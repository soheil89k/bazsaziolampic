<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel_s">
					<div class="panel-body">
					<h4 class="no-margin">
					<?php echo $title; ?>
					</h4>
					<hr class="hr-panel-heading" />
		                <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'import_form')) ;?>

						<p class="bold"><?php echo _l('whatsapp_number_customer'); ?></p>
						<?php echo render_input('file_csv','choose_csv_file','','file'); ?>

						<?php $value = (isset($announcement) ? $announcement->name : ''); ?>
						<?php echo render_input('name','announcement_name',$value); ?>

						<p class="bold"><?php echo _l('announcement_message'); ?></p>
						<?php $contents = ''; if(isset($announcement)){$contents = $announcement->message;} ?>
						<?php echo render_textarea('message','',$contents,array(),array(),'',''); ?>
						<!-- <?php echo render_textarea('message','',$contents,array(),array(),'','tinymce'); ?> -->

						<p class="bold"><?php echo _l('whatsapp_number_photo'); ?></p>
						<input type="file" extension="<?php echo str_replace(['.', ' '], '', get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">

						<br />

						<div class="col-md-12">
							<div class="form-group">
								<label for="decimal_separator">انتخاب گروه مشتری</label>
								<select name="groupCustomer" class="form-control">
									<option value="all" selected>همه مشتریان</option>
									<?php 
									$CI = &get_instance();
									foreach($CI->clients_model->get_groups() as $group) {
										echo '<option value="'.$group['id'].'">'.$group['name'].'</option>';
									}
									?>
								</select>
							</div>
						</div>

						<br />
						<br />
						<br />
						<br />

						<div class="checkbox checkbox-primary checkbox-inline">
							<input type="checkbox" name="showtostaff" id="showtostaff" <?php if(isset($announcement)){if($announcement->showtostaff == 1){echo 'checked';} } ?>>
							<label for="showtostaff"><?php echo _l('whatsapp_send_sattf'); ?></label>
						</div>
						<div class="checkbox checkbox-primary checkbox-inline">
							<input type="checkbox" name="showtousers" id="showtousers" <?php if(isset($announcement)){if($announcement->showtousers == 1){echo 'checked';}} ?>>
							<label for="showtousers"><?php echo _l('whatsapp_send_coustomer'); ?></label>
						</div>						
						<div class="checkbox checkbox-primary checkbox-inline">
							<input type="checkbox" name="sendleadr" id="sendleadr" <?php if(isset($announcement)){if($announcement->showtousers == 1){echo 'checked';}} ?>>
							<label for="showtousers"><?php echo _l('whatsapp_send_leadr'); ?></label>
						</div>
						<div class="checkbox checkbox-primary checkbox-inline">
							<input type="checkbox" name="showname" id="showname" <?php if(isset($announcement)){if($announcement->showname == 1){echo 'checked';}} ?>>
							<label for="showname"><?php echo _l('whatsapp_send_sms'); ?></label>
						</div>
						<button type="submit" class="btn btn-info pull-right"><?php echo _l('send'); ?></button>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	$(function(){
		appValidateForm($('form'),{name:'required'});
	});
</script>
</body>
</html>
