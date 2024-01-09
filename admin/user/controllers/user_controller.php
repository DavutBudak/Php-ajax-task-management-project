<?php
include_once("../../config/config.php");
include_once("../../config/security.php");

include_once("models/user_model.php");


class UserController {
    public $model;
	public $items_per_page;
	
	public function __construct() {  
        $this->model = new UserModel();
		$this->items_per_page = Config::ITEMS_PER_PAGE;
    }
	
	public function displayUserList() {
		// Show the user list
		$users = $this->model->getUserList();
		include 'views/list.php';
	}
	








	public function editUsermarka() {
		if (isset($_GET['id'])) { // Edit User
			if (!empty($_POST)) {
				$this->model->updateUserMarkalar($_GET['id']);
				
					
					if (isset($_POST['save-close'])) {
						header('Location: list.php');
						EXIT;
					} elseif (isset($_POST['save-new'])) {
						header('Location: edit.php');
						EXIT;
					}
				
			}
			
			// Show user detail
			$user = $this->model->getUserDetail($_GET['id']);
		} 
		
		include 'views/editmarka.php';
	}








	public function editUser() {
		if (isset($_GET['id']) && $_GET['id']) { // Edit User
			if (!empty($_POST)) {
				if ($this->model->checkUser($_GET['id'], 'username')) {
					   $_SESSION['save_success'] = 'usernameuniq';
                    ob_end_clean();
                    header('Location: edit.php?id=' . $_GET['id']);

						exit;
				} elseif ($this->model->checkUser($_GET['id'], 'email')) {
				$_SESSION['save_success'] = 'mailuniq';
                    ob_end_clean();
                    header('Location: edit.php?id=' . $_GET['id']);

						exit;
				} elseif ($_POST['password'] && ($_POST['password'] != $_POST['password2'])) {
				  $_SESSION['save_success'] = 'passwordreplace';
                                      ob_end_clean();

                    header('Location: edit.php?id=' . $_GET['id']);


						exit;

				} else {


					// Update user
					$this->model->updateUser($_GET['id']);
					

					if (isset($_POST['save-close'])) {
						header('Location: list.php');
						EXIT;
					} elseif (isset($_POST['save-new'])) {
						header('Location: edit.php');
						EXIT;
					}
				}
			}
			
			// Show user detail
			$user = $this->model->getUserDetail($_GET['id']);
		} else { // New User
			if (!empty($_POST)) {
				if ($this->model->checkUser(0, 'username')) {
                    $_SESSION['save_success'] = 'usernameuniq';
                    ob_end_clean();
                    header('Location: edit.php?id=' . $_GET['id']);

						exit;

				} elseif ($this->model->checkUser(0, 'email')) {
                    $_SESSION['save_success'] = 'mailuniq';
                    ob_end_clean();
                    header('Location: edit.php?id=' . $_GET['id']);

						exit;
				} elseif ($_POST['password'] != $_POST['password2']) {
                    $_SESSION['save_success'] = 'passwordreplace';
                    ob_end_clean();
                    header('Location: edit.php?id=' . $_GET['id']);


						exit;
					
				} else {
					// Create new user
					$id = $this->model->newUser();
					
					// Set success flag
					$_SESSION['save_success'] = 1;
					
					ob_end_clean();
					if (isset($_POST['save-close'])) {
						header("Location: list.php");
						EXIT;
					} elseif (isset($_POST['save-new'])) {
						header('Location: edit.php');
						EXIT;
					} else {
						header('Location: edit.php?id=' . $id);
						EXIT;
					}
				}
			}
		}
		
		include 'views/edit.php';
	}
	





	


	public function deleteUser($id) {
		if (isset($_GET['id'])) {
			// Delete user
			$this->model->deleteUser($id);
			
			// Set success flag
			$_SESSION['delete_success'] = 1;
		}
		
		header('Location: list.php');
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