<?php 

if( file_exists( DAOPATH . '/MainDao.php' ) ) require_once( DAOPATH . '/MainDao.php' );

class IndexMasterDAO extends MainDao{
	public $_table = 'users';
	
	public function index( $data = array() ) {
		if( empty( $data ) ) return FALSE;
		try {
			$query = "SELECT * FROM ".$this->_table." WHERE username = :username AND password = :password";
			$smtp = $this->db->prepare($query);
			$smtp->execute( array( ':username' => $data['username'], ':password' => md5($data['password']) ) );
			$smtp->execute();
			return $smtp->fetch();
		} catch(PDOException  $e){
			echo "Error: ".$e->getMessage();
		}
	}
}

