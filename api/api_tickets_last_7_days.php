<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../utils/session.php');
function handle_closed_tickets_last_7_days(PDO $db, Session $session) {
    if (Client::getType($db, $session->getId()) !== 'Admin') {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'You are not an admin.'));
        exit();
    }
    $tickets = Ticket::getClosedTicketsLast7Days($db);

    echo json_encode(array(
        'success' => 'Tickets retrieved successfully.',
        'tickets' => $tickets,
        'csrf' => $session->getCsrf()
    ));
}
function handle_open_tickets_last_7_days(PDO $db, Session $session) {
    if (Client::getType($db, $session->getId()) !== 'Admin') {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'You are not an admin.'));
        exit();
    }
    $tickets = Ticket::getCreatedTicketsLast7Days($db);

    echo json_encode(array(
        'success' => 'Tickets retrieved successfully.',
        'tickets' => $tickets,
        'csrf' => $session->getCsrf()
    ));
}

?>
