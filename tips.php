<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>

<body>
<?php
$id = $_GET['id'];
switch($id) {
	case "adminProblem":
		echo "<B>Problem Title:</B> Upload problems' title<br>";
		echo "<B>Create Date:</B> Date of upload problem<br>";
		echo "<B>Last Edit Date:</B> Date of the last edit questions<br>";
		echo "<B>Number Algorithm:</B> The problem of the algorithms number<br>";
		echo "<B>Publicity:</B> The question is public or private<br>";
		echo "<B>Tag:</B> The question's tag<br>";
		echo "<B>PageView:</B> How many times has the problem been visited<br>";
		echo "<B>Solved:</B> The question is solved or not<br>";
		echo "<B>User Name:</B> Who upload the problem<br>";
		echo "<B>Edit:</B> You can change your problem<br>";
		echo "<B>Delete:</B> You can delete your problem<br>";
		break;
	case "userProblem":
		echo "<B>Problem Title:</B> Uploaded problems' title<br>";
		echo "<B>Create Date:</B> Date of upload problem<br>";
		echo "<B>Last Edit Date:</B> Date of the last edit questions<br>";
		echo "<B>Number Algorithm:</B> The problem of the algorithms number<br>";
		echo "<B>Publicity:</B> The question is public or private<br>";
		echo "<B>Tag:</B> The question's tag<br>";
		echo "<B>PageView:</B> How many times has the problem been visited<br>";
		echo "<B>Solved:</B> The question is solved or not<br>";
		echo "<B>Edit:</B> You can change your problem<br>";
		echo "<B>Delete:</B> You can delete your problem<br>";
		break;
	case "adminAlgorithm":
		echo "<B>Algorithm Name:</B> Uploaded algorithms' title<br>";
		echo "<B>Solved Problem:</B> The algorithm to solve the problem<br>";
		echo "<B>Language:</B> The programming language used in the algorithm<br>";
		echo "<B>Create Date:</B> Date of upload algorithm<br>";
		echo "<B>Run Times:</B> The number of the algorithm run times<br>";
		echo "<B>User Name:</B> Who upload the algorithm<br>";
		echo "<B>Description File:</B> The algorithm description file. You can use the View to preview algorithm description<br>";
		echo "<B>Download File:</B> You can use the Download to download the algorithm's file<br>";
		echo "<B>Delete:</B> You can delete your algorithm<br>";
		break;
	case "userAlgorithm":
		echo "<B>Algorithm Name:</B> Uploaded algorithms' title<br>";
		echo "<B>Solved Problem:</B> The algorithm to solve the problem<br>";
		echo "<B>Language:</B> The programming language used in the algorithm<br>";
		echo "<B>Create Date:</B> Date of upload algorithm<br>";
		echo "<B>Run Times:</B> The number of the algorithm run times<br>";
		echo "<B>Description File:</B> The algorithm description file. You can use the View to preview algorithm description<br>";
		echo "<B>Download File:</B> You can use the Download to download the algorithm's file<br>";
		echo "<B>Delete:</B> You can delete your algorithm<br>";
		break;
	case "adminRun":
		echo "<B>Running ID:</B> The id of runing or ran task<br>";
		echo "<B>Algorithm Name:</B> Algorithms' title of the runing or ran task<br>";
		echo "<B>Solved Problem:</B> Runing or ran of the algorithm to solve the problem<br>";
		echo "<B>Begin Time:</B> The begin time of the runing or ran task<br>";
		echo "<B>Run Time:</B> Run time of the task<br>";
		echo "<B>User Name:</B> Who submit the task<br>";
		echo "<B>Result:</B> Result of the task. You can use the View to preview task result<br>";
		echo "<B>Download Result:</B> You can use the Download to download the task result<br>";
		break;
	case "userRun":
		echo "<B>Running ID:</B> The id of runing or ran task<br>";
		echo "<B>Algorithm Name:</B> Algorithms' title of the runing or ran task<br>";
		echo "<B>Solved Problem:</B> Runing or ran of the algorithm to solve the problem<br>";
		echo "<B>Begin Time:</B> The begin time of the runing or ran task<br>";
		echo "<B>Run Time:</B> Run time of the task<br>";
		echo "<B>Result:</B> Result of the task. You can use the View to preview task result<br>";
		echo "<B>Download Result:</B> You can use the Download to download the task result<br>";
		break;
	case "message":
		echo "<B>Message ID:</B> The id of message<br>";
		echo "<B>User Name:</B> The user's name who submit the message<br>";
		echo "<B>Email:</B> The user's email address<br>";
		echo "<B>Subject:</B> The message's subject<br>";
		echo "<B>Submit Date:</B> Time of submit the message<br>";
		echo "<B>Read:</B> The message is readed or not<br>";
		echo "<B>Delete:</B> You can delete the message<br>";
		break;
}	
?>
</body>
</html>