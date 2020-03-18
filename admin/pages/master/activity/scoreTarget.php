
<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ReportController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$allFinancialYear = $beanUi->get_view_data("allFinancialYear");
$getAllMonthofFinancialYear = $beanUi->get_view_data("getAllMonthofFinancialYear");
$financial_year_id = $beanUi->get_view_data("financial_year_id");

$allActivity = $beanUi->get_view_data("allActivity");
$allfilteredDistrict = $beanUi->get_view_data("allfilteredDistrict");

$allDistrict = $beanUi->get_view_data("allDistrict");
$workshopReport = $beanUi->get_view_data("workshop");
$communicationReport = $beanUi->get_view_data("commMeeting");
$safetydaysReport = $beanUi->get_view_data("safetyDays");
$trainingReport = $beanUi->get_view_data("training");
$siteauditReport = $beanUi->get_view_data("siteAudit");
$incidentReport = $beanUi->get_view_data("incident");
$ppeauditReport = $beanUi->get_view_data("ppeAudit");
$safetyobsReport = $beanUi->get_view_data("safeyObs");
$safeyObsLIneFunc = $beanUi->get_view_data("safeyObsLIneFunc");
$alldatagen = $beanUi->get_view_data("alldatagen");
$alldatadist = $beanUi->get_view_data("alldatadist");
$financial_year_for_select = $beanUi->get_view_data("financial_year_for_select");
$financial_year = ($beanUi->get_view_data("financial_year") ? $beanUi->get_view_data("financial_year") : "" );
$type_images = array("image/jpg", "image/jpeg", "image/png");
$type_videos = array("video/mp4", "video/quicktime");
$pdf = array("application/pdf");
$controller->get_header();
$site_root_url = dirname(url());
$arr = array("#F2F2F2", "#DDD9C3", "#C6D9F1", "#DCE6F2", "#F2DCDB", "#E6E0EC", "#FDEADA", "#FCD5B5");
?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
</script>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">SITE AUDIT SCORE TARGET</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr />

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
        </div>
        <label class="col-sm-2">Select District</label>
        <div class="col-sm-4">
        <select name="financial_district" id="financial_district" class="form-control">
                <option value="">select</option>
                <?php
                foreach ($allfilteredDistrict as $key => $value) {
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
    <hr /> <?php //echo "<pre>";print_r($alldatagen);  ?>

    <form action="" method="post" enctype="multipart/form-data">
        <?php if (!empty($getAllMonthofFinancialYear)) { ?>
        <table class="table table-bordered table-condensed table-responsive" style="font-size:16px;" >
                <thead>
                    <tr>
                        <th  colspan="5" width="25%" style="vertical-align:middle;" bgcolor="#92D050">SITE AUDIT SCORE TARGET</th>
                       
                      
                    </tr>
                    <tr>
                         <th  colspan="5" width="25%" style="vertical-align:middle;" bgcolor="#92D050">FINANCIAL YEAR <?php echo $financial_year; ?></th>
                    </tr>
                    <tr>
                         <th  colspan="2" width="45%" style="vertical-align:middle;" bgcolor="#92D050">P - Set</th>
                         <th bgcolor="#92D050" rowspan="2"></th>
                         <th colspan="2" width="45%" style="vertical-align:middle;" bgcolor="#92D050">C - Set</th>
                    </tr>
                </thead>
                <input type="hidden" name="financial_year_id" value="<?php echo @$financial_year_id; ?>" />
                <tbody>
                    <tr>
                        <td align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td align="center" bgcolor="#BDD7EE">Target</td>
                        <td align="center" bgcolor="#92D050" ></td>
                        <td align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td align="center" bgcolor="#BDD7EE">Target</td>
                    </tr>
                    <?php
                    
                    foreach ($allDistrict as $key => $value) {
                        echo '<input type="hidden" name="tree_id[]" value="'.$value->id.'" />';
                        echo ' <tr>
                        <td width="25%" align="center" style="font-weight:bold;">'.$value->name.'</td>
                        <td width="20%" align="center">'.$alldatagen[$value->id]["pset_value"].'</td>
                        <td></td>                                       
                        <td width="25%" align="center" style="font-size:16px;font-weight:bold;">'.$value->name.'</td>
                        <td width="20%" align="center">'.$alldatagen[$value->id]["cset_value"].'</td>
                    </tr>';
                                            
                                        } ?>
                   
                </tbody>
               

            </table>

<?php } ?>
    </form>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>
<script type="text/javascript">
    
    $(document).ready(function () {
  
    
//        $("#financial_yearselect").change(function () {
//            this.form.submit();
//        });
        var fyid = "<?php echo $financial_year_for_select; ?>"
        if (fyid) {
            $("#financial_yearselect").val(fyid);
            $("form#fyr").append('<input type="hidden" value="fromsubmit" name="fromsubmit">');
            $("form#fyr").submit();

        }


       

    });

</script>