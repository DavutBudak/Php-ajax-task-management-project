

<aside class="left-side sidebar-offcanvas">
	<section class="sidebar">
		<!-- User -->
		<div class="user-panel">
			<div class="pull-left image">

			<?php if (empty($ppimage)): ?>
    <!-- Eğer ppimage boş veya null ise -->
    <img src="<?php echo $relative_url; ?>images/temp/user.png" class="img-circle" alt="User Image" />
<?php else: ?>
    <!-- Eğer ppimage dolu ise -->
    <img src="<?php echo $relative_url; ?>admin/user/uploads/<?php echo $ppimage; ?>" class="img-circle" alt="User Image" />
<?php endif; ?>
			</div>
			<div class="pull-left info">
				<p>Merhaba, <?php echo isset($_SESSION['user']) ? $_SESSION['user']['name'] : ''; ?></p>
			</div>
		</div>
	
		
		<!-- Menu -->
		<ul class="sidebar-menu">

<!--
		<?php if($_SESSION['user']['role'] == 1){  ?>
			<li <?php echo $page == 'timetable' ? 'class="active"' : ''; ?>>
				<a href="<?php echo $relative_path; ?>timetableadmin/list.php">
					<i class="fa fa-calendar"></i> <span>Takvimler</span>
				</a>
			</li>
			<?php } ?> -->

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
		</ul>

	


		
				<!-- User Menu -->
				<ul class="sidebar-menu">


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
}


}


Database::disconnect();

	  ?>




</ul>



 
</section>
</aside>

