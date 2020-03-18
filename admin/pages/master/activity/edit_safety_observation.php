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
$activity_id = $beanUi->get_view_data("id");
$post_category_id = $beanUi->get_view_data("post_category_id");
$status_id = $beanUi->get_view_data("status_id");

$post_participants_categories = $beanUi->get_view_data("post_participants_categories");
$post_division_department = $beanUi->get_view_data("post_division_department");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$activity_type_id = $beanUi->get_view_data("post_activity_type_id");
$cat_id = $beanUi->get_view_data("cat_id");
$pcat_id = $beanUi->get_view_data("pcat_id");
$participants_list = $beanUi->get_view_data("participants_list");

$post_division_department_mapping = $beanUi->get_view_data("post_division_department_mapping");
$devition_names = $beanUi->get_view_data("devition_names");
$activities = get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);
$controller->setCss(array("cropimage/css/imgpicker"));
$controller->setCss("tree");
$controller->get_header();
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
    <h1 class="heading">Update Activity : <?php echo $activities; ?></h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div>
<?php echo $beanUi->get_error("status_id"); ?>
    <div class="panel" style="padding:20px;">
        <form name="edit_post" id="edit_post" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="data[activity_type_id]" value="<?php echo $activity_type_id; ?>" />
            <div class="holder">
                <label for="title">Activity</label>
                <b class="text-danger"> <?php echo $activities; ?> </b>                       
            </div>
            <br />
            <div class="holder required" >
                <label for="synopsis" style="float:left;">Division – Department</label>
                <table id="div_dept" class="table table-hover table-bordered table-condensed" width="50%" style="float:left;width:70%;">
                    <?php
                    if (count($devition_names) > 0) {
                        if (!empty($devition_names)) {
                            $j = 0;
                            $valxx = array();
                            echo '<tr><td colspan="2"><b class="text-danger">Selected Division - Departments : </b><br>';
                            foreach ($devition_names as $key => $ddmrow) {
                                $j = $j + 1;
                                echo $j . '. <b>' . $ddmrow . '</b>&nbsp;&nbsp;';
                                ?>
                                <a href="edit_safety_observation.php?action=delete_division&t=<?php echo base64_encode("safety_observation_division_mapping"); ?>&id=<?php echo $activity_id; ?>&delid=<?php echo $key; ?>" onclick="return confirm('Are you sure want to delete this?');">
                                    <img width="8px" src="<?php echo url("assets/images/delete.gif"); ?>" />
                                </a>
                                <br>
                                <?php
                            }
                            echo "</td></tr>";
                        }
                    }
                    ?>
                    <?php if (count($devition_names) == 0) { ?>
                        <tr id="division-department">
                            <td colspan="2">

                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"> <i class="fa fa-plus"></i> Add Division Department</button>

                            </td>
                        </tr>
<?php } ?>
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
                                                                // alert($(this).find(':selected').data("location"));
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
                                        $('.set').hide();
                                        /*tree Save Start*/
                                        $('#btnSave').click(function () {
                                            $('#division-department').hide();



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
                                                        $('.levelfour,#level2,#level3,#level4,#level5,#lebel6,#level7').empty();
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
                 <div class="clearfix"></div>
                <div id="division_error" class="clearfix"><?php echo $beanUi->get_error('division'); ?></div>
            </div>
            <div class="holder" id="extra_division"></div>
<?php
if (count($devition_names) > 0) {

    foreach ($devition_names AS $devrow) {

        echo '<div class="holder required"><label for="incident_no">Set Type</label>
                <select name="data[set_type]" id="set_type3" class="set3">';
                if ($beanUi->get_view_data("set_type") == "P-SET") {
                    echo '<option value="P-SET" ' . ($beanUi->get_view_data("set_type") == "P-SET" ? "selected" : "") . '>P-SET</option>';
                } else if ($beanUi->get_view_data("set_type") == "C-SET") {
                    echo '<option value="C-SET" ' . ($beanUi->get_view_data("set_type") == "C-SET" ? "selected" : "") . '>C-SET</option>';
                } else {
                    echo '<option value="">select one</option><option value="P-SET" ' . ($beanUi->get_view_data("set_type") == "P-SET" ? "selected" : "") . '>P-SET</option>'
                    . '<option value="C-SET" ' . ($beanUi->get_view_data("set_type") == "C-SET" ? "selected" : "") . '>C-SET</option>'
                    . '<option value="PC-SET" ' . ($beanUi->get_view_data("set_type") == "PC-SET" ? "selected" : "") . '>(P+C)-SET</option>'
                    . '<option value="OTHERS" ' . ($beanUi->get_view_data("set_type") == "OTHERS" ? "selected" : "") . '>OTHERS</option>';
                }
                echo '</select><div id="set_type_error">' . $beanUi->get_error('set_type') . '</div>  </div>';
            

    }
} else {
    echo '<div class="holder required set"><label for="incident_no">Set Type</label>                                                     
    <select name="data[set_type]" id="set_type3" class="set3">
    <option value="">select one</option>
    <option value="P-SET">P-SET</option>
    <option value="C-SET">C-SET</option>
    <option value="PC-SET">(P+C)-SET</option>
     <option value="OTHERS">OTHERS</option>
    </select>
    <div id="set_type_error">' . $beanUi->get_error('set_type') . '</div>    
    </div>';
}
?>
            <br>
            <div class="holder">
                <label for="audit_no">Activity Number</label>
                <input type="text"  name="data[activity_no]" id="activity_no" value="<?php echo $beanUi->get_view_data("activity_no"); ?>" />
                <div id="activity_no_error"><?php echo $beanUi->get_error('activity_no'); ?></div>
            </div>
            <br />

            <div class="holder required">
                <label for="activity_month">Activity Month</label>

                <select readonly="" name="data[activity_month]" id="activity_month">
                    <option value="" disabled="">Select Month</option>
<?php
for ($m = 1; $m <= 12; $m++) {
    $month = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
    $selected = ($m == $beanUi->get_view_data("activity_month")) ? 'selected' : '';
                        if( ($currentDate >= CHECK_DATE) && ($role_id != 1 && $role_id != 3)) {                            
                            if($currentmonth ==$m) {
                                echo '<option ' . $selected . ' value="' . $m . '">' . $month . '</option>';
                            }
                            
                        } else {
                            echo '<option ' . $selected . ' value="' . $m . '">' . $month . '</option>';
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

                <select readonly="" name="data[activity_year]" id="activity_year" >
                    <option value="">Select Year</option>
                <?php
                foreach ($yearArray as $year) {

                    $selected = ($year == $beanUi->get_view_data("activity_year")) ? 'selected' : '';
                        if( ($currentDate >= CHECK_DATE) && ($role_id != 1 && $role_id != 3)) {                            
                            if($currentyr ==$year) {
                              echo '<option ' . $selected . ' value="' . $year . '">' . $year . '</option>';
                          } 
                        } else {
                           echo '<option ' . $selected . ' value="' . $year . '">' . $year . '</option>';
                        }
                   // echo '<option ' . $selected . ' value="' . $year . '">' . $year . '</option>';
                }
                ?>
                </select>
                <div id="activity_year_error"><?php echo $beanUi->get_error('activity_year'); ?></div>
            </div><br>
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
                    $normaluser = array("Draft", "Final Submit");
                    $adminapprove = array("Final Submit", "Approve & Publish","Approve & Unpublish", "Refferred for Correction");
                    $admin_status = array("Approve & Publish", "Approve & Unpublish");
                    $published_status = array("Approve & Publish","Approve & Unpublish");
                    $status_id = $beanUi->get_view_data("status_id");

                    foreach ($post_status as $statusrow) {
                        if ($status_id == $statusrow->id) {
                            $selected = "selected";
                        } else {
                            $selected = "";
                        }

                        if ($role_id == 1 && $status_id == 1 && in_array($statusrow->status_name, $normaluser)) {

                            echo '<option value="' . $statusrow->id . '" ' . $selected . ' >' . $statusrow->status_name . '</option>' . "\n";
                        } else if ($role_id == 1 && $status_id == 2 && in_array($statusrow->status_name, $adminapprove)) {

                            echo '<option value="' . $statusrow->id . '" ' . $selected . ' >' . $statusrow->status_name . '</option>' . "\n";
                        } 
                        else if ($role_id == 1 && $status_id == 5 && in_array($statusrow->status_name, $admin_status)) {

                            echo '<option value="' . $statusrow->id . '" ' . $selected . '>' . $statusrow->status_name . '</option>' . "\n";
                        }
                        if ($role_id == 3 && $status_id == 1 && in_array($statusrow->status_name, $normaluser)) {

                            echo '<option value="' . $statusrow->id . '" ' . $selected . ' >' . $statusrow->status_name . '</option>' . "\n";
                        } else if ($role_id == 3 && $status_id == 2 && in_array($statusrow->status_name, $adminapprove)) {

                            echo '<option value="' . $statusrow->id . '" ' . $selected . ' >' . $statusrow->status_name . '</option>' . "\n";
                        } else if ($role_id == 3 && $status_id == 3 && in_array($statusrow->status_name, $published_status)) {

                            echo '<option value="' . $statusrow->id . '" ' . $selected . ' >' . $statusrow->status_name . '</option>' . "\n";
                        } else if ($role_id == 3 && $status_id == 4 && in_array($statusrow->status_name, $published_status)) {

                            echo '<option value="' . $statusrow->id . '" ' . $selected . ' >' . $statusrow->status_name . '</option>' . "\n";
                        } else if ($role_id == 3 && $status_id == 5 && in_array($statusrow->status_name, $admin_status)) {

                            echo '<option value="' . $statusrow->id . '" ' . $selected . '>' . $statusrow->status_name . '</option>' . "\n";
                        } else if ($role_id == 2 && in_array($statusrow->status_name, $normaluser)) {

                            echo '<option value="' . $statusrow->id . '" ' . $selected . '>' . $statusrow->status_name . '</option>' . "\n";
                        } else if ($role_id == 1 && ($status_id == 3 OR $status_id == 4) && in_array($statusrow->status_name, $published_status)) {
                            echo '<option value="' . $statusrow->id . '" ' . $selected . '>' . $statusrow->status_name . '</option>' . "\n";
                        }
                    }
                }
                ?>
                </select>
                    <?php echo $beanUi->get_error('status_id'); ?>
            </div>
            <br />
<!--             <div class="holder correction">
                <label for="reason"> Reason ( Referred for Correction ) </label>
                <textarea name="data[place]" id="place" ></textarea>                
            </div>
            <br />-->
            <div class="holder">
                <label for="author">Created by</label><?php echo $beanUi->get_view_data('created_by_name'); ?>
            </div>
            <br />
            <div class="holder">
                <label for="author">Modified Date</label><?php echo $beanUi->get_view_data('modified_date'); ?>
            </div>
            <br />
            <div class="holder">
                <input type="submit" value="Update" class="btn btn-smbtn btn-sm btn-primary" />
                <a href="index.php?status_id=<?php echo $_REQUEST["status_id"]; ?>&districtid=<?php echo $_REQUEST["districtid"]; ?>&search_title=<?php echo $_REQUEST["search_title"]; ?>&activity_month=<?php echo $_REQUEST["activity_month"]; ?>&activity_year=<?php echo $_REQUEST["activity_year"]; ?>&activity_no=<?php echo $_REQUEST["activity_no"]; ?>&page=<?php echo $_REQUEST["page"]; ?>&activity_id=<?php echo $activity_type_id; ?>" class="btn btn-sm btn-danger">Cancel</a>
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

<div id="tracking_post_detail"></div>
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>
<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
<script type="text/javascript">

    jQuery.datetimepicker.setLocale('en');
    jQuery('.datetimepicker_month').datetimepicker({
        timepicker: false,
        scrollMonth: false,
        scrollInput: false,
        format: 'm',
        step: 5
    });
    jQuery('.datetimepicker_year').datetimepicker({

        timepicker: false,
        scrollMonth: false,
        scrollInput: false,
        format: 'Y',
        step: 5
    });
    jQuery(document).ready(function ($) {        
        
        $("#activity_count").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                alert("Accept Only Numbers,Space and character is not allowed.");
                return false;
            }
        });	

        $("#edit_post").submit(function () {
            var activity_month = jQuery.trim(jQuery("#activity_month").val());
            var activity_year = jQuery.trim(jQuery("#activity_year").val());
            var activity_count = jQuery.trim(jQuery("#activity_count").val());
            var division = jQuery.trim(jQuery("#division").val());
            var set_type3 = jQuery.trim(jQuery("#set_type3").val());
            var place = jQuery.trim(jQuery("#place").val());
            var error_counter = 0;
            jQuery(".error").empty();
            <?php if (count($devition_names) == 0) { ?>
            if (division == undefined || division == "")
            {
                jQuery("#division_error").html("<div class=\"error\">Division is required.</div>");
                error_counter++;
            }
            <?php } ?>
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
            if (error_counter > 0) {
                jQuery(".message").html("<div class=\"error\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                jQuery('html, body').animate({scrollTop: 0}, 'slow');
                return false;
            }
        });
    });
</script> 
<link rel="stylesheet" href="<?php echo url('assets/css/jqueryui/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-1.12.4.js') ?>"></script>
<script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-ui.js') ?>"></script>

<script type="text/javascript">
    $(function () {
        var ajax_data = {
            "action": "getVenueList",
            "activity_type_id": "<?php echo $activity_type_id; ?>"
        };
        $.ajax({
            type: 'post', url: '<?php echo page_link("activity/edit_safety_observation.php"); ?>', cache: false,
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
</script>
</body>
</html>
