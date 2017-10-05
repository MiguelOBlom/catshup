<?php
include_once("./../sql/db_connection.php");
include_once("./../template_php/checkloginstatus.php");
if($admin !== true){
    header("location: ./../public/index.php");
}
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
            <select name="lesson" required>
                <option value=""></option>
                <?php while($FORMrow = mysqli_fetch_array($FORMquery)){?>
                    <option value="<?php echo $FORMrow["id"]; ?>"><?php echo $FORMrow["lessonname"]."[".$code[$FORMrow["id"]]."]"; ?></option>
                <?php };?>
            </select>
            <select name="documentcategory" required>
                <option value=""></option>
                <?php while($CATrow = mysqli_fetch_array($CATquery)){?>
                    <option value="<?php echo $CATrow["id"]; ?>"><?php echo $CATrow["category"]; ?></option>
                <?php };?>
            </select>
            <input id="documentcategory" type="text"  onkeyup="restrict('courseurl');" />
            <input type="submit"/>
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
