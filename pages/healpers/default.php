<?php
function recent_post($recent_post) {
	if( empty($recent_post) ) return;
	?>
	<div class="mh-widget">
		<h4 class="mh-widget-title"><a href="#">Recent POST</a></h4>
		<ul class="mh-custom-posts-widget clearfix">
                    <?php foreach ($recent_post as $key => $articlevalue) { ?>
			<li class="mh-custom-posts-item mh-custom-posts-small clearfix">
				<div class="mh-custom-posts-thumb">
					<a href="postdata.php?id=<?php echo $articlevalue->id ;?>" title="Indulge yourself">
						<img width="80" height="80" src="<?php echo url($articlevalue->featured_image_path)?>" alt="" />
					</a>
				</div>
				<header class="mh-custom-posts-header">
					<p class="mh-custom-posts-small-title">
						<a href="postdata.php?id=<?php echo $articlevalue->id ;?>">
						<?php
						if(strlen($articlevalue->title) > 60) $articlevalue->title = substr($articlevalue->title, 0, 60).'...';
						echo stripslashes($articlevalue->title);
						?>
						</a>
					</p>
					<div class="mh-meta mh-custom-posts-meta">
						<span class="mh-meta-date updated"><i class="fa fa-clock-o"></i><?php echo date('F j, Y', strtotime($articlevalue->modified_date)); ?></span>
						
					</div>
					<p class="category"><?php echo $articlevalue->category_label_1 ?></p>
                                        
				</header>
			</li>
            <?php } ?>
			
		</ul>
	</div>
	<?php
}

function most_viewed($mostviewd) {
	if( empty($mostviewd) ) return;
	?>
	<div class="mh-widget">
		<h4 class="mh-widget-title"><a href="#">MOST VIEWED</a></h4>
		<ul class="mh-custom-posts-widget clearfix">
		
		<?php foreach ($mostviewd as $key => $mostviewdvalue) { ?>
			<li class="mh-custom-posts-item mh-custom-posts-small clearfix">
				<div class="mh-custom-posts-thumb">
					<a href="postdata.php?id=<?php echo $mostviewdvalue->id ;?>" title="Indulge yourself">
						<img width="80" height="80" src="<?php echo url($mostviewdvalue->featured_image_path)?>" alt="" />
					</a>
				</div>
				<header class="mh-custom-posts-header">
					<p class="mh-custom-posts-small-title">
						<a href="postdata.php?id=<?php echo $mostviewdvalue->id ;?>">
						<?php
						if(strlen($mostviewdvalue->title) > 60) $mostviewdvalue->title = substr($mostviewdvalue->title, 0, 60).'...'; 
						echo stripslashes($mostviewdvalue->title);
						?>
						</a>
					</p>
					<div class="mh-meta mh-custom-posts-meta">
						<span class="mh-meta-date updated"><i class="fa fa-clock-o"></i><?php echo date('F j, Y', strtotime($mostviewdvalue->modified_date)); ?></span>
						
					</div>
					<p class="category">
					<?php
					$cat_label_1_arr = ( $mostviewdvalue->sugst_post_cat_name != "" ) ? explode( "&#10095;&#10095;", $mostviewdvalue->sugst_post_cat_name ) : array();
					$cat_label_1 = ! empty( $cat_label_1_arr ) ? $cat_label_1_arr[0] : "";
					echo $cat_label_1;
					?>
					</p>
										
				</header>
			</li>
		<?php } ?>
			
		</ul>
	</div>
	<?php
}
function get_menu( $array, $currentParent = 0, $currLevel = 0, $prevLevel = -1, $catid = 0, $pagename = "#", $menu_html = "" ) {
	$parent_id = 0;
	foreach ($array as $category) {
		$parent_id = $category->parent_id;
		
		if ( $currentParent == $parent_id ) {
			if ( $parent_id == 0 ) {
				$menu_html .= "<ul><li>";
				if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
				$currLevel++;
				$menu_html = get_menu($array, $category->id, $currLevel, $prevLevel, $catid, $pagename, $menu_html);
				$currLevel--;
			} else {
				if ($currLevel > $prevLevel) $menu_html .= "\n <ul> ";
				if ($currLevel == $prevLevel) $menu_html .= "\n </li> ";
				
				$menu_html .= "\n".'<li> <a href="'.$pagename.'?catid='.$category->id.'">'.htmlspecialchars($category->name)."</a>\n";
				
				if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
				$currLevel++;
				$menu_html = get_menu($array, $category->id, $currLevel, $prevLevel, $catid, $pagename, $menu_html);
				$currLevel--;
			}			
		}
	}
	
	if ($currLevel == $prevLevel) $menu_html .= "</li></ul>";
	return $menu_html;
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

function create_tree_view($array, $currentParent = 0, $currLevel = 0, $prevLevel = -1, $cat_id = 0, $select_all = 0) {
$mainDao = new MainDao();
              
              
	foreach ($array as $category) {

		if ($currentParent == $category->parent_id) {
			if ($currLevel > $prevLevel) echo " <ol class='tree'>";
			if ($currLevel == $prevLevel) echo " </li> ";
			
			$color = ( $category->id == $cat_id ) ? ' color:#ee0000;' : "";
			
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
                            
                                $result = $mainDao->db->prepare("select count(id) as childNode FROM master_library_categories WHERE parent_id=".$category->id);
                                $result->execute();
                                $rowFetchData = $result->fetchAll($mainDao->fetch_obj);
//                                echo $rowFetchData[0]->childNode;
//                               
				if( $category->parent_id != 0 ) {
                                   if($rowFetchData[0]->childNode == 0 ){
                                            $val = 'background:#fff;display:inline;padding-left:10px !important;';
//                                            $val='';
                                            $v2 = '<i class="fa fa-dot-circle-o primary"></i>';
                                        }
                                        else
                                        {
                                            $val="";
                                            $v2='';
                                        }
					echo '<li> '.$v2.'<label  for="category-'.$category->id.'" style= '.$color.$val.' title="'.htmlspecialchars($category->name).'">'.htmlspecialchars($category->name)." </label>\n";
					
                                        echo '<input  name="category-'.$category->id.'" type="checkbox" id="'.$category->id.'" value="'.$category->name.'" />';
                                        
				} else {
                                    
					//echo '<li> <span style="display:inline-block;padding-left:12px;">'.$category->name."</span>\n";
					echo '<li> <label onclick="location.href=\''.basename($_SERVER['PHP_SELF']).'\';">'.htmlspecialchars($category->name)."</label>\n";
					echo '<input name="category-'.$category->id.'" type="checkbox" id="'.$category->id.'" value="'.htmlspecialchars($category->name).'" />';
				}
			}
			
			
			if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
			$currLevel++;
			create_tree_view ($array, $category->id, $currLevel, $prevLevel, $cat_id, $select_all);
			$currLevel--;
		}
	}
	if ($currLevel == $prevLevel) echo " </li></span></ol> ";
}

function myaccount_left_menu() {
?>
	<ul class="left-menu">
		<li><a href="create_post.php">Create Post</a></li>
		<li><a href="add_presentation.php">Create Presentation</a></li>
		<li><a href="submittedpost.php">Submitted Posts</a></li>
		<li><a href="submittedpresentation.php">Submitted Presentation</a></li>
		<li><a href="publishedposts.php">Published Posts</a></li>
		<li><a href="publishedpresentation.php">Published Presentation</a></li>
	</ul>
<?php 
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

function upload($data = array()) {
	$input_name 	= isset( $data["input_name"] ) ? $data["input_name"] : "input_name";
	$upload_type 	= isset( $data["file_type"] ) ? strtolower($data["file_type"]) : "";
	$upload_path 	= isset( $data["upload_path"] ) ? $data["upload_path"] : UPLOADS_PATH;
	$upload_path 	= ( $upload_path == "" ) ? UPLOADS_PATH : $upload_path;
	$index_no 		= isset( $data["index_no"] ) ? $data["index_no"] : "single";
	$upload 		= array( "upload_path" => "", "file_type" => "", "name" => "", "error" => "" );
	
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
		$file_tmp_path 	= $_FILES[ $input_name ]["tmp_name"][$index_no];
                
		$file_size 		= $_FILES[ $input_name ]["size"][$index_no];
		$file_type 		= $_FILES[ $input_name ]["type"][$index_no];
	} else {
		$file_name 		= $_FILES[ $input_name ]["name"];
		$file_tmp_path 	= $_FILES[ $input_name ]["tmp_name"];
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
		$valid_extns = array("jpg", "jpeg", "png", "gif","bmp");
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
	} elseif( $upload_type == "" ) {
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
		$moved = @move_uploaded_file( $file_tmp_path, $upload_file_path );
		
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

function getPageHtml($dao = array(), $link) {
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
	return ( $text == "" ) ? url( "pages/master/".$page_link ) : "<a href=\"".url("pages/master/".$page_link)."\" ".$attr.">".stripslashes($text)."</a>";
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

function url($rest = "") {
	return sprintf( 
		"%s://%s%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', 
		$_SERVER['SERVER_NAME'], 
		"/".FOLDERNAME."/" 
	).$rest;
}

function current_url() {
	return sprintf(
		"%s://%s%s",
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		$_SERVER['SERVER_NAME'],
		$_SERVER['REQUEST_URI']
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

function show_html( $html = '' ) {
	die( htmlspecialchars($html) );
}

function show( $var = array() ) {
	echo "<pre>";
	print_r( $var );
	echo "</pre>";
	die;
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
    
