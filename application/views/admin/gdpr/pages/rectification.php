<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="no-mtop">
    <?php echo _l('gdpr_right_of_access'); ?>/<?php echo _l('gdpr_right_to_rectification'); ?>
    <small>
        <a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-of-access/" target="_blank"><?php echo _l('learn_more'); ?></a>
    </small>
</h4>
<hr class="hr-panel-heading" />
<h4 class="bold">مخاطبین</h4>
<hr class="hr-panel-heading" />
<p>
    مشتریان از طریق پورتال مشتری می توانند وارد حساب کاربری خود شده و اطلاعات شخصی خود را ببینند. به علاوه پرتال مشتریان این اجازه را به آنها می دهد که اطلاعات شخصی خود مانند نام، نام خانوادگی، آدرس ایمیل، تلفن و ... را تغییر دهند.
</p>
<p>در بخش زیر شما <b>ویژگی های بیشتری</b> را می توانید تنظیم نمایید که مشتریان بتوانند در پورتال خود تغییر دهند.</p>
<hr class="hr-panel-heading" />
<p class="font-medium">پروفایل/مخاطب</p>
<?php render_yes_no_option('allow_primary_contact_to_view_edit_billing_and_shipping', 'allow_primary_contact_to_view_edit_billing_and_shipping'); ?>
<small>به روزرسانی جزئیات حمل و نقل در پورتال مشتری تاثیری در فاکتورها، پیش فاکتورها و یادداشت های اعتباری از قبل ساخته شده ندارد.</small></p>
<hr />
<?php render_yes_no_option('allow_contact_to_delete_files', 'allow_contact_to_delete_files'); ?>
<hr class="hr-panel-heading" />
<h4 class="bold" id="access_leads">مشتریان بالقوه</h4>
<hr class="hr-panel-heading" />
<?php render_yes_no_option('gdpr_enable_lead_public_form', 'فعال کردن فرم های عمومی برای مشتریان بالقوه', 'هر مشتری بالقوه ای که به سیستم اضافه می کنید یک URL منحصر به فرد برای مشاهده اطلاعاتی که برای آن ها وارد کرده اید خواهد داشت و از طریق این آدرس یا URL می توانند این اطلاعات را ویرایش نمایند.'); ?>
<hr />
<?php render_yes_no_option('gdpr_show_lead_custom_fields_on_public_form', 'نمایش فیلدهای سفارشی مشتری بالقوه در فرم عمومی'); ?>
<hr />
<?php render_yes_no_option('gdpr_lead_attachments_on_public_form', 'نمایش فایلهای پیوست شده و آپلود شده در فرم عمومی و اجازه حذف این فایل ها توسط مشتری بالقوه'); ?>
