<?php

defined('BASEPATH') or exit('No direct script access allowed');
set_time_limit(0);

class Feedback extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('feedback_model');
    }

   
    public function index()
    {
        $data['title']= _l('create_feedback_request');
        $data['user']=  $this->feedback_model->get();
        $this->load->view('feedback', $data);
		
        if($this->input->get('remove', false)) {
        	$this->feedback_model->delete($this->input->get('remove', false));
        	return redirect(admin_url('feedback'));
        }        

        if($this->input->get('resent', false)) {

        	$feed = $this->feedback_model->get_feed($this->input->get('resent', false));
        	$clinet = $this->clients_model->get_contacts($feed[0]['customerid']);

	        $customer_merge_fields = [
	            '{contact_firstname}',
	            '{contact_lastname}',
	            '{feedback_short_url}',
	        ];	        

	        $customer_merge_query = [
	            $clinet[0]['firstname'],
	            $clinet[0]['lastname'],
	            site_url('satisfaction/' . $feed[0]['customerid'] . '/'. $feed[0]['id'] .'/' . md5($feed[0]['customerid'])),
	        ];

			$message = str_replace($customer_merge_fields,$customer_merge_query,get_option('message_feedback'));

            $CI = &get_instance();
            $CI->{'sms_msg91'}->send([
                    'phone' =>  preg_replace("/^0/", '98', convert2english(str_pad($clinet[0]['phonenumber'], 11, '0', STR_PAD_LEFT))),
                    'body'  =>  $message
                ],
                'message'
            );

            $CI->{'sms_twilio'}->send(
                str_pad($clinet[0]['phonenumber'], 11, '0', STR_PAD_LEFT),
                $message
            );

            return redirect(admin_url('feedback'));
        }

		 
        if ($this->input->post()) {

			$data['customerid']   = html_purify($this->input->post('clientid', false));
			$data['projectid']   = html_purify($this->input->post('project_id', false));
			$id=$this->feedback_model->add($data);

    	    $clinet = $this->clients_model->get_contacts($this->input->post('clientid', false));

        	update_option('message_feedback',$this->input->post('message_feedback', false));
			update_option('message_feedback_poll',$this->input->post('message_feedback_poll', false));

	        $customer_merge_fields = [
	            '{contact_firstname}',
	            '{contact_lastname}',
	            '{feedback_short_url}',
	        ];	        

	        $customer_merge_query = [
	            $clinet[0]['firstname'],
	            $clinet[0]['lastname'],
	            site_url('satisfaction/' . html_purify($this->input->post('clientid', false)) . '/'.$id.'/' . md5($this->input->post('clientid', false))),
	        ];

			$message = str_replace($customer_merge_fields,$customer_merge_query,$this->input->post('message_feedback', false));

            $CI = &get_instance();
            $CI->{'sms_msg91'}->send([
                    'phone' =>  preg_replace("/^0/", '98', convert2english(str_pad($clinet[0]['phonenumber'], 11, '0', STR_PAD_LEFT))),
                    'body'  =>  $message
                ],
                'message'
            );

            // $CI->{'sms_twilio'}->send(
            //     str_pad($clinet[0]['phonenumber'], 11, '0', STR_PAD_LEFT),
            //     $message
            // );

            if ($id) {
                set_alert('success', _l('feedback_added_successfully', _l('feedback')));
                redirect(admin_url('feedback'));
            }else{
				
			    set_alert('warning', _l('feedback_already_exists'));	
				redirect(admin_url('feedback'));
			}

		}	
    }
	
	
	
	    public function feedback_received()
    {
		$data['feedback_array']=$this->feedback_model->get_feedback();
        $data['title']= _l('feedback_received');
        $this->load->view('feedback_received', $data);
    }
	
	
	
	
	
}
