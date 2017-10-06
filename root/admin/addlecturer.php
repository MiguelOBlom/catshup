<?php
include_once("./../sql/db_connection.php");
include_once("./../template_php/checkloginstatus.php");
if($admin !== true){
    header("location: ./../public/index.php");
}
?>
<?php
if(isset($_POST["lecturername"])) {
    $lecturername = preg_replace('#[^a-z0-9]#i', '', $_POST['lecturername']);
    $lectureremail = preg_replace('#[^a-z0-9@.]#i', '', $_POST['lectureremail']);
    $lecturerroomno = preg_replace('#[^a-z0-9]#i', '', $_POST['lecturerroomno']);
    //Datacheck

    $lnsql = "SELECT id FROM lecturer WHERE lecturername='$lecturername' LIMIT 1";
    $lnquery = mysqli_query($db_connection, $lnsql);
    $name_check = mysqli_num_rows($lnquery);

    $lesql = "SELECT id FROM lecturer WHERE lectureremail='$lectureremail' LIMIT 1";
    $lequery = mysqli_query($db_connection, $lesql);
    $email_check = mysqli_num_rows($lequery);

    //Errors
    if ($lecturername === "" || $lectureremail === "" || $lecturerroomno === ""){
        echo "The form submission is missing values.";
        exit();
    } else if ($name_check > 0){
        echo "Name already taken.";
        exit();
    } else if ($email_check > 0){
        echo "Email already taken.";
        exit();
    } else {
        $sql = "INSERT INTO lecturer (lecturername, lectureremail, lecturerroomno) VALUES('$lecturername','$lectureremail','$lecturerroomno')";
        mysqli_query($db_connection, $sql);

        echo "send_success";
        exit();
    }
    exit();
}
?>
<?php
$TBLsql = "SELECT * FROM lecturer";
$FIELDNAMEsql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='lecturer'";
$TBLquery= mysqli_query($db_connection, $TBLsql);
$FIELDNAMEquery = mysqli_query($db_connection, $FIELDNAMEsql);
$TBLItemCount = mysqli_num_rows($TBLquery);
$TBLFieldCount = mysqli_num_fields($TBLquery);
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css"/>
    <script src="/catshup/root/js/js.js"></script>
    <script src="/catshup/root/js/ajax.js"></script>
    <script src="./send/lecturer.js"></script>
</head>
<body>
<?php include_once("./../template_php/template_header.php")?>
<div id="Content">
    <a href="./panel.php">Back to the panel.</a>
    <form id="course" onsubmit="return false;">
        <input id="lecturername" type="text" onkeyup="restrict('lecturername');" maxlength="40"/>
        <input id="lectureremail" type="text" onkeyup="restrict('lectureremail');" maxlength="40"/>
        <input id="lecturerroomno" type="text"  onkeyup="restrict('lecturerroomno');" />
        <input id="lecturerbutton" type="submit" onclick="lecturersend();"/>
        <span id="status"></span>
    </form>
    <?php if($TBLItemCount > 0){?>
        <table>
            <tr>
                <?php while($FIELDNAMErow = mysqli_fetch_array($FIELDNAMEquery)){?>
                    <th><?php echo($FIELDNAMErow[0]);?></th>
                <?php };?>
            </tr>
            <?php while($row = mysqli_fetch_array($TBLquery)){?>
                <tr>
                    <?php for($i = 0; $i < $TBLFieldCount; $i++){?>
                        <td><?php echo $row[$i]; ?></td>
                    <?php };?>
                    <td>EDIT</td>
                    <td>DELETE</td>
                </tr>
            <?php }; ?>
        </table>
    <?php } else { ?>
        <p>No lecturers yet!</p>
    <?php };?>
</div>
<?php include_once("./../template_php/template_footer.php")?>
</body>
</html>
