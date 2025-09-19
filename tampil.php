<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_birth";

// Koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query ambil data
$sql = "SELECT id, nama, tanggal_lahir, hobi, foto FROM user_data ORDER BY nama";
$result = mysqli_query($conn, $sql);

$users = [];
if ($result && mysqli_num_rows($result) > 0) {
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
mysqli_close($conn);

function e($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$picsFolder = 'pics/';
$defaultPhoto = $picsFolder . 'default-avatar.png';
?>

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Data Siswa</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    :root{
        --pink-light: #ffe6ee;
        --pink: #ff9bb5;
        --pink-dark: #e86c8c;
        --muted: #6b6b6b;
        --card-shadow: 0 6px 18px rgba(255,125,190,0.12);
    }
    body{
        font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, Arial;
        background: var(--pink-light);
        margin:0;
        padding:24px;
        color:#222;
    }
    h1{
        margin:0 0 16px 0;
        font-size:28px;
        color:var(--pink-dark);
        text-align:center;
        font-weight:700;
    }
    .btn{
        display:inline-block;
        padding:10px 16px;
        border-radius:10px;
        text-decoration:none;
        font-size:14px;
        font-weight:600;
        margin:4px;
        transition:.2s;
    }
    .btn-add{ background:var(--pink); color:#fff; box-shadow:0 4px 10px rgba(255,125,190,.3);}
    .btn-edit{ background:#ff91a4; color:#fff; }
    .btn-del{ background:#ff6f91; color:#fff; }
    .btn:hover{ transform:translateY(-2px); opacity:.9; }

    .container{
        max-width:1200px;
        margin:0 auto;
        display:grid;
        grid-template-columns: 1fr 400px;
        gap:24px;
        align-items:start;
    }
    .cards{
        display:grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap:16px;
    }
    .card{
        background: #fff;
        border-radius:14px;
        padding:14px;
        box-shadow: var(--card-shadow);
        cursor:pointer;
        display:flex;
        flex-direction:column;
        align-items:center;
        border:2px solid var(--pink-light);
        transition: all .2s ease;
        text-align:center;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 22px rgba(255,125,190,0.18);
        border-color: var(--pink);
    }
    .avatar-small{
        width:100px;
        height:100px;
        border-radius:12px;
        object-fit:cover;
        border:3px solid var(--pink);
        background: #fff;
        margin-bottom:8px;
    }
    .card .name{ font-weight:700; color:#2b1430; margin:6px 0; }
    .card .sub{ font-size:13px; color:var(--muted); margin-bottom:8px; }
    .card .actions{ display:flex; gap:6px; flex-wrap:wrap; justify-content:center; }

    .detail{
        background:#fff;
        border-radius:16px;
        padding:20px;
        box-shadow: var(--card-shadow);
        border:2px solid var(--pink-light);
        min-height:260px;
    }
    .detail .photo{
        width:180px;
        height:180px;
        border-radius:14px;
        object-fit:cover;
        border:4px solid var(--pink);
        display:block;
        margin:0 auto 14px;
    }
    .detail h2{ margin:0 0 8px 0; color:#3a1324; text-align:center; }
    .detail .role{ color: var(--pink-dark); font-weight:600; margin-bottom:14px; text-align:center; }
    .info-row{ margin-bottom:10px; color:#444; font-size:15px; }
    .muted { color:var(--muted); font-size:13px; }
    .empty{
        text-align:center;
        padding:40px 16px;
        color:var(--muted);
        border-radius:14px;
        background:#fff;
        border:2px dashed var(--pink);
        font-size:15px;
    }
    @media (max-width:980px) {
        .container{ grid-template-columns: 1fr; }
        .detail{ order: -1; margin-bottom:14px; }
    }
</style>
</head>
<body>
<h1>📚 Data Siswa</h1>
<div style="text-align:center; margin-bottom:20px;">
    <a href="tambah.php" class="btn btn-add">+ Tambah Siswa</a>
</div>

<div class="container">
    <div>
        <?php if (count($users) === 0): ?>
            <div class="empty">Belum ada data siswa.</div>
        <?php else: ?>
            <div class="cards" id="cards">
                <?php foreach ($users as $u): 
                    $photoPath = $picsFolder . ($u['foto'] ? $u['foto'] : '');
                    if (!file_exists($photoPath) || empty($u['foto'])) {
                        $photoPath = $defaultPhoto;
                    }
                    $data = [
                        'id' => $u['id'],
                        'nama' => $u['nama'],
                        'tanggal_lahir' => $u['tanggal_lahir'],
                        'hobi' => $u['hobi'],
                        'foto' => $photoPath,
                    ];
                    $dataAttr = e(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                ?>
                <div class="card" data-user='<?php echo $dataAttr; ?>'>
                    <img class="avatar-small" src="<?php echo e($photoPath); ?>" alt="avatar <?php echo e($u['nama']); ?>">
                    <div class="name"><?php echo e($u['nama']); ?></div>
                    <div class="sub"><?php echo e($u['tanggal_lahir']); ?></div>
                    <div class="actions">
                        <a href="edit.php?id=<?php echo e($u['id']); ?>" class="btn btn-edit">Edit</a>
                        <a href="hapus.php?id=<?php echo e($u['id']); ?>" class="btn btn-del" onclick="return confirm('Yakin hapus data?')">Hapus</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <aside class="detail" id="detailPane">
        <?php if (count($users) === 0): ?>
            <div class="empty">Tidak ada siswa untuk ditampilkan.</div>
        <?php else: 
            $first = $users[0];
            $firstPhoto = $picsFolder . ($first['foto'] ? $first['foto'] : '');
            if (!file_exists($firstPhoto) || empty($first['foto'])) $firstPhoto = $defaultPhoto;
        ?>
        <img id="detailPhoto" class="photo" src="<?php echo e($firstPhoto); ?>" alt="Foto siswa">
        <h2 id="detailNama"><?php echo e($first['nama']); ?></h2>
        <div class="role">Identitas Siswa</div>
        <div class="info-row"><strong>📅 Tanggal Lahir:</strong> <span id="detailTanggal"><?php echo e($first['tanggal_lahir']); ?></span></div>
        <div class="info-row"><strong>🎨 Hobi:</strong> <span id="detailHobi"><?php echo e($first['hobi']); ?></span></div>
        <?php endif; ?>
    </aside>
</div>

<script>
(function(){
    const cardsContainer = document.getElementById('cards');
    if (!cardsContainer) return;
    const detailPhoto = document.getElementById('detailPhoto');
    const detailNama = document.getElementById('detailNama');
    const detailTanggal = document.getElementById('detailTanggal');
    const detailHobi = document.getElementById('detailHobi');

    function showUser(data){
        if (!data) return;
        if (detailPhoto) detailPhoto.src = data.foto;
        if (detailNama) detailNama.textContent = data.nama || '-';
        if (detailTanggal) detailTanggal.textContent = data.tanggal_lahir || '-';
        if (detailHobi) detailHobi.textContent = data.hobi || '-';
    }

    cardsContainer.addEventListener('click', function(e){
        const card = e.target.closest('.card');
        if (!card) return;
        const json = card.getAttribute('data-user');
        if (!json) return;
        try {
            const data = JSON.parse(json);
            showUser(data);
        } catch (err) {
            console.error('Invalid user data JSON', err);
        }
    });
})();
</script>
</body>
</html>

