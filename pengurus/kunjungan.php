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
  $insertSQL = sprintf("INSERT INTO kunjungan (tgl_kunjungan, no_pasien, kd_poli) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['tgl_kunjungan'], "date"),
                       GetSQLValueString($_POST['no_pasien'], "int"),
                       GetSQLValueString($_POST['kd_poli'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($insertSQL, $koneksi) or die(mysql_error());

  $insertGoTo = "datakunjungan.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_koneksi, $koneksi);
$query_poli = "SELECT * FROM poliklinik";
$poli = mysql_query($query_poli, $koneksi) or die(mysql_error());
$row_poli = mysql_fetch_assoc($poli);
$totalRows_poli = mysql_num_rows($poli);

mysql_select_db($database_koneksi, $koneksi);
$query_pasien = "SELECT * FROM pasien";
$pasien = mysql_query($query_pasien, $koneksi) or die(mysql_error());
$row_pasien = mysql_fetch_assoc($pasien);
$totalRows_pasien = mysql_num_rows($pasien);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistem Informasi Rawat Jalan Klinik Dahlia</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table style='margin-top:10px;background:transparent' class="table">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="text" style="display:none" name="tgl_kunjungan" value="<?php echo date("Y-m-d") ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nama Pasien</td>
      <td><select name="no_pasien">
        <?php 
do {  
?>
        <option value="<?php echo $row_pasien['no_pasien']?>" ><?php echo $row_pasien['nm_pasien']?></option>
        <?php
} while ($row_pasien = mysql_fetch_assoc($pasien));
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Kode Poliklinik :</td>
      <td><select name="kd_poli">
        <?php
do {  
?>
        <option value="<?php echo $row_poli['kd_poli']?>"><?php echo $row_poli['nm_poli']?></option>
        <?php
} while ($row_poli = mysql_fetch_assoc($poli));
  $rows = mysql_num_rows($poli);
  if($rows > 0) {
      mysql_data_seek($poli, 0);
	  $row_poli = mysql_fetch_assoc($poli);
  }
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Tambahkan" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($poli);

mysql_free_result($pasien);
?>
