<?php

class ActivityNewDAO extends MainDao {

    public $_table = "activity";

    public function __construct() {
        parent::__construct();
    }
//    ********************************************pallab start****************************************
    public function get_categories($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM master_post_categories WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
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
    public function get_activity_type_master($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM activity_type_master WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }
    public function insert_participants($query = null) {
        $query = "";
        if (!$query)
            return array();
        return $this->select($query);
    }
    public function get_user_details() {
        $query = "SELECT id,audited_by_code,employee_code,full_name,designation FROM `master_users` WHERE status_id ='2' AND id NOT IN(2,140)";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }
    public function get_nodal_officer() {
        $query = "SELECT id,audited_by_code,employee_code,full_name,designation FROM `master_users` WHERE status_id ='2' AND is_nodal_officer ='1' AND id NOT IN(2,140)";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }
    public function getScoreDeviationNodalDetails() {
        $query = "SELECT * FROM view_score_division_nodal_mapping WHERE is_nodal_officer ='1'";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }
    public function get_division_department_mapping($clause = "", $tb,$pvalue) {//show($clause);
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM " . $tb . " WHERE " . $clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute($pvalue);
        return $resutl->fetchAll();
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
//    **********************************************pallab end*******************************************************
//    **********************************************santosh start*******************************************************
    /**
     * Modified by santosh
     * @param type $where_clause
     * @param type $limit
     * @param type $auth_user
     * @param type $nolimit
     * @param type $passValue
     * @return type
     */
    
    public function getGroupIdByValue() {
        $data = array();
        $query = "SELECT * FROM master_question_set_group";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        foreach($resutl->fetchAll() as $key => $val){
            $data[$val->id] = $val;
        }
        return $data;
    }
    
    public function getOptinCatIdByname() {
        $data = array();
        $query = "SELECT * FROM master_option_category";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        foreach($resutl->fetchAll() as $key => $val){
            $data[$val->id] = $val->name;
        }
        return $data;
    }
    
    public function getOptinIdByValue() {
        $data = array();
        $query = "SELECT * FROM master_option_value";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        foreach($resutl->fetchAll() as $key => $val){
            $data[$val->option_cat_id][] = $val;
        }
        return $data;
    }
    
    public function update_audit_participant($activityid,$user_id){
        $query = "UPDATE audit_participant_mapping SET `audit_id` = :audit_id WHERE `created_by` = :userid AND audit_id=0";
        $result = $this->db->prepare($query);
        $result->execute(array(":audit_id" => $activityid, ":userid" => $user_id));
        return $result->rowCount();
    }
    //    **********************************************santosh end*******************************************************
    public function authUserAuditId($get_auth_user,$status_id){
        $clause = " WHERE created_by= :created_by AND status_id= :status_id ORDER BY `id` DESC LIMIT 1";
        $query = "SELECT id FROM audit " . $clause;
        $result = $this->db->prepare($query);
        $result->execute(array(":created_by" => $get_auth_user, ":status_id" => $status_id));
        $resultData = $result->fetchAll();
        return $resultData[0];
    }
    
    
    /* DEVIATION PART (NIRMALENDU KHAN) */
    public function devSlNoGen($typeid,$auditid,$devId){
        //$sensarray[':audit_id']=$auditid;
        $sensarray[':type_id']=$typeid;
//        $clause = " WHERE audit_id= :audit_id AND deviation_type_id= :type_id";
        $clause = " WHERE deviation_type_id= :type_id";
        if($devId!=''){
         $clause.=" AND id=:devId";
         $sensarray[':devId']=$devId;
        }
//        $clause.=" ORDER BY `type_sl_no` DESC LIMIT 1";
        $clause.=" ORDER BY `id` DESC LIMIT 1";
        $query = "SELECT deviation_no FROM deviation_details " . $clause;        
        $result = $this->db->prepare($query);
        $result->execute($sensarray);
        $resultData = $result->fetchAll();
        //show($resultData);
        return $resultData;
    }
    
    public function delscoresheet($audit_id){
        $delete = 0;
        $query = "DELETE FROM audit_scoresheet_detailed WHERE audit_id= :audit_id";        
        $result = $this->db->prepare($query);
        try {
            $delete = $result->execute(array(":audit_id" => $audit_id));
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
        return $delete;
    }   
    /* DEVIATION PART (NIRMALENDU KHAN) */
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
    
    /* MERGED GROUP PART (NIRMALENDU KHAN) */
    public function delmergedgroupscrore($audit_id){
        $delete = 0;
        $query = "DELETE FROM marged_group_score_details WHERE audit_id= :audit_id";        
        $result = $this->db->prepare($query);
        try {
            $delete = $result->execute(array(":audit_id" => $audit_id));
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
        return $delete;
    }
    /* MERGED GROUP PART (NIRMALENDU KHAN) */

    public function getAuditPartiGrpCount($audit_id){       

        $query = "SELECT participant_category_id,audit_id,COUNT(id) AS patinum FROM audit_participant_mapping WHERE audit_id= :audit_id AND partcipant_status ='present' AND is_deleted = 0 GROUP BY participant_category_id";
        $result = $this->db->prepare($query);
        $result->execute(array(":audit_id" => $audit_id));
        $resultData = $result->fetchAll();
        return $resultData;
    }
}
