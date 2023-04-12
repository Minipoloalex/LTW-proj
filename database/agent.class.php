<?php
declare(strict_types=1);
require_once(__DIR__ . '/client.class.php');
class Agent extends Client
{
    public int $departmentid;

    public function __construct(int $id, string $name, string $username, string $password, string $email, int $departmentid)
    {
        parent::__construct($id, $name, $username, $password, $email);
        $this->departmentid = $departmentid;
    }

    static function searchAgents(PDO $db, string $search, int $count): array
    {
        $stmt = $db->prepare('SELECT * FROM Agent JOIN Client USING (UserID) WHERE Name LIKE ? LIMIT ?');
        $stmt->execute(array($search . '%', $count));

        $agents = array();
        while ($agent = $stmt->fetch()) {
            $agents[] = new Agent(
                intval($agent['UserID']),
                $agent['Name'],
                $agent['Username'],
                $agent['Password'],
                $agent['Email'],
                $agent['DepartmentID']
            );
        }

        return $agents;
    }

    static function getAgents(PDO $db, int $count): array
    {
        $stmt = $db->prepare('SELECT * FROM Agent JOIN Client USING (UserID) LIMIT ? ');
        $stmt->execute(array($count));

        $agents = array();
        while ($agent = $stmt->fetch()) {
            $agents[] = new Agent(
                intval($agent['UserID']),
                $agent['Name'],
                $agent['Username'],
                $agent['Password'],
                $agent['Email'],
                $agent['DepartmentID']
            );
        }
        return $agents;
    }


    static function getById(PDO $db, int $id): Agent
    {
        $stmt = $db->prepare('SELECT * FROM Agent JOIN Client USING (UserID) WHERE UserID = ?');
        $stmt->execute(array($id));

        $agent = $stmt->fetch();

        return new Agent(
            intval($agent['UserID']),
            $agent['Name'],
            $agent['Username'],
            $agent['Password'],
            $agent['Email'],
            intval($agent['DepartmentID'])
        );
    }



    static function getByDepartment(PDO $db, int $agentId): array
    {
        $stmt = $db->prepare('SELECT * FROM AGENT JOIN CLIENT USING(UserID) WHERE DepartmentID = ?');
        $stmt->execute(array($agentId));
        $agents = array();
        while ($agent = $stmt->fetch()) {
            $agents[] = new Agent(
                intval($agent['UserID']),
                $agent['Name'],
                $agent['Username'],
                $agent['Password'],
                $agent['Email'],
                intval($agent['DepartmentID'])
            );
        }
        return $agents;
    }

    static function getDepartment(PDO $db, int $agentId) : string
    {
        $stmt = $db->prepare('SELECT * FROM AGENT JOIN DEPARTMENT USING(DepartmentID) WHERE UserID = ?');
        $stmt->execute(array($agentId));
        $department = $stmt->fetch();
        return $department['DepartmentName'];
    }
}

?>