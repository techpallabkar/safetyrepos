<?php
class PostController extends MainController {
	
	public function __construct() {
		$this->bean = load_bean("PostMasterBean");
		$this->dao 	= load_dao("PostMasterDAO");
		parent::__construct();
	}
	
	public function create_post() {
		$_action = $this->bean->get_request("_action");
		if( $_action == "Create" ) {
			$this->set_session( "data", $this->bean->get_request("data") );
			$data = $this->bean->get_request("data");
			
			$data["created_by"] 		= $this->get_auth_user("id");
			$data["created_date"] 		= date("c");
			$data["last_status_date"] 	= date("c");
			$data["modified_by"] 		= $this->get_auth_user("id");
			$data["modified_date"] 		= date("c");
			$img=explode("?",end(explode("/", $this->bean->get_request('featured_image_original') )));
			$data["featured_image_original"]=str_replace("-avatar","",$img[0]);
        
           $featured_img = explode("?", str_replace(url(), "", $this->bean->get_request('featured_image_path') ) );
           $data["featured_image_path"]=$featured_img[0];
			// Featured image
			/*$data["featured_image_path"] = "";
			if( isset( $_FILES["featured_image"] ) ) {
				$filedata = array( "input_name" => "featured_image", "upload_path" => UPLOADS_PATH."/posts" );
				if( is_file($_FILES["featured_image"]["tmp_name"]) ) {
					list($width, $height, $type, $attr) = getimagesize($_FILES["featured_image"]["tmp_name"]);
					
					if( $width != $height || $width < 250 ) {
						
						$this->beanUi->set_error_message("Featured image's width and height should be same and width should be greater than 250 pixel. ");
						redirect();
					}
				}
				$featured_image = upload($filedata);
				if( $featured_image["error"] == "" && $featured_image["upload_path"] != "" ) {
					$data["featured_image_path"] = $featured_image["upload_path"];
				}
			}*/
			// End
			
			// Validate post
			$this->bean->set_data($data);
			$valid_post = $this->bean->post_validation();
			
			if( ! $valid_post ) {
				// Delete new featured image
				if( $data["featured_image_path"] != "" ) @unlink( BASE_PATH . "/" . $data["featured_image_path"] );
				// End
				
				$this->set_session( "errors", $this->bean->get_error_messages() );
				redirect();
			}
			// End
			
			
			// Save post
			$post_id = $this->dao->save($data);
			if( ! $post_id ) {
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
			
			$uploads = array();
			$caption 	= $this->bean->get_request("caption");
			
			if( ! empty($_FILES) ) {
				foreach( $_FILES as $input_name => $files ) {
					if( is_array($files["name"]) ) {
						if( $files["name"][0] == "" ) continue;
					} elseif( ! is_array( $files["name"] ) ) {
						if( $files["name"] == "" ) continue;
					}

					if( $input_name == "image_path" ) {						
						foreach( $files["name"] as $index_no => $image_name ) {
							$filedata = array(
								"input_name" 	=> $input_name, 
								"file_type" 	=> "image", 
								"upload_path" 	=> UPLOADS_PATH."/posts",
								"index_no" 		=> $index_no
							);
							$upload_info = upload($filedata);
							$upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
							$uploads[] = $upload_info;
						}
					} elseif( $input_name == "file_path" ) {
						$filedata = array( "input_name" => $input_name, "upload_path" => UPLOADS_PATH."/posts" );
						$upload_info = upload($filedata);
						$upload_info["name"] = ( $caption != "" ) ? $caption : $upload_info["name"];
						$uploads[] = $upload_info;
						
					}
				}
			}
			
			$errors = "";
			if( ! empty( $uploads ) ) {
				
				$this->dao->_table = "post_uploads";
				foreach( $uploads as $uprow ) {
					$post_uploads = array();
					if( $uprow["error"] != "" ) {
						$errors .= $uprow["error"].",";
					} elseif( $uprow["upload_path"] != "" && $post_id ) {
						$post_uploads["post_id"] = $post_id;
						$post_uploads["file_path"] = $uprow["upload_path"];
						$post_uploads["file_type"] = $uprow["file_type"];
						$post_uploads["name"] = $uprow["name"];
						$post_uploads["created_date"] = date("c");
						$upload_update = $this->dao->save($post_uploads);
						if( ! $upload_update ) {
							$errors .= $this->dao->get_query_error().",";
						}
					}
				}
			}
			
			if( $errors != "" ) {
				$errors = trim($errors, ",");
				$this->beanUi->set_error_message($errors);
				redirect();
			}
			
			$this->beanUi->set_success_message("Post is successfully created.");
			redirect(page_link("edit_post.php?id=".$post_id));
		}
		$this->beanUi->set_array_to_view_data( $this->get_session("data") );
		$this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
		$this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
	}
	
	public function edit_post() {
		$_action = $this->bean->get_request("_action");
		if( $_action == "update" ) {
			$data = $this->bean->get_request("data");
			$data["modified_by"] = $this->get_auth_user("id");
			$data["modified_date"] = date("c");
			
			if( $this->bean->get_request('featured_image_original') !='' ) {
				$img=explode("?",end(explode("/", $this->bean->get_request('featured_image_original') )));
				$data["featured_image_original"]=str_replace("-avatar","",$img[0]);
            }
            
			if($this->bean->get_request('featured_image_path') != '') {
				$oldimg = explode("?",end(explode("/", $this->bean->get_request('featured_image_path_old') )));
				$image1 = $oldimg[0];
			  
				$old_original_img=str_replace("-avatar","",$oldimg[0]);
				@unlink(UPLOADS_PATH."/posts/".$image1);
				@unlink(UPLOADS_PATH."/posts/".$old_original_img);
			
				$featured_img = explode("?", str_replace(url(), "", $this->bean->get_request('featured_image_path') ) );
				$data["featured_image_path"] = $featured_img[0];
			}
            
			// Featured image
			/*$featured_image_path = "";
			$old_featured_image = "";
			if( isset( $_FILES["featured_image"] ) ) {
				$filedata = array( "input_name" => "featured_image", "upload_path" => UPLOADS_PATH."/posts" );
				if( is_file($_FILES["featured_image"]["tmp_name"]) ) {
					list($width, $height, $type, $attr) = getimagesize($_FILES["featured_image"]["tmp_name"]);
					if( $width != $height || $width < 250 ) {
						$this->beanUi->set_error_message("Featured image's width and height should be same and width should be greater than 250 pixel. ");
						redirect();
					}
				}
				$featured_image = upload($filedata);
				if( $featured_image["error"] == "" && $featured_image["upload_path"] != "" ) {
					$featured_image_path = $featured_image["upload_path"];
					$data[ "featured_image_path" ] = $featured_image_path;
					
					if( @is_file(BASE_PATH . "/" . $featured_image_path) ) {
						$rows = $this->dao->select( "SELECT featured_image_path FROM ".$this->dao->_table." WHERE id = ".$data["id"] );
						$old_featured_image = isset( $rows[0]->featured_image_path ) ? $rows[0]->featured_image_path : "";
					}
				}
			} */
			// End
			
			
			$this->bean->set_data($data);
			$valid_post = $this->bean->post_validation();
			if( ! $valid_post ) {
				// Delete new featured image
				if( $data["featured_image_path"] != "" ) @unlink( BASE_PATH . "/" . $data["featured_image_path"] );
				// End
				
				$this->set_session( "errors", $this->bean->get_error_messages() );
				redirect();
			}
			
			// Update last status
			$statusrow = $this->dao->select( "SELECT status_id FROM master_posts WHERE id = ".$data["id"] );
			$status_id = $statusrow[0]->status_id;
			if( $data["status_id"] != $status_id ) $data["last_status_date"] = date("c");
			// End
			
			// Save post
			$update_post = $this->dao->save($data);
			if( ! $update_post ) {
				$this->beanUi->set_error_message($this->dao->get_query_error());
				redirect();
			}
			// End
			
			// Delete old featured image
			if( $old_featured_image != "" ) @unlink( BASE_PATH . "/" . $old_featured_image );
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
			
			
			// Upload images and pdf
			$uploads = array();
			$caption 	= $this->bean->get_request("caption");
			$pdf_id 	= $this->bean->get_request("pdf_id");
			$image_captions = $this->bean->get_request("image_captions");
			if( ! empty($_FILES) ) {
				foreach( $_FILES as $input_name => $files ) {
					if( is_array($files["name"]) ) {
						if( $files["name"][0] == "" ) continue;
					} elseif( ! is_array( $files["name"] ) ) {
						if( $files["name"] == "" ) continue;
					}

					if( $input_name == "image_path" ) {						
						foreach( $files["name"] as $index_no => $image_name ) {
							$filedata = array(
								"input_name" 	=> $input_name, 
								"file_type" 	=> "image", 
								"upload_path" 	=> UPLOADS_PATH."/posts",
								"index_no" 		=> $index_no
							);
							
							$upload_info = upload($filedata);
							$upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
							$uploads[] = $upload_info;
						}
					} elseif( $input_name == "file_path" ) {
						$filedata = array( "input_name" => $input_name, "upload_path" => UPLOADS_PATH."/posts" );
						$upload_info = upload($filedata);
						$upload_info["name"] = ( $caption != "" ) ? $caption : $upload_info["name"];
						$uploads[] = $upload_info;
						
					}
				}
			}
			
			$errors = "";
			if( ! empty( $uploads ) ) {
				
				$this->dao->_table = "post_uploads";
				foreach( $uploads as $uprow ) {
					$post_uploads = array();
					if( $uprow["error"] != "" ) {
						$errors .= $uprow["error"].",";
					} elseif( $uprow["upload_path"] != "" ) {
						$post_uploads["post_id"] = $data["id"];
						$post_uploads["file_path"] = $uprow["upload_path"];
						$post_uploads["file_type"] = $uprow["file_type"];
						$post_uploads["name"] = $uprow["name"];
						$post_uploads["created_date"] = date("c");
						$upload_update = $this->dao->save($post_uploads);
						if( ! $upload_update ) {
							$errors .= $this->dao->get_query_error().",";
						}
					}
				}
			}
			
			if( $errors != "" ) {
				$errors = trim($errors, ",");
				$this->beanUi->set_error_message($errors);
				redirect();
			}
			
			// Save File Caption
			
			if( $caption != "" && $pdf_id ) {
				$captiondata["id"] = $pdf_id;
				$captiondata["name"] = $caption;
				$this->dao->_table = "post_uploads";
				$this->dao->save($captiondata);
			}
			
			$this->beanUi->set_success_message("Post is successfully updated.");
			redirect();
		}
		
		$id = $this->bean->get_request("id");
		if( ! $id ) redirect("myaccount.php");
		
		$posts = $this->dao->get_post($id);
		$post = ! empty( $posts ) ? $posts[0] : array();
		$post_uploads = $this->dao->get_post_uploads($post->id);
		
		$this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
		$this->beanUi->set_array_to_view_data( $post );
		$this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
		$this->beanUi->set_view_data("post_uploads", $post_uploads);
	}

	public function get_subcategories() {
		$parent_category_name 	= $this->bean->get_request("parent_category_name");
		$category_id 			= $this->bean->get_request("category_id");
		$catlavel 				= $this->bean->get_request("catlavel");
		$prev_cat_id 				= $this->bean->get_request("prev_cat_id");
                
		$clause = "";
		if( $parent_category_name == "" ) die();
		
		$categories = $this->dao->get_categories();
		$selected_l2 = get_value_by_id($category_id, "category_label_2", $categories);
		$selected_l3 = get_value_by_id($category_id, "category_label_3", $categories);
		
		
		$cathtml = "";
		if( $catlavel == 2 && $parent_category_name == "Article" ) {
			$clause = "category_label_1 = '".$parent_category_name."' AND is_active = 1";
			$categories = $this->dao->get_categories($clause);
			
			if( ! empty( $categories ) ) {
				$cathtml = '<select name="data[suggested_category_id]" id="post_category_id">'."\n";
				$cathtml .= '<option value="" selected> -- Select One -- </option>'."\n";
				foreach( $categories as $catrow ) {
					if( $category_id == $catrow->id && $prev_cat_id ) {
						$cathtml .= '<option value="'.$catrow->id.'" selected>'.$catrow->category_label_2.'</option>'."\n";
					} else {
						$cathtml .= '<option value="'.$catrow->id.'">'.$catrow->category_label_2.'</option>'."\n";
					}
				}
				$cathtml .= '</select>'."\n";
			}
			die($cathtml);
		} elseif( $catlavel == 2 && $parent_category_name != "Article" ) {
			$clause = "category_label_1 = '".$parent_category_name."' AND is_active = 1 GROUP BY category_label_2";
			$categories = $this->dao->get_categories($clause);
			if( ! empty( $categories ) ) {
				$cathtml = '<select onchange="get_subcategories(this.options[this.selectedIndex].text, this.value, 3)">'."\n";
				$cathtml .= '<option value="" selected> -- Select One -- </option>'."\n";
				foreach( $categories as $catrow ) {
					if( $selected_l2 == $catrow->category_label_2 && $prev_cat_id ) {
						$cathtml .= '<option value="'.$catrow->id.'" selected>'.$catrow->category_label_2.'</option>'."\n";
					} else {
						$cathtml .= '<option value="'.$catrow->id.'">'.$catrow->category_label_2.'</option>'."\n";
					}
				}
				$cathtml .= '</select>'."\n";
			}
			if( $selected_l2 != "" ) {
				$clause = "category_label_2 = '".$selected_l2."' AND is_active = 1";
				$categories = $this->dao->get_categories($clause);
				if( ! empty( $categories ) ) {
					$cathtml .= '|<select name="data[suggested_category_id]" id="post_category_id">'."\n";
					$cathtml .= '<option value="" selected> -- Select One -- </option>'."\n";
					foreach( $categories as $catrow ) {
						if( $selected_l3 == $catrow->category_label_3 && $prev_cat_id ) {
							$cathtml .= '<option value="'.$catrow->id.'" selected>'.$catrow->category_label_3.'</option>'."\n";
						} else {
							$cathtml .= '<option value="'.$catrow->id.'">'.$catrow->category_label_3.'</option>'."\n";
						}
					}
					$cathtml .= '</select>'."\n";
				}
			}
			die($cathtml);
			
		} elseif($catlavel == 3) {
			$clause = "category_label_2 = '".$parent_category_name."' AND is_active = 1";
			$categories = $this->dao->get_categories($clause);
			if( ! empty( $categories ) ) {
				$cathtml .= '<select name="data[suggested_category_id]" id="post_category_id">'."\n";
				$cathtml .= '<option value="" selected> -- Select One -- </option>'."\n";
				foreach( $categories as $catrow ) {
					
					if( $category_id == $catrow->id && $prev_cat_id ) {
						$cathtml .= '<option value="'.$catrow->id.'" selected>'.$catrow->category_label_3.'</option>'."\n";
					} else {
						$cathtml .= '<option value="'.$catrow->id.'">'.$catrow->category_label_3.'</option>'."\n";
					}
				}
				$cathtml .= '</select>'."\n";
			}
			die($cathtml);
		}
		
		//$this->json_encoded_output($categories);
	}

	public function delete_upload() {
		$id = $this->bean->get_request("id");
		$post_id = $this->bean->get_request("post_id");
		if( ! $id || ! $post_id ) {
			$this->beanUi->set_error_message( "Id is missing." );
			if( $post_id ) redirect(page_link("edit_post.php?id=".$post_id));
			else redirect(page_link("myaccount.php"));
		}
		
		$this->dao->_table = "post_uploads";
		$row = $this->dao->select("SELECT file_path FROM post_uploads WHERE id = ".$id);
		
		if( ! empty( $row ) ) {
			$file_path = BASE_PATH."/".$row[0]->file_path;
			@unlink($file_path);
			$this->dao->del( array( "id" => $id ) );
		}
		redirect(page_link("edit_post.php?id=".$post_id));
	}
	
	public function delete_featured_image() {
		$id = $this->bean->get_request("id");
		if( ! $id ) {
			$this->beanUi->set_error_message( "Id is missing." );
			redirect(page_link("posts/"));
		}
		$row = $this->dao->select("SELECT featured_image_path FROM master_posts WHERE id = ".$id);
		$featured_image_path = !empty( $row ) ? $row[0]->featured_image_path : "";
		$image_abs_path = BASE_PATH."/".$featured_image_path;
		//@unlink($image_abs_path);
        
         if($featured_image_path!='') {
            $oldimg=explode("?",end(explode("/",$featured_image_path)));
            $image1=$oldimg[0];
            $old_original_img=str_replace("-avatar","",$oldimg[0]);
             @unlink(UPLOADS_PATH."/posts/".$image1);
            @unlink(UPLOADS_PATH."/posts/".$old_original_img);
           }
        
		$data["id"] = $id;
		$data["featured_image_path"] = "";
        $data["featured_image_original"] = "";
		$data["modified_by"] = $this->get_auth_user("id");
		$data["modified_date"] = date("c");
		$this->dao->save($data);
		redirect(page_link("edit_post.php?id=".$id));
	}
	
	public function save_caption() {
		$upload_id = $this->bean->get_request("upload_id");
		$caption = $this->bean->get_request("caption");
		$saved = 0;
		if( $upload_id ) {
			$this->dao->_table = "post_uploads";
			if(
				$this->dao->save(
					array( "id" => $upload_id, "name" => $caption )
				)
			) $saved++;
		}
		die("$saved");
	}
}
