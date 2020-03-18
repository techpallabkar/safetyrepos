<?php
if( file_exists( "../lib/var.inc.php" ) ) require_once( "../lib/var.inc.php" );

$user_controller = load_controller( "UserController" );
$user_controller->doAction();
$beanUi = $user_controller->beanUi;
$security_questions = $beanUi->get_view_data( "security_questions" );

header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="text/css" media="all" href="<?php echo url("assets/css/style.css") ?>" rel="stylesheet" />
	<title>CESC Safety Monitoring System</title>
	<link rel="stylesheet" id="mh-google-fonts-css" href="<?php echo url("assets/css/googleapi_OpenSans400_400italic_700_600.css") ?>" type="text/css" media="all" />
	<?php echo $user_controller->loadCss(); ?>
	
	<script type="text/javascript" src="<?php echo url("assets/js/jquery.js?ver=1.11.3") ?>"></script>
	<script type="text/javascript" src="<?php echo url("assets/js/jquery-migrate.min.js?ver=1.2.1") ?>"></script>
	<script type="text/javascript" src="<?php echo url("assets/js/scripts.js?ver=4.4") ?>"></script>
	
	<style>
	.registerform{ margin:2% auto; width:60%; border:solid 1px #fff; background:#fff; border-radius:3px; }
	.holder{ width:100%; padding:5px; }
	.registerform label{ display:inline-block; width:25%; }
	</style>
	
<script type="text/javascript">
var root_url = "<?php echo url() ?>";
var page_url = "<?php echo $_SERVER["PHP_SELF"]; ?>";
</script>
</head>
<body id="mh-mobile" class="home page page-template-homepage page-template-homepage-php custom-background mh-right-sb">
	
	<div class="registerform">
		<h2 style="margin:5px;">Forgot Password</h2>
		<?php echo $beanUi->get_view_data( "message" ); ?>
		<div class="jserror"></div>
		<form id="forgotpwd" name="forgotpwd" action="" method="post">
		<input type="hidden" id="user_id" value="" />
		<input type="hidden" id="_action" value="forgot_password_step_1" />
		
		<div class="loader"></div>
		<div class="holder employee_code_box">
			<label for="employee_code" class="required">Employee Code</label>
			<input type="text" id="employee_code" value="" />
			<?php echo $beanUi->get_error( "employee_code" ); ?>
		</div>
		<div class="holder security_question_box">
			<label for="security_question_id" class="required">Security Question</label>
			<select id="security_question_id">
				<option value="">Select one</option>
				<?php
				if( ! empty( $security_questions ) ) {
					foreach( $security_questions as $qrow ) {
						echo "<option value=\"".$qrow->id."\">".$qrow->question."</option>"."\n";
					}
				}
				?>
			</select>
			<?php echo $beanUi->get_error( "security_question_id" ); ?>
			<div class="security_question_error"></div>
		</div>
		<div class="holder security_answer_box">
			<label for="security_answer" class="required">Security Answer</label>
			<input type="password" id="security_answer" value="" />
			<?php echo $beanUi->get_error( "security_answer" ); ?>
		</div>
		<div class="holder password_box">
			<label for="password" class="required">New Password</label>
			<input type="password" id="password" value="" />
			<?php echo $beanUi->get_error( "password" ); ?>
		</div>
		<div class="holder confirm_password_box">
			<label for="confirm_password" class="required">Confirm Password</label>
			<input type="password" id="confirm_password" value="" />
			<?php echo $beanUi->get_error( "conferm_password" ); ?>
		</div>
		<div class="holder">
			<label for="forgot_password"></label>
			<input type="button" id="forgot_password" value="Submit" />
			<label for="cancel"></label>
			<input type="button" id="cancel" value="Go to login page" />
		</div>
		</form>
	</div>

<script type="text/javascript">
var forgot_step_1 = jQuery(".employee_code_box,.security_question_box, .security_answer_box");
var forgot_step_2 = jQuery(".password_box,.confirm_password_box");
forgot_step_2.hide();
	
jQuery(document).ready(function () {
	jQuery( "#forgot_password" ).click(function () {
		var employee_code 			= jQuery.trim(jQuery("#employee_code").val());
		var security_question_id 	= jQuery("#security_question_id").val();
		var security_answer 		= jQuery.trim(jQuery("#security_answer").val());
		var user_id 				= jQuery("#user_id").val();
		var password 				= jQuery.trim(jQuery("#password").val());
		var confirm_password 		= jQuery.trim(jQuery("#confirm_password").val());
		var _action 				= jQuery("#_action").val();
		var forget_pwd_data = {};
		var error_counter = 0;
		
		if( _action == "forgot_password_step_1" ) {
			
			if( employee_code != "" && security_question_id > 0 && security_answer != "" ) {
				forget_pwd_data = {
					"_action" 				: "forgot_password_step_1", 
					"employee_code" 		: employee_code, 
					"security_question_id" 	: security_question_id, 
					"security_answer" 		: security_answer
				}
			}
			
		} else if( _action == "forgot_password_step_2" ) {
			if( user_id > 0 ) {
				if( password == "" ) {
					jQuery("#password").focus();
					jQuery("#password").parent().append("<div class=\"errors\">Password is required.</div>");
					error_counter++;
				} else if( password != "" && password != confirm_password ) {
					jQuery("#confirm_password").focus();
					jQuery("#confirm_password").parent().append("<div class=\"errors\">Confirm Password is not matched with New Password.</div>");
					error_counter++;
				}
				
				if( error_counter > 0 ) {
					return false;
				} else {
					forget_pwd_data = {
						"_action" 			: "forgot_password_step_2", 
						"user_id" 			: user_id, 
						"password" 			: password, 
						"confirm_password" 	: confirm_password
					}
				}
			}
			
		}
		
		if( forget_pwd_data !== "null" && forget_pwd_data !== "undefined" ) {
			jQuery(".jserror").html("");
			
			jQuery.ajax({
				data : forget_pwd_data, url : "<?php echo current_url(); ?>", type : "post", cache : false, success : function (user_id) {
					if( _action == "forgot_password_step_1" ) {
						if(user_id > 0) {
							jQuery("#user_id").val(user_id);
							jQuery("#_action").val("forgot_password_step_2");
							forgot_step_2.show();
						} else {
							jQuery(".jserror").html('<div class="error">Incorrect informations.</div>');
						}
					} else if( _action == "forgot_password_step_2" ) {
						
						if(user_id > 0) {
							alert( "Password has been changed." );
							location.href='login.php';
						} else {
							jQuery(".jserror").html('<div class="error">Password has not been changed.</div>');
						}
					}
				}
			});
		}
	});
	
	jQuery("#cancel").click(function () { location.href="login.php";});
});


</script>
<script type="text/javascript" src="<?php echo url("assets/js/default.js") ?>"></script>
<script type="text/javascript" src="<?php echo url("assets/js/users.js") ?>"></script>
</body>
</html>
<?php $beanUi->reset_session_vars(); ?>
