
<?php
class ReportController extends MainController {
	
    public function __construct() {
            error_reporting(E_ALL);
            $this->dao 	= load_dao("ReportDAO");
            parent::__construct();
    }
	 
    protected function index() {
        $search_title 			= $this->bean->get_request("search_title");
        $fromdate 			= $this->bean->get_request("fromdate");
        $todate 			= $this->bean->get_request("todate");
        $status_id 			= $this->bean->get_request("status_id");
        $activity_type_id 		= $this->bean->get_request("activity_id");
        $incident_category_id           = $this->bean->get_request("incident_category_id");
        $activity_no			= $this->bean->get_request("activity_no");
        $auth_user_id 			= $this->get_auth_user("id");        
        $role_id 			= $this->get_auth_user("role_id");      
        
        $limit = 10;
        if($role_id == 1)
        {
            $check_role = " AND deleted= '0' AND ((status_id!= 1) OR (status_id = 1 && created_by = '".$auth_user_id."'))";
        }
         else if($role_id == 3)
        {
            $check_role = " AND deleted= '0' AND ((status_id!= 1) OR (status_id = 1 && created_by = '".$auth_user_id."'))";
        }
        else if($role_id == 2)
        {
            $check_role = " AND deleted= '0'  AND created_by = '".$auth_user_id."' OR status_id=5";
        }
        //activity - workshop , comm. meeting, Training, Safety Days
        $clause = "";   
        $incident_category = $this->dao->get_incident_category();
        $this->beanUi->set_view_data("incident_category", $incident_category);
        $this->beanUi->set_view_data("page", $this->bean->get_request("page"));
        $this->beanUi->set_view_data("post_status", $this->get_status_options("post_status"));
        $this->beanUi->set_view_data("search_title", $this->bean->get_request("search_title"));
        $this->beanUi->set_view_data("status_id", $this->bean->get_request("status_id"));
        $this->beanUi->set_view_data("fromdate", $this->bean->get_request("fromdate"));
        $this->beanUi->set_view_data("incident_category_id", $this->bean->get_request("incident_category_id"));
        $this->beanUi->set_view_data("todate", $this->bean->get_request("todate"));
        $this->beanUi->set_view_data("post_activity_type_master", $this->dao->get_activity_type_master());
        $this->beanUi->set_view_data("activity_type_id", $activity_type_id);
    }    
    
    public function view_report() {
        $st = $this->bean->get_request("st");    
           
        $_action = $this->bean->get_request("_action");   
        if($_action == "Search") {
            $from_date = $this->bean->get_request("from_date");    
            $to_date = $this->bean->get_request("to_date"); 
            $min_date = "01-".$from_date;
            $max_date = "01-".$to_date;
        }
        else {
        $getDate = $this->dao->getMinMaxDate();  
        $min_date = $getDate[0]->min_date;
        $max_date = $getDate[0]->max_date;
        }
        
        
        $date1 = $min_date;
        $date2 = $max_date;
        
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        $montharray         = array();
        $workshopReport     = $communicationReport  = $trainingReport = $safetydaysReport   =  array();       
        $siteauditReport    = $incidentReport       = $ppeauditReport = $safetyobsReport    = array();
     
        for($i=0;$i<=$diff;$i++)
        {
           
            $dt     = date("Y-m",strtotime("+$i Month",strtotime($date1)));
            $month  = date("m",strtotime("+$i Month",strtotime($date1)));
            $year   = date("Y",strtotime("+$i Month",strtotime($date1)));
            $workshopReport[$dt][1]       = $this->dao->getActivityReport(1,$dt,"activity_date","activity",$st,1); 
            $workshopReport[$dt][2]       = $this->dao->getActivityReport(1,$dt,"activity_date","activity",$st,2); 
            $workshopReport[$dt][5]       = $this->dao->getActivityReport(1,$dt,"activity_date","activity",$st,5); 
            
            $communicationReport[$dt][1]  = $this->dao->getActivityReport(2,$dt,"activity_date","activity",$st,1); 
            $communicationReport[$dt][2]  = $this->dao->getActivityReport(2,$dt,"activity_date","activity",$st,2); 
            $communicationReport[$dt][5]  = $this->dao->getActivityReport(2,$dt,"activity_date","activity",$st,5); 
            
            $trainingReport[$dt][1]       = $this->dao->getTrainingReport($dt,$st,1); 
            $trainingReport[$dt][2]       = $this->dao->getTrainingReport($dt,$st,2); 
            $trainingReport[$dt][5]       = $this->dao->getTrainingReport($dt,$st,5); 
            
            $safetydaysReport[$dt][1]     = $this->dao->getActivityReport(4,$dt,"activity_date","activity",$st,1); 
            $safetydaysReport[$dt][2]     = $this->dao->getActivityReport(4,$dt,"activity_date","activity",$st,2); 
            $safetydaysReport[$dt][5]     = $this->dao->getActivityReport(4,$dt,"activity_date","activity",$st,5); 
            
            $siteauditReport[$dt][1]      = $this->dao->getActivityReport(5,$dt,"date_of_audit","audit",$st,1); 
            $siteauditReport[$dt][2]      = $this->dao->getActivityReport(5,$dt,"date_of_audit","audit",$st,2); 
            $siteauditReport[$dt][5]      = $this->dao->getActivityReport(5,$dt,"date_of_audit","audit",$st,5); 
            
            $incidentReport[$dt][1]       = $this->dao->getActivityReport(6,$dt,"date_of_incident","incident",$st,1); 
            $incidentReport[$dt][2]       = $this->dao->getActivityReport(6,$dt,"date_of_incident","incident",$st,2); 
            $incidentReport[$dt][5]       = $this->dao->getActivityReport(6,$dt,"date_of_incident","incident",$st,5);
            
            $ppeauditReport[$dt][1]       = $this->dao->getActivityReport(7,$dt,"date_of_audit","ppe_audit",$st,1); 
            $ppeauditReport[$dt][2]       = $this->dao->getActivityReport(7,$dt,"date_of_audit","ppe_audit",$st,2); 
            $ppeauditReport[$dt][5]       = $this->dao->getActivityReport(7,$dt,"date_of_audit","ppe_audit",$st,5); 
            
            $safetyobsReport[$dt][1]      = $this->dao->getSafetyObsReport(8,$month,$year,$st,1); 
            $safetyobsReport[$dt][2]      = $this->dao->getSafetyObsReport(8,$month,$year,$st,2); 
            $safetyobsReport[$dt][5]      = $this->dao->getSafetyObsReport(8,$month,$year,$st,5); 
            
            if( $workshopReport[$dt]==0 && $communicationReport[$dt]==0 && $trainingReport[$dt]==0 && $safetydaysReport[$dt]==0 && $siteauditReport[$dt]==0 && $incidentReport[$dt]==0 && $ppeauditReport[$dt]==0 && $safetyobsReport[$dt]==0)
            {
                $montharray[$i] = 0;            
            }
            else
            {
                $montharray[$i]= date("Y-m",strtotime("+$i Month",strtotime($date1)));
            }
            
        }
        
        $allActivity = $this->dao->get_activities();
        $this->beanUi->set_view_data("allActivity", $allActivity);        
        $this->beanUi->set_view_data("montharray", $montharray);
        $this->beanUi->set_view_data("workshopReport", $workshopReport);
        $this->beanUi->set_view_data("communicationReport", $communicationReport);
        $this->beanUi->set_view_data("trainingReport", $trainingReport);
        $this->beanUi->set_view_data("safetydaysReport", $safetydaysReport);
        $this->beanUi->set_view_data("siteauditReport", $siteauditReport);
        $this->beanUi->set_view_data("incidentReport", $incidentReport);
        $this->beanUi->set_view_data("ppeauditReport", $ppeauditReport);
        $this->beanUi->set_view_data("safetyobsReport", $safetyobsReport);
    }
    
    public function view_report_DraftFinalApproved() {
        $st = $this->bean->get_request("st");    
           
        $_action = $this->bean->get_request("_action");   
        if($_action == "Search") {
            $from_date = $this->bean->get_request("from_date");    
            $to_date = $this->bean->get_request("to_date"); 
            $min_date = "01-".$from_date;
            $max_date = "01-".$to_date;
        } else {
        $getDate = $this->dao->getMinMaxDate();   
        $min_date = $getDate[0]->min_date;
        $max_date = $getDate[0]->max_date;
        }
        
        
        $date1 = $min_date;
        $date2 = $max_date;
        
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        $montharray         = array();
        $workshopReport     = $communicationReport  = $trainingReport = $safetydaysReport   =  array();       
        $siteauditReport    = $incidentReport       = $ppeauditReport = $safetyobsReport    = array();
     
        for($i=0;$i<=$diff;$i++)
        {
           
            $dt     = date("Y-m",strtotime("+$i Month",strtotime($date1)));
            $month  = date("m",strtotime("+$i Month",strtotime($date1)));
            $year   = date("Y",strtotime("+$i Month",strtotime($date1)));
            $workshopReport[$dt][1]       = $this->dao->getActivityReport(1,$dt,"activity_date","activity",$st='NA',1); 
            $workshopReport[$dt][2]       = $this->dao->getActivityReport(1,$dt,"activity_date","activity",$st='NA',2); 
            $workshopReport[$dt][5]       = $this->dao->getActivityReport(1,$dt,"activity_date","activity",$st='A',5); 
            
            $communicationReport[$dt][1]  = $this->dao->getActivityReport(2,$dt,"activity_date","activity",$st='NA',1); 
            $communicationReport[$dt][2]  = $this->dao->getActivityReport(2,$dt,"activity_date","activity",$st='NA',2); 
            $communicationReport[$dt][5]  = $this->dao->getActivityReport(2,$dt,"activity_date","activity",$st='A',5); 
            
            $trainingReport[$dt][1]       = $this->dao->getTrainingReport($dt,$st='NA',1); 
            $trainingReport[$dt][2]       = $this->dao->getTrainingReport($dt,$st='NA',2); 
            $trainingReport[$dt][5]       = $this->dao->getTrainingReport($dt,$st='A',5); 
            
            $safetydaysReport[$dt][1]     = $this->dao->getActivityReport(4,$dt,"activity_date","activity",$st='NA',1); 
            $safetydaysReport[$dt][2]     = $this->dao->getActivityReport(4,$dt,"activity_date","activity",$st='NA',2); 
            $safetydaysReport[$dt][5]     = $this->dao->getActivityReport(4,$dt,"activity_date","activity",$st='A',5); 
            
            $siteauditReport[$dt][1]      = $this->dao->getActivityReport(5,$dt,"date_of_audit","audit",$st='NA',1); 
            $siteauditReport[$dt][2]      = $this->dao->getActivityReport(5,$dt,"date_of_audit","audit",$st='NA',2); 
            $siteauditReport[$dt][5]      = $this->dao->getActivityReport(5,$dt,"date_of_audit","audit",$st='A',5); 
            
            $incidentReport[$dt][1]       = $this->dao->getActivityReport(6,$dt,"date_of_incident","incident",$st='NA',1); 
            $incidentReport[$dt][2]       = $this->dao->getActivityReport(6,$dt,"date_of_incident","incident",$st='NA',2); 
            $incidentReport[$dt][5]       = $this->dao->getActivityReport(6,$dt,"date_of_incident","incident",$st='A',5);
            
            $ppeauditReport[$dt][1]       = $this->dao->getActivityReport(7,$dt,"date_of_audit","ppe_audit",$st='NA',1); 
            $ppeauditReport[$dt][2]       = $this->dao->getActivityReport(7,$dt,"date_of_audit","ppe_audit",$st='NA',2); 
            $ppeauditReport[$dt][5]       = $this->dao->getActivityReport(7,$dt,"date_of_audit","ppe_audit",$st='A',5); 
            
            $safetyobsReport[$dt][1]      = $this->dao->getSafetyObsReport(8,$month,$year,$st='NA',1); 
            $safetyobsReport[$dt][2]      = $this->dao->getSafetyObsReport(8,$month,$year,$st='NA',2); 
            $safetyobsReport[$dt][5]      = $this->dao->getSafetyObsReport(8,$month,$year,$st='A',5); 
            
            if( $workshopReport[$dt]==0 && $communicationReport[$dt]==0 && $trainingReport[$dt]==0 && $safetydaysReport[$dt]==0 && $siteauditReport[$dt]==0 && $incidentReport[$dt]==0 && $ppeauditReport[$dt]==0 && $safetyobsReport[$dt]==0)
            {
                $montharray[$i] = 0;            
            }
            else
            {
                $montharray[$i]= date("Y-m",strtotime("+$i Month",strtotime($date1)));
            }
            
        }

        $allActivity = $this->dao->get_activities();
        $this->beanUi->set_view_data("allActivity", $allActivity);        
        $this->beanUi->set_view_data("montharray", $montharray);
        $this->beanUi->set_view_data("workshopReport", $workshopReport);
        $this->beanUi->set_view_data("communicationReport", $communicationReport);
        $this->beanUi->set_view_data("trainingReport", $trainingReport);
        $this->beanUi->set_view_data("safetydaysReport", $safetydaysReport);
        $this->beanUi->set_view_data("siteauditReport", $siteauditReport);
        $this->beanUi->set_view_data("incidentReport", $incidentReport);
        $this->beanUi->set_view_data("ppeauditReport", $ppeauditReport);
        $this->beanUi->set_view_data("safetyobsReport", $safetyobsReport);
    }
    
    public function reportEmployeeWise() {
        $allActivity = $this->dao->get_activities();
        $allUsers = $this->dao->getAllUsers();
        $workshop = $commmeeting = $training = $safetyDays = $siteAudit = $incident = $ppeAudit = $safeyObs = $safeyObsLIneFunc = array();
        $_action = $this->bean->get_request("_action");       
        $from_date = $to_date = "";
        $montharr= array();
        if( $_action == "Search" ) {
            $from_date = $this->bean->get_request("from_date");    
            $to_date = $this->bean->get_request("to_date"); 
            
            $date1 = $from_date;
            $date2 = $to_date;

            $ts1 = strtotime($date1);
            $ts2 = strtotime($date2);

            $year1 = date('Y', $ts1);
            $year2 = date('Y', $ts2);

            $month1 = date('m', $ts1);
            $month2 = date('m', $ts2);
            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
            

           for($i=0;$i<=$diff;$i++)
            {
               $montharr[$i]= date("Y-m",strtotime("+$i Month",strtotime($date1)));

           }

        } 
        
        foreach( $allUsers as $value )
        {
           $workshop[$value->id][1]     = $this->dao->getReportofPostUserWise(1,$from_date,$to_date,"activity_date","activity",$value->id,1);
           $workshop[$value->id][2]     = $this->dao->getReportofPostUserWise(1,$from_date,$to_date,"activity_date","activity",$value->id,2);
           $workshop[$value->id][5]     = $this->dao->getReportofPostUserWise(1,$from_date,$to_date,"activity_date","activity",$value->id,5);
           
           $commmeeting[$value->id][1]  = $this->dao->getReportofPostUserWise(2,$from_date,$to_date,"activity_date","activity",$value->id,1);
           $commmeeting[$value->id][2]  = $this->dao->getReportofPostUserWise(2,$from_date,$to_date,"activity_date","activity",$value->id,2);
           $commmeeting[$value->id][5]  = $this->dao->getReportofPostUserWise(2,$from_date,$to_date,"activity_date","activity",$value->id,5);
           
           $training[$value->id][1]     = $this->dao->getTrainingReportPostUserWise($value->id,$from_date,$to_date,1);
           $training[$value->id][2]     = $this->dao->getTrainingReportPostUserWise($value->id,$from_date,$to_date,2);
           $training[$value->id][5]     = $this->dao->getTrainingReportPostUserWise($value->id,$from_date,$to_date,5);
           
           $safetyDays[$value->id][1]   = $this->dao->getReportofPostUserWise(4,$from_date,$to_date,"activity_date","activity",$value->id,1);
           $safetyDays[$value->id][2]   = $this->dao->getReportofPostUserWise(4,$from_date,$to_date,"activity_date","activity",$value->id,2);
           $safetyDays[$value->id][5]   = $this->dao->getReportofPostUserWise(4,$from_date,$to_date,"activity_date","activity",$value->id,5);
           
           $siteAudit[$value->id][1]    = $this->dao->getReportofPostUserWise(5,$from_date,$to_date,"date_of_audit","audit",$value->id,1);
           $siteAudit[$value->id][2]    = $this->dao->getReportofPostUserWise(5,$from_date,$to_date,"date_of_audit","audit",$value->id,2);
           $siteAudit[$value->id][5]    = $this->dao->getReportofPostUserWise(5,$from_date,$to_date,"date_of_audit","audit",$value->id,5);
           
           $incident[$value->id][1]     = $this->dao->getReportofPostUserWise(6,$from_date,$to_date,"date_of_incident","incident",$value->id,1);
           $incident[$value->id][2]     = $this->dao->getReportofPostUserWise(6,$from_date,$to_date,"date_of_incident","incident",$value->id,2);
           $incident[$value->id][5]     = $this->dao->getReportofPostUserWise(6,$from_date,$to_date,"date_of_incident","incident",$value->id,5);
           
           $ppeAudit[$value->id][1]     = $this->dao->getReportofPostUserWise(7,$from_date,$to_date,"date_of_audit","ppe_audit",$value->id,1);
            $ppeAudit[$value->id][2]    = $this->dao->getReportofPostUserWise(7,$from_date,$to_date,"date_of_audit","ppe_audit",$value->id,2);
           $ppeAudit[$value->id][5]     = $this->dao->getReportofPostUserWise(7,$from_date,$to_date,"date_of_audit","ppe_audit",$value->id,5);
           
           $safeyObs[$value->id][1]     = $this->dao->getSafetyObsReportPostUserWise($value->id,$montharr,1);
           $safeyObs[$value->id][2]     = $this->dao->getSafetyObsReportPostUserWise($value->id,$montharr,2);
           $safeyObs[$value->id][5]     = $this->dao->getSafetyObsReportPostUserWise($value->id,$montharr,5);
           
           /*$safeyObsLIneFunc[$value->id][1] = $this->dao->getReportofPostUserWise(9,"safety_observation_line_function",$value->id,1);
           $safeyObsLIneFunc[$value->id][2] = $this->dao->getReportofPostUserWise(9,"safety_observation_line_function",$value->id,2);
           $safeyObsLIneFunc[$value->id][5] = $this->dao->getReportofPostUserWise(9,"safety_observation_line_function",$value->id,5);*/
        }
        $this->beanUi->set_view_data("workshop", $workshop);
        $this->beanUi->set_view_data("commMeeting", $commmeeting);
        $this->beanUi->set_view_data("training", $training);
        $this->beanUi->set_view_data("safetyDays", $safetyDays);
        $this->beanUi->set_view_data("siteAudit", $siteAudit);
        $this->beanUi->set_view_data("incident", $incident);
        $this->beanUi->set_view_data("ppeAudit", $ppeAudit);
        $this->beanUi->set_view_data("safeyObs", $safeyObs);
        $this->beanUi->set_view_data("safeyObsLIneFunc", $safeyObsLIneFunc);        
        $this->beanUi->set_view_data("allUsers", $allUsers);
        $this->beanUi->set_view_data("allActivity", $allActivity);
    }
    
    public function yearlyTarget() {        
        
        
        $allActivity = $this->dao->get_activities();
        $allFinancialYear = $this->dao->allFinancialYear();
		$financial_year_id = "";
		$financial_district_id_create = "";
		$alldatagen = "";
        $_action = $this->bean->get_request("_action");
        
        
          /*******/
            $arr      = array();
            $scoreID  = $this->dao->getReportSettings(2,"id");
            $rowArray = explode(",",$scoreID[0]->param_value);
            foreach ($rowArray as $k => $rr) {
            $clause   = " id='" . $rr . "'";
            $parentDivisionxx = $this->dao->get_division_department($clause);
            $arr[]= $parentDivisionxx[0];
            }
            $this->beanUi->set_view_data("allDistrict", $arr);
            /*******/
            
        $alldata = array();
        $financial_year= "";
        if( $_action == "Create" ) { 
          
        $financial_year_id_create  = $this->bean->get_request("financial_year");
        $financial_district_id_create  = $this->bean->get_request("financial_district");
        foreach($allActivity as $rowsData)
            {
                
                $alldatagenValue = $this->dao->alldatafromyearview($financial_year_id_create,$financial_district_id_create,$rowsData->id);
//                $alldatagen[$rowsData->id] = $this->dao->alldatafromyearview($financial_year_id_create,$financial_district_id_create,$rowsData->id);
            $april_month = $may_month = $june_month = $july_month = $august_month = $september_month = $october_month = $november_month = $december_month = $january_month = $february_month = $march_month = $total_calculation ="";
               foreach($alldatagenValue as $rowIDs)
               {
                   $april_month     += $rowIDs->april_month;
                   $may_month       += $rowIDs->may_month;
                   $june_month      += $rowIDs->june_month;
                   $july_month      += $rowIDs->july_month;
                   $august_month    += $rowIDs->august_month;
                   $september_month += $rowIDs->september_month;
                   $october_month   += $rowIDs->october_month;
                   $november_month  += $rowIDs->november_month;
                   $december_month  += $rowIDs->december_month;
                   $january_month   += $rowIDs->january_month;
                   $february_month  += $rowIDs->february_month;
                   $march_month     += $rowIDs->march_month;
                   $total_calculation += $rowIDs->total_calculation;
               }
                $arrData[$rowsData->id]["april_month"] = $april_month;
                $arrData[$rowsData->id]["may_month"] = $may_month;
                $arrData[$rowsData->id]["june_month"] = $june_month;
                $arrData[$rowsData->id]["july_month"] = $july_month;
                $arrData[$rowsData->id]["august_month"] = $august_month;
                $arrData[$rowsData->id]["september_month"] = $september_month;
                $arrData[$rowsData->id]["october_month"] = $october_month;
                $arrData[$rowsData->id]["november_month"] = $november_month;
                $arrData[$rowsData->id]["december_month"] = $december_month;
                $arrData[$rowsData->id]["january_month"] = $january_month;
                $arrData[$rowsData->id]["february_month"] = $february_month;
                $arrData[$rowsData->id]["march_month"] = $march_month;
                $arrData[$rowsData->id]["total_calculation"] = $total_calculation;
                $arrData[$rowsData->id]["activity_type_id"] = $rowsData->id;
                $arrData[$rowsData->id]["district_id"] = $financial_district_id_create;
                $arrData[$rowsData->id]["financial_year_id"] = $financial_year_id_create;
            }
        //$alldatadist = $this->dao->alldatafromyear($financial_year_id_create,"DIST");
       
        $fData              = $this->dao->allFinancialYear($financial_year_id_create);
        $financial_year     = $fData[0]->financial_year;
         $fromsubmit  = $this->bean->get_request("fromsubmit");
           if($fromsubmit !=""){
                $this->beanUi->set_success_message("Yearly target of <b> $financial_year </b> is successfully saved.");
           }
        
        $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
        $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
        $this->beanUi->set_view_data("alldatagen", $arrData);
        //$this->beanUi->set_view_data("alldatadist", $alldatadist);
        $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
        $this->beanUi->set_view_data("financial_district_id", $financial_district_id_create);
        }
      
        if($financial_year_id){
        $fData              = $this->dao->allFinancialYear($financial_year_id);
        $financial_year     = $fData[0]->financial_year; 
         $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("financial_district_for_select", $financial_district_id_create);
        $this->beanUi->set_view_data("alldatagen", $alldatagen);
        //$this->beanUi->set_view_data("alldatadist", $alldatadist);
        $this->beanUi->set_view_data("allActivity", $allActivity);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
    }
    
    public function scoreTarget() {        
        
        
        $allActivity = $this->dao->get_activities();
        $allFinancialYear = $this->dao->allFinancialYear();
		$financial_year_id = "";
		$alldatagen  = "";
		$alldatadist = "";
		
         /*******/
            $allfilteredDistrict  = array();
            $scoreID  = $this->dao->getReportSettings(2,"id");
            $rowArray = explode(",",$scoreID[0]->param_value);
            foreach ($rowArray as $k => $rr) {
            $clause   = " id='" . $rr . "'";
            $parentDivisionxx = $this->dao->get_division_department($clause);
            $allfilteredDistrict[]= $parentDivisionxx[0];
            }

            $this->beanUi->set_view_data("allfilteredDistrict", $allfilteredDistrict);
            /*******/
        
        $_action = $this->bean->get_request("_action");
        $alldata = array();
        $financial_year= "";
        if( $_action == "Create" ) { 
          
        $financial_year_id_create  = $this->bean->get_request("financial_year");
        $financial_district_id  = $this->bean->get_request("financial_district");
       
        $fData              = $this->dao->allFinancialYear($financial_year_id_create);
        $financial_year     = $fData[0]->financial_year;
         $fromsubmit  = $this->bean->get_request("fromsubmit");
           if($fromsubmit !=""){
                $this->beanUi->set_success_message("Yearly target of <b> $financial_year </b> is successfully saved.");
           }
           
          
           /*******/
            $arr      = array();
            $scoreID  = $this->dao->getReportSettings(1,"id");
            $rowArray = explode(",",$scoreID[0]->param_value);
            foreach ($rowArray as $k => $rr) {
            $clause   = " id='" . $rr . "'";
            $parentDivisionxx = $this->dao->get_division_department($clause);
            $arr[]= $parentDivisionxx[0];
            }
            $this->beanUi->set_view_data("allDistrict", $arr);
            /*******/
            
          
            foreach($arr as $rowsData)
            {
                $alldatagenValue = $this->dao->allscorefromyearview($financial_year_id_create,$financial_district_id,$rowsData->id);
                $pset_value = $cset_value = $total_calculation ="";
               foreach($alldatagenValue as $rowIDs)
               {
                   $pset_value     += $rowIDs->pset_value;
                   $cset_value     += $rowIDs->cset_value;
               }
                $arrData[$rowsData->id]["pset_value"] = $pset_value;
                $arrData[$rowsData->id]["cset_value"] = $cset_value;
//                $arrData[$rowsData->id]["total_calculation"] = $total_calculation;
//                $arrData[$rowsData->id]["activity_type_id"] = $rowsData->id;
//                $arrData[$rowsData->id]["district_id"] = $financial_district_id_create;
//                $arrData[$rowsData->id]["financial_year_id"] = $financial_year_id_create;
            }
            
        
        $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
        $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
        $this->beanUi->set_view_data("alldatagen", $arrData);
        $this->beanUi->set_view_data("alldatadist", $alldatadist);
        $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
        $this->beanUi->set_view_data("financial_year", $financial_year);
        }
      
       
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("alldatagen", $alldatagen);
        $this->beanUi->set_view_data("alldatadist", $alldatadist);
        $this->beanUi->set_view_data("allActivity", $allActivity);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
    }    
     
    public function actualTarget() {
      
        $allFinancialYear = $this->dao->allFinancialYear();
		$financial_year ="";
        /*******/
            $arr      = array();
            $scoreID  = $this->dao->getReportSettings(2,"id");
            $rowArray = explode(",",$scoreID[0]->param_value);
            foreach ($rowArray as $k => $rr) {
            $clause   = " id='" . $rr . "'";
            $parentDivisionxx = $this->dao->get_division_department($clause);
            $arr[]= $parentDivisionxx[0];
            }
            $this->beanUi->set_view_data("allDistrict", $arr);
          
            /*******/
            $_action = $this->bean->get_request("_action");
            if( $_action == "Create" ) { 
                 
                $financial_year_id_create  = $this->bean->get_request("financial_year");
                $financial_district_id_create  = $this->bean->get_request("financial_district");
                
                if($financial_year_id_create){
                $fData              = $this->dao->allFinancialYear($financial_year_id_create);
                $financial_year     = $fData[0]->financial_year; 
                 
                }
                
             
        $allActivity = $this->dao->get_activities();
        $workshop = $commmeeting = $training = $safetyDays = $siteAudit = $incident = $ppeAudit = $safeyObs = $safeyObsLIneFunc = array();
            foreach(getAllMonthYear($financial_year) as $rowdata2)
            {
                
                $month  = date("Y-m",strtotime($rowdata2));
                $mnth   = date("m",strtotime($rowdata2));
                $year   = date("Y",strtotime($rowdata2));
                
               
                $workshop[1][$month][$financial_district_id_create]     = $this->dao->getActualTarget(1,$table="actualtarget_view",$col="activity_date",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);             
                $commmeeting[2][$month][$financial_district_id_create]  = $this->dao->getActualTarget(2,$table="actualtarget_view",$col="activity_date",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);            
                $training[3][$month][$financial_district_id_create]     = $this->dao->getActualTargetTraining(date("Y-m",strtotime($rowdata2)),$financial_district_id_create);            
                $safetyDays[4][$month][$financial_district_id_create]   = $this->dao->getActualTarget(4,$table="actualtarget_view",$col="activity_date",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);              
                $siteAudit[5][$month][$financial_district_id_create]    = $this->dao->getActualTarget(5,$table="actualtarget_audit_view",$col="date_of_audit",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);               
                $incident[6][$month][$financial_district_id_create]     = $this->dao->getActualTarget(6,$table="actualtarget_incident_view",$col="date_of_incident",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);
                $ppeAudit[7][$month][$financial_district_id_create]     = $this->dao->getActualTarget(7,$table="actualtarget_ppeaudit_view",$col="date_of_audit",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);             
                $safeyObs[8][$month][$financial_district_id_create]     = $this->dao->getActualTargetSafeyObs($mnth,$year,$financial_district_id_create);
                
            }
        $this->beanUi->set_view_data("workshop",    $workshop);
        $this->beanUi->set_view_data("commMeeting", $commmeeting);
        $this->beanUi->set_view_data("training",    $training);
        $this->beanUi->set_view_data("safetyDays",  $safetyDays);
        $this->beanUi->set_view_data("siteAudit",   $siteAudit);
        $this->beanUi->set_view_data("incident",    $incident);
        $this->beanUi->set_view_data("ppeAudit",    $ppeAudit);
        $this->beanUi->set_view_data("safeyObs",    $safeyObs);
        $this->beanUi->set_view_data("allActivity", $allActivity);
    
            }
            
        $this->beanUi->set_view_data("financial_year", $financial_year);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        
    }
    
    public function yearlyTargetEntry() {
        $allActivity = $this->dao->get_activities();
        $allFinancialYear = $this->dao->allFinancialYear();
        $_action = $this->bean->get_request("_action");
        $alldata = array();
        $financial_year= "";
		$financial_year_id = "";
		$financial_district_with_pid = "";
		$alldatagen  = "";
        
         /*******/
            $arr      = array();
            $scoreID  = $this->dao->getReportSettings(2,"id");
            $rowArray = explode(",",$scoreID[0]->param_value);
            foreach ($rowArray as $k => $rr) {
            $clause   = " id='" . $rr . "'";
            $parentDivisionxx = $this->dao->get_division_department($clause);
            $arr[]= $parentDivisionxx[0];
            }
            $this->beanUi->set_view_data("allDistrict", $arr);
            /*******/
        
            
            
            
        if( $_action == "Create" ) { 
          
        $financial_year_id_create  = $this->bean->get_request("financial_year");
        $financial_district_with_pid  = $this->bean->get_request("financial_district");
        $financial_parent_id = explode('-',$financial_district_with_pid);
        $financial_district_id_create  = $financial_parent_id[0];
        
        foreach($allActivity as $rowsData)
            {
                
                $alldatagen[$rowsData->id] = $this->dao->alldatafromyear($financial_year_id_create,$financial_district_id_create,$rowsData->id);
            }
       
        $fData              = $this->dao->allFinancialYear($financial_year_id_create);
        $financial_year     = $fData[0]->financial_year;
         $fromsubmit  = $this->bean->get_request("fromsubmit");
           if($fromsubmit !=""){
                $this->beanUi->set_success_message("Yearly target of <b> $financial_year </b> is successfully saved.");
           }
        
        $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
        $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
        $this->beanUi->set_view_data("alldatagen", $alldatagen);
        //$this->beanUi->set_view_data("alldatadist", $alldatadist);
        $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
        $this->beanUi->set_view_data("financial_district_id", $financial_district_with_pid);
        }
        if( $_action == "submitData" ) { 
            $arrayData = array();
			
            $activity_type_id   = $this->bean->get_request("activity_type_id");
            //$type               = $this->bean->get_request("type");
            $financial_year_id  = $this->bean->get_request("financial_year_id");
            $financial_district_with_pid  = $this->bean->get_request("financial_district_id");
            $financial_parent_id = explode('-',$financial_district_with_pid);
            $financial_district_id  = $financial_parent_id[0];
            $financial_parent_id_create  = $financial_parent_id[1];
            
            $fData              = $this->dao->allFinancialYear($financial_year_id);
            $financial_year     = $fData[0]->financial_year;
           
            foreach ($activity_type_id as $key => $value) {
                $monthly_value  = $this->bean->get_request("monthly_value".$value);
                //$secondmonthly_value  = $this->bean->get_request("monthly_value_dist".$value);
                $initial_total  = $this->bean->get_request("initial_total".$value);
                //$initial_total_dist  = $this->bean->get_request("initial_total_dist".$value);
                $arrayData["activity_type_id"]  = $value;
                //$arrayData["type"]              = $type[0];
                $arrayData["financial_year_id"] = $financial_year_id;
                $arrayData["district_id"] = $financial_district_id;
                $arrayData["parent_id"] = $financial_parent_id_create;
                $arrayData["april_month"]       = $monthly_value[0];
                $arrayData["may_month"]         = $monthly_value[1];
                $arrayData["june_month"]        = $monthly_value[2];
                $arrayData["july_month"]        = $monthly_value[3];
                $arrayData["august_month"]      = $monthly_value[4];
                $arrayData["september_month"]   = $monthly_value[5];
                $arrayData["october_month"]     = $monthly_value[6];
                $arrayData["november_month"]    = $monthly_value[7];
                $arrayData["december_month"]    = $monthly_value[8];
                $arrayData["january_month"]     = $monthly_value[9];
                $arrayData["february_month"]    = $monthly_value[10];
                $arrayData["march_month"]       = $monthly_value[11];
                $arrayData["total_calculation"] = $initial_total;
                //show($arrayData);
                $checktargetExist   = $this->dao->checkYearlyTargetExist($value,$financial_district_id,$financial_year_id);
                 if( count($checktargetExist) > 0 )
                {
                    $arrayData["id"]    = $checktargetExist[0]->id;
                    $this->dao->_table = "yearly_target";
                    $insertID = $this->dao->save($arrayData); 
                }
                else
                {
                    $this->dao->_table = "yearly_target";
                    $insertID = $this->dao->save($arrayData); 
                }
            }
            //$this->beanUi->set_success_message("Yearly target of <b> $financial_year </b> is successfully saved.");
            //redirect(page_link("activity/yearlyTargetEntry.php"));
        }
        if($financial_year_id){
        $fData              = $this->dao->allFinancialYear($financial_year_id);
        $financial_year     = $fData[0]->financial_year; 
         $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("financial_district_for_select", $financial_district_with_pid);
        $this->beanUi->set_view_data("alldatagen", $alldatagen);
        //$this->beanUi->set_view_data("alldatadist", $alldatadist);
        $this->beanUi->set_view_data("allActivity", $allActivity);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
    }    
    
    public function scoreTargetEntry() {
        $allActivity = $this->dao->get_activities();
        $allFinancialYear = $this->dao->allFinancialYear();
		$financial_year_id = "";
		$financial_district_with_pid = "";
		$alldatagen = "";
		$financial_year ="";
         /*******/
            $newarr   =  array();
            $scoreID  = $this->dao->getReportSettings(2,"id");
            $rowArray = explode(",",$scoreID[0]->param_value);
            foreach ($rowArray as $k => $rr) {
            $clause   = " id='" . $rr . "'";
            $parentDivisionxx = $this->dao->get_division_department($clause);
            $newarr[]= $parentDivisionxx[0];
            }
//            show($arr);
            $this->beanUi->set_view_data("allDistrict2", $newarr);
            /*******/
        
        
        $_action = $this->bean->get_request("_action");
       
        if( $_action == "Create" ) { 
        $financial_year_id_create  = $this->bean->get_request("financial_year");
        $financial_district_with_pid  = $this->bean->get_request("financial_district");
        $financial_parent_id = explode('-',$financial_district_with_pid);
        $financial_district_id_create  = $financial_parent_id[0];
        
        $fData              = $this->dao->allFinancialYear($financial_year_id_create);
        $financial_year     = $fData[0]->financial_year;
         $fromsubmit  = $this->bean->get_request("fromsubmit");
           if($fromsubmit !=""){
                $this->beanUi->set_success_message("Site audit score target of <b> $financial_year </b> is successfully saved.");
           }
            /*******/
            $arr      = array();
            $scoreIDs  = $this->dao->getReportSettings(1,"id");
            $rowArrays = explode(",",$scoreIDs[0]->param_value);
            foreach ($rowArrays as $k => $rr) {
            $clauses   = " id='" . $rr . "'";
            $parentDivisionxxx = $this->dao->get_division_department($clauses);
            $arr[]= $parentDivisionxxx[0];
            }
            $this->beanUi->set_view_data("allDistrict", $arr);
            /*******/
          
            foreach ( $arr as $rowData )
            {
            $alldatagen[$rowData->id] = $this->dao->allscorefromyear($financial_year_id_create,$financial_district_id_create,$rowData->id);
            }
        $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
        $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
        $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
        $this->beanUi->set_view_data("financial_district_id", $financial_district_with_pid);
        $this->beanUi->set_view_data("alldatagen", $alldatagen);
        }
        
        
           if( $_action == "submitData" ) { 
            $arrayData = array();
            $pset_value          = $this->bean->get_request("pset_value");
            $cset_value          = $this->bean->get_request("cset_value");
            $tree_id            = $this->bean->get_request("tree_id");
            $financial_year_id  = $this->bean->get_request("financial_year_id");
            $financial_district_with_pid  = $this->bean->get_request("financial_district_id");
            $financial_parent_id = explode('-',$financial_district_with_pid);
            $financial_district_id  = $financial_parent_id[0];
            $financial_parent_id_create  = $financial_parent_id[1];
            $financial_year     = $fData[0]->financial_year;
            foreach( $tree_id as $key => $rowdata )
            {
                $secondarrayData["tree_id"]             = $rowdata;
                $secondarrayData["financial_year_id"]   = $financial_year_id;
                $secondarrayData["district_id"]         = $financial_district_id;
                $secondarrayData["parent_id"]           = $financial_parent_id_create;
                $secondarrayData["pset_value"]          = $pset_value[$key];
                $secondarrayData["cset_value"]          = $cset_value[$key];
                $checkscoreExist   = $this->dao->checkscoreExist($rowdata,$financial_year_id,$financial_district_id);
                if( count($checkscoreExist) > 0 )
                {
                    $secondarrayData["id"]    = $checkscoreExist[0]->id;
                    $this->dao->_table        = "site_audit_score_target";
                    $secondinsertID           = $this->dao->save($secondarrayData); 
                }
                else
                {
                    $this->dao->_table  = "site_audit_score_target";
                    $secondinsertID     = $this->dao->save($secondarrayData); 
                }
               
                
            }
           }
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("financial_district_for_select", $financial_district_with_pid);
        $this->beanUi->set_view_data("alldatagen", $alldatagen);
        $this->beanUi->set_view_data("allActivity", $allActivity);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("financialYear", $financial_year);
        
    }    

    public function buildTree($tree) {
        $arrdata = array();
        foreach ($tree as $row) {
            $arrdata[$row->id]= $row;
            
            $clause3 =  " parent_id='".$row->id."'";
            $layer3 =$this->dao->get_division_department($clause3);
            if($layer3)
            {
                $arrdata[$row->id]= $this->buildTree($layer3);
                
            }
        }
        return $arrdata;
    }
    
    public function buildTreeChild($treeid) {
        $showArr = array();
        $clause         = " parent_id=".$treeid;
        $parentDivision = $this->dao->get_division_department($clause);
        if(!empty($parentDivision)) {
        foreach($parentDivision as $row) {
	@$showArr[$row->id]->name = @$row->name;
	@$showArr[$row->id]->parent_id=@$row->parent_id;	
	@$showArr[$row->id]->isChild = @$this->buildTreeChild($row->id);
	
	}
        }
	return $showArr;
    }
    
    public function array_values_recursive($ary)  {
        $lst = array();
        foreach( array_keys($ary) as $k ) {
        $v = $ary[$k];
        $lst[] = $k;
       
            if (is_array($v)) {
            $lst = array_merge($lst,$this->array_values_recursive($v));
            }
        }
       return $lst;
    }  
    
    public function getTotalData($activity_id,$table,$col,$rowid) {
        $clause         = " id IN ($rowid)";
        $parentDivision = $this->dao->get_division_department($clause);
        
        $showArr           = array();
        if(!empty($parentDivision)) {
        foreach ($parentDivision as $result) {
            @$showArr[$result->id]->name = $result->name;
            @$showArr[$result->id]->parent_id=$result->parent_id;
            @$showArr[$result->id]->isChild = $this->buildTreeChild($result->id);            
        }
        }
        $val=0;
        if(!empty($showArr)) {
            foreach ( $showArr as $keys => $rowdata ) { 
                $val+= $this->dao->getMisTreeData($activity_id, $table, $col,$keys);        
                $isChild = $rowdata->isChild;
                if(!empty($isChild)) {
                    foreach( $isChild as $keys2 =>$rowdata2 ) {
                        $val+= $this->dao->getMisTreeData($activity_id, $table, $col,$keys2);
                        $isChild2 = $rowdata2->isChild;
                        if(!empty($isChild2)) {
                            foreach( $isChild2 as $keys3 =>$rowdata3 ) {
                                $val+= $this->dao->getMisTreeData($activity_id, $table, $col,$keys3);
                                $isChild3 = $rowdata3->isChild;
                                if(!empty($isChild3)) {
                                    foreach( $isChild3 as $keys4 =>$rowdata4 ) {
                                        $val+= $this->dao->getMisTreeData($activity_id, $table, $col,$keys4);
                                        $isChild4 = $rowdata4->isChild;
                                        if(!empty($isChild4)) {
                                            foreach( $isChild4 as $keys5 =>$rowdata5 ) {
                                                $val+= $this->dao->getMisTreeData($activity_id, $table, $col,$keys5);
                                                $isChild5 = $rowdata5->isChild;
                                                if(!empty($isChild5)) {
                                                    foreach( $isChild5 as $keys6 =>$rowdata6 ) {
                                                        $val+= $this->dao->getMisTreeData($activity_id, $table, $col,$keys6);
                                                        $isChild6 = $rowdata6->isChild;
                                                        if(!empty($isChild6)) {
                                                            foreach( $isChild6 as $keys7 =>$rowdata7 ) {
                                                                $val+= $this->dao->getMisTreeData($activity_id, $table, $col,$keys7);
                                                                $isChild7 = $rowdata7->isChild;
                                                                if(!empty($isChild7)) {
                                                                    foreach( $isChild7 as $keys8 =>$rowdata8 ) {
                                                                        $val+= $this->dao->getMisTreeData($activity_id, $table, $col,$keys8);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $val;
    }
    
    public function misTree() {
        
       $action = $this->bean->get_request("action");
       $allActivity    = $this->dao->get_activities();
       $newArr     =   array(); 
       if($action == "treesubmit") {
        $showArr = array();
        ini_set('max_execution_time', 0);
        ini_set('max_input_nesting_level', 0);
        ini_set('max_input_nesting_level', 0);
        ini_set('max_input_time', 0);
        ini_set('memory_limit', '500M');
        ini_set('post_max_size', '500M');
        
        $clause         = " id IN (1)";
        $parentDivision = $this->dao->get_division_department($clause);
        if(!empty($parentDivision)) {
        foreach ($parentDivision as $result) {
            @$showArr[$result->id]->name         =   $result->name;
            @$showArr[$result->id]->parent_id    =   $result->parent_id;
            @$showArr[$result->id]->isChild      =   $this->buildTreeChild($result->id);            
        }
        }
        $totalVal   =   0;
        if(!empty($showArr)) {
    foreach ( $showArr as $keys => $rowdata ) { 
	@$newArr[$keys]->name= $rowdata->name;
	@$newArr[$keys]->layer= 1;
	@$newArr[$keys]->parent_id= $rowdata->parent_id;
        @$newArr[$keys]->itemCount->workshopIndividual   =   $this->dao->getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys);
        @$newArr[$keys]->itemCount->commIndividual       =   $this->dao->getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys);
        @$newArr[$keys]->itemCount->trainIndividual      =   $this->dao->getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys);
        @$newArr[$keys]->itemCount->safDayIndividual     =   $this->dao->getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys);
        @$newArr[$keys]->itemCount->auditIndividual      =   $this->dao->getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys);
        @$newArr[$keys]->itemCount->incidentIndividual   =   $this->dao->getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys);
        @$newArr[$keys]->itemCount->ppeauditIndividual   =   $this->dao->getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys);
        @$newArr[$keys]->itemCount->safobsIndividual     =   $this->dao->getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys);
        
        @$newArr[$keys]->itemCount->workshopTotal        =   $this->getTotalData(1,"actualtarget_view","activity_date",$keys);
        @$newArr[$keys]->itemCount->commTotal            =   $this->getTotalData(2,"actualtarget_view","activity_date",$keys);
        @$newArr[$keys]->itemCount->trainTotal           =   $this->getTotalData(3,"actualtarget_training_view","from_date",$keys);
        @$newArr[$keys]->itemCount->safDayTotal          =   $this->getTotalData(4,"actualtarget_view","activity_date",$keys);
        @$newArr[$keys]->itemCount->auditTotal           =   $this->getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys);
        @$newArr[$keys]->itemCount->incidentTotal        =   $this->getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys);
        @$newArr[$keys]->itemCount->ppeauditTotal        =   $this->getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys);
        @$newArr[$keys]->itemCount->safobsTotal        =   $this->getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys);
        
        $isChild = $rowdata->isChild;
	if(!empty($isChild)) {
            foreach( $isChild as $keys2 =>$rowdata2 ) {
                @$newArr[$keys2]->name= $rowdata2->name;
                @$newArr[$keys2]->layer= 2;
                @$newArr[$keys2]->parent_id= $rowdata2->parent_id;
                @$newArr[$keys2]->itemCount->workshopIndividual  =   $this->dao->getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys2);
                @$newArr[$keys2]->itemCount->commIndividual      =   $this->dao->getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys2);
                @$newArr[$keys2]->itemCount->trainIndividual     =   $this->dao->getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys2);
                @$newArr[$keys2]->itemCount->safDayIndividual    =   $this->dao->getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys2);
                @$newArr[$keys2]->itemCount->auditIndividual     =   $this->dao->getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys2);
                @$newArr[$keys2]->itemCount->incidentIndividual  =   $this->dao->getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys2);
                @$newArr[$keys2]->itemCount->ppeauditIndividual  =   $this->dao->getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys2);
                @$newArr[$keys2]->itemCount->safobsIndividual   =   $this->dao->getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys2);
                
                @$newArr[$keys2]->itemCount->workshopTotal       =   $this->getTotalData(1,"actualtarget_view","activity_date",$keys2);
                @$newArr[$keys2]->itemCount->commTotal           =   $this->getTotalData(2,"actualtarget_view","activity_date",$keys2);
                @$newArr[$keys2]->itemCount->trainTotal          =   $this->getTotalData(3,"actualtarget_training_view","from_date",$keys2);
                @$newArr[$keys2]->itemCount->safDayTotal         =   $this->getTotalData(4,"actualtarget_view","activity_date",$keys2);
                @$newArr[$keys2]->itemCount->auditTotal          =   $this->getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys2);
                @$newArr[$keys2]->itemCount->incidentTotal       =   $this->getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys2);
                @$newArr[$keys2]->itemCount->ppeauditTotal       =   $this->getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys2);
                @$newArr[$keys2]->itemCount->safobsTotal        =   $this->getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys2);
                
                $isChild2 = $rowdata2->isChild;
                if(!empty($isChild2)) {
                    foreach( $isChild2 as $keys3 =>$rowdata3 ) {
                        @$newArr[$keys3]->name= $rowdata3->name;
                        @$newArr[$keys3]->layer= 3;
                        @$newArr[$keys3]->parent_id= $rowdata3->parent_id;
                        @$newArr[$keys3]->itemCount->workshopIndividual  =   $this->dao->getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->commIndividual      =   $this->dao->getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->trainIndividual     =   $this->dao->getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys3);
                        @$newArr[$keys3]->itemCount->safDayIndividual    =   $this->dao->getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->auditIndividual     =   $this->dao->getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys3);
                        @$newArr[$keys3]->itemCount->incidentIndividual  =   $this->dao->getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys3);
                        @$newArr[$keys3]->itemCount->ppeauditIndividual  =   $this->dao->getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys3);
                        @$newArr[$keys3]->itemCount->safobsIndividual   =   $this->dao->getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys3);
                        
                        @$newArr[$keys3]->itemCount->workshopTotal       =   $this->getTotalData(1,"actualtarget_view","activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->commTotal           =   $this->getTotalData(2,"actualtarget_view","activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->trainTotal          =   $this->getTotalData(3,"actualtarget_training_view","from_date",$keys3);
                        @$newArr[$keys3]->itemCount->safDayTotal         =   $this->getTotalData(4,"actualtarget_view","activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->auditTotal          =   $this->getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys3);
                        @$newArr[$keys3]->itemCount->incidentTotal       =   $this->getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys3);
                        @$newArr[$keys3]->itemCount->ppeauditTotal       =   $this->getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys3);
                        @$newArr[$keys3]->itemCount->safobsTotal        =   $this->getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys3);
                        
                        $isChild3 = $rowdata3->isChild;
                        if(!empty($isChild3)) {
                            foreach( $isChild3 as $keys4 =>$rowdata4 ) {
                                @$newArr[$keys4]->name= $rowdata4->name;
                                @$newArr[$keys4]->layer= 4;
                                @$newArr[$keys4]->parent_id= $rowdata4->parent_id;
                                @$newArr[$keys4]->itemCount->workshopIndividual  =   $this->dao->getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->commIndividual      =   $this->dao->getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->trainIndividual     =   $this->dao->getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys4);
                                @$newArr[$keys4]->itemCount->safDayIndividual    =   $this->dao->getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->auditIndividual     =   $this->dao->getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys4);
                                @$newArr[$keys4]->itemCount->incidentIndividual  =   $this->dao->getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys4);
                                @$newArr[$keys4]->itemCount->ppeauditIndividual  =   $this->dao->getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys4);
                                @$newArr[$keys4]->itemCount->safobsIndividual   =   $this->dao->getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys4);
                                
                                @$newArr[$keys4]->itemCount->workshopTotal       =   $this->getTotalData(1,"actualtarget_view","activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->commTotal           =   $this->getTotalData(2,"actualtarget_view","activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->trainTotal          =   $this->getTotalData(3,"actualtarget_training_view","from_date",$keys4);
                                @$newArr[$keys4]->itemCount->safDayTotal         =   $this->getTotalData(4,"actualtarget_view","activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->auditTotal          =   $this->getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys4);
                                @$newArr[$keys4]->itemCount->incidentTotal       =   $this->getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys4);
                                @$newArr[$keys4]->itemCount->ppeauditTotal       =   $this->getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys4);
                                @$newArr[$keys4]->itemCount->safobsTotal        =   $this->getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys4);
                                
                                $isChild4 = $rowdata4->isChild;
                                if(!empty($isChild4)) {
                                    foreach( $isChild4 as $keys5 =>$rowdata5 ) {
                                        @$newArr[$keys5]->name= $rowdata5->name;
                                        @$newArr[$keys5]->layer= 5;
                                        @$newArr[$keys5]->parent_id= $rowdata5->parent_id;
                                        @$newArr[$keys5]->itemCount->workshopIndividual  =   $this->dao->getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->commIndividual      =   $this->dao->getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->trainIndividual     =   $this->dao->getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys5);
                                        @$newArr[$keys5]->itemCount->safDayIndividual    =   $this->dao->getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->auditIndividual     =   $this->dao->getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys5);
                                        @$newArr[$keys5]->itemCount->incidentIndividual  =   $this->dao->getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys5);
                                        @$newArr[$keys5]->itemCount->ppeauditIndividual  =   $this->dao->getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys5);
                                        @$newArr[$keys5]->itemCount->safobsIndividual   =   $this->dao->getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys5);
                                        
                                        @$newArr[$keys5]->itemCount->workshopTotal       =   $this->getTotalData(1,"actualtarget_view","activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->commTotal           =   $this->getTotalData(2,"actualtarget_view","activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->trainTotal          =   $this->getTotalData(3,"actualtarget_training_view","from_date",$keys5);
                                        @$newArr[$keys5]->itemCount->safDayTotal         =   $this->getTotalData(4,"actualtarget_view","activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->auditTotal          =   $this->getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys5);
                                        @$newArr[$keys5]->itemCount->incidentTotal       =   $this->getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys5);
                                        @$newArr[$keys5]->itemCount->ppeauditTotal       =   $this->getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys5);
                                        @$newArr[$keys5]->itemCount->safobsTotal        =   $this->getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys5);
                                        
                                        $isChild5 = $rowdata5->isChild;
                                        if(!empty($isChild5)) {
                                            foreach( $isChild5 as $keys6 =>$rowdata6 ) {
                                                @$newArr[$keys6]->name= $rowdata6->name;
                                                @$newArr[$keys6]->layer= 6;
                                                @$newArr[$keys6]->parent_id= $rowdata6->parent_id;
                                                @$newArr[$keys6]->itemCount->workshopIndividual  =   $this->dao->getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys6);                                                
                                                @$newArr[$keys6]->itemCount->commIndividual      =   $this->dao->getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys6);
                                                @$newArr[$keys6]->itemCount->trainIndividual     =   $this->dao->getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys6);
                                                @$newArr[$keys6]->itemCount->safDayIndividual    =   $this->dao->getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys6);
                                                @$newArr[$keys6]->itemCount->auditIndividual     =   $this->dao->getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys6);
                                                @$newArr[$keys6]->itemCount->incidentIndividual  =   $this->dao->getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys6);
                                                @$newArr[$keys6]->itemCount->ppeauditIndividual  =   $this->dao->getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys6);
                                                @$newArr[$keys6]->itemCount->safobsIndividual   =   $this->dao->getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys6);
                                                
                                                @$newArr[$keys6]->itemCount->workshopTotal       =   $this->getTotalData(1,"actualtarget_view","activity_date",$keys6);
                                                @$newArr[$keys6]->itemCount->commTotal           =   $this->getTotalData(2,"actualtarget_view","activity_date",$keys6);
                                                @$newArr[$keys6]->itemCount->trainTotal          =   $this->getTotalData(3,"actualtarget_training_view","from_date",$keys6);
                                                @$newArr[$keys6]->itemCount->safDayTotal         =   $this->getTotalData(4,"actualtarget_view","activity_date",$keys6);
                                                @$newArr[$keys6]->itemCount->auditTotal          =   $this->getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys6);
                                                @$newArr[$keys6]->itemCount->incidentTotal       =   $this->getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys6);
                                                @$newArr[$keys6]->itemCount->ppeauditTotal       =   $this->getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys6);
                                                @$newArr[$keys6]->itemCount->safobsTotal        =   $this->getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys6);
                                            
                                                $isChild6 = $rowdata6->isChild;
                                                if(!empty($isChild6)) {
                                                    foreach( $isChild6 as $keys7 =>$rowdata7 ) {
                                                        @$newArr[$keys7]->name= $rowdata7->name;
                                                        @$newArr[$keys7]->layer= 7;
                                                        @$newArr[$keys7]->parent_id= $rowdata7->parent_id;
                                                        @$newArr[$keys7]->itemCount->workshopIndividual  =   $this->dao->getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys7);                                                
                                                        @$newArr[$keys7]->itemCount->commIndividual      =   $this->dao->getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys7);
                                                        @$newArr[$keys7]->itemCount->trainIndividual     =   $this->dao->getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys7);
                                                        @$newArr[$keys7]->itemCount->safDayIndividual    =   $this->dao->getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys7);
                                                        @$newArr[$keys7]->itemCount->auditIndividual     =   $this->dao->getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys7);
                                                        @$newArr[$keys7]->itemCount->incidentIndividual  =   $this->dao->getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys7);
                                                        @$newArr[$keys7]->itemCount->ppeauditIndividual  =   $this->dao->getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys7);
                                                        @$newArr[$keys7]->itemCount->safobsIndividual   =   $this->dao->getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys7);

                                                        @$newArr[$keys7]->itemCount->workshopTotal       =   $this->getTotalData(1,"actualtarget_view","activity_date",$keys7);
                                                        @$newArr[$keys7]->itemCount->commTotal           =   $this->getTotalData(2,"actualtarget_view","activity_date",$keys7);
                                                        @$newArr[$keys7]->itemCount->trainTotal          =   $this->getTotalData(3,"actualtarget_training_view","from_date",$keys7);
                                                        @$newArr[$keys7]->itemCount->safDayTotal         =   $this->getTotalData(4,"actualtarget_view","activity_date",$keys7);
                                                        @$newArr[$keys7]->itemCount->auditTotal          =   $this->getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys7);
                                                        @$newArr[$keys7]->itemCount->incidentTotal       =   $this->getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys7);
                                                        @$newArr[$keys7]->itemCount->ppeauditTotal       =   $this->getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys7);
                                                        @$newArr[$keys7]->itemCount->safobsTotal        =   $this->getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys7);

                                                        
                                                    }
                                                }
                                            }
                                        }  /**6**/
                                    }
                                } /**5**/
                            }
                        } /**4**/
                    }
                } /**3**/
            }
	} /**2**/
    }
    
    
        }
//die;
//show($newArr);
        
        }
         $this->beanUi->set_view_data("treeDivisionData", $newArr);
         $this->beanUi->set_view_data("allActivity", $allActivity);
         $this->beanUi->set_view_data("actvalue", $action);
        
       
        
    } 
    
    public function misTree2() {
     $treedata= $this->dao->getTreeData();
     $allActivity    = $this->dao->get_activities();
     $this->beanUi->set_view_data("allActivity", $allActivity);
     $this->beanUi->set_view_data("treeDivisionData", $treedata);
 }
    //--------------------Get Month Array List-----------------------------------//
    public function getDatesFromRange($start, $end, $format = 'Y-m') {
    $array = array();
    $interval = new DateInterval('P1M');
    $realEnd = new DateTime($end);
    $realEnd->add($interval);
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
    foreach($period as $date) { 
        $array[] = $date->format($format); 
    }
    return $array;
}

    //--------------------actualActivityTarget New Function for Report-----------------------------------//
    public function actualActivityTarget() {
      
        $allFinancialYear = $this->dao->allFinancialYear();
		$financial_year ="";
        /*******/
            $arr      = array();
            $scoreID  = $this->dao->getReportSettings(3,"id");
            $groupArr = explode('|', $scoreID[0]->param_value);
            if (!empty($groupArr)){
                foreach($groupArr as $k=> $rowdata) {
                    $rowArray = explode("~",$rowdata);
                    $indexdata = $rowArray[0];
                    $data = $rowArray[1];
                    $clause   = " id='" . $indexdata . "'";
                    $parentDivisionxx = $this->dao->get_division_department($clause);                     
                    $arr[] = $parentDivisionxx[0];
                    $arr[$k]->groupid = $data;
                }
            }
            
            $this->beanUi->set_view_data("allDistrict", $arr);
            /*******/
            $_action = $this->bean->get_request("_action");
            if( $_action == "Create" ) { 
                 
                $finalcial_M_Y_from = date("Y-m",strtotime('01-'.$this->bean->get_request("month_year_from")));
                $finalcial_M_Y_to = date("Y-m",strtotime('01-'.$this->bean->get_request("month_year_to")));

                $ArrMomthList = $this->getDatesFromRange( $finalcial_M_Y_from, $finalcial_M_Y_to);
                 
                $financial_district_id_create  = $this->bean->get_request("financial_district");
                
            $allActivity = $this->dao->get_activities();
            $workshop = $commmeeting = $training = $safetyDays = $siteAudit = $incident = $ppeAudit = $safeyObs = $safeyObsLIneFunc = array();
            foreach( $ArrMomthList as $rowdata2)
            {
                
                $month  = date("Y-m",strtotime($rowdata2));
                $mnth   = date("m",strtotime($rowdata2));
                $year   = date("Y",strtotime($rowdata2));

                $workshop[1][$month][$financial_district_id_create]     = $this->dao->getActualTarget(1,$table="actualtarget_view",$col="activity_date",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);             
                $commmeeting[2][$month][$financial_district_id_create]  = $this->dao->getActualTarget(2,$table="actualtarget_view",$col="activity_date",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);            
                $training[3][$month][$financial_district_id_create]     = $this->dao->getActualTargetTraining(date("Y-m",strtotime($rowdata2)),$financial_district_id_create);            
                $safetyDays[4][$month][$financial_district_id_create]   = $this->dao->getActualTarget(4,$table="actualtarget_view",$col="activity_date",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);              
                $siteAudit[5][$month][$financial_district_id_create]    = $this->dao->getActualTarget(5,$table="actualtarget_audit_view",$col="date_of_audit",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);               
                $incident[6][$month][$financial_district_id_create]     = $this->dao->getActualTarget(6,$table="actualtarget_incident_view",$col="date_of_incident",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);
                $ppeAudit[7][$month][$financial_district_id_create]     = $this->dao->getActualTarget(7,$table="actualtarget_ppeaudit_view",$col="date_of_audit",date("Y-m",strtotime($rowdata2)),$financial_district_id_create);             
                $safeyObs[8][$month][$financial_district_id_create]     = $this->dao->getActualTargetSafeyObs($mnth,$year,$financial_district_id_create);

            }
            $this->beanUi->set_view_data("workshop",    $workshop);
            $this->beanUi->set_view_data("commMeeting", $commmeeting);
            $this->beanUi->set_view_data("training",    $training);
            $this->beanUi->set_view_data("safetyDays",  $safetyDays);
            $this->beanUi->set_view_data("siteAudit",   $siteAudit);
            $this->beanUi->set_view_data("incident",    $incident);
            $this->beanUi->set_view_data("ppeAudit",    $ppeAudit);
            $this->beanUi->set_view_data("safeyObs",    $safeyObs);
            $this->beanUi->set_view_data("allActivity", $allActivity);
    
            }
        $this->beanUi->set_view_data("financial_year", @$ArrMomthList);
        //$this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("FinancialYearFromTo", @$finalcial_M_Y_from.",".@$finalcial_M_Y_to);
    }
    
    public function edittargetEntry() {
        $fy_id              =   $this->bean->get_request("fy");
        $mode               =   $this->bean->get_request("mode");
        $fData              = $this->dao->allFinancialYear($fy_id);
        $financial_year     = $fData[0]->financial_year;
        $major_activities_typeone = array();
        $major_activities_typetwo = array();
        $major_activities_typethree = array();
        
        $all_yearly_target_of_one = array();
        $all_yearly_target_of_two = array();
        $all_yearly_target_of_three = array();
        $all_yearly_target  =   array();
        if( $fy_id != "" && $mode == "edit") {
            $all_yearly_target_of_one = $all_yearly_target_of_two = $all_yearly_target_of_three = array();
            $major_activities_typeone       =   $this->dao->get_all_major_activities('1,3');
            $major_activities_typetwo       =   $this->dao->get_all_major_activities(2);
            $major_activities_typethree     =   $this->dao->get_all_major_activities('3,4');
            
            foreach ($major_activities_typeone as $key => $value) {
                $pvalue = array(
                                "financial_year_id" => $fy_id,
                                "major_activity_id" => $value->id,
                                "type"              => 1
                                );
                $all_yearly_target_of_one[$value->id] =   $this->dao->get_all_yearly_target(array("financial_year_id" => ":financial_year_id","major_activity_id" => ":major_activity_id","type" => ":type"),$pvalue);
            }
            foreach ($major_activities_typetwo as $key => $value) {
                $pvalue1 = array(
                                "financial_year_id" => $fy_id,
                                "major_activity_id" => $value->id,
                                "type"              => 2
                                );
                $all_yearly_target_of_two[$value->id] =   $this->dao->get_all_yearly_target(array("financial_year_id" => $fy_id,"major_activity_id" => $value->id,"type" => 2),$pvalue1);
            }
            foreach ($major_activities_typethree as $key => $value) {
                 $pvalue2 = array(
                                "financial_year_id" => $fy_id,
                                "major_activity_id" => $value->id,
                                "type"              => 3
                                );
                $all_yearly_target_of_three[$value->id] =   $this->dao->get_all_yearly_target(array("financial_year_id" => $fy_id,"major_activity_id" => $value->id,"type" => 3),$pvalue2);
            }
        }
        $_action = $this->bean->get_request("_action");
        if( $_action == "submitData" ) {
             $financial_year_id  = $this->bean->get_request("financial_year_id");
            
           
            $type_1        =   $this->bean->get_request("type_1");
            $type_2        =   $this->bean->get_request("type_2");
            $type_3        =   $this->bean->get_request("type_3");
            $oneArr = $twoArr = $threeArr = array();
            foreach( $type_1 as $key => $value ) {
                $target     =   $this->bean->get_request("target_".$value);
                $rowid      =   $this->bean->get_request("row_id_1".$value);
                
                $oneArr[$key]["id"]                 =   $rowid;
                $oneArr[$key]["major_activity_id"]  =   $value;
                $oneArr[$key]["financial_year_id"]  =   $financial_year_id;
                $oneArr[$key]["type"]               =   1;               
                $oneArr[$key]["target"]             =   $target;               
                $this->dao->_table  =   "mis_yearly_target";
                $this->dao->save($oneArr[$key]);
            }
            foreach( $type_2 as $key => $value ) {
                $pset_value     =   $this->bean->get_request("pset_value_".$value);
                $cset_value     =   $this->bean->get_request("cset_value_".$value);
                $pcset_value     =   $this->bean->get_request("pcset_value_".$value);
                $others_value     =   $this->bean->get_request("others_value_".$value);
                $rowid      =   $this->bean->get_request("row_id_2".$value);
                
                $twoArr[$key]["id"]                 =   $rowid;
                $twoArr[$key]["major_activity_id"]  =   $value;
                $twoArr[$key]["financial_year_id"]  =   $financial_year_id;
                $twoArr[$key]["type"]               =   2;
                $twoArr[$key]["pset_target"]        =   $pset_value;               
                $twoArr[$key]["cset_target"]        =   $cset_value;               
                $twoArr[$key]["pcset_target"]        =   $pcset_value;               
                $twoArr[$key]["others_target"]        =   $others_value;               
                $this->dao->_table  =   "mis_yearly_target";
                $this->dao->save($twoArr[$key]);
            }
            foreach( $type_3 as $key => $value ) {
                $gen_value     =   $this->bean->get_request("gen_value_".$value);
                $dist_value     =   $this->bean->get_request("dist_value_".$value);
                $rowid      =   $this->bean->get_request("row_id_3".$value);
                
                $threeArr[$key]["id"]                   =   $rowid;
                $threeArr[$key]["major_activity_id"]    =   $value;
                $threeArr[$key]["financial_year_id"]    =   $financial_year_id;
                $threeArr[$key]["type"]                 =   3;
                $threeArr[$key]["gen"]                  =   $gen_value;               
                $threeArr[$key]["dist"]                 =   $dist_value;               
                $this->dao->_table  =   "mis_yearly_target";
                $this->dao->save($threeArr[$key]);
            }
            redirect(page_link("activity/listtargetEntry.php"));
        }
        
        $this->beanUi->set_view_data("major_activities_typeone", $major_activities_typeone);
        $this->beanUi->set_view_data("major_activities_typetwo", $major_activities_typetwo);
        $this->beanUi->set_view_data("major_activities_typethree", $major_activities_typethree);
        
        $this->beanUi->set_view_data("all_yearly_target_of_one", $all_yearly_target_of_one);
        $this->beanUi->set_view_data("all_yearly_target_of_two", $all_yearly_target_of_two);
        $this->beanUi->set_view_data("all_yearly_target_of_three", $all_yearly_target_of_three);
        $this->beanUi->set_view_data("financial_year", $financial_year);
        $this->beanUi->set_view_data("financial_year_id", $fy_id);
    }
    
    public function targetEntry() {
        $allFinancialYear = $this->dao->allFinancialYear();         
        $_action = $this->bean->get_request("_action");
        $all_major_activities = array();
        $financial_year = "";
        $financial_year_id_create ="";
        $major_activities_typeone = $major_activities_typetwo = $major_activities_typethree = "";
        if( $_action == "Create" ) { 
            $financial_year_id_create   =   $this->bean->get_request("financial_year");
            $major_activities_typeone       =   $this->dao->get_all_major_activities('1,3');
            $major_activities_typetwo       =   $this->dao->get_all_major_activities(2);
            $major_activities_typethree     =   $this->dao->get_all_major_activities('3,4');
            $fData              = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year     = $fData[0]->financial_year;
            $yearly_target =   $this->dao->get_all_yearly_target(array("financial_year_id" => $financial_year_id_create));
           
            if( count($yearly_target) > 0 ) {
                redirect(page_link("activity/edittargetEntry.php?fy=$financial_year_id_create&mode=edit"));                
            }
        }
        if( $_action == "submitData" ) {
            $financial_year_id  = $this->bean->get_request("financial_year_id");
            
           
            $type_1        =   $this->bean->get_request("type_1");
            $type_2        =   $this->bean->get_request("type_2");
            $type_3        =   $this->bean->get_request("type_3");
            $oneArr = $twoArr = $threeArr = array();
            foreach( $type_1 as $key => $value ) {
                $target     =   $this->bean->get_request("target_".$value);
                
                $oneArr[$key]["major_activity_id"]  =   $value;
                $oneArr[$key]["financial_year_id"]  =   $financial_year_id;
                $oneArr[$key]["type"]               =   1;               
                $oneArr[$key]["target"]             =   $target;               
                $this->dao->_table  =   "mis_yearly_target";
                $this->dao->save($oneArr[$key]);
            }
            foreach( $type_2 as $key => $value ) {
                $pset_value     =   $this->bean->get_request("pset_value_".$value);
                $cset_value     =   $this->bean->get_request("cset_value_".$value);
                $pcset_value     =   $this->bean->get_request("pcset_value_".$value);
                $others_value     =   $this->bean->get_request("others_value_".$value);
                
                $twoArr[$key]["major_activity_id"]  =   $value;
                $twoArr[$key]["financial_year_id"]  =   $financial_year_id;
                $twoArr[$key]["type"]               =   2;
                $twoArr[$key]["pset_target"]        =   $pset_value;               
                $twoArr[$key]["cset_target"]        =   $cset_value;               
                $twoArr[$key]["pcset_target"]        =   $pcset_value;               
                $twoArr[$key]["others_target"]        =   $others_value;               
                $this->dao->_table  =   "mis_yearly_target";
                $this->dao->save($twoArr[$key]);
            }
            foreach( $type_3 as $key => $value ) {
                $gen_value     =   $this->bean->get_request("gen_value_".$value);
                $dist_value     =   $this->bean->get_request("dist_value_".$value);
                
                $threeArr[$key]["major_activity_id"]    =   $value;
                $threeArr[$key]["financial_year_id"]    =   $financial_year_id;
                $threeArr[$key]["type"]                 =   3;
                $threeArr[$key]["gen"]                  =   $gen_value;               
                $threeArr[$key]["dist"]                 =   $dist_value;               
                $this->dao->_table  =   "mis_yearly_target";
                $this->dao->save($threeArr[$key]);
            }
        }
        
        
        $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
        $this->beanUi->set_view_data("financialYear", $financial_year);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("major_activities_typeone", $major_activities_typeone);
        $this->beanUi->set_view_data("major_activities_typetwo", $major_activities_typetwo);
        $this->beanUi->set_view_data("major_activities_typethree", $major_activities_typethree);
        
        
        
        
    }  
 
    public function listtargetEntry() {
        $allFinancialYear   =   $this->dao->allFinancialYear();  
        
        $yearly_target = array();
        foreach ($allFinancialYear as $key => $value) {
            @$yearly_target =   $this->dao->get_all_yearly_target(array("financial_year_id" => $value->id));
            $allFinancialYear[$key]->dataCount = count($yearly_target);
        }
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
    }
    
    public function incident_report() {
        $_action            =   $this->bean->get_request("_action");
        $allFinancialYear   =   $this->dao->allFinancialYear();
        $aclause = " id NOT IN(9,10)";
        $allactivity   =   $this->dao->get_activity_type_master($aclause);
        $tableArr = array( 
            1 => 'actualtarget_view', 
            2 => 'actualtarget_view', 
            3 => 'actualtarget_view', 
            4 => 'actualtarget_view',
            5 => 'actualtarget_audit_view',
            6 => 'actualtarget_incident_view',
            7 => 'actualtarget_ppeaudit_view',
            8 => 'actualtarget_safetyobs_view'
            );
        $colArr = array( 
            1 => 'activity_date', 
            2 => 'activity_date', 
            3 => 'from_date', 
            4 => 'activity_date',
            5 => 'date_of_audit',
            6 => 'date_of_incident',
            7 => 'date_of_audit',
            8 => ''
            );
        $comArr = array();
        if( !empty( $allactivity ) ) {
            foreach ($allactivity as $key => $value) {
                @$comArr[$value->id]->name       =   $value->activity_name;
                @$comArr[$value->id]->tablename  =   $tableArr[$value->id];
                @$comArr[$value->id]->colname    =   $colArr[$value->id];
            }
        }
       $activity_name ="";
        if( $_action == "Search" ) {
            @$fy         =   $this->bean->get_request("fy"); // selected FY
            @$acttypeid  =   $this->bean->get_request("activity_type_id"); // selected FY
            @$fyexp      =   explode("-",$fy);
            @$first      =   date($fyexp[0]) - 1;
            @$second     =   date($fyexp[1]) - 1;
            @$pre_fy     =   $first.'-'.$second; // previous FY
            @$reportdata =   $this->dao->getReportSettings(14,"id");
            @$rowArray   =   explode("~",$reportdata[0]->param_value);
            
            $activity_name = $comArr[$acttypeid]->name;
            $tablename = $comArr[$acttypeid]->tablename;
            $colname = $comArr[$acttypeid]->colname;
            
            $fetchArr = array();
            $allfy = array($fy,$pre_fy);
            if( !empty( $allfy ) ) {
                 $fdata  =   array();
                foreach ( $allfy as $kfy => $vfy ) {
                    
                    @$fdata[$vfy]=   getAllMonthYear($vfy);
                    @$fromdate   =   $fdata[0].'-01';
                    @$todate     =   $fdata[11].'-31';
                    @$daterange  =   " '$fromdate' AND '$todate' ";
                    
                    if( !empty( $rowArray ) ) {
                        foreach( $rowArray as $key => $value ) {
                            $getlastid      =   explode("-",$value);
                            $tid            =   end($getlastid);
                            $clause         =   " id IN (" . $tid . ")";
                            $division_name  =   $this->dao->get_division_department($clause);
                            $exp    =   explode(",",$tid);
                            $prefixed_array = preg_filter('/^/', '249-3-5-', $exp);
                            @$treeid = ((count($division_name) > 1 ) ? $prefixed_array :  (array) $value ); 
                            @$fetchArr[$vfy][$key]["rowname"]  =  ((count($division_name) > 1 ) ? " 10 District" : $division_name[0]->name );  
                            @$fetchArr[$vfy][$key]["treeid"]   =  $treeid;   
                            if( !empty( $fdata[$vfy] ) ) {
                                foreach( $fdata[$vfy] as $k => $v ) {
                                    $fetchArr[$vfy][$key]["month"][$k]["thismonth"] = $v;
                                    $fetchArr[$vfy][$key]["month"][$k]["thismonth_data"] = $this->dao->activitydataforall($acttypeid,$tablename,$colname,$v,$treeid);
                                }
                            }
                        }  
                    }
                    
                }
            }
        }
        @$this->beanUi->set_view_data("fetchArr", $fetchArr);
        @$this->beanUi->set_view_data("financial_year", $fdata);
        @$this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        @$this->beanUi->set_view_data("allactivity", $allactivity);
        @$this->beanUi->set_view_data("activity_name", $activity_name);
    }
    
    public function safetyCellInternalEntry(){
        
    }
    
    public function listsafetyCellInternal(){
        
    }
    
    public function safetyCellInternalEdit(){
        
    }
    
    public function SAGen_TargetEntry(){
        $allActivity = $this->dao->get_activities();
        $allFinancialYear = $this->dao->allFinancialYear(); 
        $allContractor = $this->dao->get_contractor();   
        $financial_year_id = "";
        $financial_district_id_create = "";
        $financial_year_id_create = "";
        $alldatagen = "";
        $_action = $this->bean->get_request("_action"); //show($_action);
            
        $alldata = array();
        $financial_year= "";
        if( $_action == "Create" ) {
          
            $financial_year_id_create  = $this->bean->get_request("financial_year");
            @$financial_district_id_create  = $this->bean->get_request("financial_district");

            $fData              = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year     = $fData[0]->financial_year;
            $fromsubmit  = $this->bean->get_request("fromsubmit");
            if($fromsubmit !=""){
                $this->beanUi->set_success_message("Yearly target of <b> $financial_year </b> is successfully saved.");
            }

            $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
            $this->beanUi->set_view_data("financial_district_id", $financial_district_id_create);
            $this->beanUi->set_view_data("alldatagen", @$arrData);
            $this->beanUi->set_view_data("financial_year", $financial_year);
        } 
        
        $getsagentarget = $this->dao->get_sagentarget($financial_year_id_create);
        
        if( $_action == "Input" ) {  die("abcd");
            $data = $this->bean->get_request("data");  
            $financial_year_id = $this->bean->get_request("financial_year_id");
            
            foreach ($data as $key => $val) {
                $newdata =  explode("-", $key);
                $insertdata['cset_contractor_id'] = $newdata[0];
                $insertdata['month_id'] = $newdata[1];
                $insertdata['financial_year_id'] = $financial_year_id;
                $insertdata['target'] = $val;
                $this->dao->_table  = "annual_target_gen_site_audit";
                if($insertdata != ''){
                   $this->dao->save($insertdata);
                }
            }
        } else if($_action == "Update") { 
            $i = 0;
            $data = $this->bean->get_request("data"); 
            $id = $this->bean->get_request("id"); 
            $financial_year_id = $this->bean->get_request("financial_year_id");

           foreach ($data as $key => $value) { 
               $newdata =  explode("-", $key); 
                $insertdata['id'] = $id; 
                $insertdata['cset_contractor_id'] = $newdata[0];
                $insertdata['month_id'] = $newdata[1];
                $insertdata['financial_year_id'] = $financial_year_id;
                $insertdata['target'] = $value;
                $this->dao->_table  =   "annual_target_gen_site_audit";
                if($insertdata != ''){
                   $this->dao->save($insertdata);
                }
               $id++; 
           }      
        }
        
        if($getsagentarget) { 
            $array = array();
            $array1 = array();
            foreach($getAllMonthofFinancialYear as $k => $v){
                foreach($getsagentarget as $key => $val ){
                    if($getsagentarget[$key]->month_id == ($k + 1)) {
                        $array[$getsagentarget[$key]->cset_contractor_id][$getsagentarget[$key]->month_id] = $val->target;
                        $array1[] = $val->id;
                    }
                }
            } 
            $id = $array1[0];
            $this->beanUi->set_view_data("array", $array);
            $this->beanUi->set_view_data("id", $id);
        }
      
        if($financial_year_id){
             $fData              = $this->dao->allFinancialYear($financial_year_id);
             $financial_year     = $fData[0]->financial_year; 
             $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("financial_district_for_select", $financial_district_id_create);
        $this->beanUi->set_view_data("allActivity", $allActivity);
        $this->beanUi->set_view_data("alldatagen", $alldatagen);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("allContractor", $allContractor);
        
    }
    
    public function SAGen_TargetView(){
        // For displaying the financial year    
        $allActivity            = $this->dao->get_activities();
        $allFinancialYear       = $this->dao->allFinancialYear();
        $allContractor          = $this->dao->get_contractor(); 
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        
        $financial_year_id = "";
        $financial_district_id_create = "";
        $alldatagen = "";
        $getAllMonthofFinancialYear = "";
        $financial_year_id_create = "";
        
        $_action = $this->bean->get_request("_action");
        
        $array = array();
        $financial_year= "";
        if( $_action == "Create" ) {
          
            $financial_year_id_create  = $this->bean->get_request("financial_year");
            $financial_district_id_create  = $this->bean->get_request("financial_district");

            $fData              = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year     = $fData[0]->financial_year;
            $fromsubmit  = $this->bean->get_request("fromsubmit");
            if($fromsubmit !=""){
                $this->beanUi->set_success_message("Yearly target of <b> $financial_year </b> is successfully saved.");
            }

            $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
            $this->beanUi->set_view_data("financial_district_id", $financial_district_id_create);
            $this->beanUi->set_view_data("alldatagen", @$arrData);
            $this->beanUi->set_view_data("financial_year", $financial_year);
        } 
        
        $getsagentarget = $this->dao->get_sagentarget($financial_year_id_create); 
        
        foreach($getAllMonthofFinancialYear as $k => $v){
            foreach($getsagentarget as $key => $val ){
                if($getsagentarget[$key]->month_id == ($k + 1)) {
                    $array[$getsagentarget[$key]->cset_contractor_id][$getsagentarget[$key]->month_id] = $val->target;
                }
            }
        } 
        $this->beanUi->set_view_data("array", $array);
        
        if($financial_year_id){
             $fData              = $this->dao->allFinancialYear($financial_year_id);
             $financial_year     = $fData[0]->financial_year; 
             $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("financial_district_for_select", $financial_district_id_create);
        $this->beanUi->set_view_data("allActivity", $allActivity);
        $this->beanUi->set_view_data("alldatagen", $alldatagen);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("allContractor", $allContractor);
    }

    public function SADist_TargetEntry(){
        $allActivity            = $this->dao->get_activities();
        $allFinancialYear       = $this->dao->allFinancialYear();
        
        $financial_year_id = "";
        $financial_district_id_create = "";
        $financial_year_id_create = "";
        $alldatagen = "";
        $_action = $this->bean->get_request("_action");
        $_action1 = $this->bean->get_request("_action1");
        
        $arr      = array();
        $scoreID                = $this->dao->getReportSettings(18,"id");
        $rowArray                   = explode(",",$scoreID[0]->param_value);
        foreach ($rowArray as $k => $rr) {
        $clause   = " id='" . $rr . "'";
        $parentDivisionxx = $this->dao->get_division_department($clause);
        $arr[]= $parentDivisionxx[0];
        }

        $this->beanUi->set_view_data("allDistrict", $arr);
            
            
        $alldata = array();
        $financial_year= "";
        if( $_action == "Create" ) { 
          
            @$financial_year_id_create  = $this->bean->get_request("financial_year");
            $financial_district_id_create  = $this->bean->get_request("financial_district");
            foreach($allActivity as $rowsData) {
                    $alldatagenValue = $this->dao->alldatafromyearview($financial_year_id_create,$financial_district_id_create,$rowsData->id);
                    $april_month = $may_month = $june_month = $july_month = $august_month = $september_month = $october_month = $november_month = $december_month = $january_month = $february_month = $march_month = $total_calculation ="";
                }

            $fData              = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year     = $fData[0]->financial_year;
            $fromsubmit  = $this->bean->get_request("fromsubmit");
               if($fromsubmit !=""){
                    $this->beanUi->set_success_message("Yearly target of <b> $financial_year </b> is successfully saved.");
               }

            $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("alldatagen", @$arrData);
            $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
            $this->beanUi->set_view_data("financial_district_id", $financial_district_id_create);
        }
        $getsadisttarget = $this->dao->get_sadisttarget($financial_year_id_create);
      
        if( $_action1 == "Save" ) { 
             $data = $this->bean->get_request("data"); 
             $financial_year_id = $this->bean->get_request("financial_year_id"); 
            
               foreach ($data as $key => $val) {
                 $newdata =  explode("-", $key);
                 $insertdata['set_type'] = $newdata[0];
                 $insertdata['month_id'] = $newdata[1];
                 $insertdata['div_dept_id'] = $newdata[2];
                 $insertdata['financial_year_id'] = $financial_year_id;
                 $insertdata['target_value'] = $val;
                  
                   $this->dao->_table  =   "annual_target_dist_site_audit";
              
                 if($insertdata != ''){
                 $this->dao->save($insertdata);
                 
                 }
                   
               } 
            
        }else if($_action == "Update") { 
            $data = $this->bean->get_request("data"); 
            $id = $this->bean->get_request("id"); 
            $financial_year_id = $this->bean->get_request("financial_year_id");

           foreach ($data as $key => $value) { 
               $newdata =  explode("-", $key); 
                $insertdata['id'] = $id; 
                $insertdata['set_type'] = $newdata[0];
                $insertdata['month_id'] = $newdata[2];
                $insertdata['div_dept_id'] = $newdata[1];
                $insertdata['financial_year_id'] = $financial_year_id;
                $insertdata['target_value'] = $value;
                $this->dao->_table  =   "annual_target_dist_site_audit";
                if($insertdata != ''){
                   $this->dao->save($insertdata);
                }
               $id++;
           }      
        }
        
         if($getsadisttarget) {
            $arr1 = array("P", "C");
            $array = array();
            $array1 = array();
            foreach($getAllMonthofFinancialYear as $k => $v){
                foreach($getsadisttarget as $key => $val ){
                    if($getsadisttarget[$key]->month_id == ($k + 1)) {
                        $array[$k + 1][$getsadisttarget[$key]->div_dept_id][$getsadisttarget[$key]->set_type] = $val->target_value;
                        $array1[] = $val->id;
                    }
                }
            } 
            $id = $array1[0];
            $this->beanUi->set_view_data("array", $array);
            $this->beanUi->set_view_data("id", $id);
            $this->beanUi->set_view_data("arr1", $arr1);
        }
        
        if($financial_year_id){
        $fData              = $this->dao->allFinancialYear($financial_year_id);
        $financial_year     = $fData[0]->financial_year; 
         $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("financial_district_for_select", $financial_district_id_create);
        $this->beanUi->set_view_data("alldatagen", $alldatagen);
        //$this->beanUi->set_view_data("alldatadist", $alldatadist);
        $this->beanUi->set_view_data("allActivity", $allActivity);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("financial_year", $financial_year);
        
    }
    
    public function SADist_TargetView(){
        // For displaying the financial year 
        $allActivity            = $this->dao->get_activities();
        $allFinancialYear       = $this->dao->allFinancialYear();
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        
        $financial_year_id_create = "";
        $getAllMonthofFinancialYear = array();
        $getsadisttarget = "";
        $_action = $this->bean->get_request("_action");
        
        //for displaying the state values
        $arr      = array();
        $scoreID                = $this->dao->getReportSettings(18,"id");
        $rowArray                   = explode(",",$scoreID[0]->param_value);
        foreach ($rowArray as $k => $rr) {
        $clause   = " id='" . $rr . "'";
        $parentDivisionxx = $this->dao->get_division_department($clause);
        $arr[]= $parentDivisionxx[0];
        }
        $this->beanUi->set_view_data("allDistrict", $arr);
            
        $arr1 = array("P", "C");
        $array = array();
        $financial_year= "";
        if( $_action == "Create" ) { 
             // For displaying the months 
            $financial_year_id_create  = $this->bean->get_request("financial_year");
            $fData              = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year     = $fData[0]->financial_year;
            $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
        }
        $getsadisttarget = $this->dao->get_sadisttarget($financial_year_id_create); 
        
        foreach($getAllMonthofFinancialYear as $k => $v){
            foreach($getsadisttarget as $key => $val ){
                if($getsadisttarget[$key]->month_id == ($k + 1)) {
                    $array[$k + 1][$getsadisttarget[$key]->div_dept_id][$getsadisttarget[$key]->set_type] = $val->target_value;
                }
            }
        } 
        $this->beanUi->set_view_data("array", $array);
        $this->beanUi->set_view_data("arr1", $arr1);
        $this->beanUi->set_view_data("financial_year", $financial_year);
    }
    
    public function PPEA_TargetEntry(){
         // For displaying the financial year    
        $allActivity            = $this->dao->get_activities();
        $allFinancialYear       = $this->dao->allFinancialYear();
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        
        $financial_year_id = "";
        $financial_district_id_create = "";
        
        $_action = $this->bean->get_request("_action");  
        
        //for displaying the th values
        $arr      = array();
        $scoreID                = $this->dao->getReportSettings(18,"id");
        $rowArray                   = explode(",",$scoreID[0]->param_value);
        foreach ($rowArray as $k => $rr) {
            $clause   = " id='" . $rr . "'";
            $parentDivisionxx = $this->dao->get_division_department($clause);
            $arr[]= $parentDivisionxx[0];
        }
//            show($arr);
        $this->beanUi->set_view_data("allDistrict", $arr);
        
        $financial_year= "";
        $financial_year_id_create = "";
        
        if( $_action == "Create" ) { 
            
            // For displaying the months 
            $financial_year_id_create  = $this->bean->get_request("financial_year"); 
            $financial_district_id_create  = $this->bean->get_request("financial_district");
            foreach($allActivity as $rowsData)
            {
                $alldatagenValue = $this->dao->alldatafromyearview($financial_year_id_create,$financial_district_id_create,$rowsData->id);
                $april_month = $may_month = $june_month = $july_month = $august_month = $september_month = $october_month = $november_month = $december_month = $january_month = $february_month = $march_month = $total_calculation ="";
            }  
            $fData              = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year     = $fData[0]->financial_year;
            $fromsubmit  = $this->bean->get_request("fromsubmit");
           if($fromsubmit !=""){
                $this->beanUi->set_success_message("Yearly target of <b> $financial_year </b> is successfully saved.");
           }
            $getAllMonthofFinancialYear = getAllMonthYear($financial_year); 
            $this->beanUi->set_view_data("alldatagen", @$arrData);
            $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
            $this->beanUi->set_view_data("financial_district_id", $financial_district_id_create);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("financial_year", $financial_year);
        } 
        $months = $this->bean->get_request("activity_type_id"); 
        $getppeaudit = $this->dao->get_allppeaudit($financial_year_id_create); 
        
        if( $_action == "Input" ) { 
            $data = $this->bean->get_request("data");  //show($data);
            $financial_year_id = $this->bean->get_request("financial_year_id");

           foreach ($data as $key => $val) {  
               $newdata =  explode("-", $key);
                $insertdata['div_id'] = $newdata[0];
                $insertdata['set_type'] = $newdata[1];
                $insertdata['month_id'] = $newdata[3];
                $insertdata['div_dept_id'] = $newdata[2];
                $insertdata['financial_year_id'] = $financial_year_id;
                $insertdata['target_value'] = $val;
                  $this->dao->_table  =   "annual_target_ppe_audit";
                if($insertdata != ''){
                   $this->dao->save($insertdata);
                }
           }      
        } else if($_action == "Update") { 
            $i = 0;
            $data = $this->bean->get_request("data"); 
            $id = $this->bean->get_request("id"); 
            $financial_year_id = $this->bean->get_request("financial_year_id");

           foreach ($data as $key => $value) { 
               $newdata =  explode("-", $key); 
                $insertdata['id'] = $id; 
                $insertdata['div_id'] = $newdata[0];
                $insertdata['set_type'] = $newdata[1];
                $insertdata['month_id'] = $newdata[3];
                $insertdata['div_dept_id'] = $newdata[2];
                $insertdata['financial_year_id'] = $financial_year_id;
                $insertdata['target_value'] = $value;
                $this->dao->_table  =   "annual_target_ppe_audit";
                if($insertdata != ''){
                   $this->dao->save($insertdata);
                }
               $id++; 
           }      
        }
        if($getppeaudit) {
            $arr1 = array("P", "C");
            $array = array();
            $array1 = array();
            foreach($getAllMonthofFinancialYear as $k => $v){
                foreach($getppeaudit as $key => $val ){
                    if($getppeaudit[$key]->month_id == ($k + 1)) {
                        $array[$getppeaudit[$key]->div_id][$k + 1][$getppeaudit[$key]->div_dept_id][$getppeaudit[$key]->set_type] = $val->target_value;
                        $array1[] = $val->id;
                    }
                }
            } 
            $id = $array1[0];
            $this->beanUi->set_view_data("array", $array);
            $this->beanUi->set_view_data("id", $id);
            $this->beanUi->set_view_data("arr1", $arr1);
        }
        
        if($financial_year_id){
            $fData              = $this->dao->allFinancialYear($financial_year_id);
            $financial_year     = $fData[0]->financial_year; 
            $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("financial_district_for_select", $financial_district_id_create);
        $this->beanUi->set_view_data("alldatagen", @$alldatagen);
        $this->beanUi->set_view_data("allActivity", $allActivity);
    }
    
    public function PPEA_TargetView(){
        // For displaying the financial year    
        $allActivity            = $this->dao->get_activities();
        $allFinancialYear       = $this->dao->allFinancialYear();
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        
        $financial_year_id_create = "";
        $getAllMonthofFinancialYear = array();
        $_action = $this->bean->get_request("_action");
        
        //for displaying the th values
        $arr      = array();
        $scoreID                = $this->dao->getReportSettings(18,"id");
        $rowArray                   = explode(",",$scoreID[0]->param_value);
        foreach ($rowArray as $k => $rr) {
        $clause   = " id='" . $rr . "'";
        $parentDivisionxx = $this->dao->get_division_department($clause);
        $arr[]= $parentDivisionxx[0];
        }
        $this->beanUi->set_view_data("allDistrict", $arr);
        
        $arr1 = array("P", "C");
        $array = array();
        $financial_year= ""; 
        if( $_action == "Create" ) { 
            // For displaying the months 
            $financial_year_id_create  = $this->bean->get_request("financial_year"); 
            $fData              = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year     = $fData[0]->financial_year;
            $getAllMonthofFinancialYear = getAllMonthYear($financial_year); 
            $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("financial_year", $financial_year);
        } 
        $months = $this->bean->get_request("activity_type_id"); 
        
        $getppeaudit = $this->dao->get_allppeaudit($financial_year_id_create); 
        
        foreach($getAllMonthofFinancialYear as $k => $v){
            foreach($getppeaudit as $key => $val ){
                if($getppeaudit[$key]->month_id == ($k + 1)) {
                    $array[$getppeaudit[$key]->div_id][$k + 1][$getppeaudit[$key]->div_dept_id][$getppeaudit[$key]->set_type] = $val->target_value;
                }
            }
        } 
        $this->beanUi->set_view_data("array", $array);
        $this->beanUi->set_view_data("arr1", $arr1);
    }
    
    public function HH_TargetEntry(){
         // For displaying the financial year    
        $allActivity            = $this->dao->get_activities();
        $allFinancialYear       = $this->dao->allFinancialYear();
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        
        $financial_year_id = "";
        $financial_district_id_create = "";
        
        $_action = $this->bean->get_request("_action");  
        
        //for displaying the th values
        $arr      = array();
        $scoreID                = $this->dao->getReportSettings(18,"id");
        $rowArray                   = explode(",",$scoreID[0]->param_value);
        foreach ($rowArray as $k => $rr) {
            $clause   = " id='" . $rr . "'";
            $parentDivisionxx = $this->dao->get_division_department($clause);
            $arr[]= $parentDivisionxx[0];
        }
        $this->beanUi->set_view_data("allDistrict", $arr);
        
        $financial_year= "";
        
        if( $_action == "Create" ) { 
            
            // For displaying the months 
            $financial_year_id_create  = $this->bean->get_request("financial_year"); 
            $financial_district_id_create  = $this->bean->get_request("financial_district");
            foreach($allActivity as $rowsData)
            {
                $alldatagenValue = $this->dao->alldatafromyearview($financial_year_id_create,$financial_district_id_create,$rowsData->id);
                $april_month = $may_month = $june_month = $july_month = $august_month = $september_month = $october_month = $november_month = $december_month = $january_month = $february_month = $march_month = $total_calculation ="";
            }  
            $fData              = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year     = $fData[0]->financial_year;
            $fromsubmit  = $this->bean->get_request("fromsubmit");
           if($fromsubmit !=""){
                $this->beanUi->set_success_message("Yearly target of <b> $financial_year </b> is successfully saved.");
           }
            $getAllMonthofFinancialYear = getAllMonthYear($financial_year); 
            $this->beanUi->set_view_data("alldatagen", @$arrData);
            $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
            $this->beanUi->set_view_data("financial_district_id", $financial_district_id_create);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        
        $gethandholding = $this->dao->get_handholding(@$financial_year_id_create);
        
        if( $_action == "Input" ) { 
            $data = $this->bean->get_request("data");  //show($data);
            $financial_year_id = $this->bean->get_request("financial_year_id");

           foreach ($data as $key => $val) {  
               $newdata =  explode("-", $key);
               if($newdata[0] == "H") {
                    $insertdata['set_type'] = $newdata[0];
                    $insertdata['div_dept_id'] = $newdata[1];
                    $insertdata['month_id'] = $newdata[2];
                    $insertdata['financial_year_id'] = $financial_year_id;
                    $insertdata['hand_holding'] = $val;
                    $insertdata['target_value'] = " ";
               } else {
                    $insertdata['set_type'] = $newdata[0];
                    $insertdata['div_dept_id'] = $newdata[1];
                    $insertdata['month_id'] = $newdata[2];
                    $insertdata['financial_year_id'] = $financial_year_id;
                    $insertdata['hand_holding'] = 0;
                    $insertdata['target_value'] = $val;
               }
                
                $this->dao->_table  =   "annual_target_hand_holding";
                if($insertdata != ''){
                   $this->dao->save($insertdata);
                }
           }      
        } else if($_action == "Update") { 
            $i = 0;
            $data = $this->bean->get_request("data"); 
            $id = $this->bean->get_request("id"); 
            $financial_year_id = $this->bean->get_request("financial_year_id");

           foreach ($data as $key => $value) { 
               $newdata =  explode("-", $key); 
               if($newdata[0] == "H") {
                    $insertdata['id'] = $id;
                    $insertdata['set_type'] = $newdata[0];
                    $insertdata['div_dept_id'] = $newdata[1];
                    $insertdata['month_id'] = $newdata[2];
                    $insertdata['financial_year_id'] = $financial_year_id;
                    $insertdata['hand_holding'] = $value;
                    $insertdata['target_value'] = " ";
               } else {
                    $insertdata['id'] = $id;
                    $insertdata['set_type'] = $newdata[0];
                    $insertdata['div_dept_id'] = $newdata[1];
                    $insertdata['month_id'] = $newdata[2];
                    $insertdata['financial_year_id'] = $financial_year_id;
                    $insertdata['hand_holding'] = 0;
                    $insertdata['target_value'] = $value;
               }
                
                $this->dao->_table  =   "annual_target_hand_holding";
                if($insertdata != ''){
                   $this->dao->save($insertdata);
                }
               $id++; 
           }      
        }
        
        if($gethandholding) {
            $arr1 = array("H", "P", "C");
            $array = array();
            $array1 = array();
            foreach($getAllMonthofFinancialYear as $k => $v){
                foreach($gethandholding as $key => $val ){
                    if($gethandholding[$key]->month_id == ($k + 1)) {
                        if($gethandholding[$key]->set_type == 'H') {
                            $array[$k + 1][$gethandholding[$key]->div_dept_id][$gethandholding[$key]->set_type] = $val->hand_holding;
                        } else {
                            $array[$k + 1][$gethandholding[$key]->div_dept_id][$gethandholding[$key]->set_type] = $val->target_value;
                        }
                        $array1[] = $val->id;
                    }
                }
            } 
            $id = $array1[0]; 
            $this->beanUi->set_view_data("array", $array);
            $this->beanUi->set_view_data("id", $id);
            $this->beanUi->set_view_data("arr1", $arr1);
        }
        
        if($financial_year_id){
            $fData              = $this->dao->allFinancialYear($financial_year_id);
            $financial_year     = $fData[0]->financial_year; 
            $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("financial_district_for_select", $financial_district_id_create);
        $this->beanUi->set_view_data("alldatagen", @$alldatagen);
        $this->beanUi->set_view_data("allActivity", $allActivity);
    }
    public function HH_TargetView(){
        // For displaying the financial year    
        $allActivity            = $this->dao->get_activities();
        $allFinancialYear       = $this->dao->allFinancialYear();
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        
        $financial_year_id_create = "";
        $getAllMonthofFinancialYear = array();
        $_action = $this->bean->get_request("_action");
        
        //for displaying the th values
        $arr      = array();
        $scoreID                = $this->dao->getReportSettings(18,"id");
        $rowArray                   = explode(",",$scoreID[0]->param_value);
        foreach ($rowArray as $k => $rr) {
        $clause   = " id='" . $rr . "'";
        $parentDivisionxx = $this->dao->get_division_department($clause);
        $arr[]= $parentDivisionxx[0];
        }
        $this->beanUi->set_view_data("allDistrict", $arr);
        
        $arr1 = array("H", "P", "C");
        $array = array();
        $financial_year= ""; 
        if( $_action == "Create" ) { 
            // For displaying the months 
            $financial_year_id_create  = $this->bean->get_request("financial_year"); 
            $fData              = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year     = $fData[0]->financial_year;
            $getAllMonthofFinancialYear = getAllMonthYear($financial_year); 
            $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("financial_year", $financial_year);
        } 
        $months = $this->bean->get_request("activity_type_id"); 
        
        $gethandholding = $this->dao->get_handholding($financial_year_id_create); 
        
        foreach($getAllMonthofFinancialYear as $k => $v){
            foreach($gethandholding as $key => $val ){
                if($gethandholding[$key]->month_id == ($k + 1)) {
                    if($gethandholding[$key]->set_type == 'H') {
                        $array[$k + 1][$gethandholding[$key]->div_dept_id][$gethandholding[$key]->set_type] = $val->hand_holding;
                    } else {
                        $array[$k + 1][$gethandholding[$key]->div_dept_id][$gethandholding[$key]->set_type] = $val->target_value;
                    }
                }
            }
        }
        $this->beanUi->set_view_data("array", $array);
        $this->beanUi->set_view_data("arr1", $arr1);
    }

    public function TA_SO_SD_TargetEntry(){
        $allFinancialYear       = $this->dao->allFinancialYear();
        $allFinancialYearById   = $this->dao->allFinancialYearById();
        $_action = $this->bean->get_request("_action");
        
        if( $_action == "Search" ) {
            $financial_year = $this->bean->get_request("financial_year");
            $annual_target = $this->dao->get_all_annual_target($financial_year);
            $this->beanUi->set_view_data("annual_target", $annual_target);
        }
        
        if( $_action == "Update" ) { 
            
            $financial_year = $this->bean->get_request("fyear");
            $activity_type_id = $this->bean->get_request("activity_type_id");
            $getAllMonthofFinancialYear = getAllMonthYear($allFinancialYearById[$financial_year]);
            foreach ($getAllMonthofFinancialYear as $key => $value){
                
                $gen["financial_year_id"]  = $this->bean->get_request("fyear");
                $gen["month_id"]  = $activity_type_id[$key];
                $gen["div_id"]  = $this->bean->get_request("gstation".$key);
                $gen["officer"]  = $this->bean->get_request("gofficer".$key);
                $gen["pr_workman"]  = $this->bean->get_request("gworkmanp".$key);
                $gen["pr_supervisor"]  = $this->bean->get_request("gsupervisorp".$key);
                $gen["pr_total"]  = $this->bean->get_request("gtotalp".$key);
                $gen["co_workman"]  = $this->bean->get_request("gworkmanc".$key);
                $gen["co_supervisor"]  = $this->bean->get_request("gsupervisorc".$key);
                $gen["co_total"]  = $this->bean->get_request("gtotalc".$key);
                $gen["div_target_lf"]  = $this->bean->get_request("gsoblf".$key);
                $gen["div_target_day"]  = $this->bean->get_request("gsod".$key);
                $gen["div_target_work"]  = $this->bean->get_request("gsw".$key);
                $gen["id"]  = $this->bean->get_request("growid".$key);
                

                $dist["financial_year_id"]  = $this->bean->get_request("fyear");
                $dist["month_id"]  = $activity_type_id[$key];
                $dist["div_id"]  = $this->bean->get_request("dstation".$key);
                $dist["officer"]  = $this->bean->get_request("dofficer".$key);
                $dist["pr_workman"]  = $this->bean->get_request("dworkmanp".$key);
                $dist["pr_supervisor"]  = $this->bean->get_request("dsupervisorp".$key);
                $dist["pr_total"]  = $this->bean->get_request("dtotalp".$key);
                $dist["co_workman"]  = $this->bean->get_request("dworkmanc".$key);
                $dist["co_supervisor"]  = $this->bean->get_request("dsupervisorc".$key);
                $dist["co_total"]  = $this->bean->get_request("dtotalc".$key);
                $dist["div_target_lf"]  = $this->bean->get_request("dsoblf".$key);
                $dist["div_target_day"]  = $this->bean->get_request("dsod".$key);
                $dist["div_target_work"]  = $this->bean->get_request("dsw".$key);
                $dist["id"]  = $this->bean->get_request("drowid".$key);

                $this->dao->_table  =   "annual_target_tr_so_sa_ws";
                $this->dao->save($gen);
                $this->dao->save($dist); 
            }
        }
        
        if( $_action == "Save" ) { 
            
            $financial_year = $this->bean->get_request("fyear");
            $activity_type_id = $this->bean->get_request("activity_type_id");
            $getAllMonthofFinancialYear = getAllMonthYear($allFinancialYearById[$financial_year]);
            foreach ($getAllMonthofFinancialYear as $key => $value){
                
                $gen["financial_year_id"]  = $this->bean->get_request("fyear");
                $gen["month_id"]  = $activity_type_id[$key];
                $gen["div_id"]  = $this->bean->get_request("gstation".$key);
                $gen["officer"]  = $this->bean->get_request("gofficer".$key);
                $gen["pr_workman"]  = $this->bean->get_request("gworkmanp".$key);
                $gen["pr_supervisor"]  = $this->bean->get_request("gsupervisorp".$key);
                $gen["pr_total"]  = $this->bean->get_request("gtotalp".$key);
                $gen["co_workman"]  = $this->bean->get_request("gworkmanc".$key);
                $gen["co_supervisor"]  = $this->bean->get_request("gsupervisorc".$key);
                $gen["co_total"]  = $this->bean->get_request("gtotalc".$key);
                $gen["div_target_lf"]  = $this->bean->get_request("gsoblf".$key);
                $gen["div_target_day"]  = $this->bean->get_request("gsod".$key);
                $gen["div_target_work"]  = $this->bean->get_request("gsw".$key);

                $dist["financial_year_id"]  = $this->bean->get_request("fyear");
                $dist["month_id"]  = $activity_type_id[$key];
                $dist["div_id"]  = $this->bean->get_request("dstation".$key);
                $dist["officer"]  = $this->bean->get_request("dofficer".$key);
                $dist["pr_workman"]  = $this->bean->get_request("dworkmanp".$key);
                $dist["pr_supervisor"]  = $this->bean->get_request("dsupervisorp".$key);
                $dist["pr_total"]  = $this->bean->get_request("dtotalp".$key);
                $dist["co_workman"]  = $this->bean->get_request("dworkmanc".$key);
                $dist["co_supervisor"]  = $this->bean->get_request("dsupervisorc".$key);
                $dist["co_total"]  = $this->bean->get_request("dtotalc".$key);
                $dist["div_target_lf"]  = $this->bean->get_request("dsoblf".$key);
                $dist["div_target_day"]  = $this->bean->get_request("dsod".$key);
                $dist["div_target_work"]  = $this->bean->get_request("dsw".$key);

                $this->dao->_table  =   "annual_target_tr_so_sa_ws";
                $this->dao->save($gen);
                $this->dao->save($dist);
            }
        }
        if(@$financial_year){
            $getAllMonthofFinancialYear = getAllMonthYear($allFinancialYearById[$financial_year]);
            $annual_target = $this->dao->get_all_annual_target($financial_year);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("annual_target", $annual_target);
            $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("allFinancialYearById", $allFinancialYearById);
        
    }
    
    public function TA_SO_SD_TargetView(){
        $allFinancialYear       = $this->dao->allFinancialYear();
        $allFinancialYearById   = $this->dao->allFinancialYearById();
        $_action = $this->bean->get_request("_action");
        
        if( $_action == "Search" ) {
            $financial_year = $this->bean->get_request("financial_year");
            $annual_target = $this->dao->get_all_annual_target($financial_year);
            $this->beanUi->set_view_data("annual_target", $annual_target);
        }
        
        if($financial_year){
            $getAllMonthofFinancialYear = getAllMonthYear($allFinancialYearById[$financial_year]);
            $annual_target = $this->dao->get_all_annual_target($financial_year);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("annual_target", $annual_target);
            $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("allFinancialYearById", $allFinancialYearById);
        
    }
//    pallab
    public function Final_TargetEntry(){
        $allActivity = $this->dao->get_activities();
        $allFinancialYear = $this->dao->allFinancialYear();
        $allAnnualActivity = $this->dao->get_annual_activity();
        
        $financial_year_id = "";
        $financial_district_id_create = "";
        $financial_year_id_create = "";
        $alldatagen = "";
        
        $_action = $this->bean->get_request("_action");
            
        $alldata = array();
        $financial_year= "";
        
        if( $_action == "Create" ) {  
            $financial_year_id_create  = $this->bean->get_request("financial_year");
            $financial_district_id_create  = $this->bean->get_request("financial_district");

            $fData              = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year     = $fData[0]->financial_year;
            $fromsubmit  = $this->bean->get_request("fromsubmit");
            if($fromsubmit !=""){
                $this->beanUi->set_success_message("Yearly target of <b> $financial_year </b> is successfully saved.");
            }

            $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
            $this->beanUi->set_view_data("financial_year_id", $financial_year_id_create);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        
        $sad = array();
        $hh = array();
        $ppegp = array();
        $ppegc = array();
        $ppedp = array();
        $ppedc = array();
        $trwsdist = array();
        $trwsgen = array();
        $trgenc = array();
        
        $getsadview = $this->dao->get_sad_view($financial_year_id_create); 
        $gethhview = $this->dao->get_hh_view($financial_year_id_create); 
        $getppedistcview = $this->dao->get_ppe_dist_c_view($financial_year_id_create); 
        $getppedistpview = $this->dao->get_ppe_dist_p_view($financial_year_id_create); 
        $getppegencview = $this->dao->get_ppe_gen_c_view($financial_year_id_create); 
        $getppegenpview = $this->dao->get_ppe_gen_p_view($financial_year_id_create); 
        $gettrwsdistview = $this->dao->get_tr_ws_dist_view($financial_year_id_create); 
        $gettrwsgenview = $this->dao->get_tr_ws_gen_view($financial_year_id_create); 
        $gettrgencview = $this->dao->get_tr_gen_c_view($financial_year_id_create); 
        
        foreach($getsadview as $key => $value) { 
            if($key % 2 == 0) {
                if($getsadview[$key]->month_id == $getsadview[$key+1]->month_id) {
                    $totalval = $getsadview[$key]->total + $getsadview[$key+1]->total;
                    $sad[$getsadview[$key]->month_id] = $totalval;
                } 
            }
        } 
        foreach($gethhview as $key => $value) { 
            if($key % 2 == 0) {
                if($gethhview[$key]->month_id == $gethhview[$key+1]->month_id) {
                    $totalval = $gethhview[$key]->total + $gethhview[$key+1]->total;
                    $hh[$gethhview[$key]->month_id] = $totalval;
                } 
            }
        } 
        foreach($getppedistcview as $key => $value) { 
            $ppedc[$getppedistcview[$key]->month_id] = $getppedistcview[$key]->total;
        } 
        foreach($getppedistpview as $key => $value) { 
            $ppedp[$getppedistpview[$key]->month_id] = $getppedistpview[$key]->total;
        } 
        foreach($getppegencview as $key => $value) { 
            $ppegc[$getppegencview[$key]->month_id] = $getppegencview[$key]->total;
        } 
        foreach($getppegenpview as $key => $value) { 
            $ppegp[$getppegenpview[$key]->month_id] = $getppegenpview[$key]->total;
        } 
        foreach($gettrwsdistview as $key => $value) { 
            $trwsdist[$gettrwsdistview[$key]->month_id] = $gettrwsdistview[$key]->total; 
        } 
        foreach($gettrwsgenview as $key => $value) { 
            $trwsgen[$gettrwsgenview[$key]->month_id] = $gettrwsgenview[$key]->total;
        } 
        foreach($gettrgencview as $key => $value) { 
            $trgenc[$gettrgencview[$key]->month_id] = $gettrgencview[$key]->total;
        } 
        
        $this->beanUi->set_view_data("sad", $sad);
        $this->beanUi->set_view_data("hh", $hh);
        $this->beanUi->set_view_data("ppedc", $ppedc);
        $this->beanUi->set_view_data("ppedp", $ppedp);
        $this->beanUi->set_view_data("ppegc", $ppegc);
        $this->beanUi->set_view_data("ppegp", $ppegp);
        $this->beanUi->set_view_data("trwsdist", $trwsdist);
        $this->beanUi->set_view_data("trwsgen", $trwsgen);
        $this->beanUi->set_view_data("trgenc", $trgenc);
        
        $getfinaltarget = $this->dao->get_finaltarget($financial_year_id_create); 
        
        if( $_action == "Save" ) {
            $data = $this->bean->get_request("data");  //show($data);
            $financial_year_id = $this->bean->get_request("financial_year_id"); 
            
            foreach ($data as $key => $val) {
                $newdata =  explode("-", $key);
                $insertdata['div_id'] = $newdata[0];
                $insertdata['activity_id'] = $newdata[1];
                $insertdata['month_id'] = $newdata[2];
                $insertdata['financial_year_id'] = $financial_year_id;
                $insertdata['target_value'] = $val;
                $this->dao->_table  = "annual_activity_target";
                if($insertdata != ''){
                   $this->dao->save($insertdata);
                }
            }
        } else if($_action == "Update") { 
            $i = 0;
            $data = $this->bean->get_request("data"); 
            $id = $this->bean->get_request("id"); 
            $financial_year_id = $this->bean->get_request("financial_year_id");

           foreach ($data as $key => $value) { 
               $newdata =  explode("-", $key); 
                $insertdata['id'] = $id; 
                $insertdata['div_id'] = $newdata[0];
                $insertdata['activity_id'] = $newdata[1];
                $insertdata['month_id'] = $newdata[2];
                $insertdata['financial_year_id'] = $financial_year_id;
                $insertdata['target_value'] = $value;
                $this->dao->_table  =   "annual_activity_target";
                if($insertdata != ''){
                   $this->dao->save($insertdata);
                }
               $id++; 
           }      
        }
        
        if($getfinaltarget) { 
            $array = array();
            $array1 = array();
            foreach($getAllMonthofFinancialYear as $k => $v){
                foreach($getfinaltarget as $key => $val ){
                    if($getfinaltarget[$key]->month_id == ($k + 1)) {
                        $array[$getfinaltarget[$key]->activity_id][$getfinaltarget[$key]->month_id] = $val->target_value;
                        $array1[] = $val->id;
                    }
                }
            } 
            $id = $array1[0];
            $this->beanUi->set_view_data("array", $array);
            $this->beanUi->set_view_data("id", $id);
        }
      
        if($financial_year_id){
        $fData              = $this->dao->allFinancialYear($financial_year_id);
        $financial_year     = $fData[0]->financial_year; 
         $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("financial_district_for_select", $financial_district_id_create);
        $this->beanUi->set_view_data("alldatagen", $alldatagen);
        $this->beanUi->set_view_data("allActivity", $allActivity);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("allAnnualActivity", $allAnnualActivity);
        
    }
    public function Final_TargetView(){
        $allActivity = $this->dao->get_activities();
        $allFinancialYear = $this->dao->allFinancialYear();
        $allAnnualActivity = $this->dao->get_annual_activity();
        
        $financial_year_id = "";
        $financial_district_id_create = "";
        $financial_year_id_create = "";
        $getAllMonthofFinancialYear = array();
        $alldatagen = "";
        
        $_action = $this->bean->get_request("_action");
            
        $array = array();
        $financial_year= "";
        if( $_action == "Create" ) {
            $financial_year_id_create  = $this->bean->get_request("financial_year");
    //        $financial_district_id_create  = $this->bean->get_request("financial_district");


            $fData              = $this->dao->allFinancialYear($financial_year_id_create);
            $financial_year     = $fData[0]->financial_year;

            $getAllMonthofFinancialYear = getAllMonthYear($financial_year);
            $this->beanUi->set_view_data("getAllMonthofFinancialYear", $getAllMonthofFinancialYear);
            $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        
        $sad = array();
        $hh = array();
        $ppegp = array();
        $ppegc = array();
        $ppedp = array();
        $ppedc = array();
        $trwsdist = array();
        $trwsgen = array();
        $trgenc = array();
        
        $getsadview = $this->dao->get_sad_view($financial_year_id_create); 
        $gethhview = $this->dao->get_hh_view($financial_year_id_create); 
        $getppedistcview = $this->dao->get_ppe_dist_c_view($financial_year_id_create); 
        $getppedistpview = $this->dao->get_ppe_dist_p_view($financial_year_id_create); 
        $getppegencview = $this->dao->get_ppe_gen_c_view($financial_year_id_create); 
        $getppegenpview = $this->dao->get_ppe_gen_p_view($financial_year_id_create); 
        $gettrwsdistview = $this->dao->get_tr_ws_dist_view($financial_year_id_create); 
        $gettrwsgenview = $this->dao->get_tr_ws_gen_view($financial_year_id_create); 
        $gettrgencview = $this->dao->get_tr_gen_c_view($financial_year_id_create); 
        
        foreach($getsadview as $key => $value) { 
            if($key % 2 == 0) {
                if($getsadview[$key]->month_id == $getsadview[$key+1]->month_id) {
                    $totalval = $getsadview[$key]->total + $getsadview[$key+1]->total;
                    $sad[$getsadview[$key]->month_id] = $totalval;
                } 
            }
        } 
        foreach($gethhview as $key => $value) { 
            if($key % 2 == 0) {
                if($gethhview[$key]->month_id == $gethhview[$key+1]->month_id) {
                    $totalval = $gethhview[$key]->total + $gethhview[$key+1]->total;
                    $hh[$gethhview[$key]->month_id] = $totalval;
                } 
            }
        } 
        foreach($getppedistcview as $key => $value) { 
            $ppedc[$getppedistcview[$key]->month_id] = $getppedistcview[$key]->total;
        } 
        foreach($getppedistpview as $key => $value) { 
            $ppedp[$getppedistpview[$key]->month_id] = $getppedistpview[$key]->total;
        } 
        foreach($getppegencview as $key => $value) { 
            $ppegc[$getppegencview[$key]->month_id] = $getppegencview[$key]->total;
        } 
        foreach($getppegenpview as $key => $value) { 
            $ppegp[$getppegenpview[$key]->month_id] = $getppegenpview[$key]->total;
        } 
        foreach($gettrwsdistview as $key => $value) { 
            $trwsdist[$gettrwsdistview[$key]->month_id] = $gettrwsdistview[$key]->total; 
        } 
        foreach($gettrwsgenview as $key => $value) { 
            $trwsgen[$gettrwsgenview[$key]->month_id] = $gettrwsgenview[$key]->total;
        } 
        foreach($gettrgencview as $key => $value) { 
            $trgenc[$gettrgencview[$key]->month_id] = $gettrgencview[$key]->total;
        } 
        
        $this->beanUi->set_view_data("sad", $sad);
        $this->beanUi->set_view_data("hh", $hh);
        $this->beanUi->set_view_data("ppedc", $ppedc);
        $this->beanUi->set_view_data("ppedp", $ppedp);
        $this->beanUi->set_view_data("ppegc", $ppegc);
        $this->beanUi->set_view_data("ppegp", $ppegp);
        $this->beanUi->set_view_data("trwsdist", $trwsdist);
        $this->beanUi->set_view_data("trwsgen", $trwsgen);
        $this->beanUi->set_view_data("trgenc", $trgenc);
        
        $getfinaltarget = $this->dao->get_finaltarget($financial_year_id_create); 
        
         foreach($getAllMonthofFinancialYear as $k => $v){
            foreach($getfinaltarget as $key => $val ){
                if($getfinaltarget[$key]->month_id == ($k + 1)) {
                    $array[$getfinaltarget[$key]->activity_id][$getfinaltarget[$key]->month_id] = $val->target_value;
                }
            }
        } 
        $this->beanUi->set_view_data("array", $array);
      
        if($financial_year_id){
        $fData              = $this->dao->allFinancialYear($financial_year_id);
        $financial_year     = $fData[0]->financial_year; 
         $this->beanUi->set_view_data("financial_year", $financial_year);
        }
        $this->beanUi->set_view_data("financial_year_for_select", $financial_year_id);
        $this->beanUi->set_view_data("financial_district_for_select", $financial_district_id_create);
        $this->beanUi->set_view_data("alldatagen", $alldatagen);
        $this->beanUi->set_view_data("allActivity", $allActivity);
        $this->beanUi->set_view_data("allFinancialYear", $allFinancialYear);
        $this->beanUi->set_view_data("allAnnualActivity", $allAnnualActivity);
        
    }
}
