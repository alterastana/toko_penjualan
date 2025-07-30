<?php

namespace Src\Controllers;


use PDO;

class AsetController
{
    public static function hitungTotalAset(PDO $db): array
    {
        // Total saldo digital
        $stmtSaldo = $db->query("SELECT IFNULL(SUM(saldo), 0) FROM saldo_digital");
        $totalSaldo = (float) $stmtSaldo->fetchColumn();

        // Total piutang = pinjam - bayar
        $stmtPiutang = $db->query("
            SELECT IFNULL((
                SELECT IFNULL(SUM(jumlah), 0) FROM hutang WHERE jenis = 'pinjam'
            ) - (
                SELECT IFNULL(SUM(jumlah), 0) FROM hutang WHERE jenis = 'bayar'
            ), 0) AS piutang
        ");
        $totalPiutang = (float) $stmtPiutang->fetchColumn();

        return [
            'total_saldo'   => $totalSaldo,
            'total_piutang' => $totalPiutang,
            'total_aset'    => $totalSaldo + $totalPiutang,
        ];
    }
}
