jQuery(document).ready(function () {
	jQuery('.dropdown a.category').click(function () {
		jQuery('dd.post_categories').toggle();
	});
	jQuery('.mutliSelect li span').click(function () {
		var checkBoxes = jQuery(this).children('input[type=checkbox]');
		checkBoxes.prop("checked", !checkBoxes.prop("checked"));
	});
	
	var page_name = getPageName(location.href);
	
	if(page_name == 'add') {
		var error = 0;
		jQuery('#submit_add_post').click(function () {
			jQuery( '.errors' ).empty().hide();
			var title 		= jQuery.trim( jQuery('#title').val() );
			var author 		= jQuery.trim( jQuery('#author').val() );
			var synopsis 	= jQuery.trim( jQuery('#synopsis').val() );
			var description = jQuery.trim( jQuery('#description').val() );
			
			if( title == undefined || title == "" ) {
				jQuery('#title').parent().append('<span class="errors">Title is required.</span>');
				error++;
			}
			if( error ) return false;
			else jQuery('form').submit();
		});
	}
	
	jQuery("#delthumb").click(function() {
		var delimgs = 0;
		var extrainputs = '';
		var thumbs = Array();
		var uploadids = Array();
		var i = 0;
		jQuery('#thumbnail').find('span').empty();
		if( confirm( 'Confirm delete.' ) ) {
			var thumbpath = '';
			jQuery("input[type=checkbox]#filepath").each(function () {
				thumbpath = '';
				if( jQuery(this).attr("checked") == true || jQuery(this).attr("checked") == 'checked' ) {
					thumbpath = jQuery(this).val();
					delimgs++;
				}
				thumbs[i++] = thumbpath;
			});
			i = 0;
			jQuery("input[type=hidden]#uploadids").each(function () {uploadids[i++] = jQuery(this).val();});
			
			for( i = 0; i < thumbs.length; i++ ) {
				if( thumbs[i] != undefined && thumbs[i] != "" ) {
					if( uploadids[i] != undefined && uploadids[i] > 0 ) {
						extrainputs += '<input type="hidden" name="uploadids[]" id="uploadids" value="'+uploadids[i]+'">'+"\n";
						extrainputs += '<input type="hidden" name="filepath[]" id="filepath" value="'+thumbs[i]+'" >'+"\n";
					}
				}
			}
			
			if( extrainputs != "" ) {
				jQuery('.addextrathumb').html(extrainputs);
				jQuery('form#thumbimgs').submit();
			}
		}
	});
	
});

