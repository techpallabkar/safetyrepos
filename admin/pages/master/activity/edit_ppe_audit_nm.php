<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityController");
$controller->doAction();
$beanUi = $controller->beanUi;
$post_status = $beanUi->get_view_data("post_status");
$post_uploads = $beanUi->get_view_data("post_uploads");
$auth_user_id = $controller->get_auth_user("id");
$activity_id = $beanUi->get_view_data("id");
$post_category_id = $beanUi->get_view_data("post_category_id");
$status_id = $beanUi->get_view_data("status_id");
$deviation_rows = $beanUi->get_view_data("deviation");
$post_division_department = $beanUi->get_view_data("post_division_department");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$activity_type_id = $beanUi->get_view_data("post_activity_type_id");
$cat_id = $beanUi->get_view_data("cat_id");
$pcat_id = $beanUi->get_view_data("pcat_id");
$participants_list = $beanUi->get_view_data("participants_list");
$devition_names = $beanUi->get_view_data("devition_names");
$activities = get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);
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
                                <a href="edit_ppe_audit.php?action=delete_division&t=<?php echo base64_encode("ppe_audit_division_mapping"); ?>&id=<?php echo $activity_id; ?>&delid=<?php echo $key; ?>" onclick="return confirm('Are you sure want to delete this?');">
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
                                                    "action": "get_nextlevel_nm",
                                                    "id": level1,
                                                    "lcount": lc
                                                },
                                                cache: false,
                                                success: function (get_nextlevel_nm) {

                                                    if (get_nextlevel_nm)
                                                    {
                                                        division_department_treeview(lc, level1, get_nextlevel_nm, tbname = null);
                                                    }
                                                }
                                            });
                                        });

                                        /*tree function Start*/
                                        function division_department_treeview(lcount, ids, get_nextlevel_nm, tb = null) {
                                            $("#level" + lcount).html(get_nextlevel_nm);
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
                                                            "action": "get_nextlevel_nm",
                                                            "id": level_id,
                                                            "lcount": lc
                                                        },
                                                        cache: false,
                                                        success: function (get_nextlevel_nm) {

                                                            division_department_treeview(lc, level_id, get_nextlevel_nm, tb = null);

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

                                        $(".set").hide();
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
                                                                $('#division-department').show();

                                                                /***reset tree data***/
                                                                $('#L1').val('');
                                                                $('.division').val('');
                                                                $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                                            } else {
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

                                                        $('#div_dept tr[id]').each(function () {
                                                            if (duplicateChk.hasOwnProperty(this.id)) {
                                                                $(this).remove();
                                                            } else {
                                                                duplicateChk[this.id] = 'true';
                                                            }
                                                        });

                                                        /**---reset selected data---**/
                                                        $('#level2,#level3,#level4,#level5,#lebel6,#level7').empty();
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
                <div id="division_error"><?php echo $beanUi->get_error('division'); ?></div>
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
            <br />
            <div class="holder">
                <label for="audit_no">Audit Number</label>
                <input readonly="true" type="text"  name="data[audit_no]" id="audit_no" value="<?php echo $beanUi->get_view_data("audit_no"); ?>" />
                <div id="audit_no_error"><?php echo $beanUi->get_error('audit_no'); ?></div>
            </div>
            <br />

            <div class="holder required">
                <label for="date_of_audit">Date of Audit</label>
                <input type="text" name="data[date_of_audit]" class="datetimepicker" id="datetimepicker1" value="<?php echo $beanUi->get_view_data("date_of_audit"); ?>" />
                <div id="date_of_audit_error"><?php echo $beanUi->get_error('date_of_audit'); ?></div>
            </div>
            <br />
            <div style="display:none;">
            <div class="holder required">
                <label for="time_of_audit">Time of Audit</label>
                From <input style="width:10%;"  readonly="" placeholder="From" type="text" class="datetimepicker_for_time" name="data[time_of_audit_from]" id="datetimepicker2" value="<?php echo $beanUi->get_view_data("time_of_audit_from"); ?>" />
                To <input style="width:10%;" readonly="" placeholder="To" type="text" class="datetimepicker_for_time" name="data[time_of_audit_to]" id="datetimepicker22" value="<?php echo $beanUi->get_view_data("time_of_audit_to"); ?>" />
                <div id="time_of_audit_error"><?php echo $beanUi->get_error('time_of_audit'); ?></div>
            </div>
            <br />
            <div class="holder">
                <label for="audit_duration">Duration of Audit</label>
                <input type="text" readonly="" name="data[audit_duration]" id="audit_duration" value="<?php echo $beanUi->get_view_data("audit_duration"); ?>" />
                <div id="audit_duration_error"><?php echo $beanUi->get_error('audit_duration'); ?></div>
            </div>
            <br />
            </div>
            <div class="holder required">
                <label for="place">Venue</label>
                <input type="text" name="data[place]" id="place" value="<?php echo $beanUi->get_view_data("place"); ?>" />
                <div id="place_error"><?php echo $beanUi->get_error('place'); ?></div>
            </div>
            <br />
            

<?php
$pdf_links = "";
$image_links = "";
$pdf_caption = "";
$pdf_exists = 0;
// show($post_uploads);
if (!empty($post_uploads)) {
    $pdf_links .= "<div class=\"msg\"></div>\n";
    $site_root_url = dirname(url());
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
                $pdf_links .= '<a class="btn btn-danger btn-sm" href="edit_ppe_audit.php?action=delete_upload&t=' . base64_encode("file_upload_ppe_audit_mapping") . '&id=' . $purow->id . '&post_id=' . $activity_id . '" title="Delete file" onclick="return confirm(\'Confirm delete.\');">
                                                                        <i class="fa fa-trash"></i> Delete
                                                                        </a>';
            }
            $pdf_links .= '<input type="hidden" name="pdf_id" id="pdf_id" value="' . $purow->id . '" />';
        } elseif ($file_type == "image" && is_file(CESC_BASE_PATH . "/" . $purow->file_path)) {

            $image_links .= "\n<div class=\"holder\" style=\"margin-bottom:5px; border-bottom:dashed 0px #000;\">\n";
            $image_links .= "<div class=\"msg\"></div>\n";
            $image_links .= "<div id=\"myModalassa" . $purow->id . "\" class=\"modal fade\" role=\"dialog\">
                                <div class=\"modal-dialog\">
                                <!-- Modal content-->
                                <div class=\"modal-content\">
                                  <div class=\"modal-header\">
                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                                    <h4 class=\"modal-title\">" . $purow->name . "</h4>
                                  </div>
                                  <div class=\"modal-body\">
                                    <img src=\"" . $site_root_url . "/" . $purow->file_path . "\" style=\"width:100%\" />
                                  </div>
                                  <div class=\"modal-footer\">
                                    <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                  </div>
                                </div>
                                </div>
                                </div>";
            $image_links .= "<label><img data-toggle=\"modal\" data-target=\"#myModalassa" . $purow->id . "\" src=\"" . $site_root_url . "/" . $purow->file_path . "\" style=\"width:60px;\" /></label>\n";
            $image_links .= "<input type=\"text\" id=\"image_caption_" . $purow->id . "\" value=\"" . $purow->name . "\" style=\"vertical-align:top;\" />\n";
            $image_links .= "<input type=\"button\" class='btn btn-sm btn-primary' onclick=\"save_caption(" . $purow->id . ");\" value=\"Save\" style=\"vertical-align:top;\" />\n";
            if ($status_id == 1) {
                $image_links .= '<a href="edit_ppe_audit.php?action=delete_upload&t=' . base64_encode("file_upload_ppe_audit_mapping") . '&id=' . $purow->id . '&post_id=' . $activity_id . '" 
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
                $video_links .= '<a href="edit_ppe_audit.php?action=delete_upload&t=' . base64_encode("file_upload_ppe_audit_mapping") . '&id=' . $purow->id . '&post_id=' . $activity_id . '" 
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
                    <label for="image_path">Upload File</label>
                    <input type="file" name="file_path[]" id="file_path" />
                    <input type="button" id="add_upload_file" value="Add another office file" class="btn btn-sm btn-primary" />
                    <br />
                    <label for="caption">File Caption</label>
                    <input type="text" name="caption[]" id="caption"  placeholder="File Caption" value="" />                   
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php
                    echo $pdf_links;
                    ?>
                    <div id="file_path_error"><?php echo $beanUi->get_error('file_path'); ?></div>
                    <br>
<?php   echo FILESIZE_ERROR;
        echo FILE_EXTN_ALLOWED_MSG;
        ?>
                </fieldset>				
            </div>
            <div class="holder" id="extra_upload_files"></div>
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
<?php
if (!empty($image_links)) {
    echo '<hr>' . $image_links;
}
?>
                    <br>
                    <?php echo FILESIZE_ERROR; 
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
<?php
if (!empty($video_links)) {
    echo '<hr>' . $video_links;
}
?>
                    <br>
                    <?php echo FILESIZE_ERROR;
                    echo VIDEO_EXTN_ALLOWED_MSG;
                    ?>
                </fieldset>				
            </div>
            <div class="holder" id="extra_upload_video"></div>
            <br />


            <br />

            <div class="holder">
                <fieldset class="">
                    <legend>Upload Featured Image</legend>
                    <label for="image_path">Featured Image</label>
<?php
$img_path = $beanUi->get_view_data("featured_image_path");
$img = explode("?", end(explode("/", $img_path)));
$image_name = str_replace("-avatar", "", $img[0]);
$random = @$img[1];
$path = explode("?", $img_path);
$imgpath = "../../../../" . $path[0];
if ($beanUi->get_view_data("featured_image_path") != '' && file_exists($imgpath)) {
    $srcpath = "../../../../" . $beanUi->get_view_data("featured_image_path");
    $srcpath = site_url($beanUi->get_view_data("featured_image_path"));
    $actions = '<a class="btn btn-sm btn-danger" href="edit_ppe_audit.php?action=delete_featured_image&t=' . base64_encode("ppe_audit") . '&id=' . $activity_id . '"  onclick="return confirm(\'Confirm delete.\');"><i class="fa fa-trash"></i> Delete Featured Image</a>';
} else {
    $srcpath = "assets/img/ppeaudit.png";
    $srcpath = url("assets/css/cropimage/img/ppeaudit.png");
    $actions = '<button type="button" class="btn btn-primary btn-sm" data-ip-modal="#avatarModal" >Upload Photo</button>';
}
?>
                    <img src="<?php echo $srcpath; ?>" id="avatar2" width="150" style="padding:2px;border:1px solid #d0d0d0;" />
                    <?php echo $actions; ?>
                    <input type="hidden" name="featured_image_path_old" id="featured_image_path_old" value="<?php echo $beanUi->get_view_data("featured_image_path"); ?>" />
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
            <script type="text/javascript">
                $(document).ready(function () {
<?php if ($beanUi->get_view_data("investigation_req") == 'N') {
    ?>
                        $(".box2").hide();
                        $(".box3").hide();
<?php } ?>
<?php if ($beanUi->get_view_data("major_violation") == 'N') {
    ?>
                        $(".box2").hide();

<?php } ?>
                    $('.abcc input[type="radio"]').click(function () {
                        if ($(this).attr("value") == "Y") {
                            $(".investigation").show();
                            $(".box2").show();
                        }
                        if ($(this).attr("value") == "N") {
                            $(".box").not(".green").hide();
                            $(".investigation").hide();
                        }
                    });
                });
            </script>
            <div class="holder required">
                <label for="avg_mark">% of Marks</label>
                <input type="text" class="auto" data-v-max="100.99" data-m-dec="2" name="data[avg_mark]" id="avg_mark" value="<?php echo $beanUi->get_view_data("avg_mark"); ?>" />
                <div id="avg_mark_error"><?php echo $beanUi->get_error('avg_mark'); ?></div>
            </div>
            <br />

            <div class="holder abcc box3 investigation_done">
                <label for="investigation_donexx">Major Deficiency</label>
                <input type="radio" name="data[major_deviation]" id="major_deviation" value="Y" <?php
if ($beanUi->get_view_data("major_deviation") == 'Y') {
    echo "checked";
};
?> /> Yes
                <input type="radio" name="data[major_deviation]" id="major_deviation" value="N" <?php
                if ($beanUi->get_view_data("major_deviation") == 'N') {
                    echo "checked";
                };
                ?> /> No
                <div id="major_deviation_error"><?php echo $beanUi->get_error('deviation'); ?></div>
            </div>
            <br />


<?php // if($beanUi->get_view_data( "investigation_done" ) == 'Y') {     ?>
            <div class="investigation box2" style="width:100%;">


                <div class="holder">
                    <label for="synopsis" style="float:left;">Details  of Deficiency</label>
                    <table class="table table-hover table-bordered table-condensed" width="50%" style="float:left;width:70%;">

                        <tr>
                            <td >

                                <input type="text" readonly=""  id="pid" name="data[no_of_deviation]" min="1" value="<?php echo $beanUi->get_view_data("no_of_deviation"); ?>"  placeholder="No. of Deficiency"  />
                                <a class="js-open-modal btn btn-xs btn-success" href="#" data-modal-id="popup"> <i class="fa fa-plus"></i> Edit Deficiency</a> 
                                <!--participants popup-->
                                <div id="popup" class="modal-box" style="height: 50%;">
                                    <header> <a href="#" class="js-modal-close close">×</a>
                                        <h3>Details of Deficiency</h3>
                                    </header>
                                    <div class="modal-body" style="height:62%;overflow-x: hidden;overflow-y: scroll;">
                                            <!--<table id="pdetails_<?php echo $prow->id; ?>">
                                             </table>-->
                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                                var counter = <?php echo count($deviation_rows); ?> + 1;
                                                $("#addButton").click(function () {

                                                    var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'TextBoxDiv' + counter).attr("class", 'removetr cls');
                                                    newTextBoxDiv.after().html('<td align="center" >' + (counter + 1) + '</td>'
                                                            + '<td width="30%"><input class="req" style="width:100%;" type="text" id="description_' + counter + '" placeholder="Description"></td>'
                                                            + '<td width="30%">'
                                                            + '<input class="req"  style="width:100%;" type="text" id="qty_' + counter + '" placeholder="Quantity" />'
                                                            + '</td>'
                                                            + '<td width="30%"><input   style="width:100%;" type="text" id="remarks_' + counter + '" placeholder="Remarks" /></td>'
                                                            + '</td><td class="center" align="center" width="25%"><a class="btn btn-danger btn-sm rmbtnn2" id="removeButton' + counter + '" name="delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>');

                                                    function renumberRows() {
                                                        $('#TextBoxesGroup tr').each(function (index, el) {
                                                            $(this).children('td').first().text(function (i, t) {

                                                                return index++;
                                                            });
                                                        });
                                                    }
                                                    renumberRows();

                                                    $("#nof").val(counter + 1);
                                                    $(".rmbtnn").show();
                                                    newTextBoxDiv.appendTo("#TextBoxesGroup");
                                                    counter++;

                                                    $(".rmbtnn2").click(function () {
                                                        var cnd = $(this).parents("tr .removetr").siblings().length;
                                                        if (cnd >= 1) {
                                                            if ($(this).parents("tr .removetr").siblings().length && window.confirm('Delete this row ?')) {
                                                                var nofval = $('#nof').val();
                                                                $('#nof').val(nofval - 1);
                                                                counter--;
                                                                $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
                                                            }
                                                        }
                                                    });
                                                    renumberRows();
                                                    $('.complainces').change(function () {
                                                        if ($(this).val() == "Y") {
                                                            $(this).nextAll('.showdiv').show();
                                                        } else {
                                                            $(this).nextAll('.showdiv').hide();
                                                        }
                                                    });

                                                });

                                                function renumberRows() {

                                                    $('#TextBoxesGroup tr').each(function (index, el) {
                                                        $(this).children('td').first().text(function (i, t) {

                                                            return index++;
                                                        });
                                                    });
                                                }
                                                $(".rmbtnn1").click(function () {
                                                    var cnd = $(this).parents("tr .removetr").siblings().length;
                                                    if (cnd >= 1) {
                                                        if ($(this).parents("tr .removetr").siblings().length && window.confirm('Delete this row ?')) {

                                                            var nofval = $('#nof').val();
                                                            $('#nof').val(nofval - 1);
                                                            counter--;
                                                            $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
                                                        }
                                                    }
                                                });





                                                $('#action_taken_0').change(function () {
                                                    $('#compliance_0').toggle();
                                                });

                                                $(".rmbtnn").click(function () {

                                                    var action = $(this).data('action');
                                                    var id = $(this).data('acid');
                                                    $("#loaderIcon").show();
                                                    var queryString;
                                                    switch (action) {
                                                        case "p_add":
                                                            queryString = 'action=' + action + '&emp_code=' + $("#emp_code").val() + '&name=' + $("#name").val() + '&designation=' + $("#designation").val() + '&department=' + $("#department").val() + '&activity_id=' + $("#activity_id").val() + '&participant_cat_id=' + $("#participant_cat_id").val();
                                                            break;
                                                        case "p_edit":
                                                            queryString = 'action=' + action + '&message_id=' + id + '&txtmessage=' + $("#txtmessage_" + id).val();
                                                            break;
                                                        case "p_delete":
                                                            confirm("Are you sure want to delete this?");
                                                            queryString = 'action=' + action + '&message_id=' + id;
                                                            break;
                                                        case "deficiency_delete":
                                                            var cnd = $(this).parents("tr .removetr").siblings().length;
                                                            if (cnd >= 1)
                                                            {
                                                                if (confirm("Are you sure want to delete this?"))
                                                                {
                                                                    queryString = 'action=' + action + '&ppe_audit_id=' + <?php echo $activity_id; ?> + '&message_id=' + id;
                                                                    var nofval = $('#nof').val();
                                                                    $('#nof').val(nofval - 1);
                                                                    var pidval = $('#pid').val();
                                                                    $('#pid').val(pidval - 1);
                                                                    counter--;
                                                                    $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
                                                                } else
                                                                {
                                                                    return false;
                                                                }

                                                            } else
                                                            {
                                                                return false;
                                                            }
                                                    }

                                                    jQuery.ajax({
                                                        url: "crud_action.php",
                                                        data: queryString,
                                                        type: "POST",
                                                        success: function (data) {

                                                            switch (action) {
                                                                case "p_add":
                                                                    $("#comment-list-box").append(data);
                                                                    break;
                                                                case "p_edit":
                                                                    $("#message_" + id + " .message-content").html(data);
                                                                    $('#frmAdd').show();
                                                                    $("#message_" + id + " .btnEditAction").prop('disabled', '');
                                                                    break;
                                                                case "p_delete":
                                                                    $('#message_' + id).fadeOut();
                                                                    break;
                                                                case "deficiency_delete":
                                                                    $('#message_' + id).fadeOut();
                                                                    break;
                                                            }
                                                            $("#txtmessage").val('');
                                                            $("#loaderIcon").hide();
                                                        },
                                                        error: function () {}
                                                    });
                                                });



                                                /** <!--insert deficienny**/

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
                                                        var description = Array();
                                                        var qty = Array();
                                                        var remarks = Array();

                                                        var no_of_parti = $("#nof").val();

                                                        if (no_of_parti > 0)
                                                        {
                                                            for (var row_no = 0; row_no <= no_of_parti; row_no++)
                                                            {

                                                                description[row_no] = $.trim($("#description_" + row_no).val());
                                                                qty[row_no] = $.trim($("#qty_" + row_no).val());
                                                                remarks[row_no] = $.trim($("#remarks_" + row_no).val());

                                                            }

                                                            var ajax_data = {
                                                                "action": "save_deficiency_edit",
                                                                "description": description,
                                                                "qty": qty,
                                                                "remarks": remarks,
                                                                "ppe_audit_id": <?php echo $activity_id; ?>,
                                                                "nof": no_of_parti,
                                                                "token_id": token_id

                                                            };

                                                            $.ajax({
                                                                type: 'post',
                                                                cache: false,
                                                                data: ajax_data,
                                                                success: function (save_deficiency_edit) {
                                                                    if (save_deficiency_edit)
                                                                    {
                                                                        var elements = $("#TextBoxesGroup tr.cls").length;
                                                                        if (elements > 0)
                                                                        {
                                                                            //alert("yes");
                                                                            $('#TextBoxesGroup tr.cls:first').before(save_deficiency_edit);
                                                                            var idss = $('.total_count').last().val();
                                                                        } else
                                                                        {
                                                                            //alert("no");
                                                                            $('#TextBoxesGroup tr:first').append(save_deficiency_edit);
                                                                            var idss = $('.total_count2').last().val();

                                                                        }
                                                                        $("#pid").val(idss);

                                                                        function renumberRows() {
                                                                            $('#TextBoxesGroup tr').each(function (index, el) {
                                                                                $(this).children('td').first().text(function (i, t) {
                                                                                    return index++;
                                                                                });
                                                                            });
                                                                        }
                                                                        renumberRows();

                                                                        $(".ajxbtnrmv").click(function () {

                                                                            var action = $(this).data('action');
                                                                            var id = $(this).data('acid');
                                                                            $("#loaderIcon").show();
                                                                            var queryString;
                                                                            switch (action) {

                                                                                case "deficiency_delete":
                                                                                    var cnd = $(this).parents("tr .removetr").siblings().length;
                                                                                    if (cnd >= 1)
                                                                                    {
                                                                                        if (confirm("Are you sure want to delete this?"))
                                                                                        {

                                                                                            queryString = 'action=' + action + '&ppe_audit_id=' + <?php echo $beanUi->get_view_data("id"); ?> + '&message_id=' + id;
                                                                                            var nofval = $('#nof').val();
                                                                                            $('#nof').val(nofval - 1);
                                                                                            var pid = $('#pid').val();
                                                                                            $('#pid').val(nofval - 1);
                                                                                            counter--;
                                                                                            $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
                                                                                        } else
                                                                                        {
                                                                                            return false;
                                                                                        }
                                                                                    } else
                                                                                    {
                                                                                        return false;
                                                                                    }

                                                                            }

                                                                            jQuery.ajax({
                                                                                url: "crud_action.php",
                                                                                data: queryString,
                                                                                type: "POST",
                                                                                success: function (data) {
                                                                                    switch (action) {
                                                                                        case "deficiency_delete":
                                                                                            $('#message_' + id).fadeOut();
                                                                                            break;
                                                                                    }
                                                                                    $("#txtmessage").val('');
                                                                                    $("#loaderIcon").hide();
                                                                                },
                                                                                error: function () {}
                                                                            });

                                                                        });
                                                                        $('.cls').each(function () {
                                                                            $(this).remove();
                                                                        });
                                                                        $("#popup").hide();
                                                                        $(".modal-overlay").remove();
                                                                    }
                                                                }

                                                            });
                                                        } else
                                                        {
                                                            $("#popup").hide()
                                                            $(".modal-overlay").remove();
                                                            return false;
                                                        }

                                                    }
                                                    return flag;
                                                });
                                                /**insert deficiency -->**/
                                            });
                                        </script>
                                        <table id='TextBoxesGroup' class="table table-bordered">

                                            <div id="comment-list-box">
                                                <tr>
                                                    <th>Sl.No</th>
                                                    <th>Description of Deficiency</th>
                                                    <th>Quantity of Deficiency</th>
                                                    <th>Remarks</th>
<?php
if (count($deviation_rows) > 0) {
    echo '<th>Action</th>';
}
?>
                                                </tr>
<?php
if (count($deviation_rows)) {
    $f = 0;
    foreach ($deviation_rows as $row) {
        $f = $f + 1;
        ?>
                                                        <tr class="removetr" id="message_<?php echo $row->id; ?>">

                                                            <td align="center" width="10%"><?php echo $f; ?></td>
                                                            <td align="center" width="30%"><?php echo $row->description; ?></td>
                                                            <td align="center" width="30%"><?php echo $row->qty; ?></td>
                                                            <td align="center" width="30%"><?php echo $row->remarks; ?></td>
                                                            <td align="center" width="25%"><a ID="dataremove" class="btn btn-danger btn-sm rmbtnn" name="delete" class="rmbtnn" data-acid="<?php echo $row->id; ?>" data-action="deficiency_delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <tr id="TextBoxDiv0" class="removetr cls">
                                            <input type="hidden" id="activity_id" value="<?php echo $activity_id; ?>" />
                                            <td align="center"><?php echo count($deviation_rows) + 1; ?></td>
                                            <td width="30%"><input style="width:100%;" class="req"  type='text' id='description_0' placeholder='Description' ></td>
                                            <td width="30%"><input style="width:100%;" class="req"  type='text' id='qty_0' placeholder='Quantity' ></td>
                                            <td width="30%"><input style="width:100%;" type="text"  id="remarks_0" placeholder="Remarks" /></td>
                                            </td>
                                            <td align="center" width="25%"><a ID="dataremove" name="delete" class="btn btn-danger btn-sm rmbtnn1"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <footer style="margin-left:0px;"> 
                                        <input type='hidden' id="nof" name="finding_count" value="<?php echo count($deviation_rows) + 1; ?>" style="float:left;">
                                        <input type='button' value='Add More' class="btn btn-primary" id='addButton' style="float:left;">
                                        <button  id="save_pdetails" class="btn btn-small btn-primary savebtn">Save</button>
                                        <!--<a href="#" class="btn btn-small js-modal-close">Close</a> -->
                                    </footer>
                                </div>
                                <!--participants popup-->
                                </div>
                            </td>
                        </tr>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('input[type="checkbox"]').click(function () {
                                    if ($(this).attr("value") == "<?php echo $prow->id; ?>") {
                                        $(".<?php echo $prow->id; ?>").toggle();
                                        $('#pid_<?php echo $prow->id; ?>').prop('disabled', false);

                                    }

                                });

                                //called when key is pressed in textbox
                                $("#pid_<?php echo $prow->id; ?>").keypress(function (e) {
                                    //if the letter is not digit then display error and don't type anything
                                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                        //display error message
                                        //$("#errmsg_<?php echo $prow->id; ?>").html("Digits Only").show().fadeOut("slow");
                                        alert("Accept Only Numbers");
                                        return false;
                                    }
                                });
                            });
                        </script>

                    </table>
                    <div id="no_of_finding_error" class="clearfix"><?php echo $beanUi->get_error('no_of_finding'); ?></div>
                </div>
                <br>
            </div>
<?php // }     ?>
            <br>  
            <div class="holder">
                <label for="remarks">Remarks</label>
                <textarea name="data[remarks]" id="remarks"><?php echo $beanUi->get_view_data("remarks"); ?></textarea>
                <div id="remarks_error"><?php echo $beanUi->get_error('remarks'); ?></div>
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
        } else if ($role_id == 1 && $status_id == 5 && in_array($statusrow->status_name, $admin_status)) {

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

                <a href="index.php?status_id=<?php echo $_REQUEST["status_id"]; ?>&districtid=<?php echo $_REQUEST["districtid"]; ?>&activity_no=<?php echo $_REQUEST["activity_no"]; ?>&search_title=<?php echo $_REQUEST["search_title"]; ?>&fromdate=<?php echo $_REQUEST["fromdate"]; ?>&todate=<?php echo $_REQUEST["todate"]; ?>&page=<?php echo $_REQUEST["page"]; ?>&activity_id=<?php echo $activity_type_id; ?>" class="btn btn-sm btn-danger">Cancel</a>
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
        var $z=jQuery.noConflict();
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
            if( $currentDate >= CHECK_DATE && ($role_id != 1 && $role_id != 3)) {
            ?>
            minDate: new Date(currentYear, currentMonth, currentDate-(currentDate-1)),
            <?php }  if( $currentDate < CHECK_DATE && ($role_id != 1 && $role_id != 3)) { ?>
    minDate: new Date(currentYear, (currentMonth-1), currentDate-(currentDate-1)),
         
    <?php } ?>
            step: 5
        });
    });                                       
//                            
//                            jQuery('.datetimepicker').datetimepicker({
//                                timepicker: false,
//                                scrollMonth: false,
//                                scrollInput: false,
//                                format: 'Y-m-d',
//                                step: 5
//                            });
//                            jQuery('.datetimepicker_for_time').datetimepicker({
//                                datepicker: false,
//                                scrollMonth: false,
//                                scrollInput: false,
//                                format: 'H:i',
//                                step: 10
//                            });
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
                    text2 += '<input style="width:80%;" type="text" name="emp_code" id="emp_code" placeholder="Employee Code" />';
                }
                text2 += '<button id="btnAddAction" name="submit" onClick="callCrudAction("p_add","")">Add</button>';

                text2 += '</div>';
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
                left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                        // left: "10%"
            });
        });

        $m(window).resize();

    });
    jQuery(document).ready(function ($) {
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
                $s('#avatar3').attr('value', '');
                $s('#avatar4').attr('value', '');
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
        $s('.navbar-toggle').on('click', function () {
            $('.navbar-nav').toggleClass('navbar-collapse')
        });
        $s(window).resize(function (e) {
            if ($(document).width() >= 430)
                $('.navbar-nav').removeClass('navbar-collapse')
        });

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
        $("#add_upload_file").click(function () {
            var boxnumber = 1 + Math.floor(Math.random() * 6);
            var another_image_upload_html =
                    '<div class="holder" id="upload_image_box_' + boxnumber + '">' + "\n" +
                    '<fieldset>' + "\n" +
                    '<legend>Upload Office Files</legend>' + "\n" +
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
                    '<input type="button" class="btn btn-sm btn-danger" value="Remove image" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
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
                    '<input type="button" class="btn btn-sm btn-danger" value="Remove video" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
                    '<br />' +
                    '<label for="caption">Video Caption</label>' + "\n" +
                    '<input type="text" name="video_captions[]" id="video_captions"  placeholder="Video Caption" />' + "\n" +
                    '</fieldset>' + "\n" +
                    '</div>' + "\n";
            $("#extra_upload_video").append(another_image_upload_html);
        });
        $("#edit_post").submit(function () {
            var place = jQuery.trim(jQuery("#place").val());
            var avg_mark = jQuery.trim(jQuery("#avg_mark").val());
            var division = jQuery.trim(jQuery("#division").val());
            var set_type3 = jQuery.trim(jQuery("#set_type3").val());
            var error_counter = 0;
            jQuery(".error").empty();
            <?php if (count($devition_names) == 0) { ?>
            if (division == undefined || division == "")
            {
                jQuery("#division_error").html("<div class=\"error\">Division is required.</div>");
                error_counter++;
            }
            <?php } ?>
            if (set_type3 == undefined || set_type3 == "") {
                jQuery("#set_type_error").html("<div class=\"error\">Set type is required.</div>");
                error_counter++;
            }
            if (place == undefined || place == "") {
                jQuery("#place_error").html("<div class=\"error\">Venue is required.</div>");
                error_counter++;
            }           
            if (avg_mark == undefined || avg_mark == "") {
                jQuery("#avg_mark_error").html("<div class=\"error\">Average mark is required.</div>");
                error_counter++;
            }
//            if( featured_image != undefined && featured_image == "default-avatar.png" ) {
//                jQuery("#featured_image_error").html("<div class=\"errors\">Featured Image is required.</div>");
//                error_counter++;
//            }
            if (error_counter > 0) {
                jQuery(".message").html("<div class=\"error\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                jQuery('html, body').animate({scrollTop: 0}, 'slow');
                return false;
            }
        });
    });

    function save_caption(upload_id) {
        if (upload_id > 0) {
            var caption = jQuery("#image_caption_" + upload_id).val();
            jQuery("#image_caption_" + upload_id).parent().find(".message").html("");
            jQuery.ajax({
                data: {
                    "action": "save_caption",
                    "upload_id": upload_id,
                    "table_name": "file_upload_ppe_audit_mapping",
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
            type: 'post', url: '<?php echo page_link("activity/edit_ppe_audit.php"); ?>', cache: false,
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
