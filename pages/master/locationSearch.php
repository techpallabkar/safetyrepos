<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$presCtr = load_controller("LibraryController");
$presCtr->doAction();
$beanUi = $presCtr->beanUi;
$post_categories = $beanUi->get_view_data("post_categories");
$post_participants_categories = $beanUi->get_view_data("post_participants_categories");
$post_division_department = $beanUi->get_view_data("post_division_department");
$draft_posts = $beanUi->get_view_data("draft_posts");
$draft_posts_paggin_html = $beanUi->get_view_data("draft_posts_paggin_html");
$draft_presentation = $beanUi->get_view_data("draft_presentation");
$draft_presentation_paggin_html = $beanUi->get_view_data("draft_presentation_paggin_html");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");//show($post_activity_type_master);
$post_status = $beanUi->get_view_data("post_status");
$getalldata = $beanUi->get_view_data("getalldata");
$selectval = $beanUi->get_view_data("selectval");
$devition_names = $beanUi->get_view_data("devition_names");//show($devition_names);
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$from_date = $beanUi->get_view_data("from_date");
$to_date = $beanUi->get_view_data("to_date");
$totalViolation = $beanUi->get_view_data("totalViolation");
$totalFindings = $beanUi->get_view_data("totalFindings");
$totalDeficiency = $beanUi->get_view_data("totalDeficiency");
$totalJobStopRequestRaised = $beanUi->get_view_data("totalJobRequest");
$totalPendingInvestigation = $beanUi->get_view_data("totalPendingInvestigation");//show($totalPendingInvestigation);
$totalPendingFindings = $beanUi->get_view_data("totalPendingFindings");
$incidentaddresseddata = $beanUi->get_view_data("incidentaddresseddata");
$incidentaddresseddataPending = $beanUi->get_view_data("incidentaddresseddataPending");
$belowThreeMonth = $beanUi->get_view_data("belowThreeMonth");
$betweenThreeTwelve = $beanUi->get_view_data("betweenThreeTwelve");
$twelveAndMore = $beanUi->get_view_data("twelveAndMore");
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
$presCtr->get_header();
?>
<script>
    $(document).ready(function () {
        $(".left-menu li button").click(function () {
            $(this).parents("li").addClass("active");
            $(this).parents().siblings("li").removeClass("active");
        });
    });
</script>
<script src="<?php echo url("assets/js/bootstrap.min.js"); ?>"></script>
<div class="mh-wrapper mh-home clearfix">
    <div class="print-friendly">
        <div class="topic-heading"><h3>Search By Location</h3></div>
        <div class="holder required clearfix newHolderdiv">
            <?php
            if (count($devition_names) > 0) {
                if (!empty($devition_names)) {
                    $j = 0;
                    $valxx = array();
                    echo '<div style="font-size:14px;">';
                    foreach ($devition_names as $key => $ddmrow) {
                        $j = $j + 1;
                        echo '<b class="read-more" style="font-size:14px;">Selected Location  </b>' . ' : &nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#d84910;"></i> ' . str_replace("/", '  <i class="fa fa-caret-right" style="color:#d84910;"></i>  ', $ddmrow) . '&nbsp;&nbsp;';
                        ?>
                        <a  class="btn btn-danger" href="" style="float:right;" onclick="return confirm('Are you sure want to search new record?');">
                            Search New
                        </a>
                        <br>
            <?php
        }
        echo '</div>';
    }
    if ($from_date != "") {
        echo isset($from_date) ? "<b>From : </b>" . date("d-m-Y",strtotime($from_date)) . "  " : "";
    }
    if ($to_date != "") {
        echo isset($to_date) ? " <b>To : </b>" . date("d-m-Y",strtotime($to_date)) : "";
    }
}
?>
        </div>
        <form method="post" id="searchForm">
            <table id="searchtable" class="table table-striped">
                <tr id="selecview">

<?php
if (count($devition_names) == 0) {
    echo '<td colspan="3"><label id="newLevel" for="synopsis">Select Division Department</label> &nbsp; &nbsp; &nbsp; &nbsp;';
    echo '<button type="button" class="btn open-AddBookDialog" data-toggle="modal" data-id="div_dept" data-target="#myModal"> <i class="fa fa-plus"></i> Select</button></td>';
}
?>
                </tr>
                <tr class="hideDiv">
                    <td colspan="3">
                        <div class="errorMsg error"></div>
                        From &nbsp;&nbsp;<input required="true" type="text" autocomplete="off" name="from_date" id="from_date" class="form-control datepicker" style="width:230px;" /> 
                        To&nbsp;&nbsp;<input required="true" type="text" autocomplete="off" name="to_date" id="to_date" class="form-control datepicker" style="width:230px;" />
                    </td>
                </tr>
                <tr class="hideDiv">
                    <td colspan="3"><input name="search"  type="submit" value="Search"></td>
                </tr>
            </table>
        </form>
        <div class="clearfix"></div>
<?php if ($selectval) { ?>
            <hr>
            <div class="search-area clearfix">
                <div class="mh-col-1-4">
                    <ul class="left-menu">
                        <li>
                            <form method="post" name="myform" id="form1" action="">
                                <input name="activity" type="hidden" value="">
                                <input name="selectval" type="hidden" value="<?php echo $selectval; ?>">
                                <input name="from_date" type="hidden" value="<?php echo $from_date; ?>">
                                <input name="to_date" type="hidden" value="<?php echo $to_date; ?>">
                                <input name="search" type="hidden" value="search">
                                <button type="submit">Recent Activities</button></form></li>
    <?php
    foreach ($post_activity_type_master as $key => $posts) {//show($post_activity_type_master);
        if ($posts->id != 10 && $posts->id != 9) {
            if($posts->id != 3){ 
            ?>
                                <li> 
                                    <form method="post" name="myform" id="form1" action="">
                                        <input name="activity" type="hidden" value="<?php echo $posts->id; ?>">
                                        <input name="activityName" type="hidden" value="<?php echo $posts->activity_name; ?>">
                                        <input name="selectval" type="hidden" value="<?php echo $selectval; ?>">
                                        <input name="from_date" type="hidden" value="<?php echo $from_date; ?>">
                                        <input name="to_date" type="hidden" value="<?php echo $to_date; ?>">
                                        <input name="search" type="hidden" value="search">
                                        <button type="submit"><?php echo $posts->activity_name; ?></button>
                                    </form>
                                </li>
        <?php } //Training 
        }
    } ?>
                    </ul>
                    <style>
                        .asd { color:#fff;background:none;font-size: 13px;font-weight: normal;text-transform: none;}
                    </style>
                    <div class="defendent">
                        <ul>
                            <li class="one">
                                <form method="post" name="myform" id="form1" action="">
                                    <input name="activity" type="hidden" value="5">
                                    <input name="selectval" type="hidden" value="<?php echo $selectval; ?>">
                                    <input name="activityName" type="hidden" value="<?php echo "Violation of Site Audit"; ?>">
                                    <input name="from_date" type="hidden" value="<?php echo $from_date; ?>">
                                    <input name="to_date" type="hidden" value="<?php echo $to_date; ?>">
                                    <input name="search" type="hidden" value="search">
                                    <input name="print" type="hidden" value="1">
                                    <button type="submit" class="asd">Violation of Site Audit</button></form>
                                <div class="counter"><?php echo ($totalViolation) ? $totalViolation : 0; ?></div>
                            </li>
                            <li class="two">
                                <form method="post" name="myform" id="form1" action="">
                                    <input name="activity" type="hidden" value="6">
                                    <input name="activityName" type="hidden" value="<?php echo "Findings of Incident"; ?>">
                                    <input name="selectval" type="hidden" value="<?php echo $selectval; ?>">
                                    <input name="from_date" type="hidden" value="<?php echo $from_date; ?>">
                                    <input name="to_date" type="hidden" value="<?php echo $to_date; ?>">
                                    <input name="search" type="hidden" value="search">
                                    <input name="print" type="hidden" value="1">
                                    <button type="submit" class="asd">Findings of Incident</button></form>
                                <div class="counter"><?php echo ($totalFindings) ? $totalFindings : 0; ?></div></li>
                            <li class="three">
                                <form method="post" name="myform" id="form1" action="">
                                    <input name="activity" type="hidden" value="7">
                                    <input name="activityName" type="hidden" value="<?php echo "Deficiency of PPE Audit"; ?>">
                                    <input name="selectval" type="hidden" value="<?php echo $selectval; ?>">
                                    <input name="from_date" type="hidden" value="<?php echo $from_date; ?>">
                                    <input name="to_date" type="hidden" value="<?php echo $to_date; ?>">
                                    <input name="search" type="hidden" value="search">
                                    <input name="print" type="hidden" value="1">
                                    <button type="submit" class="asd">Deficiency of PPE Audit</button></form>
                                <div class="counter"><?php echo ($totalDeficiency) ? $totalDeficiency : 0; ?></div>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                        <hr>
                        <ul>
                            <li style="width: 100%;">
                                <form style="width: 100%;" method="post" name="myform" id="form1" action="">
                                    <input name="activity" type="hidden" value="6">
                                    <input name="selectval" type="hidden" value="<?php echo $selectval; ?>">
                                    <input name="activityName" type="hidden" value="<?php echo "Pending Investigation"; ?>">
                                    <input name="from_date" type="hidden" value="<?php echo $from_date; ?>">
                                    <input name="to_date" type="hidden" value="<?php echo $to_date; ?>">
                                    <input name="peningivs" type="hidden" value="<?php echo ($totalPendingInvestigation) ? $totalPendingInvestigation : 0; ?>">
                                    <input name="search" type="hidden" value="search">
                                    <input name="print" type="hidden" value="1">
                                    <button type="submit" class="asd" style="background:#2a417c;width:100%;">
                                        No Of Investigation Pending <br> As on : <span style="color:#74ff02;"><?php echo date("dS F Y"); ?></span> 
                                        <div class="counternew"><?php echo ($totalPendingInvestigation) ? $totalPendingInvestigation : 0; ?></div>
                                    </button></form>
                                
                            </li>
<!--                            <li style="width: 100%;margin-top:5px;">
                                <form method="post" name="myform" id="form1" action="">
                                    <input name="activity" type="hidden" value="6">
                                    <input name="activityName" type="hidden" value="<?php //echo "Pending Action on Non-compliances"; ?>">
                                    <input name="selectval" type="hidden" value="<?php //echo $selectval; ?>">
                                    <input name="from_date" type="hidden" value="<?php //echo $from_date; ?>">
                                    <input name="to_date" type="hidden" value="<?php //echo $to_date; ?>">
                                    <input name="pendingAction" type="hidden" value="<?php //echo ($totalPendingFindings) ? $totalPendingFindings : 0; ?>">
                                    <input name="search" type="hidden" value="search">
                                    <input name="print" type="hidden" value="1">
                                    <button type="submit" class="asd" style="background:#68c4a8;width:100%;">
                                        Pending Action on Non-compliances
                                    <div class="counternew"><?php //echo ($totalPendingFindings) ? $totalPendingFindings : 0; ?></div>
                                    </button></form>
                                
                            </li>-->
                            <li style="width: 100%;margin-top:5px;">
                                <form method="post" name="myform" id="form1" action="">
                                    <input name="activity" type="hidden" value="5">
                                    <input name="activityName" type="hidden" value="<?php echo "Number of Stop"; ?>">
                                    <input name="selectval" type="hidden" value="<?php echo $selectval; ?>">
                                    <input name="from_date" type="hidden" value="<?php echo $from_date; ?>">
                                    <input name="to_date" type="hidden" value="<?php echo $to_date; ?>">
                                    <input name="jobstop" type="hidden" value="<?php echo ($totalJobStopRequestRaised) ? $totalJobStopRequestRaised : 0; ?>">
                                    <input name="search" type="hidden" value="search">
                                    <input name="print" type="hidden" value="1">
                                    <button type="submit" class="asd" style="background:#ed7a00;width:100%;">
                                        Job Stop raised during Site Audit <br> From : <span style="color:#000000;font-weight:500;"><?php echo date("d-m-Y", strtotime($from_date)); ?>"</span>To : <span style="color:#000000;font-weight:500;"><?php echo date("d-m-Y", strtotime($to_date)); ?>"</span>
                                        <div class="counternew"><?php echo ($totalJobStopRequestRaised) ? $totalJobStopRequestRaised : 0; ?></div>
                                    </button></form>
                                
                            </li>

                        </ul>
                        <h6>No. of incident investigation done where findings addressed <br> From : <span style="color:#CA0000;"><?php echo date("d-m-Y", strtotime($from_date)); ?>"</span>To : <span style="color:#CA0000;"><?php echo date("d-m-Y", strtotime($to_date)); ?>"</span></h6><hr>
                        <table class="pending-table">
                            <tr class="bg-primary">
                                <th>%</th>
                                <th>Incident</th>
                            </tr>
                                <?php
                            if(!empty($incidentaddresseddata)) {
                                foreach($incidentaddresseddata as $key =>$value ) {
                                   echo '<tr>
                                <td>'.$value["alias"].'</td>
                                <td>'.$value["perincvalue"].'</td>
                            </tr>'; 
                                }
                            }
                            ?>
                            
                        </table>
                        <h6>No. of incident investigation pending <span style="color:#CA0000;">(Including Near Miss)</span> <br> As on : <span style="color:#CA0000;"><?php echo date("dS F Y"); ?></span></h6><hr>
                        <table class="pending-table">
                            <tr class="bg-primary" >
                                <th>Pending for</th>
                                <th>Incident</th>
                            </tr>
                            <tr>
                                <td>0 Month - 3 Months</td>
                                <td><?php echo $belowThreeMonth; ?></td>
                            </tr>
                            <tr>
                                <td>3 Months - 12 Months</td>
                                <td><?php echo $betweenThreeTwelve; ?></td>
                            </tr>
                            <tr>
                                <td>12 Months - more than 12 Month </td>
                                <td><?php echo $twelveAndMore; ?></td>
                            </tr>
                                <?php
//                            if(!empty($incidentaddresseddataPending)) {
//                                foreach($incidentaddresseddataPending as $key =>$value ) {
//                                   echo '<tr>
//                                <td>'.$value["alias"].'</td>
//                                <td>'.$value["perincvalue"].'</td>
//                            </tr>'; 
//                                }
//                            }
                            ?>
                            
                        </table>
                    </div>
                     
                </div>

                <div class="mh-col-3-4 margin-right">
                    <div class="topic-heading">
                        <h3> 
    <?php
    echo isset($_REQUEST["activityName"]) ? $_REQUEST["activityName"] : "Recent Activities";
    ?>  
                        </h3>

                    </div>


                            <?php
                            if (count($getalldata)) {
                                echo isset($_REQUEST["print"]) ? '<a href="searchPrint.php?activity_id=' . $_REQUEST["activity"] . '&selectval=' . $selectval . '&from_date=' . $_REQUEST["from_date"] . '&to_date=' . $_REQUEST['to_date'] . '&jobstop=' . $_REQUEST['jobstop'] . '&peningivs=' . $_REQUEST['peningivs'] . '&pendingAction=' . $_REQUEST['pendingAction'] . '" target="_blank" class="btn btn-danger"><i class="fa fa-eye"></i>  Preview Report</a>' : "";
                                echo '<hr><table id="example">
                                        <thead>
                                          <tr><th></th></tr>
                                        </thead>
                                        <tbody>';

                                foreach ($getalldata as $key => $datavalue) {
                                    ?>
                            <tr><td>   <ul class="mh-custom-posts-widget clearfix"> <li class="mh-custom-posts-item mh-custom-posts-small clearfix">
                                            <div class="mh-custom-posts-thumb">
                                                <a href="activitydetails.php?mode=srch&activityName=<?php echo $_REQUEST["activityName"]; ?>&activity=<?php echo $_REQUEST["activity"]; ?>&from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>&selectval=<?php echo $selectval; ?>&actcount=<?php echo $datavalue->activity_count; ?>&actmonth=<?php echo $datavalue->activity_month; ?>&actyear=<?php echo $datavalue->activity_year; ?>&actype_id=<?php echo $datavalue->activity_type_id . '&id=' . $datavalue->id ?>">
                            <?php
                            $storePath = BASE_PATH . '/' . $datavalue->featured_image_path;

                            if (file_exists($storePath) && $datavalue->featured_image_path != "") {
                                ?>
                                                        <img width="60" height="60" src="<?php echo url($datavalue->featured_image_path) ?>" alt="" />
                                                    <?php } else { ?>
                                                        <img width="60" height="60" src="<?php echo url("admin/assets/css/cropimage/img/" . $default_image[$datavalue->activity_type_id]) ?>" alt="" />
                                                    <?php } ?>
                                                </a>
                                            </div>
                                            <header class="mh-custom-posts-header">
                                                <p class="mh-custom-posts-small-title uppercase">
                                                    
                                                    <a href="activitydetails.php?actype_id=<?php echo $datavalue->activity_type_id . '&id=' . $datavalue->id ?>"><?php echo @$datavalue->subject_details; ?></a>
                                                </p>
                                                <div class="mh-meta mh-custom-posts-meta">
                                                    <span class="mh-meta-date updated">
                                                        <i class="fa fa-clock-o"></i>
                                                        <?php
                                                        if ($datavalue->activity_type_id == 3) {
                                                            $month = date("F d, Y", strtotime($datavalue->from_date));
                                                        }
                                                        if ($datavalue->activity_type_id == 1 || $datavalue->activity_type_id == 2 || $datavalue->activity_type_id == 4) {
                                                            $month = date("F d, Y", strtotime($datavalue->activity_date));
                                                        }
                                                        if ($datavalue->activity_type_id == 5 || $datavalue->activity_type_id == 7) {
                                                            $month = date("F d, Y", strtotime($datavalue->date_of_audit));
                                                        }
                                                        if ($datavalue->activity_type_id == 6) {
                                                            $month = date("F d, Y", strtotime($datavalue->date_of_incident));
                                                        }
                                                        if ($datavalue->activity_type_id == 8) {
                                                            $mnth = date("F",strtotime(date("Y").'-'.(sprintf('%02d',$datavalue->activity_month)).'-01'));
                                                            $yrs = ($datavalue->activity_year);
                                                            $complete = "$yrs, $mnth";

                                                            $month = $complete;
                                                        }


                                                        echo $month;
                                                        ?>
                                                        <i class="fa fa-map-marker"></i><?php echo $datavalue->place; ?>
                                                    </span>
                                                </div>
                                                <div class="clearfix"></div>
                                            </header>
                                        </li></ul></td></tr>
                                                        <?php
                                                        echo $beanUi->get_view_data("paggin_html");
                                                    }echo '</tbody></table>';
                                                } else {
                                                    echo '<div class="norecord">No record found</div>';
                                                }
                                                ?>

                    <br>

                </div>
            </div>
                <?php } ?>

    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Division Department</h4>
                </div>
                <div class="modal-body" style="height:350px;">
                    <input type="hidden" id="idbuttn">
                    <input type="hidden" id="closestid">
                    <input type="hidden" id="tree_department">
                    <button type="button" id="reset_button" class="btn btn-danger btn-sm" style="float:right;">Reset</button>
                    <form><div id="level_error"></div></form>


                    <div class="levelfirst clearfix">
                        <label class="label">CESC</label>
                        <select class="division" name="L1" id="L1">
                            <option  value="" selected="">-Choose one-</option>
                                <?php
                                foreach ($post_division_department as $rowvalue) {
                                    if ($rowvalue->parent_id == 1 && $rowvalue->id == 249) {
                                        echo '<option value="' . $rowvalue->id . '">' . $rowvalue->name . '</option>';
                                    }
                                }
                                ?>
                        </select>
                    </div>


                    <div id="level2" class="searchLabel clearfix"></div>
                    <div class="levelfour" class="searchLabel clearfix"></div>
                    <div id="level3" class="searchLabel clearfix"></div>
                    <div id="level4" class="searchLabel clearfix"></div>
                    <div id="level5" class="searchLabel clearfix"></div>
                    <div id="level6" class="searchLabel clearfix"></div>
                    <div id="level7" class="searchLabel clearfix"></div>

                    <script type="text/javascript">
                        $(document).ready(function () {
                            $("#reset_button").click(function () {
                                $('#L1').val('');
                                $(".division").val('');
                                $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                            });

                            $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                            var lcount = 1;
                            $("#L" + lcount).on('change', function () {
                                $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                var level1 = $(this).val();
                                var lc = lcount + 1;
                                $.ajax({
                                    type: 'post',
                                    data: {
                                        "action": "get_nextlevel",
                                        "id": level1,
                                        "lcount": lc
                                    },
                                    cache: false,
                                    success: function (get_nextlevel) {

                                        if (get_nextlevel)
                                        {
                                            division_department_treeview(lc, level1, get_nextlevel, tbname = null);
                                        }
                                    }
                                });
                            });



                            /*tree function Start*/
                            function division_department_treeview(lcount, ids, get_nextlevel, tb = null) {

                                $("#level" + lcount).html(get_nextlevel);
                                //$("#level" + lcount).css("margin-top", "20px");
                                $("#level" + lcount).show();
                                $("#L" + lcount).on('change', function () {
                                    var lc = lcount + 1;
                                    var level_id = $(this).val();
                                    var groupval;
                                    if (level_id == '251' || level_id == '252')
                                    {
                                        if (level_id == '251')
                                        {
                                            groupval = '2';
                                        }
                                        if (level_id == '252')
                                        {
                                            groupval = '3';
                                        }
                                        $.ajax({
                                            type: 'post',
                                            data: {
                                                "action": "get_contractor_list",
                                                "id": groupval
                                            },
                                            cache: false,
                                            success: function (get_contractor_list) {

                                                if (get_contractor_list)
                                                {
                                                    //alert(get_contractor_list);
                                                    $(".levelfour").show();
                                                    $(".levelfour").html(get_contractor_list);
                                                }
                                            }
                                        });


                                    } else
                                    {

                                        $.ajax({
                                            type: 'post',
                                            data: {
                                                "action": "get_nextlevel",
                                                "id": level_id,
                                                "lcount": lc
                                            },
                                            cache: false,
                                            success: function (get_nextlevel) {

                                                division_department_treeview(lc, level_id, get_nextlevel, tb = null);

                                                $(".newcons").on('change', function () {
                                                    var sdd = $(this).find(':selected').data("other");
                                                    var cc = $(this).find(':selected').data("c");
                                                    //alert(sdd);
                                                    if (sdd != 0)
                                                    {
                                                        $('#' + sdd).html('<label class="label"><b>Other Name</b></label>'
                                                                + '<input class="division" name=""  id="new_' + sdd + '" type="text" value="" />');
                                                    } else
                                                    {
                                                        $('#' + cc).html('');
                                                    }
                                                    $('#show_location').html($(this).find(':selected').data("location"));
                                                });
                                            }
                                        });

                                    }
                                });
                            }
                            /*tree function End*/
                            $(".set").hide();
                            $(".hideDiv").hide();
                            /*tree Save Start*/
                            $('#btnSave').click(function () {
                                var firstLevel = $("#L1").val();
                                if (firstLevel == "")
                                {
                                    $("#level_error").html('<div class="error">* Select atleast one record.</div>');
                                    return false;
                                }
                                $(".open-AddBookDialog").hide();
                                $("#newLevel").hide();
                                $(".newHolderdiv").hide();
                                $(".hideDiv").show();
                                var allselevals = "";
                                var alltext = "";
                                $(".division").each(function (index) {
                                    var vals = $(this).children("option:selected").val();

                                    if (vals != "") {
                                        allselevals += vals + "-";
                                        var text = $(this).children("option:selected").text();//alert(text);
                                        alltext += "<li>" + text + "</li>";
                                    }
                                });
                                 var  allseleval = allselevals.slice('0','-1');
                                 
                                $("#selecview ").html('<td colspan="3" style="vertical-align: top;padding:10px;"><b class="read-more" style="font-size:14px;float:left;">Selected Location  : </b>  <input type="hidden" value="' + allseleval + '" id="selectval" name="selectval"><ul class="searchnode" style="float:left;">' + alltext + '</ul><a class="deleterow" style="float:right;"><span class="search">RESET</span></a></td>');

                                $(".deleterow").click(function () {
                                    var cnfrm = confirm("Search With New Data ?");
                                    if (cnfrm === true) {
                                        $("#selecview").html('<td colspan="3"><label id="newLevel" for="synopsis">Select Division Department</label> &nbsp; &nbsp; &nbsp; &nbsp;\n\
               <button type="button" class="btn open-AddBookDialog" data-toggle="modal" data-id="div_dept" data-target="#myModal"> <i class="fa fa-plus"></i> Select</button></td> ');
                                        $("#selecview").show();
                                        $(".hideDiv").hide();
                                    } else {
                                        return false;
                                    }
                                });
                                $('#L1').val('');
                                $('.division').val('');
                                $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();

                            });
                            /*tree Save End*/


                        });
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" class="btn btn-primary" data-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <style>
        .modal-open {
            overflow: hidden;
        }
        .modal {
            display: none;
            overflow: hidden;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1050;
            -webkit-overflow-scrolling: touch;
            outline: 0;
            height:560px;
        }
        .modal.fade .modal-dialog {
            -webkit-transform: translate(0, -25%);
            -ms-transform: translate(0, -25%);
            -o-transform: translate(0, -25%);
            transform: translate(0, -25%);
            -webkit-transition: -webkit-transform 0.3s ease-out;
            -moz-transition: -moz-transform 0.3s ease-out;
            -o-transition: -o-transform 0.3s ease-out;
            transition: transform 0.3s ease-out;
        }
        .modal.in .modal-dialog {
            -webkit-transform: translate(0, 0);
            -ms-transform: translate(0, 0);
            -o-transform: translate(0, 0);
            transform: translate(0, 0);
        }
        .modal-open .modal {
            overflow-x: hidden;
            overflow-y: auto;
        }
        .modal-dialog {
            position: relative;
            width: auto;
            margin: 10px;
        }
        .modal-content {
            position: relative;
            background-color: #fff;
            border: 1px solid #999;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
            box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
            background-clip: padding-box;
            outline: 0;
        }
        .modal-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            background-color: #000;
        }
        .modal-backdrop.fade {
            opacity: 0;
            filter: alpha(opacity=0);
        }
        .modal-backdrop.in {
            opacity: 0.5;
            filter: alpha(opacity=50);
        }
        .modal-header {
            padding: 15px;
            border-bottom: 1px solid #e5e5e5;
        }
        .modal-header .close {
            margin-top: -2px;
        }
        .modal-title {
            margin: 0;
            line-height: 1.42857143;
        }
        .modal-body {
            position: relative;
            padding: 15px;
        }
        .modal-footer {
            padding: 15px;
            text-align: right;
            border-top: 1px solid #e5e5e5;
        }
        .modal-footer .btn + .btn {
            margin-left: 5px;
            margin-bottom: 0;
        }
        .modal-footer .btn-group .btn + .btn {
            margin-left: -1px;
        }
        .modal-footer .btn-block + .btn-block {
            margin-left: 0;
        }
        .modal-scrollbar-measure {
            position: absolute;
            top: -9999px;
            width: 50px;
            height: 50px;
            overflow: scroll;
        }
        @media (min-width: 768px) {
            .modal-dialog {
                width: 70%;
                margin: 30px auto;
            }
            .modal-content {
                -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            }
            .modal-sm {
                width: 300px;
            }
        }
        @media (min-width: 992px) {
            .modal-lg {
                width: 700px;
            }
        }
    </style>  

    <link rel="stylesheet" type="text/css" href="<?php echo url("admin/assets/css/jquery.datetimepicker.css") ?>"/>
    <script src="<?php echo url("admin/assets/js/jquery.datetimepicker.full.js") ?>"></script>
    <script>
        jQuery.datetimepicker.setLocale('en');
        jQuery('.datepicker').datetimepicker({

            timepicker: false,
            scrollMonth: false,
            scrollInput: false,
            format: 'Y-m-d',
            step: 5


        });

        $("#searchForm").submit(function () {
            var fromdate = $("#from_date").val();
            var todate = $("#to_date").val();
            if (fromdate != "" && todate == "")
            {
                $(".errorMsg").html("* Select to date.");
                return false;
            } else if (fromdate == "" && todate != "")
            {
                $(".errorMsg").html("* Select from date.");
                return false;
            } else
            {
                return true;
            }
        });
    </script>
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