<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$controller = load_controller("CmsController");
$controller->doAction();
$beanUi = $controller->beanUi;
$importantinformation = $beanUi->get_view_data("importantinformation");
$singleData = $beanUi->get_view_data("singleData");
$controller->get_header();
?>
<style type="text/css">
#suggesstion-box{ 
	width:50%; padding-left:24%; position:absolute; margin-top:-7px; max-height:200px; overflow:auto;
}
#taglist{ float:left; list-style:none; margin:0; padding:0; width:100%;}
#taglist li{padding: 10px; background:#FAFAFA;border-bottom:#F0F0F0 1px solid;}
#taglist li:hover{background:#F0F0F0;}

#feature_image_display
{
	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);
	min-width: 250px;
	max-width: 700px;
}
</style>

<script type="text/javascript" src="<?php echo url("assets/ckeditor/ckeditor.js")?>"></script>

<div class="container1">
	<h1 class="heading">Important Information</h1>
         <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <?php if($singleData) {  if ($singleData[0]->id != "" || $singleData[0]->id != 0) { ?>
    <form name="important_information" id="important_information" action="" method="post" enctype="multipart/form-data">
        <div class="holder required">			
                <label for="description">Content</label>
                <textarea name="data[content]" id='post_detail'><?php echo $singleData[0]->content; ?></textarea>
                <div id="description_error"><?php echo $beanUi->get_error('content'); ?></div>
        </div>
        <br>
        <div class="holder">
                <center>
                    <input type="submit" value="Update" class="btn btn-sm btn-primary" />
                    <a class="btn btn-sm btn-danger" href="importantinformation.php" >Cancel</a>
                    <input type="hidden" name="_action" value="updateData" />
                    <input type="hidden" name="data[id]" id="impinfo_id" value="<?php echo $singleData[0]->id; ?>" />
                </center>
            </div>
    </form>
     <hr />
    <?php } } ?>
        <div>
           <table class="table table-bordered table-condensed table-responsive">
              <thead>
                <tr class="bg-primary">
                    <th width="90%">Content</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
            foreach ($importantinformation as $key => $rowdata) {
                $var =(strlen($rowdata->content) > 200 ) ? "..." : "";
                echo '<tr>'
                . '<td>' . substr(strip_tags($rowdata->content),0,200).$var. '</td>'
                . '<td align="center"><a href="importantinformation.php?editid=' . $rowdata->id . '" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a></td>'
                . '</tr>';
            }
            ?>
                </tr>
            </tbody>
            </table>
        </div>
    
	
</div>

<?php $controller->get_footer(); ?>
<!-- JavaScript -->
<script type="text/javascript" src="<?php echo url("assets/js/jquery.Jcrop.min.js")?>"></script>
<script type="text/javascript" src="<?php echo url("assets/js/jquery.imgpicker.js")?>"></script>


<script type="text/javascript">
CKEDITOR.replace( 'post_detail' );
timer = setInterval('updateDiv()', 100);
function updateDiv(){
	var post_detail_text = CKEDITOR.instances.post_detail.getData();
	$('#tracking_post_detail').hide();
	$('#tracking_post_detail').html(post_detail_text);
}


</script>

