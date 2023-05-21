<?php
declare(strict_types=1);

class Forum
{
    public int $forumId;
    public string $question;
    public ?string $answer;
    public int $displayed;
    public function __construct(int $forumId, string $question, ?string $answer, int $displayed)
    {
        $this->forumId = $forumId;
        $this->question = htmlentities($question);
        $this->answer = $answer == NULL ? NULL : htmlentities($answer);
        $this->displayed = $displayed;
    }

    static function getDisplayedFaqs(PDO $db): array {
        $stmt = $db->prepare('SELECT * FROM FORUM WHERE Displayed = 1');
        $stmt->execute();

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
    static function getById(PDO $db, int $forumID): ?Forum {
        $stmt = $db->prepare('SELECT * FROM FORUM WHERE ForumID = ?');
        $stmt->execute(array($forumID));

        $faq = $stmt->fetch();
        if (!$faq) {
            return null;
        }

        return new Forum(
            intval($faq['ForumID']),
            $faq['Question'],
            $faq['Answer'],
            intval($faq['Displayed'])
        );
    }

    static function getFaqs(PDO $db, int $count): array
    {
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
    static function addFaq(PDO $db, string $question): Forum
    {
        $stmt = $db->prepare('INSERT INTO FORUM (Question) VALUES (?)');
        $stmt->execute(array($question));

        return new Forum(
            intval($db->lastInsertId()),
            $question,
            null,
            0
        );
    }
    static function updateFaq(PDO $db, string $question, string $answer, string $forumID): Forum
    {
        $stmt = $db->prepare('UPDATE FORUM SET Question = ?, Answer = ? WHERE ForumID = ?');

        $id = intval($forumID);
        $stmt->execute(array($question, $answer, $id));

        return new Forum(
            intval($forumID),
            $question,
            $answer,
            1
        );
    }
    static function deleteFaq(PDO $db, string $forumID): bool
    {
        $stmt = $db->prepare('DELETE FROM FORUM WHERE ForumID = ?');
        $stmt->execute(array($forumID));
        return $stmt->rowCount() > 0;
    }

    static function alreadyExists(PDO $db, string $question): bool
    {
        $stmt = $db->prepare('SELECT * FROM FORUM WHERE Question = ?');
        $stmt->execute(array($question));

        $faq = $stmt->fetch();
        if (!$faq) {
            return false;
        }
        return true;
    }

    static function updateDisplayed(PDO $db, string $displayed, string $forumID): bool
    {
        $stmt = $db->prepare('UPDATE FORUM SET Displayed = ? WHERE ForumID = ?');
        $stmt->execute(array($displayed, $forumID));

        return true;
    }
}

?>