<?php
include_once("../../config/config.php");
include_once("../../config/security.php");

include_once("models/user_model_marka.php");

class UserControllerMarka {
    public $model;
	public $items_per_page;
	
	public function __construct() {  
        $this->model = new UserModelMarka();
		$this->items_per_page = Config::ITEMS_PER_PAGE;
    }
	
	

	public function editUsermarka() {
		if (isset($_GET['id'])) { // Edit User
			if (!empty($_POST)) {
				$this->model->deleteUserMarkalar($_GET['id']);
				$this->model->updateUserMarkalar($_GET['id']);
					
					// Set success flag
					$_SESSION['save_success'] = 1;
					
					if (isset($_POST['save-close'])) {
						header('Location: list.php');
						EXIT;
					} elseif (isset($_POST['save-new'])) {
						header('Location: edit.php');
						EXIT;
					}
				
			}
			
			// Show user detail
			$user = $this->model->getUserDetailMarka($_GET['id']);
		} 
		
		include 'views/editmarka.php';
	}




}