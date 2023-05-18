<?php
declare(strict_types=1);
// ini_set('xdebug.overload_var_dump', 'off');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
$session = new Session();
$db = getDatabaseConnection();

function handle_filter_tickets(PDO $db, ?int $userID, string $pageType, ?string $dataAgents, ?string $dataDepartments,
?string $dataHashtags, ?string $dataPriorities, ?string $dataStatus, ?int $page) {
  $a = $dataAgents;
  $agents = ($a == "") ? [] : explode(',', $a);
  $d = $dataDepartments;
  $departments = ($d == "") ? [] : explode(',', $d);
  $h = $dataHashtags;
  $hashtags = ($h == "") ? [] : explode(',', $h);
  $p = $dataPriorities;
  $priorities = ($p == "") ? [] : explode(',', $p);
  $s = $dataStatus;
  $status = ($s == "") ? [] : explode(',', $s);

  $pageType = $_GET['pageType'];

  error_log('HERE'.implode($agents));
  error_log('HERE'.$departments);

  $data = Ticket::filter($db, $userID, $pageType, $status, $priorities, $hashtags, $agents, $departments, $page);
  http_response_code(200); // OK
  echo json_encode($data);
  
  // This was on an else for isset($page) but $page was always set (intval(null/string) = 0)
  // } else {
  //   $data = Ticket::filter($db);
  //   http_response_code(200); // OK
  //   echo json_encode($data);
  // }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // TODO: security for this and isset($page) does nothing
  $page = intval($_GET['page']);
  $pageType = $_GET['pageType'];
  $userID = $session->getId();
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

    // $pageType = $_GET['pageType'];

    error_log('HERE'.implode($agents));
    error_log('HERE'.$departments);

    $data = Ticket::filter($db, $userID, $pageType, $status, $priorities, $hashtags, $agents, $departments, $page);
    http_response_code(200); // OK
    echo json_encode($data);
  } else {
    $data = Ticket::filter($db, $userID, $pageType);
    http_response_code(200); // OK
    echo json_encode($data);
  }
}
?>