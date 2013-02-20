<?php

function wgo_listings_page() {
    global $wpdb;    

    $item = new ItemsClass();

    if ($_POST['action'] == "add_listing") {
        $item->name =stripslashes($_POST['wgo_listing_name']);
        $item->logo_url =stripslashes($_POST['wgo_listing_logo_url']);
        $item->description =stripslashes($_POST['wgo_listing_description']);
        $item->phone =stripslashes($_POST['wgo_listing_phone']);
        $item->movil =stripslashes($_POST['wgo_listing_movil']);
        $item->fax =stripslashes($_POST['wgo_listing_fax']);
        $item->url =stripslashes($_POST['wgo_listing_url']);
        $item->email =stripslashes($_POST['wgo_listing_email']);
        $item->address =stripslashes($_POST['wgo_listing_address']);
        $item->city_id =stripslashes($_POST['wgo_listing_cities_id']);
        $item->state =stripslashes($_POST['wgo_listing_state']);
        $item->zip =stripslashes($_POST['wgo_listing_zip']);
        $item->cat_id = $_POST['wgo_listing_cat_id'];
        $item->pkg_id = $_POST['wgo_listing_pkg_id'];
        $item->time_listed = time();
        $item->durmonths = PackageClass::getPackageDuration($item->pkg_id);
        $item->time_expired = strtotime($item->durmonths);
      $item->save();
    }

    if ($_POST['action'] == "delete_listing") {
        ItemsClass::deleteItem($_POST['wgo_listing_id']);
    }

    if ($_POST['action'] == "activate_listing") {
        ItemsClass::activateItem($_POST['wgo_listing_id']);
    }
    
    
    $registroInicial = isset($_GET['i']) ? $_GET['i'] : 0;
    $first = isset($_GET['first']) ? false : true;
    if ($first) {
    	$return .= wgo_get_find_function();
    }
    $show_map = get_option('wgo_use_map');
	$callBackFunction = $show_map ? 'buscar(true,\'frontEnd\')' : '';
	 
    $return .= CategoryClass::getCategoryList($cat, $callBackFunction);
    $return .= CityClass::getCityList($city, $callBackFunction);
    ?>

    <div class="wrap">
        <h2>Directorio de empresas</h2>

        <h2>Dar de alta una nueva empresa</h2>
        <p>Nota: Si ha dado de alta su empresa manualmente y ha escogido una opci&oacute;n de pago, no se proceder&aacute; a finalizar el alta, hasta que el pago haya sido realizado.</p>
        
        <?php DrawItemEditable($item, "add_listing");?>            
        
       <div id="panel_empresas">
       	<?php echo getAdminListingData($package, $cat, $city, $registroInicial, true); ?>
       </div>
       
    </div>
    <?php
}