<?php
session_start();
define('ROOTPATH', __DIR__);
include_once ($_SERVER['DOCUMENT_ROOT']."/catshup/root/sql/db_connection.php");

$user_ok = false;
$log_id = "";
$log_username = "";
$log_password = "";

function evalLoggedUser($dbcon, $id, $u, $p){
    $sql = "SELECT * FROM user WHERE id='$id' AND username='$u' AND password='$p' AND activated='1' LIMIT 1";
    $query = mysqli_query($dbcon, $sql);
    $numrows = mysqli_num_rows($query);
    if($numrows > 0){
        return true;
    }
}

if (isset($_SESSION["userid"]) && isset($_SESSION["username"]) && isset($_SESSION["password"])){
    $log_id = preg_replace('#[^0-9]#', '', $_SESSION['userid']);
    $log_username = preg_replace('#[^a-z0-9]#i', '', $_SESSION['username']);
    $log_password = preg_replace('#[^a-z0-9]#i', '', $_SESSION['password']);

    $user_ok = evalLoggedUser($db_connection,$log_id,$log_username,$log_password);
} else if (isset($_COOKIE["id"]) && isset($_COOKIE["user"]) && isset($_COOKIE["pass"])){
    $_SESSION["userid"] = preg_replace('#[^0-9]#', '', $_COOKIE["id"]);
    $_SESSION["username"] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE["user"]);
    $_SESSION["password"] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE["pass"]);
    $log_id = $_SESSION["userid"];
    $log_username = $_SESSION["username"];
    $log_password = $_SESSION["password"];

    $user_ok = evalLoggedUser($db_connection, $log_id,$log_username,$log_password);
    if ($user_ok === true){
        $sql = "UPDATE user SET lastlogin=now(), WHERE id='$log_id' LIMIT 1";
        $query = mysqli_query($db_connection, $sql);
    }    
    
    
}

?>