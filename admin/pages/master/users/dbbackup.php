<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$controller 		= load_controller( "UserController" );
$controller->doAction();
$beanUi 			= $controller->beanUi;
$allusers 			= $beanUi->get_view_data("allusers");
$users_paggin_html 	= $beanUi->get_view_data("users_paggin_html");
$page 				= $beanUi->get_view_data("page");
$search_user_txt 	= $beanUi->get_view_data("search_user_txt");
$user_status 		= $beanUi->get_view_data("user_status");
$status_id 			= $beanUi->get_view_data("status_id");
$controller->get_header();
?>
<div class="container1">
    <h1 class="heading">Database Backup</h1>
    <?php echo $beanUi->get_message(); ?>
    <div class="panel" style="padding:20px;">
        <form name="create_post" id="create_post" action="" method="post" enctype="multipart/form-data">
         
            <div class="holder">
				
            <input type="submit" value="Database Backup" class="btn btn-sm btn-primary" />
            <br>
            <span class="text-info"> <i class="fa fa-hand-o-up"></i> Click here for database backup</span>
           

            <input type="hidden" name="_action" value="Backup" class="btn btn-sm btn-success" />
     
            </div>
        </form>

</div>
</div>
<?php $controller->get_footer(); ?>
</body>
</html>

