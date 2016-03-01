<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['MM_Username'])) {
	header("Location: login.php");
}?>