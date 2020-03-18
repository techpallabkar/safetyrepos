<?php
class UserController extends MainController {
	
	public function __construct() {
		$this->bean = load_bean("UsersMasterBean");
		$this->dao 	= load_dao("UserMasterDAO");
		
		parent::__construct();
	}
	
	public function login() {
            
            
		if( $this->action == 'login' ) {
			
			$data = $this->bean->get_request("data");
			if( ! $this->bean->validateLogin($data) ) {
				$this->set_session( "errors", $this->bean->get_error_messages() );
				redirect( "login.php" );
			}
			
			$user = $this->dao->login($data);
			if( empty( $user ) ) {
				$this->beanUi->set_error_message("Wrong Employee Code or Password");
				redirect( "login.php" );
			}
			
			
			$status_options = array();
			$status_options = $this->dao->get_status_options();
			
			$user_status = isset( $status_options["user_status"] ) ? $status_options["user_status"] : array();
			$registered_status_id = get_id_from_array("status_name", "Registered", $user_status);
			$deactive_status_id = get_id_from_array("status_name", "Deactive", $user_status);
			
			if( $user->status_id == $registered_status_id ) {
				$this->beanUi->set_error_message( "Your account has not been activatted yeat. Please check your email to activate it." );
			} elseif( $user->status_id == $deactive_status_id ) {
				$this->beanUi->set_error_message( "Your account is deactivatted. Please contact to administrator to activate it." );
			} else {
				$this->set_session( "status_options", $status_options );
				$this->set_session( "auth_user", $user );
				
				// Clear tokenid
				$this->dao->save( array( "tokenid" => "", "id" => $user->id ) );
				// End
				
				redirect( "./" );
			}
			
			redirect( "login.php" );
		}
           
	}
	
	public function register() {
		$this->dao 	= load_dao("UserMasterDAO");
		
		$_action = $this->bean->get_request("_action");
		if( $_action == "Register" ) {
			$data = $this->bean->get_request("data");
			$this->set_session("data", $data);
			$validate = $this->bean->valid_register();
			if( ! $validate ) {
				$this->set_session( "errors", $this->bean->get_error_messages() );
				redirect('register.php');
			}
			
			$employee_code_exists 	= $this->dao->check_employee_code_exists($data["employee_code"]);
			$email_exists 			= $this->dao->check_email_exists($data["email"]);
			$error_str = "";
			if( $employee_code_exists ) $error_str = "Employee Code already exists into database.";
			if( $email_exists ) $error_str .= " E-mail already exists into database.";
			
			if( $error_str != "" ) {
				$this->beanUi->set_error_message( $error_str );
				redirect('register.php');
			}
			
			$data["role_id"] = 2;
			$data["password"] = md5($data["password"]);
			$data["status_id"] = 1;
			$data["created_date"] = date("c");
			$data["tokenid"] = md5( time() );
			
			$this->beanUi->set_error_message( "User registration faild." );
			$user_id = $this->dao->save($data);
			if( $user_id ) {
				// Send email
				$this->beanUi->set_success_message( "Registration has been completted. Please check your e-mail to activate and login." );
				$this->load_libraries('PHPMail');
				if( is_object( $this->phpmail ) ) {
					
					$this->phpmail->to_email = $data["email"];
					$this->phpmail->to_name = $data["full_name"];
					$this->phpmail->from_email = "career@viavitae.co.in";
					$this->phpmail->from_name = "CESC Safety Monitoring System";
					$this->phpmail->subject = "New registration in CESC Safety Monitoring System.";
					$this->phpmail->body = 
					"<div style=\"width:100%;border:solid 1px #ee0000;\">
					<div>Hello ".$data["full_name"].", </div>
					<br />
					<div style=\"width:100%;\">You have been successfully registered on CESC Safety Monitoring System website. Please click the link given bellow to activate and login to your account.</div>
					<br /><br />
					<div><a href=\"".page_link( "login.php?tokenid=".$data["tokenid"]."&action=valid_me" )."\" target=\"_blank\">Click to activate and login.</a></div>
					</div>";
					
					if( ! $this->phpmail->send_mail() ) {
						$this->beanUi->set_error_message( "Registration has been completted. but sending mail failed." );
					} else {
						$this->set_session("data", array());
					}
				}
			} else {
				if( $this->dao->pdo_error != "" ) die( $this->dao->pdo_error );
			}
			redirect();
		}
		
		$security_questions = $this->dao->get_security_questions();
		$this->beanUi->set_view_data("security_questions", $security_questions);
		$this->beanUi->set_array_to_view_data($this->get_session("data"));
	}
	
	public function forgot_password() {
		$_action = $this->bean->get_request("_action");
		if( $_action == "forgot_password_step_1" ) {
			$employee_code 			= $this->bean->get_request("employee_code");
			$security_question_id 	= $this->bean->get_request("security_question_id");
			$security_answer 		= $this->bean->get_request("security_answer");
			if( $employee_code != "" && $security_question_id > 0 ) {
				$data = array( 
					"employee_code" => $employee_code, 
					"security_question_id" => $security_question_id, 
					"security_answer" => $security_answer
				);
				$row 		= $this->dao->check_forgot_password_step_1($data);
				$user_id 	= isset($row->id) ? $row->id : 0;
				die($user_id);
			}
		} elseif( $_action == "forgot_password_step_2" ) {
			$user_id 			= $this->bean->get_request("user_id");
			$password 			= $this->bean->get_request("password");
			$confirm_password 	= $this->bean->get_request("confirm_password");
			$update 			= 0;
			if( $user_id > 0 && $password != "" && $password == $confirm_password ) {
				$update = $this->dao->reset_password(
					array( 
						"id" => $user_id, 
						"password" => md5($password) 
					)
				);
			}
			
			die("$update");
		}
		
		$security_questions = $this->dao->get_security_questions();
		$this->beanUi->set_view_data("security_questions", $security_questions);
	}
	
	public function valid_me() {
		$tokenid = $this->bean->get_request("tokenid");
		if( $tokenid != "" ) {
			//$this->beanUi->set_error_message( "Invalid token id." );
			if( $this->dao->activate_new_user($tokenid) ) {
				$this->beanUi->set_success_message( "Your account has been activated." );
			}
		}
		redirect(page_link("login.php"));
	}
	
	public function send_test_mail() {
		$this->load_libraries('PHPMail');
		
		$to_mail = $this->bean->get_request("to_mail");
		
		$this->phpmail->to_email = ($to_mail != "") ? $to_email : "sandip.kapat@viavitae.co.in";
		
		$this->phpmail->to_name = "Sandip kapat";
		$this->phpmail->from_email = "career@viavitae.co.in";
		$this->phpmail->from_name = "CESC Safety Monitoring System";
		$this->phpmail->subject = "New registration in CESC Safety Monitoring System.";
		$this->phpmail->body = "Hi this is a test mail.".date("c");
		$sendmail = $this->phpmail->send_mail();
		if( $sendmail ) {
			echo $sendmail;
			echo "<br />";
		}
		die( "mail sent." );
	}
	
	/*public function send_test_mail() {
		$this->load_libraries('PHPMail');
        $to_mail = $this->bean->get_request("to_mail");
		$to_email = "sandip.kapat@viavitae.co.in";
		$from_email = "From : career@viavitae.co.in\r\n";
		$from .=  "Content-type: text/html\r\n";
		$subject = "Confirmation : New registration in CESC Safety Monitoring System";
		$message = "Hello Test Mail";
		$go = mail($to_email, $subject, $message, $from_email);
		echo "$go";
		echo "<br />";
		die( "mail sent." );
	}*/
}
