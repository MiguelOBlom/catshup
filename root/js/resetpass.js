function forgotpass(){
    var e = _("email").value;
    if (e === ""){
        _('status').innerHTML = "Type in your email adress";
    } else {
        _('forgotpassbutton').style.display = "none";
        _('status').innerHTML = "please wait...";
        var ajax = ajaxObj("POST", "resetpassword.php");
        ajax.onreadystatechange = function(){
            if(ajaxReturn(ajax) == true){
                var response = ajax.responseText;
                if (response = "success"){
                    _('forgotpassform').innerHTML = "<h3>Check your email inbox</h3>"
                } else if (response === "no_exist"){
                    _('status').innerHTML = "email not in system";
                } else if (response === "email_send_failed"){
                    _('status').innerHTML = "there was a problem sending the email.";
                } else {
                    _('status').innerHTML = "oops, something went wrong";
                }
            }
        }
        ajax.send("e="+e);
    }

}