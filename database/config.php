<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();

$conn = mysqli_connect("localhost:3306", "seeproje_pkl", "Is@190804!", "seeproje_pkl");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function base_url($url = null) {
    $base_url = "http://seeproject.my.id/";
    
    if ($url) {
        return $base_url . ltrim($url, '/');
    }
    else {
        return $base_url;
    }
}

?>