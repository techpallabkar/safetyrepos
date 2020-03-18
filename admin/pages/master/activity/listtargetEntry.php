
<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ReportController");
$controller->doAction();
$beanUi             =   $controller->beanUi;
$page               =   $beanUi->get_view_data("page");
$allFinancialYear   =   $beanUi->get_view_data("allFinancialYear");
$controller->get_header();
$site_root_url = dirname(url());
?>
<div class="container1">
    <h1 class="heading" style="border-bottom:3px solid #ff9f08;">VIEW ALL TARGET ENTRY
    <a href="targetEntry.php" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a>
    </h1> 
    <div id="show_message"><?php echo $beanUi->get_message(); ?></div>
    <table class="table table-bordered table-condensed" style="width:50%;">
        <thead class="bg-primary">
            <tr>
                <th width="5%">SL.NO</th>
                <th>FINANCIAL YEAR</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($allFinancialYear)) {
                $slno=0;
                foreach ($allFinancialYear as $key => $value) {
                   
                    if ($value->dataCount > 0) {
                         $slno++;
                    echo '<tr>
                    <td class="text-center">' . ($slno) . '.</td>
                    <td class="text-center">' . ($value->financial_year) . '</td>
                    <td class="text-center">';
                   
                        echo '<a href="edittargetEntry.php?fy=' . ($value->id) . '&mode=edit" class="btn btn-primary btn-xs">Edit</a>';
                  
                    echo '</td>
                    </tr>';
                    }
                }
            }
            ?>
        </tbody>
    </table>

    <hr /> 

</div>
<?php $controller->get_footer(); ?>

</body>
</html>