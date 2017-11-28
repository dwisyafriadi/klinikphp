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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO login (username, password) VALUES (%s, %s)",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($insertSQL, $koneksi) or die(mysql_error());

  $insertGoTo = "dataadmin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_koneksi, $koneksi);
$query_admin = "SELECT * FROM login";
$admin = mysql_query($query_admin, $koneksi) or die(mysql_error());
$row_admin = mysql_fetch_assoc($admin);
$totalRows_admin = mysql_num_rows($admin);

$maxRows_tampil = 10;
$pageNum_tampil = 0;
if (isset($_GET['pageNum_tampil'])) {
  $pageNum_tampil = $_GET['pageNum_tampil'];
}
$startRow_tampil = $pageNum_tampil * $maxRows_tampil;

mysql_select_db($database_koneksi, $koneksi);
$query_tampil = "SELECT * FROM login";
$query_limit_tampil = sprintf("%s LIMIT %d, %d", $query_tampil, $startRow_tampil, $maxRows_tampil);
$tampil = mysql_query($query_limit_tampil, $koneksi) or die(mysql_error());
$row_tampil = mysql_fetch_assoc($tampil);

if (isset($_GET['totalRows_tampil'])) {
  $totalRows_tampil = $_GET['totalRows_tampil'];
} else {
  $all_tampil = mysql_query($query_tampil);
  $totalRows_tampil = mysql_num_rows($all_tampil);
}
$totalPages_tampil = ceil($totalRows_tampil/$maxRows_tampil)-1;
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
		  <table width="391" align="center">
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right"><div align="left">Username</div></td>
		      <td><input type="text" name="username" value="" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right"><div align="left">Password</div></td>
		      <td><input type="password" name="password" value="" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">&nbsp;</td>
		      <td><input type="submit" value="Tambah" /></td>
	        </tr>
	      </table>
		  <input type="hidden" name="MM_insert" value="form1" />
	  </form>
      <p>&nbsp;</p>
      <table style='margin-top:10px;background:white' class="table table-bordered table-striped table-hover">
          <tr>
            <td>Kode User</td>
            <td>Username</td>
            <td>Password</td>
            <td>Aksi</td>
          </tr>
          <?php do { ?>
            <tr>
              <td><?php echo $row_tampil['kd_user']; ?></td>
              <td><?php echo $row_tampil['username']; ?></td>
              <td><?php echo $row_tampil['password']; ?></td>
              <td>
              <div class='btn-group'>
              <a href="hapusadmin.php?kd_user=<?php echo $row_tampil['kd_user']; ?>" class="btn btn-mini btn-danger tipsy-kiri-atas" title="Hapus Data Ini"><i class="icon-remove icon-white"></i></a> 
              <a href="formeditadmin.php?kd_user=<?php echo $row_tampil['kd_user']; ?>"" class="btn btn-mini btn-info tipsy-kiri-atas" title='Edit Data ini'> <i class="icon-edit icon-white"></i> </a><a >as</a></div>
              </td>
            </tr>
            <?php } while ($row_tampil = mysql_fetch_assoc($tampil)); ?>
      </table>
<p>&nbsp;</p>
    </div>
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($admin);

mysql_free_result($tampil);
?>
