<?php
declare(strict_types=1);

require_once(__DIR__ . '/tickets.class.php');

class Message {
    public int $id;
    public string $text;
    public int $ticketID;
    public function __construct(int $messageID, string $text, int $ticketID) {
        $this->id = $messageID;
        $this->text = $text;
        $this->ticketID = $ticketID;
    }
    static function getByTicket(PDO $db, int $ticketID) : array {
        $stmt = $db->prepare('SELECT * FROM MESSAGE WHERE TicketID = ?');
        $stmt->execute(array($ticketID));
        $messages = [];
        while ($message = $stmt->fetch()) {
            $messages[] = new Message(
                intval($message["MessageID"]),
                $message["Ticket"],
                $message["TicketID"],
            );
        }
        return $messages;
    }
}

?>
