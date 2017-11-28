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
  $updateSQL = sprintf("UPDATE poliklinik SET nm_poli=%s, lantai=%s WHERE kd_poli=%s",
                       GetSQLValueString($_POST['nm_poli'], "text"),
                       GetSQLValueString($_POST['lantai'], "int"),
                       GetSQLValueString($_POST['kd_poli'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());

  $updateGoTo = "datapoliklinik.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_edit = "-1";
if (isset($_GET['kd_poli'])) {
  $colname_edit = $_GET['kd_poli'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_edit = sprintf("SELECT * FROM poliklinik WHERE kd_poli = %s", GetSQLValueString($colname_edit, "int"));
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
		<p>&nbsp;</p>
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
          <table width="446" align="center">
            <tr valign="baseline">
              <td width="168" align="right" nowrap="nowrap"><div align="left">Kode Poli</div></td>
              <td width="266"><?php echo $row_edit['kd_poli']; ?></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right"><div align="left">Nama Poli</div></td>
              <td><input type="text" name="nm_poli" value="<?php echo htmlentities($row_edit['nm_poli'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right"><div align="left">Ruangan</div></td>
              <td><input type="text" name="lantai" value="<?php echo htmlentities($row_edit['lantai'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right"><div align="left"></div></td>
              <td><input type="submit" value="Simpan" /></td>
            </tr>
          </table>
          <input type="hidden" name="MM_update" value="form1" />
          <input type="hidden" name="kd_poli" value="<?php echo $row_edit['kd_poli']; ?>" />
      </form>
        <p>&nbsp;</p>
    </div>
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($edit);
?>
