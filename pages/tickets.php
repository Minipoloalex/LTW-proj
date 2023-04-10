<?php

require_once(__DIR__ . '/../templates/common.tpl.php');

require_once(__DIR__ . '/../templates/tickets.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
$f = true;

$db = getDatabaseConnection();
if($f) {
  $tickets = Ticket::filter($db, [], ["'high'"], [], [], []);
  $filters = Ticket::getFilters($db);
}
else {
$tickets = Ticket::getTickets($db);
}

output_header();
drawFilterMenu($filters);
drawTicketsTable($tickets, "Opened tickets");
output_footer();
?>
