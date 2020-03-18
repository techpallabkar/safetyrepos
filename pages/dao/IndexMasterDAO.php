<?php
class IndexMasterDAO extends MainDao{
	public $_table = 'activity';
	public $_tablemaster     = "master_posts";
	public $_view = "activity_view";
	
	public function __construct() {
		parent::__construct();
	}
	
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
	
	public function getheadline() {
		$query = "SELECT * FROM ".$this->_view."  WHERE is_headline = '1' AND status_id = '3' ORDER BY modified_date DESC LIMIT 1";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		$headlinerow = $resutl->fetchAll();
		return !empty($headlinerow) ? $headlinerow[0] : array();
	}  
	public function getsearchData($searchdata,$table) {
		$query = "select * from $table Where (activity_name LIKE '%".$searchdata."%' 
                         OR subject_details LIKE '%".$searchdata."%' 
                         OR remarks LIKE '%".$searchdata."%' 
                         OR place LIKE '%".$searchdata."%') AND status_id ='3' 
                        ";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		$headlinerow = $resutl->fetchAll();
		return !empty($headlinerow) ? $headlinerow : array();
	}  
	public function getAuditsearchData($searchdata,$table) {
		$query = "select * from $table Where (activity_name LIKE '%".$searchdata."%' 
                         OR place LIKE '%".$searchdata."%' 
                         OR remarks LIKE '%".$searchdata."%' 
                         OR status_public_name LIKE '%".$searchdata."%') AND status_id ='3' 
                        ";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		$headlinerow = $resutl->fetchAll();
		return !empty($headlinerow) ? $headlinerow : array();
	}  
	 
	 
        public function get_activity_type_master($clause = "") {
		$clause = ( $clause == "" ) ? 1 : $clause;
		 $query = "SELECT * FROM activity_type_master WHERE ".$clause;
		//die($query);
		return $this->select($query);
	}
         public function get_division_department($clause = "") {
            $clause = ( $clause == "" ) ? 1 : $clause;
            $query = "SELECT * FROM division_department WHERE ".$clause;
            return $this->select($query);
        }
        public function get_division_department_mapping($clause = "",$tb) {
            $clause = ( $clause == "" ) ? 1 : $clause;
            $query = "SELECT * FROM ".$tb." WHERE ".$clause;//die($query);
            return $this->select($query);
        }
        public function get_activity_by_actype_id($actype_id) {
	  $query = "SELECT * FROM ".$this->_view."  WHERE activity_type_id = ".$actype_id." AND status_id=3 ORDER BY id DESC LIMIT 3";
	  $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
        
        public function checkSafObsDataExist($param) {
             $query = "SELECT * "
                   . " FROM safety_observation_view  "
                   . "WHERE CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) LIKE '$param%' AND  status_id=3 AND created_by!='2' "
                   . "AND deleted='0' AND activity_year!='0000' AND activity_month!=0 "; 
             //echo $query;
            $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll(); 
        }
        public function get_nonactivity_by_actype_id($tble,$actype_id,$coldatename) {
            /****************/
            $mid_date_of_month=date('Y-m-15');
            $mid_date_next_month=date('Y-m-d',strtotime('+1 Month',strtotime($mid_date_of_month)));
            $current_date=date('Y-m-d');
            if(($current_date >= $mid_date_of_month) && ($current_date < $mid_date_next_month))
            {
                $month_year_1=date('Y-m',strtotime('-1 Month',strtotime($current_date)));
            } else {

                $month_year_1=date('Y-m',strtotime('-2 Month',strtotime($current_date)));
            }
            $month_year_2   =   $month_year_1;    

            $checkdataexist =   $this->checkSafObsDataExist($month_year_2);
            $month_year_2_back   =  date( 'Y-m',strtotime( '-2 Month', strtotime( $current_date ) ) );
            $month_year          =  ($checkdataexist != 0 ) ? $month_year_2 : $month_year_2_back ;
            /****************/
            
            if($actype_id == 8) {
            $query = "SELECT id,created_by,deleted,activity_type_id,set_type,activity_month,activity_year,SUM(activity_count) activity_count,place,status_name "
                   . " FROM ".$tble."  "
                   . "WHERE CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) <= '$month_year' AND activity_type_id = ".$actype_id." AND status_id=3 AND created_by!='2' "
                   . "AND deleted='0' AND activity_year!='0000' AND activity_month!=0 "             
                   . " GROUP BY activity_month,activity_year"
                   . " ORDER BY CONCAT((activity_year),'-', (lpad(activity_month,2,'0'))) DESC LIMIT 0,3 ";     
            } else {
	  $query = "SELECT * "
                  . "FROM ".$tble."  WHERE activity_type_id = ".$actype_id." AND status_id=3 AND created_by!='2' AND deleted='0' "
                  . "ORDER BY ".$coldatename." DESC LIMIT 0,3";
//          if($actype_id == 5)
//          echo $query;
            }
            
	  $resutl = $this->db->prepare($query);
	 $resutl->execute();
	return $resutl->fetchAll();
	}
	public function gettITopKaizens() {
	  $query = "SELECT mpc.category_label_1,mpc.category_label_2,mpc.id as cid,mpc.category_label_3,post.id ,post.title,post.post_category_id,post.is_headline,post.synopsis,post.description,post.featured_image_path,post.modified_date 
			  FROM ".$this->_view." as post LEFT JOIN master_post_categories as mpc ON mpc.id = post.post_category_id  WHERE mpc.id IN (4,5,6) AND post.status_id = '3' ORDER BY post.modified_date DESC LIMIT 3";
	  $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function getdirectormessage() {
	  $query = "SELECT * FROM messages WHERE status ='1'";
	  $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function gettICaseStudies() {
	 $query = "SELECT mpc.category_label_1,mpc.category_label_2,mpc.id as cid,mpc.category_label_3, post.id ,post.title, post.post_category_id,post.is_headline,post.synopsis,post.description,post.featured_image_path,post.modified_date 
			   FROM ".$this->_view." as post LEFT JOIN master_post_categories as mpc ON mpc.id = post.post_category_id  WHERE mpc.id IN (7,8,9) AND post.status_id = '3' ORDER BY post.modified_date DESC LIMIT 3";
			   $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function getkcFundamentals() {
	  $query = "SELECT mpc.category_label_1,mpc.category_label_2,mpc.id as cid,mpc.category_label_3,post.id ,post.title,post.post_category_id,post.is_headline,post.synopsis,post.description,post.featured_image_path,post.modified_date 
			  FROM ".$this->_view." as post LEFT JOIN master_post_categories as mpc ON mpc.id = post.post_category_id  WHERE mpc.id IN (10,11,12) AND post.status_id = '3' ORDER BY post.modified_date DESC LIMIT 3";
	  $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function getkcechnicalPapers() {
	 $query = "SELECT mpc.category_label_1,mpc.category_label_2,mpc.id as cid,mpc.category_label_3, post.id ,post.title, post.post_category_id,post.is_headline,post.synopsis,post.description,post.featured_image_path,post.modified_date 
			   FROM ".$this->_view." as post LEFT JOIN master_post_categories as mpc ON mpc.id = post.post_category_id  WHERE mpc.id IN (13,14,15) AND post.status_id = '3' ORDER BY post.modified_date DESC LIMIT 3";
			   $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function getarticles() {
	 /*$query = "SELECT mpc.category_label_1,mpc.category_label_2,mpc.id as cid, mpc.category_label_3,mpc.category_label_2, post.id ,post.title, post.post_category_id,post.is_headline,post.synopsis,post.description,post.featured_image_path,post.modified_date 
			   FROM ".$this->_view." as post LEFT JOIN master_post_categories as mpc ON mpc.id = post.post_category_id  WHERE mpc.id IN (1,2,3) AND post.status_id = '3'  ORDER BY  post.modified_date DESC LIMIT 4";
	*/
            $query = "SELECT  * FROM ".$this->_view." WHERE status_id='3' ORDER BY id DESC LIMIT 3";
          $resutl = $this->db->prepare($query);
          
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function recent_modified_post() {
	  $query = "SELECT mpc.category_label_1,mpc.category_label_2,mpc.category_label_3,mpc.category_label_2, post.id ,post.title, post.post_category_id,post.is_headline,post.synopsis,post.description,post.featured_image_path,post.modified_date 
			   FROM ".$this->_view." as post LEFT JOIN master_post_categories as mpc ON mpc.id = post.post_category_id WHERE post.status_id = '3'  ORDER BY  post.modified_date DESC LIMIT 4";
			   $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function getpostcompletedata($id) {
	  $query = "SELECT  post.* ,mpc.category_label_1,mpc.category_label_2,mpc.category_label_3,upd.*
			   FROM ".$this->_view." as post LEFT JOIN master_post_categories as mpc ON mpc.id = post.post_category_id   
					LEFT JOIN post_uploads as upd ON upd.post_id = post.id
				 WHERE post.id = '".$id."' AND post.status_id = '3' ";

	  $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function getcategorydata($category) {
		$query = "SELECT  post.* ,mpc.category_label_1,mpc.category_label_2,mpc.category_label_2
			   FROM ".$this->_view." as post LEFT JOIN master_post_categories as mpc ON mpc.id = post.post_category_id   WHERE mpc.category_label_2 = '".$category."' AND post.status_id = '3' ";
		echo  $query;die;    
		$resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function getarticlesdata($articles) {
	 $query = "SELECT  post.* ,mpc.category_label_1,mpc.category_label_2,mpc.category_label_3 FROM ".$this->_view." as post LEFT JOIN master_post_categories as mpc ON mpc.id = post.post_category_id   WHERE mpc.id = '".$articles."' AND post.status_id = '3' ";
	 
        
            $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function getmostviewd() {
	  $query = "SELECT  *  FROM ".$this->_view."  ORDER BY  total_view DESC LIMIT 4  ";
	  $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
	public function updatepostTag($flagtag,$id) {
		if(!is_numeric($flagtag)){
	   $query = "INSERT INTO popular_posts (post_id,total_view) VALUES('".$id."','1')";
	  }else{
	  $query = "UPDATE popular_posts SET total_view = total_view+1 WHERE post_id  = '".$id."' ";
	  }
	  $resutl = $this->db->prepare($query);
	$resutl->execute();
	return $resutl->fetchAll();
	}
        
        public function get_posts_with_paggings($where_clause = 1, $limit = 5) {
			$this->pagging->page  	= 1;
			//if( $this->pagging->page_type == "posts" ) {
				$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
			//}
			$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;
			$where_clause 			= ( $where_clause == "" ) ? 1 : $where_clause;
			$start 					= ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;
			$query = "SELECT id FROM `master_posts_view` WHERE ".$where_clause;
					
			$resutl = $this->db->prepare($query);
			$resutl->execute();
			$this->pagging->max_row = $resutl->rowCount();
			$query = "SELECT * FROM `master_posts_view` WHERE ".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
			//  echo $query ;die;
                        $posts = $this->select($query);
			$this->pagging->data = $posts;
			
			return $posts;
	}
        
public function getBulletinBoard() {
            $query = "SELECT  *  FROM bulletin_board where status='1' ORDER BY id DESC";
            $resutl = $this->db->prepare($query);
            $resutl->execute();
            return $resutl->fetchAll();
	}
        
        
}

