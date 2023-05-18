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

  $all = $_GET['all'];
  $page = intval($_GET['page']);

  if (isset($all)) {
    $data = Department::getDepartments($db);
    http_response_code(200); // OK
    echo json_encode($data);
  } else {
    if (isset($page)) {
      $data = Department::filterDepartments($db, $page);
      http_response_code(200); // OK
      echo json_encode($data);
    } else {
      $data = Department::filterDepartments($db);
      http_response_code(200); // OK
      echo json_encode($data);
    }
  }
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $department_name = $_POST['department_name'];
  error_log("department_name: " . $department_name);
  if (isset($department_name)) {
    /*check if already exists using getByName from deparment.class*/

    if (Department::getByName($db, $department_name)){
      http_response_code(409); // Conflict
      echo json_encode(array('error' => 'Department already exists.'));
      exit();
    }
  
    Department::addDepartment($db, $department_name);
    http_response_code(201); // Created
    echo json_encode(array('message' => 'Department created.'));
  } else {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Invalid request parameters.'));
  }
  exit();
}

http_response_code(405); // Method not allowed
echo json_encode(array('error' => 'Invalid request method.'));


?>