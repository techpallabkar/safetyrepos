<?php
/* MIS Report No. : 10
 * Name         :   SCORE CARD DETAIL FOR P-SET (DISTRIBUTION) 
 * Controller   :   psetScoresAllDistributionWithTobBottomColourCoding()
 * Dao          :   getAuditCountforpsetDist,
 * Created By Sumit
 * Modified By Anima And Pallab
 */

if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php");
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$get_contractor_details = $beanUi->get_view_data("set_contractor_details");
$districtName = $beanUi->get_view_data("districtName");
$fetchArr = $beanUi->get_view_data("fetchArr");//show($fetchArr);
$dateArr = $beanUi->get_view_data("datearr");

$controller->get_header();
$site_root_url = dirname(url());
@$fy = $dateArr['fy'];
@$cm2 = date("M, Y",strtotime($dateArr['cm'].'-01'.'-2months'));
@$cm1 = date("M, Y",strtotime($dateArr['cm'].'-01'.'-1month'));
@$cm = date("M, Y",strtotime($dateArr['cm'].'-01'));
//show($fetchArr);
?>

<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">10. P-SET SCORES (ALL DISTRIBUTION) </h1> 
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
            <a href="psetScoresAllDistributionWithTobBottomColourCoding.php" class="btn btn-danger btn-sm">Reset</a>
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
                    <table border="1" class="table table-bordered table-condensed table-responsive test" style="font-size:13px;font-weight:bold; " >
                        <thead class="bg-primary">
                            <tr><th colspan="11"> P-SET SCORES (ALL DISTRIBUTION) </th></tr>
                            <tr>
                                <th rowspan="2"> SL.</th>
                                <th rowspan="2"> P-SET </th>
                                <th rowspan="2"> DIST. / DEPT. </th>
                                <th colspan="2"> <?php echo $cm2; ?> </th>
                                <th colspan="2"> <?php echo $cm1; ?> </th>
                                <th colspan="2"> <?php echo $cm; ?> </th>
                                <th colspan="2"> <?php //echo $fy; ?> Three Month </th>
                            </tr>
                            <tr>
                                <th> MARKS </th>
                                <th> CHECK DATE </th>
                                <th> MARKS </th>
                                <th> CHECK DATE </th>
                                <th> MARKS </th>
                                <th> CHECK DATE </th>
                                <th> AUDIT COUNT </th>
                                <th > %  AVG </th>
                            </tr>
                        </thead>
                        <tbody align="center">
                            <?php
//                                        showPre($fetchArr);
                            $a = 0;
                            foreach ($fetchArr as $key => $value) {
                                $b = 0;
                                
                                if(!empty($value)) {
                                    
                                    foreach( $value as $k1 => $v1 ) {
                                        $ptwoavgmarks=0;$cavgmarks=0;$poneavgmarks=0;
                                        $b++;
                                        
                                        $cellname = $v1["cell_name"];
                                        $districtname = $v1["district_name"]; 
                                        $currentmonth = $v1["current_month"]; 
                                        $prevonemonth = $v1["prev_month_one"]; 
                                        $prevtwomonth = $v1["prev_month_two"]; 
                                        $ab=str_replace(' ','',$v1["set_name"]);
                                        $s= str_replace('&', '', $ab);
                                        
                                        $totalauditcount = count($currentmonth) + count($prevonemonth)+count($prevtwomonth);
                                        //echo $totalauditcount.'<br>';
                                        if( !empty($currentmonth) ) {
                                            
                                            foreach( $currentmonth as $key1 => $val1 ) {
                                                $a = $a + 1;
                                                $cavgmarks +=$val1->avg_mark;
                                                echo '<tr>
                                                <td style="border:1px solid #000 !important;">' . ($a) . '. </td>
                                                <td bgcolor="#CCFFFF" style="border:1px solid #000 !important;"> ' . ($v1["set_name"]) . ' </td>
                                                <td bgcolor="#c5ff93" style="border:1px solid #000 !important;"> ' . ($cellname) . '</td>
                                                <td style="border:1px solid #000 !important;"> </td>
                                                <td style="border:1px solid #000 !important;"> </td>
                                                <td style="border:1px solid #000 !important;"> </td>
                                                <td style="border:1px solid #000 !important;"> </td>
                                                <td bgcolor="#f5d6ff" style="border:1px solid #000 !important;"> ' . ($val1->avg_mark) . '</td>
                                                <td bgcolor="#f0ad4e" style="border:1px solid #000 !important;"> ' . (($val1->date_of_audit!='0000-00-00') ? date("d-m-Y",strtotime($val1->date_of_audit)) : "") . '</td>';
                                                echo '<td bgcolor="#b3ccff" style="border:1px solid #000 !important;" class="aaa_'.$v1["set_id"].$s.'" style="font-size:16px;vertical-align:middle;"  > 1</td> ';
                                                echo '<td bgcolor="#b3ffff" style="border:1px solid #000 !important;" class="bbb_'.$v1["set_id"].$s.'" style="font-size:16px;vertical-align:middle;" > ' . ($val1->avg_mark) . '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                        if( !empty($prevonemonth) ) {
                                            
                                            foreach( $prevonemonth as $key2 => $val2 ) {
                                                $poneavgmarks +=$val2->avg_mark;
                                                $a = $a + 1;
                                                echo '<tr>
                                                <td style="border:1px solid #000 !important;">' . ($a) . '. </td>
                                                <td bgcolor="#CCFFFF" style="border:1px solid #000 !important;"> ' . ($v1["set_name"]) .' </td>
                                                <td bgcolor="#c5ff93" style="border:1px solid #000 !important;"> ' . ($cellname) . '</td>
                                                <td style="border:1px solid #000 !important;"> </td>
                                                <td style="border:1px solid #000 !important;"> </td>
                                                <td bgcolor="#f5d6ff" style="border:1px solid #000 !important;"> ' . ($val2->avg_mark) . '</td>
                                                <td bgcolor="#f0ad4e" style="border:1px solid #000 !important;"> ' . (($val2->date_of_audit!='0000-00-00') ? date("d-m-Y",strtotime($val2->date_of_audit)) : "") . '</td>
                                                <td style="border:1px solid #000 !important;"></td>
                                                <td style="border:1px solid #000 !important;"></td>';
                                                echo '<td bgcolor="#b3ccff" style="border:1px solid #000 !important;" class="aaa_'.$v1["set_id"].$s.'" style="font-size:16px;vertical-align:middle;"  > 1</td> ';
                                                echo '<td bgcolor="#b3ffff" style="border:1px solid #000 !important;" class="bbb_'.$v1["set_id"].$s.'" style="font-size:16px;vertical-align:middle;" > ' . ($val2->avg_mark) . '</td>';
                                              
                                                echo '</tr>';
                                            }
                                        }
                                        if( !empty($prevtwomonth) ) {
                                            
                                            foreach( $prevtwomonth as $key3 => $val3 ) {
                                                $a = $a + 1;
                                                $ptwoavgmarks +=$val3->avg_mark;
                                                echo '<tr>
                                                <td style="border:1px solid #000 !important;">' . ($a) . '. </td>
                                                <td bgcolor="#CCFFFF" style="border:1px solid #000 !important;"> ' . ($v1["set_name"]) . ' </td>
                                                <td bgcolor="#c5ff93" style="border:1px solid #000 !important;"> ' . ($cellname) . '</td>
                                                <td bgcolor="#f5d6ff" style="border:1px solid #000 !important;">' . ($val3->avg_mark) . ' </td>
                                                <td bgcolor="#f0ad4e" style="border:1px solid #000 !important;"> ' . (($val3->date_of_audit!='0000-00-00') ? date("d-m-Y",strtotime($val3->date_of_audit)) : "") . '</td>
                                                <td style="border:1px solid #000 !important;"> </td>
                                                <td style="border:1px solid #000 !important;"> </td>
                                                <td style="border:1px solid #000 !important;"> </td>
                                                <td style="border:1px solid #000 !important;"> </td>';
                                                
                                                echo '<td bgcolor="#b3ccff" style="border:1px solid #000 !important;" class="aaa_'.$v1["set_id"].$s.'" style="font-size:16px;vertical-align:middle;"  > 1</td> ';
                                                echo '<td bgcolor="#b3ffff" style="border:1px solid #000 !important;" class="bbb_'.$v1["set_id"].$s.'" style="font-size:16px;vertical-align:middle;" > ' . ($val3->avg_mark) . '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                       @$totalavg=0;
                                       @$tt= $cavgmarks+$poneavgmarks+ $ptwoavgmarks;
                                       @$totalavg = $tt/$totalauditcount;
                                       
                                       echo '<div class="sdfg '.$v1["set_id"].$s.'" style="display:none;" data-rowcount="'.$totalauditcount.'">'.round($totalavg,2).'</div>';
                                       ?>
                            <script>
                            $(document).ready(function () {
                                var asd = $(".<?php echo $v1["set_id"].$s;?>").data("rowcount");
                                var asd2 = $(".<?php echo $v1["set_id"].$s;?>").text();
                               // alert(asd);
                               $('.aaa_<?php echo $v1["set_id"].$s;?>:first').attr('rowspan',asd);
                               $('.aaa_<?php echo $v1["set_id"].$s;?>:first').addClass('someClass').text(asd);
                               $('.aaa_<?php echo $v1["set_id"].$s;?>:gt(0)').remove();
                               $('.bbb_<?php echo $v1["set_id"].$s;?>:first').attr('rowspan',asd);
                               $('.bbb_<?php echo $v1["set_id"].$s;?>:first').addClass('someClassb').text(asd2);
                               $('.bbb_<?php echo $v1["set_id"].$s;?>:gt(0)').remove();
                            });
                            </script>
                            <?php
                                       $tt=0;
                                       $totalauditcount=0;
                                       $totalavg=0;
                                       
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
                            $(".sdfg").html('');
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
                        mywindow.document.write('<style>@page{-webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);}body{font-size:12px !important;font-family:Arial !important;}table{border-collapse:collapse;margin-bottom:15px;font-size:12px !important;font-family:Arial !important;}table th, table td {border:1px solid #80aaff;border-collapse:collapse;}table th {background: #88b5dd !important;-webkit-print-color-adjust: exact;color-adjust: exact;border:1px solid #609dd2;}th {color:#ffffff !important;}.sdfg{display:none !important;}</style></head><body>');
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
