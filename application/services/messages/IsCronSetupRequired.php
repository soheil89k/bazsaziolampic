<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractMessage;

class IsCronSetupRequired extends AbstractMessage
{
    private $used_features = [];

    public function isVisible()
    {
        if (get_option('cron_has_run_from_cli') == 1 || !is_admin()) {
            return false;
        }

        $used_features       = [];
        $using_cron_features = 0;
        $feature             = total_rows(db_prefix() . 'reminders');
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'یادآورها');
        }

        $feature = get_option('email_queue_enabled');
        $using_cron_features += $feature;
        if ($feature == 1) {
            array_push($used_features, 'صف ایمیل');
        }

        $feature = total_rows(db_prefix() . 'leads_email_integration', [
                'active' => 1,
            ]);
        $using_cron_features += $feature;

        if ($feature > 0) {
            array_push($used_features, 'درون ریزی خودکار مشتریان بالقوه از ایمیل');
        }
        $feature = total_rows(db_prefix() . 'invoices', [
                'recurring >' => 0,
            ]);
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'فاکتور های تکرار شونده');
        }
        $feature = total_rows(db_prefix() . 'expenses', [
                'recurring' => 1,
            ]);
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'پیش فاکتور های تکرار شونده');
        }

        $feature = total_rows(db_prefix() . 'scheduled_emails');
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'ارسال فاکتورهای زمانبندی شده');
        }

        $feature = total_rows(db_prefix() . 'tasks', [
                'recurring' => 1,
            ]);
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'وظایف تکرار شونده');
        }

        $feature = total_rows(db_prefix() . 'events');
        $using_cron_features += $feature;

        if ($feature > 0) {
            array_push($used_features, 'رویدادهای سفارشی در تقویم');
        }

        $feature = total_rows(db_prefix() . 'departments', [
                'host !='     => '',
                'password !=' => '',
                'email !='    => '',
            ]);
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'درون ریزی خودکار تیکت ها با متد IMAP (تنظیمات->پشتیبانی->دپارتمان ها)');
        }

        $using_cron_features = hooks()->apply_filters('numbers_of_features_using_cron_job', $using_cron_features);
        $used_features       = hooks()->apply_filters('used_cron_features', $used_features);
        $this->used_features = $used_features;

        return $using_cron_features > 0 && get_option('hide_cron_is_required_message') == 0;
    }

    public function getMessage()
    {
        $html = '';
        $html .= 'شما از ویژگی هایی استفاده می کنید که برای درست کار کردن نیاز به تنظیم کران جاب دارد.';
        $html .= '<br />لطفا <a href="https://pegus.ir/" target="_blank">راهنمای تنظیم کران جاب </a> برای عملکرد صحیح ویژگی ها را مطالعه کنید.';
        $html .= '<br /><br /><br />';
        $html .= '<p class="bold">شما از موارد و ویژگی های زیر استفاده می کنید که نیاز به تنظیم کران جاب دارد:</p>';
        $i = 1;
        foreach ($this->used_features as $feature) {
            $html .= '&nbsp;' . $i . '. ' . $feature . '<br />';
            $i++;
        }
        $html .= '<br /><br /><a href="' . admin_url('misc/dismiss_cron_setup_message') . '" class="alert-link">دیگر این پیام را نشان نده</a>';

        return $html;
    }
}
