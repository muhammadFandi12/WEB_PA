<?php

session_start();

include 'config.php'; // Menggunakan PDO, jadi tidak perlu lagi @include

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:index.php');
   exit; // Tambahkan exit setelah header untuk menghentikan eksekusi skrip
}

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);
   $pembeli = $_POST['pembeli'];
   $pembeli = filter_var($pembeli, FILTER_SANITIZE_STRING);

   try {
      $stmt = $pdo->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ? AND pembeli = ?");
      $stmt->execute([$name, $email, $number, $msg, $pembeli]);
      
      if($stmt->rowCount() > 0){
         $message[] = 'already sent message!';
      } else {
         $stmt = $pdo->prepare("INSERT INTO `message`(user_id, name, email, number, message, pembeli) VALUES(?,?,?,?,?,?)");
         $stmt->execute([$user_id, $name, $email, $number, $msg, $pembeli]);

         $message[] = 'sent message successfully!';
      }
   } catch (PDOException $e) {
      // Tangani kesalahan PDO di sini
      $message[] = 'Error: ' . $e->getMessage();
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pesanan</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="contact">

   <h1 class="title">Pemesanan</h1>

   <form action="" method="POST">
      <input type="text" name="name" class="box" required placeholder="Masukkan Nama Kue">
      <input type="text" name="email" class="box" required placeholder="Masukan Nama Karyawan Yang Melayani ">
      <input type="number" name="number" min="0" class="box" required placeholder="Masukan Jumlah">
      <input type="text" name="pembeli" class="box" required placeholder="Masukkan Nama Pembeli">
      <textarea name="msg" class="box" required placeholder="Silahkan Masukan Pesanan" cols="30" rows="10"></textarea>
      <input type="submit" value="Kirim Pesanan" class="btn" name="send">
   </form>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
