<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('checkuser.php'); ?>
<?php require_once('Logout.php'); ?>

<?php //增加时插入数据库
if (isset($_POST['submit'])) {
	date_default_timezone_set('Asia/Shanghai');
	$name = GetSQLValueString($_POST['name'], "text", $conn);
	$tag = GetSQLValueString($_POST['tag'], "text", $conn);
	$info = GetSQLValueString($_POST['info'], "text", $conn);
	$public = GetSQLValueString($_POST['optionsRadios'], "int", $conn);
	$usrId = GetSQLValueString($_SESSION['MM_UserId'], "int", $conn);
	$date = GetSQLValueString(date('Y-m-d H:i:s'), "date", $conn);
	$insertSQL = sprintf("INSERT INTO `problem` (name, info, usr_id, createDate, lastDate, public, tag) VALUES ($name, $info, $usrId, $date, $date, $public, $tag)");
  	$Result1 = $conn->query($insertSQL) or die($conn->error);
	
	//维护tag表
	$query_Recordset_Tag = sprintf("SELECT * FROM tags WHERE name = $tag");
	$Recordset_Tag = $conn->query($query_Recordset_Tag) or die($conn->error);
	$totalRows_Recordset_Tag = mysqli_num_rows($Recordset_Tag);
	if ($totalRows_Recordset_Tag) {
		$row_Recordset_Tag = $Recordset_Tag->fetch_assoc();
		$tagNumber = $row_Recordset_Tag['number'] + 1;
		$updateTag = sprintf("UPDATE tags SET number = %s WHERE name = $tag", GetSQLValueString($tagNumber, "int", $conn));	
	}
	else
		$updateTag = sprintf("INSERT INTO tags (name) VALUES ($tag)");
	$updateResult = $conn->query($updateTag) or die($conn->error);
	header("Location: usrProblem.php");
}
?>

<?php //编辑时数据库中寻找指定算法问题
if (isset($_GET['proId'])) {
 	$colname_Recordset_Pro = $_GET['proId'];
 	$query_Recordset_Pro = sprintf("SELECT * FROM problem WHERE id = %s", GetSQLValueString($colname_Recordset_Pro, "int", $conn));
 	$Recordset_Pro = $conn->query($query_Recordset_Pro) or die($conn->error);
 	$row_Recordset_Pro = $Recordset_Pro->fetch_assoc();
 	$totalRows_Recordset_Pro = mysqli_num_rows($Recordset_Pro);
}
?>

<?php //编辑时修改数据库
if(isset($_POST['save'])) {
	$query_Recordset_User = sprintf("SELECT name FROM user WHERE id = %s", GetSQLValueString($row_Recordset_Pro['usr_id'], "int", $conn));
	$Recordset_User = $conn->query($query_Recordset_User) or die($conn->error);
	$row_Recordset_User = $Recordset_User->fetch_assoc();
}

//判断修改人是否是该问题的提出者
if (isset($_POST['save']) && $_SESSION['MM_Username'] == $row_Recordset_User['name']) {
	date_default_timezone_set('Asia/Shanghai');
	$colname_Recordset_Pro = GetSQLValueString($_GET['proId'], "int", $conn);
	$name = GetSQLValueString($_POST['chname'], "text", $conn);
	$info = GetSQLValueString($_POST['chinfo'], "text", $conn);
	$public = GetSQLValueString($_POST['choptionsRadios'], "int", $conn);
	$tag = GetSQLValueString($_POST['tag'], "text", $conn);
	$able = GetSQLValueString($_POST['choptionsResolved'], "int", $conn);
	$date = GetSQLValueString(date('Y-m-d H:i:s'), "date", $conn);
	$updateSQL = sprintf("UPDATE problem SET `name` = $name, `info` = $info, `public` = $public, tag = $tag, `able` = $able, `lastDate` = $date WHERE `id` = $colname_Recordset_Pro");
	$Result_update = $conn->query($updateSQL) or die ($conn->error);
	
	//维护tag表
	$used_tag = GetSQLValueString($_POST['usedtag'], "text", $conn);
	if ($tag != $used_tag) {
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
		
		//修改新tag
		$query_Recordset_Tag = sprintf("SELECT * FROM tags WHERE name = $tag");
		$Recordset_Tag = $conn->query($query_Recordset_Tag) or die($conn->error);
		$totalRows_Recordset_Tag = mysqli_num_rows($Recordset_Tag);
		if ($totalRows_Recordset_Tag) { //如果是改成原来就有的tag
			$row_Recordset_Tag = $Recordset_Tag->fetch_assoc();
			$tagNumber = $row_Recordset_Tag['number'] + 1;
			$updateTag = sprintf("UPDATE tags SET number = %s WHERE name = $tag", GetSQLValueString($tagNumber, "int", $conn));	
		}
		else {	//如果是改成原来没有的tag			
			$updateTag = sprintf("INSERT INTO tags (name) VALUES ($tag)");		
		}
		$updateResult = $conn->query($updateTag) or die($conn->error);
	}

	header("Location: usrProblem.php");
}
else if (isset($_POST['save'])) {
	date_default_timezone_set('Asia/Shanghai');
	$colname_Recordset_Pro = GetSQLValueString($_GET['proId'], "int", $conn);
	$tag = GetSQLValueString($_POST['tag'], "text", $conn);
	$able = GetSQLValueString($_POST['choptionsResolved'], "int", $conn);
	$date = GetSQLValueString(date('Y-m-d H:i:s'), "date", $conn);
	$updateSQL = sprintf("UPDATE `problem` SET `tag` = $tag, `able` = $able, `lastDate` = $date WHERE `id` = $colname_Recordset_Pro");
	$Result_update = $conn->query($updateSQL) or die ($conn->error);
	
	//维护tag表
	$used_tag = GetSQLValueString($_POST['usedtag'], "text", $conn);
	if ($tag != $used_tag) {
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
		
		//修改新tag
		$query_Recordset_Tag = sprintf("SELECT * FROM tags WHERE name = $tag");
		$Recordset_Tag = $conn->query($query_Recordset_Tag) or die($conn->error);
		$totalRows_Recordset_Tag = mysqli_num_rows($Recordset_Tag);
		if ($totalRows_Recordset_Tag) { //如果是改成原来就有的tag
			$row_Recordset_Tag = $Recordset_Tag->fetch_assoc();
			$tagNumber = $row_Recordset_Tag['number'] + 1;
			$updateTag = sprintf("UPDATE tags SET number = %s WHERE name = $tag", GetSQLValueString($tagNumber, "int", $conn));	
		}
		else {	//如果是改成原来没有的tag			
			$updateTag = sprintf("INSERT INTO tags (name) VALUES ($tag)");		
		}
		$updateResult = $conn->query($updateTag) or die($conn->error);
	}

	header("Location: usrProblem.php");
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
        <li class="active"><a href="#"><i class="fa fa-edit"></i>
          <?php
			if (isset($_GET['proId'])){
				echo 'Change Algorithm Question';
			}else{
				echo 'Add Algorithm Question';
			}
			?>
          </a></li>
        <li><a href="usrAlgorithm.php"><i class="fa fa-font"></i> My Algorithm</a></li>
        <!--<li><a href="addAlgorithm.php"><i class="fa fa-desktop"></i> Add Algorithm</a></li>-->
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
        <h1>Algorithm Question</h1>
        <ol class="breadcrumb">
          <li><a href="usrAdmin.php"><i class="fa fa-dashboard"></i> Profile Page</a></li>
          <li class="active"><i class="fa fa-edit"></i>
            <?php
			  if (isset($_GET['proId'])){
				  echo ' Change Algorithm Problem';
			  }else{
				  echo ' Add Algorithm Problem';
			  }
			  ?>
          </li>
        </ol>
        <div class="alert alert-info alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php
			if (isset($_GET['proId'])){
				echo 'Change algorithmic problems here.';
			}else{
				echo 'Add algorithmic problems here.';
			}
			?>
        </div>
      </div>
    </div>
    <!-- /.row-->
    
    <div class="row" id="div_addProblem" <?php if(isset($_GET['proId'])) echo "hidden" ?>>
      <div class="col-lg-12">
        <h2 id="head">Add Algorithm Question</h2>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Add Interface</h3>
          </div>
          <div class="panel-body">
            <div class="flot-chart">
              <div class="flot-chart-content" id="flot-chart-line">
                <form id="addPro" name="form1"  action="" method="post">
                  <table width="500" border="0" align="center">
                    <tr>
                      <td colspan="2"><div class="form-group input-group"> <span class="input-group-addon"> Algorithm Name</span>
                          <input name="name" type="text" class="form-control" placeholder="ProblemName" size="230" required>
                        </div></td>
                    </tr>
					<tr>
                      <td colspan="2"><div class="form-group input-group"> <span class="input-group-addon"> Tag</span>
                          <input name="tag" type="text" class="form-control" placeholder="ProblemTag" size="230" required>
                        </div></td>
                    </tr>
                    <tr>
                      <td colspan="2"><div class="form-group">
                          <label>Algorithm description of the problem</label>
                          <textarea class="form-control" rows="8" name="info" required></textarea>
                        </div></td>
                    </tr>
                    <tr>
                      <td colspan="2"><div class="form-group">
                          <label>Publicity</label>
                          <div class="radio">
                            <label>
                              <input type="radio" name="optionsRadios" id="optionsRadios1" value="1" checked>
                              Public </label>
                          </div>
                          <div class="radio">
                            <label>
                              <input type="radio" name="optionsRadios" id="optionsRadios2" value="0">
                              Private </label>
                          </div>
                        </div></td>
                    </tr>
                    <tr>
                      <td><input name="submit" type="submit" class="btn btn-default" value="Add"></td>
                      <td><input name="reset" type="reset" class="btn btn-default" value="Reset"></td>
                    </tr>
                  </table>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /#div_addQuestion  -->
    
    <div class="row" id="div_changeProblem" <?php if(!isset($_GET['proId'])) echo "hidden" ?>>
      <div class="col-lg-12">
        <h2 id="head2">Change Algorithm Question</h2>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Change Interface</h3>
          </div>
          <div class="panel-body">
            <div class="flot-chart">
              <div class="flot-chart-content" id="flot-chart-line">
                <form id="addPro" name="form1"  action="" method="post">
                  <table width="500" border="0" align="center">
                    <tr>
                      <td colspan="2"><div class="form-group input-group"> <span class="input-group-addon"> Algorithm Name</span>
                          <input name="chname" type="text" class="form-control" placeholder="ProblemName" size="230" required value="<?php if(isset($_GET['proId'])){ echo $row_Recordset_Pro['name'];} ?>" <?php if($row_Recordset_Pro['usr_id'] != $_SESSION['MM_UserId']) echo "disabled" ?>>
                        </div></td>
                    </tr>
					<tr>
                      <td colspan="2"><div class="form-group input-group"> <span class="input-group-addon"> Tag</span>
                          <input name="tag" type="text" class="form-control" placeholder="ProblemTag" size="230" required value="<?php if(isset($_GET['proId'])){ echo $row_Recordset_Pro['tag'];} ?>">
                        </div>
					  <input name="usedtag" type="hidden" value="<?php if(isset($_GET['proId'])){ echo $row_Recordset_Pro['tag'];} ?>">
					  </td>
                    </tr>
                    <tr>
                      <td colspan="2"><div class="form-group">
                          <label>Algorithm description of the problem</label>
                          <textarea class="form-control" rows="8" name="chinfo" required <?php if($row_Recordset_Pro['usr_id'] != $_SESSION['MM_UserId']) echo "disabled" ?>><?php if(isset($_GET['proId'])){ echo $row_Recordset_Pro['info'];} ?>
</textarea>
                        </div></td>
                    </tr>
                    <tr>
                      <td><div class="form-group">
                          <label>Publicity</label>
                          <div class="radio">
                            <label>
                              <input type="radio" name="choptionsRadios" id="optionsRadios1" value="1" <?php if(isset($_GET['proId']) && $row_Recordset_Pro['public']==1) echo 'checked'; ?> <?php if($row_Recordset_Pro['usr_id'] != $_SESSION['MM_UserId']) echo "disabled" ?>>
                              Public </label>
                          </div>
                          <div class="radio">
                            <label>
                              <input type="radio" name="choptionsRadios" id="optionsRadios2" value="0" <?php if(isset($_GET['proId']) && $row_Recordset_Pro['public']==0) echo 'checked'; ?> <?php if($row_Recordset_Pro['usr_id'] != $_SESSION['MM_UserId']) echo "disabled" ?>>
                              Private </label>
                          </div>
                        </div></td>
                      <td><div class="form-group">
                          <label>Is resolved</label>
                          <div class="radio">
                            <label>
                              <input type="radio" name="choptionsResolved" id="optionsRadios3" value="1" <?php if(isset($_GET['proId']) && $row_Recordset_Pro['able']==1) echo 'checked'; ?>>
                              Solved </label>
                          </div>
                          <div class="radio">
                            <label>
                              <input type="radio" name="choptionsResolved" id="optionsRadios4" value="0" <?php if(isset($_GET['proId']) && $row_Recordset_Pro['able']==0) echo 'checked'; ?>>
                              Unsolved </label>
                          </div>
                        </div></td>
                    </tr>
                    <tr>
                      <td><input name="save" type="submit" class="btn btn-default" value="Save"></td>
                    </tr>
                  </table>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /#div_changeQuestion  --> 
    
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