<?php

class LibraryController extends MainController {

    public function __construct() {
        $this->dao = load_dao("LibraryMasterDAO");

        parent::__construct();
    }

    private function get_breadcumb($cat_name = "") {
        $catid = $this->bean->get_request('catid');
        $all_categories = $this->beanUi->get_view_data("all_categories");
        $digital_library_categories = isset($all_categories["$cat_name"]) ? $all_categories["$cat_name"] : array();

        $path = "";
        if (!empty($digital_library_categories) && $catid) {
            foreach ($digital_library_categories as $row) {
                if ($row->id == $catid) {
                    $path = $row->path;
                    break;
                }
            }
        }

        return ( $path != "" ) ? str_replace('/', " <span class=\"fa fa-caret-right\"></span> ", $path) : '';
    }

    public function safetyLibrary() {
        $allLibrary = $this->dao->getLibraryCategories();

        $keyword = $this->bean->get_request("keyword");
        $status_id = $this->bean->get_request("status_id");
        $cat_id = $this->bean->get_request("cat_id");
        $page_type = $this->bean->get_request("page_type");
        $page_type = ($page_type == "") ? "buyers_guide" : $page_type;
        $url_suffix = "page_type=" . htmlspecialchars($page_type, ENT_QUOTES, 'UTF-8') . "&cat_id=" . htmlspecialchars($cat_id, ENT_QUOTES, 'UTF-8') . "&status_id=" . htmlspecialchars($status_id, ENT_QUOTES, 'UTF-8') . "&keyword=" . htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');

        $clause = "status_id = 3 AND category_deleted = 0";
        if ($keyword != "")
            $clause .= " AND title LIKE '%" . $keyword . "%'";
        if ($status_id > 0)
            $clause .= " AND status_id = " . $status_id;
        if ($cat_id > 0)
            $clause .= " AND category_id = " . $cat_id;
        $clause .= " ORDER BY modified_date DESC";
        $limit = PAGE_LIMIT;
        $paggin_html = "";
        $rows = array();
        $this->dao->pagging->page_type = $page_type;
        $rows = $this->dao->get_buyers_guides_with_pagging($clause, $limit, $this->get_auth_user());
        $paggin_html = getPageHtml($this->dao, $url_suffix);

        $categories = $this->dao->get_categories();
        $this->beanUi->set_view_data("cat_id", $cat_id);
        $this->beanUi->set_view_data("categories", $categories);
        $this->beanUi->set_view_data("page", $this->bean->get_request("page"));
        $this->beanUi->set_view_data("rows", $rows);
        $this->beanUi->set_view_data("paggin_html", $paggin_html);
        $this->beanUi->set_view_data("master_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("keyword", $this->bean->get_request("keyword"));
        $this->beanUi->set_view_data("status_id", $this->bean->get_request("status_id"));

        $this->beanUi->set_view_data("allLibrary", $allLibrary);
    }

    public function locationSearch() {
// *************************************Start No. of incident investigation done where findings addressed************************* 
        $from_date1 = $this->bean->get_request("from_date");
        $to_date1 = $this->bean->get_request("to_date");
        $selectval = $this->bean->get_request("selectval"); //show($selectval);

        $pVal["tree_division_id"] = $selectval;
        $pVal["incident_id"] = 0;
        $pVal["date_of_incident"] = $from_date1;
        $pVal["date_of_incident1"] = $to_date1;

        $caluseforaddr = " AND date_of_incident BETWEEN :date_of_incident AND :date_of_incident1";
        $incdata = $this->dao->get_inc_addressed($caluseforaddr, $pVal);
        $incArr = array();
        foreach ($incdata as $key => $value) {
            $incid = $value->incident_id;
            $incArr[$incid]["ID"] = $incid;
            $incArr[$incid]["TOTAL"] = $this->dao->get_inc_details_by_id($incid);
            $incArr[$incid]["TOTAL_" . $value->action_taken] = $value->totalcount;
            $new_width = ( $incArr[$incid]["TOTAL_" . $value->action_taken] / $incArr[$incid]["TOTAL"]) * 100;
            $incArr[$incid]["PER"] = ($value->action_taken == 'Y') ? round($new_width) : 0;
        }
        $incfinalarr = array_values($incArr);
        $percentageArr = array("1" => array("alias" => "80 % - 100 %", "minvalue" => "80", "maxvalue" => "100"),
            "2" => array("alias" => "50 % - 79 %", "minvalue" => "50", "maxvalue" => "79"),
            "3" => array("alias" => "1 % - 49 %", "minvalue" => "1", "maxvalue" => "49"),
            "4" => array("alias" => "0 %", "maxvalue" => "0"));
        $finalArr = array();

        foreach ($percentageArr as $perkey => $pvalue) {
            $inccountvalue = 0;
            foreach ($incfinalarr as $inckey => $incvalue) {
                $incpercentage = $incvalue["PER"];
                if ($incpercentage >= $pvalue["minvalue"] && $incpercentage <= $pvalue["maxvalue"]) {
                    $inccountvalue += 1;
                }
            }
            $finalArr[$perkey] = $pvalue;
            $finalArr[$perkey]["perincvalue"] = $inccountvalue;
        }
        $this->beanUi->set_view_data("from_date1", $from_date1);
        $this->beanUi->set_view_data("to_date1", $to_date1);
        $this->beanUi->set_view_data("incidentaddresseddata", $finalArr);
// *************************************End No. of incident investigation done where findings addressed*************************
// *************************************Start No. of incident investigation pending*************************
        $fromdate1 = date("Y-m-d", strtotime("-4 Years"));
        $todate1 = date("Y-m-d");

        $caluseforaddr = " AND date_of_incident BETWEEN '$fromdate1' AND '$todate1'";
        $incdataPending = $this->dao->get_inc_inv_pending($caluseforaddr, $selectval); //show($incdataPending);
        $incArr = array();
        $belowThreeMonth = 0;
        $betweenThreeTwelve = 0;
        $twelveAndMore = 0;
        foreach ($incdataPending as $key => $value) {
            $incid = $value->id;
            $CurrDate = date_create(date("Y-m-d"));
            $DataDate = date_create($value->date_of_incident);

            $diff = date_diff($CurrDate, $DataDate);
            $NoOfDays = $diff->format("%a days");
            $NoOfMonth = $NoOfDays / 30;

            if ($NoOfMonth < 3) {
                $belowThreeMonth++;
            }
            if ($NoOfMonth > 3 && $NoOfMonth < 12) {
                $betweenThreeTwelve++;
            }
            if ($NoOfMonth > 12) {
                $twelveAndMore++;
            }
        }

        $this->beanUi->set_view_data("belowThreeMonth", $belowThreeMonth);
        $this->beanUi->set_view_data("betweenThreeTwelve", $betweenThreeTwelve);
        $this->beanUi->set_view_data("twelveAndMore", $twelveAndMore);
        $this->beanUi->set_view_data("incidentaddresseddataPending", $finalArrPending);
// *************************************End No. of incident investigation pending*************************
        // show($finalArr);
        $mapingtable = array("1" => "activity_division_mapping",
            "2" => "activity_division_mapping",
            "3" => "activity_division_mapping",
            "4" => "activity_division_mapping",
            "5" => "audit_division_mapping",
            "6" => "incident_division_mapping",
            "7" => "ppe_audit_division_mapping",
            "8" => "safety_observation_division_mapping");
        $table = array("1" => "activity_view",
            "2" => "activity_view",
            "3" => "activity_view",
            "4" => "activity_view",
            "5" => "audit_view",
            "6" => "incident_view",
            "7" => "ppe_audit_view",
            "8" => "safety_observation_view");

        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());

        $search = $this->bean->get_request("search");
        $from_date = $this->bean->get_request("from_date");
        $to_date = $this->bean->get_request("to_date");
        $jobstop = $this->bean->get_request("jobstop");
        $peningivs = $this->bean->get_request("peningivs");
        $pendingAction = $this->bean->get_request("pendingAction");



        /*         * tree name get by id* */
        $post_division_department = $this->dao->get_division_department();
        $devition_name = array();
        if ($selectval != "") {
            $tree_division_id_arr = ($selectval != "") ? explode("-", $selectval) : array();
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
                        $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                        $divition .= !empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                    }
                    $divition .= '/';
                }
            }
            $devition_name[] = trim($divition, "/");
        }
        $this->beanUi->set_view_data("devition_names", $devition_name);
        /*         * ******* */

        $activityId = ($this->bean->get_request("activity") != "" ? $this->bean->get_request("activity") : "");

        $getalldata = array();
        if ($search) {
            /*             * Get id from mapping table* */
            $allIds = $this->dao->getIdsfrommappingtable($selectval, $mapingtable['1'], "activity_id");
            $allIds5 = $this->dao->getIdsfrommappingtable($selectval, $mapingtable['5'], "audit_id");
            $allIds6 = $this->dao->getIdsfrommappingtable($selectval, $mapingtable['6'], "incident_id");
            $allIds7 = $this->dao->getIdsfrommappingtable($selectval, $mapingtable['7'], "ppe_audit_id");
            $allIds8 = $this->dao->getIdsfrommappingtable($selectval, $mapingtable['8'], "safety_observation_id");
//             for audit 1 to 4 id
            foreach ($allIds as $key => $value) {
                @$invalue .= $value->activity_id . ",";
            }
            $finalquerypart = trim(@$invalue, ',');
//            end audit table 1 to 4
//             for siteaudit id 5
            foreach ($allIds5 as $key5 => $value5) {
                @$siteaudvalue .= $value5->audit_id . ",";
            }
            @$siteaudit = trim($siteaudvalue, ',');
//            end siteaudit id 5
//             for incidenty id 6

            foreach ($allIds6 as $key6 => $value6) {
                @$incidentvalue .= $value6->incident_id . ",";
            }
            @$incidentaudit = trim($incidentvalue, ',');
//            end siteaudit id 6
//             for ppeaudit id 7
            foreach ($allIds7 as $key7 => $value7) {
                @$ppevalue .= $value7->ppe_audit_id . ",";
            }
            @$ppeaudit = trim($ppevalue, ',');

//            end ppeaudit id 7
//             for safetyudit id 8
            foreach ($allIds8 as $key8 => $value8) {
                @$safevalue .= $value8->safety_observation_id . ",";
            }
            @$safeaudit = trim($safevalue, ',');
//            end safetyaudit id 8
        }
        /*         * Get all data as per search selection from mapping table* */
        @$fromaudittable = $this->dao->getalldatafromview($finalquerypart, $from_date, $to_date, $job = null, $peninginvestigation = null, $paction = null, "activity_date", $activityId, $table['1'], "activity_type_id");
        if ($jobstop != "") {
            @$siteauditval = $this->dao->getalldatafromview($siteaudit, $from_date, $to_date, $job = $jobstop, $peninginvestigation = null, $paction = null, "date_of_audit", $activityId, $table['5'], "activity_type_id");
        } else {
            @$siteauditval = $this->dao->getalldatafromview($siteaudit, $from_date, $to_date, $job = null, $peninginvestigation = null, $paction = null, "date_of_audit", $activityId, $table['5'], "activity_type_id");
        }
        if ($peningivs != "") {
            @$incidentudit = $this->dao->getalldatafromview($incidentaudit, $from_date, $to_date, $job = null, $peninginvestigation = $peningivs, $paction = null, "date_of_incident", $activityId, $table['6'], "activity_type_id");
        } else if ($pendingAction != "") {
            @$incidentudit = $this->dao->getalldatafromview($incidentaudit, $from_date, $to_date, $job = null, $peninginvestigation = $peningivs, $paction = $pendingAction, "date_of_incident", $activityId, $table['6'], "activity_type_id");
        } else {
            @$incidentudit = $this->dao->getalldatafromview($incidentaudit, $from_date, $to_date, $job = null, $peninginvestigation = null, $paction = null, "date_of_incident", $activityId, $table['6'], "activity_type_id");
        }
        @$ppeauditval = $this->dao->getalldatafromview($ppeaudit, $from_date, $to_date, $job = null, $peninginvestigation = null, $paction = null, "date_of_audit", $activityId, $table['7'], "activity_type_id");

        @$safeauditval = $this->dao->getalldatafromsafobsview($safeaudit, $from_date, $to_date, $activityId, $table['8'], "activity_type_id");

        if ($activityId == 5) {
            $searchData = @$siteauditval;
        } else if ($activityId == 6) {
            $searchData = @$incidentudit;
        } else if ($activityId == 7) {
            $searchData = @$ppeauditval;
        } else if ($activityId == 8) {
            $searchData = @$safeauditval;
        } else {
            $searchData = @$fromaudittable;
        }

        /*         * get deficiency,violation and findings* */
        $siteauditvals = $this->dao->getalldatafromview(@$siteaudit, $from_date, $to_date, $job = null, $peninginvestigation = null, $paction = null, "date_of_audit", 5, $table['5'], "activity_type_id");
        $siteauditjobRequest = $this->dao->getalldatafromview(@$siteaudit, $from_date, $to_date, $job = null, $peninginvestigation = null, $paction = null, "date_of_audit", 5, $table['5'], "activity_type_id");
        $incidentudits = $this->dao->getalldatafromview(@$incidentaudit, $from_date, $to_date, $job = null, $peninginvestigation = null, $paction = null, "date_of_incident", 6, $table['6'], "activity_type_id");
        $ppeauditvals = $this->dao->getalldatafromview(@$ppeaudit, $from_date, $to_date, $job = null, $peninginvestigation = null, $paction = null, "date_of_audit", 7, $table['7'], "activity_type_id");

        $totalViolation = "";
        $totalJobRequest = 0;
        $totalPendingInvestigation = 0;
        $totalPendingFindings = 0;

        foreach ($siteauditjobRequest as $sajrval) {
            if ($sajrval->job_stop_req_raised == 'Y') {
                $totalJobRequest += 1;
            }
        }

        foreach ($siteauditvals as $saval) {
            $totalViolation += $saval->no_of_violation;
        }
        $totalFindings = count($incidentudits);
        $totalDeficiency = "";
        foreach ($ppeauditvals as $ppeval) {
            $totalDeficiency += $ppeval->no_of_deviation;
        }

        foreach ($incidentudits as $rows) {
            $investigation_req = $rows->investigation_req;
            $investigation_done = $rows->investigation_done;
            if ($investigation_req == "Y" && $investigation_done == "N") {
                $totalPendingInvestigation += 1;
            }
            $totalPending = 0;
            $clause = " incident_id =" . $rows->id;
            $incidentFinding = $this->dao->get_findings_by_activity($clause);
            foreach ($incidentFinding as $newRow) {
                if ($newRow->action_taken == 'N')
                    ; {
                    $totalPending += 1;
                }
            }
            if ($totalPending > 0) {
                $totalPendingFindings += 1;
            }
        }
        $this->beanUi->set_view_data("getalldata", $searchData);
        $this->beanUi->set_view_data("selectval", $selectval);
        $this->beanUi->set_view_data("from_date", $from_date);
        $this->beanUi->set_view_data("to_date", $to_date);

        $this->beanUi->set_view_data("totalViolation", $totalViolation);
        $this->beanUi->set_view_data("totalFindings", $totalFindings);
        $this->beanUi->set_view_data("totalDeficiency", $totalDeficiency);
        $this->beanUi->set_view_data("totalJobRequest", $totalJobRequest);
        $this->beanUi->set_view_data("totalPendingInvestigation", $totalPendingInvestigation);
        $this->beanUi->set_view_data("totalPendingFindings", $totalPendingFindings);
    }

    public function searchPrint() {
        $mapingtable = array("1" => "activity_division_mapping",
            "2" => "activity_division_mapping",
            "3" => "activity_division_mapping",
            "4" => "activity_division_mapping",
            "5" => "audit_division_mapping",
            "6" => "incident_division_mapping",
            "7" => "ppe_audit_division_mapping",
            "8" => "safety_observation_division_mapping");
        $table = array("1" => "activity_view",
            "2" => "activity_view",
            "3" => "activity_view",
            "4" => "activity_view",
            "5" => "audit_view",
            "6" => "incident_view",
            "7" => "ppe_audit_view",
            "8" => "safety_observation_view");
        $activityId = $this->bean->get_request("activity_id");
        $selectval = $this->bean->get_request("selectval");
        $from_date = $this->bean->get_request("from_date");
        $to_date = $this->bean->get_request("to_date");
        $peningivs = $this->bean->get_request("peningivs");
        $pendingAction = $this->bean->get_request("pendingAction");
        $jobstop = $this->bean->get_request("jobstop");


        $violation_category = array(
            '1' => 'PPE Policy',
            '2' => 'SWP',
            '3' => 'Safety Standard',
            '4' => 'Job Safety - Working at Height',
            '5' => 'Job Safety - Hot Job',
            '6' => 'Job Safety - Confined Space',
            '7' => 'Job Safety - Chemical',
            '8' => 'Job Safety - Heavy Material Handling'
        );

        /*         * tree name get by id* */
        $post_division_department = $this->dao->get_division_department();
        $devition_name = array();
        if ($selectval != "") {
            $tree_division_id_arr = ($selectval != "") ? explode("-", $selectval) : array();
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
                        $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                        $divition .= !empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                    }
                    $divition .= '/';
                }
            }
            $devition_name[] = trim($divition, "/");
        }
        $this->beanUi->set_view_data("devition_names", $devition_name);
        /*         * ******* */


        /*         * Get id from mapping table* */
        $allIds = $this->dao->getIdsfrommappingtable($selectval, $mapingtable['1'], "activity_id");
        $allIds5 = $this->dao->getIdsfrommappingtable($selectval, $mapingtable['5'], "audit_id");
        $allIds6 = $this->dao->getIdsfrommappingtable($selectval, $mapingtable['6'], "incident_id");
        $allIds7 = $this->dao->getIdsfrommappingtable($selectval, $mapingtable['7'], "ppe_audit_id");
        $allIds8 = $this->dao->getIdsfrommappingtable($selectval, $mapingtable['8'], "safety_observation_id");

//             for audit 1 to 4 id
        foreach ($allIds as $key => $value) {
            @$invalue .= $value->activity_id . ",";
        }
        $finalquerypart = trim(@$invalue, ',');
//            end audit table 1 to 4
//             for siteaudit id 5
        foreach ($allIds5 as $key5 => $value5) {
            @$siteaudvalue .= $value5->audit_id . ",";
        }
        @$siteaudit = trim($siteaudvalue, ',');
//            end siteaudit id 5
//             for incidenty id 6
        foreach ($allIds6 as $key6 => $value6) {
            @$incidentvalue .= $value6->incident_id . ",";
        }
        @$incidentaudit = trim($incidentvalue, ',');
//            end siteaudit id 6
//             for ppeaudit id 7
        foreach ($allIds7 as $key7 => $value7) {
            @$ppevalue .= $value7->ppe_audit_id . ",";
        }
        @$ppeaudit = trim($ppevalue, ',');
//            end ppeaudit id 7
//             for safetyudit id 8
        foreach ($allIds8 as $key8 => $value8) {
            @$safevalue .= $value8->safety_observation_id . ",";
        }
        @$safeaudit = trim($safevalue, ',');
//            end safetyaudit id 8

        /*         * Get all data as per search selection from mapping table* */
        $pendingInvest = null;
        $jobsatopValue = null;
        $pendingValue = null;
        if ($peningivs != "") {
            $pendingInvest = $peningivs;
        } else if ($pendingAction != "") {
            $pendingValue = $pendingAction;
        } else if ($jobstop != "") {
            $jobsatopValue = $jobstop;
        }


        @$fromaudittable = $this->dao->getalldatafromview($finalquerypart, $from_date, $to_date, $job = null, $peninginvestigation = null, $paction = null, "activity_date", $activityId, $table['1'], "activity_type_id");
        @$siteauditval = $this->dao->getalldatafromview($siteaudit, $from_date, $to_date, $job = $jobsatopValue, $peninginvestigation = null, $paction = null, "date_of_audit", $activityId, $table['5'], "activity_type_id");
        @$incidentudit = $this->dao->getalldatafromview($incidentaudit, $from_date, $to_date, $job = null, $peninginvestigation = $pendingInvest, $paction = $pendingValue, "date_of_incident", $activityId, $table['6'], "activity_type_id");
        @$ppeauditval = $this->dao->getalldatafromview($ppeaudit, $from_date, $to_date, $job = null, $peninginvestigation = null, $paction = null, "date_of_audit", $activityId, $table['7'], "activity_type_id");
        @$safeauditval = $this->dao->getalldatafromsafobsview($safeaudit, $from_date, $to_date, $activityId, $table['8'], "activity_type_id");
        if ($activityId == 5) {
            $searchData = @$siteauditval;
        } else if ($activityId == 6) {
            $searchData = @$incidentudit;
        } else if ($activityId == 7) {
            $searchData = @$ppeauditval;
        } else if ($activityId == 8) {
            $searchData = @$safeauditval;
        } else {
            $searchData = @$fromaudittable;
        }
        $deviation = array();

        $childArray = array();
        $injury_status = array();
        $injury_status1 = array();
        $clause = " WHERE parent_id=0";
        $body_part_injury = $this->dao->get_body_part_injury($clause);




        foreach ($searchData as $srdata) {
            $id = $srdata->id;
            $activity_type_id = $srdata->activity_type_id;
            if ($activity_type_id == 5) {
                $clause = " audit_id=" . $id . " ";
                $deviation[$srdata->id] = $this->dao->get_violation_by_activity($clause);
            } else if ($activity_type_id == 6) {
                $clause = " incident_id=" . $id . " ";
                $deviation[$srdata->id] = $this->dao->get_findings_by_activity($clause);

                foreach ($body_part_injury as $k => $rdata) {
                    $clausenew = " WHERE parent_id=" . $rdata->id;
                    $getChild = $this->dao->get_body_part_injury($clausenew);
                    $childArray[$srdata->id][$rdata->id] = $getChild;
                    $conds = " WHERE incident_id=" . $id . " AND parent_id=" . $rdata->id;
                    $body_part_injury_mappingData1[$srdata->id][$rdata->id] = $this->dao->get_body_part_injury_mappingData($conds);

                    foreach ($getChild as $ch) {
                        $cond = " WHERE incident_id=" . $id . " AND bodypart_id=" . $ch->id;
                        $body_part_injury_mappingData = $this->dao->get_body_part_injury_mappingData($cond);
                        @$injury_status[$srdata->id][$rdata->id][$ch->id] = $body_part_injury_mappingData[0];
                    }
                }
            } else if ($activity_type_id == 7) {

                $clause = " ppe_audit_id=" . $id . " ";
                $deviation[$srdata->id] = $this->dao->get_deviation_by_activity($clause);
            } else {
                $deviation[] = array();
            }
        }


        $nature_injury = $this->dao->get_nature_injury();
        $this->beanUi->set_view_data("nature_of_injury", $nature_injury);

        $incident_category = $this->dao->get_incident_category();
        $this->beanUi->set_view_data("incident_category", $incident_category);
        $this->beanUi->set_view_data("violation_category", $violation_category);

        $this->beanUi->set_view_data("body_part_injury_mappingData", $body_part_injury_mappingData1);
        $this->beanUi->set_view_data("body_part_injury", $body_part_injury);
        $this->beanUi->set_view_data("childData", $childArray);
        $this->beanUi->set_view_data("injury_status", $injury_status);

        $this->beanUi->set_view_data("getalldata", $searchData);
        $this->beanUi->set_view_data("selectval", $selectval);
        $this->beanUi->set_view_data("from_date", $from_date);
        $this->beanUi->set_view_data("to_date", $to_date);
        $this->beanUi->set_view_data("peningivs", $peningivs);
        $this->beanUi->set_view_data("pendingAction", $pendingAction);
        $this->beanUi->set_view_data("deviation", $deviation);
        $this->beanUi->set_view_data("activityTypeId", $activityId);
    }

    public function get_contractor_list() {

        $id = $this->bean->get_request("id");
        $clause = " WHERE root_division_id=" . $id . " GROUP BY identification_code";
        $get_contractor_list = $this->dao->get_contractor_list($clause);
        $new_dropdown = "";
        $new_dropdown .= '<span style="float:left;width:150px;padding-top: 6px;"><b>Contarctor`s</b></span>';
        $new_dropdown .= '<select name="contractors" class="division">';
        $new_dropdown .= '<option value="" selected="selected">-choose one-</option>';
        foreach ($get_contractor_list as $row) {
            $new_dropdown .= '<option value="cset_contractor:' . $row->identification_code . ':identification_code">' . strtoupper($row->name) . '</option>';
        }
        $new_dropdown .= '</select>';
        echo $new_dropdown;
        die;
    }

    public function submit_division() {
        $name = $this->bean->get_request("name");
        $tree_dept_id = $this->bean->get_request("tree_dept");
        $autoid = $this->bean->get_request("autoid");
        $nameval = implode("~", $name);
        $exp_value = explode("~", $nameval);

        if (in_array("P-SET", $exp_value, TRUE)) {
            $val = 1;
        } else if (in_array("C-SET", $exp_value, TRUE)) {
            $val = 2;
        } else {
            $val = 3;
        }
        $ids = $this->bean->get_request("ids");
        $imp = implode("-", $ids);
        if ($tree_dept_id == 'department') {
            echo '<tr  id="' . $imp . '" class="' . $val . ' deletrow dddd ' . $autoid . '" ><td><input  type="hidden" id="' . $tree_dept_id . '" value="' . $nameval . '" /><input class="set-type" type="text" name="divdept_selection" value="' . $val . '" /><input type="hidden" id="division" name="division[]" value="' . $imp . '" />' . $nameval . '</td>';
            die;
        } else {
            echo '<tr  id="' . $imp . '" class="' . $val . ' deletrow ' . $autoid . '" ><td><input class="set-type" type="hidden" name="divdept_selection" value="' . $val . '" /><input  type="text" id="' . $tree_dept_id . '" value="' . $nameval . '" />' . $nameval . '</td>';
            die;
        }
    }

    public function get_nextlevel() {
        $id = $this->bean->get_request("id");
        $level_count = $this->bean->get_request("lcount");
        $childvalue = $this->dao->get_childvalue($id);
        $labelname = $this->dao->get_labelname($id);
        $tbvalue = $this->dao->check_tbvalue($id);
        $exp = array();
        if ($tbvalue) {
            $exp = explode(",", $tbvalue);
        }

        for ($i = 0; $i < count($exp); $i++) {
            $setvalue[$exp[$i]] = $this->dao->get_setvalue($exp[$i], $id);
        }
        $new_dropdown = '';
        if (!empty($childvalue)) {
            $new_dropdown .= '<span style="float:left;width:150px;padding-top: 6px;"><b>' . $labelname . '</b></span>';
            $new_dropdown .= '<select class="division" name="L' . $level_count . '"  id="L' . $level_count . '">';
            $new_dropdown .= '<option value="" selected="" disabled>choose one</option>';
            foreach ($childvalue as $cval) {
                $new_dropdown .= '<option  value="' . $cval->id . '">' . $cval->name . '</option>';
            }
            $new_dropdown .= '</select>';
        }
        if (empty($childvalue) && ($id == 111 || $id == 248)) {
            $new_dropdown .= '<span style="float:left;width:150px;padding-top: 6px;"><b>Other Name</b></span>';
            $new_dropdown .= '<input class="division othertextbox" name="L' . $level_count . '"  id="L' . $level_count . '" type="text" value="" style="width:23%;" />';
        }
        if (!empty($tbvalue)) {
            $location_name = '';
            $code = '';
            foreach ($setvalue as $key => $rowvalue) {

                $exp_tablename = explode("_", $key);
                $endvalue = end($exp_tablename);
                $label = "Select " . ucfirst($endvalue);
                if (strtoupper($endvalue) == "CONTRACTOR" || strtoupper($endvalue) == "TYPE" || strtoupper($endvalue) == "LOCATION" || strtoupper($endvalue) == "NO") {
                    $extraClass = "contractor_and_type";
                } else {
                    $extraClass = "";
                }
                $new_dropdown .= '<div class="xyz"><div class="searchLabel clearfix"><label class="label">' . $label . '</label>';
                $new_dropdown .= '<select  class="division newcons ' . $extraClass . '" name="contractor_details"  id="contractor' . $key . '">';
                $new_dropdown .= '<option value="" selected="" disabled>choose one</option>';
                foreach ($rowvalue as $cval) {
                    // echo "<pre>";
                    //print_r($cval->name);
                    if (($key == 'pset_no' || $key == 'pset_location') && $cval->name == 'Others') {
                        $val = ' data-other="' . $key . '"';
                    } else if ($key == 'pset_no' || $key == 'pset_location') {
                        $val = ' data-other="0" data-c="' . $key . '"';
                    } else {
                        $val = "";
                    }
                    if (isset($cval->location) && $cval->location != '') {
                        $location_name = 'data-location="' . $cval->location . '"';
                    } else {
                        $location_name = '';
                    }
                    $code = $cval->code != '' ? ' (' . $cval->code . ')' : "";
                    $new_dropdown .= '<option ' . $location_name . $val . ' value="' . $key . ':' . $cval->id . ':id' . '">' . $cval->name . $code . ' </option>';
                }
                $new_dropdown .= '</select></div><span id="show_location"></span></div>';
                $new_dropdown .= '<br /><div id="' . $key . '"></div>';
            }
        }
        echo $new_dropdown;
        die();
    }

}
