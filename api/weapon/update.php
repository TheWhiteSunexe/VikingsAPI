<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/dao/weapon.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/weapon/service.php';

header('Content-Type: application/json');

if (!methodIsAllowed('update')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();

if (!isset($_GET['id'])) {
    returnError(400, 'Missing parameter: id');
}

$id = intval($_GET['id']); // Convertir en entier pour éviter les injections ou erreurs

if ($data && validateMandatoryParams($data, ['type', 'damage'])) {
    verifyWeapon($data); // Valider les données fournies

    // Appeler la fonction correcte pour mettre à jour l'arme
    $updated = updateWeapon($id, $data['type'], $data['damage']);
    
    if ($updated === 1) {
        http_response_code(204); // Succès, aucune réponse supplémentaire nécessaire
    } elseif ($updated === 0) {
        returnError(404, 'Weapon not found'); // Arme non trouvée
    } else {
        returnError(500, 'Could not update the weapon'); // Erreur inconnue
    }
} else {
    returnError(412, 'Mandatory parameters: type, damage'); // Paramètres manquants ou invalides
}
