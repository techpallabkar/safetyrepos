<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ReportController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$montharray = $beanUi->get_view_data("montharray");
$allActivity = $beanUi->get_view_data("allActivity");
$allUsers = $beanUi->get_view_data("allUsers");
$workshopReport = $beanUi->get_view_data("workshop");
$communicationReport = $beanUi->get_view_data("commMeeting");
$safetydaysReport = $beanUi->get_view_data("safetyDays");
$trainingReport = $beanUi->get_view_data("training");
$siteauditReport = $beanUi->get_view_data("siteAudit");
$incidentReport = $beanUi->get_view_data("incident");
$ppeauditReport = $beanUi->get_view_data("ppeAudit");
$safetyobsReport = $beanUi->get_view_data("safeyObs");
$safeyObsLIneFunc = $beanUi->get_view_data("safeyObsLIneFunc");
$financial_year = $beanUi->get_view_data("financial_year");
$allFinancialYear = $beanUi->get_view_data("allFinancialYear");
$FinancialYearFromTo = $beanUi->get_view_data("FinancialYearFromTo");
$allDistrict = $beanUi->get_view_data("allDistrict");
$type_images = array("image/jpg", "image/jpeg", "image/png");
$type_videos = array("video/mp4", "video/quicktime");
$pdf = array("application/pdf");

$controller->get_header();
$site_root_url = dirname(url());
$arryform[4] = $workshopReport;
$arryform[5] = $communicationReport;
$arryform[3] = $trainingReport;
$arryform[7] = $safetydaysReport;
$arryform[1] = $siteauditReport;
$arryform[0] = $incidentReport;
$arryform[2] = $ppeauditReport;
$arryform[6] = $safetyobsReport;
?>
<script type="text/javascript" src="<?php echo url('assets/js/ExportExcel/jquery.table2excel.js') ?>"></script>
<div class="container1">
   
    <?php echo $beanUi->get_message(); ?>
    <h1 class="heading"></h1> 
    <div class="wrapper1">
        <div class="div1">
        </div>
    </div>
    <div class="wrapper2">
        <div class="div2">
            <script src="<?php echo url("assets/js/tableHeadFixer.js"); ?>"></script>
            <style>
                #parent {
                    height: 500px;
                }
                #fixTable {
                    width: 100% !important;
                    font-size: 11px;
                }
                .news { border-bottom:  1px solid #d0d0d0 !important;}           
                    .n-border tbody tr td {
                        border-top: none;
                        vertical-align: top;
                    }
            </style>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#fixTable").tableHeadFixer({"left": 1,"right":1});
                });
            </script>
            <form action="" method="post" id="fyr" enctype="multipart/form-data">
                <table class="table n-border">
                    <tbody>
                        <tr>
                            <td style="width: 17%;">Select Month-Year From :</td>
                            <td style="width: 15%;">
                                <?php  $YM = explode(",",$FinancialYearFromTo);
                                $finalcial_M_Y_from = date("m-Y",strtotime($YM[0].'-01'));
                                $finalcial_M_Y_to = date("m-Y",strtotime($YM[1].'-01'));
                                
                                ?>
                                <input type="text" name="month_year_from" id="month_year_from" class="month_year_picker form-control" value = "<?php echo $finalcial_M_Y_from; ?>" style="width: 100%;" >
                                <span id="financial_year_error"></span>
                            </td>
                            <td style="width: 6%;">To:</td>
                            <td style="width: 15%;">
                                <input type="text" name="month_year_to" id="month_year_to" class="month_year_picker form-control" value = "<?php echo $finalcial_M_Y_to; ?>" style="width: 100%;" >
                            </td>
                            <td style="width: 15%;">Select District :</td>
                            <td style="width: 21%;">
                                <select name="financial_district" id="financial_district" class="form-control">
                                    <option value="">select</option>
                                    <?php
                                    foreach ($allDistrict as $key => $value) {
                                        echo '<option value="' . $value->groupid . '" ' . (($value->groupid == @$_REQUEST["financial_district"]) ? "selected" : "") . ' >' . $value->name . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td style="width: 11%;">
                                <input type="hidden" name="_action" value="Create" />
                                <button type="submit" name="B1" class="btn btn-primary btn-sm">Search</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <?php if (!empty($allActivity)) { ?>
             <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
 Export to Excel</button>
            <?php } ?>
            <div id="TBL_EXPORT">
                <div id="parent" style="overflow-x:scroll;width:100%;height:auto;">
                    <table id="fixTable" border="1" class="table table-bordered table-condensed table-responsive">
                        <?php if (!empty($allActivity)) { ?>
                            <thead>
                                <tr style="display:none;">
                                    <td colspan="<?php echo count($financial_year); ?>" align="left" style="border:none;font-size:14px;font-weight:bold;">
                                        Actual Activity<br>
                                        Month-Year From : <?php echo date("F,Y",strtotime(@'01-'.$_REQUEST["month_year_from"])); ?>  to: <?php echo date("F,Y",strtotime(@'01-'.$_REQUEST["month_year_to"])); ?>
                                    </td>
                                </tr>
                                <tr id="asd">
                                    <td colspan="<?php echo count($financial_year); ?>" align="left" style="border:none;">
                                        <div style="color:#CA0000;">
                                            <i class="fa fa-hand-o-right"></i>
                                            Note :  Based on approved data.
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th  width="25%" style="vertical-align:middle;" bgcolor="#B3A2C7">Actual</th>
                                        <?php
                                        foreach ($financial_year as $rowdata) {
                                            echo '<th bgcolor="#D99694">' . date("M y", strtotime($rowdata)) . '</th>';
                                        }
                                        ?>
                                    <th bgcolor="#D99694">TOTAL [ <?php echo str_replace(","," To ", $FinancialYearFromTo); ?> ] </th>
                                </tr>
                                <?php
                                $arr = array("#F2F2F2", "#DDD9C3", "#C6D9F1", "#DCE6F2", "#F2DCDB", "#E6E0EC", "#FDEADA", "#FCD5B5");
                                ?>
                            </thead>
                            <tbody>
                                <?php
                                $total = $total2 = $total3 = 0;
                                foreach ($allActivity as $key => $rowdata) {
                                    $totalSum = 0;
                                    if ($rowdata->id != 9 && $rowdata->id != 10) {
                                        echo '<tr bgcolor="' . $arr[$key] . '">'
                                        . '<td><b>' . $rowdata->activity_name . '</b></td>';
                                        foreach (@$arryform[$key][$rowdata->id] as $wrk => $wrkvalue) {
                                            echo '<td>' . $wrkvalue[@$_REQUEST["financial_district"]] . '</td>';
                                            $total += $wrkvalue[@$_REQUEST["financial_district"]];
                                        }
                                        echo '<td>' . $total . '</td>'
                                        . '</tr>';
                                        $total = $total2 = $total3 = 0;
                                    }
                                }
                                ?>
                            </tbody>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <hr />
</div>
<?php $controller->get_footer(); ?>
<script>
    $(document).ready(function () {
        $("#fyr").submit(function () {
            var month_year_from = jQuery.trim(jQuery("#month_year_from").val());
            var month_year_to = jQuery.trim(jQuery("#month_year_to").val());
            var financial_district = jQuery.trim(jQuery("#financial_district").val());
            if (month_year_from == undefined || month_year_from == "") {
                jQuery("#month_year_from").focus();
                return false;
            }
            if (month_year_to == undefined || month_year_to == "") {
                jQuery("#month_year_to").focus();
                return false;
            }
             if (financial_district == undefined || financial_district == "") {
                jQuery("#financial_district").focus();
                return false;
            }

        });
    });
</script>

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

</body>
</html>
