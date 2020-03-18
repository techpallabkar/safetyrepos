<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ReportController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$allFinancialYear = $beanUi->get_view_data("allFinancialYear");
$allactivity = $beanUi->get_view_data("allactivity");
$financial_year_data = $beanUi->get_view_data("financial_year");
$fetch_data = $beanUi->get_view_data("fetchArr");
$activity_name = $beanUi->get_view_data("activity_name");
$controller->get_header();
$site_root_url = dirname(url());
?>
<script type="text/javascript" src="<?php echo url('assets/js/ExportExcel/jquery.table2excel.js') ?>"></script>
<div class="container1">
    <?php echo $beanUi->get_message(); ?>
    <h1 class="heading">ACTIVITY ( ALL DATA )  </h1> 
    <div id="main-content" class="mh-content mh-home-content clearfix">
        <form action="" method="post" id="fyr" enctype="multipart/form-data">
            <label class="col-sm-2">Financial Year</label>
            <div class="col-sm-3">
                <select class="" name="fy" style="width:100%;">
                    <option value="">--Select--</option>
                    <?php
                    if (!empty($allFinancialYear)) {
                        foreach ($allFinancialYear as $key => $value) {
                            echo '<option value="' . ($value->financial_year) . '" ' . (($value->financial_year == @$_REQUEST["fy"] ) ? "selected" : "") . '>' . ($value->financial_year) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <label class="col-sm-1">Activity</label>
            <div class="col-sm-3">
                <select class="" name="activity_type_id" style="width:100%;">
                    <option value="">--Select--</option>
                    <?php
                    if (!empty($allactivity)) {
                        foreach ($allactivity as $key => $value) {
                            echo '<option value="' . ($value->id) . '" ' . (($value->id == @$_REQUEST["activity_type_id"] ) ? "selected" : "") . '>' . ($value->activity_name) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-2">
                <input name="_action" value="Search" type="hidden">
                <button type="submit" name="B1" class="btn btn-primary btn-sm">Search</button>
                <a href="incident_report.php" class="btn btn-danger btn-sm">Reset</a>
            </div>
        </form>
        <hr />
        <?php if (!empty($fetch_data)) { ?>
            <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
                Export to Excel</button>
            <div id='TBL_EXPORT'>
                <div id="parent">
                    <?php
                    foreach ($fetch_data as $key => $allvalue) {
                        $fydata = $financial_year_data[$key];
                        echo '<table border="1" class="table table-bordered table-condensed table-responsive">'
                        . '<thead>'
                        . '<tr class="bg-primary"> <th colspan="15" style="font-size:16px;">'.(($activity_name != "") ? strtoupper($activity_name) : "").' ( ALL DATA ) FY` ' . $key . '</th></tr>'
                        . '<tr bgcolor="#e3ff96" style="color:black;">'
                        . '<th>Div./Dept.</th>';
                        if (!empty($fydata)) {
                            foreach ($fydata as $rowdata) {
                                echo '<th>' . date("M y", strtotime($rowdata)) . '</th>';
                            }
                        }
                        echo '<th>Total</th>'
                        . '<th>Grand Total</th>'
                        . '</tr>'
                        . '</thead>'
                        . '<tbody style="font-weight:bold;">';
                        if (!empty($allvalue)) {
                            foreach ($allvalue as $k1 => $v1) {
                                $apr = $v1["month"][0]["thismonth_data"];
                                $may = $v1["month"][1]["thismonth_data"];
                                $jun = $v1["month"][2]["thismonth_data"];
                                $jul = $v1["month"][3]["thismonth_data"];
                                $aug = $v1["month"][4]["thismonth_data"];
                                $sep = $v1["month"][5]["thismonth_data"];
                                $oct = $v1["month"][6]["thismonth_data"];
                                $nov = $v1["month"][7]["thismonth_data"];
                                $dec = $v1["month"][8]["thismonth_data"];
                                $jan = $v1["month"][9]["thismonth_data"];
                                $feb = $v1["month"][10]["thismonth_data"];
                                $mar = $v1["month"][11]["thismonth_data"];
                                $total_sum = $apr + $may + $jun + $jul + $aug + $sep + $oct + $nov + $dec + $jan + $feb + $mar;

                                if ($k1 == 0 || $k1 == 1 || $k1 == 2) {
                                    $rowcount = 3;
                                    $cls = "a" . $key;
                                    $totcls = "total1_" . $key;
                                    $color = "#f7f7f7";
                                }
                                if ($k1 == 3 || $k1 == 4 || $k1 == 5) {
                                    $rowcount = 3;
                                    $cls = "b" . $key;
                                    $totcls = "total2_" . $key;
                                    $color = "#e1ecf4";
                                }
                                if ($k1 == 6 || $k1 == 7 || $k1 == 8 || $k1 == 9) {
                                    $rowcount = 4;
                                    $cls = "c" . $key;
                                    $totcls = "total3_" . $key;
                                    $color = "#FFF7E5";
                                }

                                echo '<tr bgcolor="' . $color . '">'
                                . '<td align="left">' . $v1["rowname"] . '</td>'
                                . '<td align="center">' . $apr . '</td>'
                                . '<td align="center">' . $may . '</td>'
                                . '<td align="center">' . $jun . '</td>'
                                . '<td align="center">' . $jul . '</td>'
                                . '<td align="center">' . $aug . '</td>'
                                . '<td align="center">' . $sep . '</td>'
                                . '<td align="center">' . $oct . '</td>'
                                . '<td align="center">' . $nov . '</td>'
                                . '<td align="center">' . $dec . '</td>'
                                . '<td align="center">' . $jan . '</td>'
                                . '<td align="center">' . $feb . '</td>'
                                . '<td align="center">' . $mar . '</td>'
                                . '<td align="center" class="' . $totcls . '">' . ($total_sum) . '</td>'
                                . '<td align="center" class="' . $cls . '" ></td>'
                                . '</tr>';
                                ?>
                                <script type="text/javascript"> // to merge column
                                    $(document).ready(function () {
                                        var asd = '<?php echo $rowcount; ?>';
                                        $('.<?php echo $cls; ?>:first').attr('rowspan', asd);
                                        $('.<?php echo $cls; ?>:gt(0)').remove();
                                        var sum = 0;
                                        $('.<?php echo $totcls; ?>').each(function () {
                                            sum += parseFloat($(this).text());  
                                        });
                                        $('.<?php echo $cls; ?>:first').css({
                                            'vertical-align': 'middle',
                                            'font-size': '16px',
                                            'color' : '#CA0000'
                                        }).text(sum);
                                    });
                                </script>  
                                <?php
                            }
                        }
                        echo '</tbody></table>';
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php $controller->get_footer(); ?>
<script type='text/javascript'>
    $(document).ready(function () {
        $("#ExportExcel").click(function (e) {
            var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html());
            window.open(path);
            e.preventDefault();
        });
    });
</script>

</body>
</html>
