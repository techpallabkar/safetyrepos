<?php

class BulletinController extends MainController {

    public function __construct() {
        $this->dao = load_dao("BulletinDAO");
        parent::__construct();
    } 

    protected function index() {
        
        $bulletinData = $this->dao->getBulletinBoardData();
        $statusid = $this->bean->get_request("statusid");
        if( $statusid!="")
        {
            $this->dao->changeBulletinStatus($statusid);
            redirect("index.php");
        }
        
        $deltid = $this->bean->get_request("deltid");
        if($deltid!="")
        {
            $this->dao->_table = "bulletin_board";
            $getdeltid = $this->dao->deltData($deltid);
            $this->beanUi->set_success_message("Successfully Deleted.");
            redirect("index.php");
        }
        $this->beanUi->set_view_data("bulletinData", $bulletinData);
    }
    

    public function add() {
       
        $_action    = $this->bean->get_request("_action");
        $rowID      = ($this->bean->get_request("editid")) ? $this->bean->get_request("editid") : "";
        $bulletinData = array();
        if( $rowID != "" ) {
            $clause = " id=".$rowID;
            $bulletinData = $this->dao->getBulletinBoardData($clause);
        }
        if ($_action == "addBulletin") {
            $data = $this->bean->get_request("data");
            $data["created_by"] = $this->get_auth_user("id");
            $data["created_date"] = date("c");
            $rwID = $this->bean->get_request("id");
            
            $this->dao->_table = "bulletin_board";
            if( $rwID != "" ) {
                $data["id"] = $rwID;
                
                /*********/
                $uploads = array();
                if (!empty($_FILES)) { 
                    foreach ($_FILES as $input_name => $files) {
                        if (is_array($files["name"])) {
                            if ($files["name"][0] == "")
                                continue;
                        } elseif (!is_array($files["name"])) {
                            if ($files["name"] == "")
                                continue;
                        }
                        if ($input_name == "file_path") {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/bulletin",
                                "index_no" => @$index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[@$index_no]) ? $image_captions[@$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[$input_name] = $upload_info;
                        }
                    }
                }
                if (!empty($uploads)) {
                    $errors = "";
                    foreach ($uploads as $key => $uprow) {
                        $post_uploads = array();
                        if ($uprow["error"] != "") {
                            $errors .= $uprow["error"] . ",";
                        } elseif ($uprow["upload_path"] != "") {
                            $data[$key] = $uprow["upload_path"];
                        }
                    }
                }
                /**********/
                
                $updateID = $this->dao->save($data);
                if ($updateID) {
                $this->beanUi->set_success_message("Successfully Updated.");
                redirect("index.php");
                }
            } else {
                /*********/
                $uploads = array();
                if (!empty($_FILES)) { 
                    foreach ($_FILES as $input_name => $files) {
                        if (is_array($files["name"])) {
                            if ($files["name"][0] == "")
                                continue;
                        } elseif (!is_array($files["name"])) {
                            if ($files["name"] == "")
                                continue;
                        }
                        if ($input_name == "file_path") {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/bulletin",
                                "index_no" => @$index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[@$index_no]) ? $image_captions[@$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[$input_name] = $upload_info;
                        }
                    }
                }
                if (!empty($uploads)) {
                    $errors = "";
                    foreach ($uploads as $key => $uprow) {
                        $post_uploads = array();
                        if ($uprow["error"] != "") {
                            $errors .= $uprow["error"] . ",";
                        } elseif ($uprow["upload_path"] != "") {
                            $data[$key] = $uprow["upload_path"];
                        }
                    }
                }
                /**********/
                
                $insertID = $this->dao->save($data);
                if ($insertID) {
                $this->beanUi->set_success_message("Successfully Added.");
                redirect("index.php");
                }
            }
        }
        $bulletinRowData = !empty($bulletinData) ? $bulletinData[0] : array();
        $this->beanUi->set_view_data("rowID", $rowID);
        $this->beanUi->set_view_data("bulletinRowData", $bulletinRowData);
    }
    
    public function purge_file() {
		$id 	= $this->bean->get_request("id");
		$file 	= $this->bean->get_request("file");
		if( ! $id || $file == "" ) {
			$message = "Id is missing.";
			if( $file == "" ) {
				$message .= " and file parameter is missing.";
				$this->beanUi->set_error_message( $message );
				redirect(page_link("bulletin/add.php?editid=".$id));
			}
		}
                $this->dao->_table = "bulletin_board";
		$row = $this->dao->select("SELECT ".$file." FROM ".$this->dao->_table." WHERE id = ".$id);
		if( count( $row ) > 0 ) {
			$file_path = CESC_BASE_PATH."/".$row[0]->$file;
			@unlink($file_path);
			if( $this->dao->save( array( $file => "", "id" => $id) ) ) $this->beanUi->set_success_message( "File is deleted." );
		}
		redirect(page_link("bulletin/add.php?editid=".$id));
	}
	

}
