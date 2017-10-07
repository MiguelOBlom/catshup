function lessonsend() {
    var lessonname = _("lessonname").value;
    var lessonurl = _("lessonurl").value;
    var lessondate = _("lessondate").value;
    var lessonroom = _("lessonroom").value;
    var courseid = _("courseid")[_("courseid").selectedIndex].value;
    var status = _("status");

    if (lessonname === "" || lessonurl === "" || lessondate === "" || lessonroom === "" || courseid === "") {
        status.innerHTML = "Please fill out all data.";
    } else {
        _("lessonbutton").style.display = "none";
        status.innerHTML = 'One moment please ...';
        var ajax = ajaxObj("POST", "addlesson.php");
        ajax.onreadystatechange = function () {
            if (ajaxReturn(ajax) === true) {
                if (ajax.responseText !== "send_success") {
                    status.innerHTML = ajax.responseText;
                } else {
                    location.reload();
                }
            }
        };
        ajax.send("lessonname=" + lessonname + "&lessonurl=" + lessonurl + "&lessondate=" + lessondate + "&lessonroom=" + lessonroom + "&courseid=" + courseid);
    }
}