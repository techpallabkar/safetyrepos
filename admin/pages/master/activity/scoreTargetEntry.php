
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
$financial_district_id = $beanUi->get_view_data("financial_district_id");

$allActivity = $beanUi->get_view_data("allActivity");

$allDistrict = $beanUi->get_view_data("allDistrict");
$allDistrict2 = $beanUi->get_view_data("allDistrict2");

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
$financial_district_for_select = $beanUi->get_view_data("financial_district_for_select");
$financialYear = $beanUi->get_view_data("financialYear");
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
                foreach ($allDistrict2 as $key => $value) {
                    echo '<option value="' . $value->id .'-'.$value->parent_id. '" ' . (($value->id .'-'.$value->parent_id == @$_REQUEST["financial_district"]) ? "selected" : "") . ' >' . $value->name . '</option>';
                    
                }
                ?>
            </select>
            </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="Create" />
 <button type="submit" name="B2" class="btn btn-primary btn-sm">Search</button>
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
                         <th  colspan="5" width="25%" style="vertical-align:middle;" bgcolor="#92D050">FINANCIAL YEAR 
                         <?php echo $financialYear; ?>
                         </th>
                    </tr>
                    <tr>
                         <th  colspan="2" width="45%" style="vertical-align:middle;" bgcolor="#92D050">P - Set</th>
                         <th bgcolor="#92D050" rowspan="2"></th>
                         <th colspan="2" width="45%" style="vertical-align:middle;" bgcolor="#92D050">C - Set</th>
                    </tr>
                </thead>
                <input type="hidden" name="financial_year_id" value="<?php echo @$financial_year_id; ?>" />
               <input type="hidden" name="financial_district_id" value="<?php echo @$financial_district_id; ?>" />
                <tbody>
                    <tr>
                        <td align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td align="center" bgcolor="#BDD7EE">Target</td>
                        <td align="center" bgcolor="#92D050" ></td>
                        <td align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td align="center" bgcolor="#BDD7EE">Target</td>
                    </tr>
                    <?php 
//                   show($alldatagen);
                    foreach ($allDistrict as $key => $value) {
                        echo '<input type="hidden" name="tree_id[]" value="'.$value->id.'" />';
                        echo ' <tr>
                        <td width="25%" align="center" style="font-weight:bold;">'.$value->name.'</td>
                        <td width="20%" align="center"><input type="text" autocomplete="off" name="pset_value[]" value="'.@$alldatagen[$value->id]->pset_value.'" /></td>
                        <td></td>
                        <td width="25%" align="center" style="font-size:16px;font-weight:bold;">'.$value->name.'</td>
                        <td width="20%" align="center"><input type="text"  autocomplete="off" name="cset_value[]"  value="'.@$alldatagen[$value->id]->cset_value.'" /></td>
                    </tr>';
                                            
                                        } ?>
                   
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="16" align="right">
                            <input type="hidden" name="_action" value="submitData" />
                            <button type="submit" name="B2" class="btn btn-primary btn-sm">Submit</button></td>
                    </tr>
                </tfoot>

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
        var fydid = "<?php echo $financial_district_for_select; ?>"
        if (fyid) {
          
            $("#financial_yearselect").val(fyid);
            $("#financial_district").val(fydid);
            $("form#fyr").append('<input type="hidden" value="fromsubmit" name="fromsubmit">');
            $("form#fyr").submit();

        }


        $('.newcal').keyup(function () {
            var sum = 0;
            var cls = $(this).parents('td').parents('tr').attr("class");

            $(this).parents('td').parents('tr').children('td').children('.newcal').each(function () {
                var calpart = $(this).val();
                sum += Number(calpart);
            });

            $(this).parents('tr').children('td').children('.genTotal').val(sum);
            var total = 0;
            $(this).parents('td').parents('tr').parents('tl').css("background", "red");
            //subtotal
            $("." + cls).children('td').children('.genTotal').each(function () {
                var calpartt = $(this).val();
                total += Number(calpartt);
            });
            $("." + cls).children('td').children('.grand_total').val(total);
        });

    });

</script>