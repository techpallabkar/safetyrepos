<?php

if( file_exists( "../../lib/var.inc.php" ) )  require_once( "../../lib/var.inc.php" );
$controller = load_controller( "ActivityController" );
$controller->doAction();
$beanUi = $controller->beanUi;

$action = $_POST["action"];
if(!empty($action)) {
   
	switch($action) {
		case "p_add":
			$result = mysql_query("INSERT INTO comment(message) VALUES('".$_POST["txtmessage"]."')");
			if($result){
				  $insert_id = mysql_insert_id();
				  echo '<div class="message-box"  id="message_' . $insert_id . '">
						<div>
						<button class="btnEditAction" name="edit" onClick="showEditBox(this,' . $insert_id . ')">Edit</button>
<button class="btnDeleteAction" name="delete" onClick="callCrudAction(\'delete\',' . $insert_id . ')">Delete</button>
						</div>
						<div class="message-content">' . $_POST["txtmessage"] . '</div></div>';
			}
			break;
			
		case "p_edit":
			$result = mysql_query("UPDATE comment set message = '".$_POST["txtmessage"]."' WHERE  id=".$_POST["message_id"]);
			if($result){
				  echo $_POST["txtmessage"];
			}
			break;			
		
		case "p_delete": 
			if(!empty($_POST["message_id"])) {
				//die("DELETE FROM activity_participants_mapping WHERE id=".$_POST["message_id"]);
                              //  if( $this->dao->del(array( "id" => $_POST["message_id"] )) ) $this->beanUi->set_success_message( "Presentation is deleted." );
			}
			break;
	}
}
?>