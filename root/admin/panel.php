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
    <a href="addcourse.php">Courses</a>
    <a href="adddocument.php">Documents</a>
    <a href="addhomework.php">Homeworks</a>
    <a href="addlecturer.php">Lecturers</a>
    <a href="addlesson.php">Lessons</a>
</div>
<?php include_once("./../template_php/template_footer.php")?>
</body>
</html>
