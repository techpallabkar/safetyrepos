<?php

class CmsController extends MainController {

    public function __construct() {
        $this->dao = load_dao("CmsMasterDAO");
        parent::__construct();
    }
 
    protected function index() {
        $search_title   = $this->bean->get_request("search_title");
        $status_id      = $this->bean->get_request("status_id");
        $cat_id         = $this->bean->get_request("cat_id");
    }

    public function importantinformation() {
        $importantinformation   = $this->dao->getimportantinformation();
        $editid                 = $this->bean->get_request("editid");
        $_action                = $this->bean->get_request("_action");
        $singleData = array();
        if ($editid != "") {
            $clause = " id=:id";
            $singleData = $this->dao->getimportantinformation($clause,array("id"=>$editid));
        }
        if ($_action == "updateData") {
            $data = $this->bean->get_request("data");
            $this->dao->_table = "important_information";
            $updateID = $this->dao->save($data);
            if ($updateID) {
                $this->beanUi->set_success_message("Content Updated.");
                redirect("importantinformation.php");
            }
        }
        $this->beanUi->set_view_data("singleData", $singleData);
        $this->beanUi->set_view_data("importantinformation", $importantinformation);
    }

    public function managecms() {
        $cmsData    = $this->dao->getCMSData();
        $editid     = $this->bean->get_request("editid");
        $_action    = $this->bean->get_request("_action");
        $singleData = array();
        if ($editid != "") {
            $clause = " id=:id";
            $singleData = $this->dao->getCMSData($clause,array("id"=>$editid));
        }
        if ($_action == "updateData") {
            $data = $this->bean->get_request("data");
            $this->dao->_table = "cms";
            $updateID = $this->dao->save($data);
            if ($updateID) {
                $this->beanUi->set_success_message("Content Updated.");
                redirect("managecms.php");
            }
        }
        $this->beanUi->set_view_data("singleData", $singleData);
        $this->beanUi->set_view_data("cmsData", $cmsData);
    }

    public function manageMessages() {
        $messageData    = $this->dao->getMessagesData();
        $statusid       = $this->bean->get_request("statusid");
        if ($statusid != "") {
            $getstatusid = $this->dao->getstatusData($statusid);
            redirect("manageMessages.php");
        }
        $deltid = $this->bean->get_request("deltid");
        if ($deltid != "") {
            $this->dao->_table = "messages";
            $isdeleted = $this->dao->del(array("id" => $deltid));
            if ($isdeleted) {
                $this->beanUi->set_success_message("Successfully Deleted.");
                redirect("manageMessages.php");
            }
        }
        $this->beanUi->set_view_data("statusid", $statusid);
        $this->beanUi->set_view_data("messageData", $messageData);
    }

    public function addManageMessages() {
        $messageData = $this->dao->getMessagesData();
        $_action = $this->bean->get_request("_action");
        if ($_action == "updateMessageData") {
            $data = $this->bean->get_request("data");
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
                    if ($input_name == "image_path") {

                        $filedata = array(
                            "input_name" => $input_name,
                            "file_type" => "image",
                            "upload_path" => UPLOADS_PATH . "/profile_image",
                            "index_no" => @$index_no
                        );
                        $upload_info = upload($filedata);
                        $upload_info["name"] = isset($image_captions[@$index_no]) ? $image_captions[@$index_no] : $upload_info["name"];
                        $upload_info["type_id"] = 0;
                        $uploads[$input_name] = $upload_info;
                    } else if ($input_name == "file_path") {

                        $filedata = array(
                            "input_name" => $input_name,
                            "file_type" => "file",
                            "upload_path" => UPLOADS_PATH . "/profile_image",
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

            $this->dao->_table = "messages";
            $updateID = $this->dao->save($data);
            if ($updateID) {
                $this->beanUi->set_success_message("Successfully Updated.");
                redirect("manageMessages.php");
            }
        }
        $this->beanUi->set_view_data("messageData", $messageData);
    }

    public function editManageMessages() {
        $editid = $this->bean->get_request("editid");
        $getdata = $this->dao->getData($editid);
        $_action = $this->bean->get_request("_action");
        if ($_action == "updateMessageData") {
            $data = $this->bean->get_request("data");
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
                    if ($input_name == "image_path") {

                        $filedata = array(
                            "input_name" => $input_name,
                            "file_type" => "image",
                            "upload_path" => UPLOADS_PATH . "/profile_image",
                            "index_no" => @$index_no
                        );
                        $upload_info = upload($filedata);
                        $upload_info["name"] = isset($image_captions[@$index_no]) ? $image_captions[@$index_no] : $upload_info["name"];
                        $upload_info["type_id"] = 0;
                        $uploads[$input_name] = $upload_info;
                    } else if ($input_name == "file_path") {

                        $filedata = array(
                            "input_name" => $input_name,
                            "file_type" => "file",
                            "upload_path" => UPLOADS_PATH . "/profile_image",
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
            $this->dao->_table = "messages";
            $updateID = $this->dao->save($data);
            if ($updateID) {
                $this->beanUi->set_success_message("Successfully Updated.");
                redirect("manageMessages.php");
            }
        }

        $this->beanUi->set_view_data("getdata", $getdata);
        $this->beanUi->set_view_data("editid", $editid);
    }

    public function purge_file() {
        $id = $this->bean->get_request("id");
        $file = $this->bean->get_request("file");
        if (!$id || $file == "") {
            $message = "Id is missing.";
            if ($file == "") {
                $message .= " and file parameter is missing.";
                $this->beanUi->set_error_message($message);
                redirect(page_link("cms/editManageMessages.php?editid=" . $id));
            }
        }
        $this->dao->_table = "messages";
        $row = $this->dao->select("SELECT " . $file . " FROM " . $this->dao->_table . " WHERE id = " . $id);
        if (count($row) > 0) {
            $file_path = CESC_BASE_PATH . "/" . $row[0]->$file;
            @unlink($file_path);
            if ($this->dao->save(array($file => "", "id" => $id)))
                $this->beanUi->set_success_message("File is deleted.");
        }
        redirect(page_link("cms/editManageMessages.php?editid=" . $id));
    }

}
