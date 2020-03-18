<?php
class PresentationController extends MainController {
	
	public function __construct() {
		$this->bean = load_bean("PresentationMasterBean");
		$this->dao 	= load_dao("PresentationMasterDAO");
		
		parent::__construct();
	}
	
	public function presentations() {
		$catid 			= $this->bean->get_request('catid');
		$page_type 		= $this->bean->get_request("page_type");
		$page_type 		= ($page_type == "") ? "presentations" : $page_type;
		
		$post_dao 		= load_dao("PostMasterDAO");
		$recent_post 	= $post_dao->recent_modified_post();
		$mostviewd 		= $post_dao->getmostviewd();
		$url_suffix 	= "page_type=".$page_type."&catid=".$catid;
		
		
		$limit 			= PAGE_LIMIT;
		$this->dao->pagging->page_type = $page_type;
		$presentations 	= $this->dao->get_presentation_by_category_id($catid, $limit);
		$paggin_html = getPageHtml($this->dao, $url_suffix);
		
		$all_categories = $this->beanUi->get_view_data("all_categories");
		$presentation_categories = isset( $all_categories["presentation_categories"] ) ? $all_categories["presentation_categories"] : array();
		$path = "";
		if( ! empty( $presentation_categories ) && $catid ) {
			foreach( $presentation_categories as $row ) {
				if( $row->id == $catid ) {
					$path = $row->path;
					break;
				}
			}
		}
		
		$breadcumb = ( $path != "" ) ? str_replace('/', " <span class=\"fa fa-caret-right\"></span> ", $path) : '';
		$this->beanUi->set_view_data("breadcumb", $breadcumb);
		$this->beanUi->set_view_data("catid", $catid);
		
		$this->beanUi->set_view_data("page", $this->bean->get_request("page"));
		$this->beanUi->set_view_data("paggin_html", $paggin_html);

		
		$this->beanUi->set_view_data("mostviewd", $mostviewd);
		$this->beanUi->set_view_data("recent_post", $recent_post);
		$this->beanUi->set_view_data("presentations", $presentations);
	}

	public function presentationdetails() {
		$id = $this->bean->get_request('id');
		$subid = $this->bean->get_request('subid');
		$post_dao = load_dao("PostMasterDAO");
		$recent_post = $post_dao->recent_modified_post();
		$mostviewd = $post_dao->getmostviewd();
		$presentation = $this->dao->getpresentation($id,$subid );
		$this->beanUi->set_view_data("mostviewd", $mostviewd);
		$this->beanUi->set_view_data("recent_post", $recent_post);
		$this->beanUi->set_view_data("presentation", $presentation);
	}
	
	public function add_presentation() {
		$_action = $this->bean->get_request("_action");
		if( $_action == "Add" ) {
			$this->set_session( "data", $this->bean->get_request("data") );
			$data = $this->bean->get_request("data");
			
			$data["created_by"] = $this->get_auth_user("id");
			$data["created_date"] = date("c");
			$data["last_status_date"] = date("c");
			$data["modified_by"] = $this->get_auth_user("id");
			$data["modified_date"] = date("c");
			$presentation_upload_path = UPLOADS_PATH."/presentations";
			
			
			$featured_img = explode("?", str_replace(url(), "", $_POST['featured_image_path']) );
			$data["featured_image_path"]=$featured_img[0];
			// Featured image
			/*if( isset( $_FILES["featured_image"] ) ) {
				$filedata = array( "input_name" => "featured_image", "upload_path" => $presentation_upload_path );
				if( is_file($_FILES["featured_image"]["tmp_name"]) ) {
					list($width, $height, $type, $attr) = getimagesize($_FILES["featured_image"]["tmp_name"]);
					
					if( $width != $height || $width < 250 ) {
						$this->beanUi->set_error("featured_image", "Featured image's width and height should be same and width should be greater than 250 pixel. ");
						redirect();
					}
				}
				$featured_image = upload($filedata);
				if( $featured_image["error"] == "" && $featured_image["upload_path"] != "" ) {
					$data["featured_image_path"] = $featured_image["upload_path"];
				}
			}*/
			// End
			
			// Upload Pdf file
			if( isset( $_FILES["file_path"] ) ) {
				$filedata = array( "input_name" => "file_path", "upload_path" => $presentation_upload_path );
				if( is_file($_FILES["file_path"]["tmp_name"]) ) {
					$file_type = strtolower($_FILES["file_path"]["type"]);
					if( $file_type != "application/pdf" ) {
						$this->beanUi->set_error("file_path", "Please upload a .pdf file. ");
						redirect();
					}
				}
				$featured_image = upload($filedata);
				if( $featured_image["error"] == "" && $featured_image["upload_path"] != "" ) {
					$data["file_path"] = $featured_image["upload_path"];
				}
			}
			// End
			
			// Validate presentation
			$this->bean->set_data($data);
			$valid_presentation = $this->bean->presentation_validation();
			if( ! $valid_presentation ) {
				// Delete new uploaded files
				if( isset( $data["featured_image_path"] ) ) if( $data["featured_image_path"] != "" ) @unlink( BASE_PATH . "/" . $data["featured_image_path"] );
				if( isset( $data["file_path"] ) ) if( $data["file_path"] != "" ) @unlink( BASE_PATH . "/" . $data["file_path"] );
				// End
				$this->set_session( "errors", $this->bean->get_error_messages() );
				redirect();
			}
			// End
			
			// Save 
			$presentation_id = $this->dao->save($data);
			if( ! $presentation_id ) {
				
				$this->beanUi->set_error_message($this->dao->get_query_error());
				redirect();
			}
			// End
			
			//Enter new tag
			$alltags = $this->dao->select( "SELECT tag_name FROM master_tags" );
			$post_tags_str = $data["tag_keys"];
			$post_tag_arr = ($post_tags_str != "") ? explode( ",", $post_tags_str ) : array();
			if( count($post_tag_arr) > 0 ) {
				$all_tags = array();
				if( ! empty($alltags) ) foreach( $alltags as $tagrow ) $all_tags[] = $tagrow->tag_name;
				
				$this->dao->_table = "master_tags";
				foreach( $post_tag_arr as $tag ) {
					if( ! in_array($tag, $all_tags) ) {
						$this->dao->save( array( "tag_name" => $tag ) );
						//if( $this->dao->get_query_error() != "" ) die($this->dao->get_query_error());
					}
				}
			}
			//End tag
			
			
			$this->beanUi->set_success_message("Presentation is successfully created.");
			redirect("edit_presentation.php?id=".$presentation_id);
		}
		
		$categories = $this->dao->get_categories();
		$this->beanUi->set_view_data("categories", $categories );
		$this->beanUi->set_view_data("master_status", $this->get_status_options("post_status") );
		$this->beanUi->set_array_to_view_data( $this->get_session("data") );
	}
	
	public function edit_presentation() {
		$_action = $this->bean->get_request("_action");
		if( $_action == "Update" ) {
			
			$data = $this->bean->get_request("data");
			$data["modified_by"] = $this->get_auth_user("id");
			$data["modified_date"] = date("c");
			
			$presentation_upload_path = UPLOADS_PATH."/presentations";
			
			// Featured image
            
			if($_POST['featured_image_path']!='') {
				$oldimg=explode("?",end(explode("/",$_POST['featured_image_path_old'])));
				$image1=$oldimg[0];
			  
				$old_original_img=str_replace("-avatar","",$oldimg[0]);
				@unlink(UPLOADS_PATH."/presentations/".$image1);
				@unlink(UPLOADS_PATH."/presentations/".$old_original_img);
				
				$featured_img = explode("?", str_replace(url(), "", $_POST['featured_image_path']) );
				$data["featured_image_path"] = $featured_img[0];
			}
            
		/*	$featured_image_path = "";
			$old_featured_image = "";
			if( isset( $_FILES["featured_image"] ) ) {
				$filedata = array( "input_name" => "featured_image", "upload_path" => $presentation_upload_path );
				if( is_file($_FILES["featured_image"]["tmp_name"]) ) {
					list($width, $height, $type, $attr) = getimagesize($_FILES["featured_image"]["tmp_name"]);
					
					if( $width != $height || $width < 250 ) {
						$this->beanUi->set_error("featured_image", "Featured image's width and height should be same and width should be greater than 250 pixel. ");
						redirect();
					}
				}
				
				$upload_info = upload($filedata);
				if( $upload_info["error"] == "" && $upload_info["upload_path"] != "" ) {
					$featured_image_path 			= $upload_info["upload_path"];
					$data["featured_image_path"] 	= $featured_image_path;
					
					if( @is_file(BASE_PATH . "/" . $featured_image_path) ) {
						$rows = $this->dao->select( "SELECT featured_image_path FROM ".$this->dao->_table." WHERE id = ".$data["id"] );
						$old_featured_image = isset( $rows[0]->featured_image_path ) ? $rows[0]->featured_image_path : "";
					}
				}
			}*/
			// End
			
			// Upload Pdf file
			$file_path 		= "";
			$old_file_path 	= "";
			if( isset( $_FILES["file_path"] ) ) {
				$filedata = array( "input_name" => "file_path", "upload_path" => $presentation_upload_path );
				if( is_file($_FILES["file_path"]["tmp_name"]) ) {
					$file_type = strtolower($_FILES["file_path"]["type"]);
					if( $file_type != "application/pdf" ) {
						$this->beanUi->set_error("file_path", "Please upload a .pdf file. ");
						redirect();
					}
				}
				$upload_info = upload($filedata);
				if( $upload_info["error"] == "" && $upload_info["upload_path"] != "" ) {
					$file_path 			= $upload_info["upload_path"];
					$data["file_path"] 	= $file_path;
					
					if( @is_file(BASE_PATH . "/" . $file_path) ) {
						$rows = $this->dao->select( "SELECT file_path FROM ".$this->dao->_table." WHERE id = ".$data["id"] );
						$old_file_path = isset( $rows[0]->file_path ) ? $rows[0]->file_path : "";
					}
				}
			}
			// End
			
			// Validate presentation
			$this->bean->set_data($data);
			$valid_presentation = $this->bean->presentation_validation();
			if( ! $valid_presentation ) {
				if( $featured_image_path != "" ) @unlink( BASE_PATH . "/" . $featured_image_path );
				if( $file_path != "" ) @unlink( BASE_PATH . "/" . $file_path );
				
				$this->set_session( "errors", $this->bean->get_error_messages() );
				redirect();
			}
			// End
			
			
			// Update last status
			$statusrow = $this->dao->select( "SELECT status_id FROM ".$this->dao->_table." WHERE id = ".$data["id"] );
			$status_id = $statusrow[0]->status_id;
			if( $data["status_id"] != $status_id ) $data["last_status_date"] = date("c");
			// End
			
			// Save 
			$presentation_id = $this->dao->save($data);
			if( ! $presentation_id ) {
				$this->beanUi->set_error_message($this->dao->get_query_error());
				redirect();
			}
			// End
			
			// Delete old file if new one is uploaded.
			if( $old_featured_image != "" ) @unlink( BASE_PATH . "/" . $old_featured_image );
			if( $old_file_path != "" ) @unlink( BASE_PATH . "/" . $old_file_path );
			// End
			
			//Enter new tag
			$alltags = $this->dao->select( "SELECT tag_name FROM master_tags" );
			$post_tags_str = $data["tag_keys"];
			$post_tag_arr = ($post_tags_str != "") ? explode( ",", $post_tags_str ) : array();
			if( count($post_tag_arr) > 0 ) {
				$all_tags = array();
				if( ! empty($alltags) ) foreach( $alltags as $tagrow ) $all_tags[] = $tagrow->tag_name;
				
				$this->dao->_table = "master_tags";
				foreach( $post_tag_arr as $tag ) {
					if( ! in_array($tag, $all_tags) ) {
						$this->dao->save( array( "tag_name" => $tag ) );
						//if( $this->dao->get_query_error() != "" ) die($this->dao->get_query_error());
					}
				}
			}
			//End tag
			
			
			$this->beanUi->set_success_message("Presentation is successfully updated.");
			redirect();
		}
		
		$id = $this->bean->get_request("id");
		if( ! $id ) {
			$this->beanUi->set_error_message("Could not load the page, id is missing.");
			redirect("./");
		}
		$row = $this->dao->get_presentation($id);
		
		$categories = $this->dao->get_categories();
		$this->beanUi->set_view_data("categories", $categories );
		$this->beanUi->set_view_data("master_status", $this->get_status_options("post_status") );
		$this->beanUi->set_array_to_view_data( $row );
	}
	
	
	public function delete_file() {
		$id 	= $this->bean->get_request("id");
		$file 	= $this->bean->get_request("file");
		if( ! $id || $file == "" ) {
			$message = "Id is missing.";
			if( $file == "" ) {
				$message .= " and file parameter is missing.";
				$this->beanUi->set_error_message( $message );
				redirect(page_link("edit_presentation.php?id=".$id));
			}
		}

		$row = $this->dao->select("SELECT ".$file." FROM ".$this->dao->_table." WHERE id = ".$id);
		if( count( $row ) > 0 ) {
			$file_path = BASE_PATH."/".$row[0]->$file;
			
              if($file='featured_image_path') 
              {
                $oldimg=explode("?",end(explode("/",$row[0]->$file)));
                $image1=$oldimg[0];
                $old_original_img=str_replace("-avatar","",$oldimg[0]);
                @unlink(UPLOADS_PATH."/presentations/".$image1);
                @unlink(UPLOADS_PATH."/presentations/".$old_original_img);
            }
            else
            {
                @unlink($file_path);
            }
       
			if( $this->dao->save( array( $file => "", "id" => $id) ) ) $this->beanUi->set_success_message( "File is deleted." );
		}
		redirect(page_link("edit_presentation.php?id=".$id));
	}
	
	public function delete_presentation() {
		
		$id = $this->bean->get_request("id");
		$page = $this->bean->get_request("page");
		if( ! $id ) {
			$this->beanUi->set_error_message( "Id is missing." );
			redirect(page_link("myaccount.php?page=".$page));
		}
		$row = $this->dao->select("SELECT file_path, featured_image_path FROM ".$this->dao->_table." WHERE id = ".$id);
		$featured_image_path 	= !empty( $row ) ? $row[0]->featured_image_path : "";
		$file_path 				= !empty( $row ) ? $row[0]->file_path : "";
		
		$image_abs_path = BASE_PATH."/".$featured_image_path;
		$pdf_abs_path = BASE_PATH."/".$file_path;
		@unlink($image_abs_path);
		@unlink($pdf_abs_path);
		
		if( $this->dao->del(array( "id" => $id )) ) $this->beanUi->set_success_message( "Presentation is deleted." );
		
		redirect(page_link("myaccount.php?page=".$page));
	}
}
