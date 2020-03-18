<?php

class Wysiwyg_editor {
	
	public function getCssLinks() {
		return array('wysiwyg/css/style');
	}
	
	public function getJsLinks() {
		return array( 'wysiwyg/wysiwyg', 'wysiwyg/wysiwyg-settings' );
	}
	
	public function getFullEditor($editorId = '') {
		return '<script type="text/javascript">WYSIWYG.attach("'.$editorId.'", full);</script>';
	}
	
	public function getSmallEditor($editorId = '') {
		return '<script type="text/javascript">WYSIWYG.attach("'.$editorId.'", small);</script>';
	}
	
	public function getDefaultEditor($editorId = '') {
		return '<script type="text/javascript">WYSIWYG.attach("'.$editorId.'");</script>';
	}
}
