<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$controller = load_controller( "ActivityController" );
$controller->doAction();
$beanUi 			= $controller->beanUi;
$post_categories 	= $beanUi->get_view_data( "post_categories" );
$post_status 		= $beanUi->get_view_data( "post_status" );
$post_uploads 		= $beanUi->get_view_data( "post_uploads" );

$auth_user_id 		= $controller->get_auth_user("id");
$activity_id 		= $beanUi->get_view_data( "id" );
$post_category_id 	= $beanUi->get_view_data( "post_category_id" );
$status_id 		= $beanUi->get_view_data( "status_id" );
$framework = $beanUi->get_view_data("framework");
$framework_value = $beanUi->get_view_data("framework_value");
$getframework_value = $beanUi->get_view_data("getframework_value");

$deviation_rows = $beanUi->get_view_data("deviation");

$post_participants_categories= $beanUi->get_view_data( "post_participants_categories" );
$post_division_department= $beanUi->get_view_data( "post_division_department" );
$post_activity_type_master= $beanUi->get_view_data( "post_activity_type_master" );
$activity_type_id = $beanUi->get_view_data("post_activity_type_id");
$cat_id = $beanUi->get_view_data("cat_id");
$participants_list = $beanUi->get_view_data("participants_list");

$post_division_department_mapping = $beanUi->get_view_data("post_division_department_mapping");
$devition_names = $beanUi->get_view_data("devition_names");
$activities = get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);
$controller->setCss(array( "cropimage/css/imgpicker" ));
$controller->setCss("tree");
$controller->get_header();

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
                <br />
                <div class="panel" style="padding:20px;">
                        <form name="edit_post" id="edit_post" action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="data[activity_type_id]" value="<?php echo $activity_type_id;?>" />
                                        <div class="holder">
                                                <label for="title">Activity</label>
                                                <b class="text-danger"> <?php echo $activities; ?> </b>                       
                                        </div>
                                        <br />
                                        <div class="holder required" >
                                                <label for="synopsis" style="float:left;">Division – Department</label>
                                                <table id="div_dept" class="table table-hover table-bordered table-condensed" width="50%" style="float:left;width:70%;">
                                                                <?php
                                                                 if(count($devition_names) > 0) { 
                                                                if( !empty ( $devition_names ) )
                                                                {
                                                                    $j=0;
                                                                    $valxx = array();
                                                                    echo '<tr><td colspan="2"><b class="text-danger">Selected Division - Departments : </b><br>';
                                                                    foreach($devition_names as $key => $ddmrow)
                                                                        {
                                                                        $j = $j+1;
                                                                        echo $j.'. <b>'.$ddmrow.'</b>&nbsp;&nbsp;';
                                                                        ?>
                                                                        <a href="edit_safety_observation_line_function.php?action=delete_division&t=<?php echo base64_encode("safety_observation_line_function_division_mapping"); ?>&id=<?php echo $activity_id; ?>&delid=<?php echo $key; ?>" onclick="return confirm('Are you sure want to delete this?');">
                                                                            <img width="8px" src="<?php echo url("assets/images/delete.gif");?>" />
                                                                        </a>
                                                                        <br>
                                                                        <?php

                                                                        }
                                                                    echo "</td></tr>";
                                                                }
                                                                 }
                                                                ?>
                                                        <?php if(count($devition_names) == 0) { ?>
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
                                <span style="float:left;width:150px;padding-top: 6px;"><b>Division</b></span>
                                <select class="division" name="L1" id="L1">
                                    <option  value="" selected="">-Choose one-</option>
										<?php
										foreach ($post_division_department as $rowvalue) {
											if ($rowvalue->parent_id == 1) {
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
																$('.division').val('');
																$('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();  
																
																
																});
															
														
                                                        $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                                        var lcount = 1;
                                                        $("#L"+lcount).on('change',function(){
                                                        $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();

                                                        var level1 = $(this).val();
                                                        var lc = lcount+1;
                                                        $.ajax({
                                                        type: 'post',
                                                        data: {
                                                        "action" : "get_nextlevel",
                                                        "id"    : level1,
                                                        "lcount": lc
                                                        },
                                                        cache:false,
                                                        success:function (get_nextlevel)  { 

                                                        if(get_nextlevel)
                                                        {  
                                                        division_department_treeview(lc,level1,get_nextlevel,tbname = null);
                                                        } 
                                                        }
                                                        });
                                                        });

                                                        /*tree function Start*/
                                                        function division_department_treeview(lcount,ids,get_nextlevel,tb = null) {
                                                        $("#level"+lcount).html(get_nextlevel);
                                                        $("#level"+lcount).css("margin-top","20px"); 
                                                        $("#level"+lcount).show();
                                                        $("#L"+lcount).on('change',function(){
                                                        var lc = lcount+1;
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
																 "action" : "get_nextlevel",
																  "id"    : level_id,
																  "lcount": lc
															  },
															  cache:false,
															  success:function (get_nextlevel)  { 

																division_department_treeview(lc,level_id,get_nextlevel,tb = null);

																$(".newcons").on('change',function(){
																   // alert($(this).find(':selected').data("location"));
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
$('.set').hide();
                                                        /*tree Save Start*/
                                                        $('#btnSave').click(function() {
$('#division-department').hide();

                var closestids = $('#closestid').val();
				var level = $("#L1").find("option:selected").val();
				var level_2 = $("#L2").find("option:selected").val();
				var last_level = $(".newcons").val();
				$('#'+closestids).hide();

                var error_count = 0;
                jQuery(".errors").empty();
               

               var contractorType = 0;
                $('.contractor_and_type :selected').each(function(j, selected) { 
                    if( $(this).val() != ""){
                        contractorType++;
                    }
                });
                $('.newcons :selected').each(function(j, selected) { 
                var errormsg = $(this).parent().parent().find("label").html();                

                if(!isNaN(this.value) ) {
                    if(( typeof contractorType !== 'undefined' &&  contractorType == 1 )){
                    $("#level_error").html("<div class=\"errors\">"+errormsg+" is required.</div>");
                    error_count++; 
                    return false;
                }
                    }
                });

                if( error_count > 0 ) {
                        jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                        jQuery('html, body').animate({ scrollTop: 0}, 'slow');
                        return false;
                }


               /* $("select").prop("required",true);
                alert("dfdf");
                return false;*/

                                                        var foo_value = []; 
                                                        var foo_text = [];
                                                        $('.division :selected').each(function(i, selected){ 

                                                        
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
                                                        "action"    : "submit_division",
                                                        "name"      : foo_text,
                                                        "tree_dept" : "department",
                                                        "ids"       : foo_value
                                                        },
                                                        cache:false,
                                                        success:function (submit_division)  { 
                                                        if(submit_division)
                                                        {  
                                                                var getIdMod = submit_division.substr(9,50);
                                                                var getId = getIdMod.substr(0,getIdMod.indexOf('">'));                                                                
                                                                var gtid = replaceAll(getId, ":", "_"); 

                                                                $('#div_dept').append(submit_division+'<td><a id="sp'+gtid+'"  class="delete_row" style="cursor:pointer;" >Delete</a></td></tr>');   
                                                                $('#sp'+gtid).on('click',function() { 
                                                                    var conf = confirm("Are you sure to delete this record");
                                                                    if(conf){
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
                                                                    }else{
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
                                            <div id="post_category_id_error"><?php echo $beanUi->get_error( 'post_category_id' ); ?></div>
                                        </div>
                                        <div class="holder" id="extra_division"></div>
                                         <?php  if(count($devition_names) > 0) { 
                                                    
                                                	foreach( $devition_names AS $devrow )
														{
															
																echo '<div class="holder required"><label for="incident_no">Set Type</label>
																<select name="data[set_type]" id="set_type3" class="set3">
																<option value="">select one</option>
																<option value="P-SET" ' . ($beanUi->get_view_data("set_type") == "P-SET" ? "selected" : "") . '>P-SET</option>
																<option value="C-SET" ' . ($beanUi->get_view_data("set_type") == "C-SET" ? "selected" : "") . '>C-SET</option>
																<option value="PC-SET" ' . ($beanUi->get_view_data("set_type") == "PC-SET" ? "selected" : "") . '>(P+C)-SET</option>
																</select></div>';
															
														}
                                                }
                                                else
                                                {
                                                    echo '<div class="holder required set"><label for="incident_no">Set Type</label>     
                                                    <select name="data[set_type]" id="set_type3" class="set3">
                                                    <option value="">select one</option>
                                                    <option value="P-SET">P-SET</option>
                                                    <option value="C-SET">C-SET</option>
                                                    <option value="PC-SET">(P+C)-SET</option>
                                                    </select>
                                                    <div id="incident_no_error">'.$beanUi->get_error( 'set_type' ).'</div>    
                                                    </div>';
                                                }
                                                ?>
                                        <br>
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
                <input type="text"  name="data[date_of_observation]" id="date_of_observation" class="datetimepicker" value="<?php echo $beanUi->get_view_data("date_of_observation"); ?>" />
                <div id="date_of_observation_error"><?php echo $beanUi->get_error('date_of_observation'); ?></div>
            </div>
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
               
                $index_array = array();
foreach ($getframework_value as $k => $vals) {
    $index_array[$vals->framework_id]["fmid"] = $vals->framework_id;
    $index_array[$vals->framework_id]["fcount"] = $vals->framework_count;
}
//show($index_array);
                foreach($framework as $rowval)
                {
                   
                    $frameworkvalue = $framework_value[$rowval->id];
                    echo '<table style="width:70%;float:right;" class="table table-bordered">'
                    . '<thead><tr><th style="text-align:left;" colspan="3" class="bg-primary">'.$rowval->name.'</th></tr><thead>';
                    //echo '<pre>';
                    echo '<tr><th>SL.NO</th><th style="text-align:left;">DESCRIPTION</th><th>COUNT</th></tr>';
                    foreach($frameworkvalue as $key => $val)
                    {
                        
                        if ($val->id== $index_array[$val->id]["fmid"]) {
                            $fcount = $index_array[$val->id]["fcount"];
                          
                        } else {
                            $fcount = "";
                          
                        }
                       // echo $fcount;
                        echo '<tr>'
                        . '<td>'.($key+1).'</td>'
                        . '<td width="90%">'.$val->name.'</td>'
                        . '<td width="5%"><input type="hidden" name="framework_id[]" value="'.$val->id.'" min="1" value="" /><input type="number" name="framework_count[]" min="1" value="'.$fcount.'" /></td>'
                                . '</tr>';
                    }
                    //print_r($value);
                    echo '</table>';
                   
                }
                ?>
            </div>
            <br />

               
                                <br />
<!--            <script type="text/javascript">
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
            </script>-->
            <script type="text/javascript">
                $(document).ready(function () {

<?php if ($beanUi->get_view_data("major_deviation") == 'N') {
    ?>
                        $(".box").hide();
                      

<?php } ?>
<?php if ($beanUi->get_view_data("major_deviation") == 'Y') {
    ?>
                        $(".box").show();
                      

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
            <div class="investigation_done holder box2 required abcc">
                <label for="investigation_done">Major Deviation</label>
                <input type="radio"  name="data[major_deviation]" id="major_deviation" value="Y" <?php
                       if ($beanUi->get_view_data("major_deviation") == 'Y') {
                           echo "checked";
                       }
                       ?> /> Yes
                <input type="radio"  name="data[major_deviation]" id="major_deviation" value="N" <?php
                       if ($beanUi->get_view_data("major_deviation") == 'N') {
                           echo "checked";
                       }
                       ?> /> No
                <div id="investigation_done_error"><?php echo $beanUi->get_error( 'investigation_done' ); ?></div>
            </div>
            <br />
            <div class="investigation box" style="width:100%;">
           
            <div class="holder required">
                <label for="synopsis" style="float:left;">No. of Major Deviation</label>
                <table class="table table-hover table-bordered table-condensed" width="50%" style="float:left;width:70%;">
                    <tr>
                        <td >
                            <input type="text" readonly=""  id="pid" name="data[no_of_deviation]" value="<?php echo $beanUi->get_view_data( "no_of_deviation" ); ?>"  min="1" placeholder="No. of Major Deviation" style="width:50%;"  />
                            <a class="js-open-modal btn btn-xs btn-success" href="#" data-modal-id="popup"> <i class="fa fa-plus"></i> Edit Major Deviation</a> 
                                    <!--participants popup-->
                            <div id="popup" class="modal-box" style="height: 50%;">
                            <header> <a href="#" class="js-modal-close close">×</a>
                            <h3>Deviation Details</h3>
                            </header>
                            <div class="modal-body" style="height:62%;overflow-x: hidden;overflow-y: scroll;">
                                <script type="text/javascript">
                                            $(document).ready(function () {
                                                var counter = <?php echo count($deviation_rows); ?> + 1;
                                                $("#addButton").click(function () {

                                                    var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'TextBoxDiv' + counter).attr("class", 'removetr cls');
                                                    newTextBoxDiv.after().html('<td align="center">' +(counter+1)+'</td>'

                                                                +'<td width="30%"><input style="width:100%;"type="text"  class="req" id="description_' + counter + '" placeholder="Description of Deviation"></td>'
                                                                +'<td width="15%"><select style="width:120px;float: left;"  class="req" id="category_' + counter + '"><option selected="selected" disabled="disabled">-select category-</option><option value="UA">UA</option><option value="UC">UC</option></select></td>'
                                                                +'<td width="70%"><select style="width:100px;float:left;" class="req complainces" id="action_taken_' + counter + '"><option selected="selected" disabled="disabled">-select action-</option><option value="N">No</option><option value="Y">Yes</option></select>'
                                                                +'<div style="display:none;float:left;margin-left:10px;" class="showdiv" id="compliance_' + counter + '"><input style="width:180px;" type="text" id="correction_desc_' + counter + '" placeholder="Correction Description" />'
                                                                +'&nbsp;<input type="text" style="width:180px;" readonly="" class="datetimepicker" id="correction_date_' + counter + '" placeholder="Correction Date"></div></td>'
                                                                + '<td class="center" align="center" width="25%"><a class="btn btn-danger btn-sm rmbtnn2" id="removeButton' + counter + '" name="delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>');
															function renumberRows() {
															$('#TextBoxesGroup tr').each(function(index, el){
																$(this).children('td').first().text(function(i,t){
																	return index++;
																});
															});
															}
															renumberRows();
														
														
                                                       $("#nof").val(counter + 1);
                                                       $(".rmbtnn").show();                                                         
                                                        newTextBoxDiv.appendTo("#TextBoxesGroup");
                                                        counter++;
                                                        
                                                        
                                                        jQuery('.datetimepicker').datetimepicker({
	
                                                        timepicker:false,
                                                        scrollMonth : false,
                                                        scrollInput : false,
                                                        format:'Y-m-d',
                                                        step:5
                                                        });
                                        
                                                    
														$('.complainces').change(function () {
															if ($(this).val() == "Y") {
																$(this).nextAll('.showdiv').show();
															} else {
																$(this).nextAll('.showdiv').hide();
															}
														});
                                                    
                                                    	$(".rmbtnn2").click(function () {
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
                                                    
                                                    
                                                    
                                                });
                                                
                                                $('.complainces').change(function () {
															if ($(this).val() == "Y") {
																$(this).nextAll('.showdiv').show();
															} else {
																$(this).nextAll('.showdiv').hide();
															}
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
                                              
                                                
                                                
												$(".rmbtnn").click(function () {
        
													var action = $(this).data('action');
													var id = $(this).data('acid');
													$("#loaderIcon").show();
													var queryString;
													// alert(action);
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
														case "linefunc_deviation_delete":
														var cnd = $(this).parents("tr .removetr").siblings().length;
														if(cnd > 1)
														{
															if(confirm("Are you sure want to delete this?"))
															{ 
																queryString = 'action=' + action + '&line_function_id=' + <?php echo $activity_id; ?> + '&message_id=' + id;
																var nofval = $('#nof').val();
																$('#nof').val(nofval -1);
																var pidval = $('#pid').val();
																$('#pid').val(pidval -1);
																counter--;
																$.when($(this).parents("tr .removetr").remove()).then(renumberRows);
															}
															else
															{
																return false;
															}
														}
														else
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
																case "linefunc_deviation_delete":
																	$('#message_' + id).fadeOut();
																	break;
															}
															$("#txtmessage").val('');
															$("#loaderIcon").hide();
														},
														error: function () {}
													});
												});  
												
												
				/***<!--insert deviation***/	
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
                var button_id = $(this).attr("id");
                //save_pdetails_3



                var token_id = $("#token_id").val();
                var description = Array();
                var category = Array();
                var action_taken = Array();
                var correction_desc = Array();
                var correction_date = Array();
                var no_of_parti = $("#nof").val();

                if (no_of_parti > 0)
                {
                    for (var row_no = 0; row_no <= no_of_parti; row_no++)
                    {

                        description[row_no] = $.trim($("#description_"+ row_no).val());
                        category[row_no] = $.trim($("#category_"+ row_no).val());
                        action_taken[row_no] = $.trim($("#action_taken_"+ row_no).val());
                        correction_desc[row_no] = $.trim($("#correction_desc_"+ row_no).val());
                        correction_date[row_no] = $.trim($("#correction_date_"+ row_no).val());

                    }

                    var ajax_data = {
                        "action": "save_deviation_line_function_edit",
                        "token_id": token_id,
                        "description": description,
                        "category": category,
                        "action_taken": action_taken,
                        "correction_desc": correction_desc,
                        "correction_date": correction_date,
                        "nof": no_of_parti,
                        "line_function_id": <?php echo $activity_id; ?>
                        

                    };

                    $.ajax({
                        type: 'post',
                        cache: false,
                        data: ajax_data,
                        success: function (save_deviation_line_function_edit) {
                             //alert(save_deviation_line_function_edit);
                             if (save_deviation_line_function_edit)
                            {
								var elements = $("#TextBoxesGroup tr.cls").length;
								if(elements > 0 )
								{
									//alert("yes");
									$('#TextBoxesGroup tr.cls:first').before(save_deviation_line_function_edit);
									var idss = $('.total_count').last().val();
								}
								else
								{
									//alert("no");
									$('#TextBoxesGroup tr:first').append(save_deviation_line_function_edit);
									var idss = $('.total_count2').last().val();
									
								}
								$("#pid").val(idss);
								
											function renumberRows() {
												$('#TextBoxesGroup tr').each(function(index, el){
													$(this).children('td').first().text(function(i,t){
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
												
												case "linefunc_deviation_delete":
												var cnd = $(this).parents("tr .removetr").siblings().length;
												if(cnd > 1)
												{
													if(confirm("Are you sure want to delete this?"))
													{
														
														queryString = 'action=' + action + '&line_function_id=' + <?php echo $activity_id; ?> + '&message_id=' + id;
														var nofval = $('#nof').val();
														$('#nof').val(nofval -1);
														var pid = $('#pid').val();
														$('#pid').val(nofval -1);
														counter--;
														$.when($(this).parents("tr .removetr").remove()).then(renumberRows);
													}
													else
													{
														return false;
													}
												}
												else
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
														case "linefunc_deviation_delete":
															$('#message_' + id).fadeOut();
															break;
													}
													$("#txtmessage").val('');
													$("#loaderIcon").hide();
												},
												error: function () {}
											});
											
									});
								
                                
									
								
                                $('.cls').each(function(){
										$(this).remove();
									});
                                $("#popup").hide();
                                $(".modal-overlay").remove();
                            }
                        }

                    });
                }
                else
                {
					$("#popup").hide()
                                $(".modal-overlay").remove();
					return false;
				}

            } 
            
            return flag;

        });
				
				/***insert deviation-->***/							
												
												
												  
                                            });
                                        </script>
                               
                            <table id='TextBoxesGroup<?php echo $prow->id;?>' class="table table-bordered">
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Description of Deviation</th>
                                    <th>Category</th>
                                    <th>Action Taken</th>
                                </tr>
            <?php
            if( count($deviation_rows) )
            {
                $f = 0;
               foreach($deviation_rows as $row)
               {
                   $f = $f+1;
                    ?>
                            <tr class="removetr" id="message_<?php echo $row->id; ?>">

                                    <td align="center" width="10%"><?php echo $f; ?></td>
                                    <td align="center" width="30%"><?php echo $row->description; ?></td>
                                    <td align="center" width="30%"><?php echo $row->category; ?></td>
                                    <td align="center" width="60%">
                                        <?php 
                                            if($row->action_taken == 'Y') 
                                                { 
                                                echo "Yes"; 
                                                echo "<br><b>Correction Description </b>: ".$row->correction_desc;
                                                echo "<br><b>Correction Date </b>: ".$row->correction_date;
                                                } 
                                            else 
                                                { 
                                                echo "No"; 
                                                } 
                                                ?></td>
                                    <td align="center" width="25%"><a ID="dataremove" class="btn btn-danger btn-sm rmbtnn" name="delete" class="rmbtnn" data-acid="<?php echo $row->id; ?>" data-action="linefunc_deviation_delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>
                            </tr>
                    <?php
                    }
            }
            ?>
                                <tr id="TextBoxDiv0" class="removetr cls">
                                    <td align="center">1</td>
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
                                    <td align="center" width="25%"><a ID="dataremove" name="delete" class="btn btn-danger btn-sm rmbtnn1"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>
                                </tr>
                            </table>
                            </div>
                                <footer style="margin-left:0px;"> 
                                    <input type='hidden' id="nof" name="finding_count" value="<?php echo count($deviation_rows)+ 1; ?>" style="float:left;">
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
                <br />         
                <div class="holder">
                                                <label for="status_id">Status</label>
                                                <select name="data[status_id]" id="status_id">
                                                        <?php
                        $created_by = $beanUi->get_view_data("created_by");
                        $role_id = $controller->get_auth_user("role_id");
                        if (!empty($post_status)) {
                        $normaluser = array("Draft", "Final Submit");
                        $adminapprove = array("Final Submit","Approve","Refferred for Correction");
                        $admin_status = array("Approve","Published", "Unpublished");
                        $published_status = array("Published", "Unpublished");
                        $status_id = $beanUi->get_view_data("status_id");
                              
                        foreach ($post_status as $statusrow) {
                            if($status_id == $statusrow->id)
                            {
                                $selected= "selected";
                            }
                            else
                            {
                                $selected= "";
                            }
                            
                            if($role_id == 1 && $status_id == 1 && in_array( $statusrow->status_name, $normaluser ))
                            {
                              
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }    
                            else if($role_id == 1 && $status_id == 2 && in_array( $statusrow->status_name, $adminapprove ))
                            {
                             
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 1 && $status_id == 5 && in_array( $statusrow->status_name, $admin_status ))
                            {
                               
                                echo '<option value="'.$statusrow->id.'" '.$selected.'>'.$statusrow->status_name.'</option>'."\n";
                            }
                            if($role_id == 3 && $status_id == 1 && in_array( $statusrow->status_name, $normaluser ))
                            {
                              
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }    
                            else if($role_id == 3 && $status_id == 2 && in_array( $statusrow->status_name, $adminapprove ))
                            {
                             
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 3 && $status_id ==3 && in_array( $statusrow->status_name, $published_status ))
                            {
                             
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 3 && $status_id ==4 && in_array( $statusrow->status_name, $published_status ))
                            {
                             
                                echo '<option value="'.$statusrow->id.'" '.$selected.' >'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 3 && $status_id == 5 && in_array( $statusrow->status_name, $admin_status ))
                            {
                               
                                echo '<option value="'.$statusrow->id.'" '.$selected.'>'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 2 && in_array( $statusrow->status_name, $normaluser ))
                            {
                                
                                echo '<option value="'.$statusrow->id.'" '.$selected.'>'.$statusrow->status_name.'</option>'."\n";
                            }
                            else if($role_id == 1 && ($status_id == 3 OR $status_id == 4) && in_array( $statusrow->status_name, $published_status ))
                            {
                                echo '<option value="'.$statusrow->id.'" '.$selected.'>'.$statusrow->status_name.'</option>'."\n";
                            }
                        }
                    }

?>
                                                </select>
                                                <?php echo $beanUi->get_error( 'status_id' ); ?>
                                        </div>
                                        <br />
                                        <div class="holder required">
                                                <label for="author">Created by</label><?php echo $beanUi->get_view_data( 'created_by_name' ); ?>
                                        </div>
                                        <br />
                                        <div class="holder required">
                                                <label for="author">Modified Date</label><?php echo $beanUi->get_view_data( 'modified_date' ); ?>
                                        </div>
                                        <br />
    <div class="holder">
            <input type="submit" value="Update" class="btn btn-smbtn btn-sm btn-primary" />
            <a href="index.php?activity_id=<?php echo $activity_type_id;?>" class="btn btn-sm btn-danger">Cancel</a>
            <input type="hidden" name="_action" value="update" />
            <input type="hidden" name="data[id]" id="post_id" value="<?php echo $beanUi->get_view_data( "id" ); ?>" />
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
});
</script>

<div id="tracking_post_detail"></div>
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css")?>"/>

<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js")?>"></script>
<script type="text/javascript">

jQuery.datetimepicker.setLocale('en');

jQuery('.datetimepicker').datetimepicker({

        timepicker:false,
        scrollMonth : false,
        scrollInput : false,
        format:'Y-m-d',
        step:5
});
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
                    // alert(row_id);
                    text2 += '<input style="width:80%;" type="text" name="emp_code" id="emp_code" placeholder="Employee Code" />';
                }
                /*  text2 += '<td width="25%"> <input style="width:80%;" type="text" name="name" id="name_'+row_id+'_'+i+'" placeholder="Employee Name" /></td>';
                 text2 += '<td width="25%"><input style="width:80%;" type="text" name="designation" id="designation_'+row_id+'_'+i+'"  placeholder="Designation" /></td>';
                 text2 += '<td width="25%"><input style="width:80%;" type="text" name="department" id="department_'+row_id+'_'+i+'"  placeholder="Department" /></td>'; 
                 */
                text2 += '<button id="btnAddAction" name="submit" onClick="callCrudAction("p_add","")">Add</button>';

                text2 += '</div>';
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
                left: ($(window).width() - $(".modal-box").outerWidth()) / 2
                        // left: "10%"
            });
        });

        $m(window).resize();

    });
jQuery(document).ready(function ($) {

       

    });
  
    //function callCrudAction(action, id) {
    
    
</script> 
</body>
</html>
