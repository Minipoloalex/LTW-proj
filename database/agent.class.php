<?php
declare(strict_types=1);
require_once(__DIR__ . '/client.class.php');
class Agent extends Client
{
    public ?int $departmentid;
    public function __construct(int $id, string $name, string $username, string $password, string $email, ?int $departmentid)
    {
        parent::__construct($id, $name, $username, $password, $email);
        $this->departmentid = $departmentid;
    }
    static function getAgents(PDO $db): array
    {
        $stmt = $db->prepare('SELECT * FROM Agent JOIN Client USING (UserID)');
        $stmt->execute();

        $agents = array();
        while ($agent = $stmt->fetch()) {
            $agents[] = new Agent(
                intval($agent['UserID']),
                $agent['Name'],
                $agent['Username'],
                $agent['Password'],
                $agent['Email'],
                $agent['DepartmentID'] != NULL ? intval($agent['DepartmentID']) : NULL
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
            $agent['DepartmentID'] != NULL ? intval($agent['DepartmentID']) : NULL
        );
    }
    static function isValidId(PDO $db, int $id): bool
    {
        $stmt = $db->prepare('SELECT * FROM Agent WHERE UserID = ?');
        $stmt->execute(array($id));
        return $stmt->fetch() !== false;
    }
    static function getByDepartment(PDO $db, int $departmentID): array
    {
        $stmt = $db->prepare('SELECT * FROM AGENT JOIN CLIENT USING(UserID) WHERE DepartmentID = ?');
        $stmt->execute(array($departmentID));
        $agents = array();
        while ($agent = $stmt->fetch()) {
            $agents[] = new Agent(
                intval($agent['UserID']),
                $agent['Name'],
                $agent['Username'],
                $agent['Password'],
                $agent['Email'],
                $agent['DepartmentID'] != NULL ? intval($agent['DepartmentID']) : NULL
            );
        }
        return $agents;
    }
    static function getDepartment(PDO $db, int $agentId) : ?string
    {
        $stmt = $db->prepare('SELECT * FROM AGENT JOIN DEPARTMENT USING(DepartmentID) WHERE UserID = ?');
        $stmt->execute(array($agentId));
        $department = $stmt->fetch();
        if ($department) return $department['DepartmentName'];
        return NULL;
    }
    static function updateDepartment(PDO $db, int $userID, ?int $departmentID) {
        $stmt = $db->prepare('UPDATE AGENT SET DepartmentID = ? WHERE UserID = ?');
        $stmt->execute(array($departmentID, $userID));
    }
}

?>