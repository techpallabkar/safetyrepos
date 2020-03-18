<?php

class EventController extends MainController {

    public function __construct() {
        $this->dao = load_dao("EventMasterDAO");
        parent::__construct();
    }
 
    protected function index() {
        $search_title = $this->bean->get_request("search_title");
        $status_id = $this->bean->get_request("status_id");
        $cat_id = $this->bean->get_request("cat_id");
        $eventData = $this->dao->getEventsData();
        $deltid = $this->bean->get_request("deltid");
        if($deltid!="")
        {
        $getdeltid = $this->dao->deltData($deltid);
        $this->beanUi->set_success_message("Successfully Deleted.");
                redirect("index.php");
        }
        
        
        $this->beanUi->set_view_data("eventData", $eventData);
        
        
    }
    

    public function add() {
        $eventcategory = $this->dao->getEventsCategory();
        $_action = $this->bean->get_request("_action");
        if ($_action == "addEventData") {
            $data = $this->bean->get_request("data");
            $data["created_by"] = $this->get_auth_user("id");
            $data["created_date"] = date("c");
            $this->dao->_table = "upcoming_event";
            $updateID = $this->dao->save($data);
            if ($updateID) {
                $this->beanUi->set_success_message("Successfully Updated.");
                redirect("index.php");
            }
        }
        $this->beanUi->set_view_data("eventcategory", $eventcategory);
    }

    public function editEvent() {
        $eventcategory = $this->dao->getEventsCategory();
        $editid = $this->bean->get_request("editid");
        $getdata = $this->dao->getEventsEditData($editid);
        $_action = $this->bean->get_request("_action");
        if ($_action == "updateEventData") {
            $data = $this->bean->get_request("data");
            $this->dao->_table = "upcoming_event";
            $updateID = $this->dao->save($data);
            if ($updateID) {
                $this->beanUi->set_success_message("Successfully Updated.");
                redirect("index.php");
            }
        }
        $this->beanUi->set_view_data("getdata", $getdata);
        $this->beanUi->set_view_data("eventcategory", $eventcategory);
    }

}
