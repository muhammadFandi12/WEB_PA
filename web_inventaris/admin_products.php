<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);


   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_products = $pdo->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

 



   if($select_products->rowCount() > 0){
      $message[] = 'product name already exist!';
   }else{

      $insert_products = $pdo->prepare("INSERT INTO `products`(name, category, details, price, image) VALUES(?,?,?,?,?)");
      $insert_products->execute([$name, $category, $details, $price, $image]);

      $new_stok = $_POST['Stok'];

      // Dapatkan tanggal saat ini
      $current_date = date('Y-m-d');

   
         // $insert_stok = $pdo->prepare("INSERT INTO stok (id, Stok, tanggal) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE Stok = ?, tanggal = ?");
         // $insert_stok->execute([$new_stok, $current_date, $new_stok, $current_date]);


      
         if($insert_products){
            // Mendapatkan ID produk yang baru saja dimasukkan
            $last_product_id = $pdo->lastInsertId();
            
            // Memasukkan stok baru ke dalam tabel stok
            $insert_stok = $pdo->prepare("INSERT INTO stok (id, Stok, tanggal) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE Stok = ?, tanggal = ?");
            $insert_stok->execute([$last_product_id, $new_stok, $current_date, $new_stok, $current_date]);
        
            if($image_size > 2000000){
                $message[] = 'image size is too large!';
            }else{
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'new product added!';
            }
        }
      }        
   
   //    if($insert_products){
   //       if($image_size > 2000000){
   //          $message[] = 'image size is too large!';
   //       }else{
   //          move_uploaded_file($image_tmp_name, $image_folder);
   //          $message[] = 'new product added!';
   //       }

   //    }

   // }

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $pdo->prepare("SELECT image FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_products = $pdo->prepare("DELETE FROM `stok` WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_products = $pdo->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_wishlist = $pdo->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_cart = $pdo->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_products.php');


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Tambahkan Produk</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="Silahkan Masukan Nama Kue">
         <select name="category" class="box" required>
            <option value="" selected disabled>Pilih category</option>
               <option value="Kue Basah">Kue Basah</option>
               <option value="Kue Kering">Kue Kering</option>
               <option value="Bolu">Bolu</option>
               <option value="Asinan/Gorengan">Asinan/Gorengan</option>
         </select>
         </div>
         <div class="inputBox">
         <input type="number" min="0" name="price" class="box" required placeholder="Masukan Harga">
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         <input type="number" min="0" name="Stok" class="box" required placeholder="Masukan STOK">

         </div>
      </div>
      <textarea name="details" class="box" required placeholder="Masukan Keterangan" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="Tambahkan" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="title">Produk Telah ditambahkan</h1>

   <div class="box-container">

   <?php
      $show_products = $pdo->prepare("SELECT p.*, s.Stok 
      FROM products p 
      LEFT JOIN (
          SELECT id, Stok, Tanggal
          FROM stok
          WHERE (id, Tanggal) IN (
              SELECT id, MAX(Tanggal)
              FROM stok
              GROUP BY id
          )
      ) s ON p.id = s.id");
$show_products->execute();

      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <div class="price">Rp<?= $fetch_products['price']; ?></div>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="name"><?= $fetch_products['Stok']; ?></div>

      <div class="cat"><?= $fetch_products['category']; ?></div>
      <div class="details"><?= $fetch_products['details']; ?></div>

      <div class="flex-btn">
         <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Hapus Produk?');">Hapus</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">Tidak ada produk yang ditambahkan!</p>';
   }
   ?>

   </div>

</section>






<script src="js/script.js"></script>

</body>
</html>