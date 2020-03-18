<?php
//echo $site_root_url."pallab";die; 
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("CmsController");
$controller->doAction();
$beanUi = $controller->beanUi;
$messageData = $beanUi->get_view_data("messageData");
$getdata = $beanUi->get_view_data("getdata");
$controller->get_header();
$site_root_url = "";
$status_id = 0;
?>

<div class="container1">
    <h1 class="heading">Edit Messages</h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <form name="editManageMessages" id="editManageMessages" action="" method="post" enctype="multipart/form-data">
    <div class="holder required">
			<label for="contect_type">Title</label>
                        <input type="text" name="data[contect_type]" id="title" value="<?php echo $getdata[0]->contect_type ?>"  />
                        <!--<input type="text" name="data[contect_type]" id="title" value="<?php echo $beanUi->get_view_data( "contect_type" ); ?>" />-->
			<div id="title_error"><?php echo $beanUi->get_error( 'contect_type' ); ?></div>
		</div><br />
    <div class="holder required">
		<label for="image_path">Upload Image</label>
                <input type="file" name="image_path" id="image_path" class="imagepath" />
                <img class="img-thumbnail" src="<?php echo site_url($getdata[0]->image_path) ?>" height="150px" width="150px">
                
                </div>
                <br>
                 <div class="holder">
      <?php
            $pdf_link_html 	= "";
            $abs_file_path 	= CESC_BASE_PATH . "/" . $getdata[0]->file_path;
            if( is_file($abs_file_path )  ) {
                $pdf_link_html = '<label for="file_path">Upload File</label><a href="'.$site_root_url."/".$getdata[0]->file_path.'" target="_blank" title="View file">'."\n";
                $pdf_link_html .= '<img src="'.url("assets/images/pdfIcon.png").'" style="width:20px;" />'."\n";
                $pdf_link_html .= '</a>'."\n";
                if(! in_array($status_id , array(3, 5)) ) {
                    $pdf_link_html .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                    $pdf_link_html .= '<a href="editManageMessages.php?action=purge_file&id='.$getdata[0]->id.'&file=file_path" title="Delete file" onclick="return confirm(\'Confirm delete.\');">'."\n";
                    $pdf_link_html .= '<img src="'.url("assets/images/delete.gif").'" />'."\n";
                    $pdf_link_html .= '</a>'."\n";
                }
                $pdf_link_html .= '<input type="hidden" name="data[file_path]" id="file_path" value="'.$getdata[0]->file_path.'" />'."\n";
                echo $pdf_link_html;
            } else { 
                echo '<label for="file_path">Upload File</label>
                            <input type="file" name="file_path" id="file_path"  class="pdffile" />';
            }
            ?>        
                 </div>
                 <br>
    <div class="holder required">
			<label for="description">Message</label>
                        <textarea type="text" name="data[description]" id="title" value="" /><?php echo $getdata[0]->description ?></textarea>
                        <!--<textarea type="text" name="data[description]" id="title" value="<?php echo $beanUi->get_view_data( "description" ); ?>" /></textarea>-->
			<div id="title_error"><?php echo $beanUi->get_error( 'description' ); ?></div>
		</div><br />
    <div class="holder">
                <center>
                    <input type="submit" value="Update" class="btn btn-sm btn-primary" />
                    <a class="btn btn-sm btn-danger" href="manageMessages.php" >Cancel</a>
                    <input type="hidden" name="_action" value="updateMessageData" />
                    <input type="hidden" name="data[id]"  id="AddMM_id" value="<?php echo $getdata[0]->id; ?>" />
                </center>
            </div>
    </from>


</div>

<?php $controller->get_footer(); ?>


</body>
</html>
