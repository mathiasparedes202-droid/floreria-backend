<?php

namespace App\Controllers;

use App\Services\RolService;
use Core\Controller;
use App\Services\AuthService;
use App\Services\PasswordResetService;
use Core\Request;
use Core\Response;

class AuthController extends Controller
{
    private $authService;
    private RolService $rolService;
    private PasswordResetService $passwordResetService;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->authService = new AuthService();
        $this->rolService = new RolService();
        $this->passwordResetService = new PasswordResetService();
    }

    public function login()
    {
        $data = $this->request->getBody(); // espera { email, password }

        if (empty($data['email']) || empty($data['password'])) {
            return $this->respondError('Email y password son requeridos', 422);
        }

        $user = $this->authService->attempt($data['email'], $data['password']);

        if (!$user) {
            return $this->respondError('Credenciales incorrectas', 401);
        }

        $rol = $this->rolService->obtenerRol($user['id_rol']);

        $user['rol'] = $rol;

        $token = $this->authService->generateToken($user);

        return $this->respondSuccess('Login exitoso', [
            'user' => $user,
            'token' => $token
        ]);
    }

    public function me()
    {
        return $this->respondSuccess('Usuario autenticado', [
            'user' => $this->request->getUser()
        ]);
    }

    public function forgotPassword()
    {
        $data = $this->request->getBody();

        if (empty($data['email'])) {
            return $this->respondError('Email es requerido', 422);
        }

        try {
            $resetUrl = $this->passwordResetService->sendResetLink($data['email']);
            $response = 'Si el email existe, se ha enviado un enlace de recuperación';
            if (App::isDev() && $resetUrl) {
                $response .= '. En desarrollo: ' . $resetUrl;
            }
            return $this->respondSuccess($response);
        } catch (\Exception $e) {
            return $this->respondError('Error al procesar la solicitud', 500);
        }
    }

    public function resetPassword()
    {
        $data = $this->request->getBody();

        if (empty($data['token']) || empty($data['password'])) {
            return $this->respondError('Token y nueva contraseña son requeridos', 422);
        }

        try {
            $this->passwordResetService->resetPassword($data['token'], $data['password']);
            return $this->respondSuccess('Contraseña restablecida exitosamente');
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage(), 400);
        }
    }

    public function verifyToken()
    {
        $data = $this->request->getBody();

        if (empty($data['token'])) {
            return $this->respondError('Token es requerido', 422);
        }

        try {
            $resetData = $this->passwordResetService->verifyToken($data['token']);
            if ($resetData) {
                return $this->respondSuccess('Token válido');
            } else {
                return $this->respondError('Token inválido o expirado', 400);
            }
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage(), 400);
        }
    }

    public function logout()
    {
        return $this->respondSuccess('Logout exitoso');
    }
}
