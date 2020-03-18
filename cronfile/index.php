<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );

function getChild($parentID) {
	$query2=mysql_query("select * from division_department where parent_id=".$parentID);
	$rowCount2 = mysql_num_rows($query2);
	for($j=0;$j<$rowCount2;$j++) {
	$result2=mysql_fetch_object($query2);
	$showArr[$result2->id]->name = $result2->name;
	$showArr[$result2->id]->parent_id=$result2->parent_id;
	$showArr[$result2->id]->isChild = getChild($result2->id);
	
	}
	return $showArr;
}

function getMisTreeData( $activity_type_id, $table, $col,$treedid) {
        if( $activity_type_id == 8 ) {
        $clause = " WHERE activity_type_id=" . $activity_type_id . " AND activity_year!='0000' AND activity_month!='0' AND created_by !='2' AND deleted='0' ";
        } else if( $activity_type_id == 3 ) {
        $clause     = " WHERE activity_type_id=" . $activity_type_id . " AND type='P' AND deleted='0' AND created_by!='2' AND " . $col . "!='0000-00-00'";
        } else {
        $clause     = " WHERE activity_type_id=" . $activity_type_id . " AND deleted='0' AND created_by!=2 AND " . $col . "!='0000-00-00'";
        }
        $query      = "SELECT * FROM " . $table . " " . $clause;
        $res    = mysql_query($query);
        $rowCount2 = mysql_num_rows($res);
        
        
        $totaldiv = $totalgen = $distribution = $generation = $countvalue = $distribution1 = $distribution2= 0;
        if ($rowCount2 > 0) {
            for($j=0;$j<$rowCount2;$j++) {
				$row =mysql_fetch_object($res);
          $nm="";
                $tree_division_id_arr = ($row->tree_division_id != "") ? explode("-", $row->tree_division_id) : array();
                $totaldiv = $totalgen = 0;
                if (!empty($tree_division_id_arr)) {
                    for ($i = 0; $i < count($tree_division_id_arr); $i++) {
                        if ($tree_division_id_arr[$i] == "")
                            continue;
                        if (is_numeric($tree_division_id_arr[$i])) {
                        $nm.= $tree_division_id_arr[$i]."-";
                        }
                    }
                    $asd=substr($nm,'0','-1');
                   $exp=explode("-",$asd);
                   $newval = end($exp);
                   if($newval == $treedid) {
                      $totaldiv+= 1; 
                   }
                }
                if( $activity_type_id == 3 ) {
                    $countvalue = $row->no_of_participants;
                } else if( $activity_type_id == 8 ) {
                    $countvalue = $row->activity_count;
                }
                else {
                    $countvalue =1;
                }
                if ($totaldiv == 1) {
                    $distribution1 += $countvalue;
                }
            }
        }
      $distribution=$distribution1+$distribution2;
        return ($distribution == 0) ? 0 : $distribution;
    } 

function getTotalData($activity_id,$table,$col,$rowid) {
       
        $query2=mysql_query("select * from division_department where id IN ($rowid)");
		$rowCount2 = mysql_num_rows($query2);
        $showArr           = array();
        for($j=0;$j<$rowCount2;$j++) {
			$result2=mysql_fetch_object($query2);
            @$showArr[$result2->id]->name = $result2->name;
            @$showArr[$result2->id]->parent_id=$result2->parent_id;
            @$showArr[$result2->id]->isChild = getChild($result2->id);            
      
        }
        $val=0;
        if(!empty($showArr)) {
            foreach ( $showArr as $keys => $rowdata ) { 
                $val+= getMisTreeData($activity_id, $table, $col,$keys);        
                $isChild = $rowdata->isChild;
                if(!empty($isChild)) {
                    foreach( $isChild as $keys2 =>$rowdata2 ) {
                        $val+= getMisTreeData($activity_id, $table, $col,$keys2);
                        $isChild2 = $rowdata2->isChild;
                        if(!empty($isChild2)) {
                            foreach( $isChild2 as $keys3 =>$rowdata3 ) {
                                $val+= getMisTreeData($activity_id, $table, $col,$keys3);
                                $isChild3 = $rowdata3->isChild;
                                if(!empty($isChild3)) {
                                    foreach( $isChild3 as $keys4 =>$rowdata4 ) {
                                        $val+= getMisTreeData($activity_id, $table, $col,$keys4);
                                        $isChild4 = $rowdata4->isChild;
                                        if(!empty($isChild4)) {
                                            foreach( $isChild4 as $keys5 =>$rowdata5 ) {
                                                $val+= getMisTreeData($activity_id, $table, $col,$keys5);
                                                $isChild5 = $rowdata5->isChild;
                                                if(!empty($isChild5)) {
                                                    foreach( $isChild5 as $keys6 =>$rowdata6 ) {
                                                        $val+= getMisTreeData($activity_id, $table, $col,$keys6);
                                                        $isChild6 = $rowdata6->isChild;
                                                        if(!empty($isChild6)) {
                                                            foreach( $isChild6 as $keys7 =>$rowdata7 ) {
                                                                $val+= getMisTreeData($activity_id, $table, $col,$keys7);
                                                                $isChild7 = $rowdata7->isChild;
                                                                if(!empty($isChild7)) {
                                                                    foreach( $isChild7 as $keys8 =>$rowdata8 ) {
                                                                        $val+= getMisTreeData($activity_id, $table, $col,$keys8);
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





$query=mysql_query("select * from division_department where id =1");
$rowCount = mysql_num_rows($query);
$showArr=array();
for($i=0;$i<$rowCount;$i++) {
	$result=mysql_fetch_object($query);
	$showArr[$result->id]->name = $result->name;
	$showArr[$result->id]->parent_id=$result->parent_id;
	$showArr[$result->id]->isChild = getChild($result->id);
}
echo "<pre>";
//print_r($showArr);

 ini_set('max_execution_time', 0);
        ini_set('max_input_nesting_level', 0);
        ini_set('max_input_nesting_level', 0);
        ini_set('max_input_time', 0);
        ini_set('memory_limit', '500M');
        ini_set('post_max_size', '500M');


$newArr = array();
foreach ( $showArr as $keys => $rowdata ) {
	$newArr[$keys]->name		= $rowdata->name;
	$newArr[$keys]->division_id	= $keys;
	$newArr[$keys]->layer		= 1;
	$newArr[$keys]->parent_id	= $rowdata->parent_id;
	@$newArr[$keys]->itemCount->workshopIndividual   =   getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys);
        @$newArr[$keys]->itemCount->commIndividual       =   getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys);
        @$newArr[$keys]->itemCount->trainIndividual      =   getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys);
        @$newArr[$keys]->itemCount->safDayIndividual     =   getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys);
        @$newArr[$keys]->itemCount->auditIndividual      =   getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys);
        @$newArr[$keys]->itemCount->incidentIndividual   =   getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys);
        @$newArr[$keys]->itemCount->ppeauditIndividual   =   getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys);
        @$newArr[$keys]->itemCount->safobsIndividual     =   getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys);	
	@$newArr[$keys]->itemCount->workshopTotal        =   getTotalData(1,"actualtarget_view","activity_date",$keys);
        @$newArr[$keys]->itemCount->commTotal            =   getTotalData(2,"actualtarget_view","activity_date",$keys);
        @$newArr[$keys]->itemCount->trainTotal           =   getTotalData(3,"actualtarget_training_view","from_date",$keys);
        @$newArr[$keys]->itemCount->safDayTotal          =   getTotalData(4,"actualtarget_view","activity_date",$keys);
        @$newArr[$keys]->itemCount->auditTotal           =   getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys);
        @$newArr[$keys]->itemCount->incidentTotal        =   getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys);
        @$newArr[$keys]->itemCount->ppeauditTotal        =   getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys);
        @$newArr[$keys]->itemCount->safobsTotal        	 =   getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys);
	
	$isChild = $rowdata->isChild;
	if(!empty($isChild)) {
		foreach( $isChild as $keys2 =>$rowdata2 ) {
			$newArr[$keys2]->name		= $rowdata2->name;
                        $newArr[$keys2]->division_id	= $keys2;
			$newArr[$keys2]->layer		= 2;
			$newArr[$keys2]->parent_id	= $rowdata2->parent_id;
		@$newArr[$keys2]->itemCount->workshopIndividual  =   getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys2);
                @$newArr[$keys2]->itemCount->commIndividual      =   getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys2);
                @$newArr[$keys2]->itemCount->trainIndividual     =   getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys2);
                @$newArr[$keys2]->itemCount->safDayIndividual    =   getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys2);
                @$newArr[$keys2]->itemCount->auditIndividual     =   getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys2);
                @$newArr[$keys2]->itemCount->incidentIndividual  =   getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys2);
                @$newArr[$keys2]->itemCount->ppeauditIndividual  =   getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys2);
                @$newArr[$keys2]->itemCount->safobsIndividual    =   getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys2);
                @$newArr[$keys2]->itemCount->workshopTotal       =   getTotalData(1,"actualtarget_view","activity_date",$keys2);
                @$newArr[$keys2]->itemCount->commTotal           =   getTotalData(2,"actualtarget_view","activity_date",$keys2);
                @$newArr[$keys2]->itemCount->trainTotal          =   getTotalData(3,"actualtarget_training_view","from_date",$keys2);
                @$newArr[$keys2]->itemCount->safDayTotal         =   getTotalData(4,"actualtarget_view","activity_date",$keys2);
                @$newArr[$keys2]->itemCount->auditTotal          =   getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys2);
                @$newArr[$keys2]->itemCount->incidentTotal       =   getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys2);
                @$newArr[$keys2]->itemCount->ppeauditTotal       =   getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys2);
                @$newArr[$keys2]->itemCount->safobsTotal         =   getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys2);
                
			$isChild2 = $rowdata2->isChild;
			foreach( $isChild2 as $keys3 =>$rowdata3 ) {
				$newArr[$keys3]->name		= $rowdata3->name;
                                $newArr[$keys3]->division_id	= $keys3;
				$newArr[$keys3]->layer		= 3;
				$newArr[$keys3]->parent_id	= $rowdata3->parent_id;
			@$newArr[$keys3]->itemCount->workshopIndividual  =   getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->commIndividual      =   getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->trainIndividual     =   getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys3);
                        @$newArr[$keys3]->itemCount->safDayIndividual    =   getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->auditIndividual     =   getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys3);
                        @$newArr[$keys3]->itemCount->incidentIndividual  =   getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys3);
                        @$newArr[$keys3]->itemCount->ppeauditIndividual  =   getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys3);
                        @$newArr[$keys3]->itemCount->safobsIndividual   =   getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys3);
                        @$newArr[$keys3]->itemCount->workshopTotal       =   getTotalData(1,"actualtarget_view","activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->commTotal           =   getTotalData(2,"actualtarget_view","activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->trainTotal          =   getTotalData(3,"actualtarget_training_view","from_date",$keys3);
                        @$newArr[$keys3]->itemCount->safDayTotal         =   getTotalData(4,"actualtarget_view","activity_date",$keys3);
                        @$newArr[$keys3]->itemCount->auditTotal          =   getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys3);
                        @$newArr[$keys3]->itemCount->incidentTotal       =   getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys3);
                        @$newArr[$keys3]->itemCount->ppeauditTotal       =   getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys3);
                        @$newArr[$keys3]->itemCount->safobsTotal        =   getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys3);
				
				$isChild3 = $rowdata3->isChild;
				foreach( $isChild3 as $keys4 =>$rowdata4 ) {
					$newArr[$keys4]->name		= $rowdata4->name;
                                        $newArr[$keys4]->division_id	= $keys4;
					$newArr[$keys4]->layer		= 4;
					$newArr[$keys4]->parent_id	= $rowdata4->parent_id;
				@$newArr[$keys4]->itemCount->workshopIndividual  =   getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->commIndividual      =   getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->trainIndividual     =   getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys4);
                                @$newArr[$keys4]->itemCount->safDayIndividual    =   getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->auditIndividual     =   getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys4);
                                @$newArr[$keys4]->itemCount->incidentIndividual  =   getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys4);
                                @$newArr[$keys4]->itemCount->ppeauditIndividual  =   getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys4);
                                @$newArr[$keys4]->itemCount->safobsIndividual   =   getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys4);
                                @$newArr[$keys4]->itemCount->workshopTotal       =   getTotalData(1,"actualtarget_view","activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->commTotal           =   getTotalData(2,"actualtarget_view","activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->trainTotal          =   getTotalData(3,"actualtarget_training_view","from_date",$keys4);
                                @$newArr[$keys4]->itemCount->safDayTotal         =   getTotalData(4,"actualtarget_view","activity_date",$keys4);
                                @$newArr[$keys4]->itemCount->auditTotal          =   getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys4);
                                @$newArr[$keys4]->itemCount->incidentTotal       =   getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys4);
                                @$newArr[$keys4]->itemCount->ppeauditTotal       =   getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys4);
                                @$newArr[$keys4]->itemCount->safobsTotal        =   getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys4);
                                
					
					$isChild4 = $rowdata4->isChild;
					foreach( $isChild4 as $keys5 =>$rowdata5 ) {
						$newArr[$keys5]->name		= $rowdata5->name;
                                                $newArr[$keys5]->division_id	= $keys5;
						$newArr[$keys5]->layer		= 5;
						$newArr[$keys5]->parent_id	= $rowdata5->parent_id;
					@$newArr[$keys5]->itemCount->workshopIndividual  =   getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->commIndividual      =   getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->trainIndividual     =   getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys5);
                                        @$newArr[$keys5]->itemCount->safDayIndividual    =   getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->auditIndividual     =   getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys5);
                                        @$newArr[$keys5]->itemCount->incidentIndividual  =   getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys5);
                                        @$newArr[$keys5]->itemCount->ppeauditIndividual  =   getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys5);
                                        @$newArr[$keys5]->itemCount->safobsIndividual   =   getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys5); 
                                        @$newArr[$keys5]->itemCount->workshopTotal       =   getTotalData(1,"actualtarget_view","activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->commTotal           =   getTotalData(2,"actualtarget_view","activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->trainTotal          =   getTotalData(3,"actualtarget_training_view","from_date",$keys5);
                                        @$newArr[$keys5]->itemCount->safDayTotal         =   getTotalData(4,"actualtarget_view","activity_date",$keys5);
                                        @$newArr[$keys5]->itemCount->auditTotal          =   getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys5);
                                        @$newArr[$keys5]->itemCount->incidentTotal       =   getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys5);
                                        @$newArr[$keys5]->itemCount->ppeauditTotal       =   getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys5);
                                        @$newArr[$keys5]->itemCount->safobsTotal        =   getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys5);
                                        
						
						$isChild5 = $rowdata5->isChild;
							foreach( $isChild5 as $keys6 =>$rowdata6 ) {
								$newArr[$keys6]->name		= $rowdata6->name;
                                                                $newArr[$keys6]->division_id	= $keys6;
								$newArr[$keys6]->layer		= 6;
								$newArr[$keys6]->parent_id	= $rowdata6->parent_id;
                                                                @$newArr[$keys6]->itemCount->workshopIndividual  =   getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys6);                                                
                                                                @$newArr[$keys6]->itemCount->commIndividual      =   getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys6);
                                                                @$newArr[$keys6]->itemCount->trainIndividual     =   getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys6);
                                                                @$newArr[$keys6]->itemCount->safDayIndividual    =   getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys6);
                                                                @$newArr[$keys6]->itemCount->auditIndividual     =   getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys6);
                                                                @$newArr[$keys6]->itemCount->incidentIndividual  =   getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys6);
                                                                @$newArr[$keys6]->itemCount->ppeauditIndividual  =   getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys6);
                                                                @$newArr[$keys6]->itemCount->safobsIndividual   =    getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys6);
                                                                @$newArr[$keys6]->itemCount->workshopTotal       =   getTotalData(1,"actualtarget_view","activity_date",$keys6);
                                                                @$newArr[$keys6]->itemCount->commTotal           =   getTotalData(2,"actualtarget_view","activity_date",$keys6);
                                                                @$newArr[$keys6]->itemCount->trainTotal          =   getTotalData(3,"actualtarget_training_view","from_date",$keys6);
                                                                @$newArr[$keys6]->itemCount->safDayTotal         =   getTotalData(4,"actualtarget_view","activity_date",$keys6);
                                                                @$newArr[$keys6]->itemCount->auditTotal          =   getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys6);
                                                                @$newArr[$keys6]->itemCount->incidentTotal       =   getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys6);
                                                                @$newArr[$keys6]->itemCount->ppeauditTotal       =   getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys6);
                                                                @$newArr[$keys6]->itemCount->safobsTotal        =   getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys6);
                                                                $isChild6 = $rowdata6->isChild;
                                                                if(!empty($isChild6)) {
                                                                    foreach( $isChild6 as $keys7 =>$rowdata7 ) {
                                                                        @$newArr[$keys7]->name= $rowdata7->name;
                                                                        $newArr[$keys7]->division_id	= $keys7;
                                                                        @$newArr[$keys7]->layer= 7;
                                                                        @$newArr[$keys7]->parent_id= $rowdata7->parent_id;
                                                                        @$newArr[$keys7]->itemCount->workshopIndividual  =  getMisTreeData(1, $table = "actualtarget_view", $col = "activity_date",$keys7);                                                
                                                                        @$newArr[$keys7]->itemCount->commIndividual      =  getMisTreeData(2, $table = "actualtarget_view", $col = "activity_date",$keys7);
                                                                        @$newArr[$keys7]->itemCount->trainIndividual     =  getMisTreeData(3, $table = "actualtarget_training_view", $col = "from_date",$keys7);
                                                                        @$newArr[$keys7]->itemCount->safDayIndividual    =  getMisTreeData(4, $table = "actualtarget_view", $col = "activity_date",$keys7);
                                                                        @$newArr[$keys7]->itemCount->auditIndividual     =  getMisTreeData(5, $table = "actualtarget_audit_view", $col = "date_of_audit",$keys7);
                                                                        @$newArr[$keys7]->itemCount->incidentIndividual  =  getMisTreeData(6, $table = "actualtarget_incident_view", $col = "date_of_incident",$keys7);
                                                                        @$newArr[$keys7]->itemCount->ppeauditIndividual  =  getMisTreeData(7, $table = "actualtarget_ppeaudit_view", $col = "date_of_audit",$keys7);
                                                                        @$newArr[$keys7]->itemCount->safobsIndividual   =   getMisTreeData(8, $table = "actualtarget_safetyobs_view", $col = "activity_month",$keys7);
                                                                        @$newArr[$keys7]->itemCount->workshopTotal       =   getTotalData(1,"actualtarget_view","activity_date",$keys7);
                                                                        @$newArr[$keys7]->itemCount->commTotal           =   getTotalData(2,"actualtarget_view","activity_date",$keys7);
                                                                        @$newArr[$keys7]->itemCount->trainTotal          =   getTotalData(3,"actualtarget_training_view","from_date",$keys7);
                                                                        @$newArr[$keys7]->itemCount->safDayTotal         =   getTotalData(4,"actualtarget_view","activity_date",$keys7);
                                                                        @$newArr[$keys7]->itemCount->auditTotal          =   getTotalData(5,"actualtarget_audit_view","date_of_audit",$keys7);
                                                                        @$newArr[$keys7]->itemCount->incidentTotal       =   getTotalData(6,"actualtarget_incident_view","date_of_incident",$keys7);
                                                                        @$newArr[$keys7]->itemCount->ppeauditTotal       =   getTotalData(7,"actualtarget_ppeaudit_view","date_of_audit",$keys7);
                                                                        @$newArr[$keys7]->itemCount->safobsTotal        =    getTotalData(8,"actualtarget_safetyobs_view","activity_month",$keys7);


                                                                    }
                                                                }
							}
					}
				}
			}
		}
		
	}
}

mysql_query("truncate table mistree_cron_data");
date_default_timezone_set('Asia/Kolkata');
$updatedon=date("Y-m-d H:i:s");
 foreach ($newArr as $key => $rowdata) {
	  if($key == 1 ) {
		  $name = "CESC";
	  }
	  else {
		  $name = $rowdata->name;
	  }
	 mysql_query("INSERT INTO mistree_cron_data SET 
	 division_id='".$rowdata->division_id."',
	 name='".$name."',
	 layer='".$rowdata->layer."',
	 parent_id='".$rowdata->parent_id."',
	 incidentIndividual='".@$rowdata->itemCount->incidentIndividual."',
	 incidentTotal='".@$rowdata->itemCount->incidentTotal."',
	 auditIndividual='".@$rowdata->itemCount->auditIndividual."',
	 auditTotal='".@$rowdata->itemCount->auditTotal."',
	 ppeauditIndividual='".@$rowdata->itemCount->ppeauditIndividual."',
	 ppeauditTotal='".@$rowdata->itemCount->ppeauditTotal."',
	 trainIndividual='".@$rowdata->itemCount->trainIndividual."',
	 trainTotal='".@$rowdata->itemCount->trainTotal."',
	 workshopIndividual='".@$rowdata->itemCount->workshopIndividual."',
	 workshopTotal='".@$rowdata->itemCount->workshopTotal."',
	 commIndividual='".@$rowdata->itemCount->commIndividual."',
	 commTotal='".@$rowdata->itemCount->commTotal."',
	 safobsIndividual='".@$rowdata->itemCount->safobsIndividual."',
	 safobsTotal='".@$rowdata->itemCount->safobsTotal."',
	 safDayIndividual='".@$rowdata->itemCount->safDayIndividual."',
	 safDayTotal='".@$rowdata->itemCount->safDayTotal."',
         updatedOn='".$updatedon."'");
	 
	 }

exit;



?>

    
    
