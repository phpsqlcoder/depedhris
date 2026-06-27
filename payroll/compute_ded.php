<?php
include "dblink.php";
ini_set('max_execution_time', 3600);
$pyy=gmdate('Y');
$pmm=gmdate('m');
$pdd=gmdate('d');
$pmm=$pmm-1;
$today = unixtojd(mktime(0, 0, 0, $pmm, $pdd, $pyy));
$mosname=(cal_from_jd($today, CAL_GREGORIAN));
$yearr=$mosname[year];
$monthh=$mosname[month];

if ($monthh<10) $monthh='0'.$monthh;
	$getlstpaynumber=mysql_fetch_row(mysql_query("SELECT * FROM paynumber ORDER BY paydate DESC",$conn));
	$paydaydt=explode("-",$getlstpaynumber[0]);
	$numd=explode("-", $paydaydt[2]);		// paydaydt[2] = 15-A
	if ($paydaydt[2]=='11'){$num_payperiod='A';	} else {$num_payperiod='B';	}
	$pay_yearr=$paydaydt[0];					   											   // year of payroll period
	$pay_month=$paydaydt[1];					 											 // month of payroll period
	$num_dayspmon=cal_days_in_month(CAL_GREGORIAN,$pay_month,$pay_yearr);			  		// number of days in month.
	if ($num_payperiod=='A') { $strtdate=1; $nddate=15; } 
	if ($num_payperiod=='B') { $strtdate=16; $nddate=$num_dayspmon; }
	for ($xx=$strtdate;$xx <= $nddate;$xx++){
		$ddesc=cal_from_jd(unixtojd(mktime(0, 0, 0, $pay_month, $xx,$pay_yearr)),CAL_GREGORIAN);
		$day_name=$ddesc[dayname];
		if ($day_name!='Sunday') $num_of_dwork++;   	// number of days in one payroll period.
	}
	$result=mysql_query("SELECT a.*,b.* FROM tblemployees a join tblfixcom b on a.ndex=b.idnum WHERE a.empexprd='' && a.tayp<>'DMI' && a.empstat<>'CONPAY' && a.basicpay<>0.00 ORDER BY a.lname",$conn);
//	$result=mysql_query("SELECT a.*,b.* FROM tblemployees a join tblfixcom b on a.ndex=b.idnum WHERE a.empexprd='' && a.tayp='DECO' && a.empstat<>'CONPAY' && a.basicpay<>0.00 ORDER BY a.lname",$conn);
	while($empdat=mysql_fetch_array($result)){
		//$basicpay=$empdat['basicpay'];
		//$paytype=$empdat['paytype'];
		$empid=$empdat['EmpID'];
		$lname=$empdat['lname'];
		$taypord=$empdat['tayp'];
		$cstatus=$empdat['cstatus'];
		$dependents=$empdat['dependents'];
		$company=$empdat['tayp'];
		$dedhdmf=$empdat['pgibgded'];
		$cola=$empdat['cola'];
		if ($empdat['allow_option']=='1'){
			$e_allowance=$empdat['allowance'];
		} else if ($empdat['allow_option']=='2'){
			$e_allowance=$empdat['allowance']/2;
			$rspayroll=mysql_query("UPDATE payroll SET allwnce='$e_allowance' WHERE pay_period='".$getlstpaynumber[0]."' &&  empid='$empid'");
		}

		$e_taxable=$empdat['taxable'];
		$income_wt=0;
		$income=0;
		if ($pdd < 18){
			if ($dedhdmf > 0 || $dedhdmf > 100){ $pgibg_ded=$dedhdmf; } else { $pgibg_ded=100; }
		//	if ($paytype=='Monthly'){ $basicpayday=round($basicpay/26.17,2); } else {   		////  Paid in monthly divided by the number working days in a month.  26.17 is the average working days in a month in a year.
		//		$basicpayday=round($basicpay,2); 
		//	}
			$potexc=0;
			$potreg=0;
			$pundertime=0;
			$pdutyrd=0;
			$pdayswork=0;
			$pspholiday=0;
			$plholiday=0;
			$po_income=0;
			$pcola=0;
			$wtax=0;
			$pph=0;
			$psss=0;
			$ded_abs=0;

			$result2=mysql_query("SELECT * FROM payroll WHERE pay_period like '$yearr-$monthh%' &&  empid='$empid'",$conn);
			while($paydat=mysql_fetch_array($result2)){
				$ndeks=$paydat['ndex'];
				$ut=$paydat['undertime'];
				$otreg=$paydat['ot_reg'];
				$otexc=$paydat['ot_exc'];
				$dutyrd=$paydat['duty_rd'];
				$dayswork=$paydat['days_work'];
				$spholiday=$paydat['spholiday'];
				$lholiday=$paydat['lholiday'];
				$o_income=$paydat['oth_income'];
				$d_absent=$paydat['days_absent'];
				$pay_period=$paydat['pay_period'];
				$paytype=$paydat['pay_type'];
				$basicpay=$paydat['basicpay'];

				if ($paytype=='Monthly'){ 			////  Paid in monthly divided by the number working days in a month.  26.17 is the average working days in a month in a year.
					$basicpayday=round($basicpay/26.17,2); 
				} else {   		
					$basicpayday=round($basicpay,2); 
				}
				//echo $basicpayday."<br>";
				$pcola+=round($paydat['cola'],2);		######### remove due to cola is given every payday not monthly

				//---- OVERTIME-------------------
				$potexc=round($potexc+((($basicpayday*1.25)/8) * $otexc),2); 	 // computation for overtime Excess
				$potreg=round($potreg+($basicpayday/8) * $otreg,2);			 // computation for overtime Regular

				//----UNDERTIME-------------------
				$pundertime=round($pundertime+($basicpayday/8)*$ut,2);

				//----ABSENT-------------------
				$ded_abs=round(($basicpayday*$d_absent),2);
				//---- NUMBER OF DAYS WORK-------------------
				if ($paytype=='Monthly'){
					$pdayswork=round($pdayswork+(($basicpay/2)-$ded_abs),2);
				} else {
					$pdayswork=round($pdayswork+($dayswork*$basicpayday),2);
				}

				//----SPECIAL DAYS/HOLIDAYS-------------------
				$pdutyrd=round($pdutyrd+((($basicpayday*1.3)/8)*$dutyrd),2);  		// Duty on restday
				$pspholiday=round($pspholiday+(($basicpayday*.3)/8)*$spholiday,2);	// Duty on Special Holiday
				$plholiday=round($plholiday+(($basicpayday/8)*$lholiday),2);		// Duty on Legal Holiday

				//$ttt=($basicpay/2)-$ded_abs;
				//echo $ttt."<br>";
			}
			//$income=round($pdayswork+$potreg+$potexc+$pdutyrd+$pspholiday+$plholiday+$pcola-$pundertime,2);  // income in one month
			$income=round($pdayswork+$potreg+$potexc+$pdutyrd+$pspholiday+$plholiday-$pundertime,2);  // income in one month removed $pcola
			//echo $income;
			include "sss.php";
			include "philhealth.php";

			if ($company!='DMI' && $e_taxable!='NO'){
				$income_wt=$income - ($pph + $psss + $pgibg_ded);
				include "withold.php";
			} else {
				$wtax='0.00';
			}
			$insded=mysql_query("UPDATE payroll SET d_philhealth='$pph',d_sss='$psss',d_whtax='$wtax',pagibig='$pgibg_ded',allwnce='$e_allowance' WHERE pay_period='$yearr-$monthh-22' &&  empid='$empid'",$conn);
			$wtax=0;
		} 

		$result2=mysql_query("SELECT * FROM payroll WHERE  empid='$empid' ORDER BY ndex ASC",$conn);
		while($paydat=mysql_fetch_array($result2)){
			$dayswork=$paydat['days_work'];
			$paydaters=$paydat['pay_period'];
			$days_absnt=$paydat['days_absent'];

		}
		if ($paytype=='Monthly'){
			if ($dayswork==0) $dayswork = $num_of_dwork - $d_absent;
		}
		$pcola=round(($cola/26) * $dayswork,2);
		$insded=mysql_query("UPDATE payroll SET cola='$pcola' WHERE pay_period='$paydaters' &&  empid='$empid'",$conn);
		$pcola =0;
	}
?>