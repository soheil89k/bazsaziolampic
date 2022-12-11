<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractMessage;

class DevelopmentEnvironment extends AbstractMessage
{
    protected $alertClass = 'warning';

    public function isVisible()
    {
        return ENVIRONMENT != 'production' && is_admin();
    }

    public function getMessage()
    {
        $html = '';
        $html .= '<h4><b>سیستم در حال توسعه و به روز رسانی است</b>!</h4> <hr class="hr-10"> فراموش نکنید که پس از اتمام آزمایش یا توسعه ، در فایل اصلی index.php به مرحله تولید بازگردید.';
        $html .= '<br /><br />لطفاً توجه داشته باشید که در حالت توسعه ممکن است برخی خطاها و هشدارهای منسوخ شدن را مشاهده کنید ، به همین دلیل ، همیشه توصیه می شود اگر در واقع برخی ویژگی ها/ماژول ها را توسعه نمی دهید یا سعی نمی کنید برخی از کدها را آزمایش کنید ، محیط را روی "تولید" قرار دهید..';

        return $html;
    }
}
