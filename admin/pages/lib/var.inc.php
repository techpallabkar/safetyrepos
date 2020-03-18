<?php
error_reporting(0);
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 36000);


session_set_cookie_params(36000);

session_start();
date_default_timezone_set('Asia/Kolkata');
if( !defined( "FOLDERNAME" ) ) define( "FOLDERNAME", "safety_portal_live" );
$basePath = dirname(dirname(dirname( __FILE__ )));
if( !defined( "BASE_PATH" ) ) define( "BASE_PATH", $basePath );
if( !defined( "CESC_BASE_PATH" ) ) define( "CESC_BASE_PATH", dirname(BASE_PATH) );
if( !defined( "CONTROLLER_PATH" ) ) define( "CONTROLLER_PATH", BASE_PATH . "/pages/controllers" );
if( !defined( "LIB_PATH" ) ) define( "LIB_PATH", BASE_PATH . "/pages/lib" );
if( !defined( "BEAN_PATH" ) ) define( "BEAN_PATH", BASE_PATH . "/pages/bean" );
if( !defined( "BEAN_UI_PATH" ) ) define( "BEAN_UI_PATH", BASE_PATH . "/pages/beanui" );
if( !defined( "DAO_PATH" ) ) define( "DAO_PATH", BASE_PATH . "/pages/dao" );
if( !defined( "VIEW_PATH" ) ) define( "VIEW_PATH", BASE_PATH . "/pages/master" );
if( !defined( "CSS_PATH" ) ) define( "CSS_PATH", BASE_PATH . "/assets/css" );
if( !defined( "JS_PATH" ) ) define( "JS_PATH", BASE_PATH . "/assets/js" );
if( !defined( "UPLOADS_PATH" ) ) define( "UPLOADS_PATH", dirname(BASE_PATH) . "/assets/uploads" );
if( !defined( "MIS_IMAGE_PATH_SA" ) ) define( "MIS_IMAGE_PATH_SA", "http://10.40.83.45:8081/sas/upload/" );
if( !defined( "MIS_IMAGE_PATH_PPE" ) ) define( "MIS_IMAGE_PATH_PPE", "http://10.40.83.45:8081/sas/upload/" );

if( !defined( "DB_HOST" ) ) define( "DB_HOST", "localhost" );
if( !defined( "DB_USER" ) ) define( "DB_USER", "safety" );
if( !defined( "DB_PASSWORD" ) ) define( "DB_PASSWORD", "msroot" );
//if( !defined( "DB_NAME" ) ) define( "DB_NAME", "safety_portal_local_21112019" );
if( !defined( "DB_NAME" ) ) define( "DB_NAME", "safety_portal_12032020" );

if( !defined( "PAGENAME" ) ) define( "PAGENAME", basename($_SERVER["PHP_SELF"]) );
if( !defined( "CLASSNAMEPREFIX" ) ) define( "CLASSNAMEPREFIX", str_replace(".php", "", ucfirst(PAGENAME)) );
if( !defined( "UPLOAD_IMAGE_MAX_WIDTH" ) ) define( "UPLOAD_IMAGE_MAX_WIDTH", 800 );
if( !defined( "UPLOAD_IMAGE_MAX_SIZE" ) ) define( "UPLOAD_IMAGE_MAX_SIZE", 51200 ); //BYTES 50 KB

$base_dir_name = str_replace( dirname(BASE_PATH), "", BASE_PATH );
$base_dir_name = substr($base_dir_name, 1, strlen($base_dir_name));

if( !defined( "BASE_DIR_NAME" ) ) define( "BASE_DIR_NAME", $base_dir_name );

function load_healpers( $healpers = array() ) {
	if( is_array($healpers) && !empty($healpers) ) {
		foreach( $healpers as $healperfile) {
			$filepath = BASE_PATH . "/pages/healpers/" . $healpers . ".php";
			if( file_exists( $filepath ) ) require_once($filepath);
		}
	} else {
		$filepath = BASE_PATH . "/pages/healpers/" . $healpers . ".php";
		if( file_exists( $filepath ) ) require_once($filepath);
	}
}
//if( !defined( "FILESIZE_ERROR" ) ) define( "FILESIZE_ERROR", '<span class="error"><i class="fa fa-hand-o-right"></i> Maximum upload file size 2MB</span>' );
if( !defined( "FILESIZE_ERROR" ) ) define( "FILESIZE_ERROR", '' );
if( !defined( "CURRENT_DATE_TIME" ) ) define("CURRENT_DATE_TIME",date("l d F Y h:i A"));
if( !defined( "CHECK_DATE" ) ) define("CHECK_DATE",date("Y-m-16"));
if( !defined( "VIDEO_EXTN_ALLOWED_MSG" ) ) define( "VIDEO_EXTN_ALLOWED_MSG", '<div class="alert alert-info" ><i class="fa fa-hand-o-right"></i> Only .mp4 allowed</div>' );
if( !defined( "IMAGE_EXTN_ALLOWED_MSG" ) ) define( "IMAGE_EXTN_ALLOWED_MSG", ' <div class="alert alert-info" ><i class="fa fa-hand-o-right"></i> Only .jpg, .jpeg, .png, .gif allowed ( Total capacity is 3MB in each submit of uploaded images and office file. )</div>' );
if( !defined( "FILE_EXTN_ALLOWED_MSG" ) )  define( "FILE_EXTN_ALLOWED_MSG", ' <div class="alert alert-info" ><i class="fa fa-hand-o-right"></i> Only .pdf allowed ( Total capacity is 3MB in each submit of uploaded images and office file. )</div>' );
load_healpers('default');
load_bean();
load_beanUi();
load_dao();
load_controller();

define( "_BACK_YEAR", "2016" );
