<?php
require 'vendor/autoload.php';
$db = (new Config\Database())->connect();
$stmt = $db->prepare("INSERT INTO venta (id_cliente, numero_factura, timbrado, fecha_emision, estado_factura, tipo_comprobante, condicion_venta, total_factura, iva_5, iva_10, subtotal_iva_exenta, subtotal_iva_5, subtotal_iva_10, total_iva, creado_por) VALUES (1, '001-001-0000002', '12345678', NOW(), 'Pagada', 'Factura', 'Contado', 100, 0, 0, 0, 0, 0, 0, 1)");
try {
    $stmt->execute();
    echo "insert-ok\n";
} catch (Throwable $e) {
    echo $e->getMessage() . "\n";
}
