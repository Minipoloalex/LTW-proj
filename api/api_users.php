<?php
declare(strict_types = 1);

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
    
    

    error_log('HERE'.implode($user_type));
    error_log('HERE'.$departments);

    $data = Client::filter($db, $departments, $user_type, $page);
    http_response_code(200); // OK
    echo json_encode($data);
  } else {
    $data = Ticket::filter($db);
    http_response_code(200); // OK
    echo json_encode($data);
  }
  
} 



?>