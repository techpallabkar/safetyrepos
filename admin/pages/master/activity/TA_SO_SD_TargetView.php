
<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ReportController");
$controller->doAction();
$beanUi = $controller->beanUi;
$allFinancialYear = $beanUi->get_view_data("allFinancialYear");
$allFinancialYearById = $beanUi->get_view_data("allFinancialYearById");
$getAllMonthofFinancialYear = $beanUi->get_view_data("getAllMonthofFinancialYear");
$annual_target = $beanUi->get_view_data("annual_target");
$financial_year = $beanUi->get_view_data("financial_year");
$controller->get_header();
$site_root_url = dirname(url());
?>
<style>
    .table tr td {vertical-align: middle !important;}
</style>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
</script>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">Annual Training,Safety Observation By Line Function,Safety Observation Day and Safety Workshop Schedule of Safety Cell for <?php echo $allFinancialYearById[$financial_year] ?></h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr />

    <form action="" method="post" id="fyr" enctype="multipart/form-data">

        <label class="col-sm-2">Financial Year</label>
        <div class="col-sm-4">

            <select name="financial_year" id="financial_yearselect" class="form-control" required="required">
                <option value="">select</option>
                <?php
                $fyr = (isset($_REQUEST["financial_year"]) ? $_REQUEST["financial_year"] : $financial_year);
                foreach ($allFinancialYear as $key => $value) {
                    echo '<option value="' . $value->id . '" ' . (($value->id == $fyr) ? "selected" : "") . ' >' . $value->financial_year . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="Search" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="TA_SO_SD_TargetEntry.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr /> 
<?php if($financial_year){ ?>
    <form action="" method="post" enctype="multipart/form-data">
        <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px !important; width:100%; border: 1px solid #000 !important;" >
            <thead class="bg-primary">
                <tr>
                    <th rowspan="3" bgcolor="#B3A2C7">
                        <b>MONTH / YEAR</b>
                    </th>
                    <th rowspan="3">
                        <b>STATION</b>
                    </th>
                    <th colspan="7">
                        <b>TRAINING</b>
                    </th>
                    <th rowspan="3">
                        <b>SAFETY OBSERVATION BY LINE FUNCTION</b>
                    </th>
                    <th rowspan="3">
                        <b>SAFETY OBSERVATION DAY</b>
                    </th>
                    <th rowspan="3">
                        <b>SAFETY WORKSHOP</b>
                    </th>
                </tr>
                <tr>
                    <th rowspan="2" colspan="1">OFFICERS</th>
                    <th colspan="3"> PERMANENT </th>
                    <th colspan="3"> CONTRACTUAL </th>
                </tr>

                <tr>

                    <th>WORKMAN</th>
                    <th>SUPERVISOR</th>
                    <th>TOTAL</th>

                    <th>WORKMAN</th>
                    <th>SUPERVISOR</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $gen = 0; $dist = 1;
                foreach ($getAllMonthofFinancialYear as $key => $rowdata) {
                    
                ?>
                
                <tr bgcolor="#cec8c7">
                    <td rowspan="2" bgcolor="#D99694">
                        <?php echo date("M y", strtotime($rowdata)) ?>
                        <input type="hidden" name="activity_type_id[]" value="<?php echo ($key+1) ?>" />
                    </td>
                    <td style="text-align: center;">GEN.</td>
                    <td style="width: 10%; text-align: center;">
                        <?php if(!empty($annual_target)){ ?>
                        <input type="hidden" name="growid<?php echo $key ?>" value="<?php echo $annual_target[$gen]->id ?>" />
                        <?php } ?>
                        <input type="hidden" name="gstation<?php echo $key ?>" value="G" style="width: 100%;"/>
                        <?php echo $annual_target[$gen]->officer ?></td>
                    <td style="width: 10%; text-align: center;"><?php echo $annual_target[$gen]->pr_workman ?></td>
                    <td style="width: 10%; text-align: center;"><?php echo $annual_target[$gen]->pr_supervisor ?></td>
                    <td style="width: 10%; text-align: center;"><?php echo $annual_target[$gen]->pr_total ?></td>
                    <td style="width: 10%; text-align: center;"><?php echo $annual_target[$gen]->co_workman ?></td>
                    <td style="width: 10%; text-align: center;"><?php echo $annual_target[$gen]->co_supervisor ?></td>
                    <td style="width: 10%; text-align: center;"><?php echo $annual_target[$gen]->co_total ?></td>
                    <td style="text-align: center;"><?php echo $annual_target[$gen]->div_target_lf ?></td>
                    <td style="text-align: center;"><?php echo $annual_target[$gen]->div_target_day ?></td>
                    <td style="text-align: center;"><?php echo $annual_target[$gen]->div_target_work ?></td>
                </tr>
                <tr style="background: #ffef58;">
                    <td style="text-align: center;">DIST.</td>
                    <td style="text-align: center;">
                        <?php if(!empty($annual_target)){ ?>
                        <input type="hidden" name="drowid<?php echo $key ?>" value="<?php echo $annual_target[$dist]->id ?>" />
                        <?php } ?>
                        <input type="hidden" name="dstation<?php echo $key ?>" value="D" style="width: 100%;"/>
                        <?php echo $annual_target[$dist]->officer ?>
                    </td>
                    <td style="text-align: center;"><?php echo $annual_target[$dist]->pr_workman ?></td>
                    <td style="text-align: center;"><?php echo $annual_target[$dist]->pr_supervisor ?></td>
                    <td style="text-align: center;"><?php echo $annual_target[$dist]->pr_total ?></td>
                    <td style="text-align: center;"><?php echo $annual_target[$dist]->co_workman ?></td>
                    <td style="text-align: center;"><?php echo $annual_target[$dist]->co_supervisor ?></td>
                    <td style="text-align: center;"><?php echo $annual_target[$dist]->co_total ?></td>
                    <td style="text-align: center;"><?php echo $annual_target[$dist]->div_target_lf ?></td>
                    <td style="text-align: center;"><?php echo $annual_target[$dist]->div_target_day ?></td>
                    <td style="text-align: center;"><?php echo $annual_target[$dist]->div_target_work ?></td>
                </tr>
                
                <script type="text/javascript">
                    $(document).ready(function () {

                      $("#gworkmanp<?php echo $key ?>, #gsupervisorp<?php echo $key ?>").keyup( function () {

                            var gworkmanp = (($('#gworkmanp<?php echo $key ?>').val() == '') ? 0 : parseInt($('#gworkmanp<?php echo $key ?>').val()));
                            var gsupervisorp = (($('#gsupervisorp<?php echo $key ?>').val() == '') ? 0 : parseInt($('#gsupervisorp<?php echo $key ?>').val()));

                            var gtotalp = gworkmanp+gsupervisorp;

                            $('#gtotalp<?php echo $key ?>').val(gtotalp);

                      });

                      $("#gworkmanc<?php echo $key ?>, #gsupervisorc<?php echo $key ?>").keyup( function () {

                            var gworkmanc = (($('#gworkmanc<?php echo $key ?>').val() == '') ? 0 : parseInt($('#gworkmanc<?php echo $key ?>').val()));
                            var gsupervisorc = (($('#gsupervisorc<?php echo $key ?>').val() == '') ? 0 : parseInt($('#gsupervisorc<?php echo $key ?>').val()));

                            var gtotalc = gworkmanc+gsupervisorc;

                            $('#gtotalc<?php echo $key ?>').val(gtotalc);

                      });

                      $("#dworkmanp<?php echo $key ?>, #dsupervisorp<?php echo $key ?>").keyup( function () {

                            var dworkmanp = (($('#dworkmanp<?php echo $key ?>').val() == '') ? 0 : parseInt($('#dworkmanp<?php echo $key ?>').val()));
                            var dsupervisorp = (($('#dsupervisorp<?php echo $key ?>').val() == '') ? 0 : parseInt($('#dsupervisorp<?php echo $key ?>').val()));

                            var dtotalp = dworkmanp+dsupervisorp;

                            $('#dtotalp<?php echo $key ?>').val(dtotalp);

                      });

                      $("#dworkmanc<?php echo $key ?>, #dsupervisorc<?php echo $key ?>").keyup( function () {

                            var dworkmanc = (($('#dworkmanc<?php echo $key ?>').val() == '') ? 0 : parseInt($('#dworkmanc<?php echo $key ?>').val()));
                            var dsupervisorc = (($('#dsupervisorc<?php echo $key ?>').val() == '') ? 0 : parseInt($('#dsupervisorc<?php echo $key ?>').val()));

                            var dtotalc = dworkmanc+dsupervisorc;

                            $('#dtotalc<?php echo $key ?>').val(dtotalc);

                      });

                    });
                </script>
                
                <?php 
                
                $gen = $gen + 2; 
                $dist = $dist + 2;
                
                }
                
                ?> 
                
<!--                <tr>
                    <td colspan="11" class="text-center">
                        <input type="hidden" id="fyear" name="fyear" value="<?php //echo (($annual_target[0]->financial_year_id != '') ? $annual_target[0]->financial_year_id : $financial_year) ?>" style="width: 100%;"/>
                        <?php //if(!empty($annual_target)){ ?>
                        <input type="hidden" name="_action" value="Update" />
                        <button type="submit" name="B2" class="btn btn-primary btn-sm">Update</button>
                        <?php //}else{ ?>
                        <input type="hidden" name="_action" value="Save" />
                        <button type="submit" name="B2" class="btn btn-primary btn-sm">Save</button>
                        <?php //} ?>
                    </td>
                </tr>-->
                   
            </tbody>


        </table>
    </form>
<?php } ?>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>
<script type="text/javascript">
    $(document).ready(function () {
        
      $("#financial_yearselect").change( function () {
            var fy = $('#financial_yearselect').find(":selected").val();
            $("#fyear").val(fy);
      });
      
        
    });
</script>    

