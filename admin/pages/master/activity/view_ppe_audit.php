<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$controller 				= load_controller( "ActivityController" );
$controller->doAction();
$beanUi 					= $controller->beanUi;
$page 						= $beanUi->get_view_data( "page" );
$allactivities				= $beanUi->get_view_data( "allactivities" );
$posts_paggin_html  		= $beanUi->get_view_data( "posts_paggin_html" );
$post_status 				= $beanUi->get_view_data( "post_status" );
$search_title 				= $beanUi->get_view_data( "search_title" );
$status_id 					= $beanUi->get_view_data( "status_id" );
$post_activity_type_master	= $beanUi->get_view_data( "post_activity_type_master" );
$activity_type_id 			= $beanUi->get_view_data("activity_type_id");
$division_department 		= $beanUi->get_view_data("division_department");
$devition_names 			= $beanUi->get_view_data("devition_names");
$activity_participants 		= $beanUi->get_view_data("activity_participants");
$participants_list 			= $beanUi->get_view_data("participants_list");
$activity 					= $beanUi->get_view_data("activity");
$deviation_rows             = $beanUi->get_view_data("deviation");
$type_images 				= array("image/jpg", "image/jpeg", "image/png");
$type_videos 				= array("video/mp4", "video/quicktime");
$pdf 						= array("application/pdf");
$activities 				= get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);
$controller->get_header();
$site_root_url 				= dirname(url());
?>
<link type="text/css" media="all" href="<?php echo url("assets/css/jquery-scrollify-style.css"); ?>" rel="stylesheet" />
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
    <a onclick="return backnow()" class="btn btn-danger btn-sm">Back</a> 
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
                <tr>
		<td width="20%"><b>SAS Report No. : </b></td>
		<td> <?php echo ($activity[0]->sas_report_no !="" ) ? ($activity[0]->sas_report_no) : "" ;?></td>
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
				if($value->sas_report_no == "") {
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
                                    if($value->sas_report_no != "") {
				?>
				<li class="jq-scroller-item">
				<a class="jq-scroller-preview" href="<?php echo MIS_IMAGE_PATH_PPE.$value->file_path;?>" data-title="<?php echo @$value->name ?>"> 
				<img src="<?php echo MIS_IMAGE_PATH_PPE.$value->file_path;?>" alt=""> 
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
		<tr>
		<td><b>Featured Image : </b></td>
		<td><?php 
			if($activity[0]->featured_image_path !="" ) 
			{
				echo '<div style="float:left;"><img style="float:left;" src="'.$site_root_url.'/'.$activity[0]->featured_image_path.'" alt="" width="150" height="150" class="img2">'."</div>\n";
			}
			?></td>
		</tr>
			<tr>
		<td><b>% of Marks : </b></td>
		<td><?php echo ($activity[0]->avg_mark != "") ? $activity[0]->avg_mark : ""; ?></td>
		</tr>
		<tr>
		<td><b>Major Deficiency : </b></td>
		<td><?php echo ($activity[0]->major_deviation == "Y") ? "Yes" : "No"; ?></td>
		</tr>
		<tr>
		<td><b>Details of Deficiency : </b></td>
		<td><?php echo ($activity[0]->no_of_deviation) ? $activity[0]->no_of_deviation : "";
		echo ' <a href="" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">View All</a>';
		 ?>
		
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Details of Deficiency</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
			<tr class="bg-primary">
			
                                                    <th>Sl.No</th>
                                                    <th>Description of Deficiency</th>
                                                    <th>Quantity of Deficiency</th>
                                                    <th>Remarks</th>

                                                </tr>
<?php
if (count($deviation_rows)) {
    $f = 0;
    foreach ($deviation_rows as $row) {
        $f = $f + 1;
        ?>
                                                        <tr class="removetr" id="message_<?php echo $row->id; ?>">

                                                            <td align="center" width="10%"><?php echo $f; ?></td>
                                                            <td align="center" width="30%"><?php echo $row->description; ?></td>
                                                            <td align="center" width="30%"><?php echo $row->qty; ?></td>
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
