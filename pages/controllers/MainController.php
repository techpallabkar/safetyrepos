<?php
class MainController {
	private $eOptions 			= array();
	private $stylesheets 		= array();
	private $javascript_files 	= array();
	
	protected $bean 			= array();
	protected $dao 				= array();
	protected $action 			= "";
	
	public $beanUi 				= array();
	public $auth_user 			= array();
	
	public function __construct() {
		$this->auth_user 		= $this->get_session("auth_user");
		$this->status_options 	= $this->get_session("status_options");
		$this->bean 		= empty($this->bean) ? new MasterBean() : $this->bean;
		$this->action 		= $this->bean->get_request("action");
		$this->beanUi 		= new BeanUi();
		$mainDao 			= new MainDao();
		$all_categories 	= $mainDao->get_all_categories();
		$this->beanUi->set_view_data("all_categories", $mainDao->get_all_categories() );
		unset($mainDao);
	}
	
	public function get_auth_user($field = "") {
		return isset($this->auth_user->{$field}) ? $this->auth_user->{$field} : $this->auth_user;
	}
	
	public function get_status_options($field = "") {
		return isset($this->status_options[$field]) ? $this->status_options[$field] : $this->status_options;
	}
	
	protected function set_session($field = "", $value) {
		if( $field != "" ) {
			$_SESSION[ "{$field}" ] = $value;
		}
	}
	
	protected function get_session($field = "") {
		return isset( $_SESSION[ "$field" ] ) ? $_SESSION[ "$field" ] : '';
	}
	
	protected function json_encoded_output( $data = array() ) {
		if( empty( $data ) ) return FALSE;
		header('Content-Type: application/json');
		die( json_encode( $data ) );
	}
	
	protected function load_libraries( $libraries = array() ) {
		if( is_array($libraries) && !empty($libraries) ) {
			foreach( $libraries as $library) {
				$libname = ucfirst($library);
				$filepath = LIB_PATH . "/" . $libname . ".php";
				if( file_exists( $filepath ) ) {
					require_once($filepath);
					if( class_exists( $libname ) ) {
						//$this->libs[ strtolower($libname) ] = new $libname();
						$libnamevar = strtolower($libname);
						$this->$libnamevar = new $libname();
					}
				}
			}
		} else {
			$libname = ucfirst($libraries);
			$filepath = LIB_PATH . "/" . $libname . ".php";
			
			if( file_exists( $filepath ) ) {
				require_once($filepath);
				if( class_exists( $libname ) ) {
					$libnamevar = strtolower($libname);
					//$this->libs[ strtolower($libname) ] = new $libname();
					$this->$libnamevar = new $libname();
				}
			}
		}
	}
	
	public function setCss( $stylesheets = array() ) {
		if( !empty( $stylesheets ) ) {
			if( is_array( $stylesheets ) ) {
				foreach( $stylesheets as $cssfile ) $this->stylesheets[] = $cssfile;
			} else {
				$this->stylesheets[] = $stylesheets;
			}
		}
	}
	
	public function setJs( $javascript_files = array() ) {
		if( !empty( $javascript_files ) ) {
			if( is_array( $javascript_files ) ) {
				foreach( $javascript_files as $jsfile ) $this->javascript_files[] = $jsfile;
			} else {
				$this->javascript_files[] = $javascript_files;
			}
		}
	}
	
	public function loadCss() {
		$css_links = '';
		if( !empty( $this->stylesheets ) ) {
			if( is_array( $this->stylesheets ) ) {
				foreach( $this->stylesheets as $css ) {
					$filename 	= $css . '.css';
					$css_file_path 	= CSS_PATH . '/' . $filename;
					if( file_exists( $css_file_path ) ) 
						$css_links .= '<link rel="stylesheet" href="'.url('assets/css/'.$filename).'" />'."\n";
				}
			} else {
				$filename 	= $this->stylesheets . '.css';
				$css_file_path 	= CSS_PATH . '/' . $filename;
				if( file_exists( $css_file_path ) ) 
					$css_links 	= '<link rel="stylesheet" href="'.url('assets/css/'.$filename).'" />'."\n";
			}
		}
		return $css_links;
	}
	
	public function loadJs() {
		$js_links = '';
		if( !empty( $this->javascript_files ) ) {
			if( is_array( $this->javascript_files ) ) {
				foreach( $this->javascript_files as $js ) {
					$filename 		= $js . '.js';
					$js_file_path 	= JSPATH . '/' . $filename;
					if( file_exists( $js_file_path ) ) 
						$js_links .= '<script type="text/javascript" src="'.url('assets/js/'.$filename).'"></script>'."\n";
				}
			} else {
				$filename 	= $this->javascript_files . '.js';
				$js_file_path 	= JSPATH . '/' . $filename;
				if( file_exists( $js_file_path ) ) 
					$js_links 	= '<script type="text/javascript" src="'.url('assets/js/'.$filename).'"></script>'."\n";
			}
		}
		return $js_links;
	}
	
	public function userAuthentication() {
		
		if( empty( $this->auth_user ) && ! in_array( PAGENAME, array("login.php", "register.php", "forgot_password.php") ) ) {
			redirect( "login.php?action=logout" );
		} elseif( ! empty($this->auth_user) && PAGENAME == "login.php" && $this->action != "logout" ) {
			redirect( "./" );
		}
	}
	
	public function doAction() {
            
            
		//$this->userAuthentication();
		
		$action = $this->action;
		if( method_exists( $this, $action ) ) {
			$this->$action();
		} else {
			$method_name = str_replace('.php', '', PAGENAME);
			$method_name = str_replace('-', '_', $method_name);
			if( method_exists( $this, $method_name ) ) $this->$method_name();
		}
	}
	
	public function get_header() {
		if( file_exists( VIEW_PATH . '/header.php' ) ) include_once( VIEW_PATH . '/header.php' );
	}
	
	public function get_footer() {
		if( file_exists( VIEW_PATH . '/footer.php' ) ) include_once( VIEW_PATH . '/footer.php' );
		$this->beanUi->reset_session_vars();
	}
	
	public function logout() {
		$this->set_session( "auth_user", array() );
		redirect("login.php");
	}
	
	public function tag_suggestion() {
		$tag_html = "";
		$keyword = $this->bean->get_request("keyword");
		$id = $this->bean->get_request("post_id");
		if( $keyword != "" ) {
			$query 		= "SELECT tag_name FROM master_tags WHERE tag_name LIKE '%".$keyword."%' GROUP BY tag_name";
			$tag_rows 	= $this->dao->select($query);
			if( count($tag_rows) > 0 ) {
				$post_tags_str = "";
				if( $id ) {
					$query = "SELECT tag_keys FROM ".$this->dao->_table." WHERE id = ".$id;
					$post_tag_row = $this->dao->select($query);
					$post_tags_str = (count($post_tag_row) > 0) ? $post_tag_row[0]->tag_keys : "";
				}
				$post_tags = ( $post_tags_str != "" ) ? explode( ",", $post_tags_str ) : array();
				
				$tag_html = '<ul id="taglist">'."\n";
				foreach( $tag_rows as $row ) {
					if( in_array( $row->tag_name, $post_tags ) ) continue;
					$tag_html .= '<li onClick="select_tag(\''.$row->tag_name.'\');">'.$row->tag_name.'</li>'."\n";
				}
				$tag_html .= '</ul>'."\n";
			}
		}
		die($tag_html);
	}
        
        public function eventCat()
        {
            $keywordds = $this->dao->getEventCategory();
            show($keywordds);
        }
}
