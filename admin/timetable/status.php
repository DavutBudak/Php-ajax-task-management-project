<?php
include_once("controllers/timetable_controller.php");

$timetable = new TimetableController();
$timetable->statusTimetable($_GET['id']);
?>