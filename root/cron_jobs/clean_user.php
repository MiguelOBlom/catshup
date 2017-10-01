<?php
//This script should run daily
include_once ("./../sql/db_connection.php");
$sql = "SELECT id, username FROM user WHERE signup<=CURRENT_DATE  - INTERVAL 7 DAY AND activated='0'";
$query = mysqli_query($db_connection, $sql);
$numrows = mysqli_num_rows($query);
if ($numrows>0){
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $id = $row['id'];
        $username = $row['username'];
        $userFolder = "./../public/user/files/$username";
        if (is_dir($userFolder)){
            rmdir($userFolder);
        }
        mysqli_query($db_connection, "DELETE FROM user WHERE id='$id' AND username='$username' AND activated='0' LIMIT 1");
        mysqli_query($db_connection, "DELETE FROM useroptions WHERE username='$username' LIMIT 1");
    }

}

?>