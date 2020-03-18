<?php
class ActivityController extends MainController {
	
    public function __construct() {
            $this->bean = load_bean("ActivityMasterBean");
            $this->dao 	= load_dao("ActivityMasterDAO");
            $this->indexdao 	= load_dao("IndexMasterDAO");

            parent::__construct();
    }

    public function gallery(){
        $gallerycategory = $this->dao->getGalleryCategory();
        $this->beanUi->set_view_data("gallerycategory", $gallerycategory);
    }

    public function thumb_gallery(){
        $cat_id = $this->bean->get_request("cat_id");
        $clause = " id =$cat_id";
        $gallerycategory = $this->dao->getGalleryCategory($clause);
        $getGalleryData = $this->dao->getGalleryData($cat_id);
        $this->beanUi->set_view_data("gallerycategory", $gallerycategory);
        $this->beanUi->set_view_data("getGalleryData", $getGalleryData);
    }
        
    public function activities() {
        $mappingtbl = array("5"=>"audit_division_mapping_view","6"=>"incident_division_mapping_view","7"=>"ppe_audit_division_mapping_view","8"=>"safety_observation_division_mapping_view");
        $table = array("1"=>"activity_view",
            "2"=>"activity_view",
            "3"=>"activity_view",
            "4"=>"activity_view",
            "5"=>"audit_view",
            "6"=>"incident_view",
            "7"=>"ppe_audit_view",
            "8"=>"safety_observation_view");
        $colname = array("1"=>"activity_id","2"=>"activity_id","3"=>"activity_id","4"=>"activity_id","5"=>"audit_id","6"=>"incident_id","7"=>"ppe_audit_id","8"=>"safety_observation_id");
        $coldate = array("1"=>"activity_date","2"=>"activity_date","3"=>"from_date","4"=>"activity_date","5"=>"date_of_audit","6"=>"date_of_incident","7"=>"date_of_audit","8"=>"id");
        $actype_id 		= $this->bean->get_request('actype_id');
        $page_type 		= $this->bean->get_request("page_type");
        $page_type 		= ($page_type == "") ? "activities" : $page_type;
        $url_suffix             = "page_type=".htmlspecialchars($page_type,ENT_QUOTES, 'UTF-8')."&actype_id=".htmlspecialchars($actype_id,ENT_QUOTES, 'UTF-8');
        $limit 			= 5;
        $this->dao->pagging->page_type = $page_type;
        $activities 	= $this->dao->get_activity_by_actype_id($actype_id, $limit,$table[$actype_id],$coldate[$actype_id]);
        $paggin_html = getPageHtml($this->dao, $url_suffix);
        $post_division_department = $this->dao->get_division_department();
        
        foreach( $activities as $key => $value )
        {
             $column_name =  $colname[$actype_id];
                    $clause = $column_name." = ".$value->id;
                    $division_department_mapping = $this->indexdao->get_division_department_mapping($clause, $mappingtbl[$actype_id]);
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
                       $devition_name[$actype_id][$value->id] = trim($divition, "/");
                    }
        }
        $this->beanUi->set_view_data("page", $this->bean->get_request("page"));
        $this->beanUi->set_view_data("paggin_html", $paggin_html);
        $this->beanUi->set_view_data("activity_type_id", $actype_id);
        $this->beanUi->set_view_data("activities", $activities);

        $this->beanUi->set_view_data("activity_type_master",$this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("devition_name", @$devition_name);
    }

    public function activitydetails() {
            $table = array("1"=>"activity_view",
                        "2"=>"activity_view",
                        "3"=>"activity_view",
                        "4"=>"activity_view",
                        "5"=>"audit_view",
                        "6"=>"incident_view",
                        "7"=>"ppe_audit_view",
                        "8"=>"safety_observation_view");   
            $whereid = array("1"=>"activity_id",
                        "2"=>"activity_id",
                        "3"=>"activity_id",
                        "4"=>"activity_id",
                        "5"=>"audit_id",
                        "6"=>"incident_id",
                        "7"=>"ppe_audit_id",
                        "8"=>"safety_observation_id");   
            $filemaping = array("1"=>"file_upload_activity_mapping",
                        "2"=>"file_upload_activity_mapping",
                        "3"=>"file_upload_activity_mapping",
                        "4"=>"file_upload_activity_mapping",
                        "5"=>"file_upload_audit_mapping",
                        "6"=>"file_upload_incident_mapping",
                        "7"=>"file_upload_ppe_audit_mapping",
                        "8"=>"");   
		$id = $this->bean->get_request('id');
		$actype_id = $this->bean->get_request('actype_id');
		$actmonth = $this->bean->get_request('actmonth');
		$actyear = $this->bean->get_request('actyear');
		$actcount = $this->bean->get_request('actcount');
		$subid = $this->bean->get_request('subid');
		$incident_category = $this->dao->get_incident_category();  
		
		$nature_injury = $this->dao->get_nature_injury();
                $totalsafetyobsrecord = array();
                if( $actmonth != "" && $actyear != "" ) {
		$totalsafetyobsrecord = $this->dao->get_total_record_sfobs_monthyear($actmonth,$actyear);
                $post_division_department = $this->dao->get_division_department();
                /**********division depart for edit start*****/
                $tree_name = array();
                if( ! empty( $totalsafetyobsrecord ) ) {
                    foreach( $totalsafetyobsrecord as $k => $row ) {
                        $tree_division_id_arr = ($row->tree_division_id != "") ? explode( "-", $row->tree_division_id ) : array();
                        $divition = '';
                        if( ! empty( $tree_division_id_arr ) ) {
                            
                            for( $i = 0; $i < count($tree_division_id_arr); $i++ ) {
                                if( $tree_division_id_arr[$i] == "" ) continue;
                                
                                
                                if( is_numeric($tree_division_id_arr[$i]) ) {
                                    $divition .= get_value_by_id($tree_division_id_arr[$i], "name", $post_division_department);
                                }
                                else
                                {
                                    list($table_name, $table_id) = explode(":", $tree_division_id_arr[$i]);
                                    $query = "SELECT name FROM ".$table_name;
                                    $rowdata = $this->dao->select( $query, array("id" => $table_id), true );
                                    $divition .= ! empty( $rowdata ) ? $rowdata->name : "";
                                }
                                $divition .= '/';
                            }
                        }
                        $totalsafetyobsrecord[$k]->treename = trim($divition,'/');
                       
                    }
                   
                }
                
                }
		
        
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
        
        $clause = " incident_id=" . $id . " ";
        $findingsx = $this->dao->get_findings_by_activity($clause);
        
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
		               
		$clause = " audit_id=" . $id . " ";
		$violation = $this->dao->get_violation_by_activity($clause);
		                 
        $clause = " ppe_audit_id=" . $id . " ";
        $deviation = $this->dao->get_deviation_by_activity($clause);
        
                
		if($actype_id <5){
			$activity = $this->dao->getactivity($id,$subid );
		}else{
		  $activity = $this->dao->getactivitys($id,$subid,$table[$actype_id],$whereid[$actype_id],$filemaping[$actype_id] );  
		}
		
		$activity_type_id = $this->bean->get_request('actype_id');
		if(empty($id))
		{
			redirect(page_link("activities.php?actype_id=".$activity_type_id));
		}

	
		      
                
		$clause = " activity_id=".$id;
		$division_department = $this->dao->get_division_by_activity($clause);
		
		$pclause = " activity_id=".$id;
		$activity_participants = $this->dao->get_participants_by_activity($pclause);
		
		$participants_list = array();
		foreach($activity_participants as $actvalue)
		{                
			$related_activities = $this->dao->get_participants_details($actvalue->id);
			$participants_list[$actvalue->id] = $related_activities;
		}
		
		$mappingtbl = array("1" => "activity_division_mapping_view","2" => "activity_division_mapping_view","3" => "activity_division_mapping_view","4" => "activity_division_mapping_view","5"=>"audit_division_mapping_view","6"=>"incident_division_mapping_view","7"=>"ppe_audit_division_mapping_view","8"=>"safety_observation_division_mapping_view");
		$clause = " ".$whereid[$actype_id]." = ".$id;
		
		$division_department_mapping = $this->dao->get_division_department_mapping($clause,$mappingtbl[$actype_id]);
		$post_division_department = $this->dao->get_division_department();
                /**********division depart for edit start*****/
                $devition_name = array();
                if( ! empty( $division_department_mapping ) ) {
                    foreach( $division_department_mapping as $row ) {
                        $tree_division_id_arr = ($row->tree_division_id != "") ? explode( "-", $row->tree_division_id ) : array();
                        $divition = '';
                        if( ! empty( $tree_division_id_arr ) ) {
                            
                            for( $i = 0; $i < count($tree_division_id_arr); $i++ ) {
                                if( $tree_division_id_arr[$i] == "" ) continue;
                                
                                
                                if( is_numeric($tree_division_id_arr[$i]) ) {
                                    $divition .= get_value_by_id($tree_division_id_arr[$i], "name", $post_division_department);
                                }
                                else
                                {
                                    list($table_name, $table_id) = explode(":", $tree_division_id_arr[$i]);
                                    $query = "SELECT name FROM ".$table_name;
                                    $rowdata = $this->dao->select( $query, array("id" => $table_id), true );
                                    $divition .= ! empty( $rowdata ) ? $rowdata->name : "";
                                }
                                $divition .= '/';
                            }
                        }
                        $devition_name[$row->id] = trim($divition,"/");

                    }
                   
                }
                 /**********division depart for edit end*****/
            
                $this->beanUi->set_view_data("devition_names", $devition_name);
             
                
                $this->beanUi->set_view_data("activity", $activity);
		$this->beanUi->set_view_data("activity_type_master",$this->dao->get_activity_type_master());
		$this->beanUi->set_view_data("participants_list", $participants_list);
		$this->beanUi->set_view_data("activity_participants",$activity_participants);
		$this->beanUi->set_view_data("division_department",$division_department);
		$this->beanUi->set_view_data("deviation", $deviation);
		$this->beanUi->set_view_data("violation", $violation);
		$this->beanUi->set_view_data("violation_category", $violation_category);
		$this->beanUi->set_view_data("body_part_injury_mappingData", $body_part_injury_mappingData1);
                $this->beanUi->set_view_data("body_part_injury", $body_part_injury);
                $this->beanUi->set_view_data("childData", $childArray);
                $this->beanUi->set_view_data("injury_status", $injury_status);
		$this->beanUi->set_view_data("incident_category", $incident_category);
		$this->beanUi->set_view_data("nature_of_injury", $nature_injury);
		$this->beanUi->set_view_data("finding_rows", $findingsx);
		$this->beanUi->set_view_data("totalsafetyobsrecord", $totalsafetyobsrecord);
		$this->beanUi->set_view_data("actcount", $actcount);
                        
	}
        
    public function events()
        {
            $eventId = $this->bean->get_request("eventId");
            $clause = " event_category_id = :event_category_id ";
            $passValue["event_category_id"] = $eventId;
            $upcomingEvents = $this->dao->getUpcomingEvents($clause,$passValue);
            $clause = " id = $eventId";
            $event = $this->dao->getEventCategory($clause);
            $this->beanUi->set_view_data("upcomingEvents", $upcomingEvents);
            $this->beanUi->set_view_data("eventName", $event[0]->name);
        }
          
    public function event_details()
        {
            $rowid  =   $this->bean->get_request("rowid");
            $upcomingEvents = $this->dao->getUpcomingEvents($rowid,"id");
            $this->beanUi->set_view_data("upcomingEvents", $upcomingEvents);
            
        }
	
        
}
