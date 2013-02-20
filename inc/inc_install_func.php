<?php
//DATABASE TABLE CREATION FUNCTIONS

global $wgo_db_version;
$wgo_db_version = "1.1";

function wgo_db_install() {
   global $wpdb;
   global $wgo_db_version;

   $table_name = $wpdb->prefix."wgo_listings";
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !=$table_name) {
      
      $sql = "CREATE TABLE " .$table_name. " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  name VARCHAR(55) NOT NULL,
	  logo_url VARCHAR(128) NOT NULL,
	  description text NOT NULL,
	  phone VARCHAR(20),
	  movil VARCHAR(20),
	  fax VARCHAR(20),
	  url VARCHAR(128),
	  email VARCHAR(64) NOT NULL,
	  address VARCHAR(64) NOT NULL,
	  city VARCHAR(32) NOT NULL,
	  state VARCHAR(64) NOT NULL,
	  zip VARCHAR(5) NOT NULL,
	  cat_id mediumint(9) NOT NULL,
	  pkg_id mediumint(9) NOT NULL,
	  time_listed bigint(11) DEFAULT '0' NOT NULL,
	  time_expired bigint(11) DEFAULT '0' NOT NULL,
	  active tinyint(1) DEFAULT '0' NOT NULL,
	  UNIQUE KEY id (id)
	);";

      	$wpdb->query($sql); 
		$wpdb->insert($wpdb->prefix.'wgo_listings',array('name'=>'Blazing Torch, Inc.','logo_url'=>'http://www.blazingtorch.com/wp-content/uploads/2012/01/blazing-torch-web-development.png','description'=>'Blazing Torch, Inc. develops custom web applications, specializing in WordPress plugin development and customization.','phone'=>'423-991-2143','url'=>'http://www.blazingtorch.com','email'=>'support@blazingtorch.com','address'=>'9010 Quail Mountain Drive','city'=>'Chattanooga','state'=>'Tennessee','zip'=>'37421','cat_id'=>1,'pkg_id'=>1,'time_listed'=>0,'time_expired'=>1609477199,'active'=>1));
   }

   $table_name = $wpdb->prefix."wgo_packages";
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'")!=$table_name) {
      
      $sql = "CREATE TABLE ".$table_name." (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  name VARCHAR(55) NOT NULL,
	  description text NOT NULL,
	  cost DECIMAL(6,2) NOT NULL,
	  duration INT(3) NOT NULL,
	  UNIQUE KEY id (id)
	);";

      	$wpdb->query($sql);
      	$wpdb->insert($wpdb->prefix.'wgo_packages',array('name'=>'Sample Package','description'=>'This is a sample package. You should edit it or delete it and create a new one. It is important that you remember to keep a package listed here at all times.','cost'=>'4.00','duration'=>'1'));
   }

   $table_name = $wpdb->prefix."wgo_categories";
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'")!=$table_name) {
      
      $sql = "CREATE TABLE ".$table_name." (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  name VARCHAR(55) NOT NULL,
	  description text NOT NULL,
	  UNIQUE KEY id (id)
	);";

		$wpdb->query($sql);
		$wpdb->insert($wpdb->prefix.'wgo_categories',array('name'=>'General','description'=>'General business listings.'));
 
      add_option("wgo_db_version",$wgo_db_version);
   }
      
   if(get_option('wgo_db_version')=="1.0"){
	   $table_name = $wpdb->prefix."wgo_listings";
	      
	      $sql = "ALTER TABLE " .$table_name. " MODIFY state VARCHAR(64)";
	
	      	$wpdb->query($sql); 
   
   	delete_option('wgo_db_version');
   	add_option("wgo_db_version",$wgo_db_version);
   }
}