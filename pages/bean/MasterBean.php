<?php 
class MasterBean {
	protected $request = array();
	protected $error_messages = array();
	
	public function __construct() {
		self::register_request_data();
	}
	
	private function register_request_data() {
		if( ! empty( $_REQUEST ) ) {
			foreach( $_REQUEST as $key => $value ) {
				if( is_array( $value ) ) {
					$data = array();
					foreach( $value as $array_field => $array_val ) {
						//$data[ "{$array_field}" ] = trim( addslashes($array_val) );
						$data[ "{$array_field}" ] = trim( $array_val );
					}
					$this->request["{$key}"] = $data;
				} elseif( ! is_array( $value ) ) {
					//$this->request["{$key}"] = trim( addslashes($value) );
					$this->request["{$key}"] = trim( $value );
				}
			}
		}
	}
	
	public function get_request($field = "") {
		if( $field == "" ) return $this->request;
		return isset($this->request["{$field}"]) ? $this->request["{$field}"] : "";
	}
	
	public function get_error_messages() {
		return $this->error_messages;
	}
	
	public function set_data($data = array()) {
		if( ! empty( $data ) ) {
			$this->request["data"] = $data;
		}
	}
}

