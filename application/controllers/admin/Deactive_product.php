<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Deactive_product extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        
        $data['title']     = _l('custom_deactive_product');
        $this->load->view('admin/settings/deactive_product', $data);
    }

	function deactive() 
	{
		$data = $this->input->post();
		$result = deactive_perfex($data);
		if($result == 1){
			set_alert('success', _l('successfully_deactivated'));
            redirect(admin_url('custom_settings'));
		}
		else if($result == 2) {
			set_alert('danger', _l('deactive_limit_reached'));
			redirect(admin_url('deactive_product'));
		}else if($result == 3) {
            set_alert('danger', _l('customer_not_exist'));
			redirect(admin_url('deactive_product'));
		}else if($result == 4) {
            set_alert('danger', _l('check_your_fields'));
			redirect(admin_url('deactive_product'));
		}else {
            $this->session->setFlashdata("error_message", $result);
            set_alert('danger', $result);
			redirect(admin_url('deactive_product'));
		}

	}
	

}
