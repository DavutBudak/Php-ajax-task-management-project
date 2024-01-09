<?php



include('../yetkikontrol.php');


if (isset($user)) {
	if ($user) {
		$id = filter_var($user['id'], FILTER_SANITIZE_NUMBER_INT);
		$name = $user['name'];
		$username = $user['username'];
		$email = $user['email'];
		$role = $user['role'];
	}
}
 
?>

<?php if (isset($_SESSION['save_success'])) { ?>
	<div class="alert alert-success">
		<button data-dismiss="alert" class="close close-sm" type="button">
			<i class="fa fa-times"></i>
		</button>
		Seçili markalar başarıyla kaydedildi.
	</div>
	<?php unset($_SESSION['save_success']); ?>
<?php } ?>

<form class="form-horizontal" action="editmarka.php<?php echo $id ? ('?id=' . $id) : ''; ?>" method="post">
	

	



	<div class="form-group">
    <div class="col-sm-12">
        <?php
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Markaları çek
        $qq = $pdo->prepare("SELECT * FROM markalar");
        $qq->execute();

        // Markaları işle
        $markalar = $qq->fetchAll(PDO::FETCH_ASSOC);
        $selectedMarkalar = $this->model->getSelectedMarkalar($id);

        ?>

		
<div class="tiva-user-list table-responsive">
	<table class="table table-hover tiva-table">
		<tbody >
			<tr class="first-row">
				<th>Seç</th>
				<th >Marka Adı</th>
				<th>Marka Logosu</th>
			
			</tr>
		
		
				<?php  foreach ($markalar as $marka)  { 
					
					$isChecked = in_array($marka['id'], $selectedMarkalar) ? "checked" : "";

					?>
					<tr>
						<td>     <input type='checkbox' id='marka_<?php echo $marka['id']; ?>' name='markalar[]' value='<?php echo $marka['id']; ?>' <?php echo $isChecked ? 'checked' : ''; ?>> </td>
						<td> <label for='marka_<?php echo $marka['id']; ?>'> <?php echo $marka['markaadi']; ?> </label> </td>
						<td><img style="width:120px; object-fit:contain; height:80px;" src='../markalar/uploads/<?php echo $marka['image']; ?>' alt='Marka Resmi'> </td>
					</tr>
				<?php } ?>
			
			<tr class="last-row"><td colspan="10"></td></tr>
		</tbody>
	</table>
</div>





    </div>
</div>





	

	<div class="form-group">
		<div class="ml-5 col-lg-12">
			<button name="save" type="submit" class="btn btn-info"><i class="fa fa-edit"></i> Kaydet</button>
			<a class="btn btn-default" href="list.php"><i class="fa fa-times"></i> İptal</a>
		</div>
	</div>
	
	
</form>