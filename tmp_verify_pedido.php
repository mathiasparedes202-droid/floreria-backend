<?php
require __DIR__ . '/vendor/autoload.php';
$repo = new App\Repositories\PedidoRepository();
try {
    $result = $repo->listProductionOrders();
    file_put_contents(__DIR__ . '/tmp_verify_pedido_output.json', json_encode($result, JSON_UNESCAPED_UNICODE));
    echo "OK\n";
} catch (Throwable $e) {
    file_put_contents(__DIR__ . '/tmp_verify_pedido_output.json', json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE));
    echo "ERROR: " . $e->getMessage() . "\n";
}
