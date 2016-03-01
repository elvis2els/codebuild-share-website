<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
?>
<?php require_once('Logout.php'); ?>

<?php
$maxRows_Recordset_Problem = 6;		//需要显示的条数
$query_Recordset_Problem = sprintf("SELECT id, name, usr_id, lastDate, access FROM problem WHERE public=1 ORDER BY lastDate DESC");
$query_limit_Recordset_Alg = sprintf("%s LIMIT %d, %d", $query_Recordset_Problem, 0, $maxRows_Recordset_Problem);
$Recordset_Problem = $conn->query($query_limit_Recordset_Alg) or die($conn->error);
$row_Recordset_Problem = $Recordset_Problem->fetch_assoc();
$totalRows_Problem = mysqli_num_rows($Recordset_Problem);
?>

<?php
$maxRows_Recordset_Alg = 6;		//需要显示的条数
$query_Recordset_Alg = sprintf("SELECT id, usr_id, name, language, createDate, runTimes FROM solution ORDER BY createDate DESC");
$query_limit_Recordset_Alg = sprintf("%s LIMIT %d, %d", $query_Recordset_Alg, 0, $maxRows_Recordset_Alg);
$Recordset_Alg = $conn->query($query_limit_Recordset_Alg) or die($conn->error);
$row_Recordset_Alg = $Recordset_Alg->fetch_assoc();
$totalRows_Alg = mysqli_num_rows($Recordset_Alg);
?>

<?php
	$currentPage = $_SERVER["PHP_SELF"];
?>
<?php
//查询总量前12个tag
$query_Recordset_Tag = sprintf("SELECT name FROM tags ORDER BY number DESC");
$query_limit_Recordset_Tag = sprintf("%s LIMIT %d, %d", $query_Recordset_Tag, 0, 12);
$Recordset_Tag = $conn->query($query_limit_Recordset_Tag) or die($conn->error);
$row_Recordset_Tag = $Recordset_Tag->fetch_assoc();
?>


<!doctype html>
<html lang="zh-CN">
<head>
<!-- META TAGS -->
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clode Code</title>

<!--[支持ie9以下]>-->
<script src="http://cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
<script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<link rel="shortcut icon" href="images/favicon.png" />

<!-- Style Sheet-->
<link rel="stylesheet" href="style.css"/>
<link rel='stylesheet' id='bootstrap-css-css'  href='css/bootstrap5152.css?ver=1.0' type='text/css' media='all' />
<link rel='stylesheet' id='responsive-css-css'  href='css/responsive.css?ver=1.0' type='text/css' media='all' />
<link rel='stylesheet' id='pretty-photo-css-css'  href='js/prettyphoto/prettyPhotoaeb9.css?ver=3.1.4' type='text/css' media='all' />
<link rel='stylesheet' id='main-css-css'  href='css/main.css?ver=1.0' type='text/css' media='all' />
<link rel='stylesheet' id='custom-css-css'  href='css/custom.html?ver=1.0' type='text/css' media='all' />
</head>

<body>

<!-- Start of Header -->
<div class="header-wrapper">
  <header>
    <div class="container">
      <div class="logo-container"> 
        <!-- Website Logo --> 
        <a href="index.php"  title="Knowledge Base Theme"> <img src="images/logo.png" alt="Knowledge Base Theme"> </a> <span class="tag-line">Clode Code</span> </div>
      
      <!-- Start of Main Navigation -->
      <nav class="main-nav">
        <div class="menu-top-menu-container">
          <ul id="menu-top-menu" class="clearfix">
            <li class="current-menu-item"><a href="index.php">Home</a></li>
            <li><a href="problem-list.php">Problem List</a></li>
            <li><a href="faq.php">FAQs</a></li>
            <?php
			if(isset($_SESSION['MM_Username'])){
				echo '<li><a href="usrAdmin.php">Personal Center</a>';
			}
			else{
				echo '<li><span style="color:#fff">Personal center</span>';
			}
			?>
            <!--<li><a href="#">个人中心</a>-->
            <ul class="sub-menu">
              <?php
				if(!isset($_SESSION['MM_Username'])){
				echo '<li><a href="login.php">Login</a></li>';
				}
			  ?>
              <!--<li><a href="login.php">登录</a></li>-->
              <li id="register"><a href="register.php">Register</a></li>
              <li id="logout"><a href="<?php echo $logoutAction ?>">Logout</a></li>
            </ul>
            </li>
          </ul>
        </div>
      </nav>
      <!-- End of Main Navigation --> 
      
    </div>
  </header>
</div>
<!-- End of Header --> 

<!-- Start of Search Wrapper -->
<div class="search-area-wrapper">
  <div class="search-area container">
    <h3 class="search-header">Have a Question?</h3>
    <p class="search-tag-line">If you have any questions you can ask below or enter what you are looking for!</p>
    <form id="search-form" class="search-form clearfix" method="get" action="problem-list.php" autocomplete="off">
      <input class="search-term required" type="text" id="search" name="search" placeholder="Type your search terms here" title="* Please enter a search term!" />
      <input class="search-btn" type="submit" value="Search" />
      <div id="search-error-container"></div>
    </form>
  </div>
</div>
<!-- End of Search Wrapper --> 

<!-- Start of Page Container -->
<div class="page-container">
  <div class="container">
    <div class="row"> 
      
      <!-- start of page content -->
      <div class="span8 page-content"> 
        
        <!-- Basic Home Page Template -->
        <div class="row separator"> 
          <!--最近提出的问题-->
          <section class="span4 articles-list">
            <h3>Latest Problems</h3>
            <ul class="articles">
              <?php do { ?>
              <?php
			  $colname_Recordset_User = $row_Recordset_Problem['usr_id']; 
			  $query_Recordset_User = sprintf("SELECT name FROM user WHERE id = %s", GetSQLValueString($colname_Recordset_User, "int", $conn));
			  $Recordset_User = $conn->query($query_Recordset_User) or die($conn->error);
			  $row_Recordset_User = $Recordset_User->fetch_assoc();
			  ?>
              <li class="article-entry standard">
                <h4><a href="showProblem.php?proId=<?php echo $row_Recordset_Problem['id'] ?>"><?php echo $row_Recordset_Problem['name'] ?></a></h4>
                <span class="article-meta"><?php echo $row_Recordset_Problem['lastDate'] ?> by <?php echo $row_Recordset_User['name'] ?></span> <span class="like-count"><?php echo $row_Recordset_Problem['access'] ?></span> </li>
              <?php } while ($row_Recordset_Problem = $Recordset_Problem->fetch_assoc()); ?>
            </ul>
          </section>
          
          <!--最近上传的算法-->
          <section class="span4 articles-list">
            <h3>Latest Algorithms</h3>
            <ul class="articles">
              <?php do { ?>
              <?php
              $colname_Recordset_User = $row_Recordset_Alg['usr_id']; 
			  $query_Recordset_User = sprintf("SELECT name FROM user WHERE id = %s", GetSQLValueString($colname_Recordset_User, "int", $conn));
			  $Recordset_User = $conn->query($query_Recordset_User) or die($conn->error);
			  $row_Recordset_User = $Recordset_User->fetch_assoc();
			  ?>
              <li class="article-entry video">
                <h4><a href="showAlgorithm.php?algId=<?php echo $row_Recordset_Alg['id'] ?>"><?php echo $row_Recordset_Alg['name'] ?></a></h4>
                <span class="article-meta">
                <?php echo $row_Recordset_Alg['createDate'] ?> by <?php echo $row_Recordset_User['name'] ?></span> <span class="like-count"><?php echo $row_Recordset_Alg['runTimes'] ?></span> </li>
              <?php } while ($row_Recordset_Alg = $Recordset_Alg->fetch_assoc()); ?>
            </ul>
          </section>
        </div>
      </div>
      <!-- end of page content --> 
      
      <!-- start of sidebar -->
      <aside class="span4 page-sidebar">
        <section class="widget">
          <div class="support-widget">
            <h3 class="title">Support</h3>
            <p class="intro">Need more support? If you did not found an answer, contact us for further help.</p>
          </div>
        </section>
        <section class="widget">
          <div class="quick-links-widget">
            <h3 class="title">Quick Links</h3>
            <ul id="menu-quick-links" class="menu clearfix">
              <li><a href="index.php">Home</a></li>
              <li><a href="problem-list.php">Articles List</a></li>
              <li><a href="faq.php">FAQs</a></li>
              <?php if(!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup']=="user") echo '<li><a href="contact.php">Contact</a></li>'; ?>
            </ul>
          </div>
        </section>
        <section class="widget">
          <h3 class="title">Tags</h3>
          <div class="tagcloud">
			<?php do { ?>
			<a href="problem-list.php?tag=<?php echo $row_Recordset_Tag['name'] ?>" class="btn btn-mini"><?php echo $row_Recordset_Tag['name'] ?></a>
            <?php } while ($row_Recordset_Tag = $Recordset_Tag->fetch_assoc()); ?>
		  </div>
        </section>
      </aside>
      <!-- end of sidebar --> 
    </div>
  </div>
</div>
<!-- End of Page Container --> 

<!-- Start of Footer -->
<footer id="footer-wrapper">
  <div id="footer" class="container">
    <div class="row">
      <div class="span3">
        <section class="widget">
          <h3 class="title">How it works</h3>
          <div class="textwidget">
            <p>Cloud Code is for developers and researchers to ask questions and allow other users to upload algorithms to solve this problem. The system is made for problem, compile and run. </p>
            <p>Users can download the source code and upload the input file to get the running results. </p>
          </div>
        </section>
      </div>
      <div class="span3">
        <section class="widget">
          <h3 class="title">Categories</h3>
          <ul>
            <li><a href="index.php" title="Lorem ipsum dolor sit amet,">Home</a> </li>
            <li><a href="problem-list.php" title="Lorem ipsum dolor sit amet,">Problem List</a></li>
            <li><a href="faq.php" title="Lorem ipsum dolor sit amet,">FAQs</a></li>
            <li><a href="http://www.tongji.edu.cn" title="Lorem ipsum dolor sit amet, ">Tongji University</a></li>
			<li><a href="http://see.tongji.edu.cn" title="Lorem ipsum dolor sit amet, ">Telecommunication College of Tongji University </a></li>
          </ul>
        </section>
      </div>
    </div>
  </div>
  <!-- end of #footer --> 
  
  <!-- Footer Bottom -->
  <div id="footer-bottom-wrapper">
    <div id="footer-bottom" class="container">
      <div class="row">
        <div class="span6">
          <p class="copyright"> 2015 Copyright @ Institute of Machine Learning and Systems Biology All rights reserved. </p>
        </div>
        <div class="span6"> 
          <!-- Social Navigation -->
          <ul class="social-nav clearfix">
            <li class="linkedin"><a target="_blank" href="#"></a></li>
            <li class="stumble"><a target="_blank" href="#"></a></li>
            <li class="google"><a target="_blank" href="#"></a></li>
            <li class="deviantart"><a target="_blank" href="#"></a></li>
            <li class="flickr"><a target="_blank" href="#"></a></li>
            <li class="skype"><a target="_blank" href="skype:#?call"></a></li>
            <li class="rss"><a target="_blank" href="#"></a></li>
            <li class="twitter"><a target="_blank" href="#"></a></li>
            <li class="facebook"><a target="_blank" href="#"></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- End of Footer Bottom --> 
  
</footer>
<!-- End of Footer --> 

<a href="#top" id="scroll-top"></a> 

<!-- script --> 
<script src="http://cdn.bootcss.com/jquery/2.1.3/jquery.min.js"></script> 
<script src='js/jquery.easing.1.3.js'></script> 
<script src='js/prettyphoto/jquery.prettyPhoto.js'></script> 
<script src='js/jflickrfeed.js'></script> 
<script src='js/jquery.liveSearch.js'></script> 
<script src='js/jquery.form.js'></script> 
<script src='js/jquery.validate.min.js'></script> 
<script src='js/custom.js'></script>
<div style="display:none"><script src='http://v7.cnzz.com/stat.php?id=155540&web_id=155540' language='JavaScript' charset='gb2312'></script></div>
</body>
</html>
<?php
mysqli_free_result($Recordset_Problem);
mysqli_free_result($Recordset_Alg);
?>
<script>
 <?php
if (!isset($_SESSION['MM_Username'])){
	echo 'document.getElementById("logout").hidden="hidden";';
}

if (isset($_SESSION['MM_Username'])){
	if($_SESSION['MM_UserGroup'] != "admin"){
		echo'document.getElementById("register").hidden="hidden";';
	}
}
?>
</script>