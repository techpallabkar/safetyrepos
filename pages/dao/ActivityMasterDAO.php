<?php
class ActivityMasterDAO extends MainDao{
	public $_table 	= "activity";
	public $_view 	= "activity_view";
	public $_category = "master_presentation_categories";
	
	public function __construct() {
		parent::__construct();
	}
        
        public function getGalleryCategory($where_clause = 1) {
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $query = "SELECT * FROM gallery_category WHERE " . $where_clause." ORDER BY id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }
        public function getGalleryData($cat_id) {
        $query = "SELECT * FROM gallery_view WHERE category_id = :cat_id ORDER BY id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute(array(":cat_id" => $cat_id));
        return $resutl->fetchAll();
    }
    
     public function getUpcomingEvents($clause,$passValue) {
        $query = "SELECT * FROM event_view WHERE $clause  ORDER by date_of_event DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute($passValue);
        return $resutl->fetchAll();
     }
     public function get_total_record_sfobs_monthyear($month,$year) {
        $query = "SELECT * FROM actualtarget_safetyobs_view WHERE activity_month = :activity_month AND status_id ='3' AND activity_year= :activity_year AND created_by!='2' AND deleted='0'  ORDER by id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    "activity_month"=>$month,
                    "activity_year"=>$year,
                    )
                );
        return $resutl->fetchAll(); 
     }
     
	public function get_presentation_with_pagging($where_clause = 1, $limit = 5, $auth_user = array()) {
                $where_clause = "";
		$this->pagging->page  	= 1;
		
		if( $this->pagging->page_type == "presentations" ) {
			$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
		}
		$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;
		$where_clause 			= ( $where_clause == "" ) ? 1 : $where_clause;
		$start 					= ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;

		$query = "SELECT COUNT(id) AS maxrow FROM `".$this->_view."` WHERE ".$where_clause;
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		$row = $resutl->fetch($this->fetch_obj);
		$this->pagging->max_row = count($row) ? $row->maxrow : 0;
		$rows = array();
		if( $this->pagging->max_row ) {
			$query = "SELECT * FROM ".$this->_view." WHERE ".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
			$rows = $this->select($query);
		}
		
		$this->pagging->data = $rows;
		return $rows;
	
        }
        
        public function getarticles() {
            $query = "SELECT  * FROM ".$this->_view." WHERE status_id = 3 ORDER BY id DESC LIMIT 3";
            $resutl = $this->db->prepare($query);
          
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function get_categories($clause = "") {
		$clause = ( $clause == "" ) ? 1 : $clause;
		$query = "SELECT * FROM master_presentation_categories WHERE ".$clause." AND deleted = 0";
		$resutl = $this->db->prepare($query);
                $resutl->execute();
                return $resutl->fetchAll();
	}
	
          public function get_activity_type_master($clause = "") {
		$clause = ( $clause == "" ) ? 1 : $clause;
		$query = "SELECT * FROM activity_type_master WHERE ".$clause;
		$resutl = $this->db->prepare($query);
                $resutl->execute();
                return $resutl->fetchAll();
	}
        
         public function get_division_by_activity($clause = "") {
		$clause = ( $clause == "" ) ? 1 : $clause;
		$query = "SELECT * FROM  activity_division_mapping as dp"
                         . " LEFT JOIN division_department as dd ON dd.id = dp.division_id WHERE ".$clause;
		$resutl = $this->db->prepare($query);
                $resutl->execute();
                return $resutl->fetchAll();
	}
        public function get_participants_by_activity($clause = "") {
		$clause = ( $clause == "" ) ? 1 : $clause;
		$query = "SELECT * FROM activity_participant_category_mapping_view WHERE ".$clause;
		$resutl = $this->db->prepare($query);
                $resutl->execute();
                return $resutl->fetchAll();
	}
         public function get_incident_category() {
            $query = "SELECT * FROM master_incident_category";
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll();
        }
          public function get_nature_injury() {
            $query = "SELECT * FROM master_nature_of_injury";
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll();
        }
        
         public function get_body_part_injury($clause) {
            $query = "SELECT * FROM incident_body_part_injury".$clause;
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll();
        }
         public function get_body_part_injury_mappingData($clause) {
            $query = "SELECT * FROM incident_body_part_injury_mapping ".$clause;
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll();
        }
            public function get_findings_by_activity($clause = "") {
            $clause = ( $clause == "" ) ? 1 : $clause;
            $query = "SELECT * FROM incident_finding_mapping WHERE ".$clause;
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll();
        }
        
        public function get_participants_details($activity_participant_category_id) {
                $clause = "";
		$clause = ( $clause == "" ) ? 1 : $clause;
		$query = "SELECT * FROM activity_participants_mapping WHERE activity_participant_category_id = :id";
		$resutl = $this->db->prepare($query);
                $resutl->execute(
                        array(
                            ":id" => $activity_participant_category_id,
                        )
                        );
                return $resutl->fetchAll();
	}
	public function getactivity($id = 0, $subid = 0) {
		if($id > 0) {
                    $query = "SELECT * FROM ".$this->_view." as actview "
                            . "LEFT JOIN file_upload_activity_mapping as upd ON upd.activity_id = actview.id "
                            . "WHERE actview.id = :id";
		}
		else 
                    {
			$query = "SELECT pv.*, pv.category_path AS `path` FROM ".$this->_view." as pv WHERE pv.status_id = 3 AND pv.category_deleted = 0 ORDER BY pv.modified_date DESC ";
                    }

		$resutl = $this->db->prepare($query);
		$resutl->execute(
                        array(
                            ":id" => $id,
                            )
                        );
		return $resutl->fetchAll();
	}
	public function getactivitys($id, $subid = 0,$table,$joinid,$maping) {
            if($maping == "" ) {
                    $query = "SELECT * FROM $table as actview WHERE actview.id = :id";
                   
		}
		else if($id > 0) {
                    $query = "SELECT * FROM $table as actview "
                                . "LEFT JOIN $maping as upd ON upd.$joinid = actview.id "
                            . "WHERE actview.id = :id";
                   
		}
		else 
                    {
			$query = "SELECT pv.*, pv.category_path AS `path` FROM $table as pv WHERE pv.status_id = 3 AND pv.category_deleted = 0 ORDER BY pv.modified_date DESC ";
                    }
		$resutl = $this->db->prepare($query);
		$resutl->execute(
                        array(
                            ":id" => $id,
                            )
                        );
		return $resutl->fetchAll();
	}
	 public function checkSafObsDataExist($param) {
             $query = "SELECT * "
                   . " FROM safety_observation_view  "
                   . "WHERE CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) LIKE '$param%' AND  status_id=3 AND created_by!='2' "
                   . "AND deleted='0' AND activity_year!='0000' AND activity_month!=0 "; 
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll(); 
        }
	public function get_activity_by_actype_id($category_id = 0, $limit = 5,$table,$coldate) {
            $where_clause = "";
		$this->pagging->page  	= 1;
		if( $this->pagging->page_type == "activities" ) {
			$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
		}
		$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;                
               
		$start = ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;
                /****************/
                $mid_date_of_month=date('Y-m-15');
                $mid_date_next_month=date('Y-m-d',strtotime('+1 Month',strtotime($mid_date_of_month)));
                $current_date=date('Y-m-d');
                if(($current_date >= $mid_date_of_month) && ($current_date < $mid_date_next_month))
                {
                    $month_year_1=date('Y-m',strtotime('-1 Month',strtotime($current_date)));
                } else {

                    $month_year_1=date('Y-m',strtotime('-2 Month',strtotime($current_date)));
                }
                $month_year_2   =   $month_year_1;    

                $checkdataexist =   $this->checkSafObsDataExist($month_year_2);
                $month_year_2_back   =  date( 'Y-m',strtotime( '-2 Month', strtotime( $current_date ) ) );
                $month_year          =  ($checkdataexist != 0 ) ? $month_year_2 : $month_year_2_back ;
                /****************/
                if($category_id == 8) {
                    $query ="SELECT COUNT(id) as maxrow FROM (SELECT * FROM `".$table."` 
                        WHERE CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) <= '$month_year' AND activity_type_id=".$category_id." AND status_id = 3 AND activity_year!='0000' AND activity_month!=0
                        GROUP BY activity_month,activity_year 
                        ORDER BY id DESC) AS asd";
                } else {
                    $query = "SELECT COUNT(id) AS maxrow FROM `".$table."`";
                }
                        
                    
		
		if( $category_id ) {
                    $grpby = "";
                    if($category_id == 8) {
                     $grpby = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) <= '$month_year' AND activity_year!='0000' AND activity_month!=0 GROUP BY activity_month,activity_year ";   
                    }
                    $where_clause .= " WHERE activity_type_id=".$category_id." AND status_id = 3 AND created_by!='2' AND deleted='0'  ".$grpby."  ORDER BY ".(($category_id == 8) ? "CONCAT((activity_year),'-', (lpad(activity_month,2,'0')))" : $coldate)." DESC";
		}
		if($category_id != 8) {
		$query .= $where_clause;
                }
		$result = $this->db->prepare($query);
		$result->execute();
		$row = $result->fetch($this->fetch_obj);
		$this->pagging->max_row = count($row) ? $row->maxrow : 0;
		$rows = array();
		if( $this->pagging->max_row ) {
                    if($category_id == 8) {
                        $query = "SELECT id,created_by,deleted,activity_type_id,set_type,activity_month,activity_year,SUM(activity_count) activity_count,place,status_name"
                             . " FROM `".$table."`".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
                    } else {
                        $query 	= "SELECT * FROM `".$table."`".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
                        
                    }
                    $rows 	= $this->select($query);
		}
		return $rows;
	}
	public function get_activity_view_by_actype_id($category_id = 0, $limit = 5,$table) {
                $where_clause = "";
		$this->pagging->page  	= 1;
		if( $this->pagging->page_type == "activities" ) {
			$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
		}
		$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;
		$start 					= ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;
		
		$query = "SELECT COUNT(id) AS maxrow FROM `".$table."`";
		if( $category_id ) {
                  $where_clause .= "WHERE activity_type_id=".$category_id." AND status_id = 3 ORDER BY id DESC";
		}
		
		$query .= $where_clause;
		
		$result = $this->db->prepare($query);
		$result->execute();
		$row = $result->fetch($this->fetch_obj);
		$this->pagging->max_row = count($row) ? $row->maxrow : 0;
		$rows = array();
		if( $this->pagging->max_row ) {
			$query 	= "SELECT * FROM `".$table."`".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
                        
			$rows 	= $this->select($query);
		}
                
		return $rows;
	}
	
	public function get_presentation( $id = 0 ) {
		if( ! $id ) return array();
		$rows = $this->select( "SELECT * FROM ".$this->_view, array( "id" => $id ), 1 );
		return ( count($rows) > 0 ) ? $rows : array();
	}
         public function get_division_department($clause = "") {
		$clause = ( $clause == "" ) ? 1 : $clause;
		$query = "SELECT * FROM division_department WHERE ".$clause;
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
        
        public function get_division_department_mapping($clause = "",$tb) {
		$clause = ( $clause == "" ) ? 1 : $clause;
		$query = "SELECT * FROM ".$tb." WHERE ".$clause;
                $resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
        
         public function get_violation_by_activity($clause = "") {
            $clause = ( $clause == "" ) ? 1 : $clause;
            $query = "SELECT * FROM audit_violation_mapping WHERE ".$clause;
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll();
        }
        
          public function get_deviation_by_activity($clause = "") {
            $clause = ( $clause == "" ) ? 1 : $clause;
            $query = "SELECT * FROM ppe_audit_deviation_mapping WHERE ".$clause;
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll();
        }
}


