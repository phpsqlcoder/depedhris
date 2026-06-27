   <!-- BEGIN PRE-FOOTER -->
    <div class="pre-footer">
      <div class="container">
        <div class="row">
          <!-- BEGIN BOTTOM ABOUT BLOCK -->
          <div class="col-md-4 col-sm-4 pre-footer-col">
          
            <div class="photo-stream">
              <h2>Human Resource Team</h2>
              <ul class="list-unstyled">
                <?php include_once("../dbcon.php"); 
                $sql=mysql_query("SELECT e.lastName,e.firstName,e.middleName,p.name,e.picture,p.name as posit FROM `employee` e left join position p on p.ndex=e.position where e.deptid in (67,122) and e.isActive=1");
                while($r=mysql_fetch_array($sql)){
                  if($r['picture']){
                    $img=$r['picture'];
                  }
                  else{
                    $img="blank-user.gif";
                  }
                  if (file_exists('../picture/'.$img)) {
                   $src='../picture/'.$img; }
                   else{
                    $src='../picture/blank-user.gif';
                   }
                  echo '<li><a href="#"><img alt="" title="'.$r['firstName'].' '.$r['lastName'].' - '.$r['name'].'" src="'.$src.'"></a></li>';
                }
                ?>
              
              </ul>                    
            </div>
          </div>
          <!-- END BOTTOM ABOUT BLOCK -->

          <!-- BEGIN BOTTOM CONTACTS -->
          <div class="col-md-4 col-sm-4 pre-footer-col">
            <h2>Our Contacts</h2>
            <address class="margin-bottom-40">

             Talent Management: 1202<br>
             HR & Admin Executive Assistant: 1663<br>
             Organizational Development: 1410<br>
              Assistant HR Director: 1414<br>
              Employee Welfare: 1410<br>
              Compensation & Benefits: 1156
            </address>

          </div>

          <div class="col-md-4 col-sm-4 pre-footer-col">
              <h2>About us</h2>
            <p>About us Human Resource Department.. <a href="abouthr.php">read more</a></p>

          </div>

        
        </div>
      </div>
    </div>
    <!-- END PRE-FOOTER -->

    <!-- BEGIN FOOTER -->
    <div class="footer">
      <div class="container">
        <div class="row">
          <!-- BEGIN COPYRIGHT -->
          <div class="col-md-6 col-sm-6 padding-top-10">
            <?php echo date('Y');?> © DDH - HR Dept. ALL Rights Reserved. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
          </div>
          <!-- END COPYRIGHT -->
    
        </div>
      </div>
    </div>
    <!-- END FOOTER -->

    <!-- Load javascripts at bottom, this will reduce page load time -->
    <!-- BEGIN CORE PLUGINS (REQUIRED FOR ALL PAGES) -->
    <!--[if lt IE 9]>
    <script src="assets/global/plugins/respond.min.js"></script>
    <![endif]--> 
    <script src="assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>      

    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
    <script src="assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->
    <script src="assets/global/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script src="assets/frontend/layout/scripts/layout.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();
           // Layout.initTwitter();
        });

        $(function () {
          //$("#compose-textarea").wysihtml5();
        });
    </script>
    <!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>