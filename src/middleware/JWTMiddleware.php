<?php
namespace BestLoc\Middleware;

use Bestloc\Renderer\JsonRenderer;
use BestLoc\Service\UserService;
use Firebase\JWT\JWT;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;

class JWTMiddleware {
    private static JWTMiddleware $instance;
    private ResponseFactory $responseFactory;

    public function __construct() {
        $this->responseFactory = new ResponseFactory();
    }

    public static function initInstance(): void {
        self::$instance = new JWTMiddleware();
    }

    public static function getInstance(): JWTMiddleware {
        if(!isset(self::$instance)) self::initInstance();
        return self::$instance;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $token = $request->getHeader('Authorization');
        if ($token) {
            list($bearer, $jwt) = explode(" ", $token);
            if (strcasecmp($bearer, 'Bearer') === 0) {
                try {
                    $decoded = JWT::decode($jwt, $_ENV['KEY']);
                    if(!isset($decoded->email, $decoded->password)) {
                        throw new \Exception('Invalid JWT !');
                    }
                    $service = new UserService();
                    $user = $service->getByEmail($decoded->email);
                    if(!($user && password_verify($decoded->password, $user->getPassword()))) {
                        throw new \Exception('Invalid JWT !');
                    }
                    return $handler->handle($request);
                } catch (\Exception $e) {
                    return JsonRenderer::json($this->responseFactory->createResponse(), ['error' => $e], 403);
                }
            } else {
                return JsonRenderer::json($this->responseFactory->createResponse(), ['error' => 'Invalid token format !'], 400);
            }
        } else {
            return JsonRenderer::json($this->responseFactory->createResponse(), ['error' => 'Missing JWT !'], 401);
        }
    }
}