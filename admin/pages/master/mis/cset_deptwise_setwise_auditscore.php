<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$districtName = $beanUi->get_view_data("districtName");
$fetchArr = $beanUi->get_view_data("fetchArr");
$dateArr = $beanUi->get_view_data("datearr");

$controller->get_header();
$site_root_url = dirname(url());
@$fy = $dateArr['fy'];
@$cm = date("M, Y",strtotime($dateArr['cm'].'-01'));
?>


<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">23. C-SET DEPARTMENT WISE SET WISE AUDIT SCORE (DIST.) </h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr />
    <form action="" method="post" id="fyr" enctype="multipart/form-data">
        <label class="col-sm-2">Select Month</label>
        <div class="col-sm-2">
            <input type="text" required="true" name="month_year" id="month_year_from" class="month_year_picker form-control" value = "<?php echo @$_REQUEST["month_year"]; ?>" style="width:100%;" >
            <span id="financial_year_error"></span>
        </div>
        <label class="col-sm-2">Division Department</label>
        <div class="col-sm-3">
            <?php
            echo '<select name="tree" class="tree" required="true">';
            echo '<option value="">-Select-</option>';
            foreach ($districtName as $key => $value) {
                echo '<option value="' . $value->treeid . '" ' . (($_REQUEST["tree"] == $value->treeid) ? "selected" : "") . '>' . $value->treename . '</option>';
            }
            echo '</select>';
            ?>
        </div>

        <div class="col-sm-2">
            <input type="hidden" name="_action" value="submitDate" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="cset_deptwise_setwise_auditscore.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr /> 
    <form action="" method="post" id="p_set" enctype="multipart/form-data">
        <?php if (!empty($fetchArr)) {
            ?>
            <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
                Export to Excel</button>
            <div class="print1" style="float: right;">
                <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
            </div>
            <div id='TBL_EXPORT'>
                <div class="print-friendly" id="divToPrint">
                    <table border="1" class="table table-bordered table-condensed table-responsive" style="font-size:13px;font-weight:bold; " >
                        <thead class="bg-primary">
                            <tr><th colspan="9"> C-SET DEPARTMENT WISE SET WISE AUDIT SCORE (DIST.) </th></tr>
                            <tr>
                                <th colspan="4"> <?php echo $fy; ?> </th>
                                <th colspan="2"> <?php echo $cm; ?> </th>
                                <th colspan="3"> YTM </th>
                            </tr>
                            <tr>
                                <th> SL. </th>
                                <th> C-SET </th>
                                <th> DIST/DEPT </th>
                                <th> CELL </th>
                                <th> MARKS </th>
                                <th> CHECK DATE </th>
                                <th> AUDIT COUNT </th>
                                <th colspan="2"> %  AVG </th>
                            </tr>
                        </thead>
                        <tbody align="center">
                            <?php
//                            show($fetchArr);
                                $allids = array();
                            foreach ($fetchArr as $key1 => $value1) {
                                if(!empty($value)) {
                                    foreach( $value1 as $k2 => $v2 ) {
                                        $allids[] = $v2["set_id"].$v2["set_name"];
                                    }
                                }
                            }
                            $arr = array_count_values($allids);
                            $a = 0;
                            foreach ($fetchArr as $key => $value) {
                                $b = 0;
                                if(!empty($value)) {
                                    foreach( $value as $k1 => $v1 ) {
                                        $b++;
                                        $a = $a + 1;
                                        $cellname = $v1["cell_name"];
                                        $districtname = $v1["district_name"];                                   
                                        echo '<tr class="'.$v1["tb_name"].'">
                                        <td style="border:1px solid #000 !important;">' . ($a) . '. </td>
                                        <td bgcolor="#CCFFFF" style="border:1px solid #000 !important;" align="left"> <span class="text-danger">' . ((($v1["code"]) ? ($v1["code"]).' - ' : "").'</span>  '.$v1["set_name"]) . ' </td>
                                        <td bgcolor="#c5ff93" style="border:1px solid #000 !important;"> ' . ($districtname) . '</td>
                                        <td bgcolor="#ffff99" style="border:1px solid #000 !important;"> ' . ($cellname) . '</td>
                                        <td bgcolor="#f5d6ff" style="border:1px solid #000 !important;"> ' . ($v1["marks"]) . '</td>
                                        <td bgcolor="#f0ad4e" style="border:1px solid #000 !important;"> ' . (($v1["dateofaudit"] != '0000-00-00' && $v1["dateofaudit"] != '') ? date("d-m-Y",strtotime($v1["dateofaudit"])) : "") . '</td>';
                                        $rowspan =1;
                                        if($b == 1) {
                                            $rowspan=$arr[$v1["set_id"].$v1["set_name"]];
                                        
                                                } else {
                                                    $rowspan=0;
                                                }
                                        if($rowspan > 0) {
                                            echo '<td bgcolor="#ebebe0" style="border:1px solid #000 !important;" rowspan="'.$rowspan.'" style="font-size:16px;vertical-align:middle;"  > ' . ($v1["audit_count"]) . '</td> ';
                                            echo '<td bgcolor="#ffdd99" style="border:1px solid #000 !important;" rowspan="'.$rowspan.'" style="font-size:16px;vertical-align:middle;" > ' . ($v1["totalavg"]) . '</td>';
                                        } elseif(($arr[$v1["set_id"].$v1["set_name"]]) == 1) {
                                            echo '<td bgcolor="#ebebe0" style="border:1px solid #000 !important;" style="font-size:16px;vertical-align:middle;"  > ' . ($v1["audit_count"]) . '</td> ';
                                            echo '<td bgcolor="#ffdd99" style="border:1px solid #000 !important;" style="font-size:16px;vertical-align:middle;" > ' . ($v1["totalavg"]) . '</td>';
                                        }
                                        echo '</tr>';
                                        if($arr[$v1["set_id"].$v1["set_name"]] == 1) {
                                            $b--; 
                                        }
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
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


</script>
<script type="text/javascript">  
    function PrintDiv(){
    var mywindow = window.open('', 'PRINT', 'height=400,width=300');
    var content = document.getElementById("divToPrint").innerHTML;
    mywindow.document.write('<html><head><title> C-SET DEPARTMENT WISE AUDIT SCORE (DISTRICT) </title>');
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