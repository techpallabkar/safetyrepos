<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("GalleryController");
$controller->doAction();
$beanUi = $controller->beanUi;
$gallerycategory = $beanUi->get_view_data('gallerycategory');
$controller->get_header();
?>
<div class="container1" style="min-height: 800px;">
    <h1 class="heading">Add Gallery Image</h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <form name="addCategory" id="addCategory" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $beanUi->get_view_data("id"); ?>" />
          <div class="holder required">
            <label for="category_id">Select Category</label>
            <select name="data[category_id]" id="category_id">
                <option value="" selected>Select Category</option>
                <?php foreach( $gallerycategory as $rowdata ) { 
                echo '<option value="'.$rowdata->id.'">'.$rowdata->name.'</option>';
                } ?>
            </select>
            <div id="category_id_error" style="color:red"><?php echo $beanUi->get_error('category_id'); ?></div>
        </div>
        <br />
        <div class="holder">
                    <label for="image_path">Upload Image</label>
                    <input type="file" name="image_path" id="editImage1" class="imagepath" onChange="PreviewImage(1);" />
                    <img id="editPreview1" height="80px" width="70px" alt="" src="">
                    <div id="category_id_error1" style="color:red"><?php ?></div>
                    <br />
                    <br />
                    <label for="caption">File Caption</label>
                    <input type="text" name="data[caption]" id="date_of_event" class="datepicker" value="<?php echo $beanUi->get_view_data( "caption" ); ?>" />
                    <div id="date_of_event_error" style="color:red"><?php echo $beanUi->get_error('caption'); ?></div>

               </div>
        <br />
        <hr />
        
        <div class="holder">
            <center>
                <input type="submit" value="Add" class="btn btn-sm btn-primary" />
                <a class="btn btn-sm btn-danger" href="index.php" >Cancel</a>
                <input type="hidden" name="_action" value="addGalleryData" />
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
<script type="text/javascript">
    $(document).ready(function(){
        $("#addCategory").submit(function(){
            var category_id =$("#category_id").val();
            var editImage1 =$("#editImage1").val();
            var date_of_event =$("#date_of_event").val();
            var error_data = 0;

			
            if(category_id == "" || category_id == "undefined")
            {
                $("#category_id_error").html("<div>* Category is required</div>");
                $("#category_id_error1").html("<div>* Image is required</div>");
                $("#date_of_event_error").html("<div>* Caption is required</div>");
                
                error_data++;
                return false;
            }
else if(editImage1 == "" || editImage1 == "undefined")
            {
                $("#category_id_error").hide("<div></div>");
                $("#category_id_error1").html("<div>* Image is required</div>");
                error_data++;
                return false;
            }
            

 else if(date_of_event == "" || date_of_event == "undefined")
            {
                 $("#category_id_error1").hide("<div></div>");
                $("#date_of_event_error").html("<div>* Caption is required</div>");
                error_data++;
                return false;
            }

           
            
            if(error_data == 0)
            {
                return true;
            }
            
            
        });
    });
    
</script>