<?php
session_start();
require_once "config.php";

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $stmt->execute([$username]);
    $u = $stmt->fetch();
    if ($u && password_verify($password, $u['password'])) {
        $_SESSION['admin'] = ['id'=>$u['id'], 'username'=>$u['username']];
        header("Location: index.php");
        exit;
    }
    $error = "Username atau password salah.";
}

$isAdmin = isset($_SESSION['admin']);
$stmt = $pdo->query("SELECT * FROM schedules ORDER BY tanggal ASC, waktu ASC");
$schedules = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sistem Informasi Jadwal Sidang - Institut Kesehatan Helvetia</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="bg-slate-100 text-slate-800">
<header class="bg-blue-900 text-white shadow-lg">
  <div class="max-w-7xl mx-auto px-4 py-4 flex flex-wrap gap-3 justify-between items-center">
    <div>
      <h1 class="text-xl md:text-2xl font-bold">Institut Kesehatan Helvetia</h1>
      <p class="text-blue-200 text-sm">Sistem Informasi Jadwal Sidang</p>
    </div>
    <div class="flex gap-2">
      <button onclick="openFullscreen()" class="bg-blue-700 hover:bg-blue-600 px-4 py-2 rounded-lg"><i class="fa-solid fa-expand"></i> Fullscreen</button>
      <?php if ($isAdmin): ?>
        <a href="#form" class="bg-emerald-600 hover:bg-emerald-500 px-4 py-2 rounded-lg">Tambah Jadwal</a>
        <a href="?logout=1" class="bg-red-600 hover:bg-red-500 px-4 py-2 rounded-lg">Keluar</a>
      <?php else: ?>
        <button onclick="document.getElementById('login').showModal()" class="bg-white text-blue-900 px-4 py-2 rounded-lg">Login Admin</button>
      <?php endif; ?>
    </div>
  </div>
</header>

<main id="app" class="max-w-7xl mx-auto p-4 md:p-6">
  <div class="bg-white rounded-2xl shadow p-4 mb-5">
    <div class="grid md:grid-cols-4 gap-3">
      <input id="search" oninput="filterRows()" placeholder="Cari nama/NIM/judul..." class="border rounded-lg px-3 py-2 md:col-span-2">
      <select id="filterProdi" onchange="filterRows()" class="border rounded-lg px-3 py-2">
        <option value="">Semua Program Studi</option>
        <?php
        $prodis = $pdo->query("SELECT DISTINCT prodi FROM schedules ORDER BY prodi")->fetchAll();
        foreach ($prodis as $p) echo '<option>'.htmlspecialchars($p['prodi']).'</option>';
        ?>
      </select>
      <input id="filterDate" type="date" onchange="filterRows()" class="border rounded-lg px-3 py-2">
    </div>
  </div>

  <?php if ($isAdmin): ?>
  <section id="form" class="bg-white rounded-2xl shadow p-5 mb-5">
    <h2 class="text-lg font-bold mb-4"><i class="fa-solid fa-calendar-plus text-blue-700"></i> Tambah Jadwal Sidang</h2>
    <form method="post" action="save.php" class="grid md:grid-cols-2 gap-4">
      <input type="hidden" name="id" value="">
      <div><label class="text-sm">Nama Mahasiswa</label><input required name="nama" class="w-full border rounded-lg px-3 py-2"></div>
      <div><label class="text-sm">NIM</label><input required name="nim" class="w-full border rounded-lg px-3 py-2"></div>
      <div><label class="text-sm">Program Studi</label><input required name="prodi" placeholder="Contoh: S1 Kedokteran" class="w-full border rounded-lg px-3 py-2"></div>
      <div><label class="text-sm">Ruangan</label><input required name="ruangan" class="w-full border rounded-lg px-3 py-2"></div>
      <div><label class="text-sm">Tanggal</label><input required type="date" name="tanggal" class="w-full border rounded-lg px-3 py-2"></div>
      <div><label class="text-sm">Waktu</label><input required type="time" name="waktu" class="w-full border rounded-lg px-3 py-2"></div>
      <div><label class="text-sm">Ketua Penguji</label><input required name="ketua_penguji" class="w-full border rounded-lg px-3 py-2"></div>
      <div><label class="text-sm">Penguji 2</label><input name="penguji_2" class="w-full border rounded-lg px-3 py-2"></div>
      <div><label class="text-sm">Pembimbing</label><input name="pembimbing" class="w-full border rounded-lg px-3 py-2"></div>
      <div><label class="text-sm">Jenis Sidang</label>
        <select name="jenis_sidang" class="w-full border rounded-lg px-3 py-2">
          <option>Seminar Proposal</option><option>Sidang Skripsi</option><option>Sidang KTI</option><option>Ujian Komprehensif</option>
        </select>
      </div>
      <div class="md:col-span-2"><label class="text-sm">Judul</label><textarea required name="judul" rows="2" class="w-full border rounded-lg px-3 py-2"></textarea></div>
      <button class="md:col-span-2 bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg">Simpan Jadwal</button>
    </form>
  </section>
  <?php endif; ?>

  <section id="schedule-section" class="bg-white rounded-2xl shadow p-4 md:p-6">
    <div class="flex justify-between items-center mb-4">
      <div>
        <h2 class="text-xl font-bold">Jadwal Sidang</h2>
        <p class="text-sm text-slate-500">Institut Kesehatan Helvetia</p>
      </div>
      <span class="text-sm bg-blue-50 text-blue-800 px-3 py-1 rounded-full"><?=count($schedules)?> jadwal</span>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm" id="scheduleTable">
        <thead class="bg-blue-900 text-white">
          <tr><th class="p-3 text-left">Tanggal/Waktu</th><th class="p-3 text-left">Mahasiswa</th><th class="p-3 text-left">Judul</th><th class="p-3 text-left">Prodi</th><th class="p-3 text-left">Ruangan</th><th class="p-3 text-left">Penguji/Pembimbing</th><?php if($isAdmin) echo '<th class="p-3">Aksi</th>'; ?></tr>
        </thead>
        <tbody>
        <?php foreach($schedules as $s): ?>
          <tr class="schedule-row border-b hover:bg-slate-50" data-search="<?=htmlspecialchars(strtolower($s['nama'].' '.$s['nim'].' '.$s['judul'].' '.$s['prodi']))?>" data-prodi="<?=htmlspecialchars($s['prodi'])?>" data-date="<?=htmlspecialchars($s['tanggal'])?>">
            <td class="p-3 whitespace-nowrap"><b><?=date('d/m/Y',strtotime($s['tanggal']))?></b><br><span class="text-blue-700"><?=$s['waktu']?> WIB</span><br><span class="text-xs"><?=$s['jenis_sidang']?></span></td>
            <td class="p-3"><b><?=htmlspecialchars($s['nama'])?></b><br><span class="text-xs text-slate-500">NIM: <?=htmlspecialchars($s['nim'])?></span></td>
            <td class="p-3 min-w-[240px]"><?=htmlspecialchars($s['judul'])?></td>
            <td class="p-3"><?=htmlspecialchars($s['prodi'])?></td>
            <td class="p-3"><?=htmlspecialchars($s['ruangan'])?></td>
            <td class="p-3 min-w-[200px]">Ketua: <?=htmlspecialchars($s['ketua_penguji'])?><br>Penguji 2: <?=htmlspecialchars($s['penguji_2'])?><br>Pembimbing: <?=htmlspecialchars($s['pembimbing'])?></td>
            <?php if($isAdmin): ?><td class="p-3 text-center"><a onclick="return confirm('Hapus jadwal ini?')" href="delete.php?id=<?=$s['id']?>" class="bg-red-100 text-red-700 px-3 py-1 rounded">Hapus</a></td><?php endif; ?>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <div id="empty" class="hidden text-center p-8 text-slate-500">Jadwal tidak ditemukan.</div>
    </div>
  </section>
</main>

<dialog id="login" class="rounded-2xl p-0 shadow-2xl backdrop:bg-slate-900/60">
  <form method="post" class="p-6 w-[320px]">
    <h3 class="text-xl font-bold mb-4">Login Admin</h3>
    <?php if($error): ?><div class="bg-red-50 text-red-700 p-2 rounded mb-3"><?=$error?></div><?php endif; ?>
    <input name="username" placeholder="Username" required class="w-full border rounded-lg px-3 py-2 mb-3">
    <input name="password" type="password" placeholder="Password" required class="w-full border rounded-lg px-3 py-2 mb-4">
    <div class="flex gap-2"><button name="login" class="flex-1 bg-blue-700 text-white rounded-lg py-2">Masuk</button><button type="button" onclick="login.close()" class="px-4 border rounded-lg">Batal</button></div>
    <p class="text-xs text-slate-500 mt-3">Akun awal: admin / admin123</p>
  </form>
</dialog>

<script>
function filterRows(){
  const q=document.getElementById('search').value.toLowerCase(), p=document.getElementById('filterProdi').value, d=document.getElementById('filterDate').value;
  let shown=0;
  document.querySelectorAll('.schedule-row').forEach(r=>{
    const ok=r.dataset.search.includes(q)&&(!p||r.dataset.prodi===p)&&(!d||r.dataset.date===d);
    r.style.display=ok?'':'none'; if(ok) shown++;
  });
  document.getElementById('empty').classList.toggle('hidden',shown!==0);
}
function openFullscreen(){
  const e=document.getElementById('schedule-section');
  if(!document.fullscreenElement) e.requestFullscreen?.(); else document.exitFullscreen?.();
}
</script>
</body></html>