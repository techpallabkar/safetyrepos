<?php
/* 
 * MIS Report No. : 1
 * Name         :   MD Report Listing
 * Controller   :   mdReportList()
 * Dao          :   getmdreport
 * Created By pallab kar
 */
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$getmddata = ($beanUi->get_view_data("getmddata"));
$controller->get_header();
$site_root_url = dirname(url());
?>
<div class="container1">
    <?php echo $beanUi->get_message(); ?>
    <h1 class="heading">1. MD's Report List 
        <a href="mdReportN.php" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a><br>
        <a href="mdReportlist.php" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> MD's Report ( March,2017 to March,2018 )</a>
        <a href="mdReportlistNew.php" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> MD's Report ( April,2018 to March,2019 )</a>
        <a href="mdReportlistN.php" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> MD's Report ( April,2019 to Onwards )</a>
    </h1>
    <div class="wrapper2">   
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th width="5%">SL.NO</th>
                    <th>MONTH/YEAR</th>
                    <th>REPORT DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($getmddata)) {
                    foreach ($getmddata as $key => $rowdata) {
                        echo '<tr>
                        <td align="center">' . ($key + 1) . '.</td>
                        <td align="center">' . date("F,Y", strtotime("01-" . $rowdata->month_year)) . '</td>
                        <td align="center">' . $rowdata->report_date . '</td>
                        <td align="center">' . (($rowdata->status_id == 2) ?
                                "<a href='editmdReportN.php?mnthyr=" . $rowdata->month_year . "&id=" . $rowdata->id . "&mode=view' class='btn btn-primary btn-xs'>View</a>" :
                                "<a href='editmdReportN.php?mnthyr=" . $rowdata->month_year . "&id=" . $rowdata->id . "' class='btn btn-info btn-xs'>Edit</a>") . '</td>
                    </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
        <hr class="no-margin"/>
    </div>
</div>
<?php $controller->get_footer(); ?>
</body>
</html>
