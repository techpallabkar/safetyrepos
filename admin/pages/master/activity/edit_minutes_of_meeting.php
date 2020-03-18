<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityController");
$controller->doAction();
$beanUi = $controller->beanUi;
$post_categories = $beanUi->get_view_data("post_categories");
$post_status = $beanUi->get_view_data("post_status");
$post_uploads = $beanUi->get_view_data("post_uploads");

$auth_user_id = $controller->get_auth_user("id");
$activity_id = $beanUi->get_view_data("id");//echo $activity_id;die;
$post_category_id = $beanUi->get_view_data("post_category_id");
$status_id = $beanUi->get_view_data("status_id");
//$violation_rows = $beanUi->get_view_data("violation");
$post_participants_categories = $beanUi->get_view_data("post_participants_categories");
//$post_division_department = $beanUi->get_view_data("post_division_department");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$activity_type_id = $beanUi->get_view_data("post_activity_type_id");
$cat_id = $beanUi->get_view_data("cat_id");
$pcat_id = $beanUi->get_view_data("pcat_id");
$participants_list = $beanUi->get_view_data("participants_list");

//$post_division_department_mapping = $beanUi->get_view_data("post_division_department_mapping");
$devition_names = $beanUi->get_view_data("devition_names");
$activities = get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);//echo $activities;die;
$controller->setCss(array("cropimage/css/imgpicker"));
$controller->setCss("tree");
$controller->get_header();
?>
<style type="text/css">
    #feature_image_display
    {
        filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);
        min-width: 250px;
        max-width: 700px;
    }
    ul,li { margin:0; padding:0; list-style:none;}
    .box{display: none;}
    .box_faculty {display: none;}   
</style>
<div class="container1">
    <h1 class="heading">Update Activity : <?php echo $activities; ?></h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div>
    <?php echo $beanUi->get_error("status_id"); ?>
    <br />
    <div class="panel" style="padding:20px;">
        <form name="edit_post" id="edit_post" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="data[activity_type_id]" value="<?php echo $activity_type_id; ?>" />
            <div class="holder">
                <label for="title">Activity</label>
                <b class="text-danger"> <?php echo $activities; ?> </b>         
            </div>
            <br />
            
            
            <div class="holder required set">
                <label for="mom_title">Title</label>
                <select name="data[mom_title]" id="set_type3" class="set3">
                    <option value="">select one</option>
               <?php echo '<option value="MOM of EDG Meeting" ' . ($beanUi->get_view_data("mom_title") == "MOM of EDG Meeting" ? "selected" : "") . '>EDG Meeting</option> 
                           <option value="MOM of EDHR" ' . ($beanUi->get_view_data("mom_title") == "MOM of EDHR" ? "selected" : "") . '>EDHR</option> 
                           <option value="MOM of Review Meeting" ' . ($beanUi->get_view_data("mom_title") == "MOM of Review Meeting" ? "selected" : "") . '>Review Meeting</option>' 
                       ?>
<!--                    <option value="EDG">EDG</option>
                    <option value="HR">HR</option>
                    <option value="Renew">Renew</option>-->
                </select>
                <div id="incident_no_error"><?php echo $beanUi->get_error('mom_title'); ?></div>
            </div>
            <br>
            
            <div class="holder required">
                <label for="date_of_meeting">Date of Meeting</label>
                <input type="text" readonly="" name="data[date_of_meeting]" class="datetimepicker" id="datetimepicker1" value="<?php echo $beanUi->get_view_data("date_of_meeting"); ?>" />
                <div id="date_of_audit_error"><?php echo $beanUi->get_error('date_of_meeting'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="time_of_meeting">Time of Audit</label>
                From <input style="width:10%;"  readonly="" placeholder="From" type="text" class="datetimepicker_for_time" name="data[time_of_meeting]" id="datetimepicker2" value="<?php echo $beanUi->get_view_data("time_of_meeting"); ?>" />
                <!--To <input style="width:10%;" readonly="" placeholder="To" type="text" class="datetimepicker_for_time" name="data[time_of_audit_to]" id="datetimepicker22" value="<?php echo $beanUi->get_view_data("time_of_audit_to"); ?>" />-->
                <div id="time_of_audit_error"><?php echo $beanUi->get_error('time_of_audit'); ?></div>
            </div>
            
            <?php
            $pdf_links = "";
            $image_links = "";
            $pdf_caption = "";
            $pdf_exists = 0;
            if (!empty($post_uploads)) {
                $pdf_links .= "<div class=\"msg\"></div>\n";
                $site_root_url = dirname(url());
//                echo "<pre>";
//                print_r($post_uploads);die;
                foreach ($post_uploads as $purow) {
                    $file_type = strtolower($purow->file_type);
                    $type_id = $purow->type_id;
                    if ($file_type != "") {
                        $file_type_prefix_array = @explode("/", $file_type);
                        $file_type = $file_type_prefix_array[0];
                    }
                    if ($type_id == 1 && is_file(CESC_BASE_PATH . "/" . $purow->file_path)) {
                        $pdf_exists++;
                        $pdf_caption = $purow->name;
                        $pdf_links .= "<br><input type=\"text\" id=\"image_caption_" . $purow->id . "\" value=\"" . $purow->name . "\" style=\"vertical-align:top;\" />\n";
                        $pdf_links .= "<input type=\"button\" class='btn btn-sm btn-primary' onclick=\"save_caption(" . $purow->id . ");\" value=\"Save\" style=\"vertical-align:top;\" />\n";
                        $pdf_links .= '<a href="' . $site_root_url . "/" . $purow->file_path . '" class="btn btn-warning btn-sm" target="_blank" title="' . $purow->name . '"><i class="fa fa-search"></i> view file</a>';
                        if (!in_array($status_id, array(3, 5))) {
                            $pdf_links .= '<a class="btn btn-danger btn-sm" href="edit_minutes_of_meeting.php?action=delete_upload&t='.base64_encode("file_upload_mom_mapping").'&id=' . $purow->id . '&post_id=' . $activity_id . '" title="Delete file" onclick="return confirm(\'Confirm delete.\');">
                                <i class="fa fa-trash"></i> Delete  
                                </a>';
                        }
                        $pdf_links .= '<input type="hidden" name="pdf_id" id="pdf_id" value="' . $purow->id . '" />';
                    } elseif ($file_type == "image" && is_file(CESC_BASE_PATH . "/" . $purow->file_path)) {
                        $image_links .= "\n<div class=\"holder\" style=\"margin-bottom:5px; border-bottom:dashed 0px #000;\">\n";
                        $image_links .= "<div class=\"msg\"></div>\n";
                        $image_links .= "<label><img src=\"" . $site_root_url . "/" . $purow->file_path . "\" style=\"width:60px;\" /></label>\n";
                        $image_links .= "<input type=\"text\" id=\"image_caption_" . $purow->id . "\" value=\"" . $purow->name . "\" style=\"vertical-align:top;\" />\n";
                        $image_links .= "<input type=\"button\" class='btn btn-sm btn-primary' onclick=\"save_caption(" . $purow->id . ");\" value=\"Save\" style=\"vertical-align:top;\" />\n";
                        if ($status_id == 1) {
                            $image_links .=
                                    '<a href="edit_minutes_of_meeting.php?action=delete_upload&t='.base64_encode("file_upload_mom_mapping").'&id=' . $purow->id . '&post_id=' . $activity_id . '" 
                                title="Delete image" onclick="return confirm(\'Confirm delete.\');" class="button" style="background-color: #ef0000;border-radius: 4px;color: #fff;margin: 0;padding: 3px 10px 11px;position: relative;top: 1px;">
                                Delete</a>';
                        }
                        $image_links .= "</div>\n";
                    } elseif ($file_type == "video" && is_file(CESC_BASE_PATH . "/" . $purow->file_path)) {
                        $video_links .= "\n<div class=\"holder\" style=\"margin-bottom:5px;\">\n";
                        $video_links .= "<div class=\"msg\"></div>\n";
                        $video_links .= "<label><video width='100' controls><source src=\"" . $site_root_url . "/" . $purow->file_path . "\"  type='" . $purow->file_type . "'></video></label>\n";
                        $video_links .= "<input type=\"text\" id=\"image_caption_" . $purow->id . "\" value=\"" . $purow->name . "\" style=\"vertical-align:top;\" />\n";
                        $video_links .= "<input type=\"button\" class='btn btn-sm btn-primary' onclick=\"save_caption(" . $purow->id . ");\" value=\"Save\" style=\"vertical-align:top;\" />\n";
                        if ($status_id == 1) {
                            $video_links .=
                                    '<a href="edit_minutes_of_meeting.php?action=delete_upload&t='.base64_encode("file_upload_mom_mapping").'&id=' . $purow->id . '&post_id=' . $activity_id . '" 
                                title="Delete image" onclick="return confirm(\'Confirm delete.\');" class="button" style="background-color: #ef0000;border-radius: 4px;color: #fff;margin: 0;padding: 3px 10px 11px;position: relative;top: 1px;">
                                Delete</a>';
                        }
                        $video_links .= "</div>\n";
                    }
                }
            }
            ?>
            <div class="holder">
                <fieldset>
                    <legend>Upload Office Files</legend>
                    <?php //if (!$pdf_exists) { ?>
                        <label for="file_path">Upload File</label>
                        <input type="file" name="file_path[]" id="file_path" />
                        <input type="button" id="add_upload_file" value="Add another" class="btn btn-sm btn-primary" />
                        <br />
                        <label for="caption">File Caption</label>
                        <input type="text" name="caption[]" id="caption"  placeholder="File Caption" value="" />
                    <?php //} else { ?>
                       <!-- <label for="caption">File Caption</label> -->
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php echo $pdf_links;
                //}
                    ?>
                    <div id="file_path_error"><?php echo $beanUi->get_error('file_path'); ?></div>
                        <?php echo FILESIZE_ERROR; ?>
                </fieldset>
            </div>
            <div class="holder" id="extra_upload_files"></div>
            <br />
<!--            <div class="holder">
                <fieldset>
                    <legend>Upload Image</legend>
                    <label for="image_path">Upload Image</label>
                    <input type="file" name="image_path[]" id="image_path" />
                    <input type="button" id="add_upload_image" value="Add another" class="btn btn-sm btn-primary" />
                    <br />
                    <label for="caption">Image Caption</label>
                    <input type="text" name="image_captions[]" id="image_captions"  placeholder="Image Caption" />
                    <?php
                    if (!empty($image_links)) {
                        echo '<hr>' . $image_links;
                    }
                    ?>
                    <br>
                        <?php echo FILESIZE_ERROR; ?>
                </fieldset>
            </div>
            <div class="holder" id="extra_upload_images"></div>
            
            <br />		-->
            <div class="holder required">
                <fieldset class="required">
                    <legend>Upload Featured Image</legend>
                    <label for="image_path">Featured Image</label>
                    <?php
					 if ($beanUi->get_view_data("featured_image_path") != '' && file_exists(@$imgpath)) {
                    $img_path = $beanUi->get_view_data("featured_image_path");
                    $img = explode("?", end(explode("/", $img_path)));
                    $image_name = str_replace("-avatar", "", $img[0]);
                    $random = $img[1];
                    $path = explode("?", $img_path);
                    $imgpath = "../../../../" . $path[0];
                    
                        $srcpath = "../../../../" . $beanUi->get_view_data("featured_image_path");
                        $srcpath = site_url($beanUi->get_view_data("featured_image_path"));
                        $actions = '<a class="btn btn-sm btn-danger" href="edit_audit.php?action=delete_featured_image&t='.base64_encode("audit").'&id=' . $activity_id . '"  onclick="return confirm(\'Confirm delete.\');"><i class="fa fa-trash"></i> Delete Featured Image</a>';
                    } else {
                        $srcpath = "assets/img/audit.png";
                        $srcpath = url("assets/css/cropimage/img/audit.png");
                        $actions = '<button type="button" class="btn btn-primary btn-sm" data-ip-modal="#avatarModal" >Upload Photo</button>';
                    }
                    ?>
                    <img src="<?php echo $srcpath; ?>" id="avatar2" width="150" style="padding:2px;border:1px solid #d0d0d0;" />
<?php echo $actions; ?>
                    <input type="hidden" name="featured_image_path_old" value="<?php echo $beanUi->get_view_data("featured_image_path"); ?>" />
                    <input type="hidden" name="featured_image_path" id="avatar3" />
                    <input type="hidden" name="featured_image_original" id="avatar4" />
                    <!-- Avatar Modal -->
                    <div class="ip-modal" id="avatarModal">
                        <div class="ip-modal-dialog">
                            <div class="ip-modal-content">
                                <div class="ip-modal-header">
                                    <a class="ip-close" title="Close">&times;</a>
                                    <h4 class="ip-modal-title">Upload Featured Image</h4>
                                </div>
                                <div class="ip-modal-body">
                                    <div class="btn btn-primary ip-upload">Upload <input type="file" name="file" class="ip-file" value="<?php echo $image_name; ?>"></div>
                                    <button type="button" class="btn btn-info ip-edit">Edit</button>
                                    <button type="button" class="btn btn-danger ip-delete">Delete</button>
                                    <div class="alert ip-alert"></div>
                                    <div class="ip-info">To crop this image, drag a region below and then click "Save Image"</div>
                                    <div class="ip-preview"></div>                  
                                    <div class="ip-rotate">
                                        <button type="button" class="btn btn-default ip-rotate-ccw" title="Rotate counter-clockwise"><i class="icon-ccw"></i></button>
                                        <button type="button" class="btn btn-default ip-rotate-cw" title="Rotate clockwise"><i class="icon-cw"></i></button>
                                    </div>
                                    <div class="ip-progress">
                                        <div class="text">Uploading</div>
                                        <div class="progress progress-striped active"><div class="progress-bar"></div></div>
                                    </div>
                                </div>
                                <div class="ip-modal-footer">
                                    <div class="ip-actions">
                                        <button type="button" class="btn btn-success ip-save" >Save Image</button>
                                        <button type="button" class="btn btn-primary ip-capture" id="capture-btn">Capture</button>
                                        <button type="button" class="btn btn-default ip-cancel" id="cancel-btn">Cancel</button>
                                    </div>
                                    <button type="button" class="btn btn-default ip-close" id="close-btn">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="featured_image_error"></div>
                    <!-- end Modal -->
                    <br>
                        <?php echo FILESIZE_ERROR; ?>
                </fieldset>
            </div>
            <br />
            


        


            

                           
					  
           

            <div class="holder">
                <label for="status_id">Status</label>
                <select name="data[status_id]" id="status_id">
                    <?php
                        $created_by = $beanUi->get_view_data("created_by");
                        $role_id = $controller->get_auth_user("role_id");
                        if (!empty($post_status)) {
                        $normaluser = array("Draft", "Final Submit");
                        $adminapprove = array("Final Submit","Approve & Publish","Approve & Unpublish","Refferred for Correction");
                        $admin_status = array("Approve & Publish", "Approve & Unpublish");
                        $published_status = array("Approve & Publish", "Approve & Unpublish");
                        
                        
                        
                        
                        $status_id = $beanUi->get_view_data("status_id");
                              
                        foreach ($post_status as $statusrow) {
                            if($status_id == $statusrow->id)
                            {
                                $selected= "selected";
                            }
                            else
                            {
                                $selected= "";
                            }

                            if($role_id == 1 && $status_id == 1 && in_array( $statusrow->status_name, $normaluser ))
                            {
                              
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }    
                            else if($role_id == 1 && $status_id == 2 && in_array( $statusrow->status_name, $adminapprove ))
                            {
                             
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 1 && $status_id == 5 && in_array( $statusrow->status_name, $admin_status ))
                            {
                               
                                echo '<option value="'.$statusrow->id.'" '.$selected.'>'.$statusrow->status_name.'</option>'."\n";
                            }
                            if($role_id == 3 && $status_id == 1 && in_array( $statusrow->status_name, $normaluser ))
                            {
                              
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }    
                            else if($role_id == 3 && $status_id == 2 && in_array( $statusrow->status_name, $adminapprove ))
                            {
                             
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 3 && $status_id ==3 && in_array( $statusrow->status_name, $published_status ))
                            {
                             
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 3 && $status_id ==4 && in_array( $statusrow->status_name, $published_status ))
                            {
                             
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 3 && $status_id == 5 && in_array( $statusrow->status_name, $admin_status ))
                            {
                               
                                echo '<option value="'.$statusrow->id.'" '.$selected.'>'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 2 && in_array( $statusrow->status_name, $normaluser ))
                            {
                                
                                echo '<option value="'.$statusrow->id.'" '.$selected.'>'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 1 && ($status_id == 3 OR $status_id == 4) && in_array( $statusrow->status_name, $published_status ))
                            {
                                echo '<option value="'.$statusrow->id.'" '.$selected.'>'.$statusrow->status_name.'</option>'."\n";
                            }
                        }
                    }

?>
                </select>
<?php echo $beanUi->get_error('status_id'); ?>
            </div>
            <br />
            <div class="holder required">
                <label for="author">Created by</label><?php echo $beanUi->get_view_data('created_by_name'); ?>
            </div>
            <br />
            <div class="holder required">
                <label for="author">Modified Date</label><?php echo $beanUi->get_view_data('modified_date'); ?>
            </div>
            <br />
            <div class="holder">
                <input type="submit" value="Update" class="btn btn-smbtn btn-sm btn-primary" />

                <a href="index.php?activity_id=<?php echo $activity_type_id; ?>" class="btn btn-sm btn-danger">Cancel</a>
                <input type="hidden" name="_action" value="update" />
                <input type="hidden" name="data[id]" id="post_id" value="<?php echo $beanUi->get_view_data("id"); ?>" />
                <input type="hidden" id="f_image_error" value="" />
            </div>
        </form>

    </div>
</div>


<?php $controller->get_footer(); ?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js"); ?>"></script>
<script type="text/javascript">
                            jQuery(function ($) {
                                $('.auto').autoNumeric('init');
                            });
</script>
<!-- JavaScript -->
<script type="text/javascript" src="<?php echo url("assets/js/jquery.Jcrop.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo url("assets/js/jquery.imgpicker.js") ?>"></script>

<div id="tracking_post_detail"></div>
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>

<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
<script type="text/javascript">

                            jQuery.datetimepicker.setLocale('en');
                            jQuery('.datetimepicker').datetimepicker({
                                timepicker: false,
                                scrollMonth: false,
                                scrollInput: false,
                                format: 'Y-m-d',
                                step: 5
                            });
                            jQuery('.datetimepicker_for_time').datetimepicker({
                                datepicker: false,
                                scrollMonth: false,
                                scrollInput: false,
                                format: 'H:i',
                                step: 10
                            });


                            /*time of_audit calculation*/
                            function actualTime(reportingTime, releasingTime) {

                                if (reportingTime != '' && releasingTime != '') {
                                    var rept = reportingTime.split(":");
                                    var reptHour = rept[0];
                                    var reptMin = rept[1];
                                    var rept1 = reptHour * 60;
                                    var rept2 = parseInt(rept1) + parseInt(reptMin);

                                    var relt = releasingTime.split(":");
                                    var reltHour = relt[0];
                                    var reltMin = relt[1];
                                    var relt1 = reltHour * 60;
                                    var relt2 = parseInt(relt1) + parseInt(reltMin);

                                    var actualHours = (relt2 - rept2);

                                    if (actualHours < 0) {
                                        alert("Please check your reporting and releasing Times");
                                        return false;
                                    } else {
                                        var actualHour = parseInt(actualHours / 60);

                                        var actualMin = parseInt(actualHours % 60);

                                        actualMin = ("0" + actualMin).slice(-2);
                                        var actualTime = actualHour + ":" + actualMin;
                                        return actualTime;
                                    }
                                }

                            }

                            jQuery("#datetimepicker2").change(function () {

                            });
                            jQuery("#datetimepicker22").change(function () {
                                var reportingTime = jQuery("#datetimepicker2").val();
                                var releasingTime = jQuery("#datetimepicker22").val();
                                if(releasingTime <= reportingTime && releasingTime !="") { 
                                    alert("Realeasing time should be greater than Reporting time..");
                                    jQuery("#datetimepicker22").val('');
                                    jQuery("#audit_duration").val('');
                                    return false;
                                } else
                                {
                                    var actualTimes = actualTime(reportingTime, releasingTime);
                                    jQuery("#audit_duration").val(actualTimes);
                                }
                            });

                            /*time of_audit calculation*/

</script>   
<script type="text/javascript">
    var $m = jQuery.noConflict();
    $m(function () {

        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

        $m('a[data-modal-id]').click(function (e) {
            var row_id = $(this).attr("data-modal-id").replace("popup", "");
            var pid_value = $("#pid_" + row_id).val();



            var text2 = '';
            for (var i = 0; i < 1; i++)
            {
                text2 += '<div id="frmAdddsd">';
                if (row_id != 4 && row_id != 2)
                {
                    // alert(row_id);
                    text2 += '<input style="width:80%;" type="text" name="emp_code" id="emp_code" placeholder="Employee Code" />';
                }
                /*  text2 += '<td width="25%"> <input style="width:80%;" type="text" name="name" id="name_'+row_id+'_'+i+'" placeholder="Employee Name" /></td>';
                 text2 += '<td width="25%"><input style="width:80%;" type="text" name="designation" id="designation_'+row_id+'_'+i+'"  placeholder="Designation" /></td>';
                 text2 += '<td width="25%"><input style="width:80%;" type="text" name="department" id="department_'+row_id+'_'+i+'"  placeholder="Department" /></td>'; 
                 */
                text2 += '<button id="btnAddAction" name="submit" onClick="callCrudAction("p_add","")">Add</button>';

                text2 += '</div>';
            }

            $("#pdetails_" + row_id).html(text2);

            e.preventDefault();
            $m("body").append(appendthis);
            $m(".modal-overlay").fadeTo(500, 0.7);
            //$(".js-modalbox").fadeIn(500);
            var modalBox = $(this).attr('data-modal-id');
            $m('#' + modalBox).fadeIn($(this).data());

        });


        $m(".js-modal-close, .modal-overlay").click(function () {
            $m(".modal-box, .modal-overlay").fadeOut(500, function () {
                $m(".modal-overlay").remove();
            });
            return false;
        });

        $m(window).resize(function () {
            $m(".modal-box").css({
                top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
                left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                        // left: "10%"
            });
        });

        $m(window).resize();

    });

    $(document).ready(function () {


        $("#avg_mark").on("keypress keyup blur", function (event) {

            $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which != 8) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
                if ((pointPos = $(this).val().indexOf('.')) >= 0)
                {
                    $(this).attr("maxLength", pointPos + 3);
                }

            }
        });

    });

// Featured image
    var $s = jQuery.noConflict();
    $s(function () {
        var time = function () {
            return'?' + new Date().getTime()
        };

        // Avatar setup
        $s('#avatarModal').imgPicker({
            url: 'server/upload_avatar.php',
            aspectRatio: 1,
            deleteComplete: function () {
                $s('#avatar2').attr('src', 'assets/img/audit.png');
                $s('#avatar3').attr('value', '');
                $s('#avatar4').attr('value', '');
                this.modal('hide');
            },
            uploadSuccess: function (image) {

                // Calculate the default selection for the cropper
                /*var select = (image.width > image.height) ? 
                 [(image.width-image.height)/2, 0, image.height, image.height] : 
                 [0, (image.height-image.width)/2, image.width, image.width];*/
                var select = [0, (image.height - image.width) / 2, 250, 250];
                this.options.setSelect = select;

            },
            cropSuccess: function (image) {

                $s('#avatar2').attr('src', image.versions.avatar.url + time());
                $s('#avatar3').attr('value', image.versions.avatar.url + time());
                $s('#avatar4').attr('value', image.versions.avatar.url + time());
                this.modal('hide');
            }
        });

        // Demo only
        $s('.navbar-toggle').on('click', function () {
            $('.navbar-nav').toggleClass('navbar-collapse')
        });
        $s(window).resize(function (e) {
            if ($(document).width() >= 430)
                $('.navbar-nav').removeClass('navbar-collapse')
        });

    });

    jQuery(document).ready(function ($) {

        ////anima

    });
    var _URL = window.URL || window.webkitURL;
    jQuery(function () {
        jQuery("#f_image_error").val("");
        jQuery("#featured_image").change(function (e) {
            jQuery("#feature_image_display").html("");
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
            if (regex.test(jQuery(this).val().toLowerCase())) {
                if (jQuery.browser.msie && parseFloat(jQuery.browser.version) <= 9.0) {
                    jQuery("#feature_image_display").show();
                    jQuery("#feature_image_display")[0].filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = jQuery(this).val();
                } else {
                    if (typeof (FileReader) != "undefined") {
                        jQuery("#feature_image_display").show();
                        jQuery("#feature_image_display").append("<img />");
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            jQuery("#feature_image_display img").attr("src", e.target.result);
                        }
                        reader.readAsDataURL(jQuery(this)[0].files[0]);
                    } else {
                        alert("This browser does not support FileReader.");
                    }
                }
            } else {
                alert("Please upload a valid image file.");
            }

            check_f_image(this);
        });
    });

    function check_f_image(fobj) {
        var file, img;
        jQuery("#f_image_error").val("");
        if ((file = fobj.files[0])) {
            img = new Image();
            img.onload = function () {
                //alert(this.width + " " + this.height);
                var imgwidth = parseInt(this.width);
                var imgheight = parseInt(this.height);
                if (imgwidth < 250) {
                    alert("Image's width is less than 250 pixel. Featured image's width and height should be same and width should be greater than 250 pixel.");
                    jQuery("#f_image_error").val("Image's width is less than 250 pixel. Featured image's width and height should be same and width should be greater than 250 pixel.");
                } else if (imgwidth > 250 && imgwidth != imgheight) {
                    alert("Image's width and height is not same. Featured image's width and height should be same and width should be greater than 250 pixel.");
                    jQuery("#f_image_error").val("Image's width and height is not same. Featured image's width and height should be same and width should be greater than 250 pixel.");
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    }
// End


    jQuery(document).ready(function ($) {
        $("#post_subcat_html,#post_end_cat_html").hide();
        $("#post_subcat_html").css({"display": "inline-block", "width": "140px", "margin-left": "10px"});

        // Tag section
        jQuery("#add_tag").click(function () {
            add_tag();
            return false;
        });

        jQuery("#tags").keyup(function (e) {
            var code = e.keyCode || e.which;
            if (code == 13) { //Enter keycode
                add_tag();
                return false;
            }
            jQuery.ajax({
                type: "POST", url: "<?php echo current_url(); ?>",
                data: {
                    "action": "tag_suggestion",
                    "keyword": jQuery(this).val(),
                    "id": jQuery("#post_id").val()
                },
                beforeSend: function () {
                    jQuery("#tags").css("background", "#FFF url(<?php echo url('assets/images/LoaderIcon.gif'); ?>) no-repeat 165px");
                },
                success: function (data) {
                    jQuery("#suggesstion-box").show();
                    jQuery("#suggesstion-box").html(data);
                    jQuery("#tags").css("background", "#FFF");
                }
            });
        });
        // End tag




        $("#add_upload_file").click(function () {
            var boxnumber = 1 + Math.floor(Math.random() * 6);
            var another_image_upload_html =
                    '<div class="holder" id="upload_image_box_' + boxnumber + '">' + "\n" +
                    '<fieldset>' + "\n" +
                    '<legend>Upload Office Files</legend>' + "\n" +
                    '<label for="file_path">Upload File</label>' + "\n" +
                    '<input type="file" name="file_path[]" id="file_path" />' + "\n" +
                    '<input type="button" value="Remove" class="btn btn-danger btn-sm" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
                    '<br />' +
                    '<label for="caption">File Caption</label>' + "\n" +
                    '<input type="text" name="caption[]" id="caption"  placeholder="File Caption" />' + "\n" +
                    '</fieldset>' + "\n" +
                    '</div>' + "\n";
            $("#extra_upload_files").append(another_image_upload_html);
        });

        $("#add_upload_image").click(function () {
            var boxnumber = 1 + Math.floor(Math.random() * 6);
            var another_image_upload_html =
                    '<div class="holder" id="upload_image_box_' + boxnumber + '">' + "\n" +
                    '<fieldset>' + "\n" +
                    '<legend>Upload Image</legend>' + "\n" +
                    '<label for="image_path">Upload Image</label>' + "\n" +
                    '<input type="file" name="image_path[]" id="image_path" />' + "\n" +
                    '<input type="button" class="btn btn-sm btn-danger" value="Remove" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
                    '<br />' +
                    '<label for="caption">Image Caption</label>' + "\n" +
                    '<input type="text" name="image_captions[]" id="image_captions"  placeholder="Image Caption" />' + "\n" +
                    '</fieldset>' + "\n" +
                    '</div>' + "\n";
            $("#extra_upload_images").append(another_image_upload_html);
        });
        $("#add_upload_video").click(function () {
            var boxnumber = 1 + Math.floor(Math.random() * 6);
            var another_image_upload_html =
                    '<div class="holder" id="upload_image_box_' + boxnumber + '">' + "\n" +
                    '<fieldset>' + "\n" +
                    '<legend>Upload Video`s</legend>' + "\n" +
                    '<label for="video_path">Upload Video</label>' + "\n" +
                    '<input type="file" name="video_path[]" id="video_path" />' + "\n" +
                    '<input type="button" class="btn btn-sm btn-danger" value="Remove" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
                    '<br />' +
                    '<label for="caption">Video Caption</label>' + "\n" +
                    '<input type="text" name="video_captions[]" id="video_captions"  placeholder="Video Caption" />' + "\n" +
                    '</fieldset>' + "\n" +
                    '</div>' + "\n";
            $("#extra_upload_video").append(another_image_upload_html);
        });
//        $("#edit_post").submit(function () {
//            var audit_no = jQuery.trim(jQuery("#audit_no").val());
//            var featured_image = jQuery.trim( jQuery("#featured_image").val() );
//            var fimagesrc 	= jQuery("#avatar2").prop("src");
//            fimagesrc_arr 	= fimagesrc.split("/");
//            featured_image 	= fimagesrc_arr[fimagesrc_arr.length - 1];
//            var error_counter = 0;
//            jQuery(".errors").empty();
//            if (audit_no == undefined || audit_no == "") {
//                jQuery("#audit_no_error").html("<div class=\"errors\">Audit no is required.</div>");
//                error_counter++;
//            }
//            if( featured_image != undefined && featured_image == "audit.png" ) {
//                jQuery("#featured_image_error").html("<div class=\"errors\">Featured Image is required.</div>");
//                error_counter++;
//            }  
//
//            if (error_counter > 0) {
//                jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
//                jQuery('html, body').animate({scrollTop: 0}, 'slow');
//                return false;
//            }
//        });
    });

    function save_caption(upload_id) {
        if (upload_id > 0) {
            var caption = jQuery("#image_caption_" + upload_id).val();
            jQuery("#image_caption_" + upload_id).parent().find(".message").html("");
            jQuery.ajax({
                data: {
                    "action": "save_caption",
                    "upload_id": upload_id,
                    "table_name": "file_upload_mom_mapping",
                    "caption": caption
                }, url: "<?php echo page_link(); ?>", type: "post", cache: false, success: function (caption_saved) {
                    if (caption_saved == 1) {
                        jQuery("#image_caption_" + upload_id).parent().find(".msg").html("<div class=\"success\">Successfully saved.</div>");
                    }
                }
            });

        }
    }

    function remove_upload_image_box(boxnumber) {
        jQuery("#upload_image_box_" + boxnumber).remove();
    }




    jQuery(document).ready(function ($) {
        var cat_id = "<?php echo $cat_id; ?>";
        var cat_paths = Array();
<?php foreach ($categories as $key => $catrow) { ?>
            cat_paths[<?php echo $catrow->id; ?>] = "<?php echo $catrow->path; ?>";
<?php } ?>
        if (cat_id > 0)
            select_cat_path(cat_id);

        //jQuery("#maintree").find("input[type=checkbox]").attr("checked", "checked");
        jQuery("#maintree").find("input[type=checkbox]").each(function () {
            var endclass = jQuery(this).attr("class");

            if (endclass == 'newtree')
            {

                //  jQuery(this).attr("checked", false);
            }

        });
        jQuery("#maintree li label").click(function () {
            var tree_ol = $(this).parent().find("ol").html();
            if (tree_ol == null) {
                var cat_input_name = $(this).attr("for");
                var cat_id = jQuery("input[name=" + cat_input_name + "]").attr("id");
                //location.href="./?cat_id="+cat_id;
            }
        });

        function select_cat_path(cat_id) {
            if (cat_id > 0) {
                jQuery("#selected_cat").empty();
                jQuery("#selected_cat").html("<b>Selected Category : </b>" + cat_paths[cat_id]);
            }
        }
    });

    function showEditBox(editobj, id) {
        $('#frmAdd').hide();
        $(editobj).prop('disabled', 'true');
        var currentMessage = $("#message_" + id + " .message-content").html();
        var editMarkUp = '<textarea rows="5" cols="80" id="txtmessage_' + id + '">' + currentMessage + '</textarea><button name="ok" onClick="callCrudAction(\'edit\',' + id + ')">Save</button><button name="cancel" onClick="cancelEdit(\'' + currentMessage + '\',' + id + ')">Cancel</button>';
        $("#message_" + id + " .message-content").html(editMarkUp);
    }
    function cancelEdit(message, id) {
        $("#message_" + id + " .message-content").html(message);
        $('#frmAdd').show();
    }
   
    
    
</script> 
</body>
</html>
