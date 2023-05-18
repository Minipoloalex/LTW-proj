<?php
declare(strict_types=1);
// ini_set('xdebug.overload_var_dump', 'off');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
$session = new Session();
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
    $data = Ticket::filter($db, $status, $priorities, $hashtags, $agents, $departments);
    http_response_code(200); // OK
    echo json_encode($data);
  // }
}



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $page = intval($_GET['page']);

  if (isset($page)) {
    $a = $_GET['agents'];
    $agents = ($a == "") ? [] : explode(',', $a);
    $d = $_GET['departments'];
    $departments = ($d == "") ? [] : explode(',', $d);
    $h = $_GET['hashtags'];
    $hashtags = ($h == "") ? [] : explode(',', $h);
    $p = $_GET['priorities'];
    $priorities = ($p == "") ? [] : explode(',', $p);
    $s = $_GET['status'];
    $status = ($s == "") ? [] : explode(',', $s);

    error_log('HERE'.implode($agents));
    error_log('HERE'.$departments);

    $data = Ticket::filter($db, $status, $priorities, $hashtags, $agents, $departments, $page);
    http_response_code(200); // OK
    echo json_encode($data);
  } else {
    $data = Ticket::filter($db);
    http_response_code(200); // OK
    echo json_encode($data);
  }
  
} 
// else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//   // Handle AJAX request
//   $tickets = Ticket::filter($db);
//   http_response_code(200); // OK
//   echo json_encode($tickets);
// }
?>