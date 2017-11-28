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
  $updateSQL = sprintf("UPDATE kunjungan SET no_pasien=%s, kd_poli=%s WHERE kd_kunjungan=%s",
                       GetSQLValueString($_POST['no_pasien'], "int"),
                       GetSQLValueString($_POST['kd_poli'], "int"),
                       GetSQLValueString($_POST['kd_kunjungan'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());

  $updateGoTo = "datakunjungan.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_koneksi, $koneksi);
$query_pasien = "SELECT * FROM pasien";
$pasien = mysql_query($query_pasien, $koneksi) or die(mysql_error());
$row_pasien = mysql_fetch_assoc($pasien);
$totalRows_pasien = mysql_num_rows($pasien);

mysql_select_db($database_koneksi, $koneksi);
$query_poli = "SELECT * FROM poliklinik";
$poli = mysql_query($query_poli, $koneksi) or die(mysql_error());
$row_poli = mysql_fetch_assoc($poli);
$totalRows_poli = mysql_num_rows($poli);

mysql_select_db($database_koneksi, $koneksi);
$query_format = "SELECT * FROM kunjungan";
$format = mysql_query($query_format, $koneksi) or die(mysql_error());
$row_format = mysql_fetch_assoc($format);
$totalRows_format = mysql_num_rows($format);
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
		<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
		  <table align="center">
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Kode Kunjungan :</td>
		      <td><?php echo $row_format['kd_kunjungan']; ?></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Nama Pasien :</td>
		      <td><select name="no_pasien">
		        <?php 
do {  
?>
		        <option value="<?php echo $row_pasien['no_pasien']?>" <?php if (!(strcmp($row_pasien['no_pasien'], htmlentities($row_format['no_pasien'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_pasien['nm_pasien']?></option>
		        <?php
} while ($row_pasien = mysql_fetch_assoc($pasien));
?>
		        </select></td>
	        </tr>
		    <tr> </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Kode PoliKlinik :</td>
		      <td><select name="kd_poli">
		        <?php 
do {  
?>
		        <option value="<?php echo $row_poli['kd_poli']?>" <?php if (!(strcmp($row_poli['kd_poli'], htmlentities($row_format['kd_poli'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_poli['nm_poli']?></option>
		        <?php
} while ($row_poli = mysql_fetch_assoc($poli));
?>
		        </select></td>
	        </tr>
		    <tr> </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">&nbsp;</td>
		      <td><input type="submit" value="Simpan" /></td>
	        </tr>
	      </table>
		  <input type="hidden" name="MM_update" value="form1" />
		  <input type="hidden" name="kd_kunjungan" value="<?php echo $row_format['kd_kunjungan']; ?>" />
	  </form>
		<p style='margin-top:10px'>
		
	</div>
    </div>
</div>
	<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($pasien);

mysql_free_result($poli);

mysql_free_result($format);
?>
