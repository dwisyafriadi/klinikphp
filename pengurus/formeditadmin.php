<?php require_once('../Connections/koneksi.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE login SET username=%s, password=%s WHERE kd_user=%s",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['kd_user'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());
}

$colname_updet = "-1";
if (isset($_GET['kd_user'])) {
  $colname_updet = $_GET['kd_user'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_updet = sprintf("SELECT * FROM login WHERE kd_user = %s", GetSQLValueString($colname_updet, "int"));
$updet = mysql_query($query_updet, $koneksi) or die(mysql_error());
$row_updet = mysql_fetch_assoc($updet);
$totalRows_updet = mysql_num_rows($updet);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel='shortcut icon' type='image icon' href='../library/favicon.png'/>
<link rel='stylesheet' type='text/css' href='../library/bootstrap/bootstrap.css'/>
<link rel='stylesheet' type='text/css' href='../library/bootstrap/style.css'/>
<script src="../library/bootstrap/jquery.min.js"></script>
<title>Sistem Informasi Rawat Jalan Klinik Dahlia</title>
</head>

<body>
<div class='container-fluid' style='margin-top:20px;'>
<?php include("navbar.php"); ?>
	<div class='row-fluid'>
	<?php include("sidebar.php"); ?>
    <div class="span9">
    <div class='well' fixed center;'>
		<b>Sistem Informasi Rawat Jalan Klinik Dahlia</b>
		<p style='margin-top:10px'>
		<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
		  <table align="center">
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Kd_user:</td>
		      <td><?php echo $row_updet['kd_user']; ?></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Username:</td>
		      <td><input type="text" name="username" value="<?php echo htmlentities($row_updet['username'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Password:</td>
		      <td><input type="text" name="password" value="<?php echo htmlentities($row_updet['password'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">&nbsp;</td>
		      <td><input type="submit" value="Update record" /></td>
	        </tr>
	      </table>
		  <input type="hidden" name="MM_update" value="form1" />
		  <input type="hidden" name="kd_user" value="<?php echo $row_updet['kd_user']; ?>" />
	  </form>
        <p>&nbsp;</p>
    </div>
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($updet);
?>
