function coursesend() {
    var coursename = _("coursename").value;
    var coursecode = _("coursecode").value;
    var courseurl = _("courseurl").value;
    var lecturer = _("lecturer")[_("lecturer").selectedIndex].value;
    var status = _("status");

    if (coursename === "" || coursecode === "" || courseurl === "" || lecturer === "") {
        status.innerHTML = "Please fill out all data.";
    } else {
        _("coursebutton").style.display = "none";
        status.innerHTML = 'One moment please ...';
        var ajax = ajaxObj("POST", "addcourse.php");
        ajax.onreadystatechange = function () {
            if (ajaxReturn(ajax) === true) {
                if (ajax.responseText !== "send_success") {
                    status.innerHTML = ajax.responseText;
                } else {
                    location.reload();
                }
            }
        };
        ajax.send("coursename=" + coursename + "&coursecode=" + coursecode + "&courseurl=" + courseurl + "&lecturerid=" + lecturer);
    }
}