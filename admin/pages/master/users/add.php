<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$controller = load_controller( "UserController" );
$controller->doAction();
$beanUi = $controller->beanUi;

$allusers 		= $beanUi->get_view_data("allusers");
$users_paggin_html 	= $beanUi->get_view_data("users_paggin_html");
$page 			= $beanUi->get_view_data("page");
$search_user_txt 	= $beanUi->get_view_data("search_user_txt");
$user_status 		= $beanUi->get_view_data("user_status");
$status_id 		= $beanUi->get_view_data("status_id");
$controller->get_header();
?>

<div class="container1">
	<h1 class="heading">Add New Users</h1>
         <?php echo $beanUi->get_message(); ?>
	<div class="panel">
            <?php
           /* if($controller->get_auth_user("role_id")!= 1)
            {
            echo '<div class="error">You don`t have permission to access this page.</div>'; 
            die();
            }*/
            ?>
		<form name="add_user" id="add_user" action="" method="post">
		<div class="holder required">
			<label for="employee_id">Username</label>
			<input type="text" name="data[employee_code]" id="employee_code" value="<?php //echo $bean->getDataValue('employee_id'); ?>" />
			<?php echo $beanUi->get_error( "employee_code" ); ?>
		</div>
		<div class="holder required">
			<label for="display_name">Display Name</label>
			<input type="text" name="data[full_name]" id="full_name" value="<?php //echo $bean->getDataValue('display_name'); ?>" />
			<?php echo $beanUi->get_error( "full_name" ); ?>
		</div>
		<div class="holder required">
			<label for="email">E-mail</label>
			<input type="text" name="data[email]" id="email" value="<?php //echo $bean->getDataValue('email'); ?>" />
			<?php echo $beanUi->get_error( "email" ); ?>
		</div>
		<div class="holder required">
			<label for="contactno">Contact No.</label>
			<input type="text" name="data[mobile_no]" id="mobile_no" value="<?php //echo $bean->getDataValue('contactno'); ?>" />
			<?php echo $beanUi->get_error( "mobile_no" ); ?>
		</div>
		<div class="holder required">
			<label for="contactno">User Type</label>
			<select name="data[role_id]" id="role_id">
			<option value="" selected="selected" disabled="disabled">choose one</option>
			<option value="2">Normal User</option>
			<?php if($controller->get_auth_user("role_id") == 3) { ?>
			<option value="1">Admin</option>
			<?php } ?>
			</select>
			<?php echo $beanUi->get_error( "role_id" ); ?>
		</div>
                </br>
		<div class="holder required">
			<label for="contactno">Is Nodal Officer</label>
			<select name="data[is_nodal_officer]" id="is_nodal_officer">
			<option value="" selected="selected" disabled="disabled">choose one</option>
			<option value="1">Yes</option>
			<option value="0">No</option>
			</select>
			<?php echo $beanUi->get_error( "is_nodal_officer" ); ?>
		</div>
		<div class="holder">
			
			<input type="hidden" name="data[status_id]" id="status_id" value="1" />
		</div>
		<div class="holder">
                    <input type="submit" name="submit_add_user" id="submit_add_user" class="btn btn-primary btn-sm" value="Add" />
                    <a href="index.php" class="btn btn-danger btn-sm">Cancel</a>
			<input type="hidden" name="_action" value="add" />
		</div>
		</form>
	</div>
</div>



  <?php $controller->get_footer(); ?>
</body>
</html>
