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
$violation_category = $beanUi->get_view_data("violation_category");
$incident_category = $beanUi->get_view_data("incident_category");
$nature_of_injury = $beanUi->get_view_data("nature_of_injury");
$body_part_injury = $beanUi->get_view_data("body_part_injury");
$childData = $beanUi->get_view_data("childData");
$body_part_injury_mappingData = $beanUi->get_view_data("body_part_injury_mappingData");
$injury_status = $beanUi->get_view_data("injury_status");

$post_participants_categories = $beanUi->get_view_data("post_participants_categories");
$post_division_department = $beanUi->get_view_data("post_division_department");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$activity_type_id = $beanUi->get_view_data("post_activity_type_id");
$cat_id = $beanUi->get_view_data("cat_id");
$pcat_id = $beanUi->get_view_data("pcat_id");
$participants_list = $beanUi->get_view_data("participants_list");
$finding_rows = $beanUi->get_view_data("finding_rows");
$devition_names = $beanUi->get_view_data("devition_names");
$post_division_department_mapping = $beanUi->get_view_data("post_division_department_mapping");
$voilation_category = $beanUi->get_view_data("voilation_category");
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
    .box4 { display: none; }
    .box5 { display: none; }
</style>

<div class="container1">
    <h1 class="heading">Update Incident : <?php echo $activities; ?></h1>
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
                                <a href="edit_incident.php?action=delete_division&t=<?php echo base64_encode("incident_division_mapping"); ?>&id=<?php echo $activity_id; ?>&delid=<?php echo $key; ?>" onclick="return confirm('Are you sure want to delete this?');">
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
                        $(".division").val('');
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


                                    /**get set type start*/
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
                <div class="clearfix"></div>
                <div id="division_error"><?php echo $beanUi->get_error('division_error'); ?></div>
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
                <label for="incident_no">Incident Number</label>
                <input type="text"  name="data[incident_no]" id="incident_no" value="<?php echo $beanUi->get_view_data("incident_no"); ?>" />
                <div id="incident_no_error"><?php echo $beanUi->get_error('incident_no'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="incident_type_id">Incident Type</label>
                <select  name="incident_type_id" id="incident_type_id">
                <option value="">--Select One--</option>
                <option value="1" <?php echo (($beanUi->get_view_data("incident_type_id") == 1) ? "selected" : "");?>>Electrical</option>
                <option value="2" <?php echo (($beanUi->get_view_data("incident_type_id") == 2) ? "selected" : "");?>>Mechanical</option>
                <!--<option value="1">Electrical</option>-->
                <!--<option value="2">Mechanical</option>-->
                </select>
                <div id="incident_type_id_error"><?php echo $beanUi->get_error('incident_type_id'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="date_of_incident">Date of Incident</label>
                <input type="text" readonly="" name="data[date_of_incident]" class="datetimepicker" id="datetimepicker1" value="<?php echo $beanUi->get_view_data("date_of_incident"); ?>" />
                <div id="date_of_incident_error"><?php echo $beanUi->get_error('date_of_incident'); ?></div>
            </div>
            <br />
            <div class="holder required" id="divParentIncidentMapping">
                <label for="parent_incident_mapping">Parent Incident Mapping</label>
                <select  name="parent_incident_mapping" id="parent_incident_mapping">
                <option value="">--Select One--</option>
                <option value="1" <?php echo (($beanUi->get_view_data("parent_incident_mapping") == 1) ? "selected" : "");?>>Yes</option>
                <option value="0" <?php echo (($beanUi->get_view_data("parent_incident_mapping") == 0) ? "selected" : "");?>>No</option>
                </select>
                <div id="parent_incident_mapping_error"><?php echo $beanUi->get_error('parent_incident_mapping'); ?></div>
            </div>
            <br />
            <div class="holder required" id="divParentIncidentId">
                <label for="parent_incident_id">Common Incident</label>
                <select style="width:50%;" name="parent_incident_id" id="parent_incident_id">
                <option value="">---Select---</option>

            </select>
                <div id="parent_incident_mapping_error"><?php echo $beanUi->get_error('parent_incident_mapping'); ?></div>
            </div>
            <br />
<!--            <div class="holder required">
                <label for="time_of_incident">Time of Incident</label>
                <input type="text" readonly="" name="data[time_of_incident]" class="datetimepicker_for_time" id="datetimepicker2" value="<?php echo $beanUi->get_view_data("time_of_incident"); ?>" />
                <div id="time_of_incident_error"><?php //echo $beanUi->get_error('time_of_incident'); ?></div>
            </div>
            <br />-->
            <div class="holder required">
                <label for="time_of_incident">Time of Incident</label>
                <?php
                $inctime = $beanUi->get_view_data("time_of_incident");
                $exptime = explode(":",$inctime);
                echo '<select class="combodate hello" style="width:8%;">
		<option value="">HH</option>';
		for($i=0;$i<24;$i++) {
			$val = sprintf('%02s', $i);
			echo '<option value="'.$val.'" '.(($exptime[0] == $val) ? "selected" : "").'>'.$val.'</option>';
		}
		echo '</select>';
		echo '<select class="combodate hello" style="width:8%;">
		<option value="">mm</option>';
		for($j=0;$j<60;$j++) {
			$val = sprintf('%02s', $j);
			echo '<option value="'.$val.'" '.(($exptime[1] == $val) ? "selected" : "").'>'.$val.'</option>';
		}
		echo '</select>';
                
                ?>
                <input type="text" style="width:10%; border: medium none;font-weight: bold;font-size: 16px;" readonly="" name="data[time_of_incident]"  id="datetimepicker2" value="<?php echo $beanUi->get_view_data("time_of_incident"); ?>" />
                <div id="time_of_incident_error"><?php echo $beanUi->get_error('time_of_incident'); ?></div>
                <script type="text/javascript">
		$(function(){
			$(".combodate").change(function() {
				var asd ='';
				$(".combodate").each(function() {
						asd += ($(this).val() ? $(this).val() : '00')+":";
						
					});
				$("#datetimepicker2").val(asd.slice(0, -1));
			});
			
		});
		</script>
            </div>
            <br />
            
            
            <div class="holder required">
                <label for="place">Venue</label>
                <input type="text" name="data[place]" id="place" value="<?php echo $beanUi->get_view_data("place"); ?>" />
                <div id="place_error"><?php echo $beanUi->get_error('place'); ?></div>
            </div>
            <br />
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
</script>              
            <div class="holder required">
                <label for="time_of_incident">Incident Category</label>
                <input type="hidden" name="incident_category_id_old" value="<?php echo $beanUi->get_view_data("incident_category_id"); ?>" />
                <select  name="incident_category_id" id="incident_category_id">
                    <option value="">--Select One--</option>
                    <?php
                    foreach ($incident_category as $rowdata) {
                        ?>
                        <option value="<?php echo $rowdata->id; ?>" <?php
                        if ($beanUi->get_view_data("incident_category_id") == $rowdata->id) {
                            echo "selected";
                        }
                        ?>><?php echo $rowdata->name; ?></option>
                            <?php } ?>
                </select>
                <div id="incident_category_id_error"><?php echo $beanUi->get_error('incident_category_id'); ?></div>
            </div>
            <br/>
            <div class="holder required" id="nearmiss">
                <label for="time_of_incident_others">Incident Description</label>
                <input type="text" value="<?php echo $beanUi->get_view_data("incident_description"); ?>" name="data[incident_description]" id="incident_description">
            </div>
            <br />
            <div id="exceptnearmiss">
                <div class="holder required">
                    <label for="time_of_incident">Nature of Injury</label>
                    <input type="hidden" id="nature_of_injury_id_old" name="nature_of_injury_id_old" value="<?php echo $beanUi->get_view_data("nature_of_injury_id"); ?>" />
                    <?php
                    $expvalue = explode(",", $beanUi->get_view_data("nature_of_injury_id"));
                    ?>
                    <select name="nature_of_injury_id[]" id="nature_of_injury_id" multiple style="width:300px;height:180px;">
                        <option value="">--Select One--</option>
                        <?php
                        foreach ($nature_of_injury as $rows) {
                            ?>
                            <option value="<?php echo $rows->id; ?>" <?php
                            if ($beanUi->get_view_data("incident_category_id") != 1) {
                                if (in_array($rows->id, $expvalue)) {
                                    echo "selected";
                                }
                            }
                            ?>><?php echo $rows->name; ?></option>
                                <?php } ?>

                    </select>
                    <div id="nature_of_injury_id_error"><?php echo $beanUi->get_error('incident_category_id'); ?></div>
                </div>
                <br />
                <div class="holder required" id="edit_nop_other">
                    <label for="time_of_incident_others">Others</label>
                    <input type="text" name="data[nature_of_injury_other]" id="nature_of_injury_other" value="<?php echo $beanUi->get_view_data("nature_of_injury_other"); ?>">
                </div>
                <br />
                <div class="holder required">
                    <label for="time_of_incident" style="float: left;">Injury at Body Part</label>
                    <table class="table table-bordered table-condensed" style="width:70%;float: left;">
                        <?php
                        $childArr = "";
                        foreach ($body_part_injury as $rowdata) {
                            $childArr = @$childData[$rowdata->id];
                            $status = @$body_part_injury_mappingData[$rowdata->id][0]->status;
                            $checked = ($status == "1" || $status == "2" ? "checked" : "");
                            ?>
                            <tr>
                                <td width="5%"> 
                                    <input class="bodypart" type="checkbox" <?php echo $checked; ?> name="bodypart_id[]" value="<?php echo $rowdata->id; ?>" /></td>
                                <td width="35%"><?php echo ucwords($rowdata->name); ?></td>
                                <td  width="60%">
                                    <div>
                                        <?php
                                        foreach ($childArr as $value) {
                                            $statusData = @$injury_status[$rowdata->id][$value->id]->status;
                                            $checked1 = ($statusData == "1" ? "checked" : "");
                                            $checked2 = ($statusData == "2" ? "checked" : "");
                                            $checkeddisplay = ($statusData == "1" || $statusData == "2" ? "block" : "none");
                                            echo '<div class="new childRows' . $rowdata->id . '" style="display:' . $checkeddisplay . '" >';
                                            ?>
                                            <input type="hidden" name="parent_id[]" value="<?php echo $rowdata->id; ?>" />
                                            <input type="hidden" name="bodypart_ids[]" value="<?php echo $value->id; ?>" />
                                            <?php
                                            echo '<div id="asd' . $rowdata->id . '" class="asd">' . $value->name . ' : ';
                                            echo '<input type="radio" ' . $checked1 . ' class="rchild' . $rowdata->id . '" name="bodypart_status_' . $value->id . '" value="1" /> Yes <input type="radio" class="rchild' . $rowdata->id . '" ' . $checked2 . ' name="bodypart_status_' . $value->id . '" value="2" /> No <br></div>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('input[type="checkbox"]').click(function () {
                                        if ($(this).attr("value") == "<?php echo $rowdata->id; ?>") {
                                            $('.rchild<?php echo $rowdata->id; ?>').prop('checked', false);
                                            $(".childRows<?php echo $rowdata->id; ?>").children("div .asd").show();
                                            $(".childRows<?php echo $rowdata->id; ?>").toggle();
                                            //$("#asd<?php echo $rowdata->id; ?>").toggle();
                                            $('#pid_<?php echo $rowdata->id; ?>').prop('disabled', false);
                                        }
                                    });
                                });
                            </script>
                        <?php } ?>
                    </table>
                    <div id="bodypart_error"><?php echo $beanUi->get_error('incident_category_id'); ?></div>
                </div>
            </div>
            <br />
            <div class="holder">
                <label for="time_of_incident">Reportable</label>
                <input type="radio" name="data[reportable]" id="reportable" value="Y" <?php
                if ($beanUi->get_view_data("reportable") == 'Y') {
                    echo "checked";
                };
                ?> /> Yes
                <input type="radio" name="data[reportable]" id="reportable" value="N" <?php
                if ($beanUi->get_view_data("reportable") == 'N') {
                    echo "checked";
                };
                ?>  /> No
                <div id="reportable_error"><?php echo $beanUi->get_error('reportable'); ?></div>
            </div>
            <br />
            <div class="holder">
                <label for="reporting_date">Reporting Date</label>
                <input type="text" readonly="" name="data[reporting_date]" class="datetimepicker" id="reporting_date" value="<?php echo $beanUi->get_view_data("reporting_date"); ?>" />
                <div id="reporting_date_error"><?php echo $beanUi->get_error('reporting_date'); ?></div>
            </div>
            <br />
            <div class="holder">
                <label for="remarks">FIR Description</label>
                <textarea name="data[fir_description]" id="fir_description"><?php echo $beanUi->get_view_data("fir_description"); ?></textarea>
                <div id="fir_description_error"><?php echo $beanUi->get_error('fir_description'); ?></div>
            </div>
            <br />
            <?php
            $pdf_links = "";
            $image_links = "";
            $pdf_caption = "";
            $pdf_exists = 0;
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
                            $pdf_links .= '<a class="btn btn-danger btn-sm" href="edit_incident.php?action=delete_upload&t=' . base64_encode("file_upload_incident_mapping") . '&id=' . $purow->id . '&post_id=' . $activity_id . '" title="Delete file" onclick="return confirm(\'Confirm delete.\');">
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
                            $image_links .= '<a href="edit_incident.php?action=delete_upload&t=' . base64_encode("file_upload_incident_mapping") . '&id=' . $purow->id . '&post_id=' . $activity_id . '" 
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
                            $video_links .= '<a href="edit_incident.php?action=delete_upload&t=' . base64_encode("file_upload_incident_mapping") . '&id=' . $purow->id . '&post_id=' . $activity_id . '" 
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
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pdf_links; ?>
                    <div id="file_path_error"><?php echo $beanUi->get_error('file_path'); ?></div>
<?php   echo FILESIZE_ERROR;
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
            <div class="holder">
                <fieldset class="">
                    <legend>Upload Featured Image</legend>
                    <label for="image_path">Featured Image</label>
                    <?php
                    $imgpath =  CESC_BASE_PATH . "/" . $beanUi->get_view_data("featured_image_path");
                    if ($beanUi->get_view_data("featured_image_path") != '' && file_exists($imgpath)) {
                        $img_path = $beanUi->get_view_data("featured_image_path");
                        $var1 = explode("/", $img_path);
                        $var2 = end($var1);
                        $img = explode("?", $var2);
                        $image_name = str_replace("-avatar", "", $img[0]);
                        $random = @$img[1];
                        $path = explode("?", $img_path);
                        $imgpath = "../../../../" . $path[0];

                        $srcpath = "../../../../" . $beanUi->get_view_data("featured_image_path");
                        $srcpath = site_url($beanUi->get_view_data("featured_image_path"));
                        $actions = '<a class="btn btn-sm btn-danger" href="edit_incident.php?action=delete_featured_image&t=' . base64_encode("incident") . '&id=' . $activity_id . '"  onclick="return confirm(\'Confirm delete.\');"><i class="fa fa-trash"></i> Delete Featured Image</a>';
                    } else {
                        $srcpath = "assets/img/incident.png";
                        $srcpath = url("assets/css/cropimage/img/incident.png");
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
            <div class="holder invreq">
                <label for="investigation_req">Investigation Required</label>
                <input type="radio" name="data[investigation_req]" id="investigation_req" value="Y" <?php
                if ($beanUi->get_view_data("investigation_req") == 'Y') {
                    echo "checked";
                };
                ?> /> Yes
                <input type="radio" name="data[investigation_req]" id="investigation_req" value="N" <?php
                if ($beanUi->get_view_data("investigation_req") == 'N') {
                    echo "checked";
                };
                ?> /> No
                <div id="investigation_req_error"><?php echo $beanUi->get_error('investigation_req'); ?></div>
            </div>
            <br />

            <script type="text/javascript">
                $(document).ready(function () {
                <?php if ($beanUi->get_view_data("investigation_req") == 'N') {
                    ?>
                                    $(".box2").hide();
                                    $(".box3").hide();
                <?php } ?>
                <?php if ($beanUi->get_view_data("investigation_done") == 'N') {
                    ?>
                                    $(".box2").hide();

                <?php } ?>
                    $('.invreq input[type="radio"]').click(function () {

                        if ($(this).attr("value") == "Y") {
                            $(".box2").not(".red").hide();
                            $(".investigation_done").show();
                        }
                        if ($(this).attr("value") == "N") {
                            $(".box2").not(".green").hide();
                            $(".investigation_done").hide();
                        }

                    });
                    $('.abcc input[type="radio"]').click(function () {

                        if ($(this).attr("value") == "Y") {
                            // $(".box").not(".red").hide();
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
            <div class="holder abcc box3 investigation_done">
                <label for="investigation_done">Investigation Done</label>
                <input type="radio" name="data[investigation_done]" id="investigation_done" value="Y" <?php
                       if ($beanUi->get_view_data("investigation_done") == 'Y') {
                           echo "checked";
                       };
                       ?> /> Yes
                <input type="radio" name="data[investigation_done]" id="investigation_done" value="N" <?php
                       if ($beanUi->get_view_data("investigation_done") == 'N') {
                           echo "checked";
                       };
                       ?> /> No
                <div id="investigation_done_error"><?php echo $beanUi->get_error('investigation_done'); ?></div>
            </div>
            <br />

            <div class="investigation box2" style="width:100%;">
                <div class="holder">
                    <label for="date_of_investigation">Date of Investigation</label>

                    From <input placeholder="From" type="text" name="data[inv_from_date]" readonly="" class="datetimepicker"  id="datetimepicker3" value="<?php
                    if ($beanUi->get_view_data("inv_from_date") == '0000-00-00' || $beanUi->get_view_data("inv_from_date") == '') {
                        echo '';
                    } else {
                        echo $beanUi->get_view_data("inv_from_date");
                    }
                    ?>" style="width:25%;" id="inv_from_date" />
                    To <input placeholder="To" type="text" name="data[inv_to_date]" readonly="" class="datetimepicker" id="datetimepicker4" value="<?php
                    if ($beanUi->get_view_data("inv_to_date") == '0000-00-00' || $beanUi->get_view_data("inv_to_date") == '') {
                        echo '';
                    } else {
                        echo $beanUi->get_view_data("inv_to_date");
                    }
                    ?>" style="width:25%;" id="inv_to_date" />
                </div>
                <br />

                <div class="holder ">
                    <label for="synopsis" style="float:left;">No. of findings</label>
                    <table class="table table-hover table-bordered table-condensed" width="50%" style="float:left;width:70%;">

                        <tr>
                            <td >

                                <input type="text" readonly=""  id="pid" name="data[no_of_finding]" min="1" value="<?php echo $beanUi->get_view_data("no_of_finding"); ?>"  placeholder="No. of Finding"  />
                                <a class="js-open-modal btn btn-xs btn-success" href="#" data-modal-id="popup"> <i class="fa fa-plus"></i> Edit Findings</a> 
                                <!--participants popup-->
                                <div id="popup" class="modal-box" style="height: 50%;">
                                    <header> <a href="#" class="js-modal-close close">×</a>
                                        <h3>Finding Details</h3>
                                    </header>
                                    <div class="modal-body" style="height:62%;overflow-x: hidden;overflow-y: scroll;">
                                    <script type="text/javascript">
                                       
                                        $(document).ready(function () {
                                             
                                            var counter = <?php echo count($finding_rows); ?> + 1;
                                            $("#addButton").click(function () {
                                                var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'TextBoxDiv' + counter).attr("class", 'removetr cls');
                                                newTextBoxDiv.after().html('<td style="text-align:center;">' + (counter + 1) + '</td>'
                                                    + '<td width="30%"><input class="req" style="width:100%;"type="text" id="description_' + counter + '" placeholder="Non Compliance"></td>'
                                                    + '<td width="10%"><select style="width:100px;float: left;" class="req" id="condition_' + counter + '"><option selected="selected" disabled="disabled">-select condition-</option><option value="UA">UA</option><option value="UC">UC</option><option value="Physical Cause">Physical Cause</option><option value="System Cause">System Cause</option></select></td>'
                                                    + '<td width="70%"><select style="width:100px;float:left;" class="complainces req" id="action_taken_' + counter + '"><option selected="selected" disabled="disabled">-select action-</option><option value="N">No</option><option value="Y">Yes</option></select>'
                                                    + '<div style="display:none;float:left;margin-left:10px;" class="showdiv" id="compliance_' + counter + '"><input style="width:200px;" type="text" id="compliance_desc_' + counter + '" placeholder="Compliance Description" />'
                                                    + '&nbsp;<input type="text" style="width:200px;" readonly="" class="datetimepicker" id="compliance_date_' + counter + '" placeholder="Compliance Date"></div></td>'
                                                    + '<td class="center" align="center" width="25%"><a class="btn btn-danger btn-sm rmbtnn2" id="removeButton' + counter + '" name="delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>');
                                                
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


                                                $('.complainces').change(function () {
                                                    if ($(this).val() == "Y") {
                                                        $(this).nextAll('.showdiv').show();
                                                    } else {
                                                        $(this).nextAll('.showdiv').hide();
                                                    }
                                                });
                                                
                                               $(".datetimepicker").datetimepicker({
                                                        timepicker: false,
                                                        datepicker: true,
                                                        scrollMonth: false,
                                                        scrollInput: false,
                                                        format: 'Y-m-d'
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
                                            renumberRows();
                                            $('.complainces').change(function () {
                                                if ($(this).val() == "Y") {
                                                    $(this).nextAll('.showdiv').show();
                                                } else {
                                                    $(this).nextAll('.showdiv').hide();
                                                }
                                            });


                                            $(".rmbtnn").click(function () {
                                                var action = $(this).data('action');
                                                var id = $(this).data('acid');
                                                $("#loaderIcon").show();
                                                var queryString;
                                                switch (action) {
                                                    case "finding_delete":
                                                        var cnd = $(this).parents("tr .removetr").siblings().length;
                                                        if (cnd >= 1)
                                                        {
                                                            if (confirm("Are you sure want to delete this?"))
                                                            {
                                                                queryString = 'action=' + action + '&incident_id=' + <?php echo $activity_id; ?> + '&message_id=' + id;
                                                                var nofval = $('#nof').val();
                                                                $('#nof').val(nofval - 1);
                                                                var pidval = $('#pid').val();
                                                                $('#pid').val(pidval - 1);
                                                                counter--;
                                                                function renumberRows() {
                                                                    $('#TextBoxesGroup tr').each(function (index, el) {
                                                                        $(this).children('td').first().text(function (i, t) {
                                                                            return index++;
                                                                        });
                                                                    });
                                                                }
                                                                renumberRows();
                                                                $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
                                                            } else {
                                                                return false;
                                                            }
                                                        } else {
                                                            return false;
                                                        }
                                                }

                                                jQuery.ajax({
                                                    url: "crud_action.php",
                                                    data: queryString,
                                                    type: "POST",
                                                    success: function (data) {

                                                        switch (action) {
                                                            case "finding_delete":
                                                                $('#message_' + id).fadeOut();
                                                                break;
                                                        }
                                                        $("#txtmessage").val('');
                                                        $("#loaderIcon").hide();
                                                    },
                                                    error: function () {}
                                                });
                                            });


                                                /**<!--insert no of findings**/
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
                                                    //alert(flag);	 
                                                    if (flag == true) {
                                                        e.preventDefault();
                                                        var button_id = $(this).attr("id");
                                                        var token_id = $("#token_id").val();
                                                        var description = Array();
                                                        var condition = Array();
                                                        var action_taken = Array();
                                                        var compliance_desc = Array();
                                                        var compliance_date = Array();
                                                        var no_of_parti = $("#nof").val();
                                                        if (no_of_parti > 0)
                                                        {
                                                            for (var row_no = 0; row_no <= no_of_parti; row_no++)
                                                            {
                                                                description[row_no] = $.trim($("#description_" + row_no).val());
                                                                condition[row_no] = $.trim($("#condition_" + row_no).val());
                                                                action_taken[row_no] = $.trim($("#action_taken_" + row_no).val());
                                                                compliance_desc[row_no] = $.trim($("#compliance_desc_" + row_no).val());
                                                                compliance_date[row_no] = $.trim($("#compliance_date_" + row_no).val());
                                                            }

                                                            var ajax_data = {
                                                                "action": "save_findings_edit",
                                                                "description": description,
                                                                "condition": condition,
                                                                "action_taken": action_taken,
                                                                "compliance_desc": compliance_desc,
                                                                "compliance_date": compliance_date,
                                                                "incident_id": <?php echo $activity_id; ?>,
                                                                "nof": no_of_parti,
                                                                "token_id": token_id
                                                            };

                                                            $.ajax({
                                                                type: 'post',
                                                                cache: false,
                                                                data: ajax_data,
                                                                success: function (save_findings_edit) {
                                                                    if (save_findings_edit)
                                                                    {
                                                                        var elements = $("#TextBoxesGroup tr.cls").length;
                                                                        if (elements > 0)
                                                                        {
                                                                            $('#TextBoxesGroup tr.cls:first').before(save_findings_edit);
                                                                            var idss = $('.total_count').last().val();
                                                                        } else
                                                                        {
                                                                            $('#TextBoxesGroup tr:first').append(save_findings_edit);
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
                                                                                case "finding_delete":
                                                                                    var cnd = $(this).parents("tr .removetr").siblings().length;
                                                                                    if (cnd >= 1)
                                                                                    {
                                                                                        if (confirm("Are you sure want to delete this?"))
                                                                                        {
                                                                                            queryString = 'action=' + action + '&incident_id=' + <?php echo $activity_id; ?> + '&message_id=' + id;
                                                                                            var nofval = $('#nof').val();
                                                                                            $('#nof').val(nofval - 1);
                                                                                            var pid = $('#pid').val();
                                                                                            $('#pid').val(nofval - 1);
                                                                                            counter--;
                                                                                            $.when($(this).parents("tr .removetr").remove()).then(renumberRows);

                                                                                        } else {
                                                                                            return false;
                                                                                        }
                                                                                    } else {
                                                                                        return false;
                                                                                    }
                                                                            }

                                                                            jQuery.ajax({
                                                                                url: "crud_action.php",
                                                                                data: queryString,
                                                                                type: "POST",
                                                                                success: function (data) {
                                                                                    switch (action) {
                                                                                        case "finding_delete":
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
                                                        } else {
                                                            $("#popup").hide()
                                                            $(".modal-overlay").remove();
                                                            return false;
                                                        }
                                                    }
                                                    return flag;
                                                });
                                                /**insert no of findings **/
                                            });
                                        </script>
                                        <table id='TextBoxesGroup' class="table table-bordered">
                                            <div id="comment-list-box">
                                                <tr>
                                                    <th>Sl.No</th>
                                                    <th>Non Compliance</th>
                                                    <th>Condition</th>
                                                    <th>Action Taken</th>
                                                    <?php
                                                    if (count($finding_rows) > 0) {
                                                        echo '<th>Action</th>';
                                                    } else {
                                                        echo '<th></th>';
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                                if (count($finding_rows)) {
                                                    $f = 0;
                                                    foreach ($finding_rows as $row) {
                                                        $f = $f + 1;
                                                        ?>
                                                        <tr class="removetr" id="message_<?php echo $row->id; ?>">
                                                            <td align="center" width="10%"><?php echo $f; ?></td>
                                                            <td align="center" width="30%"><?php echo $row->description; ?></td>
                                                            <td align="center" width="30%"><?php echo $row->conditions; ?></td>
                                                            <td align="center" width="60%">
                                                                <?php
                                                                if ($row->action_taken == 'Y') {
                                                                    echo "Yes";
                                                                    echo "<br><b>Compliance Description </b>: " . $row->compliance_desc;
                                                                    echo "<br><b>Compliance Date </b>: " . $row->compliance_date;
                                                                } else {
                                                                    echo "No";
                                                                }
                                                                ?></td>
                                                            <td align="center" width="25%"><a ID="dataremove" class="btn btn-danger btn-sm rmbtnn" name="delete" class="btn btn-danger btn-sm rmbtnn" data-acid="<?php echo $row->id; ?>" data-action="finding_delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>

                                            <tr id="TextBoxDiv0" class="removetr cls">
                                            <input type="hidden" id="activity_id" value="<?php echo $activity_id; ?>" />
                                            <td align="center"><?php echo count($finding_rows) + 1; ?></td>
                                            <td width="30%"><input type='text' class="req" id='description_0' placeholder='Non Compliance' style="width:100%;" ></td>

                                            <td width="10%">
                                                <select style="width:100px;float:left;" class="req" id="condition_0">
                                                    <option selected="selected" disabled="disabled">-select condition-</option>
                                                    <option value="UA">UA</option>
                                                    <option value="UC">UC</option>
                                                    <option value="Physical Cause">Physical Cause</option>
                                                    <option value="System Cause">System Cause</option>
                                                </select>
                                            </td>

                                            <td width="70%">
                                                <select style="width:100px;float:left;" class="complainces req" id="action_taken_0">
                                                    <option selected="selected" disabled="disabled">-select action-</option>
                                                    <option value="N">No</option>
                                                    <option value="Y">Yes</option>
                                                </select>
                                                <div id="compliance_0" class="showdiv" style="display:none;float:left;margin-left:10px;">
                                                    <input style="width:200px;" type="text" id="compliance_desc_0" placeholder="Compliance Description" />
                                                    <input style="width:200px;" type="text" id="compliance_date_0" readonly="" class="datetimepicker" placeholder="Compliance Date">
                                                </div>
                                            </td>
                                            <td align="center" width="25%"><a ID="dataremove" name="delete" class="btn btn-danger btn-sm rmbtnn1"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>

                                            </tr>
                                        </table>
                                    </div>
                                    <footer style="margin-left:0px;"> 
                                        <input type='hidden' id="nof" name="finding_count" value="<?php echo count($finding_rows) + 1; ?>" style="float:left;">
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
                                $("#pid_<?php echo $prow->id; ?>").keypress(function (e) {
                                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
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
            <br>

<?php $expvaluee = explode(",", $beanUi->get_view_data("violation_category")); ?>
            <br>
            <script type="text/javascript">
                $(document).ready(function () {
                    var yesdata = $("#investigation_required_yes").val();//alert(yesdata); 
                    if (yesdata != "") {
                        $(".InvestigationYes").show();
                    } else {
                        $(".InvestigationNo").hide();
                    }
                    var nodatacapa = $("#investigation_recommended_ca_pa").val();//alert(nodatacapa); 
                    if (nodatacapa != "") {
                        $(".InvestigationNo").show();
                    } else {
                        $(".InvestigationYes").hide();
                    }

                    $('.invreq input[type="radio"]').click(function () {
                        if ($(this).attr("value") == "Y") {
                            $(".box4").not(".red").hide();
                            $(".InvestigationYes").show();
                        }
                        if ($(this).attr("value") == "N") {
                            $(".box4").not(".green").hide();
                            $(".InvestigationYes").hide();
                        }

                    });
                    $('.invreq input[type="radio"]').click(function () {
                        if ($(this).attr("value") == "N") {
                            $(".box5").not(".red").hide();
                            $(".InvestigationNo").show();
                        }
                        if ($(this).attr("value") == "Y") {
                            $(".box5").not(".green").hide();
                            $(".InvestigationNo").hide();
                        }

                    });

                });
            </script>
            <div class="InvestigationYes box4 holder">
                <label for="investigation_required_yes">If Yes</label>
                <textarea type="text" name="data[investigation_required_yes]" id="investigation_required_yes" style="width:650px; height:75px;"><?php echo $beanUi->get_view_data("investigation_required_yes"); ?></textarea>
            </div>
            <div class="InvestigationNo box5 holder">
                <label for="time_of_incident_others">If No</label>
                <br/>
                <label for="investigation_recommended_ca_pa">(Recommended CA / PA)</label>
                <textarea type="text" name="data[investigation_recommended_ca_pa]" id="investigation_recommended_ca_pa" style="width:650px; height:75px;"><?php echo $beanUi->get_view_data("investigation_recommended_ca_pa"); ?></textarea>
                <br/>
                <br/>
                <label for="investigation_action_taken">Action Taken</label>
                <textarea type="text" name="data[investigation_action_taken]" id="investigation_action_taken" style="width:650px; height:75px;"><?php echo $beanUi->get_view_data("investigation_action_taken"); ?></textarea>
            </div>
            <br>
            <div class="holder">
                <label for="violation">Violation Category</label>
                <select  multiple="" name="violation_category[]" style="width:230px;height:135px;"  id="violation_category">
                    <option  disabled="disabled">-select category-</option>

                    <?php
                    foreach ($voilation_category as $key => $rowss) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php
                        if (in_array($key, $expvaluee)) {
                            echo "selected";
                        }
                        ?>><?php echo $rowss; ?></option>
                    <?php } ?>

                </select>
            </div>
            <br>
            <div class="holder">
                <label for="remarks">Remarks</label>
                <textarea name="data[remarks]" id="remarks"><?php echo $beanUi->get_view_data("remarks"); ?></textarea>
                <div id="remarks_error"><?php echo $beanUi->get_error('remarks'); ?></div>
            </div>
            <br /><br /><br />

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
                <input type="submit" value="Update" class="btn btn-smbtn btn-sm btn-primary bodypartck" />
                <a href="index.php?status_id=<?php echo @$_REQUEST["status_id"]; ?>&search_title=<?php echo @$_REQUEST["search_title"]; ?>&districtid=<?php echo @$_REQUEST["districtid"]; ?>&incident_category_ids=<?php echo @$_REQUEST["incident_category_ids"]; ?>&fromdate=<?php echo @$_REQUEST["fromdate"]; ?>&todate=<?php echo @$_REQUEST["todate"]; ?>&page=<?php echo @$_REQUEST["page"]; ?>&activity_id=<?php echo $activity_type_id; ?>" class="btn btn-sm btn-danger">Cancel</a>
                <input type="hidden" name="_action" value="update" />
                <input type="hidden" name="data[id]" id="post_id" value="<?php echo $beanUi->get_view_data("id"); ?>" />
                <input type="hidden" id="f_image_error" value="" />
            </div>
        </form>

    </div>
</div>


<?php $controller->get_footer(); ?>
<link rel="stylesheet" href="<?php echo url('assets/css/jqueryui/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-1.12.4.js') ?>"></script>
<script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-ui.js') ?>"></script>
<!-- JavaScript -->
<script type="text/javascript" src="<?php echo url("assets/js/jquery.Jcrop.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo url("assets/js/jquery.imgpicker.js") ?>"></script>
<div id="tracking_post_detail"></div>

<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>
<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
<script type="text/javascript">
    jQuery.datetimepicker.setLocale('en');
    
    jQuery(function() {		
    var date = new Date();
    var currentMonth = date.getMonth();
    var currentDate = date.getDate();
    var currentYear = date.getFullYear();
    jQuery('.datetimepicker').datetimepicker({

        timepicker: false,
        scrollMonth: false,
        scrollInput: false,
        format: 'Y-m-d',
        <?php
        $currentDate = date('Y-m-d');
        if( $currentDate >= CHECK_DATE && ($role_id != 1 && $role_id != 3)) {
        ?>
        minDate: new Date(currentYear, currentMonth, currentDate-(currentDate-1)),
        <?php } if( $currentDate < CHECK_DATE && ($role_id != 1 && $role_id != 3)) { ?>
    minDate: new Date(currentYear, (currentMonth-1), currentDate-(currentDate-1)),
         
    <?php } ?>
        step: 5
    });
     });

</script>    
<!-- Popup -->
<script>
    var $m = jQuery.noConflict();
    $m(function () {

        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

        $m('a[data-modal-id]').click(function (e) {
            var row_id = $(this).attr("data-modal-id").replace("popup", "");
            var pid_value = $("#pid").val();
            var text2 = '';
            for (var i = 0; i < pid_value; i++)
            {
                text2 += '<tr>';                
                text2 += '<td width="70%"><input style="width:90%;" type="text" id="description_' + i + '"  placeholder="Description" /></td>';
                text2 += '<td width="10%"><select style="width:80%;" id="action_taken_' + i + '"><option value="Y">Yes</option><option value="N">No</option></select></td>';
                text2 += '</tr>';
            }
            $("#pdetails").html(text2);
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
                // left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                left: "10%"
            });
        });
        $m(window).resize();
    });
</script>
<script type="text/javascript">
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
                $s('#avatar2').attr('src', 'assets/img/incident.png');
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
        
//	$("#edit_post").submit(function () {
//            var incident_no = jQuery.trim( jQuery("#incident_no").val() );
//            var featured_image = jQuery.trim( jQuery("#featured_image").val() );
//            var fimagesrc 	= jQuery("#avatar2").prop("src");
//            fimagesrc_arr 	= fimagesrc.split("/");
//            featured_image 	= fimagesrc_arr[fimagesrc_arr.length - 1];
//            var error_counter = 0;
//            jQuery(".errors").empty();
//            if (incident_no == undefined || incident_no == "") {
//                jQuery("#activity_no_error").html("<div class=\"errors\">Audit no is required.</div>");
//                error_counter++;
//            }
//            if( featured_image != undefined && featured_image == "incident.png" ) {
//                jQuery("#featured_image_error").html("<div class=\"errors\">Featured Image is required.</div>");
//                error_counter++;
//            }  
//
//            if( error_counter > 0 ) {
//                    jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
//                    jQuery('html, body').animate({ scrollTop: 0}, 'slow');
//                    return false;
//            }
//	});


    $("#edit_post").submit(function () {
        var date_of_incident    = jQuery.trim( jQuery("#datetimepicker1").val() );
        var time_of_incident    = jQuery.trim( jQuery("#datetimepicker2").val() );
        var place               = jQuery.trim( jQuery("#place").val() );
        var set_type3           = jQuery.trim( jQuery("#set_type3").val() ); 
        var incident_type_id    = jQuery.trim( jQuery("#incident_type_id").val() ); 
        var checkedval          = 0;
        var inccatid            = $("#incident_category_id").val();
        var nature_of_injury_id = $("#nature_of_injury_id").val();
        var error_counter       = 0;
        jQuery(".errors").empty();
        <?php if (count($devition_names) == 0) { ?>
        var division  = jQuery.trim( jQuery("#division").val() );        
        if( division == undefined || division == "" ) 
        {
            jQuery("#division_error").html("<div class=\"errors\">Division is required.</div>");
            error_counter++;
        }
        <?php } ?>
        if( set_type3 == undefined || set_type3 == "" ) 
        {
            jQuery("#set_type_error").html("<div class=\"errors\">Set type is required.</div>");
            error_counter++;
        }
        if( incident_type_id == undefined || incident_type_id == "" ) 
        {
            jQuery("#incident_type_id_error").html("<div class=\"errors\">Incident Type is required.</div>");
            error_counter++;
        }
        if( date_of_incident == undefined || date_of_incident == "" ) 
        {
            jQuery("#date_of_incident_error").html("<div class=\"errors\">Date of incident is required.</div>");
            error_counter++;
        }
        if( time_of_incident == undefined || time_of_incident == "" ) 
        {
            jQuery("#time_of_incident_error").html("<div class=\"errors\">Time of incident is required.</div>");
            error_counter++;
        }
        if( place == undefined || place == "" ) 
        {
            jQuery("#place_error").html("<div class=\"errors\">Venue is required.</div>");
            error_counter++;
        }
        if( inccatid == undefined || inccatid == "" ) 
        {
            jQuery("#incident_category_id_error").html("<div class=\"errors\">Incident category is required.</div>");
            error_counter++;
        }        
       
        jQuery('.bodypart').each(function (index, elem) {
                if (jQuery(this).prop("checked") == true) {
                    checkedval++;
                }
            });
            
            if(inccatid != 1 && checkedval==0) {
                 <?php if (count($devition_names) == 0) { ?>
                if( division == undefined || division == "" ) 
                {
                    jQuery("#division_error").html("<div class=\"errors\">Division is required.</div>");
                    error_counter++;
                }
                 <?php } ?>
                if( set_type3 == undefined || set_type3 == "" ) 
                {
                    jQuery("#set_type_error").html("<div class=\"errors\">Set type is required.</div>");
                    error_counter++;
                }
                if( incident_type_id == undefined || incident_type_id == "" ) 
                {
                    jQuery("#incident_type_id_error").html("<div class=\"errors\">Incident Type is required.</div>");
                    error_counter++;
                }
                if( date_of_incident == undefined || date_of_incident == "" ) 
                {
                    jQuery("#date_of_incident_error").html("<div class=\"errors\">Date of incident is required.</div>");
                    error_counter++;
                }
                if( inccatid == undefined || inccatid == "" ) 
                {

                  jQuery("#incident_category_id_error").html("<div class=\"errors\">Incident category is required.</div>");
                    error_counter++;

                }
                if( nature_of_injury_id == undefined || nature_of_injury_id == "" ) 
                {
                    jQuery("#nature_of_injury_id_error").html("<div class=\"errors\">Nature of injury is required.</div>");
                    error_counter++;
                    
                } else {
                jQuery("#bodypart_error").html("<div class=\"errors\">Injury at Body Part not selecetd.</div>");
                 error_counter++;
                }
            }
            else if(inccatid == 1)
            {
            } else {
                 if( nature_of_injury_id == undefined || nature_of_injury_id == "" ) 
                {
                    jQuery("#nature_of_injury_id_error").html("<div class=\"errors\">Nature of injury is required.</div>");
                    error_counter++;
                    
                } 
                else 
                {
                }
            }
            if(error_counter == 0 ) {
                $("#edit_post").submit();
            }
            if( error_counter > 0 ) 
            {
                jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                jQuery('html, body').animate({ scrollTop: 0}, 'slow');
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
                    "table_name": "file_upload_incident_mapping",
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


    //function callCrudAction(action, id) {

    $(document).ready(function () {
        //------------Autoload List Data----------------------//
        var mydatalist = $("#nature_of_injury_id_old").val();
        mydatalist = mydatalist.split(',');
        //alert(mydatalist);
        for (var i = 0; i < mydatalist.length; i++) {
            if (mydatalist[i] === '12') {
                var storage = "Y";
            }
        }
        if (storage === "Y") {
            $("#edit_nop_other").show();
        } else {
            $("#edit_nop_other").hide();
            //$("#nature_of_injury_other").val("");
        }
        //------------End Autoload List Data----------------------//
        //------------Edit List data Again ------------------//

        $("#nature_of_injury_id").click(function () {
            var mydatalist = $(this).val();
            //alert(mydatalist);
            for (var j = 0; j < mydatalist.length; j++) {
                //alert(mydatalist[j]);
                if (mydatalist[j] === '12') {
                    var storage = "Y";
                }
            }
            if (storage == "Y") {
                $("#edit_nop_other").show();
            } else {
                $("#edit_nop_other").hide();
                //$("#nature_of_injury_other").val("");
            }
        });
        //------------Edit List data Again ------------------//
    });

</script> 



<script type="text/javascript">
                    var dq=jQuery.noConflict();
    dq(function () {
        var ajax_data = {
            "action": "getVenueList",
            "activity_type_id": "<?php echo $activity_type_id; ?>"
        };
        dq.ajax({
            type: 'post', url: '<?php echo page_link("activity/edit_incident.php"); ?>', cache: false,
            data: ajax_data,
            success: function (data) {
                if (data)
                {
                    var myVanueList = data.split(' | ');
                    dq("#place").autocomplete({
                        minLength: 1,
                        source: myVanueList
                    });
                }
            }
        });
    });
    
    $(document).ready(function () {
        var mydata = $("#incident_category_id").val();//alert(mydata); 
        if (mydata == '1') {
            $("#nearmiss").show();
        } else {
            $("#nearmiss").hide();
        }

        if (mydata == '1') {
            $("#exceptnearmiss").hide();
        } else {
            $("#exceptnearmiss").show();
        }
        $("#incident_category_id").change(function () {
            var mydatalist = $(this).val();//alert(mydatalist);        
            $("#nature_of_injury_id").val('');
            $(".bodypart").prop('checked', false);
            $(".bodypart").parents("td").siblings("td").find(".asd").hide();
            $(".bodypart").parents("td").siblings("td").find(".new").hide();
            for (var i = 0; i < mydatalist.length; i++) {
                if (mydatalist[i] === '1') {
                    var storage = "Y";
                }
            }
            if (storage == "Y") {
                $("#nearmiss").show();
            } else {
                $("#nearmiss").hide();
            }
            if (mydatalist == '1') {
                $("#exceptnearmiss").hide();
            } else {
                $("#exceptnearmiss").show();
            }

        });
    });

    /*$(document).ready(function () {
        $(".bodypartck").click(function () {
            var checkedval = 0;
            var inccatid = $("#incident_category_id").val();
            var nature_of_injury_id = $("#nature_of_injury_id").val();
            var error_counter = 0;
            if (inccatid == undefined || inccatid == "")
            {
                alert("Incident category is required.");

                error_counter++;
                return false;
            }

            $('.bodypart').each(function (index, elem) {
                if ($(this).prop("checked") == true) {
                    checkedval++;
                }
            });

            if (inccatid != 1 && checkedval == 0) {

                if (nature_of_injury_id == undefined || nature_of_injury_id == "")
                {
                    alert("Nature of injury is required.");
                    error_counter++;
                    return false;
                }
                alert("Injury at Body Part not selecetd.");
                return false;
            } else if (inccatid == 1)
            {
                $("#edit_post").submit();
                return true;
            } else {
                if (nature_of_injury_id == undefined || nature_of_injury_id == "")
                {
                    alert("Nature of injury is required.");
                    error_counter++;
                    return false;
                } else
                {
                    $("#edit_post").submit();
                    return true;
                }
            }

        });

    }); */
</script>
<script>
      $( document ).ready(function() {
          $("#divParentIncidentId").hide();
          var parentIncMap = $("#parent_incident_mapping").val();
          var date_of_incident = $("#datetimepicker1").val();
          if(parentIncMap == 1){
              $("#divParentIncidentId").show();
             var ajax_data = {
                            "action": "getParentIncidentDetails",
                            "doi": date_of_incident,
                            "pim": parentIncMap,
                        };
            $.ajax({
                  type: 'post',
                  cache: false,
                  data: ajax_data,
                  success: function (result) {
                   
                        var peopleHTML = "";
                        var JSONObject = JSON.parse(result);
                        console.log(JSONObject);      // Dump all data of the Object in the console

                        $.each(JSONObject, function (i) {
                            peopleHTML += '<option value="' + JSONObject[i]["id"] + '" ' + ((JSONObject[i]["id"] == <?php echo $beanUi->get_view_data("parent_incident_id"); ?>) ? "selected" : "") + '>' + JSONObject[i]["incident_no"] + '---' + JSONObject[i]["place"] + '</option>';
                        });
                        $("#parent_incident_id").html(peopleHTML);
                        $("#divParentIncidentId").show();
                  
                }
              }); 
          }
          
      
      $("#parent_incident_mapping").change(function (){
          var date_of_incident = $("#datetimepicker1").val();
          var parentIncidentMapping = $(this).val();
          var ajax_data = {
                            "action": "getParentIncidentDetails",
                            "doi": date_of_incident,
                            "pim": parentIncidentMapping,
                        };
            $.ajax({
                  type: 'post',
                  cache: false,
                  data: ajax_data,
                  success: function (result) {
                    if(parentIncidentMapping == '1'){
                        var peopleHTML = "";
                        var JSONObject = JSON.parse(result);
                        console.log(JSONObject);      // Dump all data of the Object in the console

                        peopleHTML += '<option value="">---Select---</option>';
                        $.each(JSONObject, function (i) {
                            peopleHTML += '<option value="' + JSONObject[i]["id"] + '">' + JSONObject[i]["incident_no"] + '---' + JSONObject[i]["place"] + '</option>';
                        });
                        $("#parent_incident_id").html(peopleHTML);
                        $("#divParentIncidentId").show();
                  }else{
                        $("#divParentIncidentId").hide();
                  }
                }
              });
        });
        });
  </script>
</body>
</html>
