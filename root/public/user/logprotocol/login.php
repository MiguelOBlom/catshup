<!DOCTYPE html>
<html>
//login script
//check of gebruiker is ingelogd
//store cookie
<head>
    <title>Catshup</title>
    <link rel="stylesheet" type="text/css" href="./../../../css.css"/>
    <script src="./../../../js.js"></script></head>
<body>
    <?php include_once("./../../../template_php/template_header.php")?>

    <div id="login, form">
        <form>
            
            <h1>login</h1>
            
            Username/e-mail: <br>
            <input type= "text" name = "login" value = "Henk"><br>
            Password:<br>
            <input type ="password" name= "pass" ><br>
            Submit<br>
            <input type = "submit" value = "submit" ><br>
            
            
        
        </form>
        <a href="./register.php">Register</a><br>
        <a href="./resetpassword.php">Forgot Password?</a>
        
        
    </div>







<?php include_once("./../../../template_php/template_footer.php")?>
</body>
</html>