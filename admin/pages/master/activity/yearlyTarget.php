
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
$allUsers = $beanUi->get_view_data("allUsers");
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
//$alldatadist = $beanUi->get_view_data("alldatadist");
$financial_year_for_select = $beanUi->get_view_data("financial_year_for_select");
$financial_district_for_select = $beanUi->get_view_data("financial_district_for_select");
$financial_year = ($beanUi->get_view_data("financial_year") ? $beanUi->get_view_data("financial_year") :"" );
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

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">YEARLY TARGET REPORT</h1> 
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
         <label class="col-sm-2">Select District</label>
        <div class="col-sm-4">
        <select name="financial_district" id="financial_district" class="form-control">
                <option value="">select</option>
                <?php
                foreach ($allDistrict as $key => $value) {
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
    <hr /> <?php //echo "<pre>";print_r($alldatagen); ?>
    
    <form action="" method="post" enctype="multipart/form-data">
    <?php if(!empty($getAllMonthofFinancialYear)) { ?>
    <table class="table table-bordered table-condensed table-responsive">
        <thead>
        <tr>
             <th  width="25%" style="vertical-align:middle;" bgcolor="#B3A2C7">TARGET</th>
            <?php
            foreach($getAllMonthofFinancialYear as $rowdata)
            {
                echo '<th bgcolor="#D99694">'.date("M y",strtotime($rowdata)).'</th>';
            }
            ?>
             <th bgcolor="#D99694">TOTAL</th>
             <!--<th bgcolor="#D99694">TOTAL</th>-->
        </tr>
    </thead>
    <input type="hidden" name="financial_year_id" value="<?php echo @$financial_year_id; ?>" />
    <input type="hidden" name="financial_district_id" value="<?php echo @$financial_district_id; ?>" />
                <?php
                foreach ($allActivity as $key => $rowdata) {
                    if($rowdata->id!=9 && $rowdata->id!=6 && $rowdata->id!=10) {
                        echo '<input type="hidden" name="activity_type_id[]" value="'.$rowdata->id.'" />';
                    echo '<tr class="totalCal" bgcolor="'.$arr[$key].'">'
                            
                            . '<td><b>' . $rowdata->activity_name . '</b></td>'
                             . '<td>'.$alldatagen[$rowdata->id]["april_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["may_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["june_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["july_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["august_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["september_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["october_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["november_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["december_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["january_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["february_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["march_month"].'</td>'
                            . '<td>'.$alldatagen[$rowdata->id]["total_calculation"].'</td>'
                            . '</tr>';
                    }
                }
                   
                ?>
        
            </tbody>
           
        
    </table>
       
    <?php } ?>
         </form>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>
<script type="text/javascript">
$(document).ready(function(){

//   $("#financial_yearselect").change(function(){
//      this.form.submit();
//   }); 
   var fyid = "<?php echo $financial_year_for_select ;?>"
   var fydid = "<?php echo $financial_district_for_select ;?>"
   if(fyid){
       $("#financial_yearselect").val(fyid);
       $("#financial_district").val(fydid);
       $("form#fyr").append('<input type="hidden" value="fromsubmit" name="fromsubmit">');
       $("form#fyr").submit();
      
   }
});

</script>