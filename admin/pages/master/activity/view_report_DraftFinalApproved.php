
<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ReportController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$montharray = $beanUi->get_view_data("montharray");
$allActivity = $beanUi->get_view_data("allActivity");
$workshopReport = $beanUi->get_view_data("workshopReport");
$communicationReport = $beanUi->get_view_data("communicationReport");
$trainingReport = $beanUi->get_view_data("trainingReport");
$safetydaysReport = $beanUi->get_view_data("safetydaysReport");
$siteauditReport = $beanUi->get_view_data("siteauditReport");
$incidentReport = $beanUi->get_view_data("incidentReport");
$ppeauditReport = $beanUi->get_view_data("ppeauditReport");
$safetyobsReport = $beanUi->get_view_data("safetyobsReport");


$posts_paggin_html = $beanUi->get_view_data("posts_paggin_html");
$post_status = $beanUi->get_view_data("post_status");
$search_title = $beanUi->get_view_data("search_title");
$status_id = $beanUi->get_view_data("status_id");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$activity_type_id = $beanUi->get_view_data("activity_type_id");
$division_department = $beanUi->get_view_data("division_department");
$devition_names = $beanUi->get_view_data("devition_names");
$activity_participants = $beanUi->get_view_data("activity_participants");
$participants_list = $beanUi->get_view_data("participants_list");
$activity = $beanUi->get_view_data("activity");
$type_images = array("image/jpg", "image/jpeg", "image/png");
$type_videos = array("video/mp4", "video/quicktime");
$pdf = array("application/pdf");
$activities = get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);
$controller->get_header();
$site_root_url = dirname(url());
?>
<script type="text/javascript" src="<?php echo url('assets/js/ExportExcel/jquery.table2excel.js') ?>"></script>
<script src="<?php echo url("assets/js/tableHeadFixer.js"); ?>"></script>
<style>
    #parent {
        height: 500px;
    }
</style>

<script>
    $(document).ready(function () {
        $("#fixTable").tableHeadFixer({'foot': true, 'head': true});
    });
</script>
<style>
    #fixTable thead tr th {border:1px solid #ddd;}
    #fixTable {height:500px;}

</style>
<div class="container1">

<?php echo $beanUi->get_message(); ?>
    <h1 class="heading">25. Monthly Report (Draft,Final Submit And Approved)
    <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>  
 Export to Excel</button>
    </h1> 
    <div id="main-content" class="mh-content mh-home-content clearfix">
        <form action="" method="post" id="fyr" enctype="multipart/form-data">
        
        <label class="col-sm-1">From</label>
        <div class="col-sm-3">
            
            <input type="text" class="month_year_picker" name="from_date" autocomplete="off" value="<?php echo @$_REQUEST["from_date"] ?>" style="width:100%;" /></div>
         <label class="col-sm-1">To</label>
        <div class="col-sm-3">
       <input type="text" class="month_year_picker" name="to_date" autocomplete="off" value="<?php echo @$_REQUEST["to_date"] ?>" style="width:100%;" />
        </div>
        <div class="col-sm-2">
            <input name="_action" value="Search" type="hidden">
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Search</button>
            <a href="view_report.php?st=<?php echo @$_REQUEST["st"]; ?>" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
            <hr />
            
    <div id='TBL_EXPORT'>
    <div id="parent">
        
<?php// if ($_REQUEST["st"] == "A") { ?>
           
<!--             <table border="1" id="fixTable" class="table table-bordered table-condensed table-striped" style="font-size: 11px;">
                    <thead>
                        <tr class="bg-primary" >
                            <th colspan="9" >
                                Monthly Report ( Approved ) -->
                                <?php //if(@$_REQUEST["from_date"] != "" && @$_REQUEST["to_date"] != "") { ?>
                                <?php //echo date("F,Y",strtotime(@'01-'.$_REQUEST["from_date"])); ?>  
                                    <?php //echo date("F,Y",strtotime(@'01-'.$_REQUEST["to_date"])); ?>    
                                <?php //} ?>
<!--                            </th>                           
                        </tr>
                        <tr class="bg-primary">
                            <th>Year / Month</th>-->
                            <?php
//                            foreach ($allActivity as $rowdata) {
//                                if ($rowdata->id != 9 && $rowdata->id != 10) {
//                                    echo '<th width="15%">' . $rowdata->activity_name . '</th>';
//                                }
//                            }
                            ?>
<!--                        </tr>
                        <tr>
                            <td width="7%">Status</td>                           
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#AFF26D">Approved</td>
                        </tr>
                    </thead>
                    <tbody>-->
                        <?php
//                        $total1 = $total2 = $total3 = $total4 = $total5 = $total6 = $total7 = $total8 = 0;
//                        $total11 = $total22 = $total33 = $total44 = $total55 = $total66 = $total77 = $total88 = 0;
//                        $total111 = $total222 = $total333 = $total444 = $total555 = $total666 = $total777 = $total888 = 0;
//                        foreach ($montharray as $key => $value) {
//                            if ($value != 0) {
                                ?>
<!--                                <tr>
                                    <td ><?php echo date("Y,F", strtotime($value . "-01")); ?></td>
                                    <td align="center"><?php echo $incidentReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $siteauditReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $ppeauditReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $trainingReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $workshopReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $communicationReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $safetyobsReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $safetydaysReport[$value][5]; ?></td>
                                </tr>-->
                                <?php
//                                $total555 += $workshopReport[$value][5];
//                                $total666 += $communicationReport[$value][5];
//                                $total444 += $trainingReport[$value][5];
//                                $total888 += $safetydaysReport[$value][5];
//                                $total222 += $siteauditReport[$value][5];
//                                $total111 += $incidentReport[$value][5];
//                                $total333 += $ppeauditReport[$value][5];
//                                $total777 += $safetyobsReport[$value][5];
//                            }
//                        }
                        ?>

<!--                    </tbody>
                    <tfoot style="backgroung-color:#fff;">
                        <tr style="font-weight:bold;">
                            <td bgcolor="#FFFF00">Total</td>
                            <td align="center"><?php echo $total111; ?></td>
                            <td align="center"><?php echo $total222; ?></td>
                            <td align="center"><?php echo $total333; ?></td>
                            <td align="center"><?php echo $total444; ?></td>
                            <td align="center"><?php echo $total555; ?></td>
                            <td align="center"><?php echo $total666; ?></td>
                            <td align="center"><?php echo $total777; ?></td>
                            <td align="center"><?php echo $total888; ?></td>
                        </tr>-->
<!--                        <tr>
                            <td bgcolor="#FF9F08" style="font-weight: bold;font-size:15px;width:15%;">Grand Total</td>
                            <td style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total1 + $total11 + $total111); ?></td> 
                            <td style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total2 + $total22 + $total222); ?></td> 
                            <td style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total3 + $total33 + $total333); ?></td> 
                            <td style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total4 + $total44 + $total444); ?></td> 
                            <td style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total5 + $total55 + $total555); ?></td> 
                            <td style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total6 + $total66 + $total666); ?></td> 
                            <td style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total7 + $total77 + $total777); ?></td> 
                            <td style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total8 + $total88 + $total888); ?></td> 
                        </tr>
                    </tfoot>
                </table>-->
            

<?php// } else { ?>
                <table border="1" id="fixTable" class="table table-bordered table-condensed table-striped" style="font-size: 11px;">
                    <thead>
                       <tr class="bg-primary" >
                            <th colspan="25" >
                                Monthly Report 
                                <?php if(@$_REQUEST["from_date"] != "" && @$_REQUEST["to_date"] != "") { ?>
                                : <?php echo date("F,Y",strtotime(@'01-'.$_REQUEST["from_date"])); ?> - 
                                    <?php echo date("F,Y",strtotime(@'01-'.$_REQUEST["to_date"])); ?>    
                                <?php } ?>
                            </th>                           
                        </tr>
                        <tr class="bg-primary">
                            <th>Year / Month</th>
                            <?php
                            foreach ($allActivity as $rowdata) {
                                if ($rowdata->id != 9 && $rowdata->id != 10) {
                                    echo '<th width="15%" colspan="3">' . $rowdata->activity_name . '</th>';
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <td width="7%">Status</td>
                            <td bgcolor="#FCC777">Draft</td>
                            <td bgcolor="#F9F683">Final Submit</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#FCC777">Draft</td>
                            <td bgcolor="#F9F683">Final Submit</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#FCC777">Draft</td>
                            <td bgcolor="#F9F683">Final Submit</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#FCC777">Draft</td>
                            <td bgcolor="#F9F683">Final Submit</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#FCC777">Draft</td>
                            <td bgcolor="#F9F683">Final Submit</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#FCC777">Draft</td>
                            <td bgcolor="#F9F683">Final Submit</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#FCC777">Draft</td>
                            <td bgcolor="#F9F683">Final Submit</td>
                            <td bgcolor="#AFF26D">Approved</td>
                            <td bgcolor="#FCC777">Draft</td>
                            <td bgcolor="#F9F683">Final Submit</td>
                            <td bgcolor="#AFF26D">Approved</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total1 = $total2 = $total3 = $total4 = $total5 = $total6 = $total7 = $total8 = 0;
                        $total11 = $total22 = $total33 = $total44 = $total55 = $total66 = $total77 = $total88 = 0;
                        $total111 = $total222 = $total333 = $total444 = $total555 = $total666 = $total777 = $total888 = 0;
                        foreach ($montharray as $key => $value) {
                            if ($value != 0) {
                                ?>
                                <tr>
                                    <td ><?php echo date("Y,F", strtotime($value . "-01")); ?></td>
                                    <td align="center"><?php echo $incidentReport[$value][1]; ?></td>
                                    <td align="center"><?php echo $incidentReport[$value][2]; ?></td>
                                    <td align="center"><?php echo $incidentReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $siteauditReport[$value][1]; ?></td>
                                    <td align="center"><?php echo $siteauditReport[$value][2]; ?></td>
                                    <td align="center"><?php echo $siteauditReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $ppeauditReport[$value][1]; ?></td>
                                    <td align="center"><?php echo $ppeauditReport[$value][2]; ?></td>
                                    <td align="center"><?php echo $ppeauditReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $trainingReport[$value][1]; ?></td>
                                    <td align="center"><?php echo $trainingReport[$value][2]; ?></td>
                                    <td align="center"><?php echo $trainingReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $workshopReport[$value][1]; ?></td>
                                    <td align="center"><?php echo $workshopReport[$value][2]; ?></td>
                                    <td align="center"><?php echo $workshopReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $communicationReport[$value][1]; ?></td>
                                    <td align="center"><?php echo $communicationReport[$value][2]; ?></td>
                                    <td align="center"><?php echo $communicationReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $safetyobsReport[$value][1]; ?></td>
                                    <td align="center"><?php echo $safetyobsReport[$value][2]; ?></td>
                                    <td align="center"><?php echo $safetyobsReport[$value][5]; ?></td>
                                    <td align="center"><?php echo $safetydaysReport[$value][1]; ?></td>
                                    <td align="center"><?php echo $safetydaysReport[$value][2]; ?></td>
                                    <td align="center"><?php echo $safetydaysReport[$value][5]; ?></td>
                                </tr>
                                <?php
                                $total5 += $workshopReport[$value][1];
                                $total6 += $communicationReport[$value][1];
                                $total4 += $trainingReport[$value][1];
                                $total8 += $safetydaysReport[$value][1];
                                $total2 += $siteauditReport[$value][1];
                                $total1 += $incidentReport[$value][1];
                                $total3 += $ppeauditReport[$value][1];
                                $total7 += $safetyobsReport[$value][1];

                                $total55 += $workshopReport[$value][2];
                                $total66 += $communicationReport[$value][2];
                                $total44 += $trainingReport[$value][2];
                                $total88 += $safetydaysReport[$value][2];
                                $total22 += $siteauditReport[$value][2];
                                $total11 += $incidentReport[$value][2];
                                $total33 += $ppeauditReport[$value][2];
                                $total77 += $safetyobsReport[$value][2];
                                
                                $total555 += $workshopReport[$value][5];
                                $total666 += $communicationReport[$value][5];
                                $total444 += $trainingReport[$value][5];
                                $total888 += $safetydaysReport[$value][5];
                                $total222 += $siteauditReport[$value][5];
                                $total111 += $incidentReport[$value][5];
                                $total333 += $ppeauditReport[$value][5];
                                $total777 += $safetyobsReport[$value][5];
                            }
                        }
                        ?>

                    </tbody>
                    <tfoot style="backgroung-color:#fff;">
                        <tr style="font-weight:bold;">
                            <td bgcolor="#FFFF00">Total</td>
                            <td align="center"><?php echo $total1; ?></td>
                            <td align="center"><?php echo $total11; ?></td>
                            <td align="center"><?php echo $total111; ?></td>
                            <td align="center"><?php echo $total2; ?></td>
                            <td align="center"><?php echo $total22; ?></td>
                            <td align="center"><?php echo $total222; ?></td>
                            <td align="center"><?php echo $total3; ?></td>
                            <td align="center"><?php echo $total33; ?></td>
                            <td align="center"><?php echo $total333; ?></td>
                            <td align="center"><?php echo $total4; ?></td>
                            <td align="center"><?php echo $total44; ?></td>
                            <td align="center"><?php echo $total444; ?></td>
                            <td align="center"><?php echo $total5; ?></td>
                            <td align="center"><?php echo $total55; ?></td>
                            <td align="center"><?php echo $total555; ?></td>
                            <td align="center"><?php echo $total6; ?></td>
                            <td align="center"><?php echo $total66; ?></td>
                            <td align="center"><?php echo $total666; ?></td>
                            <td align="center"><?php echo $total7; ?></td>
                            <td align="center"><?php echo $total77; ?></td>
                            <td align="center"><?php echo $total777; ?></td>
                            <td align="center"><?php echo $total8; ?></td>
                            <td align="center"><?php echo $total88; ?></td>
                            <td align="center"><?php echo $total888; ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#FF9F08" style="font-weight: bold;font-size:15px;width:15%;">Grand Total</td>
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total1 + $total11 + $total111); ?></td> 
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total2 + $total22 + $total222); ?></td> 
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total3 + $total33 + $total333); ?></td> 
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total4 + $total44 + $total444); ?></td> 
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total5 + $total55 + $total555); ?></td> 
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total6 + $total66 + $total666); ?></td> 
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total7 + $total77 + $total777); ?></td> 
                            <td colspan="3" style="text-align: center;font-weight: bold;font-size:15px;"><?php echo ($total8 + $total88 + $total888); ?></td> 
                        </tr>
                    </tfoot>
                </table>

<?php// } ?>
        </div>
    </div>
         </div>
    <hr />

</div>
<?php $controller->get_footer(); ?>
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

</body>
</html>
