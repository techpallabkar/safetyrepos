<?php	
session_start();
if( file_exists( '../../lib/var.inc.php' ) ) require_once( '../../lib/var.inc.php' );
if( file_exists( BEANPATH . '/UserMasterBean.php' ) ) require_once( BEANPATH . '/UserMasterBean.php' );
if( file_exists( DAOPATH . '/UserMasterDAO.php' ) ) require_once( DAOPATH . '/UserMasterDAO.php' );

$bean = new UserMasterBean();
$bean->setExtraCss();
$bean->setExtraJs('users/users');
$bean->doAction();

$row = $bean->getViewVars('row');

$bean->get_header();
?>

<div class="container1">
	<h1 class="heading">Edit Users</h1>
	<div class="panel">
		<form name="edit_user" id="edit_user" action="" method="post">
		<div class="holder required">
			<label for="employee_id">Employee ID</label>
			<input type="text" name="data[employee_id]" id="employee_id" value="<?php echo $row->employee_id; ?>" readonly />
			<?php echo $bean->showError( 'employee_id' ); ?>
		</div>
		<div class="holder required">
			<label for="display_name">Display Name</label>
			<input type="text" name="data[display_name]" id="display_name" value="<?php echo $row->display_name; ?>" />
			<?php echo $bean->showError( 'display_name' ); ?>
		</div>
		<div class="holder required">
			<label for="email">E-mail</label>
			<input type="text" name="data[email]" id="email" value="<?php echo $row->email; ?>" />
			<?php echo $bean->showError( 'email' ); ?>
		</div>
		<div class="holder required">
			<label for="contactno">Contact No.</label>
			<input type="text" name="data[contactno]" id="contactno" value="<?php echo $row->contactno; ?>" />
			<?php echo $bean->showError( 'contactno' ); ?>
		</div>
		<div class="holder">
			<label for="active">Status</label>
			<?php
			$active = (( $row->status > 0 ) ? ' checked="checked"' : '');
			$readonly = (( $row->role_id == 1 ) ? ' disabled="disabled"' : '');
			?>
			<input type="checkbox" name="data[active]" id="active" value="1" <?php echo $active.$readonly; ?> />
		</div>
		<div class="holder">
			<input type="submit" value="Update" />
			<input type="button" id="cancel" value="Cancel" />
			<input type="hidden" name="action" value="edit" />
			<input type="hidden" name="data[id]" value="<?php echo $row->id; ?>" />
		</div>
		</form>
	</div>
</div>



<?php $bean->get_footer(); ?>
