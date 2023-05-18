<?php

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/tickets.tpl.php');
require_once(__DIR__ . '/../templates/cards.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/client.class.php');
$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: landing_page.php'));
$db = getDatabaseConnection();
$type = Client::getType($db, $session->getId());
if ($type === 'Client') {
    die(header('Location: main_page.php'));
}

$user = Client::getById($db, $session->getId());
if (!$user) {
    die(header('Location: main_page.php'));
}

$filters = Ticket::getFilters($db);
$tickets = Ticket::getByAgent($db, $user->id);

output_header($session, $type);
drawTitle("Assigned Tickets", "ticket");
drawFilterMenu($filters, 'assigned');
// drawTicketsTable($tickets, 'Assigned Tickets');
drawCardContainer();
output_footer();
?>
