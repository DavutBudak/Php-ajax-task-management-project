<?php
session_start();

include_once('controllers/marka_controller.php');
include('../yetkikontrol.php');

// Variables
$relative_url = '../../';
$relative_path = '../';
$page = 'post';

// Get controller
$user = new UserController();
?>

<!DOCTYPE html>
<html>
<!-- Header -->
<?php include $relative_path . 'header.php'; ?>
	  
<body class="admin-panel">
	<!-- Navbar -->
	<?php include $relative_path . 'navbar.php'; ?>
				
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<!-- Sidebar -->
		<?php include $relative_path . 'sidebar.php'; ?>
		
		<aside class="right-side">
			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<section class="panel">
							<header class="panel-heading"><i class="fa fa-user"></i>Post Türleri</header>
							<div class="panel-body">
								<?php $user->displayUserList(); ?>
							</div>
						</section>
					</div>
				</div>
			</section>
			
			<!-- Footer -->
			<?php include $relative_path . 'footer.php'; ?>
		</aside>
	</div>
</body>
</html>