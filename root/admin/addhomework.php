<?php
include_once("./../sql/db_connection.php");
include_once("./../template_php/checkloginstatus.php");
if($admin !== true){
    header("location: ./../public/index.php");
}
?>
<?php
$FIELDNAMEsql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='homework'";
$FIELDNAMEquery = mysqli_query($db_connection, $FIELDNAMEsql);
$TBLsql = "SELECT * FROM homework";
$TBLquery= mysqli_query($db_connection, $TBLsql);
$TBLItemCount = mysqli_num_rows($TBLquery);
$TBLFieldCount = mysqli_num_fields($TBLquery);

$les = [];
$lessql = "SELECT homework.lessonid, lesson.lessonname FROM homework INNER JOIN lesson ON homework.lessonid = lesson.id";
$lesquery = mysqli_query($db_connection, $lessql);
while($index = mysqli_fetch_array($lesquery, MYSQLI_NUM)){
    $les[$index[0]] = $index[1];
}

$code = [];
$codesql = "SELECT lesson.id , course.coursecode FROM lesson INNER JOIN course ON lesson.courseid = course.id";
$codequery = mysqli_query($db_connection, $codesql);
while($index = mysqli_fetch_array($codequery, MYSQLI_NUM)){
    $code[$index[0]] = $index[1];
};

$codefromdocid = [];
$codefromdocidsql = "SELECT document.id, course.coursecode FROM document INNER JOIN lesson ON document.lessonid = lesson.id INNER JOIN course ON lesson.courseid = course.id";
$codefromdocidquery = mysqli_query($db_connection, $codefromdocidsql);
while($index = mysqli_fetch_array($codefromdocidquery, MYSQLI_NUM)){
    $codefromdocid[$index[0]] = $index[1];
};

$document = [];
$documentsql = "SELECT id, documentname FROM document";
$documentquery = mysqli_query($db_connection, $documentsql);
while($index = mysqli_fetch_array($documentquery, MYSQLI_NUM)){
    $document[$index[0]] = $index[1];
};

$lesson = [];
$lessonsql = "SELECT id, lessonname FROM lesson";
$lessonquery = mysqli_query($db_connection, $lessonsql);

$Coursesql = "SELECT id, coursename FROM course";
$Coursequery = mysqli_query($db_connection, $Coursesql);
$Coursecount = mysqli_num_rows($Coursequery);
$docquery = mysqli_query($db_connection, $documentsql);
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css"/>
    <script src="/catshup/root/js/js.js"></script>
    <script src="/catshup/root/js/ajax.js"></script>
</head>
<body>

<?php include_once("./../template_php/template_header.php")?>
<div id="Content">
    <a href="./panel.php">Back to the panel.</a>

    <?php if($Coursecount > 0){?>
        <form id="lesson" onsubmit="return false;">
            <input id="homeworkname" type="text"  onkeyup="restrict('homeworkname');" />
            <input id="homeworkdesc" type="text"  onkeyup="restrict('homeworkdesc');" />
            <input id="homeworkshortdesc" type="text"  onkeyup="restrict('homeworkshortdesc');" />
            <input id="homeworkurl" type="text"  onkeyup="restrict('homeworkurl');" />
            <input id="tododate" type="date"  />
            <select id="lessonid" required>
                <option value=""></option>
                <?php while($lessonrow = mysqli_fetch_array($lessonquery)){?>
                    <option value="<?php echo $lessonrow["id"];?>"><?php echo $lessonrow["lessonname"]."[".$code[$lessonrow["id"]]."]"; ?></option>
                <?php }; ?>
            </select>
            <select id="documentid" required>
                <option value=""></option>
                <?php while($docrow = mysqli_fetch_array($docquery)){?>
                    <option value="<?php echo $docrow["id"];?>"><?php echo $docrow["documentname"]."[".$codefromdocid[$docrow["id"]]."]"; ?></option>
                <?php }; ?>
            </select>
            <input type="submit"/>
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
                        <?php if($FIELDNAMErow[0] == "lessonid"){
                            echo("lesson");
                        } else if($FIELDNAMErow[0] == "documentid") {
                            echo("document");
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
                            <?php if($i === 6){
                                echo $les[$row[$i]]."[".$code[$row[$i]]."]";
                            }else if($i === 7) {
                                echo $document[$row[$i]]."[".$codefromdocid[$row[0]]."]";
                            } else{
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
        <p>No homework yet!</p>
    <?php };?>
</div>
<?php include_once("./../template_php/template_footer.php")?>
</body>
</html>
