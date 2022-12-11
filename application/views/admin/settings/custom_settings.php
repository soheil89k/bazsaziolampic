<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
      <?php /*echo form_open('admin/custom_settings/check_settings',array('id'=>'custom-settings-form')); */?>
      <div class="row">
         <div class="col-md-8 col-md-offset-2">
            <div class="panel_s">

               <div class="panel-body">
                  <h4 class="no-margin">
                      این بخش به طور کامل از سیستم حذف شد.
                     <?php /*echo $title; */?>
                  </h4>

                  <?php /*if(check_footer_load()['response'] == 'false') {
                     echo '<div class="alert alert-danger" style="margin-top: 15px;">لطفا نسبت به فعالسازی محصول خود فرم زیر را کامل کنید</div>';
                     }
                  */?>

                  <?php /*if(check_footer_load()['response'] == 'true') {
                     echo '<div class="alert alert-success" style="margin-top: 15px;">اعتبار محصول شما تا تاریخ : '. to_view_date_custom(check_footer_load()['request']['expire']).' و به مدت '.get_date_difference_in_days(date("Y-m-d H:i:s"), check_footer_load()['request']['expire']).' روز فعال است</div>';
                     }
                  */?>

                  <?php /*if(check_footer_load()['response'] == 'expire_date') {
                     echo '<div class="alert alert-warning" style="margin-top: 15px;">تاریخ استفاده محصول شما در تاریخ '. to_view_date_custom(check_footer_load()['request']['expire']) .' به اتمام رسید نسبت به فعال سازی اقدام کنید</div>';
                     }
                  */?>

                  <?php /*if(check_footer_load()['response'] == 'started') {
                     echo '<div class="alert alert-success" style="margin-top: 15px;">محصول شما با موفقیت فعال شد تاریخ اعتبار : '. to_view_date_custom(check_footer_load()['request']['expire']) .'</div>';
                     }
                  */?>

                  <hr class="hr-panel-heading" />
                  <div class="clearfix"></div>
                  <?php /*echo render_input('username','vault_username', check_footer_load()['request']['phone']); */?>
                  <?php /*echo render_input('order_id','order_id', check_footer_load()['request']['order_id']); */?>
                  <?php /*echo render_input('domain','domain', $_SERVER['SERVER_NAME']);
              */?>
                 <!-- <div class="alert alert-info" style="margin-top: 20px;">برای تمدید و خرید لایسنس به سایت <a href="https://pegus.ir">پگاسوس CRM</a> مراجعه کنید</div>-->

               </div>
            </div>
         </div>
         <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
            <button type="submit" class="btn btn-info pull-right"><?php /*echo _l('submit'); */?></button>
         </div>
      </div>
      <?php /*echo form_close(); */?>
   </div>-->
</div>
<?php init_tail(); ?>
<script>
   $(function(){
   //   init_editor('#description', {append_plugins: 'stickytoolbar'});
     appValidateForm($('#custom-settings-form'),{username:'required',order_id:'required',domain:'required'});
   });
</script>
</body>
</html>
