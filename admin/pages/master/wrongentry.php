<?php
if (file_exists("../lib/var.inc.php"))
    require_once( "../lib/var.inc.php" );
$dashCtr = load_controller("DashboardController");
$dashCtr->doAction();
$beanUi         = $dashCtr->beanUi;
$welcome_text   = $beanUi->get_view_data("welcome_text");
$allposts       = $beanUi->get_view_data("allposts");
$treedata       = $beanUi->get_view_data("treedata");
$dashCtr->get_header();
?>
<div class="container1">
    <h1 class="heading"><?php echo $welcome_text ?></h1>
    <div class="row">
        <?php
        if (!empty($allposts)) {
            $class = 'even';
            foreach (@$allposts as $row) {
                $class = ($class == 'even') ? 'odd' : 'even';
                if ($row->id != 9 && $row->id != 10) {
                    ?>
                    <div class="col-md-6 col-sm-6"  style="margin-bottom: 20px;">
                        <div class="card">
                            <div class="card-body no-padding border-dark bg-white" style="min-height:150px;">
                                
                                <table class="table table-bordered" >
                                    <tr class="bg-primary"><th colspan="2"><?php echo $row->activity_name; ?></th></tr>
                                    <tr>
                                        <th width="20%">Division Department Tree</th>
                                        <th><?php $tree = $treedata["$row->id"]["tree"]; 
                                         $dates = $treedata["$row->id"]["dates"]; 
                                       echo count($tree); 
                                       if(count($tree) > 0 ) {
                                        ?>    
                                        <!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModaltree<?php echo $row->id; ?>">View All</button>

<!-- Modal -->
<div id="myModaltree<?php echo $row->id; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $row->activity_name; ?> : TREE NOT AVAILABLE</h4>
      </div>
      <div class="modal-body">
          <table class="table table-bordered">
              <tr>
                  <th>ID</th>
                  <th>Status</th>
                  <th>Created On</th>
              </tr>
              <?php foreach($tree as $rowdata) { 
              echo '<tr>
                  <td>'.$rowdata->id.'</td>
                  <td>'.$rowdata->status_name.'</td>
                  <td>'.$rowdata->created_date.'</td>
                  
              </tr>';
               } ?>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
                                       <?php } ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th width="40%">
                                            <?php
                                            if($row->id ==1 || $row->id==2 || $row->id==4) {
                                                echo "Activity Date";
                                            } else if($row->id == 3) {
                                                echo "Period of Activity";
                                            } else if($row->id == 5 || $row->id == 7) {
                                                echo "Date of Audit";
                                            } else if ($row->id == 6) {
                                                echo "Date of Incident";                                            
                                            }
                                            ?>
                                        </th>
                                        <th><?php echo count($dates); if(count($dates) > 0 ) { ?>   
                                        <!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModaldates<?php echo $row->id; ?>">View All</button>

<!-- Modal -->
<div id="myModaldates<?php echo $row->id; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $row->activity_name; ?> : ACTIVITY DATE NOT AVAILABLE</h4>
      </div>
      <div class="modal-body">
         <table class="table table-bordered">
              <tr>
                  <th>ID</th>
                  <th>Status</th>
                  <th>Created On</th>
              </tr>
              <?php 
              foreach($dates as $rowdata) { 
//                  $actypeid = $rowdata->activity_type_id;
//                  if($actypeid == 1 || $actypeid == 2 || $actypeid == 4 ) {
//                      $datecolmn = "activity_date";
//                  } else if($actypeid == 3) {
//                      $datecolmn = "from_date";
//                  } else if($actypeid == 5 || $actypeid == 7) {
//                      $datecolmn = "date_of_audit";
//                 
//                  } else if($actypeid == 6) {
//                      $datecolmn = "date_of_incident";
//                  
//                  } else if($actypeid == 8) {
//                      $datecolmn = "from_date";
//                  }
              echo '<tr>
                  <td>'.$rowdata->id.'</td>
                  <td>'.$rowdata->status_name.'</td>
                  <td>'.$rowdata->created_date.'</td>
                  
              </tr>';
               } ?>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
                                        <?php } ?>
                                        </th>
                                    </tr>
                                </table>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            }
        }
        ?>
    </div> 
</div>
<?php $dashCtr->get_footer(); ?>
</div>
</body>
</html>
