<?php
include_once("../../config/config.php");
include_once("../../config/security.php");
include_once("models/timetable_model.php");

class TimetableController {
    public $model;
	public $items_per_page;
	
	public function __construct() {
        $this->model = new TimetableModel();
		$this->items_per_page = Config::ITEMS_PER_PAGE;
    }

	public function displayTimetableList() {
		// Show the timetable list
		$timetables = $this->model->getTimetableList();
		include 'views/list.php';
	} 
	
	public function editTimetable() {

   // update data
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Kullanıcı girişlerini kontrol et
if(isset($_GET['id']) && isset($_GET['categoryedit_id'])) {
    $id = $_GET['id'];
    $categoryedit_id = $_GET['categoryedit_id'];

 // Yeni değerler için kontrol yap
    $sql_check = "SELECT COUNT(*) AS count FROM timetables WHERE id = :id AND category_id = :category_id";
    $stmt_check =  $pdo->prepare($sql_check);
    $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_check->bindParam(':category_id', $categoryedit_id, PDO::PARAM_INT);

    $stmt_check->execute();
    $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);


} elseif(isset($_GET['newid']) && isset($_GET['category_id'])) {
    $id = $_GET['newid'];
    $categoryedit_id = $_GET['category_id'];

    // Yeni değerler için kontrol yap
    $sql_check = "SELECT COUNT(*) AS count FROM categories WHERE marka_id = :marka_id AND id = :category_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':marka_id', $id, PDO::PARAM_INT);
    $stmt_check->bindParam(':category_id', $categoryedit_id, PDO::PARAM_INT);

    $stmt_check->execute();
    $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);



    if ($result_check && $result_check['count'] > 0) {
        // Geçerli kayıt bulundu, devam et
    } else {

        header('location:/unauthorized.php');
        exit;
    }
} else {

    header('location:/unauthorized.php');
    exit;
}
// Sorguyu hazırla ve çalıştır
$sql = "SELECT marka_id FROM timetables WHERE id = :id AND category_id = :category_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->bindParam(':category_id', $categoryedit_id, PDO::PARAM_INT);

$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
    @$timetableiptal = $result['marka_id'];

if ($result || isset($_GET['newid'])) {
    @$marka_idtimetable = $result['marka_id'];
} else {

    header('location:/unauthorized.php');
    exit;
}
Database::disconnect();


		// Edit Timetable
		if (isset($_GET['id']) && $_GET['id']) {
			if (!empty($_POST)) {
				$msg = 'success';
				$image_list = '';
	
				if (!empty($_FILES['image']['name'][0])) {
					// Have image upload

					$result = $this->model->uploadImage();
					$msg = $result['msg'];
					$image_list = $result['image_list'];
                      $filevalid = $result['filevalid'];
                $filemaxsize = $result['filemaxsize'];

				}
	
				if ($msg == 'success') {
					$this->model->updateTimetable($_GET['id'], $image_list);
					// Set success flag 
					$_SESSION['save_success'] = 1;
                    					ob_end_clean();

                                      if (isset($_POST['save-close'])) {

						header('Location: list.php?id='.$marka_idtimetable.'&category_id='.$categoryedit_id);
						EXIT;
					} elseif (isset($_POST['save-new'])) {

						header('Location: edit.php?newid='.$marka_idtimetable.'&category_id='.$categoryedit_id);
						EXIT;
					}else{header('Location: edit.php?id=' . $_GET['id'] . '&categoryedit_id=' . $_GET['categoryedit_id']);exit;}

					exit;
				}

                    elseif ($filevalid == 1){
                      $_SESSION['save_success'] = 'filevalid';
                      					ob_end_clean();

					header('Location: edit.php?id=' . $_GET['id'] . '&categoryedit_id=' . $_GET['categoryedit_id']);
					exit;
                }
                elseif ($filemaxsize == 1){
                       $_SESSION['save_success'] = 'filemaxsize';
                      					ob_end_clean();
					header('Location: edit.php?id=' . $_GET['id'] . '&categoryedit_id=' . $_GET['categoryedit_id']);
					exit;
                }

                else {
					echo '<div class="alert alert-block alert-danger">' . $msg . '</div>';
				}
			}
	
			// Show timetable detail
			$timetable = $this->model->getTimetableDetail($_GET['id']);
		} else { // New Timetable
			if (!empty($_POST)) {
				// Upload image

				$result = $this->model->uploadImage();
				$msg = $result['msg'];
                @$filevalid = $result['filevalid'];
                @$filemaxsize = $result['filemaxsize'];
				@$image_list = $result['image_list'];
	
				if ($msg == 'success') {
				
					// Create new timetable
					$id = $this->model->newTimetable($image_list);
					// Set success flag
					$_SESSION['save_success'] = 1;
					ob_end_clean();





                       if (isset($_POST['save-new'])) {

						header('Location: edit.php?newid='. $_GET['newid'].'&category_id='.$categoryedit_id);
						EXIT;
					}else{header('Location: list.php?id=' . $_POST['marka_id'] . '&category_id=' . $_POST['category_id']);
					exit;}




                }

                elseif ($filevalid == 1){
                      $_SESSION['save_success'] = 'filevalid';
                      					ob_end_clean();

					header('Location: edit.php?newid=' . $_POST['marka_id'] . '&category_id=' . $_POST['category_id']);
					exit;
                }
                elseif ($filemaxsize == 1){
                       $_SESSION['save_success'] = 'filemaxsize';
                      					ob_end_clean();
					header('Location: edit.php?newid=' . $_POST['marka_id'] . '&category_id=' . $_POST['category_id']);
					exit;
                }
                else {
                    $_SESSION['save_success'] = 2;
					header('Location: edit.php?newid=' . $_POST['marka_id'] . '&category_id=' . $_POST['category_id']);
					exit;
					
				}
			} 
		}
	
		include 'views/edit.php';
	}
	
	public function deleteTimetable($id) {
		if (isset($_GET['id'])) {
			// Delete timetable
			$markageridon=$_GET['markaid'];
			$categorygeridon=$_GET['gelencategory_id'];

			var_dump($markageridon);

			var_dump($categorygeridon);
						
			$this->model->deleteTimetable($id);
			
			// Set success flag
			$_SESSION['delete_success'] = 1;
		}
		
		header('Location: list.php?id='.$markageridon.'&category_id='.$categorygeridon); //BUNU YAP 

		exit;	}
	
	public function statusTimetable($id) {
		if (isset($_GET['id'])) {
			// Delete timetable
			$this->model->statusTimetable($id);
		}
		
		ob_end_clean();
		header('Location: list.php');
		exit;
	}
}