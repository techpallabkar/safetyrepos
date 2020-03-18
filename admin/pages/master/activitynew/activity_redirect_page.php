<?php 
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityControllerNew");
$beanUi = $controller->beanUi;
$token_id = rand(000000, 111111) . time();
$controller->setCss(array("cropimage/css/imgpicker"));
$controller->setCss("tree");
$controller->get_header();
$page             = $_REQUEST["page"];
$fromdate_s       = $_REQUEST["fromdate"];
$todate_s         = $_REQUEST["todate"];
$activity_no_s    = $_REQUEST["activity_no"];
$search_title_s   = $_REQUEST["search_title"];
$status_id_s      = $_REQUEST["status_id"];
$districtid_s     = $_REQUEST["districtid"];
?>
<style type="text/css">
    .activity_redirect{
        min-height: inherit;
    }
    .actbtn{
        font-weight: 600;
        font-size:20px;
        padding: 20px;
        text-align: center;
    }
    .actbtn img {
        width: 100px;
        margin-bottom: 10px;
    }
</style>
<?php echo $beanUi->get_message(); ?>
<div class="container1">
    <h1 class="heading">User Interface</h1>
    <br>
    <div class="activity_redirect">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <button type="button" id="bttn1" class="btn btn-info btn-xs btn-block actbtn">
                        <img src="<?php echo url('assets/images/ADD-Audit.png');?>" alt=""><br>
                    Add a New Audit</button>
                </div>
                <div class="col-sm-4">
                    <button type="button" id="bttn2" class="btn btn-danger btn-xs btn-block actbtn">
                        <img src="<?php echo url('assets/images/view-audit.png');?>" alt=""><br>
                        Redirect to Audit View</button>
                </div>
                <div class="col-sm-4">
                    <button type="button" id="bttn3" class="btn btn-success btn-xs btn-block actbtn">
                        <img src="<?php echo url('assets/images/redirect.png');?>" alt=""><br>
                        Redirect to Audit List</button>
                </div>
            </div>
            <input type="hidden" name="page_no" value="<?php echo $page; ?>" />
            <input type="hidden" name="fromdate_s" value="<?php echo $fromdate_s; ?>" />
            <input type="hidden" name="todate_s" value="<?php echo $todate_s; ?>" />
            <input type="hidden" name="activity_no_s" value="<?php echo $activity_no_s; ?>" />
            <input type="hidden" name="search_title_s" value="<?php echo $search_title_s; ?>" />
            <input type="hidden" name="status_id_s" value="<?php echo $status_id_s; ?>" />
            <input type="hidden" name="districtid_s" value="<?php echo $districtid_s; ?>" />
        </div>
<!--    <table border="0" style="width:100%">
        <tr>
            <td style="width:30%"><input type="button" id="bttn1" class="btn btn-danger btn-xs actbtn" value="Add a New Audit"></td>
            <td style="width:30%"><input type="button" id="bttn2" class="btn btn-info btn-xs actbtn" value="Redirect to Audit View"></td>
            <td style="width:30%"><input type="button" id="bttn3" class="btn btn-success btn-xs actbtn" value="Redirect to Audit List"></td>
       
        </tr>
    </table>
    -->
    
</div>
</div>
</div>
<?php $controller->get_footer(); ?>
<script>
$(document).ready(function(){
   
    $("#bttn1").click(function(e) {
        e.preventDefault;
        window.location.replace("<?php echo page_link("activitynew/add_audit_new.php?activity_id=".$_REQUEST['activity_id']); ?>");
    }); 
    $("#bttn2").click(function(e) {
        e.preventDefault;
        window.location.replace("<?php echo page_link("activity/view_audit.php?actype_id=".$_REQUEST['activity_id']."&id=".$_REQUEST['auditId']); ?>");
    });
    $("#bttn3").click(function(e) {
        e.preventDefault;
        window.location.replace("<?php echo page_link("activity/index.php?page=".$page."&activity_id=".$_REQUEST['activity_id']."&fromdate=".$fromdate_s."&todate=".$todate_s."&activity_no=".$auditIdNew."&search_title=".$search_title_s."&status_id=".$status_id_s."&districtid=".$districtid_s); ?>");
    });
});
</script>
