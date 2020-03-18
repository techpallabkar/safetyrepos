
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
$financial_year                     = $beanUi->get_view_data("financial_year");
$allActivity = $beanUi->get_view_data("allActivity");
$allDistrict = $beanUi->get_view_data("allDistrict");
$array = $beanUi->get_view_data("array");
$id = $beanUi->get_view_data("id");
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

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">Annual Target for Distribution Site Audit (P+CC+C) <?php echo $financial_year; ?></h1> 
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
            <a href="SADist_TargetEntry.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr /> <?php //echo "<pre>";print_r($alldatagen);  ?>
    <div class="table-f table-responsive">
    <form action="" method="post" enctype="multipart/form-data">
        <?php if (!empty($getAllMonthofFinancialYear)) { ?>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th  width="5%" style="vertical-align:middle;" bgcolor="#B3A2C7" rowspan="2">MONTH / YEAR</th>
                            <?php
                            foreach ($allDistrict as $key => $rowdata) {
                                echo '<th bgcolor="#D99694" colspan="2">' . $rowdata->name . '</th>';
                            }
                            ?>
                        <th bgcolor="#D99694" colspan="2">TOTAL</th>
                    </tr>
                    <tr>
                            <?php
                            foreach ($allDistrict as $key => $rowdata) {
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
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
    <?php
     $i = 1;
    foreach ($getAllMonthofFinancialYear as $k => $rowdata) {
       if($array) {
            echo '<input type="hidden" name="activity_type_id[]" value="' . @$rowdata->id . '" />';
            echo '<tr class="totalCal" bgcolor="">'
            . '<td bgcolor="#D99694">' . date("M y", strtotime($rowdata)) . '</td>';
            foreach ($array as $key => $value) { 
                if($key == ($k + 1)) {
                     $ptotal = 0;
                     $ctotal = 0;
                    foreach ($allDistrict as $ky => $row) {  
                        foreach ($arr1 as $y => $r) { 
                            echo '<td><input type="text" name="data['.$r."-".$row->id."-". ($k + 1) .']" value="'.$array[$key][$allDistrict[$ky]->id][$r].'" style="width: 100%;" id="id'.$y.$allDistrict[$ky]->id.$r.$i.'"/></td>';
                            if($y == "0"){
                                 @$ptotal +=  $array[$key][$allDistrict[$ky]->id][$r];
                            }
                            if($y == "1"){
                                 @$ctotal +=  $array[$key][$allDistrict[$ky]->id][$r];
                            }
                            
                               ?>
        
                <script type="text/javascript">
                    $(document).ready(function () { 
                      $("#id0<?php echo $allDistrict[$ky]->id.$r.$i ?>").keydown( function () { 

                            var gworkmanp = (($('#id0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#id0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                            var gworkmanp1 = (($('#pid<?php echo $key ?>').val() == '') ? 0 : parseInt($('#pid<?php echo $key ?>').val()));
                            //alert(gworkmanp1);
                            var gtotalp =  gworkmanp1 - gworkmanp;

                            $(this).parent('td').siblings('td').find('#pid<?php echo $key ?>').val(gtotalp);

                      });
                      $("#id0<?php echo $allDistrict[$ky]->id.$r.$i ?>").keyup( function () { 

                            var gworkmanp = (($('#id0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#id0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                            var gworkmanp1 = (($('#pid<?php echo $key ?>').val() == '') ? 0 : parseInt($('#pid<?php echo $key ?>').val()));
                         
                            var gtotalp =  gworkmanp + gworkmanp1;

                            $(this).parent('td').siblings('td').find('#pid<?php echo $key ?>').val(gtotalp);

                      });
                      $("#id1<?php echo $allDistrict[$ky]->id.$r.$i ?>").keydown( function () {

                            var csetval = (($('#id1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#id1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                          var csetval2 = (($('#cid<?php echo $key ?>').val() == '') ? 0 : parseInt($('#cid<?php echo $key ?>').val()));
                         
                            var gtotalc = csetval2 - csetval;

                            $(this).parent('td').siblings('td').find('#cid<?php echo $key ?>').val(gtotalc);

                      });
                      $("#id1<?php echo $allDistrict[$ky]->id.$r.$i ?>").keyup( function () {

                            var csetval = (($('#id1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#id1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                          var csetval2 = (($('#cid<?php echo $key ?>').val() == '') ? 0 : parseInt($('#cid<?php echo $key ?>').val()));
                         
                            var gtotalc = csetval + csetval2;

                            $(this).parent('td').siblings('td').find('#cid<?php echo $key ?>').val(gtotalc);

                      });
        
          });
                </script>
        
     <?php 
      $i++;        
                        }
                    }
                    
                    
                    echo '<td><input type="text" value="'.$ptotal.'" style="width: 100%;" id="pid'.$key.'" readonly/></td>'
                            . '<td><input type="text" value="'.$ctotal.'" style="width: 100%;" id="cid'.$key.'" readonly/></td>';
     
                }
            }
            echo '</tr>';
        } else {
            echo '<tr class="totalCal" bgcolor="">'
            . '<td bgcolor="#D99694">' . date("M y", strtotime($rowdata)) . '<input type="hidden" name="activity_type_id[]" value="' . ($k + 1) . '" /></td>';
            foreach ($allDistrict as $key1 => $rowdata1) {
                echo '<td><input type="text" name="data['."P-".($k + 1).'-'.$rowdata1->id.']" value="" style="width: 100%;" id="pid1'.$k.$rowdata1->id.$i.'"/></td>';
                echo '<td><input type="text" name="data['."C-".($k + 1).'-'.$rowdata1->id.']" value="" style="width: 100%;" id="cid2'.$k.$rowdata1->id.$i.'"/></td>';
            ?>
                
            <script type="text/javascript">
                    $(document).ready(function () {
                        
                      $("#pid1<?php echo $k.$rowdata1->id.$i ?>").keydown( function () {

                            var gworkmanp = (($('#pid1<?php echo $k.$rowdata1->id.$i ?>').val() == '') ? 0 : parseInt($('#pid1<?php echo $k.$rowdata1->id.$i ?>').val()));
                            var gworkmanp1 = (($('#ppid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#ppid<?php echo $k ?>').val()));
                         
                            var gtotalp =  gworkmanp1 - gworkmanp;

                            $(this).parent('td').siblings('td').find('#ppid<?php echo $k ?>').val(gtotalp);

                      });
                      $("#pid1<?php echo $k.$rowdata1->id.$i ?>").keyup( function () {

                            var gworkmanp = (($('#pid1<?php echo $k.$rowdata1->id.$i ?>').val() == '') ? 0 : parseInt($('#pid1<?php echo $k.$rowdata1->id.$i ?>').val()));
                            var gworkmanp1 = (($('#ppid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#ppid<?php echo $k ?>').val()));
                         
                            var gtotalp =  gworkmanp + gworkmanp1;

                            $(this).parent('td').siblings('td').find('#ppid<?php echo $k ?>').val(gtotalp);

                      });
                      $("#cid2<?php echo $k.$rowdata1->id.$i ?>").keydown( function () {

                            var csetval = (($('#cid2<?php echo $k.$rowdata1->id.$i ?>').val() == '') ? 0 : parseInt($('#cid2<?php echo $k.$rowdata1->id.$i ?>').val()));
                          var csetval2 = (($('#ccid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#ccid<?php echo $k ?>').val()));
                         
                            var gtotalc = csetval2 - csetval;

                            $(this).parent('td').siblings('td').find('#ccid<?php echo $k ?>').val(gtotalc);

                      });
                      $("#cid2<?php echo $k.$rowdata1->id.$i ?>").keyup( function () {

                            var csetval = (($('#cid2<?php echo $k.$rowdata1->id.$i ?>').val() == '') ? 0 : parseInt($('#cid2<?php echo $k.$rowdata1->id.$i ?>').val()));
                          var csetval2 = (($('#ccid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#ccid<?php echo $k ?>').val()));
                         
                            var gtotalc = csetval + csetval2;

                            $(this).parent('td').siblings('td').find('#ccid<?php echo $k ?>').val(gtotalc);

                      });
        
          });
                </script>
                <?php $i++; ?>
    <?php    
            }
            echo '<td><input type="text" value="" style="width: 100%;" id="ppid'.$k.'" readonly/></td>'
                    . '<td><input type="text" value="" style="width: 100%;" id="ccid'.$k.'" readonly/></td>';
          echo '</tr>';
        }
    }
    ?>
                </tbody>
            </table>  
        <div class="col-sm-4">
        <?php if($array) { ?>
            <input type="hidden" name="_action" value="Update" />
            <button type="submit" name="submit" class="btn btn-primary btn-sm">Update</button> 
        <?php } else { ?>
            <input type="hidden" name="_action1" value="Save" />
            <button type="submit" name="B2" class="btn btn-primary btn-sm">Save</button>
         <?php } ?>
    </div>  
<?php } ?>
    </form>
</div>
</div>
<?php $controller->get_footer(); ?>
<style>
    .table-f .table thead tr th, .table-f .table tbody tr td {
        font-size: 10px !important;
    }
</style>
</body>
</html>
<script type="text/javascript">
$(document).ready(function(){
   var fyid = "<?php echo $financial_year_for_select; ?>"
   var fydid = "<?php echo $financial_district_for_select; ?>"
   if(fyid){
       $("#financial_yearselect").val(fyid);
       $("#financial_district").val(fydid);
       $("form#fyr").append('<input type="hidden" value="fromsubmit" name="fromsubmit">');
       $("form#fyr").submit();
      
   }
});

</script>

