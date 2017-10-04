function _(x){
    return document.getElementById(x);
}
function alerj(){alert("jey");}

function emptyElement(x){
    _(x).innerHTML = "";
}
function restrict(el){
    var tf = _(el);
    var rx = new RegExp;
    if (el === "email"){
        rx = /[' "]/gi;
    } else if(el === "username"){
        rx = /[^a-z0-9]/gi;
    }
    tf.value = tf.value.replace(rx, "");
}