<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$mnthyr = ($beanUi->get_view_data("mnthyr"));
$month_year = ($beanUi->get_view_data("month_year"));
$fetchalldata = ($beanUi->get_view_data("fetchalldata"));
$month_year = ($beanUi->get_view_data("month_year")) ? $beanUi->get_view_data("month_year") : $mnthyr ;
$rowsingledata = ($beanUi->get_view_data("rowsingledata"));
$submitdata = ($beanUi->get_view_data("submitdata") ? $beanUi->get_view_data("submitdata") : "");
$safetymember = ($beanUi->get_view_data("safetymember"));
$mode = ($beanUi->get_view_data("mode")) ? $beanUi->get_view_data("mode") : "";
$current_financial_year = ($beanUi->get_view_data("current_financial_year")) ? $beanUi->get_view_data("current_financial_year") : "";
$previous_financial_year = ($beanUi->get_view_data("previous_financial_year")) ? $beanUi->get_view_data("previous_financial_year") : "";
$CurrentFY = ($beanUi->get_view_data("CurrentFY")) ? $beanUi->get_view_data("CurrentFY") : "";
$PreviousFY = ($beanUi->get_view_data("PreviousFY")) ? $beanUi->get_view_data("PreviousFY") : "";
$controller->get_header();
$site_root_url = dirname(url());

$exp = explode("-", $current_financial_year);
if(is_array($exp) && count($exp)){
    $current_financial_year = @$exp[0].'-'.date("y", strtotime('01-01-'.@$exp[1]));
    
    
$majorActivitiesGenWCJPTSPC     = ( isset($fetchalldata[25]) ) ? $fetchalldata[25] : "";
$getsiteauditscore              = ( isset($fetchalldata[27]) ) ? $fetchalldata[27] : "";
$getaccident                    = ( isset( $fetchalldata[26] ) ) ? $fetchalldata[26] : "";
$majorActivitiesDistExHD        = ( isset($fetchalldata[28]) ) ? $fetchalldata[28] : "";
$majorActivitiesDistHD          = ( isset($fetchalldata[29]) ) ? $fetchalldata[29] : "";
$majorActivitiesDistMM          = ( isset($fetchalldata[30]) ) ? $fetchalldata[30] : "";
$majorActivitiesDistMHT         = ( isset($fetchalldata[31]) ) ? $fetchalldata[31] : "";
$majorActivitiesDistSC          = ( isset($fetchalldata[32]) ) ? $fetchalldata[32] : "";
$majorActivitiesDistSS          = ( isset($fetchalldata[33]) ) ? $fetchalldata[33] : "";
$majorActivitiesDistACG         = ( isset($fetchalldata[34]) ) ? $fetchalldata[34] : "";
$majorActivitiesDistTD          = ( isset($fetchalldata[35]) ) ? $fetchalldata[35] : "";
$getmajorActivitiesDistTA       = ( isset($fetchalldata[36]) ) ? $fetchalldata[36] : "";


}
?>
<style>
    .wrapper {padding:10px;border:1px solid #eee;}
    .text-heading {
        font-size:17px !important;
    }
</style>
<div class="container1">
    <?php echo $beanUi->get_message(); ?>
    <h1 class="heading">1. MD's Report 
        <?php echo  ( $mode == "view" ) ? '<a style="float: right;" href="mdReportlistN.php" class="btn btn-danger btn-xs">Back</a>' : ''; ?>
        </h1> 
   
    <div class="wrapper2">       
        
            
                <div class="holder" style="float:left;width:500px;">
                    
                    
                    <?php 
                        echo '<label for="job_stop_req_raisedx" style="width:30%;">Selected Month-Year :</label>';
                        echo '<span style="padding-top:3px;">'.$month_year.'</span>';
                    ?>
                </div>
             <?php if($mnthyr != '') { ?>
    <div class="print1" style="float:right;">
            <a class="btn btn-danger btn-sm" onclick="PrintDiv();"> <i class="fa fa-print"></i> Print / PDF</a>
        </div>
    <div class="clearfix"></div>
    <?php } ?>
                <hr class="no-margin"/>
           
        <div class="print-friendly" id="divToPrint">
        <?php   
//*************************************************Gen start**************************************************************        
        if (!empty($majorActivitiesGenWCJPTSPC)) {
            echo '<form method="post" action="">'
            . '<input type="hidden" name="month_year" id="month_year" value ="'.$month_year.'" style="width:250px;"  >';
            echo '<div class="wrapper">'
                 . '<div style="float:right;text-align:right;">';
            if($rowsingledata[0]->status_id == 1) {
                echo '<input type="text" name="report_date" class="datepicker" value="'.date("d-m-Y",strtotime($rowsingledata[0]->report_date)).'" style="width:250px;" />';
            } else if($rowsingledata[0]->status_id == 2) {
                echo date("dS F Y",strtotime($rowsingledata[0]->report_date));
                echo '<br><br><br><div style="font-weight:bold;">Through : ED ( HR & A )</div>';
            } else {
                echo '<input type="text" name="report_date" class="datepicker" value="" style="width:250px;" />';
            }
            echo    '</div>'
                    . '<div style="float:left;font-weight:bold;"><div style="margin-bottom:5px;">The Managing Director (Distribution)</div>The Managing Director (Generation)</span></div>'
                    . '<div style="clear:both;"></div>'
                    . '<br><div style="position:relative;"><div class="text-heading" style="text-align:center;font-weight:bold;text-transform:uppercase;font-size:0.85rem;"><u>SAFETY CELL REPORT FOR '.date("F Y",strtotime('01-'.$rowsingledata[0]->month_year)).'</u></div></div><br>'
                    . '<div class="text-primary text-heading" style="text-decoration: underline;margin-top:0;font-size:.85rem;margin-bottom:5px;"><b>1. GENERATION (BBGS & SGS) : </b></div>';
            echo '<div class="row"><div class="col-md-6">'
            . '<table class="table table-bordered">'
                    . '<tr class="text-primary">'
                    . '<th width="57.5%">ACTIVITIES </th>'
                    . '<th width="12%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
                    . '<th width="17%">YTM '.$CurrentFY.'</th>'
                    . '<th width="13.5%">Target '.$CurrentFY.'</th>'
//                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
                    . '</tr>';
            foreach( $majorActivitiesGenWCJPTSPC as $key => $rowdata ) {             
                echo '<tr>'
                        . '<td>'.$rowdata->name.'<input type="hidden" name="type1[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name[]" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                        . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="" /></td>'
//                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_'.$key.'" /></td>'   
                    . '</tr>';
            }
            
            echo '</table></div>';
            
            echo  '<div class="col-md-6">'
            . '<table class="table table-bordered" style="margin-bottom:0;">'
                . '<tr class="text-primary"><th colspan="4" style="height:25px;">AVERAGE JOB SITE AUDIT SCORE</th></tr>'
                . '<tr class="text-primary">'
                //. '<th rowspan="2" width="20%">Departments</th>'
                . '<th colspan="2" width="50%">Permanent Set</th>'
                . '<th colspan="2" width="50%">Contractor Set</th>'
                . '</tr>'
                . '<tr>'
                . '<td align="center">YTM '.$CurrentFY.'</td>'
                . '<td align="center" width="25%">FY '.$PreviousFY.'</td>'
                . '<td align="center" width="25%">YTM '.$CurrentFY.'</td>'
                . '<td align="center" width="25%">FY '.$PreviousFY.'</td>'
                . '</tr>';
            foreach( $getsiteauditscore as $key => $rowdata ) {
                if($rowdata->name == 'Generation'){
                echo '<tr>'
                //. '<td>'.$rowdata->name.'<input type="hidden" name="type3[]" value="'.$rowdata->type.'" />'
                . '<input type="hidden" name="type3[]" value="'.$rowdata->type.'" />'
                . '<input type="hidden" name="name_3'.$key.'" value="'.$rowdata->name.'" />'
                . '<input type="hidden" name="major_activity_id_3'.$key.'" value="'.$rowdata->id.'" />'
                //. '</td>'
                . '<td align="center">'.$rowdata->pset_ytm_this_year.'<input value="'.$rowdata->pset_ytm_this_year.'" type="hidden" name="pset_ytm_this_year_'.$key.'" /></td>'
                . '<td align="center">'.$rowdata->pset_last_year.'<input value="'.$rowdata->pset_last_year.'" type="hidden" name="pset_last_year_'.$key.'" /></td>'
                . '<td align="center">'.$rowdata->cset_ytm_this_year.'<input value="'.$rowdata->cset_ytm_this_year.'" type="hidden" name="cset_ytm_this_year_'.$key.'" /></td>'
                . '<td align="center">'.$rowdata->cset_last_year.'<input value="'.$rowdata->cset_last_year.'" type="hidden" name="cset_last_year_'.$key.'" /></td>'
                
                . '</tr>';
                }
            }
            echo  '</table>';
            
            echo '<table class="table table-bordered">'
//                . '<tr class="text-primary">'
//                . '<th width="20%">INCIDENT</th>'
//                . '<th width="15%" colspan="3">Distribution</th>'
//                . '<th width="15%" colspan="3">Generation</th>'
//                . '</tr>'
                . '<tr class="text-primary">'
                . '<th width="34%">INCIDENT</th>'
//                . '<th width="10%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
//                . '<th width="10%">YTM '.$CurrentFY.'</th>'
//                . '<th width="10%">YTM '.$PreviousFY.'</th>'
                . '<th width="16%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
                . '<th width="25%">YTM '.$CurrentFY.'</th>'
                . '<th width="25%">YTM '.$PreviousFY.'</th>'
                . '</tr>';
           
            foreach( $getaccident as $key => $rowdata ) {
                    if($rowdata->total_in_this_month_g != ""){
                echo '<tr>'
                . '<td>'.$rowdata->name.''
                        . '<input type="hidden" name="type2[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_2'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="insteredrowid_'.$key.'" value="'.@$rowdata->insteredrowid.'" />'
                        . '<input type="hidden" name="major_activity_id_2'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
//                . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_2'.$key.'"  /></td>'
//                . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_2'.$key.'"  /></td>'
//                . '<td align="center">'.$rowdata->last_year_ytm.'<input type="hidden" value="'.$rowdata->last_year_ytm.'" name="last_year_ytm_2'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->total_in_this_month_g.'<input type="hidden" value="'.$rowdata->total_in_this_month_g.'" name="total_in_this_month_22'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->ytm_total_g.'<input type="hidden" value="'.$rowdata->ytm_total_g.'" name="ytm_total_22'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->last_year_ytm_g.'<input type="hidden" value="'.$rowdata->last_year_ytm_g.'" name="last_year_ytm_22'.$key.'"  /></td>';                    
                echo '</tr>';
                }
            }    
            echo  '</table>'
            . '</div></div>';
//*********************************************************Gen end***********************************************************
//            ******************************distExHD**************************
            echo '<div class="text-primary text-heading" style="text-decoration: underline; margin:5px 0 5px;font-size:.85rem;"><b>2. DISTRIBUTION : </b></div><div style="clear:both;"></div>';
            echo '<table class="table table-bordered" style="margin-bottom:0;float:left;width:46%;">'
                    . '<tr class="text-primary">'
                    . '<th width="58%">ACTIVITIES</th>'
                    . '<th width="14%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
                    . '<th width="14%">YTM '.$CurrentFY.'</th>'
                    . '<th width="14%">Target '.$CurrentFY.'</th>'
//                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
                    . '</tr>'
                    . '<tr class="text-primary">'
                    . '<th>Mains Department</th>'
                    . '<th colspan="3">Mains - LT excl. HD</th>'
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
//                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_DistExHD_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>';
//            ******************************distExHD**************************
//            ******************************distHD**************************
            echo '<table class="table table-bordered" style="margin-bottom:0;float:left;width:18%;">'
                    . '<tr class="text-primary">'
                    //. '<th width="40%">Howrah Model District</th>'
                    . '<th width="33.333%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
                    . '<th width="33.333%">YTM '.$CurrentFY.'</th>'
                    . '<th width="33.333%">Target '.$CurrentFY.'</th>'
//                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
                    . '</tr>'
                    . '<tr class="text-primary">'
                    //. '<th width="40%">Mains Department</th>'
                    . '<th colspan="3">Howrah Model District</th>'
                    . '</tr>';
            foreach( $majorActivitiesDistHD as $key => $rowdata ) {             
                echo '<tr>'
                       // . '<td>'.$rowdata->name.'<input type="hidden" name="type5[]" value="'.$rowdata->type.'" />'
                        .'<input type="hidden" name="type5[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_distHD_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_distHD_'.$key.'" value="'.$rowdata->id.'" />'
                        //. '</td>'
                        . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_distHD_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_distHD_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_distHD_'.$key.'" /></td>'
//                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_distHD_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>';
//            ******************************distHD************************** 
//            ******************************distMM************************** 
            
            echo '<table class="table table-bordered" style="margin-bottom:0;float:left;width:18%;">'
                    . '<tr class="text-primary">'
                    //. '<th width="40%">Mains Maintenance</th>'
                    . '<th width="33.333%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
                    . '<th width="33.333%">YTM '.$CurrentFY.'</th>'
                    . '<th width="33.333%">Target '.$CurrentFY.'</th>'
//                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
                    . '</tr>'
                    . '<tr class="text-primary">'
                    //. '<th width="40%">Mains Department</th>'
                    . '<th width="15%" colspan="3">Mains Maintenance</th>'
                    . '</tr>';
            foreach( $majorActivitiesDistMM as $key => $rowdata ) {             
                echo '<tr>'
                        //. '<td>'.$rowdata->name.'<input type="hidden" name="type6[]" value="'.$rowdata->type.'" />'
                        .'<input type="hidden" name="type6[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_distMSTAST_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_distMSTAST_'.$key.'" value="'.$rowdata->id.'" />'
                        //. '</td>'
                        . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_distMSTAST_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_distMSTAST_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_distMSTAST_'.$key.'" /></td>'
//                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_distMSTAST_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>';
            
//            ******************************distMM************************** 
//            ******************************distMHT************************** 
            
            echo '<table class="table table-bordered" style="margin-bottom:0;float:left;width:18%;">'
                    . '<tr class="text-primary">'
                    //. '<th width="40%">Mains Maintenance</th>'
                    . '<th width="33.333%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
                    . '<th width="33.333%">YTM '.$CurrentFY.'</th>'
                    . '<th width="33.333%">Target '.$CurrentFY.'</th>'
//                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
                    . '</tr>'
                    . '<tr class="text-primary">'
                    //. '<th width="40%">Mains Department</th>'
                    . '<th colspan="3">Mains - HT(*)</th>'
                    . '</tr>';
            foreach( $majorActivitiesDistMHT as $key => $rowdata ) {             
                echo '<tr>'
                        //. '<td>'.$rowdata->name.'<input type="hidden" name="type6[]" value="'.$rowdata->type.'" />'
                        .'<input type="hidden" name="type6[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_distMSTAST_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_distMSTAST_'.$key.'" value="'.$rowdata->id.'" />'
                       // . '</td>'
                        . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_distMSTAST_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_distMSTAST_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_distMSTAST_'.$key.'" /></td>'
//                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_distMSTAST_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>'
                  . '<div style="clear:both;"></div>'            ;
            
//            ******************************distMHT************************** 
//            ******************************distSC**************************
            echo '<table class="table table-bordered" style="float:left;width:46%;">'
//                    . '<tr class="text-primary">'
//                    . '<th width="55%">ACTIVITIES</th>'
//                    . '<th width="15%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
//                    . '<th width="15%">YTM '.$CurrentFY.'</th>'
//                    . '<th width="15%">Target '.$CurrentFY.'</th>'
//                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
//                    . '</tr>'
                    . '<tr class="text-primary">'
                    . '<th width="58%">Other Departments</th>'
                    . '<th colspan="3">System Control</th>'
                    . '</tr>';
            foreach( $majorActivitiesDistSC as $key => $rowdata ) {           
                echo '<tr>'
                        . '<td>'.$rowdata->name.'<input type="hidden" name="type4[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_DistExHD_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_DistExHD_'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                        . '<td align="center" width="14%">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_DistExHD_'.$key.'" /></td>'
                        . '<td align="center" width="14%">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_DistExHD_'.$key.'" /></td>'
                        . '<td align="center" width="14%">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_DistExHD_'.$key.'" /></td>'
//                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_DistExHD_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>';
//            ******************************distSC**************************
//            ******************************distSS**************************
            echo '<table class="table table-bordered" style="float:left;width:18%;">'
//                    . '<tr class="text-primary">'
                    //. '<th width="40%">Howrah Model District</th>'
//                    . '<th width="33.333%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
//                    . '<th width="33.333%">YTM '.$CurrentFY.'</th>'
//                    . '<th width="33.333%">Target '.$CurrentFY.'</th>'
//                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
//                    . '</tr>'
                    . '<tr class="text-primary">'
                    //. '<th width="40%">Mains Department</th>'
                    . '<th colspan="3">Substations</th>'
                    . '</tr>';
            foreach( $majorActivitiesDistSS as $key => $rowdata ) {             
                echo '<tr>'
                       // . '<td>'.$rowdata->name.'<input type="hidden" name="type5[]" value="'.$rowdata->type.'" />'
                        .'<input type="hidden" name="type5[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_distHD_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_distHD_'.$key.'" value="'.$rowdata->id.'" />'
                        //. '</td>'
                        . '<td align="center" width="33.333%">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_distHD_'.$key.'" /></td>'
                        . '<td align="center" width="33.333%">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_distHD_'.$key.'" /></td>'
                        . '<td align="center" width="33.333%">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_distHD_'.$key.'" /></td>'
//                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_distHD_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>';
//            ******************************distSS************************** 
//            ******************************distACG************************** 
            
            echo '<table class="table table-bordered" style="float:left;width:18%;">'
//                    . '<tr class="text-primary">'
                    //. '<th width="40%">Mains Maintenance</th>'
//                    . '<th width="33.333%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
//                    . '<th width="33.333%">YTM '.$CurrentFY.'</th>'
//                    . '<th width="33.333%">Target '.$CurrentFY.'</th>'
//                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
//                    . '</tr>'
                    . '<tr class="text-primary">'
                    //. '<th width="40%">Mains Department</th>'
                    . '<th width="15%" colspan="3">ACG</th>'
                    . '</tr>';
            foreach( $majorActivitiesDistACG as $key => $rowdata ) {             
                echo '<tr>'
                        //. '<td>'.$rowdata->name.'<input type="hidden" name="type6[]" value="'.$rowdata->type.'" />'
                        .'<input type="hidden" name="type6[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_distMSTAST_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_distMSTAST_'.$key.'" value="'.$rowdata->id.'" />'
                        //. '</td>'
                        . '<td align="center" width="33.333%">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_distMSTAST_'.$key.'" /></td>'
                        . '<td align="center" width="33.333%">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_distMSTAST_'.$key.'" /></td>'
                        . '<td align="center" width="33.333%">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_distMSTAST_'.$key.'" /></td>'
//                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_distMSTAST_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>';
            
//            ******************************distACG**************************             
//            ******************************distTD************************** 
            
            echo '<table class="table table-bordered" style="float:left;width:18%;">'
//                    . '<tr class="text-primary">'
                    //. '<th width="40%">Mains Maintenance</th>'
//                    . '<th width="33.333%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
//                    . '<th width="33.333%">YTM '.$CurrentFY.'</th>'
//                    . '<th width="33.333%">Target '.$CurrentFY.'</th>'
//                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
//                    . '</tr>'
                    . '<tr class="text-primary">'
                    //. '<th width="40%">Mains Department</th>'
                    . '<th colspan="3">Testing</th>'
                    . '</tr>';
            foreach( $majorActivitiesDistTD as $key => $rowdata ) {             
                echo '<tr>'
                        //. '<td>'.$rowdata->name.'<input type="hidden" name="type6[]" value="'.$rowdata->type.'" />'
                        .'<input type="hidden" name="type6[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_distMSTAST_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_distMSTAST_'.$key.'" value="'.$rowdata->id.'" />'
                       // . '</td>'
                        . '<td align="center" width="33.333%">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_distMSTAST_'.$key.'" /></td>'
                        . '<td align="center" width="33.333%">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_distMSTAST_'.$key.'" /></td>'
                        . '<td align="center" width="33.333%">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_distMSTAST_'.$key.'" /></td>'
//                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_distMSTAST_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>'
                  . '<div style="clear:both;"></div>'            ;
            
//            ******************************distTD**************************             
            

            echo  '<div class="row"><div class="col-md-6">'
            . '<table class="table table-bordered" style="margin-bottom:5px;">'
                . '<tr class="text-primary"><th colspan="5">AVERAGE JOB SITE AUDIT SCORE</th></tr>'
                . '<tr class="text-primary">'
                . '<th rowspan="2" width="36%">Departments</th>'
                . '<th colspan="2">Permanent Set</th>'
                . '<th colspan="2">Contractor Set</th>'
                . '</tr>'
                . '<tr>'
                . '<td align="center" width="16%">YTM '.$CurrentFY.'</td>'
                . '<td align="center" width="16%">FY '.$PreviousFY.'</td>'
                . '<td align="center" width="16%">YTM '.$CurrentFY.'</td>'
                . '<td align="center" width="16%">FY '.$PreviousFY.'</td>'
                . '</tr>';
            foreach( $getsiteauditscore as $key => $rowdata ) {
                if($rowdata->name != 'Generation'){
                echo '<tr>'
                . '<td>'.$rowdata->name.'<input type="hidden" name="type3[]" value="'.$rowdata->type.'" />'
                . '<input type="hidden" name="name_3'.$key.'" value="'.$rowdata->name.'" />'
                . '<input type="hidden" name="major_activity_id_3'.$key.'" value="'.$rowdata->id.'" />'
                . '</td>'
                . '<td align="center">'.$rowdata->pset_ytm_this_year.'<input value="'.$rowdata->pset_ytm_this_year.'" type="hidden" name="pset_ytm_this_year_'.$key.'" /></td>'
                . '<td align="center">'.$rowdata->pset_last_year.'<input value="'.$rowdata->pset_last_year.'" type="hidden" name="pset_last_year_'.$key.'" /></td>'
                . '<td align="center">'.$rowdata->cset_ytm_this_year.'<input value="'.$rowdata->cset_ytm_this_year.'" type="hidden" name="cset_ytm_this_year_'.$key.'" /></td>'
                . '<td align="center">'.$rowdata->cset_last_year.'<input value="'.$rowdata->cset_last_year.'" type="hidden" name="cset_last_year_'.$key.'" /></td>'
                . '</tr>';
                }
            }
            echo  '</table>'
//                . '<p style="font-size:10px;margin-bottom:0px;">(*) Activities are conducted by HT Section </p>'
                . '</div>';
            
            echo '<div class="col-md-6">'
                . '<table class="table table-bordered">'
//                . '<tr class="text-primary">'
//                . '<th width="20%">INCIDENT</th>'
//                . '<th width="15%" colspan="3">Distribution</th>'
//                . '<th width="15%" colspan="3">Generation</th>'
//                . '</tr>'
                . '<tr class="text-primary">'
                . '<th width="20%">INCIDENT</th>'
                . '<th width="10%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
                . '<th width="10%">YTM '.$CurrentFY.'</th>'
                . '<th width="10%">YTM '.$PreviousFY.'</th>'
//                . '<th width="10%">Total in the Month</th>'
//                . '<th width="10%">YTM Total</th>'
//                . '<th width="10%">Last Year YTM</th>'
                . '</tr>';
           
            foreach( $getaccident as $key => $rowdata ) {
                if($rowdata->total_in_this_month_g == ""){
                echo '<tr>'
                . '<td>'.$rowdata->name.''
                        . '<input type="hidden" name="type2[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_2'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="insteredrowid_'.$key.'" value="'.@$rowdata->insteredrowid.'" />'
                        . '<input type="hidden" name="major_activity_id_2'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_2'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_2'.$key.'"  /></td>'
                . '<td align="center">'.$rowdata->last_year_ytm.'<input type="hidden" value="'.$rowdata->last_year_ytm.'" name="last_year_ytm_2'.$key.'"  /></td>';
//                . '<td align="center">'.$rowdata->total_in_this_month_g.'<input type="hidden" value="'.$rowdata->total_in_this_month_g.'" name="total_in_this_month_22'.$key.'"  /></td>'
//                . '<td align="center">'.$rowdata->ytm_total_g.'<input type="hidden" value="'.$rowdata->ytm_total_g.'" name="ytm_total_22'.$key.'"  /></td>'
//                . '<td align="center">'.$rowdata->last_year_ytm_g.'<input type="hidden" value="'.$rowdata->last_year_ytm_g.'" name="last_year_ytm_22'.$key.'"  /></td>';                    
                echo '</tr>';
                }
            }    
            echo  '</table>';
            
            
//            ******************************DistTA start**************************  

            echo '<table class="table table-bordered">'
                    . '<tr class="text-primary">'
                    . '<th width="40%">TRAINING</th>'
                    . '<th width="15%">'.date("M-y",strtotime('01-'.$rowsingledata[0]->month_year)).'</th>'
                    . '<th width="15%">YTM '.$CurrentFY.'</th>'
                    . '<th width="15%">Target '.$CurrentFY.'</th>'
//                    . '<th width="15%">Actual '.$previous_financial_year.'</th>'
                    . '</tr>';
            foreach( $getmajorActivitiesDistTA as $key => $rowdata ) {            
                echo '<tr>'
                        . '<td>'.$rowdata->name.'<input type="hidden" name="type7[]" value="'.$rowdata->type.'" />'
                        . '<input type="hidden" name="name_Gen_'.$key.'" value="'.$rowdata->name.'" />'
                        . '<input type="hidden" name="major_activity_id_Gen_'.$key.'" value="'.$rowdata->id.'" />'
                        . '</td>'
                        . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_Gen_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_Gen_'.$key.'" /></td>'
                        . '<td align="center">'.$rowdata->target.'<input type="hidden" value="'.$rowdata->target.'" name="target_Gen_'.$key.'" /></td>'
//                        . '<td align="center">'.$rowdata->last_year_total.'<input type="hidden" value="'.$rowdata->last_year_total.'" name="last_year_total_Gen_'.$key.'" /></td>'
                    . '</tr>';
            }
            echo '</table>'
            . '</div></div>';
           
 //            ******************************DistTA end************************** 
            echo '<p style="margin-bottom:0px;">(*) Activities are conducted by HT Section </p>';
            echo '<p style="margin-bottom:5px;">Note: Job Site Audit Score Sheet for Mains Department has been changed with effect from 01.04.2019.</p>';
            echo '<table class="table table-bordered spl-table">';
            echo '<tr>'
                . '<td style="width:20%">Special Activities</td>'
                . '<td colspan="4">';
                if(@$rowsingledata[0]->status_id != 2) {
                    echo '<textarea style="width:100%;" name="special_activities">'.((@$rowsingledata[0]->special_activities) ? $rowsingledata[0]->special_activities : "").'</textarea>';                    
                } else {
                    echo '<p>'.((@$rowsingledata[0]->special_activities) ? $rowsingledata[0]->special_activities : "").'</p>';
                }
                echo '</td>'
                . '</tr>';
            echo  '</table>';
            
//            echo '<h5 class="text-primary" style="text-decoration: underline; "><b>RESULTS : </b></h5>'
//                . '<table class="table table-bordered">'
//                . '<tr class="text-primary">'
//                . '<th width="20%">Type</th>'
//                . '<th width="15%">Total in the Month</th>'
//                . '<th width="15%">YTM Total</th>'
//                . '<th width="15%">Last Year YTM</th>'
//                . '<th width="15%">Remarks</th>'
//                . '</tr>';
//           
//            foreach( array_values($getaccident) as $key => $rowdata ) {
//                echo '<tr>'
//                . '<td>'.$rowdata->name.''
//                        . '<input type="hidden" name="type2[]" value="'.$rowdata->type.'" />'
//                        . '<input type="hidden" name="name[]" value="'.$rowdata->name.'" />'
//                        . '<input type="hidden" name="insertedrowid_'.$key.'" value="'.$rowdata->id.'" />'
//                        . '<input type="hidden" name="major_activity_id_2'.$key.'" value="'.$rowdata->id.'" />'
//                        . '</td>'
//                . '<td align="center">'.$rowdata->total_in_this_month.'<input type="hidden" value="'.$rowdata->total_in_this_month.'" name="total_in_this_month_2'.$key.'"  /></td>'
//                . '<td align="center">'.$rowdata->ytm_total.'<input type="hidden" value="'.$rowdata->ytm_total.'" name="ytm_total_2'.$key.'"  /></td>'
//                . '<td align="center">'.$rowdata->last_year_ytm.'<input type="hidden" value="'.$rowdata->last_year_ytm.'" name="last_year_ytm_2'.$key.'"  /></td>'
//                . '<td align="center">';
//                if($rowsingledata[0]->status_id == 2) {
//                    echo $rowdata->remarks;
//                } else {
//                    echo '<input style="width:100%;" type="text" value="'.$rowdata->remarks.'" name="remarks_'.$key.'"  /></td>';
//                }
//                    
//                echo '</tr>';
//            }
//            echo  '</table>';
            
//            echo '<table class="table table-bordered">'
//                . '<tr class="text-primary">'
//                . '<th rowspan="2" width="20%">Type</th>'
//                . '<th colspan="2" width="30%">Permanent Set’s Average Score</th>'
//                . '<th colspan="2" width="30%">Contractor Set’s Average Score</th>'
//                . '</tr>'
//                . '<tr>'
//                . '<td align="center">Last Year’s (%)</td>'
//                . '<td align="center">YTM This Year (%)</td>'
//                . '<td align="center">Last Year’s (%)</td>'
//                . '<td align="center">YTM This Year (%)</td>'
//                . '</tr>';
//            foreach( $getsiteauditscore as $key => $rowdata ) {
//                echo '<tr>'
//                . '<td>'.$rowdata->name.'<input type="hidden" name="type3[]" value="'.$rowdata->type.'" />'
//                        . '<input type="hidden" name="name[]" value="'.$rowdata->name.'" />'
//                        . '<input type="hidden" name="major_activity_id_3'.$key.'" value="'.$rowdata->id.'" />'
//                        . '</td>'
//                . '<td align="center">'.$rowdata->pset_last_year.'<input value="'.$rowdata->pset_last_year.'" type="hidden" name="pset_last_year_'.$key.'" /></td>'
//                . '<td align="center">'.$rowdata->pset_ytm_this_year.'<input value="'.$rowdata->pset_ytm_this_year.'" type="hidden" name="pset_ytm_this_year_'.$key.'" /></td>'
//                . '<td align="center">'.$rowdata->cset_last_year.'<input value="'.$rowdata->cset_last_year.'" type="hidden" name="cset_last_year_'.$key.'" /></td>'
//                . '<td align="center">'.$rowdata->cset_ytm_this_year.'<input value="'.$rowdata->cset_ytm_this_year.'" type="hidden" name="cset_ytm_this_year_'.$key.'" /></td>'
//                . '</tr>';
//            }
//            echo  '</table>';
            
            echo  '<br><br><br><br>'
                . '<div><span style="float:right;text-align:center;">['.$safetymember[0]->name.']<br><b>'.$safetymember[0]->designation.'</b></span></div>'
                . '<div style="clear:both;"></div><br>'
                . '<div class="note">';
                if($rowsingledata[0]->status_id != 2) {
                    echo '<textarea style="width:100%;" name="notes">'.(($rowsingledata[0]->notes) ? $rowsingledata[0]->notes : "").'</textarea>';                    
                } else {
                    echo nl2br(($rowsingledata[0]->notes) ? $rowsingledata[0]->notes : "");
                }
                echo '</div>'
                . '<div class="clearfix"></div>';
                
            echo '<br>';
            if($rowsingledata[0]->status_id != 2) {
            echo '<input type="hidden" name="_action" value="mdReportSubmit" />'
            . '<div style=""><input type="submit" name="B1" class="btn btn-info btn-sm" value="Save as draft" />'
                    
            . '<input type="submit" name="B2" class="btn btn-primary btn-sm" value="Submit" />'
                    . '<a href="mdReportlistNew.php" class="btn btn-danger btn-sm">Cancel</a>';
            }
            echo'</div>'
            . '</form>';
        }
        ?>
            <div> <?php // echo CURRENT_DATE_TIME; ?> </div>
    </div>    
    </div>
</div>
<?php $controller->get_footer(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css")?>"/>
<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js")?>"></script>

<script>
 
jQuery.datetimepicker.setLocale('en');
jQuery('.datepicker').datetimepicker({
    
        timepicker:false,
	scrollMonth : false,
	scrollInput : false,
	format:'d-m-Y',
	step:5
});
</script>
<script type="text/javascript">  
    function PrintDiv()
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=300');
        var content = document.getElementById("divToPrint").innerHTML;
        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('<style>@page{padding:0;-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);margin:0px 25px;padding:30px 0;}body{font-size:12px !important;font-family:Arial !important;}.row{margin-left:-8px;margin-right:-8px;}.row:before{content:"";display:table;}.row:after{content:"";display:table;clear:both;}.col-md-6 {box-sizing:border-box;width:50%;padding-left:8px;padding-right:8px;float:left;}table{border-collapse:collapse;margin-bottom:5px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;font-size:11px !important;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}table{width:100%;}textarea{white-space: pre-wrap;}h5{font-size:15px;margin-bottom:5px;}.spl-table, .spl-table td{border:0;}.spl-table td:nth-child(1), .note{display:none;}body{padding:60px 0 40px;}h5{magin-top:0;padding-top:0;}</style></head><body>');
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
