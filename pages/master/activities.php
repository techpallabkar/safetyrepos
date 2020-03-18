<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$presCtr = load_controller("ActivityController");
$presCtr->doAction();
$beanUi = $presCtr->beanUi;
$activities = $beanUi->get_view_data("activities");
$articles = $beanUi->get_view_data("articles");
$page = $beanUi->get_view_data("page");
$draft_posts = $beanUi->get_view_data("draft_posts");
$draft_posts_paggin_html = $beanUi->get_view_data("draft_posts_paggin_html");
$draft_presentation = $beanUi->get_view_data("draft_presentation");
$draft_presentation_paggin_html = $beanUi->get_view_data("draft_presentation_paggin_html");
$activity_type_id = $beanUi->get_view_data("activity_type_id");
$activity_type_master = $beanUi->get_view_data("activity_type_master");
$activity_name = get_value_by_id($activity_type_id, "activity_name", $activity_type_master);
$devition_name = $beanUi->get_view_data("devition_name");
$presCtr->get_header();
$default_image = array(
    1 => "workshop.png",
    2 => "meeting.png",
    3 => "training.png",
    4 => "safety.png",
    5 => "audit.png",
    6 => "incident.png",
    7 => "ppeaudit.png",
    8 => "safetyobs.png"
);
?>

<div class="mh-wrapper mh-home clearfix">

    <div id="main-content" class="mh-content mh-home-content clearfix">
        <div class="mh-widget mh-home-2 mh-widget-col-2 mh_slider_hp clearfix">
            <div class="topic-heading">
                <h3><?php echo $activity_name; ?></h3>
            </div>
            
            <div class="mh-widget">
                <?php if (count($activities) > 0) {
                    ?>

                    <ul class="mh-custom-posts-widget clearfix">
    <?php
    foreach ($activities as $key => $actval) {
        ?>
                            <li class="mh-custom-posts-item mh-custom-posts-small clearfix">
                                <?php
                                $asd = "";
                                if ($actval->activity_type_id == 1 || $actval->activity_type_id == 2 || $actval->activity_type_id == 4) {
                                    $month = date("M", strtotime($actval->activity_date));
                                    $day = date("d", strtotime($actval->activity_date));
                                }
                                 if ($actval->activity_type_id == 3) {
                                    $month = date("M", strtotime($actval->from_date));
                                    $day = date("d", strtotime($actval->from_date));
                                }
                                    if ($actval->activity_type_id == 5 || $actval->activity_type_id == 7) {
                                        $month = date("M", strtotime($actval->date_of_audit));
                                        $day = date("d", strtotime($actval->date_of_audit));
                                    }
                                    if ($actval->activity_type_id == 6) {
                                        $month = date("M", strtotime($actval->date_of_incident));
                                        $day = date("d", strtotime($actval->date_of_incident));
                                    }
                                    if ($actval->activity_type_id == 8) {
                                        $month = date("M", strtotime('01-' . $actval->activity_month . '-2017'));
                                        $day = date("Y", strtotime($actval->activity_year."-01-01"));
                                        $asd = "font-size: 24px;";
                                    }
                                    echo '<div class="activity-icon date-color-' . $actval->activity_type_id . '">'
                                            . '<div class="activity-mm">' . $month . '</div>
                                            <div class="activity-dd" style="'.$asd.'">' . $day . '</div>
                                                </div>';
                                ?>
                                <header class="mh-custom-posts-header">
                                    <p class="mh-custom-posts-small-title uppercase">
                                        <?php if ($actval->activity_type_id < 5) { ?>
                                            <a href="activitydetails.php?actype_id=<?php echo $actval->activity_type_id ?>&id=<?php echo $actval->id ?>">
                                                <?php
                                                if (strlen($actval->subject_details) > 160)
                                                    $actval->title = substr($actval->subject_details, 0, 160) . '...';
                                                echo stripslashes($actval->subject_details);
                                                ?>
                                            
                                            <span class="tooltiptext"><?php echo $actval->subject_details; ?></span>
											</a>
                                        <?php }else {
                                            if ($actval->activity_type_id != 8) { 
                                            ?>
                                          
                                            <?php
                                            $carratva = str_replace("Total", "", @$devition_name[$actval->activity_type_id][$actval->id]);
                                            $tree = str_replace("/", " <i class='fa fa-caret-right treecaret'></i> ", trim($carratva));
                                            //echo $tree;?>
                                        <a href="activitydetails.php?actcount=<?php echo $actval->activity_count; ?>&actmonth=<?php echo $actval->activity_month; ?>&actyear=<?php echo $actval->activity_year;?>&actype_id=<?php echo $actval->activity_type_id ?>&id=<?php echo $actval->id ?>"> 
                                                <?php echo $tree; ?>
                                            
                                         
                                            <span class="tooltiptext"><?php echo $tree; ?></span>
											</a>
                                            <?php } } ?> 
                                    </p>
                                    <div style="float: left" class="mh-meta mh-custom-posts-meta">
                                        <?php
                                        if (@$actval->activity_no != "") {
                                            echo '<span class="fa fa-bar-chart"></span> ' . stripslashes($actval->activity_no) . ' ';
                                            echo "&nbsp;&nbsp;";
                                        }
                                          echo '&nbsp;<span class="mh-meta-date updated">' . "\n";
                                            if($actval->activity_type_id == 3) {
                                                echo '<i class="fa fa-clock-o"></i>' . date('F j, Y', strtotime($actval->from_date)) . "\n";
                                            } else if($actval->activity_type_id == 5 || $actval->activity_type_id == 7) {
                                                echo '<i class="fa fa-clock-o"></i>' . date('F j, Y', strtotime($actval->date_of_audit)) . "\n";
                                            }  else if($actval->activity_type_id == 6) {
                                                echo '<i class="fa fa-clock-o"></i>' . date('F j, Y', strtotime($actval->date_of_incident)) . "\n";
                                            } else if($actval->activity_type_id == 8) {
                                                  echo '<div class="mh-custom-posts-small-title uppercase" style="color:#000;font-size: 13px;margin-top: -18px;">'
                                                . '<a style="color:#000;" href="activitydetails.php?actcount='.$actval->activity_count.'&actmonth='.$actval->activity_month.'&actyear='.$actval->activity_year.'&actype_id='.$actval->activity_type_id.'&id='.$actval->id.'"> '
                                                          . 'Activity Count : '
                                                . '' . stripslashes($actval->activity_count) . '</a></div>';
                                            echo "&nbsp;&nbsp;";
                                                        $mnths = date("F", strtotime('01-'.$actval->activity_month.'-2017'));
                                                    $yearobs = $actval->activity_year;
                                                echo '<i class="fa fa-clock-o"></i>' . $mnths.' , '.$yearobs . "\n";
                                            } else {
                                               echo '<i class="fa fa-clock-o"></i>' . date('F j, Y', strtotime($actval->activity_date)) . "\n"; 
                                            }
                                            
                                            echo '</span>' . "\n";
                                         if ($actval->activity_type_id == 8) { 
                                         } else {
                                        if ($actval->place != "") {
                                            echo '<span class="fa fa-map-marker"></span> ' . stripslashes($actval->place) . '';
                                            echo "&nbsp;&nbsp;";
                                        }
                                         }
                                        ?>
                                    </div>

                                    <div class ="clearfix"></div>
                                    <p class="category"><?php echo @$actval->category_name ?></p>
                                </header>
                                
                            </li>
                        <?php } ?>
                    </ul>
                    <?php
                    echo $beanUi->get_view_data("paggin_html");
                    echo "<br /><br />";
                } else {
                    echo '<p>Activities not found.</p>';
                }
                ?>
            </div>
        </div>
    </div>

        <div class="mh-widget-col-1 mh-sidebar">
           
            <?php include 'upcomingevent.php'; ?>
            <?php include("statistical_data.php"); ?>
             <?php include("rightsidebar.php");?>
        </div>      
    </div>
<?php $presCtr->get_footer(); ?>
<script>
 $(document).ready(function () {
         $(".paginate_button").hide();
         $(".paginate_button:eq(2)").show();
         $(".paginate_button.current").show();
         $(".paginate_button.previous").show();
         $(".paginate_button").nextAll(".paginate_button:first").show();
         $(".paginate_button:nth-last-child(3)").show();
         $(".paginate_button:nth-last-child(2)").show();
         $(".paginate_button.current").nextAll(".paginate_button:lt(3)").show();
         $(".paginate_button.current").prevAll(".paginate_button:lt(1)").show();
         $(".paginate_button.current").nextAll(".paginate_button:gt(-2)").show(); 
        });
</script>

</body>
</html>
