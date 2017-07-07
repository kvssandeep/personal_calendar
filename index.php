
<?php include_once('main.php'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> CALENDAR </title>
<link type="text/css" rel="stylesheet" href="style.css"/>
<script src="jquery.min.js"></script>
</head>
<body>
    <p style="display:inline;">
        <a style="color:darkblue; font:italic 15pt Times;font-weight:bold;margin-top:0px;text-align:center; text-decoration:none; " onmouseover="this.style.color='red';
				    this.style.font='italic 15pt Times';"
onmouseout="this.style.color='blue';this.style.font='italic 15pt Times ';">
            <pre>            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;               <?php echo 'welcome  '    .$user;?></a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;       <a href='logout.php' style="text-decoration:none;"> sign out</pre></a></p>
    <div id="calendar_div">
	<?php echo getCalender(); ?>
</div>

</body>
</html>
