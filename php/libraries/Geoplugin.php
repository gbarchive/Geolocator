<?php
    require_once dirname(__FILE__) . "/../Geolibrary.php";
    class Geoplugin extends Geolibrary {
        function isAvailable() {
          return $this->checkOnline("www.geoplugin.net"); // later fail if geoplugin.net is offline.
        }

        function get($ip) {
          if(!$this->invalidIPCheck($ip)) return false;

          $url = "http://www.geoplugin.net/php.gp?ip=" . urlencode($ip);
          $raw = $this->fetchURL($url);
          return unserialize($raw);
        }

        function _getEntry($ip, $entry) {
          $long_ip = ip2long($ip);
          if(isset($this->_cache[$long_ip]))
          {
            $data = $this->_cache[$long_ip];
          } else {
            $data = $this->get($ip);
          }

          $result = $data['geoplugin_' . $entry];
          if(!isset($result) || $result == "") return false;
          return $result;
        }

        function getCity($ip) {
          return $this->_getEntry($ip, "city");
        }

        function getCountryCode($ip) {
          return $this->_getEntry($ip, "countryCode");
        }
    
        function getCountryName($ip) {
          return $this->_getEntry($ip, "countryName");
        }

        function getRegion($ip) {
          return $this->_getEntry($ip, "region");
        }
    }

?>
