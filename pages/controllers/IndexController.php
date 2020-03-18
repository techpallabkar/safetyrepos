<?php
class IndexController extends MainController {
	
	public function __construct() {
        $this->bean = load_bean("IndexController");
		$this->dao 	= load_dao("IndexMasterDAO");
		
		parent::__construct();
	}
	
	public function index() {
            $table = array("1"=>"activity_view","2"=>"activity_view","3"=>"activity_view","4"=>"activity_view","5"=>"audit_view","6"=>"incident_view","7"=>"ppe_audit_view","8"=>"safety_observation_view");
            $mappingtbl = array("1"=>"activity_division_mapping","2"=>"activity_division_mapping","3"=>"activity_division_mapping","4"=>"activity_division_mapping","5"=>"audit_division_mapping_view","6"=>"incident_division_mapping_view","7"=>"ppe_audit_division_mapping_view","8"=>"safety_observation_division_mapping_view");
            $colname = array("1"=>"activity_id","2"=>"activity_id","3"=>"activity_id","4"=>"activity_id","5"=>"audit_id","6"=>"incident_id","7"=>"ppe_audit_id","8"=>"safety_observation_id");
            $coldate = array("1"=>"activity_date","2"=>"activity_date","3"=>"from_date","4"=>"activity_date","5"=>"date_of_audit","6"=>"date_of_incident","7"=>"date_of_audit","8"=>"");
            $clause = "";
            $post_division_department = $this->dao->get_division_department();
            $directormessage  = $this->dao->getdirectormessage();
            $bulletinList = $this->dao->getBulletinBoard();
            
            $clause = 'id NOT IN(9,10) ORDER BY orderID ASC';
            $activity_type_master = $this->dao->get_activity_type_master($clause);
            $activity_list = array();
            $tree_division_id_arr = array();
            $devition_name = array();
            foreach($activity_type_master as $actvalue)
            {
                $tble = isset($table[$actvalue->id]) ? $table[$actvalue->id] : "" ;
                $coldatename = isset($coldate[$actvalue->id]) ? $coldate[$actvalue->id] : "" ;
                $related_activities = $this->dao->get_nonactivity_by_actype_id($tble,$actvalue->id,$coldatename);
                $activity_list[$actvalue->id] = $related_activities;

               foreach ($related_activities as $key => $value) {

                    $column_name =  $colname[$actvalue->id];
                    $clause = $column_name." = ".$value->id;
                    $division_department_mapping = $this->dao->get_division_department_mapping($clause, $mappingtbl[$actvalue->id]);
                    if (!empty($division_department_mapping)) {
                        $tree_division_id_arr = ($division_department_mapping[0]->tree_division_id != "") ? explode("-", $division_department_mapping[0]->tree_division_id) : array();
                        $divition = '';
                        if (!empty($tree_division_id_arr)) {
                            for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                            if ($tree_division_id_arr[$i] == "")
                                continue;
                            if (is_numeric($tree_division_id_arr[$i])) {
                                $divition .= get_value_by_id($tree_division_id_arr[$i], "name", $post_division_department);
                            } else {
                                $treeColon = explode(":",$tree_division_id_arr[$i]);
                                if( count($treeColon)>= 4 ){
                                    list($table_name, $table_id, $field_name) = explode(":", $tree_division_id_arr[$i]);
                                    $query = "SELECT name FROM " . $table_name;
                                    $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                                    $divition .= !empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                            }
                            }
                            $divition .= '/';

                            }

                       }
                       $devition_name[$actvalue->id][$value->id] = trim($divition, "/");
                    }
               }
            }
            $this->beanUi->set_view_data("activity_type_master", $activity_type_master);     
            $this->beanUi->set_view_data("activity_list", $activity_list);
            $this->beanUi->set_view_data("devition_names", $devition_name);
            $this->beanUi->set_view_data("directormessage", $directormessage);
            $this->beanUi->set_view_data("bulletinList", $bulletinList);
	}
        
        public function directormessage(){
            $directormessage  = $this->dao->getdirectormessage();
            $this->beanUi->set_view_data("directormessage", $directormessage);
            
            
        }
        
	public function postdata() {
		$page_type 		= $this->bean->get_request("page_type");
		$id 			= $this->bean->get_request('id');
		$page_type 		= ($page_type == "") ? "posts" : $page_type;
		$getcategory = urldecode($this->bean->get_request('category'));
		$url_suffix 	= "page_type=".$page_type."&category=".$getcategory;
		
		$categorybrk = str_replace("-"," & ",$getcategory);
		$explodecategory = explode("_", $categorybrk);
		$recent_post = $this->dao->recent_modified_post();
		$completepostdata = $this->dao->getpostcompletedata($id);
		@$articlesgetogory = $this->dao->getarticlesdata($articles);
		$flagtag = @$completepostdata[0]->total_view;
		
		if( $id > 0 ) $this->dao->updatepostTag(@$flagtag, $id);
		$mostviewd = $this->dao->getmostviewd();
		$status_options = $this->get_session("status_options");
		$post_status = isset($status_options["post_status"]) ? $status_options["post_status"] : array();
		$clause = "status_id = '".get_id_from_array("status_name", "Published", $post_status)."' ";
		
		if( @$explodecategory[0] != "" ) {
			$clause .= " AND category_label_1 = '".$explodecategory[0]."' ";
		}
		if( @$explodecategory[1] != "" ) {
			$clause .="AND category_label_2 = '".$explodecategory[1]."'";
		}
		if( @$explodecategory[2] != "" ) {
			$clause .="AND category_label_3 = '".$explodecategory[2]."'";
		}
		$limit = PAGE_LIMIT;

		$this->dao->pagging->page_type = $page_type;
		$completecategory = $this->dao->get_posts_with_paggings($clause, $limit);
		$paggin_html = getPageHtml($this->dao, $url_suffix );
		
		
		$this->beanUi->set_view_data("page", $this->bean->get_request("page"));
		$this->beanUi->set_view_data("post_uploads", $this->dao->select( "SELECT * FROM post_uploads", array( "id" => $id ) ) );
		$this->beanUi->set_view_data("paggin_html", $paggin_html);

		$this->beanUi->set_view_data("recent_post", $recent_post);
		$this->beanUi->set_view_data("completepostdata", $completepostdata);
		$this->beanUi->set_view_data("completecategory", $completecategory);
		$this->beanUi->set_view_data("articles", $articlesgetogory);
		$this->beanUi->set_view_data("mostviewd", $mostviewd);          
	}
        public function search_content() {
            $dataSearch = array();
            $datatable = array("1"=>"activity_view","2"=>"activity_view","3"=>"activity_view","4"=>"activity_view",
                                "5"=>"audit_view","6"=>"incident_view","7"=>"ppe_audit_view","8"=>"safety_observation_view");
            $searchdata     = htmlspecialchars($this->bean->get_request("searchdata"),ENT_QUOTES, 'UTF-8');
            $action         = htmlspecialchars($this->bean->get_request("searchdata"),ENT_QUOTES, 'UTF-8');
            $activites = array();
			$incident = array();
			$safety_observation = array();
            if($searchdata){
                $dataSearch = $this->dao->getsearchData($searchdata,$datatable[1]);
                $dataSearch5 = $this->dao->getAuditsearchData($searchdata,$datatable[5]);
                $dataSearch6 = $this->dao->getAuditsearchData($searchdata,$datatable[6]);
                $dataSearch7 = $this->dao->getAuditsearchData($searchdata,$datatable[7]);
                $dataSearch8 = $this->dao->getAuditsearchData($searchdata,$datatable[8]);
                
                foreach ( $dataSearch as $rows )
                {
                    $clause = 'id ='.$rows->activity_type_id;
                    $acti[0]=$this->dao->get_activities($clause);
                    $activites[$rows->id] = $acti[0];
                   
                }
                $newTreeArray = array();
                $post_division_department = $this->dao->get_division_department();
                foreach ( $dataSearch5 as $rows5 )
                {
                    $clause = 'id ='.$rows5->activity_type_id;
                    $acti[0]=$this->dao->get_activities($clause);
                    $audit[$rows5->id] = $acti[0];
                    
                    ////GET TREE DIVISION
                    $clause2 = " audit_id = ".$rows5->id;
                    $division_department_mapping = $this->dao->get_division_department_mapping($clause2, "audit_division_mapping_view");
                    if (!empty($division_department_mapping)) {
                        $tree_division_id_arr = ($division_department_mapping[0]->tree_division_id != "") ? explode("-", $division_department_mapping[0]->tree_division_id) : array();
                        $divition = '';
                        if (!empty($tree_division_id_arr)) {
                            for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                            if ($tree_division_id_arr[$i] == "")
                                continue;
                            if (is_numeric($tree_division_id_arr[$i])) {
                                $divition .= get_value_by_id($tree_division_id_arr[$i], "name", $post_division_department);
                            } else {
                                $treeColon = explode(":",$tree_division_id_arr[$i]);
                                if( count($treeColon)>= 4 ){
                                    list($table_name, $table_id, $field_name) = explode(":", $tree_division_id_arr[$i]);
                                    $query = "SELECT name FROM " . $table_name;
                                    $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                                    $divition .= !empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                            }
                            }
                            $divition .= '/';

                            }

                       }
                       $devition_name = trim($divition, "/");
                    }
                   $newTreeArray[$rows5->activity_type_id][$rows5->id] = $devition_name;
                   
                }
                
                foreach ( $dataSearch6 as $rows6 )
                {
                    $clause = 'id ='.$rows6->activity_type_id;
                    $acti[0]=$this->dao->get_activities($clause);
                    $incident[$rows6->id] = $acti[0];
                    
                    ////GET TREE DIVISION
                    $clause2 = " incident_id = ".$rows6->id;
                    $division_department_mapping = $this->dao->get_division_department_mapping($clause2, "incident_division_mapping_view");
                   
                    if (!empty($division_department_mapping)) {
                        $tree_division_id_arr = ($division_department_mapping[0]->tree_division_id != "") ? explode("-", $division_department_mapping[0]->tree_division_id) : array();
                        $divition = '';
                        if (!empty($tree_division_id_arr)) {
                            for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                            if ($tree_division_id_arr[$i] == "")
                                continue;
                            if (is_numeric($tree_division_id_arr[$i])) {
                                $divition .= get_value_by_id($tree_division_id_arr[$i], "name", $post_division_department);
                            } else {
                                $treeColon = explode(":",$tree_division_id_arr[$i]);
                                if( count($treeColon)>= 4 ){
                                    list($table_name, $table_id, $field_name) = explode(":", $tree_division_id_arr[$i]);
                                    $query = "SELECT name FROM " . $table_name;
                                    $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                                    $divition .= !empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                            }
                            }
                            $divition .= '/';

                            }

                       }
                       $devition_name = trim($divition, "/");
                    }
                   $newTreeArray[$rows6->activity_type_id][$rows6->id] = $devition_name;
                   
                }
                foreach ( $dataSearch7 as $rows7 )
                {
                    $clause = 'id ='.$rows7->activity_type_id;
                    $acti[0]=$this->dao->get_activities($clause);
                    $ppe_audit[$rows7->id] = $acti[0];
                    
                     ////GET TREE DIVISION
                    $clause2 = " ppe_audit_id = ".$rows7->id;
                    $division_department_mapping = $this->dao->get_division_department_mapping($clause2, "ppe_audit_division_mapping_view");
                    if (!empty($division_department_mapping)) {
                        $tree_division_id_arr = ($division_department_mapping[0]->tree_division_id != "") ? explode("-", $division_department_mapping[0]->tree_division_id) : array();
                        $divition = '';
                        if (!empty($tree_division_id_arr)) {
                            for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                            if ($tree_division_id_arr[$i] == "")
                                continue;
                            if (is_numeric($tree_division_id_arr[$i])) {
                                $divition .= get_value_by_id($tree_division_id_arr[$i], "name", $post_division_department);
                            } else {
                                $treeColon = explode(":",$tree_division_id_arr[$i]);
                                if( count($treeColon)>= 4 ){
                                    list($table_name, $table_id, $field_name) = explode(":", $tree_division_id_arr[$i]);
                                    $query = "SELECT name FROM " . $table_name;
                                    $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                                    $divition .= !empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                            }
                            }
                            $divition .= '/';

                            }

                       }
                       $devition_name = trim($divition, "/");
                    }
                   $newTreeArray[$rows7->activity_type_id][$rows7->id] = $devition_name;
                   ////
                   
                   
                }
                foreach ( $dataSearch8 as $rows8 )
                {
                    $clause = 'id ='.$rows8->activity_type_id;
                    $acti[0]=$this->dao->get_activities($clause);
                    $safety_observation[$rows8->id] = $acti[0];
                    
                    ////GET TREE DIVISION
                    $clause2 = " safety_observation_id = ".$rows8->id;
                    $division_department_mapping = $this->dao->get_division_department_mapping($clause2, "safety_observation_division_mapping_view");
                    if (!empty($division_department_mapping)) {
                        $tree_division_id_arr = ($division_department_mapping[0]->tree_division_id != "") ? explode("-", $division_department_mapping[0]->tree_division_id) : array();
                        $divition = '';
                        if (!empty($tree_division_id_arr)) {
                            for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                            if ($tree_division_id_arr[$i] == "")
                                continue;
                            if (is_numeric($tree_division_id_arr[$i])) {
                                $divition .= get_value_by_id($tree_division_id_arr[$i], "name", $post_division_department);
                            } else {
                                $treeColon = explode(":",$tree_division_id_arr[$i]);
                                if( count($treeColon)>= 4 ){
                                    list($table_name, $table_id, $field_name) = explode(":", $tree_division_id_arr[$i]);
                                    $query = "SELECT name FROM " . $table_name;
                                    $rowdata = $this->dao->select($query, array($field_name => $table_id), true);
                                    $divition .= !empty($rowdata) ? $rowdata->name : ucfirst($tree_division_id_arr[$i]);
                            }
                            }
                            $divition .= '/';

                            }

                       }
                       $devition_name = trim($divition, "/");
                    }
                   $newTreeArray[$rows8->activity_type_id][$rows8->id] = $devition_name;
                   
                }
               
                 $this->beanUi->set_view_data("dataSearch", $dataSearch);  
                 $this->beanUi->set_view_data("dataSearch5", $dataSearch5);  
                 $this->beanUi->set_view_data("dataSearch6", $dataSearch6);  
                 $this->beanUi->set_view_data("dataSearch7", $dataSearch7);  
                 $this->beanUi->set_view_data("dataSearch8", $dataSearch8);  
                 $this->beanUi->set_view_data("activityName", $activites);  
                 $this->beanUi->set_view_data("audit", $audit);  
                 $this->beanUi->set_view_data("incident", $incident);  
                 $this->beanUi->set_view_data("ppe_audit", $ppe_audit);  
                 $this->beanUi->set_view_data("safety_observation", $safety_observation);  
            }
            
           $this->beanUi->set_view_data("dataSearch", $dataSearch);   
           $this->beanUi->set_view_data("searchdata", $searchdata);   
           $this->beanUi->set_view_data("treeDivisionDepartment", $newTreeArray);   
        }
}
