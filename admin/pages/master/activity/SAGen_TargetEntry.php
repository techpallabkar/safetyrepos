
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
$id = $beanUi->get_view_data("id");

$financial_year_for_select = $beanUi->get_view_data("financial_year_for_select");
$financial_district_for_select = $beanUi->get_view_data("financial_district_for_select");
$financial_year = $beanUi->get_view_data("financial_year");
$type_images = array("image/jpg", "image/jpeg", "image/png");
$type_videos = array("video/mp4", "video/quicktime");
$pdf = array("application/pdf");
$controller->get_header();
$site_root_url = dirname(url());
$arr = array("#F2F2F2","#DDD9C3","#C6D9F1","#DCE6F2","#F2DCDB","#E6E0EC","#FDEADA","#FCD5B5");
?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
jQuery(function($) {
    $('.auto').autoNumeric('init');
});
</script>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">Annual GENERATION Site Audit (P+C) <?php echo $financial_year; ?></h1> 
    <div id="show_message"><?php echo $beanUi->get_message();   ?></div>
    <hr />
    
    <form action="" method="post" id="fyr" enctype="multipart/form-data">
        
        <label class="col-sm-2">Financial Year</label>
        <div class="col-sm-4">
            
        <select name="financial_year" id="financial_yearselect" class="form-control">
            <option value="">select</option>
            <?php
            foreach ($allFinancialYear as $key => $value) {
                echo '<option value="'.$value->id.'" '.(($value->id == @$_REQUEST["financial_year"]) ? "selected" : "").' >'.$value->financial_year.'</option>';
            }
            ?>
        </select>
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="Create" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="SAGen_TargetEntry.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr /> 
    <!--file upload-->
        <div>
            <form action="upload.php" method ="post" enctype="multipart/form-data">
                <table class="table">
                    <tbody>
                        <tr>
                            <td><input type="file" name="file_name">
                            <!--<td colspan="5" class="text-right">-->
                                <input name="save" type="submit" value="Save" class="btn btn-primary btn-sm">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            
        </div>
        <p></p>
        <!--file upload end-->
    <form action="" method="post" enctype="multipart/form-data">
        
    <?php if(!empty($getAllMonthofFinancialYear)) { ?>
        
        <table class="table table-bordered table-condensed table-responsive" style="background: #BDBDBD;">
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
    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <?php
                $i = 1;
                foreach ($allContractor as $key => $rowdata) {
                    if($array) {
                        echo '<tr class="totalCal" >' 
                            . '<td>'.($key+1).'</td>'                           
                            . '<td><b>' . $rowdata->name . count($getAllMonthofFinancialYear).'</b></td>';
                        foreach ($array as $m => $val) {
                            if($m == $rowdata->id) {
                                 $ptotal = 0;
                                foreach ($getAllMonthofFinancialYear as $k => $v) {
                                    if(!isset($array[$m][$k+1])) {
                                        echo '<td><input type="text" name="data['.($rowdata->id)."-". ($k + 1) .']" value="" style="width: 100%;" id="tot'.$k.$rowdata->id.$i.'"/>';
                                    } else {
                                        echo '<td><input type="text" name="data['.($rowdata->id)."-". ($k + 1) .']" value="'.$array[$m][$k+1].'" style="width: 100%;" id="tot'.$k.$rowdata->id.$i.'"/>';
                                    }
                                @$ptotal +=  $array[$m][$k+1];
                                    ?>
                        <script type="text/javascript">
                            $(document).ready(function () { 

                              $("#tot<?php echo $k.$rowdata->id.$i ?>").keydown( function () {
                                  
                                    var gworkmanp = (($('#tot<?php echo $k.$rowdata->id.$i ?>').val() == '') ? 0 : parseInt($('#tot<?php echo $k.$rowdata->id.$i ?>').val()));
                                    
                                    var gworkmanp1 = (($('#totval<?php echo $key ?>').val() == '') ? 0 : parseInt($('#totval<?php echo $key ?>').val()));
                                    
                                    var gtotalp =  gworkmanp1 - gworkmanp;
                                    
                                    $(this).parent('td').siblings('td').find('#totval<?php echo $key ?>').val(gtotalp);	

                              });
                              $("#tot<?php echo $k.$rowdata->id.$i ?>").keyup( function () {
                                  
                                    var gworkmanp = (($('#tot<?php echo $k.$rowdata->id.$i ?>').val() == '') ? 0 : parseInt($('#tot<?php echo $k.$rowdata->id.$i ?>').val()));
                                    
                                    var gworkmanp1 = (($('#totval<?php echo $key ?>').val() == '') ? 0 : parseInt($('#totval<?php echo $key ?>').val()));
                                    
                                    var gtotalp =  gworkmanp + gworkmanp1;
                                    
                                    $(this).parent('td').siblings('td').find('#totval<?php echo $key ?>').val(gtotalp);	

                              });

                            });
                        </script></td>
                <?php
                                $i++;
                                    }
                            }
                        }
                        echo '<td><input type="text" value="'.$ptotal.'" style="width: 100%;" id="totval'.$key.'"/></td>';
                        echo '</tr>';
                    } else {
                        echo '<tr class="totalCal" >' 
                                . '<td>'.($key+1).'</td>'                           
                                . '<td><b>' . $rowdata->name . '</b></td>';
                        foreach ($getAllMonthofFinancialYear as $k => $val) { 
                            echo '<td><input type="text" name="data['.($rowdata->id)."-". ($k + 1) .']" value="" style="width: 100%;" id="tot'.$k.$rowdata->id.$i.'"/>';
                         ?>
                        
                        <script type="text/javascript">
                            $(document).ready(function () { 
                              
                              $("#tot<?php echo $k.$rowdata->id.$i ?>").keydown( function () { 
                                  
                                    var gworkmanp = (($('#tot<?php echo $k.$rowdata->id.$i ?>').val() == '') ? 0 : parseInt($('#tot<?php echo $k.$rowdata->id.$i ?>').val()));
                                    
                                    var gworkmanp1 = (($('#totvalue<?php echo $key ?>').val() == '') ? 0 : parseInt($('#totvalue<?php echo $key ?>').val()));
                                    
                                    var gtotalp =  gworkmanp1 - gworkmanp;
                                    
                                    $(this).parent('td').siblings('td').find('#totvalue<?php echo $key ?>').val(gtotalp);	

                              });
                              $("#tot<?php echo $k.$rowdata->id.$i ?>").keyup( function () {
                                  
                                    var gworkmanp = (($('#tot<?php echo $k.$rowdata->id.$i ?>').val() == '') ? 0 : parseInt($('#tot<?php echo $k.$rowdata->id.$i ?>').val()));
                                    
                                    var gworkmanp1 = (($('#totvalue<?php echo $key ?>').val() == '') ? 0 : parseInt($('#totvalue<?php echo $key ?>').val()));
                                    
                                    var gtotalp =  gworkmanp + gworkmanp1;
                                    
                                    $(this).parent('td').siblings('td').find('#totvalue<?php echo $key ?>').val(gtotalp);	

                              });

                            });
                        </script></td>
                <?php
                        $i++;
                            }
                         echo '<td><input type="text" value="" style="width: 100%;" id="totvalue'.$key.'" readonly/></td>';
                        echo '</tr>';
                    }
                }                  
                ?>        
            </tbody>    
    </table>  
        <?php 
            if($array) { ?>
                <input type="hidden" name="_action" value="Update" />
                <button type="submit" name="submit" class="btn btn-primary btn-sm">Update</button> 
            <?php } else { ?>
                <input type="hidden" name="_action" value="Input" />
                <button type="submit" name="submit" class="btn btn-primary btn-sm">Submit</button> 
           <?php } 
        
             } 
                        
             ?>
         </form>
</div>
<?php $controller->get_footer(); ?>

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