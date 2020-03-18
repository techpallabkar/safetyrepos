<?php
if( file_exists( "../../lib/var.inc.php" ) )  require_once( "../../lib/var.inc.php" );
$controller = load_controller( "ActivityController" );
$controller->doAction();

$beanUi=$controller->beanUi;
$post_categories=$beanUi->get_view_data( "post_categories" );
$post_participants_categories= $beanUi->get_view_data( "post_participants_categories" );
$post_division_department= $beanUi->get_view_data( "post_division_department" );
$post_activity_type_master= $beanUi->get_view_data( "post_activity_type_master" );

$post_status=$beanUi->get_view_data( "post_status" );
$auth_user_id=$controller->get_auth_user("id");
$activity_id=$beanUi->get_view_data("activity_id");

$activities = get_value_by_id($activity_id, "activity_name", $post_activity_type_master);
$l1_cat_name= "";
$tag_keys= $beanUi->get_view_data( "tag_keys" );

if($_POST)
{
$emp_code=$_POST['emp_code'];
foreach($emp_code as $emp)
{
    mysql_query("insert into activity_participants_mapping set activity_id='1', emp_code='".$emp['emp_code']."'");
    
}



}

?>