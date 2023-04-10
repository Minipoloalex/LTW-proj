<?php
    declare(strict_types = 1); 

class Hashtag {
  public int $hashtagid;
  public string $hashtagname;

  public function __construct(int $hashtagid, string $hashtagname){
      $this->hashtagid = $hashtagid;
      $this->hashtagname = $hashtagname;
  }

  static function getHashtags(PDO $db) : array {
      $stmt = $db->prepare('SELECT * FROM Hashtag'); /* podia ser NAO PODE NADA SOFIA NAO FAÇAS ISSO :)) //Sergio (... (LIMIT ) .$count) e depois execute sem receber nenhum argumento -> nao é aconselhavel */
      $stmt->execute(array()); // o execute leva os argumentos do ponto de interrogação
  
      $hashtags = array();
      while ($hashtag = $stmt->fetch()) {
        $hashtags[] = new Hashtag(
          intval($hashtag['HashtagID']),
          $hashtag['HashtagName']
        );
      }
  
      return $hashtags;
    }
  static function getByTicketId(PDO $db, int $id) : array {
    $stmt = $db->prepare('
    SELECT *
    FROM HASHTAG_TICKET JOIN HASHTAG USING(HashtagID)
    WHERE TicketId = ?
    ');
    $stmt->execute(array($id));
    $hashtags = [];
    while ($hashtag = $stmt->fetch()) {
      $hashtags[] = new Hashtag(
        intval($hashtag['HashtagID']),
        $hashtag['HashtagName'],
      );
    }
    return $hashtags;
  }
  static function getByHashtagId(PDO $db, int $id) : array {
    return array(); // TODO: implement this function or getByHashtagName
  }
}

?>
