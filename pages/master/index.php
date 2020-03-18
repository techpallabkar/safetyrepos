<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$indexCtr = load_controller("IndexController");
$indexCtr->doAction();
$beanUi = $indexCtr->beanUi;
$directormessage = $beanUi->get_view_data("directormessage");
$activity_type_master = $beanUi->get_view_data("activity_type_master");
$activity_list = $beanUi->get_view_data("activity_list");
$devition_names = $beanUi->get_view_data("devition_names");
$bulletinList = $beanUi->get_view_data("bulletinList");
$indexCtr->get_header();
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
<style>
    .mh-custom-posts-header a {display:block;} 
</style>
 
<script>
    var cn = jQuery.noConflict();
    cn(document).ready(function () {
        cn('.scroller').scrollify();
        cn("#postlist #ids").click(function () {
            cn(this).parents().toggleClass("selected");
        });

    });
</script>  
<div class="mh-wrapper mh-home clearfix">
    <?php
    if ($activity_type_master !== '') {
        ?>
        <div id="main-content" class="mh-content mh-home-content clearfix">
            <div class="mh-home-columns">
                <?php
                $i = 0;
                foreach ($activity_type_master as $actvalue) {
                    $i = $i + 1;
                    if ($i % 2 == 0) {
                        $cls = "mh-home-area-4";
                        $clearfix = '<div class="clearfix"></div>';
                    } else {
                        $cls = "mh-home-area-3";
                        $clearfix = '';
                    }
                    $activity_rows = !empty($activity_list[$actvalue->id]) ? $activity_list[$actvalue->id] : array();
                   // print_r($clearfix);
                    echo '<div class="mh-widget-col-1 mh-sidebar mh-home-sidebar ' . $cls . '">
                    <div class="mh-widget">';
                    $img = "";
                    //if ($actvalue->id > 4) {
                        $img = '<img src="' . url('admin/assets/css/cropimage/img/' . $default_image[$actvalue->id]) . '" width="24" alt=""> ';
                    //}
                    echo '<h4 class="mh-widget-title">' . $img . $actvalue->activity_name . '</h4>';
                    if (count($activity_rows)) {
                        echo '<ul class="mh-custom-posts-widget clearfix">';
                        foreach ($activity_rows as $row) {
                            if ($row->activity_type_id != 8) {
                                $featuredimgPath = BASE_PATH . '/' . ($row->featured_image_path);
                                if (file_exists($featuredimgPath) && ($row->featured_image_path != "")) {
                                    $featuredimg = url($row->featured_image_path);
                                } else {
                                    $featuredimg = url("admin/assets/css/cropimage/img/" . $default_image[$row->activity_type_id]);
                                }
                            }
                            echo '<li class="mh-custom-posts-item home-item mh-custom-posts-small clearfix">';
                            /****thumb icon****/
                            //if ($actvalue->id > 4) {
                            $asd ="";
                            if ($actvalue->id == 1 || $actvalue->id == 2 || $actvalue->id == 4) {
                                    $month = date("M", strtotime($row->activity_date));
                                    $day = date("d", strtotime($row->activity_date));
                                }
                                 if ($actvalue->id == 3) {
                                    $month = date("M", strtotime($row->from_date));
                                    $day = date("d", strtotime($row->from_date));
                                }
                                if ($actvalue->id == 5 || $actvalue->id == 7) {
                                    $month = date("M", strtotime($row->date_of_audit));
                                    $day = date("d", strtotime($row->date_of_audit));
                                }
                                if ($actvalue->id == 6) {
                                    $month = date("M", strtotime($row->date_of_incident));
                                    $day = date("d", strtotime($row->date_of_incident));
                                }
                                if ($actvalue->id == 8) {
                                    $month = date("M", strtotime('01-' . $row->activity_month . '-2017'));
                                    //$day = date("M", strtotime($row->activity_month));
                                    $day = date("Y", strtotime($row->activity_year));
                                    $asd = "font-size: 24px;";
                                }

                                echo '<a style="color:#fff;" href="activitydetails.php?actcount='.$row->activity_count.'&actmonth='.$row->activity_month.'&actyear='.$row->activity_year.'&actype_id=' . $row->activity_type_id . '&id=' . $row->id . '"><div class="activity-icon home-icon date-color-' . $actvalue->id . '">'
                                . '<div class="activity-mm">' . $month . '</div>
                                <div class="activity-dd" style="'.$asd.'">' . $day . '</div>
                                </div></a>';
                           // }
//                            if ($actvalue->id < 5) {
//                                echo '<div class="mh-custom-posts-thumb">
//                                <a href="activitydetails.php?actype_id=' . $row->activity_type_id . '&id=' . $row->id . '">
//                                    <img width="60" height="60" alt="" src="' . $featuredimg . '" title="' . $row->subject_details . '" />
//                                </a>
//                            </div>';
//                            }
                            /****thumb icon*****/

                            echo '  <header class="mh-custom-posts-header">
                                <div class="mh-custom-posts-small-title uppercase">';
                            if ($actvalue->id < 5) {

                                echo '<a href="activitydetails.php?actype_id=' . $row->activity_type_id . '&id=' . $row->id . '">';
                                if (strlen(@$row->subject_details) > 60) {
                                    @$row->title = substr(@$row->subject_details, 0, 60) . '...';
                                    echo stripslashes(strtoupper(@$row->title));
                                    echo '<span class="tooltiptext">' . @$row->subject_details . '</span>';
                                } else {
                                    @$row->title = @$row->subject_details;
                                    echo stripslashes(strtoupper(@$row->title));
                                    echo '<span class="tooltiptext">' . @$row->subject_details . '</span>';
                                }
                                echo '</a>';
                            } else {
                                
                                echo '<div class="treeheading">';
                                if ($actvalue->id != 8) {
                                if (!empty($devition_names[$actvalue->id][$row->id])) {
                                    $carratva = str_replace("Total", "", $devition_names[$actvalue->id][$row->id]);
                                    $tree = str_replace("/", " <i class='fa fa-caret-right treecaret'></i> ", trim($carratva));
                                    echo '<a href="activitydetails.php?actcount='.$row->activity_count.'&actmonth='.$row->activity_month.'&actyear='.$row->activity_year.'&actype_id=' . $row->activity_type_id . '&id=' . $row->id . '">' . $tree;
                                    echo '<span class="tooltiptext">' . $tree . '</span></a>';
                                }
                                }
                                echo '</div>';
                            }
                            echo ' </div>';
                            echo '<div class="mh-meta mh-custom-posts-meta">
                                <span class="mh-meta-date updated">';
                            $dateview = array("1" => "activity_date",
                                "2" => "activity_date",
                                "3" => "from_date",
                                "4" => "activity_date",
                                "5" => "date_of_audit",
                                "6" => "date_of_incident",
                                "7" => "date_of_audit",
                                "8" => "activity_year");

                            if ($row->activity_type_id == 8) {
                                $mnths = date("F", strtotime('01-' . $row->activity_month . '-2017'));
                                $yearobs = $row->activity_year;
                                echo "&nbsp;&nbsp;";
                                             echo '<div class="mh-custom-posts-small-title uppercase" style="color:#000;font-size: 13px;margin-top: -18px;">'
                                . '<a style="color:#000;" href="activitydetails.php?actcount='.$row->activity_count.'&actmonth='.$row->activity_month.'&actyear='.$row->activity_year.'&actype_id=' . $row->activity_type_id . '&id=' . $row->id . '">'
                                . 'Activity Count : ' . stripslashes($row->activity_count) . '</a></div>';
                                echo '<i class="fa fa-clock-o"></i>'.$mnths . " , " . $yearobs;
                            } else {
                                echo '<i class="fa fa-clock-o"></i>'.date('F j, Y', strtotime(@$row->$dateview[$actvalue->id]));
                                echo ' <i class="fa fa-map-marker"></i>' . $row->place;
                            }
                            if ($row->activity_type_id == 8) {
                                
                                            
                            } else {
                                
                            }
                            echo '</span></div><p><strong><span class="category"></span></strong></p>';
                            echo '</header>';
                            echo '</li>';
                        }
                        echo '</ul>';
                        echo '<div style="float: right"><a href="activities.php?actype_id=' . $actvalue->id . '" class="read-more"><span class="fa fa-arrow-right"></span> View More</a></div>';
                    }
                    echo '</div></div>';
                    echo $clearfix;
                }
                echo ' </div></div>';
            }
            ?>
    <div class="mh-widget-col-1 mh-sidebar">
        
        <div class="mh-widget">	
            <?php
            if (!empty($directormessage)) {
                foreach ($directormessage as $key => $dirval) {
                    ?>
                    <div class="message-area">
                        <div class="content_type"><h4 class="mh-widget-title"> Message</h4></div>
                        <div class="mh-ad-spot">
                            <img class="thumb-image" width="80" alt="" src="<?php echo url($dirval->image_path) ?>" alt="<?php echo $dirval->contect_type ?>'s Message" />
                            <p class="descr" hidden=""><?php echo $dirval->description ?></p>
                            <p>
                                <?php
                                if (strlen($dirval->description) > 455)
                                    $dirval->description = substr($dirval->description, 0, 455) . '...';
                                echo stripslashes($dirval->description);
                                ?>
                            </p>                                                        
                            <p style="text-align: right"><a href="directormessage.php" class="read-more"><span class="fa fa-arrow-right"></span> Read more</a></p>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>	
        <div class="mh-widget">
            <h4 class="mh-widget-title"><a href="javascript:void(0)">Bulletin Board</a></h4>
            <div class="board">
                <div class="bulletin">
                    <ul class="bullet-board">
                        <?php
                        foreach ($bulletinList as $bval) {
                            $filePath = BASE_PATH . '/' . $bval->file_path;
                            echo '<li><p class="date"><span class="fa fa-clock-o"></span> ' . date("F d,Y", strtotime($bval->bulletin_date)) . ' &nbsp; &nbsp; <span class="fa fa-map-marker"></span> ' . $bval->department . '</p>
                                    <p>' . $bval->description . '</p>';
                            if ($bval->file_path != "" && file_exists($filePath)) {
                                echo '<a target="_blank" href="' . url($bval->file_path) . '" class="read-more" style="color:#A24FC9 !important;"> <i class="fa fa-file"> </i> View Bulletin File</a>';
                            }
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="list-group">
                <a href="#" class="list-group-item btnUp"><i class="fa fa-arrow-up"></i> Move Up</a> &nbsp; &nbsp; 
                <a href="#" class="list-group-item btnDown"><i class="fa fa-arrow-down"></i> Move Down</a> &nbsp; &nbsp; 
                <a href="#" class="list-group-item btnToggle">Play / Pause</a>
            </div>
        </div>
        <?php include 'upcomingevent.php'; ?>
        <?php include("statistical_data.php"); ?>
        <?php include("rightsidebar.php"); ?>
    </div>
    </div> 
    <div class="clearfix"></div>
    <script src="<?php echo url("assets/js/jquery.min005.js") ?>"></script>
    <script type="text/javascript" src="<?php echo url("assets/js/jquery.easy-ticker.min.js") ?>"></script>
    <script type="text/javascript">
        $(function () {
            $('.bulletin').easyTicker({
                direction: 'up',
                //easing: 'swing',
                //visible: 2,
                interval: 2500,
                controls: {
                    up: '.btnUp',
                    down: '.btnDown',
                    toggle: '.btnToggle'
                }
            });
        });         
    </script>
    <?php //$indexCtr->get_footer(); ?>
    </body>
    </html>
