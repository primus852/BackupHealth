<?php

require_once '../vendor/autoload.php';

use primus852\JsonResponse;
use primus852\Database;
use primus852\BackupHealth;

$request = $_REQUEST;

if (!$endpoint = $request['endpoint']) {
    return new JsonResponse(array(
        'result' => 'error',
        'message' => 'Could not determine endpoint',
        'extra' => array(
            'request' => $request
        )
    ));
}

switch ($endpoint) {

    case 'removeById':
        $db = new Database();
        $result = $db->remove_by_id($request['values']);
        break;
    case 'addEntry':
        $db = new Database();
        $result = $db->add_list_entry($request['values'], $request['table'], $request['special']);
        break;
    case 'updateEntry':
        $db = new Database();
        $result = $db->update_list_entry($request['values'], $request['id'], $request['table']);
        break;
    case 'pingSite':
        $bh = new BackupHealth();
        $bh->ping_site($request['id']);
        break;
    case 'pingMySql':
        $bh = new BackupHealth();
        $bh->mysql_status($request['id']);
        break;
    case 'benchmarkMySql':
        $bh = new BackupHealth();
        $bh->mysql_benchmark($request['id']);
        break;
    case 'statusSite':
        $bh = new BackupHealth();
        $bh->get_status_code($request['id']);
        break;
    default:
        return new JsonResponse(array(
            'result' => 'error',
            'message' => 'Unknown endpoint',
            'extra' => array(
                'request' => $request
            )
        ));
}
