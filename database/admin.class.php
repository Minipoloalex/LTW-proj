<?php
declare(strict_types = 1);
class Admin extends Agent {


    public function __construct(int $id, string $name, string $username, string $password, string $email, int $departmentid)
    {
        parent::__construct($id, $name, $username, $password, $email, $departmentid);
    }
    static function getFollowingTickets(PDO $db, int $agentID) {
        $stmt = $db->prepare('SELECT * FROM FOLLOWING WHERE AgentID = ?');
        $stmt->execute(array($agentID));
        $tickets = array();
        while ($row = $stmt->fetch()) {
            $tickets[] = Ticket::getByID($db, intval($row['TicketID']));
        }
        return $tickets;
    }
}

?>
