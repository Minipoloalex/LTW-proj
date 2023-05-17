<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/api_tickets_last_7_days.php');
require_once(__DIR__ . '/api_close_ticket.php');
require_once(__DIR__ . '/handlers/common.api.php');

$session = new Session();
$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'User not logged in.'));
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_check_logged_in($session);
    handle_check_csrf($session, $_POST['csrf']);
    // if (!$session->verifyCsrf($_POST['csrf'])) {
    //     http_response_code(403); // Forbidden
    //     echo json_encode(array('error' => 'CSRF token invalid'));
    //     exit();
    // }
    $db = getDatabaseConnection();
    if (!isset($_POST['request']) || empty($_POST['request'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Missing request parameter'));
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    handle_check_logged_in($session);
    // if (!$session->isLoggedIn()) {
    //     http_response_code(401); // Unauthorized
    //     echo json_encode(array('error' => 'User not logged in'));
    //     exit();
    // }
    $input = file_get_contents('php://input');
    parse_str($input, $data);
    $db = getDatabaseConnection();

    handle_api_close_ticket($session, $db, $data['ticketID'], $data['csrf'], $data['status']);
    exit();
}
http_response_code(400); // Bad request
echo json_encode(array('error' => 'Invalid request.'));
?>
