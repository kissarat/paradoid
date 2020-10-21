<?php
require './db.php';
require './utils.php';

function index()
{
    $sort = $_GET['sort'] ?? 'id';
    $columns = ['id', 'scheme', 'hostname', 'port', 'pathname', 'query', 'fragment', 'created_at'];
    if (!in_array($sort, $columns)) {
        return respond(400, [
            'ok' => 0,
            'columns' => $columns,
            'message' => 'Column does not exists'
        ]);
    }
    $order = strtoupper($_GET['order'] ?? 'ASC');
    if (!('ASC' === $order || 'DESC' === $order)) {
        return respond(400, [
            'ok' => 0,
            'message' => 'Invalid order'
        ]);
    }
    $skip = +($_GET['skip'] ?? '0');
    $take = +($_GET['take'] ?? '100');
    $count = +($_GET['count'] ?? '-1');
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

handle_request('GET', function() {
    index();
});
