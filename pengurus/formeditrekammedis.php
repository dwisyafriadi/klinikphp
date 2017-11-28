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
  $updateSQL = sprintf("UPDATE rekam_medis SET kd_tindakan=%s, kd_obat=%s, diagnosa=%s, resep=%s, keluhan=%s, ket=%s WHERE no_rm=%s",
                       GetSQLValueString($_POST['kd_tindakan'], "int"),
                       GetSQLValueString($_POST['kd_obat'], "int"),
                       GetSQLValueString($_POST['diagnosa'], "text"),
                       GetSQLValueString($_POST['resep'], "text"),
                       GetSQLValueString($_POST['keluhan'], "text"),
                       GetSQLValueString($_POST['ket'], "text"),
                       GetSQLValueString($_POST['no_rm'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());

  $updateGoTo = "datarekammedis.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_editrekam = "-1";
if (isset($_GET['no_rm'])) {
  $colname_editrekam = $_GET['no_rm'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_editrekam = sprintf("SELECT * FROM rekam_medis WHERE no_rm = %s", GetSQLValueString($colname_editrekam, "int"));
$editrekam = mysql_query($query_editrekam, $koneksi) or die(mysql_error());
$row_editrekam = mysql_fetch_assoc($editrekam);
$totalRows_editrekam = mysql_num_rows($editrekam);

mysql_select_db($database_koneksi, $koneksi);
$query_tindakan = "SELECT * FROM tindakan";
$tindakan = mysql_query($query_tindakan, $koneksi) or die(mysql_error());
$row_tindakan = mysql_fetch_assoc($tindakan);
$totalRows_tindakan = mysql_num_rows($tindakan);

mysql_select_db($database_koneksi, $koneksi);
$query_obat = "SELECT * FROM obat";
$obat = mysql_query($query_obat, $koneksi) or die(mysql_error());
$row_obat = mysql_fetch_assoc($obat);
$totalRows_obat = mysql_num_rows($obat);
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
		      <td nowrap="nowrap" align="right">Nomor Rekam Medis:</td>
		      <td><?php echo $row_editrekam['no_rm']; ?></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Tindakan </td>
		      <td><select class="span9" name="kd_tindakan">
		        <?php 
do {  
?>
		        <option value="<?php echo $row_tindakan['kd_tindakan']?>" <?php if (!(strcmp($row_tindakan['kd_tindakan'], htmlentities($row_editrekam['kd_tindakan'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_tindakan['nm_tindakan']?></option>
		        <?php
} while ($row_tindakan = mysql_fetch_assoc($tindakan));
?>
		        </select></td>
	        </tr>
		    <tr> </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Obat </td>
		      <td><select class="span9" name="kd_obat">
		        <?php 
do {  
?>
		        <option value="<?php echo $row_obat['kd_obat']?>" <?php if (!(strcmp($row_obat['kd_obat'], htmlentities($row_editrekam['kd_obat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_obat['nm_obat']?></option>
		        <?php
} while ($row_obat = mysql_fetch_assoc($obat));
?>
		        </select></td>
	        </tr>
		    <tr> </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Diagnosa </td>
		      <td><select class="span9" name="diagnosa">
		        <option value="gejala" <?php if (!(strcmp("gejala", htmlentities($row_editrekam['diagnosa'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Gejala</option>
		        <option value="terjangkit" <?php if (!(strcmp("terjangkit", htmlentities($row_editrekam['diagnosa'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Hamil</option>
		        <option value="stadium" <?php if (!(strcmp("stadium", htmlentities($row_editrekam['diagnosa'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>lain-lain</option>
		        </select></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Resep</td>
		      <td><input type="text" name="resep" value="<?php echo htmlentities($row_editrekam['resep'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right" valign="top">Keluhan </td>
		      <td><textarea name="keluhan" cols="50" rows="5"><?php echo htmlentities($row_editrekam['keluhan'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right" valign="top">Keterangan </td>
		      <td><textarea name="ket" cols="50" rows="5"><?php echo htmlentities($row_editrekam['ket'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">&nbsp;</td>
		      <td><input type="submit" value="Simpan" class="btn btn-success" />
	          <input type="reset" name="Reset" id="button" value="Hapus" class="btn btn-warning"  /></td>
	        </tr>
	      </table>
		  <input type="hidden" name="MM_update" value="form1" />
		  <input type="hidden" name="no_rm" value="<?php echo $row_editrekam['no_rm']; ?>" />
	  </form>
        <p>&nbsp;</p>
    </div>
    </div>
    <?php include("../library/inc/footer.php") ?>
</div>
</body>
</html>
<?php
mysql_free_result($editrekam);

mysql_free_result($tindakan);

mysql_free_result($obat);
?>
