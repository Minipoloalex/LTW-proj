<?php

    declare(strict_types = 1); 

    class Hashtag {
        public int $hashtagid;
        public string $hastagname;

    public function __construct(int $hashtagid, string $hastagname){
        $this->hashtagid = $hashtagid;
        $this->hastagname = $hastagname;
    }

    public function getHashtags(PDO $db) : array {
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

    }
     
    ?>