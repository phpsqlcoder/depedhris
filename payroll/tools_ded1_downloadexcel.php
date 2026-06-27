<?php
//ini_set('error_reporting','E_ERROR | E_WARNING | E_PARSE | E_NOTICE');
ob_start();
$conn=mysql_connect("localhost","root","pangitka");
mysql_select_db("hris",$conn);
function getID($empStatus,$empNo){
	if($empStatus=='Regular'){
		$tayp='';
	}
	elseif($empStatus=='Temporary'){
		$tayp='TMP';
	}
	elseif($empStatus=='Reliever'){
		$tayp='REL';
	}
	elseif($empStatus=='Senior Manager'){
		$tayp='SM';
	}
	elseif($empStatus=='Probationary'){
		$tayp='PRO';
	}
	else{$tayp='';}
	$len=strlen($empNo);
	$len=6-$len;
	for($i=1;$i<=$len;$i++){
		$num.="0";
	}
	$empID=$tayp.$num.$empNo;
	return $empID;
}

require_once '../excel/Classes/PHPExcel.php';
	
$objPHPExcel = new PHPExcel();
$titled=$_POST['cutoffDate'];
$tayp=$_POST['tayp'];
$q=mysql_fetch_array(mysql_query("select * from loandeductionmaintenance where ndex=$tayp"));
$objPHPExcel->getProperties()->setCreator("HRIS System")
							 ->setLastModifiedBy("HRIS System")
							 ->setTitle($titled)
							 ->setSubject($titled)
							 ->setDescription($titled)
							 ->setKeywords($titled)
							 ->setCategory($titled);




	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Payroll Date:')
            ->setCellValue('B1', $titled)->setCellValue('C1', 'Code')->setCellValue('D1', $tayp)->setCellValue('E1', 'Description')->setCellValue('F1', $q['name'])
			;
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'ID')
            ->setCellValue('B2', 'Code')
			->setCellValue('C2', 'Name')
            ->setCellValue('D2', "Department")
			->setCellValue('E2', "Deduction")
			;
	$seq=2;
	$total=0;
	$genqry="select  e.*,d.name as dep from employee e left join dept d on d.ndex=e.deptId where e.isActive=1";
	$qry=mysql_query($genqry); 
	while($r=mysql_fetch_object($qry)){		
			$seq++;
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A$seq",  getID($r->employmentStatus,$r->employeeNo))
			->setCellValue("B$seq", $r->ndex)
			->setCellValue("C$seq", $r->lastName." ".$r->firstName." ".$r->middleName)
			->setCellValue("D$seq", $r->dep)
			->setCellValue("E$seq", '0.00');
			//$objPHPExcel->getActiveSheet()->getStyle('D$seq')->getNumberFormat();
	}
$objPHPExcel->getActiveSheet()->setTitle($titled);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$tayp.$titled.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>

