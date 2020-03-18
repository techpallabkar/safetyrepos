<?php
class PostMasterBean extends MasterBean {
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->setViewData( 'categories', $this->dao->getCategories('POST') );
		$this->setViewData( 'allstatus', array_flip($this->getEOptions('POST_STATUS')) );
		$this->setViewData( 'post_status', $this->getRequestValue('post_status') );
		$this->setViewData( 'srchtitle', $this->getRequestValue('srchtitle') );
		
		$pagging 			= $this->postsPagging();
		$posts 				= isset( $pagging['posts'] ) ? $pagging['posts'] : array();
		$postcatname 		= isset( $pagging['postcatname'] ) ? $pagging['postcatname'] : array();
		$paggin_html 		= isset( $pagging['paggin_html'] ) ? $pagging['paggin_html'] : '';
		
		$this->setViewData( 'postcatrows', $this->getRequestValue('catids') );
		$this->setViewData( 'posts', $posts );
		$this->setViewData( 'postcatname', $postcatname );
		$this->setViewData( 'paggin_html', $paggin_html );
		
	}
	
	public function postsPagging() {
		$limit 		= 30;
		$page 		= isset($_GET['page']) ? $_GET['page'] : 1;
		$start 		= ( $page > 1 ) ? ($limit * $page) - $limit : 0;
		
		$catids 		= $this->getRequestValue('catids');
		$srchtitle 		= $this->getRequestValue('srchtitle');
		$post_status 	= $this->getRequestValue('post_status');
		
		$condition = '';
		if( !empty($catids) ) {
			$condition['catids'] = $catids;
		}
		if( is_numeric($post_status) ) {
			$condition['post_status'] = $post_status;
		}
		if( $srchtitle != "" ) {
			$condition['title'] = $srchtitle;
		}
		
		$daoData 	= $this->dao->getPaggingPosts($start, $limit, 'posts_view', $condition);
		if( empty( $daoData ) ) return '';
		
		$paggin_html = $this->getPageHtml();
		
		return array(
			'posts' 		=> $daoData['posts'],
			'postcatname' 	=> $daoData['postcatname'], 
			'paggin_html' 	=> $paggin_html
		);
	}
	
	public function delete_post() {
		$ids = $this->getRequestValue('ids');
		if( $ids == "" ) {
			$this->setMessages( 'message', '<div class="error">Posts id not found.</div>' );
			$this->redirect();
		}
		//$postids = explode(',', $ids);
		$image_paths = $this->dao->getImagePaths($ids);
		
		$delete = $this->dao->delete($ids);
		$this->setMessages( 'message', '<div class="success">Posts are successfully deleted.</div>' );
		if( !$delete ) {
			$this->setMessages( 'message', '<div class="error">Posts are not deleted.</div>' );
		} else {
			
			if( !empty( $image_paths ) ) {
				foreach( $image_paths as $ipath ) {
					@unlink( UPLOADSPATH . '/' . $ipath->image_path );
					@unlink( UPLOADSPATH . '/' . $ipath->filepath );
					
					$this->dao->_table = 'post_categories';
					$this->dao->del( array('post_id' => $ipath->id ) );
					
					$this->dao->_table = 'uploads';
					$this->dao->del( array('id' => $ipath->upload_id ) );
				}
			}
		}
		
		$this->redirect('./');
	}
	
	public function publish() {
		$ids = $this->getRequestValue('ids');
		if( $ids == "" ) {
			$this->setMessages( 'message', '<div class="error">Posts id not found.</div>' );
			$this->redirect('./');
		}
		$active = $this->dao->updateStatus(1, $ids);
		$this->setMessages( 'message', '<div class="success">Posts are successfully published.</div>' );
		if( !$active ) {
			$this->setMessages( 'message', '<div class="error">Posts are not published.</div>' );
		}
		$this->redirect('./');
	}
	
	public function unpublish() {
		$ids = $this->getRequestValue('ids');
		if( $ids == "" ) {
			$this->setMessages( 'message', '<div class="error">Posts id not found.</div>' );
			$this->redirect('./');
		}
		
		$inactive = $this->dao->updateStatus(0, $ids);
		$this->setMessages( 'message', '<div class="success">Posts are successfully unpublished.</div>' );
		if( !$inactive ) {
			$this->setMessages( 'message', '<div class="error">Posts are not unpublished.</div>' );
		}
		$this->redirect('./');
	}
	
	public function add() {
		
		$data = array();
		$post_tags = array();
		if( $this->action == 'add' ) {
			
			$data = $this->getRequestValue('data');
			$catids = $this->getRequestValue('catids');
			$catids = empty( $catids ) ? array(1) : $catids;
			$tagnames = $this->getRequestValue('tagnames');
			
			$data['image_path'] 		= '';
			$this->file['file_type'] 	= 'image';
			$this->file['inputname'] 	= 'image_path';
			$this->file['uploadDir'] 	= UPLOADSPATH.'/posts/'.date('Y');
			$this->file['maxFileSize'] 	= 2;
			$this->uploadFile();
			
			$error = 0;
			if( $this->file['error_message'] != "" ) {
				$this->setMessages( 'message', '<div class="error">'.$this->file['error_message'].'</div>' );
				$error++;
			}
			if( !$error ) {
				if( file_exists( $this->file['uploaded_file_path'] ) && $this->file['save_path'] != "" ) {
					$data['image_path'] = $this->file['save_path'];
				}
				$data['created_by'] 	= $this->auth_user->id;
				$data['created_date'] 	= date('c');
				$this->setMessages( 'message', '<div class="error">Post has not been created.</div>' );
				
				if( $data['image_path'] == '' ) unset($data['image_path']);
				if( $this->dao->save($data) ) $this->setMessages( 'message', '<div class="success">Post has been successfully created.</div>' );
				
				$post_id = $this->dao->lastInsertedId;
				if( !empty($tagnames) ) $this->dao->saveTags($tagnames, $post_id);
				
				if( !empty( $catids ) ) {
					
					$this->dao->_table = 'post_categories';
					$catdata = array();
					foreach( $catids as $cid ) {
						$catdata = array( 'post_id' => $post_id, 'category_id' => $cid);
						$this->dao->save($catdata);
					}
					$this->setMessages( 'message', '<div class="success">Post has been successfully created.</div>' );
				}
				if( $post_id > 0 ) {
					$this->redirect('edit.php?id='.$post_id);
				}
			}
			
			$post_tags = array();
			if( !empty($tagnames) ) {
				foreach( $tagnames as $tag ) {
					$post_tags[] = (object) array( 'name' => $tag );
				}
			}
		}
		
		$this->loadLibraries(array('wysiwyg_editor', "Cropimage"));
		$jCrop = $this->libs["cropimage"];
		
		$jcrop_html = $jCrop->jcropform(array("cropto" => "assets/uploads/posts/2016/resized_pic-1452696792.jpg"));
		
		$this->setViewData( 'editor', $this->libs['wysiwyg_editor'] );
		$this->setViewData( 'categories', $this->dao->getCategories('POST') );
		$this->setViewData( 'extrajs', $this->libs['wysiwyg_editor']->getFullEditor('description') );
		$this->setViewData( 'data', $data );
		$this->setViewData( 'jcrop_html', $jcrop_html );
		$this->setViewData( 'post_tags', $post_tags );
	}
	
	public function edit() {
		$id 	= $this->getRequestValue('id');
		
		if( $this->action == 'edit' ) {
			$data 	= $this->getRequestValue('data');
			$catids = $this->getRequestValue('catids');
			$catids = empty( $catids ) ? array(1) : $catids;
			$old_image = $this->getRequestValue('old_image');
			$tagnames = $this->getRequestValue('tagnames');
			
			$data['image_path'] = '';
			$this->file['file_type'] = 'image';
			$this->file['inputname'] = 'image_path';
			$this->file['uploadDir'] = UPLOADSPATH.'/posts/'.date('Y');
			$this->file['maxFileSize'] 	= 1;
			$this->uploadFile();
			if( $this->file['error_message'] != "" ) {
				$this->setMessages( 'message', '<div class="error">'.$this->file['error_message'].'</div>' );
				$this->redirect();
			}
			$uploadedfile = 0;
			if( file_exists( $this->file['uploaded_file_path'] ) ) {
				$data['image_path'] = str_replace( UPLOADSPATH.'/', '', $this->file['uploaded_file_path'] );
				$uploadedfile++;
			}
			$data['created_by'] 	= $this->auth_user->id;
			$data['created_date'] 	= date('c');
			$this->setMessages( 'message', '<div class="error">Post has not been updated.</div>' );
			if( $data['image_path'] == '' ) unset($data['image_path']);
						
			if( $this->dao->save($data) ) {
				if( !empty($tagnames) ) $this->dao->saveTags($tagnames, $data['id']);
				
				$this->setMessages( 'message', '<div class="success">Post has been successfully updated.</div>' );
				if( $uploadedfile && file_exists(UPLOADSPATH.'/'. $old_image) ) @unlink(UPLOADSPATH.'/'. $old_image);
			}
			if( !empty( $catids ) ) {
				$this->dao->_table = 'post_categories';
				$this->dao->del(array( 'post_id' => $id ));

				$catdata = array();
				foreach( $catids as $catid ) {
					$catdata = array( 'post_id' => $id, 'category_id' => $catid);
					$this->dao->save($catdata);
				}
				$this->setMessages( 'message', '<div class="success">Post has been successfully updated.</div>' );
			}
			$this->redirect();
		}
		$rows 	= $this->dao->select( array( 'id' => $id ) );
		$row 	= $rows[0];
		
		$thmb_rows 			= array();
		$this->dao->_table 	= 'uploads';
		$this->data['thmb_rows'] 	= $this->dao->select( array( 'table_name' => 'posts', 'table_id' => $row->id ) );
		$this->data['row'] 			= $row;
		
		$this->loadLibraries(array('cropimage', 'wysiwyg_editor'));
		
		$editor 					= $this->libs['wysiwyg_editor'];
		$crpImg 					= $this->libs['cropimage'];
		$crpImg->bean 				= $this;
		$crpImg->crop_js_path 		= $this->url('assets/js/cropjs/');
		$crpImg->image_url 			= $this->url('assets/uploads/'.$row->image_path);
		$crpImg->thumbnail_form_url = $this->link('posts/?action=savethumbnail&id='.$id);
		
		$extrajs = $crpImg->getJavaScript();
		if( $extrajs != "" ) $this->setViewData( 'extrajs', $extrajs );
		$extrajs = "";
		
		$this->setExtraCss($editor->getCssLinks());
		$this->setExtraJs($editor->getJsLinks());
		$extrajs = $editor->getFullEditor('description');
		if( $extrajs != "" ) $this->setViewData( 'extrajs', $extrajs );
		
		$this->setViewData( 'thumbnailHtml', $crpImg->getThumbnailHtml() );
		$this->setViewData( 'row', $row );
		$this->setViewData( 'categories', $this->dao->getCategories('POST') );
		$this->setViewData( 'postcatrows', $this->dao->getPostCategories( 'post_id = '.$id ) );
		$this->setViewData( 'post_tags', $this->dao->getTagNames('', 'posts', '', $id) );
	}
	
	public function savethumbnail() {
		
		$image_path = $this->getRequestValue('image_path');
		$id 		= $this->getRequestValue('id');
		$filepath	= $this->getRequestValue('filepath');
		$uploadids 	= $this->getRequestValue('uploadids');
		
		$imgpath = '';
		$this->dao->_table = 'uploads';
		if( !empty($filepath) ) {
			foreach( $filepath as $key => $link ) {
				$imgpath = UPLOADSPATH . '/' . $link;
				if( file_exists($imgpath) ) @unlink($imgpath);
				
				$upload_id = isset( $uploadids[$key] ) ? $uploadids[$key] : 0;
				if( $upload_id > 0 ) $this->dao->del( array( 'id' => $upload_id ) );
			}
			$this->redirect( 'edit.php?id='.$id );
		}
		
		$this->loadLibraries('cropimage');
		$crpImg = $this->libs['cropimage'];
		
		list($name, $ext) = get_filename_and_ext($image_path);
		
		$crpImg->large_image_path 	= UPLOADSPATH.'/posts/'.date('Y').'/'.$image_path;
		$crpImg->thumb_image_path 	= UPLOADSPATH.'/posts/'.date('Y').'/thubmnail/'.$name.'-'.strtotime(date('c')).'.'.$ext;
		$cropped = $crpImg->saveThumbImage();
		if( file_exists($cropped) ) {
			$thumb_image = str_replace( UPLOADSPATH.'/', '', $cropped );
			$data = array(
				'table_name' 	=> 'posts', 
				'table_id' 		=> $id, 
				'filepath' 		=> $thumb_image, 
				'name' 			=> $name, 
				'alt_text' 		=> $name, 
				'filetype' 		=> 'THUMB', 
				'created_date' 	=> date('c')
			);
			$this->dao->save($data);
		}
		$this->redirect( 'edit.php?id='.$id );
	}
	
	/*public function getTags() {
		$tagno 		= $this->getRequestValue('tagno');
		$tagname 	= $this->getRequestValue('tagname');
		$tagtable 	= $this->getRequestValue('tagtable');
		$oldtagnames = $this->getRequestValue('oldtagnames');
		$tags 		= '';
		$namestr = '';
		if( !empty($oldtagnames) ) {
			foreach($oldtagnames as $oldname) {
				if($oldname == '') continue;
				$namestr .= "'$oldname',";
			}
			$namestr = trim($namestr, ',');
		}
		if( $tagname != "" && $tagtable != "" ) {
			$alltags = $this->dao->getTagNames($tagname, $tagtable, $namestr);
			if( !empty($alltags) ) {
				foreach( $alltags as $tagrow ) {
					$tagno++;
					$tags .= '<div class="display_box"><a class="addname" title="'.$tagrow->name.'" id="'.$tagno.'" style="cursor:pointer;">'.$tagrow->name.'</a></div>'."\n";
				}
			}
		}
		$data['tags'] = $tags;
		$data['tagno'] = $tagno;
		$this->jsonEncodedOutput($data);
	}*/
}

