<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$controller = load_controller( "StatisticController" );
$controller->doAction();
$beanUi = $controller->beanUi;
$statistictype      = $beanUi->get_view_data( "statistictype" );
$selectedmonth      = $beanUi->get_view_data( "selectedmonth" );
$selectedyear       = $beanUi->get_view_data( "selectedyear" );
$allStActivityEstb  = $beanUi->get_view_data( "allStActivityEstb" );
$statisticname      = $beanUi->get_view_data( "statisticname" );
$alldatastat      = $beanUi->get_view_data( "alldatastat" );
$editaction = $beanUi->get_view_data( "editaction" );
$site_root_url = dirname(url());
$controller->get_header();
?>


<div class="container1">
	<h1 class="heading"><?php echo $statisticname; ?></h1>
	<div class="holder2 col-md-12">
		<?php echo $beanUi->get_message(); ?>
	</div>
        <form action="" method="post" id="fyr" enctype="multipart/form-data">
            <input type="hidden" name="statype" value="<?php echo $statistictype; ?>" />        
                    
        <label class="col-sm-1" style="padding-top: 6px;">Month </label>
        <div class="col-sm-2">
            <select name="month" id="month" class="form-control">
                <option value="">select</option>
                <?php
                for ($i=1;$i<=12;$i++) {
                    $month = date('F', mktime(0,0,0,$i, 1, date('Y')));
                    echo '<option value="' . $i . '" '.(($selectedmonth == $i) ? "selected" : "").'>' .$month. '</option>';
                }
                ?>
            </select>
        </div>
        <label class="col-sm-1" style="padding-top: 6px;">Year </label>
        <div class="col-sm-2">
            <select name="year" id="year" class="form-control">
                <option value="">select</option>
                 <?php
                    $currentyr= date("Y");
                    echo '<option '.(($selectedyear == ($currentyr-2)) ? "selected" : "").' value="' . ($currentyr-2) . '" >' .($currentyr-2). '</option>';
                    echo '<option '.(($selectedyear == ($currentyr-1)) ? "selected" : "").' value="' . ($currentyr-1) . '" >' .($currentyr-1). '</option>';
                    echo '<option '.(($selectedyear == $currentyr) ? "selected" : "").' value="' . $currentyr . '" >' .$currentyr. '</option>';
                    echo '<option '.(($selectedyear == ($currentyr+1)) ? "selected" : "").' value="' . ($currentyr+1) . '" >' .($currentyr+1). '</option>';               
                ?>
            </select>
        </div>
        <div class="col-sm-4">
            <input type="hidden" name="_action" value="Create" />
            <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-sm" />

        </div>
    </form>
    <hr /> 
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="statype" value="<?php echo $statistictype; ?>" />        
        <input type="hidden" name="selectedmonth" value="<?php echo $selectedmonth; ?>" />
        <input type="hidden" name="selectedyear" value="<?php echo $selectedyear; ?>" />
    <table class="table table-bordered table-condensed table-responsive">
        <?php if($statistictype == 1 && !empty($allStActivityEstb)) {
        echo '<thead class="bg-primary">
        <tr>
            <th width="15%" rowspan="3" style="vertical-align:middle;"><b>Activity</b></th>
            <th colspan="6">'.$statisticname.'</th>            
        </tr>
        <tr>
            <th colspan="2">Generation</th>
            <th colspan="2">Distribution</th>            
            <th colspan="2">Others</th>            
        </tr>
        <tr>
            <th>Target</th>
            <th>Actual</th>
            <th>Target</th>
            <th>Actual</th>
            <th>Target</th>
            <th>Actual</th>            
        </tr>
        </thead>';
        } else if($statistictype == 2 && !empty($allStActivityEstb)) {
        echo '<thead  class="bg-primary">
        <tr>
            <th  width="15%" rowspan="3" style="vertical-align:middle;"><b>Establishment</b></th>
            <th colspan="6">'.$statisticname.'</th>            
        </tr>
        <tr>
            <th colspan="3">P-SET</th>
            <th colspan="3">C-SET</th>            
        </tr>
        <tr>
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>            
        </tr>
        </thead>';
        } else if($statistictype == 3 && !empty($allStActivityEstb)) {
        echo '<thead class="bg-primary">
        <tr>
            <th  width="15%" rowspan="3" style="vertical-align:middle;"><b>Establishment</b></th>
            <th colspan="9">'.$statisticname.'</th>            
        </tr>
        <tr>
            <th colspan="3">FAC</th>
            <th colspan="3">LWDC</th>            
            <th colspan="3">NEAR MISS</th>            
        </tr>
        <tr>
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>  
            <th>This Month</th>
            <th>YTM This Yr.</th>
            <th>Last Year</th>      
        </tr>
        </thead>';
        } else {}
        
        if(!empty($allStActivityEstb)) {
            foreach( $allStActivityEstb as $row ) {
                $alldata = $alldatastat[$row->id];
                $cls = ($statistictype == 3) ? "" : "display:none;";
            ?>
        <tr>
            <td><?php echo $row->name ?><input type="hidden" name="actestb_id[]" value="<?php echo $row->id; ?>" /></td>
            <td><input type="text" name="colvalue<?php echo $row->id; ?>[]" value="<?php echo $alldata->colone; ?>" style="width:100%;" /></td>
            <td><input type="text" name="colvalue<?php echo $row->id; ?>[]" value="<?php echo $alldata->coltwo; ?>" style="width:100%;" /></td>
            <td><input type="text" name="colvalue<?php echo $row->id; ?>[]" value="<?php echo $alldata->colthree; ?>" style="width:100%;"/></td>
            <td><input type="text" name="colvalue<?php echo $row->id; ?>[]" value="<?php echo $alldata->colfour; ?>" style="width:100%;"/></td>
            <td><input type="text" name="colvalue<?php echo $row->id; ?>[]" value="<?php echo $alldata->colfive; ?>" style="width:100%;"/></td>
            <td><input type="text" name="colvalue<?php echo $row->id; ?>[]" value="<?php echo $alldata->colsix; ?>" style="width:100%;"/></td>
            <td style="<?php echo $cls; ?>"><input type="text" name="colvalue<?php echo $row->id; ?>[]" value="<?php echo $alldata->colseven; ?>" style="width:100%;"/></td>
            <td style="<?php echo $cls; ?>"><input type="text" name="colvalue<?php echo $row->id; ?>[]" value="<?php echo $alldata->coleight; ?>" style="width:100%;"/></td>
            <td style="<?php echo $cls; ?>"><input type="text" name="colvalue<?php echo $row->id; ?>[]" value="<?php echo $alldata->colnine; ?>" style="width:100%;"/></td>
        </tr>
            <?php } ?>
        
        <tfoot>
                    <tr><td colspan="10" align="right">
                            <input type="hidden" name="_action" value="submitData" />
                            <button type="submit" name="B2" class="btn btn-primary btn-sm">Submit</button></td>
</tr>
        </tfoot>
        <?php } ?>
    </table>    
        
    </form>    
    
	
</div>
<?php $controller->get_footer();

//echo $editaction;
?>
<script type="text/javascript">    
    $(document).ready(function () {     
//        var fyid = "<?php echo $editaction; ?>";
//        alert(fyid);
//        if (fyid == "edit") {           
//            $("#fyr").submit();
//            return true;
//        }
    });

</script>
</body>
</html>
