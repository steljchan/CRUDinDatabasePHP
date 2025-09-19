<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_birth";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());

$id = $_GET['id'] ?? 0;

$sql = "DELETE FROM user_data WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: tampil.php");
exit;
