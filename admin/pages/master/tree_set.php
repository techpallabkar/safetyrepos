<?php
if (file_exists("../lib/var.inc.php"))
    require_once( "../lib/var.inc.php" );
$dashCtr = load_controller("DashboardController");
$dashCtr->doAction();
$beanUi         = $dashCtr->beanUi;
$welcome_text   = $beanUi->get_view_data("welcome_text");
$allposts       = $beanUi->get_view_data("allposts");
$treeset_data   = $beanUi->get_view_data("treeset_data");
$condition   = $beanUi->get_view_data("condition");
$dashCtr->get_header();
?>
<div class="container1">
    <h1 class="heading"><?php echo $welcome_text ?></h1>
    <div class="row">
        <form method="post" action="">
            <select name="condition">
                <option value="">-select-</option>
                <option value="1" <?php if($condition == 1) { echo "selected"; } ?>>1. Tree - P-SET, Set type - C-SET </option>
                <option value="2" <?php if($condition == 2) { echo "selected"; } ?>>2. Tree - C-SET, Set type - P-SET </option>
                <option value="3" <?php if($condition == 3) { echo "selected"; } ?>>3. Tree - P-SET, Set type - P+C-SET</option>
                <option value="4" <?php if($condition == 4) { echo "selected"; } ?>>4. Tree - C-SET, Set type - P+C-SET</option>
                <option value="5" <?php if($condition == 5) { echo "selected"; } ?>>5. Tree - Call Center, Set type - C-SET</option>
                <option value="6" <?php if($condition == 6) { echo "selected"; } ?>>6. Tree - Call Center, Set type - P+C-SET</option>
                <option value="7" <?php if($condition == 7) { echo "selected"; } ?>>7. Tree - '', Set type - P+C-SET</option>
                <option value="8" <?php if($condition == 8) { echo "selected"; } ?>>8. Tree - '', Set type - P-SET</option>
                <option value="9" <?php if($condition == 9) { echo "selected"; } ?>>9. Tree - '', Set type - C-SET</option>
            </select>
            
            <input type="hidden" name="action" value="Search" />
            <input type="submit" name="B1" class="btn btn-primary btn-sm" value="Filter" />
        </form>
        <hr />
        <?php
        if (!empty($allposts)) {
            $class = 'even';
            foreach (@$allposts as $row) {
                $class = ($class == 'even') ? 'odd' : 'even';
                if ($row->id != 9 && $row->id != 10) {
                    $alldata = $treeset_data[$row->id];
                    ?>
                    <div class="col-md-12 col-sm-6"  style="margin-bottom: 20px;">
                        <div class="card">
                            <div class="card-body no-padding border-dark bg-white" style="min-height:150px;">
                                
                                <table class="table table-bordered" >
                                    <tr class="bg-primary"><th colspan="5"><?php echo $row->activity_name; ?></th></tr>
                                    <tr>
                                        <th>ID</th>
                                        <th>Division Department Tree</th>
                                        <th>SET TYPE</th>
                                        <th>STATUS</th>
                                        <th>CREATED BY</th>
                                    </tr>
                                    <?php
                                    
                                    
                                                        foreach ($alldata as $key => $value) {
                                                            if(@$condition != '' && $value->mismatch == 1) {
                                                            echo '<tr class="bg-danger">'
                                                            . '<td>'.$value->id.'</td>'
                                                            . '<td>'.$value->tree_name.'</td>'
                                                            . '<td>'.$value->set_type.'</td>'
                                                            . '<td>'.$value->status_name.'</td>'
                                                            . '<td>'.$value->created_by_name.'</td>'
                                                                    . '</tr>';
                                                            } else if(@$condition == '' && $value->mismatch == 0) {
                                                                echo '<tr>'
                                                            . '<td>'.$value->id.'</td>'
                                                            . '<td>'.$value->tree_name.'</td>'
                                                            . '<td>'.$value->set_type.'</td>'
                                                            . '<td>'.$value->status_name.'</td>'
                                                            . '<td>'.$value->created_by_name.'</td>'
                                                                    . '</tr>';
                                                            }
                                                        }
                                    ?>
                                   
                                </table>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            }
        }
        ?>
    </div> 
</div>
<?php $dashCtr->get_footer(); ?>
</div>
</body>
</html>
