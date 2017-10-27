<?php

require_once '../vendor/autoload.php';

use primus852\BackupHealth;
use primus852\JsonResponse;
use primus852\SimpleCrypt;
use primus852\Config;

$request = $_REQUEST;

if (!$template = $request['template']) {
    return new JsonResponse(array(
        'result' => 'error',
        'message' => 'Could not determine template',
        'extra' => array(
            'request' => $request
        )
    ));
}

$bh = new BackupHealth();

/* Get Database Table for template if applicable */
switch ($template) {

    case 'add_project':
        $params = array(
            'sc' => new SimpleCrypt()
        );
        break;
    case 'add_mysql':
        $params = array(
            'sc' => new SimpleCrypt(),
            'id' => $request['id'],
        );
        break;
    case 'add_ping':
        $params = array(
            'sc' => new SimpleCrypt(),
            'id' => $request['id'],
        );
        break;
    case 'edit_project':
        $data = $bh->query_by_id('app_projects', $request['id']);
        $params = array(
            'sc' => new SimpleCrypt(),
            'bh' => new BackupHealth(),
            'data' => $data,
        );
        break;
    default:
        $params = array();
        break;

}

$content = $bh->getTemplate('../templates/' . $template . '.template.php', $params);

echo $content;
die;
