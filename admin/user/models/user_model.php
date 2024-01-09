<?php
include_once('../../config/database.php');

class UserModel {
	public function getUserList() {
		$pdo = Database::connect();
		if (isset($_GET['keyword'])) {
			$sql = 'SELECT * FROM users WHERE name LIKE "%' . $_GET['keyword'] . '%" ORDER BY id ASC';
		} else {
			$sql = 'SELECT * FROM users ORDER BY id ASC';
		}
		$users  = $pdo->query($sql)
				  ->fetchAll(PDO::FETCH_ASSOC);

		Database::disconnect();

		return $users;
	}

	public function getUserDetail($id) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM users where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$user = $q->fetch(PDO::FETCH_ASSOC);

		Database::disconnect();

		return $user;
	}

	public function checkUser($user_id, $param) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($param == 'username') {
			$sql = "SELECT * FROM users where username = ? AND id != ?";
			$q = $pdo->prepare($sql);
			$q->execute(array(@$_POST['username'], $user_id));
		} elseif ($param == 'email') {
			$sql = "SELECT * FROM users where email = ? AND id != ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($_POST['email'], $user_id));
		}

		$user = $q->fetch(PDO::FETCH_ASSOC);

		Database::disconnect();

		return $user;
	}



	public function updateUser($id) {

		// update data
		$roleuser = 2;
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if ($_POST['password']) {
			// Generate salt number
			$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));


			// Encrypt password
			$password = hash('sha256', $_POST['password'] . $salt);
			for($round = 0; $round < 65536; $round++) {
				$password = hash('sha256', $password . $salt);
			}


			$sql = "UPDATE users SET name = ?, password = ?, salt = ?  WHERE id = ?";

			$q = $pdo->prepare($sql);
			$q->execute(array($_POST['name'],  $password, $salt, $id));
			$_SESSION['save_success'] = 1;

        } elseif ($_SESSION['user']['role'] == 1) {
			$sql = "UPDATE users SET name = ?, username = ?, email = ?, role = ? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($_POST['name'], $_POST['username'], $_POST['email'], $_POST['role'], $id));

		} else {
			$sql = "UPDATE users SET name = ?,  role = ? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($_POST['name'],  $roleuser, $id));

		}

		// Profil fotoğrafı eklenmişse işlemleri gerçekleştir
		if (!empty($_FILES['ppimage']['name'])) {
			$originalFileName = $_FILES['ppimage']['name'];
			$randomString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 12);
			$imageFileName = $randomString . $originalFileName;
			$uploadDirectory = 'uploads/';

			// Dosya uzantısını kontrol et (Sadece belirli uzantılara izin ver)
			$allowedExtensions = ['jpg', 'jpeg', 'png'];
			$uploadedFileExtension = strtolower(pathinfo($_FILES['ppimage']['name'], PATHINFO_EXTENSION));

			// Dosya boyutunu kontrol et (Maksimum 4MB izin ver)
			$maxFileSize = 4 * 1024 * 1024; // 4MB
			$uploadedFileSize = $_FILES['ppimage']['size'];

			if ($uploadedFileSize <= $maxFileSize && in_array($uploadedFileExtension, $allowedExtensions)) {
				// Profil fotoğrafını belirtilen klasöre kaydet
				move_uploaded_file($_FILES['ppimage']['tmp_name'], $uploadDirectory . $imageFileName);

				// Veritabanını güncelle
				$sql = "UPDATE users SET ppimage = ? WHERE id = ?";
				$q = $pdo->prepare($sql);
				$q->execute(array($imageFileName, $id));

			} else {
				// Dosya boyutu veya uzantısı uygun değilse hata mesajı göster
                  $_SESSION['save_success'] = 'ppimagevalidsize';
                                      ob_end_clean();

 header('Location: edit.php?id=' . $_GET['id']);
				Database::disconnect();
exit;			}
		}

		Database::disconnect();
	}


	public function newUser() {
		// Generate salt number
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));

		// Encrypt password
		$password = hash('sha256', $_POST['password'] . $salt);
		for($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}

		// Create new user
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Profil fotoğrafı eklenmişse işlemi gerçekleştir
		if (!empty($_FILES['ppimage']['name'])) {
			$originalFileName = $_FILES['ppimage']['name'];
			$randomString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 12);
			$imageFileName = $randomString . $originalFileName;
			$uploadDirectory = 'uploads/';

			// Dosya uzantısını kontrol et (Sadece belirli uzantılara izin ver)
			$allowedExtensions = ['jpg', 'jpeg', 'png'];
			$uploadedFileExtension = strtolower(pathinfo($_FILES['ppimage']['name'], PATHINFO_EXTENSION));

			// Dosya boyutunu kontrol et (Maksimum 4MB izin ver)
			$maxFileSize = 4 * 1024 * 1024; // 4MB
			$uploadedFileSize = $_FILES['ppimage']['size'];

			if ($uploadedFileSize <= $maxFileSize && in_array($uploadedFileExtension, $allowedExtensions)) {
				// Profil fotoğrafını belirtilen klasöre kaydet
				move_uploaded_file($_FILES['ppimage']['tmp_name'], $uploadDirectory . $imageFileName);

				// Veritabanını güncelle
				$sql = "INSERT INTO users (name, username, password, salt, email, role, ppimage, state) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
				$q = $pdo->prepare($sql);
				$q->execute(array($_POST['name'], $_POST['username'], $password, $salt, $_POST['email'], $_POST['role'], $imageFileName, 1));
			} else {
				// Dosya boyutu veya uzantısı uygun değilse hata mesajı göster ve işlemi sonlandır
				    $_SESSION['save_success'] = 'ppimagevalidsize';
                                      ob_end_clean();

 header('Location: edit.php?id=' . $_GET['id']);
				Database::disconnect();
exit;		
			}
		} else {
			// Profil fotoğrafı eklenmemişse sadece kullanıcı bilgilerini ekleyin
			$sql = "INSERT INTO users (name, username, password, salt, email, role, state) VALUES (?, ?, ?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($_POST['name'], $_POST['username'], $password, $salt, $_POST['email'], $_POST['role'], 1));
		}

		Database::disconnect();

		return $pdo->lastInsertId();
	}









	public function deleteUser($id) {
		// delete data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM users WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));

		Database::disconnect();
	}

	public function statusUser($id) {
		// update data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE users SET state = 1 - state WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));

		Database::disconnect();
	}
}