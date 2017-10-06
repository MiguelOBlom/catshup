<?php
include_once("./../sql/db_connection.php");
include_once("./../template_php/checkloginstatus.php");
if($admin !== true){
    header("location: ./../public/index.php");
}
?>
<?php
if(isset($_POST["category"])) {
    $category = preg_replace('#[^a-z0-9]#i', '', $_POST['category']);
    //Datacheck

    $catsql = "SELECT id FROM documentcategory WHERE category='$category' LIMIT 1";
    $catquery = mysqli_query($db_connection, $catsql);
    $cat_check = mysqli_num_rows($catquery);

    //Errors
    if ($category === ""){
        echo "The form submission is missing values.";
        exit();
    } else if ($cat_check > 0){
        echo "Category already exists.";
        exit();
    } else {
        $sql = "INSERT INTO documentcategory (category) VALUES('$category')";
        mysqli_query($db_connection, $sql);

        echo "send_success";
        exit();
    }
    exit();
}
?>
<?php
$TBLsql = "SELECT * FROM documentcategory";
$FIELDNAMEsql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='documentcategory'";
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
    <script src="./send/documentcategory.js"></script>

</head>
<body>
<?php include_once("./../template_php/template_header.php")?>
<div id="Content">
    <a href="./panel.php">Back to the panel.</a>
    <form id="course" onsubmit="return false;">
        <input id="category" type="text" onkeyup="restrict('category');" maxlength="40"/>
        <input id="lecturerbutton" type="submit" onclick="documentcategorysend();"/>
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
        <p>No categories yet!</p>
    <?php };?>
</div>
<?php include_once("./../template_php/template_footer.php")?>
</body>
</html>
