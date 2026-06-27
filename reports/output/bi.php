<?php
ob_start();
session_start();
include("../../dbcon.php");
include ("../../employeefunctions.php");
function noOfDependents ($employeeId){
	$sql = "SELECT * FROM empdependents WHERE employeeId='".$employeeId."' && isDependent='1'";
	$rs = mysql_query($sql);
	return mysql_num_rows($rs);
}
//header
$header="";
if($_POST['1']=='on'){
	$header.="ID|";
}
if($_POST['2']=='on'){
	$header.="Last Name|First Name|Middle Name|";
}
if($_POST['3']=='on'){
	$header.="Status|";
}
if($_POST['4']=='on'){
	$header.="Division|";
}
if($_POST['5']=='on'){
	$header.="Dept|";
}
if($_POST['6']=='on'){
	$header.="Unit|";
}
if($_POST['7']=='on'){
	$header.="Position|";
}
if($_POST['8']=='on'){
	$header.="Date Hired|";
}
if($_POST['9']=='on'){
	$header.="Bank Account|";
}
if($_POST['10']=='on'){
	$header.="Locker|";
}
if($_POST['11']=='on'){
	$header.="Hobbies|";
}
if($_POST['12']=='on'){
	$header.="Pay Type|";
}
if($_POST['13']=='on'){
	if($r->isTaxable==1){$taxable='Yes';}else{$taxable='No';}
	$header.="Taxable|";
}
if($_POST['14']=='on'){
	$header.="Nickname|";
}
if($_POST['15']=='on'){
	$header.="Gender|";
}
if($_POST['16']=='on'){
	$header.="BloodType|";
}
if($_POST['17']=='on'){
	$header.="Civil Status|";
}
if($_POST['18']=='on'){
	$header.="Present Address|";
}
if($_POST['19']=='on'){
	$header.="Email|";
}
if($_POST['20']=='on'){
	$header.="BirthDate|";
}
if($_POST['21']=='on'){
	$header.="Religion|";
}
if($_POST['22']=='on'){
	$header.="Home #|";
}
if($_POST['23']=='on'){
	$header.="Birth Place|";
}
if($_POST['24']=='on'){
	$header.="SSS|";
}
if($_POST['25']=='on'){
	$header.="Mobile #|";
}
if($_POST['26']=='on'){
	$header.="Nationality|";
}
if($_POST['27']=='on'){
	$header.="PHIC #|";
}
if($_POST['28']=='on'){
	$header.="Pag-ibig #|";
}
if($_POST['29']=='on'){
	$header.="TIN|";
}
if($_POST['30']=='on'){
	$header.="Skills|";
}
if($_POST['31']=='on'){
	$header.="Elementary|";
}
if($_POST['32']=='on'){
	$header.="Secondary|";
}
if($_POST['33']=='on'){
	$header.="College|";
}
if($_POST['34']=='on'){
	$header.="Degree|";
}
if($_POST['35']=='on'){
	$header.="Biometric#|";
}
if($_POST['36']=='on'){
	$header.="Level|";
}
if($_POST['37']=='on'){
	$header.="Union Member<br><font style='font-size:10px'>(1=Yes,0=No)</font>|";
}
if($_POST['38']=='on'){
	$header.="Coop Member<br><font style='font-size:10px'>(1=Yes,0=No)</font>|";
}
if($_POST['39']=='on'){
	$header.="First Name|";
}
if($_POST['40']=='on'){
	$header.="Middle Name#|";
}
if($_POST['41']=='on'){
	$header.="Last Name|";
}
if($_POST['42']=='on'){
	$header.="Resigned<br><font style='font-size:10px'>(1=Yes,0=No)</font>|";
}
if($_POST['43']=='on'){
	$header.="Tax Status|";
}
if($_POST['44']=='on'){
	$header.="PRC no|";
}
if($_POST['45']=='on'){
	$header.="Picture|";
}
if($_POST['46']=='on'){
	$header.="Sub Category|";
}
if($_POST['47']=='on'){
	$header.="Category|";
}
$header=rtrim($header,"|");
$headers=explode("|",$header);
// end header
//order
if($_POST['ord']=='1'){$ordr="e.employeeNo";}
elseif($_POST['ord']=='2'){$ordr="e.lastName";}
elseif($_POST['ord']=='3'){$ordr="e.employmentStatus";}
elseif($_POST['ord']=='4'){$ordr="di.name";}
elseif($_POST['ord']=='5'){$ordr="d.name";}
elseif($_POST['ord']=='6'){$ordr="u.name";}
elseif($_POST['ord']=='7'){$ordr="p.name";}
elseif($_POST['ord']=='8'){$ordr="e.dateHired";}
elseif($_POST['ord']=='9'){$ordr="e.bankAccountNo";}
elseif($_POST['ord']=='10'){$ordr="e.lockerNo";}
elseif($_POST['ord']=='11'){$ordr="e.hobbiesInterest";}
elseif($_POST['ord']=='12'){$ordr="e.payType";}
elseif($_POST['ord']=='13'){$ordr="e.isTaxable";}
elseif($_POST['ord']=='14'){$ordr="e.nickName";}
elseif($_POST['ord']=='15'){$ordr="e.sex";}
elseif($_POST['ord']=='16'){$ordr="e.bloodType";}
elseif($_POST['ord']=='17'){$ordr="e.civilStatus";}
elseif($_POST['ord']=='18'){$ordr="e.presentAddress";}
elseif($_POST['ord']=='19'){$ordr="e.emailAddress";}
elseif($_POST['ord']=='20'){$ordr="e.birthDate";}
elseif($_POST['ord']=='21'){$ordr="e.religion";}
elseif($_POST['ord']=='22'){$ordr="e.homePhoneNumber";}
elseif($_POST['ord']=='23'){$ordr="e.birthPlace";}
elseif($_POST['ord']=='24'){$ordr="e.sssNumber";}
elseif($_POST['ord']=='25'){$ordr="e.mobilePhoneNumber";}
elseif($_POST['ord']=='26'){$ordr="e.nationality";}
elseif($_POST['ord']=='27'){$ordr="e.phicNumber";}
elseif($_POST['ord']=='28'){$ordr="e.pagibigNumber";}
elseif($_POST['ord']=='29'){$ordr="e.tin";}
elseif($_POST['ord']=='30'){$ordr="e.specialSkills";}
elseif($_POST['ord']=='35'){$ordr="e.biometricNo";}
elseif($_POST['ord']=='36'){$ordr="e.level";}
elseif($_POST['ord']=='37'){$ordr="e.isUnionMember";}
elseif($_POST['ord']=='38'){$ordr="e.isCoopMember";}
elseif($_POST['ord']=='39'){$ordr="e.firstName";}
elseif($_POST['ord']=='40'){$ordr="e.middleName";}
elseif($_POST['ord']=='41'){$ordr="e.LastName";}
elseif($_POST['ord']=='42'){$ordr="e.isActive";}
elseif($_POST['ord']=='44'){$ordr="e.prcNo";}
elseif($_POST['ord']=='45'){$ordr="e.picture";}
elseif($_POST['ord']=='46'){$ordr="cs.name";}
elseif($_POST['ord']=='47'){$ordr="c.name";}
//end order
$qry="SELECT 
	e.*,
p.name as position,d.name as dept,di.name as division,u.name as unit,cs.name as subcat, c.name as cat 
		from employee e 
		left join position p on p.ndex=e.position 
		left join dept d on d.ndex=e.deptId
		left join division di on di.ndex=e.divisionId
		left join unit u on u.ndex=e.unitId 
		left join category_sub cs on cs.ndex=e.subcategoryId
		left join category c on c.ndex=e.categoryId
	WHERE e.ndex<>'' 
		";
$cond="";


if($_POST['tname']){
	$cond.=" and (e.lastName like '%".$_POST['tname']."%' OR e.firstName like '%".$_POST['tname']."%' OR e.middleName like '%".$_POST['tname']."%')";
}
if($_POST['tempstatus']!=''){
	$cond.=" and e.employmentStatus='".$_POST['tempstatus']."'";
}
if($_POST['tdivision']!=''){
	$cond.=" and e.divisionId='".$_POST['tdivision']."'";
}
if($_POST['tdept']!=''){
	$cond.=" and e.deptId='".$_POST['tdept']."'";
}
if($_POST['tunit']!=''){
	$cond.=" and e.unitId='".$_POST['tunit']."'";
}
if($_POST['tposition']!=''){
	$cond.=" and e.position='".$_POST['tposition']."'";
}
if($_POST['thired']){
	$cond.=" and e.dateHired='".$_POST['thired']."'";
}
if($_POST['tbank']){
	$cond.=" and e.bankAccountNo='".$_POST['tbank']."'";
}
if($_POST['tlocker']){
	$cond.=" and e.lockerNo='".$_POST['tlocker']."'";
}
if($_POST['thobbies']){
	$cond.=" and e.hobbiesInterest like '%".$_POST['thobbies']."%'";
}
if($_POST['tpaytype']!=''){
	$cond.=" and e.payType='".$_POST['tpaytype']."'";
}
if($_POST['ttaxable']!=''){
	$cond.=" and e.isTaxable='".$_POST['ttaxable']."'";
}
if($_POST['tnickname']){
	$cond.=" and e.nickName like '%".$_POST['tnickname']."%'";
}
if($_POST['tsex']!=''){
	$cond.=" and e.sex='".$_POST['tsex']."'";
}
if($_POST['tbloodtype']!=''){
	$cond.=" and e.bloodType='".$_POST['tbloodtype']."'";
}
if($_POST['tcivilstatus']!=''){
	$cond.=" and e.civilStatus='".$_POST['tcivilstatus']."'";
}
if($_POST['taddress']){
	$cond.=" and e.presentAddress like '%".$_POST['taddress']."%'";
}
if($_POST['temail']){
	$cond.=" and e.emailAddress like '%".$_POST['temail']."%'";
}
if($_POST['tbirth']){
	$cond.=" and e.birthDate>'".$_POST['tbirth']."'";
}
if($_POST['treligion']!=''){
	$cond.=" and e.religion='".$_POST['treligion']."'";
}
if($_POST['thomephone']){
	$cond.=" and e.homePhoneNumber='".$_POST['thomephone']."'";
}
if($_POST['tbirthplace']){
	$cond.=" and e.birthPlace like '%".$_POST['tbirthplace']."%'";
}
if($_POST['tsss']){
	$cond.=" and e.sssNumber = '".$_POST['tsss']."'";
}
if($_POST['tmobile']){
	$cond.=" and e.mobilePhoneNumber='".$_POST['tmobile']."'";
}
if($_POST['tnationality']!=''){
	$cond.=" and e.nationality like '%".$_POST['tnationality']."%'";
}
if($_POST['tphic']){
	$cond.=" and e.phicNumber = '".$_POST['tphic']."'";
}
if($_POST['tpagibig']){
	$cond.=" and e.pagibigNumber='".$_POST['tpagibig']."'";
}
if($_POST['ttin']){
	$cond.=" and e.tin='".$_POST['ttin']."'";
}
if($_POST['tskills']){
	$cond.=" and e.technicalSkills like '%".$_POST['tskills']."%'";
}
if($_POST['telementary']){
	//$cond.=" and ";
}
if($_POST['tsecondary']){
	//$cond.=" and";
}
if($_POST['tcollege']){
	$cond.=" and e.ndex in (select employeeId from empeducationalbg where school like '%".$_POST['tcollege']."%')";
}
if($_POST['tdegree']){
	//$cond.=" and";
}
if($_POST['tbionumber']){
	$cond.=" and e.biometricNo like '%".$_POST['tbionumber']."%'";
}
if($_POST['tlevel'] || $_POST['tlevel']==0){
	if($_POST['tlevel']!=''){
		$cond.=" and e.level in (".$_POST['tlevel'].")";
	}
}
if($_POST['tunion']){
	if($_POST['tunion']!='all'){
		$cond.=" and e.isUnionMember=".$_POST['tunion']."";
	}
}
if($_POST['tcoop']){
	if($_POST['tcoop']!='all'){
		$cond.=" and e.isCoopMember=".$_POST['tcoop']."";
	}
}

if($_POST['tfirst']){
	$cond.=" and e.lastName='".$_POST['tfirst']."'";
}
if($_POST['tmiddle']){
	$cond.=" and e.middleName='".$_POST['tmiddle']."'";
}
if($_POST['tlast']){
	$cond.=" and e.lastName like '%".$_POST['tlast']."%'";
}
if($_POST['tsubcategory']){
	$cond.=" and cs.ndex = '".$_POST['tsubcategory']."'";
}
if($_POST['tcategory']){
	$cond.=" and c.ndex = '".$_POST['tcategory']."'";
}

	$cond.=" and e.isActive = '".$_POST['tisresigned']."'";

$qry.=" ".$cond." order by ".$ordr."";
//echo $qry;
$exec=mysql_query($qry);
$var=0;
while($r=mysql_fetch_object($exec)){

		$fields="";
		if($_POST['1']=='on'){
			$fields.=getID($r->employmentStatus,$r->employeeNo)."|";
		}
		if($_POST['2']=='on'){
			$fields.=$r->lastName."|".$r->firstName."|".$r->middleName."|";
		}
		if($_POST['3']=='on'){
			$fields.=$r->employmentStatus."|";
		}
		if($_POST['4']=='on'){
			$fields.=$r->division."|";
		}
		if($_POST['5']=='on'){
			$fields.=$r->dept."|";
		}
		if($_POST['6']=='on'){
			$fields.=$r->unit."|";
		}
		if($_POST['7']=='on'){
			$fields.=$r->position."|";
		}
		if($_POST['8']=='on'){
			$fields.=$r->dateHired."|";
		}
		if($_POST['9']=='on'){
			$fields.=$r->bankAccountNo."|";
		}
		if($_POST['10']=='on'){
			$fields.=$r->lockerNo."|";
		}
		if($_POST['11']=='on'){
			$fields.=$r->hobbiesInterest."|";
		}
		if($_POST['12']=='on'){
			$fields.=$r->payType."|";
		}
		if($_POST['13']=='on'){
			if($r->isTaxable==1){$taxable='Yes';}else{$taxable='No';}
			$fields.=$taxable."|";
		}
		if($_POST['14']=='on'){
			$fields.=$r->nickName."|";
		}
		if($_POST['15']=='on'){
			$fields.=$r->sex."|";
		}
		if($_POST['16']=='on'){
			$fields.=$r->bloodType."|";
		}
		if($_POST['17']=='on'){
			$fields.=$r->civilStatus."|";
		}
		if($_POST['18']=='on'){
			$fields.=$r->presentAddress."|";
		}
		if($_POST['19']=='on'){
			$fields.=$r->emailAddress."|";
		}
		if($_POST['20']=='on'){
			$fields.=$r->birthDate."|";
		}
		if($_POST['21']=='on'){
			$fields.=$r->religion."|";
		}
		if($_POST['22']=='on'){
			$fields.=$r->homePhoneNumber."|";
		}
		if($_POST['23']=='on'){
			$fields.=$r->birthPlace."|";
		}
		if($_POST['24']=='on'){
			$fields.=str_replace("-","",$r->sssNumber)."|";
		}
		if($_POST['25']=='on'){
			$fields.=$r->mobilePhoneNumber."|";
		}
		if($_POST['26']=='on'){
			$fields.=$r->nationality."|";
		}
		if($_POST['27']=='on'){
			$fields.=str_replace("-","",$r->phicNumber)."|";
		}
		if($_POST['28']=='on'){
			$fields.=str_replace("-","",$r->pagibigNumber)."|";
		}
		if($_POST['29']=='on'){
			$fields.=$r->tin."|";
		}
		if($_POST['30']=='on'){
			$fields.=$r->specialSkills."|";
		}
		if($_POST['31']=='on'){
			$sc=mysql_fetch_object(mysql_query("select * from empeducationalbg where employeeId=".$r->ndex." and level='Elementary'"));
			//echo "select * from empeducationalbg where employeeId=".$r->ndex." and level='Elementary'";
			$fields.=$sc->school."|";
		}
		if($_POST['32']=='on'){
			$sc=mysql_fetch_object(mysql_query("select * from empeducationalbg where employeeId=".$r->ndex." and level='High School'"));
			$fields.=$sc->school."|";
		}
		if($_POST['33']=='on'){
			$sc=mysql_fetch_object(mysql_query("select * from empeducationalbg where employeeId=".$r->ndex." and level='College'"));
			$fields.=$sc->school."|";
		}
		if($_POST['34']=='on'){
			$feld="";
			$sc=mysql_query("select * from empeducationalbg where employeeId=".$r->ndex." and level in ('College','Post Graduate')");
			while($cs=mysql_fetch_object($sc)){
				$feld.=$cs->degree." and ";
			}
			$feld=rtrim($feld," and ");
			$fields.=$feld."|";
		}
		
		if($_POST['35']=='on'){
			$fields.=$r->biometricNo."|";
		}
		if($_POST['36']=='on'){
			$fields.=$r->level."|";
		}
		if($_POST['37']=='on'){
			$fields.=$r->isUnionMember."|";
		}
		if($_POST['38']=='on'){
			$fields.=$r->isCoopMember."|";
		}
		if($_POST['39']=='on'){
			$fields.=$r->firstName."|";
		}
		if($_POST['40']=='on'){
			$fields.=$r->middleName."|";
		}
		if($_POST['41']=='on'){
			$fields.=$r->lastName."|";
		}
if($_POST['42']=='on'){
			$fields.=$r->isActive."|";
		}
if($_POST['43']=='on'){
			$ndependents = noOfDependents($r->ndex) ? noOfDependents($r->ndex) : '' ;
			$fields.=substr($r->civilStatus,0,1).$ndependents."|";
		}
		if($_POST['44']=='on'){
			$fields.=$r->prcNo."|";
		}
		if($_POST['45']=='on'){
			$fields.="<img height='50' src='../../picture/".$r->picture."'>|";
		}
		if($_POST['46']=='on'){
			$fields.=$r->subcat."|";
		}
		if($_POST['47']=='on'){
			$fields.=$r->cat."|";
		}
		$fields=rtrim($fields,"|");
		$displayselected=explode("|",$fields);
	
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$var."</td>
	  ";
	  foreach($displayselected as $ds){
		$data.="<td>".$ds."</td>";
	  }
     $data.="</tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="masterfile.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
	
     <table width="100%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="30" align="center" style="font-size:14px;font-weight:bold;"><?php echo $_POST['rpttitle'];?><br></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr style="font-weight:bold;">
	  	<td>Seq</td>
	       <?php
		  foreach($headers as $hd){
			echo "<td>".$hd."</td>";
		  }
		   ?>
	  </tr>
	  <tr><td colspan="30"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>
</body>
     </html>



