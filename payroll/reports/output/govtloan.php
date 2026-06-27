<?php
ob_start();
//error_reporting(E_ALL);
include("../../../dbcon.php");
include("../../../phpexcel/PHPExcel.php");
include("../../../phpexcel/PHPExcel/Writer/Excel2007.php");
include("../../../scripts/scripts.php");
include ("../../../myfunctions.php");


$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Form Name');
$objPHPExcel->getActiveSheet()->setCellValue('C3', 'SSS Loan Payment');
$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Description');
$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Contains all needed information of the employer');
$objPHPExcel->getActiveSheet()->setCellValue('B7', 'Employer Name');
$objPHPExcel->getActiveSheet()->setCellValue('C7', '');
$objPHPExcel->getActiveSheet()->setCellValue('B8', 'Employer Address');
$objPHPExcel->getActiveSheet()->setCellValue('C8', '');
$objPHPExcel->getActiveSheet()->setCellValue('B10', 'Telephone Number');
$objPHPExcel->getActiveSheet()->setCellValue('C10', '');
$objPHPExcel->getActiveSheet()->setCellValue('B11', 'Zip Code');
$objPHPExcel->getActiveSheet()->setCellValue('C11', '');
$objPHPExcel->getActiveSheet()->setCellValue('B14', 'Employer SSS ID');
$objPHPExcel->getActiveSheet()->setCellValue('C14', '');
$objPHPExcel->getActiveSheet()->setCellValue('B15', 'Employer SSS Locator');
$objPHPExcel->getActiveSheet()->setCellValue('C15', '');

$objPHPExcel->getActiveSheet()->setTitle('Employer SSS Profile');

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
$objPHPExcel->getActiveSheet()->setTitle('Employer Pagibig Profile');

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(2);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
$objPHPExcel->getActiveSheet()->setTitle('Employer Philhealth Profile');

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(3);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
$objPHPExcel->getActiveSheet()->setTitle('EPF - Employment Prevalidation');

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(4);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
$objPHPExcel->getActiveSheet()->setTitle('MCL Monthly Contribution List');

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(5);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
$objPHPExcel->getActiveSheet()->setTitle('SSS Loans');

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(6);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
$objPHPExcel->getActiveSheet()->setTitle('Pagibig - Monthly Contribution');

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(7);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
$objPHPExcel->getActiveSheet()->setTitle('Pagibig - Short Term Loan');

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(8);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
$objPHPExcel->getActiveSheet()->setTitle('Pagibig - Calamity Loan');

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(9);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
$objPHPExcel->getActiveSheet()->setTitle('Pagibig - Housing Loan');

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(10);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
$objPHPExcel->getActiveSheet()->setTitle('Simple Philhealth Contribution');

// Save Excel 2007 file
echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// Echo done
echo date('H:i:s') . " Done writing file.\r\n";
