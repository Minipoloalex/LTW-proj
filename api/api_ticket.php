<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/handlers/api_common.php');
require_once(__DIR__ . '/handlers/api_tickets_last_7_days.php');
require_once(__DIR__ . '/handlers/api_close_ticket.php');
require_once(__DIR__ . '/handlers/api_update_ticket.php');

$session = new Session();
$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    handle_check_logged_in($session);
    if (isset($_GET['page'])) {
        $page = intval($_GET['page']);
        handle_filter_tickets($db, $_GET['agents'], $_GET['departments'], $_GET['hashtags'], $_GET['priorities'], $_GET['status'], $page);
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
    // NOT DONE YET
    handle_check_logged_in($session);
    handle_check_csrf($session, $_POST['csrf']);
    $db = getDatabaseConnection();
    if (!isset($_POST['request']) || empty($_POST['request'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Missing request parameter'));
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    handle_check_logged_in($session);
    handle_check_csrf($session, $_GET['csrf']);

    $db = getDatabaseConnection();
    handle_api_close_ticket($session, $db, $_GET['ticketID'], $_GET['status']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    handle_check_logged_in($session);
    $input = file_get_contents('php://input');
    parse_str($input, $_POST);
    handle_check_csrf($session, $_POST['csrf']);
    $db = getDatabaseConnection();
    handle_update_ticket($session, $db, $_POST['hashtags'], $_POST['ticketID'], $_POST['department'], $_POST['agent'], $_POST['priority']);
    exit();
}
http_response_code(400); // Bad request
echo json_encode(array('error' => 'Invalid request.'));
?>
