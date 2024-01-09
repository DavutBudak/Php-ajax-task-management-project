<?php
session_start();
include('../yetkikontrol.php');

include_once("controllers/timetable_controller.php");

$timetable = new TimetableController();
$timetable->deleteTimetable($_GET['id']);
?>