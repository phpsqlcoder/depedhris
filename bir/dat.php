<?php
include("config.php");
error_reporting(E_ALL ^ E_NOTICE);  

//Non-MWE
	$x=0;
	$tgrossn=0;
	$ttar2=0;
	$tDeMin_12=0;
	$tStatutory_13=0;
	$tOtherSal_14=0;
	$tTotal_NT_15=0;
	$tBasicPay_16=0;
	$tthMonth_17=0;
	$tSalariesOthers_18=0;
	$tTotalTaxable_19=0;
	$tAmount_22=0;
	$tpremiumpaid=0;
	$tnettaxable=0;
	$tTax_Due_Full_25=0;
	$tPresentEmployer_27=0;
	$tWheldPaidDec_28=0;
	$tOverWithRef_29=0;
	$tAmountWheldAdj_30=0;
	$s="&nbsp;";
	$data="H1604C,".$company_tin_fix.",0000,12/31/".$year.",N,0,121<br>";


		$q = mysqli_query( $conn, "select EmploymentFrom as empfrom,EmploymentTo as emptos,g.* from annualization_nmwe g
			left join employees v on v.ndex=g.emp_id
			");
		while($r=mysqli_fetch_array($q)){
			$x++;
			$data.="D1,1604C,".$company_tin_fix.",0000,12/31/".$year.",";
			/*
			$seq_space=6-strlen($x);
			for($seq=1;$seq<=$seq_space;$seq++){
				$data.=$s;
			}
			*/
			$data.=$x.",";
			if($r['TIN_02']=='NULL'){$r['TIN_02']='000000000';}
			if($r['TIN_02']=='000-000-00'){$r['TIN_02']='000000000';}
			$data.=substr(str_replace("-","",ltrim($r['TIN_02']," ")),0,9).",0000,";			//tin
				//lastname
			$lastname_space=30-strlen($r['LastName']);
			$data.='"'.str_replace(","," ",str_replace("?","N",utf8_decode($r['LastName']))).'"';
			/*
			for($lname=1;$lname<=$lastname_space;$lname++){
				$data.=$s;
			}*/
			$data.=",";

			//firtname
			$firtname_space=30-strlen($r['FirstName']);
			$data.='"'.str_replace(","," ",str_replace("?","N",utf8_decode($r['FirstName']))).'"';
			/*
			for($fname=1;$fname<=$firtname_space;$fname++){
				$data.=$s;
			}
			*/
			$data.=",";

			//middlename
			$middlename_space=30-strlen($r['MidName']);
			$data.='"'.str_replace("?","N",utf8_decode($r['MidName'])).'"';
			/*
			for($mname=1;$mname<=$middlename_space;$mname++){
				$data.=$s;
			}
			*/
			$data.=",";

			$data.=$region.",";

			//previous employment details
			$data.="0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,";

			//cutoff
			//$data.="01/01/".$year.",12/31/".$year.",";
			$data.=date('m/d/Y',strtotime($r['empfrom'])).",".date('m/d/Y',strtotime($r['emptos'])).",";
			// gross
			$gross=$r['Total_NT_15'] + $r['TotalTaxable_19'];

			$grossn=number_format($gross, 2, '.', '');
			if($grossn=='0.00'){
				$grossn="0";
			}	
			$tgrossn+=$grossn;
			$data.=$grossn.",";
			//echo $grossn."xxxxx<br><br>";

			//Non Tax - Basic = 0
			$data.="0.00,";

			//13th non tax - present employer 13thOther_11
			$tar2=number_format($r['13thOther_11'], 2, '.', '');
			if($tar2=='0.00'){
				$tar2='0.00';
			}
			$tarten=14-strlen($tar2);
			/*
			for($tar=1;$tar<=$tarten;$tar++){
				$data.=$s;
			}*/
			$data.=$tar2.",";
			$ttar2+=$tar2;

			//DeMin_12 non tax - present employer DeMin_12
			$DeMin_12=number_format($r['DeMin_12'], 2, '.', '');
			if($DeMin_12=='0.00'){
				$DeMin_12='0.00';
			}
			$demin=14-strlen($DeMin_12);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$DeMin_12.",";
			$tDeMin_12+=$DeMin_12;

			//statutory non tax - present employer statutory
			$Statutory_13=number_format($r['Statutory_13'], 2, '.', '');
			if($Statutory_13=='0.00'){
				$Statutory_13='0.00';
			}
			$demin=14-strlen($Statutory_13);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$Statutory_13.",";
			$tStatutory_13+=$Statutory_13;

			//othersal non tax - present employer othersal
			$OtherSal_14=number_format($r['OtherSal_14'], 2, '.', '');
			if($OtherSal_14=='0.00'){
				$OtherSal_14='0.00';
			}
			$demin=14-strlen($OtherSal_14);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$OtherSal_14.",";
			$tOtherSal_14+=$OtherSal_14;

			//total non tax 
			$Total_NT_15=number_format($r['Total_NT_15'], 2, '.', '');
			if($Total_NT_15=='0.00'){
				$Total_NT_15='0.00';
			}
			$demin=14-strlen($Total_NT_15);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$Total_NT_15.",";
			$tTotal_NT_15+=$Total_NT_15;




			//basic salary tax 
			$BasicPay_16=number_format($r['BasicPay_16'], 2, '.', '');
			if($BasicPay_16=='0.00'){
				$BasicPay_16='0.00';
			}
			$demin=14-strlen($BasicPay_16);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$BasicPay_16.",";
			$tBasicPay_16+=$BasicPay_16;

			//13th tax 
			$thMonth_17=number_format($r['13thMonth_17'], 2, '.', '');
			if($thMonth_17=='0.00'){
				$thMonth_17='0.00';
			}
			$demin=14-strlen($thMonth_17);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$thMonth_17.",";
			$tthMonth_17+=$thMonth_17;

			//otherincome tax 
			$SalariesOthers_18=number_format($r['SalariesOthers_18'], 2, '.', '');
			if($SalariesOthers_18=='0.00'){
				$SalariesOthers_18='0.00';
			}
			$demin=14-strlen($SalariesOthers_18);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$SalariesOthers_18.",";
			$tSalariesOthers_18+=$SalariesOthers_18;

			//total income tax 
			$TotalTaxable_19=number_format($r['TotalTaxable_19'], 2, '.', '');
			if($TotalTaxable_19=='0.00'){
				$TotalTaxable_19='0.00';
			}
			$demin=14-strlen($TotalTaxable_19);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$TotalTaxable_19.",".$TotalTaxable_19.",".$TotalTaxable_19.",";
			$tTotalTaxable_19+=$TotalTaxable_19;


			//taxdue
			$Tax_Due_Full_25=number_format($r['Tax_Due_Full_25'], 2, '.', '');
			if($Tax_Due_Full_25=='0.00'){
				$Tax_Due_Full_25='0.00';
			}
			$demin=14-strlen($Tax_Due_Full_25);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$Tax_Due_Full_25.",";
			$tTax_Due_Full_25+=$Tax_Due_Full_25;

			//tax due previous???
			$data.="0.00,";

			//tax withheld
			$PresentEmployer_27=number_format($r['PresentEmployer_27'], 2, '.', '');
			if($PresentEmployer_27=='0.00'){
				$PresentEmployer_27='0.00';
			}
			$demin=14-strlen($PresentEmployer_27);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$PresentEmployer_27.",";
			$tPresentEmployer_27+=$PresentEmployer_27;

			//amount withheld dec
			$WheldPaidDec_28=number_format($r['WheldPaidDec_28'], 2, '.', '');
			if($WheldPaidDec_28=='0.00'){
				$WheldPaidDec_28='0.00';
			}
			$demin=14-strlen($WheldPaidDec_28);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$WheldPaidDec_28.",";
			$tWheldPaidDec_28+=$WheldPaidDec_28;

			//over withheld
			$OverWithRef_29=number_format($r['OverWithRef_29'], 2, '.', '');
			if($OverWithRef_29=='0.00'){
				$OverWithRef_29='0.00';
			}
			$demin=14-strlen($OverWithRef_29);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$OverWithRef_29.",";
			$tOverWithRef_29+=$OverWithRef_29;


			//actual amount withheld
			$AmountWheldAdj_30=number_format($r['AmountWheldAdj_30'], 2, '.', '');
			if($AmountWheldAdj_30=='0.00'){
				$AmountWheldAdj_30='0.00';
			}
			$demin=14-strlen($AmountWheldAdj_30);
			/*
			for($tar=1;$tar<=$demin;$tar++){
				$data.=$s;
			}
			*/
			$data.=$AmountWheldAdj_30.",";
			$tAmountWheldAdj_30+=$AmountWheldAdj_30;

			//End 1st Part
			$data.="FILIPINO,";

			//Employment Status
			$data.=$r['EmploymentStatus'].",";	

			//Separation Status
			if($r['isSeparated'] <> ''){		
				$data.=$r['isSeparated'].",";	
			}


			/*
				//exemption code
				$data.=$r['Code_21'].",";

				//amount of exemption
				$Amount_22=number_format($r['Amount_22'], 2, '.', '');
				if($Amount_22=='0.00'){
					$Amount_22='0.00';
				}
				$demin=14-strlen($Amount_22);
				for($tar=1;$tar<=$demin;$tar++){
					$data.=$s;
				}
				$data.=$Amount_22.",";
				$tAmount_22+=$Amount_22;

				//premiumpaid
				$premiumpaid="0.00";
				if($premiumpaid=='0.00'){
					$premiumpaid='0.00';
				}
				$demin=14-strlen($premiumpaid);
				for($tar=1;$tar<=$demin;$tar++){
					$data.=$s;
				}
				$data.=$premiumpaid.",";
				$tpremiumpaid+=$premiumpaid;

				//nettaxable
				$nettaxabletotal=$r['TotalTaxable_19'] - $r['Amount_22'];
				if($nettaxabletotal<1){$nettaxabletotal='0.00';}
				$nettaxable=number_format($nettaxabletotal, 2, '.', '');
				if($nettaxable=='0.00'){
					$nettaxable='0.00';
				}
				$demin=14-strlen($nettaxable);
				for($tar=1;$tar<=$demin;$tar++){
					$data.=$s;
				}
				$data.=$nettaxable.",";
				$tnettaxable+=$nettaxable;


				

				// subfile
				$data.='Y';
			*/


			$data.="<br>";
		}
	
	//Summary
	$data.="C1,1604C,$company_tin_fix,0000,12/31/".$year.",";

		//previous employment details
		$data.="0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,";

		//tgrossn
		$data.=number_format($tgrossn, 2, '.', '').",";

		//basic salary tax 
		$BasicPay_16=number_format($r['BasicPay_16'], 2, '.', '');
		if($BasicPay_16=='0.00'){
			$BasicPay_16='0.00';
		}
		$demin=14-strlen($BasicPay_16);
		/*
		for($tar=1;$tar<=$demin;$tar++){
			$data.=$s;
		}
		*/
		$data.=$BasicPay_16.",";
		$tBasicPay_16+=$BasicPay_16;


		$g_t=number_format($ttar2, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";



		$g_t=number_format($tDeMin_12, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";



		$g_t=number_format($tStatutory_13, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";




		$g_t=number_format($tOtherSal_14, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";




		$g_t=number_format($tTotal_NT_15, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";




		$g_t=number_format($tBasicPay_16, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";



		$g_t=number_format($tthMonth_17, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";



		$g_t=number_format($tSalariesOthers_18, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";



		$g_t=number_format($tTotalTaxable_19, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";
		$data.=$g_t.",";
		$data.=$g_t.",";


		$g_t=number_format($tTax_Due_Full_25, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";

		//previous (jan-nov)
		$data.="0.00,";


		/*
			$g_t=number_format($tAmount_22, 2, '.', '');
			if($g_t=='0.00'){
				$g_t='0.00';
			}
			$g_s=14-strlen($g_t);
			for($tar=1;$tar<=$g_s;$tar++){
				$data.=$s;
			}
			$data.=$g_t.",";



			$g_t=number_format($tpremiumpaid, 2, '.', '');
			if($g_t=='0.00'){
				$g_t='0.00';
			}
			$g_s=14-strlen($g_t);
			for($tar=1;$tar<=$g_s;$tar++){
				$data.=$s;
			}
			$data.=$g_t.",";



			$g_t=number_format($tnettaxable, 2, '.', '');
			if($g_t=='0.00'){
				$g_t='0.00';
			}
			$g_s=14-strlen($g_t);
			for($tar=1;$tar<=$g_s;$tar++){
				$data.=$s;
			}
			$data.=$g_t.",";
		*/


		$g_t=number_format($tPresentEmployer_27, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";


		$g_t=number_format($tWheldPaidDec_28, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";


		$g_t=number_format($tOverWithRef_29, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t.",";


		$g_t=number_format($tAmountWheldAdj_30, 2, '.', '');
		if($g_t=='0.00'){
			$g_t='0.00';
		}
		$g_s=14-strlen($g_t);
		/*
		for($tar=1;$tar<=$g_s;$tar++){
			$data.=$s;
		}
		*/
		$data.=$g_t."<br>";

	//echo $data;
// End NON-MWE

//MWE
	$x=0;
	$t15=0;
	$t16=0;
	$t17=0;
	$t18=0;
	$t20=0;
	$t21=0;
	$t22=0;
	$t23=0;
	$t24=0;
	$t25=0;
	$t26=0;
	$t27=0;
	$t28=0;

		$q = mysqli_query( $conn, "select EmploymentFrom as empfrom,EmploymentTo as emptos,g.* from annualization_mwe g
			left join employees v on v.ndex=g.emp_id
			");
		while($r=mysqli_fetch_array($q)){
			$x++;
			$data.="D2,1604C,".$company_tin_fix.",0000,12/31/".$year.",";
			/*
			$seq_space=6-strlen($x);
			for($seq=1;$seq<=$seq_space;$seq++){
				$data.=$s;
			}
			*/
			$data.=$x.",";
			if($r['TIN_No']=='NULL'){$r['TIN_No']='000000000';}
			if($r['TIN_No']=='000-000-00'){$r['TIN_No']='000000000';}
			$data.=substr(str_replace("-","",ltrim($r['TIN_No']," ")),0,9).",0000,";			//tin
				//lastname
			$lastname_space=30-strlen($r['LastName']);
			$data.='"'.str_replace(","," ",str_replace("?","N",utf8_decode($r['LastName']))).'"';
			/*
			for($lname=1;$lname<=$lastname_space;$lname++){
				$data.=$s;
			}*/
			$data.=",";

			//firtname
			$firtname_space=30-strlen($r['FirstName']);
			$data.='"'.str_replace(","," ",str_replace("?","N",utf8_decode($r['FirstName']))).'"';
			/*
			for($fname=1;$fname<=$firtname_space;$fname++){
				$data.=$s;
			}
			*/
			$data.=",";

			//middlename
			$middlename_space=30-strlen($r['MidName']);
			$data.='"'.str_replace("?","N",utf8_decode($r['MidName'])).'"';
			/*
			for($mname=1;$mname<=$middlename_space;$mname++){
				$data.=$s;
			}
			*/
			$data.=",";

			$data.=$region.",";

			//previous employment details
			$data.="0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,";

			//cutoff
			//$data.="01/01/".$year.",12/31/".$year.",";
			$data.=date('m/d/Y',strtotime($r['empfrom'])).",".date('m/d/Y',strtotime($r['emptos'])).",";
			// gross
			$gross=$r['GrossComp_15'];

			$grossn=number_format($gross, 2, '.', '');
			if($grossn=='0.00'){
				$grossn="0";
			}	
			$t15+=$grossn;
			$data.=$grossn.",";
			//echo $grossn."xxxxx<br><br>";

			//Non Tax - Basic per day
			$basicperday=number_format($r['BasicPerDay_16'], 2, '.', '');
			if($basicperday=='0.00'){
				$basicperday='0.00';
			}
			$data.=$basicperday.",";
			$t16+=$basicperday;

			//Non Tax - Basic per mo
			$basicpermo=number_format($r['BasicPerMo_17'], 2, '.', '');
			if($basicpermo=='0.00'){
				$basicpermo='0.00';
			}
			$data.=$basicpermo.",";
			$t17+=$basicpermo;

			//Non Tax - Basic per yr
			$basicperyr=number_format($r['BasicPerYr_18'], 2, '.', '');
			if($basicperyr=='0.00'){
				$basicperyr='0.00';
			}
			$data.=$basicperyr.",";
			$t18+=$basicperyr;

			//Non Tax - Days per year
			$daysperyr=number_format($r['DaysOfYear_19'], 2, '.', '');
			if($daysperyr=='0.00'){
				$daysperyr='0.00';
			}
			$data.=$daysperyr.",";

			//Non Tax - Holiday
			$holidaypay=number_format($r['HolidayPay_20'], 2, '.', '');
			if($holidaypay=='0.00'){
				$holidaypay='0.00';
			}
			$data.=$holidaypay.",";
			$t20+=$holidaypay;

			//Non Tax - OT
			$overtiment=number_format($r['OverTime_21'], 2, '.', '');
			if($overtiment=='0.00'){
				$overtiment='0.00';
			}
			$data.=$overtiment.",";
			$t21+=$overtiment;

			//Non Tax - ND
			$ndnt=number_format($r['NightDiff_22'], 2, '.', '');
			if($ndnt=='0.00'){
				$ndnt='0.00';
			}
			$data.=$ndnt.",";
			$t22+=$ndnt;

			//Non Tax - hazard pay
			$hazardnt=number_format($r['HazardPay_23'], 2, '.', '');
			if($hazardnt=='0.00'){
				$hazardnt='0.00';
			}
			$data.=$hazardnt.",";
			$t23+=$hazardnt;

			//13th non tax - present employer 13thOther_11
			$tar2=number_format($r['13thMoOthers_24'], 2, '.', '');
			if($tar2=='0.00'){
				$tar2='0.00';
			}
			
			$data.=$tar2.",";
			$t24+=$tar2;

			//DeMin_12 non tax - present employer DeMin_12
			$DeMin_12=number_format($r['DeMinimis_25'], 2, '.', '');
			if($DeMin_12=='0.00'){
				$DeMin_12='0.00';
			}
			
			$data.=$DeMin_12.",";
			$t25+=$DeMin_12;

			//Non Tax - statutory
			$statnt=number_format($r['Statutory_26'], 2, '.', '');
			if($statnt=='0.00'){
				$statnt='0.00';
			}
			$data.=$statnt.",";
			$t26+=$statnt;

			//Non Tax - other salsal 27
			$salsalnt=number_format($r['OtherSal_27'], 2, '.', '');
			if($salsalnt=='0.00'){
				$salsalnt='0.00';
			}
			$data.=$salsalnt.",";
			$t27+=$salsalnt;

			//Non Tax - total non taxable 28
			$totalnt=number_format($r['Total_NT_28'], 2, '.', '');
			if($totalnt=='0.00'){
				$totalnt='0.00';
			}
			$data.=$totalnt.",";
			$t28+=$totalnt;

			$data.="0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,";


			// Citizenship
			$data.="FILIPINO,";

			//Employment Status
			
			$empstat='R';
			if($r['EmpStatus']=='C'){
				$empstat='C';
			}
			if($r['EmpStatus']=='PJ'){
				$empstat='C';
			}
			if($r['EmpStatus']=='P'){
				$empstat='P';
			}
			$data.=$empstat.",";	

			//Separation Status
			if($r['isSeparated'] <> ''){		
				$data.=$r['isSeparated'].",";	
			}

			//End 1st Part

			$data.="<br>";
		}

	//Summary
	$data.="C2,1604C,".$company_tin_fix.",0000,12/31/".$year.",";

		//previous employment details
		$data.="0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,";

	

		$tt15=number_format($t15, 2, '.', '');
		$tt16=number_format($t16, 2, '.', '');
		$tt17=number_format($t17, 2, '.', '');
		$tt18=number_format($t18, 2, '.', '');
		$tt20=number_format($t20, 2, '.', '');
		$tt21=number_format($t21, 2, '.', '');
		$tt22=number_format($t22, 2, '.', '');
		$tt23=number_format($t23, 2, '.', '');
		$tt24=number_format($t24, 2, '.', '');
		$tt25=number_format($t25, 2, '.', '');
		$tt26=number_format($t26, 2, '.', '');
		$tt27=number_format($t27, 2, '.', '');
		$tt28=number_format($t28, 2, '.', '');

		$data.=$tt15.",";
		$data.=$tt16.",";
		$data.=$tt17.",";
		$data.=$tt18.",";
		$data.=$tt20.",";
		$data.=$tt21.",";
		$data.=$tt22.",";
		$data.=$tt23.",";
		$data.=$tt24.",";
		$data.=$tt25.",";
		$data.=$tt26.",";
		$data.=$tt27.",";
		$data.=$tt28.",";
	

		$data.="0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00";


	echo $data;




/*
$datah='H1604C,005883632,0000,12/31/2021,N,0,121
D1,1604C,005883632,0000,12/31/2021,1,469195246,0000,"AQUINO","MICHAEL","RECENTES",XI,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,01/01/2021,01/31/2021,244112.17,0.16,15737.11,17730.12,12879.13,252.14,46598.66,197149.16,0.17,364.18,197513.51,197513.51,197513.51,0.25,0.00,0.27,0.28,0.02,0.53,,R,
D1,1604C,005883632,0000,12/31/2021,2,948331709,0000,"BACALANMO JR","SOL","ALLAGA",XI,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,01/01/2021,12/31/2021,519110.68,0.00,33357.64,9780.00,20742.30,14858.20,78738.14,430913.18,0.00,9459.36,440372.54,440372.54,440372.54,40093.14,0.00,35099.66,4993.48,0.00,40093.14,FILIPINO,R,
D1,1604C,005883632,0000,12/31/2021,3,472554510,0000,"BOLIDO","MONIQUE CARRIZZA","BASINANG",XI,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,01/01/2021,12/31/2021,359766.10,0.00,24818.29,6825.00,19198.32,951.25,51792.86,307973.24,0.00,0.00,307973.24,307973.24,307973.24,11594.65,0.76,77.55,11517.10,0.00,11595.41,FILIPINO,R,
C1,1604C,005883632,0000,12/31/2021,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,1122988.95,0.16,73913.04,34335.12,52819.75,16061.59,177129.66,936035.58,0.17,9823.54,945859.29,945859.29,945859.29,51688.04,0.76,35177.48,16510.86,0.02,51689.08
';
$hh=explode(",", $datah);
$bb=explode(",", $data);
//'echo $bb[2]; die();
$x=0;
$ddd='';
foreach($hh as $h){
	
	$ddd.='<tr>
			<td>'.$x.'</td>
			<td>'.$h.'</td>
			<td>'.$bb[$x].'</td>
	</tr>
	';
	$x++;
	//$x."=   ".$b."<br>";
}
echo '<table width="40%" border="1" cellspacing="0" cellpadding="10">
'.$ddd.'
</table>';
*/
//echo $data;

// $bb=explode(",", $data);
// $x=0;
// foreach($bb as $b){
// 	$x++;
// 	echo $x."=   ".$b."<br>";
// }
?>
