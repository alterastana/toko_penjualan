<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoload dan dependensi
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/database/db.php';
require_once __DIR__ . '/../../src/graphql/resolvers.php';
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

    // Eksekusi query
    $result = GraphQL::executeQuery(
        $schema,
        $query,
        null,
        ['db' => $pdo],  // Context yang diteruskan ke resolvers
        $variables,
        $operation
    );

    // Kirim hasil dengan debug info jika error
    $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE);

} catch (Throwable $e) {
    // Tangani error besar (seperti gagal load schema, PDO, dll)
    $output = [
        'errors' => [
            [
                'message' => $e->getMessage(),
                'trace'   => $e->getTrace()
            ]
        ]
    ];
}

// Kirim output JSON ke browser / frontend
echo json_encode($output);
