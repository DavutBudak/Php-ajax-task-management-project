<?php
include_once('../../config/database.php');

class UserModelMarka {
	
	
	public function getUserDetailMarka($id) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM users where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$user = $q->fetch(PDO::FETCH_ASSOC);
		
		Database::disconnect();
		
		return $user;
	}
	

	


	public function updateUserMarkalar($id) {
		// update data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	
		if (isset($_POST['markalar']) && is_array($_POST['markalar']) && !empty($_POST['markalar'])) {
			$kullaniciId = $id; // Kullanıcı ID'si (örnek olarak)
			$selectedMarkalar = $_POST['markalar'];
		
			foreach ($selectedMarkalar as $markaId) {
				// Marka adını almak için marka_id'ye göre veritabanından sorgu yap
				$sqlMarkaAdi = "SELECT markaadi FROM markalar WHERE id = ?";
				$qMarkaAdi = $pdo->prepare($sqlMarkaAdi);
				$qMarkaAdi->execute([$markaId]);
				$markaAdi = $qMarkaAdi->fetch(PDO::FETCH_COLUMN);
		
				// Kullanici_markalar ilişki tablosuna veriyi ekle
				$sql = "INSERT INTO kullanici_markalar (kullanici_id, marka_id, marka_adi) VALUES (?, ?, ?)";
				$q = $pdo->prepare($sql);
				$q->execute([$kullaniciId, $markaId, $markaAdi]);
			}
		
			$_SESSION['success_message'] = "Seçili markalar başarıyla kaydedildi.";
		}
		
		Database::disconnect();
	}
	

	public function getSelectedMarkalar($kullaniciId) {
		$pdo = Database::connect();
		$sql = "SELECT marka_id FROM kullanici_markalar WHERE kullanici_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute([$kullaniciId]);
		$selectedMarkalar = $q->fetchAll(PDO::FETCH_COLUMN);
		Database::disconnect();
		return $selectedMarkalar;
	}




	public function deleteUserMarkalar($id) {
		// delete data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM kullanici_markalar WHERE kullanici_id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		
		Database::disconnect();
	}
	
	
}