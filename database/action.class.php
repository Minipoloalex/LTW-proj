<?php
declare(strict_types=1);

require_once(__DIR__ . '/ticket.class.php');

class Action {
    public int $id;
    public string $username;
    public string $type;
    public int $date;
    public function __construct(int $actionID, string $username, string $type, int $date) {
        $this->id = $actionID;
        $this->username = $username;
        $this->type = $type;
        $this->date = $date;
    }
    static function getByTicket(PDO $db, int $ticketID) : array {
        $stmt = $db->prepare('SELECT * FROM ACTION JOIN CLIENT USING(UserID) WHERE TicketID = ? ORDER BY TimeStamp ASC');
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
}

?>
