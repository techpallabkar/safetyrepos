<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$presCtr = load_controller("ActivityController");
$presCtr->doAction();
$beanUi = $presCtr->beanUi;
$recent_post = $beanUi->get_view_data("recent_post");
$mostviewd = $beanUi->get_view_data("mostviewd");
$activities = $beanUi->get_view_data("activities");
$articles = $beanUi->get_view_data("articles");
$page = $beanUi->get_view_data("page");
$upcomingEvents = $beanUi->get_view_data("upcomingEvents");

$presCtr->get_header();
?>
<div class="mh-wrapper mh-home clearfix">
    <div id="main-content" class="mh-content mh-home-content clearfix">
        <div class="mh-widget mh-home-2 mh-widget-col-2 mh_slider_hp clearfix">
            
            <?php 
//            show($upcomingEvents);
            if( !empty( $upcomingEvents ) ) { ?>
            <div class="grey-bg"><?php echo $upcomingEvents[0]->name; ?></div>
            <div class="clearfix"></div>
            <p class="event-location">
               
                <i class="fa fa-dot-circle-o danger"></i>
                 <?php echo $upcomingEvents[0]->department; ?>
               
            </p>

            <div class="mh-home-2 mh-widget-col-2 mh_slider_hp clearfix">
                <h1 class="heading"><?php echo $upcomingEvents[0]->subject; ?></h1>
            </div>
            <p class="autor">
                <span class="mh-meta-date updated">
                    <i class="fa fa-clock-o"></i>
                    <?php echo date("F d,Y",strtotime($upcomingEvents[0]->date_of_event)); ?>
                    <?php echo date("h:i A", strtotime($upcomingEvents[0]->time_of_event)); ?>
                </span>
            </p>
            <div class="clearfix" style="margin-top:18px;"></div>

            <p>
                <?php echo $upcomingEvents[0]->description; ?>
            </p>
            
            <?php } ?>
        </div>
    </div>

    <div class="mh-widget-col-1 mh-sidebar">
        <?php include("rightsidebar.php"); ?>
        <?php include 'upcomingevent.php'; ?>
<?php include("statistical_data.php"); ?>
    </div>
</div>   
<?php $presCtr->get_footer(); ?>


</body>
</html>
