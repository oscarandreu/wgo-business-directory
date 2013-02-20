<?php

function wgo_categories_page() {
global $wpdb;
	if($_POST['action']=="add_category"){
		$name=mysql_real_escape_string(stripslashes($_POST['wgo_category_name']));
		$description=mysql_real_escape_string(stripslashes($_POST['wgo_category_description']));
		$wpdb->insert($wpdb->prefix.'wgo_categories',array('name'=>$name,'description'=>$description));
	}

	if($_POST['action']=="delete_category"){
		$id=$_POST['wgo_category_id'];
		$wpdb->query("DELETE FROM ".$wpdb->prefix."wgo_categories WHERE id=$id");
	}

?>

<div class="wrap">
<h2>Paid Business Listings Categories</h2>

<table class="pbladmin">
<tr class="headrow"><td>ID</td><td>Category Name</td><td width="300">Description</td><td>Shortcode</td><td>&nbsp;</td></tr>

<?php
global $wpdb;
$categories=$wpdb->get_results("SELECT id,name,description FROM ".$wpdb->prefix."wgo_categories");

foreach($categories as $cat){
	$id=$cat->id;
	$name=stripslashes($cat->name);
	$description=stripslashes($cat->description);
	
	echo "<tr class='datarow'><td>$id</td><td>$name</td><td>$description</td><td>[wgo-listings category=\"$id\"]</td><td><form method='post'><input type='hidden' name='action' value='delete_category' /><input type='hidden' name='wgo_category_id' value='$id' /><input type='submit' class='button-secondary' value='Delete Category' /></form></td></tr>";
}

?>

</table>

<h3>Crear una nueva categor�a</h3>
<form method="post"><input type="hidden" name="action" value="add_category" />
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><strong>Nombre de la categor�a</strong></th>
        <td><input type="text" name="wgo_category_name" value="" size="56" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><strong>Descripci�n</strong></th>
        <td><textarea name="wgo_category_description" cols="50" rows="2"></textarea></td>
        </tr>

    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="Create Category" />
    </p>

</form>
</div>
<?php }