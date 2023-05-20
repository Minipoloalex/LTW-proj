<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/handlers/api_common.php');
require_once(__DIR__ . '/handlers/api_edit_profile.php');

$session = new Session();
$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  handle_check_logged_in($session);
  handle_check_admin($session, $db);

  if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
    $d = $_GET['department'] ?? NULL;
    $departments = ($d == NULL) ? [] : explode(',', $d);
    $u = $_GET['user_type'] ?? NULL;
    $user_type = ($u == NULL) ? [] : explode(',', $u);

    $data = Client::filter($db, $departments, $user_type, $page);
    http_response_code(200); // OK
    echo json_encode($data);
  } else {
    $data = Client::filter($db);
    http_response_code(200); // OK
    echo json_encode($data);
  }
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
  handle_check_logged_in($session);
  handle_check_admin($session, $db);
  handle_check_csrf($session, $_GET['csrf']);
  if (!is_valid_user_id($db, $_GET['id'])) {
    http_response_code(400); // Bad Request
    echo_json_csrf($session, array('error' => 'Invalid user ID'));
    exit();
  }
  if (!empty($_GET['department']) && !is_valid_department_id($db, $_GET['department'])) {
    http_response_code(400); // Bad Request
    echo_json_csrf($session, array('error' => 'Invalid department ID'));
    exit();
  }
  $id = intval($_GET['id']);
  $new_user_type = $_GET['user_type'];
  $department = ($_GET['department'] === '' ? NULL : intval($_GET['department']));
  $curr_user_type = Client::getType($db, $id);

  if ($curr_user_type === 'Client') {
    if ($new_user_type === 'Agent') {
      Client::upgradeToAgent($db, $id);
    } else if ($new_user_type === 'Admin') {
      Client::upgradeToAdminFromClient($db, $id);
    }

  } elseif ($curr_user_type === 'Agent') {
    if ($new_user_type === 'Client') {
      Client::demoteToClient($db, $id);
    } else if ($new_user_type === 'Admin') {
      Client::upgradeToAdminFromAgent($db, $id);
    }
  } else if ($curr_user_type === 'Admin') {
    if ($new_user_type === 'Client') {
      Client::demoteToClient($db, $id);
    } else if ($new_user_type === 'Agent') {
      Client::demoteToAgent($db, $id);
    }
  }
  if (isset($_GET['department']) && ($new_user_type === 'Agent' || $new_user_type === 'Admin')) {
    Agent::updateDepartment($db, $id, $department);
  }


  $user = Client::getByIdExpanded($db, $id);
  http_response_code(200); // OK
  echo_json_csrf($session, array(
    'id' => $user->id,
    'name' => $user->name,
    'username' => $user->username,
    'email' => $user->email,
    'department' => $user->department,
    'user_type' => $user->user_type,
    'nr_tickets_created' => $user->nr_tickets_created,
    'nr_tickets_assigned' => $user->nr_tickets_assigned
  ));
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
  handle_check_logged_in($session);
  $input = file_get_contents('php://input');
  parse_str($input, $_POST);
  handle_check_csrf($session, $_POST['csrf']);
  handle_edit_profile($session, $db, $_POST['name'], $_POST['username'], $_POST['email'], $_POST['editpass'], $_POST['oldpass'], $_POST['newpass']);
  exit();
}

http_response_code(405); // Method not allowed
echo json_encode(array('error' => 'Invalid HTTP method'));

?>
