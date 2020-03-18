<?php 
class CmsMasterDAO extends MainDao{
	public $_table 	= "important_information";

	
	public function __construct() {
		parent::__construct();
	}
	
        public function getimportantinformation($where_clause=1,$pval) {
            $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
            $query = "SELECT * FROM important_information WHERE ".$where_clause;
            $resutl = $this->db->prepare($query);
            $resutl->execute($pval);
            return $resutl->fetchAll();
        }
       
        public function getCMSData($where_clause=1,$pvalue) {
            $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
            $query = "SELECT * FROM cms WHERE ".$where_clause;
            $resutl = $this->db->prepare($query);
            $resutl->execute($pvalue);
            return $resutl->fetchAll();
        }
        
        public function getMessagesData($where_clause=1)
        {
            $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
            $query = "SELECT * FROM messages WHERE ".$where_clause;
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll();
        }
        public function getData($editid)
        {
            $query = "SELECT * FROM messages WHERE id= :id";
            $resutl = $this->db->prepare($query);
            $resutl->execute(array(":id" => $editid));
            return $resutl->fetchAll();
        }
        public function deltData($deltid)
        {
            $query = "DELETE FROM messages WHERE id= :id";
            $resutl = $this->db->prepare($query);
            $resutl->execute(array(":id" => $deltid));
            return $resutl->fetchAll();
        }
        public function getstatusData($statusid)
        {
            
            $query = "UPDATE messages SET status = 0";
            $this->select($query);
            $query2 = "UPDATE messages SET status = 1 WHERE id=$statusid";
            $this->select($query2);
            return true;
            
            
        }
        
}


