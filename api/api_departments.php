<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/agent.class.php');
require_once(__DIR__ . '/handlers/api_common.php');


$session = new Session();
$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $all = $_GET['all'] ?? NULL;
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
  handle_check_logged_in($session);
  handle_check_csrf($session, $_POST['csrf']);
  handle_check_admin($session, $db);
  if (!is_valid_department_name($_POST['department_name'])) {
    http_response_code(400); // Bad request
    echo_json_csrf($session, array('error' => 'Invalid department name. No special characters allowed'));
    exit();
  }
  $department_name = $_POST['department_name'];
  error_log("department_name: " . $department_name);
  if (isset($department_name)) {
    if (Department::getByName($db, $department_name) !== NULL){
      http_response_code(409); // Conflict
      echo_json_csrf($session, array('error' => 'Department already exists.'));
      exit();
    }
    $id = Department::addDepartment($db, $department_name);
    http_response_code(201); // Created
    echo_json_csrf($session, array(
      'success' => 'Created department "' . $department_name . '"',
      'department_name' => $department_name,
      'department_id' => $id
    ));
  } else {
    http_response_code(400); // Bad request
    echo_json_csrf($session, array('error' => 'Invalid request parameters.'));
  }
  exit();
}

http_response_code(405); // Method not allowed
echo json_encode(array('error' => 'Invalid request method.'));

?>
