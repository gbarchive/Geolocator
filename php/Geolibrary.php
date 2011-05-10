<?php
    class Geolibrary {
      var $_cache;

      function isAvailable() {
        return false;   // unimplemented classes aren't available.
      }

      // loads the data for $ip in to an array and returns it.
      function get($ip) {

      }

      // stores the data internal to class
      function cache($ip) {
        if(!$this->invalidIPCheck($ip)) return false;

        $data = get($ip);
        $this->_cache[ip2long($ip)]=$data;
        return $data;
      }

      // gets the data if the ip doesn't exist in the cache, returns the city.
      function getCity($ip) { trigger_error('Geolibrary getCity not defined.'); }
      function getCountryName($ip) { trigger_error('Geolibrary getCountryName not defined.'); }
      function getCountryCode($ip) { trigger_error('Geolibrary getCountryCode not defined.'); }
      function getRegion($ip) { trigger_error('Geolibrary getRegion not defined.'); }

      function invalidIPCheck($ip) {
        if(!$this->_checkIP($ip)) {
          trigger_error('Geolibrary class error: Geolibrary::cache was passed something that isn\'t an IP.', E_USER_ERROR);
          return false;
        }
        return true;
      }

      function _checkIP($ip) {
        $ip = explode('.', $ip);
        if(count($ip) != 4) return false;
        foreach($ip as $item) { if(!is_numeric($item) || $item > 255 || $item < 0) return false; }
        return true;
      }

      function fetchURL($url, $userAgent="Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)") {
        if(function_exists('curl_init')) {
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
          $response = curl_exec($ch);
          curl_close($ch);

          return $response; // . 
        } else if ( ini_get('allow_url_fopen') ) {
          return file_get_contents($url, 'r');
        } else {
          trigger_error('Geolibrary class error: PHP does not have cURL support and allow_url_fopen is set to off.', E_USER_ERROR);
          return false;
        }
      }

    }

?>
