<?php 

class ForumController extends MainController {
	
	public function __construct() {
		$this->bean = load_bean("ForumMasterBean");
		$this->dao 	= load_dao("ForumMasterDAO");
		
		parent::__construct();
	}
	
	public function forum() {
	
	
	}
}
?>
