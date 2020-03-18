<?php
/* MIS Report No. : 27
 * Name         :   Suraksah Barta Incident
 * Controller   :   surakshabarta_incident()
 * Dao          :   division_child_unit_count,getPPEAuditData,getSITEAuditData,getSiteAuditCount
 * Created By pallab kar
 */
if (file_exists("../../lib/var.inc.php"))
require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
//$arr_pset_data = $beanUi->get_view_data("arr_pset_data");
//$arr_cset_data = $beanUi->get_view_data("arr_cset_data");
$REPOTED_ACC_STAT_PSET      =   $beanUi->get_view_data("REPOTED_ACC_STAT_PSET");//ShowPre($REPOTED_ACC_STAT_PSET);
$REPOTED_ACC_STAT_CSET      =   $beanUi->get_view_data("REPOTED_ACC_STAT_CSET");//ShowPre($REPOTED_ACC_STAT_CSET);
$getDate = $beanUi->get_view_data("getDate");
$controller->get_header();
$site_root_url = dirname(url());

$QUARTER = array("1" => "FIRST QUARTER",
                 "2" => "SECOND QUARTER",
                 "3" => "THIRD QUARTER",
                 "4" => "FOURTH QUARTER"
               );
?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
</script>
<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">27. SURAKSHA BARTA -INCIDENT
</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
      <form action="" method="post" id="fyr" enctype="multipart/form-data">
        <div class="col-sm-6">
            <div class="col-sm-6">
            <?php
            $startyear = '2015';
            $current_year='2018';
            $yearArray = range(2015, $current_year);
             echo '<select name="selected_year" id="selected_year">
                    <option value="">--Select Year--</option>';
                  
                    foreach ($yearArray as $year) {

                        $selected = ($year == @$_REQUEST["selected_year"]) ? 'selected' : '';
                       
                           echo '<option ' . $selected . ' value="' . $year . '">' . $year . '</option>';
                        
                      
                    }
                 
                echo '</select>';
            ?></div>
            
            <div class="col-sm-6">
            <select name="quarter_value" id="quarter_value" value="<?php echo @$_REQUEST["month_year_from"]; ?>">
            <option value="">--Select Quarter--</option>
            <option value="1" <?php echo ((@$_REQUEST["quarter_value"] == 1) ? "selected" : ""); ?>>First Quarter</option>
            <option value="2" <?php echo ((@$_REQUEST["quarter_value"] == 2) ? "selected" : ""); ?>>Second Quarter</option>
            <option value="3" <?php echo ((@$_REQUEST["quarter_value"] == 3) ? "selected" : ""); ?>>Third Quarter</option>
            <option value="4" <?php echo ((@$_REQUEST["quarter_value"] == 4) ? "selected" : ""); ?>>Fourth Quarter</option>
            </select>
            </div>
        </div>
        <div class="col-sm-2">
            <input type="hidden" name="_action" value="submitData" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="surakshabarta_incident.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr/>
    <div class="container"  style="min-height:200px;">
        <?php if(!empty($_POST)) { ?>
 <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
 Export to Excel</button>
    <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
        <div id='TBL_EXPORT'>
        <div class="print-friendly" id="divToPrint">  
        <div>
        <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;" >
                <thead class="bg-primary">
                    <tr>
                        <th  colspan="10">PERMANENT SET - 
                            <?php
                            $QUA = @$_REQUEST["quarter_value"];
                            echo $QUARTER[$QUA]; 
                            ?> 
                            INCIDENT REPORT</th>
                    </tr>
                    <tr class="bg-primary">
                        <th rowspan="3" width="5%">SL.NO</th>
                        <th rowspan="3" width="20%">DEPT / DIST</th>
                    </tr>
                    <tr>
                        <th colspan="2">FAC</th>
                        <th colspan="2">LWC </th>
                        <th colspan="2">FATAL </th>
                        <th colspan="2">TOTAL </th>
                    </tr>
                    <tr>
                        <th width="10%"> <?php echo @$getDate["PREV_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["CURR_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["PREV_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["CURR_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["PREV_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["CURR_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["PREV_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["CURR_Y"]; ?> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                   // show($REPOTED_ACC_STAT_PSET);
                    if(!empty($REPOTED_ACC_STAT_PSET)) {
                        $slno =0;
                        $tot_pset_prev_FAC_count = $tot_pset_curr_FAC_count = $tot_pset_prev_LWC_score = $tot_pset_curr_LWC_score = $tot_pset_prev_FATAL_score = $tot_pset_curr_FATAL_score = 0;
                        
                        foreach( $REPOTED_ACC_STAT_PSET as $key => $value ) {
                                                   
                            if(@$value["district_id"] != 10){
                            $slno++;
                            $pSetPrevFacCount   = $value['INCCAT']['1']['PREV_YEAR1'];
                            $pSetCurrFacCount   = $value['INCCAT']['1']['CURRENT_YEAR'];
                            $pSetPrevLwcCount   = $value['INCCAT']['2']['PREV_YEAR1'];
                            $pSetCurrLwcCount   = $value['INCCAT']['2']['CURRENT_YEAR'];
                            $pSetPrevFatalCount = $value['INCCAT']['3']['PREV_YEAR1'];
                            $pSetCurrFatalCount = $value['INCCAT']['3']['CURRENT_YEAR'];
                            
                            $totalPSetPrevCount =($pSetPrevFacCount+$pSetPrevLwcCount+$pSetPrevFatalCount);
                            $totalPSetCurrCount =($pSetCurrFacCount+$pSetCurrLwcCount+$pSetCurrFatalCount);
                            echo '<tr>
                        <td bgcolor="#07efcc" style="border-color:#000;" align="center">'.($slno).'.</td>
                        <td bgcolor="#f7efef" style="border-color:#000;" align="left">'.$value["district"].'</td>
                        <td bgcolor="#ffccff" style="border-color:#000;" align="center">'.$pSetPrevFacCount.'</td>
                        <td bgcolor="#DBEEF4" style="border-color:#000;" align="center">'.$pSetCurrFacCount.'</td>
                            
                        <td bgcolor="#ffccff" style="border-color:#000;" align="center">'.$pSetPrevLwcCount.'</td>
                        <td bgcolor="#DBEEF4" style="border-color:#000;" align="center">'.$pSetCurrLwcCount.'</td>
                            
                        <td bgcolor="#ffccff" style="border-color:#000;" align="center">'.$pSetPrevFatalCount.'</td>
                        <td bgcolor="#DBEEF4" style="border-color:#000;" align="center">'.$pSetCurrFatalCount.'</td>
                            
                        <td bgcolor="#ffaa80" style="border-color:#000;" align="center">'.$totalPSetPrevCount.'</td>
                        <td bgcolor="#80aaff" style="border-color:#000;" align="center">'.$totalPSetCurrCount.'</td>
                    </tr>';
                            
                            $tot_pset_prev_FAC_count +=$pSetPrevFacCount;
                            $tot_pset_curr_FAC_count +=$pSetCurrFacCount;
                            
                            $tot_pset_prev_LWC_score +=$pSetPrevLwcCount;
                            $tot_pset_curr_LWC_score +=$pSetCurrLwcCount;
                            
                            $tot_pset_prev_FATAL_score +=$pSetPrevFatalCount;
                            $tot_pset_curr_FATAL_score +=$pSetCurrFatalCount;
                            
                            $totPSetPrevCount =($tot_pset_prev_FAC_count+$tot_pset_prev_LWC_score+$tot_pset_prev_FATAL_score);
                            $totPSetCurrCount =($tot_pset_curr_FAC_count+$tot_pset_curr_LWC_score+$tot_pset_curr_FATAL_score);
                        }
                    }
                        echo '<tr>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center" colspan="2">TOTAL</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_pset_prev_FAC_count).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_pset_curr_FAC_count).'</td>'
                                
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_pset_prev_LWC_score).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_pset_curr_LWC_score).'</td>'
                                
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_pset_prev_FATAL_score).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_pset_curr_FATAL_score).'</td>'
                                
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($totPSetPrevCount).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($totPSetCurrCount).'</td>'
                        . '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            
            
            <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;font-weight:bold;" >
                <thead class="bg-primary">
                    <tr>
                        <th  colspan="10">CONTRACTOR SET - 
                            <?php
                            $QUA = @$_REQUEST["quarter_value"];
                            echo $QUARTER[$QUA]; 
                            ?> 
                            INCIDENT REPORT</th>
                    </tr>
                    </tr>
                    <tr class="bg-primary">
                        <th rowspan="3" width="5%">SL.NO</th>
                        <th rowspan="3" width="20%">DEPT / DIST</th>
                    </tr>
                    <tr>
                        <th colspan="2">FAC</th>
                        <th colspan="2">LWC </th>
                        <th colspan="2">FATAL </th>
                        <th colspan="2">TOTAL </th>
                    </tr>
                    <tr>
                        <th width="10%"> <?php echo @$getDate["PREV_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["CURR_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["PREV_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["CURR_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["PREV_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["CURR_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["PREV_Y"]; ?> </th>
                        <th width="10%"> <?php echo @$getDate["CURR_Y"]; ?> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
//                    show($REPOTED_ACC_STAT_CSET);
                    if(!empty($REPOTED_ACC_STAT_CSET)) {
                        $slno =0;
                        $tot_cset_prev_FAC_count = $tot_cset_curr_FAC_count = $tot_cset_prev_LWC_score = $tot_cset_curr_LWC_score = $tot_cset_prev_FATAL_score = $tot_cset_curr_FATAL_score = 0;
                        
                        foreach( $REPOTED_ACC_STAT_CSET as $key => $value ) {
                            
                        if(@$value["district_id"] != 168){
                            $slno++;
                            $cSetPrevFacCount   = $value['INCCAT']['1']['PREV_YEAR1'];
                            $cSetCurrFacCount   = $value['INCCAT']['1']['CURRENT_YEAR'];
                            $cSetPrevLwcCount   = $value['INCCAT']['2']['PREV_YEAR1'];
                            $cSetCurrLwcCount   = $value['INCCAT']['2']['CURRENT_YEAR'];
                            $cSetPrevFatalCount = $value['INCCAT']['3']['PREV_YEAR1'];
                            $cSetCurrFatalCount = $value['INCCAT']['3']['CURRENT_YEAR'];
                            
                            $totalCSetPrevCount =($cSetPrevFacCount+$cSetPrevLwcCount+$cSetPrevFatalCount);
                            $totalCSetCurrCount =($cSetCurrFacCount+$cSetCurrLwcCount+$cSetCurrFatalCount);
                            
                            echo '<tr>
                        <td bgcolor="#07efcc" style="border-color:#000;" align="center">'.($slno).'.</td>
                        <td bgcolor="#f7efef" style="border-color:#000;" align="left">'.$value["district"].'</td>
                        <td bgcolor="#ffccff" style="border-color:#000;" align="center">'.$cSetPrevFacCount.'</td>
                        <td bgcolor="#DBEEF4" style="border-color:#000;" align="center">'.$cSetCurrFacCount.'</td>
                            
                        <td bgcolor="#ffccff" style="border-color:#000;" align="center">'.$cSetPrevLwcCount.'</td>
                        <td bgcolor="#DBEEF4" style="border-color:#000;" align="center">'.$cSetCurrLwcCount.'</td>
                            
                        <td bgcolor="#ffccff" style="border-color:#000;" align="center">'.$cSetPrevFatalCount.'</td>
                        <td bgcolor="#DBEEF4" style="border-color:#000;" align="center">'.$cSetCurrFatalCount.'</td>
                            
                        <td bgcolor="#ffaa80" style="border-color:#000;" align="center">'.$totalCSetPrevCount.'</td>
                        <td bgcolor="#80aaff" style="border-color:#000;" align="center">'.$totalCSetCurrCount.'</td>
                    </tr>';
                            
                            $tot_cset_prev_FAC_count +=$cSetPrevFacCount;
                            $tot_cset_curr_FAC_count +=$cSetCurrFacCount;
                            
                            $tot_cset_prev_LWC_score +=$cSetPrevLwcCount;
                            $tot_cset_curr_LWC_score +=$cSetCurrLwcCount;
                            
                            $tot_cset_prev_FATAL_score +=$cSetPrevFatalCount;
                            $tot_cset_curr_FATAL_score +=$cSetCurrFatalCount;
                            
                            $totCSetPrevCount =($tot_cset_prev_FAC_count+$tot_cset_prev_LWC_score+$tot_cset_prev_FATAL_score);
                            $totCSetCurrCount =($tot_cset_curr_FAC_count+$tot_cset_curr_LWC_score+$tot_cset_curr_FATAL_score);
                        }
                    }
                        echo '<tr>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center" colspan="2">TOTAL</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_cset_prev_FAC_count).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_cset_curr_FAC_count).'</td>'
                                
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_cset_prev_LWC_score).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_cset_curr_LWC_score).'</td>'
                                
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_cset_prev_FATAL_score).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_cset_curr_FATAL_score).'</td>'
                                
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($totCSetPrevCount).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($totCSetCurrCount).'</td>'
                                . '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        
    <!--END PPE AUDIT-->
    
    <!--START SITE AUDIT-->     
        
        <div class="print1" style="float: left; border:1px solid #000; padding:3px 8px;">
            NA : NOT APPLICABLE</td>
        </div>
       
        </div>
    <div style="clear:both;" class="clearfix" ></div>
            <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
        </div>
</div>
        <?php  } ?>
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

$(document).ready(function(){
    $("#ExportExcel").click(function(e){
        var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html()); 
        window.open(path);
        e.preventDefault();
    });
});
    function PrintDiv()
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=300');
    var content = document.getElementById("divToPrint").innerHTML;
    mywindow.document.write('<html><head><title>SURAKSHA BARTA SCORES STAT</title>');
    mywindow.document.write('<style>@page{filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {padding:10px 2px; background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}body{margin:0;padding:0;}.table-siteAudit{margin-top:220px;}</style></head><body>');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.print();
    mywindow.close();
    return true;
}
 </script>