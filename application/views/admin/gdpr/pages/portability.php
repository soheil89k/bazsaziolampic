<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="no-mtop">
  <?php echo _l('gdpr_right_to_data_portability'); ?>
  <small>
    <a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-to-data-portability/" target="_blank"><?php echo _l('learn_more'); ?></a>
  </small>
</h4>
<hr class="hr-panel-heading" />
<h4>مخاطبین</h4>
<hr class="hr-panel-heading" />
<?php render_yes_no_option('gdpr_data_portability_contacts','دسترسی کاربران برای برون بری داده ها (JSON)'); ?>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <?php
      $valAllowed = get_option('gdpr_contact_data_portability_allowed');
      if(empty($valAllowed)) {
        $valAllowed = array();
      } else {
        $valAllowed = unserialize($valAllowed);
      }
      ?>
      <label for="gdpr_contact_data_portability_allowed">هنگام برون بری، داده های زیر را برون بری کن</label>
      <div class="select-placeholder">
       <select name="settings[gdpr_contact_data_portability_allowed][]" data-actions-box="true" multiple title="None" id="gdpr_contact_data_portability_allowed" class="selectpicker" data-width="100%">
        <option value="profile_data"<?php if(in_array('profile_data', $valAllowed)){echo ' selected';} ?>>اطلاعات پروفایل مخاطب</option>
        <option value="consent"<?php if(in_array('consent', $valAllowed)){echo ' selected';} ?>>تاریخچه توافق ها</option>
        <option value="tickets"<?php if(in_array('tickets', $valAllowed)){echo ' selected';} ?>>تیکت ها</option>
          <option data-divider="true"></option>
          <option value="" disabled="true">تنها زمانی اعمال می شود که مخاطب ، مخاطب اصلی باشد</option>

         <optgroup label="مشتری">
          <option value="customer_profile_data"<?php if(in_array('customer_profile_data', $valAllowed)){echo ' selected';} ?>>اطلاعات پروفایل مشتری</option>
          <option value="profile_notes"<?php if(in_array('profile_notes', $valAllowed)){echo ' selected';} ?>>یادداشت های پروفایل مشتری</option>
          <option value="contacts"<?php if(in_array('contacts', $valAllowed)){echo ' selected';} ?>>مخاطبین</option>
        </optgroup>
        <optgroup label="فاکتورها">
          <option value="invoices"<?php if(in_array('invoices', $valAllowed)){echo ' selected';} ?>>اطلاعات فاکتورها</option>
          <option value="invoices_notes"<?php if(in_array('invoices_notes', $valAllowed)){echo ' selected';} ?>>یادداشت های فاکتورها</option>
          <option value="invoices_activity_log"<?php if(in_array('invoices_activity_log', $valAllowed)){echo ' selected';} ?>>تاریخچه فعالیت ها</option>
        </optgroup>
        <optgroup label="پیش فاکتورها">
          <option value="estimates"<?php if(in_array('estimates', $valAllowed)){echo ' selected';} ?>>اطلاعاتپیش فاکتورها</option>
          <option value="estimates_notes"<?php if(in_array('invoices_notes', $valAllowed)){echo ' selected';} ?>>یادداشت های پیش فاکتورها</option>
          <option value="estimates_activity_log"<?php if(in_array('estimates_activity_log', $valAllowed)){echo ' selected';} ?>>تاریخچه فعالیت ها</option>
        </optgroup>
        <optgroup label="پروژه ها">
            <option value="projects"<?php if(in_array('projects', $valAllowed)){echo ' selected';} ?>>پروژه ها</option>
            <option value="related_tasks"<?php if(in_array('related_tasks', $valAllowed)){echo ' selected';} ?>>وظایف ایجاد شده توسط مخاطب و وظایفی که مخاطب در آن ها نظر ارسال کرده است</option>
            <option value="related_discussions"<?php if(in_array('related_discussions', $valAllowed)){echo ' selected';} ?>>گفتگوهایی که مخاطب ایجاد کرده است و گفتگو هایی که مخاطب در آن ها نظر ارسال کرده است</option>
            <option value="projects_activity_log"<?php if(in_array('projects_activity_log', $valAllowed)){echo ' selected';} ?>>تاریخچه فعالیت ها</option>
        </optgroup>

        <option value="credit_notes"<?php if(in_array('credit_notes', $valAllowed)){echo ' selected';} ?>>یادداشت های اعتباری</option>
        <option value="proposals"<?php if(in_array('proposals', $valAllowed)){echo ' selected';} ?>>پیشنهادات</option>
        <option value="subscriptions"<?php if(in_array('subscriptions', $valAllowed)){echo ' selected';} ?>>اشتراک ها</option>
        <option value="expenses"<?php if(in_array('expenses', $valAllowed)){echo ' selected';} ?>>هزینه ها</option>
        <option value="contracts"<?php if(in_array('contracts', $valAllowed)){echo ' selected';} ?>>قراردادها</option>


    </select>
  </div>
</div>

</div>
</div>
<hr class="hr-panel-heading" />
<h4>مشتریان بالقوه</h4>
<hr class="hr-panel-heading" />
<?php render_yes_no_option('gdpr_data_portability_leads','دسترسی مشتریان بالقوه برای برون بری داده ها (JSON)'); ?>
<hr />
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <?php
      $valAllowed = get_option('gdpr_lead_data_portability_allowed');
      if(empty($valAllowed)) {
        $valAllowed = array();
      } else {
        $valAllowed = unserialize($valAllowed);
      }
      ?>
      <label for="gdpr_lead_data_portability_allowed">هنگام برون بری، داده های زیر را برون بری کن</label>
      <div class="select-placeholder">
       <select name="settings[gdpr_lead_data_portability_allowed][]" data-actions-box="true" multiple title="None" id="gdpr_lead_data_portability_allowed" class="selectpicker" data-width="100%">
        <option value=""></option>
        <option value="profile_data"<?php if(in_array('profile_data',$valAllowed)){echo ' selected';} ?>>اطلاعات پروفایل</option>
        <option value="custom_fields"<?php if(in_array('custom_fields',$valAllowed)){echo ' selected';} ?>>فیلدهای سفارشی</option>
        <option value="notes"<?php if(in_array('notes',$valAllowed)){echo ' selected';} ?>>یادداشت ها</option>
        <option value="activity_log"<?php if(in_array('activity_log',$valAllowed)){echo ' selected';} ?>>تاریخچه فعالیت ها</option>
        <option value="proposals"<?php if(in_array('proposals',$valAllowed)){echo ' selected';} ?>>پیشنهادات</option>
        <option value="integration_emails"<?php if(in_array('integration_emails',$valAllowed)){echo ' selected';} ?>>ایمیل ایمیل های یکپارچه شده</option>
        <option value="consent"<?php if(in_array('consent',$valAllowed)){echo ' selected';} ?>>تاریخچه توافق ها</option>
      </select>
    </div>
  </div>

</div>
</div>
