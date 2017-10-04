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
    $FORMsql = "SELECT id, coursename FROM course";
    $FORMquery = mysqli_query($db_connection, $FORMsql);
    $FORMcount = mysqli_num_rows($FORMquery);
    ?>
    <?php if($FORMcount > 0){?>
    <form id="lesson" onsubmit="return false;">
        <select>
            <option value=""></option>
            <?php while($FORMrow = mysqli_fetch_array($FORMquery)){?>
                <option value="<?php echo $FORMrow["id"];?>"><?php echo $FORMrow["coursename"]; ?></option>
            <?php }; ?>
        </select>
        <input id="lessonname" type="text"  onkeyup="restrict('lessonname');" />
        <input id="lessonurl" type="text"  onkeyup="restrict('lessonurl');" />
        <input id="lessondate" type="date"  />
        <input id="lessonroom" type="text"  onkeyup="restrict('lessonroom');" />
        <input type="submit"/>
        <span id="status"></span>
    </form>
    <?php } else {?>
        <p>There aren't any courses yet, add them in the course tab</p>
    <?php };?>
    <?php
    $TBLsql = "SELECT * FROM lesson";
    $FIELDNAMEsql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='lesson'";
    $TBLquery= mysqli_query($db_connection, $TBLsql);
    $FIELDNAMEquery = mysqli_query($db_connection, $FIELDNAMEsql);
    $TBLItemCount = mysqli_num_rows($TBLquery);
    $TBLFieldCount = mysqli_num_fields($TBLquery);
    ?>
    <?php
        $vak = [];
        $idsql = "SELECT id FROM course";
        $vaksql = "SELECT coursename FROM course";
        $vakquery = mysqli_query($db_connection, $vaksql);
        $idquery = mysqli_query($db_connection, $idsql);
        while($itemid = mysqli_fetch_array($idquery, MYSQLI_NUM)){

            $itemvak = mysqli_fetch_array($vakquery,MYSQLI_NUM);
            $vak[$itemid[0]] = $itemvak[0];
        };
    ?>
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
