<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityControllerNew");
$controller->doAction();

$beanUi = $controller->beanUi;
$post_categories = $beanUi->get_view_data("post_categories");
$post_participants_categories = $beanUi->get_view_data("post_participants_categories");
$post_division_department = $beanUi->get_view_data("post_division_department");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$post_status = $beanUi->get_view_data("post_status");
$user_details = $beanUi->get_view_data("user_details");
$auth_user_id = $controller->get_auth_user("id");
$role_id = $controller->get_auth_user("role_id");
$created_by = $controller->get_auth_user("created_by");
$activity_id = $beanUi->get_view_data("activity_id");
$activities = get_value_by_id($activity_id, "activity_name", $post_activity_type_master);
$l1_cat_name = "";
$tag_keys = $beanUi->get_view_data("tag_keys");
$token_id = rand(000000, 111111) . time();
$controller->setCss(array("cropimage/css/imgpicker"));
$controller->setCss("tree");
$controller->get_header();

/*DEVIATION OBSERVATION LIST PART (NIRMALENDU KHAN)*/
$alldeviations=$beanUi->get_view_data("alldeviations");
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
    .labelcls {padding-top: 4px;width:15% !important;}
    #search-table select {width:100% !important;}
    .search{margin-bottom:0px !important;width:100% !important;}
    #search-table tr td{border: none;}
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
</style>
<link rel="stylesheet" href="<?php echo url('assets/css/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jquery-ui.js') ?>"></script>
<div class="container1">
    <hr>
    <h1 class="heading" style="margin:0 0 8px;">Deviation List 
    </h1> 
    <div class="holder">
        <form name="searchuser" id="searchuser" method="get">
            <table id="search-table" class="table table-condensed" style="margin-bottom:0px;">
                <input name="page" value="1" type="hidden">
                <input name="activity_id" value="5" type="hidden">
                <tbody>
                    <tr>
                        <td><b>From Date :</b></td>
                        <td><input autocomplete="off" name="fromdate" id="fromdate" class="search datetimepicker" value="<?php echo (isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : ""); ?>" placeholder="From date" type="text"></td>
                        <td width="10%"></td>
                        <td><b>To Date :</b></td>
                        <td><input autocomplete="off" name="todate" id="todate" class="search datetimepicker" value="<?php echo (isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : ""); ?>" placeholder="To date" type="text"></td>
                    </tr>
                    <tr>
                        <td><b>Deviation Sl. No. :</b></td>
                        <td><input name="deviation_sl_no" id="deviation_sl_no" class="search" value="<?php echo (isset($_REQUEST["deviation_sl_no"]) ? $_REQUEST["deviation_sl_no"] : ""); ?>" placeholder="Search by Deviation Sl. No." type="text"></td>
                        <td></td>
                        <td><b>Status :</b></td>
                        <td>
                            <select name="statusId" id="status_id">
                                <option value="">Search By Status</option>
                                <option value="1" <?php echo (($_REQUEST["statusId"]==1)?'selected':'') ?>>Open</option>
                                <option value="2" <?php echo (($_REQUEST["statusId"]==2)?'selected':'') ?>>Closed(Not Satisfied)</option>
                                <option value="3" <?php echo (($_REQUEST["statusId"]==3)?'selected':'') ?>>Closed</option>
                                <option value="4" <?php echo (($_REQUEST["statusId"]==4)?'selected':'') ?>>Replied</option>
                            </select>                    
                        </td>                       
                    </tr>
                    <tr>
                        <td colspan="5" align="center">
                            <input type="hidden" name="_action" value="searchDeviation" />
                            <input value="Go" class="btn btn-sm btn-primary" type="submit">
                            <a href="<?php echo page_link('activitynew/deviation_view_list.php'); ?>" id="set_src_data" class="btn btn-sm btn-danger">Reset</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <hr style="padding:2px;">
    </div>


    <div class="message"></div>
    <table id="showdata" class="table table-striped table-bordered2 table-condensed table-responsive responsive-utilities jambo_table bulk_action">
        <thead>
            <tr>
                <th style="width:5%;">Sl. No.</th>
                <th style="width:10%;">Deviation Sl. No.</th>
                <th style="width:10%;">Deviation Type</th>
                <th style="width:17%;">Category</th>
                <!--<th style="width:17%;">Sub Category</th>-->
                <th style="width:23%;">Observation</th>
                <th style="width:20%;">Status</th>
                <th style="width:20%;">View</th>
            </tr>
        </thead>
        <tbody>

            <?php 
            if(!empty($alldeviations)){
                $sl=1;
                foreach ($alldeviations as $key => $value) {
            ?>
            <tr id="dev-list-<?php echo $value->id; ?>">
                <td class="text-center"><?php echo $sl; ?></td>
                <td class="text-center"><?php echo $value->deviation_no; ?></td>
                <td class="text-center"><?php echo $value->type_name; ?></td>
                <td class="text-center"><?php echo $value->category_name; ?></td>
                <!--<td class="text-center"><?php //echo $value->subcategory_name; ?></td>-->
                <td><?php echo $value->observation; ?></td>
                <td class="text-center"><?php echo (($value->status==1)? 'Open':(($value->status==2)? 'Closed(Not Satisfied)' : (($value->status==3) ? 'Closed' : 'Replied'))); ?></td>

                <td class="text-center">
                    <input type="hidden" id="dev-id" value="<?php echo $value->id; ?>">                    
                    <a href="deviationview_open.php?deviation_id=<?php echo $value->id; ?>" class="btn btn-sm btn-info btn-xs">View Deviation</a>
                    <a href="add_audit_new_view.php?audit_id=<?php echo $value->audit_id; ?>" class="btn btn-sm btn-primary btn-xs">View Activity</a>
                                    
                </td>
            </tr> 
            <?php
                $sl++;
                }
            }else{?>

            <tr>
                <td colspan="7" style="text-align: center;"> No Data Found </td>
            </tr>
            <?php } ?>

        </tbody>
    </table>
    <?php $controller->get_footer(); ?>
</body>
</html>

<!-- Datepicker -->
    <link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>
    <script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
    <script type="text/javascript">
        jQuery.datetimepicker.setLocale('en');
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
</script>
    
<!--data_table-->
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.dataTables.min.css"); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/buttons.dataTables.min.css"); ?>">
<script type="text/javascript" src="<?php echo url("assets/js/jquery.dataTables.min.js"); ?>"></script>

<script src="<?php echo url("assets/js/dataTables.buttons.min.js");?>"></script>
<script src="<?php echo url("assets/js/buttons.html5.min.js")?>"></script>
<script src="<?php echo url("assets/js/jszip.min.js")?>"></script>
<script>
   jQuery(document).ready(function(){

    jQuery('#showdata').DataTable({
        dom: 'lBfrtip',
        lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
        buttons: [],
        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         true,
        
        buttons: [
//                   {
//                    extend: 'print',
//                    exportOptions: {
//                        columns: ':visible',
//                    },
//             
//                },
//                 {extend: 'pdf',
//                     exportOptions: {
//                        columns: ':visible'
//                    }
//                 },
                 {extend: 'excel',
                      exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
             ],
        fixedColumns:   {
            leftColumns: 1,
            rightColumns: 1
        },
        stateSave: true
    });
 });
 
// function popitup(url) {
//	newwindow=window.open(url,'name','height=300,width=400');
//	if (window.focus) {newwindow.focus()}
//	return false;
//}
   
 </script>
