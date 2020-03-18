<?php
session_start();
if( file_exists( '../../lib/var.inc.php' ) ) require_once( '../../lib/var.inc.php' );
if( file_exists( BEANPATH . '/PostMasterBean.php' ) ) require_once( BEANPATH . '/PostMasterBean.php' );
if( file_exists( DAOPATH . '/PostMasterDAO.php' ) ) require_once( DAOPATH . '/PostMasterDAO.php' );

$bean = new UserMasterBean();
$bean->doAction();
//$bean->setExtraCss('jquery.dataTables');
//$bean->setExtraJs( array( 'jquery', 'jquery.dataTables' ) );

$bean->get_header();
?>


<div class="maindiv">
	<?php echo $bean->getMessages('message'); ?>
	<h1>Requested Posts</h1>
	
	<div class="actionbar">
		<button id="deleteTriger" class="delete">Delete</button>
		<button id="activeTriger" class="active">Active</button>
		<button id="inactiveTriger" class="inactive">Inactive</button>
	</div>
	<div class="message"></div>
	<div class="container">
		<table id="postlist" class="display">
		<thead>
			<tr>
				<th width="5%"><input type="checkbox" id="toggle_id"  /></th>
				<th width="60%">Title</th>
				<th>Category</th>
				<th width="5%">Status</th>
			</tr>
		</thead>
		</table>
	</div>
</div>


<?php $bean->get_footer(); ?>
