<?php session_start();
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
            <?php if($_SESSION['isViewingaccess']==0) { ?>           
             <ul>                 
                <li><a href="employee.php" ><img src="images/employee.png"width="26" height="26"  />&nbsp;&nbsp;Employee</a></li> 
	             <?php if($_SESSION['ndex']<>141) { ?>  
                <li><a href="maintenance.php"><img src="images/supplier.png" width="26" height="26" />Maintenance</a></li>      
               
                <li><a href="tools.php"><img src="images/tools.png" width="26" height="26" />&nbsp;&nbsp;&nbsp;Tools&nbsp;&nbsp;&nbsp; </a></li>

 <?php } ?>




                <li><a href="reports.php"><img src="images/reports.png" width="26" height="26" />&nbsp;&nbsp;Reports</a></li>
                                <li><a href="./payroll"><img src="images/reports.png" width="26" height="26" />&nbsp;&nbsp;Payroll</a></li>
                                <li><a href="kiosk_page.php"><img src="images/tools.png" width="26" height="26" />&nbsp;&nbsp;&nbsp;Kiosk&nbsp;&nbsp;&nbsp; </a></li>
                <li><a href="users.php" onclick="var a=<?php echo $_SESSION['nym'];?>;if(a!='joan')
                {window.location.href='users.php';}
                else{alert('You are not allowed to enter this page!'); return false;}">
                <img src="images/icondock/personal.png" width="26" height="26" />System User</a></li> 
             
                <li style="display:none;"><a href="inquiries.php" target="_blank"><img src="images/reports.png" width="26" height="26" />&nbsp;&nbsp;Inquiries</a></li>
           
            </ul>   
        <?php } else {?>
            <ul>                 
                <li><a href="employee.php" ><img src="images/employee.png"width="26" height="26"  />&nbsp;&nbsp;Employee</a></li>              
                <li><a href="reports.php"><img src="images/reports.png" width="26" height="26" />&nbsp;&nbsp;Reports</a></li>  
                <?php if($_SESSION['ndex']=='139'){?>  
                    <li><a href="tools_printcod.php" target="_blank"><img src="images/tools.png" width="26" height="26" />&nbsp;&nbsp;&nbsp;Tools&nbsp;&nbsp;&nbsp; </a></li>
                <?php } ?>
               
                <?php if($_SESSION['ndex']=='18'){?>  
                    <li><a href="payroll/tools_or.php" target="_blank"><img src="images/tools.png" width="26" height="26" />&nbsp;&nbsp;&nbsp;Payroll OR&nbsp;&nbsp;&nbsp; </a></li>
					<li><a href="payroll/certifications.php"><img src="images/icondock/personal.png" width="26" height="26" />&nbsp;&nbsp;Certifications</a></li>
                <?php } ?>
<?php if($_SESSION['ndex']=='336'){?>  
             <li><a href="tools.php"><img src="images/tools.png" width="26" height="26" />&nbsp;&nbsp;&nbsp;Tools&nbsp;&nbsp;&nbsp; </a></li>       
<?php } ?>

<?php if($_SESSION['ndex']=='451' || $_SESSION['ndex']=='452' || $_SESSION['ndex']=='453'){?>  
             <li><a href="maintenance.php"><img src="images/supplier.png" width="26" height="26" />Maintenance</a></li>      
<?php } ?>
<?php if($_SESSION['ndex']==14 || $_SESSION['ndex']==273 || $_SESSION['ndex']==406 || $_SESSION['ndex']==422) { ?>           
                           

	            
                <li><a href="maintenance.php"><img src="images/supplier.png" width="26" height="26" />Maintenance</a></li>      
               
                <li><a href="tools.php"><img src="images/tools.png" width="26" height="26" />&nbsp;&nbsp;&nbsp;Tools&nbsp;&nbsp;&nbsp; </a></li>
		<li><a href="kiosk_page.php"><img src="images/tools.png" width="26" height="26" />&nbsp;&nbsp;&nbsp;Kiosk&nbsp;&nbsp;&nbsp; </a></li>

 <?php } ?>
<?php if($_SESSION['ndex']==422 || $_SESSION['ndex']==437) { ?>   
    <li><a href="payroll/tools_withHospitalLoan.php" ><img src="images/employee.png"width="26" height="26"  />&nbsp;&nbsp;Hospital Deductions</a></li>              
        
<?php } ?> 
<?php if($_SESSION['ndex']==141 || $_SESSION['ndex']==236 || $_SESSION['ndex']==253 || $_SESSION['ndex']==280 || $_SESSION['ndex']==304 || $_SESSION['ndex']==427) { ?>           
                           

	            
                 
               
                <li><a href="tools_kiosk.php"><img src="images/tools.png" width="26" height="26" />&nbsp;&nbsp;&nbsp;Tools&nbsp;&nbsp;&nbsp; </a></li>

 <?php } ?>  
<?php if($_SESSION['ndex']==15) { ?>           
                           
		<li><a href="tools.php"><img src="images/tools.png" width="26" height="26" />&nbsp;&nbsp;&nbsp;Tools&nbsp;&nbsp;&nbsp; </a></li>
	            
                 <li><a href="./payroll"><img src="images/reports.png" width="26" height="26" />&nbsp;&nbsp;Payroll</a></li>      
               
              

 <?php } ?> 
 
<?php if($_SESSION['ndex']==114 || $_SESSION['ndex']==398 || $_SESSION['ndex']==430 || $_SESSION['ndex']==432) { ?> 
	              <li><a href="maintenance.php"><img src="images/supplier.png" width="26" height="26" />Maintenance</a></li>
               
<?php } ?>  
<?php if($_SESSION['ndex']==222 || $_SESSION['ndex']==273 || $_SESSION['ndex']==422 || $_SESSION['ndex']==437) { ?>           
                           

	            
              <li><a href="payroll/certifications.php"><img src="images/icondock/personal.png" width="26" height="26" />&nbsp;&nbsp;Certifications</a></li>

 <?php } ?>      
				<li style="display:none;"><a href="inquiries.php" target="_blank"><img src="images/reports.png" width="26" height="26" />&nbsp;&nbsp;Inquiries</a></li>     
            </ul>
        <?php } ?>



</div>
 </div>
</div>
</div>
