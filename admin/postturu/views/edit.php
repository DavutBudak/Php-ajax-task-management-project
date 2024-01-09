<?php
$id = 0;
$tur_adi = '';
$tur_renk = "#ff0000";
$tur_desc ='';
if (isset($user)) {
	if ($user) {
		$id =  filter_var($user['id'], FILTER_SANITIZE_NUMBER_INT);
		$tur_adi = $user['tur_adi'];
		$tur_renk = $user['tur_renk'];
		$tur_desc = $user['tur_desc'];

	}
}

if (isset($_POST) && $_POST) {
	$tur_adi = $_POST['tur_adi'];
	$tur_renk = $_POST['tur_renk'];
	$tur_desc = $_POST['tur_desc'];

}
?>

<?php if (isset($_SESSION['save_success'])) { ?>
	<div class="alert alert-success">
		<button data-dismiss="alert" class="close close-sm" type="button">
			<i class="fa fa-times"></i>
		</button>
Post Türü Başarıyla Kaydedildi.
	</div>
	<?php unset($_SESSION['save_success']); ?>
<?php } ?>

<form class="form-horizontal" action="edit.php<?php echo $id ? ('?id=' . $id) : ''; ?>" method="post">
	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Post Türü Adı<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<input type="text" name="tur_adi" class="form-control" value="<?php echo $tur_adi; ?>" required >
		</div>
	</div>
	

	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Post Türü Açıklaması<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<input type="text" name="tur_desc" class="form-control" value="<?php echo $tur_desc; ?>" required >
		</div>
	</div>
	




<div class="form-group">
    <label class="col-lg-2 col-sm-2 control-label">Post Türü Rengi<span class="star">&nbsp;*</span></label>
    <div class="col-sm-8">
	<input type="color" id="favcolor" name="tur_renk" value="<?php echo $tur_renk; ?>" required>

    </div>
</div>









	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-8">
			<button name="save" type="submit" class="btn btn-info"><i class="fa fa-edit"></i> Kaydet</button>
			<button name="save-close" type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Kaydet & Çık</button>
			<button name="save-new" type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Kaydet & Yeni</button>
			<a class="btn btn-default" href="list.php"><i class="fa fa-times"></i> İptal</a>
		</div>
	</div>
	
	<input type="hidden" id="relative_url" value="../../" />
</form>