

<?php
include_once('../config/database.php');

$submitted_username = '';
$alert_message = '';

if (!empty($_POST)) {
	$login_ok = false;
	
	// Get user by username
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT id, name, username, password, salt, email, role FROM users WHERE username = ? AND state = 1";
	$q = $pdo->prepare($sql);
	$q->execute(array($_POST['username']));
	$row = $q->fetch(PDO::FETCH_ASSOC);
	Database::disconnect();
	
	// Check password
	if ($row) { 
		$check_password = hash('sha256', $_POST['password'] . $row['salt']); 
		for ($round = 0; $round < 65536; $round++) { 
			$check_password = hash('sha256', $check_password . $row['salt']); 
		} 

		if ($check_password === $row['password']) { 
			$login_ok = true; 
		} 
	} 
         
	if ($login_ok) { // If the user logged in successfully
		// Unset password and salt
		unset($row['salt']);
		unset($row['password']);
		 
		// Store user info in session
		$_SESSION['user'] = $row;
		$_SESSION['login_time'] = time();
		 
		// Redirect the user to the index page. 
		ob_end_clean();
		header("Location: index.php");
		EXIT;
	} else { // Login failed
		// Alert message
		$alert_message = '<div class="alert alert-block alert-danger">Giriş başarısız oldu. Lütfen tekrar deneyin.</div>';
		 
		// Keep the username in login form
		$submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
	} 
}     
?>

<!DOCTYPE html>
<html lang="tr">
<head>
	<title>Clicksus Takvim</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="assetslogin/images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="assetslogin/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assetslogin/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assetslogin/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="assetslogin/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="assetslogin/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="assetslogin/css/util.css">
	<link rel="stylesheet" type="text/css" href="assetslogin/css/main.css">
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			
			<div class="wrap-login100">

				<div class="login100-pic js-tilt" data-tilt style="line-height: 265px;">
					<img src="assetslogin/images/logo-dark.png" alt="Logo" style="border-radius:30px;">
				</div>

				<form class="login100-form validate-form"  method="post" action="index.php" role="form" >
					<span class="login100-form-title">
						Giriş Yap
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Kullanıcı Adı Giriniz.">
						<input class="input100" type="text" name="username" placeholder="Kullanıcı Adı">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>


					<div class="wrap-input100 validate-input" data-validate = "Şifre Giriniz.">
						<input class="input100" type="password" name="password" placeholder="Şifre">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Giriş Yap
						</button>
					</div>

				

					<div class="text-center p-t-136">
						<adav class="txt2 adav" href="#">
						<?php echo $alert_message; ?>	

</adav>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
	<script src="assetslogin/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="assetslogin/vendor/bootstrap/js/popper.js"></script>
	<script src="assetslogin/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="assetslogin/vendor/select2/select2.min.js"></script>
	<script src="assetslogin/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
	<script src="assetslogin/js/main.js"></script>

</body>
</html>
