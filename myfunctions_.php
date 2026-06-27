<?php
function dateDiff($time1, $time2, $intervalValue='day',$precision = 6) {
	// If not numeric then convert texts to unix timestamps
	if (!is_int($time1)) {
		$time1 = strtotime($time1);
	}
	if (!is_int($time2)) {
		$time2 = strtotime($time2);
	}
	// If time1 is bigger than time2
	// Then swap time1 and time2
	if ($time1 > $time2) {
		$ttime = $time1;
		$time1 = $time2;
		$time2 = $ttime;
		$operationalSign = '-';
	}
	// Set up intervals and diffs arrays
	//  $intervals = array('year','month','day','hour','minute','second');
	$intervals = array($intervalValue);
	$diffs = array();
	// Loop thru all intervals
	foreach ($intervals as $interval) {
		// Set default diff to 0
		$diffs[$interval] = 0;
		// Create temp time from time1 and interval
		$ttime = strtotime("+1 " . $interval, $time1);
		// Loop until temp time is smaller than time2
		while ($time2 >= $ttime) {
			$time1 = $ttime;
			$diffs[$interval]++;
			// Create new temp time from time1 and interval
			$ttime = strtotime("+1 " . $interval, $time1);
		}
	}
	$count = 0;
	$times = array();
	// Loop thru all diffs
	foreach ($diffs as $interval => $value) {
		// Break if we have needed precission
		if ($count >= $precision) {
			break;
		}
		// Add value and interval 
		// if value is bigger than 0
		if ($value > 0) {
			// Add s if value is not 1
			if ($value != 1) {
				$interval .= "s";
			}
			// Add value and interval to times array
			//	$times[] = $value . " " . $interval;
			$times[] = $operationalSign.$value;
			$count++;
		}
	}
	// Return string with times
	return implode(", ", $times);
}

function checkIfDuplicateLog($dtrNdex){
	$logs=mysql_fetch_object(mysql_query("select * from hrinterface where hrint_id='".$dtrNdex."'"));
	$previousLog=mysql_fetch_object(mysql_query("select * from hrinterface where log<'".$logs->log."' and dtrid='".$logs->dtrid."' ORDER BY log DESC limit 0,1"));
	$nextlog=mysql_fetch_object(mysql_query("select * from hrinterface where log>'".$logs->log."' and dtrid='".$logs->dtrid."' ORDER BY log limit 0,1"));
	//return $previousLog->hrint_id." - ".$nextlog->hrint_id;
	if($previousLog->in_out=='0' || $nextlog->in_out=='0'){
		return 1;
	}
	else{
		return 0;
	}
	//return $previousLog->in_out." - ".$nextlog->in_out;
}

function tymformat($tym){
	if($tym!='-'){
		$hour=substr($tym,0,2);
		$min=substr($tym,2);
		if($hour>=13){$hour=$hour-12;}
		if(strlen($hour)==1){$hour='0'.$hour;}
		return $hour.$min;
	}
	else{
		return $tym;
	}
}

function getStartTime($in,$out,$shift){
	$s=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift.""));
	$date=date('Y-m-d',strtotime($in));
	$shiftdate=$date." ".$s->timeIn;
	if($in<=$shiftdate){	// IN before shifting schedule
		$difference=timeDiffinSeconds($in,$shiftdate)/3600;	//scenario: 11pm - shift sked and 12am timeIn.
		if($difference>=12){
			$start=$in;
		}
		else{
			$start=$shiftdate;
		}
	}
	else{		//if late
		$diff=timeDiffinSeconds($shiftdate,$in)/3600;	//scenario: 12am - shift sked and 11pm timeIn.
		if($diff>=12){
			$start=date('Y-m-d H:i:s',strtotime('1 days',strtotime(date($shiftdate))));
		}
		else{
			$start=$in;
		}
	}
	return substr($start,0,17)."00";
}

function getEndTime($in,$out,$shift){
	$s=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift.""));
	$dateout=date('Y-m-d',strtotime($out));
	$shiftdateout=$dateout." ".$s->timeOut;
	if($out>=$shiftdateout){
		$diffout=timeDiffinSeconds($shiftdateout,$out)/3600;	//scenario: 12am - shift sked and 11pm timeOut.
		if($diffout>=12){
			$end=$out;
		}
		else{
			$end=$shiftdateout;
		}
	}
	else{
		$diffout2=timeDiffinSeconds($out,$shiftdateout)/3600;	//scenario: 11pm - shift sked and 1am timeOut
		if($diffout2>=12){
			$end=date('Y-m-d H:i:s',strtotime('-1 days',strtotime(date($shiftdateout))));
		}
		else{
			$end=$out;
		}
	}
	return substr($end,0,17)."00";
}


function dailyTimeTotal($in,$out,$shift){
	$s=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift.""));
############## Getting the Start Time #############
	$start=getStartTime($in,$out,$shift);
############# End Start Time #################
############# Getting the End Time #################
	$end=getEndTime($in,$out,$shift);
############# End of End Time #################
	$total_hour_duty=timeDiffinSeconds($start,$end);
	$total_hour_duty=$total_hour_duty/3600;
######### Subtract Break Time ######
if($s->breakMinutes!=0){
	$break=$s->breakMinutes/60;
	if($total_hour_duty>5){
		$total_hour_duty=$total_hour_duty-$break;
	}
}
else{
	if($s->breakOut!='00:00:00'){
		$total_hour_duty=$total_hour_duty-1;
	}
	else{
		$total_hour_duty=$total_hour_duty;
	}
	/*if($total_hour_duty>=9){
		$total_hour_duty=$total_hour_duty-1;
	}*/
}
	return $total_hour_duty;
}

function getBreakUndertime($in,$out,$shift,$empid,$dtrid){
	$s=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift.""));
	############## Getting the Start Time #############
	$start=getStartTime($in,$out,$shift);
	############# End Start Time #################
	############# Getting the End Time #################
	$end=getEndTime($in,$out,$shift);
	############# End of End Time #################
	if($s->breakOut!='00:00:00'){
		$breakqry=mysql_query("select * from hrinterface where dtrid='".$dtrid."' and log > '".$start."' and log < '".$end."' ORDER BY log");
		$counter=0;
		while($br=mysql_fetch_object($breakqry)){
			$counter++;
			$breaklog[$counter]=$br->log;
		}
		if($counter==2){
			$datebreak=date('Y-m-d',strtotime($breaklog[1]));
			$dateout=date('Y-m-d H:i:s',strtotime($datebreak." ".$s->breakOut));
			$datein=date('Y-m-d H:i:s',strtotime($datebreak." ".$s->breakIn));
			$undertymbreak=0;
			if($dateout > $breaklog[1]){
				$undertymbreak+=timeDiffinSeconds($breaklog[1],$dateout);
			}
			if($breaklog[2] > $datein){
				$undertymbreak+=timeDiffinSeconds($datein,$breaklog[2]);
			}
			return $undertymbreak/60;
			//return $dateout."  aa  ".$breaklog[1];
		}
		//else{}
	}
	//else{}
}

function nightDifferential($in,$out,$shift){
	$s=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift.""));
	$start_work=strtotime(getStartTime($in,$out,$shift));
	$end_work=strtotime(getEndTime($in,$out,$shift));
	//$start_work=strtotime('2012-06-01 17:00:00');
	//$end_work=strtotime('2012-06-02 01:00:00');
	$nytdiff=0;
	$s=0;
	$start_night = mktime('18','00','00',date('m',$start_work),date('d',$start_work),date('Y',$start_work));
    $end_night   = mktime('06','00','00',date('m',$start_work),date('d',$start_work) + 1,date('Y',$start_work));
	$adj_night   = mktime('06','00','00',date('m',$start_work),date('d',$start_work),date('Y',$start_work));
    if($start_work >= $start_night && $start_work <= $end_night)
    {
        if($end_work >= $end_night)
        {
            $nytdiff = ($end_night - $start_work) / 3600;
			$s=1;
        }
        else
        {
            $nytdiff = ($end_work - $start_work) / 3600;
			$s=2;
        }
    }
    elseif($end_work >= $start_night && $end_work <= $end_night)
    {
        if($start_work <= $start_night)
        {
            $nytdiff = ($end_work - $start_night) / 3600;
			$s=3;
			$hour_start=date('H',$start_work);
			if($hour_start < 6 && $hour_start > 0) {
				$nytdiff += ($adj_night - $start_work) / 3600;
				//$s=date('Y-m-d H:i:s',$start_work);
				$s=6;
			}
        }
        else
        {
            $nytdiff = ($end_work - $start_work) / 3600;
			$s=4;
        }
    }
    else
    {
        if($start_work < $start_night && $end_work > $end_night)
        {
            $nytdiff = ($end_night - $start_night) / 3600;
			//$s="5 - ".date('Y-m-d H:i:s',$end_work)." - ".date('Y-m-d H:i:s',$end_night);
        }
		//if($start_work < $start_night && $end_night > $end_work)
		$hour_starts=date('H',$start_work);
			if($hour_starts < 6 && $hour_starts > 0) {
				$nytdiff = ($adj_night - $start_work) / 3600;
				//$s=date('Y-m-d H:i:s',$adj_night)." to ".date('Y-m-d H:i:s',$start_work);
				$s=7;
			}
        //$nytdiff = 0;
		//$s=$hour_starts;
    }
	return $nytdiff;
}


function lateTotalinMinutes($in,$shift){
	$s=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift.""));
############## Getting the Start Time #############
	$date=date('Y-m-d',strtotime($in));
	$shiftdate=$date." ".$s->timeIn;
	if($in>$shiftdate){		//if late
		$diff=timeDiffinSeconds($shiftdate,$in)/3600;	//scenario: 12am - shift sked and 11pm timeIn.
		if($diff<12){
			$late=timeDiffinSeconds($shiftdate,$in);
		}
	}
	else{
		$difference=timeDiffinSeconds($in,$shiftdate)/3600;
		if($difference>=12){
			$newshiftdate=date('Y-m-d H:i:s',strtotime('-1 days',strtotime(date($shiftdate))));
			$late=timeDiffinSeconds($newshiftdate,$in);
		}
	}
	$late = $late / 60;
	$late = number_format($late,2);
	return substr($late,0,-3);
}



function undertimeTotalinMinutes($out,$shift){
	$s=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift.""));
	$dateout=date('Y-m-d',strtotime($out));
	$shiftdateout=$dateout." ".$s->timeOut;
	if($out<$shiftdateout){
		$diffout2=timeDiffinSeconds($out,$shiftdateout)/3600;	//scenario: 12am - shift sked and 11pm timeOut.
		if($diffout2<12){
			$undertime=timeDiffinSeconds($out,$shiftdateout);
		}
	}
	else{
		$diffout2=timeDiffinSeconds($shiftdateout,$out)/3600;	
		if($diffout2>=12){
			$newout=date('Y-m-d H:i:s',strtotime('1 days',strtotime(date($shiftdateout))));
			$undertime=timeDiffinSeconds($out,$newout);
		}
	}
	return $undertime/60;
}

function overtimeTotalinMinutes($out,$shift){
	if($shift==0 || $shift==''){return 0;}
	else{
		$s=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift.""));
		$dateout=date('Y-m-d',strtotime($out));
		$shiftdateout=$dateout." ".$s->timeOut;
		if($out>$shiftdateout){
			$diffout=timeDiffinSeconds($shiftdateout,$out)/3600;	//scenario: 12am - shift sked and 11pm timeOut.
			if($diffout<12){
				$overtime=timeDiffinSeconds($shiftdateout,$out);
			}
		}
		else{
			$diffout2=timeDiffinSeconds($out,$shiftdateout)/3600;	
			if($diffout2>=12){
				$newout=date('Y-m-d H:i:s',strtotime('-1 days',strtotime(date($shiftdateout))));
				$overtime=timeDiffinSeconds($newout,$out);
			}
		}
		return $overtime/60;
	}
	//return $newout."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$out;
}

function overtimeAMTotalinMinutes($in,$shift){
	$s=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift.""));
	$dateout=date('Y-m-d',strtotime($in));
	$shiftdateout=$dateout." ".$s->timeIn;
	if($in>$shiftdateout){
		$diffout=timeDiffinSeconds($shiftdateout,$out)/3600;	//scenario: 12am - shift sked and 11pm timeOut.
		if($diffout<12){
			$overtime=timeDiffinSeconds($shiftdateout,$out);
		}
	}
	else{
		$diffout2=timeDiffinSeconds($out,$shiftdateout)/3600;	
		if($diffout2>=12){
			$newout=date('Y-m-d H:i:s',strtotime('-1 days',strtotime(date($shiftdateout))));
			$overtime=timeDiffinSeconds($newout,$out);
		}
	}
	return $overtime/60;
	//return $newout."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$out;
}

function timeDiffinSeconds($firstTime,$lastTime){
	$firstTime=strtotime($firstTime);
	$lastTime=strtotime($lastTime);
	$timeDiff=$lastTime-$firstTime;
	return $timeDiff;
}

function checkDayIfAbsent($dyt,$id){
	//return "select * from employee_leave where employeeId=".$employeeId." and leaveId in (5,13) and '".$dyt."' between startDate and endDate";die();
	$daylog=date('w',strtotime($dyt));
	$em=mysql_fetch_object(mysql_query("select * from employee where ndex=".$id.""));
	$restday=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$id." and '".$dyt."' between startDate and endDate"));
	$eleave=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$id." and leaveId not in (5,13) and '".$dyt."' between startDate and endDate"));
	$logs=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$em->biometricNo."' and in_out=0 and datelog='".$dyt."'"));
	$holiday=mysql_fetch_object(mysql_query("select * from holiday where date='".$dyt."'"));
	if($logs->hrint_id){
		return '3'; // with duty
	}
	else{
		$ex=explode(",",$restday->restday);
		if(in_array($daylog,$ex)){
			return '1'; // 1 if restday
		}
		else{
			if($eleave->ndex){
				return '2'; // 2 if with leave
			}
			else{
				if($holiday->ndex){
					$newdate=date('Y-m-d',strtotime('-1 days',strtotime(date($dyt))));
					return checkDayIfAbsent($newdate,$id);
					//return '5';
				}
				else{
					return 0;
					//return $restday->restday." - ".$daylog;
				}
			}
		}
	}
}

function getHoliday($dyt){
	$r=mysql_fetch_object(mysql_query("select * from holiday where date='".$dyt."'"));
	if($r->ndex){
		if($r->isSpecial==1){
			return 'S';
		}
		elseif($r->isSpecial==0){
			return 'L';
		}
		else{
			return '';
		}
	}
	else{
		return '';
	}
}


function reprocess_timelogs($empId,$dyt){
	$employeerecord=mysql_fetch_object(mysql_query("select * from employee where ndex=".$empId.""));
	$e=mysql_query("select dtrid,datelog,count(*) as cnt from hrinterface where datelog>='".$dyt."' and datelog<='".$dyt."' and dtrid='".$employeerecord->biometricNo."' group by dtrid,datelog");
		while($rs=mysql_fetch_object($e)){
			if($rs->cnt>=1){				
				$qr=mysql_query("select * from hrinterface where in_out=0 and datelog='".$rs->datelog."' and dtrid='".$rs->dtrid."' order by log limit 0,1");
				$ee=mysql_fetch_object(mysql_query("select * from employee where ndex='".$empId."'"));
				$ar=0;
				$check_duplicate_record=0;
				$total_break_Undertime=0;
				$late=0; // Late
				$undertime=0; // Undertime
				$overtime=0; // Overtime
				$nytdifferential=0;
				$holidays='';
				$hours_duty=0;
				$timeIn='';
				$timeOut='';
				$shiftId='';
				$breaklogs=array();
				while($s=mysql_fetch_object($qr)){
					//if($ee->ndex){
					$ar++;
					$er[$ar]=$s->hrint_id;
					$inout[$ar]=$s->in_out;
					$chkduble=checkIfDuplicateLog($s->hrint_id);
					if($chkduble==1){ // If nagduplicate and IN or OUT
						$check_duplicate_record=1;
					} 
					else{
						if($ee->ndex){
							$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$ee->ndex." and approvedDate<>'0000-00-00 00:00:00' and '".$s->datelog."' between startDate and endDate"));
						}
						if($shift->shiftingId){
							$y="SELECT * FROM `hrinterface` where in_out=1 and log > '".$s->log."' and dtrid=".$s->dtrid." ORDER BY log limit 0,1";
							$nxtday_timeIn=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$s->dtrid."' and datelog>'".$s->datelog."' and in_out=0 ORDER BY log LIMIT 0,1"));
							if(!$nxtday_timeIn->hrint_id){
								$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and log > '".$s->log."' and dtrid='".$s->dtrid."' ORDER BY log DESC limit 0,1")); // Get OUT record
							}
							else{
								$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and log > '".$s->log."' and log<'".$nxtday_timeIn->log."' and dtrid='".$s->dtrid."' ORDER BY log DESC limit 0,1")); // Get OUT record
							}
							if($out->log){
								$shiftId=$shift->shiftingId;
								$timeIn=$s->log;
								$timeOut=$out->log;
								$check_duplicate_record=0;
							}
							else{
								$check_duplicate_record=2; // No Out
							}
						}
						else{
							$daylogs=date('w',strtotime($s->datelog));
							$chkrestday=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId='".$ee->ndex."' and '".$s->datelog."' between startDate and endDate"));
							if($chkrestday>restday!=$daylogs){
								$check_duplicate_record=3; // No Shifting 
							}
							else{
								$check_duplicate_record=100; // duty restday
							}
						}
					}
					if($check_duplicate_record==0){ // if no error found
						$hours_duty=dailyTimeTotal($timeIn,$timeOut,$shiftId);
						$total_break_Undertime=getBreakUndertime($timeIn,$timeOut,$shift->shiftingId,$ee->ndex,$s->dtrid);
						$late=lateTotalinMinutes($timeIn,$shift->shiftingId); // Late
						$undertime=undertimeTotalinMinutes($timeOut,$shiftId) + $total_break_Undertime; // Undertime
						$overtime=overtimeTotalinMinutes($timeOut,$shiftId); // Overtime
						$nytdifferential=nightDifferential($timeIn,$timeOut,$shiftId);
						$holidays=getHoliday($s->datelog);
					}
					else{
						$hours_duty=0;
						$total_break_time=0;
						$late=0; // Late
						$undertime=0; // Undertime
						$overtime=0; // Overtime
						$nytdifferential=0;
						$holidays="";
					}
					if($holidays!=""){ //adjustment for request that if holiday, no late and undertym
						$late=0; // Late
						$undertime=0; // Undertime
					}
				}
					if($check_duplicate_record==3){ // if No Shifting dapat absent iyang logs.
						$absentvar=",`days_absent`=1";
						$absentvarInsert=1;
					}
					else{
						$absentvar="";
						$absentvarInsert="";
					}
					if($hours_duty>0){$daysWork=1;}else{$daysWork=0;}
					if($ee->ndex){
						$chkifexist=mysql_num_rows(mysql_query("select * from dailytimesummary where employeeId='".$ee->ndex."' and date='".$rs->datelog."'"));
						if($chkifexist>=1){						
							if($check_duplicate_record==100){
								$insert=mysql_query("update `dailytimesummary` set `isError`=".$check_duplicate_record.",`undertime`=".$undertime.",`overtime`='".number_format($overtime,2)."',`minutesLate`=".$late.",`night_prem`=".$nytdifferential.",`holiday`='".$holidays."',`days_work`='".$daysWork."'".$absentvar." where `employeeId`='".$ee->ndex."' and `date`='".$rs->datelog."'");
							}
							else{
								$insert=mysql_query("update `dailytimesummary` set `hoursDuty`=".$hours_duty.",`isError`=".$check_duplicate_record.",`undertime`=".$undertime.",`overtime`='".number_format($overtime,2)."',`minutesLate`=".$late.",`night_prem`=".$nytdifferential.",`holiday`='".$holidays."',`days_work`='".$daysWork."'".$absentvar." where `employeeId`='".$ee->ndex."' and `date`='".$rs->datelog."'");
								//"update `dailytimesummary` set `hoursDuty`=".$hours_duty.",`isError`=".$check_duplicate_record.",`undertime`=".$undertime.",`overtime`='".number_format($overtime,2)."',`minutesLate`=".$late.",`night_prem`=".$nytdifferential.",`holiday`='".$holidays."',`days_work`='".$daysWork."'".$absentvar." where `employeeId`='".$ee->ndex."' and `date`='".$rs->datelog."'";
							}
						}
						else{
							
								$insert=mysql_query("insert into dailytimesummary
									    (`employeeId`, `date`, `days_absent`, `undertime`, `ot_reg`, `ot_exc`, `days_work`, `spholiday`, `lholiday`, `duty_rd`, `vac_lve`, `man_lve`, `sick_lve`, `night_prem`, `isError`, `hoursDuty`,`minutesLate`,`overtime`,holiday)
									 VALUES
									    ('".$ee->ndex."','".$rs->datelog."',".$absentvarInsert.",".$undertime.",0,0,".$daysWork.",0,0,0,0,0,0,".$nytdifferential.",".$check_duplicate_record.",".$hours_duty.",".$late.",".$overtime.",'".$holidays."')");
							
						}
					}
					if($check_duplicate_record==0){
							$changeisProcess=mysql_query("update hrinterface set isProcessed=1 where dtrid='".$rs->dtrid."' and datelog='".$rs->datelog."'");
					}
			}
		}
		$chkifexistrecord=mysql_num_rows(mysql_query("select * from dailytimesummary where employeeId='".$empId."' and date='".$dyt."'"));
		//return "select * from hrinterface where in_out=0 and datelog='".$dyt"' and dtrid='".$employeerecord->biometricNo."'";die();
		$chkifexistlogss=mysql_num_rows(mysql_query("select * from hrinterface where in_out=0 and datelog='".$dyt."' and dtrid='".$employeerecord->biometricNo."'"));
		if($chkifexistrecord>=1 && $chkifexistlogss==0){
			$updaterecord=mysql_query("update `dailytimesummary` set `isError`=0 where `employeeId`='".$empId."' and `date`='".$dyt."'");
		}
		//return $check_duplicate_record."s";
		//die();
		$abs=0;
		$start = strtotime($dyt);
		$end = strtotime($dyt);
		for ( $i = $start; $i <= $end; $i += 86400 ){
			$datelog=date('Y-m-d',$i);
			$employee_qry=mysql_query("select * from employee where ndex=".$empId."");  ///// on testing change ndex
			while($em=mysql_fetch_object($employee_qry)){
				$employeeId=$em->ndex;
				$daylog=date('w',strtotime($datelog));
				$restday=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$employeeId." and '".$datelog."' between startDate and endDate"));
				//birthday
				/*if(date('m-d',strtotime($em->birthDate))==date('m-d',strtotime($datelog))){
					$isbday=1;
				}
				else{
					$isbday=0;
				}*/
				$bday=mysql_num_rows(mysql_query("select * from employee_leave where leaveId=9 and employeeId=".$employeeId." and '".$datelog."' between startDate and endDate"));
				if($bday>0){
					$isbday=1;
				}
				else{
					$isbday=0;
				}
				//
				// Start Holiday
					$absentholiday=getHoliday($datelog);
					$yesterday=date('Y-m-d',strtotime('-1 days',strtotime(date($datelog))));
					$ckiflogexist=mysql_num_rows(mysql_query("SELECT * FROM `hrinterface` where in_out=0 and datelog = '".$datelog."' and dtrid='".$em->biometricNo."'"));
					if($absentholiday=='L'){
						if($ckiflogexist==0){
							$yesterday=date('Y-m-d',strtotime('-1 days',strtotime(date($datelog))));
							$daybeforeholiday=checkDayIfAbsent($yesterday,$employeeId);
							if($daybeforeholiday!=0){  $holidayrecord='L';	}
							else{  $holidayrecord='';	}
						}
						else{
							$holidayrecord='L';
						}
					}
					elseif($absentholiday=='S'){
						if($ckiflogexist==0){
							$daybeforeholiday=checkDayIfAbsent($yesterday,$employeeId);
							if($daybeforeholiday!=0){ $holidayrecord='S';	}
							else{ $holidayrecord=''; }
						}
						else{
							$holidayrecord='S';
						}
					}
					else{
						$holidayrecord='';
					}
					// End Holiday
				$restdayarr=explode(',',$restday->restday);
				if(in_array($daylog,$restdayarr)){
					//echo "ss";
					$r=mysql_fetch_object(mysql_query("select * from dailytimesummary where date='".$datelog."' and employeeId=".$employeeId.""));
					if(!$r->ndex){
						$updaterestday=mysql_query("INSERT INTO  `dailytimesummary` (`employeeId` ,`date` ,`days_absent` ,`undertime` ,`ot_reg` ,`ot_exc` ,`days_work` ,`spholiday` ,`lholiday` ,`duty_rd` ,`vac_lve` ,`man_lve` ,`sick_lve` ,`night_prem` ,`isError` ,`hoursDuty` ,`minutesLate` ,`overtime` ,`holiday`,`leaveId`,`isDayOff`,`isBirthday`)
							VALUES ('".$employeeId."',  '".$datelog."',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '',  '',  '',  '',   '".$holidayrecord."', '0','1','".$isbday."')");
					}
					else{
						$updaterestday=mysql_query("update dailytimesummary set days_absent='0.00',isDayOff=1,holiday='".$holidayrecord."',leaveId=0,isBirthday='".$isbday."' where ndex=".$r->ndex."");
					}
				}	
				else{
					//excluded birthday leave (9) coz its already on the top using variable $isbday.........
					$leave=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId='".$employeeId."' and leaveId<>9 and '".$datelog."' between startDate and endDate"));
					if(!$leave->ndex){
						$leaveId=0;
					}
					else{
						$leaveId=$leave->leaveId;
					}
					$r=mysql_fetch_object(mysql_query("select * from dailytimesummary where date='".$datelog."' and employeeId='".$employeeId."'"));
					//echo "select * from dailytimesummary where date='".$datelog."' and employeeId=".$employeeId."<br><br>";
					if(!$r->ndex){
						$abs++;
						if($leaveId==0){
							$insert=mysql_query("INSERT INTO  `dailytimesummary` (`employeeId` ,`date` ,`days_absent` ,`undertime` ,`ot_reg` ,`ot_exc` ,`days_work` ,`spholiday` ,`lholiday` ,`duty_rd` ,`vac_lve` ,`man_lve` ,`sick_lve` ,`night_prem` ,`isError` ,`hoursDuty` ,`minutesLate` ,`overtime` ,`holiday`,`leaveId`,`isBirthday`)
							VALUES ('".$employeeId."',  '".$datelog."',  '1.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '',  '',  '',  '',   '".$holidayrecord."', '".$leaveId."','".$isbday."')");
						}
						else{
							$insert=mysql_query("INSERT INTO  `dailytimesummary` (`employeeId` ,`date` ,`days_absent` ,`undertime` ,`ot_reg` ,`ot_exc` ,`days_work` ,`spholiday` ,`lholiday` ,`duty_rd` ,`vac_lve` ,`man_lve` ,`sick_lve` ,`night_prem` ,`isError` ,`hoursDuty` ,`minutesLate` ,`overtime` ,`holiday`,`leaveId`,`isBirthday`)
							VALUES ('".$employeeId."',  '".$datelog."',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '',  '',  '',  '',   '".$holidayrecord."', '".$leaveId."','".$isbday."')");
						}
					}
					
					else{
						if($r->hoursDuty=='0.00' && $r->isError==0 && $leaveId==0 && $holidayrecord=='' && $isbday==0){
							$abs++;					
								$update=mysql_query("update dailytimesummary set days_absent='1.00',isError=0,isDayOff=0,isBirthday=0 where ndex=".$r->ndex."");
						}
						else {
							if($leaveId==5 || $leaveId==13){
								$update=mysql_query("update dailytimesummary set days_absent='1.00',isError=0,isDayOff=0,isBirthday=".$isbday." where ndex=".$r->ndex."");
							}
							else{
								if($check_duplicate_record!=3){
									$update=mysql_query("update dailytimesummary set days_absent='0.00',isDayOff=0,isBirthday=".$isbday." where ndex=".$r->ndex."");
								}
							}
						}
						if($leaveId>=1){
							$updateleave=mysql_query("update dailytimesummary set `isError`=0,
										`hoursDuty`=0,`isError`=0,`undertime`=0,`overtime`=0,`minutesLate`=0,`night_prem`=0,`holiday`=0,`days_work`=0,isBirthday=".$isbday."
										 where ndex=".$r->ndex."");
						}
						$update_leave=mysql_query("update dailytimesummary set leaveId='".$leaveId."',holiday='".$holidayrecord."',isDayOff=0,isBirthday=".$isbday." where ndex=".$r->ndex."");
					}
				}
			}
		}
		
}


//####################################################### Payroll #################################################################


function isErrorTimeSummary ($cutoffDateFrom,$cutoffDateEnd){
	$sql = "SELECT * FROM dailytimesummary WHERE isError IN (1,2,3) && `date` BETWEEN '".$cutoffDateFrom."' AND '".$cutoffDateEnd."'";
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) > 0){
		$isErrorValue = 1;  			// with error
	} else {
		$isErrorValue = 0;				// without error
	}
	return $isErrorValue;
}

function noOfDependents ($employeeId){
	$sql = "SELECT * FROM empdependents WHERE employeeId='".$employeeId."' && isDependent='1'";
	$rs = mysql_query($sql);
	return mysql_num_rows($rs);
}

function payPerSpecificTime ($basicPay,$payPer='day'){
	if ($payPer == 'day'){
		$pay = ($basicPay * 12) / 365;
	} elseif ($payPer == 'hour'){
		$pay = (($basicPay * 12) / 365) / 8;
	}  elseif ($payPer == 'minute'){
		$pay = ((($basicPay * 12) / 365) / 8) / 60;
	}
	return $pay;
}

function payPerSpecificTimeDaily ($basicPay,$payPer='day'){
	if ($payPer == 'day'){
		$pay = $basicPay;
	} elseif ($payPer == 'hour'){
		$pay = ($basicPay) / 8;
	}  elseif ($payPer == 'minute'){
		$pay = (($basicPay) / 8) / 60;
	}
	return $pay;
}

function sssPremium ($incomme){
	$sql = "SELECT * FROM `tbl_sss` WHERE str_range <= ".$incomme."  && end_range >=".$incomme."";
	$rs = mysql_fetch_assoc(mysql_query($sql));
	return array (
			eeShare => $rs['e_share'],
			companyShare => $rs['c_share']
		);
}

function philHelthPremium ($basicPay){
	$sql = "SELECT * FROM tbl_philhealth  WHERE income >'".$basicPay."' ORDER BY ndex ASC limit 1";
	$rs = mysql_fetch_assoc(mysql_query($sql));
	return array(
		eeShare => $rs['e_share'],
		companyShare => $rs['c_share']
	);
}

function withHeldTax ($dependent, $income, $dedFrequency='MONTHLY'){
	$sql = "SELECT tax_ded, income FROM tbl_wtax10 LEFT JOIN tbl_wtax11 ON tbl_wtax10.ndex=tbl_wtax11.ndexwt10 
															WHERE  tbl_wtax11.income <= '".$income."' 
																	  && tbl_wtax11.dedFrequency='".$dedFrequency."'
																		&& tbl_wtax10.noOfDependent='".$dependent."' ORDER BY tbl_wtax11.income DESC limit 1";
	$rs = mysql_query($sql);
	$dt = mysql_fetch_assoc($rs);
	$taxWithHeld = $dt['tax_ded'] + ( ($income - $dt['income']) * $dt['ded_on_exc'] );
	
	return $taxWithHeld;
}
?>


