<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityControllerNew");
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

/*DEVIATION OBSERVATION LIST PART (NIRMALENDU KHAN)*/
$alldeviations=$beanUi->get_view_data("alldeviations");
/*DEVIATION OBSERVATION LIST PART (NIRMALENDU KHAN)*/
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
    .labelcls {padding-top: 4px;width:15% !important;}
    #search-table select {width:100% !important;}
    .search{margin-bottom:0px !important;width:100% !important;}
    #search-table tr td{border: none;}
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
    }
    .table-bordered2 > tbody > tr > td {
        border: 1px solid #999;
    }
    
</style>
<link rel="stylesheet" href="<?php echo url('assets/css/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jquery-ui.js') ?>"></script>
<div class="container1">
    <!--********mbvp(pallab)**************-->
    <hr>
    <!--********mbvp(pallab)**************-->
    <h1 class="heading" style="margin:0 0 8px;">Deviation List 
    </h1> 
    <div class="holder">
        <form name="searchuser" id="searchuser" method="get">
            <table id="search-table" class="table table-condensed" style="margin-bottom:0px;">
                <input name="page" value="1" type="hidden">
                <input name="activity_id" value="5" type="hidden">
                <tbody>
                    <tr>
                        <td><b>From Date :</b></td>
                        <td><input autocomplete="off" name="fromdate" id="fromdate" class="search datetimepicker" value="<?php echo (isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : ""); ?>" placeholder="From date" type="text"></td>
                        <td width="10%"></td>
                        <td><b>To Date :</b></td>
                        <td><input autocomplete="off" name="todate" id="todate" class="search datetimepicker" value="<?php echo (isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : ""); ?>" placeholder="To date" type="text"></td>
                    </tr>
                    <tr>
                        <td><b>Deviation Sl. No. :</b></td>
                        <td><input name="deviation_sl_no" id="deviation_sl_no" class="search" value="<?php echo (isset($_REQUEST["deviation_sl_no"]) ? $_REQUEST["deviation_sl_no"] : ""); ?>" placeholder="Search by Deviation Sl. No." type="text"></td>
                        <td></td>
                        <td><b>Status :</b></td>
                        <td>
                            <select name="statusId" id="status_id">
                                <option value="">Search By Status</option>
                                <option value="1" <?php echo (($_REQUEST["statusId"]==1)?'selected':'') ?>>Open</option>
                                <option value="2" <?php echo (($_REQUEST["statusId"]==2)?'selected':'') ?>>Closed(Not Satisfied)</option>
                                <option value="3" <?php echo (($_REQUEST["statusId"]==3)?'selected':'') ?>>Closed</option>
                                <option value="4" <?php echo (($_REQUEST["statusId"]==4)?'selected':'') ?>>Replied</option>
                            </select>                    
                        </td>                       
                    </tr>
                    <tr>
                        <td colspan="5" align="center">
                            <input type="hidden" name="_action" value="searchDeviation" />
                            <input value="Go" class="btn btn-sm btn-primary" type="submit">
                            <a href="<?php echo page_link('activitynew/deviation_view_list.php'); ?>" id="set_src_data" class="btn btn-sm btn-danger">Reset</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <hr style="padding:2px;">
    </div>


    <div class="message"></div>
    <table id="showdata" class="table table-striped table-bordered2 table-condensed table-responsive responsive-utilities jambo_table bulk_action">
        <thead>
            <tr>
                <th style="width:5%;">Sl. No.</th>
                <th style="width:10%;">Deviation Sl. No.</th>
                <th style="width:10%;">Deviation Type</th>
                <th style="width:25%;">Category</th>
                <!--<th style="width:17%;">Sub Category</th>-->
                <th style="width:30%;">Observation</th>
                
                <th style="width:10%;">Status</th>
                <th style="width:10%;"></th>
            </tr>
        </thead>
        <tbody>
             <?php 
            if(!empty($alldeviations)){
                $sl=1;
                foreach ($alldeviations as $key => $value) {
            ?>
            <tr id="dev-list-<?php echo $value->id; ?>">
                <td class="text-center"><?php echo $sl; ?></td>
                <td class="text-center"><?php echo $value->deviation_no; ?></td>
                <td class="text-center"><?php echo $value->type_name; ?></td>
                <td class="text-center"><?php echo $value->category_name; ?></td>
                <!--<td class="text-center"><?php //echo $value->subcategory_name; ?></td>-->
                <td><?php echo $value->observation; ?></td>
                <td class="text-center"><?php echo (($value->status==1)? 'Open':(($value->status==2)? 'Closed(Not Satisfied)' : (($value->status==3) ? 'Closed' : 'Replied'))); ?></td>

                <td class="text-center">
                    <input type="hidden" id="dev-id" value="<?php echo $value->id; ?>">
                    <a href="deviationview_open_no.php?deviation_id=<?php echo $value->id; ?>" class="btn btn-sm btn-info btn-xs">View Deviation</a>
                    <a href="add_audit_new_view.php?audit_id=<?php echo $value->audit_id; ?>" class="btn btn-sm btn-primary btn-xs">View Activity</a>
                                    
                </td>
            </tr> 
            <?php
                $sl++;
                }
            }else{?>

            <tr>
                <td colspan="7" style="text-align: center;"> No Data Found </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
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
                                        });
                                                function remove_upload_image_box(boxnumber) {
                                                jQuery("#upload_image_box_" + boxnumber).remove();
                                                }
    </script>

    <link rel="stylesheet" href="<?php echo url('assets/css/jqueryui/jquery-ui.css') ?>" />
    <script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-1.12.4.js') ?>"></script>
    <script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-ui.js') ?>"></script>

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
<!-- Datepicker -->
    <link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>
    <script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
    <script type="text/javascript">
        jQuery.datetimepicker.setLocale('en');
        var $z = jQuery.noConflict();
        $z(function () {
            var date = new Date();
            var currentMonth = date.getMonth();
            var currentDate = date.getDate();
            var currentYear = date.getFullYear();
            $z('.datetimepicker, .span-icon').datetimepicker({
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
    </script>
    
    
<!--data_table-->
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.dataTables.min.css"); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/buttons.dataTables.min.css"); ?>">
<script type="text/javascript" src="<?php echo url("assets/js/jquery.dataTables.min.js"); ?>"></script>

<script src="<?php echo url("assets/js/dataTables.buttons.min.js");?>"></script>
<script src="<?php echo url("assets/js/buttons.html5.min.js")?>"></script>
<script src="<?php echo url("assets/js/jszip.min.js")?>"></script>
<script>
   jQuery(document).ready(function(){

    jQuery('#showdata').DataTable({
        dom: 'lBfrtip',
        lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
        buttons: [],
        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         true,
        
        buttons: [
//                   {
//                    extend: 'print',
//                    exportOptions: {
//                        columns: ':visible',
//                    },
//             
//                },
//                 {extend: 'pdf',
//                     exportOptions: {
//                        columns: ':visible'
//                    }
//                 },
                 {extend: 'excel',
                      exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
             ],
        fixedColumns:   {
            leftColumns: 1,
            rightColumns: 1
        },
        stateSave: true
    });
 });
    
 </script>