<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityController");
$controller->doAction();
$beanUi = $controller->beanUi;

$page = $beanUi->get_view_data("page");
$allactivities = $beanUi->get_view_data("allactivities");
$allaudit = $beanUi->get_view_data("allaudit");
$allsafetyobservation = $beanUi->get_view_data("allsafetyobservation");
$allsafetyobservationlinefunction = $beanUi->get_view_data("allsafetyobservationlinefunction");
$allminutesofmeetingfunction = $beanUi->get_view_data("allminutesofmeetingfunction");
$allppeaudit = $beanUi->get_view_data("allppeaudit");
$allincident = $beanUi->get_view_data("allincident");
$posts_paggin_html = $beanUi->get_view_data("posts_paggin_html");
$post_status = $beanUi->get_view_data("post_status");
$search_title = $beanUi->get_view_data("search_title");
$activity_month = $beanUi->get_view_data("activity_month");
$activity_year = $beanUi->get_view_data("activity_year");
$fromdate = $beanUi->get_view_data("fromdate");
$todate = $beanUi->get_view_data("todate");
$status_id = $beanUi->get_view_data("status_id");
$districtid = $beanUi->get_view_data("districtid");
$incident_category_ids = $beanUi->get_view_data("incident_category_ids");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$activity_type_id = $beanUi->get_view_data("activity_type_id");
$incident_category = $beanUi->get_view_data("incident_category");
$activities = get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);
$role_id = $controller->get_auth_user("role_id");
$loginuserid = $controller->get_auth_user("id");
$allDistrict = $beanUi->get_view_data("allDistrict");
$get_allactivity_type_master = $beanUi->get_view_data("get_allactivity_type_master");
$currentDate = date('Y-m-d');
$controller->get_header();

if ($activity_type_id == 5) {
    $pagelink = "add_audit.php";
} else if ($activity_type_id == 6) {
    $pagelink = "add_incident.php";
} else if ($activity_type_id == 7) {
    $pagelink = "add_ppe_audit.php";
} else if ($activity_type_id == 8) {
    $pagelink = "add_safety_observation.php";
} else if ($activity_type_id == 9) {
    $pagelink = "add_safety_observation_line_function.php";
} else if ($activity_type_id == 10) {
    $pagelink = "add_mom.php";
} else {
    $pagelink = "create.php";
}
//print_r($_SESSION['act_srch']);
?>
<style>
    .labelcls {padding-top: 4px;width:15% !important;}
    select {width:100% !important;}
    .search{margin-bottom:0px !important;width:100% !important;}
    #search-table tr td{border: none;}
</style>
<div class="container1">
    <!--********mbvp(pallab)**************-->
    <?php
    $createdurl = page_link('activity/index.php?');
    ?>
    <table id="search-table" class="table table-condensed" style="margin-bottom:0px;">
        <tr>
            <td width="20%"><b>Search By Activity :</b></td>
            <td  width="25%">
                <select name="allactivity" id="allactivity" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">

                    <?php
                    foreach ($get_allactivity_type_master as $rowdata) {
                        if($rowdata->id != 8){
                            echo '<option value="' . $createdurl . ' page=' . $_SESSION['act_srch']['page'] . '&activity_id=' . $rowdata->id . '&fromdate=' . $_SESSION['act_srch']['from_date'] . '&todate=' . $_SESSION['act_srch']['to_date'] . '&status_id='. $_SESSION['act_srch']['status_id'] .'&districtid='.$_SESSION['act_srch']['districtid'].'" ' . ($activity_type_id == $rowdata->id ? "selected" : "") . '>' . $rowdata->activity_name . '</option>';
                        }else{
                            echo '<option value="' . $createdurl . ' page=' . $_SESSION['act_srch']['page'] . '&activity_id=' . $rowdata->id . '&activity_month=' . $_SESSION['act_srch']['activity_month'] . '&activity_year=' . $_SESSION['act_srch']['activity_year'] . '&activity_no='. $_SESSION['act_srch']['activity_no'] .'&search_title='. $_SESSION['act_srch']['search_title'] .'&status_id='. $_SESSION['act_srch']['status_id'] .'&districtid='.$_SESSION['act_srch']['districtid'].'" ' . ($activity_type_id == $rowdata->id ? "selected" : "") . '>' . $rowdata->activity_name . '</option>';
                        }
                    }
                    ?>
                </select>
            </td>
            <td  width="55%"></td>
        </tr>
    </table>
    <hr />
    <!--********mbvp(pallab)**************-->
    <?php echo $beanUi->get_message(); ?>
    <h1 class="heading" style="margin:0 0 8px;"><?php echo $activities; ?>
        <a href="<?php echo $pagelink; ?>?activity_id=<?php echo $activity_type_id; ?>" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a>
    <!--For nm-->
    <?php
    if ($activity_type_id == 5) { ?>
        <!--<a href="<?php //echo page_link('activity/add_audit_nm.php'); ?>?activity_id=<?php //echo $activity_type_id; ?>" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Add New (Generation And Others Department)</a>-->
        <a href="<?php echo page_link('activitynew/add_audit_new.php'); ?>?activity_id=<?php echo $activity_type_id; ?>" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Add New (Generation And Others Department)</a>
    <?php } ?>
    <?php
    if ($activity_type_id == 7) { ?>
        <a href="<?php echo page_link('activity/add_ppe_audit_nm.php'); ?>?activity_id=<?php echo $activity_type_id; ?>" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Add New (Generation And Others Department)</a>
    <?php } ?>
    <!--For nm-->
    </h1> 

    <?php
    $searchdata = "";
    $activity_no = "";
    if ($activity_type_id == 5) {
        $searchdata = '<tr>'
                . '<td><b>From Date :</b></td>'
                . '<td><input autocomplete="off" type="text" name="fromdate" id="fromdate" class="search datetimepicker" value="' . $fromdate . '" placeholder="From date"  /></td>'
                . '<td width="10%"></td>'
                . '<td><b>To Date :</b></td>'
                . '<td><input type="text" autocomplete="off" name="todate" id="todate" class="search datetimepicker" value="' . $todate . '" placeholder="To date"  /></td>'
                . '</tr>'
                . '<tr>'
                . '<td><b>Audit No. :</b></td>'
                . '<td><input type="text" name="activity_no" id="activity_no" class="search" value="' . $activity_no . '" placeholder="Search by Audit no" /></td>'
                . '<td></td>'
                . '<td><b>Venue : </b></td>'
                . '<td> <input type="text" name="search_title" id="search_title" class="search" value="' . $search_title . '" placeholder="Search by Venue" /></td>'
                . '</tr>';
    } else if ($activity_type_id == 6) {
        $searchdata = '<tr>'
                . '<td><b>From Date :</b></td>'
                . '<td><input style="width:100%;" type="text" autocomplete="off" name="fromdate" id="fromdate" class="search datetimepicker" value="' . $fromdate . '" placeholder="From date"  /></td>'
                . '<td width="10%"></td>'
                . '<td><b>To Date :</b></td>'
                . '<td><input style="width:100%;" type="text" autocomplete="off" name="todate" id="todate" class="search datetimepicker" value="' . $todate . '" placeholder="To date"  /></td>'
                . '</tr>'
                . '<tr><td><b>Incident Category :</b></td><td>'
                . '<select name="incident_category_ids" id="incident_category_ids" class="search" >
                    <option value="">Search By Incident Category</option>
                    <option value="1" ' . ($incident_category_ids == 1 ? "selected" : "") . '>NEAR MISS</option>
                    <option value="2" ' . ($incident_category_ids == 2 ? "selected" : "") . '>FAC</option>
                    <option value="3" ' . ($incident_category_ids == 3 ? "selected" : "") . '>LWDC</option>
                    <option value="4" ' . ($incident_category_ids == 4 ? "selected" : "") . '>FATAL</option></select></td>'
                . '<td></td>'
                . '<td><b>Venue : </b></td>'
                . '<td><input type="text" name="search_title" id="search_title" class="search" value="' . $search_title . '" placeholder="Search by Venue" /></td>'
                . '</tr>';
    } else if ($activity_type_id == 7) {
        $searchdata = '<tr><td><b>From Date :</b></td> '
                . '<td><input type="text" autocomplete="off" name="fromdate" id="fromdate" class="search datetimepicker" value="' . $fromdate . '" placeholder="From date"  /></td>'
                . '<td width="10%"></td><td><b>To Date :</b></td>'
                . '<td><input type="text" autocomplete="off" name="todate" id="todate" class="search datetimepicker" value="' . $todate . '" placeholder="To date"  /></td></tr>'
                . '<tr><td><b>Audit No. :</b></td>'
                . '<td><input type="text" name="activity_no" id="activity_no" class="search" value="' . $activity_no . '" placeholder="Search by audit no" /></td>'
                . '<td></td>'
                . '<td><b>Venue : </b></td>'
                . '<td><input type="text" name="search_title" id="search_title" class="search" value="' . $search_title . '" placeholder="Search by Venue" /></td>'
                . '</tr>';
    } else if ($activity_type_id == 8) {
        $searchdata .= '<tr><td><b>Activity Month :</b></td>'
                . '<td><select name="activity_month" id="activity_month" class="search" >'
                . '<option value="">Select Month</option>';
        for ($m = 1; $m <= 12; $m++) {
            $month = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
            $searchdata .= '<option value="' . $m . '" ' . (($activity_month == $m) ? "selected" : "") . '>' . $month . '</option>';
        }
        $searchdata .= '</select></td>'
                . '<td></td>'
                . '<td><b>Activity Year : </b></td>'
                . '<td><select name="activity_year" id="activity_year" class="search" >'
                . '<option value="">Select Year</option>';
        $asd = '2015';
        for ($m = 0; $m <= 10; $m++) {
            $yrs = $asd + $m;
            $searchdata .= '<option value="' . $yrs . '" ' . (($activity_year == $yrs) ? "selected" : "") . '>' . ($yrs) . '</option>';
        }
        $searchdata .= '</select></td>'
                . '</tr>'
                . '<tr><td><b>Activity No. :</b></td>'
                . '<td><input type="text" name="activity_no" id="activity_no" class="search" value="' . $activity_no . '" placeholder="Search by activity no" /></td>'
                . '<td></td>'
                . '<td><b>Venue : </b></td>'
                . '<td><input type="text" name="search_title" id="search_title" class="search" value="' . $search_title . '" placeholder="Search by Venue" /></td>'
                . '</tr>';
    } else {
        $searchdata = '<tr>'
                . '<td><b>From Date :</b></td>'
                . '<td><input type="text" autocomplete="off" name="fromdate" id="fromdate" class="search datetimepicker" value="' . $fromdate . '" placeholder="From date"  /></td>'
                . '<td width="10%"></td><td><b>To Date :</b></td>'
                . '<td><input type="text" autocomplete="off" name="todate" id="todate" class="search datetimepicker" value="' . $todate . '" placeholder="To date"  /></td></tr>'
                . '<tr><td><b>Activity No. :</b></td>'
                . '<td><input type="text" name="activity_no" id="activity_no" class="search" value="' . $activity_no . '" placeholder="Search by activity no" /></td>'
                . '<td></td>'
                . '<td><b>Venue : </b></td>'
                . '<td><input type="text" name="search_title" id="search_title" class="search" value="' . $search_title . '" placeholder="Search by Venue" /></td>'
                . '</tr>';
    }
    ?>

    <div class="holder">
        <form name="searchuser" id="searchuser" method="get">
            <table id="search-table" class="table table-condensed" style="margin-bottom:0px;">
                <input type="hidden" name="page" value="<?php echo ($page) ? $page : 1; ?>" />
                <input type="hidden" name="activity_id" value="<?php echo $activity_type_id; ?>" />
                <?php echo $searchdata; ?>
                <tr>
                    <td><b>Status :</b></td>
                    <td>
                        <select name="status_id" id="status_id">
                            <option value="">Search By Status</option>
                            <?php
                            $created_by = $beanUi->get_view_data("created_by");
                            if (!empty($post_status)) {
                                if ($role_id == 1) {
                                    $admin_status = array("Draft", "Final Submit", "Approve & Publish", "Approve & Unpublish");
                                } else if ($role_id == 3) {
                                    $admin_status = array("Draft", "Final Submit", "Approve & Publish", "Approve & Unpublish");
                                } else if ($role_id == 2) {
                                    $admin_status = array("Draft", "Final Submit", "Approve & Publish", "Approve & Unpublish");
                                }
                                $status_id = $beanUi->get_view_data("status_id");
                                foreach ($post_status as $statusrow) {
                                    if (in_array($statusrow->status_name, $admin_status)) {
                                        $stsname = ($statusrow->id == 3 || $statusrow->id == 4) ? $statusrow->detail : $statusrow->status_name;
                                        echo '<option value="' . $statusrow->id . '" ' . ($status_id == $statusrow->id ? "selected" : "") . '>' . $stsname . '</option>' . "\n";
                                    }
                                }
                            }
                            ?>
                        </select>                    
                    </td>
                    <td></td>
                    <td>
                        <b>District :</b></td>
                    <td>
<?php
//show($allDistrict);
?>
                        <select name="districtid" id="districtid" >
                            <option value="">Search By District</option>
                        <?php
                        foreach ($allDistrict as $rowdata) {
                            if ($rowdata->flag == 'G') {
                                $val = "~250-251";
                            } else if ($rowdata->flag == 'D') {
                                $val = "~250-252";
                            } else {
                                $val = "";
                            }
                            echo '<option value="' . $rowdata->custom_id . $val . '" ' . (( $districtid == $rowdata->custom_id . $val ) ? "selected" : "") . '>' . $rowdata->name . '</option>';
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" align="center">
                        <input type="submit" value="Go" class="btn btn-sm btn-primary" />
                        <a href="<?php echo page_link("activity/index.php?activity_id=$activity_type_id"); ?>" id="set_src_data" class="btn btn-sm btn-danger" >Reset</a>
                    </td>
                </tr>
            </table>
        </form>
        <hr style="padding:2px;" />
    </div>


    <div class="message"></div>
    <table id="postlist" class="table table-striped table-bordered table-condensed table-responsive responsive-utilities jambo_table bulk_action">
<?php
if ($activity_type_id == 10) {
    ?>
            <thead>
                <tr>
                    <th style="text-align:left;">Title</th>
                    <th>Date of Meeting</th>
                    <th>Time of Meeting</th>
                    <th>Action</th>
                </tr>
            </thead>
    <?php
    if (!empty($allminutesofmeetingfunction)) {
        echo '<tbody>';
        $class = 'even';
        foreach (@$allminutesofmeetingfunction as $row) {
            if (@$row->category_id && !@$row->active_category)
                continue;
            $class = ($class == 'even') ? 'odd' : 'even';
            ?>
                    <tr class="<?php echo $class; ?>" role="row">
                        <td align="left">
                            <div class="holder">
                                <i class="smallfont"><?php echo $row->mom_title; ?></i><br>
                                <i class="smallfont">Created by : <?php echo $row->created_by_name; ?>, 

                    <?php echo ($row->created_date != '0000-00-00 00:00:00' ? date("d-m-Y h:i A", strtotime($row->created_date)) : ""); ?></i>
                            </div>
                        </td>
                        <td align="center">
            <?php
            echo ($row->date_of_meeting != '0000-00-00' ? date("d-m-Y", strtotime($row->date_of_meeting)) : "");
            ?>

                        <td align="center">
            <?php echo ($row->time_of_meeting != '00:00:00' ? date("h:i A", strtotime($row->time_of_meeting)) : ""); ?>
                        </td>

                        <td align="center" width="20%">
                            <a class="btn btn-primary btn-xs" href="view_minutes_of_meeting.php?page=<?php echo $_REQUEST["page"]; ?>&actype_id=<?php echo $row->activity_type_id ?>&id=<?php echo $row->id ?>"><i class="fa fa-search"></i> View</a> 
                            <?php
                            if ($role_id == 2 && ($row->status_id == 1)) {
                                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                    echo '<a class="btn btn-success btn-xs"  href="edit_minutes_of_meeting.php?page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                }
                            }
                            if ($role_id == 1 || $role_id == 3) {
                                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                    echo '<a class="btn btn-success btn-xs"  href="edit_minutes_of_meeting.php?page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                } else if (($row->created_by != $loginuserid) && ($row->status_id != 1)) {
                                    echo '<a class="btn btn-success btn-xs"  href="edit_minutes_of_meeting.php?page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                } else if (($row->created_by == $loginuserid) && ($row->status_id == 2 || $row->status_id == 3 || $row->status_id == 4)) {
                                    echo '<a class="btn btn-success btn-xs"  href="edit_minutes_of_meeting.php?page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                }
                            }

                            if ($row->status_id == 1) {
                                if (($row->created_by == $loginuserid || $role_id == 1 || $role_id == 3) && ($row->status_id == 1)) {
                                    echo '  <a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("minutes_of_meeting") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&page=' . $page . '" onclick="return confirm(\'Confirm delete.\');"> <i class="fa fa-trash"></i>  Delete</a>';
                                }
                            }
                            ?>
                        </td>
                    </tr>
                        <?php } ?>
                </tbody>
                    <?php } else { ?>
                <tr><td colspan="7" class="error">Records not available.</td></tr>

                        <?php
                    }
                } else if ($activity_type_id == 9) {
                    ?>
            <thead>
                <tr>
                    <th style="text-align:left;">Activity No</th>
                    <th>Date of Observation</th>
                    <th>Persons Present</th>
                    <th>No. of Deviation</th>
                    <th>Venue</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php
            if (!empty($allsafetyobservationlinefunction)) {
                echo '<tbody>';
                $class = 'even';
                foreach (@$allsafetyobservationlinefunction as $row) {
                    if (@$row->category_id && !@$row->active_category)
                        continue;
                    $class = ($class == 'even') ? 'odd' : 'even';
                    ?>
                    <tr class="<?php echo $class; ?>" role="row">
                        <td align="left"><?php echo $row->activity_no; ?>
                            <div class="holder">
                                <i class="smallfont">Created by : <?php echo $row->created_by_name; ?>, 
                    <?php echo ($row->created_date != '0000-00-00 00:00:00' ? date("d-m-Y h:i A", strtotime($row->created_date)) : ""); ?></i>
                            </div>
                        </td>
                        <td align="center"><?php echo date('Y-m-d', strtotime($row->date_of_observation)); ?></td>
                        <td align="center"><?php echo $row->persons_present_during_observation; ?></td>
                        <td align="center"><?php echo $row->no_of_deviation; ?></td>
                        <td align="center"><?php echo $row->venue; ?></td>
                        <td align="center">
            <?php
            if ($row->status_name == 'Approve & Publish') {
                echo '<span class="text-success font-bold">' . $row->status_public_name . '</span>';
            }
            if ($row->status_name == 'Final Submit') {
                echo '<span class="text-danger font-bold">' . $row->status_name . '</span>';
            }
            if ($row->status_name == 'Approve & Unpublish') {
                echo '<span class="text-warning font-bold">' . $row->status_public_name . '</span>';
            }
            if ($row->status_name == 'Draft') {
                echo '<span class="text-primary font-bold ">' . $row->status_name . '</span>';
            }
            ?>
                        </td>
                        <td align="center" width="20%">
                            <a class="btn btn-primary btn-xs" href="view_safety_ob_line.php?page=<?php echo $_REQUEST["page"]; ?>&actype_id=<?php echo $row->activity_type_id ?>&id=<?php echo $row->id ?>"><i class="fa fa-search"></i> View</a> 
                            <?php
                            if ($role_id == 2 && ($row->status_id == 1)) {

                                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                    echo '<a class="btn btn-success btn-xs"  href="edit_safety_observation_line_function.php?page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                }
                            }
                            if ($role_id == 1 || $role_id == 3) {
                                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                    echo '<a class="btn btn-success btn-xs"  href="edit_safety_observation_line_function.php?page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                } else if (($row->created_by != $loginuserid) && ($row->status_id != 1)) {
                                    echo '<a class="btn btn-success btn-xs"  href="edit_safety_observation_line_function.php?page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                } else if (($row->created_by == $loginuserid) && ($row->status_id == 2 || $row->status_id == 3 || $row->status_id == 4)) {
                                    echo '<a class="btn btn-success btn-xs"  href="edit_safety_observation_line_function.php?page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                }
                            }

                            if ($row->status_id == 1) {
                                if (($row->created_by == $loginuserid || $role_id == 1 || $role_id == 3) && ($row->status_id == 1)) {

                                    echo '  <a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("safety_observation_line_function") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&page=' . $page . '" onclick="return confirm(\'Confirm delete.\');"> <i class="fa fa-trash"></i>  Delete</a>';
                                }
                            }
                            ?>
                        </td>
                    </tr>
                        <?php } ?>
                </tbody>
                    <?php } else { ?>
                <tr><td colspan="7" class="error">Records not available.</td></tr>

                        <?php
                    }
                } else if ($activity_type_id == 8) {
                    ?>
            <thead>
                <tr>
                    <th style="text-align:left;">Division Department</th>
                    <th>Activity Month</th>
                    <th>Activity Year</th>
                    <th>Activity Count</th>
                    <th>Venue</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php
            if (!empty($allsafetyobservation)) {
                echo '<tbody>';
                $class = 'even';
                foreach (@$allsafetyobservation as $row) {
                    if (@$row->category_id && !@$row->active_category)
                        continue;
                    $class = ($class == 'even') ? 'odd' : 'even';
                    ?>
                    <tr class="<?php echo $class; ?>" role="row">

                        <td align="left"><?php echo '<b class="text-danger"><i>' . $row->tree_name . '</i></b>'; ?>
                            <div class="holder">
                                <i class="smallfont">Created by : <?php echo $row->created_by_name; ?>, 
            <?php echo ($row->created_date != '0000-00-00 00:00:00' ? date("d-m-Y h:i A", strtotime($row->created_date)) : ""); ?>
                                </i>
                            </div>
                        </td>
                        <td align="center"><?php echo date('F', mktime(0, 0, 0, $row->activity_month, 10)); ?></td>
                        <td align="center"><?php echo $row->activity_year; ?></td>
                        <td align="center"><?php echo $row->activity_count; ?></td>
                        <td align="center"><?php echo $row->place; ?></td>
                        <td align="center">
                    <?php
                    if ($row->status_name == 'Approve & Publish') {
                        echo '<span class="text-success font-bold">' . $row->status_public_name . '</span>';
                    }
                    if ($row->status_name == 'Final Submit') {
                        echo '<span class="text-danger font-bold">' . $row->status_name . '</span>';
                    }
                    if ($row->status_name == 'Approve & Unpublish') {
                        echo '<span class="text-warning font-bold">' . $row->status_public_name . '</span>';
                    }
                    if ($row->status_name == 'Draft') {
                        echo '<span class="text-primary font-bold">' . $row->status_name . '</span>';
                    }
                    ?>
                        </td>
                        <td align="center" width="20%">

                            <?php
//$row->date_of_incident
//echo $row->activity_year.$row->activity_month;
                            $actdate = $row->activity_year . '-' . $row->activity_month . '-' . '01';
                            $delflag = 0;
                            $editflag = 0;
                            $lastday = date('Y-m-t', strtotime('previous month'));

                            $onemonthbackdate = date('Y-m-01', strtotime('previous month'));



                            if ($role_id == 2) {
                                if ($currentDate < CHECK_DATE && ($actdate >= $onemonthbackdate)) {
                                    $delflag = 1;
                                    $editflag = 1;
                                } else {
                                    if ($actdate > $lastday) {
                                        $delflag = 1;
                                        $editflag = 1;
                                    }
                                }
                            } else {
                                $delflag = 1;
                                $editflag = 1;
                            }
                            echo '<a class="btn btn-primary btn-xs" href="view_safety_ob.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&search_title=' . $_REQUEST["search_title"] . '&activity_month=' . $_REQUEST["activity_month"] . '&activity_year=' . $_REQUEST["activity_year"] . '&activity_no=' . $_REQUEST["activity_no"] . '&page=' . $_REQUEST["page"] . '&actype_id=' . $row->activity_type_id . '&id=' . $row->id . '"><i class="fa fa-search"></i> View</a> ';
                            if ($role_id == 2 && ($row->status_id == 1)) {

                                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {

                                    if ($editflag == 1) {
                                        echo '<a class="btn btn-success btn-xs"  href="edit_safety_observation.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&search_title=' . $_REQUEST["search_title"] . '&activity_month=' . $_REQUEST["activity_month"] . '&activity_year=' . $_REQUEST["activity_year"] . '&activity_no=' . $_REQUEST["activity_no"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                    }
                                }
                            }
                            if ($role_id == 1 || $role_id == 3) {
                                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                    echo '<a class="btn btn-success btn-xs"  href="edit_safety_observation.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&search_title=' . $_REQUEST["search_title"] . '&activity_month=' . $_REQUEST["activity_month"] . '&activity_year=' . $_REQUEST["activity_year"] . '&activity_no=' . $_REQUEST["activity_no"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                } else if (($row->created_by != $loginuserid) && ($row->status_id != 1)) {
                                    echo '<a class="btn btn-success btn-xs"  href="edit_safety_observation.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&search_title=' . $_REQUEST["search_title"] . '&activity_month=' . $_REQUEST["activity_month"] . '&activity_year=' . $_REQUEST["activity_year"] . '&activity_no=' . $_REQUEST["activity_no"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                } else if (($row->created_by == $loginuserid) && ($row->status_id == 2 || $row->status_id == 3 || $row->status_id == 4)) {
                                    echo '<a class="btn btn-success btn-xs"  href="edit_safety_observation.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&search_title=' . $_REQUEST["search_title"] . '&activity_month=' . $_REQUEST["activity_month"] . '&activity_year=' . $_REQUEST["activity_year"] . '&activity_no=' . $_REQUEST["activity_no"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                }
                            }
                            if ($row->status_id == 1) {
                                if (($row->created_by == $loginuserid || $role_id == 1 || $role_id == 3) && ($row->status_id == 1)) {
                                    if ($delflag == 1) {
                                        echo '  <a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("safety_observation") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&search_title=' . $_REQUEST["search_title"] . '&activity_month=' . $_REQUEST["activity_month"] . '&activity_no=' . $_REQUEST["activity_no"] . '&page=' . $_REQUEST["page"] . '" onclick="return confirm(\'Confirm delete.\');"> <i class="fa fa-trash"></i>  Delete</a>';
                                    }
                                }
                            }
                            if ($row->status_id == 2 || $row->status_id == 3) {
                                if (($role_id == 3 ) && ($row->status_id == 2 || $row->status_id == 3)) {
                                    if ($delflag == 1) {
                                        echo '  <a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("safety_observation") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&search_title=' . $_REQUEST["search_title"] . '&activity_month=' . $_REQUEST["activity_month"] . '&activity_no=' . $_REQUEST["activity_no"] . '&page=' . $_REQUEST["page"] . '" onclick="return confirm(\'Confirm delete.\');"> <i class="fa fa-trash"></i>  Delete</a>';
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                        <?php } ?>
                </tbody>
                    <?php } else { ?>
                <tr><td colspan="7" class="error">Records not available.</td></tr>

                        <?php
                    }
                } else if ($activity_type_id == 7) {
                    ?>

            <thead>
                <tr>

                    <th>Division Department</th>
                    <th>Date of Audit</th>
                    <th>Time of Audit</th>
                    <th>Venue</th>
                    <th>% of Marks</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
    <?php
    if (!empty($allppeaudit)) {

        echo '<tbody>';
        $class = 'even';
        foreach (@$allppeaudit as $row) {
            if (@$row->category_id && !@$row->active_category)
                continue;
            $class = ($class == 'even') ? 'odd' : 'even';
            ?>
                    <tr class="<?php echo $class; ?>" role="row">

                        <td align="left">
            <?php echo '<b class="text-danger"><i>' . $row->tree_name . '</i></b>'; ?>
                            <div class="holder">
                                <i class="smallfont">Created by : <?php echo $row->created_by_name; ?>, 
            <?php echo ($row->created_date != '0000-00-00 00:00:00' ? date("d-m-Y h:i A", strtotime($row->created_date)) : ""); ?>
                                </i>
                            </div>
                        </td>

                        <td align="center">
                    <?php
                    echo ($row->date_of_audit != '0000-00-00' ? date("d-m-Y", strtotime($row->date_of_audit)) : "");
                    ?>
                        </td>
                        <td align="center">
                    <?php
                    echo ($row->time_of_audit_from != '00:00:00' ? date("h:i A", strtotime($row->time_of_audit_from)) . ' - ' . date("h:i A", strtotime($row->time_of_audit_to)) : "");
                    ?>
                        </td>
                        <td align="center"><?php echo $row->place; ?></td>
                        <td align="center"><?php echo $row->avg_mark; ?></td>
                        <td align="center">
            <?php
            if ($row->status_name == 'Approve & Publish') {
                echo '<span class="text-success font-bold">' . $row->status_public_name . '</span>';
            }
            if ($row->status_name == 'Final Submit') {
                echo '<span class="text-danger font-bold">' . $row->status_name . '</span>';
            }
            if ($row->status_name == 'Approve & Unpublish') {
                echo '<span class="text-warning font-bold">' . $row->status_public_name . '</span>';
            }
            if ($row->status_name == 'Draft') {
                echo '<span class="text-primary font-bold">' . $row->status_name . '</span>';
            }
            ?>
                        </td>
                        <td align="center" width="20%">

            <?php
            $delflag = 0;
            $editflag = 0;
            $lastday = date('Y-m-t', strtotime('previous month'));
            $onemonthbackdate = date('Y-m-01', strtotime('previous month'));
            $checkDatePpe = date('Y-m-16', strtotime($row->date_of_audit." +1 month"));

            if ($role_id == 2) {
//                if ($currentDate < CHECK_DATE && ($row->date_of_audit >= $onemonthbackdate)) {
                if ($currentDate < $checkDatePpe && ($row->date_of_audit >= $onemonthbackdate)) {
                    $delflag = 1;
                    $editflag = 1;
                } else {
                    if ($row->date_of_audit > $lastday) {
                        $delflag = 1;
                        $editflag = 1;
                    }
                }
            } else {
                $delflag = 1;
                $editflag = 1;
            }
            echo '<a class="btn btn-primary btn-xs" href="view_ppe_audit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&actype_id=' . $row->activity_type_id . '&id=' . $row->id . '"><i class="fa fa-search"></i> View</a> ';
            $TreeIndex = $row->tree_division_id;
            $TreeIndexExp = explode("-",$TreeIndex);
//            show($TreeIndexExp[2]);die;
            if($TreeIndexExp[2] != 5){
            if ($role_id == 2 && ($row->status_id == 1)) {
                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                    if ($editflag == 1) {
                        echo '<a class="btn btn-success btn-xs"  href="edit_ppe_audit_nm.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                    }
                }
            }
            if ($role_id == 1 || $role_id == 3) {
                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                    echo '<a class="btn btn-success btn-xs"  href="edit_ppe_audit_nm.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                } else if (($row->created_by != $loginuserid) && ($row->status_id != 1)) {
                    echo '<a class="btn btn-success btn-xs"  href="edit_ppe_audit_nm.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                } else if (($row->created_by == $loginuserid) && ($row->status_id == 2 || $row->status_id == 3 || $row->status_id == 4)) {
                    echo '<a class="btn btn-success btn-xs"  href="edit_ppe_audit_nm.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                }
            }
//            if ($row->status_id == 1) {
//                if (($row->created_by == $loginuserid || $role_id == 1 || $role_id == 3) && ($row->status_id == 1)) {
//                    if ($delflag == 1) {
//                        echo '  <a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("ppe_audit") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '" onclick="return confirm(\'Confirm delete.\');"> <i class="fa fa-trash"></i>  Delete</a>';
//                    }
//                }
//            }
        }else{
            if ($role_id == 2 && ($row->status_id == 1)) {
                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                    if ($editflag == 1) {
                        echo '<a class="btn btn-success btn-xs"  href="edit_ppe_audit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                    }
                }
            }
            if ($role_id == 1 || $role_id == 3) {
                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                    echo '<a class="btn btn-success btn-xs"  href="edit_ppe_audit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                } else if (($row->created_by != $loginuserid) && ($row->status_id != 1)) {
                    echo '<a class="btn btn-success btn-xs"  href="edit_ppe_audit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                } else if (($row->created_by == $loginuserid) && ($row->status_id == 2 || $row->status_id == 3 || $row->status_id == 4)) {
                    echo '<a class="btn btn-success btn-xs"  href="edit_ppe_audit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                }
            }
        }
        if ($row->status_id == 1) {
                if (($row->created_by == $loginuserid || $role_id == 1 || $role_id == 3) && ($row->status_id == 1)) {
                    if ($delflag == 1) {
                        echo '  <a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("ppe_audit") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '" onclick="return confirm(\'Confirm delete.\');"> <i class="fa fa-trash"></i>  Delete</a>';
                    }
                }
            }
        if ($row->status_id == 2 || $row->status_id == 3) {
                if (($role_id == 3 ) && ($row->status_id == 2 || $row->status_id == 3)) {
                    if ($delflag == 1) {
                        echo '  <a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("ppe_audit") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '" onclick="return confirm(\'Confirm delete.\');"> <i class="fa fa-trash"></i>  Delete</a>';
                    }
                }
            }
            ?>
                        </td>
                    </tr>
                        <?php } ?>
                </tbody>
                    <?php } else { ?>
                <tr><td colspan="6" class="error">Records not available.</td></tr>

                        <?php
                    }
                } else if ($activity_type_id == 5) {
                    ?>

            <thead>
                <tr>

                    <th>Division Department</th>
                    <th>Date of Audit</th>
                    <th>Time of Audit</th>
                    <th>Venue</th>
                    <th>% of Marks</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php
            if (!empty($allaudit)) {

                echo '<tbody>';
                $class = 'even';
                foreach (@$allaudit as $row) {
                    if (@$row->category_id && !@$row->active_category)
                        continue;
                    $class = ($class == 'even') ? 'odd' : 'even';
                    ?>
                    <tr class="<?php echo $class; ?>" role="row">

                        <td align="left">
            <?php echo '<b class="text-danger"><i>' . $row->tree_name . '</i></b>'; ?>
                            <div class="holder">
                                <i class="smallfont">Created by : <?php echo $row->created_by_name; ?>, 
            <?php echo ($row->created_date != '0000-00-00 00:00:00' ? date("d-m-Y h:i A", strtotime($row->created_date)) : ""); ?>
                                </i>
                            </div>
                        </td>

                        <td align="center">

                    <?php
                    echo ($row->date_of_audit != '0000-00-00' ? date("d-m-Y", strtotime($row->date_of_audit)) : "");
                    ?>
                        </td>
                        <td align="center">
                    <?php
                    echo ($row->time_of_audit_from != '00:00:00' ? date("h:i A", strtotime($row->time_of_audit_from)) . ' - ' . date("h:i A", strtotime($row->time_of_audit_to)) : "");
                    ?>

                        </td>
                        <td align="center">
            <?php echo $row->place; ?>
                        </td>
                        <td align="center">
            <?php echo $row->avg_mark; ?>
                        </td>
                        <td align="center">
            <?php
            if ($row->status_name == 'Approve & Publish') {
                echo '<span class="text-success font-bold">' . $row->status_public_name . '</span>';
            }
            if ($row->status_name == 'Final Submit') {
                echo '<span class="text-danger font-bold">' . $row->status_name . '</span>';
            }
            if ($row->status_name == 'Approve & Unpublish') {
                echo '<span class="text-warning font-bold">' . $row->status_public_name . '</span>';
            }
            if ($row->status_name == 'Draft') {
                echo '<span class="text-primary font-bold">' . $row->status_name . '</span>';
            }
            ?>
                        </td>
                        <td align="center" width="20%">			
                            <?php
                            $delflag = 0;
                            $editflag = 0;
                            $lastday = date('Y-m-t', strtotime('previous month'));
                            $onemonthbackdate = date('Y-m-01', strtotime('previous month'));
                            $checkDateSa = date('Y-m-16', strtotime($row->date_of_audit." +1 month"));
                            if ($role_id == 2) {
//                                if ($currentDate < CHECK_DATE && ($row->date_of_audit >= $onemonthbackdate)) {
                                if ($currentDate < $checkDateSa && ($row->date_of_audit >= $onemonthbackdate)) {
                                    $delflag = 1;
                                    $editflag = 1;
                                } else {
                                    if ($row->date_of_audit > $lastday) {
                                        $delflag = 1;
                                        $editflag = 1;
                                    }
                                }
                            } else {
                                $delflag = 1;
                                $editflag = 1;
                            }
                            echo '<a class="btn btn-primary btn-xs" href="view_audit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&actype_id=' . $row->activity_type_id . '&id=' . $row->id . '"><i class="fa fa-search"></i> View</a> ';
                            $TreeIndex = $row->tree_division_id;
                            $TreeIndexExp = explode("-",$TreeIndex);
//                            show($TreeIndexExp[2]);die;
                            if($TreeIndexExp[2] != 5){
//                                 ******************mbvp pk start**********************
//                                if ($role_id == 2 && ($row->status_id == 1)) {
//                                    if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
//                                        if ($editflag == 1) {
//                                            echo '<a class="btn btn-success btn-xs"  href="edit_audit_nm.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activty_n0"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
//                                        }
//                                    }
//                                }
//                                if ($role_id == 1 || $role_id == 3) {
//                                    if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
//                                        echo '<a class="btn btn-success btn-xs"  href="edit_audit_nm.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
//                                    } else if (($row->created_by != $loginuserid) && ($row->status_id != 1)) {
//                                        echo '<a class="btn btn-success btn-xs"  href="edit_audit_nm.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
//                                    } else if (($row->created_by == $loginuserid) && ($row->status_id == 2 || $row->status_id == 3 || $row->status_id == 4)) {
//                                        echo '<a class="btn btn-success btn-xs"  href="edit_audit_nm.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
//                                    }
//                                }
//                                ***************************########****************************
                                if ($role_id == 2 && ($row->status_id == 1)) {
                                    if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                        if ($editflag == 1) {
                                            echo '<a class="btn btn-success btn-xs"  href="'.page_link('activitynew/add_audit_new.php').'?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activty_n0"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&auditId=' . $row->id .'&activity_id=' . $row->activity_type_id. '"><i class="fa fa-pencil"></i> Edit</a>';
                                        }
                                    }
                                }
                                if ($role_id == 1 || $role_id == 3) {
                                    if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                        echo '<a class="btn btn-success btn-xs"  href="'.page_link('activitynew/add_audit_new.php').'?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&auditId=' . $row->id .'&activity_id=' . $row->activity_type_id. '"><i class="fa fa-pencil"></i> Edit</a>';
                                    } else if (($row->created_by != $loginuserid) && ($row->status_id != 1)) {
                                        echo '<a class="btn btn-success btn-xs"  href="'.page_link('activitynew/add_audit_new.php').'?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&auditId=' . $row->id .'&activity_id=' . $row->activity_type_id. '"><i class="fa fa-pencil"></i> Edit</a>';
                                    } else if (($row->created_by == $loginuserid) && ($row->status_id == 2 || $row->status_id == 3 || $row->status_id == 4)) {
                                        echo '<a class="btn btn-success btn-xs"  href="'.page_link('activitynew/add_audit_new.php').'?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&auditId=' . $row->id .'&activity_id=' . $row->activity_type_id. '"><i class="fa fa-pencil"></i> Edit</a>';
                                    }
                                }
                                if($row->is_oth_dept_audit == 1){
                                    if($row->status_id == 2 || $row->status_id == 3){
                                        echo '<a class="btn btn-maroon btn-xs" target="_blank" href="'. page_link('activitynew/add_audit_new_view_details_report.php?audit_id=' . $row->id . '').'"><i class="fa fa-search"></i> Full Report</a> ';
                                    }
                                }
//                                ******************mbvp pk end************************    
                            }else{
                                if ($role_id == 2 && ($row->status_id == 1)) {
                                    if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                        if ($editflag == 1) {
                                            echo '<a class="btn btn-success btn-xs"  href="edit_audit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activty_n0"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                        }
                                    }
                                }
                                if ($role_id == 1 || $role_id == 3) {
                                    if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                        echo '<a class="btn btn-success btn-xs"  href="edit_audit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                    } else if (($row->created_by != $loginuserid) && ($row->status_id != 1)) {
                                        echo '<a class="btn btn-success btn-xs"  href="edit_audit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                    } else if (($row->created_by == $loginuserid) && ($row->status_id == 2 || $row->status_id == 3 || $row->status_id == 4)) {
                                        echo '<a class="btn btn-success btn-xs"  href="edit_audit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                    }
                                }
                            }
                            if ($row->status_id == 1) {
                                if (($row->created_by == $loginuserid || $role_id == 1 || $role_id == 3) && ($row->status_id == 1)) {
                                    if ($delflag == 1) {
                                        echo '  <a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("audit") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '" onclick="return confirm(\'Confirm delete.\');"> <i class="fa fa-trash"></i>  Delete</a>';
                                    }
                                }
                            }
                            if ($row->status_id == 2 || $row->status_id == 3) {
                                if (($role_id == 3 ) && ($row->status_id == 2 || $row->status_id == 3)) {
                                    if ($delflag == 1) {
                                        echo '  <a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("audit") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '" onclick="return confirm(\'Confirm delete.\');"> <i class="fa fa-trash"></i>  Delete</a>';
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                        <?php } ?>
                </tbody>
                    <?php } else { ?>
                <tr><td colspan="7" class="error">Records not available.</td></tr>

                        <?php
                    }
                } else if ($activity_type_id == 6) {
                    ?>

            <thead>
                <tr>

                    <th>Division Department</th>
                    <th>Date of Incident</th>
                    <th>Time of Incident</th>
                    <th>Incident Category</th>
                    <th>Venue</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
                    <?php
                    if (!empty($allincident)) {

                        echo '<tbody>';
                        $class = 'even';
                        foreach (@$allincident as $row) {
                            if (@$row->category_id && !@$row->active_category)
                                continue;
                            $class = ($class == 'even') ? 'odd' : 'even';
                            $inc_cat_name = get_value_by_id($row->incident_category_id, "name", $incident_category);
                            ?>
                    <tr class="<?php echo $class; ?>" role="row">

                        <td align="left">
            <?php
            echo '<b class="text-danger"><i>' . $row->tree_name . '</i></b>';
            ?>
                            <div class="holder">
                                <i class="smallfont">Created by : <?php echo $row->created_by_name; ?>, 
            <?php echo ($row->created_date != '0000-00-00 00:00:00' ? date("d-m-Y h:i A", strtotime($row->created_date)) : ""); ?>
                                </i>
                            </div>
                        </td>
                        <td align="center">
            <?php echo ($row->date_of_incident != '0000-00-00' ? date("d-m-Y", strtotime($row->date_of_incident)) : ""); ?>
                        </td>
                        <td align="center">
                    <?php echo ($row->time_of_incident != '' ? date("h:i A", strtotime($row->time_of_incident . ":00")) : ""); ?>
                        </td>
                        <td align="center">
                    <?php echo $inc_cat_name; ?>
                        </td>
                        <td align="center">
                    <?php echo $row->place; ?>
                        </td>
                        <td align="center">
                    <?php
                    if ($row->status_name == 'Approve & Publish') {
                        echo '<span class="text-success font-bold">' . $row->status_public_name . '</span>';
                    }
                    if ($row->status_name == 'Final Submit') {
                        echo '<span class="text-danger font-bold">' . $row->status_name . '</span>';
                    }
                    if ($row->status_name == 'Approve & Unpublish') {
                        echo '<span class="text-warning font-bold">' . $row->status_public_name . '</span>';
                    }
                    if ($row->status_name == 'Draft') {
                        echo '<span class="text-primary font-bold">' . $row->status_name . '</span>';
                    }
                    ?>
                        </td>
                        <td align="center" width="20%">

                            <?php
                            $delflag = 0;
                            $editflag = 0;
                            $lastday = date('Y-m-t', strtotime('previous month'));
                            $onemonthbackdate = date('Y-m-01', strtotime('previous month'));
//                            Modified by pk for CHECK_DATE issue,cis uplode required 
                            $checkDateInc = date('Y-m-16', strtotime($row->date_of_incident." +1 month"));
                            if ($role_id == 2) {
//                                if ($currentDate < CHECK_DATE && ($row->date_of_incident >= $onemonthbackdate)) {
                                if ($currentDate < $checkDateInc && ($row->date_of_incident >= $onemonthbackdate)) {

                                    $delflag = 1;
                                    $editflag = 1;
                                } else {
                                    if ($row->date_of_incident > $lastday) {
                                        $delflag = 1;
                                        $editflag = 1;
                                    }
                                }
                            } else {
                                $delflag = 1;
                                $editflag = 1;
                            }
                            echo '<a class="btn btn-primary btn-xs" href="view_incident.php?status_id=' . $_REQUEST["status_id"] . '&search_title=' . $_REQUEST["search_title"] . '&districtid=' . $_REQUEST["districtid"] . '&incident_category_ids=' . $_REQUEST["incident_category_ids"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&actype_id=' . $row->activity_type_id . '&id=' . $row->id . '"><i class="fa fa-search"></i> View</a> ';
                            if ($role_id == 2 && ($row->status_id == 1)) {
                                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                    if ($editflag == 1) {
                                        echo '<a class="btn btn-success btn-xs" href="edit_incident.php?status_id=' . $_REQUEST["status_id"] . '&search_title=' . $_REQUEST["search_title"] . '&districtid=' . $_REQUEST["districtid"] . '&incident_category_ids=' . $_REQUEST["incident_category_ids"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                    }
                                }
                            }
                            if ($role_id == 1 || $role_id == 3) {
                                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                    echo '<a class="btn btn-success btn-xs" href="edit_incident.php?status_id=' . $_REQUEST["status_id"] . '&search_title=' . $_REQUEST["search_title"] . '&districtid=' . $_REQUEST["districtid"] . '&incident_category_ids=' . $_REQUEST["incident_category_ids"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                } else if (($row->created_by != $loginuserid) && ($row->status_id != 1)) {
                                    echo '<a class="btn btn-success btn-xs" href="edit_incident.php?status_id=' . $_REQUEST["status_id"] . '&search_title=' . $_REQUEST["search_title"] . '&districtid=' . $_REQUEST["districtid"] . '&incident_category_ids=' . $_REQUEST["incident_category_ids"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                } else if (($row->created_by == $loginuserid) && ($row->status_id == 2 || $row->status_id == 3 || $row->status_id == 4)) {
                                    echo '<a class="btn btn-success btn-xs" href="edit_incident.php?status_id=' . $_REQUEST["status_id"] . '&search_title=' . $_REQUEST["search_title"] . '&districtid=' . $_REQUEST["districtid"] . '&incident_category_ids=' . $_REQUEST["incident_category_ids"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                }
                            }
                            if ($row->status_id == 1) {
                                if (($row->created_by == $loginuserid || $role_id == 1 || $role_id == 3 ) && ($row->status_id == 1)) {

                                    if ($delflag == 1) {
                                        echo '<a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("incident") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&status_id=' . $_REQUEST["status_id"] . '&search_title=' . $_REQUEST["search_title"] . '&districtid=' . $_REQUEST["districtid"] . '&incident_category_ids=' . $_REQUEST["incident_category_ids"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '" onclick="return confirm(\'Confirm delete.\');"><i class="fa fa-trash"></i> Delete</a>';
                                    }
                                }
                            }
                            if ($row->status_id == 2 || $row->status_id == 3) {
                                if (($role_id == 3 ) && ($row->status_id == 2 || $row->status_id == 3)) {

                                    if ($delflag == 1) {
                                        echo '<a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("incident") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&status_id=' . $_REQUEST["status_id"] . '&search_title=' . $_REQUEST["search_title"] . '&districtid=' . $_REQUEST["districtid"] . '&incident_category_ids=' . $_REQUEST["incident_category_ids"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '" onclick="return confirm(\'Confirm delete.\');"><i class="fa fa-trash"></i> Delete</a>';
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                        <?php } ?>
                </tbody>
                    <?php } else { ?>
                <tr><td colspan="7" class="error">Records not available.</td></tr>
                    <?php } ?>

                <?php } else { ?>
            <thead>
                <tr>
                    <th width="35%">Activities</th>
                    <th>Activity No</th>
                    <th>Venue</th>
                    <?php
                    if ($activity_type_id == 3) {
                        echo '<th width="15%">Period of Activity</th>';
                    } else {
                        echo '<th width="15%">Date of Activity</th>';
                    }
                    ?>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
                    <?php
                    if (!empty($allactivities)) {

                        echo '<tbody>';
                        $class = 'even';
                        foreach (@$allactivities as $row) {
                            if (@$row->category_id && !@$row->active_category)
                                continue;
                            $class = ($class == 'even') ? 'odd' : 'even';
                            ?>
                    <tr class="<?php echo $class; ?>" role="row">
                        <td>

                            <div class="holder">
                                <b><?php
            echo page_link("activity/edit.php?id=" . $row->id, $row->subject_details);
            echo '<br><i class="text-danger">' . $row->tree_name . '</i>';
                            ?>
                                </b>
                            </div>
                            <div class="holder">
                                <i class="smallfont">Created by : <?php echo $row->created_by_name; ?>, 
                            <?php echo ($row->created_date != '0000-00-00 00:00:00' ? date("d-m-Y h:i A", strtotime($row->created_date)) : ""); ?>

                                </i>
                            </div>
                        </td>
                        <td align="center">
            <?php echo $row->activity_no; ?>
                        </td>
                        <td align="center">
                    <?php echo $row->place; ?>
                        </td>
                        <td align="center">
                    <?php
                    if ($activity_type_id == 3) {
                        echo ($row->from_date != '0000-00-00' ? date("d-m-Y", strtotime($row->from_date)) . " - " . date("d-m-Y", strtotime($row->to_date)) : "");
                    } else {
                        echo ($row->activity_date != '0000-00-00' ? date("d-m-Y", strtotime($row->activity_date)) : "");
                    }
                    ?>
                        </td>
                        <td align="center">
            <?php
            if ($row->status_name == 'Approve & Publish') {
                echo '<span class="text-success font-bold">' . $row->status_public_name . '</span>';
            }
            if ($row->status_name == 'Final Submit') {
                echo '<span class="text-danger font-bold">' . $row->status_name . '</span>';
            }
            if ($row->status_name == 'Approve & Unpublish') {
                echo '<span class="text-warning font-bold">' . $row->status_public_name . '</span>';
            }
            if ($row->status_name == 'Draft') {
                echo '<span class="text-primary font-bold">' . $row->status_name . '</span>';
            }
            ?>
                        </td>
                        <td align="center" width="20%">

                            <?php
                            $delflag = 0;
                            $editflag = 0;
                            $lastday = date('Y-m-t', strtotime('previous month'));
                            $onemonthbackdate = date('Y-m-01', strtotime('previous month'));
                            $dateactivity = (($activity_type_id == 3) ? $row->from_date : $row->activity_date);
                            if($activity_type_id == 3){
                            $checkDateAct = date('Y-m-16', strtotime($row->from_date." +1 month"));
                            $dateactivity1 = $row->from_date;
                            }else{
                            $checkDateAct = date('Y-m-16', strtotime($row->activity_date." +1 month"));
                            $dateactivity1 = $row->activity_date;
                            }
                            if ($role_id == 2) {
                                if ($currentDate < $checkDateAct && ($dateactivity1 >= $onemonthbackdate)) {
                                //if ($currentDate < CHECK_DATE && ($dateactivity >= $onemonthbackdate)) {
                                    $delflag = 1;
                                    $editflag = 1;
                                } else {

                                    if ($dateactivity > $lastday) {
                                        $delflag = 1;
                                        $editflag = 1;
                                    }
                                }
                            } else {
                                $delflag = 1;
                                $editflag = 1;
                            }
                            echo '<a class="btn btn-primary btn-xs" href="view.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&actype_id=' . $row->activity_type_id . '&id=' . $row->id . '" onclick="return window.history.disable();"><i class="fa fa-search"></i> View</a> ';
                            if ($role_id == 2 && ($row->status_id == 1)) {
                                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                    if ($editflag == 1) {
                                        echo '<a class="btn btn-success btn-xs" href="edit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                    }
                                }
                            }
                            if ($role_id == 1 || $role_id == 3) {
                                if (($row->created_by == $loginuserid) && ($row->status_id == 1)) {
                                    echo '<a class="btn btn-success btn-xs" href="edit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                } else if (($row->created_by != $loginuserid) && ($row->status_id != 1)) {
                                    echo '<a class="btn btn-success btn-xs" href="edit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                } else if (($row->created_by == $loginuserid) && ($row->status_id == 2 || $row->status_id == 3 || $row->status_id == 4)) {
                                    echo '<a class="btn btn-success btn-xs" href="edit.php?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&id=' . $row->id . '"><i class="fa fa-pencil"></i> Edit</a>';
                                }
                            }
                            if ($row->status_id == 1) {
                                if (($row->created_by == $loginuserid || $role_id == 1 || $role_id == 3) && ($row->status_id == 1)) {
                                    if ($delflag == 1) {
                                        echo '  <a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("activity") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '" onclick="return confirm(\'Confirm delete.\');"><i class="fa fa-trash"></i> Delete</a>';
                                    }
                                }
                            }
                            if ($row->status_id == 2 || $row->status_id == 3) {
                                if (($role_id == 3 ) && ($row->status_id == 2 || $row->status_id == 3)) {
                                    if ($delflag == 1) {
                                        echo '  <a class="btn btn-danger btn-xs" href="?action=delete_activity&t=' . base64_encode("activity") . '&id=' . $row->id . '&activity_id=' . $row->activity_type_id . '&status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activity_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '" onclick="return confirm(\'Confirm delete.\');"><i class="fa fa-trash"></i> Delete</a>';
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                        <?php } ?>
                </tbody>
                        <?php } else {
                        ?>
                <tr><td colspan="6" class="error">Records not available.</td></tr>
                    <?php
                    }
                }
                ?>

    </table>
    <hr />
                <?php echo $posts_paggin_html; ?>
</div>
                <?php $controller->get_footer(); ?>
<!-- Datepicker -->
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>
<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
<script>
                    jQuery.datetimepicker.setLocale('en');
                    jQuery('.datetimepicker').datetimepicker({
                        timepicker: false,
                        scrollMonth: false,
                        scrollInput: false,
                        format: 'Y-m-d',
                        step: 5
                    });
                    jQuery('.datetimepicker_for_time').datetimepicker({
                        datepicker: false,
                        scrollMonth: false,
                        scrollInput: false,
                        format: 'H:i',
                        step: 5
                    });

</script>   

<!-- Datepicker -->
</body>
</html>

<script>
//    $(document).ready(function(){
//        $("#set_src_data").click(function(){
//        <?php //unset($_SESSION['act_srch']); ?>
//      });
//    });
</script>


