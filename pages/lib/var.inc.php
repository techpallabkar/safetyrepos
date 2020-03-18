<?php
ini_set('session.gc_maxlifetime', 7200);
session_set_cookie_params(7200);
session_start();
error_reporting(0);

if( !defined( "FOLDERNAME" ) ) define( "FOLDERNAME", "safety_portal" );

$basePath = dirname(dirname(dirname( __FILE__ )));
if( !defined( "BASE_PATH" ) ) define( "BASE_PATH", $basePath );
if( !defined( "CONTROLLER_PATH" ) ) define( "CONTROLLER_PATH", BASE_PATH . "/pages/controllers" );
if( !defined( "LIB_PATH" ) ) define( "LIB_PATH", BASE_PATH . "/pages/lib" );
if( !defined( "BEAN_PATH" ) ) define( "BEAN_PATH", BASE_PATH . "/pages/bean" );
if( !defined( "BEAN_UI_PATH" ) ) define( "BEAN_UI_PATH", BASE_PATH . "/pages/beanui" );
if( !defined( "DAO_PATH" ) ) define( "DAO_PATH", BASE_PATH . "/pages/dao" );
if( !defined( "VIEW_PATH" ) ) define( "VIEW_PATH", BASE_PATH . "/pages/master" );
if( !defined( "CSS_PATH" ) ) define( "CSS_PATH", BASE_PATH . "/assets/css" );
if( !defined( "JS_PATH" ) ) define( "JS_PATH", BASE_PATH . "/assets/js" );
if( !defined( "UPLOADS_PATH" ) ) define( "UPLOADS_PATH", BASE_PATH . "/assets/uploads" );

if( !defined( "DB_HOST" ) ) define( "DB_HOST", "localhost" );
if( !defined( "DB_USER" ) ) define( "DB_USER", "safety" );
if( !defined( "DB_PASSWORD" ) ) define( "DB_PASSWORD", "safety@1234" );
if( !defined( "DB_NAME" ) ) define( "DB_NAME", "safety_portal" );


if( !defined( "PAGENAME" ) ) define( "PAGENAME", basename($_SERVER["PHP_SELF"]) );
if( !defined( "CLASSNAMEPREFIX" ) ) define( "CLASSNAMEPREFIX", str_replace(".php", "", ucfirst(PAGENAME)) );
if( !defined( "UPLOAD_IMAGE_MAX_WIDTH" ) ) define( "UPLOAD_IMAGE_MAX_WIDTH", 800 );
if( !defined( "UPLOAD_IMAGE_MAX_SIZE" ) ) define( "UPLOAD_IMAGE_MAX_SIZE", 51200 ); //BYTES 50 KB

if( !defined( "PAGE_LIMIT" ) ) define( "PAGE_LIMIT", 5 );

$base_dir_name = str_replace( dirname(BASE_PATH), "", BASE_PATH );
$base_dir_name = substr($base_dir_name, 1, strlen($base_dir_name));

if( !defined( "ADMIN_BASEDIR_NAME" ) ) define( "ADMIN_BASEDIR_NAME", $base_dir_name );

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
load_healpers('default');
load_bean();
load_beanUi();
load_dao();
load_controller();

