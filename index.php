<?php
$path = '_tmp/';
$dirs = opendir($path);
while($dir = readdir($dirs)){
	if($dir!="."&&$dir!=".."){
		$filename = $dir;
		if (($handle = fopen($path.$filename, "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, filesize($path.$filename), ",")) !== FALSE) {
		        $num = count($data);
		        if($data[0]=="__TIME__"){
		        	if((intval($data[1])+(60*60*24))<=(intval(date("U")))){
		        		// var_dump(chmod('C:/xampp/htdocs/bagol/'.$path.$filename, 0777));
		        		@unlink($path.$filename);
		        	}
		        }
		    }
		    fclose($handle);
		}
	}
}
$PAGE = (isset($_GET['page']))?$_GET['page']:"home";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SmartGraph</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="author" content="" />
<link href="stylesheet.css" rel="stylesheet" type="text/css" />
<link href="graph.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/jquery-ui/js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="scripts/jqSuitePHP/js/jquery.jqChart.min.js"></script>
<script type="text/javascript" src="scripts/main.js"></script>
</head>

<body>
<div id="container">

<div id="top">
</div>

<div id="menu_left">

<ul id="navLeft">
<li class="li_nav_header"><a href="#"></a></li>	
<li class="li_nav_body"><a href="index.php">Home</a></li>
<li class="li_nav_body"><a href="index.php?page=play">Play</a></li>
<li class="li_nav_body"><a href="index.php?page=how_to">How To Play</a></li>	
<li class="li_nav_body"><a href="index.php?page=about">About</a></li>	
<li class="li_nav_footer"><a href="#"></a></li>		
</ul>



</div>

<div id="head">
<h1>SmartGraph</h1>
<h2>Belajar Grafik dengan cara yang menyenangkan!</h2>
<p style="color:white;">
Selamat datang di SmartGraph! <br />
Sebuah aplikasi pembuatan grafik matematika secara dinamis <br />
Dengan SmartGraph, eksplorasi kemampuan kamu!<br />
<br />
</p>
</div>

<div id="content">

	<?php require "pages/".$PAGE.".php";?>

</div>


<div id="content_footer">
	
</div>

</div>

</body>
</html>