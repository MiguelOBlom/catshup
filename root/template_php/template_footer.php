<?php
$userHTML = '';
$sql = "SELECT COUNT(id) FROM user WHERE activated='1'";
$query = mysqli_query($db_connection, $sql);
$query_count = mysqli_fetch_row($query);
$user_count = $query_count[0];
if($user_count < 1){
    $userHTML = "There are no activated users yet.";
} else {
    $max = 10;
    $all_users = array();
    $sql = "SELECT username FROM user WHERE activated='1' ORDER BY RAND() LIMIT $max";
    $query = mysqli_query($db_connection, $sql);
    $user_view_all_link = "";
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        array_push($all_users, $row["username"]);
    }
    $userArrayCount = count($all_users);
    if($userArrayCount > $max){
        array_splice($all_users, $max);
    }
    if($user_count > $max){
        $user_view_all_link = '<a href="/catshup/root/public/view_users.php">view all</a>';
    }
    $orLogic = '';
    foreach($all_users as $key => $user){
        $orLogic .= "username='$user' OR ";
    }
    $orLogic = chop($orLogic, "OR ");
    $sql = "SELECT username, avatar FROM user WHERE $orLogic ORDER BY RAND()";
    $query = mysqli_query($db_connection, $sql);
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $user_username = $row["username"];
        $user_avatar = $row["avatar"];
        if($user_avatar != ""){
            $user_pic = '/catshup/root/public/user/files/'.$user_username.'/'.$user_avatar.'';
        } else {
            $user_pic = '/catshup/root/img/avatar.jpg';
        }
        $userHTML .= '<a href="/catshup/root/public/user/profile.php?u='.$user_username.'"><img class="userpics" src="'.$user_pic.'" alt="'.$user_username.'" title="'.$user_username.'"></a>';
    }
}
?>

<div id="Footer">
    <div id="FooterWrapper">
        <h3>De footer blablala</h3>
        <?php if(!isset($footerfriends)){ ?>
        <div id="userlist">
            <h3>Random Users</h3>
            <?php echo $userHTML; ?>
            <p><?php if($user_count > 0){
                    echo $user_view_all_link;
                } ?></p>
        </div>
        <?php };?>
    </div>
</div>