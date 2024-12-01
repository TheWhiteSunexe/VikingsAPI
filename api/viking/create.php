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

/*
if (validateMandatoryParams($data, ['name', 'health', 'attack', 'defense'])) {
    verifyViking($data);

    $newVikingId = createViking($data['name'], $data['health'], $data['attack'], $data['defense']);
    if (!$newVikingId) {
        returnError(500, 'Could not create the viking');
    }
    echo json_encode(['id' => $newVikingId]);
    http_response_code(201);
} else {
    returnError(412, 'Mandatory parameters : name, health, attack, defense');
}*/

if (validateMandatoryParams($data, ['name', 'health', 'attack', 'defense'])) {
    verifyViking($data);

    // Vérification de l'ID de l'arme : si l'ID est passé, on vérifie son existence, sinon on utilise l'ID 1 par défaut
    $weaponId = isset($data['weaponId']) ? $data['weaponId'] : 1; // Si 'weaponId' est fourni, on l'utilise, sinon on utilise 1 par défaut

    if (!weaponExists($weaponId)) {
        returnError(404, 'Weapon not found');
    }

    // Création du viking avec l'arme vérifiée
    $newVikingId = createViking($data['name'], $data['health'], $data['attack'], $data['defense'], $weaponId);
    if (!$newVikingId) {
        returnError(500, 'Could not create the viking');
    }

    echo json_encode(['id' => $newVikingId]);
    http_response_code(201);
} else {
    returnError(412, 'Mandatory parameters : name, health, attack, defense');
}


