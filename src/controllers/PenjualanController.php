<?php
namespace Src\Controllers;

use PDO;
use Exception;

class PenjualanController
{
    public static function simpan(PDO $db, array $data): array
    {
        try {
            $db->beginTransaction();

            // Simpan ke tabel penjualan
            $stmt = $db->prepare("
                INSERT INTO penjualan (
                    tanggal, nama_produk, durasi_atau_jumlah,
                    harga_beli, harga_jual, no_customer, status, catatan
                ) VALUES (
                    CURDATE(), :nama_produk, :durasi, :harga_beli,
                    :harga_jual, :no_customer, :status, :catatan
                )
            ");

            $stmt->execute([
                ':nama_produk' => $data['nama_produk'],
                ':durasi'      => $data['durasi_atau_jumlah'],
                ':harga_beli'  => $data['harga_beli'],
                ':harga_jual'  => $data['harga_jual'],
                ':no_customer' => $data['no_customer'] ?? null,
                ':status'      => $data['status'] ?? 'lunas',
                ':catatan'     => $data['catatan'] ?? null,
            ]);

            // Tambah saldo dari customer
            $db->prepare("
                UPDATE saldo_digital
                SET saldo = saldo + :jumlah
                WHERE metode = :metode
            ")->execute([
                ':jumlah' => $data['harga_jual'],
                ':metode' => $data['metode_customer']
            ]);

            // Kurangi saldo modal
            $db->prepare("
                UPDATE saldo_digital
                SET saldo = saldo - :jumlah
                WHERE metode = :metode
            ")->execute([
                ':jumlah' => $data['harga_beli'],
                ':metode' => $data['metode_modal']
            ]);

            $db->commit();

            return [
                'success' => true,
                'message' => 'Penjualan berhasil disimpan.'
            ];
        } catch (Exception $e) {
            $db->rollBack();
            return [
                'success' => false,
                'message' => 'Gagal menyimpan penjualan: ' . $e->getMessage()
            ];
        }
    }
}
