<?php
/* MIS Report No. : 21
 * Name         :   Statistics for MRM (Generation)
 * Controller   :   suraksahBartaScoresStat()
 * Dao          :   getGenerationCount
 * Created By Anima Mahato
 */
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$month_year               =   $beanUi->get_view_data("month_year");
$month_of_year_back       =   $beanUi->get_view_data("month_of_year_back");
$month_of_year_back2      =   $beanUi->get_view_data("month_of_year_back2");
$fetchAllData             =   $beanUi->get_view_data("fetchAllData");
$getDate                  =   $beanUi->get_view_data("getDate");
$incident_cm              =   $beanUi->get_view_data("incident_cm");
$incident_ytm             =   $beanUi->get_view_data("incident_ytm");
$incident_prev            =   $beanUi->get_view_data("incident_prev");
$incident_category_id     =   $beanUi->get_view_data("incident_category_id");
$all_data_generation     =   $beanUi->get_view_data("all_data_generation");


$month_year_formatted       =   date("M y", strtotime("01-" . $month_year));
$back1_formatted            =   date("M y", strtotime($month_of_year_back."-01"));
$back2_formatted            =   date("M y", strtotime($month_of_year_back2."-01"));
$previous_financial_year    =   @$getDate["PREV_YTM"];
$exp_prev_fy_year           =   explode(",", $previous_financial_year);
@$prevfyyear                =   date("Y", strtotime($exp_prev_fy_year[0] . "-01")) . '-' . date("y", strtotime($exp_prev_fy_year[1] . "-01"));
@$previous_financial_year2  =   $getDate["PREV2_YTM"];
$exp_prev_fy_year2          =   explode(",", $previous_financial_year2);
@$prevfyyear2               =   date("Y", strtotime($exp_prev_fy_year2[0] . "-01")) . '-' . date("y", strtotime($exp_prev_fy_year2[1] . "-01"));
@$present_financial_year    =   $getDate["CURR_YTM"];
$exp_pres_fy_year           =   explode(",", $present_financial_year);
@$presfyyear                =   date("Y", strtotime($exp_pres_fy_year[0] . "-01")) . '-' . date("y", strtotime($exp_pres_fy_year[1] . "-01"));
$controller->get_header();
$site_root_url = dirname(url());

?>
<style>
 #parent {
        height: 570px;
    }
</style>
<script src="<?php echo url("assets/js/tableHeadFixer.js") ?>"></script>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
        $("#fixTable").tableHeadFixer({'foot': true, 'head': true});
    });
</script>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">21.Statistics for MRM (Generation)</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr/>
    <form action="" method="post" id="mdform" enctype="multipart/form-data" style="<?php echo ( $mode == "view" ) ? 'display:none' : 'display:block'; ?>" >
        <div class="holder">
            <label for="job_stop_req_raisedx" style="width:15%;padding-top:5px;">Select Month-Year :</label>
            <input required="true" type="text" name="month_year" id="month_year_from" class="month_year_picker" value ="<?php echo $month_year; ?>" style="width:250px;"  >
            <input type="hidden" name="_action" value="Create" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="statisticsforMRMGeneration.php" class="btn btn-danger btn-sm">Reset</a>                 
        </div>
        <hr class="no-margin"/>
    </form>
    <!--REPORTED ACCIDENT STATISTICS OF PERMANENT EMPLOYEES--> 
    <?php if (!empty($all_data_generation)) { ?>
    <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
        Export to Excel</button>
    <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
   
    <div id='TBL_EXPORT'>
        <div class="print-friendly" id="divToPrint">
            <div id="parent">
            <table id="fixTable" border="1" class="table table-bordered table-condensed table-responsive totaldata" style="font-size:13px;font-weight:bold;" >
                <thead class="bg-primary  fixedHeader">
                    <tr><th colspan="20">Statics for MRM (Generation) : <?php echo $month_year_formatted; ?></th></tr>
                    <tr>
                        <th width="6%" rowspan="4">SL.No</th>
                        <th width="20%" rowspan="4">Item</th>
                        <th colspan="6">FY <?php echo $presfyyear; ?></th>
                        <th colspan="6">FY <?php echo $prevfyyear; ?></th>
                        <th colspan="6">FY <?php echo $prevfyyear2;?></th>
                    </tr>
                    <tr>
                        
                        <th colspan="6"><?php echo $month_year_formatted; ?></th>
                        <th colspan="6"><?php echo $back1_formatted; ?></th>
                        <th colspan="6"><?php echo $back2_formatted; ?></th>
                    </tr>
                    <tr>
                        <th colspan="2">BBGS</th>
                        <th colspan="2">SGS</th>
                        <th rowspan="2">TOTAL NO OF ACTIVITY</th>
                        <th rowspan="2">TOTAL YTM</th>
                        <th colspan="2">BBGS</th>
                        <th colspan="2">SGS</th>
                        <th rowspan="2">TOTAL NO OF ACTIVITY</th>
                        <th rowspan="2">TOTAL YTM</th>
                        <th colspan="2">BBGS</th>
                        <th colspan="2">SGS</th>
                        <th rowspan="2">TOTAL NO OF ACTIVITY</th>
                        <th rowspan="2">TOTAL YTM</th>
                    </tr>
                    <tr>
                        <th>NO OF ACTIVITY</th>
                        <th>YTM</th>
                        <th>NO OF ACTIVITY</th>
                        <th>YTM</th>
                        <th>NO OF ACTIVITY</th>
                        <th>YTM</th>
                        <th>NO OF ACTIVITY</th>
                        <th>YTM</th>
                        <th>NO OF ACTIVITY</th>
                        <th>YTM</th>
                        <th>NO OF ACTIVITY</th>
                        <th>YTM</th>
                    </tr>
                </thead>
                <tbody class="scrollContent">
                    <?php
                    if(!empty($all_data_generation)) { 
                        foreach ($all_data_generation as $key => $value) {
                            echo '<tr>
                                <td style="border:1px solid #000;" align="center" bgcolor="#f2f2f2">'.($key).'.</td>
                                <td style="border:1px solid #000;" bgcolor="#CCFFFF">'.($value["NAME"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#f0ad4e">'.($value["CURRENT_FY"]["BBGS"]["CURRENT"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#e3ff96">'.($value["CURRENT_FY"]["BBGS"]["YTM"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#f0ad4e">'.($value["CURRENT_FY"]["SGS"]["CURRENT"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#e3ff96">'.($value["CURRENT_FY"]["SGS"]["YTM"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#FFCC99">'.($value["CURRENT_FY"]["BBGS"]["CURRENT"]+$value["CURRENT_FY"]["SGS"]["CURRENT"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#b3ccff">'.($value["CURRENT_FY"]["BBGS"]["YTM"]+$value["CURRENT_FY"]["SGS"]["YTM"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#f0ad4e">'.($value["PREV1_FY"]["BBGS"]["CURRENT"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#e3ff96">'.($value["PREV1_FY"]["BBGS"]["YTM"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#f0ad4e">'.($value["PREV1_FY"]["SGS"]["CURRENT"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#e3ff96">'.($value["PREV1_FY"]["SGS"]["YTM"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#FFCC99">'.($value["PREV1_FY"]["BBGS"]["CURRENT"]+$value["PREV1_FY"]["SGS"]["CURRENT"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#b3ccff">'.($value["PREV1_FY"]["BBGS"]["YTM"]+$value["PREV1_FY"]["SGS"]["YTM"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#f0ad4e">'.($value["PREV2_FY"]["BBGS"]["CURRENT"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#e3ff96">'.($value["PREV2_FY"]["BBGS"]["YTM"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#f0ad4e">'.($value["PREV2_FY"]["SGS"]["CURRENT"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#e3ff96">'.($value["PREV2_FY"]["SGS"]["YTM"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#FFCC99">'.($value["PREV2_FY"]["BBGS"]["CURRENT"]+$value["PREV2_FY"]["SGS"]["CURRENT"]).'</td>
                                <td style="border:1px solid #000;" align="center" bgcolor="#b3ccff">'.($value["PREV2_FY"]["BBGS"]["YTM"]+$value["PREV2_FY"]["SGS"]["YTM"]).'</td>
                            </tr>';
                            
                        }
                    }
                    ?>
                </tbody>
            </table>
            </div>
                <div style="clear:both;" class="clearfix" ></div>
            <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
    </div>
    </div>
     <?php } ?>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>


<script type="text/javascript" src="<?php echo url('assets/js/ExportExcel/jquery.table2excel.js') ?>"></script>
<link rel="stylesheet" href="<?php echo url("assets/css/jqueryui/jquery-ui.css") ?>">
<script src="<?php echo url("assets/js/jqueryui/jquery.mtz.monthpicker.js") ?>"></script>

<script type="text/javascript">
    $('.month_year_picker').monthpicker({pattern: 'mm-yyyy',
        startYear: 2015,
        finalYear: 2025, });
    var options = {
        startYear: 2010,
        finalYear: 2018,
        openOnFocus: false
    };
  
    
    function get_total_value(type,incid) {
            var current_month = 0;
            var prev_ytm = 0;
            var current_ytm = 0;
            $('.'+type+'CMNTH_' +incid).each(function () {
                var c1val = $(this).text()
                current_month += parseInt(c1val);
            });
            $('.'+type+'PREVYTM_' +incid).each(function () {
                var c1val = $(this).text()
                prev_ytm += parseInt(c1val);
            });
            $('.'+type+'CURYTM_' +incid).each(function () {
                var c1val = $(this).text()
                current_ytm += parseInt(c1val);
            });


            $("#"+type+"CMNTH_" +incid).text(current_month);
            $("#"+type+"PREVYTM_" +incid).text(prev_ytm);
            $("#"+type+"CURYTM_" +incid).text(current_ytm);
    }
    $(document).ready(function () {
<?php if(!empty($incident_category)) {
    foreach ($incident_category as $key => $value) { ?>
        
        get_total_value('PSET',<?php echo $value->id; ?>);
        get_total_value('CSET',<?php echo $value->id; ?>);
        get_total_value('PCSET',<?php echo $value->id; ?>);
        get_total_value('OTHERS',<?php echo $value->id; ?>);
<?php } } ?>
        //Export to excel
        $("#ExportExcel").click(function (e) {
            var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html());
            window.open(path);
            e.preventDefault();
        });

    });

    function PrintDiv()
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=300');
    var content = document.getElementById("divToPrint").innerHTML;
    mywindow.document.write('<html><head><title></title>');
    mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}</style></head><body>');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.print();
    mywindow.close();
    return true;
}
 </script>