<?php
include_once('../../config/database.php');

class TimetableModelAdmin {
	public $type_ar = array('1'=>'day', '2'=>'week', '3'=>'month', '4'=>'year');
    public function uploadImage() {
    $msg = '';
    $uploaded_images = [];
    $total_size_limit = 20 * 1024 * 1024; // 20 MB limit
    $filevalid = 0;
    $filemaxsize = 0;

    if (isset($_FILES['image']['name']) && count($_FILES['image']['name']) > 0) {
        $image_files = $_FILES['image'];
        $allowed_extensions = array('jpeg', 'jpg', 'png', 'gif', 'mp4');

        foreach ($image_files['name'] as $key => $image_name) {
            $file_name = $image_files['name'][$key];
            $file_tmp = $image_files['tmp_name'][$key];
            $file_size = $image_files['size'][$key];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($file_size != 0) {
                if (!in_array($file_ext, $allowed_extensions)) {
                    $filevalid = 1;
                    break;
                } elseif ($file_size > $total_size_limit) {
                    $filemaxsize = 1;
                    break;
                } else {
                   			// Dosya adını düzenleme (isteğe bağlı olarak farklı bir yöntem seçebilirsiniz)
$file_name = uniqid() . '_' . str_replace(' ', '', $file_name);


// Dosyayı yükle (hedef dizini değiştir)
move_uploaded_file($file_tmp, '../timetable/images/' . $file_name);

// Yüklenen dosya adını listeye ekle
$uploaded_images[] = $file_name;
                }
            }
        }

        if ($filevalid) {
            return array('filevalid' => $filevalid);
        }

        if ($filemaxsize) {
            return array('filemaxsize' => $filemaxsize);
        }

        if (empty($msg)) {
            // Veritabanına kaydetmek için $uploaded_images dizisini kullanabilirsiniz.
            // implode fonksiyonunu kullanarak dosya adlarını virgülle ayırabilirsiniz.
            $image_list = implode(', ', array_map(function ($item) {
                return "'" . $item . "'";
            }, $uploaded_images));

            return array('msg' => 'success', 'image_list' => $image_list);
        }
    } else {
        // Dosya seçilmediyse sessizce devam et
        return array('msg' => 'success', 'image_list' => '');
    }

    return array('msg' => $msg, 'image_list' => '');
}



















	public function getTimetableList() {
		$pdo = Database::connect(); 
		
		// Keyword parametresi varsa veya kullanıcı admin ise
		if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
			$keyword = '%' . $_GET['keyword'] . '%';
			$sql = 'SELECT * FROM timetables WHERE name LIKE ? ORDER BY id ASC';
			$statement = $pdo->prepare($sql);
			$statement->execute([$keyword]);
		} 
		
		elseif (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && !isset($_GET['category_id']) && empty($_GET['category_id'])) {
			// Eğer id parametresi varsa ve geçerli bir sayı ise sadece o markanın timetables'ını getir
			$markaId = $_GET['id'];
			$sql = 'SELECT * FROM categories WHERE marka_id = ? ORDER BY id ASC';
			$statement = $pdo->prepare($sql);
			$statement->execute([$markaId]);   //////// BURADA KATEGORİLERİ ÇAĞIRT EN MANTIKLISI EĞER KATEGORİ İD VARSA ALTTAKİ SORGU ÇALIŞSIN ZATEN 
		}
		
		elseif (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['category_id']) && !empty($_GET['category_id']) && is_numeric($_GET['category_id'])) {
			// Eğer id parametresi varsa ve geçerli bir sayı ise sadece o markanın timetables'ını getir
			$markaId = $_GET['id'];
			$categoryId = $_GET['category_id'];
			$sql = 'SELECT * FROM timetables WHERE marka_id = ? AND category_id = ? ORDER BY id ASC';
			$statement = $pdo->prepare($sql);
			$statement->execute([$markaId, $categoryId]);
		}
		
		else {
			// Eğer hiçbir koşul sağlanmazsa hata mesajı göster
			echo '<div class="alert alert-block alert-danger" style="text-align:center" >Timetables Bulunamadı </div>';
			exit();
		}
		
		$timetables = $statement->fetchAll(PDO::FETCH_ASSOC);
		Database::disconnect();
		return $timetables;
	}






	public function getTimetableDetail($id) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM timetables where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$timetable = $q->fetch(PDO::FETCH_ASSOC);
		
		Database::disconnect();
		
		return $timetable;
	}
	
	public function updateTimetable($id, $image_list) {
		// update data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		// Eğer yeni bir dosya yüklenmişse
		if (!empty($_FILES['image']['name'][0])) {

			$sql = "UPDATE timetables SET name = ?, image = ?, date = ?, day = ?, color = ?, description = ? , postturu_id = ? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($_POST['name'], $image_list, $_POST['date'], $_POST['day'], $_POST['color'], $_POST['description'], $_POST['postturu_id'], $id));
		} else {
			// Eğer yeni bir dosya yüklenmemişse, mevcut resmi güncelleme işlemine dahil etme
			$sql = "UPDATE timetables SET name = ?, date = ?, day = ?, color = ?, description = ? , postturu_id = ? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($_POST['name'], $_POST['date'], $_POST['day'], $_POST['color'], $_POST['description'], $_POST['postturu_id'], $id));
		}
	
		Database::disconnect();
	}
	public function newTimetable($image_list) {	
		// Create new timetable
		@@$gelenmarkaid = $_GET['id'];
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO timetables (name, image, date, day, color, description,marka_id,category_id,postturu_id) values(?, ?, ?, ?, ?, ?, ? , ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($_POST['name'], $image_list, $_POST['date'], $_POST['day'],$_POST['color'], $_POST['description'], $_POST['marka_id'], $_POST['category_id'],$_POST['postturu_id'] ));
		
		Database::disconnect();
	
	}
	
	public function deleteTimetable($id) {
		// delete data
		$geridon=$_GET['markaid'];
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM timetables WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		
		Database::disconnect();
	}
	
	public function statusTimetable($id) {
		// update data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE timetables SET state = 1 - state WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		
		Database::disconnect();
	}
}