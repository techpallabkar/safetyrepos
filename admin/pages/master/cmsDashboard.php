<?php
if (file_exists("../lib/var.inc.php"))
    require_once( "../lib/var.inc.php" );
$dashCtr = load_controller("DashboardController");
$dashCtr->doAction();
$beanUi = $dashCtr->beanUi;
$welcome_text = $beanUi->get_view_data("welcome_text");
$dashCtr->get_header();
?>
<div class="container1">
    <h1 class="heading"><?php echo $welcome_text ?></h1>
    <div class="row">
        <!-- BEGIN ALERT - REVENUE -->
<!--        <div class="col-md-3 col-sm-6"  style="margin-bottom: 20px;">
            <div class="card">
                <div class="card-body no-padding border-dark bg-white" style="box-shadow: 1px 2px 4px #dfdfdf;">
                    <h5 class="bg-primary" style="margin-top:0;padding:5px 10px;height:40px;"><i class="fa fa-hand-o-right"></i> Manage Library</h5>
                    <div class="col-md-8 col-sm-6">
                        <center>
                            <img src="<?php echo url("assets/images/cms/library.png"); ?>" width = "60">
                        </center>
                    </div>
                    <div class="col-md-4 col-sm-6 ">
                        <center>
                            <a class="btn btn-xs btn-info" href="<?php echo url("pages/master/library/index.php"); ?>">Add</a> 

                            <a class="btn btn-xs btn-danger" href="<?php echo url("pages/master/library/index.php"); ?>">View</a>
                        </center>
                    </div>
                    <div class="col-md-12 col-sm-6" style="background-color:#efefef;margin-top:15px">&nbsp;</div>
                    <div class="clearfix"></div>
                </div>
            </div>end .card-body 
        </div>end .card -->
        
        
        <div class="col-md-3 col-sm-6"  style="margin-bottom: 20px;">
            <div class="card">
                <div class="card-body no-padding border-dark bg-white" style="box-shadow: 1px 2px 4px #dfdfdf;">
                    <h5 class="bg-primary" style="margin-top:0;padding:5px 10px;height:40px;"><i class="fa fa-hand-o-right"></i> Important Information</h5>
                    <div class="col-md-8 col-sm-6">
                        <center>
                            <img src="<?php echo url("assets/images/cms/info.png"); ?>" width = "60" height="60">
                        </center>
                    </div>
                    <div class="col-md-4 col-sm-6 ">
                        <center>
                            
                            <a class="btn btn-xs btn-danger" href="<?php echo url("pages/master/cms/importantinformation.php"); ?>">View</a>
                        </center>
                    </div>
                    <div class="col-md-12 col-sm-6" style="background-color:#efefef;margin-top:15px" >&nbsp;</div>
                    <div class="clearfix"></div>
                </div>
            </div><!--end .card-body -->
        </div><!--end .card -->


<!--<div class="col-md-3 col-sm-6"  style="margin-bottom: 20px;">
            <div class="card">
                <div class="card-body no-padding border-dark bg-white" style="box-shadow: 1px 2px 4px #dfdfdf;">
                    <h5 class="bg-primary" style="margin-top:0;padding:5px 10px;height:40px;"><i class="fa fa-hand-o-right"></i> Manage CMS</h5>
                    <div class="col-md-8 col-sm-6">
                        <center>
                            <img src="<?php echo url("assets/images/cms/cms.png"); ?>" width = "60" height="60">
                        </center>
                    </div>
                    <div class="col-md-4 col-sm-6 ">
                        <center> 
                            <a class="btn btn-xs btn-danger" href="<?php echo url("pages/master/cms/managecms.php"); ?>">View</a>
                        </center>
                    </div>
                    <div class="col-md-12 col-sm-6" style="background-color:#efefef;margin-top:15px">&nbsp;</div>
                    <div class="clearfix"></div>
                </div>
            </div>end .card-body 
        </div>end .card -->
<div class="col-md-3 col-sm-6"  style="margin-bottom: 20px;">
            <div class="card">
                <div class="card-body no-padding border-dark bg-white" style="box-shadow: 1px 2px 4px #dfdfdf;">
                    <h5 class="bg-primary" style="margin-top:0;padding:5px 10px;height:40px;"><i class="fa fa-hand-o-right"></i> Manage Messages</h5>
                    <div class="col-md-8 col-sm-6">
                        <center>
                            <img src="<?php echo url("assets/images/cms/msg.png"); ?>" width = "60">
                        </center>
                    </div>
                    <div class="col-md-4 col-sm-6 ">
                        <center>
                            <a class="btn btn-xs btn-info" href="<?php echo url("pages/master/cms/addManageMessages.php"); ?>">Add</a> 

                            <a class="btn btn-xs btn-danger" href="<?php echo url("pages/master/cms/manageMessages.php"); ?>">View</a>
                        </center>
                    </div>
                    <div class="col-md-12 col-sm-6" style="background-color:#efefef;margin-top:15px">&nbsp;</div>
                    <div class="clearfix"></div>
                </div>
            </div><!--end .card-body -->
        </div><!--end .card -->

<div class="col-md-3 col-sm-6"  style="margin-bottom: 20px;">
            <div class="card">
                <div class="card-body no-padding border-dark bg-white" style="box-shadow: 1px 2px 4px #dfdfdf;">
                    <h5 class="bg-primary" style="margin-top:0;padding:5px 10px;height:40px;"><i class="fa fa-hand-o-right"></i> Upcoming Events</h5>
                    <div class="col-md-8 col-sm-6">
                        <center>
                            <img src="<?php echo url("assets/images/cms/event.png"); ?>" width = "60">
                        </center>
                    </div>
                    <div class="col-md-4 col-sm-6 ">
                        <center>
                            <a class="btn btn-xs btn-info" href="<?php echo url("pages/master/event/add.php"); ?>">Add</a> 

                            <a class="btn btn-xs btn-danger" href="<?php echo url("pages/master/event/index.php"); ?>">View</a>
                        </center>
                    </div>
                    <div class="col-md-12 col-sm-6" style="background-color:#efefef;margin-top:15px">&nbsp;</div>
                    <div class="clearfix"></div>
                </div>
            </div><!--end .card-body -->
        </div><!--end .card -->
        
        <div class="col-md-3 col-sm-6"  style="margin-bottom: 20px;">
            <div class="card">
                <div class="card-body no-padding border-dark bg-white" style="box-shadow: 1px 2px 4px #dfdfdf;">
                    <h5 class="bg-primary" style="margin-top:0;padding:5px 10px;height:40px;"><i class="fa fa-hand-o-right"></i> Manage Gallery</h5>
                    <div class="col-md-8 col-sm-6">
                        <center>
                            <img src="<?php echo url("assets/images/cms/gallery.png"); ?>" width = "60">
                        </center>
                    </div>
                    <div class="col-md-4 col-sm-6 ">
                        <center>
                            <a class="btn btn-xs btn-info" href="<?php echo url("pages/master/gallery/add.php"); ?>">Add</a> 

                            <a class="btn btn-xs btn-danger" href="<?php echo url("pages/master/gallery/index.php"); ?>">View</a>
                        </center>
                    </div>
                    <div class="col-md-12 col-sm-6" style="background-color:#efefef;margin-top:15px">&nbsp;</div>
                    <div class="clearfix"></div>
                </div>
            </div><!--end .card-body -->
        </div><!--end .card -->

    </div> 


</div>


<?php $dashCtr->get_footer(); ?>
</div>
</body>
</html>
