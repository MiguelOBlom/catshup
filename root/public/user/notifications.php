<?php
include_once ("./../../sql/db_connection.php");
include_once ("./../../template_php/checkloginstatus.php");
// If the page requestor is not logged in, usher them away
if($user_ok != true || $log_username == ""){
    header("location: ./../index.php ");
    exit();
}
$notification_list = "";
$sql = "SELECT * FROM notifications WHERE username LIKE BINARY '$log_username' ORDER BY date_time DESC";
$query = mysqli_query($db_connection, $sql);
$numrows = mysqli_num_rows($query);
if($numrows < 1){
    $notification_list = "You do not have any notifications";
} else {
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $noteid = $row["id"];
        $initiator = $row["initiator"];
        $app = $row["app"];
        $note = $row["note"];
        $date_time = $row["date_time"];
        $date_time = strftime("%b %d, %Y", strtotime($date_time));
        $notification_list .= "<p><a href='profile.php?u=$initiator'>$initiator</a> | $app<br />$note</p>";
    }
}
mysqli_query($db_connection, "UPDATE user SET notescheck=now() WHERE username='$log_username' LIMIT 1");
?><?php
$friend_requests = "";
$sql = "SELECT * FROM friends WHERE user2='$log_username' AND accepted='0' ORDER BY datemade ASC";
$query = mysqli_query($db_connection, $sql);
$numrows = mysqli_num_rows($query);
if($numrows < 1){
    $friend_requests = 'No friend requests';
} else {
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $reqID = $row["id"];
        $user1 = $row["user1"];
        $datemade = $row["datemade"];
        $datemade = strftime("%B %d", strtotime($datemade));
        $thumbquery = mysqli_query($db_connection, "SELECT avatar FROM user WHERE username='$user1' LIMIT 1");
        $thumbrow = mysqli_fetch_row($thumbquery);
        $user1avatar = $thumbrow[0];
        $user1pic = '<img src="files/'.$user1.'/'.$user1avatar.'" alt="'.$user1.'" class="user_pic">';
        if($user1avatar == NULL){
            $user1pic = '<img src="./../../img/avatar.jpg" alt="'.$user1.'" class="user_pic">';
        }
        $friend_requests .= '<div id="friendreq_'.$reqID.'" class="friendrequests">';
        $friend_requests .= '<a href="profile.php?u='.$user1.'">'.$user1pic.'</a>';
        $friend_requests .= '<div class="user_info" id="user_info_'.$reqID.'">'.$datemade.' <a href="profile.php?u='.$user1.'">'.$user1.'</a> requests friendship<br /><br />';
        $friend_requests .= '<button onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">accept</button> or ';
        $friend_requests .= '<button onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">reject</button>';
        $friend_requests .= '</div>';
        $friend_requests .= '</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css?"/>
    <script src="/catshup/root/js/js.js"></script>
    <script src="/catshup/root/js/ajax.js"></script>
    <script src="/catshup/root/js/friendsblockers.js"></script>
    <script src="/catshup/root/js/friendrequest.js"></script>
</head>
<body>
<?php include_once("./../../template_php/template_header.php")?>
<div id="Content">
    <!-- START Page Content -->
    <div id="notesBox"><h2>Notifications</h2><?php echo $notification_list; ?></div>
    <div id="friendReqBox"><h2>Friend Requests</h2><?php echo $friend_requests; ?></div>
    <div style="clear:left;"></div>
    <!-- END Page Content -->
</div>
<?php include_once("./../../template_php/template_footer.php")?>
</body>
</html>
