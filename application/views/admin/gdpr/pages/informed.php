<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="no-mtop">
    <?php echo _l('gdpr_right_to_be_informed'); ?>
    <small>
        <a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-to-be-informed/" target="_blank"><?php echo _l('learn_more'); ?></a>
    </small>
</h4>
<hr class="hr-panel-heading" />
<?php render_yes_no_option('gdpr_enable_terms_and_conditions','فعال کردن قوانین و مقررات برای ثبت نام و پورتال مشتریان'); ?>
<hr />
<?php render_yes_no_option('gdpr_enable_terms_and_conditions_lead_form','فعال کردن قوانین و مقررات در فرم های دعوت مشتری از سایت دیگر'); ?>
<hr />
<?php render_yes_no_option('gdpr_enable_terms_and_conditions_ticket_form',' فعال کردن قوانین و مقررات در فرم تیکت'); ?>
<hr />
<?php render_yes_no_option('gdpr_show_terms_and_conditions_in_footer','نمایش قوانین مقررات در فوتر پورتال مشتریان'); ?>
<hr />
<?php render_yes_no_option('gdpr_enable_terms_and_conditions_estimate_request_form','فعال کردن قوانین و مقررات در فرم های درخواست پیش فاکتور'); ?>
<hr class="hr-panel-heading" />
<p class="">
    <?php echo _l('terms_and_conditions') ?>
    <br />
    <a href="<?php echo terms_url(); ?>" target="_blank"><?php echo terms_url(); ?></a>
</p>
<?php echo render_textarea('settings[terms_and_conditions]','',get_option('terms_and_conditions'),array(),array(),'','tinymce'); ?>
<hr />
<p class="">
    <i class="fa fa-question-circle" data-toggle="tooltip" data-title="ممکن است بخواهید سیاست حفظ حریم خصوصی را در متن قوانین و مقررات بگنجانید."></i> سیاست حفظ حریم خصوصی
    <br />
    <a href="<?php echo privacy_policy_url(); ?>" target="_blank"><?php echo privacy_policy_url(); ?></a>
</p>
<?php echo render_textarea('settings[privacy_policy]','',get_option('privacy_policy'),array(),array(),'','tinymce'); ?>
