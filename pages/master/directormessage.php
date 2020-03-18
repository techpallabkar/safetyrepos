<?php
if (file_exists('../lib/var.inc.php'))
    require_once( '../lib/var.inc.php' );
$indexCtr = load_controller("IndexController");
$indexCtr->doAction();
$beanUi = $indexCtr->beanUi;
$headline = $beanUi->get_view_data("headline");
$headline_cat = $beanUi->get_view_data("headline_cat");
$directormessage = $beanUi->get_view_data("directormessage");
$indexCtr->get_header();
?>

<div class="mh-wrapper mh-home clearfix">
            <?php foreach ($directormessage as $key => $dirval) { ?>
                <div class="message-area">
                    <div class="content_type"><h4 class="mh-widget-title"><?php //echo strtoupper($dirval->contect_type); ?> Message</h4></div>
                    <div class="mh-ad-spot">
                        <img class="thumb-image" width="250" alt="" src="<?php echo url($dirval->image_path) ?>" alt="<?php echo $dirval->contect_type ?>'s Message" />
                        <p class="descr" hidden=""><?php echo $dirval->description ?></p>
   <?php                   
 echo '<p>' .stripslashes($dirval->description).'</p>';
 $filePath = BASE_PATH.'/'.$dirval->file_path;
if( $dirval->file_path != "" && file_exists( $filePath ) ) { 
   echo '<p style="vertical-align: bottom;"><a target="_blank" href="'.url($dirval->file_path).'" class="read-more direct-read-more" style="color:#A24FC9 !important;">'
    . '<span class="fa fa-file"></span> View Uploaded File</a></p>';
   } 
   echo '</div></div>';
            }
   ?>
        </div>
<?php $indexCtr->get_footer(); ?>