<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
   exit; // Jangan lupa tambahkan exit setelah header
}

if(isset($_POST['update_product'])){
   $pid = $_POST['pid'];
   $new_stok = $_POST['Stok'];

   // Dapatkan tanggal saat ini
   $current_date = date('Y-m-d');

   // Siapkan dan jalankan query untuk memperbarui atau memasukkan data stok ke dalam tabel stok
   $insert_stok = $pdo->prepare("INSERT INTO stok (id, Stok, tanggal) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE Stok = ?, tanggal = ?");
   $insert_stok->execute([$pid, $new_stok, $current_date, $new_stok, $current_date]);

   // Jika berhasil memperbarui stok
   if ($insert_stok) {
      // Cek apakah gambar baru dipilih
      if(!empty($_FILES['image']['name'])){
         $image = $_FILES['image']['name'];
         $image_size = $_FILES['image']['size'];
         $image_tmp_name = $_FILES['image']['tmp_name'];
         $image_folder = 'uploaded_img/'.$image;
         $old_image = $_POST['old_image'];

         // Perbarui gambar produk
         $update_image = $pdo->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);

         if($update_image){
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$old_image);
            $message[] = 'image updated successfully!';
         }
      }

      $message[] = 'product updated successfully!';
   } else {
      $message[] = 'Failed to update product!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="update-product">

   <h1 class="title">update product</h1>   

   <?php
      $update_id = $_GET['update'];
      $select_products = $pdo->prepare("SELECT p.*, s.Stok 
                                          FROM products p 
                                          LEFT JOIN stok s ON p.id = s.id
                                          WHERE p.id = :update_id
                                          ORDER BY s.Tanggal DESC
                                          LIMIT 1");
      $select_products->execute(['update_id' => $update_id]);
      if($select_products->rowCount() > 0){
         $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
      }
      ?>

<form action="" method="post" enctype="multipart/form-data">
   <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
   <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
   <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt=""> 
   <input type="text" name="name" placeholder="enter product name" required class="box" value="<?= $fetch_products['name']; ?>">
   <input type="number" name="price" min="0" placeholder="enter product price" required class="box" value="<?= $fetch_products['price']; ?>">
   <input type="number" name="Stok" min="0" placeholder="enter product STOK" required class="box" value="<?= $fetch_products['Stok']; ?>">
   <select name="category" class="box" required>
      <option selected><?= $fetch_products['category']; ?></option>
      <option value="vegitables">Kue Basah</option>
      <option value="fruits">Kue Kering</option>
      <option value="meat">Bolu</option>
      <option value="fish">Gorengan/Asinan</option>
   </select>
   <textarea name="details" required placeholder="enter product details" class="box" cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
   <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
   <div class="flex-btn">
      <input type="submit" class="btn" value="update product" name="update_product">
      <a href="admin_products.php" class="option-btn">Kembali</a>
   </div>
</form>


</section>












<script src="js/script.js"></script>

</body>
</html>
