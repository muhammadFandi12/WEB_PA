<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:index.php');
    exit(); // Add exit() to prevent further execution
}

if (isset($_POST['order'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = array(); // Initialize an array properly

    $cart_query = $pdo->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $cart_query->execute([$user_id]);
    if ($cart_query->rowCount() > 0) {
        while ($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)) {
            $cart_products[] = $cart_item['name'] . ' ( ' . $cart_item['quantity'] . ' )';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    $order_query = $pdo->prepare("SELECT * FROM `orders` WHERE name = ? AND total_products = ? AND total_price = ?");
    $order_query->execute([$name, $total_products, $cart_total]);

    if ($cart_total == 0) {
        $message[] = 'your cart is empty';
    } elseif ($order_query->rowCount() > 0) {
        $message[] = 'order placed already!';
    } else {
        $insert_order = $pdo->prepare("INSERT INTO `orders`(user_id, name, total_products, total_price, placed_on) VALUES(?,?,?,?,?)");
        $insert_order->execute([$user_id, $name, $total_products, $cart_total, $placed_on]);
        $delete_cart = $pdo->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);
        $message[] = 'order placed successfully!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'header.php'; ?>

    <section class="display-orders">

        <?php
        $cart_grand_total = 0;
        $select_cart_items = $pdo->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart_items->execute([$user_id]);
        if ($select_cart_items->rowCount() > 0) {
            while ($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
                $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
                $cart_grand_total += $cart_total_price;
        ?>
                <p> <?= $fetch_cart_items['name']; ?> <span>(<?= 'Rp' . number_format($fetch_cart_items['price'], 0, ',', '.') . ' x ' . $fetch_cart_items['quantity']; ?>)</span> </p>
        <?php
            }
        } else {
            echo '<p class="empty">your cart is empty!</p>';
        }
        ?>
        <div class="grand-total">total : <span>Rp<?= number_format($cart_grand_total, 0, ',', '.'); ?></span></div>
    </section>

    <section class="checkout-orders">

        <form action="" method="POST">

            <h3>Selesaikan Pesanan</h3>

            <div class="flex">
                <div class="inputBox">
                    <span>Nama Karyawan :</span>
                    <input type="text" name="name" placeholder="Silahkan Masukan Nama Anda" class="box" required>
                </div>
                <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1) ? '' : 'disabled'; ?>" value="Selesai">
            </div>

        </form>

    </section>

    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>
