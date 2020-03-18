<?php 

class GalleryMasterDAO extends MainDao {

    public $_table = "upcoming_event";

    public function __construct() {
        parent::__construct();
    }

    public function getGalleryCategory($where_clause = 1,$passValue) {
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $query = "SELECT * FROM gallery_category WHERE " . $where_clause;
        $stmt = $this->db->prepare($query);
        $stmt->execute($passValue);
        return $stmt->fetchAll($this->fetch_obj);
    }
    
    public function getGalleryData($where_clause=1) {
            $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
            $query = "SELECT * FROM gallery_view WHERE ".$where_clause;
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll();
            
        }
        
    public function getGalleryEditData($editid) {
        $query = "SELECT * FROM gallery_view WHERE id = :id";
        $resutl = $this->db->prepare($query);
        $resutl->execute(array(":id" => $editid));
        return $resutl->fetchAll();

    }
    public function deltData($deltid)
        {
            $query = "DELETE FROM gallery WHERE id = :id";
            $resutl = $this->db->prepare($query);
            $resutl->execute(array(":id" => $deltid));
            return $resutl->fetchAll();
        }

}
