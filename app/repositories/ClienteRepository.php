<?php

namespace App\Repositories;

use Config\Database;
use PDO;
use App\Models\Cliente;

class ClienteRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function all(): array
    {
        $query = "SELECT * FROM cliente WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($data) => new Cliente($data), $rows);
    }

    public function findById(int $id): ?Cliente
    {
        $query = "SELECT * FROM cliente WHERE id_cliente = :id AND estado = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Cliente($data) : null;
    }

    public function findByCiRuc(string $ciRuc): ?Cliente
    {
        $query = "SELECT * FROM cliente WHERE ci_ruc = :ci_ruc AND estado = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ci_ruc', $ciRuc, PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Cliente($data) : null;
    }

    public function create(Cliente $cliente): int
    {
        $query = "INSERT INTO cliente (nombre, apellido, ci_ruc, email, telefono, celular, direccion, ciudad, departamento, tipo_cliente, estado, creado_por)
                  VALUES (:nombre, :apellido, :ci_ruc, :email, :telefono, :celular, :direccion, :ciudad, :departamento, :tipo_cliente, :estado, :creado_por)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $cliente->nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $cliente->apellido, PDO::PARAM_STR);
        $stmt->bindParam(':ci_ruc', $cliente->ci_ruc, PDO::PARAM_STR);
        $stmt->bindParam(':email', $cliente->email, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $cliente->telefono, PDO::PARAM_STR);
        $stmt->bindParam(':celular', $cliente->celular, PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $cliente->direccion, PDO::PARAM_STR);
        $stmt->bindParam(':ciudad', $cliente->ciudad, PDO::PARAM_STR);
        $stmt->bindParam(':departamento', $cliente->departamento, PDO::PARAM_STR);
        $stmt->bindParam(':tipo_cliente', $cliente->tipo_cliente, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $cliente->estado, PDO::PARAM_INT);
        $stmt->bindParam(':creado_por', $cliente->creado_por, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return (int) $this->db->lastInsertId();
        }

        throw new \Exception('No se pudo crear el cliente');
    }

    public function update(Cliente $cliente): bool
    {
        $query = "UPDATE cliente SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono, 
                  celular = :celular, direccion = :direccion, ciudad = :ciudad, departamento = :departamento, 
                  tipo_cliente = :tipo_cliente, estado = :estado, actualizado_por = :actualizado_por, 
                  fecha_actualizacion = CURRENT_TIMESTAMP WHERE id_cliente = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $cliente->id_cliente, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $cliente->nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $cliente->apellido, PDO::PARAM_STR);
        $stmt->bindParam(':email', $cliente->email, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $cliente->telefono, PDO::PARAM_STR);
        $stmt->bindParam(':celular', $cliente->celular, PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $cliente->direccion, PDO::PARAM_STR);
        $stmt->bindParam(':ciudad', $cliente->ciudad, PDO::PARAM_STR);
        $stmt->bindParam(':departamento', $cliente->departamento, PDO::PARAM_STR);
        $stmt->bindParam(':tipo_cliente', $cliente->tipo_cliente, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $cliente->estado, PDO::PARAM_INT);
        $stmt->bindParam(':actualizado_por', $cliente->actualizado_por, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete(int $id, bool $hardDelete = false): bool
    {
        if ($hardDelete) {
            $query = "DELETE FROM cliente WHERE id_cliente = :id";
        } else {
            $query = "UPDATE cliente SET estado = 0 WHERE id_cliente = :id";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
