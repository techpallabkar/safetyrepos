<?php
/* MIS Report No. : 03
 * Name         :   MAINS SCORE CARD TYPE-1
 * Controller   :   mainsScoreCardType1Report()
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

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">3. MAINS SCORE CARD TYPE-1</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr />
    <form action="" method="post" id="fyr" enctype="multipart/form-data">
        <label class="col-sm-2">Select Month-Year:</label>
        <div class="col-sm-4">
            <input required="true" type="text" name="month_year_from" id="month_year_from" class="month_year_picker form-control" value = "<?php echo @$getDate['month_year']; ?>" style="width: 100%;" >
            <span id="financial_year_error"></span>
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="submitDate" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="mainsScoreCardType1Report.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr />
    <form action="" method="post" enctype="multipart/form-data">
        <?php if (!empty($getAuditData)) { ?>
            <div class="print1" style="float: right;">
                <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
            </div>
            <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel</button>
            <div id='TBL_EXPORT' style="overflow-x: scroll;">
                <div class="print-friendly" id="divToPrint">   
                    <table border="1" id="TblAuditData" class="table table-bordered table-condensed table-responsive" style="font-size:13px;font-weight:bold;" >
                        <thead class="bg-primary">
                            <tr><th colspan="21" class="text-left">
                                    <h4 class="text-uppercase text-center"><b>MAINS DEPARTMENT<br>SAFETY PERFORMANCE CARD<br>
                                            MONTH : <?php echo date("F, Y", strtotime('01-' . @$getDate['month_year'])); ?></b></h4>
                                </th></tr>
                            <tr>
                                <th rowspan="3" style="vertical-align:middle;">SL</th>
                                <th rowspan="3" style="vertical-align:middle;">DISTRICT</th>
                                <th  colspan="5" style="font-size:14px;">
                                    <?php
                                    @$PY = explode("-", str_replace(array("'", "AND"), array("-"), @$getDate["prevYear"]));
                                    echo "" . @$PY[1] . "-" . date("y", strtotime("01-01-" . @$PY[5]));
                                    ?>
                                </th>
                                <th rowspan="3"></th>
                                <th  colspan="6" style="font-size:14px;"><?php echo "" . date("M Y", strtotime("01-" . @$getDate["month_year"])); ?></th>
                                <th  colspan="7" style="font-size:14px;"><?php
                                    $PY = explode("-", str_replace(array("'", "AND"), array("-"), @$getDate["nextYear"]));
                                    echo "" . @$PY[1] . "-" . date("y", strtotime("01-01-" . @$PY[5]));
                                    ?></th>
                            </tr>
                            <tr>
                                <th style="vertical-align:middle;" colspan="2">P-SET</th>
                                <th style="vertical-align:middle;" colspan="2">C-SET</th>
                                <th style="vertical-align:middle;" rowspan="2">OVERALL</th>
                                <th style="vertical-align:middle;" colspan="2">P-SET</th>
                                <th style="vertical-align:middle;" colspan="2">C-SET</th>
                                <th style="vertical-align:middle;" colspan="2">OVERALL</th>
                                <th style="vertical-align:middle;" colspan="2">P-SET YTM</th>
                                <th style="vertical-align:middle;" colspan="2">C-SET YTM</th>
                                <th style="vertical-align:middle;" colspan="2">OVERALL YTM</th>
                                <th style="vertical-align:middle;" rowspan="2">IMPROVEMENT(%) </th>
                            </tr>
                            <tr>
                                <th>No of Audit</th>
                                <th>Score</th>
                                <th>No of Audit</th>
                                <th>Score</th>
                                <th>No of Audit</th>
                                <th>Score</th>
                                <th>No of Audit</th>
                                <th>Score</th>
                                <th>No of Audit</th>
                                <th>Score</th>
                                <th>No of Audit</th>
                                <th>Score</th>
                                <th>No of Audit</th>
                                <th>Score</th>
                                <th>No of Audit</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Sl = 1;
                            $PYPSETSCORETOT = $CY_OVERALL = $PYOVERALL_AVG = $CM_OVERALL = $PYOVERALL_AVG = $CMOVRAL_NOA_TOT = $CY_PSET_NOA_TOT= $CY_CSET_NOA_TOT = $CY_PCSET_NOA_TOT = $CYOVRAL_NOA_TOT = $PY_OVERALL =  $CY_OTHERS_NOA_TOT = $CM_OTHERS_NOA_TOT = $CM_CSET_NOA_TOT = $CM_PCSET_NOA_TOT = $CM_PSET_NOA_TOT = $PY_OTHERS_SCORE_AVG = $PYCSETSCORETOT  = $PYPCSETSCORETOT = $PYOTHERSCORETOT = $CMPSETSCORETOT = $CMCSETSCORETOT = $CMPCSETSCORETOT = $CMOTHERSCORETOT = $CYPSETSCORETOT = $CYCSETSCORETOT = $CYPCSETSCORETOT = $CYOTHERSCORETOT = $PY_PSET_NOA_TOT = $PY_CSET_NOA_TOT = $PY_PCSET_NOA_TOT = $PY_OTHERS_NOA_TOT = 0;
                            @$NOD = count(@$getAuditData['allDistrict']);
                            for ($i = 0; $i < $NOD; $i++) {
                                ?>
                                <tr>
                                    <td style="border:1px solid #000 !important;"><?php echo $Sl; ?>.</td>
                                    <td style="border:1px solid #000 !important;"><?php echo @$getAuditData['allDistrict'][$i][0]->name; ?></td>
                                    <td align="center" bgcolor="#e3ff96" style="border:1px solid #000 !important;"><?php echo $PY_PSET_NOA = round(@$getAuditData['P_SET_PREV'][$i]['totaldiv'], 2);$PY_PSET_NOA_TOT += $PY_PSET_NOA; ?></td>
                                    <td align="center"  style="border:1px solid #000 !important;"><?php echo $PY_PSET_SCORE = round(@$getAuditData['P_SET_PREV'][$i]['score'], 2); ?></td>
                                    <td align="center" bgcolor="#e3ff96" style="border:1px solid #000 !important;"><?php echo $PY_CSET_NOA = round(@$getAuditData['C_SET_PREV'][$i]['totaldiv'], 2);$PY_CSET_NOA_TOT += $PY_CSET_NOA; ?></td>
                                    <td align="center" style="border:1px solid #000 !important;"><?php echo $PY_CSET_SCORE = round(@$getAuditData['C_SET_PREV'][$i]['score'], 2); ?></td>
                                   
                                    <?php
                                    //----------P-SET---------------//
                                    $PYPSETSCORETOT += ($PY_PSET_NOA * $PY_PSET_SCORE);
                                    if($PYPSETSCORETOT != 0){$PY_PSET_SCORE_AVG = round($PYPSETSCORETOT / $PY_PSET_NOA_TOT, 2); } else{ $PY_PSET_SCORE_AVG = 0; }
                                    //----------C-SET---------------//
                                    $PYCSETSCORETOT += ($PY_CSET_NOA * $PY_CSET_SCORE);
                                    if( $PYCSETSCORETOT != 0 ){$PY_CSET_SCORE_AVG = round($PYCSETSCORETOT / $PY_CSET_NOA_TOT, 2);} else{ $PY_CSET_SCORE_AVG = 0; }
                                    //----------PC-SET---------------//
                                    ?>
                                    <td align="center" bgcolor="#B9CDE5" style="border:1px solid #000 !important;"><?php
                                    @$DDR1 = ($PY_PSET_NOA + $PY_CSET_NOA + $PY_OTHERS_NOA + $PY_OTHERS_SCORE);
                                    if($DDR1 !=0 ){
                                    echo @$PY_OVERALL = round(($PY_PSET_NOA * $PY_PSET_SCORE + $PY_CSET_NOA * $PY_CSET_SCORE + $PY_PCSET_NOA * $PY_PCSET_SCORE + $PY_OTHERS_NOA * $PY_OTHERS_SCORE) / $DDR1, 2); } else{ echo 0; }?></td>
                                    <td style="border:1px solid #000 !important;"></td>
                                    <!----2------>
                                    <td align="center" bgcolor="#e3ff96" style="border:1px solid #000 !important;"><?php echo $CM_PSET_NOA = round(@$getAuditData['P_SET_CURRENT'][$i]['totaldiv'], 2);
                                    $CM_PSET_NOA_TOT += $CM_PSET_NOA; ?></td>
                                    <td align="center" style="border:1px solid #000 !important;"><?php echo $CM_PSET_SCORE = round(@$getAuditData['P_SET_CURRENT'][$i]['score'], 2); ?></td>
                                    <td align="center" bgcolor="#e3ff96" style="border:1px solid #000 !important;"><?php echo $CM_CSET_NOA = round(@$getAuditData['C_SET_CURRENT'][$i]['totaldiv'], 2);
                                    $CM_CSET_NOA_TOT += $CM_CSET_NOA; ?></td>
                                    <td align="center" style="border:1px solid #000 !important;"><?php echo $CM_CSET_SCORE = round(@$getAuditData['C_SET_CURRENT'][$i]['score'], 2); ?></td>
                                    
                                    <?php
                                    //----------P-SET---------------//
                                    $CMPSETSCORETOT += ($CM_PSET_NOA * $CM_PSET_SCORE);
                                    @$CM_PSET_SCORE_AVG = round($CMPSETSCORETOT / $CM_PSET_NOA_TOT, 2);
                                    //----------C-SET---------------//
                                    $CMCSETSCORETOT += ($CM_CSET_NOA * $CM_CSET_SCORE);
                                    @$CM_CSET_SCORE_AVG = round($CMCSETSCORETOT / $CM_CSET_NOA_TOT, 2);
                                    ?>
                                    <td align="center" bgcolor="#B9CDE5" style="border:1px solid #000 !important;"><?php echo @$CM_OVRAL_NOA = round(($CM_PSET_NOA + $CM_CSET_NOA + $CM_PCSET_NOA + $CM_OTHERS_NOA), 2);
                                    @$CMOVRAL_NOA_TOT += $CM_OVRAL_NOA; ?></td>
                                    <td align="center" bgcolor="#B9CDE5" style="border:1px solid #000 !important;"><?php
                                    @$DDR3 = ($CM_PSET_NOA + $CM_CSET_NOA + $CM_OTHERS_NOA + $CM_OTHERS_SCORE);
                                    if($DDR3 != 0){
                                    echo @$CM_OVERALL = round(($CM_PSET_NOA * $CM_PSET_SCORE + $CM_CSET_NOA * $CM_CSET_SCORE + $CM_PCSET_NOA * $CM_PCSET_SCORE + $CM_OTHERS_NOA * $CM_OTHERS_SCORE) / $DDR3, 2); }else{ echo 0; }?></td>
                                    <!-------3---------->
                                    <td align="center" bgcolor="#e3ff96" style="border:1px solid #000 !important;"><?php echo $CY_PSET_NOA = round(@$getAuditData['P_SET_NEXT'][$i]['totaldiv'], 2);$CY_PSET_NOA_TOT += $CY_PSET_NOA; ?></td>
                                    <td align="center" style="border:1px solid #000 !important;"><?php echo $CY_PSET_SCORE = round(@$getAuditData['P_SET_NEXT'][$i]['score'], 2); ?></td>
                                    <td align="center" bgcolor="#e3ff96" style="border:1px solid #000 !important;"><?php echo $CY_CSET_NOA = round(@$getAuditData['C_SET_NEXT'][$i]['totaldiv'], 2);$CY_CSET_NOA_TOT += $CY_CSET_NOA; ?></td>
                                    <td align="center" style="border:1px solid #000 !important;"><?php echo $CY_CSET_SCORE = round(@$getAuditData['C_SET_NEXT'][$i]['score'], 2); ?></td>
                                    
                                    <?php
                                    //----------P-SET---------------//
                                    @$CYPSETSCORETOT += ($CY_PSET_NOA * $CY_PSET_SCORE);
                                    @$CY_PSET_SCORE_AVG = round($CYPSETSCORETOT / $CY_PSET_NOA_TOT, 2);
                                    //----------C-SET---------------//
                                    @$CYCSETSCORETOT += ($CY_CSET_NOA * $CY_CSET_SCORE);
                                    @$CY_CSET_SCORE_AVG = round($CYCSETSCORETOT / $CY_CSET_NOA_TOT, 2);
                                    ?>
                                    <td align="center" bgcolor="#B9CDE5" style="border:1px solid #000 !important;"><?php echo @$CY_OVRAL_NOA = round(($CY_PSET_NOA + $CY_CSET_NOA + $CY_PCSET_NOA + $CY_OTHERS_NOA), 2);
                                    @$CYOVRAL_NOA_TOT += $CY_OVRAL_NOA; ?></td>
                                    <td align="center" bgcolor="#B9CDE5" style="border:1px solid #000 !important;"><?php
                                    @$SATA = ($CY_PSET_NOA + $CY_CSET_NOA + $CY_OTHERS_NOA + $CY_OTHERS_SCORE);
                                    if($SATA !=0 ){
                                    echo @$CY_OVERALL = round(($CY_PSET_NOA * $CY_PSET_SCORE + $CY_CSET_NOA * $CY_CSET_SCORE + $CY_PCSET_NOA * $CY_PCSET_SCORE + $CY_OTHERS_NOA * $CY_OTHERS_SCORE) / $SATA, 2); }else{ echo 0; }?></td>
                                    <th bgcolor="#DDD9C3"  style="border:1px solid #000 !important;"><?php if($PY_OVERALL !=0){ echo $IMPRIVEMENT = round((($CY_OVERALL - $PY_OVERALL) / $PY_OVERALL * 100), 2); }else{ echo 0; }?>%</th>
                                </tr>
                                <?php $Sl++;
                            } ?>

                            <tr style="font-size:14px;"> 
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;" colspan="2">TOTAL / AVG</th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $PY_PSET_NOA_TOT; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $PY_PSET_SCORE_AVG; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $PY_CSET_NOA_TOT; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $PY_CSET_SCORE_AVG; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php
                                $DDR2 = ($PY_PSET_NOA_TOT + $PY_CSET_NOA_TOT + $PY_PCSET_NOA_TOT + $PY_OTHERS_NOA_TOT);
                                if($DDR2){
                                echo @$PYOVERALL_AVG = round(($PY_PSET_NOA_TOT * $PY_PSET_SCORE_AVG + $PY_CSET_NOA_TOT * $PY_CSET_SCORE_AVG + $PY_PCSET_NOA_TOT * $PY_PCSET_SCORE_AVG + $PY_OTHERS_NOA_TOT * $PY_OTHERS_SCORE_AVG) / $DDR2, 2); } ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $CM_PSET_NOA_TOT; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $CM_PSET_SCORE_AVG; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $CM_CSET_NOA_TOT; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $CM_CSET_SCORE_AVG; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $CMOVRAL_NOA_TOT; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$CMOVERALL_AVG = round(($CM_PSET_NOA_TOT * $CM_PSET_SCORE_AVG + $CM_CSET_NOA_TOT * $CM_CSET_SCORE_AVG + $CM_PCSET_NOA_TOT * $CM_PCSET_SCORE_AVG + $CM_OTHERS_NOA_TOT * $CM_OTHERS_SCORE_AVG) / ($CM_PSET_NOA_TOT + $CM_CSET_NOA_TOT + $CM_PCSET_NOA_TOT + $CM_OTHERS_NOA_TOT), 2); ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $CY_PSET_NOA_TOT; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $CY_PSET_SCORE_AVG; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $CY_CSET_NOA_TOT; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $CY_CSET_SCORE_AVG; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo $CYOVRAL_NOA_TOT; ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$CYOVERALL_AVG = round(($CY_PSET_NOA_TOT * $CY_PSET_SCORE_AVG + $CY_CSET_NOA_TOT * $CY_CSET_SCORE_AVG + $CY_PCSET_NOA_TOT * $CY_PCSET_SCORE_AVG + $CY_OTHERS_NOA_TOT * $CY_OTHERS_SCORE_AVG) / ($CY_PSET_NOA_TOT + $CY_CSET_NOA_TOT + $CY_PCSET_NOA_TOT + $CY_OTHERS_NOA_TOT), 2); ?></th>
                                <th bgcolor="#FCD5B5" style="border:1px solid #000 !important;"><?php echo @$IMPRIVEMENT_TOT = round((($CYOVERALL_AVG - $PYOVERALL_AVG) / $PYOVERALL_AVG * 100), 2); ?></th>

                            </tr>
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
<script type='text/javascript'>
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
    
    function PrintDiv()
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=300');
        var content = document.getElementById("divToPrint").innerHTML;
        mywindow.document.write('<html><head><title>MAINS DEPARTMENT SAFETY PERFORMANCE CARD</title>');
        mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}</style></head><body>');
        mywindow.document.write(content);
        mywindow.document.write('</body></html>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/
        mywindow.print();
        mywindow.close();
        return true;
    }
</script>