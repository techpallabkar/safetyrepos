<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ReportController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$montharray = $beanUi->get_view_data("montharray");
$allActivity = $beanUi->get_view_data("allActivity");
$allUsers = $beanUi->get_view_data("allUsers");
$workshopReport = $beanUi->get_view_data("workshop");
$communicationReport = $beanUi->get_view_data("commMeeting");
$safetydaysReport = $beanUi->get_view_data("safetyDays");
$trainingReport = $beanUi->get_view_data("training");
$siteauditReport = $beanUi->get_view_data("siteAudit");
$incidentReport = $beanUi->get_view_data("incident");
$ppeauditReport = $beanUi->get_view_data("ppeAudit");
$safetyobsReport = $beanUi->get_view_data("safeyObs");
$safeyObsLIneFunc = $beanUi->get_view_data("safeyObsLIneFunc");
$type_images = array("image/jpg", "image/jpeg", "image/png");
$type_videos = array("video/mp4", "video/quicktime");
$pdf = array("application/pdf");
$controller->get_header();
$site_root_url = dirname(url());
?>
<div class="container1">
    <?php echo $beanUi->get_message(); ?>
    <h1 class="heading"> EMPLOYEE WISE REPORT
        <a class="btn btn-primary btn-sm" style="float:right;" title="Print" onclick='printDiv();'><i class="fa fa-print"></i> Print</a>  <a type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
 Export to Excel</a>
    </h1> 
    <div class="wrapper2">
        <div class="div2">
            <script type="text/javascript" src="<?php echo url('assets/js/ExportExcel/jquery.table2excel.js') ?>"></script>
            <script  src="<?php echo url("assets/js/tableHeadFixer.js"); ?>"></script>
            <style>
                #parent {
                    height: 500px;
                }
                #fixTable {
                    width: 100% !important;
                    font-size: 11px;
                }
                .news { border-bottom:  1px solid #d0d0d0 !important;}

            </style>
            <script>
         $(document).ready(function () {

             $("#fixTable").tableHeadFixer({"left": 1, "foot": true});
         });
            </script>
            </head>
            <form action="" method="post" id="fyr" enctype="multipart/form-data">
        
        <label class="col-sm-1">From</label>
        <div class="col-sm-3">
            
            <input type="text" class="datetimepicker" name="from_date" autocomplete="off" value="<?php echo @$_REQUEST["from_date"] ?>" style="width:100%;" /></div>
         <label class="col-sm-1">To</label>
        <div class="col-sm-3">
       <input type="text" class="datetimepicker" name="to_date" autocomplete="off" value="<?php echo @$_REQUEST["to_date"] ?>" style="width:100%;" />
        </div>
        <div class="col-sm-2">
            <input name="_action" value="Search" type="hidden">
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Search</button>
            <a href="reportEmployeeWise.php" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
            <hr />
            <div id="parentnew">
<!--                <div class="newhead" style="display:none;"> <h3>EMPLOYEE WISE REPORT</h3>
                    <hr>
                 <?php echo @$_REQUEST["from_date"] != "" ? " From :".date("d-m-Y",strtotime(@$_REQUEST["from_date"])) : ""; ?> 
                 <?php echo @$_REQUEST["to_date"] != "" ? " To :".date("d-m-Y",strtotime(@$_REQUEST["to_date"])) : ""; ?> 
              
                <br><br>
                </div>-->
                <div id='TBL_EXPORT'>  
            <div id="parent">
                
                <table cellpadding="0" cellspacing="0" id="fixTable" border="1" style="position:relative;left:0;" class="table table-bordered table-condensed table-responsive table-striped">
                    <thead>
                        <tr>
                            <th colspan="19"  style="vertical-align:middle;" class="bg-primary userth">
                                <b>EMPLOYEE WISE REPORT</b> (
                                <?php echo @$_REQUEST["from_date"] != "" ? " From :".date("d-m-Y",strtotime(@$_REQUEST["from_date"])) : ""; ?> 
                 <?php echo @$_REQUEST["to_date"] != "" ? " To :".date("d-m-Y",strtotime(@$_REQUEST["to_date"])) : ""; ?> ) 
                            </th>
                            
                        </tr>
                        <tr>
                            <th rowspan="2"  style="vertical-align:middle;" class="bg-primary userth">Users</th>
                            <?php
                            foreach ($allActivity as $rowdata) {
                                if ($rowdata->id != 10 && $rowdata->id != 9) {
                                    echo '<th class="bg-primary" colspan="3" width="15%">' . $rowdata->activity_name . '</th>';
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <th bgcolor="#FCC777">Draft</th>
                            <th bgcolor="#F9F683">Final Submit</th>
                            <th bgcolor="#AFF26D">Approved</th>
                            <th bgcolor="#FCC777">Draft</th>
                            <th bgcolor="#F9F683">Final Submit</th>
                            <th bgcolor="#AFF26D">Approved</th>
                            <th bgcolor="#FCC777">Draft</th>
                            <th bgcolor="#F9F683">Final Submit</th>
                            <th bgcolor="#AFF26D">Approved</th>
                            <th bgcolor="#FCC777">Draft</th>
                            <th bgcolor="#F9F683">Final Submit</th>
                            <th bgcolor="#AFF26D">Approved</th>
                            <th bgcolor="#FCC777">Draft</th>
                            <th bgcolor="#F9F683">Final Submit</th>
                            <th bgcolor="#AFF26D">Approved</th>
                            <th bgcolor="#FCC777">Draft</th>
                            <th bgcolor="#F9F683">Final Submit</th>
                            <th bgcolor="#AFF26D">Approved</th>
                            <th bgcolor="#FCC777">Draft</th>
                            <th bgcolor="#F9F683">Final Submit</th>
                            <th bgcolor="#AFF26D">Approved</th>
                            <th bgcolor="#FCC777">Draft</th>
                            <th bgcolor="#F9F683">Final Submit</th>
                            <th bgcolor="#AFF26D">Approved</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $w1 = $w2 = $w3 = "";
                        $c1 = $c2 = $c3 = "";
                        $t1 = $t2 = $t3 = "";
                        $s1 = $s2 = $s3 = "";
                        $sa1 = $sa2 = $sa3 = "";
                        $inc1 = $inc2 = $inc3 = "";
                        $pa1 = $pa2 = $pa3 = "";
                        $so1 = $so2 = $so3 = "";
                        $sol1 = $sol2 = $sol3 = "";
                        foreach ($allUsers as $key => $value) {
                            if ($value->id != 2) {
                                echo '<tr>'
                                . '<td style="background-color:#ccc;" class="news">' . $value->full_name . '</td>'
                                . '<td align="center">' . @$incidentReport[$value->id][1] . '</td>'
                                . '<td align="center">' . @$incidentReport[$value->id][2] . '</td>'
                                . '<td align="center">' . @$incidentReport[$value->id][5] . '</td>'
                                . '<td align="center">' . @$siteauditReport[$value->id][1] . '</td>'
                                . '<td align="center">' . @$siteauditReport[$value->id][2] . '</td>'
                                . '<td align="center">' . @$siteauditReport[$value->id][5] . '</td>'
                                . '<td align="center">' . @$ppeauditReport[$value->id][1] . '</td>'
                                . '<td align="center">' . @$ppeauditReport[$value->id][2] . '</td>'
                                . '<td align="center">' . @$ppeauditReport[$value->id][5] . '</td>'
                                . '<td align="center">' . @$trainingReport[$value->id][1] . '</td>'
                                . '<td align="center">' . @$trainingReport[$value->id][2] . '</td>'
                                . '<td align="center">' . @$trainingReport[$value->id][5] . '</td>'
                                . '<td align="center">' . @$workshopReport[$value->id][1] . '</td>'
                                . '<td align="center">' . @$workshopReport[$value->id][2] . '</td>'
                                . '<td align="center">' . @$workshopReport[$value->id][5] . '</td>'
                                . '<td align="center">' . @$communicationReport[$value->id][1] . '</td>'
                                . '<td align="center">' . @$communicationReport[$value->id][2] . '</td>'
                                . '<td align="center">' . @$communicationReport[$value->id][5] . '</td>'
                                . '<td align="center">' . @$safetyobsReport[$value->id][1] . '</td>'
                                . '<td align="center">' . @$safetyobsReport[$value->id][2] . '</td>'
                                . '<td align="center">' . @$safetyobsReport[$value->id][5] . '</td>'
                                . '<td align="center">' . @$safetydaysReport[$value->id][1] . '</td>'
                                . '<td align="center">' . @$safetydaysReport[$value->id][2] . '</td>'
                                . '<td align="center">' . @$safetydaysReport[$value->id][5] . '</td>'
                                /* . '<td>' . @$safeyObsLIneFunc[$value->id][1] . '</td>'
                                  . '<td>' . @$safeyObsLIneFunc[$value->id][2] . '</td>'
                                  . '<td>' . @$safeyObsLIneFunc[$value->id][5] . '</td>' */
                                . '</tr>';


                                $w1 += $workshopReport[$value->id][1];
                                $w2 += $workshopReport[$value->id][2];
                                $w3 += $workshopReport[$value->id][5];

                                $c1 += $communicationReport[$value->id][1];
                                $c2 += $communicationReport[$value->id][2];
                                $c3 += $communicationReport[$value->id][5];

                                $t1 += $trainingReport[$value->id][1];
                                $t2 += $trainingReport[$value->id][2];
                                $t3 += $trainingReport[$value->id][5];

                                $s1 += $safetydaysReport[$value->id][1];
                                $s2 += $safetydaysReport[$value->id][2];
                                $s3 += $safetydaysReport[$value->id][5];

                                $sa1 += $siteauditReport[$value->id][1];
                                $sa2 += $siteauditReport[$value->id][2];
                                $sa3 += $siteauditReport[$value->id][5];

                                $inc1 += $incidentReport[$value->id][1];
                                $inc2 += $incidentReport[$value->id][2];
                                $inc3 += $incidentReport[$value->id][5];

                                $pa1 += $ppeauditReport[$value->id][1];
                                $pa2 += $ppeauditReport[$value->id][2];
                                $pa3 += $ppeauditReport[$value->id][5];


                                $so1 += $safetyobsReport[$value->id][1];
                                $so2 += $safetyobsReport[$value->id][2];
                                $so3 += $safetyobsReport[$value->id][5];

                                /* $sol1 += $safeyObsLIneFunc[$value->id][1];
                                  $sol2 += $safeyObsLIneFunc[$value->id][2];
                                  $sol3 += $safeyObsLIneFunc[$value->id][5]; */
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="tfclas" style="font-weight: bold;">
                            <td bgcolor="yellow" class="userth2">Total</td>  
                            <td align="center"><?php echo $inc1; ?></td>
                            <td align="center"><?php echo $inc2; ?></td>
                            <td align="center"><?php echo $inc3 ?></td>

                            <td align="center"><?php echo $sa1; ?></td>
                            <td align="center"><?php echo $sa2; ?></td>
                            <td align="center"><?php echo $sa3; ?></td>

                            <td align="center"><?php echo $pa1; ?></td>
                            <td align="center"><?php echo $pa2; ?></td>
                            <td align="center"><?php echo $pa3; ?></td>

                            <td align="center"><?php echo $t1; ?></td>
                            <td align="center"><?php echo $t2; ?></td>
                            <td align="center"><?php echo $t3; ?></td>

                            <td align="center"><?php echo $w1; ?></td>
                            <td align="center"><?php echo $w2; ?></td>
                            <td align="center"><?php echo $w3; ?></td>

                            <td align="center"><?php echo $c1; ?></td>
                            <td align="center"><?php echo $c2; ?></td>
                            <td align="center"><?php echo $c3; ?></td>

                            <td align="center"><?php echo $so1; ?></td>
                            <td align="center"><?php echo $so2; ?></td>
                            <td align="center"><?php echo $so3; ?></td>

                            <td align="center"><?php echo $s1; ?></td>
                            <td align="center"><?php echo $s2; ?></td>
                            <td align="center"><?php echo $s3; ?></td>

                           <!-- <td><?php echo $sol1; ?></td>
                            <td><?php echo $sol2; ?></td>
                            <td><?php echo $sol3; ?></td>-->
                        </tr>
                        <tr class="tfclas">
                            <td  style="font-weight: bold;font-size:15px;" bgcolor="#FF9F08" class="userth2">Grand total</td>
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($inc1 + $inc2 + $inc3); ?></td>
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($sa1 + $sa2 + $sa3); ?></td>
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($pa1 + $pa2 + $pa3); ?></td>
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo $t1 + $t2 + $t3; ?></td>  
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo $w1 + $w2 + $w3; ?></td>
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo $c1 + $c2 + $c3; ?></td>
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($so1 + $so2 + $so3); ?></td>                  
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($s1 + $s2 + $s3); ?></td>
                          <!--  <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($sol1 + $sol2 + $sol3); ?></td>-->
                        </tr>
                    </tfoot>
                </table>
            </div>
                </div>  
            </div>
        </div>
    </div>
    <hr />
</div>
<?php $controller->get_footer(); ?>
</body>
</html>
<script>
    
    function printDiv()
    {
        var divToPrint = document.getElementById('parentnew');
        var newWin = window.open('', 'Print-Window');
        newWin.document.open("", "PRINT MAP", "width=100%,height=100%,top=0,left=0,toolbar=no,scrollbars=no,status=no,resizable=no");
        newWin.document.write('<html><head><style>tfoot tr td{position: relative !important;bottom:0 !important;} tr th{position: relative !important;top:0 !important;} .newhead{display:block !important;}.userth {position: relative !important;top:0 !important;left:0 !important;} .news { position: relative !important;left:0 !important; } </style></head><body style="font-size:12px;" onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();
        setTimeout(function () {
            newWin.close();
        }, 10);
    }
</script>
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>

<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
<script type="text/javascript">

                            jQuery.datetimepicker.setLocale('en');
                            jQuery('.datetimepicker').datetimepicker({
                                timepicker: false,
                                scrollMonth: false,
                                scrollInput: false,
                                format: 'Y-m-d',
                                step: 5
                            });
                          
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