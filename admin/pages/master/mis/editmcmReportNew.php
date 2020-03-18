<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$mnthyr                 = ($beanUi->get_view_data("mnthyr"));
$fetchalldata           = ($beanUi->get_view_data("fetchalldata"));
$month_year             = ($beanUi->get_view_data("month_year")) ? $beanUi->get_view_data("month_year") : $mnthyr;
$current_financial_year = ($beanUi->get_view_data("current_financial_year"));
$previous_financial_year= ($beanUi->get_view_data("previous_financial_year"));
$selected_month_year    = ($beanUi->get_view_data("selected_month_year")) ? $beanUi->get_view_data("selected_month_year") : "";

$selected_month         = ($selected_month_year == "") ? "" : date("M y", strtotime('01-' . $selected_month_year));
$selectedMY             = ($selected_month_year == "") ? "" : date("F Y", strtotime('01-' . $selected_month_year));
$prev_FY                = ($previous_financial_year == "") ? "" : $previous_financial_year;
$rowsingledata          = ($beanUi->get_view_data("rowsingledata"));
$submitdata             = ($beanUi->get_view_data("submitdata") ? $beanUi->get_view_data("submitdata") : "");
$mode                   = ($beanUi->get_view_data("mode")) ? $beanUi->get_view_data("mode") : "";
$prev_FY                = ($previous_financial_year == "") ? "" : $previous_financial_year;
$rowsingledata          = ($beanUi->get_view_data("rowsingledata"));

$majorActivities_TA_SO      =   @$fetchalldata[17];
$majorActivities_DSA        =   @$fetchalldata[18];
$majorActivities_G          =   @$fetchalldata[19];
$majorActivities_HH_SF      =   @$fetchalldata[20];
$majorActivities_HH_HT      =   @$fetchalldata[21];
$majorActivities_DG         =   @$fetchalldata[22];
$majorActivitiesIN          =   @$fetchalldata[23];

$controller->get_header();
$site_root_url = dirname(url());
//show($majorActivities2);
$prev = explode("-",$prev_FY);
$prev_FY = $prev[0].'-'.date("y",strtotime("01-01-".$prev[1]));
$curr = explode("-",$current_financial_year);
$current_financial_year     =   $curr[0].'-'.date("y",strtotime("01-01-".$curr[1]));
?>
<style>
    .none {display: none;}
</style>

<div class="container1">
    <?php echo $beanUi->get_message(); ?>
    <h1 class="heading">2. MCM Report
        <?php echo ( $mode == "view" ) ? '<a style="float: right;" href="mcmReportlistNew.php" class="btn btn-danger btn-xs">Back</a>' : ''; ?>
    </h1>    
    <div class="wrapper2">    
        <div class="holder" style="float:left;width:500px;">
            <?php
            echo '<label for="job_stop_req_raisedx" style="width:30%;">Selected Month-Year :</label>';
            echo '<span style="padding-top:3px;">' . $month_year . '</span>';          
            ?>
        </div>
        <?php if ($mnthyr != '') { ?>
            <div class="print1" style="float:right;">
                <a class="btn btn-danger btn-sm" onclick="PrintDiv();"> <i class="fa fa-print"></i> Print / PDF</a>
            </div>
        <?php } ?>
        <div class="print-friendly" id="divToPrint">
		<div class="mcm_print">
                    <table style="width: 100%;" class="print-table">
                        <thead>
                            <tr>
                                <td>
                                    <div class="none">
                                        <div class="mh-custom-header" role="banner">
                                            <div style="float: left;width:30%;">
                                                <img class="mh-header-image" src="<?php echo url("assets/images/rpgs-logo.png"); ?>" alt="RPSG" />
                                            </div>
                                            <div style="float: right;width:45%;text-align: right;">
                                                <img class="mh-header-image" src="<?php echo url("assets/images/logo2.png"); ?>" alt="CESC Safety Monitoring System logo" />
                                            </div>

                                            <div style="clear: both;"></div>
                                            <br>
                                        </div>
                                    </div>
                                    <div style="clear: both;"></div>
                                    <hr class="no-margin"/>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>                       
            <?php
            if (!empty($majorActivities_TA_SO)) {
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="month_year" id="month_year" value ="' . $selected_month_year . '" style="width:250px;"  >';
                echo '<h3 style="float:left;">Safety Related Data for the  Month of ' . $selectedMY . '</h3><div style="float:right;font-weight:600;padding-top:28px;">Owner: ED (HR & A)</div>';
                echo '<div style="clear:both;"></div>';
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
                            <td align="center">' . $activityview->ytm_total . '<input type="hidden" value="' . $activityview->ytm_total . '" name="ytm_this_year_TA_SO_' . $key . '" /></td>
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
                    <td align="center">' . $activityview2->ytm_total . '<input type="hidden" value="' . $activityview2->ytm_total . '" name="ytm_this_year_DSA_' . $key . '" /></td>
                    <td align="center">' . $activityview2->target . '<input type="hidden" value="' . $activityview2->target . '" name="target_DSA_' . $key . '" /></td>
                    <td align="center">' . $activityview2->last_year_total . '<input type="hidden" value="' . $activityview2->last_year_total . '" name="last_year_total_DSA_' . $key . '" /></td>
                    
                    
                    </tr>';
                    $total_last_year += $activityview2->last_year_total;
                    $total_target += $activityview2->target;
                    $total_in_current_month += $activityview2->total_in_this_month;
                    $total_ytm_this_year += $activityview2->ytm_total;
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
                    <td align="center">' . $activityview2->ytm_total . '<input type="hidden" value="' . $activityview2->ytm_total . '" name="ytm_this_year_G_' . $key . '" /></td>
                    <td align="center">' . $activityview2->target . '<input type="hidden" value="' . $activityview2->target . '" name="target_G_' . $key . '" /></td>
                    <td align="center">' . $activityview2->last_year_total . '<input type="hidden" value="' . $activityview2->last_year_total . '" name="last_year_total_G_' . $key . '" /></td>
                    
                    
                    </tr>';
                }
                echo ' </table>';
//                ****************************************HANDHOLDING1************************************************
                echo '<div class="clearfix"></div>';
                echo '<table class="table table-bordered">
                <tr class="bg-primary text-uppercase">
                <th width="40%" rowspan="2" style="vertical-align:middle;">SPECIAL ACTIVITY </th>
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
                    <td width="40%">' . @$activityview2->name . ''
                    . '<input type="hidden" name="type4[]" value="' . $activityview2->type . '" />'
                    . '<input type="hidden" name="major_activity_id_2' . $key . '" value="' . $activityview2->id . '" />   '
                    . '<input type="hidden" name="name_2' . $key . '" value="' . $activityview2->name . '" /> &nbsp;</td>
                    <td align="center">' . @$activityview2->total_in_this_month . '<input type="hidden" value="' . @$activityview2->total_in_this_month . '" name="total_in_this_month' . $key . '" /></td>
                    <td align="center">' . @$activityview2->ytm_total . '<input type="hidden" value="' . @$activityview2->ytm_this_year . '" name="ytm_this_year' . $key . '" /></td>
                    <td align="center">' . $activityview2->target . '<input type="hidden" value="' . $activityview2->target . '" name="target' . $key . '" /></td>
                    
                    <td align="center">' . @$activityview2->total_in_this_month_g . '<input type="hidden" value="' . @$activityview2->total_in_this_month_g . '" name="total_in_this_month_g' . $key . '" /></td>
                    <td align="center">' . @$activityview2->ytm_total_g . '<input type="hidden" value="' . @$activityview2->ytm_this_year_g . '" name="ytm_this_year_g' . $key . '" /></td>
                    <td align="center">' . $activityview2->target_g . '<input type="hidden" value="' . $activityview2->target_g . '" name="target_g' . $key . '" /></td>
                    
                    
                    </tr>';
                }
                echo ' </table>';
//                ********************************HANDHOLDING2******************************************
                //echo '<div style="height:50px; width:100%;" class="display-none"></div>';
                echo '<div style="page-break-before: always;"></div>';
                echo '<div class="clearfix"></div>';
                echo '<h3 class="text-primary" style="padding-top:15px;float:left;"><b>Safety Initiatives / Events for the Month of ' . $selectedMY . '</b></h3><div style="float:right;font-weight:600;padding-top:33px;">Owner: ED (HR & A)</div>';
                
                echo '<div style="clear:both;"></div>';
                echo '<table class="table table-bordered">';
                    $total_last_year = $total_target = $total_in_current_month = $total_ytm_this_year = 0;
                foreach ($majorActivities_HH_HT as $key => $activityview3) {
               
               echo ' <tr>'
                . '<td width="28%" rowspan="3" style="vertical-align:middle;">' . @$activityview3->name
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
                    
                    <td align="center">' . @$activityview3->total_in_this_month_p . '<input type="hidden" value="' . @$activityview3->total_in_this_month_p . '" name="total_in_this_month_p' . $key . '" /></td>
                    <td align="center">' . @$activityview3->total_in_this_month_c . '<input type="hidden" value="' . @$activityview3->total_in_this_month_c . '" name="total_in_this_month_c' . $key . '" /></td>
                    
                    <td align="center">' . @$activityview3->ytm_this_year_p . '<input type="hidden" value="' . @$activityview3->ytm_this_year_p . '" name="ytm_this_year_p' . $key . '" /></td>
                    <td align="center">' . @$activityview3->ytm_this_year_c . '<input type="hidden" value="' . @$activityview3->ytm_this_year_c . '" name="ytm_this_year_c' . $key . '" /></td>
                    
                    <td align="center">' . @$activityview3->target_p . '<input type="hidden" value="' . @$activityview2->target_p . '" name="target_p' . $key . '" /></td>
                    <td align="center">' . @$activityview3->target_c . '<input type="hidden" value="' . @$activityview2->target_c . '" name="target_c' . $key . '" />&nbsp;</td>
                        
                    <td align="center">' . @$activityview3->last_year_total_p . '<input type="hidden" value="' . @$activityview3->last_year_total_p . '" name="last_year_total_p' . $key . '" /></td>
                    <td align="center">' . @$activityview3->last_year_total_c . '<input type="hidden" value="' . @$activityview3->last_year_total_c . '" name="last_year_total_c' . $key . '" /></td>
                    
                    </tr>';
                }
                echo ' </table>';
//                ********************************************Safety activity************************************
                // echo '<h3 class="text-primary" style="padding-top:15px;float:left;"><b>Safety Initiatives / Events for the Month of ' . $selectedMY . '</b></h3><div style="float:right;font-weight:600;padding-top:33px;">Owner: ED (HR & A)</div>';
                echo '<div style="clear:both;"></div>';
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
                            . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_SAD_'.$key.'" /></td>'
                            . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_SAD_'.$key.'" /></td>'
                            . '<td align="center">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_SAD_'.$key.'" /></td>'
                            . '<td align="center">'.$rowdata->total_in_this_month_g.'<input type="hidden" value="'.$rowdata->total_in_this_month_g.'" name="total_in_this_month_SAG_'.$key.'" /></td>'
                            . '<td align="center">'.$rowdata->ytm_total_g.'<input type="hidden" value="'.$rowdata->ytm_total_g.'" name="ytm_total_SAG_'.$key.'" /></td>'
                            . '<td align="center">'.$rowdata->target_g.'<input type="hidden" value="'.$rowdata->target_g.'" name="target_SAG_'.$key.'" /></td>'
                        . '</tr>';
                }
                echo '</table>';
//                *****************************************************ANY SPECIAL ACTIVITY ****************************************************
                echo '<table class="table table-bordered" style="width:100%;">';
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
                . '<th width="20%" rowspan="2" style="vertical-align:middle">Incident Type</th>'
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
                        . '<input type="hidden" name="insteredrowid_IN_'.$key.'" value="'.@$rowdata->insteredrowid.'" />'
                        . '<input type="hidden" name="major_activity_id_IN_'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_IND_'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_IND_'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_ytm_IND_'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->total_in_this_month_g.'<input type="hidden" value="'.$rowdata->total_in_this_month_g.'" name="total_in_this_month_ING_'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->ytm_total_g.'<input type="hidden" value="'.$rowdata->ytm_total_g.'" name="ytm_total_ING_'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->last_year_total_g.'<input type="hidden" value="'.$rowdata->last_year_total_g.'" name="last_year_ytm_ING_'.$key.'"  /></td>';                    
                echo '</tr>';
            }    
            echo  '</table>';
//                *******************************************************************************************************
                ?>
                <div class="clearfix"></div>  
                

			</div>
			<div class="clearfix"></div> 
			<!--<div class="mcm_print">
                <div class="none">
                    
                    <div style="float: left;width:30%;">
                        <img class="mh-header-image" src="<?php echo url("assets/images/rpgs-logo.png"); ?>" alt="RPSG" />
                    </div>
                    <div style="float: right;width:45%;text-align: right;">
                        <img class="mh-header-image" src="<?php echo url("assets/images/logo2.png"); ?>" alt="CESC Safety Monitoring System logo" />
                    </div>

                    <div style="clear: both;"></div>
                    <br>
					<hr class="no-margin"/>
                </div>-->

                <div class="none">
                    <?php
                    if (!empty($majorActivities)) {
                            echo '<input type="hidden" name="month_year" id="month_year" value ="' . $selected_month_year . '" style="width:250px;"  >';
                            echo '<h3>Safety Related Data for the  Month of ' . $selected_month . '</h3>';
                    }
                    ?>
                </div>
                    <?php
//                    echo '<table class="table table-bordered">
//                <tr class="bg-primary text-uppercase">
//                <th width="40%">Action Items</th><th>Status for ' . $selected_month . '</th><th>Remarks</th>
//                </tr>';
//                    foreach (array_values($majorActivities4) as $key => $activityview4) {
//                        echo '<tr>
//                <td width="40%" >' . $activityview4->name . '<input type="hidden" name="type4[]" value="' . $activityview4->type . '" />
//                    <input type="hidden" name="name[]" value="' . $activityview4->name . '" /></td>
//                    <input type="hidden" name="major_activity_id_4' . $key . '" value="' . $activityview4->id . '" />   
//                    <input type="hidden" name="insertedrowid_' . $key . '" value="' . $activityview4->id . '" /></td>
//                <td width="15%" align="center">Total No. = ' . $activityview4->total_in_this_month . '<input type="hidden" value="' . $activityview4->total_in_this_month . '" name="total_in_this_month_4' . $key . '" /></td>
//                <td align="left">';
//                        if ($rowsingledata[0]->status_id != 2) {
//                            echo '<textarea name="remarks_4' . $key . '" style="width:100%;height:80px;">' . $activityview4->remarks . '</textarea>';
//                        } else {
//                            echo $activityview4->remarks;
//                        }
//
//                        echo '</td></tr>';
//                    }
//                    echo '<tr><td width="40%">ANY SPECIAL ACTIVITY </td>'
//                    . '<td colspan="2">';
//                    if ($rowsingledata[0]->status_id != 2) {
//                        echo '<textarea name="special_activities" style="width:100%;height:80px;">' . ($rowsingledata[0]->special_activities ? $rowsingledata[0]->special_activities : "" ) . '</textarea>';
//                    } else {
//                        echo ($rowsingledata[0]->special_activities ? $rowsingledata[0]->special_activities : "" );
//                    }
//                    echo '</td>'
//                    . '</tr></table>';
                    if ($rowsingledata[0]->status_id != 2) {
                        echo '<div class="clearfix"></div> <input type="hidden" name="_action" value="mcmReportSubmit" />'
                        . '<div style=""><input type="submit" name="B1" class="btn btn-info btn-sm" value="Save as draft" />'
                        . '<input type="submit" name="B2" class="btn btn-primary btn-sm" value="Submit" />'
                        . '<a href="mcmReportlistNew.php" class="btn btn-danger btn-sm">Cancel</a>';
                    }
                    echo '</form>';
                    
                }
                ?> 
		
                        <div> <?php // echo CURRENT_DATE_TIME; ?> </div>
                        
                       
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <div class="none" style="text-align: right; padding: 2px 10px;">
                        MCM DATA - SAFETY DEPARTMENT
                    </div>
                </td>
            </tr>
        </tfoot>
       </table>
        </div>
    </div>
</div>


<?php $controller->get_footer(); ?>

<script type="text/javascript">
    function PrintDiv()
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');
        var content = document.getElementById("divToPrint").innerHTML;
        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('<style>@page{margin:2mm 20mm 3mm 20mm;}.table{border-collapse:collapse;margin-bottom:15px;width:100%;}.table th, .table td {border:1px solid #80aaff;border-collapse:collapse;padding:3px 5px;}.table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;font-size:14px;}th {color:#ffffff !important;}tfoot td{background: #8c0084;color:#fed600;-webkit-print-color-adjust: exact;color-adjust: exact;font-size:14px; width:100%;}thead{display: table-header-group;}.print-table tfoot tr td {position:fixed;left:5px;right:5px;bottom:0px;}</style></head><body>');
        mywindow.document.write(content);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();

        return true;
    }
</script>
