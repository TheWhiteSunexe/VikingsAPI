<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/utils/server.php';

function verifyWeapon(array $weapon): bool {
    $type = trim($weapon['type']);
    if (strlen($type) <= 2) {
        returnError(412, 'Type must be at least 2 characters long');
    }

    $damage = intval($weapon['damage']);
    if ($damage < 1) {
        returnError(412, 'Damage must be a positive and non zero number');
    }
    return true;
}