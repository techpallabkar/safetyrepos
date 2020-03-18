<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$controller 				= load_controller( "ActivityController" );
$controller->doAction();
$beanUi 				= $controller->beanUi;
$page 					= $beanUi->get_view_data( "page" );
$allactivities				= $beanUi->get_view_data( "allactivities" );
$posts_paggin_html                      = $beanUi->get_view_data( "posts_paggin_html" );
$post_status 				= $beanUi->get_view_data( "post_status" );
$search_title 				= $beanUi->get_view_data( "search_title" );
$status_id                              = $beanUi->get_view_data( "status_id" );
$post_activity_type_master              = $beanUi->get_view_data( "post_activity_type_master" );
$activity_type_id 			= $beanUi->get_view_data("activity_type_id");
$division_department                    = $beanUi->get_view_data("division_department");
$devition_names 			= $beanUi->get_view_data("devition_names");
$activity_participants                  = $beanUi->get_view_data("activity_participants");
$participants_list 			= $beanUi->get_view_data("participants_list");
$activity                               = $beanUi->get_view_data("activity");
$deviation_rows                         = $beanUi->get_view_data("deviation");
$alldeviationsactionfile                = $beanUi->get_view_data("alldeviationsactionfile");
$violation_rows                         = $beanUi->get_view_data("violation");
$violationnm                            = $beanUi->get_view_data("violationnm");

$get_auth_user_id                       = $beanUi->get_view_data("get_auth_user_id");
$role_id                                = $beanUi->get_view_data("role_id");

$type_images 				= array("image/jpg", "image/jpeg", "image/png");
$type_videos 				= array("video/mp4", "video/quicktime");
$pdf 						= array("application/pdf");
$activities 				= get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);
$controller->get_header();
$site_root_url 				= dirname(url());
?>
<link type="text/css" media="all" href="<?php echo url("assets/css/jquery-scrollify-style.css"); ?>" rel="stylesheet" />
<style type="text/css">
    a.edit-btn{
        padding: 5px 5px;
    }
</style>
<script type="text/javascript" src="<?php echo url("assets/js/jquery_0034.js"); ?>"></script>
	<script> 
		var $k = jQuery.noConflict();
		$k(document).ready(function(){
			$k('.scroller').scrollify(); 
			
			$k("#postlist #ids").click(function(){
				$k(this).parents().toggleClass("selected");
			});
		
		});
	</script> 

<div class="container1">
	<?php echo $beanUi->get_message(); ?>
    <h1 class="heading"><?php echo $activities; ?>
  <script>
		function backnow()
		{
			
		if(history.length>1)
		{
			window.location.href ="index.php?status_id=<?php echo $_REQUEST["status_id"]; ?>&districtid=<?php echo $_REQUEST["districtid"]; ?>&activity_no=<?php echo $_REQUEST["activity_no"]; ?>&search_title=<?php echo $_REQUEST["search_title"]; ?>&fromdate=<?php echo $_REQUEST["fromdate"]; ?>&todate=<?php echo $_REQUEST["todate"]; ?>&page=<?php echo $_REQUEST["page"]; ?>&activity_id=<?php echo $activity[0]->activity_type_id; ?>";
		}
		else
		{
			window.close();
		}
	}
    </script>
    <?php
    //show($activity[0]);
        echo '<a onclick="return backnow()" class="btn btn-danger btn-sm">Back</a>'; 
//        if ($role_id == 2 && ($activity[0]->status_id == 1)) {
//            if (($activity[0]->created_by == $get_auth_user_id) && ($activity[0]->status_id == 1)) {
//                echo '<a class="btn btn-success btn-xs edit-btn"  href="'.page_link('activitynew/add_audit_new.php').'?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activty_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&auditId=' . $activity[0]->auditid .'&activity_id=' . $activity[0]->activity_type_id. '"><i class="fa fa-pencil"></i> Edit</a>'; 
//            }
//        }
//        
//        if ($role_id == 1 || $role_id == 3) {
//            if (($activity[0]->created_by == $get_auth_user_id) && ($activity[0]->status_id == 1)) {
//                echo '<a class="btn btn-success btn-xs edit-btn"  href="'.page_link('activitynew/add_audit_new.php').'?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activty_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&auditId=' . $activity[0]->auditid .'&activity_id=' . $activity[0]->activity_type_id. '"><i class="fa fa-pencil"></i> Edit</a>'; 
//            } else if (($activity[0]->created_by != $get_auth_user_id) && ($activity[0]->status_id != 1)) {
//                echo '<a class="btn btn-success btn-xs edit-btn"  href="'.page_link('activitynew/add_audit_new.php').'?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activty_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&auditId=' . $activity[0]->auditid .'&activity_id=' . $activity[0]->activity_type_id. '"><i class="fa fa-pencil"></i> Edit</a>'; 
//            } else if (($activity[0]->created_by == $get_auth_user_id) && ($activity[0]->status_id == 2 || $activity[0]->status_id == 3 || $activity[0]->status_id == 4)) {
//                echo '<a class="btn btn-success btn-xs edit-btn"  href="'.page_link('activitynew/add_audit_new.php').'?status_id=' . $_REQUEST["status_id"] . '&districtid=' . $_REQUEST["districtid"] . '&activity_no=' . $_REQUEST["activty_no"] . '&search_title=' . $_REQUEST["search_title"] . '&fromdate=' . $_REQUEST["fromdate"] . '&todate=' . $_REQUEST["todate"] . '&page=' . $_REQUEST["page"] . '&auditId=' . $activity[0]->auditid .'&activity_id=' . $activity[0]->activity_type_id. '"><i class="fa fa-pencil"></i> Edit</a>'; 
//            }
//        }
                ?>
    </h1> 
	<div class="message"></div>
<div id="main-content" class="mh-content mh-home-content clearfix">
		
		<div class="clearfix"></div>		
		<table class="table table-bordered">
			<tr>
			<td> <b>Participating Department : </b></td>
			<td>
					<?php
					if( !empty ( $devition_names ) )
					{
					?>
					
					<?php
					$j=0;
					$valxx = array();
					foreach($devition_names as $key => $ddmrow)
					{
					$j = $j+1;
					echo '<b>'.$ddmrow.'&nbsp;&nbsp;</b>';
					}
				}
					?>
			</td>
			</tr>
		<tr>
		<td width="20%"><b>Audit No : </b></td>
		<td> <?php echo ($activity[0]->audit_no !="" ) ? $activity[0]->audit_no : "" ;?></td>
		</tr>
		<tr>
		<td width="20%"><b>Date of Audit : </b></td>
		<td> <?php echo ($activity[0]->date_of_audit !="0000-00-00" ) ? date("d-m-Y",strtotime($activity[0]->date_of_audit)) : "" ;?></td>
		</tr>
		<tr>
		<td width="20%"><b>Time of Audit : </b></td>
		<td> <?php echo ($activity[0]->time_of_audit_to !="00:00:00" ) ? date("h:i A",strtotime($activity[0]->time_of_audit_from)).' - '.date("h:i A",strtotime($activity[0]->time_of_audit_to)).'  <b>Duration </b> ('.$activity[0]->audit_duration.')' : "" ;?></td>
		</tr>
		<tr>
		<td width="20%"><b>Venue : </b></td>
		<td> <?php echo ($activity[0]->place !="" ) ? '<span class="fa fa-map-marker"></span> '.($activity[0]->place) : "" ;?></td>
		</tr>
                <?php if($activity[0]->is_oth_dept_audit == 1){ ?>
                <tr>
                <td width="20%"><b>Ref/Job No. : </b></td>
                <td> <?php echo ($activity[0]->ref_job_no !="" ) ? ($activity[0]->ref_job_no) : "" ;?></td>
                </tr>
                <?php } ?>
                <tr>
                <td width="20%"><b>Job Title : </b></td>
                <td> <?php echo ($activity[0]->job_title !="" ) ? ($activity[0]->job_title) : "" ;?></td>
                </tr>
                <?php if($activity[0]->is_oth_dept_audit == 0){ ?>
		<tr>
		<td width="20%"><b>SAS Report No. : </b></td>
		<td> <?php echo ($activity[0]->sas_reportno !="" ) ? ($activity[0]->sas_reportno) : "" ;?></td>
		</tr>
		<tr>
		<td><b>Attached Office Files :</b></td>
		<td>
			<?php
				if($activity[0]->file_type !="" ) {
				foreach ($activity as $key => $value) {
				if($value->type_id == 1) {
                                    $filePath = CESC_BASE_PATH.'/'.@$value->file_path;
				?>
				<div class="clarfix">
				<div class="" style="float:left;margin:10px;">
				<center>
                                    <?php if(file_exists($filePath)) { ?>
				<a href="<?php echo $site_root_url.'/'.$value->file_path;?>" style="" download="<?php echo $site_root_url.'/'.$value->file_path;?>">
				<i title="Download Attachment" class="fa fa-download" style="color : blue;"> <?php echo  $value->name; ?></i>
				</a>
                                    <?php } ?>
				</center>
				</div>
				
				</div>
				<?php 
				} 
				}  
				}
			?>
		</td>
		</tr>
                
		<tr>
		<td width="20%"><b>Attached Images : </b></td>
                <!--(Mains)-->
		<td> 
                    <?php
                    if($activity[0]->file_type !="" ) {
                    $is_image = 0;
                    foreach ($activity as $key => $value) if ( in_array($value->file_type, $type_images) ) $is_image++;
                    if ($is_image) {
                    ?>

                    <div class="jq-scroller-wrapper">
                    <div class="jq-scroller1">
                    <ul class="scroller jq-scroller-mover"> 
                    <?php 
                    foreach ($activity as $key => $value) {
                    if( ! in_array($value->file_type, $type_images) && ! is_file( $site_root_url. "/" . $value->file_path ) )
                    {
                    continue;
                    }
                    if($value->type_id == 0) {
                    if($value->sas_reportno == "") {
                    ?>
                    <li class="jq-scroller-item">
                    <a class="jq-scroller-preview" href="<?php echo $site_root_url.'/'.$value->file_path;?>" data-title="<?php echo @$value->name ?>"> 
                    <img src="<?php echo $site_root_url.'/'.$value->file_path;?>" alt=""> 
                    </a>
                    </li>
                    <?php } }  }  ?>
                    </ul>
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
                    </div>
                    <?php 
                    } 
                    }
                    ?>
		</td>
		</tr>
		<tr>
		<td width="20%"><b>SAS Images : </b></td>
		<td> 
                    <?php
                    if($activity[0]->file_type !="" ) {
                    $is_image = 0;
                    foreach ($activity as $key => $value) if ( in_array($value->file_type, $type_images) ) $is_image++;
                    if ($is_image) {
                    ?>

                    <div class="jq-scroller-wrapper">
                    <div class="jq-scroller1">
                    <ul class="scroller jq-scroller-mover"> 
                    <?php 
                    foreach ($activity as $key => $value) {
                    if( ! in_array($value->file_type, $type_images) && ! is_file( $site_root_url. "/" . $value->file_path ) )
                    {
                    continue;
                    }
                    if($value->type_id == 0) {
                    if($value->sas_reportno != "") {
                    ?>
                    <li class="jq-scroller-item">
                    <a class="jq-scroller-preview" href="<?php echo MIS_IMAGE_PATH_SA.$value->file_path;?>" data-title="<?php echo @$value->name ?>"> 
                    <img src="<?php echo MIS_IMAGE_PATH_SA.$value->file_path;?>" alt=""> 
                    </a>
                    </li>
                    <?php } }  }  ?>
                    </ul>
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
                    </div>
                    <?php 
                    } 
                    }
                    ?>
		</td>
		</tr>
                <?php } ?>
                <?php if($activity[0]->is_oth_dept_audit == 1){ ?>
		<tr>
		<td width="20%"><b>Attached Images : </b></td> 
                <!--(Non-Mains & Generation)-->
		<td> 
                    <?php
                    if(!empty($alldeviationsactionfile)) {
                    $is_image = 0;
                    foreach ($alldeviationsactionfile as $key => $value) if ( in_array($value->file_type, $type_images) ) $is_image++;
                    if ($is_image) {
                    ?>

                    <div class="jq-scroller-wrapper">
                    <div class="jq-scroller1">
                    <ul class="scroller jq-scroller-mover"> 
                    <?php 
                    foreach ($alldeviationsactionfile as $key => $value) {
                    if( ! in_array($value->file_type, $type_images) && ! is_file( '/'.FOLDERNAME.'/'.$value->file_path ) )
                    {
                    continue;
                    }
                    
                    if(!empty($alldeviationsactionfile)) {
                    ?>
                    <li class="jq-scroller-item">
                    <a class="jq-scroller-preview" href="<?php echo '/'.FOLDERNAME.'/'.$value->file_path;?>" data-title="<?php echo @$value->name ?>"> 
                    <img src="<?php echo '/'.FOLDERNAME.'/'.$value->file_path;?>" alt=""> 
                    </a>
                    </li>
                    <?php } }  ?>
                    </ul>
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
                    </div>
                    <?php 
                    } 
                    }
                    ?>
		</td>
                <tr>
		<td width="20%"><b>Attached File : </b></td> 
                <!--(Non-Mains & Generation)-->
		<td>
                    <?php if(!empty($alldeviationsactionfile)) {
                    $is_file = 0;?>
                    <div>
                    <div>
                    <ol>
                    <?php                    
                    foreach ($alldeviationsactionfile as $key => $value){ 
                        if ( in_array($value->file_type, $pdf) )
                    {
                    ?>
                    <li>
                    <a href="<?php echo '/'.FOLDERNAME.'/'.$value->file_path;?>" data-title="<?php echo @$value->name ?>" target="_blank" download>
                     <i class="fa fa-download"></i> <?php echo ($value->name?$value->name:'New File').' '; ?>
                    </a>
                    </li>
                    <?php 
                    } 
                    }
                    ?>
                    </ol>
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
                    </div>
                     <?php 
                    }
                    ?>
		</td>
		</tr>
                <?php } ?>
                <?php if($activity[0]->is_oth_dept_audit == 0){ ?>
		<tr>
		<td width="20%"><b>Attached Video`s : </b></td>
		<td> 
			
				  
				<?php
				if($activity[0]->file_type !="" ) {
				$is_video = 0;
				foreach ($activity as $key => $value) if ( in_array($value->file_type, $type_videos) ) $is_video++;
				if ($is_video)
				{
				?>
				<div class="synp-heading" style="margin-left:0;">Videos</div>
				<div class="clarfix">
				<?php 
				foreach ($activity as $key => $value) 
				{
				if( ! in_array($value->file_type, $type_videos) && ! is_file( $site_root_url . "/" . $value->file_path ) ) continue;
				if($value->type_id == 0) 
				{
				?>
				<div class="" style="width:300px;height:150px;float:left;margin:10px;">
				<video controls="controls" style="width:300px;height:150px;"><source type="<?php echo $value->file_type; ?>" src="<?php echo $site_root_url.'/'.$value->file_path;?>" /></video>
				</div>
				<?php 
				}  
				}  ?>
				<div class="clearfix"></div>
				</div>
				<?php 
				} 
				

				}
				?>
		
		
		</td>
		</tr>
                <?php } ?>
		<tr>
		<td><b>Featured Image : </b></td>
		<td><?php 
			if($activity[0]->featured_image_path !="" ) 
			{
				echo '<div style="float:left;"><img style="float:left;" src="'.$site_root_url.'/'.$activity[0]->featured_image_path.'" alt="" width="150" height="150" class="img2">'."</div>\n";
			}
                         else {
                                echo '<div style="float:left;"><img style="float:left;padding:2px;border:1px solid #d0d0d0;" src="'.$site_root_url.'/admin/assets/css/cropimage/img/audit.png" alt="" width="150" height="150" class="img2">'."</div>\n";
                        }
			?></td>
		</tr>
		
		
		<tr>
		<td><b>% of Marks : </b></td>
		<td><?php echo ($activity[0]->avg_mark != "") ? $activity[0]->avg_mark : ""; ?></td>
		</tr>
                <?php if($activity[0]->is_oth_dept_audit == 0){ ?>
		<tr>
		<td><b>Violation : </b></td>
		<td><?php echo ($activity[0]->major_violation == "Y") ? "Yes" : "No"; ?></td>
		</tr>
		<tr>
		<td><b>No. of Deviation : </b></td>
		<td><?php echo ($activity[0]->no_of_violation) ? $activity[0]->no_of_violation : "";
		echo ' <a href="" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">View All</a>';
		 ?>
		
                <!-- Modal -->
                <div id="myModal" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Deviation Details</h4>
                      </div>
                      <div class="modal-body">
                        <table class="table table-bordered">
                            <tr class="bg-primary">

                            <th>Sl.No</th>
                            <th>Violation Type</th>
                            <th>Description of Violation</th>
                            <th>Violation Category</th>
                            <th>Remarks</th>
                        </tr>
                        <?php
                        if (count($violation_rows)) {
                            $f = 0;
                            foreach ($violation_rows as $row) {
                                $f = $f + 1;
                                ?>
                                <tr class="removetr" id="message_<?php echo $row->id; ?>">
                                <td align="center" width="10%"><?php echo $f; ?></td>
                                <td align="center" width="20%"><?php echo $row->violation_type; ?></td>
                                <td align="center" width="30%"><?php echo $row->description; ?></td>
                                <td align="center" width="20%"><?php echo $row->violation_category; ?></td>
                                <td align="center" width="30%"><?php echo $row->remarks; ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                        </table>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
		</td>
		</tr>
                <?php } ?>
                <?php if($activity[0]->is_oth_dept_audit == 1){ ?>
		<tr>
		<td><b>No. of Deviation : </b></td>
		<td><?php echo ($activity[0]->no_of_violation) ? $activity[0]->no_of_violation : "";
		echo ' <a href="" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">View All</a>';
		 ?>
		
                <!-- Modal -->
                <div id="myModal" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Deviation Details</h4>
                      </div>
                      <div class="modal-body">
                        <table class="table table-bordered">
                            <tr class="bg-primary">

                            <th style="width:7%;">Sl. No.</th>
                                <th style="width:20%;">Deviation No</th>
                                <th style="width:10%;">Deviation Type</th>
                                <th style="width:30%;">Category</th>
                                <th style="width:40%;">Observation</th>
                        </tr>
                        <?php 
                        if (count($violationnm)) {
                            $sl = 0;
                            foreach ($violationnm as $row => $value) {
                                $sl = $sl + 1;
                                ?>
                            <tr>
                                <td class="text-center"><?php echo $sl; ?></td>
                                <td class="text-center"><?php echo $value->deviation_no; ?></td>
                                <td class="text-center"><?php echo $value->type_name; ?></td>
                                <td class="text-center"><?php echo $value->category_name; ?></td>
                                <td><?php echo $value->observation; ?></td>
                            </tr>
                        <?php
                            }
                        }
                        ?>
                        </table>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
		</td>
		</tr>
                <?php } ?>
		<tr>
		<td><b>Remarks : </b></td>
		<td><?php echo ($activity[0]->remarks) ? $activity[0]->remarks : ""; ?></td>
		</tr>
		</table>	
                <br>      
		</table> 	
	</div>
	<hr />
<?php echo $posts_paggin_html; ?>
</div>
<?php $controller->get_footer(); ?>
<script>
var $m = jQuery.noConflict();
$m(function(){
var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
	$m('a[data-modal-id]').click(function(e) {
                e.preventDefault();
                $m("body").append(appendthis);
                $m(".modal-overlay").fadeTo(500, 0.7);
				var modalBox = $(this).attr('data-modal-id');
				$m('#'+modalBox).fadeIn($(this).data());
				});  
$m(".js-modal-close, .modal-overlay").click(function() {
    $m(".modal-box, .modal-overlay").fadeOut(500, function() {
        $m(".modal-overlay").remove();
    });
return false;
});
});
</script>
</body>
</html>
