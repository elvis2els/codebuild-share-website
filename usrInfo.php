<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('checkuser.php'); ?>
<?php require_once('Logout.php'); ?>
<?php
$colname_Recordset_info = $_SESSION['MM_UserId'];

$query_Recordset_info = sprintf("SELECT * FROM user_info WHERE usr_id = %s", GetSQLValueString($colname_Recordset_info, "int", $conn));
$Recordset_info = $conn->query($query_Recordset_info) or die($conn->error);
$row_Recordset_info = $Recordset_info->fetch_assoc();
$totalRows_Recordset_info = mysqli_num_rows($Recordset_info);
?>
<?php
if (isset($_POST['submit'])) {
	$id = GetSQLValueString($colname_Recordset_info, "int", $conn);
	$name = GetSQLValueString($_POST['name'], "text", $conn);
	$sex = GetSQLValueString($_POST['optionsRadios'], "text", $conn);
	$phone = GetSQLValueString($_POST['phone'], "text", $conn);
	$email = GetSQLValueString($_POST['email'], "text", $conn);
	$job = GetSQLValueString($_POST['job'], "text", $conn);
	if(!$totalRows_Recordset_info){	
		$insertSQL = sprintf("INSERT INTO `user_info` (usr_id, name, sex, phone, email, job) VALUES ($id, $name, $sex, $phone, $email, $job)");

  		$Result1 = $conn->query($insertSQL) or die($conn->error);
	}else{
		$updateSQL = sprintf("UPDATE `user_info` SET `name` = $name, `sex` = $sex, `phone` = $phone, `email` = $email, `job` = $job WHERE `usr_id` = $id");
		$Result2 = $conn->query($updateSQL) or die($conn->error);	
	}
	header("Location: usrInfo.php");
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
        <li class="active"><a href="#"><i class="fa fa-bar-chart-o"></i> Personal Information</a></li>
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
        <h1>Personal Information</h1>
        <ol class="breadcrumb">
          <li><a href="usrAdmin.php"><i class="fa fa-dashboard"></i> Profile Page</a></li>
          <li class="active"><i class="fa fa-bar-chart-o"></i> Personal Information</li>
        </ol>
        <div class="alert alert-info alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php
			  if ($totalRows_Recordset_info) {
				  echo '<div id="change">Shown here is your personal information, you can click the row to be modified.</div>';
			  }else{
				  echo 'New users Please enter your personal information.';
			  }
			  ?>
        </div>
      </div>
    </div>
    <!-- /.row-->
    
    <div class="row" id="div_addInfo" <?php if($totalRows_Recordset_info) echo "hidden" ?>>
      <div class="col-lg-12">
        <h2 id="head2">Add User Information</h2>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Data Interfaces</h3>
          </div>
          <div class="panel-body">
            <div class="flot-chart">
              <div class="flot-chart-content" id="flot-chart-line">
                <form id="addInfo" name="form1"  action="" method="post">
                  <table width="300" border="0" align="center">
                    <tr>
                      <td colspan="3"><div class="form-group input-group"> <span class="input-group-addon">Name</span>
                          <input name="name" type="text" class="form-control" placeholder="Name" size="230" required
                                <?php
                                	if ($totalRows_Recordset_info) {
										echo 'value="'.$row_Recordset_info['name'].'"';
									}
								?>
                                >
                        </div></td>
                    </tr>
                    <tr>
                      <td width="70"><label style="color:#555">&nbsp;&nbsp;&nbsp;Sex</label></td>
                      <td width="70"><div class="radio">
                          <label style="color:#555">
                            <input type="radio" name="optionsRadios" id="optionsRadios1" value="man"
                                    <?php
                                	if ($totalRows_Recordset_info) {
										if ($row_Recordset_info['sex'] == "man"){
											echo 'checked';
										}
									}else{
											echo 'checked';
										}
									?>
                                    >
                            Man </label>
                        </div></td>
                      <td width="146"><div class="radio">
                          <label style="color:#555">
                            <input type="radio" name="optionsRadios" id="optionsRadios2" value="woman"
                                    <?php 
									if ($totalRows_Recordset_info) {
										if ($row_Recordset_info['sex'] == "woman"){
											echo 'checked';
										}
									}
									?>
									>
                            Woman </label>
                        </div></td>
                    </tr>
                    <tr>
                      <td colspan="3"><div class="form-group input-group"> <span class="input-group-addon">Phone</span>
                          <input name="phone" type="text" class="form-control" placeholder="Only number is allowed" size="230" required onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" 
                          <?php
                          if ($totalRows_Recordset_info) {
						  	echo 'value="'.$row_Recordset_info['phone'].'"';
						  }
						  ?>
                          >
                        </div></td>
                    </tr>
                    <tr>
                      <td colspan="3"><div class="form-group input-group"> <span class="input-group-addon">Email</span>
                          <input type="email" class="form-control" name="email" placeholder="Email" size="230" required 
                          <?php
                          if ($totalRows_Recordset_info) {
						  	echo 'value="'.$row_Recordset_info['email'].'"';
						  }
						  ?>
                          >
                        </div></td>
                    </tr>
                    <tr>
                      <td colspan="3"><div class="form-group input-group"> <span class="input-group-addon"> Job Title</span>
                          <input type="text" class="form-control" name="job" placeholder="Job" size="230" required
                          <?php
                          if ($totalRows_Recordset_info) {
						  	echo 'value="'.$row_Recordset_info['job'].'"';
						  }
						  ?>
                          >
                        </div></td>
                    </tr>
					<tr>
                      <td colspan="3"><div class="form-group input-group"> <span class="input-group-addon"> Work Place</span>
                          <input type="text" class="form-control" name="job" placeholder="Job" size="230" required
                          <?php
                          if ($totalRows_Recordset_info) {
						  	echo 'value="'.$row_Recordset_info['workplace'].'"';
						  }
						  ?>
                          >
                        </div></td>
                    </tr>
                    <tr>
                      <td><input name="submit" type="submit" class="btn btn-default" value="Save"></td>
                      <td><input name="reset" type="reset" class="btn btn-default" value="Reset"></td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /#div_addInfo  -->
    
    <div class="row" id="div_showInfo" <?php if(!$totalRows_Recordset_info) echo "hidden" ?>>
      <div class="col-lg-12">
        <h2>View User Information</h2>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Data Interfaces</h3>
          </div>
          <div class="panel-body">
            <div class="flot-chart">
              <div class="flot-chart-content" id="flot-chart-line">
                <table width="300" border="0" align="center">
                  <tr>
                    <td width="204"><div class="form-group input-group"> <span class="input-group-addon">Name</span>
                        <input name="name" type="text" class="form-control" size="230" required disabled 
							<?php 
							if ($totalRows_Recordset_info) {
								echo 'value="'.$row_Recordset_info['name'].'"';
							}
							?>
							>
                      </div></td>
                  </tr>
                  <tr>
                    <td><div class="form-group input-group"> <span class="input-group-addon">Sex</span>
                        <input name="name2" type="text" class="form-control" size="230" required disabled 
								<?php 
								if ($totalRows_Recordset_info) {
									if ($row_Recordset_info['sex'] == 'man') {
										echo 'value="Man"';
									}else{
										echo 'value="Woman"';
									}
								}
								?>
								>
                      </div></td>
                  </tr>
                  <tr>
                    <td><div class="form-group input-group"> <span class="input-group-addon">Phone</span>
                        <input name="phone" type="text" class="form-control" size="230" disabled 
								<?php 
								if ($totalRows_Recordset_info) {
									if ($row_Recordset_info['phone'] == '') {
										echo 'value="Do not set the phone number!"';
									}else{
										echo 'value="'.$row_Recordset_info['phone'].'"';
									}
								}
								?>
                                >
                      </div></td>
                  </tr>
                  <tr>
                    <td colspan="3"><div class="form-group input-group"> <span class="input-group-addon">Email</span>
                        <input type="email" class="form-control" name="email" size="230" disabled
						  <?php 
							if ($totalRows_Recordset_info) {
								if ($row_Recordset_info['email'] == '') {
									echo 'value="Do not set the Email!"';
								}else{
									echo 'value="'.$row_Recordset_info['email'].'"';
								}
							}
						 ?>
                         >
                      </div></td>
                  </tr>
                  <tr>
                    <td colspan="3"><div class="form-group input-group"> <span class="input-group-addon">Job Title</span>
                        <input type="text" class="form-control" name="job" size="230" disabled
						  <?php 
							if ($totalRows_Recordset_info) {
								if ($row_Recordset_info['job'] == '') {
									echo 'value="Do not set the Job!"';
								}else{
									echo 'value="'.$row_Recordset_info['job'].'"';
								}
							}
						 ?>
                         >
                      </div></td>
                  </tr>
				  <tr>
                    <td colspan="3"><div class="form-group input-group"> <span class="input-group-addon">Work Place</span>
                        <input type="text" class="form-control" name="job" size="230" disabled
						  <?php 
							if ($totalRows_Recordset_info) {
								if ($row_Recordset_info['workplace'] == '') {
									echo 'value="Do not set the work place!"';
								}else{
									echo 'value="'.$row_Recordset_info['workplace'].'"';
								}
							}
						 ?>
                         >
                      </div></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /#div_showInfo--> 
    
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
<script>
$(function () {
	$('#change').on("click", function () {
		$('#div_showInfo').toggle();
		$('#div_addInfo').toggle();
		$("#head2").html("Edit User Information");
		});
});
</script>
<?php
mysqli_free_result($Recordset_info);
?>
