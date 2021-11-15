<?php
session_start();
// bikin koneksi
$conn = mysqli_connect('localhost','root','','kasir');
// login
if(isset($_POST['login'])){
    //initiate variable
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = mysqli_query($conn, "select * from user where username='$username' and password='$password'");
    $hitung = mysqli_num_rows($check);

    if($hitung>0){
        //jika datanya ditemukan
        //berhasil login

        $_SESSION['login'] = 'True';
        header('location:index.php');
    }
    else {
        //data tidak ditemukan
        //gagal login
        echo '<script>alert("Username atau Password salah");
        window.location.href="login.php"</script>';
    }
}

if(isset($_POST['tambahbarang'])){
    $namaproduk = $_POST['namaproduk'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    $harga = $_POST['harga'];

    $insert = mysqli_query($conn, "insert into produk (namaproduk, deskripsi, harga, stock) values ('$namaproduk', '$deskripsi', '$harga', '$stock')");

    if($insert){
        header('location:stock.php');
    }
    else {
    echo '<script>alert("gagal menambah barang baru");
        window.location.href="stock.php"</script>';
    }
}

if(isset($_POST['tambahpelanggan'])){
    $namapelanggan = $_POST['namapelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];
    
    $insert = mysqli_query($conn, "insert into pelanggan (namapelanggan, notelp,alamat) values ('$namapelanggan', '$notelp', '$alamat')");

    if($insert){
        header('location:pelanggan.php');
    }
    else {
    echo '<script>alert("gagal menambah pelanggan baru");
        window.location.href="pelanggan.php"</script>';
    }
}

if(isset($_POST['tambahpesanan'])){
    $idpelanggan = $_POST['idpelanggan'];
    
    $insert = mysqli_query($conn, "insert into pesanan (idpelanggan) values ('$idpelanggan')");

    if($insert){
        header('location:index.php');
    }
    else {
    echo '<script>alert("gagal menambah pesanan baru");
        window.location.href="index.php"</script>';
    }
}

if(isset($_POST['addproduk'])){
    $idproduk = $_POST['idproduk'];
    $idp = $_POST['idp'];
    $qty = $_POST['qty'];
    
   $hitung1 = mysqli_query($conn, "select * from produk where idproduk='$idproduk'");
   $hitung2 = mysqli_fetch_array($hitung1);
   $stocksekarang = $hitung2['stock'];

   if($stocksekarang>=$qty){
       $selisih = $stocksekarang-$qty;
       $insert = mysqli_query($conn, "insert into detailpesanan (idpesanan,idproduk,qty) values ('$idp','$idproduk','$qty')");
       $update = mysqli_query($conn, "update produk set stock='$selisih' where idproduk='$idproduk'");
       if($insert&&$update){
           header('location:view.php?idp='.$idp);
        }  
        else {
        echo '<script>alert("gagal menambah pesanan baru");
            window.location.href="view.php?idp='.$idp.'"</script>';
        }
}
    else {
        echo '<script>alert("stock barang tidak cukup");
        window.location.href="view.php?idp='.$idp.'"</script>';
    }
}

    
if(isset($_POST['barangmasuk'])){
    $idproduk = $_POST['idproduk'];
    $qty = $_POST['qty'];

    $insertb = mysqli_query($conn, "insert into masuk (idproduk,qty) values('$idproduk','$qty')");

    if($insertb){
        header('location:masuk.php');
    }
    else {
        echo '<script>alert("gagal");
            window.location.href="masuk.php"</script>';
    }
}

if(isset($_POST['hapusprodukpesanan'])){
    $idp = $_POST['idp'];
    $idpr = $_POST['idpr'];
    $idorder = $_POST['idorder'];

    $cek1 = mysqli_query($conn, "select * from detailpesanan where iddetailpesanan='$idp'");
    $cek2 = mysqli_fetch_array($cek1);
    $qtysekarang = $cek2['qty'];

    $cek3 = mysqli_query($conn, "select * from produk where idproduk='$idpr'");
    $cek4 = mysqli_fetch_array($cek3);
    $stocksekarang = $cek4['stock'];

    $hitung = $stocksekarang+$qtysekarang;

    $update = mysqli_query($conn, "update produk set stock='$hitung' where idproduk='$idpr'");
    $hapus = mysqli_query($conn, "delete from detailpesanan where idproduk='$idpr' and iddetailpesanan='$idp'");

    if($update&&$hapus){
        header('location:view.php?idp='.$idorder);  
    }
    else {
        echo '<script>alert("gagal menghapus barang");
        window.location.href="view.php?idp='.$idorder.'"</script>';
    }

}

if(isset($_POST['editbarang'])){
    $np = $_POST['namaproduk'];
    $desc = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $idp = $_POST['idp'];

    $query = mysqli_query($conn, "update produk set namaproduk='$np', deskripsi='$desc', harga='$harga' where idproduk='$idp'");

    if($query){
        header('location:stock.php');
    }
    else {
        echo '<script>alert("gagal");
        window.location.href="stock.php"</script>';
    }
}
?>