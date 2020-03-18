<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityController");
$controller->doAction();

$beanUi                        = $controller->beanUi;
$post_categories               = $beanUi->get_view_data("post_categories");
$post_participants_categories  = $beanUi->get_view_data("post_participants_categories");
$post_division_department      = $beanUi->get_view_data("post_division_department");
$post_activity_type_master     = $beanUi->get_view_data("post_activity_type_master");
$post_status                   = $beanUi->get_view_data("post_status");
$auth_user_id                  = $controller->get_auth_user("id");
$activity_id                   = $beanUi->get_view_data("activity_id");
$activities                    = get_value_by_id($activity_id, "activity_name", $post_activity_type_master);
$l1_cat_name                   = "";
$tag_keys                      = $beanUi->get_view_data("tag_keys");
$token_id                      = rand(000000, 111111).time();
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
    .boxes{display: none;}
</style>
<div class="container1">
    <h1 class="heading">Add Activity : <span class="text-primary"><?php echo $activities; ?></span></h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div>

<?php echo $beanUi->get_error("status_id"); ?>
    <div class="panel" style="padding:20px;">
        <form name="create_post" id="create_post" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="token_id" id="token_id" value="<?php echo $token_id; ?>" />
            <input type="hidden" name="data[activity_type_id]" class="actypeId"  value="<?php echo $activity_id; ?>" />
            <div class="holder">
                <label for="title">Activity</label>
                <b class="text-danger"> <?php echo $activities; ?> </b>
            </div>
            <br />
            <div class="holder required">
                <label for="synopsis" style="float:left;">Division Department</label>
                <!-- Trigger the modal with a button -->
                <table class="table table-bordered table-condensed" id="div_dept" style="float:left;max-width:70%;">
                    <tr id="division-department" >
                        <td colspan="2">
                            <button type="button" class="btn btn-info btn-sm open-AddBookDialog" data-toggle="modal" data-setvalue="1" data-id="div_dept" data-target="#myModal"> <i class="fa fa-plus"></i> Add Division Department</button>
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
                                <input type="hidden" id="idbuttn">
                                <input type="hidden" id="closestid">
                                <input type="hidden" id="tree_department">
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
                                <input type="hidden" name="setvalue" class="setvalue" />
                                </div>
                                <div id="level2"></div>
                                
                                <div id="level3"></div>
                                <div id="level4"></div>
                                <div id="level5"></div>
                                <div id="level6"></div>
                                <div id="level7"></div>     
                                <div class="levelfour" style="margin:20px 0px 20px 0px;"></div>
                                <script type="text/javascript">                                    
                                    $(document).ready(function () {
                                        $(".open-AddBookDialog").click(function(){
                                            var rowval = $(this).data("setvalue");                                            
                                            if(rowval == 1) {
                                                $(".setvalue").val('1');
                                            } else {
                                               $(".setvalue").val('0'); 
                                            }                                            
                                        });
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
                                            //$('#level3,#level4,#level5,#level6').hide();
                                            $("#level" + lcount).html(get_nextlevel);
                                            $("#level" + lcount).css("margin-top", "20px");
                                            $("#level" + lcount).show();
                                           // $("#L3").val("test");
                                            $("#L" + lcount).on('change', function () {
                                                var lc = lcount + 1;
                                                var level_id = $(this).val();
                                                var actypeId = $(".actypeId").val();
                                                
                                               
                                                var groupval;
                                                if(level_id == '251'  || level_id == '252' || level_id == '298' || level_id == '299' || level_id == '300')
                                                {
                                                    $('#level5,#level6,#level7,#level8').hide();   /** for contractors included under mains **/
                                                    if(level_id == '251')
                                                    {
                                                    groupval = '2';
                                                    }
                                                    if(level_id == '252')
                                                    {
                                                    groupval = '3';
                                                    }
                                                    if(level_id == '298')
                                                    {
                                                    groupval = '2,3';
                                                    }
                                                    if(level_id == '299')
                                                    {
                                                    groupval = '2,3';
                                                    }
                                                    if(level_id == '300')
                                                    {
                                                    groupval = '2,3';
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
                                                   
                                                    $('.levelfour').hide();
                                                    
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
                                                                } else {
                                                                    $('#'+cc).html('');
                                                                }
                                                                $('#show_location').html($(this).find(':selected').data("location"));
                                                               
                                                            });
                                                            
                                                            /** <!-- for contractors included under mains **/
                                                            if( level_id == 5 || level_id == 6 || level_id == 8 ) {
                                                            
                                                                if( actypeId == 3 ) {
                                                                   // $("#L4").find("option[pid='5']").css('display', 'none');
                                                                    $("#L4").find("option[value='298'],[value='299'],[value='300'],option[value='9'],option[value='10']").css('display', 'block');
                                                                } else {
                                                                $("#L4").find("option[value='298'],[value='299'],[value='300']").remove();
                                                                }
                                                            }
                                                            if( level_id == 298 || level_id == 299 || level_id == 300) {
                                                             $('#level5,#level6,#level7').hide();
                                                            }
                                                             /** for contractors included under mains --> **/
                                                        }
                                                    });
                                                }
                                                
                                            });
                                        }
                                        /*tree function End*/
                                        $(".set").hide();
                                        /*tree Save Start*/
                                        $('#btnSave').click(function () {
                                        var setvaluedata = $('.setvalue').val();
                                       
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
                                            
                                            $('#'+closestids).hide();										
                                            var error_count = 0;
                                            jQuery(".errors").empty();                                         
                                            var contractorType = 0;
                                            $('.contractor_and_type :selected').each(function (j, selected) {
                                                if ($(this).val() != "") {
                                                    contractorType++;
                                                }
                                            });
                                            var numberNewCon = $('.newcons').length;
                                            var selectedNewCons = 0;
                                            /*$('.newcons :selected').each(function (j, selected) {
                                                var errormsg = $(this).parent().parent().find("label").html();
                                                if (!isNaN(this.value)) {
                                                    selectedNewCons++;
                                                    if ((typeof contractorType !== 'undefined' && contractorType == 1)) {
                                                        $("#level_error").html("<div class=\"errors\">" + errormsg + " is required.</div>");
                                                        error_count++;
                                                        return false;
                                                    }
                                                }
                                            });*/
                                            if (selectedNewCons > 0 && selectedNewCons != numberNewCon) {
                                                error_count = 3;
                                                $("#level_error").html("<div class=\"errors\">* field(s) are required.</div>");
                                            }

                                            if (error_count > 0) {
                                                jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                                                jQuery('html, body').animate({scrollTop: 0}, 'slow');
                                                return false;
                                            }	
                                            var foo_value = [];
                                            var foo_text = [];
                                            $('.division :selected').each(function (i, selected) {
                                                if (foo_value[i] != '')
                                                {
                                                    if($(selected).val()!="")
                                                    {
                                                        foo_text.push($(selected).text());
                                                        foo_value.push($(selected).val());
                                                    }													
                                                }                                                
                                                if($(this).text() == 'Others')
                                                {
                                                    var ppp = $(this).data("other");
                                                    if(ppp)
                                                    {
                                                    foo_text.push($("#new_"+ppp).val());
                                                    foo_value.push($("#new_"+ppp).val());
                                                    }
                                                }
                                            });
                                            var othertextboxvalue = $('.othertextbox').val();
                                            foo_text.push(othertextboxvalue);
                                            foo_value.push(othertextboxvalue);	
                                                var tree_department = $('#tree_department').val();
                                                var $ctrl = '<input type="text" id="'+ tree_department +'" value="'+foo_text+'" />';
                                            function replaceAll(str, find, replace) {
                                                return str.replace(new RegExp(find, 'g'), replace);
                                            }
                                            $.ajax({
                                                type: 'post',
                                                data: {
                                                    "action": "submit_division",
                                                    "name": foo_text,
                                                    "tree_dept": tree_department,
                                                    "ids": foo_value,
                                                    "autoid" : closestids
                                                },
                                                cache: false,
                                                success: function (submit_division) {
                                                    if (submit_division)
                                                    {
                                                        var getIdMod = submit_division.substr(9, 50);
                                                        var getId = getIdMod.substr(0, getIdMod.indexOf('">'));
                                                        var gtid = replaceAll(getId, ":", "_");                                                        
                                                        var appendid = $('#idbuttn').val();
                                                        var closestids = $('#closestid').val();                                                       
                                                        $('#' + appendid).append(submit_division + '<td><a id="sp' + closestids + '"  class="delete_row deletemainrow" style="cursor:pointer;" >Delete</a></td></tr>');
                                                        $('#sp' + closestids).on('click', function () {                                                        
                                                        var delid = $(this).attr('id');                                                         
                                                        var conf = confirm("Are you sure to delete this record");
                                                        if (conf) {
                                                            if($(".set3").val())
                                                            {
                                                                $(".set3 > [value=" + $(".set3").val() + "]").removeAttr("selected");
                                                            }
                                                            $(".set").hide();
                                                            if(delid == "spdivision-department"){
                                                                $(this).parents("tr").remove();
                                                            }else{
                                                                $(this).parent().parent("tr .deletrow").remove();;
                                                                }
                                                            $('#'+closestids).show();                                                                
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
                                                        if(setvaluedata == 1) {
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
                                                        }
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
                                                    }
                                                }
                                            });
                                            /**---reset selected data---**/
                                            $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').empty();
                                            $('#L1 option').prop('selected', function () {
                                                    return this.defaultSelected;
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
                <label for="activity_no">Activity Number</label>
                <input type="text"  name="data[activity_no]" id="activity_no" value="<?php echo $beanUi->get_view_data("activity_no"); ?>" />
                <div id="activity_no_error"><?php echo $beanUi->get_error('activity_no'); ?></div>
            </div>
            <br />
            <?php
            if( $activity_id == 3 )
            {
                ?>	
            <div class="holder required">
                <label for="activity_date">Period of Activity</label>
                <input type="text" style="width:250px;" placeholder="From Date" readonly="" name="data[from_date]" class="datetimepicker" id="from_date" value="<?php echo $beanUi->get_view_data("from_date"); ?>" />

                <input type="text"  style="width:250px;" placeholder="To Date" readonly="" name="data[to_date]" class="datetimepicker" id="to_date" value="<?php echo $beanUi->get_view_data("to_date"); ?>" />
                <div id="from_date_error"><?php echo $beanUi->get_error('activity_date'); ?></div>
            </div>
            <br />
            <?php	
            }
            else
            {
            ?>			
            <!--<div class="holder <?php //if( $activity_id != 2 ) { echo "required"; } ?> ">-->
            <div class="holder required">
                <label for="activity_date">Date of Activity</label>
                <input type="text" readonly="" name="data[activity_date]" class="datetimepicker" id="datetimepicker1" value="<?php echo $beanUi->get_view_data("activity_date"); ?>" />
                <div id="activity_date_error"><?php echo $beanUi->get_error('activity_date'); ?></div>
            </div>
            <br />
            <?php } ?>
            <div id="showrelatedData"></div>
            <div class="holder required">
                <label for="place">Venue</label>
                <input type="text" name="data[place]" id="place" value="<?php echo $beanUi->get_view_data("place"); ?>" />
                <div id="place_error"><?php echo $beanUi->get_error('place'); ?></div>
            </div>
            <br />
            <div class="holder">
            <label for="synopsis" style="float:left;">Participants</label>
            <table class="table table-hover table-bordered table-condensed" width="50%" style="float:left;width:70%;">
	<?php
	if (!empty($post_participants_categories)) 
	{
		$cat_l1 = array();
		foreach ($post_participants_categories as $prow) {
			if ($prow->category_type_id == '2') {
				?>
				<tr>
					<td width="5%"><input type="checkbox" value="<?php echo $prow->id; ?>" id="<?php echo $prow->id ?>" name="participant_cat_id[]" /></td>
					<td width="30%"><b><?php echo $prow->category_name ?></b></td>  
					<td ><div class="participants<?php echo $prow->id; ?> boxes"><input style="width:100%;" type="text" readonly disabled="" id="pid_<?php echo $prow->id; ?>" name="no_of_participants[]" min="1" placeholder="No. of Participants"  /></div>                            </td>
					<td ><div class="participants<?php echo $prow->id; ?> boxes">
					<a class="js-open-modal btn btn-xs btn-success" href="#" data-modal-id="popup<?php echo $prow->id; ?>"> <i class="fa fa-plus"></i> Add Participants Details</a> 
					<!--participants popup-->
					<div id="popup<?php echo $prow->id; ?>" class="modal-box" style="max-height: 80%;">
					<header> <a href="#" class="js-modal-close close">×</a>
						<h3>Participants Details</h3>
					</header>
					<div class="modal-body" style="height:75%;overflow-x: hidden;overflow-y: auto;">								
	<script type="text/javascript">
		$(document).ready(function () {
			var counter = 1;
			$("#addButton<?php echo $prow->id; ?>").click(function () {

							var lasttr = $('#TextBoxesGroup<?php echo $prow->id; ?> tr:last').attr('class');
                                                        
                                       var forcount;
                                                                if (!lasttr || lasttr == "removetr")
                                                                {
                                                                    var forcount = 0;
                                                                }
                                                                else 
                                                                {
                                                                    var splval = lasttr.split('_');
                                                                    if(!splval[2] || splval[2]== "undefined" || splval[2] == "NaN")
                                                                    {
                                                                        var forcount=0;
                                                                    }
                                                                    else
                                                                    { 
                                                                        var forcount = parseInt(splval[2]);
                                                                    }
                                                                }
                                                                var countingval = (forcount + 1);   
							$("#valc_"+<?php echo $prow->id; ?>).val(countingval);				 
				var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'TextBoxDiv' + countingval).attr("class", 'removetr');
					   
				<?php if ($prow->id == 3) { ?>
					newTextBoxDiv.after().html('<td><input type="text" class="req_<?php echo $prow->id; ?>" style="width:100%;" id="no_of_emp_<?php echo $prow->id; ?>_' + countingval + '" placeholder="No of Parmanant Employee" ></td>'
				   
					+ '<td><select style="width:100%;" class="req_<?php echo $prow->id; ?>" id="designation_<?php echo $prow->id; ?>_' + countingval + '" style="width:200px;"><option  selected="selected" disabled="disabled">-select category-</option><option value="Workman">Workman</option><option value="Supervisor">Supervisor</option></select></td>'                                                                       
					+ '<td><table class="table table-condensed" id="div_dept_<?php echo $prow->id; ?>_' + countingval + '" style="float:left;width:100%;"><tr class="division-department_<?php echo $prow->id; ?>_' +countingval+ '" id="division-department_<?php echo $prow->id; ?>_' +countingval+ '"><td colspan="2"><button type="button" class="btn btn-info btn-sm open-AddBookDialog" data-setvalue="0" data-id="div_dept_<?php echo $prow->id; ?>_' +countingval+ '" data-toggle="modal" data-target="#myModal"> <i class="fa fa-plus"></i> Add Division Department</button></td></tr></table></td>'
					+ '<td class="center"  align="center" width="5%"><a class="btn btn-danger btn-sm rmbtnn" id="removeButton_<?php echo $prow->id; ?>_' + countingval + '" name="delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>');
				<?php } else if ($prow->id == 4) { ?>
					newTextBoxDiv.after().html('<td><input style="width:100%;" type="text" class="req_<?php echo $prow->id; ?>" id="no_of_emp_<?php echo $prow->id; ?>_' + countingval + '" placeholder="No. of Contractual Employee" ></td>'
					+ '<td><select style="float:left;width:50%;" class="asd req_<?php echo $prow->id; ?>" id="designation_<?php echo $prow->id; ?>_' + countingval + '"  style="width:200px;"><option selected="selected" disabled="disabled">-select category-</option><option value="PF Covered">PF Covered</option><option value="Casual">Casual</option><option value="Others">Others</option></select>'
					+ '<select style="float:left;width:50%;" id="designation_2<?php echo $prow->id; ?>_' + countingval + '" style="width:200px;"><option  selected="selected" disabled="disabled">-select category-</option><option value="Workman">Workman</option><option value="Supervisor">Supervisor</option></select></td>'
					+ '<td><table class="table table-condensed" id="div_dept_<?php echo $prow->id; ?>_' + countingval + '" style="float:left;width:100%;"><tr class="division-department_<?php echo $prow->id; ?>_' +countingval+ '" id="division-department_<?php echo $prow->id; ?>_' +countingval+ '"><td colspan="2"><button type="button" class="btn btn-info btn-sm open-AddBookDialog" data-setvalue="0" data-id="div_dept_<?php echo $prow->id; ?>_' +countingval+ '" data-toggle="modal" data-target="#myModal"> <i class="fa fa-plus"></i> Add Division Department</button></td></tr></table></td>'
					+ '<td class="center"  align="center" width="5%"><a class="btn btn-danger btn-sm rmbtnn" id="removeButton_<?php echo $prow->id; ?>_' + countingval + '" name="delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>');
				<?php } else if ($prow->id == 5) { ?>
					newTextBoxDiv.after().html('<td><input style="width:100%;" type="text" class="req_<?php echo $prow->id; ?>" id="no_of_emp_<?php echo $prow->id; ?>_' + countingval + '" placeholder="No. of Officers" ></td>'
					/*+ '<td><input style="width:100%;" type="text" id="name_<?php echo $prow->id; ?>_' + counter + '" placeholder="Employee Name" ></td>'*/
					+ '<td><input style="width:100%;" type="text" class="req_<?php echo $prow->id; ?>" id="designation_<?php echo $prow->id; ?>_' + countingval + '" placeholder="Designation"></td>'
					+ '<td><table class="table table-condensed" id="div_dept_<?php echo $prow->id; ?>_' + countingval + '" style="float:left;width:100%;"><tr class="division-department_<?php echo $prow->id; ?>_' +countingval+ '" id="division-department_<?php echo $prow->id; ?>_' +countingval+ '"><td colspan="2"><button type="button" class="btn btn-info btn-sm open-AddBookDialog" data-setvalue="0" data-id="div_dept_<?php echo $prow->id; ?>_' +countingval+ '" data-toggle="modal" data-target="#myModal"> <i class="fa fa-plus"></i> Add Division Department</button></td></tr></table></td>'
					+ '<td class="center"  align="center" width="5%"><a class="btn btn-danger btn-sm rmbtnn" id="removeButton_<?php echo $prow->id; ?>_' + countingval + '" name="delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>');

				<?php } ?>
				
			   
				$("#nof_"+<?php echo $prow->id; ?>).val(counter+1);
				newTextBoxDiv.appendTo("#TextBoxesGroup<?php echo $prow->id; ?>");
				counter++;
				$(".rmbtnn").show();   
                                $("#no_of_emp_<?php echo $prow->id; ?>_"+ countingval).keypress(function (e) {
                                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                        alert("Accept Only Numbers,Space and character is not allow");
                                        return false;
                                    }
                                });													
				function renumberRows() {
					$('#TextBoxesGroup tr').each(function(index, el){
						$(this).children('td').first().text(function(i,t){
						  
							return index++;
						});
					});
				}
				$(".rmbtnn").click(function () {
			   
					var cnd = $(this).parents("tr .removetr").siblings().length;
					if(cnd >=0){
						if ($(this).parents("tr .removetr").siblings().length && window.confirm('Delete this row ?')) {						
					
							//var nofval = $("#nof_"+<?php echo $prow->id; ?>).val();
							//$("#nof_"+<?php echo $prow->id; ?>).val(nofval -1);
							$("#nof_"+<?php echo $prow->id; ?>).val(counter-1);
							counter--;
							$.when($(this).parents("tr .removetr").remove()).then(renumberRows);
						}
					} 
				});
				renumberRows();  
				
			});
			$("#designation_"+<?php echo $prow->id; ?>+"_0").change(function() {
					//$(".")
					
				});
			
			$("#removeButton<?php echo $prow->id; ?>").click(function () {
				if (counter == 1) {
					alert("No more textbox to remove");
					return false;
				}
				
				counter--;
				
				$("#TextBoxDiv" + counter).remove();
				$("#nof_"+<?php echo $prow->id; ?>).val(counter);
			});
			
				function renumberRows() {
				$('#TextBoxesGroup tr').each(function(index, el){
					$(this).children('td').first().text(function(i,t){
						return index++;
					});
				});
				}
				$(".rmbtnn1").click(function () {
                                    var button_id = $(this).attr("value");
                                   // var cnd = $(this).parents("tr .removetr").siblings().length;
                                    var cnd = $(this).parents("tr .removetr").length;
                                    if( cnd > 0 )
                                    {
                                        if ( window.confirm('Delete this row ?')) {
                                            
                                        var nofval = $("#nof_"+button_id).val();
                                        $("#nof_"+button_id).val(nofval -1);
                                        counter--;
                                        $.when($(this).parents("tr .removetr").remove());
                                        }
                                    }
				});
				renumberRows();
			
			
		});
		</script>
		<table id='TextBoxesGroup<?php echo $prow->id; ?>' class="table table-bordered">
			<tr id="TextBoxDiv0" class="removetr">
			  <?php if ($prow->id == 3) { ?>
				<td><input style="width:100%;" type='text' class="req_<?php echo $prow->id; ?>" id='no_of_emp_<?php echo $prow->id; ?>_0' placeholder='No. of Parmanent Employee' ></td>
					<td>
						<select class="req_<?php echo $prow->id; ?>" id='designation_<?php echo $prow->id; ?>_0' style="width:100%;" >
							<option value="" selected="selected" disabled="disabled">-select category-</option>
							<option value="Workman">Workman</option>
							<option value="Supervisor">Supervisor</option>
						</select>
					</td>
				<?php } else if ($prow->id == 4) { ?>
				<td><input style="width:100%;" type='text' class="req_<?php echo $prow->id; ?>" id='no_of_emp_<?php echo $prow->id; ?>_0' placeholder='No. of Contractual Employee' ></td>
					<td>
						
						<select class="req_<?php echo $prow->id; ?>" id='designation_<?php echo $prow->id; ?>_0' style="float:left;width:50%;" >
							<option selected="selected" disabled="disabled">-select category-</option>
							<option value="PF Covered">PF Covered</option>
							<option value="Casual">Casual</option>
							<option value="Others">Others</option>
						</select>
						<select class="req_<?php echo $prow->id; ?>" id='designation_2<?php echo $prow->id; ?>_0' style="float:left;width:50%;" >
							<option value="" selected="selected" disabled="disabled">-select category-</option>
							<option value="Workman">Workman</option>
							<option value="Supervisor">Supervisor</option>
						</select>
						
						</td>
				<?php } else if ($prow->id == 5) { ?>
				<td><input style="width:100%;" class="req_<?php echo $prow->id; ?>" type='text' id='no_of_emp_<?php echo $prow->id; ?>_0' placeholder='No. of Officers' ></td>
				<td><input style="width:100%;"  class="req_<?php echo $prow->id; ?>" type='text' id='designation_<?php echo $prow->id; ?>_0' placeholder='Designation' ></td>
				<?php } ?>
													

				<td >
					<table class="table table-condensed" id="div_dept_<?php echo $prow->id; ?>_0" style="float:left;width:100%;">
					<tr class="division-department_<?php echo $prow->id; ?>_0" id="division-department_<?php echo $prow->id; ?>_0">
					<td colspan="2">
					<button type="button" class="btn btn-info btn-sm open-AddBookDialog" data-toggle="modal" data-id="div_dept_<?php echo $prow->id; ?>_0" data-target="#myModal"> <i class="fa fa-plus"></i> Add Division Department</button>
					</td>
				</tr>
		</table>
	</td>
	<td align="center" width="5%">
	<a ID="dataremove" name="delete" class="btn btn-danger btn-sm rmbtnn1" value="<?php echo $prow->id; ?>"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>
	</tr>
	
	
	
	
	</table>
	</div>
	<footer style="margin-left:0px;"> 
	<input type='hidden' id="nof_<?php echo $prow->id; ?>" name="finding_count" value="1" style="float:left;width:200px;">
	<input type='hidden' id="valc_<?php echo $prow->id; ?>" name="finding_count2" value="0" style="float:left;width:200px;">
		<input type='button' value='Add More' class="btn btn-primary" id='addButton<?php echo $prow->id; ?>' style="float:left;">
		<button  id="save_pdetails_<?php echo $prow->id; ?>" class="btn btn-small btn-primary savebtn">Save</button>
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
                        $(".participants<?php echo $prow->id; ?>").toggle();
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
                 $("#no_of_emp_<?php echo $prow->id; ?>_0").keypress(function (e) {
                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        alert("Accept Only Numbers,Space and character is not allow");
                        return false;
                    }
                });
            });
            </script>
		<?php
		}
		}
	}
	?>
	</table>
	<div id="participant_cat_id_error" class="clearfix"><?php echo $beanUi->get_error('participant_cat_id'); ?></div>
</div>
            <div class="holder">
                <label for="faculty" style="float:left;">
                <?php echo ( $activity_id == 4 ) ? "Speakers":"Faculty"; ?> 
                
                </label>
                <table class="table table-hover table-bordered table-condensed" width="50%" style="float:left;width:70%;">
<?php
if (!empty($post_participants_categories)) {
    $cat_l1 = array();
    foreach ($post_participants_categories as $trow) {
        if ($trow->category_type_id == '1') {
            ?>
                                <tr>
                                    <td width="5%"><input type="checkbox" value="<?php echo $trow->id; ?>" id="<?php echo $trow->id ?>" name="faculty_cat_id[]" /></td>
                                    <td width="30%"><b><?php echo $trow->category_name; ?></b></td>
                                    <td width="30%"><div class="<?php echo $trow->id; ?> box_faculty"><input type="number" disabled="" id="pid_<?php echo $trow->id; ?>" name="no_of_faculty[]" min="1" placeholder="No. of <?php echo ( $activity_id == 4 ) ? "Speaker":"Faculty"; ?>"   /></div>                            </td>
                                    <td width="30%">
                                        <div class="<?php echo $trow->id; ?> box_faculty">
                                            <a class="js-open-modal btn btn-xs btn-success" href="#" data-modal-id="popup<?php echo $trow->id; ?>"> <i class="fa fa-plus"></i> Add <?php echo ( $activity_id == 4 ) ? "Speaker":"Faculty"; ?>  Details</a> 
                                            <!--participants popup-->
                                            <div id="popup<?php echo $trow->id; ?>" class="modal-box" style="max-height: 80%;">
                                                <header> <a href="#" class="js-modal-close close">×</a>
                                                    <h3><?php echo ( $activity_id == 4 ) ? "Speaker":"Faculty"; ?>  Details</h3>
                                                </header>
                                                <div class="modal-body" style="height:75% !important;overflow-x: hidden;overflow-y: auto;">
                                                        <!--<table id="pdetails_<?php echo $trow->id; ?>"></table>-->
                                                    <script type="text/javascript">
                                                        $(document).ready(function () {
                                                            var counter = 1;
                                                            $("#addButton<?php echo $trow->id; ?>").click(function () {
                                                          var lasttr = $('#TextBoxesGroup<?php echo $trow->id; ?> tr:last').attr('class');
                                                          
                                                                var forcount;
                                                                if (!lasttr || lasttr == "removetr")
                                                                {
                                                                    var forcount = 0;
                                                                }
                                                                else 
                                                                {
                                                                    var splval = lasttr.split('_');
                                                                    if(!splval[2] || splval[2]== "undefined" || splval[2] == "NaN")
                                                                    {
                                                                        var forcount=0;
                                                                    }
                                                                    else
                                                                    { 
                                                                        var forcount = parseInt(splval[2]);
                                                                    }
                                                                }
                                                                var countingvals = (forcount + 1);
									$("#valc_"+<?php echo $trow->id; ?>).val(countingvals);
									
                                                                 var newTextBoxDiv = $(document.createElement('tr')).attr("id", 'TextBoxDiv' + countingvals).attr("class", 'removetr abc_<?php echo $trow->id; ?>_'+countingvals+'');
            <?php if ($trow->id != '2') { ?>
                                                                    newTextBoxDiv.after().html('<td><input style="width:100%;" type="text" id="emp_code_<?php echo $trow->id; ?>_' + countingvals + '" placeholder="Employee Code" ></td>'
                                                                            + '<td><input type="text" style="width:100%;" id="name_<?php echo $trow->id; ?>_' + countingvals + '" placeholder="Employee Name" ></td>'
                                                                            + '<td><select style="width:100%;" id="designation_<?php echo $trow->id; ?>_' + countingvals + '">'
                                                                                +'<option value="" selected="" >select one</option>'
                                                                                +'<option value="DGM">DGM</option>'
                                                                                +'<option value="Sr. Manager">Sr. Manager</option>'
                                                                                +'<option value="Manager">Manager</option>'
                                                                                +'<option value="Sr. Dy. Manager">Sr. Dy. Manager</option>'
                                                                                +'<option value="Dy. Manager">Dy. Manager</option>'
                                                                                +'<option value="Asst.Manager">Asst.Manager</option>'
                                                                                +'<option value="Sr. Engineer">Sr. Engineer</option>'
                                                                                +'<option value="Engineer">Engineer</option>'
                                                                                +'<option value="Consultant">Consultant</option>'
                                                                                +'</select></td>'
                                                                            + '<td><input type="text" style="width:100%;" id="department_<?php echo $trow->id; ?>_' + countingvals + '" placeholder="Department"></td>'
                                                                    + '<td  align="center" class="center" width="5%"><a class="btn btn-danger btn-sm rmbtnn" id="removeButton_<?php echo $trow->id; ?>_' + countingvals + '" name="delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>');
            <?php } else { ?>
                                                                    newTextBoxDiv.after().html('<td><input style="width:100%;" type="text" id="name_<?php echo $trow->id; ?>_' + countingvals + '" placeholder="Name" ></td>'
                                                                            + '<td ><input type="text" style="width:100%;" id="designation_<?php echo $trow->id; ?>_' + countingvals + '" placeholder="Faculty Details"></td>'
                                                                        + '<td  align="center" class="center" width="5%"><a class="btn btn-danger btn-sm rmbtnn" id="removeButton_<?php echo $trow->id; ?>_' + countingvals + '" name="delete"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>');
            <?php } ?>

                                                                newTextBoxDiv.appendTo("#TextBoxesGroup<?php echo $trow->id; ?>");
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
                                               if(cnd >=0){
                                                if ($(this).parents("tr .removetr").siblings().length && window.confirm('Delete this row ?')) {
                                                   $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
                                                    }
                                     } 
                                                    });
                                                    renumberRows(); 
                                                                
                                                            });
                                                           
                                                        });
                                                    </script>
                                                    <table id='TextBoxesGroup<?php echo $trow->id; ?>' class="table table-bordered">
                                                        <tr id="TextBoxDiv0"  class="removetr abc_<?php echo $trow->id; ?>_0">
            <?php if ($trow->id != '2') { ?>
                                                            <td><input type='text' style="width:100%;" id='emp_code_<?php echo $trow->id; ?>_0' placeholder='Employee Code' ></td>
                                                            <td><input type='text' style="width:100%;" id='name_<?php echo $trow->id; ?>_0' placeholder='Employee Name' ></td>
                                                            <td>
                                                                <select style="width:100%;" id="designation_<?php echo $trow->id; ?>_0">
                                                                    <option value="" selected="" >select one</option>
                                                                    <option value="DGM">DGM</option>
                                                                    <option value="Sr. Manager">Sr. Manager</option>
                                                                    <option value="Manager">Manager</option>
                                                                    <option value="Sr. Dy. Manager">Sr. Dy. Manager</option>
                                                                    <option value="Dy. Manager">Dy. Manager</option>
                                                                    <option value="Asst.Manager">Asst.Manager</option>
                                                                    <option value="Sr. Engineer">Sr. Engineer</option>
                                                                    <option value="Engineer">Engineer</option>
                                                                    <option value="Consultant">Consultant</option>
                                                                </select>
                                                            </td>
                                                            <td><input type='text' style="width:100%;" id='department_<?php echo $trow->id; ?>_0' placeholder='Department' ></td>
                                                            <td align="center" width="5%"><a ID="dataremove" name="delete" class="btn btn-danger btn-sm rmbtnn1"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>
            <?php } else { ?>
                                                            <td><input type='text' style="width:100%;" id='name_<?php echo $trow->id; ?>_0' placeholder='Name' ></td>
                                                            <td><input type='text' style="width:100%;" id='designation_<?php echo $trow->id; ?>_0' placeholder='Faculty Details' ></td>
                                                            <td align="center" width="5%"><a ID="dataremove" name="delete" class="btn btn-danger btn-sm rmbtnn1"  style="cursor:pointer;"><i class="fa fa-trash"></i></a></td>
                                                            
            <?php } ?>
                                                            
                                                        </tr>
                                                    </table>
                                                </div>
                                                <footer style="margin-left:0px;"> 
													<input type='hidden' id="nof_<?php echo $trow->id; ?>" name="finding_count" value="1" style="float:left;width:200px;">
	<input type='hidden' id="valc_<?php echo $trow->id; ?>" name="finding_count2" value="0" style="float:left;width:200px;">
                                                    <input type='button' value='Add More' class="btn btn-primary" id='addButton<?php echo $trow->id; ?>' style="float:left;">
                                                    <button  id="save_pdetails_<?php echo $trow->id; ?>" class="btn btn-small btn-primary savebtn2">Save</button>
                                                    <!--<a href="#" class="btn btn-small btn-default js-modal-close">Close</a> -->
                                                </footer>
                                            </div>
                                            <!--participants popup-->                                                            
                                        </div>
                                    </td>
                                </tr>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $('input[type="checkbox"]').click(function () {
                                            if ($(this).attr("value") == "<?php echo $trow->id; ?>") {
                                                $(".<?php echo $trow->id; ?>").toggle();
                                                $('#pid_<?php echo $trow->id; ?>').prop('disabled', false);
                                            }

                                        });
                                        $("#pid_<?php echo $trow->id; ?>").keypress(function (e) {
                                            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                                alert("Accept Only Numbers");
                                                return false;
                                            }
                                        });
                                    });
                                </script>
            <?php
        }
    }
}
?>
                </table>
                <div id="faculty_cat_id_error" class="clearfix"><?php echo $beanUi->get_error('faculty_cat_id'); ?></div>
            </div>
            <br />
            <div class="holder required">
                <label for="subject_details">Subject Title</label>
                <input type="text" name="data[subject_details]" id="subject_details" />
                <div id="subject_details_error"><?php echo $beanUi->get_error('subject_details'); ?></div>
            </div>
            <br />
            <div class="holder ">
                <label for="remarks">Details</label>
                <textarea name="data[remarks]" id="remarks"><?php echo $beanUi->get_view_data("remarks"); ?></textarea>
                <div id="remarks_error"><?php echo $beanUi->get_error('remarks'); ?></div>
            </div>
            <br /><br />
             <div class="holder">
                <fieldset>
                        <legend>Upload Office Files</legend>
                        <label for="image_path">Upload File</label>
                        <input type="file" name="file_path[]" id="file_path" />
                        <input type="button" id="add_upload_file" value="Add another office file" class="btn btn-sm btn-primary" />
                        <br />
                        <label for="caption">File Caption</label>
                        <input type="text" name="caption[]" id="caption"  placeholder="File Caption" value="" />
                        <div id="file_path_error"><?php echo $beanUi->get_error( 'file_path' ); ?></div>
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
                    <input type="file" name="image_path[]" id="image_path" class="imagepath" />
                    <input type="button" id="add_upload_image" value="Add another image" class="btn btn-sm btn-primary" />
                    <br />
                    <label for="caption">Image Caption</label>
                    <input type="text" name="image_captions[]" id="image_captions"  placeholder="Image Caption" />
                  
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
                     <?php if( $activity_id == 1 ) { 
                        $imgp = url("assets/css/cropimage/img/workshop.png");
                    }
                    else if( $activity_id == 2 ) { 
                        $imgp = url("assets/css/cropimage/img/meeting.png");
                    }
                    else if( $activity_id == 3 ) { 
                        $imgp = url("assets/css/cropimage/img/training.png");
                    }
                    else if( $activity_id == 4 ) { 
                        $imgp = url("assets/css/cropimage/img/safety.png");
                    }
                    ?>
                    <img src="<?php echo $imgp; ?>" id="avatar2" width="150" style="padding:2px;border:1px solid #d0d0d0;">
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
                <input type="submit" value="Submit" class="btn btn-sm btn-primary disablebutton" />
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
//    jQuery('.datetimepicker').datetimepicker({
//		
//        timepicker: false,
//        scrollMonth: false,
//        scrollInput: false,
//        format: 'Y-m-d',
//        step: 5
//		
//        
//    });
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
            <?php } if( $currentDate < CHECK_DATE && ($role_id != 1 && $role_id != 3)) { ?>
    minDate: new Date(currentYear, (currentMonth-1), currentDate-(currentDate-1)),
         
    <?php } ?>
            step: 5
        });
    });  
        var $w=jQuery.noConflict();
        $w("#from_date").on("change", function (e) {
            var fromval = $w(this).val();
           $w("#to_date").val(fromval); 
           //pk
           var selected_date = fromval;
        if( selected_date != "" ){
            var ajax_data = {
                "action"            : "getDataByDate",
                "selected_date"	: selected_date,
                "activity_id" 	: <?php echo $activity_id; ?>,
                "table_name"	: "activity_view",
            };
            $w.ajax({
                type: 'post', 
                url: '<?php echo page_link("activity/create.php"); ?>', 
                cache: false,
                data: ajax_data,
                success: function (getDataByDate) {
                    if(getDataByDate)
                    {
                        $w("#showrelatedData").html(getDataByDate);
                    }
                    return false;
                }
            });
        }
        return false;
        //pk
        });
   
    </script>
    <script type="text/javascript">
        var $y=jQuery.noConflict();
     $y("#datetimepicker1").on("change", function (e) {
        var selected_date = $y('#datetimepicker1').val();
        if( selected_date != "" ){
            var ajax_data = {
                "action"            : "getDataByDate",
                "selected_date"	: selected_date,
                "activity_id" 	: <?php echo $activity_id; ?>,
                "table_name"	: "activity_view",
            };
            $y.ajax({
                type: 'post', 
                url: '<?php echo page_link("activity/create.php"); ?>', 
                cache: false,
                data: ajax_data,
                success: function (getDataByDate) {
                    if(getDataByDate)
                    {
                        $y("#showrelatedData").html(getDataByDate);
                    }
                    return false;
                }
            });
        }
        return false;
    });
 
//Datepicker
//Popup
    $(document).on("click", ".open-AddBookDialog", function () {	
        var myBookId = $(this).data('id');
        var closest = $(this).closest('tr').attr('id');
        $(".modal-body #idbuttn").val( myBookId );
        $(".modal-body #closestid").val( closest );
        var row_id = closest.replace("division-department", "department");
        $(".modal-body #tree_department").val( row_id );
        $('#myModal').click({
            backdrop: 'static',
            keyboard: false
       });
    });  

        var $m = jQuery.noConflict();
            $m(function () {
            var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
            $m('a[data-modal-id]').click(function (e) {
            var row_id = $(this).attr("data-modal-id").replace("popup", "");
            var pid_value = $("#pid_" + row_id).val();
            if(row_id == 2 || row_id == 1)
            {
                if (pid_value == 0 || pid_value == '' || pid_value == undefined)
                {
                    alert("No. of faculty should not be blank or 0.");
                    return false;
                }
            }         
            var text2 = '';
            for (var i = 0; i < pid_value; i++)
            {
                            text2 += '<tr>';
               if (row_id != 4 && row_id != 2)
                    {

                        text2 += '<td><input style="width:80%;" type="text" id="emp_code_' + row_id + '_' + i + '" placeholder="Employee Code" /></td>';
                    }

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
//Popup

//Featured image
    var $s = jQuery.noConflict();
    $s(function () {
        var time = function () {
            return'?' + new Date().getTime()};
//Avatar setup
        $s('#avatarModal').imgPicker({
            url: 'server/upload_avatar.php',
            aspectRatio: 1,
            deleteComplete: function () {
                $s('#avatar2').attr('src', 'assets/img/default-avatar.png');
                this.modal('hide');
            },
            uploadSuccess: function (image) {
// Calculate the default selection for the cropper
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

//Demo only
        $('.navbar-toggle').on('click', function () {
            $('.navbar-nav').toggleClass('navbar-collapse')});
        $(window).resize(function (e) {
            if ($(document).width() >= 430)
                $('.navbar-nav').removeClass('navbar-collapse')
        });

    });
    </script>
    <script type="text/javascript">
    jQuery(document).ready(function ($) {
	$('.savebtn').on('click', function (e) {
        var button_id 	= $(this).attr("id");
        var form_id 	= button_id.replace("save_pdetails_", "");
	var flag = true;
        $('.req_'+form_id).each(function () {
            if(jQuery.trim($(this).val()) == '') {
                $(".req_"+form_id).css("border-color","red"); 
                flag = false;
            }                
            return flag;
        });
            
        $('.req_'+form_id).each(function () {
            if(jQuery.trim($(this).val()) != ''){
                                    $(this).css("border-color","#999"); 
            }
        });
            	 
            if(flag == true) {
		e.preventDefault();		
		if (form_id != undefined && form_id > 0)
		{
                    var emp_codes 	= Array();
                    var token_id 	= $("#token_id").val();
                    var no_of_emp 	= Array();
                    var emp_name 	= Array();
                    var designation = Array();
                    var designation2 = Array();
                    var department 	= Array();
                    var no_of_parti = $("#valc_" + form_id).val();
                    for (var row_no = 0; row_no <= no_of_parti; row_no++)
                    {					
                        emp_codes[row_no] 	= $.trim($("#emp_code_" + form_id + "_" + row_no).val());
                        no_of_emp[row_no] 	= $.trim($("#no_of_emp_" + form_id + "_" + row_no).val());
                        emp_name[row_no] 	= $.trim($("#name_" + form_id + "_" + row_no).val());
                        designation[row_no] = $.trim($("#designation_" + form_id + "_" + row_no).val());
                        designation2[row_no] = $.trim($("#designation_2" + form_id + "_" + row_no).val());
                        department[row_no] 	= $.trim($("#department_" + form_id + "_" + row_no).val());
                    }

                    var ajax_data = {
                        "action"			: "save_participants",
                        "emp_codes"			: emp_codes,
                        "emp_name"			: emp_name,
                        "no_of_emp"			: no_of_emp,
                        "designation"		: designation,
                        "designation2"		: designation2,
                        "department"		: department,
                        "token_id"			: token_id,
                        "no_of_parti" 		: no_of_parti,
                        "table_name"		: "activity_participants_mapping",
                        "participant_cat_id": form_id
                    };
                    $.ajax({
                        type: 'post', 
                        url: '<?php echo page_link("activity/create.php"); ?>', 
                        cache: false,
                        data: ajax_data,
                        success: function (save_participants) {
                            if (save_participants)
                            {
                                $("#popup" + form_id).hide();
                                $(".modal-overlay").remove();
                                $("#pid_" + form_id).val(save_participants);
                                $('#pid_' + form_id).prop('disabled', false);
                            }
                        }
                    });			
		}		
            }
            return flag;
	});
	$('.savebtn2').on('click', function (e) {				
		e.preventDefault();
		var button_id 	= $(this).attr("id");
		var form_id 	= button_id.replace("save_pdetails_", "");
		if (form_id != undefined && form_id > 0)
		{
                    var emp_codes 	= Array();
                    var token_id 	= $("#token_id").val();
                    var emp_name 	= Array();
                    var designation = Array();
                    var department 	= Array();
                    var no_of_parti = $("#valc_" + form_id).val();
                    for (var row_no = 0; row_no <= no_of_parti; row_no++)
                    {
                        emp_codes[row_no] 		= $.trim($("#emp_code_" + form_id + "_" + row_no).val());
                        emp_name[row_no] 		= $.trim($("#name_" + form_id + "_" + row_no).val());
                        designation[row_no]             = $.trim($("#designation_" + form_id + "_" + row_no).val());
                        department[row_no] 		= $.trim($("#department_" + form_id + "_" + row_no).val());
                    }
                    var ajax_data = {
                            "action"		 : "save_faculty",
                            "emp_codes"		 : emp_codes,
                            "emp_name"		 : emp_name,
                            "designation"	 : designation,
                            "department"	 : department,
                            "token_id"		 : token_id,
                            "no_of_parti" 	 : no_of_parti,
                            "table_name"	 : "activity_participants_mapping",
                            "participant_cat_id" : form_id
                    };
                    $.ajax({
                        type: 'post', url: '<?php echo page_link("activity/create.php"); ?>', cache: false,
                        data: ajax_data,
                        success: function (save_participants) {
                            if (save_participants)
                            {
                                $("#popup" + form_id).hide();
                                $(".modal-overlay").remove();						
                            }
                        }
                    });

                }
            });
	$("#add_upload_file").click(function () {
            var boxnumber = 1 + Math.floor(Math.random() * 6);
            var another_image_upload_html = 
                '<div class="holder" id="upload_image_box_'+boxnumber+'">'+"\n"+
                    '<fieldset>'+"\n"+
                        '<legend>Upload Office Files</legend>'+"\n"+
                        '<label for="image_path">Upload File</label>'+"\n"+
                        '<input type="file" name="file_path[]" id="file_path" />'+"\n"+
                        '<input type="button" value="Remove office file" class="btn btn-danger btn-sm" onclick="remove_upload_image_box('+boxnumber+');" />'+"\n"+
                        '<br />'+
                        '<label for="caption">File Caption</label>'+"\n"+
                        '<input type="text" name="caption[]" id="caption"  placeholder="File Caption" />'+"\n"+
                    '</fieldset>'+"\n"+
                '</div>'+"\n";
            $("#extra_upload_files").append(another_image_upload_html);
	});
        
	
	
	$("#add_upload_image").click(function () {
		var boxnumber = 1 + Math.floor(Math.random() * 6);
		var another_image_upload_html =
		'<div class="holder" id="upload_image_box_' + boxnumber + '">' + "\n" +
		'<fieldset>' + "\n" +
		'<legend>Upload Image</legend>' + "\n" +
		'<label for="image_path">Upload Image</label>' + "\n" +
		'<input type="file" name="image_path[]" id="image_path" class="imagepath"  />' + "\n" +
		'<input type="button" value="Remove image" class="btn btn-danger btn-sm"  onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
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
		'<input type="button" value="Remove video" class="btn btn-danger btn-sm"  onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
		'<br />' +
		'<label for="caption">Video Caption</label>' + "\n" +
		'<input type="text" name="video_captions[]" id="video_captions"  placeholder="Video Caption" />' + "\n" +
		'</fieldset>' + "\n" +
		'</div>' + "\n";
		$("#extra_upload_video").append(another_image_upload_html);
	});


       $("#create_post").submit(function () {
        jQuery(".errors,.message").empty();
        jQuery(".errors,.message").html("");
        <?php if( $activity_id == 3 ) { ?>
            var from_date  = jQuery.trim(jQuery("#from_date").val());
            var to_date    = jQuery.trim(jQuery("#to_date").val());
        <?php } else { ?>
            var activity_date = jQuery.trim(jQuery("#datetimepicker1").val());
        <?php } ?>
            
        var place = jQuery.trim(jQuery("#place").val());
        var subject_details = jQuery.trim(jQuery("#subject_details").val());
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
        <?php if( $activity_id == 3 ) { ?>
            if (from_date == undefined || from_date == "")
            {
                jQuery("#from_date_error").html("<div class=\"error\">From date is required.</div>");
                error_counter++;
            }
            else if (to_date == undefined || to_date == "")
            {
                jQuery("#from_date_error").html("<div class=\"error\">To date is required.</div>");
                error_counter++;
            }      
        <?php } else {  ?>
            if (activity_date == undefined || activity_date == "")
            {
                jQuery("#activity_date_error").html("<div class=\"error\">Activity date is required.</div>");
                error_counter++;
            }
        <?php } ?>
            if (place == undefined || place == "")
            {
                jQuery("#place_error").html("<div class=\"error\">Venue is required.</div>");
                error_counter++;
            }
            if (subject_details == undefined || subject_details == "")
            {
                jQuery("#subject_details_error").html("<div class=\"error\">Subject is required.</div>");
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
 
    <script type="text/javascript">
    $( function() {
    var ajax_data = {
        "action"	  : "getVenueList",
        "activity_type_id" : "<?php echo $activity_id; ?>"
    };
    $.ajax({
        type: 'post', url: '<?php echo page_link("activity/create.php"); ?>', cache: false,
        data: ajax_data,
        success: function (data) {
            if (data)
            {
              var myVanueList = data.split(' | ');
              $( "#place" ).autocomplete({
                minLength: 1,
                source: myVanueList
              }); 
            }
        }
    });
    });
  
    $( function() {
    var ajax_data1 = {
            "action"            : "getSubjectTitleList",
            "activity_type_id"	: "<?php echo $activity_id; ?>"
            };
    $.ajax({
            type: 'post', url: '<?php echo page_link("activity/create.php"); ?>', cache: false,
            data: ajax_data1,
            success: function (data) { 
                    if (data)
                    {
                      var mySubjectList = data.split(' | ');
                      $( "#subject_details" ).autocomplete({
                        minLength: 1,
                        source: mySubjectList
                      }); 
                    }
            }
    });
    });
  </script>
</body>
</html>