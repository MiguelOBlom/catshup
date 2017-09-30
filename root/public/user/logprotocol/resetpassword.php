<?php
include_once("./../../../template_php/checkloginstatus.php");
// If user is already logged in, header that weenis away
if($user_ok === true){
    header("location: ./../profile.php?u=".$_SESSION["username"]);
    exit();
}
?><?php
// AJAX CALLS THIS CODE TO EXECUTE
if(isset($_POST["e"])){
    $e = mysqli_real_escape_string($db_connection, $_POST['e']);
    $sql = "SELECT id, username FROM user WHERE email='$e' AND activated='1' LIMIT 1";
    $query = mysqli_query($db_connection, $sql);
    $numrows = mysqli_num_rows($query);
    if($numrows > 0){
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            $id = $row["id"];
            $u = $row["username"];
        }
        $emailcut = substr($e, 0, 4);
        $randNum = rand(10000,99999);
        $tempPass = "$emailcut$randNum";
        $hashTempPass = md5($tempPass);
        $sql = "UPDATE useroptions SET temp_pass='$hashTempPass' WHERE username='$u' LIMIT 1";
        $query = mysqli_query($db_connection, $sql);
        $to = "$e";
        $from = "";
        $headers ="From: $from\n"; // instellen als site werkt
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1 \n";
        $subject ="Catshup Temporary Password";
        $msg = '<h2>Hello '.$u.'</h2> 
                <p>This is an automated message from yoursite. If you did not recently initiate the Forgot Password process, please disregard this email.</p>
                <p>You indicated that you forgot your login password. We can generate a temporary password for you to log in with, then once logged in you can change your password to anything you like.</p>
                <p>After you click the link below your password to login will be:<br /><b>'.$tempPass.'</b></p>
                <p><a href="catshup/forgot_pass.php?u='.$u.'&p='.$hashTempPass.'">Click here now to apply the temporary password shown below to your account</a></p>
                <p>If you do not click the link in this email, no changes will be made to your account. In order to set your login password to the temporary password you must click the link above.</p>';
        if(mail($to,$subject,$msg,$headers)) {
            echo "success";
            exit();
        } else {
            echo "email_send_failed";
            exit();
        }
    } else {
        echo "no_exist";
    }
    exit();
}
?><?php
// EMAIL LINK CLICK CALLS THIS CODE TO EXECUTE
if(isset($_GET['u']) && isset($_GET['p'])){
    $u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
    $temppasshash = preg_replace('#[^a-z0-9]#i', '', $_GET['p']);
    if(strlen($temppasshash) < 10){
        exit();
    }
    $sql = "SELECT id FROM useroptions WHERE username='$u' AND temp_pass='$temppasshash' LIMIT 1";
    $query = mysqli_query($db_connection, $sql);
    $numrows = mysqli_num_rows($query);
    if($numrows === 0){
        header("location: message.php?msg=There is no match for that username with that temporary password in the system. We cannot proceed.");
        exit();
    } else {
        $row = mysqli_fetch_row($query);
        $id = $row[0];
        $sql = "UPDATE users SET password='$temppasshash' WHERE id='$id' AND username='$u' LIMIT 1";
        $query = mysqli_query($db_connection, $sql);
        $sql = "UPDATE useroptions SET temp_pass='' WHERE username='$u' LIMIT 1";
        $query = mysqli_query($db_connection, $sql);
        header("location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css"/>
    <script src="/catshup/root/js/js.js"></script>
    <script src="/catshup/root/js/ajax.js"></script>
    <script src="/catshup/root/js/resetpass.js"></script>
</head>
<body>
<?php include_once("./../../../template_php/template_header.php")?>
<div id="Content">
    <form id=""forgotpassform onsubmit="return false;">
        <span>Enter your email adress:</span>
        <input id="email" type="text" onfocus="_('status').innerHTML='';" maxlength="88"/>
        <br/>
        <button id="forgotpassbutton" onclick="forgotpass()">Generate Temporary Log In Password</button>
        <p id="status"></p>
    </form>
</div>
<?php include_once("./../../../template_php/template_footer.php")?>
</body>
</html>
