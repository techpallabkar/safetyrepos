<?php
/* 
 * MIS Report No. : 2
 * Name         :   MCM Report Listing
 * Controller   :   mcmReportLIst()
 * Dao          :   getmcmreport
 * Created By Anima Mahato
 */
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("MisController");
$controller->doAction();
$beanUi = $controller->beanUi;
$getmcmdata = ($beanUi->get_view_data("getmcmdata"));
$controller->get_header();
$site_root_url = dirname(url());
?>
<div class="container1">
    <?php echo $beanUi->get_message(); ?>
    <h1 class="heading">2. MCM Report List 
        <!--<a href="mcmReport.php" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a>-->
        <a href="mcmReportNew.php" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a><br>
        <a href="mcmReportlist.php" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> MCM Report (September,2017 to March,2018)</a>
        <a href="mcmReportlistNew.php" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> MCM Report (April,2018 to onwards)</a>
    </h1>    
    <div class="wrapper2">   
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th width="5%">SL.NO</th>
                    <th>MONTH/YEAR</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($getmcmdata)) {
                    foreach ($getmcmdata as $key => $rowdata) {
                        echo '<tr>
                        <td align="center">' . ($key + 1) . '.</td>
                        <td align="center">' . date("F,Y", strtotime("01-" . $rowdata->month_year)) . '</td>
                        <td align="center">' . (($rowdata->status_id == 2) ?
                                "<a href='editmcmReport.php?mnthyr=" . $rowdata->month_year . "&id=" . $rowdata->id . "&mode=view' class='btn btn-primary btn-xs'>View</a>" :
                                "<a href='editmcmReport.php?mnthyr=" . $rowdata->month_year . "&id=" . $rowdata->id . "' class='btn btn-info btn-xs'>Edit</a>") . '</td>
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
