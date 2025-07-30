<?php

// Aktifkan error untuk debugging (jika perlu)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Koneksi database
require_once __DIR__ . '/../src/database/db.php'; // Atur sesuai path kamu

// Ambil query pencarian dari parameter URL
$q = $_GET['q'] ?? '';
$q = trim($q);

// Siapkan query
$sql = "SELECT nama, harga_jual FROM produk";
$params = [];

if ($q !== '') {
    $sql .= " WHERE nama LIKE :keyword";
    $params[':keyword'] = '%' . $q . '%';
}

$sql .= " ORDER BY nama ASC LIMIT 10";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$produkList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output sebagai JSON
header('Content-Type: application/json');
echo json_encode($produkList);
