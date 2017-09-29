<!DOCTYPE html>
<html>
<?php//login script
//check of gebruiker is ingelogd
//store cookie?>
<head>
    <title>Catshup</title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css"/>
    <script src='/catshup/root/js/js.js'></script></head>
<body>
    <?php include_once("./../../../template_php/template_header.php")?>
    <div id="Content">
        <div id="login, form">
            <h3>login</h3>
            <form>
                <span>Username/e-mail:</span><br/>
                <input type= "text" name = "login" value = "Henk"><br/>
                <span>Password:</span><br/>
                <input type ="password" name= "pass" ><br/>
                <input type = "submit" value = "submit" >
            </form>

            <a href="./register.php">Register</a><br>
            <a href="./resetpassword.php">Forgot Password?</a>
        </div>
    </div>
    <?php include_once("./../../../template_php/template_footer.php")?>
</body>
</html>