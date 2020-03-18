<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("StatisticController");
$controller->doAction();
$beanUi = $controller->beanUi;
$allStatData = $beanUi->get_view_data("allStatData");
$statistictype      = $beanUi->get_view_data( "statistictype" );
$statisticname      = $beanUi->get_view_data( "statisticname" );
$site_root_url = dirname(url());
$controller->get_header();
?>

<div class="container1">
    <h1 class="heading"><?php echo $statisticname; ?> <a href="add.php?type=<?php echo $statistictype; ?>" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a></h1>
    <div class="holder2 col-md-12">
<?php echo $beanUi->get_message(); ?>
    </div>
    <table class="table table-bordered table-condensed table-responsive">
        <thead>
            <tr class="bg-primary">
                <th width="6%">SL.NO</th>
                <th>MONTH</th>
                <th>YEAR</th>
                <th width="8%">STATUS</th>
                <th width="8%">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(!empty($allStatData)) {
                foreach ($allStatData as $key => $value) {
                    $month = date('F', mktime(0,0,0,$value->month, 1, date('Y')));
                    echo '<tr>
                            <td align="center">'.($key+1).'.</td>
                            <td align="center">'.$month.'</td>
                            <td align="center">'.$value->year.'</td>
                            <td align="center">'.(($value->status == 1) ? '<a href="#" title="Click to make inactive" class="btn btn-info btn-xs"><i class="fa fa-check"></i> Active</a>' : '<a title="Click to make active" href="index.php?type='.$value->statistical_master_id.'&month='.$value->month.'&year='.$value->year.'" class="btn btn-danger btn-xs"><i class="fa fa-times" ></i> Inactive</a>').'
                            <td align="center"><a title="Edit" href="add.php?type='.$value->statistical_master_id.'&month='.$value->month.'&year='.$value->year.'&action=edit" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i> Edit</a></td>    
                                </td>
                        </tr>';
                }
            }
            ?>
            
        </tbody>  
    </table>
</div>
<?php $controller->get_footer(); ?>
</body>
</html>
