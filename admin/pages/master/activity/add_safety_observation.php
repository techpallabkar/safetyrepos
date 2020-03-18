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
$activity_id = $beanUi->get_view_data("activity_id");
$activities = get_value_by_id($activity_id, "activity_name", $post_activity_type_master);
$l1_cat_name = "";
$tag_keys = $beanUi->get_view_data("tag_keys");
$controller->setCss(array("cropimage/css/imgpicker"));
$controller->setCss("tree");
$controller->get_header();
$token_id = rand(000000, 111111).time();
$currentmonth = date("m",strtotime(CHECK_DATE));
$currentyr = date("Y",strtotime(CHECK_DATE));
$currentDate = date('Y-m-d');
$role_id = $controller->get_auth_user("role_id");
?>
<style type="text/css">
    ul,li { margin:0; padding:0; list-style:none;}
    .box{display: none;}
    .box_faculty {display: none;}

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
                                            if ($rowvalue->parent_id == 1 && $rowvalue->id == 249 ) {
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

                                    $(document).ready(function () {
                                        $("#reset_button").click(function () {
                                            $('#L1').val('');
                                            $('.division').val('');
                                            $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                        });

                                        $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                        var lcount = 1;
                                        $("#L" + lcount).on('change', function () {
                                            $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                            var level1 = $(this).val();
                                            var lc = lcount + 1;
                                            $.ajax({
                                                type: 'post',
                                                data: {
                                                    "action": "get_nextlevel",
                                                    "id": level1,
                                                    "lcount": lc
                                                },
                                                cache: false,
                                                success: function (get_nextlevel) {

                                                    if (get_nextlevel)
                                                    {
                                                        division_department_treeview(lc, level1, get_nextlevel, tbname = null);
                                                    }
                                                }
                                            });
                                        });

                                        /*tree function Start*/
                                        function division_department_treeview(lcount, ids, get_nextlevel, tb = null) {
                                            $("#level" + lcount).html(get_nextlevel);
                                            $("#level" + lcount).css("margin-top", "20px");
                                            $("#level" + lcount).show();
                                            $("#L4").find("option[value='298'],[value='299'],[value='300']").remove(); /** hide contractor's for activities**/
                                            $("#L" + lcount).on('change', function () {
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
                                                                $(".levelfour").show();
                                                                $(".levelfour").html(get_contractor_list);
                                                            }
                                                        }
                                                    });
                                                } else
                                                {
                                                    $.ajax({
                                                        type: 'post',
                                                        data: {
                                                            "action": "get_nextlevel",
                                                            "id": level_id,
                                                            "lcount": lc
                                                        },
                                                        cache: false,
                                                        success: function (get_nextlevel) {

                                                            division_department_treeview(lc, level_id, get_nextlevel, tb = null);

                                                            $(".newcons").on('change', function () {
                                                                var sdd = $(this).find(':selected').data("other");
                                                                var cc = $(this).find(':selected').data("c");
                                                                if (sdd != 0)
                                                                {
                                                                    $('#' + sdd).html('<span style="float:left;width:150px;padding-top: 6px;"><b>Other Name</b></span>'
                                                                            + '<input class="division" name=""  id="new_' + sdd + '" type="text" value="" style="width:23%;" />');
                                                                } else
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
                                        $('#btnSave').click(function () {
                                            $("#division-department").hide();
                                            var closestids = $('#closestid').val();
                                            var level = $("#L1").find("option:selected").val();
                                            var level_2 = $("#L2").find("option:selected").val();
                                            var last_level = $(".newcons").val();
                                            $('#' + closestids).hide();
                                            
                                            /*L2 validation start*/   
                                                if(level_2 == ""){
                                                    alert(" Division is required.  ");
                                                    $('#division-department').show();
                                                    return false;
                                                }
                                            /*L2 validation end*/
                                            
                                            var error_count = 0;
                                            jQuery(".errors").empty();


                                            var contractorType = 0;
                                            $('.contractor_and_type :selected').each(function (j, selected) {
                                                if ($(this).val() != "") {
                                                    contractorType++;
                                                }
                                            });
                                            /*$('.newcons :selected').each(function (j, selected) {
                                                var errormsg = $(this).parent().parent().find("label").html();

                                                if (!isNaN(this.value)) {
                                                    if ((typeof contractorType !== 'undefined' && contractorType == 1)) {
                                                        $("#level_error").html("<div class=\"errors\">" + errormsg + " is required.</div>");
                                                        error_count++;
                                                        return false;
                                                    }
                                                }
                                            });*/

                                            if (error_count > 0) {
                                                jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                                                jQuery('html, body').animate({scrollTop: 0}, 'slow');
                                                return false;
                                            }


                                            /* $("select").prop("required",true);
                                             alert("dfdf");
                                             return false;*/

                                            var foo_value = [];
                                            var foo_text = [];
                                            $('.division :selected').each(function (i, selected) {

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
                                                    "action": "submit_division",
                                                    "name": foo_text,
                                                    "tree_dept": "department",
                                                    "ids": foo_value
                                                },
                                                cache: false,
                                                success: function (submit_division) {
                                                    if (submit_division)
                                                    {
                                                        var getIdMod = submit_division.substr(9, 50);
                                                        var getId = getIdMod.substr(0, getIdMod.indexOf('">'));
                                                        var gtid = replaceAll(getId, ":", "_");

                                                        $('#div_dept').append(submit_division + '<td><a id="sp' + gtid + '"  class="delete_row" style="cursor:pointer;" >Delete</a></td></tr>');
                                                        $('#sp' + gtid).on('click', function () {
                                                            var conf = confirm("Are you sure to delete this record");
                                                            if (conf) {

                                                                if ($(".set3").val())
                                                                {
                                                                    $(".set3 > [value=" + $(".set3").val() + "]").removeAttr("selected");
                                                                }
                                                                $(".set").hide();
                                                                $(this).parents("tr").remove();
                                                                $("#division-department").show();

                                                                /***reset tree data***/
                                                                $('#L1').val('');
                                                                $('.division').val('');
                                                                $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                                            } else {
                                                                return false;
                                                            }

                                                        });
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
                                                                        <option value="C-SET">C-SET</option>\n\
                                                                        <option value="PC-SET">(P+C)-SET</option><option value="OTHERS">OTHERS</option>');
                                                        }
                                                        $(".set3 > [value=" + temp + "]").attr("selected", "true");
                                                        /*get set type end*/

                                                        /**---check duplicate value---**/
                                                        var duplicateChk = {};

                                                        $('#div_dept tr[id]').each(function () {
                                                            if (duplicateChk.hasOwnProperty(this.id)) {
                                                                $(this).remove();
                                                            } else {
                                                                duplicateChk[this.id] = 'true';
                                                            }
                                                        });

                                                        /**---reset selected data---**/
                                                        $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').empty();
                                                        $('#L1 option').prop('selected', function () {
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

            <br />
            <div class="holder">
                <label for="activity_no">Activity Number</label>
                <input type="text"  name="data[activity_no]" id="activity_no" value="<?php echo $beanUi->get_view_data("activity_no"); ?>" />
                <div id="activity_no_error"><?php echo $beanUi->get_error('activity_no'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="activity_month">Activity Month</label>


                <select readonly="" name="data[activity_month]" id="activity_month">
                    <option value="">Select Month</option>
                    <?php
                   
                    for ($m = 1; $m <= 12; $m++) {
                        $month = date('F', mktime(0, 0, 0, $m, 1, date('Y')));

                        
                        if( ($currentDate >= CHECK_DATE) && ($role_id != 1 && $role_id != 3)) {                            
                            if($currentmonth ==$m) {
                                echo '<option value="' . $m . '" test>' . $month . '</option>';
                            }
                            
                        } else {
                            echo '<option value="' . $m . '">' . $month . '</option>';
                        }
                        
                    }
                    ?>
                </select>
                <div id="activity_month_error"><?php echo $beanUi->get_error('activity_month'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="activity_year">Activity Year</label>
                <?php
                
                $yearArray = range(2015, $currentyr);
                
                ?>

                <select readonly="" name="data[activity_year]" id="activity_year">
                    <option value="">Select Year</option>
                    <?php
                    foreach ($yearArray as $year) {

                        $selected = ($year == 'Select Year') ? 'selected' : '';
                        if( ($currentDate >= CHECK_DATE) && ($role_id != 1 && $role_id != 3)) {                            
                            if($currentyr ==$year) {
                              echo '<option ' . $selected . ' value="' . $year . '">' . $year . '</option>';
                          } 
                        } else {
                           echo '<option ' . $selected . ' value="' . $year . '">' . $year . '</option>';
                        }
                        
                    }
                    ?>
                </select>
                <div id="activity_year_error"><?php echo $beanUi->get_error('activity_year'); ?></div>
            </div>


            <br />
            <div class="holder required">
                <label for="activity_count">Number of Activities</label>
                <input type="text" name="data[activity_count]" id="activity_count"  value="<?php echo $beanUi->get_view_data("activity_count"); ?>" />
                <div id="activity_count_error"><?php echo $beanUi->get_error('activity_count'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="place">Venue</label>
                <input type="text" name="data[place]" id="place" value="<?php echo $beanUi->get_view_data("place"); ?>" />
                <div id="place_error"><?php echo $beanUi->get_error('place'); ?></div>
            </div>
            <br />

            <div class="holder">
                <label for="status_id">Status</label>
                <select name="data[status_id]" id="status_id">
                    <?php
                    $created_by = $beanUi->get_view_data("created_by");
                    
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
                <!--<input type="button" id="cancel" value="Cancel" class="btn btn-sm btn-danger" />-->
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

                                    jQuery('.datetimepicker_month').datetimepicker({

                                        timepicker: false,
                                        scrollMonth: false,
                                        scrollInput: false,
                                        format: 'm',
                                        step: 5

                                    });
                                    jQuery('.datetimepicker_year').datetimepicker({

                                        datepicker: false,
                                        scrollMonth: false,
                                        scrollInput: false,
                                        format: 'Y',
                                        step: 5
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

                                    jQuery("#datetimepicker2").change(function () {

                                    });
                                    jQuery("#datetimepicker22").change(function () {
                                        var reportingTime = jQuery("#datetimepicker2").val();
                                        var releasingTime = jQuery("#datetimepicker22").val();
                                        if (releasingTime <= reportingTime) {
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


                                    var $m = jQuery.noConflict();
                                    $m(function () {

                                        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

                                        $m('a[data-modal-id]').click(function (e) {
                                            var row_id = $(this).attr("data-modal-id").replace("popup", "");
                                            //alert($("#pid_"+row_id).val());
                                            var pid_value = $("#pid_" + row_id).val();

                                            if (pid_value == 0 || pid_value == '' || pid_value == undefined)
                                            {
                                                alert("No. of participants should not be blank or 0.");
                                                return false;
                                            } else
                                            {


                                                var text2 = '';
                                                for (var i = 0; i < pid_value; i++)
                                                {
                                                    text2 += '<tr>';
                                                    if (row_id != 4 && row_id != 2)
                                                    {
                                                        // alert(row_id);
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
                                                //$(".js-modalbox").fadeIn(500);
                                                var modalBox = $(this).attr('data-modal-id');
                                                $m('#' + modalBox).fadeIn($(this).data());
                                            }
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
                                                // left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                                                left: "10%"
                                            });
                                        });

                                        $m(window).resize();

                                    });
</script>
<!-- Popup -->
<script type="text/javascript">
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
                $s('#avatar2').attr('src', 'assets/img/default-avatar.png');
                this.modal('hide');
            },
            uploadSuccess: function (image) {
                // Calculate the default selection for the cropper
                /*	var select = (image.width > image.height) ? 
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




        $('button').on('click', function (e) {

            e.preventDefault();
            var button_id = $(this).attr("id");
            //save_pdetails_3
            if (button_id)
            {
                var form_id = button_id.replace("save_pdetails_", "");
            } else
            {
                var form_id = "B1".replace("save_pdetails_", "");
            }
            if (form_id != undefined && form_id > 0)
            {
                var emp_codes = Array();
                var token_id = $("#token_id").val();
                var emp_name = Array();
                var designation = Array();
                var department = Array();
                var no_of_parti = $("#pid_" + form_id).val();

                if (no_of_parti > 0)
                {
                    for (var row_no = 0; row_no < no_of_parti; row_no++)
                    {
                        emp_codes[row_no] = $.trim($("#emp_code_" + form_id + "_" + row_no).val());
                        emp_name[row_no] = $.trim($("#name_" + form_id + "_" + row_no).val());
                        designation[row_no] = $.trim($("#designation_" + form_id + "_" + row_no).val());
                        department[row_no] = $.trim($("#department_" + form_id + "_" + row_no).val());
                    }
                    var ajax_data = {
                        "action": "save_participants",
                        "emp_codes": emp_codes,
                        "emp_name": emp_name,
                        "designation": designation,
                        "department": department,
                        "token_id": token_id,
                        "participant_cat_id": form_id
                    };
                    $.ajax({
                        type: 'post', url: '<?php echo page_link("activity/create.php"); ?>', cache: false,
                        data: ajax_data,
                        success: function (save_participants) {
                            //alert(save_participants);
                            if (save_participants)
                            {
                                //this.modal('hide'); 
                                $("#popup" + form_id).hide()
                                $(".modal-overlay").remove();
                                // return true;
                            }
                        }
                    });
                }
            }


        });

      
       
        
        $("#activity_count").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                alert("Accept Only Numbers,Space and character is not allowed.");
                return false;
            }
        });


        $("#create_post").submit(function () {
            
            
            var activity_month = jQuery.trim(jQuery("#activity_month").val());
            var activity_year = jQuery.trim(jQuery("#activity_year").val());
            var activity_count = jQuery.trim(jQuery("#activity_count").val());
            var division = jQuery.trim(jQuery("#division").val());
            var set_type3 = jQuery.trim(jQuery("#set_type3").val());
            var place = jQuery.trim(jQuery("#place").val());
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
            if (activity_month == undefined || activity_month == "")
            {
                jQuery("#activity_month_error").html("<div class=\"error\">Activity month is required.</div>");
                error_counter++;
            }
            if (activity_year == undefined || activity_year == "")
            {
                jQuery("#activity_year_error").html("<div class=\"error\">Activity year is required.</div>");
                error_counter++;
            }
            if (activity_count == undefined || activity_count == "")
            {
                jQuery("#activity_count_error").html("<div class=\"error\">Activity count is required.</div>");
                error_counter++;
            }
            if (place == undefined || place == "")
            {
                jQuery("#place_error").html("<div class=\"error\">Venue is required.</div>");
                error_counter++;
            }
            if( error_counter == 0 ) {
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

<script>
  $(function () {
      var ajax_data = {
          "action": "getVenueList",
          "activity_type_id" : "<?php echo $activity_id; ?>"
      };
      $.ajax({
          type: 'post', url: '<?php echo page_link("activity/add_safety_observation.php"); ?>', cache: false,
          data: ajax_data,
          success: function (data) { //alert(data);
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
</script>

</body>
</html>
