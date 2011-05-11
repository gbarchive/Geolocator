<?php
    include "Geolocator.php";

    $geolocator = new Geolocator(null, array("debug"=>true));

    echo $geolocator->getCity("74.125.155.99") . "\n";
    echo $geolocator->getCountryCode("74.125.155.99") . "\n";
