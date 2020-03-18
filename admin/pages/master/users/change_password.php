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
    <h1 class="heading">Change Password</h1>
    <?php echo $beanUi->get_message(); ?>
    <div class="panel" style="padding:20px;">
        <form name="create_post" id="create_post" action="" method="post" enctype="multipart/form-data">
            <div class="holder required">
                <label for="activity_no">Old Password</label>
                <input type="password"  name="old_password" id="old_password" value="<?php echo $beanUi->get_view_data( "activity_no" ); ?>" />
                <div id="activity_no_error"><?php echo $beanUi->get_error( 'activity_no' ); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="activity_no">New Password</label>
                <input type="password"  name="new_password" id="new_password" value="<?php echo $beanUi->get_view_data( "activity_no" ); ?>" />
                <div id="activity_no_error"><?php echo $beanUi->get_error( 'activity_no' ); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="activity_count">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password"  value="<?php echo $beanUi->get_view_data( "activity_count" ); ?>" />
                <div id="activity_count_error"><?php echo $beanUi->get_error( 'activity_count' ); ?></div>
            </div>
            <br />

            <div class="holder">
            <input type="submit" value="Change Password" class="btn btn-sm btn-primary" />
            <a href="change_password.php" class="btn btn-sm btn-danger">Cancel</a>
            <input type="hidden" name="_action" value="Create" class="btn btn-sm btn-success" />
            <input type="hidden" id="f_image_error" value="" />
            </div>
        </form>

</div>
    
</div>
<?php $controller->get_footer(); ?>
</body>
</html>
