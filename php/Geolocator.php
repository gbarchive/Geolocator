<?php
  require_once "Geolibrary.php";
  require_once "libraries/Maxmind_city.php";
  require_once "libraries/Geoplugin.php";
  require_once "libraries/Ipinfodb.php";

  class Geolocator {
    var $priority = array('maxmind_city', 
                          'geoplugin',
                          'ipinfodb');

  	var $classes = array();
    var $settings = array("case"=>"ucfirst");
   	function Geolocator($priority=null, $settings=null) {
   		if($priority !== null) $this->priority = $priority;
        if($settings !== null) $this->settings = $settings;

   		foreach($this->priority as $option) {
   			$className = ucfirst($option);
   			$this->classes[$option] = new $className();
   		}
   	}

   	function getCity($ip=null) { return $this->get($ip, "getCity");  }
    function getCountryCode($ip=null) { return $this->get($ip, "getCountryCode"); }
    function getCountryName($ip=null) { return $this->get($ip, "getCountryName"); }
    function getRegion($ip=null) { return $this->get($ip, "getRegion"); }

    function get($ip, $classMethod = "getCity") {
        if($ip === null) $ip = $_SERVER['REMOTE_ADDR'];

        foreach($this->priority as $choice) {
            if($this->classes[$choice]->isAvailable()) {
                $result = $this->classes[$choice]->$classMethod($ip);
                if($result !== false) {
                    if($this->settings['case'] != "") $result = $this->settings['case']($result);
                    return $result;
                }
            }
        }
    }
  }
?>
