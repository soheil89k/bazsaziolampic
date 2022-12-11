<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <?php echo form_open('admin/deactive_product/deactive',array('id'=>'deactive-form')); ?>
      <div class="row">
         <div class="col-md-8 col-md-offset-2">
            <div class="panel_s">
               <div class="panel-body">
                  <h4 class="no-margin">
                     <?php echo $title; ?>
                  </h4>
                  <hr class="hr-panel-heading" />
                  <div class="clearfix"></div>
                  <?php echo render_input('username','vault_username'); ?>
                  <?php echo render_input('order_id','order_id'); ?>
				 
               </div>
            </div>
         </div>
         <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
            <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
         </div>
      </div>
      <?php echo form_close(); ?>
   </div>
</div>
<?php init_tail(); ?>
<script>
   $(function(){
     appValidateForm($('#deactive-form'),{username:'required',order_id:'required'});
   });
</script>
</body>
</html>
