<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$presCtr = load_controller("ActivityController");
$presCtr->doAction();
$beanUi = $presCtr->beanUi;
$page = $beanUi->get_view_data("page");
$gallerycategory = $beanUi->get_view_data('gallerycategory'); //show($gallerycategory);
$presCtr->get_header();
?>
<div class="mh-wrapper mh-home clearfix">
    <div id="main-content" class="mh-content mh-home-content clearfix">
        <div class="mh-widget mh-home-2 mh-widget-col-2 mh_slider_hp clearfix">
            <div class="topic-heading"><h3>Gallery</h3></div>
            <?php
            foreach ($gallerycategory as $key => $rowdata) {
                echo '<ul class="gallery_list">'
                . '<li><a href="thumb_gallery.php?cat_id=' . $rowdata->id . '">' . $rowdata->name . '</a></li>'
                . '</ul>';
            }
            ?>
        </div>
    </div>
    <div class="mh-widget-col-1 mh-sidebar">
     
        <div class="mh-widget">
            <?php include 'upcomingevent.php'; ?>    
        </div>
           <div id="search-3" class="mh-widget mh-home-6 widget_search">
            <h4 class="mh-widget-title">Search</h4>
            <form role="search" method="post" class="search-form" action="#">
                <label> 
                    <span class="screen-reader-text">Search for:</span>
                    <input type="search" class="search-field" placeholder="Search &hellip;" value="" name="s" title="Search for:" />
                </label>
                <button type="submit" class="search-submit" value="" /><span class="fa fa-search"></span></button>
            </form>
        </div>
    </div>
    </div>
    <?php $presCtr->get_footer(); ?>
</body>
</html>
