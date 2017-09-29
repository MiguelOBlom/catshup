function loginfunc(){
    var l = _("login").value;
    var p = _("password").value;
    if(l == "" || p == "") {
        _("status").innerHTML = "You had some fields unfilled."
    } else {
        _("loginbutton").style.display = "none";
        _("status").innerHTML = "connecting ...";
        var ajax = ajaxObj("POST", "login.php");
        ajax.onreadystatechange = function(){
            if(ajax.responseText == "login_failed"){
                _("status").innerHTML = "Couldn't login, please try again.";
                _("loginbutton").style.display = "block";
            } else {
                window.location = "./../profile.php?u="+ajax.responseText;
            }
        }
    }
    ajax.send("l="+l+"&p="+p);

}