
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
$allContractor = $beanUi->get_view_data("allContractor");
$allDistrict = $beanUi->get_view_data("allDistrict");
$array = $beanUi->get_view_data("array");

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

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">Annual GENERATION Site Audit (P+C) <?php echo $financial_year; ?></h1> 
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
            <a href="SAGen_TargetView.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr /> <?php //echo "<pre>";print_r($alldatagen);  ?>
    <div class="table-f table-responsive">
    <form action="" method="post" enctype="multipart/form-data">
        <?php if (!empty($getAllMonthofFinancialYear)) { ?>
            <table class="table table-bordered table-condensed table-responsive">
                <thead>
                    <tr>
                        <th bgcolor="#B3A2C7">SL</th>
                        <th  width="20%" style="vertical-align:middle;" bgcolor="#B3A2C7">Name of the Dept. / Contractor</th>
                        <?php
                        foreach($getAllMonthofFinancialYear as $rowdata)
                        {
                            echo '<th bgcolor="#D99694">'.date("M y",strtotime($rowdata)).'</th>';
                        }
                        ?>
                         <th bgcolor="#D99694">TOTAL</th>
                    </tr>
                </thead>
                
        <input type="hidden" name="financial_year_id" value="<?php echo @$financial_year_id; ?>" />
        <input type="hidden" name="financial_district_id" value="<?php echo @$financial_district_id; ?>" />
        
    <?php 
    foreach ($allContractor as $key => $rowdata) {
        echo '<tr class="totalCal" >' 
            . '<td bgcolor="#BDBDBD">'.($key+1).'</td>'                           
            . '<td bgcolor="#BDBDBD"><b>' . $rowdata->name . '</b></td>';
        foreach ($array as $m => $val) {
            if($m == $rowdata->id) {
                $total = 0;
                foreach ($getAllMonthofFinancialYear as $k => $v) {
                    if(!isset($array[$m][$k+1])) {
                        echo '<td bgcolor="#BDBDBD"></td>';
                    } else {
                        echo '<td bgcolor="#BDBDBD">'.$array[$m][$k+1].'</td>';
                    }
                    @$total +=  $array[$m][$k+1];
                }
                echo '<td bgcolor="#BDBDBD">'.$total.'</td>';
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

