<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("BulletinController");
$controller->doAction();
$beanUi = $controller->beanUi;
$rowID  =  $beanUi->get_view_data('rowID');
$bulletinRowData  =  $beanUi->get_view_data('bulletinRowData');
$controller->get_header();

?>

<div class="container1" style="min-height: 800px;">
    <h1 class="heading">Add Bulletin</h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <form name="addEvent" id="addEvent" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $rowID; ?>" />
     <div class="holder required">
            <label for="bulletin_date">Date</label>
            <input type="text" name="data[bulletin_date]" id="bulletin_date" class="datepicker" autocomplete="off" value="<?php echo isset($bulletinRowData->bulletin_date) ? $bulletinRowData->bulletin_date : "" ; ?>" />
            <div id="bulletin_date_error" style="color:red"><?php //echo $beanUi->get_error('bulletin_date'); ?></div>
        </div>
        <br />
     
        <div class="holder required">
            <label for="department">Department</label>
            <input type="text" name="data[department]" id="department" value="<?php echo isset($bulletinRowData->department) ?$bulletinRowData->department : "" ; ?>" />
            <div id="department_error" style="color:red"><?php //echo $beanUi->get_error('department'); ?></div>
        </div>
        <br />
     
        <div class="holder">			
            <?php
            $pdf_link_html 	= "";
			
            $abs_file_path 	= CESC_BASE_PATH . "/" . @$bulletinRowData->file_path;
            if( is_file($abs_file_path )  ) {
                @$pdf_link_html = '<label for="file_path">Upload File</label><a href="'.$site_root_url."/".$bulletinRowData->file_path.'" target="_blank" title="View file">'."\n";
                $pdf_link_html .= '<img src="'.url("assets/images/pdfIcon.png").'" style="width:20px;" />'."\n";
                $pdf_link_html .= '</a>'."\n";
                if(! in_array(@$status_id , array(3, 5)) ) {
                    $pdf_link_html .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                    $pdf_link_html .= '<a href="add.php?action=purge_file&id='.$bulletinRowData->id.'&file=file_path" title="Delete file" onclick="return confirm(\'Confirm delete.\');">'."\n";
                    $pdf_link_html .= '<img src="'.url("assets/images/delete.gif").'" />'."\n";
                    $pdf_link_html .= '</a>'."\n";
                }
                $pdf_link_html .= '<input type="hidden" name="data[file_path]" id="file_path" value="'.$bulletinRowData->file_path.'" />'."\n";
                echo $pdf_link_html;
            } else { 
                echo '<label for="file_path">Upload File</label>
                            <input type="file" name="file_path" id="file_path"  class="pdffile" />';
            }
            ?>
            <div id="file_path_error"><?php echo $beanUi->get_error( 'file_path' ); ?></div>
        </div>
        <br />
        <div class="holder required">
            <label for="description">Description</label>
            <textarea name="data[description]" id="description" ><?php echo @$bulletinRowData->description; ?></textarea>
            <div id="description_error" style="color:red"><?php //echo $beanUi->get_error('description'); ?></div>
        </div>
        <hr />
        <div class="holder">
            <center>
                <?php 
                if( $rowID != "" ) { 
                    echo '<input type="submit" value="Update" class="btn btn-sm btn-primary" />';
                }
                else
                {
                    echo '<input type="submit" value="Add" class="btn btn-sm btn-primary" />';
                }
                ?>
                <a class="btn btn-sm btn-danger" href="index.php" >Cancel</a>
                <input type="hidden" name="_action" value="addBulletin" />
            </center>
        </div>
        </form>


</div>

<?php $controller->get_footer(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>
<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
<script>
    jQuery.datetimepicker.setLocale('en');
    jQuery('.datepicker').datetimepicker({

        timepicker: false,
        scrollMonth: false,
        scrollInput: false,
        format: 'Y-m-d',
        step: 5
    });
  
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#addEvent").submit(function(){
            var bulletin_date =$("#bulletin_date").val();
            var department =$("#department").val();
            var description =$("#description").val();
            var error_data = 0;

			
            if(bulletin_date == "" || bulletin_date == "undefined")
            {
                $("#bulletin_date_error").html("<div>* Date is required</div>");
                $("#department_error").html("<div>* Department is required</div>");
                $("#description_error").html("<div>* Description is required</div>");
                
                error_data++;
                return false;
            }
else if(department == "" || department == "undefined")
            {
                $("#bulletin_date_error").hide("<div></div>");
                $("#department_error").html("<div>* Department is required</div>");
                error_data++;
                return false;
            }
            

 else if(description == "" || description == "undefined")
            {
                 $("#department_error").hide("<div></div>");
                $("#description_error").html("<div>* Description is required</div>");
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
