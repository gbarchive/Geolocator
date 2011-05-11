<?php
    require_once "Geolibrary.php";  
    require_once "libraries/Maxmind_city.php";
    require_once "libraries/Geoplugin.php";
    require_once "libraries/Ipinfodb.php";

    class Geolocator {
    var $priority = array('maxmind_city',
                            'ipinfodb',
                            'geoplugin');

    var $classes = array();
    var $settings = array("case"=>array(
                                "getCountryCode"=>"strtoupper",
                                "*"=>"ucfirst" 
                            ), 
                            "checkAvailable"=>true, 
                            "cache"=>true,
                            "debug"=>false);

    function Geolocator($priority=null, $settings=null) {
        if($priority !== null) $this->priority = $priority;
        if($settings !== null) $this->settings = array_merge($this->settings, $settings);
   		
        foreach($this->priority as $option) {
            $this->_debug("Instantiating {$option}.");

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

        $this->_debug("Finding {$classMethod} for {$ip}.");

        foreach($this->priority as $choice) {
            if(!$this->settings['checkAvailable'] || $this->classes[$choice]->isAvailable()) {
                $this->_debug("{$choice} available.");

                // cache if required.
                if($this->settings['cache']) $this->classes[$choice]->cache($ip);

                $result = $this->classes[$choice]->$classMethod($ip);
                $this->_debug("Got response {$result}.");
                if($result !== false) {             

                    // handle autocasing
                    if(isset($this->settings['case'])) {
                        if(is_array($this->settings['case'])) {
                            if(isset($this->settings['case'][$classMethod])) {
                                $caseMethod = $this->settings['case'][$classMethod];
                            } else {
                                $caseMethod = $this->settings['case']['*'];
                            }
                        } else {    // if its not an array, assume its a method
                            $caseMethod = $this->settings['case'];
                        }
                    }

                    if(isset($caseMethod) && function_exists($caseMethod)) 
                        $result = $caseMethod($result);

                    $this->_debug("Returning {$result}.");
                    return $result;
                }
            } else {
                $this->_debug("Skipping {$choice}, unavailable.");
            }
        }
    }

    function _debug($message) {
        if($this->settings['debug']) echo "GEO> {$message}\n";
    }
  }
?>
