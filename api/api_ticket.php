<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');

require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');

require_once(__DIR__ . '/api_tickets_last_7_days.php');

$session = new Session();
$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'User not logged in.'));
        exit();
    }
    if (!$session->verifyCsrf($_GET['csrf'])) {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'Invalid CSRF token.'));
        exit();
    }
    if ($_GET['request'] === 'closedTicketsLast7Days') {
        handle_closed_tickets_last_7_days($db, $session);
        exit();
    }
    else if ($_GET['request'] === 'openTicketsLast7Days') {
        handle_open_tickets_last_7_days($db, $session);
        exit();
    }
}

http_response_code(400); // Bad request
echo json_encode(array('error' => 'Invalid request.'));
?>
