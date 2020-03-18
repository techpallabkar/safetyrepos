<?php
class BeanUi {
	private $viewData = array();
	
	public function set_view_data( $label = 'data', $value = '' ) {
		if( isset($this->viewData[ "$label" ]) ) {
			if( ! is_array($this->viewData[ "$label" ]) && $this->viewData[ "$label" ] != "" && $value != "" ) {
				$this->viewData[ "$label" ] .= $value;
			} elseif( is_array($this->viewData[ "$label" ]) && !empty($this->viewData[ "$label" ]) && !empty($value) ) {
				array_merge($this->viewData[ "$label" ], $value);
			}
		} else {
			$this->viewData[ "$label" ] = $value;
		}
	}
	
	public function get_view_data( $label = '' ) {
		return isset( $this->viewData[ "$label" ] ) ? $this->viewData[ "$label" ] : "";
	}
	
	public function set_error($label, $message) {
		$_SESSION['errors'][ $label . "_error" ] = '<div class="error">'.$message.'</div>';
	}
	
	public function get_error( $label = '' ) {
		$msg = isset( $_SESSION['errors'][ $label . "_error" ] ) ? $_SESSION['errors'][ $label . "_error" ] : '';
		if( empty( $msg ) ) $msg = isset( $_SESSION["$label"] ) ? $_SESSION["$label"] : '';
		return $msg;
	}
	
	public function get_message() {
		return isset($_SESSION[ "message" ]) ? $_SESSION[ "message" ] : "";
	}
	
	public function set_error_message( $value = '' ) {
		$_SESSION[ "message" ] = "<div class=\"error\">".$value."</div>";
	}
	
	public function set_success_message( $value = '' ) {
		$_SESSION[ "message" ] = "<div class=\"success\">".$value."</div>";
	}
	
	public function reset_session_vars() {
		$_SESSION[ 'errors' ] 	= array();
		$_SESSION[ 'message' ] 	= '';
		$_SESSION[ 'data' ] 	= array();
	}
	
	public function set_array_to_view_data($data = array(), $skip = array()) {
		if( empty( $data ) ) return FALSE;
		foreach( $data as $field => $value ) {
			//$this->viewData[ "$field" ] = stripslashes($value);
			//$this->viewData[ "$field" ] = htmlentities(stripslashes($value));
			//$this->viewData[ "$field" ] = $value;
			if( in_array( $field, $skip ) ) {
				$this->viewData[ "$field" ] = stripslashes($value);
			} else {
				$this->viewData[ "$field" ] = htmlentities(stripslashes($value));
			}
		}
	}
}
