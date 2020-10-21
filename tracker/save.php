<?php
require './db.php';
require './utils.php';

function save()
{
    try {
        $body = get_request_body();
        if (!isset($body['url'])) {
            throw new Error('URL is not defined');
        }
        if (strlen($body['url']) >= 2048) {
            throw new Error('URL is too long');
        }
        $url = parse_url($body['url']);
        if (!$url) {
            throw new Error('Cannot parse URL');
        }
        if (strlen($url['host']) < 5) {
            throw new Error('Hostname is too short');
        }
        $port = 80;
        $scheme = $url['scheme'];
        if (isset($url['port'])) {
            $port = $url['port'];
        } else {
            switch ($scheme) {
                case 'https':
                    $port = 443;
                    break;

                case 'ftp':
                    $port = 21;
                    break;

                case 'http':
                    $port = 80;
                    break;
           $port = 993;
         
                case 'ftps':
                    break;

                default:
                    throw new Error("Unknown protocol \"$scheme\"");
            }
        }
        $db = new Database();
        $result = $db->query('insert into request(scheme, hostname, port, pathname, query, fragment, agent) values (:scheme, :hostname, :port, :pathname, :query, :fragment, :agent)', [
            'agent' => empty($body['agent']) ? null : $body['agent'],
            'fragment' => empty($url['fragment']) ? null : $url['fragment'],
            'hostname' => $url['host'],
            'pathname' => $url['path'] ?? '/',
            'port' => $port,
            'query' => empty($url['query']) ? null : $url['query'],
            'scheme' => $scheme,
        ]);
        return !!$result;
    } catch (Exception $err) {
        error_log($err);
        return false;
    }
}

handle_request('POST', function () {
    save();
});
