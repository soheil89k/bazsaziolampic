<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4><?php echo _l('whatsapp_send_coustomerـin'); ?></h4>
    <?php echo form_open('admin/leads/message_send/'.$lead->id); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="sent_to" value="<?php echo $lead->phonenumber ?> - <?php echo $lead->name ?>" class="form-control" disabled>
                    </div>
                    <hr />
                    <h5 class="bold"><?php echo _l('whatsapp_message_send'); ?></h5>
                    <?php echo render_textarea('email_template_custom','','',array(),array(),'',''); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="checkbox checkbox-primary checkbox-inline">
                        <input type="checkbox" name="sms_send" id="showtostaff">
                        <label for="showtostaff"><?php echo _l('whatsapp_send_sms'); ?></label>
                    </div>
                </div>
            </div>

            <br>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default close-send-template-modal"><?php echo _l('close'); ?></button>
                <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-info"><?php echo _l('send'); ?></button>
            </div>

    <?php echo form_close(); ?>
