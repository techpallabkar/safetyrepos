<?php
class MyAccountController extends MainController {
	public $beanUi = array();
	
	public function myaccount() {
		$bean 		= load_bean("PostMasterBean");
		$post_dao 	= load_dao("PostMasterDAO");
		$status_options = $this->get_session("status_options");
		$page_type 		= $this->bean->get_request("page_type");
		$page 			= $this->bean->get_request("page");
		
		$post_status = isset($status_options["post_status"]) ? $status_options["post_status"] : array();
		$clause = "status_id = '".get_id_from_array("status_name", "Draft", $post_status)."' AND created_by = ".$this->get_auth_user("id");
		
		$limit = PAGE_LIMIT;
		$post_dao->pagging->page_type = $page_type;
		$draft_posts = $post_dao->get_posts_with_pagging($clause, $limit);
		$draft_posts_paggin_html = getPageHtml($post_dao, "page_type=posts");
		$post_rownumber = ( $page_type == "posts" ) ? (( $page * $limit ) - $limit) : 0;
		
		$this->beanUi->set_view_data("page", $this->bean->get_request("page"));
		$this->beanUi->set_view_data("draft_posts", $draft_posts);
		$this->beanUi->set_view_data("post_rownumber", $post_rownumber);
		$this->beanUi->set_view_data("draft_posts_paggin_html", $draft_posts_paggin_html);
		
		$presentation_dao 	= load_dao("PresentationMasterDAO");
		$clause = "status_id = 1 AND created_by = ".$this->get_auth_user("id");
		$limit = PAGE_LIMIT;
		
		$presentation_dao->pagging->page_type = $page_type;
		$draft_presentations = $presentation_dao->get_presentation_with_pagging($clause, $limit);
		$draft_presentation_paggin_html = getPageHtml($presentation_dao, "page_type=presentations");
		$presentation_rownumber = ( $page_type == "presentations" ) ? (( $page * $limit ) - $limit) : 0;
		
		$this->beanUi->set_view_data("draft_presentations", $draft_presentations);
		$this->beanUi->set_view_data("presentation_rownumber", $presentation_rownumber);
		$this->beanUi->set_view_data("draft_presentation_paggin_html", $draft_presentation_paggin_html);
	}
	
	public function submittedpost() {
		$bean 	= load_bean("PostMasterBean");
		$dao 	= load_dao("PostMasterDAO");
		$page_type 		= $this->bean->get_request("page_type");
		$status_options = $this->get_session("status_options");
		$post_status = isset($status_options["post_status"]) ? $status_options["post_status"] : array();
		$clause = "status_id = '".get_id_from_array("status_name", "Submitted", $post_status)."' AND created_by = ".$this->get_auth_user("id");
		$limit = PAGE_LIMIT;
		
		$dao->pagging->page_type = $page_type;
		$draft_posts = $dao->get_posts_with_pagging_submitted($clause, $limit);
		$draft_posts_paggin_html = getPageHtml($dao, "page_type=posts");
		
		@$page = $this->bean->get_request("page");
		@$page = $page > 0 ? $page : 1;
		$serial_num = ($page*$limit)+1-$limit;

		$this->beanUi->set_view_data("serial_num", $serial_num);
		$this->beanUi->set_view_data("draft_posts", $draft_posts);
		$this->beanUi->set_view_data("draft_posts_paggin_html", $draft_posts_paggin_html);
	}
	
	public function viewsubmittedpost(){
            
		$bean 	= load_bean("PostMasterBean");
		$dao 	= load_dao("PostMasterDAO");
		$status_options = $this->get_session("status_options");
		$id = $this->bean->get_request('id');
             
		$submited_posts = $dao->getpostsubmittedcompletedata($id);
		$this->beanUi->set_view_data("submited_posts", $submited_posts);
            
	}
	public function viewpublishedpost(){
            
		$bean 	= load_bean("PostMasterBean");
		$dao 	= load_dao("PostMasterDAO");
		$status_options = $this->get_session("status_options");
		$id = $this->bean->get_request('id');
             
		$published_posts = $dao->getpostpublisheddata($id);
		$this->beanUi->set_view_data("published_posts", $published_posts);
            
	}
        public function publishedposts() {
			$bean 	= load_bean("PostMasterBean");
			$dao 	= load_dao("PostMasterDAO");
			$status_options = $this->get_session("status_options");
			
			$post_status = isset($status_options["post_status"]) ? $status_options["post_status"] : array();
			$clause = "status_id = '".get_id_from_array("status_name", "Published", $post_status)."' AND created_by = ".$this->get_auth_user("id");
			$limit = PAGE_LIMIT;
			$draft_posts = $dao->get_posts_with_pagging_publist($clause, $limit);
			$draft_posts_paggin_html = getPageHtml($dao, "page_type=posts");
			
			$page = $this->bean->get_request("page");
			$page = $page > 0 ? $page : 1;
			$serial_num = ($page*$limit)+1-$limit;
		
			$this->beanUi->set_view_data("serial_num", $serial_num);
			$this->beanUi->set_view_data("draft_posts", $draft_posts);
			$this->beanUi->set_view_data("draft_posts_paggin_html", $draft_posts_paggin_html);
		
		}
		
        public function submittedpresentation() {
			$bean 	= load_bean("PostMasterBean");
			$dao 	= load_dao("PostMasterDAO");
			$status_options = $this->get_session("status_options");
			$post_status = isset($status_options["post_status"]) ? $status_options["post_status"] : array();
			$clause = "status_id = '".get_id_from_array("status_name", "Submitted", $post_status)."' AND created_by = ".$this->get_auth_user("id");
			$limit = PAGE_LIMIT;
			$submitted_presentation = $dao->get_submitpresentation_with_pagging($clause, $limit);
			$submitted_presentation_paggin_html = getPageHtml($dao, "page_type=presentations");
			$page = $this->bean->get_request("page");
			$page = $page > 0 ? $page : 1;
			$serial_num = ($page*$limit)+1-$limit;
		
			$this->beanUi->set_view_data("serial_num", $serial_num);
			$this->beanUi->set_view_data("submitted_presentation", $submitted_presentation);
			$this->beanUi->set_view_data("submitted_presentation_paggin_html", $submitted_presentation_paggin_html);
		}
		
        public function publishedpresentation() {
			$bean 	= load_bean("PostMasterBean");
			$dao 	= load_dao("PostMasterDAO");
			
			$status_options = $this->get_session("status_options");
			$post_status = isset($status_options["post_status"]) ? $status_options["post_status"] : array();
			$clause = "status_id = '".get_id_from_array("status_name", "Published", $post_status)."' AND created_by = ".$this->get_auth_user("id");
			$limit = PAGE_LIMIT;
			$published_presentation = $dao->get_submitpresentation_with_pagging($clause, $limit);
			$published_presentation_paggin_html = getPageHtml($dao, "page_type=presentations");
			$page = $this->bean->get_request("page");
			$page = $page > 0 ? $page : 1;
			$serial_num = ($page*$limit)+1-$limit;
		
			$this->beanUi->set_view_data("serial_num", $serial_num);
			$this->beanUi->set_view_data("limit", $limit);
			$this->beanUi->set_view_data("published_presentation", $published_presentation);
			$this->beanUi->set_view_data("published_presentation_paggin_html", $published_presentation_paggin_html);
		}
	
	public function postsubmitdata() {
		$category_label_1 = $this->dao->get_category_label_1();
		$id = $this->bean->get_request('id');
		die($id);
		$submit_add_post = $this->bean->get_request("submit_add_post");
		$data['submitQuery'] = $this->bean->get_request("submit_add_post");
	}
	
	public function delete_post() {
		$id 	= $this->bean->get_request("id");
		$page 	= $this->bean->get_request("page");
		if( $id ) {
			$bean 	= load_bean("PostMasterBean");
			$dao 	= load_dao("PostMasterDAO");
			
			$postrow = $dao->select( "SELECT featured_image_path FROM master_posts WHERE id = ".$id );
			$featured_image_path = isset( $postrow[0]->featured_image_path ) ? $postrow[0]->featured_image_path : "";
			if( is_file(BASE_PATH . "/" . $featured_image_path) ) @unlink(BASE_PATH . "/" . $featured_image_path);
			$uploadsrows = $dao->select( "SELECT file_path FROM post_uploads WHERE post_id = ".$id );
			if( count($uploadsrows) > 0 ) {
				foreach( $uploadsrows as $urow ) {
					if( is_file( BASE_PATH . "/" . $urow->file_path ) ) @unlink( BASE_PATH . "/" . $urow->file_path );
				}
			}
			$dao_table = "master_posts";
			$dao->del( array( "id" => $id ) );
			
			$this->beanUi->set_success_message( "Post has been successfully deleted." );
			redirect(page_link("myaccount.php?page=".$page));
		}
	}
}
