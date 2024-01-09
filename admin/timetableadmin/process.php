<?php
// File name
if ($_POST['type'] == 1) {
	$name = 'day';
} elseif ($_POST['type'] == 2) {
	$name = 'week';
} elseif ($_POST['type'] == 3) {
	$name = 'month';
} elseif ($_POST['type'] == 4) {
	$name = 'year';
}
$name .= '-' . $_POST['time'] . '.' . $file_ext;
echo file_exists('files/' . $name);
?>