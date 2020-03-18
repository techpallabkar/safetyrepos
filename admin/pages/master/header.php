<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <title>CESC Safety Monitoring System</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo url('assets/css/bootstrap.css') ?>" />

        <link rel="stylesheet" href="<?php echo url('assets/css/font-awesome.min.css') ?>" />
        <link rel="stylesheet" href="<?php echo url('assets/css/style.css') ?>" />
        <link rel="stylesheet" href="<?php echo url('assets/css/custom.css') ?>" />
        <?php echo $this->loadCss(); ?>
        <script type="text/javascript" src="<?php echo url('assets/js/jquery-1.11.3.js') ?>"></script>
        <script type="text/javascript" src="<?php echo url('assets/js/jquery_003.js') ?>"></script>
        <script type="text/javascript" src="<?php echo url('assets/js/bootstrap.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo url('assets/js/custom.js') ?>"></script>
        
        <script>
            $(document).ready(function () {
                $("#postlist #ids").click(function () {
                    $(this).parents().toggleClass("selected");
                });
            });
        </script>
    </head>
    <?php
    $activites = $this->dao->get_activities();
    $allStatisticMasterData = $this->dao->getStatisticMasterData();
    ?>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title">
                            <div class="logo">
                                <a href="<?php echo page_link('dashboard.php'); ?>"><img class="mh-header-image" src="<?php echo url("assets/images/logo2.png"); ?>" alt="CESC Safety Monitoring System logo" />
                                    <div class="powerbank"><center>CESC Safety Monitoring System</center></div>
                                </a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="profile clearfix">
                            <div class="profile_info">
                                <span>Welcome,</span>
                                <div class="admin_name"><?php echo $this->get_auth_user("full_name"); ?></div>
                            </div>
                            <div class="profile_pic text-center">
                                <div style="width: 100%; margin-bottom: 5px;" class="text-center">
                                <img src="<?php echo url("assets/images/nophoto.jpg"); ?>" alt="" class="img-circle profile_img">
                                </div>
                                <div style="clear: both;"></div>
                                <a class="LogOut" href="<?php echo page_link('users/login.php?action=logout'); ?>"><i class="fa fa-sign-out red"></i> Log Out</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="menu_section">
                                <ul class="nav side-menu">
                                    <?php
                                    if($this->get_auth_user("is_nodal_officer") != 1 ){ ?>
                                    <li><a href="<?php echo page_link('dashboard.php'); ?>"><i class="fa fa-dashboard"></i> Monitoring Dashboard</a></li>
<!--                                      <li><a href="<?php echo page_link('wrongentry.php'); ?>"><i class="fa fa-eye" aria-hidden="true"></i>
                                            Exception</a>
                                    </li>-->
                                    <li><a><i class="fa fa-bar-chart"></i> Manage Activities <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <?php
                                            if (!empty($activites)) {
                                                foreach ($activites as $act) {
                                                    if ($act->id != 9 && $act->id != 10) {
                                                        if ($act->id == 5) {
                                                            $pagelink = "audit.php";
                                                        } else if ($act->id == 6) {
                                                            $pagelink = "incident.php";
                                                        } else {
                                                            $pagelink = "index.php";
                                                        }
                                                        if ($act->activity_name != "") {
                                                            ?>
                                                            <li>
                                                                <a href="<?php echo page_link('activity/index.php?activity_id=' . $act->id . ''); ?>"><?php echo strtoupper($act->activity_name); ?></a>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </ul>
                                        <?php    if ($this->get_auth_user("role_id") == 3 || $this->get_auth_user("role_id") == 1) { ?>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo page_link('activitynew/deviation_view_list.php'); ?>">DEVIATION VIEW</a></li>
                                        </ul>
                                        <?php } ?>
                                    </li>
                                    
                                    <?php
                                    if ($this->get_auth_user("role_id") == 3) {
                                        $hide = 'display:block;';
                                    } else {
                                        $hide = 'display:none;';
                                    }
                                    ?>
                                    <li style="<?php echo $hide; ?>"><a><i class="fa fa-users"></i> Manage Users <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo page_link('users/add.php'); ?>">ADD NEW USERS</a></li>
                                            <li><a href="<?php echo page_link('users/'); ?>">MANAGE USERS</a></li>
                                        </ul>
                                    </li>
                                    <li style="<?php echo $hide; ?>"><a><i class="fa fa-archive" aria-hidden="true"></i>
                                            DB Backup <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo page_link('users/dbbackup.php'); ?>">DATABASE</a></li>
                                        </ul>
                                    </li>
                                  
                                    <li><a><i class="fa fa-cogs"></i> Settings <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo page_link('users/change_password.php'); ?>">CHANGE PASSWORD</a></li>
                                        </ul>
                                    </li>
                                    <li><a><i class="fa fa-bar-chart"></i> Report <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo page_link('activity/view_report.php?st=A'); ?>">MONTHLY REPORT(Approved)</a></li>
                                            <li><a href="<?php echo page_link('activity/view_report.php?st=NA'); ?>">MONTHLY REPORT(Not Approved)</a></li>
                                            <li><a href="<?php echo page_link('activity/reportEmployeeWise.php'); ?>">EMPLOYEE WISE REPORT </a></li>
                                            <li><a href="<?php echo page_link('activity/actualActivityTarget.php?st=current'); ?>"><?php echo "ACTUAL ACTIVITY"; ?></a></li>
                                            <!--<li><a href="<?php echo page_link('activity/yearlyTarget.php'); ?>">  <?php echo "YEARLY TARGET"; ?></a></li>-->
                                            <!--<li><a href="<?php //echo page_link('activity/scoreTarget.php'); ?>">  <?php //echo "SITE AUDIT SCORE TARGET"; ?></a></li>-->
                                            <li><a id="mistreelink" href="<?php echo page_link('activity/misTree2.php'); ?>">  <?php echo "CESC PORTAL - MIS TREE"; ?></a></li>
                                            <!--<li><a id="mistreelink" href="<?php //echo page_link('activity/incident_report.php'); ?>">  <?php //echo "ACTIVITY (ALL DATA)"; ?></a></li>-->
                                        </ul>
                                    </li>
                                    <li><a><i class="fa fa-bar-chart"></i> MIS Report <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <!--<li><a href="<?php //echo page_link('mis/mdReportN.php'); ?>">1.MD's Report ( Add )</a></li>-->
                                            <li><a href="<?php echo page_link('mis/mdReportlistN.php'); ?>">1.MD's Report</a></li>
                                            <!--<li><a href="<?php //echo page_link('mis/mdReportNew.php'); ?>">1.MD's Report ( Add New )</a></li>-->
                                            <!--<li><a href="<?php echo page_link('mis/mdReportlistNew.php'); ?>">1.MD's Report ( View All )</a></li>-->
                                            <!--<li><a href="<?php //echo page_link('mis/mdReport.php'); ?>">1.MD's Report ( Add New )</a></li>-->
                                            <!--<li><a href="<?php echo page_link('mis/mdReportlist.php'); ?>">1.MD's Report ( View All Old Designe )</a></li>-->
                                            <!--<li><a href="<?php //echo page_link('mis/mcmReportNew.php'); ?>">2.MCM Report ( Add ) </a></li>-->
                                            <li><a href="<?php echo page_link('mis/mcmReportlistNew.php'); ?>">2.MCM Report</a></li>
                                            <!--<li><a href="<?php //echo page_link('mis/mcmReport.php'); ?>">2.MCM Report ( Add New ) </a></li>-->
                                            <!--<li><a href="<?php echo page_link('mis/mcmReportlist.php'); ?>">2.MCM Report( View All Old Designe )</a></li>-->
                                            <li><a href="<?php echo page_link('mis/mainsScoreCardType1Report.php'); ?>">3.Mains Score Card Type-1</a></li>
                                            <li><a href="<?php echo page_link('mis/mainsScoreCardType2Report.php'); ?>">4.Mains Score Card(vis a vis Target) Type-2</a></li>
                                            <li><a href="<?php echo page_link('mis/otherDeptScoreCardType1Report.php'); ?>">5.Other Dept Score Card Type-1</a></li>
                                            <li><a href="<?php echo page_link('mis/otherDeptScoreCardType2Report.php'); ?>">6.Other Dept Score Card(vis-a-vis Target) Type-2</a></li>
                                            <li><a href="<?php echo page_link('mis/generation_score_card.php'); ?>">7.Generation Score Card</a></li>
                                            <li><a href="<?php echo page_link('mis/score_card_details_for_mainstream_contractor.php'); ?>">8.C-Sets(Mains) Scores-sorted with Top/Bottom Colour Coding </a></li> 
                                            <li><a href="<?php echo page_link('mis/suraksahBartaScoresStat.php'); ?>">9.Gear & Site Audit Report </a></li>
                                            <li><a href="<?php echo page_link('mis/psetScoresAllDistributionWithTobBottomColourCoding.php'); ?>">10.P-Sets Scores(All Distribution) </a></li>
                                            <li><a href="<?php echo page_link('mis/surakshaBartaIncIdentStatics.php'); ?>">11.Total Incident Statistics </a></li>
                                            <li><a href="<?php echo page_link('mis/bbgs_contractors_scores_sorted.php'); ?>">12.BBGS Contractors Scores -Sorted</a></li>
                                            <li><a href="<?php echo page_link('mis/spotLightIncidentStatistics.php'); ?>">13.Total Incident Statistics (Spot Light)</a></li>
                                            <li><a href="<?php echo page_link('mis/sgs_contractors_scores_sorted.php'); ?>">14.SGS Contractors Scores -Sorted</a></li>
                                            <li><a href="<?php echo page_link('mis/spotLightScoresStatistics.php'); ?>">15.Spot Light Scores Statistics</a></li>
                                            <li><a href="<?php echo page_link('mis/dist_data_sheet_for_momthly_report.php'); ?>">16.Distribution Data Sheet For Monthly Report</a></li>
                                            <li><a href="<?php echo page_link('mis/gen_data_sheet_for_momthly_report.php'); ?>">17.Generation Data Sheet For Monthly Report</a></li>
                                            <li><a href="<?php echo page_link('mis/incident10DistAndOtherEstb3YearsReport.php'); ?>">18.Incident 10 District & Other Establishment (3 Years)</a></li>
                                            <li><a href="<?php echo page_link('mis/ytmactivityfigure.php'); ?>">19.YTM activity Figure vis-a-vis YTM Target</a></li>
                                            <li><a href="<?php echo page_link('mis/statisticsforMRMDistribution.php'); ?>">20. Statistics  for MRM ( Distribution ) List ( Add New )</a></li>
                                            <li><a href="<?php echo page_link('mis/mrmDistributionlist.php'); ?>">20. Statistics  for MRM ( Distribution ) List ( View All )</a></li>
                                            <li><a href="<?php echo page_link('mis/statistics_for_mrm_graph.php'); ?>">20. [Graph] Statics for MRM</a></li>
                                            <li><a href="<?php echo page_link('mis/statisticsforMRMGeneration.php'); ?>">21.Statistics for MRM (Generation)</a></li>
                                            <li><a href="<?php echo page_link('mis/pset_deptwise_setwise_audit_score_gen.php'); ?>">22.P-Set Department-Wise Set-Wise Audit Score(Dist) </a></li>
                                            <li><a href="<?php echo page_link('mis/cset_deptwise_setwise_auditscore.php'); ?>">23.C-Set Department-Wise Set-Wise Audit Score(Dist) </a></li>
                                            <li><a href="<?php echo page_link('mis/cset_deptwise_setwise_auditscore_MNTC.php'); ?>">23(A).C-Set Department-Wise Set-Wise Audit Score(Dist) For MNTC </a></li>
                                            <li><a href="<?php echo page_link('mis/psetCsetWorkmanSupervisor.php'); ?>">24.Training Details</a></li>
                                            <li><a href="<?php echo page_link('activity/view_report_DraftFinalApproved.php'); ?>">25.Monthly Report (Draft,Final Submit And Approved)</a></li>
                                            <li><a href="<?php echo page_link('mis/surakshabarta_siteaudit.php'); ?>">26. Suraksha Barta -Site Audit </a></li>
                                            <li><a href="<?php echo page_link('mis/surakshabarta_incident.php'); ?>">27. Suraksha Barta - Incident</a></li>
                                            <li><a href="<?php echo page_link('mis/district_wise_month_wise_accident_statistics.php'); ?>">28. Unit Wise Month Wise Incident History</a></li>
                                            <li><a href="<?php echo page_link('mis/accident_statistics_gendist.php'); ?>">29. Accident Statistics (G+D)</a></li>
                                            <li><a href="<?php echo page_link('mis/district_wise_month_wise_activity_statistics.php'); ?>">30. Unit Wise Month Wise Activity History</a></li>
                                            <li><a href="<?php echo page_link('mis/audit_report_dist_pset.php'); ?>">31.Audit Report (Dist / P-set) </a></li>
                                        </ul>
                                    </li>
<!--                                    <li <?php
//                                    if ($this->get_auth_user("role_id") == 3 || $this->get_auth_user("role_id") == 1) {
//                                        echo 'style="display:block;"';
//                                    }
                                    ?>>
                                        <a><i class="fa fa-bar-chart"></i> Target Entry <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo page_link('activity/targetEntry.php'); ?>">Add NEW</a></li>
                                            <li><a href="<?php echo page_link('activity/listtargetEntry.php'); ?>">VIEW ALL</a></li>
                                            <li><a href="<?php echo page_link('activity/SAGen_TargetEntry.php'); ?>">Site Audit Generation Target Entry</a></li>
                                            <li><a href="<?php echo page_link('activity/SAGen_TargetView.php'); ?>">Site Audit Generation Target View</a></li>
                                            <li><a href="<?php echo page_link('activity/SADist_TargetEntry.php'); ?>">Site Audit Distribution Target Entry</a></li>
                                            <li><a href="<?php echo page_link('activity/SADist_TargetView.php'); ?>">Site Audit Distribution Target View</a></li>
                                            <li><a href="<?php echo page_link('activity/PPEA_TargetEntry.php'); ?>">PPE Audit Target Entry</a></li>
                                            <li><a href="<?php echo page_link('activity/PPEA_TargetView.php'); ?>">PPE Audit Target View</a></li>
                                            <li><a href="<?php echo page_link('activity/HH_TargetEntry.php'); ?>">Hand Holding Target Entry</a></li>
                                            <li><a href="<?php echo page_link('activity/HH_TargetView.php'); ?>">Hand Holding Target View</a></li>
                                            <li><a href="<?php echo page_link('activity/TA_SO_SD_TargetEntry.php'); ?>">TA, SO and SD Target Entry</a></li>
                                            <li><a href="<?php echo page_link('activity/TA_SO_SD_TargetView.php'); ?>">TA, SO and SD Target View</a></li>
                                            <li><a href="<?php echo page_link('activity/Final_TargetEntry.php'); ?>">Annual Target Entry</a></li>
                                            <li><a href="<?php echo page_link('activity/Final_TargetView.php'); ?>">Annual Target View</a></li>
                                        </ul>
                                    </li>-->
                                    <li <?php
                                    if ($this->get_auth_user("role_id") == 3 || $this->get_auth_user("role_id") == 1) {
                                        echo 'style="display:block;"';
                                    }
                                    ?>><a><i class="fa fa-bar-chart"></i> Safety Cell Internal <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo page_link('activity/safetyCellInternalEntry.php'); ?>">Add NEW</a></li>
                                            <li><a href="<?php echo page_link('activity/listsafetyCellInternal.php'); ?>">VIEW ALL</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="<?php echo page_link('cmsDashboard.php'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
<!--                                <li><a><i class="fa fa-cog"></i> Manage Library <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php //echo page_link('library/index.php'); ?>">Add Category</a></li>
                                            <li><a href="<?php //echo page_link('library/add.php'); ?>">Add Library File</a></li>
                                        </ul>
                                    </li>-->
                                    <li><a href="<?php echo page_link('calendar/index.php'); ?>"><i class="fa fa-cog"></i> Manage Calendar</a></li>
<!--                                    <li><a><i class="fa fa-bar-chart"></i> Statistical Data <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <?php
//                                            foreach ($allStatisticMasterData as $key => $value) {
//                                                echo '<li><a href="' . page_link('statistic/index.php?type=' . $value->id) . '">' . $value->name . '</a></li>';
//                                            }
                                            ?>
                                        </ul>
                                    </li>-->
                                    <li><a href="<?php echo page_link('cms/importantinformation.php'); ?>" ><i class="fa fa-cog"></i> Important Information </a></li>
                                    <!--<li><a href="<?php //echo page_link('cms/managecms.php'); ?>" ><i class="fa fa-cog"></i> Manage CMS </a></li>-->
                                    <li><a href="<?php echo page_link('cms/manageMessages.php'); ?>" ><i class="fa fa-cog"></i> Manage Messages </a></li>
                                    <li><a><i class="fa fa-cog"></i> Upcoming Events <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo page_link('event/add.php'); ?>">Add Event</a></li>
                                            <li><a href="<?php echo page_link('event/index.php'); ?>">View All Event</a></li>
                                        </ul>
                                    </li>
                                    <li><a><i class="fa fa-cog"></i> Manage Gallery <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo page_link('gallery/manage_category.php'); ?>">Gallery Category</a></li>
                                            <li><a href="<?php echo page_link('gallery/add.php'); ?>">Add Gallery Image</a></li>
                                            <li><a href="<?php echo page_link('gallery/index.php'); ?>">List Gallery Image</a></li>
                                        </ul>                                    
                                    </li>     
                                    <li><a><i class="fa fa-cog"></i> Bulletin Board <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo page_link('bulletin/add.php'); ?>">Add New</a></li>
                                            <li><a href="<?php echo page_link('bulletin/index.php'); ?>">List Bulletin</a></li>
                                        </ul>                                    
                                    </li>
                                    <?php if($this->get_auth_user("role_id") == 3 || $this->get_auth_user("role_id") == 1){ ?>
                                    <li><a><i class="fa fa-cog"></i> Manage Nodal Officer <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo page_link('nodal/index.php'); ?>">List Nodal</a></li>
                                        </ul>                                    
                                    </li>
                                    <?php } ?>
                                    <?php }else{ ?>
                                    <li>
                                        <a href="<?php echo page_link('activitynew/deviation_view_list_no.php'); ?>">DEVIATION VIEW (NO)</a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <!-- /sidebar menu -->
                    </div>
                </div>
                <!--------am--------->
                <div class="toggle toggle-left-hide">
                                <a id="menu_toggle"><i class="fa fa-angle-double-left"></i></a>
                            </div>
<!--                <div class="top_nav">
                    <div class="nav_menu">
                        <nav class="" role="navigation">
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>-->
<!--                            <ul class="nav navbar-nav navbar-right">
                                <li class="">
                                    <a class="user-profile dropdown-toggle" aria-expanded="true" data-toggle="dropdown" href="javascript:;">
                                        <img alt="" src="<?php //echo url("assets/images/nophoto.jpg"); ?>" alt="">
                                        <?php //echo $this->get_auth_user("full_name"); ?>
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        <li><a href="#"> Profile</a></li>
                                        <li><a href="<?php //echo page_link('users/login.php?action=logout'); ?>"><i class="fa fa-sign-out pull-right red"></i> Log Out</a></li>
                                    </ul>
                                </li>
                            </ul>-->
<!--                        </nav>
                    </div>
                </div>-->
                <div class="right_col" role="main">
                <div class="clearfix"></div>
 
