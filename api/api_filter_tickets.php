<?php
declare(strict_types=1);
// ini_set('xdebug.overload_var_dump', 'off');
require_once(__DIR__ . '/../templates/common.tpl.php');
$session = new Session();
require_once(__DIR__ . '/../templates/tickets.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
$db = getDatabaseConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Handle AJAX request
  $filterValues = json_decode(file_get_contents('php://input'), true);
  $agents = $filterValues['agents'];
  $departments = $filterValues['departments'];
  $hashtags = $filterValues['hashtags'];
  $priorities = $filterValues['priorities'];
  $status = $filterValues['status'];

  // if (empty($agents) && empty($departments) && empty($hashtags) && empty($priorities) && empty($status)) {
  //   // Get all tickets if no filters are selected
  //   $tickets = Ticket::getTickets($db);
  //   http_response_code(200); // OK
  //   echo json_encode($tickets);
  // } else {
    // Get filtered tickets
    $tickets = Ticket::filter($db, $status, $priorities, $hashtags, $agents, $departments);
    http_response_code(200); // OK
    echo json_encode($tickets);
  // }
}
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Handle AJAX request
  $tickets = Ticket::filter($db);
  http_response_code(200); // OK
  echo json_encode($tickets);
}
?>