<?php 
if ($_SESSION['user']['role'] != 1) {
	header("Location: unauthorized.php"); // Yetkili kullanıcı sadece kendi profilini düzenleyebilir
    exit();
}
?>