<?php

class MainDao extends DbCon{
	public $_table;
	public $db;
	public $fetch_assoc;
	public $fetch_obj;
	
	private $pdo_error;
	
	public $lastInsertedId = 0;
	public $pagging = array(
		'start' 		=> 0, 
		'limit' 		=> 30, 
		'conditions' 	=> '', 
		'max_row' 		=> 0, 
		'data' 			=> array(), 
		'page' 			=> 1, 
		'sql' 			=> '', 
		'page_type' 	=> ''
	);
	public $page = 1;
	
	public function __construct() {
		parent::__construct();
		$this->pagging = (object)$this->pagging;
		$this->fetch_assoc 	= PDO::FETCH_ASSOC;
		$this->fetch_obj 	= PDO::FETCH_OBJ;
	}
	
	public function save( $modelData = array() ) {
		if( empty( $modelData ) || $this->_table == "" ) return false;
		
		$smtp 	= '';
		$update = 0;
		$insert_query 	= 'INSERT INTO '.$this->_table . '(' . implode( ',', array_keys($modelData) ) . ') 
		VALUES(:'.implode( ',:', array_keys($modelData) ) . ')';
		$update_query = 'UPDATE '.$this->_table.' SET ';

		if( array_key_exists( 'id', $modelData ) ) if( $modelData['id'] > 0 ) $update++;
		if( !$update ) $smtp = $this->db->prepare($insert_query);	
		
		$set_str = '';
		foreach( $modelData as $field => $value ) {
			if( $field == 'id' ) continue;
			$value = ( $value != "" ) ? trim( addslashes( $value ) ) : $value;
			if( $update ) {
				$set_str .= ",".$field."=:".$field;
			} elseif( !$update ) {
				$smtp->bindValue( ':' . $field, $value );
				//$smtp->bindParam( ':' . $field, $value );
			}
		}
		if( $update ) {
			$set_str = trim($set_str, ',');
			$update_query .= $set_str . ' WHERE id = :id';
			$smtp = $this->db->prepare($update_query);
			foreach( $modelData as $field => $value ) {
				$smtp->bindValue( ':' . $field, $value );
			}
		}
		$smtp->execute();
		$this->set_query_error($smtp);
		$returned = 0;
		if( ! $update ) {
			$this->lastInsertedId = $this->db->lastInsertId();
			$returned = $this->lastInsertedId;
		} else {
			$returned = $smtp->rowCount();
		}
		
		return ( $this->pdo_error != "" ) ? false : $returned;
	}
	
	public function select( $query, $data = array(), $single = 0 ) {
		
		$execute = array();
		$query = ($query != "") ? $query : "SELECT * FROM ".$this->_table;
		if( !empty( $data ) ) {
			$i = 0;
			foreach( $data as $field => $val ) {
				$execute[ ":".$field ] = $val;
				if( !$i ) $query .= " WHERE " . $field . " = :".$field;
				if( $i ) $query .= " AND " . $field . " = :".$field;
				$i++;
			}
		}
		$result = $this->db->prepare($query);
		$result->execute($execute);
		$this->set_query_error($result);
		return ( ! $single ) ? $result->fetchAll($this->fetch_obj) : $result->fetch($this->fetch_obj);
	}
	
	public function del( $data = array() ) {
		if( empty( $data ) ) return FALSE;
		
		$delete = 0;
		$query 	= "DELETE FROM ".$this->_table;
		if( !empty( $data ) ) {
			$i = 0;
			foreach( $data as $field => $val ) {
				$execute[ ":".$field ] = $val;
				if( !$i ) $query .= " WHERE " . $field . " = :".$field;
				if( $i ) $query .= " AND " . $field . " = :".$field;
				$i++;
			}
		}
		$result = $this->db->prepare($query);
		
		try {
			$delete = $result->execute($execute);
		} catch(PDOException  $e){
			die( "Error: ".$e->getMessage() );
		}
		return $delete;
	}
	
	public function set_query_error($smtp) {
		$errorInfo = $smtp->errorInfo();
		$this->pdo_error = isset($errorInfo[2]) ? $errorInfo[2] : "";
	}
	
	public function get_query_error() {
		return $this->pdo_error;
	}
	
	public function getUploads( $table_id = 0, $tagtable = '', $id = 0 ) {
		if( !$table_id && $tagtable == '' && !$id ) return array();
		$tagtable = ($tagtable == '') ? $this->_table : $tagtable;
		
		$query = "SELECT * FROM `uploads` WHERE 1";
		if( $tagtable != "" ) $query .= " AND `table_name` = '".$tagtable."'";
		if( $table_id > 0 ) $query .= " AND `table_id` = ".$table_id;
		if( $id > 0 ) $query .= " AND `id` = ".$id;
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
	
	public function get_all_categories() {
		$all_categories = array();
		$all_categories["post_categories"] 		= $this->select( "SELECT * FROM `master_post_categories` WHERE is_active = 1 ORDER BY category_label_1, id" );
		$all_categories["presentation_categories"] 	= $this->select( "SELECT * FROM `master_presentation_categories` WHERE deleted = 0 ORDER BY name, id" );
		$all_categories["magazine_categories"] 		= $this->select( "SELECT * FROM `master_magazine_categories` WHERE deleted = 0 ORDER BY name, id" );
		$all_categories["buyers_guide_categories"] 	= $this->select( "SELECT * FROM `master_buyers_guide_categories` WHERE deleted = 0 ORDER BY name, id" );
		return $all_categories;
	}
        
        public function get_activities($clause = Null) 
            {
            $query = "";
            $single = "";
            if($clause)
            {
                $where_clause = " WHERE ".$clause;
            }
            else {
                $where_clause = "";
            }
            
		$table="activity_type_master";
		$execute = array();
                $query = "SELECT * FROM ".$table.$where_clause;
		$result = $this->db->prepare($query);
		$result->execute($execute);
		$this->set_query_error($result);
		return ( ! $single ) ? $result->fetchAll($this->fetch_obj) : $result->fetch($this->fetch_obj);
            }
            
            public function getEventCategory($clause = Null) 
            {
            $query = "";
            $single = "";
            if($clause)
            {
                $where_clause = " WHERE ".$clause;
            }
            else {
                $where_clause = "";
            }
            $rowFetchData=array();
		$execute = array();
                $executexx = array();
                $query = "SELECT * FROM event_category ".$where_clause;
		$result = $this->db->prepare($query);
		$result->execute($execute);
                $rowFetchData = $result->fetchAll($this->fetch_obj);
                $currentDate = date("Y-m-d");
                foreach( $rowFetchData as $keys => $newRow ) {
                    $queryxx = "SELECT * FROM upcoming_event WHERE category_id='".$newRow->id."' AND date_of_event >= '".$currentDate."' ";
                    $resultxx = $this->db->prepare($queryxx);
		$resultxx->execute($executexx);
                $rowFetchDataxx = $resultxx->fetchAll($this->fetch_obj);
                $countxx= count($rowFetchDataxx);
                $rowFetchData[$keys]->cn = $countxx;
                }
		//$this->set_query_error($result);
		return $rowFetchData;
            }
            
            
    public function getStatisticMasterData($id = null) {
        $clause = "";
        if(!empty($id)) {
            $clause = ' AND id='.$id;
        }
        $query = "SELECT *  FROM statistical_type_master WHERE name!=''".$clause;
        $results = $this->db->prepare($query);
        $results->execute();
        return $results->fetchAll();
    }
    
    
//    public function getStatisticalData($type) {
//         $clause = " WHERE stdata.status='1' AND stdata.statistical_master_id='$type'";
//        $query = " SELECT stdata.*,actestb.name as estbName FROM statistical_data stdata "
//                . "INNER JOIN statistical_activity_establishment actestb ON  actestb.id=stdata.actestb_id " . $clause;
//        $results = $this->db->prepare($query);
//        $results->execute();
//         return $results->fetchAll();
//       
//    }
    public function getStatisticalData($type) {
         $clause = " WHERE statistical_master_id='$type'";
        $query = " SELECT * FROM statistical_activity_establishment " . $clause;
        $results = $this->db->prepare($query);
        $results->execute();
         return $results->fetchAll();
       
    }
    
    public function getGeneralData($divid,$table_name,$cloumn_name,$type,$division_id,$value=null) {
        if($divid == 8){
            if( $type == 1 ) {
            $clause = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) LIKE '$value%'";
            } else if( $type == 2 ) {
                $expdata = explode("~",$value);
                $clause = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) BETWEEN '".$expdata[0]."-01' AND '".$expdata[1]."-31'";
            } else if( $type == 3 ) {
                $exp=explode(",",$value);
                $clause = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
            }
        } else {
            if( $type == 1 ) {
            $clause = " AND $cloumn_name LIKE '$value%'";
            } else if( $type == 2 ) {
                $expdata = explode("~",$value);
                $clause = " AND $cloumn_name BETWEEN '".$expdata[0]."-01' AND '".$expdata[1]."-31'";
            } else if( $type == 3 ) {
                $exp=explode(",",$value);
                $clause = " AND $cloumn_name BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
            }
        }
        
        if($divid == 8){
             $query = " SELECT *,CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) as activity_date,sum(activity_count) as total_activity_count FROM $table_name "
                . " WHERE deleted='0' AND status_id ='3' AND created_by!='2' AND activity_type_id = '$divid' AND tree_division_id LIKE '$division_id%' " . $clause;
                $rowdata = $this->select($query);
                $data = 0;
                if(count($rowdata) > 0 ) {
                    foreach($rowdata as $row) {
                   $data+=$row->total_activity_count;
                    }
                }
                return ($data);
        } else if($divid  == 3){
        $query = " SELECT * FROM $table_name "
                . " WHERE deleted='0' AND status_id ='3' AND created_by!='2' AND activity_type_id = '$divid' AND tree_division_id LIKE '$division_id%' " . $clause;
                
                $rowdata = $this->select($query);
                $data = 0;
                if(count($rowdata) > 0 ) {
                    foreach($rowdata as $row) {
                   $data+=$row->no_of_emp;
                    }
                }
            
                return ($data);
        
        } else {
        $query = " SELECT * FROM $table_name "
                . " WHERE deleted='0' AND status_id ='3' AND created_by!='2' AND activity_type_id = '$divid' AND tree_division_id LIKE '$division_id%' " . $clause;   
            
                    $results = $this->db->prepare($query);
                    $results->execute();
                    return $results->fetchAll();

        }
        
    }
    
//    public function getAuditScoreData($divid,$type,$setype,$value=null) {
//        if( $type == 1 ) {
//            $clause = " AND date_of_audit LIKE '$value%'";
//        } else if( $type == 2 ) {
//            $expdata = explode("~",$value);
//            $clause = " AND date_of_audit BETWEEN '".$expdata[0]."-01' AND '".$expdata[1]."-31'";
//        } else if( $type == 3 ) {
//            $exp=explode(",",$value);
//            $clause = " AND date_of_audit BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
//        }
//        $query = " SELECT * FROM audit_view "
//                . " WHERE deleted='0' AND status_id ='3' AND created_by!='2' AND tree_division_id LIKE '$divid%' AND set_type='$setype'" . $clause;
       // echo '<pre style="color:#000;">'.$query;
        
//        $results = $this->db->prepare($query);
//        $results->execute();
//        $results_data = $results->fetchAll();
//        return $results_data;
//        show($results);
        
//    }
    public function getAuditScoreData($divid,$type,$setype,$value=null) {
        
        $expdiv = explode("-",$divid);
        if( $type == 1 ) {
            $clause = " AND date_of_audit LIKE '$value%'";
        } else if( $type == 2 ) {
            $expdata = explode("~",$value);
            $clause = " AND date_of_audit BETWEEN '".$expdata[0]."-01' AND '".$expdata[1]."-31'";
        } else if( $type == 3 ) {
            $exp=explode(",",$value);
            $clause = " AND date_of_audit BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        $query = " SELECT * FROM actualtarget_audit_view "
                . " WHERE deleted='0' AND status_id ='3' AND created_by!='2' AND  set_type='$setype'" . $clause;
        
        $rowdata = $this->select($query);
        $totaldiv = 0;
        $totScore = 0;
        $rowdata = $this->select($query);
        if( !empty($rowdata) ) {
           
            foreach( $rowdata as $row ) {
              $nm="";
              $tree_division_id_arr = ($row->tree_division_id != "") ? explode("-", $row->tree_division_id) : array();  
                for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                    if ($tree_division_id_arr[$i] == "")
                        continue;
                    if (is_numeric($tree_division_id_arr[$i])) { 
                            $nm.= $tree_division_id_arr[$i]."-";
                        }
                   }
                   $score = $row->avg_mark ? $row->avg_mark : 0;
                   $asd=substr($nm,'0','-1');
                   $exp=explode("-",$asd);
                  if($exp[2] == 181 || $exp[2] == 183 || $exp[2] == 6 || $exp[2] == 7 || $exp[2] == 8 || $exp[2] == 168){
                   @$newval = $exp[2];
                  }else{
                   @$newval = $exp[3];
                  }
                   if(is_array($expdiv)) {
                   if(in_array($newval, $expdiv)) {
                      $totaldiv += 1;
                      $totScore = $totScore + $score;
                   } } else {
                     if($newval == $treeid) {
                      $totaldiv += 1;
                      $totScore = $totScore + $score;
                   }  
                   }
            }
        }
        if($totScore != 0 || $totaldiv != 0){
          $sScore = $totScore/$totaldiv;
        }else{ $sScore = 0; }
        
       
        $ReturnData["totaldiv"] = $totaldiv;
        $ReturnData["score"]= $sScore;
        return $ReturnData; 
        
    }

    public function getIncidentCategoryData($setype,$divid,$type,$inccategory,$value=null) {
        if( $type == 1 ) {
            $clause = " AND date_of_incident LIKE '$value%'";
        } else if( $type == 2 ) {
            $expdata = explode("~",$value);
            $clause = " AND date_of_incident BETWEEN '".$expdata[0]."-01' AND '".$expdata[1]."-31'";
        } else if( $type == 3 ) {
            $exp=explode(",",$value);
            $clause = " AND date_of_incident BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        $query = " SELECT * FROM incident_view "
                . " WHERE deleted='0' AND status_id ='3' AND created_by!='2' AND set_type = '$setype' AND tree_division_id LIKE '$divid%' AND incident_category_id='$inccategory'" . $clause;
        $results = $this->db->prepare($query);
        $results->execute();
        return $results->fetchAll();
    }
    
    
    public function checkActivityDataExist($statid,$month_year) {
        if($statid == 1) {
        $query = "SELECT activity_date as searchdate FROM activity_view WHERE activity_type_id IN (1,2,3) AND status_id ='3' AND deleted='0' AND created_by!='2' AND activity_date LIKE '$month_year%'"
                . " UNION ALL SELECT date_of_audit as searchdate FROM audit_view WHERE deleted='0' AND status_id ='3' AND created_by!='2' AND date_of_audit LIKE '$month_year%'";
        } else if($statid == 2) {
            $query = "SELECT date_of_audit as searchdate FROM audit_view WHERE deleted='0' AND status_id ='3' AND created_by!='2' AND date_of_audit LIKE '$month_year%'";
        } else if($statid == 3) {
            $query = "SELECT date_of_incident as searchdate,tree_division_id FROM incident_view WHERE deleted='0' AND status_id ='3' AND created_by!='2' AND date_of_incident LIKE '$month_year%'";
        }
        $results = $this->db->prepare($query);
        $results->execute();
        return $results->rowCount();
    }
   
    public function getFatalCount($month_year) {
        $query = "SELECT date_of_incident as searchdate,tree_division_id,incident_category_id FROM incident_view WHERE incident_category_id='4' AND deleted='0' AND status_id ='3' AND created_by!='2' AND date_of_incident LIKE '$month_year%'";
        $results = $this->db->prepare($query);
        $results->execute();
        return $results->rowCount();
    }
   
    
            
          
}
