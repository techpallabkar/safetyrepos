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


$framework = $beanUi->get_view_data("framework");
$framework_value = $beanUi->get_view_data("framework_value");
$getframework_value = $beanUi->get_view_data("getframework_value");

$deviation_rows = $beanUi->get_view_data("deviation");


$type_images 				= array("image/jpg", "image/jpeg", "image/png");
$type_videos 				= array("video/mp4", "video/quicktime");
$pdf 						= array("application/pdf");
$activities 				= get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);
$controller->get_header();
$site_root_url 				= dirname(url());
//show($activity);
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
			window.location.href ="index.php?activity_id=<?php echo $activity[0]->activity_type_id; ?>";
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
		<?php $activity_name= get_value_by_id($activity[0]->activity_type_id, "activity_name", $activity_type_master); ?>
		<div class="grey-bg"><?php echo  $activity_name; ?></div> 
		<div class="clearfix"></div>
		<div class="mh-home-2 mh-widget-col-2 mh_slider_hp clearfix">
			 <?php 
			 if($activity[0]->subject_details !="" ){ ?>  
				<h1 class="heading"><?php echo stripslashes($activity[0]->subject_details); ?></h1>
			 <?php } ?>
		</div>
		
		<hr>
		
		<div class="mh-widget mh-home-2 mh-widget-col-2 mh_slider_hp clearfix">
			<table class="table table-bordered">
				<tr>
			<td width="30%"><b>Activity No.  : </b></td>
			<td>
				<?php
				 if($activity[0]->activity_no !="" ) {
				echo '<span class="mh-meta-date updated"> '.$activity[0]->activity_no.'</span>'."\n";
			}
				?>
				</td>
				</tr>
				<tr>
			<td width="30%"><b>Persons Present During Observation    : </b></td>
			<td>
			
			<?php
			
			if( $activity[0]->persons_present_during_observation !="" )
			{
				echo $activity[0]->persons_present_during_observation;			
			}
			?>
			</td>
			</tr>
			<tr><td><b> Date of Observation : </b></td>
			<td>
			<?php
			if( $activity[0]->date_of_observation !="0000-00-00" )
			{
				echo date("Y-m-d",strtotime($activity[0]->date_of_observation));			
			}
			
			?>
			</td>
			</tr>
			<tr>
			<td><b>Venue :</b></td>
			<td>
			<?php
			if($activity[0]->place !="" ) {
				echo '<span class="fa fa-map-marker"></span> '.stripslashes($activity[0]->place).'';
			}
			?>
			</td>
			</tr>
			<tr>
				<td><b>Participating Department</b></td>
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
			?>
		
		<?php         
    }
    ?>
			</td>
			</tr>
			
			<tr>
			<td><b>Framework :</b></td>
			<td>
				<a href="" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModalFramework">View All</a>
				<div id="myModalFramework" class="modal fade" role="dialog">
  <div class="modal-dialog">
	     <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Framework Details</h4>
      </div>
      <div class="modal-body">
				   <?php
               
                $index_array = array();
foreach ($getframework_value as $k => $vals) {
    $index_array[$vals->framework_id]["fmid"] = $vals->framework_id;
    $index_array[$vals->framework_id]["fcount"] = $vals->framework_count;
}

                foreach($framework as $rowval)
                {
                   
                    $frameworkvalue = $framework_value[$rowval->id];
                    echo ' <table class="table table-bordered">'
                    . '<thead><tr><th style="text-align:left;" colspan="3" class="bg-primary">'.$rowval->name.'</th></tr><thead>';
                    //echo '<pre>';
                    echo '<tr><th>SL.NO</th><th style="text-align:left;">DESCRIPTION</th><th>COUNT</th></tr>';
                    foreach($frameworkvalue as $key => $val)
                    {
                        
                        if ($val->id== $index_array[$val->id]["fmid"]) {
                            $fcount = $index_array[$val->id]["fcount"];
                          
                        } else {
                            $fcount = "";
                          
                        }
                       // echo $fcount;
                        echo '<tr>'
                        . '<td>'.($key+1).'</td>'
                        . '<td width="90%">'.$val->name.'</td>'
                        . '<td width="5%">'.$fcount.'</td>'
                                . '</tr>';
                    }
                    //print_r($value);
                    echo '</table>';
                   
                }
                ?>
                
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
			<td><b>Major Deviation :</b></td>
			<td>
			<?php
			echo $activity[0]->major_deviation == "Y"  ? "Yes" : "No";
			?>
			</td>
			</tr>
			<tr>
			<td><b>No. of Deviation :</b></td>
			<td>
			<?php
			if($activity[0]->place !="" ) {
				echo ''.$activity[0]->no_of_deviation.'';
				echo ' <a href="" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">View All</a>';
			}
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
                                    <th>Description of Deviation</th>
                                    <th>Category</th>
                                    <th>Action Taken</th>
                                    </tr>
                                    
            <?php
            if( count($deviation_rows) )
            {
                $f = 0;
               foreach($deviation_rows as $row)
               {
                   $f = $f+1;
                    ?>
                            <tr class="removetr" id="message_<?php echo $row->id; ?>">

                                    <td align="center" width="10%"><?php echo $f; ?></td>
                                    <td align="center" width="30%"><?php echo $row->description; ?></td>
                                    <td align="center" width="30%"><?php echo $row->category; ?></td>
                                    <td align="center" width="60%">
                                        <?php 
                                            if($row->action_taken == 'Y') 
                                                { 
                                                echo "Yes"; 
                                                echo "<br><b>Correction Description </b>: ".$row->correction_desc;
                                                echo "<br><b>Correction Date </b>: ".$row->correction_date;
                                                } 
                                            else 
                                                { 
                                                echo "No"; 
                                                } 
                                                ?></td>
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
			<td><b>Remarks :</b></td>
			<td>
			<?php
			if( $activity[0]->remarks !="" )
			{
				
				echo '<div class="synp-desc" style="float:left;">'.stripslashes($activity[0]->remarks).'</div></div>'."\n";
			}
			?>
			</td>
			</tr>
			
			</table>
			
		</div>
         <br>      
       
	</div>
	<hr />
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
