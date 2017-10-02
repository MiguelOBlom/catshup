<?php
include_once ("./../../sql/db_connection.php");
include_once ("./../../template_php/checkloginstatus.php");
?>
<?php
$u="";
if(isset($_GET["u"])){
    $u = preg_replace('#[^a-z0-9]#i','', $_GET['u']);
} else {
    header("location: ./../index.php ");
    exit();
}
?>
<?php
$friendsHTML = '';
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$u' AND accepted='1' OR user2='$u' AND accepted='1'";
$query = mysqli_query($db_connection, $sql);
$query_count = mysqli_fetch_row($query);
$friend_count = $query_count[0];
if($friend_count < 1){
    $friendsHTML = $u." has no friends yet";
} else {
    $all_friends = array();
    $sql = "SELECT user1 FROM friends WHERE user2='$u' AND accepted='1' ORDER BY RAND()";
    $query = mysqli_query($db_connection, $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        array_push($all_friends, $row["user1"]);
    }
    $sql = "SELECT user2 FROM friends WHERE user1='$u' AND accepted='1' ORDER BY RAND()";
    $query = mysqli_query($db_connection, $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        array_push($all_friends, $row["user2"]);
    }
    $friendArrayCount = count($all_friends);
    $orLogic = '';
    foreach($all_friends as $key => $user){
        $orLogic .= "username='$user' OR ";
    }
    $orLogic = chop($orLogic, "OR ");
    $sql = "SELECT username, avatar FROM user WHERE $orLogic";
    $query = mysqli_query($db_connection, $sql);
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $friend_username = $row["username"];
        $friend_avatar = $row["avatar"];
        if($friend_avatar != ""){
            $friend_pic = 'files/'.$friend_username.'/'.$friend_avatar.'';
        } else {
            $friend_pic = './../../img/avatar.jpg';
        }
        $friendsHTML .= '<a href="profile.php?u='.$friend_username.'"><img class="friendpics" src="'.$friend_pic.'" alt="'.$friend_username.'" title="'.$friend_username.'"></a>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Catshup</title>
    <link rel="stylesheet" type="text/css" href="./../../css.css?v=1"/>
    <script src='./../../js/js.js'></script>
    <script src='./../../js/ajax.js'></script>
</head>
<body>
<?php include_once("./../../template_php/template_header.php")?>
<div id="Content">
    <div id="viewallfriends">
        <?php echo $friendsHTML; ?>
    </div>
</div>
<?php include_once("./../../template_php/template_footer.php")?>
</body>
</html>