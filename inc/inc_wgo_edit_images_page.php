<?php

function wgo_edit_images_page() {

global $wpdb;
$empresaid = $_POST[wgo_listing_id];

if ($_POST['action'] == "add_image") {
	//Add new image
	$path = mysql_real_escape_string(stripslashes($_POST['wgo_image_url']));
	$alt_text = mysql_real_escape_string(stripslashes($_POST['wgo_image_description']));
	
	//Check if the image is actually new, with a reload the same information can be submitted multiple times
	//Only add the data if the record does not already exist
	//Only add the data if the path has a value
	$check_image = $wpdb->get_results("SELECT id FROM " . $wpdb->prefix . "wgo_images WHERE path='$path' AND alt_text = '$alt_text'" );
	if ((!$check_image[0]->id) && ($path != "")) {
		$wpdb->query("INSERT INTO " . $wpdb->prefix . "wgo_images (id_listing, path, alt_text) VALUES ($empresaid, '$path', '$alt_text')");
	}
}

if ($_POST['action'] == "delete_image") {
	//TODO: Ask for confirmation before deleting the image
	$id = mysql_real_escape_string(stripslashes($_POST['wgo_image_id']));
	$wpdb->query("DELETE FROM " . $wpdb->prefix . "wgo_images WHERE id=$id");
}

if ($_POST['action'] == "edit_image") {
	//Pre fill values in form
	$id = mysql_real_escape_string(stripslashes($_POST['wgo_image_id']));
	$selected_image = $wpdb->get_results("SELECT path, alt_text FROM " . $wpdb->prefix . "wgo_images WHERE id=" . $id);
	$selected_image_url = $selected_image[0]->path;
	$selected_image_alt_text = $selected_image[0]->alt_text;
}

if ($_POST['action'] == "update_image") {
	//Update image data with new values
	//Only update the data if the (updated) path has a value
	$id = mysql_real_escape_string(stripslashes($_POST['wgo_image_id']));
	$path = mysql_real_escape_string(stripslashes($_POST['wgo_image_url']));
	$alt_text = mysql_real_escape_string(stripslashes($_POST['wgo_image_description']));
	if ($path != "") {
		$wpdb->update($wpdb->prefix . "wgo_images", array('path' => $path, 'alt_text' => $alt_text), array('id' => $id));
	}
}

$listing = $wpdb->get_row("SELECT name, logo_url FROM " . $wpdb->prefix . "wgo_listings WHERE id='$empresaid'");
$name = stripslashes($listing->name);
$logo = stripslashes($listing->logo_url);
?>
	
<script type="text/javascript">	<!--
		jQuery(document).ready(function() {
			var uploadID = '';

			jQuery('#upload_image_button').click(function() {
				uploadID = jQuery(this).prev('input');
				formfield = jQuery('#wgo_listing_logo_url').attr('name');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				return false;
			});
			 
			window.send_to_editor = function(html) {
				imgurl = jQuery('img',html).attr('src');
				uploadID.val(imgurl);
				tb_remove();
			}

		});
	--></script>

<div class="wrap">
<h2>Paid Business Listings <?=$name?></h2>
<?php if (!empty($logo)) { ?>
	<img src="<?=$logo?>" class="wgo_image_preview" />
<?php } ?>

<h2>Paid Business Listings Images</h2>

<table class="pbladmin">
	<tr class="headrow">
		<td></td>
		<td>URL</td>
		<td width="300">Description</td>
		<td></td>
		<td></td>
	</tr>

	<?php
	global $wpdb;
	$images = $wpdb->get_results("SELECT id, path, alt_text FROM " . $wpdb->prefix . "wgo_images WHERE id_listing=" . $empresaid);
	$imagecount = 0;
	foreach($images as $img){
		$id = stripslashes($img->id);
		$path = stripslashes($img->path);
		$alt_text = stripslashes($img->alt_text);
		echo "<tr class='datarow'>
				<td><img src='$path' class='wgo_image_preview_small' /></td>
				<td>$path</td>
				<td>$alt_text</td>
				<td><form method='post'>
					<input type='hidden' name='action' value='delete_image' />
					<input type='hidden' name='wgo_image_id' value='$id' />
					<input type='hidden' name='wgo_listing_id' value='$empresaid' />
					<input type='submit' class='button-secondary' value='Delete Image' /></form>
				</td>
				<td><form method='post'>
					<input type='hidden' name='action' value='edit_image' />
					<input type='hidden' name='wgo_image_id' value='$id' />
					<input type='hidden' name='wgo_listing_id' value='$empresaid' />
					<input type='submit' class='button-secondary' value='Edit Image' /></form>
				</td>
			</tr>";
		$imagecount += 1;
	}
	?>

</table>

<?php 
$item = new ItemsClass();
if ($_POST['action'] == "edit_image") { ?>

<h3>Update imagen</h3>

<form method="post"><input type="hidden" name="action" value="update_image" />
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><strong>Image</strong></th>
			<td>
				<img src='<?=$selected_image_url?>' class='wgo_image_preview_small' />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><strong>URL</strong></th>
			<td>
				<input type="text" name="wgo_image_url" value="<?=$selected_image_url?>" size="56" />
				<input id="upload_image_button" type="button" value="Seleccione imagen" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><strong>Descripción</strong></th>
			<td>
				<textarea name="wgo_image_description" cols="50" rows="2"><?=$selected_image_alt_text?></textarea>
			</td>
		</tr>
	</table>
	<input type="hidden" name="wgo_listing_id" value="<?=$empresaid?>" />
	<input type="hidden" name="wgo_image_id" value="<?=$id?>" />
	<p class="submit">
		<input type="submit" class="button-primary" value="Update Image" />
		<input class="button-secondary" type="submit" value="Cancel update" onclick="window.history.back();">
	</p>
</form>


<?php } elseif ($imagecount < $item->getAllowedImagesCount()) { 
	//Not an update so show the add form
	//Only show add image interface if the maximum number of images has not been reached.
?>

<h3>Añadir imagen nuevo</h3>
<form method="post"><input type="hidden" name="action" value="add_image" />
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><strong>URL</strong></th>
			<td>
				<input type="text" name="wgo_image_url" value="" size="56" />
				<input id="upload_image_button" type="button" value="Seleccione imagen" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><strong>Descripción</strong></th>
			<td>
				<textarea name="wgo_image_description" cols="50" rows="2"></textarea>
			</td>
		</tr>
	</table>
	<input type="hidden" name="wgo_listing_id" value="<?=$empresaid?>" />
	<p class="submit">
		<input type="submit" class="button-primary" value="Add Image" />
	</p>
</form>

<?php } //End if ?>

</div>

<form action="?page=wgo_settings_listings" method="post">
<input class="button-secondary" type="submit" value="Cancel">
</form>


<?php }