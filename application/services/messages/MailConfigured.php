<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractPopupMessage;

class MailConfigured extends AbstractPopupMessage
{
    public function isVisible(...$params)
    {
        $retVal = (get_option('smtp_email') != '' && get_option('email_protocol') == 'smtp' && get_option('smtp_host') != '') && get_option('_smtp_test_email_success') === '';

        if ($retVal === true) {
            add_option('_smtp_test_email_success', 1, 0);
        }

        return $retVal;
    }

    public function getMessage(...$params)
    {
        return 'تبریک! شما ویژگی های ایمیل را با موفقیت تنظیم کردید! <br /> <span style="font-size:26px;">می توانید ایمیل هایی را که مایل به ارسال آن نیستید در تنظیمات->قالب های ایمل غیرفعال کنید.</span>';
    }
}
