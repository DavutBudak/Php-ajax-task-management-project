<?php
session_start();

include_once("controllers/timetable_controller.php");

// Variables
$relative_url = '../../';
$relative_path = '../';
$page = 'timetable';

// Get controller
$timetable = new TimetableController();

ob_start();
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
							<header class="panel-heading"><i class="fa fa-folder-open"></i> <?php echo isset($_GET['id']) ? 'İÇERİĞİ DÜZENLE' : 'YENİ İÇERİK'; ?></header>
							<div class="panel-body">
								<?php $timetable->editTimetable(); ?>
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