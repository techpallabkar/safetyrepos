<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("CmsController");
$controller->doAction();
$beanUi = $controller->beanUi;
$messageData = $beanUi->get_view_data("messageData");
$singleData = $beanUi->get_view_data("singleData");
$controller->get_header();
?>

<div class="container1">
    <!--<h1 class="heading">Manage Messages</h1>-->
    <h1 class="heading">Manage Messages
        <a href="addManageMessages.php" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a>
    </h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
   
    <table class="table table-bordered table-condensed table-responsive">
        <thead>
            <tr class="bg-primary">
                <th> Sl.NO</th>
                <th>Content Type</th>
                <th>Description</th>
                <th>Status</th>
                <th width="15%">Action</th>
            </tr>
            <?php
            if(!empty($messageData)) {
            foreach ($messageData as $key => $rowdata) {
             //   $var =(strlen($rowdata->content) > 200 ) ? "..." : "";
                echo '<tr>'
                . '<td align="center">' . ($key + 1) . '.</td>'
                . '<td>' . $rowdata->contect_type . '</td>'
                . '<td>' . substr(strip_tags($rowdata->description),0,200). '</td>'
                . '<td align="center">';
                        if($rowdata->status == 1){
                            echo '<a  href="manageMessages.php?statusid=' . $rowdata->id . '" class="text-success"><i class="fa fa-check" style="font-size:24px;"></i></a>';   
                        }else{
                            echo '<a href="manageMessages.php?statusid=' . $rowdata->id . '"class="text-danger"><i class="fa fa-times" style="font-size:24px;"></i></a>';
                        }                       
                echo '</td>'
                        . '<td><a href="editManageMessages.php?editid=' . $rowdata->id . '" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
                    ?>
        <a onclick="return confirm('Delete Confirm ?');" href="manageMessages.php?deltid=<?php echo $rowdata->id; ?>" class="btn btn-info btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>
        <?php        echo '</td></tr>';
            } } else {
                echo '<tr><td  colspan="5" class="text-danger">No records available.</td></tr>';
            }
            ?>
        </thead>

    </table>


</div>

<?php $controller->get_footer(); ?>


</body>
</html>
