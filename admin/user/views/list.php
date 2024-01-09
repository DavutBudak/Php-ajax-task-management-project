<?php
// Variables
$relative_url = '../../';
$relative_path = '../';
$list_page = 'admin/user/list.php';

// Pagination
$total_items = count($users);

$page = ceil($total_items/$this->items_per_page);
if (isset($_SESSION['page'])) {
	if ($_SESSION['page'] > $page) {
		$_SESSION['page'] = 1;
	}
	$cur_page = $_SESSION['page'];
	$offset = ($_SESSION['page'] - 1) * $this->items_per_page;
} else {
	$cur_page = 1;
	$offset = 0;
}
?>

<?php if (isset($_SESSION['save_success'])) { ?>
	<div class="alert alert-success">
		<button data-dismiss="alert" class="close close-sm" type="button">
			<i class="fa fa-times"></i>
		</button>
		Kullanıcı Düzenleme İşlemi Başarıyla Tamamlandı.
	</div>
	<?php unset($_SESSION['save_success']); ?>
<?php } ?>

<?php if (isset($_SESSION['delete_success'])) { ?>
	<div class="alert alert-success">
		<button data-dismiss="alert" class="close close-sm" type="button">
			<i class="fa fa-times"></i>
		</button>
		Kullanıcı Silme İşlemi Başarıyla Tamamlandı.
	</div>
	<?php unset($_SESSION['delete_success']); ?>
<?php } ?>

<div class="tiva-quiz-bar m-b-20">
	<form method="get" action="">
		<div class="input-group">
			<a class="btn btn-success pull-left" href="edit.php"><i class="fa fa-plus-circle"></i> Yeni</a>
			<div class="search-group pull-right">
				<input type="text" name="keyword" class="form-control input-sm" style="width: 250px;" placeholder="Search" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>">
				<div class="input-group-btn">
					<button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
				</div>	
			</div>
		</div>
	</form>
</div>

<div class="tiva-user-list table-responsive">
	<table class="table table-hover tiva-table">
		<tbody>
			<tr class="first-row">
				<th>Kullanıcı Adı</th>
				<th>Ad</th>
				<th>E-posta</th>
				<th>Rol</th>
				<th width="80" >Durum</th>
				<th width="170" class="text-center" >Düzenle</th>
				<th width="170"  class="text-center">Marka Ekle</th>
			</tr>
		
			<?php if ($users) { ?>
				<?php $users = array_slice($users, $offset, $this->items_per_page); ?>
				<?php $role = array('1'=>'Admin', '2'=>'User'); ?>
				<?php foreach ($users as $user) { ?>
					<tr>
						<td><a href="edit.php?id=<?php echo $user['id']; ?>"><?php echo $user['username']; ?></a></td>
						<td><?php echo $user['name']; ?></td>
						<td><?php echo $user['email']; ?></td>
						<td><?php echo $role[$user['role']]; ?></td>
						<td ><a href="status.php?id=<?php echo $user['id']; ?>"><img src="<?php echo $user['state'] == 1 ? '../../images/temp/tick.png' : '../../images/temp/close.png'; ?>" /></a></td>
						<td class="text-center">
							<a class="btn btn-primary" href="edit.php?id=<?php echo $user['id']; ?>"><i class="fa fa-edit"></i> Düzenle</a>
							<?php if ($user['id'] == 1) { ?>
								<a class="btn btn-danger btn-delete-admin" href=""><i class="fa fa-trash-o"></i> Sil</a>
							<?php } else { ?>
								<a class="btn btn-danger btn-delete" href="delete.php?id=<?php echo $user['id']; ?>"><i class="fa fa-trash-o"></i> Sil</a>
							<?php } ?>
						</td>	
						
						<td class="text-center"> 	<?php  if ($user['id'] != 1) { ?> 	<a class="btn btn-primary" href="editmarka.php?id=<?php echo $user['id']; ?>"><i class="fa fa-edit"></i> Ekle</a> <?php  } else {  ?>  ❌ <?php  } ?>	</td>

					</tr>
				<?php } ?>
			<?php } ?>
			
			<tr class="last-row"><td colspan="10"></td></tr>
		</tbody>
	</table>
</div>

<!-- Pagination -->
<?php include $relative_url . 'system/pagination.php'; ?>