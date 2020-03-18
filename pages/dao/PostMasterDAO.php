<?php
class PostMasterDAO extends MainDao{
	public $_table 	= "master_posts";
	public $_view 	= "master_posts_view";
	public $_presentations 	= "presentations";
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_posts_with_pagging($where_clause = 1, $limit = 5) {
		$this->pagging->page  	= 1;
		if( $this->pagging->page_type == "posts" ) {
			$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
		}
		
		$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;
		
		$where_clause 			= ( $where_clause == "" ) ? 1 : $where_clause;
		$start 					= ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;
		$query = "SELECT id FROM `".$this->_view."` WHERE ".$where_clause;
        
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		$this->pagging->max_row = $resutl->rowCount();
		$query = "SELECT * FROM `".$this->_view."` WHERE ".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
		$posts = $this->select($query);
		$this->pagging->data = $posts;
		return $posts;
	}
	public function get_posts_with_pagging_publist($where_clause = 1, $limit = 10) {
		$this->pagging->page  	= 1;
		//if( $this->pagging->page_type == "posts" ) {
			$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
		//}
		$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;
		$where_clause 			= ( $where_clause == "" ) ? 1 : $where_clause;
		$start 					= ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;
		$query = "SELECT id FROM `".$this->_view."` WHERE ".$where_clause." AND active_category = 1";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		$this->pagging->max_row = $resutl->rowCount();
		$query = "SELECT * FROM `".$this->_view."` WHERE ".$where_clause." AND active_category = 1 LIMIT ".$start.", ".$this->pagging->limit;
		$posts = $this->select($query);
		$this->pagging->data = $posts;
		return $posts;
	}
	
	public function get_posts_with_pagging_submitted($where_clause = 1, $limit = 10) {
		$this->pagging->page  	= 1;
		if( $this->pagging->page_type == "posts" ) {
			$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
		}
		$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;
		$where_clause 			= ( $where_clause == "" ) ? 1 : $where_clause;
		$start 					= ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;
		$query = "SELECT id FROM `".$this->_view."` WHERE ".$where_clause;
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		$this->pagging->max_row = $resutl->rowCount();
		$query = "SELECT * FROM `".$this->_view."` WHERE ".$where_clause." LIMIT ".$start.", ".$this->pagging->limit;
		
                $posts = $this->select($query);
		$this->pagging->data = $posts;
		return $posts;
	}
	public function get_submitpresentation_with_pagging($where_clause = 1, $limit = 10) {
		$this->pagging->page  	= 1;
		//if( $this->pagging->page_type == "presentations" ) {
			$this->pagging->page  	= isset($_GET['page']) ? $_GET['page'] : 1;
		//}
		$this->pagging->limit  	= ($limit > 1) ? $limit : $this->pagging->limit;
		$where_clause 			= ( $where_clause == "" ) ? 1 : $where_clause;
		$start 			= ( $this->pagging->page > 1 ) ? ($this->pagging->limit * $this->pagging->page) - $this->pagging->limit : 0;
		$query = "SELECT id FROM `master_presentations_view` WHERE ".$where_clause." AND category_deleted = 0";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		$this->pagging->max_row = $resutl->rowCount();
		$query = "SELECT * FROM `master_presentations_view` WHERE ".$where_clause." AND category_deleted = 0 LIMIT ".$start.", ".$this->pagging->limit;
                $posts = $this->select($query);
		$this->pagging->data = $posts;
		return $posts;
	}
	
	
	public function get_category_label_1() {
		$query = "SELECT id , category_label_1 FROM master_post_categories GROUP BY category_label_1";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
	public function getsubmittedpostdetails($id) {
		$query = "SELECT * FROM ".$this->_view." WHERE status_id = 2 AND id = ".$id." AND active_category = 1";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
     public function getpostsubmittedcompletedata($id) {
		$query = "SELECT post.* ,upd.file_path, upd.file_type,upd.name FROM ".$this->_view." as post 
		LEFT JOIN post_uploads as upd ON upd.post_id = post.id 
		WHERE post.id = ".$id." AND post.status_id = 2 AND post.active_suggested_category = 1";
        
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
        }
     public function getpostpublisheddata($id) {
		$query = "SELECT  post.* ,upd.file_path,upd.file_type,upd.name 
		FROM ".$this->_view." as post LEFT JOIN master_post_categories as mpc ON mpc.id = post.post_category_id 
		LEFT JOIN post_uploads as upd ON upd.post_id = post.id 
		WHERE post.id = '".$id."' AND post.status_id = 3 AND mpc.is_active = 1";

		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}

	public function get_category_label_2($category_label) {
		$query = "SELECT id, category_label_2 FROM master_post_categories WHERE category_label_1 ='".$category_label."' AND is_active = 1 GROUP BY category_label_2";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
	
	public function get_category_label_3($category_label_second) {
		$query = "SELECT id,category_label_3 FROM master_post_categories WHERE category_label_2 ='".$category_label_second."'";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
	
	public function postDataInsert($data,$submitQuery) {
		if (is_numeric($data['category_label_2'])) {
			$data['category_label'] = $data['category_label_2'];
		} else {
			$data['category_label'] = $data['category_label_3'];
		}
		$query = "INSERT INTO master_posts (suggestted_category_id,post_status_id,title ,author,synopsis,description,is_deadline, last_status_date,created_date) 
		VALUES ('".$data['category_label']."','".$submitQuery."','".$data['title']."', '".$data['author']."','".$data['synopsis']."','".$data['description']."',
		'".$data['is_headline']."',NOW(),NOW())";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $this->db->lastInsertId();
	}
        
	public function tagdataInsert($tag) {
		$query = "INSERT INTO master_tags (tag_name)  VALUES ('".$tag."')";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $this->db->lastInsertId();
	}
        
	public function postDatauploadInsert($upload) {
		$query = "INSERT INTO  post_uploads (post_id,file_path,file_type ,name,created_date) 
		VALUES ('".$upload['postId']."','".$upload['finalpath']."','".$upload['filetype']."','".$upload['fileName']."',NOW())";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $this->db->lastInsertId();
	}
        
	public function getpostdata($id) {
		$query = "SELECT * FROM ".$this->_view." WHERE id = '".$id."' AND active_category = 1" ;
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
	public function getcategorydata($id) {
		$query = "SELECT suggestted_category_id FROM ".$this->_view." WHERE id = '".$id."' " ;
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		$fetchid = $resutl->fetchAll();
		$fetchidId = $fetchid[0]->suggestted_category_id ;
		$query1 = "SELECT * FROM  master_post_categories  WHERE id = '".$fetchidId."' " ;
		$resutll = $this->db->prepare($query1);
		$resutll->execute();
                return $resutll->fetchAll();
              
	}
        
	public function getpostuploaddataofrimage($id) {
		$query = "SELECT * FROM  post_uploads  WHERE post_id = '".$id."' AND file_type LIKE  'image/%' " ;
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
	public function getpostuploaddataofrPdf($id) {
		$query = "SELECT * FROM  post_uploads  WHERE post_id = '".$id."' AND file_type LIKE  'application/%' " ;
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
	public function postDataUpdate($data,$submitQuery) {
		if (is_numeric($data['category_label_2'])) {
			  $data['category_label'] = $data['category_label_2'];
		   } else {
			   $data['category_label'] = $data['category_label_3'];
		   }
		$query = "UPDATE master_posts 
						SET suggestted_category_id ='".$data['category_label']."',
							title                  ='".$data['title']."',
							author                 ='".$data['author']."',
							description            ='".$data['description']."',
							synopsis               ='".$data['synopsis']."',
							post_status_id               ='".$submitQuery."'
							WHERE id = '".$data['id']."' " ;
		$resutl = $this->db->prepare($query);
					  $resutl->execute();
	
	}

	public function get_categories($clause = "") {
		$clause = ( $clause == "" ) ? 1 : $clause;
		$query = "SELECT * FROM master_post_categories WHERE ".$clause;
		return $this->select($query);
	}

	public function get_post( $post_id = 0 ) {
		if( ! $post_id ) return array();
		return $this->select( "SELECT * FROM ".$this->_view." WHERE id = ".$post_id );
	}
	
	public function get_post_uploads($post_id = 0) {
		if( ! $post_id ) return array();
		return $this->select( "SELECT * FROM post_uploads WHERE post_id = ".$post_id );
	}
        
        public function getmostviewd() {
		$query = "SELECT  *  FROM ".$this->_view."  ORDER BY  total_view DESC LIMIT 4  ";
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
	
	public function recent_modified_post(){
		$query = "SELECT mpc.category_label_1,mpc.category_label_2,mpc.category_label_3,mpc.category_label_2, post.id ,post.title, post.post_category_id,
		post.is_headline,post.synopsis,post.description,post.featured_image_path,post.modified_date 
		FROM ".$this->_view." as post 
		LEFT JOIN master_post_categories as mpc ON mpc.id = post.post_category_id 
		WHERE post.status_id = 3 AND post.active_category = 1 ORDER BY  post.modified_date DESC LIMIT 4";
			  
		$resutl = $this->db->prepare($query);
		$resutl->execute();
		return $resutl->fetchAll();
	}
}


