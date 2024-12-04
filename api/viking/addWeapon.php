<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/dao/viking.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/viking/service.php';

header('Content-Type: application/json');

if (!methodIsAllowed('update')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();

if (!isset($_GET['id'])) {
    returnError(400, 'Missing parameter : id');
}

$id = intval($_GET['id']);

if (isset($data['weaponId'])) {
    $weaponId = $data['weaponId'];
    
    if (!weaponExists($weaponId)) {
        returnError(404, 'Weapon not found');
    }

    if (!vikingExists($id)) {
        returnError(404, 'Viking not found');
    }

    $updated = addWeaponToViking($id, $weaponId);
    
    if ($updated) {
        http_response_code(204);
    } else {
        returnError(500, 'Could not add weapon to the viking');
    }
} else {
    returnError(412, 'Mandatory parameter : weaponId');
}