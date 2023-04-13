<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');

require_once(__DIR__ . '/../utils/session.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../pages/landing_page.php'));
}

$db = getDatabaseConnection();

$title = $_POST['title'];
$description = $_POST['description'];
$hashtags = $_POST['hashtags'];

$departmentID = intval($_POST['department']); /* Department can be null */
$priority = $_POST['priority']; /* priority can be null */

var_dump($title);
var_dump($description);
var_dump($hashtags);
var_dump($departmentID);
var_dump($priority);
var_dump($session->getId());
var_dump($session->getName());

if (empty($title) || empty($description)) {
    die(header('Location: ../pages/create_ticket.php')); /* Not tested: check if this works */
}

$userID = $session->getId();     /* session userID */
$username = $session->getName();  /* session username */


if (Ticket::existsTicket($db, $title, $userID)) {
    $session->addMessage('error', "Ticket with the same title already exists");
    die(header('Location: ../pages/create_ticket.php'));
}
/* status is "open", submit date is now : defined inside createTicket; agent is always null */
$newTicketID = Ticket::createTicket($db, $title, $userID, $priority, $hashtags, $description, $departmentID);

header('Location: ../pages/individual_ticket.php?id=' . $newTicketID);

/*
comentário no ticket é chamada AJAX (pedido) no servidor para acrescentar, dá resposta a dizer que acrescentou
assim, não é necessário dar refresh à pagina e não se perde o contexto
servidor vai à BD e responde a dizer que acrescentou
*/

?>
