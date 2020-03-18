<?php

class GalleryController extends MainController {

    public function __construct() {
        $this->dao = load_dao("GalleryMasterDAO");
        parent::__construct();
    }
 
    protected function index() {
        $search_title = $this->bean->get_request("search_title");
        $status_id = $this->bean->get_request("status_id");
        $cat_id = $this->bean->get_request("cat_id");
        $galleryData = $this->dao->getGalleryData();
        $deltid = $this->bean->get_request("deltid");
        
        if($deltid!="")
        {
        $getdeltid = $this->dao->deltData($deltid);
        $this->beanUi->set_success_message("Successfully Deleted.");
                redirect("index.php");
        }
        $this->beanUi->set_view_data("galleryData", $galleryData);
    }
    
    public function add()
    {
        $gallerycategory = $this->dao->getGalleryCategory();
        
        $_action = $this->bean->get_request("_action");
        if ($_action == "addGalleryData") {
            $data = $this->bean->get_request("data");
            $data["created_by"] = $this->get_auth_user("id");
            $data["created_date"] = date("c");
            
            
            
            /** file upload **/
        $change="";
        $errors=0;
        $fname = $_FILES['image_path'];
 	$image =$fname["name"];
	$uploadedfile = $fname['tmp_name'];
 	if ($image) 
 	{
            $filename = stripslashes($fname['name']);
            /**check extension **/
            $i = strrpos($filename,".");
            if (!$i) { return ""; }
            $l = strlen($filename) - $i;
            $ext = substr($filename,$i+1,$l);
            /**check extension **/
            $extension = $ext;
            $extension = strtolower($extension);
            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
            {
                $change='<div class="msgdiv">Unknown Image extension </div> ';
                $errors=1;
            }
            else
            {
                $filenamerandom = rand(1, 10000) . "_" . time();
                $size=filesize($fname['tmp_name']);
                if ($size > MAX_SIZE*1024)
                {
                        $change='<div class="msgdiv">You have exceeded the size limit!</div> ';
                        $errors=1;
                }
                if($extension=="jpg" || $extension=="jpeg" )
                {
                $uploadedfile = $fname['tmp_name'];
                $src = imagecreatefromjpeg($uploadedfile);

                }
                else if($extension=="png")
                {
                $uploadedfile = $fname['tmp_name'];
                $src = imagecreatefrompng($uploadedfile);

                }
                else 
                {
                $uploadedfile = $fname['tmp_name'];    
                $src = imagecreatefromgif($uploadedfile);
                }
                echo $scr;
                list($width,$height)=getimagesize($uploadedfile);


                $newwidth=1000;
                $newheight=($height/$width)*$newwidth;
                $tmp=imagecreatetruecolor($newwidth,$newheight);
                $newwidth1=340;
                $newheight1=($height/$width)*$newwidth1;
                $tmp1=imagecreatetruecolor($newwidth1,$newheight1);

                imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
                imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
                $filename = UPLOADS_PATH."/gallery/". $filenamerandom.$fname['name'];
                $filename1 = UPLOADS_PATH."/gallery/thumbnail/". $filenamerandom.$fname['name'];
                imagejpeg($tmp,$filename,100);
                imagejpeg($tmp1,$filename1,100);
                imagedestroy($src);
                imagedestroy($tmp);
                imagedestroy($tmp1);
                if( $filename )
                {
                    $data["image_path"] = "assets/uploads/gallery/".$filenamerandom.$fname['name'];
                }
                if( $filename1 )
                {
                    $data["image_path_thumb"] = "assets/uploads/gallery/thumbnail/".$filenamerandom.$fname['name'];
                }
            }
	}
            /****/         
            
            $this->dao->_table = "gallery";
            $updateID = $this->dao->save($data);
            if ($updateID) {
                $this->beanUi->set_success_message("Successfully Updated.");
                redirect("index.php");
            }
        }
        
  
        $this->beanUi->set_view_data("gallerycategory", $gallerycategory);
    }
    
    public function editGallery() {
        $gallerycategory = $this->dao->getGalleryCategory();
        $editid = $this->bean->get_request("editid");
        $getdata = $this->dao->getGalleryEditData($editid);
        $_action = $this->bean->get_request("_action");
        if ($_action == "updateGalleryData") {
            $data = $this->bean->get_request("data");
            
            
            /** file upload **/
        $change="";
        $errors=0;
        $fname = $_FILES['image_path'];
 	$image =$fname["name"];
	$uploadedfile = $fname['tmp_name'];
 	if ($image) 
 	{
            
             $old_image = $this->bean->get_request("old_image");
             $old_image_thumb = $this->bean->get_request("old_image_thumb");
             @unlink(UPLOADS_PATH."/../../".$old_image);
             @unlink(UPLOADS_PATH."/../../".$old_image_thumb);
            $filename = stripslashes($fname['name']);
            /**check extension **/
            $i = strrpos($filename,".");
            if (!$i) { return ""; }
            $l = strlen($filename) - $i;
            $ext = substr($filename,$i+1,$l);
            /**check extension **/
            $extension = $ext;
            $extension = strtolower($extension);
            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
            {
                $change='<div class="msgdiv">Unknown Image extension </div> ';
                $errors=1;
            }
            else
            {
                $filenamerandom = rand(1, 10000) . "_" . time();
                $size=filesize($fname['tmp_name']);
                if ($size > MAX_SIZE*1024)
                {
                        $change='<div class="msgdiv">You have exceeded the size limit!</div> ';
                        $errors=1;
                }
                if($extension=="jpg" || $extension=="jpeg" )
                {
                $uploadedfile = $fname['tmp_name'];
                $src = imagecreatefromjpeg($uploadedfile);

                }
                else if($extension=="png")
                {
                $uploadedfile = $fname['tmp_name'];
                $src = imagecreatefrompng($uploadedfile);

                }
                else 
                {
                $uploadedfile = $fname['tmp_name'];    
                $src = imagecreatefromgif($uploadedfile);
                }
                echo $scr;
                list($width,$height)=getimagesize($uploadedfile);


                $newwidth=1000;
                $newheight=($height/$width)*$newwidth;
                $tmp=imagecreatetruecolor($newwidth,$newheight);
                $newwidth1=340;
                $newheight1=($height/$width)*$newwidth1;
                $tmp1=imagecreatetruecolor($newwidth1,$newheight1);

                imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
                imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
                $filename = UPLOADS_PATH."/gallery/". $filenamerandom.$fname['name'];
                $filename1 = UPLOADS_PATH."/gallery/thumbnail/". $filenamerandom.$fname['name'];
                imagejpeg($tmp,$filename,100);
                imagejpeg($tmp1,$filename1,100);
                imagedestroy($src);
                imagedestroy($tmp);
                imagedestroy($tmp1);
                if( $filename )
                {
                    $data["image_path"] = "assets/uploads/gallery/".$filenamerandom.$fname['name'];
                }
                if( $filename1 )
                {
                    $data["image_path_thumb"] = "assets/uploads/gallery/thumbnail/".$filenamerandom.$fname['name'];
                }
            }
	}
            /****/
            
            
            $this->dao->_table = "gallery";
            $updateID = $this->dao->save($data);
            if ($updateID) {
                $this->beanUi->set_success_message("Successfully Updated.");
                redirect("index.php");
            }
        }
        $this->beanUi->set_view_data("getdata", $getdata);
        $this->beanUi->set_view_data("editid", $editid);
//        $this->beanUi->set_view_data("deltid", $deltid);
        $this->beanUi->set_view_data("gallerycategory", $gallerycategory);
        
    }

    public function manage_category() {
        $action = $this->bean->get_request("_action");
        $rowid  = $this->bean->get_request("id");
        $delid  = $this->bean->get_request("delid");
        $singleRowFetch = array();
        if ($rowid != "") {
            $clause = " id = :id";
            $passValue["id"] = $rowid;
            
            $singleRowFetch = $this->dao->getGalleryCategory($clause,$passValue);
        }
        if ($delid != "") {
            $this->dao->_table = "gallery_category";
            $rowDelete = $this->dao->del(array("id" => $delid));
            if ($rowDelete) {
                $this->beanUi->set_success_message("Category Deleted.");
                redirect("manage_category.php");
            }
        }
        if ($action == "submitData") {
            $data = $this->bean->get_request("data");
            $rid = $this->bean->get_request("id");
            $this->dao->_table = "gallery_category";
            if ($rid != "") {
                $data["id"] = $rid;
                $affectedRowID = $this->dao->save($data);
                if ($affectedRowID) {
                    $this->beanUi->set_success_message("Category Updated.");
                    redirect("manage_category.php");
                }
            } else {
                $insertRowID = $this->dao->save($data);
                if ($insertRowID) {
                    $this->beanUi->set_success_message("Category Inserted.");
                    redirect("manage_category.php");
                }
            }
        }
        $gallerycategory = $this->dao->getGalleryCategory();
        $this->beanUi->set_view_data("gallerycategory", $gallerycategory);
        if (!empty($singleRowFetch)) {
            $this->beanUi->set_view_data("name", $singleRowFetch[0]->name);
            $this->beanUi->set_view_data("id", $singleRowFetch[0]->id);
        }
    }
    
    

}
