
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

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">Annual Distribution PPE Audit (P+CC+C) Schedule of Safety Cell <?php echo $financial_year; ?></h1> 
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
            <a href="PPEA_TargetEntry.php" class="btn btn-danger btn-sm">Reset</a>
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
                            <th bgcolor="#D99694" rowspan="2">STATION</th>
                            <?php
                            foreach ($allDistrict as $key => $rowdata) {
                                echo '<th bgcolor="#D99694" colspan="2">' . $rowdata->name . '</th>';
                            }
                            ?>
                        <th bgcolor="#D99694" colspan="2">TOTAL</th>
                    </tr>
                    <tr>
                            <?php
                            foreach ($allDistrict as $key => $row) {
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
    $app = array(); 
     $i = 1;
    foreach ($getAllMonthofFinancialYear as $k => $rowdata) { 
        if($array) { 
            echo '<input type="hidden" name="activity_type_id[]" value="' . @$rowdata->id . '" />';
            echo '<tr class="totalCal" bgcolor="">'
            . '<td bgcolor="#D99694" rowspan="2">' . date("M y", strtotime($rowdata)) . '</td>';
            echo '<td bgcolor="#D99694">GEN</td>';
                foreach ($array as $key => $value) { 
                    if($key == 'G') { 
                        foreach ($array[$key] as $p => $q) { 
                            if($p == ($k + 1)) { 
                                $gptotal = 0;
                                $gctotal = 0;
                                foreach ($allDistrict as $ky => $row) { 
                                    foreach ($arr1 as $y => $r) { 
                                        echo '<td><input type="text" name="data[G-'.$r."-".$row->id."-". ($k + 1) .']" value="'.$array[$key][$p][$allDistrict[$ky]->id][$r].'" style="width: 100%;" id="idg'.$y.$allDistrict[$ky]->id.$r.$i.'"/></td>';
                                        if($y == "0"){
                                             @$gptotal +=  $array[$key][$p][$allDistrict[$ky]->id][$r];
                                        }
                                        if($y == "1"){
                                             @$gctotal +=  $array[$key][$p][$allDistrict[$ky]->id][$r];
                                        }
    ?>
                                <script type="text/javascript">
                                    $(document).ready(function () { 

                                        $("#idg0<?php echo $allDistrict[$ky]->id.$r.$i ?>").keydown( function () { 

                                              var gworkmanp = (($('#idg0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#idg0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                                              
                                              var gworkmanp1 = (($('#gpid<?php echo $p ?>').val() == '') ? 0 : parseInt($('#gpid<?php echo $p ?>').val()));
                                              
                                              var gtotalp =  gworkmanp1 - gworkmanp;

                                              $(this).parent('td').siblings('td').find('#gpid<?php echo $p ?>').val(gtotalp);

                                        });
                                        $("#idg0<?php echo $allDistrict[$ky]->id.$r.$i ?>").keyup( function () { 

                                              var gworkmanp = (($('#idg0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#idg0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                                              var gworkmanp1 = (($('#gpid<?php echo $p ?>').val() == '') ? 0 : parseInt($('#gpid<?php echo $p ?>').val()));

                                              var gtotalp =  gworkmanp + gworkmanp1;

                                              $(this).parent('td').siblings('td').find('#gpid<?php echo $p ?>').val(gtotalp);

                                        });
                                        $("#idg1<?php echo $allDistrict[$ky]->id.$r.$i ?>").keydown( function () {

                                              var csetval = (($('#idg1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#idg1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                                            var csetval2 = (($('#gcid<?php echo $p ?>').val() == '') ? 0 : parseInt($('#gcid<?php echo $p ?>').val()));

                                              var gtotalc = csetval2 - csetval;

                                              $(this).parent('td').siblings('td').find('#gcid<?php echo $p ?>').val(gtotalc);

                                        });
                                        $("#idg1<?php echo $allDistrict[$ky]->id.$r.$i ?>").keyup( function () {

                                              var csetval = (($('#idg1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#idg1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                                            var csetval2 = (($('#gcid<?php echo $p ?>').val() == '') ? 0 : parseInt($('#gcid<?php echo $p ?>').val()));

                                              var gtotalc = csetval + csetval2;

                                              $(this).parent('td').siblings('td').find('#gcid<?php echo $p ?>').val(gtotalc);

                                        });

                                    });
                                </script>

                    <?php 
                            $i++;         
                                    } 
                                } echo '<td><input type="text" value="'.$gptotal.'" style="width: 100%;" id="gpid'.$p.'" readonly/></td>'
                                     . '<td><input type="text" value="'.$gctotal.'" style="width: 100%;" id="gcid'.$p.'" readonly/></td>';
                            }
                        }
                    }
                }
            echo '</tr>';
            echo '<tr>';
            echo '<td bgcolor="#D99694">DIST</td>';
                foreach ($array as $key => $value) { 
                    if($key == 'D') {
                        foreach ($array[$key] as $p => $q) {
                            if($p == ($k + 1)) {
                                $dptotal = 0;
                                $dctotal = 0;
                                foreach ($allDistrict as $ky => $row) { 
                                    foreach ($arr1 as $y => $r) { 
                                        echo '<td><input type="text" name="data[D-'.$r."-".$row->id."-". ($k + 1) .']" value="'.$array[$key][$p][$allDistrict[$ky]->id][$r].'" style="width: 100%;" id="idd'.$y.$allDistrict[$ky]->id.$r.$i.'"/></td>';
                                        if($y == "0"){
                                             @$dptotal +=  $array[$key][$p][$allDistrict[$ky]->id][$r];
                                        }
                                        if($y == "1"){
                                             @$dctotal +=  $array[$key][$p][$allDistrict[$ky]->id][$r];
                                        }

                                           ?>

                                <script type="text/javascript">
                                    $(document).ready(function () { 

                                        $("#idd0<?php echo $allDistrict[$ky]->id.$r.$i ?>").keydown( function () { //alert("hho");

                                              var gworkmanp = (($('#idd0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#idd0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                                              var gworkmanp1 = (($('#dpid<?php echo $p ?>').val() == '') ? 0 : parseInt($('#dpid<?php echo $p ?>').val()));

                                              var dtotalp =  gworkmanp1 - gworkmanp;

                                              $(this).parent('td').siblings('td').find('#dpid<?php echo $p ?>').val(dtotalp);

                                        });
                                        $("#idd0<?php echo $allDistrict[$ky]->id.$r.$i ?>").keyup( function () { 

                                              var gworkmanp = (($('#idd0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#idd0<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                                              var gworkmanp1 = (($('#dpid<?php echo $p ?>').val() == '') ? 0 : parseInt($('#dpid<?php echo $p ?>').val()));

                                              var dtotalp =  gworkmanp + gworkmanp1;

                                              $(this).parent('td').siblings('td').find('#dpid<?php echo $p ?>').val(dtotalp);

                                        });
                                        $("#idd1<?php echo $allDistrict[$ky]->id.$r.$i ?>").keydown( function () {

                                              var csetval = (($('#idd1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#idd1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                                            var csetval2 = (($('#dcid<?php echo $p ?>').val() == '') ? 0 : parseInt($('#dcid<?php echo $p ?>').val()));

                                              var dtotalc = csetval2 - csetval;

                                              $(this).parent('td').siblings('td').find('#dcid<?php echo $p ?>').val(dtotalc);

                                        });
                                        $("#idd1<?php echo $allDistrict[$ky]->id.$r.$i ?>").keyup( function () {

                                              var csetval = (($('#idd1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val() == '') ? 0 : parseInt($('#idd1<?php echo $allDistrict[$ky]->id.$r.$i ?>').val()));
                                            var csetval2 = (($('#dcid<?php echo $p ?>').val() == '') ? 0 : parseInt($('#dcid<?php echo $p ?>').val()));

                                              var dtotalc = csetval + csetval2;

                                              $(this).parent('td').siblings('td').find('#dcid<?php echo $p ?>').val(dtotalc);

                                        });

                                    });
                                </script>

                    <?php 
                            $i++;         
                                    } 
                                } echo '<td><input type="text" value="'.$dptotal.'" style="width: 100%;" id="dpid'.$p.'" readonly/></td>'
                                     . '<td><input type="text" value="'.$dctotal.'" style="width: 100%;" id="dcid'.$p.'" readonly/></td>';
                            }
                        }
                    }
                }
            echo '</tr>';
        } else {
            echo '<tr class="totalCal" bgcolor="">'
            . '<td bgcolor="#D99694" rowspan="2">' . date("M y", strtotime($rowdata)) . '<input type="hidden" name="activity_type_id[]" value="'. ($k + 1) .'" /></td>';
            echo '<td bgcolor="#D99694">GEN</td>';
                foreach ($allDistrict as $key => $value) {
                    echo '<td><input type="text" class="pset" name="data['."G-P-".$value->id."-". ($k + 1) .']" value="" style="width: 100%;" id="gpid1'.$k.$value->id.$i.'"/></td>';
                    echo '<td><input type="text" name="data['."G-C-".$value->id."-". ($k + 1) .']" value="" style="width: 100%;" id="gcid2'.$k.$value->id.$i.'"/></td>';

        ?>
                    <script type="text/javascript">
                        $(document).ready(function () {

                          $("#gpid1<?php echo $k.$value->id.$i ?>").keydown( function () {

                                var gworkmanp = (($('#gpid1<?php echo $k.$value->id.$i ?>').val() == '') ? 0 : parseInt($('#gpid1<?php echo $k.$value->id.$i ?>').val()));
                                
                                var gworkmanp1 = (($('#gpid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#gpid<?php echo $k ?>').val()));
                                
                                var gtotalp =  gworkmanp1 - gworkmanp;
                                
                                $(this).parent('td').siblings('td').find('#gpid<?php echo $k ?>').val(gtotalp);

                          });
                          $("#gpid1<?php echo $k.$value->id.$i ?>").keyup( function () {

                                var gworkmanp = (($('#gpid1<?php echo $k.$value->id.$i ?>').val() == '') ? 0 : parseInt($('#gpid1<?php echo $k.$value->id.$i ?>').val()));
                                
                                var gworkmanp1 = (($('#gpid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#gpid<?php echo $k ?>').val()));
                                
                                var gtotalp =  gworkmanp + gworkmanp1;
                                
                                $(this).parent('td').siblings('td').find('#gpid<?php echo $k ?>').val(gtotalp);

                          });
                          $("#gcid2<?php echo $k.$value->id.$i ?>").keydown( function () {

                                var csetval = (($('#gcid2<?php echo $k.$value->id.$i ?>').val() == '') ? 0 : parseInt($('#gcid2<?php echo $k.$value->id.$i ?>').val()));
                              var csetval2 = (($('#gcid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#gcid<?php echo $k ?>').val()));

                                var gtotalc = csetval2 - csetval;
                                 $(this).parent('td').siblings('td').find('#gcid<?php echo $k ?>').val(gtotalc);
                          });
                          $("#gcid2<?php echo $k.$value->id.$i ?>").keyup( function () {

                                var csetval = (($('#gcid2<?php echo $k.$value->id.$i ?>').val() == '') ? 0 : parseInt($('#gcid2<?php echo $k.$value->id.$i ?>').val()));
                              var csetval2 = (($('#gcid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#gcid<?php echo $k ?>').val()));

                                var gtotalc = csetval + csetval2;
                                
                                $(this).parent('td').siblings('td').find('#gcid<?php echo $k ?>').val(gtotalc);
                          });

              });
                    </script>
         <?php   
                $i++;
                }
                echo '<td><input type="text" value="" style="width: 100%;" id="gpid'.$k.'" readonly/></td>'
                        . '<td><input type="text" value="" style="width: 100%;" id="gcid'.$k.'" readonly/></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<td bgcolor="#D99694">DIST</td>';
                foreach ($allDistrict as $key => $value) {
                     echo '<td><input type="text" name="data['."D-P-".$value->id."-". ($k + 1) .']" value="" style="width: 100%;" id="dpid1'.$k.$value->id.$i.'"/></td>';
                     echo '<td><input type="text" name="data['."D-C-".$value->id."-". ($k + 1) .']" value="" style="width: 100%;" id="dcid2'.$k.$value->id.$i.'"/></td>';

                      ?>

                <script type="text/javascript">
                         $(document).ready(function () {

                           $("#dpid1<?php echo $k.$value->id.$i ?>").keydown( function () {

                                 var gworkmanp = (($('#dpid1<?php echo $k.$value->id.$i ?>').val() == '') ? 0 : parseInt($('#dpid1<?php echo $k.$value->id.$i ?>').val()));
                                 var gworkmanp1 = (($('#dpid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#dpid<?php echo $k ?>').val()));

                                 var gtotalp =  gworkmanp1 - gworkmanp;

                                 $(this).parent('td').siblings('td').find('#dpid<?php echo $k ?>').val(gtotalp);

                           });
                           $("#dpid1<?php echo $k.$value->id.$i ?>").keyup( function () {

                                 var gworkmanp = (($('#dpid1<?php echo $k.$value->id.$i ?>').val() == '') ? 0 : parseInt($('#dpid1<?php echo $k.$value->id.$i ?>').val()));
                                 var gworkmanp1 = (($('#dpid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#dpid<?php echo $k ?>').val()));

                                 var gtotalp =  gworkmanp + gworkmanp1;

                                 $(this).parent('td').siblings('td').find('#dpid<?php echo $k ?>').val(gtotalp);

                           });
                           $("#dcid2<?php echo $k.$value->id.$i ?>").keydown( function () {

                                 var csetval = (($('#dcid2<?php echo $k.$value->id.$i ?>').val() == '') ? 0 : parseInt($('#dcid2<?php echo $k.$value->id.$i ?>').val()));
                               var csetval2 = (($('#dcid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#dcid<?php echo $k ?>').val()));

                                 var gtotalc = csetval2 - csetval;

                                 $(this).parent('td').siblings('td').find('#dcid<?php echo $k ?>').val(gtotalc);
                           });
                           $("#dcid2<?php echo $k.$value->id.$i ?>").keyup( function () {

                                 var csetval = (($('#dcid2<?php echo $k.$value->id.$i ?>').val() == '') ? 0 : parseInt($('#dcid2<?php echo $k.$value->id.$i ?>').val()));
                               var csetval2 = (($('#dcid<?php echo $k ?>').val() == '') ? 0 : parseInt($('#dcid<?php echo $k ?>').val()));

                                 var gtotalc = csetval + csetval2;

                                 $(this).parent('td').siblings('td').find('#dcid<?php echo $k ?>').val(gtotalc);
                           });

               });
                     </script>

          <?php   
                $i++;
                 }
                 echo '<td><input type="text" value="" style="width: 100%;" id="dpid'.$k.'" readonly/></td>'
                         . '<td><input type="text" value="" style="width: 100%;" id="dcid'.$k.'" readonly/></td>';
           echo '<tr>';
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
            <input type="hidden" name="_action" value="Input" />
            <button type="submit" name="submit" class="btn btn-primary btn-sm">Submit</button> 
        <?php } ?>
    </div>  
<?php } ?>
         
    </form>
</div>    
</div>
<?php $controller->get_footer(); ?>

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
