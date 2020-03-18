<?php 
class DashboardDAO extends MainDao{
	public $_table 	= "activity_type_master";
	public $_view 	= "master_posts_view";
	
	public function __construct() {
		parent::__construct();
	}
	

	public function get_activity_type_master($clause = "") {
		$clause = ( $clause == "" ) ? 1 : $clause;
		$query = "SELECT * FROM  ".$this->_table." WHERE ".$clause;
		return $this->select($query);
	}
        
        public function get_all_wrong_enrty($activity_type_id) {
            if( $activity_type_id < 5 ) {
                $query = "  SELECT 
                            activity.id,
                            actdivmap.activity_id as masterid,
                            activity.activity_type_id,
                            acttypmast.activity_name,
                            actdivmap.tree_division_id,
                            actdivmap.division_id,
                            activity.activity_no,
                            activity.activity_date,
                            activity.set_type,
                            activity.from_date,
                            activity.to_date,
                            activity.status_id,
                            activity.deleted,
                            posts_status.status_name AS status_name, 
                            posts_status.detail AS status_public_name,
                            activity.created_by,
                            activity.created_date,
                            musr.role_id,
                            musr.full_name AS created_by_name
                            from activity
                            left join activity_type_master acttypmast on acttypmast.id = activity.activity_type_id
                            left join master_users musr on musr.id = activity.created_by
                            left join master_post_status posts_status on posts_status.id = activity.status_id
                            left join activity_division_mapping actdivmap on actdivmap.activity_id = activity.id
                            WHERE activity.created_by!='2' AND activity.deleted='0' 
                            AND activity.activity_type_id=".$activity_type_id." AND actdivmap.activity_id IS NULL";
                $result = $this->select($query);
            }
            else if( $activity_type_id == 5) {
                $query = "SELECT 
                            audit.id,
                            auditdivmap.audit_id as masterid,
                            audit.activity_type_id,
                            acttypmast.activity_name,
                            auditdivmap.tree_division_id,
                            auditdivmap.division_id,
                            audit.set_type,
                            audit.audit_no,
                            audit.avg_mark,
                            audit.date_of_audit,
                            audit.status_id,
                            audit.deleted,
                            posts_status.status_name AS status_name, 
                            posts_status.detail AS status_public_name,
                            audit.created_by,
                            audit.created_date,
                            musr.role_id,
                            musr.full_name AS created_by_name
                            from audit
                            left join activity_type_master acttypmast on acttypmast.id = audit.activity_type_id
                            left join master_users musr on musr.id = audit.created_by
                            left join master_post_status posts_status on posts_status.id = audit.status_id
                            left join audit_division_mapping auditdivmap on auditdivmap.audit_id = audit.id
                            WHERE audit.created_by!='2' AND audit.deleted='0' AND audit.activity_type_id=".$activity_type_id." AND auditdivmap.audit_id IS NULL  GROUP BY auditdivmap.audit_id";
                $result = $this->select($query);
                
            }
            else if( $activity_type_id == 6) {
                $query = "SELECT 
                            incident.id,
                            incidentdivmap.incident_id as masterid,
                            incident.activity_type_id,
                            acttypmast.activity_name,
                            incidentdivmap.tree_division_id,
                            incidentdivmap.division_id,
                            incident.incident_no,
                            incident.incident_category_id,
                            incident.set_type,
                            incident.date_of_incident,
                            incident.status_id,
                            incident.deleted,
                            posts_status.status_name AS status_name, 
                            posts_status.detail AS status_public_name,
                            incident.created_by,
                            incident.created_date,
                            musr.role_id,
                            musr.full_name AS created_by_name
                            from incident
                            left join activity_type_master acttypmast on acttypmast.id = incident.activity_type_id
                            left join master_users musr on musr.id = incident.created_by
                            left join master_post_status posts_status on posts_status.id = incident.status_id
                            left join incident_division_mapping incidentdivmap on incidentdivmap.incident_id = incident.id
                            WHERE  incident.created_by!='2' AND incident.deleted='0' 
                            AND incident.activity_type_id=".$activity_type_id." AND incidentdivmap.incident_id IS NULL  GROUP BY incidentdivmap.incident_id";
                $result = $this->select($query);
                
            }
            else if( $activity_type_id == 7) {
                $query="SELECT 
                        ppe_audit.id,
                        ppeauditdivmap.ppe_audit_id as masterid,
                        ppe_audit.activity_type_id,
                        acttypmast.activity_name,
                        ppeauditdivmap.tree_division_id,
                        ppeauditdivmap.division_id,
                        ppe_audit.audit_no,
                        ppe_audit.date_of_audit,
                        ppe_audit.status_id,
                        ppe_audit.deleted,
                        posts_status.status_name AS status_name, 
                        posts_status.detail AS status_public_name,
                        ppe_audit.created_by,
                        ppe_audit.created_date,
                        musr.role_id,
                        musr.full_name AS created_by_name
                        from ppe_audit
                        left join activity_type_master acttypmast on acttypmast.id = ppe_audit.activity_type_id
                        left join master_users musr on musr.id = ppe_audit.created_by
                        left join master_post_status posts_status on posts_status.id = ppe_audit.status_id
                        left join ppe_audit_division_mapping ppeauditdivmap on ppeauditdivmap.ppe_audit_id = ppe_audit.id
                        WHERE  ppe_audit.created_by!='2' AND ppe_audit.deleted='0' 
                        AND ppe_audit.activity_type_id=".$activity_type_id." AND ppeauditdivmap.ppe_audit_id IS NULL  GROUP BY ppeauditdivmap.ppe_audit_id";
                 $result = $this->select($query);
            }
            else if( $activity_type_id == 8) {
                $query ="SELECT 
                            safety_observation.id, 
                            safetyobsdivmap.safety_observation_id as masterid,
                            safety_observation.activity_type_id, 
                            acttypmast.activity_name,
                            safetyobsdivmap.tree_division_id,
                            safetyobsdivmap.division_id,
                            safety_observation.activity_no, 
                            safety_observation.activity_month, 
                            safety_observation.activity_year, 
                            safety_observation.activity_count, 
                            safety_observation.place, 
                            safety_observation.status_id,
                            safety_observation.deleted,
                            posts_status.status_name AS status_name, 
                            posts_status.detail AS status_public_name,
                            safety_observation.created_by,
                            safety_observation.created_date,
                            musr.role_id,
                            musr.full_name AS created_by_name
                            FROM safety_observation 
                            LEFT JOIN activity_type_master AS acttypmast ON acttypmast.id=safety_observation.activity_type_id
                            LEFT JOIN master_users AS musr ON musr.id=safety_observation.created_by
                            LEFT JOIN master_post_status AS posts_status ON posts_status.id = safety_observation.status_id  
                            LEFT JOIN safety_observation_division_mapping safetyobsdivmap on safetyobsdivmap.safety_observation_id = safety_observation.id
                            WHERE safety_observation.activity_type_id=8 AND  safety_observation.created_by!='2' AND safety_observation.deleted='0' AND  safetyobsdivmap.safety_observation_id IS NULL  GROUP BY safetyobsdivmap.safety_observation_id";
            $result = $this->select($query);
            }
            else {
                $result = array();
            }
            return $result;
        }
	
	public function get_all_wrong_entry_dates($activity_type_id) {
            if( $activity_type_id == 1 || $activity_type_id == 2 || $activity_type_id == 4) {
                $query="select * from activity_view where activity_type_id='$activity_type_id' AND deleted='0' AND created_by!='2' AND activity_date='0000-00-00'";
                $result = $this->select($query);
            } else if( $activity_type_id == 3) {
                $query="select * from activity_view where activity_type_id='$activity_type_id' AND deleted='0' AND created_by!='2' AND (from_date='0000-00-00' OR to_date='0000-00-00')";
                $result = $this->select($query);
            } else if( $activity_type_id == 5 ) {
                $query="select * from audit_view where activity_type_id='$activity_type_id' AND deleted='0' AND created_by!='2' AND date_of_audit='0000-00-00'";
                $result = $this->select($query);
            
            } else if( $activity_type_id == 6 ) {
                $query="select * from incident_view where activity_type_id='$activity_type_id' AND deleted='0' AND created_by!='2' AND date_of_incident='0000-00-00'";
                $result = $this->select($query);
           
            } else if( $activity_type_id == 7 ) {
                $query="select * from ppe_audit_view where activity_type_id='$activity_type_id' AND deleted='0' AND created_by!='2' AND date_of_audit='0000-00-00'";
                $result = $this->select($query);
            
            } else {
                $result = array();
            }
             return $result;
        
        }
        
         public function get_division_department($clause = "") {
        $clause = ( $clause == "" ) ? 1 : $clause;
        $query = "SELECT * FROM division_department WHERE " . $clause;
        return $this->select($query);
    }
        public function get_all_tree_set($activity_type_id,$task = null,$condition=null) {
          
            if( !empty( $task ) ) {
                $exp = explode(",",$task);
                $clause = " AND set_type='$exp[1]'";
            }
            
             if( $activity_type_id == 1 || $activity_type_id == 2 || $activity_type_id == 4) {
                $query="select * from actualtarget_view where activity_type_id='$activity_type_id' AND deleted='0' AND created_by!='2'".$clause;
                $result = $this->select($query);
            } else if( $activity_type_id == 3) {
                $query="select * from actualtarget_view where activity_type_id='$activity_type_id' AND deleted='0' AND created_by!='2'".$clause;
                $result = $this->select($query);
            } else if( $activity_type_id == 5 ) {
                $query="select * from actualtarget_audit_view where activity_type_id='$activity_type_id' AND deleted='0' AND created_by!='2'".$clause;
                $result = $this->select($query);
            
            } else if( $activity_type_id == 6 ) {
                $query="select * from actualtarget_incident_view where activity_type_id='$activity_type_id' AND deleted='0' AND created_by!='2'".$clause;
                $result = $this->select($query);
           
            } else if( $activity_type_id == 7 ) {
                $query="select * from actualtarget_ppeaudit_view where activity_type_id='$activity_type_id' AND deleted='0' AND created_by!='2'".$clause;
                $result = $this->select($query);
            
            } else if( $activity_type_id == 8 ) {
                $query="select * from actualtarget_safetyobs_view where activity_type_id='$activity_type_id' AND deleted='0' AND created_by!='2'".$clause;
                $result = $this->select($query);
            } else {
                $result = array();
            }
            $data = array();
            
            if(count($result) > 0) {
                
                $post_division_department = $this->get_division_department();
               
            foreach ($result as $key => $value) {
                
               $data[] = $value;
               
               $division_department_mapping = $value->tree_division_id;
                if($division_department_mapping != '') {
                        $tree_division_id_arr = ($division_department_mapping != "") ? explode("-", $division_department_mapping) : array();

                        $divition = '';
                        if (!empty($tree_division_id_arr)) {

                            for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                                if ($tree_division_id_arr[$i] == "")
                                    continue;


                                if (is_numeric($tree_division_id_arr[$i])) {
                                    $divition .= get_value_by_id($tree_division_id_arr[$i], "name", $post_division_department);
                                } else {
                                    list($table_name, $table_id, $field_name) = explode(":", $tree_division_id_arr[$i]);
                                    $query = "SELECT name FROM " . $table_name;
                                    $rowdata = $this->select($query, array($field_name => $table_id), true);
                                    $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                                }
                                $divition .= '/';
                            }
                        }
                        $devition_name[$value->id] = trim($divition, "/");
                }
                $data[$key]->tree_name = $devition_name[$value->id];
                $a = $devition_name[$value->id];
                if($condition == 8 || $condition == 9 || $condition == 7)
                {
                $searchval = $exp[1];
                    if (strpos($a, $searchval) == false) {
                        $data[$key]->mismatch = 1;
                    } else  {
                      $data[$key]->mismatch = 0;  
                    }
                }
                else 
                {
                 $searchval = $exp[0];
                    if (strpos($a, $searchval) !== false) {
                       $data[$key]->mismatch = 1;
                   } else  {
                     $data[$key]->mismatch = 0;  
                   }
                }
                
               
               
            }
            }
            return $data;
        }
}


