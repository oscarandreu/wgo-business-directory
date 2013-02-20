<?php

/*
 * �scar Andreu Mart�nez
 * 
 */
 global $wpdb;
 
if (!function_exists('add_action'))
   require_once(__dir__.'/../../../../wp-config.php');

$registroInicial = isset($_GET['i']) ? $_GET['i'] : 0;

if($_GET['p'] == 'frontEnd')
{
	$return .= doQuery($_GET['package'], $_GET['cat'], $_GET['city'], $registroInicial);
}else{
	$return .= getAdminListingData($_GET['package'], $_GET['cat'], $_GET['city'], $registroInicial);
}

echo $return;
?>
