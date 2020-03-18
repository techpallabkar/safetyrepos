<?php

class CalendarMasterDAO extends MainDao {



    public function __construct() {
        parent::__construct();
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
        
        public function getSafetycellActivity() {
            $query = "select * from safetycell_activity WHERE name!=''";
            $rowdata    = $this->select($query);
            return $rowdata;
        }
        public function alldatafromsafetycalender($financial_year_id) {
        $rowdata = array();

        $query = "SELECT * FROM safetycell_calendar WHERE financial_year_id = " . $financial_year_id;
        $rowdata = $this->select($query);
        return ($rowdata);
    }
 

}
