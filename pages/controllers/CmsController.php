<?php
class CmsController extends MainController {
	
	public function __construct() {
		$this->dao 	= load_dao("CmsMasterDAO");
		
		parent::__construct();
	}
        
        
        public function imp_information(){
        $importantinformation = $this->dao->getimportantinformation();
        $this->beanUi->set_view_data("importantinformation", $importantinformation );   
        }
        
        
     
	
}


