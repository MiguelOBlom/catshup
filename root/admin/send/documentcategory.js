function documentcategorysend() {
    var category = _("category").value;
    var status = _("status");
    if (category === "") {
        status.innerHTML = "Please fill out all data.";
    } else {
        _("lecturerbutton").style.display = "none";
        status.innerHTML = 'One moment please ...';
        var ajax = ajaxObj("POST", "adddocumentcategory.php");
        ajax.onreadystatechange = function () {
            if (ajaxReturn(ajax) === true) {
                if (ajax.responseText !== "send_success") {
                    status.innerHTML = ajax.responseText;
                } else {
                    location.reload();
                }
            }
        };
        ajax.send("category=" + category);
    }
}