<?php
/*
 * MIS Report No. : 2
 * Name         :   MCM Report
 * Controller   :   mcmReport()
 * Dao          :   getOfficerTraining,getSupervisorTraining
 * Created By pallab kar
 */
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$mnthyr = ($beanUi->get_view_data("mnthyr"));
$month_year = ($beanUi->get_view_data("month_year")) ? $beanUi->get_view_data("month_year") : $mnthyr;
$majorActivities_TA_SO = ($beanUi->get_view_data("majorActivities_TA_SO"));
$majorActivities_DSA = ($beanUi->get_view_data("majorActivities_DSA"));
$majorActivities_G = ($beanUi->get_view_data("majorActivities_G"));
$majorActivities_HH_SF = ($beanUi->get_view_data("majorActivities_HH_SF"));
$majorActivities_HH_HT = ($beanUi->get_view_data("majorActivities_HH_HT"));
$majorActivities_DG = ($beanUi->get_view_data("majorActivities_DG"));
$majorActivitiesIN = ($beanUi->get_view_data("majorActivitiesIN"));
$majorActivities4 = ($beanUi->get_view_data("majorActivities4"));
$current_financial_year = ($beanUi->get_view_data("current_financial_year"));
$previous_financial_year = ($beanUi->get_view_data("previous_financial_year"));
$selected_month_year = ($beanUi->get_view_data("selected_month_year")) ? $beanUi->get_view_data("selected_month_year") : "";
$site_root_url = dirname(url());
$selected_month = ($selected_month_year == "") ? "" : date("M y", strtotime('01-' . $selected_month_year));
$selectedMY = ($selected_month_year == "") ? "" : date("F Y", strtotime('01-' . $selected_month_year));
$prev_FY = ($previous_financial_year == "") ? "" : $previous_financial_year;
$rowsingledata = ($beanUi->get_view_data("rowsingledata"));
$submitdata = ($beanUi->get_view_data("submitdata") ? $beanUi->get_view_data("submitdata") : "");
$mode = ($beanUi->get_view_data("mode")) ? $beanUi->get_view_data("mode") : "";
$controller->get_header();
if ($prev_FY != "") {
    $prev = explode("-", $prev_FY);
    $prev_FY = $prev[0] . '-' . date("y", strtotime("01-01-" . $prev[1]));
}
if ($current_financial_year != "") {
    $curr = explode("-", $current_financial_year);
    $current_financial_year = $curr[0] . '-' . date("y", strtotime("01-01-" . $curr[1]));
}
?>
<style> 
    .none {display: none;} 
    input[type="text"] {width:100%;}
</style>

<div class="container1">   
    <h1 class="heading">2. MCM Report<?php echo ( $mode == "view" ) ? '<a style="float: right;" href="mcmReportlistNew.php" class="btn btn-danger btn-xs">Back</a>' : ''; ?></h1>    
    <div class="wrapper2">           
        <?php echo $beanUi->get_message(); ?>
        <form action="" method="post" id="mcmform" enctype="multipart/form-data" style="<?php echo ( $mode == "view" ) ? 'display:none' : 'display:block'; ?>" >
            <div class="holder">
                <?php if ($mnthyr == '') { ?>
                    <label for="job_stop_req_raisedx" style="width:15%;padding-top:5px;">Select Month-Year :</label>
                    <input required="true" type="text" name="month_year" id="month_year_from" class="month_year_picker" value ="<?php echo $month_year; ?>" style="width:250px;"  >
                    <input type="hidden" name="_action" value="Create" />
                    <input type="hidden" name="submitdata" value="1" />
                    <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
                    <a href="mcmReportNew.php" class="btn btn-danger btn-sm">Reset</a>
                    <?php
                } else {
                    echo '<label for="job_stop_req_raisedx" style="width:15%;">Select Month-Year :</label>';
                    echo '<span style="padding-top:3px;">' . $month_year . '</span>';
                    echo '<input type="hidden" name="month_year" id="month_year_from" class="month_year_picker" value ="' . $month_year . '" style="width:250px;"  >';
                    echo '<input type="hidden" name="_action" value="Create" />
                    <input type="hidden" name="submitdata" value="1" />';
                }
                ?>
            </div>
        </form>
        <?php if ($mnthyr != '') { ?>
            <div class="print1">
                <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
            </div>
        <?php } ?>
        <div class="print-friendly" id="divToPrint">
            <div class="none">
                <div class="mh-custom-header" role="banner">
                    <div class="header-right" style="float: left;">
                        <img class="mh-header-image" src="<?php echo url("assets/images/rpgs-logo.png"); ?>" alt="RPSG" />
                    </div>
                    <div class="mh-site-logo" style="float: right;">
                        <img class="mh-header-image" src="<?php echo url("assets/images/logo2.png"); ?>" alt="CESC Safety Monitoring System logo" />
                    </div>
                </div>
            </div>
            <br>
            <div style="clear: both;"></div>
            <hr class="no-margin"/>
            <?php
            if (!empty($majorActivities_TA_SO)) {
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="month_year" id="month_year" value ="' . $selected_month_year . '" style="width:250px;"  >';
                echo '<h3>Safety Related Data for the Month of ' . $selectedMY . '</h3>';
                echo '<table class="table table-bordered">
                        <tr class="bg-primary text-uppercase">
                        <th width="40%">Safety Training - Distribution & Generation (Man days)</th>
                        <th width="15%">' . $selected_month . '</th>
                        <th width="15%">YTM ' . $current_financial_year . '</th>
                        <th width="15%">Target ' . $current_financial_year . '</th>
                        <th width="15%">Actual ' . $prev_FY . '</th>
                        </tr>';
                foreach ($majorActivities_TA_SO as $key => $activityview) {
                    $rowchange = ($activityview->name == 'TOTAL') ? "font-weight:bold;font-size:14px;background: #edf9fe none repeat scroll 0 0;" : "";
                    echo '<tr style="' . $rowchange . '">
                            <td width="40%" >' . $activityview->name . ''
                    . '<input type="hidden" name="type1[]" value="' . $activityview->type . '" />'
                    . '<input type="hidden" name="major_activity_id_TA_SO_' . $key . '" value="' . $activityview->id . '" />   '
                    . '<input type="hidden" name="name_TA_SO_' . $key . '" value="' . $activityview->name . '" /></td>
                            <td align="center">' . $activityview->total_in_this_month . '<input type="hidden" value="' . $activityview->total_in_this_month . '" name="total_in_this_month_TA_SO_' . $key . '" /></td>
                            <td align="center">' . $activityview->ytm_this_year . '<input type="hidden" value="' . $activityview->ytm_this_year . '" name="ytm_this_year_TA_SO_' . $key . '" /></td>
                            <td align="center">' . $activityview->target . '<input type="hidden" value="' . $activityview->target . '" name="target_TA_SO_' . $key . '" /></td>
                            <td align="center">' . $activityview->last_year_total . '<input type="hidden" value="' . $activityview->last_year_total . '" name="last_year_total_TA_SO_' . $key . '" /></td>
                            </tr>';
                }
                echo ' </table>'
                . '<div class="clearfix"></div>  ';
//               **************************************************************************************
                echo '<table class="table table-bordered">
                <tr class="bg-primary text-uppercase">
                <th width="40%" rowspan="2" style="vertical-align:middle;">Job Site Surveillance</th>
                <th width="15%" colspan="4">Distribution</th>
                </tr>
                <tr class="bg-primary text-uppercase">'
                . '<th width="15%">' . $selected_month . '</th>'
                . '<th width="15%">YTM ' . $current_financial_year . '</th>'
                . '<th width="15%">Target ' . $current_financial_year . '</th>'
                . '<th width="15%">Actual ' . $prev_FY . '</th>';

                echo '</tr>';
                $total_last_year = $total_target = $total_in_current_month = $total_ytm_this_year = 0;
                foreach ($majorActivities_DSA as $key => $activityview2) {
                    echo '<tr>
                    <td width="40%" >' . $activityview2->name . ''
                    . '<input type="hidden" name="type2[]" value="' . $activityview2->type . '" />'
                    . '<input type="hidden" name="major_activity_id_DSA_' . $key . '" value="' . $activityview2->id . '" />   '
                    . '<input type="hidden" name="name_DSA_' . $key . '" value="' . $activityview2->name . '" /></td>
                    <td align="center">' . $activityview2->total_in_this_month . '<input type="hidden" value="' . $activityview2->total_in_this_month . '" name="total_in_this_month_DSA_' . $key . '" /></td>
                    <td align="center">' . $activityview2->ytm_this_year . '<input type="hidden" value="' . $activityview2->ytm_this_year . '" name="ytm_this_year_DSA_' . $key . '" /></td>
                    <td align="center">' . $activityview2->target . '<input type="hidden" value="' . $activityview2->target . '" name="target_DSA_' . $key . '" /></td>
                    <td align="center">' . $activityview2->last_year_total . '<input type="hidden" value="' . $activityview2->last_year_total . '" name="last_year_total_DSA_' . $key . '" /></td>
                    
                    
                    </tr>';
                    $total_last_year += $activityview2->last_year_total;
                    $total_target += $activityview2->target;
                    $total_in_current_month += $activityview2->total_in_this_month;
                    $total_ytm_this_year += $activityview2->ytm_this_year;
                }
                echo '<tr style="font-weight:bold;font-size:14px;background: #edf9fe none repeat scroll 0 0;">'
                . '<td>TOTAL</td>'
                . '<td align="center">' . ($total_in_current_month) . '</td>'
                . '<td align="center">' . ($total_ytm_this_year) . '</td>'
                . '<td align="center">' . ($total_target) . '</td>'
                . '<td align="center">' . ($total_last_year) . '</td>'
                . '</tr>';
                echo ' </table>';
//            ****************************************Generation*****************************************
                echo '<table class="table table-bordered">
                <tr class="bg-primary text-uppercase">
                <th width="40%" rowspan="2" style="vertical-align:middle;">Job Site Surveillance</th>
                <th width="15%" colspan="4">Generation</th>
                </tr>
                <tr class="bg-primary text-uppercase">'
                . '<th width="15%">' . $selected_month . '</th>'
                . '<th width="15%">YTM ' . $current_financial_year . '</th>'
                . '<th width="15%">Target ' . $current_financial_year . '</th>'
                . '<th width="15%">Actual ' . $prev_FY . '</th>';

                echo '</tr>';
                $total_last_year = $total_target = $total_in_current_month = $total_ytm_this_year = 0;
                foreach ($majorActivities_G as $key => $activityview2) {
                    echo '<tr>
                    <td width="40%" >' . $activityview2->name . ''
                    . '<input type="hidden" name="type3[]" value="' . $activityview2->type . '" />'
                    . '<input type="hidden" name="major_activity_id_G_' . $key . '" value="' . $activityview2->id . '" />   '
                    . '<input type="hidden" name="name_G_' . $key . '" value="' . $activityview2->name . '" /></td>
                    <td align="center">' . $activityview2->total_in_this_month . '<input type="hidden" value="' . $activityview2->total_in_this_month . '" name="total_in_this_month_G_' . $key . '" /></td>
                    <td align="center">' . $activityview2->ytm_this_year . '<input type="hidden" value="' . $activityview2->ytm_this_year . '" name="ytm_this_year_G_' . $key . '" /></td>
                    <td align="center">' . $activityview2->target . '<input type="hidden" value="' . $activityview2->target . '" name="target_G_' . $key . '" /></td>
                    <td align="center">' . $activityview2->last_year_total . '<input type="hidden" value="' . $activityview2->last_year_total . '" name="last_year_total_G_' . $key . '" /></td>
                    
                    
                    </tr>';
                }
                echo ' </table>';
//                ****************************************HANDHOLDING1************************************************
                echo '<div class="clearfix"></div>';
                echo '<table class="table table-bordered">
                <tr class="bg-primary text-uppercase">
                <th width="40%" rowspan="2" style="vertical-align:middle;">SPECIAL ACTIVITY</th>
                <th width="15%" colspan="3">Distribution excluding Howrah District</th>
                <th width="15%" colspan="3">Howrah Model District</th>
                </tr>
                <tr class="bg-primary text-uppercase">'
                . '<th width="10%">' . $selected_month . '</th>'
                . '<th width="10%">YTM ' . $current_financial_year . '</th>'
                . '<th width="10%">Target ' . $current_financial_year . '</th>'
                . '<th width="10%">' . $selected_month . '</th>'
                . '<th width="10%">YTM ' . $current_financial_year . '</th>'
                . '<th width="10%">Target ' . $current_financial_year . '</th>';

                echo '</tr>';
                $total_last_year = $total_target = $total_in_current_month = $total_ytm_this_year = 0;
                foreach ($majorActivities_HH_SF as $key => $activityview2) {
                    echo '<tr>
                    <td width="40%" >' . $activityview2->name . ''
                    . '<input type="hidden" name="type4[]" value="' . $activityview2->type . '" />'
                    . '<input type="hidden" name="major_activity_id_2' . $key . '" value="' . $activityview2->id . '" />   '
                    . '<input type="hidden" name="name_2' . $key . '" value="' . $activityview2->name . '" /></td>
                        
                    <td align="center"><input type="text" class="aprVal" id="totalInThisMonth_sf" name="total_in_this_month' . $key . '" /><input type="hidden" value="NA" /></td>
                    <td align="center"><input type="text" class="ytmVal" id="ytmThisYear_sf" readonly="true" name="ytm_this_year' . $key . '" /><input type="hidden" id="ytmThisYear_sfHid" value="' . $activityview2->ytm_this_year . '" /></td>
                    <td align="center">' . $activityview2->target . '<input type="hidden" value="' . $activityview2->target . '" name="target' . $key . '" /></td>
                    
                    <td align="center"><input type="text" class="aprVal" id="totalInThisMonth_sf_g" name="total_in_this_month_g' . $key . '" /><input type="hidden" value="' . $activityview2->total_in_this_month_g . '" /></td>
                    <td align="center"><input type="text" class="ytmVal" id="ytmThisYear_sf_g" readonly="true" name="ytm_this_year_g' . $key . '" /><input type="hidden" id="ytmThisYear_sf_gHid" value="' . $activityview2->ytm_this_year_g . '"  /></td>
                    <td align="center">' . $activityview2->target_g . '<input type="hidden" value="' . $activityview2->target_g . '" name="target_g' . $key . '" /></td>
                    
                    
                    </tr>';
                }
                echo ' </table>';
//                ********************************HANDHOLDING2******************************************
                echo '<h5 class="text-primary" style="text-decoration: underline; "><b>Safety Initiatives / Events for the Month of ' . $selected_month . '</b></h5>';
                echo '<div class="clearfix"></div>';
                echo '<table class="table table-bordered">';
                    $total_last_year = $total_target = $total_in_current_month = $total_ytm_this_year = 0;
                foreach ($majorActivities_HH_HT as $key => $activityview3) {
               
               echo ' <tr>'
                . '<td width="28%" rowspan="3" style="vertical-align:middle;">' . $activityview3->name
                    . '<input type="hidden" name="type5[]" value="' . $activityview3->type . '" />'
                    . '<input type="hidden" name="major_activity_id_htmpb' . $key . '" value="' . $activityview3->id . '" />   '
                    . '<input type="hidden" name="name_htmpb' . $key . '" value="' . $activityview3->name . '" /></td>'
                . '<th class="bg-primary" colspan="2">' . $selected_month . '</th>'
                . '<th class="bg-primary" colspan="2">YTM ' . $current_financial_year . '</th>'
                . '<th class="bg-primary" colspan="2">Target ' . $current_financial_year . '</th>'
                . '<th class="bg-primary" colspan="2">Actual ' . $prev_FY . '</th>';

                echo '</tr>';
                echo '<tr>
                    
                    <td width="9%" align="center">P-Set</td>
                    <td width="9%" align="center">C-Set</td>
                    <td width="9%" align="center">P-Set</td>
                    <td width="9%" align="center">C-Set</td>
                    <td width="9%" align="center">P-Set</td>
                    <td width="9%" align="center">C-Set</td>
                    <td width="9%" align="center">P-Set</td>
                    <td width="9%" align="center">C-Set</td>
                    </tr>';
                
                    
                    echo 
                     '<tr>
                        <td align="center"><input type="text" class="aprVal1" id="totalInThisMonthP" name="total_in_this_month_p' . $key . '" /><input type="hidden"  value="' . $activityview3->total_in_this_month_p . '" /></td>
                        <td align="center"><input type="text" class="aprVal1" id="totalInThisMonthC" name="total_in_this_month_c' . $key . '" /><input type="hidden" value="' . $activityview3->total_in_this_month_c . '" /></td>

                        <td align="center"><input type="text" class="ytmVal1" id="ytmThisYearP" name="ytm_this_year_p' . $key . '" /><input type="hidden" id="ytmThisYearPHid" value="' . $activityview3->ytm_this_year_p . '" /></td>
                        <td align="center"><input type="text" class="ytmVal1" id="ytmThisYearC" name="ytm_this_year_c' . $key . '" /><input type="hidden" id="ytmThisYearCHid" value="' . $activityview3->ytm_this_year_c . '" /></td>
                            
                        <td align="center"><input type="text" readonly="true" value="15" name="target_p' . $key . '" /><input type="hidden" value="' . $activityview3->target_p . '" /></td>
                        <td align="center"><input type="text" readonly="true" value="0" name="target_c' . $key . '" /><input type="hidden" value="' . $activityview3->target_c . '" /></td>
                            
                        <td align="center"><input type="text" readonly="true" value="10" name="last_year_total_p' . $key . '" /><input type="hidden" value="' . $activityview3->last_year_total_p . '" /></td>
                        <td align="center"><input type="text" readonly="true" value="15" name="last_year_total_c' . $key . '" /><input type="hidden" value="' . $activityview3->last_year_total_c . '" /></td>   
                    </tr>';
                }
                echo ' </table>';
//                ********************************************Safety activity************************************
                //echo '<h5 class="text-primary" style="text-decoration: underline; "><b>Safety Initiatives / Events for the Month of ' . $selected_month . '</b></h5>';
                echo '<table class="table table-bordered">'
                        . '<tr class="bg-primary text-uppercase">'
                        . '<th width="40%" rowspan="2" style="vertical-align:middle;">Safety Activity</th>'
                        . '<th width="40%" colspan="3">Distribution</th>'
                        . '<th width="40%" colspan="3">Generation</th>'
                        . '</tr>'
                        . '<tr class="bg-primary text-uppercase">'
                        . '<th width="10%">' . $selected_month . '</th>'
                        . '<th width="10%"> YTM ' . $current_financial_year . '</th>'
                        . '<th width="10%">Target '.$current_financial_year.'</th>'
                        . '<th width="10%">' . $selected_month . '</th>'
                        . '<th width="10%"> YTM ' . $current_financial_year . '</th>'
                        . '<th width="10%">Target '.$current_financial_year.'</th>'
                        . '</tr>';
                foreach( $majorActivities_DG as $key => $rowdata ) {           
                    echo '<tr>'
                            . '<td>'.$rowdata->name.'<input type="hidden" name="type6[]" value="'.$rowdata->type.'" />'
                            . '<input type="hidden" name="name_SADG_'.$key.'" value="'.$rowdata->name.'" />'
                            . '<input type="hidden" name="major_activity_id_SADG_'.$key.'" value="'.$rowdata->id.'" />'
                            . '</td>'
                            . '<td align="center">'.$rowdata->total_in_this_month_D.'<input type="hidden" value="'.$rowdata->total_in_this_month_D.'" name="total_in_this_month_SAD_'.$key.'" /></td>'
                            . '<td align="center">'.$rowdata->ytm_this_year_D.'<input type="hidden" value="'.$rowdata->ytm_this_year_D.'" name="ytm_total_SAD_'.$key.'" /></td>'
                            . '<td align="center">'.$rowdata->target_D.'<input type="hidden" value="'.$rowdata->target_D.'" name="target_SAD_'.$key.'" /></td>'
                            . '<td align="center">'.$rowdata->total_in_this_month_G.'<input type="hidden" value="'.$rowdata->total_in_this_month_G.'" name="total_in_this_month_SAG_'.$key.'" /></td>'
                            . '<td align="center">'.$rowdata->ytm_this_year_G.'<input type="hidden" value="'.$rowdata->ytm_this_year_G.'" name="ytm_total_SAG_'.$key.'" /></td>'
                            . '<td align="center">'.$rowdata->target_G.'<input type="hidden" value="'.$rowdata->target_G.'" name="target_SAG_'.$key.'" /></td>'
                        . '</tr>';
                }
                echo '</table>';
//                *****************************************************ANY SPECIAL ACTIVITY ****************************************************
                echo '<table class="table table-bordered">';
                echo '<tr><td width="40%" style="vertical-align:middle;">ANY SPECIAL ACTIVITY </td>'
                . '<td colspan="2">';
                if (@$rowsingledata[0]->status_id != 2) {
                    echo '<textarea name="special_activities" style="width:100%;height:80px;">' . (isset($rowsingledata[0]->special_activities) ? $rowsingledata[0]->special_activities : "" ) . '</textarea>';
                } else {
                    echo (@$rowsingledata[0]->special_activities ? @$rowsingledata[0]->special_activities : "" );
                }
                echo '</td>'
                . '</tr></table>';

//                **************************************************Incident*********************************************
                echo '<table class="table table-bordered">'
                . '<tr class="bg-primary text-uppercase">'
                . '<th width="20%" rowspan="2">Incident Type</th>'
                . '<th width="15%" colspan="3">Distribution</th>'
                . '<th width="15%" colspan="3">Generation</th>'
                . '</tr>'
                . '<tr class="bg-primary text-uppercase">'
                . '<th width="10%">' . $selected_month . '</th>'
                . '<th width="10%">YTM ' . $current_financial_year . '</th>'
                . '<th width="10%">YTM ' . $prev_FY . '</th>'
                . '<th width="10%">' . $selected_month . '</th>'
                . '<th width="10%">YTM ' . $current_financial_year . '</th>'
                . '<th width="10%">YTM ' . $prev_FY . '</th>'
                . '</tr>';
           
            foreach( $majorActivitiesIN as $key => $rowdata ) {
                echo '<tr>'
                . '<td>'.$rowdata->name.''
                        . '<input type="hidden" name="type7[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_IN_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="insteredrowid_IN_'.$key.'" value="'.$rowdata->insteredrowid.'" />'
                        . '<input type="hidden" name="major_activity_id_IN_'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                . '<td align="center">'.$rowdata->total_in_this_month_d.'<input type="hidden" value="'.$rowdata->total_in_this_month_d.'" name="total_in_this_month_IND_'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->ytm_total_d.'<input type="hidden" value="'.$rowdata->ytm_total_d.'" name="ytm_total_IND_'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->last_year_ytm_d.'<input type="hidden" value="'.$rowdata->last_year_ytm_d.'" name="last_year_ytm_IND_'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->total_in_this_month_g.'<input type="hidden" value="'.$rowdata->total_in_this_month_g.'" name="total_in_this_month_ING_'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->ytm_total_g.'<input type="hidden" value="'.$rowdata->ytm_total_g.'" name="ytm_total_ING_'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->last_year_ytm_g.'<input type="hidden" value="'.$rowdata->last_year_ytm_g.'" name="last_year_ytm_ING_'.$key.'"  /></td>';                    
                echo '</tr>';
            }    
            echo  '</table>';
//                *******************************************************************************************************

                ?>
                <div class="clearfix"></div> 
                <div class="none">
                    <br><br>
                    <div style="float: left;width:30%;">
                        <img class="mh-header-image" src="<?php echo url("assets/images/rpgs-logo.png"); ?>" alt="RPSG" />
                    </div>
                    <div style="float: right;width:45%;text-align: right;">
                        <img class="mh-header-image" src="<?php echo url("assets/images/logo2.png"); ?>" alt="CESC Safety Monitoring System logo" />
                    </div>

                    <div style="clear: both;"></div>
                    <br>
                </div>

                <div class="none">
                    <?php
                    if (!empty($majorActivities)) {
                        echo '<input type="hidden" name="month_year" id="month_year" value ="' . $selected_month_year . '" style="width:250px;"  >';
                        echo '<h3>Safety Related Data for the  Month of ' . $selected_month . '</h3>';
                    }
                    ?>
                </div>
                <?php
                if (@$rowsingledata[0]->status_id != 2) {
                    echo '<div class="clearfix"></div> <input type="hidden" name="_action" value="mcmReportSubmit" />'
                    . '<div style=""><input type="submit" name="B1" class="btn btn-info btn-sm" value="Save as draft" />'
                    . '<input type="submit" name="B2" class="btn btn-primary btn-sm" value="Submit" />'
                    . '<a href="mcmReportlist.php" class="btn btn-danger btn-sm">Cancel</a>';
                }
                echo '</form>';
            }
            ?> 

        </div>
    </div>
</div>

<?php $controller->get_footer(); ?>
<link rel="stylesheet" href="<?php echo url("assets/css/jqueryui/jquery-ui.css") ?>">
<script src="<?php echo url("assets/js/jqueryui/jquery.mtz.monthpicker.js") ?>"></script>
<script type="text/javascript">
                    $('.month_year_picker').monthpicker({pattern: 'mm-yyyy',
                        startYear: 2015,
                        finalYear: 2025, });
                    var options = {
                        startYear: 2010,
                        finalYear: 2018,
                        openOnFocus: false
                    };
</script>
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>
<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
<script type="text/javascript">
                    jQuery.datetimepicker.setLocale('en');
                    jQuery('.datepicker').datetimepicker({
                        timepicker: false,
                        scrollMonth: false,
                        scrollInput: false,
                        format: 'd-m-Y',
                        step: 5
                    });

                    $(document).ready(function () {
                        var submitdata = "<?php echo $submitdata; ?>";
                        var mnthyr = "<?php echo $mnthyr; ?>";
                        if (submitdata == 0 && mnthyr != "") {
                            $("#mcmform").submit();
                        }
                    });

                    function PrintDiv()
                    {
                        var mywindow = window.open('', 'PRINT', 'height=400,width=600');
                        var content = document.getElementById("divToPrint").innerHTML;
                        mywindow.document.write('<html><head><title></title>');
                        mywindow.document.write('<style>table{border-collapse:collapse;margin-bottom:15px;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}</style></head><body>');
                        mywindow.document.write(content);
                        mywindow.document.write('</body></html>');
                        mywindow.document.close(); // necessary for IE >= 10
                        mywindow.focus(); // necessary for IE >= 10*/
                        mywindow.print();
                        mywindow.close();
                        return true;
                    }
</script>
<script>
        $(document).ready(function(){
        $("#totalInThisMonthP").keyup(function() {
            var totalInThisMonthP = $("#totalInThisMonthP").val();
//            var monthYearFrom       = $("#month_year_from").val();
            var ytmThisYearPHid      = $("#ytmThisYearPHid").val();//alert(ytmThisYearPHid);
            var ytmThisYearP         = parseInt(totalInThisMonthP)+parseInt(ytmThisYearPHid);
            $("#ytmThisYearP").val(ytmThisYearP);           
        });
        $("#totalInThisMonthC").keyup(function() {
            var totalInThisMonthC = $("#totalInThisMonthC").val();
//            var monthYearFrom       = $("#month_year_from").val();
            var ytmThisYearCHid      = $("#ytmThisYearCHid").val();
            var ytmThisYearC         = parseInt(totalInThisMonthC)+parseInt(ytmThisYearCHid);
            $("#ytmThisYearC").val(ytmThisYearC);           
        });
        $("#totalInThisMonth_sf").keyup(function() {
            var totalInThisMonth_sf = $("#totalInThisMonth_sf").val();
//            var monthYearFrom       = $("#month_year_from").val();
            var ytmThisYear_sfHid      = $("#ytmThisYear_sfHid").val();
            var ytmThisYear_sf         = parseInt(totalInThisMonth_sf)+parseInt(ytmThisYear_sfHid);
            $("#ytmThisYear_sf").val(ytmThisYear_sf);           
        });
        $("#totalInThisMonth_sf_g").keyup(function() {
            var totalInThisMonth_sf_g = $("#totalInThisMonth_sf_g").val();
//            var monthYearFrom       = $("#month_year_from").val();
            var ytmThisYear_sf_gHid      = $("#ytmThisYear_sf_gHid").val();
            var ytmThisYear_sf_g         = parseInt(totalInThisMonth_sf_g)+parseInt(ytmThisYear_sf_gHid);
            $("#ytmThisYear_sf_g").val(ytmThisYear_sf_g);           
        });
        
        
        
        /********** Allow only number ***********/
        $(".aprVal").keypress(function (event) {    
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            var aprVal = $(this).val();
            var ytmVal = $(this).parents("td").next("td").children(".ytmVal").val();
            if ((event.which < 48 || event.which > 57)) {
                if(ytmVal == "" || aprVal == '') {
                    $(this).parents("td").next("td").children(".ytmVal").val('');
                }
                event.preventDefault();
            }
          });
          $(".aprVal1").keypress(function (event) {    
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            var aprVal1 = $(this).val();
            var ytmVal1 = $(this).parents("td").next("td").next("td").children(".ytmVal1").val();
            if ((event.which < 48 || event.which > 57)) {
                if(ytmVal1 == "" || aprVal1 == '') {
                    $(this).parents("td").next("td").next("td").children(".ytmVal1").val('');
                }
                event.preventDefault();
            }
          });
          /********** ../ Allow only number ***********/
          /********** ../ remove NaN ***********/
          $(".aprVal").keyup(function (e) {   
              var aprVal = $(this).val();
              var ytmVal = $(this).parents("td").next("td").children(".ytmVal").val();
              if(aprVal == '' || ytmVal == 'NaN') {
                    $(this).parents("td").next("td").children(".ytmVal").val('');
                }
                event.preventDefault();
          });
          
          $(".aprVal1").keyup(function (e) {   
              var aprVal1 = $(this).val();
              var ytmVal1 = $(this).parents("td").next("td").next("td").children(".ytmVal1").val();
              if(aprVal1 == '' || ytmVal1 == 'NaN') {
                    $(this).parents("td").next("td").next("td").children(".ytmVal1").val('');
                }
                event.preventDefault();
          });
        /********** ../ remove NaN ***********/
        });
</script>