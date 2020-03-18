
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
$major_activities_typeone = $beanUi->get_view_data("major_activities_typeone");
$major_activities_typetwo = $beanUi->get_view_data("major_activities_typetwo");
$major_activities_typethree = $beanUi->get_view_data("major_activities_typethree");
$financialYear = $beanUi->get_view_data("financialYear");
//show($all_major_activities);
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

$financial_year = ($beanUi->get_view_data("financial_year") ? $beanUi->get_view_data("financial_year") : "" );
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

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">TARGET ENTRY</h1> 
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


        <div class="col-sm-4">
            <input type="hidden" name="_action" value="Create" />
            <button type="submit" name="B2" class="btn btn-primary btn-sm">Go</button>
            <a href="" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr /> 
    <form action="" method="post" enctype="multipart/form-data">
        <?php if (!empty($major_activities_typeone)) { ?>        
                <input type="hidden" name="financial_year_id" value="<?php echo @$financial_year_id; ?>" />
            <table class="table table-bordered table-condensed table-responsive" style="font-size:13px;" >
                <thead>
                    <tr class="bg-primary">
                        <th  colspan="8" width="25%" style="vertical-align:middle;">TARGET ENTRY</th>    
                    </tr>
                    <tr>
                        <th  colspan="8" width="25%" style="vertical-align:middle;color:#000;" bgcolor="#92D050">FINANCIAL YEAR <?php echo $financialYear; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td colspan="7" align="center" bgcolor="#BDD7EE" >Target</td>
                    </tr>
                    <?php
                    foreach ($major_activities_typeone as $key => $value) {
                        echo '<input type="hidden" name="type_1[]" value="' . $value->id . '" />';
                        echo ' <tr>
                        <td width="25%" align="center" style="font-weight:bold;">' . $value->name . '</td>
                        <td colspan="7" width="20%" align="center"><input style="width:120px;" type="text" autocomplete="off" name="target_'.$value->id.'" value="' . @$alldatagen[$value->id]->pset_value . '" /></td>
                        </tr>';
                    }
                    ?>

                </tbody>
                <thead>
                    <tr><th colspan="8" bgcolor='#ff9f08'></th></tr>
                    <tr>
                        <th  colspan="4" style="width:25%;vertical-align:middle;color:#000;" bgcolor="#92D050">P-SET</th>
                        <th  colspan="4" style="width:25%;vertical-align:middle;color:#000;" bgcolor="#92D050">C-SET</th>
<!--                        <th  colspan="2" style="width:25%;vertical-align:middle;color:#000;" bgcolor="#92D050">*PC-SET</th>
                        <th  colspan="2" style="width:25%;vertical-align:middle;color:#000;" bgcolor="#92D050">*OTHERS</th>-->
                    </tr>
                    <tr>
                        <td colspan="2" align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td colspan="2" align="center" bgcolor="#BDD7EE">Target</td>
                        <td colspan="2" align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td colspan="2" align="center" bgcolor="#BDD7EE">Target</td>
<!--                        <td  align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td  align="center" bgcolor="#BDD7EE">Target</td>
                        <td  align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td  align="center" bgcolor="#BDD7EE">Target</td>-->
                    </tr>
                </thead>
                <tbody>
                 
                    <?php
                    foreach ($major_activities_typetwo as $key => $value) {
                        echo '<input type="hidden" name="type_2[]" value="' . $value->id . '" />';
                        echo ' <tr>
                        <td colspan="2" width="10%" align="center" style="font-weight:bold;">' . $value->name . '</td>
                        <td colspan="2" width="10%" align="center"><input type="text" autocomplete="off" name="pset_value_'.$value->id.'" value="' . @$alldatagen[$value->id]->pset_value . '" /></td>
                        <td colspan="2" width="10%" align="center" style="font-weight:bold;">' . $value->name . '</td>
                        <td colspan="2" width="10%" align="center"><input type="text" autocomplete="off" name="cset_value_'.$value->id.'" value="' . @$alldatagen[$value->id]->pset_value . '" /></td>
                        </tr>';
                    }
                    ?>

                </tbody>
                
                  <thead>
                    <tr><th colspan="8" bgcolor='#ff9f08'></th></tr>
                    <tr>
                        <th  colspan="4" width="50%" style="vertical-align:middle;color:#000;" bgcolor="#92D050">GENERATION</th>
                        <th  colspan="4" width="50%" style="vertical-align:middle;color:#000;" bgcolor="#92D050">DISTRIBUTION</th>
                    </tr>
                    <tr>
                        <td colspan="2" width="25%" align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td colspan="2" width="25%" align="center" bgcolor="#BDD7EE">Target</td>
                        <td colspan="2" width="25%" align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td colspan="2" width="25%" align="center" bgcolor="#BDD7EE">Target</td>
                    </tr>
                </thead>
                <tbody>
                 
                    <?php
                    foreach ($major_activities_typethree as $key => $value) {
                        echo '<input type="hidden" name="type_3[]" value="' . $value->id . '" />';
                        echo ' <tr>
                        <td width="25%" colspan="2" align="center" style="font-weight:bold;">' . $value->name . '</td>
                        <td width="25%" align="center" colspan="2"><input type="text" autocomplete="off" name="gen_value_'.$value->id.'" value="' . @$alldatagen[$value->id]->pset_value . '" /></td>
                        <td width="25%" colspan="2" align="center" style="font-weight:bold;">' . $value->name . '</td>
                        <td width="25%" align="center" colspan="2"><input type="text" autocomplete="off" name="dist_value_'.$value->id.'" value="' . @$alldatagen[$value->id]->pset_value . '" /></td>
                        </tr>';
                    }
                    ?>

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
