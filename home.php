<!DOCTYPE>
<html>
<meta charset="UTF-8">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<p><div class="page-header">
<p><center><strong>PENILAIAN INSTRUMEN AKREDITASI INSTITUSI DENGAN METODE</strong></p>
    <h1>
    <em>AHP - PROMETHEE</em></h1>
</div></p>
<script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="js/highcharts.js" type="text/javascript"></script>
<script src="js/exporting.js" type="text/javascript"></script>
<script type="text/javascript">
	var chart1; // globally available
$(document).ready(function() {
      chart1 = new Highcharts.Chart({
         chart: {
            renderTo: 'container',
            type: 'column'
         },   
         title: {
            text: 'Data Bobot Kriteria '
         },
         xAxis: {
            categories: ['Data Kriteria Penilaian']
         },
         yAxis: {
            title: {
               text: 'Nilai Bobot'
            }
         },
              series:             
            [
<?php      
// file koneksi php
$server = "localhost";
$username = "root";
$password = "";
$database = "ahp_promethee";
mysql_connect($server,$username,$password) or die("Koneksi gagal");
mysql_select_db($database) or die("Database tidak bisa dibuka");
$sql   = "SELECT * from tb_kriteria"; // file untuk mengakses ke tabel database
$query = mysql_query( $sql ) or die(mysql_error());         
while($ambil = mysql_fetch_array($query)){
	$nk=$ambil['nama_kriteria'];
	$sql_jumlah   = "SELECT * from tb_kriteria where nama_kriteria='$nk'";        
	$query_jumlah = mysql_query( $sql_jumlah ) or die(mysql_error());
	while( $data = mysql_fetch_array( $query_jumlah ) ){
	   $jumlahx = $data['bobot'];                 
	  }             
	  
	  ?>
	  {
		  name: '<?php echo $nk; ?>',
		  data: [<?php echo $jumlahx; ?>]
	  },
	  <?php } ?>
]
});
});	
</script>
</head>
<body>
<!-- fungsi yang di tampilkan dibrowser  -->
<div id="container" style="min-width: 200px; height: 400px; margin: 0 auto"></div>
<p>
  <style type="text/css">
.page-header h1 em {
	color: #80FF00;
}
  </style>
</p>
<p>&nbsp; </p>
<p style="text-align: justify; text-indent: 0.5in;">Data diatas merupakan hasil dari penilaian Metode AHP (Analytical Hierarcy Process) merupakan metode yang dipergunakan pada Sistem pendukung Keputusan (SPK), yang digunakan untuk memecahkan permasalaan yang bersifat multikriteria (Saaty, 1993), Merupakan hasil penilaian dari bobot kriteria. Kemudian dilakukan perangkingan menggunakan Metode Promethee yang menghitung bobot nilai alternatif maka dihasilkanlah penilaian sebagai berikut ini. dipergunakan untuk mengevaluasi alternatif dengan kriteria yang diberikan dan membuat peringkat alternatif untuk keputusan akhir dengan menghitung indeks untuk setiap pasangan alternatif yang memenuhi syarat atau antara peringkat satu relatif dengan alternatif lain. (Brans and Vicke, 1985).</p>
<p style="text-align: justify; text-indent: 0.5in;">Penerapan didalam sistem ini, AHP dijalankan terlebih dahulu untuk mendapatkan bobot kriteria. setelah itu PROMETHEE dijalankan untuk menentukan urutan prioritas untuk penilaian standar akreditasi untuk program studi. Sehingga tujuan kombinasi ini adalah untuk meningkatkan kualitas sasaran kaprodi dalam menentukan prioritas penilaian instrumen akreditasi.</p>

</body>
</html>
