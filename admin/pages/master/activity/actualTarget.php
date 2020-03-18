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
            </style>
            <script>
                $(document).ready(function () {
                    $("#fixTable").tableHeadFixer({"left": 1});
                });
            </script>


            <form action="" method="post" id="fyr" enctype="multipart/form-data">

                <label class="col-sm-2">Financial Year</label>
                <div class="col-sm-4">

                    <select name="financial_year" id="financial_yearselect" class="form-control">
                        <option value="">select</option>
                        <?php
                        foreach ($allFinancialYear as $key => $value) {
                            echo '<option value="' . $value->id . '" ' . (($value->id == @$_REQUEST["financial_year"]) ? "selected" : "") . ' >' . $value->financial_year . '</option>';
                        }
                        ?>
                    </select>
                    <span id="financial_year_error"></span>
                </div>
                <label class="col-sm-2">Select District</label>
                <div class="col-sm-4">
                    <select name="financial_district" id="financial_district" class="form-control">
                        <option value="">select</option>
                        <?php
                        foreach ($allDistrict as $key => $value) {
                            echo '<option value="' . $value->id . '" ' . (($value->id == @$_REQUEST["financial_district"]) ? "selected" : "") . ' >' . $value->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <input type="hidden" name="_action" value="Create" />
                    <button type="submit" name="B1" class="btn btn-primary btn-sm">Search</button>
                </div>
            </form>


            <div id="parent">

                <table id="" class="table table-bordered table-condensed table-responsive">
                    <?php if (!empty($allActivity)) { ?>
                        <thead>
                            <tr><td colspan="16" align="right"><div style="color:#CA0000;">
                                        <i class="fa fa-hand-o-right"></i>
                                        Note :  Based on approved data.
                                    </div></td></tr>
                            <tr>
                                <th  width="25%" style="vertical-align:middle;" bgcolor="#B3A2C7">Actual</th>
                                    <?php
                                    foreach (getAllMonthYear($financial_year) as $rowdata) {
                                        echo '<th bgcolor="#D99694">' . date("M y", strtotime($rowdata)) . '</th>';
                                    }
                                    ?>
                                <th bgcolor="#D99694">TOTAL <?php
                                echo $financial_year;
                                    ?></th>  


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
    <hr />
</div>
<?php $controller->get_footer(); ?>


<script>
    $(document).ready(function () {
        $("#fyr").submit(function () {
            var financial_year = jQuery.trim(jQuery("#financial_yearselect").val());
            if (financial_year == undefined || financial_year == "") {
                jQuery("#financial_yearselect").focus();
                jQuery("#financial_year_error").html("<div class=\"errors\">Please Select Finalcial Year.</div>");
                return false;
            }

        });
    });
</script>

</body>
</html>
