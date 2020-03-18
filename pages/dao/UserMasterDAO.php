<?php
class UserMasterDAO extends MainDao{
	public $_table = "master_users";
	private $parent = '';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function login( $data = array() ) {
		if( empty( $data ) ) return FALSE;
		$query = "SELECT * FROM ".$this->_table." WHERE employee_code = :employee_code AND password = :password";
		$smtp = $this->db->prepare($query);
		$smtp->execute( 
			array( 
				":employee_code" 	=> $data["employee_code"], 
				":password" 		=> md5($data["password"]) 
			)
		);
		$row = $smtp->fetch($this->fetch_obj);
		$this->set_query_error($smtp);
		return $row;
	}
	
	public function get_status_options() {
		return array(
			"post_status" => $this->select( "SELECT * FROM `master_post_status`" ), 
			"user_status" => $this->select( "SELECT * FROM `master_user_status`" )
		);
	}
	
	public function get_security_questions() {
		return $this->select( "SELECT * FROM `master_security_questions`" );
	}
	
	public function check_forgot_password_step_1( $data = array() ) {
		if( empty( $data ) ) return FALSE;
		$query = "SELECT id FROM ".$this->_table." 
		WHERE employee_code = :employee_code AND security_question_id = :security_question_id AND security_answer = :security_answer";
		$smtp = $this->db->prepare($query);
		$smtp->execute( 
			array( 
				":employee_code" 		=> $data["employee_code"], 
				"security_question_id" 	=> $data["security_question_id"], 
				"security_answer" 		=> $data["security_answer"]
			)
		);
		$this->set_query_error($smtp);
		return $smtp->fetch($this->fetch_obj);
	}
	
	public function reset_password( $data = array() ) {
		if( empty( $data ) ) return FALSE;
		return $this->save($data);
	}
	
	public function check_email_exists($email) {
		if( $email == "" ) return FALSE;
		$query 	= "SELECT id FROM ".$this->_table." WHERE email = :email";
		$result = $this->db->prepare($query);
		$result->execute( array( ":email" => $email ) );
		$this->set_query_error($result);
		$row = $result->fetch($this->fetch_obj);
		$id = isset($row->id) ? $row->id : 0;
		return ($id > 0) ? TRUE : FALSE;
	}
	
	public function check_employee_code_exists($employee_code) {
		if( $employee_code == "" ) return FALSE;
		$query 	= "SELECT id FROM ".$this->_table." WHERE employee_code = :employee_code";
		$result = $this->db->prepare($query);
		$result->execute( array( ":employee_code" => $employee_code ) );
		$this->set_query_error($result);
		$row = $result->fetch($this->fetch_obj);
		$id = isset($row->id) ? $row->id : 0;
		return ($id > 0) ? TRUE : FALSE;
	}
	
	public function activate_new_user( $tokenid = "" ) {
		if( $tokenid == "" ) return FALSE;
		$query = "UPDATE ".$this->_table." SET status_id = :status_id WHERE tokenid = :tokenid";
		$result = $this->db->prepare($query);
		$result->bindValue(':status_id', 2);
		$result->bindValue(':tokenid', $tokenid);
		$result->execute();
		$this->set_query_error($result);
		return ( $this->get_query_error() == "" ) ? TRUE : FALSE;
	}
}

