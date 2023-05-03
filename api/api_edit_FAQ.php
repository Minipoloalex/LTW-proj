<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/forum.class.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');

$session = new Session();
$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'User not logged in'));
        exit();
    }

    // verify if user is admin
    if (Client::getType($db, $session->getId()) !== 'Admin') {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'User not authorized'));
        exit();
    }

    // verify if all parameters are set
    if (!isset($_POST['question']) || !isset($_POST['answer'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Missing parameters'));
        exit();
    }

    // verify if parameters are valid
    if (!is_valid_string($_POST['question']) || !is_valid_string($_POST['answer'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid parameters'));
        exit();
    }
    if (!is_valid_id($_POST['id'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid parameters'));
        exit();
    }

    /* ==================== */
    // !NOTE: verify duplicate faqs
    // verify if question exists
    // $question = $_POST['question'];
    // $answer = $_POST['answer'];
    // $faq = Forum::getFaq($db, $question, $answer);

    // if ($faq !== NULL) {
    //     http_response_code(500); // Internal server error
    //     echo json_encode(array('error' => 'Found similar FAQ on database'));
    //     exit();
    // }

    /* ==================== */
    $id = $_POST['id'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $faq = Forum::updateFaq($db, $question, $answer, $id);

    // echo json_encode(array('success' => 'FAQ updated successfully', 'id' => $faq->forumId, 'question' => $faq->question, 'answer' => $faq->answer, 'displayed' => $faq->displayed));
    exit();
}

// verify if DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // TODO: receive id
    // verify if user is logged in
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'User not logged in'));
        exit();
    }

    // verify if user is admin
    if (Client::getType($db, $session->getId()) === "Client") {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'User not authorized'));
        exit();
    } 

    // verify if all parameters are set
    if (!isset($_GET['question']) || !isset($_GET['answer'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Missing parameters'));
        exit();
    }

    // verify if parameters are valid
    if (!is_valid_string($_GET['question']) || !is_valid_string($_GET['answer'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid parameters'));
        exit();
    }

    // verify if question exists
    $question = $_GET['question'];
    $answer = $_GET['answer'];
    $faq = Forum::getFaq($db, $question, $answer);

    if (!$faq) {
        http_response_code(500); // Internal server error
        echo json_encode(array('error' => 'Failed to find FAQ on database'));
        exit();
    }

    // delete FAQ
    $faq = Forum::deleteFaq($db, $question, $answer);
    echo json_encode(array('success' => 'FAQ deleted successfully'));
    exit();
}

echo json_encode(array('error' => 'Invalid request method'));

?>
