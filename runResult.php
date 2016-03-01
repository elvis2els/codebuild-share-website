<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('checkuser.php'); ?>
<?php require_once('Logout.php'); ?>
<?php
include("iofiletype.php");

$leave = $_SESSION['MM_UserId'];

$query_Recordset_Alg = sprintf("SELECT * FROM solution WHERE id = %s", GetSQLValueString($_POST['algId'], "int", $conn));
$Recordset_Alg = $conn->query($query_Recordset_Alg) or die($conn->error);
$row_Recordset_Alg = $Recordset_Alg->fetch_assoc();
$totalRows_Recordset_Alg = mysqli_num_rows($Recordset_Alg);

$ifiletype = $row_Recordset_Alg['ifiletype'];
$ofiletype = $row_Recordset_Alg['ofiletype'];
$algName = $row_Recordset_Alg['name'];
$algId = $row_Recordset_Alg['id'];
$usrId = $_SESSION['MM_UserId'];

date_default_timezone_set('Asia/Shanghai');

//在数据库中添加任务记录
$insertSQL = sprintf("INSERT INTO run SET sol_id = %s, usr_id = %s, status = -1", GetSQLValueString($algId, "int", $conn), GetSQLValueString($usrId, "int", $conn));
$result = $conn->query($insertSQL) or die($conn->error);
$runId = $conn->insert_id;
//开始创建任务工作文件夹
$path='file\task\task-'.$runId;
$inputpath=$path."\\input.$ifiletype";
mkdir($path, 0777, true);

//输入文件上传
if ($_FILES["file"]["type"] == $iofiletype[$ifiletype]){
 	if ($_FILES["file"]["error"] > 0){
 		$tfiletype = "uploadError";
    }
    else{
		$tfiletype = "success";
    	move_uploaded_file($_FILES["file"]["tmp_name"],"$inputpath");
    }
 }
 $tfiletype = "typeError";
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
        <!--<li><a href="addAlgorithm.php"><i class="fa fa-desktop"></i> Add Algorithm</a></li>-->
        <li><a href="resultList.php"><i class="fa fa-wrench"></i> Results List</a></li>
		<?php
		if ($_SESSION['MM_UserGroup'] == "admin")
			echo '<li><a href="message.php"><i class="fa fa-file"></i> Messages</a></li>';
		?>
        <li class="active"><a href="#"><i class="fa fa-file"></i> Running Results</a></li>
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
        <h1>Running Results</h1>
        <ol class="breadcrumb">
          <li><a href="usrAdmin.php"><i class="fa fa-dashboard"></i> Profile Page</a></li>
          <li class="active"><i class="fa fa-table"></i> Running Results</li>
        </ol>
        <div class="alert alert-info alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          Manage your uploaded algorithm here. </div>
      </div>
    </div>
    <!-- /.row-->
    
    <div class="row" id="div_addInfo">
      <div class="col-lg-12">
        <h2 id="head2">View Algorithm Running Result</h2>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Result Interface</h3>
          </div>
          <div class="panel-body">
            <div class="flot-chart">
              <div class="flot-chart-content" id="flot-chart-line"> 
              	<table>
              		<tr>
                		<td>Input File Name:</td>
                    	<td><?php echo $_FILES["file"]["name"] ?></td>
                	</tr>
                	<tr>
                		<td>Input File Type:</td>
                    	<td><?php echo $_FILES["file"]["type"] ?></td>
                	</tr>
            	    <tr>
               		 	<td>Input File Size:</td>
                	    <td>
						<?php 
						if ($_FILES["file"]["size"] < 1024) {
                			echo $_FILES["file"]["size"].' B';
						}
						else if($_FILES["file"]["size"] < 1024*1024) {
							echo ($_FILES["file"]["size"] / 1024).' KB';	
						}
						else {
							echo ($_FILES["file"]["size"] / (1024*1024)).' MB';
								}
						?>
                        </td>
               	 	</tr>
              	</table>
                <br>
			  	<div id="running"><img src="images\loading.gif" />Alogrithm is runing...<br/>
              		You can see your task result in <a href="resultList.php">Running List.</a><br/>
              		After you leave, we will provide you complete arithmetic tasks<br/>
              	</div>
              	<div id="err" hidden="hidden"><img src="images\cross.png" /><br/><br/>Algorithm running task commit failed</div>
              	<div id="timeout" hidden="hidden"><img src="images\cross.png" /><br/><br/>Algorithms running task commit time-out</div>
              </div>   
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /#div_addInfo  --> 
    
  </div>
  <!-- /#page-wrapper --> 
  
</div>
<!-- /#wrapper --> 

<!-- JavaScript --> 
<script src="http://cdn.bootcss.com/jquery/2.1.3/jquery.min.js"></script> 
<script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

<!--用于分页显示和弹窗--> 
<script src="laypage/laypage.js"></script> 
<script src="layer/layer.js"></script> 

<!-- Page Specific Plugins --> <script src="http://cdn.bootcss.com/raphael/2.1.2/raphael-min.js"></script> 
<script src="http://cdn.bootcss.com/morris.js/0.5.1/morris.min.js"></script> 
<script src="js/morris/chart-data-morris.js"></script> 
<script src="js/tablesorter/jquery.tablesorter.js"></script> 
<script src="js/tablesorter/tables.js"></script>
</body>
</html>

<script type="text/javascript" language="javascript">
function callexe(){
	var xmlhttp;
	var time;
	var responseText;
	var post = <?php echo '"leave='.$leave.'&algId='.$algId.'&runId='.$runId.'&ifiletype='.$ifiletype.'"' ?>;
	if (window.XMLHttpRequest)	//用于判断浏览器类型
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function(){
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){	//正在接收，status属性描述了HTTP状态代码
			responseText = xmlhttp.responseText;
			if(responseText == 0){
				//请求/运行失败
				document.getElementById("running").hidden = "hidden";
				document.getElementById("timeout").hidden = "hidden";
				document.getElementById("err").hidden = undefined;
			}
		}
	}
	xmlhttp.open("POST","run.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(post);
	<?php
	if(!$leave)echo 'time=setTimeout(timeout,20000);';
	?>
}

function timeout(){//超时处理
	document.getElementById("running").hidden="hidden";
	document.getElementById("timeout").hidden=undefined;
}
callexe();
</script>