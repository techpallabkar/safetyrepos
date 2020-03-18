<?php
if (file_exists("../../lib/var.inc.php"))
    require_once( "../../lib/var.inc.php" );
$controller = load_controller("EventController");
$controller->doAction();
$beanUi = $controller->beanUi;
$eventData  =  $beanUi->get_view_data('eventData');
$controller->get_header();
?>

<div class="container1" style="min-height: 800px;">
    <h1 class="heading">View All Events <a href="add.php" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a></h1>
    <div class="message"><?php echo $beanUi->get_message(); ?></div><br />
   <table class="table table-bordered table-condensed table-responsive">
        <thead>
            <tr class="bg-primary">
                <th>Sl.No</th>
                <th>Category</th>
                <th>Subject</th>
                <th>Department</th>
                <th>Location</th>
                <th>Date Of Event</th>
                <th>Time Of Event</th>
                <th>Status</th>
                <th align="center" width="15%">Action</th>
            </tr>
            <?php
            $currentDate = date("Y-m-d");
            foreach ($eventData as $key => $rowdata) {
                
                if( $rowdata->status == "completed" )
                {
                    $statusIcon = '<i class="fa fa-check-circle text-success fa-2x"></i>';
                }
                else if( ($rowdata->status == "upcoming") && ($rowdata->date_of_event < $currentDate) )
                {
                   $statusIcon = '<i class="fa fa-exclamation-circle text-danger fa-2x"></i>'; 
                }
                else
                {
                    $statusIcon = '';
                    //$statusIcon = '<i class="fa fa-arrow-circle-down text-info fa-2x"></i>';
                }
                echo '<tr>'
                . '<td align="center">' . ($key + 1) . '.</td>'
                . '<td>' . $rowdata->name . '</td>'
                . '<td>' . substr(strip_tags($rowdata->subject),0,100). '</td>'
                . '<td>' . $rowdata->department . '</td>'
                . '<td>' . $rowdata->location . '</td>'
                . '<td align="center">' . (($rowdata->date_of_event != "") ? date("d-m-Y",strtotime($rowdata->date_of_event)) : "") . '</td>'
                . '<td align="center">' . $rowdata->time_of_event . '</td>'
                . '<td align="center" title="'.$rowdata->status.'">' . $statusIcon. '</td>'
                . '<td align="center"><a href="editEvent.php?editid=' . $rowdata->id . '" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="index.php?deltid=' . $rowdata->id . '" onclick="return confirm(\'Confirm delete.\');" class="btn btn-info btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a></td>'
                . '</tr>';
            }
            ?>
        </thead>

    </table>

</div>

<?php $controller->get_footer(); ?>


</body>
</html>
