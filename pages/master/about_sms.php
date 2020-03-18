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
			<div class="topic-heading"><h3>About Safety Monitoring System</h3></div>
			<p class="cms-content">
				CESC Safety Monitoring System, is a repository of tacit and explicit knowledge 
                   acquired from different sources. The portal is meant for seamless dissemination and sharing of the assimilated knowledge across the organization overcoming the barrier of separate geographical entities. 
	
			</p>
		</div>
	</div>
   
	<div class="mh-widget-col-1 mh-sidebar">
		<?php include("rightsidebar.php");?>
		<?php include 'upcomingevent.php'; ?>
		<?php include("statistical_data.php"); ?>
	</div>
</div>   
<?php $presCtr->get_footer(); ?>


</body>
</html>
