<?php
if (@$_SESSION['user']['role'] == 1 && strpos($_SERVER['REQUEST_URI'], '/admin/timetable/') !== false) {
    $newUrl = str_replace('/admin/timetable/', '/admin/timetableadmin/', $_SERVER['REQUEST_URI']);
    header('Location: https://ajaxcalender.clicksuslabs.com' . $newUrl);
    exit;
}
?>
<head>
    <meta charset="UTF-8">
    <title>Clicksus Takvim</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="description" content="Clicksus Takvim">
    <meta name="keywords" content="Admin, Clicksus Takvim">
    
	<!-- CSS -->
    <link href="<?php echo $relative_url; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $relative_url; ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $relative_url; ?>assets/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $relative_url; ?>assets/css/admin.css" rel="stylesheet" type="text/css" />
	
	<!-- jQuery -->
	<script src="<?php echo $relative_url; ?>assets/js/jquery.min.js" type="text/javascript"></script>
	<script src="<?php echo $relative_url; ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo $relative_url; ?>assets/js/bootstrap-filestyle.min.js"></script>
	<script src="<?php echo $relative_url; ?>assets/js/moment.min.js"></script>
	<script src="<?php echo $relative_url; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
	<script src="<?php echo $relative_url; ?>assets/js/admin.js" type="text/javascript"></script>



</head>

