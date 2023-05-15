<?php
declare(strict_types=1);


require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/agent.class.php');
$session = new Session();
$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $page = intval($_GET['page']);

  if (isset($page)) {
    $data = Department::filterDepartments($db, $page);
    http_response_code(200); // OK
    echo json_encode($data);
  } else {
    $data = Department::filterDepartments($db);
    http_response_code(200); // OK
    echo json_encode($data);
  }
  
  // $departments = Department::getDepartments($db);
  // http_response_code(200); // OK
  // echo json_encode($departments);
}