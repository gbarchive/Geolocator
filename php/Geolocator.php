<?php
/* 
vim: tabstop=2:softtabstop=2:shiftwidth=2:noexpandtab 
*/

	// should support standardizing case.

	require_once "Geolibrary.php";
	require_once "libraries/Maxmind_city.php";
	require_once "libraries/Geoplugin.php";
	require_once "libraries/Ipinfodb.php";

  class Geolocator {
    var $priority = array('maxmind_city', 
                          'geoplugin',
													'ipinfodb');

		var $classes = array();
		function Geolocator($priority=null) {
			if($priority !== null) $this->priority = $priority;
			foreach($this->priority as $option) {
				$className = ucfirst($option);
				$this->classes[$option] = new $className();
			}
		}

		function getCity($ip=null) {
			if($ip === null) $ip = $_SERVER['REMOTE_ADDR'];
	
			foreach($this->priority as $choice) {
				if($this->classes[$choice]->isAvailable()) {
					$result = $this->classes[$choice]->getCity($ip);
					if($result !== false) return $result;
				}
			}			
		}
  }

	
	$geoloc = new Geolocator();
	var_dump($geoloc->getCity("174.4.82.113"));
?>
