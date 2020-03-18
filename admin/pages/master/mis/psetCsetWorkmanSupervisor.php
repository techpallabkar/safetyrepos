<?php
if (file_exists("../../lib/var.inc.php"))
require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$arr_date_send                  =   $beanUi->get_view_data("arr_date_send");
$totaldata   =   $beanUi->get_view_data("totaldata");
@$this_month = date("M, Y", strtotime("01-".$arr_date_send["curr_month"]));
@$controller->get_header();
@$site_root_url = dirname(url());
?>
<style>
    .none {display: none;}
</style>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
</script>
<div class="container1">

    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">24. Training Details</h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr />

    <form action="" method="post" id="mdform" enctype="multipart/form-data" style="<?php echo  ( $mode == "view" ) ? 'display:none' : 'display:block'; ?>" >
                <div class="holder">
                    <label for="job_stop_req_raisedx" style="width:15%;padding-top:5px;">Select Month-Year :</label>
                    <input type="text" name="month_year" id="month_year_from" class="month_year_picker" value ="<?php echo @$arr_date_send['curr_month']; ?>" style="width:250px;"  required/>
                    <input type="hidden" name="_action" value="Create" />
                    <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
                    <a href="psetCsetWorkmanSupervisor.php" class="btn btn-danger btn-sm">Reset</a>                 
                </div>
                <hr class="no-margin"/>
            </form>

    <form action="" method="post" enctype="multipart/form-data">
        
       <?php if(!empty($_POST)) { ?>
        
        <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
 Export to Excel</button>
        <div class="print1" style="float: right;">
        <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
        <div id='TBL_EXPORT'>
        <div class="print-friendly" id="divToPrint">
            <div class="none">
                        <u>
                            Training Details 
                            <?php
//                            $date = strtotime('01-'.$arr_date_send['curr_month']);
//                            echo date('F-Y',$date); 
                            ?>
                        </u>
                <br>
                <br>
                <br>
            </div>
            
        <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px; width:100%;" >
                <thead class="bg-primary">
                    <tr>
                        <th colspan="15">
                            <h4><b>TRAINING RECORDS TILL 
                            <?php
                            $date = strtotime('01-'.$arr_date_send['curr_month']); 
                            echo date('F, Y',$date); 
                            ?></b>
                        </h4>
                        </th>
                    </tr>
                    <tr >
                        <th rowspan="3" width="10%">STATION</th>
                        <th rowspan="2" colspan="2"  width="10%">OFFICERS</th>
                        <th  colspan="6" width="40%"> PERMANENT </th>
                        <th  colspan="6" width="40%"> CONTRACTUAL </th>
                    </tr>
                   
                    <tr>
                        
                        <th colspan="2">WORKMAN</th>
                        <th colspan="2">SUPERVISOR</th>
                        <th colspan="2">TOTAL</th>
                        
                        <th colspan="2">WORKMAN</th>
                        <th colspan="2">SUPERVISOR</th>
                        <th colspan="2">TOTAL</th>
                    </tr>
                   
                    <tr>
                        
                        <th>
                            <?php
                            $date = strtotime('01-'.$arr_date_send['curr_month']); 
                            echo date('M, Y',$date); 
                            ?>
                        </th>
                        <th>YTM Total</th>
                        <th>
                            <?php
                            $date = strtotime('01-'.$arr_date_send['curr_month']); 
                            echo date('M, Y',$date); 
                            ?>
                        </th>
                        <th>YTM Total</th>
                        <th>
                            <?php
                            $date = strtotime('01-'.$arr_date_send['curr_month']); 
                            echo date('M, Y',$date); 
                            ?>
                        </th>
                        <th>YTM Total</th>
                        <th>
                            <?php
                            $date = strtotime('01-'.$arr_date_send['curr_month']); 
                            echo date('M, Y',$date); 
                            ?>
                        </th>
                        <th>YTM Total</th>
                        <th>
                            <?php
                            $date = strtotime('01-'.$arr_date_send['curr_month']); 
                            echo date('M, Y',$date); 
                            ?>
                        </th>
                        <th>YTM Total</th>
                        <th>
                            <?php
                            $date = strtotime('01-'.$arr_date_send['curr_month']); 
                            echo date('M, Y',$date); 
                            ?>
                        </th>
                        <th>YTM Total</th>
                        <th>
                            <?php
                            $date = strtotime('01-'.$arr_date_send['curr_month']); 
                            echo date('M, Y',$date); 
                            ?>
                        </th>
                        <th>YTM Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    
                    $psetOfficerMonthlyData = $psetytmOfficerMonthlyData = $psetWorkmanMonthlyData = $psetytmWorkmanMonthlyData = $psetSupervisorMonthlyData = $psetytmSupervisorMonthlyData = 0;
                    $csetWorkmanMonthlyData = $csetytmWorkmanMonthlyData = $csetSupervisorMonthlyData = $csetytmSupervisorMonthlyData =  0;
                    $t1 = $t2 = $t3 = $t4 = 0;
                    if(!empty($totaldata)) {//show($totaldata);
                        foreach( $totaldata as $key => $value ) {//show($value["station"]);
                            echo ' <tr style="font-weight:bold;"> 
                        <td align="left" bgcolor="#e3ff96">'.($value["station"]).'</td>
                        <td align="center" bgcolor="#e1ecf4">'.(($value["psetOfficerMonthlyData"])      ? $value["psetOfficerMonthlyData"] : 0).'</td>
                        <td align="center" bgcolor="#e1ecf4">'.(($value["psetytmOfficerMonthlyData"])   ? $value["psetytmOfficerMonthlyData"] : 0).'</td>
                        <td align="center" bgcolor="#cef257">'.(($value["psetWorkmanMonthlyData"])      ? $value["psetWorkmanMonthlyData"] : 0).'</td>
                        <td align="center" bgcolor="#53f4df">'.(($value["psetytmWorkmanMonthlyData"])   ? $value["psetytmWorkmanMonthlyData"] : 0).'</td>
                        <td align="center" bgcolor="#cef257">'.(($value["psetSupervisorMonthlyData"])   ? $value["psetSupervisorMonthlyData"] : 0).'</td>
                        <td align="center" bgcolor="#53f4df">'.(($value["psetytmSupervisorMonthlyData"])? $value["psetytmSupervisorMonthlyData"] : 0).'</td>
                        <td align="center" bgcolor="#cef257">'.($value["psetWorkmanMonthlyData"]+$value["psetSupervisorMonthlyData"]).'</td>
                        <td align="center" bgcolor="#53f4df">'.($value["psetytmWorkmanMonthlyData"]+$value["psetytmSupervisorMonthlyData"]).'</td>
                        <td align="center" bgcolor="#cef257">'.(($value["csetWorkmanMonthlyData"])      ? $value["csetWorkmanMonthlyData"] : 0).'</td>
                        <td align="center" bgcolor="#53f4df">'.(($value["csetytmWorkmanMonthlyData"])   ? $value["csetytmWorkmanMonthlyData"] : 0).'</td>
                        <td align="center" bgcolor="#cef257">'.(($value["csetSupervisorMonthlyData"])   ? $value["csetSupervisorMonthlyData"] : 0).'</td>
                        <td align="center" bgcolor="#53f4df">'.(($value["csetytmSupervisorMonthlyData"])? $value["csetytmSupervisorMonthlyData"] : 0).'</td>
                        <td align="center" bgcolor="#cef257">'.($value["csetWorkmanMonthlyData"]+$value["csetSupervisorMonthlyData"]).'</td>
                        <td align="center" bgcolor="#53f4df">'.($value["csetytmWorkmanMonthlyData"]+$value["csetytmSupervisorMonthlyData"]).'</td>
                    </tr>'; 
                            if($value["station"] != "BBGS" && $value["station"] != "SGS"){
                            $psetOfficerMonthlyData +=$value["psetOfficerMonthlyData"];
                            $psetytmOfficerMonthlyData +=$value["psetytmOfficerMonthlyData"];
                            $psetWorkmanMonthlyData +=$value["psetWorkmanMonthlyData"];
                            $psetytmWorkmanMonthlyData +=$value["psetytmWorkmanMonthlyData"];
                            $psetSupervisorMonthlyData +=$value["psetSupervisorMonthlyData"];
                            $psetytmSupervisorMonthlyData +=$value["psetytmSupervisorMonthlyData"];
                            $t1 += $value["psetWorkmanMonthlyData"]+$value["psetSupervisorMonthlyData"];
                            $t2 += $value["psetytmWorkmanMonthlyData"]+$value["psetytmSupervisorMonthlyData"];
                            $csetWorkmanMonthlyData +=$value["csetWorkmanMonthlyData"];
                            $csetytmWorkmanMonthlyData +=$value["csetytmWorkmanMonthlyData"];
                            $csetSupervisorMonthlyData +=$value["csetSupervisorMonthlyData"];
                            $csetytmSupervisorMonthlyData +=$value["csetytmSupervisorMonthlyData"];
                            $t3 += ($value["csetWorkmanMonthlyData"]+$value["csetSupervisorMonthlyData"]);
                            $t4 += ($value["csetytmWorkmanMonthlyData"]+$value["csetytmSupervisorMonthlyData"]);
                            }
                        }
                        echo '<tr style="font-weight:bold;background:#f0ad4e;">'
                        . '<td>GRAND TOTAL (G+D)</td>'
                        . '<td align="center">'.($psetOfficerMonthlyData).'</td>'
                        . '<td align="center">'.($psetytmOfficerMonthlyData).'</td>'
                        . '<td align="center">'.($psetWorkmanMonthlyData).'</td>'
                        . '<td align="center">'.($psetytmWorkmanMonthlyData).'</td>'
                        . '<td align="center">'.($psetSupervisorMonthlyData).'</td>'
                        . '<td align="center">'.($psetytmSupervisorMonthlyData).'</td>'
                        . '<td align="center">'.($t1).'</td>'
                        . '<td align="center">'.($t2).'</td>'
                        . '<td align="center">'.($csetWorkmanMonthlyData).'</td>'
                        . '<td align="center">'.($csetytmWorkmanMonthlyData).'</td>'
                        . '<td align="center">'.($csetSupervisorMonthlyData).'</td>'
                        . '<td align="center">'.($csetytmSupervisorMonthlyData).'</td>'
                        . '<td align="center">'.($t3).'</td>'
                        . '<td align="center">'.($t4).'</td>'
                                . '</tr>';
                       
                    }
                   ?>
                </tbody>
              

            </table>
            <div>NOTE : Report based on all status (Draft,FinalSumit,Approve & Published,Approve & Unpublished)</div>
            <div style="clear:both;" class="clearfix" ></div>
            <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
        </div>
</div>
       <?php } ?>
    </form>
</div>
<?php $controller->get_footer(); ?>

</body>
</html>
<script type="text/javascript" src="<?php echo url('assets/js/ExportExcel/jquery.table2excel.js') ?>"></script>
<link rel="stylesheet" href="<?php echo url("assets/css/jqueryui/jquery-ui.css") ?>">
<script src="<?php echo url("assets/js/jqueryui/jquery.mtz.monthpicker.js") ?>"></script>
<script>
    $('.month_year_picker').monthpicker({pattern: 'mm-yyyy',
        startYear: 2015,
        finalYear: 2025, });
    var options = {
        startYear: 2010,
        finalYear: 2018,
        openOnFocus: false
    };
</script>
<script type='text/javascript'>
$(document).ready(function(){
    $("#ExportExcel").click(function(e){
        var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html()); 
        window.open(path);
        e.preventDefault();
    });
});
</script>
<script type="text/javascript">  
    function PrintDiv()
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=300');
    var content = document.getElementById("divToPrint").innerHTML;
    mywindow.document.write('<html><head><title></title>');
    mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}</style></head><body>');
    //mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}
 </script>
