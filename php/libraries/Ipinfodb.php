<?php
    define("IPINFODB_API_KEY", "78a12aef5a6ad02c2f618b89c004a64785df1333228c4715ab3c907e3b2bbbf8"); // you must get an API key from http://www.ipinfodb.com/ip_location_api.php

    require_once dirname(__FILE__) . "/../Geolibrary.php";
    class Ipinfodb extends Geolibrary {
        function isAvailable() {
          return (strlen(IPINFODB_API_KEY) > 1) && ($this->checkOnline("api.ipinfodb.com"));
        }

        function get($ip) {
          if(!$this->invalidIPCheck($ip)) return false;

          $url = "http://api.ipinfodb.com/v3/ip-city/?key=" . IPINFODB_API_KEY . "&format=raw&ip=" . urlencode($ip . "");
          $raw = $this->fetchURL($url);
          return explode(";", $raw);

        }

        function _getEntry($ip, $entry) {
          $long_ip = ip2long($ip);
          if(isset($this->_cache[$long_ip]))
          {
            $data = $this->_cache[$long_ip];
          } else {
            $data = $this->get($ip);
          }

          $result = $data[$entry];
          if(!is_string($result) || $result == "") return false;
          return $result;
        }

        function getCity($ip) {
          return $this->_getEntry($ip, 6);
        }

        function getCountryCode($ip) {
          return $this->_getEntry($ip, 3);
        }
    
        function getCountryName($ip) {
          return $this->_getEntry($ip, 4);
        }
    }

?>
