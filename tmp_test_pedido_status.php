<?php
require 'vendor/autoload.php';

$repo = new App\Repositories\PedidoRepository();

try {
    $id = $repo->createProductionOrder([
        'detalles' => [['id_producto' => 1, 'cantidad' => 1, 'precio_estimado' => 100]],
        'nombre_cliente_manual' => 'Prueba status',
        'estado' => 'Completado',
        'orden_estado' => 'Completado',
        'progreso_produccion' => 0,
    ], 1);
    echo "create_ok:$id\n";
    $repo->updateProductionOrder($id, ['estado' => 'En proceso']);
    echo "update_ok\n";
    $order = $repo->findProductionOrderById($id);
    echo "read_ok:" . ($order ? $order['id_pedido'] : 'null') . "\n";
} catch (Throwable $e) {
    echo 'ERR:' . get_class($e) . ': ' . $e->getMessage() . "\n";
}
