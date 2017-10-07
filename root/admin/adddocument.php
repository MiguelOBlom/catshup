<?php
include_once("./../sql/db_connection.php");
include_once("./../template_php/checkloginstatus.php");
if($admin !== true){
    header("location: ./../public/index.php");
}
?>
<?php
if(isset($_POST["documentname"])) {
    $documentname = preg_replace('#[^a-z0-9 ]#i', '', $_POST['documentname']);
    $documentdesc = mysqli_real_escape_string($db_connection, $_POST['documentdesc']);
    $documentshortdesc = mysqli_real_escape_string($db_connection, $_POST['documentshortdesc']);
    $documentsource = mysqli_real_escape_string($db_connection, $_POST['documentsource']);
    $lessonid = preg_replace('#[^0-9]#i', '', $_POST['lessonid']);
    $documentcategory = preg_replace('#[^a-z0-9]#i', '', $_POST['documentcategory']);
//Datacheck

    $dnsql = "SELECT id FROM document WHERE documentname='$documentname' LIMIT 1";
    $dnquery = mysqli_query($db_connection, $dnsql);
    $name_check = mysqli_num_rows($dnquery);

//Errors
    if ($documentname === "" || $documentdesc === "" || $documentshortdesc === "" || $documentsource === "" || $lessonid === "" || $documentcategory === ""){
        echo "The form submission is missing values.";
        exit();
    } else if ($name_check > 0){
        echo "Name already exists.";
        exit();
    } else {
        $sql = "INSERT INTO document (documentname, documentdesc, documentshortdesc, documentsource, lessonid, documentcategory, uploaddate) VALUES('$documentname', '$documentdesc', '$documentshortdesc', '$documentsource','$lessonid', '$documentcategory', now())";
        mysqli_query($db_connection, $sql);

        echo "send_success";
        exit();
    }
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/catshup/root/css.css"/>
    <script src="/catshup/root/js/js.js"></script>
    <script src="/catshup/root/js/ajax.js"></script>
    <script src="send/document.js"></script>

</head>
<body>
<?php include_once("./../template_php/template_header.php")?>
<div id="Content">
    <a href="./panel.php">Back to the panel.</a>
    <?php
    $TBLsql = "SELECT * FROM document";
    $FIELDNAMEsql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='document'";
    $TBLquery= mysqli_query($db_connection, $TBLsql);
    $FIELDNAMEquery = mysqli_query($db_connection, $FIELDNAMEsql);
    $TBLItemCount = mysqli_num_rows($TBLquery);
    $TBLFieldCount = mysqli_num_fields($TBLquery);
    ?>
    <?php
    $les = [];
    $lessql = "SELECT id, lessonname FROM lesson";
    $lesquery = mysqli_query($db_connection, $lessql);
    while($index = mysqli_fetch_array($lesquery, MYSQLI_NUM)){
        $les[$index[0]] = $index[1];
    };

    $code = [];
    $codesql = "SELECT lesson.id, course.coursecode FROM lesson INNER JOIN course ON lesson.courseid = course.id";
    $codequery = mysqli_query($db_connection, $codesql);
    while($index = mysqli_fetch_array($codequery, MYSQLI_NUM)){
        $code[$index[0]] = $index[1];
    }
    ?>
    <?php
    $FORMquery = mysqli_query($db_connection, $lessql);
    $FORMcount = mysqli_num_rows($lesquery);
    $CATsql = "SELECT id, category FROM documentcategory";
    $CATquery = mysqli_query($db_connection, $CATsql);
    $CATcount = mysqli_num_rows($CATquery);
    ?>
    <?php if($FORMcount <= 0){ ?>
            <p>No lessons yet, add them in the lessons tab.</p>
        <?php } else if($CATcount <= 0){ ?>
            <p>No document categories yet, add them in the document categories tab. </p>
        <?php } else {  ?>
        <form id="document" onsubmit="return false;">
            <input id="documentname" type="text"  onkeyup="restrict('documentname');" maxlength="40"/>
            <input id="documentdesc" type="text" onkeyup="restrict('documentdesc');" maxlength="10"/>
            <input id="documentshortdesc" type="text"  onkeyup="restrict('documentshortdesc');" />
            <input id="documentsource" type="text"  onkeyup="restrict('documentsource');" />
            <select id="lesson" required>
                <option value=""></option>
                <?php while($FORMrow = mysqli_fetch_array($FORMquery)){?>
                    <option value="<?php echo $FORMrow["id"]; ?>"><?php echo $FORMrow["lessonname"]."[".$code[$FORMrow["id"]]."]"; ?></option>
                <?php };?>
            </select>
            <select id="documentcategory" required>
                <option value=""></option>
                <?php while($CATrow = mysqli_fetch_array($CATquery)){?>
                    <option value="<?php echo $CATrow["category"]; ?>"><?php echo $CATrow["category"]; ?></option>
                <?php };?>
            </select>
            <input id="documentbutton" type="submit" onclick="documentsend();"/>
            <span id="status"></span>
        </form>
    <?php }?>
        <?php if($TBLItemCount > 0){?>
        <table>
            <tr>
                <?php while($FIELDNAMErow = mysqli_fetch_array($FIELDNAMEquery)){?>
                    <th>
                        <?php if($FIELDNAMErow[0] == "lessonid"){
                            echo("lesson");
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
                            <?php if($i === 5){
                                echo $les[$row[$i]]."[".$code[$row[$i]]."]";
                            }else{
                                echo $row[$i];
                            }; ?>
                        </td>
                    <?php };?>
                </tr>
            <?php }; ?>
        </table>
    <?php } else { ?>
        <p>No documents yet!</p>
    <?php };?>
</div>
<?php include_once("./../template_php/template_footer.php")?>
</body>
</html>
