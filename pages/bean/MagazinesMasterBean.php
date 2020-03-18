<?php
class MagazinesMasterBean extends MasterBean {
	
	public function magazines_validation() {
		$errors 		= array();
		$data 			= $this->get_request("data");
		$title 			= isset( $data["title"] ) ? trim( $data["title"] ) : "";
		$status_id 		= isset( $data["status_id"] ) ? trim( $data["status_id"] ) : 0;
		$error_counter 	= 0;
		
		if( $title == "" ) {
			$this->error_messages[ "title_error" ] = "<div class=\"errors\">Title is required.</div>";
			$error_counter++;
		}
		if( ! $status_id ) {
			$this->error_messages[ "status_id_error" ] = "<div class=\"errors\">Status value is empty.</div>";
			$error_counter++;
		}
		if( $status_id == 3 ) {
			$category_id 			= isset( $data["category_id"] ) ? trim( $data["category_id"] ) : 0;
			$author 				= isset( $data["author"] ) ? trim( $data["author"] ) : "";
			$featured_image_path 	= isset( $data["featured_image_path"] ) ? trim( $data["featured_image_path"] ) : "";
			$pdf_file_name 			= isset( $data["file_path"] )	? $data["file_path"] : "";
			
			if( $category_id == "" || ! $category_id ) {
				$this->error_messages[ "category_id_error" ] = "<div class=\"errors\">Category is required.</div>";
				$error_counter++;
			}
			if( $author == "" ) {
				$this->error_messages[ "author_error" ] = "<div class=\"errors\">Author is required.</div>";
				$error_counter++;
			}
			if( $featured_image_path == "" ) {
				$this->error_messages[ "featured_image_error" ] = "<div class=\"errors\">Featured Image is required.</div>";
				$error_counter++;
			}
			if( $pdf_file_name == "" ) {
				$this->error_messages[ "file_path_error" ] = "<div class=\"errors\">Upload Pdf File is required.</div>";
				$error_counter++;
			}
		}
		return ( $error_counter ) ? FALSE : TRUE;
	}
}

