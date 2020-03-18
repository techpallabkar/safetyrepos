<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php");
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$get_contractor_details = $beanUi->get_view_data("set_contractor_details");
$districtName = $beanUi->get_view_data("districtName");//show($districtName[0]->treename);
$fetchArr = $beanUi->get_view_data("fetchArr");
$dateArr = $beanUi->get_view_data("datearr");

$controller->get_header();
$site_root_url = dirname(url());
@$fy = $dateArr['fy'];
@$cm = date("M, Y",strtotime($dateArr['cm'].'-01'));
//showPre($fetchArr);
?>


<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">31. AUDIT REPORT DISTRIBUTION PSET </h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr />
    <form action="" method="post" id="fyr" enctype="multipart/form-data">
        <label class="col-sm-2">Select Month</label>
        <div class="col-sm-2">
            <input type="text" required="true" name="month_year" id="month_year_from" class="month_year_picker form-control" value = "<?php echo @$_REQUEST["month_year"]; ?>" style="width:100%;" >
            <span id="financial_year_error"></span>
        </div>
<!--        <label class="col-sm-2">Division Department</label>
        <div class="col-sm-3">
            <?php
//            echo '<select name="tree" class="tree" required="true">';
//            echo '<option value="">-Select-</option>';
//            foreach ($districtName as $key => $value) {
//                echo '<option value="' . $value->treeid . '" ' . (($_REQUEST["tree"] == $value->treeid) ? "selected" : "") . '>' . $value->treename . '</option>';
//            }
//            echo '</select>';
            ?>
        </div>-->

        <div class="col-sm-2">
            <input type="hidden" name="_action" value="submitDate" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="audit_report_dist_pset.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
    <hr /> 
    <form action="" method="post" id="p_set" enctype="multipart/form-data">
        <?php //if (!empty($fetchArr)) {
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
                            <tr><th colspan="9"> AUDIT REPORT DISTRIBUTION PSET </th></tr>
                            <tr>
                                <th rowspan="2"> SL. </th>
                                <th rowspan="2"> NODE </th>
                                <th colspan="2"> MTM </th>
                                <th colspan="2"> YTM </th>
                                <th colspan="2"> YTM </th>
                            </tr>
                            <tr>
                                <th> AUDIT COUNT </th>
                                <th> SCORE </th> 
                                <th> AUDIT COUNT </th>
                                <th> SCORE </th> 
                                <th> AUDIT COUNT </th>
                                <th> SCORE </th> 
                            </tr>
                        </thead>
                        <tbody align="center">
                            <?php
                                foreach( $districtName as $k1 => $v1 ) {
                                    echo '<tr>
                                            <td >'.$k1.'</td>
                                            <td >'.$v1->treename.'</td>
                                            <td ></td>
                                            <td ></td>
                                            <td ></td>
                                            <td ></td>
                                            <td ></td>
                                            <td ></td>';
                                    echo '<tr>';
                                }   
                            ?>
                        </tbody>
                    </table>
                    <div style="clear:both;" class="clearfix" ></div>
                    <div style="float: bottom"> <?php echo CURRENT_DATE_TIME; ?> </div>
                </div>
            </div>
        <?php //} ?>
    </form>
</div>
<script type="text/javascript" src="<?php echo url('assets/js/ExportExcel/jquery.table2excel.js') ?>"></script>
<link rel="stylesheet" href="<?php echo url("assets/css/jqueryui/jquery-ui.css") ?>">
<script src="<?php echo url("assets/js/jqueryui/jquery.mtz.monthpicker.js") ?>"></script>
<script type='text/javascript'>
                    $('.month_year_picker').monthpicker({pattern: 'mm-yyyy',
                        startYear: 2015,
                        finalYear: 2025, });
                    var options = {
                        startYear: 2010,
                        finalYear: 2018,
                        openOnFocus: false
                    };

                    $(document).ready(function () {
                        //export to excel start
                        $("#ExportExcel").click(function (e) {
                            var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#TBL_EXPORT').html());
                            window.open(path);
                            e.preventDefault();
                        });

                        //tree start



//                    $(".tree").change(function () {
//                        var tree_val = $(this).find(":selected").val();
//                        var ajax_data = {
//                        "action"    : "getCascadingTree",
//                        "tree_val"  : tree_val
//                        };
//                            $.ajax({
//                                type: 'post', 
//                                url: '<?php //echo page_link("mis/pset_deptwise_setwise_audit_score_gen.php");  ?>', 
//                                cache: false,
//                                data: ajax_data,
//                                success: function (data) {
//                                    if (data)
//                                    {
//                                        var peopleHTML = "";
//                                        var JSONObject = JSON.parse(data);
//                                        var ischild;
//                                         
//                                            peopleHTML += '<select name="tree_2" class="tree">';
//                                            peopleHTML += '<option value="">-Select-</option>';
//                                        $.each(JSONObject, function (i) {
//                                            
//                                           ischild = (JSONObject[i]["ischild"]);
//                                          var subchild ="";
//                                            if(ischild) {
//                                            $.each(ischild, function (j) {
//                                                peopleHTML += '<option value="'+JSONObject[i]["id"]+'-'+ischild[j]["id"]+'">'+ischild[j]["name"]+'</option>';    
//
//                                            });   
//                                            
//                                            } else {
//                                              peopleHTML += '<option value="'+JSONObject[i]["id"]+'">'+JSONObject[i]["name"]+'</option>';  
//                                            }
//                                        });
//                                            peopleHTML += '</select>';
//                                            $(".nextlevel").html(peopleHTML);
//                                    }
//                                }
//                            });
//                        
//                        
//                    });
                    });

                    function PrintDiv() {
                        var mywindow = window.open('', 'PRINT', 'height=400,width=300');
                        var content = document.getElementById("divToPrint").innerHTML;
                        mywindow.document.write('<html><head><title> P-SET DEPARTMENT WISE AUDIT SCORE (DISTRICT) </title>');
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
<?php $controller->get_footer(); ?>

</body>
</html>
