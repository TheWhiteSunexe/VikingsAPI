<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tpApi/VikingsAPI/api/utils/database.php';
/*
function findOneViking(string $id) {
    $db = getDatabaseConnection();
    $sql = "SELECT id, name, health, attack, defense FROM viking WHERE id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id]);
    if ($res) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}*/

function findOneViking(string $id) {
    $db = getDatabaseConnection();
    $sql = "SELECT viking.id, viking.name, viking.health, viking.attack, viking.defense, weapon.id AS weaponId, weapon.type AS weapon_type
            FROM viking
            LEFT JOIN weapon ON viking.weaponId = weapon.id
            WHERE viking.id = :id";
    
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id]);
    
    if ($res) {
        $viking = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ajouter l'arme au format HATEOAS si elle existe
        if ($viking['weaponId']) {
            $viking['weapon'] = "/weapons/" . $viking['weaponId']; // Lien vers le détail de l'arme
        } else {
            $viking['weapon'] = ""; // Si pas d'arme
        }
        // Nettoyer les clés inutiles
        unset($viking['weaponId'], $viking['weapon_type']);
        return $viking;
    }
    return null;
}

/*
function findAllVikings (string $name = "", int $limit = 10, int $offset = 0) {
    $db = getDatabaseConnection();
    $params = [];
    $sql = "SELECT id, name, health, attack, defense FROM viking";
    if ($name) {
        $sql .= " WHERE name LIKE %:name%";
        $params['name'] = $name;
    }
    $sql .= " LIMIT $limit OFFSET $offset ";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);
    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}*/

function findAllVikings(string $name = "", int $limit = 10, int $offset = 0) {
    $db = getDatabaseConnection();
    $params = [];
    
    $sql = "SELECT viking.id, viking.name, viking.health, viking.attack, viking.defense, weapon.id AS weaponId, weapon.type AS weapon_type
            FROM viking
            LEFT JOIN weapon ON viking.weaponId = weapon.id";
    
    if ($name) {
        $sql .= " WHERE viking.name LIKE :name";
        $params['name'] = '%' . $name . '%';
    }
    
    $sql .= " LIMIT $limit OFFSET $offset";
    
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);
    
    if ($res) {
        $vikings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ajouter l'arme pour chaque viking
        foreach ($vikings as &$viking) {
            if ($viking['weaponId']) {
                $viking['weapon'] = "/weapons/" . $viking['weaponId']; // Lien vers le détail de l'arme
            } else {
                $viking['weapon'] = ""; // Si pas d'arme
            }
            // Nettoyer les clés inutiles
            unset($viking['weaponId'], $viking['weapon_type']);
        }
        
        return $vikings;
    }
    
    return null;
}
/*
function createViking(string $name, int $health, int $attack, int $defense) {
    $db = getDatabaseConnection();
    $sql = "INSERT INTO viking (name, health, attack, defense) VALUES (:name, :health, :attack, :defense)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['name' => $name, 'health' => $health, 'attack' => $attack, 'defense' => $defense]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}*/

function createViking(string $name, int $health, int $attack, int $defense, int $weaponId) {
    $db = getDatabaseConnection();
    $sql = "INSERT INTO viking (name, health, attack, defense, weaponId) 
            VALUES (:name, :health, :attack, :defense, :weaponId)";
    
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'name' => $name, 
        'health' => $health, 
        'attack' => $attack, 
        'defense' => $defense,
        'weaponId' => $weaponId
    ]);
    
    if ($res) {
        return $db->lastInsertId();
    }
    
    return null;
}

function weaponExists(int $weaponId) {
    $db = getDatabaseConnection();
    $sql = "SELECT 1 FROM weapon WHERE id = :weaponId LIMIT 1";
    
    $stmt = $db->prepare($sql);
    $stmt->execute(['weaponId' => $weaponId]);
    
    return $stmt->rowCount() > 0; // Retourne true si l'arme existe, false sinon
}


/*
function updateViking(string $id, string $name, int $health, int $attack, int $defense) {
    $db = getDatabaseConnection();
    $sql = "UPDATE viking SET name = :name, health = :health, attack = :attack, defense = :defense WHERE id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id, 'name' => $name, 'health' => $health, 'attack' => $attack, 'defense' => $defense]);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}*/

function updateViking(string $id, string $name, int $health, int $attack, int $defense, int $weaponId) {
    $db = getDatabaseConnection();

    // Mise à jour des informations du viking
    $sql = "UPDATE viking SET name = :name, health = :health, attack = :attack, defense = :defense, weaponId = :weaponId WHERE id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'id' => $id,
        'name' => $name,
        'health' => $health,
        'attack' => $attack,
        'defense' => $defense,
        'weaponId' => $weaponId
    ]);

    if ($res) {
        return $stmt->rowCount(); // Retourne le nombre de lignes affectées
    }

    return null;
}

function addWeaponToViking(int $viking_id, int $weaponId) {
    $db = getDatabaseConnection();

    // Mise à jour du viking avec l'ID de l'arme
    $sql = "UPDATE viking SET weaponId = :weaponId WHERE id = :viking_id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'viking_id' => $viking_id,
        'weaponId' => $weaponId
    ]);

    return $res; // Retourne true si la mise à jour a été effectuée
}


function vikingExists(int $id) {
    $db = getDatabaseConnection();
    $sql = "SELECT 1 FROM viking WHERE id = :id LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->rowCount() > 0; // Retourne true si le viking existe
}

function deleteViking(string $id) {
    $db = getDatabaseConnection();
    $sql = "DELETE FROM viking WHERE id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id]);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}