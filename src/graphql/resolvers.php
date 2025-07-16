<?php
namespace Src\GraphQL;

use GraphQL\Type\Definition\{ObjectType, Type};
use Src\Models\Saldo;
use Src\Models\Hutang;
use Src\Models\Penjualan;

class Resolvers
{
    public static function queryType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Query',
            'fields' => [
                'totalAset' => [
                    'type' => self::asetType(),
                    'resolve' => fn ($r, $a, $ctx) => self::getTotalAset($ctx['db']),
                ],
            ],
        ]);
    }

    public static function mutationType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'insertTransaksi' => [
                    'type' => self::responseType(),
                    'args' => [
                        'metode' => Type::nonNull(Type::string()),
                        'jenis' => Type::nonNull(Type::string()),
                        'jumlah' => Type::nonNull(Type::float()),
                        'keterangan' => Type::string(),
                        'keperluan' => Type::string(),
                    ],
                    'resolve' => fn ($r, $a, $ctx) => self::insertTransaksi($a, $ctx['db']),
                ],
                'insertHutang' => [
                    'type' => self::responseType(),
                    'args' => [
                        'nama' => Type::nonNull(Type::string()),
                        'jenis' => Type::nonNull(Type::string()),
                        'jumlah' => Type::nonNull(Type::float()),
                        'metode' => Type::nonNull(Type::string()),
                        'keterangan' => Type::string(),
                    ],
                    'resolve' => fn ($r, $a, $ctx) => self::insertHutang($a, $ctx['db']),
                ],
                'insertPenjualan' => [
                    'type' => self::responseType(),
                    'args' => [
                        'nama_produk' => Type::nonNull(Type::string()),
                        'durasi_atau_jumlah' => Type::nonNull(Type::int()),
                        'harga_beli' => Type::nonNull(Type::float()),
                        'harga_jual' => Type::nonNull(Type::float()),
                        'metode_customer' => Type::nonNull(Type::string()),
                        'metode_modal' => Type::nonNull(Type::string()),
                        'no_customer' => Type::string(),
                        'status' => Type::string(),
                        'catatan' => Type::string(),
                    ],
                    'resolve' => fn ($r, $a, $ctx) => self::insertPenjualan($a, $ctx['db']),
                ],
                'insertSaldo' => [
                    'type' => self::responseType(),
                    'args' => [
                        'metode' => Type::nonNull(Type::string()),
                        'jumlah' => Type::nonNull(Type::float()),
                        'aksi' => Type::nonNull(Type::string()), // set / tambah
                    ],
                    'resolve' => fn ($r, $a, $ctx) => self::insertSaldo($a, $ctx['db']),
                ],
            ]
        ]);
    }

    private static function responseType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Response',
            'fields' => [
                'success' => Type::nonNull(Type::boolean()),
                'message' => Type::nonNull(Type::string()),
            ],
        ]);
    }

    private static function asetType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Aset',
            'fields' => [
                'total_saldo' => Type::nonNull(Type::float()),
                'total_piutang' => Type::nonNull(Type::float()),
                'total_aset' => Type::nonNull(Type::float()),
            ],
        ]);
    }

    /** ========== Implementasi Resolver Logic dengan Model ========== */

    private static function getTotalAset(\PDO $db): array
    {
        $saldoModel = new Saldo($db);
        $hutangModel = new Hutang($db);

        $totalSaldo = $saldoModel->totalSemua();
        $totalPiutang = $hutangModel->totalPiutang();
        $totalAset = $totalSaldo + $totalPiutang;

        return [
            'total_saldo' => $totalSaldo,
            'total_piutang' => $totalPiutang,
            'total_aset' => $totalAset,
        ];
    }

    private static function insertTransaksi(array $args, \PDO $db): array
    {
        try {
            $db->beginTransaction();

            $stmt = $db->prepare("
                INSERT INTO transaksi_dompet (tanggal, metode, jenis, jumlah, keterangan, keperluan)
                VALUES (CURDATE(), :metode, :jenis, :jumlah, :ket, :kep)
            ");
            $stmt->execute([
                ':metode' => $args['metode'],
                ':jenis' => $args['jenis'],
                ':jumlah' => $args['jumlah'],
                ':ket' => $args['keterangan'] ?? null,
                ':kep' => $args['keperluan'] ?? null,
            ]);

            // Update saldo
            $saldo = new Saldo($db);
            $args['jenis'] === 'masuk'
                ? $saldo->tambah($args['metode'], $args['jumlah'])
                : $saldo->kurangi($args['metode'], $args['jumlah']);

            $db->commit();
            return ['success' => true, 'message' => 'Transaksi dompet berhasil.'];
        } catch (\Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private static function insertHutang(array $args, \PDO $db): array
    {
        try {
            $model = new Hutang($db);
            $model->simpan($args);
            return ['success' => true, 'message' => 'Hutang/piutang disimpan.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private static function insertPenjualan(array $args, \PDO $db): array
    {
        try {
            $db->beginTransaction();

            $penjualan = new Penjualan($db);
            $penjualan->simpan($args);

            $saldo = new Saldo($db);
            $saldo->tambah($args['metode_customer'], $args['harga_jual']);
            $saldo->kurangi($args['metode_modal'], $args['harga_beli']);

            $db->commit();
            return ['success' => true, 'message' => 'Penjualan berhasil.'];
        } catch (\Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private static function insertSaldo(array $args, \PDO $db): array
    {
        try {
            $saldo = new Saldo($db);
            if ($args['aksi'] === 'set') {
                $saldo->setSaldo($args['metode'], $args['jumlah']);
            } else {
                $saldo->tambah($args['metode'], $args['jumlah']);
            }
            return ['success' => true, 'message' => 'Saldo berhasil diupdate.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
