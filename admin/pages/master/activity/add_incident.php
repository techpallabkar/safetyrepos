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
$incident_category = $beanUi->get_view_data("incident_category");
$nature_of_injury = $beanUi->get_view_data("nature_of_injury");
$body_part_injury = $beanUi->get_view_data("body_part_injury");
$childData = $beanUi->get_view_data("childData");
$voilation_category = $beanUi->get_view_data("voilation_category");


$activities = get_value_by_id($activity_id, "activity_name", $post_activity_type_master);
$l1_cat_name = "";
$tag_keys = $beanUi->get_view_data("tag_keys");
$token_id = rand(000000, 111111).time();
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
.box_faculty { display: none; }  
.box{ display: none; }
.box2{ display: none; }
.box3 { display: none; }
.box4 { display: none; }
.box5 { display: none; }
</style>

<link rel="stylesheet" href="<?php echo url('assets/css/jqueryui/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-1.12.4.js') ?>"></script>
<script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-ui.js') ?>"></script>

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
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"  data-target="#myModal"> <i class="fa fa-plus"></i> Add Division Department</button>
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
                                <select class="division" name="L1" id="L1" >
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
                                        /*tree Save Start*/
                                        $(".set").hide();
                                        $('#btnSave').click(function () {
                                            $('#division-department').hide();
                                            var closestids = $('#closestid').val();
                                            var level = $("#L1").find("option:selected").val();
                                            var level_2 = $("#L2").find("option:selected").val();
                                            
                                        /*L2 validation start*/   
                                            if(level_2 == ""){
                                                alert(" Division is required.  ");
                                                $('#division-department').show();
                                                return false;
                                            }
                                        /*L2 validation end*/
            
                                            var error_count = 0;

                                            jQuery(".errors").empty();
                                            $('#' + closestids).hide();
                                            /*$('.newcons :selected').each(function (j, selected) {
                                                var errormsg = $(this).parent().parent().find("label").html();
                                                if (this.value != '')
                                                {
                                                    if (!isNaN(this.value)) {
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
                <option value="1">Electrical</option>
                <option value="2">Mechanical</option>
                </select>
                <div id="incident_type_id_error"><?php echo $beanUi->get_error('incident_type_id'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="date_of_incident">Date of Incident</label>
                <input type="text" readonly="" name="data[date_of_incident]" class="datetimepicker dateIncidentPk" id="datetimepicker1" value="<?php echo $beanUi->get_view_data("date_of_incident"); ?>" />
                <div id="date_of_incident_error"><?php echo $beanUi->get_error('date_of_incident'); ?></div>
            </div>
            <div id="showrelatedData"></div>
            <br />
            <div class="holder " id="divParentIncidentMapping">
                <label for="parent_incident_mapping">Parent Incident Mapping</label>
                <select  name="parent_incident_mapping" id="parent_incident_mapping">
                <option value="">--Select One--</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
                </select>
                <div id="parent_incident_mapping_error"><?php echo $beanUi->get_error('parent_incident_mapping'); ?></div>
            </div>
            <br />
            <div class="holder " id="divParentIncidentId">
                <label for="parent_incident_id">Common Incident</label>
                <select style="width:50%;" name="parent_incident_id" id="parent_incident_id">
                <option value="">---Select---</option>

            </select>
                <div id="parent_incident_mapping_error"><?php echo $beanUi->get_error('parent_incident_mapping'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="time_of_incident">Time of Incident</label>
                <?php
                
                echo '<select class="combodate hello" style="width:8%;">
		<option value="">HH</option>';
		for($i=0;$i<24;$i++) {
			$val = sprintf('%02s', $i);
			echo '<option value="'.$val.'">'.$val.'</option>';
		}
		echo '</select>';
		echo '<select class="combodate hello" style="width:8%;">
		<option value="">mm</option>';
		for($j=0;$j<60;$j++) {
			$val = sprintf('%02s', $j);
			echo '<option value="'.$val.'">'.$val.'</option>';
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
            <div class="holder required">
                <label for="time_of_incident">Incident Category</label>
                <select  name="incident_category_id" id="incident_category_id">
                <option value="">--Select One--</option>
                    <?php
                    foreach ($incident_category as $rowdata) {
                        ?>
                    <option value="<?php echo $rowdata->id; ?>"><?php echo $rowdata->name; ?></option>
                    <?php } ?>
                </select>
                <div id="incident_category_id_error"><?php echo $beanUi->get_error('incident_category_id'); ?></div>
            </div>
            <br />
            <div class="holder required" id="nearmiss">
                <label for="time_of_incident_others">Incident Description</label>
                <input type="text" name="data[incident_description]" id="incident_description">
             </div>
            <br/>
            <div id="exceptnearmiss">
            <div class="holder required">
                <label for="time_of_incident">Nature of Injury</label>

                <select  class="noi" name="nature_of_injury_id[]" id="nature_of_injury_id" multiple style="width:300px;height:180px;">

                <option value="" disabled>--Select One--</option>
                    <?php
                    foreach ($nature_of_injury as $rows) {
                        ?>
                    <option value="<?php echo $rows->id; ?>"><?php echo $rows->name; ?></option>
                    <?php } ?>
                </select>
                <div id="nature_of_injury_id_error"><?php echo $beanUi->get_error('incident_category_id'); ?></div>
            </div>
            <br />
             <div class="holder required" id="divnatureofinjuryidother">
                <label for="time_of_incident_others">Others</label>
                <input type="text" name="data[nature_of_injury_other]" id="nature_of_injury_other">
                <div id="nature_of_injury_other_error"><?php echo $beanUi->get_error('nature_of_injury_other'); ?></div>
             </div>
            <br />
            <div class="holder required">
                <label for="time_of_incident" style="float: left;">Injury at Body Part</label>
                <table class="table table-bordered table-condensed" style="width:70%;float: left;">
                
                    <?php
                    $childArr = "";
                    foreach ($body_part_injury as $rowdata) {
                        //echo "<pre>";
                        // print_r($childData[$rowdata->id]);
                        $childArr = $childData[$rowdata->id];
                        ?>
                                            <tr>
                                                <td width="5%">

                                                    <input class="bodypart" type="checkbox" name="bodypart_id[]" value="<?php echo $rowdata->id; ?>" /></td>
                                                 <td width="35%"><?php echo ucwords($rowdata->name); ?></td>
                                                 <td width="60%">
                                                     <div id="childRows<?php echo $rowdata->id; ?>" style="display:none;">
                                                        <?php
                                                        foreach ($childArr as $value) {
                                                            ?>
                                                                <input type="hidden" name="parent_id[]" value="<?php echo $rowdata->id; ?>" />
                                                               <input type="hidden" name="bodypart_ids[]" value="<?php echo $value->id; ?>" />
                                                            <?php
                                                            echo $value->name . ' : ';
                                                            echo '<input type="radio" class="rchild' . $rowdata->id . '"  name="bodypart_status_' . $value->id . '" value="1" /> Yes <input type="radio" class="rchild' . $rowdata->id . '"  name="bodypart_status_' . $value->id . '" value="2" /> No <br>';
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
                                                            $("#childRows<?php echo $rowdata->id; ?>").toggle();
                                                            $('#pid_<?php echo $rowdata->id; ?>').prop('disabled', false);

                                                        }

                                                    });


                                                });
                                                        </script>


                    <?php } ?>
                </table>
                <div class="clearfix"></div>
                <div id="bodypart_error"><?php echo $beanUi->get_error('incident_category_id'); ?></div>
            </div>
    </div>
            
            <br />
            
            
            <div class="holder">
                <label for="time_of_incident">Reportable</label>
                <input type="radio" name="data[reportable]" id="reportable" value="Y" /> Yes
                <input type="radio" name="data[reportable]" id="reportable" value="N" /> No
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
            <div class="holder">
                <fieldset>
                    <legend>Upload Office Files</legend>
                    <label for="image_path">Upload File</label>
                    <input type="file" name="file_path[]" id="file_path" />
                    <input type="button" id="add_upload_file" value="Add another office files" class="btn btn-sm btn-primary" />
                    <br />
                    <label for="caption">File Caption</label>
                    <input type="text" name="caption[]" id="caption"  placeholder="File Caption" value="" />
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
                <br>
<?php   echo FILESIZE_ERROR;
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
                    <img src="<?php echo url("assets/css/cropimage/img/incident.png"); ?>" id="avatar2" width="150" style="padding: 2px;border:1px solid #d0d0d0;">
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
            <div class="holder invreq">
                <label for="investigation_req">Investigation Required</label>
                <input type="radio" name="data[investigation_req]" id="investigation_req" value="Y" /> Yes
                <input type="radio" name="data[investigation_req]" id="investigation_req" value="N" /> No
                <div id="investigation_req_error"><?php echo $beanUi->get_error('investigation_req'); ?></div>
            </div>
            <br />
            <script type="text/javascript">
                $(document).ready(function () {

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
                            $(".box").not(".red").hide();
                            $(".investigation").show();
                        }
                        if ($(this).attr("value") == "N") {
                            $(".box").not(".green").hide();
                            $(".investigation").hide();
                        }

                    });

                });
            </script>
            
            <div class="investigation_done holder box2 abcc">
                <label for="investigation_done">Investigation Done</label>
                <input type="radio"  name="data[investigation_done]" id="investigation_done" value="Y" /> Yes
                <input type="radio"  name="data[investigation_done]" id="investigation_done" value="N" /> No
                <div id="investigation_done_error"><?php echo $beanUi->get_error('investigation_done'); ?></div>
            </div>
            <br />
            <div class="investigation box" style="width:100%;">
            <div class="holder">
                <label for="date_of_investigation">Date of Investigation</label>
                From <input placeholder="From" readonly="" type="text" name="data[inv_from_date]" class="datetimepicker" id="datetimepicker3" style="width:25%;" id="inv_from_date" />
                To <input placeholder="To" readonly="" type="text" class="datetimepicker" name="data[inv_to_date]" id="datetimepicker4" style="width:25%;" id="inv_to_date" />
                  
            </div>
            <br />
            <div class="holder">
                <label for="synopsis" style="float:left;">No. of findings</label>
                <table class="table table-hover table-bordered table-condensed" width="50%" style="float:left;width:70%;">
                    <tr>
                        <td >
                            <input type="text" readonly=""  id="pid" name="data[no_of_finding]" min="1" placeholder="No. of Findings" style="width:50%;"  />
                            <a class="js-open-modal btn btn-xs btn-success" href="#" data-modal-id="popup"> <i class="fa fa-plus"></i> Add Findings</a> 
                                    <!--participants popup-->
                            <div id="popup" class="modal-box" style="height: 50%;">
                            <header> <a href="#" class="js-modal-close close">×</a>
                            <h3>Finding Details</h3>
                            </header>
                            <div class="modal-body" style="height:62%;overflow-x: hidden;overflow-y: scroll;">
                                
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        var counter = 1;
                                        $("#addButton").click(function () {
                                            var countingvalue = parseInt($("#valc").val()) + 1;
                                            $("#valc").val(countingvalue);

                                            var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'TextBoxDiv' + countingvalue).attr("class", 'removetr');
                                            newTextBoxDiv.after().html('<td>' + (countingvalue + 1) + '</td>'
                                                    + '<td width="30%"><input style="width:100%;"type="text"  class="req" id="description_' + countingvalue + '" placeholder="Non Compliance"></td>'
                                                    + '<td width="10%"><select style="width:100px;float: left;" class="req" id="condition_' + countingvalue + '"><option value="" selected disabled="disabled"> -select condition-</option><option value="UA">UA</option><option value="UC">UC</option><option value="Physical Cause">Physical Cause</option><option value="System Cause">System Cause</option></select></td>'
                                                    + '<td width="70%"><select style="width:100px;float:left;" class="complainces req" id="action_taken_' + countingvalue + '"><option value="" selected disabled="disabled"> -select action-</option><option value="N">No</option><option value="Y">Yes</option></select>'
                                                    + '<div style="display:none;float:left;margin-left:10px;" class="showdiv" id="compliance_' + countingvalue + '"><input style="width:200px;" type="text" id="compliance_desc_' + countingvalue + '" placeholder="Compliance Description" />'
                                                    + '&nbsp;<input type="text" style="width:200px;" readonly="" class="datetimepicker" id="compliance_date_' + countingvalue + '" placeholder="Compliance Date" /></div></td>'
                                                    + '<td class="center"  align="center" width="5%"><a class="btn btn-danger btn-sm rmbtnn" id="removeButton_' + countingvalue + '" name="delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>');

                                            $("#nof").val(counter + 1);
                                            newTextBoxDiv.appendTo("#TextBoxesGroup");
                                            counter++;
                                            $(".rmbtnn").show();
                                            
                                            function renumberRows() {

                                                $('#TextBoxesGroup tr').each(function (index, el) {
                                                    $(this).children('td').first().text(function (i, t) {

                                                        return index++;
                                                    });
                                                });
                                            }
                                            $(".rmbtnn").click(function () {

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
                                            
                                            jQuery(".datetimepicker").datetimepicker({
                                            timepicker: false,
                                            datepicker: true,
                                            scrollMonth: false,
                                            scrollInput: false,
                                            format: 'Y-m-d',
                                            maxDate: 0,
                                            step: 5
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

                                    });
                            </script>
                            <table id='TextBoxesGroup<?php echo @$prow->id; ?>' class="table table-bordered">
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Non Compliance</th>
                                    <th>Condition</th>
                                    <th>Action Taken</th>
                                </tr>
                                <tr id="TextBoxDiv0" class="removetr">
                                    <td>1</td>
                                    <td width="30%"><input style="width:100%;" class="req"  type='text' id='description_0' placeholder='Non Compliance' ></td>
                                    <td width="10%">
                                        <select style="width:100px;float: left;" class="req" id="condition_0">
                                            <option value="" selected disabled="disabled"> -select condition-</option>
                                            <option value="UA">UA</option>
                                            <option value="UC">UC</option>
                                            <option value="Physical Cause">Physical Cause</option>
                                            <option value="System Cause">System Cause</option>
                                        </select>
                                    </td>
                                    <td width="70%">
                                        <select style="width:100px;float: left;" class="complainces req" id="action_taken_0">
											<option value="" selected disabled="disabled"> -select action-</option>
                                            <option value="N">No</option>
                                            <option value="Y">Yes</option>
                                        </select>
                                        <div id="compliance_0" class="showdiv" style="display:none;float: left;margin-left: 10px;">
                                            <input style="width:200px;" type="text" id="compliance_desc_0" placeholder="Compliance Description" />
                                            <input style="width:200px;" type="text" id="compliance_date_0" readonly="" class="datetimepicker" placeholder="Compliance Date">
                                        </div>
                                    </td>
                                    <td align="center" width="5%"><a ID="dataremove" name="delete" class="btn btn-danger btn-sm rmbtnn1"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>
                                </tr>
                            </table>
                            </div>
                                <footer style="margin-left:0px;"> 
                                    <input type='hidden' id="nof" name="finding_count" value="1" style="float:left;">
                                    <input type='hidden' id="valc" name="finding_count2" value="0" style="float:left;width:200px;">
                                    <input type='button' value='Add More' class="btn btn-primary" id='addButton' style="float:left;">
                                    <button  id="save_pdetails" class="btn btn-small btn-primary savebtn">Save</button>
                                   <!-- <a href="#" class="btn btn-small js-modal-close">Close</a> -->
                                </footer>
                            </div>
                                    <!--participants popup-->
                                    </div>
                </td>
            </tr>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('input[type="checkbox"]').click(function () {
                        if ($(this).attr("value") == "<?php echo @$prow->id; ?>") {
                            $(".<?php echo @$prow->id; ?>").toggle();
                            $('#pid_<?php echo @$prow->id; ?>').prop('disabled', false);
                        }
                    });
                    //called when key is pressed in textbox
                    $("#pid_<?php echo @$prow->id; ?>").keypress(function (e) {
                        //if the letter is not digit then display error and don't type anything
                        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {

                            alert("Accept Only Numbers");
                            return false;
                        }
                    });
                });
            </script>
                </table>
                <div id="participant_cat_id_error" class="clearfix"><?php echo $beanUi->get_error('participant_cat_id'); ?></div>
            </div>
            <br />
    </div>
    <br>
    <script type="text/javascript">
                $(document).ready(function () {

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
            <textarea type="text" name="data[investigation_required_yes]" id="investigation_required_yes" style="width:650px; height:75px;"></textarea>
        </div>
        <div class="InvestigationNo box5 holder ">
            <label for="time_of_incident_others">If No</label>
            <br/>
            <label for="investigation_recommended_ca_pa">(Recommended CA / PA)</label>
            <textarea type="text" name="data[investigation_recommended_ca_pa]" id="investigation_recommended_ca_pa" style="width:650px; height:75px;"></textarea>
            <br/>
            <br/>
            <label for="investigation_action_taken">Action Taken</label>
            <textarea type="text" name="data[investigation_action_taken]" id="investigation_action_taken" style="width:650px; height:75px;"></textarea>
        </div>
    
    <br>
    <div class="holder">
        <label for="violation">Violation Category</label>
        <select name="violation_category[]"  multiple=""  id="violation_category">
                                            <option selected="selected" disabled="disabled">-select category-</option>
                                            <?php foreach($voilation_category as $key=>$value){ 
                                            echo '<option value="'.$key.'">'.$value.'</option>';
                                            }?>
<!--                                            <option value="SWP">SWP</option>
                                            <option value="Safety Standard">Safety Standard</option>
                                            <option value="Job Safety - Working at Height">Job Safety - Working at Height</option>
                                            <option value="Job Safety - Hot Job">Job Safety - Hot Job</option>
                                            <option value="Job Safety - Confined Space">Job Safety - Confined Space</option>
                                            <option value="Job Safety - Chemical">Job Safety - Chemical</option>
                                            <option value="Job Safety - Heavy Material Handling">Job Safety - Heavy Material Handling</option>-->
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
        <input type="submit" value="Submit" class="btn btn-sm btn-primary disablebutton bodypartck" />
        <a href="index.php?activity_id=<?php echo $activity_id; ?>" class="btn btn-sm btn-danger">Cancel</a>
        <input type="hidden" name="_action" value="Create" class="btn btn-sm btn-success" />
        <input type="hidden" id="f_image_error" value="" />
    </div>
    </form>
    </div>
</div>
<?php $controller->get_footer(); ?>
<!-- JavaScript Cropper -->
<script type="text/javascript" src="<?php echo url("assets/js/jquery.Jcrop.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo url("assets/js/jquery.imgpicker.js") ?>"></script>
<div id="tracking_post_detail"></div>
<!-- Datepicker -->

<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>
<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
<script type="text/javascript">

jQuery.datetimepicker.setLocale('en');
jQuery(function() {		
    var date = new Date();
    var currentMonth = date.getMonth();
    var currentDate = date.getDate();
    var currentYear = date.getFullYear();
var sdf = new Date(currentYear, currentMonth, currentDate-(currentDate-1));
//alert(sdf);
jQuery('.datetimepicker').datetimepicker({
    
    timepicker: false,
    scrollMonth: false,
    scrollInput: false,
    format: 'Y-m-d',
    <?php
    $currentDate = date('Y-m-d');
    if( $currentDate >= CHECK_DATE && ($role_id != 1 && $role_id != 3)) { ?>
    minDate: new Date(currentYear, currentMonth, currentDate-(currentDate-1)),
         
    <?php } if( $currentDate < CHECK_DATE && ($role_id != 1 && $role_id != 3)) { ?>
    minDate: new Date(currentYear, (currentMonth-1), currentDate-(currentDate-1)),
         
    <?php } ?>
    step: 5
});
});
//jQuery('.datetimepicker_for_time').datetimepicker({
//
//    datepicker: false,
//    scrollMonth: false,
//    scrollInput: false,
//    format: 'H:i',
//    step: 10
//});

$("#datetimepicker1").on("change", function (e) {

    var selected_date = $('#datetimepicker1').val();
 if( selected_date != "" ){
        var ajax_data = {
            "action": "getDataByDate",
            "selected_date": selected_date,
            "activity_id": <?php echo $activity_id; ?>,
            "table_name": "incident_view",
        };
        $.ajax({
            type: 'post',
            cache: false,
            data: ajax_data,
            success: function (getDataByDate) {
                if (getDataByDate)
                {
                    $("#showrelatedData").html(getDataByDate);
                }
                return false;
            }
        });
    }
    return false;
});



//<!-- Datepicker -->
//<!-- Popup -->

    var $m = jQuery.noConflict();
    $m(function () {

        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

        $m('a[data-modal-id]').click(function (e) {
            var row_id = $(this).attr("data-modal-id").replace("popup", "");
            //alert($("#pid_"+row_id).val());
            var pid_value = $("#pid").val();

//            if(pid_value==0 || pid_value=='' || pid_value == undefined)
//            {
//                alert("No. of finding should not be blank or 0.");
//				return false;
//            }
//            else
//            {


            var text2 = '';
            for (var i = 0; i < pid_value; i++)
            {
                text2 += '<tr>';
                //text2 += '<td width="10%"><input style="width:80%;" type="text" id="emp_code_'+i+'" value="" placeholder="Sl.No" /></td>';
                text2 += '<td width="70%"><input style="width:90%;" type="text" id="description_' + i + '"  placeholder="Description" /></td>';
                text2 += '<td width="10%"><select style="width:80%;" id="action_taken_' + i + '"><option value="Y">Yes</option><option value="N">No</option></select></td>';
                text2 += '</tr>';
            }

            $("#pdetails").html(text2);

            e.preventDefault();
            $m("body").append(appendthis);
            $m(".modal-overlay").fadeTo(500, 0.7);
            //$(".js-modalbox").fadeIn(500);
            var modalBox = $(this).attr('data-modal-id');
            $m('#' + modalBox).fadeIn($(this).data());
            // }
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

// Popup -->


// Featured image
    var jq = jQuery.noConflict();
    jq(function () {
        var time = function () {
            return'?' + new Date().getTime()
        };
        // Avatar setup
        jq('#avatarModal').imgPicker({
            url: 'server/upload_avatar.php',
            aspectRatio: 1,
            deleteComplete: function () {
                jq('#avatar2').attr('src', 'assets/img/incident.png');
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
                jq('#avatar2').attr('src', image.versions.avatar.url + time());
                jq('#avatar3').attr('value', image.versions.avatar.url + time());
                jq('#avatar4').attr('value', image.versions.avatar.url + time());
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
                var description = Array();
                var action_taken = Array();
                var compliance_desc = Array();
                var compliance_date = Array();
                var condition = Array();
                //var no_of_parti = $("#nof").val();
                var no_of_parti = $("#valc").val();
                //if( no_of_parti > 0 )
                // {
                for (var row_no = 0; row_no <= no_of_parti; row_no++)
                {

                    description[row_no] = $.trim($("#description_" + row_no).val());
                    action_taken[row_no] = $.trim($("#action_taken_" + row_no).val());
                    compliance_desc[row_no] = $.trim($("#compliance_desc_" + row_no).val());
                    compliance_date[row_no] = $.trim($("#compliance_date_" + row_no).val());
                    condition[row_no] = $.trim($("#condition_" + row_no).val());
                }
                var ajax_data = {
                    "action": "save_findings",
                    "description": description,
                    "condition": condition,
                    "action_taken": action_taken,
                    "compliance_desc": compliance_desc,
                    "compliance_date": compliance_date,
                    "nof": no_of_parti,
                    "token_id": token_id

                };

                $.ajax({

                    type: 'post',
                    cache: false,
                    data: ajax_data,
                    success: function (save_findings) {
                        if (save_findings)
                        {
                            $("#pid").val(save_findings);
                            $("#popup").hide()
                            $(".modal-overlay").remove();
                        } else
                        {
                            $("#popup").hide()
                            $(".modal-overlay").remove();
                        }
                    }

                });
                //}


            }

            return flag;
        });

        $("#post_subcat_html,#post_end_cat_html").hide();
        $("#post_subcat_html").css({"display": "inline-block", "width": "140px", "margin-left": "10px"});

        $("#add_upload_file").click(function () {
            var boxnumber = 1 + Math.floor(Math.random() * 6);
            var another_image_upload_html =
                    '<div class="holder" id="upload_image_box_' + boxnumber + '">' + "\n" +
                    '<fieldset>' + "\n" +
                    '<legend>Upload Office File</legend>' + "\n" +
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
        /* function renumberRows() {
         $('#TextBoxesGroup tr').each(function(index, el){
         $(this).children('td').first().text(function(i,t){
         return index++;
         });
         });
         }
         $(".rmbtnn1").click(function () {
         var cnd = $(this).parents("tr .removetr").siblings().length;
         if(cnd >1){
         if ($(this).parents("tr .removetr").siblings().length && window.confirm('Delete this row ?')) {
         $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
         }
         }
         });
         renumberRows(); */
    $("#create_post").submit(function () {
        var date_of_incident    = jQuery.trim( jQuery("#datetimepicker1").val() );
        var time_of_incident    = jQuery.trim( jQuery("#datetimepicker2").val() );
        var place               = jQuery.trim( jQuery("#place").val() );
        var set_type3           = jQuery.trim( jQuery("#set_type3").val() );
        var division            = jQuery.trim( jQuery("#division").val() );
         var incident_type_id    = jQuery.trim( jQuery("#incident_type_id").val() );
        var checkedval          = 0;
        var inccatid            = $("#incident_category_id").val();
        var nature_of_injury_id = $("#nature_of_injury_id").val();
        var nature_of_injury_other = $("#nature_of_injury_other").val();
        var error_counter       = 0;
        jQuery(".errors").empty();
        if( division == undefined || division == "" ) 
        {
            jQuery("#division_error").html("<div class=\"errors\">Division is required.</div>");
            error_counter++;
        }
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
        
        $('.bodypart').each(function(index, elem) {
            if ($(this).prop("checked") == true){
            checkedval ++;
            }
        });
            
            if(inccatid != 1 && checkedval==0) {
                if( division == undefined || division == "" ) 
                {
                    jQuery("#division_error").html("<div class=\"errors\">Division is required.</div>");
                    error_counter++;
                }
                if( set_type3 == undefined || set_type3 == "" ) 
                {
                    jQuery("#set_type_error").html("<div class=\"errors\">Set type is required.</div>");
                    error_counter++;
                }
                if( date_of_incident == undefined || date_of_incident == "" ) 
                {
                    jQuery("#date_of_incident_error").html("<div class=\"errors\">Date of incident is required.</div>");
                    error_counter++;
                }
                if( incident_type_id == undefined || incident_type_id == "" ) 
                {
                    jQuery("#incident_type_id_error").html("<div class=\"errors\">Incident Type is required.</div>");
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
                $("#create_post").submit();
                $(".disablebutton").attr('disabled', true);
            }
            if( error_counter > 0 ) 
            {
                jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                jQuery('html, body').animate({ scrollTop: 0}, 'slow');
                return false;
            }
            
    });

    });

    function remove_upload_image_box(boxnumber) {
        jQuery("#upload_image_box_" + boxnumber).remove();
    }
   
    </script>
    <script type="text/javascript">    
    $( document ).ready(function() {
        $("#divnatureofinjuryidother").hide();
        $("#nature_of_injury_id").click(function (){
            var mydatalist = $(this).val();
            for (var i = 0; i < mydatalist.length; i++){
                if (mydatalist[i] === '12'){
                    var storage = "Y";
                } 
            }
            if(storage == "Y"){
                $("#divnatureofinjuryidother").show();
            } else {
                $("#divnatureofinjuryidother").hide();
            }
        }); 
    });  
    $( document ).ready(function() {
        $("#nearmiss").hide();
        $("#incident_category_id").change(function (){
        var mydatalist = $(this).val();//alert(mydatalist);
        for (var i = 0; i < mydatalist.length; i++) {
            if (mydatalist[i] === '1'){
                var storage = "Y";
            } 
        }
        if(storage == "Y"){
            $("#nearmiss").show();
        } else {
            $("#nearmiss").hide();
        }
        if(mydatalist == '1'){
            $("#exceptnearmiss").hide();
        }else{
            $("#exceptnearmiss").show(); 
        }
    }); 
    });  
    

    var dq=jQuery.noConflict();
    dq( function() {
        var ajax_data = {
            "action"	  : "getVenueList",
            "activity_type_id" : "<?php echo $activity_id; ?>"
            };
        dq.ajax({
            type: 'post', url: '<?php echo page_link("activity/add_incident.php"); ?>', cache: false,
            data: ajax_data,
            success: function (data) {
                if (data)
                {
                    var myVanueList = data.split(' | ');
                    dq( "#place" ).autocomplete({
                        minLength: 1,
                        source: myVanueList
                    }); 
                }
            }
        });
    });
  </script>
  <script>
      $( document ).ready(function() {
      $("#divParentIncidentId").hide();
      $("#divParentIncidentMapping").hide();
      jQuery(".dateIncidentPk").change(function(){
        var dateIncident = $(this).val();
        if(dateIncident != ''){
            $("#divParentIncidentMapping").show();
        }
        
        });
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
