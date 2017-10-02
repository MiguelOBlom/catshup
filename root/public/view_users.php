<?php
$footerfriends = false;
?>
<?php
include_once ("./../sql/db_connection.php");
include_once ("./../template_php/checkloginstatus.php");
$userHTML = '';
$sql = "SELECT COUNT(id) FROM user WHERE activated='1'";
$query = mysqli_query($db_connection, $sql);
$query_count = mysqli_fetch_row($query);
$user_count = $query_count[0];
if($user_count < 1){
    $userHTML = "There are no activated users yet.";
} else {
    $userHTML = "";
    $all_users = array();
    $sql = "SELECT username FROM user WHERE activated='1' ORDER BY RAND()";
    $query = mysqli_query($db_connection, $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        array_push($all_users, $row["username"]);
    }
    $userArrayCount = count($all_users);
    $orLogic = '';
    foreach ($all_users as $key => $user) {
        $orLogic .= "username='$user' OR ";
    }
    $orLogic = chop($orLogic, "OR ");
    $sql = "SELECT username, avatar FROM user WHERE $orLogic";
    $query = mysqli_query($db_connection, $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $user_username = $row["username"];
        $user_avatar = $row["avatar"];
        if ($user_avatar != "") {
            $user_pic = '/catshup/root/public/user/files/' . $user_username . '/' . $user_avatar . '';
        } else {
            $user_pic = '/catshup/root/img/avatar.jpg';
        }
        $userHTML .= '<a href="/catshup/root/public/user/profile.php?u=' . $user_username . '"><img class="userpics" src="' . $user_pic . '" alt="' . $user_username . '" title="' . $user_username . '"></a>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Catshup</title>
    <link rel="stylesheet" type="text/css" href="./../css.css?v=1"/>
    <script src='./../js/js.js'></script>
    <script src='./../js/ajax.js'></script>
</head>
<body>
<?php include_once("./../template_php/template_header.php")?>
<div id="Content">
    <div id="viewallusers">
        <?php echo $userHTML; ?>
    </div>
</div>
<?php include_once("./../template_php/template_footer.php")?>
</body>
</html>