<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Custom_settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /* List all knowledgebase articles */
    public function index()
    {
        
        $data['title']     = _l('custom_settings');
        $this->load->view('admin/settings/custom_settings', $data);
    }

	function check_settings() 
	{
		$data = $this->input->post();
		$result = head_check($data);
		if(substr_count($result, '_')){
			$result_string = explode("_", $result);
			$domain = $result_string[1];
			set_alert('danger', _l('already_activated', $domain));
			redirect(admin_url('custom_settings'));
		}
		else if($result == 1) {
			set_alert('success', _l('successfully_activated'));
			redirect(admin_url('custom_settings'));
		}else{
			set_alert('danger', $result);
			redirect(admin_url('custom_settings'));
		}

		// $domain = trim(esc_attr( site_url() ));
	}

}
