<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {

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

    // upload foto
    $foto = "";
    if (!empty($_FILES['image_peg']['name'])) {
        $foto = time() . "_" . $_FILES['image_peg']['name'];
        move_uploaded_file($_FILES['image_peg']['tmp_name'], "upload/" . $foto);
    }

    $query = "INSERT INTO pegawai 
        (nama, stjab, nip, urajab, no_hp, email, bankrek, no_rek, alamat, pangkat, gol, INSTANSI, image_peg)
        VALUES
        ('$nama','$stjab','$nip','$urajab','$no_hp','$email','$bankrek','$no_rek','$alamat','$pangkat','$gol','$instansi','$foto')";

    mysqli_query($conn, $query);

    header("Location: list_pegawai.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pegawai</title>
    <link rel="stylesheet" href="css/list_pegawai.css">
    <style>
        .form-box {
            max-width: 700px;
            margin: auto;
        }
        .form-box label {
            display: block;
            margin-top: 10px;
            font-weight: 600;
        }
        .form-box input,
        .form-box textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .form-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="container form-box">
    <h2>Tambah Pegawai</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Nama</label>
        <input type="text" name="nama" required>

        <label>STJAB</label>
        <input type="text" name="stjab">

        <label>NIP</label>
        <input type="text" name="nip">

        <label>Uraian Jabatan</label>
        <input type="text" name="urajab">

        <label>No HP</label>
        <input type="text" name="no_hp">

        <label>Email</label>
        <input type="email" name="email">

        <label>Bank Rekening</label>
        <input type="text" name="bankrek">

        <label>No Rekening</label>
        <input type="text" name="no_rek">

        <label>Alamat</label>
        <textarea name="alamat"></textarea>

        <label>Pangkat (ASN)</label>
<select name="pangkat">
    <option value="">-- Pilih Pangkat --</option>
    <option value="Juru Muda">Juru Muda</option>
    <option value="Juru Muda Tingkat I">Juru Muda Tingkat I</option>
    <option value="Juru">Juru</option>
    <option value="Juru Tingkat I">Juru Tingkat I</option>
    <option value="Penata Muda">Penata Muda</option>
    <option value="Penata Muda Tingkat I">Penata Muda Tingkat I</option>
    <option value="Penata">Penata</option>
    <option value="Penata Tingkat I">Penata Tingkat I</option>
    <option value="Pembina">Pembina</option>
    <option value="Pembina Tingkat I">Pembina Tingkat I</option>
    <option value="Pembina Utama">Pembina Utama</option>
</select>

<label>Golongan</label>
<select name="gol">
    <option value="">-- Pilih Golongan --</option>
    <option value="I/a">I/a</option>
    <option value="I/b">I/b</option>
    <option value="I/c">I/c</option>
    <option value="I/d">I/d</option>
    <option value="II/a">II/a</option>
    <option value="II/b">II/b</option>
    <option value="II/c">II/c</option>
    <option value="II/d">II/d</option>
    <option value="III/a">III/a</option>
    <option value="III/b">III/b</option>
    <option value="III/c">III/c</option>
    <option value="III/d">III/d</option>
    <option value="IV/a">IV/a</option>
    <option value="IV/b">IV/b</option>
    <option value="IV/c">IV/c</option>
    <option value="IV/d">IV/d</option>
    <option value="IV/e">IV/e</option>
</select>


        <label>Instansi</label>
        <input type="text" name="INSTANSI">

        <label>Foto</label>
        <input type="file" name="image_peg">

        <div class="form-actions">
            <button type="submit" name="simpan" class="btn btn-edit">Simpan</button>
            <a href="list_pegawai.php" class="btn btn-hapus">Batal</a>
        </div>

    </form>
</div>

</body>
</html>
