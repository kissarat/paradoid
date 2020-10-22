<?php

function respond(int $status = 200, $obj = null)
{
    http_response_code($status);
    header('access-control-allow-origin', '*');
    header('access-control-allow-headers', 'GET, POST, OPTIONS, DELETE');
    if (is_string($obj)) {
        $obj = [
            'ok' => 0,
            'message' => $obj
        ];
    }
    if (is_array($obj)) {
        $obj['status'] = $status;
        $body = json_encode($obj, JSON_UNESCAPED_UNICODE, 5);
        header('content-type', 'application/json');
        header('content-length', strlen($body));
        echo $body;
    }
}

function respond_bool(int $ok = 1)
{
    respond($ok ? 200 : 500, ['ok' => $ok]);
}

function get_request_body()
{
    return json_decode(file_get_contents('php://input'), true);
}

function handle_request(string $method, callable $handler)
{
    $secret_hash = getenv('SECRET_HASH');
    if ($secret_hash) {
        if (empty($_GET['auth']) && $secret_hash !== hash('sha512', $_GET['auth'])) {
            respond(401, 'Authorization required');
            return;
        }
    }

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'OPTIONS':
            respond(204);
            break;
        case $method:
            $handler();;
            break;
        default:
            respond(405, ['ok' => 0]);
            break;
    }
}
