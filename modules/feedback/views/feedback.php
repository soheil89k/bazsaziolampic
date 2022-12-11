<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
        
         <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'feedback-form','class'=>'')) ;?>
         <div class="col-md-6">
            <div class="panel_s">
               <div class="panel-body">
                  
                  <h4 class="no-margin"><?php _l('create_feedback_request'); ?></h4>
                  <hr class="hr-panel-heading" />
                  <div class="form-group select-placeholder">
                     <label for="clientid" class="control-label"><?php echo _l('expense_add_edit_customer'); ?></label>
                     <select required id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                     <?php $selected = (isset($expense) ? $expense->clientid : '');
                        if($selected == ''){
                          $selected = (isset($customer_id) ? $customer_id: '');
                        }
                        if($selected != ''){
                         $rel_data = get_relation_data('customer',$selected);
                         $rel_val = get_relation_values($rel_data,'customer');
                         echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                        } ?>
                     </select>
                  </div>
                  <?php $hide_project_selector = ' hide'; ?>
                   
                  <div class="form-group projects-wrapper<?php echo $hide_project_selector; ?>">
                     <label for="project_id"><?php echo _l('project'); ?></label>
                     <div id="project_ajax_search_wrapper">
                        <select name="project_id" id="project_id" class="projects ajax-search" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <?php if(isset($expense) && $expense->project_id != 0){
                           echo '<option value="'.$expense->project_id.'" selected>'.get_project_name_by_id($expense->project_id).'</option>';
                           }
                           ?>
                        </select>
                     </div>
                  </div>

                  <div class="form-group">
                     <label for="message" class="control-label">متن پیام</label>
                     <textarea name="message_feedback" class="form-control" id="message" rows="5"><?php echo get_option('message_feedback') ?></textarea>
                  </div>


                  <div class="form-group">
                     <label for="pool_message" class="control-label">متن پیام بعد از نظر سنجی</label>
                     <textarea name="message_feedback_poll" class="form-control" id="pool_message" rows="5"><?php echo get_option('message_feedback_poll') ?></textarea>
                  </div>

                  
                  <div class="btn-bottom-toolbar text-right">
                     <button type="submit" class="btn btn-info"><?php echo _l('request_for_feedback'); ?></button>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="panel_s">
               <div class="panel-body">
                 <h4 class="no-margin">مشتریان ارسال شده</h4>
                 <hr class="hr-panel-heading">
                  <p>لطفا توجه داشته باشید بعد از <b class="text-danger">3</b> روز از ارسال پیام لیست پاسخ های مشتری به صورت خودکار به بایگانی اطلاعات مشتری انتقال پیدا میکند برای مشاهده پیگانی به قسمت مشتریان مراجعه کنید</p>
                  <div class="table-responsive">
                     <table class="table items items-preview invoice-items-preview" data-type="invoice">
                        <thead>
                           <tr>
                              <th class="description" width="20%" align="left">نام مشتری</th>
                              <th align="left" width="10%">امتیاز کلی</th>
                              <th align="left">اطلاعات</th>
                              <th align="left">تاریخ</th>
                              <th align="left" width="15%">عملیات</th>
                           </tr>
                        </thead>
                        <?php $n = 1;
                           foreach ($user as $fd) {
                             $client = $this->clients_model->get_contacts($fd['customerid']);
                           ?>
                        <tbody class="ui-sortable"><tr class="sortable" data-item-id="122">
                           <td class="item_no ui-sortable-handle" align="left" width="5%"><?php echo $client[0]['firstname']; ?> <?php echo $client[0]['lastname']; ?></td>
                           <td class="item_no ui-sortable-handle" align="left" width="5%">
                              <?php
                                
                                $coding=$fd['coding'];
                                $communication=$fd['communication'];
                                $services=$fd['services'];
                                $recommendation=$fd['recommendation'];
                                 
                                $count = $coding + $communication + $services + $recommendation;
                                $avg = $count / 4;
                              ?>
                              <?php $count = $avg; for($c=0;$c < $count;$c++){?><i class="fa fa-star"></i><?php } ?>
                           </td>                           
                           <td class="item_no ui-sortable-handle" align="center" width="5%"><?php 
                              echo ($fd['feedback_submitted'] == 1) ?  '<b class="text-success">پاسخ داده</b>' : '<b class="text-muted">بی پاسخ</b>';
                           ?></td>                           
                           <td class="item_no ui-sortable-handle" align="center" width="5%"><?php 
                              echo to_view_date_custom($fd['date']);
                           ?></td>                           
                           <td class="item_no ui-sortable-handle" align="center" width="5%">
                              <a href="feedback?remove=<?php echo $fd['id']; ?>" class="text-danger" title="بایگانی"><i class="fa fa-archive"></i></a> |
                              <a href="feedback?resent=<?php echo $fd['id']; ?>" title="ارسال مجدد"><i class="fa fa-cogs"></i></a> |
                              <a href="<?php echo admin_url('clients/client/'.$fd["customerid"].'?group=satisfaction'); ?>" title="مشاهده پیام"><i class="fa fa-eye"></i></a>
                           </td>
                        </tbody>
                        <?php $n++; } ?>
                     </table>
                  </div>
               </div>
            </div>
         </div>
        <?php echo form_close(); ?>
      </div>
      <div class="btn-bottom-pusher"></div>
   </div>
</div>

<?php init_tail(); ?>
<script>
   var customer_currency = '';
   Dropzone.options.expenseForm = false;
   var expenseDropzone;
   init_ajax_project_search_by_customer_id();
   var selectCurrency = $('select[name="currency"]');
   <?php if(isset($customer_currency)){ ?>
     var customer_currency = '<?php echo $customer_currency; ?>';
   <?php } ?>


   $('select[name="clientid"]').on('change',function(){
       customer_init();
       
     });

    function customer_init(){
        var customer_id = $('select[name="clientid"]').val();
        var projectAjax = $('select[name="project_id"]');
        var clonedProjectsAjaxSearchSelect = projectAjax.html('').clone();
        var projectsWrapper = $('.projects-wrapper');
        projectAjax.selectpicker('destroy').remove();
        projectAjax = clonedProjectsAjaxSearchSelect;
        $('#project_ajax_search_wrapper').append(clonedProjectsAjaxSearchSelect);
        init_ajax_project_search_by_customer_id();
        if(!customer_id){
           set_base_currency();
           projectsWrapper.addClass('hide');
         }
       $.get(admin_url + 'expenses/get_customer_change_data/'+customer_id,function(response){
         if(customer_id && response.customer_has_projects){
           projectsWrapper.removeClass('hide');
         } else {
           projectsWrapper.addClass('hide');
         }
         var client_currency = parseInt(response.client_currency);
       
       },'json');
     }


</script>
</body>
</html>
