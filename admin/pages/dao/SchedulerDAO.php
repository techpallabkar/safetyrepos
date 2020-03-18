<?php

class SchedulerDAO extends MainDao {

    public $_table          = "mis_sas";
    public $_table_ppe      = "mis_sas_ppe";
    public $_table_img      = "file_upload_audit_mapping";
    public $_table_ppe_img  = "file_upload_ppe_audit_mapping";

    public function __construct() {
        parent::__construct();
    }
    
    public function getTempSasDatails($getMisSasNo) {

        $query = "SELECT * FROM ". $this->_table." where sas_report_no like'%".$getMisSasNo."%'";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }
    public function getTempSasPpeDatails($getMisSasNo) {

        $query = "SELECT * FROM ". $this->_table_ppe." where sas_report_no like'%".$getMisSasNo."%'";
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }
    public function getSasSAImageDetails($clause) {

        $query = "SELECT * FROM ". $this->_table_img." where ".$clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }    
    public function getSasPPEImageDetails($clause) {

        $query = "SELECT * FROM ". $this->_table_ppe_img." where ".$clause;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }    
}

?>