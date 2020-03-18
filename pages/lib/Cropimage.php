<?php
class Cropimage {
	
	public function jcropform( $data = array() ) {
		//$cropto = isset($data["cropto"]) ? $data["cropto"] : "";
		//if( $cropto == "" ) return "";
		
		$html = "
		<link rel=\"stylesheet\" href=\"".url("assets/css/jcrop/jquery.Jcrop.css")."\" type=\"text/css\" />
		<style type=\"text/css\">
		#target { background-color: #ccc; width: 500px; height: 330px; font-size: 24px; display: block; }
		.thumb_img img{ background-position : 10px 50px; height: 100px; width: 100px; }
		</style>
		<div style=\"width:100px;height:100px;overflow:hidden;margin-left:5px;\">
			<img src=\"".url($data["cropto"])."\" id=\"cropbox\" style=\"float:left; width:500px;\" id=\"preview\" />
		</div>
		<div style=\"float:left; width:200px; margin-right:50px;\">
			<img src=\"".url($data["cropto"])."\" id=\"cropbox\" style=\"float:left; width:500px;\" id=\"jcrop_target\" />
		</div>
		
		<br clear=\"all\" />
		<form action=\"\" method=\"post\" onsubmit=\"return checkCoords();\">
			<input type=\"hidden\" id=\"x\" name=\"x\" />
			<input type=\"hidden\" id=\"y\" name=\"y\" />
			<input type=\"hidden\" id=\"w\" name=\"w\" />
			<input type=\"hidden\" id=\"h\" name=\"h\" />
			<input type=\"submit\" value=\"Crop Image\" class=\"btn btn-large btn-inverse\" />
		</form>
		
		<script src=\"".url("assets/js/jcrop/jquery.Jcrop.min.js")."\"></script>
		<script src=\"".url("assets/js/jcrop/jquery.Jcrop.js")."\"></script>
		<script type=\"text/javascript\">
		jQuery(function(){
			var Preview = jQuery('#preview');
			
			function showPreview(coords) {
				if (parseInt(coords.w) > 0)	{
					var rx = 100 / coords.w;
					var ry = 100 / coords.h;

					Preview.css({
						width: Math.round(rx * 500) + 'px',
						height: Math.round(ry * 370) + 'px',
						marginLeft: '-' + Math.round(rx * coords.x) + 'px',
						marginTop: '-' + Math.round(ry * coords.y) + 'px'
					}).show();
				}
			}

			function hidePreview() {
				Preview.stop().fadeOut('fast');
			}
			
			jQuery('#jcrop_target').Jcrop({
				onChange: showPreview,
				onSelect: showPreview,
				onRelease : hidePreview, 
				aspectRatio: 1
			});
		});
		function updateCoords(c) {
			jQuery('#x').val(c.x);
			jQuery('#y').val(c.y);
			jQuery('#w').val(c.w);
			jQuery('#h').val(c.h);
			if( c.x > 0 ) {
				//showPreview();
				//jQuery('.thumb_img').css({ 'background-position' : c.x+'px '+c.y+'px;' });
			}
		};
		function checkCoords() {
			if (parseInt(jQuery('#w').val())) return true;
			alert('Please select a crop region then press submit.');
			return false;
		};
		</script>
		";
		
		
		$html = "
		<link href=\"http://edge1y.tapmodo.com/deepliq/global.css\" rel=\"stylesheet\" type=\"text/css\" />
  <link rel=\"stylesheet\" href=\"http://jcrop-cdn.tapmodo.com/v0.9.12/css/jquery.Jcrop.min.css\" type=\"text/css\" />
	<link href=\"http://edge1u.tapmodo.com/deepliq/jcrop_demos.css\" rel=\"stylesheet\" type=\"text/css\" />

	<script src=\"http://edge1u.tapmodo.com/global/js/jquery.min.js\"></script>
  <script src=\"http://jcrop-cdn.tapmodo.com/v0.9.12/js/jquery.Jcrop.min.js\"></script>
		<script language=\"Javascript\">

// Remember to invoke within jQuery(window).load(...)
// If you don't, Jcrop may not initialize properly
jQuery(function(){

	jQuery('#jcrop_target').Jcrop({
		onChange: showPreview,
		onSelect: showPreview,
    onRelease: hidePreview,
		aspectRatio: 1
	});

  var Preview = jQuery('#preview');
  // Our simple event handler, called from onChange and onSelect
  // event handlers, as per the Jcrop invocation above
  function showPreview(coords)
  {
    if (parseInt(coords.w) > 0)
    {
      var rx = 100 / coords.w;
      var ry = 100 / coords.h;

      Preview.css({
        width: Math.round(rx * 500) + 'px',
        height: Math.round(ry * 370) + 'px',
        marginLeft: '-' + Math.round(rx * coords.x) + 'px',
        marginTop: '-' + Math.round(ry * coords.y) + 'px'
      }).show();
    }
  }

  function hidePreview()
  {
    Preview.stop().fadeOut('fast');
  }

});
		";
	}
	
	public function getThumbnailHtml() {
		if( !is_url_exist($this->image_url) || $this->bean->data['row']->image_path == '' ) return '';
		
		$image_name = @end( explode('/', $this->image_url) );
		$thumb_rows = isset( $this->bean->data['thmb_rows'] ) ? $this->bean->data['thmb_rows'] : array();
		$dbthumb = 0;
		$thumb_images = '';
		$thumb_list = '';
		if( !empty( $thumb_rows ) ) {
			$thumb_images .= '<div class="thumb_box">'."\n";
			$thumb_images .= '<img src="'.$this->image_url.'" alt="" />'."\n";
			$thumb_images .= '</div>'."\n";
			
			$thumb_list .= '<div class="list_thumb_box">'."\n";
			foreach( $thumb_rows as $row ) {
				$image_url = $this->bean->url('assets/uploads/'.$row->filepath);
				//if( is_url_exist($image_url) ) {
					$thumb_list .= '
					<input type="hidden" name="uploadids[]" id="uploadids" value="'.$row->id.'" />
					<input type="checkbox" name="filepath[]" id="filepath" value="'.$row->filepath.'" style="vertical-align:top" />
					<img src="'.$image_url.'" alt="'.$row->alt_text.'" />'."\n";
					$dbthumb++;
				//}
			}
			$thumb_list .= '</div>'."\n";
		}
		if( $dbthumb ) $thumb_list = '<input type="button" value="Delete Thumbnail" id="delthumb" />'.$thumb_list."\n";
		$html = '
		<style style="text/css">
		#thumbnail{ float: left; margin-right: 10px; }
		.thumb_box{ 
			border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:'.$this->thumb_width.'px; height:'.$this->thumb_height.'px;
		}
		.thumb_box img{ position:relative; }
		.list_thumb_box{ background:#f0ebe2;border:solid 1px #efe6d8; width:40%; }
		</style>
		<h4><u>Create Thumbnail</u></h4>
		'.$thumb_list.'
		<div>
			<img src="'.$this->image_url.'" id="thumbnail" alt="Create Thumbnail" />'."\n";
		
		
		if( !$dbthumb ) {
			$html .= '<div class="thumb_box">'."\n";
				$html .= '<img src="'.$this->image_url.'" alt="Thumbnail Preview" />'."\n";
			$html .= '</div>'."\n";
		}
		$html .= $thumb_images . '
			<br style="clear:both;"/>
			<form name="thumbimgs" id="thumbimgs" action="'.$this->thumbnail_form_url.'" method="post">
				<input type="submit" name="upload_thumbnail" value="Save Thumbnail" id="save_thumb" />';
			
			$html .= '
				<input type="hidden" name="x1" value="" id="x1" />
				<input type="hidden" name="y1" value="" id="y1" />
				<input type="hidden" name="x2" value="" id="x2" />
				<input type="hidden" name="y2" value="" id="y2" />
				<input type="hidden" name="w" value="" id="w" />
				<input type="hidden" name="h" value="" id="h" />
				<input type="hidden" name="image_path" value="'.$image_name.'" id="h" />
				<span class="addextrathumb"></span>
			</form>
		</div>
		';
		return $html;
	}
	
	public function saveThumbImage() {
		$cropped = '';
		$thum_image_dir = str_replace( @end(explode('/', $this->thumb_image_path)) , '',  $this->thumb_image_path);
		$this->createEmptyDir($thum_image_dir);
		
		if ( file_exists($this->large_image_path) && is_dir($thum_image_dir) ) {
			//Get the new coordinates to crop the image.
			$x1 = $_POST["x1"];
			$y1 = $_POST["y1"];
			$x2 = $_POST["x2"];
			$y2 = $_POST["y2"];
			$w 	= $_POST["w"];
			$h 	= $_POST["h"];
			//die($this->thumb_image_path.", ".$this->large_image_path);
			//Scale the image to the thumb_width set above
			$scale 		= $this->thumb_width/$w;
			$cropped 	= $this->resizeThumbnailImage($this->thumb_image_path, $this->large_image_path, $w, $h, $x1, $y1, $scale);
		}
		return $cropped;
	}
	
	private function createEmptyDir($dir = '') {
		if( !is_dir($this->upload_dir) && $dir != "" ) {
			@mkdir($dir, 0777);
			@chmod($dir, 0777);
		}
	}
	
	public function resizeImage($image, $width, $height, $scale) {
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		
		$imageType 		= image_type_to_mime_type($imageType);
		$newImageWidth 	= ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage 		= imagecreatetruecolor($newImageWidth, $newImageHeight);
		switch($imageType) {
			case "image/gif":
				$source = imagecreatefromgif($image); 
			break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source = imagecreatefromjpeg($image); 
			break;
			case "image/png":
			case "image/x-png":
				$source = imagecreatefrompng($image); 
			break;
		}
		
		imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);
		
		switch($imageType) {
			case "image/gif":
				imagegif($newImage, $image); 
			break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($newImage, $image, 90); 
			break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage, $image);  
			break;
		}
		
		@chmod($image, 0777);
		return $image;
	}
	
	public function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType 		= image_type_to_mime_type($imageType);
		
		$newImageWidth 	= ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage 		= imagecreatetruecolor($newImageWidth, $newImageHeight);
		switch($imageType) {
			case "image/gif":
				$source = imagecreatefromgif($image); 
			break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source = imagecreatefromjpeg($image); 
			break;
			case "image/png":
			case "image/x-png":
				$source = imagecreatefrompng($image); 
			break;
		}
		
		imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width,$height);
		switch($imageType) {
			case "image/gif":
				imagegif($newImage, $thumb_image_name); 
			break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($newImage, $thumb_image_name, 90); 
			break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage, $thumb_image_name);  
			break;
		}
		@chmod($thumb_image_name, 0777);
		return $thumb_image_name;
	}

	public function setHeight($image) {
		$size 	= getimagesize($image);
		$height = $size[1];
		return $height;
		$this->height = $size[1];
	}

	public function setWidth($image) {
		$size 	= getimagesize($image);
		$width 	= $size[0];
		return $width;
	}
	
	public function getImageWidth() {
		$width = 0;
		if( is_url_exist($this->image_url) ) {
			$size 	= getimagesize($this->image_url);
			$width = $size[0];
		}
		return $width;
	}
	
	public function getImageHeight() {
		$height = 0;
		if( is_url_exist($this->image_url) ) {
			$size 	= getimagesize($this->image_url);
			$height = $size[1];
		}
		return $height;
	}
}
