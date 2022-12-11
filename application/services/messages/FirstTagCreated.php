<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractPopupMessage;

class FirstTagCreated extends AbstractPopupMessage
{
    public function isVisible(...$params)
    {
        $tag_id = $params[0];

        return $tag_id == 1;
    }

    public function getMessage(...$params)
    {
        return 'تبریک! شما لولین برچسب را ایجاد کردید <br /> می دانید که می توان در قسمت تنظیمات->استایل های تم به برچسب ها رنگ اختصاص دهید؟';
    }
}
