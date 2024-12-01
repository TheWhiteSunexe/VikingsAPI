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

// Vérification de la présence de l'arme à ajouter
if (isset($data['weaponId'])) {
    $weaponId = $data['weaponId'];
    
    // Vérification que l'arme existe
    if (!weaponExists($weaponId)) {
        returnError(404, 'Weapon not found');
    }

    // Vérification que le viking existe
    if (!vikingExists($id)) {
        returnError(404, 'Viking not found');
    }

    // Mise à jour du viking avec la nouvelle arme
    $updated = addWeaponToViking($id, $weaponId);
    
    if ($updated) {
        http_response_code(204); // Mise à jour réussie, pas de contenu à retourner
    } else {
        returnError(500, 'Could not add weapon to the viking');
    }
} else {
    returnError(412, 'Mandatory parameter : weaponId');
}