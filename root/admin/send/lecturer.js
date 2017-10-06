function lecturersend() {
    var name = _("lecturername").value;
    var email = _("lectureremail").value;
    var room = _("lecturerroomno").value;
    var status = _("status");
    if (name === "" || email === "" || room === "") {
        status.innerHTML = "Please fill out all data.";
    } else {
        _("lecturerbutton").style.display = "none";
        status.innerHTML = 'One moment please ...';
        var ajax = ajaxObj("POST", "addlecturer.php");
        ajax.onreadystatechange = function () {
            if (ajaxReturn(ajax) === true) {
                if (ajax.responseText !== "send_success") {
                    status.innerHTML = ajax.responseText;
                } else {
                    location.reload();
                }
            }
        };
        ajax.send("lecturername=" + name + "&lectureremail=" + email + "&lecturerroomno=" + room);
    }
}