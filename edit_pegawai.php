<?php
include 'koneksi.php';

// ambil id dari URL
$id = $_GET['id_peg'];

// ambil data lama
$query = "SELECT * FROM pegawai WHERE id_peg = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// jika tombol simpan ditekan
if (isset($_POST['update'])) {

    $nama     = $_POST['nama'];
    $stjab    = $_POST['stjab'];
    $nip      = $_POST['nip'];
    $urajab   = $_POST['urajab'];
    $no_hp    = $_POST['no_hp'];
    $email    = $_POST['email'];
    $bankrek  = $_POST['bankrek'];
    $no_rek   = $_POST['no_rek'];
    $alamat   = $_POST['alamat'];
    $pangkat  = $_POST['pangkat'];
    $gol      = $_POST['gol'];
    $instansi = $_POST['INSTANSI'];

    // upload foto (opsional)
    if (!empty($_FILES['image_peg']['name'])) {
        $foto = $_FILES['image_peg']['name'];
        $tmp  = $_FILES['image_peg']['tmp_name'];
        move_uploaded_file($tmp, "upload/" . $foto);

        $update = "UPDATE pegawai SET
            nama='$nama', stjab='$stjab', nip='$nip', urajab='$urajab',
            no_hp='$no_hp', email='$email', bankrek='$bankrek',
            no_rek='$no_rek', alamat='$alamat', pangkat='$pangkat',
            gol='$gol', INSTANSI='$instansi', image_peg='$foto'
            WHERE id_peg='$id'";
    } else {
        $update = "UPDATE pegawai SET
            nama='$nama', stjab='$stjab', nip='$nip', urajab='$urajab',
            no_hp='$no_hp', email='$email', bankrek='$bankrek',
            no_rek='$no_rek', alamat='$alamat', pangkat='$pangkat',
            gol='$gol', INSTANSI='$instansi'
            WHERE id_peg='$id'";
    }

    mysqli_query($conn, $update);
    header("Location: list_pegawai.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pegawai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            width: 600px;
            margin: auto;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 6px;
        }
        button {
            margin-top: 15px;
            padding: 8px 15px;
        }
        img {
            margin-top: 5px;
            width: 80px;
        }
    </style>
</head>
<body>

<h2 align="center">Edit Data Pegawai</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Nama</label>
    <input type="text" name="nama" value="<?= $data['nama']; ?>" required>

    <label>STJAB</label>
    <input type="text" name="stsjab" value="<?= $data['stsjab']; ?>">

    <label>NIP</label>
    <input type="text" name="nip" value="<?= $data['nip']; ?>">

    <label>Uraian Jabatan</label>
    <input type="text" name="uraijab" value="<?= $data['uraijab']; ?>">

    <label>No HP</label>
    <input type="text" name="no_hp" value="<?= $data['no_hp']; ?>">

    <label>Email</label>
    <input type="email" name="email" value="<?= $data['email']; ?>">

    <label>Bank Rekening</label>
    <input type="text" name="bankrek" value="<?= $data['bankrek']; ?>">

    <label>No Rekening</label>
    <input type="text" name="no_rek" value="<?= $data['no_rek']; ?>">

    <label>Alamat</label>
    <textarea name="alamat"><?= $data['alamat']; ?></textarea>

    <label>Pangkat (ASN)</label>
<select name="pangkat">
    <option value="">-- Pilih Pangkat --</option>
    <?php
    $listPangkat = [
        "Juru Muda",
        "Juru Muda Tingkat I",
        "Juru",
        "Juru Tingkat I",
        "Penata Muda",
        "Penata Muda Tingkat I",
        "Penata",
        "Penata Tingkat I",
        "Pembina",
        "Pembina Tingkat I",
        "Pembina Utama"
    ];

    foreach ($listPangkat as $p) {
        $selected = ($data['pangkat'] == $p) ? 'selected' : '';
        echo "<option value='$p' $selected>$p</option>";
    }
    ?>
</select>


    <label>Golongan</label>
<select name="gol">
    <option value="">-- Pilih Golongan --</option>
    <?php
    $listGol = [
        "I/a","I/b","I/c","I/d",
        "II/a","II/b","II/c","II/d",
        "III/a","III/b","III/c","III/d",
        "IV/a","IV/b","IV/c","IV/d","IV/e"
    ];

    foreach ($listGol as $g) {
        $selected = ($data['gol'] == $g) ? 'selected' : '';
        echo "<option value='$g' $selected>$g</option>";
    }
    ?>
</select>


    <label>Instansi</label>
    <input type="text" name="INSTANSI" value="<?= $data['INSTANSI']; ?>">

    <label>Foto</label>
    <input type="file" name="image_peg">
    <?php if (!empty($data['image_peg'])) { ?>
        <img src="upload/<?= $data['image_peg']; ?>">
    <?php } ?>

    <button type="submit" name="update">Simpan Perubahan</button>
    <a href="list_pegawai.php">Kembali</a>

</form>

</body>
</html>
