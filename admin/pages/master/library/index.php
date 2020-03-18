<?php
if( file_exists( "../../lib/var.inc.php" ) ) require_once( "../../lib/var.inc.php" );

$controller = load_controller( "LibraryController" );
$controller->doAction();
$beanUi = $controller->beanUi;
$categories 		= $beanUi->get_view_data( "categories" );
//echo "<pre>";
//print_r($categories);die;
$page 				= $beanUi->get_view_data( "page" );
$rows 				= $beanUi->get_view_data( "rows" );
$paggin_html                    = $beanUi->get_view_data( "paggin_html" );
$master_status                  = $beanUi->get_view_data( "master_status" );
$search_title                   = $beanUi->get_view_data( "search_title" );
$status_id 			= $beanUi->get_view_data( "status_id" );
$cat_id 			= $beanUi->get_view_data( "cat_id" );
$site_root_url                  = dirname(url());

$controller->setCss("tree");
$controller->get_header();
?>

<style type="text/css">
#selected_cat{  }
.smallfont{ font-size:90%; }
</style>

<div class="container1">
	<h1 class="heading">Library</h1>
	
	<div class="holder2 col-md-4" id="maintree">
		<div class="message"></div>
		<div class="holder">
			<button id="add_category" class="btn btn-xs btn-info">Add Category</button>
			<button id="edit_category" class="btn btn-xs btn-success">Update Category</button>
			<button id="purge_category" class="btn btn-xs btn-danger">Delete Category</button>
		</div>
		<div class="hr"></div>
		<p><b><i>Category</i></b></p>
		<?php create_tree_view_cms($categories, 0, 0, -1, $cat_id, 1); ?>
	</div>
	
	<div class="holder2 col-md-8" id="rightform2" style="width:72%;">
		<?php echo $beanUi->get_message(); ?>
	<div class="holder">
		<form name="searchuser" id="searchuser" method="post">
			<input type="hidden" name="page" value="<?php echo $page; ?>" />
			<input type="text" name="search_title" id="search_title" class="search" value="<?php echo $search_title?>" placeholder="Search title" />
			<select name="status_id" id="status_id">
				<option value=""> --Select status-- </option>
				<?php
				if( ! empty( $master_status ) ) {
					$admin_status 	= array("Draft", "Published", "Unpublished","Archive");
					foreach( $master_status as $row ) {
						if( ! in_array( $row->status_name, $admin_status ) ) continue;
						
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
		</form>
	</div>
	<div class="actionbar">
		<div class="filters" style="width:80%;">
			<button id="addpresentation" onclick="location.href='./'" class="btn btn-sm btn-primary">Show all</button>
		</div>
		<button id="add_content" class="btn btn-sm btn-primary">Add Library File</button>
		
	</div>
	<div class="breadcum"><span class="holder" id="selected_cat"></span></div>
		<?php  if( count($rows) ) { ?>
			<table id="postlist" class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
				<tr>
					<?php /*?><th width="5%"><input type="checkbox" id="toggle_check"  /></th><?php*/ ?>
					<th width="70%">Title</th>
					<th>File</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<?php if( !empty( $rows ) ) { ?>
			<tbody>
				<?php
				$class = 'even';
				
				foreach( $rows as $row ) {
					$class = ($class == 'even') ? 'odd' : 'even';
					$breadcumb = ( $row->cat_path != "" ) ? str_replace('/', " <span class=\"fa fa-caret-right\"></span> ", $row->cat_path) : '';
				?>
					<tr class="<?php echo $class; ?>" role="row">
						<?php /*?><td align="center"><input type="checkbox" name="ids[]" id="ids" value="<?php echo $row->id ?>"  /></td><?php*/ ?>
						<td>							
							<?php
							echo '<div class="holder">'.page_link("library/edit.php?id=".$row->id, $row->title).'</div>'."\n";
							echo ( $cat_id ) ? "" : '<p class="breadcum category">'.$breadcumb.'</p>';
							?>
							<div class="holder">
								<?php
								$created_by_name 	= $row->created_by_name;
								$created_date 		= date("d-m-Y h:i A",strtotime($row->created_date));
								echo "<i class=\"smallfont\">Created by : ".$created_by_name.", ".$created_date."</i>";
								?>
							</div>
						</td>
						<td align="center">
							<?php
							$image_path = CESC_BASE_PATH . "/" . $row->file_path;
							if( is_file( $image_path ) ) 
								echo '<a href="'.$site_root_url."/".$row->file_path.'" target="_blank"><img src="'.url("assets/images/pdfIcon.png").'" style="width:20px;" /></a>';
							?>
						</td>
						<td align="center">
							<?php
							//echo active_icons($bean, $comp_name.'/?action=activation&id='.$row->id.'&status='.$row->status, $row->status, $row->id);
							echo get_value_by_id($row->status_id, "status_name", $master_status);
							?>
						</td>
						<td align="center">
							<a href="edit.php?id=<?php echo $row->id ?>">Edit</a>
							<?php
							if( $row->status_id == 1 )
							echo ' | <a href="?action=purge_ebook&id='.$row->id.'&cat_id='.$cat_id.'&page='.$page.'" onclick="return confirm(\'Confirm delete.\');">Delete</a>';
							?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
			<?php } ?>
			</table>
			<hr />
			<div class="pagging"><?php echo $paggin_html; ?></div>
		<?php } ?>
	</div>
	
	<input type="hidden" id="category_id" value="<?php echo $cat_id?>" />
        <div class="clearfix"></div>
</div>

<?php $controller->get_footer(); ?>

<script>
jQuery(document).ready(function ($) {
	var cat_id = jQuery("#category_id").val();
	var cat_paths 	= Array();
	<?php foreach( $categories as $key => $catrow ) { ?>
		cat_paths[<?php echo $catrow->id; ?>] = "<?php echo str_replace("/", " &#10095; ", $catrow->path); ?>";
	<?php } ?>
	if( cat_id > 0 ) select_cat_path(cat_id);
	
	jQuery("#add_category").click(function () {


		cat_id = jQuery("#category_id").val();
		location.href='add_category.php?parent_id='+cat_id;
	});
	
	jQuery("#edit_category").click(function () {
		cat_id = jQuery("#category_id").val();
		if( cat_id > 0 ) location.href='edit_category.php?id='+cat_id;
	});
	
	jQuery("#purge_category").click(function () {
		cat_id = jQuery("#category_id").val();
		if( cat_id > 0 ) {
			if( confirm( "All child categories and it's contents will be deleted. Confirm delete category." ) ) {
				location.href='?action=purge_category&id='+cat_id+"&page=<?php echo $page;?>";
			}
		}
	});
	
	jQuery("#add_content").click(function () {
		cat_id = jQuery("#category_id").val();
		location.href='add.php?category_id='+cat_id;
	});
	
	
	jQuery("#maintree").find("input[type=checkbox]").attr("checked", "checked");
	jQuery("#maintree li label").click(function () {
		var cat_input_name = $(this).attr("for");
		var cat_id = jQuery( "input[name="+cat_input_name+"]" ).attr("id");
		if( cat_id > 0 ) {
			location.href="./?cat_id="+cat_id;
		}		
	});
	
	function select_cat_path(cat_id) {
		
		if( cat_id > 0 && cat_paths[cat_id] != undefined ) {
			jQuery( "#selected_cat" ).empty();
			jQuery( "#selected_cat" ).html( "<b style=\"color:#000;\">Selected Category : </b>" +cat_paths[cat_id] );
		}
	}
});

</script>

</body>
</html>
