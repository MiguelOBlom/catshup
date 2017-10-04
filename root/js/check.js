function checkusername() {
    var u = _("username").value;
    if(u !== "") {
        _("usernamestatus").innerHTML = 'Checking ...';
        var ajax = ajaxObj("POST", 'register.php');
        ajax.onreadystatechange = function () {
            if (ajaxReturn(ajax) === true) {
                _("usernamestatus").innerHTML = ajax.responseText;
            }
        };
        ajax.send("usernamecheck=" + u);
    }
}
/*
function checkemail(){
    var e = _("email").value;
    if(!/[A-Z.a-z0-9]+@umail.leidenuniv.nl/g.test(e) && e !== ""){
        _("emailstatus").innerHTML = "Please make sure to use your umail account.";
    } else {
        _("emailstatus").innerHTML = "";
    }
}
*/
function signup() {
    var u = _("username").value;
    var e = _("email").value;
    var p1 = _("password1").value;
    var p2 = _("password2").value;
    var status = _("status");
    if (u === "" || e === "" || p1 === "" || p2 === "") {
        status.innerHTML = "Please fill out all data.";
    } else if (p1 !== p2) {
        status.innerHTML = "Please repeat the exact same password you typed in the first field.";
    } else {
        _("registerbutton").style.display = "none";
        status.innerHTML = 'One moment please ...';
        var ajax = ajaxObj("POST", "register.php");
        ajax.onreadystatechange = function () {
            if (ajaxReturn(ajax) === true) {
                if (ajax.responseText !== "signup_success") {
                    status.innerHTML = ajax.responseText;
                } else {
                    window.scrollTo(0, 0);
                    _("registerform").innerHTML = "Alright " + u + " please check your email inbox to verify that you signed up.";
                }
            }
        };
        ajax.send("u=" + u + "&e=" + e + "&p=" + p1);
    }
}