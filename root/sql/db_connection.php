<?php

$host = "localhost";
$user = "root";
$pass = "";
$database = "huiswerk";

$db_connection = mysqli_connect( $host, $user, $pass, $database );

if (mysqli_connect_errno()){
    if (mysqli_connect_errno($db_connection) == 1049 ){
        $conn = mysqli_connect($host, $user,$pass);
        $sql = "CREATE DATABASE ".$database;
        $query = mysqli_query($conn,$sql);
    } else {
        echo mysqli_connect_error();
    }
    exit;
}
?>