<?php 

class MisDAO extends MainDao {
    public function __construct() {
        parent::__construct();
    }
    
    public function get_remarks_mcm_id($majract,$rowid) {
        $query = " SELECT * FROM mis_mcmreport_mapping WHERE major_activity_id= :majract AND mcmreport_id= :rowid AND type='6'";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":majract" => $majract,
                    ":rowid" => $rowid,
                    )
                );
        return $resutl->fetchAll();   
    }
    
    public function get_remarks_by_id($majract,$rowid) {
        $query = " SELECT * FROM mis_mdreport_mapping WHERE major_activity_id= :majract AND mdreport_id= :rowid AND type='2'";
        $resutl = $this->db->prepare($query);
        $resutl->execute(
                array(
                    ":majract" => $majract,
                    ":rowid" => $rowid,
                    )
                );
        return $resutl->fetchAll();  
    }
    public function getMajorActivity($type=null) {
        $clause = ($type!="") ? " type=".$type : 1;
        $query = " SELECT * FROM mis_major_activities WHERE ".$clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();        
    }
    public function getMajorItem($type=null) {
        $clause = ($type!="") ? " type=".$type : 9;
        $query = " SELECT * FROM mis_major_activities WHERE ".$clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();        
    }
    public function checkexistmdreport($postValue) {
        $query = "SELECT * FROM mis_mdreport WHERE month_year LIKE :month_year ";
        $resutl = $this->db->prepare($query);
        $resutl->execute($postValue);
        return $resutl->fetchAll();    

    }
    public function checkexistmrmdistribution($month_year) {
        $query = " SELECT * FROM mis_mrmdistribution WHERE month_year LIKE '%".$month_year."%'";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();         
    }
    public function checkexistmcmreport($postValue) {
        $query = " SELECT * FROM mis_mcmreport WHERE month_year LIKE :month_year";
        $resutl = $this->db->prepare($query);
        $resutl->execute($postValue);
        return $resutl->fetchAll();         
    }
    public function fetchallmcmreport($id) {
        $query = " SELECT * FROM mis_mcmreport_mapping WHERE mcmreport_id= :id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(array(":id" => $id));
        return $resutl->fetchAll();        
    }
    public function fetchallmdreport($id) {
        $query = " SELECT * FROM mis_mdreport_mapping WHERE mdreport_id= :id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(array(":id" => $id));
        return $resutl->fetchAll();        
    }
    public function fetchallmrmdistribution($id) {
        $query = " SELECT * FROM mis_mrmdistribution_mapping WHERE mrmreport_id= :id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(array(":id" => $id));
        return $resutl->fetchAll();        
    }
    public function getmrmreport() {
        $query = " SELECT * FROM mis_mrmdistribution WHERE month_year!=''";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();        
    }
    public function getmdreport() {
        $query = " SELECT * FROM mis_mdreport WHERE month_year!='' AND mis_flag = '0'";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();        
    }
    public function getmdreportnew() {
        $query = " SELECT * FROM mis_mdreport WHERE month_year!='' AND mis_flag = '1'";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();        
    }
    public function getmdreportn() {
        $query = " SELECT * FROM mis_mdreport WHERE month_year!='' AND mis_flag = '2'";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();        
    }
    public function getmcmreport() {
        $query = " SELECT * FROM mis_mcmreport WHERE month_year!='' AND mis_flag = '0'";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();        
    }
    public function getmcmreportnew() {
        $query = " SELECT * FROM mis_mcmreport WHERE month_year!='' AND mis_flag = '1'";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();        
    }
    public function get_safety_member() {
        $query = " SELECT * FROM mis_safety_member WHERE name!='' AND STATUS='1'";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();      
    }
    public function get_contractor_list($code=null, $column=null) {
        if(!empty($code) && !empty($column)){
           $query = " SELECT ".$column." FROM cset_contractor WHERE flag=1 AND code=".$code; 
           
           $res = $this->select($query);
           $arr = array();
           
           foreach($res  as $value){
              $arr[] = "cset_contractor:".trim($value->$column).":$column";
           }
          
           $rowdata = array_values(array_unique($arr));
        }else{
           $query = " SELECT DISTINCT name,code FROM cset_contractor WHERE flag='1' AND code!=''  GROUP BY identification_code";
           $rowdata = $this->select($query);
          
        }
        return $rowdata;        
    }
    /**
     * created by anima
     * @param type int
     * @param type int
     * @param type date
     * @return array()
     */
    public function getAllContractorbyCode($contractor_id,$contractor_code,$audit_date) {
        $ARR_SCDMC = array();
        $score = $average = 0;
        $clause = "";
        if(!empty($contractor_id)) {
            $clause .= '';
            foreach( $contractor_id as $key => $val ) {
                if($key == 0) { 
                    $clause .= " tree_division_id LIKE '%$val%'"; 
                } else {
                    $clause .= " OR tree_division_id LIKE '%$val%' ";
                }
            }
            $clause .='';
        }
        
        $icode_clause = "";
        if(!empty($contractor_code)) {
            $icode_clause .= ' OR ';
            foreach( $contractor_code as $key => $val ) {
                if($key == 0) { 
                    $icode_clause .= " tree_division_id LIKE '%$val%'"; 
                    
                } else {
                    $icode_clause .= " OR tree_division_id LIKE '%$val%' ";
                }
            }
            $icode_clause .='';
        }
        
      $query = " SELECT count(*) as no_of_audit, avg(avg_mark) as avg_mark FROM actualtarget_audit_view WHERE deleted='0' AND created_by!='2' AND date_of_audit BETWEEN ".$audit_date." AND (".$clause.$icode_clause.") "; 
      $rowdata = $this->select($query);
      $arr_scdmc["NO_OF_AUDIT"] = $rowdata[0]->no_of_audit;
      $arr_scdmc["SCORE"] = $rowdata[0]->avg_mark;
      return $arr_scdmc;
    }
    /**
     * created by pallab
     * @param type int
     * @param type int
     * @return array()
     */
    public function get_contractor_list_Gen_Bbgs($identification_code=null, $column=null){
           if(!empty($identification_code) && !empty($column)){
           $query = " SELECT ".$column." FROM cset_contractor WHERE identification_code=".$identification_code; 
           
           $res = $this->select($query);
           $arr = array();
           
           foreach($res  as $value){
              $arr[] = "cset_contractor:".trim($value->$column).":$column";
           }
          
           $rowdata = array_values(array_unique($arr));
        }else{
           $query = " SELECT DISTINCT name,code,identification_code FROM cset_contractor GROUP BY identification_code";
           $rowdata = $this->select($query);
          
        }
        return $rowdata;          
    }
    /**
     * created by pallab
     * @param type int
     * @param type int
     * @param type date
     * @return array()
     */
    
    public function getAllContractorbyCode_Gen_Bbgs($contractor_id,$contractor_code,$audit_date) {
      $ARR_SCDMC = array();
        $score = $average = 0;
        $clause = "";
        if(!empty($contractor_id)) {
            $clause .= '';
            foreach( $contractor_id as $key => $val ) {
                if($key == 0) { 
                    $clause .= " tree_division_id LIKE '%$val%'"; 
                } else {
                    $clause .= " OR tree_division_id LIKE '%$val%' ";
                }
            }
            $clause .='';
        }
        $icode_clause = "";
        if(!empty($contractor_code)) {
            $icode_clause .= ' OR ';
            foreach( $contractor_code as $key => $val ) {
                if($key == 0) { 
                    $icode_clause .= " tree_division_id LIKE '%$val%'"; 
                    
                } else {
                    $icode_clause .= " OR tree_division_id LIKE '%$val%' ";
                }
            }
            $icode_clause .='';
        }
        $bbgsDataClause = "";
        $bbgsDataClause = "AND (tree_division_id LIKE '%249-2-181%')";
        
      $query = " SELECT count(*) as no_of_audit, avg(avg_mark) as avg_mark FROM actualtarget_audit_view WHERE deleted='0' AND created_by!='2' AND date_of_audit BETWEEN ".$audit_date." AND ((".$clause.$icode_clause.')'.$bbgsDataClause.") "; 
      $rowdata = $this->select($query);
      $arr_scdmc["NO_OF_AUDIT"] = $rowdata[0]->no_of_audit;
      $arr_scdmc["SCORE"] = $rowdata[0]->avg_mark;
      return $arr_scdmc;
    }
    /**
     * created by pallab
     * @param type int
     * @param type int
     * @return array()
     */
    public function get_contractor_list_Gen_Sgs($identification_code=null, $column=null){
           if(!empty($identification_code) && !empty($column)){
           $query = " SELECT ".$column." FROM cset_contractor WHERE identification_code=".$identification_code; 
           
           $res = $this->select($query);
           $arr = array();
           
           foreach($res  as $value){
              $arr[] = "cset_contractor:".trim($value->$column).":$column";
           }
          
           $rowdata = array_values(array_unique($arr));
        }else{
           $query = " SELECT DISTINCT name,code,identification_code FROM cset_contractor GROUP BY identification_code";
           $rowdata = $this->select($query);
          
        }
        return $rowdata;          
    }
    /**
     * created by pallab
     * @param type int
     * @param type int
     * @param type date
     * @return array()
     */
    
    public function getAllContractorbyCode_Gen_Sgs($contractor_id,$contractor_code,$audit_date) {
      $ARR_SCDMC = array();
        $score = $average = 0;
        $clause = "";
        if(!empty($contractor_id)) {
            $clause .= '';
            foreach( $contractor_id as $key => $val ) {
                if($key == 0) { 
                    $clause .= " tree_division_id LIKE '%$val%'"; 
                } else {
                    $clause .= " OR tree_division_id LIKE '%$val%' ";
                }
            }
            $clause .='';
        }
        $icode_clause = "";
        if(!empty($contractor_code)) {
            $icode_clause .= ' OR ';
            foreach( $contractor_code as $key => $val ) {
                if($key == 0) { 
                    $icode_clause .= " tree_division_id LIKE '%$val%'"; 
                    
                } else {
                    $icode_clause .= " OR tree_division_id LIKE '%$val%' ";
                }
            }
            $icode_clause .='';
        }
        $bbgsDataClause = "";
        $bbgsDataClause = "AND (tree_division_id LIKE '%249-2-183%')";
        
      $query = " SELECT count(*) as no_of_audit, avg(avg_mark) as avg_mark FROM actualtarget_audit_view WHERE deleted='0' AND created_by!='2' AND date_of_audit BETWEEN ".$audit_date." AND ((".$clause.$icode_clause.')'.$bbgsDataClause.") "; 
      $rowdata = $this->select($query);
      $arr_scdmc["NO_OF_AUDIT"] = $rowdata[0]->no_of_audit;
      $arr_scdmc["SCORE"] = $rowdata[0]->avg_mark;
      return $arr_scdmc;
    }
    
    
    public function get_mis_target($actid,$financial_year,$target_type=null) {
        if(empty($actid) && empty($financial_year))   return FALSE;
        $cls = "";
        if(!empty($target_type)) {
            $cls = " AND target_type='$target_type'";
        }
        $query = " SELECT * FROM mis_target_view WHERE major_activity_id='$actid' AND financial_year='$financial_year'".$cls;      
        $rowdata = $this->select($query);
        $newarr = array();
        @$newarr["is_set"] = ( ( $rowdata[0]->is_set != "" ) ? $rowdata[0]->is_set : "" );
        @$newarr["target"] = ( ( $rowdata[0]->target != "" ) ? $rowdata[0]->target : "" );
        @$newarr["pset_target"] = ( ( $rowdata[0]->pset_target != "" ) ? $rowdata[0]->pset_target : "" );
        @$newarr["cset_target"] = ( ( $rowdata[0]->cset_target != "" ) ? $rowdata[0]->cset_target : "" );
        @$newarr["pcset_target"] = ( ( $rowdata[0]->pcset_target != "" ) ? $rowdata[0]->pcset_target : "" );
        @$newarr["others_target"] = ( ( $rowdata[0]->others_target != "" ) ? $rowdata[0]->others_target : "" );
        @$newarr["gen_target"] = ( ( $rowdata[0]->gen != "" ) ? $rowdata[0]->gen : "" );
        @$newarr["dist_target"] = ( ( $rowdata[0]->dist != "" ) ? $rowdata[0]->dist : "" );
        
        return $newarr;
    }
    
    public function get_number_safety_observation($resultfor,$val1=null,$val2=null) {
        if( $resultfor == 1 ) {
            $imp=implode("','",$val1);
            $clause = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) IN('".$imp."') ";
        } else if( $resultfor == 2 ) {
            $exp=explode("-",$val1);
            $activity_year  = $exp[1];
            $activity_month = $exp[0];
            $clause = " AND activity_year IN($activity_year) AND activity_month IN ($activity_month) ";
        }  else if( $resultfor == 3 ) {
            $imp=implode("','",$val1);
            $clause = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) IN('".$imp."') ";
        }
            $newclause = "";
        if( !empty($val2) ) {
            if( $val2 == "G" ) {
                $newclause = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
            } else if( $val2 == "D" ) {
                $newclause = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
            }
        }
        $query = " SELECT *,CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) as formatted_date FROM safety_observation_view "
                 . "WHERE deleted='0' AND created_by!='2' ".$newclause.$clause; 
        
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
                $data+=$row->activity_count;
            }
        }
        return ($data);
    }
    public function get_number_safety_observation_New($resultfor,$val1=null,$val2=null) {
        if( $resultfor == 1 ) {
            $imp=implode("','",$val1);
            $clause = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) IN('".$imp."') ";
        } else if( $resultfor == 2 ) {
            $exp=explode("-",$val1);
            $activity_year  = $exp[1];
            $activity_month = $exp[0];
            $clause = " AND activity_year IN($activity_year) AND activity_month IN ($activity_month) ";
        }  else if( $resultfor == 3 ) {
            $imp=implode("','",$val1);
            $clause = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) IN('".$imp."') ";
        }
        $newclause = "";
        if( !empty($val2) ) {
            if( $val2 == "G" ) {
                $newclause = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
            } else if( $val2 == "D" ) {
                $newclause = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
            } else if( $val2 == "DExHD" ) {
                $newclause = " AND (tree_division_id NOT LIKE '249-3-5-19%' AND tree_division_id NOT LIKE '249-3-5-10%' AND tree_division_id NOT LIKE '249-3-6%' AND tree_division_id NOT LIKE '249-3-7%' AND tree_division_id NOT LIKE '249-3-8%' AND tree_division_id NOT LIKE '249-4-168%' AND tree_division_id NOT LIKE '249-2%' AND tree_division_id NOT LIKE '249-3-5-9%')";
            } else if( $val2 == "DHD" ) {
                $newclause = " AND (tree_division_id LIKE '249-3-5-19%')";
            } else if( $val2 == "MM" ) {
                $newclause = " AND (tree_division_id LIKE '249-3-5-10%')";
            } else if( $val2 == "MHT" ) {
                $newclause = " AND (tree_division_id LIKE '249-3-5-9%')";
            } else if( $val2 == "SC" ) {
                $newclause = " AND (tree_division_id LIKE '249-4-168%')";
            } else if( $val2 == "SS" ) {
                $newclause = " AND (tree_division_id LIKE '249-3-7%')";
            } else if( $val2 == "ACG" ) {
                $newclause = " AND (tree_division_id LIKE '249-3-8%')";
            } else if( $val2 == "TD" ) {
                $newclause = " AND (tree_division_id LIKE '249-3-6%')";
            }
        }
  
        $query = " SELECT *,CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) as formatted_date FROM safety_observation_view "
                 . "WHERE deleted='0' AND created_by!='2' ".$newclause.$clause; 

        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
                $data+=$row->activity_count;
            }
        }
        return ($data);
    }
    //mis-16/17
    public function getSafetyObservationByLineFunctionCount($resultfor,$val1=null,$val2=null,$val3=null) {
        if( $resultfor == 1 ) {
            $monthyear = explode("-",$val1);
            $year = $monthyear[0];
            $month1 = $monthyear[1];
            $month = ltrim($month1, '0');
            $clause = " AND activity_month LIKE '$month%' AND activity_year LIKE '$year%'";//show($clause)
        } else if( $resultfor == 2 ) {
            $exp=explode("-",$val1);
            $activity_month  = $exp[1];
            $activity_year = $exp[0];
            $exp2=explode("-",$val2);
            $activity_month2  = $exp2[1];
            $activity_year2 = $exp2[0];
            
          $yearrange =  $activity_year.'-'.$activity_year2;
          $getprevytmrange            =  getAllMonthYear($yearrange);
          $imp=implode("','",$getprevytmrange);
       $clause = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) IN('".$imp."') ";
          }  
        else if( $resultfor == 3 ) {
        
           $exp=explode(",",$val1);
            $activity_yearsecond  = $exp[1];
            $activity_yearfirst = $exp[0];
             $exp1=explode("-",$activity_yearfirst);
              $activity_yearsecond1  = $exp1[1];
            $activity_yearfirst1 = $exp1[0];
             $exp2=explode("-",$activity_yearsecond);
              $activity_yearsecond2  = $exp2[1];
            $activity_yearfirst2 = $exp2[0];
            
          $yearrange =  $activity_yearfirst1.'-'.$activity_yearfirst2;
          $getprevytmrange            =  getAllMonthYear($yearrange);
          $imp=implode("','",$getprevytmrange);
            $clause = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) IN('".$imp."') ";
          }   
        else if( $resultfor == 4 ) {
       
            $clause = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) IN('".$val1."') ";
        }
            $newclause = "";
        if( !empty($val3) ) {
            if( $val3 == "G" ) {
                $newclause = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
            } else if( $val3 == "D" ) {
                $newclause = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
            }
        }
        $query = " SELECT *,CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) as formatted_date FROM safety_observation_view "
                 . "WHERE deleted='0' AND created_by!='2' ".$newclause.$clause; 
        
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
                $data+=$row->activity_count;
            }
        }
        return ($data);
    }
    
    public function get_number_safety_audit($resultfor,$val1=null,$val2=null) {
        if( $resultfor == 1 ) {
            $imp=implode("','",$val1);
            $clause = " AND DATE_FORMAT(date_of_audit,'%Y-%m') IN('".$imp."') ";
        } else if( $resultfor == 2 ) {
            $exp=explode("-",$val1);
            $activity_year  = $exp[1];
            $activity_month = $exp[0];
            $clause = " AND DATE_FORMAT(date_of_audit,'%Y-%m') LIKE '$val1%' ";
        }  else if( $resultfor == 3 ) {
            $imp=implode("','",$val1);
            $clause = " AND DATE_FORMAT(date_of_audit,'%Y-%m') IN('".$imp."') ";
        }   
        $query = " SELECT id,DATE_FORMAT(date_of_audit,'%Y-%m') as asd FROM audit_view  "
                . " WHERE deleted='0' AND created_by!='2' ".$clause
                . " UNION ALL SELECT id,DATE_FORMAT(date_of_audit,'%Y-%m') as asd FROM ppe_audit_view"
                 . " WHERE deleted='0' AND created_by!='2' ".$clause; 
        $rowdata = $this->select($query);
        $rowcount=count($rowdata);
        return ($rowcount);
    }
    
    
    
    public function getOfficerTraining($resultfor,$pcatid,$val1=null,$val2=null,$divtype=null) {
        if( $resultfor == 1 ) {
            $clause = " AND a.from_date LIKE '".$val1."%'";
        } else if( $resultfor == 2 ) {
            $lastdate = date("Y-m-t",strtotime($val1));
            $clause = " AND a.from_date BETWEEN '".$val2."-01' AND '".$lastdate."'";
        }  else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $lastdate = date("Y-m-t",strtotime($exp[1]));
            $clause = " AND a.from_date BETWEEN '".$exp[0]."-01' AND '".$lastdate."'";
        }    
        $newclause = "";
        if( !empty($divtype) ) {
            if( $divtype == "G" ) {
                $newclause = " AND (a.tree_division_id LIKE '249-2%' OR a.tree_division_id LIKE '250-251%')";
            } else if( $divtype == "D" ) {
                $newclause = " AND (a.tree_division_id LIKE '249-3%' OR a.tree_division_id LIKE '249-4%' OR a.tree_division_id LIKE '250-252%')";
            }
        }
       
        $query = "SELECT * FROM training_ws_view a WHERE a.activity_type_id=3 AND a.participant_cat_id=".$pcatid." AND a.deleted='0' AND a.created_by!='2' ".$clause.$newclause;
       $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->no_of_emp;
            }
        }
        return ($data);
    }
    public function getOfficerTrainingNew($resultfor,$pcatid,$val1=null,$val2=null,$divtype=null) {
        if( $resultfor == 1 ) {
            $clause = " AND a.from_date LIKE '".$val1."%'";
        } else if( $resultfor == 2 ) {
            $lastdate = date("Y-m-t",strtotime($val1));
            $clause = " AND a.from_date BETWEEN '".$val2."-01' AND '".$lastdate."'";
        }  else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $lastdate = date("Y-m-t",strtotime($exp[1]));
            $clause = " AND a.from_date BETWEEN '".$exp[0]."-01' AND '".$lastdate."'";
        }    
        $newclause = "";
        if( !empty($divtype) ) {
            if( $divtype == "G" ) {
                $newclause = " AND (a.tree_division_id LIKE '249-2%' OR a.tree_division_id LIKE '250-251%')";
            } else if( $divtype == "D" ) {
                $newclause = " AND (a.tree_division_id LIKE '249-3%' OR a.tree_division_id LIKE '249-4%' OR a.tree_division_id LIKE '250-252%')";
            }
        }
       
        $query = "SELECT * FROM training_ws_view a WHERE a.activity_type_id=3 AND a.participant_cat_id IN (".$pcatid.") AND a.deleted='0' AND a.created_by!='2' ".$clause.$newclause;
       $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->no_of_emp;
            }
        }
        return ($data);
    }
    public function getSupervisorTraining($resultfor,$pcatid=null,$val1=null,$val2=null,$type,$divtype=null) {
        if( $resultfor == 1 ) {
            $clause = " AND from_date LIKE '".$val1."%'";
        } else if( $resultfor == 2 ) {
            $clause = " AND from_date BETWEEN '".$val2."-01' AND '".$val1."-31'";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $clause = " AND from_date BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }        
        $newclause = "";
        if( !empty($divtype) ) {
            if( $divtype == "G" ) {
                $newclause = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
            } else if( $divtype == "D" ) {
                $newclause = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
            }
        }
        $pclause ="";
        if(!empty($pcatid)){
            $pclause = " AND participant_cat_id IN ($pcatid)";
        }
        
        $query = " SELECT * FROM training_ws_view "
                 . "WHERE activity_type_id=3  ".$pclause
                 . " AND deleted='0' AND created_by!='2' "
                 . " AND (designation='".$type."' )".$clause.$newclause;  //OR designation_2='".$type."'
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->no_of_emp;
            }
        } 
        return ($data);
    }
    
    
    public function getPostCount($resultfor,$activity_type_id,$colname,$tablename,$val1=null,$val2=null,$divtype=null) {
        if( $resultfor == 1 ) {
            $clause = " AND $colname LIKE '".$val1."%'";
        } else if( $resultfor == 2 ) {
            $clause = " AND $colname BETWEEN '".$val2."-01' AND '".$val1."-31'";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $clause = " AND $colname BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }    
        $newclause = "";
        if( !empty($divtype) ) {
            if( $divtype == "G" ) {
                $newclause = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
            } else if( $divtype == "D" ) {
                $newclause = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
            }
        }
        
        $query = " SELECT * FROM $tablename "
                . "WHERE activity_type_id = $activity_type_id AND deleted = '0' AND created_by != '2' ".$clause.$newclause;
        $rowdata = $this->select($query);
        return count($rowdata);
    }
    public function getPostCountNew($resultfor,$activity_type_id,$colname,$tablename,$val1=null,$val2=null,$divtype=null) {
        if( $resultfor == 1 ) {
            $clause = " AND A.".$colname." LIKE '".$val1."%'";
        } else if( $resultfor == 2 ) {
            $clause = " AND A.".$colname." BETWEEN '".$val2."-01' AND '".$val1."-31'";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $clause = " AND A.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }    
        $newclause = "";
        if( !empty($divtype) ) {
            if( $divtype == "G" ) {
                $newclause = " AND (ADM.tree_division_id LIKE '249-2%' OR ADM.tree_division_id LIKE '250-251%')";
            } else if( $divtype == "D" ) {
                $newclause = " AND (ADM.tree_division_id LIKE '249-3%' OR ADM.tree_division_id LIKE '249-4%' OR ADM.tree_division_id LIKE '250-252%')";
            } else if( $divtype == "DExHD" ) {
                $newclause = " AND (ADM.tree_division_id NOT LIKE '249-3-5-19%' AND ADM.tree_division_id NOT LIKE '249-3-5-10%' AND ADM.tree_division_id NOT LIKE '249-3-6%' AND ADM.tree_division_id NOT LIKE '249-3-7%' AND ADM.tree_division_id NOT LIKE '249-3-8%' AND ADM.tree_division_id NOT LIKE '249-4-168%' AND ADM.tree_division_id NOT LIKE '249-2%' AND ADM.tree_division_id NOT LIKE '249-3-5-9%')";
            } else if( $divtype == "DHD" ) {
                $newclause = " AND (ADM.tree_division_id LIKE '249-3-5-19%')";
            } else if( $divtype == "MM" ) {
                $newclause = " AND (ADM.tree_division_id LIKE '249-3-5-10%')";
            } else if( $divtype == "MHT" ) {
                $newclause = " AND (ADM.tree_division_id LIKE '249-3-5-9%')";
            } else if( $divtype == "SC" ) {
                $newclause = " AND (ADM.tree_division_id LIKE '249-4-168%')";
            } else if( $divtype == "SS" ) {
                $newclause = " AND (ADM.tree_division_id LIKE '249-3-7%')";
            } else if( $divtype == "ACG" ) {
                $newclause = " AND (ADM.tree_division_id LIKE '249-3-8%')";
            } else if( $divtype == "TD" ) {
                $newclause = " AND (ADM.tree_division_id LIKE '249-3-6%')";
            }
        }
        
        $query = " SELECT * FROM activity A "
                . "LEFT JOIN activity_division_mapping ADM ON ADM.activity_id = A.id "
                . "WHERE A.activity_type_id = $activity_type_id AND A.deleted = '0' AND A.created_by != '2' ".$clause.$newclause;
        $rowdata = $this->select($query);
        return count($rowdata);
    }
    public function getPPECountNew($resultfor,$activity_type_id,$colname,$tablename,$val1=null,$val2=null,$divtype=null) {
        if( $resultfor == 1 ) {
            $clause = " AND P.".$colname." LIKE '".$val1."%'";
        } else if( $resultfor == 2 ) {
            $clause = " AND P.".$colname." BETWEEN '".$val2."-01' AND '".$val1."-31'";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $clause = " AND P.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }    
        $newclause = "";
        if( !empty($divtype) ) {
            if( $divtype == "G" ) {
                $newclause = " AND (PADM.tree_division_id LIKE '249-2%' OR PADM.tree_division_id LIKE '250-251%')";
            } else if( $divtype == "D" ) {
                $newclause = " AND (PADM.tree_division_id LIKE '249-3%' OR PADM.tree_division_id LIKE '249-4%' OR PADM.tree_division_id LIKE '250-252%')";
            } else if( $divtype == "DExHD" ) {
                $newclause = " AND (PADM.tree_division_id NOT LIKE '249-3-5-19%' AND PADM.tree_division_id NOT LIKE '249-3-5-10%' AND PADM.tree_division_id NOT LIKE '249-3-6%' AND PADM.tree_division_id NOT LIKE '249-3-7%' AND PADM.tree_division_id NOT LIKE '249-3-8%' AND PADM.tree_division_id NOT LIKE '249-4-168%' AND PADM.tree_division_id NOT LIKE '249-2%' AND PADM.tree_division_id NOT LIKE '249-3-5-9%')";
            } else if( $divtype == "DHD" ) {
                $newclause = " AND (PADM.tree_division_id LIKE '249-3-5-19%')";
            } else if( $divtype == "MM" ) {
                $newclause = " AND (PADM.tree_division_id LIKE '249-3-5-10%')";
            } else if( $divtype == "MHT" ) {
                $newclause = " AND (PADM.tree_division_id LIKE '249-3-5-9%')";
            } else if( $divtype == "SC" ) {
                $newclause = " AND (PADM.tree_division_id LIKE '249-4-168%')";
            } else if( $divtype == "SS" ) {
                $newclause = " AND (PADM.tree_division_id LIKE '249-3-7%')";
            } else if( $divtype == "ACG" ) {
                $newclause = " AND (PADM.tree_division_id LIKE '249-3-8%')";
            } else if( $divtype == "TD" ) {
                $newclause = " AND (PADM.tree_division_id LIKE '249-3-6%')";
            }
        }
        
        $query = " SELECT * FROM ppe_audit P "
                . "LEFT JOIN ppe_audit_division_mapping PADM ON PADM.ppe_audit_id = P.id "
                . "WHERE P.activity_type_id = $activity_type_id AND P.deleted = '0' AND P.created_by != '2' ".$clause.$newclause;
        $rowdata = $this->select($query);
        return count($rowdata);
    }
    public function getTBMHandHoldingGen($resultfor,$colname,$tablename,$val1=null,$val2=null,$getytmmonthyearrange) {
//        $expval1=explode("-",$val1);
        if( $resultfor == 1 ) {
//            $clause = " AND mmd.".$colname." LIKE '".$expval1[1]-$expval1[0]."%'";
        } else if( $resultfor == 2 ) {
//            $clause = " mmd.".$colname." LIKE '%".(sprintf("%02d",$expval1[1]-1))."-".$expval1[0]."%'";
            $abc='';
            foreach($getytmmonthyearrange as $kkk=>$vvv){
                $abc.="'".$vvv."',";
            }
            $clause = " mmd.".$colname." IN(".rtrim($abc,',').")";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
//            $clause = " AND mmd.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        
        
        $query = " SELECT mmd.id,mmdm.ytm_total,mmdm.total_in_this_month FROM mis_mdreport mmd "
                . "LEFT JOIN mis_mdreport_mapping mmdm ON mmdm.mdreport_id = mmd.id "
                . "WHERE mmdm.major_activity_id='121' AND ".$clause;
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->total_in_this_month;
            }
        } 
        
        return ($data); 
    }
    public function getTBMHandHoldingDExHD($resultfor,$colname,$tablename,$val1=null,$val2=null,$getytmmonthyearrange) {
        if( $resultfor == 1 ) {
//            $clause = " AND mmd.".$colname." LIKE '".$expval1[1]-$expval1[0]."%'";
        } else if( $resultfor == 2 ) {
            $abc='';
            foreach($getytmmonthyearrange as $kkk=>$vvv){
                $abc.="'".$vvv."',";
            }
            $clause = " mmd.".$colname." IN(".rtrim($abc,',').")";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
//            $clause = " AND mmd.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        
        
        $query = " SELECT mmd.id,mmdm.ytm_total,mmdm.total_in_this_month FROM mis_mdreport mmd "
                . "LEFT JOIN mis_mdreport_mapping mmdm ON mmdm.mdreport_id = mmd.id "
                . "WHERE mmdm.major_activity_id='142' AND ".$clause;
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->total_in_this_month;
            }
        } 
        
        return ($data);   
    }
    public function getTBMHandHoldingDHD($resultfor,$colname,$tablename,$val1=null,$val2=null,$getytmmonthyearrange) {
        if( $resultfor == 1 ) {
//            $clause = " AND mmd.".$colname." LIKE '".$expval1[1]-$expval1[0]."%'";
        } else if( $resultfor == 2 ) {
            $abc='';
            foreach($getytmmonthyearrange as $kkk=>$vvv){
                $abc.="'".$vvv."',";
            }
            $clause = " mmd.".$colname." IN(".rtrim($abc,',').")";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
//            $clause = " AND mmd.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        
        
        $query = " SELECT mmd.id,mmdm.ytm_total,mmdm.total_in_this_month FROM mis_mdreport mmd "
                . "LEFT JOIN mis_mdreport_mapping mmdm ON mmdm.mdreport_id = mmd.id "
                . "WHERE mmdm.major_activity_id='148' AND ".$clause;
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->total_in_this_month;
            }
        } 
        
        return ($data);   
    }
    public function getdistsiteauditMHT($resultfor,$colname,$tablename,$val1=null,$val2=null,$getytmmonthyearrange) {
        if( $resultfor == 1 ) {
//            $clause = " AND mmd.".$colname." LIKE '".$expval1[1]-$expval1[0]."%'";
        } else if( $resultfor == 2 ) {
            $abc='';
            foreach($getytmmonthyearrange as $kkk=>$vvv){
                $abc.="'".$vvv."',";
            }
            $clause = " mmd.".$colname." IN(".rtrim($abc,',').")";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
//            $clause = " AND mmd.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        
        
        $query = " SELECT mmd.id,mmdm.ytm_total,mmdm.total_in_this_month FROM mis_mdreport mmd "
                . "LEFT JOIN mis_mdreport_mapping mmdm ON mmdm.mdreport_id = mmd.id "
                . "WHERE mmdm.major_activity_id='158' AND ".$clause;//show($query);
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->total_in_this_month;
            }
        } 
        
        return ($data);   
    }
    public function getFatalCaseGP($resultfor,$colname,$tablename,$val1=null,$val2=null,$getytmmonthyearrange) {
        if( $resultfor == 1 ) {
//            $clause = " AND mmd.".$colname." LIKE '".$expval1[1]-$expval1[0]."%'";
        } else if( $resultfor == 2 ) {
            $abc='';
            foreach($getytmmonthyearrange as $kkk=>$vvv){
                $abc.="'".$vvv."',";
            }
            $clause = " mmd.".$colname." IN(".rtrim($abc,',').")";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
//            $clause = " AND mmd.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        
        
        $query = " SELECT mmd.id,mmdm.ytm_total,mmdm.total_in_this_month FROM mis_mdreport mmd "
                . "LEFT JOIN mis_mdreport_mapping mmdm ON mmdm.mdreport_id = mmd.id "
                . "WHERE mmdm.major_activity_id='188' AND ".$clause;
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->total_in_this_month;
            }
        } 
        
        return ($data);   
    }
    public function gethandholding_htmpb_pset($resultfor,$colname,$tablename,$val1=null,$val2=null,$getytmmonthyearrange) {
        if( $resultfor == 1 ) {
//            $clause = " AND mmcm.".$colname." LIKE '".$expval1[1]-$expval1[0]."%'";
        } else if( $resultfor == 2 ) {
            $abc='';
            foreach($getytmmonthyearrange as $kkk=>$vvv){
                $abc.="'".$vvv."',";
            }
            $clause = " mmcm.".$colname." IN(".rtrim($abc,',').")";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
//            $clause = " AND mmcm.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        
        
        $query = " SELECT mmcm.id,mmcmm.total_in_this_month_p,mmcmm.total_in_this_month_c,mmcmm.ytm_this_year_p,mmcmm.ytm_this_year_c "
                . "FROM mis_mcmreport mmcm "
                . "LEFT JOIN mis_mcmreport_mapping mmcmm ON mmcmm.mcmreport_id = mmcm.id "
                . "WHERE mmcmm.major_activity_id='108' AND ".$clause;
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->ytm_this_year_p;
            }
        } 
        
        return ($data);   
    }
    public function gethandholding_htmpb_cset($resultfor,$colname,$tablename,$val1=null,$val2=null,$getytmmonthyearrange) {
        if( $resultfor == 1 ) {
//            $clause = " AND mmcm.".$colname." LIKE '".$expval1[1]-$expval1[0]."%'";
        } else if( $resultfor == 2 ) {
            $abc='';
            foreach($getytmmonthyearrange as $kkk=>$vvv){
                $abc.="'".$vvv."',";
            }
            $clause = " mmcm.".$colname." IN(".rtrim($abc,',').")";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
//            $clause = " AND mmcm.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        
        
        $query = " SELECT mmcm.id,mmcmm.total_in_this_month_p,mmcmm.total_in_this_month_c,mmcmm.ytm_this_year_p,mmcmm.ytm_this_year_c "
                . "FROM mis_mcmreport mmcm "
                . "LEFT JOIN mis_mcmreport_mapping mmcmm ON mmcmm.mcmreport_id = mmcm.id "
                . "WHERE mmcmm.major_activity_id='108' AND ".$clause;
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->ytm_this_year_c;
            }
        } 
        
        return ($data);   
    }
    public function gethandholding_sf($resultfor,$colname,$tablename,$val1=null,$val2=null,$getytmmonthyearrange) {
        if( $resultfor == 1 ) {
//            $clause = " AND mmcm.".$colname." LIKE '".$expval1[1]-$expval1[0]."%'";
        } else if( $resultfor == 2 ) {
            $abc='';
            foreach($getytmmonthyearrange as $kkk=>$vvv){
                $abc.="'".$vvv."',";
            }
            $clause = " mmcm.".$colname." IN(".rtrim($abc,',').")";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
//            $clause = " AND mmcm.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        
        
        $query = " SELECT mmcm.id,mmcmm.total_in_this_month,mmcmm.ytm_total,mmcmm.total_in_this_month_g,mmcmm.ytm_total_g "
                . "FROM mis_mcmreport mmcm "
                . "LEFT JOIN mis_mcmreport_mapping mmcmm ON mmcmm.mcmreport_id = mmcm.id "
                . "WHERE mmcmm.major_activity_id='107' AND ".$clause;
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->total_in_this_month;
            }
        } 
        
        return ($data);   
    }
    public function gethandholding_sf_g($resultfor,$colname,$tablename,$val1=null,$val2=null,$getytmmonthyearrange) {
        if( $resultfor == 1 ) {
//            $clause = " AND mmcm.".$colname." LIKE '".$expval1[1]-$expval1[0]."%'";
        } else if( $resultfor == 2 ) {
            $abc='';
            foreach($getytmmonthyearrange as $kkk=>$vvv){
                $abc.="'".$vvv."',";
            }
            $clause = " mmcm.".$colname." IN(".rtrim($abc,',').")";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
//            $clause = " AND mmcm.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        
        
        $query = " SELECT mmcm.id,mmcmm.total_in_this_month,mmcmm.ytm_total,mmcmm.total_in_this_month_g,mmcmm.ytm_total_g "
                . "FROM mis_mcmreport mmcm "
                . "LEFT JOIN mis_mcmreport_mapping mmcmm ON mmcmm.mcmreport_id = mmcm.id "
                . "WHERE mmcmm.major_activity_id='107' AND ".$clause;
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->total_in_this_month_g;
            }
        } 
        
        return ($data);   
    }
    public function getdistsiteauditHT($resultfor,$colname,$tablename,$val1=null,$val2=null,$getytmmonthyearrange) {
        $expval1=explode("-",$val1);
        if( $resultfor == 1 ) {
//            $clause = " AND mmd.".$colname." LIKE '".$expval1[1]-$expval1[0]."%'";
        } else if( $resultfor == 2 ) {
            
            $clause = " mmd.".$colname." LIKE '".$expval1[1].'-'.$expval1[0]."%'";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
//            $clause = " AND mmd.".$colname." BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        
        
        $query = " SELECT mmd.id,mmdm.ytm_total,mmdm.total_in_this_month FROM mis_mdreport mmd "
                . "LEFT JOIN mis_mdreport_mapping mmdm ON mmdm.mdreport_id = mmd.id "
                . "WHERE mmdm.major_activity_id='158' AND ".$clause;//show($query);
        $rowdata = $this->select($query);
        $data = 0;
        if(count($rowdata) > 0 ) {
            foreach($rowdata as $row) {
           $data+=$row->total_in_this_month;
            }
        } 
        
        return ($data);   
    }
    public function getPPECount($resultfor,$activity_type_id,$colname,$tablename,$val1=null,$val2=null,$settype=null,$div) {
        if( $resultfor == 1 ) {
            $clause = " AND date_of_audit BETWEEN '".$val1."-01' AND '".$val1."-31'";
        }
        if( $resultfor == 2 ) {
            $clause = " AND date_of_audit BETWEEN '".$val2."-01' AND '".$val1."-31'";
        }
        if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $clause = " AND $colname BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        if( $resultfor == 4 ) {
            $clause = " AND date_of_audit BETWEEN '".$val1."-01' AND '".$val1."-31'";
        }
        $newclause = "";
        if($div == 'D') {
            $treeid = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
        } else if($div == 'G') {
            $treeid = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
        } else {
            $treeid = "";
        }
        if( !empty($settype) ) {
            if( $settype == "C" ) {
                $newclause = " AND set_type='C-SET'".$treeid;
            } else if( $settype == "P" ) {
                $newclause = " AND set_type='P-SET'".$treeid;
            }
        }
        $query = " SELECT * FROM $tablename "
                . "WHERE activity_type_id = $activity_type_id AND deleted = '0' AND created_by != '2' ".$clause.$newclause;
        $rowdata = $this->select($query);
        return count($rowdata);
        
    }
    public function getTrainingCount($resultfor,$activity_type_id,$colname,$val1=null,$val2=null,$settype=null,$div) {
        if( $resultfor == 1 ) {
            $lastdate = date("Y-m-t",strtotime($val1));
            $clause = " AND from_date BETWEEN '".$val1."-01' AND '".$lastdate."'";
        }
        
        if( $resultfor == 2 ) {
            $lastdate = date("Y-m-t",strtotime($val1));
            $clause = " AND from_date BETWEEN '".$val2."-01' AND '".$lastdate."'";
        }
        
        if( $resultfor == 3 ) {
            @$exp=explode(",",$val1);
            @$clause = " AND from_date BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        if( $resultfor == 4 ) {
            @$lastdate = date("Y-m-t",strtotime($val1));
            @$clause = " AND from_date BETWEEN '".$val1."-01' AND '".$lastdate."'";
        }
        
        $newclause = "";
        if($div == 'D') {
            $treeid = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
        } else if($div == 'G') {
            $treeid = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
        } else {
            $treeid = "";
        }
        if( !empty($settype) ) {
            if( $settype == "C" ){
                $newclause = " AND participant_cat_id = '4' AND  designation_2 LIKE '%Workman%' ";
            }
            if( $settype == "P" ){
                $newclause = " AND participant_cat_id = '3' AND  designation LIKE '%Workman%' ";
            }
            if( $settype == "O" ){
                $newclause = " AND participant_cat_id = '5'";
            }
            if( $settype == "PS" ){
                $newclause = " AND participant_cat_id = '3' AND  designation LIKE 'Supervisor%' ";
            }
            if( $settype == "CS" ) {
                $newclause = " AND participant_cat_id = '4' AND  designation_2 LIKE 'Supervisor%' ";
            }
        }
        
        $query = "SELECT *,SUM(no_of_emp) as total_emp FROM training_ws_view WHERE deleted = '0' AND created_by != '2'".$clause.$newclause.$treeid." GROUP BY (participant_cat_id)";
        $rowdata = $this->select($query);
        $data=0;
        foreach($rowdata as $value){
            $data += $value->total_emp;
        }
        return $data;
        
    }
    public function getSafetyAuditCount($resultfor,$activity_type_id,$colname,$tablename,$val1=null,$val2=null,$div) {
        if( $resultfor == 1 ) {
            $clause = " AND date_of_audit BETWEEN '".$val1."-01' AND '".$val1."-31'";
        } 
        
        if( $resultfor == 2 ) {
            $clause = " AND date_of_audit BETWEEN '".$val2."-01' AND '".$val1."-31'";
        }
        if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $clause = " AND date_of_audit BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        if( $resultfor == 4 ) {
            $clause = " AND date_of_audit BETWEEN '".$val1."-01' AND '".$val1."-31'";
        }
        if($div == 'D') {
            $treeid = " AND (tree_division_id LIKE '249-3%' OR  tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
        } else if($div == 'G') {
            $treeid = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
        } else {
            $treeid = "";
        }
        $query = " SELECT * FROM $tablename "
                . "WHERE activity_type_id = $activity_type_id AND deleted = '0' AND created_by != '2' ".$clause.$treeid;
        
        $rowdata = $this->select($query);
        return count($rowdata);    
    }
    public function getSafetyAuditCountforCC($resultfor,$activity_type_id,$colname,$tablename,$val1=null,$val2=null,$div) {
        if( $resultfor == 1 ) {
             $month_last_date = date("Y-m-t", strtotime($val1));
            $clause = " AND date_of_audit BETWEEN '".$val1."-01' AND '".$month_last_date."'";
        } 
        
        if( $resultfor == 2 ) {
             $month_last_date = date("Y-m-t", strtotime($val1));
            $clause = " AND date_of_audit BETWEEN '".$val2."-01' AND '".$month_last_date."'";
        }
        if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $month_last_date = date("Y-m-t", strtotime($exp[1]));
            $clause = " AND date_of_audit BETWEEN '".$exp[0]."-01' AND '".$month_last_date."'";
        }
        if( $resultfor == 4 ) {
            $month_last_date = date("Y-m-t", strtotime($val1));
            $clause = " AND date_of_audit BETWEEN '".$val1."-01' AND '".$month_last_date."'";
        }
        if($div == 'D') {
            $treeid = " AND ((tree_division_id LIKE '249-3%' OR  tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%') AND tree_division_id LIKE '%call_center%')";
        } else if($div == 'G') {
            $treeid = " AND ((tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%') AND tree_division_id LIKE '%call_center%')";
        } else {
            $treeid = "";
        }
        $query = " SELECT * FROM $tablename "
                . "WHERE activity_type_id = $activity_type_id AND deleted = '0' AND created_by != '2' ".$clause.$treeid;
        
        $rowdata = $this->select($query);
        return count($rowdata);    
    }
    public function getCommMeetingCount($resultfor,$activity_type_id,$colname,$tablename,$val1=null,$val2=null,$div) {
        if( $resultfor == 1 ) {
            $clause = " AND activity_date BETWEEN '".$val1."-01' AND '".$val1."-31'";
        } 
        
        if( $resultfor == 2 ) {
            $clause = " AND activity_date BETWEEN '".$val2."-01' AND '".$val1."-31'";
        }
        if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $clause = " AND activity_date BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        if( $resultfor == 4 ) {
            $clause = " AND activity_date BETWEEN '".$val1."-01' AND '".$val1."-31'";
        }
        if($div == 'D') {
            $treeid = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
        } else if($div == 'G') {
            $treeid = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
        } else {
            $treeid = "";
        }
        $query = " SELECT * FROM $tablename "
                . "WHERE activity_type_id = $activity_type_id AND deleted = '0' AND created_by != '2' ".$clause.$treeid;       
        $rowdata = $this->select($query);
        return count($rowdata);    
    }
    public function getWorkshopCount($resultfor,$activity_type_id,$colname,$tablename,$val1=null,$val2=null,$div) {
        if( $resultfor == 1 ) {
            $clause = " AND activity_date BETWEEN '".$val1."-01' AND '".$val1."-31'";
        } 
        
        if( $resultfor == 2 ) {
            $clause = " AND activity_date BETWEEN '".$val2."-01' AND '".$val1."-31'";
        }
        if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $clause = " AND activity_date BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        if( $resultfor == 4 ) {
            $clause = " AND activity_date BETWEEN '".$val1."-01' AND '".$val1."-31'";
        }
        if($div == 'D') {
            $treeid = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
        } else if($div == 'G') {
            $treeid = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
        } else {
            $treeid = "";
        }
        
        $query = " SELECT * FROM $tablename "
                . "WHERE activity_type_id = $activity_type_id AND deleted = '0' AND created_by != '2' ".$clause.$treeid;
        $rowdata = $this->select($query);
        return count($rowdata);    
    }
    public function getSafetyDayCount($resultfor,$activity_type_id,$colname,$tablename,$val1=null,$val2=null,$div) {
        if( $resultfor == 1 ) {
            $clause = " AND activity_date BETWEEN '".$val1."-01' AND '".$val1."-31'";
        } 
        if( $resultfor == 2 ) {
            $clause = " AND activity_date BETWEEN '".$val2."-01' AND '".$val1."-31'";
        }
        if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $clause = " AND activity_date BETWEEN '".$exp[0]."-01' AND '".$exp[1]."-31'";
        }
        if( $resultfor == 4 ) {
            $clause = " AND activity_date BETWEEN '".$val1."-01' AND '".$val1."-31'";
        }
        if($div == 'D') {
            $treeid = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
        } else if($div == 'G') {
            $treeid = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
        } else {
            $treeid = "";
        }
        $query = " SELECT * FROM $tablename "
                . "WHERE activity_type_id = $activity_type_id AND deleted = '0' AND created_by != '2' ".$clause.$treeid;
        $rowdata = $this->select($query);
        return count($rowdata);    
    }
    
    public function getSiteAuditCount($resultfor,$activity_type_id,$type,$colname,$tablename,$val1=null,$val2=null) {
        if( $resultfor == 1 ) {
            $clause = " AND $colname LIKE '".$val1."%'";
        } else if( $resultfor == 2 ) {
            $lastdate = date("Y-m-t",strtotime($val1));
            $clause = " AND $colname BETWEEN '".$val2."-01' AND '".$lastdate."'";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $lastdate = date("Y-m-t",strtotime($exp[1]));
            $clause = " AND $colname BETWEEN '".$exp[0]."-01' AND '".$lastdate."'";
        }    
        
        if($type == 'D') {
            $treeid = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
        } else if($type == 'G') {
            $treeid = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
        } else {
            $treeid = "";
        }
        
        $query = " SELECT * FROM $tablename "
                . "WHERE activity_type_id = $activity_type_id AND deleted = '0' AND created_by != '2' ".$clause.$treeid;
        $rowdata = $this->select($query);
     
        return count($rowdata);
        
    }
    public function getSiteAuditCountNew($resultfor,$activity_type_id,$type,$colname,$tablename,$val1=null,$val2=null) {
        if( $resultfor == 1 ) {
            $clause = " AND $colname LIKE '".$val1."%'";
        } else if( $resultfor == 2 ) {
            $lastdate = date("Y-m-t",strtotime($val1));
            $clause = " AND $colname BETWEEN '".$val2."-01' AND '".$lastdate."'";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $lastdate = date("Y-m-t",strtotime($exp[1]));
            $clause = " AND $colname BETWEEN '".$exp[0]."-01' AND '".$lastdate."'";
        }    
        
        if($type == 'DExHD') {
            $treeid = " AND (tree_division_id NOT LIKE '249-3-5-19%' AND tree_division_id NOT LIKE '249-3-5-10%' AND tree_division_id NOT LIKE '249-3-6%' AND tree_division_id NOT LIKE '249-3-7%' AND tree_division_id NOT LIKE '249-3-8%' AND tree_division_id NOT LIKE '249-4-168%' AND tree_division_id NOT LIKE '249-2%' AND tree_division_id NOT LIKE '249-3-5-9%')";
        } else if($type == 'G') {
            $treeid = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
        } else if($type == 'D') {
            $treeid = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
        } else if( $type == "DHD" ) {
            $treeid = " AND (tree_division_id LIKE '249-3-5-19%')";
        } else if( $type == "MM" ) {
            $treeid = " AND (tree_division_id LIKE '249-3-5-10%')";
        } else if( $type == "MHT" ) {
            $treeid = " AND (tree_division_id LIKE '249-3-5-9%')";
        } else if( $type == "SC" ) {
            $treeid = " AND (tree_division_id LIKE '249-4-168%')";
        }else if( $type == "SS" ) {
            $treeid = " AND (tree_division_id LIKE '249-3-7%')";
        }else if( $type == "ACG" ) {
            $treeid = " AND (tree_division_id LIKE '249-3-8%')";
        }else if( $type == "TD" ) {
            $treeid = " AND (tree_division_id LIKE '249-3-6%')";
        }else if( $type == "MCMREPORTDExHD" ) {
            $treeid = " AND (tree_division_id NOT LIKE '249-3-5-19%' AND tree_division_id NOT LIKE '249-2%' )";
        }else {
            $treeid = "";
        }
        
        $query = " SELECT * FROM $tablename "
                . "WHERE activity_type_id = $activity_type_id AND deleted = '0' AND created_by != '2' ".$clause.$treeid;
        $rowdata = $this->select($query);
     
        return count($rowdata);
    }
    public function getTrainingDataCount($resultfor,$station,$pcatid,$val1=null,$setType) {
        if( $resultfor == 1 ) {
            $clause = " AND act.from_date LIKE '".$val1."%'";
        } 
        if( $resultfor == 2 ) {
            $clause = " AND act.from_date BETWEEN $val1";
        } 
        $set = "";
        if( $pcatid == 3 ) {
            if($setType == 'W') {
                $set = " AND apm.designation='Workman'";
            }
            if($setType == 'S') {
                $set = " AND apm.designation='Supervisor'";
            }
        }
        if( $pcatid == 4 ) {
            if($setType == 'W') {
                $set = " AND apm.designation_2='Workman'";
            }
            if($setType == 'S') {
                $set = " AND apm.designation_2='Supervisor'";
            }
        }
       
        if($station == 'D') {
            $treeid = " AND (adm.tree_division_id LIKE '249-3%' OR adm.tree_division_id LIKE '249-4%' OR adm.tree_division_id LIKE '250-252%')";
        } else if($station == 'G') {
            $treeid = " AND (adm.tree_division_id LIKE '249-2%' OR adm.tree_division_id LIKE '250-251%')";
        }else if($station == 'BBGS') {
            $treeid = " AND (adm.tree_division_id LIKE '249-2-181%' OR adm.tree_division_id LIKE '250-251%')";
        }else if($station == 'SGS') {
            $treeid = " AND (adm.tree_division_id LIKE '249-2-183%' OR adm.tree_division_id LIKE '250-251%')";
        } else {
            $treeid = "";
        }
        
        
        $query = " SELECT sum(apm.no_of_emp) AS no_of_emp FROM activity act "
                ." LEFT JOIN activity_participants_mapping apm ON apm.activity_id=act.id"
                ." LEFT JOIN activity_division_mapping adm ON adm.activity_id=act.id"
                ." WHERE act.activity_type_id='3' AND apm.participant_cat_id = '$pcatid' AND act.deleted='0' AND act.created_by!='2' ".$clause.$set.$treeid;       
        
        $rdata   = $this->select($query);
        $rowdata = $rdata[0]->no_of_emp;
        return $rowdata;    
    }
    public function get_incident_category_wise_count($resultfor,$inccatid,$val1=null,$val2=null) {
        $clause = " incident_category_id='$inccatid' AND deleted='0' AND created_by!='2'";
        if( $resultfor == 1 ) {
            $clause .= " AND date_of_incident LIKE '".$val1."%'";
        } else if( $resultfor == 2 ) {
            $lastdate = date("Y-m-t",strtotime($val1));
            $clause .= " AND date_of_incident BETWEEN '".$val2."-01' AND '".$lastdate."'";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $lastdate = date("Y-m-t",strtotime($val2));
            $clause .= " AND date_of_incident BETWEEN '".$val1."-01' AND '".$lastdate."'";
        } else if( $resultfor == 4 ) {
            $exp=explode(",",$val1);
            $lastdate = date("Y-m-t",strtotime($exp[1]));
            $clause .= " AND date_of_incident BETWEEN '".$exp[0]."-01' AND '".$lastdate."'";
        }    
        $query = " SELECT id FROM incident WHERE".$clause;
        $rowdata = $this->select($query);
        return count($rowdata);   
    }
    public function get_incident_category_wise_count_new($resultfor,$inccatid,$val1=null,$val2=null,$divtype=null) {
        $clause = " incident_category_id='$inccatid' AND deleted='0' AND created_by!='2'";
        if( $resultfor == 1 ) {
            $clause .= " AND date_of_incident LIKE '".$val1."%'";
        } else if( $resultfor == 2 ) {
            $lastdate = date("Y-m-t",strtotime($val1));
            $clause .= " AND date_of_incident BETWEEN '".$val2."-01' AND '".$lastdate."'";
        } else if( $resultfor == 3 ) {
            $exp=explode(",",$val1);
            $lastdate = date("Y-m-t",strtotime($val2));
            $clause .= " AND date_of_incident BETWEEN '".$val1."-01' AND '".$lastdate."'";
        } else if( $resultfor == 4 ) {
            $exp=explode(",",$val1);
            $lastdate = date("Y-m-t",strtotime($exp[1]));
            $clause .= " AND date_of_incident BETWEEN '".$exp[0]."-01' AND '".$lastdate."'";
        }  
        
        $newclause = "";
        if( !empty($divtype) ) {
            if( $divtype == "G" ) {
                $newclause = " AND (tree_division_id LIKE '249-2%' OR tree_division_id LIKE '250-251%')";
            } else if( $divtype == "D" ) {
                $newclause = " AND (tree_division_id LIKE '249-3%' OR tree_division_id LIKE '249-4%' OR tree_division_id LIKE '250-252%')";
            }
        }
        
        $query = " SELECT id FROM actualtarget_incident_view WHERE".$clause.$newclause;
        $rowdata = $this->select($query);
        return count($rowdata);   
    }
    
    public function get_safety_initiative_count($activity_type_id,$selected_month_yr) {
        $clause = " deleted='0' AND created_by!='2' "
                . " AND activity_type_id='".$activity_type_id."' AND activity_date LIKE '$selected_month_yr%'";
        $query = " SELECT id,tree_division_id FROM activity_view WHERE ".$clause;
        $rowdata = $this->select($query);
        return $rowdata;  
    }


    public function get_site_audit_score($setype,$resultfor,$arrvalue,$val1=null,$val2=null) {
        
        $clause = " AND set_type='$setype' ";
        if( $resultfor == 1 ) {
            $exp=explode(",",$val1);
            $datval = date("Y-m-t",strtotime($exp[1]));
            $clause .= " AND date_of_audit BETWEEN '".$exp[0]."-01' AND '".$datval."'";
        } else if( $resultfor == 2 ) {
            $datval = date("Y-m-t",strtotime($val1));
            $clause .= " AND date_of_audit BETWEEN '".$val2."-01' AND '".$datval."'";
        }        
        $query= "SELECT * FROM actualtarget_audit_view WHERE deleted='0' AND created_by!='2'".$clause;   
        $rowdata = $this->select($query);
        $totaldiv = $totScore = 0;
        $ReturnData = array();
        
        if( !empty($rowdata) ) {
           
            foreach( $rowdata as $row ) {
                 $val=0;
                $tree_division_id_arr = ($row->tree_division_id != "") ? explode("-", $row->tree_division_id) : array();  
                for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                    if ($tree_division_id_arr[$i] == "")
                        continue;
                    if (is_numeric($tree_division_id_arr[$i])) {
                        
                        if(in_array($tree_division_id_arr[$i], $arrvalue)) {
                            $val=1;
                        }
                    }
                }
                
                if($val == 1) {
                    
                    $score = $row->avg_mark ? $row->avg_mark : 0;
                    $totaldiv+=1;
                    $totScore = $totScore + $score;
                }
            }
            
        }
        if($totScore != 0 && $totaldiv != 0){
          $sScore = $totScore/$totaldiv;
        }else{ $sScore = 0; }
        
        $ReturnData["totaldiv"] = $totaldiv;
        $ReturnData["score"]= $sScore;
      
        return $ReturnData; 
      
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getTreeDivisionId($id){
        $clause = ($id!="") ? " id=".$id : 0;
        $query = " SELECT tree_division_id FROM actualtarget_audit_view WHERE ".$clause;
        $rowdata = $this->select($query);
        return ($rowdata);        
    }
    /**
     * created by anima
     * modified by pallab and anima
     * reason 
     * @param type string
     * @param type date
     * @param type int
     * @return array()
     */
    public function getAuditData($setype,$date,$treeid){
        
        
        $ReturnData = array();
        $clause = " deleted='0' AND created_by!='2' AND set_type = '$setype' AND date_of_audit BETWEEN ".$date;
        $query = "SELECT * FROM actualtarget_audit_view WHERE ".$clause;
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
                   @$newval = $exp[3]; 
                   if(is_array($treeid)) {
                   if(in_array($newval, $treeid)) {
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
    public function getAuditDataOrherDeptType1($setype,$date,$treeid){
        $ReturnData = array();
        $clause = " AND set_type = '$setype' AND date_of_audit BETWEEN ".$date;
        $query = "SELECT * FROM actualtarget_audit_view WHERE deleted='0' AND created_by!='2' ".$clause; 
        
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
                   @$newval = $exp[2]; 
                   if($newval == $treeid) {
                      $totaldiv += 1;
                      $totScore = $totScore + $score;
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
    /**
     * created by sumit
     * modified by anima and pallab
     * reason 
     * @param type string
     * @param type date
     * @param type int
     * @return array()
     */
     public function getAuditDataODSCT2R($setype, $date, $treeid){
        $ReturnData = array();
        $clause = " deleted='0' AND created_by!='2' AND set_type = '$setype' AND date_of_audit BETWEEN ".$date;
        $query = "SELECT * FROM actualtarget_audit_view WHERE ".$clause; 
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
                   @$newval = $exp[2]; 
                   if($newval == $treeid) {
                      $totaldiv += 1;
                      $totScore = $totScore + $score;
                   }
            }
        }
        if($totScore != 0 && $totaldiv != 0){
          $sScore = $totScore/$totaldiv;
        }else{ $sScore = 0; }
        
        $ReturnData["totaldiv"] = $totaldiv;
        $ReturnData["score"]= $sScore;
        return $ReturnData; 
    }
    /**
     * created by anima
     * @param type string
     * @param type year
     * @param type int
     * @param type int
     * @param type int
     * @return array()
     */
    public function getReportedAccidentStatistics($setype,$year,$treeid,$inc_category_id,$currentmonth = null){
        $ReturnData = array();
        
        if(!empty($currentmonth)) {
        $clause = " deleted='0' AND created_by!='2' AND set_type = '$setype' AND incident_category_id='$inc_category_id' AND date_of_incident LIKE '".$year."%' ";
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause;    
        } else if($setype == "ALL_SET"){
        $clause = " deleted='0' AND created_by!='2' AND incident_category_id='$inc_category_id' AND date_of_incident BETWEEN ".$year;
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause; 
        }else{
        $clause = " deleted='0' AND created_by!='2' AND set_type = '$setype' AND incident_category_id='$inc_category_id' AND date_of_incident BETWEEN ".$year;
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause;    
        }
        $totaldiv = 0;      
        $rowdata = $this->select($query);
        $flag =0;
       
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
                   $asd=substr($nm,'0','-1');
                   $exp = explode("-",$asd);
                
                   if(in_array($treeid,$exp)) {
                      $totaldiv += 1;
                   }
                  
                   
            }
            
        }
        return $totaldiv; 
    }
    /**
     * created by anima
     * @param type string
     * @param type year
     * @param type int
     * @param type int
     * @param type int
     * @return array()
     */
    public function getReportedAccidentStatistics_cur($setype,$year,$treeid,$inc_category_id,$currentmonth = null,$incident_type = null,$darray=array()){
        $ReturnData = array();
        
        if(!empty($currentmonth)) {
        $clause = " deleted='0' AND created_by!='2' AND set_type = '$setype' AND incident_category_id='$inc_category_id' AND incident_type_id='$incident_type' AND date_of_incident LIKE '".$year."%' ";
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause;    
        } else if($setype == "ALL_SET"){
        $clause = " deleted='0' AND created_by!='2' AND incident_category_id='$inc_category_id' AND incident_type_id='$incident_type' AND date_of_incident BETWEEN ".$year;
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause; 
        }else{
        $clause = " deleted='0' AND created_by!='2' AND set_type = '$setype' AND incident_category_id='$inc_category_id' AND incident_type_id='$incident_type' AND date_of_incident BETWEEN ".$year;
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause;    
        }        
        $totaldiv = 0;      
        $rowdata = $this->select($query);
        $flag =0;
       
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
                   $asd=substr($nm,'0','-1');                   
                   $exp = explode("-",$asd);                    
                   /*if(in_array($treeid,$exp)) {
                      $totaldiv += 1;                       
                   }*/
                    if (($key = array_search(4, $darray)) !== false) {
                        unset($darray[$key]);
                    }
                    if($treeid==4){
                        $ct=0;
                        if(in_array($treeid,$exp)) {
                          foreach($exp as $v){
                           if(in_array($v,$darray)) {
                                $ct=1;
                                break;
                            }   
                          }
                          if($ct==0){
                            $totaldiv += 1;  
                          }
                        }
                    }else{
                        if(in_array($treeid,$exp)) {
                            $totaldiv += 1;                       
                        }  
                    }
                   
                  
                   
            }
            
        }
        return $totaldiv; 
    }
    /**
     * created by anima
     * @param type string
     * @param type year
     * @param type int
     * @param type int
     * @param type int
     * @return array()
     */
    public function getReportedAccidentStatistics_pfy($setype,$year,$treeid,$inc_category_id,$darray=array()){
        $ReturnData = array();
        
        if(!empty($currentmonth)) {
        $clause = " deleted='0' AND created_by!='2' AND set_type = '$setype' AND incident_category_id='$inc_category_id' AND incident_type_id IS NULL AND date_of_incident LIKE '".$year."%' ";
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause;    
        } else if($setype == "ALL_SET"){
        $clause = " deleted='0' AND created_by!='2' AND incident_category_id='$inc_category_id' AND incident_type_id IS NULL AND date_of_incident BETWEEN ".$year;
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause; 
        }else{
        $clause = " deleted='0' AND created_by!='2' AND set_type = '$setype' AND incident_category_id='$inc_category_id' AND incident_type_id IS NULL AND date_of_incident BETWEEN ".$year;
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause;    
        }
        $totaldiv = 0;      
        $rowdata = $this->select($query);
        $flag =0;
       
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
                   $asd=substr($nm,'0','-1');
                   $exp = explode("-",$asd);
                
                   /*if(in_array($treeid,$exp)) {
                      $totaldiv += 1;
                   }*/
                   if (($key = array_search(4, $darray)) !== false) {
                        unset($darray[$key]);
                    }
                    if($treeid==4){
                        $ct=0;
                        if(in_array($treeid,$exp)) {
                          foreach($exp as $v){
                           if(in_array($v,$darray)) {
                                $ct=1;
                                break;
                            }   
                          }
                          if($ct==0){
                            $totaldiv += 1;  
                          }
                        }
                    }else{
                        if(in_array($treeid,$exp)) {
                            $totaldiv += 1;                       
                        }  
                    }
                   
            }
            
        }
        return $totaldiv; 
    }
    
    public function getPPEAuditData($set_type,$date,$treeid){
       $ReturnData = array();
       $clause = " deleted='0' AND created_by!='2' AND set_type = '$set_type' AND date_of_audit BETWEEN ".$date;
       $query = "SELECT * FROM actualtarget_ppeaudit_view WHERE ".$clause;
       $totaldiv = $totScore = 0;
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
                   $exp = explode("-",$asd);
                   if(in_array($treeid,$exp)) {
                      $totaldiv += 1;
                      $totScore = $totScore + $score;
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
    public function getSITEAuditData($set_type,$date,$treeid){
       $ReturnData = array();
       $clause = " deleted='0' AND created_by!='2' AND set_type = '$set_type' AND date_of_audit BETWEEN ".$date;
       $query = "SELECT * FROM actualtarget_audit_view WHERE".$clause;
       $totaldiv = $totScore = 0;
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
                   $exp = explode("-",$asd);
                  
                   if(in_array($treeid,$exp)) {
                      $totaldiv += 1;
                      $totScore = $totScore + $score;
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
    
    
    public function getAuditDataforeSurveillance($setype,$resultfor,$date,$treeid){
        $ReturnData = array();
        if( $resultfor == 1 ) {
            $clause = " AND date_of_audit LIKE '$date%'";
        } else {
           $clause = " AND  date_of_audit BETWEEN $date"; 
        }
        $where_clause = " deleted='0' AND created_by!='2' AND set_type = '$setype' ".$clause;
        $query = "SELECT * FROM actualtarget_audit_view WHERE ".$where_clause;
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
                   @$newval = $exp[2]; 
                   if(in_array($treeid, $exp)) {
                      $totaldiv += 1;
                      $totScore = $totScore + $score;
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
    /**
     * created by anima
     * modified by anima(14032018 defined setype param)
     * @param type int
     * @param type date
     * @param type int
     * @return array()
     */
    public function getAuditCountforDist($setype,$resultfor,$date,$tree_id) {
        if($resultfor == 1) {
            $clause = " AND date_of_audit LIKE '$date%' AND tree_division_id LIKE '%$tree_id%'";
        } else if($resultfor == 2) {
           $clause = " AND  date_of_audit BETWEEN $date AND tree_division_id LIKE '%$tree_id%'"; 
        }
            $where_clause = " set_type = '$setype' AND deleted='0' AND created_by!='2'".$clause;
            $query = "SELECT * FROM actualtarget_audit_view WHERE".$where_clause; 
            $rowdata = $this->select($query);
            return $rowdata;
    }
    /**
     * created by anima
     * modified by pallab
     * @param type string
     * @param type int
     * @param type date
     * @param type int
     * @return array()
     */
    public function getAuditCount($setype,$resultfor,$date,$tree_id) {
        
        $ReturnData = array();
        $value1 = $value2 = 0;
        if( $resultfor == 1 ) {
            $clause = " AND date_of_audit LIKE '$date%' AND tree_division_id LIKE '$tree_id%'";
        } else {
           $clause = " AND  date_of_audit BETWEEN $date AND tree_division_id LIKE '$tree_id%'"; 
        }
        $where_clause = " deleted='0' AND created_by!='2' AND set_type = '$setype' ".$clause;
        $query = "SELECT * FROM actualtarget_audit_view WHERE ".$where_clause;
        $rowdata = $this->select($query);
        foreach($rowdata as $key => $rowdata ) {
        $value1 += 1;
        $value2 += $rowdata->avg_mark;
        }
        if($value2 != 0 || $value1 != 0){
          $sScore = $value2/$value1;
        }else{ $sScore = 0; }
        
         @$ReturnData["TOTAL_COUNT"] += $value1;
         @$ReturnData["TOTAL_AVG"] += round($sScore,2);
        
        return $ReturnData;
    }
    public function getAuditCountCallCentre($setype,$resultfor,$date,$tree_id,$var) {
        
        $ReturnData = array();
        $value1 = $value2 = 0;
        $cls = "";
        if($var == 1) {
            $cls = " AND tree_division_id NOT LIKE '%call_center%'";
        } else {
            $cls = " AND tree_division_id LIKE '%call_center%'";
        }
        if( $resultfor == 1 ) {
            $clause = " AND date_of_audit LIKE '$date%' AND  (tree_division_id LIKE '$tree_id%' $cls)";
        } else {
           $clause = " AND  date_of_audit BETWEEN $date AND (tree_division_id LIKE '$tree_id%' $cls)"; 
        }
        $where_clause = " deleted='0' AND created_by!='2' AND set_type = '$setype' ".$clause;
        $query = "SELECT * FROM actualtarget_audit_view WHERE ".$where_clause;
        $rowdata = $this->select($query);
        foreach($rowdata as $key => $rowdata ) {
        $value1 += 1;
        $value2 += $rowdata->avg_mark;
        }
        if($value2 != 0 || $value1 != 0){
          $sScore = $value2/$value1;
        }else{ $sScore = 0; }
        
         @$ReturnData["TOTAL_COUNT"] += $value1;
         @$ReturnData["TOTAL_AVG"] += round($sScore,2);
        
        return $ReturnData;
    }
    public function getIncidentCount($resultfor,$date,$tree_id) {
        if( $resultfor == 1 ) {
            $clause = " AND date_of_incident LIKE '$date%' AND tree_division_id LIKE '$tree_id%'";
        } else {
           $clause = " AND  date_of_incident BETWEEN $date AND tree_division_id LIKE '$tree_id%'"; 
        }
        $where_clause = " deleted='0' AND created_by!='2' ".$clause." GROUP BY incident_category_id";
        $query = "SELECT *,count(id) as rowid FROM actualtarget_incident_view WHERE ".$where_clause;
        
        $rowdata = $this->select($query);
        $data = array();
        foreach( $rowdata as $key => $value) {
          $data[$value->incident_category_id]=  $value->rowid;
        }
        return $data;
    }
    
    public function getAuditCountGraph($date, $tree_id, $month_basis = null){
        
        $ReturnData = array();
        $data = array();
        if($month_basis != null OR $month_basis == "YES"){
            $date = explode(" AND ", str_replace("'","",$date)); 
            $months = get_month_range($frmDt=$date[0], $toDt=$date[1]);
            foreach($months as $key=> $value){
                $made_date = "'".date("Y-m-"."01",strtotime($value))."' AND '".date("Y-m-d",strtotime($value))."'";
                $clause = " AND date_of_audit BETWEEN " .$made_date. " AND tree_division_id LIKE '$tree_id%'";
                $where_clause = " deleted='0' AND created_by!='2' ".$clause;
                $query = "SELECT count(*) as total, avg(avg_mark) as avg_mark FROM actualtarget_audit_view WHERE ".$where_clause;
                $rowdata = $this->select($query);
                $data[$value] = round($rowdata[0]->avg_mark,2);
               }
            $ReturnData = $data;
        }else{
                $clause = " AND date_of_audit BETWEEN " .$date. " AND tree_division_id LIKE '$tree_id%'";
                $where_clause = " deleted='0' AND created_by!='2' ".$clause;
                $query = "SELECT count(*) as total, avg(avg_mark) as avg_mark FROM actualtarget_audit_view WHERE ".$where_clause;
                $rowdata = $this->select($query);
                $data[$date] = round($rowdata[0]->avg_mark,2);
                $ReturnData = $data;
        }
        return $ReturnData;
    }
    
    public function getGenerationCount($resultfor,$key,$activity_type_id,$treenode,$tablename,$colname,$date) {
        $res = 0 ;
        if( $resultfor == 1 ) {
            $where_clause = " AND created_by!='2' AND deleted='0' AND $colname LIKE '$date%'";
        } else {
            $where_clause = " AND created_by!='2' AND deleted='0' AND $colname BETWEEN $date";
        }
        if( $activity_type_id == 1 || $activity_type_id == 2 || $activity_type_id == 4) {
            $query = "SELECT * FROM ".$tablename." WHERE activity_type_id = '$activity_type_id' AND tree_division_id LIKE '$treenode%' ".$where_clause;   
            $rowdata = $this->select($query);
            $res = count($rowdata);
        } else if($activity_type_id == 3) {
             if( $key == 3 ) {
                 $clause = " AND participant_cat_id='4' GROUP BY participant_cat_id";
             } else if( $key == 4 ) {
                 $clause = " AND participant_cat_id='3' AND  designation='Workman' GROUP BY designation,participant_cat_id";
             } else if( $key == 5 ) {
                 $clause = " AND participant_cat_id='3' AND  designation='Supervisor' GROUP BY designation,participant_cat_id";
             } else if( $key == 6 ) {
                 $clause = " AND participant_cat_id='5' GROUP BY participant_cat_id";
             }
            $query = "SELECT SUM(no_of_emp) as emptotal FROM ".$tablename." WHERE activity_type_id = '$activity_type_id' AND tree_division_id LIKE '$treenode%' ".$where_clause.$clause;   
            
            $rowdata = $this->select($query);
            if(!empty($rowdata)){
            $res = ($rowdata[0]->emptotal > 0 ) ? $rowdata[0]->emptotal : 0;
            }
            
        } else if($activity_type_id == 5) {
            if( $key == 7 ) {
                $clause = " AND set_type='P-SET'";
            } else if( $key == 8 ) {
                $clause = " AND set_type='C-SET'";
            }
            $query = "SELECT * FROM ".$tablename." WHERE activity_type_id = '$activity_type_id' AND tree_division_id LIKE '$treenode%' ".$where_clause.$clause;   
            $rowdata = $this->select($query);
            $res = count($rowdata);
        } else if($activity_type_id == 6) {
            if( $key == 9 ) {
                $clause = " AND incident_category_id='2'";
            } else if($key==10){
                $clause = " AND incident_category_id='3'";
            } else if($key == 11) {
                $clause = " AND incident_category_id='4'";
            } else if($key == 12) {
                $clause = " AND incident_category_id='1'";
            }
            $query = "SELECT * FROM ".$tablename." WHERE activity_type_id = '$activity_type_id' AND tree_division_id LIKE '$treenode%' ".$where_clause.$clause;   
            $rowdata = $this->select($query);
            $res = count($rowdata);
        } else if($activity_type_id == 8) {
            $query = "SELECT * FROM ".$tablename." WHERE activity_type_id = '$activity_type_id' AND tree_division_id LIKE '$treenode%' ".$where_clause;   
            $rowdata = $this->select($query);
            $res = count($rowdata);
        }
        return $res;        
    }
    /**
     * created by sumit
     * reason : to get contractor audit count
     * @param type date
     * @param type string
     * @param type string
     * @return array()
     */
    public function get_contractor_sudit_count($date, $tree_id, $set_type=null) { 
        
        $ReturnData = array();
        $value1 = $value2 = 0;
        $arrdate = "";
        $query = "SELECT * FROM actualtarget_audit_view WHERE deleted='0' AND created_by!='2' AND set_type ='".$set_type."' AND  date_of_audit BETWEEN ".$date." AND ( tree_division_id LIKE '%".$tree_id["identification_code"]."%' OR tree_division_id LIKE '%".$tree_id["id"]."%' )";
        $rowdata = $this->select($query);
        
        foreach($rowdata as $key => $rowdata ) {
         $value1 += 1;
         $value2 += $rowdata->avg_mark;
         $arrdate = $rowdata->date_of_audit;
        }
        if($value2 != 0 || $value1 != 0){
          $sScore = $value2/$value1;
        }else{ $sScore = 0; }
        
         @$ReturnData["TOTAL_COUNT"] += $value1;
         @$ReturnData["TOTAL_AVG"] += round($sScore,2);
         @$ReturnData["CHECK_DATE"] = $arrdate;
        return $ReturnData;
    }
/**
 * created by anima
 * @param type $set_type
 * @param type $date_range
 * @param type $treeid
 * @return int
 */        
    public function suraksha_barta_site_audit($set_type,$date_range,$treeid){
       $ReturnData = array();
       $daterange_qry = ($date_range !="") ? ' AND date_of_audit BETWEEN '.$date_range : ""; 
       $clause = " deleted='0' AND created_by!='2' AND set_type = '$set_type' ".$daterange_qry;
       $query = "SELECT * FROM actualtarget_audit_view WHERE".$clause;
       $totaldiv = $totScore = 0;
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
                   $exp = explode("-",$asd);
                  
                   if(in_array($treeid,$exp)) {
                      $totaldiv += 1;
                      $totScore = $totScore + $score;
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
    /**
     * created by pallab
     * @param type $set_type
     * @param type $date_range
     * @param type $inc_category_id
     * @param type $treeid
     * @return int
     */
    public function suraksha_barta_incident($set_type,$date_range,$inc_category_id,$treeid){
        $totaldiv = array();   
      if(!empty($date_range)) {   
        $clause = " deleted='0' AND created_by!='2' AND set_type = '$set_type' AND incident_category_id='$inc_category_id' AND date_of_incident BETWEEN ".$date_range ;
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause;      
        }
        
        $totaldiv = 0;      
        $rowdata = $this->select($query);
        $flag =0;
       
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
                   $asd=substr($nm,'0','-1');
                   $exp = explode("-",$asd);
                
                   if(in_array($treeid,$exp)) {
                      $totaldiv += 1;
                   }
            
            }
            
        }
        return $totaldiv;  
 
    }
    
    
    public function get_incident_monthwise_statistics($type,$month_value,$incident_type_id,$treeid) {
        $dateclause = "";
        if($type == 1) {
            $dateclause = " AND date_of_incident LIKE '$month_value%'";
        }
        if($type == 2) {
            $dateclause = " AND date_of_incident BETWEEN $month_value";
        }
        $clause = " deleted='0' AND created_by!='2' AND incident_category_id='$incident_type_id' ".$dateclause;
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause;  
       
        $rowdata = $this->select($query);
        $totaldiv =0;
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
                   $asd=substr($nm,'0','-1');
                   $exp = explode("-",$asd);
                   if(in_array(trim($treeid),$exp)) {
                      $totaldiv += 1;
                   }
            }
            
        }
        return $totaldiv;  
    }
    public function get_activity_monthwise_statistics($type,$month_value,$activity_type_id,$treeid,$id) {
       
        $dateclause = "";
        if($type == 1) {
                $monthyear = explode("-",$month_value);
                $year = $monthyear[0];
                $month = $monthyear[1];
            if($activity_type_id == 5){
                $dateclause = " AND date_of_audit LIKE '$month_value%'";
            }else if($activity_type_id == 6){
                $dateclause = " AND date_of_incident LIKE '$month_value%'";
            }else if($activity_type_id == 7){
                $dateclause = " AND date_of_audit LIKE '$month_value%'";
            }else if($activity_type_id == 8){
                $dateclause = " AND activity_month LIKE '$month%' AND activity_year LIKE '$year%'";
            }else{
                $dateclause = " AND activity_date LIKE '$month_value%'";
            }
        }
        if($type == 2) {
                $twoYear = explode("AND",$month_value);
                $firstYear = $twoYear[0];
                $firstYear1 = explode("-",$firstYear);
                $firstYear2 = $firstYear1[0];
                $firstYear3 = substr($firstYear2,1);
                
                $secondYear = $twoYear[1];
                $secondYear1 = explode("-",$secondYear);
                $secondYear2 = $secondYear1[0];
                $secondYear22 = $secondYear1[1];
                $secondYear3 = substr($secondYear2,3);
                
                $abc = getAllYTMYear("$firstYear3-$secondYear3",$secondYear22);
                $imp=implode("','",$abc);
                
                
            if($activity_type_id == 5){
                $dateclause = " AND date_of_audit BETWEEN $month_value";
            }else if($activity_type_id == 6){
                $dateclause = " AND date_of_incident BETWEEN $month_value";
            }else if($activity_type_id == 7){
                $dateclause = " AND date_of_audit BETWEEN $month_value";
            }else if($activity_type_id == 8){
                $dateclause = " AND CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) IN('".$imp."') ";
            }else{
                $dateclause = " AND activity_date BETWEEN $month_value";
            }
        }
        
        if($activity_type_id == 5){
            $clause = " deleted='0' AND created_by!='2' AND activity_type_id = '$activity_type_id' ".$dateclause;
            $query = "SELECT * FROM actualtarget_audit_view WHERE ".$clause."AND tree_division_id LIKE '$treeid%'";  
        }else if($activity_type_id == 6){
            $clause = " deleted='0' AND created_by!='2' AND activity_type_id = '$activity_type_id' ".$dateclause;
            $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause."AND tree_division_id LIKE '$treeid%'";
        }else if($activity_type_id == 7){
            $clause = " deleted='0' AND created_by!='2' AND activity_type_id = '$activity_type_id' ".$dateclause;
            $query = "SELECT * FROM actualtarget_ppeaudit_view WHERE ".$clause."AND tree_division_id LIKE '$treeid%'";
        }else if($activity_type_id == 8){
            $clause = " deleted='0' AND created_by!='2' AND activity_type_id = '$activity_type_id' ".$dateclause;
            $query = "SELECT * FROM actualtarget_safetyobs_view WHERE ".$clause."AND tree_division_id LIKE '$treeid%'";//show($query);
        }else{
            $clause = " deleted='0' AND created_by!='2' AND activity_type_id = '$activity_type_id' ".$dateclause;
            $query = "SELECT * FROM actualtarget_view WHERE ".$clause."AND tree_division_id LIKE '$treeid%'";  
        }
       // showPre($query);
        
            $datacount =0;
            if($id == 4){
                $asd ='';
                $rowdata = $this->select($query); 
                if($activity_type_id == 8){
                    foreach( $rowdata as $row ) {
                        @$activitycount += $row->activity_count;
                        $tree_division_id_arr = ($row->tree_division_id != "") ? explode("-", $row->tree_division_id) : array();
                        $treedata = array_values(array_filter($tree_division_id_arr));
                       $nm='';
                        for ($i = 0; $i < count($treedata); $i++) {
                                if (is_numeric($tree_division_id_arr[$i])) {
                                        $nm.= $tree_division_id_arr[$i]."-";

                               }
                        }
                       $asd=substr($nm,'0','-1');
                       if (stripos($asd,$treeid."-168") !== false) {
                            $datacount += $row->activity_count;
                        }    
                    }
                    @$rowcount = $activitycount-$datacount;
                    
                }else{
                    foreach( $rowdata as $row ) {
                        $tree_division_id_arr = ($row->tree_division_id != "") ? explode("-", $row->tree_division_id) : array();
                        $treedata = array_values(array_filter($tree_division_id_arr));
                       $nm='';
                        for ($i = 0; $i < count($treedata); $i++) {
                                if (is_numeric($tree_division_id_arr[$i])) {
                                        $nm.= $tree_division_id_arr[$i]."-";

                               }
                        }
                       $asd=substr($nm,'0','-1');
                       if (stripos($asd,$treeid."-168") !== false) {
                            $datacount += 1;
                        }    
                    }
                    @$rowcount = count($rowdata)-$datacount;
                }
            }else{
                $rowdata = $this->select($query); 
                if($activity_type_id != 8){
                    $rowcount = count($rowdata);
                }else{
                        $rowdata = $this->select($query); 
                        $rowcount = 0;
                        if(count($rowdata) > 0 ) {
                            foreach($rowdata as $row) {
                                $rowcount +=$row->activity_count;
                            }
                        }
                }
            }
         
        return $rowcount;
    }
    public function get_incident_accident_statistics_gendist($monthvalue,$incident_type_id,$type) {
       
        $clause = " deleted='0' AND created_by!='2' AND incident_category_id='$incident_type_id' AND set_type='$type' AND date_of_incident LIKE '$monthvalue%' ";
        $query = "SELECT * FROM actualtarget_incident_view WHERE ".$clause;  
        $rowdata = $this->select($query);
        $datacount = count($rowdata);
        return $datacount;  
    }
    public function get_latest_incident_date_fatal($incDate,$treeid,$inc_category_id){ 
       $ReturnData = array();
        
        $clause = " deleted='0' AND created_by!='2' AND incident_category_id='$inc_category_id' ";
        $query = " SELECT * FROM actualtarget_incident_view WHERE ".$clause;    
        $rowdata = $this->select($query);//show($query);
               
        $totaldiv = 0;      
        //$rowdata = $this->select($query);
        $flag =0;
        $id="";        
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
                   $asd=substr($nm,'0','-1');                   
                   $exp = explode("-",$asd);                   
                   if(in_array($treeid,$exp)) {
                       $id.= $row->id.',';
                   }
            }            
            $ids = rtrim($id,',');
            $query1 = " SELECT max(date_of_incident) AS date_of_incident FROM actualtarget_incident_view WHERE id IN (".$ids.")"; 
                       $rowdata1 = $this->select($query1);//show($query1);
                      $dateOfIncident = $rowdata1[0]->date_of_incident;
        }
        
        return $dateOfIncident;  
    }
    public function get_latest_incident_date_total($incDate,$treeid,$inc_category_id){ 
       $ReturnData = array();
       $incarr=explode(',',$inc_category_id);
       $incstr='';
       foreach ($incarr as $incd){
         $incstr.="'".$incd."',";  
       }
       $incstr = rtrim($incstr,',');
        $clause = " deleted='0' AND created_by!='2' AND incident_category_id IN ($incstr) ";
        $query = " SELECT * FROM actualtarget_incident_view WHERE ".$clause;
        $rowdata = $this->select($query);               
        $totaldiv = 0;      
        //$rowdata = $this->select($query);
        $flag =0;       
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
                   $asd=substr($nm,'0','-1');
                   $exp = explode("-",$asd);                   
                   if(in_array($treeid,$exp)) {
                       $id .= $row->id.',';                       
                   }
            }            
            $ids = rtrim($id,',');
            $query1 = " SELECT max(date_of_incident) AS date_of_incident FROM actualtarget_incident_view WHERE id IN (".$ids.") AND  CONVERT(date_of_incident, DATE)<='".$incDate."';"; 
                       $rowdata1 = $this->select($query1);//show($query1);
                      $dateOfIncident = $rowdata1[0]->date_of_incident;
        }
        
        return $dateOfIncident;  
    }
    

}

