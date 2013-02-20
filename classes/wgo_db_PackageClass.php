<?php

/**
 * Description of newPHPClass
 *
 * @author Diario
 */
class PackageClass {

	public $id;
	public $name;
	public $cost;
	public $duration;

	public static function getPackageName($id) {
		global $wpdb;

		$id = mysql_real_escape_string($id);

		$package = $wpdb->get_row("SELECT name FROM " . $wpdb->prefix . "wgo_packages WHERE id = $id");
		if ($package->name == "") {
			$lResult = "--";
		} else {
			$lResult = $package->name;
		}
		return $lResult;
	}

	public static function getPackageDuration($id) {
		global $wpdb;

		$id = mysql_real_escape_string($id);

		$package = $wpdb->get_row("SELECT duration FROM " . $wpdb->prefix . "wgo_packages WHERE id = $id");
		if ($package->duration == 0) {
			$lResult.=0;
		} else {
			if ($package->duration == 1) {
				$lResult.="+1 month";
			} else {
				$lResult.="+" . $package->duration . " months";
			}
		}
		return $lResult;
	}

	public static function getPackageList($selected = "") {
		global $wpdb;
		$wgo_package_currency = get_option('wgo_ppcurrency');
		$packages = $wpdb->get_results("SELECT id,name,cost,duration FROM " . $wpdb->prefix . "wgo_packages");
		if (count($packages) == 0) {
			$lResult.="No packages are currently set up.";
		} elseif (count($packages) == 1) {
			foreach ($packages as $pkg) {
				$id = $pkg->id;
				$name = stripslashes($pkg->name);
				$cost = stripslashes($pkg->cost);
				$duration = stripslashes($pkg->duration);
				if ($duration == 1) {
					$monthtense = "month";
				} else {
					$monthtense = "months";
				}
				$lResult.="<em>$name - $cost $wgo_package_currency/$duration $monthtense</em><input type='hidden' name='wgo_listing_pkg_id' value='$id' />";
			}
		} else {
			$multi_pkg_display = "<select name='wgo_listing_pkg_id'>";
			$multi_pkg_display.="<option value=''>Seleccione una modalidad de anuncio</option>";
			foreach ($packages as $pkg) {
				$id = $pkg->id;
				$name = stripslashes($pkg->name);
				$cost = stripslashes($pkg->cost);
				$duration = stripslashes($pkg->duration);
				if ($duration == 1) {
					$monthtense = "mes";
				} else {
					$monthtense = "meses";
				}
				if ($id == $selected) {
					$sel = "selected ";
				} else {
					$sel = "";
				}
				$multi_pkg_display.="<option value='$id' $sel>$name - $cost $wgo_package_currency/$duration $monthtense</option>";
			}
			$multi_pkg_display.="</select>";

			$lResult.=$multi_pkg_display;
		}
		return $lResult;
	}

}
?>
