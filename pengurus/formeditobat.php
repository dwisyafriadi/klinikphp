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
  $updateSQL = sprintf("UPDATE obat SET nm_obat=%s, jml_obat=%s, ukuran=%s, harga=%s WHERE kd_obat=%s",
                       GetSQLValueString($_POST['nm_obat'], "text"),
                       GetSQLValueString($_POST['jml_obat'], "int"),
                       GetSQLValueString($_POST['ukuran'], "int"),
                       GetSQLValueString($_POST['harga'], "int"),
                       GetSQLValueString($_POST['kd_obat'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());

  $updateGoTo = "dataobat.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_edit = "-1";
if (isset($_GET['kd_obat'])) {
  $colname_edit = $_GET['kd_obat'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_edit = sprintf("SELECT * FROM obat WHERE kd_obat = %s", GetSQLValueString($colname_edit, "int"));
$edit = mysql_query($query_edit, $koneksi) or die(mysql_error());
$row_edit = mysql_fetch_assoc($edit);
$totalRows_edit = mysql_num_rows($edit);
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
		  <table style='margin-top:10px;background:transparent' class="table">
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Kode Obat</td>
		      <td><?php echo $row_edit['kd_obat']; ?></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Nama Obat</td>
		      <td><input class="span9" type="text" name="nm_obat" value="<?php echo htmlentities($row_edit['nm_obat'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Jumlah Obat</td>
		      <td><input class="span9" type="text" name="jml_obat" value="<?php echo htmlentities($row_edit['jml_obat'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Ukuran</td>
		      <td><input class="span9" type="text" name="ukuran" value="<?php echo htmlentities($row_edit['ukuran'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Harga Obat</td>
		      <td><input class="span9" type="text" name="harga" value="<?php echo htmlentities($row_edit['harga'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">&nbsp;</td>
		      <td><input type="submit" value="Simpan" class="btn btn-success" /></td>
	        </tr>
	      </table>
		  <input type="hidden" name="MM_update" value="form1" />
		  <input type="hidden" name="kd_obat" value="<?php echo $row_edit['kd_obat']; ?>" />
	  </form>
        <p>&nbsp;</p>
    </div>
    </div>
    <?php include("../library/inc/footer.php") ?>
</div>
</body>
</html>
<?php
mysql_free_result($edit);
?>
