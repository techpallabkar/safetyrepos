<?php
/* MIS Report No. : 26
 * Name         :   Suraksah Barta Site Audit
 * Controller   :   suraksahBartaScoresStat()
 * Dao          :   division_child_unit_count,getPPEAuditData,getSITEAuditData,getSiteAuditCount
 * Created By Anima Mahato
 */
if (file_exists("../../lib/var.inc.php"))
require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$arr_pset_data = $beanUi->get_view_data("arr_pset_data");
$arr_cset_data = $beanUi->get_view_data("arr_cset_data");
$getDate = $beanUi->get_view_data("getDate");
$controller->get_header();
$site_root_url = dirname(url());
$QUARTER = array("1" => "FIRST QUARTER",
                 "2" => "SECOND QUARTER",
                 "3" => "THIRD QUARTER",
                 "4" => "FOURTH QUARTER"
               );
?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
</script>
<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">26.  SURAKSHA BARTA -SITE AUDIT
</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
      <form action="" method="post" id="fyr" enctype="multipart/form-data">
        <div class="col-sm-6">
            <div class="col-sm-6">
            <?php
            $startyear = '2015';
            $current_year='2018';
            $yearArray = range(2015, $current_year);
             echo '<select name="selected_year" id="selected_year">
                    <option value="">--Select Year--</option>';
                  
                    foreach ($yearArray as $year) {

                        $selected = ($year == @$_REQUEST["selected_year"]) ? 'selected' : '';
                       
                           echo '<option ' . $selected . ' value="' . $year . '">' . $year . '</option>';
                        
                      
                    }
                 
                echo '</select>';
            ?></div>
            
            <div class="col-sm-6">
            <select name="quarter_value" id="quarter_value" value="<?php echo @$_REQUEST["month_year_from"]; ?>">
            <option value="">--Select Quarter--</option>
            <option value="1" <?php echo ((@$_REQUEST["quarter_value"] == 1) ? "selected" : ""); ?>>First Quarter</option>
            <option value="2" <?php echo ((@$_REQUEST["quarter_value"] == 2) ? "selected" : ""); ?>>Second Quarter</option>
            <option value="3" <?php echo ((@$_REQUEST["quarter_value"] == 3) ? "selected" : ""); ?>>Third Quarter</option>
            <option value="4" <?php echo ((@$_REQUEST["quarter_value"] == 4) ? "selected" : ""); ?>>Fourth Quarter</option>
            </select>
            </div>
        </div>
        <div class="col-sm-2">
            <input type="hidden" name="_action" value="submitData" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="surakshabarta_siteaudit.php" class="btn btn-danger btn-sm">Reset</a>
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
        <div>
        <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;" >
                <thead class="bg-primary">
                    <tr>
                        <th  colspan="7">PERMANENT SET - 
                            <?php
                            $QUA = @$_REQUEST["quarter_value"];
                            echo $QUARTER[$QUA]; 
                            ?>
                            SITE SAFETY AUDIT SCORE</th>
                    </tr>
                    <tr class="bg-primary">
                        <th rowspan="3" width="5%">SL.NO</th>
                        <th rowspan="3" width="20%">DEPT / DIST</th>
                    </tr>
                   
                    <tr>
                        <th rowspan="2" width="10%">NO. OF SET / UNIT</th>
                        <th colspan="2">
                            <?php
                            $QUA = @$_REQUEST["quarter_value"];
                            echo $QUARTER[$QUA].'&nbsp;&nbsp;&nbsp;'; 
                            echo @$getDate["PREV_Y"];
                        ?>
                        </th>
                        <th colspan="2">
                            <?php
                            $QUA = @$_REQUEST["quarter_value"];
                            echo $QUARTER[$QUA].'&nbsp;&nbsp;&nbsp;'; 
                            echo @$getDate["CURR_Y"];
                        ?>
                        </th>
                       
                       
                    </tr>
                    <tr>
                        <th width="10%"> AUDIT COUNT </th>
                        <th width="10%"> % AVG </th>
                        <th width="10%"> AUDIT COUNT </th>
                        <th width="10%"> % AVG </th>
                       
                    
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(!empty($arr_pset_data)) {
                        $slno =0;
                        $tot_pset_unit = $tot_pset_prev_count = $tot_pset_current_count = $tot_pset_prev_score = $tot_pset_current_score =0;
                        foreach( $arr_pset_data as $key => $value ) {
                            $slno++;
                            $psetprevcount = $value["pset_prev"]["totaldiv"];
                            $psetcurrentcount = $value["pset_current"]["totaldiv"];
                            $psetprevscore = round(($value["pset_prev"]["score"]),2);
                            $psetcurrentscore = round(($value["pset_current"]["score"]),2);
                            $pset_unit = $value["pset_unit"];
                            echo '<tr>
                        <td bgcolor="#07efcc" style="border-color:#000;" align="center">'.($slno).'.</td>
                        <td bgcolor="#f7efef" style="border-color:#000;" align="left">'.$value["district"]["name"].'</td>
                        <td bgcolor="#ffaa80" style="border-color:#000;" align="center">'.$pset_unit.'</td>
                        <td bgcolor="#ffccff" style="border-color:#000;" align="center">'.$psetprevcount.'</td>
                        <td bgcolor="#DBEEF4" style="border-color:#000;" align="center">'.$psetprevscore.'</td>
                        <td bgcolor="#ffccff" style="border-color:#000;" align="center">'.$psetcurrentcount.'</td>
                        <td bgcolor="#DBEEF4" style="border-color:#000;" align="center">'.$psetcurrentscore.'</td>
                    </tr>';
                            $tot_pset_unit +=$pset_unit;
                            $tot_pset_prev_count +=$psetprevcount;
                            $tot_pset_current_count +=$psetcurrentcount;
                            
                            $tot_pset_prev_score +=$psetprevscore;
                            $tot_pset_current_score +=$psetcurrentscore;
                        }
                        echo '<tr>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center" colspan="2">TOTAL</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_pset_unit).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_pset_prev_count).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center"></td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_pset_current_count).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center"></td>'
                                . '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            
            
            <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;" >
                <thead class="bg-primary">
                    <tr>
                        <th  colspan="7">CONTRACTOR SET - 
                            <?php
                            $QUA = @$_REQUEST["quarter_value"];
                            echo $QUARTER[$QUA]; 
                            ?>
                            SITE SAFETY AUDIT SCORE</th>
                    </tr>
                    <tr class="bg-primary">
                        <th rowspan="3" width="5%">SL.NO</th>
                        <th rowspan="3" width="20%">DEPT / DIST</th>
                    </tr>
                    <tr>
                        <th rowspan="2" width="10%">NO. OF SET / UNIT</th>
                        <th colspan="2">
                            <?php
                            $QUA = @$_REQUEST["quarter_value"];
                            echo $QUARTER[$QUA].'&nbsp;&nbsp;&nbsp;';  
                            echo @$getDate["PREV_Y"];
                            ?>
                        </th>
                        <th colspan="2">
                            <?php
                            $QUA = @$_REQUEST["quarter_value"];
                            echo $QUARTER[$QUA].'&nbsp;&nbsp;&nbsp;';
                            echo @$getDate["CURR_Y"];
                        ?>
                        </th>
                       
                       
                    </tr>
                    <tr>
                        <th width="10%"> AUDIT COUNT </th>
                        <th width="10%"> % AVG </th>
                        <th width="10%"> AUDIT COUNT </th>
                        <th width="10%"> % AVG </th>
                       
                    
                    </tr>
                </thead>
                <tbody>
                    <?php
                   
                    if(!empty($arr_cset_data)) {
                        $slno =0;
                        $tot_cset_unit = $tot_cset_prev_count = $tot_cset_current_count = $tot_cset_prev_score = $tot_cset_current_score =0;
                        foreach( $arr_cset_data as $key => $value ) {
                            $slno++;
                            $csetprevcount = $value["cset_prev"]["totaldiv"];
                            $csetcurrentcount = $value["cset_current"]["totaldiv"];
                            $csetprevscore = round(($value["cset_prev"]["score"]),2);
                            $csetcurrentscore = round(($value["cset_current"]["score"]),2);
                            $cset_unit = $value["cset_unit"];
                            echo '<tr>
                        <td bgcolor="#07efcc" style="border-color:#000;" align="center">'.($slno).'.</td>
                        <td bgcolor="#f7efef" style="border-color:#000;" align="left">'.$value["district"]["name"].'</td>
                        <td bgcolor="#ffaa80" style="border-color:#000;" align="center">'.$cset_unit.'</td>
                        <td bgcolor="#ffccff" style="border-color:#000;" align="center">'.$csetprevcount.'</td>
                        <td bgcolor="#DBEEF4" style="border-color:#000;" align="center">'.$csetprevscore.'</td>
                        <td bgcolor="#ffccff" style="border-color:#000;" align="center">'.$csetcurrentcount.'</td>
                        <td bgcolor="#DBEEF4" style="border-color:#000;" align="center">'.$csetcurrentscore.'</td>
                    </tr>';
                            $tot_cset_unit +=$cset_unit;
                            $tot_cset_prev_count +=$csetprevcount;
                            $tot_cset_current_count +=$csetcurrentcount;
                            
                            $tot_cset_prev_score +=$csetprevscore;
                            $tot_cset_current_score +=$csetcurrentscore;
                        }
                        echo '<tr>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center" colspan="2">TOTAL</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_cset_unit).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_cset_prev_count).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center"></td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center">'.($tot_cset_current_count).'</td>'
                        . '<td bgcolor="#ffff80" style="border-color:#000;" align="center"></td>'
                                . '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        
    <!--END PPE AUDIT-->
    
    <!--START SITE AUDIT-->     
        
        <div class="print1" style="float: left; border:1px solid #000; padding:3px 8px;">
            NA : NOT APPLICABLE</td>
        </div>
       
        </div>
    <div style="clear:both;" class="clearfix" ></div>
            <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
        </div>
</div>
        <?php  } ?>
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