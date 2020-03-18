<?php
class BuyersGuideMasterDAO extends MainDao{
	public $_table 	= "buyers_guide";
	public $_view 	= "master_buyers_guide_view";
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_buyers_guides_with_pagging($where_clause = 1, $limit = 10, $auth_user = array()) {
		$this->pagging->page  	= 1;
		if( $this->pagging->page_type == "buyers_guide" ) {
			$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
		}
		$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;
		$where_clause 			= ( $where_clause == "" ) ? 1 : $where_clause;
		$start 					= ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;
		
		$query = "SELECT COUNT(id) AS maxrow FROM `".$this->_view."` WHERE ".$where_clause;
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		$row = $resutl->fetch($this->fetch_obj);
		$this->pagging->max_row = count($row) ? $row->maxrow : 0;
		
		$rows = array();
		$query = "SELECT * FROM ".$this->_view." WHERE ".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
		$rows = $this->select($query);
		
		$this->pagging->data = $rows;
		return $rows;
	}
	
	public function get_categories($clause = "") {
		$clause .= ( $clause != "" ) ? "deleted = 0 AND " . $clause : "deleted = 0";
		$query = "SELECT * FROM master_buyers_guide_categories WHERE ".$clause;
		return $this->select($query);
	}
	
	public function get_buyers_guide( $id = 0 ) {
		if( ! $id ) return array();
		$rows = $this->select( "SELECT * FROM ".$this->_table." WHERE id = ".$id );
		return ( count($rows) > 0 ) ? $rows[0] : array();
	}
}

