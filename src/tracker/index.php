<?php
require './db.php';
require './utils.php';

$secret = trim(file_get_contents('../../secret.txt'));

function index()
{
    $skip = $_GET['skip'] ?? 0;
    $take = $_GET['take'] ?? 100;
    $sort = $_GET['sort'] ?? 'id';
    $order = $_GET['order'] ?? 'ASC';
    $count = $_GET['count'] ?? -1;
    $db = new Database();
    $result = $db->query("select * from `request` order by $sort $order limit :take offset :skip", [
        'skip' => $skip,
        'take' => $take
    ]);
    if ('1' === $count) {
        $c = $db->query('select count(*) from `request`');
        $r = $c->fetchAll(PDO::FETCH_COLUMN);
        $count = $r[0];
    }
    respond(200, [
        'count' => +$count,
        'items' => $result->fetchAll(PDO::FETCH_ASSOC)
    ]);
}

if ($secret === hash('sha512', $_GET['auth'] ?? '')) {
    if ('GET' === $_SERVER['REQUEST_METHOD']) {
        index();
    } else {
        respond(405, ['ok' => 0]);
    }
} else {
    respond(403, ['ok' => 0]);
}
