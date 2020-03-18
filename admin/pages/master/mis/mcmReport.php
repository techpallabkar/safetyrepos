<?php
/* 
 * MIS Report No. : 2
 * Name         :   MCM Report
 * Controller   :   mcmReport()
 * Dao          :   getOfficerTraining,getSupervisorTraining
 * Created By Anima Mahato
 */
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi                     =   $controller->beanUi;
$page                       =   $beanUi->get_view_data("page");
$mnthyr                     =   ($beanUi->get_view_data("mnthyr"));
$month_year                 =   ($beanUi->get_view_data("month_year")) ? $beanUi->get_view_data("month_year") : $mnthyr;
$majorActivities            =   ($beanUi->get_view_data("majorActivities"));
$majorActivities2           =   ($beanUi->get_view_data("majorActivities2"));
$majorActivities3           =   ($beanUi->get_view_data("majorActivities3"));
$majorActivities4           =   ($beanUi->get_view_data("majorActivities4"));
$current_financial_year     =   ($beanUi->get_view_data("current_financial_year"));
$previous_financial_year    =   ($beanUi->get_view_data("previous_financial_year"));
$selected_month_year        =   ($beanUi->get_view_data("selected_month_year")) ? $beanUi->get_view_data("selected_month_year") : "";
$site_root_url      =   dirname(url());
$selected_month     =   ($selected_month_year == "") ? "" : date("M y", strtotime('01-' . $selected_month_year));
$prev_FY            =   ($previous_financial_year == "") ? "" : $previous_financial_year;
$rowsingledata      =   ($beanUi->get_view_data("rowsingledata"));
$submitdata         =   ($beanUi->get_view_data("submitdata") ? $beanUi->get_view_data("submitdata") : "");
$mode               =   ($beanUi->get_view_data("mode")) ? $beanUi->get_view_data("mode") : "";
$controller->get_header();
if($prev_FY != "") {
$prev = explode("-",$prev_FY);
$prev_FY = $prev[0].'-'.date("y",strtotime("01-01-".$prev[1]));
} 
if($current_financial_year != "") {
$curr = explode("-",$current_financial_year);
$current_financial_year     =   $curr[0].'-'.date("y",strtotime("01-01-".$curr[1]));
}
?>
<style> .none {display: none;} </style>

<div class="container1">   
    <h1 class="heading">2. MCM Report<?php echo ( $mode == "view" ) ? '<a style="float: right;" href="mcmReportlist.php" class="btn btn-danger btn-xs">Back</a>' : ''; ?></h1>    
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
                    <a href="mcmReport.php" class="btn btn-danger btn-sm">Reset</a>
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
            if (!empty($majorActivities)) {
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="month_year" id="month_year" value ="' . $selected_month_year . '" style="width:250px;"  >';
                echo '<h3>Safety Related Data for the  Month of ' . $selected_month . '</h3>';
                echo '<table class="table table-bordered">
                        <tr class="bg-primary text-uppercase">
                        <th width="40%">Safety Related Training (Man days)</th>
                        <th width="15%">Actual ' . $prev_FY . '</th>
                        <th width="15%">Target ' . $current_financial_year . '</th>
                        <th width="15%">' . $selected_month . '</th>
                        <th width="15%">YTM ' . $selected_month . '</th>
                        </tr>';
                        foreach ($majorActivities as $key => $activityview) {
                            $rowchange = ($activityview->name == 'TOTAL') ? "font-weight:bold;font-size:14px;background: #edf9fe none repeat scroll 0 0;" : "";
                            echo '<tr style="' . $rowchange . '">
                            <td width="40%" >' . $activityview->name . ''
                            . '<input type="hidden" name="type1[]" value="' . $activityview->type . '" />'
                            . '<input type="hidden" name="major_activity_id_' . $key . '" value="' . $activityview->id . '" />   '
                            . '<input type="hidden" name="name_' . $key . '" value="' . $activityview->name . '" /></td>
                            <td align="center">' . $activityview->last_year_total . '<input type="hidden" value="' . $activityview->last_year_total . '" name="last_year_total_' . $key . '" /></td>
                            <td align="center">' . $activityview->target . '<input type="hidden" value="' . $activityview->target . '" name="target_' . $key . '" /></td>
                            <td align="center">' . $activityview->total_in_this_month . '<input type="hidden" value="' . $activityview->total_in_this_month . '" name="total_in_this_month_' . $key . '" /></td>
                            <td align="center">' . $activityview->ytm_this_year . '<input type="hidden" value="' . $activityview->ytm_this_year . '" name="ytm_this_year_' . $key . '" /></td>
                            </tr>';
                        }
                echo ' </table>'
                        . '<div class="clearfix"></div>  ';
               
                echo '<table class="table table-bordered">
                <tr class="bg-primary text-uppercase">
                <th width="40%">Job Site Surveillance</th>
                <th width="15%">Actual ' . $prev_FY . '</th>'
                . '<th width="15%">Target ' . $current_financial_year . '</th>'
                . '<th width="15%">' . $selected_month . '</th>'
                . '<th width="15%">YTM ' . $selected_month . '</th>
                </tr>';
                $total_last_year = $total_target = $total_in_current_month = $total_ytm_this_year = 0;
                foreach ($majorActivities2 as $key => $activityview2) {
                    echo '<tr>
                    <td width="40%" >' . $activityview2->name . ''
                    . '<input type="hidden" name="type2[]" value="' . $activityview2->type . '" />'
                    . '<input type="hidden" name="major_activity_id_2' . $key . '" value="' . $activityview2->id . '" />   '
                    . '<input type="hidden" name="name_2' . $key . '" value="' . $activityview2->name . '" /></td>
                    <td align="center">' . $activityview2->last_year_total . '<input type="hidden" value="' . $activityview2->last_year_total . '" name="last_year_total_2' . $key . '" /></td>
                    <td align="center">' . $activityview2->target . '<input type="hidden" value="' . $activityview2->target . '" name="target_2' . $key . '" /></td>
                    <td align="center">' . $activityview2->total_in_this_month . '<input type="hidden" value="' . $activityview2->total_in_this_month . '" name="total_in_this_month_2' . $key . '" /></td>
                    <td align="center">' . $activityview2->ytm_this_year . '<input type="hidden" value="' . $activityview2->ytm_this_year . '" name="ytm_this_year_2' . $key . '" /></td>
                    </tr>';
                    $total_last_year    +=  $activityview2->last_year_total;
                    $total_target       +=  $activityview2->target;
                    $total_in_current_month +=  $activityview2->total_in_this_month;
                    $total_ytm_this_year    +=  $activityview2->ytm_this_year;
                }
                echo '<tr style="font-weight:bold;font-size:14px;background: #edf9fe none repeat scroll 0 0;">'
                . '<td>TOTAL</td>'
                . '<td align="center">' . ($total_last_year) . '</td>'
                . '<td align="center">' . ($total_target) . '</td>'
                . '<td align="center">' . ($total_in_current_month) . '</td>'
                . '<td align="center">' . ($total_ytm_this_year) . '</td>'
                . '</tr>';
                echo ' </table>'
                . '<div class="clearfix"></div>';
               
                echo '<table class="table table-bordered">
                <tr class="bg-primary text-uppercase">
                <th width="40%">Incident</th>
                <th>Actual ' . $prev_FY . '</th>
                <th>' . $selected_month . '</th>
                <th>YTM ' . $selected_month . '</th>
                </tr>';
                foreach ($majorActivities3 as $key => $activityview3) {
                    echo '<tr><td width="40%" >' . $activityview3->name
                    . '<input type="hidden" name="type3[]" value="' . $activityview3->type . '" />'
                    . '<input type="hidden" name="name_3' . $key . '" value="' . $activityview3->name . '" />'
                    . '<input type="hidden" name="major_activity_id_3' . $key . '" value="' . $activityview3->id . '" />   '
                    . '</td>'
                    . '<td align="center">' . $activityview3->actual_last_year . '<input type="hidden" value="' . $activityview3->actual_last_year . '" name="actual_last_year_3' . $key . '" /></td>'
                    . '<td align="center">' . $activityview3->total_in_this_month . '<input type="hidden" value="' . $activityview3->total_in_this_month . '" name="total_in_this_month_3' . $key . '" /></td>'
                    . '<td align="center">' . $activityview3->ytm_total . '<input type="hidden" value="' . $activityview3->ytm_total . '" name="ytm_total_3' . $key . '" /></td></tr>';
                }
                echo ' </table>';
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
                echo '<table class="table table-bordered">
                <tr class="bg-primary text-uppercase">
                <th width="40%">Action Items</th><th>Status for ' . $selected_month . '</th><th>Remarks</th>
                </tr>';
                foreach ($majorActivities4 as $key => $activityview4) {
                    echo '<tr>
                    <td width="40%" >' . $activityview4->name . '<input type="hidden" name="type4[]" value="' . $activityview4->type . '" />
                    <input type="hidden" name="name_4' . $key . '" value="' . $activityview4->name . '" /></td>
                    <input type="hidden" name="major_activity_id_4' . $key . '" value="' . $activityview4->id . '" />   
                    <input type="hidden" name="insertedrowid_' . $key . '" value="' . $activityview4->insertedrowid . '" /></td>
                    <td width="15%" align="center">Total No. = ' . $activityview4->total_in_this_month . '<input type="hidden" value="' . $activityview4->total_in_this_month . '" name="total_in_this_month_4' . $key . '" /></td>
                    <td align="left">';
                    if (@$rowsingledata[0]->status_id != 2) {
                        echo '<textarea name="remarks_4' . $key . '" style="width:100%;height:80px;">' . $activityview4->remarks . '</textarea>';
                    } else {
                        echo $activityview4->remarks;
                    }
                    echo '</td></tr>';
                }
                echo '<tr><td width="40%">ANY SPECIAL ACTIVITY </td>'
                . '<td colspan="2">';
                if (@$rowsingledata[0]->status_id != 2) {
                    echo '<textarea name="special_activities" style="width:100%;height:80px;">' . (isset($rowsingledata[0]->special_activities) ? $rowsingledata[0]->special_activities : "" ) . '</textarea>';
                } else {
                    echo (@$rowsingledata[0]->special_activities ? @$rowsingledata[0]->special_activities : "" );
                }
                echo '</td>'
                . '</tr></table>';
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