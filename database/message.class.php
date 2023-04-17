<?php
declare(strict_types=1);

require_once(__DIR__ . '/ticket.class.php');

class Message {
    public int $id;
    public string $text;
    public int $userID;
    public int $date;
    public function __construct(int $messageID, string $text, int $userID, int $date) {
        $this->id = $messageID;
        $this->text = $text;
        $this->userID = $userID;
        $this->date = $date;
    }
    static function getByTicket(PDO $db, int $ticketID) : array {
        $stmt = $db->prepare('SELECT * FROM MESSAGE WHERE TicketID = ? ORDER BY TimeStamp ASC');
        $stmt->execute(array($ticketID));
        $messages = [];
        while ($message = $stmt->fetch()) {
            $messages[] = new Message(
                intval($message["MessageID"]),
                $message["MessageText"],
                $message["UserID"],
                $message["TimeStamp"],
            );
        }
        return $messages;
    }
}

?>
