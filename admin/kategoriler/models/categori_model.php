<?php
include_once('../../config/database.php');

class CategoriModel {
	public function getUserList() {
		$pdo = Database::connect();
		if (isset($_GET['keyword'])) {
			$sql = 'SELECT * FROM categories WHERE name LIKE "%' . $_GET['keyword'] . '%" ORDER BY id ASC';
		} else {
			$sql = 'SELECT * FROM categories ORDER BY id ASC';
		}
		$users  = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		Database::disconnect();

		return $users;
	}

	public function getUserDetail($id) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM categories where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$user = $q->fetch(PDO::FETCH_ASSOC);

		Database::disconnect();

		return $user;
	}





	public function updateUser($id) {
		// update data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		if($_SESSION['user']['role'] == 1){
		if ($_POST['name']) {	
			
			$sql = "UPDATE categories SET name = ?, start_date = ?, end_date = ?, marka_id = ? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($_POST['name'], $_POST['start_date'], $_POST['end_date'] , $_POST['marka_id'], $id));
			
			
		}  }else{
			if ($_POST['name']) {	
			
				$sql = "UPDATE categories SET name = ?, start_date = ?, end_date = ? WHERE id = ?";
				$q = $pdo->prepare($sql);
				$q->execute(array($_POST['name'], $_POST['start_date'], $_POST['end_date'], $id));
				
			} 
		}

	
		
		Database::disconnect();
	}




	
	public function newUser() {
		
		
		// Create new user
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO categories (name, start_date, end_date, marka_id) VALUES (?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($_POST['name'], $_POST['start_date'], $_POST['end_date'], $_POST['marka_id']));
		


		Database::disconnect();
		
		return $pdo->lastInsertId();
	}
	
	public function deleteUser($id) {


		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM categories WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		
		$sql = "DELETE FROM timetables WHERE category_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));





		Database::disconnect();
	}
	
}