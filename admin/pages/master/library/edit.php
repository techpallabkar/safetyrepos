<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$controller = load_controller( "LibraryController" );
$controller->doAction();

$beanUi = $controller->beanUi;
$categories                     = $beanUi->get_view_data( "categories" );
$master_status                  = $beanUi->get_view_data( "master_status" );
$category_id                    = $beanUi->get_view_data( "category_id" );
$id 				= $beanUi->get_view_data( "id" );
$status_id 			= $beanUi->get_view_data( "status_id" );
//echo $status_id;
$tag_keys 			= $beanUi->get_view_data( "tag_keys" );
$created_by                     = $beanUi->get_view_data( "created_by" );
$site_root_url                  = dirname( url() );

$controller->setCss("tree");
$controller->get_header();
?>

<style type="text/css">
#suggesstion-box{ 
	width:30%; position:absolute; margin-top:-9px; max-height:200px; overflow:auto; border: solid 1px #ddd;
}
#taglist{float:left;list-style:none;margin:0;padding:0;width:100%; }
#taglist li{padding: 10px; background:#FAFAFA;border-bottom:#F0F0F0 1px solid;}
#taglist li:hover{background:#F0F0F0;}

#maintree { border-right: solid 1px #999; }
#feature_image_display
{
	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);
	min-width: 250px;
	max-width: 700px;
}
#selected_cat{ padding: 5px; color:#ee0000; width:90%; }
#selected_cat label{ color:#000; width:33%; }
</style>

<div class="container1">
	
	<h1 class="heading">Update E-book</h1>
	<div class="message"><?php echo $beanUi->get_message(); ?></div><br />
	
	<div class="holder2 col-md-4" id="maintree">
		<div class="cat_error"><?php echo $beanUi->get_error( 'category_id' ); ?></div>
		<b><i>Select Category</i> <span style="color:#ee0000;">*</span></b>
		<?php create_tree_view_cms($categories, 0, 0, -1, $category_id, 1); ?>
	</div>
	
	<div class="holder2 col-md-8" id="rightform2">
		<form name="edit_standards_and_codes" id="edit_standards_and_codes" action="" method="post" enctype="multipart/form-data">
		<div class="holder" id="selected_cat"></div><br />
		<div class="holder required">
			<label for="title">Title</label>
			<input type="text" name="data[title]" id="title" value="<?php echo htmlspecialchars($beanUi->get_view_data( "title" )); ?>" />
			<div id="title_error"><?php echo $beanUi->get_error( 'title' ); ?></div>
		</div><br />
		

		
		<div class="holder required">
			
				<?php
				$pdf_link_html 	= "";
				$abs_file_path 	= CESC_BASE_PATH . "/" . $beanUi->get_view_data( 'file_path' );
//                                echo $abs_file_path;die;
//				if( is_file($abs_file_path )) {
				if( is_file($abs_file_path )  ) {
                             
					$pdf_link_html = '<span style="display:inline-block;width:30%;"></span><a href="'.$site_root_url."/".$beanUi->get_view_data( 'file_path' ).'" target="_blank" title="View file">'."\n";
					$pdf_link_html .= '<img src="'.url("assets/images/pdfIcon.png").'" style="width:20px;" />'."\n";
					$pdf_link_html .= '</a>'."\n";
					if(! in_array($status_id , array(3, 5)) ) {
						$pdf_link_html .= '&nbsp;&nbsp;&nbsp;&nbsp;';
						$pdf_link_html .= '<a href="edit.php?action=purge_file&id='.$id.'&file=file_path" title="Delete file" onclick="return confirm(\'Confirm delete.\');">'."\n";
						$pdf_link_html .= '<img src="'.url("assets/images/delete.gif").'" />'."\n";
						$pdf_link_html .= '</a>'."\n";
					}
					$pdf_link_html .= '<input type="hidden" name="data[file_path]" id="file_path" value="'.$beanUi->get_view_data( 'file_path' ).'" />'."\n";
					echo $pdf_link_html;
				} elseif(! in_array($status_id , array(3, 5)) ) {
				?>
					<label for="image_path">Upload PDF File</label>
					<input type="file" name="file_path" id="file_path" accept="application/pdf" class="pdffile" />
				<?php
				}
				?>
				<div id="file_path_error"><?php echo $beanUi->get_error( 'file_path' ); ?></div>
							
		</div>
		<br />
		
		<div class="holder">
			<label for="status_id">Status</label>
			<select name="data[status_id]" id="status_id">
			<?php
			if( ! empty( $master_status ) ) {
				$admin_status 	= array("Draft", "Published", "Unpublished", "Archive");
				$user_status 	= array("Submitted", "Published", "Unpublished", "Rejected");
				
				foreach( $master_status as $statusrow ) {
					
					if( in_array( $statusrow->status_name, $admin_status ) ) {
						if( $status_id == $statusrow->id ) {
							echo '<option value="'.$statusrow->id.'" selected>'.$statusrow->status_name.'</option>'."\n";
						} else {
							echo '<option value="'.$statusrow->id.'">'.$statusrow->status_name.'</option>'."\n";
						}
					}
				}
			}
			?>
			</select>
			<?php echo $beanUi->get_error( 'status_id' ); ?>
		</div>
		<br />
		<?php
		$modified_date = $beanUi->get_view_data( "modified_date" );
		if( $modified_date != "0000-00-00 00:00:00" ) {
		?>
			<div class="holder">
				<label for="publication_date">Modified Date</label>
				<?php echo $beanUi->get_view_data( "modified_date" ); ?>
			</div>
			<br />
		<?php
		}
		?>
		<div class="holder">
			<input type="submit" value="Update" class="btn btn-sm btn-primary" />
			<input type="button" value="Cancel" onclick="location.href='./?cat_id=<?php echo $category_id; ?>'" class="btn btn-sm btn-danger" />
			<input type="hidden" name="_action" value="Update" />
			
			<input type="hidden" name="data[category_id]" id="category_id" value="<?php echo $category_id; ?>" />
			<input type="hidden" name="data[id]" id="standards_and_codes_id" value="<?php echo $id; ?>" />
		</div>
		</form>
	</div>
              <div class="clearfix"></div>
</div>

<?php $controller->get_footer(); ?>

<script>

jQuery(document).ready(function ($) {
	
	jQuery("#maintree").find("input[type=checkbox]").attr("checked", "checked");
	//jQuery("#maintree li label").css({"color":"#000"});
	
	// Category tree
	var cat_id = "<?php echo $category_id; ?>";
	var cat_paths 	= Array();
	<?php foreach( $categories as $key => $catrow ) { ?>
		cat_paths[<?php echo $catrow->id; ?>] = "<?php echo str_replace("/", " &#10095; ", $catrow->path); ?>";
	<?php } ?>
	if( cat_id > 0 ) select_cat_path(cat_id);
		
	// Select default category
	jQuery("#maintree li label[for=category-"+cat_id+"]").css({"color":"#ee0000"});
	jQuery("#maintree li label").click(function () {
		jQuery("#maintree li label").css({"color":"#000"});
		jQuery(this).css({"color":"#ee0000"});
		
		var cat_input_name = $(this).attr("for");
		var cat_id = jQuery( "input[name="+cat_input_name+"]" ).attr("id");
		if( cat_id > 0 ) {
			jQuery("#category_id").val(cat_id);
			select_cat_path(cat_id);
		}
	});
	
	
	
	// End

	// Tag section
	jQuery( "#add_tag" ).click(function () {
		add_tag();
		return false;
	});
	
	jQuery("#tags").keyup(function(e){
		var code = e.keyCode || e.which;
		if(code == 13) { //Enter keycode
			add_tag();
			return false;
		}
		jQuery.ajax({
			type: "POST", url: "<?php echo current_url(); ?>",
			data:{
				"action" : "tag_suggestion", 
				"keyword" : jQuery(this).val(), 
				"post_id" : jQuery("#post_id").val()
			},
			beforeSend: function(){
				jQuery("#tags").css("background","#FFF url(<?php echo url('assets/images/LoaderIcon.gif'); ?>) no-repeat 165px");
			},
			success: function(data){
				jQuery("#suggesstion-box").show();
				jQuery("#suggesstion-box").html(data);
				jQuery("#tags").css("background","#FFF");
			}
		});
	});
	// End tag
	
	$("#edit_standards_and_codes").submit(function () {
		var status_text = $("#status_id").find("option:selected").text();
		var title = jQuery.trim( jQuery("#title").val() );
		var category_id = jQuery.trim( jQuery("#category_id").val() );
		var error_counter = 0;
		
		jQuery(".errors,#title_error,.cat_error,#file_path_error,.message").empty();
		jQuery(".errors,#title_error,.cat_error,#file_path_error,.message").html("");
		
		if( title == undefined || title == "" ) {
			//jQuery("#title").focus();
			jQuery("#title_error").html("<div class=\"errors\">Title is required.</div>");
			error_counter++;
		}
		if( category_id == undefined || category_id == "" || category_id == 0 ) {
			jQuery(".cat_error").html("<div class=\"errors\">Category is required.</div>");
			error_counter++;
		}
		if( status_text == "Published" || status_text == "Archive" ) {
			
			var file_path = jQuery.trim( jQuery("#file_path").val() );
			if( file_path == undefined || file_path == "" ) {
				//jQuery("#file_path").focus();
				jQuery("#file_path_error").html("<div class=\"errors\">Upload Pdf File is required.</div>");
				error_counter++;
			}
		}
		if( error_counter > 0 ) {
			jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
			jQuery('html, body').animate({ scrollTop: 0}, 'slow');
			return false;
		}
	});
	
	function select_cat_path(cat_id) {
		if( cat_id > 0 ) {
			jQuery( "#selected_cat" ).empty();
			jQuery( "#selected_cat" ).html( "<b style=\"color:#000;\">Selected Category : </b> " +cat_paths[cat_id]).css({"border" : "1px solid black"});
		}
	}
});



function add_tag() {
	var tagname = jQuery("#tags").val();
	var post_id = jQuery("#post_id").val();
	if( tagname == "" || tagname == undefined ) return false;
	var cross_img_path = "<?php echo url('assets/images/delete.gif');?>";
	
	var tag_keys = jQuery.trim(jQuery("#tag_keys").val());
	if( tag_keys != "" && tag_keys != undefined ) {
		var alltagkeys 	= tag_keys.split(",");
		var duplicate = 0;
		for( var i = 0; i < alltagkeys.length; i++ ) {
			if( tagname == alltagkeys[i] ) {
				duplicate++;
				break;
			}
		}
		
		if( duplicate > 0 ) return false;
		tag_keys = tag_keys+","+tagname;
	} else {
		tag_keys = tagname;
	}
	
	jQuery("#tag_keys").val(tag_keys);
	
	var alltagkeys 	= tag_keys.split(",");
	var tag_html 	= "";
	for( var i = 0; i < alltagkeys.length; i++ ) {
		tag_html += '<span id="tagno-'+i+'">'+alltagkeys[i]+' <img src="'+cross_img_path+'" style="cursor:pointer" onclick="remove_tag('+i+')" /></span>,';
	}
	
	var taghtml = ( alltagkeys.length > 1 ) ? tag_html.replace(/^,|,$/g,'') : tag_html;
	taghtml = ( alltagkeys.length == 1 ) ? taghtml.substring(0, tag_html.length - 1) : taghtml;
	
	jQuery("#all_tags").html(taghtml);
	jQuery("#tags").val("");
	return false;
}

function select_tag(val) {
	jQuery("#tags").val(val);
	jQuery("#suggesstion-box").hide();
}

function remove_tag( tagno ) {
	if( isNaN(tagno) ) return false;
	
	var cross_img_path = "<?php echo url('assets/images/delete.gif');?>";
	var tag_keys = jQuery.trim(jQuery("#tag_keys").val());
	var alltagkeys 	= tag_keys.split(",");
	var tag_html 	= "";
	var newtag_keys = "";
	for( var i = 0; i < alltagkeys.length; i++ ) {
		if( i == tagno ) continue;
		
		newtag_keys += alltagkeys[i]+",";
		tag_html += '<span id="tagno-'+i+'">'+alltagkeys[i]+' <img src="'+cross_img_path+'" style="cursor:pointer" onclick="remove_tag('+i+')" /></span>,';
	}
	
	var taghtml = ( alltagkeys.length > 1 ) ? tag_html.replace(/^,|,$/g,'') : tag_html;
	jQuery("#all_tags").html(taghtml);
		
	var tag_keys = newtag_keys.replace(/^,|,$/g,'');
	jQuery("#tag_keys").val(tag_keys);
}
</script>

</body>
</html>
