<?php

class CalendarController extends MainController {

    public function __construct() {
        $this->reportDao = load_dao("ReportDAO");
        $this->dao = load_dao("CalendarMasterDAO");
        parent::__construct();
    }
 
    protected function index() {

        $allSafetycellActivity = $this->dao->getSafetycellActivity();
        $allFinancialYear = $this->reportDao->allFinancialYear();
		$financial_year_id = "";
		$alldatacalender  = "";
        $_action = $this->bean->get_request("_action");
        if ($_action == "Create") {
            $financial_year_id_create = $this->bean->get_request("financial_year");
            $alldatacalender = array();
            foreach ($allSafetycellActivity as $rowsData) {
                $alldatacalender[$rowsData->id] = $this->dao->alldatafromsafetycalender($financial_year_id_create, $rowsData->id);
            }
            
            $fData = $this->reportDao->allFinancialYear($financial_year_id_create);
            $financial_year = $fData[0]->financial_year;
            

            $fromsubmit = $this->bean->get_request("fromsubmit");
            if ($fromsubmit != "") {
                $this->beanUi->set_success_message("Calendar of <b> $financial_year </b> is successfully saved.");
            }

            $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("alldatacalender", $alldatacalender);
            $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
        }

        if ($_action == "submitData") {
            $arrayData = array();
            $safetycell_activity_id = $this->bean->get_request("safetycell_activity_id");
            $financial_year_id = $this->bean->get_request("financial_year_id");
            $fData = $this->reportDao->allFinancialYear($financial_year_id);
            $financial_year = $fData[0]->financial_year;

            foreach ($safetycell_activity_id as $key => $value) {
                $monthly_value = $this->bean->get_request("monthly_value" . $value);
                $arrayData["safetycell_activity_id"] = $value;
                $arrayData["financial_year_id"] = $financial_year_id;
                $arrayData["april_month"] = $monthly_value[0];
                $arrayData["may_month"] = $monthly_value[1];
                $arrayData["june_month"] = $monthly_value[2];
                $arrayData["july_month"] = $monthly_value[3];
                $arrayData["august_month"] = $monthly_value[4];
                $arrayData["september_month"] = $monthly_value[5];
                $arrayData["october_month"] = $monthly_value[6];
                $arrayData["november_month"] = $monthly_value[7];
                $arrayData["december_month"] = $monthly_value[8];
                $arrayData["january_month"] = $monthly_value[9];
                $arrayData["february_month"] = $monthly_value[10];
                $arrayData["march_month"] = $monthly_value[11];
                $checktargetExist = $this->dao->checksafetyCalendarExist($value, $financial_year_id);
                if (count($checktargetExist) > 0) {
                    $arrayData["id"] = $checktargetExist[0]->id;
                    $this->dao->_table = "safetycell_calendar";
                    $insertID = $this->dao->save($arrayData);
                } else {
                    $this->dao->_table = "safetycell_calendar";
                    $insertID = $this->dao->save($arrayData);
                }
            }
        }
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("alldatacalender", $alldatacalender);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("allSafetycellActivity", $allSafetycellActivity);
    }

}
