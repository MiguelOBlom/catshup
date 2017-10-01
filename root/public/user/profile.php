<?php
    include_once ("./../../sql/db_connection.php");
    include_once ("./../../template_php/checkloginstatus.php");
    $u = "";
    $userlevel = "";
    $joindate = "";
    $lastsession = "";
    $isOwner = false;
    $admin = false;

    if(isset($_GET["u"])){
        $u = preg_replace('#[^a-z0-9]#i','', $_GET['u']);
    } else {
        header("location: ./../index.php ");
        exit();
    }
    $sql = "SELECT * FROM user WHERE username='$u' AND activated='1' LIMIT 1";
    $uquery = mysqli_query($db_connection,$sql);
    $numrows = mysqli_num_rows($uquery);
    if($numrows < 1){
        echo "That user is not a registered and activated user in our system";
        exit();
    }

    if($u === $log_username && $user_ok === true){
        $isOwner = true;
    }

    while($row = mysqli_fetch_array($uquery, MYSQLI_ASSOC)){
        $profile_id = $row["id"];
        $userlevel = $row["userlevel"];
        $signup = $row["signup"];
        $lastlogin = $row["lastlogin"];
        $joindate = strftime("%b, %d, %Y", strtotime($signup));
        $lastsession = strftime("%b, %d, %Y", strtotime($lastlogin));
    }

    if($userlevel === 'd' && $isOwner){
        $admin = true;
    }
?>
<?php
$isFriend = false;
$ownerBlockViewer = false;
$viewerBlockOwner = false;
if($u != $log_username && $user_ok == true){
    $friend_check = "SELECT id FROM friends WHERE user1='$log_username' AND user2='$u' AND accepted='1' OR user1='$u' AND user2='$log_username' AND accepted='1' LIMIT 1";
    if(mysqli_num_rows(mysqli_query($db_connection, $friend_check)) > 0){
        $isFriend = true;
    }
    $block_check1 = "SELECT id FROM blockedusers WHERE blocker='$u' AND blockee='$log_username' LIMIT 1";
    if(mysqli_num_rows(mysqli_query($db_connection, $block_check1)) > 0){
        $ownerBlockViewer = true;
    }
    $block_check2 = "SELECT id FROM blockedusers WHERE blocker='$log_username' AND blockee='$u' LIMIT 1";
    if(mysqli_num_rows(mysqli_query($db_connection, $block_check2)) > 0){
        $viewerBlockOwner = true;
    }
}
?><?php
$friend_button = '<button disabled>Request As Friend</button>';
$block_button = '<button disabled>Block User</button>';
// LOGIC FOR FRIEND BUTTON
if($isFriend == true){
    $friend_button = '<button onclick="friendToggle(\'unfriend\',\''.$u.'\',\'friendButton\')">Unfriend</button>';
} else if($user_ok == true && $u != $log_username && $ownerBlockViewer == false){
    $friend_button = '<button onclick="friendToggle(\'friend\',\''.$u.'\',\'friendButton\')">Request As Friend</button>';
}
// LOGIC FOR BLOCK BUTTON
if($viewerBlockOwner == true){
    $block_button = '<button onclick="blockToggle(\'unblock\',\''.$u.'\',\'blockButton\')">Unblock User</button>';
} else if($user_ok == true && $u != $log_username){
    $block_button = '<button onclick="blockToggle(\'block\',\''.$u.'\',\'blockButton\')">Block User</button>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css"/>
    <script src="/catshup/root/js/js.js"></script>
    <script src="/catshup/root/js/ajax.js"></script>
    <script src="/catshup/root/js/friendsblockers.js"></script>
</head>
<body>
    <?php include_once("./../../template_php/template_header.php")?>
    <div id="Content">
        <h3>
            <?php
                if($isOwner){
                    echo "Your profile";
                } else {
                    echo $u;
                }
            ?>
        </h3>
        <p>Userlevel: <?php echo $userlevel;?></p>
        <p>Join date: <?php echo $joindate;?></p>
        <p>Last session: <?php echo $lastsession;?></p>
        <?php
        if ($admin){
            echo '<a href="./../../admin/panel.php">Admin pagina</a>';
        }
        if ($isOwner){
            echo "<a href='./settings.php'>Settings</a>";
        }
        ?>
        <p>Friend Button: <span id="friendButton"><?php echo $friend_button; ?></span></p>
        <p>Block Button: <span id="blockButton"><?php echo $block_button; ?></span></p>
    </div>
    <?php include_once("./../../template_php/template_footer.php")?>
</body>
</html>
