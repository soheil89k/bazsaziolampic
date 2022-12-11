<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading"><?php echo _l('whatsapp_send_coustomerـin'); ?></h4>
    <?php echo form_open('admin/clients/message_send/'.$client->userid); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php
                        $selected = array();
                        $contacts = $this->clients_model->get_contacts($client->userid,array('active'=>1,'estimate_emails'=>1));
                        foreach($contacts as $contact){
                            array_push($selected,$contact['id']);
                        }
                         if(count($selected) == 0){
                                echo '<p class="text-danger">' . _l('sending_email_contact_permissions_warning',_l('customer_permission_estimate')) . '</p><hr />';
                            }
                        echo render_select('sent_to[]',$contacts,array('id','phonenumber','firstname,lastname'),'ارسال به',$selected,array('multiple'=>true),array(),'','',false);
                        ?>
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
