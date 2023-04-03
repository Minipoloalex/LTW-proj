<?php

require_once(__DIR__ . '/../templates/common.tpl.php');

require_once(__DIR__ . '/../templates/tickets.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');


$db = getDatabaseConnection();
$tickets = Ticket::getTickets($db);

output_header();
// drawTicketsTable($tickets, "Opened tickets");
output_footer();
?>
