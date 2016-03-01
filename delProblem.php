<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('delDir.php'); ?>

<?php
if (!isset($_GET['proId'])) {
	header("Location: index.php");
}
?>

<?php
//为防止服务器空间被永久占用，删除相应的算法文件和任务文件
$id = $_GET['proId'];
$querySQL = sprintf("SELECT id FROM solution WHERE pro_id=%s", GetSQLValueString($id, "int", $conn));
$result_solId = $conn->query($querySQL) or die($conn->error);
$row_result_solId = $result_solId->fetch_assoc();
$totalRows_result_solId = mysqli_num_rows($result_solId);
if ($totalRows_result_solId) {
	do {
		$queryRun = sprintf("SELECT id FROM run WHERE sol_id=%s", GetSQLValueString($row_result_solId['id'], "int", $conn));
		$result_runId = $conn->query($queryRun) or die($conn->error);
		$row_result_runId = $result_runId->fetch_assoc();
		$totalRows_result_runId = mysqli_num_rows($result_runId);
		if ($totalRows_result_runId) {
			do {
				$path_run = dirname(__FILE__).'\\file\\task\\task-'.$row_result_runId['id'];
				delDirAndFile($path_run);
			} while ($row_result_runId = $result_runId->fetch_assoc());	
				$path_sol = dirname(__FILE__).'\\file\\Solution\\Sol-'.$row_result_solId['id'];
				delDirAndFile($path_sol);
		}
	} while ($row_result_solId = $result_solId->fetch_assoc());
}


$query_Recordset_Problem = sprintf("SELECT tag FROM problem WHERE id = %s", GetSQLValueString($id, "int", $conn));
$Recordset_Problem = $conn->query($query_Recordset_Problem) or die($conn->error);
$row_Recordset_Problem = $Recordset_Problem->fetch_assoc();

//维护tag表
$used_tag = GetSQLValueString($row_Recordset_Problem['tag'], "text", $conn);
//修改原tag，如果原tag不为1则减1，原tag为1则删除	
$queryUsedTag = sprintf("SELECT number FROM tags WHERE name = $used_tag");
$Result_usedTag = $conn->query($queryUsedTag) or die ($conn->error);
$row_Result_usedTag = $Result_usedTag->fetch_assoc();
if ($row_Result_usedTag['number'] > 1) {
	$usedNumber = $row_Result_usedTag['number'] - 1;
	$updateUsedTag = sprintf("UPDATE tags SET number = %s WHERE name = $used_tag", GetSQLValueString($usedNumber, "int", $conn));	
}
else {
	$updateUsedTag = sprintf("DELETE FROM tags WHERE name = $used_tag");
}
$Result_usedUpdate = $conn->query($updateUsedTag) or die ($conn->error);	

//删除problem表中相应的项
$deleteSQL = sprintf("DELETE FROM `problem` WHERE id = %s", GetSQLValueString($id, "int", $conn));
$Recordset_Problem = $conn->query($deleteSQL) or die($conn->error);

$delGoTo = "usrProblem.php";
if ($delGoTo) {
	header("Location: $delGoTo");
    exit;
}
?>