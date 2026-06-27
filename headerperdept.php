<?php session_start();
include("scripts/scripts.php");
?>

<div id="header" class="png_bg">
    <div id="head_wrap" class="container_12">
		<div style="position:absolute;left:210px;"><!--<img src="images/logo.jpg" height="100" width="400">--></div>	
        <div class="grid_4"><br></div>
		<div id="controlpanel" class="grid_8">        
            <ul>            
    			<li>
    			  <p><?php echo $_SESSION['nym'];?></p></li>
                <li></li>
                <li><a href="login.php?act=logout" class="last">Sign Out</a></li>                
            </ul>            
        </div>
		<div>&nbsp;</div>
      	<div id="navigation" class=" grid_12">           
             <ul>
                <li><a href="dashboard_dept.php" ><img src="images/employee.png"width="26" height="26"  />&nbsp;&nbsp;Dashboard</a></li>
                <li style="display:none;"><a href="employee_approver.php" ><img src="images/employee.png"width="26" height="26"  />&nbsp;&nbsp;Employees</a></li>
                <?php if ($_SESSION['isApprovingOfficer']<>1){ ?>                    
                <li><a href="tools_setshiftingperdept.php" ><img src="images/employee.png"width="26" height="26"  />&nbsp;&nbsp;Set Shifting</a></li>
                <?php } ?>       
				<li><a href="#"  onclick="window.open('tools_reportshiftingperdept.php','displayWindow','toolbar=no,scrollbars=yes,width=1500,height=600');" ><img src="images/employee.png" width="26" height="26"  />&nbsp;&nbsp;Schedule Summary</a></li> 
				<?php if ($_SESSION['isApprovingOfficer']==1){ ?>
				<li><a href="tools_approveshiftingperdept.php" ><img src="images/employee.png"width="26" height="26"  />&nbsp;&nbsp;Approve Schedule</a></li>
                <li><a href="tools_approveapplications.php" ><img src="images/employee.png"width="26" height="26"  />&nbsp;&nbsp;Online Applications</a></li>
				<li><a href="reports_dept.php"><img src="images/employee.png"width="26" height="26"  />&nbsp;&nbsp;Reports</a></li>
				<?php } ?>
				<li><a href="tools_changepasswordperdept.php" ><img src="images/pword.png"width="26" height="26"  />&nbsp;&nbsp;Account</a></li>
            </ul>   
</div>
 </div>
</div>
</div>
<div>
