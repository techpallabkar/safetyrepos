<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityController");
$controller->doAction();
$beanUi = $controller->beanUi;
$framework = $beanUi->get_view_data("framework");
$framework_value = $beanUi->get_view_data("framework_value");
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
$token_id = rand(000000, 111111);
?>
<style>
    .box{display: none;}
    
</style>
<div class="container1">
    <h1 class="heading">Add Activity : <span class="text-primary"><?php echo $activities; ?></span></h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div>
<?php echo $beanUi->get_error("status_id"); ?>
    <br />
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
                <table class="table table-bordered table-condensed" id="div_dept" style="float:left;width:70%;">
                    <tr id="division-department">
                        <td colspan="2">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"> <i class="fa fa-plus"></i> Add Division Department</button>
                        </td>
                    </tr>
                </table>
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog modal-lg">
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
                                    foreach ($post_division_department as $rowvalue)
                                    {
                                    if($rowvalue->parent_id == 1)
                                    {
                                    echo '<option value="'.$rowvalue->id.'">'.$rowvalue->name.'</option>';    
                                    }
                                    }
                                    ?>
                                </select>
                                </div>
                                <div id="level2"></div>
                                <div class="levelfour" style="margin:20px 0px 20px 0px;">
                                </div>
                                <div id="level3"></div>
                                <div id="level4"></div>
                                <div id="level5"></div>
                                <div id="level6"></div>
                                <div id="level7"></div>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $("#reset_button").click(function(){
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
                                                    "action"    : "get_nextlevel",
                                                    "id"        : level1,
                                                    "lcount"    : lc
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
                                            $("#L" + lcount).on('change', function () {
                                                var lc = lcount + 1;
                                                var level_id = $(this).val();
                                                var groupval;
													if(level_id == '251'  || level_id == '252')
													{
														if(level_id == '251')
														{
														groupval = '2';
														}
														if(level_id == '252')
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
													}
													else
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
																   if(sdd!=0)
																   {
																	$('#'+sdd).html('<span style="float:left;width:150px;padding-top: 6px;"><b>Other Name</b></span>'
																	+ '<input class="division" name=""  id="new_'+sdd+'" type="text" value="" style="width:23%;" />');
																	}
																	else 
																	{
																		$('#'+cc).html('');
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
 $('#'+closestids).hide();
                                            var error_count = 0;
                                            jQuery(".errors").empty();
                                      
                                            var contractorType = 0;
                                            $('.contractor_and_type :selected').each(function (j, selected) {
                                                if ($(this).val() != "") {
                                                    contractorType++;
                                                }
                                            });
                                            $('.newcons :selected').each(function (j, selected) {
                                                var errormsg = $(this).parent().parent().find("label").html();

                                                if (!isNaN(this.value)) {
                                                    if ((typeof contractorType !== 'undefined' && contractorType == 1)) {
                                                        $("#level_error").html("<div class=\"errors\">" + errormsg + " is required.</div>");
                                                        error_count++;
                                                        return false;
                                                    }
                                                }
                                            });

                                            if (error_count > 0) {
                                                jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                                                jQuery('html, body').animate({scrollTop: 0}, 'slow');
                                                return false;
                                            }

                                            var foo_value   = [];
                                            var foo_text    = [];
                                          
                                            $('.division :selected').each(function (i, selected) {
                                                
                                                   if (foo_value[i] != '') {
													   
													    if($(selected).val()!="")
												   {
                                                    foo_text.push($(selected).text());
                                                    foo_value.push($(selected).val());
												   }
                                                    
                                                    if($(this).text() == 'Others')
													{
														var ppp = $(this).data("other");
														var sss = $("#new_"+ppp).val();
															
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
                                                    "action"        : "submit_division",
                                                    "name"          : foo_text,
                                                    "tree_dept"     : "department",
                                                    "ids"           : foo_value
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
																if($(".set3").val())
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
                                                            var settype =  $(".set-type").val();
                                                            var temp;
															$(".set").show();															
															if (settype == 1)
															{
																temp = 'P-SET';
																
															} else if (settype == 2)
															{
																temp = 'C-SET';
															} else if (settype == 3)
															{
																temp = '';
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
                </select>
                <div id="set_type_error"><?php echo $beanUi->get_error('set_type'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="activity_no">Activity Number</label>
                <input type="text"  name="data[activity_no]" id="activity_no" value="<?php echo $beanUi->get_view_data("activity_no"); ?>" />
                <div id="activity_no_error"><?php echo $beanUi->get_error('activity_no'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="activity_no">Persons Present During Observation</label>
                <input type="text"  name="data[persons_present_during_observation]" id="persons_present_during_observation" value="<?php echo $beanUi->get_view_data("persons_present_during_observation"); ?>" />
                <div id="persons_present_during_observation_error"><?php echo $beanUi->get_error('persons_present_during_observation'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="activity_no">Date of Observation</label>
                <input type="text"  name="data[date_of_observation]" id="date_of_observation" class="datetimepicker" readonly value="<?php echo $beanUi->get_view_data("date_of_observation"); ?>" />
                <div id="date_of_observation_error"><?php echo $beanUi->get_error('date_of_observation'); ?></div>
            </div>
            <div id="showrelatedData"></div>
            <br />
            <div class="holder required">
                <label for="place">Venue</label>
                <input type="text" name="data[place]" id="place" value="<?php echo $beanUi->get_view_data("place"); ?>" />
                <div id="place_error"><?php echo $beanUi->get_error('place'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="activity_no">Framework</label>
                <?php
                foreach($framework as $rowval)
                {
                    $frameworkvalue = $framework_value[$rowval->id];
                    echo '<table style="width:70%;float:right;" class="table table-bordered">'
                    . '<thead><tr><th style="text-align:left;" colspan="3" class="bg-primary">'.$rowval->name.'</th></tr><thead>';
                    echo '<tr><th>SL.NO</th><th style="text-align:left;">DESCRIPTION</th><th>COUNT</th></tr>';
                    foreach($frameworkvalue as $key => $val)
                    {
                        echo '<tr>'
                        . '<td>'.($key+1).'</td>'
                        . '<td width="90%">'.$val->name.'</td>'
                        . '<td width="5%"><input type="hidden" name="framework_id[]" value="'.$val->id.'" min="1" value="" /><input type="number" class="framework_number" name="framework_count[]" min="1" value="" /></td>'
                                . '</tr>';
                    }
                    echo '</table>';
                }
                ?>
            </div>
            <br />
            <script type="text/javascript">
            $(document).ready(function(){
                 $('.invreq input[type="radio"]').click(function(){
                    if($(this).attr("value")=="Y"){
                        $(".box2").not(".red").hide();
                        $(".investigation_done").show();
                    }
                    if($(this).attr("value")=="N"){
                        $(".box2").not(".green").hide();
                        $(".investigation_done").hide();
                    }
                });
                $('.abcc input[type="radio"]').click(function(){
                    if($(this).attr("value")=="Y"){
                        $(".box").not(".red").hide();
                        $(".investigation").show();
                    }
                    if($(this).attr("value")=="N"){
                        $(".box").not(".green").hide();
                        $(".investigation").hide();
                    }
                });
            });
            </script>
            <div class="investigation_done holder box2 required abcc">
                <label for="investigation_done">Major Deviation</label>
                <input type="radio"  name="data[major_deviation]" id="major_deviation" value="Y" /> Yes
                <input type="radio"  name="data[major_deviation]" id="major_deviation" value="N" /> No
                <div id="investigation_done_error"><?php echo $beanUi->get_error( 'investigation_done' ); ?></div>
            </div>
            <br />
            <div class="investigation box" style="width:100%;">
            <div class="holder required">
                <label for="synopsis" style="float:left;">No. of Major Deviation</label>
                <table class="table table-hover table-bordered table-condensed" width="50%" style="float:left;width:70%;">
                    <tr>
                        <td >
                            <input type="text" readonly=""  id="pid" name="data[no_of_deviation]" min="1" placeholder="No. of Major Deviation" style="width:50%;"  />
                            <a class="js-open-modal btn btn-xs btn-success" href="#" data-modal-id="popup"> <i class="fa fa-plus"></i> Add Major Deviation</a> 
                                    <!--participants popup-->
                            <div id="popup" class="modal-box" style="height: 50%;">
                            <header> <a href="#" class="js-modal-close close">×</a>
                            <h3>Deviation Details</h3>
                            </header>
                            <div class="modal-body" style="height:62%;overflow-x: hidden;overflow-y: scroll;">
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        var counter = 1;
                                        $("#addButton").click(function () {
											var countingvalue = parseInt($("#valc").val()) + 1;
											$("#valc").val(countingvalue);
                                            var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'TextBoxDiv' + countingvalue).attr("class", 'removetr');
                                            newTextBoxDiv.after().html('<td>' + (countingvalue+1) + '</td>'
                                                    +'<td width="30%"><input style="width:100%;"type="text" class="req" id="description_' + countingvalue + '" placeholder="Description of Deviation"></td>'
                                                    +'<td width="15%"><select style="width:120px;float: left;" class="req" id="category_' + countingvalue + '"><option selected="selected" disabled="disabled">-select category-</option><option value="UA">UA</option><option value="UC">UC</option></select></td>'
                                                    +'<td width="70%"><select style="width:100px;float:left;" class="req complainces" id="action_taken_' + countingvalue + '"><option selected="selected" disabled="disabled">-select action-</option><option value="N">No</option><option value="Y">Yes</option></select>'
                                                    +'<div style="display:none;float:left;margin-left:10px;" class="showdiv" id="correction_' + countingvalue + '"><input style="width:180px;" type="text" id="correction_desc_' + countingvalue + '" placeholder="Correction Description" />'
                                                    +'&nbsp;<input type="text" style="width:180px;" readonly="" class="datetimepicker" id="correction_date_' + countingvalue + '" placeholder="Correction Date"></div></td>'
                                                    + '<td class="center"  align="center" width="5%"><a class="btn btn-danger btn-sm rmbtnn" id="removeButton_' + countingvalue + '" name="delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>');
                                            $("#nof").val(counter+1);
                                            newTextBoxDiv.appendTo("#TextBoxesGroup");
                                            counter++;
                                            
                                            $(".rmbtnn").show();   
                                                                
                                                function renumberRows() {
                                               
                                                    $('#TextBoxesGroup tr').each(function(index, el){
                                                        $(this).children('td').first().text(function(i,t){
                                                          
                                                            return index++;
                                                        });
                                                    });
                                                }
                                                $(".rmbtnn").click(function () {

                                                    var cnd = $(this).parents("tr .removetr").siblings().length;
                                                   if(cnd >1){
                                                    if ($(this).parents("tr .removetr").siblings().length && window.confirm('Delete this row ?')) {
														var nofval = $('#nof').val();
														$('#nof').val(nofval -1);
														counter--;
                                                       $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
                                                        }
                                                    } 
                                                });
                                                renumberRows();
                                            
                                            $('.complainces').change(function(){
                                                if($(this).val() =="Y"){
                                                    $(this).nextAll('.showdiv').show();
                                                }else{
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
												var nofval = $('#nof').val();
												$('#nof').val(nofval -1);
												counter--;
											   $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
												}
											}
										   });
										renumberRows();
                                        
                                        
                                         $('.complainces').change(function(){
                                                if($(this).val() =="Y"){
                                                    $(this).nextAll('.showdiv').show();
                                                }else{
                                                    $(this).nextAll('.showdiv').hide();
                                                }
                                            });
                                   });
                            </script>
                            <table id='TextBoxesGroup<?php echo $prow->id;?>' class="table table-bordered">
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Description of Deviation</th>
                                    <th>Category</th>
                                    <th>Action Taken</th>
                                </tr>
                                 <tr id="TextBoxDiv0" class="removetr">
                                    <td>1</td>
                                    <td width="30%"><input style="width:100%;" class="req"  type='text' id='description_0' placeholder='Description of Deviation' ></td>
                                    <td width="15%">
                                        <select style="width:120px;float: left;" class="req" id="category_0">
                                            <option selected="selected" disabled="disabled">-select category-</option>
                                            <option value="UA">UA</option>
                                            <option value="UC">UC</option>
                                        </select>
                                    </td>
                                    <td width="70%">
                                        <select style="width:100px;float: left;" class="req complainces" id="action_taken_0">
											<option selected="selected" disabled="disabled">-select action-</option>
                                            <option value="N">No</option>
                                            <option value="Y">Yes</option>
                                        </select>
                                        <div id="compliance_0" class="showdiv" style="display:none;float: left;margin-left: 10px;">
                                            <input style="width:180px;" type="text" id="correction_desc_0" placeholder="Correction Description" />
                                            <input style="width:180px;" type="text" id="correction_date_0" readonly="" class="datetimepicker" placeholder="Correction Date">
                                        </div>
                                    </td>
                                    <td align="center" width="5%"><a ID="dataremove" name="delete" class="btn btn-danger btn-sm rmbtnn1"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>
                                </tr>
                            </table>
                            </div>
                                <footer style="margin-left:0px;"> 
                                    <input type='hidden' id="nof" name="finding_count" value="1" style="float:left;">
                                    <input type='hidden' id="valc" name="finding_count2" value="0" style="float:left;">
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
                $(document).ready(function(){
                    $('input[type="checkbox"]').click(function(){
                        if($(this).attr("value")=="<?php echo $prow->id;?>"){
                            $(".<?php echo $prow->id;?>").toggle();
                            $('#pid_<?php echo $prow->id;?>').prop('disabled', false);
                        }
                        });
                        //called when key is pressed in textbox
                        $("#pid_<?php echo $prow->id;?>").keypress(function (e) {
                        //if the letter is not digit then display error and don't type anything
                        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                            //display error message
                            //$("#errmsg_<?php echo $prow->id;?>").html("Digits Only").show().fadeOut("slow");
                            alert("Accept Only Numbers");
                            return false;
                        }
                        });
                });
            </script>
                </table>
                <div id="participant_cat_id_error" class="clearfix"><?php echo $beanUi->get_error( 'participant_cat_id' ); ?></div>
            </div>
            <br />
    </div>
    <br>
           
            <div class="holder required">
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
                            if( ! empty( $post_status ) ) {
                                if($role_id == 1)
                                {
                                    $status 	= array("Draft", "Final Submit");
                                }
                                else if($role_id == 2)
                                {
                                    $status 	= array("Draft", "Final Submit");
                                }
                                else if($role_id == 3)
								{
									$status 	= array("Draft", "Final Submit");
								}
                                    $status_id 		= $beanUi->get_view_data( "status_id" );
                                    foreach( $post_status as $statusrow ) {

                                            if( in_array( $statusrow->status_name, $status ) ) {
                                                    echo '<option value="'.$statusrow->id.'">'.$statusrow->status_name.'</option>'."\n";
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
    jQuery('.datetimepicker').datetimepicker({
	
        timepicker:false,
	scrollMonth : false,
	scrollInput : false,
	format:'Y-m-d',
	step:5
    });
    jQuery('.datetimepicker_for_time').datetimepicker({
	
        datepicker:false,
	scrollMonth : false,
	scrollInput : false,
	format:'H:i',
	step:5
    });
    
    
    $("#date_of_observation").on("change", function (e) {
		 
		 var selected_date = $('#date_of_observation').val();
		 
		 var ajax_data = {
					"action"			: "getDataByDate",
					"selected_date"		: selected_date,
					"activity_id" 		: <?php echo $activity_id; ?>,
					"table_name"		: "safety_observation_line_function_view",
				};
				$.ajax({
					type: 'post', 
					cache: false,
					data: ajax_data,
					success: function (getDataByDate) {
						if(getDataByDate)
						{
							$("#showrelatedData").html(getDataByDate);
						}
						return false;
					}
				});
           return false;
        });
    
    /*Datepicker*/



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

    jQuery(document).ready(function ($) {




        $('.savebtn').on('click', function (e) {
            var flag = true;
            $('.req').each(function () {
                if(jQuery.trim($(this).val()) == ''){
                   // alert("Please fill all boxes");
                 
					$(".req").css("border-color","red"); 	
					
                   flag = false;
                
                }
                
                return flag;
            });
            
            $('.req').each(function () {
                if(jQuery.trim($(this).val()) != ''){
					$(this).css("border-color","#999"); 
                }
            });
            		 
      if(flag == true) {
          
            e.preventDefault();
                var token_id = $("#token_id").val();
                var description = Array();
                var category = Array();
                var action_taken = Array();
                var correction_desc = Array();
                var correction_date = Array();
               // var no_of_parti = $("#nof").val();
                var no_of_parti     = $("#valc").val();

                //if (no_of_parti > 0)
                //{
                    
                    for (var row_no = 0; row_no <= no_of_parti; row_no++)
                    {
                        description[row_no] = $.trim($("#description_"+ row_no).val());
                        category[row_no] = $.trim($("#category_"+ row_no).val());
                        action_taken[row_no] = $.trim($("#action_taken_"+ row_no).val());
                        correction_desc[row_no] = $.trim($("#correction_desc_"+ row_no).val());
                        correction_date[row_no] = $.trim($("#correction_date_"+ row_no).val());
                    }
                   
                    var ajax_data = {
                        "action": "save_deviation_line_function",
                        "token_id": token_id,
                        "description": description,
                        "category": category,
                        "action_taken": action_taken,
                        "correction_desc": correction_desc,
                        "correction_date": correction_date,
                        "nof": no_of_parti
                    };
                    $.ajax({
                        type: 'post', 
                        cache: false,
                        data: ajax_data,
                        success: function (save_deviation_line_function) {
                            if(save_deviation_line_function)
                            {
                                $("#pid").val(save_deviation_line_function);
                                $("#popup").hide()
                                $(".modal-overlay").remove();
                                // return true;
                            }
                            else
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
                    "id": 0
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
                    '<legend>Upload File</legend>' + "\n" +
                    '<label for="image_path">Upload File</label>' + "\n" +
                    '<input type="file" name="file_path[]" id="file_path" />' + "\n" +
                    '<input type="button" value="Remove" class="btn btn-danger btn-sm" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
                    '<br />' +
                    '<label for="caption">Image Caption</label>' + "\n" +
                    '<input type="text" name="image_captions[]" id="image_captions"  placeholder="Image Caption" />' + "\n" +
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


        $("#create_post").submit(function () {

//            var activity_no = jQuery.trim(jQuery("#activity_no").val());
//            var activity_month = jQuery.trim(jQuery("#activity_month").val());
//            var activity_year = jQuery.trim(jQuery("#activity_year").val());
//            var activity_count = jQuery.trim(jQuery("#activity_count").val());
//            var division = jQuery.trim(jQuery("#division").val());

            var error_counter = 0;
            jQuery(".errors").empty();
//            if (division == undefined || division == "")
//            {
//                jQuery("#division_error").html("<div class=\"errors\">Division is required.</div>");
//                error_counter++;
//            }
//            if (activity_no == undefined || activity_no == "")
//            {
//                jQuery("#activity_no_error").html("<div class=\"errors\">Audit no is required.</div>");
//                error_counter++;
//            }
//
//            if (activity_month == undefined || activity_month == "")
//            {
//                jQuery("#activity_month_error").html("<div class=\"errors\">Activity month is required.</div>");
//                error_counter++;
//            }
//            if (activity_year == undefined || activity_year == "")
//            {
//                jQuery("#activity_year_error").html("<div class=\"errors\">Activity year is required.</div>");
//                error_counter++;
//            }
//            if (activity_count == undefined || activity_count == "")
//            {
//                jQuery("#activity_count_error").html("<div class=\"errors\">Activity count is required.</div>");
//                error_counter++;
//            }

            if (error_counter > 0) {
                jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                jQuery('html, body').animate({scrollTop: 0}, 'slow');
                return false;
            }
        });
    });

    function remove_upload_image_box(boxnumber) {
        jQuery("#upload_image_box_" + boxnumber).remove();
    }

//accept only numbers
    $(".framework_number").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            alert("Accept Only Numbers");
            return false;
        }
    });

</script>


</body>
</html>
