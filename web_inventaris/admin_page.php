<?php
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
    exit(); // Add exit() to prevent further execution
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin page</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="dashboard">

        <h1 class="title">dashboard</h1>

        <div class="box-container">

            <div class="box">
                <?php
                $total_pendings = 0;
                $select_pendings = $pdo->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                $select_pendings->execute(['done']);
                while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
                    $total_pendings += $fetch_pendings['total_price'];
                }
                ?>
                <h3>Rp<?= $total_pendings; ?></h3>
                <p>total</p>
                <a href="admin_orders.php" class="btn">Lihat</a>
            </div>

            <div class="box">
                <p>Perkiraan Pendapatan</p>
                <a href="admin_pendapatan.php" class="btn">Lihat</a>
            </div>

            <div class="box">
                <?php
                $select_orders = $pdo->prepare("SELECT * FROM `orders`");
                $select_orders->execute();
                $number_of_orders = $select_orders->rowCount();
                ?>
                <h3><?= $number_of_orders; ?></h3>
                <p>Penjualan</p>
                <a href="admin_orders.php" class="btn">Lihat Penjualan</a>
            </div>

            <div class="box">
                <?php
                $select_products = $pdo->prepare("SELECT * FROM `products`");
                $select_products->execute();
                $number_of_products = $select_products->rowCount();
                ?>
                <h3><?= $number_of_products; ?></h3>
                <p>Tambahkan Produk</p>
                <a href="admin_products.php" class="btn">Lihat Produk</a>
            </div>

            <div class="box">
                <?php
                $select_users = $pdo->prepare("SELECT * FROM `users` WHERE user_type = ?");
                $select_users->execute(['user']);
                $number_of_users = $select_users->rowCount();
                ?>
                <h3><?= $number_of_users; ?></h3>
                <p>Karyawan</p>
                <a href="admin_users.php" class="btn">Lihat Karyawan</a>
            </div>

            <div class="box">
                <?php
                $select_admins = $pdo->prepare("SELECT * FROM `users` WHERE user_type = ?");
                $select_admins->execute(['admin']);
                $number_of_admins = $select_admins->rowCount();
                ?>
                <h3><?= $number_of_admins; ?></h3>
                <p>admin</p>
                <a href="admin_users.php" class="btn">Lihat Admin</a>
            </div>

            <div class="box">
                <?php
                $select_messages = $pdo->prepare("SELECT * FROM `message`");
                $select_messages->execute();
                $number_of_messages = $select_messages->rowCount();
                ?>
                <h3><?= $number_of_messages; ?></h3>
                <p>Pesanan</p>
                <a href="admin_contacts.php" class="btn">Lihat Pesanan</a>
            </div>

        </div>

    </section>

    <script src="js/script.js"></script>

</body>

</html>
