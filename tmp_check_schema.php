<?php
require 'vendor/autoload.php';
$db = (new Config\Database())->connect();
$tables = ['pedido', 'pedido_detalle', 'orden_produccion'];
foreach ($tables as $table) {
    echo "TABLE $table\n";
    $stmt = $db->query("SHOW COLUMNS FROM `$table`");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . "\n";
    }
    echo "---\n";
}
