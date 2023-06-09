<?php

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/tickets.tpl.php');
require_once(__DIR__ . '/../templates/cards.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/client.class.php');
$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: landing_page.php'));
}
$db = getDatabaseConnection();

$user = Client::getById($db, $session->getId());
if (!$user) {
    die(header('Location: ../pages/main_page.php'));
}
$type = Client::getType($db, $user->id);
$filters = Ticket::getFilters($db);
$tickets = Ticket::getByUser($db, $user->id);

output_header($session, $type);
drawTitle("My Tickets", "ticket");
drawFilterMenu($filters, 'my');
drawCardContainer();
output_footer();
?>
