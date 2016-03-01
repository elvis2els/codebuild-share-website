<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('checkuser.php'); ?>
<?php require_once('Logout.php'); ?>
<?php require_once('delDir.php'); ?>
<?php
if (!isset($_POST['proId'])) {
	header("Location: usrProblem.php");
}
?>
<?php
$colname_Recordset_Problem = $_POST['proId'];
$query_Recordset_Problem = sprintf("SELECT name, nSol FROM problem WHERE id = %s", GetSQLValueString($colname_Recordset_Problem, "int", $conn));
$Recordset_Problem = $conn->query($query_Recordset_Problem) or die($conn->error);
$row_Recordset_Problem = $Recordset_Problem->fetch_assoc();
$totalRows_Recordset_Problem = mysqli_num_rows($Recordset_Problem);
?>
<?php
//初始化传过来的参数
$algName = $_POST['algName'];
$language = $_POST['language'];
if (isset($_POST['exe_file'])) {
	$exefile = $_POST['exe_file'];
}else{
	$exefile = "";
}
if (isset($_POST['exe_func'])) {
	$exefunc = $_POST['exe_func'];
}else{
	$exefunc = "";
}
$ifiletype = $_POST['ifiletype'];
$ofiletype = $_POST['ofiletype'];
date_default_timezone_set('Asia/Shanghai');
$date = date('Y-m-d H:i:s');

switch($language) {
	case "C++":
	case "exe":
		$exefile = $_POST['exe_file'].".exe";
		$exefunc = $_POST['exe_file'];
		break;
	case "javac":
		$exefunc = $_POST['exe_func'];
		break;
}

//算法插入数据库
$insertSQL = sprintf("INSERT INTO `solution` (pro_id, usr_id, name, language, file, function, downloadfile, ifiletype, ofiletype, createDate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
			GetSQLValueString($_POST['proId'], "int", $conn),
			GetSQLValueString($_SESSION['MM_UserId'], "int", $conn),
			GetSQLValueString($algName, "text", $conn),
			GetSQLValueString($language, "text", $conn),
			GetSQLValueString($exefile, "text", $conn),
			GetSQLValueString($exefunc, "text", $conn),
			GetSQLValueString($_FILES["file"]["name"], "text", $conn),
			GetSQLValueString($ifiletype, "text", $conn),
			GetSQLValueString($ofiletype, "text", $conn),
			GetSQLValueString($date, "date", $conn));
				
$conn->query($insertSQL) or die($conn->error);
$SolNO = $conn->insert_id;
session_commit();

//更新problem表，使nSol+1
$nSol = GetSQLValueString($row_Recordset_Problem['nSol'] + 1, "int", $conn);
$updateSQL = sprintf("UPDATE `problem` SET `nSol` = $nSol WHERE `id` = $colname_Recordset_Problem");
$conn->query($updateSQL) or die($conn->error);

//上传算法存储路径
$path='file\Solution\Sol-'.$SolNO;
mkdir($path, 0777, true);

//算法描述文件存储路径
$Descfilepath=$path.'\Desc.txt';
if ($_FILES["Descfile"]["type"] == "text/plain") {
	if ($_FILES["Descfile"]["error"] > 0) {
	    $Descfile = "error";
	}else{
		$Descfile = "success";
		move_uploaded_file($_FILES["Descfile"]["tmp_name"],"$Descfilepath");
	}
}else{
	$Descfile = "error";
}

//算法文件存储路径
$filename = iconv('utf-8', 'gb2312', $_FILES["file"]["name"]); //转换文件名格式，防止中文文件名存储时乱码
$filepath = $path."\download\\".$filename;
if ($_FILES["file"]["type"] == "application/x-zip-compressed") {
	if ($_FILES["file"]["error"] > 0) {
		$File = "error";
	}else{
		$File = "success";
		mkdir($path.'\download', 0777, true);
		if (move_uploaded_file($_FILES["file"]["tmp_name"],"$filepath")) {
			$Upload = "success";
		}else{
			$Upload = "error";
		}
		
		
		//开始解压与编译
		$procepath = $path.'\Procedure';
		//若需要编译才执行下面代码
		if ($language == "C++" || $language == "javac") {
			$tmppath = $path.'\tmp';
			mkdir($tmppath);
			$cbatpath=getcwd()."\\$path\\compile.bat";
			switch($language){//编译使用先创建.bat文件再运行的方式
				case "C++":		
					$fp = fopen($cbatpath,'w') or die("Unable to open file!");
					$ccmd = "@cd ".getcwd()."\\".$tmppath;	//要用绝对路径来写
					fwrite($fp,$ccmd."\r\n");
					$ccmd = "@g++ -o ..\\Procedure\\$exefunc.exe  $exefunc.cpp";//c++编译指令
					fwrite($fp,$ccmd."\r\n");
					fclose($fp);
					$clanguage='exe';
					break;
				case "javac":
					$fp=fopen($cbatpath,'w') or die("Unable to open file!");
					$ccmd="@cd ".getcwd()."\\".$tmppath;
					fwrite($fp,$ccmd."\r\n");
					$ccmd="@javac -d ..\\Procedure $exefunc.java";//java编译指令
					fwrite($fp,$ccmd."\r\n");
					fclose($fp);
					$clanguage='java';
					break;
			}
		}
		else
			$tmppath = $procepath;
		mkdir($procepath); 		
		
		
		//解压,注：仅支持压缩包名和文件名均为英文
		$zip = new ZipArchive;
		if($zip->open($filepath) === TRUE){
			$zip->extractTo($tmppath);
			$zip->close();
			$logzip = "success";
		}
		else
			$logzip = "error";
		
		if ($language == "C++" || $language == "javac") {
			unset($output);
			unset($status);
			exec($cbatpath,$output,$status);
			if (!$status) {
				$logexe = "success";
			}else{
				$logexe = "error";
			}
			//插入成功后删除tmp文件夹和.bat文件 
			delDirAndFile($tmppath);
			unlink("$cbatpath"); 
		}
		else
			$logexe = "success";
    
    	 
	}
}else {
	$File = "TypeError";
}

//若是前面某一步出现错误，则撤销之前所有步骤
if ($Descfile != "success" || $File != "success" || $Upload != "success" || $logzip != "success" || $logexe != "success") {
	//删除上传的所有文件
	delDirAndFile($path);
	
	//更新problem表，使nSol恢复插入前的值
	$nSol = GetSQLValueString($nSol - 1, "int", $conn);
	$updateSQL = sprintf("UPDATE `problem` SET `nSol` = $nSol WHERE `id` = $colname_Recordset_Problem");
	$conn->query($updateSQL) or die($conn->error);
	
	//删除solution表中之前插入的数据
	$deleteSQL = sprintf("DELETE FROM `solution` WHERE `id` = %s", GetSQLValueString($SolNO, "int", $conn));
	$conn->query($deleteSQL) or die ($conn->error);

	
}
?>
<?php
if ($_SESSION['MM_UserGroup'] == "admin") {
	//查询contact数量
	$maxRows_Recordset_Contact = 3;
	$query_Recordset_Contact = sprintf("SELECT * FROM contact WHERE isread=0 ORDER BY date DESC");
	$Recordset_Contact = $conn->query($query_Recordset_Contact) or die($conn->error);
	$totalRows_Recordset_Contact = mysqli_num_rows($Recordset_Contact);
	
	$query_limit_Recordset_Contact = sprintf("%s LIMIT %d, %d", $query_Recordset_Contact, 0, $maxRows_Recordset_Contact);
	$Recordset_limit_Contact = $conn->query($query_limit_Recordset_Contact) or die($conn->error);
	$row_Recordset_limit_Contact = $Recordset_limit_Contact->fetch_assoc();
}
?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<title>Clode Code</title>

<!--[支持ie9以下]>-->
<script src="http://cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
<script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<link rel="shortcut icon" href="images/favicon.png" />

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.css" rel="stylesheet">

<!-- Add custom CSS here -->
<link href="css/sb-admin.css" rel="stylesheet">
<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
<!-- Page Specific CSS -->
<link rel="stylesheet" href="css/morris-0.4.3.min.css">
</head>

<body>
<div id="wrapper"> 
  
  <!-- Sidebar -->
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation"> 
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="index.php"  title="Knowledge Base Theme"> <img src="images/logo.png" alt="Knowledge Base Theme"> </a> </div>
    
    <!-- 侧边栏 -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav side-nav">
        <li><a href="usrAdmin.php"><i class="fa fa-dashboard"></i> Profile Page</a></li>
		<?php
		if ($_SESSION['MM_UserGroup'] == "user") 
			echo '<li><a href="usrInfo.php"><i class="fa fa-bar-chart-o"></i> Personal Information</a></li>';
		else
			echo '<li><a href="usrManage.php"><i class="fa fa-bar-chart-o"></i> User Management</a></li>';
		?>
        <li><a href="usrProblem.php"><i class="fa fa-table"></i> My Algorithm Question</a></li>
        <li><a href="addProblem.php"><i class="fa fa-edit"></i> Add Algorithm Question</a></li>
        <li><a href="usrAlgorithm.php"><i class="fa fa-font"></i> My Algorithm</a></li>
        <li class="active"><a href="#"><i class="fa fa-desktop"></i> Add Algorithm</a></li>
        <li><a href="resultList.php"><i class="fa fa-desktop"></i> Results List</a></li>
		<?php
		if ($_SESSION['MM_UserGroup'] == "admin")
			echo '<li><a href="message.php"><i class="fa fa-file"></i> Messages</a></li>';
		?>
		 <li><a href="settings.php"><i class="fa fa-wrench"></i> Settings</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right navbar-user">
        <li class="dropdown messages-dropdown" <?php if($_SESSION['MM_UserGroup'] == "user") echo 'style="display:none"'; ?>> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Messages <span class="badge"><?php if($_SESSION['MM_UserGroup'] == "admin") echo $totalRows_Recordset_Contact; ?></span> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li class="dropdown-header"><?php if($_SESSION['MM_UserGroup'] == "admin") echo $totalRows_Recordset_Contact; ?> New Messages</li>
			<?php if($_SESSION['MM_UserGroup'] == "admin") do { ?>
			<?php
			$userId = $row_Recordset_limit_Contact['usr_id'];
			$querySQL = sprintf("SELECT name FROM user_info WHERE usr_id = $userId");
			$result = $conn->query($querySQL) or die($conn->error);
			$row_result = $result->fetch_assoc();
			?>
            <li class="message-preview"> 
				<a href="message.php?contactId=<?php echo $row_Recordset_limit_Contact['id'] ?>"> 
					<span class="avatar"> <span class="name"><?php echo $row_result['name'] ?>:</span> 
					<span class="message"><?php echo substr($row_Recordset_limit_Contact['message'], 0, 30) ?>...</span> 
					<span class="time"><i class="fa fa-clock-o"></i> <?php echo $row_Recordset_limit_Contact['date'] ?></span> 
				</a> 
			</li>
            <li class="divider"></li>
			<?php } while ($row_Recordset_limit_Contact = $Recordset_limit_Contact->fetch_assoc()); ?>
            <li><a href="message.php">View Inbox <span class="badge"><?php if($_SESSION['MM_UserGroup'] == "admin") echo $totalRows_Recordset_Contact; ?></span></a></li>
          </ul>
        </li>
        <li class="dropdown user-dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['MM_Username'] ?><b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="usrAdmin.php"><i class="fa fa-user"></i> Profile</a></li>
            <li><a href="settings.php"><i class="fa fa-gear"></i> Settings</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo $logoutAction ?>"><i class="fa fa-power-off"></i> Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <!-- /.navbar-collapse --> 
  </nav>
  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1>Algorithm</h1>
        <ol class="breadcrumb">
          <li><a href="usrAdmin.php"><i class="fa fa-dashboard"></i> Profile Page</a></li>
          <li class="active"><i class="fa fa-edit"></i> Add Algorithm</li>
        </ol>
        <div class="alert alert-info alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          There is Upload results. </div>
      </div>
    </div>
    <!-- /.row-->
    
    <div class="row" id="div_addProblem">
      <div class="col-lg-12">
        <h2 id="head">Add Algorithm</h2>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Upload Results</h3>
          </div>
          <div class="panel-body">
            <div class="flot-chart">
              <div class="flot-chart-content" id="flot-chart-line">
                <table width="800" border="0" align="center">
                  <tr>
                    <td><span class="glyphicon glyphicon-remove"></span>
                      <?php
							if ($_FILES["Descfile"]["type"] == "text/plain") {
								if ($_FILES["Descfile"]["error"] > 0) {
	    							echo '<img src="images\cross.png" />Describes File upload error: ' . $_FILES["Descfile"]["error"];
								}else{
	    							echo'<img src="images\check.png" />Description File uploaded successfully';
								}
							}else{
								echo '<img src="images\cross.png" />Describes File type error';
							}
							?></td>
                    <td><?php
							if ($File == "error" || $File == "TypeError") {
								if ($File == "error") {
								echo '<img src="images\cross.png" />File upload error: ' . $_FILES["file"]["error"]; 
								}else{
									echo '<img src="images\cross.png" />Type of file upload error';
								}
							}else{
								echo '
									<div class="form-group input-group">
                  						<span class="input-group-addon"> Upload File Name</span>
                  						<input name="UploadName" type="text" class="form-control"  disabled value="'.$_FILES["file"]["name"].'">
              	 					</div>';
							}
							?></td>
                  </tr>
                  <tr>
                    <td><?php
							if ($File == "success") {
								echo '
									<div class="form-group input-group">
                  						<span class="input-group-addon"> Upload File Type</span>
                  						<input name="UploadName" type="text" class="form-control"  disabled value="'.$_FILES["file"]["type"].'">
              	 					</div>';
							}
							?></td>
                    <td><?php
							if ($File == "success") {
								echo '
									<div class="form-group input-group">
                						<span class="input-group-addon">Upload File Size</span>';
								if ($_FILES["file"]["size"] < 1024) {
                					echo '<input type="text" class="form-control" disabled value="'.$_FILES["file"]["size"].'">
                						  <span class="input-group-addon">B</span>';
								}else if($_FILES["file"]["size"] < 1024*1024){
									echo '<input type="text" class="form-control" disabled value="'.($_FILES["file"]["size"] / 1024).'">
                						  <span class="input-group-addon">KB</span>';	
								}else{
									echo '<input type="text" class="form-control" disabled value="'.($_FILES["file"]["size"] / (1024*1024)).'">
                						  <span class="input-group-addon">MB</span>';
								}
              					echo '</div>';
							}
							?></td>
                  </tr>
                  <tr>
                    <td><?php
							if ($Upload == "success") {
                            	echo '<img src="images\check.png" />'."Algorithm file ".$_FILES["file"]["name"]." upload complete";
							}
							?></td>
                    <td><?php
							if ($Upload == "success") {
                            	echo '<img src="images\check.png" />'."The algorithm file has completed decompression";
							}else{
								echo '<img src="images\cross.png" />'."The algorithm file decompression failure";	
							}
							?></td>
                  </tr>
                  <tr>
                    <td><?php
							if ($logexe == "success") {
								echo '<img src="images\check.png" />'."Algorithms compile complete";
							}else{
								echo '<img src="images\cross.png" />'."The algorithm fails to compile";
							}
							?></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /#div_addQuestion  --> 
    
  </div>
  <!-- /#page-wrapper --> 
  
</div>
<!-- /#wrapper --> 

<!-- JavaScript --> 
<script src="http://cdn.bootcss.com/jquery/2.1.3/jquery.min.js"></script> 
<script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

<!-- Page Specific Plugins --> <script src="http://cdn.bootcss.com/raphael/2.1.2/raphael-min.js"></script> 
<script src="http://cdn.bootcss.com/morris.js/0.5.1/morris.min.js"></script> 
<script src="js/morris/chart-data-morris.js"></script> 
<script src="js/tablesorter/jquery.tablesorter.js"></script> 
<script src="js/tablesorter/tables.js"></script>
</body>
</html>
<?php
mysqli_free_result($Recordset_Problem);
?>