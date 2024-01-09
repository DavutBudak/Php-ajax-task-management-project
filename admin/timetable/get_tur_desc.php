<?php
include_once("models/timetable_model.php");

if (isset($_POST['color'])) {
    $pdo = Database::connect(); 
    $sql = "SELECT tur_desc FROM post_turu WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['color']]);
    $tur_desc = $stmt->fetchColumn();
    Database::disconnect();

    // HTML etiketlerini işlemek için html_entity_decode kullanılıyor
    $decoded_tur_desc = html_entity_decode($tur_desc);

    echo $decoded_tur_desc;
}
?>