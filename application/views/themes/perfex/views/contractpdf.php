<?php defined('BASEPATH') or exit('No direct script access allowed');

if ($contract->signed == 1) {
    $contract->content .= '<div style="font-weight:bold;text-align: right;">';
    $contract->content .= '<p>' . _l('contract_signed_by') . ": {$contract->acceptance_firstname} {$contract->acceptance_lastname}</p>";
    $contract->content .= '<p>' . _l('contract_signed_date') . ': ' . format_to_relative_time($contract->acceptance_date, '', false) . '</p>';
    $contract->content .= '<p>' . _l('contract_signed_ip') . ": {$contract->acceptance_ip}</p>";
    $contract->content .= '</div>';
}


$bMargin = $pdf->getBreakMargin();
// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();
// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);
// set bacground image
$img_file = 'https://faracity.com/crm_new/uploads/company/a4.jpg';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
// set the starting point for the page content
$pdf->setPageMark();

// Theese lines should aways at the end of the document left side. Dont indent these lines
$html = <<<EOF
<div style="width:680px !important;">
$contract->content
</div>
EOF;
$pdf->writeHTMLCell(0, 0, '', 40, $html, 0, 1, 0, true, '', true);
