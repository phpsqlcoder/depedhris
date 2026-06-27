<?php 
ob_start();
include("../dbcon.php");
include('../payroll/payrollfunctions.php');
include("newheader.php");

$viewSystemDepartmentList='';
$_GET['emp']=$_SESSION['ndex'];
$sql = "SELECT l.*, e.lastName, e.firstName, e.ndex employeeNdex,ld.name as loan FROM loan_employee  l
                      LEFT JOIN employee e ON e.ndex=l.employeeId 
                      LEFT JOIN loandeductionmaintenance ld on ld.ndex=l.loanId
                        WHERE 1 && l.isDeleted<>'1' && l.employeeId='".$_GET['emp']."' ORDER BY l.dedDateStart DESC";
                   //     echo $sql;
$rs = mysql_query($sql,$conn);
  $cnt = 0;
  $ctr1s=0;
  while ($dt = mysql_fetch_assoc($rs)){
    //echo $dt['loan'];
    $cnt++;
    if ($cnt == 1){ $tr_bcg = "row1"; } else { $tr_bcg = "row2"; $cnt = 0;}
    if ($dt['posted'] != '1'){
      $edit = "<a href='?myact=edit&id=".$dt['ndex']."&emp=".$_GET['emp']."'>Edit</a> ";
      $delete = "<a href='?pageact=delete&id=".$dt['ndex']."&emp=".$_GET['emp']."'>Delete</a> ";
      //$adjustment = "<a href='#' onclick=\"$('adj".$dt['ndex']."').toggle();\">Adjustment</a>";
      $post = "<a href='?myact=postloan&id=".$dt['ndex']."&emp=".$_GET['emp']."'>post</a>";
      $post .= " | ".$edit." | ".$delete." |";
    } else {
      $post= '';
      $edit ='';
    }
    $chargeButton = '';
    $ctr1s++;
    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}  
    $viewSystemDepartmentList .= "<tr class='".$tr_bcg."' style='font-size:12px;background-color:".$bgclr1s.";color:black;'>
        <td><a href='#' onclick=\"window.open('tools_employeeloanslledger.php?id=".$dt['employeeNdex']."&loan=".$dt['ndex']."','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=500')\";>".$dt['lastName'].", ".$dt['firstName']." </a></td>
        <td>".$dt['loan']." </td>
        <td align='right'>".$dt['loanAmount']." </td>
        <td align='right'>".$dt['nOfDeduction']." </td>
        <td align='right'>".date('Y-m-d',strtotime($dt['dedDateStart']))." </td>
        <td align='right'>".number_format(getDeductionData($dt['ndex'],'current balance'),2)."</td>
        <td align='right'>".$post.$viewloans."</td>
       
      </tr>
      <tr>
        <td colspan='10'>
          <form method='post' name='frm".$dt['ndex']."' action=\"tools_employeeloans.php?emp=".$_GET['emp']."&act=adjustment&loan=".$dt['ndex']."\">
          <table width='100%' style='display:none;background-color:#EBF4FA;' id='adj".$dt['ndex']."'>
            <tr><td>
              Date:<select name='det".$dt['ndex']."'>".$optionSelectPayrollCutoffDate."</select>
              Remarks:<input type='Text' name='remark".$dt['ndex']."' value='Adjustment'>&nbsp;Amount:<input type='Text' size='5' style='text-align:right;' readonly='readonly' value='".getDeductionData($dt['ndex'],'current balance')."' name='amt".$dt['ndex']."'><input type='submit' value='GO'><br>
              <i style='color:red;'>Note: The value you will enter will subtract the current remaining balance!</i>
              </td></tr></table>
          </form>
        </td>
      </tr>
      ";
  }

?>
     
    <div class="main">
      <div class="container">
        
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN CONTENT -->
          <div class="col-md-12 col-sm-12">
         
            <table cellpadding="5" cellspacing="0" border="0" width="100%">
                <tr class="columnheader" style="color:blue;"><td>Name</td><td>Deduction</td><td align="right">LoanAmount</td><td align="right">No. of ded</td><td  align="right">Start of Ded<br />(Date)</td><td  align="center">Balance</td></tr>
                <tr><td colspan="10"><hr></td></tr>
                <?php echo $viewSystemDepartmentList;?>
              </table>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
      </div>
    </div>
<?php include("newfooter.php");?>