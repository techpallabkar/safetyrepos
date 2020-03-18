<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityControllerNew");
$controller->doAction();

$beanUi = $controller->beanUi;
//*******************************santosh start*********************************************
$getOptinCatIdByname = $beanUi->get_view_data("getOptinCatIdByname");
$getOptinIdByValue = $beanUi->get_view_data("getOptinIdByValue");
$getGroupIdByValue = $beanUi->get_view_data("getGroupIdByValue");
$getQuestion = $beanUi->get_view_data("getQuestion");
//*******************************santosh end*********************************************
$page             = $_REQUEST["page"];
$fromdate_s       = $_REQUEST["fromdate"];
$todate_s         = $_REQUEST["todate"];
$activity_no_s    = $_REQUEST["activity_no"];
$search_title_s   = $_REQUEST["search_title"];
$status_id_s      = $_REQUEST["status_id"];
$districtid_s     = $_REQUEST["districtid"];
$post_categories  = $beanUi->get_view_data("post_categories");
$post_participants_categories = $beanUi->get_view_data("post_participants_categories");
$post_division_department = $beanUi->get_view_data("post_division_department");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$post_status = $beanUi->get_view_data("post_status");
$user_details = $beanUi->get_view_data("user_details");
$devition_names = $beanUi->get_view_data("devition_names");
$nodal_officer_details = $beanUi->get_view_data("nodal_officer_details");
$division_department_mapping = $beanUi->get_view_data("division_department_mapping");
$getScoreDeviationNodalData = $beanUi->get_view_data("getScoreDeviationNodalData");
$authUserAuditIdDetails = $beanUi->get_view_data("authUserAuditIdDetails");
$getQuestionSetDetails = $beanUi->get_view_data("getQuestionSetDetails");
$auth_user_id = $controller->get_auth_user("id");
$role_id = $controller->get_auth_user("role_id");
$created_by = $controller->get_auth_user("created_by");
$activity_id = $beanUi->get_view_data("activity_id");
$get_auth_user_id = $beanUi->get_view_data("get_auth_user_id");
$role_id = $beanUi->get_view_data("get_auth_user_role_id");
$activities = get_value_by_id($activity_id, "activity_name", $post_activity_type_master);
$l1_cat_name = "";
$tag_keys = $beanUi->get_view_data("tag_keys");
$token_id = rand(000000, 111111) . time();
$controller->setCss(array("cropimage/css/imgpicker"));
$controller->setCss("tree");
$controller->get_header();

/**/
$auditData=$beanUi->get_view_data("auditData");
$auditScore=$beanUi->get_view_data("auditScore");
$auditScoreGroup=$beanUi->get_view_data("auditScoreGroup");

/**/

/* Third part Deviation Observation */
$allDevType=$beanUi->get_view_data("allDeviationType");
$allCategories=$beanUi->get_view_data("allCategories");//show($allCategories);
$alldeviations=$beanUi->get_view_data("alldeviations");
$alldeviationsactionfile=$beanUi->get_view_data("alldeviationsactionfile");
$alldeviationsactionfile1=$beanUi->get_view_data("alldeviationsactionfile1");
$allperticategories=$beanUi->get_view_data("allperticategories");
$auditParti=$beanUi->get_view_data("auditParti");

$auditId=0;
if(!empty($authUserAuditIdDetails)){
    $auditId=$authUserAuditIdDetails->id;
    
}
if($_REQUEST['auditId']){
 $auditId=$_REQUEST['auditId'];   
}

/* Third part Deviation Observation */
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
        height: 30px;
        background: #fff;
    }
    .holder label, .label {
        float: left;
    }
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
    .btn.disabled {
        margin: 0;
        font-size: 13px;
        border:0;
    }
    .freeze-container {
        padding: 10px 10px;
        border-bottom: 1px solid #ccc;
        margin-bottom:10px;
        background: #f1f1f2;
    }
    .freeze {
        position: fixed;
        width: 78.2%;
        margin-left:-1.55%;
        top:0;
        background: #f1f1f2;
        box-shadow: 0px 3px 2px rgba(0,0,0,.4);
        z-index: 1000;
    }
    .freeze-container3 {
        padding: 10px 10px 5px;
        border-bottom: 1px solid #ccc;
        margin-bottom:10px;
        background: #f1f1f2;
        margin-left:-1.72%;
        position: absolute;
        top:0px;
        width: 100%;
    }
    .freeze3 {
        position: fixed ;
        width: 78.2%;
        top:0;
        margin-left:-1.6%;
        background: #f1f1f2;
        box-shadow: 0px 3px 2px rgba(0,0,0,.4);
        z-index: 1000;
    }
    .freeze-container2 {
        padding: 0px 0;
        display: none;
        
    }
    .freeze2 {
        position: fixed;
        width: 75%;
        top:112px;
        background: #fff;
        box-shadow: 0px 3px 2px rgba(0,0,0,.4);
        display: block;
    }
    .table {margin-bottom: 0;}
    #tabs-2 input:focus, #tabs-2 select:focus, #tabs-2 textarea:focus {
        border: 2px solid #ff1ac6;
    }
    span.txt_danger{
        color: #f00;
        font-weight: bold;
    }
    
    
    /*lightbox pure css*/
    .thumbnail {
  max-width: 40%;
}

.italic { font-style: italic; }
.small { font-size: 0.8em; }

/** LIGHTBOX MARKUP **/

.lightbox .close2 {
    width: 500px;
    margin: 0 auto;
    text-align: right;
}
.lightbox .close2 i {
    padding: 5px;
    border-radius: 50%;
    background: #000;
    color: #fff;
    position: relative;
    top: 20px;
    right: -13px;
    font-size: 20px;
}
.lightbox {
	/** Default lightbox to hidden */
	display: none;

	/** Position and style */
	position: fixed;
	z-index: 999;
	width: 100%;
	height: 100%;
	text-align: center;
	top: 0;
	left: 0;
	background: rgba(0,0,0,0.8);
}

.lightbox img {
	/** Pad the lightbox image */
	max-width: 90%;
	max-height: 80%;
	/*margin-top: 2%;*/
}

.lightbox:target {
	/** Remove default browser outline */
	outline: none;

	/** Unhide lightbox **/
	display: block;
}
.ui-state-active .btn.disabled {opacity:1.0 !important;font-weight: 600;}
.lbl-draft {
    margin-right:15px;
    padding:6px 9px;
    border: 1px solid #ccc;
    background: #31b0d5;
    color: #fff;
}
.lbl-final-submit {
    margin-right:15px;
    padding:6px 9px;
    border: 1px solid #ccc;
    background: #693D3D;
    color: #fff;
}
.lbl-approve-publish {
    margin-right:15px;
    padding:6px 9px;
    border: 1px solid #ccc;
    background: #598234;
    color: #fff;
}
.lbl-approve-unpublish {
    margin-right:15px;
    padding:6px 9px;
    border: 1px solid #ccc;
    background: #598234;
    color: #fff;
}
.ui-tabs .ui-tabs-nav li a {font-weight: 600;color:#000;}
.ui-tabs .ui-state-active a {color:#fff !important;}
.bg-frezz-container {
    background: #f1f1f2;
    padding-top: 10px;
}
.ui-tabs-panel .table-bordered2 input {
    margin-bottom: 0;
}
.image-gallery {
        margin: 0;
        padding: 0;
        list-style-type: none;
    }
    .image-gallery li {
        float:left;
        width: 33.333%;
        padding: 5px;
        box-sizing:border-box;
        display: inline-block;
        margin-bottom: 10px;
        text-align: center;
    }
    .image-gallery li img {
        max-width: 100%;
        margin-bottom: 5px;
    }
</style>
<script>
    $(window).scroll(function() {    
    var scroll = $(window).scrollTop();
    if (scroll >= 250) {
        $(".freeze-container").addClass("freeze");
        $(".freeze-container2").addClass("freeze2");
        $(".freeze-container3").addClass("freeze3");
    }
    if (scroll <= 250) {
        $(".freeze-container").removeClass("freeze");
        $(".freeze-container2").removeClass("freeze2");
        $(".freeze-container3").removeClass("freeze3");
    }
}); //missing );
    </script>
<link rel="stylesheet" href="<?php echo url('assets/css/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jquery-ui.js') ?>"></script>
<!-- for image popup archana 09-09-19 --------->
<link rel="stylesheet" href="<?php echo url('assets/css/zoombox.css') ?>" />

<div class="container1">
    <h1 class="heading">Add Activity : <span class="text-primary"><?php echo $activities; ?></span></h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div>
    <?php echo $beanUi->get_error("status_id"); ?>
    <!--<br />-->
    <div class="panel">
        
<!--            <div class="holder">
                <label for="title">Activity</label>
                <b class="text-danger"> <?php echo $activities; ?> </b>
            </div>-->
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Audit Information</a></li>
                    <li><a href="#tabs-2">Audit Score</a></li>
                    <li><a href="#tabs-3">Deviation Observation</a></li>
                </ul>
                <div id="tabs-1">
                    <form name="audit_creation_form" id="audit_creation_form" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="pageaction" value="auditinfo">
                        <input type="hidden" name="token_id" id="token_id" value="<?php echo $token_id; ?>" />
                        <input type="hidden" name="data[activity_type_id]" value="<?php echo $activity_id; ?>" />
                        <input type="hidden" name="data1[auditId]" value="<?php echo $auditId; ?>" />
                        <input type="hidden" name="page_no" value="<?php echo $page; ?>" />
                        <input type="hidden" name="fromdate_s" value="<?php echo $fromdate_s; ?>" />
                        <input type="hidden" name="todate_s" value="<?php echo $todate_s; ?>" />
                        <input type="hidden" name="activity_no_s" value="<?php echo $activity_no_s; ?>" />
                        <input type="hidden" name="search_title_s" value="<?php echo $search_title_s; ?>" />
                        <input type="hidden" name="status_id_s" value="<?php echo $status_id_s; ?>" />
                        <input type="hidden" name="districtid_s" value="<?php echo $districtid_s; ?>" />
<!--                    <div align="center">
                        <button type="submit" value="Submit" class="btn btn-sm btn-primary disablebutton"><i class="fa fa-save"></i> Submit</button>
                    </div>-->

                <!-- tab button section-->
                <div class="button-section freeze-container bg-frezz-container">
                    <div class="clearfix">
                    <div class="col-xs-3"></div>
                    <div class="col-xs-6 text-center">
                        <?php 
                            if($auditData[0]->status_id == 1){
                                echo '<label class="text-primary"><strong>Draft</strong></label> &nbsp; &nbsp;'; 
                            } else if($auditData[0]->status_id == 2){
                                echo '<label class="text-danger"><strong>Final Submit</strong></label> &nbsp; &nbsp;';
                            } else if($auditData[0]->status_id == 3){
                                echo '<label class="text-success"><strong>Approve & Publish</strong></label> &nbsp; &nbsp;';
                            } else if($auditData[0]->status_id == 4) {
                                echo '<label class="text-warning"><strong>Approve & Unpublish</strong></label>';
                            }
                        ?>
                        <button type="submit" value="Submit" class="btn btn-sm btn-primary disablebutton"><i class="fa fa-save"></i> Submit</button>
                        <a href="<?php echo page_link("activity/index.php?activity_id= $activity_id ");?>" onclick="return confirm('Are you sure to cancel this page ?')" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Cancel</a>
                        <input type="hidden" name="_action" value="Create" class="btn btn-sm btn-success" />
                        <input type="hidden" id="f_image_error" value="" />
                        
                    </div>
                    <div class="col-xs-3 text-right">
                        <button type="<?php echo ($auditId==''?'button':'submit')?>" name="nexttab" class="btn btn-warning btn-xs <?php echo ($auditId==''?'disabled':'')?>" value="Next Tab"  text="Next Tab">Next Tab <i class="fa fa-arrow-right"></i></button>                
                    </div>
                    </div>
                </div> 
                <!-- / tab button section -->
                <div class="row form-group">
                    <div class="col-sm-4 col-md-3 required">
                        <label for="audit_no">Site Audit Gradation Sheet</label>
                    </div>  
                    <div class="col-sm-8 col-md-9">
                        <select type="text" name="data[gradation_sheet_division_id]" id="gradation_sheet_division_id" style="<?php echo ((!empty($auditData) && $auditData[0]->id==$auditId)?'pointer-events:none;':'');?>">                           
                            <?php
                            echo '<option value="" selected="">---Select---</option>';
                            if (!empty($getQuestionSetDetails)) {
                                foreach ($getQuestionSetDetails as $skey => $svalue) {
                                    echo "<option value='" . $svalue->division_id . "' ".(($auditData[0]->gradation_sheet_division_id==$svalue->division_id)?'selected':'')." >" . $svalue->subheading_alias . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <div id="gradation_sheet_division_id_error"><?php echo $beanUi->get_error('gradation_sheet_division_id'); ?></div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4 col-md-3 required">
                        <label for="synopsis" style="float:left;">Division Department</label>
                    </div>
                    <div class="col-sm-8 col-md-9">
                        <!-- Trigger the modal with a button -->
                        <table class="table table-bordered table-condensed" id="div_dept" style="width:100%;">
                            <?php
                                if (count($devition_names) > 0) {
                                    if (!empty($devition_names)) {//show($devition_names);
                                        $j = 0;
                                        $valxx = array();
                                        echo '<tr><td colspan="2"><b class="text-danger">Selected Division - Departments : </b><br>';
                                        foreach ($devition_names as $key => $ddmrow) {
                                            $j = $j + 1;
                                            echo $j . '. <b>' . $ddmrow . '</b>&nbsp;&nbsp;';
                                            ?>
<!--                                            <a href="<?php //echo page_link('activitynew/add_audit_new.php?id='. $activity_id .'&delid='. $key ); ?>" onclick="return confirm('Are you sure want to delete this?');">
                                                <img width="8px" src="<?php //echo url("assets/images/delete.gif"); ?>" />
                                            </a>-->
                                            <br>
                                            <?php
                                        }
                                        echo "</td></tr>";
                                    }
                                }
                                if (count($devition_names) == 0) {
                            ?>
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
                                                    $(document).ready(function(){
                                            $("#devid").val('');
                                            $("#reset_button").click(function(){
                                            $('#L1').val('');
                                                    $(".division").val('');
                                                    $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                            });
                                                    $('.levelfour,#level2,#level3,#level4,#level5,#level6,#level7').hide();
                                                    var lcount = 1;
                                                    $("#L" + lcount).on('change', function(){
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
                                                            cache:false,
                                                            success:function (get_nextlevel)  {//alert(get_nextlevel);
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
                                                                    $("#L" + lcount).on('change', function(){
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
                                                                    //alert(get_contractor_list);
                                                                    $(".levelfour").show();
                                                                            $(".levelfour").html(get_contractor_list);
                                                                    }
                                                                    }
                                                            });
                                                            } else {
                                                            $.ajax({
                                                            type: 'post',
                                                                    data: {
                                                                    "action"    : "get_nextlevel",
                                                                            "id"        : level_id,
                                                                            "lcount"    : lc
                                                                    },
                                                                    cache:false,
                                                                    success:function (get_nextlevel)  {
                                                                    division_department_treeview(lc, level_id, get_nextlevel, tb = null);
                                                                            $(".newcons").on('change', function(){
                                                                    var sdd = $(this).find(':selected').data("other");
                                                                            var cc = $(this).find(':selected').data("c");
                                                                            if (sdd != 0)
                                                                    {
                                                                    $('#' + sdd).html('<span style="float:left;width:150px;padding-top: 6px;"><b>Other Name</b></span>'
                                                                            + '<input class="division" name=""  id="new_' + sdd + '" type="text" value="" style="width:23%;" />');
                                                                    }
                                                                    else
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
                                                            $('#btnSave').click(function() {
                                                    $('#division-department').hide();
                                                            var closestids = $('#closestid').val();
                                                            var level = $("#L1").find("option:selected").val();
                                                            var level_2 = $("#L2").find("option:selected").val();
                                                            /*L2 validation start*/
                                                            if (level_2 == ""){
                                                    alert(" Division is required.  ");
                                                            $('#division-department').show();
                                                            return false;
                                                    }
                                                    /*L2 validation end*/

                                                    var error_count = 0;
                                                            jQuery(".errors").empty();
                                                            $('#' + closestids).hide();
                                                            var contractorType = 0;
                                                            $('.contractor_and_type :selected').each(function(j, selected) {
                                                    if ($(this).val() != ""){
                                                    contractorType++;
                                                    }
                                                    });
                                                            /*$('.newcons :selected').each(function(j, selected) { 
                                                             var errormsg = $(this).parent().parent().find("label").html();
                                                             if(!isNaN(this.value) ) {
                                                             if(( typeof contractorType !== 'undefined' &&  contractorType == 1 )){
                                                             $("#level_error").html("<div class=\"errors\">"+errormsg+" is required.</div>");
                                                             error_count++; 
                                                             return false;
                                                             }
                                                             }
                                                             });*/
                                                            if (error_count > 0) {
                                                    jQuery(".message").html("<div class=\"errors\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
                                                            jQuery('html, body').animate({ scrollTop: 0}, 'slow');
                                                            return false;
                                                    }
                                                    var foo_value = [];
                                                            var foo_text = [];
                                                            $('.division :selected').each(function(i, selected){

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
                                                            "action"    : "submit_division",
                                                                    "name"      : foo_text,
                                                                    "tree_dept" : "department",
                                                                    "ids"       : foo_value
                                                            },
                                                            cache:false,
                                                            success:function (submit_division)  {
                                                            if (submit_division)
                                                            {
                                                            var getIdMod = submit_division.substr(9, 50);
                                                                    var getId = getIdMod.substr(0, getIdMod.indexOf('">'));
                                                                    var gtid = replaceAll(getId, ":", "_");
                                                                    $('#div_dept').append(submit_division + '<td><a id="sp' + gtid + '"  class="delete_row" style="cursor:pointer;" >Delete</a></td></tr>');
                                                                    $('#sp' + gtid).on('click', function() {
                                                            var conf = confirm("Are you sure to delete this record");
                                                                    if (conf){
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
                                                            } else{
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

                                                    });</script>
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
                </div>
                    <?php
                        if (count($devition_names) > 0) {
                            foreach ($devition_names AS $devrow) {
                                echo '<div class="holder required"><label for="incident_no">Set Type</label>
                                <select name="data[set_type]" id="set_type3" class="set3">';
                                if ($auditData[0]->set_type == "P-SET") {
                                    echo '<option value="P-SET" ' . ($auditData[0]->set_type == "P-SET" ? "selected" : "") . '>P-SET</option>';
                                } else if ($auditData[0]->set_type == "C-SET") {
                                    echo '<option value="C-SET" ' . ($auditData[0]->set_type == "C-SET" ? "selected" : "") . '>C-SET</option>';
                                } else {
                                    echo '<option value="">select one</option><option value="P-SET" ' . ($auditData[0]->set_type == "P-SET" ? "selected" : "") . '>P-SET</option>'
                                    . '<option value="C-SET" ' . ($auditData[0]->set_type == "C-SET" ? "selected" : "") . '>C-SET</option>'
                                    . '<option value="PC-SET" ' . ($auditData[0]->set_type == "PC-SET" ? "selected" : "") . '>(P+C)-SET</option>'
                                            . '<option value="OTHERS" ' . ($auditData[0]->set_type == "OTHERS" ? "selected" : "") . '>OTHERS</option>';
                                }
                                echo '</select><div id="set_type_error">' . $beanUi->get_error('set_type') . '</div>  </div>';
                            }
                        } else {
                            echo '<div class="holder required set">
                        <label for="incident_no">Set Type</label>
                        <select name="data[set_type]" id="set_type3" class="set3">
                            <option value="">select one</option>
                            <option value="P-SET"'. (($auditData[0]->set_type=='P-SET')?'selected':'') .'>P-SET</option>
                            <option value="C-SET"'. (($auditData[0]->set_type=='C-SET')?'selected':'') .'>C-SET</option>
                            <option value="PC-SET"'.(($auditData[0]->set_type=='PC-SET')?'selected':'') .'>(P+C)-SET</option>
                            <option value="OTHERS"'.(($auditData[0]->set_type=='OTHERS')?'selected':'') .'>OTHERS</option>
                        </select>
                        <div id="set_type_error">'. $beanUi->get_error('set_type') .'</div>
                        </div>';
                        }
                        ?>
                <br>
                        <div class="row">
                            <div class="col-sm-4 col-md-3"><label for="audit_no">Audit Number</label></div>
                            <div class="col-sm-8 col-md-5">    
                                <input type="text"  name="data[audit_no]" id="audit_no" value="<?php echo $auditData[0]->audit_no; ?>" />
                                <div id="audit_no_error"><?php echo $beanUi->get_error('audit_no'); ?></div>
                            </div>
                         
                    <?php 
                    $datetime='';
                    if($auditData){
                        if($auditData[0]->date_of_audit!='0000-00-00'){
                            $dtobj=new DateTime($auditData[0]->date_of_audit);
                            $datetime=$dtobj->format('Y-m-d');
                        }                        
                    }
                    ?>
                    
                        <div class="col-sm-4 col-md-2 required">
                            <label for="date_of_audit">Date of Audit</label>
                        </div>
                        <div class="col-sm-8 col-md-2">
                            <input type="text" name="data[date_of_audit]" readonly="true"  class="datetimepicker" id="datetimepicker1" value="<?php echo $datetime; ?>" style="width:100%;" />
                            <div id="date_of_audit_error"><?php echo $beanUi->get_error('date_of_audit'); ?></div>
                        </div>
                    </div>
                    <!--<div id="showrelatedData"></div>-->

                    <?php
                    if(!empty($auditData)){
                        $auditfrom=new DateTime($auditData[0]->time_of_audit_from);
                        $auditto=new DateTime($auditData[0]->time_of_audit_to);
                        $hrform=$auditfrom->format('H');
                        $minfrom=$auditfrom->format('i');
                        $hrto=$auditto->format('H');
                        $minto=$auditto->format('i'); 
                    }
                    
                    ?>
                    <div class="row">
                    <div class="col-sm-4 col-md-3 required">
                        <label for="time_of_audit">Time of Audit</label>
                    </div>
                    <div class="col-sm-8 col-md-5">    
                        <?php
                        echo 'From <select class="combodate" id="auditfromhr" style="width:12%;">
                        <option value="">HH</option>';
                        for ($i = 0; $i < 24; $i++) {
                            $val = sprintf('%02s', $i);
                            echo '<option value="' . $val . '" '.(($val==$hrform)?'selected':'').'>' . $val . '</option>';
                        }
                        echo '</select>';
                        echo '<select class="combodate" id="auditfrommin" style="width:12%;">
		<option value="">mm</option>';
                        for ($j = 0; $j < 60; $j++) {
                            $val = sprintf('%02s', $j);
                            echo '<option value="' . $val . '" '.(($val==$minfrom)?'selected':'').'>' . $val . '</option>';
                        }
                        echo '</select>';
                        echo '<input style="width:17%; border: medium none;font-weight: bold;font-size: 16px;" readonly=""  type="text" class=""  name="data[time_of_audit_from]" id="datetimepicker2" value="' . ($hrform.($minfrom?':':'').$minfrom) . '" />';
                        echo 'To <select class="combodateto" id="audittohr" style="width:12%;">
		<option value="">HH</option>';
                        for ($i = 0; $i < 24; $i++) {
                            $val = sprintf('%02s', $i);
                            echo '<option value="' . $val . '" '.(($val==$hrto)?'selected':'').'>' . $val . '</option>';
                        }
                        echo '</select>';
                        echo '<select class="combodateto" id="audittomin" style="width:12%;">
		<option value="">mm</option>';
                        for ($j = 0; $j < 60; $j++) {
                            $val = sprintf('%02s', $j);
                            echo '<option value="' . $val . '" '.(($val==$minto)?'selected':'').'>' . $val . '</option>';
                        }
                        echo '</select>'
                        . '<input style="width:17%; border: medium none;font-weight: bold;font-size: 16px;" readonly=""  type="text" class="" name="data[time_of_audit_to]" id="datetimepicker22" value="' . ($hrto.($minto?':':'').$minto) . '" />';
                        ?>

                        <div id="time_of_audit_error"><?php echo $beanUi->get_error('time_of_audit'); ?></div>
                    </div>
                    
                        <div class="col-sm-4 col-md-2">
                        <label for="audit_duration">Duration of Audit</label>
                        </div>
                         <div class="col-sm-8 col-md-2">
                            <input type="text" readonly="" name="data[audit_duration]" id="audit_duration" value="<?php echo $auditData[0]->audit_duration; ?>" style="width:100%;" />
                            <div id="audit_duration_error"><?php echo $beanUi->get_error('audit_duration'); ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-md-3 required">
                            <label for="place">Venue / Location</label>
                        </div>
                        <div class="col-sm-8 col-md-9">    
                            <input type="text" name="data[place]" id="place" value="<?php echo $auditData[0]->place; ?>" style="width:81%;"/>
                            <div id="place_error"><?php echo $beanUi->get_error('place'); ?></div>
                        </div>
                    </div>        
                    <div id="partilist">
                    <?php 
                    if(!empty($allperticategories)){
                        foreach($allperticategories as $key=>$value){
                    ?>
                    <div class="row">
                        <div class="col-sm-4 col-md-3 partilist" id="pertidiv_<?php echo $value->id; ?>">
                            <label for=""><?php echo $value->category_name ;?></label></div>
                        <div class="col-sm-8 col-md-9">
                            <?php //if($value->id==2){?>
                            <select name="audit_participant_status_<?php echo $value->id; ?>[]" id="partistatus_<?php echo $value->id; ?>" style="width: 17%;">
                                <option value="">---Select---</option>
                                <option value="present">Present</option>
                                <option value="not present">Not Present</option>
                                <option value="not applicable">Not Applicable</option>
                            </select>
                            <?php //} ?>
                            <input type="text" name="audit_participant_name_<?php echo $value->id; ?>[]" id="partiname_<?php echo $value->id; ?>" style="width: 57%;" />                        
                            <div style="width: 24%; float: right;">
                                <a class="btn btn-success" onclick="participantadd(<?php echo $value->id; ?>)"><i class="fa fa-plus"></i></a>
                            </div>
                            <div id="particpant_new_<?php echo $value->id; ?>"></div>
                            <div id="job_title_error"><?php echo $beanUi->get_error(''); ?></div>
                            </div>
                        </div>
                    <?php if(!empty($auditParti[$value->id])){?>
                    <div class="holder" id="participant_list_<?php echo $value->id; ?>">
                        <label for="">&nbsp;</label>
                        <table style="width: 50%;" class="table2 table-bordered2">
                            <?php foreach($auditParti[$value->id] as $key2=>$value2){?>
                            <tr id="auditpartirow_<?php echo $value2->id; ?>">
                                <?php //if($value->id==2){ ?>
                                <td style="width: 31%;" class="text-center"><?php echo ucwords($value2->partcipant_status); ?></td>
                                <?php //} ?>
                                <td style="width: 60%;"><?php echo $value2->name; ?></td>                                
                                <td style="width: 10%;" class="text-center"><a class="btn btn-danger btn-xs" onclick="deleteparticipant(<?php echo $value2->id; ?>,<?php echo $value->id; ?>)"><i class="fa fa-trash"></i></a></td>
                            </tr>
                            <?php } ?>                            
                        </table>
                    </div>
                    <?php } ?>
                    <?php
                        }
                    }
                    ?>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4 col-md-3 required">
                            <label for="job_title">Job Title</label></div>
                        <div class="col-sm-8 col-md-9">
                            <textarea name="data[job_title]" id="auditjobtitle" value="" style="width: 100%; height: 30px;"><?php echo $auditData[0]->job_title; ?></textarea>
                            <div id="job_title_error"><?php echo $beanUi->get_error(''); ?></div>
                        </div>
                    </div>    
                    <div class="row">
                        <div class="col-sm-4 col-md-3">
                            <label for="ref_job_no">Ref./Job No.</label></div>
                        <div class="col-sm-8 col-md-9">
                            <input type="text" name="data[ref_job_no]" id="auditjobrefno" value="<?php echo $auditData[0]->ref_job_no; ?>" />
                            <div id="job_title_error"><?php echo $beanUi->get_error(''); ?></div>
                        </div>
                    </div>    
                    <?php 
                    $auditby=array();
                    if($auditData){
                        $auditby=explode(',',$auditData[0]->audit_by);                       
                    }
                    ?>
                    <div class="row">
                        <div class="col-sm-4 col-md-3 required">
                            <label for="audit_by">Audit Made By</label>
                        </div> 
                        <div class="col-sm-8 col-md-9">
                        <select type="text" name="audit_by[]" multiple id="audit_by" style="width:300px;height:180px;">
                            <?php
                            //echo '<option value="" selected="">---Select---</option>';
                            if (!empty($user_details)) {
                                foreach ($user_details as $skey => $svalue) {
                                    echo "<option value='" . $svalue->id . "' data-select='" . $svalue->audited_by_code . "' ".((in_array($svalue->id,$auditby))?'selected':'').">" . $svalue->full_name . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <div id="audit_by_error"><?php echo $beanUi->get_error('audit_by'); ?></div>
                    </div>
                </div>                 	

                        <fieldset class="">
                            <legend>Upload Featured Image</legend>
                            <div class="row">
                            <div class="col-sm-4 col-md-3">
                                <label for="featured_image">Featured Image</label>
                            </div>
                            <div class="col-sm-8 col-md-9">
                            <img src="<?php echo url("assets/css/cropimage/img/audit.png"); ?>" id="avatar2" width="150" style="padding:2px;border:1px solid #d0d0d0;">
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
                            </div>
                            <?php echo FILESIZE_ERROR; ?>
                            </div>
                        </fieldset>
                    
                    <div class="row">
<!--                        <div class="col-sm-4 col-md-3">
                            <label for="status_id">Activity status</label>
                        </div>-->
                        <?php //show($post_status); ?>
                        <div class="col-sm-8 col-md-9">
                            <select style="visibility: hidden;" name="data[status_id]" id="audit_status_id">
                        <?php
                        if (!empty($post_status)) {
                            if ($role_id == 1) {
                                $status = array("Draft", "Final Submit");
                            } else if ($role_id == 2) {
                                $status = array("Draft");
                            } else if ($role_id == 3) {
                                $status = array("Draft", "Final Submit", "Approve & Publish", "Approve & Unpublish");
                            }
                            $status_id = $beanUi->get_view_data("status_id");
                            foreach ($post_status as $statusrow) {

                                if (in_array($statusrow->status_name, $status)) {
                                    echo '<option value="' . $statusrow->id . '" '.(($auditData[0]->status_id==$statusrow->id)?'selected':'').'>' . $statusrow->status_name . '</option>' . "\n";
                                }
                            }
                        }
                        ?>
                </select>
                <div id="status_id_error"><?php echo $beanUi->get_error('status_id'); ?></div>
                    </div>
                    </div>
                </form>
                </div>
            
                <div id="tabs-2">
                    
                   <?php 
                   if($auditData[0]->gradation_sheet_division_id == 3){
                       
                       include("add_audit_new_generation.php");
                       
                   } else {
                       
                       include("add_audit_new_nonmains.php");
                       
                   }
                   
                   ?> 
                    
                    
                </div>
                <div id="tabs-3" style="position: relative;">
            <form name="add_dev_form" id="add_dev_form" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="token_id" id="token_id" value="<?php echo $token_id; ?>" />
                <input type="hidden" name="devdata[activity_type_id]" value="<?php echo $activity_id; ?>" />
                <input type="hidden" name="auditid" id="auditId" value="<?php echo $auditId; ?>">

                <input type="hidden" name="_action" value="addDeviation" class="btn btn-sm btn-success" />
                <br>
                <br><br>
                <div id="dev-from-div">
                    <table id="" class="table table-striped table-bordered2 table-condensed table-responsive responsive-utilities jambo_table bulk_action">
                        <thead>
                            <tr>
                                <th style="width:5%;">Deviation Type</th>
                                <th style="width:15%;">Category</th>
                                <!--<th style="width:15%;"> Sub Category</th>-->
                                <th style="width:30%;">Observation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <select id="dev-type" class="width100" name="auditdevdata[devtype]">
                                        <option value="">-Select-</option>
                                        <?php foreach ($allDevType as $key => $value) { ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->type_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                
                                 <td class="text-center">
                                    <select class="req width100" id="violation_category" name="auditdevdata[category_id]">
                                        <option value="">-select category-</option>
                                        <?php foreach ($allCategories as $key => $value) { ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->group_name_alias; ?></option>
                                        <?php } ?>
                                    </select>
                                        
                                </td>
<!--                                <td class="text-center">  
                                    <select class="req width100" id="violation_subcategory" name="auditdevdata[subcategory_id]">
                                    </select> 
                                    </td>-->
                                <td>
                               <textarea class="width100 height50" rows="2" id="CSTemplates" name="auditdevdata[observation]"></textarea></td>
                                
                                <script>
                                /*$("#select1").change(function() {
                                    if ($(this).data('options') === undefined) {
                                        $(this).data('options', $('#select2 option').clone());
                                    }
                                    var id = $(this).val();
                                    var options = $(this).data('options').filter('[value=' + id + ']');
                                    $('#select2').html(options);
                                });*/
                                
                                </script>
                                <script>
                                    function showCSTemplates(sel){   
                                    locations =[ "" , "" , "" , "",               
                                    "Working at Height -\n\
                                     All personnel at height used double lanyard Safety Harness property including anchoring.\n\
                                     Fall Arrestor / Life line is used , if required.\n\
                                     Scaffolding rules are followed / Properly erected.\n\
                                     Use of Double lanyard during climbing.", ];
                                                       srcLocation = locations    [sel.selectedIndex]; //alert(srcLocation);       
                                       if (sel.selectedIndex != undefined && sel.selectedIndex != "") {      
                                                      document.getElementById('CSTemplates').innerHTML= srcLocation;   
                                     } 
                                    }
                                </script>
                                
<!--                                <td class="text-center">
                                    <select class="req width100" id="violation_category_1">
                                        <option value="" selected="selected" disabled="disabled">-select category-</option>
                                        <option value="PPE Availability">PPE Availability</option>
                                        <option value="PPE Usage">PPE Usage</option>
                                        <option value="Safe Zone Creation">Safe Zone Creation</option>
                                        <option value="Supervision Availability">Supervision Availability</option>
                                        <option value="">Process Followed up</option>
                                    </select>
                                </td>-->
                                
                            </tr>
                            <!--<tr>
                                <td colspan="7">
                                    <div class="holder">
                                        <fieldset>
                                            <legend>Upload Office Files</legend>
                                            <label for="image_path">Upload File</label>
                                            <input type="file" name="file_path[]" id="file_path" />
                                            <input type="button" id="add_upload_file" value="Add another office file" class="btn btn-sm btn-primary" />
                                            <br />
                                            <label for="caption">File Caption</label>
                                            <input type="text" name="caption[]" id="caption"  placeholder="File Caption" value="" />
                                            <div id="file_path_error"><?php echo $beanUi->get_error('file_path'); ?></div>
                                            <?php
                                            //echo FILESIZE_ERROR;
                                            //echo FILE_EXTN_ALLOWED_MSG;
                                            ?>
                                        </fieldset>				
                                    </div>
                                    <div id="extra_upload_files"></div>
                                    <div id="extra_upload_files_edit"></div>
                                </td>
                            </tr>-->
                            <tr>
                                <td colspan="7" class="text-center">
                                    <input type="hidden" name="auditdevdata[dev_id]" id="devid" value="">
                                    <button id="dev-add" type="submit" class="btn btn-sm btn-primary">Add Row</button>
                                    <a href="#" class="btn btn-sm btn-danger">Cancel</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
               </form>
                    <br/>
                    <div id="dev-lists">
                    <table id="" class="table table-striped table-bordered2 table-condensed table-responsive responsive-utilities jambo_table bulk_action">
                        <thead>
                            <tr>
                                <th style="width:7%;">Sl. No.</th>
                                <th style="width:13%;">Deviation No</th>
                                <th style="width:13%;">Deviation Type</th>
                                <th style="width:20%;">Category</th>
                                <!--<th style="width:20%;">Sub Category</th>-->
                                <th style="width:25%;">Observation</th>
                                
                                <!--<th style="width:23%;">Files</th>-->
                                <th style="width:12%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(!empty($alldeviations)){
                            $sl=1;foreach ($alldeviations as $key => $value) {
                            ?>
                            <tr id="dev-list-<?php echo $value->id; ?>">
                                <td class="text-center"><?php echo $sl; ?></td>
                                <td class="text-center"><?php echo $value->deviation_no; ?></td>
                                <td class="text-center"><?php echo $value->type_name; ?></td>
                                <td class="text-center"><?php echo $value->category_name; ?></td>
                                <!--<td class="text-center"><?php //echo $value->subcategory_name; ?></td>-->
                                <td><?php echo $value->observation; ?></td>
                                <!--<td>                                    
                                    <ol>
                                        <?php //foreach($value->devfile as $k => $v){ ?>
                                        <li><?php //echo $v->name; ?>
                                        <a href="<?php //echo '/'.FOLDERNAME.'/'.$v->file_path; ?>" download><i class="fa fa-download"></i></a>
                                        </li>
                                        <?php //} ?>    
                                    </ol>
                                </td>-->
                                <td class="text-center">
                                    <input type="hidden" id="dev-id" value="<?php echo $value->id; ?>">
                                    <a href="javascript:void(0);" onclick="editdeviation(<?php echo $value->id; ?>);" class="btn btn-sm btn-info btn-xs">Edit</a>
                                    <a href="javascript:void(0);" onclick="deldeviation(<?php echo $value->id; ?>);" class="btn btn-sm btn-danger btn-xs">Delete</a>
                                </td>
                            </tr> 
                            <?php
                            $sl++;
                             }
                            }else{?>
                                <tr>
                                    <td colspan="8" style="text-align: center;"> No Data Found </td>
                                </tr>
                            <?php } ?>                                                       
                        </tbody>
                    </table>
                    </div>
                    <br />
                    <form name="audit_remarks_form" id="audit_remarks_form" action="" method="post" enctype="multipart/form-data">
                        
                    <input type="hidden" name="token_id" id="token_id" value="<?php echo $token_id; ?>" />
                    <input type="hidden" name="devdata[activity_type_id]" value="<?php echo $activity_id; ?>" />
                    <input type="hidden" name="auditid" id="auditId" value="<?php echo $auditId; ?>">
                    <input type="hidden" name="page_no" value="<?php echo $page; ?>" />
                    <input type="hidden" name="fromdate_s" value="<?php echo $fromdate_s; ?>" />
                    <input type="hidden" name="todate_s" value="<?php echo $todate_s; ?>" />
                    <input type="hidden" name="activity_no_s" value="<?php echo $activity_no_s; ?>" />
                    <input type="hidden" name="search_title_s" value="<?php echo $search_title_s; ?>" />
                    <input type="hidden" name="status_id_s" value="<?php echo $status_id_s; ?>" />
                    <input type="hidden" name="districtid_s" value="<?php echo $districtid_s; ?>" />
                    <input type="hidden" name="_action" value="updateDeviation" class="btn btn-sm btn-success" />
                    
                    <div class="holder">
                        <?php 
                         $gradationSheetDivisionId=$auditData[0]->gradation_sheet_division_id;
                         $gradationSheetDivisionVal=0;            
                         foreach($getScoreDeviationNodalData as $sdndkey => $sdndvalue){                          
                            if($gradationSheetDivisionId == $sdndvalue->master_question_set_id){ 
                                if(strpos($division_department_mapping[0]->tree_division_id, $sdndvalue->tree_division_id) !== false){
                                  $gradationSheetDivisionVal = $sdndvalue->user_id; 
                                }
                             
                           }
                         }  //show($gradationSheetDivisionVal);                  
                        ?>
                        <label for="status_id">Action to be Taken By</label>
                        <select name="data[nodal_officer_id]" id="audit_action_taker_id" style="width:30%;pointer-events: none;">
                            <?php 
                            echo '<option value="" selected="">---Select---</option>';
                            if (!empty($nodal_officer_details)) {
                                foreach ($nodal_officer_details as $skey => $svalue) {
                                    echo "<option value='" . $svalue->id . "' ".(($svalue->id == $gradationSheetDivisionVal)?"selected":"").">" . $svalue->full_name . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <br/>
                    <div class="holder">
                        <fieldset>
                            <legend>Upload Office Image</legend>
                            <div style="width:80%;float: left;">
                            <label for="image_path">Upload Image</label>
                            <input type="file" name="file_path[]" id="file_path_0" class="filepath"/>
                            <input type="button" id="add_upload_file_action" value="Add another office Image" class="btn btn-sm btn-primary" />
                            <br />
                            <label for="caption">Image Caption</label>
                            <input type="text" name="caption[]" id="caption"  placeholder="Image Caption" value="" />
                            <div id="file_path_error"><?php echo $beanUi->get_error('file_path'); ?></div>
                            <?php
                            echo FILESIZE_ERROR;
                            echo IMAGE_EXTN_ALLOWED_MSG;
                            ?>
                            </div>
                            <div style="width:20%;float: left;padding-left: 5px;" id="prev_img_div_0">                            
                            <img id="blah_0" src="<?php echo '/'.FOLDERNAME.'/assets/images/no-image.jpg'; ?>" style="width: 100%;height: 150px;"/>
                            </div>
                        </fieldset>                        
                    </div>
                    <div id="extra_upload_files_action"></div>
                    <fieldset>
                        <legend>Upload Office Image</legend>
                        <div class="holder">
                            <ul class="image-gallery">
                            <?php $ii = 1; foreach($alldeviationsactionfile as $key => $val){ ?>
                                
                                <li id="upload_image_box_action_edit_<?php echo $val->id; ?>">
                                    <textarea name="caption_edit[]" type="text" style="width:100%;height:45px;resize:none;"><?php echo $val->name.' '; ?></textarea>
                                    <input type="hidden" name="img_edit_id[]" value="<?php echo $val->id; ?>">
                                    <a class="zoombox zgallery1" href="<?php echo '/'.FOLDERNAME.'/'.$val->file_path; ?>" title="<?php echo $ii.'. '.$val->name.' '; ?>">
                                        <img width="100%" src="<?php echo '/'.FOLDERNAME.'/'.$val->file_path; ?>" />
                                    </a>

                                    <input type="button" id="add_upload_file_actionn" value="Remove office Image" class="btn btn-xs btn-danger" onclick="remove_upload_image_box_edit_action(<?php echo $val->id; ?>);" />
                                </li>
                                <?php if($ii%3 === 0) echo '<div class="clearfix"></div>'; ?>
                                <?php $ii++; } ?>
                                
                            </ul>
                           
                            <?php
                            //echo FILESIZE_ERROR;
                            //echo FILE_EXTN_ALLOWED_MSG;
                            ?>   
                        				
                        </div>
                   </fieldset>
                    <div class="holder">                        
                        <fieldset>
                            <legend>Upload Office File</legend>
                            <label for="image_path">Upload File</label>
                            <input type="file" name="file_path_file[]" id="file_path_file" accept="application/pdf"/>
                            <input type="button" id="add_upload_file_action_file" value="Add another office File" class="btn btn-sm btn-primary" />
                            <br />
                            <label for="caption">File Caption</label>
                            <input type="text" name="caption_file[]" id="caption"  placeholder="File Caption" value="" />
                            <div id="file_path_error"><?php echo $beanUi->get_error('file_path_file'); ?></div>
                            <?php
                            echo FILESIZE_ERROR;
                            echo FILE_EXTN_ALLOWED_MSG;
                            ?>
                        </fieldset>				
                    </div>
                    <div id="extra_upload_files_action_file"></div> 
                    <fieldset>
                        <legend>Uploaded Office File</legend>
                            <div class="holder">
                                <ul class="file-list">
                                    <?php $jj = 1; foreach($alldeviationsactionfile1 as $key1 => $val1){ ?>
                                    <li id="upload_image_box_action_edit_file_<?php echo $val1->id; ?>">
                                       
                                            <a href="<?php echo '/'.FOLDERNAME.'/'.$val1->file_path; ?>" target="_blank" download><i class="fa fa-download download-icon"></i>
                                                 <?php echo $jj.'. '.($val1->name?$val1->name:'New File').' '; ?>
                                            </a> &nbsp; &nbsp; &nbsp;                              
                                          <input type="button" id="add_upload_file_actionn_file" value="Remove office Image" class="btn btn-xs btn-danger" onclick="remove_upload_image_box_edit_action_file(<?php echo $val1->id; ?>);" />
                                        </li>

                                    <?php $jj++; } ?>
                                </ul>
                            </div>    
                    </fieldset>
                    <br/>

                    <div class="holder">
                        <label for="remarks">Remarks</label>
                        <textarea name="data[remarks]" id="remarks" style="width: 100%; height: 60px;"><?php echo $auditData[0]->remarks; ?></textarea>
                        <div id="remarks_error"><?php echo $beanUi->get_error('remarks'); ?></div>
                    </div>
                    <br />
                    
                    <br />
                    <div class="holder">
                        <label for="status_id">Activity status</label>
                        <?php //show($post_status); ?>
                        <select name="data[status_id]" id="audit_status_id_do">
                        <?php
                        
//                        $created_by = $beanUi->get_view_data("created_by");
//                        $role_id = $controller->get_auth_user("role_id");
                        if (!empty($post_status)) {
                            if ($role_id == 1) {
                                $status = array("Draft", "Final Submit");
                            } else if ($role_id == 2) {
                                $status = array("Draft", "Final Submit");
                            } else if ($role_id == 3) {
                                $status = array("Draft", "Final Submit", "Approve & Publish", "Approve & Unpublish");
                            }
                            $status_id = $beanUi->get_view_data("status_id");
                            foreach ($post_status as $statusrow) {

                                if (in_array($statusrow->status_name, $status)) {
                                    echo '<option value="' . $statusrow->id . '" '.(($auditData[0]->status_id==$statusrow->id)?'selected':'').'>' . $statusrow->status_name . '</option>' . "\n";
                                }
                            }
                        }
                        ?>
                </select>
                <div id="status_id_error"><?php echo $beanUi->get_error('status_id'); ?></div>
                    </div>
                    
                    <div class="freeze-container3">
                        <div class="row">
                            <div class="col-sm-2"><button type="submit" name="prevtab" class="btn btn-warning btn-xs" value="Previous Tab" text="Previous Tab"><i class="fa fa-arrow-left"></i> Previous Tab</button></div>
                            <div class="col-sm-8 text-center">
                                <?php 
                                    if($auditData[0]->status_id == 1){
                                        echo '<label class="text-primary"><strong>Draft</strong></label> &nbsp; &nbsp;'; 
                                    } else if($auditData[0]->status_id == 2){
                                        echo '<label class="text-danger"><strong>Final Submit</strong></label> &nbsp; &nbsp;';
                                    } else if($auditData[0]->status_id == 3){
                                        echo '<label class="text-success"><strong>Approve & Publish</strong></label> &nbsp; &nbsp;';
                                    } else if($auditData[0]->status_id == 4) {
                                        echo '<label class="text-warning"><strong>Approve & Unpublish</strong></label>';
                                    }
                                ?>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
                            <a href="<?php echo page_link("activity/index.php?activity_id= $activity_id ");?>" onclick="return confirm('Are you sure to cancel this page ?')" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Cancel</a>
                            </div>
                        </div>
                    </div>
<!--                    <div class="clearfix">
                        
                    
                        <p class="text-center">
                            <button type="submit" name="prevtab" class="btn btn-warning btn-xs" value="Previous Tab" text="Previous Tab"><i class="fa fa-arrow-left"></i> Previous Tab</button></div>
                        </p>
                    </div>
                    
                    <div class="clearfix">
                    <div class="col-xs-6">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
                            <a href="<?php echo page_link("activity/index.php?activity_id= $activity_id ");?>" onclick="return confirm('Are you sure to cancel this page ?')" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Cancel</a>
                    </div>
                     <div class="col-xs-6 text-right">-->
                    <!--<button type="submit" name="nexttab" class="btn btn-warning btn-xs" value="Next Tab"  text="Next Tab">Next Tab <i class="fa fa-arrow-right"></i></button>-->
<!--                     </div>
                </div>-->
                      
                    </form>
                </div>
            </div>
                <!--<div class="Footer clearfix">
                    <div class="col-xs-6">
                    <button id="btnMoveLeftTab" class="btn btn-warning btn-xs" type="button" value="Previous Tab" text="Previous Tab"><i class="fa fa-arrow-left"></i> Previous Tab</button>
                    </div>
                     <div class="col-xs-6 text-right">
                    <button id="btnMoveRightTab" class="btn btn-warning btn-xs" type="button" value="Next Tab"  text="Next Tab">Next Tab <i class="fa fa-arrow-right"></i></button>   
                
                     </div>
                </div>-->    
                <div class="Clearboth"></div>
                <script>$("#tabs").tabs();</script>
                <!-Need to put here to let the tabs construct first-->
                <script>
                            $(function() {
                                var url = window.location.href; 
                                var withOutHash = location.href.replace(location.hash, "");
                                var hash = window.location.hash;
                                if(url == (withOutHash + '#tabs-1')){
                                    $("#btnMoveLeftTab").hide();
                                    $("#btnMoveRightTab").show();
                                }
                                if(url == (withOutHash + '#tabs-2')){
                                    $("#btnMoveLeftTab").show();
                                    $("#btnMoveRightTab").show();
                                }
                                if(url == (withOutHash + '#tabs-3')){
                                    $("#btnMoveLeftTab").show();
                                    $("#btnMoveRightTab").hide();
                                }
                            var selectedTab = $(document).find('div[class^="ui-tabs"]').first();
                                    $(".Footer").on('click', ':button', function() {
                            var selected = selectedTab.tabs("option", "active");
                                    if (this.id == "btnMoveLeftTab") {
                            if (selected >= 1) {
                            selectedTab.tabs("option", "active", selected - 1);
                            }
                            } else {
                            selectedTab.tabs("option", "active", selected + 1);
                            }
                            });
                                    selectedTab.tabs({
                                    activate: function(event, ui) {
                                    var active = selectedTab.tabs("option", "active");
                                            var selected = selectedTab.tabs("option", "active");
                                            if (active == 0) {
                                    $("#btnMoveLeftTab").hide();
                                            $("#btnMoveRightTab").show();
                                    }
                                    else if (active == 2) {
                                    $("#btnMoveRightTab").hide();
                                            $("#btnMoveLeftTab").show();
                                    }
                                    
                                    else {
                                    $("#btnMoveLeftTab").show();
                                            $("#btnMoveRightTab").show();
                                    }
                                    return selected;
                                    }
                                    });
                                    //$("#btnMoveLeftTab").hide();
                                    //$("#btnMoveRightTab").hide();
                            });                 </script>       
        
    </div>
</div>
<?php $controller->get_footer(); ?>
<script type="text/javascript" src="<?php echo url("assets/js/autoNumeric.js"); ?>"></script>
<script type="text/javascript">
                            jQuery(function($) {
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
                                                if( $currentDate >= CHECK_DATE && ($role_id != 1 && $role_id != 3)) { ?>////
                                                minDate: new Date(currentYear, currentMonth, currentDate-(currentDate-1)),
                                                <?php } if( $currentDate < CHECK_DATE && ($role_id != 1 && $role_id != 3)) { ?>
                                        minDate: new Date(currentYear, (currentMonth-1), currentDate-(currentDate-1)),

                                        <?php } ?>
                                                step: 5
                                                });

                                        });	




//                                        var $s=jQuery.noConflict();
//                                        $s("#datetimepicker1").on("change", function (e) {		 
//                                            var selected_date = $s('#datetimepicker1').val();
//                                            if( selected_date != "" ){
//                                                var ajax_data = {
//                                                    "action"	: "getDataByDate",
//                                                    "selected_date"	: selected_date,
//                                                    "activity_id" 	: <?php echo $activity_id; ?>,
//                                                    "table_name"	: "audit_view",
//                                                };
//                                                $s.ajax({
//                                                    type: 'post', 
//                                                    cache: false,
//                                                    data: ajax_data,
//                                                    success: function (getDataByDate) {
//                                                        if(getDataByDate) {
//                                                            $s("#showrelatedData").html(getDataByDate);
//                                                        }
//                                                        return false;
//                                                    }
//                                                });
//                                            }
//                                            return false;
//                                        });
                                    /*Datepicker*/

                                    /*time of_audit calculation*/
                                            function actualTime(reportingTime, releasingTime) {
                                            if (reportingTime != '' && releasingTime != '')  {
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
                                                    if (actualHours < 0){
                                            alert("Please check your reporting and releasing Times");
                                                    return false;
                                            } else{
                                            var actualHour = parseInt(actualHours / 60);
                                                    var actualMin = parseInt(actualHours % 60);
                                                    actualMin = ("0" + actualMin).slice( - 2);
                                                    var actualTime = actualHour + ":" + actualMin;
                                                    return actualTime;
                                            }
                                            }
                                            }

                                    $(function(){
                                    $(".combodate").change(function() {
                                    var asd = '';
                                            $(".combodate").each(function() {
                                    asd += ($(this).val() ? $(this).val() : '00') + ":";
                                    });
                                            $("#datetimepicker2").val(asd.slice(0, - 1));
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
                                    } else{
                                    var rept = reportingTime.split(":");
                                            var reptHr = rept[0];
                                            var reptMin = rept[1];
                                            var rept2 = releasingTime.split(":");
                                            var relsMin2 = rept2[1];
                                            var relsHr = rept2[0];
                                            if (relsMin2 < reptMin && relsMin2 != "00"){
                                    $(".combodateto").val('');
                                            $("#datetimepicker22").val('');
                                            $("#audit_duration").val('');
                                            alert("Realeasing time should be greater than Reporting times..");
                                    }
                                    $("#audit_duration").val("");
                                    }
                                    }
                                    });
                                            $(".combodateto").change(function() {
                                    var asd = '';
                                            $(".combodateto").each(function(){
                                    asd += ($(this).val() ? $(this).val() : '00') + ":";
                                    });
                                            $("#datetimepicker22").val(asd.slice(0, - 1));
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
                                    } else{
                                    var rept = reportingTime.split(":");
                                            var reptHr = rept[0];
                                            var reptMin = rept[1];
                                            var rept2 = releasingTime.split(":");
                                            var relsMin2 = rept2[1];
                                            var relsHr = rept2[0];
                                            if ((relsMin2 < reptMin && relsMin2 != "00") || (relsHr < reptHr)){
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
                                            

                                            /*time of_audit calculation*/
                                            var $m = jQuery.noConflict();
                                            $m(function(){
                                            var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
                                                    $m('a[data-modal-id]').click(function(e) {
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
                                                    $m(".js-modal-close, .modal-overlay").click(function() {
                                            $m(".modal-box, .modal-overlay").fadeOut(500, function() {
                                            $m(".modal-overlay").remove();
                                            });
                                                    return false;
                                            });
                                                    $m(window).resize(function() {
                                            $m(".modal-box").css({
                                            top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
                                                    left: "10%"
                                            });
                                            });
                                                    $m(window).resize();
                                            });
                                            // Featured image
                                            var $s = jQuery.noConflict();
                                            $s(function() {
                                            var time = function(){return'?' + new Date().getTime()};
                                                    // Avatar setup
                                                    $s('#avatarModal').imgPicker({
                                            url: 'server/upload_avatar.php',
                                                    aspectRatio: 1,
                                                    deleteComplete: function() {
                                                    $s('#avatar2').attr('src', 'assets/img/audit.png');
                                                            this.modal('hide');
                                                    },
                                                    uploadSuccess: function(image) {
                                                    var select = [0, (image.height - image.width) / 2, 250, 250];
                                                            this.options.setSelect = select;
                                                    },
                                                    cropSuccess: function(image) {
                                                    $s('#avatar2').attr('src', image.versions.avatar.url + time());
                                                            $s('#avatar3').attr('value', image.versions.avatar.url + time());
                                                            $s('#avatar4').attr('value', image.versions.avatar.url + time());
                                                            this.modal('hide');
                                                    }
                                            });
                                                    // Demo only
                                                    $('.navbar-toggle').on('click', function(){$('.navbar-nav').toggleClass('navbar-collapse')});
                                                    $(window).resize(function(e){if ($(document).width() >= 430)$('.navbar-nav').removeClass('navbar-collapse')});
                                            });
                                            // End
                                            $(document).ready(function(){
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
                                    if (jQuery.trim($(this).val()) == ''){
                                    $(".req").css("border-color", "red");
                                            flag = false;
                                    }
                                    return flag;
                                    });
                                            $('.req').each(function () {
                                    if (jQuery.trim($(this).val()) != ''){
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
                                    "action"                    : "save_violation",
                                            "violation_type"            : violation_type,
                                            "description"               : description,
                                            "violation_category"        : violation_category,
                                            "remarks"                   : remarks,
                                            "nof"                       : no_of_parti,
                                            "token_id"                  : token_id
                                    };
                                            $.ajax({

                                            type: 'post',
                                                    cache : false,
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
                                            $("#add_upload_file_action").click(function () {
                                    var boxnumber = 1 + Math.floor(Math.random() * 6);
                                            var another_image_upload_html =
                                            '<div class="holder" id="upload_image_box_action_' + boxnumber + '">' + "\n" +
                                            '<fieldset>' + "\n" +
                                            '<legend>Upload Image</legend>' + "\n" +
                                            '<div style="width:80%;float: left;">'+
                                            '<label for="image_path">Upload Image</label>' + "\n" +
                                            '<input type="file" name="file_path[]" id="file_path_'+boxnumber+'"  class="filepath" onChange="readURLImage(this);"/>' + "\n" +
                                            '<input type="button" value="Remove office Image" class="btn btn-danger btn-sm" onclick="remove_upload_image_box_action(' + boxnumber + ');" />' + "\n" +
                                            '<br />' +
                                            '<label for="caption">Image Caption</label>' + "\n" +
                                            '<input type="text" name="caption[]" id="caption"  placeholder="Image Caption" />' + "\n" +
                                            '<?php
                                                echo FILESIZE_ERROR;
                                                echo IMAGE_EXTN_ALLOWED_MSG;
                                            ?>'+
                                            '</div>' + "\n" +
                                            '<div style="width:20%;float: left;padding-left: 5px;" id="prev_img_div_'+boxnumber+'">'+
                                            '<img id="blah_'+boxnumber+'" src="<?php echo '/'.FOLDERNAME.'/assets/images/no-image.jpg'; ?>" style="width: 100%;height:150px;"/></div>'+
                                            '</div></fieldset>' + "\n";
                                            $("#extra_upload_files_action").append(another_image_upload_html);
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
                                    $("#add_upload_file_action_file").click(function () {
                                    var boxnumber = 1 + Math.floor(Math.random() * 6);
                                            var another_image_upload_html =
                                            '<div class="holder" id="upload_image_box_action_new_' + boxnumber + '">' + "\n" +
                                            '<fieldset>' + "\n" +
                                            '<legend>Upload File</legend>' + "\n" +
                                            '<label for="image_path">Upload File</label>' + "\n" +
                                            '<input type="file" name="file_path_file[]" id="file_path_file" accept="application/pdf"/>' + "\n" +
                                            '<input type="button" value="Remove office File" class="btn btn-danger btn-sm" onclick="remove_upload_image_box_action_new(' + boxnumber + ');" />' + "\n" +
                                            '<br />' +
                                            '<label for="caption">File Caption</label>' + "\n" +
                                            '<input type="text" name="caption_file[]" id="caption"  placeholder="Image Caption" />' + "\n" +
                                            '</fieldset>' + "\n" +
                                            '</div>' + "\n";
                                            $("#extra_upload_files_action_file").append(another_image_upload_html);
                                    });
                                            
//                                            $("#create_post").submit(function () {
//                                    var date_of_audit = jQuery.trim(jQuery("#datetimepicker1").val());
//                                            var datetimepicker2 = jQuery.trim(jQuery("#datetimepicker2").val());
//                                            var datetimepicker22 = jQuery.trim(jQuery("#datetimepicker22").val());
//                                            var place = jQuery.trim(jQuery("#place").val());
//                                            var job_title = jQuery.trim(jQuery("#job_title").val());
//                                            var audit_by = jQuery.trim(jQuery("#audit_by").val());
//                                            var avg_mark = jQuery.trim(jQuery("#avg_mark").val());
//                                            var division = jQuery.trim(jQuery("#division").val());
//                                            var set_type3 = jQuery.trim(jQuery("#set_type3").val());
//                                            var error_counter = 0;
//                                            jQuery(".error").empty();
//                                            if (division == undefined || division == "")
//                                    {
//                                    jQuery("#division_error").html("<div class=\"error\">Division is required.</div>");
//                                            error_counter++;
//                                    }
//                                    if (set_type3 == undefined || set_type3 == "")
//                                    {
//                                    jQuery("#set_type_error").html("<div class=\"error\">Set type is required.</div>");
//                                            error_counter++;
//                                    }
//                                    if (date_of_audit == undefined || date_of_audit == "")
//                                    {
//                                    jQuery("#date_of_audit_error").html("<div class=\"error\">Date of audit is required.</div>");
//                                            error_counter++;
//                                    }
//                                    if (datetimepicker2 == undefined || datetimepicker2 == "" || datetimepicker22 == undefined || datetimepicker22 == "")
//                                    {
//                                    jQuery("#time_of_audit_error").html("<div class=\"error\">Audit time is required.</div>");
//                                            error_counter++;
//                                    }
//                                    if (place == undefined || place == "")
//                                    {
//                                    jQuery("#place_error").html("<div class=\"error\">Venue is required.</div>");
//                                            error_counter++;
//                                    }
//                                    if (job_title == undefined || job_title == "")
//                                    {
//                                    jQuery("#job_title_error").html("<div class=\"error\">Job Title is required.</div>");
//                                            error_counter++;
//                                    }
//                                    if (audit_by == undefined || audit_by == "")
//                                    {
//                                    jQuery("#audit_by_error").html("<div class=\"error\">Audit By is required.</div>");
//                                            error_counter++;
//                                    }
//
//                                    if (avg_mark == undefined || avg_mark == "")
//                                    {
//                                    jQuery("#avg_mark_error").html("<div class=\"error\">Average mark is required.</div>");
//                                            error_counter++;
//                                    }
//                                    if (error_counter == 0) {
//                                    $(".disablebutton").attr('disabled', true);
//                                    }
//                                    if (error_counter > 0) {
//                                    jQuery(".message").html("<div class=\"error\">All <span style=\"color:#ee0000;\">*</span> mark fields are mandatory.</div>");
//                                            jQuery('html, body').animate({ scrollTop: 0}, 'slow');
//                                            return false;
//                                    }
//                                    });
                                    });
                                            function remove_upload_image_box(boxnumber) {
                                            jQuery("#upload_image_box_" + boxnumber).remove();
                                            }
                                            function remove_upload_image_box_action(boxnumber) {
                                            jQuery("#upload_image_box_action_" + boxnumber).remove();
                                            }
                                            function remove_upload_image_box_action_new(boxnumber) {
                                            jQuery("#upload_image_box_action_new_" + boxnumber).remove();
                                            }
                                            function remove_upload_image_box_edit(boxnumber) {
                                            var cnf=confirm("Are you want to delete?");
                                            if(cnf){
                                                var ajax_data = {
                                                    "action" : "deldeviationimg",
                                                    "dev_id" : boxnumber
                                                };
                                                $.ajax({
                                                    type: 'post',
                                                    cache: false,
                                                    data: ajax_data,
                                                    success: function (getsubcatdata) {
                                                        if (getsubcatdata) {
                                                            jQuery("#upload_image_box_" + boxnumber).remove();
                                                        }                                
                                                    }
                                                });   
                                            }    
                                            
                                            }
                                            
                                            function remove_upload_image_box_edit_action(boxnumber) {
                                            var cnf=confirm("Are you want to delete?");
                                            if(cnf){
                                                var ajax_data = {
                                                    "action" : "deldeviationimgaction",
                                                    "dev_id" : boxnumber
                                                };
                                                $.ajax({
                                                    type: 'post',
                                                    cache: false,
                                                    data: ajax_data,
                                                    success: function (getsubcatdata) {
                                                        if (getsubcatdata) {
                                                            jQuery("#upload_image_box_action_edit_" + boxnumber).remove();
                                                        }                                
                                                    }
                                                });   
                                            }    
                                            
                                            }
                                            
                                            function remove_upload_image_box_edit_action_file(boxnumber) {
                                            var cnf=confirm("Are you want to delete?");
                                            if(cnf){
                                                var ajax_data = {
                                                    "action" : "deldeviationimgaction",
                                                    "dev_id" : boxnumber
                                                };
                                                $.ajax({
                                                    type: 'post',
                                                    cache: false,
                                                    data: ajax_data,
                                                    success: function (getsubcatdata) {
                                                        if (getsubcatdata) {
                                                            jQuery("#upload_image_box_action_edit_file_" + boxnumber).remove();
                                                        }                                
                                                    }
                                                });   
                                            }    
                                            
                                            }
                                            
                                            function readURLImage(input){                                                
                                                var did=input.id;
                                                var res = did.split("_");                                                
                                                var file_data = input.files[0];   
                                                var form_data = new FormData();                  
                                                form_data.append('file', file_data);
                                                 var reader = new FileReader();    
                                                reader.onload = function(el) {
                                                  $('#blah_'+res[2]).attr('src', el.target.result);
                                                }
                                                reader.readAsDataURL(file_data);
                                            }
</script>

<link rel="stylesheet" href="<?php echo url('assets/css/jqueryui/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-1.12.4.js') ?>"></script>
<script type="text/javascript" src="<?php echo url('assets/js/jqueryui/jquery-ui.js') ?>"></script>

                                        <script type="text/javascript">

                                            $(function() {
                                            var ajax_data = {
                                            "action"	  : "getVenueList",
                                            "activity_type_id" : "<?php echo $activity_id; ?>"
                                            };
                                                    $.ajax({
                                                    type: 'post', url: '<?php echo page_link("activitynew/add_audit_new.php"); ?>', cache: false,
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
                     
                     /* DEVIATION OBSERVATION PART */
                    $("#violation_category").on('change',function(e) {
                        e.preventDefault();
                        var catid = $(this).val();
                        var ajax_data = {
                            "action"	  : "getSubcategory",
                            "category_id" : catid
                        };
                        $.ajax({
                            type: 'post',
                            cache: false,
                            data: ajax_data,
                            success: function (getsubcatdata) {
                                if (getsubcatdata) {
                                    $("#violation_subcategory").html(getsubcatdata);
                                }
                                return false;
                            }
                        });
                    });
                    
                    $("#violation_subcategory").change(function(e) {
                        e.preventDefault();
                        var subcatid = $(this).val();
                        var ajax_data = {
                            "action"	  : "getSubcategoryDetails",
                            "subcategory_id" : subcatid
                        };
                        $.ajax({
                            type: 'post',
                            cache: false,
                            data: ajax_data,
                            success: function (getsubcatdata) {
                                if (getsubcatdata) {
                                    $("#CSTemplates").val(getsubcatdata);
                                }
                                return false;
                            }
                        });
                    });
                    
                    $("#caption-upload").click(function() {
                        var file_data = document.getElementById("caption-img").files[0];   
                        var form_data = new FormData();                  
                        form_data.append('file', file_data);
                        //form_data.append("user_id", 123);
                        var ajax_data = {
                            "action"	  : "uploadcaption",
                            "imgdata" : form_data
                        };
                        $.ajax({
                            dataType: 'text',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: form_data,
                            type: 'post',
                            success: function (getsubcatdata) {
                                
                            }
                        });
                                             
                        return false; 
                    });
                    
                    $("#add_dev_form").submit(function() {
                        var catid = $("#violation_category").val();
                        var subcatid = $("#violation_subcategory").val();
                        var devtype = $("#dev-type").val();
                        var auditid = $("#activity_id").val();
                        var observation = $("#CSTemplates").val();
                        var dev_id=$("#devid").val();
                        if(devtype==''){
                           alert("Please select Deviation Type");
                           $("#dev-type").focus();
                           return false;
                        }
                        if(catid==''){
                           alert("Please select Category");
                           $("#violation_category").focus();
                           return false;
                        }
                        if(subcatid==''){
                           alert("Please select Sub Category");
                           $("#violation_subcategory").focus();
                           return false;
                        }
                        if(observation==''){
                           alert("Please write text in Observation");
                           $("#CSTemplates").focus();
                           return false;
                        }
                    });
                    /* DEVIATION OBSERVATION PART */
                    
                    /* AUDIT CREATION FORM VAIDATION */
                        $("#audit_creation_form").submit(function() {
                            var gradation_sheet_division_id=$("#gradation_sheet_division_id").val();
                            var datetimepicker1=$("#datetimepicker1").val();
                            if(gradation_sheet_division_id==''){
                              alert("Please select Site Audit Gradation Sheet");
                              $("#gradation_sheet_division_id").focus();
                              return false;
                            }
                            if($("#set_type3").val()==''){
                              alert("Please select Division Department and Set Type");
                              $("#set_type3").focus();
                              return false;
                            }
                            if(datetimepicker1 ==''){
                                alert("Please select Date of Audit");
                                $("#datetimepicker1").focus();
                                return false;
                            }
                                
                            var audit_status_id=$("#audit_status_id").val();
//                            if(audit_status_id!=1){
//                                if($("#audit_action_taker_id").val()==''){
//                                    alert("Please select Action Taker");
//                                    $("#audit_action_taker_id").focus();
//                                    return false;
//                                }                                
//                                
//                                if($("#datetimepicker1").val()==''){
//                                    alert("Please select Date of Audit");
//                                    $("#datetimepicker1").focus();
//                                    return false;
//                                }
//                                if($("#auditfromhr").val()==''){
//                                    alert("Please select Audit start Hour");
//                                    $("#auditfromhr").focus();
//                                    return false;
//                                }
//                                if($("#auditfrommin").val()==''){
//                                    alert("Please select Audit Start Minute");
//                                    $("#auditfrommin").focus();
//                                    return false;
//                                }
//                                if($("#audittomhr").val()==''){
//                                    alert("Please select Audit End Hour");
//                                    $("#audittohr").focus();
//                                    return false;
//                                }
//                                if($("#audittomin").val()==''){
//                                    alert("Please select Audit End Minute");
//                                    $("#audittomin").focus();
//                                    return false;
//                                }
//                                if($("#place").val()==''){
//                                    alert("Please set Audit Place");
//                                    $("#place").focus();
//                                    return false;
//                                }                                
//                                if($("#auditjobtitle").val()==''){
//                                    alert("Please set Audit Job Title");
//                                    $("#auditjobtitle").focus();
//                                    return false;
//                                }
//                                if($("#auditjobrefno").val()==''){
//                                    alert("Please set Audit Job Reference No.");
//                                    $("#auditjobrefno").focus();
//                                    return false;
//                                }
//                                if($("#audit_by").val()==''){
//                                    alert("Please select Observation Made By");
//                                    $("#audit_by").focus();
//                                    return false;
//                                }
//                            }
                        });
                    /* AUDIT CREATION FORM VAIDATION */
                    
                    /* SCORESHEET UPDATE FORM */
                        $("#audit_scoresheet").submit(function() {
                            var audit_status_id=$("#auditup_status_id").val();
//                            if(audit_status_id!=1){
//                                if($("#engineer").val()==''){
//                                    alert("Please set Engineer Number");
//                                    $("#engineer").focus();
//                                    return false;
//                                }
//                                if($("#supervisor_cesc").val()==''){
//                                    alert("Please set Parmanent Supervisor Number");
//                                    $("#supervisor_cesc").focus();
//                                    return false;
//                                }
//                                if($("#supervisor_cset").val()==''){
//                                    alert("Please set Supervisor(CSET) Number");
//                                    $("#supervisor_cset").focus();
//                                    return false;
//                                }
//                                if($("#technician").val()==''){
//                                    alert("Please set Technician Number");
//                                    $("#technician").focus();
//                                    return false;
//                                }  
//                            }
                        });
                    /* SCORESHEET UPDATE FORM */
                    
                    /* AUDIT TOTAL FORM */
                        $("#audit_remarks_form").submit(function(e) {
                            e.preventDefault;
                            var audit_status_id_do  =$("#audit_status_id_do").val();
                            /*tab-1 start*/
                            var auditfromhr         = $("#audit_creation_form").find("#auditfromhr").val();                            
                            var auditfrommin        = $("#audit_creation_form").find("#auditfrommin").val();                            
                            var audittohr           = $("#audit_creation_form").find("#audittohr").val();
                            var place               = $("#audit_creation_form").find("#place").val();
                            var auditjobtitle       = $("#audit_creation_form").find("#auditjobtitle").val();                            
                            var audit_by            = $("#audit_creation_form").find("#audit_by").val();                           
                            /*tab-1 end*/
                            /*tab-2 start*/
                            var stdqntvalgn           = $("#audit_scoresheet").find(".stdqntvalgn");  
                            var avlqntvalgn           = $("#audit_scoresheet").find(".avlqntvalgn");  
                            var obrqntvalgn           = $("#audit_scoresheet").find(".obrqntvalgn");  
                            var stdqntvalnm           = $("#audit_scoresheet").find(".stdqntvalnm");  
                            var avlqntvalnm           = $("#audit_scoresheet").find(".avlqntvalnm");  
                            var obrqntvalnm           = $("#audit_scoresheet").find(".obrqntvalnm");  
                            /*tab-2 end*/
                            /*deviation observation start*/
                            var dev_type                = $("#add_dev_form").find("#dev-type").val(); 
                            var violation_category      = $("#add_dev_form").find("#violation_category").val(); 
                            var CSTemplates             = $("#add_dev_form").find("#CSTemplates").val(); 
                     
                            /*deviation observation end*/
                            if(audit_status_id_do!=1){  
                                /*tab-1 start*/
                                if(auditfromhr =='' || auditfromhr == 'HH' || auditfromhr == '00' || auditfrommin == '' || auditfrommin == 'mm' || audittohr == '' || audittohr == 'HH' || audittohr == '00' || audittomin == '' || audittomin == 'mm'){
                                    alert("Time of Audit is required in First Tab.");
                                    $("#auditfromhr").focus();
                                    return false;
                                }
                                if(place ==''){
                                    alert("Place is required in First Tab.");
                                    $("#place").focus();
                                    return false;
                                }
                                 if(auditjobtitle ==''){
                                    alert("Job Title is required in First Tab.");
                                    $("#auditjobtitle").focus();
                                    return false;
                                }
                                 if(audit_by == null){
                                    alert("Audit By is required in First Tab.");
                                    $("#audit_by").focus();
                                    return false;
                                }
                            /*tab-1 end*/
                            /*tab-2 start*/
                            /*Generation*/
                                var stdclggn=false;
                                stdqntvalgn.each(function() {
                                var stdqntvaluegn = $(this).val();
                                if(stdqntvaluegn == ''){
                                    alert("Standard Quantity is required in Second Tab.");
                                    $(".stdqntvaluegn").focus();
                                    stdclggn=true;
                                    return false;
                                }
                                    
                                });
                                if(stdclggn){
                                   stdclggn=false;
                                   return false;
                                }
                                
                                var avlclggn=false;
                                avlqntvalgn.each(function() {
                                var avlqntvaluegn = $(this).val();
                                if(avlqntvaluegn == ''){
                                    alert("Available Quantity is required in Second Tab.");
                                    $(".avlqntvaluegn").focus();
                                    avlclggn=true;
                                    return false;
                                }
                                    
                                });
                                if(avlclggn){
                                   avlclggn=false;
                                   return false;
                                }
                                
                                var obrclggn=false;
                                obrqntvalgn.each(function() {
                                var obrqntvaluegn = $(this).val();
                                if(obrqntvaluegn == ''){
                                    alert("Observation is required in Second Tab.");
                                    $(".avlqntvaluegn").focus();
                                    obrclggn=true;
                                    return false;
                                }
                                    
                                });
                                if(obrclggn){
                                   obrclggn=false;
                                   return false;
                                }
                                /*Non-Mains*/
                                var stdclgnm=false;
                                stdqntvalnm.each(function() {
                                var stdqntvaluenm = $(this).val();
                                if(stdqntvaluenm == ''){
                                    alert("Standard Quantity is required in Second Tab.");
                                    $(".stdqntvaluenm").focus();
                                    stdclgnm=true;
                                    return false;
                                }
                                    
                                });
                                if(stdclgnm){
                                   stdclgnm=false;
                                   return false;
                                }
                                
                                var avlclgnm=false;
                                avlqntvalnm.each(function() {
                                var avlqntvaluenm = $(this).val();
                                if(avlqntvaluenm == ''){
                                    alert("Available Quantity is required in Second Tab.");
                                    $(".avlqntvaluenm").focus();
                                    avlclgnm=true;
                                    return false;
                                }
                                    
                                });
                                if(avlclgnm){
                                   avlclgnm=false;
                                   return false;
                                }
                                
                                var obrclgnm=false;
                                obrqntvalnm.each(function() {
                                var obrqntvaluenm = $(this).val();
                                if(obrqntvaluenm == ''){
                                    alert("Observation is required in Second Tab.");
                                    $(".avlqntvaluenm").focus();
                                    obrclgnm=true;
                                    return false;
                                }
                                    
                                });
                                if(obrclgnm){
                                   obrclgnm=false;
                                   return false;
                                }
                            /*tab-2 end*/
                            /*tab-3 end*/
                                if($("#audit_action_taker_id").val()==''){
                                    alert("Please select Action Taker");
                                    $("#audit_action_taker_id").focus();
                                    return false;
                                } 
                            /*tab-3 end*/
                            }
                            if(dev_type != '' || violation_category != '' || CSTemplates != ''){
                                alert("Some information left behind in Deviation Details section. Please click on the Add Row button to confirm it.");
                                $("#dev_type").focus();
                                return false;
                            }
                            
                        });
                    /* AUDIT TOTAL FORM */
                    
                    $(".filepath").change(function(e) {
                        e.preventDefault;
                        var did=$(this).attr('id');
                        var res = did.split("_");                        
                        var file_data = $(this).prop('files')[0];   
                        var form_data = new FormData();                  
                        form_data.append('file', file_data);
                         var reader = new FileReader();    
                        reader.onload = function(el) {
                          $('#blah_'+res[2]).attr('src', el.target.result);
                        }
                        reader.readAsDataURL(file_data);                        
                    });
                        
                });
                
                
                
                function deldeviation(rowid){
                    var cnf=confirm("Are you want to delete?");
                    if(cnf){
                        var ajax_data = {
                            "action" : "deldeviation",
                            "dev_id" : rowid
                        };
                        $.ajax({
                            type: 'post',
                            cache: false,
                            data: ajax_data,
                            success: function (getsubcatdata) {
                                if (getsubcatdata) {
                                    $("#dev-lists").load(" #dev-lists");
                                }                                
                            }
                        });   
                    }
                }

                function editdeviation(rowid){
                    var ajax_data = {
                        "action" : "editdeviation",
                        "dev_id" : rowid
                    };
                    $.ajax({
                        type: 'post',
                        cache: false,
                        data: ajax_data,
                        dataType: 'json',
                        success: function (getsubcatdata) { 
                            if (getsubcatdata) {
                                $("#dev-type").val(getsubcatdata.devtype);
                                $("#violation_category").val(getsubcatdata.devcat);
//                                $("#violation_subcategory").html(getsubcatdata.subcattxt);
                                $("#extra_upload_files_edit").html(getsubcatdata.filetxt);
                                $("#CSTemplates").val(getsubcatdata.devsubdetails);
                                $("#devid").val(rowid);
                            }                                
                        }
                    });   
                    
                }
                
                function participantadd(catid){
                  /*var partiname=$("#partiname_"+catid).val();
                  if(partiname==""){
                      alert("Enter Name");
                      return false;
                  }else{
                    var auditpartidata={
                            "participant_category_id" : catid,
                            "name" : partiname,                           
                            "audit_id" : <?php //echo $auditId; ?>,
                            "participant_status" : $("#partistatus_"+catid).val()
                        }
                        var ajax_data = {
                            "action"	  : "saveParticipantData",
                            "auditpartidata" : auditpartidata
                        };
                         $.ajax({
                            type: 'post',
                            cache: false,
                            data: ajax_data,
                            success: function (getsubcatdata) {
                                $("#partilist").load(" #partilist");
                            }
                        });
                  }*/
                  var d = new Date();
                  var n = d.getTime();
                  var boxnumber = 1 + Math.floor(Math.random() * 6);
                  var seltab='';
                  
                    seltab='<select name="audit_participant_status_'+catid+'[]" id="partistatus_'+catid+'" style="width: 17%;">'+
                            '<option value="">---Select---</option>'+
                            '<option value="present">Present</option>'+
                            '<option value="not present">Not Present</option>'+
                            '<option value="not applicable">Not Applicable</option>'+'</select>';  
                                    
                  var another_image_upload_html ='<div id="delpartinewDiv_'+n+'"><label for=""></label>'+seltab+'<input type="text" name="audit_participant_name_'+catid+'[]" id="partiname_'+catid+'" style="width: 57%; " />'+                       
                        '<div style="width: 24%; float: right;">'+
                            '<a class="btn btn-danger" onclick="deleteNewParti('+n+')">'+'<i class="fa fa-trash">'+'</i></a>'+'</div></div>';
                    $("#particpant_new_"+catid).append(another_image_upload_html);
                }
                
                function deleteNewParti(divid){
                    $("#delpartinewDiv_" + divid).remove();
                }
                
                function deleteparticipant(rowid){
                    var cnf=confirm("Are you want to delete?");
                    if(cnf){
                        var ajax_data = {
                            "action" : "delparticipant",
                            "perti_id" : rowid
                        };
                        $.ajax({
                            type: 'post',
                            cache: false,
                            data: ajax_data,
                            success: function (getsubcatdata) {
                                if (getsubcatdata) {
                                    $("#auditpartirow_"+rowid).remove();
                                }                                
                            }
                        });   
                    }
                }
                </script>
                
                <script>
                    $(document).ready(function(){
                        //$("#ui-id-2").click(){
                     var hash = window.location.hash;
                     $(".ui-tabs-nav li a").addClass("btn disabled");
                     $(".ui-tabs-nav li a[href='"+hash+"'").parents("li").addClass("ui-state-active");
                     /*if(url == withOutHash+'#tabs-2'){
                         
                     }*/
                        var gradation_sheet_division_id=$("#gradation_sheet_division_id option:selected").val();
                            //if(gradation_sheet_division_id == ""){
                                $("#ui-id-2").addClass("btn disabled");
                                $("#ui-id-3").addClass("btn disabled");
                                $("#btnMoveRightTab").addClass("disabled");
                                $("#btnMoveRightTab").click(function(){
                                   return false; 
                                });
                            //}
                        //});
                    });
                </script>
                
<script type="text/javascript" src="<?php echo url('assets/js/zoombox.js') ?>"></script>
<script>
$(function(){
    $('a.zoombox').zoombox({
        theme: 'simple'
    });
});    
</script>
</body>
</html>
