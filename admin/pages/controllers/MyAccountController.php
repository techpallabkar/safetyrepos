<?php
class MyAccountController extends MainController {
	public $beanUi = array();
	 
	public function myaccount() {
		$bean 	= load_bean("PostMasterBean");
		$dao 	= load_dao("PostMasterDAO");
		
		$status_options = $this->get_session("status_options");
		$post_status = isset($status_options["post_status"]) ? $status_options["post_status"] : array();
		$clause = "post_status_id = '".get_id_from_array("status_name", "Draft", $post_status)."'";
		
		$limit = 5;
		$draft_posts = $dao->get_posts_with_pagging($clause, $limit);
		$draft_posts_paggin_html = getPageHtml($dao);
		
		$this->beanUi = new BeanUi();
		
		$this->beanUi->set_view_data("draft_posts", $draft_posts);
		$this->beanUi->set_view_data("draft_posts_paggin_html", $draft_posts_paggin_html);
		$this->beanUi->set_view_data("draft_presentation", array());
		$this->beanUi->set_view_data("draft_presentation_paggin_html", "");
		
		//$this->setViewData("draft_posts", $draft_posts);
		//$this->setViewData("paggin_html", $paggin_html);
	}
}
