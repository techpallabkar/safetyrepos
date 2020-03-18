<?php 
class UserMasterDAO extends MainDao{
	public $_table = "master_users";
	private $parent = '';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function login( $data = array() ) {
		if( empty( $data ) ) return FALSE;
		$query = "SELECT * FROM ".$this->_table." "
                . "WHERE employee_code = :employee_code AND password = :password AND deleted = 0";
		$smtp = $this->db->prepare($query);
		$smtp->execute( 
                    array( 
                        ":employee_code" 	=> $data["employee_code"], 
                        ":password" 		=> md5($data["password"]), 
                    )
		);
		$row = $smtp->fetch($this->fetch_obj);
		$errorInfo = $smtp->errorInfo();
		$this->pdo_error = isset($errorInfo[2]) ? $errorInfo[2] : "";
		return $row;
	}
	
	public function get_status_options() {
		return array(
			"post_status" => $this->select( "SELECT * FROM `master_post_status`" ), 
			"user_status" => $this->select( "SELECT * FROM `master_user_status`" )
		);
	}
	
	public function get_users_with_pagging($where_clause = 1, $limit = 5) {
		$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
		$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;
		$where_clause 			= ( $where_clause == "" ) ? 1 : $where_clause;
		$start 					= ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;
		
		$query = "SELECT id FROM `".$this->_table."` WHERE ".$where_clause;
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		$this->pagging->max_row = $resutl->rowCount();
		
		$query = "SELECT master_users.*, userstatus.status_name FROM `".$this->_table."` 
		INNER JOIN master_user_status AS userstatus ON userstatus.id = master_users.status_id 
		WHERE ".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
		return $this->pagging->data = $this->select($query);
	}
	
	public function delete_user( $ids = "" ) {
		if( $ids == "" ) return FALSE;
		$query = "UPDATE ".$this->dao->_table." SET deleted = 1 WHERE id IN (".$ids.")";
		$result = $this->db->prepare($query);
		$result->execute();
		$this->set_query_error($result);
		return $result->rowCount();
	}
	public function getUserDetails( $ids = "" ) {
            if( $ids == "" ) return FALSE;
            $query = "SELECT * FROM  ".$this->_table." WHERE id = '".$ids."'"; 
          
            return $this->select($query);
	}
}

