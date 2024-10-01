<?php
require_once('./vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


function verifyToken() {
    $headers = array_change_key_case(getallheaders(), CASE_LOWER);


    if (!isset($headers['authorization'])) {
        http_response_code(401);
        echo json_encode(["message" => "Authorization header missing"]);
        exit;
    }

    $authHeader = $headers['authorization'];
    list($jwt) = sscanf($authHeader, 'Bearer %s');

    if (!$jwt) {
        http_response_code(401);
        echo json_encode(["message" => "Token not provided"]);
        exit;
    }

    $key = $_ENV['ACCESS_TOKEN_SECRET'];

    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["message" => "Invalid token"]);
        exit;
    }
}

verifyToken();

