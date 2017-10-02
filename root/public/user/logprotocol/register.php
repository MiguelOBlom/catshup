<?php
include_once("./../../../sql/db_connection.php");
?>
<?php
//Check if user is logged in
    session_start();
    if(isset($_SESSION["username"])){
        header("location: message.php?msg=You are already logged in");
        exit();
    }
?>
<?php
// Namecheck
    if(isset($_POST["usernamecheck"])){
        $username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
        $sql = "SELECT id FROM user WHERE username='$username' LIMIT 1";
        $query = mysqli_query($db_connection, $sql);
        if($query){
            $username_check = mysqli_num_rows($query);
        } else {
            echo 'There is an issue with the database, please report this to the admin.';
            exit();
        }

        if(strlen($username)< 3 || strlen($username) > 16 ){
            echo '<strong style="color:#F00;">must be 3 - 16 characters</strong>';
            exit();
        }
        if (is_numeric($username[0])){
            echo '<strong style="color:#F00;">Username must begin with a letter</strong>';
            exit();
        }
        if($username_check < 1){
            echo '<strong style="color:#009900;">'. $username .' is OK</strong>';
            exit();
        } else {
            echo '<strong style="color:#F00;">'. $username.' is taken</strong>';
            exit();
        }

    }

?>
<?php
// Send to database
if(isset($_POST["u"])){
    include_once ("./../../../sql/db_connection.php");
    $u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
    $e = mysqli_real_escape_string($db_connection, $_POST['e']);
    $p = $_POST['p'];
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
    //Datacheck
    $sql = "SELECT id FROM user WHERE username='.$u.' LIMIT 1";
    $query = mysqli_query($db_connection, $sql);
    $u_check = mysqli_num_rows($query);

    $sql = "SELECT id FROM user WHERE email='.$e.' LIMIT 1";
    $query = mysqli_query($db_connection, $sql);
    $e_check = mysqli_num_rows($query);

    //Errors
    if ($u === "" || $e === "" || $p === ""){
        echo "The form submission is missing values.";
        exit();
    } else if ($u_check > 0){
        echo "Username already taken.";
        exit();
    } else if ($e_check > 0){
        echo "<a href='./resetpassword.php'>Email already taken, forgot your password?</a>";
        exit();
    } else if (strlen($u) < 3 || strlen($u) > 16){
        echo "Submit username ranging from 3 - 16 characters.";
        exit();
    } else if (is_numeric($u[0])){
        echo "The username cannot begin with a number.";
        exit();
    } else {
        $p_hash = md5($p);

        $sql = "INSERT INTO user (username, email, password, signup, lastlogin, notescheck, ip) VALUES('$u','$e','$p_hash',now(),now(),now(),$ip)";
        $query = mysqli_query($db_connection, $sql);
        $uid = mysqli_insert_id($db_connection);

        $sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$u','original')";
        mysqli_query($db_connection, $sql);
        if(!file_exists("./../files/$u")){
            mkdir("./../files/$u", 0755);
        }

        /*$to = "$e";
		$from = "auto_responder@catshup.com";
		$subject = 'Catshup Account Registration';
		$message = '<!DOCTYPE html>
                    <html>
                        <head>
                            <meta charset="UTF-8">
                            <title>yoursitename Message</title>
                        </head>
                        <body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;">
                            <div style="padding:10px; background:#333; font-size:24px; color:#CCC;">
                                <a href="#">Catshup</a> Account Activation
                            </div>
                            <div style="padding:24px; font-size:17px;">
                                Hello '.$u.',<br /><br />
                                Click the link below to activate your account when ready:<br /><br />
                                <a href="localhost/catshup/root/public/user/logprotocol/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">
                                    Click here to activate your account now
                                </a><br /><br />
                                Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b>
                            </div>
                        </body>
                    </html>';

		$headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail($to, $subject, $message, $headers);
        */
        echo '<a href="/catshup/root/public/user/logprotocol/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">HOI</a>';
		echo "Signup success";
		exit();
    }
    exit();
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Catshup</title>
    <link rel="stylesheet" type="text/css" href="./../../../css.css"/>
    <script src='./../../../js/js.js'></script>
    <script src='./../../../js/check.js'></script>
    <script src='./../../../js/ajax.js'></script>
</head>
<body>
    <?php include_once("./../../../template_php/template_header.php")?>
    <div id="Content">
        <h3>Registreren</h3>
        <form name="registerform" id="registerform" onsubmit="return false;">
            <div>
                <span>Username:</span>
                <input id="username" type="text" onblur="checkusername();" onkeyup="restrict('username')" maxlength="16"/> <br/>
                <span id="usernamestatus"></span>
            </div>
            <div>
                <span>Email Adress:</span>
                <input id="email" type="text" onblur="/*checkemail();*/" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88"/><br/>
                <span id="emailstatus"> </span>
            </div>
            <div>
                <span>Cool Password:</span>
                <input id="password1" type="password" onfocus="emptyElement('status')" maxlength="100" /><br/>
                <span>Repeat Password:</span>
                <input id="password2" type="password" onfocus="emptyElement('status')" maxlength="100" /><br/>
            </div>

            <span><a href="terms.php">Terms and Conditions</a></span><br/>
            <span id="status"></span><br/>
            <button id="registerbutton" onclick="signup();">Register</button>

        </form>
    </div>
    <?php include_once("./../../../template_php/template_footer.php")?>
</body>
</html>