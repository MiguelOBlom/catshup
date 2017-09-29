<?php
include_once("db_connection.php");

$tbl_user = "CREATE TABLE user(
            id INT(11) NOT NULL AUTO_INCREMENT,
            username VARCHAR(20) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            userlevel ENUM('a','b','c','d') NOT NULL DEFAULT 'a',
            avatar VARCHAR(255) NULL,
            signup DATETIME NOT NULL,
            lastlogin DATETIME NOT NULL,
            notescheck DATETIME NOT NULL,
            activated ENUM('0','1')NOT NULL DEFAULT '0',
            PRIMARY KEY (id),
            UNIQUE KEY username (username,email)
              )";

$tbl_useroptions = "CREATE TABLE useroptions(
                    id INT(11) NOT NULL,
                    username VARCHAR(255) NOT NULL,
                    background VARCHAR(255) NOT NULL,
                    question VARCHAR(255) NULL,
                    answer VARCHAR(255) NULL,
                    PRIMARY KEY (id),
                    UNIQUE KEY username (username)
                    )";

$tbl_course = "CREATE TABLE course(
              id INT(11) NOT NULL AUTO_INCREMENT,
              coursename VARCHAR(255) NOT NULL,
              coursecode VARCHAR(10) NOT NULL,
              courseurl VARCHAR(255) NULL,
              lecturerid INT(11) NULL,
              PRIMARY KEY (id),
              UNIQUE KEY coursename (coursename)
              )";

$tbl_lecturer = "CREATE TABLE lecturer(
                id INT(11) NOT NULL AUTO_INCREMENT,
                lecturername VARCHAR(255) NOT NULL,
                lectureremail VARCHAR(255) NULL,
                lecturerroomno VARCHAR(20) NULL,
                PRIMARY KEY (id),
                UNIQUE KEY lecturername (lecturername)
                )";

$tbl_lesson = "CREATE TABLE lesson(
              id INT(11) NOT NULL AUTO_INCREMENT,
              courseid INT(11) NOT NULL,
              lessonname VARCHAR(255) NOT NULL,
              lessonurl VARCHAR(255) NULL,
              lessondate DATETIME NOT NULL,
              lessonroom VARCHAR(20) NOT NULL,
              PRIMARY KEY (id)
              )";

$tbl_document = "CREATE TABLE document(
                id INT(11) NOT NULL AUTO_INCREMENT,
                documentname VARCHAR(255) NOT NULL,
                documentdesc TEXT NULL,
                documentshortdesc TINYTEXT NULL,
                documentsource VARCHAR(255) NOT NULL,
                lessonid INT(11) NOT NULL,
                uploaddate DATETIME NOT NULL,
                documentcategory VARCHAR(30) NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY documentname (documentname, documentsource)
                )";

$tbl_homework = "CREATE TABLE homework(
                id INT(11) NOT NULL AUTO_INCREMENT,
                homeworkname VARCHAR(255) NOT NULL,
                homeworkdesc TEXT NULL,
                homeworkshortdesc TINYTEXT NULL,
                homeworkurl VARCHAR(255) NULL,
                courseid INT(11) NULL,
                documentid INT(11) NULL,
                tododate DATETIME NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY homeworkname (homeworkname)                
                )";

$tbl_todo = "CREATE TABLE todo(
            id INT(11) NOT NULL AUTO_INCREMENT,
            homeworkid INT(11) NOT NULL,
            userid INT(11) NOT NULL,
            done ENUM('0','1') NOT NULL DEFAULT '0',
            PRIMARY KEY (id)
            )";
//notifications , custom documents
function qtable($name, $dbcon, $x){
    $query = mysqli_query($dbcon, $x);
    if ($query){
        echo "<h3>".$name." table created successfully!</h3>";
    } else {
        echo "<h3>".$name." table wasn't created for some reason.</h3>";
    }
};

$tables = array(
                array("User",$tbl_user),
                array("Course",$tbl_course),
                array("Lecturer",$tbl_lecturer),
                array("Lesson", $tbl_lesson),
                array("Document", $tbl_document),
                array("Homework", $tbl_homework),
                array("ToDo", $tbl_todo),
                array("Useroptions",$tbl_useroptions)
                );

for($i = 0; $i < count($tables); $i++) {
    qtable($tables[$i][0], $db_connection, $tables[$i][1]);
}

?>