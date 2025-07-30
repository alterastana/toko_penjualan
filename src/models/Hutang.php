<?php
namespace Src\Models;

use PDO;

class Hutang
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function simpan(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO hutang (tanggal, nama, jenis, jumlah, keterangan, metode)
            VALUES (CURDATE(), :nama, :jenis, :jumlah, :keterangan, :metode)
        ");

        $result = $stmt->execute([
            ':nama'       => $data['nama'],
            ':jenis'      => $data['jenis'],
            ':jumlah'     => $data['jumlah'],
            ':keterangan' => $data['keterangan'] ?? null,
            ':metode'     => $data['metode']
        ]);

        if (!$result) {
            $error = $stmt->errorInfo();
            file_put_contents('log_error.txt', "Gagal INSERT hutang: " . print_r($error, true), FILE_APPEND);
        }

        return $result;
    }

    // Perbaikan di sini
    public function totalPiutang(): float
    {
        $stmt = $this->db->query("
            SELECT IFNULL(SUM(jumlah), 0)
            FROM hutang
            WHERE jumlah > 0
        ");
        return (float) $stmt->fetchColumn();
    }

    public function getByNama(string $nama): array
    {
        $stmt = $this->db->prepare("SELECT * FROM hutang WHERE nama = :nama ORDER BY tanggal DESC");
        $stmt->execute([':nama' => $nama]);
        return $stmt->fetchAll();
    }
}
