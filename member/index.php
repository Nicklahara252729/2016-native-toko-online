<?php
ob_start();
include"../koneksi.php";
//include"../validpage-member.php";
if(!isset($_SESSION['user'])){
	session_start();
}


$limit = 25;
if(!isset($_GET['halaman'])){
	$halaman = 1;
	$posisi =0 ;
}else{
	$halaman = $_GET['halaman'];
	$posisi = ($halaman-1)*$limit;
}
if(isset($_GET['sch_cari'])){
	$key =$_GET['sch_cari'];
	if(isset($_GET['kategori'])){
		$kat= $_GET['kategori'];
		$tsql = mysql_query("select * from produk where nama_produk like '%$key%' and kategori like '%$kat%' order by id_produk asc limit $posisi,$limit");
	}else{
		$tsql = mysql_query("select * from produk where nama_produk like '%$key%' order by id_produk asc limit $posisi,$limit");
	}
}else{
		$tsql = mysql_query("select * from produk order by id_produk asc limit $posisi,$limit");
}
$tjml = mysql_num_rows($tsql);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Shopping Store</title>
<script src="../jquery-mobile/jquery-1.6.4.min.js" type="text/javascript"></script>
<link href="../css/default.css" rel="stylesheet">
<script src="../js/default.js" type="text/javascript"></script>
<script>
window.setTimeout("waktu()",1000);
function waktu(){
	var now = new Date();
	var jam = now.getHours();
	var menit = now.getMinutes();
	window.setTimeout("waktu()",1000);
	document.getElementById('jam').innerHTML=jam + " : "+menit;
	}
</script>
</head>

<body>

<span class="top"></span>


<div class="message">
<div class="isi-message" id="left-msg">
<img src="../img/<?php ?>" class="img-msg">
</div>
<div class="isi-message" id="right-msg">
<p><?php echo"nama user"; ?> <br> <div id="jam"></div></p>
</div>
</div>
<header>
  <div class="header-atas">
    <div class="content-header"> 
    <div class="isi-ha">
    <img src="../img/biglogo.png" class="big-logo">
    <button type="button" class="btn-login">Login</button>
    <button type="button" class="btn-register">Register</button>
    </div>
    </div>
  </div>
  <div class="header-tengah">
    <div class="content-header">
      <div class="isi-ht" id="ht-satu">
      <img src="../img/logo.png" class="img-logo"><br>
      </div>
      <div class="isi-ht" id="ht-dua">
      <ul>
      <li>New Brand</li>
      <li>Discount</li>
      <li>Top Produk</li>
      <li>Bantuan</li>
      <li>Keranjang</li>
      </ul>
      </div>
      <div class="isi-ht" id="ht-tiga">
      <form target="_self" name="cari" id="cari" enctype="multipart/form-data" method="get">
      <input type="search" name="sch_cari" id="sch_cari" placeholder="Search" onKeyUp="this.submit();">
      <select name="kategori" id="kategori">
      <option disabled selected>- Plih Kategori -</option>
      <?php
	  $ksql = mysql_query("select * from kategori order by kategori asc");
	  while ($fk = mysql_fetch_assoc($ksql)){
		  echo"<option value='$fk[kategori]'>$fk[kategori]</option>";
	  }
	  ?>
      </select>
      </form>
      </div>
    </div>
  </div>
  <div class="header-bawah">
    <div class="content-header">
      <div class="isi-hb" id="hb-satu">
      <img src="../img/f.jpg" class="img-kategori">
      </div>
      <div class="isi-hb" id="hb-dua">
      <img src="../img/e.jpg" class="img-kategori">
      </div>
      <div class="isi-hb" id="hb-tiga">
            <img src="../img/d.jpg" class="img-kategori">
      </div>
      <div class="isi-hb" id="hb-empat">
            <img src="../img/c.jpg" class="img-kategori">
      </div>
      <div class="isi-hb" id="hb-lima">
            <img src="../img/b.jpg" class="img-kategori">
      </div>
      <div class="isi-hb" id="hb-enam">
            <img src="../img/a.jpg" class="img-kategori">
      </div>
    </div>
  </div>
</header>

<main>
  <div class="content">
    <div class="in-content" id="top-content">
    <?php
	if($tjml > 0){
		while($ft = mysql_fetch_array($tsql)){
			
	?>
      <div class="isi-content">
        <div class="isi-atas">
        <img src="img/<?php echo $ft['gambar']; ?>" class="img-produk">
        </div>
        <div class="isi-bawah">
        <?php echo $ft['nama_produk'];?>
        </div>
      </div>
      <?php
		}
	}else{
		echo "Item Not Found";
	}
	  ?>
    </div>
    <div class="in-content" id="bottom-content">
    <?php 
	$tsql2 =mysql_query("select * from produk");
	$jdata = mysql_num_rows($tsql2);
	$jhal = ceil($jdata/$limit);
	if($halaman > 1){
		$prev = $halaman -1;
		echo"<a href='$_SERVER[PHP_SELF]?halaman=$prev'><< Prev</a>";
	}else{
		echo"<span class='page'disabled><< Prev</span>";
	}
	for($i=1;$i<=$jhal;$i++)
	if($i!=$halaman){
		echo"<a href='$_SERVER[PHP_SELF]?halaman=$i'>$i</a>";
	}else{
		echo"<span class='page' current>$i</span>";
	}
	if($halaman > $jhal){
		$next = $halaman +1;
		echo"<a href='$_SERVER[PHP_SELF]?halaman=$next'><< Next</a>";
	}else{
		echo"<span class='page'disabled><< Next</span>";
	}
	?>
    </div>
  </div>
</main>

<footer>
  <div class="footer-atas">
    <div class="cf-atas">
      <div class="isi-fa" id="fa-satu"></div>
      <div class="isi-fa" id="fa-dua"></div>
      <div class="isi-fa" id="fa-tiga"></div>
    </div>
  </div>
  <div class="footer-bawah">
    <div class="cf-bawah">
    
    </div>
  </div>
</footer>

</body>
</html>
<?php
mysql_close($koneksi);
ob_flush();
?>