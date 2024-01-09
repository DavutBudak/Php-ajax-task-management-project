<?php
include_once("controllers/user_controller.php");

$user = new UserController();
$user->statusUser($_GET['id']);
?>