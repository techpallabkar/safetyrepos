<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("GalleryController");
$controller->doAction();
$beanUi = $controller->beanUi;
$gallerycategory = $beanUi->get_view_data('gallerycategory');
$controller->get_header();
?>
<div class="container1" style="min-height: 800px;">
    <h1 class="heading">Manage Category</h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <form name="addCategory" id="addCategory" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $beanUi->get_view_data("id"); ?>" />
        <div class="holder required">
            <label for="name" style="width:100px;">Name</label>
            <input type="text" name="data[name]" id="name" value="<?php echo $beanUi->get_view_data("name"); ?>" />

            <?php 
            if( $beanUi->get_view_data("id")!="" ) {
                echo '<input type="submit" value="Update" class="btn btn-sm btn-primary" />';
            } else {
                echo '<input type="submit" value="Add" class="btn btn-sm btn-primary" />';
            }
            ?>
            <a class="btn btn-sm btn-danger" href="manage_category.php" >Cancel</a>
            <input type="hidden" name="_action" value="submitData" />
            <div id="name_error" style="color:red"><?php echo $beanUi->get_error('name'); ?></div>
        </div>
        <hr />
    </form>
    <table id="postlist" class="table table-striped table-bordered table-condensed table-responsive responsive-utilities jambo_table bulk_action">
        <thead>
            <tr>
                <th width="10%">SL.NO</th>
                <th>NAME</th>
                <th width="20%">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if( !empty( $gallerycategory ) ) {
            foreach ($gallerycategory as $keys => $rowData) { ?> 
                <tr>
                    <td align="center"><?php echo ($keys + 1)."."; ?> </td>
                    <td><?php echo $rowData->name; ?></td>
                    <td align="center">
                        <a class="btn btn-success btn-xs"  href="manage_category.php?id=<?php echo $rowData->id; ?>"><i class="fa fa-pencil"></i> Edit</a>
                        <a class="btn btn-danger btn-xs" onclick="return confirm('Are you sure want to delete this category?');"  href="manage_category.php?delid=<?php echo $rowData->id; ?>"><i class="fa fa-trash"></i> Delete</a>
                    </td>
                </tr>
            <?php } } else { 
                echo '<tr><td></tr>';
            } ?>
        </tbody>
    </table>
</div>
<?php $controller->get_footer(); ?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#addCategory").submit(function(){
            var name =$("#name").val();
           
            var error_data = 0;

			
            if(name == "" || name == "undefined")
            {
                $("#name_error").html("<div>* Name is required</div>");
               
                
                error_data++;
                return false;
            }


           
            
            if(error_data == 0)
            {
                return true;
            }
            
            
        });
    });
    
</script>
</body>
</html>
