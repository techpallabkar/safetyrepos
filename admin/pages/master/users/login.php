<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$user_controller = load_controller( "UserController" );
$user_controller->doAction();

$beanUi = $user_controller->beanUi;

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>CESC Safety Monitoring System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo url('assets/css/bootstrap.css') ?>" />
	<link rel="stylesheet" href="<?php echo url('assets/css/style.css') ?>" />
	<script type="text/javascript" src="<?php echo url('assets/js/jquery.min.js') ?>"></script>
</head>
<body>

<div class="container-fluid">
    <!-- style="background-color:#fff;color:red;border-bottom:4px solid #0283C8;"
    -->
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-3">
			<img src="<?php echo url('assets/images/logo2.png') ?>" alt="">
		</div>
		<div class="col-sm-6 col-sm-6 col-md-3 pull-right text-right">
			<div style="padding-top:10px;"><img src="<?php echo url('assets/images/rpgs-logo.png') ?>" alt=""></div>
		</div>
		<div class="col-sm-12 col-md-6 text-center"><div class=clearfix"></div><h2 style="color:#fff;text-transform: uppercase;text-shadow:2px 3px 4px #d0d0d0;">Safety Monitoring System</h2></div>
	</div>

	<div class="row">
		
		<div class="col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<div class="login-bg">
				<div class="lg_container">
					<h1 class="heading">Login Here</h1>
					<?php echo $beanUi->get_message(); ?>
					<form id="lg-form" name="lg-form" method="post">
						<div>
							<label for="employee_code"></label>
                                                        <input autocomplete="off" type="text" name="data[employee_code]" id="employee_code" required="" placeholder="Enter Your Username" style="width:98%;" />
							<?php echo $beanUi->get_error( "employee_code" ); ?>
						</div>
						<div>
							<label for="password"></label>
							<input autocomplete="off" type="password" name="data[password]" id="password" required="" placeholder="Enter Your Password" style="width:98%;" />
							<?php echo $beanUi->get_error( "password" ); ?>
						</div>
						<div>				
							<button id="btn_submit" class="btn btn-primary">Login</button>
							<input type="hidden" name="action" value="login" />
						</div>
					</form>
					<div id="message"></div>
				</div>
			</div>
                    <div style="text-align: center;color:#fff;font-size: 13px;">
	All right reserved &copy; CESC Safety Monitoring System, <?php echo date('Y'); ?>
        <br>
		Designed & developed by <a href="http://viavitae.co.in" target="_blank">Via Vitae Solutions</a>
	</div>
		</div>	
            
            
	</div>
   
</div>

<script type="text/javascript" src="<?php echo url('assets/js/default.js') ?>"></script>
</body>
</html>
<?php $beanUi->reset_messages(); ?>
