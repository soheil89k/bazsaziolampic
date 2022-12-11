<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Announcements extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('announcements_model');
    }

    /* List all announcements */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('announcements');
        }
        $data['title'] = _l('announcements');
        $this->load->view('admin/announcements/manage', $data);
    }

    /* Edit announcement or add new if passed id */
    public function announcement($id = '')
    {
        if (!is_admin()) {
            access_denied('Announcement');
        }
        if ($this->input->post()) {

            $CI = &get_instance();

            if($this->input->post('showtousers')) {

                if($this->input->post('groupCustomer') == 'all') {
                    $this->load->model('clients_model');
                    foreach($this->clients_model->get('', ['active' => 1]) as $client) {
                        if($this->input->post('showname')) {
                            $CI->{'sms_twilio'}->send(
                                str_pad($client['phonenumber'], 11, '0', STR_PAD_LEFT),
                                clear_textarea_breaks(nl2br($this->input->post('message', false)))
                            );
                        }

                        $this->send($client['phonenumber'],$CI->input->post('message'),$this->base64('attachments'));
                    }                    
                }else{
                    $this->db->where('groupid', $this->input->post('groupCustomer'));
                    $cutomer_ID = $this->db->get(db_prefix() . 'customer_groups')->result_array();
                    foreach($cutomer_ID as $customer) {
                        $phonenumber = $this->clients_model->get_contacts($customer['customer_id'])[0]['phonenumber'];
                        if(empty($phonenumber)) {

                        }else{
                            if($this->input->post('showname')) {
                                $CI->{'sms_twilio'}->send(
                                    str_pad($phonenumber, 11, '0', STR_PAD_LEFT),
                                    clear_textarea_breaks(nl2br($this->input->post('message', false)))
                                );
                            }

                            $this->send($phonenumber,$CI->input->post('message'),$this->base64('attachments'));
                        }

                    }
                }

            }             

            if($this->input->post('sendleadr')) {
                $this->load->model('leads_model');
                foreach($this->leads_model->get() as $lead) {
                    if($this->input->post('showname')) {
                        $CI->{'sms_twilio'}->send(
                            str_pad($lead['phonenumber'], 11, '0', STR_PAD_LEFT),
                            clear_textarea_breaks(nl2br($this->input->post('message', false)))
                        );
                    }

                    $this->send($lead['phonenumber'],$CI->input->post('message'),$this->base64('attachments'));
                }
            }    

            if($this->input->post('showtostaff')) {
                foreach($this->staff_model->get('', ['active' => 1]) as $user) {
                    if($this->input->post('showname')) {
                        $CI->{'sms_twilio'}->send(
                            str_pad($user['phonenumber'], 11, '0', STR_PAD_LEFT),
                            clear_textarea_breaks(nl2br($this->input->post('message', false)))
                        );
                    }

                    $this->send($user['phonenumber'],$CI->input->post('message'),$this->base64('attachments'));
                }
            }

            $this->load->library('import/import_customers', [], 'import');

            if($_FILES['file_csv']['name']) {
                if ($this->input->post()
                    && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
                    $this->import->setSimulation($this->input->post('simulate'))
                                  ->setTemporaryFileLocation($_FILES['file_csv']['tmp_name'])
                                  ->setFilename($_FILES['file_csv']['name'])
                                  ->perform();


                    $number = $this->import->getRows();

                    if (!$this->import->isSimulation()) {
                        set_alert('success', _l('import_total_imported', $this->import->totalImported()));
                    }
                }
                
                foreach($number as $phone) {
                    $this->send($phone[0],$CI->input->post('message'),$this->base64('attachments'));
                }
            }
            
            $data            = $this->input->post();
            $data['message'] = html_purify($this->input->post('message', false));
            // if ($id == '') {
            //     $id = $this->announcements_model->add($data);
            //     if ($id) {
            //         set_alert('success', _l('added_successfully', _l('announcement')));
            //         redirect(admin_url('announcements/view/' . $id));
            //     }
            // } else {
            //     $success = $this->announcements_model->update($data, $id);
            //     if ($success) {
            //         set_alert('success', _l('updated_successfully', _l('announcement')));
            //     }
            //     redirect(admin_url('announcements/view/' . $id));
            // }
        }
        if ($id == '') {
            $title = _l('add_new', _l('announcement_lowercase'));
        } else {
            $data['announcement'] = $this->announcements_model->get($id);
            $title                = _l('edit', _l('announcement_lowercase'));
        }
        $data['title'] = $title;
        $this->load->view('admin/announcements/announcement', $data);
    }

    public function view($id)
    {
        if (is_staff_member()) {
            $announcement = $this->announcements_model->get($id);
            if (!$announcement) {
                blank_page(_l('announcement_not_found'));
            }
            $data['announcement']         = $announcement;
            $data['recent_announcements'] = $this->announcements_model->get('', [
                'announcementid !=' => $id,
            ], 4);
            $data['title'] = $announcement->name;
            $this->load->view('admin/announcements/view', $data);
        }
    }

    /* Delete announcement from database */
    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('announcements'));
        }
        if (!is_admin()) {
            access_denied('Announcement');
        }
        $response = $this->announcements_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('announcement')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('announcement_lowercase')));
        }
        redirect($_SERVER['HTTP_REFERER']);
    }


    protected function send($number,$message, $photo = null){
        $CI = &get_instance();

        $CI->{'sms_msg91'}->send([
                'phone' =>  preg_replace("/^0/", '98', convert2english(str_pad($number, 11, '0', STR_PAD_LEFT))),
                'body'  =>  clear_textarea_breaks(nl2br($message))
            ],
            'message'
        );

        if($_FILES['attachments']['name'] OR $photo) {
            $CI->{'sms_msg91'}->send([
                    'phone' =>  preg_replace("/^0/", '98', convert2english(str_pad($number, 11, '0', STR_PAD_LEFT))),
                    'body'      => $photo,
                    'filename'  => time().'.jpg',
                    'caption'   => $this->input->post('announcement_name'),
                ],
                'sendFile'
            );
        }

    }


    protected function base64($file){
        if (isset($_FILES[$file]['name']) && $_FILES[$file]['name'] != '') {
            hooks()->do_action('before_upload_favicon_attachment');
            $path = WHATSAPP_FILES_FOLDER;
            // Get the temp file path
            $tmpFilePath = $_FILES[$file]['tmp_name'];

            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                // Getting file extension
                $path_parts = pathinfo($_FILES[$file]['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                // // Setup our new file path
                $filename    = 'whatsapp-'. time() . '.' . $extension;
                $newFilePath = $path . $filename;
                _maybe_create_upload_path($path);
                // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option($file, $filename);
                    $path = base_url('uploads/whatsapp/'.$filename);
                    return 'data:image/' . pathinfo($path, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($path));
                }
            }
        }
    }

}
