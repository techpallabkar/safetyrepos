<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$presCtr = load_controller("LibraryController");
$presCtr->doAction();
$beanUi                         = $presCtr->beanUi;
$recent_post                    = $beanUi->get_view_data("recent_post");
$mostviewd                      = $beanUi->get_view_data("mostviewd");
$activities                     = $beanUi->get_view_data("activities");
$articles                       = $beanUi->get_view_data("articles");
$page                           = $beanUi->get_view_data("page");
$paggin_html                    = $beanUi->get_view_data("paggin_html");
$rows 							= $beanUi->get_view_data( "rows" );
$draft_posts                    = $beanUi->get_view_data("draft_posts");
$draft_posts_paggin_html        = $beanUi->get_view_data("draft_posts_paggin_html");
$draft_presentation             = $beanUi->get_view_data("draft_presentation");
$draft_presentation_paggin_html = $beanUi->get_view_data("draft_presentation_paggin_html");
$activity_type_id               = $beanUi->get_view_data("activity_type_id");
$activity_type_master           = $beanUi->get_view_data("activity_type_master");
$activity_name                  = get_value_by_id($activity_type_id, "activity_name", $activity_type_master);
$breadcumb                      = $beanUi->get_view_data("breadcumb");
$categories                     = $beanUi->get_view_data("allLibrary");
$cat_id 			= $beanUi->get_view_data( "cat_id" );

$presCtr->setCss("tree");
$presCtr->get_header();
?>

<div class="mh-wrapper mh-home clearfix">
    <div class="topic-heading"><h3>Safety Library</h3></div>

    <?php echo $beanUi->get_message(); ?>
    <div class="left-content holder2 col-md-4" id="maintree">
        <?php create_tree_view($categories, 0, 0, -1, $cat_id, 1); ?>
    </div>
    <!--content-->
    <div class="right-content holder2 col-md-8">

        <div class="clearfix"></div>
        <div class="cat-breadcum clearfix">
            <?php echo ( $cat_id > 0 ) ? "<div id=\"selected_cat\"></div>" : ""; ?>
            <div class="clearfix"></div>
            <div class="hr"></div>
        </div>
        <?php
        if (count($rows)) { ?>
            <ul>
                <?php
                $class = 'even';
                foreach ($rows as $row) {
                    $class = ($class == 'even') ? 'odd' : 'even';
                    $breadcumb = ( $row->cat_path != "" ) ? str_replace('/', " <span class=\"fa fa-caret-right\"></span> ", $row->cat_path) : '';
                    ?>
                    <li class="buyers-item clearfix">
                        <header>
                            <?php
							$image_path = BASE_PATH . "/" . $row->file_path;
//                            echo $image_path;die;
                            if (is_file($image_path))
                                //echo ' ';
                            echo '<p class="mh-custom-posts-small-title">' .'<a title="View File" href="'.url($row->file_path).'" target="_blank" >
                                                <img src="' . url("assets/images/downloadIcon.png") . '" width="25" /> &nbsp;
                                       '. stripslashes($row->title) . ' </a></p>';
                            
                            $created_by_name = $row->created_by_name;
                            $created_date = date('F j, Y', strtotime($row->created_date));
                            echo '<div class="mh-meta">' . "\n";
                            echo "<i class='fa fa-clock-o'></i>" . $created_date;
                            echo '</div>' . "\n";
                            ?>
                        </header>
                        
                            <?php
                           /* $image_path = BASE_PATH . "/" . $row->file_path;
//                            echo $image_path;die;
                            if (is_file($image_path))
                                echo ' <a title="View File" href="'.url($row->file_path).'" target="_blank" >
                                                <img src="' . url("assets/images/downloadIcon.png") . '" />
                                        </a>';*/
                            ?>
                       
                    </li>
                    <?php
                }
                ?>
            </ul>


            <div class="pagging"><?php echo $paggin_html; ?></div>
            <?php
        } else {
            echo '<p class="red">Content not found.</p>';
        }
        ?>
    </div>
</div>

<input type="hidden" id="category_id" value="<?php echo $cat_id; ?>" />

    <?php $presCtr->get_footer(); ?>


    <script>
        jQuery(document).ready(function ($) {
           
            
            var cat_id = jQuery("#category_id").val();
            var cat_paths = Array();
<?php foreach ($categories as $key => $catrow) { ?>
                cat_paths[<?php echo $catrow->id; ?>] = '<?php echo str_replace('/', ' <span class="fa fa-caret-right"></span> ', $catrow->path); ?>';
<?php } ?>
            if (cat_id > 0)
                select_cat_path(cat_id);

            jQuery("#maintree").find("input[type=checkbox]").attr("checked", "checked");
            jQuery("#maintree li label").click(function () {
                var cat_input_name = $(this).attr("for");
                var cat_id = jQuery("input[name=" + cat_input_name + "]").attr("id");
                if (cat_id > 0) {
                    location.href = "<?php echo url('safetyLibrary.php?cat_id=') ?>" + cat_id;
                }
            });

            function select_cat_path(cat_id) {

                if (cat_id > 0 && cat_paths[cat_id] != undefined) {
                    jQuery("#selected_cat").empty();
                    jQuery("#selected_cat").html('<div class="breadcum">' + cat_paths[cat_id] + '</div>');
                }
            }

            //$(".buyers-item:odd").addClass("oddli");
            //$(".buyers-item:even").addClass("evenli");
        });
        
        
        $(document).ready(function () {
         $(".paginate_button").hide();
         $(".paginate_button:eq(2)").show();
         $(".paginate_button.current").show();
         $(".paginate_button.previous").show();
         $(".paginate_button").nextAll(".paginate_button:first").show();
         $(".paginate_button:nth-last-child(3)").show();
         $(".paginate_button:nth-last-child(2)").show();
         $(".paginate_button.current").nextAll(".paginate_button:lt(3)").show();
         $(".paginate_button.current").prevAll(".paginate_button:lt(1)").show();
         $(".paginate_button.current").nextAll(".paginate_button:gt(-2)").show(); 
        });

    </script>
</body>
</html>
