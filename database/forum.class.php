<?php
declare(strict_types = 1);

class Forum {
    public int $forumId;
    public string $question;
    public ?string $answer;
    public int $displayed;
    public function __construct(int $forumId, string $question, ?string $answer, int $displayed) {
        $this->forumId = $forumId;
        $this->question = $question;
        $this->answer = $answer;
        $this->displayed = $displayed;
    }

    static function getFaqs(PDO $db, int $count): array {
        $stmt = $db->prepare('SELECT * FROM FORUM LIMIT ? ');
        $stmt->execute(array($count));

        $faqs = array();
        while ($faq = $stmt->fetch()) {
            $faqs[] = new Forum(
                intval($faq['ForumID']),
                $faq['Question'],
                $faq['Answer'],
                intval($faq['Displayed'])
            );
        }
        return $faqs;
    }

    static function addFaq(PDO $db, string $question): Forum {
        // $question = trim($question);
        $stmt = $db->prepare('INSERT INTO FORUM (Question) VALUES (?)');
        $stmt->execute(array($question));

        return new Forum(
            intval($db->lastInsertId()),
            $question,
            null,
            0
        );
    }

    static function getFaqWithoutAnswer(PDO $db, int $count): array {
        $stmt = $db->prepare('SELECT * FROM FORUM WHERE Answer IS NULL LIMIT ? ');
        $stmt->execute(array($count));

        $faqs = array();
        while ($faq = $stmt->fetch()) {
            $faqs[] = new Forum(
                intval($faq['ForumID']),
                $faq['Question'],
                $faq['Answer'],
                0
            );
        }
        return $faqs;
    }
}

?>
