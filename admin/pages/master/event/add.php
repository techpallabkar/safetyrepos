<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("EventController");
$controller->doAction();
$beanUi = $controller->beanUi;
$eventcategory  =  $beanUi->get_view_data('eventcategory');
$controller->get_header();
?>

<div class="container1" style="min-height: 800px;">
    <h1 class="heading">Add Event</h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
    <form name="addEvent" id="addEvent" action="" method="post" enctype="multipart/form-data">
        <div class="holder required">
            <label for="category_id">Select Category</label>
            <select name="data[category_id]" id="category_id">
                <option value="" selected>Select Category</option>
                <?php foreach( $eventcategory as $rowdata ) { 
                echo '<option value="'.$rowdata->id.'">'.$rowdata->name.'</option>';
                } ?>
            </select>
            <div id="category_id_error" style="color:red"><?php echo $beanUi->get_error('category_id'); ?></div>
        </div>
        <br />
        <div class="holder required">
            <label for="subject">Subject</label>
            <input type="text" name="data[subject]" id="subject" value="<?php echo $beanUi->get_view_data( "subject" ); ?>" />
            <div id="subject_error" style="color:red"><?php echo $beanUi->get_error('subject'); ?></div>
        </div>
        <br />
        <div class="holder required">
            <label for="department">Department</label>
            <input type="text" name="data[department]" id="department" value="<?php echo $beanUi->get_view_data( "department" ); ?>" />
            <div id="department_error" style="color:red"><?php echo $beanUi->get_error('department'); ?></div>
        </div>
        <br />
        <div class="holder required">
            <label for="location">Location</label>
            <input type="text" name="data[location]" id="location" value="<?php echo $beanUi->get_view_data( "location" ); ?>" />
            <div id="location_error" style="color:red"><?php echo $beanUi->get_error('location'); ?></div>
        </div>
        <br />
        <div class="holder required">
            <label for="date_of_event">Date of Event</label>
            <input type="text" name="data[date_of_event]" id="date_of_event" class="datepicker" value="<?php echo $beanUi->get_view_data( "date_of_event" ); ?>" />
            <div id="date_of_event_error" style="color:red"><?php echo $beanUi->get_error('date_of_event'); ?></div>
        </div>
        <br />
        <div class="holder required">
            <label for="time_of_event">Time of Event</label>
            <input type="text" name="data[time_of_event]" id="time_of_event" class="timepicker" style="width:10%;" value="<?php echo $beanUi->get_view_data( "time_of_event" ); ?>" />
            <div id="time_of_event_error" style="color:red"><?php echo $beanUi->get_error('time_of_event'); ?></div>
        </div>
      <br />
         <div class="holder required">
            <label for="description">Description</label>
            <textarea name="data[description]" id="description" ><?php echo $beanUi->get_view_data( "description" ); ?></textarea>
            <div id="description_error" style="color:red"><?php echo $beanUi->get_error('description'); ?></div>
        </div>
        <br>
         <div class="holder required">
            <label for="description">Status</label>
            <select name="data[status]" id="status">
                <option value="upcoming">Upcoming</option>
                <option value="completed">Completed</option>
            </select>
            <div id="description_error" style="color:red"><?php echo $beanUi->get_error('description'); ?></div>
        </div>
        <hr />
        <div class="holder">
            <center>
                <input type="submit" value="Add" class="btn btn-sm btn-primary" />
                <a class="btn btn-sm btn-danger" href="index.php" >Cancel</a>
                <input type="hidden" name="_action" value="addEventData" />
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
<script type="text/javascript">
    $(document).ready(function(){
        $("#addEvent").submit(function(){
            var category_id =$("#category_id").val();
            var subject =$("#subject").val();
            var department =$("#department").val();
            var location =$("#location").val();
            var date_of_event =$("#date_of_event").val();
            var time_of_event =$("#time_of_event").val();
            var description =$("#description").val();
            var error_data = 0;

			
            if(category_id == "" || category_id == "undefined")
            {
                $("#category_id_error").html("<div>* Category is required</div>");
                $("#subject_error").html("<div>* Subject is required</div>");
                $("#department_error").html("<div>* Department is required</div>");
                $("#location_error").html("<div>* Location is required</div>");
                $("#date_of_event_error").html("<div>* Date of Event is required</div>");
                $("#time_of_event_error").html("<div>* Time of Event is required</div>");
                $("#description_error").html("<div>* Description is required</div>");
                
                error_data++;
                return false;
            }
else if(subject == "" || subject == "undefined")
            {
               $("#category_id_error").hide("<div>* Category is required</div>");
                $("#subject_error").html("<div>* Subject is required</div>");
                error_data++;
                return false;
            }
            

 else if(department == "" || department == "undefined")
            {
                  $("#subject_error").hide("<div>* Subject is required</div>");
                 $("#department_error").html("<div>* Department is required</div>");
                error_data++;
                return false;
            }
            else if(location == "" || location == "undefined")
            {
                $("#department_error").hide("<div>* Department is required</div>");
                 $("#location_error").html("<div>* Location is required</div>");
                error_data++;
                return false;
            }
            else if(date_of_event == "" || date_of_event == "undefined")
            {
                 $("#location_error").hide("<div>* Location is required</div>");
                 $("#date_of_event_error").html("<div>* Date of Event is required</div>");
                error_data++;
                return false;
            }
            else if(time_of_event == "" || time_of_event == "undefined")
            {
                 $("#date_of_event_error").hide("<div>* Date of Event is required</div>");
                $("#time_of_event_error").html("<div>* Time of Event is required</div>");
                error_data++;
                return false;
            }
            else if(description == "" || description == "undefined")
            {
                 $("#time_of_event_error").hide("<div>* Time of Event is required</div>");
                $("#description_error").html("<div>* Description is required</div>");
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
