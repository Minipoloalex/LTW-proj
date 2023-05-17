<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');

require_once(__DIR__ . '/../database/client.class.php');


$session = new Session();
$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $page = intval($_GET['page']);

  if (isset($page)) {
    $d = $_GET['department'];
    $departments = ($d == "") ? [] : explode(',', $d);
    $u = $_GET['user_type'];
    $user_type = ($u == "") ? [] : explode(',', $u);



    error_log('HERE' . implode($user_type));
    error_log('HERE' . $departments);

    $data = Client::filter($db, $departments, $user_type, $page);
    http_response_code(200); // OK
    echo json_encode($data);
  } else {
    $data = Client::filter($db);
    http_response_code(200); // OK
    echo json_encode($data);
  }

}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
  // error_log('ID IN API 1: ' . $_GET['id']);
  
  $id = intval($_GET['id']);
  $new_user_type = $_GET['user_type'];
  $department = ($_GET['department'] === '' ? NULL : intval($_GET['department']));
  error_log('department IN API: ' . $department);
  $curr_user_type = Client::getType($db, $id);

  if (isset($new_user_type)) {
    if ($curr_user_type === $new_user_type) {
      // User type is already the same, no need to make any changes
      http_response_code(200); // OK
      echo json_encode(array('user_type' => $curr_user_type));
      exit();
    }

    if ($curr_user_type === 'Client') {
      if ($new_user_type === 'Agent') {
        Client::upgradeToAgent($db, $id);
      } elseif ($new_user_type === 'Admin') {
        Client::upgradeToAdminFromClient($db, $id);
      }
    } elseif ($curr_user_type === 'Agent') {
      if ($new_user_type === 'Client') {
        Client::demoteToClient($db, $id);
      } elseif ($new_user_type === 'Admin') {
        Client::upgradeToAdminFromAgent($db, $id);
      }
    } elseif ($curr_user_type === 'Admin') {
      if ($new_user_type === 'Client') {
        // // Demoting an Admin to a Client is not allowed
        // http_response_code(400); // Bad Request
        // echo json_encode(array('error' => 'Invalid user type change'));
        // exit();
        Client::demoteToClient($db, $id);
      } elseif ($new_user_type === 'Agent') {
        Client::demoteToAgent($db, $id);
      }
    }
    
  }
  else{
    Client::changeDepartment($db, $id, $department);
    
  }

  $user =  CLient::getByIdExpanded($db, $id);
  http_response_code(200); // OK
  echo json_encode($user);
  exit();

}