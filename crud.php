<!DOCTYPE html>
<html>
<head>
    <title>Mahasiswa</title>
</head>
<body>
    <?php
    $host       = "localhost";
    $user       = "root";
    $pass       = "";
    $db         = "pwti5";

    $koneksi    = mysqli_connect($host, $user, $pass, $db);
    if (!$koneksi) {
        die("Tidak bisa terkoneksi");
    }
    // --- Tambah data
    function tambah($koneksi)
    {

        if (isset($_POST['btn_simpan'])) {
            $id = time();
            $nama = $_POST['nama'];
            $fakultas = $_POST['fakultas'];
            $alamat = $_POST['alamat'];

            if (!empty($nama) && !empty($fakultas) && !empty($alamat)) {
                $sql = "INSERT INTO  mahasiswa(id,nama, fakultas, alamat) VALUES(" . $id . ",'" . $nama . "','" . $fakultas . "','" . $alamat  . "')";
                $simpan = mysqli_query($koneksi, $sql);
                if ($simpan && isset($_GET['aksi'])) {
                    if ($_GET['aksi'] == 'create') {
                        header('location: crud.php');
                    }
                }
            } else {
                $pesan = "Tidak dapat menyimpan, data belum lengkap!";
            }
        }
    ?>
        <form action="" method="POST">
            <fieldset>
                <legend>
                    <h2>Tambah data</h2>
                </legend>
                <label>Nama <input type="text" name="nama" /></label> <br>
                <label>Fakultas <input type="text" name="fakultas" /></label><br>
                <label>Alamat <input type="text" name="alamat" /></label> <br>
                <br>
                <label>
                    <input type="submit" name="btn_simpan" value="Simpan" />
                    <input type="reset" name="reset" value="Hapus" />
                </label>
                <br>
                <p><?php echo isset($pesan) ? $pesan : "" ?></p>
            </fieldset>
        </form>
        <?php
    }

    // --- Lihat Data
    function tampil_data($koneksi)
    {
        $sql = "SELECT * FROM mahasiswa";
        $query = mysqli_query($koneksi, $sql);

        echo "<fieldset>";
        echo "<legend><h2>Data Mahasiswa</h2></legend>";

        echo "<table border='1' cellpadding='10'>";
        echo "<tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Fakultas</th>
            <th>Alamat</th>
            <th>Action</th>
          </tr>";

        while ($data = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?php echo $data['id']; ?></td>
                <td><?php echo $data['nama']; ?></td>
                <td><?php echo $data['fakultas']; ?></td>
                <td><?php echo $data['alamat']; ?></td>
                <td>
                    <a href="crud.php?aksi=update&id=<?php echo $data['id']; ?>&nama=<?php echo $data['nama']; ?>&fakultas=<?php echo $data['fakultas']; ?>&alamat=<?php echo $data['alamat']; ?>">Ubah</a> |
                    <a href="crud.php?aksi=delete&id=<?php echo $data['id']; ?>">Hapus</a>
                </td>
            </tr>
        <?php
        }
        echo "</table>";
        echo "</fieldset>";
    }

    // --- Update data
    function ubah($koneksi)
    {
        // ubah data
        if (isset($_POST['btn_ubah'])) {
            $id = $_POST['id'];
            $nama = $_POST['nama'];
            $fakultas = $_POST['fakultas'];
            $alamat = $_POST['alamat'];

            if (!empty($nama) && !empty($fakultas) && !empty($alamat)) {
                $perubahan = "nama_mahasiswa='" . $nama . "',fakultas_mahasiswa=" . $fakultas . ",alamat_mahasiswa=" . $alamat ."'";
                $sql_update = "UPDATE mahasiswa SET " . $perubahan . " WHERE id=$id";
                $update = mysqli_query($koneksi, $sql_update);
                if ($update && isset($_GET['aksi'])) {
                    if ($_GET['aksi'] == 'update') {
                        header('location: crud.php');
                    }
                }
            } else {
                $pesan = "Data tidak lengkap!";
            }
        }

        // tampilkan form ubah
        if (isset($_GET['id'])) {
        ?>
            <a href="crud.php"> &laquo; Home</a> |
            <a href="crud.php?aksi=create"> (+) Tambah Data</a>
            <hr>

            <form action="" method="POST">
                <fieldset>
                    <legend>
                        <h2>Ubah data</h2>
                    </legend>
                    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
                    <label>Nama <input type="text" name="nama" value="<?php echo $_GET['nama'] ?>" /></label> <br>
                    <label>Fakultas<input type="text" name="fakultas" value="<?php echo $_GET['fakultas'] ?>" /></label><br>
                    <label>Alamat <input type="text" name="alamat" value="<?php echo $_GET['alamat'] ?>" /></label> <br>
                    <br>
                    <label>
                        <input type="submit" name="btn_ubah" value="Simpan Perubahan" /> atau <a href="crud.php?aksi=delete&id=<?php echo $_GET['id'] ?>"> (x) Hapus data ini</a>!
                    </label>
                    <br>
                    <p><?php echo isset($pesan) ? $pesan : "Data telah diubah" ?></p>

                </fieldset>
            </form>
    <?php
        }
    }

    // --- Fungsi Delete
    function hapus($koneksi)
    {
        if (isset($_GET['id']) && isset($_GET['aksi'])) {
            $id = $_GET['id'];
            $sql_hapus = "DELETE FROM pelajar WHERE id=" . $id;
            $hapus = mysqli_query($koneksi, $sql_hapus);

            if ($hapus) {
                if ($_GET['aksi'] == 'delete') {
                    header('location: crud.php');
                }
            }
        }
    }

    // ===================================================================
    if (isset($_GET['aksi'])) {
        switch ($_GET['aksi']) {
            case "create":
                echo '<a href="crud.php"> &laquo; Home</a>';
                tambah($koneksi);
                break;
            case "read":
                tampil_data($koneksi);
                break;
            case "update":
                ubah($koneksi);
                tampil_data($koneksi);
                break;
            case "delete":
                hapus($koneksi);
                break;
            default:
                echo "<h3>Aksi <i>" . $_GET['aksi'] . "</i> tidak ada!</h3>";
                tambah($koneksi);
                tampil_data($koneksi);
        }
    } else {
        tambah($koneksi);
        tampil_data($koneksi);
    }
    ?>
</body>

</html>