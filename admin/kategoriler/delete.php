<?php
session_start();
// Kullanıcının rolünü kontrol et

include_once("controllers/categori_controller.php");

@@$gelenid = $_GET['category_id'];
@@$gelencatid = $_GET['id'];
$timetablefile = "timetable";

if($_SESSION['user']['role'] == 1){
    $timetablefile = "timetableadmin";
    $user = new UserController();
    $user->deleteUser($gelenid,$gelencatid,$timetablefile);
    $_SESSION['delete_success'] = 1;
   

    }
    

if (isset($_GET['category_id'])) {
    $marka_id = $_GET['category_id'];

    // Veritabanı bağlantısı yapılır (Database::connect() fonksiyonu kullanılarak)
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // categories tablosundan marka_id'yi çek
    $sql = "SELECT marka_id FROM categories WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute([$marka_id]);
    $result = $q->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $marka_id = $result['marka_id'];
       
    
        // kullanici_markalar tablosundan kullanici_id'leri çek
        $sql = "SELECT kullanici_id FROM kullanici_markalar WHERE marka_id = ?";
        $q = $pdo->prepare($sql);
        $q->execute([$marka_id]);
        $kullanici_idler = $q->fetchAll(PDO::FETCH_COLUMN);
        
        // $_SESSION['user']['id'] ile eşleşen kullanıcı ID var mı kontrol et
        if (in_array($_SESSION['user']['id'], $kullanici_idler )) {
            // İşlemi gerçekleştir
            $user = new UserController();
            $user->deleteUser($gelenid,$gelencatid,$timetablefile);
            $_SESSION['delete_success'] = 1;

        } else {
            // Erişim hatası
            header("Location: unauthorized.php"); // Yetkili kullanıcı sadece kendi profilini düzenleyebilir
            exit();
        }
    } else {
        // Belirtilen marka_id bulunamadı hatası
        header("Location: unauthorized.php"); // Yetkili kullanıcı sadece kendi profilini düzenleyebilir
        exit();
    }
    
    // Veritabanı bağlantısı kapatılır (Database::disconnect() fonksiyonu kullanılarak)
    Database::disconnect();
}
?>