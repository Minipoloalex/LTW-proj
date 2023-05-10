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
        $this->question = $question;
        $this->answer = $answer;
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

    static function getFaq(PDO $db, string $question, string $answer): ?Forum
    {
        $stmt = $db->prepare('SELECT * FROM FORUM WHERE Question = ? AND Answer = ?');
        $stmt->execute(array($question, $answer));

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

    static function addFaq(PDO $db, string $question): Forum
    {
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

    static function getFaqWithoutAnswer(PDO $db, int $count): array
    {
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

    static function updateFaq(PDO $db, string $question, string $answer, string $forumID): Forum
    {
        $stmt = $db->prepare('UPDATE FORUM SET Question = ?, Answer = ? WHERE ForumID = ?');

        $id = intval($forumID);
        $stmt->execute(array($question, $answer, $id));

        // print_r('UPDATE FORUM SET Question = ?, Answer = ? WHERE ForumID = ?');

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
        // $id = intval($forumID);
        $stmt->execute(array($forumID));

        return true;
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
        // // !NOTE: This ensures that the question is not the same as the one being updated
        // foreach ($faq as $f) {
        //     if ($f['ForumID'] != intval($forumID)) {
        //         return true;
        //     }
            
        // }
        // return false;
    }

    static function updateDisplayed(PDO $db, string $displayed, string $forumID): bool
    {
        $stmt = $db->prepare('UPDATE FORUM SET Displayed = ? WHERE ForumID = ?');
        $stmt->execute(array($displayed, $forumID));

        return true;
    }
}

?>