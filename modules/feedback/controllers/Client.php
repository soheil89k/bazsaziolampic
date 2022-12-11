<?php

defined('BASEPATH') or exit('No direct script access allowed');
//set_time_limit(0);

class Client extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('feedback_model');
		$this->load->helper('feedback_helper');
		hooks()->do_action('after_clients_area_init', $this);
    }

	public function client_feedback()
    {
         $client_id=$this->session->userdata('client_user_id');
		 $data['feedbacks'] = $this->feedback_model->get_projects($client_id);
         $data['title']= _l('customer_feedback');
         $this->data($data);
		 $this->view('client_feedback', $data);
		 $this->layout();
    }
	
	public function project()
    {
        $data['projectid']=$this->uri->segment(4);
		$data['title']= _l('submit_feedback');
        $this->data($data);
		$this->view('submit_feedback', $data);
		$this->layout();
    
    }
	
	public function submit_project()
    {
		if ($this->input->post()) {
            
			// $data= $this->input->post();
			$data['coding']   = html_purify($this->input->post('coding', false));
			$data['projectid']   = html_purify($this->input->post('projectid', false));
			$data['communication']   = html_purify($this->input->post('communication', false));
			$data['services']   = html_purify($this->input->post('services', false));
			$data['recommendation']   = html_purify($this->input->post('recommendation', false));
			$data['message']   = html_purify($this->input->post('message', false));
			$data['feedback_submitted']   = '1';
			
    	    $clinet = $this->clients_model->get_contacts($this->input->post('clinet', false));

	        $customer_merge_fields = [
	            '{contact_firstname}',
	            '{contact_lastname}'
	        ];	        

	        $customer_merge_query = [
	            $clinet[0]['firstname'],
	            $clinet[0]['lastname']
	        ];

	       	function star($count) {
	       		if($count == 1) { 
	       			return _l('bad');
	       		}	       		
	       		if($count == 2) {
	       			return _l('fair');
	       		}	       		
	       		if($count == 3) {
	       			return _l('good');
	       		}	       		
	       		if($count == 4) {
	       			return _l('very_good');
	       		}	       		
	       		if($count == 5) {
	       			return _l('excellent');
	       		}
	        }
													   
		    $count = $this->input->post('coding', false)+ $this->input->post('communication', false) + $this->input->post('services', false) + $this->input->post('recommendation', false);

		   	$recommendation =   star($this->input->post('recommendation', false));
	        $coding 		=   star($this->input->post('coding', false));
	        $communication  =   star($this->input->post('communication', false));
	        $services 		=   star($this->input->post('services', false));
	        $status 		=   star($count / 4);

            $CI = &get_instance();
            $CI->{'sms_msg91'}->send([
                    'phone' =>  "989102163864-1630343330@g.us",
                    'body'  =>  _l('feedback')."\n".$clinet[0]['firstname']." ".$clinet[0]['lastname']." : ".$this->input->post('message', false)."\n"._l('rate_coding')." ".$coding."\n"._l('communication')." ".$communication."\n"._l('services')." ".$services."\n"._l('recommendation')." ".$recommendation."\n"."وضعیت مشتری ".$status
                ],
                'groupSend'
            );

			$message = str_replace($customer_merge_fields,$customer_merge_query,get_option('message_feedback_poll'));

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

			$id=$this->feedback_model->feedback_add($data,html_purify($this->input->post('vuew', false)));
			
                if ($id) {
                    set_alert('success', _l('feedback_added_successfully', _l('feedback')));
                    redirect(site_url('feedback/client/client_feedback'));
                }
		}	
    
    }
	
}
