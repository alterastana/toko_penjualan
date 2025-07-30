<?php

session_start(); // Tambahkan session start di awal


// Autoload dan dependensi
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/database/db.php';
require_once __DIR__ . '/../../src/graphql/resolvers.php';

// Debug: cek koneksi DB (opsional, bisa dihapus di produksi)
file_put_contents('debug_db.txt', 'Aktif di DB: ' . $pdo->query("SELECT DATABASE()")->fetchColumn());

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Error\DebugFlag;

// Header JSON
header('Content-Type: application/json');

try {
    // Ambil body JSON dari request
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);

    $query     = $input['query'] ?? '';
    $variables = $input['variables'] ?? null;
    $operation = $input['operationName'] ?? null;

    // Definisikan skema dengan Query dan Mutation
    $schema = new Schema([
        'query'    => \Src\GraphQL\Resolvers::queryType(),
        'mutation' => \Src\GraphQL\Resolvers::mutationType()
    ]);

    // Eksekusi query GraphQL
    $result = GraphQL::executeQuery(
        $schema,
        $query,
        null,
        ['db' => $pdo],  // Context DB ke resolvers
        $variables,
        $operation
    );

    // Hasil JSON dengan debug info
    $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE);

} catch (Throwable $e) {
    // Tangani error besar seperti koneksi atau schema gagal
    $output = [
        'errors' => [
            [
                'message' => $e->getMessage(),
                'trace'   => $e->getTrace()
            ]
        ]
    ];
}

// Kirim output ke frontend
echo json_encode($output);
