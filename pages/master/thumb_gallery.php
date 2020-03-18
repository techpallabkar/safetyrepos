<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$presCtr = load_controller("ActivityController");
$presCtr->doAction();
$beanUi             = $presCtr->beanUi;
$page               = $beanUi->get_view_data("page");
$gallerycategory    = $beanUi->get_view_data('gallerycategory');
$getGalleryData     = $beanUi->get_view_data('getGalleryData');
$presCtr->get_header();
?>
<script src="<?php echo url("assets/js/jquery.min.js")?>" type="text/javascript"></script>
<link type="text/css" media="all" href="<?php echo url("assets/css/prettyPhoto.css") ?>" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url("assets/js/jquery.prettyPhoto.js") ?>"></script>
<div class="mh-wrapper mh-home clearfix">
    <div class="mh-widget mh-home-2 mh-widget-col-2 mh_slider_hp clearfix">
<?php ?>
        <div class="topic-heading"><h3><?php echo $gallerycategory[0]->name ?>  </h3></div>
        <div class="gallery">
            <?php
            if (!empty($getGalleryData)) {
                foreach ($getGalleryData as $key => $rowdata) {
                    echo ' <div class="thumb-gallery" style="height:220px;">
                                    <div class="thumb" style="width:196px;height:160px;">
                                            <a href="' . url($rowdata->image_path) . '" rel="prettyPhoto[gallery2]" title="' . $rowdata->caption . '">
                                                    <img width="100%" height="100%" src="' . url($rowdata->image_path) . '" alt="' . $rowdata->caption . '" />
                                            </a>
                                            <p> ' . $rowdata->caption . ' </p>
                                    </div>
                                </div>';
                }
            }
            ?>
        </div>
    </div>
</div>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $("area[rel^='prettyPhoto']").prettyPhoto();

        $(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed: 'normal', theme: 'light_square', slideshow: 3000, autoplay_slideshow: true});
        //$(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});


    });
</script> 
<?php $presCtr->get_footer(); ?>
</body>
</html>
