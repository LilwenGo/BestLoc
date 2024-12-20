<?php
namespace BestLoc\Controller;

use BestLoc\Service\UserService;
use BestLoc\Renderer\JSONRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
    private UserService $service;

    public function __construct() {
        $this->service = new UserService();
    }

    public function register(Request $request, Response $response): Response {
        $body = $request->getParsedBody();
        $errors = $this->validateUserData($body);
        if(!empty($errors)) {
            return JSONRenderer::json($response, ['errors' => $errors], 400);
        }
        $user = $this->service->find($body['email']);
        if($user) {
            return JSONRenderer::json($response, ['error' => "Email already used !"], 400);
        }
        $res = $this->service->create($body['email'], $body['password']);
        if(!$res) {
            return JSONRenderer::json($response, ['error' => "Register failed !"], 500);
        }
        return JSONRenderer::json($response, ['message' => "Registered successfully !", '_id' => $res->__toString(), 'email' => $body['email']]);
    }

    public function login(Request $request, Response $response): Response {
        return JSONRenderer::json($response, []);
    }

    private function validateUserData(array $data): array{
        $errors = [];
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email';
        }
        if (isset($data['password']) && (!is_string($data['password']) || strlen($data['password']) < 8)) {
            $errors[] = 'Password must be at least 8 characters long';
        }
        return $errors;
    }
}