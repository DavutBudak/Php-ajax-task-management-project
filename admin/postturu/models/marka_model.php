<?php
include_once('../../config/database.php');

class MarkaModel {
	public function getUserList() {
		$pdo = Database::connect();
		if (isset($_GET['keyword'])) {
			$sql = 'SELECT * FROM post_turu WHERE tur_adi LIKE "%' . $_GET['keyword'] . '%" ORDER BY id ASC';
		} else {
			$sql = 'SELECT * FROM post_turu ORDER BY id ASC';
		}
		$users  = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		
		Database::disconnect();
		
		return $users;
	}
	
	public function getUserDetail($id) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM post_turu where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$user = $q->fetch(PDO::FETCH_ASSOC);
		
		Database::disconnect();
		
		return $user;
	}
	
	public function checkUser($markaadi) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// Markanın mevcut olup olmadığını ve ID'sinin farklı olduğunu kontrol et
		$sql = "SELECT * FROM post_turu WHERE tur_adi = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($markaadi));
		
		$user = $q->fetch(PDO::FETCH_ASSOC);
		
		Database::disconnect();
		
		return $user;
	}
	
	public function updateUser($id) {
		// update data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		if ($_POST['tur_adi']) {	
			
			$sql = "UPDATE post_turu SET tur_adi = ?, tur_renk = ?, tur_desc = ? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($_POST['tur_adi'], $_POST['tur_renk'], $_POST['tur_desc'], $id));
		} 

	
		
		Database::disconnect();
	}
	
	public function newUser() {
		
		
		// Create new user
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO post_turu (tur_adi, tur_renk , tur_desc) VALUES (?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($_POST['tur_adi'], $_POST['tur_renk'], $_POST['tur_desc']));
		
		Database::disconnect();
		
		return $pdo->lastInsertId();
	}
	
	public function deleteUser($id) {
		// delete data from markalar table
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM post_turu WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
	
		
		Database::disconnect();
	}
	
}