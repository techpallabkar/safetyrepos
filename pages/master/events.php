<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$presCtr = load_controller("ActivityController");
$presCtr->doAction();
$beanUi = $presCtr->beanUi;
$recent_post = $beanUi->get_view_data("recent_post");
$mostviewd = $beanUi->get_view_data("mostviewd");
$activities = $beanUi->get_view_data("activities");
$upcomingEvents = $beanUi->get_view_data("upcomingEvents");
$eventName = $beanUi->get_view_data("eventName");
$page = $beanUi->get_view_data("page");
$presCtr->get_header();
$currentDate = date("Y-m-d");
?>
<div class="mh-wrapper mh-home clearfix">
    <div id="main-content" class="mh-content mh-home-content clearfix">
        <div class="mh-widget mh-home-2 mh-widget-col-2 mh_slider_hp clearfix">
            <div class="topic-heading"><h3> Upcoming Events :  <span style="color:#e70202;"><?php echo  $eventName; ?> </span>  </h3></div>
            <ul class="mh-custom-posts-widget clearfix">
                <?php
                if( !empty( $upcomingEvents ) ) {
                foreach ($upcomingEvents as $arrData) {
                    ?>
                    <li class="mh-custom-posts-item mh-custom-posts-small clearfix">
                        
                        <div class="mh-custom-posts-thumb-borderless">
                            <div class="event_calendar">
                                <div class="event_date"><?php echo date("d", strtotime($arrData->date_of_event)); ?></div>
                            </div>
                        </div>
                        <header class="mh-custom-posts-header">
                            <p class="mh-custom-posts-small-title" style="">
                                <a href="event_details.php?rowid=<?php echo $arrData->id; ?>">
                                    <?php
                                    echo substr(strip_tags($arrData->subject), 0, 90);
                                    if (strlen($arrData->subject) > 90) {
                                        echo "...";
                                    }
                                    ?>
                                    <span class="tooltiptext"><?php echo $arrData->subject; ?></span>
                                </a>
                                <span style="float:right;"><?php
                                if( $arrData->status == "completed" )
                                    {
                                        $statusIcon = '<i class="fa fa-check-circle text-success fa-2x" style="color:green;"></i>';
                                    }
                                    else if( ($arrData->status == "upcoming") && ($arrData->date_of_event < $currentDate) )
                                    {
                                       $statusIcon = '<i class="fa fa-exclamation-circle fa-2x" style="color:#E70202;"></i>'; 
                                    }
                                    else
                                    {
                                        $statusIcon = '';
//                                        $statusIcon = '<i class="fa fa-arrow-circle-down text-info fa-2x" style="color:#0683F8;"></i>';
                                    }
                                echo $statusIcon; 
                                ?></span>
                            </p>
                            
                            <div class="mh-meta mh-custom-posts-meta">
                                <span class="mh-meta-date updated">
                                    <i class="fa fa-clock-o"></i> 
                                        <?php echo date("F d, Y", strtotime($arrData->date_of_event)); ?>
                                        <?php echo date("h:i A", strtotime($arrData->time_of_event)); ?>
                                    &nbsp;&nbsp;
                                    <i class="fa fa-map-marker"></i> <?php echo ucfirst($arrData->location); ?>
                                </span>
                            </div>
                           
                            <p class="dept">
    <?php echo '<i class="fa fa-caret-right"></i> ' . strtoupper($arrData->department); ?>
                            <!--Generation <i class="fa fa-caret-right"></i> BBGS <i class="fa fa-caret-right"></i> BBSG <i class="fa fa-caret-right"></i> AZAD CONSTRUCTION-->
                            </p>

                        </header>
                    </li>
                <?php } } ?>

            </ul>
        </div>
    </div>

    <div class="mh-widget-col-1 mh-sidebar">
       
<?php include 'upcomingevent.php'; ?>
<?php include("statistical_data.php"); ?>
         <?php include("rightsidebar.php"); ?>
    </div>
</div>   
<?php $presCtr->get_footer(); ?>


</body>
</html>
