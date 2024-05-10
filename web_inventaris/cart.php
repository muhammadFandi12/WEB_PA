<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:index.php');
    exit(); // Add exit() to prevent further execution
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_cart_item = $pdo->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$delete_id]);
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $pdo->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:cart.php');
}

if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $p_qty = $_POST['p_qty'];
    $p_qty = filter_var($p_qty, FILTER_SANITIZE_NUMBER_INT); // Use FILTER_SANITIZE_NUMBER_INT to sanitize quantity
    $update_qty = $pdo->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$p_qty, $cart_id]);
    $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'header.php'; ?>

    <section class="shopping-cart">

        <h1 class="title">Produk Yang Ditambahkan</h1>

        <div class="box-container">

            <?php
            $grand_total = 0;
            $select_cart = $pdo->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
                while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                    $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                    $grand_total += $sub_total;
                    $formatted_sub_total = number_format($sub_total, 0, ',', '.'); // Format the sub-total with comma separator
            ?>
                    <form action="" method="POST" class="box">
                        <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
                        <a href="view_page.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
                        <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
                        <div class="name"><?= $fetch_cart['name']; ?></div>
                        <div class="price">Rp<?= $fetch_cart['price']; ?></div>
                        <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                        <div class="flex-btn">
                            <input type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" class="qty" name="p_qty">
                            <input type="submit" value="update" name="update_qty" class="option-btn">
                        </div>
                        <div class="sub-total"> total : <span>Rp<?= $formatted_sub_total; ?>,-</span> </div>
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">Keranjang Kosong</p>';
            }
            ?>
        </div>

        <div class="cart-total">
            <p>total : <span>Rp<?= number_format($grand_total, 0, ',', '.'); ?>,-</span></p>
            <a href="shop.php" class="option-btn">Tambahkan Lagi</a>
            <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Hapus Semua</a>
            <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Lanjutkan Pembayaran</a>
        </div>

    </section>

    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>
