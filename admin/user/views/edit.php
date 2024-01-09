<?php



// Kullanıcının rolünü kontrol et
if ($_SESSION['user']['role'] != 1 && $_SESSION['user']['role'] != 2) {
    header("Location: unauthorized.php"); // Yetkisiz erişim durumunda yönlendirilecek sayfa
    exit();
}

$id = 0;
$name = '';
$username = '';
$email = '';
$role = 0;
$ppimage = '';

if (isset($user)) {
	if ($user) {
		$id =filter_var( $user['id'], FILTER_SANITIZE_NUMBER_INT);

		$name = $user['name'];
		$username = $user['username'];
		$email = $user['email'];
		$ppimage = $user['ppimage'];
		$role = $user['role'];
	}
}

if ($_SESSION['user']['role'] == 2 && $_SESSION['user']['id'] != $id) {
    header("Location: unauthorized.php"); // Yetkili kullanıcı sadece kendi profilini düzenleyebilir
    exit();
}

if (isset($_POST) && $_POST) {
	$name = $_POST['name'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$role = $_POST['role'];
	
	// Kullanıcının rolüne göre güncelleme işlemini gerçekleştir
	if ($_SESSION['user']['role'] == 1 || ($_SESSION['user']['role'] == 2 && $_SESSION['user']['id'] == $id)) {
		// Veritabanında kullanıcıyı güncelle
		// ...
		$_SESSION['save_success'] = true; // Başarılı güncelleme durumunda kullanıcıya mesaj göstermek için
		header("Location: edit.php?id=".$id); // Güncelleme işleminden sonra tekrar düzenleme sayfasına yönlendir
		exit();
	} else {
		header("Location: unauthorized.php"); // Yetkisiz erişim durumunda yönlendirilecek sayfa
    	exit();
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
		Kullanıcı Düzenleme İşlemi Başarıyla Tamamlandı.
        </div>
        <?php
    } elseif ($_SESSION['save_success'] == 'usernameuniq') {
        ?>
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
           Bu Kullanıcı Adı Daha Önceden Kullanıldı.
        </div>
        <?php
    }
    elseif ($_SESSION['save_success'] == 'mailuniq') {
        ?>
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
           Bu E-posta Adresi Daha Önceden Kullanıldı.
        </div>
        <?php
    }
    elseif ($_SESSION['save_success'] == 'passwordreplace') {
        ?>
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
           Şifre onaylama şifresiyle eşleşmiyor.
        </div>
        <?php
    }

     elseif ($_SESSION['save_success'] == 'ppimagevalidsize') {
        ?>
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
           Profil fotoğrafı için geçerli bir dosya seçmelisiniz (Maksimum 4MB, JPG, JPEG, PNG uzantılarına izin verilir).
        </div>
        <?php
    }


    unset($_SESSION['save_success']);
}
?>




<form class="form-horizontal" action="edit.php<?php echo $id ? ('?id=' . $id) : ''; ?>" method="post" enctype="multipart/form-data">

	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Ad<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<input type="text" name="name" class="form-control" value="<?php echo $name; ?>" required >
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Profil Fotoğrafı</label>
		<div class="col-sm-8">
			<input type="file" name="ppimage" id="fileInput"   onchange="checkTotalFileSize()" class="filestyle" data-placeholder="<?php echo $ppimage ? $ppimage : "Toplam dosya boyutu 4 MB'ı geçemez. - Desteklenen dosya türleri: PNG, JPG, JPEG"; ?>" accept=".jpg, .jpeg, .png">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Kullanıcı Adı<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<input type="text" <?php if ($_SESSION['user']['role'] == 1){ ?>  name="username" <?php } else{ echo "disabled"; } ?>  class="form-control" value="<?php echo $username; ?>" required >
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Şifre<?php echo !isset($user) ? '<span class="star">&nbsp;*</span>' : ''; ?></label>
		<div class="col-sm-8">
			<input type="password" autocomplete="off" name="password" class="form-control" <?php echo (!isset($user)) ? 'required' : ''; ?> >
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Şifre Onayı<?php echo !isset($user) ? '<span class="star">&nbsp;*</span>' : ''; ?></label>
		<div class="col-sm-8">
			<input type="password" autocomplete="off" name="password2" class="form-control" <?php echo (!isset($user)) ? 'required' : ''; ?> >
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">E-posta<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<input type="email" <?php if ($_SESSION['user']['role'] == 1){ ?>  name="email" <?php } else{ echo "disabled"; } ?>  class="form-control" value="<?php echo $email; ?>" required >

		</div>
	</div>
<?php if ($_SESSION['user']['role'] == 1){ ?> 

	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Rol<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<select <?php if ($id == 1) { echo 'disabled'; } ?> name="role" class="form-control" required>
				<option value="">-- Select --</option>
				<option <?php echo ($role == 1) ? 'selected' : ''; ?> value="1">Admin</option>
				<option <?php echo ($role == 2) ? 'selected' : ''; ?> value="2">Kullanıcı</option>
			</select>
		</div>
	</div>
	<?php }  ?>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-8">
			<button name="save" type="submit" class="btn btn-info"><i class="fa fa-edit"></i> Kaydet</button>
            <?php if($_SESSION['user']['role']  == 1) {?>
			<button name="save-close" type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Kaydet & Çık</button>
			<button name="save-new" type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Kaydet & Yeni</button>
			<a class="btn btn-default" href="list.php"><i class="fa fa-times"></i> İptal</a>
             <?php } ?>
		</div>
	</div>



	
	<input type="hidden" id="relative_url" value="../../" />
	<?php if ($id == 1) { ?>
		<input type="hidden" name="role" value="1" />
	<?php } ?>
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
