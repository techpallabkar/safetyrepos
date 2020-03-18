<?php 
class StatisticMasterDAO extends MainDao{
	
	public function __construct() {
		parent::__construct();
	}
        
        public function getStActivityEstb($id) {
            $query = "select estb.id, estb.statistical_master_id,estb.name "
                    . "from statistical_activity_establishment estb "
                    . "WHERE estb.statistical_master_id='".$id."'";
            $rowdata    = $this->select($query);
            return $rowdata;
        }

        public function getSafetycellActivity() {
            $query = "select * from safetycell_activity WHERE name!=''";
            $rowdata    = $this->select($query);
            return $rowdata;
        }
        
         public function alldatafromsafetycalender($financial_year_id, $safetycell_activity_id) {
        $rowdata = array();

        $query = "SELECT * FROM safetycell_calendar WHERE financial_year_id = " . $financial_year_id . " AND safetycell_activity_id = " . $safetycell_activity_id ;
        $rowdata = $this->select($query);
		
        return (count($rowdata) > 0 ) ? $rowdata[0] : array() ;
    }
    
     public function checkStatisticalExist($statype, $month,$year,$actestb_id) {

        $clause = " WHERE statistical_master_id = '" . $statype . "' AND  month='" . $month . "' AND year = '".$year."' AND actestb_id='".$actestb_id."'";
        $query = " SELECT * FROM statistical_data " . $clause;
        $rowdata = $this->select($query);
        return ($rowdata);
    }
    
    public function getStatisticalData($statype, $month,$year,$actestb_id) {
         $clause = " WHERE statistical_master_id = '" . $statype . "' AND  month='" . $month . "' AND year = '".$year."' AND actestb_id='".$actestb_id."'";
        $query = " SELECT * FROM statistical_data " . $clause;
        $rowdata = $this->select($query);
        
        return (count($rowdata) > 0 ) ? $rowdata[0] : array();
    }
    
    public function getStatisticalDataByMnthYr($type) {
        $query = " SELECT id,statistical_master_id,year,month,status FROM statistical_data WHERE statistical_master_id='".$type."' GROUP BY year,month";
        $rowdata = $this->select($query);
        return $rowdata;
    }
    
    public function updateStatus($month,$year,$status,$type) {
        
        $query = "UPDATE statistical_data SET status='0' WHERE statistical_master_id='$type'";
        $rowdata = $this->select($query);
        if( $status == 1 ) {
            $clause = 0;
        } else {
            $clause = 1;
        }
        $query1 = "UPDATE statistical_data SET status='$clause' WHERE statistical_master_id='$type' AND month='$month' AND year='$year'";
        $rowdata = $this->select($query1);
    }
}


