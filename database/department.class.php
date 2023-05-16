<?php
declare(strict_types = 1);
class Department {
    public int $departmentId;
    public string $departmentName;
    public ?int $nrTickets;
    public ?int $nrAgents;
    public function __construct(int $departmentId, string $departmentName, int $nrTickets = null, int $nrAgents = null) {
        $this->departmentId = $departmentId;
        $this->departmentName = $departmentName;
        $this->nrTickets = $nrTickets;
        $this->nrAgents = $nrAgents;
    }

    // public function __constructOptional(int $departmentId, string $departmentName, int $nrTickets, int $nrAgents) {
    //     $this->departmentId = $departmentId;
    //     $this->departmentName = $departmentName;
    //     $this->nrTickets = $nrTickets;
    //     $this->nrAgents = $nrAgents;
    // }

    static public function getDepartments(PDO $db): array {
        $stmt = $db->prepare('SELECT DepartmentID, DepartmentName FROM DEPARTMENT');
        $stmt->execute();

        $departments = array();
        while ($department = $stmt->fetch()){
            $departments[] = new Department(
                intval($department['DepartmentID']),
                $department['DepartmentName']
            ); 
        }
        return $departments;
    }

    static public function filterDepartments(PDO $db, int $page = 1): array {
        // $query = 'SELECT DepartmentID, DepartmentName FROM DEPARTMENT ';
        $query = '
                SELECT  DepartmentID,
                        DepartmentName, 
                        CASE
                            WHEN NrTickets iSNULL THEN 0
                            ELSE NrTickets
                        END AS NrTickets,
                        CASE
                            WHEN NrAgents iSNULL THEN 0
                            ELSE NrAgents
                        END AS NrAgents
                FROM DEPARTMENT 
                LEFT JOIN (SELECT DepartmentID, COUNT(ALL) AS NrTickets FROM TICKET GROUP BY DepartmentID) 
                USING(DepartmentID)
                LEFT JOIN (SELECT DepartmentID, COUNT(ALL) AS NrAgents FROM AGENT GROUP BY DepartmentID)
                USING(DepartmentID)
                ';

        $params = array();
        $stmt1 = $db->prepare('SELECT COUNT(DISTINCT DepartmentID) as c FROM ('.$query.');');
        $stmt1->execute();
        $count = intval($stmt1->fetch()['c']);
        
        $query .= " LIMIT 12 OFFSET ?;";
        $params[] = ($page - 1) * 12;
        $stmt2 = $db->prepare($query);
        $stmt2->execute($params);
        $departments = array();
        while ($department = $stmt2->fetch()){
            $departments[] = new Department(
                intval($department['DepartmentID']),
                $department['DepartmentName'],
                intval($department['NrTickets']),
                intval($department['NrAgents'])
            );
        }
        $result['departments'] = $departments;
        $result['count'] = $count;
        return $result;
    }

    static public function getById(PDO $db, int $id): Department {
        $stmt = $db->prepare('SELECT DepartmentID, DepartmentName FROM DEPARTMENT WHERE DepartmentId = ?');
        $stmt->execute(array($id));
        $department = $stmt->fetch();

        return new Department(
            intval($department['DepartmentID']),
            $department['DepartmentName']
        );
    }
    static public function addDepartment(PDO $db, string $name) : int {
        $stmt = $db->prepare('INSERT INTO DEPARTMENT (DepartmentName) VALUES (?)');
        $stmt->execute(array($name));
        return intval($db->lastInsertId());
    }
    static public function isValidId($db, int $departmentID): bool {
        $stmt = $db->prepare('SELECT * from DEPARTMENT WHERE DepartmentID = ?');
        $stmt->execute(array($departmentID));
        $department = $stmt->fetch();
        return $department != NULL;
    }
    static function getByName(PDO $db, ?string $departmentName) : ? Department {
        if (!isset($departmentName) || empty($departmentName)) return NULL;

        $stmt = $db->prepare('SELECT * FROM DEPARTMENT WHERE DepartmentName = ?');
        $stmt->execute(array($departmentName));
        $department = $stmt->fetch();
        if ($department == NULL) return NULL;
        return new Department(
            intval($department['DepartmentID']),
            $department['DepartmentName']
        );
    }
}
?>
