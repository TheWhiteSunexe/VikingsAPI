<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/dao/weapon.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/utils/server.php';



if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

if (!isset($_GET['id'])) {
    returnError(400, 'Missing parameter : id');
}

$weapon = findOneWeapon($_GET['id']);
if (!$weapon) {
    returnError(404, 'Weapon not found');
}
echo json_encode($weapon);

