<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("NodalController");
$controller->doAction();
$beanUi = $controller->beanUi;
$rowID  =  $beanUi->get_view_data('rowID');
$questionNodalRowData  =  $beanUi->get_view_data('questionNodalRowData');
$nodalOfficerDetails  =  $beanUi->get_view_data('nodalOfficerDetails');
$controller->get_header();

?>

<div class="container1" style="min-height: 800px;">
    <h1 class="heading">Update Question Set Nodal Details</h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <form name="addEvent" id="addEvent" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $rowID; ?>" />
     <div class="holder">
            <label for="Question_Set">Question Set</label>
            <label for="Question_Set"><?php echo isset($questionNodalRowData->subheading) ? $questionNodalRowData->subheading : "" ; ?></label>
        </div>
        <br />
     
        <div class="holder">
            <label for="department">Division Department Tree</label>
            <label for="department"><?php echo isset($questionNodalRowData->tree_division_name) ?$questionNodalRowData->tree_division_name : "" ; ?></label>
        </div>
        <br />
        <div class="holder required">
            <label for="Name">Name</label>
            <select name="user_id" id="user_id" style="width:30%;">
                <?php
                echo '<option value="" selected="">---Select---</option>';
                if (!empty($nodalOfficerDetails)) {
                    foreach ($nodalOfficerDetails as $skey => $svalue) {
                        echo "<option value='" . $svalue->id . "' ".(($svalue->id == $questionNodalRowData->user_id)?"selected":"").">" . $svalue->full_name . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <hr />
        <div class="holder">
            <center>
                <?php 
                if( $rowID != "" ) { 
                    echo '<input type="submit" value="Update" class="btn btn-sm btn-primary" />';
                }
                else
                {
                    echo '<input type="submit" value="Add" class="btn btn-sm btn-primary" />';
                }
                ?>
                <a class="btn btn-sm btn-danger" href="index.php" >Cancel</a>
                <input type="hidden" name="_action" value="editNodalMapping" />
            </center>
        </div>
        </form>


</div>

<?php $controller->get_footer(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo url("assets/css/jquery.datetimepicker.css") ?>"/>
<script src="<?php echo url("assets/js/jquery.datetimepicker.full.js") ?>"></script>
<script>
    jQuery.datetimepicker.setLocale('en');
    jQuery('.datepicker').datetimepicker({

        timepicker: false,
        scrollMonth: false,
        scrollInput: false,
        format: 'Y-m-d',
        step: 5
    });
  
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#addEvent").submit(function(){
//            var bulletin_date =$("#bulletin_date").val();
//            var department =$("#department").val();
//            var description =$("#description").val();            
        });
    });
    
</script>

</body>
</html>
