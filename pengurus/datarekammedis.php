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

$maxRows_datarekammedis = 10;
$pageNum_datarekammedis = 0;
if (isset($_GET['pageNum_datarekammedis'])) {
  $pageNum_datarekammedis = $_GET['pageNum_datarekammedis'];
}
$startRow_datarekammedis = $pageNum_datarekammedis * $maxRows_datarekammedis;

mysql_select_db($database_koneksi, $koneksi);
$query_datarekammedis = "SELECT no_rm, no_pasien, diagnosa, tgl_pemeriksaan FROM rekam_medis";
$query_limit_datarekammedis = sprintf("%s LIMIT %d, %d", $query_datarekammedis, $startRow_datarekammedis, $maxRows_datarekammedis);
$datarekammedis = mysql_query($query_limit_datarekammedis, $koneksi) or die(mysql_error());
$row_datarekammedis = mysql_fetch_assoc($datarekammedis);

if (isset($_GET['totalRows_datarekammedis'])) {
  $totalRows_datarekammedis = $_GET['totalRows_datarekammedis'];
} else {
  $all_datarekammedis = mysql_query($query_datarekammedis);
  $totalRows_datarekammedis = mysql_num_rows($all_datarekammedis);
}
$totalPages_datarekammedis = ceil($totalRows_datarekammedis/$maxRows_datarekammedis)-1;
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
		<a href='rekammedisbaru.php' class='btn btn-primary'><i class='icon icon-white icon-plus'></i> Tambah Rekam Medis</a>
		<table style='margin-top:10px;background:white' class="table table-bordered table-striped table-hover">
		  <tr>
		    <td>Nomor Rekam Medis</td>
		    <td>Nomor Pasien</td>
		    <td>Diagnosa</td>
		    <td>Tanggal Pemeriksaan</td>
		    <td>Aksi</td>
	      </tr>
		  <?php do { ?>
		    <tr>
		      <td><?php echo $row_datarekammedis['no_rm']; ?></td>
		      <td><?php echo $row_datarekammedis['no_pasien']; ?></td>
		      <td><?php echo $row_datarekammedis['diagnosa']; ?></td>
		      <td><?php echo $row_datarekammedis['tgl_pemeriksaan']; ?></td>
		      <td>
              <div class='btn-group'>
              <a href="hapusrekammedis.php?no_rm=<?php echo $row_datarekammedis['no_rm']; ?>" class="btn btn-mini btn-danger tipsy-kiri-atas" title="Hapus Data Ini"><i class="icon-remove icon-white"></i></a> 
              <a href="formeditrekammedis.php?no_rm=<?php echo $row_datarekammedis['no_rm']; ?>" class="btn btn-mini btn-info tipsy-kiri-atas" title='Edit Data ini'> <i class="icon-edit icon-white"></i> </a></div>
              </td>
	      </tr>
		    <?php } while ($row_datarekammedis = mysql_fetch_assoc($datarekammedis)); ?>
	  </table>
    </div>
    </div>
    <?php include("../library/inc/footer.php") ?>
</div>
</body>
</html>
<?php
mysql_free_result($datarekammedis);
?>
