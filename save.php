<?php
session_start(); if(!isset($_SESSION['admin'])) die("Akses ditolak.");
require "config.php";
$id = trim($_POST['id'] ?? '');
$data = [
 trim($_POST['nama']??''), trim($_POST['nim']??''), trim($_POST['prodi']??''),
 trim($_POST['judul']??''), $_POST['tanggal']??'', $_POST['waktu']??'',
 trim($_POST['ruangan']??''), trim($_POST['ketua_penguji']??''),
 trim($_POST['penguji_2']??''), trim($_POST['pembimbing']??''), $_POST['jenis_sidang']??'Sidang Skripsi'
];
if($id){
 $sql="UPDATE schedules SET nama=?,nim=?,prodi=?,judul=?,tanggal=?,waktu=?,ruangan=?,ketua_penguji=?,penguji_2=?,pembimbing=?,jenis_sidang=? WHERE id=?";
 $data[]=(int)$id;
}else{
 $sql="INSERT INTO schedules (nama,nim,prodi,judul,tanggal,waktu,ruangan,ketua_penguji,penguji_2,pembimbing,jenis_sidang) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
}
$pdo->prepare($sql)->execute($data);
header("Location: index.php"); exit;
?>