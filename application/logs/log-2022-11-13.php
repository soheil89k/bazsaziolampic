<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-11-13 01:08:11 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, function 'feedback_permissions' not found or invalid function name /home/faracity/crm.faracity.com/application/vendor/bainternet/php-hooks/php-hooks.php 362
ERROR - 2022-11-13 01:08:11 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, function 'feedback_permissions' not found or invalid function name /home/faracity/crm.faracity.com/application/vendor/bainternet/php-hooks/php-hooks.php 362
ERROR - 2022-11-13 01:08:11 --> Query error: Column 'firstname' in field list is ambiguous - Invalid query: 
    SELECT SQL_CALC_FOUND_ROWS 1, tblclients.userid as userid, company, firstname, email, tblclients.phonenumber as phonenumber, `tblclients`.`active` AS `tblclients.active`, (SELECT GROUP_CONCAT(name SEPARATOR ",") FROM tblcustomer_groups JOIN tblcustomers_groups ON tblcustomer_groups.groupid = tblcustomers_groups.id WHERE customer_id = tblclients.userid ORDER by name ASC) as customerGroups, tblclients.datecreated as datecreated ,tblcontacts.id as contact_id,lastname,tblclients.zip as zip,registration_confirmed
    FROM tblclients
    LEFT JOIN tblcontacts ON tblcontacts.userid=tblclients.userid AND tblcontacts.is_primary=1
    
    WHERE  (tblclients.active = 1 OR tblclients.active=0 AND registration_confirmed = 0)
    
    ORDER BY company ASC
    LIMIT 0, 25
    
ERROR - 2022-11-13 01:08:11 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/faracity/crm.faracity.com/system/core/Exceptions.php:271) /home/faracity/crm.faracity.com/system/core/Common.php 570
