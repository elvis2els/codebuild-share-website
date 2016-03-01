<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('checkuser.php'); ?>
<?php require_once('Logout.php'); ?>
<?php
if (!isset($_GET['proId'])) {
	header("Location: usrProblem.php");
}
?>
<?php
$colname_Recordset_Problem = $_GET['proId'];
$query_Recordset_Problem = sprintf("SELECT name FROM problem WHERE id = %s", GetSQLValueString($colname_Recordset_Problem, "int", $conn));
$Recordset_Problem = $conn->query($query_Recordset_Problem) or die($conn->error);
$row_Recordset_Problem = $Recordset_Problem->fetch_assoc();
$totalRows_Recordset_Problem = mysqli_num_rows($Recordset_Problem);
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
           Add algorithm here. </div>
       </div>
     </div>
     <!-- /.row-->
     
     <div class="row" id="div_addProblem">
       <div class="col-lg-12">
         <h2 id="head">Add Algorithm</h2>
         <div class="panel panel-primary">
           <div class="panel-heading">
             <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Add Interface</h3>
           </div>
           <div class="panel-body">
             <div class="flot-chart">
               <div class="flot-chart-content" id="flot-chart-line">
                 <form id="addAlg" action="addAlgorithm2.php" method="post" enctype="multipart/form-data">
                   <table width="800" border="0" align="center">
                     <input type="hidden" name="proId" value="<?php echo "$colname_Recordset_Problem"; ?>" />
                     <tr>
                       <td><div class="form-group input-group"> <span class="input-group-addon"> Algorithm Question</span>
                           <input name="proName" type="text" class="form-control"  disabled value="<?php echo $row_Recordset_Problem['name']; ?>">
                         </div></td>
                       <td><div class="form-group input-group"> <span class="input-group-addon"> Algorithm Name</span>
                           <input name="algName" type="text" class="form-control" required>
                         </div></td>
                     </tr>
                     <tr>
                       <td><div class="form-group">
                           <label>Algorithmic Language Or Type</label>
                           <select id="language" name="language" class="form-control" onchange="changelan(this.value);"  onload="changelan(this.value);">
                            <option value="C++" selected="selected">C++(source file)</option>
                            <option value="javac">JAVA(source file)</option>
							<option value="exe">EXE(Compiled file)</option>
                          </select>
                         </div></td>
                       <td id="landesc"></td>
                     </tr>
                     <tr>
                       <td id="exefile"><div class="form-group input-group"> <span class="input-group-addon"> Executable File Name</span>
                           <input name="exe_file" type="text" class="form-control">
                         </div></td>
                       <td id="exefunc" hidden="hidden"><div class="form-group input-group"> <span class="input-group-addon"> Executable File Function</span>
                           <input name="exe_func" type="text" class="form-control">
                         </div></td>
                     </tr>
                     <tr>
                       <td><div class="form-group">
                           <label> Input File Type</label>
                           <select name="ifiletype" class="form-control">
                            <?php
										include("iofiletype.php");
										while(list($key) = each($iofiletype)){
											echo"<option value=\"$key\">$key</option>";
										}
										?>
                            <!--<option value="text/plain" selected>txt</option>
                  						<option value="image/gif">gif</option>
                                        <option value="image/jpeg">jpg</option>
                  						<option value="image/png">png</option>
                                        <option value="image/bmp">bmp</option>-->
                          </select>
                         </div></td>
                       <td><div class="form-group">
                           <label> Output File Type</label>
                           <select name="ofiletype" class="form-control">
                            <?php
										include("iofiletype.php");
										while(list($key) = each($iofiletype)){
											echo"<option value=\"$key\">$key</option>";
										}
										?>
                            <!--<option value="text/plain" selected>txt</option>
                  						<option value="image/gif">gif</option>
                                        <option value="image/jpeg">jpg</option>
                  						<option value="image/png">png</option>
                                        <option value="image/bmp">bmp</option>-->
                          </select>
                         </div></td>
                     </tr>
                     <tr>
                       <td><input name="submit" type="submit" class="btn btn-default" value="Next"></td>
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
     
     <div class="row" id="div_changeProblem" hidden="">
       <div class="col-lg-12">
         <h2 id="head2">Change Algorithm Question</h2>
         <div class="panel panel-primary">
           <div class="panel-heading">
             <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Change Interface</h3>
           </div>
           <div class="panel-body">
             <div class="flot-chart">
               <div class="flot-chart-content" id="flot-chart-line"> </div>
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
<?php
mysqli_free_result($Recordset_Problem);
?>
<script>
function changelan(lan){
 var file=document.getElementById("exefile");
 var func=document.getElementById("exefunc");
 var landesc=document.getElementById("landesc");
 switch(lan)
 {
 case "C++":
 	file.hidden=undefined;
 	func.hidden="hidden";
 	landesc.innerHTML="Upload a zip file that contains the .cpp file, below the \"Executable File Name\" complete without a suffix.";
 	break;
 case "javac":
 	file.hidden="hidden";
 	func.hidden=undefined;
 	landesc.innerHTML="Upload a zip file that contains the .java file.";
 	break;
 case "exe":
	file.hidden=undefined;
	func.hidden="hidden";
	landesc.innerHTML="Upload a zip file that contains the .exe file, below the \"Executable File Name\" complete without a suffix.";
	break;
 default:
 	break;
 }
}

changelan(document.getElementById("language").value);
 </script>