<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../utils/session.php');
require_once(__DIR__ . '/../../utils/validate.php');
require_once(__DIR__ . '/../../database/connection.db.php');
require_once(__DIR__ . '/../../database/ticket.class.php');

function handle_filter_tickets(PDO $db, int $userID, ?string $dataPageType, ?string $dataAgents, ?string $dataDepartments,
?string $dataHashtags, ?string $dataPriorities, ?string $dataStatus, ?string $dataPage) {
  error_log(print_r($dataPageType, true));
  error_log(print_r($dataAgents, true));
  error_log(print_r($dataHashtags, true));
  error_log(print_r($dataPriorities, true));
  error_log(print_r($dataStatus, true));
  error_log(print_r($dataPage, true));
  error_log(print_r($dataDepartments, true));
  
  if (!is_valid_page_type($dataPageType)) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Invalid page type"));
    exit();
  }
  $pageType = $dataPageType;
  if (isset($dataPage)) {
    if (!is_valid_id($dataPage)) {
      http_response_code(400); // Bad Request
      echo json_encode(array("error" => "Invalid page"));
      exit();
    }
    $page = intval($dataPage);
    $a = $dataAgents ?? NULL;
    $agents = ($a == NULL) ? [] : explode(',', $a);
    $d = $dataDepartments ?? NULL;
    $departments = ($d == NULL) ? [] : explode(',', $d);
    $h = $dataHashtags ?? NULL;
    $hashtags = ($h == NULL) ? [] : explode(',', $h);
    $p = $dataPriorities ?? NULL;
    $priorities = ($p == NULL) ? [] : explode(',', $p);
    $s = $dataStatus ?? NULL;
    $status = ($s == NULL) ? [] : explode(',', $s);

    $data = Ticket::filter($db, $userID, $pageType, $status, $priorities, $hashtags, $agents, $departments, $page);
    http_response_code(200); // OK
    echo json_encode($data);
    exit();
  } else {
    $data = Ticket::filter($db, $userID, $pageType);
    http_response_code(200); // OK
    echo json_encode($data);
    exit();
  }
}
?>
