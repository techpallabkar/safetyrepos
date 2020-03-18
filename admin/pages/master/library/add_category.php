<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$controller = load_controller( "LibraryController" );
$controller->doAction();

$beanUi = $controller->beanUi;
$categories 	= $beanUi->get_view_data( "categories" );
$parent_id 		= $beanUi->get_view_data( "parent_id" );

$controller->setCss("tree");
$controller->get_header();
?>

<div class="container1">
	
	<h1 class="heading">Add E-book Category</h1>
	<div class="message"><?php echo $beanUi->get_message(); ?></div><br />
	
	
	<div class="holder" id="maintree">
		<div class="cat_error"><?php echo $beanUi->get_error( 'parent_id_error' ); ?></div>
		<b><i>Select Parent Category</i> <span style="color:#ee0000;">*</span></b>
		<?php create_tree_view_cms($categories, 0, 0, -1, $parent_id, 1); ?>
	</div>
	
	<div class="holder" id="rightform">
		<form name="add_cat" id="add_cat" action="" method="post" enctype="multipart/form-data">
		<div class="holder" id="selected_cat"></div><br />
		<div class="holder required">
			<label for="cat_name">Category Name</label>
			<input type="text" name="data[name]" id="cat_name" value="<?php echo $beanUi->get_view_data( "name" ); ?>" />
			<div id="name_error"><?php echo $beanUi->get_error( 'name' ); ?></div>
		</div><br />
		<div class="holder">
			<input type="submit" value="Add" class="btn btn-sm btn-primary" />
			<input type="button" id="cancel" value="Cancel" class="btn btn-sm btn-danger" />
			
			<input type="hidden" name="_action" value="add_category" />
			<input type="hidden" name="data[parent_id]" id="parent_id" value="<?php echo $parent_id; ?>" />
		</div>
		</form>
	</div>
              <div class="clearfix"></div>
</div>

<?php $controller->get_footer(); ?>
<script>
jQuery(document).ready(function ($) {
	var parent_id = jQuery("#parent_id").val();
      // alert(parent_id);
	var cat_paths 	= Array();
	<?php foreach( $categories as $key => $catrow ) { ?>
		cat_paths[<?php echo $catrow->id; ?>] = "<?php echo $catrow->path; ?>";
	<?php } ?>
	if( parent_id > 0 ) select_cat_path(parent_id);
	
	
	jQuery("#maintree").find("input[type=checkbox]").attr("checked", "checked");
	jQuery("#maintree li label").click(function () {
		jQuery("#maintree li label").css({"color":"#000"});
		jQuery(this).css({"color":"#ee0000"});
		
		var cat_input_name = $(this).attr("for");
                
		var parent_id = jQuery( "input[name="+cat_input_name+"]" ).attr("id");
             
		if( parent_id > 0 ) {
			jQuery("#parent_id").val(parent_id);
			jQuery( "#selected_cat" ).empty();
			jQuery( "#selected_cat" ).html( "<label>Parent Category : </label>" +cat_paths[parent_id]).css({"border" : "1px solid black"});
		}
	});

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
	
	$("#add_cat").submit(function () {
		var cat_name = jQuery.trim( jQuery("#cat_name").val() );
		var parent_id = jQuery.trim( jQuery("#parent_id").val() );
		var error_counter = 0;
		jQuery(".errors").empty();
		if( cat_name == undefined || cat_name == "" ) {
			jQuery("#cat_name").focus();
			jQuery("#name_error").html("<div class=\"errors\">Category Name is required.</div>");
			error_counter++;
		}
		if( parent_id == undefined || parent_id == "" || parent_id == 0 ) {
			jQuery(".cat_error").html("<div class=\"errors\">Parent category is required.</div>");
			error_counter++;
		}
		if( error_counter > 0 ) {
			jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
			jQuery('html, body').animate({ scrollTop: 0}, 'slow');
			return false;
		}
	});
	
	function select_cat_path(parent_id) {
		if( parent_id > 0 ) {
			jQuery( "#selected_cat" ).empty();
			jQuery( "#selected_cat" ).html( "<label>Parent Category : </label>" +cat_paths[parent_id]).css({"border" : "1px solid black"});
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
