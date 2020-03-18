<?php
class IndexMasterBean extends MasterBean {
	public function home() { 
		
	}
	
	public function myaccount() { 
		$this->dao = $this->load_dao("PostMasterDAO");
		$draft_posts = $this->dao->get_draft_posts(5);
		$paggin_html = $this->getPageHtml();
		
		$this->setViewData("draft_posts", $draft_posts);
		$this->setViewData("paggin_html", $paggin_html);
	}
	
	public function createpost() { 
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
}
