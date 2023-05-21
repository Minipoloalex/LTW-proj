<?php
declare(strict_types=1);
class Image {
    public int $id;
    public function __construct(int $id) {
        $this->id = $id;
    }
    static function getImage(PDO $db, int $imageID) : ?Image {
        $stmt = $db->prepare('SELECT * FROM IMAGE WHERE ImageID = ?');
        $stmt->execute(array($imageID));
        $image = $stmt->fetch();
        if (!$image) return null;
        return new Image(
            intval($image['ImageID']),
        );
    }

    static function addImage(PDO $db) : Image {
        $stmt = $db->prepare('INSERT INTO IMAGE (ImageID) VALUES(NULL)');
        $stmt->execute();
        return new Image(
            intval($db->lastInsertId()),
        );
    }
}
?>
