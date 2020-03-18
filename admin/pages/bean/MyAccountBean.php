<?php
class MyAccountBean extends MasterBean {
	public function myaccount() { 
		$this->dao = $this->load_dao("PostMasterDAO");
		$limit = 5;
		$clause = "post_status_id = '".get_id_from_array("status_name", "Draft", $this->get_status_options("post_status"))."'";
		$draft_posts = $this->dao->get_posts_with_pagging($clause, $limit);
		
		$paggin_html = $this->getPageHtml();
		
		$this->setViewData("draft_posts", $draft_posts);
		$this->setViewData("paggin_html", $paggin_html);
	}
	
	public function create_post() {
		
	}
	
	public function edit_post() {
		
	}
}
