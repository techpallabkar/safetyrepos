<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$month_year = $beanUi->get_view_data("month_year");
$month_of_year_back = $beanUi->get_view_data("month_of_year_back");
$getAllPSETData = $beanUi->get_view_data("getAllPSETData");
$getAllCSETData = $beanUi->get_view_data("getAllCSETData");
$getDate = $beanUi->get_view_data("getDate");
$month_year_formatted = date("M ' Y", strtotime("01-" . $month_year));
$month_back = date("M ' Y", strtotime($month_of_year_back . '-01'));
$previous_financial_year = @$getDate["PREV_YTM"];
$exp_prev_fy_year = explode(",", $previous_financial_year);
@$prevfyyear = date("Y", strtotime($exp_prev_fy_year[0] . "-01")) . '-' . date("y", strtotime($exp_prev_fy_year[1] . "-01"));
@$present_financial_year = $getDate["CURR_YTM"];
@$exp_pres_fy_year = explode(",", $present_financial_year);
@$presfyyear = date("Y", strtotime($exp_pres_fy_year[0] . "-01")) . '-' . date("y", strtotime($exp_pres_fy_year[1] . "-01"));
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

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">15.Generation & Distribution Site Audit Report P-Set And C-Set wise (Spot light)</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr/>
    <form action="" method="post" id="mdform" enctype="multipart/form-data" style="<?php echo ( @$mode == "view" ) ? 'display:none' : 'display:block'; ?>" >
        <div class="holder">
            <label for="job_stop_req_raisedx" style="width:15%;padding-top:5px;">Select Month-Year :</label>
            <input type="text" name="month_year" id="month_year_from" class="month_year_picker" value ="<?php echo @$month_year; ?>" style="width:250px;"  required/>
            <input type="hidden" name="_action" value="Create" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="spotLightScoresStatistics.php" class="btn btn-danger btn-sm">Reset</a>                 
        </div>
        <hr class="no-margin"/>
    </form>
    <!--REPORTED ACCIDENT STATISTICS OF PERMANENT EMPLOYEES--> 
    <?php if (!empty($getAllPSETData)) { ?>
        <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
            Export to Excel</button>
        <div class="print1" style="float: right;">
            <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
    <?php } ?>
    <div id='TBL_EXPORT'>
        <div class="print-friendly" id="divToPrint">
            <?php if (!empty($getAllPSETData)) { ?>
                <table border="1" class="table table-bordered table-condensed table-responsive totaldata" style="font-size:13px;font-weight:bold;" >
                    <thead class="bg-primary">
                        <tr><th colspan="10" >JOB SITE AUDIT OF PERMANENT EMPLOYEES </th></tr>
                        <tr>
                            <th rowspan="2" width="5%">SL. NO</th>
                            <th rowspan="2" width="15%"> DEPARTMENT </th>
                            <!--<th rowspan="2" width="10%"> NUMBER OF SET / UNIT </th>-->

                            <th  colspan="2" width="10%"> <?php echo strtoupper($month_year_formatted); ?></th>
                            <th  colspan="2" width="10%"> <?php echo strtoupper($month_back); ?></th>
                            <th  colspan="2" width="12%"> YTM ' <?php echo strtoupper($presfyyear); ?></th>
                            <th  colspan="2" width="12%"> YTM ' <?php echo strtoupper($prevfyyear); ?></th>

                        </tr>
                        <tr>
                            <th width="10%">AUDIT COUNT</th>
                            <th width="10%">% AVG</th>
                            <th width="10%">AUDIT COUNT</th>
                            <th width="10%">% AVG</th>
                            <th width="10%">AUDIT COUNT</th>
                            <th width="10%">% AVG</th>
                            <th width="10%">AUDIT COUNT</th>
                            <th width="10%">% AVG</th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php
                        $pset_tot_val2 = $pset_tot_val4 = 0;
                        $pset_total0 = $cset_total1 = $pset_total2 = $pset_total3 = $pset_total4 = $pset_total5 = $pset_total6 = $pset_total1 = 0;
                        $pslno=0;
//                        show($getAllPSETData);
                        foreach ($getAllPSETData as $key => $rowdata) {
                            $pslno++;
                            if( $key < 3) {
                            $color = "#d8ed74";
                        } else if($key > 1 && $key < 15) {
                            $color= "#eaf2f9";
                        } else if($key > 14 && $key < 19) {
                            $color= "#dddddd";
                        } else {
                            $color= "#E1EED9";
                        }
                        echo  '<tr>'
                            . '<td bgcolor="'.$color.'" align="center" style="border:1px solid #000;">' . ($pslno) . '.</td>';
                        if($rowdata["name"] == 'HT'){    
                            echo '<td bgcolor="'.$color.'" style="border:1px solid #000;" bgcolor="#B9CDE5" >' . ($rowdata["name"]) .' (*) ' . '</td>';
                        } else {
                            echo '<td bgcolor="'.$color.'" style="border:1px solid #000;" bgcolor="#B9CDE5" >' . ($rowdata["name"]) . '</td>';
                        }    
                         echo '<!--<td align="center" bgcolor="#FCD5B5"  style="border:1px solid #000;">' . $val0 = ($rowdata["no_of_unit_set"]) . '</td>-->'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $val3 = ($rowdata["CURRENT"]["totaldiv"]) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $val4 = round(($rowdata["CURRENT"]["score"]), 2) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $val1 = ($rowdata["PREV"]["totaldiv"]) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $val2 = round(($rowdata["PREV"]["score"]), 2) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'"  style="border:1px solid #000;">' . $val6 = ($rowdata["current_ytm"]["totaldiv"]) . '</td>'  
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $val4 = round(($rowdata["current_ytm"]["score"]), 2) . '</td>'  
                            . '<td align="center" bgcolor="'.$color.'"  style="border:1px solid #000;">' . $val5 = ($rowdata["prev_ytm"]["totaldiv"]) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $val4 = round(($rowdata["prev_ytm"]["score"]), 2) . '</td>';

                            echo '</tr>';

                            $pset_total0 += $val0;
                            $pset_total1 += $val1;
                            $pset_tot_val2 += ($val1 * $val2);

                            $pset_total3 += $val3;
                            $pset_tot_val4 += ($val3 * $val4);

                            $pset_total5 += $val5;
                            $pset_total6 += $val6;
                        }

                        @$pset_total2 = round(($pset_tot_val2 / $pset_total1), 2);
                        @$pset_total4 = round(($pset_tot_val4 / $pset_total3), 2);
                        ?>
                        <tr  style="font-size: 16px;background:#ffdece;"> 
                            <th style="border-color:#000;" colspan="2"> TOTAL </th>
                            <!--<th style="border-color:#000;"><?php echo $pset_total0; ?></th>-->
                            <th style="border-color:#000;"><?php echo $pset_total3; ?></th>
                            <th style="border-color:#000;"><?php //echo $pset_total4; ?></th>
                            <th style="border-color:#000;"><?php echo $pset_total1; ?></th>
                            <th style="border-color:#000;"><?php //echo $pset_total2; ?></th>
                            <th style="border-color:#000;"><?php echo $pset_total6; ?></th>
                            <th style="border-color:#000;"><?php //echo $pset_total6; ?></th>
                            <th style="border-color:#000;"><?php echo $pset_total5; ?></th>
                            <th style="border-color:#000;"><?php //echo $pset_total5; ?></th>

                        </tr>
                    </tbody>
                </table>
            <?php } ?>


            <!--REPORTED ACCIDENT STATISTICS OF CONTRACTOR EMPLOYEES--> 

            <?php if (!empty($getAllCSETData)) { ?>
                <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;font-weight:bold;" >
                    <thead class="bg-primary">
                        <tr><th colspan="10">JOB SITE AUDIT OF CONTRACTOR PERSONNEL</th></tr>
                        <tr>
                            <th rowspan="2" width="5%">SL.NO</th>
                            <th rowspan="2" width="15%"> DEPARTMENT </th>
<!--                            <th rowspan="2" width="10%"> NO OF SET / UNIT </th>-->
                            <th  colspan="2" width="10%"> <?php echo strtoupper($month_year_formatted); ?></th>
                            <th  colspan="2" width="10%"> <?php echo strtoupper($month_back); ?></th>
                            <th  colspan="2" width="12%"> YTM ' <?php echo $presfyyear; ?></th>
                            <th  colspan="2" width="12%"> YTM ' <?php echo $prevfyyear; ?></th>

                        </tr>
                        <tr>
                            <th width="10%">AUDIT COUNT</th>
                            <th width="10%">% AVG</th>
                            <th width="10%">AUDIT COUNT</th>
                            <th width="10%">% AVG</th>
                            <th width="10%">AUDIT COUNT</th>
                            <th width="10%">% AVG</th>
                            <th width="10%">AUDIT COUNT</th>
                            <th width="10%">% AVG</th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php
                        $cset_tot_val2 = $cset_tot_val4 = 0;
                        $cset_total0 = $cset_total1 = $cset_total2 = $cset_total3 = $cset_total4 = $cset_total5 = $cset_total6 = 0;
                        $cslno=0;
                        foreach ($getAllCSETData as $key => $rowdata) {
                            $cslno++;
                             if( $key < 3) {
                            $color = "#d8ed74";
                        } else if($key > 1 && $key < 15) {
                            $color= "#eaf2f9";
                        } else if($key > 14 && $key < 19) {
                            $color= "#dddddd";
                        } else {
                            $color= "#E1EED9";
                        }
                        echo  '<tr>'
                            . '<td bgcolor="'.$color.'" align="center" style="border:1px solid #000;">' . ($cslno) . '.</td>';
                        if($rowdata["name"] == 'HT'){    
                            echo '<td bgcolor="'.$color.'" style="border:1px solid #000;">' . ($rowdata["name"]) .' (*) '. '</td>';
                        } else {
                            echo '<td bgcolor="'.$color.'" style="border:1px solid #000;">' . ($rowdata["name"]) . '</td>';
                        }
                        echo '<!--<td align="center" bgcolor="#FCD5B5" style="border:1px solid #000;">' . $vals0 = ($rowdata["no_of_unit_set"]) . '</td>-->'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $vals3 = ($rowdata["CURRENT"]["totaldiv"]) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $vals4 = round(($rowdata["CURRENT"]["score"]), 2) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $vals1 = ($rowdata["PREV"]["totaldiv"]) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $vals2 = round(($rowdata["PREV"]["score"]), 2) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $vals6 = ($rowdata["current_ytm"]["totaldiv"]) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $vals4 = round(($rowdata["current_ytm"]["score"]), 2) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $vals5 = ($rowdata["prev_ytm"]["totaldiv"]) . '</td>'
                            . '<td align="center" bgcolor="'.$color.'" style="border:1px solid #000;">' . $vals4 = round(($rowdata["prev_ytm"]["score"]), 2) . '</td>';
                            echo '</tr>';

                            $cset_total0 += $vals0;
                            $cset_total1 += $vals1;
                            $cset_tot_val2 += ($vals1 * $vals2);

                            $cset_total3 += $vals3;
                            $cset_tot_val4 += ($vals3 * $vals4);

                            $cset_total5 += $vals5;
                            $cset_total6 += $vals6;
                        }
                        @$cset_total2 = round(($cset_tot_val2 / $cset_total1), 2);
                        @$cset_total4 = round(($cset_tot_val4 / $cset_total3), 2);
                        ?>
                        <tr  style="font-size: 16px;background:#FAE3D4;"> 
                            <th style="border-color:#000;" colspan="2"> TOTAL </th>
                            <!--<th style="border-color:#000;"><?php echo $cset_total0; ?></th>-->
                            <th style="border-color:#000;"><?php echo $cset_total3; ?></th>
                            <th style="border-color:#000;"><?php //echo $cset_total4; ?></th>
                            <th style="border-color:#000;"><?php echo $cset_total1; ?></th>
                            <th style="border-color:#000;"><?php //echo $cset_total2; ?></th>
                            <th style="border-color:#000;"><?php echo $cset_total6; ?></th>
                            <th style="border-color:#000;"><?php //echo $cset_total6; ?></th>
                            <th style="border-color:#000;"><?php echo $cset_total5; ?></th>
                            <th style="border-color:#000;"><?php //echo $cset_total5; ?></th>
                            
                        </tr>
                    </tbody>
                </table>
            <div style="clear:both;" class="clearfix" ></div>
            <!--<div style="float: bottom"> <?php //echo CURRENT_DATE_TIME; ?> </div>-->
            <?php } ?>
            <table class="note">
                <tr>
                    <td colspan="10">
                        *Job Site Audit of Mains Department Conducted by HT Section.
                    </td>
                </tr>
                <tr>
                    <td colspan="10">
                        <?php echo CURRENT_DATE_TIME; ?>
                    </td>
                </tr>
            </table>             
        </div>
    </div>
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

            function get_total_value(type, incid) {
                var current_month = 0;
                var prev_ytm = 0;
                var current_ytm = 0;
                $('.' + type + 'CMNTH_' + incid).each(function () {
                    var c1val = $(this).text()
                    current_month += parseInt(c1val);
                });
                $('.' + type + 'PREVYTM_' + incid).each(function () {
                    var c1val = $(this).text()
                    prev_ytm += parseInt(c1val);
                });
                $('.' + type + 'CURYTM_' + incid).each(function () {
                    var c1val = $(this).text()
                    current_ytm += parseInt(c1val);
                });


                $("#" + type + "CMNTH_" + incid).text(current_month);
                $("#" + type + "PREVYTM_" + incid).text(prev_ytm);
                $("#" + type + "CURYTM_" + incid).text(current_ytm);
            }
            $(document).ready(function () {

<?php
if (!empty($incident_category)) {
    foreach ($incident_category as $key => $value) {
        ?>
                        get_total_value('PSET',<?php echo $value->id; ?>);
                        get_total_value('CSET',<?php echo $value->id; ?>);
                        get_total_value('PCSET',<?php echo $value->id; ?>);
                        get_total_value('OTHERS',<?php echo $value->id; ?>);
    <?php }
}
?>


            });
</script>
<script type="text/javascript">
    function PrintDiv()
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=300');
        var content = document.getElementById("divToPrint").innerHTML;
        mywindow.document.write('<html><head><title>SPOT LIGHT SCORES STATISTICS</title>');
        mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {padding-top:15px;background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;} .note td{border:none !important;}</style></head><body>');
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
