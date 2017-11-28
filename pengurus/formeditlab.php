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
  $updateSQL = sprintf("UPDATE laboratorium SET no_rm=%s, hasil_lab=%s, ket=%s WHERE kd_lab=%s",
                       GetSQLValueString($_POST['no_rm'], "int"),
                       GetSQLValueString($_POST['hasil_lab'], "text"),
                       GetSQLValueString($_POST['ket'], "text"),
                       GetSQLValueString($_POST['kd_lab'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());

  $updateGoTo = "datalab.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_koneksi, $koneksi);
$query_rm = "SELECT * FROM rekam_medis";
$rm = mysql_query($query_rm, $koneksi) or die(mysql_error());
$row_rm = mysql_fetch_assoc($rm);
$totalRows_rm = mysql_num_rows($rm);

$colname_update = "-1";
if (isset($_GET['kd_lab'])) {
  $colname_update = $_GET['kd_lab'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_update = sprintf("SELECT * FROM laboratorium WHERE kd_lab = %s", GetSQLValueString($colname_update, "int"));
$update = mysql_query($query_update, $koneksi) or die(mysql_error());
$row_update = mysql_fetch_assoc($update);
$totalRows_update = mysql_num_rows($update);
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
		      <td nowrap="nowrap" align="right">Kode Laboratorium</td>
		      <td><?php echo $row_update['kd_lab']; ?></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Nomor Rekam Medis</td>
		      <td><select name="no_rm" class="span9">
		        <?php 
do {  
?>
		        <option value="<?php echo $row_rm['no_rm']?>" <?php if (!(strcmp($row_rm['no_rm'], htmlentities($row_update['no_rm'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_rm['no_rm']?></option>
		        <?php
} while ($row_rm = mysql_fetch_assoc($rm));
?>
		        </select></td>
	        </tr>
		    <tr> </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Hasil Laboratorium</td>
		      <td><input type="text" name="hasil_lab" value="<?php echo htmlentities($row_update['hasil_lab'], ENT_COMPAT, 'utf-8'); ?>" size="32" class="span9" /></td>
	        </tr>
		    <tr valign="baseline">
		      <td height="100" align="right" nowrap="nowrap">Keterangan</td>
		      <td><textarea name="ket" id="ket" cols="45" rows="5" class="span9"></textarea></td>
	        </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">&nbsp;</td>
		      <td><input type="submit" value="Simpan" class="btn btn-success" />
	          <input type="reset" name="Reset" id="button" value="Hapus" class="btn btn-warning" /></td>
	        </tr>
	      </table>
		  <input type="hidden" name="MM_update" value="form1" />
		  <input type="hidden" name="kd_lab" value="<?php echo $row_update['kd_lab']; ?>" />
	  </form>
        <p>&nbsp;</p>
    </div>
    </div>
    <?php include("../library/inc/footer.php") ?>
</div>
</body>
</html>
<?php
mysql_free_result($rm);

mysql_free_result($update);
?>
