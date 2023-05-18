<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/forum.class.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/handlers/api_common.php');

$session = new Session();
$db = getDatabaseConnection();

/*add faq*/
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    handle_check_logged_in($session);
    handle_check_csrf($session, $_POST['csrf']);

    if (!is_valid_string($_POST['question'])) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Missing question parameter.'));
        exit();
    }

    $question = $_POST['question'];

    if (Forum::alreadyExists($db, $question)) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'A similar FAQ was found.'));
        exit();
    }
    $faq = Forum::addFaq($db, $question);

    if (!$faq) {
        http_response_code(500); // Internal server error
        echo_json_csrf($session, array('error' => 'Failed to add FAQ.'));
        exit();
    }

    $type = Client::getType($db, $session->getId());

    echo json_encode(array(
        'success' => 'Your question was successfully sent.',
        'id' => $faq->forumId,
        'question' => $faq->question,
        'answer' => $faq->answer,
        'displayed' => $faq->displayed,
        'type' => $type
    ));
    exit();
}

/*edit faq and answer faq*/
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    handle_check_logged_in($session);
    $input = file_get_contents('php://input');
    parse_str($input, $_GET);
    handle_check_csrf($session, $_GET['csrf']);

    if (Client::getType($db, $session->getId()) !== 'Admin') {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'You are not authorized.'));
        exit();
    }

    // verify if all parameters are set
    if (!isset($_GET['question']) || !isset($_GET['answer'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Missing parameters.'));
        exit();
    }

    // verify if parameters are valid
    if (!is_valid_string($_GET['question']) || !is_valid_string($_GET['answer'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid question or answer parameters.'));
        exit();
    }
    if (!is_valid_id($_GET['id'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid faq.'));
        exit();
    }

    /* ==================== */
    // !NOTE: verify duplicate faqs
    // verify if question exists
    // $question = $_GET['question'];
    // $answer = $_GET['answer'];
    // $faq = Forum::getFaq($db, $question, $answer); 

    // if ($faq !== NULL) {
    //     http_response_code(500); // Internal server error
    //     echo json_encode(array('error' => 'Found similar FAQ on database'));
    //     exit();
    // }

    /* ==================== */
    $id = $_GET['id'];
    $question = $_GET['question'];
    $answer = $_GET['answer'];

    
    // if (Forum::alreadyExists($db, $question, $id)) {
    //     http_response_code(500); // Internal server error
    //     echo json_encode(array('error' => 'Found similar FAQ on database'));
    //     exit();
    // }

    $faq = Forum::updateFaq($db, $question, $answer, $id);

    echo json_encode(array('success' => 'FAQ was updated successfully.', 'id' => $faq->forumId, 'question' => $faq->question, 'answer' => $faq->answer, 'displayed' => $faq->displayed));
    exit();
}

/*delete faq*/
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    handle_check_logged_in($session);
    handle_check_csrf($session, $_GET['csrf']);

    if (Client::getType($db, $session->getId()) === "Client") {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'User not authorized'));
        exit();
    }
    $id = $_GET['id'];
    $faq = Forum::deleteFaq($db, $id);
    echo json_encode(array('success' => 'FAQ deleted successfully.'));
    exit();
}

/*change display attribute*/
if ($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'You are not logged in.'));
        exit();
    }
    // TODO: CSRF

    if (Client::getType($db, $session->getId()) === 'Client') {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'You are not authorized.'));
        exit();
    }

    // verify if all parameters are set
    if (!isset($_GET['displayed'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'There are missing parameters.'));
        exit();
    }

    // verify is displayed is valid (0 or 1)
    if (!($_GET['displayed'] === '0' || $_GET['displayed'] === '1')) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid parameters.'));
        exit();
    }
    
    $displayed = $_GET['displayed'];
    $id = $_GET['id'];

    if (!Forum::updateDisplayed($db, $displayed, $id)){
        http_response_code(500); // Internal server error
        echo json_encode(array('error' => 'Failed to update FAQ.'));
        exit();
    }

    echo json_encode(array('success' => 'FAQ was updated successfully.'));
    exit();
}

/* get a specific faq by id, must be displayed/accessible to clients */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'User not logged in'));
        exit();
    }
    if (!is_valid_faq_id($db, $_GET['id'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid faq parameter'));
        exit();
    }
    $id = intval($_GET['id']);
    $faq = Forum::getById($db, $id);
    if (!$faq) {
        http_response_code(500); // Internal server error
        echo json_encode(array('error' => 'Failed to get FAQ from database'));
        exit();
    }
    if ($faq->displayed == 0) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'FAQ requested is not displayed'));
        exit();
    }

    echo json_encode(array(
        'success' => 'FAQ retrieved successfully',
        'question' => $faq->question,
        'answer' => $faq->answer,
    ));
    exit();
}
echo json_encode(array('error' => 'Invalid request method'));
?>

