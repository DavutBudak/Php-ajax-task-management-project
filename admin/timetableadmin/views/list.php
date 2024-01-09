<?php
// Variables
$relative_url = '../../';
$relative_path = '../';
$list_page = 'admin/timetableadmin/list.php';


@@$gelenmarkaid = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
@@$gelencategory_id = filter_var($_GET['category_id'], FILTER_SANITIZE_NUMBER_INT);



// Pagination
$total_items = count($timetables);

$page = ceil($total_items/$this->items_per_page);
if (isset($_SESSION['page'])) {
	if ($_SESSION['page'] > $page) {
		$_SESSION['page'] = 1;	
	}
	$cur_page = $_SESSION['page'];
	$offset = ($_SESSION['page'] - 1) * $this->items_per_page;
} else {
	$cur_page = 1;
	$offset = 0;
}
?>

<?php
$pdo = Database::connect();

// $gelenmarkaid ve $_SESSION['user']['id'] değerlerini kullanarak sorguyu hazırla
$sql = 'SELECT * FROM kullanici_markalar WHERE marka_id = ? AND kullanici_id = ?';
$statement = $pdo->prepare($sql);
$statement->execute([$gelenmarkaid, $_SESSION['user']['id']]);
$matchingRow = $statement->fetch(PDO::FETCH_ASSOC);

if ($matchingRow || $_SESSION['user']['role'] == 1) {

    // Eşleşme varsa "Eşleşiyor" yazdır
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

<?php if (isset($_SESSION['delete_success'])) { ?>
	<div class="alert alert-success">
		<button data-dismiss="alert" class="close close-sm" type="button">
			<i class="fa fa-times"></i>
		</button>
		Zaman çizelgesi başarıyla silindi.

	</div>
	<?php unset($_SESSION['delete_success']); ?>
<?php } ?>

<div class="tiva-quiz-bar m-b-20">


			<?php
if ($_SESSION['user']['role'] == 1) {
    $gelenmarkaid = isset($_GET['id']) ? $_GET['id'] : null;
    $gelencategory_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

    if ($_SESSION['user']['role'] == 1 &&  $gelenmarkaid !== null && $gelencategory_id !== null) {
        // Eğer gelenmarkaid ve gelencategory_id categories tablosunda eşleşiyorsa
        $sql_check_categories = "SELECT COUNT(*) AS count FROM categories WHERE marka_id = :marka_id AND id = :category_id";
        $stmt_check_categories = $pdo->prepare($sql_check_categories);
        $stmt_check_categories->bindParam(':marka_id', $gelenmarkaid, PDO::PARAM_INT);
        $stmt_check_categories->bindParam(':category_id', $gelencategory_id, PDO::PARAM_INT);
        $stmt_check_categories->execute();
        $result_check_categories = $stmt_check_categories->fetch(PDO::FETCH_ASSOC);

        if ($result_check_categories && $result_check_categories['count'] > 0) {
            // Eşleşme var, yeni oluştur bağlantısını göster
            echo '<a class="btn btn-success pull-left" href="edit.php?newid=' . $gelenmarkaid . '&category_id=' . $gelencategory_id . '"><i class="fa fa-plus-circle"></i> Yeni Oluştur</a>';
        }
    } else {
        // Eşleşme yoksa ve category_id set edilmişse
        if (isset($_GET['category_id'])) {
            echo '<a class="btn btn-success pull-left" href="edit.php?newid=' . $gelenmarkaid . '&category_id=' . $gelencategory_id . '"><i class="fa fa-plus-circle"></i> Yeni Oluştur</a>';
        } else {
            // Diğer durumlar için mevcut kodunuzu kullanın
            echo '<a class="btn btn-success pull-left" href="https://ajaxcalender.clicksuslabs.com/admin/kategoriler/edit.php?marka_id=' . $gelenmarkaid . '"><i class="fa fa-plus-circle"></i> Yeni Oluştur</a>';
        }
    }
}

						if(isset($_GET['category_id']) && $result_check_categories && $result_check_categories['count'] > 0){?>

			<a target="_blank" style="margin-left:15px;margin-right:15px;" class="btn btn-success pull-right"  href="https://ajaxcalender.clicksuslabs.com/index.php?marka_id=<?php echo $gelenmarkaid; ?>&category_id=<?php echo $gelencategory_id; ?>"><i class="fa fa-plus-circle" ></i> Takvim'e Git</a>
   <?php  }  ?>






</div>
<style>
.main {
  max-width: 100%;
  margin: 0px;
}

.cards {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  margin: 0;
  padding: 0;
}

.cards_item {
  display: flex;
  padding: 1rem;
}

.card_image {
  position: relative;
  max-height: 250px;
}

.card_image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.card_price {
    bottom: 0px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    color:white;
    height: 40px;
    border-radius: 0.25rem;
    background-color: #828898;
    font-size: 18px;
    font-weight: bold;
}

.card_price span {
  font-size: 12px;
  margin-top: -2px;
}

.note {
  position: absolute;
  top: 8px;
  left: 8px;
  padding: 4px 8px;
  border-radius: 0.25rem;
  background-color: #c89b3f;
  font-size: 14px;
  font-weight: 700;
}

@media (min-width: 40rem) {
  .cards_item {
    width: 50%;
  }
}

@media (min-width: 56rem) {
  .cards_item {
   width: 25%;

  }
}

.card {
  border: 1px solid #9d9d9d;
  background-color: #e7e7e7;
  box-shadow: 0 20px 40px -14px rgba(0, 0, 0, 0.25);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.card_content {
  position: relative;
  padding: 16px 12px 10px 24px;
  margin: 16px 8px 8px 0;
  max-height: 290px;
  overflow-y: scroll;
}

.card_content::-webkit-scrollbar {
  width: 8px;
}

.card_content::-webkit-scrollbar-track {
  box-shadow: 0;
  border-radius: 0;
}

.card_content::-webkit-scrollbar-thumb {
  background: #c89b3f;
  border-radius: 15px;
}

.card_title {
  position: relative;
  margin: 0 0 5px;
  padding-bottom: 10px;
  text-align: center;
  font-size: 18px;
  font-weight: 700;
}

.card_title::after {
  position: absolute;
  display: block;
  width: 100%;
  height: 2px;
  bottom: 0;
  background-color: #c9c5bb;
  content: "";
}

hr {
  margin: 24px auto;
  width: 50px;
  border-top: 2px solid #c89b3f;
}

.card_text p {
  margin: 0 0 24px;
  font-size: 14px;
  line-height: 1.5;
}

.card_text p:last-child {
  margin: 0;
}

</style>
    <?php
function translateDay($day) {
    $days = array(
        'monday' => 'Pazartesi',
        'tuesday' => 'Salı',
        'wednesday' => 'Çarşamba',
        'thursday' => 'Perşembe',
        'friday' => 'Cuma',
        'saturday' => 'Cumartesi',
        'sunday' => 'Pazar'
    );

    return isset($days[$day]) ? $days[$day] : $day;
}
?>

<div class="tiva-timetable-list table-responsive col-md-12"  style="margin-top:50px; ">
	<table class="table table-hover tiva-table " >
		<tbody>


		<?php 
			if(isset($_GET['category_id']) ){
				ECHO'
				<tr class="first-row">
					<th >Post Adı</th>
					<th  >Marka Adı</th>
					<th  >Dosya</th>
					<th  >Tarih</th>
					<th>Gün</th>
					<th  >Durum</th>
					<th class="text-center" >Düzenle</th>
				</tr>';
			} else{
				
				echo'
				<div class="main">
				  <ul class="cards">';
			
			}
		
		 ?> 

		
			<?php if ($timetables) { ?>
				
    <?php $timetables = array_slice($timetables, $offset, $this->items_per_page); ?>
    <?php foreach ($timetables as $timetable) { 
		if(isset($timetable['type_categories']) ){ ?>
		
		<li class="cards_item">
      <div class="card">
       
      <div class="card_image">
          <img src="../../images/temp/2473674.jpg" />
       
        </div>
        <span class="card_price">   <span style=" font-size: 16px; font-weight: bold; margin-right:10px;"> <?php echo $timetable['start_date']  . ' </span> /   <span style="margin-left:10px; font-size: 16px; font-weight: bold;">' . $timetable['end_date'] ;?> </span> </span>



        <div class="card_content">
          <h2 class="card_title"><?php echo $timetable['name'];?></h2>
         
        </div>
        <h4 style="position: relative; margin: 0 0 24px; padding-bottom: 10px; text-align: center; font-size: 18px; font-weight: 700;"><a href="https://ajaxcalender.clicksuslabs.com/index.php?marka_id=<?php echo $gelenmarkaid;?>&category_id=<?php echo $timetable['id'];?>" target="_blank"> <i class="fa fa-calendar"></i> Takvime Git </a> </h4>

        <div class="row" style="margin:0px;">
        <div class="col-md-4" style="padding:0px; margin:0px;">       <a style="border-radius:0px !important; height:40px;  width: 100%;  font-size: 14px;" class="btn btn-danger pull-right" href="https://ajaxcalender.clicksuslabs.com/admin/kategoriler/delete.php?category_id=<?php echo $timetable['id'];?>&id=<?php echo $gelenmarkaid; ?>"> <i class="fa fa-trash"></i> Sil </a>
</div>
          <div class="col-md-4" style="padding:0px; margin:0px;">       <a style="border-radius:0px !important; height:40px;  width: 100%;  font-size: 14px;"class="btn btn-primary pull-right" href="https://ajaxcalender.clicksuslabs.com/admin/kategoriler/edit.php?id=<?php echo $timetable['id'];?>"> <i class="fa fa-edit"></i> Düzenle</a>
</div>
          <div class="col-md-4" style="padding:0px; margin:0px;">      <a style="border-radius:0px !important; height:40px;  width: 100%;  font-size: 14px;"  class="btn btn-success pull-right" href="https://ajaxcalender.clicksuslabs.com/admin/timetableadmin/list.php?id=<?php echo $gelenmarkaid;?>&category_id=<?php echo $timetable['id'];?>"> <i class="fa fa-arrow-right"></i> Detay </a>
</div>

        </div>

      </div>
    </li>


	
	<?php	}
		
		
		


		
		
		else{
		?>
		
        <tr>
            <td><a href="edit.php?id=<?php echo $timetable['id']; ?>"><?php echo $timetable['name']; ?></a></td>
            <td>
                <?php
                $pdo = Database::connect();
                $sql = 'SELECT markaadi FROM markalar WHERE id = ?';
                $statement = $pdo->prepare($sql);
                $statement->execute([$timetable['marka_id']]);
                $markaadi = $statement->fetchColumn();

                // Eğer marka adı varsa göster, yoksa bir şey gösterme
                if ($markaadi) {
                    echo $markaadi;
                } else {
                    echo 'Marka Bulunamadı';
                }
                Database::disconnect();
                ?> 
            </td>

<td>
    <?php
    // $timetable['image'] değeri veritabanından gelen değerdir (örneğin: '652d502a06261_foto1.png', '652d502a063d9_foto2.png')
    $imageNames = explode(', ', $timetable['image']); // Virgülle ayrılmış değerleri diziye çevirir

    // Başındaki ve sondaki tek tırnak işaretlerini manuel olarak kaldırır
    foreach ($imageNames as &$imageName) {
        $imageName = trim($imageName, "'");
    }
    $firstImage = !empty($imageNames[0]) ? $imageNames[0] : 'no-image.png';

    $fileExt = strtolower(pathinfo($firstImage, PATHINFO_EXTENSION)); // Dosya uzantısını alır

    $imagePath = ($firstImage === 'no-image.png') ? 'noimage/' : ''; // Eğer 'no-image' ise 'thumbnailvideo/' eklenir

    if ($fileExt == 'mp4') { // Eğer dosya uzantısı mp4 ise videoyu gösterir
        ?>
        <video style="    object-fit: contain;"  width="150" height="150" controls>
            <source src="../timetable/images/<?php echo $imagePath . $firstImage; ?>" type="video/mp4">
            Tarayıcınız video etiketini desteklemiyor.
        </video>
        <?php
    } elseif (!empty($firstImage)) { // Dosya adı boş değilse ve dosya uzantısı mp4 değilse resmi gösterir
        ?>
        <img src="../timetable/images/<?php echo $imagePath . $firstImage; ?>" alt="<?php echo $timetable['name']; ?>" style="width: 150px; height: 150px; object-fit: contain;" />
        <?php
    }
    ?>
</td>
            <td ><?php echo $timetable['date']; ?></td>
            <td >    <?php echo translateDay($timetable['day']); ?></td>
            <td >
                <a href="status.php?id=<?php echo $timetable['id']; ?>">
                    <img src="<?php echo $timetable['state'] == 1 ? '../../images/temp/tick.png' : '../../images/temp/close.png'; ?>" />
                </a>
            </td>
            <td class="text-center">
                <a class="btn btn-primary" href="edit.php?id=<?php echo $timetable['id']; ?>&categoryedit_id=<?php echo $gelencategory_id; ?>"><i class="fa fa-edit"></i> Düzenle</a>
                <a class="btn btn-danger btn-delete" href="delete.php?id=<?php echo $timetable['id']; ?>&markaid=<?php echo $gelenmarkaid; ?>&gelencategory_id=<?php echo $gelencategory_id;?>"><i class="fa fa-trash-o"></i> Sil</a>
            </td>
        </tr>
    <?php }	} ?>        

	</ul>
</div>
<?php } ?>
			
			<tr class="last-row"><td colspan="10"></td></tr>
		</tbody>
	</table>
</div>

<?php 

if (empty($timetables)) {
	
	echo '<div class="alert alert-block alert-danger" style="text-align:center; clear:both;" >İçerik Bulunamadı  </div>';
}
?>

<!-- Pagination -->
<?php include $relative_url . 'system/pagination.php'; ?>
 
 
 <?php
} else {
    // Eşleşme yoksa bir şey yapma veya hata mesajı göster
	header("Location: unauthorized.php"); // Yetkisiz erişim durumunda yönlendirilecek sayfa
    exit();
}

Database::disconnect();

?>




 