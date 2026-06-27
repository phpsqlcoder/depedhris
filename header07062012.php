<?php session_start();
?>
<div id="header" class="png_bg">
    <div id="head_wrap" class="container_12">
		<div style="position:absolute;left:210px;"><img src="images/logo.jpg" height="100" width="400"></div>	
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
                <li><a href="employee.php" ><img src="images/employee.png"width="26" height="26"  />&nbsp;&nbsp;Employee</a></li>              
				<li><a href="maintenance.php"><img src="images/supplier.png" width="26" height="26" />Maintenance</a></li> 		
				<li><a href="utilities.php"><img src="images/icondock/link_disconnection.png" width="26" height="26" />Utilities </a></li>
				<li><a href="tools.php"><img src="images/tools.png" width="26" height="26" />&nbsp;&nbsp;&nbsp;Tools&nbsp;&nbsp;&nbsp; </a></li>
                <li><a href="reports.php"><img src="images/reports.png" width="26" height="26" />&nbsp;&nbsp;Reports</a></li>
                <li><a href="users.php" onclick="var a=<?php echo $_SESSION['editAccess'];?>;if(a==0){alert('You are not allowed to enter this page!'); return false;}else{window.location.href='users.php';}"><img src="images/icondock/personal.png" width="26" height="26" />System User</a></li>        
            </ul>
          
</div>
 </div>
</div>
</div>
