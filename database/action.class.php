<?php
declare(strict_types=1);

require_once(__DIR__ . '/tickets.class.php');

class Action {
    public int $id;
    public string $type;
    public int $date;
    public function __construct(int $actionID, string $type, int $date) {
        $this->id = $actionID;
        $this->type = $type;
        $this->date = $date;
    }
    static function getByTicket(PDO $db, int $ticketID) : array {
        $stmt = $db->prepare('SELECT * FROM ACTION WHERE TicketID = ?');
        $stmt->execute(array($ticketID));
        $actions = [];
        while ($action = $stmt->fetch()) {
            $actions[] = new Action(
                intval($action["ActionID"]),
                $action["Type"],
                $action["TimeStamp"],
            );
        }
        return $actions;
    }
}

?>
