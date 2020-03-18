<?php
if( file_exists( '../lib/var.inc.php' ) ) require_once( '../lib/var.inc.php' );
$presCtr = load_controller( "ActivityController" );
$presCtr->doAction();
$beanUi = $presCtr->beanUi;
$recent_post = $beanUi->get_view_data("recent_post");
$mostviewd = $beanUi->get_view_data("mostviewd");
$activities = $beanUi->get_view_data("activities");
$articles = $beanUi->get_view_data("articles");
$page                           =  $beanUi->get_view_data("page");
$draft_posts 			=  $beanUi->get_view_data("draft_posts");
$draft_posts_paggin_html 	=  $beanUi->get_view_data("draft_posts_paggin_html");
$draft_presentation 		=  $beanUi->get_view_data("draft_presentation");
$draft_presentation_paggin_html = $beanUi->get_view_data("draft_presentation_paggin_html");
$activity_type_id 						= $beanUi->get_view_data("activity_type_id");
$activity_type_master = $beanUi->get_view_data("activity_type_master");
$activity_name= get_value_by_id($activity_type_id, "activity_name", $activity_type_master);
$breadcumb 					= $beanUi->get_view_data("breadcumb");

$presCtr->get_header();


?>

<div class="mh-wrapper mh-home clearfix">
   
	<div id="main-content" class="mh-content mh-home-content clearfix">
		<div class="mh-widget mh-home-2 mh-widget-col-2 mh_slider_hp clearfix">
			<div class="topic-heading"><h3>About CESC</h3></div>
			<p class="cms-content">
				The Calcutta Electric Supply Corporation or CESC is the Kolkata-based flagship company of the RP-Sanjiv Goenka Group, born from the erstwhile RPG Group, under the chairmanship of businessman Sanjiv Goenka.
	
			</p>
		</div>
	</div>
   
<div class="mh-widget-col-1 mh-sidebar">
	
	<div id="search-3" class="mh-widget mh-home-6 widget_search">
		<h4 class="mh-widget-title">Search</h4>
		<form role="search" method="post" class="search-form" action="#">
			<label> 
				<span class="screen-reader-text">Search for:</span>
				<input type="search" class="search-field" placeholder="Search &hellip;" value="" name="s" title="Search for:" />
			</label>
			<button type="submit" class="search-submit" value="" /><span class="fa fa-search"></span></button>
		</form>
	</div>
    
    <?php include("upcomingevent.php"); ?>
    </div>

    
<?php $presCtr->get_footer(); ?>


</body>
</html>
