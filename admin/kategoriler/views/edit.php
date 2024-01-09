<?php

// Eğer category_id parametresi varsa ve sayısal bir değer ise doğrulama yap
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $marka_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Veritabanı bağlantısını oluşturun
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // categories tablosundan marka_id'yi çek
    $sql = "SELECT marka_id FROM categories WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute([$marka_id]);
    $result = $q->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $marka_id = filter_var($result['marka_id'], FILTER_SANITIZE_NUMBER_INT);

        // kullanici_markalar tablosundan kullanici_id'leri çek
        $sql = "SELECT kullanici_id FROM kullanici_markalar WHERE marka_id = ?";
        $q = $pdo->prepare($sql);
        $q->execute([$marka_id]);
        $kullanici_idler = $q->fetchAll(PDO::FETCH_COLUMN);

        // $_SESSION['user']['id'] ile eşleşen kullanıcı ID var mı kontrol et
        if (in_array($_SESSION['user']['id'], $kullanici_idler ) || $_SESSION['user']['role'] == 1) {
            // Doğrulama başarılı, işlemi gerçekleştir
            // Buraya ilgili işlemi ekleyebilirsiniz
        } else {
            // Erişim hatası
            header("Location: unauthorized.php"); // Yetkili kullanıcı sadece kendi profilini düzenleyebilir
            exit();
            // İsteğe bağlı olarak kullanıcıyı başka bir sayfaya yönlendirebilirsiniz
            // header('Location: hata.php');
            // exit();
        }
    } else {
        // Belirtilen marka_id bulunamadı hatası
        header("Location: unauthorized.php"); // Yetkili kullanıcı sadece kendi profilini düzenleyebilir
            exit();
        // İsteğe bağlı olarak kullanıcıyı başka bir sayfaya yönlendirebilirsiniz
        // header('Location: hata.php');
        // exit();
    }

    // Veritabanı bağlantısını kapatılır (Database::disconnect() fonksiyonu kullanılarak)
    Database::disconnect();
} else {
    // category_id parametresi eksik veya geçersizse hata mesajı göster
    // İsteğe bağlı olarak kullanıcıyı başka bir sayfaya yönlendirebilirsiniz
    // header('Location: hata.php');
    // exit();
}


$id = 0;
$name = '';
$start_date = '';
$end_date = '';
@@$marka_id = filter_var($_GET['marka_id'], FILTER_SANITIZE_NUMBER_INT);

if (isset($user)) {
	if ($user) {
		$id = filter_var($user['id'], FILTER_SANITIZE_NUMBER_INT);
		$name = $user['name'];
		$start_date = $user['start_date'];
		$end_date = $user['end_date'];
		@@$marka_id = filter_var($user['marka_id'], FILTER_SANITIZE_NUMBER_INT);

	}
}

if (isset($_POST) && $_POST) {
	$name = $_POST['name'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	@@$marka_id = filter_var($_POST['marka_id'], FILTER_SANITIZE_NUMBER_INT);


}



?>





<?php if (isset($_SESSION['save_success'])) { ?>
	<div class="alert alert-success">
		<button data-dismiss="alert" class="close close-sm" type="button">
			<i class="fa fa-times"></i>
		</button>
		Takvim Başarıyla Kayıt Edildi.
	</div>
	<?php unset($_SESSION['save_success']); ?>
<?php } ?>

<form class="form-horizontal" action="edit.php<?php echo $id ? ('?id=' . $id) : ''; ?>" method="post" id="myForm">

<input type="hidden" name="marka_id" class="form-control" value="<?php echo $marka_id; ?>"  >


	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Takvim Adı<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<input type="text" name="name" class="form-control" value="<?php echo $name; ?>" required >
		</div>
	</div>





	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Başlangıç Tarihi<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<input type="text" name="start_date" class="form-control date-picker" placeholder="Select Date"  id="custom-date"  value="<?php echo $start_date; ?>" >

		</div>
	</div>


	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Bitiş Tarihi<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<input type="text" name="end_date" class="form-control date-picker" placeholder="Select Date" id="custom-date2"   value="<?php echo $end_date; ?>">

		</div>
	</div>


	<?php
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT id, markaadi FROM markalar";
$q = $pdo->prepare($sql);
$q->execute();
$markalar = $q->fetchAll(PDO::FETCH_ASSOC);
Database::disconnect();
?>

<?php if($_SESSION['user']['role'] == 1 && empty($marka_id)): ?>
    <div class="form-group">
        <label class="col-lg-2 col-sm-2 control-label">Marka<span class="star">&nbsp;*</span></label>
        <div class="col-sm-8">
        <select style="width: 100%; height: 34px;" id="marka" name="marka_id">
    <?php foreach ($markalar as $marka): ?>
        <option value="<?php echo $marka['id']; ?>" <?php echo ($marka['id'] == $marka_id) ? 'selected' : ''; ?>>
            <?php echo $marka['markaadi']; ?>
        </option>
    <?php endforeach; ?>
</select>
        </div>
    </div>
<?php endif; ?>




		<div class="form-group">
		<div class="col-lg-offset-2 col-lg-8">
			<button name="save" type="submit" class="btn btn-info"><i class="fa fa-edit"></i> Kaydet</button>
			<button name="save-close" type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Kaydet & Çık</button>
			<button name="save-new" type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Kaydet & Yeni</button>
            <?php
            echo '<a class="btn btn-default" href="https://ajaxcalender.clicksuslabs.com/admin/timetable/list.php?id=' . urlencode($marka_id).'"><i class="fa fa-times"></i> İptal</a>'
            ?>

		</div>
	</div>

	<input type="hidden" id="relative_url" value="../../" />
</form>

