<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();

$conn = mysqli_connect("localhost", "root", "", "real_estate");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function base_url($url = null) {
    $base_url = "http://localhost/pkl/";
    
    if ($url) {
        return $base_url . ltrim($url, '/');
    }
    else {
        return $base_url;
    }
}

?>