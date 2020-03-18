<?php
/* MIS Report No. : 19
 * Name         :   YTM activity Figure vis-a-vis YTM Target 
 * Controller   :   ytmactivityfigure()
 * Dao          :   getOfficerTraining,getSupervisorTraining,getPostCount,getSiteAuditCount
 * Created By Anima Mahato
 */

if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi             =   $controller->beanUi;
$majorActivities    =   $beanUi->get_view_data("majorActivities");
$get_date           =   $beanUi->get_view_data("arr_set_date");
//showPre($get_date);
$controller->get_header();
$site_root_url      =   dirname(url());
?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js") ?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.auto').autoNumeric('init');
    });
</script>
<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">19. YTM ACTIVITY FIGURE VIS-A-VIS YTM TARGET </h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <hr/>
    <form action="" method="post" id="mdform" enctype="multipart/form-data" style="<?php echo ( $mode == "view" ) ? 'display:none' : 'display:block'; ?>" >
        <div class="holder">
            <label for="job_stop_req_raisedx" style="width:15%;padding-top:5px;">Select Month-Year :</label>
            <input required="true" type="text" name="month_year" id="month_year_from" class="month_year_picker" value ="<?php echo @$get_date["month_year"]; ?>" style="width:250px;"  required/>
            <input type="hidden" name="_action" value="Create" />
            <button type="submit" name="B1" class="btn btn-primary btn-sm">Go</button>
            <a href="ytmactivityfigure.php" class="btn btn-danger btn-sm">Reset</a>                 
        </div>
        <hr class="no-margin"/>
    </form>
    <?php if (!empty($majorActivities)) { ?>
        <button type="button" id="ExportExcel" class="btn btn-success" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel</button>
        <div class="print1" style="float: right;">
            <input  type="button" class="btn btn-danger" value="Print / PDF" onclick="PrintDiv();" />
        </div>
        <div id='TBL_EXPORT'>
            <div class="print-friendly" id="divToPrint">
                <table border="1" class="table table-bordered table-bordered table-condensed" style="font-weight: bold;">
                    <thead class="bg-primary">
                        <tr><th colspan="12"><h4><b>YTM ACTIVITY FIGURE VIS-A-VIS YTM TARGET </b></h4></th></tr>
                        <tr>
                            <th width="6%" rowspan="2">Sl.No.</th>
                            <th width="30%" rowspan="2">ACTIVITIES</th>
                            <th colspan="3">YTM Total <?php
                                $CFY = explode("-", @$get_date['CFY']);
                                echo $CFY[0] . "-" . date("y", strtotime($CFY[1] . "-01-01"));
                                ?></th>
                            <th colspan="3">Target for <?php
                                $CFY = explode("-", @$get_date['CFY']);
                                echo $CFY[0] . "-" . date("y", strtotime($CFY[1] . "-01-01"));
                                ?></th>
                            <th width="10%" rowspan="2">% COMPLETED</th>
                            <th colspan="3">Last Year 
                                <?php
                                $PFY = explode("-", @$get_date['PFY']);
                                echo $PFY[0] . "-" . date("y", strtotime($PFY[1] . "-01-01"));
                                ?></th>
                        </tr>
                        <tr>
                            <th>GEN.</th>
                            <th>DIST.</th>
                            <th>TOTAL</th>
                            <th>GEN.</th>
                            <th>DIST.</th>
                            <th>TOTAL</th>
                            <th>GEN.</th>
                            <th>DIST.</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <?php
                    foreach ($majorActivities as $key => $value) {
                        $completed = 0;
                        $YTM_TOTAL = ($value->ytm_total_GEN + $value->ytm_total_DIST);
                        $TARGET_TOTAL = ($value->gen + $value->dist);
                        if ($YTM_TOTAL != 0) {
                            @$completed = round((($YTM_TOTAL / $TARGET_TOTAL) * 100), 2);
                        }
                        echo '<tr>
                            <td align="center" style="border-color:#000;">' . ($key + 1) . '.</td>
                            <td style="border-color:#000;">' . strtoupper($value->name) . '</td>
                            <td align="center" style="border-color:#000;" bgcolor="#e3ff96">' . ($value->ytm_total_GEN) . '</td>
                            <td align="center" style="border-color:#000;" bgcolor="#DDD9C3">' . ($value->ytm_total_DIST) . '</td>
                            <td align="center" style="border-color:#000;font-size:15px;" bgcolor="#B9CDE5">' . ($value->ytm_total_GEN + $value->ytm_total_DIST) . '</td>
                            <td align="center" style="border-color:#000;" bgcolor="#e3ff96">' . ($value->gen) . '</td>
                            <td align="center" style="border-color:#000;" bgcolor="#DDD9C3">' . ($value->dist) . '</td>
                            <td align="center" style="border-color:#000;font-size:15px;" bgcolor="#B9CDE5">' . ($value->gen + $value->dist) . '</td>
                            <td align="center" style="border-color:#000;font-size:15px;" bgcolor="#FCD5B5">' . ($completed) . '</td>
                            <td align="center" style="border-color:#000;" bgcolor="#e3ff96">' . ($value->last_year_total_GEN) . '</td>
                            <td align="center" style="border-color:#000;" bgcolor="#DDD9C3">' . ($value->last_year_total_DIST) . '</td>
                            <td align="center" style="border-color:#000;font-size:15px;" bgcolor="#B9CDE5">' . ($value->last_year_total_GEN + $value->last_year_total_DIST) . '</td>
                            </tr>';
                    }
                    ?>
                </table>
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

    $(document).ready(function () {
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
        mywindow.document.write('<html><head><title>MAJOR ACTIVITYS</title>');
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