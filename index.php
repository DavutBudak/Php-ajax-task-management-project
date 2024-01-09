<?php
	session_start();
	$marka_id = intval($_GET['marka_id']);
	$category_id =intval( $_GET['category_id']);



	if(empty($_SESSION['user']) || empty($marka_id) || empty($category_id))
{
	header("Location: admin/"); 
	exit();
} 


else{
	
// Variables
$relative_url = '';
$relative_path = '';

include $relative_path . 'header.php'; 
?>

<!DOCTYPE html>
<html>
	<head>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

	<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

	</head>
<body>
<style>
	.ortalih2{
		height: 100px;
    display: flex;
    align-items: center;
	justify-content: center;
	    margin: 0;
	}
	.ortalih1{
		height: 100px;
    display: flex;
    align-items: center;
	justify-content: center;
	    margin: 0;
	}
</style>


<?php  
include_once('config/database.php');
include_once("config/security.php");





	
	// Get daily report
	unset($_SESSION['db_timetables']);
	$_SESSION['db_timetables'] = $marka_id;

	unset($_SESSION['db_timetablescagetori']);
	$_SESSION['db_timetablescagetori'] = $category_id;




$pdo = Database::connect();

// Kullanıcı ID'sini ve Marka ID'sini al
$kullanici_id =$_SESSION['user']['id'];

// Veritabanında kontrol sorgusu
$sql = 'SELECT * FROM kullanici_markalar WHERE kullanici_id = '.$kullanici_id.' AND marka_id = '.$marka_id;
$query = $pdo->query($sql);
$result = $query->fetch(PDO::FETCH_ASSOC);



$gelenkategoriadi = "SELECT name FROM categories WHERE id = ? AND marka_id = ?";
$q = $pdo->prepare($gelenkategoriadi);
$q->execute([$category_id,$marka_id]);
// Sonucu al
$resultcategoriadi = $q->fetch(PDO::FETCH_ASSOC);







$gelenmarka = "SELECT markaadi, image FROM markalar WHERE id = ?";
$q = $pdo->prepare($gelenmarka);
$q->execute([$marka_id]);
// Sonucu al
$result = $q->fetch(PDO::FETCH_ASSOC);

// Marka adını ve görsel ismi değişkenlere kaydet
$gelenmarkaadi = $result['markaadi'];
$imageFileName = $result['image'];





if ($result ||$_SESSION['user']['role'] == 1) {
    ?> 

	
	
	<div class="container">
			<div class="col-md-12">
				<div class="header">
					<div class="header-big"> <img src="admin/markalar/uploads/<?php echo $imageFileName;?>" alt="Marka Logo" style="width:150px; height:130px; object-fit: contain;"> </div>
				
				</div>
				
				<div class="content">
				
					
						
					<div class="section" id="monthly-view">
                        <h2><?= $gelenmarkaadi . " / " . @$resultcategoriadi['name']; ?></h2>
						<div class="timetable-example">
							<div class="tiva-timetable"></div>
						</div>
					</div>
					
				





<!-- BUNU KULLANMAK İSTERSEN TÜM TAKVİMLERE TİME GİRİP COLOR A DA 1-2-3 TARZINDA GİRERSEN ÇALIŞACAK VE MOBİLDE Bİ İHTİMAL KULLANILABİLİR ŞİMDİLİK KAPALI OLSUN BURASI TİMETABLES.JS DE bunabirbakweekly DİYE CLASS ATTIM ORDAN BULURSUN -->
					
				<!--	<div class="section" id="weekly-view">
						<h2>Weekly View</h2>
						<p>Display all your timetables via week. You can use as day schedule (repeat on every week) or as specific date (like monthly view).</p>
						<div class="timetable-example">
							<div class="tiva-timetable" data-view="week"></div>
						</div>
					</div>
-->













					
				<!--	<div class="section" id="list-view">
						<h2>Liste Görünümü</h2>
						<p>Tüm takvimlerinizi listede görüntüleyin. Günlük program (her hafta tekrar) veya belirli bir tarih (aylık görünüm gibi) olarak kullanabilirsiniz.</p>
						<div class="timetable-example">
							<div class="tiva-timetable" data-view="list" data-mode="day"></div>
						</div>
					</div>
				</div>
-->
				<div class="footer">
					<div class="product-name">Takvim Uygulaması PHP Script</div>
					<div>Copyright & Clicks'us , 2023</div>

			
				</div>
			</div>
		</div>
	</body>
</html>
	
	<?php

} else {
	header("Location: admin/unauthorized.php"); // Yetkisiz erişim durumunda yönlendirilecek sayfa
    exit();}

Database::disconnect();
?>



	<?php }
	
	?>
	<style>

    
    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    
    .btn-default {
		min-width:200px;
        color: white;
        border-color: #ccc;
    }
    
   
    .specificexcell{
    	background-color: green !important;
		color:white !important;
    }

p a{
  padding: 5px;
}
	</style>

	<div class="row nonedownload" style="margin-bottom: 30px;">

	<div  class="col-md-3" style="text-align:center; ">	<button style="background: #c56108;" id="screenshotButton" type="button" class="btn btn-default">Ekran Görüntüsü Al ve İndir</button>
</div>
<div class="col-md-3" style="text-align:center;">
    <button id="downloadButton" type="button" class="btn btn-default specificexcell">Excel İndir</button>
</div>
<div class="col-md-3" style="text-align:center;">

<button id="downloadButtonPowerPoint" type="button" class="btn btn-default specificpowerpoint" style="background: #ad1212;">PowerPoint İndir</button>
</div>

<div class="col-md-3" style="text-align:center;">

<button id="pdfButton" type="button" class="btn btn-default" style="background: #0956a9;">PDF İndir</button>
</div>





	</div>
	 


    <script>
document.getElementById('downloadButtonPowerPoint').addEventListener('click', function() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'timetables.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {


                console.log('Sunucu cevabı:', xhr.responseText);


                
                    // Tarayıcıya dosyayı indirme talebi gönderme
                    var a = document.createElement('a');
                    a.href = 'timetables.pptx';
                    a.download = 'timetables.pptx';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
            } 
            else {
                    alert('PowerPoint dosyası oluşturulamadı!');
                }
        }
    };
    xhr.send('downloadPowerPoint=true');
});

    </script>



	<script>
document.getElementById('pdfButton').addEventListener('click', function() {
    // Belirli bir sınıfa sahip elementleri gizle
    var elementsToHide = document.querySelectorAll('.nonedownload');
    elementsToHide.forEach(function(element) {
        element.style.display = 'none';
    });

    var element = document.body;

    html2pdf(element, {
        margin: 0,
        filename: 'sayfa.pdf',
        image: { type: 'png', quality: 0.95 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
    }).then(function() {
        // PDF oluşturma tamamlandığında yapılacak işlemler
        console.log('PDF oluşturuldu.');

        // Belirli bir sınıfa sahip elementleri yeniden göster
        elementsToHide.forEach(function(element) {
            element.style.display = 'block';
        });
    });
});
</script>




<script>
document.getElementById('screenshotButton').addEventListener('click', function() {
    // Belirli bir sınıfa sahip elementleri gizle
    var elementsToHide = document.querySelectorAll('.nonedownload');
    elementsToHide.forEach(function(element) {
        element.style.display = 'none';
    });

    html2canvas(document.body).then(function(canvas) {
        // Ekran görüntüsü alma işlemi

        // Belirli bir sınıfa sahip elementleri yeniden göster
        elementsToHide.forEach(function(element) {
            element.style.display = 'block';
        });

        var imgData = canvas.toDataURL('image/png');
        var a = document.createElement('a');
        a.href = imgData;
        a.download = 'takvim-görüntüsü.png';
        a.click();
    });
});
</script>




<script>
document.getElementById('downloadButton').addEventListener('click', function() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'timetables.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.includes("Excel dosyası oluşturuldu")) {
                // Tarayıcıya dosyayı indirme talebi gönderme
                var a = document.createElement('a');
                a.href = 'timetables.xlsx';
                a.download = 'timetables.xlsx';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            } else {
                alert('Excel dosyası oluşturulamadı!');
            }
        }
    };
    xhr.send('downloadExcel=true');
});
</script>

