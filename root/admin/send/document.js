function documentsend() {
    var documentname = _("documentname").value;
    var documentdesc = _("documentdesc").value;
    var documentshortdesc = _("documentshortdesc").value;
    var documentsource = _("documentsource").value;
    var lessonid = _("lesson")[_("lesson").selectedIndex].value;
    var documentcategory = _("documentcategory")[_("documentcategory").selectedIndex].value;
    var status = _("status");
    if (documentname === "" || documentdesc === "" || documentshortdesc === "" || documentsource === "" || lessonid === "" || documentcategory === "") {
        status.innerHTML = "Please fill out all data.";
    } else {
        _("documentbutton").style.display = "none";
        status.innerHTML = 'One moment please ...';
        var ajax = ajaxObj("POST", "adddocument.php");
        ajax.onreadystatechange = function () {
            if (ajaxReturn(ajax) === true) {
                if (ajax.responseText !== "send_success") {
                    status.innerHTML = ajax.responseText;
                } else {
                    location.reload();
                }
            }
        };
        ajax.send("documentname=" + documentname + "&documentdesc=" + documentdesc + "&documentshortdesc=" + documentshortdesc + "&documentsource=" + documentsource + "&lessonid=" + lessonid + "&documentcategory=" +documentcategory);
    }
}