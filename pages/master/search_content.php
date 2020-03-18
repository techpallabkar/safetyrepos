<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php');
$presCtr = load_controller("IndexController");
$presCtr->doAction();
$beanUi = $presCtr->beanUi;
$dataSearch = $beanUi->get_view_data("dataSearch");
$newdataSearch[5] = $beanUi->get_view_data("dataSearch5");
$newdataSearch[6] = $beanUi->get_view_data("dataSearch6");
$newdataSearch[7] = $beanUi->get_view_data("dataSearch7");
$newdataSearch[8] = $beanUi->get_view_data("dataSearch8");
$searchdata = ($beanUi->get_view_data("searchdata")) ? $beanUi->get_view_data("searchdata") : "";
$activityName = $beanUi->get_view_data("activityName");
$incident = $beanUi->get_view_data("incident");
$ppe_audit = $beanUi->get_view_data("ppe_audit");
$safety_observation = $beanUi->get_view_data("safety_observation");
$audit = $beanUi->get_view_data("audit");
$treeDivisionDepartment = $beanUi->get_view_data("treeDivisionDepartment");

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

//$mainDao = new MainDao();
//$eventCategory = $mainDao->getEventCategory();
?>

<div class="mh-wrapper mh-home clearfix">

    <div id="main-content" class="mh-content mh-home-content clearfix">
        <div class="mh-widget mh-home-2 mh-widget-col-2 mh_slider_hp clearfix">
            <div class="topic-heading"><h3>Search Result of : <span class="error"><?php echo $searchdata; ?></span></h3></div>
            <div class="mh-widget">

                <?php
                echo '<hr><table id="example">
             <thead>
               <tr><th></th></tr>
             </thead>
             <tbody>';

//show($dataSearch);
                foreach ($dataSearch as $rowData) {
                    $activtyname = $activityName[$rowData->id][0]->activity_name;
                     if($rowData->activity_type_id == 3 )
                        {
                            $showDate = date("F d, Y",strtotime($rowData->from_date));
                        }
                        else {
                            $showDate = date("F d, Y",strtotime($rowData->activity_date));
                        }
                      
                    ?>
                    <tr><td> <ul class="mh-custom-posts-widget clearfix">

                                <li class="mh-custom-posts-item mh-custom-posts-small">
                                    <div class="mh-custom-posts-thumb">                                        
                                    <?php
                                    $filePath = BASE_PATH.'/'.$rowData->featured_image_path;
                                    if ($rowData->featured_image_path != "" && file_exists($filePath)) {
                                        echo '<img src="' . url($rowData->featured_image_path) . '" alt="" width="60" height="60" >' . "\n";
                                    }
                                    else
                                    {
                                        echo '<img src="' . url("admin/assets/css/cropimage/img/".$default_image[$rowData->activity_type_id]) . '" alt="" width="60" height="60">' . "\n";
                                    }
                                    ?>
                                    </div>
                                    <header class="mh-custom-posts-header">
                                        <b>
                                            <a  target="_blank" href="<?php echo 'activitydetails.php?actype_id=' . $rowData->activity_type_id . '&id=' . $rowData->id . '' ?>">
                                            <?php echo $rowData->subject_details; ?>
                                            </a>
                                            <i style="color:#337ab7;float:right;font-size:11px;"><?php echo $activtyname; ?></i>
                                        </b>
                                        
                                        <br>
                                        
                                        <div class="mh-meta mh-custom-posts-meta" style="float: left">
                                            <?php echo '<span class="fa fa fa-clock-o"></span><i>'.$showDate.'</i>'; ?> &nbsp;&nbsp;
                                            <?php echo $rowData->place ? '<span class="fa fa fa-map-marker"></span><i>'.$rowData->place.'</i>' : ""; ?>
                                        </div>
                                        <div class="mh-meta mh-custom-posts-meta" style="float: right"></div>
                                        <div class="clearfix"></div>
                                        <!--<p class="category">Presentations by Others <span class="fa fa-caret-right"></span>Unmesh <span class="fa fa-caret-right"></span>2013</p>-->
                                    </header>
                                </li></ul></td></tr>

                <?php
                }

                for ($i = 5; $i < 9; $i++) {

                    $dataSearchforall = $newdataSearch[$i];
                  
                    $rowDataall = "newall" . $i;
                   
                    foreach ($dataSearchforall as $rowDataall) {
                        if($rowDataall->activity_type_id == 5 || $rowDataall->activity_type_id == 7 )
                        {
                            $showDate = date("F d, Y",strtotime($rowDataall->date_of_audit));
                        }
                        else if($rowDataall->activity_type_id == 6 ) {
                            $showDate = date("F d, Y",strtotime($rowDataall->date_of_incident));
                        }
                        else
                        {
                            $showDate = "";
                        }
                          $tree = $treeDivisionDepartment[$rowDataall->activity_type_id][$rowDataall->id];
//                    echo "<pre>";
//                    print_r($treeDivisionDepartment);
                        ?>
                        <tr><td> <ul class="mh-custom-posts-widget clearfix"> <li class="mh-custom-posts-item mh-custom-posts-small">
                                        <div class="mh-custom-posts-thumb">
                                            <?php
                                            $filePath = BASE_PATH.'/'.$rowDataall->featured_image_path;
                                            if ($rowDataall->featured_image_path != "" && file_exists($filePath)) {
                                                echo '<img src="' . url($rowDataall->featured_image_path) . '" alt="" width="60" height="60" >' . "\n";
                                            }
                                            else
                                            {
                                                echo '<img src="' . url("admin/assets/css/cropimage/img/".$default_image[$rowDataall->activity_type_id]) . '" alt="" width="60" height="60">' . "\n";
                                            }
                                            ?>
                                        </div>
                                        <header class="mh-custom-posts-header">
                                            <b><a target="_blank" href="<?php echo 'activitydetails.php?actype_id=' . $rowDataall->activity_type_id . '&id=' . $rowDataall->id . '' ?>"><?php echo $tree; ?></a>
                                                <i style="color:#337ab7;float:right;font-size:11px;"><?php echo $rowDataall->activity_name; ?></i>
                                            </b>
                                            <br>
                                            <div class="mh-meta mh-custom-posts-meta" style="float: left;">
                                                <span class="fa fa-clock-o"></span><i> <?php echo $showDate; ?></i>&nbsp;&nbsp;
                                                <span class="fa fa fa-map-marker"></span><i> <?php echo $rowDataall->place; ?></i>
                                                
                                            </div>
                                            <div class="mh-meta mh-custom-posts-meta" style="float: right"></div>
                                            <div class="clearfix"></div>
                                            <!--<p class="category">Presentations by Others <span class="fa fa-caret-right"></span>Unmesh <span class="fa fa-caret-right"></span>2013</p>-->
                                        </header>
                                    </li></ul></td></tr>

                    <?php
                    }
                }
                echo '</tbody></table>';
                ?>


                <br><br>		
            </div>

        </div>
    </div>

    <div class="mh-widget-col-1 mh-sidebar">

        

        <?php include("upcomingevent.php"); ?>
        <?php include("rightsidebar.php"); ?>
    </div>

    <script type="text/javascript" charset="utf8" src="<?php echo url("assets/js/jquery.dataTables.min.js"); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.dataTables.css"); ?>">
    <script>
        var conf = jQuery.noConflict();
        conf(function () {
            conf("#example").dataTable();
        })
    </script>
<?php $presCtr->get_footer(); ?>


</body>
</html>