<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>

<body>
<?php
if(isset($_GET['id'])) {
	$file = "file\\Solution\\Sol-".$_GET['id']."\\Desc.txt"; 
}
else {
	$file = "file\\task\\task-".$_GET['runid']."\\output.txt";
}
if (file_exists($file)) {
	$content = iconv("gbk", "utf-8", file_get_contents($file)); //读取文件中的内容
	echo $content;
}
else
	echo "This is not a result. Because the output file can not find!";
?>
</body>
</html>