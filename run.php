<?php include('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>

<?php
/*
 * 执行算法并返回执行信息
 * 需要:
 * 	-事先创建任务工作文件夹，并将输入文件命名为input.（后缀）存入文件夹中
 * 	-当$leave为有效值时，应事先在数据库中添加算法任务记录
 * 接收的参数有:$leave,$sol_id,$rand,$ifiletype,$runId
 * 返回值:
 * 	当$leave为有效值时：	
 * 		0-算法任务提交失败				
 * 		1-算法任务提交成功			
 * 	
 */ 
 if (!isset($_SESSION)) {
  session_start();
 }
 
 $leave = $_POST['leave'];
 $solId = $_POST['algId'];
 $runId = $_POST['runId'];

 if($leave && !$_SESSION['MM_UserId']) die ("0");
 
 //获取任务工作文件夹
 ignore_user_abort(true);	//设置与客户机断开不会终止脚本的执行
 		
 //验证参数是否与数据库task表中信息符合
 $query_Recordset_Run = sprintf("SELECT 0 FROM run WHERE id = %s AND usr_id = %s AND sol_id = %s AND status = -1", 
				GetSQLValueString($runId, "int", $conn),
				GetSQLValueString($_SESSION['MM_UserId'], "int", $conn),
				GetSQLValueString($solId, "int", $conn));
 $Recordset_Run = $conn->query($query_Recordset_Run) or die($conn->error);
 $totalRows_Recordset_Run = mysqli_num_rows($Recordset_Run);
 if(!$totalRows_Recordset_Run) die("0");
 
 $path = dirname(__FILE__).'\\file\\task\\task-'.$runId;	//注意！__FILE__是两个下划线！

 session_commit();	//解除对session的占用
 
 $ifiletype = $_POST['ifiletype'];
 $query_Recordset_Alg = sprintf("SELECT * FROM solution WHERE id = %s", GetSQLValueString($solId, "int", $conn));
 $Recordset_Alg = $conn->query($query_Recordset_Alg) or die($conn->error);
 $row_Recordset_Alg = $Recordset_Alg->fetch_assoc();
 $totalRows_Recordset_Alg = mysqli_num_rows($Recordset_Alg);
 
 if(!$totalRows_Recordset_Alg || $ifiletype != $row_Recordset_Alg['ifiletype']) die("0");
 $fpath = dirname(__FILE__).'\\file\\Solution\\Sol-'.$solId.'\\Procedure';
 $ofiletype = $row_Recordset_Alg['ofiletype'];
 $inputpath = $path.'\\input.'.$ifiletype;
 $outputpath = $path.'\\output.'.$ofiletype;
 $file = $row_Recordset_Alg['file'];	//算法入口文件
 $func = $row_Recordset_Alg['function'];	//算法入口函数
 $language = $row_Recordset_Alg['language'];
 
 //编写算法执行命令
 switch($language){
 	case "exe":
	case "C++":
 		$exepath = $fpath."\\$file";
 		$cmd = '""'.trim($exepath).'" "'.$inputpath.'" "'.$outputpath.'""';	//整个cmd要作为一个字符串！第一个参数为输入文件路径，第二个参数为输出文件路径	
 		break;
 	case "java":
	case "javac":
		$cmd = '"java -classpath "'.$fpath.'" '.$func.' "'.$inputpath.'" "'.$outputpath.'"';
		break;
	default:
		die("0");
		break;
 }
 
 //保证脚本结束后能够结束具体算法进程
 function shutdown_func(){
 	global $proc_pid;
 	exec("taskkill /f /t /pid $proc_pid");
 }
 
 register_shutdown_function('shutdown_func');	//定义PHP程序执行完成后执行的函数
 $descriptorspec = array(
   0 => array("pipe", "r"),  // 标准输入，子进程从此管道中读取数据
   1 => array("pipe", "w"),  // 标准输出，子进程向此管道中写入数据
   2 => array("file", dirname(__FILE__)."\\file\\error.txt", "a") // 标准错误，写入到一个文件
);
 

 //算法开始运行
 include("Timer.php");
 $timer = new Timer();
 $timer->start();
 $process = proc_open($cmd,$descriptorspec,$pipes);
 $procStatus = proc_get_status($process);
 $proc_pid = $procStatus['pid'];
 
 $updateSQL = sprintf("UPDATE run SET beginTime = now(), status = %s WHERE id = %s",
			GetSQLValueString($proc_pid, "int", $conn),
			GetSQLValueString($runId, "int", $conn));
 while(!$conn->query($updateSQL));
 set_time_limit(0);
 
 //对算法的标准输入输出流，暂未设计相关功能
 fclose($pipes[0]);
 fclose($pipes[1]);
 
 //脚本等待算法执行结束
 mysqli_free_result($Recordset_Run);
 mysqli_free_result($Recordset_Alg);
 mysqli_close($conn);//暂时关闭数据库连接
 $procStatus=proc_get_status($process);
 
 proc_close($process);//等待进程结束
 $timer->stop();
 $timer->spent();
 unset($proc_pid);

 //算法结束时对数据库相关数据进行操作
 include('Connections/conn.php');
 $updateSQL = sprintf("UPDATE run SET endTime = now(), status = 0, runTime = %s WHERE id = %s", GetSQLValueString($timer->getTimeSpent(), "double", $conn), GetSQLValueString($runId, "int", $conn));
 $conn->query($updateSQL);
 
 //更新算法的最佳执行时间和运行次数
 $query_Recordset_Alg = sprintf("SELECT * FROM solution WHERE id = %s", GetSQLValueString($solId, "int", $conn));
 $Recordset_Alg = $conn->query($query_Recordset_Alg) or die($conn->error);
 $row_Recordset_Alg = $Recordset_Alg->fetch_assoc();
 
 $runTimes = $row_Recordset_Alg['runTimes'] + 1;
 if (!$row_Recordset_Alg['bestRunTime'] || $row_Recordset_Alg['bestRunTime'] > $timer->getTimeSpent()) {
	$updateSQL = sprintf("UPDATE solution SET runTimes = %s, bestRunTime = %s WHERE id = %s",
				GetSQLValueString($runTimes, "int", $conn),
				GetSQLValueString($timer->getTimeSpent(), "double", $conn),
				GetSQLValueString($solId, "int", $conn)); 
 }
 else {
	$updateSQL = sprintf("UPDATE solution SET runTimes = $runTimes WHERE id = $solId");
 }
 $conn->query($updateSQL);
 
 echo "1";//算法执行完毕时输出信息
?>
