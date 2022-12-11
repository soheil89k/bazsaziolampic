<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Satisfaction extends ClientsController
{
    public function index($coustomer,$projectId,$hash)
    {
        $clinet = $this->clients_model->get_contacts($coustomer);
        $feed   = $this->feedback_model->get_feed($projectId);

        if(!$feed) {
            return redirect('https://faracity.com');
        }

        $data['vuew'] = $projectId;
        $data['client'] = $clinet;
        $data['feed']   = $feed;
        $data['title']  =  'رضایت مشتری - ' . get_option('companyname');
        $this->data($data);
        $this->view('satisfaction');
        $this->layout();
    }
}