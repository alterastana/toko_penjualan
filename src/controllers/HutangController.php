<?php

namespace Src\Controllers;

use PDO;
use Exception;

class HutangController
{
    public static function simpan(PDO $db, array $input): array
    {
        try {
            $stmt = $db->prepare("
                INSERT INTO hutang (tanggal, nama, jenis, jumlah, keterangan, metode)
                VALUES (CURDATE(), :nama, :jenis, :jumlah, :keterangan, :metode)
            ");

            $stmt->execute([
                ':nama'       => $input['nama'],
                ':jenis'      => $input['jenis'], 
                ':jumlah'     => $input['jumlah'],
                ':keterangan' => $input['keterangan'] ?? null,
                ':metode'     => $input['metode'],
            ]);

            return [
                'success' => true,
                'message' => 'Hutang/Piutang berhasil disimpan.'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menyimpan hutang: ' . $e->getMessage()
            ];
        }
    }
}
