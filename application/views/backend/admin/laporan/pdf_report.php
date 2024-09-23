<!DOCTYPE html>
<html>

<head>
    <title>Laporan Transaksi Darah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Laporan Transaksi Darah</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Transaksi</th>
                <th>Jumlah</th>
                <th>Jenis Transaksi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($transaksi as $trans): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d-m-Y', strtotime($trans->tanggal_transaksi)) ?></td>
                    <td><?= $trans->jumlah ?></td>
                    <td><?= $trans->jenis_transaksi ?></td>
                    <td><?= $trans->catatan ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>