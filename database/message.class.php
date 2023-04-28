<?php
declare(strict_types=1);

require_once(__DIR__ . '/ticket.class.php');

class Message {
    public int $id;
    public string $text;
    public int $userID;
    public string $username;
    public int $date;
    public function __construct(int $messageID, string $text, int $userID, string $username, int $date) {
        $this->id = $messageID;
        $this->text = $text;
        $this->userID = $userID;
        $this->username = $username;
        $this->date = $date;
    }
    static function getByTicket(PDO $db, int $ticketID) : array {
        $stmt = $db->prepare('SELECT * FROM MESSAGE JOIN CLIENT USING(UserID) WHERE TicketID = ? ORDER BY TimeStamp ASC');
        $stmt->execute(array($ticketID));
        $messages = [];
        while ($message = $stmt->fetch()) {
            $messages[] = new Message(
                intval($message["MessageID"]),
                $message["MessageText"],
                intval($message["UserID"]),
                $message["Username"],
                intval($message["TimeStamp"]),
            );
        }
        return $messages;
    }
    static function addMessage(PDO $db, int $userID, int $ticketID, string $messageText) : Message {
        $date = time();
        $stmt = $db->prepare('INSERT INTO MESSAGE (TicketID, UserID, MessageText, TimeStamp) VALUES (?, ?, ?, ?)');
        $stmt->execute(array($ticketID, $userID, $messageText, $date));
        return new Message(
            intval($db->lastInsertId()),
            $messageText,
            $userID,
            Client::getById($db, $userID)->username,
            $date,
        );
    }
}

?>
