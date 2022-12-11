<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Omnipay\Omnipay;
use Money\Exception\UnknownCurrencyException;

class Mollie_gateway extends App_gateway
{

    Const TokenURL = 'v1/Token';
    Const TimeURL = 'v1/Time';
    Const TranResultURL = 'v1/TranResult';
    Const CardHashURL = 'v1/CardHash';
    Const SettlementURL = 'v1/Settlement';
    Const VerifyURL = 'v1/Verify';
    Const CancelURL = 'v1/Cancel';
    Const ReverseURL = 'v1/Reverse';
    Const URL = 'https://ipgrest.asanpardakht.ir';

    private $invoiceId = null;
    private $amount = null;
    private $username = null;
    private $password = null;
    private $callBackURL = null;
    private $merchantConfigID = null;
    private static $instance = null;

    public function __construct()
    {

         /**
         * Call App_gateway __construct function
         */
        parent::__construct();
        /**
         * REQUIRED
         * Gateway unique id
         * The ID must be alpha/alphanumeric
         */
        // $this->setId('mollie');

        // /**
        //  * REQUIRED
        //  * Gateway name
        //  */
        // $this->setName('Mollie');

        // /**
        //  * Add gateway settings
        // */
        // $this->setSettings([
        //     [
        //         'name'      => 'api_key',
        //         'encrypted' => true,
        //         'label'     => 'settings_paymentmethod_mollie_api_key',
        //     ],
        //     [
        //         'name'          => 'description_dashboard',
        //         'label'         => 'settings_paymentmethod_description',
        //         'type'          => 'textarea',
        //         'default_value' => 'پرداخت آنلاین از crm',
        //     ],
        //     [
        //         'name'          => 'currencies',
        //         'label'         => 'currency',
        //         'default_value' => 'IRR',
        //     ],
        //     [
        //         'name'          => 'test_mode_enabled',
        //         'type'          => 'yes_no',
        //         'default_value' => 1,
        //         'label'         => 'settings_paymentmethod_testing_mode',
        //     ],
        // ]);
    }

    /**
     * Process the payment
     *
     * @param  array $data
     *
     * @return mixed
     */
    public function process_payment($data)
    {

        $webhookKey    = app_generate_hash();
        $invoiceNumber = format_invoice_number($data['invoice']->id);
        $description   = str_replace('{invoice_number}', $invoiceNumber, $this->getSetting('description_dashboard'));
        $returnUrl     = site_url('gateways/mollie/verify_payment?invoiceid=' . $data['invoice']->id . '&hash=' . $data['invoice']->hash);
        $webhookUrl    = site_url('gateways/mollie/webhook/' . $webhookKey);
        $invoiceUrl    = site_url('invoice/' . $data['invoice']->id . '/' . $data['invoice']->hash);

        try {

            $result = $this->make()
            ->config("frasi4768252","Fra5649si","125805", $returnUrl)
            ->amount(substr(round($data['invoice']->total_left_to_pay)*10))
            ->invoiceId($data['invoice']->id)
            ->token();
            
        } catch (UnknownCurrencyException $e) {
            set_alert('danger', $e->getMessage());
            redirect($invoiceUrl);
        }

        // Add the token to database
        $CI = &get_instance();
        $CI->db->where('id', $data['invoiceid']);
        $CI->db->update(db_prefix() . 'invoices', [
            'token' => $data['invoice']->hash,
        ]);

        // $contact = $this->ci->clients_model->get_contacts($data['invoice']->clientid);
        // foreach ($contact as $contact_id) {
        //     $CI->{'sms_msg91'}->send([
        //             'phone' =>  preg_replace("/^0/", '98', convert2english(str_pad($contact_id['phonenumber'], 11, '0', STR_PAD_LEFT))),
        //             'body'  =>  $CI->app_sms->get_trigger_value(SMS_TRIGGER_PAYMENT_RECORDED)
        //         ],
        //         'message'
        //     );
        // }
        // $CI->{'sms_msg91'}->send([
        //         'phone' =>  "989902211201-1631174844@g.us",
        //         'body'  =>  $description."\n".$invoiceUrl
        //     ],
        //     'groupSend'
        // );


        // $CI->{'sms_twilio'}->send(
        //     str_pad($contact_id['phonenumber'], 11, '0', STR_PAD_LEFT),$CI->app_sms->get_trigger_value(SMS_TRIGGER_PAYMENT_RECORDED));

        redirect(site_url('https://faracity.com/successful-payment/'));

    }

    public static function make(){
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function config($username,$password,$merchantConfigID,$callBackURL = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->callBackURL = $callBackURL;
        $this->merchantConfigID = $merchantConfigID;

        return self::$instance;
    }

    public function invoiceId($id)
    {
        $this->invoiceId = $id;
        return self::$instance;
    }

    public function amount($amount)
    {
        $this->amount = $amount;
        return self::$instance;
    }

    public function time()
    {
        return $this->callAPI('GET',self::TimeURL);
    }

    public function TranResult()
    {
        $res = $this->callAPI('GET',self::TranResultURL.'?'.http_build_query([
                'merchantConfigurationId' => $this->merchantConfigID,
                'localInvoiceId' => $this->invoiceId
            ]));
        return ['code' => $res['code'],'content' => json_decode($res['content'],true)];
    }

    public function CardHash()
    {
        return $this->callAPI('GET',self::CardHashURL.'?'.http_build_query([
            'merchantConfigurationId' => $this->merchantConfigID,
                'localInvoiceId' => $this->invoiceId
            ]));
    }

    public function token()
    {
        $this->callBackURL .= strpos($this->callBackURL, '?') === false? '?':'&';
        return $this->callAPI('POST',self::TokenURL,[
                'serviceTypeId' => 1,
                'merchantConfigurationId' => $this->merchantConfigID,
                'localInvoiceId' => $this->invoiceId,
                'amountInRials' => $this->amount,
                'localDate' => (string)(new DateTime('Asia/Tehran'))->format('Ymd His'),
                'callbackURL' => $this->callBackURL.http_build_query(['invoice' => $this->invoiceId]),
                'paymentId' => 0,
                'additionalData' => '',
        ]);
    }

    public function settlement($transId)
    {
       return $this->callAPI('POST',self::SettlementURL,[
            'merchantConfigurationId' => $this->merchantConfigID,
            'payGateTranId' => $transId
        ]);
    }

    public function verify($transId)
    {
       return $this->callAPI('POST',self::VerifyURL,[
            'merchantConfigurationId' => $this->merchantConfigID,
            'payGateTranId' => $transId
        ]);
    }

    public function reverse($transId)
    {
       return $this->callAPI('POST',self::ReverseURL,[
            'merchantConfigurationId' => $this->merchantConfigID,
            'payGateTranId' => $transId
        ]);
    }

    public function cancel($transId)
    {
       return $this->callAPI('POST',self::CancelURL,[
            'merchantConfigurationId' => $this->merchantConfigID,
            'payGateTranId' => $transId
        ]);
    }

    protected  function  callAPI($method, $url, $data = false)
    {
        $curl = curl_init();
        $url = self::URL.'/'.$url;
        switch ($method)
        {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data){
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            default:
                if ($data){
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Usr: '.$this->username,
            'Pwd: '.$this->password,
            'Content-Type: application/json',
        ]);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        $result = curl_exec($curl);
        if (curl_errno($curl))
            return ['content' => curl_error($curl), 'code' => curl_errno($curl)];       
        
        $httpcode = curl_getinfo($curl);
        curl_close($curl);

        return ['content' => $result,'code' => $httpcode['http_code']];
    }

    public static function redirect($token,$mobile = null)
    {
        echo '<html>';
        echo '<body>';
        echo '<script language="javascript" type="text/javascript">
                 function postRefId(refIdValue,mobile) {
                     var form = document.createElement("form");
                     form.setAttribute("method", "POST");
                     form.setAttribute("action",
                     "https://asan.shaparak.ir");
                     form.setAttribute("target", "_self");
                     var hiddenField = document.createElement("input");
                     hiddenField.setAttribute("name", "RefId");
                     hiddenField.setAttribute("value", refIdValue);
                     form.appendChild(hiddenField);
                     var mobileField = document.createElement("input");
                     mobileField.setAttribute("name", "mobileap");
                     mobileField.setAttribute("value", mobile);
                     form.appendChild(mobileField);
                     document.body.appendChild(form);
                     form.submit();
                     document.body.removeChild(form);
            } 
            postRefId('.$token.','.$mobile.')
        </script>';
        echo '</body>';
        echo '</html>';
    }

}
