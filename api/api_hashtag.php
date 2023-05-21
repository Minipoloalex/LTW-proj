<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');

require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/handlers/api_common.php');

$session = new Session();
$db = getDatabaseConnection();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    handle_check_logged_in($session);
    if (!is_valid_string($_GET['hashtagName'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Missing hashtag parameter'));
        exit();
    }
    $hashtag = Hashtag::getByName($db, $_GET['hashtagName']);
    if ($hashtag == NULL) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Hashtag does not exist'));
        exit();
    }
    http_response_code(200); // OK
    echo json_encode(array(
        'success' => 'Hashtag exists',
        'hashtagID' => $hashtag->hashtagid,
    ));
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_check_logged_in($session);
    handle_check_csrf($session, $_POST['csrf']);
    handle_check_admin($session, $db);
    if (!is_valid_string($_POST['hashtagName'])) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Missing hashtag parameter'));
        exit();
    }
    error_log($_POST['hashtagName']);
    $hashtagName = transform_hashtag($_POST['hashtagName']);
    error_log($hashtagName);
    if (!is_valid_hashtag_name($hashtagName)) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Invalid hashtag name. Must have at most 20 characters'));
        exit();
    }
    $hashtag = Hashtag::getByName($db, $hashtagName);
    if ($hashtag !== NULL) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Hashtag already exists'));
        exit();
    }
    
    $hashtag = Hashtag::create($db, $hashtagName);
    if ($hashtag == NULL) {
        http_response_code(500); // Internal server error
        echo_json_csrf($session, array('error' => 'Failed to create hashtag'));
        exit();
    }
    http_response_code(200); // OK
    echo_json_csrf($session, array(
        'success' => 'Hashtag created',
        'hashtagID' => $hashtag->hashtagid,
        'hashtagName' => $hashtag->hashtagname
    ));
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    handle_check_logged_in($session);
    handle_check_csrf($session, $_GET['csrf']);
    handle_check_admin($session, $db);

    if (!is_valid_string($_GET['hashtagName'])) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Missing hashtag parameter'));
        exit();
    }
    $hashtag = Hashtag::getByName($db, $_GET['hashtagName']);
    if ($hashtag == NULL) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Hashtag does not exist'));
        exit();
    }
    $hashtag->delete($db, $hashtag->hashtagid);
    http_response_code(200); // OK
    echo_json_csrf($session, array(
        'success' => 'Hashtag deleted',
    ));
    exit();
}

http_response_code(405); // Method not allowed
echo json_encode(array('error' => 'Method not allowed'));
exit();

?>
