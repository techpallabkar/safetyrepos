<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$majorItem = $beanUi->get_view_data("majorItem");
$arr_date_send = $beanUi->get_view_data("arr_date_send");
$controller->get_header();
$site_root_url = dirname(url());
?> 
<style>
    .none {display: none;}
</style>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
</script>
<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">16. DISTRIBUTION DATA SHEET FOR MONTHLY REPORT</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr/>

    <form action="" method="post" id="mdform" enctype="multipart/form-data" style="<?php echo ( $mode == "view" ) ? 'display:none' : 'display:block'; ?>" >
        <div class="holder">
            <label for="job_stop_req_raisedx" style="width:15%;padding-top:5px;">Select Month-Year :</label>
            <input type="text" name="month_year" id="month_year_from" class="month_year_picker" value ="<?php echo @$arr_date_send['curr_month']; ?>" style="width:250px;"  required/>
            <input type="hidden" name="_action" value="Create" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="dist_data_sheet_for_momthly_report.php" class="btn btn-danger btn-sm">Reset</a>                 
        </div>
        <hr class="no-margin"/>
    </form>
    <?php if (!empty($majorItem)) { ?>
        <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel</button>
        <div class="print1" style="float: right;">
            <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
        <div id='TBL_EXPORT'>
            <div class="print-friendly" id="divToPrint">
                <div class="none">
                    <u>DISTRIBUTION DATA SHEET FOR MONTHLY REPORT</u>
                    <br>
                    <br>
                </div>
                <table class="table table-bordered table-bordered table-condensed" style="font-weight: bold;">
                    <thead class="bg-primary">
                        <tr>
                            <th width="5%" rowspan="2" style="vertical-align: middle;">SL.NO</th>
                            <th width="40%" rowspan="2" style="vertical-align: middle;">ITEM</th>
                            <th colspan="4" width="26.5%">FY' <?php
                                $date = explode("-", $arr_date_send['curr_year']);
                                echo $arr_date_send['curr_year'];
                                ?></th>
                            <th colspan="4" width="26.5%">FY' <?php
                                $date2 = explode(",", $arr_date_send['last_year']);
                                echo date("Y", strtotime($date2[0])) . "-" . date("Y", strtotime($date2[1]));
                                ?></th>

                        </tr>
                        <tr>
                            <th width="8%" style="vertical-align: middle;">ANNUAL TARGET</th>
                            <th width="8%" style="vertical-align: middle;"><?php echo date("M ' Y", strtotime("01-" . $arr_date_send['curr_month'])); ?></th>
                            <th width="8%" style="vertical-align: middle;">YTM THIS YEAR</th>
                            <th width="8%" style="vertical-align: middle;">% COMPLETED</th>
                            <th width="8%" style="vertical-align: middle;">ANNUAL TARGET</th>
                            <th width="8%" style="vertical-align: middle;"><?php echo date("M ' Y", strtotime($arr_date_send['last_month'] . "-01")); ?></th>
                            <th width="8%" style="vertical-align: middle;">YTM LAST YR</th>
                            <th width="8%" style="vertical-align: middle;">% COMPLETED</th>

                        </tr>
                    </thead>
                    <?php
                    foreach ($majorItem as $key => $value) {//show($majorItem);
                        $ytm_this_year = $completed_lastFY = 0;
                        $annual_target = $value->annual_target;
                        $annual_target_lastFY = $value->annual_target_lastFY;
                        if ($annual_target != 0) {
                            $ytm_this_year = round((($value->ytm_this_year / $value->annual_target) * 100), 2);
                        }
                        if ($annual_target_lastFY != 0) {
                            $completed_lastFY = round((($value->completed_lastFY / $value->annual_target_lastFY) * 100), 2);
                        }
                        echo '<tr>
            <td align="center" style="border-color:#000;">' . ($key + 1) . '.</td>
            <td style="border-color:#000;" >' . $value->name . '</td>
            <td align="center" style="border-color:#000;" bgcolor="#FCD5B5">' . ($value->annual_target) . '</td>
            <td align="center" style="border-color:#000;" bgcolor="#e3ff96">' . ($value->this_month) . '</td>
            <td align="center" style="border-color:#000;font-size:15px;" bgcolor="#B9CDE5">' . ($value->ytm_this_year) . '</td>
            <td align="center" style="border-color:#000;" bgcolor="#DDD9C3">' . ($ytm_this_year) . '</td>
                
            <td align="center" style="border-color:#000;" bgcolor="#FCD5B5">' . ($value->annual_target_lastFY) . '</td>
            <td align="center" style="border-color:#000;font-size:15px;" bgcolor="#e3ff96">' . ($value->this_month_lastFY) . '</td>
            <td align="center" style="border-color:#000;" bgcolor="#B9CDE5">' . ($value->ytm_this_year_lastFY) . '</td>
            <td align="center" style="border-color:#000;" bgcolor="#DDD9C3">' . ($completed_lastFY) . '</td>
            </tr>';
                    }
                    ?>

                </table>
                <div class="print1" style="float: left; color:#CA0000;">
                    NOTE : Based on Distribution Data</td>
                </div>
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
<script type="text/javascript">
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