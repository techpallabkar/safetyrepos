<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityControllerNew");
$controller->doAction();

$beanUi                             = $controller->beanUi;
$post_categories                    = $beanUi->get_view_data("post_categories");
$post_participants_categories       = $beanUi->get_view_data("post_participants_categories");
$post_division_department           = $beanUi->get_view_data("post_division_department");
$post_activity_type_master          = $beanUi->get_view_data("post_activity_type_master");

$allauditdetails                    = $beanUi->get_view_data("allauditdetails");
$allsupcesc                         = $beanUi->get_view_data("allsupcesc");
$allsupcset                         = $beanUi->get_view_data("allsupcset");
$allengineer                        = $beanUi->get_view_data("allengineer");
$getQuestion                        = $beanUi->get_view_data("getQuestion");
$getGroup                           = $beanUi->get_view_data("getGroup");
$getdeviation                       = $beanUi->get_view_data("getdeviation");
$getGroupIdByValue                  = $beanUi->get_view_data("getGroupIdByValue");
$post_status                        = $beanUi->get_view_data("post_status");
$questionSetDetails                 = $beanUi->get_view_data("questionSetDetails");//show($questionSetDetails[0]->subheading);
$alldeviationsactionfile            = $beanUi->get_view_data("alldeviationsactionfile");
$auth_user_id                       = $controller->get_auth_user("id");
$role_id                            = $controller->get_auth_user("role_id");
$created_by                         = $controller->get_auth_user("created_by");
$is_nodal_officer                   = $controller->get_auth_user("is_nodal_officer");
$activity_id                        = $beanUi->get_view_data("activity_id");
$activities                         = get_value_by_id($activity_id, "activity_name", $post_activity_type_master);
$devition_names                     = $beanUi->get_view_data("devition_names");
$user_details                       = $beanUi->get_view_data( "user_details" );
$l1_cat_name = "";
$tag_keys = $beanUi->get_view_data("tag_keys");
$token_id = rand(000000, 111111) . time();
$type_images                = array("image/jpg", "image/jpeg", "image/png");
$type_videos                = array("video/mp4", "video/quicktime");
$pdf                        = array("application/pdf");
$controller->setCss(array("cropimage/css/imgpicker"));
$controller->setCss("tree");
$controller->get_header();
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
    }
    .image-gallery li img {
        max-width: 100%;
    }
</style>
<link rel="stylesheet" href="<?php echo url('assets/css/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jquery-ui.js') ?>"></script>
<!-- for image popup archana 23-08-19 --------->
<link rel="stylesheet" href="<?php echo url('assets/css/zoombox.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/zoombox.js') ?>"></script>
<script>
$(function(){
    $('a.zoombox').zoombox({
        theme: 'simple'
    });
});    
</script>
<!-- /end for image popup archana 23-08-19 --------->
<div class="container1">
    <?php if($is_nodal_officer != 1){ ?>
    <h1 class="heading">View Activity Details : <span class="text-primary"><?php echo $activities; ?></span><a class="btn btn-danger btn-sm" href="deviation_view_list.php">Back</a></h1>
    <?php }else{ ?>
    <h1 class="heading">View Activity Details : <span class="text-primary"><?php echo $activities; ?></span><a class="btn btn-danger btn-sm" href="deviation_view_list_no.php">Back</a></h1>
    <?php } ?>
    <div class="message"><?php echo $beanUi->get_message(); ?></div>
    <?php echo $beanUi->get_error("status_id"); ?>
    <br />
    <div class="panel">
        <form name="create_post" id="create_post" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="token_id" id="token_id" value="<?php echo $token_id; ?>" />
            <input type="hidden" name="data[activity_type_id]" value="<?php echo $activity_id; ?>" />
            <div class="holder">
                <label for="title">Activity</label>
                <b class="text-danger"> <?php echo $activities; ?> </b>
            </div>
            <br />
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Audit Information</a></li>
                    <li><a href="#tabs-2">Audit Score</a></li>
                    <li><a href="#tabs-3">Deviation Observation</a></li>
                </ul>
                <div id="dvContainer">
                <div id="tabs-1">
                <div style="text-align:center;font-size:16px;font-weight:bold;"><?php echo $questionSetDetails[0]->subheading; ?><br><?php echo $questionSetDetails[0]->heading; ?></div>     
                    <table class="table table-bordered">
                        <tbody><tr>
                                <td> <b>Participating Department : </b></td>
                                <td>
                                    <?php
					if( !empty ( $devition_names ) )
					{
					?>
					
					<?php
					$j=0;
					$valxx = array();
					foreach($devition_names as $key => $ddmrow)
					{
					$j = $j+1;
					echo '<b>'.$ddmrow.'&nbsp;&nbsp;</b>';
					}
                                        }
					?>			
                                </td>
                            </tr>
                            <tr>
                                <td width="20%"><b>Audit No : </b></td>
                                <td> </td>
                            </tr>
                            <tr>
                                <td><b>Date of Audit : </b></td>
                                <td> <?php echo $allauditdetails[0]->date_of_audit; ?></td>
                            </tr>
                            <tr>
                                <td><b>Time of Audit : </b></td>
                                <td> <?php echo $allauditdetails[0]->time_of_audit_from; ?> - <?php echo $allauditdetails[0]->time_of_audit_to; ?> AM  <b>Duration </b> (<?php echo $allauditdetails[0]->audit_duration; ?>)</td>
                            </tr>
                            <tr>
                                <td><b>Venue : </b></td>
                                <td> <span class="fa fa-map-marker"></span> <?php echo $allauditdetails[0]->place; ?> </td>
                            </tr>
                            <tr>
                                <td><b>Supervisor (CESC) : </b></td>
                                <td>
                                        <?php foreach($allsupcesc as $key => $val){  ?>
                                        <?php echo $val->name.","; ?>
                                        <?php } ?>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td><b>Supervisor (C-Set) : </b></td>
                                <td>
                                        <?php foreach($allsupcset as $key => $val){  ?>
                                        <?php echo $val->name; ?>&nbsp; &nbsp; | &nbsp; &nbsp;
                                            <?php echo $val->partcipant_status.", "; ?>
                                        <?php } ?>
                                   
                                </td>
                            </tr>
                            <tr>
                                <td><b>Engineer :</b></td>
                                <td>
                                    <table style="width: 50%;" class="table2 table-bordered2">
                                        <?php foreach($allengineer as $key => $val){  ?>
                                       <?php echo $val->name.","; ?>
                                        <?php } ?>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Job Title : </b></td>
                                <td><?php echo $allauditdetails[0]->job_title; ?></td>
                            </tr>
                            <tr>
                                <td><b>Ref./Job No. : </b></td>
                                <td><?php echo $allauditdetails[0]->ref_job_no; ?></td>
                            </tr>
                            <tr>
                                <td><b>Audit Made By : </b></td>
                                <td>
                                <?php 
                                $expvalue = explode(",", $allauditdetails[0]->audit_by);
//                                show($expvalue);
                                if (!empty($user_details)) {
                                foreach ($user_details as $skey => $svalue) {
                                    if(in_array($svalue->id, $expvalue)){
                                       echo (in_array($svalue->id, $expvalue) ? $svalue->full_name : "")."<br>";
                                    }
                                   }
                                }
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Featured Image : </b></td>
                                <td>
                                    <?php 
                                    $site_root_url = dirname(url());
                                    if($allauditdetails[0]->featured_image_path !="" ) 
                                    {
                                            echo '<div style="float:left;"><img style="float:left;" src="'.$site_root_url.'/'.$activity[0]->featured_image_path.'" alt="" width="150" height="150" class="img2">'."</div>\n";
                                    }else {
                                            echo '<div style="float:left;"><img style="float:left;padding:2px;border:1px solid #d0d0d0;" src="'.$site_root_url.'/admin/assets/css/cropimage/img/audit.png" alt="" width="150" height="150" class="img2">'."</div>\n";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="tabs-2">
                    <table id="" class="table table-striped table-bordered2 table-condensed table-responsive responsive-utilities jambo_table bulk_action" style="width: 50%;">
                        <tbody>
                            <tr>
                                <th>Engineer</th>
                                <th>Supervisor (CESC)</th>
                                <th>Supervisor (C-Set)</th>
                                <th>Workmen</th>
                                <th>Total man Power</th>
                            </tr>
                            <tr>
                                <td class="text-center"><?php echo $allauditdetails[0]->no_engineer; ?></td>
                                <td class="text-center"><?php echo $allauditdetails[0]->no_pset_supervisor; ?></td>
                                <td class="text-center"><?php echo $allauditdetails[0]->no_cset_supervisor; ?></td>
                                <td class="text-center"><?php echo $allauditdetails[0]->no_technician; ?></td>
                                <td class="text-center"><?php echo $allauditdetails[0]->total_manpower; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <br/>
                    <table id="" class="table table-striped table-bordered2 table-condensed table-responsive responsive-utilities jambo_table bulk_action">
                        <?php 
                        
                        if(!empty($getQuestion)){ 
                            foreach($getQuestion as $key => $val){
                        ?>
                        <tbody>
                            <tr>
                                <th rowspan="2" style="width:5%;">Sl. No.</th>
                                <th colspan="6"> <?php echo $getGroupIdByValue[$key]->group_name; ?></th>
                            </tr>
                            <tr> 
                                <th>Item</th>
                                <th colspan="2">Findings</th>
                                <th style="width:9%;">Full Marks</th>
                                <th style="width:9%;">Obtained Marks</th>
                                <th style="width:28%;">Remarks</th>
                            </tr>
                            <?php 
                                $slno = 1;
                                foreach($getQuestion[$key] as $k => $v){
                                    if(($v->standerd_qnt != 'NA') && ($v->observation != 'NA')){
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $slno; ?></td>
                                <td class="text-center" style="width:20%;"><?php echo $v->question; ?></td>
                                <?php if($v->observation == 'NOT APPLICABLE'){ ?>
                                
                                <td class="text-center">
                                    <p style="color:#3f75ff;font-size:12px;text-align:left;font-weight:600;margin-bottom:0px;">Required Number</p>
                                    <?php echo $v->standerd_qnt; ?>
                                </td>
                                <td class="text-center">
                                    <p style="color:#3f75ff;font-size:12px;text-align:left;font-weight:600;margin-bottom:0px;">Available Number</p>
                                    <?php echo $v->available_qnt; ?>                
                                </td>
                                    <?php } else { ?>
                                <td class="text-center" colspan="2">
                                    <p style="color:#3f75ff;font-size:12px;text-align:left;font-weight:600;margin-bottom:0px;">Observation</p>
                                    <?php echo $v->observation; ?>
                                </td>
                                <?php } ?>
                                <td class="text-center"><span><?php echo $v->full_marks; ?></span></td>
                                <td class="text-center"><span><?php echo $v->obtained_marks; ?></span></td>
                                <td><?php echo $v->remarks; ?></td>
                            </tr> 
                            <?php $slno++; } } ?>
                            
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2" class="text-right">Group Score Obtained</td>
                                <td colspan="2"class="text-center">
                                    <span><?php echo $getGroup[$key]->total_obtained_marks.'/'.$getGroup[$key]->applicable_full_marks; ?></span>
                                </td>
                                <td class="text-center"><span><?php echo $getGroup[$key]->percentage_obtanined; ?></span>%</td>
                            </tr>
                            <tr>
                                <td colspan="7" style="background:#fff;border: 0;">&nbsp;</td>
                            </tr>
                        </tbody>
                            <?php } } ?>
                    </table>
                    <br/>
                    <table id="" class="table table-striped table-bordered2 table-condensed table-responsive responsive-utilities jambo_table bulk_action table-final">
                        <tr>
                            <td class="text-right" style="width: 52%;">Score Obtained</td>
                            <td style="width: 10%;" class="text-center">
                                <span><?php echo $allauditdetails[0]->total_obtained_marks; ?></span>
                            </td>
                            <td style="width: 38%;"></td>
                        </tr>
                        <tr>
                            <td class="text-right">Applicable Group Score</td>
                            <td class="text-center"><span><?php echo $allauditdetails[0]->total_applicable_full_marks; ?></span></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-right"><b>Final Score (%)</b></td>
                            <td class="text-center"><b><span><?php echo $allauditdetails[0]->avg_mark; ?></span></b></td>
                            <td></td>
                        </tr>
                    </table>
                    <br />
                </div>
                <div id="tabs-3">

                    <table id="" class="table table-striped table-bordered table-condensed table-responsive responsive-utilities jambo_table bulk_action">
<!--                        <thead>
                            <tr>
                                <th style="width:5%;">Sl. No.</th>
                                <th style="width:10%;">Deviation Sl. No.</th>
                                <th style="width:10%;">Deviation Type</th>
                                <th style="width:10%;">Category</th>
                                <th style="width:15%;">Sub Category</th>
                                <th style="width:30%;">Observation</th>
                                <th style="width:20%;">Files</th>
                            </tr>
                        </thead>-->
                        <tbody>
                            <tr>
                                <th style="width:5%;">Sl. No.</th>
                                <th style="width:10%;">Deviation Sl. No.</th>
                                <th style="width:10%;">Deviation Type</th>
                                <th style="width:10%;">Category</th>
                                <th style="width:15%;">Sub Category</th>
                                <th style="width:30%;">Observation</th>
                                <th style="width:20%;">Files</th>
                            </tr>
                            <?php if(!empty($getdeviation)){ 
                                $sl = 1;
                                foreach($getdeviation as $key => $val){
                                ?>
                            <tr>
                                <td class="text-center"><?php echo $sl; ?></td>
                                <td class="text-center"><?php echo $val->deviation_no ?></td>
                                <td class="text-center"><?php echo $val->type_name ?></td>
                                <td class="text-center"><?php echo $val->category_name ?></td>
                                <td class="text-center"><?php echo $val->subcategory_name ?></td>
                                <td><?php echo $val->observation ?></td>
                                <td> 
                                    <ol>
                                    <?php if(!empty($val->devfile)){
                                        foreach($val->devfile AS $key1=>$value1){
                                    ?>                                    
                                        <li><?php echo $value1->name; ?>
                                        <a href="<?php echo '/'.FOLDERNAME.'/'.$value1->file_path; ?>" download><i class="fa fa-download"></i></a>
                                        </li>                                      
                                    
                                   <?php }} ?>
                                     </ol>
                                </td>
                            </tr>
                            <?php $sl++; } } ?>
                        </tbody>
                    </table>
                    <br />
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td width="23%" style="text-align: left;"><b>Action to be Taken By : </b></td>
                                <td>
                                    <b><?php
                                    if (!empty($user_details)) {
                                    foreach ($user_details as $skey => $svalue) {
                                        
                                           echo (($svalue->id == $allauditdetails[0]->nodal_officer_id) ? $svalue->full_name : "");
                                        
                                       }
                                    }
                                    ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: left;"><b>Upload Office Image : </b></td>   
                            </tr> 
                            <tr>                            
                                <td colspan="2">
                                    <ul class="image-gallery">
                                    <?php $ii = 1;$ai=0; foreach($alldeviationsactionfile as $key => $val){ 
                                        if ( in_array($val->file_type, $type_images) ){
                                    ?>
                                        <?php if($ai%3 === 0) echo '<div style="clear:both;"></div>'; $ai++; ?>
                                        <li>
                                            <div><a class="zoombox zgallery1" href="<?php echo '/'.FOLDERNAME.'/'.$val->file_path; ?>" title="<?php echo $val->name ?>">
                                        <img width="100%" src="<?php echo '/'.FOLDERNAME.'/'.$val->file_path; ?>" />
                                                </a></div>
                                            <div style="height: 40px;overflow: hidden;"><?php echo $val->name ?></div>
                                        </li>
                                         
                                        <?php $ii++; }
                                        
                                        } ?>
                                    </ul>
<!--                                        <div class="jq-scroller-wrapper">
                                        <div class="jq-scroller1">
                                        <ul class="scroller jq-scroller-mover">
                                        <li class="jq-scroller-item">
                                            <a width="30%" class="jq-scroller-preview" href="<?php echo '/'.FOLDERNAME.'/'.$val->file_path; ?>" data-title="<?php echo @$value->name ?>"> 
                                                <img width="300px" height="300px" src="<?php echo '/'.FOLDERNAME.'/'.$val->file_path; ?>" />
                                            </a>
                                        </li>
                                        </ul>
                                        </div>
                                        <i class="fa fa-arrow-circle-left jq-scroller-prev"></i>
                                        <i class="fa fa-arrow-circle-right jq-scroller-next"></i>
                                        <div class="jq-scroller-overlay">
                                        <div class="jq-overlay-item">
                                        <i class="jq-overlay-close fa fa-times-circle"></i>
                                        <i class="fa fa-arrow-circle-left jq-scroller-overlay-prev"></i>
                                        <i class="fa fa-arrow-circle-right jq-scroller-overlay-next"></i>
                                        <div class="jq-overlay-content"></div>
                                        </div>
                                        </div>
                                        </div>-->
                                        
                                    
                                
                               
                            </td>
                            </tr>                            
                            <tr>
                                <td style="text-align: left;"><b>Upload Office File : </b></td>
                                <td><?php $jj = 1; foreach($alldeviationsactionfile as $key => $val){ 
                                    if ( in_array($val->file_type, $pdf) ){ ?>
                                <?php echo $jj.'. '.$val->name.' '; ?><a href="<?php echo '/'.FOLDERNAME.'/'.$val->file_path;?>" data-title="<?php echo @$val->name ?>" target="_blank" download>
                                 <i class="fa fa-download"></i> <?php echo ($val->name?$value->name:'New File').' '; ?>
                                </a>
                                <?php $jj++; }} ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: left;"><b>Remarks : </b></td>
                                <td>
                                    <b><?php echo $allauditdetails[0]->remarks; ?></b>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <br />
                </div>
                </div>    
                <div class="Footer holder text-center">
                    <button id="btnMoveLeftTab" class="btn btn-primary" type="button" value="Previous Tab" text="Previous Tab">Previous Tab
                    </button>
                    <button id="btnMoveRightTab" class="btn btn-success" type="button" value="Next Tab"  text="Next Tab">Next Tab
                    </button>
                    <input type="button" class="btn btn-danger" value="Print" id="btnPrint" />
                </div>
                <div class="Clearboth"></div>
                <script>$("#tabs").tabs();</script>
                <!-Need to put here to let the tabs construct first-->
                <script>
                   $(function() {
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
                               else if(active == 2) {
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
                        $("#btnMoveLeftTab").hide(); 
                    });
                </script>

        </form>
    </div>
</div>
<?php $controller->get_footer(); ?>
</div>
</body>
    
</html>
<script type="text/javascript">
        //var j = jQuery.noConflict();
        $("#btnPrint").on("click", function () {
            var divContents = $("#dvContainer").html();
            var printWindow = window.open('', '', 'height=300,width=300');
            printWindow.document.write('<html><head><title></title><style>#tabs-1, #tabs-2, #tabs-3{display:block !important;font-size:8px !important;}#tabs-2, #tabs-3{page-break-before:always}body{font-family:arial;}table{width:100% !important;border-collapse:collapse;}table th, table td {border-collapse:collapse;border:1px solid #333;font-size:11px;text-align:center}#tabs-1 table td:nth-child(1), table td:nth-child(2){text-align:left;} .table-final td{text-align:center !important} #tabs-1 table tr th,#tabs-1 table tr td{font-size:15px !important;padding:5px;}#tabs-1 table tr th:nth-child(1){width:40%;}.image-gallery {margin: 0;padding: 0;list-style-type: none;text-align:left;}.image-gallery li {display: inline-block;width: 33.333%;padding: 0px 5px; float:left;box-sizing:border-box;text-align:left;}</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.print();
            printWindow.close();
        });
    </script>