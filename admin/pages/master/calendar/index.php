<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );

$controller = load_controller( "CalendarController" );
$controller->doAction();
$beanUi                         = $controller->beanUi;
$allFinancialYear                     = $beanUi->get_view_data( "allFinancialYear" );
$allSafetycellActivity                     = $beanUi->get_view_data( "allSafetycellActivity" );
$alldatacalender                     = $beanUi->get_view_data( "alldatacalender" );
$getAllMonthofFinancialYear                     = $beanUi->get_view_data( "getAllMonthofFinancialYear" );
$financial_year_id                     = $beanUi->get_view_data( "financial_year_id" );
$financial_year_for_select                     = $beanUi->get_view_data( "financial_year_for_select" );

$site_root_url                  = dirname(url());


$controller->setCss("tree");
$controller->get_header();
?>

<style type="text/css">
#selected_cat{  }
.smallfont{ font-size:90%; }
</style>

<div class="container1">
	<h1 class="heading">Manage Calendar</h1>
	<div class="holder2 col-md-12">
		<?php echo $beanUi->get_message(); ?>
	</div>
	
        
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

        </div>
    </form>
    <hr /> 
        
        
    <form action="" method="post" enctype="multipart/form-data">
        <?php if (!empty($getAllMonthofFinancialYear)) { ?>
            <table class="table table-bordered table-condensed table-responsive">
                <thead>
                    <tr>
                        <th width="25%" style="vertical-align:middle;" bgcolor="#B3A2C7">TARGET</th>
                        <?php
                        foreach ($getAllMonthofFinancialYear as $rowdata) {
                            echo '<th bgcolor="#D99694">' . date("M y", strtotime($rowdata)) . '</th>';
                        }
                        ?>
                        
                    </tr>
                </thead>
                <input type="hidden" name="financial_year_id" value="<?php echo @$financial_year_id; ?>" />
                <?php
                foreach ($allSafetycellActivity as $key => $rowdata) {
                   
                        echo '<input type="hidden" name="safetycell_activity_id[]" value="' . $rowdata->id . '" />';
                        echo '<tr class="totalCal' . $rowdata->id . '" >'
                        . '<td><b>' . $rowdata->name . '</b></td>'
                        . '<td><input value="' .  @$alldatacalender[$rowdata->id]->april_month . '" type="text" class="auto newcal" data-v-max="9999" data-v-min="0"  style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'
                        . '<td><input  value="' . @$alldatacalender[$rowdata->id]->may_month . '" type="text" class="auto newcal" data-v-max="9999" data-v-min="0" style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'
                        . '<td><input  value="' . @$alldatacalender[$rowdata->id]->june_month . '" type="text" class="auto newcal" data-v-max="9999" data-v-min="0"  style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'
                        . '<td><input  value="' . @$alldatacalender[$rowdata->id]->july_month . '"   type="text" class="auto newcal" data-v-max="9999" data-v-min="0"  style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'
                        . '<td><input  value="' . @$alldatacalender[$rowdata->id]->august_month . '"   type="text" class="auto newcal" data-v-max="9999" data-v-min="0"  style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'
                        . '<td><input  value="' . @$alldatacalender[$rowdata->id]->september_month . '"   type="text" class="auto newcal" data-v-max="9999" data-v-min="0"  style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'
                        . '<td><input  value="' . @$alldatacalender[$rowdata->id]->october_month . '"   type="text" class="auto newcal" data-v-max="9999" data-v-min="0"  style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'
                        . '<td><input  value="' . @$alldatacalender[$rowdata->id]->november_month . '"   type="text" class="auto newcal" data-v-max="9999" data-v-min="0"  style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'
                        . '<td><input  value="' . @$alldatacalender[$rowdata->id]->december_month . '"   type="text" class="auto newcal" data-v-max="9999" data-v-min="0"  style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'
                        . '<td><input  value="' . @$alldatacalender[$rowdata->id]->january_month . '"   type="text" class="auto newcal" data-v-max="9999" data-v-min="0"  style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'
                        . '<td><input  value="' . @$alldatacalender[$rowdata->id]->february_month . '"   type="text" class="auto newcal" data-v-max="9999" data-v-min="0"  style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'
                        . '<td><input  value="' . @$alldatacalender[$rowdata->id]->march_month . '"   type="text" class="auto newcal" data-v-max="9999" data-v-min="0"  style="width:100%;" name="monthly_value' . $rowdata->id . '[]" /></td>'                       
                        . '</tr>';
                    
                
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


<script type="text/javascript">
    
    $(document).ready(function () {
  
    
        $("#financial_yearselect").change(function () {
            this.form.submit();
        });
        var fyid = "<?php echo $financial_year_for_select; ?>"
       
        if (fyid) {
            $("#financial_yearselect").val(fyid);
            
            $("form#fyr").append('<input type="hidden" value="fromsubmit" name="fromsubmit">');
            $("form#fyr").submit();

        }


//        $('.newcal').keyup(function () {
//            var sum = 0;
//            var cls = $(this).parents('td').parents('tr').attr("class");
//
//            $(this).parents('td').parents('tr').children('td').children('.newcal').each(function () {
//                var calpart = $(this).val();
//                sum += Number(calpart);
//            });
//
//            $(this).parents('tr').children('td').children('.genTotal').val(sum);
//            var total = 0;
//            $(this).parents('td').parents('tr').parents('tl').css("background", "red");
//            //subtotal
//            $("." + cls).children('td').children('.genTotal').each(function () {
//                var calpartt = $(this).val();
//                total += Number(calpartt);
//            });
//            $("." + cls).children('td').children('.grand_total').val(total);
//        });

    });

</script>
</body>
</html>
