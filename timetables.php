<?php
include_once('config/database.php');
include_once("config/security.php");
require 'vendor/autoload.php';

session_start();
$marka_idgelen = $_SESSION['db_timetables'];
$category_idgelen = $_SESSION['db_timetablescagetori'];
$pdo = Database::connect();







$gelenmarka = "SELECT markaadi, image FROM markalar WHERE id = ?";
$q = $pdo->prepare($gelenmarka);
$q->execute([$marka_idgelen]);

// Sonucu al
$result = $q->fetch(PDO::FETCH_ASSOC);

// Marka adını ve görsel ismi değişkenlere kaydet
$gelenmarkaadi = $result['markaadi'];
$imageFileName = $result['image'];







// Categories tablosundan end_date ve start_date değerlerini al
$sql = 'SELECT start_date, end_date , name FROM categories WHERE id = ' . $category_idgelen;
$statement = $pdo->query($sql);
$category_dates = $statement->fetch(PDO::FETCH_ASSOC);

$start_date = $category_dates['start_date'];
$end_date = $category_dates['end_date'];
$categoryname = $category_dates['name'];


// Timetables tablosundan verileri alırken tarih kontrolü yap
$sql = 'SELECT * FROM timetables 
        WHERE marka_id = ' . $marka_idgelen . ' 
        AND category_id = ' . $category_idgelen ;

$statement = $pdo->prepare($sql);
$statement->bindParam(':start_date', $start_date, PDO::PARAM_STR);
$statement->bindParam(':end_date', $end_date, PDO::PARAM_STR);
$statement->execute();

$db_timetables = $statement->fetchAll(PDO::FETCH_ASSOC);


Database::disconnect();

$timetables = array();


foreach ($db_timetables as $db_timetable) {
    $timetable = new stdClass();
    $timetable->name = $db_timetable['name'];
    $timetable->image = $db_timetable['image'];
    $timetable->date = date('j', strtotime($db_timetable['date']));
    $timetable->month = date('n', strtotime($db_timetable['date']));
    $timetable->year = date('Y', strtotime($db_timetable['date']));
    $timetable->day = $db_timetable['day'];
    $timetable->start_time = $db_timetable['start_time'] ? date('H:i', strtotime($db_timetable['start_time'])) : '';
    $timetable->end_time = $db_timetable['end_time'] ? date('H:i', strtotime($db_timetable['end_time'])) : '';
        $timetable->postturu_id = $db_timetable['postturu_id'];
    $timetable->description = nl2br($db_timetable['description']);



    try {
        // PDO veritabanı bağlantısı oluşturma
        $pdo = new PDO("mysql:host=localhost;dbname=davut_calender;charset=utf8", "davut_calender", "Pass@!21Clicks");
        // Hata modunu ayarlama
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Veritabanı sorgusu oluşturma
        $sql = "SELECT tur_adi , tur_renk FROM post_turu WHERE id = :postturu_id";
        $stmt = $pdo->prepare($sql);
        
        // Sorguyu çalıştırma
        $stmt->bindParam(':postturu_id', $db_timetable['postturu_id']);
        $stmt->execute();
    
        // Sonuçları al
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        @@$postturu_adi = $row['tur_adi'];
        @@$color = $row['tur_renk'];

    
        // PDO bağlantısını kapatma
        $pdo = null;
    } catch(PDOException $e) {
        // Hata durumunda hata mesajını alabilirsiniz
        $postturu_adi = "Bulunamadı";
        $color = "#ff0000";

    }

    $timetable->postturu = $postturu_adi; // $timetable nesnesine postturu bilgisini ekleyin.
        $timetable->color = $color; // $timetable nesnesine postturu bilgisini ekleyin.

    


    $timetable->gelenmarkaadi = $gelenmarkaadi;
    $timetable->gelenmarkalogo = $imageFileName;

    array_push($timetables, $timetable);
}

echo json_encode($timetables);


?>



<?php

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Writer\PowerPoint2007;
use PhpOffice\PhpPresentation\Shape\Drawing\Gd;
use PhpOffice\PhpPresentation\Shape\Drawing;
// Hata ve uyarıları görüntülemek için eklenen satırlar
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_POST['downloadPowerPoint'])) {
    // PowerPoint dosyasını oluşturma
    $presentation = new PhpPresentation();
    $slide = $presentation->getActiveSlide();



// Logo dosyasının tam yolunu oluşturun
$logoPath = 'admin/markalar/uploads/' . $timetables[0]->gelenmarkalogo;

// Logo dosyasının varlığını ve türünü kontrol edin
if (is_file($logoPath)) {
    $extension = pathinfo($logoPath, PATHINFO_EXTENSION);
    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
        // Logo dosyasını uygun bir şekilde ekleyin
        $originalImage = imagecreatefromstring(file_get_contents($logoPath));

        // Sabit boyutlarda bir resim ekleyin
        $shape = new Gd();
        $shape->setName('Logo image')
            ->setDescription('Logo image')
            ->setImageResource($originalImage)
            ->setRenderingFunction(Gd::RENDERING_JPEG)
            ->setMimeType(Gd::MIMETYPE_DEFAULT);

        // Resmi orijinal boyutunda ekleyin
        $originalWidth = imagesx($originalImage);
        $originalHeight = imagesy($originalImage);

        // Belirlenen sabit boyutlar
        $newWidth = 200;
        $newHeight = 150;

        // Boyutları oranlı bir şekilde ayarla
        $aspectRatio = $originalWidth / $originalHeight;
        if ($aspectRatio > 1) {
            $shape->setWidth($newWidth);
            $shape->setHeight($newWidth / $aspectRatio);
        } else {
            $shape->setWidth($newHeight * $aspectRatio);
            $shape->setHeight($newHeight);
        }

        // Resmi ekleyin
        $slide = $presentation->getActiveSlide(); // Yeni bir slayt eklemek istiyorsanız bu satırı ekleyin
        $slide->addShape($shape);

        // Resmi eklemek için boyutları ve konumu ayarla
        $shape->setOffsetX(350); // Sabit bir konum
        $shape->setOffsetY(10);  // Sabit bir konum
    } else {
        echo "Desteklenmeyen dosya formatı: $extension";
    }
} else {
    echo "Logo dosyası bulunamadı: $logoPath";
}

$leftTextShape = $slide->createRichTextShape();
$leftTextShape->setHeight(720)
    ->setWidth(960)
    ->setOffsetX(0)
    ->setOffsetY(150);

$leftTextShape->createTextRun("\n");




// Metin kutularına metni ekleyin
$leftTextRun = $leftTextShape->createTextRun(" {$timetable->gelenmarkaadi} Marka Postları \n\n");
$leftTextRun->getFont()->setBold(true);
$leftTextRun->getFont()->setSize(20); // Font boyutunu ayarlamak için (örneğin 18)

// Yeni bir satır ekleyin
$leftTextShape->createTextRun("\n");

// Metin kutularına metni ekleyin
$leftTextRun = $leftTextShape->createTextRun(" {$categoryname}\n\n");
$leftTextRun->getFont()->setBold(true);
$leftTextRun->getFont()->setSize(18); // Font boyutunu ayarlamak için (örneğin 18)

// Yeni bir satır ekleyin

    // Diğer slaytları eklemek için döngü
    foreach ($timetables as $timetable) {
        $slide = $presentation->createSlide();

        // Metin kutularını oluşturun (sol ve sağ)
        $leftTextShape = $slide->createRichTextShape();
        $leftTextShape->setHeight(720)
            ->setWidth(480) // kutunun uzunluğu bu
            ->setOffsetX(0)
            ->setOffsetY(0);

        $leftTextRun = $leftTextShape->createTextRun("{$timetable->name}\n\n");
        $leftTextRun->getFont()->setBold(true); // Kalın yapmak için
        $leftTextRun->getFont()->setSize(18); // Font boyutunu ayarlamak için (örneğin 18)

        $leftTextRun = $leftTextShape->createTextRun("Post İçeriği : " . strip_tags($timetable->description) . "\n\n");
        $leftTextRun->getFont()->setSize(13); // Font boyutunu ayarlamak için (örneğin 18)

        $leftTextRun = $leftTextShape->createTextRun("Post Tarihi : {$timetable->date}.{$timetable->month}.{$timetable->year}\n\n");
        $leftTextRun->getFont()->setSize(13); // Font boyutunu ayarlamak için (örneğin 18)

        $leftTextRun = $leftTextShape->createTextRun("Post Türü : {$timetable->postturu}\n\n");
        $leftTextRun->getFont()->setSize(13); // Font boyutunu ayarlamak için (örneğin 18)

        // Resim dosyalarını ayır
        $imageNames = array_map(function ($imageName) {
            return str_replace(["'", ' '], '', $imageName);
        }, explode(',', trim($timetable->image, "'")));

        $imageCounter = 0; // Resim sayacını başlat

        // Her bir resim dosyasını işle
        foreach ($imageNames as $index => $imageName) {
            $fullImagePath = 'admin/timetable/images/' . $imageName;

            // Resim dosyasının varlığını kontrol etmek için
            if (is_file($fullImagePath)) {
                // Resmin uzantısına göre doğru fonksiyonu kullanarak orijinal resmi oku
                $extension = pathinfo($fullImagePath, PATHINFO_EXTENSION);
                if ($extension == 'jpg' || $extension == 'jpeg') {
                    $originalImage = imagecreatefromjpeg($fullImagePath);
                } elseif ($extension == 'png') {
                    $originalImage = imagecreatefrompng($fullImagePath);
                } elseif ($extension == 'mp4') {
                    $defaultThumbnail = 'admin/timetable/thumbnailvideo/youtube-thumb.jpeg';
                    $originalImage = imagecreatefromjpeg($defaultThumbnail);
                } else {
                    echo "Desteklenmeyen dosya formatı: $extension";
                    continue; // Desteklenmeyen formatları atla
                }

                // Sabit boyutlarda bir resim ekleyin
                $shape = new Gd();
                $shape->setName('Sample image')
                    ->setDescription('Sample image')
                    ->setImageResource($originalImage)
                    ->setRenderingFunction(Gd::RENDERING_JPEG)
                    ->setMimeType(Gd::MIMETYPE_DEFAULT);

                // Resmi orijinal boyutunda ekleyin
                $originalWidth = imagesx($originalImage);
                $originalHeight = imagesy($originalImage);

                // Belirlenen sabit boyutlar
                $newWidth = 200;
                $newHeight = 150;

                // Boyutları oranlı bir şekilde ayarla
                $aspectRatio = $originalWidth / $originalHeight;
                if ($aspectRatio > 1) {
                    $shape->setWidth($newWidth);
                    $shape->setHeight($newWidth / $aspectRatio);
                } else {
                    $shape->setWidth($newHeight * $aspectRatio);
                    $shape->setHeight($newHeight);
                }

                // Resme (veya videonun thumbnail'ına) köprü ekleyin
                $hyperlink = new PhpOffice\PhpPresentation\Shape\Hyperlink();
                $hyperlink->setUrl('https://' . $_SERVER['HTTP_HOST'] . '/' . $fullImagePath); // Yönlendirilmek istenen URL'yi buraya ekleyin
                $shape->setHyperlink($hyperlink);

                // Resmi ekle
                $slide->addShape($shape);

                // Resmi eklemek için boyutları ve konumu ayarla
                $shape->setOffsetX(255 * ($imageCounter % 2) + 490); // Her resim için farklı bir konum (2 resim sığacak şekilde)
                $shape->setOffsetY(160 * floor($imageCounter / 2) + 10); // Her 2 resimden sonra yeni bir satıra geç
                $imageCounter++;
            } else {
                echo "Dosya bulunamadı: $fullImagePath";
            }
        }
    }

    // PowerPoint dosyasını kaydetme işlemi
    $filename = 'timetables.pptx';
    $writer = new PowerPoint2007($presentation);
    $writer->save($filename);

    // Başarılı durumu için JSON yanıt gönder
    echo json_encode(['success' => true, 'filename' => $filename]);
    exit; // Skriptin burada sona ermesi için çıkış yapılır
}
?>






<?php
if (isset($_POST['downloadExcel'])) {
    // Verileri hazırlama işlemi (yukarıdaki kodu ekleyin)

    // Excel dosyasını oluşturma
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Verileri Excel dosyasına yazma işlemi (yukarıdaki kodu ekleyin)

    $sheet->setCellValue('A1', 'Marka Adı');
    $sheet->setCellValue('B1', 'Post Tarihi');
    $sheet->setCellValue('C1', 'Post Açıklama');
    $sheet->setCellValue('D1', 'Post Türü');


    $row = 2;
    foreach ($timetables as $timetable) {
        
        $sheet->setCellValue('A' . $row, $timetable->gelenmarkaadi);
        $sheet->setCellValue('B' . $row, $timetable->date . '.' . $timetable->month . '.' . $timetable->year);
        $sheet->setCellValue('C' . $row, strip_tags($timetable->description));
        $sheet->setCellValue('D' . $row, $timetable->postturu);


        

        // Diğer veri alanlarını eklemek için buraya ekleme yapabilirsiniz

        $row++;
    }

    // Excel dosyasını kaydetme işlemi
    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('timetables.xlsx');

    echo "Excel dosyası oluşturuldu: timetables.xlsx";
    exit; // Skriptin burada sona ermesi için çıkış yapılır
}
?>




