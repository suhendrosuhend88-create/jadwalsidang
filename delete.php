<?php
session_start(); if(!isset($_SESSION['admin'])) die("Akses ditolak.");
require "config.php";
$id=(int)($_GET['id']??0);
$pdo->prepare("DELETE FROM schedules WHERE id=?")->execute([$id]);
header("Location: index.php"); exit;
?>