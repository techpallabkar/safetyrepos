<?php

class CalendarController extends MainController {

    public function __construct() {
        $this->dao = load_dao("CalendarMasterDAO");

        parent::__construct();
    }

    public function calendar() {
        $allFinancialYear = $this->dao->allFinancialYear();
        $allSafetycellActivity = $this->dao->getSafetycellActivity();
        $alldatacalender = array();
        $_action = $this->bean->get_request("_action");
        if ($_action == "Create") {
            $financial_year_id_create = $this->bean->get_request("financial_year");


            $alldatacalender = $this->dao->alldatafromsafetycalender($financial_year_id_create);

            $fData = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year = $fData[0]->financial_year;



            $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("alldatacalender", $alldatacalender);
            $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
            $this->beanUi->set_view_data("financial_year", $financial_year);
        }


        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("allSafetycellActivity", $allSafetycellActivity);
    }

}
