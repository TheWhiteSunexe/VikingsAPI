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
/*
if (validateMandatoryParams($data, ['name', 'health', 'attack', 'defense'])) {
    verifyViking($data);

    $updated = updateViking($id, $data['name'], $data['health'], $data['attack'], $data['defense']);
    if ($updated == 1) {
        http_response_code(204);
    } elseif ($updated == 0) {
        returnError(404, 'Viking not found');
    } else {
        returnError(500, 'Could not update the viking');
    }
} else {
    returnError(412, 'Mandatory parameters : name, health, attack, defense');
}*/

if (validateMandatoryParams($data, ['name', 'health', 'attack', 'defense'])) {
    verifyViking($data);

    // Vérification de l'ID de l'arme, si spécifié
    $weaponId = isset($data['weaponId']) ? $data['weaponId'] : 1; // Si 'weaponId' est fourni, on l'utilise, sinon on utilise 1 par défaut

    // Vérification que l'arme existe, si elle est spécifiée
    if (!weaponExists($weaponId)) {
        returnError(404, 'Weapon not found');
    }

    // Mise à jour du viking avec l'arme
    $updated = updateViking($id, $data['name'], $data['health'], $data['attack'], $data['defense'], $weaponId);
    if ($updated == 1) {
        http_response_code(204);
    } elseif ($updated == 0) {
        returnError(404, 'Viking not found');
    } else {
        returnError(500, 'Could not update the viking');
    }
} else {
    returnError(412, 'Mandatory parameters : name, health, attack, defense');
}
