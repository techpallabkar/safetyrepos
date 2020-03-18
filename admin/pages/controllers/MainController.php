<?php
class MainController {
	private $admin_auth_user 	= array();
	private $status_options 	= array();
	private $stylesheets 		= array();
	private $javascript_files 	= array();
	
	protected $bean 			= array();
	protected $dao 				= array();
	protected $action 			= "";	
	public $beanUi 				= array();
	 
	
	public function __construct() {
		$this->admin_auth_user 	= $this->get_session("admin_auth_user");
		$this->status_options 	= $this->get_session("status_options");
		
		$this->bean 		= empty($this->bean) ? new MasterBean() : $this->bean;
		$this->beanUi 		= new BeanUi();
		
		$this->action	= $this->bean->get_request("action");
	}
	
	public function get_auth_user($field = "") {
		return isset($this->admin_auth_user->{$field}) ? $this->admin_auth_user->{$field} : $this->admin_auth_user;
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
		
		$auth_user_id = isset($this->admin_auth_user->id) ? $this->admin_auth_user->id : 0;
		if( ! $auth_user_id && PAGENAME != 'login.php' ) {
			$this->logout();
		} elseif( $auth_user_id && PAGENAME == "login.php" && $this->action != "logout" ) {
			redirect( page_link("dashboard.php") );
		}
	}
	
	public function doAction() {
		
		$this->userAuthentication();
		
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
		$this->beanUi->reset_messages();
	}
	
	protected function logout() {
		$this->set_session( "admin_auth_user", array() );
		redirect(page_link("users/login.php"));
	}
	
	public function activation() {
		$data 		= array();
		$status_id 	= $this->bean->get_request('status_id');
		$nextStatus = ($status_id == 2) ? 3 : 2;
		//die($nextStatus);
		$returnned 	= array( 'success' => 0, 'status_id' => $nextStatus, 'message' => '' );
		
		if( $this->bean->get_request('id') ) {
			$data['id'] 		= $this->bean->get_request('id');
			$data['status_id'] 	= $nextStatus;
			
			$activ_msg = "";
			if( $data['status_id'] == 2 ) $activ_msg = "Successfully activated.";
			elseif( $data['status_id'] == 3 ) $activ_msg = "Successfully deactivated.";
			else $activ_msg = "Failed to update status.";
			
			$returnned['message'] = '<div class="error">'.$activ_msg.'</div><br />';
			if( $this->dao->save($data) ) {
				$returnned['success'] = 1;
				$returnned['message'] = '<div class="success">'.$activ_msg.'</div><br />';
			}
		}
		$this->json_encoded_output($returnned);
	}
	
	public function bulk_active_innactive() {
		
		$ids 		= $this->bean->get_request("ids");
		$ids_arr 	= explode( ',', $ids );
		$status_id 	= $this->bean->get_request("status_id");
		$statustext = "";
		if( $status_id == 2 ) $statustext = "Successfully deactivated.";
		elseif( $status_id == 3 ) $statustext = "Successfully activated.";
		
		$success = 0;
		if( !empty( $ids_arr ) ) {
			foreach( $ids_arr as $id ) {
				//if( $id == 1 ) continue;
				if( $this->dao->toggle_activation( array( 'id' => $id, "status_id" => $status_id ) ) ) $success++;
			}
		}
		//die("$success");
		$this->beanUi->set_error_message( "Not updated." );
		if( $success ) $this->beanUi->set_success_message( $statustext );
		redirect("./");
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
        
     public function get_financial_year_month_day($year, $month, $format="Y"){
        if( $month >= 4 ) {
            $dtt=$year."-04-01";
            $pt = date($format, strtotime('+1 year',strtotime($dtt)));
            $ptt=date($format,strtotime($dtt)).'-04-01,'.$pt.'-03-31';
            }
            else {
            $dtt=$year."-04-01";	
            $pt=date($format, strtotime('-1 year',strtotime($dtt)));
            $ptt = $pt."-04-01,".date($format,strtotime($dtt))."-03-31";
            }
        return $ptt;
    }
    
     public function get_multiple_finalcial_year_and_month_date($set_date){
   
            $arr_return = array();
            //make current month start and end//
            $given_month_start_date        =   date("Y-m-d", strtotime("01-".$set_date));
            $monthback        =   date("Y-m-t", strtotime("-1 months",strtotime($given_month_start_date)));
            $given_month_end_date          =   date("Y-m-d", strtotime(date('t', strtotime($given_month_start_date))."-".$set_date));
            $asd          =   date("Y-m-d", strtotime(date('t', strtotime($monthback))."-".$set_date));
          
          
//echo date("Y-m-t", strtotime($monthback));
//make previous month start and end//
            $get_previous_month_start_date =   date("Y-m-d", strtotime('-1 months', strtotime("01-".$set_date)));
            $arr_cm                        =   explode("-",$get_previous_month_start_date);
            $get_previous_month_end_date   =   date("Y-m-d", strtotime(date('t', strtotime($get_previous_month_start_date))."-".$arr_cm[1]."-".$arr_cm[0]));
            //make next month start and end//
            $get_next_month_start_date     =   date("Y-m-d", strtotime('+1 months', strtotime("01-".$set_date)));
            $arr_nm                        =   explode("-",$get_next_month_start_date);
            $get_next_month_end_date       =   date("Y-m-d", strtotime(date('t', strtotime($get_next_month_start_date))."-".$arr_nm[1]."-".$arr_nm[0]));
            //make finalcial year start and end//
            $month_yearexp                 =   explode("-",$set_date);
            $current_financial_year        =   $this->get_financial_year_month_day($month_yearexp[1],$month_yearexp[0],"Y");
            $current_finalcial_year_arr    =   explode(",", $current_financial_year);
            $previous_financial_year       =   $this->get_financial_year_month_day(($month_yearexp[1]-1),$month_yearexp[0],"Y");
            $previous_financial_year_arr   =   explode(",", $previous_financial_year);
            $pre_of_prev_financial_year    =   $this->get_financial_year_month_day(($month_yearexp[1]-2),$month_yearexp[0],"Y"); 
            $pre_of_prev_finan_year_arr    =   explode(",", $pre_of_prev_financial_year);
            $next_financial_year           =   $this->get_financial_year_month_day(($month_yearexp[1]+1),$month_yearexp[0],"Y");
            $next_financial_year_arr       =   explode(",", $next_financial_year);
            //make YTM finalcial year start and end//
            $current_financial_year_ytm    = $current_finalcial_year_arr[0].",".$given_month_end_date;
            $current_financial_year_ytm_mback    = "'".$current_finalcial_year_arr[0]."' AND '".$monthback."'";
            $previous_financial_year_ytm   = $previous_financial_year_arr[0].",".$get_previous_month_end_date;
            $next_financial_year_ytm       = $next_financial_year_arr[0].",".$get_next_month_end_date;
            
            //month basis
            $arr_return["----------------month basis---------------"]="0";
            $arr_return["CM"]              =   $given_month_start_date.",".$given_month_end_date;
            $arr_return["CM_QRY"]          =   "'".$given_month_start_date."' AND '".$given_month_end_date."'";
            $arr_return["PM"]              =   $get_previous_month_start_date.",".$get_previous_month_end_date;
            $arr_return["PM_QRY"]          =   "'".$get_previous_month_start_date."' AND '".$get_previous_month_end_date."'";
            $arr_return["NM"]              =   $get_next_month_start_date.",".$get_next_month_end_date;
            $arr_return["NM_QRY"]          =   "'".$get_next_month_start_date."' AND '".$get_next_month_end_date."'";
            //year basis 
            $arr_return["----------------year basis ---------------"]="0";
            $arr_return["CFY"]             =   $current_financial_year;
            $arr_return["CFY_FORMATTED"]   =   date("Y",strtotime($current_finalcial_year_arr[0]))."-".date("Y",strtotime($current_finalcial_year_arr[1]));
            $arr_return["CFY_QRY"]         =   "'".$current_finalcial_year_arr[0]."' AND '".$current_finalcial_year_arr[1]."'";
            $arr_return["PFY"]             =   $previous_financial_year;
            $arr_return["PFY_FORMATTED"]   =   date("Y",strtotime($previous_financial_year_arr[0]))."-".date("Y",strtotime($previous_financial_year_arr[1]));
            $arr_return["PFY_QRY"]         =   "'".$previous_financial_year_arr[0]."' AND '".$previous_financial_year_arr[1]."'";
            $arr_return["POPFY"]           =   $pre_of_prev_financial_year;
            $arr_return["POPFY_QRY"]       =   "'".$pre_of_prev_finan_year_arr[0]."' AND '".$pre_of_prev_finan_year_arr[1]."'";
            $arr_return["NFY"]             =   $next_financial_year;
            $arr_return["NFY_QRY"]         =   "'".$next_financial_year_arr[0]."' AND '".$next_financial_year_arr[1]."'";
            //YTM basis
            $arr_return["----------------YTM basis-----------------"]="0";
            $arr_return["CFY_YTM"]         =   $current_financial_year_ytm;
            $arr_return["CFY_YTM_MBACK"]         =   $current_financial_year_ytm_mback;
            $arr_return["CFY_YTM_QRY"]     =   "'".$current_finalcial_year_arr[0]."' AND '".$given_month_end_date."'";
            $arr_return["PFY_YTM"]         =   $previous_financial_year_ytm;
            $arr_return["PFY_YTM_QRY"]     =   "'".$previous_financial_year_arr[0]."' AND '".$get_previous_month_end_date."'";
            
            
            $arr_return["NFY_YTM"]         =   $next_financial_year_ytm;
            $arr_return["NFY_YTM_QRY"]     =   "'".$next_financial_year_arr[0]."' AND '".$get_next_month_end_date."'";
            //year month basis
            $arr_return["----------------year month basis----------"]="0";
            $arr_return["CY_AND_CM"]       =   date("Y-m", strtotime("01-".$set_date));
            $arr_return["PY_AND_CM"]       =   date("Y-m", strtotime('-1 years', strtotime("01-".$set_date)));
            $arr_return["POPY_AND_CM"]     =   date("Y-m", strtotime('-2 years', strtotime("01-".$set_date)));
            $arr_return["PFY_YR_YTM_QRY"]     =   "'".$previous_financial_year_arr[0]."' AND '".$arr_return["PY_AND_CM"].date('-t')."'";
            return $arr_return;
        }
        
}
