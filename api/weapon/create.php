<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/dao/viking.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/viking/service.php';

header('Content-Type: application/json');

if (!methodIsAllowed('create')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();

if (validateMandatoryParams($data, ['type', 'damage'])) {
    verifyWeapon($data);

    $newWeaponId = createWeapon($data['type'], $data['damage']);
    if (!$newWeaponId) {
        returnError(500, 'Could not create the Weapon');
    }
    echo json_encode(['id' => $newWeaponId]);
    http_response_code(201);
} else {
    returnError(412, 'Mandatory parameters : ');
}