<?php

//ADMIN MENU INTERFACES

add_action('admin_menu','wgo_menu');

function wgo_menu() {
        
    add_menu_page('Directorio','Directorio','edit_posts','wgo_settings','wgo_settings_page',WGO_BD_IMAGES.'shopping Full.png');
	add_submenu_page('wgo_settings','Modalidades','Modalidades','edit_posts','wgo_settings_packages','wgo_packages_page');
	add_submenu_page('wgo_settings','Categorías','Categorías','edit_posts','wgo_settings_categories','wgo_categories_page');
	add_submenu_page('wgo_settings','Listado de anuncios','Listado de anuncios','edit_posts','wgo_settings_listings','wgo_listings_page');
	add_submenu_page('','Edit Listings','Edit Listings','edit_posts','wgo_settings_edit_listings','wgo_edit_listing_page');
	add_submenu_page('','Edit Images','Edit Images','edit_posts','wgo_settings_edit_images','wgo_edit_images_page');

	//call register settings function
	add_action('admin_init','register_wgo_settings');
}
