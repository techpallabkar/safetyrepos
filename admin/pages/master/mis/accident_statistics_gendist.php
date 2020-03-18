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
$getDate      =   $beanUi->get_view_data("getDate");
$dataArr      =   $beanUi->get_view_data("dataArr");
$incident_category_id      =   $beanUi->get_view_data("incident_category_id");
$controller->get_header();
$site_root_url = dirname(url());
//show($current_fy_month_arr);
?>

<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">29. ACCIDENT STATISTICS (G+D)
</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr/>
    <div class="container"  style="min-height:200px;">
      
 <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
 Export to Excel</button>
    <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
        <div id='TBL_EXPORT'>
        <div class="print-friendly" id="divToPrint">  
            <div id="parent">    
        <table  border="1" class="table table-bordered table-condensed totaldata" style="font-size:13px;font-weight: bold;" >
                <thead class="bg-primary">
                    <tr>
                        <th  colspan="12">ACCIDENT STATISTICS (G+D)</th>
                    </tr>
                    <tr>
                        <th rowspan="2" width="5%">SL.NO</th>
                        <th>TYPE</th>
                        <?php 
                        if(!empty($incident_category_id)) {
                            foreach ( $incident_category_id as $key =>$value ) {
                                $showstr = ($value->id ==4) ? "(A)" :(($value->id ==3) ? "(B)":(($value->id ==2) ? "(C)" : ""));
                                echo '<th colspan="2">'.$value->name.' '.$showstr.'</th>';
                                echo ($value->id ==2) ? '<th colspan="2">TOTAL (A+B+C)</th>' : "";
                            }
                        }
                        ?>
                    </tr>
                    <tr class="bg-primary">
                        <th width="10%">MONTH</th>
                        <th> P / EMP</th>
                        <th> C / EMP</th>
                        <th> P / EMP</th>
                        <th> C / EMP</th>
                        <th> P / EMP</th>
                        <th> C / EMP</th>
                        <th> P / EMP</th>
                        <th> C / EMP</th>
                        <th> P / EMP</th>
                        <th> C / EMP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($dataArr)) {
                        $color = array(1=> "#F2DCDB", 2=> "#FCD5B5", 3=>"#E6E0EC",4=>"#FDEADA");
                        foreach($dataArr as $key => $value) {
                            $totalpset = $totalcset = 0;
                            echo '<tr>
                        <td align="center" style="border:1px solid #000;">'.($key+1).'.</td>
                        <td align="center" style="border:1px solid #000;">'.(date("m/y",strtotime($value["month"].'-01'))).'</td>';
                            foreach ( $incident_category_id as $key1 =>$value1 ) {
                                $psetval = $value["incdata"][$value1->id]["pset"];
                                $csetval = $value["incdata"][$value1->id]["cset"];
                               echo '<td align="center" style="border:1px solid #000;"  bgcolor="'.$color[$value1->id].'">'.($psetval).'</td>
                        <td align="center" style="border:1px solid #000;"  bgcolor="'.$color[$value1->id].'">'.($csetval).'</td>'; 
                               if($value1->id != 1) {
                                   $totalpset +=$psetval;
                                   $totalcset +=$csetval;
                               }
                               if($value1->id == 2) {
                                   echo '<td style="border:1px solid #000;" align="center" bgcolor="#CCC1DA">'.($totalpset).'</td>
                        <td style="border:1px solid #000;" align="center" bgcolor="#CCC1DA">'.($totalcset).'</td>'; 
                               }
                            }
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

