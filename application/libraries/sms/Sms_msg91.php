<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sms_msg91 extends App_sms
{
    private $auth_key;

    private $dlt_te_id;

    private $apiRequestUrl = 'https://360dialog.ir/Api';

    public function __construct()
    {
        parent::__construct();

        $this->auth_key  = $this->get_option('msg91', 'auth_key');
        $this->dlt_te_id = $this->get_option('msg91', 'dlt_te_id');

        // $status = (json_decode($this->status(),true)['accountStatus'] == 'authenticated') ? "<a href='settings?group=sms&logout=true' class='btn btn-danger'>قطع اتصال</a>" : "<a href='#' class='btn btn-info' onclick=qrcode()>درخواست اتصال</a>";

        $this->add_gateway('msg91', [ 
                'info' => "<p>
                    لطفا برای ثبت اطلاعات و فعال کردن پنل واتس اپ با شرکت فراسیتی تماس بگیرید
                </p>
                <div id='qrcodeimg'></div>
                <hr class='hr-10'>",
                'name'    => 'واتس اپ',
                'options' => [
                    [
                        'name'  => 'dlt_te_id',
                        'label' => 'token',
                        'info'  => '',
                    ],
                ],
            ]);
    }

    /**
     * Send sms
     *
     * @param  string $number
     * @param  string $message
     *
     * @return boolean
     */
    public function send($data, $type)
    {

        /*$arr['id'] = $data['phone'];
        $arr['message'] = $data['body'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://sv1.360dialog.ir/message/text?key='.$this->dlt_te_id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => http_build_query($arr),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
print_r($response);
        return json_decode($response, true);*/

      /*  $ex = strtotime('+10 day', 1666903954);
        if($ex<time()){
            die('finish');
            return false;
        }*/

        try {
            $client = new SoapClient("http://ippanel.com/class/sms/wsdlservice/server.php?wsdl");
            $user = get_option('sms_faracity_account_sid');
            $pass = get_option('sms_faracity_auth_token');
            $fromNum = "+989999153696";
            $toNum = $data['phone'];
            $messageContent = $data['body'];
            $op = "send";
            //If you want to send in the future  ==> $time = '2016-07-30' //$time = '2016-07-30 12:50:50'

            $time = '';

            $response = $client->SendSMS($fromNum, $toNum, $messageContent, $user, $pass, $time, $op);
        } catch (SoapFault $ex) {
            $response = $ex->faultstring;
        }

        return json_decode($response, true);
    }

    /**
     * Get the API common query string options
     *
     * @return array
     */
    protected function status()
    {
        return file_get_contents($this->apiRequestUrl.''.$this->auth_key.'/status?full=true&no_wakeup=true&token='.$this->dlt_te_id);
    }        

    /**
     * Get the API common query string options
     *
     * @return array
     */
    public function logout()
    {
        $url = $this->apiRequestUrl.''.$this->auth_key.'/logout?token='.$this->dlt_te_id;
        // Make a POST request
        $options = stream_context_create(['http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json'
            ]
        ]);
        // Send a request
        $result = file_get_contents($url, false, $options);
    }    

    /**
     * Get the API common query string options
     *
     * @return array
     */
    public function qrcode()
    {
        $img = file_get_contents($this->apiRequestUrl.''.$this->auth_key.'/qr_code?token='.$this->dlt_te_id);
        return 'data:image/png;base64,' . base64_encode($img);
    }
}
