function homeworksend() {
    var homeworkname = _("homeworkname").value;
    var homeworkdesc = _("homeworkdesc").value;
    var homeworkshortdesc = _("homeworkshortdesc").value;
    var homeworkurl = _("homeworkurl").value;
    var tododate = _("tododate").value;
    var lessonid = _("lessonid")[_("lessonid").selectedIndex].value;
    var documentid = _("documentid")[_("documentid").selectedIndex].value;
    var status = _("status");
    if (homeworkname === "" || homeworkdesc === "" || homeworkshortdesc === "" || tododate === "" || lessonid === "" || documentid === "") {
        status.innerHTML = "Please fill out all data, homeworkurl isn't nescesary.";
    } else {
        _("homeworkbutton").style.display = "none";
        status.innerHTML = 'One moment please ...';
        var ajax = ajaxObj("POST", "addhomework.php");
        ajax.onreadystatechange = function () {
            if (ajaxReturn(ajax) === true) {
                if (ajax.responseText !== "send_success") {
                    status.innerHTML = ajax.responseText;
                } else {
                    location.reload();
                }
            }
        };
        ajax.send("homeworkname=" + homeworkname + "&homeworkdesc=" + homeworkdesc + "&homeworkshortdesc=" + homeworkshortdesc + "&homeworkurl=" + homeworkurl + "&tododate=" + tododate + "&lessonid=" + lessonid + "&documentid=" + documentid);
    }
}