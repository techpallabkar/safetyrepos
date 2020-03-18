<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("CmsController");
$controller->doAction();
$beanUi = $controller->beanUi;
//$messageData = $beanUi->get_view_data("messageData");
//$singleData = $beanUi->get_view_data("singleData");
$controller->get_header();
?>

<div class="container1">
    <h1 class="heading">Add Messages</h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <form name="addManageMessages" id="addManageMessages" action="" method="post" enctype="multipart/form-data">
    <div class="holder required">
			<label for="contect_type">Title</label>
			<input type="text" name="data[contect_type]" id="title" value="<?php echo $beanUi->get_view_data( "contect_type" ); ?>" />
			<div id="title_error" style="color:red"><?php echo $beanUi->get_error( 'contect_type' ); ?></div>
		</div><br />
    <div class="holder required">
		<label for="image_path">Upload Image</label>
                <input type="file" name="image_path" id="image_path" class="imagepath" />
                <div id="title_error1" style="color:red"><?php ?></div>
                </div>
                <br>
    <div class="holder">
		<label for="image_path">Upload File</label>
                <input type="file" name="file_path" id="file_path" class="filepath" />               
                </div>
                <br>
    <div class="holder required">
			<label for="description">Message</label>
                        <textarea type="text" name="data[description]" id="mess" value="<?php echo $beanUi->get_view_data( "description" ); ?>" /></textarea>
			<div id="title_error2" style="color:red"><?php echo $beanUi->get_error( 'description' ); ?></div>
		</div><br />
    <div class="holder">
                <center>
                    <input type="submit" value="Add" class="btn btn-sm btn-primary" />
                    <a class="btn btn-sm btn-danger" href="manageMessages.php" >Cancel</a>
                    <input type="hidden" name="_action" value="updateMessageData" />
                    <!--<input type="hidden" name="data[id]" id="AddMM_id"  />-->
                </center>
            </div>
    </from>


</div>

<?php $controller->get_footer(); ?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#addManageMessages").submit(function(){
            var title =$("#title").val();
            var image_path =$("#image_path").val();
            var mess =$("#mess").val();
            var error_data = 0;

			
            if(title == "" || title == "undefined")
            {
                $("#title_error").html("<div>* Title is required</div>");
                $("#title_error1").html("<div>* Image is required</div>");
                $("#title_error2").html("<div>* Message is required</div>");
                
                error_data++;
                return false;
            }
else if(image_path == "" || image_path == "undefined")
            {
                $("#title_error").hide("<div>* Title is required</div>");
                $("#title_error1").html("<div>* Image is required</div>");
                error_data++;
                return false;
            }
            

 else if(mess == "" || mess == "undefined")
            {
                 $("#title_error1").hide("<div>* image is required</div>");
                 $("#title_error2").html("<div>* Message is required</div>");
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

</body>
</html>
