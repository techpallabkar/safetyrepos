<?php
/* 
 * MIS Report No. : 1
 * Name         :   MD Report 
 * Controller   :   mdReport()
 * Dao          :   getOfficerTraining, getSupervisorTraining, getPostCount, getSiteAuditCount, get_number_safety_observation.
 * Created By Anima Mahato
 */
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$mnthyr = ($beanUi->get_view_data("mnthyr"));
$month_year = ($beanUi->get_view_data("month_year")) ? $beanUi->get_view_data("month_year") : $mnthyr ;
$majorActivities_TA_SO = ($beanUi->get_view_data("majorActivities_TA_SO"));
$majorActivitiesDistExHD = ($beanUi->get_view_data("majorActivitiesDistExHD"));
$majorActivitiesDistHD  = ($beanUi->get_view_data("majorActivitiesDistHD"));
$majorActivitiesDistMSTAST  = ($beanUi->get_view_data("majorActivitiesDistMSTAST"));
$majorActivitiesGen  = ($beanUi->get_view_data("majorActivitiesGen"));
$getaccident = ($beanUi->get_view_data("getaccident"));
$getsiteauditscore = ($beanUi->get_view_data("getsiteauditscore"));
$rowsingledata = ($beanUi->get_view_data("rowsingledata"));
$submitdata = ($beanUi->get_view_data("submitdata") ? $beanUi->get_view_data("submitdata") : "");
$safetymember = ($beanUi->get_view_data("safetymember"));
$mode = ($beanUi->get_view_data("mode")) ? $beanUi->get_view_data("mode") : "";
$current_financial_year = ($beanUi->get_view_data("current_financial_year")) ? $beanUi->get_view_data("current_financial_year") : "";
$previous_financial_year = ($beanUi->get_view_data("previous_financial_year")) ? $beanUi->get_view_data("previous_financial_year") : "";
$controller->get_header();
$site_root_url = dirname(url());
if($current_financial_year != "") {
$exp = explode("-", $current_financial_year);
$current_financial_year = $exp[0].'-'.date("y", strtotime('01-01-'.$exp[1]));
} else { 
    $current_financial_year = "";
}
?>
<style>
    .wrapper {padding:10px;border:1px solid #eee;}
</style>
<div class="container1">
    <?php echo $beanUi->get_message(); ?>
    <h1 class="heading">1. MD's Report 
        <?php echo  ( $mode == "view" ) ? '<a style="float: right;" href="mdReportlist.php" class="btn btn-danger btn-xs">Back</a>' : ''; ?>
        </h1> 
    <?php if($mnthyr != '') { ?>
    <div class="print1">
        <input class="btn btn-danger"  type="button" value="Print / PDF" onclick="PrintDiv();" />
   </div>
    <?php } ?>
    <div class="wrapper2">       
        <div class="print-friendly" id="divToPrint">
            <form action="" method="post" id="mdform" enctype="multipart/form-data" style="<?php echo  ( $mode == "view" ) ? 'display:none' : 'display:block'; ?>" >
                <div class="holder">
                    
                    
                    <?php if($mnthyr == '') { ?>
                    <label for="job_stop_req_raisedx" style="width:15%;padding-top:5px;">Select Month-Year :</label>
                    <input required="true" type="text" name="month_year" id="month_year_from" class="month_year_picker" value ="<?php echo $month_year; ?>" style="width:250px;"  >
                    <input type="hidden" name="_action" value="Create" />
                    <input type="hidden" name="submitdata" value="1" />
                    <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
                    <a href="mdReportNew.php" class="btn btn-danger btn-sm">Reset</a>
                    <?php } else {
                        echo '<label for="job_stop_req_raisedx" style="width:15%;">Select Month-Year :</label>';
                        echo '<span style="padding-top:3px;">'.$month_year.'</span>';
                        echo '<input type="hidden" name="month_year" id="month_year_from" class="month_year_picker" value ="'.$month_year.'" style="width:250px;"  >';
                        echo '<input type="hidden" name="_action" value="Create" />
                    <input type="hidden" name="submitdata" value="1" />';
                    } ?>
                </div>
                <hr class="no-margin"/>
            </form>
        
        <?php   
        
        if (!empty($majorActivities_TA_SO)) {
            echo '<form method="post" action="" autocomplete="off">'
            . '<input type="hidden" name="month_year" id="month_year" value ="'.$month_year.'" style="width:250px;"  >';
            echo '<div class="wrapper">'
                    . '<div style="float:right;">';
            if(@$rowsingledata[0]->status_id == 1) {
                echo '<input required="true" type="text" name="report_date" class="datepicker" value="'.date("d-m-Y",strtotime($rowsingledata[0]->report_date)).'" style="width:250px;" />';
            } else if(@$rowsingledata[0]->status_id == 2) {
                echo date("dS F Y",strtotime(@$rowsingledata[0]->report_date));
            } else {
                echo '<input required="true" type="text"  name="report_date" class="datepicker" value="'.date("d-m-Y").'" style="width:250px;" />';
            }
            echo    '</div>'
                    . '<div class="clearfix"></div>'
                    . '<div><span style="float:left;font-weight:bold;">The Managing Director (Distribution)<br>The Managing Director (Generation)<br>CESC Ltd.</span><span style="float:right;font-weight:bold;">Through : ED ( HR & A )</span></div>'
                    . '<div style="clear:both;"></div>'
                    . '<div style="text-align:center;font-weight:bold;text-transform:uppercase;">SAFETY CELL<br> REPORT FOR '.date("F,Y",strtotime('01-'.$_REQUEST['month_year'])).'</div>'
                    . '<h5 class="text-primary" style="text-decoration: underline; "><b>MAJOR ACTIVITIES : </b></h5>'
                    . '<h5 class="text-primary" style="text-decoration: underline; "><b>A. SAFETY ACTIVITIES - DISTRIBUTION & GENERATION DIVISION : </b></h5>';
            echo '<table class="table table-bordered">'
                    . '<tr class="text-primary">'
                    . '<th width="40%">Category</th>'
                    . '<th width="15%">Total in the Month</th>'
                    . '<th width="15%">YTM Total</th>'
                    . '<th width="15%">Target for '.$current_financial_year.'</th>'
                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
                    . '</tr>';
            foreach( $majorActivities_TA_SO as $key => $rowdata ) {    //show($majorActivities_TA_SO);          
                echo '<tr>'
                        . '<td>'.$rowdata->name.'<input type="hidden" name="type1[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_TA_SO_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_TA_SO_'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                        . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_TA_SO_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_TA_SO_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_TA_SO_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_TA_SO_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>';
            
//            ******************************distExHD**************************
            echo '<h5 class="text-primary" style="text-decoration: underline; "><b>SAFETY INITIATIVES BY SAFETY CELL : </b></h5>'
                 .'<h5 class="text-primary" style="text-decoration: underline; "><b>1. DISTRIBUTION : </b></h5>';
            echo '<table class="table table-bordered">'
                    . '<tr class="text-primary">'
                    . '<th width="40%">All Districts - Excluding Howrah</th>'
                    . '<th width="15%">Total in the Month</th>'
                    . '<th width="15%">YTM Total</th>'
                    . '<th width="15%">Target for '.$current_financial_year.'</th>'
                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
                    . '</tr>';
            foreach( $majorActivitiesDistExHD as $key => $rowdata ) {           
                echo '<tr>'
                        . '<td>'.$rowdata->name.'<input type="hidden" name="type4[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_DistExHD_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_DistExHD_'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                        . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_DistExHD_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_DistExHD_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_DistExHD_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_DistExHD_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>';
//            ******************************distExHD**************************
//            ******************************distHD**************************
            echo '<table class="table table-bordered">'
                    . '<tr class="text-primary">'
                    . '<th width="40%">Howrah Model District</th>'
                    . '<th width="15%">Total in the Month</th>'
                    . '<th width="15%">YTM Total</th>'
                    . '<th width="15%">Target for '.$current_financial_year.'</th>'
                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
                    . '</tr>';
            foreach( $majorActivitiesDistHD as $key => $rowdata ) {             
                echo '<tr>'
                        . '<td>'.$rowdata->name.'<input type="hidden" name="type5[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_distHD_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_distHD_'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                        . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_distHD_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_distHD_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_distHD_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_distHD_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>';
//            ******************************distHD************************** 
//            ******************************distMSTAST************************** 
            
            echo '<table class="table table-bordered">'
                    . '<tr class="text-primary">'
                    . '<th width="40%">Other Department (Mains Maintenance,Sub. Station, Testing Dept., Construction, Sys. Cont., Transmission Project)</th>'
                    . '<th width="15%">Total in the Month</th>'
                    . '<th width="15%">YTM Total</th>'
                    . '<th width="15%">Target for '.$current_financial_year.'</th>'
                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
                    . '</tr>';
            foreach( $majorActivitiesDistMSTAST as $key => $rowdata ) {             
                echo '<tr>'
                        . '<td>'.$rowdata->name.'<input type="hidden" name="type6[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_distMSTAST_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_distMSTAST_'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                        . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_distMSTAST_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_distMSTAST_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_distMSTAST_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_distMSTAST_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>';
            
//            ******************************distMSTAST**************************  
//            ******************************Gen**************************  
            echo '<h5 class="text-primary" style="text-decoration: underline; "><b>2. GENERATION : </b></h5>';
            echo '<table class="table table-bordered">'
                    . '<tr class="text-primary">'
                    . '<th width="40%">BBGS & SGS</th>'
                    . '<th width="15%">Total in the Month</th>'
                    . '<th width="15%">YTM Total</th>'
                    . '<th width="15%">Target for '.$current_financial_year.'</th>'
                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
                    . '</tr>';
            foreach( $majorActivitiesGen as $key => $rowdata ) {            
                echo '<tr>'
                        . '<td>'.$rowdata->name.'<input type="hidden" name="type7[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_Gen_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_Gen_'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                        . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_Gen_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_Gen_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_Gen_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_Gen_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>';
 //            ******************************Gen**************************            
            echo  '<h5 class="text-primary" style="text-decoration: underline; "><b>B. SITE AUDIT SCORE : </b></h5>'
                .'<table class="table table-bordered">'
                . '<tr class="text-primary">'
                . '<th rowspan="2" width="20%">Departments</th>'
                . '<th colspan="2" width="30%">Permanent Set’s Average Score</th>'
                . '<th colspan="2" width="30%">Contractor Set’s Average Score</th>'
                . '</tr>'
                . '<tr>'
                . '<td align="center">Last Year Actual (%)</td>'
                . '<td align="center">YTM This Year (%)</td>'
                . '<td align="center">Last Year Actual (%)</td>'
                . '<td align="center">YTM This Year (%)</td>'
                . '</tr>';
            foreach( $getsiteauditscore as $key => $rowdata ) {
                echo '<tr>'
                . '<td>'.$rowdata->name.'<input type="hidden" name="type3[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_3'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_3'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                . '<td align="center">'.$rowdata->pset_last_year.'<input value="'.$rowdata->pset_last_year.'" type="hidden" name="pset_last_year_'.$key.'" /></td>'
                . '<td align="center">'.$rowdata->pset_ytm_this_year.'<input value="'.$rowdata->pset_ytm_this_year.'" type="hidden" name="pset_ytm_this_year_'.$key.'" /></td>'
                . '<td align="center">'.$rowdata->cset_last_year.'<input value="'.$rowdata->cset_last_year.'" type="hidden" name="cset_last_year_'.$key.'" /></td>'
                . '<td align="center">'.$rowdata->cset_ytm_this_year.'<input value="'.$rowdata->cset_ytm_this_year.'" type="hidden" name="cset_ytm_this_year_'.$key.'" /></td>'
                . '</tr>';
            }
            echo  '</table>';
            
            echo '<h5 class="text-primary" style="text-decoration: underline; "><b>RESULTS : </b></h5>'
                . '<table class="table table-bordered">'
                . '<tr class="text-primary">'
                . '<th width="20%">Accident/Incident</th>'
                . '<th width="15%" colspan="3">Distribution</th>'
                . '<th width="15%" colspan="3">Generation</th>'
                . '</tr>'
                . '<tr class="text-primary">'
                . '<th width="20%">Type</th>'
                . '<th width="10%">Total in the Month</th>'
                . '<th width="10%">YTM Total</th>'
                . '<th width="10%">Last Year YTM</th>'
                . '<th width="10%">Total in the Month</th>'
                . '<th width="10%">YTM Total</th>'
                . '<th width="10%">Last Year YTM</th>'
                . '</tr>';
           
            foreach( $getaccident as $key => $rowdata ) {
                echo '<tr>'
                . '<td>'.$rowdata->name.''
                        . '<input type="hidden" name="type2[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_2'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="insteredrowid_'.$key.'" value="'.$rowdata->insteredrowid.'" />'
                        . '<input type="hidden" name="major_activity_id_2'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                . '<td align="center">'.$rowdata->total_in_this_month_d.'<input type="hidden" value="'.$rowdata->total_in_this_month_d.'" name="total_in_this_month_2'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->ytm_total_d.'<input type="hidden" value="'.$rowdata->ytm_total_d.'" name="ytm_total_2'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->last_year_ytm_d.'<input type="hidden" value="'.$rowdata->last_year_ytm_d.'" name="last_year_ytm_2'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->total_in_this_month_g.'<input type="hidden" value="'.$rowdata->total_in_this_month_g.'" name="total_in_this_month_22'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->ytm_total_g.'<input type="hidden" value="'.$rowdata->ytm_total_g.'" name="ytm_total_22'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->last_year_ytm_g.'<input type="hidden" value="'.$rowdata->last_year_ytm_g.'" name="last_year_ytm_22'.$key.'"  /></td>';                    
                echo '</tr>';
            }    
            echo  '</table>';
            
            echo '<table class="table table-bordered">';
            echo '<tr>'
                . '<td>Special Activities</td>'
                . '<td colspan="4">';
                if(@$rowsingledata[0]->status_id != 2) {
                    echo '<textarea style="width:100%;" name="special_activities">'.((@$rowsingledata[0]->special_activities) ? $rowsingledata[0]->special_activities : "").'</textarea>';                    
                } else {
                    echo '<pre>'.((@$rowsingledata[0]->special_activities) ? $rowsingledata[0]->special_activities : "").'</pre>';
                }
                echo '</td>'
                . '</tr>';
            echo  '</table>';
            
            echo  '<br><br><br><br>'
                . '<div><span style="float:right;text-align:center;">['.$safetymember[0]->name.']<br><b>'.$safetymember[0]->designation.'</b></span></div>'
                . '<div style="clear:both;"></div><br>'
                . '<div>';
                if(@$rowsingledata[0]->status_id != 2) {
                    echo '<textarea style="width:100%;" name="notes">'.((@$rowsingledata[0]->notes) ? $rowsingledata[0]->notes : "").'</textarea>';                    
                } else {
                    echo nl2br((@$rowsingledata[0]->notes) ? $rowsingledata[0]->notes : "");
                }
                echo '</div>'
                . '<div class="clearfix"></div>';
                
            echo '<br>';
            if(@$rowsingledata[0]->status_id != 2) {
            echo '<input type="hidden" name="_action" value="mdReportSubmit" />'
            . '<div style=""><input type="submit" name="B1" class="btn btn-info btn-sm" value="Save as draft" />'
                    
            . '<input type="submit" name="B2" class="btn btn-primary btn-sm" value="Submit" />'
                    . '<a href="mdReportlist.php" class="btn btn-danger btn-sm">Cancel</a>';
            }
            echo'</div>'
            . '</form>';
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
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css")?>"/>
<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js")?>"></script>

<script type="text/javascript">  
    
    jQuery.datetimepicker.setLocale('en');
    jQuery('.datepicker').datetimepicker({
        timepicker:false,
        scrollMonth : false,
        scrollInput : false,
        format:'d-m-Y',
        step:5
    });
    $(document).ready(function(){
        var submitdata = "<?php echo  $submitdata ;?>"; 
        var mnthyr = "<?php echo  $mnthyr ;?>"; 
        if(submitdata == 0 && mnthyr != "") {
        $("#mdform").submit();
        }
    });
    
    function PrintDiv()
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=300');
        var content = document.getElementById("divToPrint").innerHTML;
        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}table{width:100%;}</style></head><body>');
        mywindow.document.write(content);
        mywindow.document.write('</body></html>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/
        mywindow.print();
        mywindow.close();
        return true;
    }
 </script>
</body>
</html>
