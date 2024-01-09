<?php
session_start();

// Variables
$relative_url = '../';
$relative_path = '';
$page = 'homepage';

ob_start();
?>




<!DOCTYPE html>
<html>



<!-- Header -->
<?php include $relative_path . 'header.php'; ?>

<?php if (empty($_SESSION['user'])) { // Not login ?>
	<?php include $relative_path . 'login.php'; ?>
<?php } else { ?>
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
								<header class="panel-heading">Clicksus Takvim</header>
								<div class="panel-body">
									
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
<?php } ?>


</html>