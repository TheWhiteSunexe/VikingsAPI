<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/utils/server.php';

function verifyWeapon($Weapon): bool {
    $name = trim($Weapon['type']);
    if (strlen($name) < 3) {
        returnError(412, 'Type must be at least 3 characters long');
    }

    $health = intval($Weapon['damage']);
    if ($health < 1) {
        returnError(412, 'Damage must be a positive and non zero number');
    }
}