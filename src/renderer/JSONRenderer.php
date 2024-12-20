<?php
namespace BestLoc\Renderer;
use Psr\Http\Message\ResponseInterface;
final class JSONRenderer {
    public static function json(ResponseInterface $response, mixed $data = null, int $status = 200): ResponseInterface {
        $response = $response->withStatus($status)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($data,JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR));
        return $response;
    }
}