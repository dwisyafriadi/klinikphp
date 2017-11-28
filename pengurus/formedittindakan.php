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
  $updateSQL = sprintf("UPDATE tindakan SET nm_tindakan=%s, ket=%s WHERE kd_tindakan=%s",
                       GetSQLValueString($_POST['nm_tindakan'], "text"),
                       GetSQLValueString($_POST['ket'], "text"),
                       GetSQLValueString($_POST['kd_tindakan'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());

  $updateGoTo = "datatindakan.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_edittindakan = "-1";
if (isset($_GET['kd_tindakan'])) {
  $colname_edittindakan = $_GET['kd_tindakan'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_edittindakan = sprintf("SELECT * FROM tindakan WHERE kd_tindakan = %s", GetSQLValueString($colname_edittindakan, "int"));
$edittindakan = mysql_query($query_edittindakan, $koneksi) or die(mysql_error());
$row_edittindakan = mysql_fetch_assoc($edittindakan);
$totalRows_edittindakan = mysql_num_rows($edittindakan);
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
		      <td nowrap="nowrap" align="right">Kode Tindakan</td>
		      <td><?php echo $row_edittindakan['kd_tindakan']; ?></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Nama Tindakan</td>
		      <td><input type="text" name="nm_tindakan" value="<?php echo htmlentities($row_edittindakan['nm_tindakan'], ENT_COMPAT, 'utf-8'); ?>" size="32" class="span9" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right" valign="top">Keterangan Tindakan</td>
		      <td><textarea name="ket" class="span9" cols="50" rows="5"><?php echo htmlentities($row_edittindakan['ket'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">&nbsp;</td>
		      <td><input type="submit" value="Simpan" class="btn btn-success" /></td>
	        </tr>
	      </table>
		  <input type="hidden" name="MM_update" value="form1" />
		  <input type="hidden" name="kd_tindakan" value="<?php echo $row_edittindakan['kd_tindakan']; ?>" />
	  </form>
        <p>&nbsp;</p>
    </div>
    </div>
    <?php include("../library/inc/footer.php") ?>
</div>
</body>
</html>
<?php
mysql_free_result($edittindakan);
?>
