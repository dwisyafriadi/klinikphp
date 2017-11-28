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
  $insertSQL = sprintf("INSERT INTO dokter (kd_poli, tgl_kunjungan, kd_user, nm_dokter, sip, tmpat_lhr, no_tlp, alamat) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['kd_poli'], "int"),
                       GetSQLValueString($_POST['tgl_kunjungan'], "date"),
                       GetSQLValueString($_POST['kd_user'], "int"),
                       GetSQLValueString($_POST['nm_dokter'], "text"),
                       GetSQLValueString($_POST['sip'], "text"),
                       GetSQLValueString($_POST['tmpat_lhr'], "text"),
                       GetSQLValueString($_POST['no_tlp'], "text"),
                       GetSQLValueString($_POST['alamat'], "text"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($insertSQL, $koneksi) or die(mysql_error());

  $insertGoTo = "datadokter.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_koneksi, $koneksi);
$query_tampil = "SELECT * FROM dokter";
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
	<link type="text/css" href="js/themes/base/ui.all.css" rel="stylesheet" /> 
<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="js/ui.core.js"></script>
<script type="text/javascript" src="js/ui.datepicker.js"></script>


<script type="text/javascript"> 
      $(document).ready(function(){
        $("#tanggal").datepicker({
		dateFormat  : "yy-mm-dd", 
          changeMonth : true,
          changeYear  : true
		  
        });
      });
	  
    </script>
</head>

<body>
<div class='container-fluid' style='margin-top:20px;'>
<?php include("navbar.php"); ?>
	<div class='row-fluid'>
	<?php include("sidebar.php"); ?>>
    <div class="span9">
    <div class='well' fixed center;'>
		<b>Sistem Informasi Rawat Jalan Klinik Dahlia</b>
		<p style='margin-top:10px'>
		<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
		  <table style='margin-top:10px;background:transparent' class="table">
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Kode Poli</td>
		      <td><select name="kd_poli">
		        <?php 
do {  
?>
		        <option value="<?php echo $row_poli['kd_poli']?>" ><?php echo $row_poli['nm_poli']?></option>
		        <?php
} while ($row_poli = mysql_fetch_assoc($poli));
?>
		        </select></td>
	        </tr>
		    <tr> </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Tanggal Kunjungan</td>
		      <td><input type="text" name="tgl_kunjungan" value="" id="tanggal" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Admin</td>
		      <td><select name="kd_user">
		        <?php 
do {  
?>
		        <option value="<?php echo $row_user['kd_user']?>" ><?php echo $row_user['username']?></option>
		        <?php
} while ($row_user = mysql_fetch_assoc($user));
?>
		        </select></td>
	        </tr>
		    <tr> </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Nama Dokter</td>
		      <td><input type="text" name="nm_dokter" value="" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">SIP</td>
		      <td><input type="text" name="sip" value="" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Tempat Lahir</td>
		      <td><input type="text" name="tmpat_lhr" value="" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Nomor Telephone</td>
		      <td><input type="text" name="no_tlp" value="" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Alamat</td>
		      <td><input type="text" name="alamat" value="" size="32" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">&nbsp;</td>
		      <td><input type="submit" value="Tambahkan" /></td>
	        </tr>
	      </table>
		  <input type="hidden" name="MM_insert" value="form1" />
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
