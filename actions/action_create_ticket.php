<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');

require_once(__DIR__ . '/../utils/session.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../pages/main_page.php'));
}

$db = getDatabaseConnection();

$title = $_POST['title'];
$description = $_POST['description'];
$hashtags = $_POST['hashtags'] ?? array();

$departmentID = empty($_POST['department']) ? NULL : intval($_POST['department']); /* Department can be null */
$priority = $_POST['priority']; /* priority can be null (atm is always null) */

if (empty($title) || empty($description)) {
    die(header('Location: ../pages/create_ticket.php')); /* Not tested: check if this works */
}

$userID = $session->getId();     /* session userID */
$username = $session->getName();  /* session username */


if (Ticket::existsTicket($db, $title, $userID)) {
    $session->addMessage('error', "Ticket with the same title already exists");
    die(header('Location: ../pages/create_ticket.php'));
}

/* status is "open", submit date is now, agent always null: defined inside createTicket */
$id = Ticket::createTicket($db, $title, $userID, $priority, $hashtags, $description, $departmentName);
// $session->addMessage('success', "Ticket created successfully");

header('Location: ../pages/individual_ticket.php?id=' . $id);

/*
comentário no ticket é chamada AJAX (pedido) no servidor para acrescentar, dá resposta a dizer que acrescentou
assim, não é necessário dar refresh à pagina e não se perde o contexto
servidor vai à BD e responde a dizer que acrescentou
*/

?>
