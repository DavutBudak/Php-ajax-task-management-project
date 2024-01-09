<?php
include_once("controllers/marka_controller.php");

$user = new UserController();
$user->statusUser($_GET['id']);
?>