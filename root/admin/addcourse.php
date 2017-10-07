<?php
include_once("./../sql/db_connection.php");
include_once("./../template_php/checkloginstatus.php");
if($admin !== true){
    header("location: ./../public/index.php");
}
?>
<?php
if(isset($_POST["coursename"])) {
$coursename = preg_replace('#[^a-z0-9 ]#i', '', $_POST['coursename']);
$coursecode = preg_replace('#[^a-z0-9]#i', '', $_POST['coursecode']);
$courseurl = mysqli_real_escape_string($db_connection, $_POST['courseurl']);
$lecturerid = preg_replace('#[^0-9]#i', '', $_POST['lecturerid']);
//Datacheck

$cnsql = "SELECT id FROM course WHERE coursename='$coursename' LIMIT 1";
$cnquery = mysqli_query($db_connection, $cnsql);
$name_check = mysqli_num_rows($cnquery);

$ccsql = "SELECT id FROM course WHERE coursecode='$coursecode' LIMIT 1";
$ccquery = mysqli_query($db_connection, $ccsql);
$code_check = mysqli_num_rows($ccquery);

$cusql = "SELECT id FROM course WHERE courseurl='$courseurl' LIMIT 1";
$cuquery = mysqli_query($db_connection, $cusql);
$url_check = mysqli_num_rows($cuquery);

//Errors
if ($coursename === "" || $coursecode === "" || $courseurl === "" || $lecturerid === ""){
echo "The form submission is missing values.";
exit();
} else if ($name_check > 0){
echo "Name already exists.";
exit();
} else if ($code_check > 0){
echo "Code already exists.";
exit();
} else if ($url_check > 0){
    echo "URL already exists.";
    exit();
} else {
    $sql = "INSERT INTO course (coursename, coursecode, courseurl, lecturerid) VALUES('$coursename', '$coursecode', '$courseurl', '$lecturerid')";
    mysqli_query($db_connection, $sql);

    echo "send_success";
    exit();
}
exit();
}
?>
<?php
$FORMsql = "SELECT id, lecturername FROM lecturer";
$FORMquery = mysqli_query($db_connection, $FORMsql);
$FORMcount = mysqli_num_rows($FORMquery);

$TBLsql = "SELECT * FROM course";
$FIELDNAMEsql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='course'";
$TBLquery= mysqli_query($db_connection, $TBLsql);
$FIELDNAMEquery = mysqli_query($db_connection, $FIELDNAMEsql);
$TBLItemCount = mysqli_num_rows($TBLquery);
$TBLFieldCount = mysqli_num_fields($TBLquery);
$dude = [];
$dudesql = "SELECT id, lecturername FROM lecturer";
$dudequery = mysqli_query($db_connection, $dudesql);
while($index = mysqli_fetch_array($dudequery, MYSQLI_NUM)){
    $dude[$index[0]] = $index[1];
};
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css"/>
    <script src="/catshup/root/js/js.js"></script>
    <script src="/catshup/root/js/ajax.js"></script>
    <script src="send/course.js?v=1"></script>
</head>
<body>
<?php include_once("./../template_php/template_header.php")?>
<div id="Content">
    <a href="./panel.php">Back to the panel.</a>
    <?php if($FORMcount > 0){?>
        <form id="course" onsubmit="return false;">
            <input id="coursename" type="text" onkeyup="restrict('coursename');" maxlength="40"/>
            <input id="coursecode" type="text" onkeyup="restrict('coursecode');" maxlength="10"/>
            <input id="courseurl" type="text"  onkeyup="restrict('courseurl');" />
            <select id="lecturer" name="lecturer" required>
                <option value=""></option>
                <?php while($FORMrow = mysqli_fetch_array($FORMquery)){?>
                <option value="<?php echo $FORMrow["id"]; ?>"><?php echo $FORMrow["lecturername"]; ?></option>
                <?php };?>
            </select>
            <input id="coursebutton" type="submit" onclick="coursesend();"/>
            <span id="status"></span>
        </form>
    <?php } else { ?>
        <p>No lecturers yet, add them in the lecturer's tab.</p>
    <?php };?>
    <?php if($TBLItemCount > 0){?>
    <table>
        <tr>
            <?php while($FIELDNAMErow = mysqli_fetch_array($FIELDNAMEquery)){?>
                <th>
                    <?php if($FIELDNAMErow[0] === "lecturerid"){
                        echo "lecturer";
                    } else {
                        echo($FIELDNAMErow[0]);
                    }?>
                </th>
            <?php };?>
        </tr>
        <?php while($row = mysqli_fetch_array($TBLquery)){?>
            <tr>
                <?php for($i = 0; $i < $TBLFieldCount; $i++){?>
                    <td>
                    <?php if($i === 4){
                        echo $dude[$row[$i]];
                    } else {
                        echo $row[$i];
                    } ?>
                    </td>
                <?php };?>
                <td>EDIT</td>
                <td>DELETE</td>
            </tr>
        <?php }; ?>
    </table>
    <?php } else { ?>
        <p>No courses yet!</p>
    <?php };?>
</div>
<?php include_once("./../template_php/template_footer.php")?>
</body>
</html>
