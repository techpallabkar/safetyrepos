<?php

class StatisticController extends MainController {

    public function __construct() {
        $this->dao = load_dao("StatisticMasterDAO");
        parent::__construct();
    }
 
    protected function index() {
        $type           = $this->bean->get_request("type");
        $allStatData    = $this->dao->getStatisticalDataByMnthYr($type);
        $statistic      = $this->dao->getStatisticMasterData($type);
        $statisticname  = $statistic[0]->name;
        $month          = $this->bean->get_request("month");
        $year           = $this->bean->get_request("year");
        $status         = $this->bean->get_request("status");
        $newarr = array();
        if ($month != "" && $year != "") {
            $updatedata = $this->dao->updateStatus($month, $year, $status, $type);
            redirect("index.php?type=" . $type);
        }
        $this->beanUi->set_view_data("allStatData", $allStatData);
        $this->beanUi->set_view_data("statisticname", $statisticname);
        $this->beanUi->set_view_data("statistictype", $type);
    }

    public function add() {
        $type               = $this->bean->get_request("type");
        $_action            = $this->bean->get_request("_action");
        $allStActivityEstb  = $alldatastat = array();
        $month = $year = "";
        $statistic = $this->dao->getStatisticMasterData($type);
        $statisticname = $statistic[0]->name;
        if ($_action == "Create") {
            $year = $this->bean->get_request("year");
            $month = $this->bean->get_request("month");
            $type = $this->bean->get_request("statype");
            $allStActivityEstb = $this->dao->getStActivityEstb($type);
            foreach ($allStActivityEstb as $rowdata) {
                $alldatastat[$rowdata->id] = $this->dao->getStatisticalData($type, $month, $year, $rowdata->id);
            }
            $this->beanUi->set_view_data("alldatastat", $alldatastat);
        }
        if ($_action == "submitData") {

            $month = $this->bean->get_request("selectedmonth");
            $year = $this->bean->get_request("selectedyear");
            $type = $this->bean->get_request("statype");
            $actestb_id = $this->bean->get_request("actestb_id");
            $arrayData = array();
            foreach ($actestb_id as $key => $rowdata) {
                $colvalue = $this->bean->get_request("colvalue" . $rowdata);
                $arrayData["statistical_master_id"] = $type;
                $arrayData["year"]      = $year;
                $arrayData["month"]     = $month;
                $arrayData["actestb_id"]= $rowdata;
                $arrayData["colone"]    = $colvalue[0];
                $arrayData["coltwo"]    = $colvalue[1];
                $arrayData["colthree"]  = $colvalue[2];
                $arrayData["colfour"]   = $colvalue[3];
                $arrayData["colfive"]   = $colvalue[4];
                $arrayData["colsix"]    = $colvalue[5];
                $arrayData["colseven"]  = $colvalue[6];
                $arrayData["coleight"]  = $colvalue[7];
                $arrayData["colnine"]   = $colvalue[8];
                
                $checkStatisticalExist = $this->dao->checkStatisticalExist($type, $month, $year, $rowdata);
                if (count($checkStatisticalExist) > 0) {
                    $arrayData["id"] = $checkStatisticalExist[0]->id;
                    $this->dao->_table = "statistical_data";
                    $insertID = $this->dao->save($arrayData);
                } else {
                    $this->dao->_table = "statistical_data";
                    $insertID = $this->dao->save($arrayData);
                }
            }
          
            $allStActivityEstb = $this->dao->getStActivityEstb($type);
            foreach ($allStActivityEstb as $rowdata) {
                $alldatastat[$rowdata->id] = $this->dao->getStatisticalData($type, $month, $year, $rowdata->id);
            }
            redirect("index.php?type=" . $type);
        }
        $action  = $this->bean->get_request("action");
        
        if ($action == "edit") {
            
            $month = $this->bean->get_request("month");
            $year = $this->bean->get_request("year");
            $type = $this->bean->get_request("type");
             $allStActivityEstb = $this->dao->getStActivityEstb($type);
            foreach ($allStActivityEstb as $rowdata) {
                $alldatastat[$rowdata->id] = $this->dao->getStatisticalData($type, $month, $year, $rowdata->id);
            }
            $this->beanUi->set_view_data("alldatastat", $alldatastat);
            
                    $this->beanUi->set_view_data("editaction", $action);
        }
        
        $this->beanUi->set_view_data("allStActivityEstb", $allStActivityEstb);
        $this->beanUi->set_view_data("statistictype", $type);
        $this->beanUi->set_view_data("selectedmonth", $month);
        $this->beanUi->set_view_data("selectedyear", $year);
        $this->beanUi->set_view_data("statisticname", $statisticname);
        $this->beanUi->set_view_data("alldatastat", $alldatastat);
    }

}
