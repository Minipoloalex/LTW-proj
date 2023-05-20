<?php
declare(strict_types=1);
require_once(__DIR__ . '/../../utils/session.php');
require_once(__DIR__ . '/../../utils/validate.php');
require_once(__DIR__ . '/../../database/ticket.class.php');
require_once(__DIR__ . '/../../database/connection.db.php');

function handle_delete_ticket(Session $session, PDO $db, ?string $dataTicketID) {
    if (!is_valid_ticket_id($db, $dataTicketID)) {
        http_response_code(400); // Bad Request
        echo json_encode(array('error' => 'Invalid ticket ID'));
        exit();
    }
    $ticketID = intval($dataTicketID);
    $success = Ticket::deleteTicket($db, $ticketID);
    if(!$success) {
        http_response_code(500); // Internal server error
        echo json_encode(array('error' => 'Ticket could not be deleted'));
        exit();
    }
    http_response_code(200); // OK
    echo json_encode(array('success' => 'Ticket deleted'));
    exit();
}

?>