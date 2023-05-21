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

  if (isset($all)) {
    $data = Department::getDepartments($db);
    http_response_code(200); // OK
    echo json_encode($data);
  } else {
    if (isset($_GET['page'])) {
      $page = intval($_GET['page']);
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

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  handle_check_logged_in($session);
  handle_check_csrf($session, $_GET['csrf']);
  handle_check_admin($session, $db);

  $department_id = $_GET['id'] ?? NULL;
  if (!is_valid_department_id($db, $department_id)) {
    http_response_code(400); // Bad request
    echo_json_csrf($session, array('error' => 'Invalid department id parameter.'));
    exit();
  }
  $department = Department::getById($db, intval($department_id));
  if ($department === NULL) {
    http_response_code(404); // Not found
    echo_json_csrf($session, array('error' => 'Department not found.'));
    exit();
  }
  $departmentName = $department->departmentName;
  $departmentID = $department->departmentId;
  
  $success = Department::deleteDepartment($db, $departmentID);
  if (!$success) {
    http_response_code(500); // Internal server error
    echo_json_csrf($session, array('error' => 'Failed to delete department.'));
    exit();
  }
  http_response_code(200); // OK
  echo_json_csrf($session, array(
    'success' => 'Deleted department "' . $departmentName . '"'));
  exit();
}


http_response_code(405); // Method not allowed
echo json_encode(array('error' => 'Invalid request method.'));

?>
