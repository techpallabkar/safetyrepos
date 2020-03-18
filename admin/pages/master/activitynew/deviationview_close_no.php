<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityController");
$controller->doAction();

$beanUi = $controller->beanUi;
//$post_categories = $beanUi->get_view_data("post_categories");
//$post_participants_categories = $beanUi->get_view_data("post_participants_categories");
//$post_division_department = $beanUi->get_view_data("post_division_department");
//$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
//$post_status = $beanUi->get_view_data("post_status");
//$user_details = $beanUi->get_view_data("user_details");
//$auth_user_id = $controller->get_auth_user("id");
//$role_id = $controller->get_auth_user("role_id");
//$created_by = $controller->get_auth_user("created_by");
//$activity_id = $beanUi->get_view_data("activity_id");
//$activities = get_value_by_id($activity_id, "activity_name", $post_activity_type_master);
//$l1_cat_name = "";
//$tag_keys = $beanUi->get_view_data("tag_keys");
//$token_id = rand(000000, 111111) . time();
//$controller->setCss(array("cropimage/css/imgpicker"));
//$controller->setCss("tree");
$controller->get_header();
?>
<style type="text/css">
    .box{display:none;}
    .content-box{ display:none;}
    .active-content,.active-content-new{  display:block;}
    .Clearboth
    {
        clear: both;
    }

    .divright
    {
        float:right;
    }

    .divleft
    {
        float:left;
    }
    .text-vwriting-mode {
        writing-mode: vertical-rl;
        text-orientation: upright;
        vertical-align: middle !important;
        font-size: 18px;
    }
    .width100 {
        width: 100% !important;
    }
    .required2 {
        color: #F00;
    }
    textArea.height50 {
        height: 50px;
        background: #fff;
    }
    .bgari-1 {background: #57AA9A; color: #fff;} 
    .bgari-2 {background: #E1EED9;} 
    .bgari-a {color: #0066cc;} 
    .table2 {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
    }
    .table2 > tbody > tr > td {
        padding: 3px;
        line-height: 1.42857143;
        vertical-align: middle;
    }
    .table-bordered2 > thead > tr > th {
        border: 1px solid #ddd;
        font-size: 12px;
    }
    .table-bordered2 > tbody > tr > td {
        border: 1px solid #999;
    }
    .table > tbody > tr > td {
        border-top: 0px;
    }
</style>
<link rel="stylesheet" href="<?php echo url('assets/css/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jquery-ui.js') ?>"></script>
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
<div class="container1">
    <h1 class="heading">Deviation Closed : <span class="text-primary"><?php echo $activities; ?></span></h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div>
    <?php echo $beanUi->get_error("status_id"); ?>
    <br />
    <div class="panel">
        <form name="create_post" id="create_post" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="token_id" id="token_id" value="<?php echo $token_id; ?>" />
            <input type="hidden" name="data[activity_type_id]" value="<?php echo $activity_id; ?>" />
            <br />
            <table class="table table-bordered2" style="background:#a3e4d7; ">
                <tbody>
                    <tr>
                        <td width="13%" class="bgari-1"><b>Sl. No. : </b></td>
                        <td width="20%">
                            <b>SSNC0097</b>
                        </td>
                        <td width="10%" class="bgari-1"><b>Type : </b></td>
                        <td width="10%">
                            <b>NC</b>
                        </td>
                        <td width="13%" class="bgari-1"><b>Category : </b></td>
                        <td width="34%">
                            <b>PPE Availability</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="bgari-1"><b>Description : </b></td>
                        <td colspan="3">
                            <b>Connection for oil pump m/c found without plug top.</b>
                        </td>
                        <td class="bgari-1"><b>Current Status : </b></td>
                        <td><b>Closed</b></td>
                    </tr>
                </tbody>
            </table>
<!--            <table class="table table-bordered2" style="background:#a3e4d7; ">
                <tbody>
                    <tr>
                        <td width="18%" class="bgari-1"><b>Deviation Sl. No. : </b></td>
                        <td width="20%">
                            <b>SSNC0097</b>
                        </td>
                        <td width="15%" class="bgari-1"><b>Deviation Type : </b></td>
                        <td colspan="3">
                            <b>NC</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="bgari-1"><b>Deviation Description : </b></td>
                        <td colspan="5">
                            <b>Connection for oil pump m/c found without plug top.</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="bgari-1"><b>Deviation Category : </b></td>
                        <td>
                            <b>PPE Availability</b>
                        </td>
                        <td class="bgari-1"><b>Current Status : </b></td>
                        <td width="20%"><b>Closed</b></td>
                        <td width="10%" class="bgari-1"><b>Date</b></td>
                        <td width="20%">
                            <b>10/07/2018</b>
                        </td>
                    </tr>
                </tbody>
            </table>-->
            <br/>
            <h1 class="heading" style="border-bottom:3px solid #ff9f08;">Comments </h1>
            <table class="table">
                <tr>
                    <td><b>Nodal Officer - Sub Station</b><br/>10/07/2018 12:54 PM</td>
                    <td>
                         Action taken as per given specification find allow document.
                        <div class="clearfix"></div>
                        <br/>
                        <table style="width: 50%;" class="table2 table-bordered2">
                            <tbody>
                                <tr>
                                    <td class="bgari-2"><a href="#" class="bgari-a"><i class="fa fa-download"></i> Invoice</a></td>
                                </tr>
                                <tr>
                                    <td class="bgari-2"><a href="#" class="bgari-a"><i class="fa fa-download"></i> Image of Oil Pump</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><b>Admin</b><br/>06/07/2018 01:44 PM</td>
                    <td>
                        Till Date update we received you have advised to process maintain as per all allow specification.
                        <div class="clearfix"></div>
                        <br/>
                        <table style="width: 50%;" class="table2 table-bordered2">
                            <tbody>
                                <tr>
                                    <td class="bgari-2"><a href="#" class="bgari-a"><i class="fa fa-download"></i> Plug Top Specification</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="20%"><b>Nodal Officer - Sub Station</b><br/>01/07/2018 10:44 AM</td>
                    <td width="80%">
                        Note necessary action shall be taken immediately. 
                        <div class="clearfix"></div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php $controller->get_footer(); ?>
    <script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js"); ?>"></script>
    <script type="text/javascript">
        jQuery(function ($) {
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
        $z(function () {
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
            if (selected_date != "") {
                var ajax_data = {
                    "action": "getDataByDate",
                    "selected_date": selected_date,
                    "activity_id": <?php echo $activity_id; ?>,
                    "table_name": "audit_view",
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

        $(function () {
            $(".combodate").change(function () {
                var asd = '';
                $(".combodate").each(function () {
                    asd += ($(this).val() ? $(this).val() : '00') + ":";
                });
                $("#datetimepicker2").val(asd.slice(0, -1));
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
                    } else {
                        var rept = reportingTime.split(":");
                        var reptHr = rept[0];
                        var reptMin = rept[1];
                        var rept2 = releasingTime.split(":");
                        var relsMin2 = rept2[1];
                        var relsHr = rept2[0];
                        if (relsMin2 < reptMin && relsMin2 != "00") {
                            $(".combodateto").val('');
                            $("#datetimepicker22").val('');
                            $("#audit_duration").val('');
                            alert("Realeasing time should be greater than Reporting times..");
                        }
                        $("#audit_duration").val("");
                    }
                }
            });
            $(".combodateto").change(function () {
                var asd = '';
                $(".combodateto").each(function () {
                    asd += ($(this).val() ? $(this).val() : '00') + ":";
                });
                $("#datetimepicker22").val(asd.slice(0, -1));
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
                    } else {
                        var rept = reportingTime.split(":");
                        var reptHr = rept[0];
                        var reptMin = rept[1];
                        var rept2 = releasingTime.split(":");
                        var relsMin2 = rept2[1];
                        var relsHr = rept2[0];
                        if ((relsMin2 < reptMin && relsMin2 != "00") || (relsHr < reptHr)) {
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
                    var no_of_parti = $("#valc").val();
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
                var date_of_audit = jQuery.trim(jQuery("#datetimepicker1").val());
                var datetimepicker2 = jQuery.trim(jQuery("#datetimepicker2").val());
                var datetimepicker22 = jQuery.trim(jQuery("#datetimepicker22").val());
                var place = jQuery.trim(jQuery("#place").val());
                var job_title = jQuery.trim(jQuery("#job_title").val());
                var audit_by = jQuery.trim(jQuery("#audit_by").val());
                //var sas_report_no     = jQuery.trim( jQuery("#sas_report_no").val() );
                var avg_mark = jQuery.trim(jQuery("#avg_mark").val());
                var division = jQuery.trim(jQuery("#division").val());
                var set_type3 = jQuery.trim(jQuery("#set_type3").val());
                var error_counter = 0;
                jQuery(".error").empty();
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
                    jQuery('html, body').animate({scrollTop: 0}, 'slow');
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

        $(function () {
            var ajax_data = {
                "action": "getVenueList",
                "activity_type_id": "<?php echo $activity_id; ?>"
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
        });</script>
    <script>

        // JavaScript Document
        $(document).ready(function (e) {

            /// Tab Content Start 
            $('.left-section li').click(function () {

                /// Remove Class
                $('li').removeClass('active-tab-menu');
                $('.content-box').removeClass('active-content');
                // Add Class
                $(this).addClass('active-tab-menu');
                var getTabMenuClass = $(this).attr('class').split(' ')[0];
                $('.' + getTabMenuClass + '-content').addClass('active-content');
                /// Hide Next And Previous
                if (getTabMenuClass == 'form-step-1')
                {
                    $('.previous-section').addClass('Hide-this');
                    $('.next-section').removeClass('Hide-this');
                }
                else if (getTabMenuClass == 'form-step-5')
                {
                    $('.next-section').addClass('Hide-this');
                    $('.previous-section').removeClass('Hide-this');
                }
                else
                {
                    $('.previous-section').removeClass('Hide-this');
                    $('.next-section').removeClass('Hide-this');
                }



            })

            /// Tab Content End here 

            /// NEXT SECTION 
            $('.next-section').click(function () {

                if ($('li').hasClass('active-tab-menu'))
                {
                    /// Tab Menu
                    $('.active-tab-menu').next().addClass('active-tab-menu-new');
                    $('.active-tab-menu-new').prev().removeClass('active-tab-menu');
                    // Tab Content
                    $('.active-content').next().addClass('active-content-new');
                    $('.active-content-new').prev().removeClass('active-content');
                }
                else
                {
                    /// Tab Menu
                    $('.active-tab-menu-new').next().addClass('active-tab-menu');
                    $('.active-tab-menu').prev().removeClass('active-tab-menu-new');
                    // Tab Conten
                    $('.active-content-new').next().addClass('active-content');
                    $('.active-content').prev().removeClass('active-content-new');
                }

            });
            /// NEXT SECTION END

            /// PREVIOUS SECTION 
            $('.previous-section').click(function () {

                if ($('li').hasClass('active-tab-menu'))
                {
                    /// Tab Menu
                    $('.active-tab-menu').prev().addClass('active-tab-menu-new');
                    $('.active-tab-menu-new').next().removeClass('active-tab-menu');
                    // Tab Content
                    $('.active-content').prev().addClass('active-content-new');
                    $('.active-content-new').next().removeClass('active-content');
                }
                else
                {
                    /// Tab Menu
                    $('.active-tab-menu-new').prev().addClass('active-tab-menu');
                    $('.active-tab-menu').next().removeClass('active-tab-menu-new');
                    // Tab Conten
                    $('.active-content-new').prev().addClass('active-content');
                    $('.active-content').next().removeClass('active-content-new');
                }

            });
            /// NEXT SECTION END 




        });</script>
</body>
</html>
