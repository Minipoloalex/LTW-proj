<?php

require_once(__DIR__ . '/../templates/common.tpl.php');

require_once(__DIR__ . '/../templates/tickets.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
$db = getDatabaseConnection();

$filters = Ticket::getFilters($db);
output_header();
drawFilterMenu($filters);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  echo ('hello');
  // Handle AJAX request
  $filterValues = json_decode(file_get_contents('php://input'), true);
  var_dump($filterValues);
  $agents = $filters['agents'];
  $departments = $filters['departments'];
  $hastags = $filters['hastags'];
  $priorities = $filters['priorities'];
  $status = $filters['status'];
  
  if (empty($agents) && empty($departments) && empty($hastags) && empty($priorities) && empty($status)) {
      // Draw all tickets table if no filters are selected
      $tickets = Ticket::getTickets($db);
      drawTicketsTable($tickets, 'All Tickets');
      output_footer();
  } else {
      // Get filtered tickets
      // $tickets = Ticket::getTickets($db);
      $tickets = Ticket::filter($db, $status, $priorities, $hastags, $agents, $departments);

      // Draw filtered tickets table
      drawTicketsTable($tickets, 'Filtered Tickets');
      output_footer();
  }
} else {
  echo ('bye');
  // Draw all tickets table
  $tickets = Ticket::getTickets($db);
  drawTicketsTable($tickets, 'All Tickets');
  output_footer();
}

?>
