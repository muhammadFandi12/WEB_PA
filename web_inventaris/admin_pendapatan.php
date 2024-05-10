<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
};


if(isset($_POST['Tanggal'])){
    $tanggal = $_POST['Tanggal'];

    // Query untuk mengambil data stok berdasarkan tanggal
    $query = "SELECT s.*, p.name, p.price FROM stok s 
                JOIN products p ON s.id = p.id
                WHERE DATE(s.Tanggal) = :Tanggal";


    // Eksekusi query dengan parameter tanggal
     $statement = $pdo->prepare($query);
    $statement->bindParam(':Tanggal', $tanggal);
    $statement->execute();

    // Inisialisasi total pendapatan
    $total_pendapatan = 0;}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Perkiraan Pendapat</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px; /* Add some margin at the bottom */
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px; /* Add padding for spacing */
        }
        th {
            background-color: #f2f2f2; /* Gray background for header */
        }
    </style>
</head>
<body>
    
    <?php include 'admin_header.php'; ?>
    
<section class="user-accounts">
    <h1 class="title">Perkiraan Pendapatan</h1>
    <form action="" method="POST">
        <label for="Tanggal">Pilih Tanggal:</label>
        <input type="date" id="Tanggal" name="Tanggal">
        <button type="submit">Tampilkan</button>
    </form>

    <?php if($statement->rowCount() > 0): ?>
        <h2 class="title1">Detail Penjualan untuk Tanggal <?= $tanggal ?></h2>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $statement->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td>Rp<?= $row['price'] ?></td>
                            <td><?= $row['Stok'] ?></td>
                            <?php
                                $total_produk = $row['price'] * $row['Stok'];
                                $total_pendapatan += $total_produk;
                            ?>
                            <td>Rp<?= $total_produk ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <h3>Total Pendapatan: Rp<?= $total_pendapatan ?></h3>
    <?php else: ?>
        <p>Tidak ada data penjualan untuk tanggal <?= $tanggal ?></p>
    <?php endif; ?>
</body>
</html>
</section>