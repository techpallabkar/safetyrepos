<?php
class PostMasterBeanUi {
	private $viewData = array();
	
	public function set_view_data( $label = 'data', $value = '' ) {
		if( isset($this->viewData[ "$label" ]) ) {
			if( !is_array($this->viewData[ "$label" ]) && $this->viewData[ "$label" ] != "" && $value != "" ) {
				$this->viewData[ "$label" ] .= $value;
			} elseif( is_array($this->viewData[ "$label" ]) && !empty($this->viewData[ "$label" ]) && !empty($value) ) {
				array_merge($this->viewData[ "$label" ], $value);
			}
		} else {
			$this->viewData[ "$label" ] = $value;
		}
	}
	
	public function get_view_data( $label = '' ) {
		return isset( $this->viewData[ "$label" ] ) ? $this->viewData[ "$label" ] : '';
	}
}
