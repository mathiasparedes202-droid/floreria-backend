<?php

namespace App\Controllers;

use App\Services\ClienteService;
use Core\Controller;
use Core\Request;
use Core\Response;

class ClienteController extends Controller
{
    private ClienteService $clienteService;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->clienteService = new ClienteService();
    }

    public function index(): void
    {
        try {
            $clientes = $this->clienteService->listarClientes();
            $this->response->json(['success' => true, 'data' => $clientes], 200);
        } catch (\Exception $e) {
            $this->response->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(array $params): void
    {
        try {
            $id = (int)$params['id'];
            $cliente = $this->clienteService->obtenerCliente($id);

            if (!$cliente) {
                $this->response->json(['success' => false, 'message' => 'Cliente no encontrado'], 404);
                return;
            }

            $this->response->json(['success' => true, 'data' => $cliente], 200);
        } catch (\Exception $e) {
            $this->response->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(): void
    {
        try {
            $data = $this->request->getBody();
            $clienteId = $this->clienteService->crearCliente($data);
            $cliente = $this->clienteService->obtenerCliente($clienteId);

            $this->response->json(['success' => true, 'data' => $cliente], 201);
        } catch (\Exception $e) {
            $this->response->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function update(array $params): void
    {
        try {
            $id = (int)$params['id'];
            $data = $this->request->getBody();
            $this->clienteService->actualizarCliente($id, $data);
            $cliente = $this->clienteService->obtenerCliente($id);

            $this->response->json(['success' => true, 'data' => $cliente], 200);
        } catch (\Exception $e) {
            $this->response->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function destroy(array $params): void
    {
        try {
            $id = (int)$params['id'];
            $resultado = $this->clienteService->eliminarCliente($id);

            if (!$resultado) {
                $this->response->json(['success' => false, 'message' => 'Cliente no encontrado'], 404);
                return;
            }

            $this->response->json(['success' => true, 'message' => 'Cliente eliminado correctamente'], 200);
        } catch (\Exception $e) {
            $this->response->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function checkDependencies(array $params): void
    {
        try {
            $id = (int)$params['id'];
            
            // Verificar que el cliente existe
            $cliente = $this->clienteService->obtenerCliente($id);
            if (!$cliente) {
                $this->response->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
                return;
            }

            // Obtener dependencias del cliente (facturas, pedidos, etc)
            $dependencies = $this->clienteService->obtenerDependencias($id);
            
            // Determinar si se puede eliminar
            $canDelete = empty($dependencies);
            $hardAllowed = $canDelete; // Solo hard delete si no hay dependencias

            $this->response->json([
                'success' => true,
                'data' => [
                    'canDelete' => $canDelete,
                    'hardAllowed' => $hardAllowed,
                    'dependencies' => $dependencies,
                    'cliente' => $cliente
                ]
            ], 200);

        } catch (\Exception $e) {
            $this->response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
