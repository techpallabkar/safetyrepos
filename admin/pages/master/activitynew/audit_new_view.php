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
    select {width:100% !important;}
    .search{margin-bottom:0px !important;width:100% !important;}
    #search-table tr td{border: none;}
</style>
<link rel="stylesheet" href="<?php echo url('assets/css/jquery-ui.css') ?>" />
<script type="text/javascript" src="<?php echo url('assets/js/jquery-ui.js') ?>"></script>
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
<div class="container1">
    <!--********mbvp(pallab)**************-->
    <table id="search-table" class="table table-condensed" style="margin-bottom:0px;"><tbody><tr><td width="20%">Search By Activity :</td><td width="25%">
                    <select name="allactivity" id="allactivity" onchange="this.options[this.selectedIndex].value & amp; & amp; (window.location = this.options[this.selectedIndex].value);">

                        <option value="http://localhost/safetylive/admin/pages/master/activity/index.php? page=&amp;activity_id=1&amp;fromdate=&amp;todate=&amp;status_id=&amp;districtid=">Workshop</option><option value="http://localhost/safetylive/admin/pages/master/activity/index.php? page=&amp;activity_id=2&amp;fromdate=&amp;todate=&amp;status_id=&amp;districtid=">Communication Meeting</option><option value="http://localhost/safetylive/admin/pages/master/activity/index.php? page=&amp;activity_id=3&amp;fromdate=&amp;todate=&amp;status_id=&amp;districtid=">Training</option><option value="http://localhost/safetylive/admin/pages/master/activity/index.php? page=&amp;activity_id=4&amp;fromdate=&amp;todate=&amp;status_id=&amp;districtid=">Safety Day</option><option value="http://localhost/safetylive/admin/pages/master/activity/index.php? page=&amp;activity_id=5&amp;fromdate=&amp;todate=&amp;status_id=&amp;districtid=" selected="">Site Audit</option><option value="http://localhost/safetylive/admin/pages/master/activity/index.php? page=&amp;activity_id=6&amp;fromdate=&amp;todate=&amp;status_id=&amp;districtid=">Incident</option><option value="http://localhost/safetylive/admin/pages/master/activity/index.php? page=&amp;activity_id=7&amp;fromdate=&amp;todate=&amp;status_id=&amp;districtid=">PPE audit</option><option value="http://localhost/safetylive/admin/pages/master/activity/index.php? page=&amp;activity_id=8&amp;activity_month=&amp;activity_year=&amp;activity_no=&amp;search_title=&amp;status_id=&amp;districtid=">Safety Observation</option><option value="http://localhost/safetylive/admin/pages/master/activity/index.php? page=&amp;activity_id=11&amp;fromdate=&amp;todate=&amp;status_id=&amp;districtid=">Site Audit New</option>                </select></td><td width="55%"></td></tr></tbody></table>
    <hr>
    <!--********mbvp(pallab)**************-->
    <h1 class="heading" style="margin:0 0 8px;">Site Audit New <a href="add_audit_new.php" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a>
    </h1> 
    <div class="holder">
        <form name="searchuser" id="searchuser" method="get">
            <table id="search-table" class="table table-condensed" style="margin-bottom:0px;">
                <input name="page" value="1" type="hidden">
                <input name="activity_id" value="5" type="hidden">
                <tbody>
                    <tr>
                        <td><b>From Date :</b></td>
                        <td><input autocomplete="off" name="fromdate" id="fromdate" class="search datetimepicker" value="" placeholder="From date" type="text"></td>
                        <td width="10%"></td>
                        <td><b>To Date :</b></td>
                        <td><input autocomplete="off" name="todate" id="todate" class="search datetimepicker" value="" placeholder="To date" type="text"></td>
                    </tr>
                    <tr>
                        <td><b>Audit No. :</b></td>
                        <td><input name="search_title" id="search_title" class="search" value="" placeholder="Search by Audit no" type="text"></td>
                        <td></td>
                        <td><b>Venue : </b></td>
                        <td> <input name="search_title" id="search_title" class="search" value="" placeholder="Search by Venue" type="text"></td>
                    </tr>                
                    <tr>
                        <td><b>Status :</b></td>
                        <td>
                            <select name="status_id" id="status_id">
                                <option value="">Search By Status</option>
                                <option value="1">Draft</option>
                                <option value="2">Final Submit</option>
                                <option value="3">Approve &amp; Published</option>
                                <option value="4">Approve &amp; Unpublished</option>
                            </select>                    
                        </td>
                        <td></td>
                        <td>
                            <b>District :</b></td>
                        <td>
                            <select name="districtid" id="districtid">
                                <option value="">Search By District</option>
                                <option value="249">Total Establishment</option>
                                <option value="249-2~250-251">Generation</option>
                                <option value="249-3~250-252">Distribution</option>
                                <option value="249-4">Others</option>
                                <option value="249-2-181">BBGS</option>
                                <option value="249-2-182">TGS</option>
                                <option value="249-2-183">SGS</option>
                                <option value="249-3-5">Mains</option>
                                <option value="249-3-6">Testing</option>
                                <option value="249-3-7">Sub Station</option>
                                <option value="249-3-8">ACG</option>
                                <option value="249-3-5-9">HT</option>
                                <option value="249-3-5-10">MNTC</option>
                                <option value="249-3-5-11">CCD</option>
                                <option value="249-3-5-12">CND</option>
                                <option value="249-3-5-13">CSD</option>
                                <option value="249-3-5-14">NSD</option>
                                <option value="249-3-5-15">ND</option>
                                <option value="249-3-5-16">SD</option>
                                <option value="249-3-5-17">SWD</option>
                                <option value="249-3-5-18">WSD</option>
                                <option value="249-3-5-19">HD</option>
                                <option value="249-3-5-20">SERD</option>
                                <option value="249-4-167">Loss Control Cell (LCC)</option>
                                <option value="249-4-168">System Control</option>
                                <option value="249-4-169">Transmission Project</option>
                                <option value="249-4-170">Central Store</option>
                                <option value="249-4-171">Security</option>
                                <option value="249-4-246">Safety Department</option>
                                <option value="249-4-248">Others</option>                        
                            </select>
                        </td>
                    </tr>               
                    <tr>
                        <td><b>Deviation Type :</b></td>
                        <td>
                            <select id="" value="">
                                <option value="">-Select-</option>
                                <option value="">NC</option>
                                <option value="">CAPA</option>
                            </select>                    
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" align="center">
                            <input value="Go" class="btn btn-sm btn-primary" type="submit">
                            <a href="http://localhost/safetylive/admin/pages/master/activity/index.php?activity_id=5" id="set_src_data" class="btn btn-sm btn-danger">Reset</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <hr style="padding:2px;">
    </div>


    <div class="message"></div>
    <table id="postlist" class="table table-striped table-bordered table-condensed table-responsive responsive-utilities jambo_table bulk_action">

        <thead>
            <tr>

                <th>Division Department</th>
                <th>Date of Audit</th>
                <th>Time of Audit</th>
                <th>Venue</th>
                <th>% of Marks</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>                    
            <tr class="odd" role="row">
                <td align="left">
                    <b class="text-danger"><i>Total Establishment/Generation/SGS/EMD</i></b>                            
                    <div class="holder">
                        <i class="smallfont">Created by : Administrator, 27-06-2018 03:44 PM</i>
                    </div>
                </td>
                <td align="center">
                    27-06-2018                        
                </td>
                <td align="center">
                    10:00 AM - 11:00 AM
                </td>
                <td align="center">
                    45,KALIKAPUR ROAD,KOLKATA 700078                        
                </td>
                <td align="center">
                    55.55                        
                </td>
                <td align="center">
                    <span class="text-success">Final Submit</span>                        
                </td>
                <td width="20%" align="center">			
                    <a class="btn btn-info btn-xs" href="add_audit_new_view.php"><i class="fa fa-search"></i> View</a> 
                    <a class="btn btn-success btn-xs" href="add_audit_new.php"><i class="fa fa-pencil"></i> Edit</a>                        
                </td>
            </tr>
            <tr class="even" role="row">
                <td align="left">
                    <b class="text-danger"><i>Total Establishment/Distribution/Mains/HD/C-SET/TARAKESWAR ELECTRIC CO/O/H</i></b>                            <div class="holder">
                        <i class="smallfont">Created by : MR. INDRANATH CHAKRABORTY, 
                            20-06-2018 10:16 AM                                
                        </i>
                    </div>
                </td>

                <td align="center">
                    19-06-2018                        
                </td>
                <td align="center">
                    11:30 AM - 12:30 PM
                </td>
                <td align="center">
                    60/4, DINGSAI PARA ROAD, BALLY, HOWRAH-711201                        
                </td>
                <td align="center">
                    96.07                        
                </td>
                <td align="center">
                    <span class="text-danger">Draft</span>                        
                </td>
                <td width="20%" align="center">			
                    <a class="btn btn-info btn-xs" href="add_audit_new_view.php"><i class="fa fa-search"></i> View</a> 
                    <a class="btn btn-success btn-xs" href="add_audit_new.php"><i class="fa fa-pencil"></i> Edit</a>   
                </td>
            </tr>
            <tr class="odd" role="row">
                <td align="left">
                    <b class="text-danger"><i>Total Establishment/Distribution/Mains/NSD/C-SET/NGS ENGINEERING CO/O/H</i></b>                            <div class="holder">
                        <i class="smallfont">Created by : MR. ARABINDRA GHOSH, 
                            19-06-2018 12:10 PM                                
                        </i>
                    </div>
                </td>
                <td align="center">
                    18-06-2018                        
                </td>
                <td align="center">
                    02:00 PM - 03:10 PM
                </td>
                <td align="center">
                    B N Ghosal Rd, Belghoria                        
                </td>
                <td align="center">
                    49.50                        
                </td>
                <td align="center">
                    <span class="text-success">Approve &amp; Published</span>                        
                </td>
                <td width="20%" align="center">			
                    <a class="btn btn-info btn-xs" href="add_audit_new_view.php"><i class="fa fa-search"></i> View</a> 
                    <a class="btn btn-success btn-xs" href="add_audit_new.php"><i class="fa fa-pencil"></i> Edit</a>   
                </td>
            </tr>
            <tr class="even" role="row">

                <td align="left">
                    <b class="text-danger"><i>Total Establishment/Distribution/Mains/NSD/C-SET/NGS ENGINEERING CO/U/G</i></b>                            
                    <div class="holder">
                        <i class="smallfont">Created by : MR. ARABINDRA GHOSH, 
                            19-06-2018 12:13 PM                                
                        </i>
                    </div>
                </td>
                <td align="center">
                    18-06-2018                        
                </td>
                <td align="center">
                    01:00 PM - 01:45 PM
                </td>
                <td align="center">
                    B N GHosal Rd O/T, Belghoria                        
                </td>
                <td align="center">
                    99.74                        
                </td>
                <td align="center">
                    <span class="text-success">Approve &amp; Published</span>                        
                </td>
                <td width="20%" align="center">			
                    <a class="btn btn-info btn-xs" href="add_audit_new_view.php"><i class="fa fa-search"></i> View</a> 
                    <a class="btn btn-success btn-xs" href="add_audit_new.php"><i class="fa fa-pencil"></i> Edit</a>   
                </td>
            </tr>
        </tbody>
    </table>
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
                                        $z('.datetimepicker').datetimepicker({
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
                                $("#datetimepicker1").on("change", function (e) {
                        var selected_date = $('#datetimepicker1').val();
                                if (selected_date != "") {
                        var ajax_data = {
                        "action": "getDataByDate",
                                "selected_date": selected_date,
                                "activity_id": <?php echo $activity_id; ?>,
                                "table_name": "audit_view",
                        };
                                $.ajax({
                                type: 'post',
                                        cache: false,
                                        data: ajax_data,
                                        success: function (getDataByDate) {
                                        if (getDataByDate) {
                                        $("#showrelatedData").html(getDataByDate);
                                        }
                                        return false;
                                        }
                                });
                        }
                        return false;
                        });
                                /*Datepicker*/

                                        /*time of_audit calculation*/
                                                function actualTime(reportingTime, releasingTime) {
                                                if (reportingTime != '' && releasingTime != '') {
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
                                                        if (actualHours < 0) {
                                                alert("Please check your reporting and releasing Times");
                                                        return false;
                                                } else {
                                                var actualHour = parseInt(actualHours / 60);
                                                        var actualMin = parseInt(actualHours % 60);
                                                        actualMin = ("0" + actualMin).slice( - 2);
                                                        var actualTime = actualHour + ":" + actualMin;
                                                        return actualTime;
                                                }
                                                }
                                                }

                                        $(function () {
                                        $(".combodate").change(function () {
                                        var asd = '';
                                                $(".combodate").each(function () {
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
                                        } else {
                                        var rept = reportingTime.split(":");
                                                var reptHr = rept[0];
                                                var reptMin = rept[1];
                                                var rept2 = releasingTime.split(":");
                                                var relsMin2 = rept2[1];
                                                var relsHr = rept2[0];
                                                if (relsMin2 < reptMin && relsMin2 != "00") {
                                        $(".combodateto").val('');
                                                $("#datetimepicker22").val('');
                                                $("#audit_duration").val('');
                                                alert("Realeasing time should be greater than Reporting times..");
                                        }
                                        $("#audit_duration").val("");
                                        }
                                        }
                                        });
                                                $(".combodateto").change(function () {
                                        var asd = '';
                                                $(".combodateto").each(function () {
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
                                        } else {
                                        var rept = reportingTime.split(":");
                                                var reptHr = rept[0];
                                                var reptMin = rept[1];
                                                var rept2 = releasingTime.split(":");
                                                var relsMin2 = rept2[1];
                                                var relsHr = rept2[0];
                                                if ((relsMin2 < reptMin && relsMin2 != "00") || (relsHr < reptHr)) {
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
                                                //    jQuery("#datetimepicker2").change(function(){        
                                                //    });
                                                //    jQuery("#datetimepicker22").change(function(){
                                                //        var reportingTime = jQuery("#datetimepicker2").val();
                                                //        var releasingTime = jQuery("#datetimepicker22").val();
                                                //        if(releasingTime <= reportingTime && releasingTime !="") { 
                                                //            jQuery("#datetimepicker22").val('');
                                                //            jQuery("#audit_duration").val('');
                                                //            alert("Realeasing time should be greater than Reporting time..");
                                                //            return false;
                                                //        }
                                                //        else
                                                //        {
                                                //            var actualTimes = actualTime(reportingTime,releasingTime);
                                                //            jQuery("#audit_duration").val(actualTimes);
                                                //        }
                                                //    });

                                                /*time of_audit calculation*/
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
                                                        $s('#avatar2').attr('src', 'assets/img/audit.png');
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
                                                $(document).ready(function () {
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
                                        "action": "save_violation",
                                                "violation_type": violation_type,
                                                "description": description,
                                                "violation_category": violation_category,
                                                "remarks": remarks,
                                                "nof": no_of_parti,
                                                "token_id": token_id
                                        };
                                                $.ajax({
                                                type: 'post',
                                                        cache: false,
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
                                                /*       
                                                 function renumberRows() {
                                                 $('#TextBoxesGroupviolation tr').each(function(index, el){
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
                                                 $.when($(this).parents("tr .removetr").remove()).then(renumberRows);
                                                 }
                                                 }
                                                 });
                                                 renumberRows();*/
                                                $("#create_post").submit(function () {
                                        var date_of_audit = jQuery.trim(jQuery("#datetimepicker1").val());
                                                var datetimepicker2 = jQuery.trim(jQuery("#datetimepicker2").val());
                                                var datetimepicker22 = jQuery.trim(jQuery("#datetimepicker22").val());
                                                var place = jQuery.trim(jQuery("#place").val());
                                                var job_title = jQuery.trim(jQuery("#job_title").val());
                                                var audit_by = jQuery.trim(jQuery("#audit_by").val());
                                                //var sas_report_no     = jQuery.trim( jQuery("#sas_report_no").val() );
                                                var avg_mark = jQuery.trim(jQuery("#avg_mark").val());
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
                                        if (date_of_audit == undefined || date_of_audit == "")
                                        {
                                        jQuery("#date_of_audit_error").html("<div class=\"error\">Date of audit is required.</div>");
                                                error_counter++;
                                        }
                                        if (datetimepicker2 == undefined || datetimepicker2 == "" || datetimepicker22 == undefined || datetimepicker22 == "")
                                        {
                                        jQuery("#time_of_audit_error").html("<div class=\"error\">Audit time is required.</div>");
                                                error_counter++;
                                        }
                                        if (place == undefined || place == "")
                                        {
                                        jQuery("#place_error").html("<div class=\"error\">Venue is required.</div>");
                                                error_counter++;
                                        }
                                        if (job_title == undefined || job_title == "")
                                        {
                                        jQuery("#job_title_error").html("<div class=\"error\">Job Title is required.</div>");
                                                error_counter++;
                                        }
                                        if (audit_by == undefined || audit_by == "")
                                        {
                                        jQuery("#audit_by_error").html("<div class=\"error\">Audit By is required.</div>");
                                                error_counter++;
                                        }

                                        if (avg_mark == undefined || avg_mark == "")
                                        {
                                        jQuery("#avg_mark_error").html("<div class=\"error\">Average mark is required.</div>");
                                                error_counter++;
                                        }
                                        if (error_counter == 0) {
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

                                                $(function () {
                                                var ajax_data = {
                                                "action": "getVenueList",
                                                        "activity_type_id": "<?php echo $activity_id; ?>"
                                                };
                                                        $.ajax({
                                                        type: 'post', url: '<?php echo page_link("activity/add_audit.php"); ?>', cache: false,
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
                                                });</script>
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




                        });</script>
</body>
</html>
