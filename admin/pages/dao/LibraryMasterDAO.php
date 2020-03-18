<?php  
class LibraryMasterDAO extends MainDao{
	public $_table 	= "library";
	public $_view 	= "master_library_view";
	public $_category_table 	= "master_library_categories";
	
	public function __construct() {
		parent::__construct();
	}
	
        /**
         * Modified by Srimanta
         * @param type $where_clause
         * @param type $limit
         * @param type $auth_user
         * @param type $passValue
         * @return type
         */
	public function get_library_with_pagging($where_clause = 1, $limit = 5, $auth_user = array(),$passValue) {
		$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
		$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;
		$where_clause 			= ( $where_clause == "" ) ? 1 : $where_clause;
		$start 					= ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;
		$query 	= "SELECT COUNT(id) AS maxrow FROM `".$this->_view."` WHERE ".$where_clause;
                $stmt = $this->db->prepare($query);
                $stmt->execute($passValue);
                $row = $stmt->fetch($this->fetch_obj);
		$this->pagging->max_row = count($row) ? $row->maxrow : 0;
		$rows = array();
		if( $this->pagging->max_row ) {
			$query1 = "SELECT * FROM ".$this->_view." WHERE ".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
                        $stmt1 = $this->db->prepare($query1);
                        $stmt1->execute($passValue);
                        $rows = $stmt1->fetchAll($this->fetch_obj);
		}
		
		$this->pagging->data = $rows;
		return $rows;
	}
	
	public function get_categories($clause = "") {
		$clause = ( $clause == "" ) ? 1 : $clause;
		$query = "SELECT * FROM `".$this->_category_table."` WHERE deleted = 0 AND ".$clause;
		$resutl = $this->db->prepare($query);
                $resutl->execute();
                return $resutl->fetchAll();
	}
	
        /**
         * Modififed By Srimanta
         * @param type $id
         * @return type
         */
	public function get_library( $id = 0 ){
            if( ! $id ) return array();
            $stmt = $this->db->prepare('SELECT * FROM '.$this->_table.' WHERE id = :id');
            $stmt->execute(array('id' => $id));
            $row = $stmt->fetch($this->fetch_obj);
            $errorInfo = $stmt->errorInfo();
            return ( count($row) > 0 ) ? $row : array();
	}
	
	public function purge_category_by_path( $path = "" ) {
		if( $path == "" ) return FALSE;
		
		$query = $this->db->prepare("UPDATE `".$this->_category_table."` SET `deleted` = 1 WHERE path LIKE ?");
		$query->execute(array($path.'%'));
		$this->set_query_error($query);
		$error = $this->get_query_error();
		return ( $error == "" ) ? TRUE : FALSE;
	}
	
	public function get_file_paths_by_category_path($path = "") {
		if( $path == "" ) return array();
		$query = "SELECT `file_path` FROM ".$this->_table." WHERE category_id IN ( SELECT id FROM `".$this->_category_table."` WHERE `path` LIKE ?)";
		$result = $this->db->prepare($query);
		$result->execute(array($path.'%'));
		return $result->fetchAll($this->fetch_obj);
	}
	
	public function change_all_subcategories_path($old_path, $path) {
		if( $old_path == "" && $path == "" ) return FALSE;
		$query = "UPDATE `".$this->_category_table."` SET path = REPLACE(path, :old_path, :path)";
		$result = $this->db->prepare($query);
		$result->bindValue( ':old_path', $old_path );
		$result->bindValue( ':path', $path );
		$result->execute();
		$this->set_query_error($result);
		$error = $this->get_query_error();
		return ( $error == "" ) ? TRUE : FALSE;
	}
}


