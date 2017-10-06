// check of gebruiker is ingelogd
//lijst van lessen --> les
//lijst van bestanden --> bestand
//lijst van huiswerk

SELECT usercourses.userid, lesson.lessonname, course.coursename FROM usercourses INNER JOIN lesson ON usercourses.courseid = lesson.courseid INNER JOIN course ON lesson.courseid = course.id WHERE userid = '2'