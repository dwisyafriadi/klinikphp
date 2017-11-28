<?php require_once('Connections/koneksi.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "pengurus/index.php";
  $MM_redirectLoginFailed = "#";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_koneksi, $koneksi);
  
  $LoginRS__query=sprintf("SELECT username, password FROM login WHERE username=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $koneksi) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!doctype html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>Sistem Informasi Rawat Jalan Klinik Dahlia</title>
	<link rel="shortcut icon" href="images/logo.png" >
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/styles.css">
	
</head>

<body>
	
	
	<div id="container">
		
		<form ACTION="<?php echo $loginFormAction; ?>" method="POST" target="_self" id="loginuser">
		
		<label for="name">Username:</label>
		
		<input name="username" type="name" id="username">
		
		<label for="username">Password:</label>
		
		
		<input name="password" type="password" id="password">
		
		<div id="lower">
		
		
		<input type="submit" value="Masuk >>">
		
		</div>
		
		</form>
		
	</div>
	
<div class="foot">
      <div align="center">
        <p>&nbsp;</p>
        <p><strong>Copyright &copy; 2015 Sistem Informasi Rawat Jalan Klinik Dahlia</strong></p>
        <p>&nbsp;</p>
      </div>
    </div>
    <p>&nbsp;</p>
 
 <body bgcolor="#cccccff">
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="1500" height="1200" border="0" align="center">
  <tr>
    <td width="210" rowspan="3" bgcolor="#cccccff"><marquee>
    <font color="#000000" face="Courier New, Courier, monospace" size="+7"><strong>Klinik Dahlia </strong></font>
    </marquee></td>
    <td width="300" height="100" bgcolor="#cccccff">&nbsp;</td>
    <td width="279" rowspan="3" bgcolor="#cccccff"><marquee>
    <font color="#000000" face="Courier New, Courier, monospace" size="+7"><strong>Klinik Dahlia </strong></font>
    </marquee></td>
  </tr>

 
</body>

</html>
	
	
	
	
	
		
	