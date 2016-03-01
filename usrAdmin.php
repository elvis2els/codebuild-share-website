<?php require_once('Connections/conn.php'); ?>
<?php require_once('checkuser.php'); ?>
<?php require_once('Logout.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php
//查询拥有的问题数
if ($_SESSION['MM_UserGroup'] == "user") {
	$colname_Recordset_Problem = $_SESSION['MM_UserId'];
	$query_Recordset_Problem = sprintf("SELECT 0 FROM problem WHERE usr_id = %s", GetSQLValueString($colname_Recordset_Problem, "int", $conn));
}
else
	$query_Recordset_Problem = sprintf("SELECT 0 FROM problem");
$Recordset_Problem = $conn->query($query_Recordset_Problem) or die($conn->error);
$totalRows_Recordset_Problem = mysqli_num_rows($Recordset_Problem);
?>
<?php
//查询拥有的算法数
if ($_SESSION['MM_UserGroup'] == "user") {
	$colname_Recordset_Alg = $_SESSION['MM_UserId'];
	$query_Recordset_Alg = sprintf("SELECT 0 FROM solution WHERE usr_id = %s", GetSQLValueString($colname_Recordset_Alg, "int", $conn));
}
else {
	$query_Recordset_Alg =sprintf("SELECT 0 FROM solution");
}
$Recordset_Alg = $conn->query($query_Recordset_Alg) or die($conn->error);
$row_Recordset_Alg = $Recordset_Alg->fetch_assoc();
$totalRows_Recordset_Alg = mysqli_num_rows($Recordset_Alg);
?>
<?php
if ($_SESSION['MM_UserGroup'] == "user") {
	$colname_Recordset_Run = $_SESSION['MM_UserId'];
	$query_Recordset_Run = sprintf("SELECT 0 FROM run WHERE usr_id = %s", GetSQLValueString($colname_Recordset_Run, "int", $conn));
}
else {
	//查询contact数量
	$maxRows_Recordset_Contact = 3;
	$query_Recordset_Contact = sprintf("SELECT * FROM contact WHERE isread=0 ORDER BY date DESC");
	$Recordset_Contact = $conn->query($query_Recordset_Contact) or die($conn->error);
	$totalRows_Recordset_Contact = mysqli_num_rows($Recordset_Contact);
	
	$query_limit_Recordset_Contact = sprintf("%s LIMIT %d, %d", $query_Recordset_Contact, 0, $maxRows_Recordset_Contact);
	$Recordset_limit_Contact = $conn->query($query_limit_Recordset_Contact) or die($conn->error);
	$row_Recordset_limit_Contact = $Recordset_limit_Contact->fetch_assoc();
	
	$query_Recordset_Run = sprintf("SELECT 0 FROM run");
}
$Recordset_Run = $conn->query($query_Recordset_Run) or die($conn->error);
$row_Recordset_Run = $Recordset_Run->fetch_assoc();
$totalRows_Recordset_Run = mysqli_num_rows($Recordset_Run);
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
    
    <!-- 侧边栏-->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav side-nav">
        <li class="active"><a href="#"><i class="fa fa-dashboard"></i> Profile Page</a></li>
		<?php
		if ($_SESSION['MM_UserGroup'] == "user") 
			echo '<li><a href="usrInfo.php"><i class="fa fa-bar-chart-o"></i> Personal Information</a></li>';
		else
			echo '<li><a href="usrManage.php"><i class="fa fa-bar-chart-o"></i> User Management</a></li>';
		?>
        <li><a href="usrProblem.php"><i class="fa fa-table"></i> My Algorithm Question</a></li>
        <li><a href="addProblem.php"><i class="fa fa-edit"></i> Add Algorithm Question</a></li>
        <li><a href="usrAlgorithm.php"><i class="fa fa-font"></i> My Algorithm</a></li>
        <!-- <li><a href="addAlgorithm.php"><i class="fa fa-desktop"></i> Add Algorithm</a></li>-->
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
        <h1>Welcome home</h1>
        <ol class="breadcrumb">
          <li class="active">PROFILE PAGE</li>
        </ol>
        <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          Welcome to the cloud of code, where you'll enjoy the convenience of the code! </div>
      </div>
    </div>
    <!-- /.row -->
    
    <div class="row">
      <div class="col-lg-3">
        <div class="panel panel-info">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6"> <i class="fa fa-comments fa-5x"></i> </div>
              <div class="col-xs-6 text-right">
                <p class="announcement-heading"><?php echo $totalRows_Recordset_Alg ?></p>
                <p class="announcement-text">Algorithms!</p>
              </div>
            </div>
          </div>
          <a href="usrAlgorithm.php">
          <div class="panel-footer announcement-bottom">
            <div class="row">
              <div class="col-xs-6"> View details </div>
              <div class="col-xs-6 text-right"> <i class="fa fa-arrow-circle-right"></i> </div>
            </div>
          </div>
          </a> </div>
      </div>
      <div class="col-lg-3">
        <div class="panel panel-warning">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6"> <i class="fa fa-check fa-5x"></i> </div>
              <div class="col-xs-6 text-right">
                <p class="announcement-heading"><?php echo $totalRows_Recordset_Run ?></p>
                <p class="announcement-text">Result Number</p>
              </div>
            </div>
          </div>
          <a href="resultList.php">
          <div class="panel-footer announcement-bottom">
            <div class="row">
              <div class="col-xs-6"> Complete Tasks </div>
              <div class="col-xs-6 text-right"> <i class="fa fa-arrow-circle-right"></i> </div>
            </div>
          </div>
          </a> </div>
      </div>
      <div class="col-lg-3">
        <div class="panel panel-danger">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6"> <i class="fa fa-tasks fa-5x"></i> </div>
              <div class="col-xs-6 text-right">
                <p class="announcement-heading"><?php echo $totalRows_Recordset_Problem ?></p>
                <p class="announcement-text">Algorithm Question</p>
              </div>
            </div>
          </div>
          <a href="usrProblem.php">
          <div class="panel-footer announcement-bottom">
            <div class="row">
              <div class="col-xs-6"> View details </div>
              <div class="col-xs-6 text-right"> <i class="fa fa-arrow-circle-right"></i> </div>
            </div>
          </div>
          </a> </div>
      </div>
    </div>
    <!-- /.row --> 
    
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
