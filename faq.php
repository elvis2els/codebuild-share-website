<?php require_once('Connections/conn.php'); ?>
<?php require_once('GetSQLValueString.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
?>
<?php require_once('Logout.php'); ?>


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
            <li><a href="index.php">Home</a></li>
            <li><a href="problem-list.php">Problem List</a></li>
            <li class="current-menu-item"><a href="#">FAQs</a></li>
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
        <article class=" page type-page hentry clearfix">
          <h1 class="post-title"><a href="#">FAQs</a></h1>
          <hr>
          <p>Cloud Code is for developers and researchers to ask questions and allow other users to upload algorithms to solve this problem. The system is made for problem, compile and run.</p>
        </article>
        <div class="faqs clearfix">
          <article class="faq-item active"> <span class="faq-icon"></span>
            <h3 class="faq-question"> <a href="#">How to ask questions?</a> </h3>
            <div class="faq-answer">
              <p>You need to register for an account, fill your personal information then use the question section to add your question.</p>
			  <p>The proposed problem can be added tags, which facilitate our classification and search.</p>
            </div>
          </article>
          <article class="faq-item"> <span class="faq-icon"></span>
            <h3 class="faq-question"> <a href="#">What are the requirements for the upload code?</a> </h3>
            <div class="faq-answer">
              <p>Currently support code language is C++, JAVA and compiled EXE.</p>
              <p>Code: the incoming parameter is the file address of the first parameter in command line, and the result is stored in the file address of the command line second parameter.</p>
              <p>The incoming and outgoing files can be TXT, JPEG, BMP, gif.</p>
            </div>
          </article>
          <article class="faq-item"> <span class="faq-icon"></span>
            <h3 class="faq-question"> <a href="#">How is Clode Code licensed?</a> </h3>
            <div class="faq-answer" style="display: none;">
              <p>WordPress is licensed under the&nbsp;<a title="http://www.gnu.org/licenses/gpl.html" href="http://www.gnu.org/licenses/gpl.html">GNU General Public License</a>&nbsp;(GPL).</p>
              <p>The GPL is an open source license. This means you are free to modify and redistribute the source code under certain conditions. You can read more about why we chose the GPL on&nbsp;<a title="http://codex.wordpress.org/License" href="http://codex.wordpress.org/License">the License Page.</a></p>
            </div>
          </article>
          <article class="faq-item"> <span class="faq-icon"></span>
            <h3 class="faq-question"> <a href="#"> When was Clode Code first released?</a> </h3>
            <div class="faq-answer">
              <p>WordPress started out life as a fork of b2/cafelog by Matt Mullenweg and Mike Little. The first version was&nbsp;<a title="http://wordpress.org/news/2003/05/wordpress-now-available/" href="http://wordpress.org/news/2003/05/wordpress-now-available/">released in 2003</a></p>
            </div>
          </article>
          <article class="faq-item"> <span class="faq-icon"></span>
            <h3 class="faq-question"> <a href="#">What are Clode Code features?</a> </h3>
            <div class="faq-answer">
              <p>WordPress has an extensive list of features and, as it is constantly evolving, this list of features is constantly growing.&nbsp;<a title="http://codex.wordpress.org/WordPress_Features" href="http://codex.wordpress.org/WordPress_Features">Check out the up-to-date list of features.</a></p>
            </div>
          </article>
          <article class="faq-item"> <span class="faq-icon"></span>
            <h3 class="faq-question"> <a href="#">Why Choose Clode Code?</a> </h3>
            <div class="faq-answer">
              <p>One of the principle advantages of WordPress is that you are in control. Unlike remote-hosted scripts such as&nbsp;<a title="http://www.blogger.com" href="http://www.blogger.com/">Blogger</a>&nbsp;and&nbsp;<a title="http://www.livejournal.com" href="http://www.livejournal.com/">LiveJournal</a>, you host WordPress on your own server. Installation is very simple, as is the configuration. Unlike other software programs, there are not a million files to&nbsp;<a title="Changing File Permissions" href="http://codex.wordpress.org/Changing_File_Permissions">chmod</a>&nbsp;nor are there dozens of&nbsp;<a title="Templates" href="http://codex.wordpress.org/Templates">templates</a>&nbsp;to edit just to get your site set up and looking the way you want.</p>
              <p>Also,&nbsp;<a title="Glossary" href="http://codex.wordpress.org/Glossary#Blogging">Blog</a>&nbsp;pages in WordPress are generated on the fly whenever a page is requested, so you do not have multiple archive pages clogging up your web space. Waiting for pages to rebuild is a thing of the past because template changes are made in scant seconds.</p>
              <p>WordPress is built following&nbsp;<a title="http://www.w3.org/" href="http://www.w3.org/">W3C</a>&nbsp;standards for&nbsp;<a title="Glossary" href="http://codex.wordpress.org/Glossary#XHTML">XHTML</a>&nbsp;and&nbsp;<a title="Glossary" href="http://codex.wordpress.org/Glossary#CSS">CSS</a>, ensuring that your site is more easily rendered across standards-compliant browsers. Other browsers are supported with a few hacks; it’s a reality of the web that hacks are necessary.</p>
              <p><a title="Glossary" href="http://codex.wordpress.org/Glossary#News_reader">Aggregator</a>&nbsp;support is built-in with a number of standard&nbsp;<a title="Glossary" href="http://codex.wordpress.org/Glossary#RSS">RSS</a>&nbsp;configurations already done for you, as well as&nbsp;<a title="Glossary" href="http://codex.wordpress.org/Glossary#Atom">Atom</a>. Following standards makes your WordPress site easier to manage, increases its longevity for future Internet technology adoption, and helps give your site the widest audience possible.</p>
            </div>
          </article>
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
          <h3 class="title">Latest Problems</h3>
          <ul class="articles">
            <?php
			$maxRows_Recordset_Problem = 4;		//需要显示的条数
			$query_Recordset_Problem = sprintf("SELECT id, name, usr_id, lastDate, access FROM problem WHERE public=1 ORDER BY lastDate DESC");
			$query_limit_Recordset_Alg = sprintf("%s LIMIT %d, %d", $query_Recordset_Problem, 0, $maxRows_Recordset_Problem);
			$Recordset_Problem = $conn->query($query_limit_Recordset_Alg) or die($conn->error);
			$row_Recordset_Problem = $Recordset_Problem->fetch_assoc();
			?>
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