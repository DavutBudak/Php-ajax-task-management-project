<?php
session_start();

// Unset session
unset($_SESSION['user']);
unset($_SESSION['login_time']);
     
// Redirect to the index page 
header("Location: index.php");
EXIT;