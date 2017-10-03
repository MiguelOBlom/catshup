<?php
    include_once("./../sql/db_connection.php");
    include_once("./checkloginstatus.php");
    if($user_ok != true || $log_username == "") {
        exit();
    }
?>
<?php
if (isset($_FILES["avatar"]["name"]) && $_FILES["avatar"]["tmp_name"] != ""){
    $fileName = $_FILES["avatar"]["name"];
    $fileTmpLoc = $_FILES["avatar"]["tmp_name"];
    $fileType = $_FILES["avatar"]["type"];
    $fileSize = $_FILES["avatar"]["size"];
    $fileErrorMsg = $_FILES["avatar"]["error"];
    $kaboom = explode(".", $fileName);
    $fileExt = end($kaboom);
    list($width, $height) = getimagesize($fileTmpLoc);
    if($width < 10 || $height < 10){
        header("location: ./../public/user/logprotocol/message.php?msg=ERROR: That image has no dimensions");
        exit();
    }
    $db_file_name = "avatar".time().".".$fileExt;
    if($fileSize > 1048576) {
        header("location: ./../public/user/logprotocol/message.php?msg=ERROR: Your image file was larger than 1mb");
        exit();
    } else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
        header("location: ./../public/user/logprotocol/message.php?msg=ERROR: Your image file was not jpg, gif or png type");
        exit();
    } else if ($fileErrorMsg == 1) {
        header("location: ./../public/user/logprotocol/message.php?msg=ERROR: An unknown error occurred");
        exit();
    }
    $sql = "SELECT avatar FROM user WHERE username='$log_username' LIMIT 1";
    $query = mysqli_query($db_connection, $sql);
    $row = mysqli_fetch_row($query);
    $avatar = $row[0];
    if($avatar != ""){
        $picurl = "./../public/user/files/$log_username/$avatar";
        if (file_exists($picurl)) { unlink($picurl); }
    }
    $moveResult = move_uploaded_file($fileTmpLoc, "./../public/user/files/$log_username/$db_file_name");
    if ($moveResult != true) {
        header("location: ./../public/user/logprotocol/message.php?msg=ERROR: File upload failed");
        exit();
    }
    include_once("./image_resize.php");
    $target_file = "./../public/user/files/$log_username/$db_file_name";
    $resized_file = "./../public/user/files/$log_username/$db_file_name";
    $wmax = 200;
    $hmax = 300;
    img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
    $sql = "UPDATE user SET avatar='$db_file_name' WHERE username='$log_username' LIMIT 1";
    $query = mysqli_query($db_connection, $sql);
    mysqli_close($db_connection);
    header("location: ./../public/user/profile.php?u=$log_username");
    exit();
}
?>