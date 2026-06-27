<?php
// ALTER TABLE aaa_71to74
//   ADD dept varchar(250);

//   update aaa_71to74 set  dept = (select d.deptdesc from viewhrempmaster e left join hrdepartment d on d.deptid=e.deptid where e.empid=aaa_71to74.PMC_ID)

//    update aaa_71to74 set TIN_02 = '333-198-526' where TIN_02 = '333198526'
//    update aaa_71to74 set TIN_02 = '000-000-000' where TIN_02 = '0'
//    update aaa_71to74 set TIN_02 = '333-177-805' where TIN_02 = '333177805'
//    update aaa_71to74 set TIN_02 = '333-208-026' where TIN_02 = '333208026'
//    update aaa_71to74 set TIN_02 = '333-266-034' where TIN_02 = '333266034'
//    update aaa_71to74 set TIN_02 = '467-496-365' where TIN_02 = '467496365'
//    update aaa_71to74 set TIN_02 = '333-412-256' where TIN_02 = '333412256'
//    update aaa_71to74 set TIN_02 = '333-242-253' where TIN_02 = '333242253'
//    update aaa_71to74 set TIN_02 = '315-056-132' where TIN_02 = '315-056-132-000'
// update aaa_71to74 set TIN_02 = '315-065-678' where TIN_02 = '315-065-678-000'
// update aaa_71to74 set TIN_02 = '000-000-000' where TIN_02 = '000-000-0000'
// update aaa_71to74 set TIN_02 = '000-000-000' where TIN_02 = '000-000-00'
// update aaa_71to74 set TIN_02 = '460-718-405' where TIN_02 = '460-718-405-000'
// update aaa_71to74 set TIN_02 = '447-657-868' where TIN_02 = '447-657-868-000'
// update aaa_71to74 set TIN_02 = '479-713-755' where TIN_02 = '479-713-755-000'

include("config.php");
require_once('fpdf/fpdf.php');
require_once('fpdf/fpdi/fpdi.php');

$dqry=mysqli_query($conn, "select distinct dept from annualization_nmwe");
while($dd=mysqli_fetch_array($dqry)){
			if (!file_exists('individual/'.$dd['dept'])) {
			    mkdir('individual/'.$dd['dept'], 0777, true);
			}
			$q = mysqli_query( $conn, "select *,v.bday from annualization_nmwe g
				left join employees v on v.ndex=g.emp_id
				where g.dept='".$dd['dept']."'		
				");
			//ScheType='7.4'
		
			

			while($r=mysqli_fetch_array($q)){
				$pdf = new FPDI('P','mm',array(220,355));
				$pageCount = $pdf->setSourceFile("23162021.pdf");
				$tplIdx = $pdf->importPage(1, '/MediaBox');

				
				$p=mysqli_fetch_array(mysqli_query($conn,"select bday from employees where ndex='".$r['emp_id']."'"));
					

				$pdf->addPage();
				$pdf->useTemplate($tplIdx);
				$pdf->SetFont('Arial','B',12);
				//year
				$pdf->SetXY(41,37);
				$pdf->Cell(10,6,'2   0    2   1');

				//period
				$pdf->SetXY(137,37);
				$pdf->Cell(10,6,'0   1     0  1');
				$pdf->SetXY(182,37);
				$pdf->Cell(10,6,'  1  2     3  1');

				//tin

				//if(strlen($r['TIN_02'])<=1){$r['TIN_02']="000-000-000";}
				$tin=explode("-",$r['TIN_02']);

				$pdf->SetXY(26,47);
				$pdf->Cell(10,6,$tin[0][0]."  ".$tin[0][1]."  ".$tin[0][2]."     ".$tin[1][0]."  ".$tin[1][1]."  ".$tin[1][2]."      ".$tin[2][0]."  ".$tin[2][1]."  ".$tin[2][2]."      ".$tin[3][0]."  ".$tin[3][1]."  ".$tin[3][2]);
				//echo $r['LastName'].", ".$r['FirstName']." ".$r['MidName']."<br>";
				$pdf->SetXY(91,57);
				$pdf->Cell(10,6,"1  2  1");

				//employee name
				$pdf->SetFont('Arial','B',9);
				$pdf->SetXY(9,57);
				$pdf->Cell(10,6,$r['LastName'].", ".$r['FirstName']." ".$r['MidName']);

				
				//registered address
				$pdf->SetFont('Arial','B',9);
				$pdf->SetXY(9,67);
				$pdf->Cell(10,6,substr($p['RegisteredAdd'],0,42));

				$pdf->SetFont('Arial','B',12);
				$pdf->SetXY(90,67);
				$pdf->Cell(40,6,$p['RegisteredZip'][0]."  ".$p['RegisteredZip'][1]."  ".$p['RegisteredZip'][2]."  ".$p['RegisteredZip'][3]);

				//Local Home address
				$pdf->SetFont('Arial','B',9);
				$pdf->SetXY(9,76);
				$pdf->Cell(40,6,$p['LocalHomeAdd']);

				$pdf->SetFont('Arial','B',12);
				$pdf->SetXY(90,76);
				$pdf->Cell(40,6,$p['LocalZip']);

				//Foreign address
				$pdf->SetXY(9,85);
				$pdf->Cell(19,6,$p['ForeignAdd']);

				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY(90,85);
				$pdf->Cell(40,6,$p['ForeignZip']);

				// NOn Taxables
				$pdf->SetXY(164,54);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,61);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,68);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,76);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,83);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,90);
				$pdf->Cell(45,6,number_format($r['13thOther_11'],2),0,0,'R');

				$pdf->SetXY(164,97);
				$pdf->Cell(45,6,number_format($r['DeMin_12'],2),0,0,'R');

				$pdf->SetXY(164,104);
				$pdf->Cell(45,6,number_format($r['Statutory_13'],2),0,0,'R');

				$pdf->SetXY(164,111);
				$pdf->Cell(45,6,number_format($r['OtherSal_14'],2),0,0,'R');

				$pdf->SetXY(164,118);
				$pdf->Cell(45,6,number_format($r['Total_NT_15'],2),0,0,'R');

				// Taxable Income

				$pdf->SetXY(164,132);
				$pdf->Cell(45,6,number_format($r['BasicPay_16'],2),0,0,'R');

				$pdf->SetXY(164,140);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,147);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,154);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,161);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(133,171);
				$pdf->Cell(35,6,"",0,0,'R');

				$pdf->SetXY(133,178);
				$pdf->Cell(35,6,"",0,0,'R');

				$pdf->SetXY(164,171);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,178);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,187);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,195);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,202);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,209);
				$pdf->Cell(45,6,number_format($r['13thMonth_17'],2),0,0,'R');

				$pdf->SetXY(164,216);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,223);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(164,234);
				$pdf->Cell(45,6,number_format($r['SalariesOthers_18'],2),0,0,'R');
				$pdf->SetXY(164,241);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');
				$pdf->SetXY(164,248);
				$pdf->Cell(45,6,number_format($r['TotalTaxable_19'],2),0,0,'R');
				$pdf->SetXY(110,234);
				$pdf->Cell(35,6,"Other Income",0,0,'R');
				$pdf->SetXY(110,241);
				$pdf->Cell(35,6,"",0,0,'R');

				// Date of Birth
				// $emp=mysqli_fetch_array(mysqli_query($conn,"select CONVERT(NVARCHAR(10),BirthDate,110) as bday from aaa_master where PMC_ID='".$r['PMC_ID']."'"));
				$emp=$r;
				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY(12,95);
				$bday=date('mdY',strtotime($emp['bday']));
				$bday=date('mdY',strtotime($emp['bday'][6].$emp['bday'][7].$emp['bday'][8].$emp['bday'][9]."-".$emp['bday'][0].$emp['bday'][1]."-".$emp['bday'][3].$emp['bday'][4]));
				$pdf->Cell(50,6,$bday[0]."  ".$bday[1]."   ".$bday[2]."   ".$bday[3]."   ".$bday[4]."  ".$bday[5]."   ".$bday[6]."  ".$bday[7]);
				//$pdf->Cell(40,6,$emp['bday']." aa ".$bday);

				$pdf->SetXY(56,95);
				$pdf->Cell(35,6,$p['TelNbr']);

				/*

					//exemption

					$taxid=$r['Code_21'][0];

					if($taxid=='S'){
						$pdf->SetXY(3,97.5);
						$pdf->Cell(35,6,"/",0,0,'R');
					}
					if($taxid=='M'){
						$pdf->SetXY(33,97.5);
						$pdf->Cell(35,6,"/",0,0,'R');
					}
					$wifeexempt="2";
					if($wifeexempt=='1'){
						$pdf->SetXY(3,103.5);
						$pdf->Cell(35,6,"/",0,0,'R');
					}
					if($wifeexempt=='2'){
						$pdf->SetXY(33,103.5);
						$pdf->Cell(35,6,"/",0,0,'R');
					}

					//dependents
					$depnum=0;
					
					$depqry=mysqli_query($conn,"select top 4 CONVERT(NVARCHAR(10),BirthDate,110) as bday,* from HRDependents where EmpID='".$r['PMC_ID']."'");
					$whyowhy=108;

					while($d=mysqli_fetch_array($depqry)){
						$depnum++;		
						if($d['Dependent']){
							$whyowhy+=4;
							$pdf->SetFont('Courier','B',9);
							$pdf->SetXY(18,$whyowhy);
							$pdf->Cell(35,6,$d['Dependent']);

							$bday=date('mdY',strtotime($d['bday'][6].$d['bday'][7].$d['bday'][8].$d['bday'][9]."-".$d['bday'][0].$d['bday'][1]."-".$d['bday'][3].$d['bday'][4]));
							//$bday=date('mdY',strtotime($d['bdayd']));
							$pdf->SetXY(75,$whyowhy);
							$pdf->Cell(35,6,$bday[0]." ".$bday[1]."  ".$bday[2]." ".$bday[3]." ".$bday[4]." ".$bday[5]." ".$bday[6]." ".$bday[7]);

							
						}
					}
				*/

				//statutory minimum wage
				//$pdf->SetFont('Arial','B',10);
				$pdf->SetXY(62,102);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				$pdf->SetXY(62,109);
				$pdf->Cell(45,6,number_format(0,2),0,0,'R');

				// Minimum Wage Earner whose compensation is exempt from withholding tax and not subject to income tax
				$exemptmwe=0;
				if($exemptmwe==1){
					$pdf->SetXY(21.5,141.5);
					$pdf->Cell(35,6,"/");
				}

				$pdf->SetXY(26,125);
				$pdf->Cell(35,6,"$company_tin[0]   $company_tin[1]  $company_tin[2]       $company_tin[3]   $company_tin[4]   $company_tin[5]        $company_tin[6]  $company_tin[7]   $company_tin[8]       $company_tin[9]   $company_tin[10]    $company_tin[11]   $company_tin[12]    $company_tin[13]");

				$pdf->SetXY(9,135);
				$pdf->Cell(35,6,$company);
				/* if Agusan */
				/*
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(9,143);
				$pdf->Cell(35,6,"Mill Site: Bayugan 3, Rosario, Agusan del Sur");
				$pdf->SetXY(9,145.5);
				$pdf->Cell(35,6,"Mine Site: Upper Coo, Consuelo, Agusan del Sur");
				
				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY(90,144);
				$pdf->Cell(35,6,"8   5  0  4");
				*/
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(9,144);
				$pdf->Cell(35,6,$address);
			
				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY(90,144);
				$pdf->Cell(35,6,"8   0  0  0");

				$pdf->SetXY(37,151);
				$pdf->Cell(35,6,"/");

				//21 
				$t21=$r['Total_NT_15'] + $r['TotalTaxable_19'];
				$pdf->SetXY(72,190.5);
				$pdf->Cell(35,6,number_format($t21,2),0,0,'R');

				//22 
				$t22=$r['Total_NT_15'];
				$pdf->SetXY(72,197.5);
				$pdf->Cell(35,6,number_format($t22,2),0,0,'R');

				//23 
				$t23=$r['TotalTaxable_19'];
				$pdf->SetXY(72,205);
				$pdf->Cell(35,6,number_format($t23,2),0,0,'R');

				//24 
				$t24=0;
				$pdf->SetXY(72,212);
				$pdf->Cell(35,6,number_format($t24,2),0,0,'R');

				//25 
				$t25=$t23+$t24;
				$pdf->SetXY(72,219);
				$pdf->Cell(35,6,number_format($t25,2),0,0,'R');

				//26 
				
					$t26=$r['Amount_22'];
					//$pdf->SetXY(72,226);
					//$pdf->Cell(35,6,number_format($t26,2),0,0,'R');

					//27
					$t27=0;
					//$pdf->SetXY(72,233);
					//$pdf->Cell(35,6,number_format($t27,2),0,0,'R');

					//28 
					$t28=$t25 - $t26 - $t27;
					if($t28<1){$t28=0;}
					//$pdf->SetXY(72,240);
					//$pdf->Cell(35,6,number_format($t28,2),0,0,'R');
				
				//29
				$t29=$r['Tax_Due_Full_25'];
				if($t28<1){$t29=0;}
				$pdf->SetXY(72,226);
				$pdf->Cell(35,6,number_format($t29,2),0,0,'R');

				//30
				$t30=$r['PresentEmployer_27'];
				$pdf->SetXY(72,234);
				$pdf->Cell(35,6,number_format($t29,2),0,0,'R');

				//31
				$t31=0;
				$pdf->SetXY(72,241);
				$pdf->Cell(35,6,number_format($t31,2),0,0,'R');

				//32
				$t32=$t29;

				$pdf->SetXY(72,248);
				$pdf->Cell(35,6,number_format($t32,2),0,0,'R');

				
				$pdf->Image($signature,47,257,16);
				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY(40,265.5);
				$pdf->Cell(35,6,$signature_owner);

				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY(20,277);
				$pdf->Cell(35,6,"(SGD) ".$r['LastName'].", ".$r['FirstName']." ".$r['MidName']);

				$pdf->SetXY(147,265.5);
				$pdf->Cell(35,6,"0  1    3  1   2   0   2   2");

				$pdf->SetXY(147,277);
				$pdf->Cell(35,6,"0  1    3  1   2   0   2   2");

				$pdf->SetXY(147,286.5);
				$pdf->Cell(35,6,"0  1    3  1   2   0   2   2");


				$pdf->Image($signature,46,299,16);
				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY(40,307);
				$pdf->Cell(35,6,$signature_owner);

				$pdf->SetFont('Arial','B',8);
				$pdf->SetXY(133,312);
				$pdf->Cell(35,6,"(SGD) ".$r['LastName'].", ".$r['FirstName']." ".$r['MidName']);

				$pdf->Output("F","individual/".$dd['dept']."/".$r['LastName']."_".str_replace("-", "", $r['TIN_02'])."_12312021.pdf");
				
			}

			//$pdf->Output("F","result/nmwe/".str_replace("/","-",$dd['dept']).".pdf");
			//$pdf->Output();
}