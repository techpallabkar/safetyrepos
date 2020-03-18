<?php
class Datatable {
	private $datatable = array(
		'tableID' 		=> 'example', 
		'bProcessing' 		=> 'true', 
		'bJQueryUI' 		=> 'true', 
		'sPaginationType' 	=> 'full_numbers', 
		'sDom' 			=> 'T<"clear">lifrtip', 
		'sSearch' 		=> 'Search all columns:', 
		'aLengthMenu' 		=> '25, 50, 100, 200', 
		'sSwfPath' 		=> 'assets/datatable/swf/copy_csv_xls_pdf.swf', 
		'aButtons' 		=> array(
			array(
				'sExtends' 	=> 'pdf', 
				'sFileName' 	=> 'filelist.pdf', 
				'mColumns' 	=> '0, 1, 2, 3, 4, 6, 7, 8, 9, 10'
			), 
			array(
				'sExtends' 	=> 'xls', 
				'oSelectorOpts' => array( 'page' => 'current' ),
				'sFileName' 	=> 'filelist.xls', 
				'mColumns' 	=> '0, 1, 2, 3, 4, 6, 7, 8, 9, 10'
			)
		), 
		'aoColumns' 		=> array(
			'null', 'null', 'null', 'null', '{"iDataSort": 5}', '{"bVisible": false}', 
			'null', 'null', 'null', 'null', 'null', 'null', '{"bSortable":false}'
		)
	);
	
	public function __construct($datatable = array()) {
		if( !empty( $datatable ) ) $this->datatable = $datatable;
	}
	
	public function getJqDatatable() {
		$dtstr = '<script type="text/javascript">'."\n";
		$dtstr .= '$(document).ready(function() {'."\n";
			$dtstr .= '$("#"'.$this->datatable['tableID'].'").dataTable({'."\n";
				$dtstr .= '"bProcessing" : '.$this->datatable['bProcessing'].', '."\n";
				$dtstr .= '"bJQueryUI" : '.$this->datatable['bJQueryUI'].', '."\n";
				$dtstr .= '"sPaginationType" : '.$this->datatable['sPaginationType'].', '."\n";
				$dtstr .= '"sDom" : "'.$this->datatable['sDom'].'", '."\n";
				$dtstr .= '"oLanguage" :{ "sSearch" : "'.$this->datatable['sDom'].'" }, '."\n";
				$dtstr .= '"oTableTools" :{ "sSearch" : "'.$this->datatable['sDom'].'" }, '."\n";
			$dtstr .= '});'."\n";
		$dtstr .= '});'."\n";
		$dtstr .= '</script>'."\n";
	}
}
