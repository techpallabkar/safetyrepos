<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$presCtr = load_controller("LibraryController");
$presCtr->doAction();
$beanUi = $presCtr->beanUi;
$post_categories = $beanUi->get_view_data("post_categories");
$post_participants_categories = $beanUi->get_view_data("post_participants_categories");
$post_division_department = $beanUi->get_view_data("post_division_department");

$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$post_status = $beanUi->get_view_data("post_status");
$getalldata = $beanUi->get_view_data("getalldata");
$selectval = $beanUi->get_view_data("selectval");
$devition_names = $beanUi->get_view_data("devition_names");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$from_date = $beanUi->get_view_data("from_date");
$to_date = $beanUi->get_view_data("to_date");
$totalViolation = $beanUi->get_view_data("totalViolation");
$totalFindings = $beanUi->get_view_data("totalFindings");
$totalDeficiency = $beanUi->get_view_data("totalDeficiency");
$activityTypeId = $beanUi->get_view_data("activityTypeId");
$deviation_rows= $beanUi->get_view_data("deviation");
$peningivs= $beanUi->get_view_data("peningivs");
$pendingAction= $beanUi->get_view_data("pendingAction");
$jobstop= $beanUi->get_view_data("jobstop");
//show($deviation_rows);

$nature_of_injury = $beanUi->get_view_data("nature_of_injury");
$incident_category = $beanUi->get_view_data("incident_category");
$violation_category = $beanUi->get_view_data("violation_category");

$body_part_injury                   = $beanUi->get_view_data("body_part_injury");
$childData                          = $beanUi->get_view_data("childData");
$body_part_injury_mappingData       = $beanUi->get_view_data( "body_part_injury_mappingData");
$injury_status       = $beanUi->get_view_data( "injury_status");
//$finding_rows = $beanUi->get_view_data("finding_rows");
$presCtr->get_header();
?>

<script src="<?php echo url("assets/js/bootstrap.min.js"); ?>"></script>
<div class="mh-wrapper mh-home clearfix">

    <div class="print-friendly">
        <div class="topic-heading"><h3>
                <?php
                if($activityTypeId == 5 && $jobstop != "" ) {
                  echo 'Number of Stop';  
                }
                else if($activityTypeId == 5 ) {
                echo 'Violation of Site Audit';
                }
                else if($activityTypeId == 6  && $peningivs != "") {
                echo 'Pending Investigation';
                }
                else if($activityTypeId == 6  && $pendingAction != "") {
                echo 'Pending Action on Non-compliances';
                }
                else if($activityTypeId == 6 ) {
                echo 'Findings of Incident';
                }
                
                else if($activityTypeId == 7 ) {
                echo 'Deficiency of PPE Audit';
                }
                
                ?>
            </h3> 
            
        
        </div>

        <button class="btn btn-primary btn-medium" onclick="printContent('printDiv')" style="margin-left: 20px;float: right;margin-bottom: 30px;"><i class="fa fa-print"></i> Print Report</button>
        
        <div class="clearfix"></div>
        <hr>
        
        <div id="printDiv" class="holder required clearfix newHolderdiv">
            <ul class="mh-custom-posts-widget clearfix">

    <?php if (count($getalldata)) {  //show($getalldata);
        echo isset($_REQUEST["print"]) ? '<a href="searchPrint.php?activity_id='.$_REQUEST["activity"].'&tree='.$selectval.'&fromdate='.$_REQUEST["from_date"].'&to_date='.$_REQUEST['to_date'].'" target="_blank" class="btn btn-danger"><i class="fa fa-print"></i>  Print</a>' : ""; 
        foreach ($getalldata as $key => $datavalue) {
            $activity_type_id = $datavalue->activity_type_id;
            $rowdatss = $deviation_rows[$datavalue->id];
            ?>
                <li class="mh-custom-posts-item mh-custom-posts-small clearfix" style="border-bottom:2px dotted #000;">
                    	<?php   
                        if( !empty ( $devition_names ) )    {
                            $j=0;
                            $valxx = array();
                            foreach($devition_names as $key => $ddmrow)
                            {
                            $j = $j+1;
                            echo '<b>Selected Department : </b>'.$ddmrow.'<br>';
                            }
                        }
                        if( $activity_type_id == 5 )
                        {
                            echo ($datavalue->date_of_audit) ? '<b>Date of Audit : </b>'.date("F d,Y",strtotime($datavalue->date_of_audit)).'<br>' : "" ;
                            echo ($datavalue->time_of_audit_from) ? '<b>Time of Audit : </b>'.date("h:i A",strtotime($datavalue->time_of_audit_from)).' - '.date("h:i A",strtotime($datavalue->time_of_audit_to)).' ( '.$datavalue->audit_duration.' )'.'<br>' : "" ;                      
                            echo ($datavalue->place) ? '<b>Venue : </b>'.$datavalue->place.'<br>' : "" ;
                            echo ($datavalue->sas_report_no) ? '<b>SAS Report No. : </b>'.$datavalue->sas_report_no.'<br>' : "" ;
                            echo ($datavalue->major_violation) ? '<b>Major Violation : </b>'.$datavalue->major_violation.' ( '.$datavalue->no_of_violation.' )'.'<br><br>' : "" ;
                             if( !empty( $rowdatss ) ) {
                                echo '<table><tr class="bg-primary">
                                    <th align="center">Sl.No</th>
                                    <th align="center">Violation Type</th>
                                    <th align="center">Description</th>
                                    <th align="center">Violation Category</th>
                                    <th align="center">Remarks</th>
                                    </tr>';
                                foreach( $rowdatss as $kys => $rw ) {
                                    echo '<tr class="removetr" id="message_144">
                                            <td width="10%" align="center">'.($kys+1).'</td>
                                            <td width="15%" align="center">'.$rw->violation_type.'</td>
                                            <td width="40%" align="center">'.$rw->description.' pair</td>
                                            <td width="15%" align="center">'.$rw->violation_category.'</td>
                                            <td width="20%" align="center">'.$rw->remarks.'</td>
                                             </tr>';
                                }
                                echo '</table>';
                            }
                            echo ($datavalue->avg_mark) ? '<b>Average Mark : </b>'.$datavalue->avg_mark.'<br>' : "" ;
                            echo ($datavalue->job_stop_req_raised) ? '<b>Job Stop Request Raised : </b>'.$datavalue->job_stop_req_raised.'<br>' : "" ;
                            echo ($datavalue->remarks) ? '<b>Remarks : </b>'.$datavalue->remarks : "" ;
                        }
                        
                        if( $activity_type_id == 6 )
                        {
                            echo ($datavalue->date_of_incident) ? '<b>Date of incident : </b>'.date("F d,Y",strtotime($datavalue->date_of_incident)).'<br>' : "" ;
                            echo ($datavalue->time_of_incident) ? '<b>Time of Incident : </b>'.date("h:i A",strtotime($datavalue->time_of_incident)).'<br>' : "" ;                      
                            echo ($datavalue->place) ? '<b>Venue : </b>'.$datavalue->place.'<br>' : "" ;
                            
                            if(!empty($incident_category)) {
                                    foreach($incident_category as $rdata)
                                    {
                                            if( $rdata->id == $datavalue->incident_category_id )
                                            {
                                                    echo '<b>Incident Category : </b>'. $rdata->name.'<br>';
                                            }
                                    }
				}
                            
                            if($datavalue->nature_of_injury_id != "") {
                            $expvalue = explode(",",$datavalue->nature_of_injury_id);
                            $natureofinjury = "";
                            foreach($nature_of_injury as $rows)
                            {
                                if(in_array($rows->id,$expvalue)) 
                                { 
                                $natureofinjury .= $rows->name.","; 
                                } 
                            }
                            echo '<b>Nature of Injuiry : </b>'.substr($natureofinjury,0,-1).'<br>';  
                            
                            }
                            
                            echo '<table class="table table-bordered" style="width:100%;">';
                        $childArr ="";
                        foreach($body_part_injury as $rowdata)
                        {
                            $childArr = @$childData[$datavalue->id][$rowdata->id];
                            $status = @$body_part_injury_mappingData[$datavalue->id][$rowdata->id][0]->status;
                      $checked = ($status == "1" || $status == "2" ? "checked" :"");
                      if($status == "1" || $status == "2") {
                        echo  '<tr><td width="35%">'.ucwords($rowdata->name).'</td>
                        <td width="60%">';
                         foreach ($childArr as $value) {
                            $statusData= @$injury_status[$datavalue->id][$rowdata->id][$value->id]->status;
                            $checked1 = ($statusData == "1" ? "checked" :"");
                            $checked2 = ($statusData == "2" ? "checked" :"");
                            $checkeddisplay = ($statusData == "1" || $statusData == "2"  ? "block" :"none");
                            echo  '<div class="childRows'.$rowdata->id.'" style="display:'.$checkeddisplay.'" >
                                 <input type="hidden" name="parent_id[]" value="'.$rowdata->id.'" />
                                 <input type="hidden" name="bodypart_ids[]" value="'.$value->id.'" />';
                               
                             echo  $value->name.' : ';
                             if($statusData == "1") { echo "Yes"; }
                             if($statusData == "2") { echo "No"; }
                             
                             } 
                    echo  '</div>
                         </td>
                    </tr>'; 
                    } 
                    } 
		echo  '</table>';
                            
                echo '<b>Reportable : </b>';
                echo ($datavalue->reportable =="Y" ) ? "Yes" : "No" ;
                echo "<br>";
                echo '<b>Reporting Date : </b>';
                echo ($datavalue->reporting_date !="0000-00-00" ) ? date("F d, Y",strtotime($datavalue->reporting_date)) : "";
                echo "<br>";
                echo '<b>Investigation Required : </b>';
                echo ($datavalue->investigation_req == "Y" ) ? "Yes" : "NO" ;
                echo "<br>";
                echo '<b>Investigation Done : </b>';
                echo ($datavalue->investigation_done == "Y" ) ? "Yes" : "NO" ;
                if( $datavalue->investigation_done == "Y" ) {
                    echo "<br>";
                    echo '<b>Date of Investigation : </b>';
                    echo ($datavalue->inv_from_date != "") ? date("F d, Y",strtotime($datavalue->inv_from_date)).' - '.date("F d, Y",strtotime($datavalue->inv_to_date)) : "";
                    echo "<br>";
                    echo '<b>No of Findings : </b>';
                    echo ($datavalue->no_of_finding != "") ? $datavalue->no_of_finding : "";
                    if( !empty( $rowdatss ) ) {
                      echo '<table class="table">
                            <tr>
                            <th align="center">Sl.No</th>
                            <th align="center">Non Compliance</th>
                            <th align="center">Condition</th>
                            <th align="center">Action Taken</th>
                            </tr>';
                            $f = 0;
                            foreach($rowdatss as $kys => $rw)
                            {
                            $f = $f+1;

                            echo '<tr class="removetr" id="message_'.$rw->id.'">

                            <td align="center" width="10%">'.$f.'</td>
                            <td align="center" width="30%">'.$rw->description.'</td>
                            <td align="center" width="30%">'.$rw->conditions.'</td>
                            <td align="center" width="60%">';

                            if($rw->action_taken == 'Y') 
                            { 
                            echo "Yes<br><b>Compliance Description </b>: ".$rw->compliance_desc."<br><b>Compliance Date </b>: ".$rw->compliance_date;
                            } 
                            else 
                            { 
                            echo "No"; 
                            } 
                            echo '</td>
                            </tr>';
                            }

                            echo '</table>';
                    }
                }
                             
                              
                  
                if ($datavalue->violation_category != "") {
                echo '<br>';
                echo '<b>Violation Category : </b>';
                $exp = explode(",", $datavalue->violation_category);
                        $violationCategory = "";
                foreach ( $violation_category as $keyn => $rowData ) {
                    if(in_array($keyn,$exp)) 
                                { 
                                $violationCategory .= ' <i class="fa fa-dot-circle-o danger"></i> '.$rowData.' <br>'; 
                                } 
                }
                echo '<div class="synp-desc">'.$violationCategory.'</div>';

                }
                 if ($datavalue->fir_description != "") {
                        echo '<b> FIR Description : </b>';
                        echo '<div class="synp-desc">'.stripslashes($datavalue->fir_description).'</div>' ;
                        }

                 if ($datavalue->remarks != "") {
                      
                        echo '<b> Remarks : </b>';
                        echo '<div class="synp-desc">'.stripslashes($datavalue->remarks).'</div>' ;
                        }
                             
                            
                        }
                        
                        if( $activity_type_id == 7 )
                        {
                            echo ($datavalue->date_of_audit) ? '<b>Date of Audit : </b>'.date("F d,Y",strtotime($datavalue->date_of_audit)).'<br>' : "" ;
                            echo ($datavalue->time_of_audit_from) ? '<b>Time of Audit : </b>'.date("h:i A",strtotime($datavalue->time_of_audit_from)).' - '.date("h:i A",strtotime($datavalue->time_of_audit_to)).' ( '.$datavalue->audit_duration.' )'.'<br>' : "" ;                      
                            echo ($datavalue->place) ? '<b>Venue : </b>'.$datavalue->place.'<br>' : "" ;
                            echo ($datavalue->sas_report_no) ? '<b>SAS Report No. : </b>'.$datavalue->sas_report_no.'<br>' : "" ;
                            echo ($datavalue->major_deviation) ? '<b>Major Deviation : </b>'.$datavalue->major_deviation.' ( '.$datavalue->no_of_deviation.' )'.'<br><br>' : "" ;

                            if( !empty( $rowdatss ) ) {
                                echo '<table><tr class="bg-primary">
                                    <th align="center">Sl.No</th>
                                    <th align="center">Description of Deficiency</th>
                                    <th align="center">Quantity of Deficiency</th>
                                    <th align="center">Remarks</th>
                                    </tr>';
                                foreach( $rowdatss as $kys => $rw ) {
                                    echo '<tr class="removetr" id="message_144">
                                            <td width="10%" align="center">'.($kys+1).'</td>
                                            <td width="30%" align="center">'.$rw->description.'</td>
                                            <td width="30%" align="center">'.$rw->qty.' pair</td>
                                            <td width="30%" align="center">'.$rw->remarks.'</td>
                                             </tr>';
                                }
                                echo '</table>';
                            }
                            echo ($datavalue->avg_mark) ? '<b>Average Mark : </b>'.$datavalue->avg_mark : "" ;
                           
                            echo ($datavalue->remarks) ? '<b>Remarks : </b>'.$datavalue->remarks : "" ;
                        }
                        
                        ?>
                </li>
                          <?php     
                            }
    }
                        ?>
                    </ul>
                    <br>
        </div>
      
        <div class="clearfix"></div>
      
    </div>

    <script type="text/javascript">
        function printContent(el){
            var restorepage = document.body.innerHTML;
            var printcontent = document.getElementById(el).innerHTML;
            document.body.innerHTML = printcontent;
            window.print();
            document.body.innerHTML = restorepage;
        }
        </script>
<?php $presCtr->get_footer(); ?>
</body>
</html>