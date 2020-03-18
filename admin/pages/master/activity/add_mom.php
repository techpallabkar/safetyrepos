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
$auth_user_id = $controller->get_auth_user("id");
$role_id = $controller->get_auth_user("role_id");
$created_by = $controller->get_auth_user("created_by");
$activity_id = $beanUi->get_view_data("activity_id");
$activities = get_value_by_id($activity_id, "activity_name", $post_activity_type_master);
$l1_cat_name = "";
$tag_keys = $beanUi->get_view_data("tag_keys");
$token_id = rand(000000, 111111);
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
        <form name="create_post" id="create_post" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="token_id" id="token_id" value="<?php echo $token_id; ?>" />
            <input type="hidden" name="data[activity_type_id]" value="<?php echo $activity_id; ?>" />
            <div class="holder">
                <label for="title">Activity</label>
                <b class="text-danger"> <?php echo $activities; ?> </b>
            </div>
            <br />
            <div class="holder required set">
                <label for="mom_title">Title</label>
        MOM of      <select name="data[mom_title]" id="set_type3" class="set3">
                    <option value="">Select One</option>
                    <option value="MOM of EDG Meeting">EDG Meeting</option>
                    <option value="MOM of EDHR">EDHR</option>
                    <option value="MOM of Review Meeting">Review Meeting</option>
                </select>
                <div id="incident_no_error"><?php echo $beanUi->get_error('incident_no'); ?></div>
            </div>
            <br>
            
            <div class="holder required">
                <label for="date_of_meeting">Date of Meeting</label>
                <input type="text" readonly="" name="data[date_of_meeting]" class="datetimepicker" id="datetimepicker1" value="<?php echo $beanUi->get_view_data("date_of_meeting"); ?>" />
                <div id="date_of_audit_error"><?php echo $beanUi->get_error('date_of_meeting'); ?></div>
            </div>
<!--            <div id="showrelatedData"></div>-->
            <br />
            <div class="holder required">
                <label for="time_of_meeting">Time of Meeting</label>
                <input style="width:10%;"  readonly="" placeholder="" type="text" class="datetimepicker_for_time" name="data[time_of_meeting]" id="datetimepicker2" value="<?php echo $beanUi->get_view_data("time_of_meeting"); ?>" />
<!--                To <input style="width:10%;" readonly="" placeholder="To" type="text" class="datetimepicker_for_time" name="data[time_of_audit_to]" id="datetimepicker22" value="<?php echo $beanUi->get_view_data("time_of_audit_to"); ?>" />-->
                <div id="time_of_audit_error"><?php echo $beanUi->get_error('time_of_meeting'); ?></div>
            </div>
            
            

            <div class="holder">
                <fieldset>
                    <legend>Upload Office Files</legend>
                    <label for="file_path">Upload File</label>
                    <input type="file" name="file_path[]" id="file_path" />
                    <input type="button" id="add_upload_file" value="Add another" class="btn btn-sm btn-primary" />
                    <br />
                    <label for="caption">File Caption</label>
                    <input type="text" name="caption[]" id="caption"  placeholder="File Caption" value="" />
                    <div id="file_path_error"><?php echo $beanUi->get_error('file_path'); ?></div>
<?php echo FILESIZE_ERROR; ?>
                </fieldset>				
            </div>
            <div id="extra_upload_files"></div>
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
                    <br>
<?php echo FILESIZE_ERROR; ?>
                </fieldset>
            </div>
            <div class="holder" id="extra_upload_images"></div>
            <br />
            	-->
            <div class="holder required">
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
                <input type="submit" value="Submit" class="btn btn-sm btn-primary" />
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
                            jQuery(function ($) {
                                $('.auto').autoNumeric('init');
                            });
</script>
<!-- JavaScript Cropper -->
<script type="text/javascript" src="<?php echo url("assets/js/jquery.Jcrop.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo url("assets/js/jquery.imgpicker.js") ?>"></script>
<div id="tracking_post_detail"></div>
<!-- Datepicker -->
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>
<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
<script>
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

//                            $("#datetimepicker1").on("change", function (e) {
//
//                                var selected_date = $('#datetimepicker1').val();
//
//                                var ajax_data = {
//                                    "action": "getDataByDate",
//                                    "selected_date": selected_date,
//                                    "activity_id": <?php echo $activity_id; ?>,
//                                    "table_name": "audit_view",
//                                };
//                                $.ajax({
//                                    type: 'post',
//                                    cache: false,
//                                    data: ajax_data,
//                                    success: function (getDataByDate) {
//                                        if (getDataByDate)
//                                        {
//                                            $("#showrelatedData").html(getDataByDate);
//                                        }
//                                        return false;
//                                    }
//                                });
//                                return false;
//                            });

                            /*Datepicker*/

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
                                if (releasingTime <= reportingTime && releasingTime != "") {
                                    jQuery("#datetimepicker22").val('');
                                    jQuery("#audit_duration").val('');
                                    alert("Realeasing time should be greater than Reporting time..");
                                    return false;
                                } else
                                {
                                    var actualTimes = actualTime(reportingTime, releasingTime);
                                    jQuery("#audit_duration").val(actualTimes);
                                }
                            });

                            /*time of_audit calculation*/
                            var $m = jQuery.noConflict();
                            $m(function () {
                                var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
                                $m('a[data-modal-id]').click(function (e) {
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
                                $m(".js-modal-close, .modal-overlay").click(function () {
                                    $m(".modal-box, .modal-overlay").fadeOut(500, function () {
                                        $m(".modal-overlay").remove();
                                    });
                                    return false;
                                });
                                $m(window).resize(function () {
                                    $m(".modal-box").css({
                                        top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
                                        left: "10%"
                                    });
                                });
                                $m(window).resize();
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
                                        this.modal('hide');
                                    },
                                    uploadSuccess: function (image) {
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
                                $('.navbar-toggle').on('click', function () {
                                    $('.navbar-nav').toggleClass('navbar-collapse')
                                });
                                $(window).resize(function (e) {
                                    if ($(document).width() >= 430)
                                        $('.navbar-nav').removeClass('navbar-collapse')
                                });
                            });

// End
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
                            jQuery(document).ready(function ($) {
                                $('.savebtn').on('click', function (e) {
                                    var flag = true;
                                    $('.req').each(function () {
                                        if (jQuery.trim($(this).val()) == '') {
                                            // alert("Please fill all boxes");

                                            $(".req").css("border-color", "red");

                                            flag = false;

                                        }

                                        return flag;
                                    });

                                    $('.req').each(function () {
                                        if (jQuery.trim($(this).val()) != '') {
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
                                        //var no_of_parti         = $("#nof").val();
                                        var no_of_parti = $("#valc").val();

                                        //if( no_of_parti > 0 )
                                        //{
                                        for (var row_no = 0; row_no <= no_of_parti; row_no++)
                                        {
                                            description[row_no] = $.trim($("#description_" + row_no).val());
                                            violation_type[row_no] = $.trim($("#violation_type_" + row_no).val());
                                            violation_category[row_no] = $.trim($("#violation_category_" + row_no).val());
                                            remarks[row_no] = $.trim($("#remarks_" + row_no).val());

                                        }
                                        var ajax_data = {
                                            "action": "save_violation",
                                            "violation_type": violation_type,
                                            "description": description,
                                            "violation_category": violation_category,
                                            "remarks": remarks,
                                            "nof": no_of_parti,
                                            "token_id": token_id
                                        };
                                        $.ajax({

                                            type: 'post',
                                            cache: false,
                                            data: ajax_data,
                                            success: function (save_violation) {
                                                if (save_violation)
                                                {
                                                    $("#pid").val(save_violation);
                                                    $("#popup").hide()
                                                    $(".modal-overlay").remove();
                                                } else
                                                {
                                                    $("#popup").hide()
                                                    $(".modal-overlay").remove();
                                                }
                                            }
                                        });
                                        // }
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
                                            '<input type="button" value="Remove" class="btn btn-danger btn-sm" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
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
                                            '<input type="button" value="Remove" class="btn btn-danger btn-sm" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
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
//	$("#create_post").submit(function () {
//            var audit_no = jQuery.trim( jQuery("#audit_no").val() );
//            var date_of_audit = jQuery.trim( jQuery("#datetimepicker1").val() );
//            var time_of_audit = jQuery.trim( jQuery("#datetimepicker2").val() );
//            var audit_duration = jQuery.trim( jQuery("#audit_duration").val() );
//            var featured_image = jQuery.trim( jQuery("#avatar3").val() );
//            var remarks = jQuery.trim( jQuery("#remarks").val() );
//            var division = jQuery.trim( jQuery("#division").val() );
//            var error_counter = 0;
//            jQuery(".errors").empty();
//            if( division == undefined || division == "" ) 
//            {
//                jQuery("#division_error").html("<div class=\"errors\">Division is required.</div>");
//                error_counter++;
//            }
//            if( audit_no == undefined || audit_no == "" ) 
//            {
//                jQuery("#audit_no_error").html("<div class=\"errors\">Audit no is required.</div>");
//                error_counter++;
//            }		
//            if( date_of_audit == undefined || date_of_audit == "" ) 
//            {
//                jQuery("#date_of_audit_error").html("<div class=\"errors\">Audit date is required.</div>");
//                error_counter++;
//            }
//            if( time_of_audit == undefined || time_of_audit == "" ) 
//            {
//                jQuery("#time_of_audit_error").html("<div class=\"errors\">Audit time is required.</div>");
//                error_counter++;
//            }
//            if( audit_duration == undefined || audit_duration == "" ) 
//            {
//                jQuery("#audit_duration_error").html("<div class=\"errors\">Duration is required.</div>");
//                error_counter++;
//            }
//            if( featured_image == undefined || featured_image == "" ) 
//            {
//                jQuery("#featured_image_error").html("<div class=\"errors\">Featured image is required.</div>");
//                error_counter++;
//            }   
//            if( remarks == undefined || remarks == "" ) 
//            {
//                jQuery("#remarks_error").html("<div class=\"errors\">Remarks is required.</div>");
//                error_counter++;
//            }		
//            if( error_counter > 0 ) {
//                jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
//                jQuery('html, body').animate({ scrollTop: 0}, 'slow');
//                return false;
//            }
//	});
                            });

                            function remove_upload_image_box(boxnumber) {
                                jQuery("#upload_image_box_" + boxnumber).remove();
                            }
                            function get_subcategories(parent_category_name, category_id, catlavel) {
                                //alert(parent_category_name+","+category_id+","+catlavel);
                                if (parent_category_name == "" || parent_category_name == undefined)
                                    return false;
                                $.ajax({
                                    data: {
                                        "action": "get_subcategories",
                                        "parent_category_name": parent_category_name,
                                        "category_id": category_id,
                                        "catlavel": catlavel,
                                        "prev_cat_id": 0
                                    }, url: "<?php echo page_link(); ?>", type: "post", cache: false, success: function (categories) {
                                        //alert(categories);
                                        if (catlavel == 2 && parent_category_name == "Article") {
                                            jQuery("#post_subcat_html").html(categories).show();
                                            jQuery("#post_end_cat_html").empty();
                                        } else if (catlavel == 2 && parent_category_name != "Article") {
                                            var cathtmls = categories.split("|");
                                            if (cathtmls == "") {
                                                jQuery("#post_subcat_html").empty();
                                                jQuery("#post_end_cat_html").empty();
                                            }
                                            jQuery("#post_subcat_html").html(cathtmls[0]);
                                            jQuery("#post_subcat_html").show();
                                            jQuery("#post_end_cat_html").html(cathtmls[1]);
                                            jQuery("#post_end_cat_html").show();
                                        } else if (catlavel == 3) {

                                            jQuery("#post_end_cat_html").html(categories);
                                            jQuery("#post_end_cat_html").show();
                                        }
                                    }
                                });
                            }


</script>


</body>
</html>
