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

        return $stmt->execute([
            ':nama'       => $data['nama'],
            ':jenis'      => $data['jenis'],       // pinjam / bayar
            ':jumlah'     => $data['jumlah'],
            ':keterangan' => $data['keterangan'] ?? null,
            ':metode'     => $data['metode']
        ]);
    }

    public function totalPiutang(): float
    {
        $stmt = $this->db->query("
            SELECT IFNULL((
                SELECT IFNULL(SUM(jumlah), 0) FROM hutang WHERE jenis = 'pinjam'
            ) - (
                SELECT IFNULL(SUM(jumlah), 0) FROM hutang WHERE jenis = 'bayar'
            ), 0) AS piutang
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
