<?php
$serve = "localhost";
$username = "id22143472_enterprise";
$password = "2109_KEL4_website";
$db_name = "id22143472_shop_db";

// Ganti pemanggilan mysql() dengan pembuatan objek koneksi PDO atau MySQLi
try {
    // Membuat koneksi menggunakan PDO
    $dsn = "mysql:host=$serve;dbname=$db_name";
    $pdo = new PDO($dsn, $username, $password);
    // Atur opsi untuk menampilkan error PDO secara otomatis
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Alternatifnya, jika Anda ingin menggunakan MySQLi, Anda dapat menggunakan kode berikut:
    // $conn = new mysqli($serve, $username, $password, $db_name);
    
    // Pastikan untuk mengganti $pdo dengan $conn jika Anda memilih untuk menggunakan MySQLi
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
