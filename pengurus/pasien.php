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

$target = "files/"; 
 $target = $target . basename( $_FILES['foto']['name']);

  $insertSQL = sprintf("INSERT INTO pasien (nm_pasien, j_kel, agama, alamat,tgl_lhr, usia, no_tlp, nm_kk, hub_kel, foto) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nm_pasien'], "text"),
                       GetSQLValueString($_POST['j_kel'], "text"),
                       GetSQLValueString($_POST['agama'], "text"),
                       GetSQLValueString($_POST['alamat'], "text"),
                       GetSQLValueString($_POST['tgl_lhr'], "date"),
                       GetSQLValueString($_POST['usia'], "int"),
                       GetSQLValueString($_POST['no_tlp'], "int"),
                       GetSQLValueString($_POST['nm_kk'], "text"),
                       GetSQLValueString($_POST['hub_kel'], "text"),
					   GetSQLValueString($_FILES['foto']['name'],"text"));
					   if(move_uploaded_file($_FILES['foto']['tmp_name'], $target)) 
 						{

  mysql_select_db($database_koneksi, $koneksi);
  $Result1 = mysql_query($insertSQL, $koneksi) or die(mysql_error());

  $insertGoTo = "datapasien.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  }else{
 header(sprintf("Location: pasien.php?file=error"));
 }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
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
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table style='margin-top:10px;background:transparent' class="table">
    <tr valign="baseline">
      <td width="127" align="right" nowrap="nowrap"><div align="left">Nama Pasien </div></td>
      <td width="483"><input required type="text" name="nm_pasien" value="" size="32" class="span9" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left">Jenis Kelamin </div></td>
      <td><label for="j_kel"></label>
          <input type='radio' name='j_kel' value='L'>  L
			<input type='radio' name='j_kel' value='P'>  P
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left">Agama </div></td>
      <td><label for="agama"></label>
        <select name="agama" id="agama">
          <option value="islam">Islam</option>
          <option value="kristen">Kristen</option>
          <option value="budha">Budha</option>
          <option value="hindu">Hindu</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left">Alamat </div></td>
      <td><input required type="text" name="alamat" value="" size="32" class="span9" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left">Tanggal Lahir </div></td>
      <td><input required type="text" name="tgl_lhr" size="32" class="span9" id="tanggal" />         
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left">Usia </div></td>
      <td><input required type="text" name="usia" value="" size="32" class="span9" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left">Nomor Telephone </div></td>
      <td><input required type="text" name="no_tlp" value="" size="32" class="span9" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left">Nama Kepala Keluarga </div></td>
      <td><input required type="text" name="nm_kk" value="" size="32" class="span9" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left">Hubungan Keluarga </div></td>
      <td><label for="hub_kel"></label>
        <select name="hub_kel" id="hub_kel">
          <option value="Anak Kandung">Anak Kandung</option>
          <option value="Tidak Kandung">Lainnya</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left">Foto </div></td>
      <td><label for="foto">
        <input type="file" name="foto" id="foto" />
      </label></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input class="btn btn-success" type="submit" value="Tambahkan Data" /></td>
    </tr>
  </table>
  <label for="textfield"></label>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>