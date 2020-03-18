<?php
/* MIS Report No. : 04
 * Name         :   MAINS SCORE CARD(vis a vis Target) TYPE-2
 * Controller   :   mainsScoreCardType2Report()
 * Dao          :   getAuditData
 * Created By Sumit
 * Modified By Anima And Pallab
 */


if (file_exists("../../lib/var.inc.php"))
require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$getAuditData = $beanUi->get_view_data("getAuditData");
$getDate = $beanUi->get_view_data("getDate");
$controller->get_header();
$site_root_url = dirname(url());
?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
</script>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">4. MAINS SCORE CARD(vis a vis Target) TYPE-2</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr/>

    <form action="" method="post" id="fyr" enctype="multipart/form-data">

        <label class="col-sm-2">Select Month-Year:</label>
        <div class="col-sm-4">
            <input required="true" type="text" name="month_year_from" id="month_year_from" class="month_year_picker form-control" value = "<?php echo @$getDate['month_year']; ?>" style="width: 100%;" >
            <span id="financial_year_error"></span>
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="submitDate" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="mainsScoreCardType2Report.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    
    <hr/> <?php //echo "<pre>";print_r($alldatagen);  ?>

    <form action="" method="post" enctype="multipart/form-data">
        <?php  if(!empty($getAuditData)){ ?>
        <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
        <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
 Export to Excel</button>
        <div id='TBL_EXPORT'>
            <div class="print-friendly" id="divToPrint" >
        <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;font-weight: bold;" >
                <thead class="bg-primary">
                    <tr>
                        <th rowspan="3" style="vertical-align: middle;">SL</th>
                        <th rowspan="3" style="vertical-align: middle;">DISTRICT / SECTION</th>
                        <th  colspan="11" style="font-size:18px;"> <?php  echo "MAINS DEPARTMENT<br> YTM PERFORMANCE AND GAP ANALYSIS<br> ".date("F,Y",strtotime("01-".@$getDate["month_year"]));?></th>
                    </tr>
                    <tr>
                        <th colspan="5">P-SET</th>
                        <th></th>
                        <th colspan="5">C-SET</th>
                    </tr>
                    <tr>
                        <th width="10%">Score (<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["prevYear"]));
                        echo @$PY[1]."-".date("y",strtotime("01-01-".$PY[5]));
                        ?>)</th>
                        <th width="10%" >Score (<?php
                        @$CY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["currentYear"]));
                        echo @$CY[1]."-".date("y", strtotime("01-01-".$CY[5]));
                        ?>)</th>
                        <th width="10%" >Improvement (%)</th>
                        <th width="10%" >Target</th>
                        <th width="10%" >Gap</th>
                        <th></th>
                        <th width="10%">Score (<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["prevYear"]));
                        echo @$PY[1]."-".date("y",strtotime("01-01-".$PY[5]));
                        ?>)</th>
                        <th width="10%">Score (<?php  @$CY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["currentYear"]));
                        echo @$CY[1]."-".date("y", strtotime("01-01-".$CY[5])); ?>)</th>
                        <th width="10%">Improvement (%)</th>
                        <th width="10%">Target</th>
                        <th width="10%">Gap</th>
                    </tr> 
                </thead>
                <tbody>
                    <?php
                    @$pset_prev_score = $pset_current_score = $cset_prev_score = $cset_current_score = $total_pset_target = $total_cset_target = 0;
                    @$totalcount = count($getAuditData);
                    foreach($getAuditData as $key => $rowdata) {
                        @$pset_prevscroe         = round((@$rowdata["P_SET_PREV"]["score"]),2);
                        @$pset_currentscroe      = round((@$rowdata["P_SET_CURRENT"]["score"]),2);
                        @$cset_prevscroe         = round((@$rowdata["C_SET_PREV"]["score"]),2);
                        @$cset_currentscroe      = round((@$rowdata["C_SET_CURRENT"]["score"]),2);
                        @$pset_imporvement      = round(((($pset_currentscroe - $pset_prevscroe)/$pset_prevscroe)*100),2);
                        @$cset_imporvement      = round((($cset_currentscroe - $cset_prevscroe)/$cset_prevscroe*100),2);
                        @$pset_gap   = round(($pset_currentscroe- (@$rowdata["TARGET"]["pset_target"])),2);
                        @$cset_gap   = round(($cset_currentscroe- (@$rowdata["TARGET"]["cset_target"])),2);
                        echo '<tr>
                            <td style="border:1px solid #000 !important;" align="center">'.($key+1).'.</td>
                            <td bgcolor="#CCFFFF" style="border:1px solid #000 !important;" align="center">'.(@$rowdata["name"]).'</td>
                            <td bgcolor="#c5ff93" style="border:1px solid #000 !important;" align="center">'.(@$pset_prevscroe).'</td>
                            <td bgcolor="#f5d6ff" style="border:1px solid #000 !important;" align="center">'.(@$pset_currentscroe).'</td>
                            <td style="border:1px solid #000 !important;" align="center">'.(@$pset_imporvement).'%</td>
                            <td style="border:1px solid #000 !important;" align="center" bgcolor="#fff59e">'.(@$rowdata["TARGET"]["pset_target"]).'</td>
                            <td bgcolor="#f0ad4e" style="border:1px solid #000 !important;" align="center">'.(@$pset_gap).'</td>
                            <td style="border:1px solid #000 !important;" align="center"></td>
                            <td bgcolor="#c5ff93" style="border:1px solid #000 !important;" align="center">'.(@$cset_prevscroe).'</td>
                            <td bgcolor="#f5d6ff" style="border:1px solid #000 !important;" align="center">'.(@$cset_currentscroe).'</td>
                            <td style="border:1px solid #000 !important;" align="center">'.(@$cset_imporvement).'%</td>
                            <td style="border:1px solid #000 !important;" align="center" bgcolor="#fff59e">'.(@$rowdata["TARGET"]["cset_target"]).'</td>
                            <td style="border:1px solid #000 !important;" align="center" bgcolor="#f0ad4e" >'.(@$cset_gap).'</td> 
                        
                        
                        </tr>';

                        $total_pset_target += @$rowdata["TARGET"]["pset_target"];
                        @$total_pset_targetcount +=(is_numeric(@$rowdata["TARGET"]["pset_target"]) ? 1 : 0);

                       @$total_cset_target += @$rowdata["TARGET"]["cset_target"];
                       @$total_cset_targetcount +=(is_numeric(@$rowdata["TARGET"]["cset_target"]) ? 1 : 0);
                       
                       @$pset_prev_audit_count += @$rowdata["P_SET_PREV"]["totaldiv"];
                       @$pset_prev_audit_count += @$rowdata["P_SET_PREV"]["totaldiv"];
                       @$pset_prev_score_count += @$pset_prevscroe * @$rowdata["P_SET_PREV"]["totaldiv"];
                       
                       @$pset_curr_audit_count += @$rowdata["P_SET_CURRENT"]["totaldiv"];
                       @$pset_curr_score_count += @$pset_currentscroe * @$rowdata["P_SET_CURRENT"]["totaldiv"];
                       
                       @$cset_prev_audit_count += @$rowdata["C_SET_PREV"]["totaldiv"];
                       @$cset_prev_score_count += @$cset_prevscroe * @$rowdata["C_SET_PREV"]["totaldiv"];
                       
                       @$cset_curr_audit_count += @$rowdata["C_SET_CURRENT"]["totaldiv"];
                       @$cset_curr_score_count += @$cset_currentscroe * @$rowdata["C_SET_CURRENT"]["totaldiv"];
                       
                    }
                  
                    @$total_pset_prev_score=round(($pset_prev_score_count/$pset_prev_audit_count),2);                    
                    @$total_pset_current_score=round(($pset_curr_score_count/$pset_curr_audit_count),2);
                    
                    @$total_pset_target = floor($total_pset_target/$total_pset_targetcount);
                    @$total_pset_improvement = round((($total_pset_current_score - $total_pset_prev_score)/$total_pset_prev_score*100),2);
                    @$total_pset_gap  =  round(($total_pset_current_score - $total_pset_target),2);
                    
                    @$total_cset_prev_score=round(($cset_prev_score_count/$cset_prev_audit_count),2);                    
                    @$total_cset_current_score=round(($cset_curr_score_count/$cset_curr_audit_count),2);
                    
                    @$total_cset_target = floor($total_cset_target/$total_cset_targetcount);
                    @$total_cset_improvement = round((($total_cset_current_score - $total_cset_prev_score)/$total_cset_prev_score*100),2);
                    @$total_cset_gap = round(($total_cset_current_score - $total_cset_target),2);
                     
                    
                    echo '<tr style="font-weight:bold;">'
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" colspan="2" align="right">TOTAL</td>'
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" align="center">'.($total_pset_prev_score).'</td>'
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" align="center">'.($total_pset_current_score).'</td>'
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" align="center">'.($total_pset_improvement).'</td>'
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" align="center">'.($total_pset_target).'</td>'        
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" align="center">'.($total_pset_gap).'</td>'
                    
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" align="center"></td>'
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" align="center">'.($total_cset_prev_score).'</td>'
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" align="center">'.($total_cset_current_score).'</td>'
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" align="center">'.($total_cset_improvement).'</td>'
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" align="center">'.($total_cset_target).'</td>'       
                    . '<td bgcolor="#FCD5B5" style="border:1px solid #000 !important;" align="center">'.($total_cset_gap).'</td>'
                            . '</tr>';
                    ?>
                </tbody>
            </table>
                    <div class="print1" style="float: left; border:1px solid #000; padding:3px 8px;">
                        NOTE : P-Sets including Call Centres</td>
                    </div>
                    <div class="print1" style="float: right; border:1px solid #000; padding:3px 8px;">
                        Safety Audit in HT is not being conducted by Safety Cell since Dec'16.
                    </div>
                <div style="clear:both;" class="clearfix" ></div>
                <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
        </div>
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
<script>
    $('.month_year_picker').monthpicker({pattern: 'mm-yyyy',
        startYear: 2015,
        finalYear: 2025, });
    var options = {
        startYear: 2010,
        finalYear: 2018,
        openOnFocus: false
    };
</script>
<script type='text/javascript'>
$(document).ready(function(){
    $("#ExportExcel").click(function(e){
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
    mywindow.document.write('<html><head><title>MAINS DEPARTMENT YTM PERFORMANCE AND GAP ANALYSIS</title>');
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