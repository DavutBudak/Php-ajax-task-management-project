<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .bootstrap-filestyle.input-group {
    border: 1px dashed black;
    padding: 25px;
    border-radius: 10px;
}
</style>


<?php
$id = 0;
$name = '';
$image = '';
$date = date('d-m-Y');
$day = '';
$start_time = '';
$end_time = '';
$color = 1;
$description = '';
@@$idgelenmarka = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
@@$idgelenmarkanew = filter_var($_GET['newid'], FILTER_SANITIZE_NUMBER_INT);
@@$idgelencategory_id = filter_var($_GET['category_id'], FILTER_SANITIZE_NUMBER_INT);
@@$idgelencategoryedit_id = filter_var($_GET['categoryedit_id'], FILTER_SANITIZE_NUMBER_INT);



if (!empty($idgelenmarka)) {
	$idgelenmarkanew= NULL;
	// Veritabanı bağlantısını oluştur
$pdo = Database::connect();

// SQL sorgusu oluştur (prepared statement kullanarak SQL injection saldırılarına karşı koruma sağla)
$sql = 'SELECT start_date, end_date FROM categories WHERE id = :category_id';

// Prepared statement oluştur
$stmt = $pdo->prepare($sql);

// Prepared statement'e parametreleri bağla ve sorguyu çalıştır
$stmt->execute(array(':category_id' => $idgelencategoryedit_id));

// Sonuçları al 
$category_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Veritabanı bağlantısını kapat
Database::disconnect();

// Kategori verilerini doğrudan değişkene atadık
@@$start_dategelen = $category_data['start_date'];
@@$end_dategelen = $category_data['end_date'];
}




if (!empty($idgelenmarkanew)) {

	$idgelenmarka=NULL;

	// Veritabanı bağlantısını oluştur
$pdo = Database::connect();

// SQL sorgusu oluştur (prepared statement kullanarak SQL injection saldırılarına karşı koruma sağla)
$sql = 'SELECT start_date, end_date FROM categories WHERE id = :category_id';

// Prepared statement oluştur
$stmt = $pdo->prepare($sql);

// Prepared statement'e parametreleri bağla ve sorguyu çalıştır
$stmt->execute(array(':category_id' => $idgelencategory_id));

// Sonuçları al
$category_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Veritabanı bağlantısını kapat
Database::disconnect();

// Kategori verilerini doğrudan değişkene atadık
@@$start_dategelen = $category_data['start_date'];
@@$end_dategelen = $category_data['end_date'];


}






if (isset($timetable)) {
	if ($timetable) {
		$id = $timetable['id'];
		$name = $timetable['name'];
		$image = $timetable['image'];
		$date = $timetable['date'];
		$day = $timetable['day'];
		$color = $timetable['color'];
		$description = $timetable['description'];
    $postturu_id = $timetable['postturu_id'];

	}
}
 
if (isset($_POST) && $_POST) {
	$name = $_POST['name'];
	if ($_FILES['image']['name']) {
		$image = $_FILES['image']['name'];
	}
	$date = $_POST['date'];
	$marka_id = $_POST['marka_id'];
	@@$categori_idnew = $_POST['category_id'];
	$day = $timetable['day'];
	$color = $timetable['color'];
	$description = $timetable['description'];
    $postturu_id = $_POST['postturu_id'];

	
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
            Zaman çizelgesi başarıyla kaydedildi.
        </div>
        <?php
    } elseif ($_SESSION['save_success'] == 'filevalid') {
        ?>
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
            Lütfen Desteklenen bir dosya biçimi ekleyiniz. ( png - jpg - jpeg - mp4 )
        </div>
        <?php
    }
    elseif ($_SESSION['save_success'] == 'error') {
        ?>
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
            Geçici bir sorun sebebi ile kayıt yapılamadı.
        </div>
        <?php
    }
    elseif ($_SESSION['save_success'] == 'filemaxsize') {
        ?>
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
            Eklenen dosyaların toplam boyutu 20 MB aşamaz.
        </div>
        <?php
    }
    unset($_SESSION['save_success']);
}
?>



<?php

$pdo = Database::connect();
if (!empty($idgelenmarka)) {

	// $idgelenmarka'dan gelen değeri kullanarak timetables tablosundaki kaydı al
$sql = 'SELECT * FROM timetables WHERE id = ?';
$statement = $pdo->prepare($sql);
$statement->execute([$idgelenmarka]);
$timetableRow = $statement->fetch(PDO::FETCH_ASSOC);

if ($timetableRow) {
    // timetables tablosundan kayıt bulundu
    $kullanicininid = $_SESSION['user']['id'];
    // kullanici_markalar tablosunda eşleşen kayıt var mı kontrol et
    $sql = 'SELECT * FROM kullanici_markalar WHERE kullanici_id = ? AND marka_id = ?';
    $statement = $pdo->prepare($sql);
    $statement->execute([$kullanicininid,$timetableRow['marka_id']]);
    $matchingRow = $statement->fetch(PDO::FETCH_ASSOC);



    if ($matchingRow) {
        ?>









<form class="form-horizontal" action="edit.php<?php echo $id ? ('?id=' . $id) : 'dd'; ?>&categoryedit_id=<?php echo $idgelencategoryedit_id; ?>" method="post" enctype="multipart/form-data" id="myForm">

<div class="form-group">


		<label class="col-lg-2 col-sm-2 control-label">Post Adı<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<input type="text" name="name" class="form-control" value="<?php echo $name; ?>" required />
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Post Görselleri</label>
		<div class="col-sm-8">
		<?php
// Dosya yüklendi mi kontrolü
if (!empty($_FILES['image']['name'][0])) {
    // Dosya yüklendi, mevcut resmi placeholder olarak kullan
    $placeholder = is_array($image) ? implode(', ', $image) : $image;
} else {
    // Dosya yüklenmedi, placeholder olarak 'Fotoğraf Eklenmedi' kullan
    $placeholder = "Toplam dosya boyutu 20 MB'ı geçemez. - Desteklenen dosya türleri: PNG, JPG, JPEG ve MP4.";
}
?>


<input type="file" name="image[]"  id="fileInput"   onchange="checkTotalFileSize()"  class="filestyle" data-placeholder="<?php echo $placeholder; ?>" multiple accept=".jpg, .jpeg, .png, .mp4">




        </div>
	</div>

	<div class="form-group date-picker-wrap">
		<label class="col-lg-2 col-sm-2 control-label">Tarih</label>
		<div class="col-sm-8">
			<div class="input-group date">
				<input type="text" name="date"id="custom-date" class="form-control" placeholder="Tarih Seçin"  required value="<?php echo $date; ?>">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
		</div>
	</div>


	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Gün</label>
		<div class="col-sm-8">
			<select name="day" class="form-control" required>
				<option value="">-- Seçiniz --</option>
				<option value="monday" <?php echo ($day == 'monday') ? 'selected' : ''; ?> >Pazartesi</option>
				<option value="tuesday" <?php echo ($day == 'tuesday') ? 'selected' : ''; ?> >Salı</option>
				<option value="wednesday" <?php echo ($day == 'wednesday') ? 'selected' : ''; ?> >Çarşamba</option>
				<option value="thursday" <?php echo ($day == 'thursday') ? 'selected' : ''; ?> >Perşembe</option>
				<option value="friday" <?php echo ($day == 'friday') ? 'selected' : ''; ?> >Cuma</option>
				<option value="saturday" <?php echo ($day == 'saturday') ? 'selected' : ''; ?> >Cumartesi</option>
				<option value="sunday" <?php echo ($day == 'sunday') ? 'selected' : ''; ?> >Pazar</option>
			</select>
		</div>
	</div>
	
<div class="form-group">
    <label class="col-lg-2 col-sm-2 control-label" required>Post Türü</label>
    <div class="col-sm-8">
        <select name="color" required id="colorSelect" class="form-control">
            <?php
            $pdo = Database::connect();
            $sql = "SELECT * FROM post_turu";
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tur_adi = $row['tur_adi'];
                $tur_renk = $row['tur_renk'];
                $tur_id = $row['id'];
                echo '<option value="' . $tur_renk . '" data-id="' . $tur_id . '" ' . ($color == $tur_renk ? 'selected' : '') . '>' . $tur_adi . '</option>';
            }
            Database::disconnect();
            ?>
        </select>

        <!-- Döngü dışında hidden input -->
        <input type="hidden" name="postturu_id" id="selectedTurId" value="">
    </div>
</div>

	<div class="form-group">
    <label class="col-lg-2 col-sm-2 control-label">Post Türü Açıklaması </label>
    <div class="col-sm-8">
        <div class="animated-container">
            <label class="control-label" style="font-size: 14px; text-align: left !important;" id="turDescContainer"></label>
        </div>
    </div>
</div>



	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Post İçerik</label>
		<div class="col-sm-8">
			<textarea name="description" required class="description-field"><?php echo $description; ?></textarea>
		</div>
	</div>
	
<div class="form-group">
		<div class="col-lg-offset-2 col-lg-8">
			<button name="save" type="submit" class="btn btn-info"><i class="fa fa-edit"></i> Kaydet</button>
			<button name="save-close" type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Kaydet & Çık</button>
			<button name="save-new" type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Kaydet & Yeni</button>
<a class="btn btn-default" href="list.php?id=<?php echo $timetableiptal; ?>&category_id=<?php echo $idgelencategoryedit_id; ?>"><i class="fa fa-times"></i> İptal</a>
		</div>
	</div>

</form>






	<?php  } else {

        header("Location: unauthorized.php"); // Yetkisiz erişim durumunda yönlendirilecek sayfa
    exit();

    }
} else {
    // Kayıt bulunamazsa, hatalı bir durum oluştuğunu belirten bir hata mesajı göster veya yönlendirme yapabilirsiniz
    echo '<div class="alert alert-block alert-danger" style="text-align:center">Herhangi Bir Kayıt Bulunamadı.</div>';
    // veya yönlendirme yapabilirsiniz
    // header("Location: hata.php");
    // exit();
}

}



Database::disconnect();

if (!empty($idgelenmarkanew)) {
	?>
	
	<?php if (isset($_SESSION['save_success'])) { ?>
	<div class="alert alert-success">
		<button data-dismiss="alert" class="close close-sm" type="button">
			<i class="fa fa-times"></i>
		</button>
		Zaman çizelgesi başarıyla kaydedildi.
		</div>
	<?php unset($_SESSION['save_success']); ?>
<?php } ?>

<form class="form-horizontal" action="edit.php?newid=<?php echo $idgelenmarkanew; ?>&category_id=<?php echo $idgelencategory_id; ?>" method="post" enctype="multipart/form-data" id="myForm">
	<div class="form-group">
	<input type="hidden" readonly name="marka_id" class="form-control" value="<?php echo $idgelenmarkanew; ?>" required />
	<input type="hidden" readonly name="category_id" class="form-control" value="<?php echo $idgelencategory_id; ?>" required />

		<label class="col-lg-2 col-sm-2 control-label">Post Adı<span class="star">&nbsp;*</span></label>
		<div class="col-sm-8">
			<input type="text" name="name" class="form-control" value="<?php echo $name; ?>" required />
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Post Görselleri</label>
		<div class="col-sm-8">
			<input type="file" name="image[]"  id="fileInput"   onchange="checkTotalFileSize()"  class="filestyle" data-placeholder="<?php echo $image ? $image : "Toplam dosya boyutu 20 MB'ı geçemez. - Desteklenen dosya türleri: PNG, JPG, JPEG ve MP4."; ?>" multiple accept=".jpg, .jpeg, .png, .mp4">
		</div>
	</div>
	
	<div class="form-group date-picker-wrap">
		<label class="col-lg-2 col-sm-2 control-label">Tarih</label>
		<div class="col-sm-8">
			<div class="input-group date">
				<input type="text" required name="date"id="custom-date" class="form-control" placeholder="Tarih Seçin" value="<?php echo $date; ?>">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
		</div>
	</div>
			

	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Gün</label>
		<div class="col-sm-8">
			<select name="day" required class="form-control">
				<option value="">-- Seçiniz --</option>
				<option value="monday" <?php echo ($day == 'monday') ? 'selected' : ''; ?> >Pazartesi</option>
				<option value="tuesday" <?php echo ($day == 'tuesday') ? 'selected' : ''; ?> >Salı</option>
				<option value="wednesday" <?php echo ($day == 'wednesday') ? 'selected' : ''; ?> >Çarşamba</option>
				<option value="thursday" <?php echo ($day == 'thursday') ? 'selected' : ''; ?> >Perşembe</option>
				<option value="friday" <?php echo ($day == 'friday') ? 'selected' : ''; ?> >Cuma</option>
				<option value="saturday" <?php echo ($day == 'saturday') ? 'selected' : ''; ?> >Cumartesi</option>
				<option value="sunday" <?php echo ($day == 'sunday') ? 'selected' : ''; ?> >Pazar</option>
			</select>
		</div>
	</div>

<div class="form-group">
    <label class="col-lg-2 col-sm-2 control-label" required>Post Türü</label>
    <div class="col-sm-8">
        <select name="color" required id="colorSelect" class="form-control">
            <?php
            $pdo = Database::connect();
            $sql = "SELECT * FROM post_turu";
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tur_adi = $row['tur_adi'];
                $tur_renk = $row['tur_renk'];
                $tur_id = $row['id'];
                echo '<option value="' . $tur_renk . '" data-id="' . $tur_id . '" ' . ($color == $tur_renk ? 'selected' : '') . '>' . $tur_adi . '</option>';
            }
            Database::disconnect();
            ?>
        </select>

        <!-- Döngü dışında hidden input -->
        <input type="hidden" name="postturu_id" id="selectedTurId" value="">
    </div>
</div>





<div class="form-group">
    <label class="col-lg-2 col-sm-2 control-label">Post Türü Açıklaması </label>
    <div class="col-sm-8">
        <div class="animated-container">
            <label class="control-label" style="font-size: 14px; text-align: left !important;" id="turDescContainer"></label>
        </div>
    </div>
</div>

	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label">Post İçerik</label>
		<div class="col-sm-8">
			<textarea required name="description" class="description-field"><?php echo $description; ?></textarea>
		</div>
	</div>
	
<div class="form-group">
		<div class="col-lg-offset-2 col-lg-8">
			<button name="save" type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Kaydet & Çık</button>
			<button name="save-new" type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Kaydet & Yeni</button>
<a class="btn btn-default" href="list.php?id=<?php echo $idgelenmarkanew; ?>&category_id=<?php echo $idgelencategory_id; ?>"><i class="fa fa-times"></i> İptal</a>
		</div>
	</div>
	
</form>
	
	<?php
} 


if(empty($idgelenmarkanew) && empty($idgelenmarka) ){
	echo '<div class="alert alert-block alert-danger" style="text-align:center">Takvim Eklemek İçin Bir Marka Seçiniz.</div>';
}

?>
<?php

$minDate = @@$start_dategelen;
$maxDate = @@$end_dategelen;
?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    var minDateStr = '<?php echo $minDate; ?>';
    var maxDateStr = '<?php echo $maxDate; ?>';

    var minDate = new Date(minDateStr.split('-').reverse().join('-'));
    var maxDate = new Date(maxDateStr.split('-').reverse().join('-'));

    var dateInput = document.getElementById('custom-date');

    flatpickr(dateInput, {
        enable: [
            function (date) {
                return date >= minDate && date <= maxDate;
            }
        ],
        dateFormat: 'd-m-Y', 
        locale: {
            firstDayOfWeek: 1, 
        }
    });
</script>


<script>
$(document).ready(function() {
    var selectedColor = $('#colorSelect').val();
    var selectedId = $('#colorSelect option:selected').data('id');

    $.ajax({
        type: 'POST',
        url: 'get_tur_desc.php',
        data: { color: selectedId },
        success: function(response) {
            $('#turDescContainer').html(response);
        }
    });

    $('#colorSelect').change(function() {
        var selectedColor = $(this).val();
        var selectedId = $(this).find('option:selected').data('id');

        $.ajax({
            type: 'POST',
            url: 'get_tur_desc.php',
            data: { color: selectedId },
            success: function(response) {
                $('#turDescContainer').html(response);
            }
        });
    });
});



$(document).ready(function() {
    function updateTurDesc(selectedId) {
        $.ajax({
            type: 'POST',
            url: 'get_tur_desc.php',
            data: { color: selectedId },
            success: function(response) {
                $('#turDescContainer').html(response);

                $('.animated-container').addClass('show');
            }
        });
    }

    var selectedColor = $('#colorSelect').val();
    var selectedId = $('#colorSelect option:selected').data('id');

    updateTurDesc(selectedId);

    $('#colorSelect').change(function() {
        var selectedId = $(this).find('option:selected').data('id');
        updateTurDesc(selectedId);
    });
});	
</script>



<script>
    // Seçildiğinde hidden input değerini güncelleyen JavaScript
    function updateHiddenInput() {
        var selectedTurId = document.getElementById('colorSelect').options[document.getElementById('colorSelect').selectedIndex].getAttribute('data-id');
        document.getElementById('selectedTurId').value = selectedTurId;
    }

    // Sayfa yüklendiğinde ilk değeri set et
    window.onload = function () {
        updateHiddenInput();
    };

    // Seçili değiştikçe güncelle
    document.getElementById('colorSelect').addEventListener('change', updateHiddenInput);
</script>




<script>
function checkTotalFileSize() {
    var fileInput = document.getElementById('fileInput');

    if (fileInput.files.length > 0) {
        var totalFileSize = 0;
        var maxSize = 20 * 1024 * 1024;

        for (var i = 0; i < fileInput.files.length; i++) {
            totalFileSize += fileInput.files[i].size;
        }

        if (totalFileSize > maxSize) {
            alert('Toplam dosya boyutu çok büyük. (Max 20 MB)');
            fileInput.value = '';
        }
    }
}
</script>

