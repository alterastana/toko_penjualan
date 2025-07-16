<?php
namespace Src\Models;

use PDO;

class Saldo
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByMetode(string $metode): float
    {
        $stmt = $this->db->prepare("SELECT saldo FROM saldo_digital WHERE metode = :metode");
        $stmt->execute([':metode' => $metode]);
        return (float) $stmt->fetchColumn();
    }

    public function tambah(string $metode, float $jumlah): bool
    {
        $stmt = $this->db->prepare("UPDATE saldo_digital SET saldo = saldo + :jumlah WHERE metode = :metode");
        return $stmt->execute([':jumlah' => $jumlah, ':metode' => $metode]);
    }

    public function kurangi(string $metode, float $jumlah): bool
    {
        $stmt = $this->db->prepare("UPDATE saldo_digital SET saldo = saldo - :jumlah WHERE metode = :metode");
        return $stmt->execute([':jumlah' => $jumlah, ':metode' => $metode]);
    }

    public function setSaldo(string $metode, float $jumlah): bool
    {
        $stmt = $this->db->prepare("UPDATE saldo_digital SET saldo = :jumlah WHERE metode = :metode");
        return $stmt->execute([':jumlah' => $jumlah, ':metode' => $metode]);
    }

    public function totalSemua(): float
    {
        return (float) $this->db->query("SELECT IFNULL(SUM(saldo), 0) FROM saldo_digital")->fetchColumn();
    }
}
