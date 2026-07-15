<?php
require 'vendor/autoload.php';
$repo = new App\Repositories\PedidoRepository();
$orders = $repo->listProductionOrders();
file_put_contents('tmp_pedidos_output.json', json_encode($orders, JSON_UNESCAPED_UNICODE));
echo "OK\n";
