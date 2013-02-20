<?php
/*Página Para la edición individual de una empresa*/
function wgo_edit_listing_page() {

	global $wpdb;
	$item = new ItemsClass();
	
	?>
	<div class="wrap">
		<h2>Editor de negocios</h2>
	<?php
	
	if($_POST['action']=="update_listing"){		
		
		$item->id = $_POST['id'];
		$item->name = $_POST['wgo_listing_name'];
		$item->logo_url = $_POST['wgo_listing_logo_url'];
		$item->description = $_POST['wgo_listing_description'];
		$item->phone = $_POST['wgo_listing_phone'];
		$item->movil = $_POST['wgo_listing_movil'];
		$item->fax = $_POST['wgo_listing_fax'];
		$item->url = $_POST['wgo_listing_url'];
		$item->email = $_POST['wgo_listing_email'];
		$item->address = $_POST['wgo_listing_address'];
		$item->city_id = $_POST['wgo_listing_cities_id'];
		$item->state = $_POST['wgo_listing_state'];
		$item->zip = $_POST['wgo_listing_zip'];
		$item->cat_id = $_POST['wgo_listing_cat_id'];
		$item->pkg_id = $_POST['wgo_listing_pkg_id'];		

		$init_pkg_id =  $_POST['initial_pkg_id'];
		
		if($pkg_id != $init_pkg_id){
			$item->time_listed = $_POST['initial_time_listed'];
	
			$durmonths = PackageClass::getPackageDuration($item->pkg_id);
			$item->time_expired = strtotime(date("Y-m-d",strtotime($durmonths)));
		}else{
			$item->time_listed = $_POST['initial_time_listed'];
			$item->time_expired = $_POST['initial_time_expired'];
		}		

		$item->update();	
	
	}else if($_POST['action']=="edit_listing"){		
		$item = ItemsClass::getBussinesItem($_POST['wgo_listing_id']);
	
	}else if($_POST['action']=="delete_listing"){
		ItemsClass::delete($_POST['wgo_listing_id']);
		?>
		<p>Empresa eliminada. <a href="?page=wgo_settings_listings">Ver todas las empresas</a></p></div>
		<?php
		return;
		
	}else{ 
		?>		
		<p>Empresa actualizada. <a href="?page=wgo_settings_listings">Ver todas las empresas</a></p></div>		
		<?php 
		return;
	} 

?>
		<h3>Editar empresa</h3>
		<p><a href="?page=wgo_settings_listings">Volver al listado</a></p>
		<?php 
		DrawItemEditable($item,"update_listing");
		?>	
		<form method='post' action='?page=wgo_settings_edit_images'>
			<input type='hidden' name='action' value='edit_images' />
			<input type='hidden' name='wgo_listing_id' value='<?php echo $item->id;?>' />
			<input type='submit' class='button-secondary' value='Add/Edit Images' />
		</form>
	</div>
<?php 
}
?>
