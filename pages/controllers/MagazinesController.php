<?php
class MagazinesController extends MainController {
	
	public function __construct() {
		$this->bean = load_bean("MagazinesMasterBean");
		$this->dao 	= load_dao("MagazinesMasterDAO");
		
		parent::__construct();
	}
	
	public function magazines() {
		$page_type 		= $this->bean->get_request("page_type");
		$catid 			= $this->bean->get_request('catid');
		$page_type 		= ($page_type == "") ? "magazines" : $page_type;
		$url_suffix 	= "page_type=".$page_type."&catid=".$catid;
		
		$post_dao 		= load_dao("PostMasterDAO");
		$recent_post 	= $post_dao->recent_modified_post();
		$mostviewd 		= $post_dao->getmostviewd();
		
		$limit 			= PAGE_LIMIT;
		$this->dao->pagging->page_type = $page_type;
		$magazines 		= $this->dao->get_magazines_by_category_id($catid, $limit);
		$paggin_html 	= getPageHtml($this->dao, $url_suffix);
		
		$all_categories = $this->beanUi->get_view_data("all_categories");
		$magazine_categories = isset( $all_categories["magazine_categories"] ) ? $all_categories["magazine_categories"] : array();
		$path = "";
		if( ! empty( $magazine_categories ) && $catid ) {
			foreach( $magazine_categories as $row ) {
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
		$this->beanUi->set_view_data("magazines", $magazines);
	}

	public function magazinesdetails() {
		$id = $this->bean->get_request('id');
		$subid = $this->bean->get_request('subid');
		$post_dao = load_dao("PostMasterDAO");
		$recent_post = $post_dao->recent_modified_post();
		$mostviewd = $post_dao->getmostviewd();
		$magazines = $this->dao->getmagazines($id,$subid );
		$this->beanUi->set_view_data("mostviewd", $mostviewd);
		$this->beanUi->set_view_data("recent_post", $recent_post);
		$this->beanUi->set_view_data("magazines", $magazines);
	}

}
