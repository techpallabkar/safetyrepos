<?php

class NodalController extends MainController {

    public function __construct() {
        $this->dao = load_dao("NodalDAO");
        parent::__construct();
    } 

    protected function index() {
        
        $questionNodalData = $this->dao->getQuestionNodalData();//show($questionNodalData);
        $this->beanUi->set_view_data("questionNodalData", $questionNodalData);
    }
    

    public function add() {
       
        $_action    = $this->bean->get_request("_action");
        $rowID      = ($this->bean->get_request("editid")) ? $this->bean->get_request("editid") : "";
        $questionNodalData = array();
        if( $rowID != "" ) {
            $clause = " id=".$rowID;
            $questionNodalData = $this->dao->getQuestionNodalData($clause);
        }
        if ($_action == "editNodalMapping") {
            $user_id = $this->bean->get_request("user_id");
            $rwID = $this->bean->get_request("id");
            
            $this->dao->_table = "score_division_nodal_mapping";
            if( $rwID != "" ) {
                $data["id"] = $rwID; 
                $data["user_id"] = $user_id ? $user_id :""; 
                $data["modified_by"] = $this->get_auth_user("id");
                $data["modified_date"] = date("c");
                $updateID = $this->dao->save($data);
                if ($updateID) {
                $this->beanUi->set_success_message("Nodal Details Successfully Updated.");
                redirect("index.php");
                }
            } 
//            else {  
//                $data["created_by"] = $this->get_auth_user("id");
//                $data["created_date"] = date("c");
//                $insertID = $this->dao->save($data);
//                if ($insertID) {
//                $this->beanUi->set_success_message("Successfully Added.");
//                redirect("index.php");
//                }
//            }
        }
        $questionNodalRowData = !empty($questionNodalData) ? $questionNodalData[0] : array();
        $this->beanUi->set_view_data("nodalOfficerDetails", $this->dao->getNodalOfficer());
        $this->beanUi->set_view_data("rowID", $rowID);
        $this->beanUi->set_view_data("questionNodalRowData", $questionNodalRowData);
        $this->beanUi->set_view_data("nodalOfficerDetails", $nodalOfficerDetails);
    }
    
}
