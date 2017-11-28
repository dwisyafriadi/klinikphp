<?php
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'Table.php';

$table = new Table('kunjungan');
$users = $table->findAll();
?>
<html>
<head>
<style type="text/css" media="print">

	table {border: solid 1px #000; border-collapse: collapse; width: 100%}
	tr { border: solid 1px #000; page-break-inside: avoid;}
	td { padding: 7px 5px; font-size: 10px}
	th {
		font-family:Arial;
		color:black;
		font-size: 11px;
		background-color: #999;
	}
	thead {
		display:table-header-group;
	}
	tbody {
		display:table-row-group;
	}
	h3 { margin-bottom: -17px }
	h2 { margin-bottom: 0px }
</style>
<style type="text/css" media="screen">
	table {border: solid 1px #000; border-collapse: collapse; width: 100%}
	tr { border: solid 1px #000}
	th {
	font-family: "Times New Roman", Times, serif;
	color: black;
	font-size: 11px;
	background-color: #999;
	padding: 8px 0;
	}
	td { padding: 7px 5px;font-size: 10px}
	h3 { margin-bottom: -17px }
	h2 { margin-bottom: 0px }
</style>
<title>Report Kunjungan</title>
</head>

<body onLoad="window.print()">
	<center>
	<div align="center">
	  <center>
	  <b style="font-size: 20px">Sistem Informasi Rawat Jalan Klinik Dahlia</b></div>
	  <hr align="center" size="3" noshade="noshade">
	  <div align="left"><strong>Laporan Kunjungan</strong></div><br>
	</center>
	<table border="1" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
                             <td>Tanggal Kunjungan</td>
		    <td>Nomor Pasien</td>
		    <td>Kode Poliklinik</td>
		    <td>Kode Kunjungan</td>              
                        </tr>
		</thead>
	  <tbody>
              
			<?php $no = 1;
                        
                        foreach($users as $user){?>
                        <tr>
                            <td align="center"><?php echo $user->tgl_kunjungan;?></td>
                            <td align="center"><?php echo $user->no_pasien;?></td>
                            <td><?php echo $user->kd_poli;?></td>
                            <td align="center"><?php echo $user->kd_kunjungan;?></td>
                        </tr>
                        <?php } ?>
	  </tbody>
</table><br><br>
</body>
</html>