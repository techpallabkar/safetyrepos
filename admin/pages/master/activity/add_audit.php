<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityController");
$controller->doAction();

$beanUi = $controller->beanUi;
$post_categories = $beanUi->get_view_data("post_categories");
$post_participants_categories = $beanUi->get_view_data("post_participants_categories");
$post_division_department = $beanUi->get_view_data("post_division_department");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$post_status = $beanUi->get_view_data("post_status");
$user_details = $beanUi->get_view_data("user_details");
$auth_user_id = $controller->get_auth_user("id");
$role_id = $controller->get_auth_user("role_id");
$created_by = $controller->get_auth_user("created_by");
$activity_id = $beanUi->get_view_data("activity_id");
$activities = get_value_by_id($activity_id, "activity_name", $post_activity_type_master);
$l1_cat_name = "";
$tag_keys = $beanUi->get_view_data("tag_keys");
$token_id = rand(000000, 111111) . time();
$controller->setCss(array("cropimage/css/imgpicker"));
$controller->setCss("tree");
$controller->get_header();
?>
<style type="text/css">
    .box{display:none;}
    
</style>
<div class="container1">
    <h1 class="heading">Add Activity : <span class="text-primary"><?php echo $activities; ?></span></h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div>
    <?php echo $beanUi->get_error("status_id"); ?>
    <div class="panel" style="padding:20px;">
<!--            <div class="holder">
                <input type="submit" value="Update SAS Details" class="btn btn-sm btn-primary disablebutton" />
                <input type="hidden" name="_actionSAS" value="CreateSAS" class="btn btn-sm btn-success" />
            </div>-->
        <form name="create_post" id="create_post" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="token_id" id="token_id" value="<?php echo $token_id; ?>" />
            <input type="hidden" name="data[activity_type_id]" value="<?php echo $activity_id; ?>" />
            <div class="holder">
                <label for="title">Activity</label>
                <b class="text-danger"> <?php echo $activities; ?> </b>
            </div>
            <br />
            <div class="holder required">
                <label for="audit_duration">SAS Report No.</label>
                <input type="text" onclick="sas1Details()"  name="data[sas_report_no]" id="sas_report_no" value="<?php echo $beanUi->get_view_data("sas_report_no"); ?>" />
                <div id="sas_report_no_error"><?php echo $beanUi->get_error('sas_report_no'); ?></div>
                <button type="button" class="btn btn-info btn-sm" onclick="sasDetails()"> Save</button>
            </div>
            <br />
            <div class="holder required">
                <label for="synopsis" style="float:left;">Division Department</label>
                <!-- Trigger the modal with a button -->
                <table class="table table-bordered table-condensed" id="div_dept" style="float:left;width:70%;">
                    <tr id="division-department">
                        <td colspan="2">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"> <i class="fa fa-plus"></i> Add Division Department</button>
                        </td>                    
                    </tr>
                </table>
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Division Department</h4>
                            </div>
                            <div class="modal-body" style="height:550px;">
                                <button type="button" id="reset_button" class="btn btn-danger btn-sm" style="float:right;">Reset</button>

                                <div id="level_error"></div>


                                <div class="levelfirst">
                                    <span style="float:left;width:150px;padding-top: 6px;"><b>CESC</b></span>
                                    <select class="division" name="L1" id="L1">
                                        <option  value="" selected="">-Choose one-</option>
                                        <?php
                                        foreach ($post_division_department as $rowvalue) {
                                            if ($rowvalue->parent_id == 1 && $rowvalue->id == 249) {
                                                echo '<option value="' . $rowvalue->id . '">' . $rowvalue->name . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="level2"></div>

                                <div class="levelfour" style="margin:20px 0px 20px 0px;"></div>
                                <div id="level3"></div>
                                <div id="level4"></div>
                                <div id="level5"></div>
                                <div id="level6"></div>
                                <div id="level7"></div>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                    $("#reset_button").click(function(){
                                    $('#L1').val('');
                                    $(".division").val('');
                                    $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                    });
                                    $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                    var lcount = 1;
                                    $("#L" + lcount).on('change', function(){
                                    $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                    var level1 = $(this).val();
                                    var lc = lcount + 1;
                                    $.ajax({
                                    type: 'post',
                                            data: {
                                            "action"    : "get_nextlevel_m",
                                                    "id"        : level1,
                                                    "lcount"    : lc
                                            },
                                            cache:false,
                                            success:function (get_nextlevel_m)  {
                                            if (get_nextlevel_m)
                                            {
                                            division_department_treeview(lc, level1, get_nextlevel_m, tbname = null);
                                            }
                                            }
                                    });
                                    });
                                    /*tree function Start*/
                                    function division_department_treeview(lcount, ids, get_nextlevel_m, tb = null) {
                                    $("#level" + lcount).html(get_nextlevel_m);
                                    $("#level" + lcount).css("margin-top", "20px");
                                    $("#level" + lcount).show();
                                    $("#L4").find("option[value='298'],[value='299'],[value='300']").remove(); /** hide contractor's for activities**/
                                    $("#L" + lcount).on('change', function(){
                                    var lc = lcount + 1;
                                    var level_id = $(this).val();
                                    var groupval;
                                    if (level_id == '251' || level_id == '252')
                                    {
                                    if (level_id == '251')
                                    {
                                    groupval = '2';
                                    }
                                    if (level_id == '252')
                                    {
                                    groupval = '3';
                                    }
                                    $.ajax({
                                    type: 'post',
                                            data: {
                                            "action": "get_contractor_list",
                                                    "id": groupval
                                            },
                                            cache: false,
                                            success: function (get_contractor_list) {

                                            if (get_contractor_list)
                                            {
                                            //alert(get_contractor_list);
                                            $(".levelfour").show();
                                            $(".levelfour").html(get_contractor_list);
                                            }
                                            }
                                    });
                                    } else {
                                    $.ajax({
                                    type: 'post',
                                            data: {
                                            "action"    : "get_nextlevel_m",
                                                    "id"        : level_id,
                                                    "lcount"    : lc
                                            },
                                            cache:false,
                                            success:function (get_nextlevel_m)  {
                                            division_department_treeview(lc, level_id, get_nextlevel_m, tb = null);
                                            $(".newcons").on('change', function(){
                                            var sdd = $(this).find(':selected').data("other");
                                            var cc = $(this).find(':selected').data("c");
                                            if (sdd != 0)
                                            {
                                            $('#' + sdd).html('<span style="float:left;width:150px;padding-top: 6px;"><b>Other Name</b></span>'
                                                    + '<input class="division" name=""  id="new_' + sdd + '" type="text" value="" style="width:23%;" />');
                                            }
                                            else
                                            {
                                            $('#' + cc).html('');
                                            }
                                            $('#show_location').html($(this).find(':selected').data("location"));
                                            });
                                            }
                                    });
                                    }
                                    });
                                    }
                                    /*tree function End*/
                                    $(".set").hide();
                                    /*tree Save Start*/
                                    $('#btnSave').click(function() {
                                    $('#division-department').hide();
                                    var closestids = $('#closestid').val();
                                    var level = $("#L1").find("option:selected").val();
                                    var level_2 = $("#L2").find("option:selected").val();
                                    /*L2 validation start*/
                                    if (level_2 == ""){
                                    alert(" Division is required.  ");
                                    $('#division-department').show();
                                    return false;
                                    }
                                    /*L2 validation end*/

                                    var error_count = 0;
                                    jQuery(".errors").empty();
                                    $('#' + closestids).hide();
                                    var contractorType = 0;
                                    $('.contractor_and_type :selected').each(function(j, selected) {
                                    if ($(this).val() != ""){
                                    contractorType++;
                                    }
                                    });
                                    /*$('.newcons :selected').each(function(j, selected) { 
                                     var errormsg = $(this).parent().parent().find("label").html();
                                     if(!isNaN(this.value) ) {
                                     if(( typeof contractorType !== 'undefined' &&  contractorType == 1 )){
                                     $("#level_error").html("<div class=\"errors\">"+errormsg+" is required.</div>");
                                     error_count++; 
                                     return false;
                                     }
                                     }
                                     });*/
                                    if (error_count > 0) {
                                    jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                                    jQuery('html, body').animate({ scrollTop: 0}, 'slow');
                                    return false;
                                    }
                                    var foo_value = [];
                                    var foo_text = [];
                                    $('.division :selected').each(function(i, selected){

                                    if (foo_value[i] != '') {
                                    if ($(selected).val() != "")
                                    {
                                    foo_text.push($(selected).text());
                                    foo_value.push($(selected).val());
                                    }
                                    if ($(this).text() == 'Others')
                                    {
                                    var ppp = $(this).data("other");
                                    var sss = $("#new_" + ppp).val();
                                    foo_text.push(sss);
                                    foo_value.push(sss);
                                    }
                                    }
                                    });
                                    var othertextboxvalue = $('.othertextbox').val();
                                    foo_text.push(othertextboxvalue);
                                    foo_value.push(othertextboxvalue);
                                    function replaceAll(str, find, replace) {
                                    return str.replace(new RegExp(find, 'g'), replace);
                                    }
                                    $.ajax({
                                    type: 'post',
                                            data: {
                                            "action"    : "submit_division",
                                                    "name"      : foo_text,
                                                    "tree_dept" : "department",
                                                    "ids"       : foo_value
                                            },
                                            cache:false,
                                            success:function (submit_division)  {
                                            if (submit_division)
                                            {
                                            var getIdMod = submit_division.substr(9, 50);
                                            var getId = getIdMod.substr(0, getIdMod.indexOf('">'));
                                            var gtid = replaceAll(getId, ":", "_");
                                            $('#div_dept').append(submit_division + '<td><a id="sp' + gtid + '"  class="delete_row" style="cursor:pointer;" >Delete</a></td></tr>');
                                            $('#sp' + gtid).on('click', function() {
                                            var conf = confirm("Are you sure to delete this record");
                                            if (conf){
                                            if ($(".set3").val())
                                            {
                                            $(".set3 > [value=" + $(".set3").val() + "]").removeAttr("selected");
                                            }
                                            $(".set").hide();
                                            $(this).parents("tr").remove();
                                            $('#division-department').show();
                                            /***reset tree data***/
                                            $('#L1').val('');
                                            $('.division').val('');
                                            $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                            } else{
                                            return false;
                                            }
                                            });
                                            /**---check duplicate value---**/
                                            /*get set type start*/
                                            var settype = $(".set-type").val();
                                            var temp;
                                            $(".set").show();
                                            if (settype == 1)
                                            {
                                            temp = 'P-SET';
                                            $(".set3").html('<option value="P-SET">P-SET</option>');
                                            } else if (settype == 2)
                                            {
                                            temp = 'C-SET';
                                            $(".set3").html('<option value="C-SET">C-SET</option>');
                                            } else if (settype == 3)
                                            {
                                            temp = '';
                                            $(".set3").html('<option value="">select one</option>\n\
                                                                <option value="P-SET">P-SET</option>\n\
                                                                <option value="C-SET">C-SET</option>');
                                            }
                                            $(".set3 > [value=" + temp + "]").attr("selected", "true");
                                            /*get set type end*/

                                            var duplicateChk = {};
                                            $('#div_dept tr[id]').each (function () {
                                            if (duplicateChk.hasOwnProperty(this.id)) {
                                            $(this).remove();
                                            } else {
                                            duplicateChk[this.id] = 'true';
                                            }
                                            });
                                            /**---reset selected data---**/
                                            $('.levelfour,#level2,#level3,#level4,#level5,#lebel6,#level7').empty();
                                            $('#L1 option').prop('selected', function() {
                                            return this.defaultSelected;
                                            });
                                            }
                                            }
                                    });
                                    });
                                    /*tree Save End*/

                                    });
                                </script>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="btnSave" class="btn btn-primary" data-dismiss="modal">Save</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="division_error" class="clearfix"><?php echo $beanUi->get_error('division'); ?></div>
            </div>
            <div class="holder" id="extra_division"></div>
            <div class="holder required set">
                <label for="incident_no">Set Type</label>
                <select name="data[set_type]" id="set_type3" class="set3">
                    <option value="">select one</option>
                    <option value="P-SET">P-SET</option>
                    <option value="C-SET">C-SET</option>
                    <option value="PC-SET">(P+C)-SET</option>
                    <option value="OTHERS">OTHERS</option>
                </select>
                <div id="set_type_error"><?php echo $beanUi->get_error('set_type'); ?></div>
            </div>
            <br>
            <!--            <div class="holder">
                            <label for="audit_no">Audit Number</label>
                            <input type="text"  name="data[audit_no]" id="audit_no" value="<?php echo $beanUi->get_view_data("audit_no"); ?>" />
                            <div id="audit_no_error"><?php echo $beanUi->get_error('audit_no'); ?></div>
                        </div>-->
            <br />
            <div class="holder required">
                <label for="date_of_audit">Date of Audit</label>
                <!--<input type="text" name="data[date_of_audit]" readonly=""  class="datetimepicker" id="datetimepicker1" value="<?php //echo $beanUi->get_view_data("date_of_audit"); ?>" />-->
                <input type="text" name="data[date_of_audit]" readonly="true"  class="" id="datetimepicker1" value="<?php echo $beanUi->get_view_data("date_of_audit"); ?>" />
                <div id="date_of_audit_error"><?php echo $beanUi->get_error('date_of_audit'); ?></div>
            </div>
            <div id="showrelatedData"></div>

            <br />

            <!--            <div class="holder required">
                            <label for="time_of_audit" id="datetimepicker2">Time of Audit</label>
                            <?php
//                
//                echo 'From <select class="combodate" style="width:8%;">
//		<option value="">HH</option>';
//		for($i=0;$i<24;$i++) {
//			$val = sprintf('%02s', $i);
//			echo '<option value="'.$val.'">'.$val.'</option>';
//		}
//		echo '</select>';
//		echo '<select class="combodate" style="width:8%;">
//		<option value="">mm</option>';
//		for($j=0;$j<60;$j++) {
//			$val = sprintf('%02s', $j);
//			echo '<option value="'.$val.'">'.$val.'</option>';
//		}
//		echo '</select>';
//                echo '<input style="width:10%; border: medium none;font-weight: bold;font-size: 16px;" readonly=""  type="text" class=""  name="data[time_of_audit_from]" id="datetimepicker2" value="'.$beanUi->get_view_data( "time_of_audit_from" ).'" />';
//                echo 'To <select class="combodateto" style="width:8%;">
//		<option value="">HH</option>';
//		for($i=0;$i<24;$i++) {
//			$val = sprintf('%02s', $i);
//			echo '<option value="'.$val.'">'.$val.'</option>';
//		}
//		echo '</select>';
//		echo '<select class="combodateto" style="width:8%;">
//		<option value="">mm</option>';
//		for($j=0;$j<60;$j++) {
//			$val = sprintf('%02s', $j);
//			echo '<option value="'.$val.'">'.$val.'</option>';
//		}
//		echo '</select>'
//                . '<input style="width:10%; border: medium none;font-weight: bold;font-size: 16px;" readonly=""  type="text" class="" name="data[time_of_audit_to]" id="datetimepicker22" value="'.$beanUi->get_view_data( "time_of_audit_to" ).'" />';
//                
                                        ?>
                            
                            <div id="time_of_audit_error"><?php //echo $beanUi->get_error('time_of_audit'); ?></div>
                            
                        </div>-->

            <div class="holder required">
                <label for="time_of_audit">Time of Audit <?php echo $beanUi->get_view_data("time_of_audit_from"); ?></label>
                From &nbsp&nbsp  <input type="text" name="data[time_of_audit_from]" readonly="" style="width:8%;" class="datetimepicker2" id="datetimepicker2" value="<?php echo $beanUi->get_view_data("time_of_audit_from"); ?>" />&nbsp&nbsp

                To &nbsp&nbsp <input type="text" name="data[time_of_audit_to]" readonly="" style="width:8%;" class="datetimepicker22" id="datetimepicker22" value="<?php echo $beanUi->get_view_data("time_of_audit_to"); ?>" />
                <div id="audit_duration_error"><?php echo $beanUi->get_error('time_of_audit'); ?></div>
            </div>

            <br />
            <div class="holder">
                <label for="audit_duration">Duration of Audit</label>
                <input type="text" readonly="" name="data[audit_duration]" id="audit_duration" value="<?php echo $beanUi->get_view_data("audit_duration"); ?>" />
                <div id="audit_duration_error"><?php echo $beanUi->get_error('audit_duration'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="place">Venue</label>
                <input type="text" readonly="" name="data[place]" id="place" value="<?php echo $beanUi->get_view_data("place"); ?>" />
                <div id="place_error"><?php echo $beanUi->get_error('place'); ?></div>
            </div>
            <div class="holder required">
                <label for="job_title">Job Title</label>
                <input type="text" name="data[job_title]" id="job_title" value="<?php echo $beanUi->get_view_data("job_title"); ?>" />
                <div id="job_title_error"><?php echo $beanUi->get_error('job_title'); ?></div>
            </div>
            <div class="holder required">
                <label for="audit_by">Audit By</label>
                <select onmousedown="return false" type="text" name="audit_by[]" multiple id="audit_by" style="width:300px;height:180px;" />
                <?php
                //echo '<option value="" selected="">---Select---</option>';
                if (!empty($user_details)) {
                    foreach ($user_details as $skey => $svalue) {
                        echo "<option value='" . $svalue->id . "' data-select='" . $svalue->audited_by_code . "'>" . $svalue->full_name . "</option>";
                    }
                }
                ?>
                </select>
                <div id="audit_by_error"><?php echo $beanUi->get_error('audit_by'); ?></div>
            </div>
<!--            <div class="holder required">
                <label for="audit_by">Audit By</label>
                <select type="text" name="audit_by[]" multiple id="audit_by" style="width:300px;height:180px;" />
                <?php
//                echo '<option value="" selected="">---Select---</option>';
//                if (!empty($user_details)) {
//                    foreach ($user_details as $skey => $svalue) {
//                        echo "<option value='" . $svalue->id . "' " . ((($svalue->id) == ($beanUi->get_view_data("audit_by"))) ? "selected" : "") . ">" . $svalue->full_name . "</option>";
//                    }
//                }
                ?>
                </select>
                <div id="audit_by_error"><?php echo $beanUi->get_error('audit_by'); ?></div>
            </div>-->
            <br />
            <div class="holder">
                <fieldset>
                    <legend>Upload Office Files</legend>
                    <label for="image_path">Upload File</label>
                    <input type="file" name="file_path[]" id="file_path" />
                    <input type="button" id="add_upload_file" value="Add another office file" class="btn btn-sm btn-primary" />
                    <br />
                    <label for="caption">File Caption</label>
                    <input type="text" name="caption[]" id="caption"  placeholder="File Caption" value="" />
                    <div id="file_path_error"><?php echo $beanUi->get_error('file_path'); ?></div>
<?php
echo FILESIZE_ERROR;
echo FILE_EXTN_ALLOWED_MSG;
?>
                </fieldset>				
            </div>
            <div id="extra_upload_files"></div>
            <br />
            <div class="holder">
                <fieldset>
                    <legend>Upload Image</legend>
                    <label for="image_path">Upload Image</label>
                    <input type="file" name="image_path[]" id="image_path" />
                    <input type="button" id="add_upload_image" value="Add another image" class="btn btn-sm btn-primary" />
                    <br />
                    <label for="caption">Image Caption</label>
                    <input type="text" name="image_captions[]" id="image_captions"  placeholder="Image Caption" />
                    <br>
<?php
echo FILESIZE_ERROR;
echo IMAGE_EXTN_ALLOWED_MSG;
?>
                </fieldset>
            </div>
            <div class="holder" id="extra_upload_images"></div>
            <br />
            <div class="holder">
                <fieldset>
                    <legend>Upload Video's</legend>
                    <label for="video_path">Upload Video</label>
                    <input type="file" name="video_path[]" id="video_path" />
                    <input type="button" id="add_upload_video" value="Add another video" class="btn btn-sm btn-primary" />
                    <br />
                    <label for="caption">Video Caption</label>
                    <input type="text" name="video_captions[]" id="video_captions"  placeholder="Video Caption" />
                    <br>
<?php echo FILESIZE_ERROR;
echo VIDEO_EXTN_ALLOWED_MSG;
?>
                </fieldset>
            </div>
            <div class="holder" id="extra_upload_video"></div>
            <br />	
            <div class="holder">
                <fieldset class="">
                    <legend>Upload Featured Image</legend>
                    <label for="featured_image">Featured Image</label>
                    <!-- CSS -->
                    <img src="<?php echo url("assets/css/cropimage/img/audit.png"); ?>" id="avatar2" width="150" style="padding:2px;border:1px solid #d0d0d0;">
                    <button type="button" class="btn btn-primary btn-sm" data-ip-modal="#avatarModal" >Upload Photo</button>
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
                                    <div class="btn btn-primary ip-upload">Upload <input type="file" name="file" class="ip-file"></div>
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
                                        <button type="button" class="btn btn-success ip-save" id="save-btn">Save Image</button>
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
            <div class="holder required">
                <label for="avg_mark">% of Marks</label>
                <input type="text" readonly="" class="auto" data-v-max="100.99" data-m-dec="2" name="data[avg_mark]" id="avg_mark" />
                <div id="avg_mark_error"><?php echo $beanUi->get_error('avg_mark'); ?></div>
            </div>
            <br />
            <script type="text/javascript">

                $(document).ready(function(){

                $('.abcc input[type="radio"]').click(function(){
                if ($(this).attr("value") == "Y"){
                $(".box").not(".red").hide();
                $(".investigation").show();
                }
                if ($(this).attr("value") == "N"){
                $(".box").not(".green").hide();
                $(".investigation").hide();
                }
                });
                });
            </script>
            <div class="investigation_done holder box2 abcc">
                <label for="major_violationxx">Violation</label>
                <input type="radio"  name="data[major_violation]" id="major_violation" value="Y" /> Yes
                <input type="radio"  name="data[major_violation]" id="major_violation" value="N" /> No
                <div id="major_violation_error"><?php echo $beanUi->get_error('major_violation'); ?></div>
            </div>
            <br />
            <div class="investigation box" style="width:100%;">
                <div class="holder">
                    <label for="synopsis" style="float:left;">No. of Violation</label>
                    <table class="table table-hover table-bordered table-condensed" width="50%" style="float:left;width:70%;">
                        <tr>
                            <td >
                                <input type="text" readonly=""  id="pid" name="data[no_of_violation]" min="1" placeholder="No. of Violation" style="width:50%;"  />
                                <a class="js-open-modal btn btn-xs btn-success" href="#" data-modal-id="popup"> <i class="fa fa-plus"></i> Add Violation</a> 
                                <!--participants popup-->
                                <div id="popup" class="modal-box" style="height: 78%;">
                                    <header> <a href="#" class="js-modal-close close">×</a>
                                        <h3>Violation Details</h3>
                                    </header>
                                    <div class="modal-body" style="height:62%;overflow-x: hidden;overflow-y: scroll;">
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                            var violation_counter = 1;
                                            $("#addButtonviolation").click(function () {
                                            var attrs = parseInt($("#valc").val()) + 1;
                                            $("#valc").val(attrs);
                                            var newTextBoxDivviolation = $(document.createElement('tr')).attr("id", 'TextBoxDivviolation' + attrs).attr("class", 'removetr');
                                            newTextBoxDivviolation.after().html('<td align="center">' + (attrs + 1) + '</td>'
                                                    + '<td width="20%"><select style="width:100%;" class="req" id="violation_type_' + attrs + '">'
                                                    + '<option value="" selected="selected" disabled="disabled">-select violation type-</option>'
                                                    + '<option value="CAPA">CAPA</option>'
                                                    + '<option value="NC">NC</option></select></td>'
                                                    + '<td width="30%"><input style="width:100%;"type="text" class="req" id="description_' + attrs + '" placeholder="Description"></td>'
                                                    + '<td width="30%"><select style="width:100%;" class="req" id="violation_category_' + attrs + '">'
                                                    + '<option value="" selected="selected" disabled="disabled">-select category-</option>'
                                                    + '<option value="Prework Checking">Prework Checking</option>'
                                                    + '<option value="TBM">TBM</option>'
                                                    + ' <option value="SWP/WI Violation">SWP/WI Violation</option>'
                                                    + '<option value="Job Safety - Working at Height">Job Safety - Working at Height</option>'
                                                    + '<option value="Job Safety - Hot Job / Cutting / Grinding Jobs">Job Safety - Hot Job / Cutting / Grinding Jobs</option>'
                                                    + '<option value="Job Safety - Confined Space / Excavation Job">Job Safety - Confined Space / Excavation Job</option>'
                                                    + '<option value="Job Safety - Chemical Plant Job">Job Safety - Chemical Plant Job</option>'
                                                    + '<option value="Job Safety - Heavy Materials Handling">Job Safety - Heavy Materials Handling</option>'
                                                    + '<option value="Job Safety - Electrical Safety">Job Safety - Electrical Safety</option>'
                                                    + '<option value="Use of Tools">Use of Tools</option>'
                                                    + '<option value="Tools Availability">Tools Availability</option>'
                                                    + '<option value="Safe Zone Creation">Safe Zone Creation</option>'
                                                    + '<option value="PPE Use">PPE Use</option>'
                                                    + '<option value="PPE Avilibility">PPE Avilibility</option>'
                                                    + '<option value="Supervision">Supervision</option>'
                                                    + '</select></td><td width="30%">'
                                                    + '<input style="width:100%;" type="text" id="remarks_' + attrs + '" placeholder="Remarks" />'
                                                    + '</td>'
                                                    + '<td class="center"  align="center" width="5%"><a class="btn btn-danger btn-sm rmbtnn" id="removeButton_' + attrs + '" name="delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>');
                                            $("#nof").val(violation_counter + 1);
                                            newTextBoxDivviolation.appendTo("#TextBoxesGroupviolation");
                                            violation_counter++;
                                            $(".rmbtnn").show();
                                            function renumberRows() {

                                            $('#TextBoxesGroupviolation tr').each(function(index, el){
                                            $(this).children('td').first().text(function(i, t) {
                                            return index++;
                                            });
                                            });
                                            }
                                            $(".rmbtnn").click(function () {

                                            var cnd = $(this).parents("tr .removetr").siblings().length;
                                            if (cnd >= 1){
                                            if ($(this).parents("tr .removetr").siblings().length && window.confirm('Delete this row ?')) {
                                            var nofval = $('#nof').val();
                                            $('#nof').val(nofval - 1);
                                            var valss = $(this).closest('tr').find('.req').val();
                                            if (valss != null)
                                            {
                                            var pidval = $('#pid').val();
                                            $('#pid').val(pidval - 1);
                                            }
                                            violation_counter--;
                                            $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
                                            }
                                            }
                                            });
                                            renumberRows();
                                            $('.complainces').change(function(){
                                            if ($(this).val() == "Y"){
                                            $(this).nextAll('.showdiv').show();
                                            } else{
                                            $(this).nextAll('.showdiv').hide();
                                            }
                                            });
                                            jQuery('.datetimepicker').datetimepicker({
                                            timepicker:false,
                                                    scrollMonth : false,
                                                    scrollInput : false,
                                                    format:'Y-m-d',
                                                    step:5
                                            });
                                            });
                                            function renumberRows() {
                                            $('#TextBoxesGroupviolation tr').each(function(index, el){
                                            $(this).children('td').first().text(function(i, t){
                                            return index++;
                                            });
                                            });
                                            }
                                            $(".rmbtnn1").click(function () {
                                            var cnd = $(this).parents("tr .removetr").siblings().length;
                                            if (cnd >= 1){
                                            if ($(this).parents("tr .removetr").siblings().length && window.confirm('Delete this row ?')) {
                                            var nofval = $('#nof').val();
                                            $('#nof').val(nofval - 1);
                                            var valss = $(this).closest('tr').find('.req').val();
                                            if (valss != null) {
                                            var pidval = $('#pid').val();
                                            $('#pid').val(pidval - 1);
                                            }
                                            violation_counter--;
                                            $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
                                            }
                                            }
                                            });
                                            renumberRows();
                                            });
                                        </script>
                                        <table id='TextBoxesGroupviolation<?php //echo $prow->id; ?>' class="table table-bordered">
                                            <tr>
                                                <th>Sl.No</th>
                                                <th>Violation Type</th>
                                                <th>Description of Violation</th>                                        
                                                <th>Violation Category</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                            <tr id="TextBoxDivviolation0" class="removetr">
                                                <td align="center">1</td>
                                                <td width="20%">
                                                    <select style="width:100%;" class="req" id="violation_type_0">
                                                        <option value="" selected="selected" disabled="disabled">-select violation type-</option>
                                                        <option value="CAPA">CAPA</option>
                                                        <option value="NC">NC</option>
                                                    </select>
                                                </td>
                                                <td width="30%"><input style="width:100%;" class="req"  type='text' id='description_0' placeholder='Description' ></td>
                                                <td width="30%">
                                                    <select style="width:100%;" class="req" id="violation_category_0">
                                                        <option value="" selected="selected" disabled="disabled">-select category-</option>
                                                        <option value="Prework Checking">Prework Checking</option>
                                                        <option value="TBM">TBM</option>
                                                        <option value="SWP/WI Violation">SWP/WI Violation</option>
                                                        <option value="Job Safety - Working at Height">Job Safety - Working at Height</option>
                                                        <option value="Job Safety - Hot Job / Cutting / Grinding Jobs">Job Safety - Hot Job / Cutting / Grinding Jobs</option>
                                                        <option value="Job Safety - Confined Space / Excavation Job">Job Safety - Confined Space / Excavation Job</option>
                                                        <option value="Job Safety - Chemical Plant Job">Job Safety - Chemical Plant Job</option>
                                                        <option value="Job Safety - Heavy Materials Handling">Job Safety - Heavy Materials Handling</option>
                                                        <option value="Job Safety - Electrical Safety">Job Safety - Electrical Safety</option>
                                                        <option value="Use of Tools">Use of Tools</option>
                                                        <option value="Tools Availability">Tools Availability</option>
                                                        <option value="Safe Zone Creation">Safe Zone Creation</option>
                                                        <option value="PPE Use">PPE Use</option>
                                                        <option value="PPE Avilibility">PPE Avilibility</option>
                                                        <option value="Supervision">Supervision</option>
                                                    </select>
                                                </td>
                                                <td width="30%">
                                                    <input style="width:100%;" type="text"  id="remarks_0" placeholder="Remarks" />
                                                </td>
                                                <td align="center" width="5%"><a ID="dataremove" name="delete" class="btn btn-danger btn-sm rmbtnn1"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <footer style="margin-left:0px;"> 
                                        <input type='hidden' id="nof" name="finding_count" value="1" style="float:left;width:100px;">
                                        <input type='hidden' id="valc" name="finding_count2" value="0" style="float:left;width:200px;">
                                        <input type='button' value='Add More' class="btn btn-primary" id='addButtonviolation' style="float:left;">

                                        <button  id="save_pdetails" class="btn btn-small btn-primary savebtn">Save</button>
                                    </footer>
                                </div>
                                <!--participants popup-->
                                <!--                                </div>-->
                            </td>
                        </tr>
                        <script type="text/javascript">
                            $(document).ready(function(){
                            $('input[type="checkbox"]').click(function(){
                            if ($(this).attr("value") == "<?php echo $prow->id; ?>"){
                            $(".<?php echo $prow->id; ?>").toggle();
                            $('#pid_<?php echo $prow->id; ?>').prop('disabled', false);
                            }
                            });
                            //called when key is pressed in textbox
                            $("#pid_<?php echo $prow->id; ?>").keypress(function (e) {
                            //if the letter is not digit then display error and don't type anything
                            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                            //display error message
                            ////$("#errmsg_<?php echo $prow->id; ?>").html("Digits Only").show().fadeOut("slow");
                            alert("Accept Only Numbers");
                            return false;
                            }
                            });
                            });
                        </script>
                    </table>
                </div>
                <br />
            </div>
            <br>
            <div class="holder">
                <label for="job_stop_req_raisedx">Job Stop Request Raised</label>
                <input type="radio"  name="data[job_stop_req_raised]" id="job_stop_req_raised" value="Y" /> Yes
                <input type="radio"  name="data[job_stop_req_raised]" id="job_stop_req_raised" value="N" /> No
                <div id="job_stop_req_raised_error"><?php echo $beanUi->get_error('job_stop_req_raised'); ?></div>
            </div>
            <br />
            <div class="holder">
                <label for="remarks">Remarks</label>
                <textarea name="data[remarks]" id="remarks"><?php echo $beanUi->get_view_data("remarks"); ?></textarea>
                <div id="remarks_error"><?php echo $beanUi->get_error('remarks'); ?></div>
            </div>
            <br />
            <div class="holder">
                <label for="status_id">status</label>
                <select name="data[status_id]" id="status_id">
<?php
$created_by = $beanUi->get_view_data("created_by");
$role_id = $controller->get_auth_user("role_id");
if (!empty($post_status)) {
    if ($role_id == 1) {
        $status = array("Draft", "Final Submit");
    } else if ($role_id == 2) {
        $status = array("Draft", "Final Submit");
    } else if ($role_id == 3) {
        $status = array("Draft", "Final Submit");
    }
    $status_id = $beanUi->get_view_data("status_id");
    foreach ($post_status as $statusrow) {

        if (in_array($statusrow->status_name, $status)) {
            echo '<option value="' . $statusrow->id . '">' . $statusrow->status_name . '</option>' . "\n";
        }
    }
}
?>
                </select>
                <div id="status_id_error"><?php echo $beanUi->get_error('status_id'); ?></div>
            </div>
            <br />
            <br />
            <div class="holder">
                <input type="submit" value="Submit" class="btn btn-sm btn-primary disablebutton" />
                <a href="index.php?activity_id=<?php echo $activity_id; ?>" class="btn btn-sm btn-danger">Cancel</a>
                <input type="hidden" name="_action" value="Create" class="btn btn-sm btn-success" />
                <input type="hidden" id="f_image_error" value="" />
            </div>
        </form>
    </div>
</div>
                    <?php $controller->get_footer(); ?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js"); ?>"></script>
<script type="text/javascript">
                            jQuery(function($) {
                            $('.auto').autoNumeric('init');
                            });</script>
<!-- JavaScript Cropper -->
<script type="text/javascript" src="<?php echo url("assets/js/jquery.Jcrop.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo url("assets/js/jquery.imgpicker.js") ?>"></script>
<div id="tracking_post_detail"></div>
<!-- Datepicker -->
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>
<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
<script type="text/javascript">
                            jQuery.datetimepicker.setLocale('en');
//    jQuery('.datetimepicker').datetimepicker({    
//        timepicker:false,
//	scrollMonth : false,
//	scrollInput : false,
//	format:'Y-m-d',
//	step:5
//    });
//    jQuery('.datetimepicker_for_time').datetimepicker({	
//        datepicker:false,
//	scrollMonth : false,
//	scrollInput : false,
//	format:'H:i',
//	step:1
//    });
                            var $z = jQuery.noConflict();
                            $z(function() {
                            var date = new Date();
                            var currentMonth = date.getMonth();
                            var currentDate = date.getDate();
                            var currentYear = date.getFullYear();
                            $z('.datetimepicker').datetimepicker({
                            timepicker: false,
                                    scrollMonth: false,
                                    scrollInput: false,
                                    format: 'Y-m-d',
<?php
$currentDate = date('Y-m-d');
if ($currentDate >= CHECK_DATE && ($role_id != 1 && $role_id != 3)) {
    ?>
                                minDate: new Date(currentYear, currentMonth, currentDate - (currentDate - 1)),
<?php } if ($currentDate < CHECK_DATE && ($role_id != 1 && $role_id != 3)) { ?>
                                minDate: new Date(currentYear, (currentMonth - 1), currentDate - (currentDate - 1)),
<?php } ?>
                            step: 5
                            });
                            });
                            $("#datetimepicker1").on("change", function (e) {
                            var selected_date = $('#datetimepicker1').val();
                            if (selected_date != ""){
                            var ajax_data = {
                            "action"	: "getDataByDate",
                                    "selected_date"	: selected_date,
                                    "activity_id" 	: <?php echo $activity_id; ?>,
                                    "table_name"	: "audit_view",
                            };
                            $.ajax({
                            type: 'post',
                                    cache: false,
                                    data: ajax_data,
                                    success: function (getDataByDate) {
                                    if (getDataByDate) {
                                    $("#showrelatedData").html(getDataByDate);
                                    }
                                    return false;
                                    }
                            });
                            }
                            return false;
                            });
                            /*Datepicker*/

                            /*time of_audit calculation*/
                            function actualTime(reportingTime, releasingTime) {
                            if (reportingTime != '' && releasingTime != '')  {
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
                            if (actualHours < 0){
                            alert("Please check your reporting and releasing Times");
                            return false;
                            } else{
                            var actualHour = parseInt(actualHours / 60);
                            var actualMin = parseInt(actualHours % 60);
                            actualMin = ("0" + actualMin).slice( - 2);
                            var actualTime = actualHour + ":" + actualMin;
                            return actualTime;
                            }
                            }
                            }

                            $(function(){
                            $(".combodate").change(function() {
                            var asd = '';
                            $(".combodate").each(function() {
                            asd += ($(this).val() ? $(this).val() : '00') + ":";
                            });
                            $("#datetimepicker2").val(asd.slice(0, - 1));
                            var reportingTime = $("#datetimepicker2").val();
                            var releasingTime = $("#datetimepicker22").val();
                            if (releasingTime <= reportingTime && releasingTime != "") {
                            $(".combodateto").val('');
                            $("#datetimepicker22").val('');
                            $("#audit_duration").val('');
                            alert("Realeasing time should be greater than Reporting time..");
                            return false;
                            }
                            else
                            {
                            if ((releasingTime >= reportingTime)) {
                            var actualTimes = actualTime(reportingTime, releasingTime);
                            $("#audit_duration").val(actualTimes);
                            } else{
                            var rept = reportingTime.split(":");
                            var reptHr = rept[0];
                            var reptMin = rept[1];
                            var rept2 = releasingTime.split(":");
                            var relsMin2 = rept2[1];
                            var relsHr = rept2[0];
                            if (relsMin2 < reptMin && relsMin2 != "00"){
                            $(".combodateto").val('');
                            $("#datetimepicker22").val('');
                            $("#audit_duration").val('');
                            alert("Realeasing time should be greater than Reporting times..");
                            }
                            $("#audit_duration").val("");
                            }
                            }
                            });
                            $(".combodateto").change(function() {
                            var asd = '';
                            $(".combodateto").each(function(){
                            asd += ($(this).val() ? $(this).val() : '00') + ":";
                            });
                            $("#datetimepicker22").val(asd.slice(0, - 1));
                            var reportingTime = $("#datetimepicker2").val();
                            var releasingTime = $("#datetimepicker22").val();
                            var abc = (parseInt(releasingTime) + 1) + ':00';
                            if ((abc <= reportingTime) && releasingTime != "") {
                            $(".combodateto").val('');
                            $("#datetimepicker22").val('');
                            $("#audit_duration").val('');
                            alert("Realeasing time should be greater than Reporting time..");
                            return false;
                            }
                            else
                            {
                            if ((releasingTime >= reportingTime)) {
                            var actualTimes = actualTime(reportingTime, releasingTime);
                            $("#audit_duration").val(actualTimes);
                            } else{
                            var rept = reportingTime.split(":");
                            var reptHr = rept[0];
                            var reptMin = rept[1];
                            var rept2 = releasingTime.split(":");
                            var relsMin2 = rept2[1];
                            var relsHr = rept2[0];
                            if ((relsMin2 < reptMin && relsMin2 != "00") || (relsHr < reptHr)){
                            $(".combodateto").val('');
                            $("#datetimepicker22").val('');
                            $("#audit_duration").val('');
                            alert("Realeasing time should be greater than Reporting times..");
                            }
                            $("#audit_duration").val("");
                            }
                            }
                            });
                            });
//    jQuery("#datetimepicker2").change(function(){        
//    });
//    jQuery("#datetimepicker22").change(function(){
//        var reportingTime = jQuery("#datetimepicker2").val();
//        var releasingTime = jQuery("#datetimepicker22").val();
//        if(releasingTime <= reportingTime && releasingTime !="") { 
//            jQuery("#datetimepicker22").val('');
//            jQuery("#audit_duration").val('');
//            alert("Realeasing time should be greater than Reporting time..");
//            return false;
//        }
//        else
//        {
//            var actualTimes = actualTime(reportingTime,releasingTime);
//            jQuery("#audit_duration").val(actualTimes);
//        }
//    });

                            /*time of_audit calculation*/
                            var $m = jQuery.noConflict();
                            $m(function(){
                            var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
                            $m('a[data-modal-id]').click(function(e) {
                            var row_id = $(this).attr("data-modal-id").replace("popup", "");
                            var pid_value = $("#pid_" + row_id).val();
                            var text2 = '';
                            for (var i = 0; i < pid_value; i++)
                            {
                            text2 += '<tr>';
                            if (row_id != 4 && row_id != 2)
                            {
                            text2 += '<td><input style="width:80%;" type="text" id="emp_code_' + row_id + '_' + i + '" placeholder="Employee Code" /></td>';
                            }
                            text2 += '<td><input style="width:80%;" type="text" id="name_' + row_id + '_' + i + '" placeholder="Employee Name" /></td>';
                            text2 += '<td><input style="width:80%;" type="text" id="designation_' + row_id + '_' + i + '"  placeholder="Designation" /></td>';
                            text2 += '<td><input style="width:80%;" type="text" id="department_' + row_id + '_' + i + '"  placeholder="Department" /></td>';
                            text2 += '</tr>';
                            }
                            $("#pdetails_" + row_id).html(text2);
                            e.preventDefault();
                            $m("body").append(appendthis);
                            $m(".modal-overlay").fadeTo(500, 0.7);
                            var modalBox = $(this).attr('data-modal-id');
                            $m('#' + modalBox).fadeIn($(this).data());
                            });
                            $m(".js-modal-close, .modal-overlay").click(function() {
                            $m(".modal-box, .modal-overlay").fadeOut(500, function() {
                            $m(".modal-overlay").remove();
                            });
                            return false;
                            });
                            $m(window).resize(function() {
                            $m(".modal-box").css({
                            top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
                                    left: "10%"
                            });
                            });
                            $m(window).resize();
                            });
                            // Featured image
                            var $s = jQuery.noConflict();
                            $s(function() {
                            var time = function(){return'?' + new Date().getTime()};
                            // Avatar setup
                            $s('#avatarModal').imgPicker({
                            url: 'server/upload_avatar.php',
                                    aspectRatio: 1,
                                    deleteComplete: function() {
                                    $s('#avatar2').attr('src', 'assets/img/audit.png');
                                    this.modal('hide');
                                    },
                                    uploadSuccess: function(image) {
                                    var select = [0, (image.height - image.width) / 2, 250, 250];
                                    this.options.setSelect = select;
                                    },
                                    cropSuccess: function(image) {
                                    $s('#avatar2').attr('src', image.versions.avatar.url + time());
                                    $s('#avatar3').attr('value', image.versions.avatar.url + time());
                                    $s('#avatar4').attr('value', image.versions.avatar.url + time());
                                    this.modal('hide');
                                    }
                            });
                            // Demo only
                            $('.navbar-toggle').on('click', function(){$('.navbar-nav').toggleClass('navbar-collapse')});
                            $(window).resize(function(e){if ($(document).width() >= 430)$('.navbar-nav').removeClass('navbar-collapse')});
                            });
// End
                            $(document).ready(function(){
                            $("#avg_mark").on("keypress keyup blur", function (event) {
                            $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
                            if ((event.which != 46 || $(this).val().indexOf('.') != - 1) && (event.which != 8) && (event.which < 48 || event.which > 57)) {
                            event.preventDefault();
                            if ((pointPos = $(this).val().indexOf('.')) >= 0)
                            {
                            $(this).attr("maxLength", pointPos + 3);
                            }
                            }
                            });
                            });
                            jQuery(document).ready(function ($) {
                            $('.savebtn').on('click', function (e) {
                            var flag = true;
                            $('.req').each(function () {
                            if (jQuery.trim($(this).val()) == ''){
                            $(".req").css("border-color", "red");
                            flag = false;
                            }
                            return flag;
                            });
                            $('.req').each(function () {
                            if (jQuery.trim($(this).val()) != ''){
                            $(this).css("border-color", "#999");
                            }
                            });
                            if (flag == true) {
                            e.preventDefault();
                            var token_id = $("#token_id").val();
                            var violation_type = Array();
                            var description = Array();
                            var violation_category = Array();
                            var remarks = Array();
                            var no_of_parti = $("#valc").val();
                            for (var row_no = 0; row_no <= no_of_parti; row_no++)
                            {
                            description[row_no] = $.trim($("#description_" + row_no).val());
                            violation_type[row_no] = $.trim($("#violation_type_" + row_no).val());
                            violation_category[row_no] = $.trim($("#violation_category_" + row_no).val());
                            remarks[row_no] = $.trim($("#remarks_" + row_no).val());
                            }
                            var ajax_data = {
                            "action"                    : "save_violation",
                                    "violation_type"            : violation_type,
                                    "description"               : description,
                                    "violation_category"        : violation_category,
                                    "remarks"                   : remarks,
                                    "nof"                       : no_of_parti,
                                    "token_id"                  : token_id
                            };
                            $.ajax({

                            type: 'post',
                                    cache : false,
                                    data: ajax_data,
                                    success: function (save_violation) {
                                    if (save_violation)
                                    {
                                    $("#pid").val(save_violation);
                                    $("#popup").hide()
                                            $(".modal-overlay").remove();
                                    }
                                    else
                                    {
                                    $("#popup").hide()
                                            $(".modal-overlay").remove();
                                    }
                                    }
                            });
                            }
                            return flag;
                            });
                            $("#add_upload_file").click(function () {
                            var boxnumber = 1 + Math.floor(Math.random() * 6);
                            var another_image_upload_html =
                                    '<div class="holder" id="upload_image_box_' + boxnumber + '">' + "\n" +
                                    '<fieldset>' + "\n" +
                                    '<legend>Upload File</legend>' + "\n" +
                                    '<label for="image_path">Upload File</label>' + "\n" +
                                    '<input type="file" name="file_path[]" id="file_path" />' + "\n" +
                                    '<input type="button" value="Remove office file" class="btn btn-danger btn-sm" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
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
                                    '<input type="button" value="Remove image" class="btn btn-danger btn-sm" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
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
                                    '<input type="button" value="Remove video" class="btn btn-danger btn-sm" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
                                    '<br />' +
                                    '<label for="caption">Video Caption</label>' + "\n" +
                                    '<input type="text" name="video_captions[]" id="video_captions"  placeholder="Video Caption" />' + "\n" +
                                    '</fieldset>' + "\n" +
                                    '</div>' + "\n";
                            $("#extra_upload_video").append(another_image_upload_html);
                            });
                            /*       
                             function renumberRows() {
                             $('#TextBoxesGroupviolation tr').each(function(index, el){
                             $(this).children('td').first().text(function(i,t){
                             return index++;
                             });
                             });
                             }
                             $(".rmbtnn1").click(function () {
                             
                             var cnd = $(this).parents("tr .removetr").siblings().length;
                             if(cnd >1){
                             if ($(this).parents("tr .removetr").siblings().length && window.confirm('Delete this row ?')) {
                             var nofval = $('#nof').val();
                             
                             $('#nof').val(nofval -1);
                             $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
                             }
                             }
                             });
                             renumberRows();*/
                            $("#create_post").submit(function () {
                            var sas_report_no = jQuery.trim(jQuery("#sas_report_no").val());
                            var date_of_audit = jQuery.trim(jQuery("#datetimepicker1").val());
                            var datetimepicker2 = jQuery.trim(jQuery("#datetimepicker2").val());
                            var datetimepicker22 = jQuery.trim(jQuery("#datetimepicker22").val());
                            var place = jQuery.trim(jQuery("#place").val());
                            var job_title = jQuery.trim(jQuery("#job_title").val());
                            var audit_by = jQuery.trim(jQuery("#audit_by").val());
                            var avg_mark = jQuery.trim(jQuery("#avg_mark").val());
                            var division = jQuery.trim(jQuery("#division").val());
                            var set_type3 = jQuery.trim(jQuery("#set_type3").val());
                            var error_counter = 0;
                            jQuery(".error").empty();
                            if (sas_report_no == undefined || sas_report_no == "")
                            {
                            jQuery("#sas_report_no_error").html("<div class=\"error\">SAS Report No. is required.</div>");
                            error_counter++;
                            }
                            if (division == undefined || division == "")
                            {
                            jQuery("#division_error").html("<div class=\"error\">Division is required.</div>");
                            error_counter++;
                            }
                            if (set_type3 == undefined || set_type3 == "")
                            {
                            jQuery("#set_type_error").html("<div class=\"error\">Set type is required.</div>");
                            error_counter++;
                            }
                            if (date_of_audit == undefined || date_of_audit == "")
                            {
                            jQuery("#date_of_audit_error").html("<div class=\"error\">Date of audit is required.</div>");
                            error_counter++;
                            }
                            if (datetimepicker2 == undefined || datetimepicker2 == "" || datetimepicker22 == undefined || datetimepicker22 == "")
                            {
                            jQuery("#time_of_audit_error").html("<div class=\"error\">Audit time is required.</div>");
                            error_counter++;
                            }
                            if (place == undefined || place == "")
                            {
                            jQuery("#place_error").html("<div class=\"error\">Venue is required.</div>");
                            error_counter++;
                            }
                            if (job_title == undefined || job_title == "")
                            {
                            jQuery("#job_title_error").html("<div class=\"error\">Job Title is required.</div>");
                            error_counter++;
                            }
                            if (audit_by == undefined || audit_by == "")
                            {
                            jQuery("#audit_by_error").html("<div class=\"error\">Audit By is required.</div>");
                            error_counter++;
                            }

                            if (avg_mark == undefined || avg_mark == "")
                            {
                            jQuery("#avg_mark_error").html("<div class=\"error\">Average mark is required.</div>");
                            error_counter++;
                            }
                            if (error_counter == 0) {
                            $(".disablebutton").attr('disabled', true);
                            }
                            if (error_counter > 0) {
                            jQuery(".message").html("<div class=\"error\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                            jQuery('html, body').animate({ scrollTop: 0}, 'slow');
                            return false;
                            }
                            });
                            });
                            function remove_upload_image_box(boxnumber) {
                            jQuery("#upload_image_box_" + boxnumber).remove();
                            }
</script>

<link rel="stylesheet" href="<?php echo url('assets/css/jqueryui/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-1.12.4.js') ?>"></script>
<script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-ui.js') ?>"></script>

<script type="text/javascript">

                            $(function() {
                            var ajax_data = {
                            "action"	  : "getVenueList",
                                    "activity_type_id" : "<?php echo $activity_id; ?>"
                            };
                            $.ajax({
                            type: 'post', url: '<?php echo page_link("activity/add_audit.php"); ?>', cache: false,
                                    data: ajax_data,
                                    success: function (data) {
                                    if (data)
                                    {
                                    var myVanueList = data.split(' | ');
                                    $("#place").autocomplete({
                                    minLength: 1,
                                            source: myVanueList
                                    });
                                    }
                                    }
                            });
                            });
                            function sasDetails() {
                            var sas_report_no = $("#sas_report_no").val();
                            $.ajax({
                            type: 'post',
                                    cache: false,
                                    data:{"action": "getSasDetails", "sas_report_no": sas_report_no},
                                    success: function (data) {//alert(data);
                                    if (data == "null") {
                                        alert('Not Existed');
                                        $('#datetimepicker1').val("");
                                        $('#datetimepicker2').val("");
                                        $('#datetimepicker22').val("");
                                        $('#place').val("");
                                        $('#avg_mark').val("");
                                        $('#audit_by').val("");
                                        $('#audit_duration').val("");
                                        return false;
                                    }
                                    else{
                                        obj = JSON.parse(data);
                                        var is_selected = (obj.is_selected);
                                        if (is_selected == 0) {
                                        var confSASNo = confirm("Are you sure to Submit ?");
                                        if (confSASNo) {
                                            $('#datetimepicker1').val(obj.date_of_audit);
                                            $('#datetimepicker2').val(obj.time_of_audit_from);
                                            $('#datetimepicker22').val(obj.time_of_audit_to);
                                            $('#place').val(obj.place);
                                            $('#avg_mark').val(obj.avg_mark);
                                            $('#audit_duration').val(obj.audit_duration);
                                            
                                        var auditedByCode = obj.audited_by_code;
                                        var auditedByCodeResult = auditedByCode.split('|');
                                        
                                         $("#audit_by option").each(function(){
                                            var audit_val = $(this).attr("data-select");
                                             
                                                for(var i=0; i<auditedByCodeResult.length; i++){
                                                  var name = auditedByCodeResult[i];
                                                  if(name == audit_val){
                                                    //alert(audit_val+' - '+auditedByCodeResult[0]+' - '+auditedByCodeResult[1]+' - '+auditedByCodeResult[2]);
                                                    $(this).attr("selected","selected");
                                                  } else {
                                                    //alert(audit_val+' - '+auditedByCodeResult[0]+' - '+auditedByCodeResult[1]);  
                                                  }
                                                }
                                         });
                                         
                                        }}
                                        else{
                                            alert("already exist");
                                            $('#datetimepicker1').val("");
                                            $('#datetimepicker2').val("");
                                            $('#datetimepicker22').val("");
                                            $('#place').val("");
                                            $('#avg_mark').val("");
                                            $('#audit_by').val("");
                                            $('#audit_duration').val("");
                                            return false;
                                        }
                                    }
                                    }
                            });
                            }
                            function sas1Details() {
                            var sas_report_no = $("#sas_report_no").val();
                            $('#datetimepicker1').val("");
                            $('#datetimepicker2').val("");
                            $('#datetimepicker22').val("");
                            $('#place').val("");
                            $('#avg_mark').val("");
                            $('#audit_by').val("");
                            $('#audit_duration').val("");
                            $('#images').html("");
                            return false;
                            }
</script>

</body>
</html>
