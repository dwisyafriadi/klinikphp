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

$maxRows_tampil = 10;
$pageNum_tampil = 0;
if (isset($_GET['pageNum_tampil'])) {
  $pageNum_tampil = $_GET['pageNum_tampil'];
}
$startRow_tampil = $pageNum_tampil * $maxRows_tampil;

mysql_select_db($database_koneksi, $koneksi);
$query_tampil = "SELECT kd_dokter, tgl_kunjungan, nm_dokter, sip FROM dokter";
$query_limit_tampil = sprintf("%s LIMIT %d, %d", $query_tampil, $startRow_tampil, $maxRows_tampil);
$tampil = mysql_query($query_limit_tampil, $koneksi) or die(mysql_error());
$row_tampil = mysql_fetch_assoc($tampil);

if (isset($_GET['totalRows_tampil'])) {
  $totalRows_tampil = $_GET['totalRows_tampil'];
} else {
  $all_tampil = mysql_query($query_tampil);
  $totalRows_tampil = mysql_num_rows($all_tampil);
}
$totalPages_tampil = ceil($totalRows_tampil/$maxRows_tampil)-1;
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
		<a href='dokterbaru.php' class='btn btn-primary'><i class='icon icon-white icon-plus'></i> Tambah Dokter</a>
	  <table style='margin-top:10px;background:white' class="table table-bordered table-striped table-hover">
		  <tr>
		    <td>Kode Dokter</td>
		    <td>Tanggal Kunjungan</td>
		    <td>Nama Dokter</td>
		    <td>Aksi</td>
	      </tr>
		  <?php do { ?>
		    <tr>
		      <td><?php echo $row_tampil['kd_dokter']; ?></td>
		      <td><?php echo $row_tampil['tgl_kunjungan']; ?></td>
		      <td><?php echo $row_tampil['nm_dokter']; ?></td>
		      <td>
              <div class='btn-group'>
              <a href="hapusdokter.php?kd_dokter=<?php echo $row_tampil['kd_dokter']; ?>" class="btn btn-mini btn-danger tipsy-kiri-atas" title="Hapus Data Ini"><i class="icon-remove icon-white"></i></a> 
              <a href="formeditdokter.php?kd_dokter=<?php echo $row_tampil['kd_dokter']; ?>" class="btn btn-mini btn-info tipsy-kiri-atas" title='Edit Data ini'> <i class="icon-edit icon-white"></i> </a></div>
              </td>
	      </tr>
		    <?php } while ($row_tampil = mysql_fetch_assoc($tampil)); ?>
	  </table>
    </div>
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($tampil);
?>
