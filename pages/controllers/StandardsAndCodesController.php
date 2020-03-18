<?php
class StandardsAndCodesController extends MainController {
	
	public function __construct() {
		$this->bean = load_bean("StandardsAndCodesMasterBean");
		$this->dao 	= load_dao("StandardsAndCodesMasterDAO");
		
		parent::__construct();
	}
	
	public function standards_and_codes() {
		$keyword 		= $this->bean->get_request("keyword");
		$status_id 		= $this->bean->get_request("status_id");
		$cat_id 		= $this->bean->get_request("cat_id");
		$page_type 		= $this->bean->get_request("page_type");
		$page_type 		= ($page_type == "") ? "standards_and_codes" : $page_type;
		$url_suffix 	= "page_type=".$page_type."&cat_id=".$cat_id."&status_id=".$status_id."&keyword=".$keyword;
		
		
		$clause = "status_id != 6";
		if( $keyword != "" ) $clause .= " AND title LIKE '%".$keyword."%'";
		if( $status_id > 0 ) $clause .= " AND status_id = ".$status_id;
		if( $cat_id > 0 ) $clause .= " AND category_id = ".$cat_id;
		$clause .= " ORDER BY modified_date";
		$limit 	= PAGE_LIMIT;
		$paggin_html = "";
		$rows = array();
		$this->dao->pagging->page_type = $page_type;
		$rows = $this->dao->get_standards_and_codes_with_pagging( $clause, $limit, $this->get_auth_user() );
		$paggin_html = getPageHtml($this->dao, $url_suffix);
		
		$categories = $this->dao->get_categories();
		$this->beanUi->set_view_data("cat_id", $cat_id );
		$this->beanUi->set_view_data("categories", $categories );
		$this->beanUi->set_view_data("page", $this->bean->get_request("page"));
		$this->beanUi->set_view_data("rows", $rows);
		$this->beanUi->set_view_data("paggin_html", $paggin_html);
		$this->beanUi->set_view_data("master_status", $this->get_status_options("post_status") );
		$this->beanUi->set_view_data("keyword", $this->bean->get_request("keyword") );
		$this->beanUi->set_view_data("status_id", $this->bean->get_request("status_id") );
		
	}
}
