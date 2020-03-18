<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );
$controller = load_controller( "UserController" );
$controller->doAction();
$beanUi = $controller->beanUi;

$allusers 		= $beanUi->get_view_data("allusers");
$users_paggin_html 	= $beanUi->get_view_data("users_paggin_html");
$page 			= $beanUi->get_view_data("page");
$search_user_txt 	= $beanUi->get_view_data("search_user_txt");
$user_status 		= $beanUi->get_view_data("user_status");
$status_id 		= $beanUi->get_view_data("status_id");
$controller->get_header();
?>
<div class="container1">
     <h1 class="heading"> Manage Users
        <a href="add.php" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add New</a>
    </h1> 
    <?php echo $beanUi->get_message(); ?>
    <?php
    /*if($controller->get_auth_user("role_id")!= 1)
    {
    echo '<div class="error">You don`t have permission to access this page.</div>'; 
    die();
    }*/
    ?>
    <div class="holder">
        <form name="searchuser" id="searchuser" method="post">
            <input type="hidden" name="page" value="<?php echo $page; ?>" />
            <input type="text" name="search_user_txt" id="search_user_txt" class="search" value="<?php echo $search_user_txt?>" placeholder="Search By Username/Name" />
            <select name="status_id" id="status_id">
                <option value=""> --select status-- </option>
                <?php
                if( ! empty( $user_status ) ) {
                    foreach( $user_status as $row ) {
                        if( $status_id == $row->id ) {
                                echo "<option value=\"".$row->id."\" selected>".$row->status_name."</option>\n";
                        } else {
                                echo "<option value=\"".$row->id."\">".$row->status_name."</option>\n";
                        }

                    }
                }
                ?>
            </select>
            <input type="submit" value="Go" class="btn btn-sm btn-primary" />
            <a href=""  class="btn btn-sm btn-danger">Reset</a>
        </form>
	</div>
	<div class="actionbar">
            <div class="buttons" style="width:50%;">
                <a href="index.php?action=delete_user" class="btn btn-danger btn-sm btnbulk" data-value="Delete"><i class="fa fa-trash"></i> Delete</a>
            <a href="index.php?action=resetPassword" class="btn btn-info btn-sm btnbulk" data-value="Reset"><i class="fa fa-key"></i> Reset password</a>
                <a href="index.php?action=bulk_active_innactive&status_id=2"  data-value="Active" class="btn btn-sm btn-success btnbulk"><i class="fa fa-check"></i> Active</a>
                <a href="index.php?action=bulk_active_innactive&status_id=3" data-value="Deactive" class="btn btn-sm btn-warning btnbulk"><i class="fa fa-times"></i> Deactive</a>
            </div>
	</div>
	<div class="message"></div>
	<div class="container">
            <div class="table-responsive">
            <table id="postlist" class="table table-striped table-bordered table-condensed table-responsive responsive-utilities jambo_table bulk_action">
            <thead>
                <tr>
                    <th width="5%" align="center"><input type="checkbox" id="toggle_check" /></th>
                    <th width="20%">Employee Code</th>
                    <th width="20%">Display Name</th>
                    <th width="20%">E-mail</th>
                    <th width="20%">Contact no</th>
                    <th>Active</th>
                </tr>
            </thead>
                <?php if( !empty( $allusers ) ) { ?>
            <tbody>
		<?php
		$class = 'even';
		foreach( $allusers as $row ) {
                    $class = ($class == 'even') ? 'odd' : 'even';
		?>
                    <tr class="<?php echo $class; ?>" role="row">
                        <td align="center">
                        <?php 
                        if($controller->get_auth_user("role_id") == 3)
                        {
                            if( $row->role_id != 3 ) 
                                echo '<input type="checkbox" name="ids[]" id="ids" value="'.$row->id.'" />'."\n";
                        }
                        else
                        {
							if( $row->role_id != 1 ) 
                                echo '<input type="checkbox" name="ids[]" id="ids" value="'.$row->id.'" />'."\n";
						}
                        ?>
                        </td>
                        <td>
                        <?php 
                        echo $row->employee_code;
                        ?>
                        </td>
                        <td><?php echo $row->full_name; ?></td>
                        <td><?php echo $row->email; ?></td>
                        <td><?php echo $row->mobile_no; ?></td>
                        <td align="center">
                            <?php 
                            if( $row->role_id == 1 ) {
                                echo '<img src="'.url('assets/images/icon1Active.png').'" />'."\n";
                            } else {
                                if( $row->status_id == 2 ) {

                                        echo "<a href=\"#\" 
                                        onclick=\"activation('".page_link("users/?action=activation&id=".$row->id."&status_id=".$row->status_id)."', '".$row->id."')\"  
                                        class=\"activate\" id=\"activate-".$row->id."\" >
                                        <img src=\"".url("assets/images/icon1Active.png")."\" />
                                        </a>";
                                } elseif( $row->status_id == 3 ) {
                                        echo "<a href=\"#\" 
                                        onclick=\"activation('".page_link("users/?action=activation&id=".$row->id."&status_id=".$row->status_id)."', '".$row->id."')\"  
                                        class=\"activate\" id=\"activate-".$row->id."\" >
                                        <img src=\"".url("assets/images/icon1Inactive.png")."\" />
                                        </a>";
                                }  elseif( $row->status_id == 1 ) {
                                        echo "Registered";
                                }
                            }
                            ?>
                        </td>
                    </tr>
	<?php } ?>
        </tbody>
    <?php } else { echo '<tfoot><tr><td class="error" colspan="6"> Records not available</td></tr></tfoot>'; } ?>
    </table>
            </div>
            <hr />
            <?php echo $users_paggin_html; ?>
            </div>
            </div>
                <?php $controller->get_footer(); ?>
</body>
</html>
