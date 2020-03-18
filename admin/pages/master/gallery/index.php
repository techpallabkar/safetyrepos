<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("GalleryController");
$controller->doAction();
$beanUi = $controller->beanUi;
$galleryData = $beanUi->get_view_data('galleryData');
$controller->get_header();
?>
<div class="container1" style="min-height: 800px;">
    <h1 class="heading">View Gallery
        <a href="add.php" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a>
    </h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <table class="table table-bordered table-condensed table-responsive">
        <thead>
            <tr class="bg-primary">
                <th>Sl.No</th>
                <th>Category name</th>
                <th>Caption</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php
            foreach ($galleryData as $key => $rowdata) {
                echo '<tr>'
                . '<td align="center">' . ($key + 1) . '.</td>'
                . '<td>' . $rowdata->name . '</td>'
                . '<td>' . $rowdata->caption . '</td>'
                . '<td align="center">' .
                '<img data-toggle="modal" data-target="#myModal' . $rowdata->id . '" class="thumb-image" width="60" alt="" src="' . site_url($rowdata->image_path) . '" />'
                . '<!--Modal--><div id="myModal' . $rowdata->id . '" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">' . $rowdata->caption . '</h4>
                            </div>
                            <div class="modal-body">
                              <img class="thumb-image" alt="" width="100%" src="' . site_url($rowdata->image_path) . '" />
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>'
                . '</td>'
                . '<td align="center"><a href="editGallery.php?editid=' . $rowdata->id . '" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="index.php?deltid=' . $rowdata->id . '" class="btn btn-info btn-xs btn-danger" onclick="return confirm(\'Confirm delete.\');" ><i class="fa fa-trash"></i> Delete</a></td>'
                . '</tr>';
            }
            ?>
        </thead>
    </table>
</div>
<?php $controller->get_footer(); ?>
</body>
</html>
