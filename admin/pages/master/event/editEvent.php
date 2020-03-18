<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("EventController");
$controller->doAction();
$beanUi = $controller->beanUi;
$eventcategory  =  $beanUi->get_view_data('eventcategory');
$getdata = $beanUi->get_view_data("getdata");
$controller->get_header();
//show($getdata);



?>

<div class="container1" style="min-height: 800px;">
    <h1 class="heading">Edit Event</h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <form name="addEvent" id="addEvent" action="" method="post" enctype="multipart/form-data">
        <div class="holder required">
            <label for="category_id">Select Category</label>
            <select name="data[category_id]" id="category_id">
                <option value="" selected>Select Category</option> 
                <?php foreach( $eventcategory as $rowdata ) { 
                    $selected  = (($rowdata->name == $getdata[0]->name) ? "SELECTED" : "");
                echo '<option value="'.$rowdata->id.'" '.$selected.'>'.$rowdata->name.'</option>';
              } ?>
            </select>
            <div id="category_id_error"><?php echo $beanUi->get_error('category_id'); ?></div>
        </div>
        <br />
        <div class="holder required">
            <label for="subject">Subject</label>
            <input type="text" name="data[subject]" id="subject" value="<?php echo $getdata[0]->subject ?>" />
            <div id="subject_error"><?php echo $beanUi->get_error('subject'); ?></div>
        </div>
        <br />
        <div class="holder required">
            <label for="department">Department</label>
            <input type="text" name="data[department]" id="department" value="<?php echo $getdata[0]->department ?>" />
            <div id="department_error"><?php echo $beanUi->get_error('department'); ?></div>
        </div>
        <br />
        <div class="holder required">
            <label for="location">Location</label>
            <input type="text" name="data[location]" id="location" value="<?php echo $getdata[0]->location ?>" />
            <div id="location_error"><?php echo $beanUi->get_error('location'); ?></div>
        </div>
        <br />
        <div class="holder required">
            <label for="date_of_event">Date of Event</label>
            <input type="text" name="data[date_of_event]" id="date_of_event" class="datepicker" value="<?php echo $getdata[0]->date_of_event ?>" />
            <div id="date_of_event_error"><?php echo $beanUi->get_error('date_of_event'); ?></div>
        </div>
        <br />
        <div class="holder required">
            <label for="time_of_event">Time of Event</label>
            <input type="text" name="data[time_of_event]" id="time_of_event" class="timepicker" style="width:10%;" value="<?php echo $getdata[0]->time_of_event ?>" />
            <div id="time_of_event_error"><?php echo $beanUi->get_error('time_of_event'); ?></div>
        </div>
        <br />
         <div class="holder required">
            <label for="description">Description</label>
            <textarea name="data[description]" id="description" ><?php echo $getdata[0]->description; ?></textarea>
            <div id="description_error"><?php echo $beanUi->get_error('description'); ?></div>
        </div>
        <br />
       
         <div class="holder required">
            <label for="description">Status</label>
            <select name="data[status]" id="status">
                <option value="upcoming" <?php if($getdata[0]->status == "upcoming") { echo "selected"; } ?>>Upcoming</option>
                <option value="completed" <?php if($getdata[0]->status == "completed") { echo "selected"; } ?>>Completed</option>
            </select>
            <div id="description_error" style="color:red"><?php echo $beanUi->get_error('description'); ?></div>
        </div>
       
        <hr />
        <div class="holder">
            <center>
                <input type="submit" value="Update" class="btn btn-sm btn-primary" />
                <a class="btn btn-sm btn-danger" href="index.php" >Cancel</a>
                <input type="hidden" name="_action" value="updateEventData" />
                <input type="hidden" name="data[id]"  id="AddMM_id" value="<?php echo $getdata[0]->id; ?>" />
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
        step: 5,
        maxDate:false
    });
    jQuery('.timepicker').datetimepicker({

        datepicker: false,
        scrollMonth: false,
        scrollInput: false,
        format: 'H:i',
        step: 5
    });
</script>

</body>
</html>
