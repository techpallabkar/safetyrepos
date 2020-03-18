<?php

class ActivityControllerNew extends MainController {

    public function __construct() {

        $this->bean = load_bean("ActivityBean");
        $this->dao = load_dao("ActivityNewDAO");
        

        parent::__construct();
    }

    /**
     * Modified by santosh and pallab and Nirmalendu ji
     */
    public function index() {
        
    }
    
    public function add_audit_new_view(){
        $audit_id = $this->bean->get_request("audit_id");
        $clause="deleted = '0' AND id = $audit_id";
        $allauditdetails=$this->dao->fetchAllData('audit',$clause);
        $clausecesc="is_deleted = 0 AND participant_category_id = 1 AND audit_id = $audit_id";
        $allsupcesc=$this->dao->fetchAllData('audit_participant_mapping',$clausecesc);
        $clausecset="is_deleted = 0 AND participant_category_id = 2 AND audit_id = $audit_id";
        $allsupcset=$this->dao->fetchAllData('audit_participant_mapping',$clausecset);
        $clauseeng="is_deleted = 0 AND participant_category_id = 3 AND audit_id = $audit_id";
        $allengineer=$this->dao->fetchAllData('audit_participant_mapping',$clauseeng);
        $gradation_sheet_division_id = $allauditdetails[0]->gradation_sheet_division_id;
        $clause_gsdi=" is_deleted = '0' AND division_id = '$gradation_sheet_division_id'";
        $questionSetDetails=$this->dao->fetchAllData('master_question_set',$clause_gsdi);
        
        $clausegroup="is_deleted = 0 AND audit_id = $audit_id";
        $getQuestionDetails=$this->dao->fetchAllData('audit_scoresheet_detailed',$clausegroup);
        $clausegroupwise="audit_id = $audit_id";
        $getGroupWise=$this->dao->fetchAllData('audit_scoresheet_groupwise_view',$clausegroupwise);
        $clausedevi="audit_id = $audit_id AND is_deleted = 0";
        $getdeviation=$this->dao->fetchAllData('deviation_details_view',$clausedevi);
        
        foreach($getdeviation as $key => $val){
            $deviationfileClause="deleted = '0' AND deviation_details_id=".$val->id;        
            $alldeviationsfile=$this->dao->fetchAllData('file_upload_audit_deviation_mapping',$deviationfileClause);
            $getdeviation[$key]->devfile = $alldeviationsfile;
        }        
        $getGroup = array();
        foreach($getGroupWise as $key => $val){
            $getGroup[$val->group_id] = $val;
        }
        $getQuestion = array();
        foreach($getQuestionDetails as $key => $val){
            $getQuestion[$val->group_id][] = $val;
        }
        
        $clause = " audit_id= :audit_id ";
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "audit_division_mapping_view",array("audit_id"=>$audit_id));
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
        
        $getGroupIdByValue = $this->dao->getGroupIdByValue();
        $deviationfileAction="deleted = '0' AND audit_id=".$audit_id;        
        $alldeviationsactionfile=$this->dao->fetchAllData('file_upload_audit_deviation_action_mapping',$deviationfileAction);
        $this->beanUi->set_view_data("alldeviationsactionfile", $alldeviationsactionfile);
        $this->beanUi->set_view_data("getGroupIdByValue", $getGroupIdByValue);
        $this->beanUi->set_view_data("allauditdetails", $allauditdetails);        
        $this->beanUi->set_view_data("allsupcesc", $allsupcesc);        
        $this->beanUi->set_view_data("allsupcset", $allsupcset);        
        $this->beanUi->set_view_data("allengineer", $allengineer);        
        $this->beanUi->set_view_data("getQuestion", $getQuestion); 
        $this->beanUi->set_view_data("getGroup", $getGroup); 
        $this->beanUi->set_view_data("getdeviation", $getdeviation); 
        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("questionSetDetails", $questionSetDetails);
        $this->beanUi->set_view_data("user_details", $this->dao->get_user_details());
    }
    public function add_audit_new_view_details_report(){
        $audit_id = $this->bean->get_request("audit_id");
        $clause="deleted = '0' AND id = $audit_id";
        $allauditdetails=$this->dao->fetchAllData('audit',$clause);
        $clausecesc="is_deleted = 0 AND participant_category_id = 1 AND audit_id = $audit_id";
        $allsupcesc=$this->dao->fetchAllData('audit_participant_mapping',$clausecesc);
        $clausecset="is_deleted = 0 AND participant_category_id = 2 AND audit_id = $audit_id";
        $allsupcset=$this->dao->fetchAllData('audit_participant_mapping',$clausecset);
        $clauseeng="is_deleted = 0 AND participant_category_id = 3 AND audit_id = $audit_id";
        $allengineer=$this->dao->fetchAllData('audit_participant_mapping',$clauseeng);
        $gradation_sheet_division_id = $allauditdetails[0]->gradation_sheet_division_id;
        $clause_gsdi=" is_deleted = '0' AND division_id = '$gradation_sheet_division_id'";
        $questionSetDetails=$this->dao->fetchAllData('master_question_set',$clause_gsdi);
        
        $clausegroup="is_deleted = 0 AND audit_id = $audit_id";
        $getQuestionDetails=$this->dao->fetchAllData('audit_scoresheet_detailed',$clausegroup);
        $clausegroupwise="audit_id = $audit_id";
        $getGroupWise=$this->dao->fetchAllData('audit_scoresheet_groupwise_view',$clausegroupwise);
        $clausedevi="audit_id = $audit_id AND is_deleted = 0";
        $getdeviation=$this->dao->fetchAllData('deviation_details_view',$clausedevi);
        
        foreach($getdeviation as $key => $val){
            $deviationfileClause="deleted = '0' AND deviation_details_id=".$val->id;        
            $alldeviationsfile=$this->dao->fetchAllData('file_upload_audit_deviation_mapping',$deviationfileClause);
            $getdeviation[$key]->devfile = $alldeviationsfile;
        }        
        $getGroup = array();
        foreach($getGroupWise as $key => $val){
            $getGroup[$val->group_id] = $val;
        }
        $getQuestion = array();
        foreach($getQuestionDetails as $key => $val){
            $getQuestion[$val->group_id][] = $val;
        }
        
        $clause = " audit_id= :audit_id ";
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "audit_division_mapping_view",array("audit_id"=>$audit_id));
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
        
        $getGroupIdByValue = $this->dao->getGroupIdByValue();
        $deviationfileAction="deleted = '0' AND audit_id=".$audit_id;        
        $alldeviationsactionfile=$this->dao->fetchAllData('file_upload_audit_deviation_action_mapping',$deviationfileAction);
        $this->beanUi->set_view_data("alldeviationsactionfile", $alldeviationsactionfile);
        $this->beanUi->set_view_data("getGroupIdByValue", $getGroupIdByValue);
        $this->beanUi->set_view_data("allauditdetails", $allauditdetails);        
        $this->beanUi->set_view_data("allsupcesc", $allsupcesc);        
        $this->beanUi->set_view_data("allsupcset", $allsupcset);        
        $this->beanUi->set_view_data("allengineer", $allengineer);        
        $this->beanUi->set_view_data("getQuestion", $getQuestion); 
        $this->beanUi->set_view_data("getGroup", $getGroup); 
        $this->beanUi->set_view_data("getdeviation", $getdeviation); 
        $this->beanUi->set_view_data("devition_names", $devition_name);
        $this->beanUi->set_view_data("questionSetDetails", $questionSetDetails);
        $this->beanUi->set_view_data("user_details", $this->dao->get_user_details());
    }

    public function add_audit_new() {
        $get_auth_user_id             = $this->get_auth_user("id");
        $get_auth_user_role_id        = $this->get_auth_user("role_id");
        $authUserAuditId = $this->dao->authUserAuditId($get_auth_user,1);
        $questionSetClause                = " is_deleted = 0 ORDER BY order_sl ";
        $getQuestionSetDetails            = $this->dao->fetchAllData('master_question_set',$questionSetClause);
        $auditId=$this->bean->get_request("auditId");
        
        
        $_action = $this->bean->get_request("_action");
//        1st tab
        if ($_action == "Create") {
            $page               = $this->bean->get_request("page_no");
            $fromdate_s         = $this->bean->get_request("fromdate_s");
            $todate_s           = $this->bean->get_request("todate_s");
            $activity_no_s      = $this->bean->get_request("activity_no_s");
            $search_title_s     = $this->bean->get_request("search_title_s");
            $status_id_s        = $this->bean->get_request("status_id_s");
            $districtid_s       = $this->bean->get_request("districtid_s");
            
            $this->set_session("data", $this->bean->get_request("data"));
            $data = $this->bean->get_request("data");
            $data1 = $this->bean->get_request("data1");

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
            $data["audit_no"] = $activity_ref_no ? $activity_ref_no : 0;
            
            
            
            $featured_img = explode("?", ltrim($_POST['featured_image_path'], "../../../../"));
            $data["featured_image_path"] = $featured_img[0];

            $this->bean->set_data($data);
            $valid_post = $this->bean->post_validation();

            // Save post
            if($auditId){
                $data['id']=$data1['auditId']; 
                $data["modified_by"] = $this->get_auth_user("id");
                $data["modified_date"] = date("c");
            }else{
                $data["is_oth_dept_audit"] = 1;
                $data["created_by"] = $this->get_auth_user("id");
                $data["created_date"] = date("c");  
            }
            
            $this->dao->_table = "audit";
            $auditIdNew = $this->dao->save($data);
//            if($activityid){
//                $_SESSION['audit_id']=$activityid;
//            }
            if($auditId){
              $auditIdNew=$data1['auditId']; 
            }
            $partiClause1="is_deleted = 0";
            $allperticategories1=$this->dao->fetchAllData('master_participant_category',$partiClause1);
            foreach ($allperticategories1 as $key => $value) {
              $perticipant_name=$this->bean->get_request("audit_participant_name_".$value->id);
              $perticipant_status=$this->bean->get_request("audit_participant_status_".$value->id);
              foreach ($perticipant_status as $key1=>$value1) {
                if(trim($value1)!=''){
                    $datap['audit_id']=$auditIdNew;
                    $datap['participant_category_id']=$value->id;
                    $datap['name']=($perticipant_name[$key1]?trim($perticipant_name[$key1]):'');
                    $datap['partcipant_status']=$perticipant_status[$key1];
                    $datap['status']=1;
                    $datap['is_deleted']=0;
                    $datap["created_by"] = $this->get_auth_user("id");
                    $datap["created_date"] = date("c");
                    $datap["modified_by"] = $this->get_auth_user("id");
                    $datap["modified_date"] = date("c");
                    $this->dao->_table = "audit_participant_mapping";
                    $auditp = $this->dao->save($datap);
                }
              }
            }
            $auaClause1 = "id = ".$auditIdNew;
            $getauaDetailsBYId1 = $this->dao->fetchAllData('audit',$auaClause1);                
            if($auditp){
                $auditPartiGroup=$this->dao->getAuditPartiGrpCount($auditIdNew);
                if(!empty($auditPartiGroup)){
                    $total_manpower=0;
                    foreach ($auditPartiGroup as $keyg => $valueg) {
                        $auditParti[$valueg->participant_category_id]=$valueg->patinum;
                        $total_manpower+=$valueg->patinum;
                    }
                    $total_manpower+=$getauaDetailsBYId1[0]->no_technician;
                    $dataudit1["no_engineer"]= $auditParti[3];
                    $dataudit1["no_pset_supervisor"]= $auditParti[1];
                    $dataudit1["no_cset_supervisor"]= $auditParti[2];
                    $dataudit1["total_manpower"]= $total_manpower;
                    $dataudit1["id"]=$auditIdNew;
                    $this->dao->_table = "audit";
                    $activityid1 = $this->dao->save($dataudit1);  
                }  
            }
            

            /*$perticipant_name=$this->bean->get_request("audit_participant_name_1");
            show($perticipant_name);
            if(!empty($perticipant_name)){
                $this->dao->update_audit_participant($activityid,$this->get_auth_user("id"));
            }*/
//            $activity_logs = array();
//            if ($activityid) {
//                $activity_logs["status_id"] = $data["status_id"];
//                $activity_logs["activity_id"] = $activityid;
//                $activity_logs["activity_type_id"] = $data["activity_type_id"];
//                $this->dao->_table = "activity_logs";
//                $this->dao->save($activity_logs);
//            }
            
//            if (!$activityid) {
//                $this->beanUi->set_error_message($this->dao->get_query_error());
//                redirect();
//            }


//            $uploads = array();
//            $caption = $this->bean->get_request("caption");
//            $image_captions = $this->bean->get_request("image_captions");
//            $video_captions = $this->bean->get_request("video_captions");
//            $old_image_path = $this->bean->get_request("old_image_path");
//            if (!empty($_FILES)) {
//                foreach ($_FILES as $input_name => $files) {
//                    if (is_array($files["name"])) {
//                        if ($files["name"][0] == "")
//                            continue;
//                    } elseif (!is_array($files["name"])) {
//                        if ($files["name"] == "")
//                            continue;
//                    }
//                    if ($input_name == "file_path") {
//                        foreach ($files["name"] as $index_no => $image_name) {
//                            $filedata = array(
//                                "input_name" => $input_name,
//                                "file_type" => "file",
//                                "upload_path" => UPLOADS_PATH . "/deviation_commant",
//                                "index_no" => $index_no
//                            );
//                            $upload_info = upload($filedata);
//                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
//                            $upload_info["type_id"] = 1;
//                            $uploads[] = $upload_info;
//                        }
//                    }
//                }
//            }

//            if (!empty($uploads)) {
//                $errors = "";
//                $this->dao->_table = "file_upload_deviation_commant_mapping";
//                foreach ($uploads as $uprow) {
//                    $post_uploads = array();
//                    if ($uprow["error"] != "") {
//                        $errors .= $uprow["error"] . ",";
//                    } elseif ($uprow["upload_path"] != "" && $activityid) {
//                        $post_uploads["audit_id"] = $auditIdNew;
//                        $post_uploads["file_path"] = $uprow["upload_path"];
//                        $post_uploads["file_type"] = $uprow["file_type"];
//                        $post_uploads["name"] = $uprow["name"];
//                        $post_uploads["created_date"] = date("c");
//                        $post_uploads["type_id"] = $uprow["type_id"];
//                        $upload_update = $this->dao->save($post_uploads);
//                        if (!$upload_update) {
//                            $errors .= $this->dao->get_query_error() . ",";
//                        }
//                    }
//                }
//            }

//            if (!empty($old_image_path)) {
//                foreach ($old_image_path as $key => $oldrow) {
//                    $myArray1 = explode('.', $oldrow);
//                    $myArray = "image/" . $myArray1[1];
//                    $newimage = $myArray1[0] . "_" . rand() . "." . $myArray1[1];
//                    $prPathArr = explode('/', BASE_PATH);
//                    array_pop($prPathArr);
//                    $imgPath = implode("/", $prPathArr);
//                    $oldrow1 = $imgPath . '/' . $oldrow;
//                    $newimage1 = $imgPath . '/' . $newimage;
//                    if (copy($oldrow1, $newimage1)) {
//                        $data1["audit_id"] = $activityid;
//                        $data1["file_type"] = $myArray;
//                        $data1["file_path"] = $newimage;
//                        $data1["name"] = '';
//                        $data1["created_date"] = date("c");
//                        $data1["modified_date"] = date("c");
//                        $data1["type_id"] = 0;
//                        $this->dao->_table = "file_upload_audit_mapping";
//                        $oldimgid = $this->dao->save($data1);
//                    }
//                }
//            }
            
            
            //Division Mapping Insert
            $division = $this->bean->get_request("division");



            if (!empty($division)) {
                $this->dao->_table = "audit_division_mapping";
                foreach ($division as $drow => $k) {
                    $treename = ltrim($division[$drow], "tree_");
                    $division_mapping = array();
                    $division_mapping["audit_id"] = $auditIdNew;
                    $division_mapping["division_id"] = ltrim($division[$drow], "tree_");
                    $division_mapping["tree_division_id"] = str_replace('--', '-', $treename);
                    $division_mapping["created_by"] = $this->get_auth_user("id");
                    $division_mapping["created_date"] = date("c");
                    $save_division_mapping = $this->dao->save($division_mapping);
                    if (!$save_division_mapping) {
                        $errors .= $this->dao->get_query_error() . ",";
                    }
                }
            }

            // End popular post	
            
           /**/
           $nexttab = $this->bean->get_request("nexttab");
           if((isset($nexttab)) && ($nexttab=='Next Tab')){
            $this->beanUi->set_success_message("Audit is successfully Updated.");
            redirect(page_link("activitynew/add_audit_new.php?page=".$page."&activity_id=" . $data["activity_type_id"])."&fromdate=".$fromdate_s."&todate=".$todate_s."&auditId=".$auditIdNew."&search_title=".$search_title_s."&status_id=".$status_id_s."&districtid=".$districtid_s."#tabs-2");
           }else{
            $this->beanUi->set_success_message("Audit is successfully added.");
            redirect(page_link("activitynew/add_audit_new.php?page=".$page."&activity_id=" . $data["activity_type_id"])."&fromdate=".$fromdate_s."&todate=".$todate_s."&auditId=".$auditIdNew."&search_title=".$search_title_s."&status_id=".$status_id_s."&districtid=".$districtid_s."#tabs-1");
           }
           /**/
        
            /*$this->beanUi->set_success_message("Audit is successfully added.");
            redirect(page_link("activitynew/add_audit_new.php?activity_id=" . $data["activity_type_id"])."&auditId=".$auditIdNew."#tabs-1");*/
        }
        //2nd tab
        if($_action == "updateScore"){
          $page = $this->bean->get_request("page_no");
          $fromdate_s         = $this->bean->get_request("fromdate_s");
          $todate_s           = $this->bean->get_request("todate_s");
          $activity_no_s      = $this->bean->get_request("activity_no_s");
          $search_title_s     = $this->bean->get_request("search_title_s");
          $status_id_s        = $this->bean->get_request("status_id_s");
          $districtid_s       = $this->bean->get_request("districtid_s");
          
          $scoredata = $this->bean->get_request("scoredata"); 
          $questionId = $this->bean->get_request("questionId"); 
          $groupName = $this->bean->get_request("groupName");  
          $groupId = $this->bean->get_request("groupId");  
          $groupTotal = $this->bean->get_request("groupTotal");  
          $setId = $this->bean->get_request("setId");  
          $questionAudit = $this->bean->get_request("questionAudit");
          
          $fullMarks = $this->bean->get_request("fullMarks");
          $obtainMarks = $this->bean->get_request("obtainMarks");
          $remarks = $this->bean->get_request("remarks");
          
          $auditid = $this->bean->get_request("auditid");
          
          $stdQty = $this->bean->get_request("stdQty");
          $avalQty = $this->bean->get_request("avalQty");
          $observeScore = $this->bean->get_request("observeScore");
          
          $data1=array();
          $data1["audit_id"]=$auditid;
          $this->dao->_table = "audit_scoresheet_detailed";
          $devdel = $this->dao->del($data1);
          
          if(!empty($questionId)){
            $devSlData=$this->dao->delscoresheet($auditid);
            for($i=0;$i<count($questionId);$i++){
              $dataScore=array();
              $dataScore['audit_id']=$auditid;
              $dataScore['set_id']=$setId[$i];
              $dataScore['group_id']=$groupId[$i];
              $dataScore['group_name']=$groupName[$i];
              $dataScore['audit_question_id']=$questionId[$i];
              $dataScore['question']=$questionAudit[$i];
              $dataScore['full_marks']=$fullMarks[$i];
              $dataScore['obtained_marks']=$obtainMarks[$i];
              $dataScore['remarks']=$remarks[$i];
              $dataScore['group_total_score']=$groupTotal[$i];
              
              if(($stdQty[$questionId[$i]] == 'NA') || ($avalQty[$questionId[$i]] == 'NA')){
                $dataScore['standerd_qnt']='NA';
                $dataScore['available_qnt']='NA'; 
              } else {
//                $dataScore['standerd_qnt']=($stdQty[$questionId[$i]]?$stdQty[$questionId[$i]]:0);
//                $dataScore['available_qnt']=($avalQty[$questionId[$i]]?$avalQty[$questionId[$i]]:0);
                $dataScore['standerd_qnt']=(($stdQty[$questionId[$i]] != '') ? $stdQty[$questionId[$i]] : '');
                $dataScore['available_qnt']=(($avalQty[$questionId[$i]] != '') ? $avalQty[$questionId[$i]] : '');
              }
              
              $dataScore['observation']=($observeScore[$questionId[$i]]?$observeScore[$questionId[$i]]:'NOT APPLICABLE');
                                         
              $dataScore['status']=1;
              $dataScore['is_deleted']=0;
              $dataScore["created_by"]= $this->get_auth_user("id");
              $dataScore["created_date"]= date("c");
                                          
              $this->dao->_table = "audit_scoresheet_detailed";
              $auditScoreId = $this->dao->save($dataScore); 
            }  
          }
          
          $dataudit=array();
          $totalMan=0;
          if($this->bean->get_request("engineer")){
            $dataudit["no_engineer"]= $this->bean->get_request("engineer");
            $totalMan+=$this->bean->get_request("engineer");
          }
          if($this->bean->get_request("supervisor_cesc")){
            $dataudit["no_pset_supervisor"]= $this->bean->get_request("supervisor_cesc");
            $totalMan+=$this->bean->get_request("supervisor_cesc");
          }
          if($this->bean->get_request("supervisor_cset")){
            $dataudit["no_cset_supervisor"]= $this->bean->get_request("supervisor_cset");
            $totalMan+=$this->bean->get_request("supervisor_cset");
          }
          if($this->bean->get_request("technician")){
            $dataudit["no_technician"]= $this->bean->get_request("technician");
            $totalMan+=$this->bean->get_request("technician");
          }
          $dataudit["total_manpower"]=$totalMan;
          
          $dataudit["total_obtained_marks"]=$this->bean->get_request("scoreobt");
          $dataudit["total_full_marks"]=$this->bean->get_request("groupTotalScore");
          $dataudit["total_applicable_full_marks"]=$this->bean->get_request("aplgroscore");
          $dataudit["avg_mark"]=$this->bean->get_request("finalscore");
          //$dataudit["status_id"]=$scoredata["status_id"];
          $dataudit["id"]=$auditid;
          $this->dao->_table = "audit";
          $activityid = $this->dao->save($dataudit);
          
          $_SESSION['audit_id']=$auditid;

          /**/
           $nexttab = $this->bean->get_request("nexttab");
           $prevtab = $this->bean->get_request("prevtab");
           if((isset($nexttab)) && ($nexttab=='Next Tab')){
            $this->beanUi->set_success_message("Audit is successfully Updated.");
            redirect(page_link("activitynew/add_audit_new.php?page=".$page."&activity_id=" . $scoredata["activity_type_id"])."&fromdate=".$fromdate_s."&todate=".$todate_s."&auditId=".$auditid."&search_title=".$search_title_s."&status_id=".$status_id_s."&districtid=".$districtid_s."#tabs-3");
           }elseif((isset($prevtab)) && ($prevtab=='Previous Tab')){
            $this->beanUi->set_success_message("Audit is successfully Updated.");
            redirect(page_link("activitynew/add_audit_new.php?page=".$page."&activity_id=" . $scoredata["activity_type_id"])."&fromdate=".$fromdate_s."&todate=".$todate_s."&auditId=".$auditid."&search_title=".$search_title_s."&status_id=".$status_id_s."&districtid=".$districtid_s."#tabs-1");
           }else{
            $this->beanUi->set_success_message("Audit is successfully Updated.");
            redirect(page_link("activitynew/add_audit_new.php?page=".$page."&activity_id=" . $scoredata["activity_type_id"])."&fromdate=".$fromdate_s."&todate=".$todate_s."&auditId=".$auditid."&search_title=".$search_title_s."&status_id=".$status_id_s."&districtid=".$districtid_s."#tabs-2");
           }
           /**/
          
          /*$this->beanUi->set_success_message("Audit is successfully added.");
          redirect(page_link("activitynew/add_audit_new.php?activity_id=" . $scoredata["activity_type_id"])."&auditId=".$auditid."#tabs-2");*/
        }
        //2nd tab Generation
        if($_action == "updateScoreGeneration"){
          $page               = $this->bean->get_request("page_no");
          $fromdate_s         = $this->bean->get_request("fromdate_s");
          $todate_s           = $this->bean->get_request("todate_s");
          $activity_no_s      = $this->bean->get_request("activity_no_s");
          $search_title_s     = $this->bean->get_request("search_title_s");
          $status_id_s        = $this->bean->get_request("status_id_s");
          $districtid_s       = $this->bean->get_request("districtid_s");
          
          $scoredata = $this->bean->get_request("scoredata"); 
          $questionId = $this->bean->get_request("questionId"); 
          $groupName = $this->bean->get_request("groupName");  
          $groupId = $this->bean->get_request("groupId");  
          $groupTotal = $this->bean->get_request("groupTotal");  
          $setId = $this->bean->get_request("setId");  
          $questionAudit = $this->bean->get_request("questionAudit");
          $msgavggroup = $this->bean->get_request("msgavggroup");
          
          $fullMarks = $this->bean->get_request("fullMarks");
          $obtainMarks = $this->bean->get_request("obtainMarks");
          $remarks = $this->bean->get_request("remarks");
          
          $auditid = $this->bean->get_request("auditid");
          $mergedgroup = $this->bean->get_request("mergedgroup");
                    
          $stdQty = $this->bean->get_request("stdQty");
          $avalQty = $this->bean->get_request("avalQty");
          $observeScore = $this->bean->get_request("observeScore");
                   
          $data1=array();
          $data1["audit_id"]=$auditid;
          $this->dao->_table = "audit_scoresheet_detailed";
          $devdel = $this->dao->del($data1);
          
          if(!empty($questionId)){
            $devSlData=$this->dao->delscoresheet($auditid);
            for($i=0;$i<count($questionId);$i++){
              $dataScore=array();
              $dataScore['audit_id']=$auditid;
              $dataScore['set_id']=$setId[$i];
              $dataScore['group_id']=$groupId[$i];
              $dataScore['group_name']=$groupName[$i];
              $dataScore['audit_question_id']=$questionId[$i];
              $dataScore['question']=$questionAudit[$i];
              $dataScore['full_marks']=(round(($fullMarks[$i]*$msgavggroup[$i]), 2));
              $dataScore['obtained_marks']=(round(($obtainMarks[$i]*$msgavggroup[$i]), 2));
              $dataScore['remarks']=$remarks[$i];
              $dataScore['group_total_score']=$groupTotal[$i];
              
              if(!array_key_exists($questionId[$i], $stdQty) && !array_key_exists($questionId[$i], $observeScore)){
                $dataScore['standerd_qnt']='NA';
                $dataScore['available_qnt']='NA';
              } else if(($stdQty[$questionId[$i]] == 'NA') || ($avalQty[$questionId[$i]] == 'NA')){
                $dataScore['standerd_qnt']='NA';
                $dataScore['available_qnt']='NA'; 
              } else {
                $dataScore['standerd_qnt']=(($stdQty[$questionId[$i]] != '') ? $stdQty[$questionId[$i]] : '');
                $dataScore['available_qnt']=(($avalQty[$questionId[$i]] != '') ? $avalQty[$questionId[$i]] : '');
              }
              
              $dataScore['observation']=($observeScore[$questionId[$i]]?$observeScore[$questionId[$i]]:'NOT APPLICABLE');
              
              $dataScore['is_marged_group']=($mergedgroup[$i]==0?'0':'1');
              $dataScore['marged_group_master_id']=$mergedgroup[$i];             
              $dataScore['status']=1;
              $dataScore['is_deleted']=0;
              $dataScore["created_by"]= $this->get_auth_user("id");
              $dataScore["created_date"]= date("c");
                            
              $this->dao->_table = "audit_scoresheet_detailed";
              $auditScoreId = $this->dao->save($dataScore); 
            }  
          }
          
          /* For Meged Group Only*/
          $mstgroupid = $this->bean->get_request("mstgroupid"); 
          $mstgroupname = $this->bean->get_request("mstgroupname");  
          $mstgroupfullmarks = $this->bean->get_request("mstgroupfullmarks");  
          $groupobt = $this->bean->get_request("groupobt");  
          $groupfull = $this->bean->get_request("groupfull");
          
          if(!empty($mstgroupid)){
              $devSlData1=$this->dao->delmergedgroupscrore($auditid);
              for($i1=0;$i1<count($mstgroupid);$i1++){
                $dataMrgGrpScore=array();
                $dataMrgGrpScore['audit_id']=$auditid;
                $dataMrgGrpScore['marged_group_master_id']=$mstgroupid[$i1];
                $dataMrgGrpScore['marged_group_name']=$mstgroupname[$i1];
                $dataMrgGrpScore['marged_group_score']=$groupobt[$i1];
                $dataMrgGrpScore['marged_group_total_score']=$groupfull[$i1];                
                $this->dao->_table = "marged_group_score_details";
                $mergerGroupScoreId = $this->dao->save($dataMrgGrpScore);
              }
          }
          
          /* For Meged Group Only*/
           
          $dataudit=array();
          $totalMan=0;
          if($this->bean->get_request("engineer")){
            $dataudit["no_engineer"]= $this->bean->get_request("engineer");
            $totalMan+=$this->bean->get_request("engineer");
          }
          if($this->bean->get_request("supervisor_cesc")){
            $dataudit["no_pset_supervisor"]= $this->bean->get_request("supervisor_cesc");
            $totalMan+=$this->bean->get_request("supervisor_cesc");
          }
          if($this->bean->get_request("supervisor_cset")){
            $dataudit["no_cset_supervisor"]= $this->bean->get_request("supervisor_cset");
            $totalMan+=$this->bean->get_request("supervisor_cset");
          }
          if($this->bean->get_request("technician")){
            $dataudit["no_technician"]= $this->bean->get_request("technician");
            $totalMan+=$this->bean->get_request("technician");
          }
          $dataudit["total_manpower"]=$totalMan;
          
          $dataudit["total_obtained_marks"]=$this->bean->get_request("scoreobt");
          $dataudit["total_full_marks"]=$this->bean->get_request("groupTotalScore");
          $dataudit["total_applicable_full_marks"]=$this->bean->get_request("aplgroscore");
          $dataudit["avg_mark"]=$this->bean->get_request("finalscore");
          //$dataudit["status_id"]=$scoredata["status_id"];
          $dataudit["id"]=$auditid;
          $this->dao->_table = "audit";
          $activityid = $this->dao->save($dataudit);
          
          $_SESSION['audit_id']=$auditid;
          
          /**/
           $nexttab = $this->bean->get_request("nexttab");
           $prevtab = $this->bean->get_request("prevtab");
           if((isset($nexttab)) && ($nexttab=='Next Tab')){
            $this->beanUi->set_success_message("Audit is successfully added.");
            redirect(page_link("activitynew/add_audit_new.php?page=".$page."&activity_id=" . $scoredata["activity_type_id"])."&fromdate=".$fromdate_s."&todate=".$todate_s."&auditId=".$auditid."&search_title=".$search_title_s."&status_id=".$status_id_s."&districtid=".$districtid_s."#tabs-3");
           }elseif((isset($prevtab)) && ($prevtab=='Previous Tab')){
            $this->beanUi->set_success_message("Audit is successfully added.");
            redirect(page_link("activitynew/add_audit_new.php?page=".$page."&activity_id=" . $scoredata["activity_type_id"])."&fromdate=".$fromdate_s."&todate=".$todate_s."&auditId=".$auditid."&search_title=".$search_title_s."&status_id=".$status_id_s."&districtid=".$districtid_s."#tabs-1");
           }else{
            $this->beanUi->set_success_message("Audit is successfully added.");
            redirect(page_link("activitynew/add_audit_new.php?page=".$page."&activity_id=" . $scoredata["activity_type_id"])."&fromdate=".$fromdate_s."&todate=".$todate_s."&auditId=".$auditid."&search_title=".$search_title_s."&status_id=".$status_id_s."&districtid=".$districtid_s."#tabs-2");
           }
           /**/

          /*$this->beanUi->set_success_message("Audit is successfully added.");
          redirect(page_link("activitynew/add_audit_new.php?activity_id=" . $scoredata["activity_type_id"])."&auditId=".$auditid."#tabs-2");*/
        }
//        3rd tab
        if($_action == "updateDeviation"){
            $page               = $this->bean->get_request("page_no");
            $fromdate_s         = $this->bean->get_request("fromdate_s");
            $todate_s           = $this->bean->get_request("todate_s");
            $activity_no_s      = $this->bean->get_request("activity_no_s");
            $search_title_s     = $this->bean->get_request("search_title_s");
            $status_id_s        = $this->bean->get_request("status_id_s");
            $districtid_s       = $this->bean->get_request("districtid_s");
           
           $devdata = $this->bean->get_request("devdata"); 
           $auditid = $this->bean->get_request("auditid");
           $data = $this->bean->get_request("data");
           $data["id"]=$auditid;
           $this->dao->_table = "audit";
           $activityid = $this->dao->save($data);
           $_SESSION['audit_id']=$auditid;
                    
        //          file upload start
          $uploads = array();
            $caption = $this->bean->get_request("caption");
            $caption_file = $this->bean->get_request("caption_file");
            $caption_edit = $this->bean->get_request("caption_edit");
            $img_edit_id = $this->bean->get_request("img_edit_id");
            if (!empty($_FILES)) { 
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }
                    
                    if ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "image",
                                "upload_path" => UPLOADS_PATH . "/deviation_action",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption[$index_no]) ? $caption[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads[] = $upload_info;
                        }
                    }
                                        
                    if ($input_name == "file_path_file") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/activity_non_mains",
                                "index_no" => $index_no
                            );
                            $upload_info = upload($filedata);
                            $upload_info["name"] = isset($caption_file[$index_no]) ? $caption_file[$index_no] : $upload_info["name"];
                            $upload_info["type_id"] = 1;
                            $uploads_files[] = $upload_info;
                        }
                    }
                }
            }
            foreach($img_edit_id as $kiei => $ieiv){
                $imgeditidArr["id"]=$ieiv;
                $imgeditidArr["name"] = $caption_edit[$kiei];
                $this->dao->_table = "file_upload_audit_deviation_action_mapping";
                $imgeditid = $this->dao->save($imgeditidArr);   
            }
            if (!empty($uploads)) {
                $errors = "";
                $this->dao->_table = "file_upload_audit_deviation_action_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "") {
                        //$post_uploads["deviation_details_id"] = $devsave;
                        $post_uploads["audit_id"] = $auditid;
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
            
            /**=====New Part=======**/
            if (!empty($uploads_files)) {
                $errors = "";
                $this->dao->_table = "file_upload_audit_deviation_action_mapping";
                foreach ($uploads_files as $uprow) {
                    $post_uploads1 = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "") {
                        //$post_uploads1["deviation_details_id"] = $devsave;
                        $post_uploads1["audit_id"] = $auditid;
                        $post_uploads1["file_path"] = $uprow["upload_path"];
                        $post_uploads1["file_type"] = $uprow["file_type"];
                        $post_uploads1["name"] = $uprow["name"];
                        $post_uploads1["created_date"] = date("c");
                        $post_uploads1["type_id"] = $uprow["type_id"];
                        $upload_update1 = $this->dao->save($post_uploads1);
                        if (!$upload_update1) {
                            $errors .= $this->dao->get_query_error() . ",";
                        }
                    }
                }
            }
            /**=====New Part======**/

            /**/
           
           $prevtab = $this->bean->get_request("prevtab");
          if((isset($prevtab)) && ($prevtab=='Previous Tab')){
            $this->beanUi->set_success_message("Audit is successfully added.");
            redirect(page_link("activitynew/add_audit_new.php?page=".$page."&activity_id=" . $devdata["activity_type_id"])."&fromdate=".$fromdate_s."&todate=".$todate_s."&auditId=".$auditid."&search_title=".$search_title_s."&status_id=".$status_id_s."&districtid=".$districtid_s."#tabs-2");
           }else{
            $this->beanUi->set_success_message("Audit is successfully added.");
           redirect(page_link("activitynew/activity_redirect_page.php?page=".$page."&activity_id=" . $devdata["activity_type_id"]."&fromdate=".$fromdate_s."&todate=".$todate_s."&search_title=".$search_title_s."&status_id=".$status_id_s."&districtid=".$districtid_s));
           }
           /**/
           
           /*$this->beanUi->set_success_message("Audit is successfully added.");
           redirect(page_link("activitynew/activity_redirect_page.php?activity_id=" . $devdata["activity_type_id"]."&auditId=".$auditid))*/;
        }
        
        if($_action == "addDeviation"){
           $auditdevdata = $this->bean->get_request("auditdevdata");
           $devdata = $this->bean->get_request("devdata"); 
           $auditid = $this->bean->get_request("auditid"); 
           
            $data1=array();
            $devId=0;
            $deviationdata=array();
            $typeId=0;
            if(!empty($auditdevdata["dev_id"])){
                $devId=$auditdevdata["dev_id"];
                $data1["id"]= $devId;
                $devClause1 = "id = $devId";
                $deviationdata=$this->dao->fetchAllData('deviation_details',$devClause1);
                $typeId=$deviationdata[0]->deviation_type_id;
            }

            $devSl=$this->genDeviationSlNo($auditdevdata["devtype"],$auditid,$devId);
            //$devTypeSl=$this->genDeviationTypeSlNo($auditdevdata["devtype"],$auditdevdata["audit_id"],$devId);
            $devSlArr=explode(" ",$devSl);
            $devCt=count($devSlArr)-1;
            $slType=$devSlArr[$devCt];

            $data1["audit_id"] = $auditid;
            $data1["deviation_type_id"] = $auditdevdata["devtype"];
            $data1["violation_category_id"] = $auditdevdata["category_id"];
//            $data1["violation_subcategory_id"] = $auditdevdata["subcategory_id"];
            $data1["observation"] = $auditdevdata["observation"];
            if($typeId!=$auditdevdata["devtype"]){
                $data1["deviation_no"] = $devSl;
                $data1["type_sl_no"] = (int)$slType;  
            }        
            $data1["status"] = 1;
            $data1["created_by"]= $this->get_auth_user("id");
            $data1["created_date"]= date("c");
            //show($data1);
            $this->dao->_table = "deviation_details";
            $devsave = $this->dao->save($data1);
           //$this->beanUi->set_success_message("Audit is successfully added.");
            
            if($devId){
               $devsave = $devId;
            }else{
               $devsave = $devsave; 
            }
            
           //          file upload start
          $uploads = array();
            $caption = $this->bean->get_request("caption");
//            $old_image_path = $this->bean->get_request("old_image_path");
            if (!empty($_FILES)) { 
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }
                    
                    if ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/deviation",
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
                $this->dao->_table = "file_upload_audit_deviation_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "") {
                        $post_uploads["deviation_details_id"] = $devsave;
                        //$post_uploads["audit_id"] = $auditIdNew;
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
            
            
 
           redirect(page_link("activitynew/add_audit_new.php?activity_id=" . $devdata["activity_type_id"])."&auditId=".$auditid."#tabs-3");

        }

        $this->beanUi->set_view_data("post_categories", $this->dao->get_categories());
        $this->beanUi->set_view_data("post_participants_categories", $this->dao->get_participants_categories());
        $this->beanUi->set_view_data("post_division_department", $this->dao->get_division_department());
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("activity_id", $this->bean->get_request("activity_id"));
        $this->beanUi->set_view_data("post_insert_participants", $this->dao->insert_participants());
        $this->beanUi->set_view_data("user_details", $this->dao->get_user_details());
        $this->beanUi->set_view_data("nodal_officer_details", $this->dao->get_nodal_officer());
        $this->beanUi->set_view_data("getScoreDeviationNodalData", $this->dao->getScoreDeviationNodalDetails());
        $this->beanUi->set_array_to_view_data($this->get_session("data"));
        $activity_id = $this->bean->get_request("activity_id");
        $this->beanUi->set_view_data("authUserAuditIdDetails", $authUserAuditId);
        $this->beanUi->set_view_data("getQuestionSetDetails", $getQuestionSetDetails);
        $this->beanUi->set_view_data("get_auth_user_id", $get_auth_user_id);
        $this->beanUi->set_view_data("get_auth_user_role_id", $get_auth_user_role_id);       
        
        $auaClause                = "id = ".$auditId;
        $getauaDetailsBYId       = $this->dao->fetchAllData('audit',$auaClause);
        
        $getGradationSheetDivisionId = $getauaDetailsBYId[0]->gradation_sheet_division_id;
        $setId = 0;
            if($getGradationSheetDivisionId== 1){
               $setId = 1; 
            }else if($getGradationSheetDivisionId== 2){
               $setId = 2;  
            }else if($getGradationSheetDivisionId== 3){
               $setId = 3;  
            }else if($getGradationSheetDivisionId== 4){
               $setId = 4;  
            }else if($getGradationSheetDivisionId== 5){
               $setId = 5;  
            }else if($getGradationSheetDivisionId== 6){
               $setId = 6;  
            }else if($getGradationSheetDivisionId== 7){
               $setId = 7;  
            }else if($getGradationSheetDivisionId== 8){
               $setId = 8;  
            }
                
                    
        $getOptinCatIdByname      = $this->dao->getOptinCatIdByname();
        $getOptinIdByValue        = $this->dao->getOptinIdByValue();
        $getGroupIdByValue        = $this->dao->getGroupIdByValue();
        $setClause                = "id = $setId AND is_deleted = 0 LIMIT 1";
        $getSetDetails            = $this->dao->fetchAllData('master_question_set',$setClause);
        
        if($getGradationSheetDivisionId == 3){
            $getQuestionMstGrp=array();
            $queClause                = "set_id = ".$getSetDetails[0]->id." AND is_deleted = 0";
            $getQuestionDetails       = $this->dao->fetchAllData('master_audit_question_view',$queClause);//show($getQuestionDetails);
            $getQuestion = array();
            foreach($getQuestionDetails as $key => $val){
                $getQuestion[$val->group_id][] = $val;                
            }
            $queClause1                = "set_id = ".$getSetDetails[0]->id;
            $mergedGroupMaster         = $this->dao->fetchAllData('marged_group_master',$queClause1);            
            $queClause1a                = "set_id = ".$getSetDetails[0]->id." AND is_marged_group = '1'";
            $mergedGroupSet             = $this->dao->fetchAllData('master_question_set_group',$queClause1a);
            foreach($mergedGroupSet as $key1 => $val1){                
                $getQuestionMstGrp[$val1->marged_group_master_id][$val1->id] = $val1;
            }
            $mrggroupauditdata=array();
            if($auditId){
                $queClauseMrg1           = "audit_id = ".$auditId;
                $mergedGroupMasterAudit  = $this->dao->fetchAllData('marged_group_score_details',$queClauseMrg1);
                foreach($mergedGroupMasterAudit as $keyg => $valg){                
                    $mrggroupauditdata[$valg->marged_group_master_id]= $valg;
                }
            }            
            $this->beanUi->set_view_data("mrggroupauditdata", $mrggroupauditdata);
            $this->beanUi->set_view_data("getQuestionMstGrp", $getQuestionMstGrp);
            $this->beanUi->set_view_data("mergedGroupMaster", $mergedGroupMaster);
        }else{
            $queClause                = "set_id = ".$getSetDetails[0]->id." AND is_deleted = 0";
            $getQuestionDetails       = $this->dao->fetchAllData('master_audit_question_view',$queClause);//show($getQuestionDetails);
            $getQuestion = array();
            foreach($getQuestionDetails as $key => $val){
                $getQuestion[$val->group_id][] = $val;
            }            
        }

        $this->beanUi->set_view_data("getOptinCatIdByname", $getOptinCatIdByname);
        $this->beanUi->set_view_data("getOptinIdByValue", $getOptinIdByValue);
        $this->beanUi->set_view_data("getGroupIdByValue", $getGroupIdByValue);
        $this->beanUi->set_view_data("getQuestion", $getQuestion);
        
        /* GET AUDIT RELATED DATA FOR EDIT*/
        $auditScore=array();
        $auditScoreGroup=array();
        if($auditId){
            $auditClause="id = ".$auditId." AND deleted = '0'";
            $auditData            = $this->dao->fetchAllData('audit',$auditClause);       
            $this->beanUi->set_view_data("auditData", $auditData);
            $auditScoreClause="audit_id = $auditId";
            $auditScoreData            = $this->dao->fetchAllData('audit_scoresheet_detailed',$auditScoreClause);
            foreach ($auditScoreData as $key => $value) {
              $auditScore[$value->audit_question_id]=$value;  
            }
            $auditScoreGroupData            = $this->dao->fetchAllData('audit_scoresheet_groupwise_view',$auditScoreClause);
            foreach ($auditScoreGroupData as $key1 => $value1) {
              $auditScoreGroup[$value1->group_id]=$value1;  
            }
        }

        $clause = " audit_id= :audit_id ";
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "audit_division_mapping_view",array("audit_id"=>$auditData[0]->id));
        
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
        $this->beanUi->set_view_data("division_department_mapping", $division_department_mapping);  
        /*         * ********division depart for edit end**** */
        $this->beanUi->set_view_data("devition_names", $devition_name);
        
        $this->beanUi->set_view_data("auditScore", $auditScore);
        $this->beanUi->set_view_data("auditScoreGroup", $auditScoreGroup);
        /* GET AUDIT RELATED DATA */
        
        /* Third part Deviation Observation (NIRMALENDU KHAN) */
        $devClause                 = "is_deleted = 0";
        $allDeviationType=$this->dao->fetchAllData('master_deviation_type',$devClause);
        
        $auditIdClause="audit_id = $auditId"; 
        $auditScoreSheetDetails            = $this->dao->fetchAllData('audit_scoresheet_detailed',$auditIdClause);
 
        $devCatClause                 = "is_deleted = 0 AND is_category='1' AND set_id='".$auditScoreSheetDetails[0]->set_id."'";
        $allCategories=$this->dao->fetchAllData('master_question_set_group',$devCatClause);
//        $allCategories=$this->dao->fetchAllData('master_violation_category',$devClause);
        
        $deviationClause="is_deleted = 0 AND audit_id=".$auditId." ORDER BY type_name DESC";        
        $alldeviations=$this->dao->fetchAllData('deviation_details_view',$deviationClause);
        foreach($alldeviations as $key => $val){
            $deviationfileClause="deleted = '0' AND deviation_details_id=".$val->id;        
            $alldeviationsfile=$this->dao->fetchAllData('file_upload_audit_deviation_mapping',$deviationfileClause);
            $alldeviations[$key]->devfile = $alldeviationsfile;
        }

        $deviationfileAction="deleted = '0' AND file_type LIKE 'image/%' AND audit_id=".$auditId;        
        $alldeviationsactionfile=$this->dao->fetchAllData('file_upload_audit_deviation_action_mapping',$deviationfileAction);
        $deviationfileAction1="deleted = '0' AND file_type LIKE '%pdf' AND audit_id=".$auditId;        
        $alldeviationsactionfile1=$this->dao->fetchAllData('file_upload_audit_deviation_action_mapping',$deviationfileAction1);
        
        $this->beanUi->set_view_data("allDeviationType", $allDeviationType);
        $this->beanUi->set_view_data("allCategories", $allCategories);
        $this->beanUi->set_view_data("alldeviations", $alldeviations);        
        $this->beanUi->set_view_data("alldeviationsactionfile", $alldeviationsactionfile);
        $this->beanUi->set_view_data("alldeviationsactionfile1", $alldeviationsactionfile1);        
        
        $partiClause="is_deleted = 0 ORDER BY order_sl";
        $allperticategories=$this->dao->fetchAllData('master_participant_category',$partiClause);
        $this->beanUi->set_view_data("allperticategories", $allperticategories);        
        $audParti=array();
                
        if($auditId){
            $partiClause1="is_deleted = 0 AND audit_id=".$auditId;
            $getAuditParti=$this->dao->fetchAllData('audit_participant_mapping',$partiClause1);
            foreach($getAuditParti as $key=>$value){
                $audParti[$value->participant_category_id][]=$value;
            }
        }        
        $this->beanUi->set_view_data("auditParti", $audParti);
        /* Third part Deviation Observation */
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
    
    /*DEVIATION OBSERVATION PART (NIRMALENDU KHAN) */
    public function getSubcategory(){
        $category_id = $this->bean->get_request("category_id");
        $devClause                 = "violation_category_id = $category_id AND is_deleted = 0";
        $subcategories=$this->dao->fetchAllData('master_violation_subcategory',$devClause);
        $new_dropdown = '<option value="">-choose one-</option>';
        foreach ($subcategories as $row) {
            $new_dropdown .= '<option value="'. $row->id .'">' . $row->subcategory_name . '</option>';
        }
        echo $new_dropdown;
        die;
    }
    
    public function getSubcategoryDetails(){
        $subcategory_id = $this->bean->get_request("subcategory_id");
        $devClause                 = "violation_subcategory_id = $subcategory_id AND is_deleted = 0";
        $subcategories=$this->dao->fetchAllData('master_violation_subcategory_details',$devClause);        
        $txt="";
        if(!empty($subcategories)){
            foreach ($subcategories as $row) {
              $txt.=$row->subcategory_details;
              $txt.="\n";
            }  
        }else{
           $devClause1                 = "id = $subcategory_id AND is_deleted = 0";
           $subcategory=$this->dao->fetchAllData('master_violation_subcategory',$devClause1);
           $txt.=$subcategory[0]->subcategory_name;
        }
        
        echo $txt;
        die;
    }
    
    public function uploadcaption(){
        
        $post_uploads["audit_id"] = $uprow["audit_id"];
        $post_uploads["file_path"] = $uprow["upload_path"];
        $post_uploads["file_type"] = $uprow["file_type"];
        $post_uploads["name"] = $uprow["name"];
        $post_uploads["created_date"] = date("c");
        $post_uploads["type_id"] = 1;
        $post_uploads["status"] = 1;
        $this->dao->_table = "file_upload_audit_deviation_mapping";
        $upload_update = $this->dao->save($post_uploads);     

       die;
    }

    public function  saveAuditData(){
        $auditdevdata = $this->bean->get_request("auditdevdata");
        
        $data1=array();
        $devId=0;
        $deviationdata=array();
        $typeId=0;
        if(!empty($auditdevdata["dev_id"])){
            $devId=$auditdevdata["dev_id"];
            $data1["id"]= $devId;
            $devClause1 = "id = $devId";
            $deviationdata=$this->dao->fetchAllData('deviation_details',$devClause1);
            $typeId=$deviationdata[0]->deviation_type_id;
        }
        
        $devSl=$this->genDeviationSlNo($auditdevdata["devtype"],$auditdevdata["audit_id"],$devId);
        //$devTypeSl=$this->genDeviationTypeSlNo($auditdevdata["devtype"],$auditdevdata["audit_id"],$devId);
        $devSlArr=explode(" ",$devSl);
        $devCt=count($devSlArr)-1;
        $slType=$devSlArr[$devCt];
        
        $data1["audit_id"] = $auditdevdata["audit_id"];
        $data1["deviation_type_id"] = $auditdevdata["devtype"];
        $data1["violation_category_id"] = $auditdevdata["category_id"];
        $data1["violation_subcategory_id"] = $auditdevdata["subcategory_id"];
        $data1["observation"] = $auditdevdata["observation"];
        if($typeId!=$auditdevdata["devtype"]){
            $data1["deviation_no"] = $devSl;
            $data1["type_sl_no"] = (int)$slType;  
        }        
        $data1["status"] = 1;
        $data1["created_by"]= $this->get_auth_user("id");
        $data1["created_date"]= date("c");
        
        $this->dao->_table = "deviation_details";
        $devsave = $this->dao->save($data1);        
        print_r($devsave);
        die;
    }

    public function deldeviation(){
        $dev_id = $this->bean->get_request("dev_id");
        $data1=array();
        $data1["id"]=$dev_id;
        $this->dao->_table = "deviation_details";
        $devdel = $this->dao->del($data1);
        echo $devdel;
        die;
    }
    
    public function deldeviationimg(){
        $dev_id = $this->bean->get_request("dev_id");
        $data1=array();
        $data1["id"]=$dev_id;
        $this->dao->_table = "file_upload_audit_deviation_mapping";
        $devdel = $this->dao->del($data1);
        echo $devdel;
        die;
    }
    
    public function deldeviationimgaction(){
        $dev_id = $this->bean->get_request("dev_id");
        $devClause1                 = "id=".$dev_id;
        $dedata=$this->dao->fetchAllData('file_upload_audit_deviation_action_mapping',$devClause1);
        $uploadpath= str_replace('assets/uploads', '', UPLOADS_PATH);              
        $data1=array();
        $data1["id"]=$dev_id;
        $this->dao->_table = "file_upload_audit_deviation_action_mapping";
        $devdel = $this->dao->del($data1);
        unlink($uploadpath.$dedata[0]->file_path);
        echo $devdel;
        die;
    }
    
    public function editdeviation(){
       $devId = $this->bean->get_request("dev_id");
       $devClause1 = "id = $devId";
       $deviationdata=$this->dao->fetchAllData('deviation_details_view',$devClause1);
       
        $devClause                 = "violation_category_id = ".$deviationdata[0]->violation_category_id." AND is_deleted = 0";
        $subcategories=$this->dao->fetchAllData('master_violation_subcategory',$devClause);
        $new_dropdown = '<option value="" '.($deviationdata[0]->violation_subcategory_id==''?'selected':'').'>-choose one-</option>';
        foreach ($subcategories as $row) {
            $new_dropdown .= '<option value="'. $row->id .'" '.($deviationdata[0]->violation_subcategory_id==$row->id?'selected':'').' >' . $row->subcategory_name . '</option>';
        }
        
       $deviationfileClause="deleted = '0' AND deviation_details_id=".$deviationdata[0]->id;        
       $alldeviationsfile=$this->dao->fetchAllData('file_upload_audit_deviation_mapping',$deviationfileClause);
       $i=1;
       foreach($alldeviationsfile as $k => $v){
           $new_filehtml .= '<div class="holder" id="upload_image_box_'.  $v->id  .'">
                        <fieldset>
                            <legend>Upload Office Files</legend>
                            <label for="image_path">Upload File</label>
                            <li>'.$i.'. '.$v->name.' '.'<a href="'.'/'.FOLDERNAME.'/'.$v->file_path.'" download><i class="fa fa-download"></i></a>
                              <input type="button" id="add_upload_file" value="Remove office file" class="btn btn-sm btn-danger" onclick="remove_upload_image_box_edit('.  $v->id  .');" />
                            </li>
                            <br />
                        </fieldset>				
                    </div>';
           $i++;
       }
       $rtarr=array();
       $rtarr['devtype']=$deviationdata[0]->deviation_type_id;
       $rtarr['devcat']=$deviationdata[0]->violation_category_id;
       $rtarr['devsubcat']=$deviationdata[0]->violation_subcategory_id;
       $rtarr['devsubdetails']=$deviationdata[0]->observation;
       $rtarr['subcattxt']=$new_dropdown;
       $rtarr['filetxt']=$new_filehtml;
       echo json_encode($rtarr);
       exit;
    }
    
    public function saveParticipantData(){
        $auditpartidata = $this->bean->get_request("auditpartidata");
        $datap['audit_id']=0;
        $datap['participant_category_id']=$auditpartidata['participant_category_id'];
        $datap['name']=$auditpartidata['name'];
        $datap['partcipant_status']=($auditpartidata['participant_status']?$auditpartidata['participant_status']:'NA');
        $datap['status']=1;
        $datap['is_deleted']=0;
        $datap["created_by"] = $this->get_auth_user("id");
        $datap["created_date"] = date("c");
        $datap["modified_by"] = $this->get_auth_user("id");
        $datap["modified_date"] = date("c");
        $this->dao->_table = "audit_participant_mapping";
        $auditp = $this->dao->save($datap);
        show($auditpartidata);
        exit;
    }
    
    public function delparticipant(){
        $perti_id = $this->bean->get_request("perti_id");
        /**/
        $apmClause1 = "id = ".$perti_id;
        $getapmDetailsBYId1 = $this->dao->fetchAllData('audit_participant_mapping',$apmClause1);
        $auaClause1 = "id = ".$getapmDetailsBYId1[0]->audit_id;
        $getauaDetailsBYId1 = $this->dao->fetchAllData('audit',$auaClause1);         
        /**/
        $data1=array();
        $data1["id"]=$perti_id;
        $this->dao->_table = "audit_participant_mapping";
        $devdel = $this->dao->del($data1);                    
        if($devdel){
            $auditPartiGroup=$this->dao->getAuditPartiGrpCount($getapmDetailsBYId1[0]->audit_id);            
            $total_manpower=0;
            foreach ($auditPartiGroup as $keyg => $valueg) {
                $auditParti[$valueg->participant_category_id]=$valueg->patinum;
                $total_manpower+=$valueg->patinum;
            }
            $total_manpower+=$getauaDetailsBYId1[0]->no_technician;
            $dataudit1["no_engineer"]= $auditParti[3];
            $dataudit1["no_pset_supervisor"]= $auditParti[1];
            $dataudit1["no_cset_supervisor"]= $auditParti[2];
            $dataudit1["total_manpower"]= $total_manpower;
            $dataudit1["id"]=$getapmDetailsBYId1[0]->audit_id;                
            $this->dao->_table = "audit";
            $activityid1 = $this->dao->save($dataudit1);
        }
        echo $devdel;
        die;
    }  
    
    /*DEVIATION OBSERVATION PART (NIRMALENDU KHAN)*/
    
    /*DEVIATION OBSERVATION LIST PART (NIRMALENDU KHAN)*/
    public function deviation_view_list(){
        $action = $this->bean->get_request("_action");
        if($action=='searchDeviation'){
        $fromdate = $this->bean->get_request("fromdate");
        $todate   = $this->bean->get_request("todate");
        $deviation_sl_no   = $this->bean->get_request("deviation_sl_no");
        $statusId   = $this->bean->get_request("statusId");
        $devSrcClause="";
        if(!empty($fromdate)) {
                    $devSrcClause.=" AND CONVERT(posted_date,DATE) >= '".$fromdate."'";
                }
        if(!empty($todate)) {
                    $devSrcClause.=" AND CONVERT(posted_date,DATE) <= '".$todate."'";
                }
        if(!empty($deviation_sl_no)) {
                    $devSrcClause.=" AND deviation_no LIKE '%".$deviation_sl_no."%'";
                }
        if($statusId!=null) {            
                    $devSrcClause.=" AND status = ".$statusId;
        }        
        
        $devClause = "audit_id!=0 AND is_deleted = 0 AND audit_status=2 ".$devSrcClause;
        }else{
        $devClause = "audit_id!=0 AND is_deleted = 0 AND audit_status=2 AND status =1";
        }
        $devClause.=" ORDER BY type_name,id DESC";
        $alldeviations=$this->dao->fetchAllData('deviation_details_view',$devClause);//show($alldeviations);
        $this->beanUi->set_view_data("alldeviations", $alldeviations);
    }
    
    public function genDeviationSlNo($type_id,$audit_id,$devId){
        $devSlData=$this->dao->devSlNoGen($type_id,$audit_id);
        $devClause = "id = $type_id";
        $allDeviationType=$this->dao->fetchAllData('master_deviation_type',$devClause);
        $devTypeName=$allDeviationType[0]->type_name;
        $sl=0;
        $clause = " audit_id= :audit_id ";
        $division_department_mapping = $this->dao->get_division_department_mapping($clause, "audit_division_mapping_view",array("audit_id"=>$audit_id));
        $trrdiv=$division_department_mapping[0]->tree_division_id;
        $trrdivarr=explode('-', $trrdiv);
        $auditclause="deleted = '0' AND id = ".$audit_id;
        $allauditdetails=$this->dao->fetchAllData('audit',$auditclause);

        $devPrefix='';
        if($allauditdetails[0]->gradation_sheet_division_id==1){
            $devPrefix='ACGE';
        }else if($allauditdetails[0]->gradation_sheet_division_id==2){
            $devPrefix='ACGC';
        }else if($allauditdetails[0]->gradation_sheet_division_id==3){
            if($trrdivarr[2]==181){
                $devPrefix='BBGS';
            }else if($trrdivarr[2]==182){
                $devPrefix='TGS';
            }else if($trrdivarr[2]==183){
                $devPrefix='SGS';
            }            
        }else if($allauditdetails[0]->gradation_sheet_division_id==4){
            $devPrefix='ACGM';
        }else if($allauditdetails[0]->gradation_sheet_division_id==5){
            $devPrefix='ACGS';
        }else if($allauditdetails[0]->gradation_sheet_division_id==6){
            $devPrefix='SS';
        }else if($allauditdetails[0]->gradation_sheet_division_id==7){
            $devPrefix='TD';
        }else if($allauditdetails[0]->gradation_sheet_division_id==8){
            $devPrefix='SC';
        }
        if(!empty($devSlData)){
            $devSl=$devSlData[0]->deviation_no;
            $devSlArr=explode(" ",$devSl);
            $devCt=count($devSlArr)-1;
            $sl=$devSlArr[$devCt];
        }
        $sl=$sl+1;
        $audit_dev_sl = $devPrefix.' '.$devTypeName.' '.sprintf("%04d", $sl);        
        return $audit_dev_sl;
    }
      
    public function deviation_view_list_no(){
        $action = $this->bean->get_request("_action");
        if($action=='searchDeviation'){
        $fromdate = $this->bean->get_request("fromdate");
        $todate   = $this->bean->get_request("todate");
        $deviation_sl_no   = $this->bean->get_request("deviation_sl_no");
        $statusId   = $this->bean->get_request("statusId");
        $devSrcClause="";
        if(!empty($fromdate)) {
                    $devSrcClause.=" AND CONVERT(posted_date,DATE) >= '".$fromdate."'";
                }
        if(!empty($todate)) {
                    $devSrcClause.=" AND CONVERT(posted_date,DATE) <= '".$todate."'";
                }
        if(!empty($deviation_sl_no)) {
                    $devSrcClause.=" AND deviation_no LIKE '%".$deviation_sl_no."%'";
                }
        if($statusId!=null) {            
                    $devSrcClause.=" AND status = ".$statusId;
        }        
        
        $devClause= "audit_id!=0 AND is_deleted = 0 AND audit_status=2 AND nodal_officer_id=".$this->get_auth_user("id").$devSrcClause;
        }else{
        $devClause= "audit_id!=0 AND is_deleted = 0 AND audit_status=2 AND status =1 AND nodal_officer_id=".$this->get_auth_user("id");
        }
        $devClause.=" ORDER BY type_name,id DESC";
        
        $alldeviations=$this->dao->fetchAllData('deviation_details_view',$devClause);
        $this->beanUi->set_view_data("alldeviations", $alldeviations); 
    }
    
    public function deviationview_open(){
        $devId=$this->bean->get_request("deviation_id");
        $devClause= "id=$devId AND is_deleted = 0";
        $deviationDtls=$this->dao->fetchAllData('deviation_details_view',$devClause);        
        if(empty($deviationDtls)){
         redirect(page_link("activitynew/deviation_view_list.php"));   
        }else{
         $this->beanUi->set_view_data("deviationDtls", $deviationDtls[0]);   
        }
        
        $action = $this->bean->get_request("_action");
        if($action=='updateStatus'){
          $devId=$this->bean->get_request("deviation_id");
          $dstatus=$this->bean->get_request("dstatus");
          $devmoddate=$this->bean->get_request("devmoddate");
          $data1["id"] = $devId;
          $data1["status"] = $dstatus; 
          
          $data1["closed_by"] = $this->get_auth_user("id");
          $data1["closed_date"] = $devmoddate;  
          
          $data1["modified_by"] = $this->get_auth_user("id");
          $data1["modified_date"] = date("c");          
          $this->dao->_table = "deviation_details";
          $devsaveu = $this->dao->save($data1);                  
          //$devId=$this->bean->get_request("deviation_id");
          $devcomment=$this->bean->get_request("devcomment");
          
          $datac["deviation_id"] = $devId;
          $datac["message"] = $devcomment;          
          $datac["posted_by"] = $this->get_auth_user("id");
          $datac["posted_date"] = date("c");
          $this->dao->_table = "deviation_comments";
          $devsave = $this->dao->save($datac);
          
//          file upload start
          $uploads = array();          
            $caption = $this->bean->get_request("caption");
//            $old_image_path = $this->bean->get_request("old_image_path");            
            if (!empty($_FILES)) { 
                foreach ($_FILES as $input_name => $files) {
                    if (is_array($files["name"])) {
                        if ($files["name"][0] == "")
                            continue;
                    } elseif (!is_array($files["name"])) {
                        if ($files["name"] == "")
                            continue;
                    }                    
                    if ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/deviation_commant",
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
                $this->dao->_table = "file_upload_deviation_commant_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "" && $devId) {
                        $post_uploads["deviation_id"] = $devId;
                        $post_uploads["deviation_comment_id"] = $devsave;
                        //$post_uploads["audit_id"] = $auditIdNew;
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
          
//          file upload end
          
          
          
          if($devsave){
              redirect(page_link("activitynew/deviation_view_list.php")); 
          }
        }
        $devComClause= "deviation_id=$devId";
        $devComments=$this->dao->fetchAllData('deviation_comments_view',$devComClause);
        $deviationFileDtls=$this->dao->fetchAllData('file_upload_deviation_commant_mapping',$devComClause);
        $this->beanUi->set_view_data("devComments", $devComments); 
        $deviationFiles=array();
        foreach ($deviationFileDtls as $key => $value) {
          $deviationFiles[$value->deviation_comment_id][]=$value;  
        }        
        $this->beanUi->set_view_data("deviationFiles", $deviationFiles);
    }
    
    public function deviationview_open_no(){
        $devId=$this->bean->get_request("deviation_id");
        $devClause= "id=$devId AND is_deleted = 0";
        $deviationDtls=$this->dao->fetchAllData('deviation_details_view',$devClause);
        if(empty($deviationDtls)){
         redirect(page_link("activitynew/deviation_view_list_no.php"));   
        }else{
         $this->beanUi->set_view_data("deviationDtls", $deviationDtls[0]); 
        }
        $action = $this->bean->get_request("_action");
        if($action=='postComment'){
          $devId=$this->bean->get_request("deviation_id");
          $devcomment=$this->bean->get_request("devcomment");
          
          $data1["deviation_id"] = $devId;
          $data1["message"] = $devcomment;          
          $data1["status"] = 4;          
          $data1["posted_by"] = $this->get_auth_user("id");
          $data1["posted_date"] = date("c");
          $this->dao->_table = "deviation_comments";
          $devsave = $this->dao->save($data1);
          
          //          file upload start
          $uploads = array();
            $caption = $this->bean->get_request("caption");
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
                    
                    if ($input_name == "file_path") {
                        foreach ($files["name"] as $index_no => $image_name) {
                            $filedata = array(
                                "input_name" => $input_name,
                                "file_type" => "file",
                                "upload_path" => UPLOADS_PATH . "/deviation_no_commant",
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
                //$this->dao->_table = "file_upload_deviation_no_commant_mapping";
                $this->dao->_table = "file_upload_deviation_commant_mapping";
                foreach ($uploads as $uprow) {
                    $post_uploads = array();
                    if ($uprow["error"] != "") {
                        $errors .= $uprow["error"] . ",";
                    } elseif ($uprow["upload_path"] != "" && $devId) {
                        $post_uploads["deviation_id"] = $devId;
                        $post_uploads["deviation_comment_id"] = $devsave;
                        //$post_uploads["audit_id"] = $auditIdNew;
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
          
//          file upload end
            
            
          if($devsave){
              redirect(page_link("activitynew/deviation_view_list_no.php")); 
          }
        }
        $devComClause= "deviation_id=$devId";
        $devComments=$this->dao->fetchAllData('deviation_comments_view',$devComClause);
        $deviationFileDtls=$this->dao->fetchAllData('file_upload_deviation_commant_mapping',$devComClause);
        $this->beanUi->set_view_data("devComments", $devComments);
        $deviationFiles=array();
        foreach ($deviationFileDtls as $key => $value) {
          $deviationFiles[$value->deviation_comment_id][]=$value;  
        }       
        $this->beanUi->set_view_data("deviationFiles", $deviationFiles);
    }    
    
    /*DEVIATION OBSERVATION LIST PART (NIRMALENDU KHAN)*/
    
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
    
    public function imguploaddevaction(){
        echo "In";
    }
    public function activity_redirect_page(){
        $page               = $this->bean->get_request("page_no");  
        $fromdate_s         = $this->bean->get_request("fromdate_s");
        $todate_s           = $this->bean->get_request("todate_s");
        $activity_no_s      = $this->bean->get_request("activity_no_s");
        $search_title_s     = $this->bean->get_request("search_title_s");
        $status_id_s        = $this->bean->get_request("status_id_s");
        $districtid_s       = $this->bean->get_request("districtid_s");
    }
}
