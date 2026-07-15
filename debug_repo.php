<?php
require 'vendor/autoload.php';

$repo = new App\Repositories\ProductoRepository();
$reflection = new ReflectionClass($repo);
$method = $reflection->getMethod('calculateCostoProduccion');
var_dump($method->getName());

echo "FILE: " . $reflection->getFileName() . "\n";
$source = file($reflection->getFileName());
for ($i = 115; $i <= 135; $i++) {
    if (isset($source[$i - 1])) {
        echo str_pad($i, 3, ' ', STR_PAD_LEFT) . ': ' . $source[$i - 1];
    }
}

echo "\nInsumo columns:\n";
$db = (new Config\Database())->connect();
$stmt = $db->query('SHOW COLUMNS FROM insumo');
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $c) {
    echo $c['Field'] . '\t' . $c['Type'] . '\n';
}

echo "\nCalling calculateCostoProduccion with sample detail...\n";
try {
    $result = $repo->calculateCostoProduccion([['id_insumo' => 1, 'cantidad' => 2]]);
    var_dump($result);
} catch (Throwable $e) {
    echo 'EXCEPTION: ' . $e->getMessage() . '\n';
}
