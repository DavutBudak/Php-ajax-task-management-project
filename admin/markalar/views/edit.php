<?php
$id = 0;
$markaadi = '';
$image = '';

if (isset($user)) {
	if ($user) {
		$id = filter_var( $user['id'], FILTER_SANITIZE_NUMBER_INT);
		$markaadi = $user['markaadi'];
		$image = $user['image'];
	}
}

if (isset($_POST) && $_POST) {
	$markaadi = $_POST['markaadi'];
	
	if ($_FILES['image']['name']) {
		$image = $_FILES['image']['name'];

	}
	
}
?>



<?php
if (isset($_SESSION['save_success'])) {
    if ($_SESSION['save_success'] == 1) {
        ?>
        <div class="alert alert-success">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
		Marka Başarıyla Kaydedildi.
        </div>
        <?php
    } elseif ($_SESSION['save_success'] == 'requiredname') {
        ?>
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
          Marka Adı Veya Logosu Boş Bırakılamaz.
        </div>
        <?php
    }
     elseif ($_SESSION['save_success'] == 'ppimagevalidsize') {
        ?>
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
           Marka fotoğrafı için geçerli bir dosya seçmelisiniz (Maksimum 4MB, JPG, JPEG, PNG uzantılarına izin verilir).
        </div>
        <?php
    }


    unset($_SESSION['save_success']);
}
?>










<form class="form-horizontal" action="edit.php<?php echo $id ? ('?id=' . $id) : ''; ?>" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Marka Adı<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<input type="text" name="markaadi" class="form-control" value="<?php echo $markaadi; ?>" required >
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Post Görselleri</label>
		<div class="col-sm-8">
			<input type="file" name="image"  id="fileInput"   onchange="checkTotalFileSize()"  class="filestyle"  data-placeholder="<?php echo $image ? $image : "Toplam dosya boyutu 4 MB'ı geçemez. - Desteklenen dosya türleri: PNG, JPG, JPEG"; ?>" accept=".jpg, .jpeg, .png" >
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



<script>
function checkTotalFileSize() {
    var fileInput = document.getElementById('fileInput');

    if (fileInput.files.length > 0) {
        var totalFileSize = 0;
        var maxSize = 4 * 1024 * 1024;

        for (var i = 0; i < fileInput.files.length; i++) {
            totalFileSize += fileInput.files[i].size;
        }

        if (totalFileSize > maxSize) {
            alert('Dosya boyutu çok büyük. (Max 4 MB)');
            fileInput.value = '';
        }
    }
}
</script>
