<?php
class DashboardController extends MainController {
	
	public function __construct() {
             $this->dao 	= load_dao("DashboardDAO");
		parent::__construct();
	}
	 
	public function dashboard() {
		$this->beanUi->set_view_data( "welcome_text", "Welcome to Dashboard" );
                $clause = "";
		$auth_user_id = $this->get_auth_user("id");		
		$is_nodal_officer = $this->get_auth_user("is_nodal_officer");
                
		$limit 	= 30;
                $clause .= " activity_name!='' ORDER BY orderID ASC";
		$allposts = $this->dao->get_activity_type_master( $clause, $limit, $this->get_auth_user() );
                $this->beanUi->set_view_data("allposts", $allposts);
                $this->beanUi->set_view_data("auth_user_id", $auth_user_id);
                $this->beanUi->set_view_data("is_nodal_officer", $is_nodal_officer);
	}
        
        public function cmsDashboard() {
            $this->beanUi->set_view_data( "welcome_text", "Welcome to CMS Dashboard" );
            $clause = "";
		$auth_user_id = $this->get_auth_user("id");		
		
		$limit 	= 30;
                $clause .= " activity_name!='' ORDER BY id ASC";
		$allposts = $this->dao->get_activity_type_master( $clause, $limit, $this->get_auth_user() );
                $this->beanUi->set_view_data("allposts", $allposts);
            
        }
        
        public function wrongentry() {
            $this->beanUi->set_view_data( "welcome_text", "Exception" );
            $clause .= " activity_name!='' ORDER BY id ASC";
            $allposts = $this->dao->get_activity_type_master( $clause, $limit, $this->get_auth_user() );
            $newArr = array();
            foreach( $allposts as $rowdata ) {
                $newArr[$rowdata->id][tree] = $this->dao->get_all_wrong_enrty($rowdata->id);
                $newArr[$rowdata->id]["dates"] = $this->dao->get_all_wrong_entry_dates($rowdata->id);
            }
            $this->beanUi->set_view_data("allposts", $allposts);
            $this->beanUi->set_view_data("treedata", $newArr);
            
        }
        
        public function tree_set() {
            $this->beanUi->set_view_data( "welcome_text", "Tree Set" );
            $action = $this->bean->get_request("action");
            if( $action == "Search" ) {
            $condition = $this->bean->get_request("condition");           
            $condArray = array(
                1 => 'P-SET,C-SET',
                2 => 'C-SET,P-SET',
                3 => 'P-SET,PC-SET',
                4 => 'C-SET,PC-SET',
                5 => 'Call Center,C-SET',
                6 => 'Call Center,PC-SET',
                7 => 'anima,PC-SET',
                8 => 'anima,P-SET',
                9 => 'anima,C-SET'
            );
           
            if( array_key_exists( $condition, $condArray ) ) {
                $task = $condArray[$condition];
            }
            $clause .= " activity_name!='' ORDER BY id ASC";
            $allposts = $this->dao->get_activity_type_master( $clause, $limit, $this->get_auth_user() );
            $newArr = array();
                foreach( $allposts as $rowdata ) {
                    $newArr[$rowdata->id] = $this->dao->get_all_tree_set($rowdata->id,$task,$condition);
                }
            }
            $this->beanUi->set_view_data("allposts", $allposts);
            $this->beanUi->set_view_data("treeset_data", $newArr);
            $this->beanUi->set_view_data("condition", $condition);
        }
        
        
}
