<?php

namespace App\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class TokenGenerator
{
    protected $params;

    /**
     * Service constructor
     */
    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
    }

    public function generate(array $payload): string
    {
        return JWT::encode([
            "iss" => $_ENV['APP_NAME'],
            "aud" => $_ENV['APP_URL'],
            "iat" => strtotime(date("Y-m-d H:i:s")),
            "acb" => $payload
        ], $_ENV['APP_SECRET']);
    }

    public function decode(string $token)
    {
        try {
            if (!preg_match('/Bearer\s(\S+)/', $token, $matches)) {
                return false;
            }

            return JWT::decode($matches[1], $_ENV['APP_SECRET'], ['HS256']);
        } catch (SignatureInvalidException $e) {
            return false;
        }
    }
}
