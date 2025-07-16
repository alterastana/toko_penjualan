<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/database/db.php';
require_once __DIR__ . '/../../src/graphql/resolvers.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;

header('Content-Type: application/json');

try {
    // Ambil data mentah dari body request
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);

    // Ambil nilai query, variable, dan operasi
    $query = $input['query'] ?? '';
    $variables = $input['variables'] ?? null;
    $operation = $input['operationName'] ?? null;

    // Definisikan skema GraphQL
    $schema = new Schema([
        'query' => \Src\GraphQL\Resolvers::queryType(),
        'mutation' => \Src\GraphQL\Resolvers::mutationType()
    ]);

    // Eksekusi query atau mutation
    $result = GraphQL::executeQuery(
        $schema,
        $query,
        null,
        ['db' => $pdo], // context
        $variables,
        $operation
    );

    $output = $result->toArray();
} catch (Throwable $e) {
    // Tangani error
    $output = [
        'errors' => [
            ['message' => $e->getMessage()]
        ]
    ];
}

// Kirim output JSON ke frontend
echo json_encode($output);
