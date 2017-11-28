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
  $insertSQL = sprintf("INSERT INTO rekam_medis (kd_tindakan, kd_obat, kd_user, no_pasien, diagnosa, resep, keluhan, tgl_pemeriksaan, ket) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['kd_tindakan'], "int"),
                       GetSQLValueString($_POST['kd_obat'], "int"),
                       GetSQLValueString($_POST['kd_user'], "int"),
                       GetSQLValueString($_POST['no_pasien'], "int"),
                       GetSQLValueString($_POST['diagnosa'], "text"),
                       GetSQLValueString($_POST['resep'], "text"),
                       GetSQLValueString($_POST['keluhan'], "text"),
                       GetSQLValueString($_POST['tgl_pemeriksaan'], "date"),
                       GetSQLValueString($_POST['ket'], "text"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($insertSQL, $koneksi) or die(mysql_error());

  $insertGoTo = "datarekammedis.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_koneksi, $koneksi);
$query_menutindakan = "SELECT * FROM tindakan";
$menutindakan = mysql_query($query_menutindakan, $koneksi) or die(mysql_error());
$row_menutindakan = mysql_fetch_assoc($menutindakan);
$totalRows_menutindakan = mysql_num_rows($menutindakan);

mysql_select_db($database_koneksi, $koneksi);
$query_menuobat = "SELECT * FROM obat";
$menuobat = mysql_query($query_menuobat, $koneksi) or die(mysql_error());
$row_menuobat = mysql_fetch_assoc($menuobat);
$totalRows_menuobat = mysql_num_rows($menuobat);

mysql_select_db($database_koneksi, $koneksi);
$query_menuadmin = "SELECT * FROM login";
$menuadmin = mysql_query($query_menuadmin, $koneksi) or die(mysql_error());
$row_menuadmin = mysql_fetch_assoc($menuadmin);
$totalRows_menuadmin = mysql_num_rows($menuadmin);

mysql_select_db($database_koneksi, $koneksi);
$query_menupasien = "SELECT * FROM pasien";
$menupasien = mysql_query($query_menupasien, $koneksi) or die(mysql_error());
$row_menupasien = mysql_fetch_assoc($menupasien);
$totalRows_menupasien = mysql_num_rows($menupasien);
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
      <td nowrap="nowrap" align="right">Tindakan </td>
      <td><select class="span9" name="kd_tindakan">
        <?php 
do {  
?>
        <option value="<?php echo $row_menutindakan['kd_tindakan']?>" ><?php echo $row_menutindakan['nm_tindakan']?></option>
        <?php
} while ($row_menutindakan = mysql_fetch_assoc($menutindakan));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Obat</td>
      <td><select class="span9" name="kd_obat">
        <?php 
do {  
?>
        <option value="<?php echo $row_menuobat['kd_obat']?>" ><?php echo $row_menuobat['nm_obat']?></option>
        <?php
} while ($row_menuobat = mysql_fetch_assoc($menuobat));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Admin </td>
      <td><select class="span9" name="kd_user">
        <?php
do {  
?>
        <option value="<?php echo $row_menuadmin['kd_user']?>"><?php echo $row_menuadmin['username']?></option>
        <?php
} while ($row_menuadmin = mysql_fetch_assoc($menuadmin));
  $rows = mysql_num_rows($menuadmin);
  if($rows > 0) {
      mysql_data_seek($menuadmin, 0);
	  $row_menuadmin = mysql_fetch_assoc($menuadmin);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nama Pasien</td>
      <td><select class="span9" name="no_pasien">
        <?php
do {  
?>
        <option value="<?php echo $row_menupasien['no_pasien']?>"><?php echo $row_menupasien['nm_pasien']?></option>
        <?php
} while ($row_menupasien = mysql_fetch_assoc($menupasien));
  $rows = mysql_num_rows($menupasien);
  if($rows > 0) {
      mysql_data_seek($menupasien, 0);
	  $row_menupasien = mysql_fetch_assoc($menupasien);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td height="25" align="right" nowrap="nowrap">Diagnosa </td>
      <td><label for="diagnosa"></label>
        <select class="span9" name="diagnosa" id="diagnosa">
          <option value="Gejala">Kelelahan</option>
          <option value="Terjangkit">Panas</option>
          <option value="Stadium">Flu</option>
          <option value="Stadium">Diare</option>
           <option value="Stadium">Hamil</option>
            <option value="Stadium">Gusi Bengkak</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Resep </td>
      <td><input type="text" name="resep" value="" size="32" />
      <input style="display:none;" type="text" name="tgl_pemeriksaan" value=<?php $tgl=date('Y-m-d'); echo $tgl; ?> size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Keluhan </td>
      <td><label for="keluhan"></label>
      <textarea name="keluhan" id="keluhan" cols="45" rows="5"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Keterangan </td>
      <td><label for="ket"></label>
      <textarea name="ket" id="ket" cols="45" rows="5"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Tambahkan" class="btn btn-success" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($menutindakan);

mysql_free_result($menuobat);

mysql_free_result($menuadmin);

mysql_free_result($menupasien);
?>
