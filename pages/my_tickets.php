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

$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: landing_page.php'));

$tickets = Ticket::getByUser($db, $session->getId());

var_dump($tickets);
drawTicketsTable($tickets, 'My Tickets');
drawCardContainer();
output_footer();
?>