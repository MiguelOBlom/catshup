function showGallery(gallery,user, isOwner){
    _("galleries").style.display = "none";
    _("section_title").innerHTML = user+'&#39;s '+gallery+' Gallery &nbsp; <button onclick="backToGalleries()">Go back to all galleries</button>';
    _("photos").style.display = "block";
    _("photos").innerHTML = 'loading photos ...';
    var ajax = ajaxObj("POST", "/catshup/root/template_php/photo_system.php");
    ajax.onreadystatechange = function() {
        if(ajaxReturn(ajax) == true) {
            _("photos").innerHTML = '';
            var pics = ajax.responseText.split("|||");
            for (var i = 0; i < pics.length; i++){
                var pic = pics[i].split("|");
                _("photos").innerHTML += '<div><img onclick="photoShowcase(\''+pics[i]+'\',\''+user+'\',\''+isOwner+'\')" src="/catshup/root/public/user/files/'+user+'/'+pic[1]+'" alt="pic"><div>';
            }
            _("photos").innerHTML += '<p style="clear:left;"></p>';
        }
    }
    ajax.send("show=galpics&gallery="+gallery+"&user="+user);
}
function backToGalleries(){
    _("photos").style.display = "none";
    _("section_title").innerHTML = "<?php echo $u; ?>&#39;s Photo Galleries";
    _("galleries").style.display = "block";
}
function photoShowcase(picdata, user, isOwner){
    var data = picdata.split("|");
    _("section_title").style.display = "none";
    _("photos").style.display = "none";
    _("picbox").style.display = "block";
    _("picbox").innerHTML = '<button onclick="closePhoto()">x</button>';
    _("picbox").innerHTML += '<img src="/catshup/root/public/user/files/'+user+'/'+data[1]+'" alt="photo">';
    if(isOwner == "yes"){
        _("picbox").innerHTML += '<p id="deletelink"><a href="#" onclick="return false;" onmousedown="deletePhoto(\''+data[0]+'\',\''+user+'\')">Delete this Photo <?php echo $u; ?></a></p>';
    }
}
function closePhoto(){
    _("picbox").innerHTML = '';
    _("picbox").style.display = "none";
    _("photos").style.display = "block";
    _("section_title").style.display = "block";
}
function deletePhoto(id, user){
    var conf = confirm("Press OK to confirm the delete action on this photo.");
    if(conf != true){
        return false;
    }
    _("deletelink").style.visibility = "hidden";
    var ajax = ajaxObj("POST", "/catshup/root/template_php/photo_system.php");
    ajax.onreadystatechange = function() {
        if(ajaxReturn(ajax) == true) {
            if (ajax.responseText){
                alert (ajax.responseText);
            }
            if(ajax.responseText == "deleted_ok"){
                alert("This picture has been deleted successfully. We will now refresh the page for you.");
                window.location = "/catshup/root/public/user/photos.php?u="+user;
            }
        }
    }
    ajax.send("delete=photo&id="+id);
}