<?php
include_once('../../config/database.php');

class MarkaModel {
	public function getUserList() {
		$pdo = Database::connect();
		if (isset($_GET['keyword'])) {
			$sql = 'SELECT * FROM markalar WHERE markaadi LIKE "%' . $_GET['keyword'] . '%" ORDER BY id ASC';
		} else {
			$sql = 'SELECT * FROM markalar ORDER BY id ASC';
		}
		$users  = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		
		Database::disconnect();
		
		return $users;
	}



	public function getUserDetail($id) {
		$pdo = Database::connect(); 
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM markalar where id = ?";
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
		$sql = "SELECT * FROM markalar WHERE markaadi = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($markaadi));
		
		$user = $q->fetch(PDO::FETCH_ASSOC);
		
		Database::disconnect();
		
		return $user;
	}
	
	public function updateUser($id) {
		if (!empty($_POST['markaadi'])) {
			$markaadi = $_POST['markaadi'];
			$uploadDirectory = 'uploads/';
			$maxFileSize = 4 * 1024 * 1024; // 4MB
			$allowedExtensions = ['jpg', 'jpeg', 'png'];
	
			if (!empty($_FILES['image']['name'])) {
				$uploadedFileSize = $_FILES['image']['size'];
				$uploadedFileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
	
				// Dosya boyutunu ve uzantısını kontrol et
				if ($uploadedFileSize <= $maxFileSize && in_array($uploadedFileExtension, $allowedExtensions)) {
					$originalFileName = $_FILES['image']['name'];
					$randomString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 12);
					$imageFileName = $randomString . $originalFileName;
	
					move_uploaded_file($_FILES['image']['tmp_name'], $uploadDirectory . $imageFileName);
	
					$pdo = Database::connect();
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$sql = "UPDATE markalar SET markaadi = ?, image = ? WHERE id = ?";
					$q = $pdo->prepare($sql);
					$q->execute(array($markaadi, $imageFileName, $id));
					Database::disconnect();
	
					$_SESSION['save_success'] = 1;
	
					if (isset($_POST['save-close'])) {
						header('Location: list.php');
						exit;
					} elseif (isset($_POST['save-new'])) {
						header('Location: edit.php');
						exit;
					}
				} else {
					// Dosya boyutu veya uzantısı uygun değilse hata mesajı göster
					echo '<div class="alert alert-block alert-danger">Dosya boyutu maksimum 4MB olmalı ve sadece JPG, JPEG veya PNG uzantılarına izin verilir.</div>';
					return false;
				}
			} else {
				$pdo = Database::connect();
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = "UPDATE markalar SET markaadi = ? WHERE id = ?";
				$q = $pdo->prepare($sql);
				$q->execute(array($markaadi, $id));
				Database::disconnect();
	
				$_SESSION['save_success'] = 1;
	
				if (isset($_POST['save-close'])) {
					header('Location: list.php');
					exit;
				} elseif (isset($_POST['save-new'])) {
					header('Location: edit.php');
					exit;
				}
			}
		} else {
			// Marka adı boşsa hata mesajı göster veya işlemi iptal et
			echo '<div class="alert alert-block alert-danger">Marka Adı zorunludur</div>';
			return false;
		}
	}
	

	public function newUser() {
		if (!empty($_POST['markaadi']) && !empty($_FILES['image']['name'])) {
			$markaadi = $_POST['markaadi'];
			$originalFileName = $_FILES['image']['name'];
			$uploadedFileSize = $_FILES['image']['size'];
			$uploadedFileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
			$maxFileSize = 4 * 1024 * 1024; // 4MB
			$allowedExtensions = ['jpg', 'jpeg', 'png'];
	
			// Dosya boyutunu ve uzantısını kontrol et
			if ($uploadedFileSize <= $maxFileSize && in_array($uploadedFileExtension, $allowedExtensions)) {
				$randomString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 12);
				$imageFileName = $randomString . $originalFileName;
	
				// Marka adını veritabanına kaydet
				$pdo = Database::connect();
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = "INSERT INTO markalar (markaadi, image) VALUES (?, ?)";
				$q = $pdo->prepare($sql);
				$q->execute(array($markaadi, $imageFileName));
				Database::disconnect();
	
				// Marka logosunu yükleyeceğiniz klasörü belirtin (örneğin: uploads/)
				$uploadDirectory = 'uploads/';
	
				// Marka logosunu belirtilen klasöre kaydet
				move_uploaded_file($_FILES['image']['tmp_name'], $uploadDirectory . $imageFileName);
				$_SESSION['save_success'] = 1;
	

	
				return $pdo->lastInsertId();
			} else {


				// Dosya boyutu veya uzantısı uygun değilse hata mesajı göster
				    $_SESSION['save_success'] = 'ppimagevalidsize';
                ob_end_clean();
                  header('Location: edit.php');

                                      exit;
			}
		} else {

			// Marka adı veya logo boşsa hata mesajı göster veya işlemi iptal et
			    $_SESSION['save_success'] = 'requiredname';
                ob_end_clean();
                  header('Location: edit.php');

                                      exit;

		}
	}


	public function deleteUser($id) {
		// delete data from markalar table
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM markalar WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
	
		// delete data from kullanici_markalar table
		$sql = "DELETE FROM kullanici_markalar WHERE marka_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
	
		// delete data from categories table
		$sql = "DELETE FROM categories WHERE marka_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
	
		// delete data from timetables table
		$sql = "DELETE FROM timetables WHERE marka_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
	
		Database::disconnect();
	}
	
}