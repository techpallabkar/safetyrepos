</div>
</div>
<footer class="mh-footer">
	<div class="mh-container mh-container-inner mh-footer-widgets mh-row clearfix">
		
			<div class="mh-col-1-4">
				<h6 class="mh-footer-widget-title"><a href="#" style="color:#fff;">Safety Monitoring System</a></h6>
				<div class="textwidget">
					CESC Safety Monitoring System, is a repository of tacit and explicit knowledge 
                   acquired from different sources. The portal is meant for seamless dissemination and sharing of the assimilated knowledge across the organization overcoming the barrier of separate geographical entities. 
				</div>
				 <p style="margin-top:5px;"><a href="about_sms.php" id="direct-more1" class="read-more direct-more"><span class="fa fa-arrow-right"></span> View More</a ></p>
			</div>	
			<div class="mh-col-1-4 widget_categories">
				<h6 class="mh-footer-widget-title">Useful Links</h6>
				<ul style="text-transform:uppercase;">
					<li><a href="index.php">Home</a></li>
					<li><a href="safetyLibrary.php">Safety Library</a></li>
					<li><a href="gallery.php">Gallery</a></li>
<!--					<li><a href="calendar.php">Calendar</a></li>-->
				</ul>
			</div>
			<div class="mh-col-1-4">
				<div class="mh-footer-widget widget_categories">
					<h6 class="mh-footer-widget-title">Activities</h6>
					<ul>
						<?php
						$clause = 'id NOT IN(5,6,7,8,9,10)';
						$activites=$this->dao->get_activities($clause);
							if( ! empty( $activites ) ) {
								foreach( $activites as $actval ) {
								 
									?>
							<li><a href="<?php echo page_link('activities.php?actype_id='.$actval->id.''); ?>"><?=$actval->activity_name?></a></li>
							<?php
								}
							}
							?>
					</ul>
				</div>
				
				
			</div>
					
			<div class="mh-col-1-4 margin-right">
				<div class="mh-footer-widget widget_text">
					<h6 class="mh-footer-widget-title">Locate Us</h6>
					<div class="textwidget">
                     
						<p><i class="fa fa-map-marker"></i>  2A, Lord Sinha Road,1st Floor<br>
						 Kolkata - 700071, West Bengal, India</p>
						 <p><i class="fa fa-phone"></i> +91-33-22360955 | +91-33-22360955</p>
						<p><i class="fa fa-mobile"></i> +913322360955</p>
					</div>
					
				</div>
			</div>
			
	</div>
</footer>

<div class="mh-copyright-wrap clearfix">
	<div class="mh-container mh-container-inner clearfix">
		<p class="mh-copyright">
			All rights reserved by Safety Department of CESC Limited, <?php echo date('Y'); ?>

		</p >
		<p class="design">Designed & developed by <a href="http://viavitae.co.in" target="_blank">Via Vitae Solutions</a></p>
	</div>
</div>

<div class="dark-background"></div>
	<div class="light-box">
		<div class="close"><span class="fa fa-close"></span></div>
		<div class="light-box-content">
                 
                </div>
        </div>
<script type="text/javascript">
var root_url = "<?php echo url(); ?>";
var page_url = "<?php echo $_SERVER['PHP_SELF']; ?>";

	$(document).ready(function(){
		
		/*$("#direct-more1").click(function(){
			var description = $(this).parents('div').children('div.textwidget').text();
			$(".dark-background, .light-box").show();
			$(".light-box-content").html('<div class="content_type"><h4 class="mh-widget-title"> About Safety Monitoring System</h4></div>\n\
                        <p>'+description+'</p></div>');
		});*/
		/*$("#direct-more2").click(function(){
			var description = $(this).parents('div').children('div.textwidget').text();
			$(".dark-background, .light-box").show();
			$(".light-box-content").html('<div class="content_type"><h4 class="mh-widget-title"> About CESC</h4></div>\n\
                        <p>'+description+'</p></div>');
		});*/
		
		$(".close, .dark-background").click(function(){
		$(".dark-background, .light-box").hide();
		});
	});

</script>
<script type="text/javascript" src="<?php echo url("assets/js/default.js") ?>"></script>

<?php
echo $this->loadJs();
//echo $this->getViewVars('datatableHTML');
//echo $this->getViewData('extrajs');
?>
