<?php
/* MIS Report No. : 28
 * Name         :   Suraksah Barta Incident
 * Controller   :   surakshabarta_incident()
 * Dao          :   division_child_unit_count,getPPEAuditData,getSITEAuditData,getSiteAuditCount
 * Created By pallab kar
 */
if (file_exists("../../lib/var.inc.php"))
require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$incidentStatics            =   $beanUi->get_view_data("incidentStatics");
$getDate                    =   $beanUi->get_view_data("getDate");
$current_fy_month_arr       =   $beanUi->get_view_data("current_fy_month_arr");
$current_fy_year            =   $beanUi->get_view_data("current_fy_year");//show($current_fy_year);
$incident_category_id       =   $beanUi->get_view_data("incident_category_id");
$controller->get_header();
$site_root_url = dirname(url());
//show($current_fy_month_arr);
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
        $("#fixTable").tableHeadFixer({'foot': false, 'head': true});
    });
</script>

<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">28. UNIT WISE MONTH WISE INCIDENT HISTORY
</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <form action="" method="post" id="fyr" enctype="multipart/form-data">
        <div class="col-sm-6">
            <div class="col-sm-6">
            <?php
            $backyear = _BACK_YEAR;
                                         
                $current_month = date("m");
                if($current_month < 4) {
                    $current_year = date("Y");
                } else {
                    $current_year = date("Y")+1;
                }

                $count =  $current_year-$backyear;
                echo '<select required="true" class="form-control" name="selected_year" id="selected_year">';

                for($i=0; $i<=$count;$i++)
                {   
                    $year = ($current_year-1).'-'.$current_year;
                    $selected = ($year == @$_REQUEST["selected_year"]) ? 'selected' : '';
                        echo '<option ' . $selected . '>'.($current_year-1).'-'.$current_year.'</option>';
                        $current_year--;
                }
                echo '</select>'; 
            ?>
            </div>
            
        </div>
        <div class="col-sm-2">
            <input type="hidden" name="_action" value="submitData" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="district_wise_month_wise_accident_statistics.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr/>
    <div class="container"  style="min-height:200px;">
    <?php if(!empty($_POST)) { ?>  
 <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
 Export to Excel</button>
    <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
        <div id='TBL_EXPORT'>
        <div class="print-friendly" id="divToPrint">  
            <div id="parent">    
        <table id="fixTable" border="1" class="table table-bordered table-condensed scrollTable totaldata" style="font-size:13px;" >
                <thead class="bg-primary fixedHeader">
                    <tr>
                        <th  colspan="16"> UNIT  WISE MONTH WISE INCIDENT HISTORY</th>
                    </tr>
                    <tr class="bg-primary">
                        <th width="5%">SL.NO</th>
                        <th width="10%">DIST</th>
                 
                        <?php if(!empty($current_fy_month_arr)) {
                            foreach( $current_fy_month_arr as $kcol => $kval ) {
                                $cuutrntmonth = date("m/y",strtotime($kval));
                                echo '<th>'.$cuutrntmonth.'</th>';
                            }
                        }
                        ?>
                        <th> TOTAL YTM CURR YEAR </th>
                        <th> TOTAL YTM PREV YEAR </th>
                    </tr>
                </thead>
                <tbody class="scrollContent" style="font-size:10px;">
                    <?php
                    $color_array = array( 1 => "#eafaff", 2 => "#f1ffbc", 3 => "#fff1cc", 4 => "orange");
                    if(!empty($incidentStatics)) {
                        foreach ($incidentStatics as $key => $value) {
                            $apr = $value['APR'];
                            $may = $value['MAY'];
                            $jun = $value['JUN'];
                            $jul = $value['JUL'];
                            $aug = $value['AUG'];
                            $sep = $value['SEP'];
                            $oct = $value['OCT'];
                            $nov = $value['NOV'];
                            $dec = $value['DEC'];
                            $jan = $value['JAN'];
                            $feb = $value['FEB'];
                            $mar = $value['MAR'];
                            $YTM_CURR_YEAR = $value['YTM_CURR_YEAR'];
                            $YTM_PREV_YEAR = $value['YTM_PREV_YEAR'];
                            echo '<tr>'
                            . '<td style="background-color:#f2eded;">'.($key+1).'</td>'
                            . '<td style="background-color:#f2eded;">'.($value['DISTRICT']).'</td>';
                            //APR Month
                            echo '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($apr as $k4 => $v4) {
                                echo '<tr bgcolor="'.$color_array[$k4].'">'
                                . '<td>'.($v4['NAME']).'</td>'
                                . '<td>'.($v4['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }   
                            echo '</table></td>';
                            
                            //MAY Month
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($may as $k5 => $v5) {
                                echo '<tr bgcolor="'.$color_array[$k5].'">'
                                . '<td>'.($v5['NAME']).'</td>'
                                . '<td>'.($v5['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                           
                            //JUN Month
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($jun as $k6 => $v6) {
                                echo '<tr bgcolor="'.$color_array[$k6].'">'
                                . '<td>'.($v6['NAME']).'</td>'
                                . '<td>'.($v6['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            //JUL Month
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($jul as $k7 => $v7) {
                                echo '<tr bgcolor="'.$color_array[$k7].'">'
                                . '<td>'.($v7['NAME']).'</td>'
                                . '<td>'.($v7['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            //AUG Month
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($aug as $k8 => $v8) {
                                echo '<tr bgcolor="'.$color_array[$k8].'">'
                                . '<td>'.($v8['NAME']).'</td>'
                                . '<td>'.($v8['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            //SEP Month
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($sep as $k9 => $v9) {
                                echo '<tr bgcolor="'.$color_array[$k9].'">'
                                . '<td>'.($v9['NAME']).'</td>'
                                . '<td>'.($v9['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            //OCT Month
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($oct as $k10 => $v10) {
                                echo '<tr bgcolor="'.$color_array[$k10].'">'
                                . '<td>'.($v10['NAME']).'</td>'
                                . '<td>'.($v10['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            //NOV Month
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($nov as $k11 => $v11) {
                                echo '<tr bgcolor="'.$color_array[$k11].'">'
                                . '<td>'.($v11['NAME']).'</td>'
                                . '<td>'.($v11['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            //DEC Month
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($dec as $k12 => $v12) {
                                echo '<tr bgcolor="'.$color_array[$k12].'">'
                                . '<td>'.($v12['NAME']).'</td>'
                                . '<td>'.($v12['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            //JAN Month
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($jan as $k1 => $v1) {
                                echo '<tr bgcolor="'.$color_array[$k1].'">'
                                . '<td>'.($v1['NAME']).'</td>'
                                . '<td>'.($v1['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            //FEB Month
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($feb as $k2 => $v2) {
                                echo '<tr bgcolor="'.$color_array[$k2].'">'
                                . '<td>'.($v2['NAME']).'</td>'
                                . '<td>'.($v2['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            //MAR Month
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($mar as $k3 => $v3) {
                                echo '<tr bgcolor="'.$color_array[$k3].'">'
                                . '<td>'.($v3['NAME']).'</td>'
                                . '<td>'.($v3['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            //TOTAL YTM CURR YEAR
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($YTM_CURR_YEAR as $kcy => $vcy) {
                                echo '<tr bgcolor="'.$color_array[$kcy].'">'
                                . '<td>'.($vcy['NAME']).'</td>'
                                . '<td>'.($vcy['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            //TOTAL YTM PREV YEAR
                            echo  '<td><table class="table table-bordered table-stripped" style="margin-bottom:0px">';
                            foreach ($YTM_PREV_YEAR as $kpy => $vpy) {
                                echo '<tr bgcolor="'.$color_array[$kpy].'">'
                                . '<td>'.($vpy['NAME']).'</td>'
                                . '<td>'.($vpy['TOT_COUNT']).'</td>'
                                . '</tr>';
                            }
                            echo '</table></td>';
                            
                            
                                   echo '</tr>';
                        }
                    }
                    ?>
                   
                </tbody>
            </table>
            
  
        
      
       
        </div>
              <div class="print1" style="float: left; border:1px solid #000; padding:3px 8px;">
            NA : NOT APPLICABLE
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

$(document).ready(function(){
    $("#ExportExcel").click(function(e){
        var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html()); 
        window.open(path);
        e.preventDefault();
    });
});
    function PrintDiv()
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=300');
    var content = document.getElementById("divToPrint").innerHTML;
    mywindow.document.write('<html><head><title>SURAKSHA BARTA SCORES STAT</title>');
    mywindow.document.write('<style>@page{filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {padding:10px 2px; background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}body{margin:0;padding:0;}.table-siteAudit{margin-top:220px;}</style></head><body>');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.print();
    mywindow.close();
    return true;
}
 </script>

