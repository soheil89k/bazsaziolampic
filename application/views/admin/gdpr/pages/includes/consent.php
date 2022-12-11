<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="consentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <?php echo form_open('', ['id'=>'consentForm']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">هدف جدید توافق</h4>
      </div>
      <div class="modal-body">
       <?php $value = ( isset($purpose) ? $purpose->name : '');
       $attrs = array();
       if(isset($purpose) && $purpose->total_usage > 0) {
          $attrs['disabled'] = true;
       }
        ?>
       <?php echo render_input( 'name', 'نام / هدف', $value,'text', $attrs); ?>
       <?php $value = (isset($purpose) ? $purpose->description : ''); ?>
       <?php echo render_textarea('description','توضیحات', $value, array('placeholder'=>'مختصرا هدف این توافق را توضیح دهید. به عنوان مثال توضیح دهید که چرا این داده ها و اطلاعات باید استفاده شوند.','rows'=>10)); ?>
     </div>
     <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div><!-- /.modal-content -->
  <?php echo form_close(); ?>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
