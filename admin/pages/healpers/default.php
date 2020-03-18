<?php
function createTreeView($currentParent = 0, $currLevel = 0, $prevLevel = -1) {
    $mainDao = new MainDao();
    $result = $mainDao->db->prepare("select * FROM division_department WHERE parent_id=".$currentParent);
    $result->execute();
    $rowFetchData = $result->fetchAll($mainDao->fetch_obj);
  
	foreach ($rowFetchData as $category) {
		if ($currentParent == $category->parent_id) {

			$asd[$category->id]->test= $category->name.'<br />';

                        
                   $query = "select * from division_department "
                . "where parent_id IN "
                . "(select parent_id from division_department where parent_id IN (select parent_id from division_department where parent_id=".$category->id.") "
                . "OR parent_id IN (select parent_id from division_department where parent_id=".$category->id."))";
              
		}
	}
        showPre($asd);
}
          
function create_tree_view_cms($array, $currentParent = 0, $currLevel = 0, $prevLevel = -1, $cat_id = 0, $select_all = 0) {

	foreach ($array as $category) {

		if ($currentParent == $category->parent_id) {
			if ($currLevel > $prevLevel) echo " <ol class='tree'> ";
			if ($currLevel == $prevLevel) echo " </li> ";
			
			$color = ( $category->id == $cat_id ) ? ' style="color:#ee0000;"' : "";
			
			if( ! $select_all ) {
				// find child
				$is_parent = 0;
				foreach( $array as $row ) {
					if( $row->parent_id == $category->id ) $is_parent++;
				}
				if( ! $is_parent ) {
					
					echo '<li> <label for="category-'.$category->id.'" '.$color.' title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name).'</label> '."\n";
					echo '<input name="category-'.$category->id.'" type="checkbox" id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				} else {
					echo '<li> <span style="display:inline-block;padding-left:12px;" title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name)."</span>\n";
					echo '<input name="category-'.$category->id.'" type="checkbox" id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				}
			} else {
				if( $category->parent_id != 0 ) {
					echo '<li> <label for="category-'.$category->id.'" '.$color.' title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name)."</label>\n";
					echo '<input name="category-'.$category->id.'" type="checkbox" id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				} else {
					echo '<li> <span style="display:inline-block;padding-left:12px;" title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name)."</span>\n";
					echo '<input name="category-'.$category->id.'" type="checkbox" id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				}
			}
			
			
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++;
			create_tree_view_cms ($array, $category->id, $currLevel, $prevLevel, $cat_id, $select_all);
			$currLevel--;
		}
	}
	if ($currLevel == $prevLevel) echo " </li>  </ol> ";
}
function create_tree_view($array, $currentParent = 0, $currLevel = 0, $prevLevel = -1, $cat_id = 0, $select_all = 0) {

	foreach ($array as $category) {

		if ($currentParent == $category->parent_id) {
			if ($currLevel > $prevLevel) echo " <ol class='tree'> ";
			if ($currLevel == $prevLevel) echo " </li> ";
			
			//$color = ( $category->id == $cat_id ) ? ' style="color:#ee0000;"' : "";
                     
                                        if(in_array( $category->id, $cat_id )) 
                                        { 
                                            $color = 'style="color:#ee0000;"'; 
                                            $checkbox2 = ' checked="checked" '; 
											
                                        } 
                                        else 
                                        {
                                            $color = '';
                                            $checkbox2 = ''; 
                                        };
                                        if($category->parent_id == 0 || $category->parent_id == 1)
                                        {
                                            $checked_box = 'checked';
                                        }
                                        else
                                        {
                                            $checked_box = '';
                                        }
			
			if( ! $select_all ) {
				// find child
				$is_parent = 0;
				foreach( $array as $row ) {
					if( $row->parent_id == $category->id ) $is_parent++;
				}
				if( ! $is_parent ) {
					
					echo '<li> <label for="category-'.$category->id.'" '.$color.' title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name).'</label> '."\n";				
                                        echo '<input class="newtree" name="division[]" type="checkbox" '.$checkbox2.'  id="'.$category->id.'" value="tree_'.$category->id.'" />';
				} else {
					echo '<li> <span style="display:inline-block;padding-left:12px;" title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name)."</span>\n";
					echo '<input  name="category-'.$category->id.'" type="checkbox" '.$checked_box.'  id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				}
			} else {
				if( $category->parent_id != 0 ) {
                                    
					echo '<li> <label for="category-'.$category->id.'" '.$color.' title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name)."</label>\n";
					echo '<input class="newtree" name="division[]" type="checkbox" '.$checkbox2.' id="'.$category->id.'" value="tree_'.$category->id.'"  />';
				} else {
					echo '<li> <span style="display:inline-block;padding-left:12px;" title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name)."</span>\n";
					echo '<input name="category-'.$category->id.'" type="checkbox"   id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				}
			}
			
			
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++;
			create_tree_view ($array, $category->id, $currLevel, $prevLevel, $cat_id, $select_all);
			$currLevel--;
		}
	}
	if ($currLevel == $prevLevel) echo " </li>  </ol> ";
}
function create_tree_view2($array, $currentParent = 0, $currLevel = 0, $prevLevel = -1, $cat_id = 0, $select_all = 0) {

	foreach ($array as $category) {

		if ($currentParent == $category->parent_id) {
			if ($currLevel > $prevLevel) echo " <select>";
			if ($currLevel == $prevLevel) echo " </option> ";
			
			//$color = ( $category->id == $cat_id ) ? ' style="color:#ee0000;"' : "";
                     
                                        if(in_array( $category->id, $cat_id )) 
                                        { 
                                            $color = 'style="color:#ee0000;"'; 
                                            $checkbox2 = ' checked="checked" '; 
											
                                        } 
                                        else 
                                        {
                                            $color = '';
                                            $checkbox2 = ''; 
                                        };
                                        if($category->parent_id == 0 || $category->parent_id == 1)
                                        {
                                            $checked_box = 'checked';
                                        }
                                        else
                                        {
                                            $checked_box = '';
                                        }
			
			if( ! $select_all ) {
				// find child
                            echo '<select>';
				$is_parent = 0;
				foreach( $array as $row ) {
					if( $row->parent_id == $category->id ) $is_parent++;
				}
				if( ! $is_parent ) {
					
					echo '<option> '.htmlspecialchars($category->name).''."\n";				
                                      //  echo '<input class="newtree" name="division[]" type="checkbox" '.$checkbox2.'  id="'.$category->id.'" value="tree_'.$category->id.'" />';
				} else {
					echo '<option> '.htmlspecialchars($category->name)."\n";
					//echo '<input  name="category-'.$category->id.'" type="checkbox" '.$checked_box.'  id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				}
                                echo '</select>';
			} else {
                            echo '<select>';
				if( $category->parent_id != 0 ) {
                                    
					echo '<option> '.htmlspecialchars($category->name).'\n';
					//echo '<input class="newtree" name="division[]" type="checkbox" '.$checkbox2.' id="'.$category->id.'" value="tree_'.$category->id.'"  />';
				} else {
					echo '<option> '.htmlspecialchars($category->name)."\n";
					//echo '<input name="category-'.$category->id.'" type="checkbox"   id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				}
                                echo '</select>';
			}
			
			
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++;
			create_tree_view2 ($array, $category->id, $currLevel, $prevLevel, $cat_id, $select_all);
			$currLevel--;
		}
	}
	if ($currLevel == $prevLevel) echo " </option>  </select> ";
}
function load_tag_html( $tag_keys = "" ) {
	if( $tag_keys == "" ) return "";
	$tag_html = "";
	$tag_array = explode(",",$tag_keys);
	
	foreach( $tag_array as $i => $tag ) {
		$tag_html .= '<span id="tagno-'.$i.'">'.$tag.' <img src="'.url("assets/images/delete.gif").'" style="cursor:pointer" onclick="remove_tag('.$i.')" /></span>,';
	}
	$tag_html = trim($tag_html, ",");

	return $tag_html;
}

function upload($data = array()) {
	$input_name 	= isset( $data["input_name"] ) ? $data["input_name"] : "input_name";
	$upload_type 	= isset( $data["file_type"] ) ? strtolower($data["file_type"]) : "";
	$upload_path 	= isset( $data["upload_path"] ) ? $data["upload_path"] : UPLOADS_PATH;
	$upload_path 	= ( $upload_path == "" ) ? UPLOADS_PATH : $upload_path;
	$index_no 	= isset( $data["index_no"] ) ? $data["index_no"] : "single";
	$upload 	= array( "upload_path" => "", "file_type" => "", "name" => "", "error" => "" );
	
        
        
	if( $input_name == "" || ! isset( $_FILES[$input_name] ) ) {
		$upload["error"] = "File input type name is incorrect.";
		return $upload;
	}
	
        
	if( ! is_dir( $upload_path ) ) {
		$restdirname = str_replace(UPLOADS_PATH.'/', '', $upload_path);
		$restDirArr = explode('/', $restdirname);
		if( !empty($restDirArr) ) {
			$cascadingDir = '';
			foreach( $restDirArr as $dir_name ) {
				if( strlen($dir_name) > 2 ) {
					$cascadingDir .= '/'.$dir_name;
					$fullpath = UPLOADS_PATH.$cascadingDir;
					//echo $fullpath.'<br />';
					@mkdir($fullpath, 0777);
					@chmod($fullpath, 0777);
				}
			}
		}
	}
	
	if( ! is_dir( $upload_path ) ) {
		$upload["error"] = "Please create $upload_path directory. Or check directory permission.";
	}
	
	if( ! empty( $upload["error"] ) ) return $upload;
	$filename 	= $_FILES[ $input_name ]["name"];
     
        if( is_array($filename) ) {
		$file_name 		= $_FILES[ $input_name ]["name"][$index_no];
		$file_tmp_path          = $_FILES[ $input_name ]["tmp_name"][$index_no];
                
		$file_size 		= $_FILES[ $input_name ]["size"][$index_no];
		$file_type 		= $_FILES[ $input_name ]["type"][$index_no];
	} else {
		$file_name 		= $_FILES[ $input_name ]["name"];
		$file_tmp_path          = $_FILES[ $input_name ]["tmp_name"];
		$file_size 		= $_FILES[ $input_name ]["size"];
		$file_type 		= $_FILES[ $input_name ]["type"];
	}
	
	if( $file_name == "" ) {
		return FALSE;
	}
	
	if( ! @file_exists( $file_tmp_path ) ) {
		$upload["error"] = "Tmp file not found.";
	}
	//if( is_array($file_name) ) {
	//	show($file_name);
	//}
	$split_file_name 	= explode('.', $file_name);
	$file_ext 			= @end($split_file_name);
	$file_ext 			= ($file_ext != "") ? strtolower($file_ext) : $file_ext;
	
	$valid = 0;
	$valid_extns = array();
        if( $upload_type == "image" ) {
		$valid_extns = array("jpg", "jpeg", "png", "gif");
		foreach( $valid_extns as $itype) {
			if( $file_ext == $itype ) {
				$valid = 1;
				break;
			}
		}
	} elseif( $upload_type == "video" ) {
		$valid_extns = array("mp4");
		foreach( $valid_extns as $itype) {
			if( $file_ext == $itype ) {
				$valid = 1;
				break;
			}
		}
	}
        elseif( $upload_type == "file" ) {
		$valid_extns = array("pdf");
		foreach( $valid_extns as $itype) {
			if( $file_ext == $itype ) {
				$valid = 1;
				break;
			}
		}
	} 
        elseif( $upload_type == "" ) {
		$valid = 1;
	}
       
	if( ! $valid ) {
		$ext_str = ! empty( $valid_extns ) ? "Upload only " . implode( ",", $valid_extns )." extention files." : "";
		$upload["error"] = "Please upload valid file.".$ext_str;
	} else {
           // show($split_file_name[0]);
            $newstr = preg_replace('/[^a-zA-Z0-9\']/', '_', $split_file_name[0]);
            $newstr = str_replace("'", '', $newstr);
		$upload_file_path = $upload_path . "/" . $newstr . rand(1, 10000) . "_" . time() . ".".$file_ext;
                
//		$moved = @move_uploaded_file( $file_tmp_path, $upload_file_path );

                if($upload_type == "image"){
                   $moved = compress_image($file_tmp_path, $upload_file_path, $width, $height, $quality); 
                }else{
                   $moved = @move_uploaded_file( $file_tmp_path, $upload_file_path ); 
                }
                
		
		if( ! $moved ) {
			$upload["error"] = "File could not uploaded to [ $upload_path ] destination. Please contact to administrator.";
		} else {
			$upload[ "upload_path" ] = str_replace( dirname(BASE_PATH)."/", "", $upload_file_path);
			$upload[ "file_type" ] = $file_type;
			$upload[ "name" ] = $split_file_name[0];
		}
	}
	
	return $upload;
}




function get_page_html($dao = array()) {
	if( empty( $dao ) ) return "";
	$maxRow = isset($dao->pagging->max_row) ? $dao->pagging->max_row : 0;
	if( $maxRow == 0 ) return '';
	
	$limit 			= isset($dao->pagging->limit) ? $dao->pagging->limit : 5;//$param['limit'];
	$page 			= isset($dao->pagging->page) ? $dao->pagging->page : 1;//$param['page'];
	
	$total_pages 	= ($limit > 0) ? ceil($maxRow/$limit) : 1;
	//$paggin_html 	= '<div class="dataTables_info" id="employee-grid_info" role="status" aria-live="polite">Showing ';
	//$paggin_html 	.= $limit . ' of '.$maxRow.' entries</div>'."\n";
	$paggin_html 	= '<div class="dataTables_info" id="employee-grid_info" role="status" aria-live="polite"> Total records : '.$maxRow.'</div>'."\n";
	$paggin_html 	.= '<div class="dataTables_paginate paging_simple_numbers" id="employee-grid_paginate">';
	
	if( ! empty($_GET) && $link == "" ) {
		foreach( $_GET as $name => $val ) {
			if( $name == 'page' ) continue;
			$link .= '&'.$name.'='.$val;
		}
		$link = ( $link != '' ) ? trim( $link, '&' ) : '';
	}
	if( $total_pages > 1 ) {
		$prev_disabled 	= ($page == 1) ? 'disabled' : '';
		$prev_pageno 	= ($page == 1) ? 1 : ($page - 1);
		$paggin_html 	.= '<a class="paginate_button previous '.$prev_disabled.'" href="?page='.$prev_pageno.'&'.$link.'">Previous</a>'."\n";
		for( $p = 1; $p <= $total_pages; $p++ ) {
			$current = '';
			if( $p == $page ) $current = 'current';
			$paggin_html .= '<a class="paginate_button '.$current.'" href="?page='.$p.'&'.$link.'">'.$p.'</a>'."\n";
		}
		$nex_disabled 	= ($page == $total_pages) ? 'disabled' : '';
		$next_pageno 	= ($page < $total_pages) ? ($page + 1) : $total_pages;
		$paggin_html 	.= '<a class="paginate_button next '.$nex_disabled.'" href="?page='.$next_pageno.'&'.$link.'">Next</a>'."\n";
	} else {
		$paggin_html .= '<a class="paginate_button current" aria-controls="employee-grid" data-dt-idx="1" tabindex="0">1</a>'."\n";
	}
	$paggin_html 	.= '</div>'."\n";
	return $paggin_html;
}

function page_link($page_link = "", $text = "", $attr = "") {
	$page_link = ( $page_link == "" ) ? $_SERVER["PHP_SELF"] : url("pages/master/".$page_link);
	return ( $text == "" ) ? $page_link : "<a href=\"".$page_link."\" ".$attr.">".stripslashes($text)."</a>";
}

function redirect( $path = "" ) {
	$path = ($path == "") ? current_url() : $path;
	header( 'location:' . $path );
	exit;
}

function load_dao($daofile = "") {
	$dao = array();
	if( $daofile == "" ) {
		$dbconDao = DAO_PATH . "/DbCon.php";
		if( file_exists( $dbconDao ) ) require_once( $dbconDao );
		$mainDao = DAO_PATH . "/MainDao.php";
		if( file_exists( $mainDao ) ) require_once( $mainDao );
	} elseif( $daofile != "" ) {
		$daofilepath = DAO_PATH . "/".$daofile.".php";
		//die($daofile);
		if( file_exists( $daofilepath ) ) {
			require_once( $daofilepath );
			$dao = new $daofile();
		}
	}
	return $dao;
}

function load_bean($beanfile = "") {
	$bean = array();
	if( $beanfile == "" ) {
		$beanfilepath = BEAN_PATH . "/MasterBean.php";
		if( file_exists( $beanfilepath ) ) require_once( $beanfilepath );
	} elseif( $beanfile != "" ) {
		$beanfilepath = BEAN_PATH . "/".$beanfile.".php";
		//die($daofile);
		if( file_exists( $beanfilepath ) ) {
			require_once( $beanfilepath );
			$bean = new $beanfile();
		}
	}
	return $bean;
}

function load_beanUi($beanfile = "") {
	$bean = array();
	if($beanfile == "" ) {
		$beanfilepath = BEAN_UI_PATH . "/BeanUi.php";
		if( file_exists( $beanfilepath ) ) require_once( $beanfilepath );
	} elseif( $beanfile != "" ) {
		$beanfilepath = BEAN_UI_PATH . "/".$beanfile.".php";
		if( file_exists( $beanfilepath ) ) {
			require_once( $beanfilepath );
			$bean = new $beanfile();
		}
	}
	return $bean;
}

function load_controller( $controller_name = "MainController" ) {
	$controllerFile = CONTROLLER_PATH . "/".$controller_name.".php";
	if( file_exists($controllerFile) ) require_once( $controllerFile );
	if( $controllerFile != "MainController" ) return new $controller_name();
}

function get_id_from_array( $fieldname = "", $text = "", $array = array() ) {
	if( empty( $array ) ) return 0;
	$id = 0;
	foreach( $array as $row ) {
		if( isset($row->$fieldname) ) {
			if( $row->$fieldname == $text ) {
				$id = $row->id;
				break;
			}
		}
	}
	return $id;
}

function get_value_by_id($id = 0, $field = "", $array = array()) {
	if( ! $id || $field == "" || empty($array) ) return false;
	
	$field_val = "";
	foreach( $array as $row ) {
		if( $row->id == $id ) {
			$field_val = isset( $row->$field ) ? $row->$field : "";
			break;
		}
	}
	return $field_val;
}

function current_url() {
	return sprintf(
		"%s://%s%s",
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		$_SERVER['SERVER_NAME'],
		$_SERVER['REQUEST_URI']
	);
}

function url($rest = ""){
	return sprintf( 
		"%s://%s%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', 
		$_SERVER['SERVER_NAME'], 
		"/".FOLDERNAME."/".BASE_DIR_NAME."/" 
	).$rest;
}

function site_url($url) {
	return dirname(url()).'/'.$url;
}

function active_icons($link, $active, $id) {
	if( $link != "" ) return ( !$active ) ? 
		page_link(
			'#', 
			'<img src="'.url('assets/images/icon1Inactive.png').'" />', 
			' onclick="activation(\''.page_link($link).'\', '.$id.')" class="activate" id="activate-'.$id.'"'
		) : 
		page_link(
			'#', 
			'<img src="'.url('assets/images/icon1Active.png').'" />', 
			' onclick="activation(\''.page_link($link).'\', '.$id.')" class="activate" id="activate-'.$id.'"'
		);
}

function isValidEmail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
}

if( ! function_exists( "hex2bin" ) ) {
	function hex2bin($hexstr) {
		$n 		= strlen($hexstr);
		$sbin 	= "";
		$i 		= 0;
		while( $i < $n ) {
			$a = substr($hexstr, $i, 2);
			$c = pack("H*", $a);
			if ( $i == 0 ) $sbin = $c;
			else $sbin.= $c;
			$i += 2;
		}
		return $sbin;
	}
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function is_url_exist($file){
    $file_headers = @get_headers($file);
	if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
		$exists = false;
	}
	else {
		$exists = true;
	}
   return $exists;
}

function get_filename_and_ext($file = '') {
	if( $file == "" ) return array( '', '' );
	$filename = @end(explode('/', $file));
    $ext = @end(explode('.', $filename));
	$name = str_replace( '.'.$ext, '', $filename);
	return array( $name, $ext );
}

function showHtml( $html = '' ) {
	die( htmlspecialchars($html) );
}

function show( $var = array() ) {
	echo "<pre>";
	print_r( $var );
	echo "</pre>";
	die;
}
function showPre( $var = array() ) {
	echo "<pre>";
	print_r( $var );
	echo "</pre>";
	
}

function formatSizeUnits($bytes) {
	if ($bytes >= 1073741824) {
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	} elseif ($bytes >= 1048576) {
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	} elseif ($bytes >= 1024) {
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	} elseif ($bytes > 1) {
		$bytes = $bytes . ' bytes';
	} elseif ($bytes == 1) {
		$bytes = $bytes . ' byte';
	} else {
		$bytes = '0 bytes';
	}
	return $bytes;
}
function mb2Bytes($mb = 0) {
	return ($mb * 1048576);
}
function resize_image($imagefile = '', $destination = '') {
	$img = new SimpleImage($imagefile);
	// Resize the image to 320x200
	$img->resize(320, 200);
	$img->save($destination);
}
function getPrevFinancialYear($year,$month,$format="Y")
    {
        if($month>=4) {
            $dtt=$year."-04-01";
            $pt = date($format, strtotime('+1 year',strtotime($dtt)));
            $ptt=date($format,strtotime($dtt)).'-04,'.$pt.'-03';
            }
            else {
            $dtt=$year."-04-01";	
            $pt=date($format, strtotime('-1 year',strtotime($dtt)));
            $ptt=$pt."-04,".date($format,strtotime($dtt))."-03";
            }
        return $ptt;
    }
function getFinancialYear($year,$month,$format="Y")
    {
        if($month>=4) {
            $dtt=$year."-04-01";
            $pt = date($format, strtotime('+1 year',strtotime($dtt)));
            $ptt=date($format,strtotime($dtt)).'-'.$pt;
            }
            else {
            $dtt=$year."-04-01";	
            $pt=date($format, strtotime('-1 year',strtotime($dtt)));
            $ptt=$pt."-".date($format,strtotime($dtt));
            }
        return $ptt;
    }
    
    function getFinancialYearMonth($year,$month,$format="Y")
    {
        if($month>=4) {
           
            $dtt=$year."-04-01";
            $pt = date($format, strtotime('+1 year',strtotime($dtt)));
            $ptt=date($format,strtotime($dtt)).'-04';
            }
            else {
            $dtt=$year."-04-01";	
            $pt=date($format, strtotime('-1 year',strtotime($dtt)));
            $ptt=$pt."-04";
            }
        return $ptt;
    }
    
    function getAllMonthYear($year)
    {
        $exp = explode("-",$year);
        $date1 = $exp[0]."-04-01";
        $date2 = $exp[1]."-03-01";
        
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);
        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        $montharr= array();
       
       for($i=0;$i<=$diff;$i++)
        {
           $montharr[$i]= date("Y-m",strtotime("+$i Month",strtotime($date1)));
           
       }
       
        return $montharr;
    }
    
    function getAllYTMYear($year,$month)
    {
        $exp = explode("-",$year);
        $year1 = $exp[0];
        if($month >= 4) {
            $year2 = $exp[0];
        } else {
            $year2 = $exp[1];
        }
        
        $date1 = $year1."-04-01";
        $date2 = $year2."-$month-01";
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);
        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        $montharr= array();
       
       for($i=0;$i<=$diff;$i++)
        {
           $montharr[$i]= date("Y-m",strtotime("+$i Month",strtotime($date1)));
           
       }
        return $montharr;
    }
    
    function getAllYTMMonthYear($year,$month)
    {
        $exp = explode("-",$year);
        $year1 = $exp[0];
        if($month >= 4) {
            $year2 = $exp[0];
        } else {
            $year2 = $exp[1];
        }
        
        $date1 = $year1."-04-01";
        $date2 = $year2."-$month-01";
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);
        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        $montharr= array();
       
       for($i=0;$i<=$diff;$i++)
        {
           $montharr[$i]= date("m-Y",strtotime("+$i Month",strtotime($date1)));
           
       }
        return $montharr;
    }
    
    
  function create_tree_view_new($array, $currentParent = 0, $currLevel = 0, $prevLevel = -1, $cat_id = 0, $select_all = 0) {

	foreach ($array as $category) {

		if ($currentParent == $category->parent_id) {
			if ($currLevel > $prevLevel) echo " <ul class='tree1'> ";
			if ($currLevel == $prevLevel) echo " </li> ";
			
			$color = ( $category->id == $cat_id ) ? ' style="color:#ee0000;"' : "";
			
			if( ! $select_all ) {
				// find child
				$is_parent = 0;
				foreach( $array as $row ) {
					if( $row->parent_id == $category->id ) $is_parent++;
				}
				if( ! $is_parent ) {
					
					echo '<li> <label for="category-'.$category->id.'" '.$color.' title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name).'</label> '."\n";
					echo '<input name="category-'.$category->id.'" type="hidden" id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				} else {
					echo '<li> <span style="display:inline-block;padding-left:;" title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name)."</span>\n";
					echo '<input name="category-'.$category->id.'" type="hidden" id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				}
			} else {
				if( $category->parent_id != 0 ) {
					echo '<li > <label for="category-'.$category->id.'" '.$color.' title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name)."</label>\n";
					echo '<input name="category-'.$category->id.'" type="hidden" id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				} else {
					echo '<li class="over-tree-con"> <span style="display:inline-block;padding-left:;" title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name)."</span>\n";
					echo '<input name="category-'.$category->id.'" type="hidden" id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				}
			}
			
			
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++;
			create_tree_view_new ($array, $category->id, $currLevel, $prevLevel, $cat_id, $select_all);
			$currLevel--;
		}
	}
	if ($currLevel == $prevLevel) echo " </li>  </ul> ";
}  

function get_image_croper_box($image_path = "", $root_url = "") {
	
	if( ! is_file( CESC_BASE_PATH . "/" . $image_path ) || $image_path == "" ) return "";
	$root_url = ( $root_url == "" ) ? url() : $root_url;
	list($width, $height, $type, $attr) = getimagesize(CESC_BASE_PATH . "/" . $image_path);
	$style = ( $width > 500 ) ? ' style="width:500px;"' : "";
	
	?>
	<!--<link rel="stylesheet" type="text/css" href="<?php echo url("assets/cropimage/css/style.css");?>" /> -->
	<link rel="stylesheet" type="text/css" href="<?php echo url("assets/cropimage/css/imgareaselect-default.css");?>" />
	<script src="<?php echo url("assets/cropimage/js/jquery.min.js");?>"></script>  
	<script type="text/javascript" src="<?php echo url("assets/cropimage/js/67hbnkm.imgareaselect.pack.js");?>"></script>
	<h3>Drag on image and select an area to crop</h3>
	<img id="photo" src="http://localhost/test/imgareaselect/nature.jpg" alt="" title="" <?php echo $style; ?> />
	<input type="hidden" name="x1" value="" />
	<input type="hidden" name="y1" value="" />
	<input type="hidden" name="x2" value="" />
	<input type="hidden" name="y2" value="" />
	<input type="hidden" name="w" value="" />
	<input type="hidden" name="h" value="" />
	<input type="submit" value="Crop" />
	<script>
        function preview(img, selection) { 
            var scaleX = 200 / (selection.width || 1); 
            var scaleY = 200 / (selection.height || 1); 
            jQuery('#photo + div > img').css({ 
                width: Math.round(scaleX * 400) + 'px', 
                height: Math.round(scaleY * 400) + 'px', 
                marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
                marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
            }); 
        } 

        jQuery(document).ready(function () { 
			
            jQuery('<div><img src="http://localhost/test/imgareaselect/nature.jpg" style="position: relative;" /><div>').css({ 
                float: 'left', 
                position: 'relative', 
                overflow: 'hidden', 
                width: '200px', 
                height: '200px',
                marginRight: '20px'
            }) .insertAfter(jQuery('#photo')); 

            jQuery('#photo').imgAreaSelect({ 
                aspectRatio: '1:1', 
                handles: true,
                persistent: true,
                x1: 100, y1: 100, x2: 300, y2: 300,
                onInit: preview,
                onSelectChange: preview, 
                onSelectEnd: function ( image, selection ) {
                    jQuery('input[name=x1]').val(selection.x1); 
                    jQuery('input[name=y1]').val(selection.y1); 
                    jQuery('input[name=x2]').val(selection.x2); 
                    jQuery('input[name=y2]').val(selection.y2);
                    jQuery('input[name=w]').val(selection.width);
            f/d
            ';h.'jQuery('input[name=h]').val(selection.height);  
                } 
            }); 
        }); 
    </script>
	<?php

      
        
}

function get_month_range($frmDt=null, $toDt=null){
    $retMonths = array();
    $time   = strtotime($frmDt);
    $last   = date('m-Y', strtotime($toDt));

    do{
       $month = date('m-Y', $time);
       $day = date('t', $time);
       $retMonths[] =  $day."-".$month;
       $time = strtotime('+1 month', $time);
    } while ($month != $last);
    
    return $retMonths;
}
//Compress_image
function compress_image($source_file, $target_file, $nwidth, $nheight, $quality) {
	//Return an array consisting of image type, height, widh and mime type.
	$image_info = getimagesize($source_file);
	if(!($nwidth > 0)) $nwidth = $image_info[0];
	if(!($nheight > 0)) $nheight = $image_info[1];
	

	/*echo '<pre>';
	print_r($image_info);*/
	if(!empty($image_info)) {
		switch($image_info['mime']) {
			case 'image/jpeg' :
				if($quality == '' || $quality < 0 || $quality > 100) $quality = 25; //Default quality
				// Create a new image from the file or the url.
				$image = imagecreatefromjpeg($source_file);
				$thumb = imagecreatetruecolor($nwidth, $nheight);
				//Resize the $thumb image
				imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
				// Output image to the browser or file.
				return imagejpeg($thumb, $target_file, $quality); 
				
				break;
			
			case 'image/png' :
				if($quality == '' || $quality < 0 || $quality > 9) $quality = 6; //Default quality
				// Create a new image from the file or the url.
				$image = imagecreatefrompng($source_file);
				$thumb = imagecreatetruecolor($nwidth, $nheight);
				//Resize the $thumb image
				imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
				// Output image to the browser or file.
				return imagepng($thumb, $target_file, $quality);
				break;
				
			case 'image/gif' :
				if($quality == '' || $quality < 0 || $quality > 100) $quality = 25; //Default quality
				// Create a new image from the file or the url.
				$image = imagecreatefromgif($source_file);
				$thumb = imagecreatetruecolor($nwidth, $nheight);
				//Resize the $thumb image
				imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
				// Output image to the browser or file.
				return imagegif($thumb, $target_file, $quality); //$success = true;
				break;
				
			default:
				echo "<h4>Not supported file type!</h4>";
				break;
		}
	}
}