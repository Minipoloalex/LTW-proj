<?php

require_once(__DIR__ . '/../templates/common.tpl.php');

require_once(__DIR__ . '/../templates/tickets.tpl.php');
require_once(__DIR__ . '/../templates/cards.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
$db = getDatabaseConnection();

$filters = Ticket::getFilters($db);
output_header();
drawFilterMenu($filters);
$tickets = Ticket::getTickets($db);
drawTicketsTable($tickets, 'All Tickets');
drawCardContainer();
output_footer();
?>
