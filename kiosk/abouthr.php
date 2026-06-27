<?php
ob_start();
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../employeefunctions.php");
include ("../myfunctions.php");


include("newheader.php");
?>
<div class="row margin-bottom-30">
<div class="col-md-12">
					<img src="../images/group3.jpg" style="width:100%; height:327px;border:0">
					
				</div>
				<div class="col-md-12">
					<h2>VISION</h2>
					<p>
						The organization’s strategic partner.
					</p>
					<h2>MISSION</h2>
					<p>
						To align HR practices with business strategies.<br>
To help the organization adapt to new conditions.<br>
To design and deliver HR services with efficiency.<br>
To support employees develop competence, generate commitment and make contribution.<br>
To build a people-centered organizational culture.<br>

					</p>
					<h2>About Us</h2>
					<p>
						We are the HR Department Team. We primarily focused on maximizing employee productivity and protecting the company from any issues that may arise from the workforce. Our responsibilities include compensation and benefits, recruitment, firing and keeping up to date with any laws that may affect the company and our employees.

						Essential Functions of HR:
					</p>
					<ul class="list-unstyled margin-top-10 margin-bottom-10">
						<li>
							<i class="fa fa-check icon-default"></i> Effectively managing and utilizing people.
						</li>
						<li>
							<i class="fa fa-check icon-success"></i>  Tying performance appraisal and compensation to competencies.
						</li>
						<li>
							<i class="fa fa-check icon-info"></i>  Developing competencies that enhance individual and organizational performance.
						</li>
						<li>
							<i class="fa fa-check icon-danger"></i>  Increasing the innovation, creativity and flexibility necessary to enhance competitiveness.
						</li>
						<li>
							<i class="fa fa-check icon-warning"></i>  Applying new approaches to work process design, succession planning, career development and interorganizational mobility.
						</li>
						<li>
							<i class="fa fa-check icon-warning"></i>  Managing the implementation and integration of technology through improved staffing, training and communication with employees.
						</li>
					</ul>
					<!-- Blockquotes -->
					<blockquote class="hero">
						<p>
							 Great vision without great people is irrelevant.
						</p>
						<small>Jim Collins, Good to Great</small>
					</blockquote>
				</div>
				
			</div>
			<!--/row-->
			<!-- Meer Our Team -->
			<div class="headline">
				<h1>Meet Our Team</h1>
			</div>
			<div class="row thumbnails">
				 <?php 
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
               //   echo '<li><a href="#"><img alt="" title="'.$r['firstName'].' '.$r['lastName'].' - '.$r['name'].'" src="'.$src.'"></a></li>';
                
                ?>
                	<div class="col-md-3">
						<div class="meet-our-team">
							<h3><small><?php echo $r['firstName'].' '.$r['lastName']; ?> </small></h3>
							<img src="<?php echo $src;?>" alt="" class="img-responsive" style="height:200px;width:200px;" />
							<div class="team-info">
								<p><?php echo $r['name'];?></p>
								
							</div>
						</div>
					</div>
                <?php
                	}
                ?>
				
				
				

			</div>


<?php include("newfooter.php");?>