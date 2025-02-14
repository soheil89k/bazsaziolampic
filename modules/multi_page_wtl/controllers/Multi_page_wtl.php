<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Multi_page_wtl extends ClientsController {
	public function index() {
		show_404();
	}

	/**
	 * Web to lead form
	 * User no need to see anything like LEAD in the url, this is the reason the method is named form
	 * @param  string $key web to lead form key identifier
	 * @return mixed
	 */
	public function form($key) {
		$this->load->model('leads_model');
		$form = $this->leads_model->get_form([
			'form_key' => $key,
			'is_mpwtl' => 1,
		]);

		if (!$form) {
			show_404();
		}

		// Change the locale so the validation loader function can load
		// the proper localization file
		$GLOBALS['locale'] = get_locale_key($form->language);

		$data['form_fields'] = json_decode($form->form_data);
		if (!$data['form_fields']) {
			$data['form_fields'] = [];
		}
		if ($this->input->post('key')) {
			if ($this->input->post('key') == $key) {
				$post_data = $this->input->post();
				$required = [];

				foreach ($data['form_fields'] as $field) {
					if (isset($field->required)) {
						$required[] = $field->name;
					}
				}
				if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions_lead_form') == 1) {
					$required[] = 'accept_terms_and_conditions';
				}

				foreach ($required as $field) {
					if ($field == 'file-input') {
						continue;
					}
					if (!isset($post_data[$field]) || isset($post_data[$field]) && empty($post_data[$field])) {
						$this->output->set_status_header(422);
						die;
					}
				}

				if (show_recaptcha() && $form->recaptcha == 1) {
					if (!do_recaptcha_validation($post_data['g-recaptcha-response'])) {
						echo json_encode([
							'success' => false,
							'message' => _l('recaptcha_error'),
						]);
						die;
					}
				}

				if (isset($post_data['g-recaptcha-response'])) {
					unset($post_data['g-recaptcha-response']);
				}

				unset($post_data['key']);

				$regular_fields = [];
				$custom_fields = [];
				foreach ($post_data as $name => $val) {
					if (strpos($name, 'form-cf-') !== false) {
						array_push($custom_fields, [
							'name' => $name,
							'value' => $val,
						]);
					} else {
						if ($this->db->field_exists($name, db_prefix() . 'leads')) {
							if ($name == 'country') {
								if (!is_numeric($val)) {
									if ($val == '') {
										$val = 0;
									} else {
										$this->db->where('iso2', $val);
										$this->db->or_where('short_name', $val);
										$this->db->or_where('long_name', $val);
										$country = $this->db->get(db_prefix() . 'countries')->row();
										if ($country) {
											$val = $country->country_id;
										} else {
											$val = 0;
										}
									}
								}
							} elseif ($name == 'address') {
								$val = trim($val);
								$val = nl2br($val);
							}

							$regular_fields[$name] = $val;
						}
					}
				}
				$success = false;
				$insert_to_db = true;

				if ($form->allow_duplicate == 0) {
					$where = [];
					if (!empty($form->track_duplicate_field) && isset($regular_fields[$form->track_duplicate_field])) {
						$where[$form->track_duplicate_field] = $regular_fields[$form->track_duplicate_field];
					}
					if (!empty($form->track_duplicate_field_and) && isset($regular_fields[$form->track_duplicate_field_and])) {
						$where[$form->track_duplicate_field_and] = $regular_fields[$form->track_duplicate_field_and];
					}

					if (count($where) > 0) {
						$total = total_rows(db_prefix() . 'leads', $where);

						$duplicateLead = false;
						/**
						 * Check if the lead is only 1 time duplicate
						 * Because we wont be able to know how user is tracking duplicate and to send the email template for
						 * the request
						 */
						if ($total == 1) {
							$this->db->where($where);
							$duplicateLead = $this->db->get(db_prefix() . 'leads')->row();
						}

						if ($total > 0) {
							// Success set to true for the response.
							$success = true;
							$insert_to_db = false;
							if ($form->create_task_on_duplicate == 1) {
								$task_name_from_form_name = false;
								$task_name = '';
								if (isset($regular_fields['name'])) {
									$task_name = $regular_fields['name'];
								} elseif (isset($regular_fields['email'])) {
									$task_name = $regular_fields['email'];
								} elseif (isset($regular_fields['company'])) {
									$task_name = $regular_fields['company'];
								} else {
									$task_name_from_form_name = true;
									$task_name = $form->name;
								}
								if ($task_name_from_form_name == false) {
									$task_name .= ' - ' . $form->name;
								}

								$description = '';
								$custom_fields_parsed = [];
								foreach ($custom_fields as $key => $field) {
									$custom_fields_parsed[$field['name']] = $field['value'];
								}

								$all_fields = array_merge($regular_fields, $custom_fields_parsed);
								$fields_labels = [];
								foreach ($data['form_fields'] as $f) {
									if ($f->type != 'header' && $f->type != 'paragraph' && $f->type != 'file') {
										$fields_labels[$f->name] = $f->label;
									}
								}

								$description .= $form->name . '<br /><br />';
								foreach ($all_fields as $name => $val) {
									if (isset($fields_labels[$name])) {
										if ($name == 'country' && is_numeric($val)) {
											$c = get_country($val);
											if ($c) {
												$val = $c->short_name;
											} else {
												$val = 'Unknown';
											}
										}

										$description .= $fields_labels[$name] . ': ' . $val . '<br />';
									}
								}

								$task_data = [
									'name' => $task_name,
									'priority' => get_option('default_task_priority'),
									'dateadded' => date('Y-m-d H:i:s'),
									'startdate' => date('Y-m-d'),
									'addedfrom' => $form->responsible,
									'status' => 1,
									'description' => $description,
								];

								$task_data = hooks()->apply_filters('before_add_task', $task_data);
								$this->db->insert(db_prefix() . 'tasks', $task_data);
								$task_id = $this->db->insert_id();
								if ($task_id) {
									$attachment = handle_task_attachments_array($task_id, 'file-input');

									if ($attachment && count($attachment) > 0) {
										$this->tasks_model->add_attachment_to_database($task_id, $attachment, false, false);
									}

									$assignee_data = [
										'taskid' => $task_id,
										'assignee' => $form->responsible,
									];
									$this->tasks_model->add_task_assignees($assignee_data, true);

									hooks()->do_action('after_add_task', $task_id);
									if ($duplicateLead && $duplicateLead->email != '') {
										send_mail_template('lead_web_form_submitted', $duplicateLead);
									}
								}
							}
						}
					}
				}

				if ($insert_to_db == true) {

					$regular_fields['status'] = $form->lead_status;
					if ((isset($regular_fields['name']) && empty($regular_fields['name'])) || !isset($regular_fields['name'])) {
						$regular_fields['name'] = 'Unknown';
					}
					$regular_fields['source'] = $form->lead_source;
					$regular_fields['addedfrom'] = 0;
					$regular_fields['lastcontact'] = null;
					$regular_fields['assigned'] = $form->responsible;
					$regular_fields['dateadded'] = date('Y-m-d H:i:s');
					$regular_fields['from_form_id'] = $form->id;
					$regular_fields['is_public'] = $form->mark_public;
					$this->db->insert(db_prefix() . 'leads', $regular_fields);
					$lead_id = $this->db->insert_id();

					hooks()->do_action('lead_created', [
						'lead_id' => $lead_id,
						'web_to_lead_form' => true,
					]);

					$success = false;
					if ($lead_id) {
						$success = true;

						$this->leads_model->log_lead_activity($lead_id, 'not_lead_imported_from_form', true, serialize([
							$form->name,
						]));
						// /handle_custom_fields_post
						$custom_fields_build['leads'] = [];
						foreach ($custom_fields as $cf) {
							$cf_id = strafter($cf['name'], 'form-cf-');
							$custom_fields_build['leads'][$cf_id] = $cf['value'];
						}

						handle_custom_fields_post($lead_id, $custom_fields_build);

						$this->leads_model->lead_assigned_member_notification($lead_id, $form->responsible, true);

						handle_lead_attachments($lead_id, 'file-input', $form->name);

						if ($form->notify_lead_imported != 0) {
							if ($form->notify_type == 'assigned') {
								$to_responsible = true;
							} else {
								$ids = @unserialize($form->notify_ids);
								$to_responsible = false;
								if ($form->notify_type == 'specific_staff') {
									$field = 'staffid';
								} elseif ($form->notify_type == 'roles') {
									$field = 'role';
								}
							}

							if ($to_responsible == false && is_array($ids) && count($ids) > 0) {
								$this->db->where('active', 1);
								$this->db->where_in($field, $ids);
								$staff = $this->db->get(db_prefix() . 'staff')->result_array();
							} else {
								$staff = [
									[
										'staffid' => $form->responsible,
									],
								];
							}
							$notifiedUsers = [];
							foreach ($staff as $member) {
								if ($member['staffid'] != 0) {
									$notified = add_notification([
										'description' => 'not_lead_imported_from_form',
										'touserid' => $member['staffid'],
										'fromcompany' => 1,
										'fromuserid' => null,
										'additional_data' => serialize([
											$form->name,
										]),
										'link' => '#leadid=' . $lead_id,
									]);
									if ($notified) {
										array_push($notifiedUsers, $member['staffid']);
									}
								}

								$valueAll = "فرم ساز crm مشتری \n";
								foreach ($post_data as $name => $val) {
									$valueAll .= $name." : ".$val."\n";
								}

								$CI = &get_instance();
						        $CI->{'sms_msg91'}->send([
						                'phone' =>  preg_replace("/^0/", '98', convert2english(str_pad($member['phonenumber'], 11, '0', STR_PAD_LEFT))),
						                'body'  =>  $valueAll
						            ],
						            'message'
						        );
							}

							$CI = &get_instance();
					        $CI->{'sms_msg91'}->send([
					                'phone' =>  preg_replace("/^0/", '98', convert2english(str_pad($regular_fields['phonenumber'], 11, '0', STR_PAD_LEFT))),
					                'body'  =>  strip_tags($form->success_submit_msg)
					            ],
					            'message'
					        );

				            $CI->{'sms_twilio'}->send(
				                str_pad($regular_fields['phonenumber'], 11, '0', STR_PAD_LEFT),
				                $form->success_submit_msg
				            );
					        

						}
						if (isset($regular_fields['email']) && $regular_fields['email'] != '') {
							$lead = $this->leads_model->get($lead_id);
							send_mail_template('lead_web_form_submitted', $lead);
						}
					}
				} // end insert_to_db
				if ($success == true) {
					if (!isset($lead_id)) {
						$lead_id = 0;
					}
					if (!isset($task_id)) {
						$task_id = 0;
					}
					hooks()->do_action('web_to_lead_form_submitted', [
						'lead_id' => $lead_id,
						'form_id' => $form->id,
						'task_id' => $task_id,
					]);
				}
				echo json_encode([
					'success' => $success,
					'message' => $form->success_submit_msg,
				]);
				die;
			}
		}

		$data['form'] = $form;
		if ($form->form_theme == 'elegant') {
			$this->load->view('forms/web_to_lead', $data);
		} else if ($form->form_theme == 'classic') {
			$this->load->view('forms/web_to_lead_classic', $data);
		} else if ($form->form_theme == 'standard') {
			$this->load->view('forms/web_to_lead_standard', $data);
		}
	}

	//CSS OVERIDE
	public function mpwtl_custom_css($key) {
		header("Content-type: text/css");
		$form_color = '#7b1fa2';
		$form_bd_color = '#ffffff';

		$this->load->model('leads_model');
		$form = $this->leads_model->get_form([
			'form_key' => $key,
			'is_mpwtl' => 1,
		]);

		if (isset($form->form_color) && !empty($form->form_color)) {
			$form_color = $form->form_color;
		}

		if (isset($form->form_bg_color) && !empty($form->form_bg_color)) {
			$form_bg_color = $form->form_bg_color;
		}

		$output = '.steps fieldset {border-top: 9px solid ' . $form_color . ' !important; background: ' . $form_bg_color . ' !important;}';
		$output .= '.steps input:focus, .steps textarea:focus{border: 1px solid ' . $form_color . ' !important;}';
		$output .= '.steps .action-button, .action-button {background: ' . $form_color . ' !important;}';
		$output .= '#progressbar li.active:before,  #progressbar li.active:after{ background: ' . $form_color . ' !important;}';
		$output .= '.nav-tabs.wizard li.completed>* {background-color: ' . $form_color . ' !important;border-color: ' . $form_color . ' !important;}';
		$output .= '.nav-tabs.wizard li.active>* {background-color: ' . $form_color . ' !important;border-color: ' . $form_color . ' !important;}';
		$output .= '.current_span{font-size: 30px; font-weight: bold; color: ' . $form_color . '}';
		$output .= '.stroke_span{font-size: 25px; font-weight: bold;}';
		$output .= '.total_span{font-size: 16px; font-weight: bold;}';
		$output .= '.form-text-color{color:' . $form_color . ';}';
		echo $output;
		die();
	}
}
