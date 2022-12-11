<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
    }

    /* This is admin dashboard view */
    public function index()
    {

     /*   if (check_footer_load()['response'] == 'false' OR check_footer_load()['response'] == 'expire_date') {
            redirect(admin_url('custom_settings'));
            die();
        }*/

        close_setup_menu();
        $this->load->model('departments_model');
        $this->load->model('todo_model');
        $data['departments'] = $this->departments_model->get();

        $data['todos'] = $this->todo_model->get_todo_items(0);
        // Only show last 5 finished todo items
        $this->todo_model->setTodosLimit(5);
        $data['todos_finished'] = $this->todo_model->get_todo_items(1);
        $data['upcoming_events_next_week'] = $this->dashboard_model->get_upcoming_events_next_week();
        $data['upcoming_events'] = $this->dashboard_model->get_upcoming_events();
        $data['title'] = _l('dashboard_string');

        $this->load->model('contracts_model');
        $data['expiringContracts'] = $this->contracts_model->get_contracts_about_to_expire(get_staff_user_id());

        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['activity_log'] = $this->misc_model->get_activity_log();
        // Tickets charts
        $tickets_awaiting_reply_by_status = $this->dashboard_model->tickets_awaiting_reply_by_status();
        $tickets_awaiting_reply_by_department = $this->dashboard_model->tickets_awaiting_reply_by_department();

        $data['tickets_reply_by_status'] = json_encode($tickets_awaiting_reply_by_status);
        $data['tickets_awaiting_reply_by_department'] = json_encode($tickets_awaiting_reply_by_department);

        $data['tickets_reply_by_status_no_json'] = $tickets_awaiting_reply_by_status;
        $data['tickets_awaiting_reply_by_department_no_json'] = $tickets_awaiting_reply_by_department;

        $data['projects_status_stats'] = json_encode($this->dashboard_model->projects_status_stats());
        $data['leads_status_stats'] = json_encode($this->dashboard_model->leads_status_stats());
        $data['google_ids_calendars'] = $this->misc_model->get_google_calendar_ids();
        $data['bodyclass'] = 'dashboard invoices-total-manual';
        $this->load->model('announcements_model');
        $data['staff_announcements'] = $this->announcements_model->get();
        $data['total_undismissed_announcements'] = $this->announcements_model->get_total_undismissed_announcements();

        $this->load->model('projects_model');
        $data['projects_activity'] = $this->projects_model->get_activity('', hooks()->apply_filters('projects_activity_dashboard_limit', 20));
        add_calendar_assets();
        $this->load->model('utilities_model');
        $this->load->model('estimates_model');
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();

        $this->load->model('proposals_model');
        $data['proposal_statuses'] = $this->proposals_model->get_statuses();

        $wps_currency = 'undefined';
        if (is_using_multiple_currencies()) {
            $wps_currency = $data['base_currency']->id;
        }
        $data['weekly_payment_stats'] = json_encode($this->dashboard_model->get_weekly_payments_statistics($wps_currency));

        $data['dashboard'] = true;

        $data['user_dashboard_visibility'] = get_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility');

        if (!$data['user_dashboard_visibility']) {
            $data['user_dashboard_visibility'] = [];
        } else {
            $data['user_dashboard_visibility'] = unserialize($data['user_dashboard_visibility']);
        }
        $data['user_dashboard_visibility'] = json_encode($data['user_dashboard_visibility']);

        $data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/dashboard', $data);

        /********************WHATS APP START****************************/
/*
        $data = array(
            'phoneNumber' => "09102163829",        // phonenumber of panel
            'passWord' => "l35705",              // password of panel
            'destPhoneNumbers' => "09109406409;09102163864",   // destination phonenumber - for two or more phonenumber use ; - examle 09121111111;09122222222
            'text' => "تست ارسال پیام در واتس آپ (کاظمی)",                        // text message - for messagePack use # + packCode - example : #2vbo94
            'lines' => "02191300217",              // message lines - for two or more line use - examle 09121111111;09122222222
        );

        $url = "http://whatspanel.ir/Api/SendMessage.php";

        $handler = curl_init($url);

        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($handler);
        $response = json_decode($response);

        if ($response->status == 100) {
            echo "message number : " . $response->code;
        } else {
            echo "error : " . $response->status;
        }*/


/********************WHATS APP END****************************/
        /*$CI = &get_instance();
        $data = $CI->{'sms_msg91'}->send([
            'phone' =>  '09109406409',
            'body'  =>  'با سلام همکار گرامی ' . ' در حال حاظر تیکت شما توسط ' . ' پاسخ داده شده.',
        ],
            'message'
        );*/



     //   ini_set("soap.wsdl_cache_enabled", "0");
        /*try {
            $client = new SoapClient("http://ippanel.com/class/sms/wsdlservice/server.php?wsdl");
            $user = get_option('sms_faracity_account_sid');
            $pass = get_option('sms_faracity_auth_token');
            $fromNum = "+989999153696";
            $toNum = array("9102163864","9109406409");
            $messageContent = 'سلام آقای فراستی. این پیام از سامانه crm به صورت تستی برای شما ارسال شده است. ';
            $op  = "send";
            //If you want to send in the future  ==> $time = '2016-07-30' //$time = '2016-07-30 12:50:50'

            $time = '';

            echo $client->SendSMS($fromNum,$toNum,$messageContent,$user,$pass,$time,$op);
        } catch (SoapFault $ex) {
            echo $ex->faultstring;
        }*/

        /*$client = new SoapClient("http://ippanel.com/class/sms/wsdlservice/server.php?wsdl");

        $req = number_format((int)$client->GetCredit(get_option('sms_faracity_account_sid'), get_option('sms_faracity_auth_token')));
        $req = number_format((int)$client->GetCredit(get_option('sms_faracity_account_sid'), get_option('sms_faracity_auth_token')));
        var_dump($req);
        die;
        $this->sendSMS();*/
        //die;
        /*

        var_dump($data);
        die;*/
    }

    private function sendSMS()
    {
        $url = "https://ippanel.com/services.jspd";
        //$url = 'http://sv1.360dialog.ir/message/text?key=a056ce5e-aa25-4f64-b2b5-25c7cd3c6e0c';

        $rcpt_nm = array('9109406409');
        $param = array
        (
            'uname' => 'Faracity',
            'pass' => 'fara3864',
            'from' => '',
            'message' => 'تست',
            'to' => json_encode($rcpt_nm),
            'op' => 'send'
        );

        $handler = curl_init($url);

        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

        print_r($handler);
        $response2 = curl_exec($handler);

        print_r($response2);

        $response2 = json_decode($response2);
        $res_code = $response2[0];
        $res_data = $response2[1];


        echo $res_data;
    }

    /* Chart weekly payments statistics on home page / ajax */
    public function weekly_payments_statistics($currency)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->dashboard_model->get_weekly_payments_statistics($currency));
            die();
        }
    }
}
