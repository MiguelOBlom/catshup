<?php // register form
// check of gebruiker is ingelogd
?>
<!DOCTYPE html>
<html>
<head>
    <title>Catshup</title>
    <link rel="stylesheet" type="text/css" href="./../../../css.css"/>
    <script src='../../../js/js.js'></script></head>
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
                <input id="email" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88"/><br/>
                <span id="emailstatus"> </span>
            </div>
            <div>
                <span>Cool Password:</span>
                <input id="password1" type="password" onfocus="emptyElement('status')" maxlength="16" /><br/>
                <span>Repeat Password:</span>
                <input id="password2" type="password" onfocus="emptyElement('status')" maxlength="16" /><br/>
            </div>

            <span><a href="terms.php">Terms and Conditions</a></span><br/>

            <button id="registerbutton" onclick="register();">Register</button>

        </form>
    </div>
    <?php include_once("./../../../template_php/template_footer.php")?>
</body>
</html>