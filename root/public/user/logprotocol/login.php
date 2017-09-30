<?php
session_start();
if (isset($_SESSION["username"])){
    header("location: ./../profile.php?u=".$_SESSION["username"]);
    exit;
}
?>
<?php
    if(isset($_POST["l"])){
        include_once("./../../../sql/db_connection.php");
        $l = mysqli_real_escape_string($db_connection, $_POST['l']);
        $p = md5($_POST['p']);
        $ip = preg_replace('#[^0-9.]#','', getenv('REMOTE_ADDR'));
        if($l === "" || $p === ""){
            echo "login_failed";
            exit();

        } else {
            $sql = "SELECT id, username, password FROM user WHERE email='$l' OR username='$l' LIMIT 1";
            $query = mysqli_query($db_connection, $sql);
            $row = mysqli_fetch_row($query);
            $db_id = $row[0];
            $db_username = $row[1];
            $db_pass_str = $row[2];
            if($p !== $db_pass_str){
                echo "login_failed";
                exit();
            } else {
                $_SESSION['userid'] = $db_id;
                $_SESSION['username'] = $db_username;
                $_SESSION['password'] = $db_pass_str;
                setcookie("id", $db_id, strtotime('+30 days'), "/", "", "", TRUE);
                setcookie("user", $db_username, strtotime('+30 days'), "/", "", "", TRUE);
                setcookie("pass", $db_pass_str, strtotime('+30 days'), "/", "","", TRUE);

                $sql = "UPDATE users SET lastlogin=now(), ip='$ip' WHERE username='$db_username' LIMIT 1";
                $query = mysqli_query($db_connection, $sql);
                echo $db_username;
                exit();
            }
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
    <script src='./../../../js/ajax.js'></script>
    <script src='./../../../js/login.js'></script>
    <script>
        function clearlogin() {
            if (_('login').value === 'Voorbeeld: Henk') {
                _('login').value = '';
                _('login').style.color = 'black';
            };
        }
    </script>
</head>
<body>
    <?php include_once("./../../../template_php/template_header.php")?>
    <div id="Content">
        <h3>Login</h3>
        <form id="loginform" onsubmit="return false;">
            <div>
                <span>Username/e-mail:</span><br/>
                <input type= "text" id="login" name="login" onclick="clearlogin();" onfocus="emptyElement('status');" value = "Voorbeeld: Henk" style="color:lightgrey;" maxlength="88"><br/>
            </div>
            <div>
                <span>Password:</span><br/>
                <input type="password" name="password" id="password" onfocus="emptyElement('status')" maxlength='100'><br/>
            </div>
            <button id="loginbutton" onclick="loginfunc();">Log In</button>
            <p id="status"></p>
        </form>
        <a href="./register.php">Register</a><br>
        <a href="./resetpassword.php">Forgot Password?</a>
        </div>
    </div>
    <?php include_once("./../../../template_php/template_footer.php")?>
</body>
</html>