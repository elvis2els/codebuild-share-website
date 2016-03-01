<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php require_once('PasswordHash.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['MM_Username'])) {
	header("Location: usrAdmin.php");
}

$loginFormAction = htmlentities($_SERVER['PHP_SELF']);
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['submit'])) {
 	$loginUsername=$_POST['usr_name'];
 	$password=$_POST['usr_passwd'];
	
 	$MM_fldUserAuthorization = "power";
	$MM_redirectLoginSuccess = "index.php";
	$MM_redirecttoReferrer = true;
  	
	$LoginRS__query=sprintf("SELECT * FROM `user` WHERE name=%s", GetSQLValueString($loginUsername, "text", $conn)); 
	$LoginRS = $conn->query($LoginRS__query) or die($conn->error);
  	$loginFoundUser = mysqli_num_rows($LoginRS);
 	if ($loginFoundUser) {
    	$row = $LoginRS->fetch_assoc();  //关联数组形式返回结果
		$hasher = new PasswordHash(8, TRUE);
		$check = $hasher->CheckPassword($password, $row['passwd']);
		if($check) {
			$loginStrGroup = $row["power"];
			$loginUserId =$row["id"];
			// echo $loginStrGroup;
			if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
			//declare two session variables and assign them
			$_SESSION['MM_Username'] = $loginUsername;
			$_SESSION['MM_UserGroup'] = $loginStrGroup;	 
			$_SESSION['MM_UserId'] = $loginUserId;    

			if (isset($_SESSION['PrevUrl']) && true) {
				$MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
			}
			header("Location: " . $MM_redirectLoginSuccess );
		}
		else
			$loginFoundUser = 0;
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

<!-- BSA AdPacks code -->
<script src="http://cdn.bootcss.com/jquery/2.1.3/jquery.min.js"></script>
<script src="layer/layer.js"></script>
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

<form id="login" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
  <h1>Log In</h1>
  <fieldset id="inputs">
    <input type="text" name="usr_name" id="username" required autofocus placeholder="Username">
    <input type="password" name="usr_passwd" id="password" required placeholder="Password">
  </fieldset>
  <fieldset id="actions">
    <p>
      <input type="submit" name="submit" id="submit" value="Login">
      <a href="">
      <?php
if(isset($loginFoundUser)){
	if (!$loginFoundUser){
		echo '<span style="color:red">Incorrect username or password! Please try again.</span>';
	}
}
?>
      </a> </p>
  </fieldset>
</form>
</body>
</html>
<script>
$('#submit').on('click', function(){
    var ii = layer.load();
    //此处用setTimeout演示ajax的回调
    setTimeout(function(){
        layer.close(ii);
    }, 1000);
});
</script>