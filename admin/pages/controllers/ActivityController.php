<?php

class ActivityController extends MainController {

    public function __construct() {
        $this->bean = load_bean("ActivityBean");
        $this->dao = load_dao("ActivityDAO");
        $this->reportdao = load_dao("ReportDAO");
        $this->schedulerdao = load_dao("SchedulerDAO");

        parent::__construct();
    }
    
    /**
     * Modified by pallab
     */
    protected function index() {
        $_SESSION['act_srch'] = array("activity_type_id"=>$_REQUEST["activity_id"],"from_date"=>$_REQUEST["fromdate"],"to_date"=>$_REQUEST["todate"],"status_id"=>$_REQUEST["status_id"],"districtid"=>$_REQUEST["districtid"],"page"=>$_REQUEST["page"]);
        $get_allactivity_type_master = $this->dao->get_allactivity_type_master();
        $search_title = $this->bean->get_request("search_title");
        $activity_month ="";
        $fromdate = $this->bean->get_request("fromdate");
        $todate = $this->bean->get_request("todate");
        $status_id = $this->bean->get_request("status_id");
        $activity_type_id = $this->bean->get_request("activity_id");
        $incident_category_ids = $this->bean->get_request("incident_category_ids");
        $activity_no = $this->bean->get_request("activity_no");
        $districtid = $this->bean->get_request("districtid");
       
        
        $auth_user_id = $this->get_auth_user("id");
        $role_id = $this->get_auth_user("role_id");
        $page = $this->bean->get_request("page");
        $post_division_department = $this->dao->get_division_department();
        if(isset($_REQUEST["Go"])) {
           $page = $this->bean->get_request("page");
        } else {
            $page =1;
        }

        $limit = 10;
        $passValue = array();
        if ($role_id == 1) {
            $passValue["deleted"] = 0;
            $check_role = " AND deleted= :deleted";
        } else if ($role_id == 3) {
            $passValue["deleted"] = 0;
            $check_role = " AND deleted= :deleted";            
        } else if ($role_id == 2) {
            $passValue["deleted"] = 0;
            $check_role = " AND deleted= :deleted ";
        }
        
        //activity - workshop , comm. meeting, Training, Safety Days
        $clause = "";
        if ($activity_type_id == 1 || $activity_type_id == 2 || $activity_type_id == 3 || $activity_type_id == 4) {
            $nolimit = (($search_title != "" || $todate != "" || $fromdate != "" || $status_id != "") ? 1 : 0 );
            $passValue["activity_type_id"] = $activity_type_id;
            $clause .= " activity_type_id= :activity_type_id " . $check_role;

            if ($fromdate <> "" && $todate <> "") {
                $passValue["from_date"] = $fromdate;
                $passValue["todate"] = $todate;
                if ($activity_type_id == 3) {
                    $clause .= " AND (from_date BETWEEN :from_date AND :todate)";
                } else {
                    $clause .= " AND activity_date BETWEEN :from_date AND :todate";
                }
            }
            if ($search_title != "") {
                $passValue["place"] = "%".$search_title."%";
                $clause .= " AND place LIKE :place";
            }
            if ($activity_no != "") {
                $passValue["activity_no"] = $activity_no;
                $clause .= " AND activity_no LIKE '%:activity_no%'";
            }
            if ($status_id != "") {
                $passValue["status_id"] = $status_id;
                $clause .= " AND status_id = :status_id";
            }
            if ($districtid != "") {
                if( strpos( $districtid, '~' ) !== false ) {
                $exp = explode("~",$districtid);
                $passValue["tree_division_id"] = $exp[0]."%";
                $passValue["tree_division_id1"] = $exp[1]."%";
                $clause .= " AND (tree_division_id LIKE :tree_division_id OR tree_division_id LIKE :tree_division_id1)";
                } else {
                    $passValue["tree_division_id2"] = $districtid."%";
                  $clause .= " AND tree_division_id LIKE :tree_division_id2";  
                }
                
            }
            
            if ($activity_type_id == 3) {
                $clause .= " ORDER BY from_date DESC";
            } else {
                $clause .= " ORDER BY activity_date DESC";
            }
            
            $allactivities = $this->dao->get_activity_with_pagging($clause, $limit, $this->get_auth_user(), $nolimit,$passValue);
            foreach( $allactivities as $k => $treedata ) {
                $tree_division_id_arr = ($treedata->tree_division_id != "") ? explode("-", $treedata->tree_division_id) : array();
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
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name = trim($divition, "/"); 
                $allactivities[$k]->tree_name = $devition_name;
            }
            $activities_pagination = get_page_html($this->dao);
            $this->beanUi->set_view_data("posts_paggin_html", $activities_pagination);
            $this->beanUi->set_view_data("allactivities", $allactivities);
        }

        //audit
        if ($activity_type_id == 5) {
            $nolimit = (($search_title != "" || $todate != "" || $fromdate != "" || $status_id != "") ? 1 : 0 );

            $audit_clause = "";
            $passValue["activity_type_id"] = $activity_type_id;
            $audit_clause .= " activity_type_id= :activity_type_id " . $check_role;
            if ($fromdate <> "" && $todate <> "") {
                $passValue["date_of_audit"] = $fromdate;
                $passValue["date_of_audit1"] = $todate;
                $audit_clause .= " AND date_of_audit BETWEEN :date_of_audit AND :date_of_audit1";
            }
            if ($search_title != "") {
                $passValue["place"] = "%".$search_title."%";
                $audit_clause .= " AND place LIKE :place";
            }
            if ($activity_no != "") {
                $passValue["audit_no"] = $activity_no;
                $audit_clause .= " AND audit_no LIKE %:audit_no%";
            }
            if ($status_id != "") {
                $passValue["status_id"] = $status_id;
                $audit_clause .= " AND status_id = :status_id";
            }
            if ($districtid != "") {
                if( strpos( $districtid, '~' ) !== false ) {
                $exp = explode("~",$districtid);
                $passValue["tree_division_id"] = $exp[0]."%";
                $passValue["tree_division_id1"] = $exp[1]."%";
                $audit_clause .= " AND (tree_division_id LIKE :tree_division_id OR tree_division_id LIKE :tree_division_id1)";
                } else {
                    $passValue["tree_division_id"] = $districtid."%";
                  $audit_clause .= " AND tree_division_id LIKE :tree_division_id";  
                }
                
            }

            $audit_clause .= " ORDER BY date_of_audit DESC";

            $allaudit = $this->dao->get_audit_with_pagging($audit_clause, $limit, $this->get_auth_user(), $nolimit,$passValue);
            foreach( $allaudit as $k => $treedata ) {
                $tree_division_id_arr = ($treedata->tree_division_id != "") ? explode("-", $treedata->tree_division_id) : array();
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
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name = trim($divition, "/"); 
                $allaudit[$k]->tree_name = $devition_name;
            }
            $audit_pagination = get_page_html($this->dao);
            $this->beanUi->set_view_data("posts_paggin_html", $audit_pagination);
            $this->beanUi->set_view_data("allaudit", $allaudit);
        }
        
        
        
        
        //incident
        if ($activity_type_id == 6) {
            $nolimit = (($search_title != "" || $todate != "" || $fromdate != "" || $status_id != "") ? 1 : 0 );

            $incident_clause = "";
            $passValue["activity_type_id"] = $activity_type_id;
            $incident_clause .= " activity_type_id= :activity_type_id " . $check_role;
            if ($search_title != "") {
                $passValue["place"] = "%".$search_title."%";
                $incident_clause .= " AND place LIKE :place";
            }
            if ($fromdate <> "" && $todate <> "") {
                $passValue["date_of_incident"] = $fromdate;
                $passValue["date_of_incident1"] = $todate;
                $incident_clause .= " AND date_of_incident BETWEEN :date_of_incident AND :date_of_incident1";
            }
            if ($incident_category_ids != "") {
                $passValue["incident_category_id"] = $incident_category_ids;
                $incident_clause .= " AND incident_category_id= :incident_category_id ";
            }
            if ($status_id != "") {
                $passValue["status_id"] = $status_id;
                $incident_clause .= " AND status_id = :status_id ";
            }
            if ($districtid != "") {
                if( strpos( $districtid, '~' ) !== false ) {
                $exp = explode("~",$districtid);
                $passValue["tree_division_id"]  = $exp[0]."%";
                $passValue["tree_division_id1"] = $exp[1]."%";
                $incident_clause .= " AND (tree_division_id LIKE :tree_division_id OR tree_division_id LIKE :tree_division_id1)";
                } else {
                    $passValue["tree_division_id"] = $districtid."%";
                  $incident_clause .= " AND tree_division_id LIKE :tree_division_id";  
                }
                
            }
            $incident_clause .= " ORDER BY date_of_incident DESC";
            $allincident = $this->dao->get_incident_with_pagging($incident_clause, $limit, $this->get_auth_user(), $nolimit,$passValue);
             
            foreach( $allincident as $k => $treedata ) {
                
                $tree_division_id_arr = ($treedata->tree_division_id != "") ? explode("-", $treedata->tree_division_id) : array();
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
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name = trim($divition, "/"); 
                $allincident[$k]->tree_name = $devition_name;
            }
            $incident_pagination = get_page_html($this->dao);
            $this->beanUi->set_view_data("posts_paggin_html", $incident_pagination);
            $this->beanUi->set_view_data("allincident", $allincident);
        }

        //ppe_audit
        if ($activity_type_id == 7) {
            $nolimit = (($search_title != "" || $todate != "" || $fromdate != "" || $status_id != "") ? 1 : 0 );

            $ppe_audit_clause = "";
            $passValue["activity_type_id"] = $activity_type_id;
            $ppe_audit_clause .= " activity_type_id= :activity_type_id " .  $check_role;
            if ($search_title != "") {
                $passValue["place"] = "%".$search_title."%";
                $ppe_audit_clause .= " AND place LIKE :place ";
            }
            if ($fromdate <> "" && $todate <> "") {
                $passValue["date_of_audit"] = $fromdate;
                $passValue["date_of_audit1"] = $todate;
                $ppe_audit_clause .= " AND date_of_audit >= :date_of_audit AND date_of_audit <= :date_of_audit1";
            }
            if ($activity_no != "") {
                $passValue["audit_no"] = $activity_no;
                $ppe_audit_clause .= " AND audit_no LIKE %:audit_no% ";
            }
            if ($status_id != "") {
                $passValue["status_id"] = $status_id;
                $ppe_audit_clause .= " AND status_id = :status_id ";
            }
            if ($districtid != "") {
                if( strpos( $districtid, '~' ) !== false ) {
                $exp = explode("~",$districtid);
                $passValue["tree_division_id"] = $exp[0]."%";
                $passValue["tree_division_id1"] = $exp[1]."%";
                $ppe_audit_clause .= " AND (tree_division_id LIKE :tree_division_id OR tree_division_id LIKE :tree_division_id1 )";
                } else {
                    $passValue["tree_division_id"] = $districtid."%";
                  $ppe_audit_clause .= " AND tree_division_id LIKE :tree_division_id";  
                }
                
            }

            $ppe_audit_clause .= " ORDER BY date_of_audit DESC";
            $allppeaudit = $this->dao->get_ppe_audit_with_pagging($ppe_audit_clause, $limit, $this->get_auth_user(), $nolimit,$passValue);
            foreach( $allppeaudit as $k => $treedata ) {
                $tree_division_id_arr = ($treedata->tree_division_id != "") ? explode("-", $treedata->tree_division_id) : array();
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
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name = trim($divition, "/"); 
                $allppeaudit[$k]->tree_name = $devition_name;
            }
            $ppeaudit_pagination = get_page_html($this->dao);
            $this->beanUi->set_view_data("posts_paggin_html", $ppeaudit_pagination);
            $this->beanUi->set_view_data("allppeaudit", $allppeaudit);
        }

        //safety observation
        if ($activity_type_id == 8) {
            $activity_month = $this->bean->get_request("activity_month");
            $activity_year = $this->bean->get_request("activity_year");
            $safety_observation_clause = "";
            $passValue["activity_type_id"] = $activity_type_id;
            $safety_observation_clause .= " activity_type_id=:activity_type_id " . $check_role;
            if ($activity_no != "") {
                $passValue["activity_no"] = $activity_no;
                $safety_observation_clause .= " AND activity_no LIKE %:activity_no%'";
            }
            if ($search_title != "") {
                $passValue["place"] = "%".$search_title."%";
                $safety_observation_clause .= " AND place LIKE :place";
            }
            if ($activity_month != "") {
                $passValue["activity_month"] = $activity_month;
                $safety_observation_clause .= " AND activity_month = :activity_month";
            }
            if ($activity_year != "") {
                $passValue["activity_year"] = $activity_year;
                $safety_observation_clause .= " AND activity_year = :activity_year ";
            }
            if ($status_id != "") {
                $passValue["status_id"] = $status_id;
                $safety_observation_clause .= " AND status_id = :status_id ";
            }
            if ($districtid != "") {
                 if( strpos( $districtid, '~' ) !== false ) {
                $exp = explode("~",$districtid);
                $passValue["tree_division_id"] = $exp[0]."%";
                $passValue["tree_division_id1"] = $exp[1]."%";
                $safety_observation_clause .= " AND (tree_division_id LIKE :tree_division_id OR tree_division_id LIKE :tree_division_id1)";
                } else {
                    $passValue["tree_division_id"] = $districtid."%";
                  $safety_observation_clause .= " AND tree_division_id LIKE :tree_division_id";  
                }
               
            }
            $safety_observation_clause .= " ORDER BY activity_year DESC, activity_month DESC";
            $allsafetyobservation = $this->dao->get_safety_observation_with_pagging($safety_observation_clause, $limit, $this->get_auth_user(),$passValue);
            foreach( $allsafetyobservation as $k => $treedata ) {
                $tree_division_id_arr = ($treedata->tree_division_id != "") ? explode("-", $treedata->tree_division_id) : array();
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
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name = trim($divition, "/"); 
                $allsafetyobservation[$k]->tree_name = $devition_name;
            }
            $safetyob_pagination = get_page_html($this->dao);
            $this->beanUi->set_view_data("posts_paggin_html", $safetyob_pagination);
            $this->beanUi->set_view_data("allsafetyobservation", $allsafetyobservation);
        }
        if ($activity_type_id == 9) {
            $safety_observation_line_clause = "";
            $passValue["activity_type_id"] = $activity_type_id;
            $safety_observation_line_clause .= " activity_type_id= :activity_type_id " . $check_role;
            if ($search_title != "") {
                $passValue["activity_no"] = $search_title;
                $safety_observation_line_clause .= " AND activity_no LIKE %:activity_no% ";
            }
            if ($fromdate <> "" && $todate <> "") {
                $passValue["date_of_observation"] = $fromdate;
                $passValue["date_of_observation1"] = $todate;
                $safety_observation_line_clause .= " AND date_of_observation BETWEEN :date_of_observation AND :date_of_observation1";
            }
            if ($status_id != "") {
                $passValue["status_id"] = $status_id;
                $safety_observation_line_clause .= " AND status_id = :status_id";
            }
            if ($districtid != "") {
                 $passValue["tree_division_id"] = $districtid;
                $safety_observation_line_clause .= " AND tree_division_id LIKE :tree_division_id";
            }
            $safety_observation_line_clause .= " ORDER BY date_of_observation DESC";

            $allsafetyobservationlinefunction = $this->dao->get_safety_observation_line_function_with_pagging($safety_observation_line_clause, $limit, $this->get_auth_user());


            $safetyob_pagination = get_page_html($this->dao);
            $this->beanUi->set_view_data("posts_paggin_html", $safetyob_pagination);
            $this->beanUi->set_view_data("allsafetyobservationlinefunction", $allsafetyobservationlinefunction);
        }
        //minutes_of_meeting
        if ($activity_type_id == 10) {
            $minutes_of_meeting_function_clause = "";
            $passValue["activity_type_id"] = $activity_type_id;
            $minutes_of_meeting_function_clause .= " activity_type_id=:activity_type_id " . $check_role;
            if ($search_title != "") {
                $passValue["activity_no"] = $search_title;
                $minutes_of_meeting_function_clause .= " AND activity_no LIKE %:activity_no% ";
            }
            if ($status_id != "") {
                $passValue["status_id"] = $status_id;
                $minutes_of_meeting_function_clause .= " AND status_id = :status_id ";
            }
            $minutes_of_meeting_function_clause .= " ORDER BY date_of_meeting DESC";

            $allminutesofmeetingfunction = $this->dao->get_minutes_of_meeting_function_with_pagging($minutes_of_meeting_function_clause, $limit, $this->get_auth_user());


            $minutes_of_meeting_pagination = get_page_html($this->dao);
            $this->beanUi->set_view_data("posts_paggin_html", $minutes_of_meeting_pagination);
            $this->beanUi->set_view_data("allminutesofmeetingfunction", $allminutesofmeetingfunction);
        }

               /*******/
            $arr      = array();
            $scoreID  = $this->reportdao->getReportSettings(9,"id");
            $rowArray = explode(",",$scoreID[0]->param_value);
            foreach ($rowArray as $k => $rr) {
                $newexp = explode("-",$rr);
                $rowid = end($newexp);
            $clause   = " id='" . $rowid . "'";
            $parentDivisionxx = $this->dao->get_division_department($clause);
            $arr[]= $parentDivisionxx[0];
            $arr[$k]->custom_id = $rr;
            }
           
            $this->beanUi->set_view_data("allDistrict", $arr);
            /*******/
        
        
        $incident_category = $this->dao->get_incident_category();
        $this->beanUi->set_view_data("incident_category", $incident_category);
        $this->beanUi->set_view_data("page", $page);
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("search_title", $this->bean->get_request("search_title"));
        $this->beanUi->set_view_data("activity_month", $activity_month);
        $this->beanUi->set_view_data("activity_year", $activity_year);
        $this->beanUi->set_view_data("status_id", $this->bean->get_request("status_id"));
        $this->beanUi->set_view_data("fromdate", $this->bean->get_request("fromdate"));
        $this->beanUi->set_view_data("incident_category_ids", $this->bean->get_request("incident_category_ids"));
        $this->beanUi->set_view_data("todate", $this->bean->get_request("todate"));
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("activity_type_id", $activity_type_id);
        $this->beanUi->set_view_data("districtid", $districtid);
        $this->beanUi->set_view_data("get_allactivity_type_master", $get_allactivity_type_master);
    }

    public function create() {
        $_action = $this->bean->get_request("_action");
        if ($_action == "Create") {
            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");
            
//            pk14112018
//                $clauseAN         =   " activity_type_id ='". $data["activity_type_id"]."' AND activity_no !='' ORDER BY id DESC LIMIT 1";
//                $getAllAuditNo    =   $this->dao->fetchAllData('activity',$clauseAN);
//                $year = date('Y');
//                $month = date('m');
//                $financYear = getFinancialYear($year,$month,'y');
//                $lastId = explode('/',$getAllAuditNo[0]->activity_no);
//                $divDeptId = $this->bean->get_request("division");
//                $divDeptIdExp = explode('-',$divDeptId[0]);
//                
//                if ($divDeptIdExp[1] == 2) {
//                    $dT = "G";
//                } else if ($divDeptIdExp[1] == 3 || $divDeptIdExp[1] == 4) {
//                    $dT = "D";
//                }
//                if($data["activity_type_id"] == 1){
//                    if(!empty($getAllAuditNo)) {
//                    $activity_ref_no = $dT.'/'.WORKSHOP_AN.sprintf("%04d", ($lastId[2] + 1)).'/'.$financYear;//show($activity_ref_no);
//                    } else {
//                        $activity_ref_no = $dT.'/'.WORKSHOP_AN.sprintf("%04d", 1).'/'.$financYear;
//                    } 
//                    
//                } else if($data["activity_type_id"] == 2){
//                    if(!empty($getAllAuditNo)) {
//                    $activity_ref_no = $dT.'/'.COMM_MEETING_AN.sprintf("%04d", ($lastId[2] + 1)).'/'.$financYear;//show($activity_ref_no);
//                    } else {
//                        $activity_ref_no = $dT.'/'.COMM_MEETING_AN.sprintf("%04d", 1).'/'.$financYear;
//                    } 
//                } else if($data["activity_type_id"] == 3){
//                    if(!empty($getAllAuditNo)) {
//                    $activity_ref_no = $dT.'/'.TRAINING_AN.sprintf("%04d", ($lastId[2] + 1)).'/'.$financYear;//show($activity_ref_no);
//                    } else {
//                        $activity_ref_no = $dT.'/'.TRAINING_AN.sprintf("%04d", 1).'/'.$financYear;
//                    } 
//                } else if($data["activity_type_id"] == 4){
//                   if(!empty($getAllAuditNo)) {
//                    $activity_ref_no = $dT.'/'.SAFETY_DAY_AN.sprintf("%04d", ($lastId[2] + 1)).'/'.$financYear;//show($activity_ref_no);
//                    } else {
//                        $activity_ref_no = $dT.'/'.SAFETY_DAY_AN.sprintf("%04d", 1).'/'.$financYear;
//                    } 
//                }
                
//            pk14112018
                
            $token_id = $this->bean->get_request("token_id");
            $data["activity_no"]            = $activity_ref_no ? $activity_ref_no : 0;
            $data["created_by"]             = $this->get_auth_user("id");
            $data["created_date"]           = date("c");
            $data["modified_by"]            = $this->get_auth_user("id");
            $data["modified_date"]          = date("c");
            $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
            $data["featured_image_path"] = $featured_img[0];
            $activity_no_exists = $this->dao->check_activity_no_exists($data["activity_no"]);
            if ($activity_no_exists) {
                $this->beanUi->set_error_message("Activity No. already exists into database.");
                redirect("./index.php?activity_id=" . $data["activity_type_id"]);
            }

            // Save Activity
            $activityid = $this->dao->save($data);
            $activity_logs = array();
            if ($activityid) {
                $activity_logs["status_id"] = $data["status_id"];
                $activity_logs["activity_id"] = $activityid;
                $activity_logs["activity_type_id"] = $data["activity_type_id"];
                $this->dao->_table = "activity_logs";
                $this->dao->save($activity_logs);
            }
            if (!$activityid) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            // End

            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }
            
            if (!empty($uploads)) {
                $errors = "";
                $this->dao->_table = "file_upload_activity_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "" && $activityid) {
                        $post_uploads["activity_id"] = $activityid;
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }
            //Participant Category Mapping Insert
            $participant_category = $this->bean->get_request("participant_cat_id");
            $no_participants = $this->bean->get_request("no_of_participants");
            if (!empty($participant_category)) {
                $this->dao->_table = "activity_participant_category_mapping";
                foreach ($participant_category as $prtrow => $key) {
                    $act_p_category_mapping = array();
                    $act_p_category_mapping["activity_id"] = $activityid;
                    $act_p_category_mapping["participant_cat_id"] = $key["participant_cat_id"];
                    $act_p_category_mapping["no_of_participants"] = $no_participants[$prtrow];
                    $act_p_category_mapping["created_by"] = $this->get_auth_user("id");
                    $act_p_category_mapping["created_date"] = date("c");
                    $act_p_category_mapping["type"] = "P";
                    $category_mapping = $this->dao->save($act_p_category_mapping);
                    if (!$category_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }

            //Faculty Category Mapping Insert
            $faculty_category = $this->bean->get_request("faculty_cat_id");
            $no_of_faculty = $this->bean->get_request("no_of_faculty");
            if (!empty($faculty_category)) {
                $this->dao->_table = "activity_participant_category_mapping";
                foreach ($faculty_category as $frtrow => $keys) {
                    $act_faculty_category_mapping = array();
                    $act_faculty_category_mapping["activity_id"] = $activityid;
                    $act_faculty_category_mapping["participant_cat_id"] = $keys["faculty_cat_id"];
                    $act_faculty_category_mapping["no_of_participants"] = $no_of_faculty[$frtrow];
                    $act_faculty_category_mapping["created_by"] = $this->get_auth_user("id");
                    $act_faculty_category_mapping["created_date"] = date("c");
                    $act_faculty_category_mapping["type"] = "F";
                    $category_mappings = $this->dao->save($act_faculty_category_mapping);
                    if (!$category_mappings) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }

            //Division Mapping Insert
            $division = $this->bean->get_request("division");
            if (!empty($division)) {
                $this->dao->_table = "activity_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["activity_id"] = $activityid;
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            $this->dao->update_activity_participants($activityid, $token_id);
            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Add popular post entry
            if ($data["status_id"] == 3) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $activityid);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $activityid));
                }
            }
            // End popular post

            $this->beanUi->set_success_message("Activity is successfully added.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("activity_id", $this->bean->get_request("activity_id"));
        $this->beanUi->set_view_data("post_insert_participants", $this->dao->insert_participants($query = null));
        $this->beanUi->set_array_to_view_data($this->get_session("data"));
        $activity_id = $this->bean->get_request("activity_id");
        if (!$activity_id) {
            $this->beanUi->set_error_message("Could not load the page, activity id is missing.");
            redirect("./");
        }
    }

    public function add_audit() {

        $_action = $this->bean->get_request("_action");
        if ($_action == "Create") {
            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");
            
             if ($this->bean->get_request("audit_by") != "") {
                $data["audit_by"] = implode(",", $this->bean->get_request("audit_by"));
            }
  //            pk14112018
//                $clauseAN         =   " audit_no !='' ORDER BY id DESC LIMIT 1";
//                $getAllAuditNo    =   $this->dao->fetchAllData('audit',$clauseAN);
//                $year = date('Y');
//                $month = date('m');
//                $financYear = getFinancialYear($year,$month,'y');
//                $lastId = explode('/',$getAllAuditNo[0]->audit_no);
//                $divDeptId = $this->bean->get_request("division");
//                $divDeptIdExp = explode('-',$divDeptId[0]);
//                
//                if ($divDeptIdExp[1] == 2) {
//                    $dT = "G";
//                } else if ($divDeptIdExp[1] == 3 || $divDeptIdExp[1] == 4) {
//                    $dT = "D";
//                }
//                
//                if(!empty($getAllAuditNo)) {
//                    $activity_ref_no = $dT.'/'.SITE_AUDIT_AN.sprintf("%04d", ($lastId[2] + 1)).'/'.$financYear;
//                } else {
//                    $activity_ref_no = $dT.'/'.SITE_AUDIT_AN.sprintf("%04d", 1).'/'.$financYear;
//                }
//            pk14112018
            
            $token_id = $this->bean->get_request("token_id");
            $data["audit_no"]               = $activity_ref_no ? $activity_ref_no : 0;
            $data["created_by"]             = $this->get_auth_user("id");
            $data["created_date"]           = date("c");
            $data["modified_by"]            = $this->get_auth_user("id");
            $data["modified_date"]          = date("c");
            $featured_img                   = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
            $data["featured_image_path"]    = $featured_img[0];

            $this->bean->set_data($data);
            $valid_post = $this->bean->post_validation();

            // Save post
            $this->dao->_table = "audit";
            $activityid = $this->dao->save($data);
            $activity_logs = array();
            if ($activityid) {
                $activity_logs["status_id"] = $data["status_id"];
                $activity_logs["activity_id"] = $activityid;
                $activity_logs["activity_type_id"] = $data["activity_type_id"];
                $this->dao->_table = "activity_logs";
                $this->dao->save($activity_logs);
            }
            //sas
            $clauseSN         =   " is_selected = '0' AND sas_report_no = '".$data["sas_report_no"]."'";
            $getSN    =   $this->dao->fetchAllData('mis_sas',$clauseSN);
            $sasNoArr = array();
            if($getSN[0]->id){
                $sasNoArr["id"] = $getSN[0]->id;
                $sasNoArr["is_selected"]= 1;
                $sasNoArr["modified_by"]            = $this->get_auth_user("id");
                $sasNoArr["modified_date"]          = date("c");
                $this->dao->_table = "mis_sas";
                $this->dao->save($sasNoArr);
            }
            $clauseSNI         =   " audit_id = '0' AND sas_report_no = '".$data["sas_report_no"]."'";
            $getSNI    =   $this->dao->fetchAllData('file_upload_audit_mapping',$clauseSNI);
            $sasImgArr = array();
            foreach ($getSNI as $kSNI => $vSNI) {
            if($vSNI->id){
                $sasImgArr["id"]                    = $vSNI->id;
                $sasImgArr["audit_id"]              = $activityid;
                $sasImgArr["modified_date"]         = date("c");
                $this->dao->_table = "file_upload_audit_mapping";
                $this->dao->save($sasImgArr);
            }   
            }
            
            //sas
            if (!$activityid) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }


            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            $old_image_path = $this->bean->get_request("old_image_path");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }
                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            if (!empty($uploads)) {
                $errors = "";
                $this->dao->_table = "file_upload_audit_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "" && $activityid) {
                        $post_uploads["audit_id"] = $activityid;
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }

 			if(!empty($old_image_path)){
                foreach ($old_image_path as $key => $oldrow) {
                    //show($oldrow);
                    $myArray1 = explode('.', $oldrow);
                    $myArray = "image/".$myArray1[1];
//                    $imagename = explode('/',$oldrow);
//                    $imagename1 = $imagename[3];
                    $newimage = $myArray1[0]."_".rand().".".$myArray1[1];//show($newimage);
//                    $oldrow1 = $myArray1[0]."_".$newimage;
                    $prPathArr=  explode('/', BASE_PATH);
                    array_pop($prPathArr);                    
                    $imgPath=  implode("/",$prPathArr);
                      $oldrow1 = $imgPath.'/'.$oldrow;
                    $newimage1 = $imgPath.'/'.$newimage;
                    
                        if (copy($oldrow1, $newimage1)) { 
                                
                            
                            
                    $data1["audit_id"]               = $activityid;
                    $data1["file_type"]             = $myArray;
                    $data1["file_path"]           = $newimage;
                    $data1["name"]            = '';
                    $data1["created_date"]          = date("c");
                    $data1["modified_date"]    = date("c");
                    $data1["type_id"]    = 0;
                    $this->dao->_table = "file_upload_audit_mapping";
                    $oldimgid = $this->dao->save($data1);
                        }
                }
            }
            //Division Mapping Insert
            $division = $this->bean->get_request("division");



            if (!empty($division)) {
                $this->dao->_table = "audit_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["audit_id"] = $activityid;
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] =str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            $this->dao->update_mappingID($activityid, $token_id, "audit_id", "audit_violation_mapping");



            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Add popular post entry
            if ($data["status_id"] == 3) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $activityid);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $activityid));
                }
            }
            // End popular post			
            $this->beanUi->set_success_message("Audit is successfully added.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }

        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("activity_id", $this->bean->get_request("activity_id"));
        $this->beanUi->set_view_data("post_insert_participants", $this->dao->insert_participants());
        $this->beanUi->set_view_data("user_details", $this->dao->get_user_details());
        $this->beanUi->set_array_to_view_data($this->get_session("data"));
        $activity_id = $this->bean->get_request("activity_id");
        if (!$activity_id) {
            $this->beanUi->set_error_message("Could not load the page, activity id is missing.");
            redirect("./");
        }
    }
    public function getSasDetails(){
        $sas_report_no      =   $this->bean->get_request("sas_report_no");
        $clause             =   " sas_report_no = '$sas_report_no' ";
        $sasData            =   $this->dao->fetchAllData("mis_sas",$clause);
        
        $content = file_get_contents("http://10.40.83.45:8081/sas/SiteAuditView?report_id=".$sas_report_no);
//        $content = '{"audited_by_code":"048739|104776|NA","date_of_audit":"2019-02-11","audited_by_name":"MALIN SINGHA ROY|NA|NA","image_captions":"F A BOX WITHOUT BANDAID|Barricading arrangement without small caution board|Use of caution board at site|Use of full body apron during isolation|Use of danger board|Damaged nose mask available at site|","uploaded_images":"20181006_123955.jpg|20181006_125038.jpg|DSCN7847.JPG|DSCN7856.JPG|DSCN7864.JPG|DSCN7870.JPG|","avg_mark":"98.52","time_of_audit_to":"13:40","audit_duration_minutes":"100","time_of_audit_from":"12:00","place":"7/P/1,KAZI BAGAN LANE,HOWRAH 711104","sas_report_no":"PS06/180/4"}';
        $contentResult  = json_decode($content);
        if(empty($sasData)){
            if($contentResult->sas_report_no == $sas_report_no){
                $incData["sas_report_no"]            = $contentResult->sas_report_no           ? $contentResult->sas_report_no : 0;
                $incData["date_of_audit"]            = $contentResult->date_of_audit           ? $contentResult->date_of_audit : 0;
                $incData["time_of_audit_from"]       = $contentResult->time_of_audit_from      ? $contentResult->time_of_audit_from : 0;
                $incData["time_of_audit_to"]         = $contentResult->time_of_audit_to        ? $contentResult->time_of_audit_to : 0;
                $incData["audit_duration"]           = $contentResult->audit_duration_minutes  ? $contentResult->audit_duration_minutes : 0;
                $incData["place"]                    = $contentResult->place                   ? $contentResult->place : 0;
                $incData["avg_mark"]                 = $contentResult->avg_mark                ? $contentResult->avg_mark : 0;
                $incData["audited_by_code"]          = $contentResult->audited_by_code         ? $contentResult->audited_by_code : 0;
                $incData["audited_by_name"]          = $contentResult->audited_by_name         ? $contentResult->audited_by_name : 0;
                $incData["created_by"]               = $this->get_auth_user("id");
                $incData["created_date"]             = date("c");
                $this->schedulerdao->_table = "mis_sas";
                $this->schedulerdao->save($incData);
                
                
                $uploadedImages         = $contentResult->uploaded_images;
                $uploadExpImage         = explode("|",$uploadedImages);
                $uploadExpImagePop      = array_pop($uploadExpImage);
                $imagesCaptions         = $contentResult->image_captions;
                $imagesExpCaptions      = explode("|",$imagesCaptions);
                foreach($uploadExpImage AS $key => $value){
                   
                $incDataImg["sas_report_no"]            = $contentResult->sas_report_no           ? $contentResult->sas_report_no : 0;  
                $incDataImg["name"]                     = $imagesExpCaptions[$key];  
                $incDataImg["file_type"]                = 'image/jpeg';  
                $incDataImg["file_path"]                = $value ? $value : 0;  
                $incDataImg["created_date"]             = date("c");
                
                $this->schedulerdao->_table = "file_upload_audit_mapping";
                $this->schedulerdao->save($incDataImg);
                }
                
                
            }                            
                $clause             =   " sas_report_no = '$sas_report_no' ";
                $sasData            =   $this->dao->fetchAllData("mis_sas",$clause);
                echo json_encode($sasData[0]); die;            
         }else{
                $getMisSasNo = $contentResult->sas_report_no;
                $getTempSasDatails = $this->schedulerdao->getTempSasDatails($getMisSasNo);
                if(count($getTempSasDatails) > 0){

                    $updateData["date_of_audit"]            = $contentResult->date_of_audit             ? $contentResult->date_of_audit : 0;
                    $updateData["time_of_audit_from"]       = $contentResult->time_of_audit_from        ? $contentResult->time_of_audit_from : 0;
                    $updateData["time_of_audit_to"]         = $contentResult->time_of_audit_to          ? $contentResult->time_of_audit_to : 0;
                    $updateData["audit_duration"]           = $contentResult->audit_duration_minutes    ? $contentResult->audit_duration_minutes : 0;
                    $updateData["place"]                    = $contentResult->place                     ? $contentResult->place : 0;
                    $updateData["avg_mark"]                 = $contentResult->avg_mark                  ? $contentResult->avg_mark : 0;
                    $updateData["audited_by_code"]          = $contentResult->audited_by_code           ? $contentResult->audited_by_code : 0;
                    $updateData["audited_by_name"]          = $contentResult->audited_by_name           ? $contentResult->audited_by_name : 0;
                    $updateData["modified_by"]              = $this->get_auth_user("id");
                    $updateData["modified_date"]            = date("c");
                    $this->schedulerdao->_table = "mis_sas";
                    $getTempSasDatailsId = $getTempSasDatails[0]->id;
                    if($getTempSasDatailsId != 0){
                        $updateData["id"]                   = $getTempSasDatailsId;
                        $insert_id = $this->schedulerdao->save($updateData);
                    }
                }          
            $clause             =   " sas_report_no = '$sas_report_no' ";//show($updateData);
            $sasData            =   $this->dao->fetchAllData("mis_sas",$clause);
            echo json_encode($sasData[0]); die;
        }       
    }
    public function getSasDetailsForPPE(){
        $sas_report_no      =   $this->bean->get_request("sas_report_no");
        $clause             =   " sas_report_no = '$sas_report_no' ";
        $sasData            =   $this->dao->fetchAllData("mis_sas_ppe",$clause);
        
        $content = file_get_contents("http://10.40.83.45:8081/sas/GearAuditView?report_id=".$sas_report_no);
        //$content = '{"audited_by_code":"311510|096487|NA","date_of_audit":"2019-02-04","audited_by_name":"BISHWANATH ROY|INDRANATH CHAKRABORTY|NA","image_captions":"Scrap|Caution Board|Safety PPE and Tools|First Aid Box with expired mercurochrome|Ladders|Metered Supply|","uploaded_images":"IMG_3970.JPG|IMG_3973.JPG|IMG_3974.JPG|IMG_3977.JPG|IMG_3979.JPG|IMG_3968.JPG|","avg_mark":"95.00","place":"BAGHAJATIN STN. RD. O/T","sas_report_no":"CS01/058/01"}';
        $contentResult  = json_decode($content);//show($contentResult);
        if(empty($sasData)){
            if($contentResult->sas_report_no == $sas_report_no){
                $incData["sas_report_no"]            = $contentResult->sas_report_no           ? $contentResult->sas_report_no : 0;
                $incData["date_of_audit"]            = $contentResult->date_of_audit           ? $contentResult->date_of_audit : 0;
//                $incData["time_of_audit_from"]       = $contentResult->time_of_audit_from      ? $contentResult->time_of_audit_from : 0;
//                $incData["time_of_audit_to"]         = $contentResult->time_of_audit_to        ? $contentResult->time_of_audit_to : 0;
//                $incData["audit_duration"]           = $contentResult->audit_duration_minutes  ? $contentResult->audit_duration_minutes : 0;
                $incData["place"]                    = $contentResult->place                   ? $contentResult->place : 0;
                $incData["avg_mark"]                 = $contentResult->avg_mark                ? $contentResult->avg_mark : 0;
                $incData["audited_by_code"]          = $contentResult->audited_by_code         ? $contentResult->audited_by_code : 0;
                $incData["audited_by_name"]          = $contentResult->audited_by_name         ? $contentResult->audited_by_name : 0;
                $incData["created_by"]               = $this->get_auth_user("id");
                $incData["created_date"]             = date("c");
                $this->schedulerdao->_table = "mis_sas_ppe";
                $this->schedulerdao->save($incData);
                
                
                $uploadedImages         = $contentResult->uploaded_images;
                $uploadExpImage         = explode("|",$uploadedImages);
                $uploadExpImagePop      = array_pop($uploadExpImage);
                $imagesCaptions         = $contentResult->image_captions;
                $imagesExpCaptions      = explode("|",$imagesCaptions);
                foreach($uploadExpImage AS $key => $value){
                   
                $incDataImg["sas_report_no"]            = $contentResult->sas_report_no           ? $contentResult->sas_report_no : 0;  
                $incDataImg["name"]                     = $imagesExpCaptions[$key];  
                $incDataImg["file_type"]                = 'image/jpeg';  
                $incDataImg["file_path"]                = $value ? $value : 0;  
                $incDataImg["created_date"]             = date("c");
                
                $this->schedulerdao->_table = "file_upload_ppe_audit_mapping";
                $this->schedulerdao->save($incDataImg);
                }
                
                
            }                            
                $clause             =   " sas_report_no = '$sas_report_no' ";
                $sasData            =   $this->dao->fetchAllData("mis_sas_ppe",$clause);
                echo json_encode($sasData[0]); die;            
         }else{
                $getMisSasNo = $contentResult->sas_report_no;
                $getTempSasPpeDatails = $this->schedulerdao->getTempSasPpeDatails($getMisSasNo);
                if(count($getTempSasPpeDatails) > 0){

                    $updateData["date_of_audit"]            = $contentResult->date_of_audit             ? $contentResult->date_of_audit : 0;
//                    $updateData["time_of_audit_from"]       = $contentResult->time_of_audit_from        ? $contentResult->time_of_audit_from : 0;
//                    $updateData["time_of_audit_to"]         = $contentResult->time_of_audit_to          ? $contentResult->time_of_audit_to : 0;
//                    $updateData["audit_duration"]           = $contentResult->audit_duration_minutes    ? $contentResult->audit_duration_minutes : 0;
                    $updateData["place"]                    = $contentResult->place                     ? $contentResult->place : 0;
                    $updateData["avg_mark"]                 = $contentResult->avg_mark                  ? $contentResult->avg_mark : 0;
                    $updateData["audited_by_code"]          = $contentResult->audited_by_code           ? $contentResult->audited_by_code : 0;
                    $updateData["audited_by_name"]          = $contentResult->audited_by_name           ? $contentResult->audited_by_name : 0;
                    $updateData["modified_by"]              = $this->get_auth_user("id");
                    $updateData["modified_date"]            = date("c");
                    $this->schedulerdao->_table = "mis_sas_ppe";
                    $getTempSasPpeDatailsId = $getTempSasPpeDatails[0]->id;
                    if($getTempSasPpeDatailsId != 0){
                        $updateData["id"]                   = $getTempSasPpeDatailsId;
                        $insert_id = $this->schedulerdao->save($updateData);
                    }
                }          
            $clause             =   " sas_report_no = '$sas_report_no' ";
            $sasData            =   $this->dao->fetchAllData("mis_sas_ppe",$clause);
            echo json_encode($sasData[0]); die;
        }
    }
    public function getSasImageDetails(){
        $sas_report_no      =   $this->bean->get_request("sas_report_no");
        $clause             =   " sas_report_no = '$sas_report_no' ";
        $sasData             = $this->schedulerdao->getSasSAImageDetails($clause);
        echo json_encode($sasData); die;
    }
    public function getSasImageDetailsForPPE(){
        $sas_report_no      =   $this->bean->get_request("sas_report_no");
        $clause             =   " sas_report_no = '$sas_report_no' ";
        $sasData             = $this->schedulerdao->getSasPPEImageDetails($clause);
        echo json_encode($sasData); die;
    }

    public function add_audit_nm() {

        $_action = $this->bean->get_request("_action");
        if ($_action == "Create") {
            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");
            
             if ($this->bean->get_request("audit_by") != "") {
                $data["audit_by"] = implode(",", $this->bean->get_request("audit_by"));
            }
  //            pk14112018
//                $clauseAN         =   " audit_no !='' ORDER BY id DESC LIMIT 1";
//                $getAllAuditNo    =   $this->dao->fetchAllData('audit',$clauseAN);
//                $year = date('Y');
//                $month = date('m');
//                $financYear = getFinancialYear($year,$month,'y');
//                $lastId = explode('/',$getAllAuditNo[0]->audit_no);
//                $divDeptId = $this->bean->get_request("division");
//                $divDeptIdExp = explode('-',$divDeptId[0]);
//                
//                if ($divDeptIdExp[1] == 2) {
//                    $dT = "G";
//                } else if ($divDeptIdExp[1] == 3 || $divDeptIdExp[1] == 4) {
//                    $dT = "D";
//                }
//                
//                if(!empty($getAllAuditNo)) {
//                    $activity_ref_no = $dT.'/'.SITE_AUDIT_AN.sprintf("%04d", ($lastId[2] + 1)).'/'.$financYear;
//                } else {
//                    $activity_ref_no = $dT.'/'.SITE_AUDIT_AN.sprintf("%04d", 1).'/'.$financYear;
//                }
//            pk14112018
            
            $token_id = $this->bean->get_request("token_id");
            $data["audit_no"]               = $activity_ref_no ? $activity_ref_no : 0;
            $data["created_by"]             = $this->get_auth_user("id");
            $data["created_date"]           = date("c");
            $data["modified_by"]            = $this->get_auth_user("id");
            $data["modified_date"]          = date("c");
            $featured_img                   = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
            $data["featured_image_path"]    = $featured_img[0];

            $this->bean->set_data($data);
            $valid_post = $this->bean->post_validation();
//show($data);
            // Save post
            $this->dao->_table = "audit";
            $activityid = $this->dao->save($data);
            $activity_logs = array();
            if ($activityid) {
                $activity_logs["status_id"] = $data["status_id"];
                $activity_logs["activity_id"] = $activityid;
                $activity_logs["activity_type_id"] = $data["activity_type_id"];
                $this->dao->_table = "activity_logs";
                $this->dao->save($activity_logs);
            }
            if (!$activityid) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }


            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }
                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            if (!empty($uploads)) {
                $errors = "";
                $this->dao->_table = "file_upload_audit_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "" && $activityid) {
                        $post_uploads["audit_id"] = $activityid;
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }


            //Division Mapping Insert
            $division = $this->bean->get_request("division");



            if (!empty($division)) {
                $this->dao->_table = "audit_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["audit_id"] = $activityid;
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] =str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            $this->dao->update_mappingID($activityid, $token_id, "audit_id", "audit_violation_mapping");



            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Add popular post entry
            if ($data["status_id"] == 3) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $activityid);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $activityid));
                }
            }
            // End popular post			
            $this->beanUi->set_success_message("Audit is successfully added.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }

        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("activity_id", $this->bean->get_request("activity_id"));
        $this->beanUi->set_view_data("post_insert_participants", $this->dao->insert_participants());
        $this->beanUi->set_view_data("user_details", $this->dao->get_user_details());
        $this->beanUi->set_array_to_view_data($this->get_session("data"));
        $activity_id = $this->bean->get_request("activity_id");
        if (!$activity_id) {
            $this->beanUi->set_error_message("Could not load the page, activity id is missing.");
            redirect("./");
        }
    }
     public function new_add_incident() {
        $voilation_category = array(
            '1' => 'PPEs',
            '2' => 'SWP',
            '3' => 'Safety Standard',
            '4' => 'Job Safety - Working at Height',
            '5' => 'Job Safety - Hot Job',
            '6' => 'Job Safety - Confined Space',
            '7' => 'Job Safety - Chemical',
            '8' => 'Job Safety - Heavy Material Handling',
            '9' => 'Reaction Of People',
            '10' => 'Position Of People',
            '11' => 'Tools & Equipment',
            '12' => 'Procudure',
            '13' => 'Unsafe condition',
            '14' => 'Unsafe Act',
            '15' => 'Job Safety- Electrical Safety',
            '16' => 'Job Safety - Excavation',
            '17' => 'Safe Zone Creation'
        );

        $_action = $this->bean->get_request("_action");
        if ($_action == "Create") {
            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");
            $token_id = $this->bean->get_request("token_id");
            $data["created_by"] = $this->get_auth_user("id");
            $data["created_date"] = date("c");
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $data["incident_category_id"] = $this->bean->get_request("incident_category_id");
            if ($this->bean->get_request("violation_category") != "") {
                $data["violation_category"] = implode(",", $this->bean->get_request("violation_category"));
            }
            if ($this->bean->get_request("nature_of_injury_id") != "") {
                $data["nature_of_injury_id"] = implode(",", $this->bean->get_request("nature_of_injury_id"));
            }
        
            $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
            $data["featured_image_path"] = $featured_img[0];

           
            $this->dao->_table = "incident";
            $activityid = $this->dao->save($data);
            $activity_logs = array();
            if ($activityid) {
                $activity_logs["status_id"] = $data["status_id"];
                $activity_logs["activity_id"] = $activityid;
                $activity_logs["activity_type_id"] = $data["activity_type_id"];
                $this->dao->_table = "activity_logs";
                $this->dao->save($activity_logs);
            }
            if (!$activityid) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
           
            /*             * ******** incident file upload ********* */
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }
           
            if (!empty($uploads)) {
                $errors = "";
                $this->dao->_table = "file_upload_incident_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "" && $activityid) {
                        $post_uploads["incident_id"] = $activityid;
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        
                        
                        
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }
            /*             * ******** incident file upload ********* */

            /*             * ******** division mapping insert ********* */
            $division = $this->bean->get_request("division");
            if (!empty($division)) {
                $this->dao->_table = "incident_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["incident_id"] = $activityid;
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            /*             * ******** division mapping insert ********* */
            $this->dao->update_incident_finding_mapping($activityid, $token_id);

            /*             * ******** incident category insert ********* */
            $incident_category_id = $this->bean->get_request("incident_category_id");
            if (!empty($incident_category_id)) {
                $this->dao->_table = "incident_category_logs";
                $inc_cat = array();
                $inc_cat["incident_id"] = $activityid;
                $inc_cat["incident_category_id"] = $incident_category_id;
                $inc_cat["created_by"] = $this->get_auth_user("id");
                $inc_cat["created_date"] = date("c");
                $save_inc_category = $this->dao->save($inc_cat);
                if (!$save_inc_category) {
                    $errors .= $this->dao->get_query_error() . ",";
                }
            }
            /*             * ******** incident category insert ********* */

            /*             * ******** body part insert ********* */
            $bodypart_ids = $this->bean->get_request("bodypart_ids");
            $parent_id = $this->bean->get_request("parent_id");
            if (!empty($bodypart_ids)) {
                $this->dao->_table = "incident_body_part_injury_mapping";
                $bpart_insert = array();
                foreach ($bodypart_ids as $key => $values) {
                    $bodypart_status = $this->bean->get_request("bodypart_status_" . $bodypart_ids[$key]);
                    $bpart_insert["incident_id"] = $activityid;
                    $bpart_insert["parent_id"] = $parent_id[$key];
                    $bpart_insert["bodypart_id"] = $bodypart_ids[$key];
                    $bpart_insert["status"] = $bodypart_status;
                    $save_bpart_insert = $this->dao->save($bpart_insert);
                    if (!$save_bpart_insert) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            /*             * ******** body part insert ********* */

            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Add popular post entry
            if ($data["status_id"] == 3) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $activityid);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $activityid));
                }
            }
            // End popular post
            $this->beanUi->set_success_message("Incident is successfully added.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }

        $incident_category = $this->dao->get_incident_category();
        $nature_injury = $this->dao->get_nature_injury();
        $childArray = array();
        $clause = " WHERE parent_id=0";
        $body_part_injury = $this->dao->get_body_part_injury($clause);
        foreach ($body_part_injury as $rdata) {
            $clausenew = " WHERE parent_id=" . $rdata->id;
            $getChild = $this->dao->get_body_part_injury($clausenew);
            $childArray[$rdata->id] = $getChild;
        }

        $this->beanUi->set_view_data("voilation_category", $voilation_category);
        $this->beanUi->set_view_data("incident_category", $incident_category);
        $this->beanUi->set_view_data("nature_of_injury", $nature_injury);
        $this->beanUi->set_view_data("body_part_injury", $body_part_injury);
        $this->beanUi->set_view_data("childData", $childArray);
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("activity_id", $this->bean->get_request("activity_id"));
        $this->beanUi->set_view_data("post_insert_participants", $this->dao->insert_participants());
        $this->beanUi->set_array_to_view_data($this->get_session("data"));
        $activity_id = $this->bean->get_request("activity_id");
        if (!$activity_id) {
            $this->beanUi->set_error_message("Could not load the page, activity id is missing.");
            redirect("./");
        }
    }
    public function add_incident() {
        $voilation_category = array(
            '1' => 'PPEs',
            '2' => 'SWP',
            '3' => 'Safety Standard',
            '4' => 'Job Safety - Working at Height',
            '5' => 'Job Safety - Hot Job',
            '6' => 'Job Safety - Confined Space',
            '7' => 'Job Safety - Chemical',
            '8' => 'Job Safety - Heavy Material Handling',
            '9' => 'Reaction Of People',
            '10' => 'Position Of People',
            '11' => 'Tools & Equipment',
            '12' => 'Procudure',
            '13' => 'Unsafe condition',
            '14' => 'Unsafe Act',
            '15' => 'Job Safety- Electrical Safety',
            '16' => 'Job Safety - Excavation',
            '17' => 'Safe Zone Creation'
        );

        $_action = $this->bean->get_request("_action");
        if ($_action == "Create") {
            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");
            $parent_incident_mapping= $this->bean->get_request("parent_incident_mapping");
            $parent_incident_id     = $this->bean->get_request("parent_incident_id");
            
//            pk14112018
//                $clauseAN         =   " incident_no !='' ORDER BY id DESC LIMIT 1";
//                $getAllAuditNo    =   $this->dao->fetchAllData('incident',$clauseAN);
//                $year = date('Y');
//                $month = date('m');
//                $financYear = getFinancialYear($year,$month,'y');
//                $lastId = explode('/',$getAllAuditNo[0]->incident_no);
//                $divDeptId = $this->bean->get_request("division");
//                $divDeptIdExp = explode('-',$divDeptId[0]);
//                
//                if ($divDeptIdExp[1] == 2) {
//                    $dT = "G";
//                } else if ($divDeptIdExp[1] == 3 || $divDeptIdExp[1] == 4) {
//                    $dT = "D";
//                }
//                
//                if(!empty($getAllAuditNo)) {
//                    $activity_ref_no = $dT.'/'.INCIDENT_AN.sprintf("%04d", ($lastId[2] + 1)).'/'.$financYear;
//                } else {
//                    $activity_ref_no = $dT.'/'.INCIDENT_AN.sprintf("%04d", 1).'/'.$financYear;
//                }
//            pk14112018
                
            $token_id = $this->bean->get_request("token_id");
            $data["incident_no"]            = $activity_ref_no ? $activity_ref_no : 0;
            $data["created_by"]             = $this->get_auth_user("id");
            $data["created_date"]           = date("c");
            $data["modified_by"]            = $this->get_auth_user("id");
            $data["modified_date"]          = date("c");
            $data["parent_incident_mapping"]= $parent_incident_mapping ? $parent_incident_mapping : 0;
            $data["parent_incident_id"]     = $parent_incident_id ? $parent_incident_id : 0;
            $data["incident_category_id"]   = $this->bean->get_request("incident_category_id");
            $data["incident_type_id"]       = $this->bean->get_request("incident_type_id");
            if ($this->bean->get_request("violation_category") != "") {
                $data["violation_category"] = implode(",", $this->bean->get_request("violation_category"));
            }
            if ($this->bean->get_request("nature_of_injury_id") != "") {
                $data["nature_of_injury_id"] = implode(",", $this->bean->get_request("nature_of_injury_id"));
            }
        
            $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
            $data["featured_image_path"] = $featured_img[0];

           
            $this->dao->_table = "incident";
            $activityid = $this->dao->save($data);
            $activity_logs = array();
            if ($activityid) {
                $activity_logs["status_id"] = $data["status_id"];
                $activity_logs["activity_id"] = $activityid;
                $activity_logs["activity_type_id"] = $data["activity_type_id"];
                $this->dao->_table = "activity_logs";
                $this->dao->save($activity_logs);
            }
            if (!$activityid) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
           
            /*             * ******** incident file upload ********* */
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            if (!empty($uploads)) {
                $errors = "";
                $this->dao->_table = "file_upload_incident_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "" && $activityid) {
                        $post_uploads["incident_id"] = $activityid;
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }
            /*             * ******** incident file upload ********* */

            /*             * ******** division mapping insert ********* */
            $division = $this->bean->get_request("division");
            if (!empty($division)) {
                $this->dao->_table = "incident_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["incident_id"] = $activityid;
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] =str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            /*             * ******** division mapping insert ********* */
            $this->dao->update_incident_finding_mapping($activityid, $token_id);

            /*             * ******** incident category insert ********* */
            $incident_category_id = $this->bean->get_request("incident_category_id");
            if (!empty($incident_category_id)) {
                $this->dao->_table = "incident_category_logs";
                $inc_cat = array();
                $inc_cat["incident_id"] = $activityid;
                $inc_cat["incident_category_id"] = $incident_category_id;
                $inc_cat["created_by"] = $this->get_auth_user("id");
                $inc_cat["created_date"] = date("c");
                $save_inc_category = $this->dao->save($inc_cat);
                if (!$save_inc_category) {
                    $errors .= $this->dao->get_query_error() . ",";
                }
            }
            /*             * ******** incident category insert ********* */

            /*             * ******** body part insert ********* */
            $bodypart_ids = $this->bean->get_request("bodypart_ids");
            $parent_id = $this->bean->get_request("parent_id");
            if (!empty($bodypart_ids)) {
                $this->dao->_table = "incident_body_part_injury_mapping";
                $bpart_insert = array();
                foreach ($bodypart_ids as $key => $values) {
                    $bodypart_status = $this->bean->get_request("bodypart_status_" . $bodypart_ids[$key]);
                    $bpart_insert["incident_id"] = $activityid;
                    $bpart_insert["parent_id"] = $parent_id[$key];
                    $bpart_insert["bodypart_id"] = $bodypart_ids[$key];
                    $bpart_insert["status"] = $bodypart_status;
                    $save_bpart_insert = $this->dao->save($bpart_insert);
                    if (!$save_bpart_insert) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            /*             * ******** body part insert ********* */

            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Add popular post entry
            if ($data["status_id"] == 3) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $activityid);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $activityid));
                }
            }
            // End popular post
            $this->beanUi->set_success_message("Incident is successfully added.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }

        $incident_category = $this->dao->get_incident_category();
        $nature_injury = $this->dao->get_nature_injury();
        $childArray = array();
        $clause = " WHERE parent_id=0";
        $body_part_injury = $this->dao->get_body_part_injury($clause);
        foreach ($body_part_injury as $rdata) {
            $clausenew = " WHERE parent_id=" . $rdata->id;
            $getChild = $this->dao->get_body_part_injury($clausenew);
            $childArray[$rdata->id] = $getChild;
        }

        $this->beanUi->set_view_data("voilation_category", $voilation_category);
        $this->beanUi->set_view_data("incident_category", $incident_category);
        $this->beanUi->set_view_data("nature_of_injury", $nature_injury);
        $this->beanUi->set_view_data("body_part_injury", $body_part_injury);
        $this->beanUi->set_view_data("childData", $childArray);
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("activity_id", $this->bean->get_request("activity_id"));
        $this->beanUi->set_view_data("post_insert_participants", $this->dao->insert_participants());
        $this->beanUi->set_array_to_view_data($this->get_session("data"));
        $activity_id = $this->bean->get_request("activity_id");
        if (!$activity_id) {
            $this->beanUi->set_error_message("Could not load the page, activity id is missing.");
            redirect("./");
        }
    }

       public function add_ppe_audit() {
        $_action = $this->bean->get_request("_action");
        if ($_action == "Create") {
            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");
            
//            pk14112018
//                $clauseAN         =   " audit_no !='' ORDER BY id DESC LIMIT 1";
//                $getAllAuditNo    =   $this->dao->fetchAllData('ppe_audit',$clauseAN);
//                $year = date('Y');
//                $month = date('m');
//                $financYear = getFinancialYear($year,$month,'y');
//                $lastId = explode('/',$getAllAuditNo[0]->audit_no);
//                $divDeptId = $this->bean->get_request("division");
//                $divDeptIdExp = explode('-',$divDeptId[0]);
//                
//                if ($divDeptIdExp[1] == 2) {
//                    $dT = "G";
//                } else if ($divDeptIdExp[1] == 3 || $divDeptIdExp[1] == 4) {
//                    $dT = "D";
//                }
//                
//                if(!empty($getAllAuditNo)) {
//                    $activity_ref_no = $dT.'/'.PPE_AUDIT_AN.sprintf("%04d", ($lastId[2] + 1)).'/'.$financYear;
//                } else {
//                    $activity_ref_no = $dT.'/'.PPE_AUDIT_AN.sprintf("%04d", 1).'/'.$financYear;
//                }
//            pk14112018
                
            $token_id                       = $this->bean->get_request("token_id");
            $data["audit_no"]               = $activity_ref_no ? $activity_ref_no : 0;
            $data["created_by"]             = $this->get_auth_user("id");
            $data["created_date"]           = date("c");
            $data["modified_by"]            = $this->get_auth_user("id");
            $data["modified_date"]          = date("c");
            $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
            $data["featured_image_path"] = $featured_img[0];
            $this->bean->set_data($data);
            $valid_post = $this->bean->post_validation();
            $this->dao->_table = "ppe_audit";
            $activityid = $this->dao->save($data);
           
            
            $activity_logs = array();
            if ($activityid) {
                $activity_logs["status_id"] = $data["status_id"];
                $activity_logs["activity_id"] = $activityid;
                $activity_logs["activity_type_id"] = $data["activity_type_id"];
                $this->dao->_table = "activity_logs";
                $this->dao->save($activity_logs);
            }
            //sas
            $clauseSN         =   " is_selected = '0' AND sas_report_no ='".$data["sas_report_no"]."'";
            $getSN    =   $this->dao->fetchAllData('mis_sas_ppe',$clauseSN);
            $sasNoArr = array();
            if($getSN[0]->id){
                $sasNoArr["id"] = $getSN[0]->id;
                $sasNoArr["is_selected"]= 1;
                $sasNoArr["modified_by"]            = $this->get_auth_user("id");
                $sasNoArr["modified_date"]          = date("c");
                $this->dao->_table = "mis_sas_ppe";
                $this->dao->save($sasNoArr);
            }
            $clauseSNI         =   " ppe_audit_id = '0' AND sas_report_no = '".$data["sas_report_no"]."'";
            $getSNI    =   $this->dao->fetchAllData('file_upload_ppe_audit_mapping',$clauseSNI);
            $sasImgArr = array();
            foreach ($getSNI as $kSNI => $vSNI) {
            if($vSNI->id){
                $sasImgArr["id"]                    = $vSNI->id;
                $sasImgArr["ppe_audit_id"]          = $activityid;
                $sasImgArr["modified_date"]         = date("c");
                $this->dao->_table = "file_upload_ppe_audit_mapping";
                $this->dao->save($sasImgArr);
            }   
            }
            //sas
            if (!$activityid) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }

            /*             * ******** ppe audit file upload ********* */
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            $old_image_path = $this->bean->get_request("old_image_path");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {

                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            if (!empty($uploads)) {
                $errors = "";
                $this->dao->_table = "file_upload_ppe_audit_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "" && $activityid) {
                        $post_uploads["ppe_audit_id"] = $activityid;
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }
            
            if(!empty($old_image_path)){
                foreach ($old_image_path as $key => $oldrow) {
                    //show($oldrow);
                    $myArray1 = explode('.', $oldrow);
                    $myArray = "image/".$myArray1[1];
//                    $imagename = explode('/',$oldrow);
//                    $imagename1 = $imagename[3];
                    $newimage = $myArray1[0]."_".rand().".".$myArray1[1];//show($newimage);
//                    $oldrow1 = $myArray1[0]."_".$newimage;
                    $prPathArr=  explode('/', BASE_PATH);
                    array_pop($prPathArr);                    
                    $imgPath=  implode("/",$prPathArr);
                      $oldrow1 = $imgPath.'/'.$oldrow;
                    $newimage1 = $imgPath.'/'.$newimage;
                    
                        if (copy($oldrow1, $newimage1)) { //show($newimage1);
                                
                            
                            
                    $data1["ppe_audit_id"]               = $activityid;
                    $data1["file_type"]             = $myArray;
                    $data1["file_path"]           = $newimage;
                    $data1["name"]            = '';
                    $data1["created_date"]          = date("c");
                    $data1["modified_date"]    = date("c");
                    $data1["type_id"]    = 0;
                    $this->dao->_table = "file_upload_ppe_audit_mapping";
                    $oldimgid = $this->dao->save($data1);
                        }
                }
            }
            /*             * ******** ppe_audit file upload ********* */
            /*             * ******** div dept insert ********* */
            $division = $this->bean->get_request("division");
            if (!empty($division)) {
                $this->dao->_table = "ppe_audit_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["ppe_audit_id"] = $activityid;
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            /*             * ******** div dept insert ********* */
            $this->dao->update_ppe_audit_violation_mapping($activityid, $token_id);
            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Add popular post entry
            if ($data["status_id"] == 3) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $activityid);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $activityid));
                }
            }

            // End popular post

            $this->beanUi->set_success_message("PPE Audit is successfully added.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("activity_id", $this->bean->get_request("activity_id"));
        $this->beanUi->set_view_data("post_insert_participants", $this->dao->insert_participants());
        $this->beanUi->set_array_to_view_data($this->get_session("data"));
        $activity_id = $this->bean->get_request("activity_id");
        if (!$activity_id) {
            $this->beanUi->set_error_message("Could not load the page, activity id is missing.");
            redirect("./");
        }
    }
    public function add_ppe_audit_nm() {
        $_action = $this->bean->get_request("_action");
        if ($_action == "Create") {
            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");
            
//            pk14112018
//                $clauseAN         =   " audit_no !='' ORDER BY id DESC LIMIT 1";
//                $getAllAuditNo    =   $this->dao->fetchAllData('ppe_audit',$clauseAN);
//                $year = date('Y');
//                $month = date('m');
//                $financYear = getFinancialYear($year,$month,'y');
//                $lastId = explode('/',$getAllAuditNo[0]->audit_no);
//                $divDeptId = $this->bean->get_request("division");
//                $divDeptIdExp = explode('-',$divDeptId[0]);
//                
//                if ($divDeptIdExp[1] == 2) {
//                    $dT = "G";
//                } else if ($divDeptIdExp[1] == 3 || $divDeptIdExp[1] == 4) {
//                    $dT = "D";
//                }
//                
//                if(!empty($getAllAuditNo)) {
//                    $activity_ref_no = $dT.'/'.PPE_AUDIT_AN.sprintf("%04d", ($lastId[2] + 1)).'/'.$financYear;
//                } else {
//                    $activity_ref_no = $dT.'/'.PPE_AUDIT_AN.sprintf("%04d", 1).'/'.$financYear;
//                }
//            pk14112018
                
            $token_id                       = $this->bean->get_request("token_id");
            $data["audit_no"]               = $activity_ref_no ? $activity_ref_no : 0;
            $data["created_by"]             = $this->get_auth_user("id");
            $data["created_date"]           = date("c");
            $data["modified_by"]            = $this->get_auth_user("id");
            $data["modified_date"]          = date("c");
            $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
            $data["featured_image_path"] = $featured_img[0];
            $this->bean->set_data($data);
            $valid_post = $this->bean->post_validation();
            $this->dao->_table = "ppe_audit";
            $activityid = $this->dao->save($data);
           
            
            $activity_logs = array();
            if ($activityid) {
                $activity_logs["status_id"] = $data["status_id"];
                $activity_logs["activity_id"] = $activityid;
                $activity_logs["activity_type_id"] = $data["activity_type_id"];
                $this->dao->_table = "activity_logs";
                $this->dao->save($activity_logs);
            }
            
            if (!$activityid) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }

            /*             * ******** ppe audit file upload ********* */
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {

                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            if (!empty($uploads)) {
                $errors = "";
                $this->dao->_table = "file_upload_ppe_audit_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "" && $activityid) {
                        $post_uploads["ppe_audit_id"] = $activityid;
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }
            /*             * ******** ppe_audit file upload ********* */
            /*             * ******** div dept insert ********* */
            $division = $this->bean->get_request("division");
            if (!empty($division)) {
                $this->dao->_table = "ppe_audit_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["ppe_audit_id"] = $activityid;
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            /*             * ******** div dept insert ********* */
            $this->dao->update_ppe_audit_violation_mapping($activityid, $token_id);
            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Add popular post entry
            if ($data["status_id"] == 3) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $activityid);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $activityid));
                }
            }

            // End popular post

            $this->beanUi->set_success_message("PPE Audit is successfully added.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("activity_id", $this->bean->get_request("activity_id"));
        $this->beanUi->set_view_data("post_insert_participants", $this->dao->insert_participants());
        $this->beanUi->set_array_to_view_data($this->get_session("data"));
        $activity_id = $this->bean->get_request("activity_id");
        if (!$activity_id) {
            $this->beanUi->set_error_message("Could not load the page, activity id is missing.");
            redirect("./");
        }
    }

    public function add_safety_observation() {

        $_action = $this->bean->get_request("_action");
        if ($_action == "Create") {

            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");
            
//            pk14112018
//                $clauseAN         =   " activity_no !='' ORDER BY id DESC LIMIT 1";
//                $getAllAuditNo    =   $this->dao->fetchAllData('safety_observation',$clauseAN);
//                $year = date('Y');
//                $month = date('m');
//                $financYear = getFinancialYear($year,$month,'y');
//                $lastId = explode('/',$getAllAuditNo[0]->activity_no);
//                $divDeptId = $this->bean->get_request("division");
//                $divDeptIdExp = explode('-',$divDeptId[0]);
//                
//                if ($divDeptIdExp[1] == 2) {
//                    $dT = "G";
//                } else if ($divDeptIdExp[1] == 3 || $divDeptIdExp[1] == 4) {
//                    $dT = "D";
//                }
//                
//                if(!empty($getAllAuditNo)) {
//                    $activity_ref_no = $dT.'/'.SAFETY_OBSERVATION_AN.sprintf("%04d", ($lastId[2] + 1)).'/'.$financYear;
//                } else {
//                    $activity_ref_no = $dT.'/'.SAFETY_OBSERVATION_AN.sprintf("%04d", 1).'/'.$financYear;
//                }
//            pk14112018
            
            $token_id = $this->bean->get_request("token_id");
            $data["activity_no"]                = $activity_ref_no ? $activity_ref_no : 0;
            $data["created_by"]                 = $this->get_auth_user("id");
            $data["created_date"]               = date("c");
            $data["modified_by"]                = $this->get_auth_user("id");
            $data["modified_date"]              = date("c");
            $this->bean->set_data($data);
            $valid_post = $this->bean->post_validation();
            $this->dao->_table = "safety_observation";
            $activityid = $this->dao->save($data);
            $activity_logs = array();
            if ($activityid) {
                $activity_logs["status_id"] = $data["status_id"];
                $activity_logs["activity_id"] = $activityid;
                $activity_logs["activity_type_id"] = $data["activity_type_id"];
                $this->dao->_table = "activity_logs";
                $this->dao->save($activity_logs);
            }
            if (!$activityid) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }

            /*             * ****div dept insert**** */
            $division = $this->bean->get_request("division");
            if (!empty($division)) {
                $this->dao->_table = "safety_observation_division_mapping";
                foreach ($division as $drow => $k) {
                     $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["safety_observation_id"] = $activityid;
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] =str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }
            // Add popular post entry
            if ($data["status_id"] == 3) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $activityid);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $activityid));
                }
            }
            // End popular post			
            $this->beanUi->set_success_message("Safety observation is successfully added.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }

        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("activity_id", $this->bean->get_request("activity_id"));
        $this->beanUi->set_view_data("post_insert_participants", $this->dao->insert_participants());
        $this->beanUi->set_array_to_view_data($this->get_session("data"));
        $activity_id = $this->bean->get_request("activity_id");
        if (!$activity_id) {
            $this->beanUi->set_error_message("Could not load the page, activity id is missing.");
            redirect("./");
        }
    }

    public function add_mom() {

        $_action = $this->bean->get_request("_action");
        if ($_action == "Create") {
            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");
            $token_id = $this->bean->get_request("token_id");
            $data["created_by"] = $this->get_auth_user("id");
            $data["created_date"] = date("c");
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
            $data["featured_image_path"] = $featured_img[0];

            $this->bean->set_data($data);
            $valid_post = $this->bean->post_validation();

            // Save post
            $this->dao->_table = "minutes_of_meeting";
            $activityid = $this->dao->save($data);
            $activity_logs = array();
            if ($activityid) {
                $activity_logs["status_id"] = $data["status_id"];
                $activity_logs["activity_id"] = $activityid;
                $activity_logs["activity_type_id"] = $data["activity_type_id"];
                $this->dao->_table = "activity_logs";
                $this->dao->save($activity_logs);
            }
            if (!$activityid) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }


            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }
                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            if (!empty($uploads)) {
                $errors = "";
                $this->dao->_table = "file_upload_mom_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "" && $activityid) {
                        $post_uploads["mom_id"] = $activityid;
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }

            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Add popular post entry
            if ($data["status_id"] == 3) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $activityid);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $activityid));
                }
            }
            // End popular post			
            $this->beanUi->set_success_message("MOM is successfully added.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }

        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("activity_id", $this->bean->get_request("activity_id"));
        $this->beanUi->set_view_data("post_insert_participants", $this->dao->insert_participants());
        $this->beanUi->set_array_to_view_data($this->get_session("data"));
        $activity_id = $this->bean->get_request("activity_id");
        if (!$activity_id) {
            $this->beanUi->set_error_message("Could not load the page, activity id is missing.");
            redirect("./");
        }
    }

    public function add_safety_observation_line_function() {
        $_action = $this->bean->get_request("_action");
        $framework = $this->dao->getFramework();
        $arraydata = array();
        foreach ($framework as $row) {
            $arraydata[$row->id] = $this->dao->getFrameworkValue($row->id);
        }

        $this->beanUi->set_view_data("framework_value", $arraydata);
        $this->beanUi->set_view_data("framework", $framework);
        if ($_action == "Create") {

            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");
            $token_id = $this->bean->get_request("token_id");
            $data["created_by"] = $this->get_auth_user("id");
            $data["created_date"] = date("c");
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $this->dao->_table = "safety_observation_line_function";


            $activityid = $this->dao->save($data);
            //
            $activity_logs = array();
            if ($activityid) {
                $activity_logs["status_id"] = $data["status_id"];
                $activity_logs["activity_id"] = $activityid;
                $activity_logs["activity_type_id"] = $data["activity_type_id"];
                $this->dao->_table = "activity_logs";
                $this->dao->save($activity_logs);
            }
            if (!$activityid) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            $framework_count = $this->bean->get_request("framework_count");
            $framework_id = $this->bean->get_request("framework_id");
            if (!empty($framework_count)) {
                $this->dao->_table = "safety_observation_framework_mapping";
                foreach ($framework_count as $frow => $kf) {
                    $framework_mapping = array();
                    $framework_mapping["line_function_id"] = $activityid;
                    $framework_mapping["framework_id"] = $framework_id[$frow];
                    $framework_mapping["framework_count"] = $framework_count[$frow];
                    $framework_mapping["created_by"] = $this->get_auth_user("id");
                    $framework_mapping["created_date"] = date("c");
                    if ($framework_count[$frow] > 0) {
                        $save_framework_mapping = $this->dao->save($framework_mapping);
                    }
                    if (!$save_framework_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }

            //file upload            
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            if (!empty($uploads)) {
                $errors = "";
                $this->dao->_table = "file_upload_safety_observation_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "" && $activityid) {
                        $post_uploads["line_function_id"] = $activityid;
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }


            //Division Mapping Insert
            $division = $this->bean->get_request("division");
            if (!empty($division)) {
                $this->dao->_table = "safety_observation_line_function_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["line_function_id"] = $activityid;
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            $this->dao->update_mappingID($activityid, $token_id, "line_function_id", "safety_observation_line_function_deviation_mapping");

            // Add popular post entry
            if ($data["status_id"] == 3) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $activityid);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $activityid));
                }
            }

            // End popular post

            $this->beanUi->set_success_message("Safety observation line function is successfully added.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }

        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("activity_id", $this->bean->get_request("activity_id"));
        $this->beanUi->set_view_data("post_insert_participants", $this->dao->insert_participants());
        $this->beanUi->set_array_to_view_data($this->get_session("data"));
        $activity_id = $this->bean->get_request("activity_id");
        if (!$activity_id) {
            $this->beanUi->set_error_message("Could not load the page, activity id is missing.");
            redirect("./");
        }
    }

    public function p_add() {
        $data["emp_code"] = $this->bean->get_request("emp_code");
        $data["no_of_emp"] = $this->bean->get_request("no_of_emp");
        $data["activity_id"] = $this->bean->get_request("activity_id");
        $data["participant_cat_id"] = $this->bean->get_request("participant_cat_id");
        $data["name"] = $this->bean->get_request("name");
        $data["designation"] = $this->bean->get_request("designation");
        $data["department"] = $this->bean->get_request("department");
        $data["created_by"] = $this->get_auth_user("id");
        $data["created_date"] = date("c");
        $this->dao->_table = "activity_participants_mapping";
        $asd = $this->dao->save($data);
        if ($asd) {
            $this->dao->update_participants_mapping($data["no_of_emp"], $data["activity_id"], $data["participant_cat_id"], "U");
            $newtr = '<tr id="message_' . $asd . '"><td align="center">' . $data["no_of_emp"] . '</td><td align="center">' . $data["designation"] . '</td><td align="center">' . $data["department"] . '</td>' .
                    '<td align="center" width="25%"><a style="cursor:pointer;"  name="delete"  href="edit.php?action=p_delete&message_id=' . $asd . '&activity_id=' . $data["activity_id"] . '&no_of_emp=' . $data["no_of_emp"] . '&participant_cat_id=' . $data["participant_cat_id"] . '" class="deleteRecord"><i class="fa fa-trash"></i></a></td></tr>';
        } else {
            $newtr = "";
        }

        die($newtr);
    }

    public function f_add() {
        $data["emp_code"] = $this->bean->get_request("emp_code");
        $data["activity_id"] = $this->bean->get_request("activity_id");
        $data["participant_cat_id"] = $this->bean->get_request("participant_cat_id");
        $data["name"] = $this->bean->get_request("name");
        $data["designation"] = $this->bean->get_request("designation");
        $data["department"] = $this->bean->get_request("department");
        $data["created_by"] = $this->get_auth_user("id");
        $data["created_date"] = date("c");
        $this->dao->_table = "activity_participants_mapping";
        $asd = $this->dao->save($data);
    }

    public function finding_add() {
        $data["incident_id"] = $this->bean->get_request("activity_id");
        $data["description"] = $this->bean->get_request("description");
        $data["action_taken"] = $this->bean->get_request("action_taken");
        $data["compliance_desc"] = $this->bean->get_request("compliance_desc");
        $data["compliance_date"] = $this->bean->get_request("compliance_date");
        $this->dao->_table = "incident_finding_mapping";
        $asd = $this->dao->save($data);
    }

    public function save_participants() {
        $emp_codes = $this->bean->get_request("emp_codes");
        $emp_name = $this->bean->get_request("emp_name");
        $no_of_emp = $this->bean->get_request("no_of_emp");
        $designation = $this->bean->get_request("designation");
        $designation2 = $this->bean->get_request("designation2");
        $department = $this->bean->get_request("department");
        $token_id = $this->bean->get_request("token_id");
        $table_name = $this->bean->get_request("table_name");
        $no_of_parti = $this->bean->get_request("no_of_parti");
        $participant_cat_id = $this->bean->get_request("participant_cat_id");
        $noe = "";
        $related_Participants = count($this->dao->getRelatedParticipants($token_id, $participant_cat_id));
        if ($related_Participants > 0) {
            $this->dao->deleteRelatedParticipants($token_id, $participant_cat_id);
        }

        for ($i = 0; $i <= $no_of_parti; $i++) {
            $data["emp_code"] = ($emp_codes[$i] != '') ? $emp_codes[$i] : '';
            $data["no_of_emp"] = ($no_of_emp[$i] != '') ? trim($no_of_emp[$i]) : '';
            $data["name"] = ($emp_name[$i] != '') ? $emp_name[$i] : '';
            $data["designation"] = ($designation[$i] != '') ? $designation[$i] : '';
            $data["department"] = ($department[$i] != '') ? $department[$i] : '';
            $data["designation_2"] = ($designation2[$i] != '') ? $designation2[$i] : '';
            $data["token_id"] = $token_id;

            $data["participant_cat_id"] = $participant_cat_id;
            $data["created_by"] = $this->get_auth_user("id");
            $data["created_date"] = date("c");
            $noe += $no_of_emp[$i];

            $data2["employee_code"] = ($emp_codes[$i] != '') ? $emp_codes[$i] : '';
            $data2["full_name"] = ($emp_name[$i] != '') ? $emp_name[$i] : '';
            $data2["designation"] = ($designation[$i] != '') ? $designation[$i] : '';
            $data2["department"] = ($department[$i] != '') ? $department[$i] : '';
            $data2["role_id"] = 2;
            if ($no_of_emp[$i] != '') {
                $emp_code_exists = $this->dao->check_emp_code_exists($data2["employee_code"]);
                if (!$emp_code_exists) {
                    $this->dao->_table = "master_employees";
                    $user_inserted = $this->dao->save($data2);
                } else {
                    $query = $this->dao->db->prepare("UPDATE master_employees SET role_id=:role_id, full_name=:full_name, designation=:designation, department=:department WHERE employee_code = :employee_code");
                    $query->execute(array(":role_id" => $data2["role_id"], ":full_name" => $data2["full_name"], ":designation" => $data2["designation"], ":department" => $data2["department"], ":employee_code" => $data2["employee_code"]));
                }

                $this->dao->_table = $table_name;
                $participants = $this->dao->save($data);
            }
        }


        if (!empty($no_of_emp)) {
            echo $noe;
            die;
        } else {
            
        }
    }

    public function save_participants_for_edit() {
        $emp_codes = $this->bean->get_request("emp_codes");
        $emp_name = $this->bean->get_request("emp_name");
        $no_of_emp = $this->bean->get_request("no_of_emp");
        $designation = $this->bean->get_request("designation");
        $designation2 = $this->bean->get_request("designation2");
        $department = $this->bean->get_request("department");
        $token_id = $this->bean->get_request("token_id");
        $table_name = $this->bean->get_request("table_name");
        $no_of_parti = $this->bean->get_request("no_of_parti");
        $activity_id = $this->bean->get_request("activity_id");
        $participant_cat_id = $this->bean->get_request("participant_cat_id");
        $noe = "";
        $related_Participants = count($this->dao->getRelatedParticipants($token_id));
        if ($related_Participants > 0) {
            $this->dao->deleteRelatedParticipants($token_id);
        }
        for ($i = 0; $i <= $no_of_parti; $i++) {
            $data["emp_code"] = ($emp_codes[$i] != '') ? $emp_codes[$i] : '';
            $data["no_of_emp"] = ($no_of_emp[$i] != '') ? trim($no_of_emp[$i]) : '';
            $data["name"] = ($emp_name[$i] != '') ? $emp_name[$i] : '';
            $data["designation"] = ($designation[$i] != '') ? $designation[$i] : '';
            $data["designation_2"] = ($designation2[$i] != '') ? $designation2[$i] : '';
            $data["department"] = ($department[$i] != '') ? $department[$i] : '';
            $data["activity_id"] = ($activity_id != '') ? $activity_id : '';
            $data["token_id"] = $token_id;
            $data["participant_cat_id"] = $participant_cat_id;
            $data["created_by"] = $this->get_auth_user("id");
            $data["created_date"] = date("c");
            $noe += $no_of_emp[$i];

            $data2["employee_code"] = ($emp_codes[$i] != '') ? $emp_codes[$i] : '';
            $data2["full_name"] = ($emp_name[$i] != '') ? $emp_name[$i] : '';
            $data2["designation"] = ($designation[$i] != '') ? $designation[$i] : '';
            $data2["department"] = ($department[$i] != '') ? $department[$i] : '';
            $data2["role_id"] = 2;
            if ($no_of_emp[$i] != '') {
                if ($emp_codes[$i] != '') {
                    $emp_code_exists = $this->dao->check_emp_code_exists($data2["employee_code"]);
                    if (!$emp_code_exists) {
                        $this->dao->_table = "master_employees";
                        $user_inserted = $this->dao->save($data2);
                    } else {
                        $query = $this->dao->db->prepare("UPDATE master_employees SET role_id=:role_id, full_name=:full_name, designation=:designation, department=:department WHERE employee_code = :employee_code");
                        $query->execute(array(":role_id" => $data2["role_id"], ":full_name" => $data2["full_name"], ":designation" => $data2["designation"], ":department" => $data2["department"], ":employee_code" => $data2["employee_code"]));
                    }
                }

                $this->dao->_table = $table_name;
                $user_inserted = $this->dao->save($data);
                if ($user_inserted) {
                    $total_inserted += 1;

                    $val .= '<tr class="removetr appendrow" id="rm' . $user_inserted . '">
						<td align="center">' . $data["no_of_emp"] . '</td>
						<td align="center">' . $data["designation"] . '</td>
						<td align="center">' . $data["department"] . '</td>
						<td class="center" width="25%" align="center">
						<a id="dataremove" class="btn btn-danger btn-sm ajxbtnrmv" name="delete" data-acid="' . $user_inserted . '" data-action="p_delete" data-parti="'.$participant_cat_id.'" data-noofemp="'.$data["no_of_emp"].'" style="cursor:pointer;">
						<i class="fa fa-trash"></i></a>
						
						</td>
						</tr>';

                    $this->dao->update_participants_mapping($data["no_of_emp"], $data["activity_id"], $data["participant_cat_id"], "U");
                }
            }
        }
        if ($total_inserted >= 1) {

            $val .= '<input type="hidden" class="total_count_'.$participant_cat_id.'" value="' . $noe . '" />';
            echo $val;
            die;
        } else {
            $val .= '<input type="hidden" class="total_count2_'.$participant_cat_id.'" value="' . $noe . '" />';
            echo $val;
            die;
        }
    }

    public function save_faculty() {
        $emp_codes = $this->bean->get_request("emp_codes");
        $emp_name = $this->bean->get_request("emp_name");
        $designation = $this->bean->get_request("designation");
        $department = $this->bean->get_request("department");
        $token_id = $this->bean->get_request("token_id");
        $no_of_parti = $this->bean->get_request("no_of_parti");
        $table_name = $this->bean->get_request("table_name");
        $participant_cat_id = $this->bean->get_request("participant_cat_id");
        $related_Participants = count($this->dao->getRelatedParticipants($token_id, $participant_cat_id));
        if ($related_Participants > 0) {
            $this->dao->deleteRelatedParticipants($token_id, $participant_cat_id);
        }
        for ($i = 0; $i <= $no_of_parti; $i++) {
            $data["emp_code"] = ($emp_codes[$i] != '') ? $emp_codes[$i] : '';
            $data["name"] = ($emp_name[$i] != '') ? $emp_name[$i] : '';
            $data["designation"] = ($designation[$i] != '') ? $designation[$i] : '';
            $data["department"] = ($department[$i] != '') ? $department[$i] : '';
            $data["token_id"] = $token_id;
            $data["participant_cat_id"] = $participant_cat_id;
            $data["created_by"] = $this->get_auth_user("id");
            $data["created_date"] = date("c");

            $data2["employee_code"] = ($emp_codes[$i] != '') ? $emp_codes[$i] : '';
            $data2["full_name"] = ($emp_name[$i] != '') ? $emp_name[$i] : '';
            $data2["designation"] = ($designation[$i] != '') ? $designation[$i] : '';
            $data2["department"] = ($department[$i] != '') ? $department[$i] : '';
            $data2["role_id"] = 2;
            $total_inserted = "";
            if ($participant_cat_id == 1) {

                if ($emp_codes[$i] != '') {
                    $emp_code_exists = $this->dao->check_emp_code_exists($data2["employee_code"]);
                    if (!$emp_code_exists) {
                        $this->dao->_table = "master_employees";
                        $user_inserted = $this->dao->save($data2);
                    } else {
                        $query = $this->dao->db->prepare("UPDATE master_employees SET role_id=:role_id, full_name=:full_name, designation=:designation, department=:department WHERE employee_code = :employee_code");
                        $query->execute(array(":role_id" => $data2["role_id"], ":full_name" => $data2["full_name"], ":designation" => $data2["designation"], ":department" => $data2["department"], ":employee_code" => $data2["employee_code"]));
                    }
                }
                $this->dao->_table = $table_name;
                $savefaculty = $this->dao->save($data);
                if ($savefaculty) {
                    $total_inserted += 1;
                }
            } else if ($participant_cat_id == 2) {
                $this->dao->_table = $table_name;
                $savefaculty = $this->dao->save($data);
                if ($savefaculty) {
                    $total_inserted += 1;
                }
            }
        }
    }

    public function save_faculty_for_edit() {
        $emp_codes = $this->bean->get_request("emp_codes");
        $emp_name = $this->bean->get_request("emp_name");
        $designation = $this->bean->get_request("designation");
        $department = $this->bean->get_request("department");
        $token_id = $this->bean->get_request("token_id");
        $no_of_parti = $this->bean->get_request("no_of_parti");
        $activity_id = $this->bean->get_request("activity_id");
        $table_name = $this->bean->get_request("table_name");
        $participant_cat_id = $this->bean->get_request("participant_cat_id");
        $related_Participants = count($this->dao->getRelatedParticipants($token_id, $participant_cat_id));
        if ($related_Participants > 0) {
            $this->dao->deleteRelatedParticipants($token_id, $participant_cat_id);
        }
        for ($i = 0; $i <= $no_of_parti; $i++) {
            $data["emp_code"] = ($emp_codes[$i] != '') ? $emp_codes[$i] : '';
            $data["name"] = ($emp_name[$i] != '') ? $emp_name[$i] : '';
            $data["designation"] = ($designation[$i] != '') ? $designation[$i] : '';
            $data["department"] = ($department[$i] != '') ? $department[$i] : '';
            $data["token_id"] = $token_id;
            $data["activity_id"] = ($activity_id != '') ? $activity_id : '';
            $data["participant_cat_id"] = $participant_cat_id;
            $data["created_by"] = $this->get_auth_user("id");
            $data["created_date"] = date("c");

            $data2["employee_code"] = ($emp_codes[$i] != '') ? $emp_codes[$i] : '';
            $data2["full_name"] = ($emp_name[$i] != '') ? $emp_name[$i] : '';
            $data2["designation"] = ($designation[$i] != '') ? $designation[$i] : '';
            $data2["department"] = ($department[$i] != '') ? $department[$i] : '';
            $data2["role_id"] = 2;
            $total_inserted = "";
            if ($participant_cat_id == 1) {

                if ($emp_codes[$i] != '') {
                    $emp_code_exists = $this->dao->check_emp_code_exists($data2["employee_code"]);
                    if (!$emp_code_exists) {
                        $this->dao->_table = "master_employees";
                        $user_inserted = $this->dao->save($data2);
                    } else {
                        $query = $this->dao->db->prepare("UPDATE master_employees SET role_id=:role_id, full_name=:full_name, designation=:designation, department=:department WHERE employee_code = :employee_code");
                        $query->execute(array(":role_id" => $data2["role_id"], ":full_name" => $data2["full_name"], ":designation" => $data2["designation"], ":department" => $data2["department"], ":employee_code" => $data2["employee_code"]));
                    }
                }
                if ($emp_codes[$i] != '' || $emp_name[$i] != '' || $designation[$i] != '' || $department[$i] != '') {
                    $this->dao->_table = $table_name;
                    $savefaculty = $this->dao->save($data);
                    if ($savefaculty) {
                        $total_inserted += 1;
                    }
                }
            } else if ($participant_cat_id == 2) {
                if ($emp_name[$i] != '' || $designation[$i] != '') {
                    $this->dao->_table = $table_name;
                    $savefaculty = $this->dao->save($data);
                    if ($savefaculty) {
                        $total_inserted += 1;
                    }
                }
            }
        }
    }

    public function save_findings() {

        $description = $this->bean->get_request("description");
        $condition = $this->bean->get_request("condition");
        $incident_id = $this->bean->get_request("incident_id");
        $action_taken = $this->bean->get_request("action_taken");
        $compliance_desc = $this->bean->get_request("compliance_desc");
        $compliance_date = $this->bean->get_request("compliance_date");
        $token_id = $this->bean->get_request("token_id");
        $nof = $this->bean->get_request("nof");
        $incident_findings = count($this->dao->getRelatedFindings($token_id));
        if ($incident_id == '') {
            if ($incident_findings > 0) {
                $this->dao->deleteRelatedFindings($token_id);
            }
        }
        $data2 = array();
        $total_inserted = '';
        for ($i = 0; $i <= $nof; $i++) {
            $data2["description"] = ($description[$i] != '') ? $description[$i] : '';
            $data2["conditions"] = ($condition[$i] != '') ? $condition[$i] : '';
            $data2["action_taken"] = ($action_taken[$i] != '') ? $action_taken[$i] : '';
            $data2["compliance_desc"] = ($compliance_desc[$i] != '') ? $compliance_desc[$i] : '';
            $data2["compliance_date"] = ($compliance_date[$i] != '') ? $compliance_date[$i] : '';
            $data2["token_id"] = $token_id;
            $data2["incident_id"] = $incident_id;

            if ($description[$i] != '') {

                $this->dao->_table = "incident_finding_mapping";
                $user_inserted = $this->dao->save($data2);
                if ($user_inserted) {
                    $total_inserted += 1;
                }
            }
        }

        $incident_finding_no = count($this->dao->getincidentFindings($incident_id));
        if ($incident_id != '') {
            $this->dao->updateincidentFindings($incident_id, $incident_finding_no);

            echo $incident_finding_no;
            die;
        } else {
            echo $total_inserted;
            die;
        }
    }

    public function save_findings_edit() {

        $description = $this->bean->get_request("description");
        $condition = $this->bean->get_request("condition");
        $incident_id = $this->bean->get_request("incident_id");
        $action_taken = $this->bean->get_request("action_taken");
        $compliance_desc = $this->bean->get_request("compliance_desc");
        $compliance_date = $this->bean->get_request("compliance_date");
        $token_id = $this->bean->get_request("token_id");
        $nof = $this->bean->get_request("nof");
        $incident_findings = count($this->dao->getRelatedFindings($token_id));
        if ($incident_id == '') {
            if ($incident_findings > 0) {
                $this->dao->deleteRelatedFindings($token_id);
            }
        }
        $data2 = array();
        $total_inserted = '';
        for ($i = 0; $i <= $nof; $i++) {
            $data2["description"] = ($description[$i] != '') ? $description[$i] : '';
            $data2["conditions"] = ($condition[$i] != '') ? $condition[$i] : '';
            $data2["action_taken"] = ($action_taken[$i] != '') ? $action_taken[$i] : '';
            $data2["compliance_desc"] = ($compliance_desc[$i] != '') ? $compliance_desc[$i] : '';
            $data2["compliance_date"] = ($compliance_date[$i] != '') ? $compliance_date[$i] : '';
            $data2["token_id"] = $token_id;
            $data2["incident_id"] = $incident_id;

            if ($description[$i] != '') {

                $this->dao->_table = "incident_finding_mapping";
                $user_inserted = $this->dao->save($data2);
                if ($user_inserted) {
                    $total_inserted += 1;
                    $val .= '<tr class="removetr appendrow" id="rm' . $user_inserted . '">
                        <td align="center"></td>
						<td align="center">' . $data2["description"] . '</td>
						<td align="center">' . $data2["conditions"] . '</td>
						<td align="center">' . $data2["action_taken"] . '</td>
						<td class="center" width="25%" align="center">
						<a id="dataremove" class="btn btn-danger btn-sm ajxbtnrmv" name="delete" data-acid="' . $user_inserted . '" data-action="finding_delete" style="cursor:pointer;">
						<i class="fa fa-trash"></i></a>
						<input type="hidden" class="total_count" value="' . $nof . '" />
						</td>
						</tr>';
                }
            }
        }

        $incident_finding_no = count($this->dao->getincidentFindings($incident_id));
        if ($incident_id != '') {
            $this->dao->updateincidentFindings($incident_id, $incident_finding_no);
        } else {
            
        }
        if ($total_inserted >= 1) {
            echo $val;
            die;
        } else {
            $val .= '<input type="hidden" class="total_count2" value="' . $nof . '" />';
            echo $val;
            die;
        }
    }

    public function save_violation() {
        $violation_type = $this->bean->get_request("violation_type");
        $description = $this->bean->get_request("description");
        $violation_category = $this->bean->get_request("violation_category");
        $audit_id = $this->bean->get_request("audit_id");
        $remarks = $this->bean->get_request("remarks");
        $token_id = $this->bean->get_request("token_id");
        $nof = $this->bean->get_request("nof");
        $related_violation = count($this->dao->getRelatedViolation($token_id));
        if ($audit_id == '') {
            if ($related_violation > 0) {
                $this->dao->deleteRelatedViolation($token_id);
            }
        }
        $data2 = array();
        $total_inserted = '';
        $val = "";
        for ($i = 0; $i <= $nof; $i++) {
            $data2["violation_type"] = ($violation_type[$i] != '') ? $violation_type[$i] : '';
            $data2["description"] = ($description[$i] != '') ? $description[$i] : '';
            $data2["violation_category"] = ($violation_category[$i] != '') ? $violation_category[$i] : '';
            $data2["remarks"] = ($remarks[$i] != '') ? $remarks[$i] : '';
            $data2["token_id"] = $token_id;
            $data2["audit_id"] = $audit_id;

            if ($description[$i] != '') {
                $this->dao->_table = "audit_violation_mapping";
                $user_inserted = $this->dao->save($data2);
                if ($user_inserted) {
                    $total_inserted += 1;
                }
            }
        }
        $auditvgiolno = count($this->dao->getAuditViolation($audit_id));
        if ($audit_id) {

            $this->dao->updateAuditViolation($audit_id, $auditvgiolno);
            echo $auditvgiolno;
            die;
        } else {
            echo $total_inserted;
            die;
        }
    }

    public function save_violation_edit() {
        $violation_type = $this->bean->get_request("violation_type");
        $description = $this->bean->get_request("description");
        $violation_category = $this->bean->get_request("violation_category");
        $audit_id = $this->bean->get_request("audit_id");
        $remarks = $this->bean->get_request("remarks");
        $token_id = $this->bean->get_request("token_id");
        $nof = $this->bean->get_request("nof");
        $related_violation = count($this->dao->getRelatedViolation($token_id));
        if ($audit_id == '') {
            if ($related_violation > 0) {
                $this->dao->deleteRelatedViolation($token_id);
            }
        }
        $data2 = array();
        $total_inserted = '';
        $val = "";
        for ($i = 0; $i <= $nof; $i++) {
            $data2["violation_type"] = ($violation_type[$i] != '') ? $violation_type[$i] : '';
            $data2["description"] = ($description[$i] != '') ? $description[$i] : '';
            $data2["violation_category"] = ($violation_category[$i] != '') ? $violation_category[$i] : '';
            $data2["remarks"] = ($remarks[$i] != '') ? $remarks[$i] : '';
            $data2["token_id"] = $token_id;
            $data2["audit_id"] = $audit_id;

            if ($description[$i] != '') {
                $this->dao->_table = "audit_violation_mapping";
                $user_inserted = $this->dao->save($data2);
                if ($user_inserted) {
                    $total_inserted += 1;
                    $val .= '<tr class="removetr appendrow" id="rm' . $user_inserted . '">
                        <td align="center"></td>
						<td align="center">' . $data2["violation_type"] . '</td>
						<td align="center">' . $data2["description"] . '</td>
						<td align="center">' . $data2["violation_category"] . '</td>
						<td align="center">' . $data2["remarks"] . '</td>
						<td class="center" width="25%" align="center">
						<a id="dataremove" class="btn btn-danger btn-sm ajxbtnrmv" name="delete" data-acid="' . $user_inserted . '" data-action="violation_delete" style="cursor:pointer;">
						<i class="fa fa-trash"></i></a>
						<input type="hidden" class="total_count" value="' . $nof . '" />
						</td>
						</tr>';
                }
            }
        }
        $auditvgiolno = count($this->dao->getAuditViolation($audit_id));
        if ($audit_id) {

            $this->dao->updateAuditViolation($audit_id, $auditvgiolno);
        } else {
            
        }

        if ($total_inserted >= 1) {
            echo $val;
            die;
        } else {
            $val .= '<input type="hidden" class="total_count2" value="' . $nof . '" />';
            echo $val;
            die;
        }
    }

    public function save_deficiency() {
        $description = $this->bean->get_request("description");
        $qty = $this->bean->get_request("qty");
        $ppe_audit_id = $this->bean->get_request("ppe_audit_id");
        $remarks = $this->bean->get_request("remarks");
        $token_id = $this->bean->get_request("token_id");
        $nof = $this->bean->get_request("nof");
        $related_deviation = count($this->dao->getRelatedDeviation($token_id));
        if ($ppe_audit_id == '') {
            if ($related_deviation > 0) {
                $this->dao->deleteRelatedDeviation($token_id);
            }
        }
        $data2 = array();
        $total_inserted = '';
        for ($i = 0; $i <= $nof; $i++) {
            $data2["description"] = ($description[$i] != '') ? $description[$i] : '';
            $data2["qty"] = ($qty[$i] != '') ? $qty[$i] : '';
            $data2["remarks"] = ($remarks[$i] != '') ? $remarks[$i] : '';
            $data2["token_id"] = $token_id;
            $data2["ppe_audit_id"] = $ppe_audit_id;

            if ($description[$i] != '') {
                $this->dao->_table = "ppe_audit_deviation_mapping";
                $user_inserted = $this->dao->save($data2);
                if ($user_inserted) {
                    $total_inserted += 1;
                }
            }
        }
        $ppeauditdeviation = count($this->dao->getAuditDeviation($ppe_audit_id));
        if ($ppe_audit_id) {
            $this->dao->updateAuditDeviation($ppe_audit_id, $ppeauditdeviation);

            echo $ppeauditdeviation;
            die;
        } else {
            echo $total_inserted;
            die;
        }
    }

    public function save_deficiency_edit() {
        $description = $this->bean->get_request("description");
        $qty = $this->bean->get_request("qty");
        $ppe_audit_id = $this->bean->get_request("ppe_audit_id");
        $remarks = $this->bean->get_request("remarks");
        $token_id = $this->bean->get_request("token_id");
        $nof = $this->bean->get_request("nof");
        $related_deviation = count($this->dao->getRelatedDeviation($token_id));
        if ($ppe_audit_id == '') {
            if ($related_deviation > 0) {
                $this->dao->deleteRelatedDeviation($token_id);
            }
        }
        $data2 = array();
        $total_inserted = '';
        for ($i = 0; $i <= $nof; $i++) {
            $data2["description"] = ($description[$i] != '') ? $description[$i] : '';
            $data2["qty"] = ($qty[$i] != '') ? $qty[$i] : '';
            $data2["remarks"] = ($remarks[$i] != '') ? $remarks[$i] : '';
            $data2["token_id"] = $token_id;
            $data2["ppe_audit_id"] = $ppe_audit_id;

            if ($description[$i] != '') {
                $this->dao->_table = "ppe_audit_deviation_mapping";
                $user_inserted = $this->dao->save($data2);
                if ($user_inserted) {
                    $total_inserted += 1;
                    $val .= '<tr class="removetr appendrow" id="rm' . $user_inserted . '">
                        <td align="center"></td>
						<td align="center">' . $data2["description"] . '</td>
						<td align="center">' . $data2["qty"] . '</td>
						<td align="center">' . $data2["remarks"] . '</td>
						<td class="center" width="25%" align="center">
						<a id="dataremove" class="btn btn-danger btn-sm ajxbtnrmv" name="delete" data-acid="' . $user_inserted . '" data-action="deficiency_delete" style="cursor:pointer;">
						<i class="fa fa-trash"></i></a>
						<input type="hidden" class="total_count" value="' . $nof . '" />
						</td>
						</tr>';
                }
            }
        }
        $ppeauditdeviation = count($this->dao->getAuditDeviation($ppe_audit_id));
        if ($ppe_audit_id) {
            $this->dao->updateAuditDeviation($ppe_audit_id, $ppeauditdeviation);
        } else {
            
        }

        if ($total_inserted >= 1) {
            echo $val;
            die;
        } else {
            $val .= '<input type="hidden" class="total_count2" value="' . $nof . '" />';
            echo $val;
            die;
        }
    }

    public function save_deviation_line_function() {

        $description = $this->bean->get_request("description");
        $category = $this->bean->get_request("category");
        $line_function_id = $this->bean->get_request("line_function_id");
        $action_taken = $this->bean->get_request("action_taken");
        $correction_desc = $this->bean->get_request("correction_desc");
        $correction_date = $this->bean->get_request("correction_date");
        $token_id = $this->bean->get_request("token_id");
        $nof = $this->bean->get_request("nof");
        $incident_findings = count($this->dao->getRelated($token_id));
        if ($line_function_id == '') {
            if ($incident_findings > 0) {
                $this->dao->deleteRelated($token_id);
            }
        }
        $data2 = array();
        $total_inserted = '';

        for ($i = 0; $i <= $nof; $i++) {
            $data2["description"] = ($description[$i] != '') ? $description[$i] : '';
            $data2["category"] = ($category[$i] != '') ? $category[$i] : '';
            $data2["action_taken"] = ($action_taken[$i] != '') ? $action_taken[$i] : '';
            $data2["correction_desc"] = ($correction_desc[$i] != '') ? $correction_desc[$i] : '';
            $data2["correction_date"] = ($correction_date[$i] != '') ? $correction_date[$i] : '';
            $data2["token_id"] = $token_id;
            $data2["line_function_id"] = $line_function_id;

            if ($description[$i] != '') {

                $this->dao->_table = "safety_observation_line_function_deviation_mapping";
                $user_inserted = $this->dao->save($data2);
                if ($user_inserted) {
                    $total_inserted += 1;
                }
            }
        }

        $incident_finding_no = count($this->dao->getlinefunctionDeviation($line_function_id));

        if ($line_function_id != '') {
            $this->dao->updatelinefunctionDeviation($line_function_id, $incident_finding_no);
            echo $incident_finding_no;
            die;
        } else {
            echo $total_inserted;
            die;
        }
    }

    public function save_deviation_line_function_edit() {

        $description = $this->bean->get_request("description");
        $category = $this->bean->get_request("category");
        $line_function_id = $this->bean->get_request("line_function_id");
        $action_taken = $this->bean->get_request("action_taken");
        $correction_desc = $this->bean->get_request("correction_desc");
        $correction_date = $this->bean->get_request("correction_date");
        $token_id = $this->bean->get_request("token_id");
        $nof = $this->bean->get_request("nof");
        $incident_findings = count($this->dao->getRelated($token_id));
        if ($line_function_id == '') {
            if ($incident_findings > 0) {
                $this->dao->deleteRelated($token_id);
            }
        }
        $data2 = array();
        $total_inserted = '';

        for ($i = 0; $i <= $nof; $i++) {
            $data2["description"] = ($description[$i] != '') ? $description[$i] : '';
            $data2["category"] = ($category[$i] != '') ? $category[$i] : '';
            $data2["action_taken"] = ($action_taken[$i] != '') ? $action_taken[$i] : '';
            $data2["correction_desc"] = ($correction_desc[$i] != '') ? $correction_desc[$i] : '';
            $data2["correction_date"] = ($correction_date[$i] != '') ? $correction_date[$i] : '';
            $data2["token_id"] = $token_id;
            $data2["line_function_id"] = $line_function_id;

            if ($description[$i] != '') {

                $this->dao->_table = "safety_observation_line_function_deviation_mapping";
                $user_inserted = $this->dao->save($data2);
                if ($user_inserted) {
                    $total_inserted += 1;
                    $val .= '<tr class="removetr appendrow" id="rm' . $user_inserted . '">
                        <td align="center"></td>
						<td align="center">' . $data2["description"] . '</td>
						<td align="center">' . $data2["category"] . '</td>
						<td align="center">';
                    if ($data2["action_taken"] == 'Y') {
                        $val .= "Yes";
                        $val .= "<br><b>Correction Description </b>: " . $data2["correction_desc"];
                        $val .= "<br><b>Correction Date </b>: " . $data2["correction_date"];
                    } else {
                        $val .= "No";
                    }

                    $val .= '</td>
						<td class="center" width="25%" align="center">
						<a id="dataremove" class="btn btn-danger btn-sm ajxbtnrmv" name="delete" data-acid="' . $user_inserted . '" data-action="linefunc_deviation_delete" style="cursor:pointer;">
						<i class="fa fa-trash"></i></a>
						<input type="hidden" class="total_count" value="' . $nof . '" />
						</td>
						</tr>';
                }
            }
        }

        $incident_finding_no = count($this->dao->getlinefunctionDeviation($line_function_id));

        if ($line_function_id != '') {
            $this->dao->updatelinefunctionDeviation($line_function_id, $incident_finding_no);
        } else {
            
        }

        if ($total_inserted >= 1) {
            echo $val;
            die;
        } else {
            $val .= '<input type="hidden" class="total_count2" value="' . $nof . '" />';
            echo $val;
            die;
        }
    }

    public function participants() {
        $activity_id = $this->bean->get_request("activity_id");
        $auth_user_id = $this->get_auth_user("id");
        $clause .= " activity_id=" . $activity_id . " AND type='P'";
        $limit = 10;
        $clause .= " ORDER BY created_date";
        $allparticipants = $this->dao->get_participants_with_pagging($clause, $limit, $this->get_auth_user());
        $posts_paggin_html = get_page_html($this->dao);
        $this->beanUi->set_view_data("page", $this->bean->get_request("page"));
        $this->beanUi->set_view_data("allparticipants", $allparticipants);
        $this->beanUi->set_view_data("posts_paggin_html", $posts_paggin_html);
    }

    public function edit() {
        $_action = $this->bean->get_request("_action");

        if ($_action == "update") {

            $data = $this->bean->get_request("data");
            $page = $this->bean->get_request("page") ?$this->bean->get_request("page") : 1;
            $status_id = $this->bean->get_request("status_id");
            $search_title = $this->bean->get_request("search_title");
            $fromdate = $this->bean->get_request("fromdate");
            $todate = $this->bean->get_request("todate");
            $activity_no = $this->bean->get_request("activity_no");
            $districtid=  $this->bean->get_request("districtid");
            $todate = $this->bean->get_request("todate");
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $draftstatus = $data["status_id"];
            if ($data["status_id"] == 7) {
                $data["status_id"] = 1;
            }

            if ($_POST['featured_image_path'] != '') {
                $oldimg = explode("?", end(explode("/", $_POST['featured_image_path_old'])));
                $image1 = $oldimg[0];

                $old_original_img = str_replace("-avatar", "", $oldimg[0]);
                @unlink(UPLOADS_PATH . "/activities/" . $image1);
                @unlink(UPLOADS_PATH . "/activities/" . $old_original_img);
                $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
                $data["featured_image_path"] = $featured_img[0];
            }

            // Save post
            $update_post = $this->dao->save($data);
            $activity_logs = array();
            if ($update_post) {
                if ($draftstatus != 1) {
                    $activity_logs["status_id"] = $draftstatus;
                    $activity_logs["activity_id"] = $data["id"];
                    $activity_logs["activity_type_id"] = $data["activity_type_id"];
                    $this->dao->_table = "activity_logs";
                    $this->dao->save($activity_logs);
                }
            }
            if (!$update_post) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            // End
            // Delete old featured image if new one is uploaded.
            if ($old_featured_image != "")
                @unlink(BASE_PATH . "/" . $old_featured_image);
            // End
            // Upload images and pdf
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $pdf_id = $this->bean->get_request("pdf_id");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );

                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {

                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );


                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );


                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            $errors = "";
            if (!empty($uploads)) {
                $this->dao->_table = "file_upload_activity_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "") {
                        $post_uploads["activity_id"] = $data["id"];
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }


            //Participant Category Mapping Insert
            $participant_category = $this->bean->get_request("participant_cat_id");
            if (!empty($participant_category)) {
                $check_participant_exists = array();
                $this->dao->_table = "activity_participant_category_mapping";
                foreach ($participant_category as $prtrow => $key) {
                    $no_participants = $this->bean->get_request("no_of_participants_".$key["participant_cat_id"]);
                    $act_p_category_mapping = array();
                    $act_p_category_mapping["activity_id"] = $data["id"];
                    $act_p_category_mapping["participant_cat_id"] = $key["participant_cat_id"];
                    $act_p_category_mapping["no_of_participants"] = $no_participants;
                    $act_p_category_mapping["created_by"] = $this->get_auth_user("id");
                    $act_p_category_mapping["created_date"] = date("c");
                    $act_faculty_category_mapping["type"] = 'P';
                    
                    $check_participant_exists = $this->dao->check_participant_exists($data["id"], $key["participant_cat_id"], "activity_id", "activity_participant_category_mapping");

                    if (!$check_participant_exists) {
                        $p_mappings = $this->dao->save($act_p_category_mapping);
                        if (!$p_mappings) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    } else {
                      echo  $query = "UPDATE activity_participant_category_mapping "
                              . "SET no_of_participants ='".$no_participants."'"
                              . "WHERE participant_cat_id = ".$key['participant_cat_id']." AND activity_id = '".$data['id']."'";
                        $result = $this->dao->db->prepare($query);
                        $result->execute();
                    }
                }
            }
            //Faculty Category Mapping Insert
            $faculty_category = $this->bean->get_request("faculty_cat_id");
            $no_of_faculty = $this->bean->get_request("no_of_faculty");
            if (!empty($faculty_category)) {
                $check_faculty_exists = array();
                $this->dao->_table = "activity_participant_category_mapping";
                foreach ($faculty_category as $frtrow => $keys) {
                    $act_faculty_category_mapping = array();
                    $act_faculty_category_mapping["activity_id"] = $data["id"];
                    $act_faculty_category_mapping["participant_cat_id"] = $keys["faculty_cat_id"];
                    $act_faculty_category_mapping["no_of_participants"] = $no_of_faculty[$frtrow];
                    $act_faculty_category_mapping["created_by"] = $this->get_auth_user("id");
                    $act_faculty_category_mapping["created_date"] = date("c");
                    $act_faculty_category_mapping["type"] = 'F';
                    $check_faculty_exists = $this->dao->check_participant_exists($data["id"], $keys["faculty_cat_id"], "activity_id", "activity_participant_category_mapping");

                    if (!$check_faculty_exists) {
                        $faculty_mappings = $this->dao->save($act_faculty_category_mapping);
                        if (!$faculty_mappings) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    } else {
                        $query = "UPDATE activity_participant_category_mapping SET no_of_participants = :no_of_faculty WHERE participant_cat_id = :pcatid AND activity_id = :act_id";
                        $result = $this->dao->db->prepare($query);
                        $result->execute(array(":pcatid" => $keys['participant_cat_id'], ":act_id" => $data['id'], ":no_of_faculty" => $no_of_faculty[$frtrow]));
                    }
                }
            }



            //Division Mapping Insert
            $division = $this->bean->get_request("division");
            if (!empty($division)) {
                $this->dao->_table = "activity_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["activity_id"] = $data["id"];
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            $this->dao->update_activity_participants($data["id"], $token_id);

            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Save File Caption
            if ($caption != "" && $pdf_id) {
                $captiondata["id"] = $pdf_id;
                $captiondata["name"] = $caption;
                $this->dao->_table = "post_uploads";
                $this->dao->save($captiondata);
            }
            // End
            // Add popular post entry
            if ($data["status_id"] == 3 && $data["id"]) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $data["id"]);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $data["id"]));
                    if ($this->dao->get_query_error() != "")
                        die($this->dao->get_query_error());
                }
            }
            // End popular post
            $this->beanUi->set_success_message("Activity is successfully updated.");

            redirect(page_link("activity/index.php?status_id=".$status_id."&districtid=".$districtid."&activity_no=".$activity_no."&search_title=".$search_title."&fromdate=".$fromdate."&todate=".$todate."&page=".$page."&activity_id=" . $data["activity_type_id"]));
        }

        $id = $this->bean->get_request("id");
        if (!$id) {
            $this->beanUi->set_error_message("Could not load the page, post id is missing.");
            redirect("./");
        }
        $posts = $this->dao->fetchdata($id, "activity_view");
        $post = !empty($posts) ? $posts[0] : array();
        $post_uploads = $this->dao->get_post_uploads($post->id);
        $skip_fields = array("post_cat_name", "sugst_post_cat_name", "synopsis", "description");

        $this->beanUi->set_array_to_view_data($post, $skip_fields);
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("post_uploads", $post_uploads);
        $clause = " activity_id=" . $id;
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "activity_division_mapping_view");
        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();

        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */

        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("post_division_department_mapping", $division_department_mapping);
        $catid_array = array();
        $activity_participants_category_mapping = "";
        foreach ($division_department_mapping as $ddm) {
            $catid_array[] = $ddm->division_id;
        }
        $clause = " activity_id=" . $id . "";
        $activity_participants_category_mapping = $this->dao->get_participants_category_mapping($clause, "activity_participant_category_mapping");

        $this->beanUi->set_view_data("post_activity_participants_category_mapping", $activity_participants_category_mapping);

        $pcatid_array = array();
        foreach ($activity_participants_category_mapping as $rows) {
            $pcatid_array[$rows->participant_cat_id] = $rows->no_of_participants;
        }
        $this->beanUi->set_view_data("cat_id", $catid_array);
        $this->beanUi->set_view_data("pcat_id", $pcatid_array);
        $clause = " activity_id=" . $id . " AND type = 'P'";
        $aaa = $this->dao->get_participants_by_activity($clause);
        foreach ($aaa as $actvalue) {
            $related_activities = $this->dao->get_participants_details($actvalue->activity_id, $actvalue->participant_cat_id);
            $participants_list[$actvalue->participant_cat_id] = $related_activities;
        }
        $clause2 = " activity_id=" . $id . " AND type = 'F'";
        $aaa2 = $this->dao->get_participants_by_activity($clause2);
        $faculty_list = array();
        foreach ($aaa2 as $actvalue) {
            $related_activities = $this->dao->get_participants_details($actvalue->activity_id, $actvalue->participant_cat_id);
            $faculty_list[$actvalue->participant_cat_id] = $related_activities;
        }

        $this->beanUi->set_view_data("participants_list", $participants_list);
        $this->beanUi->set_view_data("faculty_list", $faculty_list);
        $this->beanUi->set_view_data("post_activity_participants_category_mapping", $activity_participants_category_mapping);
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_activity_type_id", $post->activity_type_id);
    }

    public function edit_audit() {
        $_action = $this->bean->get_request("_action");
        if ($_action == "update") {
            $data = $this->bean->get_request("data");
            $page = $this->bean->get_request("page") ? $this->bean->get_request("page") : 1;
             $status_id = $this->bean->get_request("status_id");
            $search_title = $this->bean->get_request("search_title");
            $fromdate = $this->bean->get_request("fromdate");
            $todate = $this->bean->get_request("todate");
            $districtid = $this->bean->get_request("districtid");
            $activity_no = $this->bean->get_request("activity_no");
            
             if ($this->bean->get_request("audit_by") != "") {
                $data["audit_by"] = implode(",", $this->bean->get_request("audit_by"));
            }
            
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $draftstatus = $data["status_id"];
            if ($data["status_id"] == 7) {
                $data["status_id"] = 1;
            }
            if ($_POST['featured_image_path'] != '') {
                $oldimg = explode("?", end(explode("/", $_POST['featured_image_path_old'])));
                $image1 = $oldimg[0];
                $old_original_img = str_replace("-avatar", "", $oldimg[0]);
                @unlink(UPLOADS_PATH . "/activities/" . $image1);
                @unlink(UPLOADS_PATH . "/activities/" . $old_original_img);
                $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
                $data["featured_image_path"] = $featured_img[0];
            }

            $this->dao->_table = "audit";
            $update_post = $this->dao->save($data);
            if ($update_post) {
                if ($draftstatus != 1) {
                    $activity_logs["status_id"] = $draftstatus;
                    $activity_logs["activity_id"] = $data["id"];
                    $activity_logs["activity_type_id"] = $data["activity_type_id"];
                    $this->dao->_table = "activity_logs";
                    $this->dao->save($activity_logs);
                }
            }

            if (!$update_post) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            // End
            // Delete old featured image if new one is uploaded.
            if ($old_featured_image != "")
                @unlink(BASE_PATH . "/" . $old_featured_image);
            // End
            // Upload images and pdf
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $pdf_id = $this->bean->get_request("pdf_id");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );

                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {

                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );


                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );


                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            $errors = "";
            if (!empty($uploads)) {
                $this->dao->_table = "file_upload_audit_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "") {
                        $post_uploads["audit_id"] = $data["id"];
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }


            //Division Mapping Insert
            $division = $this->bean->get_request("division");

            if (!empty($division)) {
                $this->dao->_table = "audit_division_mapping";
                $this->dao->_table = "audit_division_mapping";

                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["audit_id"] = $data["id"];
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }


            

            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Save File Caption
            if ($caption != "" && $pdf_id) {
                $captiondata["id"] = $pdf_id;
                $captiondata["name"] = $caption;
                $this->dao->_table = "post_uploads";
                $this->dao->save($captiondata);
            }
            // End
            // Add popular post entry
            if ($data["status_id"] == 3 && $data["id"]) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $data["id"]);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $data["id"]));
                    if ($this->dao->get_query_error() != "")
                        die($this->dao->get_query_error());
                }
            }
            // End popular post



            $this->beanUi->set_success_message("Audit is successfully updated.");
            redirect(page_link("activity/index.php?status_id=".$status_id."&districtid=".$districtid."&activity_no=".$activity_no."&search_title=".$search_title."&fromdate=".$fromdate."&todate=".$todate."&page=".$page."&activity_id=" . $data["activity_type_id"]));
        }

        $id = $this->bean->get_request("id");
        if (!$id) {

            $this->beanUi->set_error_message("Could not load the page, post id is missing.");
            redirect("./");
        }
        $posts = $this->dao->fetchdata($id, "audit_view");
        $post = !empty($posts) ? $posts[0] : array();
        $post_uploads = $this->dao->get_audit_uploads($post->id);
        $skip_fields = array("post_cat_name", "sugst_post_cat_name", "synopsis", "description");
        $this->beanUi->set_array_to_view_data($post, $skip_fields);
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("post_uploads", $post_uploads);
        $clause = " audit_id=" . $id;
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "audit_division_mapping_view");
        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */

        $this->beanUi->set_view_data("devition_names", $devition_name);



        $this->beanUi->set_view_data("post_division_department_mapping", $division_department_mapping);
        $catid_array = array();
        foreach ($division_department_mapping as $ddm) {
            $catid_array[] = $ddm->division_id;
        }
        $this->beanUi->set_view_data("cat_id", $catid_array);

        $clause = " audit_id=" . $id . " ";
        $violation = $this->dao->get_violation_by_activity($clause);
        $this->beanUi->set_view_data("violation", $violation);
        // participant_cat_id
        $this->beanUi->set_view_data("post_activity_participants_category_mapping", $activity_participants_category_mapping);
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("user_details", $this->dao->get_user_details());
        $this->beanUi->set_view_data("post_activity_type_id", $post->activity_type_id);
    }
    public function edit_audit_nm() {
        $_action = $this->bean->get_request("_action");
        if ($_action == "update") {
            $data = $this->bean->get_request("data");
            $page = $this->bean->get_request("page") ? $this->bean->get_request("page") : 1;
             $status_id = $this->bean->get_request("status_id");
            $search_title = $this->bean->get_request("search_title");
            $fromdate = $this->bean->get_request("fromdate");
            $todate = $this->bean->get_request("todate");
            $districtid = $this->bean->get_request("districtid");
            $activity_no = $this->bean->get_request("activity_no");
            
             if ($this->bean->get_request("audit_by") != "") {
                $data["audit_by"] = implode(",", $this->bean->get_request("audit_by"));
            }
            
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $draftstatus = $data["status_id"];
            if ($data["status_id"] == 7) {
                $data["status_id"] = 1;
            }
            if ($_POST['featured_image_path'] != '') {
                $oldimg = explode("?", end(explode("/", $_POST['featured_image_path_old'])));
                $image1 = $oldimg[0];
                $old_original_img = str_replace("-avatar", "", $oldimg[0]);
                @unlink(UPLOADS_PATH . "/activities/" . $image1);
                @unlink(UPLOADS_PATH . "/activities/" . $old_original_img);
                $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
                $data["featured_image_path"] = $featured_img[0];
            }

            $this->dao->_table = "audit";
            $update_post = $this->dao->save($data);
            if ($update_post) {
                if ($draftstatus != 1) {
                    $activity_logs["status_id"] = $draftstatus;
                    $activity_logs["activity_id"] = $data["id"];
                    $activity_logs["activity_type_id"] = $data["activity_type_id"];
                    $this->dao->_table = "activity_logs";
                    $this->dao->save($activity_logs);
                }
            }

            if (!$update_post) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            // End
            // Delete old featured image if new one is uploaded.
            if ($old_featured_image != "")
                @unlink(BASE_PATH . "/" . $old_featured_image);
            // End
            // Upload images and pdf
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $pdf_id = $this->bean->get_request("pdf_id");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );

                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {

                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );


                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );


                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            $errors = "";
            if (!empty($uploads)) {
                $this->dao->_table = "file_upload_audit_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "") {
                        $post_uploads["audit_id"] = $data["id"];
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }


            //Division Mapping Insert
            $division = $this->bean->get_request("division");

            if (!empty($division)) {
                $this->dao->_table = "audit_division_mapping";
                $this->dao->_table = "audit_division_mapping";

                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["audit_id"] = $data["id"];
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }


            

            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Save File Caption
            if ($caption != "" && $pdf_id) {
                $captiondata["id"] = $pdf_id;
                $captiondata["name"] = $caption;
                $this->dao->_table = "post_uploads";
                $this->dao->save($captiondata);
            }
            // End
            // Add popular post entry
            if ($data["status_id"] == 3 && $data["id"]) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $data["id"]);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $data["id"]));
                    if ($this->dao->get_query_error() != "")
                        die($this->dao->get_query_error());
                }
            }
            // End popular post



            $this->beanUi->set_success_message("Audit is successfully updated.");
            redirect(page_link("activity/index.php?status_id=".$status_id."&districtid=".$districtid."&activity_no=".$activity_no."&search_title=".$search_title."&fromdate=".$fromdate."&todate=".$todate."&page=".$page."&activity_id=" . $data["activity_type_id"]));
        }

        $id = $this->bean->get_request("id");
        if (!$id) {

            $this->beanUi->set_error_message("Could not load the page, post id is missing.");
            redirect("./");
        }
        $posts = $this->dao->fetchdata($id, "audit_view");
        $post = !empty($posts) ? $posts[0] : array();
        $post_uploads = $this->dao->get_audit_uploads($post->id);
        $skip_fields = array("post_cat_name", "sugst_post_cat_name", "synopsis", "description");
        $this->beanUi->set_array_to_view_data($post, $skip_fields);
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("post_uploads", $post_uploads);
        $clause = " audit_id=" . $id;
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "audit_division_mapping_view");
        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */

        $this->beanUi->set_view_data("devition_names", $devition_name);



        $this->beanUi->set_view_data("post_division_department_mapping", $division_department_mapping);
        $catid_array = array();
        foreach ($division_department_mapping as $ddm) {
            $catid_array[] = $ddm->division_id;
        }
        $this->beanUi->set_view_data("cat_id", $catid_array);

        $clause = " audit_id=" . $id . " ";
        $violation = $this->dao->get_violation_by_activity($clause);
        $this->beanUi->set_view_data("violation", $violation);
        // participant_cat_id
        $this->beanUi->set_view_data("post_activity_participants_category_mapping", $activity_participants_category_mapping);
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("user_details", $this->dao->get_user_details());
        $this->beanUi->set_view_data("post_activity_type_id", $post->activity_type_id);
    }

    public function edit_minutes_of_meeting() {
        $_action = $this->bean->get_request("_action");
        if ($_action == "update") {
            $data = $this->bean->get_request("data");
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $draftstatus = $data["status_id"];
            if ($data["status_id"] == 7) {
                $data["status_id"] = 1;
            }
            if ($_POST['featured_image_path'] != '') {
                $oldimg = explode("?", end(explode("/", $_POST['featured_image_path_old'])));
                $image1 = $oldimg[0];
                $old_original_img = str_replace("-avatar", "", $oldimg[0]);
                @unlink(UPLOADS_PATH . "/activities/" . $image1);
                @unlink(UPLOADS_PATH . "/activities/" . $old_original_img);
                $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
                $data["featured_image_path"] = $featured_img[0];
            }

            $this->dao->_table = "minutes_of_meeting";
            $update_post = $this->dao->save($data);
            if ($update_post) {
                if ($draftstatus != 1) {
                    $activity_logs["status_id"] = $draftstatus;
                    $activity_logs["activity_id"] = $data["id"];
                    $activity_logs["activity_type_id"] = $data["activity_type_id"];
                    $this->dao->_table = "activity_logs";
                    $this->dao->save($activity_logs);
                }
            }

            if (!$update_post) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            // End
            // Delete old featured image if new one is uploaded.
            if ($old_featured_image != "")
                @unlink(BASE_PATH . "/" . $old_featured_image);
            // End
            // Upload images and pdf
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $pdf_id = $this->bean->get_request("pdf_id");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );

                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );


                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            $errors = "";
            if (!empty($uploads)) {
                $this->dao->_table = "file_upload_mom_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "") {
                        $post_uploads["mom_id"] = $data["id"];
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }


            // Save File Caption
            if ($caption != "" && $pdf_id) {
                $captiondata["id"] = $pdf_id;
                $captiondata["name"] = $caption;
                $this->dao->_table = "post_uploads";
                $this->dao->save($captiondata);
            }
            // End

            $this->beanUi->set_success_message("MOM is successfully updated.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }

        $id = $this->bean->get_request("id");


        if (!$id) {

            $this->beanUi->set_error_message("Could not load the page, post id is missing.");
            redirect("./");
        }
        $posts = $this->dao->fetchdata($id, "mom_view");
        $post = !empty($posts) ? $posts[0] : array();

        $post_uploads = $this->dao->get_mom_uploads($post->id);

        $skip_fields = array("post_cat_name", "sugst_post_cat_name", "synopsis", "description");

        $this->beanUi->set_array_to_view_data($post, $skip_fields);

        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("post_uploads", $post_uploads);
        $this->beanUi->set_view_data("post_activity_participants_category_mapping", @$activity_participants_category_mapping);
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_activity_type_id", $post->activity_type_id);
    }

    public function edit_incident() {
        $voilation_category = array(
            '1' => 'PPEs',
            '2' => 'SWP',
            '3' => 'Safety Standard',
            '4' => 'Job Safety - Working at Height',
            '5' => 'Job Safety - Hot Job',
            '6' => 'Job Safety - Confined Space',
            '7' => 'Job Safety - Chemical',
            '8' => 'Job Safety - Heavy Material Handling',
            '9' => 'Reaction Of People',
            '10' => 'Position Of People',
            '11' => 'Tools & Equipment',
            '12' => 'Procudure',
            '13' => 'Unsafe condition',
            '14' => 'Unsafe Act',
            '15' => 'Job Safety- Electrical Safety',
            '16' => 'Job Safety - Excavation',
            '17' => 'Safe Zone Creation'
        );
        $_action = $this->bean->get_request("_action");
       
        if ($_action == "update") {

            $data = $this->bean->get_request("data");
            $parent_incident_mapping= $this->bean->get_request("parent_incident_mapping");
            $parent_incident_id     = $this->bean->get_request("parent_incident_id");
             $page = $this->bean->get_request("page") ? $this->bean->get_request("page") : 1;
             $status_id = $this->bean->get_request("status_id");
             $incident_category_ids = $this->bean->get_request("incident_category_ids");
             $fromdate = $this->bean->get_request("fromdate");
             $todate = $this->bean->get_request("todate");
             $search_title = $this->bean->get_request("search_title");
             $districtid = $this->bean->get_request("districtid");
            $draftstatus = $data["status_id"];
            if ($data["status_id"] == 7) {
                $data["status_id"] = 1;
            }
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $data["parent_incident_mapping"] = $parent_incident_mapping;
            $data["parent_incident_id"] = $parent_incident_id;
            $data["incident_category_id"] = $this->bean->get_request("incident_category_id");
            $data["incident_type_id"] = $this->bean->get_request("incident_type_id");

            if ($this->bean->get_request("nature_of_injury_id") != "") {
                $data["nature_of_injury_id"] = implode(",", $this->bean->get_request("nature_of_injury_id"));
            }

            if ($this->bean->get_request("violation_category") != "") {
                $data["violation_category"] = implode(",", $this->bean->get_request("violation_category"));
            }

            if ($_POST['featured_image_path'] != '') {
                $oldimg = explode("?", end(explode("/", $_POST['featured_image_path_old'])));
                $image1 = $oldimg[0];

                $old_original_img = str_replace("-avatar", "", $oldimg[0]);
                @unlink(UPLOADS_PATH . "/activities/" . $image1);
                @unlink(UPLOADS_PATH . "/activities/" . $old_original_img);
                $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
                $data["featured_image_path"] = $featured_img[0];
            }
            $this->dao->_table = "incident";
            $update_post = $this->dao->save($data);
            $activity_logs = array();
            if ($update_post) {
                if ($draftstatus != 1) {
                    $activity_logs["status_id"] = $draftstatus;
                    $activity_logs["activity_id"] = $data["id"];
                    $activity_logs["activity_type_id"] = $data["activity_type_id"];
                    $this->dao->_table = "activity_logs";
                    $this->dao->save($activity_logs);
                }
            }
            if (!$update_post) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            // End
            // Delete old featured image if new one is uploaded.
            if ($old_featured_image != "")
                @unlink(BASE_PATH . "/" . $old_featured_image);
            // End
            // Upload images and pdf
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $pdf_id = $this->bean->get_request("pdf_id");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );

                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {

                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );


                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }
            $errors = "";
            if (!empty($uploads)) {
                $this->dao->_table = "file_upload_incident_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "") {
                        $post_uploads["incident_id"] = $data["id"];
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }

            //Division Mapping Insert
            $division = $this->bean->get_request("division");

            if (!empty($division)) {
                $this->dao->_table = "incident_division_mapping";
                $this->dao->_table = "incident_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["incident_id"] = $data["id"];
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }

            $incident_category_id = $this->bean->get_request("incident_category_id");
            $incident_category_id_old = $this->bean->get_request("incident_category_id_old");
            if (!empty($incident_category_id)) {
                $this->dao->_table = "incident_category_logs";
                $inc_cat = array();
                $inc_cat["incident_id"] = $data["id"];
                $inc_cat["incident_category_id"] = $incident_category_id;
                $inc_cat["created_by"] = $this->get_auth_user("id");
                $inc_cat["created_date"] = date("c");
                if ($incident_category_id_old != $incident_category_id) {
                    $save_inc_category = $this->dao->save($inc_cat);
                }
                if (!$save_inc_category) {
                    $errors .= $this->dao->get_query_error() . ",";
                }
            }


            $bodypart_ids = $this->bean->get_request("bodypart_ids");
            $parent_id = $this->bean->get_request("parent_id");
            if (!empty($bodypart_ids)) {


                $this->dao->_table = "incident_body_part_injury_mapping";
                $this->dao->del(array("incident_id" => $data["id"]));
                $bpart_insert = array();
                foreach ($bodypart_ids as $key => $values) {
                    $bodypart_status = $this->bean->get_request("bodypart_status_" . $bodypart_ids[$key]);
                    $bpart_insert["incident_id"] = $data["id"];
                    $bpart_insert["parent_id"] = $parent_id[$key];
                    $bpart_insert["bodypart_id"] = $bodypart_ids[$key];
                    $bpart_insert["status"] = $bodypart_status;
                    $save_bpart_insert = $this->dao->save($bpart_insert);
                    if (!$save_bpart_insert) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }


            // Save File Caption
            if ($caption != "" && $pdf_id) {
                $captiondata["id"] = $pdf_id;
                $captiondata["name"] = $caption;
                $this->dao->_table = "post_uploads";
                $this->dao->save($captiondata);
            }
            // End
            $this->beanUi->set_success_message("Incident is successfully updated.");
            redirect(page_link("activity/index.php?status_id=".$status_id."&search_title=".$search_title."&districtid=".$districtid."&incident_category_ids=".$incident_category_ids."&fromdate=".$fromdate."&todate=".$todate."&page=".$page."&activity_id=" . $data["activity_type_id"]));
        }
        $id = $this->bean->get_request("id");
        if (!$id) {

            $this->beanUi->set_error_message("Could not load the page, post id is missing.");
            redirect("./");
        }
        $posts = $this->dao->fetchdata($id, "incident_view");
        $post = !empty($posts) ? $posts[0] : array();
        $post_uploads = $this->dao->get_incident_uploads($post->id);
        $incident_category = $this->dao->get_incident_category();
        $this->beanUi->set_view_data("incident_category", $incident_category);
        $nature_of_injury = $this->dao->get_nature_injury();
        $this->beanUi->set_view_data("nature_of_injury", $nature_of_injury);

        $childArray = array();
        $injury_status = array();
        $injury_status1 = array();
        $clause = " WHERE parent_id=0";
        $body_part_injury = $this->dao->get_body_part_injury($clause);

        foreach ($body_part_injury as $k => $rdata) {
            $clausenew = " WHERE parent_id=" . $rdata->id;
            $getChild = $this->dao->get_body_part_injury($clausenew);
            $childArray[$rdata->id] = $getChild;
            $conds = " WHERE incident_id=" . $id . " AND parent_id=" . $rdata->id;
            $body_part_injury_mappingData1[$rdata->id] = $this->dao->get_body_part_injury_mappingData($conds);

            foreach ($getChild as $ch) {
                $cond = " WHERE incident_id=" . $id . " AND bodypart_id=" . $ch->id;
                $body_part_injury_mappingData = $this->dao->get_body_part_injury_mappingData($cond);
                @$injury_status[$rdata->id][$ch->id] = $body_part_injury_mappingData[0];
            }
        }

        $this->beanUi->set_view_data("body_part_injury", $body_part_injury);
        $this->beanUi->set_view_data("childData", $childArray);
        $this->beanUi->set_view_data("injury_status", $injury_status);

        $this->beanUi->set_view_data("body_part_injury_mappingData", $body_part_injury_mappingData1);
        $skip_fields = array("post_cat_name", "sugst_post_cat_name", "synopsis", "description");
        $this->beanUi->set_array_to_view_data($post, $skip_fields);
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("post_uploads", $post_uploads);
        $clause = " incident_id=" . $id;
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "incident_division_mapping_view");
        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */

        $this->beanUi->set_view_data("devition_names", $devition_name);

        $this->beanUi->set_view_data("post_division_department_mapping", $division_department_mapping);
        $catid_array = array();
        foreach ($division_department_mapping as $ddm) {
            $catid_array[] = $ddm->division_id;
        }


        $this->beanUi->set_view_data("voilation_category", $voilation_category);
        $this->beanUi->set_view_data("cat_id", $catid_array);
        $clause = " incident_id=" . $id . " ";
        $findingsx = $this->dao->get_findings_by_activity($clause);
        $this->beanUi->set_view_data("finding_rows", $findingsx);

        $this->beanUi->set_view_data("post_activity_participants_category_mapping", @$activity_participants_category_mapping);
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_activity_type_id", $post->activity_type_id);
    }

    public function edit_safety_observation() {
        $_action = $this->bean->get_request("_action");
        if ($_action == "update") {
            $data = $this->bean->get_request("data");
            $page = $this->bean->get_request("page") ? $this->bean->get_request("page") : 1;
            $status_id = $this->bean->get_request("status_id");
            $search_title = $this->bean->get_request("search_title");
            $districtid = $this->bean->get_request("districtid");
            $activity_month = $this->bean->get_request("activity_month");
            $activity_year = $this->bean->get_request("activity_year");
            $activity_no = $this->bean->get_request("activity_no");
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $draftstatus = $data["status_id"];
            if ($data["status_id"] == 7) {
                $data["status_id"] = 1;
            }
            $this->dao->_table = "safety_observation";
            $update_post = $this->dao->save($data);

            if ($update_post) {
                if ($draftstatus != 1) {
                    $activity_logs["status_id"] = $draftstatus;
                    $activity_logs["activity_id"] = $data["id"];
                    $activity_logs["activity_type_id"] = $data["activity_type_id"];
                    $this->dao->_table = "activity_logs";
                    $this->dao->save($activity_logs);
                }
            }

            if (!$update_post) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            // End
            //Division Mapping Insert
            $division = $this->bean->get_request("division");

            if (!empty($division)) {
                $this->dao->_table = "safety_observation_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["safety_observation_id"] = $data["id"];
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            // Save File Caption
            if ($caption != "" && $pdf_id) {
                $captiondata["id"] = $pdf_id;
                $captiondata["name"] = $caption;
                $this->dao->_table = "post_uploads";
                $this->dao->save($captiondata);
            }
            // End
            // Add popular post entry
            if ($data["status_id"] == 3 && $data["id"]) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $data["id"]);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $data["id"]));
                    if ($this->dao->get_query_error() != "")
                        die($this->dao->get_query_error());
                }
            }
            // End popular post
            $this->beanUi->set_success_message("Safety observation is successfully updated.");
            redirect(page_link("activity/index.php?status_id=".$status_id."&districtid=".$districtid."&search_title=".$search_title."&activity_month=".$activity_month."&activity_year=".$activity_year."&activity_no=".$activity_no."&page=".$page."&activity_id=" . $data["activity_type_id"]));
        }

        $id = $this->bean->get_request("id");


        if (!$id) {

            $this->beanUi->set_error_message("Could not load the page, post id is missing.");
            redirect("./");
        }
        $posts = $this->dao->fetchdata($id, "safety_observation_view");
        $post = !empty($posts) ? $posts[0] : array();

        $post_uploads = $this->dao->get_audit_uploads($post->id);
        $skip_fields = array("post_cat_name", "sugst_post_cat_name", "synopsis", "description", "created_by_name");
        $this->beanUi->set_array_to_view_data($post, $skip_fields);
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("post_uploads", $post_uploads);
        $clause = " safety_observation_id=" . $id;
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "safety_observation_division_mapping_view");
        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */

        $this->beanUi->set_view_data("devition_names", $devition_name);



        $this->beanUi->set_view_data("post_division_department_mapping", $division_department_mapping);
        $catid_array = array();
        foreach ($division_department_mapping as $ddm) {
            $catid_array[] = $ddm->division_id;
        }
        $this->beanUi->set_view_data("cat_id", $catid_array);
        $clause = " activity_id=" . $id . " AND type = 'P'";
        $aaa = $this->dao->get_participants_by_activity($clause);
        foreach ($aaa as $actvalue) {

            $related_activities = $this->dao->get_participants_details($actvalue->activity_id, $actvalue->participant_cat_id);
            $participants_list[$actvalue->participant_cat_id] = $related_activities;
        }

        $this->beanUi->set_view_data("participants_list", $participants_list);

        $this->beanUi->set_view_data("post_activity_participants_category_mapping", $activity_participants_category_mapping);
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_activity_type_id", $post->activity_type_id);
    }

    public function edit_safety_observation_line_function() {
        $_action = $this->bean->get_request("_action");
        if ($_action == "update") {
            $data = $this->bean->get_request("data");
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $draftstatus = $data["status_id"];
            if ($data["status_id"] == 7) {
                $data["status_id"] = 1;
            }
            $this->dao->_table = "safety_observation_line_function";
            $update_post = $this->dao->save($data);
            if ($update_post) {
                if ($draftstatus != 1) {
                    $activity_logs["status_id"] = $draftstatus;
                    $activity_logs["activity_id"] = $data["id"];
                    $activity_logs["activity_type_id"] = $data["activity_type_id"];
                    $this->dao->_table = "activity_logs";
                    $this->dao->save($activity_logs);
                }
            }
            if (!$update_post) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            //Division Mapping Insert
            $division = $this->bean->get_request("division");
            if (!empty($division)) {
                $this->dao->_table = "safety_observation_line_function_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["line_function_id"] = $data["id"];
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }

            $framework_count = $this->bean->get_request("framework_count");
            $framework_id = $this->bean->get_request("framework_id");
            if (!empty($framework_count)) {
                $this->dao->_table = "safety_observation_framework_mapping";
                foreach ($framework_count as $frow => $k) {
                    $framework_mapping = array();
                    $framework_mapping["line_function_id"] = $data["id"];
                    $framework_mapping["framework_id"] = $framework_id[$frow];
                    $framework_mapping["framework_count"] = $framework_count[$frow];
                    $framework_mapping["created_by"] = $this->get_auth_user("id");
                    $framework_mapping["created_date"] = date("c");
                    $save_framework_mapping = $this->dao->save($framework_mapping);
                    if (!$save_framework_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }


            // Upload images and pdf
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $pdf_id = $this->bean->get_request("pdf_id");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }

                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );

                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {

                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );


                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );


                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            $errors = "";
            if (!empty($uploads)) {
                $this->dao->_table = "file_upload_safety_observation_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "") {
                        $post_uploads["line_function_id"] = $data["id"];
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }





            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }
            // Save File Caption
            if ($caption != "" && $pdf_id) {
                $captiondata["id"] = $pdf_id;
                $captiondata["name"] = $caption;
                $this->dao->_table = "post_uploads";
                $this->dao->save($captiondata);
            }
            // End			
            // Add popular post entry
            if ($data["status_id"] == 3 && $data["id"]) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $data["id"]);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $data["id"]));
                    if ($this->dao->get_query_error() != "")
                        die($this->dao->get_query_error());
                }
            }
            // End popular post
            $this->beanUi->set_success_message("Safety observation is successfully updated.");
            redirect(page_link("activity/index.php?activity_id=" . $data["activity_type_id"]));
        }

        $id = $this->bean->get_request("id");
        $framework = $this->dao->getFramework();
        $getframework_value = $this->dao->getFrameworkById($id);
        $arraydata = array();
        $frmwrkval = "";
        foreach ($framework as $row) {
            $arraydata[$row->id] = $this->dao->getFrameworkValue($row->id);
        }
        $this->beanUi->set_view_data("framework_value", $arraydata);
        $this->beanUi->set_view_data("framework", $framework);
        $this->beanUi->set_view_data("getframework_value", $getframework_value);
        if (!$id) {
            $this->beanUi->set_error_message("Could not load the page, post id is missing.");
            redirect("./");
        }
        $posts = $this->dao->fetchdata($id, "safety_observation_line_function_view");
        $post = !empty($posts) ? $posts[0] : array();
        $post_uploads = $this->dao->get_observation_uploads($post->id);
        $skip_fields = array("post_cat_name", "sugst_post_cat_name", "synopsis", "description", "created_by_name");
        $this->beanUi->set_array_to_view_data($post, $skip_fields);
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("post_uploads", $post_uploads);
        $clause = "line_function_id=" . $id;
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "safety_observation_line_function_division_mapping_view");
        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */
        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("post_division_department_mapping", $division_department_mapping);

        $clause = " line_function_id=" . $id . " ";
        $deviation = $this->dao->get_linefunction_deviation_by_activity($clause);
        $this->beanUi->set_view_data("deviation", $deviation);

        //show($deviation);
        $catid_array = array();
        foreach ($division_department_mapping as $ddm) {
            $catid_array[] = $ddm->division_id;
        }
        /* type - P/F */

        $this->beanUi->set_view_data("cat_id", $catid_array);
        $clause = " activity_id=" . $id . " AND type = 'P'";
        $aaa = $this->dao->get_participants_by_activity($clause);
        foreach ($aaa as $actvalue) {
            $related_activities = $this->dao->get_participants_details($actvalue->activity_id, $actvalue->participant_cat_id);
            $participants_list[$actvalue->participant_cat_id] = $related_activities;
        }
        $this->beanUi->set_view_data("participants_list", $participants_list);
        $this->beanUi->set_view_data("post_activity_participants_category_mapping", $activity_participants_category_mapping);
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_activity_type_id", $post->activity_type_id);
    }

    public function edit_ppe_audit() {
        $_action = $this->bean->get_request("_action");
        if ($_action == "update") {
            $data = $this->bean->get_request("data");
            $page = $this->bean->get_request("page") ? $this->bean->get_request("page") : 1;
            $status_id = $this->bean->get_request("status_id");
            $search_title = $this->bean->get_request("search_title");
            $fromdate = $this->bean->get_request("fromdate");
            $todate = $this->bean->get_request("todate");
            $activity_no = $this->bean->get_request("activity_no");
            $districtid = $this->bean->get_request("districtid");
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $draftstatus = $data["status_id"];
            if ($data["status_id"] == 7) {
                $data["status_id"] = 1;
            }
            if ($_POST['featured_image_path'] != '') {
                $oldimg = explode("?", end(explode("/", $_POST['featured_image_path_old'])));
                $image1 = $oldimg[0];
                $old_original_img = str_replace("-avatar", "", $oldimg[0]);
                @unlink(UPLOADS_PATH . "/activities/" . $image1);
                @unlink(UPLOADS_PATH . "/activities/" . $old_original_img);
                $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
                $data["featured_image_path"] = $featured_img[0];
            }
            $this->dao->_table = "ppe_audit";
            $update_post = $this->dao->save($data);

            /*             * ****activity logs***** */
            if ($update_post) {
                if ($draftstatus != 1) {
                    $activity_logs["status_id"] = $draftstatus;
                    $activity_logs["activity_id"] = $data["id"];
                    $activity_logs["activity_type_id"] = $data["activity_type_id"];
                    $this->dao->_table = "activity_logs";
                    $this->dao->save($activity_logs);
                }
            }
            /*             * ****activity logs***** */

            if (!$update_post) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            // End
            // Delete old featured image if new one is uploaded.
            if ($old_featured_image != "")
                @unlink(BASE_PATH . "/" . $old_featured_image);
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $pdf_id = $this->bean->get_request("pdf_id");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }
                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            $errors = "";
            if (!empty($uploads)) {
                $this->dao->_table = "file_upload_ppe_audit_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "") {
                        $post_uploads["ppe_audit_id"] = $data["id"];
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }

            /*             * ***<< Division Mapping Insert ****** */
            $division = $this->bean->get_request("division");
            if (!empty($division)) {
                $this->dao->_table = "ppe_audit_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["ppe_audit_id"] = $data["id"];
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            /*             * ***Division Mapping Insert  >> ****** */



            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            /*             * *Save File Caption*** */
            if ($caption != "" && $pdf_id) {
                $captiondata["id"] = $pdf_id;
                $captiondata["name"] = $caption;
                $this->dao->_table = "post_uploads";
                $this->dao->save($captiondata);
            }
            // End
            // Add popular post entry
            if ($data["status_id"] == 3 && $data["id"]) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $data["id"]);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $data["id"]));
                    if ($this->dao->get_query_error() != "")
                        die($this->dao->get_query_error());
                }
            }
            // End popular post



            $this->beanUi->set_success_message("PPE Audit is successfully updated.");
            redirect(page_link("activity/index.php?status_id=".$status_id."&districtid=".$districtid."&activity_no=".$activity_no."&search_title=".$search_title."&fromdate=".$fromdate."&todate=".$todate."&page=".$page."&activity_id=" . $data["activity_type_id"]));
        }

        $id = $this->bean->get_request("id");
        if (!$id) {
            $this->beanUi->set_error_message("Could not load the page, post id is missing.");
            redirect("./");
        }
        $posts = $this->dao->fetchdata($id, "ppe_audit_view");
        $post = !empty($posts) ? $posts[0] : array();
        $post_uploads = $this->dao->get_ppe_audit_uploads($post->id);
        $skip_fields = array("post_cat_name", "sugst_post_cat_name", "synopsis", "description");
        $this->beanUi->set_array_to_view_data($post, $skip_fields);
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("post_uploads", $post_uploads);
        $clause = " ppe_audit_id=" . $id;
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "ppe_audit_division_mapping_view");
        $post_division_department = $this->dao->get_division_department();
        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */
        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("post_division_department_mapping", $division_department_mapping);
        $catid_array = array();
        foreach ($division_department_mapping as $ddm) {
            $catid_array[] = $ddm->division_id;
        }
        $this->beanUi->set_view_data("cat_id", $catid_array);
        $clause = " activity_id=" . $id . " AND type = 'P'";
        $aaa = $this->dao->get_participants_by_activity($clause);
        $participants_list = array();
        foreach ($aaa as $actvalue) {
            $related_activities = $this->dao->get_participants_details($actvalue->activity_id, $actvalue->participant_cat_id);
            $participants_list[$actvalue->participant_cat_id] = $related_activities;
        }


        $clause = " ppe_audit_id=" . $id . " ";
        $deviation = $this->dao->get_deviation_by_activity($clause);
        $this->beanUi->set_view_data("deviation", $deviation);
        $this->beanUi->set_view_data("participants_list", $participants_list);
        $this->beanUi->set_view_data("post_activity_participants_category_mapping", $activity_participants_category_mapping);
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_activity_type_id", $post->activity_type_id);
    }
    public function edit_ppe_audit_nm() {
        $_action = $this->bean->get_request("_action");
        if ($_action == "update") {
            $data = $this->bean->get_request("data");
            $page = $this->bean->get_request("page") ? $this->bean->get_request("page") : 1;
            $status_id = $this->bean->get_request("status_id");
            $search_title = $this->bean->get_request("search_title");
            $fromdate = $this->bean->get_request("fromdate");
            $todate = $this->bean->get_request("todate");
            $activity_no = $this->bean->get_request("activity_no");
            $districtid = $this->bean->get_request("districtid");
            $data["modified_by"] = $this->get_auth_user("id");
            $data["modified_date"] = date("c");
            $draftstatus = $data["status_id"];
            if ($data["status_id"] == 7) {
                $data["status_id"] = 1;
            }
            if ($_POST['featured_image_path'] != '') {
                $oldimg = explode("?", end(explode("/", $_POST['featured_image_path_old'])));
                $image1 = $oldimg[0];
                $old_original_img = str_replace("-avatar", "", $oldimg[0]);
                @unlink(UPLOADS_PATH . "/activities/" . $image1);
                @unlink(UPLOADS_PATH . "/activities/" . $old_original_img);
                $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
                $data["featured_image_path"] = $featured_img[0];
            }
            $this->dao->_table = "ppe_audit";
            $update_post = $this->dao->save($data);

            /*             * ****activity logs***** */
            if ($update_post) {
                if ($draftstatus != 1) {
                    $activity_logs["status_id"] = $draftstatus;
                    $activity_logs["activity_id"] = $data["id"];
                    $activity_logs["activity_type_id"] = $data["activity_type_id"];
                    $this->dao->_table = "activity_logs";
                    $this->dao->save($activity_logs);
                }
            }
            /*             * ****activity logs***** */

            if (!$update_post) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            // End
            // Delete old featured image if new one is uploaded.
            if ($old_featured_image != "")
                @unlink(BASE_PATH . "/" . $old_featured_image);
            $uploads = array();
            $caption = $this->bean->get_request("caption");
            $pdf_id = $this->bean->get_request("pdf_id");
            $image_captions = $this->bean->get_request("image_captions");
            $video_captions = $this->bean->get_request("video_captions");
            if (!empty($_FILES)) {
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }
                    if ($input_name == "image_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/activities",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($image_captions[$index_no]) ? $image_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "video_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "video",
                                "upload_path" => UPLOADS_PATH . "/videos",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($video_captions[$index_no]) ? $video_captions[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 0;
                            $uploads[] = $upload_info;
                        }
                    } elseif ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/scope_attachment",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                }
            }

            $errors = "";
            if (!empty($uploads)) {
                $this->dao->_table = "file_upload_ppe_audit_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "") {
                        $post_uploads["ppe_audit_id"] = $data["id"];
                        $post_uploads["file_path"] = $uprow["upload_path"];
                        $post_uploads["file_type"] = $uprow["file_type"];
                        $post_uploads["name"] = $uprow["name"];
                        $post_uploads["created_date"] = date("c");
                        $post_uploads["type_id"] = $uprow["type_id"];
                        $upload_update = $this->dao->save($post_uploads);
                        if (!$upload_update) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }

            /*             * ***<< Division Mapping Insert ****** */
            $division = $this->bean->get_request("division");
            if (!empty($division)) {
                $this->dao->_table = "ppe_audit_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["ppe_audit_id"] = $data["id"];
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--','-',$treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }
            /*             * ***Division Mapping Insert  >> ****** */



            if ($errors != "") {
                $errors = trim($errors, ",");
                $this->beanUi->set_error_message($errors);
                redirect();
            }

            /*             * *Save File Caption*** */
            if ($caption != "" && $pdf_id) {
                $captiondata["id"] = $pdf_id;
                $captiondata["name"] = $caption;
                $this->dao->_table = "post_uploads";
                $this->dao->save($captiondata);
            }
            // End
            // Add popular post entry
            if ($data["status_id"] == 3 && $data["id"]) {
                $row = $this->dao->select("SELECT id FROM popular_posts WHERE post_id = " . $data["id"]);
                $popular_post_id = !empty($row) ? $row[0]->id : 0;
                if (!$popular_post_id) {
                    $this->dao->_table = "popular_posts";
                    $this->dao->save(array("post_id" => $data["id"]));
                    if ($this->dao->get_query_error() != "")
                        die($this->dao->get_query_error());
                }
            }
            // End popular post



            $this->beanUi->set_success_message("PPE Audit is successfully updated.");
            redirect(page_link("activity/index.php?status_id=".$status_id."&districtid=".$districtid."&activity_no=".$activity_no."&search_title=".$search_title."&fromdate=".$fromdate."&todate=".$todate."&page=".$page."&activity_id=" . $data["activity_type_id"]));
        }

        $id = $this->bean->get_request("id");
        if (!$id) {
            $this->beanUi->set_error_message("Could not load the page, post id is missing.");
            redirect("./");
        }
        $posts = $this->dao->fetchdata($id, "ppe_audit_view");
        $post = !empty($posts) ? $posts[0] : array();
        $post_uploads = $this->dao->get_ppe_audit_uploads($post->id);
        $skip_fields = array("post_cat_name", "sugst_post_cat_name", "synopsis", "description");
        $this->beanUi->set_array_to_view_data($post, $skip_fields);
        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("post_uploads", $post_uploads);
        $clause = " ppe_audit_id=" . $id;
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "ppe_audit_division_mapping_view");
        $post_division_department = $this->dao->get_division_department();
        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */
        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("post_division_department_mapping", $division_department_mapping);
        $catid_array = array();
        foreach ($division_department_mapping as $ddm) {
            $catid_array[] = $ddm->division_id;
        }
        $this->beanUi->set_view_data("cat_id", $catid_array);
        $clause = " activity_id=" . $id . " AND type = 'P'";
        $aaa = $this->dao->get_participants_by_activity($clause);
        $participants_list = array();
        foreach ($aaa as $actvalue) {
            $related_activities = $this->dao->get_participants_details($actvalue->activity_id, $actvalue->participant_cat_id);
            $participants_list[$actvalue->participant_cat_id] = $related_activities;
        }


        $clause = " ppe_audit_id=" . $id . " ";
        $deviation = $this->dao->get_deviation_by_activity($clause);
        $this->beanUi->set_view_data("deviation", $deviation);
        $this->beanUi->set_view_data("participants_list", $participants_list);
        $this->beanUi->set_view_data("post_activity_participants_category_mapping", $activity_participants_category_mapping);
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_activity_type_id", $post->activity_type_id);
    }

    public function get_subcategories() {
        $parent_category_name = $this->bean->get_request("parent_category_name");
        $category_id = $this->bean->get_request("category_id");
        $catlavel = $this->bean->get_request("catlavel");
        $prev_cat_id = $this->bean->get_request("prev_cat_id");

        $clause = "";
        if ($parent_category_name == "")
            die();

        $categories = $this->dao->get_categories();
        $selected_l2 = get_value_by_id($category_id, "category_label_2", $categories);
        $selected_l3 = get_value_by_id($category_id, "category_label_3", $categories);
        $cathtml = "";

        if ($catlavel == 2 && $category_id != '') {
            $clause = "parent_id = '" . $category_id . "'";
            $categories = $this->dao->get_division_department($clause);

            if (!empty($categories)) {
                $cathtml = '<select onchange="get_subcategories(this.options[this.selectedIndex].text, this.value, 3)">' . "\n";
                $cathtml .= '<option value="" selected> -- Select One -- </option>' . "\n";
                foreach ($categories as $catrow) {
                    if ($category_id == $catrow->id && $prev_cat_id) {
                        $cathtml .= '<option value="' . $catrow->id . '" selected>' . $catrow->name . '</option>' . "\n";
                    } else {
                        $cathtml .= '<option value="' . $catrow->id . '">' . $catrow->name . '</option>' . "\n";
                    }
                }
                $cathtml .= '</select>' . "\n";
            }
            die($cathtml);
        } elseif ($catlavel == 3) {
            $clause = "parent_id = '" . $category_id . "'";
            $categories = $this->dao->get_division_department($clause);
            if (!empty($categories)) {
                $cathtml .= '<select name="data[post_category_id]" id="post_category_id">' . "\n";
                $cathtml .= '<option value="" selected> -- Select One -- </option>' . "\n";
                foreach ($categories as $catrow) {
                    if ($category_id == $catrow->id && $prev_cat_id) {
                        $cathtml .= '<option value="' . $catrow->id . '" selected>' . $catrow->name . '</option>' . "\n";
                    } else {
                        $cathtml .= '<option value="' . $catrow->id . '">' . $catrow->name . '</option>' . "\n";
                    }
                }
                $cathtml .= '</select>' . "\n";
            }
            die($cathtml);
        } else {
            
        }
    }

    public function delete_upload() {
        $id = $this->bean->get_request("id");
        $table = $this->bean->get_request("t");
        $table_name = base64_decode($table);
        $pagename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
        $post_id = $this->bean->get_request("post_id");
        if (!$id || !$post_id) {
            $this->beanUi->set_error_message("Id is missing.");
            if ($post_id)
                redirect(page_link("activity/edit.php?id=" . $post_id));
            else
                redirect(page_link("./"));
        }

        $row = $this->dao->select("SELECT file_path FROM  " . $table_name . " WHERE id = " . $id);
        $asset_base_path = dirname(BASE_PATH);

        if (!empty($row)) {
            $file_path = $asset_base_path . "/" . $row[0]->file_path;
            @unlink($file_path);
            $this->dao->_table = $table_name;
            $this->dao->del(array("id" => $id));
        }
        $this->beanUi->set_success_message("Successfully Deleted.");
        redirect(page_link("activity/$pagename?id=" . $post_id));
    }

    public function delete_featured_image() {
        $id = $this->bean->get_request("id");
        $table = $this->bean->get_request("t");
        $table_name = base64_decode($table);
        $pagename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
        if (!$id) {
            $this->beanUi->set_error_message("Id is missing.");
            redirect(page_link("activity/"));
        }
        $row = $this->dao->select("SELECT featured_image_path FROM " . $table_name . " WHERE id = " . $id);
        $featured_image_path = !empty($row) ? $row[0]->featured_image_path : "";
        $image_abs_path = CESC_BASE_PATH . "/" . $featured_image_path;

        if ($featured_image_path != '') {
            $oldimg = explode("?", end(explode("/", $featured_image_path)));
            $image1 = $oldimg[0];
            $old_original_img = str_replace("-avatar", "", $oldimg[0]);
            @unlink(UPLOADS_PATH . "/activities/" . $image1);
            @unlink(UPLOADS_PATH . "/activities/" . $old_original_img);
        }

        $data["id"] = $id;
        $data["featured_image_path"] = "";
        $data["featured_image_original"] = "";
        $data["modified_by"] = $this->get_auth_user("id");
        $data["modified_date"] = date("c");
        $this->dao->save($data);
        $this->beanUi->set_success_message("Featured image successfully deleted.");
        redirect(page_link("activity/$pagename?id=" . $id));
    }

    public function save_caption() {
        $upload_id = $this->bean->get_request("upload_id");
        $caption = $this->bean->get_request("caption");
        $table_name = $this->bean->get_request("table_name");
        $saved = 0;
        if ($upload_id) {
            $this->dao->_table = $table_name;
            if (
                    $this->dao->save(
                            array("id" => $upload_id, "name" => $caption)
                    )
            )
                $saved++;
        }
        die("$saved");
    }

    public function delete_activity() {

        $data = array();
        $id = $this->bean->get_request("id");
        $table = $this->bean->get_request("t");
        $table_name = base64_decode($table);
        $activity_type_id = $this->bean->get_request("activity_id");
        $page = $this->bean->get_request("page");
        $status_id = $this->bean->get_request("status_id");
        $search_title = $this->bean->get_request("search_title");
        $activity_no = $this->bean->get_request("activity_no");
        $activity_month = $this->bean->get_request("activity_month");
        $incident_category_ids = $this->bean->get_request("incident_category_ids");
        $fromdate = $this->bean->get_request("fromdate");
        $todate = $this->bean->get_request("todate");
        $data["id"] = $id;
        $data["deleted"] = 1;
        //free sas
        if($activity_type_id == 7){
            $clauseD         =   " deleted = '0' AND id ='".$id."'";
            $getD    =   $this->dao->fetchAllData('ppe_audit',$clauseD);
            $clauseDD         =   " sas_report_no ='".$getD[0]->sas_report_no."'";
            $getDD    =   $this->dao->fetchAllData('mis_sas_ppe',$clauseDD);
            $sasNoArrDD = array();
            if($getDD[0]->id){
                $sasNoArrDD["id"] = $getDD[0]->id;
                $sasNoArrDD["is_selected"]= 0;
                $sasNoArrDD["modified_by"]            = $this->get_auth_user("id");
                $sasNoArrDD["modified_date"]          = date("c");//show($sasNoArrDD);
                $this->dao->_table = "mis_sas_ppe";
                $this->dao->save($sasNoArrDD);
            }
            $clauseDF         =   " sas_report_no ='".$getD[0]->sas_report_no."'";
            $getDF    =   $this->dao->fetchAllData('file_upload_ppe_audit_mapping',$clauseDF);
            $sasNoArrDF = array();
            foreach ($getDF AS $kDF => $vDF){
                if($vDF->id){
                    $sasNoArrDF["id"] = $vDF->id;
                    $sasNoArrDF["deleted"]= 1;
                    $sasNoArrDF["modified_by"]            = $this->get_auth_user("id");
                    $sasNoArrDF["modified_date"]          = date("c");
                    $this->dao->_table = "file_upload_ppe_audit_mapping";
                    $this->dao->save($sasNoArrDF);
                }
            }
        }
        if($activity_type_id == 5){
            $clauseD         =   " deleted = '0' AND id ='".$id."'";
            $getD    =   $this->dao->fetchAllData('audit',$clauseD);
            $clauseDD         =   " sas_report_no ='".$getD[0]->sas_report_no."'";
            $getDD    =   $this->dao->fetchAllData('mis_sas',$clauseDD);
            $sasNoArrDD = array();
            if($getDD[0]->id){
                $sasNoArrDD["id"] = $getDD[0]->id;
                $sasNoArrDD["is_selected"]= 0;
                $sasNoArrDD["modified_by"]            = $this->get_auth_user("id");
                $sasNoArrDD["modified_date"]          = date("c");
                $this->dao->_table = "mis_sas";
                $this->dao->save($sasNoArrDD);
            }
            $clauseDF         =   " sas_report_no ='".$getD[0]->sas_report_no."'";
            $getDF    =   $this->dao->fetchAllData('file_upload_audit_mapping',$clauseDF);
            $sasNoArrDF = array();
            foreach ($getDF AS $kDF => $vDF){
                if($vDF->id){
                    $sasNoArrDF["id"] = $vDF->id;
                    $sasNoArrDF["deleted"]= 1;
                    $sasNoArrDF["modified_by"]            = $this->get_auth_user("id");
                    $sasNoArrDF["modified_date"]          = date("c");
                    $this->dao->_table = "file_upload_audit_mapping";
                    $this->dao->save($sasNoArrDF);
                }
            }
        }
        
        //free sas 
        if (!$id) {
            $this->beanUi->set_error_message("Id is missing.");
            redirect(page_link("activity/"));
        }
        $this->dao->_table = $table_name;
        $row = $this->dao->select("SELECT featured_image_path FROM " . $this->dao->_table . " WHERE id = " . $id);
        $featured_image_path = !empty($row) ? $row[0]->featured_image_path : "";
        $image_abs_path = CESC_BASE_PATH . "/" . $featured_image_path;
        @unlink($image_abs_path);
        @unlink($pdf_abs_path);
        $fileupload_table_name = "";
        $where_clause = "";
        if ($table_name == "activity") {
            $fileupload_table_name = " file_upload_activity_mapping ";
            $where_clause = " WHERE activity_id = " . $id;
        }
        if ($table_name == "audit") {
            $fileupload_table_name = " file_upload_audit_mapping ";
            $where_clause = " WHERE audit_id = " . $id;
        }
        if ($table_name == "incident") {
            $fileupload_table_name = " file_upload_incident_mapping ";
            $where_clause = " WHERE incident_id = " . $id;
        }
        if ($table_name == "ppe_audit") {
            $fileupload_table_name = " file_upload_ppe_audit_mapping ";
            $where_clause = " WHERE ppe_audit_id = " . $id;
        }
        if (!empty($fileupload_table_name) && $where_clause != "") {
           
            $row2 = $this->dao->select("SELECT * FROM " . $fileupload_table_name . $where_clause);            
            $imagepath = !empty($row2) ? $row2[0]->file_path : "";            
            $image_to_delete = CESC_BASE_PATH . "/" . $imagepath;
            @unlink($image_to_delete);
        }
        $this->dao->_table = $table_name;
        if ($this->dao->save($data))
            $this->beanUi->set_success_message("Activity is deleted.");
        redirect(page_link("activity/?status_id=".$status_id."&search_title=".$search_title."&activity_no=".$activity_no."&activity_month=".$activity_month."&incident_category_ids=".$incident_category_ids."&fromdate=".$fromdate."&todate=".$todate."&activity_id=" . $activity_type_id . "&page=" . $page));
    }

    public function p_delete() {
        $activity_id = $this->bean->get_request("activity_id");
        $participant_cat_id = $this->bean->get_request("participant_cat_id");
        $no_of_emp = $this->bean->get_request("no_of_emp");
        if (!empty($no_of_emp)) {
            $this->dao->update_participants_mapping($no_of_emp, $activity_id, $participant_cat_id, "D");
        }
        $id = $this->bean->get_request("message_id");
        $this->dao->_table = "activity_participants_mapping";
        if ($this->dao->del(array("id" => $id)))
            $this->beanUi->set_success_message("Record is deleted.");
        redirect(page_link("activity/edit.php?id=" . $activity_id . "&page=" . $page));
    }

    public function f_delete() {
        $activity_id = $this->bean->get_request("activity_id");
        $participant_cat_id = $this->bean->get_request("participant_cat_id");
        $no_of_emp = $this->bean->get_request("no_of_emp");
        if (!empty($no_of_emp)) {
            $this->dao->update_participants_mapping($no_of_emp, $activity_id, $participant_cat_id, "D");
        }
        $id = $this->bean->get_request("message_id");
        $this->dao->_table = "activity_participants_mapping";
        if ($this->dao->del(array("id" => $id)))
            $this->beanUi->set_success_message("Record is deleted.");
        redirect(page_link("activity/edit.php?id=" . $activity_id . "&page=" . $page));
    }

    public function finding_delete() {
        $id = $this->bean->get_request("message_id");
        $incident_id = $this->bean->get_request("incident_id");
        $this->dao->_table = "incident_finding_mapping";
        if ($this->dao->del(array("id" => $id))) {

            $query = "UPDATE `incident` SET `no_of_finding` = no_of_finding-1 WHERE `id` = :id";
            $result = $this->dao->db->prepare($query);
            $result->execute(array(":id" => $incident_id));
            $this->beanUi->set_success_message("Finding is deleted.");
        }
    }

    public function violation_delete() {
        $id = $this->bean->get_request("message_id");
        $audit_id = $this->bean->get_request("audit_id");
        $this->dao->_table = "audit_violation_mapping";



        if ($this->dao->del(array("id" => $id))) {
            $query = "UPDATE `audit` SET `no_of_violation` = no_of_violation-1 WHERE `id` = :id";
            $result = $this->dao->db->prepare($query);
            $result->execute(array(":id" => $audit_id));

            $this->beanUi->set_success_message("Violation is deleted.");
        }
    }

    public function linefunc_deviation_delete() {
        $id = $this->bean->get_request("message_id");
        $line_function_id = $this->bean->get_request("line_function_id");
        $this->dao->_table = "safety_observation_line_function_deviation_mapping";
        if ($this->dao->del(array("id" => $id))) {
            $query = "UPDATE `safety_observation_line_function` SET `no_of_deviation` = no_of_deviation-1 WHERE `id` = :id";
            $result = $this->dao->db->prepare($query);
            $result->execute(array(":id" => $line_function_id));
            $this->beanUi->set_success_message("Deviation is deleted.");
        }
    }

    public function deviation_delete() {
        $id = $this->bean->get_request("message_id");
        $this->dao->_table = "ppe_audit_deviation_mapping";
        if ($this->dao->del(array("id" => $id)))
            $this->beanUi->set_success_message("Deviation is deleted.");
    }

    public function deficiency_delete() {
        $id = $this->bean->get_request("message_id");
        $ppe_audit_id = $this->bean->get_request("ppe_audit_id");
        $this->dao->_table = "ppe_audit_deviation_mapping";
        if ($this->dao->del(array("id" => $id))) {
            $query = "UPDATE `ppe_audit` SET `no_of_deviation` = no_of_deviation-1 WHERE `id` = :id";
            $result = $this->dao->db->prepare($query);
            $result->execute(array(":id" => $ppe_audit_id));
            $this->beanUi->set_success_message("Deficiency is deleted.");
        }
    }

    public function delete_participant() {
        $id = $this->bean->get_request("id");
        $activity_id = $this->bean->get_request("activity_id");
        $page = $this->bean->get_request("page");
        if (!$id) {
            $this->beanUi->set_error_message("Id is missing.");
            redirect(page_link("activity/participants.php?activity_id=" . $activity_id));
        }
        $clause = " id=" . $id;
        $activity_participants_mapping = $this->dao->get_activity_participants_mapping($clause);
        $activity_participant_category_id = $activity_participants_mapping[0]->activity_participant_category_id;
        $query = "UPDATE `activity_participant_category_mapping` SET `no_of_participants` = no_of_participants-1 WHERE `id` = :id";
        $result = $this->dao->db->prepare($query);
        $result->execute(array(":id" => $activity_participant_category_id));
    }

    public function view() {
        $id = $this->bean->get_request('id');
        $activity_type_id = $this->bean->get_request('actype_id');
        if (empty($id)) {
            redirect(page_link("index.php?activity_id=" . $activity_type_id));
        }
        $activity = $this->dao->getactivity($id, $subid = Null, $type = "activity");
        $clause = " activity_id=:activity_id";
        $division_department = $this->dao->get_division_by_activity($clause,array("activity_id"=>$id));
        $pclause = " activity_id=:activity_id";
        $activity_participants = $this->dao->get_participants_by_activity_new($pclause,array("activity_id"=>$id));
        $participants_list = array();

        foreach ($activity_participants as $actvalue) {
            $related_activities = $this->dao->get_participants_details_new($actvalue->id);
            $participants_list[$actvalue->id] = $related_activities;
        }
        $this->beanUi->set_view_data("activity_type_id", $activity_type_id);
        $this->beanUi->set_view_data("participants_list", $participants_list);
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());

        $clause = " activity_id=:activity_id";
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "activity_division_mapping_view",array("activity_id"=>$id));
        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */
        $this->beanUi->set_view_data("activity", $activity);
        $this->beanUi->set_view_data("division_department", $division_department);
        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("activity_participants", $activity_participants);
    }

    /* Division Department Tree */

    public function get_nextlevel() {
        $id = $this->bean->get_request("id");
        $level_count = $this->bean->get_request("lcount");
        $childvalue = $this->dao->get_childvalue($id);
        $labelname = $this->dao->get_labelname($id);
        $tbvalue = $this->dao->check_tbvalue($id);
        $restrict_arr = array('266','268','269','271','272','274','275','277','278','280','281','283','284','286','287','289','290','292','293','295');
        $exp = explode(",", $tbvalue);
        for ($i = 0; $i < count($exp); $i++) {
            $setvalue[$exp[$i]] = $this->dao->get_setvalue($exp[$i], $id);
        }
        $new_dropdown = '';
        if (!empty($childvalue)) {
            $new_dropdown .= '<span style="float:left;width:150px;padding-top: 6px;"><b>' . $labelname . '</b></span>';
            $new_dropdown .= '<select class="division" name="L' . $level_count . '"  id="L' . $level_count . '">';
            $new_dropdown .= '<option value="" selected="" disabled>choose one</option>';
            foreach ($childvalue as $cval) {                
                if(!in_array($cval->id,$restrict_arr)) {
                    $new_dropdown .= '<option pid="'.$cval->parent_id.'"  value="' . $cval->id . '">' . $cval->name . '</option>';                
                }
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
                $new_dropdown .= '<div class="xyz"><span style="float:left;width:150px;padding-top: 6px;"><b>' . $label . '</b></span>';
                $new_dropdown .= '<select  class="division newcons ' . $extraClass . '" name="contractor_details"  id="contractor' . $key . '">';
                $new_dropdown .= '<option value="" selected="" disabled>choose one  </option>';
                foreach ($rowvalue as $cval) {
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
                $new_dropdown .= '</select><span id="show_location"></span></div>';
                $new_dropdown .= '<br /><div id="' . $key . '"></div>';
            }
        }
        echo $new_dropdown;
        die();
    }
    /* Division Department Tree for Non-Mains*/

    public function get_nextlevel_m() {
        $id = $this->bean->get_request("id");
        $level_count = $this->bean->get_request("lcount");
        $childvalue = $this->dao->get_childvalue($id);
        $labelname = $this->dao->get_labelname($id);
        $tbvalue = $this->dao->check_tbvalue($id);
        $restrict_arr = array('2','4','6','7','8','266','268','269','271','272','274','275','277','278','280','281','283','284','286','287','289','290','292','293','295');
        $exp = explode(",", $tbvalue);
        for ($i = 0; $i < count($exp); $i++) {
            $setvalue[$exp[$i]] = $this->dao->get_setvalue($exp[$i], $id);
        }
        $new_dropdown = '';
        if (!empty($childvalue)) {
            $new_dropdown .= '<span style="float:left;width:150px;padding-top: 6px;"><b>' . $labelname . '</b></span>';
            $new_dropdown .= '<select class="division" name="L' . $level_count . '"  id="L' . $level_count . '">';
            $new_dropdown .= '<option value="" selected="" disabled>choose one</option>';
            foreach ($childvalue as $cval) {                
                if(!in_array($cval->id,$restrict_arr)) {
                    $new_dropdown .= '<option pid="'.$cval->parent_id.'"  value="' . $cval->id . '">' . $cval->name . '</option>';                
                }
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
                $new_dropdown .= '<div class="xyz"><span style="float:left;width:150px;padding-top: 6px;"><b>' . $label . '</b></span>';
                $new_dropdown .= '<select  class="division newcons ' . $extraClass . '" name="contractor_details"  id="contractor' . $key . '">';
                $new_dropdown .= '<option value="" selected="" disabled>choose one  </option>';
                foreach ($rowvalue as $cval) {
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
                $new_dropdown .= '</select><span id="show_location"></span></div>';
                $new_dropdown .= '<br /><div id="' . $key . '"></div>';
            }
        }
        echo $new_dropdown;
        die();
    }
    /* Division Department Tree for Non-Mains*/

    public function get_nextlevel_nm() {
        $id = $this->bean->get_request("id");
        $level_count = $this->bean->get_request("lcount");
        $childvalue = $this->dao->get_childvalue($id);
        $labelname = $this->dao->get_labelname($id);
        $tbvalue = $this->dao->check_tbvalue($id);
        $restrict_arr = array('5','266','268','269','271','272','274','275','277','278','280','281','283','284','286','287','289','290','292','293','295');
        $exp = explode(",", $tbvalue);
        for ($i = 0; $i < count($exp); $i++) {
            $setvalue[$exp[$i]] = $this->dao->get_setvalue($exp[$i], $id);
        }
        $new_dropdown = '';
        if (!empty($childvalue)) {
            $new_dropdown .= '<span style="float:left;width:150px;padding-top: 6px;"><b>' . $labelname . '</b></span>';
            $new_dropdown .= '<select class="division" name="L' . $level_count . '"  id="L' . $level_count . '">';
            $new_dropdown .= '<option value="" selected="" disabled>choose one</option>';
            foreach ($childvalue as $cval) {                
                if(!in_array($cval->id,$restrict_arr)) {
                    $new_dropdown .= '<option pid="'.$cval->parent_id.'"  value="' . $cval->id . '">' . $cval->name . '</option>';                
                }
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
                $new_dropdown .= '<div class="xyz"><span style="float:left;width:150px;padding-top: 6px;"><b>' . $label . '</b></span>';
                $new_dropdown .= '<select  class="division newcons ' . $extraClass . '" name="contractor_details"  id="contractor' . $key . '">';
                $new_dropdown .= '<option value="" selected="" disabled>choose one  </option>';
                foreach ($rowvalue as $cval) {
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
                $new_dropdown .= '</select><span id="show_location"></span></div>';
                $new_dropdown .= '<br /><div id="' . $key . '"></div>';
            }
        }
        echo $new_dropdown;
        die();
    }

    public function submit_division() {
        $name = $this->bean->get_request("name");
        $tree_dept_id = $this->bean->get_request("tree_dept");
        $autoid = $this->bean->get_request("autoid");
        $nameval = implode("~", $name);
        $exp_value = explode("~", $nameval);

        if (in_array("P-SET", $exp_value, TRUE)) {
            $val = 1;
        } else if(in_array("Call Center", $exp_value, TRUE)) {
            $val = 1;
        } else if (in_array("C-SET", $exp_value, TRUE)) {
            $val = 2;
        } else {
            $val = 3;
        }
        $ids = $this->bean->get_request("ids");
        $imp = implode("-", $ids);
        if ($tree_dept_id == 'department') {
            echo '<tr  id="' . $imp . '" class="' . $val . ' deletrow dddd ' . $autoid . '" ><td><input  type="hidden" id="' . $tree_dept_id . '" value="' . $nameval . '" /><input class="set-type" type="hidden" name="divdept_selection" value="' . $val . '" /><input type="hidden" id="division" name="division[]" value="' . $imp . '" />' . $nameval . '</td>';
            die;
        } else {
            echo '<tr  id="' . $imp . '" class="' . $val . ' deletrow ' . $autoid . '" ><td><input class="set-type" type="hidden" name="divdept_selection" value="' . $val . '" /><input  type="hidden" id="' . $tree_dept_id . '" value="' . $nameval . '" />' . $nameval . '</td>';
            die;
        }
    }

    public function delete_division() {
        $id = $this->bean->get_request("delid");
        $activityid = $this->bean->get_request("id");
        $table = $this->bean->get_request("t");
        $table_name = base64_decode($table);
        $pagename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
        $this->dao->_table = $table_name;
        if ($this->dao->del(array("id" => $id)))
            $this->beanUi->set_success_message("Divison Department Deleted.");
        redirect(page_link("activity/$pagename?id=" . $activityid));
    }

    public function get_contractor_list() {

        $id = $this->bean->get_request("id");
        $clause = " WHERE root_division_id IN (" . $id . ") GROUP BY identification_code ORDER BY name ASC";
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

    public function view_minutes_of_meeting() {
        $id = $this->bean->get_request('id');
        $activity_type_id = $this->bean->get_request('actype_id');
        if (empty($id)) {
            redirect(page_link("index.php?activity_id=" . $activity_type_id));
        }
        $activity = $this->dao->getactivity($id, $subid = Null, $type = "mom");
        $pclause = " activity_id=" . $id;
        $activity_participants = $this->dao->get_participants_by_activity_new($pclause);
        $participants_list = array();
        $this->beanUi->set_view_data("activity_type_id", $activity_type_id);
        $this->beanUi->set_view_data("participants_list", $participants_list);
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());

        /*         * ********division depart for edit end**** */
        $this->beanUi->set_view_data("activity", $activity);
        $this->beanUi->set_view_data("activity_participants", $activity_participants);
    }

    public function view_audit() {
        $id = $this->bean->get_request('id');
        $get_auth_user_id = $this->get_auth_user("id");
        $role_id = $this->get_auth_user("role_id");
        $activity_type_id = $this->bean->get_request('actype_id');
        if (empty($id)) {
            redirect(page_link("index.php?activity_id=" . $activity_type_id));
        }
        $activity = $this->dao->getactivity($id, $subid = Null, $type = "audit");
        $clause = " activity_id= :activity_id ";
        $division_department = $this->dao->get_division_by_activity($clause,array("activity_id"=>$id));
        $pclause = " activity_id=:activity_id ";
        $activity_participants = $this->dao->get_participants_by_activity_new($pclause,array("activity_id"=>$id));
        $participants_list = array();

        foreach ($activity_participants as $actvalue) {
            $related_activities = $this->dao->get_participants_details_new($actvalue->id);
            $participants_list[$actvalue->id] = $related_activities;
        }
        $this->beanUi->set_view_data("activity_type_id", $activity_type_id);
        $this->beanUi->set_view_data("participants_list", $participants_list);
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());

        $clause = " audit_id= :audit_id ";
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "audit_division_mapping_view",array("audit_id"=>$id));

        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */
        $clause = " audit_id=:audit_id ";
        $violation = $this->dao->get_violation_by_activity($clause,array("audit_id"=>$id));
        $deviationfileAction="deleted = '0' AND audit_id=".$id;        
        $alldeviationsactionfile=$this->dao->fetchAllData('file_upload_audit_deviation_action_mapping',$deviationfileAction);
        $deviationClause="is_deleted = 0 AND audit_id=".$id." ORDER BY type_name DESC";      
        $violationnm=$this->dao->fetchAllData('deviation_details_view',$deviationClause);
        $this->beanUi->set_view_data("alldeviationsactionfile", $alldeviationsactionfile);
        $this->beanUi->set_view_data("violation", $violation);
        $this->beanUi->set_view_data("violationnm", $violationnm);
        $this->beanUi->set_view_data("activity", $activity);
        $this->beanUi->set_view_data("division_department", $division_department);
        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("activity_participants", $activity_participants);
        $this->beanUi->set_view_data("get_auth_user_id", $get_auth_user_id);
        $this->beanUi->set_view_data("role_id", $role_id);
    }

    public function view_incident() {
        $id = $this->bean->get_request('id');
        $activity_type_id = $this->bean->get_request('actype_id');
        if (empty($id)) {
            redirect(page_link("index.php?activity_id=" . $activity_type_id));
        }
        $activity = $this->dao->getactivity($id, $subid = Null, $type = "incident");
        $clause = " activity_id= :activity_id ";
        $division_department = $this->dao->get_division_by_activity($clause,array("activity_id" => $id));
        $pclause = " activity_id=:activity_id ";
        $activity_participants = $this->dao->get_participants_by_activity_new($pclause,array("activity_id"=>$id));
        $participants_list = array();

        foreach ($activity_participants as $actvalue) {
            $related_activities = $this->dao->get_participants_details_new($actvalue->id);
            $participants_list[$actvalue->id] = $related_activities;
        }
        $this->beanUi->set_view_data("activity_type_id", $activity_type_id);
        $this->beanUi->set_view_data("participants_list", $participants_list);
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());

        $clause = " incident_id= :incident_id ";
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "incident_division_mapping_view",array("incident_id"=>$id));

        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */
        $incident_category = $this->dao->get_incident_category();
        $this->beanUi->set_view_data("incident_category", $incident_category);


        $nature_of_injury = $this->dao->get_nature_injury();
        $this->beanUi->set_view_data("nature_of_injury", $nature_of_injury);

        $childArray = array();
        $injury_status = array();
        $injury_status1 = array();
        $clause = " WHERE parent_id=:parent_id";
        $body_part_injury = $this->dao->get_body_part_injury($clause,array("parent_id"=>0));

        foreach ($body_part_injury as $k => $rdata) {
            $clausenew = " WHERE parent_id= :parent_id ";
            $getChild = $this->dao->get_body_part_injury($clausenew,array("parent_id"=>$rdata->id));
            $childArray[$rdata->id] = $getChild;
            $pvalue["incident_id"] = $id;
            $pvalue["parent_id"] = $rdata->id;
            $conds = " WHERE incident_id=" . $id . " AND parent_id=:parent_id ";
            $body_part_injury_mappingData1[$rdata->id] = $this->dao->get_body_part_injury_mappingData($conds,$pvalue);

            foreach ($getChild as $ch) {
                $pvalue1["incident_id"] = $id;
                $pvalue1["bodypart_id"] = $ch->id;
                $cond = " WHERE incident_id=:incident_id AND bodypart_id=:bodypart_id ";
                $body_part_injury_mappingData = $this->dao->get_body_part_injury_mappingData($cond,$pvalue1);
                @$injury_status[$rdata->id][$ch->id] = $body_part_injury_mappingData[0];
            }
        }

        $this->beanUi->set_view_data("body_part_injury", $body_part_injury);
        $this->beanUi->set_view_data("childData", $childArray);
        $this->beanUi->set_view_data("injury_status", $injury_status);

        $this->beanUi->set_view_data("body_part_injury_mappingData", $body_part_injury_mappingData1);

        $clause = " incident_id= :incident_id ";
        $findingsx = $this->dao->get_findings_by_activity($clause,array("incident_id"=>$id));
        $this->beanUi->set_view_data("finding_rows", $findingsx);
        
        $violation_category = array(
		 '1' => 'PPEs',
                    '2' => 'SWP',
                    '3' => 'Safety Standard',
                    '4' => 'Job Safety - Working at Height',
                    '5' => 'Job Safety - Hot Job',
                    '6' => 'Job Safety - Confined Space',
                    '7' => 'Job Safety - Chemical',
                    '8' => 'Job Safety - Heavy Material Handling',
                    '9' => 'Reaction Of People',
                    '10' => 'Position Of People',
                    '11' => 'Tools & Equipment',
                    '12' => 'Procudure',
                    '13' => 'Unsafe condition',
                    '14' => 'Unsafe Act'

		);


        $this->beanUi->set_view_data("activity", $activity);
        $this->beanUi->set_view_data("division_department", $division_department);
        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("activity_participants", $activity_participants);
        $this->beanUi->set_view_data("violation_category", $violation_category);
    }

    public function view_ppe_audit() {
        $id = $this->bean->get_request('id');
        $activity_type_id = $this->bean->get_request('actype_id');
        if (empty($id)) {
            redirect(page_link("index.php?activity_id=" . $activity_type_id));
        }
        $activity = $this->dao->getactivity($id, $subid = Null, $type = "ppe_audit");
        $clause = " activity_id=:activity_id";
        $pvalue["activity_id"] = $id;
        $division_department = $this->dao->get_division_by_activity($clause,$pvalue);
        $pclause = " activity_id=:activity_id";
        $ppvalue["activity_id"] = $id;
        $activity_participants = $this->dao->get_participants_by_activity_new($pclause,$ppvalue);
        $participants_list = array();

        foreach ($activity_participants as $actvalue) {
            $related_activities = $this->dao->get_participants_details_new($actvalue->id);
            $participants_list[$actvalue->id] = $related_activities;
        }
        $this->beanUi->set_view_data("activity_type_id", $activity_type_id);
        $this->beanUi->set_view_data("participants_list", $participants_list);
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());

        $clause = " ppe_audit_id=:ppe_audit_id";
        $clValue["ppe_audit_id"] = $id;
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "ppe_audit_division_mapping_view",$clValue);

        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */
        $clause = " ppe_audit_id= :ppe_audit_id ";
        $ppV["ppe_audit_id"] = $id;
        $deviation = $this->dao->get_deviation_by_activity($clause,$ppV);
        $this->beanUi->set_view_data("deviation", $deviation);

        $this->beanUi->set_view_data("activity", $activity);
        $this->beanUi->set_view_data("division_department", $division_department);
        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("activity_participants", $activity_participants);
    }

    public function view_safety_ob() {
        $id = $this->bean->get_request('id');
        $activity_type_id = $this->bean->get_request('actype_id');
        if (empty($id)) {
            redirect(page_link("index.php?activity_id=" . $activity_type_id));
        }
        $activity = $this->dao->getactivity($id, $subid = Null, $type = "safety_observation");
        $clause = " activity_id=" . $id;
        $division_department = $this->dao->get_division_by_activity($clause);
        $pclause = " activity_id=" . $id;
        $activity_participants = $this->dao->get_participants_by_activity_new($pclause);
        $participants_list = array();

        foreach ($activity_participants as $actvalue) {
            $related_activities = $this->dao->get_participants_details_new($actvalue->id);
            $participants_list[$actvalue->id] = $related_activities;
        }
        $this->beanUi->set_view_data("activity_type_id", $activity_type_id);
        $this->beanUi->set_view_data("participants_list", $participants_list);
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());

        $clause = " safety_observation_id=" . $id;
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "safety_observation_division_mapping_view");

        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */

        $this->beanUi->set_view_data("activity", $activity);
        $this->beanUi->set_view_data("division_department", $division_department);
        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("activity_participants", $activity_participants);
    }

    public function view_safety_ob_line() {
        $id = $this->bean->get_request('id');
        $activity_type_id = $this->bean->get_request('actype_id');
        if (empty($id)) {
            redirect(page_link("index.php?activity_id=" . $activity_type_id));
        }
        $activity = $this->dao->getactivity($id, $subid = Null, $type = "safety_observation_line_function");
        $clause = " activity_id=" . $id;
        $division_department = $this->dao->get_division_by_activity($clause);
        $pclause = " activity_id=" . $id;
        $activity_participants = $this->dao->get_participants_by_activity_new($pclause);
        $participants_list = array();


        foreach ($activity_participants as $actvalue) {
            $related_activities = $this->dao->get_participants_details_new($actvalue->id);
            $participants_list[$actvalue->id] = $related_activities;
        }
        $this->beanUi->set_view_data("activity_type_id", $activity_type_id);
        $this->beanUi->set_view_data("participants_list", $participants_list);
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());

        $clause = " line_function_id=" . $id;
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "safety_observation_line_function_division_mapping_view");

        $post_division_department = $this->dao->get_division_department();

        /*         * ********division depart for edit start**** */
        $devition_name = array();
        if (!empty($division_department_mapping)) {
            foreach ($division_department_mapping as $row) {
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
                            $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                            $divition .=!empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                        }
                        $divition .= '/';
                    }
                }
                $devition_name[$row->id] = trim($divition, "/");
            }
        }
        /*         * ********division depart for edit end**** */
        /*         * **framework start*** */

        $framework = $this->dao->getFramework();
        $getframework_value = $this->dao->getFrameworkById($id);
        $arraydata = array();
        $frmwrkval = "";
        foreach ($framework as $row) {
            $arraydata[$row->id] = $this->dao->getFrameworkValue($row->id);
        }
        $this->beanUi->set_view_data("framework_value", $arraydata);
        $this->beanUi->set_view_data("framework", $framework);
        $this->beanUi->set_view_data("getframework_value", $getframework_value);

        $clause = " line_function_id=" . $id . " ";
        $deviation = $this->dao->get_linefunction_deviation_by_activity($clause);
        $this->beanUi->set_view_data("deviation", $deviation);
        /*         * **framework start*** */

        $this->beanUi->set_view_data("activity", $activity);
        $this->beanUi->set_view_data("division_department", $division_department);
        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("activity_participants", $activity_participants);
    }

    public function getDataByDate() {
        $activity_id = $this->bean->get_request('activity_id');//show($activity_id);
        $selected_date = $this->bean->get_request('selected_date');
        $table_name = $this->bean->get_request('table_name');
        $showData = "";

        if ($activity_id == 9) {
            $clause = " WHERE deleted='0' AND activity_type_id='" . $activity_id . "' AND created_by!='2' AND date_of_observation LIKE '%" . $selected_date . "%' ";
        }


        if ($activity_id == 1 || $activity_id == 2 || $activity_id == 4) {
            $clause = " WHERE deleted='0' AND activity_type_id='" . $activity_id . "' AND created_by!='2' AND activity_date LIKE '%" . $selected_date . "%' ";
        } else if ($activity_id == 3) {created_by!='2' AND 
            $clause = " WHERE deleted='0' AND activity_type_id='" . $activity_id . "' AND created_by!='2' AND from_date LIKE '%" . $selected_date . "%' ";
        } else if ($activity_id == 5 || $activity_id == 7) {created_by!='2' AND 
            $clause = " WHERE deleted='0' AND activity_type_id='" . $activity_id . "' AND created_by!='2' AND date_of_audit LIKE '%" . $selected_date . "%' ";
        } else if ($activity_id == 6) {created_by!='2' AND 
            $clause = " WHERE deleted='0' AND activity_type_id='" . $activity_id . "' AND created_by!='2' AND date_of_incident LIKE '%" . $selected_date . "%' ";
        } else if ($activity_id == 9) {created_by!='2' AND 
            $clause = " WHERE deleted='0' AND activity_type_id='" . $activity_id . "' AND created_by!='2' AND date_of_observation LIKE '%" . $selected_date . "%' ";
        }

        $showDataByDate = $this->dao->get_DataByDate($table_name, $clause);
        if (count($showDataByDate) > 0) {
            if ($activity_id == 1 || $activity_id == 2 || $activity_id == 4) {
                $showData .= '<table style="width:70%;float:right;" class="table table-bordered table-condensed">
					<thead class="bg-primary"><tr>
					<th>Activity Date</th>
					<th>Subject Details</th>
                                        <th>Status</th>
                                        <th>Created By</th>
					<th>Action</th>
					</tr></thead>';
                foreach ($showDataByDate as $value) {
                    $showData .= '<tr>';
                    $showData .= '<td align="center">' . $value->activity_date . '</td>';
                    $showData .= '<td align="center">' . $value->subject_details . '</td>';
                    $showData .= '<td align="center">' . $value->status_name . '</td>';
                    $showData .= '<td align="center">' . $value->created_by_name . '</td>';
                    $showData .= '<td align="center"><a  href="view.php?actype_id=' . $value->activity_type_id . '&id=' . $value->id . '" target="_new" class="btn btn-info btn-xs" >View</a></td>';
                    $showData .= '</tr>';
                }
                $showData .= '</table>';
                echo $showData;
                die;
            } else if ($activity_id == 3) {
                $showData .= '<table style="width:70%;float:right;" class="table table-bordered table-condensed">
					<thead class="bg-primary"><tr>
					<th>Activity Date</th>
					<th>Subject Details</th>
                                        <th>Status</th>
                                        <th>Created By</th>
					<th>Action</th>
					</tr></thead>';
                foreach ($showDataByDate as $value) {
                    $showData .= '<tr>';
                    $showData .= '<td align="center">' . $value->from_date . '-' . $value->to_date . '</td>';
                    $showData .= '<td align="center">' . $value->subject_details . '</td>';
                    $showData .= '<td align="center">' . $value->status_name . '</td>';
                    $showData .= '<td align="center">' . $value->created_by_name . '</td>';
                    $showData .= '<td align="center"><a  href="view.php?actype_id=' . $value->activity_type_id . '&id=' . $value->id . '" target="_new"  class="btn btn-info btn-xs" >View</a></td>';
                    $showData .= '</tr>';
                }
                $showData .= '</table>';
                echo $showData;
                die;
            } else if ($activity_id == 5 || $activity_id == 7) {
                if ($activity_id == 5) {
                    $pg = "view_audit.php";
                } else if ($activity_id == 7) {
                    $pg = "view_ppe_audit.php";
                }
                $showData .= '<table style="width:70%;float:right;" class="table table-bordered table-condensed">
					<thead class="bg-primary"><tr>
					<th>Date of Audit</th>
					<th>Time of Audit</th>
                                        <th>Status</th>
                                        <th>Created By</th>
					<th>Action</th>
					</tr></thead>';
                foreach ($showDataByDate as $value) {
                    $showData .= '<tr>';
                    $showData .= '<td align="center">' . $value->date_of_audit . '</td>';
                    $showData .= '<td align="center">' . $value->time_of_audit_from . ' - ' . $value->time_of_audit_to . '</td>';
                    $showData .= '<td align="center">' . $value->status_name . '</td>';
                    $showData .= '<td align="center">' . $value->created_by_name . '</td>';
                    $showData .= '<td align="center"><a  href="' . $pg . '?actype_id=' . $value->activity_type_id . '&id=' . $value->id . '" target="_new"  class="btn btn-info btn-sm" >View</a></td>';
                    $showData .= '</tr>';
                }
                $showData .= '</table>';
                echo $showData;
                die;
            } else if ($activity_id == 6) {
                $showData .= '<table style="width:70%;float:right;" class="table table-bordered table-condensed">
					<thead class="bg-primary"><tr>
					<th>Date of Incident</th>
					<th>Time of Incident</th>
                                        <th>Status</th>
                                        <th>Created By</th>
					<th>Action</th>
					</tr></thead>';
                foreach ($showDataByDate as $value) {
                    $showData .= '<tr>';
                    $showData .= '<td align="center">' . $value->date_of_incident . '</td>';
                    $showData .= '<td align="center">' . $value->time_of_incident . '</td>';
                    $showData .= '<td align="center">' . $value->status_name . '</td>';
                    $showData .= '<td align="center">' . $value->created_by_name . '</td>';
                    $showData .= '<td align="center"><a href="view_incident.php?actype_id=' . $value->activity_type_id . '&id=' . $value->id . '" target="_new" class="btn btn-info btn-xs" >View</a></td>';
                    $showData .= '</tr>';
                }
                $showData .= '</table>';
                echo $showData;
                die;
            } else if ($activity_id == 9) {
                $showData .= '<table style="width:70%;float:right;" class="table table-bordered table-condensed">
					<thead class="bg-primary"><tr>
					<th>Date of Observation</th>
					<th>Persons during Observation</th>
                                        <th>Status</th>
                                        <th>Created By</th>
					<th>Action</th>
					</tr></thead>';
                foreach ($showDataByDate as $value) {
                    $showData .= '<tr>';
                    $showData .= '<td align="center">' . $value->date_of_observation . '</td>';
                    $showData .= '<td align="center">' . $value->persons_present_during_observation . '</td>';
                    $showData .= '<td align="center">' . $value->status_name . '</td>';
                    $showData .= '<td align="center">' . $value->created_by_name . '</td>';
                    $showData .= '<td align="center"><a href="view_safety_ob_line.php?actype_id=' . $value->activity_type_id . '&id=' . $value->id . '" target="_new"  class="btn btn-info btn-xs" >View</a></td>';
                    $showData .= '</tr>';
                }
                $showData .= '</table>';
                echo $showData;
                die;
            }
        } else {
            echo $showData = ' ';
            die;
        }
    }

    public function getVenueList(){
        $activity_type_id = $this->bean->get_request("activity_type_id");
       
        $rowdata = $this->dao->get_venue_list($activity_type_id);
        foreach ($rowdata as $rowvalue) {
            if ($rowvalue) {
                echo ''.$rowvalue->place.' | ';
            }
        }
        die;
    }
    
    public function getSubjectTitleList(){
         $activity_type_id = $this->bean->get_request("activity_type_id");
        
        $rowdata = $this->dao->get_subject_title_list($activity_type_id);
        foreach ($rowdata as $rowvalue) {
            if ($rowvalue) {
                echo ''.$rowvalue->subject_details.' | ';
            }
        }
        die;
    }
    /**
     * created by pallab
     * date 20/03/2019
     */
    public function getParentIncidentDetails(){
       $doi = $this->bean->get_request("doi");
       $pim = $this->bean->get_request("pim");
       $clause = " date_of_incident = '".$doi."' AND parent_incident_mapping = '0' AND created_by != '2' AND deleted= '0'";
       $show_parent_incident = $this->dao->fetchAllData('incident', $clause);
       $choose_incident = array();
       foreach($show_parent_incident as $row){
           $choose_incident[] = $row;
       }
       echo json_encode($choose_incident);die;
    } 
    
    
  
}



