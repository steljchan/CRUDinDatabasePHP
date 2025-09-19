<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_birth";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());

$id = $_GET['id'] ?? 0;

// Ambil data lama
$sql = "SELECT * FROM user_data WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("Data tidak ditemukan");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $tanggal = $_POST['tanggal_lahir'] ?? '';
    $hobi = $_POST['hobi'] ?? '';
    $fotoName = $user['foto'];

    if (!empty($_FILES['foto']['name'])) {
        $fotoName = time() . "_" . basename($_FILES['foto']['name']);
        $target = "pics/" . $fotoName;
        move_uploaded_file($_FILES['foto']['tmp_name'], $target);
    }

    $sql = "UPDATE user_data SET nama=?, tanggal_lahir=?, hobi=?, foto=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $nama, $tanggal, $hobi, $fotoName, $id);
    mysqli_stmt_execute($stmt);

    header("Location: tampil.php");
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Siswa</title>
<style>
    body {
        font-family: system-ui;
        background: #fffafc;
        padding: 30px;
        color: #333;
    }
    h1 {
        color: #b03b6a;
        text-align: center;
    }
    form {
        max-width: 420px;
        margin: 20px auto;
        padding: 20px;
        background: #fff0f6;
        border: 1px solid #ffc1d6;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(255,145,164,0.15);
    }
    label {
        display: block;
        margin-top: 12px;
        font-weight: 600;
        color: #b03b6a;
    }
    input, textarea {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        border: 1px solid #ffc1d6;
        border-radius: 8px;
        background: #fff;
        font-size: 14px;
    }
    .btn {
        display: inline-block;
        margin-top: 16px;
        padding: 10px 16px;
        background: #ff91a4;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s ease-in-out;
    }
    .btn:hover {
        background: #ff7d90;
    }
    .back {
        display: inline-block;
        margin-top: 16px;
        text-decoration: none;
        color: #b03b6a;
        font-weight: 600;
    }
    .back:hover {
        text-decoration: underline;
    }
    img {
        margin-top: 8px;
        border-radius: 8px;
        border: 1px solid #ffc1d6;
    }
</style>
</head>
<body>
<h1>Edit Siswa</h1>
<form method="post" enctype="multipart/form-data">
    <label>Nama</label>
    <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
    
    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" value="<?php echo htmlspecialchars($user['tanggal_lahir']); ?>" required>
    
    <label>Hobi</label>
    <textarea name="hobi"><?php echo htmlspecialchars($user['hobi']); ?></textarea>
    
    <label>Foto</label>
    <input type="file" name="foto" accept="image/*">
    
    <?php if ($user['foto']): ?>
        <p>Foto sekarang:</p>
        <img src="pics/<?php echo htmlspecialchars($user['foto']); ?>" width="120">
    <?php endif; ?>
    
    <button type="submit" class="btn">Update</button>
    <a href="tampil.php" class="back">Kembali</a>
</form>
</body>
</html>
