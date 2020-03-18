
<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ReportController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$financial_year = ($beanUi->get_view_data("financial_year") ? $beanUi->get_view_data("financial_year") : "" );
$financial_year_id = ($beanUi->get_view_data("financial_year_id") ? $beanUi->get_view_data("financial_year_id") : "" );
$major_activities_typeone = $beanUi->get_view_data("major_activities_typeone");
$major_activities_typetwo = $beanUi->get_view_data("major_activities_typetwo");
$major_activities_typethree = $beanUi->get_view_data("major_activities_typethree");
$all_yearly_target_of_one = $beanUi->get_view_data("all_yearly_target_of_one");
$all_yearly_target_of_two = $beanUi->get_view_data("all_yearly_target_of_two");
$all_yearly_target_of_three = $beanUi->get_view_data("all_yearly_target_of_three");
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

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">TARGET ENTRY</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr />
   
    
        <div class="col-sm-12">
<label class="col-sm-2">Financial Year : </label>
           <?php echo $financial_year; ?>
        </div>
        <hr />
        <br>
    <form action="" method="post" enctype="multipart/form-data">
        <?php if (!empty($major_activities_typeone)) { ?>        
                <input type="hidden" name="financial_year_id" value="<?php echo @$financial_year_id; ?>" />
            <table class="table table-bordered table-condensed table-responsive" style="font-size:14px;" >
                <thead>
                    <tr class="bg-primary">
                        <th  colspan="8" width="25%" style="vertical-align:middle;">TARGET ENTRY</th>    
                    </tr>
                    <tr>
                        <th  colspan="8" width="25%" style="vertical-align:middle;color:#000;" bgcolor="#92D050">FINANCIAL YEAR <?php echo @$financialYear; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td align="center" bgcolor="#BDD7EE" colspan="7">Target</td>
                    </tr>
                    <?php
                    foreach ($major_activities_typeone as $key => $value) {
                        $alldata = $all_yearly_target_of_one[$value->id];
                        $target_value = (isset($alldata[0]->target)) ? @$alldata[0]->target : "";
                        $rowid = (isset($alldata[0]->id)) ? @$alldata[0]->id : "";
                        echo '<input type="hidden" name="type_1[]" value="' . $value->id . '" />'
                                . '<input type="hidden" name="row_id_1'.$value->id.'" value="' . $rowid . '" />';
                        echo ' <tr>
                        <td width="25%" align="center" style="font-weight:bold;">' . $value->name . '</td>
                        <td colspan="7" width="20%" align="center"><input style="width:120px;" type="text" autocomplete="off" name="target_'.$value->id.'" value="' . $target_value . '" /></td>
                        </tr>';
                    }
                    ?>

                </tbody>
                <thead>
                    <tr><th colspan="8" bgcolor='#ff9f08'></th></tr>
                    <tr>
                        <th  colspan="4" width="50%" style="vertical-align:middle;color:#000;" bgcolor="#92D050">P-SET</th>
                        <th  colspan="4" width="50%" style="vertical-align:middle;color:#000;" bgcolor="#92D050">C-SET</th>
<!--                        <th  colspan="2" width="50%" style="vertical-align:middle;color:#000;" bgcolor="#92D050">PC-SET</th>
                        <th  colspan="2" width="50%" style="vertical-align:middle;color:#000;" bgcolor="#92D050">OTHERS</th>-->
                    </tr>
                    <tr>
                        <td colspan="2" align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td colspan="2" align="center" bgcolor="#BDD7EE">Target</td>
                        <td colspan="2" align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td colspan="2" align="center" bgcolor="#BDD7EE">Target</td>
<!--                        <td align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td align="center" bgcolor="#BDD7EE">Target</td>
                        <td align="center" bgcolor="#BDD7EE">District / Section</td>
                        <td align="center" bgcolor="#BDD7EE">Target</td>-->
                    </tr>
                </thead>
                <tbody>
                 
                    <?php
                    foreach ($major_activities_typetwo as $key => $value) {
                        $alldata = $all_yearly_target_of_two[$value->id];
                        @$pset_target_value = @$alldata[0]->pset_target ? $alldata[0]->pset_target : "";
                        @$cset_target_value = @$alldata[0]->cset_target ? $alldata[0]->cset_target : "";
                        @$pcset_target_value = @$alldata[0]->pcset_target ? $alldata[0]->pcset_target : "";
                        @$others_target_value = @$alldata[0]->others_target ? $alldata[0]->others_target : "";
                        @$rowid = $alldata[0]->id ? $alldata[0]->id : "";
                        echo '<input type="hidden" name="type_2[]" value="' . $value->id . '" />'
                                . '<input type="hidden" name="row_id_2'.$value->id.'" value="' . $rowid . '" />';
                        echo ' <tr>
                        <td colspan="2" width="10%" align="center" style="font-weight:bold;">' . $value->name . '</td>
                        <td colspan="2" width="10%" align="center"><input type="text" autocomplete="off" name="pset_value_'.$value->id.'" value="' . $pset_target_value. '" /></td>
                        <td colspan="2" width="10%" align="center" style="font-weight:bold;">' . $value->name . '</td>
                        <td colspan="2" width="10%" align="center"><input type="text" autocomplete="off" name="cset_value_'.$value->id.'" value="' . $cset_target_value . '" /></td>
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
                        $alldata            = $all_yearly_target_of_three[$value->id];
                        $gen_target_value   = (isset($alldata[0]->gen)) ? $alldata[0]->gen : "";
                        $dist_target_value  = (isset($alldata[0]->dist)) ? $alldata[0]->dist : "";
                        $rowid              = (isset($alldata[0]->id)) ? $alldata[0]->id : "";
                        echo '<input type="hidden" name="type_3[]" value="' . $value->id . '" />'
                                . '<input type="hidden" name="row_id_3'.$value->id.'" value="' . $rowid . '" />';
                        echo ' <tr>
                        <td colspan="2" width="25%" align="center" style="font-weight:bold;">' . $value->name . '</td>
                        <td colspan="2" width="25%" align="center"><input type="text" autocomplete="off" name="gen_value_'.$value->id.'" value="' . $gen_target_value . '" /></td>
                        <td colspan="2" width="25%" align="center" style="font-weight:bold;">' . $value->name . '</td>
                        <td colspan="2" width="25%" align="center"><input type="text" autocomplete="off" name="dist_value_'.$value->id.'" value="' . $dist_target_value . '" /></td>
                        </tr>';
                    }
                    ?>

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="16" align="right">
                            <input type="hidden" name="_action" value="submitData" />
                            <button type="submit" name="B2" class="btn btn-primary btn-sm">Update</button>
                            <a href="listtargetEntry.php" class="btn btn-danger btn-sm">Cancel</a>
                        </td>
                    </tr>
                </tfoot>

            </table>

        <?php } ?>
    </form>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>