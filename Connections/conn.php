<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conn = "localhost";
$database_conn = "bysj";
$username_conn = "root";
$password_conn = "";
$conn = mysqli_connect($hostname_conn, $username_conn, $password_conn, $database_conn) or trigger_error(mysqli_connect_error(),E_USER_ERROR); 
?>