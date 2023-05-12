<?php
declare(strict_types=1);
class Image {
    public int $id;
    public string $title;
    public function __construct(int $id, string $title) {
        $this->id = $id;
        $this->title = $title;
    }
    static function getImageInfo(PDO $db, int $imageID) : ?Image {
        $stmt = $db->prepare('SELECT * FROM IMAGE WHERE ImageID = ?');
        $stmt->execute(array($imageID));
        $image = $stmt->fetch();
        if (!$image) return null;
        return new Image(
            intval($image['ImageID']),
            $image['Title'],
        );
    }

    static function addImage(PDO $db, string $title) : Image {
        $stmt = $db->prepare('INSERT INTO IMAGE (Title) VALUES (?)');
        $stmt->execute(array($title));
        return new Image(
            intval($db->lastInsertId()),
            $title,
        );
    }
}

?>