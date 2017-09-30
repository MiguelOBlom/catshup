<?php
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

    if($userlevel === 'd'){
        $admin = true;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css"/>
    <script src="/catshup/root/js/js.js"></script>
    <script src="/catshup/root/js/ajax.js"></script>
</head>
<body>
    <?php include_once("./../../template_php/template_header.php")?>
    <div id="Content">
        <h3><?php echo $u;?></h3>
        <p><?php echo $isOwner;?></p>
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
    </div>
    <?php include_once("./../../template_php/template_footer.php")?>
</body>
</html>
