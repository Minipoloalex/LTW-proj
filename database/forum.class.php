<?php
declare(strict_types = 1);

class Forum {
    public int $forumId;
    public string $question;
    public string $answer;
    public function __construct(int $forumId, string $question, string $answer) {
        $this->forumId = $forumId;
        $this->question = $question;
        $this->answer = $answer;
    }
    static function getFaqs(PDO $db, int $count): array {
        $stmt = $db->prepare('SELECT * FROM FORUM LIMIT ? ');
        $stmt->execute(array($count));

        $faqs = array();
        while ($faq = $stmt->fetch()) {
            $faqs[] = new Forum(
                intval($faq['ForumID']),
                $faq['Question'],
                $faq['Answer']
            );
        }
        return $faqs;
    }
}

?>
