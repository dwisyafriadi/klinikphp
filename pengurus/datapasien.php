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

$maxRows_datapasien = 10;
$pageNum_datapasien = 0;
if (isset($_GET['pageNum_datapasien'])) {
  $pageNum_datapasien = $_GET['pageNum_datapasien'];
}
$startRow_datapasien = $pageNum_datapasien * $maxRows_datapasien;

mysql_select_db($database_koneksi, $koneksi);
$query_datapasien = "SELECT no_pasien, nm_pasien, j_kel, alamat, no_tlp FROM pasien";
$query_limit_datapasien = sprintf("%s LIMIT %d, %d", $query_datapasien, $startRow_datapasien, $maxRows_datapasien);
$datapasien = mysql_query($query_limit_datapasien, $koneksi) or die(mysql_error());
$row_datapasien = mysql_fetch_assoc($datapasien);

if (isset($_GET['totalRows_datapasien'])) {
  $totalRows_datapasien = $_GET['totalRows_datapasien'];
} else {
  $all_datapasien = mysql_query($query_datapasien);
  $totalRows_datapasien = mysql_num_rows($all_datapasien);
}
$totalPages_datapasien = ceil($totalRows_datapasien/$maxRows_datapasien)-1;
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
		<a href='pasienbaru.php' class='btn btn-primary'><i class='icon icon-white icon-plus'></i> Tambah Pasien</a>
		<table style='margin-top:10px;background:white' class="table table-bordered table-striped table-hover">
		  <tr>
		    <td>Nomor Pasien</td>
		    <td>Nama Pasien</td>
		    <td>Jenis Kelamin</td>
		    <td>Alamat</td>
		    <td>Nomor Telephone</td>
		    <td>Aksi</td>
	      </tr>
		  <?php do { ?>
		    <tr>
		      <td><?php echo $row_datapasien['no_pasien']; ?></td>
		      <td><?php echo $row_datapasien['nm_pasien']; ?></td>
		      <td><?php echo $row_datapasien['j_kel']; ?></td>
		      <td><?php echo $row_datapasien['alamat']; ?></td>
		      <td><?php echo $row_datapasien['no_tlp']; ?></td>
		      <td>
              <div class='btn-group'>
              <a href="hapuspasien.php?no_pasien=<?php echo $row_datapasien['no_pasien']; ?>" class="btn btn-mini btn-danger tipsy-kiri-atas" title="Hapus Data Ini"><i class="icon-remove icon-white"></i></a> 
              <a href="formeditpasien.php?no_pasien=<?php echo $row_datapasien['no_pasien']; ?>" class="btn btn-mini btn-info tipsy-kiri-atas" title='Edit Data ini'> <i class="icon-edit icon-white"></i> </a>
              </div>
              </td>
	      </tr>
		    <?php } while ($row_datapasien = mysql_fetch_assoc($datapasien)); ?>
	  </table>
    </div>
    </div>
    <?php include("../library/inc/footer.php"); ?>
</div>
</body>
</html>
<?php
mysql_free_result($datapasien);
?>
