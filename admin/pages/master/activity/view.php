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
$type_images 				= array("image/jpg", "image/jpeg", "image/png");
$type_videos 				= array("video/mp4", "video/quicktime");
$pdf 						= array("application/pdf");
$activities 				= get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);
$controller->get_header();
$site_root_url 				= dirname(url());
?>
<link type="text/css" media="all" href="<?php echo url("assets/css/jquery-scrollify-style.css"); ?>" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url("assets/js/jquery_0034.js"); ?>"></script>
 <script type="text/javascript">
$(document).ready(function(){
window.history.forward(1);
});
</script>


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
			//alert(history.length);return false;
		if(history.length>1)
		{
			//history.go(-2);
			window.location.href ="index.php?status_id=<?php echo $_REQUEST["status_id"];?>&districtid=<?php echo $_REQUEST["districtid"];?>&activity_no=<?php echo $_REQUEST["activity_no"];?>&search_title=<?php echo $_REQUEST["search_title"]; ?>&fromdate=<?php echo $_REQUEST["fromdate"];?>&todate=<?php echo $_REQUEST["todate"];?>&page=<?php echo $_REQUEST["page"]; ?>&activity_id=<?php echo $activity[0]->activity_type_id; ?>";
		}
		else
		{
			window.close();
		}
	}
    </script>
    <a onclick="return backnow()" class="btn btn-danger btn-sm">Back</a> 
    <!--
   <a href="javascript: history.go(-2)" class="btn btn-danger btn-sm">Back</a> -->
    </h1> 
    
	<div class="message"></div>
	
	<div class="modal fade" id="viewmyModal183" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Modal title</h4>

            </div>
            <div class="modal-body"><div class="te"></div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
	
	
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
		<td width="20%"><b>Activity No : </b></td>
		<td> <?php echo ($activity[0]->activity_no !="" ) ? $activity[0]->activity_no : "" ;?></td>
		</tr>
		<tr>
		<td width="20%"><b>Date of Activity : </b></td>
		<td> 
			<?php 
			 if($activity[0]->activity_type_id == 3 ) {
				if($activity[0]->from_date !="0000-00-00" || $activity[0]->to_date !="0000-00-00"  ) {
					echo date('d-m-Y', strtotime($activity[0]->from_date)).' - '.date('d-m-Y', strtotime($activity[0]->to_date));
				}
			}
			else
			{
				if($activity[0]->activity_date !="0000-00-00" ) {
					echo date('d-m-Y', strtotime($activity[0]->activity_date));
				}
				
			}
			//echo ($activity[0]->activity_date !="" ) ? date('F j, Y', strtotime($activity[0]->activity_date)) : "" ;
			
			
			?>
			
			
			</td>
		</tr>
		<tr>
		<td width="20%"><b>Venue : </b></td>
		<td> <?php echo ($activity[0]->place !="" ) ? '<span class="fa fa-map-marker"></span> '.($activity[0]->place) : "" ;?></td>
		</tr>
		<tr>
		<td><b>Activity Participants : </b></td>
		<td>
			<?php
		 if( !empty($activity_participants) )
    {
		?>
	
		<table class="table table-bordered table-condensed">
			<tr>
				<th>Category of participants</th>
				<th>No. of participants</th>
			</tr>
			<?php
			foreach ($activity_participants as $rowdata)
			{
				$activity_rows = isset($participants_list[$rowdata->id]) ? $participants_list[$rowdata->id] : array();
				if($rowdata->type == 'P')
				{
					?>
					<tr>
						<td align="center"><?php echo $rowdata->category_name; ?></td>
						<td align="center">
							<?php echo $rowdata->no_of_participants; ?> ( <a href="#" class="js-open-modal" data-modal-id="popup<?php echo $rowdata->id;?>" style="color: blue;"> <i class="fa fa-search"></i> View Participants</a> )
							<!--participants popup-->
							<div id="popup<?php echo $rowdata->id;?>" class="modal-box" >
							<header> <a href="#" class="js-modal-close close">×</a>
							<h3 align="left">Participants Details</h3>
							</header>
							<div class="modal-body" style="overflow-x: hidden;overflow-y: auto;">
								<table class="table table-bordered" id="pdetails_<?php echo $rowdata->id;?>">
								<?php if($rowdata->participant_cat_id == '4') { $hide = ' style="display:none;"'; } else { $hide = '';} ?>
								<tr>
									<th>No. of Employee</th>
									<th>Designation</th>
									<th>Department</th>
								</tr>
								<?php
								if( count($activity_rows) )
								{
									foreach($activity_rows as $row)
									{
										?>
										<tr>
											<td align="center"><?php echo $row->no_of_emp; ?></td>
											<td align="center"><?php echo $row->designation; 
											if(!empty($row->designation_2))
											{
												echo " => ".$row->designation_2;
											}
											?></td>
											<td align="center"><?php echo $row->department; ?></td>
										</tr>
										<?php
									
									}
								}
								?>
								</table>
							</div>
							<footer style="margin-left:0px;">
								<a href="#" class="btn btn-default btn-small js-modal-close">Close</a> 
							</footer>
							</div>
						</td>
					</tr>
					<?php   
				}
			}
                    echo "</table>";
    } 
		?>
		
		</td>
		</tr>
		<tr>
		<td><b>
		<?php if($activity[0]->activity_type_id == 4 ) {
			echo "Speakers :";
		}
		else
		{
			echo "Faculty :";
		}
		?> 
			
			
			</b></td>
		<td>
			<?php
		  if( !empty($activity_participants) )
    {
	?>
	
	<table class="table table-bordered table-condensed table-hover">
		<tr>
			<th>Faculty</th>
			<th>No. of Faculty</th>
		</tr>
		<?php
		foreach ($activity_participants as $rowdata)
		{
			$activity_rows = isset($participants_list[$rowdata->id]) ? $participants_list[$rowdata->id] : array();
			if($rowdata->type == 'F')
			{
			?>
			<tr>
				<td align="center"><?php echo $rowdata->category_name; ?></td>
				<td align="center">
					<?php echo $rowdata->no_of_participants; ?> ( <a href="#" class="js-open-modal" data-modal-id="popup<?php echo $rowdata->id;?>" style="color: blue;"> <i class="fa fa-search"></i> View Faculty</a> )
					
					<!--participants popup-->
					<div id="popup<?php echo $rowdata->id;?>" class="modal-box">
						<header> <a href="#" class="js-modal-close close">×</a>
							<h3>Faculty Details</h3>
						</header>
						<div class="modal-body" style="overflow-x: hidden;overflow-y: scroll;">
							<table class="table table-bordered" id="pdetails_<?php echo $rowdata->id;?>">
							<?php if($rowdata->participant_cat_id == '2') { $hide = ' style="display:none;"'; } else { $hide = '';} ?>
								<tr class="primary">
									<?php if($rowdata->participant_cat_id!= '2') 
									{ ?>
										<th <?php echo $hide; ?>>Employee Code</th>
										<th>Name</th>
										<th>Designation</th>
										<th>Department</th>
									<?php 
									} 
									else 
									{ ?>
										<th>Name</th>
										<th>Faculty Details</th>
									<?php 
									} 
									?>
								</tr>
								<?php
								if( count($activity_rows) )
								{
									foreach($activity_rows as $row)
									{
										if($rowdata->participant_cat_id!='2') 
										{
										?>
											<tr>
												<td align="center" <?php echo $hide; ?>><?php echo $row->emp_code; ?></td>
												<td align="center"><?php echo $row->name; ?></td>
												<td align="center"><?php echo $row->designation; ?></td>
												<td align="center"><?php echo $row->department; ?></td>
											</tr>
										<?php
										} else {
									   ?>
											<tr>
												<td align="center"><?php echo $row->name; ?></td>
												<td align="center"><?php echo $row->designation; ?></td>
											</tr>         
										<?php
										}																	   
									}
												
								}
								  ?>
							</table>
						</div>
						<footer style="margin-left:0px;"> 
							<a href="#" class="btn btn-default btn-small js-modal-close">Close</a> 
						</footer>
					</div>
				<!--participants popup-->
				</td>                               
			</tr>
			<?php   
			}
		}
		echo "</table>";
    } 
    ?>	
		
		</td>
		</tr>
		
		<tr>
		<td><b>Subject Title : </b></td>
		<td>
			<?php 
				if($activity[0]->subject_details !="" )	{ 
				  echo stripslashes($activity[0]->subject_details); 
				} 
			?>
		</td>
		</tr>
		<tr>
		<td><b>Details : </b></td>
		<td>
			<?php 
				if($activity[0]->remarks !="" )	{ 
				  echo stripslashes($activity[0]->remarks); 
				} 
			?>
		</td>
		</tr>
		<tr>
		<td><b>Featured Image :</b></td>
		<td>
		
		<?php 
			if($activity[0]->featured_image_path !="" ) 
			{
				echo '<div style="float:left;"><img style="float:left;" src="'.$site_root_url.'/'.$activity[0]->featured_image_path.'" alt="" width="150" height="150" class="img2">'."</div>\n";
			}
		?>
		</td>
		</tr>
		<tr>
		<td><b> Attached Office Files : </b></td>
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
		<td><b> Attached Images :  </b></td>
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
                                    
				?>
				<li class="jq-scroller-item">
				<a class="jq-scroller-preview" href="<?php echo $site_root_url.'/'.$value->file_path;?>" data-title="<?php echo @$value->name ?>"> 
				<img src="<?php echo $site_root_url.'/'.$value->file_path;?>" alt=""> 
				</a>
				</li>
				<?php }  }  ?>
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
		<td><b> Attached Video`s : </b></td>
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
		
		</table>
		
		
         <br>      
         
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
