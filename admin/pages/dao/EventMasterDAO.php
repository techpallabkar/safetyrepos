<?php 
class EventMasterDAO extends MainDao {


    public $_table = "upcoming_event";

    public function __construct() {
        parent::__construct();
    }

    public function getEventsCategory($where_clause = 1) {
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $query = "SELECT * FROM event_category WHERE " . $where_clause. " ORDER BY id DESC";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }
    
    
    public function getEventsData($where_clause=1) {
            $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
            $query = "SELECT * FROM event_view WHERE ".$where_clause. " ORDER BY id DESC";
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll();
            
        }
    public function getEventsEditData($editid) {
            $query = "SELECT * FROM event_view WHERE id= :id";
            $resutl = $this->db->prepare($query);
            $resutl->execute(array(":id" => $editid));
            return $resutl->fetchAll();
            
        }
        
        public function deltData($deltid)
        {
            $query = "DELETE FROM upcoming_event WHERE id= :id";
            $resutl = $this->db->prepare($query);
            $resutl->execute(array(":id" => $deltid));
            return $resutl->fetchAll();
        }

}
