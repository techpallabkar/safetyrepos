<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$mnthyr = ($beanUi->get_view_data("mnthyr"));
$month_year = ($beanUi->get_view_data("month_year"));
$fetchAllData = ($beanUi->get_view_data("fetchalldata"));
$month_year = ($beanUi->get_view_data("month_year")) ? $beanUi->get_view_data("month_year") : $mnthyr ;
$rowsingledata = ($beanUi->get_view_data("rowsingledata"));
$submitdata = ($beanUi->get_view_data("submitdata") ? $beanUi->get_view_data("submitdata") : "");
$safetymember = ($beanUi->get_view_data("safetymember"));
$mode = ($beanUi->get_view_data("mode")) ? $beanUi->get_view_data("mode") : "";
$current_financial_year = ($beanUi->get_view_data("current_financial_year")) ? $beanUi->get_view_data("current_financial_year") : "";

$getDate                  =   $beanUi->get_view_data("getDate");
$month_year_formatted     =   date("M y", strtotime("01-" . $month_year));
$previous_financial_year  =   @$getDate["PREV_YTM"];
$exp_prev_fy_year         =   explode("-", $previous_financial_year);
$prevfyyear = $exp_prev_fy_year[0] . '-' . date("y", strtotime($exp_prev_fy_year[1] . "-01-01"));
$present_financial_year =@$getDate["CURR_YTM"];     
$exp_pres_fy_year         =   explode("-", $present_financial_year);
@$presfyyear               =  $exp_pres_fy_year[0]. '-' . date("y", strtotime($exp_pres_fy_year[1] . "-01-01"));
$controller->get_header();
$site_root_url = dirname(url());

?>
<style>
    .wrapper {padding:10px;border:1px solid #eee;}
</style>
<div class="container1">
    <?php echo $beanUi->get_message(); ?>
    <h1 class="heading">20.Statistics  for MRM ( Distribution )
        <?php echo  ( $mode == "view" ) ? '<a style="float: right;" href="mrmDistributionlist.php" class="btn btn-danger btn-xs">Back</a>' : ''; ?>
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
                
          <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel</button> 
        <div class="print-friendly" id="divToPrint">
            <form method="post" autocomplete="off"> 
         <?php if (!empty($fetchAllData)) { ?>
    
     <div id='TBL_EXPORT' style="overflow:scroll;height: 630px;">
        <div class="print-friendly" id="divToPrint">   
            <div class="table-border">
            <table border="1" cellspacing="0" class="table table-bordered table-condensed table-responsive totaldata" style="font-size:13px;font-weight:bold;" >
                <thead class="bg-primary">
                    <tr><th colspan="24">SITE SAFETY SURVEILLANCE OF PERMANENT SET</th></tr>
                    <tr>
                        <th rowspan="3" width="5%">SL</th>
                        <th rowspan="3" width="15%"> ENGINEERS NAME </th>
                        <th rowspan="3" width="15%"> DEPT. </th>
                       
                        <th  colspan="9" width="10%"> AVG SCORE OF AUDITS IN <?php echo $month_year_formatted; ?></th>
                        <th  colspan="9" width="10%"> <?php echo $presfyyear; ?> YTM </th>
                        <th  colspan="4" width="10%"> INCIDENT INVOLVING P & C SET EMPLOYEES ONLY</th>
                    </tr>
                    <tr>
                        <th colspan="2">P SET</th>
                        <th colspan="2">CC</th>
                        <th colspan="2">C SET</th>
                        <th colspan="2">OVERALL</th>
                        <th rowspan="2">OTHER ACTIVITIES</th>
                        <th colspan="2">P SET</th>
                        <th colspan="2">CC</th>
                        <th colspan="2">C SET</th>
                        <th colspan="2">OVERALL</th>
                        <th rowspan="2">OTHER ACTIVITIES</th>
                        <th width="10%" rowspan="2"><?php echo $month_year_formatted; ?></th>
                        <th width="10%" rowspan="2"><?php echo $presfyyear; ?> YTM</th>
                        <th width="10%" rowspan="2"><?php echo $prevfyyear; ?></th>
                    </tr>
                    <tr>
                      
                        <th width="10%">NO OF AUDIT</th>
                        <th width="10%">SCORE</th>
                        <th width="10%">NO OF AUDIT</th>
                        <th width="10%">SCORE</th>
                        <th width="10%">NO OF AUDIT</th>
                        <th width="10%">SCORE</th>
                         <th width="10%">NO OF AUDIT</th>
                        <th width="10%">SCORE</th>
<!--                        <th width="10%">OTHER ACTIVITIES</th>-->
                        <th width="10%">NO OF AUDIT</th>
                        <th width="10%">SCORE</th>
                        <th width="10%">NO OF AUDIT</th>
                        <th width="10%">SCORE</th>
                        <th width="10%">NO OF AUDIT</th>
                        <th width="10%">SCORE</th>
                         <th width="10%">NO OF AUDIT</th>
                        <th width="10%">SCORE</th>
                        
                    </tr>
               
                </thead>
                <tbody>
                    <?php
                    
                    $total1 = $total5 = $total7 = $total10 = $total14 =  $total16 = $total18 = $total19 = $total20 = 0;
                    echo '<input type="hidden" name="month_year" value="'.$month_year.'" />';
                    foreach ($fetchAllData as $key => $rowdata) {
                        @$val1= ($rowdata->cm_pset_audit_count);
                        @$val2= ($rowdata->cm_pset_avg);
                        @$val3= ($rowdata->cm_cc_audit_count);
                        @$val4= ($rowdata->cm_cc_avg);
                        @$val5= ($rowdata->cm_cset_audit_count);
                        @$val6= ($rowdata->cm_cset_avg);
                        @$val7 = $rowdata->cm_ovr_audit_count;
                        @$val8 = $rowdata->cm_ovr_avg;
                        @$val9 = $rowdata->other_activities;
                        @$val10 = ($rowdata->cytm_pset_audit_count);
                        @$val11 = ($rowdata->cytm_pset_avg);
                        @$val12 = $rowdata->cytm_cc_audit_count;
                        @$val13 = $rowdata->cytm_cc_avg;
                        @$val14 = ($rowdata->cytm_cset_audit_count);
                        @$val15 = ($rowdata->cytm_cset_avg);
                        @$val16 = $rowdata->cytm_ovr_audit_count;
                        @$val17 = $rowdata->cytm_ovr_avg;
                        @$val21 = $rowdata->other_activities1;
                        @$val18 = ($rowdata->inc_cm);
                        @$val19 = ($rowdata->inc_cytm);
                        @$val20 = ($rowdata->inc_pfy);
                        echo '<tr>'
                        . '<td align="center" style="border:1px solid #000;"><input type="hidden" name="rowcount[]" value="'.$key.'"/><input type="hidden" name="rowid_'.$key.'" value="'.$rowdata->id.'"/>' . ($key + 1) . '.</td>'
                        . '<td bgcolor="#B9CDE5" style="border:1px solid #000;" bgcolor="#B9CDE5" >'.(($rowsingledata[0]->status_id != 2) ?  '<input type="text" style="width:100%;" name="engineer_name_'.$key.'" value="'.$rowdata->engineer_name.'"  />' : $rowdata->engineer_name).'</td>'
                        . '<td bgcolor="#B9CDE5" style="border:1px solid #000;" bgcolor="#B9CDE5" >' . (@$rowdata->district) . '</td>'
                        . '<td align="center" bgcolor="#e3ff96" style="border:1px solid #000;">' . @$val1. '</td>'
                        . '<td align="center" bgcolor="#e3ff96" style="border:1px solid #000;">' . @$val2 . '</td>'
                        . '<td align="center" bgcolor="#e0eeff" style="border:1px solid #000;">'.$val3.'</td>'
                        . '<td align="center" bgcolor="#e0eeff" style="border:1px solid #000;">'.$val4.'</td>'
                        . '<td align="center" bgcolor="#feccff" style="border:1px solid #000;">' . @$val5 . '</td>'
                        . '<td align="center" bgcolor="#feccff" style="border:1px solid #000;">' . @$val6 . '</td>'
                        . '<td align="center" bgcolor="#C5E0B4" style="border:1px solid #000;">'. @$val7.'</td>'
                                . '<td align="center" bgcolor="#C5E0B4" style="border:1px solid #000;">'.@$val8.'</td>'
                                . '<td align="center" bgcolor="#fff" style="border:1px solid #000;">'.(($rowsingledata[0]->status_id != 2) ?  '<input type="text" style="width:100%;" name="other_activities_'.$key.'" value="'.$rowdata->other_activities.'"   />' : $rowdata->other_activities).'</td>'
                                . '<td align="center" bgcolor="#e3ff96" style="border:1px solid #000;">'.@$val10.'</td>'
                                . '<td align="center" bgcolor="#e3ff96" style="border:1px solid #000;">'.@$val11.'</td>'
                                . '<td align="center" bgcolor="#e0eeff" style="border:1px solid #000;">'.@$val12.'</td>'
                                . '<td align="center" bgcolor="#e0eeff" style="border:1px solid #000;">'.@$val13.'</td>'
                                . '<td align="center" bgcolor="#feccff" style="border:1px solid #000;">'.@$val14.'</td>'
                                . '<td align="center" bgcolor="#feccff" style="border:1px solid #000;">'.@$val15.'</td>'
                                . '<td align="center" bgcolor="#C5E0B4" style="border:1px solid #000;">'.@$val16.'</td>'
                                . '<td align="center" bgcolor="#C5E0B4" style="border:1px solid #000;">'.@$val17.'</td>'
                                . '<td align="center" bgcolor="#fff" style="border:1px solid #000;">'.(($rowsingledata[0]->status_id != 2) ?  '<input type="text" style="width:100%;" name="other_activities1_'.$key.'" value="'.$rowdata->other_activities1.'"   />' : $rowdata->other_activities1).'</td>'
                                . '<td align="center" bgcolor="#f0ad4e" style="border:1px solid #000;">'.@$val18.'</td>'
                                . '<td align="center" bgcolor="#f0ad4e" style="border:1px solid #000;">'.@$val19.'</td>'
                                . '<td align="center" bgcolor="#f0ad4e" style="border:1px solid #000;">'.@$val20.'</td>';
                        echo '</tr>';
                       @$total1 +=$val1;
                       @$total2 +=$val3;
                       @$total5 +=$val5;
                       @$total7 +=$val7;
                       @$total10 +=$val10;
                       @$total14 +=$val14;
                       @$total16 +=$val16;
                       @$total18 +=$val18;
                       @$total19 +=$val19;
                       @$total20 +=$val20;
                       @$total21 +=$val12;
                    }
                    ?>
                    <tr  style="font-size: 16px;background:#ffdece;" class="total"> 
                        <td align="center" style="border-color:#000;" colspan="3"> TOTAL </td>
                        <td align="center" style="border-color:#000;"><?php echo $total1; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo ''; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo $total2; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo ''; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo $total5; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo ''; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo $total7; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo ''; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo ''; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo $total10; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo ''; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo $total21; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo ''; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo $total14; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo ''; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo $total16; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo ''; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo ''; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo $total18; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo $total19; ?></td>
                        <td align="center" style="border-color:#000;"><?php echo $total20; ?></td>
                    </tr>
                    <tr class="remarks"> 
                        <td style="border-color:#000; vertical-align: middle;" colspan="3"> REMARKS </th>
                        <td style="border-color:#000;"><?php echo ''; ?></td>
                        <td style="border-color:#000;"><?php echo ''; ?></td>
                        <td style="border-color:#000;"><?php echo ''; ?></td>
                        <td style="border-color:#000;"><?php echo ''; ?></td>
                        <td style="border-color:#000;"><?php echo ''; ?></td>
                        <td style="border-color:#000;"><?php echo ''; ?></td>
                        <td style="border-color:#000;"><?php echo ''; ?></td>
                        <td style="border-color:#000;"><?php echo ''; ?></td>
                        <td style="border-color:#000;"><?php echo ''; ?></td>
                        <td style="border-color:#000;"><?php echo ''; ?></td>
                        <td style="border-color:#000;" colspan="8">
                         
                            
                        </td>
                        
                        <td style="border-color:#000;">
                            
                            <?php 
                            echo $rowsingledata[0]->cm_data;
                            ?>
                        </td>
                        <td style="border-color:#000;">
                         
                             <?php 
                            echo $rowsingledata[0]->ytm_data;
                            ?>
                        </td>
                        <td style="border-color:#000;">
                           
                             <?php 
                            echo $rowsingledata[0]->pfy_data;
                            ?>
                        </td>
                    </tr>
                </tbody>
              
                </tfoot>
            </table>
                
            </div>
            <div style="clear:both;" class="clearfix" ></div>
            <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
            </div>
    </div>
        <?php } if($rowsingledata[0]->status_id != 2) { ?>
                 <div class="clearfix"></div><br>
       <div class="align_right text-right">
           <input type="hidden" name="_action" value="submitData" />
           <button type="submit" name="B1" class="btn btn-info btn-sm">Save as draft</button>
           <button type="submit" name="B2" class="btn btn-primary btn-sm">Submit</button>
           <a href="mrmDistributionlist.php" class="btn btn-danger btn-sm">Cancel</a>
        </div> <?php } ?>
            </form>
<!--        </div>
    </div>    -->
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

$(document).ready(function () {
        $("#ExportExcel").click(function (e) {
            var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html());
            window.open(path);
            e.preventDefault();
        });
    });
</script>
<script type="text/javascript">  
    function PrintDiv()
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=300');
        var content = document.getElementById("divToPrint").innerHTML;
        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('<style>body{margin:0;padding:0;}@page{margin:1in .5in .7in .5in;padding:0;);}body{font-size:14px !important;font-family:Arial !important;}table{page-break-inside:auto;border-left:1px solid black !important;border-bottom:1px solid black !important;font-size:13px !important;font-family:Arial !important;}tr{ page-break-inside:avoid; page-break-after:auto }table td {padding:6px 1px 1px;border:0 !important;border-collapse:collapse;border-right:1px solid #000000 !important;border-top:1px solid #000000 !important;border-bottom:1px solid #000000 !important;}table th {background: #88b5dd !important;border-collapse:collapse;-webkit-print-color-adjust: exact;color-adjust: exact;border-right:1px solid #000000 !important;border-top:1px solid #000000 !important;font-size:10px;padding:3px 0px !important;}.total td{font-size:13px;text-align:center;}.remarks{font-size:13px;}thead{display:table-header-group;}tbody{display:table-row-group;}</style></head><body>');
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
