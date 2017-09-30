<?php

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
