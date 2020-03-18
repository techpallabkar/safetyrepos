<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$controller = load_controller( "ActivityController" );
$controller->doAction();
$beanUi = $controller->beanUi;

$page 			= $beanUi->get_view_data( "page" );
$allparticipants			= $beanUi->get_view_data( "allparticipants" );
$posts_paggin_html  = $beanUi->get_view_data( "posts_paggin_html" );
$post_status 		= $beanUi->get_view_data( "post_status" );
$search_title 		= $beanUi->get_view_data( "search_title" );
$status_id 			= $beanUi->get_view_data( "status_id" );
$post_activity_type_master= $beanUi->get_view_data( "post_activity_type_master" );
$activity_type_id = $beanUi->get_view_data("activity_type_id");

$activities = get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);
$controller->get_header();
?>

<div class="container1">
	<?php echo $beanUi->get_message(); ?>
    <h1 class="heading"><?php echo $activities; ?>
    <a href="create.php?activity_id=<?php echo $activity_type_id; ?>" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New Participants</a>
    </h1> 
	<div class="holder">
		<form name="searchuser" id="searchuser" method="post">
			<input type="hidden" name="page" value="<?php echo $page; ?>" />
			<input type="text" name="search_title" id="search_title" class="search" value="<?php echo $search_title?>" placeholder="Search title" />
			<input type="submit" value="Go" class="btn btn-sm btn-primary" />
		</form>
	</div>

	
	<div class="message"></div>
	
	<table id="postlist" class="table table-striped responsive-utilities jambo_table bulk_action">
	<thead>
		<tr>
			<?php /*?><th width="5%"><input type="checkbox" id="toggle_check"  /></th><?php*/ ?>
                    <th>Category</th>
			<th>Name</th>
			<th>Employee Code</th>
                        <th>Designation</th>
                        <th>Department</th>
			<th>Action</th>
		</tr>
	</thead>
	<?php 
        
        if( !empty( $allparticipants ) ) {
           
           
            ?>
	<tbody>
		<?php
		$class = 'even';
		foreach( @$allparticipants as $row ) {
			if( @$row->category_id && ! @$row->active_category ) continue;
			
			$class = ($class == 'even') ? 'odd' : 'even';
		?>
			<tr class="<?php echo $class; ?>" role="row">
                            <td align="center"> <?php echo $row->category_name; ?> </td>
				<td align="center">
                                    
                                    <div class="holder">
                                         <?php echo $row->name; ?>
                                        
                                    </div>
                                   
                                   
                                    
                                </td>
                                <td align="center">
                                     <div class="holder ">
                                          <?php echo $row->emp_code; ?>
                                </td>
				<td align="center">
                                     
                                   <?php echo $row->designation; ?>
                                       
                                </td>
                                <td align="center">
                                     
                                       <?php echo $row->department; ?>
                                    
                                </td>
                                <td align="center">
                                    	<a href="edit.php?id=<?php echo $row->id ?>">Edit</a>
					<?php 
                                       
                                            echo ' | <a href="?action=delete_participant&id='.$row->id.'&activity_id='.$row->activity_id.'&page='.$page.'" onclick="return confirm(\'Confirm delete.\');">Delete</a>';
                                        
                                            ?>
                                    
                                </td>
			</tr>
		<?php } ?>
	</tbody>
	<?php } ?>
	</table>
	<hr />
	<?php echo $posts_paggin_html; ?>
</div>

<?php $controller->get_footer(); ?>
</body>
</html>
