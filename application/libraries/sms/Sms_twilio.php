<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sms_twilio extends App_sms
{
    // Username Faracity
    private $username;

    // Password from Faracity
    private $password;

    // Faracity Phone Number
    private $phone;

    // link Webservice
    private $requestURL;

    public function __construct()
    {
        parent::__construct();

        $this->username   = $this->get_option('Faracity', 'account_sid');
        $this->password = $this->get_option('Faracity', 'auth_token');
        $this->phone = $this->get_option('Faracity', 'phone_number');
        $this->requestURL = $this->get_option('Faracity', 'request_url');

        $this->add_gateway('twilio', [
            'name'    => 'Faracity',
            'info'    => '<p>لطفا برای ثبت اطلاعات و فعال کردن پنل اس ام اس با شرکت <a href="http://faracity.com">فراسیتی</a> تماس بگیرید</p><hr class="hr-10" />',
            'options' => [
                [
                    'name'  => 'account_sid',
                    'label' => 'نام کاربر',
                ],
                [
                    'name'  => 'auth_token',
                    'label' => 'کلمه عبور',
                ],
                [
                    'name'  => 'phone_number',
                    'label' => 'شماره اختصاصی',
                ],                
                [
                    'name'  => 'request_url',
                    'label' => 'لینک وب سرویس',
                ],
            ],
        ]);
    }

    public function send($number, $message)
    {

        try {
            // document webservice Faracity
            $SMS = new SoapClient($this->requestURL);
            $SMS->SendSMS($this->phone,$number,$message,$this->username, $this->password,'',"send");

            $this->logSuccess($number, $message);
        } catch (Exception $e) {
            $this->set_error($e->getMessage());

            return false;
        }

        return true;
    }
}
