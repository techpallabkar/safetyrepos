<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityControllerNew");
$controller->doAction();

$beanUi = $controller->beanUi;
//$post_categories = $beanUi->get_view_data("post_categories");
//$post_participants_categories = $beanUi->get_view_data("post_participants_categories");
//$post_division_department = $beanUi->get_view_data("post_division_department");
//$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
//$post_status = $beanUi->get_view_data("post_status");
//$user_details = $beanUi->get_view_data("user_details");
$auth_user_id = $controller->get_auth_user("id");
//$role_id = $controller->get_auth_user("role_id");
//$created_by = $controller->get_auth_user("created_by");
//$activity_id = $beanUi->get_view_data("activity_id");
//$activities = get_value_by_id($activity_id, "activity_name", $post_activity_type_master);
//$l1_cat_name = "";
//$tag_keys = $beanUi->get_view_data("tag_keys");
//$token_id = rand(000000, 111111) . time();
//$controller->setCss(array("cropimage/css/imgpicker"));
//$controller->setCss("tree");
$controller->get_header();



/*DEVIATION OBSERVATION LIST PART (NIRMALENDU KHAN)*/
$deviationDtls=$beanUi->get_view_data("deviationDtls");
$devComments=$beanUi->get_view_data("devComments");
$deviationFiles=$beanUi->get_view_data("deviationFiles");

$is_admin=0;
foreach($devComments AS $keyc=>$valuec){
  if($valuec->is_nodal_officer==0){
      $is_admin=1;
  }  
}
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
    .bgari-1 {background: #57AA9A; color: #fff;} 
    .bgari-2 {background: #E1EED9;} 
    .bgari-a {color: #0066cc;} 
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
    .table > tbody > tr > td {
        border-top: 0px;
    }
    .date-div {
        position: relative;
        display: inline-block;
        width:110px;
    }
    .span-icon {
        position: absolute;
        right: 10px;
        top:4px;
    }
    .date-div .datetimepicker {width: 100%;padding-right: 40px;}
</style>
<link rel="stylesheet" href="<?php echo url('assets/css/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jquery-ui.js') ?>"></script>
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
<div class="container1">
    <?php if($auth_user_id != 202 && $auth_user_id != 203 && $auth_user_id != 204 && $auth_user_id != 205 && $auth_user_id != 206){ ?>
    <h1 class="heading">Deviation Open : <span class="text-primary"><?php echo $activities; ?></span><a class="btn btn-danger btn-sm" href="deviation_view_list.php">Back</a></h1>
    <?php }else{ ?>
    <h1 class="heading">Deviation Open : <span class="text-primary"><?php echo $activities; ?></span><a class="btn btn-danger btn-sm" href="deviation_view_list_no.php">Back</a></h1>
    <?php } ?>
    <div class="message"><?php echo $beanUi->get_message(); ?></div>
    <?php echo $beanUi->get_error("status_id"); ?>
    <br />
    <div class="panel">
        <form name="create_post" id="upstatus" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="token_id" id="token_id" value="<?php echo $token_id; ?>" />
            <input type="hidden" name="data[activity_type_id]" value="<?php echo $activity_id; ?>" />
            <input type="hidden" name="_action" value="updateStatus" />
            <input type="hidden" name="deviation_id" value="<?php echo $deviationDtls->id; ?>" />
            <br />
            <table class="table table-bordered2" style="background:#a3e4d7; ">
                <tbody>
                    <tr>
                        <td class="bgari-1"><b>Sl. No. : </b></td>
                        <td>
                            <b><?php echo $deviationDtls->deviation_no; ?></b>
                        </td>
                        <td class="bgari-1"><b>Type : </b></td>
                        <td>
                            <b><?php echo $deviationDtls->type_name; ?></b>
                        </td>
                        <td class="bgari-1"><b>Category : </b></td>
                        <td colspan="2">
                            <b><?php echo $deviationDtls->category_name; ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="bgari-1"><b>Description : </b></td>
                        <td colspan="6">
                            <b><?php echo $deviationDtls->observation; ?>.</b>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <td width="15%" class="bgari-1"><b>Current Status : </b></td>
                        <td width="15%"><b><?php echo (($deviationDtls->status==1)? 'Open':(($deviationDtls->status==2)? 'Closed(Not Satisfied)' : (($deviationDtls->status==3) ? 'Closed' : 'Replied'))); ?></b></td>
                        <td width="15%" class="bgari-1"><b>Update Status : </b></td>
                        <td width="10%">
                            <?php if($deviationDtls->status==1){ ?>
                            <select id="dstatus" name="dstatus" style="width: 100%;">
                                <option value="">Select</option>                                
                                <option value="2">Close(Not Satisfied)</option>
                                <option value="3">Close</option>
                            </select>
                            <?php } ?>
                        </td>
                        <td width="10%" class="bgari-1"><b>Date :</b></td>
                        <td width="30%">
                            <?php if($deviationDtls->status==1){ ?>
                            <div class="date-div">
                                <span class="span-icon"><i class="fa fa-calendar"></i></span>
                            <input type="text" class="datetimepicker" name="devmoddate" id="devmoddate" value="<?php echo date("Y-m-d");?>"/>
                            </div>
                            <?php } ?>
                        </td>
                       
                    </tr>
                </tbody>
            </table>        
        <br/>       
            <h1 class="heading" style="border-bottom:3px solid #ff9f08;">Comments </h1>
            <?php if($deviationDtls->status==1){ ?>
            <table class="table">
                <tr>
                    <td width="20%"><b>Admin</b></td>
                    <td width="60%">
                        <textarea name="devcomment" id="devcomment" class="width100" placeholder="Enter Comment message"></textarea>
                        
                    <div class="holder">
                        <fieldset>
                            <legend>Upload File</legend>
                            <label for="image_path">Upload File</label>
                            <input type="file" name="file_path[]" id="file_path" />
                            <input type="button" id="add_upload_file" value="Add another file" class="btn btn-sm btn-primary" />
                            <br />
                            <label for="caption">File Caption</label>
                            <input type="text" name="caption[]" id="caption"  placeholder="File Caption" value="" />
                            <div id="file_path_error"><?php echo $beanUi->get_error('file_path'); ?></div>
                            <?php
                            echo FILESIZE_ERROR;
                            echo FILE_EXTN_ALLOWED_MSG;
                            ?>
                        </fieldset>				
                    </div>
            <div id="extra_upload_files"></div>
                    </td>
                    <td width="20%" style="vertical-align: bottom;"></td>
                </tr>
                <tr>
                    <td style="display: none;" class="fileshow"></td>
                    <td style="display: none;" class="fileshow">
                        <table style="width: 50%;" class="table2 table-bordered2">
                            <thead>
                                <tr>
                                    <th style="background:  #154360; color: #fff;">Files</th>
                                    <th style="background:  #154360; color: #fff;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 75%;" class="bgari-2">File Name 1</td>
                                    <td style="width: 25%;" class="text-center"><a class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="display: none;" class="fileshow"></td>
                </tr>
            </table>
            
            <script>
                $(".btn-show").click(function(){
                    $(".fileshow").css("display","table-cell");
                });
            </script>
            <p class="text-center">
                <button type="submit" class="btn btn-success">Post</button>
                <a href="<?php echo page_link('activitynew/deviation_view_list.php'); ?>" class="btn btn-danger">Cancel</a>
            </p>
            <?php } ?>
<!--            <table id="" class="table table-striped table-bordered table-condensed table-responsive responsive-utilities jambo_table bulk_action">
        <thead>
            <tr>
                <th style="width:7%;">Sl. No.</th>
                <th style="width:13%;">Deviation Type</th>
                <th style="width:25%;">Observation</th>
                <th style="width:20%;">Category</th>
                <th style="width:23%;">Files</th>
                <th style="width:12%;"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td class="text-center">NC</td>
                <td>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</td>
                <td class="text-center">SWP</td>
                <td>                                    
                    <ol>
                        <li>Abc.doc</li>
                        <li>Abc.doc</li>
                        <li>Abc.doc</li>
                        <li>Abc.doc</li>
                    </ol>
                </td>
                <td class="text-center">
                    <a href="#" class="btn btn-sm btn-info btn-xs">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger btn-xs">Cancel</a>
                </td>
            </tr>
        </tbody>
    </table>-->
        </form>
        <b>Previous Comments</b>
        <?php if(!empty($devComments)){ ?>
        <table class="table table-striped table-bordered2 table-condensed table-responsive responsive-utilities jambo_table bulk_action">
             <thead>
                <tr>
                    <th  style="width:20%;">Posted By</th>
                    <th  style="width:20%;">Posted Date</th>
                    <th  style="width:40%;">Comment</th>
                    <th style="width:20%;">File(s)</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($devComments as $key=>$value){
                $d = new DateTime($value->posted_date);
            ?>
            <tr>
                <td class="text-center"><b><?php echo $value->full_name; ?></b></td>
                <td class="text-center"><?php echo $d->format('d/m/Y'); ?> & <?php echo $d->format('h:i A'); ?></td>
                <td class="text-center"><?php echo $value->message; ?>.</td>
                <td class="text-center">
                    <?php if(!empty($deviationFiles[$value->id])){
                        foreach($deviationFiles[$value->id] AS $value1){
                    ?>
                    <?php echo (($value1->name)?$value1->name:'New File');  ?>
                    <a href="<?php echo '/'.FOLDERNAME.'/'.$value1->file_path; ?>" download><i class="fa fa-download"></i></a>
                    <br>
                    <?php                        
                        }
                    }else{
                        
                    }?>
                </td>
            </tr>            
            <?php } ?>
            </tbody>
        </table>
        <?php } ?>
    </div>
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
        jQuery.datetimepicker.setLocale('en');
        //    jQuery('.datetimepicker').datetimepicker({    
        //        timepicker:false,
        //	scrollMonth : false,
        //	scrollInput : false,
        //	format:'Y-m-d',
        //	step:5
        //    });
        //    jQuery('.datetimepicker_for_time').datetimepicker({	
        //        datepicker:false,
        //	scrollMonth : false,
        //	scrollInput : false,
        //	format:'H:i',
        //	step:1
        //    });
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
            
            $("#add_upload_file").click(function () {
                var boxnumber = 1 + Math.floor(Math.random() * 6);
                var another_image_upload_html =
                        '<div class="holder" id="upload_image_box_' + boxnumber + '">' + "\n" +
                        '<fieldset>' + "\n" +
                        '<legend>Upload File</legend>' + "\n" +
                        '<label for="image_path">Upload File</label>' + "\n" +
                        '<input type="file" name="file_path[]" id="file_path" />' + "\n" +
                        '<input type="button" value="Remove file" class="btn btn-danger btn-sm" onclick="remove_upload_image_box(' + boxnumber + ');" />' + "\n" +
                        '<br />' +
                        '<label for="caption">File Caption</label>' + "\n" +
                        '<input type="text" name="caption[]" id="caption"  placeholder="File Caption" />' + "\n" +
                        '</fieldset>' + "\n" +
                        '</div>' + "\n";
                $("#extra_upload_files").append(another_image_upload_html);
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

//        $(function () {
//            var ajax_data = {
//                "action": "getVenueList",
//                "activity_type_id": "<?php //echo $activity_id; ?>"
//            };
//            $.ajax({
//                type: 'post', url: '<?php //echo page_link("activity/add_audit.php"); ?>', cache: false,
//                data: ajax_data,
//                success: function (data) {
//                    if (data)
//                    {
//                        var myVanueList = data.split(' | ');
//                        $("#place").autocomplete({
//                            minLength: 1,
//                            source: myVanueList
//                        });
//                    }
//                }
//            });
//        });
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

            // Devition status update form //
            $('#upstatus').submit(function () {               
               var upstatus=$("#dstatus").val();
               var devmoddate=$("#devmoddate").val();
               var devcomment=$("#devcomment").val();
               if(upstatus==''){
                   alert("Select Update Status");
                   return false;
               }
               if(devmoddate==''){
                   alert("Enter Date");
                   return false;
               }
               if(devcomment==''){
                   alert("Enter Comment Message");
                   return false;
               }
            });

            // Devition status update form //           
            
        });</script>
</body>
</html>
