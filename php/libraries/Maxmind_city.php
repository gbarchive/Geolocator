<?php
    require_once dirname(__FILE__) . "/../Geolibrary.php";
    if(!function_exists("geoip_open")) {
        require_once dirname(__FILE__) . "/maxmindapi/geoipcity.inc";
    }

    define("MAXMINDIPCITY_FILE", "");
    class Maxmind_city extends Geolibrary {
        function isAvailable() {
            return (file_exists(MAXMINDIPCITY_FILE));
        }

        function get($ip) {
          if(!$this->invalidIPCheck($ip)) return false;

          $geofile = geoip_open(MAXMINDIPCITY_FILE, GEOIP_STANDARD);
          $raw = geoip_record_by_addr($geofile, $ip);
          return get_object_vars($raw);
        }

        function _getEntry($ip, $entry) {
          $long_ip = ip2long($ip);
          if(isset($this->_cache[$long_ip]))
          {
            $data = $this->_cache[$long_ip];
          } else {
            $data = $this->get($ip);
          }

          return $data[$entry];
        }

        function getCity($ip) {
          return $this->_getEntry($ip, "city");
        }

        function getCountryCode($ip) {
          return $this->_getEntry($ip, "country_code");
        }
    
        function getCountryName($ip) {
          return $this->_getEntry($ip, "country_name");
        }

        function getRegion($ip) {
          global $GEOIP_REGION_NAME;
          return $GEOIP_REGION_NAME[$this->getCountryCode($ip)][$this->_getEntry($ip, "region")];
        }
    }

?>
