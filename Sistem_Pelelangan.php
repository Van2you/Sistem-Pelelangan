<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pelelangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 15px;
            margin: 40px;
            background-color: #f3f6fa;
            color: #333;
        }

        h3 {
            color: #007BFF;
            margin-bottom: 15px;
        }

        form {
            background: #fff;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            max-width: 400px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input[type="submit"] {
            background: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }

        #clock {
            font-weight: bold;
            color: #007BFF;
        }

        .result {
            background: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-top: 20px;
            max-width: 400px;
        }

        .success { color: green; font-weight: bold; }
        .danger { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h3>Sistem Pelelangan</h3>

<form method="post">
    Masukkan Nama: 
    <input type="text" name="nama" required><br>
    Masukkan Jumlah Tawaran: 
    <input type="number" name="nilai" required><br>
    <input type="submit" value="Kirim Tawaran">
</form>

<hr>

<?php
$harga_awal = 250000;
$batas_waktu = "2025-10-16 23:00:00";
$waktu_sekarang = date("Y-m-d H:i:s");

if (!isset($_SESSION['pemenang'])) {
    $_SESSION['pemenang'] = "Belum ada";
    $_SESSION['tawaran_tertinggi'] = $harga_awal;
}

echo "<div class='result'>";
echo "<p>💰 Harga awal barang: <b>Rp " . number_format($harga_awal, 0, ',', '.') . "</b></p>";
echo "<p>⏰ Batas waktu lelang: <b>$batas_waktu</b></p>";
echo "<p>🕒 Waktu sekarang: <span id='clock'></span></p>";
echo "</div>";


if (isset($_POST['nama']) && isset($_POST['nilai'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $tawaran_baru = (int)$_POST['nilai'];

    echo "<div class='result'>";
    if ($waktu_sekarang <= $batas_waktu) {
        echo "<p><b>Lelang masih berlangsung...</b></p>";

        if ($tawaran_baru > $_SESSION['tawaran_tertinggi']) {
            $_SESSION['tawaran_tertinggi'] = $tawaran_baru;
            $_SESSION['pemenang'] = $nama;
            echo "<p class='success'>✅ Tawaran Anda diterima! Anda sekarang pemenang sementara.</p>";
        } else {
            echo "<p class='danger'>❌ Tawaran Anda lebih rendah dari tawaran tertinggi saat ini.</p>";
        }

        echo "<p>Tawaran Anda: <b>Rp " . number_format($tawaran_baru, 0, ',', '.') . "</b></p>";
    } else {
        echo "<p class='danger'>⏰ Batas waktu lelang telah berakhir. Tidak bisa menawar lagi.</p>";
    }
    echo "</div>";
}

echo "<div class='result'>";
if ($waktu_sekarang <= $batas_waktu) {
    echo "<p>🏆 Pemenang sementara: <b>" . $_SESSION['pemenang'] . "</b> dengan tawaran <b>Rp " . number_format($_SESSION['tawaran_tertinggi'], 0, ',', '.') . "</b></p>";
} else {
    echo "<p>🏁 <b>Lelang Selesai!</b></p>";
    echo "<p>🎉 Pemenang akhir: <b>" . $_SESSION['pemenang'] . "</b> dengan tawaran <b>Rp " . number_format($_SESSION['tawaran_tertinggi'], 0, ',', '.') . "</b></p>";
}
echo "</div>";
?>

<script>
// Fungsi waktu real-time
function updateClock() {
    const now = new Date();
    const tahun = now.getFullYear();
    const bulan = String(now.getMonth() + 1).padStart(2, '0');
    const tanggal = String(now.getDate()).padStart(2, '0');
    const jam = String(now.getHours()).padStart(2, '0');
    const menit = String(now.getMinutes()).padStart(2, '0');
    const detik = String(now.getSeconds()).padStart(2, '0');
    document.getElementById("clock").innerText = `${tahun}-${bulan}-${tanggal} ${jam}:${menit}:${detik}`;
}
updateClock();
setInterval(updateClock, 1000);
</script>

</body>
</html>
