<?php
declare(strict_types = 1);
class Department {
    public int $departmentId;
    public string $departmentName;
    public function __construct(int $departmentId, string $departmentName) {
        $this->departmentId = $departmentId;
        $this->departmentName = $departmentName;
    }

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
