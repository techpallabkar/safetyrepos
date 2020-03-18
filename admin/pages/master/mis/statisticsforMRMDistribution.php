<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$month_year               =   $beanUi->get_view_data("month_year");
$fetchAllData             =   $beanUi->get_view_data("fetchAllData");
$getDate                  =   $beanUi->get_view_data("getDate");
$incident_cm              =   $beanUi->get_view_data("incident_cm");
$incident_ytm             =   $beanUi->get_view_data("incident_ytm");
$incident_prev            =   $beanUi->get_view_data("incident_prev");
$incident_category_id     =   $beanUi->get_view_data("incident_category_id");
 

@$month_year_formatted     =   date("M y", strtotime("01-" . $month_year));
@$previous_financial_year  =   @$getDate["PREV_YTM"];
@$exp_prev_fy_year         =   explode("-", $previous_financial_year);
@$prevfyyear = $exp_prev_fy_year[0] . '-' . date("y", strtotime($exp_prev_fy_year[1] . "-01-01"));
@$present_financial_year =@$getDate["CURR_YTM"];     
@$exp_pres_fy_year         =   explode("-", $present_financial_year);
@$presfyyear               =  $exp_pres_fy_year[0]. '-' . date("y", strtotime($exp_pres_fy_year[1] . "-01-01"));
$controller->get_header();
$site_root_url = dirname(url());
$arrsum =  $arrsum2 = $arrsum3 = array();
if(!empty($incident_category_id)){
foreach($incident_category_id as $r => $v) {
    $inc1 = array();
    foreach( $incident_ytm as $k1 => $v1) {
        foreach($v1 as $key => $val ) {
            
            if($key == $v->id) {
                $inc1[$v->id][]= $val;
            }
        }
    }
    @$arrsum[$v->id]["total"] = array_sum($inc1[$v->id]);
    @$arrsum[$v->id]["name"] = $v->name;
    
    $inc2 = array();
    foreach( $incident_cm as $k2 => $v2) {
        foreach($v2 as $key => $val ) {
            
            if($key == $v->id) {
                $inc2[$v->id][]= $val;
            }
        }
    }
    @$arrsum2[$v->id]["total"] = array_sum($inc2[$v->id]);
     $arrsum2[$v->id]["name"] = $v->name;
    
    $inc3 = array();
    foreach( $incident_prev as $k3 => $v3) {
        foreach($v3 as $key => $val ) {
            
            if($key == $v->id) {
                $inc3[$v->id][]= $val;
            }
        }
    }
    $arrsum3[$v->id]["total"] = array_sum($inc3[$v->id]);
    $arrsum3[$v->id]["name"] = $v->name;
}
}
?>
<style>
 #parent {
        height: 570px;
    }
</style>

<script src="<?php echo url("assets/js/tableHeadFixer.js") ?>"></script>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
         $('.auto').autoNumeric('init');
         $("#fixTable").tableHeadFixer({'foot': true, 'head': true});
    });
</script>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">20. Statistics  for MRM ( Distribution )</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr/>
    <form action="" method="post" id="mdform" enctype="multipart/form-data" style="<?php echo ( $mode == "view" ) ? 'display:none' : 'display:block'; ?>" >
        <div class="holder">
            <label for="job_stop_req_raisedx" style="width:15%;padding-top:5px;">Select Month-Year :</label>
            <input required="true" type="text" name="month_year" id="month_year_from" class="month_year_picker" value ="<?php echo $month_year; ?>" style="width:250px;"  >
            <input type="hidden" name="_action" value="Create" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="statisticsforMRMDistribution.php" class="btn btn-danger btn-sm">Reset</a>                 
        </div>
        <hr class="no-margin"/>
    </form>
    <!--REPORTED ACCIDENT STATISTICS OF PERMANENT EMPLOYEES--> 
    <?php if (!empty($fetchAllData)) { ?>
    <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
        Export to Excel</button>
    <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
    <?php } ?>
   <form method="post"> 
        <?php if (!empty($fetchAllData)) { ?>
     <div id='TBL_EXPORT'>
         <div class="table-responsive">
        <div class="print-friendly" id="divToPrint">   
            <div id="parent">
            <table id="fixTable" border="1" class="table table-bordered table-condensed totaldata scrollTable" style="font-size:13px;font-weight:bold;" >
                <thead class="bg-primary fixedHeader">
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
               <tbody class="scrollContent">
                    <?php
                    @$total1 = @$total5 = @$total7 = @$total10 = @$total14 =  @$total16 = @$total18 = @$total19 = @$total20 = 0;
                    echo '<input type="hidden" name="month_year" value="'.$month_year.'" />';
                    foreach ($fetchAllData as $key => $rowdata) {
                        @$val1= ($rowdata["CM_PSET"]["TOTAL_COUNT"]);
                        @$val2= ($rowdata["CM_PSET"]["TOTAL_AVG"]);
                        @$val5= ($rowdata["CM_CSET"]["TOTAL_COUNT"]);
                        @$val6= ($rowdata["CM_CSET"]["TOTAL_AVG"]);
                        @$val11cc= ($rowdata["CM_CC"]["TOTAL_COUNT"]);
                        @$val22cc= ($rowdata["CM_CC"]["TOTAL_AVG"]);
                        @$valcc = $val11+$val22;
                        @$val7 = $val1+$val11cc+$val5;
                        if($val7 != 0){
                         $val8 = round(((($val1*$val2)+($val5*$val6)) / $val7),2);
                        }
                        @$val9 = '';
                        @$val10 = ($rowdata["CYTM_PSET"]["TOTAL_COUNT"]);
                        @$val11 = ($rowdata["CYTM_PSET"]["TOTAL_AVG"]);
                        @$val12 = ($rowdata["CYTM_CC"]["TOTAL_COUNT"]);
                        @$val13 = ($rowdata["CYTM_CC"]["TOTAL_AVG"]);
                        
                        @$val14 = ($rowdata["CYTM_CSET"]["TOTAL_COUNT"]);
                        @$val15 = ($rowdata["CYTM_CSET"]["TOTAL_AVG"]);
                        @$val16 = $val10+$val12+$val14;
                        @$val21 = ($rowdata["CYTM_CC"]["TOTAL_COUNT"]);
                        @$val23 = ($rowdata["CYTM_CC"]["TOTAL_AVG"]);
                       // $val24 = $val12+$val13;
                        if($val16 != 0){
                         $val17 = round(((($val10*$val11)+($val14*$val15))/$val16),2);
                        }
                        $val18 = ($rowdata["INC_CM"]);
                        $val19 = ($rowdata["INC_YTM"]);
                        $val20 = ($rowdata["INC_PREVYEAR"]);
                        echo '<tr>'
                        . '<td align="center" style="border:1px solid #000;"><input type="hidden" name="rowcount[]" value="'.$key.'"/>' . ($key + 1) . '.</td>'
                        . '<td bgcolor="#B9CDE5" style="border:1px solid #000;" bgcolor="#B9CDE5" ><input type="text" style="width:100%;" name="engineer_name_'.$key.'"  /></td>'
                        . '<td bgcolor="#B9CDE5" style="border:1px solid #000;" bgcolor="#B9CDE5" ><input type="hidden" style="width:100%;" name="district_'.$key.'" value="'.(@$rowdata["DISTRICT"]).'"  />' . (@$rowdata["DISTRICT"]) . '</td>'
                        . '<td align="center" bgcolor="#e3ff96" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cm_pset_audit_count_'.$key.'" value="'.(@$val1).'"  />' . @$val1. '</td>'
                        . '<td align="center" bgcolor="#e3ff96" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cm_pset_avg_'.$key.'" value="' . @$val2 . '"  />' . @$val2 . '</td>'
                        . '<td align="center" bgcolor="#e0eeff" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cm_cc_audit_count_'.$key.'" value="'.@$val11cc.'"  />'.$val11cc.'</td>'
                        . '<td align="center" bgcolor="#e0eeff" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cm_cc_avg_'.$key.'" value="'.@$val22cc.'"  />'.$val22cc.'</td>'
                        . '<td align="center" bgcolor="#feccff" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cm_cset_audit_count_'.$key.'" value="' . @$val5 . '"  />' . @$val5 . '</td>'
                        . '<td align="center" bgcolor="#feccff" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cm_cset_avg_'.$key.'" value="' . @$val6 . '"  />' . @$val6 . '</td>'
                        . '<td align="center" bgcolor="#C5E0B4" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cm_ovr_audit_count_'.$key.'" value="' . @$val7 . '"  />'. @$val7.'</td>'
                                . '<td align="center" bgcolor="#C5E0B4" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cm_ovr_avg_'.$key.'" value="' . @$val8 . '"   />'.@$val8.'</td>'
                                . '<td align="center" bgcolor="#fff" style="border:1px solid #000;"><input type="text" style="width:100%;" name="other_activities_'.$key.'"  /></td>'
                                . '<td align="center" bgcolor="#e3ff96" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cytm_pset_audit_count_'.$key.'" value="' . @$val10 . '"  />'.@$val10.'</td>'
                                . '<td align="center" bgcolor="#e3ff96" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cytm_pset_avg_'.$key.'" value="' . @$val11 . '"  />'.@$val11.'</td>'
                                . '<td align="center" bgcolor="#e0eeff" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cytm_cc_audit_count_'.$key.'" value="' . @$val12 . '"  />'.@$val12.'</td>'
                                . '<td align="center" bgcolor="#e0eeff" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cytm_cc_avg_'.$key.'" value="' . @$val13 . '"  />'.@$val13.'</td>'
                                . '<td align="center" bgcolor="#feccff" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cytm_cset_audit_count_'.$key.'" value="' . @$val14 . '"  />'.@$val14.'</td>'
                                . '<td align="center" bgcolor="#feccff" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cytm_cset_avg_'.$key.'" value="' . @$val15 . '"  />'.@$val15.'</td>'
                                . '<td align="center" bgcolor="#C5E0B4" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cytm_ovr_audit_count_'.$key.'" value="' . @$val16 . '"  />'.@$val16.'</td>'
                                . '<td align="center" bgcolor="#C5E0B4" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="cytm_ovr_avg_'.$key.'" value="' . @$val17 . '"  />'.@$val17.'</td>'
                                . '<td align="center" bgcolor="#fff" style="border:1px solid #000;"><input type="text" style="width:100%;" name="other_activities1_'.$key.'"  /></td>'
                                . '<td align="center" bgcolor="#f0ad4e" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="inc_cm_'.$key.'" value="' . @$val18 . '"  />'.@$val18.'</td>'
                                . '<td align="center" bgcolor="#f0ad4e" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="inc_cytm_'.$key.'" value="' . @$val19 . '"  />'.@$val19.'</td>'
                                . '<td align="center" bgcolor="#f0ad4e" style="border:1px solid #000;"><input type="hidden" style="width:100%;" name="inc_pfy_'.$key.'" value="' . @$val20 . '"  />'.@$val20.'</td>';
                        echo '</tr>';
                       @$total1 +=$val1;
                       @$total5 +=$val5;
                       @$total7 +=$val7;
                       @$total10 +=$val10;
                       @$total14 +=$val14;
                       @$total16 +=$val16;
                       @$total18 +=$val18;
                       @$total19 +=$val19;
                       @$total20 +=$val20;
                       @$total21 +=$val11cc;
                       @$total23 +=$val12;
                    }
                    ?>
                    <tr  style="font-size: 16px;background:#ffdece;"> 
                        <th style="border-color:#000;" colspan="3"> TOTAL </th>
                        <th style="border-color:#000;"><?php echo $total1; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo $total21; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo $total5; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo $total7; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo $total10; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo $total23; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo $total14; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo $total16; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo $total18; ?></th>
                        <th style="border-color:#000;"><?php echo $total19; ?></th>
                        <th style="border-color:#000;"><?php echo $total20; ?></th>
                    </tr>
                    <tr > 
                        <th style="border-color:#000; vertical-align: middle;" colspan="3"> REMARKS </th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;"><?php echo ''; ?></th>
                        <th style="border-color:#000;" colspan="8">
                         
                            
                        </th>
                        
                        <th style="border-color:#000;">
                            
                            <?php 
                            $totaltarget1 = "";
                            foreach ($arrsum2 as $key => $value) {
                                $totaltarget1 .= $value["name"].' - '.(($value["total"] != '') ? $value["total"] : '0').'<br>';
                            }
                            echo $totaltarget1;
                            echo '<input type="hidden" style="width:100%;" name="cm_data" value="' . $totaltarget1 . '"  />';
                            ?>
                        </th>
                        <th style="border-color:#000;">
                         
                             <?php 
                             $totaltarget2 = "";
                            foreach ($arrsum as $key => $value) {
                                $totaltarget2 .= $value["name"].' - '.(($value["total"] != '') ? $value["total"] : '0').'<br>';
                            }
                            echo $totaltarget2;
                            echo '<input type="hidden" style="width:100%;" name="ytm_data" value="' . $totaltarget2 . '"  />';
                            ?>
                        </th>
                        <th style="border-color:#000;">
                           
                             <?php 
                             $totaltarget3 = "";
                            foreach ($arrsum3 as $key => $value) {
                                @$totaltarget3 .= $value["name"].' - '.(($value["total"] != '') ? $value["total"] : '0').'<br>';
                            }
                            echo $totaltarget3;
                            echo '<input type="hidden" style="width:100%;" name="pfy_data" value="' . $totaltarget3 . '"  />';
                            ?>
                        </th>
                    </tr>
                </tbody>
              
                
            </table>
            </div>
        </div>
         </div>
    </div>
       <div class="clearfix"></div><br>
       <div class="align_right text-right">
           <input type="hidden" name="_action" value="submitData" />
           <button type="submit" name="B1" class="btn btn-info btn-sm">Save as Draft</button>
           <button type="submit" name="B2" class="btn btn-primary btn-sm">Submit</button>
           <a href="mrmDistributionlist.php" class="btn btn-danger btn-sm">Cancel</a>
       </div>
        <?php } ?>
       
    
    </form>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>


<script type="text/javascript" src="<?php echo url('assets/js/ExportExcel/jquery.table2excel.js') ?>"></script>
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


    $(document).ready(function () {
        $("#ExportExcel").click(function (e) {
            var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html());
            window.open(path);
            e.preventDefault();
        });



    });
    
    function get_total_value(type,incid) {
            var current_month = 0;
            var prev_ytm = 0;
            var current_ytm = 0;
            $('.'+type+'CMNTH_' +incid).each(function () {
                var c1val = $(this).text()
                current_month += parseInt(c1val);
            });
            $('.'+type+'PREVYTM_' +incid).each(function () {
                var c1val = $(this).text()
                prev_ytm += parseInt(c1val);
            });
            $('.'+type+'CURYTM_' +incid).each(function () {
                var c1val = $(this).text()
                current_ytm += parseInt(c1val);
            });


            $("#"+type+"CMNTH_" +incid).text(current_month);
            $("#"+type+"PREVYTM_" +incid).text(prev_ytm);
            $("#"+type+"CURYTM_" +incid).text(current_ytm);
    }
    $(document).ready(function () {

<?php 
if(!empty($incident_category))
foreach ($incident_category as $key => $value) { ?>
        
        get_total_value('PSET',<?php echo $value->id; ?>);
        get_total_value('CSET',<?php echo $value->id; ?>);
        get_total_value('PCSET',<?php echo $value->id; ?>);
        get_total_value('OTHERS',<?php echo $value->id; ?>);
<?php } ?>


    });
</script>
<script type="text/javascript">  
    function PrintDiv()
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=300');
    var content = document.getElementById("divToPrint").innerHTML;
    mywindow.document.write('<html><head><title></title>');
    mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}</style></head><body>');
    //mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}
 </script>
