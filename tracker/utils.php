<?php

function respond(int $status = 200, array $obj = ['ok' => true]) {
    $obj['status'] = $status;
    $body = json_encode($obj);
    http_response_code($status);
    header('content-type', 'application/json');
    header('content-length', strlen($body));
    echo $body;
}

function respond_bool(int $ok = 1) {
    respond($ok ? 200 : 500, ['ok' => $ok]);
}

function get_request_body() {
    return json_decode(file_get_contents('php://input'), true);
}
