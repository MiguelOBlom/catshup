<?php
include_once("./../sql/db_connection.php");
include_once("./../template_php/checkloginstatus.php");
if($admin !== true){
    header("location: ./../public/index.php");
}
?>
<?php
if(isset($_POST["lessonname"])) {
    $lessonname = preg_replace('#[^a-z0-9 ]#i', '', $_POST['lessonname']);
    $lessonurl = mysqli_real_escape_string($db_connection, $_POST['lessonurl']);
    $lessondate = preg_replace('#[^0-9:- ]#i', '', $_POST['lessondate']);
    $lessonroom = preg_replace('#[^a-z0-9]#i', '', $_POST['lessonroom']);
    $courseid = preg_replace('#[^0-9]#i', '', $_POST['courseid']);


//Errors
    if ($lessonname === "" || $lessonurl === "" || $lessondate === "" || $lessonroom === "" || $courseid === ""){
        echo "The form submission is missing values.";
        exit();
    } else {
        $sql = "INSERT INTO lesson (lessonname, lessonurl, lessondate, lessonroom, courseid) 
                VALUES('$lessonname', '$lessonurl', '$lessondate', '$lessonroom','$courseid')";
        mysqli_query($db_connection, $sql);

        echo "send_success";
        exit();
    }
    exit();
}
?>
<?php
$FORMsql = "SELECT id, coursename FROM course";
$FORMquery = mysqli_query($db_connection, $FORMsql);
$FORMcount = mysqli_num_rows($FORMquery);

$FIELDNAMEsql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='lesson'";
$FIELDNAMEquery = mysqli_query($db_connection, $FIELDNAMEsql);
$TBLsql = "SELECT * FROM lesson";
$TBLquery= mysqli_query($db_connection, $TBLsql);
$TBLItemCount = mysqli_num_rows($TBLquery);
$TBLFieldCount = mysqli_num_fields($TBLquery);

$vak = [];
$vaksql = "SELECT id, coursename FROM course";
$vakquery = mysqli_query($db_connection, $vaksql);
while($index = mysqli_fetch_array($vakquery, MYSQLI_NUM)){
    $vak[$index[0]] = $index[1];
};
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css"/>
    <script src="/catshup/root/js/js.js"></script>
    <script src="/catshup/root/js/ajax.js"></script>
    <script src="send/lesson.js"></script>
</head>
<body>
<?php include_once("./../template_php/template_header.php")?>
<div id="Content">
    <a href="./panel.php">Back to the panel.</a>
    <?php if($FORMcount > 0){?>
    <form id="lesson" onsubmit="return false;">
        <select id ="courseid">
            <option value=""></option>
            <?php while($FORMrow = mysqli_fetch_array($FORMquery)){?>
                <option value="<?php echo $FORMrow["id"];?>"><?php echo $FORMrow["coursename"]; ?></option>
            <?php }; ?>
        </select>
        <input id="lessonname" type="text"  onkeyup="restrict('lessonname');" />
        <input id="lessonurl" type="text"  onkeyup="restrict('lessonurl');" />
        <input id="lessondate" type="date"  />
        <input id="lessonroom" type="text"  onkeyup="restrict('lessonroom');" />
        <input id="lessonbutton" type="submit" onclick="lessonsend();"/>
        <span id="status"></span>
    </form>
    <?php } else {?>
        <p>There aren't any courses yet, add them in the course tab</p>
    <?php };?>
   <?php if($TBLItemCount > 0){?>
        <table>
            <tr>
                <?php while($FIELDNAMErow = mysqli_fetch_array($FIELDNAMEquery)){?>
                    <th>
                        <?php if($FIELDNAMErow[0] == "courseid"){
                            echo("course");
                        } else {
                            echo($FIELDNAMErow[0]);
                        };?>
                    </th>
                <?php };?>
            </tr>
            <?php while($row = mysqli_fetch_array($TBLquery)){?>
                <tr>
                    <?php for($i = 0; $i < $TBLFieldCount; $i++){?>
                        <td>
                            <?php if($i === 1){
                                echo $vak[$row[$i]];
                            }else{
                                echo $row[$i];
                            }; ?>
                        </td>
                    <?php };?>
                    <td>EDIT</td>
                    <td>DELETE</td>
                </tr>
            <?php }; ?>
        </table>
    <?php } else { ?>
        <p>No lessons yet!</p>
    <?php };?>
</div>
<?php include_once("./../template_php/template_footer.php")?>
</body>
</html>
