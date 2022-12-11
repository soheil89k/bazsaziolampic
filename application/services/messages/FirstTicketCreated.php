<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractPopupMessage;

class FirstTicketCreated extends AbstractPopupMessage
{
    public function isVisible(...$params)
    {
        $ticket_id = $params[0];

        return $ticket_id == 1;
    }

    public function getMessage(...$params)
    {
        return 'اولین تیکت ایجاد شد! <br /> <span style="font-size:26px;">می دانید که می توانید فرم تیکت را از قسمت تنظیمات->تنظیمات->فرم تیکت مستقیما در وبسایت خود جاگذاری کنید؟</span>';
    }
}
