<?php
defined('BASEPATH') or exit('No direct script access allowed');


class App_message
{

    public $tabId = 0;
    public $data = [];
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();

        


    }

    public function insert()
    {
        \Database\PDO::fetch("insert into tblMessages (`groupId`, `text`) VALUES
                                                  (1,'hi')");
    }


    private function set_default_triggers()
    {
        /* print_r($this->CI->db);
         die;*/
        $customer_merge_fields = [
            '{contact_firstname}',
            '{contact_lastname}',
            '{client_company}',
            '{client_vat_number}',
            '{client_id}',
        ];

        $invoice_merge_fields = [
            '{invoice_link}',
            '{invoice_number}',
            '{invoice_duedate}',
            '{invoice_date}',
            '{invoice_status}',
            '{invoice_subtotal}',
            '{invoice_total}',
            '{invoice_amount_due}',
            '{invoice_short_url}',
        ];

        $proposal_merge_fields = [
            '{proposal_number}',
            '{proposal_id}',
            '{proposal_subject}',
            '{proposal_open_till}',
            '{proposal_subtotal}',
            '{proposal_total}',
            '{proposal_proposal_to}',
            '{proposal_link}',
            '{proposal_short_url}',
        ];

        $contract_merge_fields = [
            '{contract_id}',
            '{contract_subject}',
            '{contract_datestart}',
            '{contract_dateend}',
            '{contract_contract_value}',
            '{contract_link}',
            '{contract_short_url}',
        ];

        $triggers = [

            SMS_TRIGGER_INVOICE_OVERDUE => [
                'merge_fields' => array_merge($customer_merge_fields, $invoice_merge_fields, ['{total_days_overdue}']),
                'label' => 'اعلان سر رسید فاکتور',
                'info' => 'زمانی اجرا می شود که اعلان سر رسید فاکتور به مخاطبین مشتری ارسال شود.',
            ],

            SMS_TRIGGER_PAYMENT_RECORDED => [
                'merge_fields' => array_merge($customer_merge_fields, $invoice_merge_fields, ['{payment_total}', '{payment_date}']),
                'label' => 'پرداخت فاکتور ثبت شد',
                'info' => 'زمانی اجرا می شود که پرداخت فاکتور ثبت شود.',
            ],

            SMS_TRIGGER_ESTIMATE_EXP_REMINDER => [
                'merge_fields' => array_merge(
                    $customer_merge_fields,
                    [
                        '{estimate_link}',
                        '{estimate_number}',
                        '{estimate_date}',
                        '{estimate_status}',
                        '{estimate_subtotal}',
                        '{estimate_total}',
                        '{estimate_short_url}',
                    ]
                ),
                'label' => 'یادآور انقضای پیش فاکتور',
                'info' => 'زمانی اجرا می شود که یادآور انقضا باید برای مخاطبین مشتری ارسال شود.',
            ],

            SMS_TRIGGER_PROPOSAL_EXP_REMINDER => [
                'merge_fields' => $proposal_merge_fields,
                'label' => 'یادآور انقضای پیشنهاد',
                'info' => 'زمانی اجرا می شود که یادآور انقضا باید برای پیشنهاد ارسال شود.',
            ],

            SMS_TRIGGER_PROPOSAL_NEW_COMMENT_TO_CUSTOMER => [
                'merge_fields' => $proposal_merge_fields,
                'label' => 'نظر جدید روی پیشنهاد (به مشتری)',
                'info' => 'زمانی اجرا می شود که کارمندی روی پیشنهاد نظر ارسال می کند، اس ام اس برای شماره پیشنهاد ارسال خواهد شد(شماره مشتری یا مشتری بالقوه).',
            ],

            SMS_TRIGGER_PROPOSAL_NEW_COMMENT_TO_STAFF => [
                'merge_fields' => $proposal_merge_fields,
                'label' => 'نظر جدید روی پیشنهاد (به کارمند)',
                'info' => 'زمانی اجرا می شود که مشتری یا مشتری بالقوه روی پیشنهاد نظر ارسال می کند، اس ام اس برای ایجاد کننده پیشنهاد و کارمند تخصیص داده شده به آن ارسال می شود.',
            ],

            SMS_TRIGGER_CONTRACT_NEW_COMMENT_TO_CUSTOMER => [
                'merge_fields' => array_merge($customer_merge_fields, $contract_merge_fields),
                'label' => 'نظر جدید روی قرارداد (به مشتری)',
                'info' => 'زماین اجرا می شود که کارمند نظری روی قرارداد ارسال می کند،اس ام اس برای مخاطبین مشتری ارسال می شود.',
            ],

            SMS_TRIGGER_CONTRACT_NEW_COMMENT_TO_STAFF => [
                'merge_fields' => $contract_merge_fields,
                'label' => 'نظر جدید روی قرارداد (به کارمند)',
                'info' => 'زمانی اجرا می شود که مشتری نظری روی قرارداد ارسال می کند، اس ام اس برای ایجاد کننده قرارداد ارسال خواهد شد.',
            ],

            SMS_TRIGGER_CONTRACT_EXP_REMINDER => [
                'merge_fields' => array_merge($customer_merge_fields, $contract_merge_fields),
                'label' => 'یادآور انقضای قرارداد',
                'info' => 'زمانی اجرا می شود که یادآور انقضای قرارداد باید توسط کرون جاب برای مخاطبین مشتری ارسال شود.',
            ],

            SMS_TRIGGER_STAFF_REMINDER => [
                'merge_fields' => [
                    '{staff_firstname}',
                    '{staff_lastname}',
                    '{staff_reminder_description}',
                    '{staff_reminder_date}',
                    '{staff_reminder_relation_name}',
                    '{staff_reminder_relation_link}',
                ],
                'label' => 'یادآور کارمند',
                'info' => 'زمانی اجرا می شود که کارمند برای یک <a href="' . admin_url('misc/reminders') . '">یادآور</a> سفارشی اعلان دریافت کرده باشد.',
            ],
        ];


        $this->triggers = hooks()->apply_filters('message_triggers', $triggers);
    }
}