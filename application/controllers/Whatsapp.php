<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp extends ClientsController
{
    public function index()
    {
        $CI = &get_instance();
        echo "<img src='".$CI->{'sms_msg91'}->qrcode()."' />";
        echo "<script>
            window.setTimeout(function () {
              window.location.reload();
            }, 30000);
            </script>";
    }
}
