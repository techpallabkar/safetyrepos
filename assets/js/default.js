function validEmail(email) {
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return filter.test(email) ? true : false;
}
var loader_path = root_url+"assets/images/ajax-loader.gif";
jQuery(".loader").html("<img src=\""+loader_path +"\" />").css({ "padding-left":"10px" });
var $loader = jQuery(".loader");
$loader.hide();
jQuery(document).ready(function () {
	
});


//$('html, body').animate({
//	scrollTop: 0
//}, 'slow');
//$message.fadeOut(1000);
//e.preventDefault();
