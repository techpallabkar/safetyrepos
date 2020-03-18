<?php

class LibraryController extends MainController {

    public function __construct() {
        $this->bean = load_bean("LibraryMasterBean");
        $this->dao = load_dao("LibraryMasterDAO");

        parent::__construct();
    }

    protected function indexOld() {
        $search_title = $this->bean->get_request("search_title");
        $status_id = $this->bean->get_request("status_id");
        $cat_id = $this->bean->get_request("cat_id");
        $passValue = array(6,0);
        $clause = "status_id != 6 AND category_deleted = 0";
        if ($search_title != ""){
            array_push($passValue,$search_title);
            $clause .= " AND title LIKE '%" . $search_title . "%'";
        }
        if ($status_id > 0){
            array_push($passValue,$status_id);
            $clause .= " AND status_id = " . $status_id;
        }
        if ($cat_id > 0){
            array_push($passValue,$cat_id);
            $clause .= " AND category_id = " . $cat_id;
        }
        $clause .= " ORDER BY modified_date DESC";
        $limit = 10;
        $paggin_html = "";
        $rows = array();
        $rows = $this->dao->get_library_with_pagging($clause, $limit, $this->get_auth_user());
        $paggin_html = get_page_html($this->dao);

        $categories = $this->dao->get_categories();

        $this->beanUi->set_view_data("cat_id", $cat_id);
        $this->beanUi->set_view_data("categories", $categories);
        $this->beanUi->set_view_data("page", $this->bean->get_request("page"));
        $this->beanUi->set_view_data("rows", $rows);
        $this->beanUi->set_view_data("paggin_html", $paggin_html);
        $this->beanUi->set_view_data("master_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("search_title", $this->bean->get_request("search_title"));
        $this->beanUi->set_view_data("status_id", $this->bean->get_request("status_id"));
    }
    
    /**
     * Modified By Srimanta
     */
    protected function index() {
        $search_title = $this->bean->get_request("search_title");
        $status_id = $this->bean->get_request("status_id");
        $cat_id = $this->bean->get_request("cat_id");
        $passValue = array(
                            "status_id" => 6,
                            "category_deleted" => 0
                            );
        $clause = "status_id != :status_id AND category_deleted = :category_deleted";
        if ($search_title != ""){
            $passValue["title"] = $search_title;
            $clause .= " AND title LIKE '%:title%'";
        }
        if ($status_id > 0){
            $passValue["status_ids"] = $status_id;
            $clause .= " AND status_id = :status_ids";
        }
        if ($cat_id > 0){
            $passValue["category_id"] = $cat_id;
            $clause .= " AND category_id = :category_id";
        }
        $clause .= " ORDER BY modified_date DESC";
        $limit = 10;
        $paggin_html = "";
        $rows = array();
        $rows = $this->dao->get_library_with_pagging($clause, $limit, $this->get_auth_user(),$passValue);
        $paggin_html = get_page_html($this->dao);

        $categories = $this->dao->get_categories();

        $this->beanUi->set_view_data("cat_id", $cat_id);
        $this->beanUi->set_view_data("categories", $categories);
        $this->beanUi->set_view_data("page", $this->bean->get_request("page"));
        $this->beanUi->set_view_data("rows", $rows);
        $this->beanUi->set_view_data("paggin_html", $paggin_html);
        $this->beanUi->set_view_data("master_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("search_title", $this->bean->get_request("search_title"));
        $this->beanUi->set_view_data("status_id", $this->bean->get_request("status_id"));
    }

    public function add_category() {

        $_action = $this->bean->get_request("_action");
        if ($_action == "add_category") {

            $data = $this->bean->get_request("data");
            $this->set_session("data", $data);

            $valid_standards_and_codes_category = $this->bean->library_category_validation();
            if (!$valid_standards_and_codes_category) {
                $this->set_session("errors", $this->bean->get_error_messages());
                redirect();
            }

            // Get parent path
            $catrows = $this->dao->select("SELECT `path` FROM `master_library_categories`", array("id" => $data["parent_id"]), 1);
            $path = count($catrows) ? $catrows->path : "";
            $path = ( $path == "" ) ? $data["name"] : $path . "/" . $data["name"];
            $data["path"] = $path;

            $this->dao->_table = "master_library_categories";
            $standards_and_codes_category_id = $this->dao->save($data);
            $this->beanUi->set_success_message("Library has been successfully added.");
            if (!$standards_and_codes_category_id) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            redirect(page_link("library/?cat_id=" . $data["parent_id"]));
        }


        $categories = $this->dao->get_categories();
        $this->beanUi->set_view_data("categories", $categories);
        $this->beanUi->set_view_data("parent_id", $this->bean->get_request("parent_id"));
    }

    public function edit_category() {

        $_action = $this->bean->get_request("_action");
        if ($_action == "edit_category") {
            $data = $this->bean->get_request("data");
            $this->set_session("data", $data);

            $valid_library_cat = $this->bean->library_category_validation();
            if (!$valid_library_cat) {
                $this->set_session("errors", $this->bean->get_error_messages());
                redirect();
            }

            // Get parent path
            $catrows = $this->dao->select("SELECT `path` FROM `master_library_categories`", array("id" => $data["parent_id"]), 1);
            $path = count($catrows) ? $catrows->path : "";
            $path = ( $path == "" ) ? $data["name"] : $path . "/" . $data["name"];
            $data["path"] = $path;
            $this->dao->_table = "master_library_categories";
            $old_path_row = $this->dao->select("SELECT `path` FROM `master_library_categories`", array("id" => $data["id"]), 1);
            $old_path = count($old_path_row) ? $old_path_row->path : "";
            if ($old_path != "") {
                $this->dao->change_all_subcategories_path($old_path, $path);
                if ($this->dao->get_query_error() != "")
                    die($this->dao->get_query_error());
            }
            $this->dao->save($data);
            $error_message = $this->dao->get_query_error();

            $this->beanUi->set_success_message("Library category has been successfully updated.");

            if ($error_message != "") {
                $this->beanUi->set_error_message($error_message);
            }

            redirect(page_link("library/"));
        }
        $id = $this->bean->get_request("id");
        if (!$id) {
            $this->beanUi->set_error_message("Could not load the page, id is missing.");
            redirect();
        }

        $row = $this->dao->select("SELECT * FROM `master_library_categories`", array("id" => $id), 1);

        $categories = $this->dao->get_categories();
        $this->beanUi->set_view_data("categories", $categories);
        $this->beanUi->set_array_to_view_data($row);
    }

    public function add() {

        ini_set('memory_limit', '512M');
        ini_set('upload_max_filesize', '500M');
        ini_set('post_max_size', '100M');
        ini_set('max_execution_time', 0);
        ini_set('max_input_time', 0);
        $_action = $this->bean->get_request("_action");
        if ($_action == "Add") {

            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");

            $data["created_by"] = $this->get_auth_user("id");
            $data["created_date"] = date("c");
            $data["last_status_date"] = date("c");
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");

            $library_upload_path = UPLOADS_PATH . "/library";
            // Upload Pdf file
            if (isset($_FILES["file_path"])) {

                $filedata = array("input_name" => "file_path", "upload_path" => $library_upload_path);
                if (is_file($_FILES["file_path"]["tmp_name"])) {
                    $file_type = strtolower($_FILES["file_path"]["type"]);
                }
                $upload_data = upload($filedata);

                if ($upload_data["error"] == "" && $upload_data["upload_path"] != "") {
                    $data["file_path"] = $upload_data["upload_path"];
                }
            }
            // End
            // Validate standards_and_codes
            $this->bean->set_data($data);
            $valid_library = $this->bean->library_validation();
            if (!$valid_library) {
                // Delete new uploaded files
                if (isset($data["file_path"]))
                    if ($data["file_path"] != "")
                        @unlink(BASE_PATH . "/" . $data["file_path"]);
                // End

                $this->set_session("errors", $this->bean->get_error_messages());
                redirect();
            }
            // End
            // Save 
            $library_id = $this->dao->save($data);
            if (!$library_id) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            // End
            //Enter new tag
            $alltags = $this->dao->select("SELECT tag_name FROM master_tags");
            $post_tags_str = $data["tag_keys"];
            $post_tag_arr = ($post_tags_str != "") ? explode(",", $post_tags_str) : array();
            if (count($post_tag_arr) > 0) {
                $all_tags = array();
                if (!empty($alltags))
                    foreach ($alltags as $tagrow)
                        $all_tags[] = $tagrow->tag_name;

                $this->dao->_table = "master_tags";
                foreach ($post_tag_arr as $tag) {
                    if (!in_array($tag, $all_tags)) {
                        $this->dao->save(array("tag_name" => $tag));
                    }
                }
            }
            //End tag

            $this->beanUi->set_success_message("Library content is successfully added.");
            redirect(page_link("library/?cat_id=" . $data["category_id"]));
        }

        $categories = $this->dao->get_categories();
        $this->beanUi->set_view_data("categories", $categories);
        $this->beanUi->set_view_data("master_status", $this->get_status_options("post_status"));
        $data = $this->get_session("data");
        $data = empty($data) ? array("category_id" => $this->bean->get_request("category_id")) : $data;
        $this->beanUi->set_array_to_view_data($data);
    }
    
    /**
     * Modified by Srimanta
     */
    public function edit() {
        $_action = $this->bean->get_request("_action");
        if ($_action == "Update") {
            ini_set('max_execution_time', 0);
            ini_set('max_input_nesting_level', 0);
            ini_set('max_input_nesting_level', 0);
            ini_set('max_input_time', 0);
            ini_set('memory_limit', '800M');
            ini_set('post_max_size', '800M');

            $data = $this->bean->get_request("data");
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");

            $library_upload_path = UPLOADS_PATH . "/library";
            // Upload Pdf file
            $file_path = "";
            $old_file_path = "";
            if (isset($_FILES["file_path"])) {
                $filedata = array("input_name" => "file_path", "upload_path" => $library_upload_path);
                if (is_file($_FILES["file_path"]["tmp_name"])) {
                    $file_type = strtolower($_FILES["file_path"]["type"]);
                }
                $upload_info = upload($filedata);
                if ($upload_info["error"] == "" && $upload_info["upload_path"] != "") {
                    $file_path = $upload_info["upload_path"];
                    $data["file_path"] = $file_path;

                    if (@is_file(BASE_PATH . "/" . $file_path)) {
                        $row = $this->dao->select("SELECT file_path FROM " . $this->dao->_table, array("id" => $data["id"]), 1);
                        $old_file_path = isset($row->file_path) ? $row->file_path : "";
                    }
                }
            }
            // End
            // Validate standards_and_codes
            $this->bean->set_data($data);
            $valid_library = $this->bean->library_validation();
            if (!$valid_library) {
                if ($file_path != "")
                    @unlink(BASE_PATH . "/" . $file_path);

                $this->set_session("errors", $this->bean->get_error_messages());
                redirect();
            }
            // End
            // Update last status
            $statusrow = $this->dao->select("SELECT status_id FROM " . $this->dao->_table . " WHERE id = " . $data["id"]);
            $status_id = $statusrow[0]->status_id;
            if ($data["status_id"] != $status_id)
                $data["last_status_date"] = date("c");
            // End
            // Save 
            $library_id = $this->dao->save($data);
            if (!$library_id) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            // End
            // Delete old file if new one is uploaded.
            if ($old_file_path != "")
                @unlink(BASE_PATH . "/" . $old_file_path);
            // End
            //Enter new tag
            $alltags = $this->dao->select("SELECT tag_name FROM master_tags");
            $post_tag_arr = ($post_tags_str != "") ? explode(",", $post_tags_str) : array();
            if (count($post_tag_arr) > 0) {
                $all_tags = array();
                if (!empty($alltags))
                    foreach ($alltags as $tagrow)
                        $all_tags[] = $tagrow->tag_name;

                $this->dao->_table = "master_tags";
                foreach ($post_tag_arr as $tag) {
                    if (!in_array($tag, $all_tags)) {
                        $this->dao->save(array("tag_name" => $tag));
                    }
                }
            }
            //End tag


            $this->beanUi->set_success_message("Library file is successfully updated.");
            redirect();
        }

        $id = $this->bean->get_request("id");
        if (!$id) {
            $this->beanUi->set_error_message("Could not load the page, id is missing.");
            redirect("./");
        }

        $row = $this->dao->get_library($id);
        $categories = $this->dao->get_categories();
        $this->beanUi->set_view_data("categories", $categories);
        $this->beanUi->set_view_data("master_status", $this->get_status_options("post_status"));
        $this->beanUi->set_array_to_view_data($row);
    }

    public function purge_file() {
        $id = $this->bean->get_request("id");
        $file = $this->bean->get_request("file");
        if (!$id || $file == "") {
            $message = "Id is missing.";
            if ($file == "") {
                $message .= " and file parameter is missing.";
                $this->beanUi->set_error_message($message);
                redirect(page_link("library/edit.php?id=" . $id));
            }
        }

        $row = $this->dao->select("SELECT " . $file . " FROM " . $this->dao->_table . " WHERE id = " . $id);
        if (count($row) > 0) {
            $file_path = CESC_BASE_PATH . "/" . $row[0]->$file;
            @unlink($file_path);
            if ($this->dao->save(array($file => "", "id" => $id)))
                $this->beanUi->set_success_message("File is deleted.");
        }
        redirect(page_link("library/edit.php?id=" . $id));
    }

    public function purge_library() {
        $id = $this->bean->get_request("id");
        $cat_id = $this->bean->get_request("cat_id");
        $page = $this->bean->get_request("page");
        if (!$id) {
            $this->beanUi->set_error_message("Id is missing.");
            redirect(page_link("library/"));
        }
        $row = $this->dao->select("SELECT file_path FROM " . $this->dao->_table, array("id" => $id), 1);
        $file_path = count($row) ? $row->file_path : "";

        $pdf_abs_path = CESC_BASE_PATH . "/" . $file_path;
        if (is_file($pdf_abs_path))
            @unlink($pdf_abs_path);

        if ($this->dao->del(array("id" => $id)))
            $this->beanUi->set_success_message("File is deleted.");
        redirect(page_link("library/?cat_id=" . $cat_id . "&page=" . $page));
    }

    public function purge_category() {
        $cat_id = $this->bean->get_request("id");
        $page = $this->bean->get_request("page");
        if (!$cat_id) {
            $this->beanUi->set_error_message("Id is missing.");
            redirect(page_link("library/?page=" . $page));
        }

        $row = $this->dao->select("SELECT `path`, `parent_id` FROM `master_library_categories`", array("id" => $cat_id), 1);
        if (count($row)) {

            $path = trim($row->path);
            if ($row->parent_id == 0) {
                $this->beanUi->set_error_message("Root category can not be deleted.");
                redirect(page_link("library/?cat_id=" . $cat_id . "&page=" . $page));
            }
            if ($path == "") {
                $this->beanUi->set_error_message("Category path not found.");
                redirect(page_link("library/?cat_id=" . $cat_id . "&page=" . $page));
            }

            if (!$this->dao->purge_category_by_path($path)) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
            } else {
                $this->beanUi->set_success_message("Category is successfully deleted.");
            }
            redirect(page_link("library/"));
        }
    }

}
