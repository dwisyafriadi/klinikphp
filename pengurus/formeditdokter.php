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
  $updateSQL = sprintf("UPDATE dokter SET kd_poli=%s, kd_user=%s, nm_dokter=%s, sip=%s, tmpat_lhr=%s, no_tlp=%s, alamat=%s WHERE kd_dokter=%s",
                       GetSQLValueString($_POST['kd_poli'], "int"),
                       GetSQLValueString($_POST['kd_user'], "int"),
                       GetSQLValueString($_POST['nm_dokter'], "text"),
                       GetSQLValueString($_POST['sip'], "text"),
                       GetSQLValueString($_POST['tmpat_lhr'], "text"),
                       GetSQLValueString($_POST['no_tlp'], "text"),
                       GetSQLValueString($_POST['alamat'], "text"),
                       GetSQLValueString($_POST['kd_dokter'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());

  $updateGoTo = "datadokter.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_tampil = "-1";
if (isset($_GET['kd_dokter'])) {
  $colname_tampil = $_GET['kd_dokter'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_tampil = sprintf("SELECT * FROM dokter WHERE kd_dokter = %s", GetSQLValueString($colname_tampil, "int"));
$tampil = mysql_query($query_tampil, $koneksi) or die(mysql_error());
$row_tampil = mysql_fetch_assoc($tampil);
$totalRows_tampil = mysql_num_rows($tampil);

mysql_select_db($database_koneksi, $koneksi);
$query_poli = "SELECT * FROM poliklinik";
$poli = mysql_query($query_poli, $koneksi) or die(mysql_error());
$row_poli = mysql_fetch_assoc($poli);
$totalRows_poli = mysql_num_rows($poli);

mysql_select_db($database_koneksi, $koneksi);
$query_user = "SELECT * FROM login";
$user = mysql_query($query_user, $koneksi) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);
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
		  <table width="454" height="278" align="center">
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Kode Dokter </td>
		      <td><?php echo $row_tampil['kd_dokter']; ?></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Poliklinik</td>
		      <td><select name="kd_poli">
		        <?php 
do {  
?>
		        <option value="<?php echo $row_poli['kd_poli']?>" <?php if (!(strcmp($row_poli['kd_poli'], htmlentities($row_tampil['kd_poli'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_poli['nm_poli']?></option>
		        <?php
} while ($row_poli = mysql_fetch_assoc($poli));
?>
		        </select></td>
	        </tr>
		    <tr> </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">User</td>
		      <td><select name="kd_user">
		        <?php 
do {  
?>
		        <option value="<?php echo $row_user['kd_user']?>" <?php if (!(strcmp($row_user['kd_user'], htmlentities($row_tampil['kd_user'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_user['username']?></option>
		        <?php
} while ($row_user = mysql_fetch_assoc($user));
?>
		        </select></td>
	        </tr>
		    <tr> </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Nama Dokter</td>
		      <td><input type="text" name="nm_dokter" value="<?php echo htmlentities($row_tampil['nm_dokter'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">SIP</td>
		      <td><input type="text" name="sip" value="<?php echo htmlentities($row_tampil['sip'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Tempat Lahir</td>
		      <td><input type="text" name="tmpat_lhr" value="<?php echo htmlentities($row_tampil['tmpat_lhr'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Nomor Telephone</td>
		      <td><input type="text" name="no_tlp" value="<?php echo htmlentities($row_tampil['no_tlp'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Alamat</td>
		      <td><input type="text" name="alamat" value="<?php echo htmlentities($row_tampil['alamat'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">&nbsp;</td>
		      <td><input type="submit" value="Perbaharui" /></td>
	        </tr>
	      </table>
		  <input type="hidden" name="MM_update" value="form1" />
		  <input type="hidden" name="kd_dokter" value="<?php echo $row_tampil['kd_dokter']; ?>" />
	  </form>
        <p>&nbsp;</p>
    </div>
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($tampil);

mysql_free_result($poli);

mysql_free_result($user);
?>
