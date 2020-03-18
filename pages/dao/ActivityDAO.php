<?php

class ActivityDAO extends MainDao {

    public $_table = "activity";
    public $_view = "activity_view";
    public $_audit_view = "audit_view";
    public $_ppe_audit_view = "ppe_audit_view";
    public $_incident_view = "incident_view";
    public $_incident_category = "master_incident_category";
    public $_nature_of_injury = "master_nature_of_injury";
    public $_safety_observation_view = "safety_observation_view";
    public $_safety_observation_line_function_view = "safety_observation_line_function_view";
    public $_minutes_of_meeting_view = "minutes_of_meeting_view";

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Modified by pallab
     * @param type $where_clause
     * @param type $limit
     * @param type $auth_user
     * @param type $nolimit
     * @param type $passValue
     * @return type
     */
    public function get_activity_with_pagging($where_clause = 1, $limit = 5, $auth_user = array(), $nolimit,$passValue) {
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;
//                $pagelink = isset($_GET['page']) ? $_GET['page'] : 1;
//                $this->pagging->page = ($nolimit ? 1 : $pagelink);

        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_view . "` WHERE " . $where_clause;

        $stmt = $this->db->prepare($query);
        $stmt->execute($passValue);
        $row = $stmt->fetch($this->fetch_obj);
        
        //$row = $this->select($query, array(), 1);

        $this->pagging->max_row = count($row) ? $row->maxrow : 0;
        
        $allposts = array();
        if ($this->pagging->max_row) {
            $query1 = "SELECT * FROM " . $this->_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            $stmt1 = $this->db->prepare($query1);
            $stmt1->execute($passValue);
            $allposts = $stmt1->fetchAll($this->fetch_obj);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_audit_with_pagging($where_clause = 1, $limit = 5, $auth_user = array(), $nolimit,$passValue) {
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;

        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_audit_view . "` WHERE " . $where_clause;
       
        $stmt = $this->db->prepare($query);
        $stmt->execute($passValue);
        $row = $stmt->fetch($this->fetch_obj);

        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query1 = "SELECT * FROM " . $this->_audit_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            $stmt1 = $this->db->prepare($query1);
            $stmt1->execute($passValue);
            $allposts = $stmt1->fetchAll($this->fetch_obj);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_ppe_audit_with_pagging($where_clause = 1, $limit = 5, $auth_user = array(), $nolimit,$passValue) {
        $pagelink = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_ppe_audit_view . "` WHERE " . $where_clause;
        $stmt = $this->db->prepare($query);
        $stmt->execute($passValue);
        $row = $stmt->fetch($this->fetch_obj);
        $this->pagging->max_row = count($row) ? $row->maxrow : 0;


        $allposts = array();
        if ($this->pagging->max_row) {
            $query1 = "SELECT * FROM " . $this->_ppe_audit_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            $stmt1 = $this->db->prepare($query1);
            $stmt1->execute($passValue);
            $allposts = $stmt1->fetchAll($this->fetch_obj);
            
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_safety_observation_with_pagging($where_clause = 1, $limit = 5, $auth_user = array(),$passValue) {
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;

        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_safety_observation_view . "` WHERE " . $where_clause;
        $stmt = $this->db->prepare($query);
        $stmt->execute($passValue);
        $row = $stmt->fetch($this->fetch_obj);
        //$row = $this->select($query, array(), 1);
        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query1 = "SELECT * FROM " . $this->_safety_observation_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            $stmt1 = $this->db->prepare($query1);
            $stmt1->execute($passValue);
            $allposts = $stmt1->fetchAll($this->fetch_obj);
            //$allposts = $this->select($query);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_safety_observation_line_function_with_pagging($where_clause = 1, $limit = 5, $auth_user = array(),$passValue) {
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;

        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_safety_observation_line_function_view . "` WHERE " . $where_clause;
        $stmt = $this->db->prepare($query);
        $stmt->execute($passValue);
        $row = $stmt->fetch($this->fetch_obj);
        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query1 = "SELECT * FROM " . $this->_safety_observation_line_function_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            $stmt1 = $this->db->prepare($query1);
            $stmt1->execute($passValue);
            $allposts = $stmt1->fetchAll($this->fetch_obj);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_minutes_of_meeting_function_with_pagging($where_clause = 1, $limit = 5, $auth_user = array(),$passValue) {
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_minutes_of_meeting_view . "` WHERE " . $where_clause;
        $stmt = $this->db->prepare($query);
        $stmt->execute($passValue);
        $row = $stmt->fetch($this->fetch_obj);
        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query1 = "SELECT * FROM " . $this->_minutes_of_meeting_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            
            $stmt1 = $this->db->prepare($query1);
            $stmt1->execute($passValue);
            $allposts = $stmt1->fetchAll($this->fetch_obj);
        }
        $this->pagging->data = $allposts;
        return $allposts;
    }

    public function get_incident_with_pagging($where_clause = 1, $limit = 5, $auth_user = array(), $nolimit,$passValue) {
        $pagelink = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->pagging->limit = ($limit > 1) ? $limit : $this->pagging->limit;
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

        $query = "SELECT COUNT(id) AS maxrow FROM `" . $this->_incident_view . "` WHERE " . $where_clause;
        $stmt = $this->db->prepare($query);
        $stmt->execute($passValue);
        $row = $stmt->fetch($this->fetch_obj);
        
        
        $this->pagging->max_row = count($row) ? $row->maxrow : 0;

        $allposts = array();
        if ($this->pagging->max_row) {
            $query1 = "SELECT * FROM " . $this->_incident_view . " WHERE " . $where_clause . " LIMIT " . $start . ", " . $this->pagging->limit;
            $stmt1 = $this->db->prepare($query1);
            $stmt1->execute($passValue);
            $allposts = $stmt1->fetchAll($this->fetch_obj);
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
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    public function get_incident_category() {
        $query = "SELECT * FROM " . $this->_incident_category;
        return $this->select($query);
    }

    public function get_nature_injury() {
        $query = "SELECT * FROM " . $this->_nature_of_injury;
        return $this->select($query);
    }

    public function get_body_part_injury($clause,$pvalue) {
        $query = "SELECT * FROM incident_body_part_injury" . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute($pvalue);
        return $resutl->fetchAll();
    }

    public function get_body_part_injury_mappingData($clause,$pvalue) {
        $query = "SELECT * FROM incident_body_part_injury_mapping " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute($pvalue);
        return $resutl->fetchAll();
    }

    public function getarticles() {
        $query = "SELECT  * FROM " . $this->_view . " WHERE status_id = 3 ORDER BY id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    public function get_division_by_activity($clause = "",$pvalue) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM  activity_division_mapping as dp"
                . " LEFT JOIN division_department as dd ON dd.id = dp.division_id WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute($pvalue);
        return $resutl->fetchAll();
    }

    public function get_participants_categories($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM participants_category_master WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    public function get_division_department($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM division_department WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    public function get_division_department_mapping($clause = "", $tb,$pvalue) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM " . $tb . " WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute($pvalue);
        return $resutl->fetchAll();
    }

    public function get_participants_category_mapping($clause = "", $table = Null) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM " . $table . " WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    public function get_activity_participants_mapping($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_participants_mapping WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    public function get_participants_by_activity($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_participants_mapping_view WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    public function get_findings_by_activity($clause = "",$pvalue) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM incident_finding_mapping WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute($pvalue);
        return $resutl->fetchAll();
    }

    public function get_violation_by_activity($clause = "",$pvalue) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM audit_violation_mapping WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute($pvalue);
        return $resutl->fetchAll();
    }

    public function get_linefunction_deviation_by_activity($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM safety_observation_line_function_deviation_mapping WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    public function get_deviation_by_activity($clause = "",$pValue) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM ppe_audit_deviation_mapping WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute($pValue);
        return $resutl->fetchAll();
    }

    public function get_participants_details($activity_id, $participant_cat_id) {
        $query = "SELECT * FROM activity_participants_mapping_view WHERE activity_id= :activity_id AND participant_cat_id = :participant_cat_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":activity_id" => $activity_id,
                    ":participant_cat_id" => $participant_cat_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function get_parti_details($activity_participant_category_id) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_participants_mapping WHERE activity_participant_category_id = :activity_participant_category_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":activity_participant_category_id" => $activity_participant_category_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function get_participants_by_activity_new($clause = "",$pvalue) {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_participant_category_mapping_view WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute($pvalue);
        return $resutl->fetchAll();
    }

    public function get_participants_details_new($activity_participant_category_id) {
        $activity_participant_category_id = ( $activity_participant_category_id == "" ) ? 1 : $activity_participant_category_id;
        $query = "SELECT * FROM activity_participants_mapping WHERE activity_participant_category_id = :activity_participant_category_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":activity_participant_category_id" => $activity_participant_category_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function get_activity_type_master($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_type_master WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
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
            $resutl->execute(array(":id" => $id));
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
            $resutl->execute( array(":id" => $id) );
            return $resutl->fetchAll();
        }
        if ($type == 'mom') {
            if ($id > 0) {
                $query = "SELECT * FROM mom_view as actview "
                        . "LEFT JOIN file_upload_mom_mapping as upd ON upd.mom_id = actview.id "
                        . "WHERE actview.id = :id";
            } else {
                $query = "SELECT pv.*, pv.category_path AS `path` FROM mom_view as pv WHERE pv.status_id = 3 AND pv.category_deleted = 0 ORDER BY pv.modified_date DESC ";
            }

            $resutl = $this->db->prepare($query);
            $resutl->execute(array(":id" => $id));
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
            $resutl->execute(array(":id" => $id));
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
            $resutl->execute(array(":id" => $id));
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
            $resutl->execute(array(":id" => $id));
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
            $resutl->execute(array(":id" => $id));
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
        $query = "SELECT * FROM file_upload_activity_mapping WHERE activity_id = :activity_id  ORDER BY id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":activity_id" => $activity_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function get_audit_uploads($audit_id = 0) {
        if (!$audit_id)
            return array();
        $query = "SELECT * FROM file_upload_audit_mapping WHERE audit_id = :audit_id ORDER BY id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":audit_id" => $audit_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function get_mom_uploads($mom_id = 0) {
        if (!$mom_id)
            return array();
        $query = "SELECT * FROM file_upload_mom_mapping WHERE mom_id = :mom_id ORDER BY id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":mom_id" => $mom_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function get_observation_uploads($audit_id = 0) {
        if (!$audit_id)
            return array();
        $query = "SELECT * FROM file_upload_safety_observation_mapping WHERE line_function_id = :audit_id ORDER BY id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":audit_id" => $audit_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function get_ppe_audit_uploads($audit_id = 0) {
        if (!$audit_id)
            return array();
        $query = "SELECT * FROM file_upload_ppe_audit_mapping WHERE ppe_audit_id = :audit_id ORDER BY id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":audit_id" => $audit_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function get_incident_uploads($incident_id = 0) {
        if (!$incident_id)
            return array();
        $query = "SELECT * FROM file_upload_incident_mapping WHERE incident_id = :incident_id ORDER BY id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":incident_id" => $incident_id,
                    )
                );
        return $resutl->fetchAll();
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
        $query = "SELECT * FROM division_department WHERE parent_id = :parent_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":parent_id" => $parent_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function check_tbvalue($id = 0) {
        if (!$id)
            return array();
        $query = "SELECT * FROM division_department WHERE id = " . $id;
        $rowdata = $this->select($query);
        return $rowdata[0]->table_name;
    }

    public function get_setvalue_mis($tb = '', $id = 0) {
        $cond = "";
//           if($tb == "cset_contractor")
//           {
//               $cond = " GROUP BY name,location,code";
//           }
//           if($tb == "cset_contractor")
//           {
//               $cond = " GROUP BY code";
//           }
        $query = "SELECT * FROM " . $tb . " WHERE division_id = :id " . $cond . " ORDER BY name asc";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":id" => $id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function get_setvalue($tb = '', $id = 0) {
        $cond = "";
//           if($tb == "cset_contractor")
//           {
//               $cond = " GROUP BY name,location,code";
//           }
//           if($tb == "cset_contractor")
//           {
//               $cond = " GROUP BY code";
//           }
        $query = "SELECT * FROM " . $tb . " WHERE division_id = :id " . $cond . " ORDER BY name asc";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":id" => $id,
                    )
                );
        return $resutl->fetchAll();
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
        $query = "SELECT audit_id FROM audit_violation_mapping WHERE audit_id = :audit_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":audit_id" => $audit_id,
                    )
                );
        return $resutl->fetchAll();
    }

    // Incident
    public function updateincidentFindings($incident_id, $val) {
        $query = "UPDATE incident SET investigation_req = 'Y',investigation_done = 'Y', no_of_finding = :val WHERE id = :incident_id ";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":val" => $val,
                    ":incident_id" => $incident_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function updatelinefunctionDeviation($line_function_id, $val) {
        $query = "UPDATE safety_observation_line_function SET major_deviation = 'Y', no_of_deviation = :val WHERE id = :line_function_id ";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":val" => $val,
                    ":line_function_id" => $line_function_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function getincidentFindings($incident_id) {
        $query = "SELECT incident_id FROM incident_finding_mapping WHERE incident_id = :incident_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":incident_id" => $incident_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function getlinefunctionDeviation($line_function_id) {
        $query = "SELECT line_function_id FROM safety_observation_line_function_deviation_mapping WHERE line_function_id = :line_function_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":line_function_id" => $line_function_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function getRelatedFindings($token_id) {
        $query = "SELECT * FROM incident_finding_mapping WHERE token_id = :token_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":token_id" => $token_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function deleteRelatedFindings($token_id) {
        $query = "DELETE FROM incident_finding_mapping WHERE token_id = :token_id AND incident_id = 0";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":token_id" => $token_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function getRelated($token_id) {
        $query = "SELECT * FROM safety_observation_line_function_deviation_mapping WHERE token_id = :token_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":token_id" => $token_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function deleteRelated($token_id) {
        $query = "DELETE FROM safety_observation_line_function_deviation_mapping WHERE token_id = :token_id AND line_function_id = 0";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":token_id" => $token_id,
                    )
                );
        return $resutl->fetchAll();
    }

    // violation
    public function getRelatedViolation($token_id) {

        $query = "SELECT * FROM audit_violation_mapping WHERE token_id = :token_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":token_id" => $token_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function deleteRelatedViolation($token_id) {

        $query = "DELETE FROM audit_violation_mapping WHERE token_id = :token_id AND audit_id = 0";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":token_id" => $token_id,
                    )
                );
        return $resutl->fetchAll();
    }

    // deviation
    public function updateAuditDeviation($ppe_audit_id, $val) {

        $query = "UPDATE ppe_audit SET major_deviation = 'Y', no_of_deviation = :val WHERE id = :ppe_audit_id ";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":val" => $val,
                    ":ppe_audit_id" => $ppe_audit_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function getAuditDeviation($ppe_audit_id) {

        $query = "SELECT ppe_audit_id FROM ppe_audit_deviation_mapping WHERE ppe_audit_id = :ppe_audit_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":ppe_audit_id" => $ppe_audit_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function getRelatedDeviation($token_id) {

        $query = "SELECT * FROM ppe_audit_deviation_mapping WHERE token_id = :token_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":token_id" => $token_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function deleteRelatedDeviation($token_id) {

        $query = "DELETE FROM ppe_audit_deviation_mapping WHERE token_id = :token_id AND ppe_audit_id = 0";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":token_id" => $token_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function getRelatedParticipants($token_id, $participant_cat_id) {

        $query = "SELECT * FROM activity_participants_mapping WHERE token_id = :token_id AND participant_cat_id= :participant_cat_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":token_id" => $token_id,
                    ":participant_cat_id" => $participant_cat_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function deleteRelatedParticipants($token_id, $participant_cat_id) {

        $query = "DELETE FROM activity_participants_mapping WHERE token_id = :token_id AND activity_participant_category_id = 0 AND participant_cat_id= :participant_cat_id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":token_id" => $token_id,
                    ":participant_cat_id" => $participant_cat_id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function updateAuditViolation($audit_id, $val) {

        $query = "UPDATE audit SET major_violation = 'Y',no_of_violation = :val WHERE id = :audit_id ";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":val" => $val,
                    ":audit_id" => $audit_id,
                    )
                );
        return $resutl->fetchAll();
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
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    /**
     * 
     * @param type $id
     * @return boolean
     */
    public function getFrameworkValue($id) {
        if (empty($id))
            return FALSE;
        $query = "SELECT * FROM master_framework_value where framework_id = :id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":id" => $id,
                    )
                );
        return $resutl->fetchAll();
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function getFrameworkById($id = Null) {
        $query = "SELECT * FROM safety_observation_framework_mapping WHERE line_function_id= :id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":id" => $id,
                    )
                );
        return $resutl->fetchAll();
    }

    public function get_contractor_list($clause) {
        $query = "SELECT COUNT(identification_code) as avgcode,identification_code,name,root_division_id FROM cset_contractor " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    /**
     * created by anima
     * reason
     * @param type string
     * @param type string
     * @return type array()
     */
    public function get_DataByDate($table_name, $clause) {
        $query = "SELECT * FROM " . $table_name . " " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    /**
     * created by anima
     * modified by pallab
     * reason 
     * @param type integer
     * @return type array()
     */
    public function get_venue_list($activity_type_id) {
        if ($activity_type_id < 5) {
            $table_name = "activity";
        } else if ($activity_type_id == 5) {
            $table_name = "audit";
        } else if ($activity_type_id == 6) {
            $table_name = "incident";
        } else if ($activity_type_id == 7) {
            $table_name = "ppe_audit";
        } else if ($activity_type_id == 8) {
            $table_name = "safety_observation";
        }
        $query = "SELECT DISTINCT `place` FROM " . $table_name . " WHERE activity_type_id= :activity_type_id AND created_by!='2'";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":activity_type_id" => $activity_type_id,
                    )
                );
        return $resutl->fetchAll();
    }

    /**
     * created by anima
     * modified by pallab
     * @param type integer
     * @return type array()
     */
    public function get_subject_title_list($activity_type_id) {
        $query = "SELECT DISTINCT `subject_details` FROM `activity` WHERE activity_type_id= :activity_type_id AND created_by!='2'";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":activity_type_id" => $activity_type_id,
                    )
                );
        return $resutl->fetchAll();
    }

    /**
     * created by pallab
     * modified by pallab 
     * @param 
     * @return type array()
     */
    public function get_user_details() {
        $query = "SELECT id,audited_by_code,employee_code,full_name,designation FROM `master_users` WHERE status_id ='2' AND id NOT IN(2,140)";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    /**
     * created by pallab
     * modified by pallab 
     * @param 
     * @return type array()
     */
    public function get_allactivity_type_master() {

        $query = "SELECT * FROM activity_type_master WHERE id NOT IN(9,10)";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }
}
