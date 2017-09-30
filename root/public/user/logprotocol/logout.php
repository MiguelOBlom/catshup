<?php
session_start();
$_SESSION = array();
if(isset($_COOKIE["id"]) && isset($_COOKIE["user"]) && isset($_COOKIE["pass"])){
    unset($_COOKIE["id"]);
    unset($_COOKIE["user"]);
    unset($_COOKIE["pass"]);
    setcookie("id",null, strtotime('-5 days'), '/');
    setcookie("user",null, strtotime('-5 days'), '/');
    setcookie("pass",null, strtotime('-5 days'), '/');
};

session_destroy();

if(isset($_SESSION['username'])){
    echo "<script>alert('".$_SESSION['username']."')</script>";
    header("location: message.php?msg=ERROR:_Logout_Failed");
} else {
    echo "<script>alert('".$_SESSION['username']."')</script>";
    header("location: ./../../index.php");
}
?>