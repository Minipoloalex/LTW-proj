<?php
declare(strict_types=1);

require_once(__DIR__ . '/ticket.class.php');

class Action {
    public int $id;
    public string $username;
    public string $text;
    public int $date;
    public function __construct(int $actionID, string $username, string $text, int $date) {
        $this->id = $actionID;
        $this->username = $username;
        $this->text = $text;    // this text is already sanitized by us
        $this->date = $date;
    }
    static function getByTicket(PDO $db, int $ticketID) : array {
        $stmt = $db->prepare('SELECT * FROM ACTION JOIN CLIENT USING(UserID) WHERE TicketID = ? ORDER BY TimeStamp DESC');
        $stmt->execute(array($ticketID));
        $actions = [];
        while ($action = $stmt->fetch()) {
            $actions[] = new Action(
                intval($action["ActionID"]),
                $action["Username"],
                $action["Type"],
                intval($action["TimeStamp"]),
            );
        }
        return $actions;
    }
    static function addUserAction(PDO $db, int $userID, int $ticketID, string $type, int $date): Action {
        $client = Client::getByID($db, $userID);
        $actionMessage = '';
        switch ($type) {
            case 'create':
                $actionMessage = $client->username . ' created this ticket';
                break;
            case 'reopen':
                $actionMessage = $client->username . ' reopened this ticket';
                break;
            case 'close':
                $actionMessage = $client->username . ' closed this ticket';
                break;
            default:
                throw new Exception('Invalid action type');
        }
        $stmt = $db->prepare('INSERT INTO ACTION (UserID, TicketID, Type, TimeStamp) VALUES (?, ?, ?, ?)');
        $stmt->execute(array($userID, $ticketID, $actionMessage, $date));
        return new Action(
            intval($db->lastInsertId()),
            $client->username,
            $actionMessage,
            $date,
        );
    }
    static function addEditAction(PDO $db, int $userID, int $ticketID, string $type, int $date, ?int $agentID): Action {
        $client = Client::getByID($db, $userID);
        $actionMessage = '';
        $agent = '';
        if ($agentID) $agent = Agent::getById($db, $agentID);
        switch ($type) {
            case 'edit':
                $actionMessage = $client->username . ' edited this ticket\'s information';
                break;
            case 'assign':
                $actionMessage = $client->username . ' edited this ticket\'s information and assigned the ticket to ' . $agent->username;
                break;
            case 'unassign':
                $actionMessage = $client->username . ' edited this ticket\'s information and unassigned the ticket from ' . $agent->username;
                break;
            default:
                throw new Exception('Invalid action type');
        }

        $stmt = $db->prepare('INSERT INTO ACTION (UserID, TicketID, Type, TimeStamp) VALUES (?, ?, ?, ?)');
        $stmt->execute(array($userID, $ticketID, $actionMessage, $date));
        return new Action(
            intval($db->lastInsertId()),
            $client->username,
            $actionMessage,
            $date,
        );
    }
}

?>
