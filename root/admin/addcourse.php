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
    $FORMsql = "SELECT id, lecturername FROM lecturer";
    $FORMquery = mysqli_query($db_connection, $FORMsql);
    $FORMcount = mysqli_num_rows($FORMquery);
    ?>
    <?php if($FORMcount > 0){?>
        <form id="course" onsubmit="return false;">
            <input id="coursename" type="text" onblur="checkcourse();" onkeyup="restrict('coursename');" maxlength="40"/>
            <input id="coursecode" type="text" onkeyup="restrict('coursecode');" maxlength="10"/>
            <input id="courseurl" type="text"  onkeyup="restrict('courseurl');" />
            <select name="lecturer" required>
                <option value=""></option>
                <?php while($FORMrow = mysqli_fetch_array($FORMquery)){?>
                <option value="<?php echo $FORMrow["id"]; ?>"><?php echo $FORMrow["lecturername"]; ?></option>
                <?php };?>
            </select>
            <input type="submit"/>
            <span id="status"></span>
        </form>
    <?php } else { ?>
        <p>No lecturers yet, add them in the lecturer's tab.</p>
    <?php };?>
    <?php
    $TBLsql = "SELECT * FROM course";
    $FIELDNAMEsql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='course'";
    $TBLquery= mysqli_query($db_connection, $TBLsql);
    $FIELDNAMEquery = mysqli_query($db_connection, $FIELDNAMEsql);
    $TBLItemCount = mysqli_num_rows($TBLquery);
    $TBLFieldCount = mysqli_num_fields($TBLquery);
    ?>
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
        <p>No courses yet!</p>
    <?php };?>
</div>
<?php include_once("./../template_php/template_footer.php")?>
</body>
</html>
