<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('checkuser.php'); ?>
<?php require_once('Logout.php'); ?>
<?php
	$currentPage = $_SERVER["PHP_SELF"];
?>
<?php
$maxRows_Recordset_User = 6;
$pageNum_Recordset_User = 0;

if (isset($_GET['page'])) {
	$pageNum_Recordset_User = $_GET['page']-1;
}

$startRow_Recordset_User = $pageNum_Recordset_User * $maxRows_Recordset_User;

//查询所有用户信息
$query_Recordset_User = sprintf("SELECT id, name, power FROM user");
$query_limit_Recordset_User = sprintf("%s LIMIT %d, %d", $query_Recordset_User, $startRow_Recordset_User, $maxRows_Recordset_User);
$Recordset_User = $conn->query($query_limit_Recordset_User) or die($conn->error);
$row_Recordset_User = $Recordset_User->fetch_assoc();

//计算总页数
if (isset($_GET['totalRows_Recordset_User'])) {
  $totalRows_Recordset_User = $_GET['totalRows_Recordset_User'];
} else {
  $all_Recordset_User = $conn->query($query_Recordset_User);
  $totalRows_Recordset_User = mysqli_num_rows($all_Recordset_User);
}
$totalPages_Recordset_User = ceil($totalRows_Recordset_User/$maxRows_Recordset_User)-1;

$queryString_Recordset_User = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_User") == false && 
        stristr($param, "totalRows_Recordset_User") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_User = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_User = sprintf("&totalRows_Recordset_User=%d%s", $totalRows_Recordset_User, $queryString_Recordset_User);
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
        <li class="active"><a href="#"><i class="fa fa-bar-chart-o"></i> User Management</a></li>
        <li><a href="usrProblem.php"><i class="fa fa-table"></i> My Algorithm Question</a></li>
        <li><a href="addProblem.php"><i class="fa fa-edit"></i> Add Algorithm Question</a></li>
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
        <h1>User Management</h1>
        <ol class="breadcrumb">
          <li><a href="usrAdmin.php"><i class="fa fa-dashboard"></i> Profile Page</a></li>
          <li class="active"><i class="fa fa-table"></i> User Management</li>
        </ol>
        <div class="alert alert-info alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          Manage all users here. </div>
      </div>
    </div>
    <!-- /.row-->
    
    <div class="row" id="div_addInfo">
      <div class="col-lg-12">
        <h2 id="head2">View User Info</h2>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> User Interface</h3>
          </div>
          <div class="panel-body">
            <div class="flot-chart">
              <div class="flot-chart-content" id="flot-chart-line">
                <table id="exist" width="300" border="0" align="center" class="table table-bordered table-hover tablesorter">
                  <tr>
                    <td>Account name</td>
                    <td>User Group</td>
					<td>User Name</td>
                    <td>User Sex</td>
                    <td>User Phone Number</td>
                    <td>User Email</td>
                    <td>User Job Title</td>
					<td>User Work Place</td>
                    <td colspan="2">Operation</td>
                  </tr>
                  <?php do { ?>
                  <tr>
                    <td><?php echo $row_Recordset_User['name'] ?></td>
                    <td><?php echo $row_Recordset_User['power'] ?></td>
					<?php
					$userId = $row_Recordset_User['id'];
					$query_Recordset_UserInfo = sprintf("SELECT * FROM user_info WHERE usr_id = $userId");
					$Recordset_UserInfo = $conn->query($query_Recordset_UserInfo) or die($conn->error);
					$row_Recordset_UserInfo = $Recordset_UserInfo->fetch_assoc();
					?>
					<td><?php 
						if ($row_Recordset_UserInfo['name'] == '')
							echo 'Do not set the user name!';
						else
							echo $row_Recordset_UserInfo['name'] 
						?></td>
                    <td><?php 
						if ($row_Recordset_UserInfo['sex'] == '')
							echo 'Do not set the sex!';
						else
							echo $row_Recordset_UserInfo['sex'] 
						?></td>
                    <td><?php 
						if ($row_Recordset_UserInfo['phone'] == '')
							echo 'Do not set the phone number!';
						else
							echo $row_Recordset_UserInfo['phone'];
						?></td>
                    <td><?php 
						if ($row_Recordset_UserInfo['email'] == '')
							echo 'Do not set the Email!';
						else
							echo $row_Recordset_UserInfo['email'];
						?></td>
                    <td><?php 
						if ($row_Recordset_UserInfo['job'] == '')
							echo 'Do not set the Job Title!';
						else
							echo $row_Recordset_UserInfo['job'];
						  ?></td>
					<td><?php 
						if ($row_Recordset_UserInfo['workplace'] == '')
							echo 'Do not set the work place!';
						else
							echo $row_Recordset_UserInfo['workplace'];
						  ?></td>
                    <td><button class="btn btn-default" id="del<?php echo $userId ?>" value=<?php echo $userId ?>>Delete</button></td>
                  </tr>
                  <?php } while ($row_Recordset_User = $Recordset_User->fetch_assoc()); ?>
                </table>
                <input type="hidden" id="totalNum" value=<?php echo $totalPages_Recordset_User + 1 ?>>
                <div align="center" id="page">
                  <?php /*?> <?php if ($pageNum_Recordset_Problem > 0) { // Show if not first page ?>
                      <a href="<?php printf("%s?pageNum_Recordset_Problem=%d%s", $currentPage, 0, $queryString_Recordset_Problem); ?>">第一页</a><a href="<?php printf("%s?pageNum_Recordset_Problem=%d%s", $currentPage, max(0, $pageNum_Recordset_Problem - 1), $queryString_Recordset_Problem); ?>">前一页</a>
                    <?php } // Show if not first page ?>
                    
                    <?php echo '第' ?><?php echo $pageNum_Recordset_Problem + 1 .'页'; ?>
                    <?php echo ' / ' ?>
                    <?php echo '总' ?><?php echo $totalPages_Recordset_Problem + 1 .'页'; ?>
                    
					<?php if ($pageNum_Recordset_Problem < $totalPages_Recordset_Problem) { // Show if not last page ?>
  						<a href="<?php printf("%s?pageNum_Recordset_Problem=%d%s", $currentPage, min($totalPages_Recordset_Problem, $pageNum_Recordset_Problem + 1), $queryString_Recordset_Problem); ?>">下一个</a><a href="<?php printf("%s?pageNum_Recordset_Problem=%d%s", $currentPage, $totalPages_Recordset_Problem, $queryString_Recordset_Problem); ?>">最后一页</a>
  					<?php } // Show if not last page ?><?php */?>
                  <div id="pages" align="center"></div>
                </div>
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
<?php
mysqli_free_result($Recordset_User);
?>
<script>
//好像很实用的样子，后端的同学再也不用写分页逻辑了。
laypage({
    cont: 'pages', 
    pages: document.getElementById('totalNum').value, //可以叫服务端把总页数放在某一个隐藏域，再获取。假设我们获取到的是18
    curr: function(){ //通过url获取当前页，也可以同上（pages）方式获取
        var page = location.search.match(/page=(\d+)/);
        return page ? page[1] : 1;
    }(), 
    jump: function(e, first){ //触发分页后的回调
        if(!first){ //一定要加此判断，否则初始时会无限刷新
            location.href = '?page='+e.curr;
        }
    }
});
</script>
<script>
$("button[id^='del']").on('click', function(){
   //询问框
   var id = $(this).val();
	layer.confirm('Are you sure deletion?', {
    btn: ['Sure','Think Again'], //按钮
    shade: false //不显示遮罩
	}, function(){
    layer.msg('Delete Success', {icon: 1});
	window.location.href='del.php?userId=' + id;
	});
});
</script>