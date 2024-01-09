<?php
session_start();

// Set page in session
if (isset($_GET['p'])) {
	$_SESSION['page'] = $_GET['p'];
} else {
	$_SESSION['page'] = 1;
}

// Redirect to list
header('Location: ../' . $_GET['url']);
EXIT;
?>