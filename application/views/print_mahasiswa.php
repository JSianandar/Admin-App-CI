<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Data</title>
</head>
<body>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Mahasiswa</th>
            <th>NIM</th>
            <th>Tanggal Lahir</th>
            <th>Jurusan</th>
            <th>Alamat</th>
            <th>Email</th>
            <th>No. Telepon</th>
        </tr>
        <?php 
            $no = 1;
            foreach ($mahasiswa as $mhs):
        ?>
        <tr>
            <td> <?php echo $no++ ?></td>
            <td> <?php echo $mhs->nama ?></td>
            <td> <?php echo $mhs->nim ?></td>
            <td> <?php echo $mhs->tgl_lahir ?></td>
            <td> <?php echo $mhs->jurusan ?></td>
            <td> <?php echo $mhs->alamat ?></td>
            <td> <?php echo $mhs->email ?></td>
            <td> <?php echo $mhs->no_telp ?></td>
        </tr>

        <?php endforeach; ?>
    </table>

    <script type="text/javascript">
        window.print();
    </script>
</body>
</html>