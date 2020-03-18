<?php
if (file_exists("../../lib/var.inc.php"))
require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$REPOTED_ACC_STAT_PSET = $beanUi->get_view_data("REPOTED_ACC_STAT_PSET");
$REPOTED_ACC_STAT_CSET = $beanUi->get_view_data("REPOTED_ACC_STAT_CSET");
$REPOTED_ACC_STAT_PCSET = $beanUi->get_view_data("REPOTED_ACC_STAT_PCSET");
$REPOTED_ACC_STAT_OTHERS = $beanUi->get_view_data("REPOTED_ACC_STAT_OTHERS");
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
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">18. INCIDENT 10 DISTRICT & OTHER ESTABLISHMENT (3 YEARS)</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <form action="" method="post" id="fyr" enctype="multipart/form-data">        
        <label class="col-sm-2">Select Month</label>
        <div class="col-sm-4">
            <input required="true" type="text" name="month_year_from" id="month_year_from" value="<?php echo @$_REQUEST["month_year_from"]; ?>" class="month_year_picker form-control" value = "<?php echo @$getDate['month_year']; ?>" style="width: 100%;" />
            <span id="financial_year_error"></span>           
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="submitData" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="incident10DistAndOtherEstb3YearsReport.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr/>
     <!--REPORTED ACCIDENT STATISTICS OF PERMANENT EMPLOYEES--> 
    <?php if(!empty($_POST)) { ?>
    <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>Export to Excel</button>
    <div class="print1" style="float: right;">
    <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
    </div>
    <div id='TBL_EXPORT'>
    <div class="print-friendly" id="divToPrint">
    <?php  if(!empty($REPOTED_ACC_STAT_PSET)){ ?>
    <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;font-weight: bold;" >
        <thead class="bg-primary">
            <tr>
                <th rowspan="3">SL</th>
                <th rowspan="3"> DEPT. </th>
                <th  colspan="12"> REPORTED ACCIDENT STATISTICS OF PERMANENT EMPLOYEES TILL: <?php echo date("F, Y", strtotime('01-' . @$_REQUEST["month_year_from"])); ?></th>
            </tr>
            <tr>
               <?php
                foreach($REPOTED_ACC_STAT_PSET as $key => $rowdata1) {
                $inc_category = $rowdata1["INCCAT"];
                }
                for($d = 1; $d <= count($inc_category); $d++){ ?>
                <th colspan="3"><?php echo $inc_category[$d]['NAME'] ?></th>
                <?php } ?>
                <th colspan="3">TOTAL</th>
            </tr>
            <tr>
                <?php for($i=1; $i<=4;$i++){ ?>
                <th><?php @$PREV_Y2 = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["PREV_Y2"])); echo "".@$PREV_Y2[1]."-".@$PREV_Y2[5]; ?></th>
                <th><?php @$PREV_Y1 = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["PREV_Y1"])); echo "".@$PREV_Y1[1]."-".@$PREV_Y1[5]; ?></th>
                <th>YTM ' <?php @$CURR_Y = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["CURR_Y"])); echo "".@$CURR_Y[1]."-".@$CURR_Y[5]; ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php
        $a1 = $a2 = $a3= "";
        $newarray = array();
        $slno=0;
        foreach($REPOTED_ACC_STAT_PSET as $key => $rowdata) {
            $tid = $rowdata["district_id"];
            if($tid != 4 && $tid != 10 ) {
                $slno++;
                $PREV_YEAR2 = $PREV_YEAR1 = $CURRENT_YEAR = 0;
                $inc_category = $rowdata["INCCAT"];
                echo '<tr>
                <td style="border:1px solid #000;">'.($slno).'.</td>
                <td style="border:1px solid #000;">'.$rowdata["name"].'</td>';
                $YR = 1;
                foreach ($inc_category as $k => $value) {
                    if( $tid == 168 ) {
                       $newarray[$value["ID"]]["P2"]    =   $value["PREV_YEAR2"];
                       $newarray[$value["ID"]]["P1"]    =   $value["PREV_YEAR1"];
                       $newarray[$value["ID"]]["C"]     =   $value["CURRENT_YEAR"];
                    }
                    echo '<td align="center" style="border:1px solid #000;" bgcolor="#B9CDE"  class="P_'.$inc_category[$YR]["NAME"].'_P2">'.$value["PREV_YEAR2"].'</td>
                    <td align="center" style="border:1px solid #000;" bgcolor="#e3ff96" class="P_'.$inc_category[$YR]["NAME"].'_P1">'.$value["PREV_YEAR1"].'</td>
                    <td align="center" style="border:1px solid #000;" bgcolor="#feccff" class="P_'.$inc_category[$YR]["NAME"].'_C">'.$value["CURRENT_YEAR"].'</td>';
                    $PREV_YEAR2 +=$value["PREV_YEAR2"];
                    $PREV_YEAR1 +=$value["PREV_YEAR1"];
                    $CURRENT_YEAR +=$value["CURRENT_YEAR"];
                    $YR++;
                }
                echo '<th bgcolor="#f0ad4e" style="border:1px solid #000;" class="P_PREV_YEAR2">'. $PREV_YEAR2.'</th>
                <th bgcolor="#f0ad4e" style="border:1px solid #000;" class="P_PREV_YEAR1">'.$PREV_YEAR1.'</th>
                <th bgcolor="#f0ad4e" style="border:1px solid #000;" class="P_CURRENT_YEAR">'.$CURRENT_YEAR.'</th></tr>';
            } 
            
        }
        foreach($REPOTED_ACC_STAT_PSET as $key => $rowdata) {
            $tid = $rowdata["district_id"];
            if($tid == 4) {
                $slno++;
                 $POPREV_YEAR2 = $POPREV_YEAR1 = $POCURRENT_YEAR = 0;
                $inc_category = $rowdata["INCCAT"];
                echo '<tr>
                <td style="border:1px solid #000;">'.($slno).'.</td>
                <td style="border:1px solid #000;">'.$rowdata["name"].'</td>';
                $YR = 1;
                foreach ($inc_category as $k => $value)    {
                    $p2 = $newarray[$value["ID"]]["P2"];
                    $p1 = $newarray[$value["ID"]]["P1"];
                    $c = $newarray[$value["ID"]]["C"];
                    $totprev2 = $value["PREV_YEAR2"] - $p2;
                    $totprev1 = $value["PREV_YEAR1"] - $p1;
                    $totcur = $value["CURRENT_YEAR"] - $c;
                    echo '<td align="center" style="border:1px solid #000;" bgcolor="#B9CDE"  class="P_'.$inc_category[$YR]["NAME"].'_P2">'.$totprev2.'</td>
                          <td align="center" style="border:1px solid #000;" bgcolor="#e3ff96" class="P_'.$inc_category[$YR]["NAME"].'_P1">'.$totprev1.'</td>
                          <td align="center" style="border:1px solid #000;" bgcolor="#feccff" class="P_'.$inc_category[$YR]["NAME"].'_C">'.$totcur.'</td>';

                    $POPREV_YEAR2 +=$totprev2;
                    $POPREV_YEAR1 +=$totprev1;
                    $POCURRENT_YEAR +=$totcur;
                    $YR++;
                }
                echo '<th bgcolor="#f0ad4e" style="border:1px solid #000;" class="P_PREV_YEAR2">'.$POPREV_YEAR2.'</th>
                <th bgcolor="#f0ad4e" style="border:1px solid #000;" class="P_PREV_YEAR1">'.$POPREV_YEAR1.'</th>
                <th bgcolor="#f0ad4e" style="border:1px solid #000;" class="P_CURRENT_YEAR">'.$POCURRENT_YEAR.'</th></tr>';
            }
        }
        ?>
        <tr> 
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" colspan="2"> TOTAL </th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_FAC_P2"></th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_FAC_P1"></th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_FAC_C"></th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_LWDC_P2"></th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_LWDC_P1"></th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_LWDC_C"></th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_FATAL_P2"></th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_FATAL_P1"></th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_FATAL_C"></th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_P2"></th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_P1"></th>
            <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="P_TOT_C"></th>
        </tr>
        </tbody>
        </table>
    <?php } ?>
<!--REPORTED ACCIDENT STATISTICS OF CONTRACTOR EMPLOYEES--> 
    <?php  if(!empty($REPOTED_ACC_STAT_CSET)){ ?>
    <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;font-weight: bold;" >
            <thead class="bg-primary">
                <tr>
                    <th rowspan="3">SL</th>
                    <th rowspan="3"> DEPT. </th>
                    <th  colspan="12"> REPORTED ACCIDENT STATISTICS OF CONTRACTOR EMPLOYEES TILL: <?php echo date("F, Y", strtotime('01-' . @$_REQUEST["month_year_from"])); ?></th>
                </tr>
                
                <?php
                    echo '<tr>';
                    foreach($REPOTED_ACC_STAT_CSET as $key => $rowdata2) {
                    $inc_category = $rowdata2["INCCAT"];
                    }
                    for($d = 1; $d <= count($inc_category); $d++){ 
                    echo '<th colspan="3">'.$inc_category[$d]['NAME'].'</th>';
                    }
                    echo '<th colspan="3">TOTAL</th>';
                    echo '</tr>';
                 ?>
                <tr>
                    <?php for($i=1; $i<=4;$i++){ ?>
                    <th><?php @$PREV_Y2 = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["PREV_Y2"])); echo "".@$PREV_Y2[1]."-".@$PREV_Y2[5]; ?></th>
                    <th><?php @$PREV_Y1 = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["PREV_Y1"])); echo "".@$PREV_Y1[1]."-".@$PREV_Y1[5]; ?></th>
                    <th>YTM ' <?php @$CURR_Y = explode("-",str_replace(array("'","AND"),array("-"),@$getDate["CURR_Y"])); echo "".@$CURR_Y[1]."-".@$CURR_Y[5]; ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
               <?php
                $a4 = $a5 = $a6= "";
                $csetnewarray = array();
                foreach($REPOTED_ACC_STAT_CSET as $key => $rowdata)  {
                    $PREV_YEAR2 = $PREV_YEAR1 = $CURRENT_YEAR = 0;
                    $tid = $rowdata["district_id"];
                    $inc_category = $rowdata["INCCAT"];
                    if($tid != 4) {
                        echo '<tr>
                        <td style="border:1px solid #000;">'.($key+1).'.</td>
                        <td style="border:1px solid #000;">'.$rowdata["name"].'</td>';
                        $YR = 1;
                        foreach ($inc_category as $k => $value) {
                            if( $tid == 168 ) {
                                @$newarray[$value["ID"]]["P2"]    =   $value["PREV_YEAR2"];
                                @$newarray[$value["ID"]]["P1"]    =   $value["PREV_YEAR1"];
                                @$newarray[$value["ID"]]["C"]     =   $value["CURRENT_YEAR"];
                            }
                            echo '<td align="center" style="border:1px solid #000;" bgcolor="#B9CDE"  class="C_'.$inc_category[$YR]["NAME"].'_P2">'.$value["PREV_YEAR2"].'</td>
                            <td align="center" style="border:1px solid #000;" bgcolor="#e3ff96" class="C_'.$inc_category[$YR]["NAME"].'_P1">'.$value["PREV_YEAR1"].'</td>
                            <td align="center" style="border:1px solid #000;" bgcolor="#feccff" class="C_'.$inc_category[$YR]["NAME"].'_C">'.$value["CURRENT_YEAR"].'</td>';
                            $PREV_YEAR2     +=  $value["PREV_YEAR2"];
                            $PREV_YEAR1     +=  $value["PREV_YEAR1"];
                            $CURRENT_YEAR   +=  $value["CURRENT_YEAR"];
                            $YR++;
                        }
                        echo '<th bgcolor="#f0ad4e" style="border:1px solid #000;" class="C_PREV_YEAR2">'.$PREV_YEAR2.'</th>
                        <th bgcolor="#f0ad4e" style="border:1px solid #000;" class="C_PREV_YEAR1">'.$PREV_YEAR1.'</th>
                        <th bgcolor="#f0ad4e" style="border:1px solid #000;" class="C_CURRENT_YEAR">'.$CURRENT_YEAR.'</th></tr>';
                    }
                }
                
                foreach($REPOTED_ACC_STAT_CSET as $key => $rowdata)  {
                    $OPREV_YEAR2 = $OPREV_YEAR1 = $OCURRENT_YEAR = 0;
                    $tid = $rowdata["district_id"];
                    $inc_category = $rowdata["INCCAT"];
                    if($tid == 4) {
                        echo '<tr>
                        <td style="border:1px solid #000;">'.($key+1).'.</td>
                        <td style="border:1px solid #000;">'.$rowdata["name"].'</td>';
                        $YR = 1;
                        foreach ($inc_category as $k => $value) {
                            $csetp2 = @$csetnewarray[$value["ID"]]["P2"];
                            $csetp1 = @$csetnewarray[$value["ID"]]["P1"];
                            $csetc = @$csetnewarray[$value["ID"]]["C"];
                            $csettotprev2 = @$value["PREV_YEAR2"] - @$csetp2;
                            $csettotprev1 = @$value["PREV_YEAR1"] - @$csetp1;
                            $csettotcur = @$value["CURRENT_YEAR"] - @$csetc;

                            echo '<td align="center" style="border:1px solid #000;" bgcolor="#B9CDE"  class="C_'.$inc_category[$YR]["NAME"].'_P2">'.$csettotprev2.'</td>
                            <td align="center" style="border:1px solid #000;" bgcolor="#e3ff96" class="C_'.$inc_category[$YR]["NAME"].'_P1">'.$csettotprev1.'</td>
                            <td align="center" style="border:1px solid #000;" bgcolor="#feccff" class="C_'.$inc_category[$YR]["NAME"].'_C">'.$csettotcur.'</td>';
                            $OPREV_YEAR2     +=  $csettotprev2;
                            $OPREV_YEAR1     +=  $csettotprev1;
                            $OCURRENT_YEAR   +=  $csettotcur;
                            $YR++;
                        }
                        echo '<th bgcolor="#f0ad4e" style="border:1px solid #000;" class="C_PREV_YEAR2">'.$OPREV_YEAR2.'</th>
                        <th bgcolor="#f0ad4e" style="border:1px solid #000;" class="C_PREV_YEAR1">'.$OPREV_YEAR1.'</th>
                        <th bgcolor="#f0ad4e" style="border:1px solid #000;" class="C_CURRENT_YEAR">'.$OCURRENT_YEAR.'</th></tr>';
                    }
                }
                ?>
                <tr> 
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" colspan="2"> TOTAL </th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_FAC_P2"></th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_FAC_P1"></th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_FAC_C"></th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_LWDC_P2"></th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_LWDC_P1"></th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_LWDC_C"></th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_FATAL_P2"></th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_FATAL_P1"></th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_FATAL_C"></th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_P2"></th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_P1"></th>
                    <th style="border:1px solid #000;font-size:16px;background:#ffdece;" id="C_TOT_C"></th>
                </tr>
            </tbody>
        </table>
    <?php } ?>
    <div style="clear:both;" class="clearfix" ></div>
        <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
    </div>
   </div>
      <?php } ?>
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
    $(document).ready(function() {
        $("#ExportExcel").click(function(e){
            var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html()); 
            window.open(path);
            e.preventDefault();
        });
    });
//------P-SET-TOTAL-----//     
$(document).ready(function() {
var P_FAC_P2 = 0;       
$('.P_FAC_P2').each(function() {
    var p2val = $(this).text()
        P_FAC_P2 += parseInt(p2val);
});
$("#P_TOT_FAC_P2").text(P_FAC_P2);

var P_FAC_P1 = 0; 
$('.P_FAC_P1').each(function() {
    var p1val = $(this).text()
        P_FAC_P1 += parseInt(p1val);
});
$("#P_TOT_FAC_P1").text(P_FAC_P1);

var P_FAC_C = 0; 
$('.P_FAC_C').each(function() {
    var cval = $(this).text()
        P_FAC_C += parseInt(cval);
});
$("#P_TOT_FAC_C").text(P_FAC_C);
//------------ END-FAC--------------//
var P_LWDC_P2 = 0;       
$('.P_LWDC_P2').each(function() {
    var p2val = $(this).text()
        P_LWDC_P2 += parseInt(p2val);
});
$("#P_TOT_LWDC_P2").text(P_LWDC_P2);

var P_LWDC_P1 = 0; 
$('.P_LWDC_P1').each(function() {
    var p1val = $(this).text()
        P_LWDC_P1 += parseInt(p1val);
});
$("#P_TOT_LWDC_P1").text(P_LWDC_P1);

var P_LWDC_C = 0; 
$('.P_LWDC_C').each(function() {
    var cval = $(this).text()
       P_LWDC_C += parseInt(cval);
});
$("#P_TOT_LWDC_C").text(P_LWDC_C);
});
//------------ END-LWDC--------------
$(document).ready(function(){
var P_FATAL_P2 = 0;       
$('.P_FATAL_P2').each(function() {
    var p2val = $(this).text()
        P_FATAL_P2 += parseInt(p2val);
});
$("#P_TOT_FATAL_P2").text(P_FATAL_P2);

var P_FATAL_P1 = 0; 
$('.P_FATAL_P1').each(function() {
    var p1val = $(this).text()
        P_FATAL_P1 += parseInt(p1val);
});
$("#P_TOT_FATAL_P1").text(P_FATAL_P1);

var P_FATAL_C = 0; 
$('.P_FATAL_C').each(function() {
    var cval = $(this).text()
        P_FATAL_C += parseInt(cval);
});
$("#P_TOT_FATAL_C").text(P_FATAL_C);
//------------ END-FATAL--------------

var P_PREV_YEAR2 = 0;       
$('.P_PREV_YEAR2').each(function() {
    var p2val = $(this).text()
        P_PREV_YEAR2 += parseInt(p2val);
});
$("#P_TOT_P2").text(P_PREV_YEAR2);

var P_PREV_YEAR1 = 0;       
$('.P_PREV_YEAR1').each(function() {
    var p1val = $(this).text()
        P_PREV_YEAR1 += parseInt(p1val);
});
$("#P_TOT_P1").text(P_PREV_YEAR1);

var P_CURRENT_YEAR = 0; 
$('.P_CURRENT_YEAR').each(function() {
    var cval = $(this).text()
        P_CURRENT_YEAR += parseInt(cval);
});
$("#P_TOT_C").text(P_CURRENT_YEAR);
//------------ END-TOT-----------//
//------END P-SET-TOTAL-----// 
});

//------C-SET-TOTAL-----// 
$(document).ready(function(){
var C_FAC_P2 = 0;       
$('.C_FAC_P2').each(function() {
    var p2val = $(this).text()
        C_FAC_P2 += parseInt(p2val);
});
$("#C_TOT_FAC_P2").text(C_FAC_P2);

var C_FAC_P1 = 0; 
$('.C_FAC_P1').each(function() {
    var p1val = $(this).text()
        C_FAC_P1 += parseInt(p1val);
});
$("#C_TOT_FAC_P1").text(C_FAC_P1);

var C_FAC_C = 0; 
$('.C_FAC_C').each(function() {
    var cval = $(this).text()
        C_FAC_C += parseInt(cval);
});
$("#C_TOT_FAC_C").text(C_FAC_C);
//------------ END-FAC--------------//
var C_LWDC_P2 = 0;       
$('.C_LWDC_P2').each(function() {
    var p2val = $(this).text()
        C_LWDC_P2 += parseInt(p2val);
});
$("#C_TOT_LWDC_P2").text(C_LWDC_P2);

var C_LWDC_P1 = 0; 
$('.C_LWDC_P1').each(function() {
    var p1val = $(this).text()
        C_LWDC_P1 += parseInt(p1val);
});
$("#C_TOT_LWDC_P1").text(C_LWDC_P1);

var C_LWDC_C = 0; 
$('.C_LWDC_C').each(function() {
    var cval = $(this).text()
       C_LWDC_C += parseInt(cval);
});
$("#C_TOT_LWDC_C").text(C_LWDC_C);

//------------ END-LWDC-------------//
var C_FATAL_P2 = 0;       
$('.C_FATAL_P2').each(function() {
    var p2val = $(this).text()
        C_FATAL_P2 += parseInt(p2val);
});
$("#C_TOT_FATAL_P2").text(C_FATAL_P2);

var C_FATAL_P1 = 0; 
$('.C_FATAL_P1').each(function() {
    var p1val = $(this).text()
        C_FATAL_P1 += parseInt(p1val);
});
$("#C_TOT_FATAL_P1").text(C_FATAL_P1);

var C_FATAL_C = 0; 
$('.C_FATAL_C').each(function() {
    var cval = $(this).text()
        C_FATAL_C += parseInt(cval);
});
$("#C_TOT_FATAL_C").text(C_FATAL_C);
//------------ END-FATAL------------//
var C_PREV_YEAR2 = 0;       
$('.C_PREV_YEAR2').each(function() {
    var p2val = $(this).text()
        C_PREV_YEAR2 += parseInt(p2val);
});
$("#C_TOT_P2").text(C_PREV_YEAR2);

var C_PREV_YEAR1 = 0;       
$('.C_PREV_YEAR1').each(function() {
    var cp1val = $(this).text()
        C_PREV_YEAR1 += parseInt(cp1val);
});
$("#C_TOT_P1").text(C_PREV_YEAR1);


var C_CURRENT_YEAR = 0; 
$('.C_CURRENT_YEAR').each(function() {
    var cval = $(this).text()
        C_CURRENT_YEAR += parseInt(cval);
});
$("#C_TOT_C").text(C_CURRENT_YEAR);
//------------ END-TOT-----------//
//------END C-SET-TOTAL-----//
});



$(document).ready(function(){
var PC_FAC_P2 = 0;       
$('.PC_FAC_P2').each(function() {
    var pc2val = $(this).text()
        PC_FAC_P2 += parseInt(pc2val);
});

$("#PC_TOT_FAC_P2").text(PC_FAC_P2);

var PC_FAC_P1 = 0; 
$('.PC_FAC_P1').each(function() {
    var pc1val = $(this).text()
        PC_FAC_P1 += parseInt(pc1val);
});
$("#PC_TOT_FAC_P1").text(PC_FAC_P1);

var PC_FAC_C = 0; 
$('.PC_FAC_C').each(function() {
    var pcval = $(this).text()
        PC_FAC_C += parseInt(pcval);
});
$("#PC_TOT_FAC_C").text(PC_FAC_C);
//------------ END-FAC--------------//
var PC_LWDC_P2 = 0;       
$('.PC_LWDC_P2').each(function() {
    var pc2val = $(this).text()
        PC_LWDC_P2 += parseInt(pc2val);
});
$("#PC_TOT_LWDC_P2").text(PC_LWDC_P2);

var PC_LWDC_P1 = 0; 
$('.PC_LWDC_P1').each(function() {
    var pc1val = $(this).text()
        PC_LWDC_P1 += parseInt(pc1val);
});
$("#PC_TOT_LWDC_P1").text(PC_LWDC_P1);

var PC_LWDC_C = 0; 
$('.PC_LWDC_C').each(function() {
    var cval = $(this).text()
       PC_LWDC_C += parseInt(cval);
});
$("#PC_TOT_LWDC_C").text(PC_LWDC_C);

//------------ END-LWDC-------------//
var PC_FATAL_P2 = 0;       
$('.PC_FATAL_P2').each(function() {
    var pc2val = $(this).text()
        PC_FATAL_P2 += parseInt(pc2val);
});
$("#PC_TOT_FATAL_P2").text(PC_FATAL_P2);

var PC_FATAL_P1 = 0; 
$('.PC_FATAL_P1').each(function() {
    var pc1val = $(this).text()
        PC_FATAL_P1 += parseInt(pc1val);
});
$("#PC_TOT_FATAL_P1").text(PC_FATAL_P1);

var PC_FATAL_C = 0; 
$('.PC_FATAL_C').each(function() {
    var pcval = $(this).text()
        PC_FATAL_C += parseInt(pcval);
});
$("#PC_TOT_FATAL_C").text(PC_FATAL_C);
//------------ END-FATAL------------//
var PC_PREV_YEAR2 = 0;       
$('.PC_PREV_YEAR2').each(function() {
    var pc2val = $(this).text()
        PC_PREV_YEAR2 += parseInt(pc2val);
});
$("#PC_TOT_P2").text(PC_PREV_YEAR2);

var PC_PREV_YEAR1 = 0;       
$('.PC_PREV_YEAR1').each(function() {
    var cp1val = $(this).text()
        PC_PREV_YEAR1 += parseInt(cp1val);
});
$("#PC_TOT_P1").text(PC_PREV_YEAR1);


var PC_CURRENT_YEAR = 0; 
$('.PC_CURRENT_YEAR').each(function() {
    var pcval = $(this).text()
        PC_CURRENT_YEAR += parseInt(pcval);
});
$("#PC_TOT_C").text(PC_CURRENT_YEAR);
//------------ END-TOT-----------//
//------END C-SET-TOTAL-----//
});


$(document).ready(function(){
var OTHERS_FAC_P2 = 0;       
$('.OTHERS_FAC_P2').each(function() {
    var other2val = $(this).text()
        OTHERS_FAC_P2 += parseInt(other2val);
});

$("#OTHERS_TOT_FAC_P2").text(OTHERS_FAC_P2);

var OTHERS_FAC_P1 = 0; 
$('.OTHERS_FAC_P1').each(function() {
    var other1val = $(this).text()
        OTHERS_FAC_P1 += parseInt(other1val);
});
$("#OTHERS_TOT_FAC_P1").text(OTHERS_FAC_P1);

var OTHERS_FAC_C = 0; 
$('.OTHERS_FAC_C').each(function() {
    var otherval = $(this).text()
        OTHERS_FAC_C += parseInt(otherval);
});
$("#OTHERS_TOT_FAC_C").text(OTHERS_FAC_C);
//------------ END-FAC--------------//
var OTHERS_LWDC_P2 = 0;       
$('.OTHERS_LWDC_P2').each(function() {
    var other2val = $(this).text()
        OTHERS_LWDC_P2 += parseInt(other2val);
});
$("#OTHERS_TOT_LWDC_P2").text(OTHERS_LWDC_P2);

var OTHERS_LWDC_P1 = 0; 
$('.OTHERS_LWDC_P1').each(function() {
    var other1val = $(this).text()
        OTHERS_LWDC_P1 += parseInt(other1val);
});
$("#OTHERS_TOT_LWDC_P1").text(OTHERS_LWDC_P1);

var OTHERS_LWDC_C = 0; 
$('.OTHERS_LWDC_C').each(function() {
    var otherval = $(this).text()
       OTHERS_LWDC_C += parseInt(otherval);
});
$("#OTHERS_TOT_LWDC_C").text(OTHERS_LWDC_C);

//------------ END-LWDC-------------//
var OTHERS_FATAL_P2 = 0;       
$('.OTHERS_FATAL_P2').each(function() {
    var other2val = $(this).text()
        OTHERS_FATAL_P2 += parseInt(other2val);
});
$("#OTHERS_TOT_FATAL_P2").text(OTHERS_FATAL_P2);

var OTHERS_FATAL_P1 = 0; 
$('.OTHERS_FATAL_P1').each(function() {
    var other1val = $(this).text()
        OTHERS_FATAL_P1 += parseInt(other1val);
});
$("#OTHERS_TOT_FATAL_P1").text(OTHERS_FATAL_P1);

var OTHERS_FATAL_C = 0; 
$('.OTHERS_FATAL_C').each(function() {
    var otherval = $(this).text()
        OTHERS_FATAL_C += parseInt(otherval);
});
$("#OTHERS_TOT_FATAL_C").text(OTHERS_FATAL_C);
//------------ END-FATAL------------//
var OTHERS_PREV_YEAR2 = 0;       
$('.OTHERS_PREV_YEAR2').each(function() {
    var other2val = $(this).text()
        OTHERS_PREV_YEAR2 += parseInt(other2val);
});
$("#OTHERS_TOT_P2").text(OTHERS_PREV_YEAR2);

var OTHERS_PREV_YEAR1 = 0;       
$('.OTHERS_PREV_YEAR1').each(function() {
    var other1val = $(this).text()
        OTHERS_PREV_YEAR1 += parseInt(other1val);
});
$("#OTHERS_TOT_P1").text(OTHERS_PREV_YEAR1);


var OTHERS_CURRENT_YEAR = 0; 
$('.OTHERS_CURRENT_YEAR').each(function() {
    var otherval = $(this).text()
        OTHERS_CURRENT_YEAR += parseInt(otherval);
});
$("#OTHERS_TOT_C").text(OTHERS_CURRENT_YEAR);
//------------ END-TOT-----------//
//------END C-SET-TOTAL-----//
});

    function PrintDiv()
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=300');
        var content = document.getElementById("divToPrint").innerHTML;
        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {padding-top:7px;background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}</style></head><body>');
        mywindow.document.write(content);
        mywindow.document.write('</body></html>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/
        mywindow.print();
        mywindow.close();

        return true;
    }
 </script>