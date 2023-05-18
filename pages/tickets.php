<?php

require_once(__DIR__ . '/../templates/common.tpl.php');

require_once(__DIR__ . '/../templates/tickets.tpl.php');
require_once(__DIR__ . '/../templates/cards.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: landing_page.php'));
}
$db = getDatabaseConnection();
$userType = Client::getType($db, $session->getId());
if ($userType === 'Client') {
    die(header('Location: main_page.php'));
}
$filters = Ticket::getFilters($db);

output_header($session, $userType);
if ($userType === 'Admin') {
    drawTitle("All Tickets", "ticket");
    drawFilterMenu($filters, 'all-admin');
}
else if ($userType === 'Agent') {
    drawTitle("My Department's tickets", "ticket");
    drawFilterMenu($filters, 'all-agent');
}

// $tickets = Ticket::getTickets($db);
// $tickets = Ticket::filter($db);
// drawTicketsTable($tickets, 'All Tickets');

drawCardContainer();
output_footer();
?>
