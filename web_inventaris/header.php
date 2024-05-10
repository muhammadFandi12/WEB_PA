<?php
@include 'config.php';

if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>

<header class="header">

   <div class="flex">

      <a href="admin_page.php" class="logo">Roti Bahari<span>.</span></a>

      <nav class="navbar">
         <a href="home.php">Beranda</a>
         <a href="shop.php">Produk</a>
         <a href="contact.php">Pesanan</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <a href="search_page.php" class="fas fa-search"></a>
         <?php
            if(isset($pdo)) {
               $count_cart_items = $pdo->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $count_cart_items->execute([$user_id]);
            }
         ?>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= isset($count_cart_items) ? $count_cart_items->rowCount() : 0; ?>)</span></a>
      </div>

      <div class="profile">
         <?php
            if(isset($pdo)) {
               $select_profile = $pdo->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_profile->execute([$user_id]);
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            }
         ?>
         <img src="uploaded_img/<?= isset($fetch_profile) ? $fetch_profile['image'] : ''; ?>" alt="">
         <p><?= isset($fetch_profile) ? $fetch_profile['name'] : ''; ?></p>
         <a href="user_profile_update.php" class="btn">update profile</a>
         <a href="logout.php" class="delete-btn">logout</a>
         <div class="flex-btn">
            <?php if (!isset($user_id)) { ?>
               <a href="index.php" class="option-btn">login</a>
               <a href="register.php" class="option-btn">register</a>
            <?php } ?>
         </div>
      </div>

   </div>

</header>
