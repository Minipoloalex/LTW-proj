<?php
declare(strict_types=1);
/*
 * Updates related with Save button of users_list.php
 * for upgrading user type and for changing/assigning departments
 */

// TODO: not working atm (depends on pagination for users_list)

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../pages/landing_page.php'));
}
if (!Client::isAdmin($db, $session->getId())) {
    die(header('Location: ../pages/main_page.php'));
}

// TODO: csrf check
require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();

require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/client.class.php');

require_once(__DIR__ . '/../utils/validate.php');

if (!is_valid_string($_POST['user_ids'])) {
    die(header('Location: ../pages/main_page.php'));
}
$users = explode(',', $_POST['user_ids']);
if (count($users) == 0) {
    die(header('Location: ../pages/main_page.php'));
}

$usersID = [];
$usersBeforeType = [];
$usersNextType = [];
$usersDepartments = [];

foreach ($users as $userID) {
    if (!is_valid_id($userID)) {
        die(header('Location: ../pages/main_page.php'));
    }
    $usersID[] = intval($userID);

    $beforeType = Client::getType($db, intval($userID));
    $userBeforeType[] = $beforeType;

    $nextTypeInput = 'type-' . $userID;
    $departmentInput = 'department-' . $userID;

    if (!is_valid_string($_POST[$nextTypeInput]) || !is_valid_type($_POST[$nextTypeInput])) {
        die(header('Location: ../pages/main_page.php'));
    }
    $usersNextType[] = $_POST[$nextTypeInput];

    if ($beforeType !== 'Client') {
        
        if (!empty($_POST[$departmentInput]) && !is_valid_id($_POST[$departmentInput])) {
            die(header('Location: ../pages/main_page.php'));
        }
        $usersDepartments[] = empty($_POST[$departmentInput]) ? NULL : intval($_POST[$departmentInput]);
    }
    else $usersDepartments[] = NULL;
}

for ($i = 0; $i < count($usersID); $i++) {
    $userID = $usersID[$i];
    $beforeType = $usersBeforeType[$i];
    $nextType = $usersNextType[$i];
    $userDpt = $usersDepartments[$i];

    if ($beforeType !== 'Client') {
        Agent::updateDepartment($db, $userID, $userDpt);
    }

    if ($beforeType === 'Client' && $nextType === 'Agent') {
        Client::upgradeToAgent($db, $userID);
    }
    else if ($beforeType === 'Client' && $nextType === 'Admin') {
        Client::upgradeToAdminFromClient($db, $userID);
    }
    else if ($beforeType === 'Agent' && $nextType === 'Client') {
        Client::demoteToClient($db, $userID);
    }
    else if ($beforeType === 'Agent' && $nextType === 'Admin') {
        Client::upgradeToAdminFromAgent($db, $userID);
    }
    else if ($beforeType === 'Admin' && $nextType === 'Client') {
        Client::demoteToClient($db, $userID);
    }
    else if ($beforeType === 'Admin' && $nextType === 'Agent') {
        Client::demoteToAgent($db, $userID);
    }
}

?>
