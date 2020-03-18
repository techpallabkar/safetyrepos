<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("NodalController");
$controller->doAction();
$beanUi = $controller->beanUi;
$questionNodalData  =  $beanUi->get_view_data('questionNodalData');
$controller->get_header();
?>

<div class="container1" style="min-height: 800px;">
    <h1 class="heading"> List Question Set Nodal Manage </h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
   <table class="table table-bordered table-condensed table-responsive">
        <thead>
            <tr class="bg-primary">
                <th width="5%">Sl.No</th>
                <th width="25%">Question Set</th>
                <th width="25%">Division Department Tree</th>
                <th width="25%">Name</th>
                <th width="15%">Email</th>
                <th width="15%">Phone No.</th>
                <th align="center" width="5%">Action</th>
            </tr>
            <?php
            if( !empty( $questionNodalData ) ) {
            foreach ($questionNodalData as $key => $rowdata) {
                echo '<tr>'
                . '<td align="center">' . ($key + 1) . '</td>'
                . '<td align="center">' .  (($rowdata->subheading != "") ? ($rowdata->subheading) : "")  . '</td>'
                . '<td align="center">' .  (($rowdata->tree_division_id != "") ? ($rowdata->tree_division_name) : "")  . '</td>'
                . '<td align="center">' . (($rowdata->full_name != "") ? ($rowdata->full_name) : "") . '</td>'
                . '<td align="center">' . (($rowdata->email != "") ? ($rowdata->email) : "") . '</td>'
                . '<td align="center">' . (($rowdata->mobile_no != "") ? ($rowdata->mobile_no) : "") . '</td>'; 
                echo '</td><td align="center"><a href="add.php?editid=' . $rowdata->id . '" class="btn btn-success btn-xs"></i>Edit</a></td>'
                . '</tr>';
            } } else {
                    echo '<tr><td class="text-danger">Records not available.</td></tr>';
                }
            ?>
        </thead>

    </table>

</div>

<?php $controller->get_footer(); ?>


</body>
</html>
