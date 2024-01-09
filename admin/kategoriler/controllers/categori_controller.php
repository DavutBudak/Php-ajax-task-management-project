<?php
include_once("../../config/config.php");
include_once("../../config/security.php");
include_once("models/categori_model.php");

class UserController {
    public $model;
	public $items_per_page;

	public function __construct() {
        $this->model = new CategoriModel();
		$this->items_per_page = Config::ITEMS_PER_PAGE;
    }

	public function displayUserList() {
		// Show the user list
		$users = $this->model->getUserList();
		include 'views/list.php';
	}

	public function editUser() {
		if (isset($_GET['id'])) { // Edit User
			if (!empty($_POST)) {

                $geridonmarka = $_POST['marka_id'];
					$this->model->updateUser($_GET['id']);

					// Set success flag
					$_SESSION['save_success'] = 1;

					if (isset($_POST['save-close']) && $_SESSION['user']['role'] == 2) {

								header('Location: https://ajaxcalender.clicksuslabs.com/admin/timetable/list.php?id=' . urlencode($geridonmarka));

						EXIT;
					}

                    elseif (isset($_POST['save-close']) && $_SESSION['user']['role'] == 1) {


								header('Location: https://ajaxcalender.clicksuslabs.com/admin/timetableadmin/list.php?id=' . urlencode($geridonmarka));

						EXIT;
					}


                    elseif (isset($_POST['save-new'])) {

						header('Location: edit.php?marka_id='.$geridonmarka);
						EXIT;
					}

			}

			// Show user detail
			$user = $this->model->getUserDetail($_GET['id']);

		} else { // New User
			if (!empty($_POST)) {
                $geridonmarka = $_POST['marka_id'];

					// Create new user
					$id = $this->model->newUser();

					// Set success flag
					$_SESSION['save_success'] = 1;

					ob_end_clean();
					
                    	if (isset($_POST['save-close'])) {
								header('Location: https://ajaxcalender.clicksuslabs.com/admin/timetable/list.php?id=' . urlencode($geridonmarka));
						EXIT;
					}


                    elseif (isset($_POST['save-new']) ) {
                    header('Location: edit.php?marka_id='.$geridonmarka);

						EXIT;
					} else {
						header('Location: edit.php?id=' . $id);

						EXIT;
					}

			}
		}

		include 'views/edit.php';
	}

	public function deleteUser($gelenid,$gelencatid,$timetablefile) {

		if (isset($gelenid) && isset($gelencatid)) {
			// Delete user
			$this->model->deleteUser($gelenid);

			// Set success flag
			$_SESSION['delete_success'] = 1;
		}

		header('Location: https://ajaxcalender.clicksuslabs.com/admin/'.$timetablefile.'/list.php?id=' . urlencode($gelencatid));
				EXIT;
	}

	public function statusUser($id) {
		if (isset($_GET['id'])) {
			// Delete user
			$this->model->statusUser($id);
		}

		ob_end_clean();
		header('Location: list.php');
		EXIT;
	}
}