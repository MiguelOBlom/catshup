<?php
include_once ("./../../sql/db_connection.php");
include_once ("./../../template_php/checkloginstatus.php");
// Make sure the _GET "u" is set, and sanitize it
$u = "";

if(isset($_GET["u"])){
    $u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: /catshup/root/public/index.php");
    exit();
}
$photo_form = "";
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
    $isOwner = "yes";
    $photo_form  = '<form id="photo_form" enctype="multipart/form-data" method="post" action="./../../template_php/photo_system.php">';
    $photo_form .=   '<h3>Hi '.$u.', add a new photo into one of your galleries</h3>';
    $photo_form .=   '<b>Choose Gallery:</b> ';
    $photo_form .=   '<select name="gallery" required>';
    $photo_form .=     '<option value=""></option>';
    $photo_form .=     '<option value="Myself">Myself</option>';
    $photo_form .=     '<option value="Notes">Notes</option>';
    $photo_form .=   '</select>';
    $photo_form .=   ' &nbsp; &nbsp; &nbsp; <b>Choose Photo:</b> ';
    $photo_form .=   '<input type="file" name="photo" accept="image/*" required>';
    $photo_form .=   '<p><input type="submit" value="Upload Photo Now"></p>';
    $photo_form .= '</form>';               //add delete galleries
}
// Select the user galleries
$gallery_list = "";
$sql = "SELECT DISTINCT gallery FROM photos WHERE user='$u'";
$query = mysqli_query($db_connection, $sql);
if(mysqli_num_rows($query) < 1){
    if($isOwner == "yes"){
      $gallery_list = "You haven't uploaded any photos yet.";
    } else {
      $gallery_list = "This user has not uploaded any photos yet.";
    }
} else {
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $gallery = $row["gallery"];
        $countquery = mysqli_query($db_connection, "SELECT COUNT(id) FROM photos WHERE user='$u' AND gallery='$gallery'");
        $countrow = mysqli_fetch_row($countquery);
        $count = $countrow[0];
        $filequery = mysqli_query($db_connection, "SELECT filename FROM photos WHERE user='$u' AND gallery='$gallery' ORDER BY RAND() LIMIT 1");
        $filerow = mysqli_fetch_row($filequery);
        $file = $filerow[0];
        $gallery_list .= '<div>';
        $gallery_list .=   '<div onclick="showGallery(\''.$gallery.'\',\''.$u.'\',\''.$isOwner.'\')">';
        $gallery_list .=     '<img src="files/'.$u.'/'.$file.'" alt="cover photo">';
        $gallery_list .=   '</div>';
        $gallery_list .=   '<b>'.$gallery.'</b> ('.$count.')';
        $gallery_list .= '</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html>
<head>
    <title>Catshup <?php echo $u ?>'s profile</title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css?v=2"/>
    <script src="/catshup/root/js/js.js"></script>
    <script src="/catshup/root/js/ajax.js"></script>
    <script src="/catshup/root/js/photos.js"></script>
</head>
<body>
<?php include_once("./../../template_php/template_header.php")?>
<div id="Content">
    <div id="photo_form"><?php echo $photo_form; ?></div>
    <h2 id="section_title"><?php echo $u; ?>&#39;s Photo Galleries</h2>
    <div id="galleries"><?php echo $gallery_list; ?></div>
    <div id="photos"></div>
    <div id="picbox"></div>
    <?php if($isOwner == "no"){?>
        <p style="clear:left;">These photos belong to <a href="profile.php?u=<?php echo $u; ?>"><?php echo $u; ?></a></p>
    <?php };?>
</div>
<?php include_once("./../../template_php/template_footer.php")?>
</body>
</html>
