
<?php

ob_start();

	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
    include ("myfunctions.php");

    function getStartTimex($in,$out,$shift){
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
	//return substr($start,0,17)."00";
	return $start;
}

function getEndTimex($in,$out,$shift){
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
	//return substr($end,0,17)."00";
	return $end;
}
	//echo checkDayIfAbsent('2012-12-16',732);
	/*
	$a=mysql_query("SELECT * from employee_leave where leaveId='3' order by startDate");
	while($b=mysql_fetch_object($a)){
		$l=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$b->employeeId." and '".$b->startDate."' between startDate and endDate"));
		if($l->ndex){
			echo $b->startDate." - ".$b->employeeId."<br>";
		}
	}*/
	//$out='2018-06-12 19:00:00';
	$in='2018-06-12 04:59:52';
	$out='2018-06-12 15:32:57';
	$shift=80;
	$s=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift.""));
	$start_work=strtotime(getStartTime($in,$out,$shift));
	$end_work=strtotime(getEndTime($in,$out,$shift));
	//echo date('Y-m-d H:i:s',$end_work)."<br><br>";
	$start_work=strtotime('2018-06-12 04:59:52');
	$end_work=strtotime('2018-06-12 15:32:57');
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
		if($start_work < $start_night && $end_night > $end_work){
			$nytdiff = ($start_night - $start_work) / 3600;
			//echo date('h:i A',strtotime($start_night))."<br>";
			//echo date('H:i:s A',strtotime($start_work));
		}
		$hour_starts=date('H',$start_work);
			if($hour_starts < 6 && $hour_starts > 0) {
				$nytdiff = ($adj_night - $start_work) / 3600;
				//$s=date('Y-m-d H:i:s',$adj_night)." to ".date('Y-m-d H:i:s',$start_work);
				$s=7;
			}
        //$nytdiff = 0;
		//$s=$hour_starts;

    }
    echo $nytdiff;
?>
