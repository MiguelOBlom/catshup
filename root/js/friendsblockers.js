function friendToggle(type, user, elem){
    var conf = confirm("Press OK to confirm the '"+type+"' action for user "+user+".");
    if(conf != true){
        return false;
    }
    _(elem).innerHTML = 'please wait ...';
    var ajax = ajaxObj("POST", './../../template_php/friend_system.php');
    ajax.onreadystatechange = function (){
        if(ajaxReturn(ajax) === true){
            if(ajax.responseText == "friendok"){
                _(elem).innerHTML = 'OK friend request sent.';
            } else if (ajax.responseText == "unfriend_ok"){
                _(elem).innerHTML = '<button onclick="friendToggle(\'friend\',\'<?php echo $u;?>\',\'friendButton\' )">Request As Friend</button>';
            } else {
                alert(ajax.responseText);
                _(elem).innerHTML = 'Try again later.';
            }
        }
    }
    ajax.send("type="+type+"&user="+user);
}

function blockToggle(type,blockee,elem){
    var conf = confirm("Press OK to confirm the '"+type+"' action on user "+blockee+".");
    if(conf != true){
        return false;
    }
    _(elem).innerHTML = "please wait ...";
    var ajax = ajaxObj("POST", "./../../template_php/block_system.php");
    ajax.onreadystatechange = function () {
        if(ajaxReturn(ajax) == true){
            if(ajax.responseText === "blocked_ok"){
                _(elem).innerHTML = '<button onclick="blockToggle(\'unblock\',\''+blockee+'\',\'blockButton\')">Unblock User</button>';
            } else if(ajax.responseText === "unblocked_ok") {
                _(elem).innerHTML = '<button onclick="blockToggle(\'block\',\''+blockee+'\',\'blockButton\')">Block User</button>';
            } else {
                alert(ajax.responseText);
                _(elem).innerHTML = 'Try again later.';
            }
        }
    }
    ajax.send('type='+type+'&blockee='+blockee);
}