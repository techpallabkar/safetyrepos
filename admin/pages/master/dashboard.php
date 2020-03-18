<?php
if( file_exists( "../lib/var.inc.php" ) ) require_once( "../lib/var.inc.php" );
$dashCtr = load_controller( "DashboardController" );
$dashCtr->doAction();
$beanUi = $dashCtr->beanUi;
$welcome_text = $beanUi->get_view_data("welcome_text");
$allposts= $beanUi->get_view_data("allposts");
$auth_user_id= $beanUi->get_view_data("auth_user_id");
$is_nodal_officer= $beanUi->get_view_data("is_nodal_officer");
$dashCtr->get_header();
?>
<style>
    .card-height{height: 83px;}
</style>
	<div class="container1">
		<h1 class="heading"><?php echo $welcome_text ?></h1>
                 <?php if($is_nodal_officer != 1){ ?>
               <div class="row">
	<?php if( !empty( $allposts ) ) {  ?>
		<?php
		$class = 'even';
		foreach( @$allposts as $row ) {
		$class = ($class == 'even') ? 'odd' : 'even';
                if($row->id != 9 && $row->id != 10) {
		?>
<!-- BEGIN ALERT - REVENUE -->
                <div class="col-md-3 col-sm-6"  style="margin-bottom: 20px;">
			<div class="card">
                            <div class="card-body no-padding border-dark bg-white" style="box-shadow: 1px 2px 4px #dfdfdf;">
                                <h5 class="bg-primary" style="margin-top:0;padding:5px 10px;height:40px;"><i class="fa fa-hand-o-right"></i> <?php echo $row->activity_name; ?></h5>
                                <div class="card-height">
                        <div class="col-md-5 col-sm-5">
						<center>
						<a href="activity/index.php?activity_id=<?php echo $row->id; ?>">
						<?php
						if($row->id == 1)
						{
                                                        $pagelink = "create.php";
							echo '<img src="'.url("assets/images/1.png").'" width = "60">';
						}
						else if($row->id == 2)
						{   
                                                        $pagelink = "create.php";
							echo '<img src="'.url("assets/images/2.png").'" width = "60">';
						}
						else if($row->id == 3)
						{
                                                        $pagelink = "create.php";
							echo '<img src="'.url("assets/images/3.png").'" width = "60">';
						}
						else if($row->id == 4)
						{
                                                        $pagelink = "create.php";
							echo '<img src="'.url("assets/images/4.png").'" width = "60">';
						}
                                                else if($row->id == 5)
						{
                                                        $pagelinkgnsa = "add_audit_new.php";
                                                        $pagelink = "add_audit.php";
							echo '<img src="'.url("assets/images/5.png").'" width = "60">';
						}
                                                else if($row->id == 6)
						{
                                                        $pagelink = "add_incident.php";
							echo '<img src="'.url("assets/images/6.png").'" width = "60">';
						}
                                                else if($row->id == 7)
						{
                                                        $pagelinkgnpa = "add_ppe_audit_nm.php";
                                                        $pagelink = "add_ppe_audit.php";
							echo '<img src="'.url("assets/images/7.png").'" width = "60">';
						}
                                                else if($row->id == 8)
						{
                                                        $pagelink = "add_safety_observation.php";
							echo '<img src="'.url("assets/images/8.png").'" width = "60">';
						}
                                                else if($row->id == 9)
						{
                                                        $pagelink = "add_safety_observation_line_function.php";
							echo '<img src="'.url("assets/images/8.png").'" width = "60">';
						}
                                                else if($row->id == 10)
						{
                                                        $pagelink = "add_mom.php";
							echo '<img src="'.url("assets/images/2.png").'" width = "60">';
						}
						?>
						</a>
						</center>
						</div>
						<div class="col-md-7 col-sm-7">
						<center>
                                                    <?php if($row->id == 5){ ?>
                                                        <a class="btn btn-xs btn-info" href="activitynew/<?php echo $pagelinkgnsa; ?>?activity_id=<?php echo $row->id; ?>">Add (Gen/Other)</a> 
                                                    <?php } ?>
                                                    <?php if($row->id == 7){ ?>
                                                        <a class="btn btn-xs btn-info" href="activity/<?php echo $pagelinkgnpa; ?>?activity_id=<?php echo $row->id; ?>">Add (Gen/Other)</a> 
                                                    <?php } ?>
						<a class="btn btn-xs btn-info" href="activity/<?php echo $pagelink; ?>?activity_id=<?php echo $row->id; ?>"><?php echo ($row->id == 5 ? "Add (Mains)" : ($row->id == 7 ? "Add (Mains)" : "Add")); ?></a> 
                                                <br><a class="btn btn-xs btn-danger" href="activity/index.php?activity_id=<?php echo $row->id; ?>">View</a>
						</center>
						</div>
						</div>
						<div class="col-md-12 col-sm-6" style="background-color:#efefef;margin-top:15px">&nbsp;</div>
						<div class="clearfix"></div>
                        </div>
                        </div>
		</div>
                        
                <?php } } ?>	
	<?php } ?>
        </div> 
        <?php } ?>              
	</div>
<?php $dashCtr->get_footer(); ?>
</div>
</body>
</html>
