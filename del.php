<?/* 该文件是根据id删除数据库项的 */?>
<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('delDir.php'); ?>


<?php
//删除problem表中相应的项
if (isset($_GET['userId'])) {
	$id = $_GET['userId'];	
	$deleteSQL = sprintf("DELETE FROM user WHERE id=%s", GetSQLValueString($id, "int", $conn));
	$delGoTo = "usrManage.php";
}
else if(isset($_GET['algId'])) {//先把problem的nSol-1，再删除算法
	$id = $_GET['algId'];	
	$querySQL = sprintf("SELECT pro_id FROM solution WHERE id=%s", GetSQLValueString($id, "int", $conn));
	$result_proId = $conn->query($querySQL) or die($conn->error);
	$row_result_proId = $result_proId->fetch_assoc();
	
	$proId = $row_result_proId['pro_id'];	
	$querySQL = sprintf("SELECT nSol FROM problem WHERE id=%s", GetSQLValueString($proId, "int", $conn));
	$result_nSol = $conn->query($querySQL) or die($conn->error);
	$row_result_nSol = $result_nSol->fetch_assoc();
	
	$nSol = $row_result_nSol['nSol'] - 1;
	$updataSQL = sprintf("UPDATE problem SET nSol=%s WHERE id=%s", GetSQLValueString($nSol, "int", $conn), GetSQLValueString($proId, "int", $conn));
	$conn->query($updataSQL) or die($conn->error);
	
	//删除相关任务文件夹
	$querySQL = sprintf("SELECT id FROM run WHERE sol_id=%s", GetSQLValueString($id, "int", $conn));
	$result_runId = $conn->query($querySQL) or die($conn->error);
	$row_result_runId = $result_runId->fetch_assoc();
	$totalRows_result_runId = mysqli_num_rows($result_runId);
	if ($totalRows_result_runId) {
		do {
			$path_run = dirname(__FILE__).'\\file\\task\\task-'.$row_result_runId['id'];
			delDirAndFile($path_run);
		} while ($row_result_runId = $result_runId->fetch_assoc());
	}
	
	//删除相关算法文件夹
	$path_sol = dirname(__FILE__).'\\file\\Solution\\Sol-'.$id;
	delDirAndFile($path_sol);
	
	$deleteSQL = sprintf("DELETE FROM solution WHERE id=%s", GetSQLValueString($id, "int", $conn));
	$delGoTo = "usrAlgorithm.php";
}
else if(isset($_GET['contactId'])) {
	$id = $_GET['contactId'];
	$deleteSQL = sprintf("DELETE FROM contact WHERE id=%s", GetSQLValueString($id, "int", $conn));
	$delGoTo = "message.php";
}

$Recordset = $conn->query($deleteSQL) or die($conn->error);

if ($delGoTo) {
	header("Location: $delGoTo");
    exit;
}
?>