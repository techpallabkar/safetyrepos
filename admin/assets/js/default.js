var currentUrl = window.location.href;
jQuery(document).ready(function ($) {
	/*** For Menu ***/
	jQuery('.maindiv').click(function () {
		jQuery(".headermenu .more").parent().find('#popover').css({ 'opacity' : 0, 'z-index' : -1 });
	});
	jQuery('.headermenu .more').click(function () { 
		if( jQuery(this).parent().find('#popover').css('opacity') == 0 ) {
			jQuery(this).parent().find('#popover').css({ 'opacity' : 1, 'z-index' : 1 });
		} else {
			jQuery(this).parent().find('#popover').css({ 'opacity' : 0, 'z-index' : -1 });
		}
	});
	
	
	/**** Go to controller's index action ****/
	jQuery('#cancel').click(function () {
		var urlSplit = currentUrl.split('/');
		var requiredUrl = currentUrl.replace( '/'+urlSplit[urlSplit.length - 1], '');
		location.href=requiredUrl;
		//history.go(-1);
	});
	
	/**** Toggle check all ****/
	jQuery('#toggle_check').click(function () {
		jQuery('input[type=checkbox]#ids').prop( "checked", jQuery(this).is(':checked') );
	});

	jQuery('a').click(function () {
		if( jQuery(this).attr('id') == 'delete' ) {
			if( confirm('Confirm Delete.') ) return true;
			return false;
		}
	});
	
	var btnattrs = Array('', 'noevents', 'delete', 'active', 'deactive', 'reset');
	jQuery('.btn').each(function () {
		var btntxt 	= jQuery.trim(jQuery(this).html()).toLowerCase();
		//if( btntxt == 'delete' ) jQuery(this).css({ 'background':'#db0d0d', 'border' : 'solid 1px #db3131' });
		//if( btntxt == 'unpublish' ) jQuery(this).css({ 'background':'#6f6e6e', 'border' : 'solid 1px #ddd', 'color' : '#fff' });
	});
//	jQuery('.btn').click(function () {
//            
//		var btntxt 	= jQuery.trim(jQuery(this).html());
//		var ids 	= Array();
//		var i 		= 0;
//		var btnhref = '';
//		if( jQuery.inArray(btntxt.toLowerCase(), btnattrs) > 0 ) {
//			if( confirm('Confirm gghh '+btntxt+'.') ) {
//				jQuery('input#ids:checked').each(function () {
//					ids[i++] = jQuery(this).val();
//				});
//				if( ids.length > 0 ) {
//					btnhref = jQuery(this).attr('href')+'&ids='+ids;
//					jQuery(this).attr('href', btnhref);
//					return true;
//				}
//			}
//			return false;
//		}
//	});
        
        jQuery('.btnbulk').click(function () {
            
		var btntxt 	= jQuery(this).data("value");
		var ids 	= Array();
		var i 		= 0;
		var btnhref = '';
		if( jQuery.inArray(btntxt.toLowerCase(), btnattrs) > 0 ) {
			if( confirm('Confirm '+btntxt+'.') ) {
				jQuery('input#ids:checked').each(function () {
					ids[i++] = jQuery(this).val();
				});
				if( ids.length > 0 ) {
					btnhref = jQuery(this).attr('href')+'&ids='+ids;
					jQuery(this).attr('href', btnhref);
					return true;
				}
			}
			return false;
		}
	});
});


function activation(statuslink, id) {
	if( statuslink == undefined && statuslink == "" ) return false;
	
	var activelink = '';
	jQuery('#activate-'+id).html('<img src="'+root_url+'assets/images/ajax_loader.gif" />');
	jQuery.ajax({
		data : {}, url : statuslink, type : 'POST', cache : false, success : function (activation) {
			jQuery('.message').html( activation.message );
			
			if( activation.status_id == "2" ) {
				activelink = statuslink.replace('status_id=3', 'status_id=2');
				jQuery('#activate-'+id).html('<img src="'+root_url+'assets/images/icon1Active.png" />');
			}
			if( activation.status_id == "3" ) {
				activelink = statuslink.replace('status_id=2', 'status_id=3');
				jQuery('#activate-'+id).html('<img src="'+root_url+'assets/images/icon1Inactive.png" />');
			}
			//alert(activelink);
			jQuery('#activate-'+id).attr('onclick', 'activation(\''+activelink+'\', '+id+');' );
		}
	});
	return false;
}

function validEmail(email) {
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return filter.test(email) ? true : false;
}

function getPageName(url) {
    var index = url.lastIndexOf("/") + 1;
    var filenameWithExtension = url.substr(index);
    var filename = filenameWithExtension.split(".")[0];
    return filename;
}

var matched, browser;
jQuery.uaMatch = function( ua ) {
    ua = ua.toLowerCase();

    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        /(msie) ([\w.]+)/.exec( ua ) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        [];

    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};

matched = jQuery.uaMatch( navigator.userAgent );
browser = {};

if ( matched.browser ) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}

// Chrome is Webkit, but Webkit is also Safari.
if ( browser.chrome ) {
    browser.webkit = true;
} else if ( browser.webkit ) {
    browser.safari = true;
}
jQuery.browser = browser;
