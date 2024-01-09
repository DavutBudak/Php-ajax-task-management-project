<?php
include_once("controllers/categori_controller.php");

$user = new UserController();
$user->statusUser($_GET['id']);
?>