<?php 
class CalendarMasterDAO extends MainDao{
	
	public function __construct() {
		parent::__construct();
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
    
     public function checksafetyCalendarExist($safetycell_activity_id, $financial_year_id) {

        $clause = " WHERE safetycell_activity_id = '" . $safetycell_activity_id . "' AND  financial_year_id='" . $financial_year_id . "'";
        $query = " SELECT * FROM safetycell_calendar " . $clause;
        $rowdata = $this->select($query);
        return ($rowdata);
    }
}


