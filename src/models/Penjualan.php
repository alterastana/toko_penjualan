<?php
namespace Src\Models;

use PDO;

class Penjualan
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function simpan(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO penjualan (
                tanggal, nama_produk, durasi_atau_jumlah,
                harga_beli, harga_jual, metode_customer, metode_modal,
                no_customer, status, catatan
            ) VALUES (
                CURDATE(), :produk, :durasi,
                :beli, :jual, :metode_cust, :metode_modal,
                :no_cust, :status, :catatan
            )
        ");

        return $stmt->execute([
            ':produk'        => $data['nama_produk'],
            ':durasi'        => $data['durasi_atau_jumlah'],
            ':beli'          => $data['harga_beli'],
            ':jual'          => $data['harga_jual'],
            ':metode_cust'   => $data['metode_customer'],
            ':metode_modal'  => $data['metode_modal'],
            ':no_cust'       => $data['no_customer'] ?? null,
            ':status'        => $data['status'] ?? 'lunas',
            ':catatan'       => $data['catatan'] ?? null,
        ]);
    }

    public function totalPenjualan(): float
    {
        $stmt = $this->db->query("SELECT IFNULL(SUM(harga_jual), 0) FROM penjualan");
        return (float) $stmt->fetchColumn();
    }

    public function totalProfit(): float
    {
        $stmt = $this->db->query("
            SELECT IFNULL(SUM(harga_jual - harga_beli), 0) FROM penjualan
        ");
        return (float) $stmt->fetchColumn();
    }

    public function semua(): array
    {
        $stmt = $this->db->query("SELECT * FROM penjualan ORDER BY tanggal DESC");
        return $stmt->fetchAll();
    }
}
