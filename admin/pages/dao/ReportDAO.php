<?php 

class ReportDAO extends MainDao { 

    public $_table = "activity";
    public $_view = "activity_view";
    public $_audit_view = "audit_view";
    public $_ppe_audit_view = "ppe_audit_view";
    public $_incident_view = "incident_view";
    public $_incident_category = "master_incident_category";
    public $_activity_type_master_mis = "activity_type_master_mis";
    public $_nature_of_injury = "master_nature_of_injury";
    public $_safety_observation_view = "safety_observation_view";
    public $_safety_observation_line_function_view = "safety_observation_line_function_view";

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Function : get_activity_with_pagging($param1,$param2,$param3)
     * @param type String
     * @param type Integer
     * @param type Array
     * @return type Array
     */
    public function get_activity_with_pagging($where_clause = 1, $limit = 5, $auth_user = array()) {
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_view . "` WHERE " . $where_clause;
        $row = $this->select($query, array(), 1);

        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query = "SELECT * FROM " . $this->_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            $allposts = $this->select($query);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    /**
     * Function : get_audit_with_pagging($param1,$param2,$param3)
     * Created by Anima Mahato
     * @param type String
     * @param type Integer
     * @param type Array
     * @return type Array
     */
    public function get_audit_with_pagging($where_clause = 1, $limit = 5, $auth_user = array()) {
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_audit_view . "` WHERE " . $where_clause;
        $row = $this->select($query, array(), 1);
        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query = "SELECT * FROM " . $this->_audit_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;

            $allposts = $this->select($query);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_ppe_audit_with_pagging($where_clause = 1, $limit = 5, $auth_user = array()) {
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_ppe_audit_view . "` WHERE " . $where_clause;
        $row = $this->select($query, array(), 1);
        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query = "SELECT * FROM " . $this->_ppe_audit_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;

            $allposts = $this->select($query);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_safety_observation_with_pagging($where_clause = 1, $limit = 5, $auth_user = array()) {
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_safety_observation_view . "` WHERE " . $where_clause;
        $row = $this->select($query, array(), 1);
        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query = "SELECT * FROM " . $this->_safety_observation_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            $allposts = $this->select($query);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_safety_observation_line_function_with_pagging($where_clause = 1, $limit = 5, $auth_user = array()) {
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_safety_observation_line_function_view . "` WHERE " . $where_clause;
        $row = $this->select($query, array(), 1);
        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query = "SELECT * FROM " . $this->_safety_observation_line_function_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            $allposts = $this->select($query);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_incident_with_pagging($where_clause = 1, $limit = 5, $auth_user = array()) {
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_incident_view . "` WHERE " . $where_clause;
        $row = $this->select($query, array(), 1);
        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query = "SELECT * FROM " . $this->_incident_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            $allposts = $this->select($query);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_participants_with_pagging($where_clause = 1, $limit = 5, $auth_user = array()) {
        $this->_view = "activity_participants_mapping_view";
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_view . "` WHERE " . $where_clause;
        $row = $this->select($query, array(), 1);
        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query = "SELECT * FROM " . $this->_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            $allposts = $this->select($query);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_categories($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM master_post_categories WHERE " . $clause;
        return $this->select($query);
    }

    public function get_incident_category() {
        $query = "SELECT * FROM " . $this->_incident_category;
        return $this->select($query);
    }
    public function get_activity_category() {
        $query = "SELECT * FROM " . $this->_activity_type_master_mis ;
        return $this->select($query);
    }

    public function get_nature_injury() {
        $query = "SELECT * FROM " . $this->_nature_of_injury;
        return $this->select($query);
    }

    public function get_body_part_injury($clause) {
        $query = "SELECT * FROM incident_body_part_injury" . $clause;
        return $this->select($query);
    }

    public function get_body_part_injury_mappingData($clause) {
        $query = "SELECT * FROM incident_body_part_injury_mapping " . $clause;
        return $this->select($query);
    }

    public function getarticles() {
        $query = "SELECT  * FROM " . $this->_view . " WHERE status_id = 3 ORDER BY id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    public function get_division_by_activity($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM  activity_division_mapping as dp"
                . " LEFT JOIN division_department as dd ON dd.id = dp.division_id WHERE " . $clause;
        return $this->select($query);
    }

    public function get_participants_categories($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM participants_category_master WHERE " . $clause;
        return $this->select($query);
    }
//MIS-10
    public function get_division_department($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM division_department WHERE " . $clause;
        return $this->select($query);
    }

    public function show_division_department($id) {

        $query = "SELECT * FROM division_department WHERE parent_id = " . $id;
        return $this->select($query);
    }

    public function get_division_department_mapping($clause = "", $tb) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM " . $tb . " WHERE " . $clause;
        return $this->select($query);
    }

    public function get_participants_category_mapping($clause = "", $table = Null) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM " . $table . " WHERE " . $clause;
        return $this->select($query);
    }

    public function get_activity_participants_mapping($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_participants_mapping WHERE " . $clause;
        return $this->select($query);
    }

    public function get_participants_by_activity($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_participants_mapping_view WHERE " . $clause;
        return $this->select($query);
    }

    public function get_findings_by_activity($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM incident_finding_mapping WHERE " . $clause;
        return $this->select($query);
    }

    public function get_violation_by_activity($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM audit_violation_mapping WHERE " . $clause;
        return $this->select($query);
    }

    public function get_linefunction_deviation_by_activity($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM safety_observation_line_function_deviation_mapping WHERE " . $clause;
        return $this->select($query);
    }

    public function get_deviation_by_activity($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM ppe_audit_deviation_mapping WHERE " . $clause;
        return $this->select($query);
    }

    public function get_participants_details($activity_id, $participant_cat_id) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_participants_mapping_view WHERE activity_id= $activity_id AND participant_cat_id = $participant_cat_id";

        return $this->select($query);
    }

    public function get_parti_details($activity_participant_category_id) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_participants_mapping WHERE activity_participant_category_id = $activity_participant_category_id";
        return $this->select($query);
    }

    public function get_participants_by_activity_new($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_participant_category_mapping_view WHERE " . $clause;
        return $this->select($query);
    }

    public function get_participants_details_new($activity_participant_category_id) {
        $activity_participant_category_id = ( $activity_participant_category_id == "" ) ? 1 : $activity_participant_category_id;
        $query = "SELECT * FROM activity_participants_mapping WHERE activity_participant_category_id = $activity_participant_category_id";
        return $this->select($query);
    }

    public function get_activity_type_master($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_type_master WHERE " . $clause;
        return $this->select($query);
    }

    public function getactivity($id = 0, $subid = 0, $type) {
        if ($type == 'activity') {
            if ($id > 0) {
                $query = "SELECT * FROM " . $this->_view . " as actview "
                        . "LEFT JOIN file_upload_activity_mapping as upd ON upd.activity_id = actview.id "
                        . "WHERE actview.id = :id";
            } else {
                $query = "SELECT pv.*, pv.category_path AS `path` FROM " . $this->_view . " as pv WHERE pv.status_id = 3 AND pv.category_deleted = 0 ORDER BY pv.modified_date DESC ";
            }

            $resutl = $this->db->prepare($query);
            $resutl->execute(
                    array( 
                        ":id" 	=> $id,
                    )
                    );
            return $resutl->fetchAll();
        }
        if ($type == 'audit') {
            if ($id > 0) {
                $query = "SELECT * FROM audit_view as actview "
                        . "LEFT JOIN file_upload_audit_mapping as upd ON upd.audit_id = actview.id "
                        . "WHERE actview.id = :id";
            } else {
                $query = "SELECT pv.*, pv.category_path AS `path` FROM audit_view as pv WHERE pv.status_id = 3 AND pv.category_deleted = 0 ORDER BY pv.modified_date DESC ";
            }

            $resutl = $this->db->prepare($query);
            $resutl->execute(
                    array( 
                        ":id" 	=> $id,
                    )
                    );
            return $resutl->fetchAll();
        }
        if ($type == 'incident') {
            if ($id > 0) {
                $query = "SELECT * FROM incident_view as actview "
                        . "LEFT JOIN file_upload_incident_mapping as upd ON upd.incident_id = actview.id "
                        . "WHERE actview.id = :id";
            } else {
                $query = "SELECT pv.*, pv.category_path AS `path` FROM incident_view as pv WHERE pv.status_id = 3 AND pv.category_deleted = 0 ORDER BY pv.modified_date DESC ";
            }

            $resutl = $this->db->prepare($query);
            $resutl->execute(
                    array( 
                        ":id" 	=> $id,
                        )
                    );
            return $resutl->fetchAll();
        }

        if ($type == 'ppe_audit') {
            if ($id > 0) {
                $query = "SELECT * FROM ppe_audit_view as actview "
                        . "LEFT JOIN file_upload_ppe_audit_mapping as upd ON upd.ppe_audit_id = actview.id "
                        . "WHERE actview.id = :id";
            } else {
                $query = "SELECT pv.*, pv.category_path AS `path` FROM ppe_audit_view as pv WHERE pv.status_id = 3 AND pv.category_deleted = 0 ORDER BY pv.modified_date DESC ";
            }

            $resutl = $this->db->prepare($query);
            $resutl->execute(
                    array( 
                        ":id" 	=> $id,
                        )
                    );
            return $resutl->fetchAll();
        }
        if ($type == 'safety_observation') {
            if ($id > 0) {
                $query = "SELECT * FROM safety_observation_view as actview "
                        . "WHERE actview.id = :id";
            } else {
                $query = "SELECT pv.*, pv.category_path AS `path` FROM safety_observation_view as pv WHERE pv.status_id = 3 AND pv.category_deleted = 0 ORDER BY pv.modified_date DESC ";
            }

            $resutl = $this->db->prepare($query);
            $resutl->execute(
                    array( 
                        ":id" 	=> $id,
                        )
                    );
            return $resutl->fetchAll();
        }
        if ($type == 'safety_observation_line_function') {
            if ($id > 0) {
                $query = "SELECT * FROM safety_observation_line_function_view as actview "
                        . "WHERE actview.id = :id";
            } else {
                $query = "SELECT pv.*, pv.category_path AS `path` FROM safety_observation_line_function_view as pv WHERE pv.status_id = 3 AND pv.category_deleted = 0 ORDER BY pv.modified_date DESC ";
            }
            $resutl = $this->db->prepare($query);
            $resutl->execute(
                    array( 
                        ":id" 	=> $id,
                        )
                    );
            return $resutl->fetchAll();
        }
    }

    public function check_activity_no_exists($activity_no) {
        if ($activity_no == "")
            return FALSE;
        $query = "SELECT id FROM " . $this->_table . " WHERE activity_no = :activity_no";
        $result = $this->db->prepare($query);
        $result->execute(array(":activity_no" => $activity_no));
        $this->set_query_error($result);
        $row = $result->fetch($this->fetch_obj);
        $id = isset($row->id) ? $row->id : 0;
        return ($id > 0) ? TRUE : FALSE;
    }

    public function check_emp_code_exists($employee_code) {

        if ($employee_code == "")
            return FALSE;
        $this->_table = " master_users";
        $query = "SELECT id FROM " . $this->_table . " WHERE employee_code = :employee_code";
        $result = $this->db->prepare($query);
        $result->execute(array(":employee_code" => $employee_code));
        $this->set_query_error($result);
        $row = $result->fetch($this->fetch_obj);
        $id = isset($row->id) ? $row->id : 0;
        return ($id > 0) ? TRUE : FALSE;
    }

    public function check_participant_exists($activity_id, $part_cat_id, $colname = null, $table = Null) {

        if ($part_cat_id == "")
            return FALSE;
        $query = "SELECT id FROM " . $table . " WHERE " . $colname . " = :activity_id AND participant_cat_id = :participant_cat_id";
        $result = $this->db->prepare($query);
        $result->execute(array(":participant_cat_id" => $part_cat_id, ":activity_id" => $activity_id));
        $this->set_query_error($result);
        $row = $result->fetch($this->fetch_obj);
        $id = isset($row->id) ? $row->id : 0;
        return ($id > 0) ? TRUE : FALSE;
    }

    public function fetchdata($post_id = 0, $table_name) {
        if (!$post_id)
            return array();
        return $this->select("SELECT * FROM $table_name WHERE id = " . $post_id);
    }


    public function get_post_uploads($activity_id = 0) {
        if (!$activity_id)
            return array();
        $query = "SELECT * FROM file_upload_activity_mapping WHERE activity_id = " . $activity_id;
        return $this->select($query);
    }

    public function get_audit_uploads($audit_id = 0) {
        if (!$audit_id)
            return array();
        $query = "SELECT * FROM file_upload_audit_mapping WHERE audit_id = " . $audit_id;
        return $this->select($query);
    }

    public function get_ppe_audit_uploads($audit_id = 0) {
        if (!$audit_id)
            return array();
        $query = "SELECT * FROM file_upload_ppe_audit_mapping WHERE ppe_audit_id = " . $audit_id;
        return $this->select($query);
    }

    public function get_incident_uploads($incident_id = 0) {
        if (!$incident_id)
            return array();
        $query = "SELECT * FROM file_upload_incident_mapping WHERE incident_id = " . $incident_id;
        return $this->select($query);
    }

    public function insert_participants($query = null) {
        $query = "";
        if (!$query)
            return array();
        return $this->select($query);
    }

    /* Division Department Tree */

    public function get_childvalue($parent_id = 0) {
        if (!$parent_id)
            return array();
        $query = "SELECT * FROM division_department WHERE parent_id = " . $parent_id;
        return $this->select($query);
    }

    public function check_tbvalue($id = 0) {
        if (!$id)
            return array();
        $query = "SELECT * FROM division_department WHERE id = " . $id;
        $rowdata = $this->select($query);
        return $rowdata[0]->table_name;
    }

    public function get_setvalue($tb = '', $id = 0) {

        $query = "SELECT * FROM " . $tb . " WHERE division_id = " . $id . " ORDER BY name asc";
        $rowdata = $this->select($query);
        return $rowdata;
    }

    public function get_labelname($id = 0) {
        if (!$id)
            return array();
        $query = "SELECT * FROM division_department WHERE id = " . $id;
        $rowdata = $this->select($query);
        if ($rowdata[0]->label_id != 0) {
            $query2 = "SELECT * FROM division_level WHERE id = " . $rowdata[0]->label_id;
            $rowdata2 = $this->select($query2);
            return $rowdata2[0]->level_name;
        } else {
            return false;
        }
    }

    /* Division Department Tree */

    public function getAuditViolation($audit_id) {
        $query = "SELECT audit_id FROM audit_violation_mapping WHERE audit_id = " . $audit_id;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    // Incident
    public function updateincidentFindings($incident_id, $val) {
        $query = "UPDATE incident SET investigation_req = 'Y',investigation_done = 'Y', no_of_finding = '$val' WHERE id = '$incident_id' ";
        $rowdata = $this->select($query);
    }

    public function updatelinefunctionDeviation($line_function_id, $val) {
        $query = "UPDATE safety_observation_line_function SET major_deviation = 'Y', no_of_deviation = '$val' WHERE id = '$line_function_id' ";
        $rowdata = $this->select($query);
    }

    public function getincidentFindings($incident_id) {
        $query = "SELECT incident_id FROM incident_finding_mapping WHERE incident_id = " . $incident_id;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function getlinefunctionDeviation($line_function_id) {
        $query = "SELECT line_function_id FROM safety_observation_line_function_deviation_mapping WHERE line_function_id = " . $line_function_id;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function getRelatedFindings($token_id) {
        $query = "SELECT * FROM incident_finding_mapping WHERE token_id = " . $token_id;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function deleteRelatedFindings($token_id) {
        $query = "DELETE FROM incident_finding_mapping WHERE token_id = " . $token_id . " AND incident_id = 0";
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function getRelated($token_id) {
        $query = "SELECT * FROM safety_observation_line_function_deviation_mapping WHERE token_id = " . $token_id;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function deleteRelated($token_id) {
        $query = "DELETE FROM safety_observation_line_function_deviation_mapping WHERE token_id = " . $token_id . " AND line_function_id = 0";
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    // violation
    public function getRelatedViolation($token_id) {

        $query = "SELECT * FROM audit_violation_mapping WHERE token_id = " . $token_id;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function deleteRelatedViolation($token_id) {

        $query = "DELETE FROM audit_violation_mapping WHERE token_id = " . $token_id . " AND audit_id = 0";
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    // deviation
    public function updateAuditDeviation($ppe_audit_id, $val) {

        $query = "UPDATE ppe_audit SET major_deviation = 'Y', no_of_deviation = '$val' WHERE id = '$ppe_audit_id' ";
        $rowdata = $this->select($query);
    }

    public function getAuditDeviation($ppe_audit_id) {

        $query = "SELECT ppe_audit_id FROM ppe_audit_deviation_mapping WHERE ppe_audit_id = " . $ppe_audit_id;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function getRelatedDeviation($token_id) {

        $query = "SELECT * FROM ppe_audit_deviation_mapping WHERE token_id = " . $token_id;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function deleteRelatedDeviation($token_id) {

        $query = "DELETE FROM ppe_audit_deviation_mapping WHERE token_id = " . $token_id . " AND ppe_audit_id = 0";
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function getRelatedParticipants($token_id, $participant_cat_id) {

        $query = "SELECT * FROM activity_participants_mapping WHERE token_id = " . $token_id . " AND participant_cat_id=" . $participant_cat_id;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function deleteRelatedParticipants($token_id, $participant_cat_id) {

        $query = "DELETE FROM activity_participants_mapping WHERE token_id = " . $token_id . " AND activity_participant_category_id = 0 AND participant_cat_id=" . $participant_cat_id;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function updateAuditViolation($audit_id, $val) {

        $query = "UPDATE audit SET major_violation = 'Y',no_of_violation = '$val' WHERE id = '$audit_id' ";
        $rowdata = $this->select($query);
    }

    public function update_participants_mapping($no_of_parti, $activity_id, $participant_cat_id, $task) {
        if ($task == "U") {
            $nop = "no_of_participants + " . $no_of_parti;
        }
        if ($task == "D") {
            $nop = "no_of_participants - " . $no_of_parti;
        }
        $query = "UPDATE activity_participant_category_mapping SET no_of_participants =" . $nop . " WHERE activity_id = '" . $activity_id . "' AND participant_cat_id='" . $participant_cat_id . "' AND type='P'";
        $rowdata = $this->select($query);
        $query2 = "SELECT id AS rowid FROM activity_participant_category_mapping WHERE activity_id = '" . $activity_id . "' AND participant_cat_id='" . $participant_cat_id . "' AND type='P'";

        $rowdata2 = $this->select($query2);
        $rowid = $rowdata2[0]->rowid;
        $query3 = "UPDATE activity_participants_mapping SET activity_participant_category_id =" . $rowid . " WHERE activity_id = '" . $activity_id . "' AND participant_cat_id='" . $participant_cat_id . "'";

        $rowdata = $this->select($query3);
        
        }

    public function getFramework() {
        $query = "SELECT * FROM master_framework";
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function getFrameworkValue($id) {
        if (empty($id))
            return FALSE;
        $query = "SELECT * FROM master_framework_value where framework_id ='$id'";
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function getFrameworkById($id = Null) {
        $query = "SELECT * FROM safety_observation_framework_mapping WHERE line_function_id='$id'";
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function get_contractor_list($clause) {
        $query = "SELECT COUNT(identification_code) as avgcode,identification_code,name,root_division_id FROM cset_contractor " . $clause;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function get_DataByDate($table_name, $clause) {
        $query = "SELECT * FROM " . $table_name . " " . $clause;
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function getMinMaxDate() {
        $query = "SELECT MIN(dateListing) AS min_date, MAX(dateListing) AS max_date FROM
                        (
                            SELECT date_of_audit AS dateListing FROM audit  WHERE date_of_audit!='0000-00-00' AND deleted='0' UNION 
                            SELECT activity_date AS dateListing  FROM activity  WHERE activity_date!='0000-00-00' AND deleted='0' UNION
							SELECT from_date AS dateListing FROM activity  WHERE from_date!='0000-00-00' AND deleted='0' UNION
                            SELECT date_of_incident AS dateListing FROM incident  WHERE date_of_incident!='0000-00-00' AND deleted='0' UNION
                            SELECT date_of_audit AS dateListing FROM ppe_audit WHERE date_of_audit!='0000-00-00' AND deleted='0' UNION
                            SELECT STR_TO_DATE(CONCAT(activity_year,'-',activity_month,'-',01),'%Y-%m-%d' ) AS dateListing 
                            FROM safety_observation 
                            WHERE activity_year!='0000' AND activity_month!='00'  AND deleted='0'
                        ) AS sq";
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function getActivityReport($activity_type_id, $dt, $col, $table, $st, $status_idx) {
        $statusids = (($status_idx == 5) ? "5,4,3" : $status_idx);
        if ($st == "A") {
            $status_id = " AND status_id IN ($statusids)";
        } else if ($st == "NA") {
            $status_id = " AND status_id IN ($statusids)";
        }
        
        $clause = " WHERE activity_type_id=" . $activity_type_id . " AND created_by!='2' AND deleted='0' " . $status_id . " AND " . $col . " LIKE '" . $dt . "%'";
        $query = "SELECT * FROM " . $table . " " . $clause;
        $rowdata = $this->select($query);
        return (count($rowdata) > 0) ? count($rowdata) : "-";
    }

    public function getTrainingReport($dt, $st, $status_idx) {
        $statusids = (($status_idx == 5) ? "5,4,3" : $status_idx);
         if ($st == "A") {
            $status_id = " AND status_id IN ($statusids)";
        } else if ($st == "NA") {
            $status_id = " AND status_id IN ($statusids)";
        }
        $clause = " WHERE activity_type_id=3 AND created_by!='2' AND type='P' AND deleted='0' " . $status_id . " AND from_date LIKE '" . $dt . "%'";
        $query = "SELECT * FROM activity_training_participant_view " . $clause;
        $rowdata = $this->select($query);
        $arrdata = array();
        $values = 0;
        foreach ($rowdata as $val) {

            $arrdata = $val->no_of_participants;
            $values += $arrdata;
        }

        return (($values) > 0) ? ($values) : "-";
    }

    public function getSafetyObsReport($activity_type_id, $month, $year, $st, $status_idx) {
        $statusids = (($status_idx == 5) ? "5,4,3" : $status_idx);
         if ($st == "A") {
            $status_id = " AND status_id IN ($statusids)";
        } else if ($st == "NA") {
            $status_id = " AND status_id IN ($statusids)";
        }

        $clause = " WHERE activity_type_id=" . $activity_type_id . " AND  activity_year='" . $year . "' AND deleted='0' " . $status_id . " AND activity_month='" . $month . "' "
                . "GROUP BY activity_year,activity_month";
        $query = "SELECT activity_month,activity_year,activity_type_id,SUM(activity_count) as activity_count  FROM safety_observation " . $clause;
        $rowdata = $this->select($query);
        return (count($rowdata) > 0) ? $rowdata[0]->activity_count : "-";
    }

    public function getAllUsers($clause = Null) {
        $query = "SELECT * FROM  master_users WHERE deleted=0 " . $clause;
        return $this->select($query);
    }

    public function getReportofPostUserWise($activity_type_id,$from_date=null,$to_date=null,$col, $table, $createdby, $status) {
        if( !empty($from_date) && !empty($to_date)) {
            $newclause = "  AND ". $col ." BETWEEN '$from_date' AND '$to_date'";
        }
        else
        {
            $newclause ="";
        }
        $statusids = (($status == 5) ? "5,4,3" : $status);
        $clause = " WHERE activity_type_id=" . $activity_type_id . " AND " . $col . "!='0000-00-00' "
                . "AND created_by =" . $createdby . " AND deleted='0' "
                . "AND created_by!='2' AND status_id IN (" . $statusids.") ".$newclause;
        $query = "SELECT * FROM " . $table . " " . $clause;
        $rowdata = $this->select($query);
        return (count($rowdata) > 0) ? count($rowdata) : "-";
    }

    public function getTrainingReportPostUserWise($createdby,$from_date=null,$to_date=null, $status) {
        if( !empty($from_date) && !empty($to_date)) {
            $newclause = "  AND from_date BETWEEN '$from_date' AND '$to_date'";
        }
        else
        {
            $newclause ="";
        }
        $statusids = (($status == 5) ? "5,4,3" : $status);
        $clause = " WHERE activity_type_id=3 AND deleted='0' AND type='P' AND from_date!='0000-00-00' "
                . "AND created_by =" . $createdby . "  AND created_by!='2' AND status_id IN (" . $statusids.")".$newclause;
        $query = "SELECT * FROM activity_training_participant_view " . $clause;
        $rowdata = $this->select($query);
        $arrdata = array();
        $values = 0;
        foreach ($rowdata as $val) {
            $arrdata = $val->no_of_participants;
            $values += $arrdata;
        }

        return (($values) > 0) ? ($values) : "-";
    }

    public function getSafetyObsReportPostUserWise($createdby,$dateArr=null, $status) {
        if( !empty($dateArr)) {
            $imp = implode("','",$dateArr);
//            $fromyear = date("Y",strtotime($from_date));
//            $frommonth = date("m",strtotime($from_date));
//            $toyear = date("Y",strtotime($to_date));
//            $tomonth = date("m",strtotime($to_date));
            $newclause = "  AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) IN ('$imp')";
        }
        else
        {
            $newclause ="";
        }
        $statusids = (($status == 5) ? "5,4,3" : $status);
        $clause = " WHERE  status_id IN (" . $statusids . ") AND activity_year!='0000' AND activity_month!='0' "
                . "AND created_by =" . $createdby . " AND created_by !='2' AND deleted='0' ".$newclause
                . "GROUP BY created_by";
        $query = "SELECT activity_month,activity_year,activity_type_id,CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) as formatted_date,SUM(activity_count) as activity_count  FROM safety_observation " . $clause;
        $rowdata = $this->select($query);
        return (count($rowdata) > 0) ? $rowdata[0]->activity_count : "-";
    }

    public function getActualTargetTraining($month, $div) {
        $clause = " WHERE activity_type_id=3 AND deleted='0' AND type='P' AND status_id IN (5,4,3) AND from_date!='0000-00-00' AND created_by!='2' AND from_date LIKE '" . $month . "%'";
        $query = "SELECT * FROM actualtarget_training_view " . $clause;
        $rowdata = $this->select($query);
        $arrdata = array();
        $values = 0;
        $post_division_department = $this->get_division_department();
        $totaldiv = $totalgen = $distribution = $generation = 0;
        if (!empty($rowdata)) {
            foreach ($rowdata as $row) {
                $tree_division_id_arr = ($row->tree_division_id != "") ? explode("-", $row->tree_division_id) : array();
                $divition = '';
                $totaldiv = $totalgen = 0;
                   if (!empty($tree_division_id_arr)) {
                       for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                           if ($tree_division_id_arr[$i] == "")
                               continue;
                           if (is_numeric($tree_division_id_arr[$i])) {
                               $Arr = explode(',', $div);
                               if(in_array($tree_division_id_arr[$i], $Arr)) {
                                   $totaldiv = 1;
                               }
                           }
                       }
                   }
                if ($totaldiv == 1) {
                    $distribution += $row->no_of_participants;
                }
            }
        }
            return ($distribution == 0) ? "-" : $distribution;
    }

    public function getActualTargetSafeyObs($month, $year, $div) {
        $clause = " WHERE activity_year='" . $year . "'  AND activity_month='" . $month . "' AND status_id IN (5,4,3) AND activity_year!='0000' AND activity_month!='0' AND created_by !='2' AND deleted='0' ";
        $query = "SELECT * FROM actualtarget_safetyobs_view " . $clause;
        $rowdata = $this->select($query);
        $post_division_department = $this->get_division_department();
        $distribution = $generation = 0;
        if (!empty($rowdata)) {
            foreach ($rowdata as $row) {
                $totaldiv = $totalgen = 0;
                $tree_division_id_arr = ($row->tree_division_id != "") ? explode("-", $row->tree_division_id) : array();
                $divition = '';
                if (!empty($tree_division_id_arr)) {
                       for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                           if ($tree_division_id_arr[$i] == "")
                               continue;
                           if (is_numeric($tree_division_id_arr[$i])) {
                               $Arr = explode(',', $div);
                               if(in_array($tree_division_id_arr[$i], $Arr)) {
                                   $totaldiv = 1;
                               }
                           }
                       }
                   }
                if ($totaldiv == 1) {
                    $distribution += $row->activity_count;
                }
            }
        }
            return ($distribution == 0) ? "-" : $distribution;
    }

    public function getActualTarget($activity_type_id, $table, $col, $month, $div) {
         //AND status_id='5'
        $clause = " WHERE activity_type_id=" . $activity_type_id . " AND deleted='0' AND status_id IN (5,4,3) AND  created_by!=2 AND " . $col . "!='0000-00-00' AND " . $col . " LIKE '" . $month . "%'";
        $query = "SELECT * FROM " . $table . " " . $clause;
        $rowdata = $this->select($query);

        $post_division_department = $this->get_division_department();
        $totaldiv = $totalgen = $distribution = $generation = $totalValCount = 0;
        if (!empty($rowdata)) {

            foreach ($rowdata as $row) {
                $tree_division_id_arr = ($row->tree_division_id != "") ? explode("-", $row->tree_division_id) : array();
                $divition = '';
                $totalVal = 0;
                if (!empty($tree_division_id_arr)) {

                    for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                        if ($tree_division_id_arr[$i] == "")
                            continue;
                        if (is_numeric($tree_division_id_arr[$i])) {
                            $Arr = explode(',', $div);
                            
                            if(in_array($tree_division_id_arr[$i], $Arr)) {
                                $totalVal = 1;
                            }
                        }
                    }
                }
                if ($totalVal == 1) {
                    $totalValCount += 1;
                }
               
            }
        }
            return ($totalValCount == 0) ? "-" : $totalValCount;
    }

    public function allFinancialYear($id = Null) {
        $clause = "";
        if (!empty($id)) {
            $clause = " where id=$id";
        }
        $query = "SELECT * FROM financial_year" . $clause;
        $rowdata = $this->select($query);
        return ($rowdata);
    }
    
    public function allFinancialYearById() {
        $data = array();
        $query = "SELECT * FROM financial_year";
        $rowdata = $this->select($query);
        foreach($rowdata as $key => $value){
            $data[$value->id] = $value->financial_year;
        }
        return ($data);
    }

    public function alldatafromyear($financial_year_id, $financial_district_id_create,$activity_type_id) {
        $rowdata = array();

        $query = "SELECT * FROM yearly_target WHERE financial_year_id = " . $financial_year_id . " AND activity_type_id = " . $activity_type_id . " AND district_id = '$financial_district_id_create'";
        $rowdata = $this->select($query);
        return ( count($rowdata) > 0 ? $rowdata[0] : array());
    }
     public function alldatafromyearview($financial_year_id, $financial_district_id_create,$activity_type_id) {
        $rowdata = array();

       
        $query = "select * from yearly_target "
                . "where financial_year_id = ".$financial_year_id." AND activity_type_id = " . $activity_type_id . " AND district_id IN "
                . "(select district_id from yearly_target where district_id IN (select district_id from yearly_target where parent_id=".$financial_district_id_create." OR district_id=".$financial_district_id_create.") "
                . "OR parent_id IN (select district_id from yearly_target where parent_id=".$financial_district_id_create." OR district_id=".$financial_district_id_create."))";
        
        $rowdata = $this->select($query);
        return ($rowdata);
    }
    public function allscorefromyear($financial_year_id,$financial_district_id_create,$rowID) {
        $rowdata = array();

        $query = "SELECT * FROM site_audit_score_target WHERE tree_id=".$rowID." AND financial_year_id = " . $financial_year_id. " AND district_id=".$financial_district_id_create;
        $rowdata = $this->select($query);
        return ( count($rowdata) > 0 ? $rowdata[0] : array());
    }
    public function allscorefromyearview($financial_year_id,$financial_district_id_create,$rowID) {
        $rowdata = array();
         $query = "select * from site_audit_score_target "
                . "where financial_year_id = ".$financial_year_id." AND tree_id = " . $rowID . " AND district_id IN "
                . "(select district_id from site_audit_score_target where district_id IN (select district_id from site_audit_score_target where parent_id=".$financial_district_id_create." OR district_id=".$financial_district_id_create.") "
                . "OR parent_id IN (select district_id from site_audit_score_target where parent_id=".$financial_district_id_create." OR district_id=".$financial_district_id_create."))";
        
$rowdata = $this->select($query);
        return ($rowdata);
    }

    public function checkYearlyTargetExist($activity_type_id, $financial_district_id, $financial_year_id) {

        $clause = " WHERE activity_type_id = '" . $activity_type_id . "' AND district_id='" . $financial_district_id . "' AND financial_year_id='" . $financial_year_id . "'";
        $query = " SELECT * FROM yearly_target" . $clause;
        $rowdata = $this->select($query);
        return ($rowdata);
    }
    public function checkscoreExist($tree_id, $financial_year_id, $district_id) {

        $clause = " WHERE tree_id='" . $tree_id . "' AND financial_year_id='" . $financial_year_id . "' AND district_id='".$district_id."'";
        $query = " SELECT * FROM site_audit_score_target" . $clause;
        $rowdata = $this->select($query);
        return ($rowdata);
    }
    
    

    public function yearlyTargetList($financial_year_id) {

        $clause = " WHERE financial_year_id='" . $financial_year_id . "'";
        $query = " SELECT * FROM yearly_target" . $clause;
        $rowdata = $this->select($query);
        return ($rowdata);
    }
    public function getReportSettings($value,$column) {

        $clause = " WHERE ".$column."='" . $value . "'";
        $query = " SELECT * FROM report_settings" . $clause;
        $rowdata = $this->select($query);
        return ($rowdata);
    }
    
    /*****MIS TREE******/
    public function getMisTreeData( $activity_type_id, $table, $col,$treedid) {
        
        if( $activity_type_id == 8 ) {
        $clause = " WHERE activity_type_id=" . $activity_type_id . " AND activity_year!='0000' AND activity_month!='0' AND created_by !='2' AND deleted='0' ";
        } else if( $activity_type_id == 3 ) {
        $clause     = " WHERE activity_type_id=" . $activity_type_id . " AND type='P' AND deleted='0' AND created_by!='2' AND " . $col . "!='0000-00-00'";
        } else {
        $clause     = " WHERE activity_type_id=" . $activity_type_id . " AND deleted='0' AND created_by!='2' AND " . $col . "!='0000-00-00'"; 
        }
        $query      = "SELECT * FROM " . $table . " " . $clause;
        $rowdata    = $this->select($query);
        
        
        $totaldiv = $totalgen = $distribution = $generation = $countvalue = $distribution1 = $distribution2= 0;
        if (!empty($rowdata)) {

            foreach ($rowdata as $row) {
          $nm="";
                $tree_division_id_arr = ($row->tree_division_id != "") ? explode("-", $row->tree_division_id) : array();
                $totaldiv = $totalgen = 0;
                if (!empty($tree_division_id_arr)) {
                    for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                        if ($tree_division_id_arr[$i] == "")
                            continue;
                        if (is_numeric($tree_division_id_arr[$i])) {
                        $nm.= $tree_division_id_arr[$i]."-";
                        }
                    }
                    
                   $asd=substr($nm,'0','-1');
                   $exp=explode("-",$asd);
                   $newval = end($exp);
                   if($newval == $treedid) {
                      $totaldiv+= 1; 
                   }
                }
                if( $activity_type_id == 3 ) {
                    $countvalue = $row->no_of_participants ? $row->no_of_participants : 0;
                } else if( $activity_type_id == 8 ) {
                    $countvalue = $row->activity_count;
                }
                else {
                    $countvalue =1;
                }
                if ($totaldiv == 1){
                    $distribution1 += $countvalue;
                }
            }
        }
      $distribution=$distribution1+$distribution2;
        return ($distribution == 0) ? 0 : $distribution;
    }
     
    public function getTreeData(){ 
        $query = " SELECT * FROM mistree_cron_data";
        $rowdata = $this->select($query);
        return ($rowdata);
    }

    public function division_child_unit_count( $ids, $set_type, $ids_arr = array(), $tables, $count = 0 ){
        
            $sql = "SELECT * FROM division_department WHERE parent_id IN ( ".@$ids." ) AND path LIKE '%/".$set_type."/%' ";
            if( @$count == 0 && @$ids == 9  ){
               $sql = "SELECT * FROM division_department WHERE parent_id IN ( ".@$ids." ) ";   
            }
            if( @$count == 0 && @$ids == 10 ){
               $sql = "SELECT * FROM division_department WHERE parent_id IN ( ".@$ids." ) AND path LIKE '%/MNTC/%' ";
            }
            if( ( @$ids == 7 || @$ids == 8 || @$ids == 168 || @$ids == 6 ) && ( @$set_type == "P-SET" ) ){
                return $this->chield_unit_count_with_out_set(@$ids, @$ids_arr = array());
            }
            if( ( @$ids == 181 || @$ids == 183 ) ){
                return $this->chield_unit_count_with_out_set(@$ids, @$ids_arr = array());
            }
            @$ids = $this->select($sql);
            foreach ($ids as $value){
                @$arr_ids[]              = $value->id;
                @$arr_tables[$value->id] = $value->table_name;
            }
            
            @$ids_in = implode(", ", @$arr_ids);
            if( !empty($ids) ){
                    $count ++;
                    return $this->division_child_unit_count($ids_in, $set_type, $arr_ids, $arr_tables, $count);
            }else{
                if( count(@$tables) == 1 || @$count == 2 ){
                     return $this->child_unit_count_other_table(@$tables,@$set_type);
                }else{
                     return count(@$tables);
                }
            }
    }
    
    public function child_unit_count_other_table( $tables, $set_type ){
        $tbls_val = '';
        $table_name = '';
        
        if($set_type == "P-SET"){
           $tbls_val = "pset_no,pset_location,pset_type";
           $tableArr = explode(",", $tbls_val);
           $table_name = $tableArr[0];
           
           foreach( $tables as $key => $value ){
           if( $value == $tbls_val){
               $division_id[] = $key;
           }
        }
        
        $division_id = implode(", ",$division_id);
        $sql = "SELECT * FROM ".$table_name." WHERE division_id IN ( ".$division_id." ) ";
        $ids = $this->select($sql);
        return count($ids);  
        }
        
        if($set_type == "C-SET"){
            $tbls_val = "cset_contractor,cset_type";
            $tableArr = explode(",", $tbls_val);
            $table_name = $tableArr[0];
            
            foreach( $tables as $key => $value ){
           if( $value == $tbls_val){
               $division_id[] = $key;
           }
        }
        $division_id = implode(", ",$division_id);
        $sql = "SELECT * FROM ".$table_name." WHERE division_id IN ( ".$division_id." ) GROUP BY code";
        $ids = $this->select($sql);
        return count($ids);
        }
    }
    
    /**
     * Function : chield_unit_count_with_out_set($param1,$param2)
     * Created by Anima Mahato
     * @param type Integer
     * @param type Array
     * @return type Integer
     */
    public function chield_unit_count_with_out_set($ids, $ids_arr = array()){
        
            $sql = "SELECT * FROM division_department WHERE parent_id IN ( ".$ids." ) "; 
            @$ids = $this->select($sql);
            foreach ($ids as $value){
                @$arr_ids[] = $value->id;
            }
            @$ids_in = implode(", ", @$arr_ids);
            
            if(!empty($ids)){
                return $this->chield_unit_count_with_out_set($ids_in, $ids);
            }else{
               return count(@$ids_arr);
            }
    }
    
    
    /**
     * Function : get_cset_contractor_details($param1)
     * Created by Anima Mahato 
     * Reason : to get all C-SET named from division department by id.
     * @param type Integer
     * @return type Array
     */
    public function get_cset_contractor_details($ids){
        
            $sql = "SELECT * FROM division_department WHERE parent_id =".$ids." AND name ='C-SET' ";
            $row = $this->select($sql);
            
            $sql2 = "SELECT * FROM cset_contractor WHERE division_id =".$row[0]->id." GROUP BY code";
            $return_rows = $this->select($sql2);
            return $return_rows;
    }

    /**
     * Function : get_all_major_activities($param1)
     * Created by Anima Mahato 
     * Reason : to get all major activities target type wise.
     * @param type Integer
     * @return type Array
     */
    public function get_all_major_activities($target_type){  
        if( $target_type == "" ) return FALSE;
        $sql = "SELECT * FROM mis_major_activities WHERE target_type IN (". $target_type .")";//show($sql); 
        $resutl = $this->db->prepare($sql);
        $resutl->execute();
        return $resutl->fetchAll();
    }
    
    /**
     * Function : get_all_yearly_target($param1)
     * Created by Anima Mahato 
     * Modified by pallab
     * Reason : to get all yearly target.
     * @param type Array
     * @return type Array
     */
    public function get_all_yearly_target($arrcloumn = array(),$pvalue){     
        if(!empty($arrcloumn)) {
            $clause1 = "";
            $cnt = count($arrcloumn);
            foreach ($arrcloumn as $key => $value) {
                if($cnt > 0) { $sd = "AND"; } else { $sd =""; }
              $clause1 .= "$key='$value' $sd " ;
              $cnt--;
            }
            $clause = substr($clause1,'0','-4');
        } else {
            $clause = 1;
        }
        $sql = "SELECT * FROM mis_yearly_target WHERE ".$clause;
        $resutl = $this->db->prepare($sql);
        $resutl->execute($pvalue);
        return $resutl->fetchAll();
    } 
    
    /**
     * Function : get_pset_contractor_details($param1)
     * Created by Anima Mahato 
     * Reason : to get all P-SET named from division department by id.
     * @param type Integer
     * @return type Array
     */
    public function get_pset_contractor_details($ids){        
        $sql = "SELECT * FROM division_department WHERE parent_id =".$ids." AND name ='P-SET' ";
        $row = $this->select($sql);
        $sql2 = "SELECT * FROM pset_no WHERE division_id =".$row[0]->id;
        $return_rows = $this->select($sql2);
        return $return_rows;
    }
    
    /**
     * Function : activitydataforall($param1,$param2,$param3,$param4,$param5)
     * Created by Anima Mahato 
     * Reason : to get all activity data to check mis data tree wise.
     * @param type Integer
     * @param type String
     * @param type String
     * @param type Date
     * @param type Array
     * @return type Integer
     */
    public function activitydataforall($acttypeid,$tablename,$colname,$date_of_incident,$treeArr) {
        $clause = "";
        if(!empty($treeArr)) {
            $clause .=  "AND (";
            foreach( $treeArr as $key => $value ) {
                $clause .=  " tree_division_id LIKE '$value%' ";
                if(count($treeArr) > 1 && $key < 9)  {
                    $clause .= "OR";
                }
            } 
            $clause .=  ")";
        }
        if( $acttypeid == 8 ) {
            $sql = "SELECT *,CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) as formatted_date FROM $tablename "
                . "WHERE deleted='0' AND created_by !='2' AND activity_type_id='$acttypeid' AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) LIKE '$date_of_incident%' $clause ";
        } else {
            $sql = "SELECT * FROM $tablename "
                . "WHERE deleted='0' AND created_by !='2' AND activity_type_id='$acttypeid' AND $colname LIKE '$date_of_incident%' $clause ";
        }
        $row = $this->select($sql);
        return count($row);    
    }
    
    /**
     * Function : get_annual_target_tr_so_sa_ws($param1)
     * Created by Santosh Singh
     * @param type Integer
     * @return type Array
     */
    public function get_all_annual_target($fyear){        
        $sql = "SELECT * FROM annual_target_tr_so_sa_ws WHERE financial_year_id = ".$fyear;
        $row = $this->select($sql);
        return $row;
    }
    public function get_contractor(){
        $new_data_set = array();
        $sql = "SELECT * FROM cset_contractor WHERE division_id IN (SELECT id FROM division_department WHERE parent_id = 181) GROUP BY identification_code";
        $data_set1 = $this->select($sql);
        foreach($data_set1 as $key=>$values){
          $new_data_set[]  = $values;
        }
        
        $sql3 = "SELECT * FROM division_department WHERE id IN (184,185,187,186)";
        $data_set4 = $this->select($sql3);
        foreach($data_set4 as $key=>$values){
          $new_data_set[]  = $values;
        }
        
        $sql1 = "SELECT * FROM cset_contractor WHERE division_id IN (SELECT id FROM division_department WHERE parent_id = 183) GROUP BY identification_code";
        $data_set2 = $this->select($sql1);
        foreach($data_set2 as $key=>$values){
          $new_data_set[]  = $values;
        }
        
        $sql4 = "SELECT * FROM division_department WHERE id IN (201,204,203,302)";
        $data_set5 = $this->select($sql4);
        foreach($data_set5 as $key=>$values){
          $new_data_set[]  = $values;
        }
        
        
        return $new_data_set;
        
    }
    public function get_annual_activity(){
        $sql = "SELECT * FROM annual_target_label WHERE status = '1' ";
        $row = $this->select($sql);
        return $row;
        
    }

    public function get_annualtardetdistaudit($financial_year_id_create) {
       
                $query = "SELECT * FROM annual_target_dist_site_audit WHERE financial_year_id = $financial_year_id_create ";
                
          $rowdata = $this->select($query);
        $arrdata = array();
       
        foreach ($rowdata as $val) {

            $arrdata[] = $val;
           
        }
        return $arrdata;
    } 
    
    public function get_allppeaudit($id) {
        $sql = "SELECT 	id, div_id, div_dept_id, month_id, set_type, target_value FROM annual_target_ppe_audit WHERE financial_year_id =".$id;
        $row = $this->select($sql);  
        return $row;
    }
    
    public function get_sadisttarget($id) {
        $sql = "SELECT 	id, div_dept_id, month_id, set_type, target_value FROM annual_target_dist_site_audit WHERE financial_year_id =".$id;
        $row = $this->select($sql);  
        return $row;
    }
    
    public function get_handholding($id) {
        $sql = "SELECT 	id, div_dept_id, month_id, set_type, hand_holding, target_value FROM annual_target_hand_holding  WHERE financial_year_id =".$id;
        $row = $this->select($sql);  
        return $row;
    }
    
    public function get_sagentarget($id) {
        $sql = "SELECT 	id, cset_contractor_id, month_id, target FROM annual_target_gen_site_audit WHERE financial_year_id =".$id;
        $row = $this->select($sql);  
        return $row;
    }
    
    public function get_finaltarget($id) {
        $sql = "SELECT 	id, activity_id, month_id, target_value FROM annual_activity_target WHERE financial_year_id =".$id;
        $row = $this->select($sql);  
        return $row;
    }
    
    public function get_sad_view($id) {
        $sql = "SELECT 	month_id, total FROM annual_target_dist_site_audit_view WHERE financial_year_id =".$id;
        $row = $this->select($sql); 
        return $row;
    }
    
    public function get_hh_view($id) {
        $sql = "SELECT 	month_id, total FROM annual_target_hand_holding_view WHERE financial_year_id =".$id;
        $row = $this->select($sql); 
        return $row;
    }
    
    public function get_ppe_dist_c_view($id) {
        $sql = "SELECT 	month_id, total FROM annual_target_ppe_audit_dist_c_view WHERE financial_year_id =".$id;
        $row = $this->select($sql); 
        return $row;
    }
    
    public function get_ppe_dist_p_view($id) {
        $sql = "SELECT 	month_id, total FROM annual_target_ppe_audit_dist_p_view WHERE financial_year_id =".$id;
        $row = $this->select($sql); 
        return $row;
    }
    
    public function get_ppe_gen_c_view($id) {
        $sql = "SELECT 	month_id, total FROM annual_target_ppe_audit_gen_c_view WHERE financial_year_id =".$id;
        $row = $this->select($sql); 
        return $row;
    }
    
    public function get_ppe_gen_p_view($id) {
        $sql = "SELECT 	month_id, total FROM annual_target_ppe_audit_gen_p_view WHERE financial_year_id =".$id;
        $row = $this->select($sql); 
        return $row;
    }
    
    public function get_tr_ws_dist_view($id) { 
        $sql = "SELECT 	month_id, total FROM annual_target_tr_ws_dist_view WHERE financial_year_id =".$id;
        $row = $this->select($sql); 
        return $row;
    }
    
    public function get_tr_ws_gen_view($id) {
        $sql = "SELECT 	month_id, total FROM annual_target_tr_ws_gen_view WHERE financial_year_id =".$id;
        $row = $this->select($sql); 
        return $row;
    }
    
    public function get_tr_gen_c_view($id) {
        $sql = "SELECT 	month_id, total FROM annual_target_tr_gen_c_view WHERE financial_year_id =".$id;
        $row = $this->select($sql); 
        return $row;
    }
 
    
}
