<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("ActivityController");
$controller->doAction();
$beanUi = $controller->beanUi;
$page = $beanUi->get_view_data("page");
$allactivities = $beanUi->get_view_data("allactivities");
$posts_paggin_html = $beanUi->get_view_data("posts_paggin_html");
$post_status = $beanUi->get_view_data("post_status");
$search_title = $beanUi->get_view_data("search_title");
$status_id = $beanUi->get_view_data("status_id");
$post_activity_type_master = $beanUi->get_view_data("post_activity_type_master");
$activity_type_id = $beanUi->get_view_data("activity_type_id");
$division_department = $beanUi->get_view_data("division_department");
$devition_names = $beanUi->get_view_data("devition_names");
$activity_participants = $beanUi->get_view_data("activity_participants");
$participants_list = $beanUi->get_view_data("participants_list");
$activity = $beanUi->get_view_data("activity");
$type_images = array("image/jpg", "image/jpeg", "image/png");
$type_videos = array("video/mp4", "video/quicktime");
$pdf = array("application/pdf");
$activities = get_value_by_id($activity_type_id, "activity_name", $post_activity_type_master);
$controller->get_header();
$site_root_url = dirname(url());
//show($activity);
$activity_type_master = "";
?>
<link type="text/css" media="all" href="<?php echo url("assets/css/jquery-scrollify-style.css"); ?>" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url("assets/js/jquery_0034.js"); ?>"></script>
<script>
    var $k = jQuery.noConflict();
    $k(document).ready(function () {
        $k('.scroller').scrollify();

        $k("#postlist #ids").click(function () {
            $k(this).parents().toggleClass("selected");
        });

    });
</script> 

<div class="container1">
<?php echo $beanUi->get_message(); ?>
    <h1 class="heading"><?php echo $activities; ?>
        <script>
            function backnow()
            {

                if (history.length > 1)
                {
                    window.location.href = "index.php?status_id=<?php echo $_REQUEST["status_id"]; ?>&districtid=<?php echo $_REQUEST["districtid"]; ?>&search_title=<?php echo $_REQUEST["search_title"]; ?>&activity_month=<?php echo $_REQUEST["activity_month"]; ?>&activity_year=<?php echo $_REQUEST["activity_year"]; ?>&activity_no=<?php echo $_REQUEST["activity_no"]; ?>&page=<?php echo $_REQUEST["page"]; ?>&activity_id=<?php echo $activity[0]->activity_type_id; ?>";
                } else
                {
                    window.close();
                }
            }
        </script>
        <a onclick="return backnow()" class="btn btn-danger btn-sm">Back</a> 
    </h1> 
    <div class="message"></div>
    <div id="main-content">
        <?php $activity_name = get_value_by_id($activity[0]->activity_type_id, "activity_name", $activity_type_master); ?>
        <div class="grey-bg"><?php echo $activity_name; ?></div> <hr>
        <table class="table table-bordered">
            <tr>
                <td width="30%"><b>Participating Department :</b></td>
                <td> 
                    <?php
                    if (!empty($devition_names)) {
                        $j = 0;
                        $valxx = array();
                        foreach ($devition_names as $key => $ddmrow) {
                            $j = $j + 1;
                            echo '<b>' . $ddmrow . '&nbsp;&nbsp;</b>';
                        }
                    }
                    ?>	


                </td>
            </tr>
            <tr>
                <td width="30%"><b>Activity No. :</b></td>
                <td> <?php echo ($activity[0]->activity_no != "") ? $activity[0]->activity_no : ""; ?></td>
            </tr>
            <tr>
                <td width="30%"><b>Activity Month :</b></td>
                <td> <?php echo ($activity[0]->activity_month != "") ? date('F', mktime(0, 0, 0, $activity[0]->activity_month, 10)) : ""; ?></td>
            </tr>
            <tr>
                <td width="30%"><b>Activity Year :</b></td>
                <td> <?php echo ($activity[0]->activity_year != "") ? $activity[0]->activity_year : ""; ?></td>
            </tr>
            <tr>
                <td width="30%"><b>Number of Activities :</b></td>
                <td> <?php echo ($activity[0]->activity_count != "") ? $activity[0]->activity_count : ""; ?></td>
            </tr>
            <tr>
                <td width="30%"><b>Venue :</b></td>
                <td> <?php echo ($activity[0]->place != "") ? $activity[0]->place : ""; ?></td>
            </tr>
        </table>

        <hr />
    </div>
<?php $controller->get_footer(); ?>
    <script>
        var $m = jQuery.noConflict();
        $m(function () {
            var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
            $m('a[data-modal-id]').click(function (e) {
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
        });
    </script>
</body>
</html>
