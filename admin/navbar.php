<?php
// Check session login
if (empty($_SESSION['user'])) {
	// If not login, redirect to the index page
	header('Location: ' . $relative_path . 'index.php');
	EXIT;
} else {
	if (isset($_SESSION['login_time'])){
		$login_session_duration = 900; // 15 minutes
		$current_time = time();
		if (((time() - $_SESSION['login_time']) > $login_session_duration)) { // Timeover
			// Unset session
			unset($_SESSION['user']);
			unset($_SESSION['login_time']);
				 
			// Redirect to the index page 
			header('Location: ' . $relative_path . 'index.php');
			EXIT;
		} else {
			$_SESSION['login_time'] = time();
		}
	}
}

	@@include_once('../config/database.php');

$gelenuserid = $_SESSION['user']['id'];
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT ppimage FROM users WHERE id = ?";
$q = $pdo->prepare($sql);
$q->execute([$gelenuserid]);
$userdata = $q->fetch(PDO::FETCH_ASSOC);
$ppimage = $userdata['ppimage'];
Database::disconnect();


?>
    <link href="<?php echo $relative_url; ?>assets/css/navbarrespons.css" rel="stylesheet" type="text/css" />





<nav class="nav_news">
    
        <div id="logo" style="color:white;"> <a style="display: inline !important;" href="<?php echo $relative_url; ?>admin"> <i class="fa fa-calendar" aria-hidden="true"></i>  Clicks'us Takvim </a> </div>
        <div id="logoutdiv" style="color:white;"> <a style="display:inline !important;" href="<?php echo isset($_SESSION['user']) ? $relative_path . 'user/edit.php?id=' . $_SESSION['user']['id'] : ''; ?>"> <i class="fa fa-user"></i> </a>  	<a  onclick="return confirm('Çıkış Yapmak İstediğinize Emin misiniz?');" style="display:inline !important;" href="<?php echo $relative_path . 'logout.php'; ?>"> <i class="fa fa-sign-out"></i> </a> </div>

        <label for="drop" class="toggle"> <i class="fa fa-bars"></i> Menü</label>
        <input type="checkbox" id="drop" />
            <ul class="menu">

                <?php if($_SESSION['user']['role'] == 1){  ?>
			<li <?php echo $page == 'user' ? 'class="active"' : ''; ?>>
				<a href="<?php echo $relative_path; ?>user/list.php">
				<i class="fa fa-users"></i> <span>Kullanıcılar</span>
				</a>
			</li>

			<li <?php echo $page == 'post' ? 'class="active"' : ''; ?>>
				<a href="<?php echo $relative_path; ?>postturu/list.php">
					<i class="fa fa-quote-right"></i> <span>Post Türleri</span>
				</a>
			</li>



			<li <?php echo $page == 'markalar' ? 'class="active"' : ''; ?>>
				<a href="<?php echo $relative_path; ?>markalar/list.php">
					<i class="fa fa-copyright"></i> <span>Markalar</span>
				</a>
			</li>


			<li <?php echo $page == 'kategoriler' ? 'class="active"' : ''; ?>>
				<a href="<?php echo $relative_path; ?>kategoriler/list.php">
					<i class="fa fa-eye"></i> <span>Takvimler</span>
				</a>
			</li>

			<?php } ?>



             <?php

			$pdo = Database::connect();


			 if($_SESSION['user']['role'] != 1){

			$kullaniciId = $_SESSION['user']['id'];


// Kullanıcının seçtiği markaları çek
$sql = "SELECT * FROM kullanici_markalar WHERE kullanici_id = ?";
$q = $pdo->prepare($sql);
$q->execute([$kullaniciId]);

// Sonuçları al
$seciliMarkalar = $q->fetchAll(PDO::FETCH_ASSOC);

// Şimdi $seciliMarkalar dizisinde kullanıcının seçtiği markalar var.
// Bu diziyi foreach döngüsüyle istediğiniz şekilde kullanabilirsiniz:

foreach ($seciliMarkalar as $marka) {
   ?>
   <li>
   <a href="https://ajaxcalender.clicksuslabs.com/admin/timetable/list.php?id=<?php echo $marka['marka_id'] ?>">
	   <i class="fa fa-calendar"></i> <span><?php echo $marka['marka_adi']; ?></span>
   </a>
</li>
<?php
}

}

elseif($_SESSION['user']['role'] == 1){





// Kullanıcının seçtiği markaları çek
$sql = "SELECT DISTINCT marka_adi, marka_id FROM kullanici_markalar WHERE id";
$q = $pdo->prepare($sql);
$q->execute();

// Sonuçları al
$seciliMarkalar = $q->fetchAll(PDO::FETCH_ASSOC);

// Şimdi $seciliMarkalar dizisinde kullanıcının seçtiği markalar var.
// Bu diziyi foreach döngüsüyle istediğiniz şekilde kullanabilirsiniz:

foreach ($seciliMarkalar as $marka) {
   ?>
   <li>
   <a href="https://ajaxcalender.clicksuslabs.com/admin/timetableadmin/list.php?id=<?php echo $marka['marka_id'] ?>">
	   <i class="fa fa-calendar"></i> <span><?php echo $marka['marka_adi']; ?></span>
   </a>
</li>
<?php

}}
Database::disconnect();

	  ?>

               

            </ul>
        </nav>