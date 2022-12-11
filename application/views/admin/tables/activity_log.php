<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'description',
    'date',
    db_prefix().'activity_log.staffid',
    ];

$sWhere = [];
if ($this->ci->input->post('activity_log_date')) {
    
    $is_duedate_english_date = substr_count($this->ci->input->post('activity_log_date'), '-');
    if(!$is_duedate_english_date){
        $duedate_date_string = explode("/", $this->ci->input->post('activity_log_date'));
        $duedate_date = $this->ci->jdf->jalali_to_gregorian($duedate_date_string[0],$duedate_date_string[1],$duedate_date_string[2],'-');
    } else {
            $duedate_date = $this->ci->input->post('activity_log_date');
    }
    array_push($sWhere, 'AND date LIKE "' . $this->ci->db->escape_like_str(to_sql_date($duedate_date)) . '%" ESCAPE \'!\'');
}
$sIndexColumn = 'id';
$sTable       = db_prefix().'activity_log';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $sWhere);
$output       = $result['output'];
$rResult      = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'date') {
            $_data = $this->ci->jdf->jdate('Y/m/j H:i',convert_datetime(($_data)));
            
        }
        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
