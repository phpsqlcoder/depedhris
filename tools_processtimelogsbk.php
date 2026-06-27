<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");

/*$e='2012-01-31 15:59:09';
echo substr($e,11);
die();
*/
//$test=mysql_query("update hrinterface set isProcessed='' where isProcessed=1");
$e=mysql_query("select dtrid,datelog,count(*) as cnt from hrinterface where isProcessed<>'1' group by dtrid,datelog");
	while($rs=mysql_fetch_object($e)){
		if($rs->cnt>=1){
			$qr=mysql_query("select * from hrinterface where datelog='".$rs->datelog."' and dtrid='".$rs->dtrid."' order by hrint_id");
			$ar=0;
			$felds="";
			$values="";
			while($s=mysql_fetch_object($qr)){
				$ar++;
				$tym=substr($s->log,11);
				$felds.="time".$ar.",time".$ar."Type,";
				$values.="'".$tym."',".$s->in_out.",";
			}
			$felds=rtrim($felds,",");
			$values=rtrim($values,",");
			$chkifexist=mysql_num_rows(mysql_query("select * from timelogs where employeeId='".$rs->dtrid."' and date='".$rs->datelog."'"));
			if($chkifexist>=1){ $deletelog=mysql_query("delete from timelogs where employeeId='".$rs->dtrid."' and date='".$rs->datelog."'"); }
			$upd=mysql_query("insert into timelogs (`employeeId`, `date`,".$felds.")
					 VALUES('".$rs->dtrid."','".$rs->datelog."',".$values.")");
			$changeisProcess=mysql_query("update hrinterface set isProcessed=1 where dtrid='".$rs->dtrid."' and datelog='".$rs->datelog."'");
		}
		/*elseif($rs->cnt==3){
			$qr=mysql_query("select * from hrinterface where datelog='".$rs->datelog."' and dtrid='".$rs->dtrid."' order by hrint_id");
			$ar=0;
			while($s=mysql_fetch_object($qr)){
				$ar++;
				$tl[$ar]=0;
				$tl[$ar]=substr($s->log,11);
				$inout[$ar]=$s->in_out;
				$last=$ar;
			}
			$upd=mysql_query("insert into timelogs (`employeeId`, `date`, `timeA`, `timeAType`, `timeB`, `timeBType`)
					 VALUES('".$rs->dtrid."','".$rs->datelog."','".$tl[1]."','".$inout[1]."','".$tl[$last]."','".$inoutl[$last]."')");
			$changeisProcess=mysql_query("update hrinterface set isProcessed=1 where dtrid='".$rs->dtrid."' and datelog='".$rs->datelog."'");
		}
		elseif($rs->cnt==2){
			$qr=mysql_query("select * from hrinterface where datelog='".$rs->datelog."' and dtrid='".$rs->dtrid."' order by hrint_id");
			$ar=0;
			while($s=mysql_fetch_object($qr)){
				$ar++;
				$tl[$ar]=0;
				$tl[$ar]=substr($s->log,11);
				$inout[$ar]=$s->in_out;
				$last=$ar;
			}
			$upd=mysql_query("insert into timelogs (`employeeId`, `date`, `timeA`, `timeAType`, `timeB`, `timeBType`)
					 VALUES('".$rs->dtrid."','".$rs->datelog."','".$tl[1]."','".$inout[1]."','".$tl[$last]."','".$inoutl[$last]."')");
			$changeisProcess=mysql_query("update hrinterface set isProcessed=1 where dtrid='".$rs->dtrid."' and datelog='".$rs->datelog."'");
		}
		elseif($rs->cnt==1){
			$s=mysql_fetch_object(mysql_query("select * from hrinterface where datelog='".$rs->datelog."' and dtrid='".$rs->dtrid."'"));
			if($s->in_out==0){
				$feld='timeA,timeAType';
			}
			elseif($s->in_out==1){
				$feld='timeB,timeBType';
			}
			$upd=mysql_query("insert into timelogs (`employeeId`, `date`, ".$feld.")
					 VALUES('".$s->dtrid."','".$s->datelog."','".substr($s->log,11)."','".$s->in_out."')");
			$changeisProcess=mysql_query("update hrinterface set isProcessed=1 where dtrid='".$rs->dtrid."' and datelog='".$rs->datelog."'");
		}
		*/
		
	}

?>
