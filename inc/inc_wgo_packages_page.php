<?php

function wgo_packages_page() {
global $wpdb;

	if($_POST['action']=="add_package"){
		$name=mysql_real_escape_string(stripslashes($_POST['wgo_package_name']));
		$description=mysql_real_escape_string(stripslashes($_POST['wgo_package_description']));
		if($_POST['wgo_package_cost']==""){$cost=0;}else{$cost=mysql_real_escape_string(stripslashes($_POST['wgo_package_cost']));}
		$duration=mysql_real_escape_string(stripslashes($_POST['wgo_package_duration']));
		$wpdb->insert($wpdb->prefix.'wgo_packages',array('name'=>$name,'description'=>$description,'cost'=>$cost,'duration'=>$duration));
	}

	if($_POST['action']=="delete_package"){
		$id=$_POST['wgo_package_id'];
		$wpdb->query("DELETE FROM ".$wpdb->prefix."wgo_packages WHERE id=$id");
	}
?>

<div class="wrap">
<h2>Paid Business Listings Packages</h2>

<table class="pbladmin">
<tr class="headrow"><td>Package ID</td><td>Package Name</td><td>Cost</td><td>Duration</td><td width="300">Description</td><td>Shortcode</td><td>&nbsp;</td></tr>

<?php
global $wpdb;
$packages=$wpdb->get_results("SELECT id,name,description,cost,duration FROM ".$wpdb->prefix."wgo_packages");

foreach($packages as $pkg){
	$id=$pkg->id;
	$name=stripslashes($pkg->name);
	$description=stripslashes($pkg->description);
	$cost=$pkg->cost;
	$duration=$pkg->duration;
	
	echo "<tr class='datarow'><td>$id</td><td>$name</td><td>$cost</td><td>$duration</td><td>$description</td><td>[wgo-listings package=\"$id\"]</td><td><form method='post'><input type='hidden' name='action' value='delete_package' /><input type='hidden' name='wgo_package_id' value='$id' /><input type='submit' class='button-secondary' value='Delete Package' /></form></td></tr>";
}

?>

</table>

<h3>Create A New Listings Package</h3>
<form method="post"><input type="hidden" name="action" value="add_package" />
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><strong>Package Name</strong></th>
        <td><input type="text" name="wgo_package_name" value="" size="56" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><strong>Package Description</strong></th>
        <td><textarea name="wgo_package_description" cols="50" rows="2"></textarea></td>
        </tr>

        <tr valign="top">
        <th scope="row"><strong>Package Cost</strong></th>
        <td><input type="text" name="wgo_package_cost" value="" size="4" /> <strong><?php echo get_option('wgo_ppcurrency'); ?></strong> <em>(to change currencies, go to the <a href="?page=wgo_settings">General Settings</a> page)</em></td></tr>
        <tr><td>&nbsp;</td><td><em>(Please only use numbers and decimals, no currency characters. Cost per number of months listed as "duration")</em></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><strong>Package Duration</strong></th>
        <td><input type="text" name="wgo_package_duration" value="" size="2" /> <strong>Months</strong></td>
        </tr>

    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="Create Package" />
    </p>

</form>
</div>
<?php }