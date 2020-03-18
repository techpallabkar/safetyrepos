<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("GalleryController");
$controller->doAction();
$beanUi = $controller->beanUi;
$gallerycategory = $beanUi->get_view_data('gallerycategory');
$getdata = $beanUi->get_view_data("getdata");
$controller->get_header();
?>
<div class="container1" style="min-height: 800px;">
    <h1 class="heading">Edit Gallery Image</h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <form name="addCategory" id="addCategory" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $beanUi->get_view_data("id"); ?>" />
          <div class="holder required">
            <label for="category_id">Select Category</label>
            <select name="data[category_id]" id="category_id">
                <option value="" selected>Select Category</option>
                <?php foreach( $gallerycategory as $rowdata ) { 
                //echo '<option value="'.$rowdata->id.'">'.$rowdata->name.'</option>';
                    $selected  = (($rowdata->name == $getdata[0]->name) ? "SELECTED" : "");
                    echo '<option value="'.$rowdata->id.'" '.$selected.'>'.$rowdata->name.'</option>';
                } ?>
            </select>
            <div id="category_id_error"><?php echo $beanUi->get_error('category_id'); ?></div>
        </div>
        <br />
        <div class="holder">
                    <label for="image_path">Upload Image</label>
                    <input type="file" name="image_path" id="editImage1" class="imagepath" onChange="PreviewImage(1);" />
                    <input type="hidden" name="old_image" value="<?php echo $getdata[0]->image_path; ?>" />
                    <input type="hidden" name="old_image_thumb" value="<?php echo $getdata[0]->image_path_thumb; ?>" />
                    <img id="editPreview1" height="80px" width="70px" alt="Preview here" src="<?php echo site_url($getdata[0]->image_path); ?>">
                    <br />
                    <br />
                    <label for="caption">File Caption</label>
                    <input type="text" name="data[caption]" id="caption" class="datepicker" value="<?php echo $getdata[0]->caption ?>" />
                    <div id="date_of_event_error"><?php echo $beanUi->get_error('caption'); ?></div>
<?php echo FILESIZE_ERROR; ?>
               </div>
        <br />
        <hr />
        
        <div class="holder">
            <center>
                <input type="submit" value="Update" class="btn btn-sm btn-primary" />
                <a class="btn btn-sm btn-danger" href="index.php" >Cancel</a>
                <input type="hidden" name="_action" value="updateGalleryData" />
                <input type="hidden" name="data[id]"  id="AddMM_id" value="<?php echo $getdata[0]->id; ?>" />
            </center>
        </div>
    </form>
    
</div>
<?php $controller->get_footer(); ?>
</body>
</html>
<script type="text/javascript"> 
function PreviewImage(no) 
{ 
var oFReader = new FileReader();     
oFReader.readAsDataURL(document.getElementById("editImage"+no).files[0]); 
oFReader.onload = function (oFREvent)
 {           
 document.getElementById("editPreview"+no).src = oFREvent.target.result; 
 }; 
 } 
</script>