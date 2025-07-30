<?php
namespace Src\Models;

use PDO;
use PDOException;
use RuntimeException;

class Saldo
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get balance for a specific payment method
     * @param string $metode Payment method
     * @return float Current balance (returns 0 if method doesn't exist)
     * @throws PDOException On database error
     */
    public function getByMetode(string $metode): float
    {
        try {
            $stmt = $this->db->prepare("SELECT saldo FROM saldo_digital WHERE metode = :metode");
            $stmt->execute([':metode' => $metode]);
            return (float) ($stmt->fetchColumn() ?: 0);
        } catch (PDOException $e) {
            error_log("Error getting saldo: " . $e->getMessage());
            throw new PDOException("Failed to retrieve balance", 0, $e);
        }
    }

    /**
     * Add to balance for a specific payment method
     * @param string $metode Payment method
     * @param float $jumlah Amount to add
     * @return bool True on success
     * @throws PDOException On database error
     */
    public function tambah(string $metode, float $jumlah): bool
    {
        try {
            if ($jumlah <= 0) {
                throw new RuntimeException("Jumlah harus lebih besar dari 0");
            }

            $stmt = $this->db->prepare("
                INSERT INTO saldo_digital (metode, saldo)
                VALUES (:metode, :jumlah)
                ON DUPLICATE KEY UPDATE saldo = saldo + :jumlah
            ");
            
            return $stmt->execute([
                ':metode' => $metode,
                ':jumlah' => $jumlah
            ]);
        } catch (PDOException $e) {
            error_log("Error adding saldo: " . $e->getMessage());
            throw new PDOException("Failed to add balance", 0, $e);
        }
    }

    /**
     * Subtract from balance for a specific payment method
     * @param string $metode Payment method
     * @param float $jumlah Amount to subtract
     * @return bool True on success
     * @throws RuntimeException When insufficient balance
     * @throws PDOException On database error
     */
    public function kurangi(string $metode, float $jumlah): bool
    {
        try {
            if ($jumlah <= 0) {
                throw new RuntimeException("Jumlah harus lebih besar dari 0");
            }

            // Verify sufficient balance
            $current = $this->getByMetode($metode);
            if ($current < $jumlah) {
                throw new RuntimeException("Saldo tidak mencukupi");
            }

            $stmt = $this->db->prepare("
                UPDATE saldo_digital 
                SET saldo = saldo - :jumlah 
                WHERE metode = :metode
            ");
            
            return $stmt->execute([
                ':metode' => $metode,
                ':jumlah' => $jumlah
            ]);
        } catch (PDOException $e) {
            error_log("Error subtracting saldo: " . $e->getMessage());
            throw new PDOException("Failed to subtract balance", 0, $e);
        }
    }

    /**
     * Set absolute balance for a payment method
     * @param string $metode Payment method
     * @param float $jumlah New balance amount
     * @return bool True on success
     * @throws PDOException On database error
     */
    public function setSaldo(string $metode, float $jumlah): bool
    {
        try {
            if ($jumlah < 0) {
                throw new RuntimeException("Jumlah tidak boleh negatif");
            }

            $stmt = $this->db->prepare("
                INSERT INTO saldo_digital (metode, saldo)
                VALUES (:metode, :jumlah)
                ON DUPLICATE KEY UPDATE saldo = :jumlah
            ");
            
            return $stmt->execute([
                ':metode' => $metode,
                ':jumlah' => $jumlah
            ]);
        } catch (PDOException $e) {
            error_log("Error setting saldo: " . $e->getMessage());
            throw new PDOException("Failed to set balance", 0, $e);
        }
    }

    /**
     * Get total balance across all payment methods
     * @return float Total balance
     * @throws PDOException On database error
     */
    public function totalSemua(): float
    {
        try {
            return (float) $this->db->query("SELECT IFNULL(SUM(saldo), 0) FROM saldo_digital")->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error calculating total saldo: " . $e->getMessage());
            throw new PDOException("Failed to calculate total balance", 0, $e);
        }
    }

    /**
     * Get all payment methods and their balances
     * @return array Associative array of [metode => saldo]
     * @throws PDOException On database error
     */
    public function getAll(): array
    {
        try {
            $stmt = $this->db->query("SELECT metode, saldo FROM saldo_digital ORDER BY metode");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $saldoData = [];
            foreach ($results as $row) {
                $saldoData[$row['metode']] = (float) $row['saldo'];
            }
            
            return $saldoData;
        } catch (PDOException $e) {
            error_log("Error retrieving all saldo: " . $e->getMessage());
            throw new PDOException("Failed to retrieve all balances", 0, $e);
        }
    }

    /**
     * Transfer balance between payment methods
     * @param string $dari Source payment method
     * @param string $ke Target payment method
     * @param float $jumlah Amount to transfer
     * @return bool True on success
     * @throws RuntimeException When insufficient balance or invalid transfer
     * @throws PDOException On database error
     */
    public function transfer(string $dari, string $ke, float $jumlah): bool
    {
        try {
            if ($jumlah <= 0) {
                throw new RuntimeException("Jumlah transfer harus positif");
            }
            if ($dari === $ke) {
                throw new RuntimeException("Tidak bisa transfer ke metode yang sama");
            }

            $this->db->beginTransaction();

            // Check source balance
            $saldoAwal = $this->getByMetode($dari);
            if ($saldoAwal < $jumlah) {
                throw new RuntimeException("Saldo tidak mencukupi untuk transfer");
            }

            // Perform transfer
            $this->kurangi($dari, $jumlah);
            $this->tambah($ke, $jumlah);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error during transfer: " . $e->getMessage());
            throw new PDOException("Failed to complete transfer", 0, $e);
        } catch (RuntimeException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}