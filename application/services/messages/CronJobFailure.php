<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractMessage;

class CronJobFailure extends AbstractMessage
{
    protected $alertClass = 'warning';

    private $hoursCheck = 48;

    public function isVisible()
    {
        $last_cron_run = get_option('last_cron_run');
        $fromCli       = get_option('cron_has_run_from_cli');

        return ($last_cron_run != '' && $fromCli == '1' && is_admin()) && ($last_cron_run <= strtotime('-' . $this->hoursCheck . ' hours'));
    }

    public function getMessage()
    {
        // Check and clean locks for all cases if the cron somehow is stuck or locked
        if (file_exists(get_temp_dir() . 'pcrm-cron-lock')) {
            @unlink(get_temp_dir() . 'pcrm-cron-lock');
        }

        if (file_exists(TEMP_FOLDER . 'pcrm-cron-lock')) {
            @unlink(TEMP_FOLDER . 'pcrm-cron-lock');
        } ?>
        <h4><b>اخطار کرون جاب</b></h4>
        <hr class="hr-10" />
        <p>
         <b>به نظر می رسد که کرون جاب شما در طی <?php echo $this->hoursCheck; ?> ساعت گذشته اجرا نشده است</b>، باید مجددا درست بودن پیکربندی کرون جاب را بررسی کنید. این پیام به صورت اتوماتیک 5 دقیقه بعد از اجرای موفقیت آمیز دوباره کرون جاب محو خواهد شد.
     </p>
     <?php
    }
}
