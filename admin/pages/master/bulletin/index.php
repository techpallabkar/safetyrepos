<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("BulletinController");
$controller->doAction();
$beanUi = $controller->beanUi;
$bulletinData  =  $beanUi->get_view_data('bulletinData');
$controller->get_header();
?>

<div class="container1" style="min-height: 800px;">
    <h1 class="heading"> List Bulletin  <a href="add.php" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a></h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
   <table class="table table-bordered table-condensed table-responsive">
        <thead>
            <tr class="bg-primary">
                <th>Sl.No</th>
                <th>Bulletin Date</th>
                <th>Department</th>
                <th width="50%">Description</th>
                <th>Status</th>
                <th align="center" width="15%">Action</th>
            </tr>
            <?php
            if( !empty( $bulletinData ) ) {
            foreach ($bulletinData as $key => $rowdata) {
             //   $var =(strlen($rowdata->content) > 200 ) ? "..." : "";
                echo '<tr>'
                . '<td align="center">' . ($key + 1) . '</td>'
                . '<td align="center">' .  (($rowdata->bulletin_date != "") ? date("d-m-Y",strtotime($rowdata->bulletin_date)) : "")  . '</td>'
                . '<td align="center">' . $rowdata->department . '</td>'
                . '<td align="left">' . substr(strip_tags($rowdata->description),0,100);
                if(strlen($rowdata->description) > 100 ) { echo "..."; }
                echo '</td><td>';
                if( $rowdata->status == 1 ) {
                echo '<a class="btn btn-info btn-xs" href="index.php?statusid='.$rowdata->id.'"><i class="fa fa-check"></i> Active</a>';
                } else {
                echo '<a class="btn btn-danger btn-xs" href="index.php?statusid='.$rowdata->id.'" class="text-danger"><i class="fa fa-times"></i> Inactive</a>';
                }
                echo '</td><td align="center"><a href="add.php?editid=' . $rowdata->id . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="index.php?deltid=' . $rowdata->id . '" onclick="return confirm(\'Confirm delete.\');" class="btn btn-info btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a></td>'
                . '</tr>';
            } }
 else {
     echo '<tr><td class="text-danger">Records not available.</td></tr>';
 }
            ?>
        </thead>

    </table>

</div>

<?php $controller->get_footer(); ?>


</body>
</html>
