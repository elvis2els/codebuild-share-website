<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('checkuser.php'); ?>
<?php require_once('Logout.php'); ?>
<?php
if (!isset($_POST['proId'])) {
	header("Location: usrProblem.php");
}
?>
<?php
//初始化传过来的参数
$colname_Recordset_Problem = $_POST['proId'];
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
          Confirm your add information. </div>
      </div>
    </div>
    <!-- /.row-->
    
    <div class="row" id="div_addProblem">
      <div class="col-lg-12">
        <h2 id="head">Add Algorithm</h2>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Confirm Add Information</h3>
          </div>
          <div class="panel-body">
            <div class="flot-chart">
              <div class="flot-chart-content" id="flot-chart-line">
                <table width="800" border="0" align="center">
                  <tr>
                    <td width="396"><div class="form-group input-group"> <span class="input-group-addon"> Algorithm Name</span>
                        <input name="proName" type="text" class="form-control"  disabled value="<?php echo $algName; ?>">
                      </div></td>
                    <td width="394"><div class="form-group input-group"> <span class="input-group-addon"> Language</span>
                        <input name="proName" type="text" class="form-control"  disabled value="<?php 
								 switch($language){
									case "C++":
										echo "C++(source file)";break;
									case "javac":
										echo "JAVA(source file)";break;
									case "exe":
										echo "EXE(Compiled file)";break;
								}
								 ?>">
                      </div></td>
                  </tr>
                  <tr>
                    <td><?php
							switch($language){
								case "C++":
									echo '<div class="form-group input-group">
                  						  	<span class="input-group-addon"> Executable File Name</span>
                  						  	<input name="proName" type="text" class="form-control"  disabled value="'.$exefile.'">
              	 						  </div>';
							}
							?></td>
                    <td><?php
							switch($language){
								case "javac":
									echo '<div class="form-group input-group">
                  						  	<span class="input-group-addon"> Executable File Name</span>
                  						  	<input name="proName" type="text" class="form-control"  disabled value="'.$exefunc.'">
              	 						  </div>';
							}
							?></td>
                  </tr>
                  <tr>
                    <td><div class="form-group input-group"> <span class="input-group-addon"> Input File Type</span>
                        <input name="proName" type="text" class="form-control"  disabled value="<?php echo $ifiletype; ?>">
                      </div></td>
                    <td><div class="form-group input-group"> <span class="input-group-addon"> Input File Type</span>
                        <input name="proName" type="text" class="form-control"  disabled value="<?php echo $ofiletype; ?>">
                      </div></td>
                  </tr>
                  <form action="addAlgorithm3.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="proId" value="<?php echo $colname_Recordset_Problem; ?>" />
                    <input type="hidden" name="algName" value="<?php echo $algName ?>">
                    <input type="hidden" name="language" value="<?php echo $language ?>">
                    <input type="hidden" name="exe_file" value="<?php echo $exefile ?>">
                    <input type="hidden" name="exe_func" value="<?php echo $exefunc ?>">
                    <input type="hidden" name="ifiletype" value="<?php echo $ifiletype ?>">
                    <input type="hidden" name="ofiletype" value="<?php echo $ofiletype ?>">
                    <tr>
                      <td><div class="form-group">
                          <label>Algorithm description file</label>
                          <input type="file" name="Descfile" accept="text/plain" required>
                        </div></td>
                      <td><div class="form-group">
                          <label>Algorithm file</label>
                          <input type="file" name="file" accept="application/x-zip-compressed" required>
                        </div></td>
                    </tr>
                    <td><input type="button" name="goBack" class="btn btn-default" value="Previous Page" onclick="javascript:window.history.back(-1);"></td>
                    <td><input id="submitAlg" name="submitAlg" type="submit" class="btn btn-default" value="Upload"></td>
                      </tr>
                    
                  </form>
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
