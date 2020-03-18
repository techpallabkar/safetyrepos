<?php
/* MIS Report No. : 06
 * Name         :   Other Dept Score Card(vis-a-vis Target) Type-2
 * Controller   :   otherDeptScoreCardType2Report()
 * Dao          :   getAuditDataODSCT2R
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

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">6.Other Dept Score Card(vis-a-vis Target) Type-2</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr/>

    <form action="" method="post" id="fyr" enctype="multipart/form-data">

        <label class="col-sm-2">Select Month-Year:</label>
        <div class="col-sm-4">
            <input type="text" name="month_year_from" id="month_year_from" class="month_year_picker form-control" value = "<?php echo @$getDate['month_year']; ?>" style="width: 100%;" required>
            <span id="financial_year_error"></span>
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="submitDate" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="otherDeptScoreCardType2Report.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    
    <hr/> <?php //echo "<pre>";print_r($alldatagen);  ?>

    <form action="" method="post" enctype="multipart/form-data">
        <?php  if(!empty($getAuditData)){ ?>
         <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
 Export to Excel</button>
        <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
        <div id='TBL_EXPORT'>
        <div class="print-friendly" id="divToPrint">
        <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;" >
                <thead class="bg-primary">
                    <tr>
                        <th rowspan="3">SL</th>
                        <th rowspan="3">DISTRICT</th>
                        <th  colspan="11" class="text-uppercase"> <?php  echo "NON-MAINS DEPARTMENTS <br> YTM PERFORMANCE AND GAP ANALYSIS <br> ".date("F,Y",strtotime("01-".@$getDate["month_year"]));?></th>
                    </tr>
                    <tr>
                        <th colspan="5"> P-SET </th>
                        <th></th>
                        <th colspan="5"> C-SET </th>
                    </tr>
                    
                    <tr>
                        <th>Score (<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["prevYear"]));
                        echo @$PY[1]."-".date("y",  strtotime("01-01-".@$PY[5]));
                        ?>)</th>
                        <th>Score (<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["currentYear"]));
                        echo @$PY[1]."-".date("y",  strtotime("01-01-".@$PY[5]));
                        ?>)</th>
                        <th>Improvement (%)</th>
                        <th>Target</th>
                        <th>Gap</th>
                        
                        <th></th>
                        <th>Score (<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["prevYear"]));
                        echo @$PY[1]."-".date("y",  strtotime("01-01-".@$PY[5]));
                        ?>)</th>
                        <th>Score (<?php
                        @$PY = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["currentYear"]));
                        echo @$PY[1]."-".date("y",  strtotime("01-01-".@$PY[5]));
                        ?>)</th>
                        <th>Improvement (%)</th>
                        <th>Target</th>
                        <th>Gap</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pset_prev_score = $pset_current_score = $cset_prev_score = $cset_current_score = $pcset_current_score = $otherset_current_score  = 0;
                    $totalcount = count($getAuditData);
                    
                    foreach($getAuditData as $key => $rowdata) {
                        $pset_prevscroe          = round(($rowdata["P_SET_PREV"]["score"]),2);
                        $pset_currentscroe       = round(($rowdata["P_SET_CURRENT"]["score"]),2);
                        
                        $cset_prevscroe          = round(($rowdata["C_SET_PREV"]["score"]),2);
                        $cset_currentscroe       = round(($rowdata["C_SET_CURRENT"]["score"]),2);                       
                        
                        @$pset_imporvement       = round((($pset_currentscroe - $pset_prevscroe)/$pset_prevscroe*100),2);
                        @$cset_imporvement       = round((($cset_currentscroe - $cset_prevscroe)/$cset_prevscroe*100),2);

                        @$pset_gap                = round(($pset_currentscroe - (@$rowdata["TARGET"]["pset_target"])),2);
                        @$cset_gap                = round(($cset_currentscroe - (@$rowdata["TARGET"]["cset_target"])),2);

                        echo '<tr>
                            <td style="border:1px solid #000 !important;" align="center">'.($key+1).'.</td>
                            <td style="border:1px solid #000 !important;" align="center">'.(@$rowdata["name"]).'</td>
                            <td bgcolor="#CCFFFF" style="border:1px solid #000 !important;" align="center">'.(@$pset_prevscroe).'</td>
                            <td bgcolor="#c5ff93" style="border:1px solid #000 !important;" align="center">'.(@$pset_currentscroe).'</td>
                            <td bgcolor="#f5d6ff" style="border:1px solid #000 !important;" align="center">'.(@$pset_imporvement).'</td>
                            <td bgcolor="#f0ad4e" style="border:1px solid #000 !important;" align="center">'.(@$rowdata["TARGET"]["pset_target"]).'</td>
                            <td bgcolor="#b3ccff" style="border:1px solid #000 !important;" align="center">'.(@$pset_gap).'</td>
                            <td style="border:1px solid #000 !important;" align="center"></td>
                            <td bgcolor="#CCFFFF" style="border:1px solid #000 !important;" align="center">'.(@$cset_prevscroe).'</td>
                            <td bgcolor="#c5ff93" style="border:1px solid #000 !important;" align="center">'.(@$cset_currentscroe).'</td>
                            <td bgcolor="#f5d6ff" style="border:1px solid #000 !important;" align="center">'.(@$cset_imporvement).'</td>
                            <td bgcolor="#f0ad4e" style="border:1px solid #000 !important;" align="center">'.(@$rowdata["TARGET"]["cset_target"]).'</td>
                            <td bgcolor="#b3ccff" style="border:1px solid #000 !important;" align="center">'.(@$cset_gap).'</td>  
                        </tr>';
                        $pset_prev_score += @$rowdata["P_SET_PREV"]["score"];
                        $pset_current_score += @$rowdata["P_SET_CURRENT"]["score"];
                        $cset_prev_score += @$rowdata["C_SET_PREV"]["score"];
                        $cset_current_score += @$rowdata["C_SET_CURRENT"]["score"];
                    }
                    ?>
                </tbody>
            </table>
        <div class="print1" style="float: left; border:1px solid #000; padding:3px 8px;">
                        NOTE : P-Sets including Call Centres</td>
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
    mywindow.document.write('<html><head><title>NON-MAINS DEPARTMENTS YTM PERFORMANCE AND GAP ANALYSIS</title>');
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