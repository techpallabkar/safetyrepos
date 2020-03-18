<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$presCtr = load_controller("ActivityController");
$presCtr->doAction();
$beanUi = $presCtr->beanUi;
//$recent_post = $beanUi->get_view_data("recent_post");
//$mostviewd = $beanUi->get_view_data("mostviewd");
$activity = $beanUi->get_view_data("activity");
$activity_type_master = $beanUi->get_view_data("activity_type_master");
$articles = $beanUi->get_view_data("articles");
$incident_category          = $beanUi->get_view_data("incident_category");
$nature_of_injury                   = $beanUi->get_view_data("nature_of_injury");
$body_part_injury                   = $beanUi->get_view_data("body_part_injury");
$childData                          = $beanUi->get_view_data("childData");
$body_part_injury_mappingData       = $beanUi->get_view_data( "body_part_injury_mappingData");
$injury_status       = $beanUi->get_view_data( "injury_status");
$finding_rows               = $beanUi->get_view_data("finding_rows");
$violation_category               = $beanUi->get_view_data("violation_category");

$violation_rows = $beanUi->get_view_data("violation");
$deviation_rows             = $beanUi->get_view_data("deviation");


$division_department = $beanUi->get_view_data("division_department");
$activity_participants = $beanUi->get_view_data("activity_participants");
$participants_list = $beanUi->get_view_data("participants_list");
$totalsafetyobsrecord = $beanUi->get_view_data("totalsafetyobsrecord");
$actcount = $beanUi->get_view_data("actcount");
//     show($totalsafetyobsrecord);

$devition_names = $beanUi->get_view_data("devition_names");
$type_images = array("image/jpg", "image/jpeg", "image/png");
$type_videos = array("video/mp4", "video/quicktime");
$pdf = array("application/pdf");
$presCtr->get_header();
$activty_type_id = $activity[0]->activity_type_id;
$activity_name = get_value_by_id($activty_type_id, "activity_name", $activity_type_master); 
$default_image = array(
    1 => "workshop.png",
    2 => "meeting.png",
    3 => "training.png",
    4 => "safety.png",
    5 => "audit.png",
    6 => "incident.png",
    7 => "ppeaudit.png",
    8 => "safetyobs.png"
);
?>
    
<script> 
$(document).ready(function(){
	$('.scroller').scrollify(); 
});
</script>
   <form id="serachform" action="<?php echo url("pages/master/locationSearch.php"); ?>" method="post">
        <input type="hidden" name="selectval" value="<?php echo $_REQUEST['selectval']; ?>" />
        <input type="hidden" name="from_date" value="<?php echo $_REQUEST['from_date']; ?>" />
        <input type="hidden" name="to_date" value="<?php echo $_REQUEST['to_date']; ?>" />
        <input type="hidden" name="activityName" value="<?php echo $_REQUEST['activityName']; ?>" />
        <input type="hidden" name="activity" value="<?php echo $_REQUEST['activity']; ?>" />
       <input name="search" type="hidden" value="search">
    </form>
<div class="mh-wrapper mh-home clearfix">
    <div id="main-content" class="mh-content mh-home-content clearfix">  
        <div align="right"><button onclick="goBack()">Back</button></div>
        <div class="grey-bg"><?php echo $activity_name; ?></div> 
        <div class="clearfix"></div>
        
        <?php
      /********************************INCIDENT*****************************************/
	if( $activty_type_id == 6 )
	{
        $incident = "";

        
        $incident .= '<p class="autor">';
            if ( @$activity[0]->incident_no != "" ) {
                $incident .= '<span class="mh-meta-date updated"><i class="fa fa-bar-chart"></i> ' . $activity[0]->incident_no . '</span> &nbsp; &nbsp' . "\n";
            }
            if ( @$activity[0]->date_of_incident != "" ) {
                $incident .= '<span class="mh-meta-date updated"><i class="fa fa-clock-o"></i> ' . date('F j, Y', strtotime($activity[0]->date_of_incident)) . '</span>' . "&nbsp; &nbsp; \n";
            }
            if ( @$activity[0]->time_of_incident != "" ) {
                $incident .= '<span class="mh-meta-date updated"><i class="fa fa-clock-o"></i> ' . date('h:i A', strtotime($activity[0]->time_of_incident)) . '</span>' . "&nbsp; &nbsp; \n";
            }
            if ( @$activity[0]->place != "" ) {
                $incident .= '<span class="fa fa-map-marker"></span> ' . stripslashes($activity[0]->place) . '';
            }
        $incident .= '</p>';
        
        $incident .= '<div class="dept-details" style="margin-left:0;">'; 
        foreach ($devition_names as $key => $ddmrow) {
            $dptree = str_replace("/", " <i class='fa fa-caret-right treecaret'></i> ", trim($ddmrow));        
            $incident .= '<i class="fa fa-dot-circle-o danger"></i> ' . $dptree . '&nbsp;&nbsp;';
        }
        $incident .= '</div>'; 
        $incident .= '<div class="clearfix" style="margin-top:7px;">';
          $filePath = BASE_PATH.'/'.@$activity[0]->featured_image_path;
                if (@$activity[0]->featured_image_path != "" && file_exists($filePath)) {
                    $incident .= '<img src="' . url(@$activity[0]->featured_image_path) . '" alt="" width="150" height="150" class="img2">' . "\n";
                }
                else
                {
                    $incident .= '<img src="' . url("admin/assets/css/cropimage/img/".$default_image[$activity[0]->activity_type_id]) . '" alt="" width="150" height="150" class="img2">' . "\n";
                    
                }
                
                
                $incident .= '<table class="border-less">';
		
                $incident .= (@$activity[0]->date_of_incident!='0000-00-00') ? "<tr><td><b>Date of Incident </b></td><td> : </td><td>".date("F d,Y",strtotime(@$activity[0]->date_of_incident))."</td>" : "" ;
                $incident .= (@$activity[0]->time_of_incident!='00:00:00') ? "<tr><td><b>Time of Incident </b></td><td> : </td><td>".date("h:i A",strtotime(@$activity[0]->time_of_incident))."</td>" : "" ;
                $incident .= (@$activity[0]->place!='') ? "<tr><td><b>Venue </b></td><td> : </td><td>".@$activity[0]->place."</td>" : "" ;
                if(!empty($incident_category)) {
        foreach($incident_category as $rdata)
		{
                    if( $rdata->id == @$activity[0]->incident_category_id )
                    {
                            $incident .= '<tr><td><b>Incident Category</b></td><td> : </td><td>'. $rdata->name.'</td></tr>';
                    }
		}
				}
         $expvalue = explode(",",@$activity[0]->nature_of_injury_id);
		 $natureofinjury = "";
			foreach($nature_of_injury as $rows)
			{

			if(in_array($rows->id,$expvalue)) 
			{ 
			$natureofinjury .= $rows->name.","; 
			} 
			}
			$incident .= '<tr><td><b>Nature of Injuiry</b></td><td> : </td><td>'.substr($natureofinjury,0,-1).'</td></tr>';  
                        
        $incident .= '<tr><td><b>Reportable </b></td><td> : </td><td>';
                $incident .= (@$activity[0]->reportable =="Y" ) ? "YES" : "NO" ;
                $incident .= '</td></tr>';
                $incident .= '<tr><td><b>Reporting Date </b></td><td> : </td><td>';
                $incident .= (@$activity[0]->reporting_date !="0000-00-00" ) ? date("F d, Y",strtotime(@$activity[0]->reporting_date)) : "" ;
                $incident .= '</td></tr>';
                
                $incident .= '<tr><td><b>Investigation Required </b></td><td> : </td><td>';
                $incident .= (@$activity[0]->investigation_req == "Y" ) ? "YES" : "NO" ;
                $incident .= '</td></tr>';
                if(@$activity[0]->investigation_req == 'Y') {
                $incident .= '<tr><td><b>Investigation Done</b></td><td> : </td><td>';
                $incident .= (@$activity[0]->investigation_done == "Y") ? "YES" : "NO";
                $incident .= '</td></tr>';
              
                
                if(@$activity[0]->investigation_done == "Y") {
                $incident .= '<tr><td><b>Date of Investigation</b></td><td> : </td><td>';
                $incident .= (@$activity[0]->inv_from_date != "") ? date("F d, Y",strtotime($activity[0]->inv_from_date)).' - '.date("F d, Y",strtotime($activity[0]->inv_to_date)) : "";
                $incident .= '</td></tr>';
                
                $incident .= '<tr><td><b>No of Findings </b></td><td> : </td><td>';
                $incident .= (@$activity[0]->no_of_finding != "") ? $activity[0]->no_of_finding : "";
                
                $incident .= '&nbsp;&nbsp;&nbsp;<a href="#" style="padding:4px 10px;" class="js-open-modal btn btn-info btn-xs" data-modal-id="popup"> <i class="fa fa-eye"></i> View All</a></td>';
                }
                }
                $incident .='</tr></table><div class="clearfix"></div>';
		$incident .= '<div id="popup" class="modal modalbox" role="dialog">
                    <div class="modal-dialog"><div class="modal-content">
      <div class="modal-header clearfix">
        <a href="#" style="float:right;"  class="js-modal-close" data-dismiss="modal">&times;</a>
        <h4 class="modal-title">Finding Details</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-hover">
			<tr class="bg-primary">
                        <th>Sl.No</th>
                        <th>Description of Deficiency</th>
                        <th>Quantity of Deficiency</th>
                        <th>Remarks</th>
                        </tr>';

                        if( count($finding_rows) )
                        {
                        $f = 0;
                        foreach($finding_rows as $row)
                        {
                        $f = $f+1;

                        $incident .='<tr class="removetr" id="message_'.$row->id.'">

                        <td align="center" width="10%">'.$f.'</td>
                        <td align="center" width="30%">'.$row->description.'</td>
                        <td align="center" width="30%">'.$row->conditions.'</td>
                        <td align="center" width="60%">';

                        if($row->action_taken == 'Y') 
                        { 
                        $incident .= "Yes<br><b>Compliance Description </b>: ".$row->compliance_desc."<br><b>Compliance Date </b>: ".$row->compliance_date;
                        } 
                        else 
                        { 
                        $incident .= "No"; 
                        } 
                        $incident .='</td>
                        </tr>';
                        }
                        }
        $incident .='</table>
            </div>
            <div class="modal-footer">
              <a href="#" class="btn btn-small btn-primary js-modal-close">Close</a>
            </div>
          </div>

        </div>
      </div>';                
                        
        $incident .=  '</div>';   
        if(@$activity[0]->investigation_req == 'Y') {
          $incident .= '<br><div class="synp-heading" style="margin-left:0;">Investigation Description</div>';
                $incident .= @$activity[0]->investigation_required_yes;
               
        }
        if(@$activity[0]->incident_category_id == 1) {
            if(@$activity[0]->incident_description != "") {
            $incident .= '<br><div class="synp-heading" style="margin-left:0;">Incident Description</div>'.@$activity[0]->incident_description;
            }
        } else {
        $incident .=  '<br><table class="table table-bordered table-red" style="width:100%;">'
                . '<tr align="left"  class="bg-info"><th colspan="2">Injury at Body Part</th></tr>';
		 
                $childArr ="";
                        foreach($body_part_injury as $rowdata)
                        {
                            $childArr = @$childData[$rowdata->id];
                            $status = @$body_part_injury_mappingData[$rowdata->id][0]->status;
                      $checked = ($status == "1" || $status == "2" ? "checked" :"");
                      if($status == "1" || $status == "2") {
               $incident .=  '<tr>
                       
                         <td width="35%">'.ucwords($rowdata->name).'</td>
                         <td width="60%">';
                           
                         foreach ($childArr as $value) {
                            $statusData= @$injury_status[$rowdata->id][$value->id]->status;
                            $checked1 = ($statusData == "1" ? "checked" :"");
                            $checked2 = ($statusData == "2" ? "checked" :"");
                            $checkeddisplay = ($statusData == "1" || $statusData == "2"  ? "block" :"none");
                            $incident .=  '<div class="childRows'.$rowdata->id.'" style="display:'.$checkeddisplay.'" >
                                 <input type="hidden" name="parent_id[]" value="'.$rowdata->id.'" />
                                 <input type="hidden" name="bodypart_ids[]" value="'.$value->id.'" />';
                               
                             $incident .=  $value->name.' : ';
                             if($statusData == "1") { $incident .= "Yes"; }
                             if($statusData == "2") { $incident .= "No"; }
                             
                             } 
                    $incident .=  '</div>
                         </td>
                    </tr>'; } } 
		 $incident .=  '</table>';
        }
                 
                
        if (@$activity[0]->violation_category != "") {
        $incident .= '<br>';
        $incident .= '<div class="synp-heading" style="margin-left:0;">Violation Category </div>';
        $exp = explode(",", $activity[0]->violation_category);
		$violationCategory = "";
        foreach ( $violation_category as $keyn => $rowData ) {
            if(in_array($keyn,$exp)) 
			{ 
			$violationCategory .= ' <i class="fa fa-dot-circle-o danger"></i> '.$rowData.' <br>'; 
			} 
        }
        $incident .= '<div class="synp-desc">'.$violationCategory.'</div>';
        
        }
        $incident .= '<br>';
                if (@$activity[0]->fir_description != "") {
                $incident .= '<div class="synp-heading" style="margin-left:0;">FIR Description </div>';
                $incident .= '<div class="synp-desc">'.stripslashes($activity[0]->fir_description).'</div>' ;
                }
        
        
        $incident .= '<br><br>';
                
        if (@$activity[0]->file_type != "") { 
            $is_image = 0;
            foreach ($activity as $key => $value)
                if (in_array($value->file_type, $type_images))
                    $is_image++;
                if ($is_image) {
					
                    if($is_image > 1 ) {
                $incident .= '<div class="jq-scroller-wrapper">
                            <div class="jq-scroller1">
                                <ul class="scroller jq-scroller-mover"> ';
                                    foreach ($activity as $key => $value) {
                                        if (!in_array($value->file_type, $type_images) || !is_file(BASE_PATH . "/" . $value->file_path))
                                            continue;
                                        if ($value->type_id == 0) {
                                            $imagePath = BASE_PATH.'/'.$value->file_path;
                                            if( file_exists($imagePath) && $value->file_path !="" ) {
                                            $incident .= '<li class="jq-scroller-item">
                                                <a class="jq-scroller-preview" href="'.url($value->file_path).'" data-title="'.@$value->name.'"> 
                                                    <img src="'.url($value->file_path).'" alt=""> 
                                                </a>
                                            </li>';
                                            }
                                        }
                                    }
                                  
                                $incident .= '</ul>
                            </div>
                            <i class="fa fa-arrow-circle-left jq-scroller-prev"></i>
                            <i class="fa fa-arrow-circle-right jq-scroller-next"></i>
                            <div class="jq-scroller-overlay">
                                <div class="jq-overlay-item">
                                    <i class="jq-overlay-close fa fa-times-circle"></i>
                                    <i class="fa fa-arrow-circle-left jq-scroller-overlay-prev"></i>
                                    <i class="fa fa-arrow-circle-right jq-scroller-overlay-next"></i>
                                    <div class="jq-overlay-content"></div>
                                </div>
                            </div>
                        </div>';
					}
                }
                $incident .= '<br>';
                $is_video = 0;
                foreach ($activity as $key => $value)
                    if (in_array($value->file_type, $type_videos))
                            $is_video++;
                    if ($is_video) {
                    $incident .= '<div class="clearfix">';
                           
                            foreach ($activity as $key => $value) {
                                if (!in_array($value->file_type, $type_videos) || !is_file(BASE_PATH . "/" . $value->file_path))
                                    continue;
                                $videoPath = BASE_PATH.'/'.$value->file_path;
                                if ($value->type_id == 0) {
                                    if( file_exists($videoPath) && $value->file_path !="" ) {
                                    $incident .= '<div class="" style="width:300px;height:180px;float:left;margin:10px;">
                                        <center>
                                            <video controls="controls" style="width:300px;height:150px;"><source type="'.$value->file_type.'" src="'.url($value->file_path).'" /></video>
                                            <a href="'.url($value->file_path).'" style="" download="'.$value->file_path.'">
                                                <i title="Download Video" class="fa fa-download" style="color : blue;"></i> Download Video
                                            </a>
                                        </center>
                                    </div>';
                                    }
                                }
                            }
                            
                    $incident .= '<div class="clearfix"></div></div>';
                    } 
                    $incident .= '<br>';
                    foreach ($activity as $key => $value) {
                        if ($value->type_id == 1) {
                        $filePath = BASE_PATH.'/'.$value->file_path;
                        if( file_exists($filePath) && $value->file_path !="" ) {
                            $incident .= '<div class="clearfix"></div>
                                <div class="document">
                                <a href="'.url($value->file_path).'" target="_blank" download="'.$value->file_path.'">
                                    <i title="Download Document" class="fa fa-download"> </i> ';
                                       
                                        if ($value->name != '') {
                                            $incident .= $value->name;
                                        } else {
                                            $incident .= "Download Attached File";
                                        }
                                    $incident .= '</a>
                                </div>
                                <div class="clearfix"></div>
                            ';
                        }
                        }
                    }
                }
                
                 $incident .= '<br />';
                if (@$activity[0]->remarks != "") {
                $incident .= '<div class="synp-heading" style="margin-left:0;">Remarks</div>' . "\n";
                $incident .= '<div class="synp-desc">' . stripslashes($activity[0]->remarks) . '</div>' . "\n";
            }
			echo $incident;
    } 
	else if( $activty_type_id == 8 )
	{  
            
/*********************************************SAFETY OBS****************************************************/                
        $safety_observation = "";
         if ( $activity[0]->activity_month != "" && $activity[0]->activity_year != "" ) {
                $fomrmatedval = $activity[0]->activity_year."-".$activity[0]->activity_month."-01";
                $safety_observation .= '<h4>' . date("F , Y",strtotime($fomrmatedval)) . '&nbsp;&nbsp;&nbsp; <span style="float:right;">Activity Count : '.$actcount.'</span></h4><hr />';
            }
        if( !empty( $totalsafetyobsrecord ) ) {
            $safety_observation .= '<table class="table table-bordered table-red">';
            $safety_observation .= '<tr class="bg-primary">'
                    . '<th>Sl.No</th>'                   
                    . '<th>Division Department</th>'                   
                    . '<th>Venue</th>'
                    . '<th>Activity Count</th>'
                    . '</tr>';
            foreach( $totalsafetyobsrecord as $r => $arrRow ) {
                $tree = str_replace("/", " <i class='fa fa-caret-right treecaret'></i> ", trim($arrRow->treename));
                if($arrRow->id == $_REQUEST['id'] && $_REQUEST['mode'] == 'srch') {
                 $safety_observation .= '<tr class="bg-primary">'
                         . '<td align="center">1.</td>'
                    . '<td align="left"> <i class="fa fa-dot-circle-o danger"></i> '.$tree.'</td>'                   
                    . '<td align="center">'.$arrRow->place.'</td>'
                    . '<td align="center">'.$arrRow->activity_count.'</td>'
                    . '</tr>';
                 } else if(!$_REQUEST['mode']) {
                      $safety_observation .= '<tr class="bg-primary">'
                         . '<td align="center">'.($r+1).'.</td>'
                    . '<td align="left"> <i class="fa fa-dot-circle-o danger"></i> '.$tree.'</td>'                   
                    . '<td align="center">'.$arrRow->place.'</td>'
                    . '<td align="center">'.$arrRow->activity_count.'</td>'
                    . '</tr>';
                 }
            }
            
            $safety_observation .= '</table>';
        }
        /*$safety_observation .= '<p class="autor">';
            if ( @$activity[0]->activity_no != "" ) {
                $safety_observation .= '<span class="mh-meta-date updated"><i class="fa fa-bar-chart"></i> ' . @$activity[0]->activity_no . '</span> &nbsp; &nbsp' . "\n";
            }
            if ( $activity[0]->activity_month != "" && $activity[0]->activity_year != "" ) {
                $fomrmatedval = $activity[0]->activity_year."-".$activity[0]->activity_month."-01";
                $safety_observation .= ' <span class="fa fa-clock-o"></span> ' . date("F , Y",strtotime($fomrmatedval)) . '';
            }
            if ( $activity[0]->place != "" ) {
                $safety_observation .= ' <span class="fa fa-map-marker"></span> ' . stripslashes($activity[0]->place) . '';
            }
        $safety_observation .= '</p>';
        $safety_observation .= '<div class="dept-details" style="margin-left:0;">'; 
        foreach ($devition_names as $key => $ddmrow) {
            $dptree = str_replace("/", " <i class='fa fa-caret-right treecaret'></i> ", trim($ddmrow));        
            $safety_observation .= '<i class="fa fa-dot-circle-o danger"></i> ' . $dptree . '&nbsp;&nbsp;';
        }
        $safety_observation .= '</div> '; 
             $filePath = BASE_PATH.'/'.@$activity[0]->featured_image_path;
                if (@$activity[0]->featured_image_path != "" && file_exists($filePath)) {
                    $safety_observation .= '<img src="' . url(@$activity[0]->featured_image_path) . '" alt="" width="150" height="150" class="img2">' . "\n";
                }
                else
                {
                    $safety_observation .= '<img src="' . url("admin/assets/css/cropimage/img/".$default_image[$activity[0]->activity_type_id]) . '" alt="" width="150" height="150" class="img2">' . "\n";
                    
                }
	$montht = date("Y").'-'.@$activity[0]->activity_month.'-01';
        $safety_observation .= '<table class="border-less"><tr><td style="width:150px;"><b>Activity Month </b></td><td style="width:20px;"> : </td><td>'.date('F', strtotime($montht)).'</td></tr>';   
        $safety_observation .= '<tr><td><b>Activity Year </b></td><td> : </td><td>'.@$activity[0]->activity_year.'</td></tr>'; 
        $safety_observation .= (@$activity[0]->place != "") ? '<tr><td><b>Venue </b></td><td> : </td><td>'.@$activity[0]->place.'</td></tr>' : "";
        $safety_observation .= '<tr><td><b>Number of Activities </b> </td><td>:</td><td>'.@$activity[0]->activity_count.'</td></tr>';   
        $safety_observation .= '</table>*/
         $safety_observation .= '<div class="clearfix"></div>';
        
        echo $safety_observation;
    } 
	else if( $activty_type_id < 5 )
	{    
/*******************************WORKSHOP COMM MEETING TRAINING SAFETY DAYS******************************************************************/        
    $wrk_comm_train_safety = "";     
    $wrk_comm_train_safety .= '<div class="mh-home-2 mh-widget-col-2 mh_slider_hp clearfix">';
        if ( @$activity[0]->subject_details != "" ) { 
            $wrk_comm_train_safety .= '<h4 class="mh-widget-title">' . @stripslashes($activity[0]->subject_details) . '</h4>'; 
        }
       
        
        /*$wrk_comm_train_safety .= '<p class="autor">';
            if ( @$activity[0]->activity_no != "" ) {
                $wrk_comm_train_safety .= '<span class="mh-meta-date updated"><i class="fa fa-bar-chart"></i> ' . @$activity[0]->activity_no . '</span> &nbsp; &nbsp' . "\n";
            }
            if($activty_type_id == 3 ) {
                if ( @$activity[0]->from_date != "" ) {
                $wrk_comm_train_safety .= '<span class="mh-meta-date updated"><i class="fa fa-clock-o"></i> ' . date('F j, Y', strtotime($activity[0]->from_date)) . '</span>' . "&nbsp; &nbsp; \n";
                }
            } else {
                if ( @$activity[0]->activity_date != "" ) {
                    $wrk_comm_train_safety .= '<span class="mh-meta-date updated"><i class="fa fa-clock-o"></i> ' . date('F j, Y', strtotime($activity[0]->activity_date)) . '</span>' . "&nbsp; &nbsp; \n";
                }
            }
            
            if ( @$activity[0]->place != "" ) {
                $wrk_comm_train_safety .= '<span class="fa fa-map-marker"></span> ' . @stripslashes($activity[0]->place) . '';
            }
        $wrk_comm_train_safety .= '</p>';
        $wrk_comm_train_safety .= '<div class="dept-details" style="margin-left:0;">'; 
        foreach ($devition_names as $key => $ddmrow) {
            $dptree2 = str_replace("/", " <i class='fa fa-caret-right treecaret'></i> ", trim($ddmrow));        
            $wrk_comm_train_safety .= '<i class="fa fa-dot-circle-o danger"></i> ' . $dptree2 . '&nbsp;&nbsp;';
        }
        $wrk_comm_train_safety .= '</div>';
         * 
         */ 
        $filePath = BASE_PATH.'/'.@$activity[0]->featured_image_path;
        if (@$activity[0]->featured_image_path != "" && file_exists($filePath)) {
            $wrk_comm_train_safety .= '<img src="' . url(@$activity[0]->featured_image_path) . '" alt="" width="150" height="150" class="img2">' . "\n";
        } else {
            $wrk_comm_train_safety .= '<img src="' . url("admin/assets/css/cropimage/img/".$default_image[$activity[0]->activity_type_id]) . '" alt="" width="150" height="150" class="img2">' . "\n";
        }
        $wrk_comm_train_safety .= '<table class="border-less">';
        $wrk_comm_train_safety .= '<tr><td><b>Division Department</b></td><td>:</td><td><div style="margin-left:0;">';
            foreach ($devition_names as $key => $ddmrow) {
                $dptree2 = str_replace("/", " <i class='fa fa-caret-right treecaret'></i> ", trim($ddmrow));        
                $wrk_comm_train_safety .= '<i class="fa fa-dot-circle-o danger"></i> ' . $dptree2 . '&nbsp;&nbsp;';
            }
        $wrk_comm_train_safety .='</td></div></tr>';
        $wrk_comm_train_safety .=   '<tr><td><b>Set type</b></td><td>:</td><td>'.@$activity[0]->set_type.'</td></tr>';
        
        $wrk_comm_train_safety .= '<tr><td><b>Activity No.  </b></td><td>:</td>'
                        . '<td>'.(( @$activity[0]->activity_no != "" ) ? $activity[0]->activity_no : "") .'</td>'
                        . '</tr>';
        if( $activty_type_id == 3 ) {
            $wrk_comm_train_safety .= (( @$activity[0]->from_date != "0000-00-00" && @$activity[0]->to_date != "0000-00-00" ) ? '<tr><td><b>Period of Activity  </b></td><td>:</td><td>'.date('F j, Y', strtotime($activity[0]->from_date)).' - '.date('F j, Y', strtotime($activity[0]->to_date)).'</td></tr>' : "") .'';
        }
        else {
            $wrk_comm_train_safety .= (( @$activity[0]->activity_date != "0000-00-00" ) ? '<tr><td><b>Date of Activity  </b></td><td>:</td><td>'.date('F j, Y', strtotime($activity[0]->activity_date)).'</td></tr>' : "");
        }
        $wrk_comm_train_safety .= '<tr><td><b>Venue  </b></td><td>:</td>'
                        . '<td>'.(( @$activity[0]->place != "" ) ? $activity[0]->place : "") .'</td>'
                        . '</tr>';
        $wrk_comm_train_safety .= '</table><div class="clearfix"></div><br>';
        
                if (!empty($activity_participants)) {
                   $wrk_comm_train_safety .= '<div class="synp-heading" style="margin-left:0;">Participants & faculty</div><table class="table-bordered table-hover" style="float:left; width:100%;">
                        <tr>
                            <th width="3%">Sl.No</th>
                            <th width="50%">Category of Participants</th>
                            <th width="50%">No. of Participants</th>
                        </tr>';
                   $i=0;
                        foreach ($activity_participants as $rowdata) {
                            $activity_rows = isset($participants_list[$rowdata->id]) ? $participants_list[$rowdata->id] : array();
                            if ($rowdata->type == 'P') {
                                $i=$i+1;
                               $wrk_comm_train_safety .= '<tr>
                                   <td>'.($i).'.</td>
                                    <td align="center">'.$rowdata->category_name.'</td>
                                    <td align="center">
                                       '.$rowdata->no_of_participants.' ( <a href="#" class="js-open-modal" data-modal-id="popup'.$rowdata->id.'" style="color: blue;"> <i class="fa fa-search"></i> View Participants</a> )
                                        <!--participants popup-->
                                        <div id="popup'.$rowdata->id.'" class="modal modalbox">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header clearfix"><a href="#" class="js-modal-close close">×</a>
                                                        <h4 class="modal-title">Participants Details</h4>
                                                    </div>
                                            <div class="modal-body" style="overflow-x: hidden;overflow-y: auto;max-height:320px;">
                                                <table class="table table-bordered" id="pdetails_'.$rowdata->id.'">';
                                                  
                                                    if ($rowdata->participant_cat_id == '4') {
                                                        $hide = ' style="display:none;"';
                                                    } else {
                                                        $hide = '';
                                                    }
                                                   
                                                    $wrk_comm_train_safety .='<tr>

                                                        <th>No. of Employee</th>
                                                        <th>Designation</th>
                                                        <th>Department</th>
                                                    </tr>';
                                                    if (count($activity_rows)) {
                                                        foreach ($activity_rows as $row) {
                                                            
                                                            $wrk_comm_train_safety .= '<tr>
                                                                <td align="center">'.$row->no_of_emp.'</td>
                                                                <td align="center">'.$row->designation;

                                                                    if (!empty($row->designation_2)) {
                                                                        $wrk_comm_train_safety .= " => " . $row->designation_2;
                                                                    }
                                                                 $wrk_comm_train_safety .='</td>
                                                                <td align="center">'.$row->department.'</td>
                                                            </tr>';
                                                        }
                                                    }
                                                   
                                                $wrk_comm_train_safety .= '</table>
                                            </div>
                                            <div class="modal-footer">           
                                                <a href="#" class="btn btn-small btn-primary js-modal-close">Close</a>
                                            </div>
                                        </div>

                                      </div>
                                    </div>
                                    </td>
                                </tr>';
                            }
                        }
                       $wrk_comm_train_safety .=  "</table><div class='clearfix'></div>";
                    }
        
                         
   if (!empty($activity_participants)) {
               
                $wrk_comm_train_safety .= '
                    <table class="table table-bordered table-condensed table-hover table-red">
                    <tr>
                    <th width="5%">Sl.No</th>
                        <th width="50%">Faculty</th>
                        <th width="50%">No. of Faculty</th>
                    </tr>';
                   $j=0;
                    foreach ($activity_participants as $rowdata) {
                        $activity_rows = isset($participants_list[$rowdata->id]) ? $participants_list[$rowdata->id] : array();
                        if ($rowdata->type == 'F') {
                           $j=$j+1;
                            $wrk_comm_train_safety .='<tr>
                                <td>'.($j).'.</td>
                                <td align="center">'.$rowdata->category_name.'</td>
                                <td align="center">'.$rowdata->no_of_participants.' ( <a href="#" class="js-open-modal" data-modal-id="popup'.$rowdata->id.'" style="color: blue;"> <i class="fa fa-search"></i> View Faculty</a> )
                                    <!--participants popup-->
                                    <div id="popup'.$rowdata->id.'" class="modal modalbox">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header clearfix">
                                                    <a href="#" class="js-modal-close close">×</a>
                                             <h4 class="modal-title">Faculty Details</h4>
                                        </div>
                                        <div class="modal-body" style="max-height:320px;overflow-x: hidden;overflow-y: auto;">
                                            <table class="table table-bordered" id="pdetails_'.$rowdata->id.'">';
                                             
                                                if ($rowdata->participant_cat_id == '2') {
                                                    $hide = ' style="display:none;"';
                                                } else {
                                                    $hide = '';
                                                }
                                               
                                                $wrk_comm_train_safety .= '<tr class="primary" >';
                if ($rowdata->participant_cat_id != '2') {
                                                        $wrk_comm_train_safety .= '<th <?php echo $hide; ?>Employee Code</th>
                                                        <th>Name</th>
                                                        <th>Designation</th>
                                                        <th>Department</th>';
             } else { 
                                                        $wrk_comm_train_safety .= '<th>Name</th>
                                                        <th>Faculty Details</th>';
              } 
                                                $wrk_comm_train_safety .= '</tr>';
                                               
                                                if (count($activity_rows)) {
                                                    foreach ($activity_rows as $row) {
                                                        if ($rowdata->participant_cat_id != '2') {
                                                            
                                                            $wrk_comm_train_safety .= '<tr>
                                                                <td align="center" '.$hide.'>'.$row->emp_code.'</td>
                                                                <td align="center">'.$row->name.'</td>
                                                                <td align="center">'.$row->designation.'</td>
                                                                <td align="center">'.$row->department.'</td>
                                                            </tr>';
                                                        } else {
                                                         
                                                            $wrk_comm_train_safety .='<tr>

                                                                <td align="center">'.$row->name.'</td>
                                                                <td align="center">'.$row->designation.'</td>
                                                            </tr> ';                             

                                                        }
                                                    }
                                                }
                                                
                                            $wrk_comm_train_safety .= '</table>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="#" class="btn btn-small btn-primary js-modal-close">Close</a> 
                                            </div>
                                            </div>

                                          </div>
                                        </div>
                                    <!--participants popup-->
                                </td>
                            </tr>';
                         
                        }
                    }
                    $wrk_comm_train_safety .= "</table>";
                }
               
                $wrk_comm_train_safety .= '<div class="clearfix"></div><div class="synp-heading" style="margin-left:0;">Office Files,Images & Video</div>';    
               
                if (@$activity[0]->file_type != "") {
                    $is_image = 0;
                    foreach ($activity as $key => $value)
                        if (in_array($value->file_type, $type_images))
                            $is_image++;
                    if ($is_image) {
                        if($is_image > 1 ) 
						{
                        $wrk_comm_train_safety .= '<div class="jq-scroller-wrapper">
                            <div class="jq-scroller1">
                                <ul class="scroller jq-scroller-mover">'; 
                                   
                                    foreach ($activity as $key => $value) {
                                        if (!in_array($value->file_type, $type_images) || !is_file(BASE_PATH . "/" . $value->file_path))
                                            continue;
                                        if ($value->type_id == 0) {
                                          
                                            $wrk_comm_train_safety .= '<li class="jq-scroller-item">
                                                <a class="jq-scroller-preview" href="'.url($value->file_path).'" data-title="'.@$value->name.'"> 
                                                    <img src="'.url($value->file_path).'" alt=""> 
                                                </a>
                                            </li>';
                                          
                                        }
                                    }
                                  
                               $wrk_comm_train_safety .= '</ul>
                            </div>';
                            $wrk_comm_train_safety .= '<i class="fa fa-arrow-circle-left jq-scroller-prev"></i>
                            <i class="fa fa-arrow-circle-right jq-scroller-next"></i>
                            <div class="jq-scroller-overlay">
                                <div class="jq-overlay-item">
                                    <i class="jq-overlay-close fa fa-times-circle"></i>
                                    <i class="fa fa-arrow-circle-left jq-scroller-overlay-prev"></i>
                                    <i class="fa fa-arrow-circle-right jq-scroller-overlay-next"></i>
                                    <div class="jq-overlay-content"></div>
                                </div>
                            </div>
                        </div>';
						}
                    } 
                    $wrk_comm_train_safety .= '<br><hr>';
                    $is_video = 0;
                    foreach ($activity as $key => $value)
                        if (in_array($value->file_type, $type_videos))
                            $is_video++;
                    if ($is_video) {
                        
                        $wrk_comm_train_safety .= '<div class="clearfix">';
                          
                            foreach ($activity as $key => $value) {
                                if (!in_array($value->file_type, $type_videos) || !is_file(BASE_PATH . "/" . $value->file_path))
                                    continue;
                                if ($value->type_id == 0) {
                                   $wrk_comm_train_safety .='<div class="" style="width:300px;height:150px;float:left;margin:10px;">
                                        <center>
                                            <video controls="controls" style="width:300px;height:150px;"><source type="'.$value->file_type.'" src="'.url($value->file_path).'" /></video>
                                            <a href="'.url($value->file_path).'" style="" download="'.$value->file_path.'">
                                                <i title="Download Video" class="fa fa-download" style="color : blue;"> Download Video</i>
                                            </a>
                                        </center>
                                    </div>';
                                }
                            }
                            
                            $wrk_comm_train_safety .= '<div class="clearfix"></div>
                        </div>';
               } 
                    $wrk_comm_train_safety .= '<br>';  
                    
                    foreach ($activity as $key => $value) {
                        if ($value->type_id == 1) {
                           
                            $wrk_comm_train_safety .= '<div class="clearfix"></div>
                                <div class="document">
                                <a href="'.url($value->file_path).'" download="'.$value->file_path.'">
                                        <i title="Download Document" class="fa fa-download"> </i> ';
                                        
                                        if ($value->name != '') {
                                            $wrk_comm_train_safety .= $value->name;
                                        } else {
                                            $wrk_comm_train_safety .= "Download Attached File";
                                        }
                                        

                                    $wrk_comm_train_safety .='</a>';

                                $wrk_comm_train_safety .='</div><div class="clearfix"></div>';
                          
                        }
                    }
                }
              
                $wrk_comm_train_safety .= '<br />';
                if (@$activity[0]->remarks != "") {
                $wrk_comm_train_safety .= '<div class="synp-heading" style="margin-left:0;">Remarks</div>' . "\n";
                $wrk_comm_train_safety .= '<div class="synp-desc">' . stripslashes($activity[0]->remarks) . '</div>' . "\n";
            }
         $wrk_comm_train_safety .='</div>';   
		 echo $wrk_comm_train_safety;
    }
	else if( $activty_type_id == 5 )
	{		 
/*********************************SITE AUDIT*********************************************************************************************/
            $siteAudit = "";
             $siteAudit .= '<p class="autor">';
            if ( @$activity[0]->audit_no != "" ) {
                $siteAudit .= '<span class="mh-meta-date updated"><i class="fa fa-bar-chart"></i> ' . $activity[0]->audit_no . '</span> &nbsp; &nbsp' . "\n";
            }
            if ( @$activity[0]->date_of_audit != "0000:00:00" ) {
                $siteAudit .= '<span class="mh-meta-date updated"><i class="fa fa-clock-o"></i> ' . date('F j, Y', strtotime($activity[0]->date_of_audit)) . '</span>' . "&nbsp; &nbsp; \n";
            }
            if ( @$activity[0]->time_of_audit_to != "00:00:00" ) {
                $siteAudit .= '<span class="mh-meta-date updated"><i class="fa fa-clock-o"></i> ' . ($activity[0]->time_of_audit_to !="" ) ? date("h:i A",strtotime($activity[0]->time_of_audit_from)).' - '.date("h:i A",strtotime($activity[0]->time_of_audit_to)).' &nbsp; &nbsp  <b>Duration </b> ('.$activity[0]->audit_duration.')' : ""  . '</span>' . "&nbsp; &nbsp; \n";
            }
            if ( $activity[0]->place != "" ) {
                $siteAudit .= ' &nbsp; &nbsp <span class="fa fa-map-marker"></span> ' . stripslashes($activity[0]->place) . '';
            }
        $siteAudit .= '</p>';
                $siteAudit .= '<div class="dept-details" style="margin-left:0;">'; 
        foreach ($devition_names as $key => $ddmrow) {
            $dptree2 = str_replace("/", " <i class='fa fa-caret-right treecaret'></i> ", trim($ddmrow));        
            $siteAudit .= '<i class="fa fa-dot-circle-o danger"></i> ' . $dptree2 . '&nbsp;&nbsp;';
        }
        $siteAudit .= '</div>'; 
             $filePath = BASE_PATH.'/'.@$activity[0]->featured_image_path;
                if (@$activity[0]->featured_image_path != "" && file_exists($filePath)) {
                    $siteAudit .= '<img src="' . url(@$activity[0]->featured_image_path) . '" alt="" width="150" height="150" class="img2">' . "\n";
                }
                else
                {
                    $siteAudit .= '<img src="' . url("admin/assets/css/cropimage/img/".$default_image[$activity[0]->activity_type_id]) . '" alt="" width="150" height="150" class="img2">' . "\n";
                    
                }
        //$siteAudit .= '<br>';
        $siteAudit .= '<table class="border-less">';
        if(@$activity[0]->date_of_audit != "0000-00-00") {
        $siteAudit .= '<tr><td style="width:38%;"><b>Date of Audit</b></td><td> : </td><td>'.date("F d, Y",strtotime(@$activity[0]->date_of_audit)).'</td></tr>';
        }
        if(@$activity[0]->time_of_audit_from != "00:00:00" && @$activity[0]->time_of_audit_to != "00:00:00") {
        $siteAudit .= '<tr><td style="width:38%;"><b>Time of Audit</b></td><td> : </td><td>'.date("h:i A",strtotime(@$activity[0]->time_of_audit_from)).' - '.date("h:i A",strtotime(@$activity[0]->time_of_audit_to)).' - <b>Duration </b> ('.$activity[0]->audit_duration.')</td></tr>';
        }
        if(@$activity[0]->place != "") {
        $siteAudit .= '<tr><td style="width:38%;"><b>Venue</b></td><td> : </td><td>'.@$activity[0]->place.'</td></tr>';
        }
        $siteAudit .= '<tr><td style="width:38%;"><b>SAS Report No.</b></td><td> : </td><td>'.@$activity[0]->sas_report_no.'</td></tr>';
        
        $siteAudit .= '<tr><td><b>Average Marks</b></td><td> : </td>';
        $siteAudit .= '<td>'.@$activity[0]->avg_mark.'</td></tr>';
      
        $siteAudit .= '<tr><td><b>Major Violation</b></td><td> : </td>';
        $siteAudit .= '<td>'.((@$activity[0]->major_violation == 'Y') ? "YES" : "NO").'</td></tr>';
        
        if(@$activity[0]->major_violation == 'Y') {
        $siteAudit .= '<tr><td><b>No. of Violation</b></td><td> : </td>';
        $siteAudit .= '<td>'.@$activity[0]->no_of_violation ;
        
        $siteAudit .= '<div id="popup" class="modal modalbox" role="dialog">
		<div class="modal-dialog"><div class="modal-content">
                
      <div class="modal-header clearfix">
       <a href="#" style ="float:right;" class="js-modal-close">&times;</a>
        
        <h4 class="modal-title">Violation Details</h4>
      </div>
      <div class="modal-body" style="max-height:320px; overflow:auto;">
        <table class="table table-bordered table-hover">
			<tr class="bg-primary">
                         <th>Sl.No</th>
                        <th>Violation Type</th>
                        <th>Description of Violation</th>
                        <th>Violation Category</th>
                        <th>Remarks</th>
                        </tr>';

                        if( count($violation_rows) )
                        {
                        $f = 0;
                        foreach($violation_rows as $row)
                        {
                        $f = $f+1;

                        $siteAudit .='<tr class="removetr" id="message_'.$row->id.'">

                        <td align="center" width="10%">'.$f.'</td>
                        <td align="center" width="30%">'.$row->violation_type.'</td>
                        <td align="center" width="80%">'.$row->description.'</td>
                        <td align="center" width="20%">'.$row->violation_category.'</td>
                        <td align="center" width="30%">'.$row->remarks.'</td>                        
                        </tr>';
                        }
                        }
        $siteAudit .='</table>
            </div>
            <div class="modal-footer">
              <a href="#" class="btn btn-small btn-primary js-modal-close">Close</a>
            </div>
          </div>

        </div>
      </div>';
        if(@$activity[0]->no_of_violation > 0 ) {
        $siteAudit .= ' <a href="#" class="js-open-modal btn btn-info btn-xs" data-modal-id="popup"> <i class="fa fa-eye"></i> View All</a>';
        }
        $siteAudit .= '</td></tr>';
	
		
        }
       
        $siteAudit .= '<tr><td><b>Job Stop Request Raised</b></td><td> : </td>';
        $siteAudit .= '<td>'.((@$activity[0]->job_stop_req_raised == 'Y') ? "YES" : "NO").'</td></tr></table><div class="clearfix"></div>';
         $siteAudit .= '<div class="clearfix"></div>';   
               
                if (@$activity[0]->file_type != "") {
                    $is_image = 0;
                    foreach ($activity as $key => $value)
                        if (in_array($value->file_type, $type_images))
                            $is_image++;
					
                    if ($is_image) {
                        if ($is_image > 1 ) {
                        $siteAudit .= '<div class="jq-scroller-wrapper">
                            <div class="jq-scroller1">
                                <ul class="scroller jq-scroller-mover">'; 
                                   
                                    foreach ($activity as $key => $value) {
                                        if (!in_array($value->file_type, $type_images) || !is_file(BASE_PATH . "/" . $value->file_path))
                                            continue;
                                        if ($value->type_id == 0) {
                                          
                                            $siteAudit .= '<li class="jq-scroller-item">
                                                <a class="jq-scroller-preview" href="'.url($value->file_path).'" data-title="'.@$value->name.'"> 
                                                    <img src="'.url($value->file_path).'" alt=""> 
                                                </a>
                                            </li>';
                                          
                                        }
                                    }
                                  
                               $siteAudit .= '</ul>
                            </div>';
                            $siteAudit .= '<i class="fa fa-arrow-circle-left jq-scroller-prev"></i>
                            <i class="fa fa-arrow-circle-right jq-scroller-next"></i>
                            <div class="jq-scroller-overlay">
                                <div class="jq-overlay-item">
                                    <i class="jq-overlay-close fa fa-times-circle"></i>
                                    <i class="fa fa-arrow-circle-left jq-scroller-overlay-prev"></i>
                                    <i class="fa fa-arrow-circle-right jq-scroller-overlay-next"></i>
                                    <div class="jq-overlay-content"></div>
                                </div>
                            </div>
                        </div>';
						}
                    } 
                    $siteAudit .= '<br>';
                    $is_video = 0;
                    foreach ($activity as $key => $value)
                        if (in_array($value->file_type, $type_videos))
                            $is_video++;
                    if ($is_video) {
                        
                        $siteAudit .= '<div class="clearfix">';
                          
                            foreach ($activity as $key => $value) {
                                if (!in_array($value->file_type, $type_videos) || !is_file(BASE_PATH . "/" . $value->file_path))
                                    continue;
                                if ($value->type_id == 0) {
                                   $siteAudit .='<div class="" style="width:300px;height:150px;float:left;margin:10px;">
                                        <center>
                                            <video controls="controls" style="width:300px;height:150px;"><source type="'.$value->file_type.'" src="'.url($value->file_path).'" /></video>
                                            <a href="'.url($value->file_path).'" style="" download="'.$value->file_path.'">
                                                <i title="Download Video" class="fa fa-download" style="color : blue;"> Download Video</i>
                                            </a>
                                        </center>
                                    </div>';
                                }
                            }
                            
                            $siteAudit .= '<div class="clearfix"></div>
                        </div>';
               } 
                     
                    
                    foreach ($activity as $key => $value) {
                        if ($value->type_id == 1) {
                           
                            $siteAudit .= '<div class="clearfix"></div>
                                <div class="document">
                                <a href="'.url($value->file_path).'" download="'.$value->file_path.'">
                                        <i title="Download Document" class="fa fa-download"> </i> ';
                                        
                                        if ($value->name != '') {
                                            $siteAudit .= $value->name;
                                        } else {
                                            $siteAudit .= "Download Attached File";
                                        }
                                        

                                    $siteAudit .='</a>';

                                $siteAudit .='</div><div class="clearfix"></div>';
                          
                        }
                    }
                }
        
        if (@$activity[0]->remarks != "") {
                $siteAudit .= '<div class="synp-heading" style="margin-left:0;">Remarks</div>' . "\n";
                $siteAudit .= '<div class="synp-desc">' . stripslashes($activity[0]->remarks) . '</div>' . "\n";
            }
		echo $siteAudit;	
    } 
    else if( $activty_type_id == 7 )
	{    
    /*****************************PPE AUDIT**********************************************************************************************/
        $ppeAudit = "";
        
        $ppeAudit .= '<p class="autor">';
            if ( @$activity[0]->audit_no != "" ) {
                $ppeAudit .= '<span class="mh-meta-date updated"><i class="fa fa-bar-chart"></i> ' . $activity[0]->audit_no . '</span> &nbsp; &nbsp' . "\n";
            }
            if ( @$activity[0]->date_of_audit != "" ) {
                $ppeAudit .= '<span class="mh-meta-date updated"><i class="fa fa-clock-o"></i> ' . date('F j, Y', strtotime($activity[0]->date_of_audit)) . '</span>' . "&nbsp; &nbsp; \n";
            }
            if ( @$activity[0]->time_of_audit_to != "" ) {
                $ppeAudit .= '<span class="mh-meta-date updated"><i class="fa fa-clock-o"></i> ' . ($activity[0]->time_of_audit_to !="" ) ? date("h:i A",strtotime($activity[0]->time_of_audit_from)).' - '.date("h:i A",strtotime($activity[0]->time_of_audit_to)).' &nbsp; &nbsp; <b>Duration </b> ('.$activity[0]->audit_duration.')' : ""  . '</span>' . "&nbsp; &nbsp; \n";
            }
            if ( $activity[0]->place != "" ) {
                $ppeAudit .= '&nbsp; &nbsp; <span class="fa fa-map-marker"></span> ' . stripslashes($activity[0]->place) . '';
            }
        $ppeAudit .= '</p>';
                $ppeAudit .= '<div class="dept-details" style="margin-left:0;">'; 
        foreach ($devition_names as $key => $ddmrow) {
            $dptree2 = str_replace("/", " <i class='fa fa-caret-right treecaret'></i> ", trim($ddmrow));        
            $ppeAudit .= '<i class="fa fa-dot-circle-o danger"></i> ' . $dptree2 . '&nbsp;&nbsp;';
        }
        $ppeAudit .= '</div>'; 
             $filePath = BASE_PATH.'/'.@$activity[0]->featured_image_path;
                if (@$activity[0]->featured_image_path != "" && file_exists($filePath)) {
                    $ppeAudit .= '<img src="' . url(@$activity[0]->featured_image_path) . '" alt="" width="150" height="150" class="img2">' . "\n";
                }
                else
                {
                    $ppeAudit .= '<img src="' . url("admin/assets/css/cropimage/img/".$default_image[$activity[0]->activity_type_id]) . '" alt="" width="150" height="150" class="img2">' . "\n";
                    
                }
                
      $ppeAudit .= '<table class="border-less">';
        if(@$activity[0]->date_of_audit != "0000-00-00") {
        $ppeAudit .= '<tr><td style="width:38%;"><b>Date of Audit</b></td><td> : </td><td>'.date("F d, Y",strtotime(@$activity[0]->date_of_audit)).'</td></tr>';
        }
        if(@$activity[0]->time_of_audit_from != "00:00:00" && @$activity[0]->time_of_audit_to != "00:00:00") {
        $ppeAudit .= '<tr><td style="width:38%;"><b>Time of Audit</b></td><td> : </td><td>'.date("h:i A",strtotime(@$activity[0]->time_of_audit_from)).' - '.date("h:i A",strtotime(@$activity[0]->time_of_audit_to)).' - <b>Duration </b> ('.$activity[0]->audit_duration.')</td></tr>';
        }
        if(@$activity[0]->place != "") {
        $ppeAudit .= '<tr><td style="width:38%;"><b>Venue</b></td><td> : </td><td>'.@$activity[0]->place.'</td></tr>';
        }
        $ppeAudit .= '<tr><td style="width:38%;"><b>SAS Report No.</b></td><td> : </td><td>'.@$activity[0]->sas_report_no.'</td></tr>';
        
        $ppeAudit .= '<tr><td><b>Average Marks</b></td><td> : </td>';
        $ppeAudit .= '<td>'.@$activity[0]->avg_mark.'</td></tr>';
        
        $ppeAudit .= '<tr><td><b>Major Deficiency</b></td><td> : </td>';
        $ppeAudit .= '<td>'.((@$activity[0]->major_deviation== 'Y') ? "YES" : "NO").'</td></tr>';
        
        $ppeAudit .= '<tr><td><b>No. of Deficiency</b></td><td> : </td>';
        $ppeAudit .= '<td>'.@$activity[0]->no_of_deviation ;
        if(@$activity[0]->no_of_deviation > 0) {
        $ppeAudit .= ' &nbsp; <a href="#" class="js-open-modal btn" data-modal-id="popup"> <i class="fa fa-eye"></i> View All</a>';
        }
        $ppeAudit .= '<div id="popup" class="modal modalbox" role="dialog">
                    <div class="modal-dialog"><div class="modal-content">
      <div class="modal-header clearfix">
        
         <a href="#" style="float:right;"  class="js-modal-close">&times;</a>
        <h4 class="modal-title">Deficiency Details</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-hover">
			<tr class="bg-primary">
                        <th>Sl.No</th>
                        <th>Description of Deficiency</th>
                        <th>Quantity of Deficiency</th>
                        <th>Remarks</th>
                        </tr>';

                        if( count($deviation_rows) )
                        {
                        $f = 0;
                        foreach($deviation_rows as $row)
                        {
                        $f = $f+1;

                        $ppeAudit .='<tr class="removetr" id="message_'.$row->id.'">

                        <td align="center" width="10%">'.$f.'</td>
                        <td align="center" width="30%">'.$row->description.'</td>
                        <td align="center" width="20%">'.$row->qty.'</td>
                        <td align="center" width="30%">'.$row->remarks.'</td>                        
                        </tr>';
                        }
                        }
        $ppeAudit .='</table>
            </div>
            <div class="modal-footer">
              <a href="#" class="btn btn-small btn-primary js-modal-close">Close</a>
            </div>
          </div>

        </div>
      </div>';
        $ppeAudit .= '</td></tr>';
		
		$ppeAudit .= '</table>';
		
        
        $ppeAudit .= '<br>';    
        $ppeAudit .= '<div class="clearfix"></div>';    
        $ppeAudit .= '<br>';         
                if (@$activity[0]->file_type != "") {
                    $is_image = 0;
                    foreach ($activity as $key => $value)
                        if (in_array($value->file_type, $type_images))
                            $is_image++;
                    if ($is_image) {
                        if( $is_image > 1 ) {
                        $ppeAudit .= '<div class="jq-scroller-wrapper">
                            <div class="jq-scroller1">
                                <ul class="scroller jq-scroller-mover">'; 
                                   
                                    foreach ($activity as $key => $value) {
                                        if (!in_array($value->file_type, $type_images) || !is_file(BASE_PATH . "/" . $value->file_path))
                                            continue;
                                        if ($value->type_id == 0) {
                                          
                                            $ppeAudit .= '<li class="jq-scroller-item">
                                                <a class="jq-scroller-preview" href="'.url($value->file_path).'" data-title="'.@$value->name.'"> 
                                                    <img src="'.url($value->file_path).'" alt=""> 
                                                </a>
                                            </li>';
                                          
                                        }
                                    }
                                  
                               $ppeAudit .= '</ul>
                            </div>';
                            $ppeAudit .= '<i class="fa fa-arrow-circle-left jq-scroller-prev"></i>
                            <i class="fa fa-arrow-circle-right jq-scroller-next"></i>
                            <div class="jq-scroller-overlay">
                                <div class="jq-overlay-item">
                                    <i class="jq-overlay-close fa fa-times-circle"></i>
                                    <i class="fa fa-arrow-circle-left jq-scroller-overlay-prev"></i>
                                    <i class="fa fa-arrow-circle-right jq-scroller-overlay-next"></i>
                                    <div class="jq-overlay-content"></div>
                                </div>
                            </div>
                        </div>';
						}
                    } 
                    $ppeAudit .= '<br>';
                    $is_video = 0;
                    foreach ($activity as $key => $value)
                        if (in_array($value->file_type, $type_videos))
                            $is_video++;
                    if ($is_video) {
                        
                        $ppeAudit .= '<div class="clarfix">';
                          
                            foreach ($activity as $key => $value) {
                                if (!in_array($value->file_type, $type_videos) || !is_file(BASE_PATH . "/" . $value->file_path))
                                    continue;
                                if ($value->type_id == 0) {
                                   $ppeAudit .='<div class="" style="width:300px;height:150px;float:left;margin:10px;">
                                        <center>
                                            <video controls="controls" style="width:300px;height:150px;"><source type="'.$value->file_type.'" src="'.url($value->file_path).'" /></video>
                                            <a href="'.url($value->file_path).'" style="" download="'.$value->file_path.'">
                                                <i title="Download Video" class="fa fa-download" style="color : blue;"> Download Video</i>
                                            </a>
                                        </center>
                                    </div>';
                                }
                            }
                            
                            $ppeAudit .= '<div class="clearfix"></div>
                        </div>';
               } 
                    $ppeAudit .= '<br>';  
                    
                    foreach ($activity as $key => $value) {
                        if ($value->type_id == 1) {
                            $filePath = BASE_PATH.'/'.$value->file_path;
                        if( file_exists($filePath) && $value->file_path !="" ) {
                            $ppeAudit .= '<div class="clarfix"></div>
                                <div class="document">
                                <a href="'.url($value->file_path).'" download="'.$value->file_path.'">
                                        <i title="Download Document" class="fa fa-download"> </i> ';
                                        
                                        if ($value->name != '') {
                                            $ppeAudit .= $value->name;
                                        } else {
                                            $ppeAudit .= " Download Attached File";
                                        }
                                        

                                    $ppeAudit .='</a>';

                                $ppeAudit .='</div><div class="clearfix"></div>';
                        }
                          
                        }
                    }
                }
            
            if (@$activity[0]->remarks != "") {
                $ppeAudit .= '<div class="synp-heading" style="margin-left:0;">Remarks</div>' . "\n";
                $ppeAudit .= '<div class="synp-desc">' . stripslashes($activity[0]->remarks) . '</div>' . "\n";
            }
			
			echo $ppeAudit;
	}    
      
      
      
       
        ?>
      	
    </div>
    <div class="mh-widget-col-1 mh-sidebar">
        <?php         
        include 'upcomingevent.php'; 
        include("statistical_data.php"); 
        include("rightsidebar.php");
        ?>        
    </div>
<?php $presCtr->get_footer(); ?>
    
    
    <script>
        var $m = jQuery.noConflict();
        $m(function () {
            var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
            $m('a[data-modal-id]').click(function (e) {
                e.preventDefault();
                $m("body").append(appendthis);
                $m(".modal-overlay").fadeTo(500, 0.7);
                var modalBox = $(this).attr('data-modal-id');
                $m('#' + modalBox).fadeIn($(this).data());
            });

            $m(".js-modal-close, .modal-overlay").click(function () {
                $m(".modalbox, .modal-overlay").fadeOut(500, function () {
                    $m(".modal-overlay").remove();
                });
                return false;
            });

            $m(window).resize(function () {
                $m(".modalbox").css({
                    //top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
                    // left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                    //left: "20%"
                });
            });

            $m(window).resize();

        });
    </script>
 
<script>
function goBack() {
   <?php if($_REQUEST['mode'] == 'srch') { ?>
$('#serachform').submit()              
   <?php } else { ?>
       window.history.go(-1);
   <?php } ?> 
}
</script>
