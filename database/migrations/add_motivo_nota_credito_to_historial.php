<?php

// Migration: Agregar columnas motivo y crear_nota_credito a compra_historial
// Fecha: 2024-01-15

use Config\Database;

$db = (new Database())->connect();

try {
    // Agregar columna motivo
    $sql1 = "ALTER TABLE compra_historial ADD COLUMN motivo TEXT NULL AFTER valor_nuevo";
    $db->exec($sql1);
    echo "Columna 'motivo' agregada a compra_historial\n";

    // Agregar columna crear_nota_credito
    $sql2 = "ALTER TABLE compra_historial ADD COLUMN crear_nota_credito BOOLEAN DEFAULT FALSE AFTER motivo";
    $db->exec($sql2);
    echo "Columna 'crear_nota_credito' agregada a compra_historial\n";

    echo "Migration completada exitosamente.\n";
} catch (Exception $e) {
    echo "Error en migration: " . $e->getMessage() . "\n";
}
?>