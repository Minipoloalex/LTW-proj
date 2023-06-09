<?php
    declare(strict_types = 1); 

class Hashtag {
  public int $hashtagid;
  public string $hashtagname;

  public function __construct(int $hashtagid, string $hashtagname){
      $this->hashtagid = $hashtagid;
      $this->hashtagname = htmlentities($hashtagname);
  }

  static function getHashtags(PDO $db) : array {
      $stmt = $db->prepare('SELECT * FROM Hashtag ORDER BY HashtagName');
      $stmt->execute();
  
      $hashtags = array();
      while ($hashtag = $stmt->fetch()) {
        $hashtags[] = new Hashtag(
          intval($hashtag['HashtagID']),
          $hashtag['HashtagName']
        );
      }
  
      return $hashtags;
    }
  static function getByTicketId(PDO $db, int $ticketID) : array {
    $stmt = $db->prepare('
    SELECT *
    FROM HASHTAG_TICKET JOIN HASHTAG USING(HashtagID)
    WHERE TicketId = ?
    ');
    $stmt->execute(array($ticketID));
    $hashtags = [];
    while ($hashtag = $stmt->fetch()) {
      $hashtags[] = new Hashtag(
        intval($hashtag['HashtagID']),
        $hashtag['HashtagName'],
      );
    }
    return $hashtags;
  }
  static function addHashtagToTicket(PDO $db, int $ticketID, int $hashtagID) : void {
    $stmt = $db->prepare('
    INSERT INTO HASHTAG_TICKET (TicketID, HashtagID)
    VALUES (?, ?)
    ');
    $stmt->execute(array($ticketID, $hashtagID));
  }
  static function removeHashtagFromTicket(PDO $db, int $ticketID, int $hashtagID) {
    $stmt = $db->prepare('
    DELETE FROM HASHTAG_TICKET
    WHERE TicketID = ? AND HashtagID = ?
    ');
    $stmt->execute(array($ticketID, $hashtagID));
  }

  static function getByName(PDO $db, string $hashtagName): ?Hashtag {
    $stmt = $db->prepare('SELECT * from HASHTAG WHERE HashtagName = ?');
    $stmt->execute(array($hashtagName));
    $hashtag = $stmt->fetch();
    if ($hashtag == NULL) return NULL;
    return new Hashtag(
      intval($hashtag['HashtagID']),
      $hashtag['HashtagName']
    );
  }
  static function isValidId(PDO $db, int $hashtagID): bool {
    $stmt = $db->prepare('SELECT * from HASHTAG WHERE HashtagID = ?');
    $stmt->execute(array($hashtagID));
    $hashtag = $stmt->fetch();
    return $hashtag != NULL;
  }

  static function create(PDO $db, string $hashtagName): ?Hashtag {
    $stmt = $db->prepare('INSERT INTO HASHTAG (HashtagName) VALUES (?)');
    $stmt->execute(array($hashtagName));
    $hashtag = Hashtag::getByName($db, $hashtagName);
    if ($hashtag == NULL) return NULL;
    return $hashtag;
  }

  static function delete(PDO $db, int $hashtagID): void {
    $stmt = $db->prepare('DELETE FROM HASHTAG WHERE HashtagID = ?');
    $stmt->execute(array($hashtagID));
  }
}

?>
