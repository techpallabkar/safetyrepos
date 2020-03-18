<?php
class PresentationMasterDAO extends MainDao{
	public $_table 	= "presentations";
	public $_view 	= "master_presentations_view";
	public $_category = "master_presentation_categories";
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_presentation_with_pagging($where_clause = 1, $limit = 5, $auth_user = array()) {
		$this->pagging->page  	= 1;
		
		if( $this->pagging->page_type == "presentations" ) {
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
		if( $this->pagging->max_row ) {
			$query = "SELECT * FROM ".$this->_view." WHERE ".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
			$rows = $this->select($query);
		}
		
		$this->pagging->data = $rows;
		return $rows;
	
        }
	public function get_categories($clause = "") {
		$clause = ( $clause == "" ) ? 1 : $clause;
		$query = "SELECT * FROM master_presentation_categories WHERE ".$clause." AND deleted = 0";
		return $this->select($query);
	}
	
	public function getpresentation($id = 0, $subid = 0) {
		if($id > 0) {
			$query = "SELECT * FROM ".$this->_view." WHERE status_id = 3 AND id = ".$id." AND category_deleted = 0";
		} elseif( $subid > 0 ){
			$query = "SELECT pv.*, pv.category_path AS `path` FROM ".$this->_view." as pv WHERE pv.status_id = 2 AND pv.id = ".$subid." AND pv.category_deleted = 0";
		} else {
			$query = "SELECT pv.*, pv.category_path AS `path` FROM ".$this->_view." as pv WHERE pv.status_id = 3 AND pv.category_deleted = 0 ORDER BY pv.modified_date DESC ";
		}

		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
	
	public function get_presentation_by_category_id($category_id = 0, $limit = 5) {
		$this->pagging->page  	= 1;
		if( $this->pagging->page_type == "presentations" ) {
			$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
		}
		$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;
		$where_clause 			= " WHERE category_deleted = 0 AND status_id = 3";
		$start 					= ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;
		
		$query = "SELECT COUNT(id) AS maxrow FROM `".$this->_view."`";
		if( $category_id ) {
			$where_clause .= " AND category_id IN (
				SELECT a.id FROM `".$this->_category."` AS a 
				INNER JOIN `".$this->_category."` AS b ON a.path LIKE CONCAT( b.path, '%') AND b.id = '".$category_id."'
			)";
		}
		
		$query .= $where_clause;
		
		$result = $this->db->prepare($query);
		$result->execute();
		$row = $result->fetch($this->fetch_obj);
		$this->pagging->max_row = count($row) ? $row->maxrow : 0;
		$rows = array();
		if( $this->pagging->max_row ) {
			$query 	= "SELECT * FROM `".$this->_view."`".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
			$rows 	= $this->select($query);
		}
		return $rows;
	}
	
	public function get_presentation( $id = 0 ) {
		if( ! $id ) return array();
		$rows = $this->select( "SELECT * FROM ".$this->_view, array( "id" => $id ), 1 );
		return ( count($rows) > 0 ) ? $rows : array();
	}
}


