<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('checkuser.php'); ?>
<?php require_once('Logout.php'); ?>
<?php
	$currentPage = $_SERVER["PHP_SELF"];
?>
<?php
$maxRows_Recordset_Problem = 6;
$pageNum_Recordset_Problem = 0;

if (isset($_GET['page'])) {
	$pageNum_Recordset_Problem = $_GET['page']-1;
}

//if (isset($_GET['pageNum_Recordset_Problem'])) {
//  $pageNum_Recordset_Problem = $_GET['pageNum_Recordset_Problem'];
//}
$startRow_Recordset_Problem = $pageNum_Recordset_Problem * $maxRows_Recordset_Problem;

//查询该用户的所有算法问题
if ($_SESSION['MM_UserGroup'] == "user") {
	$colname_Recordset_Problem = $_SESSION['MM_UserId'];
	$query_Recordset_Problem = sprintf("SELECT * FROM problem WHERE usr_id = %s ORDER BY createDate DESC", GetSQLValueString($colname_Recordset_Problem, "int", $conn));
}
else {
	$query_Recordset_Problem = sprintf("SELECT * FROM problem ORDER BY createDate DESC");
}
$query_limit_Recordset_Problem = sprintf("%s LIMIT %d, %d", $query_Recordset_Problem, $startRow_Recordset_Problem, $maxRows_Recordset_Problem);
$Recordset_Problem = $conn->query($query_limit_Recordset_Problem) or die($conn->error);
$row_Recordset_Problem = $Recordset_Problem->fetch_assoc();

//计算总页数
if (isset($_GET['totalRows_Recordset_Problem'])) {
  $totalRows_Recordset_Problem = $_GET['totalRows_Recordset_Problem'];
} else {
  $all_Recordset_Problem = $conn->query($query_Recordset_Problem);
  $totalRows_Recordset_Problem = mysqli_num_rows($all_Recordset_Problem);
}
$totalPages_Recordset_Problem = ceil($totalRows_Recordset_Problem/$maxRows_Recordset_Problem)-1;

$queryString_Recordset_Problem = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_Problem") == false && 
        stristr($param, "totalRows_Recordset_Problem") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_Problem = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_Problem = sprintf("&totalRows_Recordset_Problem=%d%s", $totalRows_Recordset_Problem, $queryString_Recordset_Problem);
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
        <li class="active"><a href="#"><i class="fa fa-table"></i> My Algorithm Question</a></li>
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
        <h1>Algorithm Question</h1>
        <ol class="breadcrumb">
          <li><a href="usrAdmin.php"><i class="fa fa-dashboard"></i> Profile Page</a></li>
          <li class="active"><i class="fa fa-table"></i> My Algorithm Question</li>
        </ol>
        <div class="alert alert-info alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          Manage your algorithm problem here. </div>
      </div>
    </div>
    <!-- /.row-->
    
    <div class="row" id="div_addInfo">
      <div class="col-lg-12">
        <h2 id="head2">View Algorithm Question</h2>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Problem Interface</h3>
          </div>
          <div class="panel-body">
            <div class="flot-chart">
              <div class="flot-chart-content" id="flot-chart-line">
                <table id="exist" width="300" border="0" align="center" class="table table-bordered table-hover tablesorter" <?php if(!$totalRows_Recordset_Problem) echo "hidden"; ?>>
                  <tr>
                    <td>Problem Title</td>
                    <td>Create Date</td>
					  <td>Last Edit Date</td>
                    <td>Number Algorithm</td>
                    <td>Publicity</td>
					<td>Tag</td>
                    <td>PageView</td>
                    <td>Solved</td>
					  <?php 
					  if($_SESSION['MM_UserGroup'] == "admin") 
						  echo '<td>User Name</td>'; 
					  ?> 
                    <td colspan="2">Operation</td>
                  </tr>
                  <?php do { ?>
                  <tr>
                    <td><a href="showProblem.php?proId=<?php echo $row_Recordset_Problem['id'] ?>"><?php echo $row_Recordset_Problem['name']; ?></a></td>
                    <td><?php echo $row_Recordset_Problem['createDate']; ?></td>
					  <td><?php echo $row_Recordset_Problem['lastDate'] ?></td>
                    <td><?php echo $row_Recordset_Problem['nSol']; ?></td>
                    <td><?php 
						  if ($row_Recordset_Problem['public']){
							  echo 'Public';
						  }else{
							  echo 'Private';
						  }  
						  ?></td>
					<td><?php echo $row_Recordset_Problem['tag']; ?></td>
                    <td><?php echo $row_Recordset_Problem['access']; ?></td>
                    <td><?php 
						  if ($row_Recordset_Problem['able']){
							  echo 'Resolved';
						  }else{
							  echo 'Unsolved';
						  }
						  ?></td>
					  <?php 
					  if($_SESSION['MM_UserGroup'] == "admin") { 
						  $query_Recordset_User = sprintf("SELECT name FROM user WHERE id = %s", GetSQLValueString($row_Recordset_Problem['usr_id'], "int", $conn));
						  $Recordset_User = $conn->query($query_Recordset_User) or die($conn->error);
						  $row_Recordset_User = $Recordset_User->fetch_assoc();
						  echo '<td>'.$row_Recordset_User['name'].'</td>'; 
					  }
					  ?> 
                    <td><a href="addProblem.php?proId=<?php echo $row_Recordset_Problem['id'] ?>">
                      <button class="btn btn-default">Edit</button>
                      </a></td>
                    <td><button class="btn btn-default" id="del<?php echo $row_Recordset_Problem['id'] ?>" value=<?php echo $row_Recordset_Problem['id'] ?>>Delete</button></td>
                  </tr>
                  <?php } while ($row_Recordset_Problem = $Recordset_Problem->fetch_assoc()); ?>
                </table>
                <table id="noexist" width="300" border="0" align="center" class="table table-bordered table-hover tablesorter" <?php if($totalRows_Recordset_Problem) echo "hidden" ?>>
                  <tr>
                    <td align="center">There is no Algorithm, please turn to add algorithm</td>
                  </tr>
                </table>
                <input type="hidden" id="totalNum" value=<?php echo $totalPages_Recordset_Problem + 1 ?>>
                <div id="pages" align="center"></div>
				<div>
					<button class="btn btn-default" id="tips" value="<?php if($_SESSION['MM_UserGroup']=='user') echo "userProblem";else echo "adminProblem"; ?>">Tips</button>
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
mysqli_free_result($Recordset_Problem);
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
//删除按钮弹出控件
$("button[id^='del']").on('click', function(){
   //询问框
   var id = $(this).val();
	layer.confirm('Are you sure deletion?', {
	title : ['message' , false],
    btn: ['Sure','Think Again'], //按钮
    shade: false //不显示遮罩
	}, function(){
    layer.msg('Delete Success', {icon: 1});
	window.location.href='delProblem.php?proId=' + id;
	});
});
</script>

<script>
//Tips按钮弹出控件
$("button[id='tips']").on('click', function(){
	var id = $(this).val();
    layer.open({
    	type: 2,
    	title: 'Tips About This Page',
    	shadeClose: true,
    	shade: 0.8,
    	area: ['500px', '250px'],
    	content: 'tips.php?id=' + id //iframe的url
	});
});
</script>