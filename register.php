<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('PasswordHash.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['MM_Username']) && $_SESSION['MM_UserGroup'] == "user") {
	header("Location: index.php");
}

$registerFormAction = htmlentities($_SERVER['PHP_SELF']);

if (isset($_POST['submit'])) {
	
	$userName = $_POST['usr_name'];
	
	//判断两次输入密码是否相等
	if($_POST['usr_passwd1'] == $_POST['usr_passwd2']){
		$hasher = new PasswordHash(8, FALSE);
		$passwd_hash = $hasher->HashPassword($_POST['usr_passwd1']);
		$Passwd = "equal";
	}else{
		$Passwd = "err";
	}
	
	//验证用户名是否已存在
	$findName_query = sprintf("SELECT name FROM `user` WHERE name=%s", GetSQLValueString($userName, "text", $conn));
	$result = $conn->query($findName_query);
	$row = mysqli_num_rows($result);
	if($row){
		$Name = "exist";
	}else{
		$Name = "noExist";
	}
	
	if($Name == "noExist" && $Passwd == "equal"){ 
  		$insertSQL = sprintf("INSERT INTO `user` (name, passwd, power) VALUES (%s, %s, %s)",
                       GetSQLValueString($userName, "text", $conn),
                       GetSQLValueString($passwd_hash, "text", $conn),
                       GetSQLValueString($_POST['radio'], "text", $conn));

  		$Result1 = $conn->query($insertSQL) or die($conn->error);
		header("Location: successReg.html");
	}
	
}

?>

<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Clode Code</title>

<!--[支持ie9以下]>-->
<script src="http://cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
<script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<script src="http://cdn.bootcss.com/jquery/2.1.3/jquery.min.js"></script>
<script src="js/getName.js"></script>
<link rel='stylesheet' id='bootstrap-css-css'  href='css/bootstrap5152.css?ver=1.0' type='text/css' media='all' />
<link rel='stylesheet' id='responsive-css-css'  href='css/responsive.css?ver=1.0' type='text/css' media='all' />
<link rel='stylesheet' id='main-css-css'  href='css/main.css?ver=1.0' type='text/css' media='all' />
<link rel="shortcut icon" href="images/favicon.png" />
<link rel="stylesheet" href="css/login.css">
<style type="text/css" adt="123">
body, td, th {
	font-family: "Lucida Sans Unicode", "Trebuchet MS", Arial, Helvetica;
}
body {
	background-image: url(images/main-bg.jpg);
}
</style>
</head>

<body>
<!-- Start of Header -->
<div class="header-wrapper">
  <header>
    <div class="container">
      <div class="logo-container"> 
        <!-- Website Logo --> 
        <a href="index.php"  title="Knowledge Base Theme"> <img src="images/logo.png" alt="Knowledge Base Theme"> </a> <span class="tag-line">Premium WordPress Theme</span> </div>
    </div>
  </header>
</div>
<!-- End of Header -->

<form id="register" name="form1" method="POST" action="<?php echo $registerFormAction; ?>">
  <h1>Register</h1>
  <fieldset id="inputs">
    <input type="text" name="usr_name" id="username" autofocus required placeholder="Username" value="<?php if(isset($_POST['usr_name'])){ echo $_POST['usr_name']; }?>">
    <div id="show">
      <?php
	  	if(isset($Name)){
			if($Name == "exist"){
				echo '<span style="color:red">User name already exists!</span>';
			}
		}
	  ?>
    </div>
    <input type="password" name="usr_passwd1" id="password" required placeholder="Password">
    <input type="password" name="usr_passwd2" id="password2" required placeholder="Confirm Password">
  </fieldset>
  <table width="361" border="0">
    <tr id="selectGroup">
      <td width="71" height="62">User Group</td>
      <td width="274"><input name="radio" type="radio" id="usr_group" value="user" checked>
        User
        <input type="radio" name="radio" id="usr_group_admin" value="admin">
        Administrator</td>
    </tr>
  </table>
  <fieldset id="actions">
    <p>
      <input type="submit" name="submit" id="submit" value="Register" onClick="checkPasswords()">
      <input type="reset" name="reset" id="reset" value="Reset">
    </p>
  </fieldset>
</form>
</body>
</html>
<script>
 <?php
if (!(isset($_SESSION['MM_UserGroup']) && $_SESSION['MM_UserGroup'] == "admin")){
	echo 'document.getElementById("selectGroup").hidden="hidden";';
}
?>

function checkPasswords() {
	var pass1 = document.getElementById("password");
    var pass2 = document.getElementById("password2");
	if (pass2.value == "")
		pass2.setCustomValidity("Enter password again");
    else if (pass1.value != pass2.value)
    	pass2.setCustomValidity("The passwords you typed do not match");
    else
    	pass2.setCustomValidity("");
}

</script>