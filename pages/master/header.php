<?php
//header('Content-type: text/html; charset=utf-8');
$all_categories = $this->beanUi->get_view_data("all_categories");
$all_categories = $this->beanUi->get_view_data("all_categories");
$catid = $this->beanUi->get_view_data("catid");

$magazin_menu = "";
$magazin_catid = ( PAGENAME == 'magazines.php' ) ? $catid : 0;
@$magazin_menu = get_menu($all_categories["magazine_categories"], 0, 0, -1, $magazin_catid, 'magazines.php');
@$magazin_menu = substr($magazin_menu, 8, strlen($magazin_menu));
@$magazin_menu = substr($magazin_menu, 0, (strlen($magazin_menu) - 10));

$presentation_catid = "";
$presentation_menu = "";
$presentation_catid = ( PAGENAME == 'presentations.php' ) ? $catid : 0;
@$presentation_menu = get_menu($all_categories["presentation_categories"], 0, 0, -1, $presentation_catid, 'presentations.php');
@$presentation_menu = substr($presentation_menu, 8, strlen($presentation_menu));
@$presentation_menu = substr($presentation_menu, 0, (strlen($presentation_menu) - 10));


?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link type="text/css" media="all" href="<?php echo url("assets/css/style.css") ?>" rel="stylesheet" />
        <title>Shankha</title>
        <link type="text/css" media="all" href="<?php echo url("assets/css/jquery-scrollify-style.css") ?>" rel="stylesheet" />
        <link rel="stylesheet" id="mh-google-fonts-css" href="<?php echo url("assets/css/googleapi_OpenSans400_400italic_700_600.css") ?>" type="text/css" media="all" />
        <?php echo $this->loadCss(); ?>

        <script type="text/javascript" src="<?php echo url("assets/js/jquery_002.js") ?>"></script>
        <script type="text/javascript" src="<?php echo url("assets/js/jquery_003.js") ?>"></script>

        <script type="text/javascript" src="<?php echo url("assets/js/jquery.js?ver=1.11.3") ?>"></script>
        <script type="text/javascript" src="<?php echo url("assets/js/jquery-migrate.min.js?ver=1.2.1") ?>"></script>
        <script src="<?php echo url("admin/assets/js/tableHeadFixer.js") ?>"></script>
        <script type="text/javascript" src="<?php echo url("assets/js/scripts.js?ver=4.4") ?>"></script>
<link type="text/css" media="all" href="<?php echo url("assets/css/prettyPhoto.css") ?>" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url("assets/js/jquery.prettyPhoto.js") ?>"></script>
       
    </head>
    <body id="mh-mobile" class="home page page-template-homepage page-template-homepage-php custom-background mh-right-sb">

        <div class="mh-container mh-container-outer">
            <div class="mh-header-mobile-nav clearfix"></div>
            <header class="mh-header">
                <div class="mh-container mh-container-inner mh-row clearfix">
                    <div class="mh-custom-header" role="banner">
						<div class="mh-col-1-6">
							<a href="index.php" rel="home">
                            <div class="mh-site-logo" role="banner">
                                <img class="mh-header-image" src="<?php echo url("assets/images/cesc-logo.png"); ?>" alt="CESC Safety Monitoring System logo" />
                                <!--<div class="powerbank">Safety Monitoring System</div>-->
                            </div> </a>
						</div>	
						
						<div class="mh-col-1-6" style="margin-right:0px;float:right;">
							<div class="header-right">
								<img class="mh-header-image" src="<?php echo url("assets/images/rpgs-logo.png"); ?>" alt="RPSG" />
							</div>
						</div>
						<div class="mh-col-4-6">
							<div class="header-image">
							<img class="mh-header-image" src="<?php echo url("assets/images/header-image.png"); ?>" alt="CESC Safety Monitoring System" />
							</div>
						</div>
                    </div>
                </div>
                <?php
                $whereclause = 'id NOT IN(10,9) ORDER BY orderID ASC';
                $activites = $this->dao->get_activities($whereclause);
                
                
              
                ?>
            </header>	
        </div>
        <nav class="mh-main-nav clearfix">
            <div class="mh-container mh-container-outer">
                <div class="mh-main-nav-wrap">

                    <div class="menu-navigation-container">
                        <ul id="menu-navigation" class="menu">
                            <li><a href="index.php" <?php if (PAGENAME == 'index.php') {
							echo 'class="active"';
							} ?>>Home</a></li>
										<li><a href="#" <?php if (PAGENAME == 'activities.php' || PAGENAME == 'activitydetails.php') {
								echo 'class="active"';
							} ?>>Activities</a>
                                <ul class="sub-menu">
                                    <?php
                                    if (!empty($activites)) {
                                        foreach ($activites as $act) {
                                            if( $act->activity_name != "" ) {
                                            ?>
                                            <li><a href="<?php echo page_link('activities.php?actype_id=' . $act->id . ''); ?>"><?php echo ucfirst($act->activity_name); ?></a></li>
                                            <?php
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>
                            <!--<li><a href="safetyLibrary.php"  <?php //if (PAGENAME == 'safetyLibrary.php') { echo 'class="active"'; } ?>>Safety Library</a></li>-->
                            <li><a href="#LINK_WITH_CESCINTRANET"  <?php //if (PAGENAME == 'safetyLibrary.php') { echo 'class="active"'; } ?>>Safety Library</a></li>
                            <li><a href="gallery.php" <?php if (PAGENAME == 'gallery.php') { echo 'class="active"'; } ?>>Gallery</a></li>
                           <li><a href="calendar.php" <?php if (PAGENAME == 'calendar.php') { echo 'class="active"'; } ?>>Calendar</a></li>
						   <div class="clearfix"></div>
                        </ul>
						<ul class="important-link">
                                                    <li><a href="locationSearch.php"<?php if (PAGENAME == 'locationSearch.php') { echo 'class="active"'; } ?>>Search By Location</a></li>
							<li><a href="imp_information.php" class="implink">Important Information</a></li>
						</ul>
						<div class="clearfix"></div>
                    </div>

                </div>
            </div>
        </nav>
        <div class="mh-container mh-container-outer">   
