<?php
session_start();
if(!isset($_SESSION["username"])){
header("Location: login.php");
exit(); }
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Welcome Home</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="form">
<p>are you <?php echo $_SESSION['username']; ?>??</p>
<p>If not<a href="logout.php" style="text-decoration:none;">&nbsp;&nbsp;Logout</a></p>
<p>If yes<a href="index.php" style="text-decoration:none;">&nbsp;&nbsp;reload</a></p>




<br /><br /><br /><br />

</div>
</body>
</html>
