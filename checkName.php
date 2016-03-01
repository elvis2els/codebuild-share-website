<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>

<?php
$uname = $_GET['uname'];
$query = sprintf("SELECT name FROM `user` WHERE name=%s", GetSQLValueString($uname, "text", $conn));
$result = $conn->query($query);
if(mysqli_num_rows($result) < 1){
	echo '<span style="color:green">User name is available!</span>';
}else{
	echo '<span style="color:red">User name already exists!</span>';
}
?>