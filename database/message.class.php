<?php
declare(strict_types=1);

require_once(__DIR__ . '/ticket.class.php');

class Message {
    public int $id;
    public int $ticketID;
    public string $text;
    public int $userID;
    public string $username;
    public int $date;
    public ?int $imageID;
    public function __construct(int $messageID, int $ticketID, string $text, int $userID, string $username, int $date, ?int $imageID = null) {
        $this->id = $messageID;
        $this->ticketID = $ticketID;
        $this->text = $text;
        $this->userID = $userID;
        $this->username = $username;
        $this->date = $date;
        $this->imageID = $imageID;
    }
    static function getByTicket(PDO $db, int $ticketID) : array {
        $stmt = $db->prepare('SELECT * FROM MESSAGE JOIN CLIENT USING(UserID) WHERE TicketID = ? ORDER BY TimeStamp ASC');
        $stmt->execute(array($ticketID));
        $messages = [];
        while ($message = $stmt->fetch()) {
            $messages[] = new Message(
                intval($message["MessageID"]),
                intval($message["TicketID"]),
                $message["MessageText"],
                intval($message["UserID"]),
                $message["Username"],
                intval($message["TimeStamp"]),
                $message['ImageID'] != null ? intval($message["ImageID"]) : null,
            );
        }
        return $messages;
    }
    static function addMessage(PDO $db, int $userID, int $ticketID, string $messageText, ?int $imageID = null) : Message {
        $date = time();
        $stmt = $db->prepare('INSERT INTO MESSAGE (TicketID, UserID, MessageText, TimeStamp, ImageID) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute(array($ticketID, $userID, $messageText, $date, $imageID));
        return new Message(
            intval($db->lastInsertId()),
            $ticketID,
            $messageText,
            $userID,
            Client::getById($db, $userID)->username,
            $date,
            $imageID,
        );
    }
    static function getById(PDO $db, int $messageID): ?Message {
        $stmt = $db->prepare('SELECT * FROM MESSAGE JOIN CLIENT USING(UserID) WHERE MessageID = ?');
        $stmt->execute(array($messageID));
        $message = $stmt->fetch();
        if (!$message) return null;
        return new Message(
            intval($message["MessageID"]),
            intval($message["TicketID"]),
            $message["MessageText"],
            intval($message["UserID"]),
            $message["Username"],
            intval($message["TimeStamp"]),
            $message['ImageID'] != null ? intval($message["ImageID"]) : null,
        );
    }
}

?>
