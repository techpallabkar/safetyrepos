jQuery(document).ready(function () {
	jQuery('#add_user').submit(function () {
		var errors 			= 0;
		var username 		= jQuery.trim(jQuery('#username').val());
		var display_name 	= jQuery.trim(jQuery('#display_name').val());
		var email 			= jQuery.trim(jQuery('#email').val());
		var contactno 		= jQuery.trim(jQuery('#contactno').val());
		var label 			= '';
		
		jQuery('.errors').empty().hide();
		if( username == "" ) {
			label = jQuery('#username').parent().find('label').html();
			jQuery('#username').parent().append('<div class="errors">'+label+' is required</div>');
			errors++;
		}
		if( display_name == "" ) {
			label = jQuery('#display_name').parent().find('label').html();
			jQuery('#display_name').parent().append('<div class="errors">'+label+' is required</div>');
			errors++;
		}
		if( email == "" ) {
			label = jQuery('#email').parent().find('label').html();
			jQuery('#email').parent().append('<div class="errors">'+label+' is required</div>');
			errors++;
		}
		if( email != "" && validEmail(email) == false ) {
			label = jQuery('#email').parent().find('label').html();
			jQuery('#email').parent().append('<div class="errors">A valid '+label+' is required</div>');
			errors++;
		}
		if( contactno == "" ) {
			label = jQuery('#contactno').parent().find('label').html();
			jQuery('#contactno').parent().append('<div class="errors">'+label+' is required</div>');
			errors++;
		}
		if( contactno != "" && isNaN(contactno) ) {
			label = jQuery('#contactno').parent().find('label').html();
			jQuery('#contactno').parent().append('<div class="errors">A valid '+label+' is required</div>');
			errors++;
		}
		if( contactno != "" && !isNaN(contactno) ) {
			if( contactno.length < 10 || contactno.length > 12 ) {
				label = jQuery('#contactno').parent().find('label').html();
				jQuery('#contactno').parent().append('<div class="errors">'+label+' should be 10 to 12 number of digit long.</div>');
				errors++;
			}
		}
		return ( errors == 0 ) ? true : false;
	});
});

