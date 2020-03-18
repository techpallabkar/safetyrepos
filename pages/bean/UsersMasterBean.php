<?php
class UsersMasterBean extends MasterBean {
	public function __construct() {
		parent::__construct();
	}
	
	public function validateLogin($data) {
		if( empty( $data ) ) return FALSE;
		
		$count_error 	= 0;
		$errors 		= array();
		$employee_code 	= isset( $data['employee_code'] ) ? trim( $data['employee_code'] ) : '';
		$password 		= isset( $data['password'] ) ? trim( $data['password'] ) : '';
		if( $employee_code == "" ) {
			$this->error_messages[ 'employee_code_error' ] = '<div class="errors">Employee Code is required.</div>';
			$count_error++;
		}
		if( $password == "" ) {
			$this->error_messages[ 'password_error' ] = '<div class="errors">Password is required.</div>';
			$count_error++;
		}
		return ( $count_error ) ? FALSE : TRUE;
	}
	
	public function valid_register() {

		$errors 				= array();
		$data 					= $this->get_request("data");
		$full_name 				= isset( $data["full_name"] ) ? trim( $data["full_name"] ) : "";
		$employee_code 			= isset( $data["employee_code"] ) ? trim( $data["employee_code"] ) : "";
		$password 				= isset( $data["password"] ) ? trim( $data["password"] ) : "";
		$confirm_password 		= $this->get_request("confirm_password");
		$email 					= isset( $data["email"] ) ? trim( $data["email"] ) : "";
		$mobile_no 				= isset( $data["mobile_no"] ) ? trim( $data["mobile_no"] ) : "";
		$designation 			= isset( $data["designation"] ) ? trim( $data["designation"] ) : "";
		$department 			= isset( $data["department"] ) ? trim( $data["department"] ) : "";
		$security_question_id 	= isset( $data["security_question_id"] ) ? trim( $data["security_question_id"] ) : "";
		$security_answer 		= isset( $data["security_answer"] ) ? trim( $data["security_answer"] ) : "";
		$error_counter 			= 0;
		
		if( $employee_code == "" ) {
			$this->error_messages[ "employee_code_error" ] = "<div class=\"errors\">Employee Code is required.</div>";
			$error_counter++;
		}
		if( $password == "" ) {
			$this->error_messages[ "password_error" ] = "<div class=\"errors\">Password is required.</div>";
			$error_counter++;
		} elseif( $password != "" && $password != $confirm_password ) {
			$this->error_messages[ "conferm_password_error" ] = "<div class=\"errors\">Wrong Confirm Password.</div>";
			$error_counter++;
		}
		if( $full_name == "" ) {
			$this->error_messages[ "full_name_error" ] = "<div class=\"errors\">Full Name is required.</div>";
			$error_counter++;
		}
		if( $email == "" ) {
			$this->error_messages[ "email_error" ] = "<div class=\"errors\">E-mail is required.</div>";
			$error_counter++;
		} elseif( $email != "" && ! isValidEmail($email) ) {
			$this->error_messages[ "email_error" ] = "<div class=\"errors\">A valid E-mail is required.</div>";
			$error_counter++;
		}
		if( $mobile_no == "" ) {
			$this->error_messages[ "mobile_no_error" ] = "<div class=\"errors\">Mobile No. is required.</div>";
			$error_counter++;
		} elseif( $mobile_no != "" && ! is_numeric($mobile_no) ) {
			$this->error_messages[ "mobile_no_error" ] = "<div class=\"errors\">Mobile No. should be a numeric value and length should be 10 to 12 digit long.</div>";
			$error_counter++;
		} elseif( is_numeric($mobile_no) && ( strlen($mobile_no) < 10 || strlen($mobile_no) > 12 ) ) {
			$this->error_messages[ "mobile_no_error" ] = "<div class=\"errors\">Mobile No. should be 10 to 12 digit long.</div>";
			$error_counter++;
		}
		if( $designation == "" ) {
			$this->error_messages[ "designation_error" ] = "<div class=\"errors\">Designation is required.</div>";
			$error_counter++;
		}
		if( $department == "" ) {
			$this->error_messages[ "department_error" ] = "<div class=\"errors\">Department is required.</div>";
			$error_counter++;
		}
		if( $security_question_id == "" ) {
			$this->error_messages[ "security_question_id_error" ] = "<div class=\"errors\">A valid E-mail is required.</div>";
			$error_counter++;
		}
		if( $security_answer == "" ) {
			$this->error_messages[ "security_answer_error" ] = "<div class=\"errors\">Security Answer is required.</div>";
			$error_counter++;
		}
		return ( $error_counter ) ? FALSE : TRUE;
	}
	
	/*public function __construct() {
		parent::__construct();
		$this->dao = parent::load_dao("UserMasterDAO");
	}
	
	
	
	public function logout() {
		session_destroy();
		$this->redirect('login.php');
	}
	
	public function register() {
		$_action = $this->getRequestValue("_action");
		if( $_action == "Register" ) {
			$validate = $this->valid_register();
			if( ! $validate ) $this->redirect('register.php');
			
			$data = $this->getRequestValue('data');
			$data["role_id"] = 2;
			$this->loadLibraries('encription');
			$data["password"] = $this->encription->vvs_encript($data["password"]);
			$data["status_id"] = 1;
			$data["created_date"] = date("c");
			
			$this->set_error_message( "User registration faild." );
			if( $this->dao->save($data) ) {
				// Send email
				$this->loadLibraries('PHPMail');
				if( is_object( $this->phpmail ) ) {
					
				}
			} else {
				//$this->dao->pdo_error;
			}
			$this->redirect('login.php');
		}
		
		$security_questions = $this->dao->select( "SELECT * FROM `master_security_questions`" );
		$this->setViewData( "security_questions", $security_questions );
	}
	
	private function valid_register() {

		$errors 				= array();
		$data 					= $this->getRequestValue("data");
		$full_name 				= isset( $data["full_name"] ) ? trim( $data["full_name"] ) : "";
		$employee_code 			= isset( $data["employee_code"] ) ? trim( $data["employee_code"] ) : "";
		$password 				= isset( $data["password"] ) ? trim( $data["password"] ) : "";
		$confirm_password 		= $this->getRequestValue("confirm_password");
		$email 					= isset( $data["email"] ) ? trim( $data["email"] ) : "";
		$security_question_id 	= isset( $data["security_question_id"] ) ? trim( $data["security_question_id"] ) : "";
		$security_answer 		= isset( $data["security_answer"] ) ? trim( $data["security_answer"] ) : "";
		$error_counter 			= 0;
		
		
		if( $full_name == "" ) {
			$errors[ "full_name_error" ] = "<div class=\"errors\">Full Name is required.</div>";
			$error_counter++;
		}
		if( $employee_code == "" ) {
			$errors[ "employee_code_error" ] = "<div class=\"errors\">Employee Code is required.</div>";
			$error_counter++;
		}
		if( $password == "" ) {
			$errors[ "password_error" ] = "<div class=\"errors\">Password is required.</div>";
			$error_counter++;
		}
		if( $password != "" && $password != $confirm_password ) {
			$errors[ "conferm_password_error" ] = "<div class=\"errors\">Wrong Confirm Password.</div>";
			$error_counter++;
		}
		if( $email == "" ) {
			$errors[ "email_error" ] = "<div class=\"errors\">E-mail is required.</div>";
			$error_counter++;
		}
		if( $email != "" && ! isValidEmail($email) ) {
			$errors[ "email_error" ] = "<div class=\"errors\">A valid E-mail is required.</div>";
			$error_counter++;
		}
		if( $security_question_id == "" ) {
			$errors[ "security_question_id_error" ] = "<div class=\"errors\">A valid E-mail is required.</div>";
			$error_counter++;
		}
		if( $security_answer == "" ) {
			$errors[ "security_answer_error" ] = "<div class=\"errors\">Security Answer is required.</div>";
			$error_counter++;
		}
		$this->setMessages( 'errors', $errors );
		return ( $error_counter ) ? FALSE : TRUE;
	}*/
}
