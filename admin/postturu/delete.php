<?php
session_start();
// Kullanıcının rolünü kontrol et
include('../yetkikontrol.php');

include_once("controllers/marka_controller.php");

$user = new UserController();
$user->deleteUser($_GET['id']);
?>