<?php 
class BulletinDAO extends MainDao {

    public function __construct() {
        parent::__construct();
    }

    public function getBulletinBoardData($where_clause = 1) {
        $where_clause = ( $where_clause == "" ) ? 1 : $where_clause;
        $query = "SELECT * FROM bulletin_board WHERE " . $where_clause. " ORDER BY id DESC";
        return $this->select($query);
    }
        
    public function deltData($deltid)
    {
        $query = "DELETE FROM bulletin_board WHERE id=".$deltid;
        return $this->select($query);
    }
    
    public function changeBulletinStatus($rowID)
    {
        $query = "SELECT status from  bulletin_board WHERE id=".$rowID;
        $rowdata = $this->select($query);
        if(!empty($rowdata))
        {
            $rowStatus = $rowdata[0]->status;
            if( $rowdata[0]->status == 1 )
            {
                $this->select("UPDATE bulletin_board SET status='0' WHERE id='".$rowID."'");
            }
            else
            {
                $this->select("UPDATE bulletin_board SET status='1' WHERE id='".$rowID."'");
            }
        }
      
    }

}
