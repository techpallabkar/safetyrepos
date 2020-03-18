<?php
/* MIS Report No. : 11
 * Name         :   Suraksha Barta IncIdent Statistics
 * Controller   :   surakshaBartaIncIdentStatics()
 * Dao          :   getAuditData
 * Created By Anima
 * Modified Pallab
 */

if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$REPOTED_ACC_STAT_PSET      =   $beanUi->get_view_data("REPOTED_ACC_STAT_PSET");
$REPOTED_ACC_STAT_CSET      =   $beanUi->get_view_data("REPOTED_ACC_STAT_CSET");
$REPOTED_ACC_STAT_PCSET     =   $beanUi->get_view_data("REPOTED_ACC_STAT_PCSET");
$REPOTED_ACC_STAT_OTHERS    =   $beanUi->get_view_data("REPOTED_ACC_STAT_OTHERS");
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
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">11. Total Incident Statistics </h1>
        <form action="" method="post" id="fyr" enctype="multipart/form-data">
        
        <label class="col-sm-2">Select Month</label>
        <div class="col-sm-4">
            <input required="true" type="text" name="month_year_from" id="month_year_from" value="<?php echo @$_REQUEST["month_year_from"]; ?>" class="month_year_picker form-control" value = "<?php echo @$getDate['month_year']; ?>" style="width: 100%;" />
            <span id="financial_year_error"></span>
           
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="submitData" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="surakshaBartaIncIdentStatics.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr/>
    <!--REPORTED ACCIDENT STATISTICS OF PERMANENT EMPLOYEES--> 
    <?php if(!empty($_POST)) { ?>
    <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
        Export to Excel</button>
    <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
    </div>
    <div id='TBL_EXPORT'>
        <div class="print-friendly" id="divToPrint">
            <form action="" method="post" id="p_set" enctype="multipart/form-data">
                <?php if (!empty($REPOTED_ACC_STAT_PSET)) { ?>
                    <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;font-weight:bold;" >
                        <thead class="bg-primary">
                            <tr>
                                <th rowspan="3">SL</th>
                                <th rowspan="3"> DEPT. </th>
                                <th  colspan="8"> REPORTED ACCIDENT STATISTICS OF PERMANENT EMPLOYEES UPTO MONTH : <?php echo strtoupper(date("F", strtotime('01-' . @$getDate['month_year']))); ?> </th>
                            </tr>
                            <tr>
                                <?php
                                foreach ($REPOTED_ACC_STAT_PSET as $key => $rowdata1) {
                                    $inc_category = $rowdata1["INCCAT"];
                                }
                                for ($d = 1; $d <= count($inc_category); $d++) {
                                    ?>
                                    <th colspan="2" ><?php echo $inc_category[$d]['NAME'] ?></th>
                            <?php } ?>
                                <th colspan="2">TOTAL</th>
                            </tr>
                            <tr>
                            <?php for ($i = 1; $i <= 4; $i++) { ?>
                                    <th><?php @$PREV_Y1 = explode("-", str_replace(array("'", "AND"), array("-"), @$getDate["PREV_Y1"]));
                                        echo "" . @$PREV_Y1[1] . "-" . @$PREV_Y1[5]; ?></th>
                                    <th><?php @$CURR_Y = explode("-", str_replace(array("'", "AND"), array("-"), @$getDate["CURR_Y"]));
                            echo "YTM ' " . @$CURR_Y[1] . "-" . @$CURR_Y[5]; ?></th>
                            <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $slno=0;
                            $a1 = $a2 = $a3= "";
                            $psetnewarray = array();
                            foreach ($REPOTED_ACC_STAT_PSET as $key => $rowdata) {
                                $PREV_YEAR1 = $CURRENT_YEAR = 0;
                                $inc_category = $rowdata["INCCAT"];
                                $tid=$rowdata["district_id"];
                                if($tid != 4 && $tid != 10 ) {
                                    $slno++;
                                echo '<tr>
                             <td align="center" style="border-color:#000;" >' . ($slno) . '.</td>
                             <td style="border-color:#000;" >' . $rowdata["name"] . '</td>';

                                $YR = 1;
                                foreach ($inc_category as $k => $value) {
                                    if( $tid == 168 ) {
                                        $psetnewarray[$value["ID"]]["P1"]    =   $value["PREV_YEAR1"];
                                        $psetnewarray[$value["ID"]]["C"]     =   $value["CURRENT_YEAR"];
                                    }
                                    echo '<td style="border-color:#000;" align="center" class="PSETPREVYRDEDUCT_'.$inc_category[$YR]["ID"].'_'.$tid.' P_' . $inc_category[$YR]["NAME"] . '_P1">' . $value["PREV_YEAR1"] . '</td>
                                    <td bgcolor="#DBEEF4" style="border-color:#000;" align="center" class="PSETCURYRDEDUCT_'.$inc_category[$YR]["ID"].'_'.$tid.' P_' . $inc_category[$YR]["NAME"] . '_C">' . $value["CURRENT_YEAR"] . '</td>';


                                    $PREV_YEAR1 += $value["PREV_YEAR1"];
                                    $CURRENT_YEAR += $value["CURRENT_YEAR"];
                                    $YR++;
                                }
                                echo '<th bgcolor="#f0ad4e" style="border-color:#000;" class="P_PREV_YEAR1">' . $PREV_YEAR1 . '</th>
                              <th bgcolor="#f0ad4e" style="border-color:#000;" class="P_CURRENT_YEAR">' . $CURRENT_YEAR . '</th></tr>';
                                    } 
                                 
                                }
                            foreach ($REPOTED_ACC_STAT_PSET as $key => $rowdata) {
                                $PREV_YEAR1 = $CURRENT_YEAR = 0;
                                $inc_category = $rowdata["INCCAT"];
                                $tid=$rowdata["district_id"];
                                if($tid == 4 ) {
                                    $slno++;
                                echo '<tr>
                             <td align="center" style="border-color:#000;" >' . ($slno) . '.</td>
                             <td style="border-color:#000;" >' . $rowdata["name"] . '</td>';

                                $YR = 1;
                                foreach ($inc_category as $k => $value) {
                                    $p1 = $psetnewarray[$value["ID"]]["P1"];
                                    $c = $psetnewarray[$value["ID"]]["C"];
                                    $totprev1 = $value["PREV_YEAR1"] - $p1;
                                    $totcur = $value["CURRENT_YEAR"] - $c;
                                    echo '<td style="border-color:#000;" align="center" class="PSETPREVYRDEDUCT_'.$inc_category[$YR]["ID"].'_'.$tid.' P_' . $inc_category[$YR]["NAME"] . '_P1">' . $totprev1 . '</td>
                              <td bgcolor="#DBEEF4" style="border-color:#000;" align="center" class="PSETCURYRDEDUCT_'.$inc_category[$YR]["ID"].'_'.$tid.' P_' . $inc_category[$YR]["NAME"] . '_C">' . $totcur . '</td>';


                                    $PREV_YEAR1 += $totprev1;
                                    $CURRENT_YEAR += $totcur;
                                    $YR++;
                                }
                                echo '<th bgcolor="#f0ad4e" style="border-color:#000;" class="P_PREV_YEAR1">' . $PREV_YEAR1 . '</th>
                              <th bgcolor="#f0ad4e" style="border-color:#000;" class="P_CURRENT_YEAR">' . $CURRENT_YEAR . '</th></tr>';
                                 } }
                            ?>
                            <tr  style="font-size: 16px;background:#FDEADA;"> 
                                <th style="border-color:#000;" colspan="2"> TOTAL </th>

                                <th style="border-color:#000;" id="P_TOT_FAC_P1"></th>
                                <th style="border-color:#000;" id="P_TOT_FAC_C"></th>

                                <th style="border-color:#000;" id="P_TOT_LWDC_P1"></th>
                                <th style="border-color:#000;" id="P_TOT_LWDC_C"></th>

                                <th style="border-color:#000;" id="P_TOT_FATAL_P1"></th>
                                <th style="border-color:#000;" id="P_TOT_FATAL_C"></th>

                                <th style="border-color:#000;" id="P_TOT_P1"></th>
                                <th style="border-color:#000;" id="P_TOT_C"></th>
                            </tr>
                        </tbody>
                    </table>
<?php } ?>
            </form>


            <!--REPORTED ACCIDENT STATISTICS OF CONTRACTOR EMPLOYEES--> 
            <form action="" method="post" id="c_set" enctype="multipart/form-data">
<?php if (!empty($REPOTED_ACC_STAT_CSET)) { ?>
                    <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;font-weight:bold;" >
                        <thead class="bg-primary">
                            <tr>
                                <th rowspan="3">SL</th>
                                <th rowspan="3"> DEPT. </th>
                                <th  colspan="8"> REPORTED ACCIDENT STATISTICS OF CONTRACTOR EMPLOYEES UPTO MONTH : <?php echo strtoupper(date("F", strtotime('01-' . @$getDate['month_year']))); ?></th>
                            </tr>
                            <tr>
                                <?php
                                foreach ($REPOTED_ACC_STAT_CSET as $key => $rowdata2) {
                                    $inc_category = $rowdata2["INCCAT"];
                                }
                                for ($d = 1; $d <= count($inc_category); $d++) {
                                    ?>
                                    <th colspan="2"><?php echo $inc_category[$d]['NAME'] ?></th>
                                <?php } ?>
                                <th colspan="2">TOTAL</th>
                            </tr>
                            <tr>
    <?php for ($i = 1; $i <= 4; $i++) { ?>
                                    <th><?php @$PREV_Y1 = explode("-", str_replace(array("'", "AND"), array("-"), @$getDate["PREV_Y1"]));
        echo "" . @$PREV_Y1[1] . "-" . @$PREV_Y1[5]; ?></th>
                                    <th><?php @$CURR_Y = explode("-", str_replace(array("'", "AND"), array("-"), @$getDate["CURR_Y"]));
        echo "YTM ' " . @$CURR_Y[1] . "-" . @$CURR_Y[5]; ?></th>
                            <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $slno=0;
                            $a4 = $a5 = $a6= "";
                            $csetnewarray = array();
                            foreach ($REPOTED_ACC_STAT_CSET as $key => $rowdata) {
                                $PREV_YEAR1 = $CURRENT_YEAR = 0;
                                $inc_category = $rowdata["INCCAT"];
                                $tid = $rowdata["district_id"];
                                if( $tid != 4 ) {
                                     $slno++;
                                    echo '<tr>
                                    <td align="center" style="border-color:#000;">' . ($slno) . '.</td>
                                    <td style="border-color:#000;" >' . @$rowdata["name"] . '</td>';
                                    $YR = 1;
                                    foreach ($inc_category as $k => $value) {
                                        if( $tid == 168 ) {
                                            $psetnewarray[$value["ID"]]["P1"]    =   $value["PREV_YEAR1"];
                                            $psetnewarray[$value["ID"]]["C"]     =   $value["CURRENT_YEAR"];
                                        }
                                        echo '<td  style="border-color:#000;" align="center" class="CSETPREVYRDEDUCT_'.$inc_category[$YR]["ID"].'_'.$tid.' C_' . $inc_category[$YR]["NAME"] . '_P1">' . $value["PREV_YEAR1"] . '</td>
                                        <td bgcolor="#E6E0EC" style="border-color:#000;" align="center" class="CSETCURYRDEDUCT_'.$inc_category[$YR]["ID"].'_'.$tid.' C_' . $inc_category[$YR]["NAME"] . '_C">' . $value["CURRENT_YEAR"] . '</td>';
                                        $PREV_YEAR1 += $value["PREV_YEAR1"];
                                        $CURRENT_YEAR += $value["CURRENT_YEAR"];
                                        $YR++;
                                    }
                                    echo '<th bgcolor="#f0ad4e" style="border-color:#000;" class="C_PREV_YEAR1">' . $PREV_YEAR1 . '</th>
                                    <th bgcolor="#f0ad4e" style="border-color:#000;" class="C_CURRENT_YEAR">' . $CURRENT_YEAR . '</th></tr>';
                                } 
                            
                            }
                            foreach ($REPOTED_ACC_STAT_CSET as $key => $rowdata) {
                                $CSETPREV_YEAR1 = $CSETCURRENT_YEAR = 0;
                                $inc_category = $rowdata["INCCAT"];
                                $tid = $rowdata["district_id"];
                                if( $tid == 4 ) {
                                    echo '<tr>
                                    <td align="center" style="border-color:#000;">' . ($key + 1) . '.</td>
                                    <td style="border-color:#000;" >' . @$rowdata["name"] . '</td>';
                                    $YR = 1;
                                    foreach ($inc_category as $k => $value) {
                                        $p1 = $psetnewarray[$value["ID"]]["P1"];
                                        $c = $psetnewarray[$value["ID"]]["C"];
                                        $totprev1 = $value["PREV_YEAR1"] - $p1;
                                        $totcur = $value["CURRENT_YEAR"] - $c;
                                        echo '<td  style="border-color:#000;" align="center" class="CSETPREVYRDEDUCT_'.$inc_category[$YR]["ID"].'_'.$tid.' C_' . $inc_category[$YR]["NAME"] . '_P1">' . $totprev1 . '</td>
                                        <td bgcolor="#E6E0EC" style="border-color:#000;" align="center" class="CSETCURYRDEDUCT_'.$inc_category[$YR]["ID"].'_'.$tid.' C_' . $inc_category[$YR]["NAME"] . '_C">' . $totcur . '</td>';
                                        $CSETPREV_YEAR1 += $totprev1;
                                        $CSETCURRENT_YEAR += $totcur;
                                        $YR++;
                                    }
                                    echo '<th bgcolor="#f0ad4e" style="border-color:#000;" class="C_PREV_YEAR1">' . $CSETPREV_YEAR1 . '</th>
                                    <th bgcolor="#f0ad4e" style="border-color:#000;" class="C_CURRENT_YEAR">' . $CSETCURRENT_YEAR . '</th></tr>';
                                } 
                            
                            }
                            ?>
                            <tr  style="font-size: 16px;background:#FDEADA;"> 
                                <th style="border-color:#000;" colspan="2"> TOTAL </th>

                                <th style="border-color:#000;" id="C_TOT_FAC_P1"></th>
                                <th style="border-color:#000;" id="C_TOT_FAC_C"></th>

                                <th style="border-color:#000;" id="C_TOT_LWDC_P1"></th>
                                <th style="border-color:#000;" id="C_TOT_LWDC_C"></th>

                                <th style="border-color:#000;" id="C_TOT_FATAL_P1"></th>
                                <th style="border-color:#000;" id="C_TOT_FATAL_C"></th>

                                <th style="border-color:#000;" id="C_TOT_P1"></th>
                                <th style="border-color:#000;" id="C_TOT_C"></th>
                            </tr>
                        </tbody>
                    </table>
<?php } ?>
            </form>

            <div style="clear:both;" class="clearfix" ></div>
            <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php $controller->get_footer();
 //showPre($inc_category);
?>

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

    function get_others_except_syscontrol(type,incid) {
        var val1 =  $("."+type+"PREVYRDEDUCT_"+incid+"_168").text();
      var val2 =  $("."+type+"PREVYRDEDUCT_"+incid+"_4").text();
      var val3 =  $("."+type+"CURYRDEDUCT_"+incid+"_168").text();
      var val4 =  $("."+type+"CURYRDEDUCT_"+incid+"_4").text();
    
      var totalvalue1 = parseInt(val2)-parseInt(val1);
      var totalvalue2 = parseInt(val4)-parseInt(val3);
   
      $("."+type+"PREVYRDEDUCT_"+incid+"_4").text(totalvalue1);
      $("."+type+"CURYRDEDUCT_"+incid+"_4").text(totalvalue2);
    }
    $(document).ready(function () {
        $("#ExportExcel").click(function (e) {
            var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html());
            window.open(path);
            e.preventDefault();
        });
    });
//------P-SET-TOTAL-----//     
    $(document).ready(function () {

        var P_FAC_P1 = 0;
        $('.P_FAC_P1').each(function () {
            var p1val = $(this).text()
            P_FAC_P1 += parseInt(p1val);
        });
        $("#P_TOT_FAC_P1").text(P_FAC_P1);

        var P_FAC_C = 0;
        $('.P_FAC_C').each(function () {
            var cval = $(this).text()
            P_FAC_C += parseInt(cval);
        });
        $("#P_TOT_FAC_C").text(P_FAC_C);
//------------ END-FAC--------------//

        var P_LWDC_P1 = 0;
        $('.P_LWDC_P1').each(function () {
            var p1val = $(this).text()
            P_LWDC_P1 += parseInt(p1val);
        });
        $("#P_TOT_LWDC_P1").text(P_LWDC_P1);

        var P_LWDC_C = 0;
        $('.P_LWDC_C').each(function () {
            var cval = $(this).text()
            P_LWDC_C += parseInt(cval);
        });
        $("#P_TOT_LWDC_C").text(P_LWDC_C);
   
//------------ END-LWDC--------------//

    var P_FATAL_P1 = 0;
    $('.P_FATAL_P1').each(function () {
        var p1val = $(this).text()
        P_FATAL_P1 += parseInt(p1val);
    });
    $("#P_TOT_FATAL_P1").text(P_FATAL_P1);

    var P_FATAL_C = 0;
    $('.P_FATAL_C').each(function () {
        var cval = $(this).text()
        P_FATAL_C += parseInt(cval);
    });
    $("#P_TOT_FATAL_C").text(P_FATAL_C);
//------------ END-FATAL--------------

    var P_PREV_YEAR1 = 0;
    $('.P_PREV_YEAR1').each(function () {
        var p1val = $(this).text()
        P_PREV_YEAR1 += parseInt(p1val);
    });
    $("#P_TOT_P1").text(P_PREV_YEAR1);

    var P_CURRENT_YEAR = 0;
    $('.P_CURRENT_YEAR').each(function () {
        var cval = $(this).text()
        P_CURRENT_YEAR += parseInt(cval);
    });
    $("#P_TOT_C").text(P_CURRENT_YEAR);
//------------ END-TOT---------//
//------END P-SET-TOTAL-----// 

//------C-SET-TOTAL-----// 
    //$(document).ready(function () {


        var C_FAC_P1 = 0;
        $('.C_FAC_P1').each(function () {
            var p1val = $(this).text()
            C_FAC_P1 += parseInt(p1val);
        });
        $("#C_TOT_FAC_P1").text(C_FAC_P1);

        var C_FAC_C = 0;
        $('.C_FAC_C').each(function () {
            var cval = $(this).text()
            C_FAC_C += parseInt(cval);
        });
        $("#C_TOT_FAC_C").text(C_FAC_C);
//------------ END-FAC--------------//


        var C_LWDC_P1 = 0;
        $('.C_LWDC_P1').each(function () {
            var p1val = $(this).text()
            C_LWDC_P1 += parseInt(p1val);
        });
        $("#C_TOT_LWDC_P1").text(C_LWDC_P1);

        var C_LWDC_C = 0;
        $('.C_LWDC_C').each(function () {
            var cval = $(this).text()
            C_LWDC_C += parseInt(cval);
        });
        $("#C_TOT_LWDC_C").text(C_LWDC_C);

//------------ END-LWDC-------------//


        var C_FATAL_P1 = 0;
        $('.C_FATAL_P1').each(function () {
            var p1val = $(this).text()
            C_FATAL_P1 += parseInt(p1val);
        });
        $("#C_TOT_FATAL_P1").text(C_FATAL_P1);

        var C_FATAL_C = 0;
        $('.C_FATAL_C').each(function () {
            var cval = $(this).text()
            C_FATAL_C += parseInt(cval);
        });
        $("#C_TOT_FATAL_C").text(C_FATAL_C);
//------------ END-FATAL------------//


        var C_PREV_YEAR1 = 0;
        $('.C_PREV_YEAR1').each(function () {
            var cp1val = $(this).text()
            C_PREV_YEAR1 += parseInt(cp1val);
        });
        $("#C_TOT_P1").text(C_PREV_YEAR1);


        var C_CURRENT_YEAR = 0;
        $('.C_CURRENT_YEAR').each(function () {
            var cval = $(this).text()
            C_CURRENT_YEAR += parseInt(cval);
        });
        $("#C_TOT_C").text(C_CURRENT_YEAR);
    });
//------------ END-TOT-----------//
//------END C-SET-TOTAL-----//

    $(document).ready(function () {

<?php
if(!empty($inc_category)){
foreach ($inc_category as $key => $value) { ?>
     
      get_others_except_syscontrol('PSET',<?php echo $value["ID"]; ?>);
      get_others_except_syscontrol('CSET',<?php echo $value["ID"]; ?>);
      
        
<?php }} ?>


    });


    function PrintDiv()
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=300');
        var content = document.getElementById("divToPrint").innerHTML;
        mywindow.document.write('<html><head><title>SURAKSHA BARTA INCIDENT STATISTICS</title>');
        mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {padding-top:3px;background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}</style></head><body>');
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