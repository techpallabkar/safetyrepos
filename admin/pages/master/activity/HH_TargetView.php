
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
$array = $beanUi->get_view_data("array");
$arr1 = $beanUi->get_view_data("arr1");

$financial_year_for_select = $beanUi->get_view_data("financial_year_for_select");
$financial_district_for_select = $beanUi->get_view_data("financial_district_for_select");
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

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">Annual Hand Holding Target Entry (P+C) <?php echo $financial_year; ?></h1> 
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
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="HH_TargetView.php_TargetEntry.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr /> <?php //echo "<pre>";print_r($alldatagen);  ?>
    <div class="table-f table-responsive">
    <form action="" method="post" enctype="multipart/form-data">
        <?php if (!empty($getAllMonthofFinancialYear)) { ?>
            <table class="table table-bordered table-condensed table-responsive">
                <thead>
                    <tr>
                        <th  width="5%" style="vertical-align:middle;" bgcolor="#B3A2C7" rowspan="2">MONTH / YEAR</th>
                            <?php
                            foreach ($allDistrict as $key => $rowdata) {
                                echo '<th bgcolor="#D99694" colspan="3">' . $rowdata->name . '</th>';
                            }
                            ?>
                        <th bgcolor="#D99694" colspan="2">TOTAL</th>
                    </tr>
                    <tr>
                        <?php 
                        foreach ($allDistrict as $key => $row) {
                            echo '<th bgcolor="#D99694">HH</th>';
                            echo '<th bgcolor="#D99694">P-SET</th>';
                            echo '<th bgcolor="#D99694">C-SET</th>';
                        }
                        echo '<th bgcolor="#D99694">P-SET</th>';
                        echo '<th bgcolor="#D99694">C-SET</th>';
                        ?>
                    </tr>
                </thead>
                
                <input type="hidden" name="financial_year_id" value="<?php echo @$financial_year_id; ?>" />
                <input type="hidden" name="financial_district_id" value="<?php echo @$financial_district_id; ?>" />
    <?php 
    foreach ($getAllMonthofFinancialYear as $k => $rowdata) {
        echo '<input type="hidden" name="activity_type_id[]" value="' . $rowdata->id . '" />';
        echo '<tr class="totalCal" bgcolor="">'
        . '<td bgcolor="#D99694">' . date("M y", strtotime($rowdata)) . '</td>';
        foreach ($array as $key => $value) { 
            if($key == ($k + 1)) {
                $ptotal = 0;
                $ctotal = 0;
                foreach ($allDistrict as $ky => $row) { 
                    foreach ($arr1 as $y => $r) { 
                        echo '<td>'.$array[$key][$allDistrict[$ky]->id][$r].'</td>';
                        if($y == "1"){
                            @$ptotal +=  $array[$key][$allDistrict[$ky]->id][$r];
                       }
                       if($y == "2"){
                            @$ctotal +=  $array[$key][$allDistrict[$ky]->id][$r];
                       }
                    }
                }
                echo '<td>'.$ptotal.'</td>'
                        . '<td>'.$ctotal.'</td>';
            }
        }
        echo '</tr>';
    }
    ?>
                </tbody>
            </table>  
<?php } ?>
    </form>
    </div>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>
<script type="text/javascript">
//$(document).ready(function(){
//   var fyid = "<?php echo $financial_year_for_select; ?>"
//   var fydid = "<?php echo $financial_district_for_select; ?>"
//   if(fyid){
//       $("#financial_yearselect").val(fyid);
//       $("#financial_district").val(fydid);
//       $("form#fyr").append('<input type="hidden" value="fromsubmit" name="fromsubmit">');
//       $("form#fyr").submit();
//      
//   }
//});

</script>

