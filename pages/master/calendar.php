<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$presCtr = load_controller("CalendarController");
$presCtr->doAction();
$beanUi = $presCtr->beanUi;
$allFinancialYear    = $beanUi->get_view_data('allFinancialYear');
$allSafetycellActivity    = $beanUi->get_view_data('allSafetycellActivity');
$alldatacalender    = $beanUi->get_view_data('alldatacalender');
$getAllMonthofFinancialYear    = $beanUi->get_view_data('getAllMonthofFinancialYear');
$financial_year    = $beanUi->get_view_data('financial_year');

$presCtr->get_header();
?>

<div class="mh-wrapper mh-home clearfix">
    <div class="topic-heading"><h3>Calendar</h3></div>

    <?php echo $beanUi->get_message(); ?>
    <div class="col-md-12">

        <form action="" method="post" id="fyr" enctype="multipart/form-data">

         
            <div class="col-sm-4">

                <select name="financial_year" id="financial_yearselect" class="form-control" style="height:30px;background: none;border:1px solid #d0d0d0;width:200px; float:right;">
                    <option value="">Select Financial Year</option>
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
        <div class="clearfix"></div>
        <hr /> 
        
            <?php if(!empty($getAllMonthofFinancialYear)) { ?>
                 <table class="table table-bordered table-condensed table-responsive">
                    
                <thead>
                    <tr><td colspan="13"><center><h4>ANNUAL CALENDAR FOR MAJOR ACTIVITIES IN FY : <?php echo @$financial_year; ?></h4></center></td></tr>
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
                if(empty($alldatacalender))
                {
                $alldatacalender = array();
                }
                foreach ($allSafetycellActivity as $key => $rowdata) {
                 if($key % 2 == 0 )
                 {
                     $color = "#AEAAAA"; 
                     $color1 = "#D0CECE";
                 }
                 else
                 {
                     $color = "#BDD7EE";
                     $color1="#DDECF8";
                 }
                
                        echo '<tr class="totalCal' . $rowdata->id . '" >'
                        . '<td bgcolor="'.$color.'"><b>' . $rowdata->name . '</b></td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->april_month.'</td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->may_month . '</td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->june_month . '</td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->july_month . '</td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->august_month . '</td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->september_month . '</td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->october_month . '</td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->november_month . '</td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->december_month . '</td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->january_month . '</td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->february_month . '</td>'
                        . '<td bgcolor="'.$color1.'">' . @$alldatacalender[$key]->march_month . '</td>'                       
                        . '</tr>';
                    
                
                }
                ?>

                </tbody>
                

            </table>
            <?php
            } ?>
        
    </div>





    <?php $presCtr->get_footer(); ?>


    <script type="text/javascript">

        $(document).ready(function () {


            $("#financial_yearselect").change(function () {
                this.form.submit();
            });
            
//            var fyid = "<?php //echo $financial_year_for_select; ?>"
//
//            if (fyid) {
//                $("#financial_yearselect").val(fyid);
//
//                $("form#fyr").append('<input type="hidden" value="fromsubmit" name="fromsubmit">');
//                $("form#fyr").submit();
//
//            }


        });

    </script>

</body>
</html>
