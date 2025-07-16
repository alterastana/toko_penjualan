<?php
namespace Src\Utils;

class Helper
{
    public static function formatRupiah(float|int $angka): string
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    public static function formatTanggalIndo(string $tanggal): string
    {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $tanggalParts = explode('-', $tanggal);
        if (count($tanggalParts) !== 3) return $tanggal;

        $tahun = $tanggalParts[0];
        $bulanNum = (int) $tanggalParts[1];
        $hari = $tanggalParts[2];

        return $hari . ' ' . $bulan[$bulanNum] . ' ' . $tahun;
    }

    public static function sanitizeString(string $str): string
    {
        return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
    }

    public static function isValidMetode(string $metode): bool
    {
        // Sesuaikan dengan daftar metode pembayaran kamu
        $metodeList = ['Gopay', 'QRIS', 'Dana', 'Spay', 'Cash'];
        return in_array($metode, $metodeList);
    }
}
