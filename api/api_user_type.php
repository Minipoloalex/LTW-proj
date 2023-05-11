<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../database/connection.db.php');


$session = new Session();
$db = getDatabaseConnection();

/* get user type */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'User not logged in'));
        exit();
    }

    $type = Client::getType($db, $session->getId());

    echo json_encode(array(
        'type' => $type
    ));
    exit();
}