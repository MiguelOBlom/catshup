<?php
include_once("./../sql/db_connection.php");
include_once("./../template_php/checkloginstatus.php");
if($admin !== true){
    header("location: ./../public/index.php");
}
?>
<?php
if(isset($_POST["homeworkname"])) {
    $homeworkname = preg_replace('#[^a-z0-9 ]#i', '', $_POST['homeworkname']);
    $homeworkdesc = mysqli_real_escape_string($db_connection, $_POST['homeworkdesc']);
    $homeworkshortdesc = mysqli_real_escape_string($db_connection, $_POST['homeworkshortdesc']);
    $homeworkurl = mysqli_real_escape_string($db_connection, $_POST['homeworkurl']);
    $tododate = mysqli_real_escape_string($db_connection, $_POST['tododate']);
    $lessonid = preg_replace('#[^0-9]#i', '', $_POST['lessonid']);
    $documentid = preg_replace('#[^0-9]#i', '', $_POST['documentid']);
//Datacheck

    $hnsql = "SELECT id FROM homework WHERE homeworkname='$homeworkname' LIMIT 1";
    $hnquery = mysqli_query($db_connection, $hnsql);
    $name_check = mysqli_num_rows($hnquery);

    $husql = "SELECT id FROM homework WHERE homeworkurl='$homeworkurl' LIMIT 1";
    $huquery = mysqli_query($db_connection, $husql);
    $url_check = mysqli_num_rows($huquery);

    $lisql = "SELECT id FROM homework WHERE lessonid='$lessonid' LIMIT 1";
    $liquery = mysqli_query($db_connection, $lisql);
    $lessonid_check = mysqli_num_rows($liquery);

    $disql = "SELECT id FROM homework WHERE documentid='$documentid' LIMIT 1";
    $diquery = mysqli_query($db_connection, $lisql);
    $documentid_check = mysqli_num_rows($diquery);

//Errors
    if ($homeworkname === "" || $homeworkdesc === "" || $homeworkshortdesc === "" || $tododate === "" || $lessonid === "" || $documentid === ""){
        echo "The form submission is missing values, homeworkurl isn't nescesary.";
        exit();
    } else if ($name_check > 0){
        echo "Name already exists.";
        exit();
    } else if ($url_check > 0){
        echo "URL already exists.";
        exit();
    } else if ($lessonid_check > 0){
        echo "The selected lesson already has homework assigned to it.";
        exit();
    } else if ($documentid_check > 0){
        echo "The selected document has already been assigned to another homeworks.";
        exit();
    } else {
        $sql = "INSERT INTO homework (homeworkname, homeworkdesc, homeworkshortdesc, homeworkurl, tododate, lessonid, documentid) VALUES('$homeworkname', '$homeworkdesc', '$homeworkshortdesc', '$homeworkurl', '$tododate', '$lessonid', '$documentid')";
        mysqli_query($db_connection, $sql);

        echo "send_success";
        exit();
    }
    exit();
}
?>
<?php
// select headers and count for table
$FIELDNAMEsql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='homework'";
$FIELDNAMEquery = mysqli_query($db_connection, $FIELDNAMEsql);
$TBLsql = "SELECT * FROM homework";
$TBLquery= mysqli_query($db_connection, $TBLsql);
$TBLItemCount = mysqli_num_rows($TBLquery);
$TBLFieldCount = mysqli_num_fields($TBLquery);
// select lessonname by lessonid
$les = [];
$lessql = "SELECT homework.lessonid, lesson.lessonname FROM homework INNER JOIN lesson ON homework.lessonid = lesson.id";
$lesquery = mysqli_query($db_connection, $lessql);
while($index = mysqli_fetch_array($lesquery, MYSQLI_NUM)){
    $les[$index[0]] = $index[1];
}
//select lessonname by id
$lesid =[ ];
$lesidsql = "SELECT id, lessonname FROM lesson";
$lesidquery = mysqli_query($db_connection, $lesidsql);
while($index = mysqli_fetch_array($lesidquery, MYSQLI_NUM)){
    $lesid[$index[0]] = $index[1];
}
// select coursecode by lessonid
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

//select documentname by id
$document = [];
$documentsql = "SELECT id, documentname FROM document";
$documentquery = mysqli_query($db_connection, $documentsql);
while($index = mysqli_fetch_array($documentquery, MYSQLI_NUM)){
    $document[$index[0]] = $index[1];
};
// select lessonname by id from lesson
$lesson = [];
$lessonsql = "SELECT id, lessonname FROM lesson WHERE id NOT IN(SELECT lessonid FROM homework)";
$lessonquery = mysqli_query($db_connection, $lessonsql);

// select coursename by id from course
$Coursesql = "SELECT id, coursename FROM course";
$Coursequery = mysqli_query($db_connection, $Coursesql);
$Coursecount = mysqli_num_rows($Coursequery);

$soledocsql = "SELECT id, documentname, lessonid FROM document WHERE id NOT IN (SELECT documentid FROM homework)";
$soledocquery = mysqli_query($db_connection, $soledocsql);
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css"/>
    <script src="/catshup/root/js/js.js"></script>
    <script src="/catshup/root/js/ajax.js"></script>
    <script src="send/homework.js?v=2"></script>
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
                <?php while($docrow = mysqli_fetch_array($soledocquery)){?>
                    <option value="<?php echo $docrow["id"];?>"><?php echo $docrow["documentname"]."[".$lesid[$docrow["lessonid"]]."(".$codefromdocid[$docrow["id"]].")"."]"; ?></option>
                <?php }; ?>
            </select>
            <input id="homeworkbutton" type="submit" onclick="homeworksend();"/>
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
                                echo $document[$row[$i]]."[".$codefromdocid[$row[$i]]."(".$les[$row["lessonid"]].")"."]";
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
