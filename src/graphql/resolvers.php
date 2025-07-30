<?php

namespace Src\GraphQL;

use GraphQL\Type\Definition\{ObjectType, Type};
use Src\Models\Saldo;
use Src\Models\Hutang;
use Src\Models\Penjualan;

class Resolvers
{
    private static $responseType;
    private static $asetType;
    private static $penjualanType;
    private static $transaksiDompetType;
    private static $saldoDigitalType;
    private static $hutangType;
    private static $akunType;

private static function akunType(): ObjectType
{
    if (!self::$akunType) {
        self::$akunType = new ObjectType([
            'name' => 'Akun',
            'fields' => [
                'id' => Type::int(),
                'created_at' => Type::string(),
                'username' => Type::string(),
                'password' => Type::string(),
                'jadwal_promosi' => Type::string(),
                'status' => Type::string(),
            ],
        ]);
    }
    return self::$akunType;
}




    private static function transaksiDompetType(): ObjectType
    {
        if (!self::$transaksiDompetType) {
            self::$transaksiDompetType = new ObjectType([
                'name' => 'TransaksiDompet',
                'fields' => [
                    'id_transaksi' => Type::int(),
                    'tanggal' => Type::string(),
                    'metode' => Type::string(),
                    'jenis' => Type::string(),
                    'jumlah' => Type::float(),
                    'keterangan' => Type::string(),
                    'keperluan' => Type::string(),
                ],
            ]);
        }
        return self::$transaksiDompetType;
    }

    private static function saldoDigitalType(): ObjectType
    {
        if (!self::$saldoDigitalType) {
            self::$saldoDigitalType = new ObjectType([
                'name' => 'SaldoDigital',
                'fields' => [
                    'id_saldo' => Type::int(),
                    'metode' => Type::string(),
                    'jumlah' => Type::float(),
                ],
            ]);
        }
        return self::$saldoDigitalType;
    }

    public static function queryType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Query',
            'fields' => [
                'totalAset' => [
                    'type' => self::asetType(),
                    'resolve' => fn ($r, $a, $ctx) => self::getTotalAset($ctx['db']),
                ],
                'getAllPenjualan' => [
                    'type' => Type::listOf(self::penjualanType()),
                    'resolve' => fn ($r, $a, $ctx) => self::getAllPenjualan($ctx['db']),
                ],
                'getAllHutang' => [
                    'type' => Type::listOf(self::hutangType()),
                    'resolve' => fn ($r, $a, $ctx) => self::getAllHutang($ctx['db']),
                ],
                'getAllTransaksiDompet' => [
                    'type' => Type::listOf(self::transaksiDompetType()),
                    'resolve' => fn ($r, $a, $ctx) => self::getAllTransaksiDompet($ctx['db']),
                ],
                'getHutangByNama' => [
                    'type' => self::hutangType(),
                    'args' => [
                        'nama' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => function ($r, $args, $ctx) {
                        $stmt = $ctx['db']->prepare("SELECT * FROM hutang WHERE nama = :nama ORDER BY id_hutang DESC LIMIT 1");
                        $stmt->execute([':nama' => $args['nama']]);
                        $hutang = $stmt->fetch(\PDO::FETCH_ASSOC);
                        return $hutang ?: ['jumlah' => 0];
                    },
                ],
                'getAllSaldoDigital' => [
                    'type' => Type::listOf(self::saldoDigitalType()),
                    'resolve' => fn ($r, $a, $ctx) => self::getAllSaldoDigital($ctx['db']),
                ],
                'getPenjualanLimit' => [
                        'type' => Type::listOf(self::penjualanType()),
                        'args' => [
                            'limit' => Type::int(),
                            'offset' => Type::int(),
                        ],
                        'resolve' => function ($root, $args, $ctx) {
                            $limit = $args['limit'] ?? 5;
                            $offset = $args['offset'] ?? 0;

                            $stmt = $ctx['db']->prepare("
                                SELECT * FROM penjualan
                                ORDER BY tanggal DESC
                                LIMIT :limit OFFSET :offset
                            ");
                            $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
                            $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
                            $stmt->execute();

                            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        }
                    ],

                    'getAllAkun' => [
    'type' => Type::listOf(self::akunType()),
    'resolve' => function ($root, $args, $ctx) {
        $stmt = $ctx['db']->query("SELECT * FROM akun ORDER BY id DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
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
        'untung' => Type::float(), // dibuat opsional
        'metode_customer' => Type::nonNull(Type::string()),
        'metode_modal' => Type::nonNull(Type::string()),
        'no_customer' => Type::string(),
        'status' => Type::string(),
        'catatan' => Type::string(),
    ],
    'resolve' => function ($root, $args, $ctx) {
        // Kalau untung tidak dikirim atau null, hitung manual
        if (!isset($args['untung'])) {
            $args['untung'] = $args['harga_jual'] - $args['harga_beli'];
        }

        return self::insertPenjualan($args, $ctx['db']);
    }
],

                'insertSaldo' => [
                    'type' => self::responseType(),
                    'args' => [
                        'metode' => Type::nonNull(Type::string()),
                        'jumlah' => Type::nonNull(Type::float()),
                        'aksi' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => fn ($r, $a, $ctx) => self::insertSaldo($a, $ctx['db']),
                ],
                'updateHutang' => [
                    'type' => self::responseType(),
                    'args' => [
                        'id' => Type::nonNull(Type::int()),
                        'tanggal' => Type::nonNull(Type::string()),
                        'jumlah' => Type::nonNull(Type::float()),
                        'metode' => Type::nonNull(Type::string()),
                        'keterangan' => Type::string(),
                    ],
                    'resolve' => fn ($r, $a, $ctx) => self::updateHutang($a, $ctx['db']),
                ],
                'updatePenjualan' => [
                    'type' => self::responseType(),
                    'args' => [
                        'id' => Type::nonNull(Type::int()),
                        'nama_produk' => Type::nonNull(Type::string()),
                        'durasi_atau_jumlah' => Type::nonNull(Type::int()),
                        'harga_beli' => Type::nonNull(Type::float()),
                        'harga_jual' => Type::nonNull(Type::float()),
                        'metode_customer' => Type::nonNull(Type::string()),
                        'metode_modal' => Type::nonNull(Type::string()),
                        'status' => Type::string(),
                        'proof' => Type::string(),
                        'catatan' => Type::string(),
                    ],
                    'resolve' => fn ($r, $a, $ctx) => self::updatePenjualan($a, $ctx['db']),
                ],

                'insertAkun' => [
    'type' => self::responseType(),
    'args' => [
        'username' => Type::nonNull(Type::string()),
        'password' => Type::nonNull(Type::string()),
        'jadwal_promosi' => Type::nonNull(Type::string()),
        'status' => Type::nonNull(Type::string()),
    ],
    'resolve' => function ($root, $args, $ctx) {
        $stmt = $ctx['db']->prepare("
            INSERT INTO akun (tanggal, username, password, jadwal_promosi, status)
            VALUES (CURDATE(), :username, :password, :jadwal_promosi, :status)
        ");
        $success = $stmt->execute([
            ':username' => $args['username'],
            ':password' => password_hash($args['password'], PASSWORD_BCRYPT),
            ':jadwal_promosi' => $args['jadwal_promosi'],
            ':status' => $args['status'],
        ]);
        return ['success' => $success, 'message' => $success ? 'Akun berhasil ditambahkan' : 'Gagal menambah akun'];
    }
],
                  'updateAkun' => [
    'type' => self::responseType(),
    'args' => [
        'id' => Type::nonNull(Type::int()),
        'username' => Type::nonNull(Type::string()),
        'password' => Type::nonNull(Type::string()),
        'jadwal_promosi' => Type::nonNull(Type::string()),
        'status' => Type::nonNull(Type::string()),
    ],
    'resolve' => function ($root, $args, $ctx) {
        $stmt = $ctx['db']->prepare("
            UPDATE akun
            SET username = :username,
                password = :password,
                jadwal_promosi = :jadwal_promosi,
                status = :status
            WHERE id = :id
        ");
        $success = $stmt->execute([
            ':id' => $args['id'],
            ':username' => $args['username'],
            ':password' => password_hash($args['password'], PASSWORD_BCRYPT),
            ':jadwal_promosi' => $args['jadwal_promosi'],
            ':status' => $args['status'],
        ]);
        return ['success' => $success, 'message' => $success ? 'Akun berhasil diupdate' : 'Gagal update akun'];
    }
],

                    'deleteAkun' => [
                        'type' => self::responseType(),
                        'args' => ['id' => Type::nonNull(Type::int())],
                        'resolve' => function ($root, $args, $ctx) {
                            $stmt = $ctx['db']->prepare("DELETE FROM akun WHERE id = :id");
                            $success = $stmt->execute([':id' => $args['id']]);
                            return ['success' => $success, 'message' => $success ? 'Akun berhasil dihapus' : 'Gagal hapus akun'];
                        }
                    ],


            ]
        ]);
    }

    private static function responseType(): ObjectType
    {
        if (!self::$responseType) {
            self::$responseType = new ObjectType([
                'name' => 'Response',
                'fields' => [
                    'success' => Type::nonNull(Type::boolean()),
                    'message' => Type::nonNull(Type::string()),
                ],
            ]);
        }
        return self::$responseType;
    }

    private static function asetType(): ObjectType
    {
        if (!self::$asetType) {
            self::$asetType = new ObjectType([
                'name' => 'Aset',
                'fields' => [
                    'total_saldo' => Type::nonNull(Type::float()),
                    'total_piutang' => Type::nonNull(Type::float()),
                    'total_aset' => Type::nonNull(Type::float()),
                ],
            ]);
        }
        return self::$asetType;
    }

 private static function penjualanType(): ObjectType
{
    if (!self::$penjualanType) {
        self::$penjualanType = new ObjectType([
            'name' => 'Penjualan',
            'fields' => [
                'id_penjualan' => Type::int(),
                'tanggal' => Type::string(),
                'nama_produk' => Type::string(),
                'durasi_atau_jumlah' => Type::int(),
                'harga_beli' => Type::float(),
                'harga_jual' => Type::float(),
                'metode_customer' => Type::string(),
                'metode_modal' => Type::string(),
                'no_customer' => Type::string(),
                'status' => Type::string(),
                'catatan' => Type::string(),
                'untung' => Type::float(),
                'pengeluaran' => Type::float(),
                'total_untung' => Type::float(),
                'proof' => Type::string(), // ditambahkan
            ]
        ]);
    }
    return self::$penjualanType;
}


    private static function hutangType(): ObjectType
    {
        if (!self::$hutangType) {
            self::$hutangType = new ObjectType([
                'name' => 'Hutang',
                'fields' => [
                    'id_hutang' => Type::int(),
                    'nama' => Type::string(),
                    'jenis' => Type::string(),
                    'jumlah' => Type::float(),
                    'tanggal' => Type::string(),
                    'metode' => Type::string(),
                    'keterangan' => Type::string(),
                ],
            ]);
        }
        return self::$hutangType;
    }

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
            $db->beginTransaction();

            $stmt = $db->prepare("SELECT * FROM hutang WHERE nama = :nama ORDER BY id_hutang DESC LIMIT 1");
            $stmt->execute([':nama' => $args['nama']]);
            $existing = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($existing) {
                $jumlah_lama = (float) $existing['jumlah'];
                $jumlah_baru = (float) $args['jumlah'];
                $jenis_baru = $args['jenis'];

                if ($jenis_baru === 'pinjam') {
                    $total = $jumlah_lama + $jumlah_baru;
                } elseif ($jenis_baru === 'bayar') {
                    $total = max(0, $jumlah_lama - $jumlah_baru);
                } else {
                    throw new \Exception("Jenis hutang tidak valid: " . $jenis_baru);
                }

                $update = $db->prepare("
                    UPDATE hutang SET
                        jumlah = :jumlah,
                        tanggal = CURRENT_DATE(),
                        metode = :metode,
                        keterangan = :keterangan,
                        jenis = :jenis
                    WHERE id_hutang = :id
                ");
                $update->execute([
                    ':jumlah' => $total,
                    ':metode' => $args['metode'],
                    ':keterangan' => $args['keterangan'] ?? null,
                    ':jenis' => $jenis_baru,
                    ':id' => $existing['id_hutang'],
                ]);
            } else {
                if ($args['jenis'] !== 'pinjam') {
                    throw new \Exception("Tidak ada hutang sebelumnya untuk dibayar.");
                }

                $insert = $db->prepare("
                    INSERT INTO hutang (nama, jenis, jumlah, tanggal, metode, keterangan)
                    VALUES (:nama, :jenis, :jumlah, CURRENT_DATE(), :metode, :keterangan)
                ");
                $insert->execute([
                    ':nama' => $args['nama'],
                    ':jenis' => $args['jenis'],
                    ':jumlah' => $args['jumlah'],
                    ':metode' => $args['metode'],
                    ':keterangan' => $args['keterangan'] ?? null,
                ]);
            }

            $saldo = new \Src\Models\Saldo($db);
            if ($args['jenis'] === 'pinjam') {
                $saldo->kurangi($args['metode'], $args['jumlah']);
            } elseif ($args['jenis'] === 'bayar') {
                $saldo->tambah($args['metode'], $args['jumlah']);
            }

            $db->commit();
            return ['success' => true, 'message' => 'Data hutang berhasil diperbarui atau ditambahkan.'];
        } catch (\Exception $e) {
            $db->rollBack();
            file_put_contents('log_error.txt', "InsertHutang error: " . $e->getMessage() . "\n", FILE_APPEND);
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

    private static function getAllPenjualan(\PDO $db): array
    {
        $stmt = $db->query("SELECT * FROM penjualan ORDER BY tanggal DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private static function getAllHutang(\PDO $db): array
    {
        $stmt = $db->query("SELECT * FROM hutang ORDER BY tanggal DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private static function updateHutang(array $args, \PDO $db): array
    {
        try {
            $db->beginTransaction();

            $stmt = $db->prepare("UPDATE hutang SET jenis = 'bayar', tanggal = :tanggal, jumlah = :jumlah, metode = :metode, keterangan = :keterangan WHERE id_hutang = :id");
            $success = $stmt->execute([
                ':tanggal' => $args['tanggal'],
                ':jumlah' => $args['jumlah'],
                ':metode' => $args['metode'],
                ':keterangan' => $args['keterangan'] ?? null,
                ':id' => $args['id']
            ]);

            if ($success) {
                $saldo = new Saldo($db);
                $saldo->tambah($args['metode'], $args['jumlah']);
            }

            $db->commit();
            return [
                'success' => $success,
                'message' => $success ? 'Hutang berhasil dilunasi.' : 'Gagal melunasi hutang.'
            ];
        } catch (\Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private static function getAllTransaksiDompet(\PDO $db): array
    {
        $stmt = $db->query("SELECT * FROM transaksi_dompet ORDER BY tanggal DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private static function getAllSaldoDigital(\PDO $db): array
    {
        $stmt = $db->query("SELECT metode, saldo AS jumlah FROM saldo_digital ORDER BY metode ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


 private static function updatePenjualan(array $args, \PDO $db): array
{
    try {
        $db->beginTransaction();

        // Ambil data lama
        $stmtOld = $db->prepare("SELECT * FROM penjualan WHERE id_penjualan = :id");
        $stmtOld->execute([':id' => $args['id']]);
        $old = $stmtOld->fetch(\PDO::FETCH_ASSOC);

        if (!$old) {
            throw new \Exception("Data penjualan tidak ditemukan.");
        }

        // Update tabel penjualan
        $stmt = $db->prepare("
            UPDATE penjualan SET
                nama_produk = :nama_produk,
                durasi_atau_jumlah = :durasi,
                harga_beli = :harga_beli,
                harga_jual = :harga_jual,
                metode_customer = :metode_customer,
                metode_modal = :metode_modal,
                status = :status,
                proof = :proof,
                catatan = :catatan
            WHERE id_penjualan = :id
        ");
        $success = $stmt->execute([
            ':id' => $args['id'],
            ':nama_produk' => $args['nama_produk'],
            ':durasi' => $args['durasi_atau_jumlah'],
            ':harga_beli' => $args['harga_beli'],
            ':harga_jual' => $args['harga_jual'],
            ':metode_customer' => $args['metode_customer'],
            ':metode_modal' => $args['metode_modal'],
            ':status' => $args['status'] ?? null,
            ':proof' => $args['proof'] ?? 'belum',
            ':catatan' => $args['catatan'] ?? null,
        ]);

        if (!$success) {
            throw new \Exception("Gagal memperbarui penjualan.");
        }

        // Perbaiki saldo jika metode atau jumlah berubah
        $saldo = new \Src\Models\Saldo($db);

        // Kalau metode_customer berubah, pindahkan seluruh harga_jual lama
        if ($old['metode_customer'] !== $args['metode_customer']) {
            $saldo->kurangi($old['metode_customer'], $old['harga_jual']);
            $saldo->tambah($args['metode_customer'], $args['harga_jual']);
        } else {
            // Kalau metode sama tapi harga_jual berubah, sesuaikan selisih
            $selisih = $args['harga_jual'] - $old['harga_jual'];
            if ($selisih > 0) {
                $saldo->tambah($args['metode_customer'], $selisih);
            } elseif ($selisih < 0) {
                $saldo->kurangi($args['metode_customer'], abs($selisih));
            }
        }

        // Kalau metode_modal berubah, tidak bisa dipindahkan otomatis karena modal sudah keluar

        $db->commit();
        return [
            'success' => true,
            'message' => 'Penjualan berhasil diperbarui dan saldo disesuaikan.'
        ];
    } catch (\Exception $e) {
        $db->rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
private static function getAllAkun(\PDO $db): array
{
    $stmt = $db->query("SELECT id, username, jadwal_promosi, status, tanggal FROM akun ORDER BY tanggal DESC");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

private static function insertAkun(array $args, \PDO $db): array
{
    try {
        $stmt = $db->prepare("
            INSERT INTO akun (username, password, jadwal_promosi, status, tanggal)
            VALUES (:username, :password, :jadwal_promosi, :status, NOW())
        ");
        $stmt->execute([
            ':username' => $args['username'],
            ':password' => password_hash($args['password'], PASSWORD_BCRYPT),
            ':jadwal_promosi' => $args['jadwal_promosi'],
            ':status' => $args['status'],
        ]);
        return ['success' => true, 'message' => 'Akun berhasil ditambahkan'];
    } catch (\Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}


}
