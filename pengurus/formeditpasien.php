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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE pasien SET nm_pasien=%s, j_kel=%s, agama=%s, alamat=%s, tgl_lhr=%s, usia=%s, no_tlp=%s, nm_kk=%s, hub_kel=%s WHERE no_pasien=%s",
                       GetSQLValueString($_POST['nm_pasien'], "text"),
                       GetSQLValueString($_POST['j_kel'], "text"),
                       GetSQLValueString($_POST['agama'], "text"),
                       GetSQLValueString($_POST['alamat'], "text"),
                       GetSQLValueString($_POST['tgl_lhr'], "date"),
                       GetSQLValueString($_POST['usia'], "int"),
                       GetSQLValueString($_POST['no_tlp'], "int"),
                       GetSQLValueString($_POST['nm_kk'], "text"),
                       GetSQLValueString($_POST['hub_kel'], "text"),
                       GetSQLValueString($_POST['no_pasien'], "int"));

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());

  $updateGoTo = "#";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_editdata = "-1";
if (isset($_GET['no_pasien'])) {
  $colname_editdata = $_GET['no_pasien'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_editdata = sprintf("SELECT * FROM pasien WHERE no_pasien = %s", GetSQLValueString($colname_editdata, "int"));
$editdata = mysql_query($query_editdata, $koneksi) or die(mysql_error());
$row_editdata = mysql_fetch_assoc($editdata);
$totalRows_editdata = mysql_num_rows($editdata);
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
	<?php include("sidebar.php"); ?>
    <div class="span9">
    <div class='well' fixed center;'>
		<b>Sistem Informasi Rawat Jalan Klinik Dahlia</b>
		<p style='margin-top:10px'>
	  <form method="post" name="form1" id="form1">
	    <input type="hidden" name="no_pasien" />
	  </form>
      <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
        <table style='margin-top:10px;background:transparent' class="table">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Nomor Pasien</td>
            <td><?php echo $row_editdata['no_pasien']; ?></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Nama Pasien</td>
            <td><input required type="text" name="nm_pasien" value="<?php echo htmlentities($row_editdata['nm_pasien'], ENT_COMPAT, 'utf-8'); ?>" size="32" class="span9" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Jenis Kelamin</td>
            <td>
             <input type='radio' name='j_kel' value='L'>  L
			<input type='radio' name='j_kel' value='P'>  P
			</select></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Agama Pasien</td>
            <td><select name="agama">
              <option value="islam" <?php if (!(strcmp("islam", htmlentities($row_editdata['agama'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Islam</option>
              <option value="Kristen" <?php if (!(strcmp("Kristen", htmlentities($row_editdata['agama'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Kristen</option>
              <option value="budha" <?php if (!(strcmp("budha", htmlentities($row_editdata['agama'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Budha</option>
              <option value="hindu" <?php if (!(strcmp("hindu", htmlentities($row_editdata['agama'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Hindu</option>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Alamat Pasien</td>
            <td><input required type="text" name="alamat" value="<?php echo htmlentities($row_editdata['alamat'], ENT_COMPAT, 'utf-8'); ?>" size="32" class="span9" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Tanggal Lahir</td>
            <td><input required type="text" name="tgl_lhr" id="tanggal" value="<?php echo htmlentities($row_editdata['tgl_lhr'], ENT_COMPAT, 'utf-8'); ?>" size="32" class="span9" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Usia</td>
            <td><input required type="text" name="usia" value="<?php echo htmlentities($row_editdata['usia'], ENT_COMPAT, 'utf-8'); ?>" size="32" class="span9" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Nomor Telephone</td>
            <td><input required type="text" name="no_tlp" value="<?php echo htmlentities($row_editdata['no_tlp'], ENT_COMPAT, 'utf-8'); ?>" size="32" class="span9" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Nama Kepala Keluarga</td>
            <td><input required type="text" name="nm_kk" value="<?php echo htmlentities($row_editdata['nm_kk'], ENT_COMPAT, 'utf-8'); ?>" size="32" class="span9" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Hubungan Keluarga</td>
            <td><select name="hub_kel">
              <option value="Anak Kandung" <?php if (!(strcmp("Anak Kandung", htmlentities($row_editdata['hub_kel'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Anak Kandung</option>
              <option value="Tidak Kandung" <?php if (!(strcmp("Tidak Kandung", htmlentities($row_editdata['hub_kel'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Lainnya</option>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="Simpan" class="btn btn-success" />
            <input name="button" type="submit" class="btn btn-warning" id="button" value="Hapus" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form2" />
        <input type="hidden" name="no_pasien" value="<?php echo $row_editdata['no_pasien']; ?>" />
      </form>
      <p>&nbsp;</p>
<p>&nbsp;</p>
    </div>
    </div>
    <?php include("../library/inc/footer.php") ?>
</div>
</body>
</html>
<?php
mysql_free_result($editdata);
?>
