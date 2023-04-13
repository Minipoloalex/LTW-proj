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
$hashtags = $_POST['hashtags'] ?? array();
$departmentID = empty($_POST['department']) ? NULL : intval($_POST['department']); /* Department can be null */

$priority = $_POST['priority']; /* priority can be null (atm it is always null) */

var_dump($title);
var_dump($description);
var_dump($hashtags);
var_dump($departmentID);
var_dump($priority);
var_dump($session->getId());
var_dump($session->getName());

if (empty($title) || empty($description)) {
    die(header('Location: ../pages/create_ticket.php'));
}

$userID = $session->getId();     /* session userID */
$username = $session->getName();  /* session username */


if ($oldTicketID = Ticket::existsTicket($db, $title, $userID)) { // if does not exist -> old_id is NULL (type ?int)
    $session->addMessage('error', "Ticket with the same title already exists");
    
    // this can be a bit weird, since the ticket isn't being created, but the user can feel like it is
    die(header('Location: ../pages/individual_ticket.php?id=' . $oldTicketID));
    
    // die(header('Location: ../pages/create_ticket.php'));    // could send to the ticket page of the already existent ticket
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
