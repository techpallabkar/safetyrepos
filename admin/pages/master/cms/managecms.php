<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("CmsController");
$controller->doAction();
$beanUi = $controller->beanUi;
$cmsData = $beanUi->get_view_data("cmsData");
$singleData = $beanUi->get_view_data("singleData");
$controller->get_header();
?>
<script type="text/javascript" src="<?php echo url("assets/ckeditor/ckeditor.js") ?>"></script>

<div class="container1">
    <h1 class="heading">Manage CMS</h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <?php 
    if(!empty($singleData)) {
    if ($singleData[0]->id != "" || $singleData[0]->id != 0) { ?>
        <form name="add_standards_and_codes" id="add_standards_and_codes" action="" method="post" enctype="multipart/form-data">

            <div class="holder required">
                <label for="title">Title</label>
                <input type="text" name="data[title]" id="title" value="<?php echo $singleData[0]->title; ?>" />
                <div id="title_error"><?php echo $beanUi->get_error('title'); ?></div>
            </div>
            <br />
            <div class="holder">
                <label for="tags" style="float:left;">Content</label>
                <div style="width:70%;float:left;">
                    <textarea name="data[content]" id='post_detail'><?php echo $singleData[0]->content; ?></textarea>
                </div>
                <div id="description_error"><?php echo $beanUi->get_error('content'); ?></div>
            </div>

            <br />
            <div class="holder">
                <center>
                    <input type="submit" value="Update" class="btn btn-sm btn-primary" />
                    <a class="btn btn-sm btn-danger" href="managecms.php" >Cancel</a>
                    <input type="hidden" name="_action" value="updateData" />
                    <input type="hidden" name="data[id]" id="cms_id" value="<?php echo $singleData[0]->id; ?>" />
                </center>
            </div>
        </form>
        <hr />
    <?php } } ?>
    <table class="table table-bordered table-condensed table-responsive">
        <thead>
            <tr class="bg-primary">
                <th>Sl.No</th>
                <th>Title</th>
                <th>Content</th>
                <th>Action</th>
            </tr>
            <?php
            foreach ($cmsData as $key => $rowdata) {
                $var =(strlen($rowdata->content) > 200 ) ? "..." : "";
                echo '<tr>'
                . '<td>' . ($key + 1) . '</td>'
                . '<td>' . $rowdata->title . '</td>'
                . '<td>' . substr(strip_tags($rowdata->content),0,200).$var. '</td>'
                . '<td><a href="managecms.php?editid=' . $rowdata->id . '" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a></td>'
                . '</tr>';
            }
            ?>
        </thead>

    </table>


</div>

<?php $controller->get_footer(); ?>

<script type="text/javascript">
    CKEDITOR.replace('post_detail',
            {
                toolbar: 'Basic',
                uiColor: '#9AB8F3'
            });
</script>
</body>
</html>
