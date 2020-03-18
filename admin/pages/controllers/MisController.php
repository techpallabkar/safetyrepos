<?php

class MisController extends MainController {

    public function __construct() {
        error_reporting(E_ALL);
        $this->dao = load_dao("MisDAO");
        $this->reportdao = load_dao("ReportDAO");
        $this->activitydao = load_dao("ActivityDAO");
        parent::__construct();
        error_reporting(E_ALL);
    }

    protected function index() {
        
    }

    public function editmcmReport() {
        $mnthyr = $this->bean->get_request("mnthyr");
        $rowid = $this->bean->get_request("id");
        $mode = $this->bean->get_request("mode");
        if($mnthyr != ""){
        $postValue["month_year"] = "%".$mnthyr."%";
        $checkmcmreportexist = $this->dao->checkexistmcmreport($postValue);
        }
        $month_year = $mnthyr;
        $month_yearexp = explode("-", $month_year);
        $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
        $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
        $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
        $prevfyrange = getAllMonthYear($previous_financial_year);
        $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
        $newArr = array();
        if (count($checkmcmreportexist) > 0) {
            $fetachallmappingdata = $this->dao->fetchallmcmreport($checkmcmreportexist[0]->id);
            $arr_type = array(4, 5, 2, 6);
            foreach ($arr_type as $row) {
                foreach ($fetachallmappingdata as $key => $value) {
                    if ($value->type == $row) {
                        @$newArr[$row][$key]->id = $value->id;
                        @$newArr[$row][$key]->mcmreport_id = $value->mcmreport_id;
                        @$newArr[$row][$key]->major_activity_id = $value->major_activity_id;
                        @$newArr[$row][$key]->name = $value->name;
                        @$newArr[$row][$key]->type = $value->type;
                        @$newArr[$row][$key]->total_in_this_month = $value->total_in_this_month;
                        @$newArr[$row][$key]->target = $value->target;
                        @$newArr[$row][$key]->ytm_total = $value->ytm_total;
                        @$newArr[$row][$key]->last_year_total = $value->last_year_total;
                        @$newArr[$row][$key]->last_year_ytm = $value->last_year_ytm;
                        @$newArr[$row][$key]->remarks = $value->remarks;
                        @$newArr[$row][$key]->pset_last_year = $value->pset_last_year;
                        @$newArr[$row][$key]->pset_ytm_this_year = $value->pset_ytm_this_year;
                        @$newArr[$row][$key]->cset_last_year = $value->cset_last_year;
                        @$newArr[$row][$key]->cset_ytm_this_year = $value->cset_ytm_this_year;
                    }
                }
            }
        }
        $_action = $this->bean->get_request("_action");
        if ($_action == "mcmReportSubmit") {
            $type4 = $this->bean->get_request("type4");
            $special_activities = $this->bean->get_request("special_activities");
            $name = $this->bean->get_request("name");
            $month_year = $this->bean->get_request("month_year");
            $basicData = array();
            $basicData["special_activities"] = $special_activities;
            if (isset($_POST["B1"])) {
                $status = 1;
            } else {
                $status = 2;
            }
            $basicData["id"] = $checkmcmreportexist[0]->id;
            $basicData["status_id"] = $status;
            $this->dao->_table = "mis_mcmreport";
            $basic_data_id = $this->dao->save($basicData);
            foreach ($type4 as $key => $rowdata) {
                $remarks = $this->bean->get_request("remarks_4" . $key);
                $insertedrowid = $this->bean->get_request("insertedrowid_" . $key);
                $newArr4[$key]["id"] = $insertedrowid;
                $newArr4[$key]["remarks"] = $remarks;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr4[$key]);
            }
            redirect(page_link("mis/mcmReportlist.php"));
        }
        $this->beanUi->set_view_data("fetchalldata", $newArr);
        $this->beanUi->set_view_data("selected_month_year", $mnthyr);
        $this->beanUi->set_view_data("mnthyr", $mnthyr);
        $this->beanUi->set_view_data("current_financial_year", $current_financial_year);
        $this->beanUi->set_view_data("previous_financial_year", $previous_financial_year);
        $this->beanUi->set_view_data("rowsingledata", $checkmcmreportexist);
        $this->beanUi->set_view_data("mode", $mode);
    }
    public function editmcmReportNew() {
        $mnthyr = $this->bean->get_request("mnthyr");
        $rowid = $this->bean->get_request("id");
        $mode = $this->bean->get_request("mode");
        if($mnthyr != ""){
        $postValue["month_year"] = "%".$mnthyr."%";
        $checkmcmreportexist = $this->dao->checkexistmcmreport($postValue);
        }
        
        $month_year = $mnthyr;
        $month_yearexp = explode("-", $month_year);
        $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
        $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
        $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
        $prevfyrange = getAllMonthYear($previous_financial_year);
        $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
        $newArr = array();
        if (count($checkmcmreportexist) > 0) {
            $fetachallmappingdata = $this->dao->fetchallmcmreport($checkmcmreportexist[0]->id);
            $arr_type = array(17, 18, 19, 20, 21, 22, 23);
            foreach ($arr_type as $row) {
                foreach ($fetachallmappingdata as $key => $value) {
                    if ($value->type == $row) {
                        @$newArr[$row][$key]->id                        = $value->id;
                        @$newArr[$row][$key]->mcmreport_id              = $value->mcmreport_id;
                        @$newArr[$row][$key]->major_activity_id         = $value->major_activity_id;
                        @$newArr[$row][$key]->name                      = $value->name;
                        @$newArr[$row][$key]->type                      = $value->type;
                        @$newArr[$row][$key]->total_in_this_month       = $value->total_in_this_month;
                        @$newArr[$row][$key]->total_in_this_month_g     = $value->total_in_this_month_g;
                        @$newArr[$row][$key]->target                    = $value->target;
                        @$newArr[$row][$key]->target_g                  = $value->target_g;
                        @$newArr[$row][$key]->ytm_total                 = $value->ytm_total;
                        @$newArr[$row][$key]->ytm_total_g               = $value->ytm_total_g;
                        @$newArr[$row][$key]->last_year_total           = $value->last_year_total;
                        @$newArr[$row][$key]->last_year_total_g         = $value->last_year_total_g;
                        @$newArr[$row][$key]->last_year_ytm             = $value->last_year_ytm;
                        @$newArr[$row][$key]->last_year_ytm_g           = $value->last_year_ytm_g;
                        @$newArr[$row][$key]->remarks                   = $value->remarks;
                        @$newArr[$row][$key]->pset_last_year            = $value->pset_last_year;
                        @$newArr[$row][$key]->pset_ytm_this_year        = $value->pset_ytm_this_year;
                        @$newArr[$row][$key]->cset_last_year            = $value->cset_last_year;
                        @$newArr[$row][$key]->cset_ytm_this_year        = $value->cset_ytm_this_year;
                        @$newArr[$row][$key]->total_in_this_month_p     = $value->total_in_this_month_p;
                        @$newArr[$row][$key]->total_in_this_month_c     = $value->total_in_this_month_c;
                        @$newArr[$row][$key]->ytm_this_year_p           = $value->ytm_this_year_p;
                        @$newArr[$row][$key]->ytm_this_year_c           = $value->ytm_this_year_c;
                        @$newArr[$row][$key]->last_year_total_p         = $value->last_year_total_p;
                        @$newArr[$row][$key]->last_year_total_c         = $value->last_year_total_c;
                        @$newArr[$row][$key]->target_p                  = $value->target_p;
                        @$newArr[$row][$key]->target_c                  = $value->target_c;
                    }
                }
            }
        }
        $_action = $this->bean->get_request("_action");
        if ($_action == "mcmReportSubmit") {
            $type4 = $this->bean->get_request("type4");
            $special_activities = $this->bean->get_request("special_activities");
            $name = $this->bean->get_request("name");
            $month_year = $this->bean->get_request("month_year");
            $basicData = array();
            $basicData["special_activities"] = $special_activities;
            if (isset($_POST["B1"])) {
                $status = 1;
            } else {
                $status = 2;
            }
            $basicData["id"] = $checkmcmreportexist[0]->id;
            $basicData["status_id"] = $status;
            $this->dao->_table = "mis_mcmreport";
            $basic_data_id = $this->dao->save($basicData);
            foreach ($type4 as $key => $rowdata) {
                $remarks = $this->bean->get_request("remarks_4" . $key);
                $insertedrowid = $this->bean->get_request("insertedrowid_" . $key);
                $newArr4[$key]["id"] = $insertedrowid;
                $newArr4[$key]["remarks"] = $remarks;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr4[$key]);
            }
            redirect(page_link("mis/mcmReportlistNew.php"));
        }
        $this->beanUi->set_view_data("fetchalldata", $newArr);
        $this->beanUi->set_view_data("selected_month_year", $mnthyr);
        $this->beanUi->set_view_data("mnthyr", $mnthyr);
        $this->beanUi->set_view_data("current_financial_year", $current_financial_year);
        $this->beanUi->set_view_data("previous_financial_year", $previous_financial_year);
        $this->beanUi->set_view_data("rowsingledata", $checkmcmreportexist);
        $this->beanUi->set_view_data("mode", $mode);
    }

    public function mcmReport() {
        $_action = $this->bean->get_request("_action");
        $mnthyr = $this->bean->get_request("mnthyr");
        $rowid = $this->bean->get_request("id");
        $mode = $this->bean->get_request("mode");
        $month_year = $current_financial_year = $previous_financial_year = $submitdata = "";
        $safety_related_trainingArr = array();
        $getjobsiteArr = array();
        $getmajorActivities3 = array();
        $getmajorActivities4 = array();
        $checkmcmreportexist = array();
        if ($_action == "Create") {

            $submitdata = $this->bean->get_request("submitdata");
            $month_year = $this->bean->get_request("month_year") ? $this->bean->get_request("month_year") : "";
            if($month_year != ""){
                $postValue["month_year"] = "%".$month_year."%";
                $checkmcmreportexist = $this->dao->checkexistmcmreport($postValue);
                }
            $month_yearexp = explode("-", $month_year);
            $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
            $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $prevfyrange = getAllMonthYear($previous_financial_year);
            $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getmajorActivities = $this->dao->getMajorActivity(4);

            #########################################TYPE 1 START##################################################
            $officerTraining1 = $this->dao->getOfficerTraining(1, 5, $month_year_formatted);
            $officerTraining2 = $this->dao->getOfficerTraining(2, 5, $month_year_formatted, $financialyearstart);
            $officerTraining3 = $this->dao->getOfficerTraining(3, 5, $lastfinancialyear);

            $supervisorTraining1 = $this->dao->getSupervisorTraining(1, '3', $month_year_formatted, '', 'Supervisor');
            $supervisorTraining2 = $this->dao->getSupervisorTraining(2, '3', $month_year_formatted, $financialyearstart, 'Supervisor');
            $supervisorTraining3 = $this->dao->getSupervisorTraining(3, '3', $lastfinancialyear, '', 'Supervisor');

            $workmanTraining1 = $this->dao->getSupervisorTraining(1, '3', $month_year_formatted, '', 'Workman');
            $workmanTraining2 = $this->dao->getSupervisorTraining(2, '3', $month_year_formatted, $financialyearstart, 'Workman');
            $workmanTraining3 = $this->dao->getSupervisorTraining(3, '3', $lastfinancialyear, '', 'Workman');

            $contractualTraining1 = $this->dao->getOfficerTraining(1, 4, $month_year_formatted);
            $contractualTraining2 = $this->dao->getOfficerTraining(2, 4, $month_year_formatted, $financialyearstart);
            $contractualTraining3 = $this->dao->getOfficerTraining(3, 4, $lastfinancialyear);

            $arrTrain = array(
                23 => array(
                    "last_year_total" => $officerTraining3,
                    "target" => 0,
                    "total_in_this_month" => $officerTraining1,
                    "ytm_this_year" => $officerTraining2),
                24 => array(
                    "last_year_total" => $supervisorTraining3,
                    "target" => 0,
                    "total_in_this_month" => $supervisorTraining1,
                    "ytm_this_year" => $supervisorTraining2),
                25 => array(
                    "last_year_total" => $workmanTraining3,
                    "target" => 0,
                    "total_in_this_month" => $workmanTraining1,
                    "ytm_this_year" => $workmanTraining2),
                26 => array(
                    "last_year_total" => ($officerTraining3 + $supervisorTraining3 + $workmanTraining3),
                    "target" => 0,
                    "total_in_this_month" => ($officerTraining1 + $supervisorTraining1 + $workmanTraining1),
                    "ytm_this_year" => ($officerTraining2 + $supervisorTraining2 + $workmanTraining2)),
                27 => array(
                    "last_year_total" => $contractualTraining3,
                    "target" => 0,
                    "total_in_this_month" => $contractualTraining1,
                    "ytm_this_year" => $contractualTraining2),
            );
            $safety_related_training = $this->dao->getMajorActivity(4);

            foreach ($safety_related_training as $key => $rowdata) {
                $targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                $safety_related_trainingArr[$key] = new stdClass();
                $safety_related_trainingArr[$key]->id = $rowdata->id;
                $safety_related_trainingArr[$key]->name = $rowdata->name;
                $safety_related_trainingArr[$key]->type = $rowdata->type;
                $safety_related_trainingArr[$key]->last_year_total = $arrTrain[$rowdata->id]["last_year_total"];
                $safety_related_trainingArr[$key]->target = $targetvalue["target"];
                $safety_related_trainingArr[$key]->total_in_this_month = $arrTrain[$rowdata->id]["total_in_this_month"];
                $safety_related_trainingArr[$key]->ytm_this_year = $arrTrain[$rowdata->id]["ytm_this_year"];
            }
            #########################################TYPE 1 END################################################## 
            #########################################TYPE 2 START################################################## 
            $surveillance1 = $this->dao->get_number_safety_observation(1, $prevfyrange);
            $surveillance2 = $this->dao->get_number_safety_observation(2, $month_year);
            $surveillance3 = $this->dao->get_number_safety_observation(3, $getytmrange);

            $safetaudit1 = $this->dao->get_number_safety_audit(1, $prevfyrange);
            $safetaudit2 = $this->dao->get_number_safety_audit(2, $month_year_formatted);
            $safetaudit3 = $this->dao->get_number_safety_audit(3, $getytmrange);

            $arrnew = array(
                28 => array(
                    "last_year_total" => $surveillance1,
                    "target" => 0,
                    "total_in_this_month" => $surveillance2,
                    "ytm_this_year" => $surveillance3),
                29 => array(
                    "last_year_total" => $safetaudit1,
                    "target" => 0,
                    "total_in_this_month" => $safetaudit2,
                    "ytm_this_year" => $safetaudit3)
            );
            $getjobsite = $this->dao->getMajorActivity(5);

            foreach ($getjobsite as $key => $rowdata) {
                $targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                $getjobsiteArr[$key] = new stdClass();
                $getjobsiteArr[$key]->id = $rowdata->id;
                $getjobsiteArr[$key]->name = $rowdata->name;
                $getjobsiteArr[$key]->type = $rowdata->type;
                $getjobsiteArr[$key]->last_year_total = $arrnew[$rowdata->id]["last_year_total"];
                $getjobsiteArr[$key]->target = $targetvalue["target"];
                $getjobsiteArr[$key]->total_in_this_month = $arrnew[$rowdata->id]["total_in_this_month"];
                $getjobsiteArr[$key]->ytm_this_year = $arrnew[$rowdata->id]["ytm_this_year"];
            }
            #########################################TYPE 2 END####################################################
            #########################################TYPE 3 START############################################################
            $nearmisscase1 = $this->dao->get_incident_category_wise_count(1, 1, $month_year_formatted);
            $nearmisscase2 = $this->dao->get_incident_category_wise_count(2, 1, $month_year_formatted, $financialyearstart);
            $nearmisscase3 = $this->dao->get_incident_category_wise_count(4, 1, $lastfinancialyear);

            $fac1 = $this->dao->get_incident_category_wise_count(1, 2, $month_year_formatted);
            $fac2 = $this->dao->get_incident_category_wise_count(2, 2, $month_year_formatted, $financialyearstart);
            $fac3 = $this->dao->get_incident_category_wise_count(4, 2, $lastfinancialyear);

            $lwc1 = $this->dao->get_incident_category_wise_count(1, 3, $month_year_formatted);
            $lwc2 = $this->dao->get_incident_category_wise_count(2, 3, $month_year_formatted, $financialyearstart);
            $lwc3 = $this->dao->get_incident_category_wise_count(4, 3, $lastfinancialyear);

            $fatal1 = $this->dao->get_incident_category_wise_count(1, 4, $month_year_formatted);
            $fatal2 = $this->dao->get_incident_category_wise_count(2, 4, $month_year_formatted, $financialyearstart);
            $fatal3 = $this->dao->get_incident_category_wise_count(4, 4, $lastfinancialyear);

            $arr2 = array(
                12 => array(
                    "total_in_this_month" => $nearmisscase1,
                    "ytm_total" => $nearmisscase2,
                    "last_year_ytm" => $nearmisscase3),
                13 => array(
                    "total_in_this_month" => $fac1,
                    "ytm_total" => $fac2,
                    "last_year_ytm" => $fac3),
                14 => array(
                    "total_in_this_month" => $lwc1,
                    "ytm_total" => $lwc2,
                    "last_year_ytm" => $lwc3),
                15 => array(
                    "total_in_this_month" => $fatal1,
                    "ytm_total" => $fatal2,
                    "last_year_ytm" => $fatal3)
            );

            $getaccident = $this->dao->getMajorActivity(2);

            foreach ($getaccident as $key => $rowdata) {
                $getmajorActivities3[$key] = new stdClass();
                $getmajorActivities3[$key]->id = $rowdata->id;
                $getmajorActivities3[$key]->name = $rowdata->name;
                $getmajorActivities3[$key]->type = $rowdata->type;
                $getmajorActivities3[$key]->total_in_this_month = $arr2[$rowdata->id]["total_in_this_month"];
                $getmajorActivities3[$key]->ytm_total = $arr2[$rowdata->id]["ytm_total"];
                $getmajorActivities3[$key]->actual_last_year = $arr2[$rowdata->id]["last_year_ytm"];
            }
            #####################################TYPE 3 END###########################################
            ##############################TYPE 4 START########################
            $unitsafetyday = $this->dao->get_safety_initiative_count(4, $month_year_formatted);
            $safety_workshop = $this->dao->get_safety_initiative_count(1, $month_year_formatted);
            $cmeeting = $this->dao->get_safety_initiative_count(2, $month_year_formatted);


            $arr4 = array(
                31 => array(
                    "total_in_this_month" => count($unitsafetyday),
                    "remarks" => $unitsafetyday),
                32 => array(
                    "total_in_this_month" => count($safety_workshop),
                    "remarks" => $safety_workshop),
                33 => array(
                    "total_in_this_month" => count($cmeeting),
                    "remarks" => $cmeeting)
            );
            $getsafetyinitiatives = $this->dao->getMajorActivity(6);

            $post_division_department = $this->activitydao->get_division_department();

            foreach ($getsafetyinitiatives as $key => $rowdata) {
                $arrremarks = $arr4[$rowdata->id]["remarks"];
                $devition_name = array();
                $newArr = array();
                if (!empty($arrremarks)) {
                    foreach ($arrremarks as $rkey => $row) {
                        $tree_division_id_arr = ($row->tree_division_id != "") ? explode("-", $row->tree_division_id) : array();
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
                                    $rowdatas = $this->dao->select($query, array($field_name => $table_id), true);
                                    $divition .= !empty($rowdatas) ? $rowdatas->name : ucfirst($tree_division_id_arr[$i]);
                                }
                                $divition .= '/';
                            }
                        }
                        $arrremarks[$rkey]->treename = trim($divition, "/");

                        @$newArr[$rowdata->id] .= $arrremarks[$rkey]->treename . ',';
                    }
                }
                $real_value = "";
                if (!empty($newArr)) {
                    $real_value = substr($newArr[$rowdata->id], 0, '-1');
                }
                $remrk1 = array();
                $remrk1 = $this->dao->get_remarks_mcm_id($rowdata->id, $rowid);
                $getmajorActivities4[$key] = new stdClass();
                $getmajorActivities4[$key]->id = $rowdata->id;
                $getmajorActivities4[$key]->name = $rowdata->name;
                $getmajorActivities4[$key]->type = $rowdata->type;
                $getmajorActivities4[$key]->total_in_this_month = $arr4[$rowdata->id]["total_in_this_month"];
                $getmajorActivities4[$key]->remarks = isset($remrk1[0]->remarks) ? $remrk1[0]->remarks : $real_value;
                $getmajorActivities4[$key]->insertedrowid = isset($remrk1[0]->id) ? $remrk1[0]->id : 0;
            }
            ##############################TYPE 4 END########################

            if($month_year != ""){
                $postValue["month_year"] = "%".$month_year."%";
                $checkmcmreportexist = $this->dao->checkexistmcmreport($postValue);
                }
            if (count($checkmcmreportexist) > 0) {
                $ids = $checkmcmreportexist[0]->id;
                if ($checkmcmreportexist[0]->status_id == 1) {
                    redirect(page_link("mis/editmcmReport.php?mnthyr=$month_year&id=$ids"));
                } else {
                    redirect(page_link("mis/editmcmReport.php?mnthyr=$month_year&id=$ids&mode=view"));
                }
            }
        }

        if ($_action == "mcmReportSubmit") {
            $type1 = $this->bean->get_request("type1");
            $type2 = $this->bean->get_request("type2");
            $type3 = $this->bean->get_request("type3");
            $type4 = $this->bean->get_request("type4");
            $special_activities = $this->bean->get_request("special_activities");

            $month_year = $this->bean->get_request("month_year");
            $basicData = array();
            $basicData["special_activities"] = $special_activities;
            $basicData["month_year"] = $month_year;
            if (isset($_POST["B1"])) {
                $status = 1;
            } else {
                $status = 2;
            }

            $basicData["status_id"] = $status;
            $this->dao->_table = "mis_mcmreport";
            $basic_data_id = $this->dao->save($basicData);

            $newArr = $newArr2 = $newArr3 = $newArr4 = array();
            ###############################################
            foreach ($type1 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_" . $key);
                $ytm_total = $this->bean->get_request("ytm_this_year_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_" . $key);
                $target = $this->bean->get_request("target_" . $key);
                $name = $this->bean->get_request("name_" . $key);
                $newArr[$key]["mcmreport_id"] = $basic_data_id;
                $newArr[$key]["type"] = $rowdata;
                $newArr[$key]["name"] = $name;
                $newArr[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr[$key]["target"] = $target;
                $newArr[$key]["major_activity_id"] = $major_activity_id;
                $newArr[$key]["ytm_total"] = $ytm_total;
                $newArr[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr[$key]);
            }
            foreach ($type2 as $key => $rowdata) {
                $last_year_total = $this->bean->get_request("last_year_total_2" . $key);
                $target = $this->bean->get_request("target_2" . $key);
                $total_in_this_month = $this->bean->get_request("total_in_this_month_2" . $key);
                $ytm_total = $this->bean->get_request("ytm_this_year_2" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_2" . $key);
                $name = $this->bean->get_request("name_2" . $key);
                $newArr2[$key]["mcmreport_id"] = $basic_data_id;
                $newArr2[$key]["type"] = $rowdata;
                $newArr2[$key]["name"] = $name;
                $newArr2[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr2[$key]["target"] = $target;
                $newArr2[$key]["major_activity_id"] = $major_activity_id;
                $newArr2[$key]["ytm_total"] = $ytm_total;
                $newArr2[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr2[$key]);
            }             
            foreach ($type3 as $key => $rowdata) {
                $last_year_total = $this->bean->get_request("actual_last_year_3" . $key);
                $total_in_this_month = $this->bean->get_request("total_in_this_month_3" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_3" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_3" . $key);
                $name = $this->bean->get_request("name_3" . $key);
                $newArr3[$key]["mcmreport_id"] = $basic_data_id;
                $newArr3[$key]["type"] = $rowdata;
                $newArr3[$key]["name"] = $name;
                $newArr3[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr3[$key]["major_activity_id"] = $major_activity_id;
                $newArr3[$key]["ytm_total"] = $ytm_total;
                $newArr3[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr3[$key]);
            }
            foreach ($type4 as $key => $rowdata) {
                $remarks = $this->bean->get_request("remarks_4" . $key);
                $total_in_this_month = $this->bean->get_request("total_in_this_month_4" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_4" . $key);
                $name = $this->bean->get_request("name_4" . $key);
                $newArr4[$key]["mcmreport_id"] = $basic_data_id;
                $newArr4[$key]["type"] = $rowdata;
                $newArr4[$key]["name"] = $name;
                $newArr4[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr4[$key]["major_activity_id"] = $major_activity_id;
                $newArr4[$key]["remarks"] = $remarks;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr4[$key]);
            }

            ###############################################

            redirect(page_link("mis/mcmReportlist.php"));
        }
        $this->beanUi->set_view_data("month_year", $month_year);
        $this->beanUi->set_view_data("majorActivities", $safety_related_trainingArr);
        $this->beanUi->set_view_data("majorActivities2", $getjobsiteArr);
        $this->beanUi->set_view_data("majorActivities3", $getmajorActivities3);
        $this->beanUi->set_view_data("majorActivities4", $getmajorActivities4);
        $this->beanUi->set_view_data("rowsingledata", $checkmcmreportexist);
        $this->beanUi->set_view_data("current_financial_year", $current_financial_year);
        $this->beanUi->set_view_data("previous_financial_year", $previous_financial_year);
        $this->beanUi->set_view_data("selected_month_year", $month_year);
        $this->beanUi->set_view_data("mnthyr", $mnthyr);
        $this->beanUi->set_view_data("submitdata", $submitdata);
        $this->beanUi->set_view_data("mode", $mode);
    }
    public function mcmReportNew() {
        $_action = $this->bean->get_request("_action");
        $mnthyr = $this->bean->get_request("mnthyr");
        $rowid = $this->bean->get_request("id");
        $mode = $this->bean->get_request("mode");
        $month_year = $current_financial_year = $previous_financial_year = $submitdata = "";
        $majorActivities_TA_SOArr = array();
        $majorActivities_DSA = array();
        $getmajorActivities3 = array();
        $getmajorActivities4 = array();
        $checkmcmreportexist = array();
        if ($_action == "Create") {

            $submitdata = $this->bean->get_request("submitdata");
            $month_year = $this->bean->get_request("month_year") ? $this->bean->get_request("month_year") : "";
            if($month_year != ""){
                $postValue["month_year"] = "%".$month_year."%";
                $checkmcmreportexist = $this->dao->checkexistmcmreport($postValue);
                }
            $month_yearexp = explode("-", $month_year);
            $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
            $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $prevfyrange = getAllMonthYear($previous_financial_year);
            $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getytmrange1 = getAllYTMYear($previous_financial_year, $month_yearexp[0]);
            $getytmmonthyearrange = getAllYTMMonthYear($current_financial_year, $month_yearexp[0]);
            $prevfystartytm = $getytmrange1[0];
            $prevfyendytm = end($getytmrange1);
            $getmajorActivities = $this->dao->getMajorActivity(17);

            #########################################TYPE 17 START##################################################
            $officerTraining1 = $this->dao->getOfficerTrainingNew(1,'3,5', $month_year_formatted);
            $officerTraining2 = $this->dao->getOfficerTrainingNew(2,'3,5', $month_year_formatted, $financialyearstart);
            $officerTraining3 = $this->dao->getOfficerTrainingNew(3,'3,5', $lastfinancialyear);
            $officerTraining4 = 2870;
            
            $contractualTraining1 = $this->dao->getOfficerTrainingNew(1, 4, $month_year_formatted);
            $contractualTraining2 = $this->dao->getOfficerTrainingNew(2, 4, $month_year_formatted, $financialyearstart);
            $contractualTraining3 = $this->dao->getOfficerTrainingNew(3, 4, $lastfinancialyear);
            $contractualTraining4 = 1540;
            
            $safobs3 = $this->dao->get_number_safety_observation(1, $prevfyrange);
            $safobs1 = $this->dao->get_number_safety_observation(2, $month_year);
            $safobs2 = $this->dao->get_number_safety_observation(3, $getytmrange);
            $safobs4 = 2460;
            
            $arrTrain = array(
                101 => array(
                    "last_year_total"       => $officerTraining3,
                    "target"                => $officerTraining4,
                    "total_in_this_month"   => $officerTraining1,
                    "ytm_this_year"         => $officerTraining2),
                102 => array(
                    "last_year_total"       => $contractualTraining3,
                    "target"                => $contractualTraining4,
                    "total_in_this_month"   => $contractualTraining1,
                    "ytm_this_year"         => $contractualTraining2),
                103 => array(
                    "total_in_this_month"   => $safobs1,
                    "ytm_this_year"         => $safobs2,
                    "last_year_total"       => $safobs3,
                    "target"                => $safobs4),
            );
            $majorActivities_TA_SO = $this->dao->getMajorActivity(17);

            foreach ($majorActivities_TA_SO as $key => $rowdata) {
//                $targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                $majorActivities_TA_SOArr[$key] = new stdClass();
                $majorActivities_TA_SOArr[$key]->id = $rowdata->id;
                $majorActivities_TA_SOArr[$key]->name = $rowdata->name;
                $majorActivities_TA_SOArr[$key]->type = $rowdata->type;
                $majorActivities_TA_SOArr[$key]->total_in_this_month = $arrTrain[$rowdata->id]["total_in_this_month"];
                $majorActivities_TA_SOArr[$key]->ytm_this_year = $arrTrain[$rowdata->id]["ytm_this_year"];
                $majorActivities_TA_SOArr[$key]->target = $arrTrain[$rowdata->id]["target"];
                $majorActivities_TA_SOArr[$key]->last_year_total = $arrTrain[$rowdata->id]["last_year_total"];
            }
            #########################################TYPE 17 END################################################## 
            #########################################TYPE 18 START################################################## 

            $distsiteaudit1     = $this->dao->getSiteAuditCountNew(1, 5, 'MCMREPORTDExHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteaudit2     = $this->dao->getSiteAuditCountNew(2, 5, 'MCMREPORTDExHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit3     = $this->dao->getSiteAuditCountNew(3, 5, 'MCMREPORTDExHD', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);
            $distsiteaudit4     = 840;
            $distsiteauditHT5   = $this->dao->getdistsiteauditHT(2, "month_year", "mis_mdreport", $month_year_formatted, $financialyearstart,$getytmmonthyearrange);
            $distsiteauditHT6   = $this->dao->getdistsiteauditMHT(2, "month_year", "mis_mdreport", $month_year_formatted, $financialyearstart,$getytmmonthyearrange);
//            show($distsiteauditHT5);
            $distsiteaudit11    = $this->dao->getSiteAuditCountNew(1, 5, 'DHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteaudit22    = $this->dao->getSiteAuditCountNew(2, 5, 'DHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit33    = $this->dao->getSiteAuditCountNew(3, 5, 'DHD', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);
            $distsiteaudit44    = 101;
            
//            $distsiteaudit111   = $this->dao->getSiteAuditCountNew(1, 5, 'MM', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
//            $distsiteaudit222   = $this->dao->getSiteAuditCountNew(2, 5, 'MM', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
//            $distsiteaudit333   = $this->dao->getSiteAuditCountNew(3, 5, 'MM', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $arrnew = array(
                104 => array(
                    "total_in_this_month"   => $distsiteaudit1+$distsiteauditHT5,
                    "ytm_this_year"         => $distsiteaudit2+$distsiteauditHT6,
                    "last_year_total"       => $distsiteaudit3,
                    "target"                => $distsiteaudit4),
                105 => array(
                    "total_in_this_month"   => $distsiteaudit11,
                    "ytm_this_year"         => $distsiteaudit22,
                    "last_year_total"       => $distsiteaudit33,
                    "target"                => $distsiteaudit44),
                
            );
            $majorActivities_DSA = $this->dao->getMajorActivity(18);

            foreach ($majorActivities_DSA as $key => $rowdata) {
//                $targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                $majorActivities_DSA[$key] = new stdClass();
                $majorActivities_DSA[$key]->id = $rowdata->id;
                $majorActivities_DSA[$key]->name = $rowdata->name;
                $majorActivities_DSA[$key]->type = $rowdata->type;
                $majorActivities_DSA[$key]->last_year_total = $arrnew[$rowdata->id]["last_year_total"];
                $majorActivities_DSA[$key]->target = $arrnew[$rowdata->id]["target"];
                $majorActivities_DSA[$key]->total_in_this_month = $arrnew[$rowdata->id]["total_in_this_month"];
                $majorActivities_DSA[$key]->ytm_this_year = $arrnew[$rowdata->id]["ytm_this_year"];
            }
            #########################################TYPE 18 END####################################################
            #########################################TYPE 19 START################################################## 

            $distsiteaudit1     = $this->dao->getSiteAuditCountNew(1, 5, 'G', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteaudit2     = $this->dao->getSiteAuditCountNew(2, 5, 'G', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit3     = $this->dao->getSiteAuditCountNew(3, 5, 'G', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);
            $distsiteaudit4     = 620;
            $arrnew = array(
                106 => array(
                    "total_in_this_month"   => $distsiteaudit1,
                    "ytm_this_year"         => $distsiteaudit2,
                    "last_year_total"       => $distsiteaudit3,
                    "target"                => $distsiteaudit4),
                
            );
            $majorActivities_G = $this->dao->getMajorActivity(19);

            foreach ($majorActivities_G as $key => $rowdata) {
//                $targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                $majorActivities_G[$key] = new stdClass();
                $majorActivities_G[$key]->id = $rowdata->id;
                $majorActivities_G[$key]->name = $rowdata->name;
                $majorActivities_G[$key]->type = $rowdata->type;
                $majorActivities_G[$key]->last_year_total = $arrnew[$rowdata->id]["last_year_total"];
                $majorActivities_G[$key]->target = $arrnew[$rowdata->id]["target"];
                $majorActivities_G[$key]->total_in_this_month = $arrnew[$rowdata->id]["total_in_this_month"];
                $majorActivities_G[$key]->ytm_this_year = $arrnew[$rowdata->id]["ytm_this_year"];
            }
            #########################################TYPE 19 END####################################################
            #########################################TYPE 20 START HANDHOLDING1################################################## 
            $handholding_sf1     = "NA";
            $handholding_sf2     = $this->dao->gethandholding_sf(2, "month_year", "mcmreport_id", $month_year_formatted, $financialyearstart,$getytmmonthyearrange);
            $handholding_sf3     = "NA";
            $handholding_sf4     = 75;
            $handholding_sfhd1     = "NA";
            $handholding_sfhd2     = $this->dao->gethandholding_sf_g(2, "month_year", "mcmreport_id", $month_year_formatted, $financialyearstart,$getytmmonthyearrange);
            $handholding_sfhd3     = "NA";
            $handholding_sfhd4     = 10;
            
            $arrnew = array(
                107 => array(
                    "total_in_this_month"   => $handholding_sf1,
                    "ytm_this_year"         => $handholding_sf2,
                    "last_year_total"       => $handholding_sf3,
                    "target"                => $handholding_sf4,
                    "total_in_this_month_g" => $handholding_sfhd1,
                    "ytm_this_year_g"       => $handholding_sfhd2,
                    "last_year_total_g"     => $handholding_sfhd3,
                    "target_g"              => $handholding_sfhd4),
                
            );
            $majorActivities_HH_SF = $this->dao->getMajorActivity(20);

            foreach ($majorActivities_HH_SF as $key => $rowdata) {
//                $targetvalue                                        = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                $majorActivities_HH_SF[$key]                        = new stdClass();
                $majorActivities_HH_SF[$key]->id                    = $rowdata->id;
                $majorActivities_HH_SF[$key]->name                  = $rowdata->name;
                $majorActivities_HH_SF[$key]->type                  = $rowdata->type;
                $majorActivities_HH_SF[$key]->last_year_total       = @$arrnew[$rowdata->id]["last_year_total"];
                $majorActivities_HH_SF[$key]->target                = $arrnew[$rowdata->id]["target"];
                $majorActivities_HH_SF[$key]->total_in_this_month   = @$arrnew[$rowdata->id]["total_in_this_month"];
                $majorActivities_HH_SF[$key]->ytm_this_year         = @$arrnew[$rowdata->id]["ytm_this_year"];
                $majorActivities_HH_SF[$key]->last_year_total_g     = @$arrnew[$rowdata->id]["last_year_total_g"];
                $majorActivities_HH_SF[$key]->target_g              = $arrnew[$rowdata->id]["target_g"];
                $majorActivities_HH_SF[$key]->total_in_this_month_g = @$arrnew[$rowdata->id]["total_in_this_month_g"];
                $majorActivities_HH_SF[$key]->ytm_this_year_g       = @$arrnew[$rowdata->id]["ytm_this_year_g"];
            }
            #########################################TYPE 20 END HANDHOLDING1####################################################
            #########################################TYPE 21 START HANDHOLDING2################################################## 

            $handholding_htmpb_pset1     = "NA";
            $handholding_htmpb_cset2     = "NA";
            $handholding_htmpb_pset3     = $this->dao->gethandholding_htmpb_pset(2, "month_year", "mcmreport_id", $month_year_formatted, $financialyearstart,$getytmmonthyearrange);//show($handholding_htmpb_pset3);die;
            $handholding_htmpb_cset4     = $this->dao->gethandholding_htmpb_cset(2, "month_year", "mcmreport_id", $month_year_formatted, $financialyearstart,$getytmmonthyearrange);//show($handholding_htmpb_pset3);die;
            $handholding_htmpb_pset5     = "NA";
            $handholding_htmpb_cset6     = "NA";
            $handholding_htmpb_pset7     = "NA";
            $handholding_htmpb_cset8     = "NA";
            
            
            $arrnew = array(
                108 => array(
                    "total_in_this_month_p"   => $handholding_htmpb_pset1,
                    "total_in_this_month_c"   => $handholding_htmpb_cset2,
                    "ytm_this_year_p"         => $handholding_htmpb_pset3,
                    "ytm_this_year_c"         => $handholding_htmpb_cset4,
                    "last_year_total_p"       => $handholding_htmpb_pset5,
                    "last_year_total_c"       => $handholding_htmpb_cset6,
                    "target_p"                => $handholding_htmpb_pset7,
                    "target_c"                => $handholding_htmpb_cset8),
                
            );
            $majorActivities_HH_HT = $this->dao->getMajorActivity(21);

            foreach ($majorActivities_HH_HT as $key => $rowdata) {
//                $targetvalue                                        = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                $majorActivities_HH_HT[$key]                        = new stdClass();
                $majorActivities_HH_HT[$key]->id                    = $rowdata->id;
                $majorActivities_HH_HT[$key]->name                  = $rowdata->name;
                $majorActivities_HH_HT[$key]->type                  = $rowdata->type;
                $majorActivities_HH_HT[$key]->total_in_this_month_p = @$arrnew[$rowdata->id]["total_in_this_month_p"];
                $majorActivities_HH_HT[$key]->total_in_this_month_c = @$arrnew[$rowdata->id]["total_in_this_month_c"];
                $majorActivities_HH_HT[$key]->ytm_this_year_p       = @$arrnew[$rowdata->id]["ytm_this_year_p"];
                $majorActivities_HH_HT[$key]->ytm_this_year_c       = @$arrnew[$rowdata->id]["ytm_this_year_c"];
                $majorActivities_HH_HT[$key]->last_year_total_p     = @$arrnew[$rowdata->id]["last_year_total_p"];
                $majorActivities_HH_HT[$key]->last_year_total_c     = @$arrnew[$rowdata->id]["last_year_total_c"];
                $majorActivities_HH_HT[$key]->target_p              = @$arrnew[$rowdata->id]["target_p"];
                $majorActivities_HH_HT[$key]->target_c              = @$arrnew[$rowdata->id]["target_c"];
                
            }
            #########################################TYPE 21 END HANDHOLDING2####################################################
            #########################################TYPE 22 START################################################## 

            $safetyworkshopD1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "D");
            $safetyworkshopD2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "D");
            $safetyworkshopD3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "D");
            $safetyworkshopD4 = 28;
            $safetyworkshopG1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "G");
            $safetyworkshopG2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "G");
            $safetyworkshopG3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "G");
            $safetyworkshopG4 = 18;
            
            $commMeetingD1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "D");
            $commMeetingD2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "D");
            $commMeetingD3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "D");
            $commMeetingD4 = 28;
            $commMeetingG1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "G");
            $commMeetingG2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "G");
            $commMeetingG3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "G");
            $commMeetingG4 = 23;
            
            $ppeauditD1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "D");
            $ppeauditD2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "D");
            $ppeauditD3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "D");
            $ppeauditD4 = 230;
            $ppeauditG1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "G");
            $ppeauditG2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "G");
            $ppeauditG3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "G");
            $ppeauditG4 = 76;
                    
            //  show($getytmrange);
            $arrnew = array(
                109 => array(
                    "total_in_this_month_D"   => $safetyworkshopD1,
                    "ytm_this_year_D"         => $safetyworkshopD2,
                    "last_year_total_D"       => $safetyworkshopD3,
                    "target_D"                => $safetyworkshopD4,
                    "total_in_this_month_G"   => $safetyworkshopG1,
                    "ytm_this_year_G"         => $safetyworkshopG2,
                    "last_year_total_G"       => $safetyworkshopG3,
                    "target_G"                => $safetyworkshopG4),
                110 => array(
                    "total_in_this_month_D"   => $commMeetingD1,
                    "ytm_this_year_D"         => $commMeetingD2,
                    "last_year_total_D"       => $commMeetingD3,
                    "target_D"                => $commMeetingD4,
                    "total_in_this_month_G"   => $commMeetingG1,
                    "ytm_this_year_G"         => $commMeetingG2,
                    "last_year_total_G"       => $commMeetingG3,
                    "target_G"                => $commMeetingG4),
                111 => array(
                    "total_in_this_month_D"   => $ppeauditD1,
                    "ytm_this_year_D"         => $ppeauditD2,
                    "last_year_total_D"       => $ppeauditD3,
                    "target_D"                => $ppeauditD4,
                    "total_in_this_month_G"   => $ppeauditG1,
                    "ytm_this_year_G"         => $ppeauditG2,
                    "last_year_total_G"       => $ppeauditG3,
                    "target_G"                => $ppeauditG4),
                
            );
            $majorActivities_DG = $this->dao->getMajorActivity(22);

            foreach ($majorActivities_DG as $key => $rowdata) {
//                $targetvalue                                        = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                $majorActivities_DG[$key]                           = new stdClass();
                $majorActivities_DG[$key]->id                       = $rowdata->id;
                $majorActivities_DG[$key]->name                     = $rowdata->name;
                $majorActivities_DG[$key]->type                     = $rowdata->type;
                $majorActivities_DG[$key]->last_year_total_D        = $arrnew[$rowdata->id]["last_year_total_D"];
                $majorActivities_DG[$key]->target_D                 = $arrnew[$rowdata->id]["target_D"];
                $majorActivities_DG[$key]->total_in_this_month_D    = $arrnew[$rowdata->id]["total_in_this_month_D"];
                $majorActivities_DG[$key]->ytm_this_year_D          = $arrnew[$rowdata->id]["ytm_this_year_D"];
                $majorActivities_DG[$key]->last_year_total_G        = $arrnew[$rowdata->id]["last_year_total_G"];
                $majorActivities_DG[$key]->target_G                 = $arrnew[$rowdata->id]["target_G"];
                $majorActivities_DG[$key]->total_in_this_month_G    = $arrnew[$rowdata->id]["total_in_this_month_G"];
                $majorActivities_DG[$key]->ytm_this_year_G          = $arrnew[$rowdata->id]["ytm_this_year_G"];
            }
            #########################################TYPE 22 END####################################################
            #########################################TYPE 23 START############################################################
            $nearmisscase1 = $this->dao->get_incident_category_wise_count_new(1, 1, $month_year_formatted, "", "D");
            $nearmisscase2 = $this->dao->get_incident_category_wise_count_new(2, 1, $month_year_formatted, $financialyearstart, "D");
            $nearmisscase3 = $this->dao->get_incident_category_wise_count_new(3, 1, $prevfystartytm, $prevfyendytm, "D");
            $nearmisscase4 = $this->dao->get_incident_category_wise_count_new(1, 1, $month_year_formatted, "", "G");
            $nearmisscase5 = $this->dao->get_incident_category_wise_count_new(2, 1, $month_year_formatted, $financialyearstart, "G");
            $nearmisscase6 = $this->dao->get_incident_category_wise_count_new(3, 1, $prevfystartytm, $prevfyendytm, "G");

            $fac1 = $this->dao->get_incident_category_wise_count_new(1, 2, $month_year_formatted, "", "D");
            $fac2 = $this->dao->get_incident_category_wise_count_new(2, 2, $month_year_formatted, $financialyearstart, "D");
            $fac3 = $this->dao->get_incident_category_wise_count_new(3, 2, $prevfystartytm, $prevfyendytm, "D");
            $fac4 = $this->dao->get_incident_category_wise_count_new(1, 2, $month_year_formatted, "", "G");
            $fac5 = $this->dao->get_incident_category_wise_count_new(2, 2, $month_year_formatted, $financialyearstart, "G");
            $fac6 = $this->dao->get_incident_category_wise_count_new(3, 2, $prevfystartytm, $prevfyendytm, "G");

            $lwc1 = $this->dao->get_incident_category_wise_count_new(1, 3, $month_year_formatted, "", "D");
            $lwc2 = $this->dao->get_incident_category_wise_count_new(2, 3, $month_year_formatted, $financialyearstart, "D");
            $lwc3 = $this->dao->get_incident_category_wise_count_new(3, 3, $prevfystartytm, $prevfyendytm, "D");
            $lwc4 = $this->dao->get_incident_category_wise_count_new(1, 3, $month_year_formatted, "", "G");
            $lwc5 = $this->dao->get_incident_category_wise_count_new(2, 3, $month_year_formatted, $financialyearstart, "G");
            $lwc6 = $this->dao->get_incident_category_wise_count_new(3, 3, $prevfystartytm, $prevfyendytm, "G");

            $fatal1 = $this->dao->get_incident_category_wise_count_new(1, 4, $month_year_formatted, "", "D");
            $fatal2 = $this->dao->get_incident_category_wise_count_new(2, 4, $month_year_formatted, $financialyearstart, "D");
            $fatal3 = $this->dao->get_incident_category_wise_count_new(3, 4, $prevfystartytm, $prevfyendytm, "D");
            $fatal4 = $this->dao->get_incident_category_wise_count_new(1, 4, $month_year_formatted, "", "G");
            $fatal5 = $this->dao->get_incident_category_wise_count_new(2, 4, $month_year_formatted, $financialyearstart, "G");
            $fatal6 = $this->dao->get_incident_category_wise_count_new(3, 4, $prevfystartytm, $prevfyendytm, "G");

            $arr2 = array(
                112 => array(
                    "total_in_this_month_d" => $nearmisscase1,
                    "ytm_total_d" => $nearmisscase2,
                    "last_year_ytm_d" => $nearmisscase3,
                    "total_in_this_month_g" => $nearmisscase4,
                    "ytm_total_g" => $nearmisscase5,
                    "last_year_ytm_g" => $nearmisscase6
                ),
                113 => array(
                    "total_in_this_month_d" => $fac1,
                    "ytm_total_d" => $fac2,
                    "last_year_ytm_d" => $fac3,
                    "total_in_this_month_g" => $fac4,
                    "ytm_total_g" => $fac5,
                    "last_year_ytm_g" => $fac6
                ),
                114 => array(
                    "total_in_this_month_d" => $lwc1,
                    "ytm_total_d" => $lwc2,
                    "last_year_ytm_d" => $lwc3,
                    "total_in_this_month_g" => $lwc4,
                    "ytm_total_g" => $lwc5,
                    "last_year_ytm_g" => $lwc6
                ),
                115 => array(
                    "total_in_this_month_d" => $fatal1,
                    "ytm_total_d" => $fatal2,
                    "last_year_ytm_d" => $fatal3,
                    "total_in_this_month_g" => $fatal4,
                    "ytm_total_g" => $fatal5,
                    "last_year_ytm_g" => $fatal6
                )
            );
            $majorActivitiesIN = $this->dao->getMajorActivity(23);


            if (!empty($majorActivitiesIN)) {
                foreach ($majorActivitiesIN as $key => $rowdata) {
                    $remrk1 = array();
                    $remrk1 = $this->dao->get_remarks_by_id($rowdata->id, $rowid);
                    $arrData2[$key] = new stdClass();
                    $arrData2[$key]->id = $rowdata->id;
                    $arrData2[$key]->name = $rowdata->name;
                    $arrData2[$key]->type = $rowdata->type;
                    $arrData2[$key]->total_in_this_month_d = $arr2[$rowdata->id]["total_in_this_month_d"];
                    $arrData2[$key]->ytm_total_d = $arr2[$rowdata->id]["ytm_total_d"];
                    $arrData2[$key]->last_year_ytm_d = $arr2[$rowdata->id]["last_year_ytm_d"];
                    $arrData2[$key]->total_in_this_month_g = $arr2[$rowdata->id]["total_in_this_month_g"];
                    $arrData2[$key]->ytm_total_g = $arr2[$rowdata->id]["ytm_total_g"];
                    $arrData2[$key]->last_year_ytm_g = $arr2[$rowdata->id]["last_year_ytm_g"];
                    $arrData2[$key]->remarks = isset($remrk1[0]->remarks) ? $remrk1[0]->remarks : "(Dist.) & (Gen.)";
                    $arrData2[$key]->insteredrowid = isset($remrk1[0]->id) ? $remrk1[0]->id : 0;
                }
            }
            #####################################TYPE 23 END###########################################
            ##############################TYPE 4 START########################
            $unitsafetyday = $this->dao->get_safety_initiative_count(4, $month_year_formatted);
            $safety_workshop = $this->dao->get_safety_initiative_count(1, $month_year_formatted);
            $cmeeting = $this->dao->get_safety_initiative_count(2, $month_year_formatted);


            $arr4 = array(
                31 => array(
                    "total_in_this_month" => count($unitsafetyday),
                    "remarks" => $unitsafetyday),
                32 => array(
                    "total_in_this_month" => count($safety_workshop),
                    "remarks" => $safety_workshop),
                33 => array(
                    "total_in_this_month" => count($cmeeting),
                    "remarks" => $cmeeting)
            );
            $getsafetyinitiatives = $this->dao->getMajorActivity(6);

            $post_division_department = $this->activitydao->get_division_department();

            foreach ($getsafetyinitiatives as $key => $rowdata) {
                $arrremarks = $arr4[$rowdata->id]["remarks"];
                $devition_name = array();
                $newArr = array();
                if (!empty($arrremarks)) {
                    foreach ($arrremarks as $rkey => $row) {
                        $tree_division_id_arr = ($row->tree_division_id != "") ? explode("-", $row->tree_division_id) : array();
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
                                    $rowdatas = $this->dao->select($query, array($field_name => $table_id), true);
                                    $divition .= !empty($rowdatas) ? $rowdatas->name : ucfirst($tree_division_id_arr[$i]);
                                }
                                $divition .= '/';
                            }
                        }
                        $arrremarks[$rkey]->treename = trim($divition, "/");

                        @$newArr[$rowdata->id] .= $arrremarks[$rkey]->treename . ',';
                    }
                }
                $real_value = "";
                if (!empty($newArr)) {
                    $real_value = substr($newArr[$rowdata->id], 0, '-1');
                }
                $remrk1 = array();
                $remrk1 = $this->dao->get_remarks_mcm_id($rowdata->id, $rowid);
                $getmajorActivities4[$key] = new stdClass();
                $getmajorActivities4[$key]->id = $rowdata->id;
                $getmajorActivities4[$key]->name = $rowdata->name;
                $getmajorActivities4[$key]->type = $rowdata->type;
                $getmajorActivities4[$key]->total_in_this_month = $arr4[$rowdata->id]["total_in_this_month"];
                $getmajorActivities4[$key]->remarks = isset($remrk1[0]->remarks) ? $remrk1[0]->remarks : $real_value;
                $getmajorActivities4[$key]->insertedrowid = isset($remrk1[0]->id) ? $remrk1[0]->id : 0;
            }
            ##############################TYPE 4 END########################

            if($month_year != ""){
                $postValue["month_year"] = "%".$month_year."%";
                $checkmcmreportexist = $this->dao->checkexistmcmreport($postValue);
                }
            if (count($checkmcmreportexist) > 0) {
                $ids = $checkmcmreportexist[0]->id;
                if ($checkmcmreportexist[0]->status_id == 1) {
                    redirect(page_link("mis/editmcmReportNew.php?mnthyr=$month_year&id=$ids"));
                } else {
                    redirect(page_link("mis/editmcmReportNew.php?mnthyr=$month_year&id=$ids&mode=view"));
                }
            }
        }

        if ($_action == "mcmReportSubmit") {
            $type1 = $this->bean->get_request("type1");
            $type2 = $this->bean->get_request("type2");
            $type3 = $this->bean->get_request("type3");
            $type4 = $this->bean->get_request("type4");
            $type5 = $this->bean->get_request("type5");
            $type6 = $this->bean->get_request("type6");
            $type7 = $this->bean->get_request("type7");
            
            $special_activities = $this->bean->get_request("special_activities");

            $month_year = $this->bean->get_request("month_year");
            $basicData = array();
            $basicData["special_activities"] = $special_activities;
            $basicData["month_year"] = $month_year;
            if (isset($_POST["B1"])) {
                $status = 1;
            } else {
                $status = 2;
            }

            $basicData["status_id"] = $status;
            $this->dao->_table = "mis_mcmreport";
            $basic_data_id = $this->dao->save($basicData);

            $newArr = $newArr2 = $newArr3 = $newArr4 = $newArr5 = $newArr6 = $newArr7 = array();
            ###############################################
            foreach ($type1 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_TA_SO_" . $key);
                $ytm_total = $this->bean->get_request("ytm_this_year_TA_SO_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_TA_SO_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_TA_SO_" . $key);
                $target = $this->bean->get_request("target_TA_SO_" . $key);
                $name = $this->bean->get_request("name_TA_SO_" . $key);
                $newArr[$key]["mcmreport_id"] = $basic_data_id;
                $newArr[$key]["type"] = $rowdata;
                $newArr[$key]["name"] = $name;
                $newArr[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr[$key]["target"] = $target;
                $newArr[$key]["major_activity_id"] = $major_activity_id;
                $newArr[$key]["ytm_total"] = $ytm_total;
                $newArr[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr[$key]);
            }
            foreach ($type2 as $key => $rowdata) {
                $last_year_total = $this->bean->get_request("last_year_total_DSA_" . $key);
                $target = $this->bean->get_request("target_DSA_" . $key);
                $total_in_this_month = $this->bean->get_request("total_in_this_month_DSA_" . $key);
                $ytm_total = $this->bean->get_request("ytm_this_year_DSA_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_DSA_" . $key);
                $name = $this->bean->get_request("name_DSA_" . $key);
                $newArr2[$key]["mcmreport_id"] = $basic_data_id;
                $newArr2[$key]["type"] = $rowdata;
                $newArr2[$key]["name"] = $name;
                $newArr2[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr2[$key]["target"] = $target;
                $newArr2[$key]["major_activity_id"] = $major_activity_id;
                $newArr2[$key]["ytm_total"] = $ytm_total;
                $newArr2[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr2[$key]);
            } 
            foreach ($type3 as $key => $rowdata) {
                $last_year_total = $this->bean->get_request("last_year_total_G_" . $key);
                $target = $this->bean->get_request("target_G_" . $key);
                $total_in_this_month = $this->bean->get_request("total_in_this_month_G_" . $key);
                $ytm_total = $this->bean->get_request("ytm_this_year_G_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_G_" . $key);
                $name = $this->bean->get_request("name_G_" . $key);
                $newArr3[$key]["mcmreport_id"] = $basic_data_id;
                $newArr3[$key]["type"] = $rowdata;
                $newArr3[$key]["name"] = $name;
                $newArr3[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr3[$key]["target"] = $target;
                $newArr3[$key]["major_activity_id"] = $major_activity_id;
                $newArr3[$key]["ytm_total"] = $ytm_total;
                $newArr3[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr3[$key]);
            }  
            foreach ($type4 as $key => $rowdata) {
                $total_in_this_month    = $this->bean->get_request("total_in_this_month" . $key);
                $ytm_total              = $this->bean->get_request("ytm_this_year" . $key);
                $target                 = $this->bean->get_request("target" . $key);
                $total_in_this_month_g  = $this->bean->get_request("total_in_this_month_g" . $key);
                $ytm_total_g            = $this->bean->get_request("ytm_this_year_g" . $key);
                $target_g               = $this->bean->get_request("target_g" . $key);
                
                $remarks                = $this->bean->get_request("remarks_" . $key);
                $major_activity_id      = $this->bean->get_request("major_activity_id_2" . $key);
                $name                   = $this->bean->get_request("name_2" . $key);
                
                $newArr4[$key]["mcmreport_id"]          = $basic_data_id;
                $newArr4[$key]["type"]                  = $rowdata;
                $newArr4[$key]["name"]                  = $name;
                $newArr4[$key]["major_activity_id"]     = $major_activity_id;
                $newArr4[$key]["total_in_this_month"]   = $total_in_this_month;
                $newArr4[$key]["ytm_total"]             = $ytm_total;
                $newArr4[$key]["target"]                = $target;
                $newArr4[$key]["total_in_this_month_g"] = $total_in_this_month_g;
                $newArr4[$key]["ytm_total_g"]           = $ytm_total_g;
                $newArr4[$key]["target_g"]              = $target_g;
                $newArr4[$key]["remarks"]               = $remarks;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr4[$key]);
            }  
            foreach ($type5 as $key => $rowdata) {
                $total_in_this_month_p    = $this->bean->get_request("total_in_this_month_p" . $key);
                $total_in_this_month_c    = $this->bean->get_request("total_in_this_month_c" . $key);
                $ytm_this_year_p          = $this->bean->get_request("ytm_this_year_p" . $key);
                $ytm_this_year_c          = $this->bean->get_request("ytm_this_year_c" . $key);
                $last_year_total_p        = $this->bean->get_request("last_year_total_p" . $key);
                $last_year_total_c        = $this->bean->get_request("last_year_total_c" . $key);
                $target_p                 = $this->bean->get_request("target_p" . $key);
                $target_c                 = $this->bean->get_request("target_c" . $key);
                                
                $remarks                = $this->bean->get_request("remarks_" . $key);
                $major_activity_id      = $this->bean->get_request("major_activity_id_htmpb" . $key);
                $name                   = $this->bean->get_request("name_htmpb" . $key);
                
                $newArr5[$key]["mcmreport_id"]          = $basic_data_id;
                $newArr5[$key]["type"]                  = $rowdata;
                $newArr5[$key]["name"]                  = $name;
                $newArr5[$key]["major_activity_id"]     = $major_activity_id;
                $newArr5[$key]["total_in_this_month_p"] = $total_in_this_month_p;
                $newArr5[$key]["total_in_this_month_c"] = $total_in_this_month_c;
                $newArr5[$key]["ytm_this_year_p"]       = $ytm_this_year_p;
                $newArr5[$key]["ytm_this_year_c"]       = $ytm_this_year_c;
                $newArr5[$key]["last_year_total_p"]     = $last_year_total_p;
                $newArr5[$key]["last_year_total_c"]     = $last_year_total_c;
                $newArr5[$key]["target_p"]              = $target_p;
                $newArr5[$key]["target_c"]              = $target_c;
                
                $newArr5[$key]["remarks"]               = $remarks;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr5[$key]);
            }
            foreach ($type6 as $key => $rowdata) {
                $last_year_total_SAD = $this->bean->get_request("last_year_total_SAD_" . $key);
                $target_SAD = $this->bean->get_request("target_SAD_" . $key);
                $total_in_this_month_SAD = $this->bean->get_request("total_in_this_month_SAD_" . $key);
                $ytm_total_SAD = $this->bean->get_request("ytm_total_SAD_" . $key);
                $last_year_total_SAG = $this->bean->get_request("last_year_total_SAG_" . $key);
                $target_SAG = $this->bean->get_request("target_SAG_" . $key);
                $total_in_this_month_SAG = $this->bean->get_request("total_in_this_month_SAG_" . $key);
                $ytm_total_SAG = $this->bean->get_request("ytm_total_SAG_" . $key);
                
                $major_activity_id = $this->bean->get_request("major_activity_id_SADG_" . $key);
                $name = $this->bean->get_request("name_SADG_" . $key);
                $newArr6[$key]["mcmreport_id"] = $basic_data_id;
                $newArr6[$key]["type"] = $rowdata;
                $newArr6[$key]["name"] = $name;
                $newArr6[$key]["major_activity_id"] = $major_activity_id;
                $newArr6[$key]["total_in_this_month"] = $total_in_this_month_SAD;
                $newArr6[$key]["target"] = $target_SAD;
                $newArr6[$key]["ytm_total"] = $ytm_total_SAD;
                $newArr6[$key]["last_year_total"] = $last_year_total_SAD;
                $newArr6[$key]["total_in_this_month_g"] = $total_in_this_month_SAG;
                $newArr6[$key]["target_g"] = $target_SAG;
                $newArr6[$key]["ytm_total_g"] = $ytm_total_SAG;
                $newArr6[$key]["last_year_total_g"] = $last_year_total_SAG;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr6[$key]);
            }  
            foreach ($type7 as $key => $rowdata) {
                $last_year_total_IND = $this->bean->get_request("last_year_ytm_IND_" . $key);
                $target_IND = $this->bean->get_request("target_IND_" . $key);
                $total_in_this_month_IND = $this->bean->get_request("total_in_this_month_IND_" . $key);
                $ytm_total_IND = $this->bean->get_request("ytm_total_IND_" . $key);
                $last_year_total_ING = $this->bean->get_request("last_year_ytm_ING_" . $key);
                $target_ING = $this->bean->get_request("target_ING_" . $key);
                $total_in_this_month_ING = $this->bean->get_request("total_in_this_month_ING_" . $key);
                $ytm_total_ING = $this->bean->get_request("ytm_total_ING_" . $key);
                
                $major_activity_id = $this->bean->get_request("major_activity_id_IN_" . $key);
                $name = $this->bean->get_request("name_IN_" . $key);
                $newArr7[$key]["mcmreport_id"] = $basic_data_id;
                $newArr7[$key]["type"] = $rowdata;
                $newArr7[$key]["name"] = $name;
                $newArr7[$key]["major_activity_id"] = $major_activity_id;
                $newArr7[$key]["total_in_this_month"] = $total_in_this_month_IND;
                $newArr7[$key]["target"] = $target_IND;
                $newArr7[$key]["ytm_total"] = $ytm_total_IND;
                $newArr7[$key]["last_year_total"] = $last_year_total_IND;
                $newArr7[$key]["total_in_this_month_g"] = $total_in_this_month_ING;
                $newArr7[$key]["target_g"] = $target_ING;
                $newArr7[$key]["ytm_total_g"] = $ytm_total_ING;
                $newArr7[$key]["last_year_total_g"] = $last_year_total_ING;
                $this->dao->_table = "mis_mcmreport_mapping";
                $this->dao->save($newArr7[$key]);
            }

            ###############################################

            redirect(page_link("mis/mcmReportlistNew.php"));
        }
        $this->beanUi->set_view_data("month_year", $month_year);
        $this->beanUi->set_view_data("majorActivities_TA_SO", $majorActivities_TA_SOArr);
        $this->beanUi->set_view_data("majorActivities_DSA", $majorActivities_DSA);
        $this->beanUi->set_view_data("majorActivities_G", @$majorActivities_G);
        $this->beanUi->set_view_data("majorActivities_HH_SF", @$majorActivities_HH_SF);
        $this->beanUi->set_view_data("majorActivities_HH_HT", @$majorActivities_HH_HT);
        $this->beanUi->set_view_data("majorActivities_DG", @$majorActivities_DG);
        $this->beanUi->set_view_data("majorActivitiesIN", @$arrData2);
        $this->beanUi->set_view_data("majorActivities4", $getmajorActivities4);
        $this->beanUi->set_view_data("rowsingledata", $checkmcmreportexist);
        $this->beanUi->set_view_data("current_financial_year", $current_financial_year);
        $this->beanUi->set_view_data("previous_financial_year", $previous_financial_year);
        $this->beanUi->set_view_data("selected_month_year", $month_year);
        $this->beanUi->set_view_data("mnthyr", $mnthyr);
        $this->beanUi->set_view_data("submitdata", $submitdata);
        $this->beanUi->set_view_data("mode", $mode);
    }

    public function editmdReport() {
        $mnthyr = $this->bean->get_request("mnthyr");
        $rowid = $this->bean->get_request("id");
        $mode = $this->bean->get_request("mode");
        if($mnthyr != ""){
        $postValue["month_year"] = "%".$mnthyr."%";
        $checkmdreportexist = $this->dao->checkexistmdreport($postValue);
        }
        $month_year = $mnthyr;
        $month_yearexp = explode("-", $month_year);
        $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
        $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
        $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
        $prevfyrange = getAllMonthYear($previous_financial_year);
        $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
        $newArr = array();
        if (count($checkmdreportexist) > 0) {
            $fetachallmappingdata = $this->dao->fetchallmdreport($checkmdreportexist[0]->id);
            $arr_type = array(1, 2, 3);
            foreach ($arr_type as $row) {
                foreach ($fetachallmappingdata as $key => $value) {
                    if ($value->type == $row) {
                        $newArr[$row][$key] = new stdClass();
                        $newArr[$row][$key]->id = $value->id;
                        $newArr[$row][$key]->mdreport_id = $value->mdreport_id;
                        $newArr[$row][$key]->major_activity_id = $value->major_activity_id;
                        $newArr[$row][$key]->name = $value->name;
                        $newArr[$row][$key]->type = $value->type;
                        $newArr[$row][$key]->total_in_this_month = $value->total_in_this_month;
                        $newArr[$row][$key]->target = $value->target;
                        $newArr[$row][$key]->ytm_total = $value->ytm_total;
                        $newArr[$row][$key]->last_year_total = $value->last_year_total;
                        $newArr[$row][$key]->last_year_ytm = $value->last_year_ytm;
                        $newArr[$row][$key]->remarks = $value->remarks;
                        $newArr[$row][$key]->pset_last_year = $value->pset_last_year;
                        $newArr[$row][$key]->pset_ytm_this_year = $value->pset_ytm_this_year;
                        $newArr[$row][$key]->cset_last_year = $value->cset_last_year;
                        $newArr[$row][$key]->cset_ytm_this_year = $value->cset_ytm_this_year;
                    }
                }
            }
            $_action = $this->bean->get_request("_action");
            if ($_action == "mdReportSubmit") {
                $type2 = $this->bean->get_request("type2");

                $special_activities = $this->bean->get_request("special_activities");
                $report_date = date("Y-m-d", strtotime($this->bean->get_request("report_date")));
                $name = $this->bean->get_request("name");
                $month_year = $this->bean->get_request("month_year");
                $notes = $this->bean->get_request("notes");
                $basicData = array();
                $basicData["special_activities"] = $special_activities;
                $basicData["notes"] = $notes;
                $basicData["report_date"] = $report_date;
                if (isset($_POST["B1"])) {
                    $status = 1;
                } else {
                    $status = 2;
                }
                $basicData["id"] = $checkmdreportexist[0]->id;
                $basicData["status_id"] = $status;
                $this->dao->_table = "mis_mdreport";
                $basic_data_id = $this->dao->save($basicData);
                $newArr2 = array();
                foreach ($type2 as $key => $rowdata) {
                    $remarks = $this->bean->get_request("remarks_" . $key);
                    $insertedrowid = $this->bean->get_request("insertedrowid_" . $key);
                    $newArr2[$key]["id"] = $insertedrowid;
                    $newArr2[$key]["remarks"] = $remarks;
                    $this->dao->_table = "mis_mdreport_mapping";
                    $this->dao->save($newArr2[$key]);
                }
                redirect(page_link("mis/mdReportlist.php"));
            }
            $safetymember = $this->dao->get_safety_member();
            $this->beanUi->set_view_data("fetchalldata", $newArr);
            $this->beanUi->set_view_data("selected_month_year", $mnthyr);
            $this->beanUi->set_view_data("mnthyr", $mnthyr);
            $this->beanUi->set_view_data("current_financial_year", $current_financial_year);
            $this->beanUi->set_view_data("previous_financial_year", $previous_financial_year);
            $this->beanUi->set_view_data("rowsingledata", $checkmdreportexist);
            $this->beanUi->set_view_data("safetymember", $safetymember);
            $this->beanUi->set_view_data("mode", $mode);
        }
    }

    public function editmdReportNew() {
        $mnthyr = $this->bean->get_request("mnthyr");
        $rowid = $this->bean->get_request("id");
        $mode = $this->bean->get_request("mode");
        if($mnthyr != ""){
        $postValue["month_year"] = "%".$mnthyr."%";
        $checkmdreportexist = $this->dao->checkexistmdreport($postValue);
        }
        $month_year = $mnthyr;
        $month_yearexp = explode("-", $month_year);
        $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
        $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
        $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
        $prevfyrange = getAllMonthYear($previous_financial_year);
        $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
        $newArr = array();
        if (count($checkmdreportexist) > 0) {
            $fetachallmappingdata = $this->dao->fetchallmdreport($checkmdreportexist[0]->id);
            $arr_type = array(10, 11, 12, 13, 14, 15, 16);
            foreach ($arr_type as $row) {
                foreach ($fetachallmappingdata as $key => $value) {
                    if ($value->type == $row) {
                        $newArr[$row][$key] = new stdClass();
                        $newArr[$row][$key]->id                     = @$value->id;
                        $newArr[$row][$key]->mdreport_id            = @$value->mdreport_id;
                        $newArr[$row][$key]->major_activity_id      = @$value->major_activity_id;
                        $newArr[$row][$key]->name                   = @$value->name;
                        $newArr[$row][$key]->type                   = @$value->type;
                        $newArr[$row][$key]->total_in_this_month    = @$value->total_in_this_month;
                        $newArr[$row][$key]->target                 = @$value->target;
                        $newArr[$row][$key]->ytm_total              = @$value->ytm_total;
                        $newArr[$row][$key]->last_year_total        = @$value->last_year_total;
                        $newArr[$row][$key]->last_year_ytm          = @$value->last_year_ytm;
                        $newArr[$row][$key]->remarks                = @$value->remarks;
                        $newArr[$row][$key]->total_in_this_month_g  = @$value->total_in_this_month_g;
                        $newArr[$row][$key]->target_g               = @$value->target_g;
                        $newArr[$row][$key]->ytm_total_g            = @$value->ytm_total_g;
                        $newArr[$row][$key]->last_year_ytm_g        = @$value->last_year_ytm_g;
                        $newArr[$row][$key]->pset_last_year         = @$value->pset_last_year;
                        $newArr[$row][$key]->pset_ytm_this_year     = @$value->pset_ytm_this_year;
                        $newArr[$row][$key]->cset_last_year         = @$value->cset_last_year;
                        $newArr[$row][$key]->cset_ytm_this_year     = @$value->cset_ytm_this_year;
                    }
                }
            }
            $_action = $this->bean->get_request("_action");
            if ($_action == "mdReportSubmit") {
                $type2 = $this->bean->get_request("type2");

                $special_activities = $this->bean->get_request("special_activities");
                $report_date = date("Y-m-d", strtotime($this->bean->get_request("report_date")));
                $name = $this->bean->get_request("name");
                $month_year = $this->bean->get_request("month_year");
                $notes = $this->bean->get_request("notes");
                $basicData = array();
                $basicData["special_activities"] = $special_activities;
                $basicData["notes"] = $notes;
                $basicData["report_date"] = $report_date;
                if (isset($_POST["B1"])) {
                    $status = 1;
                } else {
                    $status = 2;
                }
                $basicData["id"] = $checkmdreportexist[0]->id;
                $basicData["status_id"] = $status;
                $this->dao->_table = "mis_mdreport";
                $basic_data_id = $this->dao->save($basicData);
                $newArr2 = array();
                foreach ($type2 as $key => $rowdata) {
                    $remarks = $this->bean->get_request("remarks_" . $key);
                    $insertedrowid = $this->bean->get_request("insertedrowid_" . $key);
                    $newArr2[$key]["id"] = $insertedrowid;
                    $newArr2[$key]["remarks"] = $remarks;
                    $this->dao->_table = "mis_mdreport_mapping";
                    $this->dao->save($newArr2[$key]);
                }
                redirect(page_link("mis/mdReportlistNew.php"));
            }
            $safetymember = $this->dao->get_safety_member();
            $this->beanUi->set_view_data("fetchalldata", $newArr);
            $this->beanUi->set_view_data("selected_month_year", $mnthyr);
            $this->beanUi->set_view_data("mnthyr", $mnthyr);
            $this->beanUi->set_view_data("current_financial_year", $current_financial_year);
            $this->beanUi->set_view_data("previous_financial_year", $previous_financial_year);
            $this->beanUi->set_view_data("rowsingledata", $checkmdreportexist);
            $this->beanUi->set_view_data("safetymember", $safetymember);
            $this->beanUi->set_view_data("mode", $mode);
        }
    }
    public function editmdReportN() {
        $mnthyr = $this->bean->get_request("mnthyr");
        $rowid = $this->bean->get_request("id");
        $mode = $this->bean->get_request("mode");
        if($mnthyr != ""){
        $postValue["month_year"] = "%".$mnthyr."%";
        $checkmdreportexist = $this->dao->checkexistmdreport($postValue);
        }
        $month_year = $mnthyr;
        $month_yearexp = explode("-", $month_year);
        $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
        $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
        $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $cur_fin_y_exp = explode("-", $current_financial_year);
        $curFinYExpF = substr($cur_fin_y_exp[0],2);
        $curFinYExpL = substr($cur_fin_y_exp[1],2);
        $CurrentFY = $curFinYExpF.'-'.$curFinYExpL;
        $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
        $pre_fin_y_exp = explode("-", $previous_financial_year);
        $preFinYExpF = substr($pre_fin_y_exp[0],2);
        $preFinYExpL = substr($pre_fin_y_exp[1],2);
        $PreviousFY = $preFinYExpF.'-'.$preFinYExpL;
//            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");           
//            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            
            
        $prevfyrange = getAllMonthYear($previous_financial_year);
        $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
        $newArr = array();
        if (count($checkmdreportexist) > 0) {
            $fetachallmappingdata = $this->dao->fetchallmdreport($checkmdreportexist[0]->id);
//            $arr_type = array(10, 11, 12, 13, 14, 15, 16);
            $arr_type = array(25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36);
            foreach ($arr_type as $row) {
                foreach ($fetachallmappingdata as $key => $value) {
                    if ($value->type == $row) {
                        $newArr[$row][$key] = new stdClass();
                        $newArr[$row][$key]->id                     = @$value->id;
                        $newArr[$row][$key]->mdreport_id            = @$value->mdreport_id;
                        $newArr[$row][$key]->major_activity_id      = @$value->major_activity_id;
                        $newArr[$row][$key]->name                   = @$value->name;
                        $newArr[$row][$key]->type                   = @$value->type;
                        $newArr[$row][$key]->total_in_this_month    = @$value->total_in_this_month;
                        $newArr[$row][$key]->target                 = @$value->target;
                        $newArr[$row][$key]->ytm_total              = @$value->ytm_total;
                        $newArr[$row][$key]->last_year_total        = @$value->last_year_total;
                        $newArr[$row][$key]->last_year_ytm          = @$value->last_year_ytm;
                        $newArr[$row][$key]->remarks                = @$value->remarks;
                        $newArr[$row][$key]->total_in_this_month_g  = @$value->total_in_this_month_g;
                        $newArr[$row][$key]->target_g               = @$value->target_g;
                        $newArr[$row][$key]->ytm_total_g            = @$value->ytm_total_g;
                        $newArr[$row][$key]->last_year_ytm_g        = @$value->last_year_ytm_g;
                        $newArr[$row][$key]->pset_last_year         = @$value->pset_last_year;
                        $newArr[$row][$key]->pset_ytm_this_year     = @$value->pset_ytm_this_year;
                        $newArr[$row][$key]->cset_last_year         = @$value->cset_last_year;
                        $newArr[$row][$key]->cset_ytm_this_year     = @$value->cset_ytm_this_year;
                    }
                }
            }
            $_action = $this->bean->get_request("_action");
            if ($_action == "mdReportSubmit") {
                $type2 = $this->bean->get_request("type2");

                $special_activities = $this->bean->get_request("special_activities");
                $report_date = date("Y-m-d", strtotime($this->bean->get_request("report_date")));
                $name = $this->bean->get_request("name");
                $month_year = $this->bean->get_request("month_year");
                $notes = $this->bean->get_request("notes");
                $basicData = array();
                $basicData["special_activities"] = $special_activities;
                $basicData["notes"] = $notes;
                $basicData["report_date"] = $report_date;
                $basicData["modified_by"] = '';
                if (isset($_POST["B1"])) {
                    $status = 1;
                } else {
                    $status = 2;
                }
                $basicData["id"] = $checkmdreportexist[0]->id;
                $basicData["status_id"] = $status;
                $this->dao->_table = "mis_mdreport";
                $basic_data_id = $this->dao->save($basicData);
                $newArr2 = array();
                foreach ($type2 as $key => $rowdata) {
                    $remarks = $this->bean->get_request("remarks_" . $key);
                    $insertedrowid = $this->bean->get_request("insertedrowid_" . $key);
                    $newArr2[$key]["id"] = $insertedrowid;
                    $newArr2[$key]["remarks"] = $remarks;
                    $this->dao->_table = "mis_mdreport_mapping";
                    $this->dao->save($newArr2[$key]);
                }
                redirect(page_link("mis/mdReportlistN.php"));
            }
            $safetymember = $this->dao->get_safety_member();
            $this->beanUi->set_view_data("fetchalldata", $newArr);
            $this->beanUi->set_view_data("selected_month_year", $mnthyr);
            $this->beanUi->set_view_data("mnthyr", $mnthyr);
            $this->beanUi->set_view_data("current_financial_year", $current_financial_year);
            $this->beanUi->set_view_data("CurrentFY", $CurrentFY);
            $this->beanUi->set_view_data("previous_financial_year", $previous_financial_year);
            $this->beanUi->set_view_data("PreviousFY", $PreviousFY);
            $this->beanUi->set_view_data("rowsingledata", $checkmdreportexist);
            $this->beanUi->set_view_data("safetymember", $safetymember);
            $this->beanUi->set_view_data("mode", $mode);
        }
    }

    public function mdReport() {
        $_action = $this->bean->get_request("_action");
        $mnthyr = $this->bean->get_request("mnthyr");
        $mode = $this->bean->get_request("mode") ? $this->bean->get_request("mode") : "";
        $rowid = $this->bean->get_request("id");
        $safetymember = array();
        $current_financial_year = $submitdata = "";
        $month_year = $current_financial_year = "";
        $getmajorActivities = $getaccident = $checkmdreportexist = $arrData = $arrData2 = $arrData3 = $safetymember = array();
        if ($_action == "Create") {
            $month_year = $this->bean->get_request("month_year");
            $submitdata = $this->bean->get_request("submitdata");

            $month_yearexp = explode("-", $month_year);
            $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
            ///////TYPE 1 ///////////
            $getmajorActivities = $this->dao->getMajorActivity(1);
            $arrData = array();
            $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $prevfyrange = getAllMonthYear($previous_financial_year);
            $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getytmrange1 = getAllYTMYear($previous_financial_year, $month_yearexp[0]);
            $prevfystartytm = $getytmrange1[0];
            $prevfyendytm = end($getytmrange1);
            $officerTraining1 = $this->dao->getOfficerTraining(1, 5, $month_year_formatted);
            $officerTraining2 = $this->dao->getOfficerTraining(2, 5, $month_year_formatted, $financialyearstart);
            $officerTraining3 = $this->dao->getOfficerTraining(3, 5, $lastfinancialyear);
            
            $supervisorTraining1 = $this->dao->getSupervisorTraining(1, '3,4', $month_year_formatted, '', 'Supervisor');
            $supervisorTraining2 = $this->dao->getSupervisorTraining(2, '3,4', $month_year_formatted, $financialyearstart, 'Supervisor');
            $supervisorTraining3 = $this->dao->getSupervisorTraining(3, '3,4', $lastfinancialyear, '', 'Supervisor');

            $workmanTraining1 = $this->dao->getSupervisorTraining(1, '3,4', $month_year_formatted, '', 'Workman');
            $workmanTraining2 = $this->dao->getSupervisorTraining(2, '3,4', $month_year_formatted, $financialyearstart, 'Workman');
            $workmanTraining3 = $this->dao->getSupervisorTraining(3, '3,4', $lastfinancialyear, '', 'Workman');

            $contractualTraining1 = $this->dao->getOfficerTraining(1, '4', $month_year_formatted);
            $contractualTraining2 = $this->dao->getOfficerTraining(2, '4', $month_year_formatted, $financialyearstart);
            $contractualTraining3 = $this->dao->getOfficerTraining(3, '4', $lastfinancialyear);

            $unitsafeydays1 = $this->dao->getPostCount(1, 4, "activity_date", "activity", $month_year_formatted);
            $unitsafeydays2 = $this->dao->getPostCount(2, 4, "activity_date", "activity", $month_year_formatted, $financialyearstart);
            $unitsafeydays3 = $this->dao->getPostCount(3, 4, "activity_date", "activity", $lastfinancialyear);

            $safetyworkshop1 = $this->dao->getPostCount(1, 1, "activity_date", "activity", $month_year_formatted);
            $safetyworkshop2 = $this->dao->getPostCount(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart);
            $safetyworkshop3 = $this->dao->getPostCount(3, 1, "activity_date", "activity", $lastfinancialyear);

            $commMeeting1 = $this->dao->getPostCount(1, 2, "activity_date", "activity", $month_year_formatted);
            $commMeeting2 = $this->dao->getPostCount(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart);
            $commMeeting3 = $this->dao->getPostCount(3, 2, "activity_date", "activity", $lastfinancialyear);

            $distsiteaudit1 = $this->dao->getSiteAuditCount(1, 5, 'D', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteaudit2 = $this->dao->getSiteAuditCount(2, 5, 'D', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit3 = $this->dao->getSiteAuditCount(3, 5, 'D', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $gensiteaudit1 = $this->dao->getSiteAuditCount(1, 5, 'G', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $gensiteaudit2 = $this->dao->getSiteAuditCount(2, 5, 'G', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $gensiteaudit3 = $this->dao->getSiteAuditCount(3, 5, 'G', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeaudit1 = $this->dao->getPostCount(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted);
            $ppeaudit2 = $this->dao->getPostCount(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart);
            $ppeaudit3 = $this->dao->getPostCount(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear);

            $safobs3 = $this->dao->get_number_safety_observation(1, $prevfyrange);
            $safobs1 = $this->dao->get_number_safety_observation(2, $month_year);
            $safobs2 = $this->dao->get_number_safety_observation(3, $getytmrange);

            $arr = array(
                1 => array(
                    "total_in_this_month" => $officerTraining1,
                    "ytm_total" => $officerTraining2,
                    "last_year_total" => $officerTraining3,
                    "target" => "0"),
                2 => array(
                    "total_in_this_month" => $supervisorTraining1,
                    "ytm_total" => $supervisorTraining2,
                    "last_year_total" => $supervisorTraining3,
                    "target" => "0"),
                3 => array(
                    "total_in_this_month" => $workmanTraining1,
                    "ytm_total" => $workmanTraining2,
                    "last_year_total" => $workmanTraining3,
                    "target" => "0"),
                4 => array(
                    "total_in_this_month" => $contractualTraining1,
                    "ytm_total" => $contractualTraining2,
                    "last_year_total" => $contractualTraining3,
                    "target" => "0"),
                5 => array(
                    "total_in_this_month" => $unitsafeydays1,
                    "ytm_total" => $unitsafeydays2,
                    "last_year_total" => $unitsafeydays3,
                    "target" => "0"),
                6 => array(
                    "total_in_this_month" => $safetyworkshop1,
                    "ytm_total" => $safetyworkshop2,
                    "last_year_total" => $safetyworkshop3,
                    "target" => "0"),
                7 => array(
                    "total_in_this_month" => $commMeeting1,
                    "ytm_total" => $commMeeting2,
                    "last_year_total" => $commMeeting3,
                    "target" => "0"),
                8 => array(
                    "total_in_this_month" => $distsiteaudit1,
                    "ytm_total" => $distsiteaudit2,
                    "last_year_total" => $distsiteaudit3,
                    "target" => "0"),
                9 => array(
                    "total_in_this_month" => $gensiteaudit1,
                    "ytm_total" => $gensiteaudit2,
                    "last_year_total" => $gensiteaudit3,
                    "target" => "0"),
                10 => array(
                    "total_in_this_month" => $ppeaudit1,
                    "ytm_total" => $ppeaudit2,
                    "last_year_total" => $ppeaudit3,
                    "target" => "0"),
                11 => array(
                    "total_in_this_month" => $safobs1,
                    "ytm_total" => $safobs2,
                    "last_year_total" => $safobs3,
                    "target" => "0"),
            );

            foreach ($getmajorActivities as $key => $rowdata) {
                @$arrData[$key]->id = $rowdata->id;
                @$arrData[$key]->name = $rowdata->name;
                @$arrData[$key]->type = $rowdata->type;
                @$arrData[$key]->total_in_this_month = $arr[$rowdata->id]["total_in_this_month"];
                @$arrData[$key]->ytm_total = $arr[$rowdata->id]["ytm_total"];
                @$arrData[$key]->last_year_total = $arr[$rowdata->id]["last_year_total"];
                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                @$arrData[$key]->target = $targetvalue["target"];
            }
            #########################################TYPE 2 START############################################################
            $nearmisscase1 = $this->dao->get_incident_category_wise_count(1, 1, $month_year_formatted);
            $nearmisscase2 = $this->dao->get_incident_category_wise_count(2, 1, $month_year_formatted, $financialyearstart);
            $nearmisscase3 = $this->dao->get_incident_category_wise_count(3, 1, $prevfystartytm, $prevfyendytm);

            $fac1 = $this->dao->get_incident_category_wise_count(1, 2, $month_year_formatted);
            $fac2 = $this->dao->get_incident_category_wise_count(2, 2, $month_year_formatted, $financialyearstart);
            $fac3 = $this->dao->get_incident_category_wise_count(3, 2, $prevfystartytm, $prevfyendytm);

            $lwc1 = $this->dao->get_incident_category_wise_count(1, 3, $month_year_formatted);
            $lwc2 = $this->dao->get_incident_category_wise_count(2, 3, $month_year_formatted, $financialyearstart);
            $lwc3 = $this->dao->get_incident_category_wise_count(3, 3, $prevfystartytm, $prevfyendytm);

            $fatal1 = $this->dao->get_incident_category_wise_count(1, 4, $month_year_formatted);
            $fatal2 = $this->dao->get_incident_category_wise_count(2, 4, $month_year_formatted, $financialyearstart);
            $fatal3 = $this->dao->get_incident_category_wise_count(3, 4, $prevfystartytm, $prevfyendytm);


            $arr2 = array(
                12 => array(
                    "total_in_this_month" => $nearmisscase1,
                    "ytm_total" => $nearmisscase2,
                    "last_year_ytm" => $nearmisscase3,
                    "remarks" => "(Dist.) & (Gen.)"),
                13 => array(
                    "total_in_this_month" => $fac1,
                    "ytm_total" => $fac2,
                    "last_year_ytm" => $fac3,
                    "remarks" => "(Dist.) & (Gen.)"),
                14 => array(
                    "total_in_this_month" => $lwc1,
                    "ytm_total" => $lwc2,
                    "last_year_ytm" => $lwc3,
                    "remarks" => "(Dist.) & (Gen.)"),
                15 => array(
                    "total_in_this_month" => $fatal1,
                    "ytm_total" => $fatal2,
                    "last_year_ytm" => $fatal3,
                    "remarks" => "(Dist.) & (Gen.)")
            );
            
            $getaccident = $this->dao->getMajorActivity(2);


            if (!empty($getaccident)) {
                foreach ($getaccident as $key => $rowdata) {
                    $remrk1 = array();
                    $remrk1 = $this->dao->get_remarks_by_id($rowdata->id, $rowid);
                    $arrData2[$key] = new stdClass();
                    $arrData2[$key]->id = $rowdata->id;
                    $arrData2[$key]->name = $rowdata->name;
                    $arrData2[$key]->type = $rowdata->type;
                    $arrData2[$key]->total_in_this_month = $arr2[$rowdata->id]["total_in_this_month"];
                    $arrData2[$key]->ytm_total = $arr2[$rowdata->id]["ytm_total"];
                    $arrData2[$key]->last_year_ytm = $arr2[$rowdata->id]["last_year_ytm"];
                    $arrData2[$key]->remarks = isset($remrk1[0]->remarks) ? $remrk1[0]->remarks : "(Dist.) & (Gen.)";
                    $arrData2[$key]->insteredrowid = isset($remrk1[0]->id) ? $remrk1[0]->id : 0;
                }
            }

            #####################################TYPE 3 START###########################################
            $district1 = $this->dao->get_site_audit_score('P-SET', 1, array(11, 12, 13, 14, 15, 16, 17, 18, 19, 20), $lastfinancialyear);
            $district2 = $this->dao->get_site_audit_score('P-SET', 2, array(11, 12, 13, 14, 15, 16, 17, 18, 19, 20), $month_year_formatted, $financialyearstart);
            $district3 = $this->dao->get_site_audit_score('C-SET', 1, array(11, 12, 13, 14, 15, 16, 17, 18, 19, 20), $lastfinancialyear);
            $district4 = $this->dao->get_site_audit_score('C-SET', 2, array(11, 12, 13, 14, 15, 16, 17, 18, 19, 20), $month_year_formatted, $financialyearstart);

            $maintenance1 = $this->dao->get_site_audit_score('P-SET', 1, array(10), $lastfinancialyear);
            $maintenance2 = $this->dao->get_site_audit_score('P-SET', 2, array(10), $month_year_formatted, $financialyearstart);
            $maintenance3 = $this->dao->get_site_audit_score('C-SET', 1, array(10), $lastfinancialyear);
            $maintenance4 = $this->dao->get_site_audit_score('C-SET', 2, array(10), $month_year_formatted, $financialyearstart);

            $substn1 = $this->dao->get_site_audit_score('P-SET', 1, array(7), $lastfinancialyear);
            $substn2 = $this->dao->get_site_audit_score('P-SET', 2, array(7), $month_year_formatted, $financialyearstart);
            $substn3 = $this->dao->get_site_audit_score('C-SET', 1, array(7), $lastfinancialyear);
            $substn4 = $this->dao->get_site_audit_score('C-SET', 2, array(7), $month_year_formatted, $financialyearstart);

            $testing1 = $this->dao->get_site_audit_score('P-SET', 1, array(6), $lastfinancialyear);
            $testing2 = $this->dao->get_site_audit_score('P-SET', 2, array(6), $month_year_formatted, $financialyearstart);
            $testing3 = $this->dao->get_site_audit_score('C-SET', 1, array(6), $lastfinancialyear);
            $testing4 = $this->dao->get_site_audit_score('C-SET', 2, array(6), $month_year_formatted, $financialyearstart);

            $acg1 = $this->dao->get_site_audit_score('P-SET', 1, array(8), $lastfinancialyear);
            $acg2 = $this->dao->get_site_audit_score('P-SET', 2, array(8), $month_year_formatted, $financialyearstart);
            $acg3 = $this->dao->get_site_audit_score('C-SET', 1, array(8), $lastfinancialyear);
            $acg4 = $this->dao->get_site_audit_score('C-SET', 2, array(8), $month_year_formatted, $financialyearstart);

            $syscntrl1 = $this->dao->get_site_audit_score('P-SET', 1, array(168), $lastfinancialyear);
            $syscntrl2 = $this->dao->get_site_audit_score('P-SET', 2, array(168), $month_year_formatted, $financialyearstart);
            $syscntrl3 = $this->dao->get_site_audit_score('C-SET', 1, array(168), $lastfinancialyear);
            $syscntrl4 = $this->dao->get_site_audit_score('C-SET', 2, array(168), $month_year_formatted, $financialyearstart);

            $genration1 = $this->dao->get_site_audit_score('P-SET', 1, array(2, 251), $lastfinancialyear);
            $genration2 = $this->dao->get_site_audit_score('P-SET', 2, array(2, 251), $month_year_formatted, $financialyearstart);
            $genration3 = $this->dao->get_site_audit_score('C-SET', 1, array(2, 251), $lastfinancialyear);
            $genration4 = $this->dao->get_site_audit_score('C-SET', 2, array(2, 251), $month_year_formatted, $financialyearstart);

            $arr3 = array(
                16 => array(
                    "pset_last_year" => round($district1["score"], 2),
                    "pset_ytm_this_year" => round($district2["score"], 2),
                    "cset_last_year" => round($district3["score"], 2),
                    "cset_ytm_this_year" => round($district4["score"], 2)),
                17 => array(
                    "pset_last_year" => round($maintenance1["score"], 2),
                    "pset_ytm_this_year" => round($maintenance2["score"], 2),
                    "cset_last_year" => round($maintenance3["score"], 2),
                    "cset_ytm_this_year" => round($maintenance4["score"], 2)),
                18 => array(
                    "pset_last_year" => round($substn1["score"], 2),
                    "pset_ytm_this_year" => round($substn2["score"], 2),
                    "cset_last_year" => round($substn3["score"], 2),
                    "cset_ytm_this_year" => round($substn4["score"], 2)),
                19 => array(
                    "pset_last_year" => round($testing1["score"], 2),
                    "pset_ytm_this_year" => round($testing2["score"], 2),
                    "cset_last_year" => round($testing3["score"], 2),
                    "cset_ytm_this_year" => round($testing4["score"], 2)),
                20 => array(
                    "pset_last_year" => round($acg1["score"], 2),
                    "pset_ytm_this_year" => round($acg2["score"], 2),
                    "cset_last_year" => round($acg3["score"], 2),
                    "cset_ytm_this_year" => round($acg4["score"], 2)),
                21 => array(
                    "pset_last_year" => round($syscntrl1["score"], 2),
                    "pset_ytm_this_year" => round($syscntrl2["score"], 2),
                    "cset_last_year" => round($syscntrl3["score"], 2),
                    "cset_ytm_this_year" => round($syscntrl4["score"], 2)),
                22 => array(
                    "pset_last_year" => round($genration1["score"], 2),
                    "pset_ytm_this_year" => round($genration2["score"], 2),
                    "cset_last_year" => round($genration3["score"], 2),
                    "cset_ytm_this_year" => round($genration4["score"], 2))
            );
            $getsiteauditscore = $this->dao->getMajorActivity(3);
            if (!empty($getsiteauditscore)) {
                foreach ($getsiteauditscore as $key => $rowdata) {
                    $arrData3[$key] = new stdClass();
                    $arrData3[$key]->id = $rowdata->id;
                    $arrData3[$key]->name = $rowdata->name;
                    $arrData3[$key]->type = $rowdata->type;
                    $arrData3[$key]->pset_last_year = $arr3[$rowdata->id]["pset_last_year"];
                    $arrData3[$key]->pset_ytm_this_year = $arr3[$rowdata->id]["pset_ytm_this_year"];
                    $arrData3[$key]->cset_last_year = $arr3[$rowdata->id]["cset_last_year"];
                    $arrData3[$key]->cset_ytm_this_year = $arr3[$rowdata->id]["cset_ytm_this_year"];
                }
            }

            if($month_year != ""){
                $postValue["month_year"] = "%".$month_year."%";
                $checkmdreportexist = $this->dao->checkexistmdreport($postValue);
                }
            if (count($checkmdreportexist) > 0) {
                $ids = $checkmdreportexist[0]->id;
                if ($checkmdreportexist[0]->status_id == 1) {
                    redirect(page_link("mis/editmdReport.php?mnthyr=$month_year&id=$ids"));
                } else {
                    redirect(page_link("mis/editmdReport.php?mnthyr=$month_year&id=$ids&mode=view"));
                }
            }
            $safetymember = $this->dao->get_safety_member();
        }
        if ($_action == "mdReportSubmit") {
            $type1 = $this->bean->get_request("type1");
            $type2 = $this->bean->get_request("type2");
            $type3 = $this->bean->get_request("type3");



            $special_activities = $this->bean->get_request("special_activities");
            $report_date = date("Y-m-d", strtotime($this->bean->get_request("report_date")));
            $notes = $this->bean->get_request("notes");
            $month_year = $this->bean->get_request("month_year");
            $basicData = array();
            $basicData["special_activities"] = $special_activities;
            $basicData["notes"] = $notes;
            $basicData["report_date"] = $report_date;
            $basicData["month_year"] = $month_year;
            if (isset($_POST["B1"])) {
                $status = 1;
            } else {
                $status = 2;
            }


            $basicData["status_id"] = $status;
            $this->dao->_table = "mis_mdreport";
            $basic_data_id = $this->dao->save($basicData);
            $newArr = $newArr2 = $newArr3 = array();
            ###############################################
            foreach ($type1 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_" . $key);
                $target = $this->bean->get_request("target_" . $key);
                $name = $this->bean->get_request("name_" . $key);
                $newArr[$key]["mdreport_id"] = $basic_data_id;
                $newArr[$key]["type"] = $rowdata;
                $newArr[$key]["name"] = $name;
                $newArr[$key]["major_activity_id"] = $major_activity_id;
                $newArr[$key]["target"] = $target;
                $newArr[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr[$key]["ytm_total"] = $ytm_total;
                $newArr[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr[$key]);
            }
            foreach ($type2 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_2" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_2" . $key);
                $last_year_total = $this->bean->get_request("last_year_ytm_2" . $key);
                $remarks = $this->bean->get_request("remarks_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_2" . $key);
                $name = $this->bean->get_request("name_2" . $key);
                $newArr2[$key]["mdreport_id"] = $basic_data_id;
                $newArr2[$key]["type"] = $rowdata;
                $newArr2[$key]["name"] = $name;
                $newArr2[$key]["major_activity_id"] = $major_activity_id;
                $newArr2[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr2[$key]["ytm_total"] = $ytm_total;
                $newArr2[$key]["last_year_ytm"] = $last_year_total;
                $newArr2[$key]["remarks"] = $remarks;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr2[$key]);
            }
            foreach ($type3 as $key => $rowdata) {
                $pset_last_year = $this->bean->get_request("pset_last_year_" . $key);
                $pset_ytm_this_year = $this->bean->get_request("pset_ytm_this_year_" . $key);
                $cset_last_year = $this->bean->get_request("cset_last_year_" . $key);
                $cset_ytm_this_year = $this->bean->get_request("cset_ytm_this_year_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_3" . $key);
                $name = $this->bean->get_request("name_3" . $key);
                $newArr3[$key]["mdreport_id"] = $basic_data_id;
                $newArr3[$key]["type"] = $rowdata;
                $newArr3[$key]["name"] = $name;
                $newArr3[$key]["major_activity_id"] = $major_activity_id;
                $newArr3[$key]["pset_last_year"] = $pset_last_year;
                $newArr3[$key]["pset_ytm_this_year"] = $pset_ytm_this_year;
                $newArr3[$key]["cset_last_year"] = $cset_last_year;
                $newArr3[$key]["cset_ytm_this_year"] = $cset_ytm_this_year;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr3[$key]);
            }
            ###############################################
            redirect(page_link("mis/mdReportlist.php"));
        }
        $this->beanUi->set_view_data("month_year", $month_year);
        $this->beanUi->set_view_data("current_financial_year", $current_financial_year);
        $this->beanUi->set_view_data("majorActivities", $arrData);
        $this->beanUi->set_view_data("getaccident", $arrData2);
        $this->beanUi->set_view_data("getsiteauditscore", $arrData3);
        $this->beanUi->set_view_data("rowsingledata", $checkmdreportexist);
        $this->beanUi->set_view_data("mnthyr", $mnthyr);
        $this->beanUi->set_view_data("submitdata", $submitdata);
        $this->beanUi->set_view_data("safetymember", $safetymember);
        $this->beanUi->set_view_data("mode", $mode);
    }

    public function mdReportNew() {
        $_action = $this->bean->get_request("_action");
        $mnthyr = $this->bean->get_request("mnthyr");
        $mode = $this->bean->get_request("mode") ? $this->bean->get_request("mode") : "";
        $rowid = $this->bean->get_request("id");
        $safetymember = array();
        $current_financial_year = $submitdata = "";
        $month_year = $current_financial_year = "";
        $getmajorActivities_TA_SO = $majorActivitiesDistExHD = $majorActivitiesDistHD = $majorActivitiesDistMSTAST = $majorActivitiesGen = $getaccident = $checkmdreportexist = $arrDataTASO = $arrDataDistExHD = $arrDataDistHD = $arrDataDistMSTAST = $arrDataGen = $arrData2 = $arrData3 = $safetymember = array();
        if ($_action == "Create") {
            $month_year = $this->bean->get_request("month_year");
            $submitdata = $this->bean->get_request("submitdata");

            $month_yearexp = explode("-", $month_year);
            $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
            ///////TYPE 1 ///////////
            $getmajorActivities_TA_SO = $this->dao->getMajorActivity(10);
            $arrDataTASO = array();
            $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $prevfyrange = getAllMonthYear($previous_financial_year);
            $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getytmrange1 = getAllYTMYear($previous_financial_year, $month_yearexp[0]);
            $prevfystartytm = $getytmrange1[0];
            $prevfyendytm = end($getytmrange1);
            
            $permanentTraining1 = $this->dao->getOfficerTrainingNew('1', '3,5', $month_year_formatted);
            $permanentTraining2 = $this->dao->getOfficerTrainingNew('2', '3,5', $month_year_formatted, $financialyearstart);
            $permanentTraining3 = $this->dao->getOfficerTrainingNew('3', '3,5', $lastfinancialyear);

            $contractualTraining1 = $this->dao->getOfficerTrainingNew(1, '4', $month_year_formatted);
            $contractualTraining2 = $this->dao->getOfficerTrainingNew(2, '4', $month_year_formatted, $financialyearstart);
            $contractualTraining3 = $this->dao->getOfficerTrainingNew(3, '4', $lastfinancialyear);

            $safobs3 = $this->dao->get_number_safety_observation(1, $prevfyrange);
            $safobs1 = $this->dao->get_number_safety_observation(2, $month_year);
            $safobs2 = $this->dao->get_number_safety_observation(3, $getytmrange);

            $arrTASO = array(
                65 => array(
                    "total_in_this_month" => $permanentTraining1,
                    "ytm_total" => $permanentTraining2,
                    "last_year_total" => $permanentTraining3,
                    "target" => "0"),
                66 => array(
                    "total_in_this_month" => $contractualTraining1,
                    "ytm_total" => $contractualTraining2,
                    "last_year_total" => $contractualTraining3,
                    "target" => "0"),
                67 => array(
                    "total_in_this_month" => $safobs1,
                    "ytm_total" => $safobs2,
                    "last_year_total" => $safobs3,
                    "target" => "0"),
            );
            foreach ($getmajorActivities_TA_SO as $key => $rowdata) {
                @$arrDataTASO[$key]->id = $rowdata->id;
                @$arrDataTASO[$key]->name = $rowdata->name;
                @$arrDataTASO[$key]->type = $rowdata->type;
                @$arrDataTASO[$key]->total_in_this_month = $arrTASO[$rowdata->id]["total_in_this_month"];
                @$arrDataTASO[$key]->ytm_total = $arrTASO[$rowdata->id]["ytm_total"];
                @$arrDataTASO[$key]->last_year_total = $arrTASO[$rowdata->id]["last_year_total"];
                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                @$arrDataTASO[$key]->target = $targetvalue["target"];
            }


            $majorActivitiesDistExHD = $this->dao->getMajorActivity(11);
            $arrDataDistExHD = array();

            $safetyworkshop1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "DExHD");
            $safetyworkshop2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "DExHD");
            $safetyworkshop3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "DExHD");

            $commMeeting1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "DExHD");
            $commMeeting2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "DExHD");
            $commMeeting3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "DExHD");

            $distsiteaudit1 = $this->dao->getSiteAuditCountNew(1, 5, 'DExHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteaudit2 = $this->dao->getSiteAuditCountNew(2, 5, 'DExHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit3 = $this->dao->getSiteAuditCountNew(3, 5, 'DExHD', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeaudit1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "DExHD");
            $ppeaudit2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "DExHD");
            $ppeaudit3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "DExHD");

            $arrDistExHD = array(
                68 => array(
                    "total_in_this_month" => $safetyworkshop1,
                    "ytm_total" => $safetyworkshop2,
                    "last_year_total" => $safetyworkshop3,
                    "target" => "0"),
                69 => array(
                    "total_in_this_month" => $commMeeting1,
                    "ytm_total" => $commMeeting2,
                    "last_year_total" => $commMeeting3,
                    "target" => "0"),
                70 => array(
                    "total_in_this_month" => $distsiteaudit1,
                    "ytm_total" => $distsiteaudit2,
                    "last_year_total" => $distsiteaudit3,
                    "target" => "0"),
                71 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
                    "last_year_total" => "NA",
                    "target" => "0"),
                72 => array(
                    "total_in_this_month" => $ppeaudit1,
                    "ytm_total" => $ppeaudit2,
                    "last_year_total" => $ppeaudit3,
                    "target" => "0"),
            );
            foreach ($majorActivitiesDistExHD as $key => $rowdata) {
                @$arrDataDistExHD[$key]->id = $rowdata->id;
                @$arrDataDistExHD[$key]->name = $rowdata->name;
                @$arrDataDistExHD[$key]->type = $rowdata->type;
                @$arrDataDistExHD[$key]->total_in_this_month = $arrDistExHD[$rowdata->id]["total_in_this_month"];
                @$arrDataDistExHD[$key]->ytm_total = $arrDistExHD[$rowdata->id]["ytm_total"];
                @$arrDataDistExHD[$key]->last_year_total = $arrDistExHD[$rowdata->id]["last_year_total"];
                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                @$arrDataDistExHD[$key]->target = $targetvalue["target"];
            }

            $majorActivitiesDistHD = $this->dao->getMajorActivity(12);
            $arrDataDistHD = array();

            $safetyworkshop1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "DHD");
            $safetyworkshop2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "DHD");
            $safetyworkshop3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "DHD");

            $commMeeting1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "DHD");
            $commMeeting2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "DHD");
            $commMeeting3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "DHD");

            $distsiteaudit1 = $this->dao->getSiteAuditCountNew(1, 5, 'DHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteaudit2 = $this->dao->getSiteAuditCountNew(2, 5, 'DHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit3 = $this->dao->getSiteAuditCountNew(3, 5, 'DHD', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeaudit1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "DHD");
            $ppeaudit2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "DHD");
            $ppeaudit3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "DHD");

            $arrDistHD = array(
                73 => array(
                    "total_in_this_month" => $safetyworkshop1,
                    "ytm_total" => $safetyworkshop2,
                    "last_year_total" => $safetyworkshop3,
                    "target" => "0"),
                74 => array(
                    "total_in_this_month" => $commMeeting1,
                    "ytm_total" => $commMeeting2,
                    "last_year_total" => $commMeeting3,
                    "target" => "0"),
                75 => array(
                    "total_in_this_month" => $distsiteaudit1,
                    "ytm_total" => $distsiteaudit2,
                    "last_year_total" => $distsiteaudit3,
                    "target" => "0"),
                76 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
                    "last_year_total" => "NA",
                    "target" => "0"),
                77 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
                    "last_year_total" => "NA",
                    "target" => "0"),
                78 => array(
                    "total_in_this_month" => $ppeaudit1,
                    "ytm_total" => $ppeaudit2,
                    "last_year_total" => $ppeaudit3,
                    "target" => "0"),
            );
            foreach ($majorActivitiesDistHD as $key => $rowdata) {
                @$arrDataDistHD[$key]->id = $rowdata->id;
                @$arrDataDistHD[$key]->name = $rowdata->name;
                @$arrDataDistHD[$key]->type = $rowdata->type;
                @$arrDataDistHD[$key]->total_in_this_month = $arrDistHD[$rowdata->id]["total_in_this_month"];
                @$arrDataDistHD[$key]->ytm_total = $arrDistHD[$rowdata->id]["ytm_total"];
                @$arrDataDistHD[$key]->last_year_total = $arrDistHD[$rowdata->id]["last_year_total"];
                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                @$arrDataDistHD[$key]->target = $targetvalue["target"];
            }

            $majorActivitiesDistMSTAST = $this->dao->getMajorActivity(13);
            $arrDataDistMSTAST = array();

            $safetyworkshop1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "MSTAST");
            $safetyworkshop2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "MSTAST");
            $safetyworkshop3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "MSTAST");

            $commMeeting1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "MSTAST");
            $commMeeting2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "MSTAST");
            $commMeeting3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "MSTAST");

            $distsiteaudit1 = $this->dao->getSiteAuditCountNew(1, 5, 'MSTAST', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteaudit2 = $this->dao->getSiteAuditCountNew(2, 5, 'MSTAST', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit3 = $this->dao->getSiteAuditCountNew(3, 5, 'MSTAST', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeaudit1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "MSTAST");
            $ppeaudit2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "MSTAST");
            $ppeaudit3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "MSTAST");

            $arrDistMSTAST = array(
                79 => array(
                    "total_in_this_month" => $safetyworkshop1,
                    "ytm_total" => $safetyworkshop2,
                    "last_year_total" => $safetyworkshop3,
                    "target" => "0"),
                80 => array(
                    "total_in_this_month" => $commMeeting1,
                    "ytm_total" => $commMeeting2,
                    "last_year_total" => $commMeeting3,
                    "target" => "0"),
                81 => array(
                    "total_in_this_month" => $distsiteaudit1,
                    "ytm_total" => $distsiteaudit2,
                    "last_year_total" => $distsiteaudit3,
                    "target" => "0"),
                82 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
                    "last_year_total" => "NA",
                    "target" => "0"),
                83 => array(
                    "total_in_this_month" => $ppeaudit1,
                    "ytm_total" => $ppeaudit2,
                    "last_year_total" => $ppeaudit3,
                    "target" => "0"),
            );
            foreach ($majorActivitiesDistMSTAST as $key => $rowdata) {
                @$arrDataDistMSTAST[$key]->id = $rowdata->id;
                @$arrDataDistMSTAST[$key]->name = $rowdata->name;
                @$arrDataDistMSTAST[$key]->type = $rowdata->type;
                @$arrDataDistMSTAST[$key]->total_in_this_month = $arrDistMSTAST[$rowdata->id]["total_in_this_month"];
                @$arrDataDistMSTAST[$key]->ytm_total = $arrDistMSTAST[$rowdata->id]["ytm_total"];
                @$arrDataDistMSTAST[$key]->last_year_total = $arrDistMSTAST[$rowdata->id]["last_year_total"];
                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                @$arrDataDistMSTAST[$key]->target = $targetvalue["target"];
            }

            $majorActivitiesGen = $this->dao->getMajorActivity(14);
            $arrDataGen = array();

            $safetyworkshop1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "G");
            $safetyworkshop2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "G");
            $safetyworkshop3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "G");

            $commMeeting1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "G");
            $commMeeting2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "G");
            $commMeeting3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "G");

            $distsiteaudit1 = $this->dao->getSiteAuditCountNew(1, 5, 'G', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteaudit2 = $this->dao->getSiteAuditCountNew(2, 5, 'G', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit3 = $this->dao->getSiteAuditCountNew(3, 5, 'G', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeaudit1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "G");
            $ppeaudit2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "G");
            $ppeaudit3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "G");

            $arrGen = array(
                84 => array(
                    "total_in_this_month" => $safetyworkshop1,
                    "ytm_total" => $safetyworkshop2,
                    "last_year_total" => $safetyworkshop3,
                    "target" => "0"),
                85 => array(
                    "total_in_this_month" => $commMeeting1,
                    "ytm_total" => $commMeeting2,
                    "last_year_total" => $commMeeting3,
                    "target" => "0"),
                86 => array(
                    "total_in_this_month" => $distsiteaudit1,
                    "ytm_total" => $distsiteaudit2,
                    "last_year_total" => $distsiteaudit3,
                    "target" => "0"),
                87 => array(
                    "total_in_this_month" => $ppeaudit1,
                    "ytm_total" => $ppeaudit2,
                    "last_year_total" => $ppeaudit3,
                    "target" => "0"),
            );
            foreach ($majorActivitiesGen as $key => $rowdata) {
                @$arrDataGen[$key]->id = $rowdata->id;
                @$arrDataGen[$key]->name = $rowdata->name;
                @$arrDataGen[$key]->type = $rowdata->type;
                @$arrDataGen[$key]->total_in_this_month = $arrGen[$rowdata->id]["total_in_this_month"];
                @$arrDataGen[$key]->ytm_total = $arrGen[$rowdata->id]["ytm_total"];
                @$arrDataGen[$key]->last_year_total = $arrGen[$rowdata->id]["last_year_total"];
                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                @$arrDataGen[$key]->target = $targetvalue["target"];
            }

            #####################################TYPE 15 START###########################################
            $district1 = $this->dao->get_site_audit_score('P-SET', 1, array(11, 12, 13, 14, 15, 16, 17, 18, 20), $lastfinancialyear);
            $district2 = $this->dao->get_site_audit_score('P-SET', 2, array(11, 12, 13, 14, 15, 16, 17, 18, 20), $month_year_formatted, $financialyearstart);
            $district3 = $this->dao->get_site_audit_score('C-SET', 1, array(11, 12, 13, 14, 15, 16, 17, 18, 20), $lastfinancialyear);
            $district4 = $this->dao->get_site_audit_score('C-SET', 2, array(11, 12, 13, 14, 15, 16, 17, 18, 20), $month_year_formatted, $financialyearstart);

            $district11 = $this->dao->get_site_audit_score('P-SET', 1, array(19), $lastfinancialyear);
            $district22 = $this->dao->get_site_audit_score('P-SET', 2, array(19), $month_year_formatted, $financialyearstart);
            $district33 = $this->dao->get_site_audit_score('C-SET', 1, array(19), $lastfinancialyear);
            $district44 = $this->dao->get_site_audit_score('C-SET', 2, array(19), $month_year_formatted, $financialyearstart);

            $maintenance1 = $this->dao->get_site_audit_score('P-SET', 1, array(10), $lastfinancialyear);
            $maintenance2 = $this->dao->get_site_audit_score('P-SET', 2, array(10), $month_year_formatted, $financialyearstart);
            $maintenance3 = $this->dao->get_site_audit_score('C-SET', 1, array(10), $lastfinancialyear);
            $maintenance4 = $this->dao->get_site_audit_score('C-SET', 2, array(10), $month_year_formatted, $financialyearstart);

            $substn1 = $this->dao->get_site_audit_score('P-SET', 1, array(7), $lastfinancialyear);
            $substn2 = $this->dao->get_site_audit_score('P-SET', 2, array(7), $month_year_formatted, $financialyearstart);
            $substn3 = $this->dao->get_site_audit_score('C-SET', 1, array(7), $lastfinancialyear);
            $substn4 = $this->dao->get_site_audit_score('C-SET', 2, array(7), $month_year_formatted, $financialyearstart);

            $testing1 = $this->dao->get_site_audit_score('P-SET', 1, array(6), $lastfinancialyear);
            $testing2 = $this->dao->get_site_audit_score('P-SET', 2, array(6), $month_year_formatted, $financialyearstart);
            $testing3 = $this->dao->get_site_audit_score('C-SET', 1, array(6), $lastfinancialyear);
            $testing4 = $this->dao->get_site_audit_score('C-SET', 2, array(6), $month_year_formatted, $financialyearstart);

            $acg1 = $this->dao->get_site_audit_score('P-SET', 1, array(8), $lastfinancialyear);
            $acg2 = $this->dao->get_site_audit_score('P-SET', 2, array(8), $month_year_formatted, $financialyearstart);
            $acg3 = $this->dao->get_site_audit_score('C-SET', 1, array(8), $lastfinancialyear);
            $acg4 = $this->dao->get_site_audit_score('C-SET', 2, array(8), $month_year_formatted, $financialyearstart);

            $syscntrl1 = $this->dao->get_site_audit_score('P-SET', 1, array(168), $lastfinancialyear);
            $syscntrl2 = $this->dao->get_site_audit_score('P-SET', 2, array(168), $month_year_formatted, $financialyearstart);
            $syscntrl3 = $this->dao->get_site_audit_score('C-SET', 1, array(168), $lastfinancialyear);
            $syscntrl4 = $this->dao->get_site_audit_score('C-SET', 2, array(168), $month_year_formatted, $financialyearstart);

            $genration1 = $this->dao->get_site_audit_score('P-SET', 1, array(2, 251), $lastfinancialyear);
            $genration2 = $this->dao->get_site_audit_score('P-SET', 2, array(2, 251), $month_year_formatted, $financialyearstart);
            $genration3 = $this->dao->get_site_audit_score('C-SET', 1, array(2, 251), $lastfinancialyear);
            $genration4 = $this->dao->get_site_audit_score('C-SET', 2, array(2, 251), $month_year_formatted, $financialyearstart);

            $arr3 = array(
                88 => array(
                    "pset_last_year" => round($district1["score"], 2),
                    "pset_ytm_this_year" => round($district2["score"], 2),
                    "cset_last_year" => round($district3["score"], 2),
                    "cset_ytm_this_year" => round($district4["score"], 2)),
                89 => array(
                    "pset_last_year" => round($district11["score"], 2),
                    "pset_ytm_this_year" => round($district22["score"], 2),
                    "cset_last_year" => round($district33["score"], 2),
                    "cset_ytm_this_year" => round($district44["score"], 2)),
                90 => array(
                    "pset_last_year" => 0,
                    "pset_ytm_this_year" => 0,
                    "cset_last_year" => 0,
                    "cset_ytm_this_year" => 0),
                91 => array(
                    "pset_last_year" => round($maintenance1["score"], 2),
                    "pset_ytm_this_year" => round($maintenance2["score"], 2),
                    "cset_last_year" => round($maintenance3["score"], 2),
                    "cset_ytm_this_year" => round($maintenance4["score"], 2)),
                92 => array(
                    "pset_last_year" => round($substn1["score"], 2),
                    "pset_ytm_this_year" => round($substn2["score"], 2),
                    "cset_last_year" => round($substn3["score"], 2),
                    "cset_ytm_this_year" => round($substn4["score"], 2)),
                93 => array(
                    "pset_last_year" => round($testing1["score"], 2),
                    "pset_ytm_this_year" => round($testing2["score"], 2),
                    "cset_last_year" => round($testing3["score"], 2),
                    "cset_ytm_this_year" => round($testing4["score"], 2)),
                94 => array(
                    "pset_last_year" => round($acg1["score"], 2),
                    "pset_ytm_this_year" => round($acg2["score"], 2),
                    "cset_last_year" => round($acg3["score"], 2),
                    "cset_ytm_this_year" => round($acg4["score"], 2)),
                95 => array(
                    "pset_last_year" => round($syscntrl1["score"], 2),
                    "pset_ytm_this_year" => round($syscntrl2["score"], 2),
                    "cset_last_year" => round($syscntrl3["score"], 2),
                    "cset_ytm_this_year" => round($syscntrl4["score"], 2)),
                96 => array(
                    "pset_last_year" => round($genration1["score"], 2),
                    "pset_ytm_this_year" => round($genration2["score"], 2),
                    "cset_last_year" => round($genration3["score"], 2),
                    "cset_ytm_this_year" => round($genration4["score"], 2))
            );

            $getsiteauditscore = $this->dao->getMajorActivity(15);
            if (!empty($getsiteauditscore)) {
                foreach ($getsiteauditscore as $key => $rowdata) {
                    $arrData3[$key] = new stdClass();
                    $arrData3[$key]->id = $rowdata->id;
                    $arrData3[$key]->name = $rowdata->name;
                    $arrData3[$key]->type = $rowdata->type;
                    $arrData3[$key]->pset_last_year = $arr3[$rowdata->id]["pset_last_year"];
                    $arrData3[$key]->pset_ytm_this_year = $arr3[$rowdata->id]["pset_ytm_this_year"];
                    $arrData3[$key]->cset_last_year = $arr3[$rowdata->id]["cset_last_year"];
                    $arrData3[$key]->cset_ytm_this_year = $arr3[$rowdata->id]["cset_ytm_this_year"];
                }
            }

            #########################################TYPE 16 START############################################################
            $nearmisscase1 = $this->dao->get_incident_category_wise_count_new(1, 1, $month_year_formatted, "", "D");
            $nearmisscase2 = $this->dao->get_incident_category_wise_count_new(2, 1, $month_year_formatted, $financialyearstart, "D");
            $nearmisscase3 = $this->dao->get_incident_category_wise_count_new(3, 1, $prevfystartytm, $prevfyendytm, "D");
            $nearmisscase4 = $this->dao->get_incident_category_wise_count_new(1, 1, $month_year_formatted, "", "G");
            $nearmisscase5 = $this->dao->get_incident_category_wise_count_new(2, 1, $month_year_formatted, $financialyearstart, "G");
            $nearmisscase6 = $this->dao->get_incident_category_wise_count_new(3, 1, $prevfystartytm, $prevfyendytm, "G");

            $fac1 = $this->dao->get_incident_category_wise_count_new(1, 2, $month_year_formatted, "", "D");
            $fac2 = $this->dao->get_incident_category_wise_count_new(2, 2, $month_year_formatted, $financialyearstart, "D");
            $fac3 = $this->dao->get_incident_category_wise_count_new(3, 2, $prevfystartytm, $prevfyendytm, "D");
            $fac4 = $this->dao->get_incident_category_wise_count_new(1, 2, $month_year_formatted, "", "G");
            $fac5 = $this->dao->get_incident_category_wise_count_new(2, 2, $month_year_formatted, $financialyearstart, "G");
            $fac6 = $this->dao->get_incident_category_wise_count_new(3, 2, $prevfystartytm, $prevfyendytm, "G");

            $lwc1 = $this->dao->get_incident_category_wise_count_new(1, 3, $month_year_formatted, "", "D");
            $lwc2 = $this->dao->get_incident_category_wise_count_new(2, 3, $month_year_formatted, $financialyearstart, "D");
            $lwc3 = $this->dao->get_incident_category_wise_count_new(3, 3, $prevfystartytm, $prevfyendytm, "D");
            $lwc4 = $this->dao->get_incident_category_wise_count_new(1, 3, $month_year_formatted, "", "G");
            $lwc5 = $this->dao->get_incident_category_wise_count_new(2, 3, $month_year_formatted, $financialyearstart, "G");
            $lwc6 = $this->dao->get_incident_category_wise_count_new(3, 3, $prevfystartytm, $prevfyendytm, "G");

            $fatal1 = $this->dao->get_incident_category_wise_count_new(1, 4, $month_year_formatted, "", "D");
            $fatal2 = $this->dao->get_incident_category_wise_count_new(2, 4, $month_year_formatted, $financialyearstart, "D");
            $fatal3 = $this->dao->get_incident_category_wise_count_new(3, 4, $prevfystartytm, $prevfyendytm, "D");
            $fatal4 = $this->dao->get_incident_category_wise_count_new(1, 4, $month_year_formatted, "", "G");
            $fatal5 = $this->dao->get_incident_category_wise_count_new(2, 4, $month_year_formatted, $financialyearstart, "G");
            $fatal6 = $this->dao->get_incident_category_wise_count_new(3, 4, $prevfystartytm, $prevfyendytm, "G");


            $arr2 = array(
                97 => array(
                    "total_in_this_month_d" => $nearmisscase1,
                    "ytm_total_d" => $nearmisscase2,
                    "last_year_ytm_d" => $nearmisscase3,
                    "total_in_this_month_g" => $nearmisscase4,
                    "ytm_total_g" => $nearmisscase5,
                    "last_year_ytm_g" => $nearmisscase6
                ),
                98 => array(
                    "total_in_this_month_d" => $fac1,
                    "ytm_total_d" => $fac2,
                    "last_year_ytm_d" => $fac3,
                    "total_in_this_month_g" => $fac4,
                    "ytm_total_g" => $fac5,
                    "last_year_ytm_g" => $fac6
                ),
                99 => array(
                    "total_in_this_month_d" => $lwc1,
                    "ytm_total_d" => $lwc2,
                    "last_year_ytm_d" => $lwc3,
                    "total_in_this_month_g" => $lwc4,
                    "ytm_total_g" => $lwc5,
                    "last_year_ytm_g" => $lwc6
                ),
                100 => array(
                    "total_in_this_month_d" => $fatal1,
                    "ytm_total_d" => $fatal2,
                    "last_year_ytm_d" => $fatal3,
                    "total_in_this_month_g" => $fatal4,
                    "ytm_total_g" => $fatal5,
                    "last_year_ytm_g" => $fatal6
                )
            );

            $getaccident = $this->dao->getMajorActivity(16);


            if (!empty($getaccident)) {
                foreach ($getaccident as $key => $rowdata) {
                    $remrk1 = array();
                    $remrk1 = $this->dao->get_remarks_by_id($rowdata->id, $rowid);
                    $arrData2[$key] = new stdClass();
                    $arrData2[$key]->id = $rowdata->id;
                    $arrData2[$key]->name = $rowdata->name;
                    $arrData2[$key]->type = $rowdata->type;
                    $arrData2[$key]->total_in_this_month_d = $arr2[$rowdata->id]["total_in_this_month_d"];
                    $arrData2[$key]->ytm_total_d = $arr2[$rowdata->id]["ytm_total_d"];
                    $arrData2[$key]->last_year_ytm_d = $arr2[$rowdata->id]["last_year_ytm_d"];
                    $arrData2[$key]->total_in_this_month_g = $arr2[$rowdata->id]["total_in_this_month_g"];
                    $arrData2[$key]->ytm_total_g = $arr2[$rowdata->id]["ytm_total_g"];
                    $arrData2[$key]->last_year_ytm_g = $arr2[$rowdata->id]["last_year_ytm_g"];
                    $arrData2[$key]->remarks = isset($remrk1[0]->remarks) ? $remrk1[0]->remarks : "(Dist.) & (Gen.)";
                    $arrData2[$key]->insteredrowid = isset($remrk1[0]->id) ? $remrk1[0]->id : 0;
                }

            }

            if($month_year != ""){
                $postValue["month_year"] = "%".$month_year."%";
                $checkmdreportexist = $this->dao->checkexistmdreport($postValue);
                }
            if (count($checkmdreportexist) > 0) {
                $ids = $checkmdreportexist[0]->id;
                if ($checkmdreportexist[0]->status_id == 1) {
                    redirect(page_link("mis/editmdReport.php?mnthyr=$month_year&id=$ids"));
                } else {
                    redirect(page_link("mis/editmdReport.php?mnthyr=$month_year&id=$ids&mode=view"));
                }
            }
            $safetymember = $this->dao->get_safety_member();
        }
        if ($_action == "mdReportSubmit") {
            $type1 = $this->bean->get_request("type1");
            $type2 = $this->bean->get_request("type2");
            $type3 = $this->bean->get_request("type3");
            $type4 = $this->bean->get_request("type4");
            $type5 = $this->bean->get_request("type5");
            $type6 = $this->bean->get_request("type6");
            $type7 = $this->bean->get_request("type7");

            $special_activities = $this->bean->get_request("special_activities");
            $report_date = date("Y-m-d", strtotime($this->bean->get_request("report_date")));
            $notes = $this->bean->get_request("notes");
            $month_year = $this->bean->get_request("month_year");
            $basicData = array();
            $basicData["special_activities"] = $special_activities;
            $basicData["notes"] = $notes;
            $basicData["report_date"] = $report_date;
            $basicData["month_year"] = $month_year;
            if (isset($_POST["B1"])) {
                $status = 1;
            } else {
                $status = 2;
            }


            $basicData["status_id"] = $status;
            $this->dao->_table = "mis_mdreport";
            $basic_data_id = $this->dao->save($basicData);
            $newArr1 = $newArr2 = $newArr3 = $newArr4 = array();
            ###############################################
            foreach ($type1 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_TA_SO_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_TA_SO_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_TA_SO_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_TA_SO_" . $key);
                $target = $this->bean->get_request("target_TA_SO_" . $key);
                $name = $this->bean->get_request("name_TA_SO_" . $key);
                $newArr1[$key]["mdreport_id"] = $basic_data_id;
                $newArr1[$key]["type"] = $rowdata;
                $newArr1[$key]["name"] = $name;
                $newArr1[$key]["major_activity_id"] = $major_activity_id;
                $newArr1[$key]["target"] = $target;
                $newArr1[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr1[$key]["ytm_total"] = $ytm_total;
                $newArr1[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr1[$key]);
            }
            foreach ($type4 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_DistExHD_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_DistExHD_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_DistExHD_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_DistExHD_" . $key);
                $target = $this->bean->get_request("target_DistExHD_" . $key);
                $name = $this->bean->get_request("name_DistExHD_" . $key);
                $newArr4[$key]["mdreport_id"] = $basic_data_id;
                $newArr4[$key]["type"] = $rowdata;
                $newArr4[$key]["name"] = $name;
                $newArr4[$key]["major_activity_id"] = $major_activity_id;
                $newArr4[$key]["target"] = $target;
                $newArr4[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr4[$key]["ytm_total"] = $ytm_total;
                $newArr4[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr4[$key]);
            }
            foreach ($type5 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_distHD_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_distHD_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_distHD_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_distHD_" . $key);
                $target = $this->bean->get_request("target_distHD_" . $key);
                $name = $this->bean->get_request("name_distHD_" . $key);
                $newArr5[$key]["mdreport_id"] = $basic_data_id;
                $newArr5[$key]["type"] = $rowdata;
                $newArr5[$key]["name"] = $name;
                $newArr5[$key]["major_activity_id"] = $major_activity_id;
                $newArr5[$key]["target"] = $target;
                $newArr5[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr5[$key]["ytm_total"] = $ytm_total;
                $newArr5[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr5[$key]);
            }
            foreach ($type6 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_distMSTAST_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_distMSTAST_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_distMSTAST_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_distMSTAST_" . $key);
                $target = $this->bean->get_request("target_distMSTAST_" . $key);
                $name = $this->bean->get_request("name_distMSTAST_" . $key);
                $newArr6[$key]["mdreport_id"] = $basic_data_id;
                $newArr6[$key]["type"] = $rowdata;
                $newArr6[$key]["name"] = $name;
                $newArr6[$key]["major_activity_id"] = $major_activity_id;
                $newArr6[$key]["target"] = $target;
                $newArr6[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr6[$key]["ytm_total"] = $ytm_total;
                $newArr6[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr6[$key]);
            }
            foreach ($type7 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_Gen_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_Gen_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_Gen_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_Gen_" . $key);
                $target = $this->bean->get_request("target_Gen_" . $key);
                $name = $this->bean->get_request("name_Gen_" . $key);
                $newArr7[$key]["mdreport_id"] = $basic_data_id;
                $newArr7[$key]["type"] = $rowdata;
                $newArr7[$key]["name"] = $name;
                $newArr7[$key]["major_activity_id"] = $major_activity_id;
                $newArr7[$key]["target"] = $target;
                $newArr7[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr7[$key]["ytm_total"] = $ytm_total;
                $newArr7[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr7[$key]);
            }
            foreach ($type2 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_2" . $key);
                $total_in_this_month22 = $this->bean->get_request("total_in_this_month_22" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_2" . $key);
                $ytm_total22 = $this->bean->get_request("ytm_total_22" . $key);
                $last_year_total = $this->bean->get_request("last_year_ytm_2" . $key);
                $last_year_total22 = $this->bean->get_request("last_year_ytm_22" . $key);
                $remarks = $this->bean->get_request("remarks_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_2" . $key);
                $name = $this->bean->get_request("name_2" . $key);
                $newArr2[$key]["mdreport_id"] = $basic_data_id;
                $newArr2[$key]["type"] = $rowdata;
                $newArr2[$key]["name"] = $name;
                $newArr2[$key]["major_activity_id"] = $major_activity_id;
                $newArr2[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr2[$key]["ytm_total"] = $ytm_total;
                $newArr2[$key]["last_year_ytm"] = $last_year_total;
                $newArr2[$key]["total_in_this_month_g"] = $total_in_this_month22;
                $newArr2[$key]["ytm_total_g"] = $ytm_total22;
                $newArr2[$key]["last_year_ytm_g"] = $last_year_total22;
                $newArr2[$key]["remarks"] = $remarks;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr2[$key]);
            }
            foreach ($type3 as $key => $rowdata) {
                $pset_last_year = $this->bean->get_request("pset_last_year_" . $key);
                $pset_ytm_this_year = $this->bean->get_request("pset_ytm_this_year_" . $key);
                $cset_last_year = $this->bean->get_request("cset_last_year_" . $key);
                $cset_ytm_this_year = $this->bean->get_request("cset_ytm_this_year_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_3" . $key);
                $name = $this->bean->get_request("name_3" . $key);
                $newArr3[$key]["mdreport_id"] = $basic_data_id;
                $newArr3[$key]["type"] = $rowdata;
                $newArr3[$key]["name"] = $name;
                $newArr3[$key]["major_activity_id"] = $major_activity_id;
                $newArr3[$key]["pset_last_year"] = $pset_last_year;
                $newArr3[$key]["pset_ytm_this_year"] = $pset_ytm_this_year;
                $newArr3[$key]["cset_last_year"] = $cset_last_year;
                $newArr3[$key]["cset_ytm_this_year"] = $cset_ytm_this_year;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr3[$key]);
            }
            ###############################################
            redirect(page_link("mis/mdReportlistNew.php"));
        }
        $this->beanUi->set_view_data("month_year", $month_year);
        $this->beanUi->set_view_data("current_financial_year", @$current_financial_year);
        $this->beanUi->set_view_data("previous_financial_year", @$previous_financial_year);
        $this->beanUi->set_view_data("majorActivities_TA_SO", $arrDataTASO);
        $this->beanUi->set_view_data("majorActivitiesDistExHD", $arrDataDistExHD);
        $this->beanUi->set_view_data("majorActivitiesDistHD", $arrDataDistHD);
        $this->beanUi->set_view_data("majorActivitiesDistMSTAST", $arrDataDistMSTAST);
        $this->beanUi->set_view_data("majorActivitiesGen", $arrDataGen);
        $this->beanUi->set_view_data("getaccident", $arrData2);
        $this->beanUi->set_view_data("getsiteauditscore", $arrData3);
        $this->beanUi->set_view_data("rowsingledata", $checkmdreportexist);
        $this->beanUi->set_view_data("mnthyr", $mnthyr);
        $this->beanUi->set_view_data("submitdata", $submitdata);
        $this->beanUi->set_view_data("safetymember", $safetymember);
        $this->beanUi->set_view_data("mode", $mode);
    }
    public function mdReportN() {
        $_action = $this->bean->get_request("_action");
        $mnthyr = $this->bean->get_request("mnthyr");
        $mode = $this->bean->get_request("mode") ? $this->bean->get_request("mode") : "";
        $rowid = $this->bean->get_request("id");
        $safetymember = array();
        $current_financial_year = $submitdata = "";
        $month_year = $current_financial_year = "";
        $CurrentFY = $PreviousFY = "";
        $getmajorActivities_TA_SO = $majorActivitiesDistExHD = $majorActivitiesDistHD = $majorActivitiesDistMSTAST = $majorActivitiesDistMHT = $majorActivitiesDistSC = $majorActivitiesGen = $getaccident = $checkmdreportexist = $arrDataTASO = $arrDataDistExHD = $arrDataDistHD = $arrDataDistMSTAST = $arrDataGen = $arrData2 = $arrData3 = $safetymember = array();
        if ($_action == "Create") {
            $month_year = $this->bean->get_request("month_year");
            $submitdata = $this->bean->get_request("submitdata");

            $month_yearexp = explode("-", $month_year);
            $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
            ///////TYPE 1 ///////////
            $getmajorActivities_TA_SO = $this->dao->getMajorActivity(36);
            $arrDataTASO = array();
            $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
            
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $cur_fin_y_exp = explode("-", $current_financial_year);
            $curFinYExpF = substr($cur_fin_y_exp[0],2);
            $curFinYExpL = substr($cur_fin_y_exp[1],2);
            $CurrentFY = $curFinYExpF.'-'.$curFinYExpL;
            
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $pre_fin_y_exp = explode("-", $previous_financial_year);
            $preFinYExpF = substr($pre_fin_y_exp[0],2);
            $preFinYExpL = substr($pre_fin_y_exp[1],2);
            $PreviousFY = $preFinYExpF.'-'.$preFinYExpL;
                        
            $prevfyrange = getAllMonthYear($previous_financial_year);
            $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getytmrange1 = getAllYTMYear($previous_financial_year, $month_yearexp[0]);
            $prevfystartytm = $getytmrange1[0];
            $prevfyendytm = end($getytmrange1);
            
            $getytmmonthyearrange = getAllYTMMonthYear($current_financial_year, $month_yearexp[0]);
                    
            $permanentTraining1 = $this->dao->getOfficerTrainingNew('1', '3,5', $month_year_formatted,"","D");
            $permanentTraining2 = $this->dao->getOfficerTrainingNew('2', '3,5', $month_year_formatted, $financialyearstart,"D");
            $permanentTraining3 = $this->dao->getOfficerTrainingNew('3', '3,5', $lastfinancialyear,"","D");
            
            $contractualTraining1 = $this->dao->getOfficerTrainingNew(1, '4', $month_year_formatted,"","D");
            $contractualTraining2 = $this->dao->getOfficerTrainingNew(2, '4', $month_year_formatted, $financialyearstart,"D");
            $contractualTraining3 = $this->dao->getOfficerTrainingNew(3, '4', $lastfinancialyear,"","D");

//            $safobsTASO3 = $this->dao->get_number_safety_observation(1, $prevfyrange);
//            $safobsTASO1 = $this->dao->get_number_safety_observation(2, $month_year);
//            $safobsTASO2 = $this->dao->get_number_safety_observation(3, $getytmrange);

            $arrTASO = array(
                186 => array(
                    "total_in_this_month" => $permanentTraining1,
                    "ytm_total" => $permanentTraining2,
//                    "last_year_total" => $permanentTraining3,
                    "target" => 2700),
                187 => array(
                    "total_in_this_month" => $contractualTraining1,
                    "ytm_total" => $contractualTraining2,
//                    "last_year_total" => $contractualTraining3,
                    "target" => 1000),
//                67 => array(
//                    "total_in_this_month" => $safobsTASO1,
//                    "ytm_total" => $safobsTASO2,
//                    "last_year_total" => $safobsTASO3,
//                    "target" => "0"),
            );
            foreach ($getmajorActivities_TA_SO as $key => $rowdata) {
                @$arrDataTASO[$key]->id = $rowdata->id;
                @$arrDataTASO[$key]->name = $rowdata->name;
                @$arrDataTASO[$key]->type = $rowdata->type;
                @$arrDataTASO[$key]->total_in_this_month = $arrTASO[$rowdata->id]["total_in_this_month"];
                @$arrDataTASO[$key]->ytm_total = $arrTASO[$rowdata->id]["ytm_total"];
                @$arrDataTASO[$key]->last_year_total = $arrTASO[$rowdata->id]["last_year_total"];
//                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
//                @$arrDataTASO[$key]->target = $targetvalue["target"];
                @$arrDataTASO[$key]->target = $arrTASO[$rowdata->id]["target"];
            }
            
            //*******************************Gen start*********************************************************
            $majorActivitiesGen = $this->dao->getMajorActivity(25);
            $arrDataGen = array();

            $safetyworkshop1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "G");
            $safetyworkshop2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "G");
//            $safetyworkshop3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "G");

            $commMeeting1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "G");
            $commMeeting2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "G");
            $commMeeting3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "G");

            $distsiteaudit1 = $this->dao->getSiteAuditCountNew(1, 5, 'G', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteaudit2 = $this->dao->getSiteAuditCountNew(2, 5, 'G', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit3 = $this->dao->getSiteAuditCountNew(3, 5, 'G', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeaudit1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "G");
            $ppeaudit2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "G");
            $ppeaudit3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "G");

            $TBMHandHolding1 = "NA";
            $TBMHandHolding2 = $this->dao->getTBMHandHoldingGen(2, "month_year", "mis_mdreport", $month_year_formatted, $financialyearstart,$getytmmonthyearrange);//show($TBMHandHolding2);die;
            $TBMHandHolding3 = "NA";
            
            $safobsGen1 = $this->dao->get_number_safety_observation_New(2, $month_year, "G");
            $safobsGen2 = $this->dao->get_number_safety_observation_New(3, $getytmrange, "G");
            $safobsGen3 = $this->dao->get_number_safety_observation_New(1, $prevfyrange, "G");
            
            $permanentTraining1 = $this->dao->getOfficerTrainingNew('1', '3,5', $month_year_formatted,"","G");
            $permanentTraining2 = $this->dao->getOfficerTrainingNew('2', '3,5', $month_year_formatted, $financialyearstart,"G");
            $permanentTraining3 = $this->dao->getOfficerTrainingNew('3', '3,5', $lastfinancialyear,"","G");

            $contractualTraining1 = $this->dao->getOfficerTrainingNew(1, '4', $month_year_formatted,"","G");
            $contractualTraining2 = $this->dao->getOfficerTrainingNew(2, '4', $month_year_formatted, $financialyearstart,"G");
            $contractualTraining3 = $this->dao->getOfficerTrainingNew(3, '4', $lastfinancialyear,"","G");
            
            $arrGen = array(
                117 => array(
                    "total_in_this_month" => $safetyworkshop1,
                    "ytm_total" => $safetyworkshop2,
//                    "last_year_total" => $safetyworkshop3,
                    "target" => 18),
                118 => array(
                    "total_in_this_month" => $commMeeting1,
                    "ytm_total" => $commMeeting2,
//                    "last_year_total" => $commMeeting3,
                    "target" => 23),
                119 => array(
                    "total_in_this_month" => $distsiteaudit1,
                    "ytm_total" => $distsiteaudit2,
//                    "last_year_total" => $distsiteaudit3,
                    "target" => 620),
                120 => array(
                    "total_in_this_month" => $ppeaudit1,
                    "ytm_total" => $ppeaudit2,
//                    "last_year_total" => $ppeaudit3,
                    "target" => 76),
                121 => array(
                    "total_in_this_month" => $TBMHandHolding1,
                    "ytm_total" => $TBMHandHolding2,
//                    "last_year_total" => $TBMHandHolding3,
                    "target" => 36),
                122 => array(
                    "total_in_this_month" => $safobsGen1,
                    "ytm_total" => $safobsGen2,
//                    "last_year_total" => $safobsGen3,
                    "target" => 288),
                123 => array(
                    "total_in_this_month" => $permanentTraining1,
                    "ytm_total" => $permanentTraining2,
//                    "last_year_total" => $permanentTraining3,
                    "target" => 170),
                124 => array(
                    "total_in_this_month" => $contractualTraining1,
                    "ytm_total" => $contractualTraining2,
//                    "last_year_total" => $contractualTraining3,
                    "target" => 540),
            );
            foreach ($majorActivitiesGen as $key => $rowdata) {
                @$arrDataGen[$key]->id = $rowdata->id;
                @$arrDataGen[$key]->name = $rowdata->name;
                @$arrDataGen[$key]->type = $rowdata->type;
                @$arrDataGen[$key]->total_in_this_month = $arrGen[$rowdata->id]["total_in_this_month"];
                @$arrDataGen[$key]->ytm_total = $arrGen[$rowdata->id]["ytm_total"];
                @$arrDataGen[$key]->last_year_total = $arrGen[$rowdata->id]["last_year_total"];
//                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
//                @$arrDataGen[$key]->target = $targetvalue["target"];
                @$arrDataGen[$key]->target = $arrGen[$rowdata->id]["target"];
            }
            
//************************************************Gen end*****************************************************

//*******************************************distExHD start type 28*****************************************************
            $majorActivitiesDistExHD = $this->dao->getMajorActivity(28);
            $arrDataDistExHD = array();

            $safetyworkshop1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "DExHD");
            $safetyworkshop2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "DExHD");
            $safetyworkshop3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "DExHD");

            $commMeeting1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "DExHD");
            $commMeeting2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "DExHD");
            $commMeeting3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "DExHD");

            $distsiteaudit1 = $this->dao->getSiteAuditCountNew(1, 5, 'DExHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteaudit2 = $this->dao->getSiteAuditCountNew(2, 5, 'DExHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit3 = $this->dao->getSiteAuditCountNew(3, 5, 'DExHD', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeaudit1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "DExHD");
            $ppeaudit2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "DExHD");
            $ppeaudit3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "DExHD");
            
            $TBMHandHoldingDExHD1 = "NA";
            $TBMHandHoldingDExHD2 = $this->dao->getTBMHandHoldingDExHD(2, "month_year", "mis_mdreport", $month_year_formatted, $financialyearstart,$getytmmonthyearrange);//show($TBMHandHolding2);die;
            $TBMHandHoldingDExHD3 = "NA";
            
            $safobs1 = $this->dao->get_number_safety_observation_New(2, $month_year, "DExHD");
            $safobs2 = $this->dao->get_number_safety_observation_New(3, $getytmrange, "DExHD");
            $safobs3 = $this->dao->get_number_safety_observation_New(1, $prevfyrange, "DExHD");

            $arrDistExHD = array(
                138 => array(
                    "total_in_this_month" => $safetyworkshop1,
                    "ytm_total" => $safetyworkshop2,
//                    "last_year_total" => $safetyworkshop3,
                    "target" => 18),
                139 => array(
                    "total_in_this_month" => $commMeeting1,
                    "ytm_total" => $commMeeting2,
//                    "last_year_total" => $commMeeting3,
                    "target" => 18),
                140 => array(
                    "total_in_this_month" => $distsiteaudit1,
                    "ytm_total" => $distsiteaudit2,
//                    "last_year_total" => $distsiteaudit3,
                    "target" => 537),
                141 => array(
                    "total_in_this_month" => $ppeaudit1,
                    "ytm_total" => $ppeaudit2,
//                    "last_year_total" => $ppeaudit3,
                    "target" => 155),
                142 => array(
                    "total_in_this_month" => $TBMHandHoldingDExHD1,
                    "ytm_total" => $TBMHandHoldingDExHD2,
//                    "last_year_total" => $TBMHandHoldingDExHD3,
                    "target" => 75),
                143 => array(
                    "total_in_this_month" => $safobs1,
                    "ytm_total" => $safobs2,
//                    "last_year_total" => $safobs3,
                    "target" => 864),
                
            );
            foreach ($majorActivitiesDistExHD as $key => $rowdata) {
                @$arrDataDistExHD[$key]->id = $rowdata->id;
                @$arrDataDistExHD[$key]->name = $rowdata->name;
                @$arrDataDistExHD[$key]->type = $rowdata->type;
                @$arrDataDistExHD[$key]->total_in_this_month = $arrDistExHD[$rowdata->id]["total_in_this_month"];
                @$arrDataDistExHD[$key]->ytm_total = $arrDistExHD[$rowdata->id]["ytm_total"];
                @$arrDataDistExHD[$key]->last_year_total = $arrDistExHD[$rowdata->id]["last_year_total"];
//                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
//                @$arrDataDistExHD[$key]->target = $targetvalue["target"];
                @$arrDataDistExHD[$key]->target = $arrDistExHD[$rowdata->id]["target"];
            }
//*******************************************distExHD end*****************************************************
//*******************************************distHD start type 29*****************************************************
            $majorActivitiesDistHD = $this->dao->getMajorActivity(29);
            $arrDataDistHD = array();

            $safetyworkshop1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "DHD");
            $safetyworkshop2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "DHD");
            $safetyworkshop3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "DHD");

            $commMeeting1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "DHD");
            $commMeeting2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "DHD");
            $commMeeting3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "DHD");

            $distsiteaudit1 = $this->dao->getSiteAuditCountNew(1, 5, 'DHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteaudit2 = $this->dao->getSiteAuditCountNew(2, 5, 'DHD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit3 = $this->dao->getSiteAuditCountNew(3, 5, 'DHD', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeaudit1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "DHD");
            $ppeaudit2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "DHD");
            $ppeaudit3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "DHD");
            
            $TBMHandHoldingDHD1 = "NA";
            $TBMHandHoldingDHD2 = $this->dao->getTBMHandHoldingDHD(2, "month_year", "mis_mdreport", $month_year_formatted, $financialyearstart,$getytmmonthyearrange);//show($TBMHandHolding2);die;
            $TBMHandHoldingDHD3 = "NA";
            
            $safobs1 = $this->dao->get_number_safety_observation_New(2, $month_year, "DHD");
            $safobs2 = $this->dao->get_number_safety_observation_New(3, $getytmrange, "DHD");
            $safobs3 = $this->dao->get_number_safety_observation_New(1, $prevfyrange, "DHD");
            
            $arrDistHD = array(
                144 => array(
                    "total_in_this_month" => $safetyworkshop1,
                    "ytm_total" => $safetyworkshop2,
//                    "last_year_total" => $safetyworkshop3,
                    "target" => 2),
                145 => array(
                    "total_in_this_month" => $commMeeting1,
                    "ytm_total" => $commMeeting2,
//                    "last_year_total" => $commMeeting3,
                    "target" => 2),
                146 => array(
                    "total_in_this_month" => $distsiteaudit1,
                    "ytm_total" => $distsiteaudit2,
//                    "last_year_total" => $distsiteaudit3,
                    "target" => 101),
                147 => array(
                    "total_in_this_month" => $ppeaudit1,
                    "ytm_total" => $ppeaudit2,
//                    "last_year_total" => $ppeaudit3,
                    "target" => 29),
                148 => array(
                    "total_in_this_month" => $TBMHandHoldingDHD1,
                    "ytm_total" => $TBMHandHoldingDHD2,
//                    "last_year_total" => $TBMHandHoldingDHD3,
                    "target" => 10),
                149 => array(
                    "total_in_this_month" => $safobs1,
                    "ytm_total" => $safobs2,
//                    "last_year_total" => $safobs3,
                    "target" => 96),
                
            );
            foreach ($majorActivitiesDistHD as $key => $rowdata) {
                @$arrDataDistHD[$key]->id = $rowdata->id;
                @$arrDataDistHD[$key]->name = $rowdata->name;
                @$arrDataDistHD[$key]->type = $rowdata->type;
                @$arrDataDistHD[$key]->total_in_this_month = $arrDistHD[$rowdata->id]["total_in_this_month"];
                @$arrDataDistHD[$key]->ytm_total = $arrDistHD[$rowdata->id]["ytm_total"];
                @$arrDataDistHD[$key]->last_year_total = $arrDistHD[$rowdata->id]["last_year_total"];
//                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
//                @$arrDataDistHD[$key]->target = $targetvalue["target"];
                @$arrDataDistHD[$key]->target = $arrDistHD[$rowdata->id]["target"];
            }
//*******************************************distHD end*****************************************************
//*******************************************distMM start type 30*****************************************************
            $majorActivitiesDistMSTAST = $this->dao->getMajorActivity(30);
            $arrDataDistMSTAST = array();

            $safetyworkshopMSTAST1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "MM");
            $safetyworkshopMSTAST2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "MM");
            $safetyworkshopMSTAST3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "MM");

            $commMeetingMSTAST1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "MM");
            $commMeetingMSTAST2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "MM");
            $commMeetingMSTAST3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "MM");

            $distsiteauditMSTAST1 = $this->dao->getSiteAuditCountNew(1, 5, 'MM', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteauditMSTAST2 = $this->dao->getSiteAuditCountNew(2, 5, 'MM', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteauditMSTAST3 = $this->dao->getSiteAuditCountNew(3, 5, 'MM', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeauditMSTAST1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "MM");
            $ppeauditMSTAST2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "MM");
            $ppeauditMSTAST3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "MM");
            
            $safobsMSTAST1 = $this->dao->get_number_safety_observation_New(2, $month_year, "MM");
            $safobsMSTAST2 = $this->dao->get_number_safety_observation_New(3, $getytmrange, "MM");
            $safobsMSTAST3 = $this->dao->get_number_safety_observation_New(1, $prevfyrange, "MM");
            
            $arrDistMSTAST = array(
                150 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
                    "last_year_total" => "NA",
                    "target" => "NA"),
                151 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => $commMeetingMSTAST3,
                    "target" => "NA"),
                152 => array(
                    "total_in_this_month" => $distsiteauditMSTAST1,
                    "ytm_total" => $distsiteauditMSTAST2,
//                    "last_year_total" => $distsiteauditMSTAST3,
                    "target" => 138),
                153 => array(
                    "total_in_this_month" => $ppeauditMSTAST1,
                    "ytm_total" => $ppeauditMSTAST2,
//                    "last_year_total" => $ppeauditMSTAST3,
                    "target" => 46),
                154 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => "NA",
                    "target" => "NA"),
                155 => array(
                    "total_in_this_month" => $safobsMSTAST1,
                    "ytm_total" => $safobsMSTAST2,
//                    "last_year_total" => $safobsMSTAST3,
                    "target" => 48),
                
            );
            foreach ($majorActivitiesDistMSTAST as $key => $rowdata) {
                @$arrDataDistMSTAST[$key]->id = $rowdata->id;
                @$arrDataDistMSTAST[$key]->name = $rowdata->name;
                @$arrDataDistMSTAST[$key]->type = $rowdata->type;
                @$arrDataDistMSTAST[$key]->total_in_this_month = $arrDistMSTAST[$rowdata->id]["total_in_this_month"];
                @$arrDataDistMSTAST[$key]->ytm_total = $arrDistMSTAST[$rowdata->id]["ytm_total"];
                @$arrDataDistMSTAST[$key]->last_year_total = $arrDistMSTAST[$rowdata->id]["last_year_total"];
//                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
//                @$arrDataDistMSTAST[$key]->target = $targetvalue["target"];
                @$arrDataDistMSTAST[$key]->target = $arrDistMSTAST[$rowdata->id]["target"];
            }
//*******************************************distMM end*****************************************************
//*******************************************distMHT start type 31*****************************************************
            $majorActivitiesDistMHT = $this->dao->getMajorActivity(31);
            $arrDataDistMHT = array();

            $safetyworkshopMHT1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "MHT");
            $safetyworkshopMHT2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "MHT");
            $safetyworkshopMHT3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "MHT");

            $commMeetingMHT1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "MHT");
            $commMeetingMHT2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "MHT");
            $commMeetingMHT3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "MHT");

//            $distsiteauditMHT1 = $this->dao->getSiteAuditCountNew(1, 5, 'MHT', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
//            $distsiteauditMHT2 = $this->dao->getSiteAuditCountNew(2, 5, 'MHT', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
//            $distsiteauditMHT3 = $this->dao->getSiteAuditCountNew(3, 5, 'MHT', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);
            
//            $distsiteauditMHT1 = "pk";
//            $distsiteauditMHT2 = $this->dao->getdistsiteauditMHT(2, "month_year", "mis_mdreport", $month_year_formatted, $financialyearstart,$getytmmonthyearrange);//show($TBMHandHolding2);die;
//            $distsiteauditMHT3 = "NA";
            
            $distsiteauditMHT1 = $this->dao->getSiteAuditCountNew(1, 5, 'MHT', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteauditMHT2 = $this->dao->getSiteAuditCountNew(2, 5, 'MHT', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteauditMHT3 = "NA";
            
            $ppeauditMHT1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "MHT");
            $ppeauditMHT2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "MHT");
            $ppeauditMHT3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "MHT");
            
            $safobsMHT1 = $this->dao->get_number_safety_observation_New(2, $month_year, "MHT");//show($safobsMHT1);
            $safobsMHT2 = $this->dao->get_number_safety_observation_New(3, $getytmrange, "MHT");
            $safobsMHT3 = $this->dao->get_number_safety_observation_New(1, $prevfyrange, "MHT");
            
            $arrDistMHT = array(
                156 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => $safetyworkshopMHT3,
                    "target" => "NA"),
                157 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => $commMeetingMHT3,
                    "target" => "NA"),
//                158 => array(
//                    "total_in_this_month" => $distsiteauditMHT1,
//                    "ytm_total" => $distsiteauditMHT2,
////                    "last_year_total" => $distsiteauditMHT3,
//                    "target" => 48),
                158 => array(
                    "total_in_this_month" => $distsiteauditMHT1,
                    "ytm_total" => $distsiteauditMHT2,
//                    "last_year_total" => $distsiteauditMHT3,
                    "target" => 48),
                159 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => $ppeauditMHT3,
                    "target" => "NA"),
                160 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => "NA",
                    "target" => "NA"),
                161 => array(
                    "total_in_this_month" => $safobsMHT1,
                    "ytm_total" => $safobsMHT2,
//                    "last_year_total" => $safobsMHT3,
                    "target" => 96),
                
            );
            foreach ($majorActivitiesDistMHT as $key => $rowdata) {
                @$arrDataDistMHT[$key]->id = $rowdata->id;
                @$arrDataDistMHT[$key]->name = $rowdata->name;
                @$arrDataDistMHT[$key]->type = $rowdata->type;
                @$arrDataDistMHT[$key]->total_in_this_month = $arrDistMHT[$rowdata->id]["total_in_this_month"];
                @$arrDataDistMHT[$key]->ytm_total = $arrDistMHT[$rowdata->id]["ytm_total"];
                @$arrDataDistMHT[$key]->last_year_total = $arrDistMHT[$rowdata->id]["last_year_total"];
//                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
//                @$arrDataDistMHT[$key]->target = $targetvalue["target"];
                @$arrDataDistMHT[$key]->target =  $arrDistMHT[$rowdata->id]["target"];
            }
//*******************************************distMHT end*****************************************************
//*******************************************distSC start type 32*****************************************************
            $majorActivitiesDistSC = $this->dao->getMajorActivity(32);
            $arrDataDistSC = array();

            $safetyworkshopSC1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "SC");
            $safetyworkshopSC2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "SC");
            $safetyworkshopSC3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "SC");

            $commMeetingSC1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "SC");
            $commMeetingSC2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "SC");
            $commMeetingSC3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "SC");

            $distsiteauditSC1 = $this->dao->getSiteAuditCountNew(1, 5, 'SC', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteauditSC2 = $this->dao->getSiteAuditCountNew(2, 5, 'SC', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteauditSC3 = $this->dao->getSiteAuditCountNew(3, 5, 'SC', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeauditSC1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "SC");
            $ppeauditSC2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "SC");
            $ppeauditSC3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "SC");
            
            $safobsSC1 = $this->dao->get_number_safety_observation_New(2, $month_year, "SC");
            $safobsSC2 = $this->dao->get_number_safety_observation_New(3, $getytmrange, "SC");
            $safobsSC3 = $this->dao->get_number_safety_observation_New(1, $prevfyrange, "SC");
            
            $arrDistSC = array(
                162 => array(
                    "total_in_this_month" => $safetyworkshopSC1,
                    "ytm_total" => $safetyworkshopSC2,
//                    "last_year_total" => $safetyworkshopSC3,
                    "target" => 2),
                163 => array(
                    "total_in_this_month" => $commMeetingSC1,
                    "ytm_total" => $commMeetingSC2,
//                    "last_year_total" => $commMeetingSC3,
                    "target" => 2),
                164 => array(
                    "total_in_this_month" => $distsiteauditSC1,
                    "ytm_total" => $distsiteauditSC2,
//                    "last_year_total" => $distsiteauditSC3,
                    "target" => 14),
                165 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => $ppeauditSC3,
                    "target" => "NA"),
                166 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
                    "last_year_total" => "NA",
                    "target" => "NA"),
                167 => array(
                    "total_in_this_month" => $safobsSC1,
                    "ytm_total" => $safobsSC2,
//                    "last_year_total" => $safobsSC3,
                    "target" => 72),
                
            );
            foreach ($majorActivitiesDistSC as $key => $rowdata) {
                @$arrDataDistSC[$key]->id = $rowdata->id;
                @$arrDataDistSC[$key]->name = $rowdata->name;
                @$arrDataDistSC[$key]->type = $rowdata->type;
                @$arrDataDistSC[$key]->total_in_this_month = $arrDistSC[$rowdata->id]["total_in_this_month"];
                @$arrDataDistSC[$key]->ytm_total = $arrDistSC[$rowdata->id]["ytm_total"];
                @$arrDataDistSC[$key]->last_year_total = $arrDistSC[$rowdata->id]["last_year_total"];
//                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
//                @$arrDataDistSC[$key]->target = $targetvalue["target"];
                @$arrDataDistSC[$key]->target = $arrDistSC[$rowdata->id]["target"];
            }
//*******************************************distSC end*****************************************************
//*******************************************distSS start type 33*****************************************************
            $majorActivitiesDistSS = $this->dao->getMajorActivity(33);
            $arrDataDistSS = array();

            $safetyworkshopSS1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "SS");
            $safetyworkshopSS2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "SS");
            $safetyworkshopSS3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "SS");

            $commMeetingSS1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "SS");
            $commMeetingSS2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "SS");
            $commMeetingSS3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "SS");

            $distsiteauditSS1 = $this->dao->getSiteAuditCountNew(1, 5, 'SS', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteauditSS2 = $this->dao->getSiteAuditCountNew(2, 5, 'SS', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteauditSS3 = $this->dao->getSiteAuditCountNew(3, 5, 'SS', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeauditSS1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "SS");
            $ppeauditSS2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "SS");
            $ppeauditSS3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "SS");
            
            $safobsSS1 = $this->dao->get_number_safety_observation_New(2, $month_year, "SS");
            $safobsSS2 = $this->dao->get_number_safety_observation_New(3, $getytmrange, "SS");
            $safobsSS3 = $this->dao->get_number_safety_observation_New(1, $prevfyrange, "SS");
            
            $arrDistSS = array(
                168 => array(
                    "total_in_this_month" => $safetyworkshopSS1,
                    "ytm_total" => $safetyworkshopSS2,
//                    "last_year_total" => $safetyworkshopSS3,
                    "target" => 2),
                169 => array(
                    "total_in_this_month" => $commMeetingSS1,
                    "ytm_total" => $commMeetingSS2,
                    "last_year_total" => $commMeetingSS3,
                    "target" => 2),
                170 => array(
                    "total_in_this_month" => $distsiteauditSS1,
                    "ytm_total" => $distsiteauditSS2,
//                    "last_year_total" => $distsiteauditSS3,
                    "target" => 44),
                171 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => $ppeauditSS3,
                    "target" => "NA"),
                172 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => "NA",
                    "target" => "NA"),
                173 => array(
                    "total_in_this_month" => $safobsSS1,
                    "ytm_total" => $safobsSS2,
//                    "last_year_total" => $safobsSS3,
                    "target" => 120),
                
            );
            foreach ($majorActivitiesDistSS as $key => $rowdata) {
                @$arrDataDistSS[$key]->id = $rowdata->id;
                @$arrDataDistSS[$key]->name = $rowdata->name;
                @$arrDataDistSS[$key]->type = $rowdata->type;
                @$arrDataDistSS[$key]->total_in_this_month = $arrDistSS[$rowdata->id]["total_in_this_month"];
                @$arrDataDistSS[$key]->ytm_total = $arrDistSS[$rowdata->id]["ytm_total"];
                @$arrDataDistSS[$key]->last_year_total = $arrDistSS[$rowdata->id]["last_year_total"];
//                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
//                @$arrDataDistSS[$key]->target = $targetvalue["target"];
                @$arrDataDistSS[$key]->target = $arrDistSS[$rowdata->id]["target"];
            }
//*******************************************distSS end*****************************************************
//*******************************************distACG start type 34*****************************************************
            $majorActivitiesDistACG = $this->dao->getMajorActivity(34);
            $arrDataDistACG = array();

            $safetyworkshopACG1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "ACG");
            $safetyworkshopACG2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "ACG");
            $safetyworkshopACG3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "ACG");

            $commMeetingACG1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "ACG");
            $commMeetingACG2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "ACG");
            $commMeetingACG3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "ACG");

            $distsiteauditACG1 = $this->dao->getSiteAuditCountNew(1, 5, 'ACG', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteauditACG2 = $this->dao->getSiteAuditCountNew(2, 5, 'ACG', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteauditACG3 = $this->dao->getSiteAuditCountNew(3, 5, 'ACG', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeauditACG1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "ACG");
            $ppeauditACG2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "ACG");
            $ppeauditACG3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "ACG");
            
            $safobsACG1 = $this->dao->get_number_safety_observation_New(2, $month_year, "ACG");
            $safobsACG2 = $this->dao->get_number_safety_observation_New(3, $getytmrange, "ACG");
            $safobsACG3 = $this->dao->get_number_safety_observation_New(1, $prevfyrange, "ACG");
            
            $arrDistACG = array(
                174 => array(
                    "total_in_this_month" => $safetyworkshopACG1,
                    "ytm_total" => $safetyworkshopACG2,
//                    "last_year_total" => $safetyworkshopACG3,
                    "target" => 2),
                175 => array(
                    "total_in_this_month" => $commMeetingACG1,
                    "ytm_total" => $commMeetingACG2,
//                    "last_year_total" => $commMeetingACG3,
                    "target" => 2),
                176 => array(
                    "total_in_this_month" => $distsiteauditACG1,
                    "ytm_total" => $distsiteauditACG2,
//                    "last_year_total" => $distsiteauditACG3,
                    "target" => 35),
                177 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => $ppeauditACG3,
                    "target" => "NA"),
                178 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => "NA",
                    "target" => "NA"),
                179 => array(
                    "total_in_this_month" => $safobsACG1,
                    "ytm_total" => $safobsACG2,
//                    "last_year_total" => $safobsACG3,
                    "target" => 672),
                
            );
            foreach ($majorActivitiesDistACG as $key => $rowdata) {
                @$arrDataDistACG[$key]->id = $rowdata->id;
                @$arrDataDistACG[$key]->name = $rowdata->name;
                @$arrDataDistACG[$key]->type = $rowdata->type;
                @$arrDataDistACG[$key]->total_in_this_month = $arrDistACG[$rowdata->id]["total_in_this_month"];
                @$arrDataDistACG[$key]->ytm_total = $arrDistACG[$rowdata->id]["ytm_total"];
                @$arrDataDistACG[$key]->last_year_total = $arrDistACG[$rowdata->id]["last_year_total"];
//                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
//                @$arrDataDistACG[$key]->target = $targetvalue["target"];
                @$arrDataDistACG[$key]->target = $arrDistACG[$rowdata->id]["target"];
            }
//*******************************************distACG end*****************************************************
//*******************************************distTD start type 35*****************************************************
            $majorActivitiesDistTD = $this->dao->getMajorActivity(35);
            $arrDataDistTD = array();

            $safetyworkshopTD1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "TD");
            $safetyworkshopTD2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "TD");
            $safetyworkshopTD3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "TD");

            $commMeetingTD1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "TD");
            $commMeetingTD2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "TD");
            $commMeetingTD3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "TD");

            $distsiteauditTD1 = $this->dao->getSiteAuditCountNew(1, 5, 'TD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
            $distsiteauditTD2 = $this->dao->getSiteAuditCountNew(2, 5, 'TD', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteauditTD3 = $this->dao->getSiteAuditCountNew(3, 5, 'TD', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);

            $ppeauditTD1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "TD");
            $ppeauditTD2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "TD");
            $ppeauditTD3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "TD");
            
            $safobsTD1 = $this->dao->get_number_safety_observation_New(2, $month_year, "TD");
            $safobsTD2 = $this->dao->get_number_safety_observation_New(3, $getytmrange, "TD");
            $safobsTD3 = $this->dao->get_number_safety_observation_New(1, $prevfyrange, "TD");
            
            $arrDistTD = array(
                180 => array(
                    "total_in_this_month" => $safetyworkshopTD1,
                    "ytm_total" => $safetyworkshopTD2,
//                    "last_year_total" => $safetyworkshopTD3,
                    "target" => 2),
                181 => array(
                    "total_in_this_month" => $commMeetingTD1,
                    "ytm_total" => $commMeetingTD2,
//                    "last_year_total" => $commMeetingTD3,
                    "target" => 2),
                182 => array(
                    "total_in_this_month" => $distsiteauditTD1,
                    "ytm_total" => $distsiteauditTD2,
//                    "last_year_total" => $distsiteauditTD3,
                    "target" => 24),
                183 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => $ppeauditTD3,
                    "target" => "NA"),
                184 => array(
                    "total_in_this_month" => "NA",
                    "ytm_total" => "NA",
//                    "last_year_total" => "NA",
                    "target" => "NA"),
                185 => array(
                    "total_in_this_month" => $safobsTD1,
                    "ytm_total" => $safobsTD2,
//                    "last_year_total" => $safobsTD3,
                    "target" => 204),
                
            );
            foreach ($majorActivitiesDistTD as $key => $rowdata) {
                @$arrDataDistTD[$key]->id = $rowdata->id;
                @$arrDataDistTD[$key]->name = $rowdata->name;
                @$arrDataDistTD[$key]->type = $rowdata->type;
                @$arrDataDistTD[$key]->total_in_this_month = $arrDistTD[$rowdata->id]["total_in_this_month"];
                @$arrDataDistTD[$key]->ytm_total = $arrDistTD[$rowdata->id]["ytm_total"];
                @$arrDataDistTD[$key]->last_year_total = $arrDistTD[$rowdata->id]["last_year_total"];
//                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
//                @$arrDataDistTD[$key]->target = $targetvalue["target"];
                @$arrDataDistTD[$key]->target = $arrDistTD[$rowdata->id]["target"];
            }
//*******************************************distTD end*****************************************************
//*******************************************distMSTAST start*****************************************************
//            $majorActivitiesDistMSTAST = $this->dao->getMajorActivity(13);
//            $arrDataDistMSTAST = array();
//
//            $safetyworkshop1 = $this->dao->getPostCountNew(1, 1, "activity_date", "activity", $month_year_formatted, "", "MSTAST");
//            $safetyworkshop2 = $this->dao->getPostCountNew(2, 1, "activity_date", "activity", $month_year_formatted, $financialyearstart, "MSTAST");
//            $safetyworkshop3 = $this->dao->getPostCountNew(3, 1, "activity_date", "activity", $lastfinancialyear, "", "MSTAST");
//
//            $commMeeting1 = $this->dao->getPostCountNew(1, 2, "activity_date", "activity", $month_year_formatted, "", "MSTAST");
//            $commMeeting2 = $this->dao->getPostCountNew(2, 2, "activity_date", "activity", $month_year_formatted, $financialyearstart, "MSTAST");
//            $commMeeting3 = $this->dao->getPostCountNew(3, 2, "activity_date", "activity", $lastfinancialyear, "", "MSTAST");
//
//            $distsiteaudit1 = $this->dao->getSiteAuditCountNew(1, 5, 'MSTAST', "date_of_audit", "actualtarget_audit_view", $month_year_formatted);
//            $distsiteaudit2 = $this->dao->getSiteAuditCountNew(2, 5, 'MSTAST', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
//            $distsiteaudit3 = $this->dao->getSiteAuditCountNew(3, 5, 'MSTAST', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);
//
//            $ppeaudit1 = $this->dao->getPPECountNew(1, 7, "date_of_audit", "ppe_audit", $month_year_formatted, "", "MSTAST");
//            $ppeaudit2 = $this->dao->getPPECountNew(2, 7, "date_of_audit", "ppe_audit", $month_year_formatted, $financialyearstart, "MSTAST");
//            $ppeaudit3 = $this->dao->getPPECountNew(3, 7, "date_of_audit", "ppe_audit", $lastfinancialyear, "", "MSTAST");
//
//            $arrDistMSTAST = array(
//                79 => array(
//                    "total_in_this_month" => $safetyworkshop1,
//                    "ytm_total" => $safetyworkshop2,
//                    "last_year_total" => $safetyworkshop3,
//                    "target" => "0"),
//                80 => array(
//                    "total_in_this_month" => $commMeeting1,
//                    "ytm_total" => $commMeeting2,
//                    "last_year_total" => $commMeeting3,
//                    "target" => "0"),
//                81 => array(
//                    "total_in_this_month" => $distsiteaudit1,
//                    "ytm_total" => $distsiteaudit2,
//                    "last_year_total" => $distsiteaudit3,
//                    "target" => "0"),
//                82 => array(
//                    "total_in_this_month" => "NA",
//                    "ytm_total" => "NA",
//                    "last_year_total" => "NA",
//                    "target" => "0"),
//                83 => array(
//                    "total_in_this_month" => $ppeaudit1,
//                    "ytm_total" => $ppeaudit2,
//                    "last_year_total" => $ppeaudit3,
//                    "target" => "0"),
//            );
//            foreach ($majorActivitiesDistMSTAST as $key => $rowdata) {
//                @$arrDataDistMSTAST[$key]->id = $rowdata->id;
//                @$arrDataDistMSTAST[$key]->name = $rowdata->name;
//                @$arrDataDistMSTAST[$key]->type = $rowdata->type;
//                @$arrDataDistMSTAST[$key]->total_in_this_month = $arrDistMSTAST[$rowdata->id]["total_in_this_month"];
//                @$arrDataDistMSTAST[$key]->ytm_total = $arrDistMSTAST[$rowdata->id]["ytm_total"];
//                @$arrDataDistMSTAST[$key]->last_year_total = $arrDistMSTAST[$rowdata->id]["last_year_total"];
//                @$targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
//                @$arrDataDistMSTAST[$key]->target = $targetvalue["target"];
//            }
//*******************************************distMSTAST end*****************************************************
            #####################################TYPE 27 START###########################################
            $genration1 = $this->dao->get_site_audit_score('P-SET', 1, array(2, 251), $lastfinancialyear);
            $genration2 = $this->dao->get_site_audit_score('P-SET', 2, array(2, 251), $month_year_formatted, $financialyearstart);
            $genration3 = $this->dao->get_site_audit_score('C-SET', 1, array(2, 251), $lastfinancialyear);
            $genration4 = $this->dao->get_site_audit_score('C-SET', 2, array(2, 251), $month_year_formatted, $financialyearstart);

            $district1 = $this->dao->get_site_audit_score('P-SET', 1, array(11, 12, 13, 14, 15, 16, 17, 18, 20), $lastfinancialyear);
            $district2 = $this->dao->get_site_audit_score('P-SET', 2, array(11, 12, 13, 14, 15, 16, 17, 18, 20), $month_year_formatted, $financialyearstart);
            $district3 = $this->dao->get_site_audit_score('C-SET', 1, array(11, 12, 13, 14, 15, 16, 17, 18, 20), $lastfinancialyear);
            $district4 = $this->dao->get_site_audit_score('C-SET', 2, array(11, 12, 13, 14, 15, 16, 17, 18, 20), $month_year_formatted, $financialyearstart);

            $district11 = $this->dao->get_site_audit_score('P-SET', 1, array(19), $lastfinancialyear);
            $district22 = $this->dao->get_site_audit_score('P-SET', 2, array(19), $month_year_formatted, $financialyearstart);
            $district33 = $this->dao->get_site_audit_score('C-SET', 1, array(19), $lastfinancialyear);
            $district44 = $this->dao->get_site_audit_score('C-SET', 2, array(19), $month_year_formatted, $financialyearstart);

            $maintenance1 = $this->dao->get_site_audit_score('P-SET', 1, array(10), $lastfinancialyear);
            $maintenance2 = $this->dao->get_site_audit_score('P-SET', 2, array(10), $month_year_formatted, $financialyearstart);
            $maintenance3 = $this->dao->get_site_audit_score('C-SET', 1, array(10), $lastfinancialyear);
            $maintenance4 = $this->dao->get_site_audit_score('C-SET', 2, array(10), $month_year_formatted, $financialyearstart);
            
            $maintenanceHT1 = $this->dao->get_site_audit_score('P-SET', 1, array(9), $lastfinancialyear);
            $maintenanceHT2 = $this->dao->get_site_audit_score('P-SET', 2, array(9), $month_year_formatted, $financialyearstart);
            $maintenanceHT3 = $this->dao->get_site_audit_score('C-SET', 1, array(9), $lastfinancialyear);
            $maintenanceHT4 = $this->dao->get_site_audit_score('C-SET', 2, array(9), $month_year_formatted, $financialyearstart);

            $substn1 = $this->dao->get_site_audit_score('P-SET', 1, array(7), $lastfinancialyear);
            $substn2 = $this->dao->get_site_audit_score('P-SET', 2, array(7), $month_year_formatted, $financialyearstart);
            $substn3 = $this->dao->get_site_audit_score('C-SET', 1, array(7), $lastfinancialyear);
            $substn4 = $this->dao->get_site_audit_score('C-SET', 2, array(7), $month_year_formatted, $financialyearstart);

            $testing1 = $this->dao->get_site_audit_score('P-SET', 1, array(6), $lastfinancialyear);
            $testing2 = $this->dao->get_site_audit_score('P-SET', 2, array(6), $month_year_formatted, $financialyearstart);
            $testing3 = $this->dao->get_site_audit_score('C-SET', 1, array(6), $lastfinancialyear);
            $testing4 = $this->dao->get_site_audit_score('C-SET', 2, array(6), $month_year_formatted, $financialyearstart);

            $acg1 = $this->dao->get_site_audit_score('P-SET', 1, array(8), $lastfinancialyear);
            $acg2 = $this->dao->get_site_audit_score('P-SET', 2, array(8), $month_year_formatted, $financialyearstart);
            $acg3 = $this->dao->get_site_audit_score('C-SET', 1, array(8), $lastfinancialyear);
            $acg4 = $this->dao->get_site_audit_score('C-SET', 2, array(8), $month_year_formatted, $financialyearstart);

            $syscntrl1 = $this->dao->get_site_audit_score('P-SET', 1, array(168), $lastfinancialyear);
            $syscntrl2 = $this->dao->get_site_audit_score('P-SET', 2, array(168), $month_year_formatted, $financialyearstart);
            $syscntrl3 = $this->dao->get_site_audit_score('C-SET', 1, array(168), $lastfinancialyear);
            $syscntrl4 = $this->dao->get_site_audit_score('C-SET', 2, array(168), $month_year_formatted, $financialyearstart);

//            sprintf("%02d",$expval1)
            $arr3 = array(
                129 => array(
                    "pset_last_year"        => ($genration1["score"] ==0 ? "-" : sprintf("%0.2f",round($genration1["score"], 2))),
                    "pset_ytm_this_year"    => ($genration2["score"] ==0 ? "-" : sprintf("%0.2f",round($genration2["score"], 2))),
                    "cset_last_year"        => ($genration3["score"] ==0 ? "-" : sprintf("%0.2f",round($genration3["score"], 2))),
                    "cset_ytm_this_year"    => ($genration4["score"] ==0 ? "-" : sprintf("%0.2f",round($genration4["score"], 2)))),
                130 => array(
                    "pset_last_year"        => ($district1["score"]==0 ? "-" : sprintf("%0.2f",round($district1["score"], 2))),
                    "pset_ytm_this_year"    => ($district2["score"]==0 ? "-" : sprintf("%0.2f",round($district2["score"], 2))),
                    "cset_last_year"        => ($district3["score"]==0 ? "-" : sprintf("%0.2f",round($district3["score"], 2))),
                    "cset_ytm_this_year"    => ($district4["score"]==0 ? "-" : sprintf("%0.2f",round($district4["score"], 2)))),
                131 => array(
                    "pset_last_year"        => ($district11["score"]==0 ? "-" : sprintf("%0.2f",round($district11["score"], 2))),
                    "pset_ytm_this_year"    => ($district22["score"]==0 ? "-" : sprintf("%0.2f",round($district22["score"], 2))),
                    "cset_last_year"        => ($district33["score"]==0 ? "-" : sprintf("%0.2f",round($district33["score"], 2))),
                    "cset_ytm_this_year"    => ($district44["score"]==0 ? "-" : sprintf("%0.2f",round($district44["score"], 2)))),
//                132 => array(
//                    "pset_last_year" => round($maintenance1["score"], 2),
//                    "pset_ytm_this_year" => round($maintenance2["score"], 2),
//                    "cset_last_year" => round($maintenance3["score"], 2),
//                    "cset_ytm_this_year" => round($maintenance4["score"], 2)),
                132 => array(
                    "pset_last_year"        => "NA",
                    "pset_ytm_this_year"    => "NA",
                    "cset_last_year"        => ($maintenance3["score"]==0 ? "-" : sprintf("%0.2f",round($maintenance3["score"], 2))),
                    "cset_ytm_this_year"    => ($maintenance4["score"]==0 ? "-" : sprintf("%0.2f",round($maintenance4["score"], 2)))),
//                133 => array(
//                    "pset_last_year"        => ($maintenanceHT1["score"]==0 ? "-" : round($maintenanceHT1["score"], 2)),
//                    "pset_ytm_this_year"    => ($maintenanceHT2["score"]==0 ? "-" : round($maintenanceHT2["score"], 2)),
//                    "cset_last_year"        => ($maintenanceHT3["score"]==0 ? "-" : round($maintenanceHT3["score"], 2)),
//                    "cset_ytm_this_year"    => ($maintenanceHT4["score"]==0 ? "-" : round($maintenanceHT4["score"], 2))),
                
                133 => array(
                    "pset_last_year"        => 91.48,
                    "pset_ytm_this_year"    => ($maintenanceHT2["score"]==0 ? "-" : sprintf("%0.2f",round($maintenanceHT2["score"], 2))),
                    "cset_last_year"        => 92.93,
                    "cset_ytm_this_year"    => ($maintenanceHT4["score"]==0 ? "-" : sprintf("%0.2f",round($maintenanceHT4["score"], 2)))),
                
//                133 => array(
//                    "pset_last_year"        => ($maintenanceHT1["score"]==0 ? "-" : sprintf("%0.2f",round($maintenanceHT1["score"], 2))),
//                    "pset_ytm_this_year"    => ($maintenanceHT2["score"]==0 ? "-" : sprintf("%0.2f",round($maintenanceHT2["score"], 2))),
//                    "cset_last_year"        => ($maintenanceHT3["score"]==0 ? "-" : sprintf("%0.2f",round($maintenanceHT3["score"], 2))),
//                    "cset_ytm_this_year"    => ($maintenanceHT4["score"]==0 ? "-" : sprintf("%0.2f",round($maintenanceHT4["score"], 2)))),
//                134 => array(
//                    "pset_last_year" => round($syscntrl1["score"], 2),
//                    "pset_ytm_this_year" => round($syscntrl2["score"], 2),
//                    "cset_last_year" => round($syscntrl3["score"], 2),
//                    "cset_ytm_this_year" => round($syscntrl4["score"], 2)),
                134 => array(
                    "pset_last_year"        => ($syscntrl1["score"]==0 ? "-" : sprintf("%0.2f",round($syscntrl1["score"], 2))),
                    "pset_ytm_this_year"    => ($syscntrl2["score"]==0 ? "-" : sprintf("%0.2f",round($syscntrl2["score"], 2))),
                    "cset_last_year"        => "NA",
                    "cset_ytm_this_year"    => "NA"),
//                135 => array(
//                    "pset_last_year" => round($substn1["score"], 2),
//                    "pset_ytm_this_year" => round($substn2["score"], 2),
//                    "cset_last_year" => round($substn3["score"], 2),
//                    "cset_ytm_this_year" => round($substn4["score"], 2)),
                135 => array(
                    "pset_last_year"        => ($substn1["score"]==0 ? "-" : sprintf("%0.2f",round($substn1["score"], 2))),
                    "pset_ytm_this_year"    => ($substn2["score"]==0 ? "-" : sprintf("%0.2f",round($substn2["score"], 2))),
                    "cset_last_year"        => "NA",
                    "cset_ytm_this_year"    => "NA"),
                136 => array(
                    "pset_last_year"        => ($acg1["score"]==0 ? "-" : sprintf("%0.2f",round($acg1["score"], 2))),
                    "pset_ytm_this_year"    => ($acg2["score"]==0 ? "-" : sprintf("%0.2f",round($acg2["score"], 2))),
                    "cset_last_year"        => ($acg3["score"]==0 ? "-" : sprintf("%0.2f",round($acg3["score"], 2))),
                    "cset_ytm_this_year"    => ($acg4["score"]==0 ? "-" : sprintf("%0.2f",round($acg4["score"], 2)))),
//                137 => array(
//                    "pset_last_year" => round($testing1["score"], 2),
//                    "pset_ytm_this_year" => round($testing2["score"], 2),
//                    "cset_last_year" => round($testing3["score"], 2),
//                    "cset_ytm_this_year" => round($testing4["score"], 2)),
                137 => array(
                    "pset_last_year"        => ($testing1["score"]==0 ? "-" : sprintf("%0.2f",round($testing1["score"], 2))),
                    "pset_ytm_this_year"    => ($testing2["score"]==0 ? "-" : sprintf("%0.2f",round($testing2["score"], 2))),
                    "cset_last_year"        => "NA",
                    "cset_ytm_this_year"    => "NA"),
            
            );

            $getsiteauditscore = $this->dao->getMajorActivity(27);
            if (!empty($getsiteauditscore)) {
                foreach ($getsiteauditscore as $key => $rowdata) {
                    $arrData3[$key] = new stdClass();
                    $arrData3[$key]->id = $rowdata->id;
                    $arrData3[$key]->name = $rowdata->name;
                    $arrData3[$key]->type = $rowdata->type;
                    $arrData3[$key]->pset_last_year = $arr3[$rowdata->id]["pset_last_year"];
                    $arrData3[$key]->pset_ytm_this_year = $arr3[$rowdata->id]["pset_ytm_this_year"];
                    $arrData3[$key]->cset_last_year = $arr3[$rowdata->id]["cset_last_year"];
                    $arrData3[$key]->cset_ytm_this_year = $arr3[$rowdata->id]["cset_ytm_this_year"];
                }
            }

            #########################################TYPE 26 START############################################################
            $nearmisscase1 = $this->dao->get_incident_category_wise_count_new(1, 1, $month_year_formatted, "", "D");
            $nearmisscase2 = $this->dao->get_incident_category_wise_count_new(2, 1, $month_year_formatted, $financialyearstart, "D");
            $nearmisscase3 = $this->dao->get_incident_category_wise_count_new(3, 1, $prevfystartytm, $prevfyendytm, "D");
            $nearmisscase4 = $this->dao->get_incident_category_wise_count_new(1, 1, $month_year_formatted, "", "G");
            $nearmisscase5 = $this->dao->get_incident_category_wise_count_new(2, 1, $month_year_formatted, $financialyearstart, "G");
            $nearmisscase6 = $this->dao->get_incident_category_wise_count_new(3, 1, $prevfystartytm, $prevfyendytm, "G");

            $fac1 = $this->dao->get_incident_category_wise_count_new(1, 2, $month_year_formatted, "", "D");
            $fac2 = $this->dao->get_incident_category_wise_count_new(2, 2, $month_year_formatted, $financialyearstart, "D");
            $fac3 = $this->dao->get_incident_category_wise_count_new(3, 2, $prevfystartytm, $prevfyendytm, "D");
            $fac4 = $this->dao->get_incident_category_wise_count_new(1, 2, $month_year_formatted, "", "G");
            $fac5 = $this->dao->get_incident_category_wise_count_new(2, 2, $month_year_formatted, $financialyearstart, "G");
            $fac6 = $this->dao->get_incident_category_wise_count_new(3, 2, $prevfystartytm, $prevfyendytm, "G");

            $lwc1 = $this->dao->get_incident_category_wise_count_new(1, 3, $month_year_formatted, "", "D");
            $lwc2 = $this->dao->get_incident_category_wise_count_new(2, 3, $month_year_formatted, $financialyearstart, "D");
            $lwc3 = $this->dao->get_incident_category_wise_count_new(3, 3, $prevfystartytm, $prevfyendytm, "D");
            $lwc4 = $this->dao->get_incident_category_wise_count_new(1, 3, $month_year_formatted, "", "G");
            $lwc5 = $this->dao->get_incident_category_wise_count_new(2, 3, $month_year_formatted, $financialyearstart, "G");
            $lwc6 = $this->dao->get_incident_category_wise_count_new(3, 3, $prevfystartytm, $prevfyendytm, "G");

            $fatal1 = $this->dao->get_incident_category_wise_count_new(1, 4, $month_year_formatted, "", "D");
            $fatal2 = $this->dao->get_incident_category_wise_count_new(2, 4, $month_year_formatted, $financialyearstart, "D");
            $fatal3 = $this->dao->get_incident_category_wise_count_new(3, 4, $prevfystartytm, $prevfyendytm, "D");
            $fatal4 = $this->dao->get_incident_category_wise_count_new(1, 4, $month_year_formatted, "", "G");
            $fatal5 = $this->dao->get_incident_category_wise_count_new(2, 4, $month_year_formatted, $financialyearstart, "G");
            $fatal6 = $this->dao->get_incident_category_wise_count_new(3, 4, $prevfystartytm, $prevfyendytm, "G");

            $fatal_gp1 = "NA";
            $fatal_gp2 = $this->dao->getFatalCaseGP(2, "month_year", "mis_mdreport", $month_year_formatted, $financialyearstart,$getytmmonthyearrange);//show($fatal_gp2);die;;
            $fatal_gp3 = "NA";
            $fatal_gp4 = "";
            $fatal_gp5 = "";
            $fatal_gp6 = "";
            
            $arr2 = array(
                125 => array(
                    "total_in_this_month_d" => $nearmisscase1,
                    "ytm_total_d" => $nearmisscase2,
                    "last_year_ytm_d" => $nearmisscase3,
                    "total_in_this_month_g" => $nearmisscase4,
                    "ytm_total_g" => $nearmisscase5,
                    "last_year_ytm_g" => $nearmisscase6
                ),
                126 => array(
                    "total_in_this_month_d" => $fac1,
                    "ytm_total_d" => $fac2,
                    "last_year_ytm_d" => $fac3,
                    "total_in_this_month_g" => $fac4,
                    "ytm_total_g" => $fac5,
                    "last_year_ytm_g" => $fac6
                ),
                127 => array(
                    "total_in_this_month_d" => $lwc1,
                    "ytm_total_d" => $lwc2,
                    "last_year_ytm_d" => $lwc3,
                    "total_in_this_month_g" => $lwc4,
                    "ytm_total_g" => $lwc5,
                    "last_year_ytm_g" => $lwc6
                ),
                128 => array(
                    "total_in_this_month_d" => $fatal1,
                    "ytm_total_d" => $fatal2,
                    "last_year_ytm_d" => $fatal3,
                    "total_in_this_month_g" => $fatal4,
                    "ytm_total_g" => $fatal5,
                    "last_year_ytm_g" => $fatal6
                ),
                188 => array(
                    "total_in_this_month_d" => $fatal_gp1,
                    "ytm_total_d" => $fatal_gp2,
                    "last_year_ytm_d" => $fatal_gp3,
                    "total_in_this_month_g" => $fatal_gp4,
                    "ytm_total_g" => $fatal_gp5,
                    "last_year_ytm_g" => $fatal_gp6
                )
            );

            $getaccident = $this->dao->getMajorActivity(26);


            if (!empty($getaccident)) {
                foreach ($getaccident as $key => $rowdata) {
                    $remrk1 = array();
                    $remrk1 = $this->dao->get_remarks_by_id($rowdata->id, $rowid);
                    $arrData2[$key] = new stdClass();
                    $arrData2[$key]->id = $rowdata->id;
                    $arrData2[$key]->name = $rowdata->name;
                    $arrData2[$key]->type = $rowdata->type;
                    $arrData2[$key]->total_in_this_month_d = $arr2[$rowdata->id]["total_in_this_month_d"];
                    $arrData2[$key]->ytm_total_d = $arr2[$rowdata->id]["ytm_total_d"];
                    $arrData2[$key]->last_year_ytm_d = $arr2[$rowdata->id]["last_year_ytm_d"];
                    $arrData2[$key]->total_in_this_month_g = $arr2[$rowdata->id]["total_in_this_month_g"];
                    $arrData2[$key]->ytm_total_g = $arr2[$rowdata->id]["ytm_total_g"];
                    $arrData2[$key]->last_year_ytm_g = $arr2[$rowdata->id]["last_year_ytm_g"];
                    $arrData2[$key]->remarks = isset($remrk1[0]->remarks) ? $remrk1[0]->remarks : "(Dist.) & (Gen.)";
                    $arrData2[$key]->insteredrowid = isset($remrk1[0]->id) ? $remrk1[0]->id : 0;
                }

            }
            

            if($month_year != ""){
                $postValue["month_year"] = "%".$month_year."%";
                $checkmdreportexist = $this->dao->checkexistmdreport($postValue);
                }
            if (count($checkmdreportexist) > 0) {
                $ids = $checkmdreportexist[0]->id;
                if ($checkmdreportexist[0]->status_id == 1) {
                    redirect(page_link("mis/editmdReport.php?mnthyr=$month_year&id=$ids"));
                } else {
                    redirect(page_link("mis/editmdReport.php?mnthyr=$month_year&id=$ids&mode=view"));
                }
            }
            $safetymember = $this->dao->get_safety_member();
        }
        if ($_action == "mdReportSubmit") {
            $type1  = $this->bean->get_request("type1");
            $type2  = $this->bean->get_request("type2");
            $type22 = $this->bean->get_request("type22");
            $type3  = $this->bean->get_request("type3");
            $type4  = $this->bean->get_request("type4");
            $type5  = $this->bean->get_request("type5");
            $type6  = $this->bean->get_request("type6");
            $type7  = $this->bean->get_request("type7");
            $type8  = $this->bean->get_request("type8");
            $type9  = $this->bean->get_request("type9");
            $type10 = $this->bean->get_request("type10");
            $type11 = $this->bean->get_request("type11");
            $type12 = $this->bean->get_request("type12");

            $special_activities = $this->bean->get_request("special_activities");
            $auth_name = $this->bean->get_request("auth_name");
            $auth_designation = $this->bean->get_request("auth_designation");
            $report_date = date("Y-m-d", strtotime($this->bean->get_request("report_date")));
            $notes = $this->bean->get_request("notes");
            $month_year = $this->bean->get_request("month_year");
            $basicData = array();
            $basicData["special_activities"] = $special_activities;
            $basicData["auth_name"] = $auth_name;
            $basicData["auth_designation"] = $auth_designation;
            $basicData["notes"] = $notes;
            $basicData["report_date"] = $report_date;
            $basicData["month_year"] = $month_year;
            $basicData["created_by"] = '';
            $basicData["modified_by"] = '';
            $basicData["created_date"] = $report_date;
            if (isset($_POST["B1"])) {
                $status = 1;
            } else {
                $status = 2;
            }


            $basicData["status_id"] = $status;
            $this->dao->_table = "mis_mdreport";
            $basic_data_id = $this->dao->save($basicData);
            $newArr1 = $newArr2 = $newArr22 = $newArr3 = $newArr4 = array();
            ###############################################
            foreach ($type1 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_TA_SO_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_TA_SO_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_TA_SO_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_TA_SO_" . $key);
                $target = $this->bean->get_request("target_TA_SO_" . $key);
                $name = $this->bean->get_request("name_TA_SO_" . $key);
                $newArr1[$key]["mdreport_id"] = $basic_data_id;
                $newArr1[$key]["type"] = $rowdata;
                $newArr1[$key]["name"] = $name;
                $newArr1[$key]["major_activity_id"] = $major_activity_id;
                $newArr1[$key]["target"] = $target;
                $newArr1[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr1[$key]["ytm_total"] = $ytm_total;
                $newArr1[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr1[$key]);
            }
            foreach ($type4 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_DistExHD_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_DistExHD_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_DistExHD_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_DistExHD_" . $key);
                $target = $this->bean->get_request("target_DistExHD_" . $key);
                $name = $this->bean->get_request("name_DistExHD_" . $key);
                $newArr4[$key]["mdreport_id"] = $basic_data_id;
                $newArr4[$key]["type"] = $rowdata;
                $newArr4[$key]["name"] = $name;
                $newArr4[$key]["major_activity_id"] = $major_activity_id;
                $newArr4[$key]["target"] = $target;
                $newArr4[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr4[$key]["ytm_total"] = $ytm_total;
                $newArr4[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr4[$key]);
            }
            foreach ($type5 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_distHD_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_distHD_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_distHD_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_distHD_" . $key);
                $target = $this->bean->get_request("target_distHD_" . $key);
                $name = $this->bean->get_request("name_distHD_" . $key);
                $newArr5[$key]["mdreport_id"] = $basic_data_id;
                $newArr5[$key]["type"] = $rowdata;
                $newArr5[$key]["name"] = $name;
                $newArr5[$key]["major_activity_id"] = $major_activity_id;
                $newArr5[$key]["target"] = $target;
                $newArr5[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr5[$key]["ytm_total"] = $ytm_total;
                $newArr5[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr5[$key]);
            }
            //MM
            foreach ($type6 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_distMSTAST_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_distMSTAST_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_distMSTAST_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_distMSTAST_" . $key);
                $target = $this->bean->get_request("target_distMSTAST_" . $key);
                $name = $this->bean->get_request("name_distMSTAST_" . $key);
                $newArr6[$key]["mdreport_id"] = $basic_data_id;
                $newArr6[$key]["type"] = $rowdata;
                $newArr6[$key]["name"] = $name;
                $newArr6[$key]["major_activity_id"] = $major_activity_id;
                $newArr6[$key]["target"] = $target;
                $newArr6[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr6[$key]["ytm_total"] = $ytm_total;
                $newArr6[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr6[$key]);
            }
            //MHT
            foreach ($type8 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_distMHT_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_distMHT_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_distMHT_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_distMHT_" . $key);
                $target = $this->bean->get_request("target_distMHT_" . $key);
                $name = $this->bean->get_request("name_distMHT_" . $key);
                $newArr8[$key]["mdreport_id"] = $basic_data_id;
                $newArr8[$key]["type"] = $rowdata;
                $newArr8[$key]["name"] = $name;
                $newArr8[$key]["major_activity_id"] = $major_activity_id;
                $newArr8[$key]["target"] = $target;
                $newArr8[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr8[$key]["ytm_total"] = $ytm_total;
                $newArr8[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr8[$key]);
            }
            //SC
            foreach ($type9 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_distSC_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_distSC_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_distSC_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_distSC_" . $key);
                $target = $this->bean->get_request("target_distSC_" . $key);
                $name = $this->bean->get_request("name_distSC_" . $key);
                $newArr9[$key]["mdreport_id"] = $basic_data_id;
                $newArr9[$key]["type"] = $rowdata;
                $newArr9[$key]["name"] = $name;
                $newArr9[$key]["major_activity_id"] = $major_activity_id;
                $newArr9[$key]["target"] = $target;
                $newArr9[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr9[$key]["ytm_total"] = $ytm_total;
                $newArr9[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr9[$key]);
            }
            //SS
            foreach ($type10 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_distSS_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_distSS_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_distSS_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_distSS_" . $key);
                $target = $this->bean->get_request("target_distSS_" . $key);
                $name = $this->bean->get_request("name_distSS_" . $key);
                $newArr10[$key]["mdreport_id"] = $basic_data_id;
                $newArr10[$key]["type"] = $rowdata;
                $newArr10[$key]["name"] = $name;
                $newArr10[$key]["major_activity_id"] = $major_activity_id;
                $newArr10[$key]["target"] = $target;
                $newArr10[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr10[$key]["ytm_total"] = $ytm_total;
                $newArr10[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr10[$key]);
            }
            //ACG
            foreach ($type11 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_distACG_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_distACG_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_distACG_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_distACG_" . $key);
                $target = $this->bean->get_request("target_distACG_" . $key);
                $name = $this->bean->get_request("name_distACG_" . $key);
                $newArr11[$key]["mdreport_id"] = $basic_data_id;
                $newArr11[$key]["type"] = $rowdata;
                $newArr11[$key]["name"] = $name;
                $newArr11[$key]["major_activity_id"] = $major_activity_id;
                $newArr11[$key]["target"] = $target;
                $newArr11[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr11[$key]["ytm_total"] = $ytm_total;
                $newArr11[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr11[$key]);
            }
            //TD
            foreach ($type12 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_distTD_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_distTD_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_distTD_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_distTD_" . $key);
                $target = $this->bean->get_request("target_distTD_" . $key);
                $name = $this->bean->get_request("name_distTD_" . $key);
                $newArr12[$key]["mdreport_id"] = $basic_data_id;
                $newArr12[$key]["type"] = $rowdata;
                $newArr12[$key]["name"] = $name;
                $newArr12[$key]["major_activity_id"] = $major_activity_id;
                $newArr12[$key]["target"] = $target;
                $newArr12[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr12[$key]["ytm_total"] = $ytm_total;
                $newArr12[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr12[$key]);
            }
            foreach ($type7 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_Gen_" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_Gen_" . $key);
                $last_year_total = $this->bean->get_request("last_year_total_Gen_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_Gen_" . $key);
                $target = $this->bean->get_request("target_Gen_" . $key);
                $name = $this->bean->get_request("name_Gen_" . $key);
                $newArr7[$key]["mdreport_id"] = $basic_data_id;
                $newArr7[$key]["type"] = $rowdata;
                $newArr7[$key]["name"] = $name;
                $newArr7[$key]["major_activity_id"] = $major_activity_id;
                $newArr7[$key]["target"] = $target;
                $newArr7[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr7[$key]["ytm_total"] = $ytm_total;
                $newArr7[$key]["last_year_total"] = $last_year_total;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr7[$key]);
            }
//            GEN
            foreach ($type22 as $key => $rowdata) {
//                $total_in_this_month = $this->bean->get_request("total_in_this_month_2" . $key);
                $total_in_this_month22 = $this->bean->get_request("total_in_this_month_22" . $key);
//                $ytm_total = $this->bean->get_request("ytm_total_2" . $key);
                $ytm_total22 = $this->bean->get_request("ytm_total_22" . $key);
//                $last_year_total = $this->bean->get_request("last_year_ytm_2" . $key);
                $last_year_total22 = $this->bean->get_request("last_year_ytm_22" . $key);
                $remarks = $this->bean->get_request("remarks_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_22" . $key);
                $name = $this->bean->get_request("name_22" . $key);
                $newArr22[$key]["mdreport_id"] = $basic_data_id;
                $newArr22[$key]["type"] = $rowdata;
                $newArr22[$key]["name"] = $name;
                $newArr22[$key]["major_activity_id"] = $major_activity_id;
//                $newArr22[$key]["total_in_this_month"] = $total_in_this_month;
//                $newArr22[$key]["ytm_total"] = $ytm_total;
//                $newArr22[$key]["last_year_ytm"] = $last_year_total;
                $newArr22[$key]["total_in_this_month_g"] = $total_in_this_month22;
                $newArr22[$key]["ytm_total_g"] = $ytm_total22;
                $newArr22[$key]["last_year_ytm_g"] = $last_year_total22;
                $newArr22[$key]["remarks"] = $remarks;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr22[$key]);
            }
            //DIST
            foreach ($type2 as $key => $rowdata) {
                $total_in_this_month = $this->bean->get_request("total_in_this_month_2" . $key);
//                $total_in_this_month22 = $this->bean->get_request("total_in_this_month_22" . $key);
                $ytm_total = $this->bean->get_request("ytm_total_2" . $key);
//                $ytm_total22 = $this->bean->get_request("ytm_total_22" . $key);
                $last_year_total = $this->bean->get_request("last_year_ytm_2" . $key);
//                $last_year_total22 = $this->bean->get_request("last_year_ytm_22" . $key);
                $remarks = $this->bean->get_request("remarks_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_2" . $key);
                $name = $this->bean->get_request("name_2" . $key);
                $newArr2[$key]["mdreport_id"] = $basic_data_id;
                $newArr2[$key]["type"] = $rowdata;
                $newArr2[$key]["name"] = $name;
                $newArr2[$key]["major_activity_id"] = $major_activity_id;
                $newArr2[$key]["total_in_this_month"] = $total_in_this_month;
                $newArr2[$key]["ytm_total"] = $ytm_total;
                $newArr2[$key]["last_year_ytm"] = $last_year_total;
//                $newArr2[$key]["total_in_this_month_g"] = $total_in_this_month22;
//                $newArr2[$key]["ytm_total_g"] = $ytm_total22;
//                $newArr2[$key]["last_year_ytm_g"] = $last_year_total22;
                $newArr2[$key]["remarks"] = $remarks;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr2[$key]);
            }
            foreach ($type3 as $key => $rowdata) {
                $pset_last_year = $this->bean->get_request("pset_last_year_" . $key);
                $pset_ytm_this_year = $this->bean->get_request("pset_ytm_this_year_" . $key);
                $cset_last_year = $this->bean->get_request("cset_last_year_" . $key);
                $cset_ytm_this_year = $this->bean->get_request("cset_ytm_this_year_" . $key);
                $major_activity_id = $this->bean->get_request("major_activity_id_3" . $key);
                $name = $this->bean->get_request("name_3" . $key);
                $newArr3[$key]["mdreport_id"] = $basic_data_id;
                $newArr3[$key]["type"] = $rowdata;
                $newArr3[$key]["name"] = $name;
                $newArr3[$key]["major_activity_id"] = $major_activity_id;
                $newArr3[$key]["pset_last_year"] = $pset_last_year;
                $newArr3[$key]["pset_ytm_this_year"] = $pset_ytm_this_year;
                $newArr3[$key]["cset_last_year"] = $cset_last_year;
                $newArr3[$key]["cset_ytm_this_year"] = $cset_ytm_this_year;
                $this->dao->_table = "mis_mdreport_mapping";
                $this->dao->save($newArr3[$key]);
            }
            ###############################################
            redirect(page_link("mis/mdReportlistN.php"));
        }
        $this->beanUi->set_view_data("month_year", $month_year);
        $this->beanUi->set_view_data("current_financial_year", @$current_financial_year);
        $this->beanUi->set_view_data("previous_financial_year", @$previous_financial_year);
        $this->beanUi->set_view_data("CurrentFY", $CurrentFY);
        $this->beanUi->set_view_data("PreviousFY", $PreviousFY);
        $this->beanUi->set_view_data("majorActivities_TA_SO", $arrDataTASO);
        $this->beanUi->set_view_data("majorActivitiesDistExHD", $arrDataDistExHD);
        $this->beanUi->set_view_data("majorActivitiesDistHD", $arrDataDistHD);
        $this->beanUi->set_view_data("majorActivitiesDistMSTAST", $arrDataDistMSTAST);
        $this->beanUi->set_view_data("majorActivitiesDistMHT", $arrDataDistMHT);
        $this->beanUi->set_view_data("majorActivitiesDistSC", $arrDataDistSC);
        $this->beanUi->set_view_data("majorActivitiesDistSS", $arrDataDistSS);
        $this->beanUi->set_view_data("majorActivitiesDistACG", $arrDataDistACG);
        $this->beanUi->set_view_data("majorActivitiesDistTD", $arrDataDistTD);
        $this->beanUi->set_view_data("majorActivitiesGen", $arrDataGen);
        $this->beanUi->set_view_data("getaccident", $arrData2);
        $this->beanUi->set_view_data("getsiteauditscore", $arrData3);
        $this->beanUi->set_view_data("rowsingledata", $checkmdreportexist);
        $this->beanUi->set_view_data("mnthyr", $mnthyr);
        $this->beanUi->set_view_data("submitdata", $submitdata);
        $this->beanUi->set_view_data("safetymember", $safetymember);
        $this->beanUi->set_view_data("mode", $mode);
    }
//    public function tBmhh_ytmTotalGen(){
//        $monthYearFrom       =   $this->bean->get_request("monthYearFrom");
//        $query = "SELECT * FROM mis_mdreport WHERE month_year LIKE '%".$monthYearFrom."%'";show($query);
//        $resutl = $this->db->prepare($query);
//    }
    
    public function mdReportlist() {
        $getmddata = $this->dao->getmdreport();
        $this->beanUi->set_view_data("getmddata", $getmddata);
    }

    public function mdReportlistNew() {
        $getmddata = $this->dao->getmdreportnew();
        $this->beanUi->set_view_data("getmddata", $getmddata);
    }
    
    public function mdReportlistN() {
        $getmddata = $this->dao->getmdreportn();
        $this->beanUi->set_view_data("getmddata", $getmddata);
    }

    public function mcmReportlist() {
        $getmcmdata = $this->dao->getmcmreport();
        $this->beanUi->set_view_data("getmcmdata", $getmcmdata);
    }
    
    public function mcmReportlistNew() {
        $getmcmdata = $this->dao->getmcmreportnew();
        $this->beanUi->set_view_data("getmcmdata", $getmcmdata);
    }

//    MIS-03
    public function mainsScoreCardType1Report() {
        $action = $this->bean->get_request("_action");
        if ($action == "submitDate") {
            $SetDMY = array();
            $month_year = $this->bean->get_request("month_year_from");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $month_yearexp = explode("-", $month_year);
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $getcurrytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getprevytmrange = getAllYTMYear($previous_financial_year, $month_yearexp[0]);
            $endcurrent = date("Y-m-t", strtotime(end($getcurrytmrange)));
            $endprev = date("Y-m-t", strtotime(end($getprevytmrange)));
            $currentytm = "'$getcurrytmrange[0]-01' AND '" . $endcurrent . "'";
            $prevytm = "'$getprevytmrange[0]-01' AND '" . $endprev . "'";
            $scoreID = $this->reportdao->getReportSettings(4, "id");
            $rowArray = explode(",", $scoreID[0]->param_value);
            $totaldiv = 0;
            $getAuditData = array();
            foreach ($rowArray as $k => $rr) {
                $clause = " id='" . $rr . "'";
                //-------------------------------All District----------------------------------------//
                $getAuditData['allDistrict'][] = $this->reportdao->get_division_department($clause);
                $arr = array();
                $arr = $rr;
                //--------------------------------Previous Year Data----------------------------------//
                $getAuditData['P_SET_PREV'][] = $this->dao->getAuditData('P-SET', $prevytm, $rr);
                $getAuditData['C_SET_PREV'][] = $this->dao->getAuditData('C-SET', $prevytm, $rr);
                //--------------------------------Current selected Month Data------------------------//
                $getAuditData['P_SET_CURRENT'][] = $this->dao->getAuditData('P-SET', $date_arr['CM_QRY'], $rr);
                $getAuditData['C_SET_CURRENT'][] = $this->dao->getAuditData('C-SET', $date_arr['CM_QRY'], $rr);
                //--------------------------------Current Finalcial Year Data------------------------//
                $getAuditData['P_SET_NEXT'][] = $this->dao->getAuditData('P-SET', $currentytm, $rr);
                $getAuditData['C_SET_NEXT'][] = $this->dao->getAuditData('C-SET', $currentytm, $rr);
            }
            $SetDMY["month_year"] = $month_year;
            $SetDMY["prevYear"] = $date_arr['PFY_QRY'];
            $SetDMY["nextYear"] = $date_arr['CFY_QRY'];
            $this->beanUi->set_view_data("getDate", $SetDMY);
            $this->beanUi->set_view_data("getAuditData", $getAuditData);
        }
    }

    public function otherDeptScoreCardType1Report() {
        $action = $this->bean->get_request("_action");
        if ($action == "submitDate") {
            $SetDMY = array();
            $month_year = $this->bean->get_request("month_year_from");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $month_yearexp = explode("-", $month_year);
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $getcurrytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $endcurrent = date("Y-m-t", strtotime(end($getcurrytmrange)));
            $currentytm = "'$getcurrytmrange[0]-01' AND '" . $endcurrent . "'";
            $scoreID = $this->reportdao->getReportSettings(5, "id");
            $rowArray = explode(",", $scoreID[0]->param_value);
            $totaldiv = 0;

            $getAuditData = array();
            foreach ($rowArray as $k => $rr) {
                $clause = " id='" . $rr . "'";
                $getAuditData['allDistrict'][] = $this->reportdao->get_division_department($clause);
                $getAuditData['P_SET_CURRENT'][] = $this->dao->getAuditDataOrherDeptType1('P-SET', $date_arr['CM_QRY'], $rr);
                $getAuditData['C_SET_CURRENT'][] = $this->dao->getAuditDataOrherDeptType1('C-SET', $date_arr['CM_QRY'], $rr);
                $getAuditData['P_SET_YTM'][] = $this->dao->getAuditDataOrherDeptType1('P-SET', $currentytm, $rr);
                $getAuditData['C_SET_YTM'][] = $this->dao->getAuditDataOrherDeptType1('C-SET', $currentytm, $rr);
            }

            $SetDMY["month_year"] = $month_year;
            $SetDMY["curYear"] = $date_arr['CFY_QRY'];

            $this->beanUi->set_view_data("getDate", $SetDMY);
            $this->beanUi->set_view_data("month_year", $month_year);
            $this->beanUi->set_view_data("getAuditData", $getAuditData);
        }
    }

    public function mainsScoreCardType2Report() { /* modified by anima */
        $action = $this->bean->get_request("_action");
        if ($action == "submitDate") {
            $SetDMY = array();
            //---------START D-M-Y -----//
            $month_year = $this->bean->get_request("month_year_from");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $month_yearexp = explode("-", $month_year);
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $getcurrytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getprevytmrange = getAllYTMYear($previous_financial_year, $month_yearexp[0]);
            $endcurrent = date("Y-m-t", strtotime(end($getcurrytmrange)));
            $endprev = date("Y-m-t", strtotime(end($getprevytmrange)));
            $currentytm = "'$getcurrytmrange[0]-01' AND '" . $endcurrent . "'";
            $prevytm = "'$getprevytmrange[0]-01' AND '" . $endprev . "'";
            $current_financial_year = $date_arr['CFY_FORMATTED'];
            $rowArray = $this->dao->getMajorActivity(7);
            $totaldiv = 0;
            $getAuditData = array();
            foreach ($rowArray as $k => $rr) {
                $getAuditData[$k]['division_id'] = $rr->division_id;
                $clause = " id ='" . $rr->division_id . "' OR ( parent_id='10' AND name ='$rr->name')";
                $val1 = $this->reportdao->get_division_department($clause);
                $arr = array();
                foreach ($val1 as $kk => $vv) {
                    $arr[] = $vv->id;
                }
                $getAuditData[$k]['name'] = $rr->name;
                $getAuditData[$k]['P_SET_PREV'] = $this->dao->getAuditData('P-SET', $prevytm, $arr);
                $getAuditData[$k]['C_SET_PREV'] = $this->dao->getAuditData('C-SET', $prevytm, $arr);
                $getAuditData[$k]['P_SET_CURRENT'] = $this->dao->getAuditData('P-SET', $currentytm, $arr);
                $getAuditData[$k]['C_SET_CURRENT'] = $this->dao->getAuditData('C-SET', $currentytm, $arr);
                $getAuditData[$k]['TARGET'] = $this->dao->get_mis_target($rr->id, $current_financial_year);
            }
            $SetDMY["month_year"] = $month_year;
            $SetDMY["prevYear"] = $date_arr['PFY_QRY'];
            $SetDMY["currentYear"] = $date_arr['CFY_QRY'];
            $this->beanUi->set_view_data("getDate", $SetDMY);
            $this->beanUi->set_view_data("getAuditData", $getAuditData);
        }
    }

    public function otherDeptScoreCardType2Report() {
        $action = $this->bean->get_request("_action");
        if ($action == "submitDate") {
            $SetDMY = array();
            $month_year = $this->bean->get_request("month_year_from");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $month_yearexp = explode("-", $month_year);
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $getcurrytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getprevytmrange = getAllYTMYear($previous_financial_year, $month_yearexp[0]);

            $endcurrent = date("Y-m-t", strtotime(end($getcurrytmrange)));
            $endprev = date("Y-m-t", strtotime(end($getprevytmrange)));
            $currentytm = "'$getcurrytmrange[0]-01' AND '" . $endcurrent . "'";
            $prevytm = "'$getprevytmrange[0]-01' AND '" . $endprev . "'";
            $rowArray = $this->dao->getMajorActivity(8);
            $totaldiv = 0;
            $getAuditData = array();
            foreach ($rowArray as $k => $rr) {
                $getAuditData[$k]['division_id'] = $rr->division_id;
                $getAuditData[$k]['name'] = $rr->name;
                $getAuditData[$k]['P_SET_PREV'] = $this->dao->getAuditDataODSCT2R('P-SET', $prevytm, $rr->division_id);
                $getAuditData[$k]['C_SET_PREV'] = $this->dao->getAuditDataODSCT2R('C-SET', $prevytm, $rr->division_id);

                $getAuditData[$k]['P_SET_CURRENT'] = $this->dao->getAuditDataODSCT2R('P-SET', $currentytm, $rr->division_id);
                $getAuditData[$k]['C_SET_CURRENT'] = $this->dao->getAuditDataODSCT2R('C-SET', $currentytm, $rr->division_id);
                $getAuditData[$k]['TARGET'] = $this->dao->get_mis_target($rr->id, $current_financial_year);
            }
            $SetDMY["month_year"] = $month_year;
            $SetDMY["prevYear"] = $date_arr['PFY_QRY'];
            $SetDMY["currentYear"] = $date_arr['CFY_QRY'];

            $this->beanUi->set_view_data("getDate", $SetDMY);
            $this->beanUi->set_view_data("getAuditData", $getAuditData);
        }
    }

    public function incident10DistAndOtherEstb3YearsReport() {
        $action = $this->bean->get_request("_action");
        if ($action == "submitData") {
            $SetDMY = array();
            $month_year = $this->bean->get_request("month_year_from");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);

            $incident_category_id = $this->reportdao->get_incident_category();
            $REPOTED_ACC_STAT_PSET = array();
            $REPOTED_ACC_STAT_CSET = array();
            $REPOTED_ACC_STAT_PCSET = array();
            $REPOTED_ACC_STAT_OTHERS = array();

            $scoreID = $this->reportdao->getReportSettings(6, "id");
            $rowArray = explode(",", $scoreID[0]->param_value);

            foreach ($rowArray as $k => $rw) {
                $clause = " id='" . $rw . "'";
                $val1 = $this->reportdao->get_division_department($clause);
                $REPOTED_ACC_STAT_PSET[$k]["name"] = $val1[0]->name;
                $REPOTED_ACC_STAT_PSET[$k]["district_id"] = $val1[0]->id;
                $REPOTED_ACC_STAT_CSET[$k]["name"] = $val1[0]->name;
                $REPOTED_ACC_STAT_CSET[$k]["district_id"] = $val1[0]->id;
                $REPOTED_ACC_STAT_PCSET[$k]["name"] = $val1[0]->name;
                $REPOTED_ACC_STAT_PCSET[$k]["district_id"] = $val1[0]->id;
                $REPOTED_ACC_STAT_OTHERS[$k]["name"] = $val1[0]->name;
                $REPOTED_ACC_STAT_OTHERS[$k]["district_id"] = $val1[0]->id;
                //------P-SET-----//
                foreach ($incident_category_id as $key => $rowdata) {
                    if ($rowdata->id != 1) {
                        $REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["NAME"] = $rowdata->name;
                        $REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["ID"] = $rowdata->id;
                        $REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["PREV_YEAR2"] = $this->dao->getReportedAccidentStatistics("P-SET", $date_arr['POPFY_QRY'], $rw, $rowdata->id);
                        $REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["PREV_YEAR1"] = $this->dao->getReportedAccidentStatistics("P-SET", $date_arr['PFY_QRY'], $rw, $rowdata->id);
                        $REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["CURRENT_YEAR"] = $this->dao->getReportedAccidentStatistics("P-SET", $date_arr['CFY_YTM_QRY'], $rw, $rowdata->id);
                    }
                }

                //------END P-SET-----//
                //------C-SET-----//
                foreach ($incident_category_id as $key => $rowdata) {
                    if ($rowdata->id != 1) {
                        $REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["NAME"] = $rowdata->name;
                        $REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["ID"] = $rowdata->id;
                        $REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["PREV_YEAR2"] = $this->dao->getReportedAccidentStatistics("C-SET", $date_arr['POPFY_QRY'], $rw, $rowdata->id);
                        $REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["PREV_YEAR1"] = $this->dao->getReportedAccidentStatistics("C-SET", $date_arr['PFY_QRY'], $rw, $rowdata->id);
                        $REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["CURRENT_YEAR"] = $this->dao->getReportedAccidentStatistics("C-SET", $date_arr['CFY_YTM_QRY'], $rw, $rowdata->id);
                    }
                }
                //------END C-SET-----// 
                //------PC-SET-----//
                foreach ($incident_category_id as $key => $rowdata) {
                    if ($rowdata->id != 1) {
                        $REPOTED_ACC_STAT_PCSET[$k]["INCCAT"][$key]["NAME"] = $rowdata->name;
                        $REPOTED_ACC_STAT_PCSET[$k]["INCCAT"][$key]["PREV_YEAR2"] = $this->dao->getReportedAccidentStatistics("PC-SET", $date_arr['POPFY_QRY'], $rw, $rowdata->id);
                        $REPOTED_ACC_STAT_PCSET[$k]["INCCAT"][$key]["PREV_YEAR1"] = $this->dao->getReportedAccidentStatistics("PC-SET", $date_arr['PFY_QRY'], $rw, $rowdata->id);
                        $REPOTED_ACC_STAT_PCSET[$k]["INCCAT"][$key]["CURRENT_YEAR"] = $this->dao->getReportedAccidentStatistics("PC-SET", $date_arr['CFY_QRY'], $rw, $rowdata->id);
                    }
                }
                //------END PC-SET-----// 
                //------OTHERS-----//
                foreach ($incident_category_id as $key => $rowdata) {
                    if ($rowdata->id != 1) {
                        $REPOTED_ACC_STAT_OTHERS[$k]["INCCAT"][$key]["NAME"] = $rowdata->name;
                        $REPOTED_ACC_STAT_OTHERS[$k]["INCCAT"][$key]["PREV_YEAR2"] = $this->dao->getReportedAccidentStatistics("OTHERS", $date_arr['POPFY_QRY'], $rw, $rowdata->id);
                        $REPOTED_ACC_STAT_OTHERS[$k]["INCCAT"][$key]["PREV_YEAR1"] = $this->dao->getReportedAccidentStatistics("OTHERS", $date_arr['PFY_QRY'], $rw, $rowdata->id);
                        $REPOTED_ACC_STAT_OTHERS[$k]["INCCAT"][$key]["CURRENT_YEAR"] = $this->dao->getReportedAccidentStatistics("OTHERS", $date_arr['CFY_QRY'], $rw, $rowdata->id);
                    }
                }
                //------OTHERS-----// 
            }
            @$SetDMY["PREV_Y2"] = $date_arr['POPFY_QRY'];
            @$SetDMY["PREV_Y1"] = $date_arr['PFY_QRY'];
            @$SetDMY["CURR_Y"] = $date_arr['CFY_QRY'];
            $this->beanUi->set_view_data("getDate", $SetDMY);
            $this->beanUi->set_view_data("REPOTED_ACC_STAT_PSET", $REPOTED_ACC_STAT_PSET);
            $this->beanUi->set_view_data("REPOTED_ACC_STAT_CSET", $REPOTED_ACC_STAT_CSET);
            $this->beanUi->set_view_data("REPOTED_ACC_STAT_PCSET", $REPOTED_ACC_STAT_PCSET);
            $this->beanUi->set_view_data("REPOTED_ACC_STAT_OTHERS", $REPOTED_ACC_STAT_OTHERS);
        }
    }

//    MIS-11
    public function surakshaBartaIncIdentStatics() {

        $action = $this->bean->get_request("_action");
        if ($action == "submitData") {
            $SetDMY = array();
            $month_year = $this->bean->get_request("month_year_from");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $incident_category_id = $this->reportdao->get_incident_category();
            $REPOTED_ACC_STAT_PSET = array();
            $REPOTED_ACC_STAT_CSET = array();

            $scoreID = $this->reportdao->getReportSettings(6, "id");
            $rowArray = explode(",", $scoreID[0]->param_value);
            foreach ($rowArray as $k => $rw) {
                $clause = " id='" . $rw . "'";
                $val1 = $this->reportdao->get_division_department($clause);
                $REPOTED_ACC_STAT_PSET[$k]["name"] = $val1[0]->name;
                $REPOTED_ACC_STAT_PSET[$k]["district_id"] = $val1[0]->id;
                $REPOTED_ACC_STAT_CSET[$k]["name"] = $val1[0]->name;
                $REPOTED_ACC_STAT_CSET[$k]["district_id"] = $val1[0]->id;
                $REPOTED_ACC_STAT_PCSET[$k]["name"] = $val1[0]->name;
                $REPOTED_ACC_STAT_PCSET[$k]["district_id"] = $val1[0]->id;
                $REPOTED_ACC_STAT_OTHERS[$k]["name"] = $val1[0]->name;
                $REPOTED_ACC_STAT_OTHERS[$k]["district_id"] = $val1[0]->id;
                //------P-SET-----//
                foreach ($incident_category_id as $key => $rowdata) {
                    if ($rowdata->id != 1) {
                        $REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["NAME"] = $rowdata->name;
                        $REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["ID"] = $rowdata->id;
                        $REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["PREV_YEAR1"] = $this->dao->getReportedAccidentStatistics("P-SET", $date_arr['PFY_QRY'], $rw, $rowdata->id);
                        $REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["CURRENT_YEAR"] = $this->dao->getReportedAccidentStatistics("P-SET", $date_arr['CFY_YTM_QRY'], $rw, $rowdata->id);
                    }
                }
                //------END P-SET-----//
                //------C-SET-----//
                foreach ($incident_category_id as $key => $rowdata) {
                    if ($rowdata->id != 1) {
                        $REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["NAME"] = $rowdata->name;
                        $REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["ID"] = $rowdata->id;
                        $REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["PREV_YEAR1"] = $this->dao->getReportedAccidentStatistics("C-SET", $date_arr['PFY_QRY'], $rw, $rowdata->id);
                        $REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["CURRENT_YEAR"] = $this->dao->getReportedAccidentStatistics("C-SET", $date_arr['CFY_YTM_QRY'], $rw, $rowdata->id);
                    }
                }
                //------END C-SET-----// 
                //------C-SET-----//
                //            foreach($incident_category_id as $key => $rowdata) {
                //               if( $rowdata->id != 1 ) {
                //                $REPOTED_ACC_STAT_PCSET[$k]["INCCAT"][$key]["NAME"] = $rowdata->name;
                //                $REPOTED_ACC_STAT_PCSET[$k]["INCCAT"][$key]["PREV_YEAR1"] = $this->dao->getReportedAccidentStatistics("PC-SET",$date_arr['PFY_QRY'],$rw,$rowdata->id);
                //                $REPOTED_ACC_STAT_PCSET[$k]["INCCAT"][$key]["CURRENT_YEAR"] = $this->dao->getReportedAccidentStatistics("PC-SET",$date_arr['CFY_QRY'],$rw,$rowdata->id);
                //               }
                //            }
                //          //------END C-SET-----// 
                //           //------C-SET-----//
                //            foreach($incident_category_id as $key => $rowdata) {
                //               if( $rowdata->id != 1 ) {
                //                $REPOTED_ACC_STAT_OTHERS[$k]["INCCAT"][$key]["NAME"] = $rowdata->name;
                //                $REPOTED_ACC_STAT_OTHERS[$k]["INCCAT"][$key]["PREV_YEAR1"] = $this->dao->getReportedAccidentStatistics("OTHERS",$date_arr['PFY_QRY'],$rw,$rowdata->id);
                //                $REPOTED_ACC_STAT_OTHERS[$k]["INCCAT"][$key]["CURRENT_YEAR"] = $this->dao->getReportedAccidentStatistics("OTHERS",$date_arr['CFY_QRY'],$rw,$rowdata->id);
                //               }
                //            }
                //------END C-SET-----// 
            }
            $SetDMY["month_year"] = $month_year;
            $SetDMY["PREV_Y1"] = $date_arr['PFY_QRY'];
            $SetDMY["CURR_Y"] = $date_arr['CFY_QRY'];
            $this->beanUi->set_view_data("getDate", $SetDMY);
            $this->beanUi->set_view_data("REPOTED_ACC_STAT_PSET", $REPOTED_ACC_STAT_PSET);
            $this->beanUi->set_view_data("REPOTED_ACC_STAT_CSET", $REPOTED_ACC_STAT_CSET);
            $this->beanUi->set_view_data("REPOTED_ACC_STAT_PCSET", $REPOTED_ACC_STAT_PCSET);
            $this->beanUi->set_view_data("REPOTED_ACC_STAT_OTHERS", $REPOTED_ACC_STAT_OTHERS);
        }

        // die;
    }

    public function suraksahBartaScoresStat() {
        $action = $this->bean->get_request("_action");
        if ($action == "submitData") {


            $SetDMY = array();
            $month_year = $this->bean->get_request("month_year_from");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $PPEscoreID = $this->reportdao->getReportSettings(7, "id");
            $rowArray = explode(",", $PPEscoreID[0]->param_value);
            $SITEscoreID = $this->reportdao->getReportSettings(8, "id");
            $rowArray1 = explode(",", $SITEscoreID[0]->param_value);
            $getPPEAuditData = array();
            $getSITEAuditData = array();
            foreach ($rowArray as $k => $id) {
                $clause = " id='" . $id . "'";
                //-------------------------------All District----------------------------------------//
                $getPPEAuditData['allDistrict'][] = $this->reportdao->get_division_department($clause);
                //-------------------------------All UNIT COUNT----------------------------------------//
                @$getPPEAuditData['P_SET_UNIT'][] = $this->reportdao->division_child_unit_count($id, 'P-SET');
                @$getPPEAuditData['C_SET_UNIT'][] = $this->reportdao->division_child_unit_count($id, 'C-SET');
//            @$getPPEAuditData['PC_SET_UNIT'][]      =   $this->reportdao->division_child_unit_count($id, 'PC-SET');
//            @$getPPEAuditData['OTHERS_SET_UNIT'][]  =   $this->reportdao->division_child_unit_count($id, 'OTHERS');
                //--------------------------------Previous Year Data----------------------------------//
                $getPPEAuditData['P_SET_PREV'][] = $this->dao->getPPEAuditData('P-SET', $date_arr['PFY_QRY'], $id);
                $getPPEAuditData['C_SET_PREV'][] = $this->dao->getPPEAuditData('C-SET', $date_arr['PFY_QRY'], $id);
//            $getPPEAuditData['PC_SET_PREV'][]       =   $this->dao->getPPEAuditData('PC-SET',$date_arr['PFY_QRY'],$id);
//            $getPPEAuditData['OTHERS_SET_PREV'][]   =   $this->dao->getPPEAuditData('OTHERS',$date_arr['PFY_QRY'],$id);
                //--------------------------------Current selected Month Data------------------------//
                $getPPEAuditData['P_SET_CURRENT'][] = $this->dao->getPPEAuditData('P-SET', $date_arr['CFY_YTM_QRY'], $id);
                $getPPEAuditData['C_SET_CURRENT'][] = $this->dao->getPPEAuditData('C-SET', $date_arr['CFY_YTM_QRY'], $id);
//            $getPPEAuditData['PC_SET_CURRENT'][]    =   $this->dao->getPPEAuditData('PC-SET',$date_arr['CFY_YTM_QRY'],$id);
//            $getPPEAuditData['OTHERS_SET_CURRENT'][]=   $this->dao->getPPEAuditData('OTHERS',$date_arr['CFY_YTM_QRY'],$id);
                //--------------------------------Current Finalcial Year Data------------------------//
            }
            foreach ($rowArray1 as $k => $rp) {
                $clause = " id='" . $rp . "'";
                //-------------------------------All District----------------------------------------//                
                @$getSITEAuditData['allDistrict'][] = $this->reportdao->get_division_department($clause);
                //-------------------------------All UNIT COUNT----------------------------------------//
                @$getSITEAuditData['P_SET_UNIT'][] = $this->reportdao->division_child_unit_count($rp, 'P-SET');
                @$getSITEAuditData['C_SET_UNIT'][] = $this->reportdao->division_child_unit_count($rp, 'C-SET');
                //--------------------------------Previous Year Data----------------------------------//
                @$getSITEAuditData['P_SET_PREV'][] = $this->dao->getSITEAuditData('P-SET', $date_arr['PFY_QRY'], $rp);
                @$getSITEAuditData['C_SET_PREV'][] = $this->dao->getSITEAuditData('C-SET', $date_arr['PFY_QRY'], $rp);
                //--------------------------------Current selected Month Data------------------------//
                @$getSITEAuditData['P_SET_CURRENT'][] = $this->dao->getSITEAuditData('P-SET', $date_arr['CFY_YTM_QRY'], $rp);
                @$getSITEAuditData['C_SET_CURRENT'][] = $this->dao->getSITEAuditData('C-SET', $date_arr['CFY_YTM_QRY'], $rp);
                //--------------------------------Current Finalcial Year Data------------------------//
            }
            //die;
            $SetDMY["PREV_Y"] = $date_arr['PFY_QRY'];
            $SetDMY["CURR_Y"] = $date_arr['CFY_QRY'];
            $this->beanUi->set_view_data("getDate", $SetDMY);
            $this->beanUi->set_view_data("getPPEAuditData", $getPPEAuditData);
            $this->beanUi->set_view_data("getSITEAuditData", $getSITEAuditData);
        }
    }

    /* create by anima */

    public function score_card_details_for_mainstream_contractor() {
        $action = $this->bean->get_request("_action");
        if ($action == "get_report") {
            $get_view_date = array();
            $month_year = $this->bean->get_request("month_year");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $contractorlist = $this->dao->get_contractor_list();
            $month_yearexp = explode("-", $month_year);
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $getcurrytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getprevytmrange = getAllYTMYear($previous_financial_year, $month_yearexp[0]);

            $endcurrent = date("Y-m-t", strtotime(end($getcurrytmrange)));
            $endprev = date("Y-m-t", strtotime(end($getprevytmrange)));
            $currentytm = "'$getcurrytmrange[0]-01' AND '" . $endcurrent . "'";
            $prevytm = "'$getprevytmrange[0]-01' AND '" . $endprev . "'";
            $get_all_contractor_data = array();
            $contractor_id = array();
            if(is_array($contractorlist) && count($contractorlist)){
                foreach ($contractorlist as $key => $rowdata) {
                    $contractor_id[$rowdata->code]['id'] = $this->dao->get_contractor_list($rowdata->code, "id");
                    $contractor_id[$rowdata->code]['identification_code'] = $this->dao->get_contractor_list($rowdata->code, "identification_code");
                    $get_all_contractor_data[$rowdata->code]["VENDOR_NAME"] = $rowdata->name;
                    @$get_all_contractor_data[$rowdata->code]["PREV_YEAR"] = $this->dao->getAllContractorbyCode($contractor_id[$rowdata->code]['id'], $contractor_id[$rowdata->code]['identification_code'], $date_arr['PFY_QRY']);
                    @$get_all_contractor_data[$rowdata->code]["CUR_YEAR_PREV_MONTH_YTM"] = $this->dao->getAllContractorbyCode($contractor_id[$rowdata->code]['id'], $contractor_id[$rowdata->code]['identification_code'], $date_arr['CFY_YTM_MBACK']);
                    @$get_all_contractor_data[$rowdata->code]["CURRENT_MONTH"] = $this->dao->getAllContractorbyCode($contractor_id[$rowdata->code]['id'], $contractor_id[$rowdata->code]['identification_code'], $date_arr['CM_QRY']);
                    @$get_all_contractor_data[$rowdata->code]["CURR_YEAR_CURR_MNTH_YTM"] = $this->dao->getAllContractorbyCode($contractor_id[$rowdata->code]['id'], $contractor_id[$rowdata->code]['identification_code'], $currentytm);
                }
            }
            if(is_array($get_all_contractor_data) && count($get_all_contractor_data)){
                foreach ($get_all_contractor_data as $key => $value) {
                    if ($value["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"] != "" || !empty($value["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"])) {
                        $newarr[$key] = $value["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"];
                    } else {
                        $newarr[$key] = 0;
                    }
                }
            }
            if(is_array($newarr) && count($newarr)){
                arsort($newarr);
            }
            $all_contractor_data = array();
            if(is_array($newarr) && count($newarr)){
                foreach (array_keys($newarr) as $key) {
                    $all_contractor_data[$key] = $get_all_contractor_data[$key];
                }
            }
            $get_view_date["PREV_YEAR"] = $date_arr['PFY_QRY'];
            $get_view_date["CUR_YEAR_YTM"] = $date_arr['PFY_YTM_QRY'];
            $get_view_date["CURRENT_MONTH"] = $date_arr['CM_QRY'];
            $get_view_date["CURR_YEAR"] = $date_arr['CFY_QRY'];
            $get_view_date["month_year"] = $month_year;
        }
        if (!empty($get_view_date))
            $this->beanUi->set_view_data("get_view_date", $get_view_date);
        if (!empty($get_all_contractor_data))
            $this->beanUi->set_view_data("get_all_contractor_data", $all_contractor_data);
    }

//    Report-12
    public function bbgs_contractors_scores_sorted() {
        $action = $this->bean->get_request("_action");
        if ($action == "get_report") {
            $get_view_date = array();
            $month_year = $this->bean->get_request("month_year");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $contractorlist = $this->dao->get_contractor_list_Gen_Bbgs();


            $month_yearexp = explode("-", $month_year);
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $getcurrytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getprevytmrange = getAllYTMYear($previous_financial_year, $month_yearexp[0]);

            $endcurrent = date("Y-m-t", strtotime(end($getcurrytmrange)));
            $endprev = date("Y-m-t", strtotime(end($getprevytmrange)));
            $currentytm = "'$getcurrytmrange[0]-01' AND '" . $endcurrent . "'";
            $prevytm = "'$getprevytmrange[0]-01' AND '" . $endprev . "'";
            $get_all_contractor_data = array();
            $contractor_id = array();
            foreach ($contractorlist as $key => $rowdata) {
                @$contractor_id[$rowdata->identification_code]['id'] = $this->dao->get_contractor_list_Gen_Bbgs($rowdata->identification_code, "id");
                @$contractor_id[$rowdata->identification_code]['identification_code'] = $this->dao->get_contractor_list_Gen_Bbgs($rowdata->identification_code, "identification_code"); //showPre($contractor_id[$rowdata->identification_code]['id']);
                @$get_all_contractor_data[$rowdata->identification_code]["VENDOR_NAME"] = $rowdata->name;
                @$get_all_contractor_data[$rowdata->identification_code]["PREV_YEAR"] = $this->dao->getAllContractorbyCode_Gen_Bbgs($contractor_id[$rowdata->identification_code]['id'], $contractor_id[$rowdata->identification_code]['identification_code'], $date_arr['PFY_QRY']);
                @$get_all_contractor_data[$rowdata->identification_code]["CUR_YEAR_PREV_MONTH_YTM"] = $this->dao->getAllContractorbyCode_Gen_Bbgs($contractor_id[$rowdata->identification_code]['id'], $contractor_id[$rowdata->identification_code]['identification_code'], $date_arr['CFY_YTM_MBACK']);
                @$get_all_contractor_data[$rowdata->identification_code]["CURRENT_MONTH"] = $this->dao->getAllContractorbyCode_Gen_Bbgs($contractor_id[$rowdata->identification_code]['id'], $contractor_id[$rowdata->identification_code]['identification_code'], $date_arr['CM_QRY']);
                @$get_all_contractor_data[$rowdata->identification_code]["CURR_YEAR_CURR_MNTH_YTM"] = $this->dao->getAllContractorbyCode_Gen_Bbgs($contractor_id[$rowdata->identification_code]['id'], $contractor_id[$rowdata->identification_code]['identification_code'], $currentytm);
            }

            foreach ($get_all_contractor_data as $key => $value) {
                if ($value["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"] != "" || !empty($value["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"])) {
                    $newarr[$key] = $value["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"];
                } else {
                    $newarr[$key] = 0;
                }
            }
            if(is_array($newarr) && count($newarr)){
                arsort($newarr);
            }
            $all_contractor_data = array();
            if(is_array($newarr) && count($newarr)){
                foreach (array_keys($newarr) as $key) {
                    $all_contractor_data[$key] = $get_all_contractor_data[$key];
                }
            }

            $get_view_date["PREV_YEAR"] = $date_arr['PFY_QRY'];
            $get_view_date["CUR_YEAR_YTM"] = $date_arr['PFY_YTM_QRY'];
            $get_view_date["CURRENT_MONTH"] = $date_arr['CM_QRY'];
            $get_view_date["CURR_YEAR"] = $date_arr['CFY_QRY'];
            $get_view_date["month_year"] = $month_year;
        }

        if (!empty($get_view_date))
            $this->beanUi->set_view_data("get_view_date", $get_view_date);
        if (!empty($get_all_contractor_data))
            $this->beanUi->set_view_data("get_all_contractor_data", $all_contractor_data);
    }

//    Report-13
    public function sgs_contractors_scores_sorted() {

        $action = $this->bean->get_request("_action");
        if ($action == "get_report") {

            $get_view_date = array();
            $month_year = $this->bean->get_request("month_year");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $contractorlist = $this->dao->get_contractor_list_Gen_Sgs();


            $month_yearexp = explode("-", $month_year);
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $getcurrytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getprevytmrange = getAllYTMYear($previous_financial_year, $month_yearexp[0]);

            $endcurrent = date("Y-m-t", strtotime(end($getcurrytmrange)));
            $endprev = date("Y-m-t", strtotime(end($getprevytmrange)));
            $currentytm = "'$getcurrytmrange[0]-01' AND '" . $endcurrent . "'";
            $prevytm = "'$getprevytmrange[0]-01' AND '" . $endprev . "'";
            $get_all_contractor_data = array();
            $contractor_id = array();
            foreach ($contractorlist as $key => $rowdata) {
                @$contractor_id[$rowdata->identification_code]['id'] = $this->dao->get_contractor_list_Gen_Sgs($rowdata->identification_code, "id");
                @$contractor_id[$rowdata->identification_code]['identification_code'] = $this->dao->get_contractor_list_Gen_Sgs($rowdata->identification_code, "identification_code"); //showPre($contractor_id[$rowdata->identification_code]['id']);
                @$get_all_contractor_data[$rowdata->identification_code]["VENDOR_NAME"] = $rowdata->name;
                @$get_all_contractor_data[$rowdata->identification_code]["PREV_YEAR"] = $this->dao->getAllContractorbyCode_Gen_Sgs($contractor_id[$rowdata->identification_code]['id'], $contractor_id[$rowdata->identification_code]['identification_code'], $date_arr['PFY_QRY']);
                @$get_all_contractor_data[$rowdata->identification_code]["CUR_YEAR_PREV_MONTH_YTM"] = $this->dao->getAllContractorbyCode_Gen_Sgs($contractor_id[$rowdata->identification_code]['id'], $contractor_id[$rowdata->identification_code]['identification_code'], $date_arr['CFY_YTM_MBACK']);
                @$get_all_contractor_data[$rowdata->identification_code]["CURRENT_MONTH"] = $this->dao->getAllContractorbyCode_Gen_Sgs($contractor_id[$rowdata->identification_code]['id'], $contractor_id[$rowdata->identification_code]['identification_code'], $date_arr['CM_QRY']);
                @$get_all_contractor_data[$rowdata->identification_code]["CURR_YEAR_CURR_MNTH_YTM"] = $this->dao->getAllContractorbyCode_Gen_Sgs($contractor_id[$rowdata->identification_code]['id'], $contractor_id[$rowdata->identification_code]['identification_code'], $currentytm);
            }

            foreach ($get_all_contractor_data as $key => $value) {
                if ($value["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"] != "" || !empty($value["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"])) {
                    $newarr[$key] = $value["CURR_YEAR_CURR_MNTH_YTM"]["SCORE"];
                } else {
                    $newarr[$key] = 0;
                }
            }
            if(is_array($newarr) && count($newarr)){
                arsort($newarr);
            }
            $all_contractor_data = array();
            if(is_array($newarr) && count($newarr)){
                foreach (array_keys($newarr) as $key) {
                    $all_contractor_data[$key] = $get_all_contractor_data[$key];
                }
            }

            $get_view_date["PREV_YEAR"] = $date_arr['PFY_QRY'];
            $get_view_date["CUR_YEAR_YTM"] = $date_arr['PFY_YTM_QRY'];
            $get_view_date["CURRENT_MONTH"] = $date_arr['CM_QRY'];
            $get_view_date["CURR_YEAR"] = $date_arr['CFY_QRY'];
            $get_view_date["month_year"] = $month_year;
        }

        if (!empty($get_view_date))
            $this->beanUi->set_view_data("get_view_date", $get_view_date);
        if (!empty($get_all_contractor_data))
            $this->beanUi->set_view_data("get_all_contractor_data", $all_contractor_data);
    }

    public function ytmactivityfigure() {
        $arr_set_date = array();
        $_action = $this->bean->get_request("_action");
        $LFY = $current_financial_year = "";
        $arrData = array();
        if ($_action == "Create") {
            $month_year = $this->bean->get_request("month_year");
            $month_yearexp = explode("-", $month_year);
            $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
            ///////TYPE 1 ///////////
            $getmajorActivities = $this->dao->getMajorActivity(1);

            $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");

            $lastfinancialyear_dt = getPrevFinancialYear(($month_yearexp[1] - 1) . "-" . $month_yearexp[0], "Y");

            $dt_arr = explode(",", $lastfinancialyear);
            $dt_arr1 = explode("-", $dt_arr[0]);
            $dt_arr2 = explode("-", $dt_arr[1]);
            $LFY = $dt_arr1[0] . "-" . $dt_arr2[0];


            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $prevfyrange = getAllMonthYear($previous_financial_year);
            $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
            $getytmrange1 = getAllYTMYear($previous_financial_year, $month_yearexp[0]);
            $prevfystartytm = $getytmrange1[0];
            $prevfyendytm = end($getytmrange1);

            $officerTrainingGEN2 = $this->dao->getOfficerTraining(2, 5, $month_year_formatted, $financialyearstart, 'G');
            $officerTrainingDIST2 = $this->dao->getOfficerTraining(2, 5, $month_year_formatted, $financialyearstart, 'D');
            $officerTrainingGEN3 = $this->dao->getOfficerTraining(3, 5, $lastfinancialyear, '', 'G');
            $officerTrainingDIST3 = $this->dao->getOfficerTraining(3, 5, $lastfinancialyear, '', 'D');

            $supervisorTrainingGEN2 = $this->dao->getSupervisorTraining(2, '3,4', $month_year_formatted, $financialyearstart, 'Supervisor', 'G');
            $supervisorTrainingDIST2 = $this->dao->getSupervisorTraining(2, '3,4', $month_year_formatted, $financialyearstart, 'Supervisor', 'D');
            $supervisorTrainingGEN3 = $this->dao->getSupervisorTraining(3, '3,4', $lastfinancialyear, '', 'Supervisor', 'G');
            $supervisorTrainingDIST3 = $this->dao->getSupervisorTraining(3, '3,4', $lastfinancialyear, '', 'Supervisor', 'D');

            $workmanTrainingGEN2 = $this->dao->getSupervisorTraining(2, '3,4', $month_year_formatted, $financialyearstart, 'Workman', 'G');
            $workmanTrainingDIST2 = $this->dao->getSupervisorTraining(2, '3,4', $month_year_formatted, $financialyearstart, 'Workman', 'D');
            $workmanTrainingGEN3 = $this->dao->getSupervisorTraining(3, '3,4', $lastfinancialyear, '', 'Workman', 'G');
            $workmanTrainingDIST3 = $this->dao->getSupervisorTraining(3, '3,4', $lastfinancialyear, '', 'Workman', 'D');

            $contractualTrainingGEN2 = $this->dao->getOfficerTraining(2, 4, $month_year_formatted, $financialyearstart, 'G');
            $contractualTrainingDIST2 = $this->dao->getOfficerTraining(2, 4, $month_year_formatted, $financialyearstart, 'D');
            $contractualTrainingGEN3 = $this->dao->getOfficerTraining(3, 4, $lastfinancialyear, '', 'G');
            $contractualTrainingDIST3 = $this->dao->getOfficerTraining(3, 4, $lastfinancialyear, '', 'D');


            $unitsafeydaysGEN2 = $this->dao->getPostCount(2, 4, "activity_date", "activity_view", $month_year_formatted, $financialyearstart, 'G');
            $unitsafeydaysDIST2 = $this->dao->getPostCount(2, 4, "activity_date", "activity_view", $month_year_formatted, $financialyearstart, 'D');
            $unitsafeydaysGEN3 = $this->dao->getPostCount(3, 4, "activity_date", "activity_view", $lastfinancialyear, '', 'G');
            $unitsafeydaysDIST3 = $this->dao->getPostCount(3, 4, "activity_date", "activity_view", $lastfinancialyear, '', 'D');

            $safetyworkshopGEN2 = $this->dao->getPostCount(2, 1, "activity_date", "activity_view", $month_year_formatted, $financialyearstart, 'G');
            $safetyworkshopDIST2 = $this->dao->getPostCount(2, 1, "activity_date", "activity_view", $month_year_formatted, $financialyearstart, 'D');
            $safetyworkshopGEN3 = $this->dao->getPostCount(3, 1, "activity_date", "activity_view", $lastfinancialyear, '', 'G');
            $safetyworkshopDIST3 = $this->dao->getPostCount(3, 1, "activity_date", "activity_view", $lastfinancialyear, '', 'D');

            $commMeetingGEN2 = $this->dao->getPostCount(2, 2, "activity_date", "activity_view", $month_year_formatted, $financialyearstart, 'G');
            $commMeetingDIST2 = $this->dao->getPostCount(2, 2, "activity_date", "activity_view", $month_year_formatted, $financialyearstart, 'D');
            $commMeetingGEN3 = $this->dao->getPostCount(3, 2, "activity_date", "activity_view", $lastfinancialyear, '', 'G');
            $commMeetingDIST3 = $this->dao->getPostCount(3, 2, "activity_date", "activity_view", $lastfinancialyear, '', 'D');
            
            $distsiteaudit2 = $this->dao->getSiteAuditCount(2, 5, 'D', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $distsiteaudit3 = $this->dao->getSiteAuditCount(3, 5, 'D', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);
            
            $gensiteaudit2 = $this->dao->getSiteAuditCount(2, 5, 'G', "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart);
            $gensiteaudit3 = $this->dao->getSiteAuditCount(3, 5, 'G', "date_of_audit", "actualtarget_audit_view", $lastfinancialyear);
            
            $ppeauditGEN2 = $this->dao->getPostCount(2, 7, "date_of_audit", "ppe_audit_view", $month_year_formatted, $financialyearstart, 'G');
            $ppeauditDIST2 = $this->dao->getPostCount(2, 7, "date_of_audit", "ppe_audit_view", $month_year_formatted, $financialyearstart, 'D');
            $ppeauditGEN3 = $this->dao->getPostCount(3, 7, "date_of_audit", "ppe_audit_view", $lastfinancialyear, '', 'G');
            $ppeauditDIST3 = $this->dao->getPostCount(3, 7, "date_of_audit", "ppe_audit_view", $lastfinancialyear, '', 'D');

            $safobsGEN2 = $this->dao->get_number_safety_observation(1, $getytmrange, 'G');
            $safobsDIST2 = $this->dao->get_number_safety_observation(1, $getytmrange, 'D');
            $safobsGEN3 = $this->dao->get_number_safety_observation(1, $prevfyrange, 'G');
            $safobsDIST3 = $this->dao->get_number_safety_observation(1, $prevfyrange, 'D');


            $arr = array(
                1 => array(
                    "ytm_total_GEN" => $officerTrainingGEN2,
                    "ytm_total_DIST" => $officerTrainingDIST2,
                    "last_year_total_GEN" => $officerTrainingGEN3,
                    "last_year_total_DIST" => $officerTrainingDIST3,
                    "target" => "0"),
                2 => array(
                    "ytm_total_GEN" => $supervisorTrainingGEN2,
                    "ytm_total_DIST" => $supervisorTrainingDIST2,
                    "last_year_total_GEN" => $supervisorTrainingGEN3,
                    "last_year_total_DIST" => $supervisorTrainingDIST3,
                    "target" => "0"),
                3 => array(
                    "ytm_total_GEN" => $workmanTrainingGEN2,
                    "ytm_total_DIST" => $workmanTrainingDIST2,
                    "last_year_total_GEN" => $workmanTrainingGEN3,
                    "last_year_total_DIST" => $workmanTrainingDIST3,
                    "target" => "0"),
                4 => array(
                    "ytm_total_GEN" => $contractualTrainingGEN2,
                    "ytm_total_DIST" => $contractualTrainingDIST2,
                    "last_year_total_GEN" => $contractualTrainingGEN3,
                    "last_year_total_DIST" => $contractualTrainingDIST3,
                    "target" => "0"),
                5 => array(
                    "ytm_total_GEN" => $unitsafeydaysGEN2,
                    "ytm_total_DIST" => $unitsafeydaysDIST2,
                    "last_year_total_GEN" => $unitsafeydaysGEN3,
                    "last_year_total_DIST" => $unitsafeydaysDIST3,
                    "target" => "0"),
                6 => array(
                    "ytm_total_GEN" => $safetyworkshopGEN2,
                    "ytm_total_DIST" => $safetyworkshopDIST2,
                    "last_year_total_GEN" => $safetyworkshopGEN3,
                    "last_year_total_DIST" => $safetyworkshopDIST3,
                    "target" => "0"),
                7 => array(
                    "ytm_total_GEN" => $commMeetingGEN2,
                    "ytm_total_DIST" => $commMeetingDIST2,
                    "last_year_total_GEN" => $commMeetingGEN3,
                    "last_year_total_DIST" => $commMeetingDIST3,
                    "target" => "0"),
                8 => array(/* data required only for distribution */
                    "ytm_total_GEN" => 0,
                    "ytm_total_DIST" => $distsiteaudit2,
                    "last_year_total_GEN" => 0,
                    "last_year_total_DIST" => $distsiteaudit3,
                    "target" => "0"),
                9 => array(/* data required only for generation */
                    "ytm_total_GEN" => $gensiteaudit2,
                    "ytm_total_DIST" => 0,
                    "last_year_total_GEN" => $gensiteaudit3,
                    "last_year_total_DIST" => 0,
                    "target" => "0"),
                10 => array(
                    "ytm_total_GEN" => $ppeauditGEN2,
                    "ytm_total_DIST" => $ppeauditDIST2,
                    "last_year_total_GEN" => $ppeauditGEN3,
                    "last_year_total_DIST" => $ppeauditDIST3,
                    "target" => "0"),
                11 => array(
                    "ytm_total_GEN" => $safobsGEN2,
                    "ytm_total_DIST" => $safobsDIST2,
                    "last_year_total_GEN" => $safobsGEN3,
                    "last_year_total_DIST" => $safobsDIST3,
                    "target" => "0"),
            );

            foreach ($getmajorActivities as $key => $rowdata) {
                $targetvalue = $this->dao->get_mis_target($rowdata->id, $current_financial_year, 3);

                @$arrData[$key]->id = $rowdata->id;
                $arrData[$key]->name = $rowdata->name;
                $arrData[$key]->type = $rowdata->type;
                $arrData[$key]->ytm_total_GEN = $arr[$rowdata->id]["ytm_total_GEN"];
                $arrData[$key]->ytm_total_DIST = $arr[$rowdata->id]["ytm_total_DIST"];
                $arrData[$key]->last_year_total_GEN = $arr[$rowdata->id]["last_year_total_GEN"];
                $arrData[$key]->last_year_total_DIST = $arr[$rowdata->id]["last_year_total_DIST"];
                $arrData[$key]->gen = $targetvalue["gen_target"];
                $arrData[$key]->dist = $targetvalue["dist_target"];
                $arrData[$key]->target = $targetvalue["target"];
            }

        }
        if (!empty($arrData))
            $arr_set_date["month_year"] = $month_year;
        $arr_set_date["CFY"] = $current_financial_year;
        $arr_set_date["PFY"] = $LFY;

        $this->beanUi->set_view_data("majorActivities", $arrData);
        $this->beanUi->set_view_data("arr_set_date", $arr_set_date);
    }

    public function dist_data_sheet_for_momthly_report() {

        $_action = $this->bean->get_request("_action");
        $arr_date_send = array();
        $date_arr = array();
        if ($_action == "Create") {
            $month_year = $this->bean->get_request("month_year");
            $month_yearexp = explode("-", $month_year);
            $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
            ///////TYPE 1 ///////////
            $getMajorItem = $this->dao->getMajorItem(9);
            $arrData = array();
            $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastFinancialMonth = date('Y-m', strtotime('-1 years', strtotime($month_year_formatted)));
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $prev_financial_year = $date_arr["PFY_FORMATTED"];
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $getprevytmrange = getAllYTMYear($previous_financial_year, $month_yearexp[0]);
            $endprev = date("Y-m-t", strtotime(end($getprevytmrange)));

            $prevytm = "'$getprevytmrange[0]-01' AND '" . $endprev . "'";
            //For SL-1          
            $ppeAuditConductedAtCSetGearMonth = $this->dao->getPPECount(1, 7, "date_of_audit", "actualtarget_ppeaudit_view", $month_year_formatted, $financialyearstart, 'C', 'D');
            $ppeAuditConductedAtCSetGearYear = $this->dao->getPPECount(2, 7, "date_of_audit", "actualtarget_ppeaudit_view", $month_year_formatted, $financialyearstart, 'C', 'D');
            $ppeAuditConductedAtCSetGearMonthLastFY = $this->dao->getPPECount(4, 7, "date_of_audit", "actualtarget_ppeaudit_view", $lastFinancialMonth, '', 'C', 'D');
            $ppeAuditConductedAtCSetGearYearLastFY = $this->dao->getPPECount(3, 7, "date_of_audit", "actualtarget_ppeaudit_view", $lastfinancialyear, '', 'C', 'D');
            //For SL-2        
            $ppeAuditConductedAtPSetGearMonth = $this->dao->getPPECount(1, 7, "date_of_audit", "actualtarget_ppeaudit_view", $month_year_formatted, $financialyearstart, 'P', 'D');
            $ppeAuditConductedAtPSetGearYear = $this->dao->getPPECount(2, 7, "date_of_audit", "actualtarget_ppeaudit_view", $month_year_formatted, $financialyearstart, 'P', 'D');
            $ppeAuditConductedAtPSetGearMonthLastFY = $this->dao->getPPECount(4, 7, "date_of_audit", "actualtarget_ppeaudit_view", $lastFinancialMonth, '', 'P', 'D');
            $ppeAuditConductedAtPSetGearYearLastFY = $this->dao->getPPECount(3, 7, "date_of_audit", "actualtarget_ppeaudit_view", $lastfinancialyear, '', 'P', 'D');
            //For SL-3  
            $trainingOfWorkmenCSetMonth = $this->dao->getTrainingCount(1, 3, "from_date", $month_year_formatted, $financialyearstart, 'C', 'D');
            $trainingOfWorkmenCSetYear = $this->dao->getTrainingCount(2, 3, "from_date", $month_year_formatted, $financialyearstart, 'C', 'D');
            $trainingOfWorkmenCSetMonthLastFY = $this->dao->getTrainingCount(4, 3, "from_date", $lastFinancialMonth, '', 'C', 'D');
            $trainingOfWorkmenCSetYearLastFY = $this->dao->getTrainingCount(3, 3, "from_date", $lastfinancialyear, '', 'C', 'D');
            //For SL-4
            $trainingOfWorkmenPSetMonth = $this->dao->getTrainingCount(1, 3, "from_date", $month_year_formatted, $financialyearstart, 'P', 'D');
            $trainingOfWorkmenPSetYear = $this->dao->getTrainingCount(2, 3, "from_date", $month_year_formatted, $financialyearstart, 'P', 'D');
            $trainingOfWorkmenPSetMonthLastFY = $this->dao->getTrainingCount(4, 3, "from_date", $lastFinancialMonth, '', 'P', 'D');
            $trainingOfWorkmenPSetYearLastFY = $this->dao->getTrainingCount(3, 3, "from_date", $lastfinancialyear, '', 'P', 'D');
            //For SL-5
            $trainingOfSupervisorPSetMonth = $this->dao->getTrainingCount(1, 3, "from_date", $month_year_formatted, $financialyearstart, 'PS', 'D');
            $trainingOfSupervisorPSetYear = $this->dao->getTrainingCount(2, 3, "from_date", $month_year_formatted, $financialyearstart, 'PS', 'D');
            $trainingOfSupervisorPSetMonthLastFY = $this->dao->getTrainingCount(4, 3, "from_date", $lastFinancialMonth, '', 'PS', 'D');
            $trainingOfSupervisorPSetYearLastFY = $this->dao->getTrainingCount(3, 3, "from_date", $lastfinancialyear, '', 'PS', 'D');
            //For SL-6
            $trainingOfSupervisorCSetMonth = $this->dao->getTrainingCount(1, 3, "from_date", $month_year_formatted, $financialyearstart, 'CS', 'D');
            $trainingOfSupervisorCSetYear = $this->dao->getTrainingCount(2, 3, "from_date", $month_year_formatted, $financialyearstart, 'CS', 'D');
            $trainingOfSupervisorCSetMonthLastFY = $this->dao->getTrainingCount(4, 3, "from_date", $lastFinancialMonth, '', 'CS', 'D');
            $trainingOfSupervisorCSetYearLastFY = $this->dao->getTrainingCount(3, 3, "from_date", $lastfinancialyear, '', 'CS', 'D');
            //For SL-7
            $trainingOfficerMonth = $this->dao->getTrainingCount(1, 3, "from_date", $month_year_formatted, $financialyearstart, 'O', 'D');
            $trainingOfficerYear = $this->dao->getTrainingCount(2, 3, "from_date", $month_year_formatted, $financialyearstart, 'O', 'D');
            $trainingOfficerMonthLastFY = $this->dao->getTrainingCount(4, 3, "from_date", $lastFinancialMonth, '', 'O', 'D');
            $trainingOfficerYearLastFY = $this->dao->getTrainingCount(3, 3, "from_date", $lastfinancialyear, '', 'O', 'D');
            //For SL-8
            $safetyAuditConductedMonth = $this->dao->getSafetyAuditCount(1, 5, "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart, 'D');
            $safetyAuditConductedYear = $this->dao->getSafetyAuditCount(2, 5, "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart, 'D');
            $safetyAuditConductedMonthLastFY = $this->dao->getSafetyAuditCount(4, 5, "date_of_audit", "actualtarget_audit_view", $lastFinancialMonth, '', 'D');
            $safetyAuditConductedYearLastFY = $this->dao->getSafetyAuditCount(3, 5, "date_of_audit", "actualtarget_audit_view", $lastfinancialyear, '', 'D');
            //For SL-9    
            $safetyAuditConductedCCMonth = $this->dao->getSafetyAuditCountforCC(1, 5, "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart, 'D');
            $safetyAuditConductedCCYear = $this->dao->getSafetyAuditCountforCC(2, 5, "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart, 'D');
            $safetyAuditConductedCCMonthLastFY = $this->dao->getSafetyAuditCountforCC(4, 5, "date_of_audit", "actualtarget_audit_view", $lastFinancialMonth, '', 'D');
            $safetyAuditConductedCCYearLastFY = $this->dao->getSafetyAuditCountforCC(3, 5, "date_of_audit", "actualtarget_audit_view", $lastfinancialyear, '', 'D');
            //For SL-10
            $communicationMeetingMonth = $this->dao->getCommMeetingCount(1, 2, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'D');
            $communicationMeetingYear = $this->dao->getCommMeetingCount(2, 2, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'D');
            $communicationMeetingMonthLastFY = $this->dao->getCommMeetingCount(4, 2, "activity_date", "actualtarget_view", $lastFinancialMonth, '', 'D');
            $communicationMeetingYearLastFY = $this->dao->getCommMeetingCount(3, 2, "activity_date", "actualtarget_view", $lastfinancialyear, '', 'D');
            //For SL-11
            $safetyWorkshopMonth = $this->dao->getWorkshopCount(1, 1, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'D');
            $safetyWorkshopYear = $this->dao->getWorkshopCount(2, 1, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'D');
            $safetyWorkshopMonthLastFY = $this->dao->getWorkshopCount(4, 1, "activity_date", "actualtarget_view", $lastFinancialMonth, '', 'D');
            $safetyWorkshopYearLastFY = $this->dao->getWorkshopCount(3, 1, "activity_date", "actualtarget_view", $lastfinancialyear, '', 'D');
            //For SL-12
            $safetyObservationDayMonth = $this->dao->getSafetyDayCount(1, 4, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'D');
            $safetyObservationDayYear = $this->dao->getSafetyDayCount(2, 4, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'D');
            $safetyObservationDayMonthLastFY = $this->dao->getSafetyDayCount(4, 4, "activity_date", "actualtarget_view", $lastFinancialMonth, '', 'D');
            $safetyObservationDayYearLastFY = $this->dao->getSafetyDayCount(3, 4, "activity_date", "actualtarget_view", $lastfinancialyear, '', 'D');
            //For SL-13
            $safetyObservationByLineFunctionMonth = $this->dao->getSafetyObservationByLineFunctionCount(1, $month_year_formatted, $financialyearstart, 'D');
            $safetyObservationByLineFunctionYear = $this->dao->getSafetyObservationByLineFunctionCount(2, $financialyearstart, $month_year_formatted, 'D');
            $safetyObservationByLineFunctionMonthLastFY = $this->dao->getSafetyObservationByLineFunctionCount(4, $lastFinancialMonth, '', 'D');
            $safetyObservationByLineFunctionYearLastFY = $this->dao->getSafetyObservationByLineFunctionCount(3, $lastfinancialyear, '', 'D');

            $arr = array(
                50 => array(
                    "annual_target" => "0",
                    "this_month" => $ppeAuditConductedAtCSetGearMonth,
                    "ytm_this_year" => $ppeAuditConductedAtCSetGearYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $ppeAuditConductedAtCSetGearMonthLastFY,
                    "ytm_this_year_lastFY" => $ppeAuditConductedAtCSetGearYearLastFY,
                    "completed_lastFY" => ""),
                51 => array(
                    "annual_target" => "0",
                    "this_month" => $ppeAuditConductedAtPSetGearMonth,
                    "ytm_this_year" => $ppeAuditConductedAtPSetGearYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $ppeAuditConductedAtPSetGearMonthLastFY,
                    "ytm_this_year_lastFY" => $ppeAuditConductedAtPSetGearYearLastFY,
                    "completed_lastFY" => ""),
                52 => array(
                    "annual_target" => "0",
                    "this_month" => $trainingOfWorkmenCSetMonth,
                    "ytm_this_year" => $trainingOfWorkmenCSetYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $trainingOfWorkmenCSetMonthLastFY,
                    "ytm_this_year_lastFY" => $trainingOfWorkmenCSetYearLastFY,
                    "completed_lastFY" => ""),
                53 => array(
                    "annual_target" => "0",
                    "this_month" => $trainingOfWorkmenPSetMonth,
                    "ytm_this_year" => $trainingOfWorkmenPSetYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $trainingOfWorkmenPSetMonthLastFY,
                    "ytm_this_year_lastFY" => $trainingOfWorkmenPSetYearLastFY,
                    "completed_lastFY" => ""),
                54 => array(
                    "annual_target" => "0",
                    "this_month" => $trainingOfSupervisorPSetMonth,
                    "ytm_this_year" => $trainingOfSupervisorPSetYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $trainingOfSupervisorPSetMonthLastFY,
                    "ytm_this_year_lastFY" => $trainingOfSupervisorPSetYearLastFY,
                    "completed_lastFY" => ""),
                55 => array(
                    "annual_target" => "0",
                    "this_month" => $trainingOfSupervisorCSetMonth,
                    "ytm_this_year" => $trainingOfSupervisorCSetYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $trainingOfSupervisorCSetMonthLastFY,
                    "ytm_this_year_lastFY" => $trainingOfSupervisorCSetYearLastFY,
                    "completed_lastFY" => ""),
                56 => array(
                    "annual_target" => "0",
                    "this_month" => $trainingOfficerMonth,
                    "ytm_this_year" => $trainingOfficerYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $trainingOfficerMonthLastFY,
                    "ytm_this_year_lastFY" => $trainingOfficerYearLastFY,
                    "completed_lastFY" => ""),
                57 => array(
                    "annual_target" => "0",
                    "this_month" => ($safetyAuditConductedMonth - $safetyAuditConductedCCMonth),
                    "ytm_this_year" => ($safetyAuditConductedYear - $safetyAuditConductedCCYear),
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => ($safetyAuditConductedMonthLastFY - $safetyAuditConductedCCMonthLastFY),
                    "ytm_this_year_lastFY" => ($safetyAuditConductedYearLastFY - $safetyAuditConductedCCYearLastFY),
                    "completed_lastFY" => ""),
                58 => array(
                    "annual_target" => "0",
                    "this_month" => $safetyAuditConductedCCMonth,
                    "ytm_this_year" => $safetyAuditConductedCCYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $safetyAuditConductedCCMonthLastFY,
                    "ytm_this_year_lastFY" => $safetyAuditConductedCCYearLastFY,
                    "completed_lastFY" => ""),
                59 => array(
                    "annual_target" => "0",
                    "this_month" => $communicationMeetingMonth,
                    "ytm_this_year" => $communicationMeetingYear,
                    "completed" => $communicationMeetingCompleted = "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $communicationMeetingMonthLastFY,
                    "ytm_this_year_lastFY" => $communicationMeetingYearLastFY,
                    "completed_lastFY" => ""),
                60 => array(
                    "annual_target" => "0",
                    "this_month" => $safetyWorkshopMonth,
                    "ytm_this_year" => $safetyWorkshopYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $safetyWorkshopMonthLastFY,
                    "ytm_this_year_lastFY" => $safetyWorkshopYearLastFY,
                    "completed_lastFY" => ""),
                61 => array(
                    "annual_target" => "0",
                    "this_month" => $safetyObservationDayMonth,
                    "ytm_this_year" => $safetyObservationDayYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $safetyObservationDayMonthLastFY,
                    "ytm_this_year_lastFY" => $safetyObservationDayYearLastFY,
                    "completed_lastFY" => ""),
                62 => array(
                    "annual_target" => "0",
                    "this_month" => $safetyObservationByLineFunctionMonth,
                    "ytm_this_year" => @$safetyObservationByLineFunctionYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => @$safetyObservationByLineFunctionMonthLastFY,
                    "ytm_this_year_lastFY" => @$safetyObservationByLineFunctionYearLastFY,
                    "completed_lastFY" => ""),
            );
            foreach ($getMajorItem as $key => $rowdata) {
                $targetvalue_curr = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                $targetvalue_prev = $this->dao->get_mis_target($rowdata->id, $prev_financial_year);
                @$arrData[$key]->id = $rowdata->id;
                @$arrData[$key]->name = $rowdata->name;
                @$arrData[$key]->type = $rowdata->type;
                @$arrData[$key]->annual_target = $targetvalue_curr["dist_target"];
                @$arrData[$key]->this_month = $arr[$rowdata->id]["this_month"];
                @$arrData[$key]->ytm_this_year = $arr[$rowdata->id]["ytm_this_year"];
                @$arrData[$key]->completed = $arr[$rowdata->id]["completed"];
                @$arrData[$key]->annual_target_lastFY = $targetvalue_prev["dist_target"];
                @$arrData[$key]->this_month_lastFY = $arr[$rowdata->id]["this_month_lastFY"];
                @$arrData[$key]->ytm_this_year_lastFY = $arr[$rowdata->id]["ytm_this_year_lastFY"];
                @$arrData[$key]->completed_lastFY = $arr[$rowdata->id]["completed_lastFY"];
            }

            $arr_date_send["curr_month"] = $month_year;
            $arr_date_send["curr_year"] = $current_financial_year;
            $arr_date_send["last_month"] = $lastFinancialMonth;
            $arr_date_send["last_year"] = $lastfinancialyear;
        }
        if (!empty($arrData))
            $this->beanUi->set_view_data("majorItem", $arrData);
        $this->beanUi->set_view_data("arr_date_send", $arr_date_send);
    }

    public function gen_data_sheet_for_momthly_report() {

        $_action = $this->bean->get_request("_action");
        $arr_date_send = array();
        $date_arr = array();
        if ($_action == "Create") {
            $month_year = $this->bean->get_request("month_year");
            $month_yearexp = explode("-", $month_year);
            $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
            ///////TYPE 1 ///////////
            $getMajorItem = $this->dao->getMajorItem(9);
            $arrData = array();
            $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastFinancialMonth = date('Y-m', strtotime('-1 years', strtotime($month_year_formatted)));
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $prev_financial_year = $date_arr["PFY_FORMATTED"];
            $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
            $getprevytmrange = getAllYTMYear($previous_financial_year, $month_yearexp[0]);
            $endprev = date("Y-m-t", strtotime(end($getprevytmrange)));
            $prevytm = "'$getprevytmrange[0]-01' AND '" . $endprev . "'";

            //For SL-1          
            $ppeAuditConductedAtCSetGearMonth = $this->dao->getPPECount(1, 7, "date_of_audit", "actualtarget_ppeaudit_view", $month_year_formatted, $financialyearstart, 'C', 'G');
            $ppeAuditConductedAtCSetGearYear = $this->dao->getPPECount(2, 7, "date_of_audit", "actualtarget_ppeaudit_view", $month_year_formatted, $financialyearstart, 'C', 'G');
            $ppeAuditConductedAtCSetGearMonthLastFY = $this->dao->getPPECount(4, 7, "date_of_audit", "actualtarget_ppeaudit_view", $lastFinancialMonth, '', 'C', 'G');
            $ppeAuditConductedAtCSetGearYearLastFY = $this->dao->getPPECount(3, 7, "date_of_audit", "actualtarget_ppeaudit_view", $lastfinancialyear, '', 'C', 'G');
            //For SL-2        
            $ppeAuditConductedAtPSetGearMonth = $this->dao->getPPECount(1, 7, "date_of_audit", "actualtarget_ppeaudit_view", $month_year_formatted, $financialyearstart, 'P', 'G');
            $ppeAuditConductedAtPSetGearYear = $this->dao->getPPECount(2, 7, "date_of_audit", "actualtarget_ppeaudit_view", $month_year_formatted, $financialyearstart, 'P', 'G');
            $ppeAuditConductedAtPSetGearMonthLastFY = $this->dao->getPPECount(4, 7, "date_of_audit", "actualtarget_ppeaudit_view", $lastFinancialMonth, '', 'P', 'G');
            $ppeAuditConductedAtPSetGearYearLastFY = $this->dao->getPPECount(3, 7, "date_of_audit", "actualtarget_ppeaudit_view", $lastfinancialyear, '', 'P', 'G');
            //For SL-3          
            $trainingOfWorkmenCSetMonth = $this->dao->getTrainingCount(1, 3, "from_date", $month_year_formatted, $financialyearstart, 'C', 'G');
            $trainingOfWorkmenCSetYear = $this->dao->getTrainingCount(2, 3, "from_date", $month_year_formatted, $financialyearstart, 'C', 'G');
            $trainingOfWorkmenCSetMonthLastFY = $this->dao->getTrainingCount(4, 3, "from_date", $lastFinancialMonth, '', 'C', 'G');
            $trainingOfWorkmenCSetYearLastFY = $this->dao->getTrainingCount(3, 3, "from_date", $lastfinancialyear, '', 'C', 'G');


            //For SL-4
            $trainingOfWorkmenPSetMonth = $this->dao->getTrainingCount(1, 3, "from_date", $month_year_formatted, $financialyearstart, 'P', 'G');
            $trainingOfWorkmenPSetYear = $this->dao->getTrainingCount(2, 3, "from_date", $month_year_formatted, $financialyearstart, 'P', 'G');
            $trainingOfWorkmenPSetMonthLastFY = $this->dao->getTrainingCount(4, 3, "from_date", $lastFinancialMonth, '', 'P', 'G');
            $trainingOfWorkmenPSetYearLastFY = $this->dao->getTrainingCount(3, 3, "from_date", $lastfinancialyear, '', 'P', 'G');

            //For SL-5
            $trainingOfSupervisorPSetMonth = $this->dao->getTrainingCount(1, 3, "from_date", $month_year_formatted, $financialyearstart, 'PS', 'G');
            $trainingOfSupervisorPSetYear = $this->dao->getTrainingCount(2, 3, "from_date", $month_year_formatted, $financialyearstart, 'PS', 'G');
            $trainingOfSupervisorPSetMonthLastFY = $this->dao->getTrainingCount(4, 3, "from_date", $lastFinancialMonth, '', 'PS', 'G');
            $trainingOfSupervisorPSetYearLastFY = $this->dao->getTrainingCount(3, 3, "from_date", $lastfinancialyear, '', 'PS', 'G');
            //   die;
            //For SL-6
            $trainingOfSupervisorCSetMonth = $this->dao->getTrainingCount(1, 3, "from_date", $month_year_formatted, $financialyearstart, 'CS', 'G');
            $trainingOfSupervisorCSetYear = $this->dao->getTrainingCount(2, 3, "from_date", $month_year_formatted, $financialyearstart, 'CS', 'G');
            $trainingOfSupervisorCSetMonthLastFY = $this->dao->getTrainingCount(4, 3, "from_date", $lastFinancialMonth, '', 'CS', 'G');
            $trainingOfSupervisorCSetYearLastFY = $this->dao->getTrainingCount(3, 3, "from_date", $lastfinancialyear, '', 'CS', 'G');
            //For SL-7
            $trainingOfficerMonth = $this->dao->getTrainingCount(1, 3, "from_date", $month_year_formatted, $financialyearstart, 'O', 'G');
            $trainingOfficerYear = $this->dao->getTrainingCount(2, 3, "from_date", $month_year_formatted, $financialyearstart, 'O', 'G');
            $trainingOfficerMonthLastFY = $this->dao->getTrainingCount(4, 3, "from_date", $lastFinancialMonth, '', 'O', 'G');
            $trainingOfficerYearLastFY = $this->dao->getTrainingCount(3, 3, "from_date", $lastfinancialyear, '', 'O', 'G');


            //For SL-8
            $safetyAuditConductedMonth = $this->dao->getSafetyAuditCount(1, 5, "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart, 'G');
            $safetyAuditConductedYear = $this->dao->getSafetyAuditCount(2, 5, "date_of_audit", "actualtarget_audit_view", $month_year_formatted, $financialyearstart, 'G');
            $safetyAuditConductedMonthLastFY = $this->dao->getSafetyAuditCount(4, 5, "date_of_audit", "actualtarget_audit_view", $lastFinancialMonth, '', 'G');
            $safetyAuditConductedYearLastFY = $this->dao->getSafetyAuditCount(3, 5, "date_of_audit", "actualtarget_audit_view", $lastfinancialyear, '', 'G');

            //For SL-10
            $communicationMeetingMonth = $this->dao->getCommMeetingCount(1, 2, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'G');
            $communicationMeetingYear = $this->dao->getCommMeetingCount(2, 2, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'G');
            $communicationMeetingMonthLastFY = $this->dao->getCommMeetingCount(4, 2, "activity_date", "actualtarget_view", $lastFinancialMonth, '', 'G');
            $communicationMeetingYearLastFY = $this->dao->getCommMeetingCount(3, 2, "activity_date", "actualtarget_view", $lastfinancialyear, '', 'G');
            //For SL-11
            $safetyWorkshopMonth = $this->dao->getWorkshopCount(1, 1, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'G');
            $safetyWorkshopYear = $this->dao->getWorkshopCount(2, 1, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'G');
            $safetyWorkshopMonthLastFY = $this->dao->getWorkshopCount(4, 1, "activity_date", "actualtarget_view", $lastFinancialMonth, '', 'G');
            $safetyWorkshopYearLastFY = $this->dao->getWorkshopCount(3, 1, "activity_date", "actualtarget_view", $lastfinancialyear, '', 'G');
            //For SL-12
            $safetyObservationDayMonth = $this->dao->getSafetyDayCount(1, 4, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'G');
            $safetyObservationDayYear = $this->dao->getSafetyDayCount(2, 4, "activity_date", "actualtarget_view", $month_year_formatted, $financialyearstart, 'G');
            $safetyObservationDayMonthLastFY = $this->dao->getSafetyDayCount(4, 4, "activity_date", "actualtarget_view", $lastFinancialMonth, '', 'G');
            $safetyObservationDayYearLastFY = $this->dao->getSafetyDayCount(3, 4, "activity_date", "actualtarget_view", $lastfinancialyear, '', 'G');
            //For SL-13
            $safetyObservationByLineFunctionMonth = $this->dao->getSafetyObservationByLineFunctionCount(1, $month_year_formatted, $financialyearstart, 'G');
            $safetyObservationByLineFunctionYear = $this->dao->getSafetyObservationByLineFunctionCount(2, $financialyearstart, $month_year_formatted, 'G');

            $safetyObservationByLineFunctionMonthLastFY = $this->dao->getSafetyObservationByLineFunctionCount(4, $lastFinancialMonth, '', 'G');
            $safetyObservationByLineFunctionYearLastFY = $this->dao->getSafetyObservationByLineFunctionCount(3, $lastfinancialyear, '', 'G');

            $arr = array(
                50 => array(
                    "annual_target" => "0",
                    "this_month" => $ppeAuditConductedAtCSetGearMonth,
                    "ytm_this_year" => $ppeAuditConductedAtCSetGearYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $ppeAuditConductedAtCSetGearMonthLastFY,
                    "ytm_this_year_lastFY" => $ppeAuditConductedAtCSetGearYearLastFY,
                    "completed_lastFY" => ""),
                51 => array(
                    "annual_target" => "0",
                    "this_month" => $ppeAuditConductedAtPSetGearMonth,
                    "ytm_this_year" => $ppeAuditConductedAtPSetGearYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $ppeAuditConductedAtPSetGearMonthLastFY,
                    "ytm_this_year_lastFY" => $ppeAuditConductedAtPSetGearYearLastFY,
                    "completed_lastFY" => ""),
                52 => array(
                    "annual_target" => "0",
                    "this_month" => $trainingOfWorkmenCSetMonth,
                    "ytm_this_year" => $trainingOfWorkmenCSetYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $trainingOfWorkmenCSetMonthLastFY,
                    "ytm_this_year_lastFY" => $trainingOfWorkmenCSetYearLastFY,
                    "completed_lastFY" => ""),
                53 => array(
                    "annual_target" => "0",
                    "this_month" => $trainingOfWorkmenPSetMonth,
                    "ytm_this_year" => $trainingOfWorkmenPSetYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $trainingOfWorkmenPSetMonthLastFY,
                    "ytm_this_year_lastFY" => $trainingOfWorkmenPSetYearLastFY,
                    "completed_lastFY" => ""),
                54 => array(
                    "annual_target" => "0",
                    "this_month" => $trainingOfSupervisorPSetMonth,
                    "ytm_this_year" => $trainingOfSupervisorPSetYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $trainingOfSupervisorPSetMonthLastFY,
                    "ytm_this_year_lastFY" => $trainingOfSupervisorPSetYearLastFY,
                    "completed_lastFY" => ""),
                55 => array(
                    "annual_target" => "0",
                    "this_month" => $trainingOfSupervisorCSetMonth,
                    "ytm_this_year" => $trainingOfSupervisorCSetYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $trainingOfSupervisorCSetMonthLastFY,
                    "ytm_this_year_lastFY" => $trainingOfSupervisorCSetYearLastFY,
                    "completed_lastFY" => ""),
                56 => array(
                    "annual_target" => "0",
                    "this_month" => $trainingOfficerMonth,
                    "ytm_this_year" => $trainingOfficerYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $trainingOfficerMonthLastFY,
                    "ytm_this_year_lastFY" => $trainingOfficerYearLastFY,
                    "completed_lastFY" => ""),
                57 => array(
                    "annual_target" => "0",
                    "this_month" => $safetyAuditConductedMonth,
                    "ytm_this_year" => $safetyAuditConductedYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $safetyAuditConductedMonthLastFY,
                    "ytm_this_year_lastFY" => $safetyAuditConductedYearLastFY,
                    "completed_lastFY" => ""),
                58 => array(
                    "annual_target" => "0",
                    "this_month" => $jobAuditOfEmDepotCallCentreMonth = "",
                    "ytm_this_year" => $jobAuditOfEmDepotCallCentreYear = "",
                    "completed" => ""),
                59 => array(
                    "annual_target" => "0",
                    "this_month" => $communicationMeetingMonth,
                    "ytm_this_year" => $communicationMeetingYear,
                    "completed" => $communicationMeetingCompleted = "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $communicationMeetingMonthLastFY,
                    "ytm_this_year_lastFY" => $communicationMeetingYearLastFY,
                    "completed_lastFY" => ""),
                60 => array(
                    "annual_target" => "0",
                    "this_month" => $safetyWorkshopMonth,
                    "ytm_this_year" => $safetyWorkshopYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $safetyWorkshopMonthLastFY,
                    "ytm_this_year_lastFY" => $safetyWorkshopYearLastFY,
                    "completed_lastFY" => ""),
                61 => array(
                    "annual_target" => "0",
                    "this_month" => $safetyObservationDayMonth,
                    "ytm_this_year" => $safetyObservationDayYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => $safetyObservationDayMonthLastFY,
                    "ytm_this_year_lastFY" => $safetyObservationDayYearLastFY,
                    "completed_lastFY" => ""),
                62 => array(
                    "annual_target" => "0",
                    "this_month" => $safetyObservationByLineFunctionMonth,
                    "ytm_this_year" => @$safetyObservationByLineFunctionYear,
                    "completed" => "",
                    "annual_target_lastFY" => "0",
                    "this_month_lastFY" => @$safetyObservationByLineFunctionMonthLastFY,
                    "ytm_this_year_lastFY" => @$safetyObservationByLineFunctionYearLastFY,
                    "completed_lastFY" => ""),
            );
            foreach ($getMajorItem as $key => $rowdata) {
                if ($rowdata->id != "58") {


                    $targetvalue_curr = $this->dao->get_mis_target($rowdata->id, $current_financial_year);
                    $targetvalue_prev = $this->dao->get_mis_target($rowdata->id, $prev_financial_year);
                    @$arrData[$key]->id = $rowdata->id;
                    @$arrData[$key]->name = $rowdata->name;
                    @$arrData[$key]->type = $rowdata->type;
                    @$arrData[$key]->annual_target = $targetvalue_curr["gen_target"];
                    @$arrData[$key]->this_month = $arr[$rowdata->id]["this_month"];
                    @$arrData[$key]->ytm_this_year = $arr[$rowdata->id]["ytm_this_year"];
                    @$arrData[$key]->completed = $arr[$rowdata->id]["completed"];
                    @$arrData[$key]->annual_target_lastFY = $targetvalue_prev["gen_target"];
                    @$arrData[$key]->this_month_lastFY = $arr[$rowdata->id]["this_month_lastFY"];
                    @$arrData[$key]->ytm_this_year_lastFY = $arr[$rowdata->id]["ytm_this_year_lastFY"];
                    @$arrData[$key]->completed_lastFY = $arr[$rowdata->id]["completed_lastFY"];
                }
            }

            $arr_date_send["curr_month"] = $month_year;
            $arr_date_send["curr_year"] = $current_financial_year;
            $arr_date_send["last_month"] = $lastFinancialMonth;
            $arr_date_send["last_year"] = $lastfinancialyear;
        }
        if (!empty($arrData))
            $this->beanUi->set_view_data("majorItem", $arrData);
        $this->beanUi->set_view_data("arr_date_send", $arr_date_send);
    }

    public function spotLightIncidentStatistics() {
        $action = $this->bean->get_request("_action");
        $curr_date = date('Y-m-d');
        if ($action == "Create") {
            $SetDMY = array();
            //---------START D-M-Y -----//
            $month_year = $this->bean->get_request("month_year");
            $jhanthDate = date("Y-m-t", strtotime('5'.'-'.$month_year));
            if ($month_year) {
                $PreviousFinancialYear1 = $presentFinancialyear = "";
                $month_yearexp = explode("-", $month_year);
                $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
                $one_year_back = date("Y-m", strtotime("-1 Year", strtotime($month_year_formatted . "-01")));
                $PreviousFinancialYear1 = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
                $presentFinancialyear = getPrevFinancialYear($month_yearexp[1], $month_yearexp[0], "Y");
                $prevYear1Arr = explode(",", $PreviousFinancialYear1);
                $currentMonth = $month_year;
                $onsss = date("Y-m-t", strtotime($one_year_back));
                $prevYear1 = "'" . $prevYear1Arr[0] . "-01' AND '" . $onsss . "'";
                $CurrentYearArr = explode(",", $presentFinancialyear);
                $endateformatted = date("Y-m-t", strtotime($month_year_formatted));
                $PresentYear = "'" . $CurrentYearArr[0] . "-01' AND '" . $endateformatted . "'";


                //---------END D-M-Y -----//

                $incident_category_id = $this->reportdao->get_incident_category();
                $incident_type_id = array(1,2);

                $REPOTED_ACC_STAT_PSET = array();
                $REPOTED_ACC_STAT_CSET = array();

                $scoreID = $this->reportdao->getReportSettings(6, "id");
                $rowArray = explode(",", $scoreID[0]->param_value);                
                $dataArray1 = $dataArray2 = $dataArray3 = $dataArray4 = array();
                //show($incident_type_id);
                foreach ($rowArray as $k => $rw) {
                 if($rw != '182' && $rw != '169' ) {        
                    foreach ($incident_type_id as $ky => $v){
                    $clause = " id='" . $rw . "'";
                    $val1 = $this->reportdao->get_division_department($clause);
                    $getLatestFatalDate = $this->dao->get_latest_incident_date_fatal($jhanthDate,$rw, 4 );
                    if(isset($getLatestFatalDate )){
                        $date1 = new DateTime($getLatestFatalDate);
                    } else {
                        $date1 = new DateTime('2015-03-31');
                    }
                    $date2 = new DateTime($jhanthDate);
                    $dayss  = $date2->diff($date1)->format('%a');
                    if($date1 == new DateTime('2015-03-31')){
                        $days = $dayss;
                    } else {
                        $days = $dayss;
                    }
                    $total_inc_cat = '2,3,4';
                    $getLatestFatalDateTotal = $this->dao->get_latest_incident_date_total($jhanthDate,$rw, $total_inc_cat );
                    if($getLatestFatalDateTotal != ''){
                        $date11 = new DateTime($getLatestFatalDateTotal);
                    } else {
                        $date11 = new DateTime('2015-03-31');
                    }
                    $date22 = new DateTime($jhanthDate);
                    $dayss1  = $date22->diff($date11)->format('%a');
                    if($date11 == new DateTime('2015-03-31')){
                        $days1 = $dayss1;
                    } else {
                        $days1 = $dayss1;
                    }
                    $dataArray1[$k][$v]["id"] = $rw;
                    $dataArray2[$k][$v]["id"] = $rw;
                    //$dataArray3[$k]["id"] = $rw;
                    //$dataArray4[$k]["id"] = $rw;
                    $dataArray1[$k][$v]["name"] = ($val1[0]->name);
                    $dataArray2[$k][$v]["name"] = ($val1[0]->name);
                    $dataArray2[$k][$v]["FatalDate"] = $days;
                    $dataArray2[$k][$v]["tatalIncDay1"] = $getLatestFatalDateTotal;
                    $dataArray2[$k][$v]["tatalIncDay"] = $days1;
                    //$dataArray2[$k][$v]["tatalIncDay1"] = $rw;
                    //$dataArray3[$k]["name"] = ($val1[0]->name);
                    //$dataArray4[$k]["name"] = ($val1[0]->name);

                    foreach ($incident_category_id as $key => $rowdata) {
                                                
                            $dataArray1[$k][$v]["INC"][$rowdata->id]["CURRENT_MONTH"]       = $this->dao->getReportedAccidentStatistics_cur("P-SET", $month_year_formatted, $rw, $rowdata->id, 1, $v,$rowArray);
                            $dataArray1[$k][$v]["INC"][$rowdata->id]["PREVIOUS_YEAR_YTM"]   = $this->dao->getReportedAccidentStatistics_pfy("P-SET", $prevYear1, $rw, $rowdata->id,$rowArray);
                            $dataArray1[$k][$v]["INC"][$rowdata->id]["CURRENT_YEAR_YTM"]    = $this->dao->getReportedAccidentStatistics_cur("P-SET", $PresentYear, $rw, $rowdata->id, '', $v,$rowArray);
                            $dataArray2[$k][$v]["INC"][$rowdata->id]["CURRENT_MONTH"]       = $this->dao->getReportedAccidentStatistics_cur("C-SET", $month_year_formatted, $rw, $rowdata->id, 1, $v,$rowArray);
                            $dataArray2[$k][$v]["INC"][$rowdata->id]["PREVIOUS_YEAR_YTM"]   = $this->dao->getReportedAccidentStatistics_pfy("C-SET", $prevYear1, $rw, $rowdata->id,$rowArray);
                            $dataArray2[$k][$v]["INC"][$rowdata->id]["CURRENT_YEAR_YTM"]    = $this->dao->getReportedAccidentStatistics_cur("C-SET", $PresentYear, $rw, $rowdata->id, '', $v,$rowArray);
                        }
                    }
                    }
                }
            }
            //show($dataArray2);
            $SetDMY["PREV_Y1"] = $PreviousFinancialYear1;
            $SetDMY["CURR_Y"] = $presentFinancialyear;
            $this->beanUi->set_view_data("getDate", $SetDMY);
            $this->beanUi->set_view_data("getAllPSETData", $dataArray1);
            $this->beanUi->set_view_data("getAllCSETData", $dataArray2);
            $this->beanUi->set_view_data("getAllPCSETData", $dataArray3);
            $this->beanUi->set_view_data("getAllOTHERSData", $dataArray4);
            $this->beanUi->set_view_data("incident_category", $incident_category_id);
            $this->beanUi->set_view_data("incident_type", $incident_type_id);
            $this->beanUi->set_view_data("REPOTED_ACC_STAT_CSET", $REPOTED_ACC_STAT_CSET);
            if (!empty($REPOTED_ACC_STAT_PCSET))
                $this->beanUi->set_view_data("REPOTED_ACC_STAT_PCSET", $REPOTED_ACC_STAT_PCSET);
            if (!empty($REPOTED_ACC_STAT_OTHERS))
                $this->beanUi->set_view_data("REPOTED_ACC_STAT_OTHERS", $REPOTED_ACC_STAT_OTHERS);
            $this->beanUi->set_view_data("month_year", $month_year);
        }
    }

    public function spotLightScoresStatistics() {

        $action = $this->bean->get_request("_action");
        if ($action == "Create") {
            $month_year = $this->bean->get_request("month_year");
            $month_yearexp = explode("-", $month_year);
            $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));

            $month_of_year_back = date("Y-m", strtotime("-1 Year", strtotime($month_year_formatted . "-01")));
            $one_year_back = date("Y-m", strtotime("-1 Year", strtotime($month_year_formatted . "-01")));

            $one_year_back = date("Y-m", strtotime("-1 Year", strtotime($month_year_formatted . "-01")));
            $PreviousFinancialYear1 = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
            $presentFinancialyear = getPrevFinancialYear($month_yearexp[1], $month_yearexp[0], "Y");
            $prevYear1Arr = explode(",", $PreviousFinancialYear1);
            $currentMonth = $month_year;
            $bkdate = date("Y-m-t", strtotime($one_year_back));
            $prevYear1 = "'" . $prevYear1Arr[0] . "-01' AND '" . $bkdate . "'";
            $CurrentYearArr = explode(",", $presentFinancialyear);
            $predate = date("Y-m-t", strtotime($month_year_formatted));
            $PresentYear = "'" . $CurrentYearArr[0] . "-01' AND '" . $predate . "'";
            $scoreID = $this->reportdao->getReportSettings(6, "id");
            $rowArray = explode(",", $scoreID[0]->param_value);
            $dataArray1 = $dataArray2 = $dataArray3 = array();
            foreach ($rowArray as $k => $rw) {
                if ($rw != '182' && $rw != '4') {
                    $clause = " id='" . $rw . "'";
                    $val1 = $this->reportdao->get_division_department($clause);
//                    if ($rw != '10' && $rw != '9') {
                    if ($rw != '10' && $rw != '169') {
                        $dataArray1[$k]["name"] = $val1[0]->name;
                        $dataArray1[$k]["division_id"] = $rw;
                        @$dataArray1[$k]["no_of_unit_set"] = $this->reportdao->division_child_unit_count($rw, 'P-SET');
                        $dataArray1[$k]["PREV"] = $this->dao->getAuditDataforeSurveillance('P-SET', 1, $month_of_year_back, $rw);
                        $dataArray1[$k]["CURRENT"] = $this->dao->getAuditDataforeSurveillance('P-SET', 1, $month_year_formatted, $rw);
                        $dataArray1[$k]["prev_ytm"] = $this->dao->getAuditDataforeSurveillance('P-SET', 2, $prevYear1, $rw);
                        $dataArray1[$k]["current_ytm"] = $this->dao->getAuditDataforeSurveillance('P-SET', 2, $PresentYear, $rw);
                    }
//                    if ($rw != '168' && $rw != '9' && $rw != '7') {
                    if ($rw != '168' && $rw != '169' && $rw != '7') {
                        $dataArray2[$k]["name"] = $val1[0]->name;
                        $dataArray2[$k]["division_id"] = $rw;
                        @$dataArray2[$k]["no_of_unit_set"] = $this->reportdao->division_child_unit_count($rw, 'C-SET');
                        $dataArray2[$k]["PREV"] = $this->dao->getAuditDataforeSurveillance('C-SET', 1, $month_of_year_back, $rw);
                        $dataArray2[$k]["CURRENT"] = $this->dao->getAuditDataforeSurveillance('C-SET', 1, $month_year_formatted, $rw);
                        $dataArray2[$k]["prev_ytm"] = $this->dao->getAuditDataforeSurveillance('C-SET', 2, $prevYear1, $rw);
                        $dataArray2[$k]["current_ytm"] = $this->dao->getAuditDataforeSurveillance('C-SET', 2, $PresentYear, $rw);
                    }
                }
            }
            $SetDMY = array();
            $SetDMY["PREV_YTM"] = $PreviousFinancialYear1;
            $SetDMY["CURR_YTM"] = $presentFinancialyear;
            $this->beanUi->set_view_data("getDate", $SetDMY);
            $this->beanUi->set_view_data("getAllPSETData", $dataArray1);
            $this->beanUi->set_view_data("getAllCSETData", $dataArray2);
            $this->beanUi->set_view_data("month_year", $month_year);
            $this->beanUi->set_view_data("month_of_year_back", $month_of_year_back);
        }
    }

    public function getParentTree($treeid) {
        $parent = "";
        $showArr = array();
        $clause = " id=" . $treeid;
        $parentDivision = $this->reportdao->get_division_department($clause);

        if ($parentDivision[0]->parent_id != 0 && $parentDivision[0]->id != 1) {
            $clause1 = " id=" . $parentDivision[0]->parent_id;
            $parentDivision1 = $this->reportdao->get_division_department($clause1);
            if ($parentDivision1[0]->parent_id != 0 && $parentDivision1[0]->parent_id != 1) {
                $clause2 = " id=" . $parentDivision1[0]->parent_id;
                $parentDivision2 = $this->reportdao->get_division_department($clause2);
                if ($parentDivision2[0]->parent_id != 0 && $parentDivision2[0]->parent_id != 1) {
                    $clause3 = " id=" . $parentDivision2[0]->parent_id;
                    $parentDivision3 = $this->reportdao->get_division_department($clause3);
                    if ($parentDivision3[0]->parent_id != 0 && $parentDivision3[0]->parent_id != 1) {
                        $clause4 = " id=" . $parentDivision3[0]->parent_id;
                        $parentDivision4 = $this->reportdao->get_division_department($clause4);
                        if ($parentDivision4[0]->parent_id != 0 && $parentDivision4[0]->parent_id != 1) {
                            $clause5 = " id=" . $parentDivision4[0]->parent_id;
                            $parentDivision5 = $this->reportdao->get_division_department($clause5);
                            if ($parentDivision5[0]->parent_id != 0 && $parentDivision5[0]->parent_id != 1) {
                                $parent .= $parentDivision5[0]->parent_id . "-";
                            }
                            $parent .= $parentDivision4[0]->parent_id . "-";
                        }
                        $parent .= $parentDivision3[0]->parent_id . "-";
                    }
                    $parent .= $parentDivision2[0]->parent_id . "-";
                }
                $parent .= $parentDivision1[0]->parent_id . "-";
            }
        }
        $parent .= $parentDivision[0]->parent_id;
        return $parent;
    }

    public function mrmDistributionlist() {
        $getmrmdata = $this->dao->getmrmreport();
        $this->beanUi->set_view_data("getmrmdata", $getmrmdata);
    }

    public function editmrmDistribution() {

        $mnthyr = $this->bean->get_request("mnthyr");
        $rowid = $this->bean->get_request("id");
        $mode = $this->bean->get_request("mode");

        $checkmrmreportexist = $this->dao->checkexistmrmdistribution($mnthyr);
        $month_year = $mnthyr;
        $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
        $month_yearexp = explode("-", $month_year);
        $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
        $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
        $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
        $previous_financial_year = getFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], $format = "Y");
        $prevfyrange = getAllMonthYear($previous_financial_year);
        $getytmrange = getAllYTMYear($current_financial_year, $month_yearexp[0]);
        $newArr = array();

        if (count($checkmrmreportexist) > 0) {
            $fetachallmappingdata = $this->dao->fetchallmrmdistribution($checkmrmreportexist[0]->id);

            $_action = $this->bean->get_request("_action");

            if ($_action == "submitData") {
                if (isset($_POST["B1"])) {
                    $status = 1;
                } else {
                    $status = 2;
                }
                $basicData = array();
                $basicData["id"] = $checkmrmreportexist[0]->id;
                $basicData["status_id"] = $status;
                $this->dao->_table = "mis_mrmdistribution";
                $basic_data_id = $this->dao->save($basicData);
                $rowcount = $this->bean->get_request("rowcount");
                $mappArr = array();
                foreach ($rowcount as $key => $value) {
                    $row_id = $this->bean->get_request("rowid_" . $key);
                    $engineer_name = $this->bean->get_request("engineer_name_" . $key);
                    $other_activities = $this->bean->get_request("other_activities_" . $key);
                    $other_activities1 = $this->bean->get_request("other_activities1_" . $key);

                    $mappArr["engineer_name"] = $engineer_name;
                    $mappArr["other_activities"] = $other_activities;
                    $mappArr["other_activities1"] = $other_activities1;
                    $mappArr["id"] = $row_id;
                    $this->dao->_table = "mis_mrmdistribution_mapping";
                    $insertID = $this->dao->save($mappArr);
                }
                redirect(page_link("mis/mrmDistributionlist.php"));
            }

            $getDate = array();
            $getDate["CURR_YTM"] = $date_arr["CFY_FORMATTED"];
            $getDate["PREV_YTM"] = $date_arr["PFY_FORMATTED"];
            $this->beanUi->set_view_data("getDate", $getDate);
            $this->beanUi->set_view_data("fetchalldata", $fetachallmappingdata);

            $this->beanUi->set_view_data("selected_month_year", $mnthyr);
            $this->beanUi->set_view_data("mnthyr", $mnthyr);
            $this->beanUi->set_view_data("current_financial_year", $current_financial_year);
            $this->beanUi->set_view_data("previous_financial_year", $previous_financial_year);
            $this->beanUi->set_view_data("rowsingledata", $checkmrmreportexist);
            $this->beanUi->set_view_data("mode", $mode);
        }
    }

    //Report-20
    public function statisticsforMRMDistribution() {
        $action = $this->bean->get_request("_action");
        if ($action == "Create") {
            $incident_category_id = $this->reportdao->get_incident_category();
            $month_year = $this->bean->get_request("month_year");
            if ($month_year != "") {
                $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
                $scoreID = $this->reportdao->getReportSettings(10, "id");
                $rowArray = explode(",", $scoreID[0]->param_value);
                $totaldiv = 0;
                $dataArr = array();
//            show($date_arr);
                foreach ($rowArray as $k => $rr) {
                    $trr = array();
                    $clause = " id='" . $rr . "'";
                    //-------------------------------All District----------------------------------------//
                    $alldistrict = $this->reportdao->get_division_department($clause);

                    $treeArr = $this->getParentTree($alldistrict[0]->parent_id);
                    $tt = ($treeArr != 1) ? $treeArr . "-" : "";
                    $tree_id = $tt . $alldistrict[0]->parent_id . "-" . $rr;

                    $dataArr[$k]["DISTRICT"] = $alldistrict[0]->name;
                    $dataArr[$k]["DISTRICT_ID"] = $alldistrict[0]->id;
                    $dataArr[$k]["CM_PSET"] = $this->dao->getAuditCountCallCentre("P-SET", 1, $date_arr['CY_AND_CM'], $tree_id, 1);
                    $dataArr[$k]["CM_CC"] = $this->dao->getAuditCountCallCentre("P-SET", 1, $date_arr['CY_AND_CM'], $tree_id, 2);
                    $dataArr[$k]["CM_CSET"] = $this->dao->getAuditCount("C-SET", 1, $date_arr['CY_AND_CM'], $tree_id);
                    $dataArr[$k]["CM_OTHER_ACTIVITIES"] = '';

                    $dataArr[$k]["CYTM_PSET"] = $this->dao->getAuditCountCallCentre("P-SET", 2, $date_arr['CFY_YTM_QRY'], $tree_id, 1);
                    $dataArr[$k]["CYTM_CC"] = $this->dao->getAuditCountCallCentre("P-SET", 2, $date_arr['CFY_YTM_QRY'], $tree_id, 2);
                    $dataArr[$k]["CYTM_CSET"] = $this->dao->getAuditCount("C-SET", 2, $date_arr['CFY_YTM_QRY'], $tree_id);
                    $dataArr[$k]["CYTM_OTHER_ACTIVITIES"] = '';
                    $inc1[$k] = $this->dao->getIncidentCount(1, $date_arr['CY_AND_CM'], $tree_id);
                    $inc2[$k] = $this->dao->getIncidentCount(2, $date_arr['CFY_YTM_QRY'], $tree_id);
                    $inc3[$k] = $this->dao->getIncidentCount(2, $date_arr['PFY_QRY'], $tree_id);
                    $dataArr[$k]["INC_CM"] = array_sum($inc1[$k]);
                    $dataArr[$k]["INC_YTM"] = array_sum($inc2[$k]);
                    $dataArr[$k]["INC_PREVYEAR"] = array_sum($inc3[$k]);
                }

                $checkmrmreportexist = $this->dao->checkexistmrmdistribution($month_year);
                if (count($checkmrmreportexist) > 0) {
                    $ids = $checkmrmreportexist[0]->id;
                    if ($checkmrmreportexist[0]->status_id == 1) {
                        redirect(page_link("mis/editmrmDistribution.php?mnthyr=$month_year&id=$ids"));
                    } else {
                        redirect(page_link("mis/editmrmDistribution.php?mnthyr=$month_year&id=$ids&mode=view"));
                    }
                }

                $getDate = array();
                $getDate["CURR_YTM"] = $date_arr["CFY_FORMATTED"];
                $getDate["PREV_YTM"] = $date_arr["PFY_FORMATTED"];
                $this->beanUi->set_view_data("fetchAllData", $dataArr);
                $this->beanUi->set_view_data("month_year", $month_year);
                $this->beanUi->set_view_data("incident_cm", $inc1);
                $this->beanUi->set_view_data("incident_ytm", $inc2);
                $this->beanUi->set_view_data("incident_prev", $inc3);
                $this->beanUi->set_view_data("incident_category_id", $incident_category_id);
                $this->beanUi->set_view_data("getDate", $getDate);
            }
        }

        if ($action == "submitData") {
            $rowcount = $this->bean->get_request("rowcount");
            $month_year = $this->bean->get_request("month_year");
            $cm_data = $this->bean->get_request("cm_data");
            $ytm_data = $this->bean->get_request("ytm_data");
            $pfy_data = $this->bean->get_request("pfy_data");
            if (isset($_POST["B1"])) {
                $status = 1;
            } else {
                $status = 2;
            }
            $basicData = array();
            $basicData["month_year"] = $month_year;
            $basicData["cm_data"] = $cm_data;
            $basicData["ytm_data"] = $ytm_data;
            $basicData["pfy_data"] = $pfy_data;
            $basicData["status_id"] = $status;
            $basicData["created_date"] = date("Y-m-d");
            $this->dao->_table = "mis_mrmdistribution";
            $basic_data_id = $this->dao->save($basicData);
            $mappArr = array();
            foreach ($rowcount as $key => $value) {
                $engineer_name = $this->bean->get_request("engineer_name_" . $key);
                $district = $this->bean->get_request("district_" . $key);
                $cm_pset_audit_count = $this->bean->get_request("cm_pset_audit_count_" . $key);
                $cm_pset_avg = $this->bean->get_request("cm_pset_avg_" . $key);
                $cm_cc_audit_count = $this->bean->get_request("cm_cc_audit_count_" . $key);
                $cm_cc_avg = $this->bean->get_request("cm_cc_avg_" . $key);
                $cm_cset_audit_count = $this->bean->get_request("cm_cset_audit_count_" . $key);
                $cm_cset_avg = $this->bean->get_request("cm_cset_avg_" . $key);
                $cm_ovr_audit_count = $this->bean->get_request("cm_ovr_audit_count_" . $key);
                $cm_ovr_avg = $this->bean->get_request("cm_ovr_avg_" . $key);
                $other_activities = $this->bean->get_request("other_activities_" . $key);
                $cytm_pset_audit_count = $this->bean->get_request("cytm_pset_audit_count_" . $key);
                $cytm_pset_avg = $this->bean->get_request("cytm_pset_avg_" . $key);
                $cytm_cc_audit_count = $this->bean->get_request("cytm_cc_audit_count_" . $key);
                $cytm_cc_avg = $this->bean->get_request("cytm_cc_avg_" . $key);
                $cytm_cset_audit_count = $this->bean->get_request("cytm_cset_audit_count_" . $key);
                $cytm_cset_avg = $this->bean->get_request("cytm_cset_avg_" . $key);
                $cytm_ovr_audit_count = $this->bean->get_request("cytm_ovr_audit_count_" . $key);
                $cytm_ovr_avg = $this->bean->get_request("cytm_ovr_avg_" . $key);
                $other_activities1 = $this->bean->get_request("other_activities1_" . $key);
                $inc_cm = $this->bean->get_request("inc_cm_" . $key);
                $inc_cytm = $this->bean->get_request("inc_cytm_" . $key);
                $inc_pfy = $this->bean->get_request("inc_pfy_" . $key);
                $mappArr["mrmreport_id"] = $basic_data_id;
                $mappArr["engineer_name"] = $engineer_name;
                $mappArr["district"] = $district;
                $mappArr["cm_pset_audit_count"] = $cm_pset_audit_count;
                $mappArr["cm_pset_avg"] = $cm_pset_avg;
                $mappArr["cm_cc_audit_count"] = $cm_cc_audit_count;
                $mappArr["cm_cc_avg"] = $cm_cc_avg;
                $mappArr["cm_cset_audit_count"] = $cm_cset_audit_count;
                $mappArr["cm_cset_avg"] = $cm_cset_avg;
                $mappArr["cm_ovr_audit_count"] = $cm_ovr_audit_count;
                $mappArr["cm_ovr_avg"] = $cm_ovr_avg;
                $mappArr["other_activities"] = $other_activities;
                $mappArr["cytm_pset_audit_count"] = $cytm_pset_audit_count;
                $mappArr["cytm_pset_avg"] = $cytm_pset_avg;
                $mappArr["cytm_cc_audit_count"] = $cytm_cc_audit_count;
                $mappArr["cytm_cc_avg"] = $cytm_cc_avg;
                $mappArr["cytm_cset_audit_count"] = $cytm_cset_audit_count;
                $mappArr["cytm_cset_avg"] = $cytm_cset_avg;
                $mappArr["cytm_ovr_audit_count"] = $cytm_ovr_audit_count;
                $mappArr["cytm_ovr_avg"] = $cytm_ovr_avg;
                $mappArr["other_activities1"] = $other_activities1;
                $mappArr["inc_cm"] = $inc_cm;
                $mappArr["inc_cytm"] = $inc_cytm;
                $mappArr["inc_pfy"] = $inc_pfy;
                $this->dao->_table = "mis_mrmdistribution_mapping";
                $insertID = $this->dao->save($mappArr);
            }
        }
    }

    public function statisticsforMRMGeneration() {

        $action = $this->bean->get_request("_action");
        $incident_category_id = $this->reportdao->get_incident_category();
        if ($action == "Create") {
            $month_year = $this->bean->get_request("month_year");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $nodeArr = array(1 => "Safety Workshop", 2 => "Communication Meeting", 3 => "Contractor Employee Training", 4 => "Permanent Workmen Training",
                5 => "Permanent Supervisors Training", 6 => "Officer Training", 7 => "Field Audit conducted (P-set)", 8 => "Field Audit conducted (C-set)",
                9 => "No of Incidents recorded at OHC : FAC", 10 => "No of Incidents recorded at OHC : LWC", 11 => "No of Incidents recorded at OHC : FATAL",
                12 => "No of Incidents recorded as Near Miss", 13 => "Safety Day");
            $activity_type_id = array(
                1 => "1", 2 => "2", 3 => "3", 4 => "3", 5 => "3", 6 => "3", 7 => "5", 8 => "5", 9 => "6", 10 => "6", 11 => "6", 12 => "6", 13 => "4"
            );
            $table_name = array(
                1 => "actualtarget_view", 2 => "actualtarget_view", 3 => "mrmgeneration_training_view", 4 => "mrmgeneration_training_view", 5 => "mrmgeneration_training_view",
                6 => "mrmgeneration_training_view", 7 => "actualtarget_audit_view", 8 => "actualtarget_audit_view", 9 => "actualtarget_incident_view",
                10 => "actualtarget_incident_view", 11 => "actualtarget_incident_view", 12 => "actualtarget_incident_view", 13 => "actualtarget_view"
            );
            $column_name = array(
                1 => "activity_date", 2 => "activity_date", 3 => "from_date", 4 => "from_date", 5 => "from_date", 6 => "from_date",
                7 => "date_of_audit", 8 => "date_of_audit", 9 => "date_of_incident", 10 => "date_of_incident", 11 => "date_of_incident",
                12 => "date_of_incident", 13 => "activity_date"
            );
            $dataArr = array();
            foreach ($nodeArr as $key => $value) {
                $dataArr[$key]["NAME"] = $value;
                $dataArr[$key]["CURRENT_FY"]["BBGS"]["CURRENT"] = $this->dao->getGenerationCount(1, $key, $activity_type_id[$key], "249-2-181", $table_name[$key], $column_name[$key], $date_arr['CY_AND_CM']);
                $dataArr[$key]["CURRENT_FY"]["BBGS"]["YTM"] = $this->dao->getGenerationCount(2, $key, $activity_type_id[$key], "249-2-181", $table_name[$key], $column_name[$key], $date_arr['CFY_YTM_QRY']);
                $dataArr[$key]["CURRENT_FY"]["SGS"]["CURRENT"] = $this->dao->getGenerationCount(1, $key, $activity_type_id[$key], "249-2-183", $table_name[$key], $column_name[$key], $date_arr['CY_AND_CM']);
                $dataArr[$key]["CURRENT_FY"]["SGS"]["YTM"] = $this->dao->getGenerationCount(2, $key, $activity_type_id[$key], "249-2-183", $table_name[$key], $column_name[$key], $date_arr['CFY_YTM_QRY']);
                $dataArr[$key]["PREV1_FY"]["BBGS"]["CURRENT"] = $this->dao->getGenerationCount(1, $key, $activity_type_id[$key], "249-2-181", $table_name[$key], $column_name[$key], $date_arr['PY_AND_CM']);
                $dataArr[$key]["PREV1_FY"]["BBGS"]["YTM"] = $this->dao->getGenerationCount(2, $key, $activity_type_id[$key], "249-2-181", $table_name[$key], $column_name[$key], $date_arr['PFY_QRY']);
                $dataArr[$key]["PREV1_FY"]["SGS"]["CURRENT"] = $this->dao->getGenerationCount(1, $key, $activity_type_id[$key], "249-2-183", $table_name[$key], $column_name[$key], $date_arr['PY_AND_CM']);
                $dataArr[$key]["PREV1_FY"]["SGS"]["YTM"] = $this->dao->getGenerationCount(2, $key, $activity_type_id[$key], "249-2-183", $table_name[$key], $column_name[$key], $date_arr['PFY_QRY']);
                $dataArr[$key]["PREV2_FY"]["BBGS"]["CURRENT"] = $this->dao->getGenerationCount(1, $key, $activity_type_id[$key], "249-2-181", $table_name[$key], $column_name[$key], $date_arr['POPY_AND_CM']);
                $dataArr[$key]["PREV2_FY"]["BBGS"]["YTM"] = $this->dao->getGenerationCount(2, $key, $activity_type_id[$key], "249-2-181", $table_name[$key], $column_name[$key], $date_arr['POPFY_QRY']);
                $dataArr[$key]["PREV2_FY"]["SGS"]["CURRENT"] = $this->dao->getGenerationCount(1, $key, $activity_type_id[$key], "249-2-183", $table_name[$key], $column_name[$key], $date_arr['POPY_AND_CM']);
                $dataArr[$key]["PREV2_FY"]["SGS"]["YTM"] = $this->dao->getGenerationCount(2, $key, $activity_type_id[$key], "249-2-183", $table_name[$key], $column_name[$key], $date_arr['POPFY_QRY']);
            }

            $SetDMY = array();
            $SetDMY["PREV2_YTM"] = $date_arr['POPFY'];
            $SetDMY["PREV_YTM"] = $date_arr['PFY'];
            $SetDMY["CURR_YTM"] = $date_arr['CFY'];
            $this->beanUi->set_view_data("getDate", $SetDMY);
            $this->beanUi->set_view_data("all_data_generation", $dataArr);
            $this->beanUi->set_view_data("month_year", $month_year);
            $this->beanUi->set_view_data("month_of_year_back", $date_arr['PY_AND_CM']);
            $this->beanUi->set_view_data("month_of_year_back2", $date_arr['POPY_AND_CM']);
        }
    }

    public function generation_score_card() {
        $action = $this->bean->get_request("_action");
        if ($action == "Create") {
            $month_year = $this->bean->get_request("month_year");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $dataArr = array();
            $scoreID = $this->reportdao->getReportSettings(11, "id");
            $rowArray = explode(",", $scoreID[0]->param_value);
            $totaldiv = 0;
            $dataArr = array();
            foreach ($rowArray as $k => $rr) {
                $clause = " id='" . $rr . "'";
                $alldistrict = $this->reportdao->get_division_department($clause);
                $clause2 = " id='" . $alldistrict[0]->parent_id . "'";
                $getparent = $this->reportdao->get_division_department($clause2);

                $treeArr = $this->getParentTree($alldistrict[0]->parent_id);
                $tree_id = $treeArr . "-" . $alldistrict[0]->parent_id . "-" . $rr;

                @$dataArr[$getparent[0]->name][$rr]["DISTRICT"] = $alldistrict[0]->name;
                @$dataArr[$getparent[0]->name][$rr]["PREV_YEAR"]["PSET"] = $this->dao->getAuditCount("P-SET", 2, $date_arr['PFY_QRY'], $tree_id);
                @$dataArr[$getparent[0]->name][$rr]["PREV_YEAR"]["CSET"] = $this->dao->getAuditCount("C-SET", 2, $date_arr['PFY_QRY'], $tree_id);
                @$dataArr[$getparent[0]->name][$rr]["CURRENT_MONTH"]["PSET"] = $this->dao->getAuditCount("P-SET", 1, $date_arr['CY_AND_CM'], $tree_id);
                @$dataArr[$getparent[0]->name][$rr]["CURRENT_MONTH"]["CSET"] = $this->dao->getAuditCount("C-SET", 1, $date_arr['CY_AND_CM'], $tree_id);
                @$dataArr[$getparent[0]->name][$rr]["CURRENT_YTM"]["PSET"] = $this->dao->getAuditCount("P-SET", 2, $date_arr['CFY_YTM_QRY'], $tree_id);
                @$dataArr[$getparent[0]->name][$rr]["CURRENT_YTM"]["CSET"] = $this->dao->getAuditCount("C-SET", 2, $date_arr['CFY_YTM_QRY'], $tree_id);
            }
            $SetDMY = array();
            $SetDMY["PFY"] = $date_arr['PFY'];
            $SetDMY["CM"] = $date_arr['CY_AND_CM'];
            $SetDMY["CFY"] = $date_arr['CFY'];

            $this->beanUi->set_view_data("getDate", $SetDMY);
            $this->beanUi->set_view_data("month_year", $month_year);
            $this->beanUi->set_view_data("fetchAllData", $dataArr);
        }
    }

    public function statistics_for_mrm_graph() {
        $action = $this->bean->get_request("_action");
        //---------------List--------------//
        $scoreID = $this->reportdao->getReportSettings(10, "id");
        $rowArray = explode(",", $scoreID[0]->param_value);
        $arr_dept = array();
        foreach ($rowArray as $k => $rr) {
            $trr = array();
            $clause = " id='" . $rr . "'";
            //-------------------------------All District----------------------------------------//
            $alldistrict = $this->reportdao->get_division_department($clause);
            $treeArr = $this->getParentTree($alldistrict[0]->parent_id);

            $arr_dept[$k]["dept_name"] = $alldistrict[0]->name;
            $arr_dept[$k]["dept_id"] = $alldistrict[0]->id;
            $arr_dept[$k]["tree_id"] = $treeArr . "-" . $alldistrict[0]->parent_id . "-" . $rr;
        }
        //---------------List--------------//       
        if ($action == "Create") {
            $set_rerurn_data = array();
            $set_statistics_data = array();
            $month_year = $this->bean->get_request("month_year_from");
            $tree_id = $this->bean->get_request("tree_id");
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            //show($date_arr);
            $set_statistics_data["PREV_YEAR"] = $this->dao->getAuditCountGraph($date_arr['PFY_QRY'], $tree_id);
            $set_statistics_data["CUR_YEAR"] = $this->dao->getAuditCountGraph($date_arr['CFY_QRY'], $tree_id, $month_basis = "YES");
            $set_statistics_data["CUR_YEAR_YTM"] = $this->dao->getAuditCountGraph($date_arr['CFY_YTM_QRY'], $tree_id);
            $this->beanUi->set_view_data("set_statistics_data", $set_statistics_data);
        }

        $set_rerurn_data['date'] = @$month_year;
        $set_rerurn_data['tree_id'] = @$tree_id;
        $this->beanUi->set_view_data("set_rerurn_data", $set_rerurn_data);
        $this->beanUi->set_view_data("allDepertment", $arr_dept);
    }

//    MIS-23
    public function cset_deptwise_setwise_auditscore() {

        $get_view_date = array();
        $month_year = $this->bean->get_request("month_year");
        $distID = $this->reportdao->getReportSettings(13, 'id');
        $rowArray = explode(",", $distID[0]->param_value);
        $distictname_arr = array();
        foreach ($rowArray as $key => $id) {
            if ($id == 5 || $id == 8) {
                @$treedata = array();
                @$clause = " id='" . $id . "'";
                @$treedata = $this->reportdao->get_division_department($clause);
                @$treename = $treedata[0]->name;
                @$distictname_arr[$key]->treeid = $id;
                @$distictname_arr[$key]->treename = $treename;
            }
        }

        if ($month_year != "") {
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            @$treeid = $this->bean->get_request("tree");
            @$clause = " id='" . $treeid . "'";
            @$treedata = $this->reportdao->get_division_department($clause);
            @$treename = $treedata[0]->name;
            @$treenameid = $treedata[0]->id;
            @$fetchArr = array();
            switch ($treeid) {
                case "5":
                    $clause = " parent_id='" . $treeid . "' AND id NOT IN (10)";
                    $treeArr = $this->reportdao->get_division_department($clause);
                    foreach ($treeArr as $key => $val) {
                        @$fetchArr[$key]->cellid = $treenameid;
                        @$fetchArr[$key]->cell = $treename;
                        @$fetchArr[$key]->districtid = $val->id;
                        @$fetchArr[$key]->district = $val->name;
                        @$clause1 = " parent_id='" . $val->id . "'";
                        @$treeArr1 = $this->reportdao->get_division_department($clause1);
                        foreach ($treeArr1 as $key1 => $val1) {
                            if ($val->id != 9) {
                                $table_name = $val1->table_name;
                                $exptbname = explode(",", $table_name);
                                if ($exptbname[0] == 'cset_contractor') {
                                    $fetchArr[$key]->tb = 'cset_contractor';
                                    $fetchArr[$key]->setarr = $this->activitydao->get_setvalue_mis($tb = 'cset_contractor', $id = $val1->id);
                                }
                            } else {
                                $clause2 = " parent_id='" . $val1->id . "'";
                                $treeArr2 = $this->reportdao->get_division_department($clause2);
                                foreach ($treeArr2 as $key2 => $val2) {
                                    $table_name = $val2->table_name;
                                    $exptbname = explode(",", $table_name);
                                    if ($exptbname[0] == 'cset_contractor') {
                                        $rrr = $this->activitydao->get_setvalue_mis($tb = 'cset_contractor', $id = $val2->id);
                                        $fetchArr[$key]->tb = 'cset_contractor';
                                        if (!empty($rrr)) {
                                            foreach ($rrr as $k1 => $v1) {
                                                $fetchArr[$key]->setarr[] = $v1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;
                case "8":
                    $clause = " parent_id='" . $treeid . "'";
                    $treeArr = $this->reportdao->get_division_department($clause);

                    foreach ($treeArr as $key => $val) {
                        if ($val->id == '141') {
                            $clause1 = " parent_id='" . $val->id . "'";
                            $treeArr1 = $this->reportdao->get_division_department($clause1);
                            foreach ($treeArr1 as $key1 => $val1) {
                                @$clause2 = " parent_id='" . $val1->id . "'";
                                @$treeArr2 = $this->reportdao->get_division_department($clause2);
                                @$fetchArr[$key]->cellid = $treenameid;
                                @$fetchArr[$key]->cell = $treename;
                                @$fetchArr[$key]->districtid = $val->id;
                                @$fetchArr[$key]->district = $val->name;
                                foreach ($treeArr2 as $key2 => $val2) {
                                    $table_name = $val2->table_name;

                                    $exptb = explode(",", $table_name);
                                    if ($exptb[0] == 'cset_contractor') {
                                        $fetchArr[$key]->tb = 'cset_contractor';
                                        $fetchArr[$key]->setarr = $this->activitydao->get_setvalue_mis($tb = 'cset_contractor', $id = $val2->id);
                                    }
                                }
                            }
                        } else if ($val->id == '145') {
                            @$clause1 = " parent_id='" . $val->id . "'";
                            @$treeArr1 = $this->reportdao->get_division_department($clause1);
                            @$fetchArr[$key]->cellid = $treenameid;
                            @$fetchArr[$key]->cell = $treename;
                            @$fetchArr[$key]->districtid = $val->id;
                            @$fetchArr[$key]->district = $val->name;
                            foreach ($treeArr1 as $key1 => $val1) {
                                $table_name = $val1->table_name;
                                $exptb = explode(",", $table_name);
                                if ($exptb[0] == 'cset_contractor') {
                                    $fetchArr[$key]->tb = 'cset_contractor';
                                    $fetchArr[$key]->setarr = $this->activitydao->get_setvalue_mis($tb = 'cset_contractor', $id = $val1->id);
                                }
                            }
                        } else {
                            @$fetchArr[$key]->cellid = $treenameid;
                            @$fetchArr[$key]->cell = $treename;
                            @$fetchArr[$key]->districtid = $val->id;
                            @$fetchArr[$key]->district = $val->name;
                            @$table_name = $val->table_name;
                            @$exptb = explode(",", $table_name);
                            if ($exptb[0] == 'cset_contractor') {
                                $fetchArr[$key]->tb = 'cset_contractor';
                                $fetchArr[$key]->setarr = $this->activitydao->get_setvalue_mis($tb = 'cset_contractor', $id = $val->id);
                            }
                        }
                    }
                    break;
            }
            $datearr["fy"] = $date_arr["CFY_FORMATTED"];
            $datearr["cm"] = $date_arr["CY_AND_CM"];
            $datearr["cytm"] = $date_arr["CFY_YTM"];
            $ex = explode(",", $date_arr["CFY_YTM"]);
            $ytm_range = " '$ex[0]' AND '$ex[1]'";
            @$finalArr = array();
            foreach ($fetchArr as $key => $value) {
                @$cellname = $value->cell;
                @$districtname = $value->district;
                @$setArr = $value->setarr;
                @$tablename = (($value->tb != '') ? $value->tb : 'division_department');

                foreach ($setArr as $key1 => $val1) {
                    @$pid = ($tablename == "division_department") ? $val1->parent_id : $val1->division_id;
                    @$treeids = (($tablename == 'division_department')) ? ($pid . '-' . $val1->id) : $pid . '-cset_contractor:' . $val1->id . ':id';
                    @$currentMonthRecArr = $this->dao->getAuditCountforDist('C-SET', 1, $datearr["cm"], $treeids);

                    @$finalArr[$key][$key1]["tb_name"] = $tablename . $treeids;
                    @$finalArr[$key][$key1]["p_id"] = ($tablename == "division_department") ? $val1->parent_id : $val1->division_id;
                    @$finalArr[$key][$key1]["set_id"] = $val1->id;
                    @$finalArr[$key][$key1]["set_name"] = $val1->name;
                    @$finalArr[$key][$key1]["icode"] = $val1->identification_code;
                    @$finalArr[$key][$key1]["code"] = $val1->code;
                    @$finalArr[$key][$key1]["district_name"] = $districtname;
                    @$finalArr[$key][$key1]["cell_name"] = $cellname;
                    @$finalArr[$key][$key1]["marks"] = $currentMonthRecArr[0]->avg_mark;
                    @$finalArr[$key][$key1]["dateofaudit"] = $currentMonthRecArr[0]->date_of_audit;
                    @$currentYtmRecArr = $this->dao->getAuditCountforDist('C-SET', 2, $ytm_range, $treeids);
                    $totalavg = 0;
                    $avgmark = 0;
                    if (!empty($currentYtmRecArr)) {
                        foreach ($currentYtmRecArr as $key3 => $val3) {
                            $avgmark += $val3->avg_mark ? $val3->avg_mark : 0;
                        }
                        $totalavg = $avgmark / count($currentYtmRecArr);
                    }
                    $finalArr[$key][$key1]["audit_count"] = count($currentYtmRecArr);
                    $finalArr[$key][$key1]["totalavg"] = round($totalavg, 2);
                }
            }
        }
        @$this->beanUi->set_view_data("districtName", $distictname_arr);
        @$this->beanUi->set_view_data("fetchArr", $finalArr);
        @$this->beanUi->set_view_data("datearr", $datearr);
    }

//    MIS-23(A)
    public function cset_deptwise_setwise_auditscore_MNTC() {

        $get_view_date = array();
        $month_year = $this->bean->get_request("month_year");
        $distID = $this->reportdao->getReportSettings(13, 'id');
        $rowArray = explode(",", $distID[0]->param_value);
        $distictname_arr = array();
        foreach ($rowArray as $key => $id) {
            if ($id == 5) {
                @$treedata = array();
                @$clause = " id='" . $id . "'";
                @$treedata = $this->reportdao->get_division_department($clause);
                @$treename = $treedata[0]->name;
                @$distictname_arr[$key]->treeid = $id;
                @$distictname_arr[$key]->treename = $treename;
            }
        }

        if ($month_year != "") {
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            @$treeid = $this->bean->get_request("tree");
            @$clause = " id='" . $treeid . "'";
            @$treedata = $this->reportdao->get_division_department($clause);
            @$treename = $treedata[0]->name;
            @$treenameid = $treedata[0]->id;
            @$fetchArr = array();

//                        $clause   = " parent_id='" . $treeid . "' AND id NOT IN (10)"; //show($treeid);
            $clause = " parent_id= 10 "; //show($treeid);
            $treeArr = $this->reportdao->get_division_department($clause);
            foreach ($treeArr as $key => $val) {
                @$fetchArr[$key]->cellid = $treenameid;
                @$fetchArr[$key]->cell = $treename;
                @$fetchArr[$key]->districtid = $val->id;
                @$fetchArr[$key]->district = $val->name;
                @$clause1 = " parent_id='" . $val->id . "'";
                @$treeArr1 = $this->reportdao->get_division_department($clause1);
                foreach ($treeArr1 as $key1 => $val1) {
//                                    if($val->id != 9) {
                    $table_name = $val1->table_name;
                    $exptbname = explode(",", $table_name);
                    if ($exptbname[0] == 'cset_contractor') {
                        $fetchArr[$key]->tb = 'cset_contractor';
                        $fetchArr[$key]->setarr = $this->activitydao->get_setvalue_mis($tb = 'cset_contractor', $id = $val1->id);
                    }
//                                    } 
//                                    else {
//                                        $clause2   = " parent_id='" . $val1->id . "'";  
//                                        $treeArr2 = $this->reportdao->get_division_department($clause2);
//                                        foreach( $treeArr2 as $key2 => $val2 ) {
//                                            $table_name = $val2->table_name;
//                                            $exptbname = explode(",",$table_name);
//                                            if($exptbname[0] == 'cset_contractor') {
//                                                $rrr = $this->activitydao->get_setvalue_mis($tb = 'cset_contractor',$id = $val2->id);
//                                                $fetchArr[$key]->tb  = 'cset_contractor';
//                                                if(!empty($rrr)) {
//                                                    foreach( $rrr as $k1 => $v1 ) {
//                                                        $fetchArr[$key]->setarr[] = $v1;
//                                                    }
//
//                                                }
//                                                 
//                                            }
//                                        }
//                                    }
                }
            }

            $datearr["fy"] = $date_arr["CFY_FORMATTED"];
            $datearr["cm"] = $date_arr["CY_AND_CM"];
            $datearr["cytm"] = $date_arr["CFY_YTM"];
            $ex = explode(",", $date_arr["CFY_YTM"]);
            $ytm_range = " '$ex[0]' AND '$ex[1]'";
            // show($fetchArr);
            @$finalArr = array();
            foreach ($fetchArr as $key => $value) {
                @$cellname = $value->cell;
                @$districtname = $value->district;
                @$setArr = $value->setarr;
                @$tablename = (($value->tb != '') ? $value->tb : 'division_department');

                foreach ($setArr as $key1 => $val1) {
                    @$pid = ($tablename == "division_department") ? $val1->parent_id : $val1->division_id;
                    @$treeids = (($tablename == 'division_department')) ? ($pid . '-' . $val1->id) : $pid . '-cset_contractor:' . $val1->id . ':id';
                    @$currentMonthRecArr = $this->dao->getAuditCountforDist('C-SET', 1, $datearr["cm"], $treeids);

                    @$finalArr[$key][$key1]["tb_name"] = $tablename . $treeids;
                    @$finalArr[$key][$key1]["p_id"] = ($tablename == "division_department") ? $val1->parent_id : $val1->division_id;
                    @$finalArr[$key][$key1]["set_id"] = $val1->id;
                    @$finalArr[$key][$key1]["set_name"] = $val1->name;
                    @$finalArr[$key][$key1]["icode"] = $val1->identification_code;
                    @$finalArr[$key][$key1]["code"] = $val1->code;
                    @$finalArr[$key][$key1]["district_name"] = $districtname;
                    @$finalArr[$key][$key1]["cell_name"] = $cellname;
                    @$finalArr[$key][$key1]["marks"] = $currentMonthRecArr[0]->avg_mark;
                    @$finalArr[$key][$key1]["dateofaudit"] = $currentMonthRecArr[0]->date_of_audit;
                    @$currentYtmRecArr = $this->dao->getAuditCountforDist('C-SET', 2, $ytm_range, $treeids);
                    $totalavg = 0;
                    $avgmark = 0;
                    if (!empty($currentYtmRecArr)) {
                        foreach ($currentYtmRecArr as $key3 => $val3) {
                            $avgmark += $val3->avg_mark ? $val3->avg_mark : 0;
                        }
                        $totalavg = $avgmark / count($currentYtmRecArr);
                    }
                    $finalArr[$key][$key1]["audit_count"] = count($currentYtmRecArr);
                    $finalArr[$key][$key1]["totalavg"] = round($totalavg, 2);
                }
            }
        }
        @$this->beanUi->set_view_data("districtName", $distictname_arr);
        @$this->beanUi->set_view_data("fetchArr", $finalArr);
        @$this->beanUi->set_view_data("datearr", $datearr);
    }

//    MIS-22    
    public function pset_deptwise_setwise_audit_score_gen() {
        //---------// START D-M-Y //--------//
        $get_view_date = array();
        $month_year = $this->bean->get_request("month_year");

        $distID = $this->reportdao->getReportSettings(13, 'id');
        $rowArray = explode(",", $distID[0]->param_value);
        $distictname_arr = array();
        foreach ($rowArray as $key => $id) {
            @$treedata = array();
            @$clause = " id='" . $id . "'";
            @$treedata = $this->reportdao->get_division_department($clause);
            @$treename = $treedata[0]->name;
            @$distictname_arr[$key]->treeid = $id;
            @$distictname_arr[$key]->treename = $treename;
        }

        $fetchArr = array();
        $date_arr = array();
        if ($month_year != "") {
            @$date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            @$treeid = $this->bean->get_request("tree");
            @$clause = " id='" . $treeid . "'";
            @$treedata = $this->reportdao->get_division_department($clause);
            @$treename = $treedata[0]->name;
            @$treenameid = $treedata[0]->id;
            @$fetchArr = array();
            switch ($treeid) {
                case "5":
                    //298 only for training,10 = for MNTC
                    $clause = " parent_id='" . $treeid . "' AND id NOT IN (10,298)";
                    $treeArr = $this->reportdao->get_division_department($clause);
                    foreach ($treeArr as $key => $val) {
                        @$fetchArr[$key]->cellid = $treenameid;
                        @$fetchArr[$key]->cell = $treename;
                        @$fetchArr[$key]->districtid = $val->id;
                        @$fetchArr[$key]->district = $val->name;

                        $clause1 = " parent_id='" . $val->id . "' AND id NOT IN (213) ";
                        $treeArr1 = $this->reportdao->get_division_department($clause1);

                        foreach ($treeArr1 as $key1 => $val1) {
                            if ($val->id != 9) {
                                $table_name = $val1->table_name;
                                $exptbname = explode(",", $table_name);

                                if ($exptbname[0] != 'cset_contractor') {
                                    $fetchArr[$key]->tb = $exptbname[0];
                                    $fetchArr[$key]->setarr[$exptbname[0]] = $this->activitydao->get_setvalue($tb = $exptbname[0], $id = $val1->id);
                                    $sd = $fetchArr[$key]->setarr[$exptbname[0]];
                                    if (!empty($sd)) {
                                        foreach ($sd as $kk => $vv) {
                                            $fetchArr[$key]->setarr[$exptbname[0]][$kk]->table_name = $exptbname[0];
                                        }
                                    }
                                }
                            } else {
                                $clause2 = " parent_id='" . $val1->id . "'";
                                $treeArr2 = $this->reportdao->get_division_department($clause2);
                                $fetchArr[$key]->tb = 'division_department';
                                foreach ($treeArr2 as $key2 => $val2) {
                                    $clause3 = " parent_id='" . $val2->id . "'";
                                    $treeArr3 = $this->reportdao->get_division_department($clause3);
                                    if (!empty($treeArr3)) {
                                        foreach ($treeArr3 as $key3 => $val3) {
                                            $fetchArr[$key]->setarr['divdept'][] = $val3;
                                            $fetchArr[$key]->setarr['divdept'][$key3]->table_name = 'division_department';
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;
                case "6":
                    $clause = " parent_id='" . $treeid . "'";
                    $treeArr = $this->reportdao->get_division_department($clause);
                    foreach ($treeArr as $key => $val) {
                        @$fetchArr[$key]->cellid = $treenameid;
                        @$fetchArr[$key]->cell = $treename;
                        @$fetchArr[$key]->districtid = $val->id;
                        @$fetchArr[$key]->district = $val->name;
                        @$fetchArr[$key]->tb = '';
                        @$fetchArr[$key]->setarr['divdept'][] = $val;
                    }

                    break;
                case "7":
                    $clause = " parent_id='" . $treeid . "' AND id!='80'";
                    $treeArr = $this->reportdao->get_division_department($clause);


                    foreach ($treeArr as $key => $val) {

                        $clause1 = " parent_id='" . $val->id . "'";
                        $treeArr1 = $this->reportdao->get_division_department($clause1);

                        foreach ($treeArr1 as $key1 => $val1) {
                            @$fetchArr[$key1]->cellid = $treenameid;
                            @$fetchArr[$key1]->cell = $treename;
                            @$fetchArr[$key1]->districtid = $val1->id;
                            @$fetchArr[$key1]->district = $val1->name;
                            @$fetchArr[$key1]->tb = '';
                            @$clause2 = " parent_id='" . $val1->id . "'";
                            @$treeArr2 = $this->reportdao->get_division_department($clause2); //show($treeArr2);
                            if (!empty($treeArr2)) {
                                $fetchArr[$key1]->setarr['divdept'] = $treeArr2;
                            } else {
                                $fetchArr[$key1]->setarr['divdept'][] = $val1;
                            }
                        }
                    }

                    break;
                case "8":
                    $clause = " parent_id='" . $treeid . "' AND id NOT IN(142,143,144)";
                    $treeArr = $this->reportdao->get_division_department($clause);
                    foreach ($treeArr as $key => $val) {
                        $clause1 = " parent_id='" . $val->id . "'";
                        $treeArr1 = $this->reportdao->get_division_department($clause1);
                        foreach ($treeArr1 as $key1 => $val1) {
                            if ($val1->id == '146') {
                                $clause3 = " parent_id='" . $val1->id . "'";
                                $treeArr3 = $this->reportdao->get_division_department($clause3);
                                foreach ($treeArr3 as $key3 => $val3) {
                                    $clause4 = " parent_id='" . $val3->id . "'";
                                    $treeArr4 = $this->reportdao->get_division_department($clause4);
                                    @$fetchArr[$key]->cellid = $treenameid;
                                    @$fetchArr[$key]->cell = $treename;
                                    @$fetchArr[$key]->districtid = $val->id;
                                    @$fetchArr[$key]->district = $val->name;
                                    @$fetchArr[$key]->tb = '';
                                    foreach ($treeArr4 as $kkk => $vvv) {
                                        $fetchArr[$key]->setarr['divdept'][$kkk] = $vvv;
                                    }
                                }
                            } else {
                                $clause3 = " parent_id='" . $val1->id . "'";
                                $treeArr3 = $this->reportdao->get_division_department($clause3);
                                foreach ($treeArr3 as $key3 => $val3) {
                                    @$fetchArr[$key]->cellid = $treenameid;
                                    @$fetchArr[$key]->cell = $treename;
                                    @$fetchArr[$key]->districtid = $val->id;
                                    @$fetchArr[$key]->district = $val->name;
                                    @$fetchArr[$key]->tb = '';
                                    @$fetchArr[$key]->setarr['divdept'] = $treeArr3;
                                }
                            }
                        }
                    }

                    break;
                case "168":
                    $clause = " parent_id='" . $treeid . "' ";
                    $treeArr = $this->reportdao->get_division_department($clause);

                    foreach ($treeArr as $key => $val) {
                        $clause1 = " parent_id='" . $val->id . "'";
                        $treeArr1 = $this->reportdao->get_division_department($clause1);
                        @$fetchArr[$key]->cellid = $treenameid;
                        @$fetchArr[$key]->cell = $treename;
                        @$fetchArr[$key]->districtid = $val->id;
                        @$fetchArr[$key]->district = $val->name;
                        @$fetchArr[$key]->tb = '';
                        @$fetchArr[$key]->setarr['divdept'] = $treeArr1;
                    }

                    break;
                default:
                    echo "exit";
            }

            $datearr["fy"] = $date_arr["CFY_FORMATTED"];
            $datearr["cm"] = $date_arr["CY_AND_CM"];
            $datearr["cytm"] = $date_arr["CFY_YTM"];
            $ex = explode(",", $date_arr["CFY_YTM"]);
            $ytm_range = " '$ex[0]' AND '$ex[1]'";

            $finalArr = array();
            foreach ($fetchArr as $key => $value) {
                $cellname = $value->cell;
                $districtname = $value->district;
                $setArr = $value->setarr;

                @$psetArr = $setArr['pset_no'];
                @$ccArr = $setArr['call_center_location'];
                @$sd = array_merge($setArr['pset_no'], $setArr['call_center_location']);
                @$divdeptArr = ($setArr['divdept']) ? $setArr['divdept'] : $sd;
                if (!empty($divdeptArr)) {
                    foreach ($divdeptArr as $k1 => $v1) {
                        $tablename = (($v1->table_name != '') ? $v1->table_name : 'division_department');

                        @$pid = ($tablename == "division_department") ? $v1->parent_id : $v1->division_id;
                        @$treeids = (($tablename == "division_department") ? ($pid . '-' . $v1->id) : ($pid . '-' . $tablename . ':' . $v1->id . ':id'));
                        @$currentMonthRecArr = $this->dao->getAuditCountforDist('P-SET', 1, $datearr["cm"], $treeids);
                        @$finalArr[$key][$k1]["tb_name"] = $tablename . $treeids;
                        @$finalArr[$key][$k1]["district_name"] = $districtname;
                        @$finalArr[$key][$k1]['cell_name'] = $cellname;
                        @$finalArr[$key][$k1]["set_id"] = $v1->id;
                        @$finalArr[$key][$k1]['set_name'] = $v1->name;
                        @$finalArr[$key][$k1]["marks"] = $currentMonthRecArr[0]->avg_mark;
                        @$finalArr[$key][$k1]["dateofaudit"] = $currentMonthRecArr[0]->date_of_audit;
                        @$currentYtmRecArr = $this->dao->getAuditCountforDist('P-SET', 2, $ytm_range, $treeids);
                        @$totalavg = 0;
                        @$avgmark = 0;
                        if (!empty($currentYtmRecArr)) {
                            foreach ($currentYtmRecArr as $key3 => $val3) {
                                $avgmark += $val3->avg_mark ? $val3->avg_mark : 0;
                            }
                            $totalavg = $avgmark / count($currentYtmRecArr);
                        }
                        @$finalArr[$key][$k1]["audit_count"] = count($currentYtmRecArr);
                        @$finalArr[$key][$k1]["totalavg"] = round($totalavg, 2);
                    }
                }
            }
        }

        @$this->beanUi->set_view_data("districtName", $distictname_arr);
        @$this->beanUi->set_view_data("fetchArr", array_values($finalArr));
        @$this->beanUi->set_view_data("datearr", $datearr);
    }

//        Report-10
    public function psetScoresAllDistributionWithTobBottomColourCoding() {
        //---------// START D-M-Y //--------//
        $get_view_date = array();
        $month_year = $this->bean->get_request("month_year");

        $distID = $this->reportdao->getReportSettings(13, 'id');
        $rowArray = explode(",", $distID[0]->param_value);
        $distictname_arr = array();
        foreach ($rowArray as $key => $id) {
            @$treedata = array();
            @$clause = " id='" . $id . "'";
            @$treedata = $this->reportdao->get_division_department($clause);
            @$treename = $treedata[0]->name;

            @$distictname_arr[$key]->treeid = $id;
            @$distictname_arr[$key]->treename = $treename;
        }
        $fetchArr = array();
        $date_arr = array();
        if ($month_year != "") {
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $treeid = $this->bean->get_request("tree");
            $clause = " id='" . $treeid . "'";
            $treedata = $this->reportdao->get_division_department($clause);
            $treename = $treedata[0]->name;
            $treenameid = $treedata[0]->id;
            $fetchArr = array();
            switch ($treeid) {
                case "5":
                    $clause = " parent_id='" . $treeid . "' AND id NOT IN (10)";
                    $treeArr = $this->reportdao->get_division_department($clause);
                    foreach ($treeArr as $key => $val) {
                        @$fetchArr[$key]->cellid = $treenameid;
                        @$fetchArr[$key]->cell = $treename;
                        @$fetchArr[$key]->districtid = $val->id;
                        @$fetchArr[$key]->district = $val->name;
                        @$clause1 = " parent_id='" . $val->id . "'";
                        @$treeArr1 = $this->reportdao->get_division_department($clause1);

                        foreach ($treeArr1 as $key1 => $val1) {
                            if ($val->id != 9) {
                                $table_name = $val1->table_name;
                                $exptbname = explode(",", $table_name);
                                if ($exptbname[0] == 'pset_no') {
                                    $fetchArr[$key]->tb = 'pset_no';
                                    $fetchArr[$key]->setarr = $this->activitydao->get_setvalue($tb = 'pset_no', $id = $val1->id);
                                }
                            } else {
                                $clause2 = " parent_id='" . $val1->id . "'";
                                $treeArr2 = $this->reportdao->get_division_department($clause2);
                                foreach ($treeArr2 as $key2 => $val2) {
                                    $clause3 = " parent_id='" . $val2->id . "'";
                                    $treeArr3 = $this->reportdao->get_division_department($clause3);
                                    if (!empty($treeArr3)) {
                                        foreach ($treeArr3 as $key3 => $val3) {
                                            $fetchArr[$key]->setarr[] = $val3;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;
                case "6":
                    $clause = " parent_id='" . $treeid . "'";
                    $treeArr = $this->reportdao->get_division_department($clause);
                    foreach ($treeArr as $key => $val) {
                        @$fetchArr[$key]->cellid = $treenameid;
                        @$fetchArr[$key]->cell = $treename;
                        @$fetchArr[$key]->districtid = $val->id;
                        @$fetchArr[$key]->district = $val->name;
                        @$fetchArr[$key]->setarr[] = $val;
                    }

                    break;
                case "7":
                    $clause = " parent_id='" . $treeid . "' AND id!='80'";
                    $treeArr = $this->reportdao->get_division_department($clause);


                    foreach ($treeArr as $key => $val) {

                        $clause1 = " parent_id='" . $val->id . "'";
                        $treeArr1 = $this->reportdao->get_division_department($clause1);

                        foreach ($treeArr1 as $key1 => $val1) {
                            @$fetchArr[$key1]->cellid = $treenameid;
                            @$fetchArr[$key1]->cell = $treename;
                            @$fetchArr[$key1]->districtid = $val1->id;
                            @$fetchArr[$key1]->district = $val1->name;
                            @$clause2 = " parent_id='" . $val1->id . "'";
                            @$treeArr2 = $this->reportdao->get_division_department($clause2); //show( @$treeArr2);
                            if (!empty($treeArr2)) {
                                $fetchArr[$key1]->setarr = $treeArr2;
                            } else {
                                $fetchArr[$key1]->setarr[] = $val1;
                            }
                        }
                    }
                    break;
                case "8":
                    $clause = " parent_id='" . $treeid . "' AND id NOT IN(142,143,144)";
                    $treeArr = $this->reportdao->get_division_department($clause);
                    foreach ($treeArr as $key => $val) {
                        $clause1 = " parent_id='" . $val->id . "'";
                        $treeArr1 = $this->reportdao->get_division_department($clause1);
                        foreach ($treeArr1 as $key1 => $val1) {
                            if ($val1->id == '146') {
                                $clause3 = " parent_id='" . $val1->id . "'";
                                $treeArr3 = $this->reportdao->get_division_department($clause3);
                                foreach ($treeArr3 as $key3 => $val3) {
                                    $clause4 = " parent_id='" . $val3->id . "'";
                                    $treeArr4 = $this->reportdao->get_division_department($clause4);
                                    @$fetchArr[$key]->cellid = $treenameid;
                                    @$fetchArr[$key]->cell = $treename;
                                    @$fetchArr[$key]->districtid = $val->id;
                                    @$fetchArr[$key]->district = $val->name;
                                    foreach ($treeArr4 as $kkk => $vvv) {
                                        $fetchArr[$key]->setarr[$kkk] = $vvv;
                                    }
                                }
                            } else {
                                $clause3 = " parent_id='" . $val1->id . "'";
                                $treeArr3 = $this->reportdao->get_division_department($clause3);
                                foreach ($treeArr3 as $key3 => $val3) {
                                    @$fetchArr[$key]->cellid = $treenameid;
                                    @$fetchArr[$key]->cell = $treename;
                                    @$fetchArr[$key]->districtid = $val->id;
                                    @$fetchArr[$key]->district = $val->name;
                                    @$fetchArr[$key]->setarr = $treeArr3;
                                }
                            }
                        }
                    }
                    break;
                case "168":
                    $clause = " parent_id='" . $treeid . "' ";
                    $treeArr = $this->reportdao->get_division_department($clause);

                    foreach ($treeArr as $key => $val) {
                        $clause1 = " parent_id='" . $val->id . "'";
                        $treeArr1 = $this->reportdao->get_division_department($clause1);
                        @$fetchArr[$key]->cellid = $treenameid;
                        @$fetchArr[$key]->cell = $treename;
                        @$fetchArr[$key]->districtid = $val->id;
                        @$fetchArr[$key]->district = $val->name;
                        @$fetchArr[$key]->setarr = $treeArr1;
                    }

                    break;
                default:
                    echo "exit";
            }


            $datearr["fy"] = $date_arr["CFY_FORMATTED"];
            $datearr["cm"] = $date_arr["CY_AND_CM"];
            $datearr["cytm"] = $date_arr["CFY_YTM"];

            $prevtwo = date("Y-m", strtotime("-2 Months", strtotime($date_arr["CY_AND_CM"])));
            $prevone = date("Y-m", strtotime("-1 Month", strtotime($date_arr["CY_AND_CM"])));

            $ex = explode(",", $date_arr["CFY_YTM"]);
            $ytm_range = " '$ex[0]' AND '$ex[1]'";
            $finalArr = array();
            foreach ($fetchArr as $key => $value) {
                @$cellname = $value->cell;
                @$districtname = $value->district;
                @$setArr = $value->setarr;
                @$tablename = (($value->tb != '') ? $value->tb : 'division_department');

                if (!empty($setArr)) {
                    foreach ($setArr as $key1 => $val1) {
                        $pid = ($tablename == "division_department") ? $val1->parent_id : $val1->division_id;
                        $treeids = (($tablename == "division_department") ? ($pid . '-' . $val1->id) : ($pid . '-pset_no:' . $val1->id . ':id'));
                        $currentMonthRecArr = $this->dao->getAuditCountforDist('P-SET', 1, $datearr["cm"], $treeids);
                        $prevoneMonthRecArr = $this->dao->getAuditCountforDist('P-SET', 1, $prevone, $treeids);
                        $prevtwoMonthRecArr = $this->dao->getAuditCountforDist('P-SET', 1, $prevtwo, $treeids);
                        $finalArr[$key][$key1]["tb_name"] = $tablename . $treeids;
                        $finalArr[$key][$key1]["p_id"] = ($tablename == "division_department") ? $val1->parent_id : $val1->division_id;
                        $finalArr[$key][$key1]["set_id"] = $val1->id;
                        $finalArr[$key][$key1]["set_name"] = $val1->name;
                        $finalArr[$key][$key1]["district_name"] = $districtname;
                        $finalArr[$key][$key1]["cell_name"] = $cellname;
                        $finalArr[$key][$key1]["current_month"] = $currentMonthRecArr;
                        $finalArr[$key][$key1]["prev_month_one"] = $prevoneMonthRecArr;
                        $finalArr[$key][$key1]["prev_month_two"] = $prevtwoMonthRecArr;
                    }
                }
            }
        }

        @$this->beanUi->set_view_data("districtName", $distictname_arr);
        @$this->beanUi->set_view_data("fetchArr", $finalArr);
        @$this->beanUi->set_view_data("datearr", $datearr);
    }

    public function psetCsetWorkmanSupervisor() {

        $_action = $this->bean->get_request("_action");
        $arr_date_send = array();
        $date_arr = array();
        if ($_action == "Create") {
            $month_year = $this->bean->get_request("month_year");
            $month_yearexp = explode("-", $month_year);
            $month_year_formatted = date("Y-m", strtotime("01-" . $month_year));
            ///////TYPE 1 ///////////
            $getMajorItem = $this->dao->getMajorItem(9);
            $arrData = array();
            $financialyearstart = getFinancialYearMonth($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastfinancialyear = getPrevFinancialYear(($month_yearexp[1] - 1), $month_yearexp[0], "Y");
            $current_financial_year = getFinancialYear($month_yearexp[1], $month_yearexp[0], $format = "Y");
            $lastFinancialMonth = date('Y-m', strtotime('-1 years', strtotime($month_year_formatted)));
            $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
            $prev_financial_year = $date_arr["PFY_FORMATTED"];
            $currytm = explode(",", $date_arr["CFY_YTM"]);
            $currentyrytm = " '" . $currytm[0] . "' AND '" . $currytm[1] . "' ";
            $stataionArr = array("BBGS", "SGS", "G", "D");
            $dataArr = array();
            if (!empty($stataionArr)) {
                foreach ($stataionArr as $key => $value) {
                    //For Training current month
                    $dataArr[$key]["station"] = (($value == 'BBGS') ? "BBGS" : (($value == 'SGS') ? "SGS" : (($value == 'G') ? "GENERATION TOTAL" : "DISTRIBUTION")));
//                    $dataArr[$key]["station"]                           =   (($value == 'G') ? "Generation" : "Distribtuion");
                    $dataArr[$key]["psetWorkmanMonthlyData"] = $this->dao->getTrainingDataCount(1, $value, 3, $month_year_formatted, 'W');
                    $dataArr[$key]["csetWorkmanMonthlyData"] = $this->dao->getTrainingDataCount(1, $value, 4, $month_year_formatted, 'W');
                    $dataArr[$key]["psetOfficerMonthlyData"] = $this->dao->getTrainingDataCount(1, $value, 5, $month_year_formatted, '');
                    $dataArr[$key]["psetSupervisorMonthlyData"] = $this->dao->getTrainingDataCount(1, $value, 3, $month_year_formatted, 'S');
                    $dataArr[$key]["csetSupervisorMonthlyData"] = $this->dao->getTrainingDataCount(1, $value, 4, $month_year_formatted, 'S');
                    $dataArr[$key]["psetytmWorkmanMonthlyData"] = $this->dao->getTrainingDataCount(2, $value, 3, $currentyrytm, 'W');
                    $dataArr[$key]["csetytmWorkmanMonthlyData"] = $this->dao->getTrainingDataCount(2, $value, 4, $currentyrytm, 'W');
                    $dataArr[$key]["psetytmOfficerMonthlyData"] = $this->dao->getTrainingDataCount(2, $value, 5, $currentyrytm, '');
                    $dataArr[$key]["psetytmSupervisorMonthlyData"] = $this->dao->getTrainingDataCount(2, $value, 3, $currentyrytm, 'S');
                    $dataArr[$key]["csetytmSupervisorMonthlyData"] = $this->dao->getTrainingDataCount(2, $value, 4, $currentyrytm, 'S');
                }
            }
            $arr_date_send["curr_month"] = $month_year;
            $arr_date_send["curr_year"] = $financialyearstart . "," . $month_year_formatted;
            $arr_date_send["last_month"] = $lastFinancialMonth;
            $arr_date_send["last_year"] = $lastfinancialyear;
        }
        @$this->beanUi->set_view_data("arr_date_send", $arr_date_send);
        @$this->beanUi->set_view_data("totaldata", $dataArr);
    }

    public function getCascadingTree() {
        $tree_val = $this->bean->get_request("tree_val");
        if ($tree_val != "") {
            $exp = explode("-", $tree_val);
            $lastnode = end($exp);
            $treeArr = array();
            $clause_1 = " parent_id='" . $lastnode . "'";
            $treedata_1 = $this->reportdao->get_division_department($clause_1);
            if (!empty($treedata_1)) {
                foreach ($treedata_1 as $k1 => $v1) {
                    $treeArr[$k1]["id"] = $v1->id;
                    $treeArr[$k1]["parent_id"] = $v1->parent_id;
                    $treeArr[$k1]["name"] = $v1->name;
                    $clause_2 = " parent_id='" . $v1->id . "' ";
                    $treedata_2 = $this->reportdao->get_division_department($clause_2);
                    if (!empty($treedata_2)) {
                        foreach ($treedata_2 as $k2 => $v2) {
                            if ($v2->name != 'C-SET' && $v2->name != 'Call Center') {
                                $treeArr[$k1]["ischild"][$k2]["id"] = $v2->id;
                                $treeArr[$k1]["ischild"][$k2]["parent_id"] = $v2->parent_id;
                                $treeArr[$k1]["ischild"][$k2]["name"] = $v1->name . '/' . $v2->name;
                            }
                            $clause_3 = " parent_id='" . $v2->id . "' ";
                            $treedata_3 = $this->reportdao->get_division_department($clause_3);
                            if (!empty($treedata_3)) {
                                foreach ($treedata_3 as $k3 => $v3) {
                                    if ($v3->name != 'C-SET' && $v3->name != 'Call Center') {
                                        $treeArr[$k1]["ischild"][$k2]["subchild"][$k3]["id"] = $v3->id;
                                        $treeArr[$k1]["ischild"][$k2]["subchild"][$k3]["parent_id"] = $v3->parent_id;
                                        $treeArr[$k1]["ischild"][$k2]["subchild"][$k3]["name"] = $v1->name . '/' . $v2->name . '/' . $v3->name . '/';
                                    }
                                }
                            }
                        }
                    }
                }
            }
            echo json_encode($treeArr);
            die;
        }
    }

    public function surakshabarta_siteaudit() {
        $_action = $this->bean->get_request("_action");
        $arr_date_send = array();
        $date_arr = array();
        if ($_action == "submitData") {
            $selected_year = $this->bean->get_request("selected_year");
            $quarter_value = $this->bean->get_request("quarter_value");
            switch ($quarter_value) {
                case '4':
                    $d1 = date(($selected_year + 1) . '-01-01');
                    $d2 = date(($selected_year + 1) . '-03-31');
                    $d3 = date(($selected_year) . '-01-01');
                    $d4 = date(($selected_year) . '-03-31');
                    $month_array = array($d1, $d2);
                    break;
                case '1':
                    $d1 = date($selected_year . '-04-01');
                    $d2 = date($selected_year . '-06-30');
                    $d3 = date(($selected_year - 1) . '-04-01');
                    $d4 = date(($selected_year - 1) . '-06-30');
                    $month_array = array($d1, $d2);
                    break;
                case '2':
                    $d1 = date($selected_year . '-07-01');
                    $d2 = date($selected_year . '-09-30');
                    $d3 = date(($selected_year - 1) . '-07-01');
                    $d4 = date(($selected_year - 1) . '-09-30');
                    break;
                case '3':
                    $d1 = date($selected_year . '-10-01');
                    $d2 = date($selected_year . '-12-31');
                    $d3 = date(($selected_year - 1) . '-10-01');
                    $d4 = date(($selected_year - 1) . '-12-31');
                    break;
                default:
                    exit;
            }
            @$daterange_for_current_year = "'$d1' AND '$d2'";
            @$daterange_for_prev_year = "'$d3' AND '$d4'";
            @$PPEscoreID = $this->reportdao->getReportSettings(15, "id");
            @$rowArray = explode(",", $PPEscoreID[0]->param_value);
            $getSiteAuditPsetData = array();
            $getSiteAuditCsetData = array();
            foreach ($rowArray as $k => $id) {
                $clause = " id='" . $id . "'";
                $district = $this->reportdao->get_division_department($clause);
                if ($id != 10) {
                    @$getSiteAuditPsetData[$k]["district"] = (array) $district[0];
                    @$getSiteAuditPsetData[$k]["pset_unit"] = $this->reportdao->division_child_unit_count($id, 'P-SET');
                    @$getSiteAuditPsetData[$k]["pset_prev"] = $this->dao->suraksha_barta_site_audit('P-SET', $daterange_for_prev_year, $id);
                    @$getSiteAuditPsetData[$k]["pset_current"] = $this->dao->suraksha_barta_site_audit('P-SET', $daterange_for_current_year, $id);
                }
                if ($id != 168 && $id != 7 && $id != 6) {
                    @$getSiteAuditCsetData[$k]["district"] = (array) $district[0];
                    @$getSiteAuditCsetData[$k]["cset_unit"] = $this->reportdao->division_child_unit_count($id, 'C-SET');
                    @$getSiteAuditCsetData[$k]["cset_prev"] = $this->dao->suraksha_barta_site_audit('C-SET', $daterange_for_prev_year, $id);
                    @$getSiteAuditCsetData[$k]["cset_current"] = $this->dao->suraksha_barta_site_audit('C-SET', $daterange_for_current_year, $id);
                }
            }
        }
        @$date_arr["CURR_Y"] = $selected_year . '-' . ($selected_year + 1);
        @$date_arr["PREV_Y"] = ($selected_year - 1) . '-' . ($selected_year);
        @$this->beanUi->set_view_data("arr_pset_data", $getSiteAuditPsetData);
        @$this->beanUi->set_view_data("arr_cset_data", $getSiteAuditCsetData);
        @$this->beanUi->set_view_data("getDate", $date_arr);
    }

    public function surakshabarta_incident() {
        $_action = $this->bean->get_request("_action");
        $arr_date_send = array();
        $date_arr = array();
        if ($_action == "submitData") {
            $selected_year = $this->bean->get_request("selected_year");
            $quarter_value = $this->bean->get_request("quarter_value");
            switch ($quarter_value) {
                case '4':
                    $d1 = date(($selected_year + 1) . '-01-01');
                    $d2 = date(($selected_year + 1) . '-03-31');
                    $d3 = date(($selected_year) . '-01-01');
                    $d4 = date(($selected_year) . '-03-31');
                    $month_array = array($d1, $d2);
                    break;
                case '1':
                    $d1 = date($selected_year . '-04-01');
                    $d2 = date($selected_year . '-06-30');
                    $d3 = date(($selected_year - 1) . '-04-01');
                    $d4 = date(($selected_year - 1) . '-06-30');
                    $month_array = array($d1, $d2);
                    break;
                case '2':
                    $d1 = date($selected_year . '-07-01');
                    $d2 = date($selected_year . '-09-30');
                    $d3 = date(($selected_year - 1) . '-07-01');
                    $d4 = date(($selected_year - 1) . '-09-30');
                    break;
                case '3':
                    $d1 = date($selected_year . '-10-01');
                    $d2 = date($selected_year . '-12-31');
                    $d3 = date(($selected_year - 1) . '-10-01');
                    $d4 = date(($selected_year - 1) . '-12-31');
                    break;
                default:
                    exit;
            }
            @$daterange_for_current_year = "'$d1' AND '$d2'";
            @$daterange_for_prev_year = "'$d3' AND '$d4'";
            @$PPEscoreID = $this->reportdao->getReportSettings(16, "id");
            @$rowArray = explode(",", $PPEscoreID[0]->param_value);
            @$incident_category_id = $this->reportdao->get_incident_category();
            $getSiteAuditPsetData = array();
            $getSiteAuditCsetData = array();
            foreach ($rowArray as $k => $id) {
                $clause = " id='" . $id . "'";
                $val1 = $this->reportdao->get_division_department($clause);
                $REPOTED_ACC_STAT_PSET[$k]["district"] = $val1[0]->name;
                $REPOTED_ACC_STAT_CSET[$k]["district"] = $val1[0]->name;
                $REPOTED_ACC_STAT_PSET[$k]["district_id"] = $val1[0]->id;
                $REPOTED_ACC_STAT_CSET[$k]["district_id"] = $val1[0]->id;

                foreach ($incident_category_id as $key => $incCat) {
                    if ($incCat->id != 1) {
                        @$REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["NAME"] = $incCat->name;
                        @$REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["ID"] = $incCat->id;
                        @$REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["PREV_YEAR1"] = $this->dao->suraksha_barta_incident("P-SET", $daterange_for_prev_year, $incCat->id, $id);
                        @$REPOTED_ACC_STAT_PSET[$k]["INCCAT"][$key]["CURRENT_YEAR"] = $this->dao->suraksha_barta_incident("P-SET", $daterange_for_current_year, $incCat->id, $id);
                    }
                }
                foreach ($incident_category_id as $key => $incCat) {
                    if ($incCat->id != 1) {
                        @$REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["NAME"] = $incCat->name;
                        @$REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["ID"] = $incCat->id;
                        @$REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["PREV_YEAR1"] = $this->dao->suraksha_barta_incident("C-SET", $daterange_for_prev_year, $incCat->id, $id);
                        @$REPOTED_ACC_STAT_CSET[$k]["INCCAT"][$key]["CURRENT_YEAR"] = $this->dao->suraksha_barta_incident("C-SET", $daterange_for_current_year, $incCat->id, $id);
                    }
                }
            }
        }
        @$date_arr["CURR_Y"] = $selected_year . '-' . ($selected_year + 1);
        @$date_arr["PREV_Y"] = ($selected_year - 1) . '-' . ($selected_year);
        @$this->beanUi->set_view_data("REPOTED_ACC_STAT_PSET", $REPOTED_ACC_STAT_PSET);
        @$this->beanUi->set_view_data("REPOTED_ACC_STAT_CSET", $REPOTED_ACC_STAT_CSET);
        $this->beanUi->set_view_data("getDate", $date_arr);
    }

//    MIS-28
    public function district_wise_month_wise_accident_statistics() {
        $scoreID = $this->reportdao->getReportSettings(17, "id");
        $rowArray = explode(",", $scoreID[0]->param_value);
        $incident_category_id = $this->reportdao->get_incident_category();
        $month_year = date("m-Y");
        $date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);

        $action = $this->bean->get_request("_action");
        $dataarr = array();
        if ($action == "submitData") {
            $current_fy_year = $this->bean->get_request("selected_year");
            $current_fy_month_arr = getAllMonthYear($current_fy_year);

            $current_fy_year_exp = explode("-", $current_fy_year);
            $current_fy_year_exp_F = $current_fy_year_exp[0] . "-04-01";
            $current_fy_year_exp_L = $current_fy_year_exp[1] . "-03-31";
            $curr_year = "'$current_fy_year_exp_F'  AND  '$current_fy_year_exp_L'";

            $prev_fy_year_exp_F = ($current_fy_year_exp[0] - 1);
            $prev_fy_year_exp_L = ($current_fy_year_exp[1] - 1);
            $prev_fy_year_exp_FF = $prev_fy_year_exp_F . "-04-01";
            $prev_fy_year_exp_LL = $prev_fy_year_exp_L . "-03-31";
            $prev_year = "'$prev_fy_year_exp_FF'  AND  '$prev_fy_year_exp_LL'";         
            foreach ($rowArray as $k => $id) {
                $clause = " id='" . $id . "'";
                $val1 = $this->reportdao->get_division_department($clause);

                $dataarr[$k]["DISTRICT_ID"] = $id;
                $dataarr[$k]["DISTRICT"] = $val1[0]->name;


                if (!empty($current_fy_month_arr)) {
                    foreach ($current_fy_month_arr as $key => $value) {
                        $getmonthname = strtoupper(date('M', strtotime($value . '-01')));
                        if (!empty($incident_category_id)) {
                            foreach ($incident_category_id as $inck => $incvalue) {
                                $dataarr[$k][$getmonthname][$incvalue->id]['NAME'] = $incvalue->name;
                                $dataarr[$k][$getmonthname][$incvalue->id]['TOT_COUNT'] = $this->dao->get_incident_monthwise_statistics(1, $value, $incvalue->id, $id);
                            }
                        }
                    }
                }
                if (!empty($incident_category_id)) {
                    foreach ($incident_category_id as $inck => $incvalue) {
                        $dataarr[$k]['YTM_CURR_YEAR'][$incvalue->id]['NAME'] = $incvalue->name;
                        $dataarr[$k]['YTM_CURR_YEAR'][$incvalue->id]['TOT_COUNT'] = $this->dao->get_incident_monthwise_statistics(2, $curr_year, $incvalue->id, $id);
                        $dataarr[$k]['YTM_PREV_YEAR'][$incvalue->id]['NAME'] = $incvalue->name;
                        $dataarr[$k]['YTM_PREV_YEAR'][$incvalue->id]['TOT_COUNT'] = $this->dao->get_incident_monthwise_statistics(2, $prev_year, $incvalue->id, $id);
                    }
                }
            }
        }

        @$SetDMY["month_year"] = $month_year;
        @$SetDMY["prevYear"] = $date_arr['PFY_QRY'];
        @$SetDMY["nextYear"] = $date_arr['CFY_QRY'];
        @$this->beanUi->set_view_data("getDate", $SetDMY);
        @$this->beanUi->set_view_data("current_fy_month_arr", $current_fy_month_arr);
        @$this->beanUi->set_view_data("incidentStatics", $dataarr);
        @$this->beanUi->set_view_data("incident_category_id", $incident_category_id);
        @$this->beanUi->set_view_data("current_fy_year", $current_fy_year);
    }

//     MIS-30
    public function district_wise_month_wise_activity_statistics() {
        @$scoreID = $this->reportdao->getReportSettings(17, "id");
        @$rowArray = explode(",", $scoreID[0]->param_value);
        @$activity_category_id = $this->reportdao->get_activity_category(); //show($activity_category_id);
        @$month_year = date("m-Y");
        @$date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);


        $action = $this->bean->get_request("_action");
        $dataarr = array();
        if ($action == "submitData") {
            $current_fy_year = $this->bean->get_request("selected_year");
            $current_fy_month_arr = getAllMonthYear($current_fy_year);

            $current_fy_year_exp = explode("-", $current_fy_year);
            $current_fy_year_exp_F = $current_fy_year_exp[0] . "-04-01";
            $current_fy_year_exp_L = $current_fy_year_exp[1] . "-03-31";
            $curr_year = "'$current_fy_year_exp_F'  AND  '$current_fy_year_exp_L'";

            $prev_fy_year_exp_F = ($current_fy_year_exp[0] - 1);
            $prev_fy_year_exp_L = ($current_fy_year_exp[1] - 1);
            $prev_fy_year_exp_FF = $prev_fy_year_exp_F . "-04-01";
            $prev_fy_year_exp_LL = $prev_fy_year_exp_L . "-03-31";
            $prev_year = "'$prev_fy_year_exp_FF'  AND  '$prev_fy_year_exp_LL'";
       
            foreach ($rowArray as $k => $id) {
                //  if($id == 4) {
                $clause = " id='" . $id . "'";

                $alldistrict = $this->reportdao->get_division_department($clause);

                $treeArr = $this->getParentTree($alldistrict[0]->parent_id);

                $tt = ($treeArr != 1) ? $treeArr . "-" : "";

                $tree_id = $tt . $alldistrict[0]->parent_id . "-" . $id;

                $dataarr[$k]["DISTRICT_ID"] = $id;
                $dataarr[$k]["DISTRICT"] = $alldistrict[0]->name;


                if (!empty($current_fy_month_arr)) {
                    foreach ($current_fy_month_arr as $key => $value) {
                        $getmonthname = strtoupper(date('M', strtotime($value . '-01')));
                        if (!empty($activity_category_id)) {
                            foreach ($activity_category_id as $inck => $actvalue) {//show($actvalue);
                                $dataarr[$k][$getmonthname][$actvalue->id]['NAME'] = $actvalue->activity_name;
                                $dataarr[$k][$getmonthname][$actvalue->id]['TOT_COUNT'] = $this->dao->get_activity_monthwise_statistics(1, $value, $actvalue->id, $tree_id, $id);
                            }
                        }
                    }
                }
                if (!empty($activity_category_id)) {
                    foreach ($activity_category_id as $inck => $actvalue) {
                        $dataarr[$k]['YTM_CURR_YEAR'][$actvalue->id]['NAME'] = $actvalue->activity_name;
                        $dataarr[$k]['YTM_CURR_YEAR'][$actvalue->id]['TOT_COUNT'] = $this->dao->get_activity_monthwise_statistics(2, $curr_year, $actvalue->id, $tree_id, $id);
                        $dataarr[$k]['YTM_PREV_YEAR'][$actvalue->id]['NAME'] = $actvalue->activity_name;
                        $dataarr[$k]['YTM_PREV_YEAR'][$actvalue->id]['TOT_COUNT'] = $this->dao->get_activity_monthwise_statistics(2, $prev_year, $actvalue->id, $tree_id, $id);
                    }
                }

                // } 
            }
        }

        @$SetDMY["month_year"] = $month_year;
        @$SetDMY["prevYear"] = $date_arr['PFY_QRY'];
        @$SetDMY["nextYear"] = $date_arr['CFY_QRY'];
        @$this->beanUi->set_view_data("getDate", $SetDMY);
        @$this->beanUi->set_view_data("current_fy_month_arr", $current_fy_month_arr);
        @$this->beanUi->set_view_data("activityStatics", $dataarr);
        @$this->beanUi->set_view_data("activity_category_id", $activity_category_id);
    }

    public function accident_statistics_gendist() {
        @$date_arr = $this->get_multiple_finalcial_year_and_month_date($month_year);
        @$incident_category_id = $this->reportdao->get_incident_category();
        @$current_fy_year = $date_arr["CFY_FORMATTED"];
        @$current_fy_month_arr = getAllMonthYear($current_fy_year);
        @$dataArr = array();
        if (!empty($current_fy_month_arr)) {
            foreach ($current_fy_month_arr as $key => $value) {
                $dataArr[$key]["month"] = $value;
                if (!empty($incident_category_id)) {
                    foreach ($incident_category_id as $inckey => $incvalue) {
                        @$dataArr[$key]["incdata"][$incvalue->id]["pset"] = $this->dao->get_incident_accident_statistics_gendist($value, $incvalue->id, 'P-SET');
                        @$dataArr[$key]["incdata"][$incvalue->id]["cset"] = $this->dao->get_incident_accident_statistics_gendist($value, $incvalue->id, 'C-SET');
                    }
                }
            }
        }
        @$this->beanUi->set_view_data("dataArr", $dataArr);
        @$this->beanUi->set_view_data("incident_category_id", array_reverse($incident_category_id));
    }
    
   
    public function audit_report_dist_pset() {
        //---------// START D-M-Y //--------//
        $get_view_date = array();
        $month_year = $this->bean->get_request("month_year");

        $distID = $this->reportdao->getReportSettings(18, 'id');
        $rowArray = explode(",", $distID[0]->param_value);
        $distictname_arr = array();
        foreach ($rowArray as $key => $id) {
            @$treedata = array();
            @$clause = " id='" . $id . "'";
            @$treedata = $this->reportdao->get_division_department($clause);
            @$treename = $treedata[0]->name;
            @$distictname_arr[$key]->treeid = $id;
            @$distictname_arr[$key]->treename = $treename;
        }

        

        @$this->beanUi->set_view_data("districtName", $distictname_arr);
        @$this->beanUi->set_view_data("fetchArr", array_values($finalArr));
        @$this->beanUi->set_view_data("datearr", $datearr);
    }

}
