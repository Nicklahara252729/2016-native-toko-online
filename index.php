<?php
ob_start();
include"koneksi.php";
if(!isset($_SESSION['user'])){
	session_start();
}
$pesan ="Welcome";
if(isset($_POST['username'])){
	$user =strip_tags(trim($_POST['username']));
	$pass =strip_tags(trim($_POST['password']));
	$pass1 =sha1($pass);
	if(isset($_POST['remember'])){
		setcookie("username",$user,time() + (3600*24));
		setcookie("password",$pass,time() + (3600*24));
	}else{
		unset($_COOKIE['username']);
		unset($_COOKIE['password']);
	}
	$sql = mysql_query("select * from user where username='$user' and password='$pass1'");
	$jml = mysql_num_rows($sql);
	$r = mysql_fetch_array($sql);
	if($jml  > 0 ){
		$_SESSION['nama']=$r['nama'];
		$_SESSION['user']=$r['username'];
		$_SESSION['pass']=$r['password'];
		$_SESSION['level']=$r['level'];
		$_SESSION['email']=$r['emai;'];
		$_SESSION['foto']=$r['foto'];
		if($r['level']=="admin" or $level=="user"){
			header("location:admin/index.php");
		}else{
			header("location:member/index.php");
		}
		$pesan = "Username dan password valid";
	}else{
		$pesan = "Username dan password tidak valid ! Harap cek kembali";
	}
}

if(isset($_POST['rusername'])){
	$rnama = strip_tags(trim($_POST['rnama']));
	$ruser = strip_tags(trim($_POST['rusername']));
	$rpass = strip_tags(trim($_POST['rpassword']));
	$remail = strip_tags(trim($_POST['remail']));
	$rfoto = $_FILES['rfile']['name']?$_FILES['rfile']['name']:"default.jpg";
	$rsize = $_FILES['rfile']['size'];
	$rsql = mysql_query("select * from user where username='$ruser' and password='$rpass'");
	$rjml = mysql_num_rows($rsql);
	if($rjml > 0){
		?>
        <script>alert('Username <?php echo $ruser; ?> sudah ada');history.back();</script>
        <?php
	}else{
		if($rsize > 2097152){
			?>
            <script>alert('Ukuran foto terlalu besar');history.back();</script>
            <?php
		}else{
			$simpan = mysql_query("insert into user set nama_user='$rnama', username='$ruser',password='$rpass',email='$remail', level='member', foto='$rfoto'");
			if($simpan && isset($_FILES['rfile']['name'])){
				move_uploaded_file($_FILES['rfilr']['tmp_name'],"img/".$rfoto);
			}
		}
	}
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
<script src="jquery-mobile/jquery-1.6.4.min.js" type="text/javascript"></script>
<link href="css/default.css" rel="stylesheet">
<script src="js/default.js" type="text/javascript"></script>
<script type="text/javascript">
function cekpass(){
	var pass = document.getElementById('rpassword').value;
	var pass1 = document.getElementById('rpassword').value;
	var pass2 = document.getElementById('rconfirm').value;
	if(pass.length < 8 && pass1!=pass2){
		document.getElementById('msglogin').style.color="red";
		document.getElementById('msglogin').innerHTML="Password length must more than 8 character";
		document.getElementById('rpassword').focus();
		return false;
	}	
	else if(pass.length >=8 && pass2.length<0){
		document.getElementById('msglogin').style.color="green";
		document.getElementById('msglogin').innerHTML="Password length is good";
	}
	else	if(pass.length >= 8 && pass1!=pass2){
		document.getElementById('msglogin').style.color="red";
		document.getElementById('msglogin').innerHTML="Password length doesn't match";
		document.getElementById('rconfirm').focus();
		return false;
	}
	else	if(pass.length >=8 && pass1==pass2){
		document.getElementById('msglogin').style.color="blue";
		document.getElementById('msglogin').innerHTML="Password accepted";
	}
}

function cekfile(){
	var filein =document.getElementById('rfile');
	var info = filein.files[0];
	var size = info.size;
	var mbszie = Math.round(size / 1048576);
	var kbsize =Math.round(size /1024);
	if(size > 2097152){
		document.getElementById('msgfile').style.color="red";
		document.getElementById('msgfile').innerHTML="Your photo size is too large : "+(mbsize) +" Mb";
		document.getElementById('msgfile').focus();
		return false;
	}else{
		document.getElementById('msgfile').style.color="blue";
		document.getElementById('msgfile').innerHTML="Your photo size was accepted : "+(kbsize)+" kb";
	}
}
</script>
</head>

<body>

<span class="top"><img src="img/top.png" class="img-top"></span>
<div class="login">
<button type="button" class="close" onClick="location.href='index.php'"> X </button>
<div class="content-login">
<div class="isi-login" id="top-login">
<div class="isi-tl"><img src="img/default.jpg" class="img-login"></div>
</div>
<div class="isi-login" id="bottom-login">
<div class="msglogin"><?php echo $pesan;?></div>
<form target="_self" method="post" enctype="multipart/form-data" name="login" id="login">
<input type="text" name="username" id="username" placeholder="Username" required value="<?php echo isset($_COOKIE['username'])?$_COOKIE['username']:''; ?>">
<input type="password" name="password" id="password" placeholder="Password" required value="<?php echo isset($_COOKIE['password'])?$_COOKIE['username']:''; ?>"><br>
<input type="checkbox" name="remember" id="remember"> Remember Me<br>
<button type="submit"> Login </button>
</form>
</div>
</div>
</div>

<div class="register">
<button type="button" class="close" onClick="location.href='index.php'"> X </button>
<div class="content-login">
<div class="isi-login" id="top-login">
<div class="isi-tl"><img src="img/default.jpg" class="img-login"></div>
</div>
<div class="isi-login" id="bottom-login">
<form target="_self" method="post" enctype="multipart/form-data" name="login" id="login">
<input type="text" name="rnama" id="rnama" placeholder="Nama Lengkap" required>
<input type="text" name="rusername" id="rusername" placeholder="Username" required>
<input type="password" name="rpassword" id="rpassword" placeholder="Password" required onBlur="cekpass();">
<input type="password" name="rconfirm" id="rconfirm" placeholder="Confirm Password" required onBlur="cekpass();" onFocus="cekpass();">
<div id="msglogin"></div><br>
<input type="email" name="remail" id="remail" placeholder="Email (Example@email.com)" onFocus="cekpass();">
<label for="file"> Pilih FOto</label><br>
<input type="file" name="rfile" id="rfile" onChange="cekfile();" onBlur="cekfile();" onFocus="cekfile();">
Ukuran foto maximal 2 M.
<div id="msgfile"></div><br>
<button type="submit"> Register </button>
</form>
</div>
</div>
</div>


<header>
  <div class="header-atas">
    <div class="content-header"> 
    <div class="isi-ha">
    <img src="img/biglogo.png" class="big-logo">
    <button type="button" class="btn-login">Login</button>
    <button type="button" class="btn-register">Register</button>
    </div>
    </div>
  </div>
  <div class="header-tengah">
    <div class="content-header">
      <div class="isi-ht" id="ht-satu">
      <img src="img/logo.png" class="img-logo"><br>
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
      <img src="img/laptop.png" class="img-kategori">
      </div>
      <div class="isi-hb" id="hb-dua">
      <img src="img/otomotif.jpg" class="img-kategori">
      </div>
      <div class="isi-hb" id="hb-tiga">
            <img src="img/sport.png" class="img-kategori">
      </div>
      <div class="isi-hb" id="hb-empat">
            <img src="img/fashion.png" class="img-kategori">
      </div>
      <div class="isi-hb" id="hb-lima">
            <img src="img/c.png" class="img-kategori">
      </div>
      <div class="isi-hb" id="hb-enam">
            <img src="img/kamera.png" class="img-kategori">
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
        <div class="price"><?php echo"Rp.".number_format($ft['harga'],0,',','.'); ?></div>
        </div>
        <div class="isi-bawah">
        <?php echo $ft['nama_produk'];?> <br>
        <button type="button" class="btn-detail">Detail</button>
        <button type="button" class="btn-beli">Beli</button>
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
      <div class="isi-fa" id="fa-tiga">
      <div class="newslatter">
      <form target="_self">
      <input type="text" placeholder="Email (Example@email.com)">
      <input type="radio" value="laki-laki"> Laki - laki &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="perempuan"> Perempuan<br>
      <button type="submit">Submit</button>
      </form>
      </div>
      </div>
    </div>
  </div>
  <div class="footer-bawah">
    <div class="cf-bawah">
    <p>Copyright &copy; 2016 Nico Lahara </p>
    </div>
  </div>
</footer>

</body>
</html>
<?php
mysql_close($koneksi);
ob_flush();
?>