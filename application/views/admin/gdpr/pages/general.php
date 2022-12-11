<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="no-mtop">
    <?php echo _l('settings_group_general'); ?>
</h4>
<hr class="hr-panel-heading" />
<?php render_yes_no_option('enable_gdpr','فعال کردن GDPR'); ?>
<hr />
<?php render_yes_no_option('show_gdpr_in_customers_menu','نمایش لینک GDPR در سربرگ پورتال مشتری'); ?>
<hr />
<?php render_yes_no_option('show_gdpr_link_in_footer','نمایش لینک GDPR در فوتر پورتال مشتریان'); ?>
<hr />
<p class="">
    بلوک اطلاعات بالای صفحه GDPR
</p>
<?php echo render_textarea('settings[gdpr_page_top_information_block]','',get_option('gdpr_page_top_information_block'),array(),array(),'','tinymce'); ?>
